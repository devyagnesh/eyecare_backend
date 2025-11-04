/**
 * AJAX Utility Functions for Better UI/UX
 * Provides reusable functions for AJAX form submissions, delete operations, and notifications
 */

(function($) {
    'use strict';

    // Initialize CSRF token for all AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    /**
     * Show loading state on element
     */
    function showLoading(element) {
        if (element.is('button')) {
            element.data('original-text', element.html());
            element.prop('disabled', true);
            element.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span><span class="sr-only">Loading...</span>');
        } else if (element.is('form')) {
            element.find('button[type="submit"]').each(function() {
                showLoading($(this));
            });
        }
    }

    /**
     * Hide loading state on element
     */
    function hideLoading(element) {
        if (element.is('button')) {
            element.prop('disabled', false);
            const originalText = element.data('original-text');
            if (originalText) {
                element.html(originalText);
            }
        } else if (element.is('form')) {
            element.find('button[type="submit"]').each(function() {
                hideLoading($(this));
            });
        }
    }

    /**
     * Show success notification
     */
    function showSuccess(message, title) {
        if (typeof showSuccessToast !== 'undefined') {
            showSuccessToast(message, title);
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: title || 'Success',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        } else {
            // Fallback to Bootstrap alert
            showAlert('success', message);
        }
    }

    /**
     * Show error notification
     */
    function showError(message, title) {
        if (typeof showErrorToast !== 'undefined') {
            showErrorToast(message, title);
        } else if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: title || 'Error',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true
            });
        } else {
            // Fallback to Bootstrap alert
            showAlert('danger', message);
        }
    }

    /**
     * Show Bootstrap alert
     */
    function showAlert(type, message) {
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        const alertContainer = $('.alert-container');
        if (alertContainer.length) {
            alertContainer.html(alertHtml);
        } else {
            $('main, .main-content').first().prepend(alertHtml);
        }
        
        // Auto-dismiss after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    }

    /**
     * Display validation errors
     */
    function displayValidationErrors(errors) {
        // Clear previous errors
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').remove();
        
        // Display new errors
        $.each(errors, function(field, messages) {
            const fieldElement = $('[name="' + field + '"]');
            if (fieldElement.length) {
                fieldElement.addClass('is-invalid');
                const errorHtml = '<div class="invalid-feedback">' + messages[0] + '</div>';
                fieldElement.after(errorHtml);
            }
        });
    }

    /**
     * AJAX Form Submission
     * @param {jQuery} form - Form element
     * @param {Object} options - Configuration options
     */
    window.ajaxFormSubmit = function(form, options) {
        const defaults = {
            successCallback: null,
            errorCallback: null,
            successMessage: 'Operation completed successfully',
            redirectOnSuccess: false,
            redirectUrl: null,
            showLoader: true
        };
        const config = $.extend({}, defaults, options);

        form.on('submit', function(e) {
            e.preventDefault();
            
            const $form = $(this);
            const submitButton = $form.find('button[type="submit"]');
            const formData = new FormData(this);
            const formMethod = $form.find('input[name="_method"]').val() || 'POST';

            // Show loading state
            if (config.showLoader) {
                showLoading(submitButton);
            }

            // Clear previous errors
            $form.find('.is-invalid').removeClass('is-invalid');
            $form.find('.invalid-feedback').remove();

            $.ajax({
                url: $form.attr('action'),
                type: formMethod,
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    hideLoading(submitButton);
                    
                    if (response.success) {
                        showSuccess(response.message || config.successMessage);
                        
                        // Execute custom success callback
                        if (config.successCallback && typeof config.successCallback === 'function') {
                            config.successCallback(response);
                        }
                        
                        // Redirect if configured
                        if (config.redirectOnSuccess) {
                            const redirectUrl = config.redirectUrl || response.redirect || window.location.href;
                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 1500);
                        } else if (response.redirect) {
                            setTimeout(function() {
                                window.location.href = response.redirect;
                            }, 1500);
                        }
                    } else {
                        showError(response.message || 'An error occurred');
                        
                        if (response.errors) {
                            displayValidationErrors(response.errors);
                        }
                    }
                },
                error: function(xhr) {
                    hideLoading(submitButton);
                    
                    if (xhr.status === 422) {
                        // Validation errors
                        const errors = xhr.responseJSON.errors || {};
                        displayValidationErrors(errors);
                        showError('Please correct the errors in the form');
                    } else if (xhr.status === 403) {
                        showError('You do not have permission to perform this action');
                    } else if (xhr.status === 404) {
                        showError('Resource not found');
                    } else {
                        const errorMessage = xhr.responseJSON?.message || 'An unexpected error occurred';
                        showError(errorMessage);
                        
                        // Execute custom error callback
                        if (config.errorCallback && typeof config.errorCallback === 'function') {
                            config.errorCallback(xhr);
                        }
                    }
                }
            });
        });
    };

    /**
     * AJAX Delete with Confirmation
     * @param {String} url - Delete URL
     * @param {Object} options - Configuration options
     */
    window.ajaxDelete = function(url, options) {
        const defaults = {
            title: 'Are you sure?',
            text: 'You won\'t be able to revert this!',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
            successMessage: 'Deleted successfully',
            errorMessage: 'Error deleting item',
            successCallback: null,
            redirectOnSuccess: false,
            redirectUrl: null
        };
        const config = $.extend({}, defaults, options);

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: config.title,
                text: config.text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: config.confirmButtonText,
                cancelButtonText: config.cancelButtonText
            }).then((result) => {
                if (result.isConfirmed) {
                    performDelete(url, config);
                }
            });
        } else {
            // Fallback to confirm dialog
            if (confirm(config.text)) {
                performDelete(url, config);
            }
        }
    };

    /**
     * Perform the actual delete operation
     */
    function performDelete(url, config) {
        Swal.fire({
            title: 'Processing...',
            text: 'Please wait',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        $.ajax({
            url: url,
            type: 'DELETE',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Deleted!',
                        text: response.message || config.successMessage,
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Execute custom success callback
                    if (config.successCallback && typeof config.successCallback === 'function') {
                        config.successCallback(response);
                    }

                    // Redirect if configured
                    if (config.redirectOnSuccess) {
                        const redirectUrl = config.redirectUrl || response.redirect || window.location.href;
                        setTimeout(function() {
                            window.location.href = redirectUrl;
                        }, 2000);
                    } else if (response.redirect) {
                        setTimeout(function() {
                            window.location.href = response.redirect;
                        }, 2000);
                    } else {
                        // Reload page after 2 seconds
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || config.errorMessage
                    });
                }
            },
            error: function(xhr) {
                let errorMessage = config.errorMessage;
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.status === 403) {
                    errorMessage = 'You do not have permission to delete this item';
                } else if (xhr.status === 404) {
                    errorMessage = 'Item not found';
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMessage
                });
            }
        });
    }

    /**
     * Initialize AJAX forms on page load
     */
    $(document).ready(function() {
        // Auto-initialize forms with data-ajax attribute
        $('form[data-ajax="true"]').each(function() {
            const $form = $(this);
            const options = {
                successMessage: $form.data('success-message') || 'Operation completed successfully',
                redirectOnSuccess: $form.data('redirect-on-success') === true || $form.data('redirect-on-success') === 'true',
                redirectUrl: $form.data('redirect-url') || null
            };
            ajaxFormSubmit($form, options);
        });

        // Auto-initialize delete buttons with data-ajax-delete attribute
        $('[data-ajax-delete]').on('click', function(e) {
            e.preventDefault();
            const $button = $(this);
            const deleteUrl = $button.data('ajax-delete');
            const options = {
                title: $button.data('delete-title') || 'Are you sure?',
                text: $button.data('delete-text') || 'You won\'t be able to revert this!',
                successMessage: $button.data('success-message') || 'Deleted successfully',
                redirectOnSuccess: $button.data('redirect-on-success') === true || $button.data('redirect-on-success') === 'true',
                redirectUrl: $button.data('redirect-url') || null,
                successCallback: function(response) {
                    // If table row, remove it
                    if ($button.closest('tr').length) {
                        $button.closest('tr').fadeOut(300, function() {
                            $(this).remove();
                        });
                    }
                }
            };
            ajaxDelete(deleteUrl, options);
        });
    });

})(jQuery);

