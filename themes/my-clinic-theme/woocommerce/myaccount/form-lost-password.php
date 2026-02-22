<?php
/**
 * Lost password form
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

do_action('woocommerce_before_lost_password_form');
?>

<div class="y-l-auth-container" data-y="auth-container">
    <div class="y-c-auth-column y-c-auth-column--single" data-y="lost-password-column">
        <h2 class="y-c-auth-title" data-y="lost-password-title"><?php esc_html_e('نسيت كلمة المرور', 'my-clinic'); ?></h2>

        <p class="y-c-auth-info" data-y="lost-password-info">
            <?php echo apply_filters('woocommerce_lost_password_message', esc_html__('نسيت كلمة المرور؟ يرجى إدخال اسم المستخدم أو البريد الإلكتروني. ستصلك رسالة بريد إلكتروني تحتوي على رابط لإنشاء كلمة مرور جديدة.', 'my-clinic')); ?>
        </p>

        <form method="post" class="woocommerce-ResetPassword lost_reset_password y-c-auth-form" data-y="lost-password-form">
            <div class="y-c-form-field" data-y="user-login-field">
                <label for="user_login" class="y-c-form-label">
                    <?php esc_html_e('اسم المستخدم أو البريد الإلكتروني', 'my-clinic'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <input class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" type="text" name="user_login" id="user_login" autocomplete="username" required aria-required="true" />
            </div>

            <?php do_action('woocommerce_lostpassword_form'); ?>

            <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

            <input type="hidden" name="wc_reset_password" value="true" />

            <button type="submit" class="woocommerce-Button button y-c-btn y-c-btn-primary y-c-btn-full" value="<?php esc_attr_e('إعادة تعيين كلمة المرور', 'my-clinic'); ?>" data-y="reset-password-submit">
                <?php esc_html_e('إعادة تعيين كلمة المرور', 'my-clinic'); ?>
            </button>

            <p class="y-c-auth-link" data-y="back-to-login-link">
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('العودة إلى تسجيل الدخول', 'my-clinic'); ?></a>
            </p>
        </form>
    </div>
</div>

<?php do_action('woocommerce_after_lost_password_form'); ?>
