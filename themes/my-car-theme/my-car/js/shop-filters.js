/**
 * Shop Filters and Sorting for WooCommerce
 */

(function($) {
    'use strict';

    let filterTimeout;
    let isApplyingFilter = false;

    // Function to apply filters
    function applyFilters() {
        if (isApplyingFilter) return;
        
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(function() {
            isApplyingFilter = true;
            
            // Get current filter values
            const activeCategoryBtn = $('.y-c-category-filter-btn.active');
            const categoryFilter = activeCategoryBtn.length ? activeCategoryBtn.data('filter') : 'all';
            const categorySlug = activeCategoryBtn.data('category-slug') || categoryFilter;
            
            const sortDropdown = $('[data-y="sort-by-price"] .y-c-dropdown-toggle');
            const sortValue = sortDropdown.attr('data-value') || 'default';
            
            // Get price range values
            const minValue = $('#min-value').text();
            const maxValue = $('#max-value').text();
            const minPrice = parseInt(minValue) || 100;
            const maxPrice = parseInt(maxValue) || 1500;
            
            // Build URL parameters
            const params = new URLSearchParams(window.location.search);
            
            // Category filter
            if (categorySlug && categorySlug !== 'all') {
                params.set('product_cat', categorySlug);
            } else {
                params.delete('product_cat');
            }
            
            // Sort order
            if (sortValue !== 'default') {
                if (sortValue === 'price-asc') {
                    params.set('orderby', 'price');
                    params.set('order', 'asc');
                } else if (sortValue === 'price-desc') {
                    params.set('orderby', 'price');
                    params.set('order', 'desc');
                } else if (sortValue === 'name-asc') {
                    params.set('orderby', 'title');
                    params.set('order', 'asc');
                }
            } else {
                params.set('orderby', 'price');
                params.set('order', 'desc');
            }
            
            // Price range - using min_price and max_price for WooCommerce
            if (minPrice && maxPrice && (minPrice !== 100 || maxPrice !== 1500)) {
                params.set('min_price', minPrice);
                params.set('max_price', maxPrice);
            } else {
                params.delete('min_price');
                params.delete('max_price');
            }
            
            // Remove pagination when filtering
            params.delete('paged');
            
            // Reload page with new parameters
            const newUrl = window.location.pathname + (params.toString() ? '?' + params.toString() : '');
            window.location.href = newUrl;
        }, 300);
    }

    // Initialize filters when DOM is ready
    $(document).ready(function() {
        // Initialize price range slider
        if (typeof PriceRangeSlider !== 'undefined') {
            const rangeSlider = new PriceRangeSlider();
            rangeSlider.initialize();
        }
        
        initializeShopFilters();
        loadFilterStateFromURL();
    });

    function initializeShopFilters() {
        // Initialize dropdown toggles
        $('.y-c-dropdown-toggle').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const $dropdown = $(this).closest('.y-c-dropdown');
            const $allDropdowns = $('.y-c-dropdown');
            
            // Close all other dropdowns
            $allDropdowns.not($dropdown).removeClass('active');
            
            // Toggle current dropdown
            $dropdown.toggleClass('active');
        });
        
        // Close dropdowns when clicking outside
        $(document).on('click', function(e) {
            if (!$(e.target).closest('.y-c-dropdown').length) {
                $('.y-c-dropdown').removeClass('active');
            }
        });
        
        // Category Filter Tabs
        $('.y-c-category-filter-btn').on('click', function(e) {
            e.preventDefault();
            const filter = $(this).data('filter');
            const categorySlug = $(this).data('category-slug') || filter;
            
            // Update active state
            $('.y-c-category-filter-btn').removeClass('active');
            $(this).addClass('active');
            
            // Update category dropdown if exists
            const categoryDropdown = $('[data-y="sort-by-category"] .y-c-dropdown-toggle');
            if (categoryDropdown.length) {
                if (filter === 'all') {
                    categoryDropdown.attr('data-value', 'all');
                    categoryDropdown.find('.y-c-dropdown-selected').text('الفئة');
                } else {
                    categoryDropdown.attr('data-value', categorySlug);
                    categoryDropdown.find('.y-c-dropdown-selected').text($(this).text());
                }
            }
            
            // Apply filters
            applyFilters();
        });

        // Category Dropdown
        $('[data-y="sort-by-category"] .y-c-dropdown-menu button').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const categorySlug = $(this).data('value');
            
            // Update dropdown
            const toggle = $(this).closest('.y-c-dropdown').find('.y-c-dropdown-toggle');
            toggle.attr('data-value', categorySlug);
            toggle.find('.y-c-dropdown-selected').text($(this).text().trim());
            
            // Close dropdown
            $(this).closest('.y-c-dropdown').removeClass('active');
            
            // Update category filter tabs
            if (categorySlug === 'all') {
                $('.y-c-category-filter-btn[data-filter="all"]').click();
            } else {
                const filterBtn = $('.y-c-category-filter-btn[data-category-slug="' + categorySlug + '"]');
                if (filterBtn.length) {
                    filterBtn.click();
                } else {
                    // Update active state manually if button exists with different data attribute
                    $('.y-c-category-filter-btn').removeClass('active');
                    applyFilters();
                }
            }
        });

        // Sort Dropdown
        $('[data-y="sort-by-price"] .y-c-dropdown-menu button').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const sortValue = $(this).data('value');
            
            // Update dropdown
            const toggle = $(this).closest('.y-c-dropdown').find('.y-c-dropdown-toggle');
            toggle.attr('data-value', sortValue);
            toggle.find('.y-c-dropdown-selected').text($(this).text().trim());
            
            // Close dropdown
            $(this).closest('.y-c-dropdown').removeClass('active');
            
            // Apply filters
            applyFilters();
        });

        // Price Range Slider - update values but don't apply filter automatically
        $(document).on('priceChange', '.y-c-slider-track', function(e) {
            // Just update the displayed values, don't apply filter
            currentMinPrice = e.detail.min;
            currentMaxPrice = e.detail.max;
        });

        // Apply Price Filter Button
        $('[data-y="apply-price-filter"]').on('click', function(e) {
            e.preventDefault();
            applyFilters();
        });
    }

    // Load filter state from URL parameters
    function loadFilterStateFromURL() {
        const urlParams = new URLSearchParams(window.location.search);
        
        // Load category filter
        const productCat = urlParams.get('product_cat');
        if (productCat) {
            const filterBtn = $('.y-c-category-filter-btn[data-category-slug="' + productCat + '"]');
            if (filterBtn.length) {
                $('.y-c-category-filter-btn').removeClass('active');
                filterBtn.addClass('active');
                
                // Update dropdown
                const categoryDropdown = $('[data-y="sort-by-category"] .y-c-dropdown-toggle');
                if (categoryDropdown.length) {
                    categoryDropdown.attr('data-value', productCat);
                    categoryDropdown.find('.y-c-dropdown-selected').text(filterBtn.text());
                }
            }
        }
        
        // Load sort order
        const orderby = urlParams.get('orderby');
        const order = urlParams.get('order');
        if (orderby && order) {
            let sortValue = 'default';
            if (orderby === 'price' && order === 'asc') {
                sortValue = 'price-asc';
            } else if (orderby === 'price' && order === 'desc') {
                sortValue = 'default'; // Default is price desc
            } else if (orderby === 'title' && order === 'asc') {
                sortValue = 'name-asc';
            }
            
            const sortDropdown = $('[data-y="sort-by-price"] .y-c-dropdown-toggle');
            if (sortDropdown.length && sortValue !== 'default') {
                sortDropdown.attr('data-value', sortValue);
                const sortBtn = $('[data-y="sort-by-price"] .y-c-dropdown-menu button[data-value="' + sortValue + '"]');
                if (sortBtn.length) {
                    sortDropdown.find('.y-c-dropdown-selected').text(sortBtn.text());
                }
            }
        }
        
        // Load price range
        const minPrice = urlParams.get('min_price');
        const maxPrice = urlParams.get('max_price');
        if (minPrice && maxPrice) {
            // Price range slider will be updated by range-slider.js after initialization
            // We just need to set the values in the display
            $('#min-value').text(parseInt(minPrice));
            $('#max-value').text(parseInt(maxPrice));
        }
    }

})(jQuery);
