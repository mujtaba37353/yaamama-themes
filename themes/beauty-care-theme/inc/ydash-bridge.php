<?php
/**
 * Beauty Care Theme - Yaamama Dashboard Bridge
 *
 * Maps beauty_care_* array options to the unified dashboard format.
 *
 * @package beauty-care-theme
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'beauty-care-theme',
		'theme_name'  => 'Beauty Care',
		'store_type'  => 'retail',
		'version'     => defined( 'BEAUTY_CARE_VERSION' ) ? BEAUTY_CARE_VERSION : '1.0.0',
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
			array( 'id' => 'color_primary', 'label' => 'اللون الأساسي', 'default' => '#FEE2AD' ),
			array( 'id' => 'color_secondary', 'label' => 'اللون الثانوي', 'default' => '#bda069' ),
			array( 'id' => 'color_pink', 'label' => 'اللون الوردي', 'default' => '#D50B8B' ),
			array( 'id' => 'color_icon', 'label' => 'لون الأيقونة', 'default' => '#F3F6CD' ),
			array( 'id' => 'color_bg', 'label' => 'خلفية الصفحة', 'default' => '#ffffff' ),
			array( 'id' => 'color_text', 'label' => 'لون النص', 'default' => '#2a1e07' ),
			array( 'id' => 'color_muted', 'label' => 'لون النص الباهت', 'default' => '#382607' ),
		),
		'homepage_sections' => array( 'hero', 'about', 'banners', 'categories', 'products' ),
	);
});

// ── Homepage ──

add_filter( 'ydash_get_homepage_settings', function () {
	$h = function_exists( 'beauty_care_get_homepage_settings' ) ? beauty_care_get_homepage_settings() : get_option( 'beauty_care_homepage_settings', array() );
	return array(
		'hero' => array(
			'slides' => array(
				array( 'image' => isset( $h['hero_img1'] ) ? (int) $h['hero_img1'] : 0 ),
				array( 'image' => isset( $h['hero_img2'] ) ? (int) $h['hero_img2'] : 0 ),
			),
			'title'      => isset( $h['hero_title'] ) ? $h['hero_title'] : '',
			'desc'       => ( isset( $h['hero_subtitle1'] ) ? $h['hero_subtitle1'] : '' ) . "\n" . ( isset( $h['hero_subtitle2'] ) ? $h['hero_subtitle2'] : '' ),
			'btn_text'   => isset( $h['hero_cta_text'] ) ? $h['hero_cta_text'] : '',
			'btn_url'    => '',
		),
		'banners' => array(
			'main'  => isset( $h['panner1_text'] ) ? $h['panner1_text'] : '',
			'main2' => isset( $h['panner2_text1'] ) ? $h['panner2_text1'] : '',
			'panner1_img' => isset( $h['panner1_img'] ) ? (int) $h['panner1_img'] : 0,
			'panner2_img' => isset( $h['panner2_img'] ) ? (int) $h['panner2_img'] : 0,
			'panner2_text2' => isset( $h['panner2_text2'] ) ? $h['panner2_text2'] : '',
			'panner2_cta_text' => isset( $h['panner2_cta_text'] ) ? $h['panner2_cta_text'] : '',
		),
		'about' => array(
			'title' => isset( $h['about_title'] ) ? $h['about_title'] : '',
			'text'  => isset( $h['about_text'] ) ? $h['about_text'] : '',
			'img'   => isset( $h['about_img'] ) ? (int) $h['about_img'] : 0,
		),
		'categories' => array(
			'cat1_title' => isset( $h['cat1_title'] ) ? $h['cat1_title'] : '',
			'cat2_title' => isset( $h['cat2_title'] ) ? $h['cat2_title'] : '',
			'cat3_title' => isset( $h['cat3_title'] ) ? $h['cat3_title'] : '',
			'cat4_title' => isset( $h['cat4_title'] ) ? $h['cat4_title'] : '',
			'cat1_img'   => isset( $h['cat1_img'] ) ? (int) $h['cat1_img'] : 0,
			'cat2_img'   => isset( $h['cat2_img'] ) ? (int) $h['cat2_img'] : 0,
			'cat3_img'   => isset( $h['cat3_img'] ) ? (int) $h['cat3_img'] : 0,
			'cat4_img'   => isset( $h['cat4_img'] ) ? (int) $h['cat4_img'] : 0,
		),
		'products' => array(
			'title'    => isset( $h['products_title'] ) ? $h['products_title'] : '',
			'text'     => isset( $h['products_text'] ) ? $h['products_text'] : '',
			'cta_text' => isset( $h['products_cta_text'] ) ? $h['products_cta_text'] : '',
		),
		'hero_curve' => isset( $h['hero_curve'] ) ? (int) $h['hero_curve'] : 0,
	);
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	$opt = get_option( 'beauty_care_homepage_settings', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}

	$hero = $d['hero'] ?? array();
	$slides = $hero['slides'] ?? array();
	if ( isset( $slides[0]['image'] ) ) {
		$opt['hero_img1'] = (int) $slides[0]['image'];
	}
	if ( isset( $slides[1]['image'] ) ) {
		$opt['hero_img2'] = (int) $slides[1]['image'];
	}
	if ( isset( $hero['title'] ) ) {
		$opt['hero_title'] = sanitize_text_field( $hero['title'] );
	}
	if ( isset( $hero['desc'] ) ) {
		$parts = preg_split( '/\r?\n/', $hero['desc'], 2 );
		$opt['hero_subtitle1'] = isset( $parts[0] ) ? sanitize_text_field( $parts[0] ) : '';
		$opt['hero_subtitle2'] = isset( $parts[1] ) ? sanitize_text_field( $parts[1] ) : '';
	}
	if ( isset( $hero['btn_text'] ) ) {
		$opt['hero_cta_text'] = sanitize_text_field( $hero['btn_text'] );
	}

	$banners = $d['banners'] ?? array();
	if ( isset( $banners['main'] ) ) {
		$opt['panner1_text'] = sanitize_textarea_field( $banners['main'] );
	}
	if ( isset( $banners['main2'] ) ) {
		$opt['panner2_text1'] = sanitize_text_field( $banners['main2'] );
	}
	if ( isset( $banners['panner1_img'] ) ) {
		$opt['panner1_img'] = (int) $banners['panner1_img'];
	}
	if ( isset( $banners['panner2_img'] ) ) {
		$opt['panner2_img'] = (int) $banners['panner2_img'];
	}
	if ( isset( $banners['panner2_text2'] ) ) {
		$opt['panner2_text2'] = sanitize_text_field( $banners['panner2_text2'] );
	}
	if ( isset( $banners['panner2_cta_text'] ) ) {
		$opt['panner2_cta_text'] = sanitize_text_field( $banners['panner2_cta_text'] );
	}

	$about = $d['about'] ?? array();
	if ( isset( $about['title'] ) ) {
		$opt['about_title'] = sanitize_text_field( $about['title'] );
	}
	if ( isset( $about['text'] ) ) {
		$opt['about_text'] = sanitize_textarea_field( $about['text'] );
	}
	if ( isset( $about['img'] ) ) {
		$opt['about_img'] = (int) $about['img'];
	}

	$categories = $d['categories'] ?? array();
	$cat_keys = array( 'cat1_title', 'cat2_title', 'cat3_title', 'cat4_title', 'cat1_img', 'cat2_img', 'cat3_img', 'cat4_img' );
	foreach ( $cat_keys as $k ) {
		if ( isset( $categories[ $k ] ) ) {
			$opt[ $k ] = strpos( $k, '_img' ) !== false ? (int) $categories[ $k ] : sanitize_text_field( $categories[ $k ] );
		}
	}

	$products = $d['products'] ?? array();
	if ( isset( $products['title'] ) ) {
		$opt['products_title'] = sanitize_text_field( $products['title'] );
	}
	if ( isset( $products['text'] ) ) {
		$opt['products_text'] = sanitize_textarea_field( $products['text'] );
	}
	if ( isset( $products['cta_text'] ) ) {
		$opt['products_cta_text'] = sanitize_text_field( $products['cta_text'] );
	}

	if ( isset( $d['hero_curve'] ) ) {
		$opt['hero_curve'] = (int) $d['hero_curve'];
	}

	update_option( 'beauty_care_homepage_settings', $opt );
});

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	$c = function_exists( 'beauty_care_get_contact_settings' ) ? beauty_care_get_contact_settings() : get_option( 'beauty_care_contact_settings', array() );
	$f = function_exists( 'beauty_care_get_footer_settings' ) ? beauty_care_get_footer_settings() : get_option( 'beauty_care_footer_settings', array() );
	return array(
		'phone'    => isset( $c['contact_phone'] ) ? $c['contact_phone'] : ( isset( $f['phone_number'] ) ? $f['phone_number'] : '' ),
		'email'    => isset( $c['contact_email_display'] ) ? $c['contact_email_display'] : '',
		'whatsapp' => isset( $f['whatsapp_number'] ) ? $f['whatsapp_number'] : '',
		'address'  => isset( $c['contact_address'] ) ? $c['contact_address'] : '',
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
		'sidebar_image' => isset( $c['sidebar_image'] ) ? (int) $c['sidebar_image'] : 0,
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	$c = get_option( 'beauty_care_contact_settings', array() );
	$f = get_option( 'beauty_care_footer_settings', array() );
	if ( ! is_array( $c ) ) {
		$c = array();
	}
	if ( ! is_array( $f ) ) {
		$f = array();
	}

	if ( isset( $d['phone'] ) ) {
		$c['contact_phone'] = sanitize_text_field( $d['phone'] );
		$f['phone_number']  = sanitize_text_field( $d['phone'] );
	}
	if ( isset( $d['email'] ) ) {
		$c['contact_email_display'] = sanitize_email( $d['email'] );
	}
	if ( isset( $d['whatsapp'] ) ) {
		$f['whatsapp_number'] = sanitize_text_field( $d['whatsapp'] );
	}
	if ( isset( $d['address'] ) ) {
		$c['contact_address'] = sanitize_text_field( $d['address'] );
	}
	if ( isset( $d['recipient_email'] ) ) {
		$c['recipient_email'] = sanitize_email( $d['recipient_email'] );
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
	if ( isset( $smtp['from_email'] ) ) {
		$c['smtp_from_email'] = sanitize_email( $smtp['from_email'] );
	}

	if ( isset( $d['sidebar_image'] ) ) {
		$c['sidebar_image'] = (int) $d['sidebar_image'];
	}

	update_option( 'beauty_care_contact_settings', $c );
	update_option( 'beauty_care_footer_settings', $f );
});

// ── Colors ──

add_filter( 'ydash_get_color_settings', function () {
	$s = function_exists( 'beauty_care_get_site_settings' ) ? beauty_care_get_site_settings() : get_option( 'beauty_care_site_settings', array() );
	return array(
		'color_primary'   => isset( $s['color_primary'] ) ? $s['color_primary'] : '',
		'color_secondary' => isset( $s['color_secondary'] ) ? $s['color_secondary'] : '',
		'color_pink'      => isset( $s['color_pink'] ) ? $s['color_pink'] : '',
		'color_icon'      => isset( $s['color_icon'] ) ? $s['color_icon'] : '',
		'color_bg'        => isset( $s['color_bg'] ) ? $s['color_bg'] : '',
		'color_text'      => isset( $s['color_text'] ) ? $s['color_text'] : '',
		'color_muted'     => isset( $s['color_muted'] ) ? $s['color_muted'] : '',
	);
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	$opt = get_option( 'beauty_care_site_settings', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}
	$keys = array( 'color_primary', 'color_secondary', 'color_pink', 'color_icon', 'color_bg', 'color_text', 'color_muted' );
	foreach ( $keys as $k ) {
		if ( isset( $d[ $k ] ) && $d[ $k ] !== '' ) {
			$val = sanitize_hex_color( $d[ $k ] );
			if ( $val ) {
				$opt[ $k ] = $val;
			}
		}
	}
	update_option( 'beauty_care_site_settings', $opt );
});

// ── Logo ──

add_filter( 'ydash_get_logo', function () {
	$f = function_exists( 'beauty_care_get_footer_settings' ) ? beauty_care_get_footer_settings() : get_option( 'beauty_care_footer_settings', array() );
	$id = isset( $f['site_logo'] ) ? (int) $f['site_logo'] : 0;
	return $id && wp_attachment_is_image( $id ) ? wp_get_attachment_url( $id ) : '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	$opt = get_option( 'beauty_care_footer_settings', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}
	$opt['site_logo'] = (int) $attachment_id;
	update_option( 'beauty_care_footer_settings', $opt );
	return true;
}, 10, 2 );

// ── Hide theme admin menu when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_menu_page( 'beauty-care-content' );
}, 999 );
