<?php
/*
Template Name: Login
*/
get_header();
?>

<main class="y-l-auth-page" data-y="login-page">
    <div class="y-c-auth-card" data-y="login-card">

        <h1 class="y-c-form-title" data-y="form-title">تسجيل دخول</h1>

        <?php wc_print_notices(); ?>

        <form method="post" data-y="login-form" id="login-form" class="woocommerce-form woocommerce-form-login">

            <div class="y-c-form-group" data-y="form-group-email">
                <label for="username" class="y-c-form-label">البريد الإلكتروني <span class="y-c-required">*</span></label>
                <input type="text" id="username" name="username" class="y-c-form-input" required data-y="email-input" autocomplete="username">
            </div>

            <div class="y-c-form-group y-l-password-wrapper" data-y="form-group-password">
                <label for="password" class="y-c-form-label">كلمة المرور <span class="y-c-required">*</span></label>
                <input type="password" id="password" name="password" class="y-c-form-input" required data-y="login-password-input" autocomplete="current-password">
                <i class="fas fa-eye y-c-password-toggle" id="password-toggle" data-y="password-toggle-icon"></i>
            </div>

            <div class="y-l-forgot-password" data-y="forgot-password-link-container">
                <a href="<?php echo esc_url(al_thabihah_get_page_link('forgot-password')); ?>" class="y-c-forgot-password-link" data-y="forgot-password-link">هل نسيت كلمة المرور؟</a>
            </div>

            <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>

            <div class="y-l-form-button" data-y="form-submit-button-container">
                <button type="submit" class="y-c-outline-btn" data-y="form-submit-btn" name="login" value="1">تسجيل الدخول</button>
            </div>

            <div class="y-c-auth-switch" data-y="auth-switch-container">
                <span>ليس لديك حساب؟</span>
                <a href="<?php echo esc_url(al_thabihah_get_page_link('register')); ?>" data-y="auth-switch-link">إنشاء حساب جديد</a>
            </div>
        </form>
    </div>
</main>

<?php
get_footer();
