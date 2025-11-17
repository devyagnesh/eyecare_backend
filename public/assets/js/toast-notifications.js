/**
 * Global Toast Notification System using Toastify
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
            // Default colors for each type
            const colorMap = {
                'success': '#28a745',
                'error': '#dc3545',
                'warning': '#ffc107',
                'info': '#17a2b8',
                'danger': '#dc3545'
            };

            const backgroundColor = options.backgroundColor || colorMap[type] || colorMap['info'];
            const duration = options.duration || (type === 'error' ? 5000 : 3000);
            const position = options.position || 'top-right';
            const gravity = position.includes('bottom') ? 'bottom' : 'top';
            const positionLeft = position.includes('left');

            // Use Toastify if available
            if (typeof Toastify !== 'undefined') {
                const toast = Toastify({
                    text: message,
                    duration: duration,
                    gravity: gravity,
                    positionLeft: positionLeft,
                    backgroundColor: backgroundColor,
                    close: true,
                    stopOnFocus: true,
                    className: 'toastify toastify-' + type,
                    onClick: options.onClick || function() {},
                    callback: options.callback || function() {}
                });

                toast.showToast();
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
                
                // Check for consistent API error format: {success: false, message: "..."}
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.error) {
                        errorMessage = xhr.responseJSON.error;
                    } else if (xhr.responseJSON.errors) {
                        // Handle validation errors - show first error message
                        const errors = xhr.responseJSON.errors;
                        if (typeof errors === 'object') {
                            const firstError = Object.values(errors)[0];
                            if (Array.isArray(firstError) && firstError.length > 0) {
                                errorMessage = firstError[0];
                            } else if (typeof firstError === 'string') {
                                errorMessage = firstError;
                            }
                        }
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

