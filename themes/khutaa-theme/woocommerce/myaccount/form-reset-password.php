<?php
/**
 * Lost password reset form.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';

// Enqueue auth-specific styles
wp_enqueue_style( 'khutaa-auth', $khutaa_uri . '/components/auth/y-c-auth.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-auth-btn', $khutaa_uri . '/components/buttons/y-c-auth-btn.css', array(), '1.0.0' );

do_action( 'woocommerce_before_reset_password_form' );
?>

<div class="reset-password-form-container">
	<div class="auth-container">
		<div class="img-container">
			<img src="<?php echo esc_url( $khutaa_uri . '/assets/reset-password.png' ); ?>" alt="<?php esc_attr_e( 'إعادة تعيين كلمة المرور', 'khutaa-theme' ); ?>" />
		</div>
		<div class="form">
			<form method="post" class="woocommerce-ResetPassword lost_reset_password">
				<h1>
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather lock">
							<rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
							<path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
						</svg>
					</span>
					<?php esc_html_e( 'إعادة تعيين كلمة المرور', 'khutaa-theme' ); ?>
				</h1>

				<p class="form-description">
					<?php esc_html_e( 'أدخل كلمة مرور جديدة أدناه', 'khutaa-theme' ); ?>
				</p>

				<label for="password_1">
					<?php esc_html_e( 'كلمة المرور الجديدة', 'khutaa-theme' ); ?>
					<span class="required">*</span>
				</label>
				<div class="password-input-wrapper">
					<input 
						type="password" 
						class="woocommerce-Input woocommerce-Input--text input-text" 
						name="password_1" 
						id="password_1" 
						autocomplete="new-password" 
						required 
						aria-required="true" 
					/>
					<button type="button" class="toggle-password" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'khutaa-theme' ); ?>">
						<i class="fas fa-eye"></i>
					</button>
				</div>

				<label for="password_2">
					<?php esc_html_e( 'تأكيد كلمة المرور', 'khutaa-theme' ); ?>
					<span class="required">*</span>
				</label>
				<div class="password-input-wrapper">
					<input 
						type="password" 
						class="woocommerce-Input woocommerce-Input--text input-text" 
						name="password_2" 
						id="password_2" 
						autocomplete="new-password" 
						required 
						aria-required="true" 
					/>
					<button type="button" class="toggle-password" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'khutaa-theme' ); ?>">
						<i class="fas fa-eye"></i>
					</button>
				</div>

				<input type="hidden" name="reset_key" value="<?php echo esc_attr( $args['key'] ); ?>" />
				<input type="hidden" name="reset_login" value="<?php echo esc_attr( $args['login'] ); ?>" />
				<input type="hidden" name="wc_reset_password" value="true" />

				<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>

				<?php do_action( 'woocommerce_resetpassword_form' ); ?>

				<button type="submit" class="btn-auth" value="<?php esc_attr_e( 'حفظ', 'khutaa-theme' ); ?>">
					<?php esc_html_e( 'حفظ', 'khutaa-theme' ); ?>
				</button>

				<p class="text y-u-text-center">
					<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'login' ) ) ?: home_url( '/login' ) ); ?>" class="y-t-text-decoration-none">
						<?php esc_html_e( 'العودة لتسجيل الدخول', 'khutaa-theme' ); ?>
					</a>
				</p>
			</form>
		</div>
	</div>
</div>

<?php
do_action( 'woocommerce_after_reset_password_form' );
?>
