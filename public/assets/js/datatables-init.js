/**
 * DataTables Initialization
 * Standardized DataTables setup matching theme styling
 * Works with AJAX filters without page refresh
 */

(function($) {
    'use strict';

    /**
     * Initialize DataTable with theme-standard settings
     * @param {string} tableId - Table selector (e.g., '#users-table')
     * @param {object} options - Additional DataTable options
     */
    window.initDataTable = function(tableId, options = {}) {
        const $table = $(tableId);
        
        if (!$table.length) {
            console.warn('Table not found:', tableId);
            return null;
        }

        // Check if DataTable is already initialized
        if ($.fn.DataTable && $table.DataTable) {
            try {
                $table.DataTable().destroy();
            } catch (e) {
                // Ignore if not initialized
            }
        }

        // Default options matching theme
        const defaultOptions = {
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
            pageLength: options.pageLength || 10, // Default to 10 entries per page
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
            order: [[0, 'desc']],
            responsive: true,
            paging: true,
            searching: false, // Hide search box by default
            lengthChange: false, // Hide per page dropdown by default
            ordering: true,
            info: true,
            autoWidth: false,
            destroy: true, // Allow re-initialization
            drawCallback: function(settings) {
                // Update serial numbers on each page
                const api = this.api();
                const pageInfo = api.page.info();
                api.column(0, {page: 'current'}).nodes().each(function(cell, i) {
                    cell.innerHTML = (pageInfo.start + i + 1);
                });
                
                // Reinitialize tooltips if needed
                if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                    const tooltipTriggerList = [].slice.call($table[0].querySelectorAll('[data-bs-toggle="tooltip"]'));
                    tooltipTriggerList.map(function(tooltipTriggerEl) {
                        return new bootstrap.Tooltip(tooltipTriggerEl);
                    });
                }
            }
        };

        // Merge with custom options
        const finalOptions = $.extend(true, {}, defaultOptions, options);

        // Initialize DataTable
        if ($.fn.DataTable) {
            return $table.DataTable(finalOptions);
        } else {
            console.error('DataTables library not loaded');
            return null;
        }
    };

    /**
     * Initialize all DataTables on page load
     */
    $(document).ready(function() {
        // Auto-initialize tables with data-datatable attribute
        $('table[data-datatable="true"]').each(function() {
            const $table = $(this);
            const tableId = '#' + $table.attr('id');
            const pageLength = $table.data('page-length') || 10;
            const orderColumn = $table.data('order-column') || 0;
            const orderDirection = $table.data('order-direction') || 'desc';
            
            initDataTable(tableId, {
                pageLength: pageLength,
                order: [[orderColumn, orderDirection]]
            });
        });
    });

    // Make it available globally
    window.DataTableInit = {
        init: initDataTable
    };

})(jQuery);

