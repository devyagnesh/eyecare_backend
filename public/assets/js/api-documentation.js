/**
 * API Documentation JavaScript
 * Handles search, filtering, copy-to-clipboard, and tab management
 */

(function($) {
    'use strict';

    // Initialize when DOM is ready
    $(document).ready(function() {
        initializeApiDocumentation();
    });

    /**
     * Initialize API Documentation features
     */
    function initializeApiDocumentation() {
        initializeSearch();
        initializeFilters();
        initializeCopyButtons();
        initializeTabSwitching();
        initializeKeyboardShortcuts();
    }

    /**
     * Initialize search functionality
     */
    function initializeSearch() {
        const searchInput = document.getElementById('api-search');
        const searchClear = document.getElementById('search-clear');
        const searchStats = document.getElementById('search-results');
        const resultsCount = document.getElementById('results-count');

        if (!searchInput) return;

        let searchTimeout;

        // Search input handler
        $(searchInput).on('input', function() {
            const searchTerm = $(this).val().toLowerCase().trim();
            $(searchClear).toggleClass('visible', searchTerm.length > 0);

            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                performSearch(searchTerm);
            }, 300);
        });

        // Clear search handler
        $(searchClear).on('click', function() {
            $(searchInput).val('');
            $(searchClear).removeClass('visible');
            performSearch('');
            $(searchInput).focus();
        });

        // Perform search across all tabs
        function performSearch(term) {
            const currentTab = $('.nav-link.active').attr('data-bs-target');
            let visibleCount = 0;

            if (term === '') {
                // Show all when search is empty
                $('.endpoint-card, .docs-group').show();
                $(searchStats).removeClass('visible');
                $('.no-results').hide();
                return;
            }

            // Search in current tab
            if (currentTab === '#endpoints') {
                $('.endpoint-card').each(function() {
                    const $card = $(this);
                    const name = $card.data('endpoint-name') || '';
                    const method = $card.data('endpoint-method') || '';
                    const url = $card.data('endpoint-url') || '';
                    const description = $card.data('endpoint-description') || '';

                    const matches = name.includes(term) || 
                                  method.includes(term) || 
                                  url.includes(term) || 
                                  description.includes(term);

                    if (matches) {
                        $card.show();
                        $card.closest('.docs-group').show();
                        visibleCount++;
                    } else {
                        $card.hide();
                    }
                });

                // Hide groups with no visible endpoints
                $('.docs-group').each(function() {
                    const $group = $(this);
                    const visibleInGroup = $group.find('.endpoint-card:visible').length;
                    if (visibleInGroup === 0) {
                        $group.hide();
                    }
                });

                if (visibleCount === 0) {
                    $('#no-results-endpoints').show();
                } else {
                    $('#no-results-endpoints').hide();
                }
            } else if (currentTab === '#examples') {
                $('.endpoint-card').each(function() {
                    const $card = $(this);
                    const name = $card.data('endpoint-name') || '';
                    const method = $card.data('endpoint-method') || '';

                    const matches = name.includes(term) || method.includes(term);

                    if (matches) {
                        $card.show();
                        visibleCount++;
                    } else {
                        $card.hide();
                    }
                });

                if (visibleCount === 0) {
                    $('#no-results-examples').show();
                } else {
                    $('#no-results-examples').hide();
                }
            }

            $(resultsCount).text(visibleCount);
            $(searchStats).toggleClass('visible', term !== '');
        }
    }

    /**
     * Initialize filter pills
     */
    function initializeFilters() {
        const filterPills = $('.filter-pill');
        let currentFilter = 'all';

        filterPills.on('click', function() {
            filterPills.removeClass('active');
            $(this).addClass('active');
            currentFilter = $(this).data('filter');
            applyFilter(currentFilter);
        });

        function applyFilter(filter) {
            const currentTab = $('.nav-link.active').attr('data-bs-target');

            if (currentTab === '#endpoints' || currentTab === '#examples') {
                if (filter === 'all') {
                    $('.endpoint-card').show();
                } else {
                    $('.endpoint-card').each(function() {
                        const method = $(this).data('endpoint-method') || '';
                        if (method === filter) {
                            $(this).show();
                        } else {
                            $(this).hide();
                        }
                    });
                }

                // Update search stats
                const visibleCount = $('.endpoint-card:visible').length;
                $('#results-count').text(visibleCount);
                $('#search-results').toggleClass('visible', filter !== 'all' || $('#api-search').val().trim() !== '');
            }
        }
    }

    /**
     * Initialize copy to clipboard functionality
     */
    function initializeCopyButtons() {
        // Global copy function
        window.copyCode = function(elementId, button) {
            const element = document.getElementById(elementId);
            if (!element) {
                console.error('Element not found:', elementId);
                return;
            }

            let text = '';
            
            // Get text from pre or code element
            if (element.tagName === 'PRE') {
                text = element.textContent || element.innerText;
            } else if (element.querySelector('code')) {
                text = element.querySelector('code').textContent || element.querySelector('code').innerText;
            } else {
                text = element.textContent || element.innerText;
            }

            // Copy to clipboard
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopySuccess(button);
                    showToastNotification('Code copied to clipboard!', 'success');
                }).catch(function(err) {
                    console.error('Failed to copy:', err);
                    fallbackCopyTextToClipboard(text);
                    showCopySuccess(button);
                    showToastNotification('Code copied to clipboard!', 'success');
                });
            } else {
                // Fallback for older browsers
                fallbackCopyTextToClipboard(text);
                showCopySuccess(button);
                showToastNotification('Code copied to clipboard!', 'success');
            }
        };

        // Copy headers function
        window.copyHeaders = function(elementId, button) {
            const element = document.getElementById(elementId);
            if (!element) return;

            const text = element.textContent || element.innerText;

            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(function() {
                    showCopySuccess(button);
                    showToastNotification('Headers copied to clipboard!', 'success');
                }).catch(function(err) {
                    console.error('Failed to copy:', err);
                    fallbackCopyTextToClipboard(text);
                    showCopySuccess(button);
                    showToastNotification('Headers copied to clipboard!', 'success');
                });
            } else {
                fallbackCopyTextToClipboard(text);
                showCopySuccess(button);
                showToastNotification('Headers copied to clipboard!', 'success');
            }
        };

        // Fallback copy method for older browsers
        function fallbackCopyTextToClipboard(text) {
            const textArea = document.createElement('textarea');
            textArea.value = text;
            textArea.style.position = 'fixed';
            textArea.style.left = '-999999px';
            textArea.style.top = '-999999px';
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();

            try {
                const successful = document.execCommand('copy');
                if (!successful) {
                    console.error('Fallback: Copy command was unsuccessful');
                }
            } catch (err) {
                console.error('Fallback: Oops, unable to copy', err);
            }

            document.body.removeChild(textArea);
        }

        // Show copy success feedback
        function showCopySuccess(button) {
            const $btn = $(button);
            const originalHtml = $btn.html();
            
            $btn.html('<i class="ri-check-line"></i> <span>Copied!</span>');
            $btn.addClass('copied');
            
            setTimeout(function() {
                $btn.html(originalHtml);
                $btn.removeClass('copied');
            }, 2000);
        }
    }

    /**
     * Initialize tab switching
     */
    function initializeTabSwitching() {
        // Handle tab change events
        $('#api-docs-tabs button[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
            const targetTab = $(e.target).attr('data-bs-target');
            
            // Clear search when switching tabs
            $('#api-search').val('');
            $('#search-clear').removeClass('visible');
            
            // Show all content in the new tab
            if (targetTab === '#endpoints' || targetTab === '#examples') {
                $('.endpoint-card').show();
                $('.docs-group').show();
                $('.no-results').hide();
            }

            // Reset filters
            $('.filter-pill').removeClass('active');
            $('.filter-pill[data-filter="all"]').addClass('active');
        });
    }

    /**
     * Initialize keyboard shortcuts
     */
    function initializeKeyboardShortcuts() {
        $(document).on('keydown', function(e) {
            // Focus search with "/" key (when not in input/textarea)
            if (e.key === '/' && 
                !e.ctrlKey && 
                !e.metaKey && 
                document.activeElement.tagName !== 'INPUT' && 
                document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                const searchInput = document.getElementById('api-search');
                if (searchInput) {
                    searchInput.focus();
                    searchInput.select();
                }
            }

            // Clear search with Escape key
            if (e.key === 'Escape' && document.activeElement.id === 'api-search') {
                $('#api-search').val('');
                $('#search-clear').removeClass('visible');
                $('.endpoint-card, .docs-group').show();
                $('.no-results').hide();
                $('#search-results').removeClass('visible');
                document.activeElement.blur();
            }
        });
    }

    /**
     * Show toast notification
     */
    function showToastNotification(message, type) {
        // Use global toast notification system if available
        if (typeof window.ToastNotification !== 'undefined') {
            if (type === 'success') {
                window.ToastNotification.success(message);
            } else if (type === 'error') {
                window.ToastNotification.error(message);
            } else {
                window.ToastNotification.info(message);
            }
        } else if (typeof Swal !== 'undefined') {
            // Fallback to SweetAlert2
            const Toast = Swal.mixin({
                toast: true,
                position: 'bottom-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            Toast.fire({
                icon: type === 'success' ? 'success' : (type === 'error' ? 'error' : 'info'),
                title: message
            });
        } else {
            // Final fallback - console log
            console.log(type.toUpperCase() + ':', message);
        }
    }

})(jQuery);

