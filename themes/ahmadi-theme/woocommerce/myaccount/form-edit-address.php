<?php
/**
 * Edit address form (shipping only).
 */
defined('ABSPATH') || exit;

$customer_id = get_current_user_id();
$country = WC()->customer->get_shipping_country();
if (!$country) {
    $country = WC()->countries->get_base_country();
}
$country_name = WC()->countries->countries[$country] ?? $country;
if ($country === 'SA') {
    $country_name = 'المملكة العربية السعودية';
}

do_action('woocommerce_before_edit_address_form');
?>

<form method="post" class="y-c-form-container">
    <div class="y-c-form-row">
        <?php
        woocommerce_form_field('shipping_first_name', [
            'label' => 'الاسم الأول',
            'required' => true,
            'class' => ['y-c-form-field'],
            'label_class' => ['y-c-form-label'],
            'input_class' => ['y-c-form-input'],
        ], WC()->customer->get_shipping_first_name());

        woocommerce_form_field('shipping_last_name', [
            'label' => 'اسم العائلة',
            'required' => true,
            'class' => ['y-c-form-field'],
            'label_class' => ['y-c-form-label'],
            'input_class' => ['y-c-form-input'],
        ], WC()->customer->get_shipping_last_name());
        ?>
    </div>

    <div class="y-c-form-field">
        <label class="y-c-form-label">الدولة/المنطقة <span class="y-c-required-mark">*</span></label>
        <input type="text" class="y-c-form-input" value="<?php echo esc_attr($country_name); ?>" readonly>
        <input type="hidden" name="shipping_country" value="<?php echo esc_attr($country); ?>">
    </div>

    <div class="y-c-form-field">
        <?php
        woocommerce_form_field('shipping_address_1', [
            'label' => 'العنوان',
            'required' => true,
            'class' => ['y-c-form-field'],
            'label_class' => ['y-c-form-label'],
            'input_class' => ['y-c-form-input'],
            'placeholder' => 'عنوان الشارع/رقم المنزل',
            'custom_attributes' => [
                'style' => 'margin-bottom:10px;',
            ],
        ], WC()->customer->get_shipping_address_1());

        woocommerce_form_field('shipping_address_2', [
            'label' => '',
            'required' => false,
            'class' => ['y-c-form-field'],
            'input_class' => ['y-c-form-input'],
            'placeholder' => 'رقم الشقة (اختياري)',
        ], WC()->customer->get_shipping_address_2());
        ?>
    </div>

    <div class="y-c-form-field">
        <?php
        woocommerce_form_field('shipping_city', [
            'label' => 'المدينة',
            'required' => true,
            'class' => ['y-c-form-field'],
            'label_class' => ['y-c-form-label'],
            'input_class' => ['y-c-form-input'],
        ], WC()->customer->get_shipping_city());
        ?>
    </div>

    <div class="y-c-form-field">
        <?php
        woocommerce_form_field('shipping_state', [
            'label' => 'المحافظة',
            'required' => true,
            'class' => ['y-c-form-field'],
            'label_class' => ['y-c-form-label'],
            'input_class' => ['y-c-form-input'],
        ], WC()->customer->get_shipping_state());
        ?>
    </div>

    <div class="y-c-form-field">
        <?php
        woocommerce_form_field('shipping_postcode', [
            'label' => 'الرمز البريدي',
            'required' => true,
            'class' => ['y-c-form-field'],
            'label_class' => ['y-c-form-label'],
            'input_class' => ['y-c-form-input'],
        ], WC()->customer->get_shipping_postcode());
        ?>
    </div>

    <div class="y-c-form-field">
        <?php
        woocommerce_form_field('shipping_phone', [
            'label' => 'الهاتف',
            'required' => true,
            'class' => ['y-c-form-field'],
            'label_class' => ['y-c-form-label'],
            'input_class' => ['y-c-form-input'],
        ], get_user_meta($customer_id, 'shipping_phone', true));
        ?>
    </div>

    <div class="y-c-form-field">
        <?php
        woocommerce_form_field('shipping_email', [
            'label' => 'البريد الإلكتروني',
            'required' => true,
            'class' => ['y-c-form-field'],
            'label_class' => ['y-c-form-label'],
            'input_class' => ['y-c-form-input'],
        ], get_user_meta($customer_id, 'shipping_email', true));
        ?>
    </div>

    <div class="y-c-form-actions">
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-address')); ?>" class="y-c-login-btn">
            إلغاء
        </a>
        <button type="submit" class="y-c-login-btn" name="save_address" value="shipping">
            حفظ
        </button>
        <?php wp_nonce_field('woocommerce-edit_address', 'woocommerce-edit-address-nonce'); ?>
        <input type="hidden" name="action" value="edit_address">
    </div>
</form>

<?php do_action('woocommerce_after_edit_address_form'); ?>
