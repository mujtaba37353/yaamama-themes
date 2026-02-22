<?php
/**
 * Cart totals
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart_totals');
?>

<div class="y-c-cart-summary" data-y="cart-summary-details">
    <div class="y-c-cart-summary__row" data-y="cart-subtotal-row">
        <span class="y-c-cart-summary__label" data-y="cart-subtotal-label"><?php esc_html_e('اجمالي العناصر', 'techno-souq-theme'); ?></span>
        <span class="y-c-cart-summary__value" data-y="cart-subtotal-value"><?php wc_cart_totals_subtotal_html(); ?></span>
    </div>

    <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
        <div class="y-c-cart-summary__row" data-y="cart-discount-row">
            <span class="y-c-cart-summary__label" data-y="cart-discount-label"><?php wc_cart_totals_coupon_label($coupon); ?></span>
            <span class="y-c-cart-summary__value" data-y="cart-discount-value"><?php wc_cart_totals_coupon_html($coupon); ?></span>
        </div>
    <?php endforeach; ?>

    <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
        <?php do_action('woocommerce_cart_totals_before_shipping'); ?>
        <div class="y-c-cart-summary__row" data-y="cart-shipping-row">
            <span class="y-c-cart-summary__label" data-y="cart-shipping-label"><?php esc_html_e('الشحن', 'techno-souq-theme'); ?></span>
            <span class="y-c-cart-summary__value" data-y="cart-shipping-value"><?php wc_cart_totals_shipping_html(); ?></span>
        </div>
        <?php do_action('woocommerce_cart_totals_after_shipping'); ?>
    <?php elseif (WC()->cart->needs_shipping() && 'yes' === get_option('woocommerce_enable_shipping_calc')) : ?>
        <div class="y-c-cart-summary__row" data-y="cart-shipping-row">
            <span class="y-c-cart-summary__label" data-y="cart-shipping-label"><?php esc_html_e('الشحن', 'techno-souq-theme'); ?></span>
            <span class="y-c-cart-summary__value" data-y="cart-shipping-value"><?php woocommerce_shipping_calculator(); ?></span>
        </div>
    <?php endif; ?>

    <?php foreach (WC()->cart->get_fees() as $fee) : ?>
        <div class="y-c-cart-summary__row" data-y="cart-fee-row">
            <span class="y-c-cart-summary__label" data-y="cart-fee-label"><?php echo esc_html($fee->name); ?></span>
            <span class="y-c-cart-summary__value" data-y="cart-fee-value"><?php wc_cart_totals_fee_html($fee); ?></span>
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
                <div class="y-c-cart-summary__row" data-y="cart-tax-row">
                    <span class="y-c-cart-summary__label" data-y="cart-tax-label"><?php echo esc_html($tax->label) . $estimated_text; ?></span>
                    <span class="y-c-cart-summary__value" data-y="cart-tax-value"><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                </div>
                <?php
            }
        } else {
            ?>
            <div class="y-c-cart-summary__row" data-y="cart-tax-row">
                <span class="y-c-cart-summary__label" data-y="cart-tax-label"><?php echo esc_html(WC()->countries->tax_or_vat()) . $estimated_text; ?></span>
                <span class="y-c-cart-summary__value" data-y="cart-tax-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
            </div>
            <?php
        }
    }
    ?>

    <?php do_action('woocommerce_cart_totals_before_order_total'); ?>

    <div class="y-c-cart-summary__row y-c-cart-summary__row--total" data-y="cart-total-row">
        <span class="y-c-cart-summary__label" data-y="cart-total-label"><?php esc_html_e('الإجمالي', 'techno-souq-theme'); ?></span>
        <span class="y-c-cart-summary__value" data-y="cart-total-value"><?php wc_cart_totals_order_total_html(); ?></span>
    </div>

    <?php do_action('woocommerce_cart_totals_after_order_total'); ?>
</div>

<div class="wc-proceed-to-checkout">
    <?php do_action('woocommerce_proceed_to_checkout'); ?>
</div>

<?php do_action('woocommerce_after_cart_totals'); ?>