<?php
defined('ABSPATH') || exit;
?>
<section class="auth-section">
  <div class="auth-form">
    <h2><?php esc_html_e('إنشاء حساب', 'mallati-theme'); ?></h2>
    <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?> id="signup-form">
      <?php do_action('woocommerce_register_form_start'); ?>
      <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
      <input type="hidden" name="username" id="reg_username" value="<?php echo esc_attr(!empty($_POST['email']) ? sanitize_user(wp_unslash($_POST['email'])) : ''); ?>" />
      <?php endif; ?>
      <div class="form-group">
        <label for="reg_billing_first_name"><?php esc_html_e('الاسم', 'mallati-theme'); ?> <span class="required">*</span></label>
        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text y-c-input" name="billing_first_name" id="reg_billing_first_name" autocomplete="name" value="<?php echo (!empty($_POST['billing_first_name'])) ? esc_attr(wp_unslash($_POST['billing_first_name'])) : ''; ?>" placeholder="<?php esc_attr_e('الاسم بالكامل', 'mallati-theme'); ?>" required />
      </div>
      <div class="form-group">
        <label for="reg_email"><?php esc_html_e('البريد الإلكتروني', 'mallati-theme'); ?> <span class="required">*</span></label>
        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text y-c-input" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required placeholder="example@gmail.com" />
      </div>
      <div class="form-group">
        <label for="reg_phone"><?php esc_html_e('رقم الجوال', 'mallati-theme'); ?> <span class="required">*</span></label>
        <input type="tel" class="woocommerce-Input woocommerce-Input--text input-text y-c-input" name="billing_phone" id="reg_phone" autocomplete="tel" value="<?php echo (!empty($_POST['billing_phone'])) ? esc_attr(wp_unslash($_POST['billing_phone'])) : ''; ?>" placeholder="05xxxxxxxx" pattern="^05\d{8}$" dir="rtl" required />
      </div>
      <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
      <div class="form-group">
        <label for="reg_password"><?php esc_html_e('كلمة المرور', 'mallati-theme'); ?> <span class="required">*</span></label>
        <div class="password-input-wrapper">
          <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-input" name="password" id="reg_password" autocomplete="new-password" required />
          <button type="button" class="password-toggle" aria-label="<?php esc_attr_e('إظهار/إخفاء', 'mallati-theme'); ?>"><i class="fa-regular fa-eye"></i></button>
        </div>
      </div>
      <div class="form-group">
        <label for="reg_password_2"><?php esc_html_e('تأكيد كلمة المرور', 'mallati-theme'); ?> <span class="required">*</span></label>
        <div class="password-input-wrapper">
          <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-input" name="password_2" id="reg_password_2" autocomplete="new-password" required />
          <button type="button" class="password-toggle" aria-label="<?php esc_attr_e('إظهار/إخفاء', 'mallati-theme'); ?>"><i class="fa-regular fa-eye"></i></button>
        </div>
      </div>
      <?php else : ?>
      <p class="y-u-text-muted"><?php esc_html_e('سيتم إرسال رابط لإنشاء كلمة المرور إلى بريدك الإلكتروني.', 'mallati-theme'); ?></p>
      <?php endif; ?>
      <?php do_action('woocommerce_register_form'); ?>
      <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
      <button type="submit" class="btn btn-primary woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('إنشاء حساب', 'mallati-theme'); ?>"><?php esc_html_e('إنشاء حساب', 'mallati-theme'); ?></button>
      <?php do_action('woocommerce_register_form_end'); ?>
    </form>
    <div class="switch-auth">
      <p><?php esc_html_e('لديك حساب بالفعل؟', 'mallati-theme'); ?> <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('تسجيل الدخول', 'mallati-theme'); ?></a></p>
    </div>
  </div>
</section>
<?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
<script>document.getElementById('signup-form')?.addEventListener('submit', function() { var u=document.getElementById('reg_username'),e=document.getElementById('reg_email'); if(u&&e) u.value=e.value||''; });</script>
<?php endif; ?>
