<?php
/**
 * Beauty Time Theme - Yaamama Dashboard Bridge
 *
 * Maps beauty_demo_site_options, beauty_contact_settings, beauty_static_pages_content
 * to the unified dashboard format.
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'beauty-time-theme',
		'theme_name'  => 'Beauty Time',
		'store_type'  => 'booking',
		'version'     => defined( 'BEAUTY_TIME_VERSION' ) ? BEAUTY_TIME_VERSION : '1.0.0',
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
			array( 'id' => 'primary',  'label' => 'اللون الأساسي',     'default' => '#C67C73' ),
			array( 'id' => 'card',     'label' => 'لون بطاقة القسم',   'default' => '#A88558' ),
			array( 'id' => 'hero_bg',  'label' => 'لون خلفية الهيرو',  'default' => '#f3eae5' ),
			array( 'id' => 'text',     'label' => 'لون الخط الأساسي',  'default' => '#0d0507' ),
		),
		'homepage_sections' => array( 'hero', 'mid_banner', 'footer' ),
	);
});

// ── Homepage ──

add_filter( 'ydash_get_homepage_settings', function () {
	$opt = function_exists( 'beauty_demo_site_get_options' ) ? beauty_demo_site_get_options() : get_option( 'beauty_demo_site_options', array() );
	$colors = $opt['colors'] ?? array();
	$logos  = $opt['logos'] ?? array();
	$hero   = $opt['hero'] ?? array();
	$mid    = $opt['mid_banner'] ?? array();
	$footer = $opt['footer'] ?? array();

	return array(
		'hero' => array(
			'slides'     => array(
				array( 'image' => 0, 'image_url' => $hero['image'] ?? '' ),
			),
			'title'      => $hero['title'] ?? '',
			'desc'       => $hero['description'] ?? '',
			'btn_text'   => $hero['button_text'] ?? '',
			'btn_url'    => $hero['button_link'] ?? '',
			'text_image' => $hero['text_image'] ?? '',
		),
		'mid_banner' => $mid,
		'footer' => array(
			'paragraph' => $footer['paragraph'] ?? '',
		),
		'logos' => $logos,
		'colors' => $colors,
	);
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	$opt = function_exists( 'beauty_demo_site_get_options' ) ? beauty_demo_site_get_options() : get_option( 'beauty_demo_site_options', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}

	$hero = $d['hero'] ?? array();
	if ( isset( $hero['title'] ) ) {
		$opt['hero']['title'] = sanitize_text_field( $hero['title'] );
	}
	if ( isset( $hero['desc'] ) ) {
		$opt['hero']['description'] = sanitize_textarea_field( $hero['desc'] );
	}
	if ( isset( $hero['btn_text'] ) ) {
		$opt['hero']['button_text'] = sanitize_text_field( $hero['btn_text'] );
	}
	if ( isset( $hero['btn_url'] ) ) {
		$opt['hero']['button_link'] = esc_url_raw( $hero['btn_url'] );
	}
	if ( isset( $hero['text_image'] ) ) {
		$opt['hero']['text_image'] = esc_url_raw( $hero['text_image'] );
	}
	$slides = $hero['slides'] ?? array();
	if ( ! empty( $slides[0] ) ) {
		$img = $slides[0]['image'] ?? $slides[0]['image_url'] ?? '';
		$url = is_numeric( $img ) ? wp_get_attachment_image_url( (int) $img, 'full' ) : $img;
		$opt['hero']['image'] = $url ? esc_url_raw( $url ) : '';
	}

	if ( isset( $d['mid_banner'] ) && is_array( $d['mid_banner'] ) ) {
		$opt['mid_banner'] = array();
		foreach ( $d['mid_banner'] as $item ) {
			$opt['mid_banner'][] = array(
				'icon'   => isset( $item['icon'] ) ? sanitize_text_field( $item['icon'] ) : '',
				'number' => isset( $item['number'] ) ? sanitize_text_field( $item['number'] ) : '',
				'label'  => isset( $item['label'] ) ? sanitize_text_field( $item['label'] ) : '',
			);
		}
	}

	$footer = $d['footer'] ?? array();
	if ( isset( $footer['paragraph'] ) ) {
		$opt['footer']['paragraph'] = sanitize_textarea_field( $footer['paragraph'] );
	}

	$logos = $d['logos'] ?? array();
	if ( isset( $logos['header'] ) ) {
		$opt['logos']['header'] = esc_url_raw( $logos['header'] );
	}
	if ( isset( $logos['footer'] ) ) {
		$opt['logos']['footer'] = esc_url_raw( $logos['footer'] );
	}
	if ( isset( $logos['footer_alt'] ) ) {
		$opt['logos']['footer_alt'] = esc_url_raw( $logos['footer_alt'] );
	}

	$colors = $d['colors'] ?? array();
	if ( ! empty( $colors ) ) {
		foreach ( array( 'primary', 'card', 'hero_bg', 'text' ) as $k ) {
			if ( isset( $colors[ $k ] ) ) {
				$opt['colors'][ $k ] = sanitize_hex_color( $colors[ $k ] ) ?: ( $opt['colors'][ $k ] ?? '' );
			}
		}
	}

	update_option( 'beauty_demo_site_options', $opt, false );
} );

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	$c = function_exists( 'beauty_contact_settings_get_options' ) ? beauty_contact_settings_get_options() : get_option( 'beauty_contact_settings', array() );
	$info = $c['info'] ?? array();
	$whatsapp = $c['whatsapp'] ?? array();
	$prof = $c['professional'] ?? array();
	$gmail = $c['gmail'] ?? array();

	return array(
		'phone'    => $info['phones'] ?? '',
		'email'    => $info['email'] ?? '',
		'whatsapp' => $whatsapp['number'] ?? '',
		'address'  => $info['address'] ?? '',
		'map_embed' => $info['map_embed'] ?? '',
		'working_hours' => $info['working_hours'] ?? '',
		'social'   => array(
			'facebook'  => '',
			'instagram' => '',
			'twitter'   => '',
		),
		'recipient_email' => $c['recipient_email'] ?? '',
		'mail' => array(
			'mailer_type' => $c['mailer_type'] ?? 'professional',
			'gmail'       => array(
				'email'        => $gmail['username'] ?? $gmail['from_email'] ?? '',
				'app_password' => $gmail['app_pass'] ?? '',
				'from_name'    => $gmail['from_name'] ?? '',
				'from_email'   => $gmail['from_email'] ?? '',
			),
			'smtp' => array(
				'host'       => $prof['host'] ?? '',
				'port'       => $prof['port'] ?? '587',
				'username'   => $prof['username'] ?? '',
				'password'   => $prof['password'] ?? '',
				'encryption' => $prof['encryption'] ?? 'tls',
				'from_email' => $prof['from_email'] ?? '',
				'from_name'  => $prof['from_name'] ?? '',
			),
		),
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	$c = function_exists( 'beauty_contact_settings_get_options' ) ? beauty_contact_settings_get_options() : get_option( 'beauty_contact_settings', array() );
	if ( ! is_array( $c ) ) {
		$c = array();
	}

	if ( isset( $d['phone'] ) ) {
		$c['info']['phones'] = sanitize_text_field( $d['phone'] );
	}
	if ( isset( $d['email'] ) ) {
		$c['info']['email'] = sanitize_email( $d['email'] );
	}
	if ( isset( $d['whatsapp'] ) ) {
		$c['whatsapp']['number'] = sanitize_text_field( $d['whatsapp'] );
	}
	if ( isset( $d['address'] ) ) {
		$c['info']['address'] = sanitize_text_field( $d['address'] );
	}
	if ( isset( $d['map_embed'] ) ) {
		$c['info']['map_embed'] = esc_url_raw( $d['map_embed'] );
	}
	if ( isset( $d['working_hours'] ) ) {
		$c['info']['working_hours'] = sanitize_text_field( $d['working_hours'] );
	}
	if ( isset( $d['recipient_email'] ) ) {
		$c['recipient_email'] = sanitize_email( $d['recipient_email'] );
	}

	$mail = $d['mail'] ?? array();
	if ( isset( $mail['mailer_type'] ) ) {
		$c['mailer_type'] = sanitize_text_field( $mail['mailer_type'] );
	}
	$smtp = $mail['smtp'] ?? array();
	if ( isset( $smtp['host'] ) ) {
		$c['professional']['host'] = sanitize_text_field( $smtp['host'] );
	}
	if ( isset( $smtp['port'] ) ) {
		$c['professional']['port'] = sanitize_text_field( $smtp['port'] );
	}
	if ( isset( $smtp['username'] ) ) {
		$c['professional']['username'] = sanitize_text_field( $smtp['username'] );
	}
	if ( isset( $smtp['password'] ) && '' !== $smtp['password'] ) {
		$c['professional']['password'] = sanitize_text_field( $smtp['password'] );
	}
	if ( isset( $smtp['encryption'] ) ) {
		$c['professional']['encryption'] = sanitize_text_field( $smtp['encryption'] );
	}
	if ( isset( $smtp['from_email'] ) ) {
		$c['professional']['from_email'] = sanitize_email( $smtp['from_email'] );
	}
	if ( isset( $smtp['from_name'] ) ) {
		$c['professional']['from_name'] = sanitize_text_field( $smtp['from_name'] );
	}
	$gmail = $mail['gmail'] ?? array();
	if ( isset( $gmail['email'] ) ) {
		$c['gmail']['username'] = sanitize_email( $gmail['email'] );
	}
	if ( isset( $gmail['app_password'] ) && '' !== $gmail['app_password'] ) {
		$c['gmail']['app_pass'] = sanitize_text_field( $gmail['app_password'] );
	}
	if ( isset( $gmail['from_name'] ) ) {
		$c['gmail']['from_name'] = sanitize_text_field( $gmail['from_name'] );
	}
	if ( isset( $gmail['from_email'] ) ) {
		$c['gmail']['from_email'] = sanitize_email( $gmail['from_email'] );
	}

	update_option( 'beauty_contact_settings', $c, false );
} );

// ── Colors ──

add_filter( 'ydash_get_color_settings', function () {
	$opt = function_exists( 'beauty_demo_site_get_options' ) ? beauty_demo_site_get_options() : get_option( 'beauty_demo_site_options', array() );
	$colors = $opt['colors'] ?? array();
	return array(
		'primary' => $colors['primary'] ?? '',
		'card'    => $colors['card'] ?? '',
		'hero_bg' => $colors['hero_bg'] ?? '',
		'text'    => $colors['text'] ?? '',
	);
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	$opt = function_exists( 'beauty_demo_site_get_options' ) ? beauty_demo_site_get_options() : get_option( 'beauty_demo_site_options', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}
	if ( ! isset( $opt['colors'] ) ) {
		$opt['colors'] = array();
	}
	foreach ( array( 'primary', 'card', 'hero_bg', 'text' ) as $k ) {
		if ( isset( $d[ $k ] ) && '' !== $d[ $k ] ) {
			$val = sanitize_hex_color( $d[ $k ] );
			if ( $val ) {
				$opt['colors'][ $k ] = $val;
			}
		}
	}
	update_option( 'beauty_demo_site_options', $opt, false );
} );

// ── Logo ──

add_filter( 'ydash_get_logo', function () {
	$opt = function_exists( 'beauty_demo_site_get_options' ) ? beauty_demo_site_get_options() : get_option( 'beauty_demo_site_options', array() );
	$logos = $opt['logos'] ?? array();
	$logo = $logos['header'] ?? '';
	if ( $logo && filter_var( $logo, FILTER_VALIDATE_URL ) ) {
		return $logo;
	}
	if ( is_numeric( $logo ) ) {
		$url = wp_get_attachment_image_url( (int) $logo, 'full' );
		return $url ?: '';
	}
	return '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	$url = wp_get_attachment_image_url( (int) $attachment_id, 'full' );
	if ( ! $url ) {
		return false;
	}
	$opt = function_exists( 'beauty_demo_site_get_options' ) ? beauty_demo_site_get_options() : get_option( 'beauty_demo_site_options', array() );
	if ( ! is_array( $opt ) ) {
		$opt = array();
	}
	if ( ! isset( $opt['logos'] ) ) {
		$opt['logos'] = array();
	}
	$opt['logos']['header'] = esc_url_raw( $url );
	update_option( 'beauty_demo_site_options', $opt, false );
	return true;
}, 10, 2 );

// ── Hide theme admin menu when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_menu_page( 'beauty-theme-settings' );
}, 999 );
