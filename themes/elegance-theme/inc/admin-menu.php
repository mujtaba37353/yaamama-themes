<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: admin-menu — تسجيل قائمة "المحتوى" والـ submenus.

add_action( 'admin_menu', 'elegance_register_content_menu', 10 );

/**
 * Register "المحتوى" top-level menu and all submenu pages.
 */
function elegance_register_content_menu() {
	add_menu_page(
		__( 'المحتوى', 'elegance' ),
		__( 'المحتوى', 'elegance' ),
		'manage_options',
		'elegance-content',
		'elegance_admin_render_content_pages',
		'dashicons-admin-generic',
		25
	);

	add_submenu_page(
		'elegance-content',
		__( 'الصفحات', 'elegance' ),
		__( 'الصفحات', 'elegance' ),
		'manage_options',
		'elegance-content',
		'elegance_admin_render_content_pages'
	);

	add_submenu_page(
		'elegance-content',
		__( 'منتجات ديمو', 'elegance' ),
		__( 'منتجات ديمو', 'elegance' ),
		'manage_options',
		'elegance-demo-products',
		'elegance_admin_render_demo_products'
	);

	add_submenu_page(
		'elegance-content',
		__( 'الصفحة الرئيسية', 'elegance' ),
		__( 'الصفحة الرئيسية', 'elegance' ),
		'manage_options',
		'elegance-home',
		'elegance_admin_render_home'
	);

	add_submenu_page(
		'elegance-content',
		__( 'من نحن', 'elegance' ),
		__( 'من نحن', 'elegance' ),
		'manage_options',
		'elegance-about',
		'elegance_admin_render_about'
	);

	add_submenu_page(
		'elegance-content',
		__( 'سياسة الشحن', 'elegance' ),
		__( 'سياسة الشحن', 'elegance' ),
		'manage_options',
		'elegance-shipping-policy',
		'elegance_admin_render_shipping_policy'
	);

	add_submenu_page(
		'elegance-content',
		__( 'سياسة الاسترجاع', 'elegance' ),
		__( 'سياسة الاسترجاع', 'elegance' ),
		'manage_options',
		'elegance-return-policy',
		'elegance_admin_render_return_policy'
	);

	add_submenu_page(
		'elegance-content',
		__( 'سياسة الخصوصية', 'elegance' ),
		__( 'سياسة الخصوصية', 'elegance' ),
		'manage_options',
		'elegance-privacy-policy',
		'elegance_admin_render_privacy_policy'
	);

	add_submenu_page(
		'elegance-content',
		__( 'تواصل معنا', 'elegance' ),
		__( 'تواصل معنا', 'elegance' ),
		'manage_options',
		'elegance-contact',
		'elegance_admin_render_contact'
	);

	add_submenu_page(
		'elegance-content',
		__( 'الفوتر', 'elegance' ),
		__( 'الفوتر', 'elegance' ),
		'manage_options',
		'elegance-footer',
		'elegance_admin_render_footer'
	);

	add_submenu_page(
		'elegance-content',
		__( 'إعدادات الموقع', 'elegance' ),
		__( 'إعدادات الموقع', 'elegance' ),
		'manage_options',
		'elegance-theme-settings',
		'elegance_admin_render_theme_settings'
	);
}
