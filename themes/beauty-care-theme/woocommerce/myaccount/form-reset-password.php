<?php
/**
 * Reset password form — Beauty Care design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_reset_password_form' );
?>

<section class="auth-section">
	<div class="container y-u-max-w-1200">
		<div class="right">
			<form method="post" class="woocommerce-ResetPassword lost_reset_password">
				<p><?php echo apply_filters( 'woocommerce_reset_password_message', esc_html__( 'أدخل كلمة المرور الجديدة أدناه.', 'beauty-care-theme' ) ); ?></p>
				<div class="form-group">
					<label for="password_1"><?php esc_html_e( 'كلمة المرور الجديدة', 'beauty-care-theme' ); ?> <span class="required">*</span></label>
					<div class="password-input-wrapper">
						<input type="password" name="password_1" id="password_1" autocomplete="new-password" />
						<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'beauty-care-theme' ); ?>">
							<i class="fa-regular fa-eye"></i>
						</button>
					</div>
				</div>
				<div class="form-group">
					<label for="password_2"><?php esc_html_e( 'إعادة كلمة المرور الجديدة', 'beauty-care-theme' ); ?> <span class="required">*</span></label>
					<div class="password-input-wrapper">
						<input type="password" name="password_2" id="password_2" autocomplete="new-password" />
						<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'beauty-care-theme' ); ?>">
							<i class="fa-regular fa-eye"></i>
						</button>
					</div>
				</div>
				<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
				<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />
				<?php do_action( 'woocommerce_resetpassword_form' ); ?>
				<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
				<input type="hidden" name="wc_reset_password" value="true" />
				<button type="submit" value="<?php esc_attr_e( 'حفظ', 'beauty-care-theme' ); ?>"><?php esc_html_e( 'حفظ', 'beauty-care-theme' ); ?></button>
			</form>
		</div>
	</div>
</section>
<?php
do_action( 'woocommerce_after_reset_password_form' );
