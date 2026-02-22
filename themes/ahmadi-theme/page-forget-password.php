<?php
/*
Template Name: Forget Password
*/

get_header();

if (isset($_GET['reset-link-sent']) && function_exists('wc_add_notice')) {
    wc_add_notice('تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.', 'success');
}
?>

<section class="y-c-container y-c-forget-password">
    <?php if (function_exists('wc_print_notices')) : ?>
        <div class="woocommerce-notices-wrapper">
            <?php wc_print_notices(); ?>
        </div>
    <?php endif; ?>
    <form class="y-c-login-form-container woocommerce-ResetPassword lost_reset_password" method="post">
        <h1 class="y-c-header-title">نسيت كلمة المرور</h1>
        <br>
        <div class="y-c-form-field">
            <label class="y-c-form-label">البريد الإلكتروني<span class="y-c-required-mark">*</span></label>
            <input type="text" name="user_login" class="y-c-form-input" required autocomplete="username">
        </div>
        <div class="y-c-login-btn-container">
            <input type="hidden" name="wc_reset_password" value="true">
            <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>
            <button type="submit" class="y-c-login-btn">إرسال رابط إعادة التعيين</button>
        </div>
        <div class="y-c-form-options">
            <a href="<?php echo esc_url(ahmadi_theme_page_url('login')); ?>">العودة إلى تسجيل الدخول</a>
        </div>
    </form>
</section>

<?php
get_footer();
