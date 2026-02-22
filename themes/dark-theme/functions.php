<?php

define( 'DARK_THEME_VERSION', '1.0.0' );

function dark_theme_asset_base_uri() {
	return get_template_directory_uri() . '/dark';
}

function dark_theme_asset_base_path() {
	return get_template_directory() . '/dark';
}

function dark_theme_asset_uri( $relative_path ) {
	return dark_theme_asset_base_uri() . '/' . ltrim( $relative_path, '/' );
}

function dark_theme_asset_path( $relative_path ) {
	return dark_theme_asset_base_path() . '/' . ltrim( $relative_path, '/' );
}

function dark_theme_enqueue_style( $handle, $relative_path, $deps = array(), $version = null ) {
	$absolute_path = dark_theme_asset_path( $relative_path );
	if ( file_exists( $absolute_path ) ) {
		$ver = $version ? $version : (string) filemtime( $absolute_path );
		wp_enqueue_style( $handle, dark_theme_asset_uri( $relative_path ), $deps, $ver );
	}
}

function dark_theme_enqueue_script( $handle, $relative_path, $deps = array(), $in_footer = true ) {
	$absolute_path = dark_theme_asset_path( $relative_path );
	if ( file_exists( $absolute_path ) ) {
		$ver = (string) filemtime( $absolute_path );
		wp_enqueue_script( $handle, dark_theme_asset_uri( $relative_path ), $deps, $ver, $in_footer );
	}
}

function dark_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'dark_theme_setup' );

/**
 * صفحة المنتج المنفرد: إخفاء تبويبات التقييمات والمنتجات الإضافية، والإبقاء على "تسوق أكثر" فقط.
 */
function dark_theme_single_product_remove_tabs_upsell() {
	if ( ! function_exists( 'is_product' ) || ! is_product() ) {
		return;
	}
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
}
add_action( 'wp', 'dark_theme_single_product_remove_tabs_upsell' );

/**
 * عرض كل المنتجات في صفحة المتجر (حتى 100 منتج في الصفحة الواحدة).
 */
function dark_theme_shop_per_page() {
	return 100;
}
add_filter( 'loop_shop_per_page', 'dark_theme_shop_per_page', 20 );

/**
 * في صفحة التصنيف: عرض المنتجات الموجودة في التصنيف فقط.
 * يعمل مع روابط التصنيف (?category=slug) وصفحة أرشيف التصنيف (product-category/slug).
 */
function dark_theme_shop_filter_by_category( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( ! function_exists( 'is_shop' ) || ! function_exists( 'is_product_taxonomy' ) ) {
		return;
	}
	// صفحة أرشيف التصنيف: WooCommerce يضبط الاستعلام تلقائياً، لا نغيّر شيء
	if ( is_product_taxonomy() ) {
		return;
	}
	// صفحة المتجر مع معامل التصنيف: تصفية حسب product_cat
	if ( is_shop() ) {
		$cat_slug = get_query_var( 'product_cat' );
		if ( empty( $cat_slug ) && ! empty( $_GET['category'] ) ) {
			$cat_slug = sanitize_text_field( wp_unslash( $_GET['category'] ) );
		}
		if ( ! empty( $cat_slug ) ) {
			$tax_query = (array) $query->get( 'tax_query' );
			$tax_query[] = array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => $cat_slug,
			);
			$query->set( 'tax_query', $tax_query );
		}
	}
}
add_action( 'pre_get_posts', 'dark_theme_shop_filter_by_category', 20 );

/**
 * عند البحث من الهيدر (إرسال النموذج إلى المتجر مع post_type=product) تطبيق كلمة البحث على استعلام المنتجات.
 */
function dark_theme_product_search_query( $q ) {
	if ( ! empty( $_GET['s'] ) ) {
		$q->set( 's', sanitize_text_field( wp_unslash( $_GET['s'] ) ) );
	}
}
add_action( 'woocommerce_product_query', 'dark_theme_product_search_query', 10 );

function dark_theme_get_page_url( $slug, $fallback = '' ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		return get_permalink( $page->ID );
	}

	if ( $fallback ) {
		return $fallback;
	}

	return home_url( '/' . $slug );
}

function dark_theme_current_page_key() {
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
	if ( is_page( 'login' ) ) {
		return 'login';
	}
	if ( is_page( 'signup' ) ) {
		return 'signup';
	}
	if ( is_page( 'reset-password' ) ) {
		return 'reset-password';
	}
	if ( is_page( 'my-account' ) || ( function_exists( 'is_account_page' ) && is_account_page() ) ) {
		return 'my-account';
	}
	if ( is_page( 'contact' ) ) {
		return 'contact';
	}
	if ( is_page( 'about' ) ) {
		return 'about';
	}
	if ( is_page( array( 'policy', 'privacy-policy', 'refund-policy', 'shipping-policy' ) ) ) {
		return 'policy';
	}
	if ( is_404() ) {
		return 'not-found';
	}

	return '';
}

function dark_theme_enqueue_assets() {
	$base_uri = dark_theme_asset_base_uri();

	wp_enqueue_style(
		'dark-theme-fonts',
		'https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Gulzar&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'dark-theme-font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
		array(),
		'6.5.0'
	);

	dark_theme_enqueue_style( 'dark-theme-reset', 'base/reset.css' );
	dark_theme_enqueue_style( 'dark-theme-tokens', 'base/tokens.css', array( 'dark-theme-reset' ) );
	dark_theme_enqueue_style( 'dark-theme-utilities', 'base/utilities.css', array( 'dark-theme-tokens' ) );
	dark_theme_enqueue_style( 'dark-theme-typography', 'base/typography.css', array( 'dark-theme-utilities' ) );

	dark_theme_enqueue_style( 'dark-theme-navbar', 'components/layout/y-c-navbar.css', array( 'dark-theme-typography' ) );
	dark_theme_enqueue_style( 'dark-theme-footer', 'components/layout/y-c-footer.css', array( 'dark-theme-typography' ) );
	dark_theme_enqueue_style( 'dark-theme-breadcrumb', 'components/layout/y-c-breadcrumb.css', array( 'dark-theme-typography' ) );
	dark_theme_enqueue_style( 'dark-theme-buttons', 'components/buttons/y-c-btn.css', array( 'dark-theme-typography' ) );
	dark_theme_enqueue_style( 'dark-theme-auth-buttons', 'components/buttons/y-c-auth-btn.css', array( 'dark-theme-typography' ) );
	dark_theme_enqueue_style( 'dark-theme-qty-buttons', 'components/buttons/y-c-qnt-btn.css', array( 'dark-theme-typography' ) );
	dark_theme_enqueue_style( 'dark-theme-product-card', 'components/cards/y-c-product-card.css', array( 'dark-theme-typography' ) );
	dark_theme_enqueue_style( 'dark-theme-animations', 'css/y-animations.css', array( 'dark-theme-typography' ) );

	$site_colors = get_option( 'dark_theme_site_colors', array() );
	if ( ! empty( $site_colors ) ) {
		$c   = dark_theme_get_site_colors();
		$css = sprintf(
			'[data-y="nav"] .navbar { background-color: %1$s !important; } .footer { background: %2$s !important; } .product-card { background: %3$s !important; border-color: %3$s; } .btn-primary { background: %4$s !important; } .actions .btn-primary { background-color: %4$s !important; }',
			esc_attr( $c['header_background'] ),
			esc_attr( $c['footer_background'] ),
			esc_attr( $c['product_card_bg'] ),
			esc_attr( $c['button_primary'] )
		);
		wp_add_inline_style( 'dark-theme-product-card', $css );
	}

	if ( is_front_page() ) {
		dark_theme_enqueue_style( 'dark-theme-home-header', 'components/home/y-c-header.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-home-section', 'components/home/y-c-section.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-home-category', 'components/home/y-c-category.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-home-reviews', 'components/home/y-c-reviews.css', array( 'dark-theme-typography' ) );
	}

	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		dark_theme_enqueue_style( 'dark-theme-home-section', 'components/home/y-c-section.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-products', 'components/products/y-c-products.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-pagination', 'components/products/y-c-pagination.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-filter', 'components/products/y-c-filter-bar.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-products-page', 'templates/products/products.css', array( 'dark-theme-typography' ) );
	}

	if ( is_page( 'offers' ) ) {
		dark_theme_enqueue_style( 'dark-theme-products', 'components/products/y-c-products.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-pagination', 'components/products/y-c-pagination.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-filter', 'components/products/y-c-filter-bar.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-home-section', 'components/home/y-c-section.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-offers', 'components/offers/y-c-offers.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-offers-page', 'templates/offers/offers.css', array( 'dark-theme-typography' ) );
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		dark_theme_enqueue_style( 'dark-theme-single', 'components/single product/y-c-single-product.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-single-page', 'templates/single product/single-product.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-home-section', 'components/home/y-c-section.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-products', 'components/products/y-c-products.css', array( 'dark-theme-typography' ) );
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		dark_theme_enqueue_style( 'dark-theme-text-fields', 'components/text fields/y-c-text-fields.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-cart-summary', 'components/cards/y-c-cart-summary-card.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-cart-table', 'components/cart/y-c-cart-table.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-cart-page', 'templates/cart/cart.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-empty', 'components/products/y-c-empty.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-wc-cart-checkout', 'woocommerce/woocommerce-cart-checkout-override.css', array( 'dark-theme-cart-page' ) );
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		dark_theme_enqueue_style( 'dark-theme-text-fields', 'components/text fields/y-c-text-fields.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-cart-summary', 'components/cards/y-c-cart-summary-card.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-payment-form', 'components/payment/y-c-payment-form.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-payment', 'templates/payment/payment.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-wc-cart-checkout', 'woocommerce/woocommerce-cart-checkout-override.css', array( 'dark-theme-payment' ) );
	}

	if ( is_page( 'thank-you' ) ) {
		dark_theme_enqueue_style( 'dark-theme-empty', 'components/products/y-c-empty.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-thank-you', 'templates/thank-you/thank-you.css', array( 'dark-theme-empty' ) );
	}

	if ( is_page( 'wishlist' ) ) {
		dark_theme_enqueue_style( 'dark-theme-products', 'components/products/y-c-products.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-pagination', 'components/products/y-c-pagination.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-filter', 'components/products/y-c-filter-bar.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-home-section', 'components/home/y-c-section.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-wishlist', 'templates/wishlist/wishlist.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-empty', 'components/products/y-c-empty.css', array( 'dark-theme-typography' ) );
	}

	if ( is_page( array( 'login', 'signup', 'forget-password', 'reset-password' ) ) ) {
		dark_theme_enqueue_style( 'dark-theme-auth', 'components/auth/y-c-auth.css', array( 'dark-theme-typography' ) );
	}

	if ( is_page( 'my-account' ) || ( function_exists( 'is_account_page' ) && is_account_page() ) ) {
		dark_theme_enqueue_style( 'dark-theme-account', 'templates/my-account/my-account.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-account-details', 'components/account/y-c-account-details.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-account-sidebar', 'components/account/y-c-account-sidebar.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-account-orders', 'components/account/y-c-orders.css', array( 'dark-theme-typography' ) );
		dark_theme_enqueue_style( 'dark-theme-account-address', 'components/account/y-c-address.css', array( 'dark-theme-typography' ) );
	}

	wp_register_script( 'dark-theme-config', '', array(), DARK_THEME_VERSION, true );
	wp_enqueue_script( 'dark-theme-config' );

	$urls = array(
		'home'      => home_url( '/' ),
		'shop'      => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : dark_theme_get_page_url( 'shop' ),
		'offers'    => dark_theme_get_page_url( 'offers' ),
		'cart'      => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : dark_theme_get_page_url( 'cart' ),
		'checkout'  => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'checkout' ) : dark_theme_get_page_url( 'payment' ),
		'wishlist'  => dark_theme_get_page_url( 'wishlist' ),
		'login'     => dark_theme_get_page_url( 'login' ),
		'signup'    => dark_theme_get_page_url( 'signup' ),
		'myAccount' => dark_theme_get_page_url( 'my-account' ),
	);

	$dark_theme_config = array(
		'baseUrl' => $base_uri,
		'urls'    => $urls,
		'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'dark_theme_nonce' ),
	);
	if ( is_front_page() ) {
		$dark_theme_config['homeContent'] = dark_theme_get_home_content();
	}
	wp_add_inline_script(
		'dark-theme-config',
		'window.DarkTheme = ' . wp_json_encode( $dark_theme_config ) . ';',
		'before'
	);

	dark_theme_enqueue_script( 'dark-theme-navbar', 'js/y-navbar.js', array( 'dark-theme-config' ) );
	dark_theme_enqueue_script( 'dark-theme-footer', 'js/y-footer.js', array( 'dark-theme-config' ) );

	if ( is_front_page() ) {
		dark_theme_enqueue_script( 'dark-theme-header', 'js/y-header.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-products-section', 'js/y-products-section.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-offers-section', 'js/y-offers-section.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-category', 'js/y-category.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-reviews', 'js/y-reviews.js', array( 'dark-theme-config' ) );
	}

	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		dark_theme_enqueue_script( 'dark-theme-breadcrumb', 'js/y-breadcrumb.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-pagination', 'js/y-pagination.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-top-products-logo', 'js/y-top-products-logo.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-filter', 'js/y-filter-bar.js', array( 'dark-theme-config' ) );
	}

	if ( is_page( 'offers' ) ) {
		dark_theme_enqueue_script( 'dark-theme-breadcrumb', 'js/y-breadcrumb.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-offers', 'js/y-offers.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-pagination', 'js/y-pagination.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-top-products-logo', 'js/y-top-products-logo.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-filter', 'js/y-filter-bar.js', array( 'dark-theme-config' ) );
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		dark_theme_enqueue_script( 'dark-theme-single-product', 'js/y-single-product.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-products', 'js/y-products.js', array( 'dark-theme-config' ) );
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		dark_theme_enqueue_script( 'dark-theme-breadcrumb', 'js/y-breadcrumb.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-cart-summary', 'js/y-cart-summary-card.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-cart-table', 'js/y-cart-table.js', array( 'dark-theme-config' ) );
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		dark_theme_enqueue_script( 'dark-theme-breadcrumb', 'js/y-breadcrumb.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-payment-summary', 'js/y-payment-summary-card.js', array( 'dark-theme-config' ) );
	}

	if ( is_page( 'thank-you' ) ) {
		dark_theme_enqueue_script( 'dark-theme-design-header', 'js/y-design-header.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-breadcrumb', 'js/y-breadcrumb.js', array( 'dark-theme-config' ) );
	}

	if ( is_page( 'payment' ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
		wp_enqueue_script( 'wc-country-select' );
		wp_enqueue_script( 'wc-address-i18n' );
		wp_enqueue_script( 'wc-checkout' );
	}

	if ( is_page( 'wishlist' ) ) {
		dark_theme_enqueue_script( 'dark-theme-breadcrumb', 'js/y-breadcrumb.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-wishlist', 'js/y-wishlist.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-top-products-logo', 'js/y-top-products-logo.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-pagination', 'js/y-pagination.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-filter', 'js/y-filter-bar.js', array( 'dark-theme-config' ) );
	}

	if ( is_page( array( 'login', 'signup' ) ) ) {
		dark_theme_enqueue_script( 'dark-theme-design-header', 'js/y-design-header.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-breadcrumb', 'js/y-breadcrumb.js', array( 'dark-theme-config' ) );
	}

	if ( is_page( 'reset-password' ) ) {
		dark_theme_enqueue_script( 'dark-theme-reset-password', 'js/y-reset-password.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-design-header', 'js/y-design-header.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-breadcrumb', 'js/y-breadcrumb.js', array( 'dark-theme-config' ) );
	}

	if ( is_page( 'my-account' ) || ( function_exists( 'is_account_page' ) && is_account_page() ) ) {
		dark_theme_enqueue_script( 'dark-theme-order-details', 'js/y-order-details.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-my-account', 'js/y-my-account.js', array( 'dark-theme-config' ) );
		dark_theme_enqueue_script( 'dark-theme-breadcrumb', 'js/y-breadcrumb.js', array( 'dark-theme-config' ) );
	}

	if ( is_page() && ! is_front_page() ) {
		dark_theme_enqueue_script( 'dark-theme-breadcrumb', 'js/y-breadcrumb.js', array( 'dark-theme-config' ) );
	}

	dark_theme_enqueue_script( 'dark-theme-wishlist-toggle', 'js/dark-theme-wishlist.js', array( 'dark-theme-config' ) );
}
add_action( 'wp_enqueue_scripts', 'dark_theme_enqueue_assets' );

function dark_theme_enqueue_wc_checkout_scripts() {
	if ( is_page( 'payment' ) ) {
		wp_enqueue_script( 'wc-cart-fragments' );
		wp_enqueue_script( 'wc-country-select' );
		wp_enqueue_script( 'wc-address-i18n' );
		wp_enqueue_script( 'wc-checkout' );
	}
}
add_action( 'wp_enqueue_scripts', 'dark_theme_enqueue_wc_checkout_scripts', 20 );

function dark_theme_get_wishlist_ids() {
	$ids = array();

	if ( is_user_logged_in() ) {
		$stored = get_user_meta( get_current_user_id(), 'dark_theme_wishlist', true );
		if ( is_array( $stored ) ) {
			$ids = $stored;
		}
	} elseif ( function_exists( 'WC' ) && WC()->session ) {
		$stored = WC()->session->get( 'dark_theme_wishlist', array() );
		if ( is_array( $stored ) ) {
			$ids = $stored;
		}
	}

	$ids = array_map( 'absint', $ids );
	$ids = array_filter( $ids );

	return array_values( array_unique( $ids ) );
}

function dark_theme_set_wishlist_ids( $ids ) {
	$ids = array_values( array_unique( array_filter( array_map( 'absint', (array) $ids ) ) ) );

	if ( is_user_logged_in() ) {
		update_user_meta( get_current_user_id(), 'dark_theme_wishlist', $ids );
	} elseif ( function_exists( 'WC' ) && WC()->session ) {
		WC()->session->set( 'dark_theme_wishlist', $ids );
	}

	return $ids;
}

function dark_theme_is_in_wishlist( $product_id ) {
	$ids = dark_theme_get_wishlist_ids();
	return in_array( absint( $product_id ), $ids, true );
}

function dark_theme_toggle_wishlist() {
	check_ajax_referer( 'dark_theme_nonce', 'nonce' );

	$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => 'المنتج غير صالح.' ) );
	}

	$ids = dark_theme_get_wishlist_ids();
	if ( in_array( $product_id, $ids, true ) ) {
		$ids = array_values( array_diff( $ids, array( $product_id ) ) );
		$in_wishlist = false;
	} else {
		$ids[] = $product_id;
		$in_wishlist = true;
	}

	dark_theme_set_wishlist_ids( $ids );

	wp_send_json_success(
		array(
			'inWishlist' => $in_wishlist,
			'count'      => count( $ids ),
		)
	);
}
add_action( 'wp_ajax_dark_theme_toggle_wishlist', 'dark_theme_toggle_wishlist' );
add_action( 'wp_ajax_nopriv_dark_theme_toggle_wishlist', 'dark_theme_toggle_wishlist' );

function dark_theme_product_image_url( $product, $size = 'medium' ) {
	if ( $product && $product->get_image_id() ) {
		$sizes_to_try = is_array( $size ) ? $size : array( $size, 'large', 'woocommerce_single', 'medium', 'full' );
		foreach ( $sizes_to_try as $s ) {
			$url = wp_get_attachment_image_url( $product->get_image_id(), $s );
			if ( $url ) {
				return $url;
			}
		}
	}

	$fallback = dark_theme_asset_path( 'assets/product.png' );
	if ( file_exists( $fallback ) ) {
		return dark_theme_asset_uri( 'assets/product.png' );
	}

	return wc_placeholder_img_src();
}

/**
 * إرجاع نص علم الخصم للمنتج: نسبة مئوية (مثل 60%) أو مبلغ الخصم. فارغ إذا المنتج ليس في عرض.
 */
function dark_theme_product_sale_badge_text( $product ) {
	if ( ! $product || ! $product->is_on_sale() ) {
		return '';
	}
	$regular = (float) $product->get_regular_price();
	$sale    = (float) $product->get_sale_price();
	if ( $regular <= 0 || $sale >= $regular ) {
		return '';
	}
	$percent = round( ( ( $regular - $sale ) / $regular ) * 100 );
	if ( $percent >= 1 && $percent <= 99 ) {
		return (int) $percent . '%';
	}
	$amount = $regular - $sale;
	$symbol = function_exists( 'get_woocommerce_currency_symbol' ) ? get_woocommerce_currency_symbol() : '';
	return 'خصم ' . number_format_i18n( $amount ) . ( $symbol ? ' ' . $symbol : '' );
}

function dark_theme_render_product_card( $product ) {
	if ( ! $product ) {
		return;
	}

	$product_id  = $product->get_id();
	$permalink   = $product->get_permalink();
	$title       = $product->get_name();
	$price       = $product->get_price_html();
	$image_url   = dark_theme_product_image_url( $product );
	$in_wishlist = dark_theme_is_in_wishlist( $product_id );
	$sale_badge  = dark_theme_product_sale_badge_text( $product );
	$is_on_sale  = $product->is_on_sale();
	$regular     = $is_on_sale ? (float) $product->get_regular_price() : (float) $product->get_price();
	$sale        = $is_on_sale ? (float) $product->get_sale_price() : (float) $product->get_price();
	?>
	<li class="product-card">
		<label class="product-card-fav">
			<input type="checkbox" class="product-card-fav-input" data-product-id="<?php echo esc_attr( $product_id ); ?>" <?php checked( $in_wishlist ); ?> />
			<i class="fa-regular fa-heart product-card-fav-icon"></i>
		</label>
		<?php if ( $sale_badge ) : ?>
			<div class="offer-badge"><span><?php echo esc_html( $sale_badge ); ?></span></div>
		<?php endif; ?>
		<a href="<?php echo esc_url( $permalink ); ?>">
			<div class="product-card-img">
				<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
			</div>
		</a>
		<div class="product-card-info">
			<h3 class="product-card-title"><?php echo esc_html( $title ); ?></h3>
			<?php if ( $is_on_sale && $regular > 0 ) : ?>
				<div class="product-card-pricing">
					<span class="product-card-price-old y-u-d-flex"><?php echo wp_kses_post( wc_price( $regular ) ); ?></span>
					<span class="product-card-price-new y-u-d-flex"><?php echo wp_kses_post( wc_price( $sale ) ); ?></span>
				</div>
			<?php else : ?>
				<span class="product-card-price"><?php echo wp_kses_post( $price ); ?></span>
			<?php endif; ?>
		</div>
		<a
			href="<?php echo esc_url( $product->add_to_cart_url() ); ?>"
			data-quantity="1"
			class="btn-primary add_to_cart_button ajax_add_to_cart"
			data-product_id="<?php echo esc_attr( $product_id ); ?>"
			data-product_sku="<?php echo esc_attr( $product->get_sku() ); ?>"
			aria-label="<?php echo esc_attr( $product->add_to_cart_description() ); ?>"
			rel="nofollow"
		>
			أضف إلى السلة
		</a>
	</li>
	<?php
}

/**
 * عرض قسم "أحدث منتجاتنا" في الرئيسية: 6 منتجات حقيقية (wc_get_products أو WP_Query كبديل).
 */
function dark_theme_render_home_products_section() {
	$c = dark_theme_get_home_content();
	$products = array();
	if ( function_exists( 'wc_get_products' ) ) {
		$products = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => 6,
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		);
	}
	$products = is_array( $products ) ? $products : array();
	if ( empty( $products ) && function_exists( 'wc_get_product' ) ) {
		$q = new WP_Query(
			array(
				'post_type'      => 'product',
				'posts_per_page' => 6,
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC',
				'no_found_rows'  => true,
			)
		);
		if ( $q->have_posts() ) {
			while ( $q->have_posts() ) {
				$q->the_post();
				$products[] = wc_get_product( get_the_ID() );
			}
			wp_reset_postdata();
		}
	}
	$section1_img = dark_theme_asset_uri( 'assets/section1.png' );
	?>
	<h2 class="section-title"><img src="<?php echo esc_url( $c['products_section_image'] ); ?>" alt="" /></h2>
	<div class="section section1">
		<ul class="products">
			<?php
			foreach ( $products as $product ) {
				if ( $product ) {
					dark_theme_render_product_card( $product );
				}
			}
			?>
		</ul>
		<div class="img-container">
			<h1><?php echo esc_html( $c['products_heading'] ); ?></h1>
			<img src="<?php echo esc_url( $section1_img ); ?>" alt="" />
		</div>
	</div>
	<div class="banner">
		<img src="<?php echo esc_url( $c['products_banner_image'] ); ?>" alt="" />
	</div>
	<?php
}

/**
 * عرض الهيدر في الصفحة الرئيسية من المحتوى المحفوظ أو الافتراضي.
 */
function dark_theme_render_home_header() {
	$c = dark_theme_get_home_content();
	?>
	<header class="header">
		<div class="image-wrapper">
			<img src="<?php echo esc_url( $c['header_image'] ); ?>" alt="<?php echo esc_attr( $c['header_title'] ); ?>" />
		</div>
		<div class="content">
			<h1><?php echo esc_html( $c['header_title'] ); ?></h1>
			<p><?php echo esc_html( $c['header_text'] ); ?></p>
			<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : dark_theme_get_page_url( 'shop' ) ); ?>" class="btn-primary header"><?php echo esc_html( $c['header_btn_text'] ); ?></a>
		</div>
	</header>
	<?php
}

/**
 * عرض قسم "تسوق أقسامنا" في الرئيسية من المحتوى المحفوظ أو الافتراضي.
 */
function dark_theme_render_home_category() {
	$c = dark_theme_get_home_content();
	?>
	<h2 class="section-title"><img src="<?php echo esc_url( $c['category_section_image'] ); ?>" alt="" /></h2>
	<div class="categories">
		<h1><?php echo esc_html( $c['category_heading'] ); ?></h1>
		<?php
		for ( $i = 1; $i <= 5; $i++ ) {
			$img   = $c[ "cat{$i}_image" ];
			$title = $c[ "cat{$i}_title" ];
			?>
			<div class="category">
				<img src="<?php echo esc_url( $img ); ?>" alt="<?php echo esc_attr( $title ); ?>" />
				<h3 class="category-title"><?php echo esc_html( $title ); ?></h3>
			</div>
			<?php
		}
		?>
	</div>
	<?php
}

/**
 * إرجاع مصفوفة معرفات المنتجات المعروضة للبيع (on sale).
 * نفس المنطق المستخدم في صفحة العروض: wc_get_product_ids_on_sale + حذف الكاش عند الفراغ + استعلام بديل _sale_price.
 * يُستخدم في صفحة العروض وقسم أقوى العروض بالرئيسية لضمان نفس النتائج.
 *
 * @return int[]
 */
function dark_theme_get_product_ids_on_sale() {
	$sale_ids = array();
	if ( ! function_exists( 'wc_get_product_ids_on_sale' ) ) {
		return $sale_ids;
	}
	$sale_ids = wc_get_product_ids_on_sale();
	$sale_ids = array_filter( array_map( 'absint', (array) $sale_ids ) );

	if ( empty( $sale_ids ) ) {
		delete_transient( 'wc_products_onsale' );
		$sale_ids = wc_get_product_ids_on_sale();
		$sale_ids = array_filter( array_map( 'absint', (array) $sale_ids ) );
	}

	if ( empty( $sale_ids ) || count( $sale_ids ) < 2 ) {
		$fallback = new WP_Query(
			array(
				'post_type'      => 'product',
				'post_status'    => 'publish',
				'posts_per_page' => 200,
				'orderby'        => 'date',
				'order'          => 'DESC',
				'fields'         => 'ids',
				'meta_query'     => array(
					array(
						'key'     => '_sale_price',
						'value'   => 0,
						'compare' => '>',
						'type'    => 'NUMERIC',
					),
				),
			)
		);
		if ( $fallback->have_posts() ) {
			$sale_ids = array_unique( array_merge( $sale_ids, array_map( 'absint', $fallback->posts ) ) );
			wp_reset_postdata();
		}
	}

	return $sale_ids;
}

/**
 * عرض قسم "أقوى العروض" في الرئيسية: أول 6 منتجات ذات سعر تخفيض (onsale).
 * نفس منطق الحصول على العروض المستخدم في صفحة العروض؛ يعرض حتى 6 عناصر (كل متغير أو منتج بسيط = بطاقة).
 */
function dark_theme_render_home_offers_section() {
	$products = array();
	if ( ! function_exists( 'wc_get_product' ) ) {
		$c = dark_theme_get_home_content();
		?>
		<div data-y="offers-sec">
			<h2 class="section-title"><img src="<?php echo esc_url( $c['offers_section_image'] ); ?>" alt="" /></h2>
			<div class="section section2">
				<ul class="products"></ul>
				<div class="img-container">
					<h1><?php echo esc_html( $c['offers_heading'] ); ?></h1>
					<img src="<?php echo esc_url( $c['offers_image'] ); ?>" alt="" />
				</div>
			</div>
		</div>
		<?php
		return;
	}
	$sale_ids = dark_theme_get_product_ids_on_sale();

	// Take up to 6 sale items (each variation or simple = one card).
	$take = array_slice( $sale_ids, 0, 6 );
	foreach ( $take as $product_id ) {
		$product = wc_get_product( $product_id );
		if ( $product ) {
			$products[] = $product;
		}
	}

	// If we still have fewer than 6, try wc_get_products( on_sale ) to fill.
	if ( count( $products ) < 6 && function_exists( 'wc_get_products' ) ) {
		$existing_ids = array_map( function ( $p ) {
			return $p->get_id();
		}, $products );
		$existing_ids = array_flip( $existing_ids );
		$extra = wc_get_products(
			array(
				'status'  => 'publish',
				'limit'   => 6,
				'on_sale' => true,
				'orderby' => 'date',
				'order'   => 'DESC',
			)
		);
		$extra = is_array( $extra ) ? $extra : array();
		foreach ( $extra as $p ) {
			if ( ! $p || count( $products ) >= 6 ) {
				break;
			}
			$pid = $p->get_id();
			if ( ! isset( $existing_ids[ $pid ] ) ) {
				$products[] = $p;
				$existing_ids[ $pid ] = true;
			}
		}
	}
	$c = dark_theme_get_home_content();
	?>
	<div data-y="offers-sec">
		<h2 class="section-title"><img src="<?php echo esc_url( $c['offers_section_image'] ); ?>" alt="" /></h2>
		<div class="section section2">
			<ul class="products">
				<?php
				foreach ( $products as $product ) {
					if ( $product ) {
						dark_theme_render_product_card( $product );
					}
				}
				?>
			</ul>
			<div class="img-container">
				<h1><?php echo esc_html( $c['offers_heading'] ); ?></h1>
				<img src="<?php echo esc_url( $c['offers_image'] ); ?>" alt="" />
			</div>
		</div>
	</div>
	<?php
}

/**
 * عرض قسم التعليقات في الصفحة الرئيسية (نفس هيكل y-c-reviews).
 */
function dark_theme_render_home_reviews() {
	$reviews = dark_theme_get_home_reviews();
	if ( empty( $reviews ) ) {
		return;
	}
	$style_img = dark_theme_asset_uri( 'assets/style.png' );
	?>
	<h2 class="section-title"><img src="<?php echo esc_url( $style_img ); ?>" alt=""></h2>
	<div class="reviews-container">
		<button class="scroll-arrow left" onclick="scrollReviews('left')">
			<i class="fas fa-chevron-left"></i>
		</button>
		<div class="reviews">
			<?php foreach ( $reviews as $r ) : ?>
				<?php
				$stars = str_repeat( '⭐', (int) ( $r['rating'] ?? 5 ) );
				?>
				<div class="review-card">
					<div class="user">
						<img src="<?php echo esc_url( $r['image_url'] ?? dark_theme_asset_uri( 'assets/review.jpg' ) ); ?>" alt="<?php echo esc_attr( $r['name'] ?? '' ); ?>" />
						<div>
							<h3 class="user-name"><?php echo esc_html( $r['name'] ?? '' ); ?></h3>
							<p class="rate"><span><?php echo esc_html( $stars ); ?></span></p>
						</div>
					</div>
					<div class="content">
						<p><?php echo esc_html( $r['text'] ?? '' ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<button class="scroll-arrow right" onclick="scrollReviews('right')">
			<i class="fas fa-chevron-right"></i>
		</button>
	</div>
	<?php
}

function dark_theme_render_products_pagination() {
	if ( ! function_exists( 'wc_get_loop_prop' ) ) {
		return;
	}

	$total_pages = (int) wc_get_loop_prop( 'total_pages' );
	if ( $total_pages <= 1 ) {
		return;
	}

	$current = max( 1, (int) get_query_var( 'paged' ) );
	$links = paginate_links(
		array(
			'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'    => '',
			'current'   => $current,
			'total'     => $total_pages,
			'type'      => 'array',
			'prev_next' => false,
		)
	);

	if ( empty( $links ) ) {
		return;
	}

	$prev_url = $current > 1 ? get_pagenum_link( $current - 1 ) : '';
	$next_url = $current < $total_pages ? get_pagenum_link( $current + 1 ) : '';
	?>
	<section data-y="pagination">
		<nav class="pagination" aria-label="pagination">
			<?php if ( $prev_url ) : ?>
				<a class="pagination-prev pagination-link" href="<?php echo esc_url( $prev_url ); ?>" aria-label="الصفحة السابقة">&laquo;</a>
			<?php else : ?>
				<span class="pagination-prev pagination-link is-disabled" aria-hidden="true">&laquo;</span>
			<?php endif; ?>
			<ul class="pagination-list">
				<?php foreach ( $links as $link ) : ?>
					<?php if ( false !== strpos( $link, 'dots' ) ) : ?>
						<li class="pagination-item-dots">...</li>
						<?php continue; ?>
					<?php endif; ?>
					<?php
					$is_current = false !== strpos( $link, 'current' );
					$label = trim( wp_strip_all_tags( $link ) );
					$href  = '';
					if ( preg_match( '/href="([^"]+)"/', $link, $matches ) ) {
						$href = $matches[1];
					}
					?>
					<li>
						<?php if ( $is_current || ! $href ) : ?>
							<span class="pagination-link active" aria-current="page"><?php echo esc_html( $label ); ?></span>
						<?php else : ?>
							<a class="pagination-link" href="<?php echo esc_url( $href ); ?>"><?php echo esc_html( $label ); ?></a>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ul>
			<?php if ( $next_url ) : ?>
				<a class="pagination-next pagination-link" href="<?php echo esc_url( $next_url ); ?>" aria-label="الصفحة التالية">&raquo;</a>
			<?php else : ?>
				<span class="pagination-next pagination-link is-disabled" aria-hidden="true">&raquo;</span>
			<?php endif; ?>
		</nav>
	</section>
	<?php
}

function dark_theme_wishlist_shortcode() {
	$ids = dark_theme_get_wishlist_ids();
	if ( empty( $ids ) ) {
		ob_start();
		?>
		<div class="not-found-content">
			<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/empty-fav.png' ) ); ?>" alt="المفضلة فارغة" class="not-found-img" />
			<p class="not-found-text">
				قائمة المفضلة فارغة، لم تقم بإضافة أي منتجات إلى قائمة المفضلة الخاصة بك بعد.
			</p>
			<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : dark_theme_get_page_url( 'shop' ) ); ?>" class="btn-back">
				تصفح المنتجات <i class="fa-solid fa-store"></i>
			</a>
		</div>
		<?php
		return ob_get_clean();
	}

	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'post__in'       => $ids,
			'posts_per_page' => -1,
			'orderby'        => 'post__in',
		)
	);

	ob_start();
	if ( $query->have_posts() ) {
		echo '<ul class="products y-u-my-10">';
		while ( $query->have_posts() ) {
			$query->the_post();
			$product = wc_get_product( get_the_ID() );
			dark_theme_render_product_card( $product );
		}
		echo '</ul>';
	}
	wp_reset_postdata();

	return ob_get_clean();
}
add_shortcode( 'dark_wishlist', 'dark_theme_wishlist_shortcode' );

function dark_theme_customize_checkout_fields( $fields ) {
	if ( isset( $fields['billing'] ) ) {
		$fields['billing']['billing_full_name'] = array(
			'type'        => 'text',
			'label'       => 'الاسم الكامل',
			'required'    => true,
			'priority'    => 10,
			'class'       => array( 'form-row-wide' ),
			'placeholder' => 'الاسم الكامل',
		);

		unset( $fields['billing']['billing_first_name'], $fields['billing']['billing_last_name'] );
		unset( $fields['billing']['billing_company'], $fields['billing']['billing_address_2'] );
		unset( $fields['billing']['billing_city'], $fields['billing']['billing_state'] );
		unset( $fields['billing']['billing_postcode'], $fields['billing']['billing_country'] );

		if ( isset( $fields['billing']['billing_email'] ) ) {
			$fields['billing']['billing_email']['label'] = 'البريد الإلكتروني';
			$fields['billing']['billing_email']['priority'] = 20;
			$fields['billing']['billing_email']['required'] = true;
		}

		if ( isset( $fields['billing']['billing_phone'] ) ) {
			$fields['billing']['billing_phone']['label'] = 'رقم الجوال';
			$fields['billing']['billing_phone']['priority'] = 30;
			$fields['billing']['billing_phone']['required'] = true;
		}

		if ( isset( $fields['billing']['billing_address_1'] ) ) {
			$fields['billing']['billing_address_1']['label'] = 'العنوان';
			$fields['billing']['billing_address_1']['priority'] = 40;
			$fields['billing']['billing_address_1']['class'] = array( 'form-row-wide' );
			$fields['billing']['billing_address_1']['placeholder'] = 'العنوان';
		}
	}

	if ( isset( $fields['order']['order_comments'] ) ) {
		$fields['order']['order_comments']['label'] = 'ملاحظات الطلب';
	}

	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'dark_theme_customize_checkout_fields', 20 );

function dark_theme_checkout_no_shipping( $needs_shipping ) {
	return false;
}
add_filter( 'woocommerce_cart_needs_shipping', 'dark_theme_checkout_no_shipping' );
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );
add_filter( 'woocommerce_checkout_registration_enabled', '__return_true' );
add_filter( 'woocommerce_checkout_registration_required', '__return_true' );

function dark_theme_force_checkout_page( $is_checkout ) {
	if ( is_page( 'payment' ) ) {
		return true;
	}

	return $is_checkout;
}
add_filter( 'woocommerce_is_checkout', 'dark_theme_force_checkout_page' );

function dark_theme_add_to_cart_redirect_to_checkout( $redirect ) {
	if ( ! empty( $_POST['redirect_to_checkout'] ) && function_exists( 'wc_get_checkout_url' ) ) {
		return wc_get_checkout_url();
	}
	return $redirect;
}
add_filter( 'woocommerce_add_to_cart_redirect', 'dark_theme_add_to_cart_redirect_to_checkout', 10, 1 );

function dark_theme_single_add_to_cart_text( $text, $product ) {
	return 'أضف إلى السلة';
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'dark_theme_single_add_to_cart_text', 10, 2 );

function dark_theme_translate_gateway_title( $title, $gateway_id ) {
	$map = array(
		'bacs' => 'تحويل بنكي مباشر',
		'cod'  => 'الدفع عند الاستلام',
	);

	if ( isset( $map[ $gateway_id ] ) ) {
		return $map[ $gateway_id ];
	}

	return $title;
}
add_filter( 'woocommerce_gateway_title', 'dark_theme_translate_gateway_title', 10, 2 );

function dark_theme_translate_gateway_description( $description, $gateway_id ) {
	$map = array(
		'bacs' => 'قم بتحويل المبلغ مباشرة إلى حسابنا البنكي مع استخدام رقم الطلب كمرجع.',
		'cod'  => 'الدفع عند الاستلام.',
	);

	if ( isset( $map[ $gateway_id ] ) ) {
		return $map[ $gateway_id ];
	}

	return $description;
}
add_filter( 'woocommerce_gateway_description', 'dark_theme_translate_gateway_description', 10, 2 );

function dark_theme_translate_optional_label( $field_html, $key, $args, $value ) {
	if ( is_admin() || ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return $field_html;
	}

	return str_replace( '(optional)', 'اختياري', $field_html );
}
add_filter( 'woocommerce_form_field', 'dark_theme_translate_optional_label', 20, 4 );

function dark_theme_translate_checkout_texts( $translated, $text, $domain ) {
	if ( is_admin() || ! function_exists( 'is_checkout' ) || ! is_checkout() ) {
		return $translated;
	}

	$map = array(
		'Billing details'                 => 'بيانات الفاتورة',
		'Additional information'          => 'معلومات إضافية',
		'Your order'                      => 'طلبك',
		'Place order'                     => 'إتمام الطلب',
		'Have a coupon?'                  => 'هل لديك قسيمة؟',
		'Click here to enter your code'   => 'اضغط هنا لإدخال القسيمة',
		'Product'                         => 'المنتج',
		'Subtotal'                        => 'المجموع الفرعي',
		'Total'                           => 'الإجمالي',
		'(optional)'                      => 'اختياري',
		'Direct bank transfer'            => 'تحويل بنكي مباشر',
		'Cash on delivery'                => 'الدفع عند الاستلام',
		'Order notes'                      => 'ملاحظات الطلب',
		'Notes about your order, e.g. special notes for delivery.' => 'ملاحظات حول الطلب، مثل تعليمات التوصيل.',
		'Returning customer?'             => 'هل لديك حساب؟',
		'Click here to login'             => 'اضغط هنا لتسجيل الدخول',
		'Create account password'         => 'كلمة المرور',
		'Password'                         => 'كلمة المرور',
		'Show password'                   => 'إظهار كلمة المرور',
	);

	if ( isset( $map[ $text ] ) ) {
		return $map[ $text ];
	}

	return $translated;
}
add_filter( 'gettext', 'dark_theme_translate_checkout_texts', 20, 3 );

function dark_theme_translate_cart_texts( $translated, $text, $domain ) {
	if ( is_admin() || ! function_exists( 'is_cart' ) || ! is_cart() ) {
		return $translated;
	}

	$map = array(
		'Apply coupon'        => 'تطبيق القسيمة',
		'Update cart'        => 'تحديث السلة',
		'Proceed to checkout' => 'متابعة للدفع',
		'Cart totals'        => 'إجماليات السلة',
		'Subtotal'           => 'المجموع الفرعي',
		'Total'              => 'الإجمالي',
	);

	if ( isset( $map[ $text ] ) ) {
		return $map[ $text ];
	}

	return $translated;
}
add_filter( 'gettext', 'dark_theme_translate_cart_texts', 20, 3 );

function dark_theme_store_full_name_in_order( $order, $data ) {
	if ( isset( $_POST['billing_full_name'] ) ) {
		$full_name = sanitize_text_field( wp_unslash( $_POST['billing_full_name'] ) );
		if ( $full_name ) {
			$name_parts = preg_split( '/\s+/', trim( $full_name ) );
			$order->set_billing_first_name( $name_parts[0] ?? $full_name );
			if ( count( $name_parts ) > 1 ) {
				$order->set_billing_last_name( implode( ' ', array_slice( $name_parts, 1 ) ) );
			}
		}
	}
}
add_action( 'woocommerce_checkout_create_order', 'dark_theme_store_full_name_in_order', 20, 2 );

function dark_theme_split_full_name_posted_data( $data ) {
	if ( ! empty( $data['billing_full_name'] ) && empty( $data['billing_first_name'] ) ) {
		$name_parts = preg_split( '/\s+/', trim( $data['billing_full_name'] ), 2 );
		$data['billing_first_name'] = $name_parts[0] ?? $data['billing_full_name'];
		$data['billing_last_name'] = $name_parts[1] ?? $data['billing_first_name'];
	}

	return $data;
}
add_filter( 'woocommerce_checkout_posted_data', 'dark_theme_split_full_name_posted_data' );

add_filter( 'woocommerce_checkout_privacy_policy_text', function () {
	return 'سيتم استخدام بياناتك الشخصية لمعالجة طلبك ودعم تجربتك خلال هذا الموقع ولأغراض أخرى موضحة في سياسة الخصوصية.';
}, 99 );

function dark_theme_translate_pay_order_button_text( $text ) {
	$normalized = strtolower( trim( (string) $text ) );
	if ( $normalized === 'pay for order' || $normalized === 'pay for order »' ) {
		return 'إتمام الدفع';
	}
	return $text;
}
add_filter( 'woocommerce_pay_order_button_text', 'dark_theme_translate_pay_order_button_text' );

function dark_theme_allow_email_login( $user, $username, $password ) {
	if ( $user instanceof WP_User ) {
		return $user;
	}

	if ( is_email( $username ) ) {
		$user_obj = get_user_by( 'email', $username );
		if ( $user_obj ) {
			$username = $user_obj->user_login;
		}
	}

	return wp_authenticate_username_password( null, $username, $password );
}
add_filter( 'authenticate', 'dark_theme_allow_email_login', 20, 3 );

function dark_theme_handle_login() {
	check_admin_referer( 'dark_theme_login', 'dark_theme_login_nonce' );

	$login_raw = sanitize_text_field( wp_unslash( $_POST['email'] ?? '' ) );
	$password = wp_unslash( $_POST['password'] ?? '' );

	if ( empty( $login_raw ) || empty( $password ) ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'الرجاء إدخال البريد وكلمة المرور.' ), dark_theme_get_page_url( 'login' ) ) );
		exit;
	}

	$user = wp_signon(
		array(
			'user_login'    => $login_raw,
			'user_password' => $password,
			'remember'      => ! empty( $_POST['remember'] ),
		),
		is_ssl()
	);

	if ( is_wp_error( $user ) ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'بيانات الدخول غير صحيحة.' ), dark_theme_get_page_url( 'login' ) ) );
		exit;
	}

	$redirect = esc_url_raw( wp_unslash( $_POST['redirect_to'] ?? dark_theme_get_page_url( 'my-account' ) ) );
	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_nopriv_dark_theme_login', 'dark_theme_handle_login' );

function dark_theme_handle_signup() {
	check_admin_referer( 'dark_theme_signup', 'dark_theme_signup_nonce' );

	$full_name = sanitize_text_field( wp_unslash( $_POST['full_name'] ?? '' ) );
	$email     = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone_raw = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$phone     = preg_replace( '/[^0-9]/', '', $phone_raw );
	if ( strlen( $phone ) === 9 && strpos( $phone, '5' ) === 0 ) {
		$phone = '0' . $phone;
	}
	$password  = wp_unslash( $_POST['password'] ?? '' );
	$confirm   = wp_unslash( $_POST['confirm_password'] ?? '' );

	if ( ! $full_name || ! $email || ! $password ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'يرجى تعبئة جميع الحقول المطلوبة.' ), dark_theme_get_page_url( 'signup' ) ) );
		exit;
	}
	if ( strlen( $phone ) !== 10 || strpos( $phone, '05' ) !== 0 ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'رقم الجوال غير صحيح. استخدم 10 أرقام تبدأ بـ 05 (مثال: 0512345678).' ), dark_theme_get_page_url( 'signup' ) ) );
		exit;
	}
	if ( $password !== $confirm ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'كلمتا المرور غير متطابقتين.' ), dark_theme_get_page_url( 'signup' ) ) );
		exit;
	}
	if ( email_exists( $email ) ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'هذا البريد مستخدم بالفعل.' ), dark_theme_get_page_url( 'signup' ) ) );
		exit;
	}

	$username = sanitize_user( current( explode( '@', $email ) ), true );
	if ( username_exists( $username ) ) {
		$username .= wp_generate_password( 4, false, false );
	}

	$user_id = wp_create_user( $username, $password, $email );
	if ( is_wp_error( $user_id ) ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'تعذر إنشاء الحساب.' ), dark_theme_get_page_url( 'signup' ) ) );
		exit;
	}

	wp_update_user(
		array(
			'ID'           => $user_id,
			'display_name' => $full_name,
			'first_name'   => $full_name,
		)
	);

	update_user_meta( $user_id, 'phone', $phone );
	wp_update_user( array( 'ID' => $user_id, 'role' => 'customer' ) );

	// تسجيل الدخول فوراً بعد إنشاء الحساب باستخدام user_login الفعلي
	$user = get_user_by( 'ID', $user_id );
	if ( $user ) {
		$signon = wp_signon(
			array(
				'user_login'    => $user->user_login,
				'user_password' => $password,
				'remember'      => true,
			),
			is_ssl()
		);
		if ( is_wp_error( $signon ) ) {
			// إن فشل التلقائي نوجّه لصفحة تسجيل الدخول مع رسالة
			wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'تم إنشاء الحساب. يرجى تسجيل الدخول.' ), dark_theme_get_page_url( 'login' ) ) );
			exit;
		}
	}

	$redirect = esc_url_raw( wp_unslash( $_POST['redirect_to'] ?? dark_theme_get_page_url( 'my-account' ) ) );
	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_nopriv_dark_theme_signup', 'dark_theme_handle_signup' );

function dark_theme_handle_forgot_password() {
	check_admin_referer( 'dark_theme_forgot_password', 'dark_theme_forgot_password_nonce' );

	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	if ( ! $email ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'يرجى إدخال البريد الإلكتروني.' ), dark_theme_get_page_url( 'forget-password' ) ) );
		exit;
	}

	$_POST['user_login'] = $email;
	$result = retrieve_password();
	if ( is_wp_error( $result ) ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'تعذر إرسال الرابط. تأكد من البريد.' ), dark_theme_get_page_url( 'forget-password' ) ) );
		exit;
	}

	wp_safe_redirect( add_query_arg( 'sent', '1', dark_theme_get_page_url( 'forget-password' ) ) );
	exit;
}
add_action( 'admin_post_nopriv_dark_theme_forgot_password', 'dark_theme_handle_forgot_password' );

function dark_theme_build_reset_password_url( $user_login, $key ) {
	return add_query_arg(
		array(
			'key'   => $key,
			'login' => $user_login,
		),
		dark_theme_get_page_url( 'reset-password' )
	);
}

function dark_theme_filter_retrieve_password_message( $message, $key, $user_login, $user_data ) {
	$reset_url = dark_theme_build_reset_password_url( $user_login, $key );
	$site_name = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );

	$lines   = array();
	$lines[] = sprintf( 'مرحباً %s،', $user_login );
	$lines[] = '';
	$lines[] = sprintf( 'تم طلب إعادة تعيين كلمة المرور الخاصة بك في %s.', $site_name );
	$lines[] = 'إذا لم تطلب ذلك، يمكنك تجاهل هذه الرسالة.';
	$lines[] = '';
	$lines[] = 'لإعادة تعيين كلمة المرور، افتح الرابط التالي:';
	$lines[] = $reset_url;
	$lines[] = '';

	return implode( "\r\n", $lines );
}
add_filter( 'retrieve_password_message', 'dark_theme_filter_retrieve_password_message', 10, 4 );

function dark_theme_handle_reset_password() {
	check_admin_referer( 'dark_theme_reset_password', 'dark_theme_reset_password_nonce' );

	$login    = sanitize_text_field( wp_unslash( $_POST['login'] ?? '' ) );
	$key      = sanitize_text_field( wp_unslash( $_POST['key'] ?? '' ) );
	$password = (string) wp_unslash( $_POST['password'] ?? '' );
	$confirm  = (string) wp_unslash( $_POST['confirm_password'] ?? '' );

	if ( ! $login || ! $key ) {
		wp_safe_redirect( add_query_arg( 'reset_error', rawurlencode( 'الرابط غير صالح. يرجى طلب رابط جديد.' ), dark_theme_get_page_url( 'reset-password' ) ) );
		exit;
	}

	if ( ! $password || ! $confirm ) {
		wp_safe_redirect( add_query_arg( array(
			'reset_error' => rawurlencode( 'يرجى إدخال كلمة المرور وتأكيدها.' ),
			'login'       => rawurlencode( $login ),
			'key'         => rawurlencode( $key ),
		), dark_theme_get_page_url( 'reset-password' ) ) );
		exit;
	}

	if ( $password !== $confirm ) {
		wp_safe_redirect( add_query_arg( array(
			'reset_error' => rawurlencode( 'كلمتا المرور غير متطابقتين.' ),
			'login'       => rawurlencode( $login ),
			'key'         => rawurlencode( $key ),
		), dark_theme_get_page_url( 'reset-password' ) ) );
		exit;
	}

	$user = check_password_reset_key( $key, $login );
	if ( is_wp_error( $user ) ) {
		wp_safe_redirect( add_query_arg( 'reset_error', rawurlencode( 'الرابط غير صالح أو منتهي. اطلب رابطاً جديداً.' ), dark_theme_get_page_url( 'reset-password' ) ) );
		exit;
	}

	reset_password( $user, $password );
	wp_safe_redirect( add_query_arg( 'reset_success', '1', dark_theme_get_page_url( 'reset-password' ) ) );
	exit;
}
add_action( 'admin_post_nopriv_dark_theme_reset_password', 'dark_theme_handle_reset_password' );

function dark_theme_pages_manifest() {
	return array(
		'home'            => 'الرئيسية',
		'shop'            => 'كل المنتجات',
		'offers'          => 'العروض',
		'wishlist'        => 'المفضلة',
		'cart'            => 'السلة',
		'payment'         => 'الدفع',
		'about'           => 'من نحن',
		'contact'         => 'تواصل معنا',
		'login'           => 'تسجيل الدخول',
		'signup'          => 'إنشاء حساب',
		'forget-password' => 'نسيت كلمة المرور',
		'reset-password'  => 'إعادة تعيين كلمة المرور',
		'my-account'      => 'حسابي',
		'privacy-policy'  => 'سياسة الخصوصية',
		'refund-policy'   => 'سياسة الاسترجاع',
		'shipping-policy' => 'سياسة الشحن',
		'thank-you'       => 'تأكيد الطلب',
	);
}

/**
 * المحتوى الأصلي الافتراضي للصفحة الرئيسية (للاستعادة).
 */
function dark_theme_default_home_content() {
	return array(
		'header_image'           => 0,
		'header_title'           => 'دارك',
		'header_text'            => 'نوفر لك تشكيلة واسعة من الأثاث الراقي الذي يناسب جميع الأذواق والمساحات، وبخبرة تمتد لأكثر من 24 عاماً من التميز.',
		'header_btn_text'        => 'اطلب الآن',
		'category_section_image' => 0,
		'category_heading'       => 'تسوق أقسامنا',
		'cat1_image'             => 0,
		'cat1_title'             => 'غرف نوم',
		'cat2_image'             => 0,
		'cat2_title'             => 'صالون',
		'cat3_image'             => 0,
		'cat3_title'             => 'غرف أطفال',
		'cat4_image'             => 0,
		'cat4_title'             => 'انتريه',
		'cat5_image'             => 0,
		'cat5_title'             => 'سفرة',
		'products_section_image' => 0,
		'products_heading'       => 'أحدث منتجاتنا',
		'products_banner_image'  => 0,
		'offers_section_image'   => 0,
		'offers_heading'         => 'أقوى العروض',
		'offers_image'           => 0,
	);
}

/** مسارات الصور الافتراضية في الثيم (عند عدم رفع بديل). */
function dark_theme_home_default_image_paths() {
	return array(
		'header_image'           => 'assets/header.png',
		'category_section_image' => 'assets/style.png',
		'cat1_image'             => 'assets/circle1.png',
		'cat2_image'             => 'assets/circle2.png',
		'cat3_image'             => 'assets/circle3.png',
		'cat4_image'             => 'assets/circle4.png',
		'cat5_image'             => 'assets/circle5.png',
		'products_section_image' => 'assets/style.png',
		'products_banner_image'  => 'assets/banner.png',
		'offers_section_image'   => 'assets/style.png',
		'offers_image'           => 'assets/section2.png',
	);
}

/**
 * إرجاع محتوى الصفحة الرئيسية (المُحرَّر أو الافتراضي) مع تحويل معرفات الصور إلى روابط.
 */
function dark_theme_get_home_content() {
	$saved   = get_option( 'dark_theme_home_content', array() );
	$default = dark_theme_default_home_content();
	$paths   = dark_theme_home_default_image_paths();
	$content = array_merge( $default, is_array( $saved ) ? $saved : array() );

	foreach ( array_keys( $paths ) as $key ) {
		$id = isset( $content[ $key ] ) ? (int) $content[ $key ] : 0;
		if ( $id > 0 ) {
			$url = wp_get_attachment_image_url( $id, 'full' );
			$content[ $key ] = $url ? $url : dark_theme_asset_uri( $paths[ $key ] );
		} else {
			$content[ $key ] = dark_theme_asset_uri( $paths[ $key ] );
		}
	}

	return $content;
}

/** إرجاع القيم المحفوظة للصفحة الرئيسية (للعرض في لوحة التحكم). */
function dark_theme_get_home_content_raw() {
	$saved   = get_option( 'dark_theme_home_content', array() );
	$default = dark_theme_default_home_content();
	return array_merge( $default, is_array( $saved ) ? $saved : array() );
}

/**
 * الألوان الافتراضية لإعدادات الموقع (للاستعادة).
 */
function dark_theme_default_site_colors() {
	return array(
		'button_primary'      => '#A4825D',
		'header_background'   => '#A3B993',
		'footer_background'   => '#A3B993',
		'product_card_bg'    => '#E5CBAD',
	);
}

/** إرجاع ألوان الموقع (المحفوظة أو الافتراضية). */
function dark_theme_get_site_colors() {
	$saved   = get_option( 'dark_theme_site_colors', array() );
	$default = dark_theme_default_site_colors();
	return array_merge( $default, is_array( $saved ) ? $saved : array() );
}

/**
 * التعليقات الافتراضية لقسم المراجعات في الرئيسية.
 */
function dark_theme_default_home_reviews() {
	return array(
		array(
			'name'   => 'عبدالله احمد',
			'text'   => 'جميل جدًا وعندهم سرعة في التوصيل، وكمان راضٍ عن التغليف.',
			'rating' => 5,
			'image'  => 0,
		),
		array(
			'name'   => 'محمد علي',
			'text'   => 'خدمة ممتازة وجودة عالية في المنتجات، أنصح الجميع بالتعامل معهم.',
			'rating' => 5,
			'image'  => 0,
		),
		array(
			'name'   => 'فاطمة سعد',
			'text'   => 'منتجات رائعة وخدمة عملاء ممتازة، تجربة تسوق مميزة.',
			'rating' => 5,
			'image'  => 0,
		),
		array(
			'name'   => 'أحمد محمود',
			'text'   => 'أثاث عالي الجودة وأسعار مناسبة، أنصح بالتعامل معهم بقوة.',
			'rating' => 5,
			'image'  => 0,
		),
	);
}

/** إرجاع تعليقات الرئيسية (المحفوظة أو الافتراضية) مع روابط الصور. */
function dark_theme_get_home_reviews() {
	$saved = get_option( 'dark_theme_home_reviews', array() );
	if ( ! is_array( $saved ) || empty( $saved ) ) {
		$saved = dark_theme_default_home_reviews();
	}
	$default_img = dark_theme_asset_uri( 'assets/review.jpg' );
	foreach ( $saved as $i => $r ) {
		$saved[ $i ]['image_url'] = ! empty( $r['image'] ) ? wp_get_attachment_image_url( (int) $r['image'], 'thumbnail' ) : $default_img;
		if ( ! $saved[ $i ]['image_url'] ) {
			$saved[ $i ]['image_url'] = $default_img;
		}
		$saved[ $i ]['rating'] = max( 1, min( 5, (int) ( $r['rating'] ?? 5 ) ) );
	}
	return $saved;
}

function dark_theme_register_content_admin() {
	add_menu_page(
		'المحتوى',
		'المحتوى',
		'manage_options',
		'dark-theme-content',
		'dark_theme_render_content_admin',
		'dashicons-admin-page',
		30
	);
	add_submenu_page(
		'dark-theme-content',
		'الصفحة الرئيسية',
		'الصفحة الرئيسية',
		'manage_options',
		'dark-theme-home-content',
		'dark_theme_render_home_content_admin'
	);
	add_submenu_page(
		'dark-theme-content',
		'الصفحات',
		'الصفحات',
		'manage_options',
		'dark-theme-pages',
		'dark_theme_render_pages_admin'
	);
	add_submenu_page(
		'dark-theme-content',
		'إعدادات الموقع',
		'إعدادات الموقع',
		'manage_options',
		'dark-theme-site-settings',
		'dark_theme_render_site_settings_admin'
	);
	add_submenu_page(
		'dark-theme-content',
		'تعليقات الرئيسية',
		'التعليقات',
		'manage_options',
		'dark-theme-home-reviews',
		'dark_theme_render_home_reviews_admin'
	);
	add_submenu_page(
		'dark-theme-content',
		'سياسة الخصوصية',
		'سياسة الخصوصية',
		'manage_options',
		'dark-theme-policy-privacy',
		function () {
			dark_theme_render_policy_editor_admin( 'privacy-policy' );
		}
	);
	add_submenu_page(
		'dark-theme-content',
		'سياسة الاسترجاع',
		'سياسة الاسترجاع',
		'manage_options',
		'dark-theme-policy-refund',
		function () {
			dark_theme_render_policy_editor_admin( 'refund-policy' );
		}
	);
	add_submenu_page(
		'dark-theme-content',
		'سياسة الشحن',
		'سياسة الشحن',
		'manage_options',
		'dark-theme-policy-shipping',
		function () {
			dark_theme_render_policy_editor_admin( 'shipping-policy' );
		}
	);
	add_submenu_page(
		'dark-theme-content',
		'منتجات ديمو',
		'منتجات ديمو',
		'manage_options',
		'dark-theme-demo-products',
		'dark_theme_render_demo_products_admin'
	);
}
add_action( 'admin_menu', 'dark_theme_register_content_admin' );

function dark_theme_render_content_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>المحتوى</h1><p>اختر من القائمة الجانبية لإدارة الصفحة الرئيسية أو الصفحات أو إعدادات الموقع أو السياسات أو منتجات الديمو.</p></div>';
}

/**
 * تحرير محتوى صفحة سياسة (خصوصية / استرجاع / شحن).
 *
 * @param string $slug مسار الصفحة: privacy-policy | refund-policy | shipping-policy
 */
function dark_theme_render_policy_editor_admin( $slug ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$pages  = dark_theme_pages_manifest();
	$title  = isset( $pages[ $slug ] ) ? $pages[ $slug ] : $slug;
	$page   = get_page_by_path( $slug );

	if ( ! $page ) {
		echo '<div class="wrap">';
		echo '<h1>' . esc_html( $title ) . '</h1>';
		echo '<div class="notice notice-warning"><p>الصفحة غير موجودة بعد. يرجى <a href="' . esc_url( admin_url( 'admin.php?page=dark-theme-pages' ) ) . '">إنشاء/تحديث الصفحات</a> من قسم "الصفحات" أولاً.</p></div>';
		echo '</div>';
		return;
	}

	$content = $page->post_content;
	$page_title = $page->post_title;

	if ( isset( $_GET['dark_theme_policy_saved'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>تم حفظ المحتوى.</p></div>';
	}

	wp_enqueue_editor();
	wp_enqueue_media();
	wp_enqueue_style( 'editor-buttons' );
	?>
	<div class="wrap">
		<h1>تحرير: <?php echo esc_html( $title ); ?></h1>
		<p><a href="<?php echo esc_url( get_permalink( $page->ID ) ); ?>" target="_blank">معاينة الصفحة</a></p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'dark_theme_save_policy', 'dark_theme_save_policy_nonce' ); ?>
			<input type="hidden" name="action" value="dark_theme_save_policy">
			<input type="hidden" name="policy_slug" value="<?php echo esc_attr( $slug ); ?>">

			<table class="form-table">
				<tr>
					<th><label for="policy_title">عنوان الصفحة</label></th>
					<td><input type="text" id="policy_title" name="policy_title" value="<?php echo esc_attr( $page_title ); ?>" class="large-text" /></td>
				</tr>
				<tr>
					<th><label for="policy_content">المحتوى</label></th>
					<td>
						<?php
						wp_editor(
							$content,
							'policy_content',
							array(
								'textarea_name' => 'policy_content',
								'textarea_rows' => 20,
								'media_buttons' => true,
								'teeny'         => false,
								'quicktags'     => true,
								'tinymce'       => array(
									'toolbar1' => 'formatselect,bold,italic,underline,blockquote,link,unlink,bullist,numlist,outdent,indent,undo,redo',
								),
								'editor_css'    => '',
								'dfw'           => false,
							)
						);
						?>
					</td>
				</tr>
			</table>

			<p class="submit">
				<?php submit_button( 'حفظ التغييرات', 'primary', 'submit', false ); ?>
			</p>
		</form>
	</div>
	<?php
}

function dark_theme_save_policy_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'dark_theme_save_policy', 'dark_theme_save_policy_nonce' );

	$slug = isset( $_POST['policy_slug'] ) ? sanitize_text_field( wp_unslash( $_POST['policy_slug'] ) ) : '';
	$allowed = array( 'privacy-policy', 'refund-policy', 'shipping-policy' );
	if ( ! in_array( $slug, $allowed, true ) ) {
		wp_safe_redirect( admin_url( 'admin.php?page=dark-theme-content' ) );
		exit;
	}

	$page = get_page_by_path( $slug );
	if ( ! $page ) {
		wp_safe_redirect( admin_url( 'admin.php?page=dark-theme-policy-' . str_replace( '-policy', '', $slug ) ) );
		exit;
	}

	$title   = isset( $_POST['policy_title'] ) ? sanitize_text_field( wp_unslash( $_POST['policy_title'] ) ) : $page->post_title;
	$content = isset( $_POST['policy_content'] ) ? wp_kses_post( wp_unslash( $_POST['policy_content'] ) ) : $page->post_content;

	wp_update_post(
		array(
			'ID'           => $page->ID,
			'post_title'   => $title,
			'post_content' => $content,
		)
	);

	$page_map = array(
		'privacy-policy'  => 'privacy',
		'refund-policy'   => 'refund',
		'shipping-policy' => 'shipping',
	);
	$admin_slug = 'dark-theme-policy-' . ( isset( $page_map[ $slug ] ) ? $page_map[ $slug ] : $slug );
	wp_safe_redirect( add_query_arg( 'dark_theme_policy_saved', '1', admin_url( 'admin.php?page=' . $admin_slug ) ) );
	exit;
}
add_action( 'admin_post_dark_theme_save_policy', 'dark_theme_save_policy_handler' );

function dark_theme_render_home_content_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	wp_enqueue_media();
	$content = dark_theme_get_home_content_raw();
	?>
	<div class="wrap">
		<h1>تعديل الصفحة الرئيسية</h1>
		<?php if ( isset( $_GET['dark_theme_home_saved'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم حفظ التغييرات.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['dark_theme_home_restored'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم استعادة المحتوى الأصلي.</p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="dark-theme-home-content-form">
			<?php wp_nonce_field( 'dark_theme_save_home_content', 'dark_theme_home_content_nonce' ); ?>
			<input type="hidden" name="action" value="dark_theme_save_home_content">

			<h2 class="title">الهيدر</h2>
			<table class="form-table">
				<tr>
					<th><label>صورة الهيدر</label></th>
					<td>
						<div class="dark-theme-image-field">
							<div class="dark-theme-image-preview">
								<?php
								$img_id = (int) ( $content['header_image'] ?? 0 );
								if ( $img_id > 0 ) {
									echo wp_get_attachment_image( $img_id, 'thumbnail' );
								} else {
									echo '<img src="' . esc_url( dark_theme_asset_uri( 'assets/header.png' ) ) . '" alt="" style="max-width:150px;height:auto;" />';
								}
								?>
							</div>
							<input type="hidden" name="home_content[header_image]" value="<?php echo esc_attr( $content['header_image'] ?? 0 ); ?>" class="dark-theme-image-id" />
							<button type="button" class="button dark-theme-upload-image">رفع صورة</button>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="home_header_title">عنوان الهيدر</label></th>
					<td><input type="text" id="home_header_title" name="home_content[header_title]" value="<?php echo esc_attr( $content['header_title'] ?? '' ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label for="home_header_text">نص الهيدر</label></th>
					<td><textarea id="home_header_text" name="home_content[header_text]" rows="4" class="large-text"><?php echo esc_textarea( $content['header_text'] ?? '' ); ?></textarea></td>
				</tr>
				<tr>
					<th><label for="home_header_btn_text">نص زر الهيدر</label></th>
					<td><input type="text" id="home_header_btn_text" name="home_content[header_btn_text]" value="<?php echo esc_attr( $content['header_btn_text'] ?? '' ); ?>" class="regular-text" /></td>
				</tr>
			</table>

			<h2 class="title">قسم الأقسام (تسوق أقسامنا)</h2>
			<table class="form-table">
				<tr>
					<th><label>صورة عنوان القسم</label></th>
					<td>
						<div class="dark-theme-image-field">
							<div class="dark-theme-image-preview">
								<?php
								$img_id = (int) ( $content['category_section_image'] ?? 0 );
								if ( $img_id > 0 ) {
									echo wp_get_attachment_image( $img_id, 'thumbnail' );
								} else {
									echo '<img src="' . esc_url( dark_theme_asset_uri( 'assets/style.png' ) ) . '" alt="" style="max-width:150px;height:auto;" />';
								}
								?>
							</div>
							<input type="hidden" name="home_content[category_section_image]" value="<?php echo esc_attr( $content['category_section_image'] ?? 0 ); ?>" class="dark-theme-image-id" />
							<button type="button" class="button dark-theme-upload-image">رفع صورة</button>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="home_category_heading">عنوان القسم</label></th>
					<td><input type="text" id="home_category_heading" name="home_content[category_heading]" value="<?php echo esc_attr( $content['category_heading'] ?? '' ); ?>" class="regular-text" /></td>
				</tr>
				<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
				<tr>
					<th><label>صورة قسم <?php echo (int) $i; ?></label></th>
					<td>
						<div class="dark-theme-image-field">
							<div class="dark-theme-image-preview">
								<?php
								$key  = "cat{$i}_image";
								$img_id = (int) ( $content[ $key ] ?? 0 );
								$def_path = 'assets/circle' . $i . '.png';
								if ( $img_id > 0 ) {
									echo wp_get_attachment_image( $img_id, 'thumbnail' );
								} else {
									echo '<img src="' . esc_url( dark_theme_asset_uri( $def_path ) ) . '" alt="" style="max-width:80px;height:auto;" />';
								}
								?>
							</div>
							<input type="hidden" name="home_content[<?php echo esc_attr( $key ); ?>]" value="<?php echo esc_attr( $content[ $key ] ?? 0 ); ?>" class="dark-theme-image-id" />
							<button type="button" class="button dark-theme-upload-image">رفع صورة</button>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="home_cat<?php echo $i; ?>_title">عنوان قسم <?php echo (int) $i; ?></label></th>
					<td><input type="text" id="home_cat<?php echo $i; ?>_title" name="home_content[cat<?php echo $i; ?>_title]" value="<?php echo esc_attr( $content[ "cat{$i}_title" ] ?? '' ); ?>" class="regular-text" /></td>
				</tr>
				<?php endfor; ?>
			</table>

			<h2 class="title">قسم أحدث المنتجات</h2>
			<table class="form-table">
				<tr>
					<th><label>صورة عنوان القسم</label></th>
					<td>
						<div class="dark-theme-image-field">
							<div class="dark-theme-image-preview">
								<?php
								$img_id = (int) ( $content['products_section_image'] ?? 0 );
								if ( $img_id > 0 ) {
									echo wp_get_attachment_image( $img_id, 'thumbnail' );
								} else {
									echo '<img src="' . esc_url( dark_theme_asset_uri( 'assets/style.png' ) ) . '" alt="" style="max-width:150px;height:auto;" />';
								}
								?>
							</div>
							<input type="hidden" name="home_content[products_section_image]" value="<?php echo esc_attr( $content['products_section_image'] ?? 0 ); ?>" class="dark-theme-image-id" />
							<button type="button" class="button dark-theme-upload-image">رفع صورة</button>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="home_products_heading">عنوان القسم</label></th>
					<td><input type="text" id="home_products_heading" name="home_content[products_heading]" value="<?php echo esc_attr( $content['products_heading'] ?? '' ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label>صورة البانر</label></th>
					<td>
						<div class="dark-theme-image-field">
							<div class="dark-theme-image-preview">
								<?php
								$img_id = (int) ( $content['products_banner_image'] ?? 0 );
								if ( $img_id > 0 ) {
									echo wp_get_attachment_image( $img_id, 'thumbnail' );
								} else {
									echo '<img src="' . esc_url( dark_theme_asset_uri( 'assets/banner.png' ) ) . '" alt="" style="max-width:150px;height:auto;" />';
								}
								?>
							</div>
							<input type="hidden" name="home_content[products_banner_image]" value="<?php echo esc_attr( $content['products_banner_image'] ?? 0 ); ?>" class="dark-theme-image-id" />
							<button type="button" class="button dark-theme-upload-image">رفع صورة</button>
						</div>
					</td>
				</tr>
			</table>

			<h2 class="title">قسم أقوى العروض</h2>
			<table class="form-table">
				<tr>
					<th><label>صورة عنوان القسم</label></th>
					<td>
						<div class="dark-theme-image-field">
							<div class="dark-theme-image-preview">
								<?php
								$img_id = (int) ( $content['offers_section_image'] ?? 0 );
								if ( $img_id > 0 ) {
									echo wp_get_attachment_image( $img_id, 'thumbnail' );
								} else {
									echo '<img src="' . esc_url( dark_theme_asset_uri( 'assets/style.png' ) ) . '" alt="" style="max-width:150px;height:auto;" />';
								}
								?>
							</div>
							<input type="hidden" name="home_content[offers_section_image]" value="<?php echo esc_attr( $content['offers_section_image'] ?? 0 ); ?>" class="dark-theme-image-id" />
							<button type="button" class="button dark-theme-upload-image">رفع صورة</button>
						</div>
					</td>
				</tr>
				<tr>
					<th><label for="home_offers_heading">عنوان القسم</label></th>
					<td><input type="text" id="home_offers_heading" name="home_content[offers_heading]" value="<?php echo esc_attr( $content['offers_heading'] ?? '' ); ?>" class="regular-text" /></td>
				</tr>
				<tr>
					<th><label>صورة قسم العروض</label></th>
					<td>
						<div class="dark-theme-image-field">
							<div class="dark-theme-image-preview">
								<?php
								$img_id = (int) ( $content['offers_image'] ?? 0 );
								if ( $img_id > 0 ) {
									echo wp_get_attachment_image( $img_id, 'thumbnail' );
								} else {
									echo '<img src="' . esc_url( dark_theme_asset_uri( 'assets/section2.png' ) ) . '" alt="" style="max-width:150px;height:auto;" />';
								}
								?>
							</div>
							<input type="hidden" name="home_content[offers_image]" value="<?php echo esc_attr( $content['offers_image'] ?? 0 ); ?>" class="dark-theme-image-id" />
							<button type="button" class="button dark-theme-upload-image">رفع صورة</button>
						</div>
					</td>
				</tr>
			</table>

			<p class="submit">
				<?php submit_button( 'حفظ التغييرات', 'primary', 'submit', false ); ?>
			</p>
		</form>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:1em;" onsubmit="return confirm('هل تريد استعادة المحتوى الأصلي؟ سيتم استبدال كل التعديلات.');">
			<?php wp_nonce_field( 'dark_theme_restore_home_content', 'dark_theme_restore_home_nonce' ); ?>
			<input type="hidden" name="action" value="dark_theme_restore_home_content">
			<?php submit_button( 'استعادة المحتوى الأصلي', 'secondary', 'restore', false ); ?>
		</form>
	</div>

	<script>
	(function(){
		document.addEventListener('DOMContentLoaded', function() {
			var uploadBtns = document.querySelectorAll('.dark-theme-upload-image');
			uploadBtns.forEach(function(btn) {
				btn.addEventListener('click', function() {
					var wrap = btn.closest('.dark-theme-image-field');
					var input = wrap ? wrap.querySelector('.dark-theme-image-id') : null;
					var preview = wrap ? wrap.querySelector('.dark-theme-image-preview') : null;
					if (!input) return;
					var frame = wp.media({
						library: { type: 'image' },
						multiple: false
					});
					frame.on('select', function() {
						var att = frame.state().get('selection').first().toJSON();
						input.value = att.id;
						if (preview) {
							preview.innerHTML = '<img src="' + (att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url) + '" style="max-width:150px;height:auto;" />';
						}
					});
					frame.open();
				});
			});
		});
	})();
	</script>
	<?php
}

function dark_theme_save_home_content_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'dark_theme_save_home_content', 'dark_theme_home_content_nonce' );

	$default = dark_theme_default_home_content();
	$allowed_keys = array_keys( $default );
	$raw = isset( $_POST['home_content'] ) && is_array( $_POST['home_content'] ) ? wp_unslash( $_POST['home_content'] ) : array();
	$saved = array();
	foreach ( $allowed_keys as $key ) {
		if ( ! isset( $raw[ $key ] ) ) {
			continue;
		}
		if ( in_array( $key, array( 'header_image', 'category_section_image', 'cat1_image', 'cat2_image', 'cat3_image', 'cat4_image', 'cat5_image', 'products_section_image', 'products_banner_image', 'offers_section_image', 'offers_image' ), true ) ) {
			$saved[ $key ] = absint( $raw[ $key ] );
		} else {
			$saved[ $key ] = sanitize_text_field( $raw[ $key ] );
		}
	}
	update_option( 'dark_theme_home_content', $saved );
	wp_safe_redirect( add_query_arg( 'dark_theme_home_saved', '1', admin_url( 'admin.php?page=dark-theme-home-content' ) ) );
	exit;
}
add_action( 'admin_post_dark_theme_save_home_content', 'dark_theme_save_home_content_handler' );

function dark_theme_restore_home_content_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'dark_theme_restore_home_content', 'dark_theme_restore_home_nonce' );
	delete_option( 'dark_theme_home_content' );
	wp_safe_redirect( add_query_arg( 'dark_theme_home_restored', '1', admin_url( 'admin.php?page=dark-theme-home-content' ) ) );
	exit;
}
add_action( 'admin_post_dark_theme_restore_home_content', 'dark_theme_restore_home_content_handler' );

/**
 * صفحة إعدادات الموقع: الألوان الرئيسية (أزرار، هيدر، فوتر، بطاقة منتج).
 */
function dark_theme_render_site_settings_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$colors = dark_theme_get_site_colors();

	if ( isset( $_GET['dark_theme_site_saved'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>تم حفظ الإعدادات.</p></div>';
	}
	if ( isset( $_GET['dark_theme_site_restored'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>تم استعادة الألوان الرئيسية.</p></div>';
	}
	?>
	<div class="wrap">
		<h1>إعدادات الموقع — الألوان الرئيسية</h1>
		<p>تعديل الألوان المستخدمة في الأزرار (أضف إلى السلة، شراء الآن)، وخلفية الهيدر والفوتر وبطاقة المنتج.</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'dark_theme_save_site_settings', 'dark_theme_site_settings_nonce' ); ?>
			<input type="hidden" name="action" value="dark_theme_save_site_settings">

			<table class="form-table">
				<tr>
					<th><label for="color_button_primary">لون الأزرار الرئيسي</label></th>
					<td>
						<input type="color" id="color_button_primary" name="site_colors[button_primary]" value="<?php echo esc_attr( $colors['button_primary'] ); ?>" />
						<span class="description">أزرار: أضف إلى السلة، شراء الآن، إلخ.</span>
					</td>
				</tr>
				<tr>
					<th><label for="color_header_background">خلفية الهيدر (الشريط العلوي)</label></th>
					<td>
						<input type="color" id="color_header_background" name="site_colors[header_background]" value="<?php echo esc_attr( $colors['header_background'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th><label for="color_footer_background">خلفية الفوتر</label></th>
					<td>
						<input type="color" id="color_footer_background" name="site_colors[footer_background]" value="<?php echo esc_attr( $colors['footer_background'] ); ?>" />
					</td>
				</tr>
				<tr>
					<th><label for="color_product_card_bg">خلفية بطاقة المنتج</label></th>
					<td>
						<input type="color" id="color_product_card_bg" name="site_colors[product_card_bg]" value="<?php echo esc_attr( $colors['product_card_bg'] ); ?>" />
					</td>
				</tr>
			</table>

			<p class="submit">
				<?php submit_button( 'حفظ التغييرات', 'primary', 'submit', false ); ?>
			</p>
		</form>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:1em;" onsubmit="return confirm('هل تريد استعادة الألوان الرئيسية الافتراضية؟');">
			<?php wp_nonce_field( 'dark_theme_restore_site_settings', 'dark_theme_restore_site_nonce' ); ?>
			<input type="hidden" name="action" value="dark_theme_restore_site_settings">
			<?php submit_button( 'استعادة الألوان الرئيسية', 'secondary', 'restore', false ); ?>
		</form>
	</div>
	<?php
}

function dark_theme_save_site_settings_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'dark_theme_save_site_settings', 'dark_theme_site_settings_nonce' );

	$allowed = array( 'button_primary', 'header_background', 'footer_background', 'product_card_bg' );
	$raw    = isset( $_POST['site_colors'] ) && is_array( $_POST['site_colors'] ) ? wp_unslash( $_POST['site_colors'] ) : array();
	$saved  = array();
	foreach ( $allowed as $key ) {
		if ( isset( $raw[ $key ] ) && preg_match( '/^#[0-9A-Fa-f]{6}$/', $raw[ $key ] ) ) {
			$saved[ $key ] = $raw[ $key ];
		}
	}
	update_option( 'dark_theme_site_colors', $saved );
	wp_safe_redirect( add_query_arg( 'dark_theme_site_saved', '1', admin_url( 'admin.php?page=dark-theme-site-settings' ) ) );
	exit;
}
add_action( 'admin_post_dark_theme_save_site_settings', 'dark_theme_save_site_settings_handler' );

function dark_theme_restore_site_settings_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'dark_theme_restore_site_settings', 'dark_theme_restore_site_nonce' );
	delete_option( 'dark_theme_site_colors' );
	wp_safe_redirect( add_query_arg( 'dark_theme_site_restored', '1', admin_url( 'admin.php?page=dark-theme-site-settings' ) ) );
	exit;
}
add_action( 'admin_post_dark_theme_restore_site_settings', 'dark_theme_restore_site_settings_handler' );

/**
 * صفحة إدارة تعليقات الرئيسية: عرض التعليقات المعروضة حالياً مع التعديل والحذف والإضافة.
 */
function dark_theme_render_home_reviews_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	wp_enqueue_media();
	$saved         = get_option( 'dark_theme_home_reviews', array() );
	$saved         = is_array( $saved ) ? $saved : array();
	$display_list  = dark_theme_get_home_reviews();
	$is_using_saved = ! empty( $saved );
	$edit_index    = isset( $_GET['edit'] ) ? max( 0, (int) $_GET['edit'] ) : null;
	$edit_item     = null;
	if ( $edit_index !== null && isset( $display_list[ $edit_index ] ) ) {
		$raw = $display_list[ $edit_index ];
		$edit_item = array(
			'name'   => $raw['name'] ?? '',
			'text'   => $raw['text'] ?? '',
			'rating' => (int) ( $raw['rating'] ?? 5 ),
			'image'  => (int) ( $raw['image'] ?? 0 ),
		);
	}
	$default_img = dark_theme_asset_uri( 'assets/review.jpg' );
	?>
	<div class="wrap">
		<h1>تعليقات الرئيسية</h1>
		<p>التعليقات أدناه هي المعروضة حالياً في قسم المراجعات في الصفحة الرئيسية. يمكنك التعديل أو الحذف أو إضافة تعليق جديد.</p>

		<?php if ( isset( $_GET['dark_theme_review_saved'] ) ) : ?>
			<div class="notice notice-success"><p>تم حفظ التعليق بنجاح.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['dark_theme_review_deleted'] ) ) : ?>
			<div class="notice notice-success"><p>تم حذف التعليق.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['dark_theme_reviews_restored'] ) ) : ?>
			<div class="notice notice-success"><p>تم استعادة التعليقات الافتراضية.</p></div>
		<?php endif; ?>

		<h2><?php echo $edit_item ? 'تعديل التعليق' : 'إضافة تعليق جديد'; ?></h2>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="max-width:600px;">
			<?php wp_nonce_field( 'dark_theme_save_home_review', 'dark_theme_save_review_nonce' ); ?>
			<input type="hidden" name="action" value="dark_theme_save_home_review">
			<?php if ( $edit_index !== null ) : ?>
				<input type="hidden" name="dark_theme_review_index" value="<?php echo (int) $edit_index; ?>">
				<?php if ( ! $is_using_saved ) : ?>
					<input type="hidden" name="dark_theme_review_from_default" value="1">
				<?php endif; ?>
			<?php endif; ?>
			<table class="form-table">
				<tr>
					<th><label for="review_name">الاسم</label></th>
					<td><input type="text" id="review_name" name="review_name" value="<?php echo $edit_item ? esc_attr( $edit_item['name'] ?? '' ) : ''; ?>" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="review_text">نص التعليق</label></th>
					<td><textarea id="review_text" name="review_text" rows="4" class="large-text" required><?php echo $edit_item ? esc_textarea( $edit_item['text'] ?? '' ) : ''; ?></textarea></td>
				</tr>
				<tr>
					<th><label for="review_rating">التقييم (1–5)</label></th>
					<td>
						<select id="review_rating" name="review_rating">
							<?php for ( $i = 1; $i <= 5; $i++ ) : ?>
								<option value="<?php echo $i; ?>" <?php selected( (int) ( $edit_item['rating'] ?? 5 ), $i ); ?>><?php echo $i; ?> نجوم</option>
							<?php endfor; ?>
						</select>
					</td>
				</tr>
				<tr>
					<th>صورة</th>
					<td>
						<?php
						$img_id  = $edit_item ? ( (int) ( $edit_item['image'] ?? 0 ) ) : 0;
						$img_url = $img_id ? wp_get_attachment_image_url( $img_id, 'thumbnail' ) : $default_img;
						if ( ! $img_url ) {
							$img_url = $default_img;
						}
						?>
						<div class="dark-theme-review-image-wrap">
							<img id="dark_theme_review_img_preview" src="<?php echo esc_url( $img_url ); ?>" alt="" style="max-width:80px;height:80px;object-fit:cover;display:block;margin-bottom:8px;">
							<input type="hidden" id="dark_theme_review_image" name="review_image" value="<?php echo (int) $img_id; ?>">
							<button type="button" class="button" id="dark_theme_review_upload_btn">اختيار صورة</button>
							<button type="button" class="button" id="dark_theme_review_clear_img_btn" <?php echo ! $img_id ? 'style="display:none;"' : ''; ?>>إزالة الصورة</button>
						</div>
					</td>
				</tr>
			</table>
			<p class="submit">
				<button type="submit" class="button button-primary"><?php echo $edit_item ? 'تحديث التعليق' : 'إضافة التعليق'; ?></button>
				<?php if ( $edit_item ) : ?>
					<a href="<?php echo esc_url( admin_url( 'admin.php?page=dark-theme-home-reviews' ) ); ?>" class="button">إلغاء</a>
				<?php endif; ?>
			</p>
		</form>

		<h2>التعليقات المعروضة حالياً</h2>
		<?php if ( empty( $display_list ) ) : ?>
			<p>لا توجد تعليقات. أضف تعليقاً جديداً من النموذج أعلاه.</p>
		<?php else : ?>
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th>الاسم</th>
						<th>نص التعليق</th>
						<th>التقييم</th>
						<th>صورة</th>
						<th>إجراءات</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $display_list as $idx => $rev ) : ?>
						<tr>
							<td><?php echo esc_html( $rev['name'] ?? '' ); ?></td>
							<td><?php echo esc_html( wp_trim_words( $rev['text'] ?? '', 12 ) ); ?></td>
							<td><?php echo (int) ( $rev['rating'] ?? 5 ); ?> نجوم</td>
							<td>
								<?php $url = $rev['image_url'] ?? $default_img; ?>
								<img src="<?php echo esc_url( $url ); ?>" alt="" style="max-width:40px;height:40px;object-fit:cover;">
							</td>
							<td>
								<a href="<?php echo esc_url( add_query_arg( 'edit', $idx, admin_url( 'admin.php?page=dark-theme-home-reviews' ) ) ); ?>">تعديل</a>
								&nbsp;|&nbsp;
								<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline;" onsubmit="return confirm('حذف هذا التعليق؟');">
									<?php wp_nonce_field( 'dark_theme_delete_home_review', 'dark_theme_delete_review_nonce' ); ?>
									<input type="hidden" name="action" value="dark_theme_delete_home_review">
									<input type="hidden" name="dark_theme_review_index" value="<?php echo (int) $idx; ?>">
									<?php if ( ! $is_using_saved ) : ?>
										<input type="hidden" name="dark_theme_review_from_default" value="1">
									<?php endif; ?>
									<button type="submit" class="button-link link-delete">حذف</button>
								</form>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<?php if ( $is_using_saved ) : ?>
			<p style="margin-top:1.5rem;">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline;" onsubmit="return confirm('استعادة التعليقات الافتراضية؟ سيتم حذف التعديلات الحالية.');">
					<?php wp_nonce_field( 'dark_theme_restore_home_reviews', 'dark_theme_restore_reviews_nonce' ); ?>
					<input type="hidden" name="action" value="dark_theme_restore_home_reviews">
					<button type="submit" class="button">استعادة التعليقات الافتراضية</button>
				</form>
			</p>
		<?php endif; ?>
	</div>
	<script>
	(function(){
		var btn = document.getElementById('dark_theme_review_upload_btn');
		var clearBtn = document.getElementById('dark_theme_review_clear_img_btn');
		var imgInput = document.getElementById('dark_theme_review_image');
		var preview = document.getElementById('dark_theme_review_img_preview');
		var defaultImg = '<?php echo esc_js( $default_img ); ?>';
		if (btn) {
			btn.onclick = function() {
				var f = wp.media({ multiple: false, library: { type: 'image' } });
				f.on('select', function() {
					var att = f.state().get('selection').first().toJSON();
					if (att && att.id) {
						imgInput.value = att.id;
						preview.src = att.sizes && att.sizes.thumbnail ? att.sizes.thumbnail.url : att.url;
						if (clearBtn) clearBtn.style.display = 'inline-block';
					}
				});
				f.open();
			};
		}
		if (clearBtn && imgInput) {
			clearBtn.onclick = function() {
				imgInput.value = '0';
				preview.src = defaultImg;
				clearBtn.style.display = 'none';
			};
		}
	})();
	</script>
	<?php
}

function dark_theme_save_home_review_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'dark_theme_save_home_review', 'dark_theme_save_review_nonce' );
	$reviews = get_option( 'dark_theme_home_reviews', array() );
	$reviews = is_array( $reviews ) ? $reviews : array();
	$from_default = ! empty( $_POST['dark_theme_review_from_default'] ) && empty( $reviews );
	if ( $from_default ) {
		$reviews = dark_theme_default_home_reviews();
	}
	$name   = sanitize_text_field( wp_unslash( $_POST['review_name'] ?? '' ) );
	$text   = sanitize_textarea_field( wp_unslash( $_POST['review_text'] ?? '' ) );
	$rating = max( 1, min( 5, (int) ( $_POST['review_rating'] ?? 5 ) ) );
	$image  = max( 0, (int) ( $_POST['review_image'] ?? 0 ) );
	if ( $name === '' || $text === '' ) {
		wp_safe_redirect( add_query_arg( 'dark_theme_review_error', '1', admin_url( 'admin.php?page=dark-theme-home-reviews' ) ) );
		exit;
	}
	$item      = array( 'name' => $name, 'text' => $text, 'rating' => $rating, 'image' => $image );
	$edit_index = isset( $_POST['dark_theme_review_index'] ) ? (int) $_POST['dark_theme_review_index'] : null;
	if ( $edit_index !== null && isset( $reviews[ $edit_index ] ) ) {
		$reviews[ $edit_index ] = $item;
	} else {
		$reviews[] = $item;
	}
	update_option( 'dark_theme_home_reviews', $reviews );
	wp_safe_redirect( add_query_arg( 'dark_theme_review_saved', '1', admin_url( 'admin.php?page=dark-theme-home-reviews' ) ) );
	exit;
}
add_action( 'admin_post_dark_theme_save_home_review', 'dark_theme_save_home_review_handler' );

function dark_theme_delete_home_review_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'dark_theme_delete_home_review', 'dark_theme_delete_review_nonce' );
	$reviews = get_option( 'dark_theme_home_reviews', array() );
	$reviews = is_array( $reviews ) ? $reviews : array();
	$idx = isset( $_POST['dark_theme_review_index'] ) ? (int) $_POST['dark_theme_review_index'] : -1;
	$from_default = ! empty( $_POST['dark_theme_review_from_default'] ) && empty( $reviews );
	if ( $from_default && $idx >= 0 ) {
		$reviews = dark_theme_default_home_reviews();
	}
	if ( $idx >= 0 && isset( $reviews[ $idx ] ) ) {
		array_splice( $reviews, $idx, 1 );
		update_option( 'dark_theme_home_reviews', $reviews );
	}
	wp_safe_redirect( add_query_arg( 'dark_theme_review_deleted', '1', admin_url( 'admin.php?page=dark-theme-home-reviews' ) ) );
	exit;
}
add_action( 'admin_post_dark_theme_delete_home_review', 'dark_theme_delete_home_review_handler' );

function dark_theme_restore_home_reviews_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'dark_theme_restore_home_reviews', 'dark_theme_restore_reviews_nonce' );
	delete_option( 'dark_theme_home_reviews' );
	wp_safe_redirect( add_query_arg( 'dark_theme_reviews_restored', '1', admin_url( 'admin.php?page=dark-theme-home-reviews' ) ) );
	exit;
}
add_action( 'admin_post_dark_theme_restore_home_reviews', 'dark_theme_restore_home_reviews_handler' );

function dark_theme_render_pages_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$pages = dark_theme_pages_manifest();
	?>
	<div class="wrap">
		<h1>إدارة الصفحات</h1>
		<p>يمكنك إنشاء الصفحات أو تحديثها لتطابق القوالب الحالية. <strong>لا توجد صفحة باسم "السياسات"</strong> — "السياسات" عنوان قسم في الفوتر فقط. عند "إنشاء/تحديث الصفحات" يتم إنشاء صفحة منفصلة لكل سياسة: <strong>سياسة الخصوصية</strong>، <strong>سياسة الاسترجاع</strong>، <strong>سياسة الشحن</strong>.</p>

		<?php if ( isset( $_GET['dark_theme_synced'] ) ) : ?>
			<div class="notice notice-success"><p>تم تحديث الصفحات بنجاح.</p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'dark_theme_sync_pages', 'dark_theme_sync_pages_nonce' ); ?>
			<input type="hidden" name="action" value="dark_theme_sync_pages">
			<?php submit_button( 'إنشاء/تحديث الصفحات' ); ?>
		</form>

		<table class="widefat fixed striped">
			<thead>
				<tr>
					<th>العنوان</th>
					<th>المسار</th>
					<th>الحالة</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $pages as $slug => $title ) : ?>
					<?php $page = get_page_by_path( $slug ); ?>
					<tr>
						<td><?php echo esc_html( $title ); ?></td>
						<td><?php echo esc_html( '/' . $slug ); ?></td>
						<td>
							<?php if ( $page ) : ?>
								<a href="<?php echo esc_url( get_edit_post_link( $page->ID ) ); ?>">موجودة</a>
							<?php else : ?>
								غير موجودة
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

function dark_theme_sync_pages_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}

	check_admin_referer( 'dark_theme_sync_pages', 'dark_theme_sync_pages_nonce' );

	$pages = dark_theme_pages_manifest();
	foreach ( $pages as $slug => $title ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			if ( $page->post_title !== $title || 'publish' !== $page->post_status ) {
				wp_update_post(
					array(
						'ID'          => $page->ID,
						'post_title'  => $title,
						'post_status' => 'publish',
					)
				);
			}
		} else {
			wp_insert_post(
				array(
					'post_title'  => $title,
					'post_name'   => $slug,
					'post_status' => 'publish',
					'post_type'   => 'page',
				)
			);
		}
	}

	$home_page = get_page_by_path( 'home' );
	if ( $home_page ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_page->ID );
	}

	if ( function_exists( 'wc_get_page_id' ) ) {
		$shop_page = get_page_by_path( 'shop' );
		if ( $shop_page ) {
			update_option( 'woocommerce_shop_page_id', $shop_page->ID );
		}
		$cart_page = get_page_by_path( 'cart' );
		if ( $cart_page ) {
			update_option( 'woocommerce_cart_page_id', $cart_page->ID );
		}
		$checkout_page = get_page_by_path( 'payment' );
		if ( $checkout_page ) {
			update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
		}
		$account_page = get_page_by_path( 'my-account' );
		if ( $account_page ) {
			update_option( 'woocommerce_myaccount_page_id', $account_page->ID );
		}
	}

	wp_safe_redirect( add_query_arg( 'dark_theme_synced', '1', admin_url( 'admin.php?page=dark-theme-pages' ) ) );
	exit;
}
add_action( 'admin_post_dark_theme_sync_pages', 'dark_theme_sync_pages_handler' );

define( 'DARK_THEME_DEMO_META', '_dark_theme_demo' );

/**
 * التصنيفات الديمو: 6 تصنيفات × 6 منتجات = 36، + 6 بدون تصنيف = 42 منتج.
 * منتج واحد في كل مجموعة عليه تخفيض (onsale). تكرار استخدام نفس الصورة.
 */
function dark_theme_demo_categories_config() {
	return array(
		'ghuraf-nawm' => 'غرف نوم',
		'tawilat'     => 'طاولات',
		'salon'       => 'صالون',
		'makatib'     => 'مكاتب',
		'dikor'       => 'ديكور',
		'idaa'        => 'إضاءة',
	);
}

function dark_theme_demo_products_manifest() {
	$categories = dark_theme_demo_categories_config();
	$products   = array();
	$base_prices = array( 800, 1200, 1500, 2000, 2500, 3000 );
	$image = 'assets/product.png';

	foreach ( $categories as $slug => $name ) {
		for ( $i = 1; $i <= 6; $i++ ) {
			$regular = $base_prices[ ( $i - 1 ) % 6 ];
			$sale = ( 1 === $i ) ? (int) round( $regular * 0.75 ) : null;
			$products[] = array(
				'slug'          => $slug . '-' . $i,
				'name'          => $name . ' ' . $i,
				'regular_price' => $regular,
				'sale_price'    => $sale,
				'category_slug' => $slug,
				'category_name' => $name,
				'image'         => $image,
			);
		}
	}

	for ( $i = 1; $i <= 6; $i++ ) {
		$regular = $base_prices[ ( $i - 1 ) % 6 ];
		$sale = ( 1 === $i ) ? (int) round( $regular * 0.75 ) : null;
		$products[] = array(
			'slug'          => 'general-' . $i,
			'name'          => 'منتج عام ' . $i,
			'regular_price' => $regular,
			'sale_price'    => $sale,
			'category_slug' => '',
			'category_name' => '',
			'image'         => $image,
		);
	}

	return $products;
}

function dark_theme_render_demo_products_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$demo_products = dark_theme_get_current_demo_products();
	?>
	<div class="wrap">
		<h1>منتجات ديمو</h1>
		<p>يمكنك إنشاء المنتجات التجريبية المرتبطة بالتصميم وصورها.</p>

		<?php if ( isset( $_GET['dark_theme_demo_done'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم إنشاء المنتجات التجريبية بنجاح.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['dark_theme_demo_deleted'] ) ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>تم حذف منتجات الديمو. <?php echo isset( $_GET['deleted_products'] ) ? ' تم حذف ' . (int) $_GET['deleted_products'] . ' منتج.' : ''; ?></p>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $demo_products ) ) : ?>
			<h2 style="margin-top: 24px;">المنتجات الديمو المضافة</h2>
			<table class="widefat fixed striped" style="margin-top: 12px; max-width: 900px;">
				<thead>
					<tr>
						<th style="width: 50px;">#</th>
						<th>المنتج</th>
						<th style="width: 100px;">السعر</th>
						<th>التصنيفات</th>
						<th style="width: 100px;">تعديل</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $demo_products as $i => $row ) : ?>
						<tr>
							<td><?php echo (int) ( $i + 1 ); ?></td>
							<td><?php echo esc_html( $row['title'] ); ?></td>
							<td><?php echo esc_html( $row['price'] ); ?> ر.س</td>
							<td><?php echo esc_html( $row['categories'] ?: '—' ); ?></td>
							<td>
								<?php if ( ! empty( $row['edit_url'] ) ) : ?>
									<a href="<?php echo esc_url( $row['edit_url'] ); ?>" class="button button-small">تعديل</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<p style="margin-top: 16px;">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block;">
					<?php wp_nonce_field( 'dark_theme_delete_demo_products', 'dark_theme_delete_demo_products_nonce' ); ?>
					<input type="hidden" name="action" value="dark_theme_delete_demo_products">
					<button type="submit" class="button button-secondary" onclick="return confirm('هل تريد حذف كل منتجات الديمو؟');">مسح كل منتجات الديمو</button>
				</form>
			</p>
		<?php endif; ?>

		<p style="margin-top: 20px;">
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block;">
				<?php wp_nonce_field( 'dark_theme_create_demo_products', 'dark_theme_create_demo_products_nonce' ); ?>
				<input type="hidden" name="action" value="dark_theme_create_demo_products">
				<?php submit_button( 'إنشاء/تحديث منتجات الديمو', 'primary' ); ?>
			</form>
			<?php if ( ! empty( $demo_products ) ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block; margin-right: 12px;">
					<?php wp_nonce_field( 'dark_theme_delete_demo_products', 'dark_theme_delete_demo_products_nonce' ); ?>
					<input type="hidden" name="action" value="dark_theme_delete_demo_products">
					<button type="submit" class="button" onclick="return confirm('هل تريد حذف كل منتجات الديمو؟');">مسح كل منتجات الديمو</button>
				</form>
			<?php endif; ?>
		</p>
	</div>
	<?php
}

function dark_theme_import_image_from_theme( $relative_path ) {
	$source_path = dark_theme_asset_path( $relative_path );
	if ( ! file_exists( $source_path ) ) {
		return 0;
	}

	$upload_dir = wp_upload_dir();
	$filename = wp_unique_filename( $upload_dir['path'], basename( $source_path ) );
	$destination = trailingslashit( $upload_dir['path'] ) . $filename;
	copy( $source_path, $destination );

	$filetype = wp_check_filetype( $filename );
	$attachment = array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => sanitize_file_name( $filename ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);
	$attachment_id = wp_insert_attachment( $attachment, $destination );

	require_once ABSPATH . 'wp-admin/includes/image.php';
	$attach_data = wp_generate_attachment_metadata( $attachment_id, $destination );
	wp_update_attachment_metadata( $attachment_id, $attach_data );

	return $attachment_id;
}

function dark_theme_get_current_demo_products() {
	$saved = get_option( 'dark_theme_demo_product_ids', array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}
	$by_meta = new WP_Query(
		array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => DARK_THEME_DEMO_META,
					'value' => '1',
				),
			),
		)
	);
	$ids = array_unique( array_merge( $saved, $by_meta->posts ) );
	$ids = array_filter( array_map( 'intval', $ids ) );
	if ( empty( $ids ) ) {
		return array();
	}
	$products = array();
	foreach ( $ids as $id ) {
		if ( get_post_type( $id ) !== 'product' ) {
			continue;
		}
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $id ) : null;
		if ( ! $product ) {
			continue;
		}
		$terms = get_the_terms( $id, 'product_cat' );
		$cat_names = array();
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$cat_names[] = $t->name;
			}
		}
		$products[] = array(
			'id'         => $id,
			'title'      => $product->get_name(),
			'price'      => $product->get_price(),
			'categories' => implode( '، ', $cat_names ),
			'edit_url'   => get_edit_post_link( $id, 'raw' ),
		);
	}
	return $products;
}

function dark_theme_delete_demo_products_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'dark_theme_delete_demo_products', 'dark_theme_delete_demo_products_nonce' );

	$ids = get_option( 'dark_theme_demo_product_ids', array() );
	if ( ! is_array( $ids ) ) {
		$ids = array();
	}
	$deleted = 0;
	foreach ( $ids as $id ) {
		$id = (int) $id;
		if ( $id && get_post_type( $id ) === 'product' ) {
			wp_delete_post( $id, true );
			$deleted++;
		}
	}
	$by_meta = new WP_Query(
		array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => DARK_THEME_DEMO_META,
					'value' => '1',
				),
			),
		)
	);
	foreach ( $by_meta->posts as $id ) {
		wp_delete_post( $id, true );
		$deleted++;
	}
	delete_option( 'dark_theme_demo_product_ids' );

	wp_safe_redirect(
		add_query_arg(
			array(
				'dark_theme_demo_deleted' => '1',
				'deleted_products'        => $deleted,
			),
			admin_url( 'admin.php?page=dark-theme-demo-products' )
		)
	);
	exit;
}
add_action( 'admin_post_dark_theme_delete_demo_products', 'dark_theme_delete_demo_products_handler' );

function dark_theme_create_demo_products_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'dark_theme_create_demo_products', 'dark_theme_create_demo_products_nonce' );

	$products = dark_theme_demo_products_manifest();
	$created_ids = array();
	foreach ( $products as $product_data ) {
		$existing = get_page_by_path( $product_data['slug'], OBJECT, 'product' );
		if ( $existing ) {
			$product_id = $existing->ID;
			wp_update_post(
				array(
					'ID'         => $product_id,
					'post_title' => $product_data['name'],
					'post_status'=> 'publish',
				)
			);
		} else {
			$product_id = wp_insert_post(
				array(
					'post_title'  => $product_data['name'],
					'post_name'   => $product_data['slug'],
					'post_status' => 'publish',
					'post_type'   => 'product',
				)
			);
		}

		if ( $product_id && ! is_wp_error( $product_id ) ) {
			update_post_meta( $product_id, DARK_THEME_DEMO_META, 1 );

			$regular = isset( $product_data['regular_price'] ) ? $product_data['regular_price'] : $product_data['price'];
			$sale    = isset( $product_data['sale_price'] ) ? $product_data['sale_price'] : null;
			update_post_meta( $product_id, '_regular_price', (string) $regular );
			if ( $sale !== null && $sale < $regular ) {
				update_post_meta( $product_id, '_sale_price', (string) $sale );
				update_post_meta( $product_id, '_price', (string) $sale );
			} else {
				update_post_meta( $product_id, '_price', (string) $regular );
			}

			if ( ! empty( $product_data['category_slug'] ) && ! empty( $product_data['category_name'] ) ) {
				$term = get_term_by( 'slug', $product_data['category_slug'], 'product_cat' );
				if ( ! $term || is_wp_error( $term ) ) {
					$ins = wp_insert_term( $product_data['category_name'], 'product_cat', array( 'slug' => $product_data['category_slug'] ) );
					if ( ! is_wp_error( $ins ) ) {
						$term = get_term( $ins['term_id'], 'product_cat' );
					}
				}
				if ( $term && ! is_wp_error( $term ) ) {
					wp_set_object_terms( $product_id, array( (int) $term->term_id ), 'product_cat' );
				}
			}

			if ( ! has_post_thumbnail( $product_id ) ) {
				$attachment_id = dark_theme_import_image_from_theme( $product_data['image'] );
				if ( $attachment_id ) {
					set_post_thumbnail( $product_id, $attachment_id );
				}
			}
			$created_ids[] = $product_id;
		}
	}
	update_option( 'dark_theme_demo_product_ids', array_unique( array_filter( $created_ids ) ) );

	wp_safe_redirect( add_query_arg( 'dark_theme_demo_done', '1', admin_url( 'admin.php?page=dark-theme-demo-products' ) ) );
	exit;
}
add_action( 'admin_post_dark_theme_create_demo_products', 'dark_theme_create_demo_products_handler' );

function dark_theme_order_received_redirect( $url, $order ) {
	$thank_you_page = get_page_by_path( 'thank-you' );
	if ( $thank_you_page ) {
		return add_query_arg(
			array(
				'order' => $order ? $order->get_id() : 0,
				'key'   => $order ? $order->get_order_key() : '',
			),
			get_permalink( $thank_you_page->ID )
		);
	}

	return $url;
}
add_filter( 'woocommerce_get_checkout_order_received_url', 'dark_theme_order_received_redirect', 20, 2 );

