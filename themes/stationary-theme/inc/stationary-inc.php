<?php
/**
 * stationary-theme — Inc loader.
 *
 * @package stationary-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stationary_get_option( $key, $default = '' ) {
	return get_option( 'stationary_' . $key, $default );
}

function stationary_get_theme_mod( $key, $default = '' ) {
	return get_theme_mod( 'stationary_' . $key, $default );
}

require_once get_template_directory() . '/inc/ydash-bridge.php';

require_once get_template_directory() . '/inc/auth.php';

if ( is_admin() ) {
	require_once get_template_directory() . '/inc/admin-pages.php';
	require_once get_template_directory() . '/inc/admin-menu.php';
	require_once get_template_directory() . '/inc/admin-demo-products.php';
	require_once get_template_directory() . '/inc/admin-content-pages.php';
	require_once get_template_directory() . '/inc/admin-home.php';
	require_once get_template_directory() . '/inc/admin-about.php';
	require_once get_template_directory() . '/inc/admin-policy-settings.php';
	require_once get_template_directory() . '/inc/admin-contact.php';
	require_once get_template_directory() . '/inc/admin-footer.php';
	require_once get_template_directory() . '/inc/admin-theme-settings.php';
}
