<?php
/**
 * Empty cart page
 *
 * Override for WooCommerce empty cart template.
 *
 * @package Nafhat
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_cart_is_empty');
?>

<div class="cart-page">
    <div class="header y-u-flex y-u-justify-between y-u-items-center y-u-p-y-56 y-u-p-t-24">
        <h1 class="y-u-color-primary y-u-text-2xl"><?php esc_html_e('سلة التسوق', 'nafhat'); ?></h1>
    </div>

    <div class="cart-empty">
        <div class="cart-empty-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor">
                <path d="M0 24C0 10.7 10.7 0 24 0H69.5c22 0 41.5 12.8 50.6 32h411c26.3 0 45.5 25 38.6 50.4l-41 152.3c-8.5 31.4-37 53.3-69.5 53.3H170.7l5.4 28.5c2.2 11.3 12.1 19.5 23.6 19.5H488c13.3 0 24 10.7 24 24s-10.7 24-24 24H199.7c-34.6 0-64.3-24.6-70.7-58.5L77.4 54.5c-.7-3.8-4-6.5-7.9-6.5H24C10.7 48 0 37.3 0 24zM128 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm336-48a48 48 0 1 1 0 96 48 48 0 1 1 0-96z"/>
            </svg>
        </div>
        <h2><?php esc_html_e('سلة التسوق فارغة', 'nafhat'); ?></h2>
        <p><?php esc_html_e('لم تقم بإضافة أي منتجات إلى السلة بعد', 'nafhat'); ?></p>
        <p class="cart-empty-subtitle"><?php esc_html_e('تصفح منتجاتنا واختر ما يناسبك', 'nafhat'); ?></p>
        
        <div class="cart-empty-actions">
            <?php
            $shop_page_url = wc_get_page_permalink('shop');
            if (!$shop_page_url) {
                $shop_page_url = get_post_type_archive_link('product');
            }
            if (!$shop_page_url) {
                $shop_page_url = home_url('/shop/');
            }
            ?>
            <a href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', $shop_page_url)); ?>" class="cart-btn cart-btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" width="20" height="20">
                    <path d="M160 112c0-35.3 28.7-64 64-64s64 28.7 64 64v48H160V112zm-48 48H48c-26.5 0-48 21.5-48 48V416c0 53 43 96 96 96H352c53 0 96-43 96-96V208c0-26.5-21.5-48-48-48H336V112C336 50.1 285.9 0 224 0S112 50.1 112 112v48zm24 48a24 24 0 1 1 0 48 24 24 0 1 1 0-48zm152 24a24 24 0 1 1 48 0 24 24 0 1 1 -48 0z"/>
                </svg>
                <?php esc_html_e('تصفح منتجاتنا', 'nafhat'); ?>
            </a>
        </div>
    </div>
</div>
