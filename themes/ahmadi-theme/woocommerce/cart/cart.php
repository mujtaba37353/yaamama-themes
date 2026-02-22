<?php

defined('ABSPATH') || exit;
?>
<section class="y-c-container">
    <h1 class="y-c-header-title">السلة</h1>
    <form class="woocommerce-cart-form" action="<?php echo esc_url(wc_get_cart_url()); ?>" method="post">
        <section class="y-c-Cart-container">
            <div class="y-c-Cart-right-part">
                <div class="y-c-Cart-right-part-header">
                    <p>المنتج</p>
                    <p>الاجمالي</p>
                </div>
                <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) : ?>
                    <?php
                    $product = $cart_item['data'];
                    if (!$product || !$product->exists() || $cart_item['quantity'] <= 0) {
                        continue;
                    }
                    $product_name = $product->get_name();
                    $product_subtotal = WC()->cart->get_product_subtotal($product, $cart_item['quantity']);
                    ?>
                    <div class="y-c-Cart-item">
                        <div class="y-c-Cart-right-part-details">
                            <?php echo $product->get_image('woocommerce_thumbnail'); ?>
                            <div class="y-c-Cart-right-part-info">
                                <div>
                                    <p><?php echo esc_html($product_name); ?></p>
                                    <p><?php echo wp_kses_post(wc_price($product->get_price())); ?></p>
                                    <p>كرتون</p>
                                </div>
                                <div class="y-c-Cart-quantity-btn">
                                    <button class="y-c-qty-btn y-c-qty-minus" type="button" aria-label="تقليل الكمية">
                                        <span aria-hidden="true">-</span>
                                    </button>
                                    <?php
                                    echo woocommerce_quantity_input([
                                        'input_name' => "cart[{$cart_item_key}][qty]",
                                        'input_value' => $cart_item['quantity'],
                                        'min_value' => 0,
                                        'max_value' => $product->get_max_purchase_quantity(),
                                    ], $product, false);
                                    ?>
                                    <button class="y-c-qty-btn y-c-qty-plus" type="button" aria-label="زيادة الكمية">
                                        <span aria-hidden="true">+</span>
                                    </button>
                                </div>
                                <a class="y-c-Cart-remove-item" href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>" aria-label="إزالة المنتج">
                                    <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
                                    <span>إزالة</span>
                                </a>
                            </div>
                        </div>
                        <div class="y-c-Cart-right-part-cost">
                            <p><?php echo wp_kses_post($product_subtotal); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php do_action('woocommerce_cart_contents'); ?>
            </div>
            <div class="y-c-Cart-left-part">
                <p>إجمالي سلة المشتريات<span class="y-c-fas fa-chevron-down"></span></p>
                <p>
                    ضريبة القيمة المضافة
                    <span><?php echo wp_kses_post(wc_price(WC()->cart->get_taxes_total())); ?></span>
                </p>
                <p>
                    الإجمالي المقدر
                    <span><?php echo wp_kses_post(WC()->cart->get_total()); ?></span>
                </p>
                <button class="y-c-checkout-btn" type="submit" name="update_cart" value="1">تحديث السلة</button>
                <a class="y-c-checkout-btn" href="<?php echo esc_url(wc_get_checkout_url()); ?>">
                    المتابعة لاتمام الطلب
                </a>
            </div>
        </section>
        <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
        <?php do_action('woocommerce_cart_actions'); ?>
    </form>
    <div class="y-c-shipping-note">
        <p><i class="fa-solid fa-truck"></i>
            ملاحظة الشحن: داخل المدينة المنورة: التوصيل خلال نفس اليوم.<br>
            خارج المدينة: من يوم إلى ثلاثة أيام عمل.
        </p>
        <p><i class="fa-regular fa-clock"></i>
            أوقات العمل: 9 صباحًا – 5 مساءً. الطلبات بعد انتهاء الدوام تُرسل في اليوم التالي.
        </p>
    </div>
</section>
