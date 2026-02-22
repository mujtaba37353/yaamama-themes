<?php
/**
 * Beauty Time Theme — functions and setup
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

define( 'BEAUTY_TIME_VERSION', '1.0.0' );
define( 'BEAUTY_TIME_MOCK', 'beauty-time' );

/**
 * Theme setup
 */
function beauty_time_setup() {
	load_theme_textdomain( 'beauty-time-theme', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'custom-logo', array(
		'height'      => 80,
		'width'       => 200,
		'flex-height' => true,
		'flex-width'  => true,
	) );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'wp-block-styles' );
	add_theme_support( 'editor-styles' );
	add_editor_style( BEAUTY_TIME_MOCK . '/base/tokens.css' );

	// WooCommerce support
	if ( class_exists( 'WooCommerce' ) ) {
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
	}
}
add_action( 'after_setup_theme', 'beauty_time_setup' );

/**
 * Enqueue scripts and styles
 */
function beauty_time_scripts() {
	$bt   = get_template_directory_uri();
	$mock = $bt . '/' . BEAUTY_TIME_MOCK;
	$ver  = BEAUTY_TIME_VERSION;

	// Base
	wp_enqueue_style( 'beauty-time-reset', $mock . '/base/reset.css', array(), $ver );
	wp_enqueue_style( 'beauty-time-tokens', $mock . '/base/tokens.css', array(), $ver );
	wp_enqueue_style( 'beauty-time-utilities', $mock . '/base/utilities.css', array( 'beauty-time-tokens' ), $ver );
	wp_enqueue_style( 'beauty-time-typography', $mock . '/base/typography.css', array( 'beauty-time-tokens' ), $ver );

	// Components (global)
	wp_enqueue_style( 'beauty-time-header', $mock . '/components/header.css', array( 'beauty-time-utilities' ), $ver );
	wp_enqueue_style( 'beauty-time-footer', $mock . '/components/footer.css', array(), $ver );
	wp_enqueue_style( 'beauty-time-buttons', $mock . '/components/buttons.css', array(), $ver );
	wp_enqueue_style( 'beauty-time-panner', $mock . '/components/panner.css', array(), $ver );
	wp_enqueue_style( 'beauty-time-products', $mock . '/components/products.css', array(), $ver );
	wp_enqueue_style( 'beauty-time-categories', $mock . '/components/categories.css', array(), $ver );
	wp_enqueue_style( 'beauty-time-auth', $mock . '/components/auth.css', array(), $ver );
	wp_enqueue_style( 'beauty-time-special-form', $mock . '/components/special-form.css', array(), $ver );

	if ( file_exists( get_template_directory() . '/' . BEAUTY_TIME_MOCK . '/components/toggle.css' ) ) {
		wp_enqueue_style( 'beauty-time-toggle', $mock . '/components/toggle.css', array(), $ver );
	}

	// Fonts
	wp_enqueue_style(
		'beauty-time-fonts',
		'https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
		array(),
		'6.4.0'
	);

	// Main JS
	wp_enqueue_script(
		'beauty-time-app',
		$mock . '/js/y-app-init.js',
		array(),
		$ver,
		true
	);
	wp_script_add_data( 'beauty-time-app', 'defer', true );

	// WooCommerce notices styling (inline or separate file)
	if ( class_exists( 'WooCommerce' ) ) {
		$notices_css = get_template_directory() . '/' . BEAUTY_TIME_MOCK . '/components/woocommerce-notices.css';
		if ( file_exists( $notices_css ) ) {
			wp_enqueue_style( 'beauty-time-wc-notices', $mock . '/components/woocommerce-notices.css', array( 'beauty-time-products' ), $ver );
		}
		
		// Category page styles
		if ( is_product_category() ) {
			$services_css = get_template_directory() . '/' . BEAUTY_TIME_MOCK . '/templates/services/services.css';
			if ( file_exists( $services_css ) ) {
				wp_enqueue_style( 'beauty-time-services', $mock . '/templates/services/services.css', array( 'beauty-time-products' ), $ver );
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'beauty_time_scripts' );

/**
 * Base template directory URI for beauty-time assets
 *
 * @return string
 */
function beauty_time_mock_uri() {
	return get_template_directory_uri() . '/' . BEAUTY_TIME_MOCK;
}

/**
 * Asset URL
 *
 * @param string $path Path relative to beauty-time (e.g. assets/icon.png).
 * @return string
 */
function beauty_time_asset( $path ) {
	return beauty_time_mock_uri() . '/' . ltrim( $path, '/' );
}

/**
 * WooCommerce: Remove default wrappers and breadcrumbs (we use our own)
 */
function beauty_time_woocommerce_setup() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

	// Remove default shop page title (we add it in archive-product.php)
	add_filter( 'woocommerce_show_page_title', '__return_false' );
}
add_action( 'wp', 'beauty_time_woocommerce_setup' );

/**
 * Validate register password confirmation.
 */
function beauty_time_validate_register_password_confirm( $errors, $username, $email ) {
	if ( 'no' !== get_option( 'woocommerce_registration_generate_password' ) ) {
		return $errors;
	}
	$password = isset( $_POST['password'] ) ? wp_unslash( $_POST['password'] ) : '';
	$confirm  = isset( $_POST['password_confirm'] ) ? wp_unslash( $_POST['password_confirm'] ) : '';
	if ( '' === $password || '' === $confirm ) {
		$errors->add( 'password_required', __( 'يرجى إدخال كلمة المرور وتأكيدها.', 'beauty-time-theme' ) );
		return $errors;
	}
	if ( $password !== $confirm ) {
		$errors->add( 'password_mismatch', __( 'تأكيد كلمة المرور غير مطابق.', 'beauty-time-theme' ) );
	}
	return $errors;
}
add_filter( 'woocommerce_registration_errors', 'beauty_time_validate_register_password_confirm', 10, 3 );

/**
 * Enable product reviews/comments in admin.
 */
function beauty_time_enable_product_comments( $open, $post_id ) {
	if ( 'product' === get_post_type( $post_id ) ) {
		return true;
	}
	return $open;
}
add_filter( 'comments_open', 'beauty_time_enable_product_comments', 10, 2 );
add_filter( 'pings_open', 'beauty_time_enable_product_comments', 10, 2 );
add_filter( 'woocommerce_enable_reviews', '__return_true' );
add_filter( 'wc_product_reviews_enabled', '__return_true' );

/**
 * Get icon for account menu item
 *
 * @param string $endpoint Endpoint slug.
 * @return string Icon class.
 */
function beauty_get_account_icon( $endpoint ) {
	$icons = array(
		'dashboard'        => 'user',
		'orders'           => 'calendar-alt',
		'bookings'         => 'calendar-alt',
		'downloads'        => 'download',
		'edit-address'     => 'map-marker-alt',
		'edit-account'     => 'user-edit',
		'customer-logout' => 'right-from-bracket',
	);
	return isset( $icons[ $endpoint ] ) ? $icons[ $endpoint ] : 'circle';
}

/**
 * Auto-assign page templates based on slug (fallback)
 * Ensures pages use correct templates even if not manually assigned
 */
function beauty_time_auto_page_template( $template ) {
	if ( ! is_page() ) {
		return $template;
	}

	global $post;
	if ( ! $post ) {
		return $template;
	}

	$slug = $post->post_name;
	$template_map = array(
		'booking'         => 'page-templates/booking.php',
		'booking-success' => 'page-templates/booking-success.php',
		'services'        => 'page-templates/services.php',
		'onsale'          => 'page-templates/onsale.php',
		'about-us'        => 'page-templates/about-us.php',
		'contact'         => 'page-templates/contact.php',
		'privacy-policy'  => 'page-templates/privacy-policy.php',
	);

	if ( isset( $template_map[ $slug ] ) ) {
		$custom_template = locate_template( $template_map[ $slug ] );
		if ( $custom_template ) {
			return $custom_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'beauty_time_auto_page_template', 99 );

/**
 * Remove WooCommerce default privacy policy text from registration form
 */
function beauty_remove_wc_privacy_policy_text() {
	remove_action( 'woocommerce_register_form', 'wc_registration_privacy_policy_text', 20 );
}
add_action( 'init', 'beauty_remove_wc_privacy_policy_text' );

/**
 * Handle register endpoint - show separate register page
 */
function beauty_handle_register_endpoint() {
	if ( ! is_account_page() ) {
		return;
	}
	
	global $wp;
	$is_register = isset( $wp->query_vars['register'] ) || ( isset( $_GET['action'] ) && 'register' === $_GET['action'] );
	
	if ( $is_register ) {
		if ( is_user_logged_in() ) {
			wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
			exit;
		}
		// Override the login form with register form
		add_filter( 'woocommerce_locate_template', 'beauty_override_register_template', 10, 3 );
	}
}
add_action( 'template_redirect', 'beauty_handle_register_endpoint', 5 );

/**
 * Override register template
 */
function beauty_override_register_template( $template, $template_name, $template_path ) {
	if ( 'myaccount/form-login.php' === $template_name ) {
		global $wp;
		$is_register = isset( $wp->query_vars['register'] ) || ( isset( $_GET['action'] ) && 'register' === $_GET['action'] );
		if ( $is_register && ! is_user_logged_in() ) {
			$custom_template = locate_template( 'woocommerce/myaccount/form-register.php' );
			if ( $custom_template ) {
				return $custom_template;
			}
		}
	}
	return $template;
}

/**
 * Add register endpoint
 */
function beauty_add_register_endpoint() {
	add_rewrite_endpoint( 'register', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'beauty_add_register_endpoint' );

/**
 * Add register to WooCommerce query vars
 */
function beauty_register_query_vars( $vars ) {
	$vars[] = 'register';
	return $vars;
}
add_filter( 'woocommerce_get_query_vars', 'beauty_register_query_vars' );

/**
 * Remove dashboard, downloads, orders, and edit-address from MyAccount menu
 * Keep only: edit-account, bookings
 */
function beauty_remove_account_menu_items( $items ) {
	unset( $items['dashboard'] );
	unset( $items['downloads'] );
	unset( $items['orders'] );
	unset( $items['edit-address'] );
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'beauty_remove_account_menu_items', 5 );

/**
 * Translate MyAccount menu items to Arabic
 */
function beauty_translate_account_menu_items( $items ) {
	$translations = array(
		'orders'          => __( 'طلباتي', 'beauty-time-theme' ),
		'bookings'        => __( 'حجوزاتي', 'beauty-time-theme' ),
		'edit-address'    => __( 'العناوين', 'beauty-time-theme' ),
		'edit-account'    => __( 'تفاصيل الحساب', 'beauty-time-theme' ),
		'customer-logout' => __( 'تسجيل الخروج', 'beauty-time-theme' ),
	);
	
	foreach ( $items as $key => $label ) {
		if ( isset( $translations[ $key ] ) ) {
			$items[ $key ] = $translations[ $key ];
		}
	}
	
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'beauty_translate_account_menu_items', 20 );

/**
 * Redirect dashboard to edit-account page
 */
function beauty_redirect_account_dashboard() {
	if ( is_account_page() && is_user_logged_in() && ! is_wc_endpoint_url() ) {
		wp_safe_redirect( wc_get_account_endpoint_url( 'edit-account' ) );
		exit;
	}
}
add_action( 'template_redirect', 'beauty_redirect_account_dashboard' );

/**
 * Include theme partials
 */
require_once get_template_directory() . '/inc/enqueue.php';
$booking_file = get_template_directory() . '/inc/booking.php';
if ( file_exists( $booking_file ) ) {
	require_once $booking_file;
}
$demo_file = get_template_directory() . '/inc/demo-products-admin.php';
if ( file_exists( $demo_file ) ) {
	require_once $demo_file;
}
$demo_site_file = get_template_directory() . '/inc/demo-site-admin.php';
if ( file_exists( $demo_site_file ) ) {
	require_once $demo_site_file;
}
