<?php
/**
 * Template Name: استعادة كلمة المرور
 * Template for lost password page
 *
 * @package KhutaaTheme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
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
wp_enqueue_script( 'khutaa-reset-password', $khutaa_uri . '/js/y-reset-password.js', array( 'jquery' ), '1.0.0', true );
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
			<img src="<?php echo esc_url( $khutaa_uri . '/assets/reset-password.png' ); ?>" alt="<?php esc_attr_e( 'استعادة كلمة المرور', 'khutaa-theme' ); ?>" />
		</div>
		<div class="form">
			<form method="post" class="woocommerce-ResetPassword lost_reset_password" action="<?php echo esc_url( wc_lostpassword_url() ); ?>">
				<h1>
					<span>
						<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather user">
							<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
							<circle cx="8.5" cy="7" r="4"></circle>
							<line x1="20" y1="8" x2="20" y2="14"></line>
							<line x1="23" y1="11" x2="17" y2="11"></line>
						</svg>
					</span>
					<?php esc_html_e( 'استعادة كلمة المرور', 'khutaa-theme' ); ?>
				</h1>

				<p><?php esc_html_e( 'برجاء تسجيل رقم الهاتف المسجل به الحساب وسيصلك كود مكون من 4 أرقام', 'khutaa-theme' ); ?></p>

				<label for="user_login"><?php esc_html_e( 'البريد الإلكتروني أو اسم المستخدم', 'khutaa-theme' ); ?></label>
				<input type="text" name="user_login" id="user_login" value="<?php echo ( ! empty( $_POST['user_login'] ) ) ? esc_attr( $_POST['user_login'] ) : ''; ?>" required autocomplete="username" />

				<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
				<input type="hidden" name="wc_reset_password" value="true" />
				<button type="submit" name="wc_reset_password" class="btn-auth" value="<?php esc_attr_e( 'إعادة تعيين كلمة المرور', 'khutaa-theme' ); ?>">
					<?php esc_html_e( 'التالي', 'khutaa-theme' ); ?>
				</button>

				<p class="text y-u-text-center">
					<a href="<?php echo esc_url( get_permalink( get_page_by_path( 'login' ) ) ?: home_url( '/login' ) ); ?>" class="y-t-text-decoration-none">
						<?php esc_html_e( 'العودة لتسجيل الدخول', 'khutaa-theme' ); ?>
					</a>
				</p>
			</form>
		</div>
	</div>
</main>

<?php
get_footer();
