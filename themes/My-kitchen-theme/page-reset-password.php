<?php
$error_message = '';
$success_message = '';
$reset_link = '';

if (isset($_POST['myk_reset_submit'])) {
    check_admin_referer('myk_reset_password');

    $login_input = sanitize_text_field(wp_unslash($_POST['myk_reset_login'] ?? ''));
    if (!$login_input) {
        $error_message = 'يرجى إدخال البريد الإلكتروني.';
    } else {
        $user = get_user_by('email', $login_input);
        if (!$user) {
            $user = get_user_by('login', $login_input);
        }

        if (!$user) {
            $error_message = 'لم يتم العثور على حساب بهذا البريد الإلكتروني.';
        } else {
            $key = get_password_reset_key($user);
            if (is_wp_error($key)) {
                $error_message = 'حدث خطأ أثناء إنشاء رابط الاستعادة.';
            } else {
                $reset_link = home_url('/create-password/?key=' . rawurlencode($key) . '&login=' . rawurlencode($user->user_login));
                $message = "لاستعادة كلمة المرور، استخدم الرابط التالي:\n" . $reset_link;
                wp_mail($user->user_email, 'استعادة كلمة المرور', $message);
                $success_message = 'تم إرسال رابط استعادة كلمة المرور إلى بريدك الإلكتروني.';
            }
        }
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
          <?php if ($reset_link) : ?>
            <p class="y-u-text-center"><a href="<?php echo esc_url($reset_link); ?>">إنشاء كلمة مرور جديدة</a></p>
          <?php endif; ?>
        <?php endif; ?>
        <form method="post">
          <?php wp_nonce_field('myk_reset_password'); ?>
          <h1>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather user">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
              </svg>
            </span>
            استعادة كلمة المرور
          </h1>
          <p>أدخل البريد الإلكتروني المسجل وسيصلك رابط إعادة تعيين كلمة المرور.</p>
          <label for="myk_reset_login">البريد الإلكتروني</label>
          <input id="myk_reset_login" name="myk_reset_login" type="email" autocomplete="email" required />
          <button type="submit" name="myk_reset_submit" class="btn-auth">التالي</button>
        </form>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
