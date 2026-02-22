<?php
/**
 * Login Form
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Enqueue auth styles
$theme_version = wp_get_theme()->get('Version');
$theme_uri = get_template_directory_uri();
wp_enqueue_style('my-clinic-auth', $theme_uri . '/assets/css/components/auth.css', array(
    'my-clinic-header',
    'my-clinic-footer',
    'my-clinic-buttons'
), $theme_version);

do_action('woocommerce_before_customer_login_form');
?>

<div class="y-l-auth-container" data-y="auth-container">
    <!-- Login Page Only -->
    <div class="y-c-auth-column y-c-auth-column--single" data-y="login-column">
        <h2 class="y-c-auth-title" data-y="login-title"><?php esc_html_e('تسجيل الدخول', 'my-clinic'); ?></h2>

        <form class="woocommerce-form woocommerce-form-login login y-c-auth-form" method="post" novalidate data-y="login-form">
            <?php do_action('woocommerce_login_form_start'); ?>

            <div class="y-c-form-field" data-y="username-field">
                <label for="username" class="y-c-form-label">
                    <?php esc_html_e('اسم المستخدم أو البريد الإلكتروني', 'my-clinic'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username']) && is_string($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required aria-required="true" />
            </div>

            <div class="y-c-form-field" data-y="password-field">
                <label for="password" class="y-c-form-label">
                    <?php esc_html_e('كلمة المرور', 'my-clinic'); ?>
                    <span class="y-c-required-mark">*</span>
                </label>
                <input class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
            </div>

            <?php do_action('woocommerce_login_form'); ?>

            <div class="y-c-form-field y-c-form-field--checkbox" data-y="rememberme-field">
                <label class="y-c-checkbox-label">
                    <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                    <span><?php esc_html_e('تذكرني', 'my-clinic'); ?></span>
                </label>
            </div>

            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>

            <button type="submit" class="woocommerce-button button woocommerce-form-login__submit y-c-btn y-c-btn-primary y-c-btn-full" name="login" value="<?php esc_attr_e('تسجيل الدخول', 'my-clinic'); ?>" data-y="login-submit">
                <?php esc_html_e('تسجيل الدخول', 'my-clinic'); ?>
            </button>

            <p class="y-c-auth-link" data-y="lost-password-link">
                <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('نسيت كلمة المرور؟', 'my-clinic'); ?></a>
            </p>

            <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
                <p class="y-c-auth-link y-c-auth-link--register" data-y="register-link">
                    <?php esc_html_e('ليس لديك حساب؟', 'my-clinic'); ?> 
                    <a href="<?php echo esc_url(add_query_arg('action', 'register', wc_get_page_permalink('myaccount'))); ?>"><?php esc_html_e('إنشاء حساب', 'my-clinic'); ?></a>
                </p>
            <?php endif; ?>

            <?php do_action('woocommerce_login_form_end'); ?>
        </form>
    </div>
</div>

<?php do_action('woocommerce_after_customer_login_form'); ?>
