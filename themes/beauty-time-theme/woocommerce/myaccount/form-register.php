<?php
/**
 * My Account Register — override
 * Markup from beauty-time/templates/signup/signup.html
 * Separate page for registration
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

// Redirect if already logged in
if ( is_user_logged_in() ) {
	wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
	exit;
}

do_action( 'woocommerce_before_customer_register_form' );
?>
<main>
	<section class="panner"></section>
	<section class="auth-section">
		<div class="container right-left y-u-max-w-1200">
			<div class="right">
				<?php wc_print_notices(); ?>
				<h2><img src="<?php echo esc_url( beauty_time_asset( 'assets/profile-primary.svg' ) ); ?>" alt=""> <?php esc_html_e( 'إنشاء حساب جديد', 'beauty-time-theme' ); ?></h2>
				<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
					<?php do_action( 'woocommerce_register_form_start' ); ?>
					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
						<div class="form-group">
							<label for="reg_username"><?php esc_html_e( 'اسم المستخدم', 'beauty-time-theme' ); ?></label>
							<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
						</div>
					<?php endif; ?>
					<div class="form-group">
						<label for="reg_email"><?php esc_html_e( 'البريد الإلكتروني', 'beauty-time-theme' ); ?></label>
						<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" />
					</div>
					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
						<div class="form-group">
							<label for="reg_password"><?php esc_html_e( 'كلمة المرور', 'beauty-time-theme' ); ?></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
						</div>
						<div class="form-group">
							<label for="reg_password_confirm"><?php esc_html_e( 'تأكيد كلمة المرور', 'beauty-time-theme' ); ?></label>
							<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_confirm" id="reg_password_confirm" autocomplete="new-password" />
						</div>
					<?php else : ?>
						<p><?php esc_html_e( 'سيتم إرسال رابط لإنشاء كلمة مرور جديدة إلى بريدك الإلكتروني.', 'beauty-time-theme' ); ?></p>
					<?php endif; ?>
					<div class="checkbox">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-register__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="rememberme" type="checkbox" id="reg_rememberme" value="forever" /> <span><?php esc_html_e( 'تذكرني', 'beauty-time-theme' ); ?></span>
						</label>
					</div>
					<?php do_action( 'woocommerce_register_form' ); ?>
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" name="register" value="<?php esc_attr_e( 'إنشاء حساب', 'beauty-time-theme' ); ?>"><?php esc_html_e( 'إنشاء حساب', 'beauty-time-theme' ); ?></button>
					<?php do_action( 'woocommerce_register_form_end' ); ?>
					<div class="signup-login-link">
						<span><?php esc_html_e( 'لدي حساب بالفعل ؟', 'beauty-time-theme' ); ?></span>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php esc_html_e( 'تسجيل دخول', 'beauty-time-theme' ); ?></a>
					</div>
				</form>
			</div>
		</div>
	</section>
</main>
<?php do_action( 'woocommerce_after_customer_register_form' );
