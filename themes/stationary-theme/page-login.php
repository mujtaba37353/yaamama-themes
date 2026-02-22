<?php
/**
 * Login page template.
 *
 * @package stationary-theme
 */

get_header();
$au       = stationary_base_uri() . '/assets';
$redirect = isset( $_GET['redirect_to'] ) ? esc_url( wp_unslash( $_GET['redirect_to'] ) ) : ( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url() );
?>

<main>
	<section class="panner panner-image y-u-m-b-0 container y-u-max-w-1200">
		<h1 class="y-u-text-center"><?php esc_html_e( 'تسجيل الدخول', 'stationary-theme' ); ?></h1>
	</section>

	<section class="auth-section">
		<div class="container y-u-max-w-1200 special">
			<div class="left special-img">
				<img src="<?php echo esc_url( $au . '/login.png' ); ?>" alt="<?php esc_attr_e( 'صورة توضيحية لتسجيل الدخول', 'stationary-theme' ); ?>" onerror="this.style.display='none'">
			</div>
			<div class="right">
				<?php
				if ( ! empty( $_GET['password-reset'] ) ) {
					wc_add_notice( __( 'تم تعيين كلمة المرور الجديدة. يمكنك تسجيل الدخول الآن.', 'stationary-theme' ), 'success' );
				}
				wc_print_notices();
				?>
				<form method="post" id="login-form" class="woocommerce-form woocommerce-form-login">
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<div class="form-group">
						<label for="username"><?php esc_html_e( 'البريد الإلكتروني', 'stationary-theme' ); ?></label>
						<input type="text" id="username" name="username" value="<?php echo ! empty( $_POST['username'] ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" autocomplete="username" required>
					</div>
					<div class="form-group">
						<label for="password"><?php esc_html_e( 'كلمة المرور', 'stationary-theme' ); ?></label>
						<div class="password-input-wrapper">
							<input type="password" id="password" name="password" autocomplete="current-password" required>
							<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'stationary-theme' ); ?>">
								<i class="fa-regular fa-eye"></i>
							</button>
						</div>
					</div>
					<div class="auth-actions">
						<div class="auth-remember">
							<input type="checkbox" id="rememberme" name="rememberme" value="forever">
							<label for="rememberme"><?php esc_html_e( 'تذكرني', 'stationary-theme' ); ?></label>
						</div>
						<a class="auth-forgot" href="<?php echo esc_url( home_url( '/forget-password' ) ); ?>"><?php esc_html_e( 'هل نسيت كلمة المرور؟', 'stationary-theme' ); ?></a>
					</div>
					<input type="hidden" name="redirect" value="<?php echo esc_attr( $redirect ); ?>">
					<button type="submit" name="login" value="<?php esc_attr_e( 'تسجيل الدخول', 'stationary-theme' ); ?>"><?php esc_html_e( 'تسجيل الدخول', 'stationary-theme' ); ?></button>
					<p class="auth-switch"><?php esc_html_e( 'ليس لديك حساب؟', 'stationary-theme' ); ?> <a href="<?php echo esc_url( home_url( '/signup' ) ); ?>"><?php esc_html_e( 'إنشاء حساب جديد', 'stationary-theme' ); ?></a></p>
				</form>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
