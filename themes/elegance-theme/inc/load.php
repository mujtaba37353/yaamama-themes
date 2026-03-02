<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$elegance_inc_dir = get_template_directory() . '/inc';

require_once $elegance_inc_dir . '/helpers.php';
require_once $elegance_inc_dir . '/ydash-bridge.php';

if ( ! is_admin() ) {
	require_once $elegance_inc_dir . '/auth-handlers.php';
}

if ( is_admin() ) {
	require_once $elegance_inc_dir . '/admin-pages.php';
	require_once $elegance_inc_dir . '/admin-menu.php';
	require_once $elegance_inc_dir . '/content-pages.php';
	require_once $elegance_inc_dir . '/demo-products.php';
	require_once $elegance_inc_dir . '/home-settings.php';
	require_once $elegance_inc_dir . '/about-settings.php';
	require_once $elegance_inc_dir . '/policy-settings.php';
	require_once $elegance_inc_dir . '/contact-settings.php';
	require_once $elegance_inc_dir . '/footer-settings.php';
	require_once $elegance_inc_dir . '/theme-settings.php';
}
