<?php
/**
 * Stationary Theme - Yaamama Dashboard Bridge
 *
 * Maps stationary_* options to the unified dashboard format.
 * Uses stationary_get_option() for options and get_theme_mod('stationary_*') for theme mods.
 *
 * @package Stationary
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'stationary-theme',
		'theme_name'  => 'Stationary',
		'store_type'  => 'retail',
		'version'     => defined( 'STATIONARY_VERSION' ) ? STATIONARY_VERSION : '1.0.0',
		'supports'    => array(
			'homepage_settings' => true,
			'contact_settings'  => true,
			'about_page'        => true,
			'privacy_page'      => true,
			'terms_page'        => true,
			'colors'            => true,
			'logo'              => true,
		),
		'color_fields' => array(
			array( 'id' => 'header_color',       'label' => 'لون الهيدر',          'default' => '#6D28D9' ),
			array( 'id' => 'footer_color',       'label' => 'لون الفوتر',          'default' => '#6D28D9' ),
			array( 'id' => 'btn_cart_color',      'label' => 'زر إضافة للسلة',     'default' => '#FACC15' ),
			array( 'id' => 'btn_checkout_color',  'label' => 'زر الدفع',           'default' => '#FACC15' ),
			array( 'id' => 'btn_payment_color',   'label' => 'زر الدفع النهائي',   'default' => '#FACC15' ),
			array( 'id' => 'page_bg_color',       'label' => 'خلفية الصفحة',       'default' => '#F3F4F6' ),
		),
		'homepage_sections' => array( 'hero', 'banners' ),
	);
});

// ── Homepage ──

add_filter( 'ydash_get_homepage_settings', function () {
	$g = 'stationary_get_option';
	return array(
		'hero' => array(
			'slides' => array(
				array( 'image' => (int) $g( 'home_hero_image_1', 0 ) ),
				array( 'image' => (int) $g( 'home_hero_image_2', 0 ) ),
			),
			'title'      => $g( 'home_hero_title_1', '' ),
			'title2'     => $g( 'home_hero_title_2', '' ),
			'desc'       => '',
			'btn_text'   => '',
			'btn_url'    => '',
		),
		'banners' => array(
			'main'  => $g( 'home_panner_text_1', '' ),
			'main2' => $g( 'home_panner_text_2', '' ),
			'image' => (int) $g( 'home_panner_image', 0 ),
		),
	);
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	$hero = $d['hero'] ?? array();
	$slides = $hero['slides'] ?? array();
	if ( isset( $slides[0]['image'] ) ) {
		update_option( 'stationary_home_hero_image_1', (int) $slides[0]['image'] );
	}
	if ( isset( $slides[1]['image'] ) ) {
		update_option( 'stationary_home_hero_image_2', (int) $slides[1]['image'] );
	}
	if ( isset( $hero['title'] ) ) {
		update_option( 'stationary_home_hero_title_1', sanitize_text_field( $hero['title'] ) );
	}
	if ( isset( $hero['title2'] ) ) {
		update_option( 'stationary_home_hero_title_2', sanitize_text_field( $hero['title2'] ) );
	}

	$banners = $d['banners'] ?? array();
	if ( isset( $banners['main'] ) ) {
		update_option( 'stationary_home_panner_text_1', sanitize_text_field( $banners['main'] ) );
	}
	if ( isset( $banners['main2'] ) ) {
		update_option( 'stationary_home_panner_text_2', sanitize_text_field( $banners['main2'] ) );
	}
	if ( isset( $banners['image'] ) ) {
		update_option( 'stationary_home_panner_image', (int) $banners['image'] );
	}
} );

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	$g = 'stationary_get_option';
	$content = $g( 'contact_content', '' );
	return array(
		'phone'    => $g( 'footer_phone', '' ),
		'email'    => $g( 'footer_email', '' ),
		'whatsapp' => $g( 'footer_whatsapp', '' ),
		'address'  => $content,
		'social'   => array(
			'facebook'  => '',
			'instagram' => '',
			'twitter'   => '',
		),
		'content' => $content,
		'banner_image' => (int) $g( 'contact_banner_image', 0 ),
		'content_image' => (int) $g( 'contact_content_image', 0 ),
		'mail' => array(
			'mailer_type' => $g( 'contact_smtp_type', 'gmail' ),
			'gmail'       => array(
				'email'        => $g( 'contact_smtp_user', '' ),
				'app_password' => $g( 'contact_smtp_pass', '' ),
			),
			'smtp' => array(
				'host'       => $g( 'contact_smtp_host', '' ),
				'port'       => $g( 'contact_smtp_port', '587' ),
				'username'   => $g( 'contact_smtp_user', '' ),
				'password'   => $g( 'contact_smtp_pass', '' ),
				'encryption' => 'tls',
			),
		),
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	if ( isset( $d['phone'] ) ) {
		update_option( 'stationary_footer_phone', sanitize_text_field( $d['phone'] ) );
	}
	if ( isset( $d['email'] ) ) {
		update_option( 'stationary_footer_email', sanitize_email( $d['email'] ) );
	}
	if ( isset( $d['whatsapp'] ) ) {
		update_option( 'stationary_footer_whatsapp', sanitize_text_field( $d['whatsapp'] ) );
	}
	if ( isset( $d['address'] ) ) {
		update_option( 'stationary_contact_content', wp_kses_post( $d['address'] ) );
	}
	if ( isset( $d['content'] ) ) {
		update_option( 'stationary_contact_content', wp_kses_post( $d['content'] ) );
	}
	if ( isset( $d['banner_image'] ) ) {
		update_option( 'stationary_contact_banner_image', (int) $d['banner_image'] );
	}
	if ( isset( $d['content_image'] ) ) {
		update_option( 'stationary_contact_content_image', (int) $d['content_image'] );
	}

	$mail = $d['mail'] ?? array();
	if ( isset( $mail['mailer_type'] ) ) {
		update_option( 'stationary_contact_smtp_type', sanitize_text_field( $mail['mailer_type'] ) );
	}
	$gmail = $mail['gmail'] ?? array();
	if ( isset( $gmail['email'] ) ) {
		update_option( 'stationary_contact_smtp_user', sanitize_email( $gmail['email'] ) );
	}
	if ( isset( $gmail['app_password'] ) ) {
		update_option( 'stationary_contact_smtp_pass', sanitize_text_field( $gmail['app_password'] ) );
	}
	$smtp = $mail['smtp'] ?? array();
	if ( isset( $smtp['host'] ) ) {
		update_option( 'stationary_contact_smtp_host', sanitize_text_field( $smtp['host'] ) );
	}
	if ( isset( $smtp['port'] ) ) {
		update_option( 'stationary_contact_smtp_port', absint( $smtp['port'] ) ?: 587 );
	}
	if ( isset( $smtp['username'] ) ) {
		update_option( 'stationary_contact_smtp_user', sanitize_text_field( $smtp['username'] ) );
	}
	if ( isset( $smtp['password'] ) && '' !== $smtp['password'] ) {
		update_option( 'stationary_contact_smtp_pass', sanitize_text_field( $smtp['password'] ) );
	}
} );

// ── Colors ──

add_filter( 'ydash_get_color_settings', function () {
	$keys = array( 'header_color', 'footer_color', 'btn_cart_color', 'btn_checkout_color', 'btn_payment_color', 'page_bg_color' );
	$out = array();
	foreach ( $keys as $k ) {
		$out[ $k ] = function_exists( 'stationary_get_theme_mod' ) ? stationary_get_theme_mod( $k, '' ) : get_theme_mod( 'stationary_' . $k, '' );
	}
	return $out;
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	foreach ( $d as $k => $v ) {
		set_theme_mod( 'stationary_' . $k, sanitize_hex_color( $v ) );
	}
} );

// ── Logo ──

add_filter( 'ydash_get_logo', function () {
	$id = (int) ( function_exists( 'stationary_get_option' ) ? stationary_get_option( 'footer_header_logo', 0 ) : get_option( 'stationary_footer_header_logo', 0 ) );
	return $id ? wp_get_attachment_url( $id ) : '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	update_option( 'stationary_footer_header_logo', (int) $attachment_id );
	return true;
}, 10, 2 );

// ── Hide theme admin pages when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_menu_page( 'stationary-content' );
}, 999 );
