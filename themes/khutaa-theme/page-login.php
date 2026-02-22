<?php
/**
 * Template Name: تسجيل الدخول
 * Template for login page
 *
 * @package KhutaaTheme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Redirect if already logged in
if ( is_user_logged_in() ) {
	wp_redirect( wc_get_page_permalink( 'myaccount' ) );
	exit;
}

get_header();

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';

// Enqueue auth-specific styles
wp_enqueue_style( 'khutaa-auth', $khutaa_uri . '/components/auth/y-c-auth.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-auth-btn', $khutaa_uri . '/components/buttons/y-c-auth-btn.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-design-header', $khutaa_uri . '/templates/pages header/y-c-design-header.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-breadcrumb', $khutaa_uri . '/components/layout/y-c-breadcrumb.css', array(), '1.0.0' );

// Enqueue scripts
wp_enqueue_script( 'khutaa-login', $khutaa_uri . '/js/y-login.js', array( 'jquery' ), '1.0.0', true );
wp_enqueue_script( 'khutaa-design-header', $khutaa_uri . '/js/y-design-header.js', array(), '1.0.0', true );
wp_enqueue_script( 'khutaa-breadcrumb', $khutaa_uri . '/js/y-breadcrumb.js', array(), '1.0.0', true );
?>

<main id="main" class="y-u-container">
	<?php
	// Display WooCommerce notices
	if ( function_exists( 'wc_print_notices' ) ) {
		wc_print_notices();
	}
	?>

	<div class="auth-container">
		<div class="img-container">
			<img src="<?php echo esc_url( $khutaa_uri . '/assets/login.png' ); ?>" alt="<?php esc_attr_e( 'تسجيل الدخول', 'khutaa-theme' ); ?>" />
		</div>
		<div class="form">
			<form method="post" class="woocommerce-form woocommerce-form-login login" action="<?php echo esc_url( get_permalink() ); ?>">
				<h1>
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather user">
							<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
							<circle cx="8.5" cy="7" r="4"></circle>
							<line x1="20" y1="8" x2="20" y2="14"></line>
							<line x1="23" y1="11" x2="17" y2="11"></line>
						</svg>
					</span>
					<?php esc_html_e( 'تسجيل الدخول', 'khutaa-theme' ); ?>
				</h1>

				<label for="email"><?php esc_html_e( 'البريد الإلكتروني', 'khutaa-theme' ); ?></label>
				<input type="email" name="log" id="email" value="<?php echo ( ! empty( $_POST['log'] ) ) ? esc_attr( $_POST['log'] ) : ''; ?>" required autocomplete="email" />

				<label for="password"><?php esc_html_e( 'كلمة المرور', 'khutaa-theme' ); ?></label>
				<div class="password-input-wrapper">
					<input type="password" name="pwd" id="password" required autocomplete="current-password" />
					<button type="button" class="toggle-password" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'khutaa-theme' ); ?>">
						<i class="fas fa-eye"></i>
					</button>
				</div>

				<div class="y-u-d-flex y-u-justify-between y-u-align-items-center y-u-mb-4">
					<label class="checkbox y-u-d-flex y-u-align-items-center">
						<input type="checkbox" name="rememberme" id="rememberme" value="forever" />
						<span class="checkmark"></span>
						<span><?php esc_html_e( 'تذكرني', 'khutaa-theme' ); ?></span>
					</label>
					<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'lost-password' ) ) ?: home_url( '/lost-password' ) ); ?>" class="y-t-text-decoration-none"><?php esc_html_e( 'نسيت كلمة المرور؟', 'khutaa-theme' ); ?></a>
				</div>

				<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
				<input type="hidden" name="redirect" value="<?php echo esc_url( $_SERVER['REQUEST_URI'] ); ?>" />
				<button type="submit" name="login" class="btn-auth" value="<?php esc_attr_e( 'تسجيل الدخول', 'khutaa-theme' ); ?>">
					<?php esc_html_e( 'تسجيل الدخول', 'khutaa-theme' ); ?>
				</button>

				<p class="text y-u-text-center">
					<?php esc_html_e( 'ليس لديك حساب؟', 'khutaa-theme' ); ?>
					<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'register' ) ) ?: home_url( '/register' ) ); ?>" class="y-t-text-decoration-none">
						<?php esc_html_e( 'إنشاء حساب', 'khutaa-theme' ); ?>
					</a>
				</p>
			</form>
		</div>
	</div>
</main>

<?php
get_footer();
