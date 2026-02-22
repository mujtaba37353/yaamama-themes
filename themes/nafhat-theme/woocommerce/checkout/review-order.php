<?php
/**
 * Review order table
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;
?>

<div class="woocommerce-checkout-review-order-table">
    <div class="order-products">
        <?php
        do_action('woocommerce_review_order_before_cart_contents');

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                $product_image = $_product->get_image('thumbnail');
                $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);
                $product_quantity = apply_filters('woocommerce_checkout_cart_item_quantity', $cart_item['quantity'], $cart_item, $cart_item_key);
                $product_subtotal = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                ?>
                <div class="order-product-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                    <div class="product-image">
                        <?php echo $product_image; ?>
                        <span class="product-quantity"><?php echo $product_quantity; ?></span>
                    </div>
                    <div class="product-details">
                        <span class="product-name"><?php echo wp_kses_post($product_name); ?></span>
                        <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                    </div>
                    <div class="product-total">
                        <?php echo $product_subtotal; ?>
                    </div>
                </div>
                <?php
            }
        }

        do_action('woocommerce_review_order_after_cart_contents');
        ?>
    </div>

    <div class="order-totals">
        <div class="totals-row subtotal">
            <span class="label"><?php esc_html_e('المجموع الفرعي', 'nafhat'); ?></span>
            <span class="value"><?php wc_cart_totals_subtotal_html(); ?></span>
        </div>

        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
            <div class="totals-row coupon coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <span class="label"><?php wc_cart_totals_coupon_label($coupon); ?></span>
                <span class="value"><?php wc_cart_totals_coupon_html($coupon); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
            <?php do_action('woocommerce_review_order_before_shipping'); ?>
            <div class="totals-row shipping">
                <span class="label"><?php esc_html_e('الشحن', 'nafhat'); ?></span>
                <span class="value">
                    <?php 
                    $packages = WC()->shipping()->get_packages();
                    foreach ($packages as $i => $package) {
                        $chosen_method = isset(WC()->session->chosen_shipping_methods[$i]) ? WC()->session->chosen_shipping_methods[$i] : '';
                        $available_methods = $package['rates'];
                        
                        if (!empty($available_methods)) {
                            foreach ($available_methods as $method) {
                                if ($method->id === $chosen_method) {
                                    echo wp_kses_post(wc_price($method->cost));
                                    break;
                                }
                            }
                        }
                    }
                    ?>
                </span>
            </div>
            <?php do_action('woocommerce_review_order_after_shipping'); ?>
        <?php endif; ?>

        <?php foreach (WC()->cart->get_fees() as $fee) : ?>
            <div class="totals-row fee">
                <span class="label"><?php echo esc_html($fee->name); ?></span>
                <span class="value"><?php wc_cart_totals_fee_html($fee); ?></span>
            </div>
        <?php endforeach; ?>

        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
            <?php if ('itemized' === get_option('woocommerce_tax_total_display')) : ?>
                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                    <div class="totals-row tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                        <span class="label"><?php echo esc_html($tax->label); ?></span>
                        <span class="value"><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                    </div>
                <?php endforeach; ?>
            <?php else : ?>
                <div class="totals-row tax-total">
                    <span class="label"><?php echo esc_html(WC()->countries->tax_or_vat()); ?></span>
                    <span class="value"><?php wc_cart_totals_taxes_total_html(); ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action('woocommerce_review_order_before_order_total'); ?>

        <div class="totals-row total">
            <span class="label"><?php esc_html_e('الإجمالي', 'nafhat'); ?></span>
            <span class="value"><?php wc_cart_totals_order_total_html(); ?></span>
        </div>

        <?php do_action('woocommerce_review_order_after_order_total'); ?>
    </div>
</div>
