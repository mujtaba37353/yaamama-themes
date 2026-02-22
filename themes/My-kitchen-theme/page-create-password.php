<?php
$error_message = '';
$success_message = '';
$login = sanitize_text_field(wp_unslash($_GET['login'] ?? ''));
$key = sanitize_text_field(wp_unslash($_GET['key'] ?? ''));
$user = null;

if ($login && $key) {
    $user = check_password_reset_key($key, $login);
    if (is_wp_error($user)) {
        $error_message = 'رابط إنشاء كلمة المرور غير صالح أو منتهي.';
        $user = null;
    }
} else {
    $error_message = 'رابط إنشاء كلمة المرور غير مكتمل.';
}

if (isset($_POST['myk_create_password_submit']) && $user) {
    check_admin_referer('myk_create_password');

    $password = (string) ($_POST['myk_new_password'] ?? '');
    $confirm = (string) ($_POST['myk_confirm_password'] ?? '');

    if ($password !== $confirm) {
        $error_message = 'كلمتا المرور غير متطابقتين.';
    } elseif (strlen($password) < 6) {
        $error_message = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.';
    } else {
        reset_password($user, $password);
        wp_safe_redirect(home_url('/my-kitchen/login/?reset=success'));
        exit;
    }
}
?>

<?php get_header(); ?>

<header data-y="design-header"></header>

<main data-y="main">
  <div class="main-container">
    <div class="auth-container">
      <div class="form">
        <?php if ($error_message) : ?>
          <p class="y-u-text-center"><?php echo esc_html($error_message); ?></p>
        <?php endif; ?>
        <?php if ($success_message) : ?>
          <p class="y-u-text-center"><?php echo esc_html($success_message); ?></p>
        <?php endif; ?>
        <?php if ($user) : ?>
          <form method="post">
            <?php wp_nonce_field('myk_create_password'); ?>
            <h1>إنشاء كلمة المرور</h1>
            <label for="myk_new_password">كلمة المرور الجديدة</label>
            <input id="myk_new_password" name="myk_new_password" type="password" required />
            <label for="myk_confirm_password">تأكيد كلمة المرور</label>
            <input id="myk_confirm_password" name="myk_confirm_password" type="password" required />
            <button type="submit" name="myk_create_password_submit" class="btn-auth">حفظ</button>
          </form>
        <?php else : ?>
          <a href="/my-kitchen/reset-password/" class="btn-auth">طلب رابط جديد</a>
        <?php endif; ?>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
