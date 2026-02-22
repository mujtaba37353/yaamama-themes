<?php
/**
 * Auth route guards, compatibility redirects and Woo auth helpers.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Normalize old auth slugs and guard routes by auth state.
 */
function elegance_auth_route_guards() {
	if ( is_admin() ) {
		return;
	}

	$request_uri = ! empty( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '';
	$path        = $request_uri ? trim( (string) wp_parse_url( 'http://host' . $request_uri, PHP_URL_PATH ), '/' ) : '';

	// Legacy routes -> required routes.
	if ( strpos( $path, 'signup' ) !== false ) {
		wp_safe_redirect( elegance_page_url( 'register', '/register/' ), 301 );
		exit;
	}
	if ( strpos( $path, 'forget-password' ) !== false ) {
		wp_safe_redirect( elegance_page_url( 'forgot-password', '/forgot-password/' ), 301 );
		exit;
	}
	if ( strpos( $path, 'profile' ) !== false ) {
		wp_safe_redirect( elegance_myaccount_url(), 301 );
		exit;
	}

	$is_login_page  = is_page( 'login' );
	$is_register    = is_page( 'register' );
	$is_forgot      = is_page( 'forgot-password' );
	$is_my_account  = is_page( 'my-account' ) || ( function_exists( 'is_account_page' ) && is_account_page() );

	if ( is_user_logged_in() && ( $is_login_page || $is_register || $is_forgot ) ) {
		wp_safe_redirect( elegance_myaccount_url() );
		exit;
	}

	if ( ! is_user_logged_in() && $is_my_account ) {
		$redirect = add_query_arg( 'redirect_to', rawurlencode( elegance_myaccount_url() ), elegance_page_url( 'login', '/login/' ) );
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'template_redirect', 'elegance_auth_route_guards', 1 );

/**
 * Ensure Woo register uses email as username for design form.
 */
function elegance_prepare_wc_register_payload() {
	if ( empty( $_POST['register'] ) || ! isset( $_POST['email'] ) ) {
		return;
	}
	if ( empty( $_POST['username'] ) ) {
		$_POST['username'] = sanitize_email( wp_unslash( $_POST['email'] ) );
	}
}
add_action( 'init', 'elegance_prepare_wc_register_payload', 2 );

/**
 * Enforce confirm password field from design form before Woo registration.
 */
function elegance_wc_register_confirm_password( $username, $email, $errors ) {
	$password = isset( $_POST['password'] ) ? (string) wp_unslash( $_POST['password'] ) : '';
	$confirm  = isset( $_POST['confirm-password'] ) ? (string) wp_unslash( $_POST['confirm-password'] ) : '';
	if ( $password === '' || $confirm === '' || $password !== $confirm ) {
		$errors->add( 'password_mismatch', __( 'كلمة المرور وتأكيدها غير متطابقين.', 'elegance' ) );
	}
}
add_action( 'woocommerce_register_post', 'elegance_wc_register_confirm_password', 10, 3 );

/**
 * Persist phone from register form into billing_phone.
 */
function elegance_wc_save_register_phone( $customer_id ) {
	if ( ! $customer_id || empty( $_POST['phone'] ) ) {
		return;
	}
	$phone = sanitize_text_field( wp_unslash( $_POST['phone'] ) );
	if ( $phone === '' ) {
		return;
	}
	update_user_meta( $customer_id, 'billing_phone', $phone );
}
add_action( 'woocommerce_created_customer', 'elegance_wc_save_register_phone', 10, 1 );

/**
 * Redirect after successful login/register/logout.
 */
function elegance_auth_redirect_after_login( $redirect, $user ) {
	if ( $user instanceof WP_User ) {
		return elegance_myaccount_url();
	}
	return $redirect;
}
add_filter( 'woocommerce_login_redirect', 'elegance_auth_redirect_after_login', 10, 2 );
function elegance_auth_redirect_after_register( $redirect ) {
	return elegance_myaccount_url();
}
add_filter( 'woocommerce_registration_redirect', 'elegance_auth_redirect_after_register', 10, 1 );

function elegance_auth_lostpassword_redirect() {
	return elegance_page_url( 'forgot-password', '/forgot-password/' );
}
add_filter( 'woocommerce_lostpassword_redirect', 'elegance_auth_lostpassword_redirect', 10 );

function elegance_auth_logout_redirect( $redirect_to, $requested_redirect_to, $user ) {
	if ( ! empty( $requested_redirect_to ) ) {
		return $requested_redirect_to;
	}
	return elegance_page_url( 'login', '/login/' );
}
add_filter( 'logout_redirect', 'elegance_auth_logout_redirect', 10, 3 );

/**
 * Send reset links to /reset-password instead of wp-login.php.
 */
function elegance_reset_password_message_url( $message, $key, $user_login, $user_data ) {
	$reset_url = add_query_arg(
		array(
			'key'   => rawurlencode( $key ),
			'login' => rawurlencode( $user_login ),
		),
		elegance_page_url( 'reset-password', '/reset-password/' )
	);
	return sprintf(
		/* translators: %s reset link */
		__( "تم طلب إعادة تعيين كلمة المرور.\n\nاستخدم الرابط التالي لإعادة التعيين:\n%s\n\nإذا لم تطلب ذلك، تجاهل هذه الرسالة.", 'elegance' ),
		esc_url_raw( $reset_url )
	);
}
add_filter( 'retrieve_password_message', 'elegance_reset_password_message_url', 20, 4 );

/**
 * Process forgot-password on custom page and keep notices on the same design page.
 */
function elegance_process_forgot_password_page() {
	if ( ! is_page( 'forgot-password' ) || empty( $_POST['elegance_forgot_password'] ) ) {
		return;
	}
	if ( ! isset( $_POST['elegance_forgot_password_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['elegance_forgot_password_nonce'] ) ), 'elegance_forgot_password' ) ) {
		wp_safe_redirect( add_query_arg( 'fp', 'nonce', elegance_page_url( 'forgot-password', '/forgot-password/' ) ) );
		exit;
	}

	$user_login = isset( $_POST['user_login'] ) ? sanitize_text_field( wp_unslash( $_POST['user_login'] ) ) : '';
	if ( $user_login === '' ) {
		wp_safe_redirect( add_query_arg( 'fp', 'empty', elegance_page_url( 'forgot-password', '/forgot-password/' ) ) );
		exit;
	}

	$result = retrieve_password( $user_login );
	if ( is_wp_error( $result ) ) {
		wp_safe_redirect( add_query_arg( 'fp', 'notfound', elegance_page_url( 'forgot-password', '/forgot-password/' ) ) );
		exit;
	}
	wp_safe_redirect( add_query_arg( 'fp', 'sent', elegance_page_url( 'forgot-password', '/forgot-password/' ) ) );
	exit;
}
add_action( 'template_redirect', 'elegance_process_forgot_password_page', 2 );

/**
 * Process reset-password form on custom page.
 */
function elegance_process_reset_password_page() {
	if ( ! is_page( 'reset-password' ) || empty( $_POST['elegance_reset_password'] ) ) {
		return;
	}
	if ( ! isset( $_POST['elegance_reset_password_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['elegance_reset_password_nonce'] ) ), 'elegance_reset_password' ) ) {
		$reset_login = isset( $_POST['reset_login'] ) ? sanitize_text_field( wp_unslash( $_POST['reset_login'] ) ) : '';
		$reset_key   = isset( $_POST['reset_key'] ) ? sanitize_text_field( wp_unslash( $_POST['reset_key'] ) ) : '';
		$args        = array( 'rp' => 'nonce' );
		if ( $reset_login !== '' ) {
			$args['login'] = rawurlencode( $reset_login );
		}
		if ( $reset_key !== '' ) {
			$args['key'] = rawurlencode( $reset_key );
		}
		wp_safe_redirect( add_query_arg( $args, elegance_page_url( 'reset-password', '/reset-password/' ) ) );
		exit;
	}

	$reset_login = isset( $_POST['reset_login'] ) ? sanitize_text_field( wp_unslash( $_POST['reset_login'] ) ) : '';
	$reset_key   = isset( $_POST['reset_key'] ) ? sanitize_text_field( wp_unslash( $_POST['reset_key'] ) ) : '';
	$password_1  = isset( $_POST['password_1'] ) ? (string) wp_unslash( $_POST['password_1'] ) : '';
	$password_2  = isset( $_POST['password_2'] ) ? (string) wp_unslash( $_POST['password_2'] ) : '';

	$user = check_password_reset_key( $reset_key, $reset_login );
	if ( is_wp_error( $user ) ) {
		wp_safe_redirect( add_query_arg( 'rp', 'invalid', elegance_page_url( 'reset-password', '/reset-password/' ) ) );
		exit;
	}

	if ( $password_1 === '' || strlen( $password_1 ) < 6 ) {
		wp_safe_redirect( add_query_arg( array( 'login' => rawurlencode( $reset_login ), 'key' => rawurlencode( $reset_key ), 'rp' => 'weak' ), elegance_page_url( 'reset-password', '/reset-password/' ) ) );
		exit;
	}

	if ( $password_1 !== $password_2 ) {
		wp_safe_redirect( add_query_arg( array( 'login' => rawurlencode( $reset_login ), 'key' => rawurlencode( $reset_key ), 'rp' => 'mismatch' ), elegance_page_url( 'reset-password', '/reset-password/' ) ) );
		exit;
	}

	reset_password( $user, $password_1 );
	wp_safe_redirect( add_query_arg( 'rp', 'success', elegance_page_url( 'login', '/login/' ) ) );
	exit;
}
add_action( 'template_redirect', 'elegance_process_reset_password_page', 2 );

/**
 * Arabic replacements for common auth notices from Woo/WordPress.
 */
function elegance_translate_auth_notices( $translated, $text, $domain ) {
	static $map = null;
	if ( $map === null ) {
		$map = array(
			'Unknown email address. Check again or try your username.' => 'لا يوجد حساب مرتبط بهذا البريد الإلكتروني.',
			'Invalid username or password.'                             => 'بيانات تسجيل الدخول غير صحيحة.',
			'The password reset link appears to be invalid.'            => 'رابط إعادة تعيين كلمة المرور غير صالح.',
			'The password reset link has expired.'                      => 'انتهت صلاحية رابط إعادة تعيين كلمة المرور.',
			'Passwords do not match.'                                   => 'كلمة المرور وتأكيدها غير متطابقين.',
			'Please enter your password.'                               => 'الرجاء إدخال كلمة المرور.',
			'Please provide a valid email address.'                     => 'الرجاء إدخال بريد إلكتروني صحيح.',
			'Invalid username or email.'                                => 'لا يوجد حساب مرتبط بهذا البريد الإلكتروني أو اسم المستخدم.',
			'Password reset email has been sent.'                       => 'تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.',
		);
	}
	if ( isset( $map[ $text ] ) ) {
		return $map[ $text ];
	}
	if ( strpos( $text, 'An account is already registered with' ) !== false ) {
		return 'يوجد حساب مسجل بالفعل بهذا البريد الإلكتروني. الرجاء تسجيل الدخول أو استخدام بريد آخر.';
	}
	if ( strpos( $text, 'Please log in or use a different email address.' ) !== false ) {
		return 'الرجاء تسجيل الدخول أو استخدام بريد إلكتروني مختلف.';
	}
	if ( trim( $text ) === 'Error:' ) {
		return 'خطأ:';
	}
	return $translated;
}
add_filter( 'gettext', 'elegance_translate_auth_notices', 10, 3 );
