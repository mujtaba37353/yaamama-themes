<?php
/**
 * Dark Theme - Yaamama Dashboard Bridge
 *
 * Maps dark_theme_* options to the unified dashboard format.
 *
 * @package Dark_Theme
 */

defined( 'ABSPATH' ) || exit;

add_filter( 'ydash_theme_manifest', function () {
	return array(
		'theme_id'    => 'dark-theme',
		'theme_name'  => 'Dark Theme',
		'store_type'  => 'retail',
		'version'     => defined( 'DARK_THEME_VERSION' ) ? DARK_THEME_VERSION : '1.0.0',
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
			array( 'id' => 'button_primary',    'label' => 'لون الزر الأساسي',     'default' => '#A4825D' ),
			array( 'id' => 'header_background',  'label' => 'خلفية الهيدر',        'default' => '#A3B993' ),
			array( 'id' => 'footer_background',  'label' => 'خلفية الفوتر',        'default' => '#A3B993' ),
			array( 'id' => 'product_card_bg',     'label' => 'خلفية بطاقة المنتج',  'default' => '#E5CBAD' ),
		),
		'homepage_sections' => array( 'hero', 'category', 'featured', 'promo', 'products', 'offers', 'reviews' ),
	);
});

// ── Homepage ──

add_filter( 'ydash_get_homepage_settings', function () {
	$raw = function_exists( 'dark_theme_get_home_content_raw' ) ? dark_theme_get_home_content_raw() : get_option( 'dark_theme_home_content', array() );
	$reviews = get_option( 'dark_theme_home_reviews', array() );
	$demo_ids = get_option( 'dark_theme_demo_product_ids', array() );
	return array(
		'hero'       => array(
			'header_image'    => isset( $raw['header_image'] ) ? (int) $raw['header_image'] : 0,
			'header_title'    => $raw['header_title'] ?? '',
			'header_text'     => $raw['header_text'] ?? '',
			'header_btn_text' => $raw['header_btn_text'] ?? '',
		),
		'category'   => array(
			'section_image' => isset( $raw['category_section_image'] ) ? (int) $raw['category_section_image'] : 0,
			'heading'       => $raw['category_heading'] ?? '',
			'cat1_image'    => isset( $raw['cat1_image'] ) ? (int) $raw['cat1_image'] : 0,
			'cat1_title'    => $raw['cat1_title'] ?? '',
			'cat2_image'    => isset( $raw['cat2_image'] ) ? (int) $raw['cat2_image'] : 0,
			'cat2_title'    => $raw['cat2_title'] ?? '',
			'cat3_image'    => isset( $raw['cat3_image'] ) ? (int) $raw['cat3_image'] : 0,
			'cat3_title'    => $raw['cat3_title'] ?? '',
			'cat4_image'    => isset( $raw['cat4_image'] ) ? (int) $raw['cat4_image'] : 0,
			'cat4_title'    => $raw['cat4_title'] ?? '',
			'cat5_image'    => isset( $raw['cat5_image'] ) ? (int) $raw['cat5_image'] : 0,
			'cat5_title'    => $raw['cat5_title'] ?? '',
		),
		'featured'   => array(
			'section_image' => isset( $raw['products_section_image'] ) ? (int) $raw['products_section_image'] : 0,
			'heading'       => $raw['products_heading'] ?? '',
			'banner_image'  => isset( $raw['products_banner_image'] ) ? (int) $raw['products_banner_image'] : 0,
		),
		'promo'      => array(
			'section_image' => isset( $raw['offers_section_image'] ) ? (int) $raw['offers_section_image'] : 0,
			'heading'       => $raw['offers_heading'] ?? '',
			'offers_image'  => isset( $raw['offers_image'] ) ? (int) $raw['offers_image'] : 0,
		),
		'products'   => array(),
		'offers'     => array(),
		'reviews'    => is_array( $reviews ) ? $reviews : array(),
		'demo_product_ids' => is_array( $demo_ids ) ? $demo_ids : array(),
		'raw'        => is_array( $raw ) ? $raw : array(),
	);
});

add_action( 'ydash_save_homepage_settings', function ( $d ) {
	$raw = get_option( 'dark_theme_home_content', array() );
	$raw = is_array( $raw ) ? $raw : array();

	$hero = $d['hero'] ?? array();
	if ( isset( $hero['header_image'] ) )    $raw['header_image'] = (int) $hero['header_image'];
	if ( isset( $hero['header_title'] ) )     $raw['header_title'] = sanitize_text_field( $hero['header_title'] );
	if ( isset( $hero['header_text'] ) )      $raw['header_text'] = sanitize_textarea_field( $hero['header_text'] );
	if ( isset( $hero['header_btn_text'] ) )  $raw['header_btn_text'] = sanitize_text_field( $hero['header_btn_text'] );

	$category = $d['category'] ?? array();
	if ( isset( $category['section_image'] ) ) $raw['category_section_image'] = (int) $category['section_image'];
	if ( isset( $category['heading'] ) )       $raw['category_heading'] = sanitize_text_field( $category['heading'] );
	foreach ( array( 1, 2, 3, 4, 5 ) as $i ) {
		if ( isset( $category[ 'cat' . $i . '_image' ] ) ) $raw[ 'cat' . $i . '_image' ] = (int) $category[ 'cat' . $i . '_image' ];
		if ( isset( $category[ 'cat' . $i . '_title' ] ) ) $raw[ 'cat' . $i . '_title' ] = sanitize_text_field( $category[ 'cat' . $i . '_title' ] );
	}

	$featured = $d['featured'] ?? array();
	if ( isset( $featured['section_image'] ) ) $raw['products_section_image'] = (int) $featured['section_image'];
	if ( isset( $featured['heading'] ) )       $raw['products_heading'] = sanitize_text_field( $featured['heading'] );
	if ( isset( $featured['banner_image'] ) )  $raw['products_banner_image'] = (int) $featured['banner_image'];

	$promo = $d['promo'] ?? array();
	if ( isset( $promo['section_image'] ) ) $raw['offers_section_image'] = (int) $promo['section_image'];
	if ( isset( $promo['heading'] ) )       $raw['offers_heading'] = sanitize_text_field( $promo['heading'] );
	if ( isset( $promo['offers_image'] ) )  $raw['offers_image'] = (int) $promo['offers_image'];

	if ( isset( $d['reviews'] ) && is_array( $d['reviews'] ) ) {
		update_option( 'dark_theme_home_reviews', $d['reviews'] );
	}
	if ( isset( $d['demo_product_ids'] ) && is_array( $d['demo_product_ids'] ) ) {
		update_option( 'dark_theme_demo_product_ids', array_map( 'absint', $d['demo_product_ids'] ) );
	}
	if ( isset( $d['raw'] ) && is_array( $d['raw'] ) ) {
		$raw = array_merge( $raw, $d['raw'] );
	}

	update_option( 'dark_theme_home_content', $raw );
} );

// ── Contact ──

add_filter( 'ydash_get_contact_settings', function () {
	return array(
		'phone'    => get_option( 'dark_theme_contact_phone', '' ),
		'email'    => get_option( 'dark_theme_contact_email', get_option( 'admin_email', '' ) ),
		'whatsapp' => get_option( 'dark_theme_contact_whatsapp', '' ),
		'address'  => get_option( 'dark_theme_contact_address', '' ),
		'map_embed' => get_option( 'dark_theme_contact_map_embed', '' ),
		'social'   => array(
			'facebook'  => get_option( 'dark_theme_facebook', '' ),
			'instagram' => get_option( 'dark_theme_instagram', '' ),
			'twitter'   => get_option( 'dark_theme_twitter', '' ),
			'snapchat'  => get_option( 'dark_theme_snapchat', '' ),
			'tiktok'    => get_option( 'dark_theme_tiktok', '' ),
		),
	);
});

add_action( 'ydash_save_contact_settings', function ( $d ) {
	$fields = array( 'phone', 'email', 'whatsapp', 'address', 'map_embed' );
	foreach ( $fields as $f ) {
		if ( isset( $d[ $f ] ) ) {
			$opt = 'dark_theme_contact_' . ( 'map_embed' === $f ? 'map_embed' : $f );
			if ( 'map_embed' === $f ) {
				update_option( 'dark_theme_contact_map_embed', esc_url_raw( $d[ $f ] ) );
			} elseif ( 'email' === $f ) {
				update_option( 'dark_theme_contact_email', sanitize_email( $d[ $f ] ) );
			} else {
				update_option( 'dark_theme_contact_' . $f, sanitize_text_field( $d[ $f ] ) );
			}
		}
	}
	if ( isset( $d['social'] ) && is_array( $d['social'] ) ) {
		$map = array( 'facebook' => 'dark_theme_facebook', 'instagram' => 'dark_theme_instagram', 'twitter' => 'dark_theme_twitter', 'snapchat' => 'dark_theme_snapchat', 'tiktok' => 'dark_theme_tiktok' );
		foreach ( $map as $k => $opt ) {
			if ( isset( $d['social'][ $k ] ) ) {
				update_option( $opt, esc_url_raw( $d['social'][ $k ] ) );
			}
		}
	}
} );

// ── Colors ──

add_filter( 'ydash_get_color_settings', function () {
	$c = function_exists( 'dark_theme_get_site_colors' ) ? dark_theme_get_site_colors() : get_option( 'dark_theme_site_colors', array() );
	return array(
		'button_primary'    => $c['button_primary'] ?? '#A4825D',
		'header_background' => $c['header_background'] ?? '#A3B993',
		'footer_background' => $c['footer_background'] ?? '#A3B993',
		'product_card_bg'   => $c['product_card_bg'] ?? '#E5CBAD',
	);
});

add_action( 'ydash_save_color_settings', function ( $d ) {
	$saved = get_option( 'dark_theme_site_colors', array() );
	$saved = is_array( $saved ) ? $saved : array();
	$keys = array( 'button_primary', 'header_background', 'footer_background', 'product_card_bg' );
	foreach ( $keys as $k ) {
		if ( isset( $d[ $k ] ) ) {
			$saved[ $k ] = sanitize_hex_color( $d[ $k ] ) ?: ( $saved[ $k ] ?? '' );
		}
	}
	update_option( 'dark_theme_site_colors', $saved );
} );

// ── Logo ──

add_filter( 'ydash_get_logo', function () {
	$id = (int) get_theme_mod( 'custom_logo', 0 );
	return $id ? wp_get_attachment_image_url( $id, 'full' ) : '';
});

add_filter( 'ydash_save_logo', function ( $prev, $attachment_id ) {
	set_theme_mod( 'custom_logo', (int) $attachment_id );
	return true;
}, 10, 2 );

// ── Hide theme admin pages when dashboard is active ──

add_action( 'admin_menu', function () {
	if ( ! class_exists( 'Yaamama_Theme_Bridge' ) ) {
		return;
	}
	remove_menu_page( 'dark-theme-content' );
}, 999 );
