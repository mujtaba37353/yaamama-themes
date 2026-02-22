<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stationary_get_option( $key, $default = '' ) {
	return get_option( 'stationary_' . $key, $default );
}

function stationary_get_theme_mod( $key, $default = '' ) {
	return get_theme_mod( 'stationary_' . $key, $default );
}
