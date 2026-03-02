<?php
/**
 * Ahmadi Theme - Yaamama Dashboard Bridge
 *
 * Minimal bridge: only ahmadi_theme_pages_created and ahmadi_demo_content_state flags.
 * No homepage, contact, footer, about, or color options.
 *
 * @package Ahmadi_Theme
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'ahmadi-theme',
		'theme_name'  => 'Ahmadi',
		'store_type'  => 'retail',
		'version'     => '1.0.0',
		'supports'    => array(
			'homepage_settings' => false,
			'contact_settings'  => false,
			'about_page'        => false,
			'privacy_page'      => false,
			'terms_page'        => false,
			'colors'            => false,
			'logo'              => true,
		),
		'color_fields' => array(),
		'homepage_sections' => array(),
	);
});

// ── Homepage (no-op) ──

add_filter( 'ydash_get_homepage_settings', function () {
	return array();
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	// No-op: ahmadi does not support homepage settings via dashboard.
} );

// ── Contact (no-op) ──

add_filter( 'ydash_get_contact_settings', function () {
	return array();
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	// No-op: ahmadi does not support contact settings via dashboard.
} );

// ── Colors (no-op) ──

add_filter( 'ydash_get_color_settings', function () {
	return array();
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	// No-op: ahmadi does not support color settings via dashboard.
} );

// ── Logo (ahmadi uses ahmadi_site_logo post type) ──

add_filter( 'ydash_get_logo', function () {
	return function_exists( 'ahmadi_theme_get_site_logo_url' ) ? ahmadi_theme_get_site_logo_url() : '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	$attachment_id = (int) $attachment_id;
	if ( $attachment_id <= 0 ) {
		return true;
	}
	$url = wp_get_attachment_image_url( $attachment_id, 'full' );
	if ( ! $url ) {
		return true;
	}
	$logo_post = function_exists( 'ahmadi_theme_get_latest_post' ) ? ahmadi_theme_get_latest_post( 'ahmadi_site_logo' ) : null;
	if ( $logo_post ) {
		update_post_meta( $logo_post->ID, 'ahmadi_site_logo_url', $url );
	} else {
		$post_id = wp_insert_post( array(
			'post_type'   => 'ahmadi_site_logo',
			'post_title'  => 'شعار الموقع',
			'post_status' => 'publish',
		) );
		if ( $post_id && ! is_wp_error( $post_id ) ) {
			update_post_meta( $post_id, 'ahmadi_site_logo_url', $url );
		}
	}
	return true;
}, 10, 2 );

// ── Hide theme admin pages when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_menu_page( 'ahmadi-site-content' );
}, 999 );
