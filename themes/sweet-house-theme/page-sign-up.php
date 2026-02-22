<?php
/**
 * Template Name: إنشاء حساب
 * Sign-up / Registration page — Sweet House design
 *
 * @package Sweet_House_Theme
 */

get_header();

if ( is_user_logged_in() ) {
	wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
	exit;
}

if ( 'yes' !== get_option( 'woocommerce_enable_myaccount_registration' ) ) {
	echo '<main data-y="main"><div class="main-container"><div class="auth-container"><div class="form"><p>' . esc_html__( 'التسجيل غير متاح حالياً. يرجى التفعيل من إعدادات WooCommerce.', 'sweet-house-theme' ) . '</p></div></div></div></main>';
	get_footer();
	return;
}

$asset_uri = sweet_house_asset_uri( '' );
?>
<header data-y="design-header" class="account-design-header">
	<img src="<?php echo esc_url( $asset_uri . 'assets/panner.png' ); ?>" alt="<?php esc_attr_e( 'بانر سويت هاوس - إنشاء حساب', 'sweet-house-theme' ); ?>" class="panner-img" />
</header>

<main data-y="main">
	<div class="main-container">
		<nav class="y-breadcrumb-container" aria-label="<?php esc_attr_e( 'مسار التنقل', 'sweet-house-theme' ); ?>">
			<ol class="y-breadcrumb">
				<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
				<li class="y-breadcrumb-item active"><?php esc_html_e( 'إنشاء حساب', 'sweet-house-theme' ); ?></li>
			</ol>
		</nav>

		<div class="auth-container woocommerce">
			<div class="form">
				<h1>
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
							<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
							<circle cx="8.5" cy="7" r="4"></circle>
							<line x1="20" y1="8" x2="20" y2="14"></line>
							<line x1="23" y1="11" x2="17" y2="11"></line>
						</svg>
					</span>
					<?php esc_html_e( 'إنشاء حساب جديد', 'sweet-house-theme' ); ?>
				</h1>

				<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
					<?php do_action( 'woocommerce_register_form_start' ); ?>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
					<div class="form-group">
						<label for="reg_username"><?php esc_html_e( 'اسم المستخدم', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" />
					</div>
					<?php endif; ?>

					<div class="form-group">
						<label for="reg_email"><?php esc_html_e( 'البريد الإلكتروني', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" />
					</div>

					<div class="form-group">
						<label for="billing_phone"><?php esc_html_e( 'رقم الجوال', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="tel" class="woocommerce-Input woocommerce-Input--text input-text" name="billing_phone" id="billing_phone" autocomplete="tel" value="<?php echo ( ! empty( $_POST['billing_phone'] ) && is_string( $_POST['billing_phone'] ) ) ? esc_attr( wp_unslash( $_POST['billing_phone'] ) ) : ''; ?>" placeholder="05xxxxxxxx" pattern="05[0-9]{8}" title="<?php esc_attr_e( 'يجب أن يبدأ الرقم بـ 05 ويليه 8 أرقام (مثال: 0512345678)', 'sweet-house-theme' ); ?>" required aria-required="true" inputmode="numeric" maxlength="10" />
					</div>

					<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
					<div class="form-group">
						<label for="reg_password"><?php esc_html_e( 'كلمة المرور', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
					</div>
					<div class="form-group">
						<label for="reg_password_confirm"><?php esc_html_e( 'إعادة كتابة كلمة المرور', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
						<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_confirm" id="reg_password_confirm" autocomplete="new-password" required aria-required="true" />
						<span id="reg_password_mismatch" class="password-mismatch-error" style="display:none;color:#dc3545;font-size:12px;margin-top:4px;"><?php esc_html_e( 'كلمة المرور غير متطابقة', 'sweet-house-theme' ); ?></span>
					</div>
					<?php else : ?>
					<p><?php esc_html_e( 'سيصلك رابط لتعيين كلمة مرور جديدة عبر البريد الإلكتروني.', 'sweet-house-theme' ); ?></p>
					<?php endif; ?>

					<?php do_action( 'woocommerce_register_form' ); ?>

					<p class="form-row">
						<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
						<button type="submit" class="btn-auth woocommerce-Button woocommerce-button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'إنشاء حساب', 'sweet-house-theme' ); ?>"><?php esc_html_e( 'إنشاء حساب', 'sweet-house-theme' ); ?></button>
					</p>

					<?php do_action( 'woocommerce_register_form_end' ); ?>
				</form>

				<p class="text y-u-text-center">
					<?php esc_html_e( 'لديك حساب بالفعل؟', 'sweet-house-theme' ); ?>
					<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="y-t-text-decoration-none"><?php esc_html_e( 'تسجيل دخول', 'sweet-house-theme' ); ?></a>
				</p>
			</div>
		</div>
	</div>
</main>

<?php
if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) :
	?>
<script>
(function() {
	var form = document.querySelector('.woocommerce-form-register');
	if (!form) return;
	var pass = document.getElementById('reg_password');
	var confirm = document.getElementById('reg_password_confirm');
	var mismatchEl = document.getElementById('reg_password_mismatch');

	function checkMatch() {
		if (!confirm.value) {
			mismatchEl.style.display = 'none';
			confirm.setCustomValidity('');
			return;
		}
		if (pass.value !== confirm.value) {
			mismatchEl.style.display = 'block';
			confirm.setCustomValidity('<?php echo esc_js( __( 'كلمة المرور غير متطابقة', 'sweet-house-theme' ) ); ?>');
		} else {
			mismatchEl.style.display = 'none';
			confirm.setCustomValidity('');
		}
	}

	if (pass && confirm) {
		pass.addEventListener('input', checkMatch);
		confirm.addEventListener('input', checkMatch);
		form.addEventListener('submit', function(e) {
			checkMatch();
			if (pass.value !== confirm.value) {
				e.preventDefault();
				confirm.focus();
				return false;
			}
		});
	}
})();
</script>
	<?php
endif;
get_footer();
?>
