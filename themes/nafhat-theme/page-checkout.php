<?php
/**
 * Template Name: Checkout Page
 * 
 * Custom template for WooCommerce Checkout page
 *
 * @package Nafhat Theme
 */

get_header();

// Make sure WooCommerce is active
if (!class_exists('WooCommerce')) {
    echo '<p>WooCommerce is not active.</p>';
    get_footer();
    return;
}

// Get checkout object
$checkout = WC()->checkout();

// Check if this is the order-received (thank you) page
$order_received = isset($_GET['key']) && isset($wp->query_vars['order-received']);
$order_id = absint($wp->query_vars['order-received']);
$order = $order_id ? wc_get_order($order_id) : false;
?>

<main id="primary" class="site-main woocommerce-checkout-page">
    <?php
    // If order received page, show thank you template
    if ($order_received && $order) {
        wc_get_template('checkout/thankyou.php', array('order' => $order));
    }
    // Check if cart is empty (and not on thank you page)
    elseif (WC()->cart->is_empty()) {
        ?>
        <div class="checkout-page">
            <div class="container">
                <div class="checkout-header">
                    <h1><?php esc_html_e('إتمام الطلب', 'nafhat'); ?></h1>
                </div>
                <div class="checkout-empty">
                    <div class="empty-cart-message">
                        <i class="fas fa-shopping-cart"></i>
                        <h2><?php esc_html_e('السلة فارغة', 'nafhat'); ?></h2>
                        <p><?php esc_html_e('لا يمكنك إتمام الطلب لأن سلة التسوق فارغة.', 'nafhat'); ?></p>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary">
                            <?php esc_html_e('تصفح المنتجات', 'nafhat'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php
    } else {
        // Load checkout form
        wc_get_template('checkout/form-checkout.php', array('checkout' => $checkout));
    }
    ?>
</main>

<?php
get_footer();
