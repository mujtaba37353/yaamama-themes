<?php
defined('ABSPATH') || exit;
?>

<main class="y-l-auth-page" data-y="password-reset-page">
    <div class="y-c-auth-card" data-y="password-reset-card">
        <h1 class="y-c-form-title" data-y="form-title">تعيين كلمة المرور</h1>
        <form method="post" class="woocommerce-ResetPassword lost_reset_password">
            <div class="y-c-form-group y-l-password-wrapper">
                <label for="password_1" class="y-c-form-label">كلمة المرور الجديدة <span class="y-c-required">*</span></label>
                <input type="password" id="password_1" name="password_1" class="y-c-form-input" required data-y="password-input" autocomplete="new-password">
                <i class="fas fa-eye y-c-password-toggle"></i>
            </div>
            <div class="y-c-form-group y-l-password-wrapper">
                <label for="password_2" class="y-c-form-label">إعادة كلمة المرور <span class="y-c-required">*</span></label>
                <input type="password" id="password_2" name="password_2" class="y-c-form-input" required data-y="confirm-password-input" autocomplete="new-password">
                <i class="fas fa-eye y-c-password-toggle"></i>
            </div>

            <input type="hidden" name="reset_key" value="<?php echo esc_attr($args['key']); ?>" />
            <input type="hidden" name="reset_login" value="<?php echo esc_attr($args['login']); ?>" />
            <input type="hidden" name="wc_reset_password" value="true" />

            <div class="y-l-form-button" data-y="form-submit-button-container">
                <button type="submit" class="y-c-outline-btn" data-y="form-submit-btn">حفظ</button>
            </div>

            <?php wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce'); ?>
        </form>
    </div>
</main>
