<?php
/*
Template Name: Password Reset
*/
get_header();
?>

<main class="y-l-auth-page" data-y="password-reset-page">
    <div class="y-c-auth-card" data-y="password-reset-card">

        <h1 class="y-c-form-title" data-y="form-title">نسيت كلمة المرور</h1>

        <?php wc_print_notices(); ?>
        <?php if (isset($_GET['reset-link-sent']) && $_GET['reset-link-sent'] === 'true') : ?>
            <p class="y-c-form-description-small y-c-notice-success-inline">تم إرسال الرابط إلى بريدك الإلكتروني. يرجى التحقق من صندوق الوارد.</p>
        <?php else : ?>
        <form method="post" data-y="password-reset-form" id="forgetPasswordForm" class="woocommerce-ResetPassword lost_reset_password">
            <div class="y-c-form-group" data-y="form-group-email">
                <label for="user_login" class="y-c-form-label">البريد الإلكتروني <span class="y-c-required">*</span></label>
                <input type="text" id="user_login" name="user_login" class="y-c-form-input" required data-y="email-input" autocomplete="username">
            </div>
            <p class="y-c-form-description-small" data-y="form-description">أدخل بريدك الإلكتروني ليتسنى لنا إعادة تعيين كلمة المرور. تأكد من إدخال البريد المسجل لدينا.</p>
            <input type="hidden" name="wc_reset_password" value="true" />
            <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>
            <div class="y-l-form-button" data-y="form-submit-button-container">
                <button type="submit" class="y-c-outline-btn" data-y="form-submit-btn" value="1">إرسال رابط الاستعادة</button>
            </div>
            <div class="y-c-auth-switch" data-y="auth-switch-container">
                <span>تذكرت كلمة المرور؟</span>
                <a href="<?php echo esc_url(al_thabihah_get_page_link('login')); ?>" data-y="auth-switch-link">تسجيل دخول</a>
            </div>
        </form>
        <?php endif; ?>
    </div>
</main>

<?php
get_footer();
