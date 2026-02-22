<?php
/*
Template Name: Signup
*/
get_header();
?>

<main class="y-l-auth-page" data-y="signup-page">
    <div class="y-c-auth-card" data-y="signup-card">

        <h1 class="y-c-form-title" data-y="form-title">حساب جديد</h1>

        <?php wc_print_notices(); ?>

        <form method="post" data-y="signup-form" id="signup-form" class="woocommerce-form woocommerce-form-register register">

            <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                <div class="y-c-form-group" data-y="form-group-username">
                    <label for="reg_username" class="y-c-form-label">اسم المستخدم <span class="y-c-required">*</span></label>
                    <input type="text" id="reg_username" name="username" class="y-c-form-input" required autocomplete="username">
                </div>
            <?php endif; ?>

            <div class="y-c-form-group" data-y="form-group-email">
                <label for="reg_email" class="y-c-form-label">البريد الإلكتروني <span class="y-c-required">*</span></label>
                <input type="email" id="reg_email" name="email" class="y-c-form-input" required data-y="email-input" autocomplete="email">
            </div>

            <div class="y-c-form-group" data-y="form-group-phone">
                <label for="billing_phone" class="y-c-form-label">رقم الجوال <span class="y-c-required">*</span></label>
                <input type="tel" id="billing_phone" name="billing_phone" class="y-c-form-input" placeholder="05 xxxx xxxx" required data-y="phone-input">
            </div>

            <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                <div class="y-c-form-group" data-y="form-group-password">
                    <label for="reg_password" class="y-c-form-label">كلمة المرور <span class="y-c-required">*</span></label>
                    <input type="password" id="reg_password" name="password" class="y-c-form-input" required data-y="password-input" autocomplete="new-password">
                </div>

                <div class="y-c-form-group" data-y="form-group-confirm-password">
                    <label for="confirm-password" class="y-c-form-label">إعادة كلمة المرور <span class="y-c-required">*</span></label>
                    <input type="password" id="confirm-password" name="confirm_password" class="y-c-form-input" required data-y="confirm-password-input">
                </div>
            <?php endif; ?>

            <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>

            <div class="y-l-form-button" data-y="form-submit-button-container">
                <button type="submit" class="y-c-outline-btn" data-y="form-submit-btn" name="register" value="1">إنشاء حساب جديد</button>
            </div>

            <div class="y-c-auth-switch" data-y="auth-switch-container">
                <span>لديك حساب بالفعل؟</span>
                <a href="<?php echo esc_url(al_thabihah_get_page_link('login')); ?>" data-y="auth-switch-link">تسجيل دخول</a>
            </div>
        </form>
    </div>
</main>

<?php
get_footer();
