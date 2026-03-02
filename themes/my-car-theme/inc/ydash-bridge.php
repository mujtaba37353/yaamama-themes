<?php
/**
 * My Car Theme - Yaamama Dashboard Bridge
 *
 * Maps my_car_theme_* array options to the unified dashboard format.
 *
 * @package my-car-theme
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	$theme = wp_get_theme();
	return array(
		'theme_id'    => 'my-car-theme',
		'theme_name'  => 'My Car',
		'store_type'  => 'rental',
		'version'     => $theme->get( 'Version' ) ?: '1.0.0',
		'supports'    => array(
			'homepage_settings' => false,
			'contact_settings'  => true,
			'about_page'        => true,
			'privacy_page'      => true,
			'terms_page'        => true,
			'colors'            => false,
			'logo'              => true,
		),
		'color_fields' => array(),
		'homepage_sections' => array(),
	);
});

// ── Homepage (minimal – theme uses static front page) ──

add_filter( 'ydash_get_homepage_settings', function () {
	return array();
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	// No homepage settings for my-car-theme.
}, 10, 1 );

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	$c = class_exists( 'My_Car_Theme_Contact_Settings' ) && method_exists( 'My_Car_Theme_Contact_Settings', 'get_all_settings' )
		? My_Car_Theme_Contact_Settings::get_all_settings()
		: get_option( 'my_car_theme_contact_settings', array() );
	$phone = isset( $c['phone_1'] ) ? $c['phone_1'] : '';
	if ( empty( $phone ) && isset( $c['phone_2'] ) ) {
		$phone = $c['phone_2'];
	}
	return array(
		'phone'    => $phone,
		'email'    => isset( $c['email'] ) ? $c['email'] : '',
		'whatsapp' => isset( $c['whatsapp_number'] ) ? $c['whatsapp_number'] : '',
		'address'  => isset( $c['address'] ) ? $c['address'] : '',
		'social'   => array(
			'facebook'  => '',
			'instagram' => '',
			'twitter'   => '',
		),
		'recipient_email' => isset( $c['recipient_email'] ) ? $c['recipient_email'] : '',
		'mail' => array(
			'mailer_type' => isset( $c['smtp_type'] ) ? $c['smtp_type'] : 'gmail',
			'gmail'       => array(
				'email'        => isset( $c['smtp_from_email'] ) ? $c['smtp_from_email'] : '',
				'app_password' => isset( $c['smtp_password'] ) ? $c['smtp_password'] : '',
				'client_id'    => isset( $c['gmail_client_id'] ) ? $c['gmail_client_id'] : '',
				'client_secret' => isset( $c['gmail_client_secret'] ) ? $c['gmail_client_secret'] : '',
			),
			'smtp' => array(
				'host'       => isset( $c['smtp_host'] ) ? $c['smtp_host'] : '',
				'port'       => isset( $c['smtp_port'] ) ? $c['smtp_port'] : '587',
				'username'   => isset( $c['smtp_username'] ) ? $c['smtp_username'] : '',
				'password'   => isset( $c['smtp_password'] ) ? $c['smtp_password'] : '',
				'encryption' => isset( $c['smtp_encryption'] ) ? $c['smtp_encryption'] : 'tls',
				'from_email' => isset( $c['smtp_from_email'] ) ? $c['smtp_from_email'] : '',
				'from_name'  => isset( $c['smtp_from_name'] ) ? $c['smtp_from_name'] : '',
			),
		),
		'floating' => array(
			'whatsapp' => isset( $c['whatsapp_number'] ) ? $c['whatsapp_number'] : '',
			'call'     => $phone,
			'enabled'  => ! empty( $c['whatsapp_enabled'] ),
		),
		'sidebar_image' => 0,
		'company_name' => isset( $c['company_name'] ) ? $c['company_name'] : '',
		'working_hours' => isset( $c['working_hours'] ) ? $c['working_hours'] : '',
		'map_link' => isset( $c['map_link'] ) ? $c['map_link'] : '',
		'whatsapp_message' => isset( $c['whatsapp_message'] ) ? $c['whatsapp_message'] : '',
		'whatsapp_position' => isset( $c['whatsapp_position'] ) ? $c['whatsapp_position'] : 'left',
		'email_subject_prefix' => isset( $c['email_subject_prefix'] ) ? $c['email_subject_prefix'] : '',
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	$c = get_option( 'my_car_theme_contact_settings', array() );
	if ( ! is_array( $c ) ) {
		$c = array();
	}

	if ( isset( $d['phone'] ) ) {
		$c['phone_1'] = sanitize_text_field( $d['phone'] );
	}
	if ( isset( $d['email'] ) ) {
		$c['email'] = sanitize_email( $d['email'] );
	}
	if ( isset( $d['whatsapp'] ) ) {
		$c['whatsapp_number'] = sanitize_text_field( $d['whatsapp'] );
	}
	if ( isset( $d['address'] ) ) {
		$c['address'] = sanitize_text_field( $d['address'] );
	}
	if ( isset( $d['recipient_email'] ) ) {
		$c['recipient_email'] = sanitize_email( $d['recipient_email'] );
	}

	$floating = $d['floating'] ?? array();
	if ( isset( $floating['whatsapp'] ) ) {
		$c['whatsapp_number'] = sanitize_text_field( $floating['whatsapp'] );
	}
	if ( isset( $floating['call'] ) ) {
		$c['phone_1'] = sanitize_text_field( $floating['call'] );
	}
	if ( isset( $floating['enabled'] ) ) {
		$c['whatsapp_enabled'] = $floating['enabled'];
	}

	$mail = $d['mail'] ?? array();
	$gmail = $mail['gmail'] ?? array();
	if ( isset( $gmail['email'] ) ) {
		$c['smtp_from_email'] = sanitize_email( $gmail['email'] );
	}
	if ( isset( $gmail['app_password'] ) ) {
		$c['smtp_password'] = sanitize_text_field( $gmail['app_password'] );
	}
	if ( isset( $gmail['client_id'] ) ) {
		$c['gmail_client_id'] = sanitize_text_field( $gmail['client_id'] );
	}
	if ( isset( $gmail['client_secret'] ) ) {
		$c['gmail_client_secret'] = sanitize_text_field( $gmail['client_secret'] );
	}

	$smtp = $mail['smtp'] ?? array();
	if ( isset( $smtp['host'] ) ) {
		$c['smtp_host'] = sanitize_text_field( $smtp['host'] );
	}
	if ( isset( $smtp['port'] ) ) {
		$c['smtp_port'] = absint( $smtp['port'] );
	}
	if ( isset( $smtp['username'] ) ) {
		$c['smtp_username'] = sanitize_text_field( $smtp['username'] );
	}
	if ( isset( $smtp['password'] ) ) {
		$c['smtp_password'] = sanitize_text_field( $smtp['password'] );
	}
	if ( isset( $smtp['encryption'] ) ) {
		$c['smtp_encryption'] = sanitize_text_field( $smtp['encryption'] );
	}
	if ( isset( $smtp['from_email'] ) ) {
		$c['smtp_from_email'] = sanitize_email( $smtp['from_email'] );
	}
	if ( isset( $smtp['from_name'] ) ) {
		$c['smtp_from_name'] = sanitize_text_field( $smtp['from_name'] );
	}

	if ( isset( $d['company_name'] ) ) {
		$c['company_name'] = sanitize_text_field( $d['company_name'] );
	}
	if ( isset( $d['working_hours'] ) ) {
		$c['working_hours'] = sanitize_text_field( $d['working_hours'] );
	}
	if ( isset( $d['map_link'] ) ) {
		$c['map_link'] = esc_url_raw( $d['map_link'] );
	}
	if ( isset( $d['whatsapp_message'] ) ) {
		$c['whatsapp_message'] = sanitize_textarea_field( $d['whatsapp_message'] );
	}
	if ( isset( $d['whatsapp_position'] ) ) {
		$c['whatsapp_position'] = sanitize_text_field( $d['whatsapp_position'] );
	}
	if ( isset( $d['email_subject_prefix'] ) ) {
		$c['email_subject_prefix'] = sanitize_text_field( $d['email_subject_prefix'] );
	}

	update_option( 'my_car_theme_contact_settings', $c );
});

// ── Colors (not supported by my-car-theme) ──

add_filter( 'ydash_get_color_settings', function () {
	return array();
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	// No color settings for my-car-theme.
}, 10, 1 );

// ── Logo ──

add_filter( 'ydash_get_logo', function () {
	$id = (int) get_theme_mod( 'custom_logo', 0 );
	return $id && wp_attachment_is_image( $id ) ? wp_get_attachment_url( $id ) : '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	set_theme_mod( 'custom_logo', (int) $attachment_id );
	return true;
}, 10, 2 );

// ── Hide theme admin menu when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_submenu_page( 'themes.php', 'my-car-contact-settings' );
}, 999 );
