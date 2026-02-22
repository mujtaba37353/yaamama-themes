<?php
if ( is_user_logged_in() ) {
	wp_safe_redirect( dark_theme_get_page_url( 'my-account' ) );
	exit;
}

get_header();
$error = isset( $_GET['auth_error'] ) ? sanitize_text_field( urldecode( wp_unslash( $_GET['auth_error'] ) ) ) : '';
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
			<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/login.png' ) ); ?>" alt="">
			<div class="form">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
					<h1>
						<span>
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather user">
								<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
								<circle cx="8.5" cy="7" r="4"></circle>
								<line x1="20" y1="8" x2="20" y2="14"></line>
								<line x1="23" y1="11" x2="17" y2="11"></line>
							</svg>
						</span>
						تسجيل دخول
					</h1>
					<?php if ( $error ) : ?>
						<div class="y-c-auth-message y-c-auth-message--error" role="alert">
							<svg class="y-c-auth-message__icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
							<span class="y-c-auth-message__text"><?php echo esc_html( $error ); ?></span>
						</div>
					<?php endif; ?>
					<?php wp_nonce_field( 'dark_theme_login', 'dark_theme_login_nonce' ); ?>
					<input type="hidden" name="action" value="dark_theme_login">
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( dark_theme_get_page_url( 'my-account' ) ); ?>">

					<label for="email">البريد الالكتروني</label>
					<input type="email" id="email" name="email" required />
					<label for="password">كلمة المرور</label>
					<input type="password" id="password" name="password" required />

					<div class="y-u-d-flex y-u-justify-between y-u-align-items-center y-u-mb-4">
						<label class="checkbox y-u-d-flex y-u-align-items-center">
							<input type="checkbox" name="remember" value="1" />
							<span class="checkmark"></span>
							<span>تذكرني</span>
						</label>
						<a href="<?php echo esc_url( dark_theme_get_page_url( 'forget-password' ) ); ?>" class="y-t-text-decoration-none">نسيت كلمة المرور؟</a>
					</div>
					<button type="submit" class="btn-auth">تسجيل دخول</button>
					<p class="text y-u-text-center">
						ليس لديك حساب؟
						<a href="<?php echo esc_url( dark_theme_get_page_url( 'signup' ) ); ?>" class="y-t-text-decoration-none">انشاء حساب</a>
					</p>
				</form>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
