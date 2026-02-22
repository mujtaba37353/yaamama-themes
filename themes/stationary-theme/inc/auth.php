<?php
/**
 * Auth & My Account — Design overrides and flow.
 *
 * @package stationary-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'after_switch_theme', 'stationary_ensure_auth_pages' );
function stationary_ensure_auth_pages() {
	$slugs = array( 'login', 'signup', 'forget-password', 'reset-password' );
	$titles = array(
		'login'           => 'تسجيل الدخول',
		'signup'          => 'إنشاء حساب',
		'forget-password' => 'نسيت كلمة المرور',
		'reset-password'  => 'إعادة تعيين كلمة المرور',
	);
	foreach ( $slugs as $slug ) {
		$page = get_page_by_path( $slug );
		if ( ! $page ) {
			wp_insert_post( array(
				'post_title'   => $titles[ $slug ],
				'post_name'    => $slug,
				'post_content' => '',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			) );
		}
	}
	if ( class_exists( 'WooCommerce' ) ) {
		update_option( 'woocommerce_enable_myaccount_registration', 'yes' );
		update_option( 'woocommerce_registration_generate_password', 'no' );
	}
}

add_filter( 'lostpassword_url', 'stationary_lostpassword_url', 20 );
function stationary_lostpassword_url( $url ) {
	return home_url( '/forget-password' );
}

add_filter( 'woocommerce_lostpassword_url', 'stationary_wc_lostpassword_url', 20 );
function stationary_wc_lostpassword_url( $url ) {
	return home_url( '/forget-password' );
}

add_action( 'template_redirect', 'stationary_auth_redirects', 5 );
function stationary_auth_redirects() {
	if ( is_admin() || ! function_exists( 'is_account_page' ) ) {
		return;
	}
	$logged_in = is_user_logged_in();

	if ( $logged_in ) {
		if ( is_page( 'login' ) || is_page( 'signup' ) ) {
			wp_safe_redirect( wc_get_page_permalink( 'myaccount' ) );
			exit;
		}
	} else {
		if ( is_account_page() || is_page( 'my-account' ) ) {
			wp_safe_redirect( add_query_arg( 'redirect_to', urlencode( wc_get_page_permalink( 'myaccount' ) ), home_url( '/login' ) ) );
			exit;
		}
	}

	if ( is_page( 'forget-password' ) && ! empty( $_GET['show-reset-form'] ) ) {
		wp_safe_redirect( home_url( '/reset-password' ) );
		exit;
	}
}

add_filter( 'woocommerce_get_account_endpoint_url', 'stationary_account_endpoint_url', 10, 2 );
function stationary_account_endpoint_url( $url, $endpoint ) {
	if ( 'lost-password' === $endpoint ) {
		return home_url( '/forget-password' );
	}
	return $url;
}

add_action( 'woocommerce_customer_reset_password', 'stationary_redirect_after_reset' );
function stationary_redirect_after_reset() {
	add_filter( 'wp_redirect', 'stationary_redirect_to_login_after_reset', 10, 2 );
}

function stationary_redirect_to_login_after_reset( $location, $status ) {
	if ( strpos( $location, 'password-reset' ) !== false ) {
		return add_query_arg( 'password-reset', 'true', home_url( '/login' ) );
	}
	return $location;
}

add_filter( 'woocommerce_registration_redirect', 'stationary_registration_redirect', 10, 1 );
add_filter( 'woocommerce_login_redirect', 'stationary_login_redirect', 10, 2 );
function stationary_login_redirect( $redirect, $user ) {
	if ( ! empty( $_POST['redirect'] ) ) {
		return wp_validate_redirect( wp_unslash( $_POST['redirect'] ), $redirect );
	}
	$redirect_to = isset( $_GET['redirect_to'] ) ? wp_unslash( $_GET['redirect_to'] ) : '';
	if ( $redirect_to ) {
		return wp_validate_redirect( $redirect_to, $redirect );
	}
	return $redirect;
}

function stationary_registration_redirect( $redirect ) {
	if ( ! empty( $_POST['redirect'] ) ) {
		return wp_validate_redirect( wp_unslash( $_POST['redirect'] ), $redirect );
	}
	return $redirect;
}

add_filter( 'logout_url', 'stationary_logout_redirect', 20, 2 );
function stationary_logout_redirect( $logout_url, $redirect ) {
	$redirect = home_url( '/login' );
	return add_query_arg( 'redirect_to', urlencode( $redirect ), $logout_url );
}

add_filter( 'woocommerce_process_registration_errors', 'stationary_validate_registration', 10, 3 );
function stationary_validate_registration( $validation_error, $username, $email ) {
	if ( isset( $_POST['confirm_password'], $_POST['password'] ) && $_POST['password'] !== $_POST['confirm_password'] ) {
		return new WP_Error( 'password_mismatch', __( 'كلمة المرور غير متطابقة.', 'stationary-theme' ) );
	}
	return $validation_error;
}

add_action( 'woocommerce_created_customer', 'stationary_save_registration_phone', 10, 1 );
function stationary_save_registration_phone( $customer_id ) {
	if ( ! empty( $_POST['billing_phone'] ) ) {
		update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) ) );
	}
}

add_filter( 'gettext', 'stationary_wc_auth_translations', 20, 3 );
function stationary_wc_auth_translations( $translated, $text, $domain ) {
	if ( 'woocommerce' !== $domain ) {
		return $translated;
	}
	$map = array(
		'Invalid username or email.' => 'البريد الإلكتروني غير مسجل لدينا.',
		'Enter a username or email address.' => 'أدخل البريد الإلكتروني أو اسم المستخدم.',
		'A link to set a new password has been sent to your email address.' => 'تم إرسال رابط تعيين كلمة مرور جديدة إلى بريدك الإلكتروني.',
		'Passwords do not match.' => 'كلمة المرور غير متطابقة.',
		'Please enter your password.' => 'الرجاء إدخال كلمة المرور.',
		'ERROR: The username or password you entered is incorrect. Lost your password?' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
		'Unknown email address. Check again or try your username.' => 'البريد الإلكتروني غير مسجل.',
	);
	if ( isset( $map[ $text ] ) ) {
		return $map[ $text ];
	}
	return $translated;
}

add_action( 'wp_logout', 'stationary_do_logout_redirect' );
function stationary_do_logout_redirect() {
	if ( isset( $_REQUEST['redirect_to'] ) ) {
		return;
	}
	wp_safe_redirect( home_url( '/login' ) );
	exit;
}

add_filter( 'woocommerce_account_menu_items', 'stationary_account_menu_items_ar', 20 );
function stationary_account_menu_items_ar( $items ) {
	$ar = array(
		'dashboard'       => __( 'لوحة التحكم', 'stationary-theme' ),
		'orders'          => __( 'الطلبات', 'stationary-theme' ),
		'downloads'       => __( 'التحميلات', 'stationary-theme' ),
		'edit-address'    => __( 'العناوين', 'stationary-theme' ),
		'payment-methods' => __( 'طرق الدفع', 'stationary-theme' ),
		'edit-account'    => __( 'البيانات الشخصية', 'stationary-theme' ),
		'customer-logout' => __( 'تسجيل الخروج', 'stationary-theme' ),
	);
	foreach ( $items as $key => $label ) {
		if ( isset( $ar[ $key ] ) ) {
			$items[ $key ] = $ar[ $key ];
		}
	}
	return $items;
}

