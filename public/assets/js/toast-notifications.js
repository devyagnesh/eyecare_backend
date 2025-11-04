/**
 * Global Toast Notification System
 * Provides unified toast notifications for success, error, warning, and info messages
 */

(function($) {
    'use strict';

    // Toast container
    let toastContainer = null;

    /**
     * Initialize toast container
     */
    function initToastContainer() {
        if (!toastContainer) {
            toastContainer = $('<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 9999;"></div>');
            $('body').append(toastContainer);
        }
        return toastContainer;
    }

    /**
     * Show Bootstrap Toast
     */
    function showBootstrapToast(type, message, title, duration) {
        const container = initToastContainer();
        const toastId = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        
        const iconMap = {
            success: '<i class="bx bx-check-circle fs-18"></i>',
            error: '<i class="bx bx-error-circle fs-18"></i>',
            warning: '<i class="bx bx-error fs-18"></i>',
            info: '<i class="bx bx-info-circle fs-18"></i>'
        };

        const bgMap = {
            success: 'bg-success',
            error: 'bg-danger',
            warning: 'bg-warning',
            info: 'bg-info'
        };

        const toastHtml = `
            <div id="${toastId}" class="toast align-items-center text-white ${bgMap[type]} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <span class="me-2">${iconMap[type] || ''}</span>
                        <div>
                            ${title ? '<strong>' + title + '</strong><br>' : ''}
                            ${message}
                        </div>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        `;

        const $toast = $(toastHtml);
        container.append($toast);

        const toast = new bootstrap.Toast($toast[0], {
            autohide: true,
            delay: duration || (type === 'error' ? 5000 : 3000)
        });

        $toast.on('hidden.bs.toast', function() {
            $toast.remove();
            if (container.children().length === 0) {
                container.remove();
                toastContainer = null;
            }
        });

        toast.show();
    }

    /**
     * Show SweetAlert2 Toast
     */
    function showSweetAlertToast(type, message, title, duration) {
        if (typeof Swal !== 'undefined') {
            const iconMap = {
                success: 'success',
                error: 'error',
                warning: 'warning',
                info: 'info'
            };

            Swal.fire({
                icon: iconMap[type] || 'info',
                title: title || (type === 'error' ? 'Error' : type === 'success' ? 'Success' : type.charAt(0).toUpperCase() + type.slice(1)),
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: duration || (type === 'error' ? 5000 : 3000),
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        } else {
            // Fallback to Bootstrap toast
            showBootstrapToast(type, message, title, duration);
        }
    }

    /**
     * Global Toast Notification Function
     */
    window.showToast = function(type, message, title, options) {
        const defaults = {
            useSweetAlert: true, // Prefer SweetAlert2 if available
            duration: null // Auto-detect based on type
        };
        const config = $.extend({}, defaults, options || {});

        if (config.useSweetAlert && typeof Swal !== 'undefined') {
            showSweetAlertToast(type, message, title, config.duration);
        } else {
            showBootstrapToast(type, message, title, config.duration);
        }
    };

    /**
     * Convenience methods
     */
    window.showSuccessToast = function(message, title, options) {
        window.showToast('success', message, title || 'Success', options);
    };

    window.showErrorToast = function(message, title, options) {
        window.showToast('error', message, title || 'Error', options);
    };

    window.showWarningToast = function(message, title, options) {
        window.showToast('warning', message, title || 'Warning', options);
    };

    window.showInfoToast = function(message, title, options) {
        window.showToast('info', message, title || 'Info', options);
    };

    /**
     * Initialize on page load
     * Flash messages are handled via data attributes in the HTML
     */
    $(document).ready(function() {
        // Check for flash messages in data attributes
        const flashSuccess = $('body').data('flash-success');
        const flashError = $('body').data('flash-error');
        const flashWarning = $('body').data('flash-warning');
        const flashInfo = $('body').data('flash-info');

        if (flashSuccess) {
            showSuccessToast(flashSuccess);
        }
        if (flashError) {
            showErrorToast(flashError);
        }
        if (flashWarning) {
            showWarningToast(flashWarning);
        }
        if (flashInfo) {
            showInfoToast(flashInfo);
        }

        // Check for validation errors
        const validationErrors = $('body').data('validation-errors');
        if (validationErrors && Array.isArray(validationErrors)) {
            validationErrors.forEach(function(error) {
                showErrorToast(error);
            });
        }
    });

})(jQuery);

