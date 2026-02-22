<?php
/**
 * Template Name: صفحة المفضلة
 * 
 * Wishlist Page Template
 *
 * @package Nafhat
 * @since 1.0.0
 */

get_header();

$is_logged_in = is_user_logged_in();
$wishlist_products = array();

if ($is_logged_in) {
    $user_id = get_current_user_id();
    $product_ids = nafhat_get_wishlist($user_id);
    
    if (!empty($product_ids)) {
        foreach ($product_ids as $product_id) {
            $product = wc_get_product($product_id);
            if ($product) {
                $wishlist_products[] = $product;
            }
        }
    }
}
?>

<main class="wishlist-page">
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-w-full wishlist-container">
            
            <h1 class="wishlist-title"><?php esc_html_e('المفضلة', 'nafhat'); ?></h1>
            
            <?php if (!$is_logged_in) : ?>
                <!-- Not Logged In -->
                <div class="wishlist-empty">
                    <div class="wishlist-empty-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h2><?php esc_html_e('سجل دخولك لعرض المفضلة', 'nafhat'); ?></h2>
                    <p><?php esc_html_e('قم بتسجيل الدخول لحفظ منتجاتك المفضلة والوصول إليها في أي وقت', 'nafhat'); ?></p>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="wishlist-btn wishlist-btn-primary">
                        <i class="fas fa-sign-in-alt"></i>
                        <?php esc_html_e('سجل دخول', 'nafhat'); ?>
                    </a>
                </div>
                
            <?php elseif (empty($wishlist_products)) : ?>
                <!-- Empty Wishlist -->
                <?php 
                // Get shop URL - try multiple methods
                $shop_url = '';
                if (function_exists('wc_get_page_id')) {
                    $shop_page_id = wc_get_page_id('shop');
                    if ($shop_page_id && $shop_page_id > 0) {
                        $shop_url = get_permalink($shop_page_id);
                    }
                }
                if (empty($shop_url) || $shop_url === home_url('/')) {
                    // Fallback to post type archive
                    $shop_url = get_post_type_archive_link('product');
                }
                if (empty($shop_url)) {
                    $shop_url = home_url('/shop/');
                }
                ?>
                <div class="wishlist-empty">
                    <div class="wishlist-empty-icon">
                        <i class="fas fa-heart-broken"></i>
                    </div>
                    <h2><?php esc_html_e('قائمة المفضلة فارغة', 'nafhat'); ?></h2>
                    <p><?php esc_html_e('لم تقم بإضافة أي منتجات إلى المفضلة بعد', 'nafhat'); ?></p>
                    <a href="<?php echo esc_url($shop_url); ?>" class="wishlist-btn wishlist-btn-primary">
                        <i class="fas fa-shopping-bag"></i>
                        <?php esc_html_e('تصفح منتجاتنا', 'nafhat'); ?>
                    </a>
                </div>
                
            <?php else : ?>
                <!-- Wishlist Products -->
                <div class="wishlist-count-info">
                    <?php printf(
                        esc_html(_n('لديك %d منتج في المفضلة', 'لديك %d منتجات في المفضلة', count($wishlist_products), 'nafhat')),
                        count($wishlist_products)
                    ); ?>
                </div>
                
                <div class="wishlist-grid">
                    <?php foreach ($wishlist_products as $product) : 
                        $product_id = $product->get_id();
                        $product_link = get_permalink($product_id);
                        $product_image = wp_get_attachment_url($product->get_image_id());
                        if (!$product_image) {
                            $product_image = wc_placeholder_img_src();
                        }
                        $product_title = $product->get_name();
                        $product_price = $product->get_price();
                        $product_description = $product->get_short_description() ?: wp_trim_words($product->get_description(), 10);
                    ?>
                    <div class="wishlist-item" data-product_id="<?php echo esc_attr($product_id); ?>">
                        <button class="wishlist-remove-btn" data-product_id="<?php echo esc_attr($product_id); ?>" title="<?php esc_attr_e('إزالة من المفضلة', 'nafhat'); ?>">
                            <i class="fas fa-times"></i>
                        </button>
                        
                        <a href="<?php echo esc_url($product_link); ?>" class="wishlist-item-link">
                            <div class="wishlist-item-image">
                                <img src="<?php echo esc_url($product_image); ?>" alt="<?php echo esc_attr($product_title); ?>" />
                                <?php if ($product->is_on_sale()) : ?>
                                <span class="wishlist-sale-badge"><?php esc_html_e('خصم', 'nafhat'); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="wishlist-item-info">
                                <h3 class="wishlist-item-title"><?php echo esc_html($product_title); ?></h3>
                                <?php if ($product_description) : ?>
                                <p class="wishlist-item-desc"><?php echo esc_html($product_description); ?></p>
                                <?php endif; ?>
                                <div class="wishlist-item-price">
                                    <?php if ($product->is_on_sale()) : ?>
                                    <span class="wishlist-price-old"><?php echo esc_html($product->get_regular_price()); ?></span>
                                    <?php endif; ?>
                                    <span class="wishlist-price-current"><?php echo esc_html($product_price); ?></span>
                                    <span class="wishlist-price-currency"><?php esc_html_e('ر.س', 'nafhat'); ?></span>
                                </div>
                            </div>
                        </a>
                        
                        <div class="wishlist-item-actions">
                            <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="wishlist-btn wishlist-btn-cart" data-product_id="<?php echo esc_attr($product_id); ?>">
                                <i class="fas fa-shopping-cart"></i>
                                <?php esc_html_e('أضف للسلة', 'nafhat'); ?>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="wishlist-footer">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="wishlist-btn wishlist-btn-secondary">
                        <?php esc_html_e('متابعة التسوق', 'nafhat'); ?>
                    </a>
                </div>
            <?php endif; ?>
            
        </div>
    </section>
</main>

<?php
get_footer();
