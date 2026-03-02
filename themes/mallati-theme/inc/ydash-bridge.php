<?php
/**
 * Mallati Theme - Yaamama Dashboard Bridge
 *
 * Maps mallati_* options (get_option + get_theme_mod) to the unified dashboard format.
 *
 * @package Mallati
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'mallati-theme',
		'theme_name'  => 'Mallati',
		'store_type'  => 'retail',
		'version'     => defined( 'MALLATI_THEME_VERSION' ) ? MALLATI_THEME_VERSION : '1.0.0',
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
	$slides_raw = get_option( 'mallati_hero_slides', array() );
	$slides = array();
	foreach ( (array) $slides_raw as $id ) {
		$slides[] = array( 'image' => (int) $id );
	}
	return array(
		'hero' => array(
			'slides'      => $slides,
			'title'       => '',
			'desc'        => '',
			'btn_text'    => '',
			'btn_url'     => '',
			'title_best'  => get_option( 'mallati_home_title_best', '' ),
			'title_new'   => get_option( 'mallati_home_title_new', '' ),
		),
		'banners' => array(
			'main'      => get_option( 'mallati_home_banner', 0 ),
			'main2'     => '',
			'title_best'=> get_option( 'mallati_home_title_best', '' ),
			'title_new' => get_option( 'mallati_home_title_new', '' ),
		),
	);
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	$hero = $d['hero'] ?? array();
	$slides = $hero['slides'] ?? array();
	$ids = array();
	foreach ( (array) $slides as $slide ) {
		$ids[] = isset( $slide['image'] ) ? (int) $slide['image'] : 0;
	}
	if ( ! empty( $ids ) ) {
		update_option( 'mallati_hero_slides', array_pad( $ids, 3, 0 ) );
	}
	if ( isset( $hero['title_best'] ) ) {
		update_option( 'mallati_home_title_best', sanitize_text_field( $hero['title_best'] ) );
	}
	if ( isset( $hero['title_new'] ) ) {
		update_option( 'mallati_home_title_new', sanitize_text_field( $hero['title_new'] ) );
	}

	$banners = $d['banners'] ?? array();
	if ( isset( $banners['main'] ) ) {
		update_option( 'mallati_home_banner', (int) $banners['main'] );
	}
	if ( isset( $banners['title_best'] ) ) {
		update_option( 'mallati_home_title_best', sanitize_text_field( $banners['title_best'] ) );
	}
	if ( isset( $banners['title_new'] ) ) {
		update_option( 'mallati_home_title_new', sanitize_text_field( $banners['title_new'] ) );
	}
} );

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	return array(
		'phone'    => get_theme_mod( 'mallati_phone', '' ),
		'email'    => get_theme_mod( 'mallati_email', '' ),
		'whatsapp' => get_theme_mod( 'mallati_whatsapp', '' ),
		'address'  => get_theme_mod( 'mallati_address', '' ),
		'map_embed'=> get_theme_mod( 'mallati_map_embed', '' ),
		'social'   => array(
			'facebook'  => get_theme_mod( 'mallati_facebook', '' ),
			'instagram' => get_theme_mod( 'mallati_instagram', '' ),
			'snapchat'  => get_theme_mod( 'mallati_snapchat', '' ),
		),
		'form' => array(
			'title' => get_option( 'mallati_contact_form_title', '' ),
			'desc'  => get_option( 'mallati_contact_form_desc', '' ),
		),
		'mail' => array(
			'mailer_type' => get_option( 'mallati_smtp_type', 'gmail' ),
			'gmail'       => array(
				'email'        => get_option( 'mallati_smtp_user', '' ),
				'app_password' => get_option( 'mallati_smtp_pass', '' ),
			),
			'smtp' => array(
				'host'       => get_option( 'mallati_smtp_host', '' ),
				'port'       => get_option( 'mallati_smtp_port', '587' ),
				'username'   => get_option( 'mallati_smtp_user', '' ),
				'password'   => get_option( 'mallati_smtp_pass', '' ),
				'encryption' => 'tls',
			),
		),
		'floating' => array(
			'whatsapp' => get_theme_mod( 'mallati_footer_whatsapp', '' ),
			'call'     => get_theme_mod( 'mallati_footer_phone', '' ),
		),
		'footer' => array(
			'phone'     => get_theme_mod( 'mallati_footer_phone', '' ),
			'whatsapp'  => get_theme_mod( 'mallati_footer_whatsapp', '' ),
			'copyright' => get_theme_mod( 'mallati_footer_copyright', '' ),
		),
		'floating_buttons' => get_theme_mod( 'mallati_floating_buttons', 0 ),
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	$map = array(
		'phone'   => 'mallati_phone',
		'email'   => 'mallati_email',
		'address' => 'mallati_address',
		'whatsapp'=> 'mallati_whatsapp',
	);
	foreach ( $map as $k => $mod ) {
		if ( isset( $d[ $k ] ) ) {
			set_theme_mod( $mod, sanitize_text_field( $d[ $k ] ) );
		}
	}
	if ( isset( $d['map_embed'] ) ) {
		set_theme_mod( 'mallati_map_embed', esc_url_raw( $d['map_embed'] ) );
	}

	$social = $d['social'] ?? array();
	$social_map = array(
		'facebook'  => 'mallati_facebook',
		'instagram' => 'mallati_instagram',
		'snapchat'  => 'mallati_snapchat',
	);
	foreach ( $social_map as $k => $mod ) {
		if ( isset( $social[ $k ] ) ) {
			set_theme_mod( $mod, esc_url_raw( $social[ $k ] ) );
		}
	}

	$form = $d['form'] ?? array();
	if ( isset( $form['title'] ) ) {
		update_option( 'mallati_contact_form_title', sanitize_text_field( $form['title'] ) );
	}
	if ( isset( $form['desc'] ) ) {
		update_option( 'mallati_contact_form_desc', sanitize_textarea_field( $form['desc'] ) );
	}

	$mail = $d['mail'] ?? array();
	if ( isset( $mail['mailer_type'] ) ) {
		update_option( 'mallati_smtp_type', sanitize_text_field( $mail['mailer_type'] ) );
	}
	$gmail = $mail['gmail'] ?? array();
	if ( isset( $gmail['email'] ) ) {
		update_option( 'mallati_smtp_user', sanitize_email( $gmail['email'] ) );
	}
	if ( isset( $gmail['app_password'] ) ) {
		update_option( 'mallati_smtp_pass', sanitize_text_field( $gmail['app_password'] ) );
	}
	$smtp = $mail['smtp'] ?? array();
	if ( isset( $smtp['host'] ) ) {
		update_option( 'mallati_smtp_host', sanitize_text_field( $smtp['host'] ) );
	}
	if ( isset( $smtp['port'] ) ) {
		update_option( 'mallati_smtp_port', absint( $smtp['port'] ) ?: 587 );
	}
	if ( isset( $smtp['username'] ) ) {
		update_option( 'mallati_smtp_user', sanitize_text_field( $smtp['username'] ) );
	}
	if ( isset( $smtp['password'] ) && '' !== $smtp['password'] ) {
		update_option( 'mallati_smtp_pass', sanitize_text_field( $smtp['password'] ) );
	}

	$floating = $d['floating'] ?? array();
	if ( isset( $floating['whatsapp'] ) ) {
		set_theme_mod( 'mallati_footer_whatsapp', sanitize_text_field( $floating['whatsapp'] ) );
	}
	if ( isset( $floating['call'] ) ) {
		set_theme_mod( 'mallati_footer_phone', sanitize_text_field( $floating['call'] ) );
	}

	$footer = $d['footer'] ?? array();
	if ( isset( $footer['phone'] ) ) {
		set_theme_mod( 'mallati_footer_phone', sanitize_text_field( $footer['phone'] ) );
	}
	if ( isset( $footer['whatsapp'] ) ) {
		set_theme_mod( 'mallati_footer_whatsapp', sanitize_text_field( $footer['whatsapp'] ) );
	}
	if ( isset( $footer['copyright'] ) ) {
		set_theme_mod( 'mallati_footer_copyright', sanitize_text_field( $footer['copyright'] ) );
	}
	if ( isset( $d['floating_buttons'] ) ) {
		set_theme_mod( 'mallati_floating_buttons', (int) $d['floating_buttons'] );
	}
} );

// ── Colors ──

add_filter( 'ydash_get_color_settings', function () {
	$map = array(
		'header_color'      => 'mallati_color_header_bg',
		'footer_color'      => 'mallati_color_footer_bg',
		'btn_cart_color'    => 'mallati_color_add_to_cart',
		'btn_checkout_color'=> 'mallati_color_checkout_btn',
		'btn_payment_color' => 'mallati_color_payment_btn',
		'page_bg_color'     => 'mallati_color_page_bg',
	);
	$out = array();
	foreach ( $map as $k => $mod ) {
		$out[ $k ] = get_theme_mod( $mod, '' );
	}
	return $out;
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	$map = array(
		'header_color'      => 'mallati_color_header_bg',
		'footer_color'      => 'mallati_color_footer_bg',
		'btn_cart_color'    => 'mallati_color_add_to_cart',
		'btn_checkout_color'=> 'mallati_color_checkout_btn',
		'btn_payment_color' => 'mallati_color_payment_btn',
		'page_bg_color'     => 'mallati_color_page_bg',
	);
	foreach ( $map as $k => $mod ) {
		if ( isset( $d[ $k ] ) ) {
			set_theme_mod( $mod, sanitize_hex_color( $d[ $k ] ) );
		}
	}
} );

// ── Logo ──

add_filter( 'ydash_get_logo', function () {
	$id = (int) get_theme_mod( 'mallati_logo_id', 0 ) ?: (int) get_theme_mod( 'mallati_footer_logo', 0 );
	return $id ? wp_get_attachment_url( $id ) : '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	$id = (int) $attachment_id;
	set_theme_mod( 'mallati_footer_logo', $id );
	set_theme_mod( 'mallati_logo_id', $id );
	return true;
}, 10, 2 );

// ── Hide theme admin pages when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_menu_page( 'mallati-content' );
}, 999 );
