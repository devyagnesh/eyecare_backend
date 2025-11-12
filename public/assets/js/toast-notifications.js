/**
 * Global Toast Notification System
 * Handles all success, error, warning, and info messages
 * Works with both Laravel flash messages and AJAX responses
 */

(function($) {
    'use strict';

    /**
     * Global Toast Notification Object
     */
    window.ToastNotification = {
        /**
         * Show a toast notification
         * 
         * @param {string} message - The message to display
         * @param {string} type - Type of notification: success, error, warning, info
         * @param {object} options - Additional options
         */
        show: function(message, type = 'success', options = {}) {
            // Default options
            const defaultOptions = {
                position: 'bottom-end', // Changed to bottom-right
                timer: type === 'error' ? 5000 : 3000,
                showConfirmButton: false,
                timerProgressBar: true,
                allowOutsideClick: true,
                allowEscapeKey: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            };

            // Merge with custom options
            const finalOptions = Object.assign({}, defaultOptions, options);

            // Map type to SweetAlert2 icon
            const iconMap = {
                'success': 'success',
                'error': 'error',
                'warning': 'warning',
                'info': 'info',
                'danger': 'error'
            };

            const icon = iconMap[type] || 'info';

            // Use SweetAlert2 if available
            if (typeof Swal !== 'undefined') {
                const Toast = Swal.mixin(finalOptions);

                Toast.fire({
                    icon: icon,
                    title: message,
                    html: options.html || null
                });
            } else {
                // Fallback to browser alert
                alert(message);
            }
        },

        /**
         * Show success message
         * 
         * @param {string} message - Success message
         * @param {object} options - Additional options
         */
        success: function(message, options = {}) {
            this.show(message, 'success', options);
        },

        /**
         * Show error message
         * 
         * @param {string} message - Error message
         * @param {object} options - Additional options
         */
        error: function(message, options = {}) {
            this.show(message, 'error', Object.assign({ timer: 5000 }, options));
        },

        /**
         * Show warning message
         * 
         * @param {string} message - Warning message
         * @param {object} options - Additional options
         */
        warning: function(message, options = {}) {
            this.show(message, 'warning', options);
        },

        /**
         * Show info message
         * 
         * @param {string} message - Info message
         * @param {object} options - Additional options
         */
        info: function(message, options = {}) {
            this.show(message, 'info', options);
        },

        /**
         * Show validation errors
         * 
         * @param {object|array} errors - Validation errors
         */
        validationErrors: function(errors) {
            let errorMessage = 'Please fix the following errors:';
            
            if (Array.isArray(errors)) {
                errorMessage += '<ul class="text-start mt-2 mb-0">';
                errors.forEach(error => {
                    errorMessage += '<li>' + error + '</li>';
                });
                errorMessage += '</ul>';
            } else if (typeof errors === 'object') {
                errorMessage += '<ul class="text-start mt-2 mb-0">';
                Object.keys(errors).forEach(key => {
                    const fieldErrors = Array.isArray(errors[key]) ? errors[key] : [errors[key]];
                    fieldErrors.forEach(error => {
                        errorMessage += '<li><strong>' + key + ':</strong> ' + error + '</li>';
                    });
                });
                errorMessage += '</ul>';
            } else {
                errorMessage = errors;
            }

            this.error(errorMessage, { html: errorMessage });
        }
    };

    /**
     * Initialize toast system on page load
     */
    $(document).ready(function() {
        // Check for Laravel flash messages
        const flashMessages = {
            success: window.flashSuccess || null,
            error: window.flashError || null,
            warning: window.flashWarning || null,
            info: window.flashInfo || null
        };

        // Show flash messages if they exist
        if (flashMessages.success) {
            ToastNotification.success(flashMessages.success);
        }
        if (flashMessages.error) {
            ToastNotification.error(flashMessages.error);
        }
        if (flashMessages.warning) {
            ToastNotification.warning(flashMessages.warning);
        }
        if (flashMessages.info) {
            ToastNotification.info(flashMessages.info);
        }

        // Listen for custom toast events
        $(document).on('toast:success', function(e, message, options) {
            ToastNotification.success(message, options || {});
        });

        $(document).on('toast:error', function(e, message, options) {
            ToastNotification.error(message, options || {});
        });

        $(document).on('toast:warning', function(e, message, options) {
            ToastNotification.warning(message, options || {});
        });

        $(document).on('toast:info', function(e, message, options) {
            ToastNotification.info(message, options || {});
        });

        // Global error handler for unhandled AJAX errors
        $(document).ajaxError(function(event, xhr, settings, thrownError) {
            // Only show error if it's not already handled
            if (xhr.status === 0 || xhr.status >= 400) {
                let errorMessage = 'An error occurred. Please try again.';
                
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.responseJSON.errors) {
                        ToastNotification.validationErrors(xhr.responseJSON.errors);
                        return;
                    }
                } else if (xhr.status === 0) {
                    errorMessage = 'Network error. Please check your connection.';
                } else if (xhr.status === 401) {
                    errorMessage = 'Unauthorized. Please login again.';
                } else if (xhr.status === 403) {
                    errorMessage = 'Access denied. You don\'t have permission.';
                } else if (xhr.status === 404) {
                    errorMessage = 'Resource not found.';
                } else if (xhr.status === 422) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        ToastNotification.validationErrors(xhr.responseJSON.errors);
                        return;
                    }
                    errorMessage = 'Validation error. Please check your input.';
                } else if (xhr.status === 500) {
                    errorMessage = 'Server error. Please try again later.';
                }

                // Don't show error for already handled cases
                if (!settings.skipGlobalErrorHandler) {
                    ToastNotification.error(errorMessage);
                }
            }
        });
    });

    // Make it available globally
    window.showToast = function(message, type, options) {
        ToastNotification.show(message, type, options);
    };

})(jQuery);

