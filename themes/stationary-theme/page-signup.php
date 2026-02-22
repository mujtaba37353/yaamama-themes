<?php
/**
 * Registration (Signup) page template.
 *
 * @package stationary-theme
 */

get_header();
$au       = stationary_base_uri() . '/assets';
$redirect = isset( $_GET['redirect_to'] ) ? esc_url( wp_unslash( $_GET['redirect_to'] ) ) : ( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url() );
?>

<main>
	<section class="panner panner-image y-u-m-b-0 container y-u-max-w-1200">
		<h1 class="y-u-text-center"><?php esc_html_e( 'إنشاء حساب جديد', 'stationary-theme' ); ?></h1>
	</section>

	<section class="auth-section">
		<div class="container y-u-max-w-1200 special">
			<div class="left special-img">
				<img src="<?php echo esc_url( $au . '/signup.png' ); ?>" alt="<?php esc_attr_e( 'صورة توضيحية لإنشاء حساب جديد', 'stationary-theme' ); ?>" onerror="this.style.display='none'">
			</div>
			<div class="right">
				<?php wc_print_notices(); ?>
				<form method="post" id="signup-form" class="woocommerce-form woocommerce-form-register signup-form">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<div class="form-group">
						<label for="reg_email"><?php esc_html_e( 'البريد الإلكتروني', 'stationary-theme' ); ?> <span class="required">*</span></label>
						<input type="email" id="reg_email" name="email" value="<?php echo ! empty( $_POST['email'] ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" autocomplete="email" required>
					</div>
					<div class="form-group">
						<label for="reg_phone"><?php esc_html_e( 'رقم الجوال', 'stationary-theme' ); ?> <span class="required">*</span></label>
						<input type="tel" id="reg_phone" name="billing_phone" value="<?php echo ! empty( $_POST['billing_phone'] ) ? esc_attr( wp_unslash( $_POST['billing_phone'] ) ) : ''; ?>" placeholder="05 xxxx xxxx" dir="ltr">
					</div>
					<div class="form-group">
						<label for="reg_password"><?php esc_html_e( 'كلمة المرور', 'stationary-theme' ); ?> <span class="required">*</span></label>
						<div class="password-input-wrapper">
							<input type="password" id="reg_password" name="password" autocomplete="new-password" required>
							<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'stationary-theme' ); ?>">
								<i class="fa-regular fa-eye"></i>
							</button>
						</div>
					</div>
					<div class="form-group">
						<label for="reg_confirm_password"><?php esc_html_e( 'إعادة كلمة المرور', 'stationary-theme' ); ?> <span class="required">*</span></label>
						<div class="password-input-wrapper">
							<input type="password" id="reg_confirm_password" name="confirm_password" autocomplete="new-password" required>
							<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'stationary-theme' ); ?>">
								<i class="fa-regular fa-eye"></i>
							</button>
						</div>
					</div>
					<input type="hidden" name="redirect" value="<?php echo esc_attr( $redirect ); ?>">
					<button type="submit" name="register" value="<?php esc_attr_e( 'إنشاء حساب جديد', 'stationary-theme' ); ?>" class="signup-button"><?php esc_html_e( 'إنشاء حساب جديد', 'stationary-theme' ); ?></button>
					<p class="auth-switch"><?php esc_html_e( 'لديك حساب بالفعل؟', 'stationary-theme' ); ?> <a href="<?php echo esc_url( home_url( '/login' ) ); ?>"><?php esc_html_e( 'تسجيل الدخول', 'stationary-theme' ); ?></a></p>
				</form>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
