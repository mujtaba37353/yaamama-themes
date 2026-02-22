<?php
defined('ABSPATH') || exit;
?>

<main class="y-l-cart-page" data-y="cart-main">
    <div class="y-u-container">

        <nav class="y-c-breadcrumbs" aria-label="breadcrumb" data-y="breadcrumbs">
            <p>
                <a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a>
                <span>></span>
                <span data-y="bc-current">سلة المشتريات</span>
            </p>
        </nav>

        <?php if (WC()->cart->is_empty()) : ?>
            <div id="empty-cart-message" class="y-c-empty-cart" data-y="empty-cart">
                <i class="fas fa-shopping-cart"></i>
                <h3>سلتك فارغة حالياً</h3>
                <p>تصفح المتجر وأضف بعض المنتجات المميزة!</p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-outline-btn y-c-basic-btn">تصفح المتجر</a>
            </div>
        <?php else : ?>
            <form class="y-l-cart-container" id="cart-content-wrapper" data-y="cart-container" method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>">
                <div class="y-l-cart-items-col" data-y="cart-items-col">
                    <div class="y-c-cart-items-list" id="cart-items-container" data-y="cart-items-list">
                        <?php foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) :
                            $product = $cart_item['data'];
                            if (!$product || !$product->exists()) {
                                continue;
                            }
                            $product_id = $product->get_id();
                            $product_name = $product->get_name();
                            $quantity = $cart_item['quantity'];
                            $product_permalink = $product->is_visible() ? $product->get_permalink($cart_item) : '';
                            $thumbnail = $product->get_image('woocommerce_thumbnail');
                            $line_total = $cart_item['line_total'] + $cart_item['line_tax'];
                            ?>
                            <div class="y-c-cart-item" data-id="<?php echo esc_attr($product_id); ?>">
                                <div class="y-c-item-image">
                                    <a href="<?php echo esc_url($product_permalink); ?>">
                                        <?php echo $thumbnail; ?>
                                    </a>
                                </div>

                                <div class="y-c-item-details">
                                    <a href="<?php echo esc_url($product_permalink); ?>" class="y-c-item-name"><?php echo esc_html($product_name); ?></a>
                                    <div class="y-c-item-price">
                                        <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-coin-icon-small" alt="">
                                        <?php echo esc_html(number_format_i18n((float) $product->get_price(), 0)); ?>
                                    </div>
                                    <div class="y-c-item-tags">
                                        <span class="y-c-item-tag">تقطيع ثلاجة</span>
                                        <span class="y-c-item-tag">أكياس فاكيوم</span>
                                    </div>
                                </div>

                                <div class="y-c-item-actions">
                                    <div class="y-c-quantity-wrapper">
                                        <div class="y-c-quantity-selector" data-y="quantity-selector">
                                            <button type="button" class="y-c-qty-btn y-btn-increase" data-action="increase">+</button>
                                            <input type="number" id="qty-input-<?php echo esc_attr($product_id); ?>" name="cart[<?php echo esc_attr($cart_item_key); ?>][qty]" value="<?php echo esc_attr($quantity); ?>" min="1" readonly>
                                            <button type="button" class="y-c-qty-btn y-btn-decrease" data-action="decrease">-</button>
                                        </div>
                                    </div>

                                    <div class="y-c-item-total">
                                        <span class="y-c-total-label">المجموع : </span>
                                        <span class="y-c-total-value"><?php echo esc_html(number_format_i18n($line_total, 0)); ?></span>
                                        <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-coin-icon-small" alt="">
                                    </div>
                                </div>

                                <a class="y-c-delete-btn" href="<?php echo esc_url(wc_get_cart_remove_url($cart_item_key)); ?>" title="حذف">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="y-l-cart-summary-col" data-y="cart-summary-col">
                    <div class="y-c-cart-summary-sticky">
                        <div class="y-c-order-summary-card" data-y="summary-card">
                            <h3 class="y-c-summary-title" data-y="summary-title">ملخص الطلب</h3>

                            <div class="y-c-summary-row" data-y="summary-subtotal">
                                <span class="y-c-summary-label">المجموع</span>
                                <div class="y-c-summary-value-wrapper">
                                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-currency-icon" alt="SAR">
                                    <span class="y-c-summary-value" id="summary-subtotal"><?php echo esc_html(number_format_i18n(WC()->cart->get_subtotal(), 0)); ?></span>
                                </div>
                            </div>

                            <div class="y-c-summary-row" data-y="summary-tax">
                                <span class="y-c-summary-label">ضريبة القيمة المضافة</span>
                                <div class="y-c-summary-value-wrapper">
                                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-currency-icon" alt="SAR">
                                    <span class="y-c-summary-value" id="summary-tax"><?php echo esc_html(number_format_i18n(WC()->cart->get_total_tax(), 0)); ?></span>
                                </div>
                            </div>

                            <hr class="y-c-summary-divider">

                            <div class="y-c-summary-row y-c-total-row" data-y="summary-total">
                                <span class="y-c-summary-label">الإجمالي</span>
                                <div class="y-c-summary-value-wrapper">
                                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-currency-icon" alt="SAR">
                                    <span class="y-c-summary-value" id="summary-total"><?php echo esc_html(number_format_i18n(WC()->cart->get_total('edit'), 0)); ?></span>
                                </div>
                            </div>
                        </div>

                        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="y-c-outline-btn y-c-btn-full y-c-checkout-btn" data-y="checkout-btn">
                            الذهاب إلى الدفع
                        </a>

                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-outline-btn y-c-btn-full" data-y="continue-shopping-btn">
                            مواصلة التسوق
                        </a>

                        <button type="submit" name="update_cart" value="1" class="y-c-outline-btn y-c-btn-full y-c-update-cart-btn" data-y="update-cart-btn">تحديث السلة</button>
                    </div>
                </div>

                <?php wp_nonce_field('woocommerce-cart', 'woocommerce-cart-nonce'); ?>
            </form>
        <?php endif; ?>
    </div>
</main>
