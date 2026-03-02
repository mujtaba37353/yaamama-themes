<?php
/**
 * Al-Thabihah Theme - Yaamama Dashboard Bridge
 *
 * Maps al_thabihah_* array options to the unified dashboard format.
 *
 * @package al-thabihah-theme
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'al-thabihah-theme',
		'theme_name'  => 'Al-Thabihah',
		'store_type'  => 'retail',
		'version'     => defined( 'AL_THABIHAH_THEME_VERSION' ) ? AL_THABIHAH_THEME_VERSION : '1.0.0',
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
			array( 'id' => 'header_color', 'label' => 'لون الهيدر', 'default' => '#67001a' ),
			array( 'id' => 'footer_color', 'label' => 'لون الفوتر', 'default' => '#67001a' ),
			array( 'id' => 'add_to_cart_color', 'label' => 'لون زر إضافة للسلة', 'default' => '#9a1c20' ),
			array( 'id' => 'checkout_color', 'label' => 'لون زر الدفع', 'default' => '#9a1c20' ),
			array( 'id' => 'payment_color', 'label' => 'لون زر التأكيد', 'default' => '#9a1c20' ),
			array( 'id' => 'page_background', 'label' => 'خلفية الصفحة', 'default' => '#f7f7f7' ),
		),
		'homepage_sections' => array( 'hero', 'categories', 'featured', 'offers', 'promo', 'testimonials' ),
	);
});

// ── Homepage ──

add_filter( 'ydash_get_homepage_settings', function () {
	$h = function_exists( 'al_thabihah_get_option' ) ? al_thabihah_get_option( 'al_thabihah_home_settings', array() ) : get_option( 'al_thabihah_home_settings', array() );
	$defaults = function_exists( 'al_thabihah_default_home_settings' ) ? al_thabihah_default_home_settings() : array();
	$h = wp_parse_args( $h, $defaults );
	return array(
		'hero' => array(
			'slides' => array(
				array( 'image' => isset( $h['hero_image_id'] ) ? (int) $h['hero_image_id'] : 0 ),
			),
			'title'    => isset( $h['hero_title'] ) ? $h['hero_title'] : '',
			'desc'     => isset( $h['hero_subtitle'] ) ? $h['hero_subtitle'] : '',
			'btn_text' => '',
			'btn_url'  => '',
		),
		'categories' => array(
			'subtitle' => isset( $h['category_subtitle'] ) ? $h['category_subtitle'] : '',
			'title'    => isset( $h['category_title'] ) ? $h['category_title'] : '',
			'cat1_label' => isset( $h['category_offers_label'] ) ? $h['category_offers_label'] : '',
			'cat2_label' => isset( $h['category_naemi_label'] ) ? $h['category_naemi_label'] : '',
			'cat3_label' => isset( $h['category_tays_label'] ) ? $h['category_tays_label'] : '',
			'cat4_label' => isset( $h['category_ejel_label'] ) ? $h['category_ejel_label'] : '',
			'cat5_label' => isset( $h['category_cuts_label'] ) ? $h['category_cuts_label'] : '',
			'cat1_img' => isset( $h['category_offers_image_id'] ) ? (int) $h['category_offers_image_id'] : 0,
			'cat2_img' => isset( $h['category_naemi_image_id'] ) ? (int) $h['category_naemi_image_id'] : 0,
			'cat3_img' => isset( $h['category_tays_image_id'] ) ? (int) $h['category_tays_image_id'] : 0,
			'cat4_img' => isset( $h['category_ejel_image_id'] ) ? (int) $h['category_ejel_image_id'] : 0,
			'cat5_img' => isset( $h['category_cuts_image_id'] ) ? (int) $h['category_cuts_image_id'] : 0,
		),
		'featured' => array(
			'title' => isset( $h['featured_title'] ) ? $h['featured_title'] : '',
		),
		'offers' => array(
			'title' => isset( $h['offers_title'] ) ? $h['offers_title'] : '',
		),
		'promo' => array(
			'title'   => isset( $h['promo_title'] ) ? $h['promo_title'] : '',
			'subtitle' => isset( $h['promo_subtitle'] ) ? $h['promo_subtitle'] : '',
			'btn_text' => isset( $h['promo_button'] ) ? $h['promo_button'] : '',
			'img'     => isset( $h['promo_image_id'] ) ? (int) $h['promo_image_id'] : 0,
		),
		'testimonials' => array(
			'title' => isset( $h['testimonials_title'] ) ? $h['testimonials_title'] : '',
		),
	);
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	$opt = get_option( 'al_thabihah_home_settings', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}

	$hero = $d['hero'] ?? array();
	$slides = $hero['slides'] ?? array();
	if ( isset( $slides[0]['image'] ) ) {
		$opt['hero_image_id'] = (int) $slides[0]['image'];
	}
	if ( isset( $hero['title'] ) ) {
		$opt['hero_title'] = sanitize_text_field( $hero['title'] );
	}
	if ( isset( $hero['desc'] ) ) {
		$opt['hero_subtitle'] = sanitize_text_field( $hero['desc'] );
	}

	$categories = $d['categories'] ?? array();
	$cat_map = array(
		'subtitle' => 'category_subtitle',
		'title' => 'category_title',
		'cat1_label' => 'category_offers_label',
		'cat2_label' => 'category_naemi_label',
		'cat3_label' => 'category_tays_label',
		'cat4_label' => 'category_ejel_label',
		'cat5_label' => 'category_cuts_label',
		'cat1_img' => 'category_offers_image_id',
		'cat2_img' => 'category_naemi_image_id',
		'cat3_img' => 'category_tays_image_id',
		'cat4_img' => 'category_ejel_image_id',
		'cat5_img' => 'category_cuts_image_id',
	);
	foreach ( $cat_map as $dkey => $optkey ) {
		if ( isset( $categories[ $dkey ] ) ) {
			$opt[ $optkey ] = strpos( $dkey, '_img' ) !== false ? (int) $categories[ $dkey ] : sanitize_text_field( $categories[ $dkey ] );
		}
	}

	$featured = $d['featured'] ?? array();
	if ( isset( $featured['title'] ) ) {
		$opt['featured_title'] = sanitize_text_field( $featured['title'] );
	}

	$offers = $d['offers'] ?? array();
	if ( isset( $offers['title'] ) ) {
		$opt['offers_title'] = sanitize_text_field( $offers['title'] );
	}

	$promo = $d['promo'] ?? array();
	if ( isset( $promo['title'] ) ) {
		$opt['promo_title'] = sanitize_text_field( $promo['title'] );
	}
	if ( isset( $promo['subtitle'] ) ) {
		$opt['promo_subtitle'] = sanitize_text_field( $promo['subtitle'] );
	}
	if ( isset( $promo['btn_text'] ) ) {
		$opt['promo_button'] = sanitize_text_field( $promo['btn_text'] );
	}
	if ( isset( $promo['img'] ) ) {
		$opt['promo_image_id'] = (int) $promo['img'];
	}

	$testimonials = $d['testimonials'] ?? array();
	if ( isset( $testimonials['title'] ) ) {
		$opt['testimonials_title'] = sanitize_text_field( $testimonials['title'] );
	}

	update_option( 'al_thabihah_home_settings', $opt );
});

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	$c = function_exists( 'al_thabihah_get_option' ) ? al_thabihah_get_option( 'al_thabihah_contact_settings', array() ) : get_option( 'al_thabihah_contact_settings', array() );
	$f = function_exists( 'al_thabihah_get_option' ) ? al_thabihah_get_option( 'al_thabihah_footer_settings', array() ) : get_option( 'al_thabihah_footer_settings', array() );
	$smtp = function_exists( 'al_thabihah_get_option' ) ? al_thabihah_get_option( 'al_thabihah_smtp_settings', array() ) : get_option( 'al_thabihah_smtp_settings', array() );
	return array(
		'phone'    => isset( $c['phone'] ) ? $c['phone'] : ( isset( $f['phone'] ) ? $f['phone'] : '' ),
		'email'    => isset( $c['email'] ) ? $c['email'] : '',
		'whatsapp' => isset( $c['whatsapp'] ) ? $c['whatsapp'] : ( isset( $f['whatsapp'] ) ? $f['whatsapp'] : '' ),
		'address'  => isset( $f['address'] ) ? $f['address'] : '',
		'social'   => array(
			'facebook'  => '',
			'instagram' => '',
			'twitter'   => '',
		),
		'recipient_email' => isset( $c['email'] ) ? $c['email'] : '',
		'mail' => array(
			'mailer_type' => ! empty( $smtp['host'] ) ? 'smtp' : 'gmail',
			'gmail'       => array(
				'email'        => '',
				'app_password' => '',
			),
			'smtp' => array(
				'host'       => isset( $smtp['host'] ) ? $smtp['host'] : '',
				'port'       => isset( $smtp['port'] ) ? $smtp['port'] : '587',
				'username'   => isset( $smtp['username'] ) ? $smtp['username'] : '',
				'password'   => isset( $smtp['password'] ) ? $smtp['password'] : '',
				'encryption' => isset( $smtp['encryption'] ) ? $smtp['encryption'] : 'tls',
				'from_email' => isset( $smtp['from_email'] ) ? $smtp['from_email'] : '',
				'from_name' => isset( $smtp['from_name'] ) ? $smtp['from_name'] : '',
			),
		),
		'floating' => array(
			'whatsapp' => isset( $f['floating_whatsapp'] ) ? $f['floating_whatsapp'] : ( isset( $f['whatsapp'] ) ? $f['whatsapp'] : '' ),
			'call'     => isset( $f['floating_phone'] ) ? $f['floating_phone'] : ( isset( $f['phone'] ) ? $f['phone'] : '' ),
			'enabled'  => ! empty( $f['floating_enabled'] ),
		),
		'sidebar_image' => isset( $c['image_id'] ) ? (int) $c['image_id'] : 0,
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	$c = get_option( 'al_thabihah_contact_settings', array() );
	$f = get_option( 'al_thabihah_footer_settings', array() );
	$smtp = get_option( 'al_thabihah_smtp_settings', array() );
	if ( ! is_array( $c ) ) {
		$c = array();
	}
	if ( ! is_array( $f ) ) {
		$f = array();
	}
	if ( ! is_array( $smtp ) ) {
		$smtp = array();
	}

	if ( isset( $d['phone'] ) ) {
		$c['phone'] = sanitize_text_field( $d['phone'] );
		$f['phone'] = sanitize_text_field( $d['phone'] );
	}
	if ( isset( $d['email'] ) ) {
		$c['email'] = sanitize_email( $d['email'] );
	}
	if ( isset( $d['whatsapp'] ) ) {
		$c['whatsapp'] = sanitize_text_field( $d['whatsapp'] );
		$f['whatsapp'] = sanitize_text_field( $d['whatsapp'] );
	}
	if ( isset( $d['address'] ) ) {
		$f['address'] = sanitize_text_field( $d['address'] );
	}
	if ( isset( $d['recipient_email'] ) ) {
		$c['email'] = sanitize_email( $d['recipient_email'] );
	}

	$floating = $d['floating'] ?? array();
	if ( isset( $floating['whatsapp'] ) ) {
		$f['floating_whatsapp'] = sanitize_text_field( $floating['whatsapp'] );
	}
	if ( isset( $floating['call'] ) ) {
		$f['floating_phone'] = sanitize_text_field( $floating['call'] );
	}
	if ( isset( $floating['enabled'] ) ) {
		$f['floating_enabled'] = $floating['enabled'] ? 1 : 0;
	}

	$mail = $d['mail'] ?? array();
	$smtp_data = $mail['smtp'] ?? array();
	if ( isset( $smtp_data['host'] ) ) {
		$smtp['host'] = sanitize_text_field( $smtp_data['host'] );
	}
	if ( isset( $smtp_data['port'] ) ) {
		$smtp['port'] = sanitize_text_field( $smtp_data['port'] );
	}
	if ( isset( $smtp_data['username'] ) ) {
		$smtp['username'] = sanitize_text_field( $smtp_data['username'] );
	}
	if ( isset( $smtp_data['password'] ) ) {
		$smtp['password'] = sanitize_text_field( $smtp_data['password'] );
	}
	if ( isset( $smtp_data['encryption'] ) ) {
		$smtp['encryption'] = sanitize_text_field( $smtp_data['encryption'] );
	}
	if ( isset( $smtp_data['from_email'] ) ) {
		$smtp['from_email'] = sanitize_email( $smtp_data['from_email'] );
	}
	if ( isset( $smtp_data['from_name'] ) ) {
		$smtp['from_name'] = sanitize_text_field( $smtp_data['from_name'] );
	}

	if ( isset( $d['sidebar_image'] ) ) {
		$c['image_id'] = (int) $d['sidebar_image'];
	}

	update_option( 'al_thabihah_contact_settings', $c );
	update_option( 'al_thabihah_footer_settings', $f );
	update_option( 'al_thabihah_smtp_settings', $smtp );
});

// ── Colors ──

add_filter( 'ydash_get_color_settings', function () {
	$s = function_exists( 'al_thabihah_get_option' ) ? al_thabihah_get_option( 'al_thabihah_site_colors', array() ) : get_option( 'al_thabihah_site_colors', array() );
	return array(
		'header_color'      => isset( $s['header_color'] ) ? $s['header_color'] : '',
		'footer_color'      => isset( $s['footer_color'] ) ? $s['footer_color'] : '',
		'add_to_cart_color' => isset( $s['add_to_cart_color'] ) ? $s['add_to_cart_color'] : '',
		'checkout_color'    => isset( $s['checkout_color'] ) ? $s['checkout_color'] : '',
		'payment_color'     => isset( $s['payment_color'] ) ? $s['payment_color'] : '',
		'page_background'   => isset( $s['page_background'] ) ? $s['page_background'] : '',
	);
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	$opt = get_option( 'al_thabihah_site_colors', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}
	$keys = array( 'header_color', 'footer_color', 'add_to_cart_color', 'checkout_color', 'payment_color', 'page_background' );
	foreach ( $keys as $k ) {
		if ( isset( $d[ $k ] ) && $d[ $k ] !== '' ) {
			$val = sanitize_hex_color( $d[ $k ] );
			if ( $val ) {
				$opt[ $k ] = $val;
			}
		}
	}
	update_option( 'al_thabihah_site_colors', $opt );
});

// ── Logo ──

add_filter( 'ydash_get_logo', function () {
	$f = function_exists( 'al_thabihah_get_option' ) ? al_thabihah_get_option( 'al_thabihah_footer_settings', array() ) : get_option( 'al_thabihah_footer_settings', array() );
	$id = isset( $f['header_logo_id'] ) ? (int) $f['header_logo_id'] : ( isset( $f['footer_logo_id'] ) ? (int) $f['footer_logo_id'] : 0 );
	return $id && wp_attachment_is_image( $id ) ? wp_get_attachment_url( $id ) : '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	$opt = get_option( 'al_thabihah_footer_settings', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}
	$opt['header_logo_id'] = (int) $attachment_id;
	$opt['footer_logo_id'] = (int) $attachment_id;
	update_option( 'al_thabihah_footer_settings', $opt );
	return true;
}, 10, 2 );

// ── Hide theme admin menu when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_menu_page( 'al-thabihah-content' );
}, 999 );
