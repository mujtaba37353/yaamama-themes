<?php
/**
 * Sweet House Theme - Yaamama Dashboard Bridge
 *
 * Maps sweet_house_* array options to the unified dashboard format.
 *
 * @package Sweet_House_Theme
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'sweet-house-theme',
		'theme_name'  => 'Sweet House',
		'store_type'  => 'retail',
		'version'     => defined( 'SWEET_HOUSE_THEME_VERSION' ) ? SWEET_HOUSE_THEME_VERSION : '1.0.0',
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
			array( 'id' => 'color_button', 'label' => 'لون الأزرار', 'default' => '#55C1DF' ),
			array( 'id' => 'color_header', 'label' => 'لون الهيدر', 'default' => '#55C1DF' ),
			array( 'id' => 'color_footer', 'label' => 'لون الفوتر', 'default' => '#55C1DF' ),
		),
		'homepage_sections' => array( 'hero', 'banners', 'categories', 'products', 'about', 'features' ),
	);
});

// ── Homepage ──

add_filter( 'ydash_get_homepage_settings', function () {
	$h = function_exists( 'sweet_house_get_home_content' ) ? sweet_house_get_home_content() : get_option( 'sweet_house_home_content', array() );
	return array(
		'hero' => array(
			'slides' => array(
				array( 'image' => isset( $h['hero_banner_img'] ) ? (int) $h['hero_banner_img'] : 0 ),
			),
			'title'      => isset( $h['hero_title'] ) ? $h['hero_title'] : '',
			'desc'       => trim( ( isset( $h['hero_subtitle1'] ) ? $h['hero_subtitle1'] : '' ) . "\n" . ( isset( $h['hero_subtitle2'] ) ? $h['hero_subtitle2'] : '' ) ),
			'btn_text'   => '',
			'btn_url'    => '',
		),
		'banners' => array(
			'main'  => isset( $h['mid_banner_img'] ) ? (int) $h['mid_banner_img'] : 0,
			'main2' => '',
		),
		'categories' => array(
			'title'    => isset( $h['categories_title'] ) ? $h['categories_title'] : '',
			'subtitle' => isset( $h['categories_subtitle'] ) ? $h['categories_subtitle'] : '',
		),
		'products' => array(
			'title' => isset( $h['products_title'] ) ? $h['products_title'] : '',
		),
		'about' => array(
			'title'    => isset( $h['about_title'] ) ? $h['about_title'] : '',
			'subtitle' => isset( $h['about_subtitle'] ) ? $h['about_subtitle'] : '',
			'text'     => isset( $h['about_text'] ) ? $h['about_text'] : '',
			'img1'     => isset( $h['about_img1'] ) ? (int) $h['about_img1'] : 0,
			'img2'     => isset( $h['about_img2'] ) ? (int) $h['about_img2'] : 0,
		),
		'features' => array(
			array(
				'icon'  => isset( $h['feat1_icon'] ) ? (int) $h['feat1_icon'] : 0,
				'title' => isset( $h['feat1_title'] ) ? $h['feat1_title'] : '',
				'text'  => isset( $h['feat1_text'] ) ? $h['feat1_text'] : '',
			),
			array(
				'icon'  => isset( $h['feat2_icon'] ) ? (int) $h['feat2_icon'] : 0,
				'title' => isset( $h['feat2_title'] ) ? $h['feat2_title'] : '',
				'text'  => isset( $h['feat2_text'] ) ? $h['feat2_text'] : '',
			),
			array(
				'icon'  => isset( $h['feat3_icon'] ) ? (int) $h['feat3_icon'] : 0,
				'title' => isset( $h['feat3_title'] ) ? $h['feat3_title'] : '',
				'text'  => isset( $h['feat3_text'] ) ? $h['feat3_text'] : '',
			),
			array(
				'icon'  => isset( $h['feat4_icon'] ) ? (int) $h['feat4_icon'] : 0,
				'title' => isset( $h['feat4_title'] ) ? $h['feat4_title'] : '',
				'text'  => isset( $h['feat4_text'] ) ? $h['feat4_text'] : '',
			),
		),
	);
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	$opt = get_option( 'sweet_house_home_content', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}

	$hero = $d['hero'] ?? array();
	$slides = $hero['slides'] ?? array();
	if ( ! empty( $slides ) && isset( $slides[0]['image'] ) ) {
		$opt['hero_banner_img'] = (int) $slides[0]['image'];
	}
	if ( isset( $hero['title'] ) ) {
		$opt['hero_title'] = sanitize_text_field( $hero['title'] );
	}
	if ( isset( $hero['desc'] ) ) {
		$parts = preg_split( '/\r?\n/', $hero['desc'], 2 );
		$opt['hero_subtitle1'] = isset( $parts[0] ) ? sanitize_text_field( $parts[0] ) : '';
		$opt['hero_subtitle2'] = isset( $parts[1] ) ? sanitize_text_field( $parts[1] ) : '';
	}

	$banners = $d['banners'] ?? array();
	if ( isset( $banners['main'] ) && is_numeric( $banners['main'] ) ) {
		$opt['mid_banner_img'] = (int) $banners['main'];
	}

	$categories = $d['categories'] ?? array();
	if ( isset( $categories['title'] ) ) {
		$opt['categories_title'] = sanitize_text_field( $categories['title'] );
	}
	if ( isset( $categories['subtitle'] ) ) {
		$opt['categories_subtitle'] = sanitize_text_field( $categories['subtitle'] );
	}

	$products = $d['products'] ?? array();
	if ( isset( $products['title'] ) ) {
		$opt['products_title'] = sanitize_text_field( $products['title'] );
	}

	$about = $d['about'] ?? array();
	if ( isset( $about['title'] ) ) {
		$opt['about_title'] = sanitize_text_field( $about['title'] );
	}
	if ( isset( $about['subtitle'] ) ) {
		$opt['about_subtitle'] = sanitize_text_field( $about['subtitle'] );
	}
	if ( isset( $about['text'] ) ) {
		$opt['about_text'] = sanitize_textarea_field( $about['text'] );
	}
	if ( isset( $about['img1'] ) ) {
		$opt['about_img1'] = (int) $about['img1'];
	}
	if ( isset( $about['img2'] ) ) {
		$opt['about_img2'] = (int) $about['img2'];
	}

	$features = $d['features'] ?? array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$f = $features[ $i - 1 ] ?? array();
		if ( isset( $f['icon'] ) ) {
			$opt[ 'feat' . $i . '_icon' ] = (int) $f['icon'];
		}
		if ( isset( $f['title'] ) ) {
			$opt[ 'feat' . $i . '_title' ] = sanitize_text_field( $f['title'] );
		}
		if ( isset( $f['text'] ) ) {
			$opt[ 'feat' . $i . '_text' ] = sanitize_text_field( $f['text'] );
		}
	}

	update_option( 'sweet_house_home_content', $opt );
});

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	$c = function_exists( 'sweet_house_get_contact_settings' ) ? sweet_house_get_contact_settings() : get_option( 'sweet_house_contact_settings', array() );
	$f = function_exists( 'sweet_house_get_footer_settings' ) ? sweet_house_get_footer_settings() : get_option( 'sweet_house_footer_settings', array() );
	return array(
		'phone'    => isset( $c['contact_phones'] ) ? $c['contact_phones'] : ( isset( $f['footer_phones'] ) ? $f['footer_phones'] : '' ),
		'email'    => isset( $c['contact_email_display'] ) ? $c['contact_email_display'] : ( isset( $f['footer_email'] ) ? $f['footer_email'] : '' ),
		'whatsapp' => isset( $f['whatsapp_number'] ) ? $f['whatsapp_number'] : '',
		'address'  => isset( $c['contact_address'] ) ? $c['contact_address'] : ( isset( $f['footer_address'] ) ? $f['footer_address'] : '' ),
		'social'   => array(
			'facebook'  => '',
			'instagram' => '',
			'twitter'   => '',
		),
		'recipient_email' => isset( $c['recipient_email'] ) ? $c['recipient_email'] : '',
		'mail' => array(
			'mailer_type' => isset( $c['mail_type'] ) ? $c['mail_type'] : 'gmail',
			'gmail'       => array(
				'email'        => isset( $c['google_email'] ) ? $c['google_email'] : '',
				'app_password' => isset( $c['google_app_password'] ) ? $c['google_app_password'] : '',
			),
			'smtp' => array(
				'host'       => isset( $c['smtp_host'] ) ? $c['smtp_host'] : '',
				'port'       => isset( $c['smtp_port'] ) ? $c['smtp_port'] : '587',
				'username'   => isset( $c['smtp_user'] ) ? $c['smtp_user'] : '',
				'password'   => isset( $c['smtp_pass'] ) ? $c['smtp_pass'] : '',
				'encryption' => isset( $c['smtp_encryption'] ) ? $c['smtp_encryption'] : 'tls',
			),
		),
		'floating' => array(
			'whatsapp' => isset( $f['whatsapp_number'] ) ? $f['whatsapp_number'] : '',
			'call'     => isset( $f['phone_number'] ) ? $f['phone_number'] : '',
		),
		'map_embed' => isset( $c['map_embed_url'] ) ? $c['map_embed_url'] : '',
		'visit_hours' => isset( $c['visit_hours'] ) ? $c['visit_hours'] : '',
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	$c = get_option( 'sweet_house_contact_settings', array() );
	$f = get_option( 'sweet_house_footer_settings', array() );
	if ( ! is_array( $c ) ) {
		$c = array();
	}
	if ( ! is_array( $f ) ) {
		$f = array();
	}

	if ( isset( $d['phone'] ) ) {
		$c['contact_phones'] = sanitize_text_field( $d['phone'] );
		$f['footer_phones']  = sanitize_text_field( $d['phone'] );
	}
	if ( isset( $d['email'] ) ) {
		$c['contact_email_display'] = sanitize_email( $d['email'] );
		$f['footer_email']          = sanitize_email( $d['email'] );
	}
	if ( isset( $d['whatsapp'] ) ) {
		$f['whatsapp_number'] = sanitize_text_field( $d['whatsapp'] );
	}
	if ( isset( $d['address'] ) ) {
		$c['contact_address'] = sanitize_text_field( $d['address'] );
		$f['footer_address']   = sanitize_text_field( $d['address'] );
	}

	$floating = $d['floating'] ?? array();
	if ( isset( $floating['whatsapp'] ) ) {
		$f['whatsapp_number'] = sanitize_text_field( $floating['whatsapp'] );
	}
	if ( isset( $floating['call'] ) ) {
		$f['phone_number'] = sanitize_text_field( $floating['call'] );
	}

	$mail = $d['mail'] ?? array();
	if ( isset( $mail['mailer_type'] ) ) {
		$c['mail_type'] = sanitize_text_field( $mail['mailer_type'] );
	}
	$gmail = $mail['gmail'] ?? array();
	if ( isset( $gmail['email'] ) ) {
		$c['google_email'] = sanitize_email( $gmail['email'] );
	}
	if ( isset( $gmail['app_password'] ) ) {
		$c['google_app_password'] = sanitize_text_field( $gmail['app_password'] );
	}
	$smtp = $mail['smtp'] ?? array();
	if ( isset( $smtp['host'] ) ) {
		$c['smtp_host'] = sanitize_text_field( $smtp['host'] );
	}
	if ( isset( $smtp['port'] ) ) {
		$c['smtp_port'] = sanitize_text_field( $smtp['port'] );
	}
	if ( isset( $smtp['username'] ) ) {
		$c['smtp_user'] = sanitize_text_field( $smtp['username'] );
	}
	if ( isset( $smtp['password'] ) ) {
		$c['smtp_pass'] = sanitize_text_field( $smtp['password'] );
	}
	if ( isset( $smtp['encryption'] ) ) {
		$c['smtp_encryption'] = sanitize_text_field( $smtp['encryption'] );
	}
	if ( isset( $d['recipient_email'] ) ) {
		$c['recipient_email'] = sanitize_email( $d['recipient_email'] );
	}
	if ( isset( $smtp['from_email'] ) ) {
		$c['smtp_from_email'] = sanitize_email( $smtp['from_email'] );
	}

	if ( isset( $d['map_embed'] ) ) {
		$c['map_embed_url'] = esc_url_raw( $d['map_embed'] );
	}
	if ( isset( $d['visit_hours'] ) ) {
		$c['visit_hours'] = sanitize_text_field( $d['visit_hours'] );
	}

	update_option( 'sweet_house_contact_settings', $c );
	update_option( 'sweet_house_footer_settings', $f );
});

// ── Colors ──

add_filter( 'ydash_get_color_settings', function () {
	$colors = function_exists( 'sweet_house_get_site_colors' ) ? sweet_house_get_site_colors() : get_option( 'sweet_house_site_colors', array() );
	return array(
		'color_button' => isset( $colors['color_button'] ) ? $colors['color_button'] : '',
		'color_header' => isset( $colors['color_header'] ) ? $colors['color_header'] : '',
		'color_footer' => isset( $colors['color_footer'] ) ? $colors['color_footer'] : '',
	);
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	$opt = get_option( 'sweet_house_site_colors', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}
	$keys = array( 'color_button', 'color_header', 'color_footer' );
	foreach ( $keys as $k ) {
		if ( isset( $d[ $k ] ) && $d[ $k ] !== '' ) {
			$val = sanitize_hex_color( $d[ $k ] );
			if ( $val ) {
				$opt[ $k ] = $val;
			}
		}
	}
	update_option( 'sweet_house_site_colors', $opt );
});

// ── Logo ──

add_filter( 'ydash_get_logo', function () {
	$f = function_exists( 'sweet_house_get_footer_settings' ) ? sweet_house_get_footer_settings() : get_option( 'sweet_house_footer_settings', array() );
	$id = isset( $f['site_logo'] ) ? (int) $f['site_logo'] : 0;
	return $id && wp_attachment_is_image( $id ) ? wp_get_attachment_url( $id ) : '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	$opt = get_option( 'sweet_house_footer_settings', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}
	$opt['site_logo'] = (int) $attachment_id;
	update_option( 'sweet_house_footer_settings', $opt );
	return true;
}, 10, 2 );

// ── Hide theme admin menu when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_menu_page( 'sweet-house-content' );
}, 999 );
