<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: admin-pages — تعريف عناوين الصفحات والـ capability وربط الـ callbacks.

if ( ! function_exists( 'elegance_admin_render_content_pages' ) ) {
	function elegance_admin_render_content_pages() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'elegance' ) );
		}
		$title = __( 'الصفحات', 'elegance' );
		elegance_admin_render_wrapper( $title, 'elegance_content_pages' );
	}
}

if ( ! function_exists( 'elegance_admin_render_demo_products' ) ) {
	function elegance_admin_render_demo_products() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'elegance' ) );
		}
		$title = __( 'منتجات ديمو', 'elegance' );
		elegance_admin_render_wrapper( $title, 'elegance_demo_products' );
	}
}

if ( ! function_exists( 'elegance_admin_render_home' ) ) {
	function elegance_admin_render_home() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'elegance' ) );
		}
		$title = __( 'الصفحة الرئيسية', 'elegance' );
		elegance_admin_render_wrapper( $title, 'elegance_home_settings' );
	}
}

if ( ! function_exists( 'elegance_admin_render_about' ) ) {
	function elegance_admin_render_about() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'elegance' ) );
		}
		$title = __( 'من نحن', 'elegance' );
		elegance_admin_render_wrapper( $title, 'elegance_about_settings' );
	}
}

if ( ! function_exists( 'elegance_admin_render_shipping_policy' ) ) {
	function elegance_admin_render_shipping_policy() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'elegance' ) );
		}
		$title = __( 'سياسة الشحن', 'elegance' );
		elegance_admin_render_wrapper( $title, 'elegance_shipping_policy' );
	}
}

if ( ! function_exists( 'elegance_admin_render_return_policy' ) ) {
	function elegance_admin_render_return_policy() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'elegance' ) );
		}
		$title = __( 'سياسة الاسترجاع', 'elegance' );
		elegance_admin_render_wrapper( $title, 'elegance_return_policy' );
	}
}

if ( ! function_exists( 'elegance_admin_render_privacy_policy' ) ) {
	function elegance_admin_render_privacy_policy() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'elegance' ) );
		}
		$title = __( 'سياسة الخصوصية', 'elegance' );
		elegance_admin_render_wrapper( $title, 'elegance_privacy_policy' );
	}
}

if ( ! function_exists( 'elegance_admin_render_contact' ) ) {
	function elegance_admin_render_contact() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'elegance' ) );
		}
		$title = __( 'تواصل معنا', 'elegance' );
		elegance_admin_render_wrapper( $title, 'elegance_contact_settings' );
	}
}

if ( ! function_exists( 'elegance_admin_render_footer' ) ) {
	function elegance_admin_render_footer() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'elegance' ) );
		}
		$title = __( 'الفوتر', 'elegance' );
		elegance_admin_render_wrapper( $title, 'elegance_footer_settings' );
	}
}

if ( ! function_exists( 'elegance_admin_render_theme_settings' ) ) {
	function elegance_admin_render_theme_settings() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions.', 'elegance' ) );
		}
		$title = __( 'إعدادات الموقع', 'elegance' );
		elegance_admin_render_wrapper( $title, 'elegance_theme_settings' );
	}
}

/**
 * Wrapper: output admin page with title and nonce; content via action.
 *
 * @param string $title   Page title.
 * @param string $action  Action name for content (each settings file hooks here).
 */
function elegance_admin_render_wrapper( $title, $action ) {
	?>
	<div class="wrap">
		<h1><?php echo esc_html( $title ); ?></h1>
		<?php wp_nonce_field( 'elegance_admin_' . $action, 'elegance_admin_nonce' ); ?>
		<?php do_action( 'elegance_admin_render_' . $action ); ?>
	</div>
	<?php
}
