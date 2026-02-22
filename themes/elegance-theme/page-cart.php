<?php
/**
 * Cart page template override (forces theme Woo templates).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( function_exists( 'WC' ) && WC()->cart && WC()->cart->is_empty() ) {
	if ( function_exists( 'wc_get_template' ) ) {
		wc_get_template( 'cart/cart-empty.php' );
	}
} else {
	if ( function_exists( 'wc_get_template' ) ) {
		wc_get_template( 'cart/cart.php' );
	}
}

get_footer();

