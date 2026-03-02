<?php
/**
 * Elegance Theme - Yaamama Dashboard Bridge
 *
 * Maps elegance_* options to the unified dashboard format.
 *
 * @package Elegance
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'elegance-theme',
		'theme_name'  => 'Elegance',
		'store_type'  => 'retail',
		'version'     => ELEGANCE_THEME_VERSION,
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
			array( 'id' => 'header_color',       'label' => 'لون الهيدر',          'default' => '#ffffff' ),
			array( 'id' => 'footer_color',       'label' => 'لون الفوتر',          'default' => '#1f2937' ),
			array( 'id' => 'btn_cart_color',      'label' => 'زر إضافة للسلة',     'default' => '#10b981' ),
			array( 'id' => 'btn_checkout_color',  'label' => 'زر الدفع',           'default' => '#10b981' ),
			array( 'id' => 'btn_payment_color',   'label' => 'زر الدفع النهائي',   'default' => '#10b981' ),
			array( 'id' => 'page_bg_color',       'label' => 'خلفية الصفحة',       'default' => '#F3F4F6' ),
		),
		'homepage_sections' => array( 'hero', 'banners' ),
	);
});

// ── Homepage ──

add_filter( 'ydash_get_homepage_settings', function () {
	$g = 'elegance_get_option';
	return array(
		'hero' => array(
			'slides' => array(
				array( 'image' => $g( 'home_hero_image_1', 0 ) ),
				array( 'image' => $g( 'home_hero_image_2', 0 ) ),
				array( 'image' => $g( 'home_hero_image_3', 0 ) ),
			),
			'title'      => $g( 'home_hero_title', '' ),
			'desc'       => $g( 'home_hero_desc', '' ),
			'btn_text'   => $g( 'home_hero_btn_text', '' ),
			'btn_url'    => $g( 'home_hero_btn_url', '' ),
		),
		'banners' => array(
			'main'  => $g( 'home_banner_1_text', '' ),
			'main2' => $g( 'home_banner_2_text', '' ),
		),
	);
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	$hero = $d['hero'] ?? array();
	$slides = $hero['slides'] ?? array();
	for ( $i = 0; $i < 3; $i++ ) {
		$val = $slides[ $i ]['image'] ?? 0;
		update_option( 'elegance_home_hero_image_' . ( $i + 1 ), $val );
	}
	if ( isset( $hero['title'] ) )    update_option( 'elegance_home_hero_title', $hero['title'] );
	if ( isset( $hero['desc'] ) )     update_option( 'elegance_home_hero_desc', $hero['desc'] );
	if ( isset( $hero['btn_text'] ) ) update_option( 'elegance_home_hero_btn_text', $hero['btn_text'] );
	if ( isset( $hero['btn_url'] ) )  update_option( 'elegance_home_hero_btn_url', $hero['btn_url'] );

	$banners = $d['banners'] ?? array();
	if ( isset( $banners['main'] ) )  update_option( 'elegance_home_banner_1_text', $banners['main'] );
	if ( isset( $banners['main2'] ) ) update_option( 'elegance_home_banner_2_text', $banners['main2'] );
});

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	$g = 'elegance_get_option';
	return array(
		'phone'    => $g( 'contact_phones', '' ),
		'email'    => $g( 'contact_display_email', '' ),
		'whatsapp' => $g( 'footer_whatsapp', '' ),
		'address'  => $g( 'contact_address', '' ),
		'social'   => array(
			'facebook'  => $g( 'contact_facebook_url', '' ),
			'instagram' => $g( 'contact_instagram_url', '' ),
			'twitter'   => $g( 'contact_twitter_url', '' ),
		),
		'mail' => array(
			'mailer_type' => $g( 'contact_mail_type', 'gmail' ),
			'gmail'       => array(
				'email'        => $g( 'contact_gmail_email', '' ),
				'app_password' => $g( 'contact_gmail_app_password', '' ),
			),
			'smtp' => array(
				'host'       => $g( 'contact_smtp_host', '' ),
				'port'       => $g( 'contact_smtp_port', '587' ),
				'username'   => $g( 'contact_smtp_user', '' ),
				'password'   => $g( 'contact_smtp_pass', '' ),
				'encryption' => $g( 'contact_smtp_encryption', 'tls' ),
			),
		),
		'floating' => array(
			'whatsapp' => $g( 'footer_floating_whatsapp', '' ),
			'call'     => $g( 'footer_floating_contact', '' ),
		),
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	$map = array(
		'phone'   => 'elegance_contact_phones',
		'email'   => 'elegance_contact_display_email',
		'address' => 'elegance_contact_address',
	);
	foreach ( $map as $k => $opt ) {
		if ( isset( $d[ $k ] ) ) update_option( $opt, sanitize_text_field( $d[ $k ] ) );
	}
	if ( isset( $d['whatsapp'] ) ) update_option( 'elegance_footer_whatsapp', sanitize_text_field( $d['whatsapp'] ) );

	$social = $d['social'] ?? array();
	$social_map = array(
		'facebook'  => 'elegance_contact_facebook_url',
		'instagram' => 'elegance_contact_instagram_url',
		'twitter'   => 'elegance_contact_twitter_url',
	);
	foreach ( $social_map as $k => $opt ) {
		if ( isset( $social[ $k ] ) ) update_option( $opt, esc_url_raw( $social[ $k ] ) );
	}

	$mail = $d['mail'] ?? array();
	if ( isset( $mail['mailer_type'] ) ) update_option( 'elegance_contact_mail_type', $mail['mailer_type'] );
	$gmail = $mail['gmail'] ?? array();
	if ( isset( $gmail['email'] ) )        update_option( 'elegance_contact_gmail_email', $gmail['email'] );
	if ( isset( $gmail['app_password'] ) ) update_option( 'elegance_contact_gmail_app_password', $gmail['app_password'] );
	$smtp = $mail['smtp'] ?? array();
	$smtp_map = array(
		'host' => 'elegance_contact_smtp_host', 'port' => 'elegance_contact_smtp_port',
		'username' => 'elegance_contact_smtp_user', 'password' => 'elegance_contact_smtp_pass',
		'encryption' => 'elegance_contact_smtp_encryption',
	);
	foreach ( $smtp_map as $k => $opt ) {
		if ( isset( $smtp[ $k ] ) ) update_option( $opt, sanitize_text_field( $smtp[ $k ] ) );
	}

	$floating = $d['floating'] ?? array();
	if ( isset( $floating['whatsapp'] ) ) update_option( 'elegance_footer_floating_whatsapp', $floating['whatsapp'] );
	if ( isset( $floating['call'] ) )     update_option( 'elegance_footer_floating_contact', $floating['call'] );
});

// ── Colors ──

add_filter( 'ydash_get_color_settings', function () {
	$keys = array( 'header_color', 'footer_color', 'btn_cart_color', 'btn_checkout_color', 'btn_payment_color', 'page_bg_color' );
	$out = array();
	foreach ( $keys as $k ) {
		$out[ $k ] = get_theme_mod( 'elegance_' . $k, '' );
	}
	return $out;
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	foreach ( $d as $k => $v ) {
		set_theme_mod( 'elegance_' . $k, sanitize_hex_color( $v ) );
	}
});

// ── Logo ──

add_filter( 'ydash_get_logo', function () {
	$id = (int) elegance_get_option( 'footer_logo', 0 );
	return $id ? wp_get_attachment_url( $id ) : '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	update_option( 'elegance_footer_logo', (int) $attachment_id );
	return true;
}, 10, 2 );

// ── Hide theme admin pages when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_menu_page( 'elegance-content' );
}, 999 );
