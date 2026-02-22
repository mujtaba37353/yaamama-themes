<?php
/*
Template Name: Reset Password
*/
$reset_key = isset($_GET['key']) ? sanitize_text_field(wp_unslash($_GET['key'])) : '';
$reset_login = isset($_GET['login']) ? sanitize_text_field(wp_unslash($_GET['login'])) : '';
$user_id = isset($_GET['id']) ? absint($_GET['id']) : 0;
if ($reset_login && !$user_id && function_exists('wc_get_shortcode_my_account')) {
    $user = get_user_by('login', $reset_login);
    $user_id = $user ? $user->ID : 0;
}
$has_reset_params = !empty($reset_key) && (!empty($reset_login) || $user_id);

if ($has_reset_params && $user_id && function_exists('WC')) {
    $value = sprintf('%d:%s', $user_id, $reset_key);
    if (class_exists('WC_Shortcode_My_Account') && method_exists('WC_Shortcode_My_Account', 'set_reset_password_cookie')) {
        WC_Shortcode_My_Account::set_reset_password_cookie($value);
    }
}

get_header();
?>

<main class="y-l-auth-page" data-y="password-reset-page">
    <?php if ($has_reset_params) : ?>
        <div class="y-c-auth-card" data-y="password-reset-card">
            <h1 class="y-c-form-title" data-y="form-title">إعادة تعيين كلمة المرور</h1>
            <?php wc_print_notices(); ?>
            <form method="post" class="woocommerce-ResetPassword lost_reset_password" data-y="reset-password-form">
                <p class="y-c-form-description-small" data-y="form-description">أدخل كلمة المرور الجديدة أدناه.</p>
                <div class="y-c-form-group y-l-password-wrapper" data-y="form-group-password-1">
                    <label for="password_1" class="y-c-form-label">كلمة المرور الجديدة <span class="y-c-required">*</span></label>
                    <input type="password" id="password_1" name="password_1" class="y-c-form-input" required data-y="password-input" autocomplete="new-password">
                    <i class="fas fa-eye y-c-password-toggle" aria-hidden="true"></i>
                </div>
                <div class="y-c-form-group y-l-password-wrapper" data-y="form-group-password-2">
                    <label for="password_2" class="y-c-form-label">إعادة كلمة المرور <span class="y-c-required">*</span></label>
                    <input type="password" id="password_2" name="password_2" class="y-c-form-input" required data-y="confirm-password-input" autocomplete="new-password">
                    <i class="fas fa-eye y-c-password-toggle" aria-hidden="true"></i>
                </div>
                <input type="hidden" name="reset_key" value="<?php echo esc_attr($reset_key); ?>" />
                <input type="hidden" name="reset_login" value="<?php echo esc_attr($reset_login); ?>" />
                <input type="hidden" name="wc_reset_password" value="true" />
                <?php wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce'); ?>
                <div class="y-l-form-button" data-y="form-submit-button-container">
                    <button type="submit" class="y-c-outline-btn" data-y="form-submit-btn">حفظ كلمة المرور</button>
                </div>
            </form>
        </div>
    <?php else : ?>
        <div class="y-c-auth-card" data-y="password-reset-card">
            <h1 class="y-c-form-title" data-y="form-title">إعادة تعيين كلمة المرور</h1>
            <?php wc_print_notices(); ?>
            <div class="y-c-empty-state y-c-auth-empty-state" data-y="reset-empty-state">
                <i class="fas fa-key" aria-hidden="true"></i>
                <h2 class="y-c-form-title-sub">رابط غير صالح أو منتهي</h2>
                <p>لم يتم توفير رابط إعادة التعيين، أو أن الرابط منتهي الصلاحية. يرجى طلب رابط جديد من صفحة نسيت كلمة المرور.</p>
                <a href="<?php echo esc_url(al_thabihah_get_page_link('forgot-password')); ?>" class="y-c-outline-btn y-c-basic-btn">نسيت كلمة المرور</a>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php
get_footer();
