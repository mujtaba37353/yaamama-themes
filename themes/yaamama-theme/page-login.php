<?php
$redirect_to = isset( $_GET['redirect_to'] ) ? esc_url_raw( wp_unslash( $_GET['redirect_to'] ) ) : home_url( '/my-account' );
if ( is_user_logged_in() ) {
	wp_safe_redirect( $redirect_to );
	exit;
}
get_header();
$error = isset( $_GET['auth_error'] ) ? sanitize_text_field( wp_unslash( $_GET['auth_error'] ) ) : '';
?>

<main class="special-bg">
	<section class="auth-section">
		<div class="container y-u-max-w-1200 right-left">
			<div class="right">
				<form id="login-form" class="auth-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<h2>تسجيل الدخول</h2>
					<?php if ( $error ) : ?>
						<p class="y-u-text-s y-u-m-b-16" style="color: var(--y-color-danger);"><?php echo esc_html( $error ); ?></p>
					<?php endif; ?>
					<?php wp_nonce_field( 'yaamama_login', 'yaamama_login_nonce' ); ?>
					<input type="hidden" name="action" value="yaamama_login">
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( $redirect_to ); ?>">

					<div class="form-group">
						<label for="email">البريد الإلكتروني أو اسم المستخدم <span class="required">*</span></label>
						<input type="text" id="email" name="email" placeholder="Example@gmail.com" required autocomplete="username">
					</div>

					<div class="form-group password-group">
						<label for="password">كلمة المرور <span class="required">*</span></label>
						<div class="password-input-wrapper">
							<i class="fa-regular fa-eye password-toggle" data-target="password"></i>
							<input type="password" id="password" name="password" placeholder="أدخل كلمة المرور" required autocomplete="current-password">
						</div>
					</div>
					<div class="auth-actions">
						<div class="auth-remember">
							<label for="remember">تذكرني</label>
							<input type="checkbox" id="remember" name="remember">
						</div>
						<a class="auth-forgot" href="<?php echo esc_url( home_url( '/forget-password' ) ); ?>">هل نسيت كلمة المرور؟</a>
					</div>
					<button type="submit" class="btn main-button fw">تسجيل الدخول</button>
					<p class="new-account">ليس لديك حساب؟ <a href="<?php echo esc_url( home_url( '/signup' ) ); ?>">إنشاء حساب</a></p>
				</form>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
?>
