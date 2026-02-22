<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stationary_admin_render_content_pages() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'ليس لديك صلاحية.', 'stationary-theme' ) );
	}
	stationary_admin_render_wrapper( __( 'الصفحات', 'stationary-theme' ), 'stationary_content_pages' );
}

function stationary_admin_render_demo_products() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'ليس لديك صلاحية.', 'stationary-theme' ) );
	}
	stationary_admin_render_wrapper( __( 'منتجات ديمو', 'stationary-theme' ), 'stationary_demo_products' );
}

function stationary_admin_render_home() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'ليس لديك صلاحية.', 'stationary-theme' ) );
	}
	stationary_admin_render_wrapper( __( 'الصفحة الرئيسية', 'stationary-theme' ), 'stationary_home_settings' );
}

function stationary_admin_render_about() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'ليس لديك صلاحية.', 'stationary-theme' ) );
	}
	stationary_admin_render_wrapper( __( 'من نحن', 'stationary-theme' ), 'stationary_about_settings' );
}

function stationary_admin_render_shipping_policy() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'ليس لديك صلاحية.', 'stationary-theme' ) );
	}
	stationary_admin_render_wrapper( __( 'سياسة الشحن', 'stationary-theme' ), 'stationary_shipping_policy' );
}

function stationary_admin_render_return_policy() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'ليس لديك صلاحية.', 'stationary-theme' ) );
	}
	stationary_admin_render_wrapper( __( 'سياسة الاسترجاع', 'stationary-theme' ), 'stationary_return_policy' );
}

function stationary_admin_render_privacy_policy() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'ليس لديك صلاحية.', 'stationary-theme' ) );
	}
	stationary_admin_render_wrapper( __( 'سياسة الخصوصية', 'stationary-theme' ), 'stationary_privacy_policy' );
}

function stationary_admin_render_contact() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'ليس لديك صلاحية.', 'stationary-theme' ) );
	}
	stationary_admin_render_wrapper( __( 'تواصل معنا', 'stationary-theme' ), 'stationary_contact_settings' );
}

function stationary_admin_render_footer() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'ليس لديك صلاحية.', 'stationary-theme' ) );
	}
	stationary_admin_render_wrapper( __( 'الفوتر', 'stationary-theme' ), 'stationary_footer_settings' );
}

function stationary_admin_render_theme_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'ليس لديك صلاحية.', 'stationary-theme' ) );
	}
	stationary_admin_render_wrapper( __( 'إعدادات الموقع', 'stationary-theme' ), 'stationary_theme_settings' );
}

function stationary_admin_render_wrapper( $title, $action ) {
	?>
	<div class="wrap stationary-admin-wrap">
		<h1><?php echo esc_html( $title ); ?></h1>
		<?php do_action( 'stationary_admin_render_' . $action ); ?>
	</div>
	<?php
}
