<?php
/**
 * Beauty Care Theme Functions
 *
 * @package beauty-care-theme
 */

define( 'BEAUTY_CARE_VERSION', '1.0.0' );

require_once get_template_directory() . '/inc/admin-loader.php';
require_once get_template_directory() . '/inc/ydash-bridge.php';

add_action( 'init', 'beauty_care_manifest_rewrite' );
function beauty_care_manifest_rewrite() {
	add_rewrite_rule( '^beauty-care-manifest\.webmanifest$', 'index.php?beauty_care_manifest=1', 'top' );
}

add_filter( 'query_vars', function( $vars ) {
	$vars[] = 'beauty_care_manifest';
	return $vars;
} );

add_action( 'template_redirect', function() {
	if ( get_query_var( 'beauty_care_manifest' ) ) {
		$template = get_template_directory() . '/manifest.php';
		if ( file_exists( $template ) ) {
			require $template;
			exit;
		}
	}
} );

function beauty_care_manifest_url() {
	if ( get_option( 'permalink_structure' ) ) {
		return home_url( '/beauty-care-manifest.webmanifest' );
	}
	return add_query_arg( 'beauty_care_manifest', '1', home_url( '/' ) );
}

function beauty_care_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'beauty_care_theme_setup' );

add_action( 'after_switch_theme', function() {
	beauty_care_manifest_rewrite();
	flush_rewrite_rules();
} );

add_filter( 'template_include', 'beauty_care_cart_checkout_templates', 20 );
function beauty_care_cart_checkout_templates( $template ) {
	if ( function_exists( 'is_cart' ) && is_cart() ) {
		$cart_template = get_template_directory() . '/page-cart.php';
		if ( file_exists( $cart_template ) ) {
			return $cart_template;
		}
	}
	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		$checkout_template = get_template_directory() . '/page-checkout.php';
		if ( file_exists( $checkout_template ) ) {
			return $checkout_template;
		}
	}
	if ( function_exists( 'is_account_page' ) && is_account_page() ) {
		$account_template = get_template_directory() . '/page-my-account.php';
		if ( file_exists( $account_template ) ) {
			return $account_template;
		}
	}
	if ( is_page( 'wishlist' ) ) {
		$wishlist_template = get_template_directory() . '/page-wishlist.php';
		if ( file_exists( $wishlist_template ) ) {
			return $wishlist_template;
		}
	}
	if ( is_page( array( 'privacy-policy', 'return-policy', 'shipping-policy' ) ) ) {
		$policies_template = get_template_directory() . '/page-policies.php';
		if ( file_exists( $policies_template ) ) {
			return $policies_template;
		}
	}
	$auth_pages = array( 'login' => 'page-login.php', 'signup' => 'page-signup.php', 'forget-password' => 'page-forget-password.php', 'reset-password' => 'page-reset-password.php' );
	foreach ( $auth_pages as $slug => $tpl ) {
		if ( is_page( $slug ) ) {
			$auth_tpl = get_template_directory() . '/' . $tpl;
			if ( file_exists( $auth_tpl ) ) {
				return $auth_tpl;
			}
			break;
		}
	}
	return $template;
}

/**
 * تحويل أي بحث عام إلى صفحة المتجر مع فلترة المنتجات.
 * يمنع ظهور صفحة نتائج ووردبريس الافتراضية.
 */
add_action( 'template_redirect', 'beauty_care_redirect_search_to_shop', 5 );
function beauty_care_redirect_search_to_shop() {
	if ( is_admin() || ! isset( $_GET['s'] ) ) {
		return;
	}
	$s = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	if ( '' === trim( $s ) ) {
		return;
	}
	// إذا كان المستخدم بالفعل في صفحة المتجر أو تصنيف منتجات، لا نعدّل
	if ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) ) {
		return;
	}
	// تحويل البحث إلى صفحة المتجر
	$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
	$redirect = add_query_arg( 's', $s, $shop_url );
	wp_safe_redirect( $redirect );
	exit;
}

/**
 * فلترة منتجات المتجر حسب كلمة البحث عند وجود باراميتر s.
 */
add_action( 'pre_get_posts', 'beauty_care_shop_search_filter', 30 );
function beauty_care_shop_search_filter( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	$s = isset( $_GET['s'] ) ? sanitize_text_field( wp_unslash( $_GET['s'] ) ) : '';
	if ( '' === trim( $s ) ) {
		return;
	}
	$post_type = $query->get( 'post_type' );
	$is_product_query = ( 'product' === $post_type || ( is_array( $post_type ) && in_array( 'product', $post_type, true ) ) );
	$is_shop_page = function_exists( 'wc_get_page_id' ) && absint( $query->get( 'page_id' ) ) === wc_get_page_id( 'shop' );
	if ( $is_product_query || $is_shop_page || $query->is_post_type_archive( 'product' ) || isset( $query->query_vars['product_cat'] ) || isset( $query->query_vars['product_tag'] ) ) {
		$query->set( 's', $s );
	}
}

add_action( 'init', 'beauty_care_remove_woo_wrappers', 20 );
function beauty_care_remove_woo_wrappers() {
	if ( class_exists( 'WooCommerce' ) ) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
		remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
	}
}

add_filter( 'woocommerce_checkout_fields', 'beauty_care_checkout_fields_simplified', 20 );
function beauty_care_checkout_fields_simplified( $fields ) {
	if ( isset( $fields['billing'] ) ) {
		$billing = &$fields['billing'];

		// الاسم حقل واحد
		unset( $billing['billing_last_name'] );
		$billing['billing_first_name']['label']       = __( 'الاسم', 'beauty-care-theme' );
		$billing['billing_first_name']['placeholder'] = __( 'الاسم الكامل', 'beauty-care-theme' );
		$billing['billing_first_name']['class']       = array( 'form-row-wide' );
		$billing['billing_first_name']['priority']   = 10;

		// البريد الإلكتروني
		$billing['billing_email']['label']       = __( 'البريد الإلكتروني', 'beauty-care-theme' );
		$billing['billing_email']['placeholder'] = __( 'example@email.com', 'beauty-care-theme' );

		// رقم الهاتف — مطلوب
		$billing['billing_phone']['label']       = __( 'رقم الهاتف', 'beauty-care-theme' );
		$billing['billing_phone']['placeholder'] = __( '05xxxxxxxx', 'beauty-care-theme' );
		$billing['billing_phone']['required']    = true;

		// العنوان — حقل واحد
		$billing['billing_address_1']['label']       = __( 'العنوان', 'beauty-care-theme' );
		$billing['billing_address_1']['placeholder'] = __( 'رقم المنزل، اسم الشارع، الحي', 'beauty-care-theme' );
		$billing['billing_address_1']['class']       = array( 'form-row-wide' );
		$billing['billing_address_1']['priority']    = 40;

		// إزالة باقي حقول العنوان
		unset( $billing['billing_company'] );
		unset( $billing['billing_address_2'] );
		unset( $billing['billing_city'] );
		unset( $billing['billing_state'] );
		unset( $billing['billing_postcode'] );
		unset( $billing['billing_country'] );
	}

	// ترجمة حقول الحساب
	if ( isset( $fields['account'] ) ) {
		if ( isset( $fields['account']['account_username'] ) ) {
			$fields['account']['account_username']['label']       = __( 'اسم المستخدم', 'beauty-care-theme' );
			$fields['account']['account_username']['placeholder'] = __( 'اسم المستخدم', 'beauty-care-theme' );
		}
		if ( isset( $fields['account']['account_password'] ) ) {
			$fields['account']['account_password']['label']       = __( 'كلمة المرور', 'beauty-care-theme' );
			$fields['account']['account_password']['placeholder'] = __( 'كلمة المرور', 'beauty-care-theme' );
		}
	}

	// ملاحظات الطلب — حقل الإدخال مع تسمية عربية بدون (optional)
	if ( isset( $fields['order']['order_comments'] ) ) {
		$fields['order']['order_comments']['label']       = __( 'ملاحظات الطلب', 'beauty-care-theme' );
		$fields['order']['order_comments']['placeholder'] = __( 'أي ملاحظات إضافية تتعلق بالطلب...', 'beauty-care-theme' );
	}

	return $fields;
}

add_filter( 'woocommerce_checkout_required_field_notice', 'beauty_care_checkout_required_notice_arabic', 10, 3 );
function beauty_care_checkout_required_notice_arabic( $notice, $field_label, $key ) {
	$messages = array(
		'billing_first_name'  => __( '<strong>الاسم</strong> حقل مطلوب.', 'beauty-care-theme' ),
		'billing_email'      => __( '<strong>البريد الإلكتروني</strong> حقل مطلوب.', 'beauty-care-theme' ),
		'billing_phone'      => __( '<strong>رقم الهاتف</strong> حقل مطلوب.', 'beauty-care-theme' ),
		'billing_address_1'  => __( '<strong>العنوان</strong> حقل مطلوب.', 'beauty-care-theme' ),
		'account_password'   => __( '<strong>كلمة المرور</strong> حقل مطلوب.', 'beauty-care-theme' ),
	);
	return isset( $messages[ $key ] ) ? $messages[ $key ] : sprintf( __( '%s حقل مطلوب.', 'beauty-care-theme' ), '<strong>' . esc_html( $field_label ) . '</strong>' );
}


add_filter( 'woocommerce_default_address_fields', 'beauty_care_address_fields_arabic' );
function beauty_care_address_fields_arabic( $fields ) {
	$translations = array(
		'first_name' => __( 'الاسم الأول', 'beauty-care-theme' ),
		'last_name'  => __( 'الاسم الأخير', 'beauty-care-theme' ),
		'company'    => __( 'اسم الشركة', 'beauty-care-theme' ),
		'country'    => __( 'الدولة / المنطقة', 'beauty-care-theme' ),
		'address_1'  => __( 'عنوان الشارع', 'beauty-care-theme' ),
		'address_2'  => __( 'الشقة، الوحدة، إلخ. (اختياري)', 'beauty-care-theme' ),
		'city'       => __( 'المدينة', 'beauty-care-theme' ),
		'state'      => __( 'المنطقة / المحافظة', 'beauty-care-theme' ),
		'postcode'   => __( 'الرمز البريدي', 'beauty-care-theme' ),
	);
	foreach ( $translations as $key => $label ) {
		if ( isset( $fields[ $key ] ) ) {
			$fields[ $key ]['label'] = $label;
			if ( 'address_2' === $key ) {
				$fields[ $key ]['placeholder'] = $label;
			}
		}
	}
	return $fields;
}

add_filter( 'woocommerce_billing_fields', 'beauty_care_billing_fields_arabic' );
function beauty_care_billing_fields_arabic( $fields ) {
	if ( isset( $fields['billing_phone'] ) ) {
		$fields['billing_phone']['label'] = __( 'رقم الهاتف (اختياري)', 'beauty-care-theme' );
	}
	if ( isset( $fields['billing_email'] ) ) {
		$fields['billing_email']['label'] = __( 'البريد الإلكتروني', 'beauty-care-theme' );
	}
	return $fields;
}

add_filter( 'gettext', 'beauty_care_translate_woocommerce_strings', 10, 3 );
function beauty_care_translate_woocommerce_strings( $translated, $text, $domain ) {
	if ( 'woocommerce' !== $domain ) {
		return $translated;
	}
	$strings = array(
		'Additional information' => __( 'معلومات إضافية', 'beauty-care-theme' ),
		'Billing details'        => __( 'تفاصيل الفاتورة', 'beauty-care-theme' ),
		'Product'                => __( 'المنتج', 'beauty-care-theme' ),
		'Subtotal'               => __( 'المجموع الفرعي', 'beauty-care-theme' ),
		'Total'                  => __( 'الإجمالي', 'beauty-care-theme' ),
		'Your order'             => __( 'طلبك', 'beauty-care-theme' ),
		/* Auth notices */
		'Error:'                 => __( 'خطأ:', 'beauty-care-theme' ),
		'Your account was created successfully and a password has been sent to your email address.' => __( 'تم إنشاء حسابك بنجاح. تم إرسال كلمة المرور إلى بريدك الإلكتروني.', 'beauty-care-theme' ),
		'Your account was created successfully. Your login details have been sent to your email address.' => __( 'تم إنشاء حسابك بنجاح. تم إرسال بيانات الدخول إلى بريدك الإلكتروني.', 'beauty-care-theme' ),
		'Your password has been reset successfully.' => __( 'تم إعادة تعيين كلمة المرور بنجاح.', 'beauty-care-theme' ),
		'Password reset link has been sent.' => __( 'تم إرسال رابط إعادة تعيين كلمة المرور.', 'beauty-care-theme' ),
		'Invalid username or email.' => __( 'البريد الإلكتروني غير صحيح.', 'beauty-care-theme' ),
		'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.' => __( 'تم إرسال بريد إعادة تعيين كلمة المرور إلى بريدك الإلكتروني، قد يستغرق عدة دقائق للظهور. يرجى الانتظار 10 دقائق على الأقل قبل المحاولة مرة أخرى.', 'beauty-care-theme' ),
		'Are you sure you want to log out?' => __( 'هل أنت متأكد من تسجيل الخروج؟', 'beauty-care-theme' ),
		'Confirm and log out'   => __( 'تأكيد وتسجيل الخروج', 'beauty-care-theme' ),
		'Username or email'      => __( 'البريد الإلكتروني', 'beauty-care-theme' ),
		'Password'               => __( 'كلمة المرور', 'beauty-care-theme' ),
		'Password reset email has been sent.' => __( 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.', 'beauty-care-theme' ),
		'Enter a username or email address.' => __( 'أدخل البريد الإلكتروني.', 'beauty-care-theme' ),
		'Please enter your password.' => __( 'الرجاء إدخال كلمة المرور.', 'beauty-care-theme' ),
		'Passwords do not match.' => __( 'كلمتا المرور غير متطابقتين.', 'beauty-care-theme' ),
	);
	return isset( $strings[ $text ] ) ? $strings[ $text ] : $translated;
}

add_filter( 'woocommerce_order_button_text', function() {
	return __( 'ادفع', 'beauty-care-theme' );
} );

add_filter( 'woocommerce_gateway_title', 'beauty_care_payment_gateway_title_arabic', 10, 2 );
function beauty_care_payment_gateway_title_arabic( $title, $gateway_id ) {
	$translations = array(
		'bacs'   => __( 'تحويل بنكي مباشر', 'beauty-care-theme' ),
		'cod'    => __( 'الدفع عند الاستلام', 'beauty-care-theme' ),
		'cheque' => __( 'شيك', 'beauty-care-theme' ),
		'paypal' => __( 'باي بال', 'beauty-care-theme' ),
	);
	return isset( $translations[ $gateway_id ] ) ? $translations[ $gateway_id ] : $title;
}

add_filter( 'woocommerce_gateway_description', 'beauty_care_payment_gateway_description_arabic', 10, 2 );
function beauty_care_payment_gateway_description_arabic( $description, $gateway_id ) {
	$translations = array(
		'bacs'   => __( 'قم بالتحويل المباشر إلى حسابنا البنكي. يرجى استخدام رقم الطلب كمرجع للدفعة. لن يتم شحن طلبك حتى يتم استلام المبلغ في حسابنا.', 'beauty-care-theme' ),
		'cod'    => __( 'ادفع نقداً عند الاستلام.', 'beauty-care-theme' ),
		'cheque' => __( 'يرجى إرسال الشيك إلى عنوان المتجر.', 'beauty-care-theme' ),
		'paypal' => __( 'ادفع بشكل آمن باستخدام حسابك في باي بال.', 'beauty-care-theme' ),
	);
	return isset( $translations[ $gateway_id ] ) ? $translations[ $gateway_id ] : $description;
}

add_filter( 'woocommerce_checkout_posted_data', 'beauty_care_checkout_default_address', 10 );
function beauty_care_checkout_default_address( $data ) {
	if ( empty( $data['billing_country'] ) ) {
		$data['billing_country'] = 'SA';
	}
	if ( empty( $data['billing_address_1'] ) ) {
		$data['billing_address_1'] = '-';
	}
	if ( empty( $data['billing_city'] ) ) {
		$data['billing_city'] = '-';
	}
	if ( empty( $data['billing_state'] ) ) {
		$data['billing_state'] = '';
	}
	if ( empty( $data['billing_postcode'] ) ) {
		$data['billing_postcode'] = '';
	}
	if ( empty( $data['billing_last_name'] ) ) {
		$data['billing_last_name'] = '';
	}
	return $data;
}

function beauty_care_base_uri() {
	return get_template_directory_uri() . '/beauty-care';
}

function beauty_care_enqueue_template_style( $handle, $relative_path ) {
	$absolute_path = get_template_directory() . '/beauty-care/' . ltrim( $relative_path, '/' );
	if ( file_exists( $absolute_path ) ) {
		$version = (string) filemtime( $absolute_path );
		wp_enqueue_style(
			$handle,
			beauty_care_base_uri() . '/' . ltrim( $relative_path, '/' ),
			array( 'beauty-care-typography' ),
			$version
		);
	}
}

function beauty_care_enqueue_assets() {
	$base_uri = beauty_care_base_uri();
	$reset_path = get_theme_file_path( 'beauty-care/base/reset.css' );
	$reset_version = file_exists( $reset_path ) ? (string) filemtime( $reset_path ) : BEAUTY_CARE_VERSION;

	wp_enqueue_style( 'beauty-care-reset', $base_uri . '/base/reset.css', array(), $reset_version );
	wp_enqueue_style( 'beauty-care-tokens', $base_uri . '/base/tokens.css', array( 'beauty-care-reset' ), BEAUTY_CARE_VERSION );
	wp_enqueue_style( 'beauty-care-utilities', $base_uri . '/base/utilities.css', array( 'beauty-care-tokens' ), BEAUTY_CARE_VERSION );
	wp_enqueue_style( 'beauty-care-typography', $base_uri . '/base/typography.css', array( 'beauty-care-utilities' ), BEAUTY_CARE_VERSION );

	wp_enqueue_style( 'beauty-care-header', $base_uri . '/components/header.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
	wp_enqueue_style( 'beauty-care-footer', $base_uri . '/components/footer.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
	wp_enqueue_style( 'beauty-care-buttons', $base_uri . '/components/buttons.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
	if ( class_exists( 'WooCommerce' ) ) {
		wp_enqueue_style( 'beauty-care-woo-overrides', $base_uri . '/components/woocommerce-overrides.css', array( 'beauty-care-buttons' ), BEAUTY_CARE_VERSION );
	}

	wp_enqueue_style(
		'beauty-care-fonts',
		'https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'beauty-care-font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
		array(),
		'6.4.0'
	);

	if ( is_front_page() ) {
		wp_enqueue_style( 'beauty-care-products', $base_uri . '/components/products.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-panner', $base_uri . '/components/panner.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-hero', $base_uri . '/components/hero.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-fav-icon', $base_uri . '/components/fav-icon.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		beauty_care_enqueue_template_style( 'beauty-care-about', 'templates/about-us/about-us.css' );
		beauty_care_enqueue_template_style( 'beauty-care-layout', 'templates/layout/layout.css' );
	}

	if ( is_page( 'about-us' ) ) {
		wp_enqueue_style( 'beauty-care-panner', $base_uri . '/components/panner.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-breadcrumbs', $base_uri . '/components/breadcrumbs.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		beauty_care_enqueue_template_style( 'beauty-care-about', 'templates/about-us/about-us.css' );
		beauty_care_enqueue_template_style( 'beauty-care-layout', 'templates/layout/layout.css' );
	}

	if ( is_page( 'contact' ) ) {
		wp_enqueue_style( 'beauty-care-panner', $base_uri . '/components/panner.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-breadcrumbs', $base_uri . '/components/breadcrumbs.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-auth', $base_uri . '/components/auth.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-forms', $base_uri . '/components/forms.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		beauty_care_enqueue_template_style( 'beauty-care-contact', 'templates/contact/contact.css' );
		beauty_care_enqueue_template_style( 'beauty-care-layout', 'templates/layout/layout.css' );
	}

	$is_shop_or_category = ( is_page( 'shop' ) || ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_category' ) && is_product_category() ) );
	$is_search = is_search();
	if ( $is_shop_or_category || $is_search ) {
		wp_enqueue_style( 'beauty-care-products', $base_uri . '/components/products.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-panner', $base_uri . '/components/panner.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-breadcrumbs', $base_uri . '/components/breadcrumbs.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-empty', $base_uri . '/components/empty.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-fav-icon', $base_uri . '/components/fav-icon.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		beauty_care_enqueue_template_style( 'beauty-care-store', 'templates/store/store.css' );
		beauty_care_enqueue_template_style( 'beauty-care-layout', 'templates/layout/layout.css' );
		if ( $is_shop_or_category ) {
			wp_enqueue_style( 'beauty-care-filter', $base_uri . '/components/filter.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
			wp_enqueue_script( 'beauty-care-dropdown', $base_uri . '/js/dropdown.js', array(), BEAUTY_CARE_VERSION, true );
			wp_enqueue_script( 'beauty-care-store-filter', $base_uri . '/js/store-filter.js', array(), BEAUTY_CARE_VERSION, true );
			wp_localize_script( 'beauty-care-store-filter', 'wc_beauty_care', array(
				'shop_url' => beauty_care_shop_permalink(),
			) );
		}
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_style( 'beauty-care-products', $base_uri . '/components/products.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-breadcrumbs', $base_uri . '/components/breadcrumbs.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-quantity', $base_uri . '/components/quantity.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-payment-details', $base_uri . '/components/payment-details.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-fav-icon', $base_uri . '/components/fav-icon.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		beauty_care_enqueue_template_style( 'beauty-care-product-details', 'templates/product-details/product-details.css' );
	}

	if ( function_exists( 'is_cart' ) && is_cart() ) {
		wp_enqueue_style( 'beauty-care-panner', $base_uri . '/components/panner.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-breadcrumbs', $base_uri . '/components/breadcrumbs.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-quantity', $base_uri . '/components/quantity.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-products-table', $base_uri . '/components/products-table.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-total', $base_uri . '/components/total.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-empty', $base_uri . '/components/empty.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		beauty_care_enqueue_template_style( 'beauty-care-cart', 'templates/cart/cart.css' );
		beauty_care_enqueue_template_style( 'beauty-care-layout', 'templates/layout/layout.css' );
		wp_enqueue_script( 'beauty-care-cart-quantity', $base_uri . '/js/cart-quantity.js', array(), BEAUTY_CARE_VERSION, true );
	}

	$is_account_page = function_exists( 'is_account_page' ) && is_account_page();
	if ( $is_account_page || is_page( array( 'login', 'signup', 'forget-password', 'reset-password' ) ) ) {
		wp_enqueue_style( 'beauty-care-panner', $base_uri . '/components/panner.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-breadcrumbs', $base_uri . '/components/breadcrumbs.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-auth', $base_uri . '/components/auth.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-alerts', $base_uri . '/components/alerts.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-forms', $base_uri . '/components/forms.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		if ( $is_account_page ) {
			wp_enqueue_style( 'beauty-care-buttons', $base_uri . '/components/buttons.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
			wp_enqueue_style( 'beauty-care-status-popup', $base_uri . '/components/status-popup.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
			wp_enqueue_style( 'beauty-care-products', $base_uri . '/components/products.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
			wp_enqueue_style( 'beauty-care-fav-icon', $base_uri . '/components/fav-icon.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
			wp_enqueue_style( 'beauty-care-empty', $base_uri . '/components/empty.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
			beauty_care_enqueue_template_style( 'beauty-care-profile', 'templates/profile/profile.css' );
		}
		beauty_care_enqueue_template_style( 'beauty-care-layout', 'templates/layout/layout.css' );
		if ( is_page( array( 'login', 'signup', 'forget-password' ) ) ) {
			wp_enqueue_script( 'just-validate', 'https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js', array(), null, true );
			wp_enqueue_script( 'beauty-care-validation', $base_uri . '/js/validation.js', array( 'just-validate' ), BEAUTY_CARE_VERSION, true );
		}
	}

	if ( function_exists( 'is_checkout' ) && is_checkout() ) {
		wp_enqueue_style( 'beauty-care-panner', $base_uri . '/components/panner.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-breadcrumbs', $base_uri . '/components/breadcrumbs.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-forms', $base_uri . '/components/forms.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-status-popup', $base_uri . '/components/status-popup.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-products-table', $base_uri . '/components/products-table.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-total', $base_uri . '/components/total.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-payment-details', $base_uri . '/components/payment-details.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		beauty_care_enqueue_template_style( 'beauty-care-payment', 'templates/payment/payment.css' );
		beauty_care_enqueue_template_style( 'beauty-care-layout', 'templates/layout/layout.css' );
	}

	if ( is_page( 'wishlist' ) ) {
		wp_enqueue_style( 'beauty-care-products', $base_uri . '/components/products.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-panner', $base_uri . '/components/panner.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-breadcrumbs', $base_uri . '/components/breadcrumbs.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-empty', $base_uri . '/components/empty.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-fav-icon', $base_uri . '/components/fav-icon.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		beauty_care_enqueue_template_style( 'beauty-care-wishlist', 'templates/wishlist/wishlist.css' );
		beauty_care_enqueue_template_style( 'beauty-care-layout', 'templates/layout/layout.css' );
	}
	$has_wishlist_toggle = is_front_page()
		|| ( function_exists( 'is_shop' ) && ( is_shop() || is_product_taxonomy() ) )
		|| ( function_exists( 'is_product' ) && is_product() )
		|| is_page( 'wishlist' );
	if ( $has_wishlist_toggle ) {
		wp_enqueue_script( 'beauty-care-wishlist', $base_uri . '/js/wishlist.js', array(), BEAUTY_CARE_VERSION, true );
		wp_localize_script( 'beauty-care-wishlist', 'beautyCareWishlist', array(
			'wishlistIds' => array_map( 'intval', function_exists( 'beauty_care_get_wishlist_ids' ) ? beauty_care_get_wishlist_ids() : array() ),
			'shopUrl'     => beauty_care_shop_permalink(),
			'assetUri'    => get_template_directory_uri() . '/beauty-care/assets',
		) );
	}
	if ( is_page( array( 'privacy-policy', 'return-policy', 'shipping-policy' ) ) ) {
		wp_enqueue_style( 'beauty-care-panner', $base_uri . '/components/panner.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		wp_enqueue_style( 'beauty-care-breadcrumbs', $base_uri . '/components/breadcrumbs.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		beauty_care_enqueue_template_style( 'beauty-care-policies', 'templates/policies/policies.css' );
		beauty_care_enqueue_template_style( 'beauty-care-layout', 'templates/layout/layout.css' );
	}

	if ( is_404() ) {
		wp_enqueue_style( 'beauty-care-cards', $base_uri . '/components/cards.css', array( 'beauty-care-typography' ), BEAUTY_CARE_VERSION );
		beauty_care_enqueue_template_style( 'beauty-care-404', 'templates/404/404.css' );
		beauty_care_enqueue_template_style( 'beauty-care-layout', 'templates/layout/layout.css' );
	}

	wp_enqueue_script(
		'beauty-care-app',
		$base_uri . '/js/y-app-init.js',
		array(),
		BEAUTY_CARE_VERSION,
		array( 'in_footer' => true )
	);
}
add_action( 'wp_enqueue_scripts', 'beauty_care_enqueue_assets' );

function beauty_care_shop_permalink() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		return wc_get_page_permalink( 'shop' );
	}
	return home_url( '/shop' );
}

function beauty_care_cart_permalink() {
	if ( function_exists( 'wc_get_cart_url' ) ) {
		return wc_get_cart_url();
	}
	return home_url( '/cart' );
}

/**
 * تحديث عداد السلة في الهيدر عبر AJAX عند إضافة منتج.
 */
function beauty_care_cart_count_fragment( $fragments ) {
	$cart_count = 0;
	if ( function_exists( 'WC' ) && WC()->cart ) {
		$cart_count = WC()->cart->get_cart_contents_count();
	}
	$assets_uri = get_template_directory_uri() . '/beauty-care/assets';
	ob_start();
	?>
	<span class="beauty-care-cart-fragment">
		<a href="<?php echo esc_url( beauty_care_cart_permalink() ); ?>" class="header-cart-link" aria-label="<?php echo esc_attr( sprintf( _n( '%s منتج في السلة', '%s منتجات في السلة', $cart_count, 'beauty-care-theme' ), $cart_count ) ); ?>">
			<img src="<?php echo esc_url( $assets_uri . '/cart.svg' ); ?>" alt="<?php esc_attr_e( 'السلة', 'beauty-care-theme' ); ?>" />
			<?php if ( $cart_count > 0 ) : ?>
				<span class="cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
			<?php endif; ?>
		</a>
	</span>
	<?php
	$fragments['.beauty-care-cart-fragment'] = ob_get_clean();
	return $fragments;
}
if ( function_exists( 'WC' ) ) {
	add_filter( 'woocommerce_add_to_cart_fragments', 'beauty_care_cart_count_fragment' );
}

function beauty_care_account_permalink() {
	if ( function_exists( 'wc_get_page_permalink' ) ) {
		return wc_get_page_permalink( 'myaccount' );
	}
	return home_url( '/my-account' );
}

add_action( 'admin_post_nopriv_beauty_care_contact', 'beauty_care_handle_contact' );
add_action( 'admin_post_beauty_care_contact', 'beauty_care_handle_contact' );

add_filter( 'woocommerce_add_to_cart_redirect', 'beauty_care_buy_now_redirect', 10, 2 );
function beauty_care_buy_now_redirect( $url, $adding_to_cart ) {
	if ( ! empty( $_POST['beauty_care_buy_now'] ) && function_exists( 'wc_get_checkout_url' ) ) {
		return wc_get_checkout_url();
	}
	return $url;
}

add_filter( 'lostpassword_url', 'beauty_care_lostpassword_url', 20 );
function beauty_care_lostpassword_url( $url ) {
	return home_url( '/forget-password' );
}

add_filter( 'woocommerce_lost_password_confirmation_message', 'beauty_care_lost_password_confirmation_ar' );
function beauty_care_lost_password_confirmation_ar() {
	return __( 'تم إرسال بريد إعادة تعيين كلمة المرور إلى بريدك الإلكتروني، قد يستغرق عدة دقائق للظهور. يرجى الانتظار 10 دقائق على الأقل قبل المحاولة مرة أخرى.', 'beauty-care-theme' );
}

add_filter( 'retrieve_password_message', 'beauty_care_retrieve_password_message', 10, 4 );
function beauty_care_retrieve_password_message( $message, $key, $user_login, $user_data ) {
	$reset_url = add_query_arg(
		array(
			'key'   => $key,
			'login' => rawurlencode( $user_login ),
		),
		home_url( '/reset-password' )
	);
	$message = sprintf(
		/* translators: 1: blog name, 2: reset URL, 3: user login */
		__( 'طلب إعادة تعيين كلمة المرور لـ %1$s.

قام شخص ما بطلب إعادة تعيين كلمة المرور للحساب المرتبط بالبريد: %3$s

إذا كان هذا خطأ، تجاهل هذا البريد ولن يحدث شيء.

لإعادة تعيين كلمة المرور، زر الرابط التالي:
%2$s', 'beauty-care-theme' ),
		get_bloginfo( 'name' ),
		$reset_url,
		$user_login
	);
	return $message;
}

add_filter( 'woocommerce_registration_auth_new_customer', '__return_true' );
add_filter( 'woocommerce_registration_redirect', 'beauty_care_registration_redirect', 10, 1 );
function beauty_care_registration_redirect( $redirect ) {
	return function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account' );
}

add_action( 'woocommerce_created_customer', 'beauty_care_save_billing_phone_on_register' );
function beauty_care_save_billing_phone_on_register( $customer_id ) {
	if ( ! empty( $_POST['billing_phone'] ) ) {
		update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) );
	}
}

add_filter( 'woocommerce_process_registration_errors', 'beauty_care_validate_register_password_match' );
function beauty_care_validate_register_password_match( $errors ) {
	if ( ! empty( $_POST['password'] ) && ! empty( $_POST['password2'] ) && $_POST['password'] !== $_POST['password2'] ) {
		$errors->add( 'password_mismatch', __( 'كلمة المرور غير متطابقة.', 'beauty-care-theme' ) );
	}
	return $errors;
}

add_filter( 'woocommerce_account_menu_items', 'beauty_care_account_menu_items' );
function beauty_care_account_menu_items( $items ) {
	unset( $items['downloads'] );
	unset( $items['payment-methods'] );
	return array(
		'dashboard'       => __( 'البيانات الشخصية', 'beauty-care-theme' ),
		'edit-address'    => __( 'عنواني', 'beauty-care-theme' ),
		'orders'          => __( 'الطلبات', 'beauty-care-theme' ),
		'customer-logout' => __( 'تسجيل الخروج', 'beauty-care-theme' ),
	);
}

add_action( 'woocommerce_edit_account_form_fields', 'beauty_care_edit_account_phone_field' );
function beauty_care_edit_account_phone_field() {
	$user  = wp_get_current_user();
	$phone = get_user_meta( $user->ID, 'billing_phone', true );
	?>
	<div class="form-row">
		<div class="form-group">
			<label for="billing_phone"><?php esc_html_e( 'الهاتف', 'beauty-care-theme' ); ?></label>
			<input type="tel" name="billing_phone" id="billing_phone" value="<?php echo esc_attr( $phone ); ?>" pattern="^05\d{8}$" dir="ltr" />
		</div>
	</div>
	<?php
}

add_action( 'woocommerce_save_account_details', 'beauty_care_save_account_phone', 10, 1 );
function beauty_care_save_account_phone( $user_id ) {
	if ( isset( $_POST['billing_phone'] ) ) {
		update_user_meta( $user_id, 'billing_phone', sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) );
	}
}

add_filter( 'woocommerce_logout_default_redirect_url', 'beauty_care_logout_redirect' );
function beauty_care_logout_redirect() {
	return home_url( '/login' );
}

add_action( 'woocommerce_customer_save_address', 'beauty_care_set_address_saved_flag', 10, 2 );
function beauty_care_set_address_saved_flag( $user_id, $address_type ) {
	set_transient( 'beauty_care_address_saved_' . $user_id, 1, 30 );
}

add_action( 'woocommerce_account_navigation', 'beauty_care_address_saved_popup', 5 );
function beauty_care_address_saved_popup() {
	$user_id = get_current_user_id();
	if ( ! $user_id || ! get_transient( 'beauty_care_address_saved_' . $user_id ) ) {
		return;
	}
	delete_transient( 'beauty_care_address_saved_' . $user_id );
	?>
	<input type="checkbox" id="address-saved-popup" class="status-popup-toggle" checked>
	<div class="status-popup-overlay">
		<label for="address-saved-popup" class="status-popup-overlay-close"></label>
		<div class="status-popup-card">
			<div class="status-popup-icon">
				<svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
			</div>
			<p class="status-popup-text"><?php esc_html_e( 'تم حفظ العنوان', 'beauty-care-theme' ); ?></p>
		</div>
	</div>
	<?php
}

add_filter( 'body_class', 'beauty_care_order_success_body_class' );
function beauty_care_order_success_body_class( $classes ) {
	global $wp;
	if ( function_exists( 'is_order_received_page' ) && is_order_received_page() && ! empty( $wp->query_vars['order-received'] ) ) {
		$order_id = absint( $wp->query_vars['order-received'] );
		if ( $order_id ) {
			$order = wc_get_order( $order_id );
			if ( $order && ! $order->has_status( 'failed' ) ) {
				$classes[] = 'order-success-modal-only';
			}
		}
	}
	return $classes;
}

add_action( 'woocommerce_before_thankyou', 'beauty_care_order_success_popup' );
function beauty_care_order_success_popup( $order_id ) {
	if ( ! $order_id ) {
		return;
	}
	$order = wc_get_order( $order_id );
	if ( ! $order || $order->has_status( 'failed' ) ) {
		return;
	}
	?>
	<div class="order-success-modal-wrapper">
		<input type="checkbox" id="payment-success-popup" class="status-popup-toggle" checked>
		<div class="status-popup-overlay">
			<label for="payment-success-popup" class="status-popup-overlay-close"></label>
			<div class="status-popup-card">
				<div class="status-popup-icon">
					<svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
				</div>
				<p class="status-popup-text"><?php esc_html_e( 'تم تسجيل طلبك بنجاح', 'beauty-care-theme' ); ?></p>
				<div class="status-popup-actions">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="status-popup-btn status-popup-btn--primary"><?php esc_html_e( 'اذهب الى الرئيسية', 'beauty-care-theme' ); ?></a>
					<a href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account/' ) ); ?>" class="status-popup-btn status-popup-btn--outline"><?php esc_html_e( 'اذهب الى حسابي', 'beauty-care-theme' ); ?></a>
				</div>
			</div>
		</div>
	</div>
	<?php
}

add_action( 'after_switch_theme', 'beauty_care_ensure_woo_registration_options' );
function beauty_care_ensure_woo_registration_options() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	if ( 'yes' !== get_option( 'woocommerce_enable_myaccount_registration' ) ) {
		update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
	}
	if ( 'no' !== get_option( 'woocommerce_registration_generate_password' ) ) {
		update_option( 'woocommerce_registration_generate_password', 'no' );
	}
}

function beauty_care_handle_contact() {
	if ( ! isset( $_POST['beauty_care_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['beauty_care_contact_nonce'] ) ), 'beauty_care_contact' ) ) {
		wp_safe_redirect( home_url( '/contact' ) );
		exit;
	}
	$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
	$phone   = isset( $_POST['number'] ) ? sanitize_text_field( wp_unslash( $_POST['number'] ) ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';
	$contact_settings = function_exists( 'beauty_care_get_contact_settings' ) ? beauty_care_get_contact_settings() : array();
	$to              = ! empty( $contact_settings['recipient_email'] ) ? $contact_settings['recipient_email'] : get_option( 'admin_email' );
	$subject = sprintf( '[%s] %s', get_bloginfo( 'name' ), __( 'رسالة تواصل جديد', 'beauty-care-theme' ) );
	$body    = sprintf(
		"%s: %s\n%s: %s\n%s: %s\n\n%s",
		__( 'الاسم', 'beauty-care-theme' ),
		$name,
		__( 'البريد', 'beauty-care-theme' ),
		$email,
		__( 'الهاتف', 'beauty-care-theme' ),
		$phone,
		$message
	);
	if ( function_exists( 'beauty_care_send_mail_with_config' ) ) {
		beauty_care_send_mail_with_config( $to, $subject, $body );
	} else {
		wp_mail( $to, $subject, $body );
	}
	wp_safe_redirect( add_query_arg( 'contact_sent', '1', home_url( '/contact' ) ) );
	exit;
}
