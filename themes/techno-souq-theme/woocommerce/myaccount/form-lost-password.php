<?php
/**
 * Lost password form
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

// Enqueue auth styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-auth', $techno_souq_path . '/templates/auth/y-auth.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-forms',
    'techno-souq-buttons'
), $theme_version);

do_action('woocommerce_before_lost_password_form');
?>

<div class="y-l-auth-container" data-y="auth-container">
    <div class="y-c-auth-column y-c-auth-column--single" data-y="lost-password-column">
        <h2 class="y-c-auth-title" data-y="lost-password-title"><?php esc_html_e('نسيت كلمة المرور', 'techno-souq-theme'); ?></h2>

        <p class="y-c-auth-info" data-y="lost-password-info">
            <?php echo apply_filters('woocommerce_lost_password_message', esc_html__('نسيت كلمة المرور؟ يرجى إدخال اسم المستخدم أو البريد الإلكتروني. ستصلك رسالة بريد إلكتروني تحتوي على رابط لإنشاء كلمة مرور جديدة.', 'techno-souq-theme')); ?>
        </p>

        <form method="post" class="woocommerce-ResetPassword lost_reset_password y-c-auth-form" data-y="lost-password-form">
            <div class="y-c-form-field" data-y="user-login-field">
                <label for="user_login" class="y-c-form-label">
                    <?php esc_html_e('اسم المستخدم أو البريد الإلكتروني', 'techno-souq-theme'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <input class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" type="text" name="user_login" id="user_login" autocomplete="username" required aria-required="true" />
            </div>

            <?php do_action('woocommerce_lostpassword_form'); ?>

            <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>

            <input type="hidden" name="wc_reset_password" value="true" />

            <button type="submit" class="woocommerce-Button button y-c-btn y-c-btn-primary y-c-btn-full" value="<?php esc_attr_e('إعادة تعيين كلمة المرور', 'techno-souq-theme'); ?>" data-y="reset-password-submit">
                <?php esc_html_e('إعادة تعيين كلمة المرور', 'techno-souq-theme'); ?>
            </button>
        </form>
    </div>
</div>

<?php do_action('woocommerce_after_lost_password_form'); ?>
