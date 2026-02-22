<?php
/**
 * Beauty Care Theme — رابط لتحرير صفحة سياسة الاسترجاع.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beauty_care_register_policy_return_admin() {
	add_submenu_page(
		'beauty-care-content',
		'سياسة الاسترجاع',
		'سياسة الاسترجاع',
		'edit_pages',
		'beauty-care-policy-return',
		'beauty_care_redirect_to_policy_page'
	);
}
add_action( 'admin_menu', 'beauty_care_register_policy_return_admin', 17 );
