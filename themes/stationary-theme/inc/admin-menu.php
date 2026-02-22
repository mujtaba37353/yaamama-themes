<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'admin_menu', 'stationary_register_content_menu', 10 );

function stationary_register_content_menu() {
	add_menu_page(
		'المحتوى',
		'المحتوى',
		'manage_options',
		'stationary-content',
		'stationary_admin_render_content_pages',
		'dashicons-admin-generic',
		25
	);

	add_submenu_page(
		'stationary-content',
		'الصفحات',
		'الصفحات',
		'manage_options',
		'stationary-content',
		'stationary_admin_render_content_pages'
	);

	add_submenu_page(
		'stationary-content',
		'منتجات ديمو',
		'منتجات ديمو',
		'manage_options',
		'stationary-demo-products',
		'stationary_admin_render_demo_products'
	);

	add_submenu_page(
		'stationary-content',
		'الصفحة الرئيسية',
		'الصفحة الرئيسية',
		'manage_options',
		'stationary-home',
		'stationary_admin_render_home'
	);

	add_submenu_page(
		'stationary-content',
		'من نحن',
		'من نحن',
		'manage_options',
		'stationary-about',
		'stationary_admin_render_about'
	);

	add_submenu_page(
		'stationary-content',
		'سياسة الشحن',
		'سياسة الشحن',
		'manage_options',
		'stationary-shipping-policy',
		'stationary_admin_render_shipping_policy'
	);

	add_submenu_page(
		'stationary-content',
		'سياسة الاسترجاع',
		'سياسة الاسترجاع',
		'manage_options',
		'stationary-return-policy',
		'stationary_admin_render_return_policy'
	);

	add_submenu_page(
		'stationary-content',
		'سياسة الخصوصية',
		'سياسة الخصوصية',
		'manage_options',
		'stationary-privacy-policy',
		'stationary_admin_render_privacy_policy'
	);

	add_submenu_page(
		'stationary-content',
		'تواصل معنا',
		'تواصل معنا',
		'manage_options',
		'stationary-contact',
		'stationary_admin_render_contact'
	);

	add_submenu_page(
		'stationary-content',
		'الفوتر',
		'الفوتر',
		'manage_options',
		'stationary-footer',
		'stationary_admin_render_footer'
	);

	add_submenu_page(
		'stationary-content',
		'إعدادات الموقع',
		'إعدادات الموقع',
		'manage_options',
		'stationary-theme-settings',
		'stationary_admin_render_theme_settings'
	);
}
