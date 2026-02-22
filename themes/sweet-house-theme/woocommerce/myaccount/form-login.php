<?php
/**
 * Login Form — Sweet House design (صفحة تسجيل الدخول منفصلة)
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$signup_url = function_exists( 'sweet_house_get_page_url' ) ? sweet_house_get_page_url( 'sign-up', home_url( '/sign-up/' ) ) : home_url( '/sign-up/' );

do_action( 'woocommerce_before_customer_login_form' );
?>

<div class="auth-container">
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
			<?php esc_html_e( 'تسجيل دخول', 'sweet-house-theme' ); ?>
		</h1>

		<form class="woocommerce-form woocommerce-form-login login" method="post" novalidate>
			<?php do_action( 'woocommerce_login_form_start' ); ?>

			<div class="form-group">
				<label for="username"><?php esc_html_e( 'البريد الإلكتروني أو اسم المستخدم', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
				<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" />
			</div>

			<div class="form-group">
				<label for="password"><?php esc_html_e( 'كلمة المرور', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
				<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" required aria-required="true" />
			</div>

			<?php do_action( 'woocommerce_login_form' ); ?>

			<div class="y-u-d-flex y-u-justify-between y-u-align-items-center y-u-mb-4">
				<label class="checkbox y-u-d-flex y-u-align-items-center woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
					<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
					<span class="checkmark"></span>
					<span><?php esc_html_e( 'تذكرني', 'sweet-house-theme' ); ?></span>
				</label>
				<a href="<?php echo esc_url( function_exists( 'wc_lostpassword_url' ) ? wc_lostpassword_url() : wp_lostpassword_url() ); ?>" class="y-t-text-decoration-none"><?php esc_html_e( 'نسيت كلمة المرور؟', 'sweet-house-theme' ); ?></a>
			</div>

			<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
			<button type="submit" class="btn-auth woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'تسجيل دخول', 'sweet-house-theme' ); ?>"><?php esc_html_e( 'تسجيل دخول', 'sweet-house-theme' ); ?></button>

			<?php do_action( 'woocommerce_login_form_end' ); ?>
		</form>

		<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
		<p class="text y-u-text-center">
			<?php esc_html_e( 'ليس لديك حساب؟', 'sweet-house-theme' ); ?>
			<a href="<?php echo esc_url( $signup_url ); ?>" class="y-t-text-decoration-none"><?php esc_html_e( 'إنشاء حساب', 'sweet-house-theme' ); ?></a>
		</p>
		<?php endif; ?>
	</div>
</div>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
