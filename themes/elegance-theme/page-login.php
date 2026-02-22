<?php
/**
 * Template Name: تسجيل الدخول (Login)
 * Elegance - Login page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Login is processed in inc/auth-handlers.php on template_redirect (before any output).

elegance_enqueue_component_css( array( 'auth', 'panner' ) );
wp_enqueue_style( 'elegance-auth-page', ELEGANCE_ELEGANCE_URI . '/templates/contact/contact.css', array( 'elegance-panner' ), ELEGANCE_THEME_VERSION );

$redirect_to = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : elegance_myaccount_url();
$signup_url = elegance_page_url( 'register', '/register/' );
$forget_url = elegance_page_url( 'forgot-password', '/forgot-password/' );
$form_action = get_permalink( get_queried_object_id() ) ?: elegance_page_url( 'login', '/login/' );

if ( function_exists( 'wc_add_notice' ) && isset( $_GET['rp'] ) && sanitize_text_field( wp_unslash( $_GET['rp'] ) ) === 'success' ) {
	wc_add_notice( __( 'تم تحديث كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.', 'elegance' ), 'success' );
}

get_header();

$assets = ELEGANCE_ELEGANCE_URI . '/assets';
?>
<main>
  <section class="panner y-u-m-b-0">
    <h1 class="y-u-text-center">تسجيل الدخول</h1>
  </section>
  <section class="auth-section">
    <div class="container y-u-max-w-1200">
      <div class="right">
        <?php if ( function_exists( 'woocommerce_output_all_notices' ) ) : ?>
          <?php woocommerce_output_all_notices(); ?>
        <?php endif; ?>
        <?php if ( is_user_logged_in() ) : ?>
          <p><a href="<?php echo esc_url( elegance_myaccount_url() ); ?>" class="y-c-btn--primary">حسابي</a></p>
        <?php else : ?>
          <form class="login-form" id="login-form" action="<?php echo esc_url( $form_action ); ?>" method="post">
            <div class="form-group">
              <label for="login-email">البريد الإلكتروني <span class="required">*</span></label>
              <input type="text" id="login-email" name="username" placeholder="" required>
            </div>
            <div class="form-group password-group">
              <label for="login-password">كلمة المرور <span class="required">*</span></label>
              <div class="password-input-wrapper">
                <input type="password" id="login-password" name="password" placeholder="" required>
                <button type="button" class="password-toggle" aria-label="إظهار/إخفاء كلمة المرور">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="form-options">
              <label class="remember-me">
                <input type="checkbox" id="remember" name="rememberme" value="forever">
                <span>تذكرني</span>
              </label>
              <a href="<?php echo esc_url( $forget_url ); ?>" class="forgot-password">هل نسيت كلمة السر ؟</a>
            </div>
            <input type="hidden" name="redirect" value="<?php echo esc_url( $redirect_to ); ?>">
            <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
            <button type="submit" class="login-button" name="login" id="login-btn" value="1">تسجيل الدخول</button>
            <div class="signup-link">
              <span>ليس لديك حساب ؟</span>
              <a href="<?php echo esc_url( $signup_url ); ?>">إنشاء حساب جديد</a>
            </div>
          </form>
        <?php endif; ?>
      </div>
      <div class="left">
        <img src="<?php echo esc_url( $assets . '/login.png' ); ?>" alt="">
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
