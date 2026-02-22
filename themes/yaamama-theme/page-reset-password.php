<?php
if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url( '/my-account' ) );
	exit;
}
get_header();

$reset_success = isset( $_GET['reset_success'] );
$reset_error   = isset( $_GET['reset_error'] ) ? sanitize_text_field( wp_unslash( $_GET['reset_error'] ) ) : '';
$login         = isset( $_GET['login'] ) ? sanitize_text_field( wp_unslash( $_GET['login'] ) ) : '';
$key           = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
$show_form     = false;

if ( ! $reset_success && $login && $key ) {
	$user = check_password_reset_key( $key, $login );
	if ( is_wp_error( $user ) ) {
		$reset_error = 'الرابط غير صالح أو منتهي. اطلب رابطاً جديداً.';
	} else {
		$show_form = true;
	}
}
?>

<main class="special-bg">
	<section class="auth-section reset-password-section">
		<div class="container y-u-max-w-1200 right-left">
			<div class="right">
				<?php if ( $reset_success ) : ?>
					<div class="auth-form">
						<h2>تم تحديث كلمة المرور</h2>
						<p class="forget-text">يمكنك الآن تسجيل الدخول باستخدام كلمة المرور الجديدة.</p>
						<a href="<?php echo esc_url( home_url( '/login' ) ); ?>" class="btn main-button fw">العودة لتسجيل الدخول</a>
					</div>
				<?php elseif ( $show_form ) : ?>
					<form id="reset-password-form" class="auth-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<h2>إعادة تعيين كلمة المرور</h2>
						<?php if ( $reset_error ) : ?>
							<p class="y-u-text-s y-u-m-b-16" style="color: var(--y-color-danger);"><?php echo esc_html( $reset_error ); ?></p>
						<?php endif; ?>
						<?php wp_nonce_field( 'yaamama_reset_password', 'yaamama_reset_password_nonce' ); ?>
						<input type="hidden" name="action" value="yaamama_reset_password">
						<input type="hidden" name="login" value="<?php echo esc_attr( $login ); ?>">
						<input type="hidden" name="key" value="<?php echo esc_attr( $key ); ?>">

						<div class="form-group password-group">
							<label for="password">كلمة المرور الجديدة <span class="required">*</span></label>
							<div class="password-input-wrapper">
								<i class="fa-regular fa-eye password-toggle" data-target="password"></i>
								<input type="password" id="password" name="password" placeholder="أدخل كلمة المرور الجديدة" required autocomplete="new-password">
							</div>
						</div>

						<div class="form-group password-group">
							<label for="confirm-password">تأكيد كلمة المرور <span class="required">*</span></label>
							<div class="password-input-wrapper">
								<i class="fa-regular fa-eye password-toggle" data-target="confirm-password"></i>
								<input type="password" id="confirm-password" name="confirm-password" placeholder="أعد إدخال كلمة المرور" required autocomplete="new-password">
							</div>
						</div>

						<button type="submit" class="btn main-button fw">تحديث كلمة المرور</button>
					</form>
				<?php else : ?>
					<div class="auth-form">
						<h2>رابط غير صالح</h2>
						<?php if ( $reset_error ) : ?>
							<p class="y-u-text-s y-u-m-b-16" style="color: var(--y-color-danger);"><?php echo esc_html( $reset_error ); ?></p>
						<?php else : ?>
							<p class="forget-text">يرجى طلب رابط جديد لإعادة تعيين كلمة المرور.</p>
						<?php endif; ?>
						<a href="<?php echo esc_url( home_url( '/forget-password' ) ); ?>" class="btn main-button fw">طلب رابط جديد</a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
?>
