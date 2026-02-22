<?php
/**
 * My Addresses
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;

$customer_id = get_current_user_id();

if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing'  => __('عنوان الفاتورة', 'nafhat'),
            'shipping' => __('عنوان الشحن', 'nafhat'),
        ),
        $customer_id
    );
} else {
    $get_addresses = apply_filters(
        'woocommerce_my_account_get_addresses',
        array(
            'billing' => __('عنوان الفاتورة', 'nafhat'),
        ),
        $customer_id
    );
}

?>

<h2><?php esc_html_e('عنواني', 'nafhat'); ?></h2>
<p class="myaccount-address-description">
    <?php esc_html_e('العناوين التالية سيتم استخدامها في صفحة الدفع', 'nafhat'); ?>
</p>

<?php if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) : ?>
    <div class="u-columns woocommerce-Addresses col2-set addresses">
<?php endif; ?>

<?php foreach ($get_addresses as $name => $address_title) : ?>
    <?php
        $address = wc_get_account_formatted_address($name);
        $col_class = 'woocommerce-Address';
    ?>

    <div class="<?php echo esc_attr($col_class); ?>">
        <div class="woocommerce-Address-title title">
            <h3><?php echo esc_html($address_title); ?></h3>
            <a href="<?php echo esc_url(wc_get_endpoint_url('edit-address', $name)); ?>" class="edit">
                <i class="fas fa-edit"></i> <?php echo $address ? esc_html__('تعديل', 'nafhat') : esc_html__('إضافة', 'nafhat'); ?>
            </a>
        </div>
        <address>
            <?php
                echo $address ? wp_kses_post($address) : esc_html_e('لم تقم بإعداد هذا النوع من العناوين بعد.', 'nafhat');
            ?>
        </address>
    </div>

<?php endforeach; ?>

<?php if (!wc_ship_to_billing_address_only() && wc_shipping_enabled()) : ?>
    </div>
<?php endif; ?>
