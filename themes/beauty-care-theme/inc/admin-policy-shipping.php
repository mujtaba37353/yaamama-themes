<?php
/**
 * Beauty Care Theme — رابط لتحرير صفحة سياسة الشحن.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beauty_care_register_policy_shipping_admin() {
	add_submenu_page(
		'beauty-care-content',
		'سياسة الشحن',
		'سياسة الشحن',
		'edit_pages',
		'beauty-care-policy-shipping',
		'beauty_care_redirect_to_policy_page'
	);
}
add_action( 'admin_menu', 'beauty_care_register_policy_shipping_admin', 18 );
