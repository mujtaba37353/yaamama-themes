<?php
/**
 * Beauty Care Theme — نظام المفضلة (Wishlist)
 *
 * التخزين: cookie للزوار، user_meta للمسجلين. مزامنة من cookie إلى user_meta عند تسجيل الدخول.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * ترجع مصفوفة IDs المنتجات المفضلة (من cookie و user_meta للمسجلين).
 *
 * @return int[]
 */
function beauty_care_get_wishlist_ids() {
	$ids = array();
	if ( ! empty( $_COOKIE['beauty_care_wishlist'] ) ) {
		$decoded = json_decode( wp_unslash( $_COOKIE['beauty_care_wishlist'] ), true );
		if ( is_array( $decoded ) ) {
			$ids = array_merge( $ids, $decoded );
		}
	}
	if ( is_user_logged_in() ) {
		$meta = get_user_meta( get_current_user_id(), 'beauty_care_wishlist', true );
		if ( is_array( $meta ) ) {
			$ids = array_merge( $ids, $meta );
		}
	}
	$ids = array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );
	return $ids;
}

/**
 * مزامنة المفضلة من cookie إلى user_meta عند تسجيل الدخول.
 */
function beauty_care_sync_wishlist_from_cookie() {
	if ( ! is_user_logged_in() || empty( $_COOKIE['beauty_care_wishlist'] ) ) {
		return;
	}
	$decoded = json_decode( wp_unslash( $_COOKIE['beauty_care_wishlist'] ), true );
	if ( ! is_array( $decoded ) ) {
		return;
	}
	$ids = array_values( array_unique( array_filter( array_map( 'absint', $decoded ) ) ) );
	update_user_meta( get_current_user_id(), 'beauty_care_wishlist', $ids );
}
add_action( 'init', 'beauty_care_sync_wishlist_from_cookie', 5 );

/**
 * رابط صفحة المفضلة.
 *
 * @return string
 */
function beauty_care_wishlist_permalink() {
	return home_url( '/wishlist' );
}
