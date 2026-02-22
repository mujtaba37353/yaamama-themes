<?php
/*
Template Name: Login
*/

get_header();
?>

<section class="y-c-container">
    <?php $login_value = (!empty($_POST['username']) && is_string($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>
    <?php if (function_exists('wc_print_notices')) : ?>
        <div class="woocommerce-notices-wrapper">
            <?php wc_print_notices(); ?>
        </div>
    <?php endif; ?>
    <form class="y-c-login-form-container woocommerce-form woocommerce-form-login" method="post" novalidate>
        <h1 class="y-c-header-title">تسجيل الدخول</h1>
        <br>
        <div class="y-c-form-field">
            <label class="y-c-form-label">البريد الإلكتروني<span class="y-c-required-mark">*</span></label>
            <input type="text" name="username" class="y-c-form-input" required autocomplete="username" value="<?php echo $login_value; ?>">
        </div>
        <div class="y-c-form-field">
            <label class="y-c-form-label">ادخل كلمة مرور<span class="y-c-required-mark">*</span></label>
            <input type="password" name="password" class="y-c-form-input" required autocomplete="current-password">
        </div>

        <div class="y-c-form-options">
            <div class="y-c-remember-me">
                <input type="checkbox" id="remember" name="rememberme" class="y-c-remember-checkbox" value="forever">
                <label for="remember">تذكرني </label>
            </div>
            <div class="y-c-forgot-password">
                <a href="<?php echo esc_url(ahmadi_theme_page_url('forget-password')); ?>">هل نسيت كلمة السر ؟</a>
                <br>
                <a href="<?php echo esc_url(ahmadi_theme_page_url('signup')); ?>">ليس لديك حساب ؟</a>
            </div>
        </div>
        <div class="y-c-login-btn-container">
            <input type="hidden" name="redirect" value="<?php echo esc_url(function_exists('wc_get_account_endpoint_url') ? wc_get_account_endpoint_url('edit-account') : ahmadi_theme_page_url('account')); ?>">
            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
            <button type="submit" class="y-c-login-btn" name="login" value="تسجيل الدخول">تسجيل الدخول</button>
        </div>
    </form>
</section>

<?php
get_footer();
