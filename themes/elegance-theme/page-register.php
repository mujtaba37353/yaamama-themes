<?php
/**
 * Template Name: إنشاء حساب (Register)
 * Elegance - Register page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
elegance_enqueue_component_css( array( 'auth', 'panner' ) );
wp_enqueue_style( 'elegance-contact', ELEGANCE_ELEGANCE_URI . '/templates/contact/contact.css', array( 'elegance-panner' ), ELEGANCE_THEME_VERSION );

$login_url   = elegance_page_url( 'login', '/login/' );
$signup_url  = get_permalink( get_queried_object_id() ) ?: elegance_page_url( 'register', '/register/' );
$form_action = $signup_url;

get_header();

$assets = ELEGANCE_ELEGANCE_URI . '/assets';
?>
<main>
  <section class="panner y-u-m-b-0">
    <h1 class="y-u-text-center">إنشاء حساب جديد</h1>
  </section>
  <section class="auth-section">
    <div class="container y-u-max-w-1200">
      <div class="right">
        <?php if ( function_exists( 'woocommerce_output_all_notices' ) ) : ?>
          <?php woocommerce_output_all_notices(); ?>
        <?php endif; ?>
        <form class="signup-form" id="signup-form" action="<?php echo esc_url( $form_action ); ?>" method="post">
          <div class="form-group">
            <label for="signup-email">البريد الإلكتروني <span class="required">*</span></label>
            <input type="email" id="signup-email" name="email" placeholder="" required>
          </div>
          <div class="form-group">
            <label for="signup-phone">رقم الجوال <span class="required">*</span></label>
            <input type="tel" id="signup-phone" name="phone" placeholder="05 xxxx xxxx" dir="ltr">
          </div>
          <div class="form-group">
            <label for="signup-password">كلمة المرور <span class="required">*</span></label>
            <div class="password-input-wrapper">
              <input type="password" id="signup-password" name="password" placeholder="" required>
              <button type="button" class="password-toggle" aria-label="إظهار/إخفاء كلمة المرور">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
          </div>
          <div class="form-group">
            <label for="confirm-password">إعادة كلمة المرور <span class="required">*</span></label>
            <div class="password-input-wrapper">
              <input type="password" id="confirm-password" name="confirm-password" placeholder="" required>
              <button type="button" class="password-toggle" aria-label="إظهار/إخفاء كلمة المرور">
                <i class="fa-regular fa-eye"></i>
              </button>
            </div>
          </div>
          <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
          <button type="submit" class="signup-button" name="register" value="1">إنشاء حساب جديد</button>
          <div class="login-link">
            <span>لديك حساب بالفعل؟</span>
            <a href="<?php echo esc_url( $login_url ); ?>">تسجيل دخول</a>
          </div>
        </form>
      </div>
      <div class="left">
        <img src="<?php echo esc_url( $assets . '/signup.png' ); ?>" alt="">
      </div>
    </div>
  </section>
</main>
<?php
get_footer();

