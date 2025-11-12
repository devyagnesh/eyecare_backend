/**
 * AJAX Filter Handler
 * Handles filter forms without page refresh using jQuery AJAX exclusively
 * Auto-submits on input change - no submit button needed
 * 
 * @author Eyecare Admin Panel
 * @version 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Initialize AJAX filters
     * Auto-submits on input change, no submit button needed
     */
    $(document).ready(function() {
        // Auto-submit filter forms on input change (with debounce)
        $('form[data-ajax-filter="true"]').on('change input', 'input, select, textarea', function() {
            const $form = $(this).closest('form[data-ajax-filter="true"]');
            if ($form.length) {
                // Debounce: wait 300ms after last change before submitting
                clearTimeout($form.data('filter-timeout'));
                $form.data('filter-timeout', setTimeout(function() {
                    $form.trigger('submit');
                }, 300));
            }
        });
        
        // Handle filter forms (also triggered by auto-submit above)
        $('form[data-ajax-filter="true"]').on('submit', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $form = $(this);
            const tableId = $form.data('table-id');
            
            if (!tableId) {
                console.warn('Filter form missing data-table-id attribute');
                return;
            }
            
            // Get form data
            const formData = $form.serialize();
            const baseUrl = $form.attr('action');
            
            // Build URL with query params for GET request (but don't update browser URL)
            const requestUrl = baseUrl + '?' + formData;
            
            // Don't update browser URL - keep it clean and professional (no query parameters visible)
            
            // Make AJAX request using jQuery
            $.ajax({
                url: requestUrl,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json, text/html'
                },
                success: function(response) {
                    // Handle response
                    let htmlContent = null;
                    
                    if (typeof response === 'string') {
                        htmlContent = response;
                    } else if (response && response.html) {
                        htmlContent = response.html;
                    }
                    
                    if (htmlContent) {
                        // Parse HTML response
                        const $response = $(htmlContent);
                        const $newTable = $response.find(tableId);
                        
                        if ($newTable.length) {
                            // Get the table body
                            const $newTbody = $newTable.find('tbody');
                            const $currentTable = $(tableId);
                            
                            if ($newTbody.length && $currentTable.length) {
                                // Destroy existing DataTable if it exists
                                if ($.fn.DataTable && $currentTable.DataTable) {
                                    try {
                                        $currentTable.DataTable().destroy();
                                    } catch (e) {
                                        console.warn('Error destroying DataTable:', e);
                                    }
                                }
                                
                                // Replace table body
                                $currentTable.find('tbody').html($newTbody.html());
                                
                                // Reinitialize DataTable with clean settings (no search, no per page)
                                if (typeof window.initDataTable !== 'undefined') {
                                    window.initDataTable(tableId, {
                                        searching: false,
                                        lengthChange: false
                                    });
                                } else if ($.fn.DataTable) {
                                    initializeDataTable(tableId);
                                }
                                
                                // Don't show toast for filter applications (silent update)
                            } else {
                                // If table structure changed, replace entire table
                                const $tableContainer = $currentTable.closest('.table-responsive');
                                const $newTableContainer = $response.find('.table-responsive');
                                
                                if ($newTableContainer.length) {
                                    $tableContainer.replaceWith($newTableContainer);
                                    
                                    // Reinitialize DataTable with clean settings (no search, no per page)
                                    if (typeof window.initDataTable !== 'undefined') {
                                        window.initDataTable(tableId, {
                                            searching: false,
                                            lengthChange: false
                                        });
                                    } else if ($.fn.DataTable) {
                                        initializeDataTable(tableId);
                                    }
                                    
                                    // Don't show toast for filter applications (silent update)
                                } else {
                                    // Fallback: reload page (use base URL, not request URL)
                                    window.location.href = baseUrl;
                                }
                            }
                        } else {
                            // If we can't find the table, reload the page
                            window.location.href = baseUrl;
                        }
                    } else {
                        // Unexpected response format, reload page
                        window.location.href = baseUrl;
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Log error
                    console.error('Filter AJAX Error:', {
                        url: requestUrl,
                        status: xhr.status,
                        statusText: xhr.statusText,
                        error: errorThrown
                    });
                    
                    // Only show error toast for actual errors (not silent)
                    let errorMessage = 'Failed to apply filters';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 0) {
                        errorMessage = 'Network error. Please check your connection.';
                    } else if (xhr.status === 500) {
                        errorMessage = 'Server error. Please try again later.';
                    }
                    
                    // Show error toast only for real errors
                    if (typeof window.ToastNotification !== 'undefined') {
                        window.ToastNotification.error(errorMessage);
                    } else if (typeof window.ajaxAlert !== 'undefined') {
                        window.ajaxAlert.error(errorMessage);
                    }
                }
            });
        });
        
        // Handle filter reset
        $('.ajax-filter-reset').on('click', function(e) {
            e.preventDefault();
            
            const $form = $(this).closest('form');
            const tableId = $form.data('table-id');
            
            if (!tableId) {
                return;
            }
            
            // Reset form
            $form[0].reset();
            
            // Get base URL
            const baseUrl = $form.attr('action');
            
            // Don't update URL - keep it clean and professional
            
            // Reload table content using jQuery AJAX
            $.ajax({
                url: baseUrl,
                type: 'GET',
                dataType: 'json',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json, text/html'
                },
                success: function(response) {
                    let htmlContent = null;
                    
                    if (typeof response === 'string') {
                        htmlContent = response;
                    } else if (response && response.html) {
                        htmlContent = response.html;
                    }
                    
                    if (htmlContent) {
                        const $response = $(htmlContent);
                        const $newTable = $response.find(tableId);
                        
                        if ($newTable.length) {
                            const $newTbody = $newTable.find('tbody');
                            const $currentTable = $(tableId);
                            
                            if ($newTbody.length && $currentTable.length) {
                                // Destroy existing DataTable
                                if ($.fn.DataTable && $currentTable.DataTable) {
                                    try {
                                        $currentTable.DataTable().destroy();
                                    } catch (e) {
                                        console.warn('Error destroying DataTable:', e);
                                    }
                                }
                                
                                // Replace table body
                                $currentTable.find('tbody').html($newTbody.html());
                                
                                // Reinitialize DataTable with clean settings (no search, no per page)
                                if (typeof window.initDataTable !== 'undefined') {
                                    window.initDataTable(tableId, {
                                        searching: false,
                                        lengthChange: false
                                    });
                                } else if ($.fn.DataTable) {
                                    initializeDataTable(tableId);
                                }
                                
                                // Don't show toast for filter reset (silent update)
                            }
                        }
                    } else {
                        window.location.href = baseUrl;
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    console.error('Filter Reset Error:', {
                        url: baseUrl,
                        status: xhr.status,
                        error: errorThrown
                    });
                    window.location.href = baseUrl;
                }
            });
        });
    });
    
    /**
     * Initialize DataTable with standard settings (fallback if initDataTable not available)
     */
    function initializeDataTable(tableId) {
        const $table = $(tableId);
        
        if (!$table.length) {
            return;
        }
        
        // Check if DataTable is already initialized
        if ($.fn.DataTable && $table.DataTable) {
            try {
                // Destroy if exists
                $table.DataTable().destroy();
            } catch (e) {
                // Ignore if not initialized
            }
        }
        
        // Initialize DataTable with standard settings
        if ($.fn.DataTable) {
            $table.DataTable({
                language: {
                    searchPlaceholder: 'Search...',
                    sSearch: '',
                    lengthMenu: 'Show _MENU_ entries',
                    info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                    infoEmpty: 'Showing 0 to 0 of 0 entries',
                    infoFiltered: '(filtered from _MAX_ total entries)',
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Previous'
                    },
                    emptyTable: 'No data available in table',
                    zeroRecords: 'No matching records found'
                },
                pageLength: 10, // Default to 10 entries per page
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
                order: [[0, 'desc']],
                responsive: true,
                searching: false, // Hide search box
                lengthChange: false, // Hide per page dropdown
                destroy: true, // Allow re-initialization
                drawCallback: function(settings) {
                    // Update serial numbers on each page
                    const api = this.api();
                    const pageInfo = api.page.info();
                    api.column(0, {page: 'current'}).nodes().each(function(cell, i) {
                        cell.innerHTML = (pageInfo.start + i + 1);
                    });
                }
            });
        }
    }

})(jQuery);
