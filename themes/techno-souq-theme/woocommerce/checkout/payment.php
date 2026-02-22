<?php
/**
 * Checkout Payment Section
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

if (!wp_doing_ajax()) {
    do_action('woocommerce_review_order_before_payment');
}

$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
?>
<div id="payment" class="woocommerce-checkout-payment">
    <?php if (WC()->cart && WC()->cart->needs_payment()) : ?>
        <div class="y-c-payment-method-options" data-y="payment-method-options">
            <?php
            if (!empty($available_gateways)) {
                $gateway_index = 0;
                foreach ($available_gateways as $gateway) {
                    $gateway_index++;
                    $is_first = $gateway_index === 1;
                    ?>
                    <?php
                    // Translate payment method titles based on gateway ID
                    $gateway_translations = array(
                        'bacs' => 'تحويل بنكي مباشر',
                        'cod' => 'الدفع عند الاستلام',
                        'cheque' => 'دفع بشيك',
                        'paypal' => 'باي بال',
                        'stripe' => 'سترايب',
                    );
                    
                    // Use translation if available, otherwise use original title
                    if (isset($gateway_translations[$gateway->id])) {
                        $gateway_title = $gateway_translations[$gateway->id];
                    } else {
                        $gateway_title = $gateway->get_title();
                        // Fallback translations for common titles
                        $title_translations = array(
                            'Direct bank transfer' => 'تحويل بنكي مباشر',
                            'Cash on delivery' => 'الدفع عند الاستلام',
                            'Check payments' => 'دفع بشيك',
                            'PayPal' => 'باي بال',
                            'Stripe' => 'سترايب',
                        );
                        if (isset($title_translations[$gateway_title])) {
                            $gateway_title = $title_translations[$gateway_title];
                        }
                    }
                    ?>
                    <label class="y-c-radio-option <?php echo $is_first ? 'y-c-active' : ''; ?>" data-y="payment-option-<?php echo esc_attr($gateway->id); ?>">
                        <div class="y-c-payment-method-header" data-y="payment-header-<?php echo esc_attr($gateway->id); ?>">
                            <div data-y="payment-label-container-<?php echo esc_attr($gateway->id); ?>">
                                <input type="radio" name="payment_method" value="<?php echo esc_attr($gateway->id); ?>" <?php checked($gateway->chosen, true); ?> data-order_button_text="<?php echo esc_attr($gateway->order_button_text); ?>" data-y="payment-radio-<?php echo esc_attr($gateway->id); ?>">
                                <span data-y="payment-label-<?php echo esc_attr($gateway->id); ?>"><?php echo esc_html($gateway_title); ?></span>
                            </div>
                            <?php if ($gateway->id === 'bacs' || $gateway->id === 'cod' || $gateway->id === 'cheque') : ?>
                                <!-- No card logos for these methods -->
                            <?php else : ?>
                                <div class="y-c-card-logos" data-y="card-logos-<?php echo esc_attr($gateway->id); ?>">
                                    <i class="fab fa-cc-visa" data-y="visa-logo"></i>
                                    <i class="fab fa-cc-mastercard" data-y="mastercard-logo"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        <?php if ($gateway->has_fields() && $gateway->id !== 'bacs') : ?>
                            <div class="y-c-payment-card-details" data-y="payment-details-<?php echo esc_attr($gateway->id); ?>" style="<?php echo $is_first ? 'display: flex; flex-direction: column;' : 'display: none;'; ?>">
                                <?php $gateway->payment_fields(); ?>
                            </div>
                        <?php endif; ?>
                    </label>
                    <?php
                }
            } else {
                echo '<li>';
                wc_print_notice(apply_filters('woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__('عذراً، يبدو أنه لا توجد طرق دفع متاحة. يرجى الاتصال بنا إذا كنت بحاجة إلى مساعدة.', 'techno-souq-theme') : esc_html__('يرجى ملء بياناتك أعلاه لرؤية طرق الدفع المتاحة.', 'techno-souq-theme')), 'notice');
                echo '</li>';
            }
            ?>
        </div>
    <?php endif; ?>
    
    <?php wc_get_template('checkout/terms.php'); ?>
    
    <?php do_action('woocommerce_review_order_before_submit'); ?>
    
    <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
    
    <div class="y-l-submit-container" data-y="submit-container">
        <?php
        $order_button_text = apply_filters('woocommerce_order_button_text', __('تقديم الطلب', 'techno-souq-theme'));
        echo apply_filters('woocommerce_order_button_html', '<button type="submit" class="y-c-btn y-c-btn-outline y-c-checkout-btn" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr($order_button_text) . '" data-value="' . esc_attr($order_button_text) . '" data-y="place-order-button">' . esc_html($order_button_text) . '</button>');
        ?>
        
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="y-c-btn y-c-btn-outline y-c-back-btn" data-y="back-to-cart-link">
            <?php esc_html_e('العودة إلى سلة المشتريات', 'techno-souq-theme'); ?>
        </a>
    </div>
    
    <?php do_action('woocommerce_review_order_after_submit'); ?>
</div>
<?php
if (!wp_doing_ajax()) {
    do_action('woocommerce_review_order_after_payment');
}