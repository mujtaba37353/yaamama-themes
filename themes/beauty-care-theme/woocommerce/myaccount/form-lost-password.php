<?php
/**
 * Lost password form — Beauty Care design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

$action_url = function_exists( 'wc_get_endpoint_url' ) && function_exists( 'wc_get_page_permalink' )
	? wc_get_endpoint_url( 'lost-password', '', wc_get_page_permalink( 'myaccount' ) )
	: home_url( '/my-account/lost-password/' );

do_action( 'woocommerce_before_lost_password_form' );
?>

<section class="auth-section">
	<div class="container y-u-max-w-1200">
		<div class="right">
			<form method="post" class="woocommerce-ResetPassword lost_reset_password" id="forget-password-form" action="<?php echo esc_url( $action_url ); ?>">
				<?php do_action( 'woocommerce_lostpassword_form' ); ?>
				<div class="form-group">
					<label for="user_login"><?php esc_html_e( 'البريد الإلكتروني', 'beauty-care-theme' ); ?></label>
					<input type="text" name="user_login" id="user_login" autocomplete="username" />
				</div>
				<p class="auth-note"><?php esc_html_e( 'ادخل بريدك الإلكتروني لإرسال رابط إعادة تعيين كلمة المرور. تأكد من إدخال البريد المسجل لدينا لاستعادة الوصول إلى حسابك بسهولة', 'beauty-care-theme' ); ?></p>
				<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
				<input type="hidden" name="wc_reset_password" value="true" />
				<button type="submit"><?php esc_html_e( 'إرسال الرابط', 'beauty-care-theme' ); ?></button>
			</form>
		</div>
	</div>
</section>
<?php
do_action( 'woocommerce_after_lost_password_form' );
