<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form');
?>

<main class="auth-main">
<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
<div class="u-columns col2-set" id="customer_login">
  <div class="u-column1 col-1">
<?php endif; ?>
  <section class="auth-section">
    <div class="container">
      <div class="auth-form">
        <h2><?php esc_html_e('تسجيل الدخول', 'mallati-theme'); ?></h2>
        <form id="login-form" class="woocommerce-form woocommerce-form-login login" method="post" novalidate>
          <?php do_action('woocommerce_login_form_start'); ?>
          <div class="form-group">
            <label for="username"><?php esc_html_e('البريد الإلكتروني', 'mallati-theme'); ?> <span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text y-c-input" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username']) && is_string($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required placeholder="example@gmail.com" />
          </div>
          <div class="form-group">
            <label for="password"><?php esc_html_e('كلمة المرور', 'mallati-theme'); ?> <span class="required">*</span></label>
            <div class="password-input-wrapper">
              <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-input" name="password" id="password" autocomplete="current-password" required />
              <button type="button" class="password-toggle" aria-label="<?php esc_attr_e('إظهار/إخفاء كلمة المرور', 'mallati-theme'); ?>"><i class="fa-regular fa-eye"></i></button>
            </div>
          </div>
          <div class="auth-actions">
            <div class="auth-remember">
              <label for="rememberme">
                <input type="checkbox" name="rememberme" id="rememberme" value="forever" />
                <?php esc_html_e('تذكرني', 'mallati-theme'); ?>
              </label>
            </div>
            <a class="auth-forgot" href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('هل نسيت كلمة المرور؟', 'mallati-theme'); ?></a>
          </div>
          <?php do_action('woocommerce_login_form'); ?>
          <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
          <button type="submit" class="btn btn-primary woocommerce-form-login__submit" name="login" value="<?php esc_attr_e('تسجيل الدخول', 'mallati-theme'); ?>"><?php esc_html_e('تسجيل الدخول', 'mallati-theme'); ?></button>
          <?php do_action('woocommerce_login_form_end'); ?>
        </form>
        <div class="switch-auth">
          <p><?php esc_html_e('ليس لديك حساب؟', 'mallati-theme'); ?> <a href="<?php echo esc_url(wc_get_page_permalink('myaccount') . '?action=register'); ?>"><?php esc_html_e('إنشاء حساب', 'mallati-theme'); ?></a></p>
        </div>
      </div>
    </div>
  </section>
</main>

<?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
  </div>
  <div class="u-column2 col-2">
    <?php wc_get_template('myaccount/form-register.php'); ?>
  </div>
</div>
<?php endif; ?>


<?php do_action('woocommerce_after_customer_login_form'); ?>
