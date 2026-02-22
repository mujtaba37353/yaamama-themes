<?php
/**
 * Elegance Theme - Functions and setup
 *
 * @package Elegance
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'ELEGANCE_THEME_VERSION', '1.0.0' );
define( 'ELEGANCE_ELEGANCE_URI', get_template_directory_uri() . '/elegance' );

require_once get_template_directory() . '/inc/load.php';

/**
 * Enqueue global styles and scripts.
 */
function elegance_enqueue_assets() {
	$e = ELEGANCE_ELEGANCE_URI;

	// Base
	wp_enqueue_style( 'elegance-reset', $e . '/base/reset.css', array(), ELEGANCE_THEME_VERSION );
	wp_enqueue_style( 'elegance-tokens', $e . '/base/tokens.css', array( 'elegance-reset' ), ELEGANCE_THEME_VERSION );
	wp_enqueue_style( 'elegance-typography', $e . '/base/typography.css', array( 'elegance-tokens' ), ELEGANCE_THEME_VERSION );
	wp_enqueue_style( 'elegance-utilities', $e . '/base/utilities.css', array( 'elegance-typography' ), ELEGANCE_THEME_VERSION );

	// Components (global)
	wp_enqueue_style( 'elegance-header', $e . '/components/header.css', array( 'elegance-utilities' ), ELEGANCE_THEME_VERSION );
	wp_enqueue_style( 'elegance-footer', $e . '/components/footer.css', array( 'elegance-utilities' ), ELEGANCE_THEME_VERSION );
	wp_enqueue_style( 'elegance-toggle', $e . '/components/toggle.css', array(), ELEGANCE_THEME_VERSION );
	wp_enqueue_style( 'elegance-buttons', $e . '/components/buttons.css', array(), ELEGANCE_THEME_VERSION );
	wp_enqueue_style( 'elegance-panner', $e . '/components/panner.css', array(), ELEGANCE_THEME_VERSION );

	// Fonts
	wp_enqueue_style(
		'elegance-google-fonts',
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

	// Global JS (WP-specific: no fetch for header/footer, uses body class for active nav)
	wp_enqueue_script(
		'elegance-y-app-init',
		$e . '/js/y-app-init-wp.js',
		array(),
		ELEGANCE_THEME_VERSION,
		true
	);
}

add_action( 'wp_enqueue_scripts', 'elegance_enqueue_assets' );

/**
 * Output theme color CSS variables (from theme_mod) so frontend can use var(--elegance-*).
 */
function elegance_enqueue_theme_variables() {
	$css = elegance_theme_css_variables();
	if ( $css !== '' ) {
		wp_add_inline_style( 'elegance-reset', ':root{' . $css . '}' );
	}
}
add_action( 'wp_enqueue_scripts', 'elegance_enqueue_theme_variables', 21 );

/**
 * Add top offset for policy pages to avoid fixed header overlap.
 */
function elegance_enqueue_policy_page_offset() {
	if ( ! is_page() ) {
		return;
	}
	$page = get_queried_object();
	if ( ! ( $page instanceof WP_Post ) || empty( $page->post_name ) ) {
		return;
	}
	if ( strpos( (string) $page->post_name, 'policy' ) === false ) {
		return;
	}
	$css = 'main{padding-top:96px;}@media (max-width:768px){main{padding-top:88px;}}';
	wp_add_inline_style( 'elegance-header', $css );
}
add_action( 'wp_enqueue_scripts', 'elegance_enqueue_policy_page_offset', 25 );

/**
 * Enqueue WooCommerce / page-specific styles.
 */
function elegance_enqueue_woo_styles() {
	if ( function_exists( 'is_woocommerce' ) && ( is_shop() || is_product_category() ) ) {
		elegance_enqueue_page_css( 'store' );
		elegance_enqueue_component_css( array( 'products', 'breadcrumbs' ) );
		wp_dequeue_style( 'woocommerce-layout' );
		wp_deregister_style( 'woocommerce-layout' );
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		elegance_enqueue_page_css( 'product-details' );
		elegance_enqueue_component_css( array( 'products', 'quantity', 'breadcrumbs', 'price-value', 'payment-details' ) );
	}
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		elegance_enqueue_page_css( 'cart' );
		elegance_enqueue_component_css( array( 'quantity', 'empty-state' ) );
	}
}
add_action( 'wp_enqueue_scripts', 'elegance_enqueue_woo_styles', 20 );

/**
 * Products per shop page:
 * - Desktop/tablet web: 18
 * - Mobile: 16
 */
function elegance_loop_shop_per_page( $products_per_page ) {
	if ( function_exists( 'wp_is_mobile' ) && wp_is_mobile() ) {
		return 16;
	}
	return 18;
}
add_filter( 'loop_shop_per_page', 'elegance_loop_shop_per_page', 20 );

/**
 * تحديث عداد السلة في الهيدر عبر AJAX عند إضافة منتج.
 */
function elegance_cart_count_fragment( $fragments ) {
	$cart_count = 0;
	if ( function_exists( 'WC' ) && WC()->cart ) {
		$cart_count = WC()->cart->get_cart_contents_count();
	}
	$assets = ELEGANCE_ELEGANCE_URI . '/assets';
	ob_start();
	?>
	<span class="elegance-cart-fragment" data-fragment="cart-count">
		<a href="<?php echo esc_url( elegance_cart_url() ); ?>" class="nav-icon nav-icon--cart" data-nav="cart" aria-label="<?php echo esc_attr( sprintf( _n( '%s منتج في السلة', '%s منتجات في السلة', $cart_count, 'elegance' ), $cart_count ) ); ?>">
			<img src="<?php echo esc_url( $assets . '/cart.svg' ); ?>" alt="" />
			<?php if ( $cart_count > 0 ) : ?>
				<span class="cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
			<?php endif; ?>
		</a>
	</span>
	<?php
	$fragments['.elegance-cart-fragment'] = ob_get_clean();
	return $fragments;
}
if ( function_exists( 'WC' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'elegance_cart_count_fragment' );
}

/**
 * Add body classes for nav active state (used by y-app-init-wp.js).
 */
function elegance_body_class_nav( $classes ) {
	if ( is_front_page() && ! is_home() ) {
		$classes[] = 'elegance-nav-home';
	} elseif ( is_home() ) {
		$classes[] = 'elegance-nav-home';
	} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
		$classes[] = 'elegance-nav-shop';
	} elseif ( is_page( 'about-us' ) || is_page( 'من-نحن' ) ) {
		$classes[] = 'elegance-nav-about-us';
	} elseif ( is_page( 'contact' ) || is_page( 'تواصل-معنا' ) || is_page( 'contact-us' ) ) {
		$classes[] = 'elegance-nav-contact';
	} elseif ( function_exists( 'is_cart' ) && is_cart() ) {
		$classes[] = 'elegance-nav-cart';
	} elseif ( function_exists( 'is_account_page' ) && is_account_page() ) {
		$classes[] = 'elegance-nav-profile';
	} elseif ( is_page( 'login' ) ) {
		$classes[] = 'elegance-nav-login';
	} elseif ( is_page( 'signup' ) || is_page( 'register' ) ) {
		$classes[] = 'elegance-nav-signup';
	} elseif ( is_404() ) {
		$classes[] = 'elegance-nav-404';
	}
	return $classes;
}

add_filter( 'body_class', 'elegance_body_class_nav' );

/**
 * Enqueue page-specific styles (called from templates).
 *
 * @param string $handle_suffix e.g. 'layout', 'store', 'cart', 'about-us', 'contact', '404', 'profile', 'payment', 'product-details'.
 */
function elegance_enqueue_page_css( $handle_suffix ) {
	$e = ELEGANCE_ELEGANCE_URI;
	$templates = array(
		'layout'         => array( 'templates/layout/layout.css', array( 'elegance-panner' ) ),
		'store'          => array( 'templates/store/store.css', array( 'elegance-buttons', 'elegance-panner', 'elegance-breadcrumbs' ) ),
		'cart'           => array( 'templates/cart/cart.css', array( 'elegance-panner' ) ),
		'about-us'       => array( 'templates/about-us/about-us.css', array( 'elegance-panner' ) ),
		'contact'        => array( 'templates/contact/contact.css', array( 'elegance-panner' ) ),
		'404'            => array( 'templates/404/404.css', array() ),
		'profile'        => array( 'templates/profile/profile.css', array( 'elegance-panner', 'elegance-auth' ) ),
		'payment'        => array( 'templates/payment/payment.css', array( 'elegance-panner', 'elegance-payment-details' ) ),
		'product-details'=> array( 'templates/product-details/product-details.css', array( 'elegance-panner', 'elegance-products', 'elegance-quantity' ) ),
	);
	if ( ! isset( $templates[ $handle_suffix ] ) ) {
		return;
	}
	list( $file, $deps ) = $templates[ $handle_suffix ];
	$deps = array_merge( array( 'elegance-footer' ), $deps );
	wp_enqueue_style( 'elegance-' . $handle_suffix, $e . '/' . $file, $deps, ELEGANCE_THEME_VERSION );
}

/**
 * Enqueue component CSS by handle (for use when a template needs products, breadcrumbs, auth, etc.).
 */
function elegance_enqueue_component_css( $handles ) {
	$e = ELEGANCE_ELEGANCE_URI;
	$map = array(
		'products'        => $e . '/components/products.css',
		'breadcrumbs'     => $e . '/components/breadcrumbs.css',
		'auth'            => $e . '/components/auth.css',
		'payment-details' => $e . '/components/payment-details.css',
		'quantity'        => $e . '/components/quantity.css',
		'empty-state'     => $e . '/components/empty-state.css',
		'status-popup'    => $e . '/components/status-popup.css',
	);
	foreach ( (array) $handles as $h ) {
		if ( isset( $map[ $h ] ) ) {
			$handle = 'elegance-' . $h;
			/**
			 * A style can appear "enqueued" as a dependency before being registered.
			 * Register first, then enqueue, to avoid unresolved deps dropping page CSS.
			 */
			if ( ! wp_style_is( $handle, 'registered' ) ) {
				wp_register_style( $handle, $map[ $h ], array( 'elegance-utilities' ), ELEGANCE_THEME_VERSION );
			}
			wp_enqueue_style( $handle );
		}
	}
}

/**
 * Theme setup.
 */
function elegance_setup() {
	load_theme_textdomain( 'elegance', get_template_directory() . '/languages' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	add_theme_support( 'customize-selective-refresh-widgets' );
	add_theme_support( 'responsive-embeds' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
}
add_action( 'after_setup_theme', 'elegance_setup' );

/**
 * Helper: asset URL for elegance folder.
 *
 * @param string $path Path relative to elegance/ e.g. 'assets/icon.png'
 * @return string
 */
function elegance_asset_url( $path ) {
	return ELEGANCE_ELEGANCE_URI . '/' . ltrim( $path, '/' );
}

/**
 * Shop URL (WooCommerce or fallback).
 */
function elegance_shop_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		return wc_get_page_permalink( 'shop' );
	}
	return home_url( '/shop/' );
}

/**
 * Cart URL.
 */
function elegance_cart_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		return wc_get_page_permalink( 'cart' );
	}
	return home_url( '/cart/' );
}

/**
 * My Account URL.
 */
function elegance_myaccount_url() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		return wc_get_page_permalink( 'myaccount' );
	}
	return home_url( '/my-account/' );
}

/**
 * Page URL by slug (for about-us, contact, etc.).
 *
 * @param string $slug Page slug.
 * @param string $fallback Fallback path e.g. '/about-us/'.
 * @return string
 */
function elegance_page_url( $slug, $fallback = '' ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		return get_permalink( $page );
	}
	return $fallback ? home_url( $fallback ) : home_url( '/' . $slug . '/' );
}

/**
 * Ensure favorites page exists so /favorites/ works (create once if missing).
 */
function elegance_ensure_favorites_page() {
	if ( get_option( 'elegance_favorites_page_created' ) ) {
		return;
	}
	if ( get_page_by_path( 'favorites', OBJECT, 'page' ) ) {
		update_option( 'elegance_favorites_page_created', '1' );
		return;
	}
	$id = wp_insert_post(
		array(
			'post_title'   => 'المفضلة',
			'post_name'    => 'favorites',
			'post_content' => '',
			'post_status'  => 'publish',
			'post_type'    => 'page',
			'post_author'  => 1,
		),
		true
	);
	if ( ! is_wp_error( $id ) ) {
		update_post_meta( $id, '_wp_page_template', 'page-favorites.php' );
		update_option( 'elegance_favorites_page_created', '1' );
	}
}

add_action( 'init', 'elegance_ensure_favorites_page', 20 );
