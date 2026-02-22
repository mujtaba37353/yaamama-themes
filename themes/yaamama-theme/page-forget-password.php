<?php
if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url( '/my-account' ) );
	exit;
}
get_header();
$error = isset( $_GET['auth_error'] ) ? sanitize_text_field( wp_unslash( $_GET['auth_error'] ) ) : '';
$sent  = isset( $_GET['sent'] );
?>

<main class="special-bg">
	<section class="auth-section">
		<div class="container y-u-max-w-1200 right-left">
			<div class="right">
				<form id="forget-form" class="auth-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<h2>نسيت كلمة المرور</h2>
					<?php if ( $sent ) : ?>
						<p class="y-u-text-s y-u-m-b-16" style="color: var(--y-color-primary);">تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك.</p>
					<?php elseif ( $error ) : ?>
						<p class="y-u-text-s y-u-m-b-16" style="color: var(--y-color-danger);"><?php echo esc_html( $error ); ?></p>
					<?php endif; ?>
					<?php wp_nonce_field( 'yaamama_forgot_password', 'yaamama_forgot_password_nonce' ); ?>
					<input type="hidden" name="action" value="yaamama_forgot_password">
					<div class="form-group">
						<label for="email">البريد الإلكتروني</label>
						<input type="email" id="email" name="email" required>
					</div>
					<p class="forget-text">أدخل بريدك الإلكتروني لإرسال رابط إعادة تعيين كلمة المرور. تأكد من إدخال البريد
						المسجل لدينا لاستعادة الوصول إلى حسابك بسهولة</p>
					<button type="submit" id="forget-btn" class="btn main-button fw">إرسال الرابط</button>
				</form>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
?>
