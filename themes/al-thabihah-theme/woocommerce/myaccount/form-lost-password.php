<?php
defined('ABSPATH') || exit;
?>

<main class="y-l-auth-page" data-y="password-reset-page">
    <div class="y-c-auth-card" data-y="password-reset-card">
        <h1 class="y-c-form-title" data-y="form-title">استعادة كلمة المرور</h1>
        <form method="post" class="woocommerce-ResetPassword lost_reset_password" data-y="password-reset-form" id="forgetPasswordForm">
            <div class="y-c-form-group" data-y="form-group-email">
                <label for="user_login" class="y-c-form-label">البريد الإلكتروني <span class="y-c-required">*</span></label>
                <input type="text" id="user_login" name="user_login" class="y-c-form-input" required data-y="email-input" autocomplete="username">
            </div>

            <div class="y-l-form-button" data-y="form-submit-button-container">
                <button type="submit" class="y-c-outline-btn" data-y="form-submit-btn">إرسال رابط الاستعادة</button>
            </div>

            <input type="hidden" name="wc_reset_password" value="true" />
            <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>
        </form>
    </div>
</main>
