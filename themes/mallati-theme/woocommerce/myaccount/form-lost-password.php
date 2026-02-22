<?php
defined('ABSPATH') || exit;

do_action('woocommerce_before_lost_password_form');
?>
<main>
  <section class="container y-u-w-full y-u-flex y-u-justify-center">
    <div class="y-u-max-w-600 y-u-w-full">
      <div class="y-u-surface y-u-rounded-12 y-u-p-24">
        <h1 class="y-u-text-3xl y-u-text-bold y-u-color-muted y-u-m-b-16 y-u-text-center"><?php esc_html_e('استعادة كلمة المرور', 'mallati-theme'); ?></h1>
        <p class="y-u-text-center y-u-text-muted y-u-m-b-16"><?php echo apply_filters('woocommerce_lost_password_message', esc_html__('أدخل بريدك الإلكتروني وسنرسل لك رابطًا لإعادة تعيين كلمة المرور.', 'woocommerce')); ?></p>
        <form method="post" class="woocommerce-ResetPassword lost_reset_password y-u-flex y-u-flex-col y-u-gap-16" id="forget-password-form">
          <div class="y-c-field">
            <label class="y-c-label" for="user_login"><?php esc_html_e('البريد الإلكتروني', 'mallati-theme'); ?> <span class="required">*</span></label>
            <input type="text" class="woocommerce-Input woocommerce-Input--text y-c-input" name="user_login" id="user_login" autocomplete="username" required placeholder="example@mail.com" />
          </div>
          <?php do_action('woocommerce_lostpassword_form'); ?>
          <input type="hidden" name="wc_reset_password" value="true" />
          <?php wp_nonce_field('lost_password', 'woocommerce-lost-password-nonce'); ?>
          <button type="submit" class="y-c-btn y-c-btn--primary" value="<?php esc_attr_e('إرسال الرابط', 'mallati-theme'); ?>"><?php esc_html_e('إرسال الرابط', 'mallati-theme'); ?></button>
          <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="y-c-btn y-c-btn--ghost y-u-text-center"><?php esc_html_e('العودة لتسجيل الدخول', 'mallati-theme'); ?></a>
        </form>
      </div>
    </div>
  </section>
</main>
<?php do_action('woocommerce_after_lost_password_form'); ?>
