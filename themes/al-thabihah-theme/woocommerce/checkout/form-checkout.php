<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_checkout_form', $checkout);

if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce')));
    return;
}

$available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
$first_gateway = $available_gateways ? array_key_first($available_gateways) : '';
?>

<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__('Checkout', 'woocommerce'); ?>">
    <main class="y-l-payment-page" data-y="payment-main">
        <div class="y-l-payment-container">
            <section class="y-c-payment-forms-col">

                <nav class="y-c-breadcrumbs" aria-label="breadcrumb" data-y="breadcrumbs">
                    <p>
                        <a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a>
                        <span>></span>
                        <a href="<?php echo esc_url(wc_get_cart_url()); ?>">سلة المشتريات</a>
                        <span>></span>
                        <span data-y="bc-current">الدفع</span>
                    </p>
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="y-c-back-link">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                        <span class="y-c-back-link-text">الرجوع إلى السلة</span>
                    </a>
                </nav>

                <div class="y-c-form-section" data-y="delivery-info-section">
                    <h2 class="y-c-section-header">معلومات التوصيل</h2>

                    <div class="y-c-form-group">
                        <input type="text" class="y-c-form-input" placeholder="الاسم الكامل" required data-y="full-name" name="billing_first_name" value="<?php echo esc_attr($checkout->get_value('billing_first_name')); ?>">
                    </div>

                    <div class="y-c-form-group">
                        <input type="email" class="y-c-form-input" placeholder="البريد الإلكتروني" required data-y="email-input" name="billing_email" value="<?php echo esc_attr($checkout->get_value('billing_email')); ?>">
                    </div>

                    <div class="y-c-form-group">
                        <input type="tel" class="y-c-form-input" placeholder="رقم الجوال" required data-y="phone-input" name="billing_phone" value="<?php echo esc_attr($checkout->get_value('billing_phone')); ?>">
                    </div>

                    <div class="y-c-form-group">
                        <input type="text" class="y-c-form-input" placeholder="العنوان بالتفصيل" required data-y="address-input" name="billing_address_1" value="<?php echo esc_attr($checkout->get_value('billing_address_1')); ?>">
                    </div>
                    <input type="hidden" name="billing_country" value="SA">
                    <input type="hidden" name="billing_state" value="<?php echo esc_attr($checkout->get_value('billing_state')); ?>">

                    <?php if (!is_user_logged_in() && $checkout->is_registration_enabled()) : ?>
                        <div class="y-c-create-account-section">
                            <div class="y-c-create-account-header" id="create-account-toggle">
                                <h3 class="y-c-subsection-title">هل تود إنشاء حساب جديد ؟</h3>
                                <i class="fas fa-chevron-down y-c-toggle-icon"></i>
                            </div>
                            <div class="y-c-form-group y-l-password-wrapper" id="create-account-password" style="display: none;">
                                <input type="password" class="y-c-form-input" placeholder="كلمة المرور" data-y="password-input" name="account_password">
                                <i class="fas fa-eye y-c-password-toggle"></i>
                            </div>
                            <input type="checkbox" name="createaccount" id="createaccount" class="y-u-hidden">
                        </div>
                    <?php endif; ?>
                </div>

                <div class="y-c-form-section" data-y="payment-method-section">
                    <h2 class="y-c-section-header">طريقة الدفع</h2>

                    <div class="y-c-payment-methods">
                        <?php foreach ($available_gateways as $gateway_id => $gateway) :
                            $checked = $gateway_id === $first_gateway;
                            ?>
                            <label class="y-c-payment-option<?php echo $checked ? ' selected' : ''; ?>"<?php echo al_thabihah_is_card_gateway($gateway_id) ? ' data-is-card="1"' : ''; ?>>
                                <div class="y-c-radio-wrapper">
                                    <input type="radio" name="payment_method" value="<?php echo esc_attr($gateway_id); ?>" class="y-c-radio-input" <?php checked($checked); ?>>
                                    <span class="y-c-radio-checkmark"></span>
                                </div>
                                <span class="y-c-radio-label">
                                    <?php echo esc_html(al_thabihah_payment_gateway_title($gateway->get_title(), $gateway_id)); ?>
                                </span>
                                <?php if (al_thabihah_is_card_gateway($gateway_id)) : ?>
                                <div class="y-c-card-icons">
                                    <?php
                                    $mada_src = al_thabihah_asset_uri('al-thabihah/assets/mada.png');
                                    if ($mada_src) :
                                    ?><img src="<?php echo esc_url($mada_src); ?>" alt="مدى" style="height: 20px;"><?php endif; ?>
                                    <i class="fab fa-cc-visa" style="color: #1a1f71;"></i>
                                    <i class="fab fa-cc-mastercard" style="color: #eb001b;"></i>
                                </div>
                                <?php endif; ?>
                            </label>
                        <?php endforeach; ?>

                        <div class="y-c-card-details-form" id="card-details-form" data-y="card-details-form">
                            <h3 class="y-c-subsection-title">رقم البطاقة</h3>
                            <div class="y-c-form-group">
                                <input type="text" class="y-c-form-input" placeholder="xxxx xxxx xxxx xxxx" maxlength="19" data-y="card-number">
                            </div>

                            <h3 class="y-c-subsection-title">اسم حامل البطاقة</h3>
                            <div class="y-c-form-group">
                                <input type="text" class="y-c-form-input" placeholder="الاسم على البطاقة" data-y="card-name">
                            </div>

                            <div class="y-c-form-row">
                                <div class="y-c-form-field">
                                    <h3 class="y-c-subsection-title">تاريخ الإنتهاء</h3>
                                    <div class="y-c-date-inputs">
                                        <input type="text" class="y-c-form-input" placeholder="الشهر" maxlength="2" data-y="card-month">
                                        <input type="text" class="y-c-form-input" placeholder="السنة" maxlength="2" data-y="card-year">
                                    </div>
                                </div>
                                <div class="y-c-form-field">
                                    <h3 class="y-c-subsection-title">CVV</h3>
                                    <div class="y-c-cvv-input-wrapper">
                                        <input type="text" class="y-c-form-input" placeholder="xxx" maxlength="3" data-y="card-cvv">
                                        <i class="fas fa-question-circle y-c-cvv-hint"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="submit" class="y-c-outline-btn y-c-btn-full" id="place_order" name="woocommerce_checkout_place_order" value="1">
                    اكمل الطلب
                </button>

                <?php wp_nonce_field('woocommerce-process_checkout', 'woocommerce-process-checkout-nonce'); ?>
            </section>

            <aside class="y-c-order-summary-sidebar" data-y="order-summary-sidebar">
                <div class="y-c-summary-items" id="summary-items-container">
                    <?php foreach (WC()->cart->get_cart() as $cart_item) :
                        $product = $cart_item['data'];
                        if (!$product || !$product->exists()) {
                            continue;
                        }
                        $img_id = $product->get_image_id();
                        $img_src = $img_id ? wp_get_attachment_image_url($img_id, 'thumbnail') : al_thabihah_asset_uri('al-thabihah/assets/product.jpg');
                        ?>
                        <div class="y-c-summary-item">
                            <div class="y-c-summary-image-wrapper">
                                <img src="<?php echo esc_url($img_src); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" class="y-c-summary-img">
                                <span class="y-c-summary-qty-badge"><?php echo esc_html($cart_item['quantity']); ?></span>
                            </div>
                            <div class="y-c-summary-details">
                                <div class="y-c-summary-header">
                                    <span class="y-c-summary-name"><?php echo esc_html($product->get_name()); ?></span>
                                    <div class="y-c-summary-price">
                                        <span><?php echo esc_html(number_format_i18n((float) $product->get_price(), 0)); ?></span>
                                        <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-coin-icon-small">
                                    </div>
                                </div>
                                <div class="y-c-summary-options">
                                    <span class="y-c-summary-option">تقطيع ثلاجة</span>
                                    <span class="y-c-summary-option">أكياس فاكيوم</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="y-c-summary-totals">
                    <div class="y-c-summary-row">
                        <span>المجموع</span>
                        <span class="y-c-value"><span id="summary-subtotal"><?php echo esc_html(number_format_i18n(WC()->cart->get_subtotal(), 0)); ?></span>
                            <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" alt="ر.س" class="y-c-coin-icon-small"></span>
                    </div>
                    <div class="y-c-summary-row">
                        <span>ضريبة القيمة المضافة</span>
                        <span class="y-c-value"><span id="summary-tax"><?php echo esc_html(number_format_i18n(WC()->cart->get_total_tax(), 0)); ?></span>
                            <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" alt="ر.س" class="y-c-coin-icon-small">
                        </span>
                    </div>
                    <div class="y-c-summary-row">
                        <span>رسوم التوصيل</span>
                        <span class="y-c-value">
                            <span id="summary-delivery"><?php echo esc_html(number_format_i18n(WC()->cart->get_shipping_total(), 0)); ?></span>
                            <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" alt="ر.س" class="y-c-coin-icon-small">
                        </span>
                    </div>

                    <div class="y-c-total-row-highlight">
                        <span>
                            إجمالي السعر
                            <small>(شامل ضريبة القيمة المضافة)</small>
                        </span>
                        <span class="y-c-total-value"><span id="summary-total"><?php echo esc_html(number_format_i18n(WC()->cart->get_total('edit'), 0)); ?></span>
                            <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" alt="ر.س" class="y-c-coin-icon-small">
                        </span>
                    </div>
                </div>
            </aside>
        </div>
    </main>
</form>

<?php do_action('woocommerce_after_checkout_form', $checkout); ?>
