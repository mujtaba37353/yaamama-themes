<?php
/**
 * Template Name: تسجيل الخروج (Logout)
 * Elegance - Logout bridge page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

wp_logout();
wp_safe_redirect( elegance_page_url( 'login', '/login/' ) );
exit;

