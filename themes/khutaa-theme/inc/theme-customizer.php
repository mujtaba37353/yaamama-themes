<?php
/**
 * Theme Customizer for Demo Content
 *
 * @package KhutaaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Demo Content settings to Theme Customizer
 */
function khutaa_demo_content_customize_register( $wp_customize ) {
	// Load custom control class if not already loaded
	if ( ! class_exists( 'Khutaa_Reset_Demo_Control' ) ) {
		require_once get_template_directory() . '/inc/class-reset-demo-control.php';
	}
	// Add Demo Content Panel
	$wp_customize->add_panel( 'khutaa_demo_content', array(
		'title'       => __( 'المحتوى الديمو', 'khutaa-theme' ),
		'description' => __( 'إعدادات المحتوى الديمو للثيم', 'khutaa-theme' ),
		'priority'    => 30,
	) );

	// ===== Contact Information Section =====
	$wp_customize->add_section( 'khutaa_contact_info', array(
		'title'    => __( 'معلومات التواصل', 'khutaa-theme' ),
		'panel'    => 'khutaa_demo_content',
		'priority' => 10,
	) );

	// Address
	$wp_customize->add_setting( 'khutaa_address', array(
		'default'           => 'الرياض - المملكة العربية السعودية',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'khutaa_address', array(
		'label'    => __( 'العنوان', 'khutaa-theme' ),
		'section'  => 'khutaa_contact_info',
		'type'     => 'text',
	) );

	// Phone
	$wp_customize->add_setting( 'khutaa_phone', array(
		'default'           => '059688929 - 058493948',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'khutaa_phone', array(
		'label'    => __( 'رقم الهاتف', 'khutaa-theme' ),
		'section'  => 'khutaa_contact_info',
		'type'     => 'text',
	) );

	// Email
	$wp_customize->add_setting( 'khutaa_email', array(
		'default'           => 'info@super.ksa.com',
		'sanitize_callback' => 'sanitize_email',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'khutaa_email', array(
		'label'    => __( 'البريد الإلكتروني', 'khutaa-theme' ),
		'section'  => 'khutaa_contact_info',
		'type'     => 'email',
	) );

	// ===== Hero Section =====
	$wp_customize->add_section( 'khutaa_hero_section', array(
		'title'    => __( 'قسم الهيرو', 'khutaa-theme' ),
		'panel'    => 'khutaa_demo_content',
		'priority' => 20,
	) );

	// Hero Image
	$wp_customize->add_setting( 'khutaa_hero_image', array(
		'default'           => get_template_directory_uri() . '/khutaa/assets/hero.jpg',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'khutaa_hero_image', array(
		'label'    => __( 'صورة الهيرو', 'khutaa-theme' ),
		'section'  => 'khutaa_hero_section',
		'settings' => 'khutaa_hero_image',
	) ) );

	// Hero Title
	$wp_customize->add_setting( 'khutaa_hero_title', array(
		'default'           => __( 'مرحباً بك في خطى للأحذية', 'khutaa-theme' ),
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'khutaa_hero_title', array(
		'label'    => __( 'عنوان الهيرو', 'khutaa-theme' ),
		'section'  => 'khutaa_hero_section',
		'type'     => 'text',
	) );

	// Hero Content
	$wp_customize->add_setting( 'khutaa_hero_content', array(
		'default'           => __( 'اكتشف مجموعتنا المميزة من الأحذية والشنط', 'khutaa-theme' ),
		'sanitize_callback' => 'sanitize_textarea_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'khutaa_hero_content', array(
		'label'    => __( 'محتوى الهيرو', 'khutaa-theme' ),
		'section'  => 'khutaa_hero_section',
		'type'     => 'textarea',
	) );

	// ===== Banners Section =====
	$wp_customize->add_section( 'khutaa_banners', array(
		'title'    => __( 'البنرات', 'khutaa-theme' ),
		'panel'    => 'khutaa_demo_content',
		'priority' => 30,
	) );

	// Banner 1
	$wp_customize->add_setting( 'khutaa_banner_1_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'khutaa_banner_1_image', array(
		'label'    => __( 'صورة البنر الأول', 'khutaa-theme' ),
		'section'  => 'khutaa_banners',
		'settings' => 'khutaa_banner_1_image',
	) ) );

	$wp_customize->add_setting( 'khutaa_banner_1_title', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'khutaa_banner_1_title', array(
		'label'    => __( 'عنوان البنر الأول', 'khutaa-theme' ),
		'section'  => 'khutaa_banners',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'khutaa_banner_1_link', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'khutaa_banner_1_link', array(
		'label'    => __( 'رابط البنر الأول', 'khutaa-theme' ),
		'section'  => 'khutaa_banners',
		'type'     => 'url',
	) );

	// Banner 2
	$wp_customize->add_setting( 'khutaa_banner_2_image', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'khutaa_banner_2_image', array(
		'label'    => __( 'صورة البنر الثاني', 'khutaa-theme' ),
		'section'  => 'khutaa_banners',
		'settings' => 'khutaa_banner_2_image',
	) ) );

	$wp_customize->add_setting( 'khutaa_banner_2_title', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'khutaa_banner_2_title', array(
		'label'    => __( 'عنوان البنر الثاني', 'khutaa-theme' ),
		'section'  => 'khutaa_banners',
		'type'     => 'text',
	) );

	$wp_customize->add_setting( 'khutaa_banner_2_link', array(
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'khutaa_banner_2_link', array(
		'label'    => __( 'رابط البنر الثاني', 'khutaa-theme' ),
		'section'  => 'khutaa_banners',
		'type'     => 'url',
	) );

	// ===== Reset Demo Content Button =====
	$wp_customize->add_section( 'khutaa_reset_demo', array(
		'title'    => __( 'إعادة تعيين المحتوى الديمو', 'khutaa-theme' ),
		'panel'    => 'khutaa_demo_content',
		'priority' => 100,
	) );

	$wp_customize->add_setting( 'khutaa_reset_demo_content', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Khutaa_Reset_Demo_Control( $wp_customize, 'khutaa_reset_demo_content', array(
		'section' => 'khutaa_reset_demo',
	) ) );
}
add_action( 'customize_register', 'khutaa_demo_content_customize_register' );

/**
 * AJAX handler for resetting demo content
 */
function khutaa_reset_demo_content_ajax() {
	check_ajax_referer( 'khutaa_reset_demo_content', 'nonce' );

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'غير مصرح لك بهذا الإجراء', 'khutaa-theme' ) ) );
	}

	// Reset all demo content settings to defaults
	$defaults = khutaa_get_default_demo_content();

	foreach ( $defaults as $key => $value ) {
		set_theme_mod( $key, $value );
	}

	wp_send_json_success( array( 'message' => __( 'تم إعادة التعيين بنجاح', 'khutaa-theme' ) ) );
}
add_action( 'wp_ajax_khutaa_reset_demo_content', 'khutaa_reset_demo_content_ajax' );

/**
 * Get default demo content values
 */
function khutaa_get_default_demo_content() {
	$theme_uri = get_template_directory_uri();
	return array(
		'khutaa_address'          => 'الرياض - المملكة العربية السعودية',
		'khutaa_phone'            => '059688929 - 058493948',
		'khutaa_email'            => 'info@super.ksa.com',
		'khutaa_hero_image'       => $theme_uri . '/khutaa/assets/header sec.png',
		'khutaa_hero_title'       => 'خطى للأحذية',
		'khutaa_hero_content'     => 'هنالك العديد من الأنواع المتوفرة لنصوص لوريم إيبسوم، ولكن الغالبية تم تعديلها بشكل ما عبر إدخال بعض النوادر أو الكلمات العشوائية إلى النص. إن كنت تريد أن تستخدم نص لوريم إيبسوم ما',
		'khutaa_banner_1_image'   => $theme_uri . '/khutaa/assets/design.png',
		'khutaa_banner_1_title'   => '',
		'khutaa_banner_1_link'    => '',
		'khutaa_banner_2_image'   => $theme_uri . '/khutaa/assets/design.png',
		'khutaa_banner_2_title'   => '',
		'khutaa_banner_2_link'    => '',
		// Demo Products - Shoes
		'khutaa_shoes_1_image'    => $theme_uri . '/khutaa/assets/shoes1.png',
		'khutaa_shoes_1_title'    => 'صندل كعب',
		'khutaa_shoes_1_price'    => '54.00',
		'khutaa_shoes_2_image'    => $theme_uri . '/khutaa/assets/shoes2.png',
		'khutaa_shoes_2_title'    => 'كوتشي رياضي',
		'khutaa_shoes_2_price'    => '54.00',
		'khutaa_shoes_3_image'    => $theme_uri . '/khutaa/assets/shoes3.png',
		'khutaa_shoes_3_title'    => 'هاف بوت',
		'khutaa_shoes_3_price'    => '54.00',
		'khutaa_shoes_4_image'    => $theme_uri . '/khutaa/assets/shoes1.png',
		'khutaa_shoes_4_title'    => 'صندل كعب نسخة',
		'khutaa_shoes_4_price'    => '49.00',
		'khutaa_shoes_5_image'    => $theme_uri . '/khutaa/assets/shoes2.png',
		'khutaa_shoes_5_title'    => 'كوتشي رياضي نسخة',
		'khutaa_shoes_5_price'    => '59.00',
		'khutaa_shoes_6_image'    => $theme_uri . '/khutaa/assets/shoes3.png',
		'khutaa_shoes_6_title'    => 'هاف بوت نسخة',
		'khutaa_shoes_6_price'    => '64.00',
		// Demo Products - Bags
		'khutaa_bags_1_image'     => $theme_uri . '/khutaa/assets/bag1.png',
		'khutaa_bags_1_title'     => 'صندل كعب',
		'khutaa_bags_1_price'     => '54.00',
		'khutaa_bags_2_image'     => $theme_uri . '/khutaa/assets/bag2.png',
		'khutaa_bags_2_title'     => 'كوتشي رياضي',
		'khutaa_bags_2_price'     => '54.00',
		'khutaa_bags_3_image'     => $theme_uri . '/khutaa/assets/bag3.png',
		'khutaa_bags_3_title'     => 'هاف بوت',
		'khutaa_bags_3_price'     => '54.00',
		'khutaa_bags_4_image'     => $theme_uri . '/khutaa/assets/bag1.png',
		'khutaa_bags_4_title'     => 'صندل كعب نسخة',
		'khutaa_bags_4_price'     => '49.00',
		'khutaa_bags_5_image'     => $theme_uri . '/khutaa/assets/bag2.png',
		'khutaa_bags_5_title'     => 'كوتشي رياضي نسخة',
		'khutaa_bags_5_price'     => '59.00',
		'khutaa_bags_6_image'     => $theme_uri . '/khutaa/assets/bag3.png',
		'khutaa_bags_6_title'     => 'هاف بوت نسخة',
		'khutaa_bags_6_price'     => '64.00',
	);
}

/**
 * Helper function to get demo content setting with default fallback
 */
function khutaa_get_demo_content( $setting, $default = '' ) {
	$defaults = khutaa_get_default_demo_content();
	$default_value = isset( $defaults[ $setting ] ) ? $defaults[ $setting ] : $default;
	return get_theme_mod( $setting, $default_value );
}
