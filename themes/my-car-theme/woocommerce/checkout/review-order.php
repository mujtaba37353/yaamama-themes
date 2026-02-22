<?php
/**
 * Review order table - Custom Template
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;
?>

<div class="y-c-order-review">
    
    <!-- Cart Items -->
    <div class="y-c-cart-items">
        <?php
        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                ?>
                <div class="y-c-cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                    <div class="y-c-cart-item-image">
                        <?php
                        $thumbnail = apply_filters('woocommerce_cart_item_thumbnail', $_product->get_image('thumbnail'), $cart_item, $cart_item_key);
                        echo $thumbnail;
                        ?>
                    </div>
                    <div class="y-c-cart-item-details">
                        <div class="y-c-cart-item-name">
                            <?php echo wp_kses_post(apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key)); ?>
                        </div>
                        <div class="y-c-cart-item-quantity">
                            <span class="y-c-qty-label">الكمية:</span>
                            <span class="y-c-qty-value"><?php echo apply_filters('woocommerce_checkout_cart_item_quantity', $cart_item['quantity'], $cart_item, $cart_item_key); ?></span>
                        </div>
                    </div>
                    <div class="y-c-cart-item-price">
                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key); ?>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>

    <!-- Order Totals -->
    <div class="y-c-order-totals">
        
        <!-- Subtotal -->
        <div class="y-c-total-row y-c-subtotal-row">
            <span class="y-c-total-label">المجموع الفرعي</span>
            <span class="y-c-total-value"><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
            <div class="y-c-total-row y-c-coupon-row cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <span class="y-c-total-label"><?php wc_cart_totals_coupon_label($coupon); ?></span>
                <span class="y-c-total-value"><?php wc_cart_totals_coupon_html($coupon); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
            <?php do_action('woocommerce_review_order_before_shipping'); ?>
            <?php wc_cart_totals_shipping_html(); ?>
            <?php do_action('woocommerce_review_order_after_shipping'); ?>
        <?php endif; ?>

        <?php foreach (WC()->cart->get_fees() as $fee) : ?>
            <div class="y-c-total-row y-c-fee-row fee">
                <span class="y-c-total-label"><?php echo esc_html($fee->name); ?></span>
                <span class="y-c-total-value"><?php wc_cart_totals_fee_html($fee); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
            <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                    <div class="y-c-total-row y-c-tax-row tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                        <span class="y-c-total-label"><?php echo esc_html($tax->label); ?></span>
                        <span class="y-c-total-value"><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="y-c-total-row y-c-tax-row tax-total">
                    <span class="y-c-total-label"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></span>
                    <span class="y-c-total-value"><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action('woocommerce_review_order_before_order_total'); ?>

        <!-- Total -->
        <div class="y-c-total-row y-c-grand-total-row order-total">
            <span class="y-c-total-label">الإجمالي</span>
            <span class="y-c-total-value"><?php wc_cart_totals_order_total_html(); ?></span>
        </div>

        <?php do_action('woocommerce_review_order_after_order_total'); ?>
        
    </div>

</div>
