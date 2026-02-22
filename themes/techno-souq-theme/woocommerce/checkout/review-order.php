<?php
/**
 * Review order table
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;
?>
<div class="y-c-order-summary" data-y="order-summary">
    <?php do_action('woocommerce_review_order_before_cart_contents'); ?>
    
    <div class="y-c-summary-item" data-y="subtotal-row">
        <span class="y-c-summary-label" data-y="subtotal-label"><?php esc_html_e('المجموع', 'techno-souq-theme'); ?></span>
        <span class="y-c-summary-value" data-y="subtotal"><?php wc_cart_totals_subtotal_html(); ?></span>
    </div>
    
    <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
        <div class="y-c-summary-item" data-y="discount-row">
            <span class="y-c-summary-label" data-y="discount-label"><?php wc_cart_totals_coupon_label($coupon); ?></span>
            <span class="y-c-summary-value" data-y="discount-value"><?php wc_cart_totals_coupon_html($coupon); ?></span>
        </div>
    <?php endforeach; ?>
    
    <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
        <?php do_action('woocommerce_review_order_before_shipping'); ?>
        <div class="y-c-summary-item" data-y="shipping-row">
            <span class="y-c-summary-label" data-y="shipping-label"><?php esc_html_e('الشحن', 'techno-souq-theme'); ?></span>
            <span class="y-c-summary-value" data-y="shipping-value"><?php wc_cart_totals_shipping_html(); ?></span>
        </div>
        <?php do_action('woocommerce_review_order_after_shipping'); ?>
    <?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')) : ?>
        <div class="y-c-summary-item" data-y="shipping-row">
            <span class="y-c-summary-label" data-y="shipping-label"><?php esc_html_e('الشحن', 'techno-souq-theme'); ?></span>
            <span class="y-c-summary-value" data-y="shipping-value"><?php woocommerce_shipping_calculator(); ?></span>
        </div>
    <?php endif; ?>
    
    <?php foreach (WC()->cart->get_fees() as $fee) : ?>
        <div class="y-c-summary-item" data-y="fee-row">
            <span class="y-c-summary-label" data-y="fee-label"><?php echo esc_html($fee->name); ?></span>
            <span class="y-c-summary-value" data-y="fee-value"><?php wc_cart_totals_fee_html($fee); ?></span>
        </div>
    <?php endforeach; ?>
    
    <?php
    if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) {
        $estimated_text = '';
        if (WC()->customer->is_customer_outside_base() && !WC()->customer->has_calculated_shipping()) {
            $estimated_text = sprintf(' <small>' . esc_html__('(مقدر لـ %s)', 'techno-souq-theme') . '</small>', WC()->countries->estimated_for_prefix($taxable_address[0]) . WC()->countries->countries[$taxable_address[0]]);
        }
        if ('itemized' === get_option('woocommerce_tax_total_display')) {
            foreach (WC()->cart->get_tax_totals() as $code => $tax) {
                ?>
                <div class="y-c-summary-item" data-y="tax-row">
                    <span class="y-c-summary-label" data-y="tax-label"><?php echo esc_html($tax->label) . $estimated_text; ?></span>
                    <span class="y-c-summary-value" data-y="tax-value"><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="y-c-summary-item" data-y="tax-row">
                <span class="y-c-summary-label" data-y="tax-label"><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; ?></span>
                <span class="y-c-summary-value" data-y="tax-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
            </div>
            <?php
        }
    } elseif (wc_tax_enabled() && WC()->cart->display_prices_including_tax()) {
        ?>
        <div class="y-c-summary-item" data-y="tax-row">
            <span class="y-c-summary-label" data-y="tax-label"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></span>
            <span class="y-c-summary-value" data-y="tax-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
        </div>
        <?php
    }
    ?>
    
    <?php do_action('woocommerce_review_order_before_order_total'); ?>
    
    <div class="y-c-summary-item y-c-summary-total" data-y="total-row">
        <span class="y-c-summary-total" data-y="total-label"><?php esc_html_e('الإجمالي المقدر', 'techno-souq-theme'); ?></span>
        <span class="y-c-summary-total" data-y="total"><?php wc_cart_totals_order_total_html(); ?></span>
    </div>
    
    <?php do_action('woocommerce_review_order_after_order_total'); ?>
    <?php do_action('woocommerce_review_order_after_cart_contents'); ?>
</div>