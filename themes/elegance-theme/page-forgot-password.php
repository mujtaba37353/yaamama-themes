<?php
/**
 * Template Name: نسيت كلمة المرور (Forgot Password)
 * Elegance - Forgot password page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
elegance_enqueue_component_css( array( 'auth', 'panner' ) );
wp_enqueue_style( 'elegance-contact', ELEGANCE_ELEGANCE_URI . '/templates/contact/contact.css', array( 'elegance-panner' ), ELEGANCE_THEME_VERSION );

$form_action = get_permalink( get_queried_object_id() ) ?: elegance_page_url( 'forgot-password', '/forgot-password/' );

if ( function_exists( 'wc_add_notice' ) && isset( $_GET['fp'] ) ) {
	$fp_state = sanitize_text_field( wp_unslash( $_GET['fp'] ) );
	if ( $fp_state === 'sent' ) {
		wc_add_notice( __( 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.', 'elegance' ), 'success' );
	} elseif ( in_array( $fp_state, array( 'nonce', 'empty', 'notfound' ), true ) ) {
		wc_add_notice( __( 'تعذر إرسال رابط الاستعادة. تحقّق من البيانات وحاول مرة أخرى.', 'elegance' ), 'error' );
	}
}

get_header();

$assets = ELEGANCE_ELEGANCE_URI . '/assets';
?>
<main>
  <section class="panner y-u-m-b-0">
    <h1 class="y-u-text-center">إعادة تعيين كلمة المرور</h1>
  </section>
  <section class="auth-section">
    <div class="container y-u-max-w-1200">
      <div class="right">
        <?php if ( function_exists( 'woocommerce_output_all_notices' ) ) : ?>
          <?php woocommerce_output_all_notices(); ?>
        <?php endif; ?>
        <form class="forget-password-form" id="forget-password-form" action="<?php echo esc_url( $form_action ); ?>" method="post">
          <div class="form-group">
            <label for="forget-email">البريد الإلكتروني <span class="required">*</span></label>
            <input type="email" id="forget-email" name="user_login" placeholder="" required>
          </div>
          <span class="description">
            أدخل بريدك الإلكتروني لإرسال رابط إعادة تعيين كلمة المرور. تأكد من إدخال البريد المسجل لدينا لاستعادة الوصول إلى حسابك بسهولة.
          </span>
          <?php wp_nonce_field( 'elegance_forgot_password', 'elegance_forgot_password_nonce' ); ?>
          <button type="submit" class="reset-button" name="elegance_forgot_password" value="1">إرسال رابط الاستعادة</button>
        </form>
      </div>
      <div class="left">
        <img src="<?php echo esc_url( $assets . '/login.png' ); ?>" alt="">
      </div>
    </div>
  </section>
</main>
<?php
get_footer();

