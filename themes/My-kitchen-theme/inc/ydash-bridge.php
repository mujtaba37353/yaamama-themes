<?php
/**
 * My Kitchen Theme - Yaamama Dashboard Bridge
 *
 * Maps mykitchen_* options to the unified dashboard format.
 *
 * @package My_Kitchen_Theme
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'My-kitchen-theme',
		'theme_name'  => 'My Kitchen',
		'store_type'  => 'retail',
		'version'     => '1.0.0',
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
			array( 'id' => 'main_color',        'label' => 'اللون الأساسي',          'default' => '#3a0506' ),
			array( 'id' => 'secondary_color',   'label' => 'اللون الثانوي',          'default' => '#542525' ),
			array( 'id' => 'background_color',  'label' => 'لون خلفية المكونات',    'default' => '#ffffff' ),
			array( 'id' => 'body_background',   'label' => 'خلفية الموقع',          'default' => '#fff3f3' ),
		),
		'homepage_sections' => array( 'hero', 'banners', 'phrases' ),
	);
});

// ── Homepage ──

add_filter( 'ydash_get_homepage_settings', function () {
	$h = function_exists( 'mykitchen_get_homepage_settings' ) ? mykitchen_get_homepage_settings() : get_option( 'mykitchen_homepage_settings', array() );
	return is_array( $h ) ? $h : array();
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	if ( is_array( $d ) && ! empty( $d ) ) {
		$saved = get_option( 'mykitchen_homepage_settings', array() );
		$saved = is_array( $saved ) ? $saved : array();
		update_option( 'mykitchen_homepage_settings', array_merge( $saved, $d ) );
	}
} );

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	$c = function_exists( 'mykitchen_get_contact_settings' ) ? mykitchen_get_contact_settings() : get_option( 'mykitchen_contact_settings', array() );
	$c = is_array( $c ) ? $c : array();
	return array(
		'hero_title'      => $c['hero_title'] ?? '',
		'contact_heading' => $c['contact_heading'] ?? '',
		'label_name'      => $c['label_name'] ?? '',
		'label_email'     => $c['label_email'] ?? '',
		'label_phone'     => $c['label_phone'] ?? '',
		'label_topic'     => $c['label_topic'] ?? '',
		'label_message'   => $c['label_message'] ?? '',
		'label_submit'    => $c['label_submit'] ?? '',
		'address'         => $c['address'] ?? '',
		'phone'           => $c['phone'] ?? '',
		'whatsapp'        => $c['whatsapp'] ?? '',
		'email'           => $c['email'] ?? get_option( 'admin_email', '' ),
		'visit_title_1'   => $c['visit_title_1'] ?? '',
		'visit_title_2'   => $c['visit_title_2'] ?? '',
		'map_embed'       => $c['map_embed'] ?? '',
		'recipient_email' => $c['recipient_email'] ?? '',
		'from_name'       => $c['from_name'] ?? '',
		'from_email'      => $c['from_email'] ?? '',
		'smtp_enabled'    => $c['smtp_enabled'] ?? 'no',
		'smtp_mode'       => $c['smtp_mode'] ?? 'gmail',
		'smtp_host'       => $c['smtp_host'] ?? '',
		'smtp_port'       => $c['smtp_port'] ?? '587',
		'smtp_encryption' => $c['smtp_encryption'] ?? 'tls',
		'smtp_username'   => $c['smtp_username'] ?? '',
		'smtp_password'   => $c['smtp_password'] ?? '',
		'gmail_address'   => $c['gmail_address'] ?? '',
		'gmail_app_password' => $c['gmail_app_password'] ?? '',
		'social'         => array(
			'facebook'  => $c['facebook'] ?? '',
			'instagram' => $c['instagram'] ?? '',
			'twitter'    => $c['twitter'] ?? '',
		),
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	$saved = get_option( 'mykitchen_contact_settings', array() );
	$saved = is_array( $saved ) ? $saved : array();
	$fields = array(
		'hero_title', 'contact_heading', 'label_name', 'label_email', 'label_phone', 'label_topic',
		'label_message', 'label_submit', 'address', 'phone', 'whatsapp', 'email', 'visit_title_1',
		'visit_title_2', 'map_embed', 'recipient_email', 'from_name', 'from_email', 'smtp_enabled',
		'smtp_mode', 'smtp_host', 'smtp_port', 'smtp_encryption', 'smtp_username', 'smtp_password',
		'gmail_address', 'gmail_app_password',
	);
	foreach ( $fields as $f ) {
		if ( isset( $d[ $f ] ) ) {
			if ( in_array( $f, array( 'email', 'recipient_email', 'from_email', 'gmail_address' ), true ) ) {
				$saved[ $f ] = sanitize_email( $d[ $f ] );
			} elseif ( 'map_embed' === $f ) {
				$saved[ $f ] = esc_url_raw( $d[ $f ] );
			} else {
				$saved[ $f ] = sanitize_text_field( $d[ $f ] );
			}
		}
	}
	if ( isset( $d['social'] ) && is_array( $d['social'] ) ) {
		foreach ( array( 'facebook', 'instagram', 'twitter' ) as $k ) {
			if ( isset( $d['social'][ $k ] ) ) {
				$saved[ $k ] = esc_url_raw( $d['social'][ $k ] );
			}
		}
	}
	update_option( 'mykitchen_contact_settings', $saved );
} );

// ── Colors (mykitchen_site_settings) ──

add_filter( 'ydash_get_color_settings', function () {
	$s = function_exists( 'mykitchen_get_site_settings' ) ? mykitchen_get_site_settings() : get_option( 'mykitchen_site_settings', array() );
	return array(
		'main_color'        => $s['main_color'] ?? '#3a0506',
		'secondary_color'   => $s['secondary_color'] ?? '#542525',
		'background_color'  => $s['background_color'] ?? '#ffffff',
		'body_background'   => $s['body_background'] ?? '#fff3f3',
	);
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	$saved = get_option( 'mykitchen_site_settings', array() );
	$saved = is_array( $saved ) ? $saved : array();
	$keys = array( 'main_color', 'secondary_color', 'background_color', 'body_background' );
	foreach ( $keys as $k ) {
		if ( isset( $d[ $k ] ) ) {
			$saved[ $k ] = sanitize_hex_color( $d[ $k ] ) ?: ( $saved[ $k ] ?? '' );
		}
	}
	update_option( 'mykitchen_site_settings', $saved );
} );

// ── Logo (from mykitchen_site_settings or custom_logo) ──

add_filter( 'ydash_get_logo', function () {
	$s = function_exists( 'mykitchen_get_site_settings' ) ? mykitchen_get_site_settings() : get_option( 'mykitchen_site_settings', array() );
	$logo = $s['logo'] ?? '';
	if ( is_numeric( $logo ) && (int) $logo > 0 ) {
		$url = wp_get_attachment_image_url( (int) $logo, 'full' );
		return $url ? $url : '';
	}
	if ( ! empty( $logo ) && is_string( $logo ) ) {
		return esc_url( $logo );
	}
	$custom_logo_id = get_theme_mod( 'custom_logo', 0 );
	return $custom_logo_id ? wp_get_attachment_image_url( $custom_logo_id, 'full' ) : '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	$saved = get_option( 'mykitchen_site_settings', array() );
	$saved = is_array( $saved ) ? $saved : array();
	$saved['logo'] = (int) $attachment_id;
	update_option( 'mykitchen_site_settings', $saved );
	return true;
}, 10, 2 );

// ── Hide theme admin pages when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_menu_page( 'mykitchen-content' );
}, 999 );
