<?php
/**
 * Reset password form — Sweet House design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );
?>

<div class="auth-container woocommerce">
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

		<p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'أدخل كلمة مرور جديدة أدناه.', 'sweet-house-theme' ) ); ?></p>

		<form method="post" class="woocommerce-ResetPassword lost_reset_password">
			<div class="form-group">
				<label for="password_1"><?php esc_html_e( 'كلمة المرور الجديدة', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" required aria-required="true" />
			</div>
			<div class="form-group">
				<label for="password_2"><?php esc_html_e( 'تأكيد كلمة المرور', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" required aria-required="true" />
			</div>

			<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
			<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />

			<?php do_action( 'woocommerce_resetpassword_form' ); ?>

			<input type="hidden" name="wc_reset_password" value="true" />
			<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
			<button type="submit" class="btn-auth woocommerce-Button button" value="<?php esc_attr_e( 'حفظ', 'sweet-house-theme' ); ?>"><?php esc_html_e( 'حفظ', 'sweet-house-theme' ); ?></button>
		</form>
	</div>
</div>

<?php
do_action( 'woocommerce_after_reset_password_form' );
