<?php
/**
 * Beauty Care Theme — روابط لتحرير صفحات السياسات من لوحة التحكم.
 * المحتوى يُعدّل عبر: الصفحات → تحرير الصفحة.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beauty_care_register_policy_privacy_admin() {
	add_submenu_page(
		'beauty-care-content',
		'سياسة الخصوصية',
		'سياسة الخصوصية',
		'edit_pages',
		'beauty-care-policy-privacy',
		'beauty_care_redirect_to_policy_page'
	);
}
add_action( 'admin_menu', 'beauty_care_register_policy_privacy_admin', 16 );

/**
 * إعادة توجيه لتحرير صفحة السياسة في ووردبريس.
 */
function beauty_care_redirect_to_policy_page() {
	$slug_map = array(
		'beauty-care-policy-privacy'  => 'privacy-policy',
		'beauty-care-policy-return'   => 'return-policy',
		'beauty-care-policy-shipping' => 'shipping-policy',
	);
	$current_page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
	$page_slug    = isset( $slug_map[ $current_page ] ) ? $slug_map[ $current_page ] : null;
	if ( $page_slug ) {
		$page = get_page_by_path( $page_slug );
		if ( $page ) {
			wp_safe_redirect( get_edit_post_link( $page->ID, 'raw' ) );
			exit;
		}
	}
	echo '<div class="wrap"><p>الصفحة غير موجودة. يرجى <a href="' . esc_url( admin_url( 'admin.php?page=beauty-care-pages' ) ) . '">إنشاء الصفحات</a> أولاً.</p></div>';
}
