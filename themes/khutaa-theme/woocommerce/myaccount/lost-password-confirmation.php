<?php
/**
 * Lost password confirmation text.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.9.0
 */

defined( 'ABSPATH' ) || exit;
?>

<div class="password-reset-confirmation">
	<div class="confirmation-box">
		<div class="confirmation-icon">
			<i class="fas fa-envelope-circle-check"></i>
		</div>
		<h2 class="confirmation-title">
			<?php esc_html_e( 'تم إرسال بريد إعادة تعيين كلمة المرور', 'khutaa-theme' ); ?>
		</h2>
		<p class="confirmation-message">
			<?php esc_html_e( 'تم إرسال بريد إلكتروني لإعادة تعيين كلمة المرور إلى عنوان البريد الإلكتروني المسجل في حسابك. قد يستغرق وصوله إلى صندوق الوارد عدة دقائق. يرجى الانتظار 10 دقائق على الأقل قبل محاولة إعادة التعيين مرة أخرى.', 'khutaa-theme' ); ?>
		</p>
		<div class="confirmation-actions">
			<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'login' ) ) ?: home_url( '/login' ) ); ?>" class="btn-back-login">
				<i class="fas fa-arrow-right"></i>
				<?php esc_html_e( 'العودة لتسجيل الدخول', 'khutaa-theme' ); ?>
			</a>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-back-home">
				<i class="fas fa-home"></i>
				<?php esc_html_e( 'العودة للرئيسية', 'khutaa-theme' ); ?>
			</a>
		</div>
	</div>
</div>

<?php do_action( 'woocommerce_after_lost_password_confirmation_message' ); ?>
