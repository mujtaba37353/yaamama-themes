<?php
/**
 * My Addresses
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

$customer_id = get_current_user_id();

if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing'  => __('عنوان الفاتورة', 'techno-souq-theme'),
            'shipping' => __('عنوان الشحن', 'techno-souq-theme'),
        ),
        $customer_id
    );
} else {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing' => __('عنوان الفاتورة', 'techno-souq-theme'),
        ),
        $customer_id
    );
}
?>

<h3 class="y-c-address-list-title" id="address-list-title" style="<?php echo empty($get_addresses) ? 'display: none;' : ''; ?>">
    <?php esc_html_e('العناوين التالية سيتم استخدامها في صفحة الدفع', 'techno-souq-theme'); ?>
</h3>

<div class="y-l-address-list" id="address-list" style="<?php echo empty($get_addresses) ? 'display: none;' : ''; ?>">
    <?php foreach ($get_addresses as $name => $address_title) : ?>
        <?php
        $address = wc_get_account_formatted_address($name);
        $address_data = WC()->countries->get_formatted_address(array_merge(
            array(
                'first_name' => get_user_meta($customer_id, $name . '_first_name', true),
                'last_name'  => get_user_meta($customer_id, $name . '_last_name', true),
                'company'    => get_user_meta($customer_id, $name . '_company', true),
                'address_1'  => get_user_meta($customer_id, $name . '_address_1', true),
                'address_2'  => get_user_meta($customer_id, $name . '_address_2', true),
                'city'       => get_user_meta($customer_id, $name . '_city', true),
                'state'      => get_user_meta($customer_id, $name . '_state', true),
                'postcode'   => get_user_meta($customer_id, $name . '_postcode', true),
                'country'    => get_user_meta($customer_id, $name . '_country', true),
            ),
            array()
        ));
        ?>
        <?php if ($address) : ?>
            <div class="y-c-address-card">
                <div class="y-c-address-details">
                    <div class="y-c-address-col">
                        <strong><?php echo esc_html($address_title); ?></strong>
                    </div>
                    <div class="y-c-address-col">
                        <?php echo wp_kses_post($address); ?>
                    </div>
                    <div class="y-c-address-col y-c-address-email">
                        <?php echo esc_html(get_user_meta($customer_id, $name . '_email', true) ?: get_user_meta($customer_id, 'billing_email', true) ?: wp_get_current_user()->user_email); ?>
                    </div>
                    <div class="y-c-address-col">
                        <?php echo esc_html(get_user_meta($customer_id, $name . '_phone', true) ?: get_user_meta($customer_id, 'billing_phone', true)); ?>
                    </div>
                </div>
                <div class="y-c-address-actions">
                    <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>" class="y-c-icon-btn">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button class="y-c-icon-btn delete-btn" data-address-type="<?php echo esc_attr($name); ?>">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<div class="y-c-empty-state" id="empty-address-state" data-y="empty-address-container" style="<?php echo !empty($get_addresses) && !empty(array_filter(array_map('wc_get_account_formatted_address', array_keys($get_addresses)))) ? 'display: none;' : ''; ?>">
    <div class="y-c-empty-icon-wrapper">
        <i class="fas fa-map-marker-alt y-c-empty-icon"></i>
    </div>
    <h3 class="y-c-empty-title"><?php esc_html_e('لا يوجد عنوان', 'techno-souq-theme'); ?></h3>
    <button class="y-c-btn y-c-btn-primary y-c-empty-btn" onclick="window.location.href='<?php echo esc_url(wc_get_endpoint_url('edit-address', 'billing')); ?>'">
        <?php esc_html_e('أضف عنوانا جديدا', 'techno-souq-theme'); ?>
    </button>
</div>

<div class="y-c-add-address-action" id="add-address-action" style="<?php echo !empty($get_addresses) && !empty(array_filter(array_map('wc_get_account_formatted_address', array_keys($get_addresses)))) ? '' : 'display: none;'; ?>">
    <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', 'billing')); ?>" class="y-c-btn y-c-btn-primary y-c-btn-full">
        <?php esc_html_e('أدخل عنوان الفاتورة الجديد', 'techno-souq-theme'); ?>
    </a>
</div>