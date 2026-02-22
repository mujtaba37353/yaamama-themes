<?php
/**
 * Lost password confirmation — Beauty Care design
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_confirmation_message' );
?>

<section class="auth-section">
	<div class="container y-u-max-w-1200">
		<div class="right">
			<?php
			if ( function_exists( 'wc_print_notice' ) ) {
				wc_print_notice( esc_html__( 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.', 'beauty-care-theme' ) );
			}
			?>
			<p><?php echo esc_html( apply_filters( 'woocommerce_lost_password_confirmation_message', __( 'تم إرسال بريد إعادة تعيين كلمة المرور إلى بريدك الإلكتروني، قد يستغرق عدة دقائق للظهور. يرجى الانتظار 10 دقائق على الأقل قبل المحاولة مرة أخرى.', 'beauty-care-theme' ) ) ); ?></p>
			<a href="<?php echo esc_url( home_url( '/login' ) ); ?>" class="btn main-button"><?php esc_html_e( 'تسجيل الدخول', 'beauty-care-theme' ); ?></a>
		</div>
	</div>
</section>

<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>
