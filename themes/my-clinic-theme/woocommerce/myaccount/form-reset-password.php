<?php
/**
 * Lost password reset form.
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

// Enqueue auth styles
$theme_version = wp_get_theme()->get('Version');
$theme_uri = get_template_directory_uri();
wp_enqueue_style('my-clinic-auth', $theme_uri . '/assets/css/components/auth.css', array(
    'my-clinic-header',
    'my-clinic-footer',
    'my-clinic-buttons'
), $theme_version);

do_action('woocommerce_before_reset_password_form');
?>

<div class="y-l-auth-container" data-y="auth-container">
    <div class="y-c-auth-column y-c-auth-column--single" data-y="reset-password-column">
        <h2 class="y-c-auth-title" data-y="reset-password-title"><?php esc_html_e('إنشاء كلمة مرور جديدة', 'my-clinic'); ?></h2>

        <p class="y-c-auth-info" data-y="reset-password-info">
            <?php echo apply_filters('woocommerce_reset_password_message', esc_html__('أدخل كلمة مرور جديدة أدناه.', 'my-clinic')); ?>
        </p>

        <form method="post" class="woocommerce-ResetPassword lost_reset_password y-c-auth-form" data-y="reset-password-form">
            <div class="y-c-form-field" data-y="password-1-field">
                <label for="password_1" class="y-c-form-label">
                    <?php esc_html_e('كلمة المرور الجديدة', 'my-clinic'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="password_1" id="password_1" autocomplete="new-password" required aria-required="true" />
            </div>

            <div class="y-c-form-field" data-y="password-2-field">
                <label for="password_2" class="y-c-form-label">
                    <?php esc_html_e('إعادة إدخال كلمة المرور الجديدة', 'my-clinic'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="password_2" id="password_2" autocomplete="new-password" required aria-required="true" />
            </div>

            <input type="hidden" name="reset_key" value="<?php echo esc_attr($args['key']); ?>" />
            <input type="hidden" name="reset_login" value="<?php echo esc_attr($args['login']); ?>" />

            <?php do_action('woocommerce_resetpassword_form'); ?>

            <?php wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce'); ?>

            <input type="hidden" name="wc_reset_password" value="true" />

            <button type="submit" class="woocommerce-Button button y-c-btn y-c-btn-primary y-c-btn-full" value="<?php esc_attr_e('حفظ', 'my-clinic'); ?>" data-y="save-password-submit">
                <?php esc_html_e('حفظ', 'my-clinic'); ?>
            </button>
        </form>
    </div>
</div>

<?php do_action('woocommerce_after_reset_password_form'); ?>
