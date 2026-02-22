<?php
/**
 * Empty Cart Template
 *
 * This template displays when the cart is empty.
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Get shop page URL - use product archive link
if (function_exists('get_post_type_archive_link')) {
    $shop_url = get_post_type_archive_link('product');
}

// Fallback: try WooCommerce shop page if archive link doesn't work
if (empty($shop_url) && function_exists('wc_get_page_permalink')) {
    $shop_url = wc_get_page_permalink('shop');
}

// Final fallback
if (empty($shop_url)) {
    $shop_url = home_url('/shop');
}
?>

<section class="y-l-container" data-y="cart-container">
    <h2 class="y-c-cart-page-title" data-y="cart-page-title"><?php esc_html_e('سلة المشتريات', 'techno-souq-theme'); ?></h2>
    
    <p class="y-c-breadcrumb" data-y="cart-breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>" data-y="breadcrumb-home"><?php esc_html_e('الرئيسية', 'techno-souq-theme'); ?></a>
        <span data-y="breadcrumb-separator"> > </span>
        <?php esc_html_e('سلة المشتريات', 'techno-souq-theme'); ?>
    </p>
    
    <!-- Empty Cart State -->
    <div class="y-c-empty-cart" data-y="empty-cart" style="display: flex !important; flex-direction: column !important; align-items: center !important; justify-content: center !important; text-align: center !important; margin: 2rem auto !important; width: 100% !important;">
        <div class="y-c-empty-icon-wrapper" style="display: flex !important; justify-content: center !important; align-items: center !important; width: 100% !important;">
            <i class="fas fa-shopping-basket y-c-empty-icon" data-y="empty-cart-icon" style="font-size: 90px !important; color: var(--y-color-primary) !important; display: block !important; margin: 0 auto !important;"></i>
        </div>
        <h3 class="y-c-empty-title" data-y="empty-cart-title" style="text-align: center !important; width: 100% !important;"><?php esc_html_e('السلة فارغة', 'techno-souq-theme'); ?></h3>
        <a href="<?php echo esc_url($shop_url); ?>" class="y-c-btn y-c-btn-primary y-c-empty-btn" data-y="continue-shopping-btn" style="margin: 0 auto !important; display: inline-block !important;">
            <?php esc_html_e('متابعة التسوق', 'techno-souq-theme'); ?>
        </a>
    </div>

    <!-- Suggested Products Section -->
    <?php
    // Get suggested products (featured products or recent products)
    $suggested_products = wc_get_products(array(
        'limit' => 4,
        'status' => 'publish',
        'featured' => true,
        'orderby' => 'date',
        'order' => 'DESC',
    ));
    
    // If no featured products, get recent products
    if (empty($suggested_products)) {
        $suggested_products = wc_get_products(array(
            'limit' => 4,
            'status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
        ));
    }
    
    if (!empty($suggested_products)) :
    ?>
        <style>
            /* Force 2 columns on mobile for suggested products grid - inline style to override y-cards.css */
            @media (max-width: 768px) {
                .y-l-suggested-products ul.y-l-product-grid[data-y="suggested-products-grid"],
                ul.y-l-product-grid[data-y="suggested-products-grid"] {
                    grid-template-columns: repeat(2, 1fr) !important;
                }
            }
            @media (max-width: 480px) {
                .y-l-suggested-products ul.y-l-product-grid[data-y="suggested-products-grid"],
                ul.y-l-product-grid[data-y="suggested-products-grid"] {
                    grid-template-columns: repeat(2, 1fr) !important;
                }
            }
        </style>
        <div class="y-l-suggested-products" data-y="suggested-products-section">
            <div class="y-l-similar-products-header" data-y="suggested-products-header" style="margin-bottom: 50px !important; display: flex !important; justify-content: space-between !important; align-items: center !important;">
                <h2 class="y-c-section-title" data-y="suggested-products-title"><?php esc_html_e('مقترحات لك:', 'techno-souq-theme'); ?></h2>
                <a href="<?php echo esc_url($shop_url); ?>" class="y-c-categories-btn" data-y="suggested-products-view-all"><?php esc_html_e('أظهر الكل', 'techno-souq-theme'); ?></a>
            </div>
            
            <ul class="y-l-product-grid" data-y="suggested-products-grid" style="margin-bottom: 50px !important;">
                <?php
                foreach ($suggested_products as $product) {
                    if ($product && $product->is_visible()) {
                        $GLOBALS['product'] = $product;
                        wc_get_template_part('content', 'product');
                    }
                }
                wp_reset_postdata();
                ?>
            </ul>
        </div>
    <?php endif; ?>
</section>
