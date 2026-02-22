<?php

defined('ABSPATH') || exit;

$checkout = WC()->checkout();

if (!$checkout->is_registration_enabled() && $checkout->is_registration_required() && !is_user_logged_in()) {
    echo esc_html(apply_filters('woocommerce_checkout_must_be_logged_in_message', 'يجب تسجيل الدخول لإتمام الطلب.'));
    return;
}
?>
<section class="y-c-container">
    <h1 class="y-c-header-title">الدفع</h1>

    <form name="checkout" method="post" class="checkout y-c-form-container" action="<?php echo esc_url(wc_get_checkout_url()); ?>" enctype="multipart/form-data">
        <div class="y-c-payment-layout">
            <div class="y-c-payment-main">
                <div class="y-c-payment-section">
                    <h2 class="y-c-payment-section-title">معلومات التوصيل</h2>
                    <?php
                    woocommerce_form_field('billing_email', [
                        'type' => 'email',
                        'label' => 'البريد الالكتروني',
                        'required' => true,
                        'class' => ['y-c-form-field'],
                        'input_class' => ['y-c-form-input'],
                        'placeholder' => '******@gmail.com',
                    ], $checkout->get_value('billing_email'));
                    ?>
                    <div class="y-c-form-field">
                        <label>أدخل عنوان الفاتورة الذي يتطابق مع طريقة الدفع الخاصة بك.
                            <span class="y-c-required-mark">*</span>
                        </label>
                    </div>
                    <div class="y-c-form-row">
                        <?php
                        woocommerce_form_field('billing_first_name', [
                            'type' => 'text',
                            'required' => true,
                            'class' => ['y-c-form-field'],
                            'input_class' => ['y-c-form-input'],
                            'placeholder' => 'الاسم الأول',
                        ], $checkout->get_value('billing_first_name'));
                        woocommerce_form_field('billing_last_name', [
                            'type' => 'text',
                            'required' => true,
                            'class' => ['y-c-form-field'],
                            'input_class' => ['y-c-form-input'],
                            'placeholder' => 'اسم العائلة',
                        ], $checkout->get_value('billing_last_name'));
                        ?>
                    </div>
                    <?php
                    woocommerce_form_field('billing_address_1', [
                        'type' => 'text',
                        'required' => true,
                        'class' => ['y-c-form-field'],
                        'input_class' => ['y-c-form-input'],
                        'placeholder' => 'عنوان الشارع / رقم المنزل',
                    ], $checkout->get_value('billing_address_1'));
                    ?>
                    <div class="y-c-form-field">
                        <label>+اضافة شقة , الجناح , الوحدة , الخ
                            <span class="y-c-required-mark">*</span>
                        </label>
                    </div>
                    <div class="y-c-form-row">
                        <?php
                        woocommerce_form_field('billing_state', [
                            'type' => 'text',
                            'required' => true,
                            'class' => ['y-c-form-field'],
                            'input_class' => ['y-c-form-input'],
                            'placeholder' => 'المحافظة',
                        ], $checkout->get_value('billing_state'));
                        woocommerce_form_field('billing_city', [
                            'type' => 'text',
                            'required' => true,
                            'class' => ['y-c-form-field'],
                            'input_class' => ['y-c-form-input'],
                            'placeholder' => 'المدينة',
                        ], $checkout->get_value('billing_city'));
                        ?>
                    </div>
                    <div class="y-c-form-row">
                        <?php
                        woocommerce_form_field('billing_postcode', [
                            'type' => 'text',
                            'required' => true,
                            'class' => ['y-c-form-field'],
                            'input_class' => ['y-c-form-input'],
                            'placeholder' => 'الرمز البريدي',
                        ], $checkout->get_value('billing_postcode'));
                        woocommerce_form_field('billing_phone', [
                            'type' => 'text',
                            'required' => true,
                            'class' => ['y-c-form-field'],
                            'input_class' => ['y-c-form-input'],
                            'placeholder' => 'رقم الجوال',
                        ], $checkout->get_value('billing_phone'));
                        ?>
                    </div>
                </div>

                <div class="y-c-payment-section">
                    <h2 class="y-c-payment-section-title">طرق الدفع</h2>
                    <div class="y-c-payment-method-options">
                        <?php woocommerce_checkout_payment(); ?>
                    </div>
                </div>

                <div class="y-c-payment-section">
                    <h2 class="y-c-payment-section-title">ملاحظات إضافية</h2>
                    <div class="y-c-comments">
                        <?php
                        woocommerce_form_field('order_comments', [
                            'type' => 'textarea',
                            'required' => false,
                            'class' => ['y-c-form-field'],
                            'input_class' => ['y-c-form-input'],
                            'placeholder' => 'اكتب ملاحظاتك هنا...',
                        ], $checkout->get_value('order_comments'));
                        ?>
                    </div>
                </div>
            </div>

            <div class="y-c-order-summary-part">
                <div class="y-c-order-summary">
                    <?php foreach (WC()->cart->get_cart() as $cart_item) : ?>
                        <?php $product = $cart_item['data']; ?>
                        <div class="y-c-product-summary-item">
                            <?php echo $product->get_image('woocommerce_thumbnail'); ?>
                            <div class="y-c-product-summary-info">
                                <h4><?php echo esc_html($product->get_name()); ?></h4>
                                <p><?php echo wp_kses_post(WC()->cart->get_product_subtotal($product, $cart_item['quantity'])); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="y-c-summary-item">
                        <span>عدد المنتجات</span>
                        <span><?php echo esc_html(WC()->cart->get_cart_contents_count()); ?></span>
                    </div>
                    <div class="y-c-summary-item">
                        <span>المجموع</span>
                        <span><?php echo wp_kses_post(WC()->cart->get_cart_subtotal()); ?></span>
                    </div>
                    <div class="y-c-summary-item">
                        <span>ضريبة القيمة المضافة</span>
                        <span><?php echo wp_kses_post(wc_price(WC()->cart->get_taxes_total())); ?></span>
                    </div>
                    <div class="y-c-summary-item">
                        <span class="y-c-summary-total">الإجمالي المقدر</span>
                        <span class="y-c-summary-total"><?php echo wp_kses_post(WC()->cart->get_total()); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </form>
</section>
