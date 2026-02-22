<?php
/**
 * Registration Form
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Check if registration is enabled
if ('yes' !== get_option('woocommerce_enable_myaccount_registration')) {
    wp_safe_redirect(wc_get_page_permalink('myaccount'));
    exit;
}

// Enqueue auth styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-auth', $techno_souq_path . '/templates/auth/y-auth.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-forms',
    'techno-souq-buttons'
), $theme_version);

// Enqueue register validation script
wp_enqueue_script('techno-souq-register', $techno_souq_path . '/js/register-validation.js', array('jquery'), $theme_version, true);

do_action('woocommerce_before_customer_register_form');
?>

<div class="y-l-auth-container" data-y="auth-container">
    <div class="y-c-auth-column y-c-auth-column--single" data-y="register-column">
        <h2 class="y-c-auth-title" data-y="register-title"><?php esc_html_e('إنشاء حساب', 'techno-souq-theme'); ?></h2>

        <form method="post" class="woocommerce-form woocommerce-form-register register y-c-auth-form" <?php do_action('woocommerce_register_form_tag'); ?> data-y="register-form">
            <?php do_action('woocommerce_register_form_start'); ?>

            <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                <div class="y-c-form-field" data-y="reg-username-field">
                    <label for="reg_username" class="y-c-form-label">
                        <?php esc_html_e('اسم المستخدم', 'techno-souq-theme'); ?>
                        <span class="y-c-required-mark">*</span>
                    </label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
                </div>
            <?php endif; ?>

            <div class="y-c-form-field" data-y="reg-email-field">
                <label for="reg_email" class="y-c-form-label">
                    <?php esc_html_e('البريد الإلكتروني', 'techno-souq-theme'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required aria-required="true" />
            </div>

            <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                <div class="y-c-form-field" data-y="reg-password-field">
                    <label for="reg_password" class="y-c-form-label">
                        <?php esc_html_e('كلمة المرور', 'techno-souq-theme'); ?>
                        <span class="y-c-required-mark">*</span>
                    </label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
                </div>
                
                <div class="y-c-form-field" data-y="reg-password-confirm-field">
                    <label for="reg_password_confirm" class="y-c-form-label">
                        <?php esc_html_e('تأكيد كلمة المرور', 'techno-souq-theme'); ?>
                        <span class="y-c-required-mark">*</span>
                    </label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="password_confirm" id="reg_password_confirm" autocomplete="new-password" required aria-required="true" />
                    <span class="y-c-password-error" id="password-match-error" style="display: none; color: var(--y-color-error); font-size: var(--y-font-size-sm); margin-top: var(--y-spacing-xs);">
                        <?php esc_html_e('كلمتا المرور غير متطابقتين', 'techno-souq-theme'); ?>
                    </span>
                </div>
            <?php else : ?>
                <p class="y-c-auth-info" data-y="reg-info">
                    <?php esc_html_e('سيتم إرسال رابط لإنشاء كلمة مرور جديدة إلى بريدك الإلكتروني.', 'techno-souq-theme'); ?>
                </p>
            <?php endif; ?>

            <?php do_action('woocommerce_register_form'); ?>

            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>

            <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit y-c-btn y-c-btn-primary y-c-btn-full" name="register" value="<?php esc_attr_e('إنشاء حساب', 'techno-souq-theme'); ?>" data-y="register-submit">
                <?php esc_html_e('إنشاء حساب', 'techno-souq-theme'); ?>
            </button>

            <p class="y-c-auth-link y-c-auth-link--login" data-y="login-link">
                <?php esc_html_e('لديك حساب بالفعل؟', 'techno-souq-theme'); ?> 
                <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('تسجيل الدخول', 'techno-souq-theme'); ?></a>
            </p>

            <?php do_action('woocommerce_register_form_end'); ?>
        </form>
    </div>
</div>

<?php do_action('woocommerce_after_customer_register_form'); ?>
