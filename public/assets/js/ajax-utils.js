/**
 * AJAX Utilities for Admin Panel (Legacy Support)
 * This file provides backward compatibility
 * New code should use app.js utilities
 * 
 * @deprecated Use app.js for new implementations
 */

(function($) {
    'use strict';

    // Check if app.js is loaded (it handles CSRF setup)
    if (typeof window.ajaxRequest === 'undefined') {
        // Setup CSRF token for all AJAX requests (fallback)
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
    }

    /**
     * Show loading state on button
     */
    function showLoading(button) {
        const $btn = $(button);
        $btn.data('original-html', $btn.html());
        $btn.prop('disabled', true);
        $btn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Loading...');
    }

    /**
     * Hide loading state on button
     */
    function hideLoading(button) {
        const $btn = $(button);
        $btn.prop('disabled', false);
        if ($btn.data('original-html')) {
            $btn.html($btn.data('original-html'));
        }
    }

    /**
     * Show toast notification using global toast system
     * Uses app.js ajaxAlert if available, otherwise falls back
     */
    function showToast(message, type = 'success') {
        if (typeof window.ajaxAlert !== 'undefined') {
            window.ajaxAlert.show(type, message);
        } else if (typeof window.ToastNotification !== 'undefined') {
            window.ToastNotification.show(message, type);
        } else if (typeof Swal !== 'undefined') {
            // Fallback if global toast system not loaded
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: type,
                title: message
            });
        } else {
            // Final fallback to alert
            alert(message);
        }
    }

    /**
     * Handle AJAX form submission
     * Uses app.js handleAjaxForm if available, otherwise provides fallback
     */
    window.handleAjaxForm = function(formSelector, options = {}) {
        // Use app.js implementation if available
        if (typeof window.AjaxUtils !== 'undefined' && typeof window.AjaxUtils.form === 'function') {
            return window.AjaxUtils.form(formSelector, options);
        }

        // Fallback implementation
        const $form = $(formSelector);
        
        $form.off('submit.ajax').on('submit.ajax', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $submitBtn = $form.find('button[type="submit"]').first();
            const formData = new FormData(this);
            const url = $form.attr('action') || options.url;
            const method = $form.find('input[name="_method"]').val() || $form.attr('method') || 'POST';

            if (typeof window.ajaxLoader !== 'undefined') {
                window.ajaxLoader.show($submitBtn);
            } else {
                showLoading($submitBtn);
            }

            $.ajax({
                url: url,
                type: method,
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (typeof window.ajaxLoader !== 'undefined') {
                        window.ajaxLoader.hide($submitBtn);
                    } else {
                        hideLoading($submitBtn);
                    }
                    
                    if (response.success) {
                        showToast(response.message || 'Operation completed successfully', 'success');
                        
                        // Only redirect if explicitly requested or if it's a create operation on a non-index page
                        const formAction = $form.attr('action') || '';
                        const isCreatePage = formAction.includes('/store') || formAction.includes('/create');
                        const isEditPage = formAction.includes('/update') || formAction.match(/\/\d+\/update/);
                        
                        // Reset form if it's a create form
                        if (isCreatePage && !options.keepFormData) {
                            $form[0].reset();
                            $form.find('.is-invalid').removeClass('is-invalid');
                            $form.find('.invalid-feedback').remove();
                        }
                        
                        // Handle redirect only if explicitly provided or if it's a create operation
                        if (response.redirect) {
                            // Only redirect if explicitly needed (like after login/logout)
                            if (options.forceRedirect || response.forceRedirect || $form.data('force-redirect') === true) {
                                setTimeout(function() {
                                    window.location.href = response.redirect;
                                }, 1000);
                            } else if (isCreatePage && !options.stayOnPage) {
                                // For create operations, optionally redirect to index
                                setTimeout(function() {
                                    window.location.href = response.redirect;
                                }, 1500);
                            }
                        } else if (options.onSuccess) {
                            options.onSuccess(response);
                        } else {
                            // Reload DataTable if exists (no page refresh)
                            if ($.fn.DataTable) {
                                const tableId = options.tableId || $form.data('table-id');
                                if (tableId && $(tableId).length) {
                                    $(tableId).DataTable().ajax.reload(null, false);
                                }
                            }
                            
                            // For edit operations, optionally close modal if exists
                            if (isEditPage) {
                                const $modal = $form.closest('.modal');
                                if ($modal.length && typeof $modal.modal === 'function') {
                                    setTimeout(function() {
                                        $modal.modal('hide');
                                    }, 1000);
                                }
                            }
                        }
                    } else {
                        showToast(response.message || 'An error occurred', 'error');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (typeof window.ajaxLoader !== 'undefined') {
                        window.ajaxLoader.hide($submitBtn);
                    } else {
                        hideLoading($submitBtn);
                    }
                    
                    // Log error
                    console.error('Form AJAX Error:', {
                        url: url,
                        method: method,
                        status: xhr.status,
                        statusText: xhr.statusText,
                        error: errorThrown,
                        response: xhr.responseJSON || xhr.responseText
                    });
                    
                    let errorMessage = 'An error occurred. Please try again.';
                    
                    if (xhr.responseJSON) {
                        if (xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON.errors) {
                            // Use global toast system for validation errors
                            if (typeof window.ToastNotification !== 'undefined') {
                                window.ToastNotification.validationErrors(xhr.responseJSON.errors);
                            } else {
                                const errors = Object.values(xhr.responseJSON.errors).flat();
                                errorMessage = errors.join('<br>');
                                showToast(errorMessage, 'error');
                            }
                            
                            // Display validation errors in form
                            displayValidationErrors($form, xhr.responseJSON.errors);
                            
                            if (options.onError) {
                                options.onError(xhr, textStatus, errorThrown);
                            }
                            return; // Exit early for validation errors
                        }
                    } else if (xhr.status === 0) {
                        errorMessage = 'Network error. Please check your connection.';
                    } else if (xhr.status === 401) {
                        errorMessage = 'Your session has expired. Redirecting to login...';
                        setTimeout(function() {
                            window.location.href = '/login';
                        }, 2000);
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error. Please try again later.';
                    }
                    
                    showToast(errorMessage, 'error');
                    
                    if (options.onError) {
                        options.onError(xhr, textStatus, errorThrown);
                    }
                }
            });
        });
    };

    /**
     * Handle AJAX delete with confirmation
     * Uses app.js handleAjaxDelete if available, otherwise provides fallback
     */
    window.handleAjaxDelete = function(deleteSelector, options = {}) {
        // Use app.js implementation if available
        if (typeof window.AjaxUtils !== 'undefined' && typeof window.AjaxUtils.delete === 'function') {
            return window.AjaxUtils.delete(deleteSelector, options);
        }

        // Fallback implementation
        $(document).off('click', deleteSelector).on('click', deleteSelector, function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $button = $(this);
            const $form = $button.closest('form');
            const url = $form.attr('action') || $button.data('url');
            const confirmMessage = $button.data('confirm') || options.confirmMessage || 'Are you sure you want to delete this item?';
            const tableId = $button.data('table-id') || options.tableId;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Are you sure?',
                    text: confirmMessage,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performDelete(url, $button, tableId, options);
                    }
                });
            } else {
                if (confirm(confirmMessage)) {
                    performDelete(url, $button, tableId, options);
                }
            }
        });
    };

    /**
     * Perform delete operation
     */
    function performDelete(url, $button, tableId, options) {
        if (typeof window.ajaxLoader !== 'undefined') {
            window.ajaxLoader.show($button, 'Deleting...');
        } else {
            showLoading($button);
        }

        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (typeof window.ajaxLoader !== 'undefined') {
                    window.ajaxLoader.hide($button);
                } else {
                    hideLoading($button);
                }
                
                if (response.success) {
                    showToast(response.message, 'success');
                    
                    // Remove row from table if DataTable exists
                    if (tableId && $.fn.DataTable) {
                        const table = $(tableId).DataTable();
                        const $row = $button.closest('tr');
                        if ($row.length) {
                            table.row($row).remove().draw();
                        } else {
                            table.ajax.reload(null, false);
                        }
                    } else {
                        // Reload page after short delay
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                    
                    if (options.onSuccess) {
                        options.onSuccess(response);
                    }
                } else {
                    showToast(response.message || 'Failed to delete item', 'error');
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                if (typeof window.ajaxLoader !== 'undefined') {
                    window.ajaxLoader.hide($button);
                } else {
                    hideLoading($button);
                }
                
                // Log error
                console.error('Delete AJAX Error:', {
                    url: url,
                    status: xhr.status,
                    statusText: xhr.statusText,
                    error: errorThrown
                });
                
                let errorMessage = 'Failed to delete item. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 0) {
                    errorMessage = 'Network error. Please check your connection.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                }
                
                showToast(errorMessage, 'error');
                
                if (options.onError) {
                    options.onError(xhr, textStatus, errorThrown);
                }
            }
        });
    }

    /**
     * Handle AJAX restore
     * Uses app.js handleAjaxRestore if available, otherwise provides fallback
     */
    window.handleAjaxRestore = function(restoreSelector, options = {}) {
        // Use app.js implementation if available
        if (typeof window.AjaxUtils !== 'undefined' && typeof window.AjaxUtils.restore === 'function') {
            return window.AjaxUtils.restore(restoreSelector, options);
        }

        // Fallback implementation
        $(document).off('click', restoreSelector).on('click', restoreSelector, function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $button = $(this);
            const url = $button.data('url') || $button.attr('href');
            const tableId = $button.data('table-id') || options.tableId;

            if (typeof window.ajaxLoader !== 'undefined') {
                window.ajaxLoader.show($button, 'Restoring...');
            } else {
                showLoading($button);
            }

            $.ajax({
                url: url,
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                success: function(response) {
                    if (typeof window.ajaxLoader !== 'undefined') {
                        window.ajaxLoader.hide($button);
                    } else {
                        hideLoading($button);
                    }
                    
                    if (response.success) {
                        showToast(response.message, 'success');
                        
                        if (tableId && $.fn.DataTable) {
                            $(tableId).DataTable().ajax.reload(null, false);
                        } else {
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }
                        
                        if (options.onSuccess) {
                            options.onSuccess(response);
                        }
                    } else {
                        showToast(response.message || 'Failed to restore item', 'error');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    if (typeof window.ajaxLoader !== 'undefined') {
                        window.ajaxLoader.hide($button);
                    } else {
                        hideLoading($button);
                    }
                    
                    // Log error
                    console.error('Restore AJAX Error:', {
                        url: url,
                        status: xhr.status,
                        error: errorThrown
                    });
                    
                    let errorMessage = 'Failed to restore item. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 0) {
                        errorMessage = 'Network error. Please check your connection.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error. Please try again later.';
                    }
                    
                    showToast(errorMessage, 'error');
                    
                    if (options.onError) {
                        options.onError(xhr, textStatus, errorThrown);
                    }
                }
            });
        });
    };

    /**
     * Handle AJAX force delete
     * Uses app.js handleAjaxForceDelete if available, otherwise provides fallback
     */
    window.handleAjaxForceDelete = function(forceDeleteSelector, options = {}) {
        // Use app.js implementation if available
        if (typeof window.AjaxUtils !== 'undefined' && typeof window.AjaxUtils.forceDelete === 'function') {
            return window.AjaxUtils.forceDelete(forceDeleteSelector, options);
        }

        // Fallback implementation
        $(document).off('click', forceDeleteSelector).on('click', forceDeleteSelector, function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $button = $(this);
            const url = $button.data('url') || $button.attr('href');
            const confirmMessage = $button.data('confirm') || options.confirmMessage || 'Are you sure you want to permanently delete this item? This action cannot be undone!';
            const tableId = $button.data('table-id') || options.tableId;

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Permanently Delete?',
                    text: confirmMessage,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete permanently!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        performForceDelete(url, $button, tableId, options);
                    }
                });
            } else {
                if (confirm(confirmMessage)) {
                    performForceDelete(url, $button, tableId, options);
                }
            }
        });
    };

    /**
     * Perform force delete operation
     */
    function performForceDelete(url, $button, tableId, options) {
        if (typeof window.ajaxLoader !== 'undefined') {
            window.ajaxLoader.show($button, 'Deleting...');
        } else {
            showLoading($button);
        }

        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                if (typeof window.ajaxLoader !== 'undefined') {
                    window.ajaxLoader.hide($button);
                } else {
                    hideLoading($button);
                }
                
                if (response.success) {
                    showToast(response.message, 'success');
                    
                    if (tableId && $.fn.DataTable) {
                        const table = $(tableId).DataTable();
                        const $row = $button.closest('tr');
                        if ($row.length) {
                            table.row($row).remove().draw();
                        } else {
                            table.ajax.reload(null, false);
                        }
                    } else {
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                    
                    if (options.onSuccess) {
                        options.onSuccess(response);
                    }
                } else {
                    showToast(response.message || 'Failed to delete item', 'error');
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                if (typeof window.ajaxLoader !== 'undefined') {
                    window.ajaxLoader.hide($button);
                } else {
                    hideLoading($button);
                }
                
                // Log error
                console.error('Force Delete AJAX Error:', {
                    url: url,
                    status: xhr.status,
                    statusText: xhr.statusText,
                    error: errorThrown
                });
                
                let errorMessage = 'Failed to delete item. Please try again.';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 0) {
                    errorMessage = 'Network error. Please check your connection.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                }
                
                showToast(errorMessage, 'error');
                
                if (options.onError) {
                    options.onError(xhr, textStatus, errorThrown);
                }
            }
        });
    }

    /**
     * Display validation errors in form
     */
    function displayValidationErrors($form, errors) {
        // Clear previous errors
        $form.find('.is-invalid').removeClass('is-invalid');
        $form.find('.invalid-feedback').remove();

        // Display new errors
        $.each(errors, function(field, messages) {
            const $field = $form.find('[name="' + field + '"]');
            if ($field.length) {
                $field.addClass('is-invalid');
                const errorHtml = '<div class="invalid-feedback">' + messages.join('<br>') + '</div>';
                $field.after(errorHtml);
            }
        });
    }

    /**
     * Initialize AJAX handlers on page load
     */
    $(document).ready(function() {
        // Handle all forms with data-ajax attribute
        $('form[data-ajax="true"]').each(function() {
            const $form = $(this);
            const tableId = $form.data('table-id');
            handleAjaxForm($form, { tableId: tableId });
        });

        // Handle delete buttons
        handleAjaxDelete('.ajax-delete', {
            confirmMessage: 'Are you sure you want to delete this item?'
        });

        // Handle restore buttons
        handleAjaxRestore('.ajax-restore');

        // Handle force delete buttons
        handleAjaxForceDelete('.ajax-force-delete', {
            confirmMessage: 'Are you sure you want to permanently delete this item? This action cannot be undone!'
        });
    });

})(jQuery);

