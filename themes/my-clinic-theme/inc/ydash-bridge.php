<?php
/**
 * My Clinic Theme - Yaamama Dashboard Bridge
 *
 * Maps individual my-clinic options to the unified dashboard format.
 * Uses get_option() for flat keys. No color settings (uses Customizer).
 *
 * @package MyClinic
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'my-clinic-theme',
		'theme_name'  => 'My Clinic',
		'store_type'  => 'booking',
		'version'     => wp_get_theme()->get( 'Version' ) ?: '1.0.0',
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
		'homepage_sections' => array( 'hero', 'why', 'specialties', 'banner' ),
	);
});

// ── Homepage ──

add_filter( 'ydash_get_homepage_settings', function () {
	$g = 'get_option';
	$slides = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$img = $g( 'hero_card' . $i . '_image', '' );
		if ( is_numeric( $img ) ) {
			$url = wp_get_attachment_image_url( (int) $img, 'full' );
			$slides[] = array( 'image' => (int) $img, 'image_url' => $url ?: '' );
		} else {
			$slides[] = array( 'image' => 0, 'image_url' => $img ?: '' );
		}
	}
	$why_cards = array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$why_cards[] = array(
			'icon' => $g( 'why_card' . $i . '_icon', '' ),
			'text' => $g( 'why_card' . $i . '_text', '' ),
		);
	}
	$specialties = array();
	for ( $i = 1; $i <= 6; $i++ ) {
		$specialties[] = array(
			'name' => $g( 'specialty' . $i . '_name', '' ),
			'icon' => $g( 'specialty' . $i . '_icon', '' ),
		);
	}
	return array(
		'hero' => array(
			'slides'   => $slides,
			'title'    => $g( 'hero_card1_title', '' ),
			'desc'     => $g( 'hero_card1_text', '' ),
			'btn_text' => '',
			'btn_url'  => '',
			'cards'    => array(
				array(
					'image' => $g( 'hero_card1_image', '' ),
					'title' => $g( 'hero_card1_title', '' ),
					'text'  => $g( 'hero_card1_text', '' ),
				),
				array(
					'image' => $g( 'hero_card2_image', '' ),
					'title' => $g( 'hero_card2_title', '' ),
					'text'  => $g( 'hero_card2_text', '' ),
				),
				array(
					'image' => $g( 'hero_card3_image', '' ),
					'title' => $g( 'hero_card3_title', '' ),
					'text'  => $g( 'hero_card3_text', '' ),
				),
				array(
					'image' => $g( 'hero_card4_image', '' ),
					'title' => $g( 'hero_card4_title', '' ),
					'text'  => $g( 'hero_card4_text', '' ),
				),
			),
		),
		'why' => array(
			'title' => $g( 'why_section_title', '' ),
			'cards' => $why_cards,
		),
		'specialties' => $specialties,
		'banners' => array(
			'main'         => $g( 'banner_text', '' ),
			'title'        => $g( 'banner_title', '' ),
			'image'        => $g( 'banner_image', '' ),
			'button_text'  => $g( 'banner_button_text', '' ),
			'button_link'  => $g( 'banner_button_link', '' ),
		),
	);
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	$hero = $d['hero'] ?? array();
	$cards = $hero['cards'] ?? $hero['slides'] ?? array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$c = isset( $cards[ $i - 1 ] ) ? $cards[ $i - 1 ] : array();
		if ( isset( $c['image'] ) ) {
			$img = $c['image'];
			$url = is_numeric( $img ) ? wp_get_attachment_image_url( (int) $img, 'full' ) : $img;
			update_option( 'hero_card' . $i . '_image', $url ? esc_url_raw( $url ) : '' );
		}
		if ( isset( $c['title'] ) ) {
			update_option( 'hero_card' . $i . '_title', sanitize_text_field( $c['title'] ) );
		}
		if ( isset( $c['text'] ) ) {
			update_option( 'hero_card' . $i . '_text', sanitize_textarea_field( $c['text'] ) );
		}
	}
	if ( isset( $hero['title'] ) ) {
		update_option( 'hero_card1_title', sanitize_text_field( $hero['title'] ) );
	}
	if ( isset( $hero['desc'] ) ) {
		update_option( 'hero_card1_text', sanitize_textarea_field( $hero['desc'] ) );
	}

	$why = $d['why'] ?? array();
	if ( isset( $why['title'] ) ) {
		update_option( 'why_section_title', sanitize_text_field( $why['title'] ) );
	}
	$why_cards = $why['cards'] ?? array();
	for ( $i = 1; $i <= 4; $i++ ) {
		$c = isset( $why_cards[ $i - 1 ] ) ? $why_cards[ $i - 1 ] : array();
		if ( isset( $c['icon'] ) ) {
			update_option( 'why_card' . $i . '_icon', esc_url_raw( $c['icon'] ) );
		}
		if ( isset( $c['text'] ) ) {
			update_option( 'why_card' . $i . '_text', sanitize_text_field( $c['text'] ) );
		}
	}

	$specialties = $d['specialties'] ?? array();
	for ( $i = 1; $i <= 6; $i++ ) {
		$s = isset( $specialties[ $i - 1 ] ) ? $specialties[ $i - 1 ] : array();
		if ( isset( $s['name'] ) ) {
			update_option( 'specialty' . $i . '_name', sanitize_text_field( $s['name'] ) );
		}
		if ( isset( $s['icon'] ) ) {
			update_option( 'specialty' . $i . '_icon', esc_url_raw( $s['icon'] ) );
		}
	}

	$banners = $d['banners'] ?? $d['banner'] ?? array();
	if ( isset( $banners['image'] ) ) {
		$img = $banners['image'];
		$url = is_numeric( $img ) ? wp_get_attachment_image_url( (int) $img, 'full' ) : $img;
		update_option( 'banner_image', $url ? esc_url_raw( $url ) : '' );
	}
	if ( isset( $banners['title'] ) ) {
		update_option( 'banner_title', sanitize_text_field( $banners['title'] ) );
	}
	if ( isset( $banners['main'] ) ) {
		update_option( 'banner_text', sanitize_textarea_field( $banners['main'] ) );
	}
	if ( isset( $banners['button_text'] ) ) {
		update_option( 'banner_button_text', sanitize_text_field( $banners['button_text'] ) );
	}
	if ( isset( $banners['button_link'] ) ) {
		update_option( 'banner_button_link', esc_url_raw( $banners['button_link'] ) );
	}
} );

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	$g = 'get_option';
	$mailer = $g( 'contact_email_type', 'professional' );
	return array(
		'phone'    => $g( 'footer_contact_phone', '' ),
		'email'    => $g( 'footer_contact_email', '' ),
		'whatsapp' => $g( 'footer_contact_whatsapp', '' ),
		'address'  => $g( 'footer_contact_address', '' ),
		'map_embed' => $g( 'footer_contact_map_link', '' ),
		'social'   => array(
			'facebook'  => '',
			'instagram' => '',
			'twitter'   => '',
		),
		'recipient_email' => $g( 'contact_receive_email', get_option( 'admin_email' ) ),
		'mail' => array(
			'mailer_type' => $mailer,
			'gmail'       => array(
				'email'        => $g( 'smtp_username', '' ),
				'app_password' => $g( 'smtp_password', '' ),
			),
			'smtp' => array(
				'host'       => $g( 'smtp_host', '' ),
				'port'       => $g( 'smtp_port', '587' ),
				'username'   => $g( 'smtp_username', '' ),
				'password'   => $g( 'smtp_password', '' ),
				'encryption' => $g( 'smtp_encryption', 'tls' ),
				'from_email' => $g( 'smtp_from_email', get_option( 'admin_email' ) ),
				'from_name'  => $g( 'smtp_from_name', get_bloginfo( 'name' ) ),
			),
		),
		'footer_description' => $g( 'footer_description', '' ),
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	if ( isset( $d['phone'] ) ) {
		update_option( 'footer_contact_phone', sanitize_text_field( $d['phone'] ) );
	}
	if ( isset( $d['email'] ) ) {
		update_option( 'footer_contact_email', sanitize_email( $d['email'] ) );
	}
	if ( isset( $d['whatsapp'] ) ) {
		update_option( 'footer_contact_whatsapp', sanitize_text_field( $d['whatsapp'] ) );
	}
	if ( isset( $d['address'] ) ) {
		update_option( 'footer_contact_address', sanitize_text_field( $d['address'] ) );
	}
	if ( isset( $d['map_embed'] ) ) {
		update_option( 'footer_contact_map_link', esc_url_raw( $d['map_embed'] ) );
	}
	if ( isset( $d['recipient_email'] ) ) {
		update_option( 'contact_receive_email', sanitize_email( $d['recipient_email'] ) );
	}
	if ( isset( $d['footer_description'] ) ) {
		update_option( 'footer_description', wp_kses_post( $d['footer_description'] ) );
	}

	$mail = $d['mail'] ?? array();
	if ( isset( $mail['mailer_type'] ) ) {
		update_option( 'contact_email_type', sanitize_text_field( $mail['mailer_type'] ) );
	}
	$smtp = $mail['smtp'] ?? array();
	if ( isset( $smtp['host'] ) ) {
		update_option( 'smtp_host', sanitize_text_field( $smtp['host'] ) );
	}
	if ( isset( $smtp['port'] ) ) {
		update_option( 'smtp_port', absint( $smtp['port'] ) ?: 587 );
	}
	if ( isset( $smtp['username'] ) ) {
		update_option( 'smtp_username', sanitize_text_field( $smtp['username'] ) );
	}
	if ( isset( $smtp['password'] ) && '' !== $smtp['password'] ) {
		update_option( 'smtp_password', base64_encode( sanitize_text_field( $smtp['password'] ) ) );
	}
	if ( isset( $smtp['encryption'] ) ) {
		update_option( 'smtp_encryption', sanitize_text_field( $smtp['encryption'] ) );
	}
	if ( isset( $smtp['from_email'] ) ) {
		update_option( 'smtp_from_email', sanitize_email( $smtp['from_email'] ) );
	}
	if ( isset( $smtp['from_name'] ) ) {
		update_option( 'smtp_from_name', sanitize_text_field( $smtp['from_name'] ) );
	}
	$gmail = $mail['gmail'] ?? array();
	if ( isset( $gmail['email'] ) ) {
		update_option( 'smtp_username', sanitize_email( $gmail['email'] ) );
	}
	if ( isset( $gmail['app_password'] ) && '' !== $gmail['app_password'] ) {
		update_option( 'smtp_password', base64_encode( sanitize_text_field( $gmail['app_password'] ) ) );
	}
} );

// ── Colors (not supported - uses Customizer) ──

add_filter( 'ydash_get_color_settings', function () {
	return array();
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	// No-op: theme uses Customizer for colors
} );

// ── Logo ──

add_filter( 'ydash_get_logo', function () {
	$logo = get_option( 'footer_logo', '' );
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
	if ( $url ) {
		update_option( 'footer_logo', esc_url_raw( $url ) );
		return true;
	}
	return false;
}, 10, 2 );

// ── Hide theme admin submenus when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_submenu_page( 'themes.php', 'homepage-settings' );
	remove_submenu_page( 'themes.php', 'contact-settings' );
}, 999 );
