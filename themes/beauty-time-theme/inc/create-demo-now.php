<?php
/**
 * Direct function to create demo products - called from admin page
 * This bypasses form submission issues
 */

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'beauty_create_demo_products_now' ) ) {
	function beauty_create_demo_products_now() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return false;
		}

		if ( ! class_exists( 'WooCommerce' ) ) {
			return false;
		}

		require_once get_template_directory() . '/inc/demo-products-admin.php';
		return beauty_demo_create_products();
	}
}
