<?php
if (is_user_logged_in()) {
    wp_safe_redirect(home_url('/my-kitchen/my-account/'));
    exit;
}

$error_message = '';
$success_message = '';

if (!empty($_GET['registered'])) {
    $success_message = 'تم إنشاء الحساب بنجاح. يمكنك تسجيل الدخول الآن.';
}
if (!empty($_GET['reset']) && 'success' === $_GET['reset']) {
    $success_message = 'تم تحديث كلمة المرور بنجاح.';
}

if (isset($_POST['myk_login_submit'])) {
    check_admin_referer('myk_login');

    $login_input = sanitize_text_field(wp_unslash($_POST['myk_login_email'] ?? ''));
    $password = (string) ($_POST['myk_login_password'] ?? '');

    if (!$login_input || !$password) {
        $error_message = 'يرجى إدخال البريد الإلكتروني وكلمة المرور.';
    } else {
        $user_login = $login_input;
        if (strpos($login_input, '@') !== false) {
            $user_by_email = get_user_by('email', $login_input);
            if ($user_by_email) {
                $user_login = $user_by_email->user_login;
            }
        }

        $user = wp_signon(
            array(
                'user_login' => $user_login,
                'user_password' => $password,
                'remember' => !empty($_POST['rememberme']),
            ),
            false
        );

        if (is_wp_error($user)) {
            $error_message = $user->get_error_message();
            if (!$error_message) {
                $error_message = 'بيانات الدخول غير صحيحة.';
            }
        } else {
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, !empty($_POST['rememberme']));
            wp_safe_redirect(home_url('/my-kitchen/my-account/'));
            exit;
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
          <div class="auth-message auth-message--error"><?php echo wp_kses_post($error_message); ?></div>
        <?php endif; ?>
        <?php if ($success_message) : ?>
          <div class="auth-message auth-message--success"><?php echo esc_html($success_message); ?></div>
        <?php endif; ?>
        <form method="post">
          <?php wp_nonce_field('myk_login'); ?>
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
            تسجيل دخول
          </h1>
          <label for="myk_login_email">البريد الإلكتروني</label>
          <input id="myk_login_email" name="myk_login_email" type="text" autocomplete="username" required />
          <label for="myk_login_password">كلمة المرور</label>
          <input id="myk_login_password" name="myk_login_password" type="password" autocomplete="current-password" required />

          <div class="y-u-d-flex y-u-justify-between y-u-align-items-center y-u-mb-4">
            <label class="checkbox y-u-d-flex y-u-align-items-center">
              <input type="checkbox" name="rememberme" />
              <span class="checkmark"></span>
              <span>تذكرني</span>
            </label>
            <a href="/my-kitchen/reset-password/" class="y-t-text-decoration-none">نسيت كلمة المرور؟</a>
          </div>
          <button type="submit" name="myk_login_submit" class="btn-auth">تسجيل دخول</button>
          <p class="text y-u-text-center">
            ليس لديك حساب؟
            <a href="/my-kitchen/sign-up/" class="y-t-text-decoration-none">إنشاء حساب</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
