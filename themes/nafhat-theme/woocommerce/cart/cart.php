<?php
/**
 * Cart Page
 *
 * Override for WooCommerce cart template.
 *
 * @package Nafhat
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_cart');
?>

<div class="cart-page">
    <div class="header y-u-flex y-u-justify-between y-u-items-center y-u-p-y-56 y-u-p-t-24">
        <h1 class="y-u-color-primary y-u-text-2xl"><?php esc_html_e('سلة التسوق', 'nafhat'); ?></h1>
    </div>

    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <?php do_action('woocommerce_before_cart_table'); ?>
        
        <div class="cart-page-grid">
            <!-- Cart Items -->
            <div class="cart-items-container">
                <div class="cart-list">
                    <?php
                    foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                        $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
                        $product_id = apply_filters('woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key);
                        $product_name = apply_filters('woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key);

                        if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_cart_item_visible', true, $cart_item, $cart_item_key)) :
                            $product_permalink = apply_filters('woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink($cart_item) : '', $cart_item, $cart_item_key);
                            $thumbnail = $_product->get_image_id() ? wp_get_attachment_url($_product->get_image_id()) : wc_placeholder_img_src();
                            
                            // Get brand if exists
                            $brands = wp_get_post_terms($product_id, 'product_brand');
                            $brand_name = !empty($brands) && !is_wp_error($brands) ? $brands[0]->name : '';
                    ?>
                    <div class="cart-item woocommerce-cart-form__cart-item <?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                        <div class="item-image-wrapper">
                            <?php if ($product_permalink) : ?>
                            <a href="<?php echo esc_url($product_permalink); ?>">
                                <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($product_name); ?>" class="item-image" />
                            </a>
                            <?php else : ?>
                            <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php echo esc_attr($product_name); ?>" class="item-image" />
                            <?php endif; ?>
                        </div>
                        
                        <div class="item-details">
                            <div class="item-header">
                                <?php if ($brand_name) : ?>
                                <h3 class="item-brand"><?php echo esc_html($brand_name); ?></h3>
                                <?php endif; ?>
                                <p class="item-title">
                                    <?php if ($product_permalink) : ?>
                                    <a href="<?php echo esc_url($product_permalink); ?>"><?php echo esc_html($product_name); ?></a>
                                    <?php else : ?>
                                    <?php echo esc_html($product_name); ?>
                                    <?php endif; ?>
                                </p>
                                <?php
                                // Meta data
                                echo wc_get_formatted_cart_item_data($cart_item);
                                
                                // Backorder notification
                                if ($_product->backorders_require_notification() && $_product->is_on_backorder($cart_item['quantity'])) {
                                    echo wp_kses_post(apply_filters('woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__('متوفر للطلب المسبق', 'nafhat') . '</p>', $product_id));
                                }
                                ?>
                            </div>
                            <div class="item-actions">
                                <div class="qty-selector">
                                    <button type="button" class="qty-btn qty-minus" data-cart-key="<?php echo esc_attr($cart_item_key); ?>" aria-label="<?php esc_attr_e('نقصان', 'nafhat'); ?>">-</button>
                                    <?php
                                    if ($_product->is_sold_individually()) {
                                        $min_quantity = 1;
                                        $max_quantity = 1;
                                    } else {
                                        $min_quantity = 0;
                                        $max_quantity = $_product->get_max_purchase_quantity();
                                    }
                                    
                                    $product_quantity = woocommerce_quantity_input(
                                        array(
                                            'input_name'   => "cart[{$cart_item_key}][qty]",
                                            'input_value'  => $cart_item['quantity'],
                                            'max_value'    => $max_quantity > 0 ? $max_quantity : '',
                                            'min_value'    => $min_quantity,
                                            'product_name' => $product_name,
                                            'classes'      => array('qty-input'),
                                        ),
                                        $_product,
                                        false
                                    );
                                    
                                    echo apply_filters('woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item);
                                    ?>
                                    <button type="button" class="qty-btn qty-plus" data-cart-key="<?php echo esc_attr($cart_item_key); ?>" aria-label="<?php esc_attr_e('زيادة', 'nafhat'); ?>">+</button>
                                </div>
                            </div>
                        </div>
                        
                        <div class="item-pricing">
                            <div class="price-group">
                                <span class="price-label"><?php esc_html_e('السعر', 'nafhat'); ?></span>
                                <span class="price-value">
                                    <?php
                                    echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $cart_item['quantity']), $cart_item, $cart_item_key);
                                    ?>
                                </span>
                            </div>
                            <?php
                            echo apply_filters(
                                'woocommerce_cart_item_remove_link',
                                sprintf(
                                    '<a href="%s" class="item-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">%s</a>',
                                    esc_url(wc_get_cart_remove_url($cart_item_key)),
                                    esc_attr__('حذف المنتج', 'nafhat'),
                                    esc_attr($product_id),
                                    esc_attr($_product->get_sku()),
                                    esc_html__('حذف المنتج', 'nafhat')
                                ),
                                $cart_item_key
                            );
                            ?>
                        </div>
                    </div>
                    <?php
                        endif;
                    endforeach;
                    ?>
                </div>
                
                <!-- Update Cart Button (hidden, triggered by JS) -->
                <button type="submit" class="button update-cart-btn" name="update_cart" value="<?php esc_attr_e('تحديث السلة', 'nafhat'); ?>">
                    <?php esc_html_e('تحديث السلة', 'nafhat'); ?>
                </button>
                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
            </div>

            <!-- Cart Summary -->
            <aside class="cart-summary-container">
                <div class="summary-card">
                    <h2 class="summary-title"><?php esc_html_e('ملخص الطلب', 'nafhat'); ?></h2>
                    <div class="summary-items">
                        <div class="summary-row">
                            <span class="summary-value"><?php wc_cart_totals_subtotal_html(); ?></span>
                            <span class="summary-label"><?php esc_html_e('إجمالي العناصر', 'nafhat'); ?></span>
                        </div>
                        <div class="divider"></div>
                        
                        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()) : ?>
                        <div class="summary-row">
                            <span class="summary-value">
                                <?php 
                                $shipping_total = WC()->cart->get_shipping_total();
                                if ($shipping_total > 0) {
                                    echo wc_price($shipping_total);
                                } else {
                                    esc_html_e('مجاني', 'nafhat');
                                }
                                ?>
                            </span>
                            <span class="summary-label"><?php esc_html_e('الشحن', 'nafhat'); ?></span>
                        </div>
                        <div class="divider"></div>
                        <?php endif; ?>
                        
                        <?php foreach (WC()->cart->get_coupons() as $code => $coupon) : ?>
                        <div class="summary-row coupon-row">
                            <span class="summary-value coupon-value">-<?php wc_cart_totals_coupon_html($coupon); ?></span>
                            <span class="summary-label"><?php wc_cart_totals_coupon_label($coupon); ?></span>
                        </div>
                        <div class="divider"></div>
                        <?php endforeach; ?>
                        
                        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()) : ?>
                        <?php foreach (WC()->cart->get_tax_totals() as $code => $tax) : ?>
                        <div class="summary-row">
                            <span class="summary-value"><?php echo wp_kses_post($tax->formatted_amount); ?></span>
                            <span class="summary-label"><?php echo esc_html($tax->label); ?></span>
                        </div>
                        <div class="divider"></div>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        
                        <div class="summary-row total">
                            <span class="total-value"><?php wc_cart_totals_order_total_html(); ?></span>
                            <span class="total-label-group">
                                <span class="total-label"><?php esc_html_e('الإجمالي', 'nafhat'); ?></span>
                                <?php if (wc_tax_enabled() && WC()->cart->display_prices_including_tax()) : ?>
                                <span class="vat-text"><?php esc_html_e('(شامل ضريبة القيمة المضافة)', 'nafhat'); ?></span>
                                <?php endif; ?>
                            </span>
                        </div>
                    </div>

                    <!-- Coupon -->
                    <?php if (wc_coupons_enabled()) : ?>
                    <div class="coupon-section">
                        <div class="coupon-input-wrapper">
                            <input type="text" name="coupon_code" class="coupon-input" id="coupon_code" placeholder="<?php esc_attr_e('كود الخصم', 'nafhat'); ?>" />
                            <button type="submit" class="coupon-btn" name="apply_coupon" value="<?php esc_attr_e('تطبيق', 'nafhat'); ?>">
                                <?php esc_html_e('تطبيق', 'nafhat'); ?>
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>

                    <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="checkout-btn">
                        <?php esc_html_e('انتقل إلى الدفع', 'nafhat'); ?>
                    </a>
                </div>
            </aside>
        </div>
        
        <?php do_action('woocommerce_after_cart_table'); ?>
    </form>
</div>

<?php do_action('woocommerce_after_cart'); ?>
