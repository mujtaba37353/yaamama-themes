<?php
/**
 * Forgot password (lost password) page template.
 *
 * @package stationary-theme
 */

get_header();
$au = stationary_base_uri() . '/assets';
?>

<main>
	<section class="panner panner-image y-u-m-b-0 container y-u-max-w-1200">
		<h1 class="y-u-text-center"><?php esc_html_e( 'نسيت كلمة المرور', 'stationary-theme' ); ?></h1>
	</section>

	<section class="auth-section">
		<div class="container y-u-max-w-1200 special">
			<div class="left special-img">
				<img src="<?php echo esc_url( $au . '/login.png' ); ?>" alt="<?php esc_attr_e( 'صورة توضيحية لإعادة تعيين كلمة المرور', 'stationary-theme' ); ?>" onerror="this.style.display='none'">
			</div>
			<div class="right">
				<?php if ( ! empty( $_GET['reset-link-sent'] ) ) : ?>
					<div class="woocommerce-message woocommerce-message--success" role="alert">
						<?php esc_html_e( 'تم إرسال رابط استعادة كلمة المرور إلى بريدك الإلكتروني.', 'stationary-theme' ); ?>
					</div>
					<p><a href="<?php echo esc_url( home_url( '/login' ) ); ?>" class="auth-forgot"><?php esc_html_e( 'العودة لتسجيل الدخول', 'stationary-theme' ); ?></a></p>
				<?php else : ?>
					<?php wc_print_notices(); ?>
					<form method="post" id="forget-password-form" class="woocommerce-ResetPassword lost_reset_password">
						<div class="form-group">
							<label for="user_login"><?php esc_html_e( 'البريد الإلكتروني', 'stationary-theme' ); ?></label>
							<input type="text" id="user_login" name="user_login" autocomplete="username" required>
						</div>
						<p class="auth-note"><?php esc_html_e( 'ادخل بريدك الإلكتروني لإرسال رابط إعادة تعيين كلمة المرور. تأكد من إدخال البريد المسجل لدينا لاستعادة الوصول إلى حسابك بسهولة', 'stationary-theme' ); ?></p>
						<input type="hidden" name="wc_reset_password" value="true">
						<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
						<button type="submit"><?php esc_html_e( 'إرسال الرابط', 'stationary-theme' ); ?></button>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
