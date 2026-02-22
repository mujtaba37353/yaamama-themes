<?php
/**
 * My Addresses (shipping only).
 */
defined('ABSPATH') || exit;

$customer_id = get_current_user_id();
$address = wc_get_account_formatted_address('shipping');
$edit_url = wc_get_endpoint_url('edit-address', 'shipping', wc_get_page_permalink('myaccount'));
?>

<div class="y-c-form-container">
    <h2 class="y-c-header-title">عنوان الشحن</h2>

    <?php if ($address) : ?>
        <address>
            <?php echo wp_kses_post($address); ?>
        </address>
    <?php else : ?>
        <p>لم تقم بإضافة عنوان الشحن بعد.</p>
    <?php endif; ?>

    <a href="<?php echo esc_url($edit_url); ?>" class="y-c-login-btn">
        <?php echo $address ? 'تعديل عنوان الشحن' : 'إضافة عنوان الشحن'; ?>
    </a>
</div>
