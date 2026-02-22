<?php
/**
 * Login / Register Form — Beauty Care design
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$show_register = is_page( 'signup' );
$forget_url   = home_url( '/forget-password' );
$login_url    = home_url( '/login' );
$signup_url   = home_url( '/signup' );

do_action( 'woocommerce_before_customer_login_form' );
?>

<?php if ( ! $show_register ) : ?>
<section class="auth-section">
	<div class="container y-u-max-w-1200">
		<div class="right">
			<form class="woocommerce-form woocommerce-form-login login" method="post" id="login-form" novalidate>
				<?php do_action( 'woocommerce_login_form_start' ); ?>
				<input type="hidden" name="login" value="1" />
				<div class="form-group">
					<label for="username"><?php esc_html_e( 'البريد الإلكتروني', 'beauty-care-theme' ); ?></label>
					<input type="text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" />
				</div>
				<div class="form-group">
					<label for="password"><?php esc_html_e( 'كلمة المرور', 'beauty-care-theme' ); ?></label>
					<div class="password-input-wrapper">
						<input type="password" name="password" id="password" autocomplete="current-password" />
						<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'beauty-care-theme' ); ?>">
							<i class="fa-regular fa-eye"></i>
						</button>
					</div>
				</div>
				<div class="auth-actions">
					<div class="auth-remember">
						<input type="checkbox" name="rememberme" id="rememberme" value="forever" />
						<label for="rememberme"><?php esc_html_e( 'تذكرني', 'beauty-care-theme' ); ?></label>
					</div>
					<a class="auth-forgot" href="<?php echo esc_url( $forget_url ); ?>"><?php esc_html_e( 'هل نسيت كلمة المرور؟', 'beauty-care-theme' ); ?></a>
				</div>
				<?php do_action( 'woocommerce_login_form' ); ?>
				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<button type="submit" name="login" value="<?php esc_attr_e( 'تسجيل الدخول', 'beauty-care-theme' ); ?>"><?php esc_html_e( 'تسجيل الدخول', 'beauty-care-theme' ); ?></button>
				<p class="auth-switch"><?php esc_html_e( 'ليس لديك حساب ؟', 'beauty-care-theme' ); ?> <a href="<?php echo esc_url( $signup_url ); ?>"><?php esc_html_e( 'إنشاء حساب جديد', 'beauty-care-theme' ); ?></a></p>
				<?php do_action( 'woocommerce_login_form_end' ); ?>
			</form>
		</div>
	</div>
</section>
<?php elseif ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
<section class="auth-section">
	<div class="container y-u-max-w-1200">
		<div class="right">
			<form method="post" class="woocommerce-form woocommerce-form-register register signup-form" id="signup-form" <?php do_action( 'woocommerce_register_form_tag' ); ?> novalidate>
				<?php do_action( 'woocommerce_register_form_start' ); ?>
				<input type="hidden" name="register" value="1" />
				<div class="form-group">
					<label for="reg_email"><?php esc_html_e( 'البريد الإلكتروني', 'beauty-care-theme' ); ?> <span class="required">*</span></label>
					<input type="email" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" />
				</div>
				<div class="form-group">
					<label for="reg_phone"><?php esc_html_e( 'رقم الجوال', 'beauty-care-theme' ); ?> <span class="required">*</span></label>
					<input type="tel" name="billing_phone" id="reg_phone" value="<?php echo ( ! empty( $_POST['billing_phone'] ) ) ? esc_attr( wp_unslash( $_POST['billing_phone'] ) ) : ''; ?>" placeholder="05 xxxx xxxx" dir="ltr" pattern="^05\d{8}$" />
				</div>
				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
				<div class="form-group">
					<label for="reg_password"><?php esc_html_e( 'كلمة المرور', 'beauty-care-theme' ); ?> <span class="required">*</span></label>
					<div class="password-input-wrapper">
						<input type="password" name="password" id="reg_password" autocomplete="new-password" />
						<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'beauty-care-theme' ); ?>">
							<i class="fa-regular fa-eye"></i>
						</button>
					</div>
				</div>
				<div class="form-group">
					<label for="reg_password2"><?php esc_html_e( 'إعادة كلمة المرور', 'beauty-care-theme' ); ?> <span class="required">*</span></label>
					<div class="password-input-wrapper">
						<input type="password" name="password2" id="reg_password2" autocomplete="new-password" />
						<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'beauty-care-theme' ); ?>">
							<i class="fa-regular fa-eye"></i>
						</button>
					</div>
				</div>
				<?php else : ?>
				<p><?php esc_html_e( 'سيتم إرسال رابط لتعيين كلمة المرور إلى بريدك الإلكتروني.', 'beauty-care-theme' ); ?></p>
				<?php endif; ?>
				<?php do_action( 'woocommerce_register_form' ); ?>
				<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
				<button type="submit" name="register" class="signup-button" value="<?php esc_attr_e( 'إنشاء حساب جديد', 'beauty-care-theme' ); ?>"><?php esc_html_e( 'إنشاء حساب جديد', 'beauty-care-theme' ); ?></button>
				<p class="auth-switch"><?php esc_html_e( 'لديك حساب بالفعل؟', 'beauty-care-theme' ); ?> <a href="<?php echo esc_url( $login_url ); ?>"><?php esc_html_e( 'تسجيل الدخول', 'beauty-care-theme' ); ?></a></p>
				<?php do_action( 'woocommerce_register_form_end' ); ?>
			</form>
		</div>
	</div>
</section>
<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>
