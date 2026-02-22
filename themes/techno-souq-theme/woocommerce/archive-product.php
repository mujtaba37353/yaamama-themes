<?php
/**
 * The Template for displaying product archives, including the main shop page
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// Remove default WooCommerce wrappers and sidebar
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

// Enqueue shop archive styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-shop-archive', $techno_souq_path . '/templates/shop-archive/y-shop-archive.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-cards',
    'techno-souq-buttons'
), $theme_version);

// Enqueue shop archive scripts
wp_enqueue_script('techno-souq-products', $techno_souq_path . '/js/products.js', array('techno-souq-shared-components'), $theme_version, true);
wp_enqueue_script('techno-souq-shop-archive', $techno_souq_path . '/js/shop-archive.js', array('techno-souq-products'), $theme_version, true);
?>

<main data-y="shop-main">
    <section class="y-l-container" data-y="shop-container">
        <?php
        // Check if there are any products (real or demo)
        $has_products = woocommerce_product_loop();
        
        if (!$has_products) :
            // No products - show only message
            ?>
            <div class="y-l-shop-section" data-y="shop-section" style="text-align: center; padding: 3rem 1rem;">
                <p class="y-c-subtitle" style="font-size: 1.25rem; color: var(--y-color-secondary-text, #666);">
                    <?php esc_html_e('لا يوجد منتجات', 'techno-souq-theme'); ?>
                </p>
            </div>
            <?php
        else :
            // Has products - show full shop page
            ?>
        <div class="y-l-shop-section" data-y="shop-section">
            <!-- Breadcrumb -->
            <p class="y-c-subtitle" data-y="shop-breadcrumb">
                <?php
                if (function_exists('woocommerce_breadcrumb')) {
                    $breadcrumb = woocommerce_breadcrumb(array('delimiter' => ' > ', 'wrap_before' => '', 'wrap_after' => '', 'home' => 'الرئيسية'), false);
                    echo $breadcrumb;
                } else {
                    echo 'الرئيسية > ' . woocommerce_page_title(false);
                }
                ?>
            </p>

            <!-- Product List Area -->
            <div class="y-l-shop-content">
                <!-- Promo Banner -->
                <div class="y-c-shop-promo" data-y="shop-promo-banner">
                    <div class="y-c-slide-text" data-y="promo-text-section">
                        <h1 data-y="promo-title"><?php esc_html_e('أحدث الأجهزة', 'techno-souq-theme'); ?></h1>
                        <p data-y="promo-subtitle"><?php esc_html_e('الجيل الجديد من الأجهزة بين يديك الآن', 'techno-souq-theme'); ?></p>
                    </div>
                    <div class="y-c-slide-image" data-y="promo-image-section">
                        <img src="<?php echo esc_url(techno_souq_asset_url('phone-slider.png')); ?>" alt="<?php esc_attr_e('Latest Devices', 'techno-souq-theme'); ?>" data-y="promo-image">
                    </div>
                </div>
                <br data-y="promo-products-separator">

                <!-- Controls Header -->
                <div class="y-l-header-filter-container" data-y="products-header">
                    <h2 class="y-c-section-title" data-y="products-title"><?php esc_html_e('جميع المنتجات', 'techno-souq-theme'); ?></h2>

                    <div class="y-l-header-filter" data-y="shop-controls">
                        <!-- Filter Dropdown -->
                        <div class="y-c-shop-menu" id="filter-dropdown" data-y="filter-dropdown">
                            <button class="y-c-shop-menu-button" data-y="filter-dropdown-btn">
                                <i class="fas fa-filter y-c-shop-menu-icon" data-y="filter-icon"></i>
                                <span class="y-c-shop-menu-text" data-y="filter-dropdown-text">
                                    <?php esc_html_e('تصفية المنتجات', 'techno-souq-theme'); ?>
                                </span>
                                <i class="fas fa-chevron-down y-c-shop-menu-text" data-y="filter-dropdown-arrow"></i>
                            </button>

                            <div class="y-c-shop-menu-dropdown y-c-filter-dropdown-content" data-y="filter-dropdown-content">
                                <!-- Category Filter -->
                                <div class="y-c-filter-section">
                                    <details open>
                                        <summary class="y-c-filter-title">
                                            <?php esc_html_e('فئات المنتجات', 'techno-souq-theme'); ?>
                                            <i class="fas fa-minus y-c-accordion-icon"></i>
                                        </summary>
                                        <div class="y-c-filter-content">
                                            <?php
                                            // Get parent categories only, excluding "Uncategorized"
                                            $parent_categories = get_terms(array(
                                                'taxonomy' => 'product_cat',
                                                'hide_empty' => false,
                                                'parent' => 0,
                                                'exclude' => array(get_option('default_product_cat')), // Exclude default category
                                            ));
                                            
                                            // Also filter out by slug in case exclude doesn't work
                                            if (!empty($parent_categories) && !is_wp_error($parent_categories)) {
                                                $parent_categories = array_filter($parent_categories, function($cat) {
                                                    return strtolower($cat->slug) !== 'uncategorized' && 
                                                           strtolower($cat->name) !== 'uncategorized';
                                                });
                                            }
                                            
                                            if (!empty($parent_categories) && !is_wp_error($parent_categories)) :
                                                foreach ($parent_categories as $parent_category) :
                                                    // Get child categories
                                                    $child_categories = get_terms(array(
                                                        'taxonomy' => 'product_cat',
                                                        'hide_empty' => false,
                                                        'parent' => $parent_category->term_id,
                                                    ));
                                                    ?>
                                                    <?php
                                                    // Get current selected category
                                                    $current_category = isset($_GET['product_cat']) ? sanitize_text_field($_GET['product_cat']) : '';
                                                    $parent_checked = ($current_category === $parent_category->slug) ? 'checked' : '';
                                                    ?>
                                                    <div class="y-c-category-group">
                                                        <label class="y-c-custom-radio y-c-category-parent">
                                                            <input type="radio" name="product_cat" value="<?php echo esc_attr($parent_category->slug); ?>" data-category-id="<?php echo esc_attr($parent_category->term_id); ?>" <?php echo $parent_checked; ?>>
                                                            <span class="y-c-radio-checkmark"></span>
                                                            <span class="y-c-radio-label"><?php echo esc_html($parent_category->name); ?></span>
                                                        </label>
                                                        <?php if (!empty($child_categories) && !is_wp_error($child_categories)) : ?>
                                                            <div class="y-c-subcategories">
                                                                <?php foreach ($child_categories as $child_category) : 
                                                                    $child_checked = ($current_category === $child_category->slug) ? 'checked' : '';
                                                                    ?>
                                                                    <label class="y-c-custom-radio y-c-category-child">
                                                                        <input type="radio" name="product_cat" value="<?php echo esc_attr($child_category->slug); ?>" data-category-id="<?php echo esc_attr($child_category->term_id); ?>" <?php echo $child_checked; ?>>
                                                                        <span class="y-c-radio-checkmark"></span>
                                                                        <span class="y-c-radio-label"><?php echo esc_html($child_category->name); ?></span>
                                                                    </label>
                                                                <?php endforeach; ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <?php
                                                endforeach;
                                            endif;
                                            ?>
                                        </div>
                                    </details>
                                </div>

                                <!-- Price Filter -->
                                <div class="y-c-filter-section">
                                    <details open>
                                        <summary class="y-c-filter-title">
                                            <?php esc_html_e('السعر', 'techno-souq-theme'); ?>
                                            <i class="fas fa-minus y-c-accordion-icon"></i>
                                        </summary>
                                        <div class="y-c-filter-content">
                                            <label class="y-c-custom-radio">
                                                <input type="radio" name="price" value="under-500">
                                                <span class="y-c-radio-checkmark"></span>
                                                <span class="y-c-radio-label"><?php esc_html_e('أقل من 500', 'techno-souq-theme'); ?></span>
                                            </label>
                                            <label class="y-c-custom-radio">
                                                <input type="radio" name="price" value="500-1000">
                                                <span class="y-c-radio-checkmark"></span>
                                                <span class="y-c-radio-label"><?php esc_html_e('من 500 إلى 1000', 'techno-souq-theme'); ?></span>
                                            </label>
                                            <label class="y-c-custom-radio">
                                                <input type="radio" name="price" value="1000-2000">
                                                <span class="y-c-radio-checkmark"></span>
                                                <span class="y-c-radio-label"><?php esc_html_e('من 1000 إلى 2000', 'techno-souq-theme'); ?></span>
                                            </label>
                                            <label class="y-c-custom-radio">
                                                <input type="radio" name="price" value="over-2000">
                                                <span class="y-c-radio-checkmark"></span>
                                                <span class="y-c-radio-label"><?php esc_html_e('أكثر من 2000', 'techno-souq-theme'); ?></span>
                                            </label>

                                            <div class="y-c-price-inputs">
                                                <input type="number" placeholder="<?php esc_attr_e('من ر.س', 'techno-souq-theme'); ?>" class="y-c-form-input y-c-price-input-sm" id="min-price">
                                                <input type="number" placeholder="<?php esc_attr_e('الى ر.س', 'techno-souq-theme'); ?>" class="y-c-form-input y-c-price-input-sm" id="max-price">
                                            </div>
                                        </div>
                                    </details>
                                </div>

                                <!-- Action Buttons -->
                                <div class="y-c-filter-actions">
                                    <button class="y-c-btn y-c-btn-primary y-c-btn-full y-c-apply-filter-btn" data-y="apply-filter-btn">
                                        <?php esc_html_e('تطبيق الفلتر', 'techno-souq-theme'); ?>
                                    </button>
                                    <button class="y-c-btn y-c-btn-secondary y-c-btn-full y-c-delete-filter-btn" data-y="delete-filter-btn">
                                        <?php esc_html_e('حذف الفلتر', 'techno-souq-theme'); ?>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Sort Dropdown -->
                        <div class="y-c-shop-menu" id="sort-dropdown" data-y="sort-dropdown">
                            <button class="y-c-shop-menu-button" data-y="sort-dropdown-btn">
                                <span data-y="sort-dropdown-text"><?php esc_html_e('فرز حسب', 'techno-souq-theme'); ?></span>
                                <i class="fas fa-chevron-down" data-y="sort-dropdown-icon"></i>
                            </button>
                            <div class="y-c-shop-menu-dropdown" data-y="sort-dropdown-menu">
                                <?php
                                // Get current orderby from WooCommerce
                                $current_orderby = isset($_GET['orderby']) ? wc_clean($_GET['orderby']) : apply_filters('woocommerce_default_catalog_orderby', get_option('woocommerce_default_catalog_orderby', 'menu_order'));
                                
                                // Use WooCommerce's default sorting options
                                $catalog_orderby_options = apply_filters('woocommerce_catalog_orderby', array(
                                    'menu_order' => __('ترتيب افتراضي', 'woocommerce'),
                                    'popularity' => __('الشعبية', 'woocommerce'),
                                    'rating' => __('التقييم', 'woocommerce'),
                                    'date' => __('الأحدث', 'woocommerce'),
                                    'price' => __('السعر: من الأقل إلى الأعلى', 'woocommerce'),
                                    'price-desc' => __('السعر: من الأعلى إلى الأقل', 'woocommerce'),
                                ));
                                
                                // Arabic translations
                                $sort_options = array(
                                    'menu_order' => __('ترتيب افتراضي', 'techno-souq-theme'),
                                    'popularity' => __('الشعبية', 'techno-souq-theme'),
                                    'rating' => __('التقييم', 'techno-souq-theme'),
                                    'date' => __('الأحدث', 'techno-souq-theme'),
                                    'price' => __('السعر: من الأقل إلى الأعلى', 'techno-souq-theme'),
                                    'price-desc' => __('السعر: من الأعلى إلى الأقل', 'techno-souq-theme'),
                                );
                                
                                foreach ($sort_options as $value => $label) :
                                    $active = ($current_orderby === $value) ? 'active' : '';
                                    // Preserve other query parameters
                                    $url = add_query_arg(array('orderby' => $value), remove_query_arg('paged'));
                                    ?>
                                    <a href="<?php echo esc_url($url); ?>" class="y-c-shop-menu-item <?php echo esc_attr($active); ?>" data-sort="<?php echo esc_attr($value); ?>">
                                        <?php echo esc_html($label); ?>
                                    </a>
                                    <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products grid -->
                <div class="y-l-product-grid" id="featured-products-container" data-y="products-grid">
                    <?php
                    woocommerce_product_loop_start();
                    if (wc_get_loop_prop('total')) {
                        while (have_posts()) {
                            the_post();
                            wc_get_template_part('content', 'product');
                        }
                    }
                    woocommerce_product_loop_end();
                    ?>
                </div>

                <!-- Pagination -->
                <ul class="y-l-pagination" id="pagination-container" data-y="pagination-container">
                    <?php
                    $pagination = paginate_links(array(
                        'base' => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
                        'format' => '',
                        'current' => max(1, get_query_var('paged')),
                        'total' => $GLOBALS['wp_query']->max_num_pages,
                        'prev_text' => '<i class="fas fa-chevron-right"></i>',
                        'next_text' => '<i class="fas fa-chevron-left"></i>',
                        'type' => 'list',
                        'end_size' => 2,
                        'mid_size' => 2,
                    ));
                    echo $pagination;
                    ?>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </section>
</main>

<script>
(function() {
    'use strict';
    
    // shop-archive.js handles dropdowns, we just need to handle category filter and accordions
    
    // Initialize filter accordions
    function initAccordions() {
        const details = document.querySelectorAll('.y-c-filter-section details');
        details.forEach(detail => {
            detail.addEventListener('toggle', function() {
                const icon = this.querySelector('.y-c-accordion-icon');
                if (icon) {
                    icon.classList.toggle('fa-minus');
                    icon.classList.toggle('fa-plus');
                }
            });
        });
    }
    
    // Apply filter functionality
    function applyFilters() {
        const currentUrl = new URL(window.location.href);
        
        // Get selected category
        const selectedCategory = document.querySelector('input[name="product_cat"]:checked');
        if (selectedCategory && selectedCategory.value) {
            currentUrl.searchParams.set('product_cat', selectedCategory.value);
        } else {
            currentUrl.searchParams.delete('product_cat');
        }
        
        // Get selected price filter
        const selectedPrice = document.querySelector('input[name="price"]:checked');
        if (selectedPrice && selectedPrice.value) {
            currentUrl.searchParams.set('price_filter', selectedPrice.value);
        } else {
            currentUrl.searchParams.delete('price_filter');
        }
        
        // Get custom price range
        const minPrice = document.getElementById('min-price');
        const maxPrice = document.getElementById('max-price');
        if (minPrice && minPrice.value) {
            currentUrl.searchParams.set('min_price', minPrice.value);
        } else {
            currentUrl.searchParams.delete('min_price');
        }
        if (maxPrice && maxPrice.value) {
            currentUrl.searchParams.set('max_price', maxPrice.value);
        } else {
            currentUrl.searchParams.delete('max_price');
        }
        
        // Remove paged parameter when filtering
        currentUrl.searchParams.delete('paged');
        
        // Redirect to filtered URL
        window.location.href = currentUrl.toString();
    }
    
    // Category filter functionality
    let filterButtonsInitialized = false;
    
    function initCategoryFilter() {
        // Prevent multiple initializations
        if (filterButtonsInitialized) {
            return;
        }
        
        // Find filter buttons
        const applyFilterBtn = document.querySelector('.y-c-apply-filter-btn');
        const deleteFilterBtn = document.querySelector('.y-c-delete-filter-btn');
        
        // Apply filter button
        if (applyFilterBtn) {
            applyFilterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Apply filter button clicked');
                applyFilters();
            });
        }
        
        // Delete filter button
        if (deleteFilterBtn) {
            deleteFilterBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                console.log('Delete filter button clicked');
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.delete('product_cat');
                currentUrl.searchParams.delete('price_filter');
                currentUrl.searchParams.delete('min_price');
                currentUrl.searchParams.delete('max_price');
                currentUrl.searchParams.delete('paged');
                
                // Reset form inputs
                document.querySelectorAll('input[name="product_cat"]').forEach(input => {
                    input.checked = false;
                });
                document.querySelectorAll('input[name="price"]').forEach(input => {
                    input.checked = false;
                });
                const minPrice = document.getElementById('min-price');
                const maxPrice = document.getElementById('max-price');
                if (minPrice) minPrice.value = '';
                if (maxPrice) maxPrice.value = '';
                
                window.location.href = currentUrl.toString();
            });
        }
        
        filterButtonsInitialized = true;
    }
    
    // Run initialization
    function initAll() {
        initAccordions();
        initCategoryFilter();
    }
    
    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit for shop-archive.js to initialize dropdowns first
            setTimeout(initAll, 500);
        });
    } else {
        // DOM already loaded, wait a bit for shop-archive.js
        setTimeout(initAll, 500);
    }
})();
</script>

<?php
get_footer();
