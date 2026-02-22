<?php
if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url( '/my-account' ) );
	exit;
}
get_header();
$error = isset( $_GET['auth_error'] ) ? sanitize_text_field( wp_unslash( $_GET['auth_error'] ) ) : '';
?>

<main class="special-bg">
	<section class="auth-section">
		<div class="container y-u-max-w-1200 right-left">
			<div class="right">
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="signup-form" method="post" novalidate>
					<h2>إنشاء حساب</h2>
					<?php if ( $error ) : ?>
						<p class="y-u-text-s y-u-m-b-16" style="color: var(--y-color-danger);"><?php echo esc_html( $error ); ?></p>
					<?php endif; ?>
					<?php wp_nonce_field( 'yaamama_signup', 'yaamama_signup_nonce' ); ?>
					<input type="hidden" name="action" value="yaamama_signup">
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( home_url( '/my-account' ) ); ?>">

					<div class="form-group">
						<label for="fullname">الاسم كامل <span class="required">*</span></label>
						<input type="text" id="fullname" name="fullname" placeholder="أدخل اسمك الكامل" required autocomplete="name">
					</div>

					<div class="form-group">
						<label for="email">البريد الإلكتروني <span class="required">*</span></label>
						<input type="email" id="email" name="email" placeholder="Example@gmail.com" required autocomplete="email">
					</div>

					<div class="form-group">
						<label for="phone">رقم الجوال <span class="required">*</span></label>
						<input type="tel" id="phone" name="phone" placeholder="05xxxxxxxxx" required autocomplete="tel">
					</div>

					<div class="form-group password-group">
						<label for="password">كلمة المرور <span class="required">*</span></label>
						<div class="password-input-wrapper">
							<i class="fa-regular fa-eye password-toggle" data-target="password"></i>
							<input type="password" id="password" name="password" placeholder="أدخل كلمة المرور" required autocomplete="new-password">
						</div>
					</div>

					<div class="form-group password-group">
						<label for="confirm-password">تأكيد كلمة المرور <span class="required">*</span></label>
						<div class="password-input-wrapper">
							<i class="fa-regular fa-eye password-toggle" data-target="confirm-password"></i>
							<input type="password" id="confirm-password" name="confirm-password" placeholder="أعد إدخال كلمة المرور" required autocomplete="new-password">
						</div>
					</div>

					<button type="submit" class="btn main-button fw">إنشاء حساب</button>
					<p class="new-account">لديك حساب بالفعل؟ <a href="<?php echo esc_url( home_url( '/login' ) ); ?>">تسجيل الدخول</a></p>
				</form>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
?>
