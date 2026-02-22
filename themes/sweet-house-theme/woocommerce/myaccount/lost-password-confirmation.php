<?php
/**
 * Lost password confirmation — Sweet House design (Arabic)
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;

if ( function_exists( 'sweet_house_account_design_header' ) ) {
	sweet_house_account_design_header();
}

wc_print_notice( esc_html__( 'تم إرسال بريد إعادة تعيين كلمة المرور.', 'sweet-house-theme' ), 'success' );
?>

<?php do_action( 'woocommerce_before_lost_password_confirmation_message' ); ?>

<div class="auth-container">
	<div class="form">
		<p><?php echo esc_html( apply_filters( 'woocommerce_lost_password_confirmation_message', esc_html__( 'تم إرسال بريد إلكتروني لإعادة تعيين كلمة المرور إلى البريد الإلكتروني المسجل في حسابك، وقد يستغرق عدة دقائق للظهور. يرجى الانتظار 10 دقائق على الأقل قبل المحاولة مرة أخرى.', 'sweet-house-theme' ) ) ); ?></p>
		<p class="text y-u-text-center">
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="y-t-text-decoration-none btn-auth" style="display: inline-block; margin-top: 1rem;"><?php esc_html_e( 'العودة لتسجيل الدخول', 'sweet-house-theme' ); ?></a>
		</p>
	</div>
</div>

<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>
