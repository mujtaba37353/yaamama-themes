<?php
/**
 * Checkout Form
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// Remove default WooCommerce wrappers
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

// Enqueue checkout styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-checkout', $techno_souq_path . '/templates/payment/y-payment.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-forms',
    'techno-souq-buttons'
), $theme_version);

// Enqueue checkout scripts
wp_enqueue_script('techno-souq-payment', $techno_souq_path . '/js/payment.js', array('jquery'), $theme_version, true);

do_action('woocommerce_before_checkout_form', $checkout);

// If checkout registration is disabled and not logged in, the user cannot checkout.
if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('يجب عليك تسجيل الدخول لإتمام عملية الشراء.', 'techno-souq-theme')));
    return;
}
?>

<main data-y="payment-main">
    <div class="y-l-payment-container" data-y="payment-container">
        <div class="y-c-payment-main" data-y="payment-main">
            <form name="checkout" method="post" class="checkout woocommerce-checkout y-l-form-container" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" data-y="payment-form">
                
                <?php if ($checkout->get_checkout_fields()) : ?>
                    <?php do_action('woocommerce_checkout_before_customer_details'); ?>
                    
                    <div data-y="shipping-info-section">
                        <h2 class="y-c-payment-section-title" data-y="shipping-info-title"><?php esc_html_e('معلومات التوصيل', 'techno-souq-theme'); ?></h2>
                        <br>
                        
                        <?php do_action('woocommerce_checkout_billing'); ?>
                        
                        <?php if (true === WC()->cart->needs_shipping_address()) : ?>
                            <?php do_action('woocommerce_checkout_shipping'); ?>
                        <?php endif; ?>
                    </div>
                    
                    <?php do_action('woocommerce_checkout_after_customer_details'); ?>
                <?php endif; ?>
                
                <div class="y-c-payment-section" data-y="payment-method-section">
                    <h2 class="y-c-payment-section-title" data-y="payment-method-title"><?php esc_html_e('طريقة الدفع', 'techno-souq-theme'); ?></h2>
                    <?php 
                    // Only show payment methods, not order review summary
                    // Call payment directly without order review
                    do_action('woocommerce_review_order_before_payment');
                    wc_get_template('checkout/payment.php');
                    do_action('woocommerce_review_order_after_payment');
                    ?>
                </div>
            </form>
        </div>
        
        <div class="y-c-order-summary-part" data-y="order-summary-part">
            <?php
            // Display cart items
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                    $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                    
                    // Get product category
                    $categories = wp_get_post_terms($_product->get_id(), 'product_cat');
                    $category_name = !empty($categories) ? $categories[0]->name : '';
                    ?>
                    <div class="y-l-payment-product">
                        <div class="y-c-payment-image">
                            <?php
                            $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key);
                            if (!$product_permalink) {
                                echo $thumbnail; // PHPCS: XSS ok.
                            } else {
                                printf('<a href="%s">%s</a>', esc_url($product_permalink), $thumbnail); // PHPCS: XSS ok.
                            }
                            ?>
                        </div>
                        <div class="y-u-flex y-u-flex-column">
                            <?php if ($category_name) : ?>
                                <h4 class="y-c-payment-section-title"><?php echo esc_html($category_name); ?></h4>
                            <?php endif; ?>
                            <h4 class="y-c-payment-section-description">
                                <?php
                                if (!$product_permalink) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key) . '&nbsp;');
                                } else {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_name', sprintf('<a href="%s">%s</a>', esc_url($product_permalink), $_product->get_name()), $cart_item, $cart_item_key));
                                }
                                
                                echo wc_get_formatted_cart_item_data($cart_item); // PHPCS: XSS ok.
                                ?>
                            </h4>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
            
            <?php wc_get_template('checkout/review-order.php'); ?>
        </div>
    </div>
</main>

<?php
do_action('woocommerce_after_checkout_form', $checkout);
get_footer();