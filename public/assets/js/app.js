/**
 * Main Application JavaScript
 * 
 * This is the core application file that handles all AJAX operations
 * using jQuery exclusively. No fetch(), axios, or native form submissions.
 * 
 * @author Eyecare Admin Panel
 * @version 1.0.0
 */

(function($) {
    'use strict';

    /**
     * ============================================
     * AJAX CONFIGURATION & SETUP
     * ============================================
     */

    // Setup CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
        },
        timeout: 30000, // 30 seconds timeout
        error: function(xhr, status, error) {
            // Global error logging
            console.error('AJAX Error:', {
                url: xhr.responseURL || 'Unknown',
                status: xhr.status,
                statusText: xhr.statusText,
                error: error,
                response: xhr.responseJSON || xhr.responseText
            });
        }
    });

    /**
     * ============================================
     * REUSABLE AJAX UTILITIES
     * ============================================
     */

    /**
     * AJAX Loader Utility
     * Shows/hides loading state on elements
     */
    window.ajaxLoader = {
        /**
         * Show loading state
         * @param {jQuery|string} element - Element to show loading on
         * @param {string} text - Loading text (optional)
         */
        show: function(element, text = 'Loading...') {
            const $el = $(element);
            if ($el.length) {
                $el.data('original-html', $el.html());
                $el.data('original-disabled', $el.prop('disabled'));
                $el.prop('disabled', true);
                
                if ($el.is('button') || $el.is('input[type="submit"]')) {
                    $el.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>' + text);
                } else {
                    $el.prepend('<div class="ajax-loader-overlay"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                }
            }
        },

        /**
         * Hide loading state
         * @param {jQuery|string} element - Element to hide loading from
         */
        hide: function(element) {
            const $el = $(element);
            if ($el.length) {
                $el.prop('disabled', $el.data('original-disabled') || false);
                
                if ($el.is('button') || $el.is('input[type="submit"]')) {
                    const originalHtml = $el.data('original-html');
                    if (originalHtml) {
                        $el.html(originalHtml);
                    }
                } else {
                    $el.find('.ajax-loader-overlay').remove();
                }
            }
        }
    };

    /**
     * AJAX Alert Utility
     * Displays consistent alerts/toasts
     */
    window.ajaxAlert = {
        /**
         * Show alert/toast
         * @param {string} type - success, error, warning, info
         * @param {string} message - Message to display
         * @param {object} options - Additional options
         */
        show: function(type, message, options = {}) {
            if (typeof window.ToastNotification !== 'undefined') {
                window.ToastNotification.show(message, type, options);
            } else {
                // Fallback to Bootstrap alert
                const alertClass = type === 'error' ? 'danger' : type;
                const alertHtml = `
                    <div class="alert alert-${alertClass} alert-dismissible fade show" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;
                $('body').prepend(alertHtml);
                setTimeout(function() {
                    $('.alert').fadeOut(function() {
                        $(this).remove();
                    });
                }, 5000);
            }
        },

        /**
         * Show success message
         */
        success: function(message, options) {
            this.show('success', message, options);
        },

        /**
         * Show error message
         */
        error: function(message, options) {
            this.show('error', message, options);
        },

        /**
         * Show warning message
         */
        warning: function(message, options) {
            this.show('warning', message, options);
        },

        /**
         * Show info message
         */
        info: function(message, options) {
            this.show('info', message, options);
        }
    };

    /**
     * ============================================
     * CORE AJAX REQUEST FUNCTION
     * ============================================
     */

    /**
     * Universal AJAX Request Handler
     * Handles all AJAX requests with consistent error handling
     * 
     * @param {string} url - Request URL
     * @param {string} method - HTTP method (GET, POST, PUT, DELETE, PATCH)
     * @param {object|FormData} data - Request data
     * @param {function} onSuccess - Success callback
     * @param {function} onError - Error callback (optional)
     * @param {object} options - Additional options
     * @returns {jqXHR} jQuery XHR object
     */
    window.ajaxRequest = function(url, method, data, onSuccess, onError, options = {}) {
        const defaultOptions = {
            showLoader: true,
            loaderElement: null,
            showAlert: true,
            alertOnSuccess: false,
            successMessage: null,
            errorMessage: null,
            timeout: 30000,
            processData: true,
            contentType: true
        };

        const config = $.extend({}, defaultOptions, options);
        const isFormData = data instanceof FormData;

        // Prepare AJAX settings
        const ajaxSettings = {
            url: url,
            type: method.toUpperCase(),
            data: data,
            dataType: 'json',
            timeout: config.timeout,
            processData: isFormData ? false : config.processData,
            contentType: isFormData ? false : (config.contentType ? 'application/json' : false),
            beforeSend: function(xhr) {
                // Show loader if enabled
                if (config.showLoader && config.loaderElement) {
                    ajaxLoader.show(config.loaderElement);
                }

                // Log request
                if (window.console && console.log) {
                    console.log('AJAX Request:', {
                        url: url,
                        method: method,
                        data: isFormData ? '[FormData]' : data
                    });
                }
            },
            success: function(response, textStatus, xhr) {
                // Hide loader
                if (config.showLoader && config.loaderElement) {
                    ajaxLoader.hide(config.loaderElement);
                }

                // Handle response
                if (response && typeof response === 'object') {
                    // Show success alert if enabled
                    if (config.alertOnSuccess && config.successMessage) {
                        ajaxAlert.success(config.successMessage);
                    }

                    // Call success callback
                    if (typeof onSuccess === 'function') {
                        onSuccess(response, textStatus, xhr);
                    }
                } else {
                    // Invalid response format
                    const errorMsg = config.errorMessage || 'Invalid response from server.';
                    ajaxAlert.error(errorMsg);
                    if (typeof onError === 'function') {
                        onError(xhr, 'invalid_response', 'Invalid response format');
                    }
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                // Hide loader
                if (config.showLoader && config.loaderElement) {
                    ajaxLoader.hide(config.loaderElement);
                }

                // Handle different error types
                let errorMessage = config.errorMessage || 'An error occurred. Please try again.';
                let errorType = 'error';

                // Parse error response
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.responseJSON.errors) {
                        // Validation errors
                        if (typeof window.ToastNotification !== 'undefined') {
                            window.ToastNotification.validationErrors(xhr.responseJSON.errors);
                        } else {
                            const errors = Object.values(xhr.responseJSON.errors).flat();
                            errorMessage = errors.join('<br>');
                        }
                        errorType = 'validation';
                    }
                }

                // Handle HTTP status codes
                if (xhr.status === 0) {
                    errorMessage = 'Network error. Please check your internet connection.';
                    errorType = 'network';
                } else if (xhr.status === 401) {
                    errorMessage = 'Your session has expired. Please login again.';
                    errorType = 'unauthorized';
                    // Redirect to login after 2 seconds
                    setTimeout(function() {
                        window.location.href = '/login';
                    }, 2000);
                } else if (xhr.status === 403) {
                    errorMessage = 'Access denied. You do not have permission to perform this action.';
                    errorType = 'forbidden';
                } else if (xhr.status === 404) {
                    errorMessage = 'Resource not found.';
                    errorType = 'not_found';
                } else if (xhr.status === 422) {
                    // Validation errors already handled above
                    if (errorType !== 'validation') {
                        errorMessage = 'Validation error. Please check your input.';
                    }
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later or contact support.';
                    errorType = 'server_error';
                } else if (textStatus === 'timeout') {
                    errorMessage = 'Request timeout. Please try again.';
                    errorType = 'timeout';
                }

                // Log error
                console.error('AJAX Error Details:', {
                    url: url,
                    method: method,
                    status: xhr.status,
                    statusText: xhr.statusText,
                    error: errorThrown,
                    textStatus: textStatus,
                    response: xhr.responseJSON || xhr.responseText
                });

                // Show error alert
                if (config.showAlert && errorType !== 'validation') {
                    ajaxAlert.error(errorMessage);
                }

                // Call error callback
                if (typeof onError === 'function') {
                    onError(xhr, errorType, errorThrown);
                }
            },
            complete: function(xhr, textStatus) {
                // Always hide loader on complete
                if (config.showLoader && config.loaderElement) {
                    ajaxLoader.hide(config.loaderElement);
                }

                // Log completion
                if (window.console && console.log) {
                    console.log('AJAX Complete:', {
                        url: url,
                        status: xhr.status,
                        textStatus: textStatus
                    });
                }
            }
        };

        // Convert data to JSON string if not FormData
        if (!isFormData && config.contentType && typeof data === 'object') {
            ajaxSettings.data = JSON.stringify(data);
        }

        // Make the AJAX request
        return $.ajax(ajaxSettings);
    };

    /**
     * ============================================
     * CONVENIENCE METHODS
     * ============================================
     */

    /**
     * AJAX GET Request
     */
    window.ajaxGet = function(url, onSuccess, onError, options) {
        return ajaxRequest(url, 'GET', {}, onSuccess, onError, options);
    };

    /**
     * AJAX POST Request
     */
    window.ajaxPost = function(url, data, onSuccess, onError, options) {
        return ajaxRequest(url, 'POST', data, onSuccess, onError, options);
    };

    /**
     * AJAX PUT Request
     */
    window.ajaxPut = function(url, data, onSuccess, onError, options) {
        return ajaxRequest(url, 'PUT', data, onSuccess, onError, options);
    };

    /**
     * AJAX PATCH Request
     */
    window.ajaxPatch = function(url, data, onSuccess, onError, options) {
        return ajaxRequest(url, 'PATCH', data, onSuccess, onError, options);
    };

    /**
     * AJAX DELETE Request
     */
    window.ajaxDelete = function(url, onSuccess, onError, options) {
        return ajaxRequest(url, 'DELETE', {}, onSuccess, onError, options);
    };

    /**
     * ============================================
     * FORM HANDLING
     * ============================================
     */

    /**
     * Sanitize form input
     * @param {string} value - Input value
     * @returns {string} Sanitized value
     */
    function sanitizeInput(value) {
        if (typeof value !== 'string') {
            return value;
        }
        // Remove potentially dangerous characters
        return value.trim().replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
    }

    /**
     * Serialize form data with sanitization
     * @param {jQuery} $form - Form element
     * @returns {object} Sanitized form data
     */
    function serializeFormData($form) {
        const formData = {};
        $form.find('input, select, textarea').each(function() {
            const $field = $(this);
            const name = $field.attr('name');
            const type = $field.attr('type');
            
            if (!name || $field.prop('disabled')) {
                return;
            }

            if (type === 'checkbox') {
                formData[name] = $field.is(':checked') ? ($field.val() || 1) : 0;
            } else if (type === 'radio') {
                if ($field.is(':checked')) {
                    formData[name] = sanitizeInput($field.val());
                }
            } else {
                formData[name] = sanitizeInput($field.val());
            }
        });

        return formData;
    }

    /**
     * Handle AJAX form submission
     * @param {jQuery|string} formSelector - Form selector
     * @param {object} options - Options
     */
    window.handleAjaxForm = function(formSelector, options = {}) {
        const $form = $(formSelector);
        
        if (!$form.length) {
            console.warn('Form not found:', formSelector);
            return;
        }

        $form.off('submit.ajax').on('submit.ajax', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $submitBtn = $form.find('button[type="submit"], input[type="submit"]').first();
            const url = $form.attr('action') || options.url;
            const method = $form.find('input[name="_method"]').val() || $form.attr('method') || 'POST';
            
            if (!url) {
                ajaxAlert.error('Form action URL is missing.');
                return;
            }

            // Get form data
            const formData = new FormData(this);
            
            // Show loading
            ajaxLoader.show($submitBtn, 'Processing...');

            // Clear previous validation errors
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').remove();

            // Make AJAX request
            ajaxRequest(
                url,
                method,
                formData,
                function(response) {
                    // Success handler
                    if (response.success) {
                        // Show success message
                        ajaxAlert.success(response.message || 'Operation completed successfully.');

                        // Handle redirect
                        if (response.redirect) {
                            const forceRedirect = options.forceRedirect || response.forceRedirect || $form.data('force-redirect');
                            if (forceRedirect) {
                                setTimeout(function() {
                                    window.location.href = response.redirect;
                                }, 1000);
                                return;
                            }
                        }

                        // Reset form if it's a create form
                        const formAction = $form.attr('action') || '';
                        const isCreatePage = formAction.includes('/store') || formAction.includes('/create');
                        if (isCreatePage && !options.keepFormData) {
                            $form[0].reset();
                            $form.find('.is-invalid').removeClass('is-invalid');
                            $form.find('.invalid-feedback').remove();
                        }

                        // Reload DataTable if exists
                        if ($.fn.DataTable) {
                            const tableId = options.tableId || $form.data('table-id');
                            if (tableId && $(tableId).length) {
                                $(tableId).DataTable().ajax.reload(null, false);
                            }
                        }

                        // Close modal if exists
                        const $modal = $form.closest('.modal');
                        if ($modal.length && typeof $modal.modal === 'function') {
                            setTimeout(function() {
                                $modal.modal('hide');
                            }, 1000);
                        }

                        // Custom success callback
                        if (typeof options.onSuccess === 'function') {
                            options.onSuccess(response);
                        }
                    } else {
                        ajaxAlert.error(response.message || 'Operation failed.');
                    }
                },
                function(xhr, errorType, errorThrown) {
                    // Error handler
                    if (errorType === 'validation' && xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors inline
                        displayValidationErrors($form, xhr.responseJSON.errors);
                    }

                    // Custom error callback
                    if (typeof options.onError === 'function') {
                        options.onError(xhr, errorType, errorThrown);
                    }
                },
                {
                    showLoader: false, // We handle loader manually
                    loaderElement: $submitBtn,
                    showAlert: false, // We handle alerts manually
                    processData: false,
                    contentType: false
                }
            );
        });
    };

    /**
     * Display validation errors in form
     * @param {jQuery} $form - Form element
     * @param {object} errors - Validation errors object
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
                const errorHtml = '<div class="invalid-feedback">' + 
                    (Array.isArray(messages) ? messages.join('<br>') : messages) + 
                    '</div>';
                $field.after(errorHtml);
            } else {
                // Show error at top of form if field not found
                const errorHtml = '<div class="alert alert-danger alert-dismissible fade show" role="alert">' +
                    '<strong>' + field + ':</strong> ' +
                    (Array.isArray(messages) ? messages.join(', ') : messages) +
                    '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                    '</div>';
                $form.prepend(errorHtml);
            }
        });
    }

    /**
     * ============================================
     * DELETE OPERATIONS
     * ============================================
     */

    /**
     * Handle AJAX delete with confirmation
     */
    window.handleAjaxDelete = function(deleteSelector, options = {}) {
        $(document).off('click', deleteSelector).on('click', deleteSelector, function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const $form = $button.closest('form');
            const url = $form.attr('action') || $button.data('url') || $button.attr('href');
            const confirmMessage = $button.data('confirm') || options.confirmMessage || 'Are you sure you want to delete this item?';
            const tableId = $button.data('table-id') || options.tableId;

            if (!url) {
                ajaxAlert.error('Delete URL is missing.');
                return;
            }

            // Show confirmation
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
        ajaxLoader.show($button, 'Deleting...');

        ajaxRequest(
            url,
            'DELETE',
            {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            function(response) {
                if (response.success) {
                    ajaxAlert.success(response.message || 'Item deleted successfully.');

                    // Remove row from DataTable
                    if (tableId && $.fn.DataTable && $(tableId).length) {
                        const table = $(tableId).DataTable();
                        const $row = $button.closest('tr');
                        if ($row.length) {
                            table.row($row).remove().draw();
                        } else {
                            table.ajax.reload(null, false);
                        }
                    } else {
                        // Reload page if no DataTable
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }

                    if (typeof options.onSuccess === 'function') {
                        options.onSuccess(response);
                    }
                } else {
                    ajaxAlert.error(response.message || 'Failed to delete item.');
                }
            },
            function(xhr, errorType, errorThrown) {
                if (typeof options.onError === 'function') {
                    options.onError(xhr, errorType, errorThrown);
                }
            },
            {
                showLoader: false,
                loaderElement: $button,
                errorMessage: 'Failed to delete item. Please try again.'
            }
        );
    }

    /**
     * ============================================
     * RESTORE OPERATIONS
     * ============================================
     */

    /**
     * Handle AJAX restore
     */
    window.handleAjaxRestore = function(restoreSelector, options = {}) {
        $(document).off('click', restoreSelector).on('click', restoreSelector, function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const url = $button.data('url') || $button.attr('href');
            const tableId = $button.data('table-id') || options.tableId;

            if (!url) {
                ajaxAlert.error('Restore URL is missing.');
                return;
            }

            ajaxLoader.show($button, 'Restoring...');

            ajaxRequest(
                url,
                'POST',
                {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                function(response) {
                    if (response.success) {
                        ajaxAlert.success(response.message || 'Item restored successfully.');

                        // Reload DataTable
                        if (tableId && $.fn.DataTable && $(tableId).length) {
                            $(tableId).DataTable().ajax.reload(null, false);
                        } else {
                            setTimeout(function() {
                                window.location.reload();
                            }, 1000);
                        }

                        if (typeof options.onSuccess === 'function') {
                            options.onSuccess(response);
                        }
                    } else {
                        ajaxAlert.error(response.message || 'Failed to restore item.');
                    }
                },
                function(xhr, errorType, errorThrown) {
                    if (typeof options.onError === 'function') {
                        options.onError(xhr, errorType, errorThrown);
                    }
                },
                {
                    showLoader: false,
                    loaderElement: $button,
                    errorMessage: 'Failed to restore item. Please try again.'
                }
            );
        });
    };

    /**
     * ============================================
     * FORCE DELETE OPERATIONS
     * ============================================
     */

    /**
     * Handle AJAX force delete
     */
    window.handleAjaxForceDelete = function(forceDeleteSelector, options = {}) {
        $(document).off('click', forceDeleteSelector).on('click', forceDeleteSelector, function(e) {
            e.preventDefault();
            e.stopPropagation();

            const $button = $(this);
            const url = $button.data('url') || $button.attr('href');
            const confirmMessage = $button.data('confirm') || options.confirmMessage || 'Are you sure you want to permanently delete this item? This action cannot be undone!';
            const tableId = $button.data('table-id') || options.tableId;

            if (!url) {
                ajaxAlert.error('Delete URL is missing.');
                return;
            }

            // Show confirmation
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
        ajaxLoader.show($button, 'Deleting...');

        ajaxRequest(
            url,
            'DELETE',
            {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            function(response) {
                if (response.success) {
                    ajaxAlert.success(response.message || 'Item permanently deleted.');

                    // Remove row from DataTable
                    if (tableId && $.fn.DataTable && $(tableId).length) {
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

                    if (typeof options.onSuccess === 'function') {
                        options.onSuccess(response);
                    }
                } else {
                    ajaxAlert.error(response.message || 'Failed to delete item.');
                }
            },
            function(xhr, errorType, errorThrown) {
                if (typeof options.onError === 'function') {
                    options.onError(xhr, errorType, errorThrown);
                }
            },
            {
                showLoader: false,
                loaderElement: $button,
                errorMessage: 'Failed to delete item. Please try again.'
            }
        );
    }

    /**
     * ============================================
     * INITIALIZATION
     * ============================================
     */

    /**
     * Initialize all AJAX handlers on page load
     */
    $(document).ready(function() {
        // Handle all forms with data-ajax attribute
        $('form[data-ajax="true"]').each(function() {
            const $form = $(this);
            const tableId = $form.data('table-id');
            handleAjaxForm($form, { 
                tableId: tableId,
                forceRedirect: $form.data('force-redirect') === true
            });
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

        // Prevent default form submissions (safety net)
        $('form').not('[data-ajax="true"]').on('submit', function(e) {
            // Only prevent if form doesn't have action or method
            const $form = $(this);
            if (!$form.attr('action') && !$form.data('no-ajax')) {
                console.warn('Form submission prevented. Add data-ajax="true" to enable AJAX:', $form);
            }
        });
    });

    /**
     * ============================================
     * GLOBAL ERROR HANDLER
     * ============================================
     */

    // Global AJAX error handler (catches unhandled errors)
    $(document).ajaxError(function(event, xhr, settings, thrownError) {
        // Skip if error handler is disabled
        if (settings.skipGlobalErrorHandler) {
            return;
        }

        // Only handle unhandled errors
        if (xhr.status >= 400 && !settings.handled) {
            let errorMessage = 'An unexpected error occurred.';

            if (xhr.status === 401) {
                errorMessage = 'Your session has expired. Redirecting to login...';
                setTimeout(function() {
                    window.location.href = '/login';
                }, 2000);
            } else if (xhr.status === 403) {
                errorMessage = 'Access denied. You do not have permission.';
            } else if (xhr.status === 500) {
                errorMessage = 'Server error. Please try again later.';
            }

            if (typeof window.ToastNotification !== 'undefined') {
                window.ToastNotification.error(errorMessage);
            } else {
                ajaxAlert.error(errorMessage);
            }
        }
    });

    // Export utilities globally
    window.AjaxUtils = {
        request: ajaxRequest,
        get: ajaxGet,
        post: ajaxPost,
        put: ajaxPut,
        patch: ajaxPatch,
        delete: ajaxDelete,
        loader: ajaxLoader,
        alert: ajaxAlert,
        form: handleAjaxForm,
        delete: handleAjaxDelete,
        restore: handleAjaxRestore,
        forceDelete: handleAjaxForceDelete
    };

})(jQuery);

