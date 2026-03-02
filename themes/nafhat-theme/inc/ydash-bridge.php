<?php
/**
 * Nafhat Theme - Yaamama Dashboard Bridge
 *
 * Maps nafhat_* options and theme mods to the unified dashboard format.
 * Uses add_theme_page (no top-level admin menu).
 *
 * @package Nafhat
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'nafhat-theme',
		'theme_name'  => 'Nafhat',
		'store_type'  => 'retail',
		'version'     => '1.0.0',
		'supports'    => array(
			'homepage_settings' => true,
			'contact_settings'  => true,
			'about_page'        => true,
			'privacy_page'      => true,
			'terms_page'        => true,
			'colors'            => false,
			'logo'              => true,
		),
		'color_fields' => array(),
		'homepage_sections' => array( 'hero_slides', 'secondary_banners', 'third_banner' ),
	);
});

// ── Homepage ──

add_filter( 'ydash_get_homepage_settings', function () {
	$h = function_exists( 'nafhat_get_homepage_settings' ) ? nafhat_get_homepage_settings() : get_option( 'nafhat_homepage_settings', array() );
	return is_array( $h ) ? $h : array();
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	if ( is_array( $d ) && ! empty( $d ) ) {
		$saved = get_option( 'nafhat_homepage_settings', array() );
		$saved = is_array( $saved ) ? $saved : array();
		update_option( 'nafhat_homepage_settings', array_merge( $saved, $d ) );
	}
} );

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	$c = function_exists( 'nafhat_get_contact_settings' ) ? nafhat_get_contact_settings() : get_option( 'nafhat_contact_settings', array() );
	$c = is_array( $c ) ? $c : array();
	return array(
		'email_type'         => $c['email_type'] ?? 'default',
		'contact_email'      => $c['contact_email'] ?? get_option( 'admin_email', '' ),
		'gmail_email'        => $c['gmail_email'] ?? '',
		'gmail_app_password' => $c['gmail_app_password'] ?? '',
		'smtp_host'          => $c['smtp_host'] ?? '',
		'smtp_port'          => $c['smtp_port'] ?? '587',
		'smtp_username'      => $c['smtp_username'] ?? '',
		'smtp_password'      => $c['smtp_password'] ?? '',
		'smtp_encryption'    => $c['smtp_encryption'] ?? 'tls',
		'smtp_from_email'    => $c['smtp_from_email'] ?? '',
		'smtp_from_name'     => $c['smtp_from_name'] ?? get_bloginfo( 'name', 'raw' ),
		'address'            => $c['address'] ?? '',
		'phone'              => $c['phone'] ?? '',
		'display_email'      => $c['display_email'] ?? '',
		'instagram'          => $c['instagram'] ?? '',
		'facebook'           => $c['facebook'] ?? '',
		'snapchat'           => $c['snapchat'] ?? '',
		'twitter'            => $c['twitter'] ?? '',
		'tiktok'             => $c['tiktok'] ?? '',
		'whatsapp_number'    => $c['whatsapp_number'] ?? '',
		'whatsapp_message'   => $c['whatsapp_message'] ?? '',
		'whatsapp_enabled'   => ! empty( $c['whatsapp_enabled'] ),
		'map_embed_url'      => $c['map_embed_url'] ?? '',
		'social'             => array(
			'facebook'  => $c['facebook'] ?? '',
			'instagram' => $c['instagram'] ?? '',
			'twitter'   => $c['twitter'] ?? '',
			'snapchat'  => $c['snapchat'] ?? '',
			'tiktok'    => $c['tiktok'] ?? '',
		),
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	$saved = get_option( 'nafhat_contact_settings', array() );
	$saved = is_array( $saved ) ? $saved : array();
	$fields = array(
		'email_type', 'contact_email', 'gmail_email', 'gmail_app_password',
		'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption',
		'smtp_from_email', 'smtp_from_name', 'address', 'phone', 'display_email',
		'instagram', 'facebook', 'snapchat', 'twitter', 'tiktok',
		'whatsapp_number', 'whatsapp_message', 'map_embed_url',
	);
	foreach ( $fields as $f ) {
		if ( isset( $d[ $f ] ) ) {
			if ( in_array( $f, array( 'contact_email', 'gmail_email', 'smtp_from_email', 'display_email' ), true ) ) {
				$saved[ $f ] = sanitize_email( $d[ $f ] );
			} elseif ( in_array( $f, array( 'map_embed_url', 'instagram', 'facebook', 'snapchat', 'twitter', 'tiktok' ), true ) ) {
				$saved[ $f ] = esc_url_raw( $d[ $f ] );
			} else {
				$saved[ $f ] = sanitize_text_field( $d[ $f ] );
			}
		}
	}
	if ( isset( $d['whatsapp_enabled'] ) ) {
		$saved['whatsapp_enabled'] = ! empty( $d['whatsapp_enabled'] );
	}
	if ( isset( $d['social'] ) && is_array( $d['social'] ) ) {
		foreach ( array( 'facebook', 'instagram', 'twitter', 'snapchat', 'tiktok' ) as $k ) {
			if ( isset( $d['social'][ $k ] ) ) {
				$saved[ $k ] = esc_url_raw( $d['social'][ $k ] );
			}
		}
	}
	update_option( 'nafhat_contact_settings', $saved );
} );

// ── Colors (not supported) ──

add_filter( 'ydash_get_color_settings', function () {
	return array();
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	// No-op: nafhat does not support color settings via dashboard.
} );

// ── Logo (theme mod: custom_logo, nafhat_contact_email) ──

add_filter( 'ydash_get_logo', function () {
	$custom_logo_id = get_theme_mod( 'custom_logo', 0 );
	if ( $custom_logo_id ) {
		$url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
		return $url ? $url : '';
	}
	return '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	set_theme_mod( 'custom_logo', (int) $attachment_id );
	return true;
}, 10, 2 );

// ── Hide theme admin pages when dashboard is active (add_theme_page submenus) ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_submenu_page( 'themes.php', 'nafhat-homepage-settings' );
	remove_submenu_page( 'themes.php', 'nafhat-contact-settings' );
	remove_submenu_page( 'themes.php', 'nafhat-theme-pages' );
}, 999 );
