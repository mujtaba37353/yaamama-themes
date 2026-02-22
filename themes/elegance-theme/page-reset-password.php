<?php
/**
 * Template Name: إعادة تعيين كلمة المرور (Reset Password)
 * Elegance - Reset password page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

elegance_enqueue_component_css( array( 'auth', 'panner', 'empty-state' ) );
wp_enqueue_style( 'elegance-contact', ELEGANCE_ELEGANCE_URI . '/templates/contact/contact.css', array( 'elegance-panner' ), ELEGANCE_THEME_VERSION );

$assets = ELEGANCE_ELEGANCE_URI . '/assets';
$login_url  = elegance_page_url( 'login', '/login/' );
$forgot_url = elegance_page_url( 'forgot-password', '/forgot-password/' );
$form_action = get_permalink( get_queried_object_id() ) ?: elegance_page_url( 'reset-password', '/reset-password/' );

$login_value = isset( $_GET['login'] ) ? sanitize_text_field( wp_unslash( $_GET['login'] ) ) : '';
$key_value   = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
$has_reset_token = $login_value !== '' && $key_value !== '';

if ( function_exists( 'wc_add_notice' ) && isset( $_GET['rp'] ) ) {
	$rp_state = sanitize_text_field( wp_unslash( $_GET['rp'] ) );
	if ( $rp_state === 'weak' ) {
		wc_add_notice( __( 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.', 'elegance' ), 'error' );
	} elseif ( $rp_state === 'mismatch' ) {
		wc_add_notice( __( 'كلمة المرور وتأكيدها غير متطابقين.', 'elegance' ), 'error' );
	} elseif ( $rp_state === 'invalid' ) {
		wc_add_notice( __( 'رابط إعادة التعيين غير صالح أو منتهي الصلاحية.', 'elegance' ), 'error' );
	}
}

get_header();
?>
<main>
  <section class="panner y-u-m-b-0">
    <h1 class="y-u-text-center">إعادة تعيين كلمة المرور</h1>
  </section>
  <?php if ( $has_reset_token ) : ?>
    <section class="auth-section">
      <div class="container y-u-max-w-1200">
        <div class="right">
          <?php if ( function_exists( 'woocommerce_output_all_notices' ) ) : ?>
            <?php woocommerce_output_all_notices(); ?>
          <?php endif; ?>
          <form class="forget-password-form" action="<?php echo esc_url( $form_action ); ?>" method="post">
            <div class="form-group">
              <label for="reset-password-1">كلمة المرور الجديدة <span class="required">*</span></label>
              <div class="password-input-wrapper">
                <input type="password" id="reset-password-1" name="password_1" required>
                <button type="button" class="password-toggle" aria-label="إظهار/إخفاء كلمة المرور">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
            <div class="form-group">
              <label for="reset-password-2">تأكيد كلمة المرور <span class="required">*</span></label>
              <div class="password-input-wrapper">
                <input type="password" id="reset-password-2" name="password_2" required>
                <button type="button" class="password-toggle" aria-label="إظهار/إخفاء كلمة المرور">
                  <i class="fa-regular fa-eye"></i>
                </button>
              </div>
            </div>
            <input type="hidden" name="reset_login" value="<?php echo esc_attr( $login_value ); ?>">
            <input type="hidden" name="reset_key" value="<?php echo esc_attr( $key_value ); ?>">
            <?php wp_nonce_field( 'elegance_reset_password', 'elegance_reset_password_nonce' ); ?>
            <button type="submit" class="reset-button" name="elegance_reset_password" value="1">حفظ كلمة المرور الجديدة</button>
            <div class="login-link">
              <a href="<?php echo esc_url( $login_url ); ?>">العودة إلى تسجيل الدخول</a>
            </div>
          </form>
        </div>
        <div class="left">
          <img src="<?php echo esc_url( $assets . '/login.png' ); ?>" alt="">
        </div>
      </div>
    </section>
  <?php else : ?>
    <section class="container y-u-max-w-1200">
      <?php if ( function_exists( 'woocommerce_output_all_notices' ) ) : ?>
        <?php woocommerce_output_all_notices(); ?>
      <?php endif; ?>
      <div class="empty-state-container">
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fas fa-key"></i>
          </div>
          <h2>رابط إعادة التعيين غير مكتمل. اطلب رابطًا جديدًا للمتابعة.</h2>
          <a href="<?php echo esc_url( $forgot_url ); ?>" class="btn main-button">الذهاب إلى نسيت كلمة المرور</a>
        </div>
      </div>
    </section>
  <?php endif; ?>
</main>
<?php
get_footer();

