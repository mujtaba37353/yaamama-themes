<?php
/**
 * Checkout Payment Section - Custom Template
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

if (!wp_doing_ajax()) {
    do_action('woocommerce_review_order_before_payment');
}
?>

<div id="payment" class="woocommerce-checkout-payment y-c-payment-section">
    
    <div class="y-c-payment-header">
        <span class="y-c-payment-icon"><i class="fa-solid fa-credit-card"></i></span>
        <h3 class="y-c-payment-title">طريقة الدفع</h3>
    </div>

    <?php if (WC()->cart->needs_payment()) : ?>
        <ul class="wc_payment_methods payment_methods methods y-c-payment-methods">
            <?php
            if (!empty($available_gateways)) {
                foreach ($available_gateways as $gateway) {
                    wc_get_template('checkout/payment-method.php', array('gateway' => $gateway));
                }
            } else {
                echo '<li class="y-c-no-payment-methods">';
                echo '<div class="y-c-no-payment-icon"><i class="fa-solid fa-exclamation-circle"></i></div>';
                echo '<p>' . esc_html(apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('Sorry, it seems that there are no available payment methods. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce') : esc_html__('Please fill in your details above to see available payment methods.', 'woocommerce'))) . '</p>';
                echo '</li>';
            }
            ?>
        </ul>
    <?php endif; ?>

    <div class="y-c-place-order">
        <noscript>
            <?php
            printf(esc_html__('Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce'), '<em>', '</em>');
            ?>
            <br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e('Update totals', 'woocommerce'); ?>"><?php esc_html_e('Update totals', 'woocommerce'); ?></button>
        </noscript>

        <?php wc_get_template('checkout/terms.php'); ?>

        <?php do_action('woocommerce_review_order_before_submit'); ?>

        <?php echo apply_filters('woocommerce_order_button_html', '<button type="submit" class="button alt y-c-place-order-btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '"><span class="y-c-btn-text">تأكيد الحجز</span><i class="fa-solid fa-arrow-left"></i></button>'); ?>

        <?php do_action('woocommerce_review_order_after_submit'); ?>

        <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
    </div>

    <div class="y-c-secure-checkout">
        <i class="fa-solid fa-lock"></i>
        <span>دفع آمن ومشفر</span>
    </div>

</div>

<?php
if (!wp_doing_ajax()) {
    do_action('woocommerce_review_order_after_payment');
}
?>
