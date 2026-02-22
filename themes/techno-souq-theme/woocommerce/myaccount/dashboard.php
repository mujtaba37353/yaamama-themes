<?php
/**
 * My Account Dashboard
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
?>

<div class="y-c-account-details-form-container">
    <form class="y-c-account-details-form" id="account-details-form" method="post" action="<?php echo esc_url(wc_get_endpoint_url('edit-account')); ?>">
        <!-- First row: First name and Last name -->
        <div class="y-c-form-row">
            <div class="y-c-form-group">
                <label for="account_first_name" class="y-c-form-label"><?php esc_html_e('الاسم الأول', 'techno-souq-theme'); ?></label>
                <input type="text" id="account_first_name" name="account_first_name" class="y-c-form-input" value="<?php echo esc_attr($current_user->first_name); ?>" required>
            </div>

            <div class="y-c-form-group">
                <label for="account_last_name" class="y-c-form-label"><?php esc_html_e('الاسم الأخير', 'techno-souq-theme'); ?></label>
                <input type="text" id="account_last_name" name="account_last_name" class="y-c-form-input" value="<?php echo esc_attr($current_user->last_name); ?>" required>
            </div>
        </div>

        <!-- Second row: Birth date and Gender -->
        <div class="y-c-form-row">
            <div class="y-c-form-group">
                <label for="birth-date" class="y-c-form-label"><?php esc_html_e('تاريخ الميلاد', 'techno-souq-theme'); ?></label>
                <input type="date" id="birth-date" name="birth_date" class="y-c-form-input" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'birth_date', true)); ?>">
            </div>

            <div class="y-c-form-group">
                <label for="gender" class="y-c-form-label"><?php esc_html_e('الجنس', 'techno-souq-theme'); ?></label>
                <div class="y-c-dropdown y-c-gender-dropdown" id="gender-dropdown">
                    <?php 
                    $gender = get_user_meta($current_user->ID, 'gender', true);
                    $gender = $gender ? $gender : 'male';
                    ?>
                    <div class="y-c-dropdown-selected" data-value="<?php echo esc_attr($gender); ?>">
                        <?php echo ($gender === 'male') ? esc_html__('ذكر', 'techno-souq-theme') : esc_html__('أنثى', 'techno-souq-theme'); ?>
                    </div>
                    <div class="y-c-dropdown-options">
                        <div class="y-c-dropdown-option" data-value="male"><?php esc_html_e('ذكر', 'techno-souq-theme'); ?></div>
                        <div class="y-c-dropdown-option" data-value="female"><?php esc_html_e('أنثى', 'techno-souq-theme'); ?></div>
                    </div>
                </div>
                <input type="hidden" name="gender" id="gender" value="<?php echo esc_attr($gender); ?>">
            </div>
        </div>

        <!-- Third row: Email and Mobile -->
        <div class="y-c-form-row">
            <div class="y-c-form-group">
                <label for="account_email" class="y-c-form-label"><?php esc_html_e('البريد الإلكتروني', 'techno-souq-theme'); ?></label>
                <input type="email" id="account_email" name="account_email" class="y-c-form-input" value="<?php echo esc_attr($current_user->user_email); ?>" required>
            </div>

            <div class="y-c-form-group">
                <label for="billing_phone" class="y-c-form-label"><?php esc_html_e('رقم الهاتف المحمول', 'techno-souq-theme'); ?></label>
                <input type="tel" id="billing_phone" name="billing_phone" class="y-c-form-input" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'billing_phone', true)); ?>" required>
            </div>
        </div>

        <!-- Submit button -->
        <div class="y-c-form-actions">
            <?php wp_nonce_field('save_account_details', 'save-account-details-nonce'); ?>
            <button type="submit" name="save_account_details" class="y-c-btn y-c-btn-primary y-c-checkout-btn y-c-btn-full"><?php esc_html_e('حفظ التغييرات', 'techno-souq-theme'); ?></button>
        </div>
    </form>
</div>

<?php
/**
 * My Account dashboard.
 *
 * @since 2.6.0
 */
do_action('woocommerce_account_dashboard');
?>