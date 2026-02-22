<?php
/**
 * Sweet House Theme — essential setup and assets.
 *
 * @package Sweet_House_Theme
 */

define( 'SWEET_HOUSE_THEME_VERSION', '1.0.0' );

/**
 * Cart page: use static design (Phase 1) or WooCommerce (Phase 2).
 * false = WooCommerce integration active — design preserved via theme templates & CSS.
 */
if ( ! defined( 'SWEET_HOUSE_CART_USE_STATIC_DESIGN' ) ) {
	define( 'SWEET_HOUSE_CART_USE_STATIC_DESIGN', false );
}

/**
 * Base URI for design assets (sweet-house folder).
 */
function sweet_house_asset_base_uri() {
	return get_template_directory_uri() . '/sweet-house';
}

/**
 * Base path for design assets (sweet-house folder).
 */
function sweet_house_asset_base_path() {
	return get_template_directory() . '/sweet-house';
}

/**
 * Full URI for an asset under sweet-house.
 */
function sweet_house_asset_uri( $relative_path ) {
	return sweet_house_asset_base_uri() . '/' . ltrim( $relative_path, '/' );
}

/**
 * Full filesystem path for an asset under sweet-house.
 */
function sweet_house_asset_path( $relative_path ) {
	return sweet_house_asset_base_path() . '/' . ltrim( $relative_path, '/' );
}

/**
 * Enqueue a stylesheet from sweet-house.
 */
function sweet_house_enqueue_style( $handle, $relative_path, $deps = array(), $version = null ) {
	$path = sweet_house_asset_path( $relative_path );
	if ( file_exists( $path ) ) {
		$ver = $version ? $version : (string) filemtime( $path );
		wp_enqueue_style( $handle, sweet_house_asset_uri( $relative_path ), $deps, $ver );
	}
}

/**
 * Enqueue a script from sweet-house.
 */
function sweet_house_enqueue_script( $handle, $relative_path, $deps = array(), $in_footer = true ) {
	$path = sweet_house_asset_path( $relative_path );
	if ( file_exists( $path ) ) {
		$ver = (string) filemtime( $path );
		wp_enqueue_script( $handle, sweet_house_asset_uri( $relative_path ), $deps, $ver, $in_footer );
	}
}

/**
 * Theme setup.
 */
function sweet_house_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
	load_theme_textdomain( 'sweet-house-theme', get_template_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'sweet_house_setup' );

/**
 * Enqueue global styles and scripts (header, navbar, footer, base).
 */
function sweet_house_enqueue_assets() {
	// Base design system
	sweet_house_enqueue_style( 'sweet-house-reset', 'base/reset.css' );
	sweet_house_enqueue_style( 'sweet-house-tokens', 'base/tokens.css' );
	sweet_house_enqueue_style( 'sweet-house-typography', 'base/typography.css', array( 'sweet-house-tokens' ) );
	sweet_house_enqueue_style( 'sweet-house-utilities', 'base/utilities.css', array( 'sweet-house-tokens' ) );

	// Layout components
	sweet_house_enqueue_style( 'sweet-house-navbar', 'components/layout/y-c-navbar.css', array( 'sweet-house-utilities' ) );
	sweet_house_enqueue_style( 'sweet-house-footer', 'components/layout/y-c-footer.css', array( 'sweet-house-utilities' ) );

	// Fonts
	wp_enqueue_style(
		'sweet-house-fonts',
		'https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Gulzar&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
		array(),
		'6.5.0'
	);

	// Front page: hero + section + product cards (for المنتجات section)
	if ( is_front_page() ) {
		sweet_house_enqueue_style( 'sweet-house-section', 'components/home/y-c-section.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-product-card', 'components/cards/y-c-product-card.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-products', 'components/products/y-c-products.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-quick-view', 'components/quick-view/y-c-quick-view.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_script( 'sweet-house-quick-view', 'js/y-quick-view.js', array(), true );
		wp_localize_script( 'sweet-house-quick-view', 'sweetHouseQuickView', array(
			'assetUri' => sweet_house_asset_uri( '' ),
			'cartUrl'  => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : '',
		) );
	}

	// Thank You (order-received) page: design layout
	if ( function_exists( 'is_checkout' ) && is_checkout() && function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-auth-btn', 'components/buttons/y-c-auth-btn.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-thank-you', 'templates/thank-you/thank-you.css', array( 'sweet-house-utilities' ) );
	}

	// Checkout (payment) page: design layout
	if ( function_exists( 'is_checkout' ) && is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-text-fields', 'components/text fields/y-c-text-fields.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-cart-summary', 'components/cards/y-c-cart-summary-card.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-payment-form', 'components/payment/y-c-payment-form.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-payment-summary', 'components/payment/y-c-payment-summary-card.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-auth-btn', 'components/buttons/y-c-auth-btn.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-payment-page', 'templates/payment/payment.css', array( 'sweet-house-payment-form', 'sweet-house-payment-summary' ) );
	}

	// My Account, Auth & Sign-up pages: design header, auth forms, account sidebar
	if ( ( function_exists( 'is_account_page' ) && is_account_page() ) || is_page( 'sign-up' ) ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-auth', 'components/auth/y-c-auth.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-auth-btn', 'components/buttons/y-c-auth-btn.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-account-sidebar', 'components/account/y-c-account-sidebar.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-account-details', 'components/account/y-c-account-details.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-address', 'components/account/y-c-address.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-orders', 'components/account/y-c-orders.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-my-account', 'templates/my-account/my-account.css', array( 'sweet-house-account-sidebar' ) );
	}

	// Cart page: cart table, cart summary, design layout, empty cart
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-empty', 'components/products/y-c-empty.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-cart-summary', 'components/cards/y-c-cart-summary-card.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-cart-table', 'components/cart/y-c-cart-table.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-qnt-btn', 'components/buttons/y-c-qnt-btn.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-auth-btn', 'components/buttons/y-c-auth-btn.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-cart-page', 'templates/cart/cart.css', array( 'sweet-house-cart-table', 'sweet-house-cart-summary' ) );
		sweet_house_enqueue_style( 'sweet-house-product-card', 'components/cards/y-c-product-card.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-products', 'components/products/y-c-products.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-quick-view', 'components/quick-view/y-c-quick-view.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_script( 'sweet-house-cart-qty', 'js/y-cart-qty.js', array( 'jquery' ), true );
		sweet_house_enqueue_script( 'sweet-house-quick-view', 'js/y-quick-view.js', array(), true );
		wp_localize_script( 'sweet-house-quick-view', 'sweetHouseQuickView', array(
			'assetUri' => sweet_house_asset_uri( '' ),
			'cartUrl'  => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : '',
		) );
	}

	// Shop / product archive: product cards, products grid, filter, pagination, empty state
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		sweet_house_enqueue_style( 'sweet-house-empty', 'components/products/y-c-empty.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-product-card', 'components/cards/y-c-product-card.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-products', 'components/products/y-c-products.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-filter-bar', 'components/products/y-c-filter-bar.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-pagination', 'components/products/y-c-pagination.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-quick-view', 'components/quick-view/y-c-quick-view.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_script( 'sweet-house-shop-sort', 'js/y-shop-sort.js', array(), true );
		sweet_house_enqueue_script( 'sweet-house-quick-view', 'js/y-quick-view.js', array(), true );
		wp_localize_script( 'sweet-house-quick-view', 'sweetHouseQuickView', array(
			'assetUri' => sweet_house_asset_uri( '' ),
			'cartUrl'  => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : '',
		) );
	}

	// About Us page
	if ( is_page( 'about-us' ) || is_page( 'about' ) ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-about-us', 'components/about us/y-c-about-us.css', array( 'sweet-house-utilities' ) );
	}

	// Contact Us page
	if ( is_page( 'contact-us' ) || is_page( 'contact' ) ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-btn', 'components/buttons/y-c-btn.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-text-fields', 'components/text fields/y-c-text-fields.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-contact-us', 'components/contact us/y-c-contact-us.css', array( 'sweet-house-utilities' ) );
	}

	// Policy pages (privacy, refund, shipping, policy)
	if ( sweet_house_is_policy_page() ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-policy', 'components/policy/y-c-policy.css', array( 'sweet-house-utilities' ) );
	}

	// Wishlist page
	if ( is_page( 'wishlist' ) ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-product-card', 'components/cards/y-c-product-card.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-products', 'components/products/y-c-products.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-empty', 'components/products/y-c-empty.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-wishlist', 'templates/wishlist/wishlist.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-quick-view', 'components/quick-view/y-c-quick-view.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_script( 'sweet-house-quick-view', 'js/y-quick-view.js', array(), true );
	}

	// Recipe archive page (أو صفحة وصفاتنا)
	if ( is_post_type_archive( 'recipe' ) || ( is_page() && get_queried_object() && 'recepies' === get_queried_object()->post_name ) ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-recipe', 'components/recipe/y-c-recipe.css', array( 'sweet-house-utilities' ) );
	}

	// Single recipe page
	if ( is_singular( 'recipe' ) ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-single-recipe', 'components/recipe/y-c-single-recipe.css', array( 'sweet-house-utilities' ) );
	}

	// 404 page
	if ( is_404() ) {
		sweet_house_enqueue_style( 'sweet-house-not-found', 'components/not found/y-c-not-found.css', array( 'sweet-house-utilities' ) );
	}

	// Single product page — design from beauty-care (product-details). CSS only here.
	if ( function_exists( 'is_product' ) && is_product() ) {
		sweet_house_enqueue_style( 'sweet-house-design-header', 'templates/pages header/y-c-design-header.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-product-card', 'components/cards/y-c-product-card.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-products', 'components/products/y-c-products.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_style( 'sweet-house-quick-view', 'components/quick-view/y-c-quick-view.css', array( 'sweet-house-utilities' ) );
		sweet_house_enqueue_script( 'sweet-house-quick-view', 'js/y-quick-view.js', array(), true );
		// Page-specific CSS (beauty-care product-details design) — enqueued only on single product.
		$single_product_css = get_stylesheet_directory() . '/assets/css/pages/single-product.css';
		if ( file_exists( $single_product_css ) ) {
			wp_enqueue_style(
				'sweet-house-single-product-page',
				get_stylesheet_directory_uri() . '/assets/css/pages/single-product.css',
				array( 'sweet-house-utilities' ),
				(string) filemtime( $single_product_css )
			);
		}
	}

	// Mobile menu / header toggle
	if ( file_exists( sweet_house_asset_path( 'js/header-toggle.js' ) ) ) {
		sweet_house_enqueue_script( 'sweet-house-header-toggle', 'js/header-toggle.js', array( 'jquery' ) );
	}

	// Wishlist toggle — on all pages with product cards (front, shop, cart, single product, etc.)
	if ( is_front_page()
		|| ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) )
		|| ( function_exists( 'is_cart' ) && is_cart() )
		|| ( function_exists( 'is_product' ) && is_product() )
		|| is_page( 'wishlist' )
		|| is_page( 'offers' ) ) {
		sweet_house_enqueue_script( 'sweet-house-wishlist-toggle', 'js/sweet-house-wishlist.js', array(), true );
		wp_localize_script( 'sweet-house-wishlist-toggle', 'sweetHouseWishlist', array(
			'shopUrl'     => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' ),
			'assetUri'   => sweet_house_asset_uri( '' ),
			'cartUrl'    => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : '',
			'wishlistIds' => array_map( 'intval', function_exists( 'sweet_house_get_wishlist_ids' ) ? sweet_house_get_wishlist_ids() : array() ),
		) );
	}
}
add_action( 'wp_enqueue_scripts', 'sweet_house_enqueue_assets', 5 );

/**
 * WooCommerce: remove default wrappers/sidebar and use Sweet House layout.
 */
function sweet_house_woocommerce_layout() {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
}
add_action( 'wp', 'sweet_house_woocommerce_layout', 15 );

/**
 * Empty search/category: use Sweet House empty wishlist design instead of default WooCommerce message.
 */
function sweet_house_no_products_found_design() {
	remove_action( 'woocommerce_no_products_found', 'wc_no_products_found', 10 );
	add_action( 'woocommerce_no_products_found', 'sweet_house_render_no_products_found', 10 );
}
add_action( 'wp', 'sweet_house_no_products_found_design', 20 );

/**
 * Output the Sweet House empty wishlist-style design for no products found.
 */
function sweet_house_render_no_products_found() {
	wc_get_template( 'loop/no-products-found.php' );
}

/**
 * Hide default archive description ("Search results for X") when no products found, so our design is centered.
 */
function sweet_house_hide_archive_description_when_empty() {
	if ( function_exists( 'woocommerce_product_loop' ) && ! woocommerce_product_loop() ) {
		remove_all_actions( 'woocommerce_archive_description' );
	}
}
add_action( 'wp', 'sweet_house_hide_archive_description_when_empty', 25 );

/**
 * WooCommerce cart: remove cross-sells from collaterals (we display them in "تسوق أكثر" section).
 */
function sweet_house_cart_remove_cross_sells_from_collaterals() {
	remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
}
add_action( 'wp', 'sweet_house_cart_remove_cross_sells_from_collaterals', 20 );

/**
 * Single product: remove default tabs/upsells/related from summary (we output related in "تسوق أكثر" section).
 */
function sweet_house_single_product_remove_default_sections() {
	if ( function_exists( 'is_product' ) && is_product() ) {
		remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
		remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	}
}
add_action( 'wp', 'sweet_house_single_product_remove_default_sections', 20 );

/**
 * Single product: hide "Related products" heading — we use "تسوق أكثر" from the template.
 */
function sweet_house_hide_related_products_heading() {
	return '';
}
add_filter( 'woocommerce_product_related_products_heading', 'sweet_house_hide_related_products_heading' );

/**
 * Single product "شراء الآن": redirect to checkout after add to cart when buy_now param is set.
 */
function sweet_house_add_to_cart_redirect_checkout( $url, $product_id ) {
	if ( isset( $_POST['sweet_house_buy_now'] ) && $_POST['sweet_house_buy_now'] && function_exists( 'wc_get_checkout_url' ) ) {
		return wc_get_checkout_url();
	}
	return $url;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'sweet_house_add_to_cart_redirect_checkout', 10, 2 );

/**
 * WooCommerce cart: strip <br> from quantity input HTML.
 */
function sweet_house_cart_item_quantity_strip_br( $quantity_html ) {
	return str_replace( array( '<br>', '<br/>', '<br />' ), '', $quantity_html );
}
add_filter( 'woocommerce_cart_item_quantity', 'sweet_house_cart_item_quantity_strip_br', 5 );

/**
 * WooCommerce cart (Phase 2): disable default WooCommerce styles so Sweet House design applies.
 * When SWEET_HOUSE_CART_USE_STATIC_DESIGN is false, cart uses WooCommerce; this ensures our CSS takes precedence.
 */
function sweet_house_cart_disable_woo_styles( $styles ) {
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		return array();
	}
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		return array();
	}
	return $styles;
}
add_filter( 'woocommerce_enqueue_styles', 'sweet_house_cart_disable_woo_styles', 20 );

/**
 * WooCommerce cart: dequeue wc-cart script to avoid conflict with Sweet House AJAX.
 */
function sweet_house_cart_dequeue_wc_cart_script() {
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		wp_dequeue_script( 'wc-cart' );
	}
}
add_action( 'wp_enqueue_scripts', 'sweet_house_cart_dequeue_wc_cart_script', 100 );

/**
 * WooCommerce registration: validate billing_phone (required, format 05 + 8 digits).
 */
function sweet_house_registration_validate_billing_phone( $validation_error, $username, $password, $email ) {
	$phone = isset( $_POST['billing_phone'] ) ? trim( sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) ) : '';
	if ( empty( $phone ) ) {
		$validation_error->add( 'billing_phone_required', __( 'رقم الجوال مطلوب.', 'sweet-house-theme' ) );
		return $validation_error;
	}
	if ( ! preg_match( '/^05\d{8}$/', $phone ) ) {
		$validation_error->add( 'billing_phone_invalid', __( 'رقم الجوال يجب أن يبدأ بـ 05 ويتكون من 10 أرقام (مثال: 0512345678).', 'sweet-house-theme' ) );
	}
	return $validation_error;
}
add_filter( 'woocommerce_process_registration_errors', 'sweet_house_registration_validate_billing_phone', 10, 4 );

/**
 * WooCommerce registration: validate password confirm matches password.
 */
function sweet_house_registration_validate_password_confirm( $validation_error, $username, $password, $email ) {
	if ( 'no' !== get_option( 'woocommerce_registration_generate_password' ) ) {
		return $validation_error;
	}
	$confirm = isset( $_POST['password_confirm'] ) ? $_POST['password_confirm'] : '';
	if ( $password !== $confirm ) {
		$validation_error->add( 'password_mismatch', __( 'كلمة المرور غير متطابقة. يرجى التأكد من تطابق حقل إعادة كتابة كلمة المرور.', 'sweet-house-theme' ) );
	}
	return $validation_error;
}
add_filter( 'woocommerce_process_registration_errors', 'sweet_house_registration_validate_password_confirm', 10, 4 );

/**
 * WooCommerce registration: save billing_phone to user meta.
 */
function sweet_house_registration_save_billing_phone( $customer_id ) {
	if ( ! empty( $_POST['billing_phone'] ) && is_string( $_POST['billing_phone'] ) ) {
		$phone = sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) );
		if ( preg_match( '/^05\d{8}$/', $phone ) ) {
			update_user_meta( $customer_id, 'billing_phone', $phone );
		}
	}
}
add_action( 'woocommerce_created_customer', 'sweet_house_registration_save_billing_phone', 10, 1 );

/**
 * WooCommerce breadcrumb: use Arabic labels on cart page (الرئيسية / سلة المشتريات).
 */
function sweet_house_cart_breadcrumb_arabic( $crumbs, $breadcrumb ) {
	if ( empty( $crumbs ) ) {
		return $crumbs;
	}
	$last = count( $crumbs ) - 1;
	foreach ( $crumbs as $i => &$crumb ) {
		if ( ! is_array( $crumb ) ) {
			continue;
		}
		$title = $crumb[0];
		if ( in_array( $title, array( 'Home', __( 'Home', 'woocommerce' ) ), true ) ) {
			$crumb[0] = __( 'الرئيسية', 'sweet-house-theme' );
		}
		if ( $i !== $last ) {
			continue;
		}
		if ( function_exists( 'is_cart' ) && is_cart() ) {
			$crumb[0] = __( 'سلة المشتريات', 'sweet-house-theme' );
		} elseif ( function_exists( 'is_checkout' ) && is_checkout() && function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-received' ) ) {
			$crumb[0] = __( 'شكراً لطلبك', 'sweet-house-theme' );
		} elseif ( function_exists( 'is_checkout' ) && is_checkout() ) {
			$crumb[0] = __( 'الدفع', 'sweet-house-theme' );
		}
	}
	return $crumbs;
}
add_filter( 'woocommerce_get_breadcrumb', 'sweet_house_cart_breadcrumb_arabic', 20, 2 );

/**
 * WooCommerce cart: output proceed to checkout button without <br> or extra whitespace.
 */
function sweet_house_proceed_to_checkout_button() {
	echo '<a href="' . esc_url( wc_get_checkout_url() ) . '" class="btn-auth checkout-button button alt wc-forward">' . esc_html__( 'المتابعة لإتمام الطلب', 'sweet-house-theme' ) . '</a>';
}
add_action( 'wp', 'sweet_house_replace_proceed_to_checkout_button', 25 );
function sweet_house_replace_proceed_to_checkout_button() {
	remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
	add_action( 'woocommerce_proceed_to_checkout', 'sweet_house_proceed_to_checkout_button', 20 );
}

/**
 * WooCommerce cart: use classic shortcode template instead of block (for Sweet House design).
 */
function sweet_house_cart_use_classic_template( $content ) {
	if ( function_exists( 'is_cart' ) && is_cart() && has_block( 'woocommerce/cart' ) ) {
		return do_shortcode( '[woocommerce_cart]' );
	}
	return $content;
}
add_filter( 'the_content', 'sweet_house_cart_use_classic_template', 5 );

/**
 * WooCommerce checkout: use classic shortcode template instead of block (for Sweet House design).
 */
function sweet_house_checkout_use_classic_template( $content ) {
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return do_shortcode( '[woocommerce_checkout]' );
	}
	return $content;
}
add_filter( 'the_content', 'sweet_house_checkout_use_classic_template', 1 );

/**
 * WooCommerce checkout: short-circuit block render (backup for blocks that bypass the_content).
 */
function sweet_house_checkout_pre_render_block( $pre_render, $parsed_block, $parent_block ) {
	if ( isset( $parsed_block['blockName'] ) && 'woocommerce/checkout' === $parsed_block['blockName'] ) {
		if ( function_exists( 'is_checkout' ) && is_checkout() ) {
			return do_shortcode( '[woocommerce_checkout]' );
		}
	}
	return $pre_render;
}
add_filter( 'pre_render_block', 'sweet_house_checkout_pre_render_block', 10, 3 );

/**
 * One-time migration: ensure checkout page uses classic shortcode for Sweet House design.
 */
function sweet_house_maybe_migrate_checkout_page() {
	if ( get_option( 'sweet_house_checkout_migrated', false ) ) {
		return;
	}
	$checkout_id = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'checkout' ) : 0;
	if ( ! $checkout_id ) {
		return;
	}
	$post = get_post( $checkout_id );
	if ( ! $post || has_shortcode( $post->post_content, 'woocommerce_checkout' ) ) {
		update_option( 'sweet_house_checkout_migrated', true );
		return;
	}
	wp_update_post(
		array(
			'ID'           => $checkout_id,
			'post_content' => '<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->',
		)
	);
	update_option( 'sweet_house_checkout_migrated', true );
}
add_action( 'init', 'sweet_house_maybe_migrate_checkout_page', 5 );

/**
 * Get wishlist product IDs (cookie + user meta for logged-in).
 */
function sweet_house_get_wishlist_ids() {
	$ids = array();
	if ( ! empty( $_COOKIE['sweet_house_wishlist'] ) ) {
		$decoded = json_decode( wp_unslash( $_COOKIE['sweet_house_wishlist'] ), true );
		if ( is_array( $decoded ) ) {
			$ids = array_merge( $ids, $decoded );
		}
	}
	if ( is_user_logged_in() ) {
		$meta = get_user_meta( get_current_user_id(), 'sweet_house_wishlist', true );
		if ( is_array( $meta ) ) {
			$ids = array_merge( $ids, $meta );
		}
	}
	$ids = array_values( array_unique( array_filter( array_map( 'absint', $ids ) ) ) );
	return $ids;
}

/**
 * Sync wishlist from cookie to user meta on login (keep after login).
 */
function sweet_house_sync_wishlist_from_cookie() {
	if ( ! is_user_logged_in() || empty( $_COOKIE['sweet_house_wishlist'] ) ) {
		return;
	}
	$decoded = json_decode( wp_unslash( $_COOKIE['sweet_house_wishlist'] ), true );
	if ( ! is_array( $decoded ) ) {
		return;
	}
	$ids = array_values( array_unique( array_filter( array_map( 'absint', $decoded ) ) ) );
	update_user_meta( get_current_user_id(), 'sweet_house_wishlist', $ids );
}
add_action( 'init', 'sweet_house_sync_wishlist_from_cookie', 5 );

/**
 * WooCommerce cart: output design header (panner) — full width.
 */
function sweet_house_cart_design_header() {
	echo '<header data-y="design-header" class="cart-design-header"><img src="' . esc_url( sweet_house_asset_uri( 'assets/panner.png' ) ) . '" alt="' . esc_attr__( 'بانر سويت هاوس - متجر الحلويات والمخبوزات', 'sweet-house-theme' ) . '" class="panner-img" /></header>';
}
add_action( 'woocommerce_before_cart', 'sweet_house_cart_design_header', 5 );

/**
 * WooCommerce checkout: output design header (panner) before checkout content.
 */
function sweet_house_checkout_design_header() {
	if ( function_exists( 'is_checkout' ) && is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
		echo '<header data-y="design-header" class="checkout-design-header"><img src="' . esc_url( sweet_house_asset_uri( 'assets/panner.png' ) ) . '" alt="' . esc_attr__( 'بانر سويت هاوس - متجر الحلويات والمخبوزات', 'sweet-house-theme' ) . '" class="panner-img" /></header>';
	}
}
add_action( 'woocommerce_before_checkout_form', 'sweet_house_checkout_design_header', 5 );

/**
 * WooCommerce checkout: split order review and payment for 2-column design layout.
 */
function sweet_house_checkout_split_order_review() {
	if ( function_exists( 'is_checkout' ) && is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
		add_action( 'sweet_house_checkout_order_summary', 'woocommerce_order_review', 10 );
		add_action( 'sweet_house_checkout_payment_section', 'woocommerce_checkout_payment', 10 );
	}
}
add_action( 'wp', 'sweet_house_checkout_split_order_review', 20 );

/**
 * WooCommerce checkout: Arabic place order button text.
 */
function sweet_house_order_button_text( $text ) {
	return __( 'إتمام الدفع', 'sweet-house-theme' );
}
add_filter( 'woocommerce_order_button_text', 'sweet_house_order_button_text' );

/**
 * WooCommerce checkout: ترجمة عناوين ووصف طرق الدفع للعربية.
 */
function sweet_house_payment_gateway_arabic( $description, $gateway_id ) {
	$translations = array(
		'bacs' => __( 'قم بالتحويل المباشر إلى حسابنا البنكي. يرجى استخدام رقم الطلب كمرجع للدفعة. لن يتم شحن طلبك حتى يتم استلام المبلغ في حسابنا.', 'sweet-house-theme' ),
		'cod'  => __( 'ادفع نقداً عند الاستلام.', 'sweet-house-theme' ),
	);
	if ( isset( $translations[ $gateway_id ] ) ) {
		return $translations[ $gateway_id ];
	}
	return $description;
}
add_filter( 'woocommerce_gateway_description', 'sweet_house_payment_gateway_arabic', 10, 2 );

function sweet_house_payment_gateway_title_arabic( $title, $gateway_id ) {
	$translations = array(
		'bacs' => __( 'تحويل بنكي مباشر', 'sweet-house-theme' ),
		'cod'  => __( 'الدفع عند الاستلام', 'sweet-house-theme' ),
	);
	if ( isset( $translations[ $gateway_id ] ) ) {
		return $translations[ $gateway_id ];
	}
	return $title;
}
add_filter( 'woocommerce_gateway_title', 'sweet_house_payment_gateway_title_arabic', 10, 2 );

/**
 * WooCommerce checkout: ترجمة رسائل التحقق (الحقول المطلوبة) للعربية.
 */
function sweet_house_checkout_required_field_notice_arabic( $notice, $field_label, $key ) {
	$messages = array(
		'billing_first_name'   => __( '<strong>الاسم الكامل</strong> حقل مطلوب.', 'sweet-house-theme' ),
		'billing_address_1'    => __( '<strong>العنوان</strong> حقل مطلوب.', 'sweet-house-theme' ),
		'billing_email'       => __( '<strong>البريد الإلكتروني</strong> حقل مطلوب.', 'sweet-house-theme' ),
		'billing_phone'       => __( '<strong>رقم الجوال</strong> حقل مطلوب.', 'sweet-house-theme' ),
		'account_password'   => __( '<strong>كلمة المرور</strong> حقل مطلوب.', 'sweet-house-theme' ),
	);
	if ( isset( $messages[ $key ] ) ) {
		return $messages[ $key ];
	}
	return sprintf( __( '%s حقل مطلوب.', 'sweet-house-theme' ), '<strong>' . esc_html( $field_label ) . '</strong>' );
}
add_filter( 'woocommerce_checkout_required_field_notice', 'sweet_house_checkout_required_field_notice_arabic', 10, 3 );

/**
 * WooCommerce checkout: simplify fields (الاسم الكامل، الإيميل، الجوال، العنوان).
 * + كلمة المرور للزوار (إنشاء حساب).
 */
function sweet_house_checkout_fields_simplified( $fields ) {
	if ( ! isset( $fields['billing'] ) ) {
		return $fields;
	}

	$billing = &$fields['billing'];

	// دمج الاسم: حقل واحد "الاسم الكامل"
	unset( $billing['billing_last_name'] );
	$billing['billing_first_name']['label']       = __( 'الاسم الكامل', 'sweet-house-theme' );
	$billing['billing_first_name']['placeholder'] = __( 'أدخل الاسم الكامل', 'sweet-house-theme' );
	$billing['billing_first_name']['class']       = array( 'form-row-wide' );
	$billing['billing_first_name']['priority']    = 10;

	// حقل الإيميل
	$billing['billing_email']['class'] = array( 'form-row-wide' );
	$billing['billing_email']['placeholder'] = __( 'example@gmail.com', 'sweet-house-theme' );

	// حقل الجوال
	$billing['billing_phone']['class'] = array( 'form-row-wide' );
	$billing['billing_phone']['placeholder'] = __( '05xxxxxxxx', 'sweet-house-theme' );

	// حقل العنوان الواحد
	$billing['billing_address_1']['label']       = __( 'العنوان', 'sweet-house-theme' );
	$billing['billing_address_1']['placeholder'] = __( 'رقم المنزل اسم الشارع / الحي', 'sweet-house-theme' );
	$billing['billing_address_1']['class']      = array( 'form-row-wide' );
	$billing['billing_address_1']['priority']   = 50;

	unset( $billing['billing_address_2'] );
	unset( $billing['billing_city'] );
	unset( $billing['billing_state'] );
	unset( $billing['billing_postcode'] );

	// إخفاء الدولة ووضع افتراضي للسعودية
	$billing['billing_country']['type']     = 'country';
	$billing['billing_country']['class']    = array( 'form-row-wide', 'billing-country-hidden' );
	$billing['billing_country']['default']  = 'SA';
	$billing['billing_country']['required'] = false;

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'sweet_house_checkout_fields_simplified', 20 );

/**
 * WooCommerce checkout: دولة افتراضية للسعودية عند تحميل الصفحة.
 */
function sweet_house_checkout_default_country( $value, $input ) {
	if ( 'billing_country' === $input && empty( $value ) ) {
		return 'SA';
	}
	return $value;
}
add_filter( 'woocommerce_checkout_get_value', 'sweet_house_checkout_default_country', 10, 2 );

/**
 * WooCommerce checkout: Arabic field labels.
 */
function sweet_house_checkout_field_labels( $field, $key ) {
	$labels = array(
		'billing_first_name'   => __( 'الاسم الكامل', 'sweet-house-theme' ),
		'billing_email'        => __( 'البريد الإلكتروني', 'sweet-house-theme' ),
		'billing_phone'        => __( 'رقم الجوال', 'sweet-house-theme' ),
		'billing_address_1'    => __( 'العنوان', 'sweet-house-theme' ),
		'account_password'     => __( 'كلمة المرور', 'sweet-house-theme' ),
	);
	if ( isset( $labels[ $key ] ) ) {
		$field['label'] = $labels[ $key ];
	}
	return $field;
}
add_filter( 'sweet_house_checkout_field_args', 'sweet_house_checkout_field_labels', 10, 2 );

/**
 * WooCommerce checkout: ترجمة ملاحظات الطلب.
 */
function sweet_house_order_notes_field_args( $fields ) {
	if ( isset( $fields['order']['order_comments'] ) ) {
		$fields['order']['order_comments']['label']       = __( 'إضافة ملحوظة', 'sweet-house-theme' );
		$fields['order']['order_comments']['placeholder'] = __( 'أي ملاحظات إضافية...', 'sweet-house-theme' );
	}
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'sweet_house_order_notes_field_args', 25 );

/**
 * WooCommerce checkout: حفظ الاسم الكامل في first_name (last_name فارغ).
 */
function sweet_house_checkout_save_full_name( $data ) {
	if ( ! empty( $data['billing_first_name'] ) && empty( $data['billing_last_name'] ) ) {
		$data['billing_last_name'] = '';
	}
	return $data;
}
add_filter( 'woocommerce_checkout_posted_data', 'sweet_house_checkout_save_full_name' );

/**
 * WooCommerce checkout: قيم افتراضية للحقول المخفية (العنوان المفرد).
 */
function sweet_house_checkout_default_address_fields( $data ) {
	if ( ! empty( $data['billing_address_1'] ) ) {
		if ( empty( $data['billing_country'] ) ) {
			$data['billing_country'] = 'SA';
		}
		if ( empty( $data['billing_city'] ) ) {
			$data['billing_city'] = $data['billing_address_1'];
		}
		if ( empty( $data['billing_state'] ) ) {
			$data['billing_state'] = '';
		}
		if ( empty( $data['billing_postcode'] ) ) {
			$data['billing_postcode'] = '';
		}
	}
	return $data;
}
add_filter( 'woocommerce_checkout_posted_data', 'sweet_house_checkout_default_address_fields', 15 );

/**
 * My Account / Auth: output design header (panner) before account content.
 */
function sweet_house_account_design_header() {
	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() ) {
		return;
	}
	echo '<header data-y="design-header" class="account-design-header"><img src="' . esc_url( sweet_house_asset_uri( 'assets/panner.png' ) ) . '" alt="' . esc_attr__( 'بانر سويت هاوس - حسابي', 'sweet-house-theme' ) . '" class="panner-img" /></header>';
}
add_action( 'woocommerce_before_customer_login_form', 'sweet_house_account_design_header', 5 );
add_action( 'woocommerce_before_lost_password_form', 'sweet_house_account_design_header', 5 );
add_action( 'woocommerce_before_reset_password_form', 'sweet_house_account_design_header', 5 );

/**
 * Shortcode: صفحة إنشاء حساب منفصلة [sweet_house_register]
 */
function sweet_house_register_shortcode() {
	if ( 'yes' !== get_option( 'woocommerce_enable_myaccount_registration' ) ) {
		return '<p>' . esc_html__( 'التسجيل غير متاح حالياً.', 'sweet-house-theme' ) . '</p>';
	}
	ob_start();
	echo '<header data-y="design-header" class="account-design-header"><img src="' . esc_url( sweet_house_asset_uri( 'assets/panner.png' ) ) . '" alt="' . esc_attr__( 'بانر سويت هاوس - إنشاء حساب', 'sweet-house-theme' ) . '" class="panner-img" /></header>';
	?>
	<div class="auth-container">
		<div class="form">
			<h1>
				<span>
					<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user-plus">
						<path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
						<circle cx="8.5" cy="7" r="4"></circle>
						<line x1="20" y1="8" x2="20" y2="14"></line>
						<line x1="23" y1="11" x2="17" y2="11"></line>
					</svg>
				</span>
				<?php esc_html_e( 'إنشاء حساب جديد', 'sweet-house-theme' ); ?>
			</h1>

			<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>
				<?php do_action( 'woocommerce_register_form_start' ); ?>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
				<div class="form-group">
					<label for="reg_username"><?php esc_html_e( 'اسم المستخدم', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" required aria-required="true" />
				</div>
				<?php endif; ?>

				<div class="form-group">
					<label for="reg_email"><?php esc_html_e( 'البريد الإلكتروني', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" required aria-required="true" />
				</div>

				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
				<div class="form-group">
					<label for="reg_password"><?php esc_html_e( 'كلمة المرور', 'sweet-house-theme' ); ?>&nbsp;<span class="required">*</span></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" required aria-required="true" />
				</div>
				<?php else : ?>
				<p><?php esc_html_e( 'سيصلك رابط لتعيين كلمة مرور جديدة عبر البريد الإلكتروني.', 'sweet-house-theme' ); ?></p>
				<?php endif; ?>

				<?php do_action( 'woocommerce_register_form' ); ?>

				<p class="form-row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="btn-auth woocommerce-Button woocommerce-button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'إنشاء حساب', 'sweet-house-theme' ); ?>"><?php esc_html_e( 'إنشاء حساب', 'sweet-house-theme' ); ?></button>
				</p>

				<?php do_action( 'woocommerce_register_form_end' ); ?>
			</form>

			<p class="text y-u-text-center">
				<?php esc_html_e( 'لديك حساب بالفعل؟', 'sweet-house-theme' ); ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="y-t-text-decoration-none"><?php esc_html_e( 'تسجيل دخول', 'sweet-house-theme' ); ?></a>
			</p>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'sweet_house_register', 'sweet_house_register_shortcode' );

/**
 * Redirect logged-in users from sign-up page to my-account.
 */
function sweet_house_signup_redirect_logged_in() {
	if ( is_page( 'sign-up' ) && is_user_logged_in() ) {
		wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
		exit;
	}
}
add_action( 'template_redirect', 'sweet_house_signup_redirect_logged_in' );

/**
 * Handle contact form submission (before any output).
 */
function sweet_house_handle_contact_form() {
	if ( ! ( is_page( 'contact-us' ) || is_page( 'contact' ) ) ) {
		return;
	}
	if ( 'POST' !== ( isset( $_SERVER['REQUEST_METHOD'] ) ? $_SERVER['REQUEST_METHOD'] : '' ) ) {
		return;
	}
	if ( ! isset( $_POST['sweet_house_contact_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sweet_house_contact_nonce'] ) ), 'sweet_house_contact' ) ) {
		return;
	}
	$name    = isset( $_POST['contact_name'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) : '';
	$email   = isset( $_POST['contact_email'] ) ? sanitize_email( wp_unslash( $_POST['contact_email'] ) ) : '';
	$phone   = isset( $_POST['contact_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_phone'] ) ) : '';
	$topic   = isset( $_POST['contact_topic'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_topic'] ) ) : '';
	$message = isset( $_POST['contact_message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) : '';
	if ( $name && $email && $phone && $topic && $message ) {
		$to      = function_exists( 'sweet_house_get_contact_settings' ) ? sweet_house_get_contact_settings()['recipient_email'] : get_option( 'admin_email' );
		if ( empty( $to ) || ! is_email( $to ) ) {
			$to = get_option( 'admin_email' );
		}
		$subject = sprintf( '[%s] %s', get_bloginfo( 'name' ), $topic );
		$body    = sprintf(
			"%s\n\n%s: %s\n%s: %s\n%s: %s\n\n%s",
			$message,
			__( 'الاسم', 'sweet-house-theme' ),
			$name,
			__( 'البريد الإلكتروني', 'sweet-house-theme' ),
			$email,
			__( 'رقم الهاتف', 'sweet-house-theme' ),
			$phone
		);
		$headers = array( 'Content-Type: text/plain; charset=UTF-8', 'Reply-To: ' . $name . ' <' . $email . '>' );
		$sent    = function_exists( 'sweet_house_send_mail_with_config' ) ? sweet_house_send_mail_with_config( $to, $subject, $body ) : wp_mail( $to, $subject, $body, $headers );
		$GLOBALS['sweet_house_contact_sent']  = $sent;
		$GLOBALS['sweet_house_contact_error'] = false;
	} else {
		$GLOBALS['sweet_house_contact_sent'] = false;
		$GLOBALS['sweet_house_contact_error'] = true;
	}
}
add_action( 'template_redirect', 'sweet_house_handle_contact_form', 1 );

/**
 * WooCommerce: change "View cart" link text to "انظر السلة" on add to cart.
 */
function sweet_house_view_cart_text( $params, $handle ) {
	if ( 'wc-add-to-cart' === $handle && isset( $params['i18n_view_cart'] ) ) {
		$params['i18n_view_cart'] = 'انظر السلة';
	}
	return $params;
}
add_filter( 'woocommerce_get_script_data', 'sweet_house_view_cart_text', 10, 2 );

/**
 * WooCommerce: translate "View cart" in PHP notices to "انظر السلة".
 */
function sweet_house_view_cart_translation( $translated, $text, $domain ) {
	if ( 'woocommerce' === $domain && 'View cart' === $text ) {
		return 'انظر السلة';
	}
	return $translated;
}
add_filter( 'gettext', 'sweet_house_view_cart_translation', 20, 3 );

/**
 * WooCommerce: cart count fragment for header badge (AJAX add to cart).
 */
function sweet_house_cart_count_fragment( $fragments ) {
	$cart_count = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
	$cart_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : '';
	ob_start();
	?>
	<a href="<?php echo esc_url( $cart_url ); ?>" class="cart-icon-link" aria-label="<?php echo esc_attr( sprintf( __( 'السلة (%d)', 'sweet-house-theme' ), $cart_count ) ); ?>">
		<i class="fa-solid fa-basket-shopping"></i>
		<span class="cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
	</a>
	<?php
	$fragments['.header .user-nav .cart-icon-link'] = ob_get_clean();
	return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'sweet_house_cart_count_fragment' );

/**
 * Fix sign-up routing: عند 404 أو تحميل الصفحة الأم (sweet-house) بدلاً من sign-up.
 * يدعم المسار /sweet-house/sign-up/ ليتوافق مع /sweet-house/my-account/lost-password/.
 */
function sweet_house_sign_up_fix_query() {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$uri = strtok( $uri, '?' );
	if ( false === strpos( $uri, 'sign-up' ) || ! preg_match( '#/sign-up/?$#', $uri ) ) {
		return;
	}
	$signup = sweet_house_get_page_by_slug( 'sign-up' );
	if ( ! $signup ) {
		return;
	}
	$queried = get_queried_object();
	$is_wrong = is_404()
		|| ( $queried && isset( $queried->post_name ) && 'sweet-house' === $queried->post_name );
	if ( ! $is_wrong ) {
		return;
	}
	global $wp_query;
	$wp_query = new WP_Query(
		array(
			'page_id'     => $signup->ID,
			'post_type'   => 'page',
			'post_status' => 'publish',
		)
	);
	$wp_query->is_404             = false;
	$wp_query->is_page             = true;
	$wp_query->is_singular         = true;
	$wp_query->queried_object      = $signup;
	$wp_query->queried_object_id   = $signup->ID;
}
add_action( 'wp', 'sweet_house_sign_up_fix_query', 5 );
add_action( 'template_redirect', 'sweet_house_sign_up_fix_query', 1 );

/**
 * Redirect /sweet-house/ to my-account (الصفحة الأم للروابط الفرعية فقط).
 */
function sweet_house_redirect_sweet_house_to_my_account() {
	$queried = get_queried_object();
	if ( ! $queried || ! isset( $queried->post_name ) || 'sweet-house' !== $queried->post_name ) {
		return;
	}
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	if ( false === strpos( $uri, 'sign-up' ) && false === strpos( $uri, 'my-account' ) ) {
		$my_account = sweet_house_get_page_by_slug( 'my-account' );
		$redirect   = $my_account ? get_permalink( $my_account->ID ) : home_url( '/' );
		wp_safe_redirect( $redirect, 302 );
		exit;
	}
}
add_action( 'template_redirect', 'sweet_house_redirect_sweet_house_to_my_account', 2 );

/**
 * إصلاح صفحة الوصفة المنفردة: عند مسار /recipe/{slug}/ أو /sweet-house/recipe/{slug}/
 * يتم تحميل الوصفة وعرض قالب single-recipe.php.
 */
function sweet_house_single_recipe_fix_query() {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	$uri = strtok( $uri, '?' );
	if ( ! preg_match( '#/recipe/([^/]+)/?$#', $uri, $m ) ) {
		return;
	}
	$recipe_slug = $m[1];
	$recipe      = get_page_by_path( $recipe_slug, OBJECT, 'recipe' );
	if ( ! $recipe || 'recipe' !== get_post_type( $recipe ) ) {
		return;
	}
	$queried = get_queried_object();
	$needs_fix = is_404()
		|| ! have_posts()
		|| ( $queried && isset( $queried->post_name ) && in_array( $queried->post_name, array( 'sweet-house' ), true ) );
	if ( ! $needs_fix ) {
		return;
	}
	global $wp_query;
	$wp_query = new WP_Query(
		array(
			'p'         => $recipe->ID,
			'post_type' => 'recipe',
		)
	);
	$wp_query->is_404           = false;
	$wp_query->is_singular      = true;
	$wp_query->is_single        = true;
	$wp_query->queried_object   = $recipe;
	$wp_query->queried_object_id = $recipe->ID;
}
add_action( 'wp', 'sweet_house_single_recipe_fix_query', 5 );

/**
 * تحميل قالب single-recipe.php عند مسار الوصفة.
 */
function sweet_house_template_include_single_recipe( $template ) {
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	if ( ! preg_match( '#/recipe/([^/]+)/?$#', $uri, $m ) ) {
		return $template;
	}
	$recipe = get_page_by_path( $m[1], OBJECT, 'recipe' );
	if ( $recipe && 'recipe' === get_post_type( $recipe ) ) {
		$single_file = get_template_directory() . '/single-recipe.php';
		if ( file_exists( $single_file ) ) {
			return $single_file;
		}
	}
	return $template;
}
add_filter( 'template_include', 'sweet_house_template_include_single_recipe', 5 );

/**
 * Force sign-up template for sign-up page (including child path sweet-house/sign-up).
 */
function sweet_house_template_include_sign_up( $template ) {
	$signup_file = get_template_directory() . '/page-sign-up.php';
	if ( ! file_exists( $signup_file ) ) {
		return $template;
	}
	$queried = get_queried_object();
	if ( $queried && isset( $queried->post_name ) && 'sign-up' === $queried->post_name ) {
		return $signup_file;
	}
	$signup = sweet_house_get_page_by_slug( 'sign-up' );
	if ( $signup && is_page() && (int) get_queried_object_id() === (int) $signup->ID ) {
		return $signup_file;
	}
	// Fallback: عند زيارة مسار يحتوي sign-up (مثلاً /sweet-house/sign-up/) حتى لو لم يتطابق الاستعلام.
	$uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
	if ( $signup && ( false !== strpos( $uri, 'sign-up' ) || preg_match( '#/sign-up/?$#', $uri ) ) ) {
		return $signup_file;
	}
	return $template;
}
add_filter( 'template_include', 'sweet_house_template_include_sign_up', 5 );

/**
 * إذا كانت صفحة إنشاء الحساب تستخدم القالب الافتراضي ومحتواها فارغ، اعرض النموذج.
 */
function sweet_house_sign_up_empty_content_fallback( $content ) {
	if ( ! is_page( 'sign-up' ) ) {
		return $content;
	}
	$content = trim( $content );
	if ( '' !== $content ) {
		return $content;
	}
	return do_shortcode( '[sweet_house_register]' );
}
add_filter( 'the_content', 'sweet_house_sign_up_empty_content_fallback', 5 );

/**
 * Cart page: use static design template when SWEET_HOUSE_CART_USE_STATIC_DESIGN is true (Phase 1).
 * Bypasses WooCommerce completely for design verification.
 */
function sweet_house_template_include_cart_static( $template ) {
	if ( ! defined( 'SWEET_HOUSE_CART_USE_STATIC_DESIGN' ) || ! SWEET_HOUSE_CART_USE_STATIC_DESIGN ) {
		return $template;
	}
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		$static_file = get_template_directory() . '/page-cart-static.php';
		if ( file_exists( $static_file ) ) {
			return $static_file;
		}
	}
	return $template;
}
add_filter( 'template_include', 'sweet_house_template_include_cart_static', 5 );

/**
 * Use policy template for all policy pages (privacy, refund, shipping, policy).
 */
function sweet_house_template_include_policy( $template ) {
	$policy_slugs = array( 'policy', 'privacy-policy', 'refund-policy', 'shipping-policy', 'privacy_policy' );
	if ( ! is_page() ) {
		return $template;
	}
	$page = get_queried_object();
	if ( ! $page || ! isset( $page->post_name ) ) {
		return $template;
	}
	$slug = $page->post_name;
	$privacy_id = function_exists( 'wp_privacy_policy_page_id' ) ? wp_privacy_policy_page_id() : 0;
	$is_privacy = $privacy_id && (int) $page->ID === (int) $privacy_id;
	if ( in_array( $slug, $policy_slugs, true ) || $is_privacy ) {
		$policy_template = get_template_directory() . '/page-policy.php';
		if ( file_exists( $policy_template ) ) {
			return $policy_template;
		}
	}
	return $template;
}
add_filter( 'template_include', 'sweet_house_template_include_policy', 15 );

/**
 * عرض أرشيف الوصفات عند زيارة صفحة وصفاتنا (/recepies/).
 */
function sweet_house_template_include_recipes_page( $template ) {
	$page = get_queried_object();
	if ( ! $page || ! is_page() || 'recepies' !== $page->post_name ) {
		return $template;
	}
	$archive = get_template_directory() . '/archive-recipe.php';
	if ( file_exists( $archive ) ) {
		return $archive;
	}
	return $template;
}
add_filter( 'template_include', 'sweet_house_template_include_recipes_page', 12 );

/**
 * Get page object by slug, including child pages (e.g. sweet-house/sign-up).
 */
function sweet_house_get_page_by_slug( $slug ) {
	$page = get_page_by_path( $slug );
	if ( ! $page && 'sign-up' === $slug ) {
		$page = get_page_by_path( 'sweet-house/sign-up' );
	}
	if ( ! $page && 'my-account' === $slug ) {
		$page = get_page_by_path( 'sweet-house/my-account' );
	}
	return $page;
}

/**
 * Get URL for a page by slug (e.g. shop, cart, offers).
 */
function sweet_house_get_page_url( $slug, $fallback = '' ) {
	$page = sweet_house_get_page_by_slug( $slug );
	if ( $page ) {
		return get_permalink( $page->ID );
	}
	if ( $fallback ) {
		return $fallback;
	}
	return home_url( '/' . $slug );
}

/**
 * Check if current page is a policy page (privacy, refund, shipping, or generic policy).
 */
function sweet_house_is_policy_page() {
	if ( ! is_page() ) {
		return false;
	}
	$policy_slugs = array( 'policy', 'privacy-policy', 'refund-policy', 'shipping-policy', 'privacy_policy' );
	$page = get_queried_object();
	if ( ! $page || ! isset( $page->post_name ) ) {
		return false;
	}
	if ( in_array( $page->post_name, $policy_slugs, true ) ) {
		return true;
	}
	$privacy_id = function_exists( 'wp_privacy_policy_page_id' ) ? wp_privacy_policy_page_id() : 0;
	return $privacy_id && (int) $page->ID === (int) $privacy_id;
}

/**
 * Current page key for body/data-current-page and nav state.
 */
function sweet_house_current_page_key() {
	if ( is_front_page() ) {
		return 'home';
	}
	if ( function_exists( 'is_shop' ) && is_shop() ) {
		return 'products';
	}
	if ( function_exists( 'is_product' ) && is_product() ) {
		return 'single-product';
	}
	if ( is_page( 'offers' ) ) {
		return 'offers';
	}
	if ( is_page( 'wishlist' ) ) {
		return 'wishlist';
	}
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		return 'cart';
	}
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		return 'payment';
	}
	if ( is_page( 'my-account' ) ) {
		return 'my-account';
	}
	if ( is_page( 'sign-up' ) ) {
		return 'sign-up';
	}
	if ( is_page( 'contact-us' ) || is_page( 'contact' ) ) {
		return 'contact-us';
	}
	if ( sweet_house_is_policy_page() ) {
		return 'policy';
	}
	if ( is_post_type_archive( 'recipe' ) || ( is_page() && get_queried_object() && 'recepies' === get_queried_object()->post_name ) ) {
		return 'recipe';
	}
	if ( is_singular( 'recipe' ) ) {
		return 'single-recipe';
	}
	return '';
}

/**
 * Home page: التصنيفات section — real WooCommerce product categories (design: y-c-categories-sec).
 */
function sweet_house_render_home_categories() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return;
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => 0,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
			'number'     => 12,
		)
	);
	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return;
	}
	$default_cat_id = (int) get_option( 'default_product_cat' );
	$fallback_imgs = array( 'assets/cat1.png', 'assets/cat2.png', 'assets/cat3.png', 'assets/cat4.png', 'assets/cat6.png', 'assets/cat7.png' );
	$index = 0;
	$cat_content = function_exists( 'sweet_house_get_home_content' ) ? sweet_house_get_home_content() : array();
	$cat_title = isset( $cat_content['categories_title'] ) ? $cat_content['categories_title'] : __( 'أقسام منتجاتنا', 'sweet-house-theme' );
	$cat_subtitle = isset( $cat_content['categories_subtitle'] ) ? $cat_content['categories_subtitle'] : __( 'اكتشف تشكيلة واسعة من أجود الحلويات بألذ النكهات', 'sweet-house-theme' );
	?>
	<section class="py-5 y-section-categories" aria-label="<?php echo esc_attr__( 'التصنيفات', 'sweet-house-theme' ); ?>">
		<div class="container my-5">
			<div class="text-center my-5">
				<h2 class="mb-4 y-t-fs-titles"><?php echo esc_html( $cat_title ); ?></h2>
				<h5 class="cairo-font"><?php echo esc_html( $cat_subtitle ); ?></h5>
			</div>
			<div class="row justify-content-center" dir="ltr">
				<?php foreach ( $terms as $term ) : ?>
					<?php
					if ( $term->term_id === $default_cat_id || 'uncategorized' === $term->slug ) {
						continue;
					}
					$link = get_term_link( $term );
					if ( is_wp_error( $link ) ) {
						continue;
					}
					$thumb_id = get_term_meta( $term->term_id, 'thumbnail_id', true );
					if ( $thumb_id ) {
						$img = wp_get_attachment_image( $thumb_id, 'woocommerce_thumbnail', false, array( 'class' => 'img-fluid rounded-circle mb-2 category-img', 'alt' => esc_attr( $term->name ) ) );
					} else {
						$fallback = isset( $fallback_imgs[ $index % count( $fallback_imgs ) ] ) ? $fallback_imgs[ $index % count( $fallback_imgs ) ] : $fallback_imgs[0];
						$img = '<img src="' . esc_url( sweet_house_asset_uri( $fallback ) ) . '" class="img-fluid rounded-circle mb-2 category-img" alt="' . esc_attr( $term->name ) . '" />';
						$index++;
					}
					?>
					<div class="col-6 col-sm-4 col-md-3 col-lg-2 text-center category-item mb-4">
						<a href="<?php echo esc_url( $link ); ?>" class="d-block">
							<?php echo $img; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<p class="fs-6 fs-md-5 fw-bold"><?php echo esc_html( $term->name ); ?></p>
						</a>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>
	<?php
}

/**
 * Home page: المنتجات section — real WooCommerce products (design: y-c-products-sec, product cards).
 * Uses WP_Query so published products are always found (WC_Product_Query can filter by visibility).
 */
function sweet_house_render_home_products() {
	if ( ! post_type_exists( 'product' ) ) {
		return;
	}
	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post_status'    => 'publish',
			'posts_per_page' => 12,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'no_found_rows'  => true,
		)
	);
	if ( ! $query->have_posts() ) {
		$prod_content = function_exists( 'sweet_house_get_home_content' ) ? sweet_house_get_home_content() : array();
		$prod_title = isset( $prod_content['products_title'] ) ? $prod_content['products_title'] : __( 'المنتجات', 'sweet-house-theme' );
		?>
		<section class="cairo-font y-section-products" aria-label="<?php echo esc_attr__( 'المنتجات', 'sweet-house-theme' ); ?>">
			<div class="container py-5">
				<h2 class="text-center fw-bold mb-4 cairo-font y-t-fs-titles"><?php echo esc_html( $prod_title ); ?></h2>
				<p class="text-center"><?php echo esc_html__( 'لا توجد منتجات حالياً.', 'sweet-house-theme' ); ?></p>
			</div>
		</section>
		<?php
		return;
	}
	$prod_content = function_exists( 'sweet_house_get_home_content' ) ? sweet_house_get_home_content() : array();
	$prod_title = isset( $prod_content['products_title'] ) ? $prod_content['products_title'] : __( 'منتجاتنا', 'sweet-house-theme' );
	?>
	<section class="cairo-font y-section-products" aria-label="<?php echo esc_attr__( 'المنتجات', 'sweet-house-theme' ); ?>">
		<div class="container py-5">
			<h2 class="text-center fw-bold mb-4 cairo-font y-t-fs-titles"><?php echo esc_html( $prod_title ); ?></h2>
			<div class="section section1">
				<ul class="products">
					<?php
					global $product;
					while ( $query->have_posts() ) {
						$query->the_post();
						$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
						if ( ! $product || ! $product->is_visible() ) {
							continue;
						}
						wc_get_template_part( 'content', 'product' );
					}
					wp_reset_postdata();
					?>
				</ul>
			</div>
		</div>
	</section>
	<?php
}

/**
 * حسابي: إزالة الإشعارات والتنزيلات وطرق الدفع من القائمة، وتسمية بالعربية.
 */
function sweet_house_account_menu_items( $items ) {
	unset( $items['notifications'], $items['downloads'], $items['payment-methods'], $items['edit-account'] );
	$keys_to_remove = array( 'notification', 'notifications' );
	foreach ( $keys_to_remove as $key ) {
		foreach ( array_keys( $items ) as $k ) {
			if ( false !== strpos( $k, $key ) ) {
				unset( $items[ $k ] );
			}
		}
	}
	if ( isset( $items['dashboard'] ) ) {
		$items['dashboard'] = __( 'الحساب', 'sweet-house-theme' );
	}
	if ( isset( $items['orders'] ) ) {
		$items['orders'] = __( 'الطلبات', 'sweet-house-theme' );
	}
	if ( isset( $items['edit-address'] ) ) {
		$items['edit-address'] = __( 'العنوان', 'sweet-house-theme' );
	}
	if ( isset( $items['customer-logout'] ) ) {
		$items['customer-logout'] = __( 'تسجيل الخروج', 'sweet-house-theme' );
	}
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'sweet_house_account_menu_items', 20 );

/**
 * ترجمة عناوين صفحات lost-password و reset-password للعربية.
 */
function sweet_house_endpoint_lost_password_title( $title, $endpoint, $action ) {
	if ( 'lost-password' === $endpoint ) {
		if ( in_array( $action, array( 'rp', 'resetpass', 'newaccount' ), true ) ) {
			return __( 'إنشاء كلمة مرور', 'sweet-house-theme' );
		}
		return __( 'استعادة كلمة المرور', 'sweet-house-theme' );
	}
	return $title;
}
add_filter( 'woocommerce_endpoint_lost-password_title', 'sweet_house_endpoint_lost_password_title', 10, 3 );

/**
 * معالجة مسح العنوان من حسابي.
 */
function sweet_house_handle_clear_address() {
	if ( ! isset( $_POST['sweet_house_action'] ) || 'clear_address' !== $_POST['sweet_house_action'] ) {
		return;
	}
	if ( ! isset( $_POST['sweet_house_clear_address_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['sweet_house_clear_address_nonce'] ) ), 'sweet_house_clear_address' ) ) {
		return;
	}
	if ( ! is_user_logged_in() ) {
		return;
	}
	$user_id = get_current_user_id();
	$fields  = array( 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country', 'phone', 'email' );
	foreach ( array( 'billing', 'shipping' ) as $type ) {
		foreach ( $fields as $field ) {
			delete_user_meta( $user_id, $type . '_' . $field );
		}
	}
	if ( function_exists( 'wc_add_notice' ) ) {
		wc_add_notice( __( 'تم مسح العنوان بنجاح.', 'sweet-house-theme' ), 'success' );
	}
	$redirect = function_exists( 'wc_get_endpoint_url' ) ? wc_get_endpoint_url( 'edit-address', '', wc_get_page_permalink( 'myaccount' ) ) : wc_get_page_permalink( 'myaccount' );
	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'template_redirect', 'sweet_house_handle_clear_address', 5 );

/**
 * إعادة توجيه عنوان الشحن إلى العنوان (نستخدم عنواناً واحداً فقط).
 */
function sweet_house_redirect_shipping_to_billing() {
	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'edit-address' ) ) {
		global $wp;
		if ( isset( $wp->query_vars['edit-address'] ) && 'shipping' === $wp->query_vars['edit-address'] ) {
			wp_safe_redirect( wc_get_endpoint_url( 'edit-address', 'billing', wc_get_page_permalink( 'myaccount' ) ) );
			exit;
		}
	}
}
add_action( 'template_redirect', 'sweet_house_redirect_shipping_to_billing', 6 );

/**
 * نسخ عنوان الفاتورة إلى عنوان الشحن عند الحفظ (عنوان واحد لجميع الأغراض).
 */
function sweet_house_sync_billing_to_shipping( $user_id, $address_type, $address, $customer ) {
	if ( 'billing' !== $address_type ) {
		return;
	}
	$fields = array( 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'state', 'postcode', 'country', 'phone' );
	foreach ( $fields as $field ) {
		$value = get_user_meta( $user_id, 'billing_' . $field, true );
		update_user_meta( $user_id, 'shipping_' . $field, $value );
	}
}
add_action( 'woocommerce_customer_save_address', 'sweet_house_sync_billing_to_shipping', 20, 4 );

/**
 * إعادة توجيه edit-account إلى الصفحة الرئيسية (تفاصيل الحساب تظهر هناك).
 */
function sweet_house_redirect_edit_account_to_dashboard() {
	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'edit-account' ) ) {
		wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
		exit;
	}
}
add_action( 'template_redirect', 'sweet_house_redirect_edit_account_to_dashboard' );

/**
 * عناوين ورسائل WooCommerce للعربية (حسابي، المصادقة، الطلبات، إلخ).
 */
function sweet_house_woocommerce_arabic_translations( $translated, $text, $domain ) {
	if ( 'woocommerce' !== $domain ) {
		return $translated;
	}
	$strings = array(
		/* عام */
		'Billing address'             => __( 'العنوان', 'sweet-house-theme' ),
		'Shipping address'            => __( 'عنوان الشحن', 'sweet-house-theme' ),
		'Orders'                      => __( 'الطلبات', 'sweet-house-theme' ),
		'Addresses'                   => __( 'العناوين', 'sweet-house-theme' ),
		'Order details'               => __( 'تفاصيل الطلب', 'sweet-house-theme' ),
		'Product'                     => __( 'المنتج', 'sweet-house-theme' ),
		'Total'                       => __( 'الإجمالي', 'sweet-house-theme' ),
		'Order updates'               => __( 'تحديثات الطلب', 'sweet-house-theme' ),
		'Note:'                       => __( 'ملاحظة:', 'sweet-house-theme' ),
		'Phone (optional)'            => __( 'الهاتف (اختياري)', 'sweet-house-theme' ),
		'Phone'                       => __( 'الهاتف', 'sweet-house-theme' ),
		'Email address'              => __( 'البريد الإلكتروني', 'sweet-house-theme' ),
		'No order has been made yet.' => __( 'لم يتم تقديم أي طلب بعد.', 'sweet-house-theme' ),
		'Browse products'             => __( 'تصفح المنتجات', 'sweet-house-theme' ),
		'The following addresses will be used on the checkout page by default.' => __( 'سيتم استخدام العناوين التالية في صفحة الدفع افتراضياً.', 'sweet-house-theme' ),
		'You have not set up this type of address yet.' => __( 'لم تقم بإضافة هذا النوع من العنوان بعد.', 'sweet-house-theme' ),
		'Are you sure you want to log out?' => __( 'هل أنت متأكد أنك تريد تسجيل الخروج؟', 'sweet-house-theme' ),
		'Confirm and log out'         => __( 'تأكيد وتسجيل الخروج', 'sweet-house-theme' ),
		/* المصادقة — نسيت كلمة المرور */
		'Enter a username or email address.' => __( 'أدخل اسم المستخدم أو البريد الإلكتروني.', 'sweet-house-theme' ),
		'Invalid username or email.' => __( 'اسم المستخدم أو البريد الإلكتروني غير صحيح.', 'sweet-house-theme' ),
		'Password reset is not allowed for this user' => __( 'إعادة تعيين كلمة المرور غير مسموحة لهذا المستخدم.', 'sweet-house-theme' ),
		'This key is invalid or has already been used. Please reset your password again if needed.' => __( 'رابط إعادة التعيين غير صالح أو تم استخدامه مسبقاً. يرجى طلب رابط جديد إذا لزم الأمر.', 'sweet-house-theme' ),
		'This password reset key is for a different user account. Please log out and try again.' => __( 'رابط إعادة التعيين خاص بمستخدم آخر. يرجى تسجيل الخروج والمحاولة مرة أخرى.', 'sweet-house-theme' ),
		/* المصادقة — إعادة تعيين كلمة المرور */
		'Please enter your password.' => __( 'يرجى إدخال كلمة المرور.', 'sweet-house-theme' ),
		'Passwords do not match.'     => __( 'كلمات المرور غير متطابقة.', 'sweet-house-theme' ),
		'Your password has been reset successfully.' => __( 'تم إعادة تعيين كلمة المرور بنجاح.', 'sweet-house-theme' ),
		/* المصادقة — إنشاء حساب */
		'Your account was created successfully and a password has been sent to your email address.' => __( 'تم إنشاء حسابك بنجاح، وتم إرسال كلمة المرور إلى بريدك الإلكتروني.', 'sweet-house-theme' ),
		'Your account was created successfully. Your login details have been sent to your email address.' => __( 'تم إنشاء حسابك بنجاح. تم إرسال بيانات تسجيل الدخول إلى بريدك الإلكتروني.', 'sweet-house-theme' ),
		'Error:'                      => __( 'خطأ:', 'sweet-house-theme' ),
		/* تعديل الحساب */
		'Account details changed successfully.' => __( 'تم تحديث بيانات الحساب بنجاح.', 'sweet-house-theme' ),
		'Display name cannot be changed to email address due to privacy concern.' => __( 'لا يمكن تغيير اسم العرض ليصبح البريد الإلكتروني لأسباب تتعلق بالخصوصية.', 'sweet-house-theme' ),
		'Please provide a valid email address.' => __( 'يرجى إدخال بريد إلكتروني صحيح.', 'sweet-house-theme' ),
		'This email address is already registered.' => __( 'هذا البريد الإلكتروني مسجل مسبقاً.', 'sweet-house-theme' ),
		'Please fill out all password fields.' => __( 'يرجى تعبئة جميع حقول كلمة المرور.', 'sweet-house-theme' ),
		'Please enter your current password.' => __( 'يرجى إدخال كلمة المرور الحالية.', 'sweet-house-theme' ),
		'Please re-enter your password.' => __( 'يرجى إعادة إدخال كلمة المرور.', 'sweet-house-theme' ),
		'New passwords do not match.' => __( 'كلمات المرور الجديدة غير متطابقة.', 'sweet-house-theme' ),
		'Your current password is incorrect.' => __( 'كلمة المرور الحالية غير صحيحة.', 'sweet-house-theme' ),
		'An error occurred while saving account details: %s' => __( 'حدث خطأ أثناء حفظ بيانات الحساب: %s', 'sweet-house-theme' ),
		/* تعديل العنوان */
		'Address changed successfully.' => __( 'تم تحديث العنوان بنجاح.', 'sweet-house-theme' ),
		'%s is a required field.'     => __( '%s حقل مطلوب.', 'sweet-house-theme' ),
		'%s is not a valid phone number.' => __( '%s ليس رقماً جوّالاً صحيحاً.', 'sweet-house-theme' ),
		'%s is not a valid email address.' => __( '%s ليس بريداً إلكترونياً صحيحاً.', 'sweet-house-theme' ),
		/* طلب غير صالح */
		'Invalid order.'              => __( 'الطلب غير صالح.', 'sweet-house-theme' ),
		'My account'                  => __( 'حسابي', 'sweet-house-theme' ),
		/* كلمة مرور مؤقتة */
		'Your account with %s is using a temporary password. We emailed you a link to change your password.' => __( 'حسابك في %s يستخدم كلمة مرور مؤقتة. تم إرسال رابط لتغيير كلمة المرور إلى بريدك الإلكتروني.', 'sweet-house-theme' ),
		/* حقول مطلوبة — أسماء الحقول */
		'First name'                  => __( 'الاسم الأول', 'sweet-house-theme' ),
		'Last name'                   => __( 'اسم العائلة', 'sweet-house-theme' ),
		'Display name'                => __( 'اسم العرض', 'sweet-house-theme' ),
	);
	return isset( $strings[ $text ] ) ? $strings[ $text ] : $translated;
}
add_filter( 'gettext', 'sweet_house_woocommerce_arabic_translations', 10, 3 );

/**
 * رسائل خطأ تسجيل الدخول (WordPress) — العربية.
 */
function sweet_house_login_errors_arabic( $translated, $text, $domain ) {
	if ( '' !== $domain ) {
		return $translated;
	}
	$strings = array(
		'<strong>Error:</strong> The password field is empty.' => __( '<strong>خطأ:</strong> حقل كلمة المرور فارغ.', 'sweet-house-theme' ),
		'<strong>Error:</strong> Unknown username. Check again or try your email address.' => __( '<strong>خطأ:</strong> اسم المستخدم غير صحيح. تحقق مرة أخرى أو جرب البريد الإلكتروني.', 'sweet-house-theme' ),
		'<strong>Error:</strong> The username field is empty.' => __( '<strong>خطأ:</strong> حقل اسم المستخدم فارغ.', 'sweet-house-theme' ),
		'<strong>Error:</strong> Incorrect username or password. Lost your password?' => __( '<strong>خطأ:</strong> اسم المستخدم أو كلمة المرور غير صحيحة. نسيت كلمة المرور؟', 'sweet-house-theme' ),
		'<strong>Error:</strong> Invalid username, email address or incorrect password.' => __( '<strong>خطأ:</strong> اسم المستخدم أو البريد الإلكتروني غير صحيح، أو كلمة المرور خاطئة.', 'sweet-house-theme' ),
	);
	return isset( $strings[ $text ] ) ? $strings[ $text ] : $translated;
}
add_filter( 'gettext', 'sweet_house_login_errors_arabic', 20, 3 );

/**
 * في صفحة تعديل العنوان (حسابي): عنوان الصفحة يكون «العنوان» بدل «عنوان الفاتورة».
 */
function sweet_house_my_account_edit_address_title( $page_title, $load_address ) {
	if ( 'billing' === $load_address ) {
		return __( 'العنوان', 'sweet-house-theme' );
	}
	return $page_title;
}
add_filter( 'woocommerce_my_account_edit_address_title', 'sweet_house_my_account_edit_address_title', 10, 2 );

/**
 * WooCommerce: Arabic labels for address form fields (my-account & checkout).
 */
function sweet_house_address_field_labels( $fields ) {
	$labels = array(
		'first_name' => __( 'الاسم الأول', 'sweet-house-theme' ),
		'last_name'  => __( 'اسم العائلة', 'sweet-house-theme' ),
		'company'    => __( 'اسم الشركة', 'sweet-house-theme' ),
		'address_1'  => __( 'العنوان', 'sweet-house-theme' ),
		'address_2'  => __( 'عنوان إضافي', 'sweet-house-theme' ),
		'city'       => __( 'المدينة', 'sweet-house-theme' ),
		'state'      => __( 'المنطقة', 'sweet-house-theme' ),
		'postcode'   => __( 'الرمز البريدي', 'sweet-house-theme' ),
		'country'    => __( 'الدولة', 'sweet-house-theme' ),
		'phone'      => __( 'رقم الجوال', 'sweet-house-theme' ),
	);
	foreach ( $labels as $key => $label ) {
		if ( isset( $fields[ $key ] ) ) {
			$fields[ $key ]['label'] = $label;
		}
	}
	return $fields;
}
add_filter( 'woocommerce_default_address_fields', 'sweet_house_address_field_labels', 20 );

/**
 * WooCommerce: Arabic labels for My Account orders table columns.
 */
function sweet_house_orders_columns_arabic( $columns ) {
	return array(
		'order-number'  => __( 'الطلب', 'sweet-house-theme' ),
		'order-date'    => __( 'التاريخ', 'sweet-house-theme' ),
		'order-status'  => __( 'الحالة', 'sweet-house-theme' ),
		'order-total'   => __( 'الإجمالي', 'sweet-house-theme' ),
		'order-actions' => __( 'إجراءات', 'sweet-house-theme' ),
	);
}
add_filter( 'woocommerce_account_orders_columns', 'sweet_house_orders_columns_arabic' );

/**
 * WooCommerce: Arabic for order actions (View, Pay, etc).
 */
function sweet_house_order_actions_arabic( $actions, $order ) {
	$translate = array(
		'view'   => __( 'عرض', 'sweet-house-theme' ),
		'pay'    => __( 'دفع', 'sweet-house-theme' ),
		'cancel' => __( 'إلغاء', 'sweet-house-theme' ),
	);
	foreach ( $translate as $key => $label ) {
		if ( isset( $actions[ $key ] ) ) {
			$actions[ $key ]['name'] = $label;
		}
	}
	return $actions;
}
add_filter( 'woocommerce_my_account_my_orders_actions', 'sweet_house_order_actions_arabic', 10, 2 );

/**
 * الوصفات: Custom Post Type + taxonomy.
 */
$sweet_house_recipe_cpt = get_template_directory() . '/inc/recipe-cpt.php';
if ( file_exists( $sweet_house_recipe_cpt ) ) {
	require_once $sweet_house_recipe_cpt;
}

/**
 * Admin: المحتوى (Content) — الصفحات + منتجات ديمو.
 */
$sweet_house_admin_content = get_template_directory() . '/inc/admin-content.php';
if ( file_exists( $sweet_house_admin_content ) ) {
	require_once $sweet_house_admin_content;
}

$sweet_house_admin_content_editor = get_template_directory() . '/inc/admin-content-editor.php';
if ( file_exists( $sweet_house_admin_content_editor ) ) {
	require_once $sweet_house_admin_content_editor;
}

$sweet_house_admin_contact = get_template_directory() . '/inc/admin-contact-us.php';
if ( file_exists( $sweet_house_admin_contact ) ) {
	require_once $sweet_house_admin_contact;
}

$sweet_house_admin_footer = get_template_directory() . '/inc/admin-footer.php';
if ( file_exists( $sweet_house_admin_footer ) ) {
	require_once $sweet_house_admin_footer;
}

/**
 * Admin: إعدادات الموقع (ألوان الهيدر، الفوتر، الأزرار، الهوفر).
 */
$sweet_house_site_settings = get_template_directory() . '/inc/site-settings.php';
if ( file_exists( $sweet_house_site_settings ) ) {
	require_once $sweet_house_site_settings;
}
