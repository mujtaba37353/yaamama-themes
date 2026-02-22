<?php
/**
 * تخصيص حقول الدفع لـ WooCommerce - مطابقة التصميم
 * الحقول المطلوبة: الاسم الكامل، البريد، الجوال، العنوان بالتفصيل
 */
if (!defined('ABSPATH')) exit;

/**
 * إخفاء الحقول غير الموجودة في التصميم
 */
add_filter('woocommerce_checkout_fields', function ($fields) {
    $to_remove = array(
        'billing_company',
        'billing_address_2',
        'billing_state',
        'billing_postcode',
        'billing_country',
        'billing_last_name',
        'billing_city',
    );
    foreach ($to_remove as $key) {
        if (isset($fields['billing'][$key])) {
            unset($fields['billing'][$key]);
        }
    }
    $fields['billing']['billing_first_name']['priority'] = 10;
    $fields['billing']['billing_email']['priority'] = 20;
    $fields['billing']['billing_phone']['priority'] = 30;
    $fields['billing']['billing_address_1']['priority'] = 40;
    // تسمية العنوان
    if (isset($fields['billing']['billing_address_1'])) {
        $fields['billing']['billing_address_1']['placeholder'] = __('العنوان بالتفصيل', 'mallati-theme');
        $fields['billing']['billing_address_1']['label'] = '';
    }
    foreach (array('billing_first_name', 'billing_email', 'billing_phone', 'billing_address_1') as $key) {
        if (isset($fields['billing'][$key])) {
            $fields['billing'][$key]['class'] = array('form-field-full');
            $fields['billing'][$key]['input_class'] = array('form-input');
        }
    }
    if (isset($fields['billing']['billing_first_name'])) {
        $fields['billing']['billing_first_name']['placeholder'] = __('الاسم الكامل', 'mallati-theme');
        $fields['billing']['billing_first_name']['label'] = '';
    }
    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['placeholder'] = __('رقم الجوال', 'mallati-theme');
    }
    if (isset($fields['billing']['billing_email'])) {
        $fields['billing']['billing_email']['placeholder'] = __('البريد الإلكتروني', 'mallati-theme');
    }
    return $fields;
}, 20);

/**
 * تعيين الاسم الأخير من الاسم الأول (لتوافق WooCommerce)
 */
add_filter('woocommerce_checkout_posted_data', function ($data) {
    if (!empty($data['billing_first_name']) && empty($data['billing_last_name'])) {
        $data['billing_last_name'] = $data['billing_first_name'];
    }
    return $data;
});

/**
 * إخفاء خيار "الشحن لعنوان مختلف"
 */
add_filter('woocommerce_cart_needs_shipping_address', '__return_false');
add_filter('woocommerce_ship_to_different_address_checked', '__return_false');

add_filter('woocommerce_my_account_get_addresses', function ($addresses) {
    return array('billing' => __('العنوان', 'mallati-theme'));
}, 20);

add_action('template_redirect', function () {
    if (function_exists('is_wc_endpoint_url') && is_wc_endpoint_url('edit-address')) {
        $addr = get_query_var('edit-address');
        if ('shipping' === $addr) {
            wp_safe_redirect(wc_get_account_endpoint_url('edit-address/billing'));
            exit;
        }
    }
});

/**
 * رابط العودة إلى السلة قبل زر تقديم الطلب
 */
add_action('woocommerce_review_order_before_submit', function () {
    echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="btn-back" style="display:inline-flex;align-items:center;gap:8px;margin-left:16px;color:var(--y-color-secondary);font-weight:700;"><i class="fa-solid fa-chevron-right"></i> ' . esc_html__('العودة إلى سلة المشتريات', 'mallati-theme') . '</a>';
}, 5);

add_filter('woocommerce_thankyou_order_received_text', function ($text, $order) {
    return __('تمت العملية بنجاح', 'mallati-theme');
}, 10, 2);

/**
 * حفظ رقم الجوال عند التسجيل
 */
add_action('woocommerce_created_customer', function ($customer_id) {
    if (!empty($_POST['billing_phone'])) {
        update_user_meta($customer_id, 'billing_phone', sanitize_text_field(wp_unslash($_POST['billing_phone'])));
    }
    if (!empty($_POST['billing_first_name'])) {
        $name = sanitize_text_field(wp_unslash($_POST['billing_first_name']));
        update_user_meta($customer_id, 'billing_first_name', $name);
        update_user_meta($customer_id, 'first_name', $name);
        wp_update_user(array('ID' => $customer_id, 'display_name' => $name));
    }
});

add_action('woocommerce_register_post', function ($username, $email, $validation_errors) {
    if (!empty($_POST['password']) && !empty($_POST['password_2']) && $_POST['password'] !== $_POST['password_2']) {
        $validation_errors->add('password_mismatch', __('كلمة المرور وتأكيد كلمة المرور غير متطابقتين.', 'mallati-theme'));
    }
}, 10, 3);

add_action('woocommerce_save_account_details', function ($user_id) {
    if (!empty($_POST['billing_phone'])) {
        update_user_meta($user_id, 'billing_phone', sanitize_text_field(wp_unslash($_POST['billing_phone'])));
    }
}, 20);
