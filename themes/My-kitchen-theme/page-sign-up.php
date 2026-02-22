<?php
if (is_user_logged_in()) {
    wp_safe_redirect(home_url('/my-kitchen/my-account/'));
    exit;
}

$error_message = '';

if (isset($_POST['myk_register_submit'])) {
    check_admin_referer('myk_register');

    $email = sanitize_email(wp_unslash($_POST['myk_register_email'] ?? ''));
    $password = (string) ($_POST['myk_register_password'] ?? '');
    $confirm = (string) ($_POST['myk_register_confirm'] ?? '');

    if (!$email || !is_email($email)) {
        $error_message = 'يرجى إدخال بريد إلكتروني صحيح.';
    } elseif ($password !== $confirm) {
        $error_message = 'كلمتا المرور غير متطابقتين.';
    } elseif (strlen($password) < 6) {
        $error_message = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.';
    } else {
        $user_id = wc_create_new_customer($email, '', $password);
        if (is_wp_error($user_id)) {
            $error_message = $user_id->get_error_message();
        } else {
            if (function_exists('wc_set_customer_auth_cookie')) {
                wc_set_customer_auth_cookie($user_id);
            } else {
                wp_set_auth_cookie($user_id);
            }
            wp_set_current_user($user_id);
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
        <form method="post">
          <?php wp_nonce_field('myk_register'); ?>
          <h1>
            <span>
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="feather feather-user-plus">
                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                <circle cx="8.5" cy="7" r="4"></circle>
                <line x1="20" y1="8" x2="20" y2="14"></line>
                <line x1="23" y1="11" x2="17" y2="11"></line>
              </svg>
            </span>
            إنشاء حساب جديد
          </h1>
          <label for="myk_register_email">البريد الإلكتروني</label>
          <input id="myk_register_email" name="myk_register_email" type="email" autocomplete="email" required />
          <label for="myk_register_password">كلمة المرور</label>
          <input id="myk_register_password" name="myk_register_password" type="password" autocomplete="new-password" required />
          <label for="myk_register_confirm">تأكيد كلمة المرور</label>
          <input id="myk_register_confirm" name="myk_register_confirm" type="password" autocomplete="new-password" required />

          <label class="checkbox">
            <input type="checkbox" required />
            <span class="checkmark"></span>
            <p>أوافق على <a href="#">الشروط والأحكام</a></p>
          </label>
          <button type="submit" name="myk_register_submit" class="btn-auth">إنشاء حساب</button>
          <p class="text y-u-text-center">
            لديك حساب بالفعل؟
            <a href="/my-kitchen/login/" class="y-t-text-decoration-none">تسجيل دخول</a>
          </p>
        </form>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
