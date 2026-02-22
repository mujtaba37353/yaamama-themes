<?php
/**
 * Lost password form — Sweet House design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>

<div class="auth-container">
	<div class="form">
		<h1>
			<span>
				<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather user">
					<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
					<circle cx="8.5" cy="7" r="4"></circle>
					<line x1="20" y1="8" x2="20" y2="14"></line>
					<line x1="23" y1="11" x2="17" y2="11"></line>
				</svg>
			</span>
			<?php esc_html_e( 'استعادة كلمة المرور', 'sweet-house-theme' ); ?>
		</h1>

		<p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'نسيت كلمة المرور؟ أدخل اسم المستخدم أو البريد الإلكتروني. سيصلك رابط لإنشاء كلمة مرور جديدة عبر البريد الإلكتروني.', 'sweet-house-theme' ) ); ?></p>

		<form method="post" class="woocommerce-ResetPassword lost_reset_password">
			<div class="form-group">
				<label for="user_login"><?php esc_html_e( 'اسم المستخدم أو البريد الإلكتروني', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" required aria-required="true" />
			</div>

			<?php do_action( 'woocommerce_lostpassword_form' ); ?>

			<input type="hidden" name="wc_reset_password" value="true" />
			<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
			<button type="submit" class="btn-auth woocommerce-Button button" value="<?php esc_attr_e( 'إعادة تعيين كلمة المرور', 'sweet-house-theme' ); ?>"><?php esc_html_e( 'التالي', 'sweet-house-theme' ); ?></button>
		</form>

		<p class="text y-u-text-center">
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="y-t-text-decoration-none"><?php esc_html_e( 'العودة لتسجيل الدخول', 'sweet-house-theme' ); ?></a>
		</p>
	</div>
</div>

<?php
do_action( 'woocommerce_after_lost_password_form' );
