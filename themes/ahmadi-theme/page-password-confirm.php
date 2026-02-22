<?php
/*
Template Name: Password Confirm
*/

$key = isset($_GET['key']) ? sanitize_text_field(wp_unslash($_GET['key'])) : '';
$login = isset($_GET['login']) ? sanitize_text_field(wp_unslash($_GET['login'])) : '';

get_header();
?>

<section class="y-c-container">
    <form class="y-c-login-form-container woocommerce-ResetPassword lost_reset_password" method="post">
        <h1 class="y-c-header-title">تأكيد كلمة المرور</h1>
        <br>
        <?php if (!$key || !$login) : ?>
            <p class="y-c-form-error">رابط إعادة التعيين غير صالح.</p>
        <?php endif; ?>
        <input type="hidden" name="reset_key" value="<?php echo esc_attr($key); ?>">
        <input type="hidden" name="reset_login" value="<?php echo esc_attr($login); ?>">

        <div class="y-c-form-field">
            <label class="y-c-form-label">ادخل كلمة مرور جديدة<span class="y-c-required-mark">*</span></label>
            <input type="password" name="password_1" class="y-c-form-input" required autocomplete="new-password">
        </div>
        <div class="y-c-form-field">
            <label class="y-c-form-label">تأكيد كلمة المرور<span class="y-c-required-mark">*</span></label>
            <input type="password" name="password_2" class="y-c-form-input" required autocomplete="new-password">
        </div>

        <div class="y-c-login-btn-container">
            <input type="hidden" name="wc_reset_password" value="true">
            <?php wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce'); ?>
            <button type="submit" class="y-c-login-btn">تحديث كلمة المرور</button>
        </div>
    </form>
</section>

<?php
get_footer();
