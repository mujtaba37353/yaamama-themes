<?php
/**
 * Beauty Time — page-specific enqueues
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Enqueue page-specific CSS and JS (layout, process, 404, etc.)
 */
function beauty_time_page_assets() {
	$mock = get_template_directory_uri() . '/' . BEAUTY_TIME_MOCK;
	$ver  = BEAUTY_TIME_VERSION;
	$dir  = get_template_directory() . '/' . BEAUTY_TIME_MOCK;

	// Toggle (referenced by process, login, signup, forget-password, profile, contact)
	if ( file_exists( $dir . '/components/toggle.css' ) ) {
		wp_enqueue_style( 'beauty-time-toggle', $mock . '/components/toggle.css', array( 'beauty-time-products' ), $ver );
	}

	// Booking (process) page
	$is_booking_page = is_page_template( 'page-templates/booking.php' );
	if ( ! $is_booking_page && is_page() ) {
		$post = get_queried_object();
		if ( $post && isset( $post->post_name ) && 'booking' === $post->post_name ) {
			$is_booking_page = true;
		}
	}
	if ( $is_booking_page ) {
		if ( file_exists( $dir . '/templates/process/process.css' ) ) {
			wp_enqueue_style( 'beauty-time-process', $mock . '/templates/process/process.css', array( 'beauty-time-toggle' ), $ver );
		}
		wp_enqueue_script(
			'beauty-time-date-time-picker',
			$mock . '/js/date-time-picker.js',
			array( 'beauty-time-app' ),
			$ver,
			true
		);
		wp_enqueue_script(
			'beauty-time-booking-form',
			$mock . '/js/booking-form.js',
			array( 'beauty-time-date-time-picker', 'jquery' ),
			$ver,
			true
		);
		wp_localize_script(
			'beauty-time-booking-form',
			'beautyBooking',
			array(
				'ajaxUrl'             => admin_url( 'admin-ajax.php' ),
				'nonce'               => wp_create_nonce( 'beauty_booking_nonce' ),
				'workingHoursNonce'   => wp_create_nonce( 'beauty_working_hours' ),
				'workingHoursInterval'=> 60,
			)
		);
	}

	// Booking success
	$is_booking_success_page = is_page_template( 'page-templates/booking-success.php' );
	if ( ! $is_booking_success_page && is_page() ) {
		$post = get_queried_object();
		if ( $post && isset( $post->post_name ) && 'booking-success' === $post->post_name ) {
			$is_booking_success_page = true;
		}
	}
	if ( $is_booking_success_page ) {
		if ( file_exists( $dir . '/templates/process/process.css' ) ) {
			wp_enqueue_style( 'beauty-time-process', $mock . '/templates/process/process.css', array( 'beauty-time-toggle' ), $ver );
		}
	}

	// 404
	if ( is_404() ) {
		if ( file_exists( $dir . '/templates/404/404.css' ) ) {
			wp_enqueue_style( 'beauty-time-404', $mock . '/templates/404/404.css', array(), $ver );
		}
	}

	// About-us uses layout.css (panner-hero, etc.)
	$is_about_page = is_page_template( 'page-templates/about-us.php' );
	if ( ! $is_about_page && is_page() ) {
		$post = get_queried_object();
		if ( $post && isset( $post->post_name ) && 'about-us' === $post->post_name ) {
			$is_about_page = true;
		}
	}
	if ( $is_about_page && file_exists( $dir . '/templates/layout/layout.css' ) ) {
		wp_enqueue_style( 'beauty-time-layout', $mock . '/templates/layout/layout.css', array( 'beauty-time-products' ), $ver );
	}

	// About, Contact, Privacy, Services, Onsale, Profile
	$templates = array(
		'page-templates/about-us.php'       => array( 'about-us', 'about-us', 'beauty-time-layout', 'about-us' ),
		'page-templates/contact.php'        => array( 'contact', 'contact', '' ),
		'page-templates/privacy-policy.php' => array( 'privacy-policy', 'privacy-policy', '' ),
		'page-templates/services.php'       => array( 'services', 'services', '' ),
		'page-templates/onsale.php'         => array( 'products', 'sub-products', '' ),
		'page-templates/profile.php'        => array( 'profile', 'profile', '' ),
	);
	foreach ( $templates as $template => $pair ) {
		$match = is_page_template( $template );
		if ( ! $match && isset( $pair[3] ) && is_page() ) {
			$post = get_queried_object();
			if ( $post && isset( $post->post_name ) && $pair[3] === $post->post_name ) {
				$match = true;
			}
		}
		if ( ! $match ) {
			continue;
		}
		$folder   = $pair[0];
		$file     = $pair[1];
		$dep      = isset( $pair[2] ) && $pair[2] ? array( $pair[2] ) : array( 'beauty-time-products' );
		$path     = $dir . '/templates/' . $folder . '/' . $file . '.css';
		if ( file_exists( $path ) ) {
			wp_enqueue_style(
				'beauty-time-' . $folder,
				$mock . '/templates/' . $folder . '/' . $file . '.css',
				$dep,
				$ver
			);
		}
		break;
	}

	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		$profile_css = $dir . '/templates/profile/profile.css';
		if ( file_exists( $profile_css ) ) {
			wp_enqueue_style(
				'beauty-time-profile',
				$mock . '/templates/profile/profile.css',
				array( 'beauty-time-products' ),
				$ver
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'beauty_time_page_assets', 20 );

/**
 * Inject demo site colors as CSS variables.
 */
function beauty_demo_site_inline_colors() {
	if ( ! function_exists( 'beauty_demo_site_get_options' ) ) {
		return;
	}
	$options = beauty_demo_site_get_options();
	$colors = $options['colors'] ?? array();
	$primary = $colors['primary'] ?? '';
	$card = $colors['card'] ?? '';
	$hero_bg = $colors['hero_bg'] ?? '';
	$text = $colors['text'] ?? '';

	$vars = array();
	if ( $primary ) {
		$vars[] = '--y-color-primary: ' . $primary . ';';
	}
	if ( $card ) {
		$vars[] = '--y-color-yellow: ' . $card . ';';
	}
	if ( $hero_bg ) {
		$vars[] = '--y-hero-bg: ' . $hero_bg . ';';
	}
	if ( $text ) {
		$vars[] = '--y-color-text: ' . $text . ';';
	}

	if ( $vars ) {
		echo '<style>:root{' . esc_html( implode( '', $vars ) ) . '}</style>';
	}
}
add_action( 'wp_head', 'beauty_demo_site_inline_colors', 30 );

/**
 * Enqueue layout CSS when using front-page template
 */
function beauty_time_front_page_layout() {
	if ( ! is_front_page() ) {
		return;
	}
	$dir = get_template_directory() . '/' . BEAUTY_TIME_MOCK;
	if ( file_exists( $dir . '/templates/layout/layout.css' ) ) {
		wp_enqueue_style(
			'beauty-time-layout',
			get_template_directory_uri() . '/' . BEAUTY_TIME_MOCK . '/templates/layout/layout.css',
			array( 'beauty-time-products' ),
			BEAUTY_TIME_VERSION
		);
	}
}
add_action( 'wp_enqueue_scripts', 'beauty_time_front_page_layout', 15 );
