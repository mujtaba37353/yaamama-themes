<?php
/**
 * My Account Login — override
 * Markup from beauty-time/templates/login/login.html
 * Separate page for login only
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

// Redirect if already logged in
if ( is_user_logged_in() ) {
	wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
	exit;
}

do_action( 'woocommerce_before_customer_login_form' );
?>
<main>
	<section class="panner"></section>
	<section class="auth-section">
		<div class="container right-left y-u-max-w-1200">
			<div class="right">
				<?php wc_print_notices(); ?>
				<h2><img src="<?php echo esc_url( beauty_time_asset( 'assets/profile-primary.svg' ) ); ?>" alt=""> <?php esc_html_e( 'تسجيل الدخول', 'beauty-time-theme' ); ?></h2>
				<form class="woocommerce-form woocommerce-form-login login" method="post">
					<?php do_action( 'woocommerce_login_form_start' ); ?>
					<div class="form-group">
						<label for="username"><?php esc_html_e( 'البريد الإلكتروني', 'beauty-time-theme' ); ?></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
					</div>
					<div class="form-group">
						<label for="password"><?php esc_html_e( 'كلمة المرور', 'beauty-time-theme' ); ?></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
					</div>
					<div class="remember-forgot-row">
						<div class="checkbox">
							<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
								<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'تذكرني', 'beauty-time-theme' ); ?></span>
							</label>
						</div>
						<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="forgot-password-link"><?php esc_html_e( 'نسيت كلمة المرور؟', 'beauty-time-theme' ); ?></a>
					</div>
					<?php do_action( 'woocommerce_login_form' ); ?>
					<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
					<button type="submit" class="woocommerce-button button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="login" value="<?php esc_attr_e( 'تسجيل الدخول', 'beauty-time-theme' ); ?>"><?php esc_html_e( 'تسجيل الدخول', 'beauty-time-theme' ); ?></button>
					<?php do_action( 'woocommerce_login_form_end' ); ?>
					<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
						<div class="signup-login-link">
							<span><?php esc_html_e( 'ليس لديك حساب', 'beauty-time-theme' ); ?></span>
							<a href="<?php echo esc_url( add_query_arg( 'action', 'register', wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php esc_html_e( 'أنشئ حساب', 'beauty-time-theme' ); ?></a>
						</div>
					<?php endif; ?>
				</form>
			</div>
		</div>
	</section>
</main>
<?php do_action( 'woocommerce_after_customer_login_form' );
 ?>
