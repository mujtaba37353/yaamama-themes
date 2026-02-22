<?php
/**
 * Stationary Theme Functions
 *
 * @package stationary-theme
 */

define( 'STATIONARY_VERSION', '1.0.0' );
define( 'STATIONARY_DS', 'stationary' );

require_once get_template_directory() . '/inc/stationary-inc.php';

function stationary_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
	add_theme_support( 'html5', array( 'search-form' ) );
}
add_action( 'after_setup_theme', 'stationary_theme_setup' );

add_action( 'init', 'stationary_remove_woo_wrappers', 20 );
function stationary_remove_woo_wrappers() {
	if ( class_exists( 'WooCommerce' ) ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
	}
}

add_filter( 'template_include', 'stationary_template_include', 20 );
function stationary_template_include( $template ) {
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		$tpl = get_template_directory() . '/page-cart.php';
		if ( file_exists( $tpl ) ) return $tpl;
	}
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		$tpl = get_template_directory() . '/page-checkout.php';
		if ( file_exists( $tpl ) ) return $tpl;
	}
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		$tpl = get_template_directory() . '/page-my-account.php';
		if ( file_exists( $tpl ) ) return $tpl;
	}
	$auth_slugs = array( 'login', 'signup', 'forget-password', 'reset-password' );
	foreach ( $auth_slugs as $slug ) {
		if ( is_page( $slug ) ) {
			$tpl = get_template_directory() . '/page-' . $slug . '.php';
			if ( file_exists( $tpl ) ) return $tpl;
			break;
		}
	}
	return $template;
}

add_action( 'template_redirect', 'stationary_redirect_search_to_shop', 5 );
function stationary_redirect_search_to_shop() {
	if ( is_admin() || ! isset( $_GET['s'] ) ) return;
	$s = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	if ( '' === trim( $s ) ) return;
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) return;
	$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	wp_safe_redirect( add_query_arg( 's', $s, $shop_url ) );
	exit;
}

add_action( 'pre_get_posts', 'stationary_shop_filters', 30 );
function stationary_shop_filters( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) return;
	$pt = $query->get( 'post_type' );
	$is_product = ( 'product' === $pt || ( is_array( $pt ) && in_array( 'product', $pt, true ) ) );
	$is_shop = function_exists( 'wc_get_page_id' ) && absint( $query->get( 'page_id' ) ) === wc_get_page_id( 'shop' );
	$is_shop_page = $is_product || $is_shop || $query->is_post_type_archive( 'product' ) || isset( $query->query_vars['product_cat'] );

	$s = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	if ( '' !== trim( $s ) && $is_shop_page ) {
		$query->set( 's', $s );
	}

	if ( isset( $_GET['on_sale'] ) && '1' === $_GET['on_sale'] && $is_shop_page ) {
		$sale_ids = function_exists( 'wc_get_product_ids_on_sale' ) ? wc_get_product_ids_on_sale() : array();
		if ( ! empty( $sale_ids ) ) {
			$query->set( 'post__in', $sale_ids );
		}
	}
}

function stationary_base_uri() {
	return get_template_directory_uri() . '/' . STATIONARY_DS;
}

function stationary_asset_url( $path ) {
	return stationary_base_uri() . '/assets/' . ltrim( $path, '/' );
}

function stationary_logo_url( $type = 'navbar' ) {
	$base = stationary_base_uri() . '/assets';
	$dir  = get_template_directory() . '/' . STATIONARY_DS . '/assets';
	$header_id = (int) ( function_exists( 'stationary_get_option' ) ? stationary_get_option( 'footer_header_logo', 0 ) : 0 );
	$footer_id = (int) ( function_exists( 'stationary_get_option' ) ? stationary_get_option( 'footer_footer_logo', 0 ) : 0 );
	if ( 'navbar' === $type && $header_id ) {
		$url = wp_get_attachment_image_url( $header_id, 'full' );
		if ( $url ) return $url;
	}
	if ( 'footer' === $type && $footer_id ) {
		$url = wp_get_attachment_image_url( $footer_id, 'full' );
		if ( $url ) return $url;
	}
	if ( 'navbar' === $type && file_exists( $dir . '/navbar-icon.png' ) ) return $base . '/navbar-icon.png';
	if ( 'footer' === $type && file_exists( $dir . '/footer-icon.png' ) ) return $base . '/footer-icon.png';
	if ( file_exists( $dir . '/icon.png' ) ) return $base . '/icon.png';
	return $base . '/icon.png';
}

function stationary_shop_permalink() {
	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
}

function stationary_cart_permalink() {
	return function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/cart/' );
}

function stationary_account_permalink() {
	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' );
}

function stationary_enqueue_template_style( $handle, $rel ) {
	$abs = get_template_directory() . '/' . STATIONARY_DS . '/' . ltrim( $rel, '/' );
	if ( file_exists( $abs ) ) {
		wp_enqueue_style( $handle, stationary_base_uri() . '/' . ltrim( $rel, '/' ), array( 'stationary-typography' ), (string) filemtime( $abs ) );
	}
}

function stationary_enqueue_assets() {
	$base = stationary_base_uri();
	$dir = get_template_directory() . '/' . STATIONARY_DS;

	wp_enqueue_style( 'stationary-reset', $base . '/base/reset.css', array(), STATIONARY_VERSION );
	wp_enqueue_style( 'stationary-tokens', $base . '/base/tokens.css', array( 'stationary-reset' ), STATIONARY_VERSION );
	wp_enqueue_style( 'stationary-utilities', $base . '/base/utilities.css', array( 'stationary-tokens' ), STATIONARY_VERSION );
	wp_enqueue_style( 'stationary-typography', $base . '/base/typography.css', array( 'stationary-utilities' ), STATIONARY_VERSION );
	wp_enqueue_style( 'stationary-header', $base . '/components/header.css', array( 'stationary-typography' ), STATIONARY_VERSION );
	wp_enqueue_style( 'stationary-footer', $base . '/components/footer.css', array( 'stationary-typography' ), STATIONARY_VERSION );
	wp_enqueue_style( 'stationary-buttons', $base . '/components/buttons.css', array( 'stationary-typography' ), STATIONARY_VERSION );
	wp_enqueue_style( 'stationary-panner', $base . '/components/panner.css', array( 'stationary-typography' ), STATIONARY_VERSION );
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'stationary-woo-overrides', $base . '/components/woocommerce-overrides.css', array( 'stationary-buttons' ), STATIONARY_VERSION );
	}
	wp_enqueue_style( 'stationary-breadcrumbs', $base . '/components/breadcrumbs.css', array( 'stationary-typography' ), STATIONARY_VERSION );
	wp_enqueue_style( 'stationary-forms', $base . '/components/forms.css', array( 'stationary-typography' ), STATIONARY_VERSION );

	wp_enqueue_style( 'stationary-fonts', 'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700&display=swap', array(), null );
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), '6.4.0' );

	if ( function_exists( 'stationary_get_theme_mod' ) ) {
		$colors = array(
			'header_color'       => '--y-color-primary',
			'footer_color'       => '--y-color-primary',
			'btn_cart_color'     => '--y-color-button',
			'btn_checkout_color' => '--y-color-button',
			'btn_payment_color'  => '--y-color-button',
			'page_bg_color'      => '--y-color-bg',
		);
		$defaults = array(
			'header_color'       => '#6D28D9',
			'footer_color'       => '#6D28D9',
			'btn_cart_color'     => '#FACC15',
			'btn_checkout_color' => '#FACC15',
			'btn_payment_color'  => '#FACC15',
			'page_bg_color'      => '#F3F4F6',
		);
		$css_parts = array();
		foreach ( $colors as $key => $var ) {
			$val = stationary_get_theme_mod( $key, $defaults[ $key ] );
			if ( $val ) {
				$css_parts[] = $var . ':' . $val;
			}
		}
		if ( ! empty( $css_parts ) ) {
			wp_add_inline_style( 'stationary-tokens', ':root{' . implode( ';', $css_parts ) . '}' );
		}
	}

	if ( is_front_page() ) {
		wp_enqueue_style( 'stationary-products', $base . '/components/products.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		wp_enqueue_style( 'stationary-fav-icon', $base . '/components/fav-icon.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		stationary_enqueue_template_style( 'stationary-layout', 'templates/layout/layout.css' );
	}

	if ( is_page( 'about-us' ) ) {
		stationary_enqueue_template_style( 'stationary-about', 'templates/about-us/about-us.css' );
	}

	if ( is_page( 'contact' ) ) {
		wp_enqueue_style( 'stationary-auth', $base . '/components/auth.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		stationary_enqueue_template_style( 'stationary-contact', 'templates/contact/contact.css' );
	}

	$is_shop = function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() );
	if ( $is_shop ) {
		wp_enqueue_style( 'stationary-products', $base . '/components/products.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		wp_enqueue_style( 'stationary-pagination', $base . '/components/pagination.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		wp_enqueue_style( 'stationary-filter', $base . '/components/filter.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		wp_enqueue_style( 'stationary-fav-icon', $base . '/components/fav-icon.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		stationary_enqueue_template_style( 'stationary-store', 'templates/store/store.css' );
		wp_enqueue_script( 'stationary-dropdown', $base . '/js/dropdown.js', array(), STATIONARY_VERSION, true );
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_style( 'stationary-products', $base . '/components/products.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		wp_enqueue_style( 'stationary-quantity', $base . '/components/quantity.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		wp_enqueue_style( 'stationary-payment-details', $base . '/components/payment-details.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		wp_enqueue_style( 'stationary-fav-icon', $base . '/components/fav-icon.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		stationary_enqueue_template_style( 'stationary-product-details', 'templates/product-details/product-details.css' );
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		wp_enqueue_style( 'stationary-quantity', $base . '/components/quantity.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		wp_enqueue_style( 'stationary-total', $base . '/components/total.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		wp_enqueue_style( 'stationary-empty', $base . '/components/empty.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		stationary_enqueue_template_style( 'stationary-cart', 'templates/cart/cart.css' );
		wp_enqueue_script( 'stationary-cart', $base . '/js/cart.js', array(), STATIONARY_VERSION, true );
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		wp_enqueue_style( 'stationary-total', $base . '/components/total.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		wp_enqueue_style( 'stationary-status-popup', $base . '/components/status-popup.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		stationary_enqueue_template_style( 'stationary-payment', 'templates/payment/payment.css' );
	}

	$is_account = function_exists( 'is_account_page' ) && is_account_page();
	$auth_pages  = array( 'login', 'signup', 'forget-password', 'reset-password' );
	if ( $is_account || is_page( $auth_pages ) ) {
		wp_enqueue_style( 'stationary-auth', $base . '/components/auth.css', array( 'stationary-typography' ), STATIONARY_VERSION );
		if ( $is_account ) {
			wp_enqueue_style( 'stationary-products', $base . '/components/products.css', array( 'stationary-typography' ), STATIONARY_VERSION );
			wp_enqueue_style( 'stationary-status-popup', $base . '/components/status-popup.css', array( 'stationary-typography' ), STATIONARY_VERSION );
			wp_enqueue_style( 'stationary-fav-icon', $base . '/components/fav-icon.css', array( 'stationary-typography' ), STATIONARY_VERSION );
			wp_enqueue_style( 'stationary-empty', $base . '/components/empty.css', array( 'stationary-typography' ), STATIONARY_VERSION );
			stationary_enqueue_template_style( 'stationary-profile', 'templates/profile/profile.css' );
		}
		if ( is_page( $auth_pages ) ) {
			wp_enqueue_script( 'stationary-auth-password-toggle', $base . '/js/auth-password-toggle.js', array(), STATIONARY_VERSION, true );
		}
	}

	if ( is_404() ) {
		stationary_enqueue_template_style( 'stationary-404', 'templates/404/404.css' );
	}

	wp_enqueue_script( 'stationary-app', $base . '/js/y-app-init.js', array(), STATIONARY_VERSION, true );
	$favorites = array();
	if ( is_user_logged_in() ) {
		$stored = get_user_meta( get_current_user_id(), '_stationary_favorites', true );
		if ( is_array( $stored ) ) {
			$favorites = array_values( array_map( 'intval', $stored ) );
		}
	}
	wp_localize_script(
		'stationary-app',
		'stationaryFavorites',
		array(
			'isLoggedIn' => is_user_logged_in(),
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'nonce'      => wp_create_nonce( 'stationary_favorites_nonce' ),
			'initialIds' => $favorites,
		)
	);
}
add_action( 'wp_enqueue_scripts', 'stationary_enqueue_assets' );

add_filter( 'woocommerce_add_to_cart_fragments', 'stationary_cart_fragment' );
function stationary_cart_fragment( $fragments ) {
	$count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
	ob_start();
	?>
	<span class="stationary-cart-fragment">
		<a href="<?php echo esc_url( stationary_cart_permalink() ); ?>" class="header-cart-link" aria-label="<?php echo esc_attr( sprintf( _n( '%s منتج في السلة', '%s منتجات في السلة', $count, 'stationary-theme' ), $count ) ); ?>">
			<img src="<?php echo esc_url( stationary_asset_url( 'cart.svg' ) ); ?>" alt="<?php esc_attr_e( 'السلة', 'stationary-theme' ); ?>" />
			<?php if ( $count > 0 ) : ?>
				<span class="cart-count-badge"><?php echo esc_html( $count ); ?></span>
			<?php endif; ?>
		</a>
	</span>
	<?php
	$fragments['.stationary-cart-fragment'] = ob_get_clean();
	return $fragments;
}

add_filter( 'woocommerce_account_menu_items', 'stationary_customize_account_menu', 20 );
function stationary_customize_account_menu( $items ) {
	$updated = array();
	$order   = array( 'edit-account', 'orders', 'downloads', 'edit-address', 'customer-logout' );
	foreach ( $order as $endpoint ) {
		if ( ! isset( $items[ $endpoint ] ) ) {
			continue;
		}
		$label = $items[ $endpoint ];
		if ( 'downloads' === $endpoint ) {
			$label = __( 'المفضلة', 'stationary-theme' );
		}
		if ( 'edit-account' === $endpoint ) {
			$label = __( 'البيانات الشخصية', 'stationary-theme' );
		}
		$updated[ $endpoint ] = $label;
	}
	return ! empty( $updated ) ? $updated : $items;
}

add_filter( 'woocommerce_billing_fields', 'stationary_customize_account_address_fields', 20 );
add_filter( 'woocommerce_shipping_fields', 'stationary_customize_account_address_fields', 20 );
function stationary_customize_account_address_fields( $fields ) {
	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() || ! function_exists( 'is_wc_endpoint_url' ) || ! is_wc_endpoint_url( 'edit-address' ) ) {
		return $fields;
	}

	foreach ( array( 'billing', 'shipping' ) as $prefix ) {
		$first = $prefix . '_first_name';
		$last  = $prefix . '_last_name';
		$phone = $prefix . '_phone';
		$addr2 = $prefix . '_address_2';
		if ( isset( $fields[ $first ] ) ) {
			$fields[ $first ]['label']       = __( 'الاسم الكامل', 'stationary-theme' );
			$fields[ $first ]['placeholder'] = __( 'الاسم الكامل', 'stationary-theme' );
			$fields[ $first ]['class']       = array( 'form-row-wide' );
			$fields[ $first ]['required']    = true;
		}
		if ( isset( $fields[ $last ] ) ) {
			unset( $fields[ $last ] );
		}
		if ( isset( $fields[ $phone ] ) ) {
			$fields[ $phone ]['label']    = __( 'رقم الجوال', 'stationary-theme' );
			$fields[ $phone ]['required'] = false;
			$fields[ $phone ]['class']    = array( 'form-row-wide' );
		}
		if ( isset( $fields[ $addr2 ] ) ) {
			$fields[ $addr2 ]['label']       = __( 'تفاصيل إضافية للعنوان', 'stationary-theme' );
			$fields[ $addr2 ]['placeholder'] = __( 'تفاصيل إضافية للعنوان (اختياري)', 'stationary-theme' );
			$fields[ $addr2 ]['class']       = array( 'form-row-wide' );
		}
	}

	return $fields;
}

add_filter( 'woocommerce_gateway_title', 'stationary_translate_gateway_title', 20, 2 );
function stationary_translate_gateway_title( $title, $gateway_id ) {
	$map = array(
		'bacs'           => 'تحويل بنكي مباشر',
		'cheque'         => 'الدفع بشيك',
		'cod'            => 'الدفع عند الاستلام',
		'paypal'         => 'باي بال',
		'stripe'         => 'بطاقة ائتمانية',
		'stripe_cc'      => 'بطاقة ائتمانية',
		'ppcp-gateway'   => 'باي بال',
	);
	return isset( $map[ $gateway_id ] ) ? $map[ $gateway_id ] : $title;
}

add_filter( 'woocommerce_gateway_description', 'stationary_translate_gateway_description', 20, 2 );
function stationary_translate_gateway_description( $description, $gateway_id ) {
	$map = array(
		'bacs'   => 'قم بالتحويل مباشرة إلى حسابنا البنكي. يرجى استخدام رقم الطلب كمرجع للدفع. لن يتم شحن طلبك حتى يتم تأكيد وصول المبلغ إلى حسابنا.',
		'cheque' => 'يرجى إرسال الشيك إلى عنوان المتجر. لن يتم شحن طلبك حتى يتم تحصيل المبلغ.',
		'cod'    => 'ادفع نقداً عند استلام الطلب.',
		'paypal' => 'ادفع عبر حسابك في باي بال.',
	);
	return isset( $map[ $gateway_id ] ) ? $map[ $gateway_id ] : $description;
}

add_filter( 'woocommerce_checkout_fields', 'stationary_simplify_checkout_fields', 30 );
function stationary_simplify_checkout_fields( $fields ) {
	$remove = array(
		'billing_country',
		'billing_city',
		'billing_state',
		'billing_postcode',
		'billing_company',
		'billing_address_2',
	);
	foreach ( $remove as $key ) {
		if ( isset( $fields['billing'][ $key ] ) ) {
			unset( $fields['billing'][ $key ] );
		}
	}

	if ( isset( $fields['billing']['billing_first_name'] ) ) {
		$fields['billing']['billing_first_name']['label']       = __( 'الاسم الكامل', 'stationary-theme' );
		$fields['billing']['billing_first_name']['placeholder'] = __( 'الاسم الكامل', 'stationary-theme' );
		$fields['billing']['billing_first_name']['class']       = array( 'form-row-wide' );
		$fields['billing']['billing_first_name']['priority']    = 10;
	}
	if ( isset( $fields['billing']['billing_last_name'] ) ) {
		unset( $fields['billing']['billing_last_name'] );
	}
	if ( isset( $fields['billing']['billing_phone'] ) ) {
		$fields['billing']['billing_phone']['label']       = __( 'رقم الجوال', 'stationary-theme' );
		$fields['billing']['billing_phone']['placeholder'] = __( 'رقم الجوال', 'stationary-theme' );
		$fields['billing']['billing_phone']['class']       = array( 'form-row-wide' );
		$fields['billing']['billing_phone']['required']    = true;
		$fields['billing']['billing_phone']['priority']    = 20;
	}
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['label']       = __( 'البريد الإلكتروني', 'stationary-theme' );
		$fields['billing']['billing_email']['placeholder'] = __( 'البريد الإلكتروني', 'stationary-theme' );
		$fields['billing']['billing_email']['class']       = array( 'form-row-wide' );
		$fields['billing']['billing_email']['priority']    = 30;
	}
	if ( isset( $fields['billing']['billing_address_1'] ) ) {
		$fields['billing']['billing_address_1']['label']       = __( 'العنوان', 'stationary-theme' );
		$fields['billing']['billing_address_1']['placeholder'] = __( 'العنوان الكامل', 'stationary-theme' );
		$fields['billing']['billing_address_1']['class']       = array( 'form-row-wide' );
		$fields['billing']['billing_address_1']['priority']    = 40;
	}

	if ( isset( $fields['order']['order_comments'] ) ) {
		$fields['order']['order_comments']['label']       = __( 'معلومات إضافية', 'stationary-theme' );
		$fields['order']['order_comments']['placeholder'] = __( 'ملاحظات حول طلبك، مثل تعليمات التوصيل (اختياري)', 'stationary-theme' );
	}

	return $fields;
}

add_action(
	'init',
	function() {
		remove_action( 'woocommerce_account_downloads_endpoint', 'woocommerce_account_downloads' );
	},
	20
);
add_action( 'woocommerce_account_downloads_endpoint', 'stationary_render_account_favorites' );
function stationary_render_account_favorites() {
	if ( ! is_user_logged_in() ) {
		echo '<p>' . esc_html__( 'يرجى تسجيل الدخول لعرض المفضلة.', 'stationary-theme' ) . '</p>';
		return;
	}

	$favorites = get_user_meta( get_current_user_id(), '_stationary_favorites', true );
	$favorites = is_array( $favorites ) ? array_values( array_filter( array_map( 'intval', $favorites ) ) ) : array();

	echo '<div class="favorites">';
	echo '<h3>' . esc_html__( 'المنتجات المفضلة', 'stationary-theme' ) . '</h3>';
	if ( empty( $favorites ) ) {
		echo '<div class="empty-state-container"><div class="empty-state">';
		echo '<img src="' . esc_url( stationary_asset_url( 'empty-cart.png' ) ) . '" alt="">';
		echo '<h3>' . esc_html__( 'لا توجد منتجات في المفضلة بعد.', 'stationary-theme' ) . '</h3>';
		echo '<a class="btn main-button" href="' . esc_url( stationary_shop_permalink() ) . '">' . esc_html__( 'تسوق الآن', 'stationary-theme' ) . '</a>';
		echo '</div></div>';
		echo '</div>';
		return;
	}

	$products = wc_get_products(
		array(
			'include' => $favorites,
			'limit'   => count( $favorites ),
			'status'  => 'publish',
		)
	);

	echo '<ul class="grid">';
	foreach ( $products as $product ) {
		get_template_part( 'stationary/partials/product-card', null, array( 'product' => $product, 'show_sale' => $product->is_on_sale() ) );
	}
	echo '</ul>';
	echo '</div>';
}

add_action( 'wp_ajax_stationary_toggle_favorite', 'stationary_toggle_favorite' );
function stationary_toggle_favorite() {
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => 'not_logged_in' ), 401 );
	}
	check_ajax_referer( 'stationary_favorites_nonce', 'nonce' );

	$product_id  = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
	$is_favorite = isset( $_POST['is_favorite'] ) && '1' === sanitize_text_field( wp_unslash( $_POST['is_favorite'] ) );
	if ( ! $product_id || 'product' !== get_post_type( $product_id ) ) {
		wp_send_json_error( array( 'message' => 'invalid_product' ), 400 );
	}

	$favorites = get_user_meta( get_current_user_id(), '_stationary_favorites', true );
	$favorites = is_array( $favorites ) ? array_values( array_filter( array_map( 'intval', $favorites ) ) ) : array();
	$key       = array_search( $product_id, $favorites, true );

	if ( $is_favorite && false === $key ) {
		$favorites[] = $product_id;
	}
	if ( ! $is_favorite && false !== $key ) {
		unset( $favorites[ $key ] );
		$favorites = array_values( $favorites );
	}

	update_user_meta( get_current_user_id(), '_stationary_favorites', $favorites );
	wp_send_json_success( array( 'favorites' => $favorites ) );
}

add_action( 'woocommerce_customer_save_address', 'stationary_sync_full_name_to_last_name', 20, 2 );
function stationary_sync_full_name_to_last_name( $user_id, $load_address ) {
	$prefix = ( 'shipping' === $load_address ) ? 'shipping' : 'billing';
	$first  = get_user_meta( $user_id, $prefix . '_first_name', true );
	if ( '' !== trim( (string) $first ) ) {
		update_user_meta( $user_id, $prefix . '_last_name', $first );
	}
}

add_filter( 'gettext', 'stationary_translate_account_strings', 20, 3 );
function stationary_translate_account_strings( $translated, $text, $domain ) {
	if ( 'woocommerce' !== $domain ) {
		return $translated;
	}
	if ( 0 === strpos( $text, 'First name' ) ) return 'الاسم الأول';
	if ( 0 === strpos( $text, 'Last name' ) ) return 'اسم العائلة';
	if ( 0 === strpos( $text, 'Display name' ) ) return 'الاسم المعروض';
	if ( 0 === strpos( $text, 'Email address' ) ) return 'البريد الإلكتروني';
	if ( 0 === strpos( $text, 'Current password' ) ) return 'كلمة المرور الحالية (اتركه فارغا بدون تغيير)';
	if ( 0 === strpos( $text, 'New password' ) ) return 'كلمة المرور الجديدة (اتركه فارغا بدون تغيير)';
	if ( 0 === strpos( $text, 'Confirm new password' ) ) return 'تأكيد كلمة المرور الجديدة';
	if ( 0 === strpos( $text, 'Apartment, suite, unit, etc.' ) ) return 'تفاصيل إضافية للعنوان';
	if ( 0 === strpos( $text, 'Phone (optional)' ) ) return 'رقم الجوال (اختياري)';
	if ( 0 === strpos( $text, 'House number and street name' ) ) return 'اسم الشارع ورقم المبنى';
	if ( 'optional' === trim( $text ) ) return 'اختياري';
	$map = array(
		'First name'                                               => 'الاسم الأول',
		'Last name'                                                => 'اسم العائلة',
		'Display name'                                             => 'الاسم المعروض',
		'Email address'                                            => 'البريد الإلكتروني',
		'Password change'                                          => 'تغيير كلمة المرور',
		'Current password (leave blank to leave unchanged)'        => 'كلمة المرور الحالية (اتركه فارغا بدون تغيير)',
		'New password (leave blank to leave unchanged)'            => 'كلمة المرور الجديدة (اتركه فارغا بدون تغيير)',
		'Confirm new password'                                     => 'تأكيد كلمة المرور الجديدة',
		'Save changes'                                             => 'حفظ التغييرات',
		'No order has been made yet.'                              => 'لم يتم إنشاء أي طلب حتى الآن.',
		'Browse products'                                          => 'تصفح المنتجات',
		'No downloads available yet.'                              => 'لا توجد تنزيلات متاحة حاليا.',
		'Dashboard'                                                => 'لوحة التحكم',
		'Orders'                                                   => 'الطلبات',
		'Addresses'                                                => 'العناوين',
		'Account details'                                          => 'البيانات الشخصية',
		'Downloads'                                                => 'التحميلات',
		'Billing address'                                          => 'عنوان الفوترة',
		'Shipping address'                                         => 'عنوان الشحن',
		'Edit billing address'                                     => 'تعديل عنوان الفوترة',
		'Add shipping address'                                     => 'إضافة عنوان الشحن',
		'The following addresses will be used on the checkout page by default.' => 'العنوان التالي سيستخدم افتراضيا في صفحة الدفع.',
		'You have not set up this type of address yet.'            => 'لم تقم بإعداد هذا النوع من العناوين بعد.',
		'Country / Region'                                         => 'الدولة / المنطقة',
		'Street address'                                           => 'عنوان الشارع',
		'Town / City'                                              => 'المدينة',
		'State / County'                                           => 'المنطقة / المحافظة',
		'Postcode / ZIP'                                           => 'الرمز البريدي',
		'Phone (optional)'                                         => 'رقم الجوال (اختياري)',
		'Save address'                                             => 'حفظ العنوان',
		'Apartment, suite, unit, etc. (optional)'                  => 'تفاصيل إضافية للعنوان',
		'House number and street name'                             => 'اسم الشارع ورقم المبنى',
		'optional'                                                 => 'اختياري',
		'Add to cart'                                              => 'أضف إلى السلة',
		'View cart'                                                => 'عرض السلة',
		'Read more'                                                => 'عرض المنتج',
		'Select options'                                           => 'اختر الخيارات',
		'Place order'                                              => 'إتمام الطلب',
		'Update cart'                                              => 'تحديث السلة',
		'Apply coupon'                                             => 'تطبيق',
		'Coupon code'                                              => 'كود الخصم',
		'Subtotal'                                                 => 'المجموع الفرعي',
		'Total'                                                    => 'الإجمالي',
		'Shipping'                                                 => 'الشحن',
		'Proceed to checkout'                                      => 'إتمام الشراء',
		'Return to shop'                                           => 'العودة للمتجر',
		'Additional information'                                   => 'معلومات إضافية',
		'Order notes'                                              => 'ملاحظات الطلب',
		'Notes about your order, e.g. special notes for delivery.' => 'ملاحظات حول طلبك، مثل تعليمات التوصيل.',
		'Direct bank transfer'                                     => 'تحويل بنكي مباشر',
		'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.' => 'قم بالتحويل مباشرة إلى حسابنا البنكي. يرجى استخدام رقم الطلب كمرجع للدفع. لن يتم شحن طلبك حتى يتم تأكيد وصول المبلغ إلى حسابنا.',
		'Check payments'                                           => 'الدفع بشيك',
		'Cash on delivery'                                         => 'الدفع عند الاستلام',
		'Pay with cash upon delivery.'                             => 'ادفع نقداً عند استلام الطلب.',
		'Billing details'                                          => 'معلومات الفوترة',
		'Billing &amp; Shipping'                                   => 'معلومات الشحن والفوترة',
		'Ship to a different address?'                             => 'الشحن لعنوان مختلف؟',
		'Your order'                                               => 'طلبك',
		'Product'                                                  => 'المنتج',
		'Have a coupon?'                                           => 'لديك كود خصم؟',
		'Click here to enter your code'                            => 'اضغط هنا لإدخال الكود',
	);
	return isset( $map[ $text ] ) ? $map[ $text ] : $translated;
}

add_action( 'template_redirect', 'stationary_probe_account_endpoint', 1 );
function stationary_probe_account_endpoint() {
	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() ) {
		return;
	}
	$endpoint = function_exists( 'WC' ) && WC()->query ? WC()->query->get_current_endpoint() : '';
	$payload  = array(
		'runId'        => 'initial',
		'hypothesisId' => 'A2',
		'location'     => 'functions.php:template_redirect',
		'message'      => 'Account endpoint probe',
		'data'         => array(
			'path'          => isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '',
			'endpoint'      => $endpoint,
			'isLoggedIn'    => is_user_logged_in(),
			'menuItems'     => array_keys( wc_get_account_menu_items() ),
			'menuLabels'    => array_values( wc_get_account_menu_items() ),
		),
		'timestamp'    => round( microtime( true ) * 1000 ),
	);
	@file_put_contents( 'c:\\Users\\mujtaba\\Local Sites\\yamama-platform\\.cursor\\debug.log', wp_json_encode( $payload ) . PHP_EOL, FILE_APPEND );
}

add_action( 'template_redirect', 'stationary_account_default_redirects', 6 );
function stationary_account_default_redirects() {
	if ( ! function_exists( 'is_account_page' ) || ! is_account_page() || ! is_user_logged_in() ) {
		return;
	}
	$current_endpoint = function_exists( 'WC' ) && WC()->query ? WC()->query->get_current_endpoint() : '';
	if ( '' === $current_endpoint ) {
		wp_safe_redirect( wc_get_account_endpoint_url( 'edit-account' ) );
		exit;
	}
	if ( 'edit-address' === $current_endpoint ) {
		$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
		if ( false === strpos( $path, '/edit-address/billing/' ) ) {
			wp_safe_redirect( trailingslashit( wc_get_account_endpoint_url( 'edit-address' ) ) . 'billing/' );
			exit;
		}
	}
}

add_action( 'admin_post_nopriv_stationary_contact', 'stationary_handle_contact' );
add_action( 'admin_post_stationary_contact', 'stationary_handle_contact' );
function stationary_handle_contact() {
	if ( ! isset( $_POST['stationary_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['stationary_contact_nonce'] ) ), 'stationary_contact' ) ) {
		wp_safe_redirect( home_url( '/contact' ) );
		exit;
	}
	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	if ( $name && $email && $message ) {
		$to      = get_option( 'admin_email' );
		$subject = sprintf( __( 'رسالة تواصل من %s', 'stationary-theme' ), $name );
		$body    = sprintf( __( "الاسم: %s\nالبريد: %s\nالهاتف: %s\nالرسالة:\n%s", 'stationary-theme' ), $name, $email, $phone, $message );
		wp_mail( $to, $subject, $body );
	}
	wp_safe_redirect( add_query_arg( 'contact_sent', '1', home_url( '/contact' ) ) );
	exit;
}
