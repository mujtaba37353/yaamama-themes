<?php
if ( is_user_logged_in() ) {
	wp_safe_redirect( dark_theme_get_page_url( 'my-account' ) );
	exit;
}

get_header();
$error = isset( $_GET['auth_error'] ) ? sanitize_text_field( urldecode( wp_unslash( $_GET['auth_error'] ) ) ) : '';
$sent  = isset( $_GET['sent'] );
?>

<header data-y="design-header">
	<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/header2.png' ) ); ?>" alt="" class="design-img y-u-w-100" />
</header>

<main data-y="main" class="y-u-container">
	<div class="y-main-container">
		<div data-y="breadcrumb">
			<nav aria-label="breadcrumb" class="y-breadcrumb-container">
				<ol class="y-breadcrumb"></ol>
			</nav>
		</div>
		<div class="auth-container">
			<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/password.png' ) ); ?>" alt="">
			<div class="form">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<h1>نسيت كلمة المرور</h1>
					<?php if ( $sent ) : ?>
						<div class="y-c-auth-message y-c-auth-message--success" role="status">
							<svg class="y-c-auth-message__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
							<span class="y-c-auth-message__text">تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك.</span>
						</div>
					<?php elseif ( $error ) : ?>
						<div class="y-c-auth-message y-c-auth-message--error" role="alert">
							<svg class="y-c-auth-message__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
							<span class="y-c-auth-message__text"><?php echo esc_html( $error ); ?></span>
						</div>
					<?php endif; ?>
					<?php wp_nonce_field( 'dark_theme_forgot_password', 'dark_theme_forgot_password_nonce' ); ?>
					<input type="hidden" name="action" value="dark_theme_forgot_password">

					<label for="email">البريد الإلكتروني</label>
					<input type="email" id="email" name="email" required />
					<p class="forget-text">أدخل بريدك الإلكتروني لإرسال رابط إعادة تعيين كلمة المرور.</p>
					<button type="submit" class="btn-auth">إرسال الرابط</button>
				</form>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
