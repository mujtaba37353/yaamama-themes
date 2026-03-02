<?php

define( 'YAAMAMA_THEME_VERSION', '1.0.1' );

require_once get_template_directory() . '/homepage-settings.php';
require_once get_template_directory() . '/demo-products.php';

function yaamama_ensure_theme_assets() {
	$theme_dir = get_template_directory();
	$target_dir = $theme_dir . '/assets';
	$target_file = $target_dir . '/spec-bg.png';
	$source_file = $theme_dir . '/yaamama-front-platform/assets/spec-bg.png';

	if ( ! file_exists( $target_file ) && file_exists( $source_file ) ) {
		wp_mkdir_p( $target_dir );
		copy( $source_file, $target_file );
	}
}
add_action( 'after_setup_theme', 'yaamama_ensure_theme_assets' );

function yaamama_upload_size_limit( $size ) {
	$limit = 64 * 1024 * 1024;
	return min( $size, $limit );
}
add_filter( 'upload_size_limit', 'yaamama_upload_size_limit' );

function yaamama_theme_setup() {
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'yaamama_theme_setup' );

function yaamama_force_account_template( $template ) {
	if ( is_page( 'my-account' ) || ( function_exists( 'is_account_page' ) && is_account_page() ) ) {
		$account_template = get_template_directory() . '/page-my-account.php';
		if ( file_exists( $account_template ) ) {
			return $account_template;
		}
	}

	return $template;
}
add_filter( 'template_include', 'yaamama_force_account_template', 999 );

function yaamama_account_body_class( $classes ) {
	if ( is_page( 'my-account' ) || ( function_exists( 'is_account_page' ) && is_account_page() ) ) {
		$classes[] = 'yaamama-my-account-template';
	}
	return $classes;
}
add_filter( 'body_class', 'yaamama_account_body_class' );

function yaamama_theme_base_uri() {
	return get_template_directory_uri() . '/yaamama-front-platform';
}

function yaamama_enqueue_template_style( $handle, $relative_path ) {
	$absolute_path = get_template_directory() . '/yaamama-front-platform/' . ltrim( $relative_path, '/' );
	if ( file_exists( $absolute_path ) ) {
		$version = (string) filemtime( $absolute_path );
		wp_enqueue_style(
			$handle,
			yaamama_theme_base_uri() . '/' . ltrim( $relative_path, '/' ),
			array( 'yaamama-typography' ),
			$version
		);
	}
}

function yaamama_enqueue_assets() {
	$base_uri = yaamama_theme_base_uri();
	$is_my_account_page = is_page( 'my-account' ) || ( function_exists( 'is_account_page' ) && is_account_page() );
	$reset_path = get_theme_file_path( 'yaamama-front-platform/base/reset.css' );
	$reset_version = file_exists( $reset_path ) ? (string) filemtime( $reset_path ) : YAAMAMA_THEME_VERSION;

	wp_enqueue_style( 'yaamama-reset', $base_uri . '/base/reset.css', array(), $reset_version );
	wp_enqueue_style( 'yaamama-tokens', $base_uri . '/base/tokens.css', array( 'yaamama-reset' ), YAAMAMA_THEME_VERSION );
	wp_enqueue_style( 'yaamama-utilities', $base_uri . '/base/utilities.css', array( 'yaamama-tokens' ), YAAMAMA_THEME_VERSION );
	wp_enqueue_style( 'yaamama-typography', $base_uri . '/base/typography.css', array( 'yaamama-utilities' ), YAAMAMA_THEME_VERSION );
	wp_add_inline_style(
		'yaamama-utilities',
		'.woocommerce-Price-currencySymbol img{display:inline-block;vertical-align:middle;margin-inline-start:0.25em}' .
		'.woocommerce-Price-amount{white-space:nowrap}'
	);
	if ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-pay' ) ) {
		wp_add_inline_style( 'yaamama-utilities', '#place_order{display:inline-flex !important;}' );
	}

	wp_enqueue_style( 'yaamama-header', $base_uri . '/components/header.css', array( 'yaamama-typography' ), YAAMAMA_THEME_VERSION );
	wp_enqueue_style( 'yaamama-footer', $base_uri . '/components/footer.css', array( 'yaamama-typography' ), YAAMAMA_THEME_VERSION );
	wp_enqueue_style( 'yaamama-buttons', $base_uri . '/components/buttons.css', array( 'yaamama-typography' ), YAAMAMA_THEME_VERSION );
	wp_enqueue_style( 'yaamama-forms', $base_uri . '/components/forms.css', array( 'yaamama-typography' ), YAAMAMA_THEME_VERSION );
	wp_enqueue_style( 'yaamama-faqs', $base_uri . '/components/faqs.css', array( 'yaamama-typography' ), YAAMAMA_THEME_VERSION );
	wp_enqueue_style( 'yaamama-auth', $base_uri . '/components/auth.css', array( 'yaamama-typography' ), YAAMAMA_THEME_VERSION );
	wp_enqueue_style( 'yaamama-filters', $base_uri . '/components/filters.css', array( 'yaamama-typography' ), YAAMAMA_THEME_VERSION );
	wp_enqueue_style( 'yaamama-message', $base_uri . '/components/message.css', array( 'yaamama-typography' ), YAAMAMA_THEME_VERSION );

	wp_enqueue_style(
		'yaamama-theme-fonts',
		'https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap',
		array(),
		null
	);
	wp_enqueue_style(
		'yaamama-font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
		array(),
		'6.4.0'
	);

	if ( is_front_page() ) {
		yaamama_enqueue_template_style( 'yaamama-home', 'templates/home/style.css' );
	}

	if ( is_page( 'store' ) ) {
		yaamama_enqueue_template_style( 'yaamama-store', 'templates/store/style.css' );
	}

	if ( is_page( 'single-temp' ) || ( function_exists( 'is_product' ) && is_product() ) ) {
		yaamama_enqueue_template_style( 'yaamama-single-temp', 'templates/single-temp/style.css' );
	}

	if ( is_page( 'about-us' ) ) {
		yaamama_enqueue_template_style( 'yaamama-about', 'templates/about-us/style.css' );
	}

	if ( is_page( 'contact' ) ) {
		yaamama_enqueue_template_style( 'yaamama-contact', 'templates/contact/style.css' );
	}

	if ( is_page( 'delete-account' ) ) {
		yaamama_enqueue_template_style( 'yaamama-delete-account', 'templates/delete-account/style.css' );
	}

	if ( is_page( 'forget-password' ) ) {
		yaamama_enqueue_template_style( 'yaamama-forget-password', 'templates/forget-password/style.css' );
	}

	if ( $is_my_account_page ) {
		yaamama_enqueue_template_style( 'yaamama-my-account', 'templates/my-account/style.css' );
	}

	if ( is_page( 'payment' ) || ( function_exists( 'is_checkout' ) && is_checkout() ) ) {
		yaamama_enqueue_template_style( 'yaamama-payment', 'templates/payment/style.css' );
	}

	if ( is_page( 'reset-message' ) ) {
		yaamama_enqueue_template_style( 'yaamama-reset-message', 'templates/reset-message/style.css' );
	}

	if ( is_page( 'reset-password' ) ) {
		yaamama_enqueue_template_style( 'yaamama-reset-password', 'templates/reset-password/style.css' );
	}

	if ( is_page( 'thank-you' ) ) {
		yaamama_enqueue_template_style( 'yaamama-thank-you', 'templates/thank-you/style.css' );
	}

	if ( is_404() ) {
		yaamama_enqueue_template_style( 'yaamama-404', 'templates/404/style.css' );
	}

	$needs_validation = is_page(
		array(
			'contact',
			'login',
			'signup',
			'forget-password',
			'reset-password',
			'payment',
			'my-account',
		)
	) || $is_my_account_page;

	wp_enqueue_script(
		'yaamama-app',
		$base_uri . '/js/y-app-init.js',
		array(),
		YAAMAMA_THEME_VERSION,
		true
	);
	if ( is_front_page() ) {
		wp_add_inline_script(
			'yaamama-app',
			"(function(){\n" .
			"  function buildLogos(){\n" .
			"    var track=document.getElementById('logosTrack');\n" .
			"    if(!track || !Array.isArray(window.LOGO_IMAGES) || !window.LOGO_IMAGES.length){return;}\n" .
			"    var urls=window.LOGO_IMAGES.filter(Boolean);\n" .
			"    if(!urls.length){return;}\n" .
			"    track.innerHTML='';\n" .
			"    urls.forEach(function(url){\n" .
			"      var item=document.createElement('div');\n" .
			"      item.className='logo-item';\n" .
			"      var img=document.createElement('img');\n" .
			"      img.src=url;\n" .
			"      img.alt='';\n" .
			"      img.loading='lazy';\n" .
			"      item.appendChild(img);\n" .
			"      track.appendChild(item);\n" .
			"    });\n" .
			"  }\n" .
			"  window.addEventListener('load', function(){\n" .
			"    buildLogos();\n" .
			"  });\n" .
			"})();"
		);
	}

	if ( $needs_validation ) {
		wp_enqueue_script(
			'yaamama-just-validate',
			'https://unpkg.com/just-validate@4.3.0/dist/just-validate.production.min.js',
			array(),
			'4.3.0',
			true
		);
		wp_enqueue_script(
			'yaamama-form-validation',
			$base_uri . '/js/form-validation.js',
			array( 'yaamama-just-validate' ),
			YAAMAMA_THEME_VERSION,
			true
		);
	}

	if ( $is_my_account_page ) {
		wp_enqueue_script(
			'yaamama-dropdown',
			$base_uri . '/js/dropdown.js',
			array(),
			YAAMAMA_THEME_VERSION,
			true
		);
		wp_enqueue_script(
			'yaamama-my-account',
			$base_uri . '/js/my-account.js',
			array( 'yaamama-dropdown' ),
			YAAMAMA_THEME_VERSION,
			true
		);
		wp_enqueue_script(
			'yaamama-jspdf',
			'https://cdn.jsdelivr.net/npm/jspdf@2.5.1/dist/jspdf.umd.min.js',
			array(),
			null,
			true
		);
		wp_enqueue_script(
			'yaamama-html2canvas',
			'https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js',
			array( 'yaamama-jspdf' ),
			null,
			true
		);
		wp_add_inline_script(
			'yaamama-html2canvas',
			"(function(){\n" .
			"  function buildInvoiceNode(data){\n" .
			"    var container=document.createElement('div');\n" .
			"    container.style.width='595px';\n" .
			"    container.style.padding='24px';\n" .
			"    container.style.fontFamily='Cairo, Arial, sans-serif';\n" .
			"    container.style.direction='rtl';\n" .
			"    container.style.background='#ffffff';\n" .
			"    container.style.color='#001309';\n" .
			"    container.innerHTML=\n" .
			"      '<div style=\"text-align:center;margin-bottom:16px;\">' +\n" .
			"        '<h2 style=\"margin:0 0 8px;font-size:20px;\">فاتورة الدفع</h2>' +\n" .
			"        '<div style=\"font-size:13px;color:#6b8076;\">رقم الطلب: '+data.orderId+'</div>' +\n" .
			"      '</div>' +\n" .
			"      '<div style=\"display:flex;justify-content:space-between;gap:16px;margin-bottom:16px;\">' +\n" .
			"        '<div><div style=\"font-size:12px;color:#6b8076;\">المتجر</div><div style=\"font-size:14px;font-weight:600;\">'+data.storeName+'</div></div>' +\n" .
			"        '<div><div style=\"font-size:12px;color:#6b8076;\">التاريخ</div><div style=\"font-size:14px;font-weight:600;\">'+data.date+'</div></div>' +\n" .
			"      '</div>' +\n" .
			"      '<div style=\"border-top:1px solid #e5e7eb;border-bottom:1px solid #e5e7eb;padding:12px 0;margin-bottom:16px;\">' +\n" .
			"        '<div style=\"font-size:13px;color:#6b8076;margin-bottom:8px;\">تفاصيل الطلب</div>' +\n" .
			"        '<ul style=\"margin:0;padding:0;list-style:none;\">' +\n" .
			"          data.items.map(function(item){return '<li style=\"display:flex;justify-content:space-between;font-size:13px;margin-bottom:6px;\"><span>'+item.name+'</span><span>× '+item.qty+'</span></li>';}).join('') +\n" .
			"        '</ul>' +\n" .
			"      '</div>' +\n" .
			"      '<div style=\"display:flex;justify-content:space-between;align-items:center;\">' +\n" .
			"        '<span style=\"font-size:14px;font-weight:600;\">الإجمالي</span>' +\n" .
			"        '<span style=\"font-size:16px;font-weight:700;\">'+data.total+' <span style=\"font-size:12px;\">ر.س</span></span>' +\n" .
			"      '</div>';\n" .
			"    return container;\n" .
			"  }\n" .
			"  function downloadInvoice(data){\n" .
			"    if (!window.html2canvas || !window.jspdf || !window.jspdf.jsPDF) return;\n" .
			"    var node=buildInvoiceNode(data);\n" .
			"    node.style.position='fixed';\n" .
			"    node.style.left='-9999px';\n" .
			"    node.style.top='0';\n" .
			"    document.body.appendChild(node);\n" .
			"    html2canvas(node,{scale:2,backgroundColor:'#ffffff'}).then(function(canvas){\n" .
			"      var imgData=canvas.toDataURL('image/png');\n" .
			"      var pdf=new window.jspdf.jsPDF('p','pt','a4');\n" .
			"      var pageWidth=pdf.internal.pageSize.getWidth();\n" .
			"      var pageHeight=pdf.internal.pageSize.getHeight();\n" .
			"      var imgWidth=pageWidth;\n" .
			"      var imgHeight=canvas.height*(pageWidth/canvas.width);\n" .
			"      if (imgHeight>pageHeight) {imgHeight=pageHeight;}\n" .
			"      pdf.addImage(imgData,'PNG',0,0,imgWidth,imgHeight);\n" .
			"      pdf.save('invoice-'+data.orderId+'.pdf');\n" .
			"      document.body.removeChild(node);\n" .
			"    }).catch(function(){document.body.removeChild(node);});\n" .
			"  }\n" .
			"  document.addEventListener('click',function(e){\n" .
			"    var btn=e.target.closest('[data-invoice-download]');\n" .
			"    if(!btn) return;\n" .
			"    e.preventDefault();\n" .
			"    var items=[];\n" .
			"    try{items=JSON.parse(btn.getAttribute('data-items')||'[]');}catch(err){items=[];}\n" .
			"    var data={\n" .
			"      orderId: btn.getAttribute('data-order-id')||'',\n" .
			"      storeName: btn.getAttribute('data-store-name')||'',\n" .
			"      date: btn.getAttribute('data-order-date')||'',\n" .
			"      total: btn.getAttribute('data-order-total')||'',\n" .
			"      items: items\n" .
			"    };\n" .
			"    downloadInvoice(data);\n" .
			"  });\n" .
			"})();"
		);
	}

	if ( is_page( 'payment' ) || ( function_exists( 'is_checkout' ) && is_checkout() ) ) {
		wp_enqueue_script(
			'yaamama-payment',
			$base_uri . '/js/payment.js',
			array(),
			YAAMAMA_THEME_VERSION,
			true
		);
	}

	if ( is_page( 'store' ) ) {
		wp_enqueue_script(
			'yaamama-store',
			$base_uri . '/js/store.js',
			array(),
			YAAMAMA_THEME_VERSION,
			true
		);
	}

	if ( function_exists( 'is_product' ) && is_product() ) {
		wp_enqueue_script(
			'yaamama-subscriptions',
			$base_uri . '/js/subscriptions.js',
			array(),
			YAAMAMA_THEME_VERSION,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'yaamama_enqueue_assets' );

function yaamama_is_checkout_or_order_pay() {
	return ( function_exists( 'is_checkout' ) && is_checkout() ) || ( function_exists( 'is_wc_endpoint_url' ) && is_wc_endpoint_url( 'order-pay' ) );
}

function yaamama_translate_paymob_gateway_title( $title, $gateway_id ) {
	if ( ! yaamama_is_checkout_or_order_pay() ) {
		return $title;
	}

	if ( strpos( (string) $gateway_id, 'paymob' ) === false ) {
		return $title;
	}

	return 'بطاقة بنكية';
}
add_filter( 'woocommerce_gateway_title', 'yaamama_translate_paymob_gateway_title', 10, 2 );

function yaamama_translate_paymob_gateway_description( $description, $gateway_id ) {
	if ( ! yaamama_is_checkout_or_order_pay() ) {
		return $description;
	}

	if ( strpos( (string) $gateway_id, 'paymob' ) === false ) {
		return $description;
	}

	return 'دفع آمن عبر بوابة Paymob';
}
add_filter( 'woocommerce_gateway_description', 'yaamama_translate_paymob_gateway_description', 10, 2 );

function yaamama_customize_paymob_gateway_texts( $gateways ) {
	if ( ! yaamama_is_checkout_or_order_pay() ) {
		return $gateways;
	}

	foreach ( $gateways as $gateway_id => $gateway ) {
		if ( strpos( (string) $gateway_id, 'paymob' ) === false ) {
			continue;
		}

		$gateway->title       = 'بطاقة بنكية';
		$gateway->description = 'دفع آمن عبر بوابة Paymob';
	}

	return $gateways;
}
add_filter( 'woocommerce_available_payment_gateways', 'yaamama_customize_paymob_gateway_texts', 20 );

function yaamama_translate_order_item_totals( $totals ) {
	if ( ! yaamama_is_checkout_or_order_pay() ) {
		return $totals;
	}

	if ( isset( $totals['payment_method'] ) ) {
		$totals['payment_method']['value'] = 'بطاقة بنكية';
	}

	return $totals;
}
add_filter( 'woocommerce_get_order_item_totals', 'yaamama_translate_order_item_totals', 20 );

function yaamama_translate_privacy_policy_text( $text ) {
	if ( ! yaamama_is_checkout_or_order_pay() ) {
		return $text;
	}

	return 'سيتم استخدام بياناتك الشخصية لمعالجة طلبك ودعم تجربتك خلال هذا الموقع ولأغراض أخرى موضحة في سياسة الخصوصية.';
}
add_filter( 'woocommerce_checkout_privacy_policy_text', 'yaamama_translate_privacy_policy_text', 99 );

function yaamama_output_privacy_policy_text() {
	if ( ! yaamama_is_checkout_or_order_pay() ) {
		wc_checkout_privacy_policy_text();
		return;
	}

	$privacy_url = get_privacy_policy_url();
	$text        = 'سيتم استخدام بياناتك الشخصية لمعالجة طلبك ودعم تجربتك خلال هذا الموقع ولأغراض أخرى موضحة في سياسة الخصوصية.';
	if ( $privacy_url ) {
		$text .= ' <a href="' . esc_url( $privacy_url ) . '">سياسة الخصوصية</a>.';
	}

	echo '<p>' . wp_kses_post( $text ) . '</p>';
}
remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text', 20 );
add_action( 'woocommerce_checkout_terms_and_conditions', 'yaamama_output_privacy_policy_text', 20 );

function yaamama_translate_pay_order_button_text( $text ) {
	if ( ! yaamama_is_checkout_or_order_pay() ) {
		return $text;
	}

	$normalized = strtolower( trim( (string) $text ) );
	if ( $normalized === 'pay for order' || $normalized === 'pay for order »' ) {
		return 'إتمام الدفع';
	}

	return $text;
}
add_filter( 'woocommerce_pay_order_button_text', 'yaamama_translate_pay_order_button_text' );

function yaamama_handle_profile_update() {
	if ( ! is_user_logged_in() ) {
		wp_safe_redirect( home_url( '/login?redirect_to=' . rawurlencode( home_url( '/my-account' ) ) ) );
		exit;
	}

	check_admin_referer( 'yaamama_update_profile', 'yaamama_update_profile_nonce' );

	$user_id   = get_current_user_id();
	$full_name = sanitize_text_field( wp_unslash( $_POST['full_name'] ?? '' ) );
	$email     = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone     = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$gender    = sanitize_text_field( wp_unslash( $_POST['gender'] ?? 'male' ) );

	if ( $email && ! is_email( $email ) ) {
		wp_safe_redirect( wp_get_referer() ?: home_url( '/my-account' ) );
		exit;
	}

	$update_data = array(
		'ID' => $user_id,
	);
	if ( $full_name ) {
		$update_data['display_name'] = $full_name;
	}
	if ( $email ) {
		$update_data['user_email'] = $email;
	}

	if ( $full_name ) {
		$name_parts = preg_split( '/\s+/', trim( $full_name ) );
		if ( $name_parts ) {
			$update_data['first_name'] = $name_parts[0];
			if ( count( $name_parts ) > 1 ) {
				$update_data['last_name'] = implode( ' ', array_slice( $name_parts, 1 ) );
			}
		}
	}

	wp_update_user( $update_data );

	if ( $phone ) {
		update_user_meta( $user_id, 'phone', $phone );
	}
	if ( in_array( $gender, array( 'male', 'female' ), true ) ) {
		update_user_meta( $user_id, 'gender', $gender );
	}

	$redirect = wp_get_referer() ?: home_url( '/my-account' );
	wp_safe_redirect( add_query_arg( 'profile_updated', '1', $redirect ) );
	exit;
}
add_action( 'admin_post_yaamama_update_profile', 'yaamama_handle_profile_update' );

function yaamama_handle_login() {
	check_admin_referer( 'yaamama_login', 'yaamama_login_nonce' );

	$login_raw = sanitize_text_field( wp_unslash( $_POST['email'] ?? '' ) );
	$password = wp_unslash( $_POST['password'] ?? '' );
	$remember = ! empty( $_POST['remember'] );

	if ( empty( $login_raw ) || empty( $password ) ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'الرجاء إدخال البريد وكلمة المرور.' ), home_url( '/login' ) ) );
		exit;
	}

	$user = wp_signon(
		array(
			'user_login'    => $login_raw,
			'user_password' => $password,
			'remember'      => $remember,
		),
		is_ssl()
	);

	if ( is_wp_error( $user ) ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'بيانات الدخول غير صحيحة.' ), home_url( '/login' ) ) );
		exit;
	}

	$redirect = esc_url_raw( wp_unslash( $_POST['redirect_to'] ?? home_url( '/my-account' ) ) );
	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_nopriv_yaamama_login', 'yaamama_handle_login' );

function yaamama_handle_signup() {
	check_admin_referer( 'yaamama_signup', 'yaamama_signup_nonce' );

	$full_name = sanitize_text_field( wp_unslash( $_POST['fullname'] ?? '' ) );
	$email     = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$phone     = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$password  = wp_unslash( $_POST['password'] ?? '' );
	$confirm   = wp_unslash( $_POST['confirm-password'] ?? '' );

	if ( ! $full_name || ! $email || ! $password ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'يرجى تعبئة جميع الحقول المطلوبة.' ), home_url( '/signup' ) ) );
		exit;
	}
	if ( $password !== $confirm ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'كلمتا المرور غير متطابقتين.' ), home_url( '/signup' ) ) );
		exit;
	}
	if ( email_exists( $email ) ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'هذا البريد مستخدم بالفعل.' ), home_url( '/signup' ) ) );
		exit;
	}

	$username = sanitize_user( current( explode( '@', $email ) ), true );
	if ( username_exists( $username ) ) {
		$username .= wp_generate_password( 4, false, false );
	}

	$user_id = wp_create_user( $username, $password, $email );
	if ( is_wp_error( $user_id ) ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'تعذر إنشاء الحساب.' ), home_url( '/signup' ) ) );
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

	$user = wp_signon(
		array(
			'user_login'    => $email,
			'user_password' => $password,
			'remember'      => true,
		),
		is_ssl()
	);

	$redirect = esc_url_raw( wp_unslash( $_POST['redirect_to'] ?? home_url( '/my-account' ) ) );
	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_nopriv_yaamama_signup', 'yaamama_handle_signup' );

function yaamama_handle_forgot_password() {
	check_admin_referer( 'yaamama_forgot_password', 'yaamama_forgot_password_nonce' );

	$email = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	if ( ! $email ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'يرجى إدخال البريد الإلكتروني.' ), home_url( '/forget-password' ) ) );
		exit;
	}

	$_POST['user_login'] = $email;
	$result = retrieve_password();
	if ( is_wp_error( $result ) ) {
		wp_safe_redirect( add_query_arg( 'auth_error', rawurlencode( 'تعذر إرسال الرابط. تأكد من البريد.' ), home_url( '/forget-password' ) ) );
		exit;
	}

	wp_safe_redirect( add_query_arg( 'sent', '1', home_url( '/forget-password' ) ) );
	exit;
}
add_action( 'admin_post_nopriv_yaamama_forgot_password', 'yaamama_handle_forgot_password' );

function yaamama_build_reset_password_url( $user_login, $key ) {
	return add_query_arg(
		array(
			'key'   => $key,
			'login' => $user_login,
		),
		home_url( '/reset-password' )
	);
}

function yaamama_filter_retrieve_password_message( $message, $key, $user_login, $user_data ) {
	$reset_url = yaamama_build_reset_password_url( $user_login, $key );
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
add_filter( 'retrieve_password_message', 'yaamama_filter_retrieve_password_message', 10, 4 );

function yaamama_handle_reset_password() {
	check_admin_referer( 'yaamama_reset_password', 'yaamama_reset_password_nonce' );

	$login    = sanitize_text_field( wp_unslash( $_POST['login'] ?? '' ) );
	$key      = sanitize_text_field( wp_unslash( $_POST['key'] ?? '' ) );
	$password = (string) wp_unslash( $_POST['password'] ?? '' );
	$confirm  = (string) wp_unslash( $_POST['confirm-password'] ?? '' );

	if ( ! $login || ! $key ) {
		wp_safe_redirect( add_query_arg( 'reset_error', rawurlencode( 'الرابط غير صالح. يرجى طلب رابط جديد.' ), home_url( '/reset-password' ) ) );
		exit;
	}

	if ( ! $password || ! $confirm ) {
		wp_safe_redirect( add_query_arg( array(
			'reset_error' => rawurlencode( 'يرجى إدخال كلمة المرور وتأكيدها.' ),
			'login'       => rawurlencode( $login ),
			'key'         => rawurlencode( $key ),
		), home_url( '/reset-password' ) ) );
		exit;
	}

	if ( $password !== $confirm ) {
		wp_safe_redirect( add_query_arg( array(
			'reset_error' => rawurlencode( 'كلمتا المرور غير متطابقتين.' ),
			'login'       => rawurlencode( $login ),
			'key'         => rawurlencode( $key ),
		), home_url( '/reset-password' ) ) );
		exit;
	}

	$user = check_password_reset_key( $key, $login );
	if ( is_wp_error( $user ) ) {
		wp_safe_redirect( add_query_arg( 'reset_error', rawurlencode( 'الرابط غير صالح أو منتهي. اطلب رابطاً جديداً.' ), home_url( '/reset-password' ) ) );
		exit;
	}

	reset_password( $user, $password );
	wp_safe_redirect( add_query_arg( 'reset_success', '1', home_url( '/reset-password' ) ) );
	exit;
}
add_action( 'admin_post_nopriv_yaamama_reset_password', 'yaamama_handle_reset_password' );

function yaamama_contact_configure_phpmailer( $phpmailer ) {
	global $yaamama_contact_mailer_settings;

	if ( empty( $yaamama_contact_mailer_settings ) ) {
		return;
	}

	$type = $yaamama_contact_mailer_settings['type'] ?? '';
	$config = $yaamama_contact_mailer_settings['config'] ?? array();

	if ( ! in_array( $type, array( 'smtp', 'gmail' ), true ) ) {
		return;
	}

	$phpmailer->CharSet = 'UTF-8';
	$phpmailer->isSMTP();
	$phpmailer->SMTPAuth = true;

	if ( 'gmail' === $type ) {
		$phpmailer->Host = 'smtp.gmail.com';
		$phpmailer->Port = 587;
		$phpmailer->SMTPSecure = 'tls';
		$phpmailer->Username = $config['email'] ?? '';
		$phpmailer->Password = $config['app_password'] ?? '';
		$from_name = $config['from_name'] ?? '';
		$from_email = $config['from_email'] ?? ( $config['email'] ?? '' );
	} else {
		$phpmailer->Host = $config['host'] ?? '';
		$phpmailer->Port = (int) ( $config['port'] ?? 587 );
		$encryption = $config['encryption'] ?? '';
		if ( $encryption && 'none' !== $encryption ) {
			$phpmailer->SMTPSecure = $encryption;
		} else {
			$phpmailer->SMTPSecure = '';
			$phpmailer->SMTPAutoTLS = false;
		}
		$phpmailer->Username = $config['username'] ?? '';
		$phpmailer->Password = $config['password'] ?? '';
		$from_name = $config['from_name'] ?? '';
		$from_email = $config['from_email'] ?? '';
	}

	if ( $from_email ) {
		$phpmailer->setFrom( $from_email, $from_name ?: get_bloginfo( 'name' ), false );
	}
}

function yaamama_prepare_contact_mailer( $contact_settings ) {
	global $yaamama_contact_mailer_settings;

	$type = $contact_settings['mail']['mailer_type'] ?? '';
	if ( ! in_array( $type, array( 'smtp', 'gmail' ), true ) ) {
		return;
	}

	$yaamama_contact_mailer_settings = array(
		'type'   => $type,
		'config' => ( 'gmail' === $type )
			? ( $contact_settings['mail']['gmail'] ?? array() )
			: ( $contact_settings['mail']['smtp'] ?? array() ),
	);

	add_action( 'phpmailer_init', 'yaamama_contact_configure_phpmailer' );
}

function yaamama_clear_contact_mailer() {
	global $yaamama_contact_mailer_settings;
	remove_action( 'phpmailer_init', 'yaamama_contact_configure_phpmailer' );
	$yaamama_contact_mailer_settings = null;
}

function yaamama_handle_contact_submit() {
	if ( ! isset( $_POST['yaamama_contact_nonce'] ) || ! wp_verify_nonce( $_POST['yaamama_contact_nonce'], 'yaamama_contact_submit' ) ) {
		wp_safe_redirect( add_query_arg( 'contact_error', rawurlencode( 'الطلب غير صالح.' ), home_url( '/contact' ) ) );
		exit;
	}

	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$name    = sanitize_text_field( wp_unslash( $_POST['name'] ?? '' ) );
	$subject = sanitize_text_field( wp_unslash( $_POST['subject'] ?? '' ) );
	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$message = sanitize_textarea_field( wp_unslash( $_POST['message'] ?? '' ) );

	if ( ! $email || ! $name || ! $subject || ! $message ) {
		wp_safe_redirect( add_query_arg( 'contact_error', rawurlencode( 'يرجى تعبئة جميع الحقول المطلوبة.' ), home_url( '/contact' ) ) );
		exit;
	}

	$settings = yaamama_get_contact_settings();
	$recipient = $settings['mail']['recipient_email'] ?? '';
	if ( ! $recipient ) {
		$recipient = get_option( 'admin_email' );
	}

	$body = "تم استلام رسالة جديدة عبر نموذج التواصل:\n\n";
	$body .= "الاسم: {$name}\n";
	$body .= "البريد: {$email}\n";
	if ( $phone ) {
		$body .= "الهاتف: {$phone}\n";
	}
	$body .= "الموضوع: {$subject}\n\n";
	$body .= "الرسالة:\n{$message}\n";

	$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );

	yaamama_prepare_contact_mailer( $settings );
	$sent = wp_mail( $recipient, $subject, $body, $headers );
	yaamama_clear_contact_mailer();

	if ( ! $sent ) {
		wp_safe_redirect( add_query_arg( 'contact_error', rawurlencode( 'تعذر إرسال الرسالة. حاول لاحقًا.' ), home_url( '/contact' ) ) );
		exit;
	}

	$redirect = esc_url_raw( wp_unslash( $_POST['redirect_to'] ?? home_url( '/contact-message' ) ) );
	wp_safe_redirect( $redirect );
	exit;
}
add_action( 'admin_post_yaamama_contact_submit', 'yaamama_handle_contact_submit' );
add_action( 'admin_post_nopriv_yaamama_contact_submit', 'yaamama_handle_contact_submit' );

function yaamama_handle_contact_test_email() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	if ( ! isset( $_POST['yaamama_contact_test_nonce'] ) || ! wp_verify_nonce( $_POST['yaamama_contact_test_nonce'], 'yaamama_contact_test_nonce' ) ) {
		wp_safe_redirect( add_query_arg( array( 'page' => 'yaamama-contact', 'contact_test' => 'error', 'contact_test_msg' => rawurlencode( 'الطلب غير صالح.' ) ), admin_url( 'admin.php' ) ) );
		exit;
	}

	$settings = yaamama_get_contact_settings();
	$recipient = $settings['mail']['recipient_email'] ?? '';
	if ( ! $recipient ) {
		wp_safe_redirect( add_query_arg( array( 'page' => 'yaamama-contact', 'contact_test' => 'error', 'contact_test_msg' => rawurlencode( 'يرجى تحديد البريد المستلم أولاً.' ) ), admin_url( 'admin.php' ) ) );
		exit;
	}

	$subject = 'اختبار البريد - Yamama';
	$body = 'هذه رسالة اختبار للتأكد من إعدادات البريد.';

	yaamama_prepare_contact_mailer( $settings );
	$sent = wp_mail( $recipient, $subject, $body );
	yaamama_clear_contact_mailer();

	$status = $sent ? 'success' : 'error';
	$message = $sent ? 'تم إرسال رسالة الاختبار بنجاح.' : 'تعذر إرسال رسالة الاختبار.';
	wp_safe_redirect( add_query_arg( array( 'page' => 'yaamama-contact', 'contact_test' => $status, 'contact_test_msg' => rawurlencode( $message ) ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_post_yaamama_contact_test_email', 'yaamama_handle_contact_test_email' );

/**
 * محتوى افتراضي لصفحات السياسات من ملفات التصميم (قوالب السياسات).
 * يُستخدم عند إنشاء الصفحة لأول مرة.
 */
function yaamama_get_policy_page_default_content( $slug ) {
	$contents = array(
		'privacy-policy'  => '<div class="policy-container">' .
			'<h1>سياسة الخصوصية</h1>' .
			'<p>نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية. توضح هذه السياسة كيفية جمعنا واستخدامنا وحمايتنا لمعلوماتك عند استخدام الموقع.</p>' .
			'<p>نستخدم المعلومات التي نجمعها لتحسين تجربتك وتقديم خدمة العملاء ومعالجة الطلبات وإرسال التحديثات إن وافقت على ذلك.</p>' .
			'<p>لا نبيع أو نؤجر بياناتك الشخصية لأطراف ثالثة. قد نشارك المعلومات مع مقدمي خدمات موثوقين لمساعدتنا في تشغيل الموقع ومعالجة المدفوعات.</p>' .
			'<p>لأي استفسار حول الخصوصية يرجى التواصل معنا عبر البريد الإلكتروني أو نموذج تواصل معنا.</p>' .
			'</div>',
		'refund-policy'   => '<div class="policy-container">' .
			'<h1>سياسة الاسترجاع والاسترداد</h1>' .
			'<p>نعمل دائماً لنيل رضاكم. إذا كنت ترغب في إرجاع منتج ما، نقبل بسرور استبدال المنتج أو منحك رصيداً في المتجر أو إرجاع المبلغ وفق خياراتنا المتاحة.</p>' .
			'<p>في حال طلب استرجاع أي منتج، يرجى التواصل معنا عبر البريد الإلكتروني أو الهاتف أو الواتساب.</p>' .
			'<h2>الدفع عبر الإنترنت</h2>' .
			'<p>ستتم معالجة المبالغ المستردة في غضون ٢٤ ساعة وستضاف إلى حساب العميل في غضون 3-5 أيام عمل، اعتماداً على مصدر البنك.</p>' .
			'<h2>الدفع نقداً عند التسليم</h2>' .
			'<p>ستُضاف المبالغ المستردة إلى حساب العميل كنقاط متجر ويمكن استخدامها في الطلب التالي.</p>' .
			'</div>',
		'shipping-policy' => '<div class="policy-container">' .
			'<h1>سياسة الشحن</h1>' .
			'<p>نسعى لتوصيل طلباتكم في أسرع وقت. يتم الشحن خلال اليوم أو خلال يوم عمل واحد للطلبات داخل المدينة، أما الطلبات داخل المملكة وخارج المدينة فيستغرق الشحن من يوم إلى ثلاثة أيام عمل.</p>' .
			'<p>نوفر تتبع الشحنة وتحديثات الحالة. للتتبع أو الاستفسار عن طلبك يرجى التواصل معنا.</p>' .
			'</div>',
	);
	return isset( $contents[ $slug ] ) ? $contents[ $slug ] : '';
}

function yaamama_pages_manifest() {
	return array(
		'home'            => 'الرئيسية',
		'store'           => 'متاجرنا',
		'single-temp'     => 'تفاصيل القالب',
		'about-us'        => 'من نحن',
		'contact'         => 'تواصل معنا',
		'faq'             => 'الأسئلة الشائعة',
		'guide'           => 'الدليل',
		'privacy-policy'  => 'سياسة الخصوصية',
		'refund-policy'   => 'سياسة الاسترجاع',
		'shipping-policy' => 'سياسة الشحن',
		'login'           => 'تسجيل الدخول',
		'signup'          => 'إنشاء حساب',
		'forget-password' => 'نسيت كلمة المرور',
		'reset-password'  => 'إعادة تعيين كلمة المرور',
		'reset-message'   => 'رسالة إعادة التعيين',
		'contact-message' => 'تم إرسال الاستفسار',
		'delete-account'  => 'حذف الحساب',
		'my-account'      => 'حسابي',
		'payment'         => 'إتمام الشراء',
		'thank-you'       => 'تأكيد الدفع',
	);
}

function yaamama_register_pages_admin() {
	// الصفحات تُعرض تحت: المحتوى → الصفحات (في homepage-settings).
}

function yaamama_render_pages_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$pages = yaamama_pages_manifest();

	if ( isset( $_GET['yaamama_synced'] ) ) {
		echo '<div class="notice notice-success"><p>تم تحديث الصفحات بنجاح.</p></div>';
	}
	?>
	<div class="wrap">
		<h1>الصفحات</h1>
		<p>إنشاء أو تحديث جميع الصفحات المطلوبة للموقع. <strong>لا توجد صفحة باسم "السياسات"</strong> — "السياسات" عنوان قسم في الفوتر فقط. عند الضغط على "إنشاء/تحديث الصفحات" يتم إنشاء صفحة منفصلة لكل سياسة: <strong>سياسة الخصوصية</strong>، <strong>سياسة الاسترجاع</strong>، <strong>سياسة الشحن</strong> (مع محتوى افتراضي إن وُجدت فارغة).</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'yaamama_sync_pages', 'yaamama_sync_pages_nonce' ); ?>
			<input type="hidden" name="action" value="yaamama_sync_pages">
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

function yaamama_sync_pages_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}

	check_admin_referer( 'yaamama_sync_pages', 'yaamama_sync_pages_nonce' );

	$pages = yaamama_pages_manifest();
	$policy_slugs = array( 'privacy-policy', 'refund-policy', 'shipping-policy' );
	foreach ( $pages as $slug => $title ) {
		$page = get_page_by_path( $slug );
		if ( $page ) {
			$update = array(
				'ID'          => $page->ID,
				'post_title'  => $title,
				'post_status' => 'publish',
			);
			if ( in_array( $slug, $policy_slugs, true ) && trim( (string) $page->post_content ) === '' && function_exists( 'yaamama_get_policy_page_default_content' ) ) {
				$update['post_content'] = yaamama_get_policy_page_default_content( $slug );
			}
			if ( $page->post_title !== $title || 'publish' !== $page->post_status || isset( $update['post_content'] ) ) {
				wp_update_post( $update );
			}
		} else {
			$post_content = in_array( $slug, $policy_slugs, true ) && function_exists( 'yaamama_get_policy_page_default_content' )
				? yaamama_get_policy_page_default_content( $slug )
				: '';
			wp_insert_post(
				array(
					'post_title'   => $title,
					'post_name'    => $slug,
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_content' => $post_content,
				)
			);
		}
	}

	$home_page = get_page_by_path( 'home' );
	if ( $home_page ) {
		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_page->ID );
	}

	wp_safe_redirect( add_query_arg( 'yaamama_synced', '1', admin_url( 'admin.php?page=yaamama-pages' ) ) );
	exit;
}
add_action( 'admin_post_yaamama_sync_pages', 'yaamama_sync_pages_handler' );

function yaamama_add_demo_url_metabox() {
	add_meta_box(
		'yaamama_demo_url',
		'رابط الديمو',
		'yaamama_render_demo_url_metabox',
		'product',
		'side'
	);
}
add_action( 'add_meta_boxes', 'yaamama_add_demo_url_metabox' );

function yaamama_render_demo_url_metabox( $post ) {
	wp_nonce_field( 'yaamama_demo_url_meta', 'yaamama_demo_url_meta_nonce' );
	$value = get_post_meta( $post->ID, 'demo_url', true );
	?>
	<p>
		<label for="yaamama-demo-url">رابط الديمو</label>
		<input type="url" class="widefat" id="yaamama-demo-url" name="yaamama_demo_url"
			value="<?php echo esc_attr( $value ); ?>" placeholder="https://demo.example.com">
	</p>
	<?php
}

function yaamama_save_demo_url_meta( $post_id ) {
	if ( ! isset( $_POST['yaamama_demo_url_meta_nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['yaamama_demo_url_meta_nonce'], 'yaamama_demo_url_meta' ) ) {
		return;
	}

	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( 'product' !== get_post_type( $post_id ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$demo_url = '';
	if ( isset( $_POST['yaamama_demo_url'] ) ) {
		$demo_url = esc_url_raw( wp_unslash( $_POST['yaamama_demo_url'] ) );
	}

	if ( $demo_url ) {
		update_post_meta( $post_id, 'demo_url', $demo_url );
	} else {
		delete_post_meta( $post_id, 'demo_url' );
	}
}
add_action( 'save_post', 'yaamama_save_demo_url_meta' );

function yaamama_purge_site_cache() {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}

	if ( class_exists( 'LiteSpeed_Cache_API' ) ) {
		LiteSpeed_Cache_API::purge_all();
	} else {
		do_action( 'litespeed_purge_all' );
	}
}

function yaamama_purge_cache_on_option_change( $option_name ) {
	$watched = array(
		'yaamama_homepage_settings',
		'yaamama_about_settings',
		'yaamama_footer_settings',
	);

	if ( ! in_array( $option_name, $watched, true ) ) {
		return;
	}

	yaamama_purge_site_cache();
}

function yaamama_force_store_currency( $currency ) {
	return 'SAR';
}

function yaamama_force_currency_symbol( $symbol, $currency ) {
	if ( 'SAR' === $currency ) {
		$icon_url = get_template_directory_uri() . '/yaamama-front-platform/assets/ryal.svg';
		return '<img src="' . esc_url( $icon_url ) . '" alt="SAR" class="yaamama-currency-icon" style="height:1em;width:auto;vertical-align:middle;margin-inline-start:0.25em;">';
	}

	return $symbol;
}

function yaamama_order_received_redirect( $url, $order ) {
	$thank_you_page = get_page_by_path( 'thank-you' );
	if ( $thank_you_page ) {
		$thank_url = get_permalink( $thank_you_page->ID );
		if ( $order instanceof WC_Order ) {
			$sub_id = (int) $order->get_meta( 'yaamama_subscription_id' );
			if ( $sub_id ) {
				$thank_url = add_query_arg( 'sub', $sub_id, $thank_url );
			}
		}
		return $thank_url;
	}

	return $url;
}

function yaamama_fix_local_home_url( $url, $path, $orig_scheme, $blog_id ) {
	if ( defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE === 'local' ) {
		$bad_domain  = 'yamama-front-platform.local';
		$good_domain = 'yamama-platform.local';
		if ( strpos( $url, $bad_domain ) !== false ) {
			return str_replace( $bad_domain, $good_domain, $url );
		}
	}

	return $url;
}

function yaamama_fix_local_option_url( $value, $option ) {
	if ( defined( 'WP_ENVIRONMENT_TYPE' ) && WP_ENVIRONMENT_TYPE === 'local' ) {
		$bad_domain  = 'yamama-front-platform.local';
		$good_domain = 'yamama-platform.local';
		if ( is_string( $value ) && strpos( $value, $bad_domain ) !== false ) {
			return str_replace( $bad_domain, $good_domain, $value );
		}
	}

	return $value;
}

function yaamama_ensure_pay_order_button( $button_html ) {
	if ( is_string( $button_html ) && strpos( $button_html, 'id="place_order"' ) !== false ) {
		return $button_html;
	}

	$button_text = apply_filters( 'woocommerce_pay_order_button_text', __( 'Pay for order', 'woocommerce' ) );

	return '<button type="submit" class="btn main-button fw" id="place_order" value="' . esc_attr( $button_text ) . '" data-value="' . esc_attr( $button_text ) . '"><i class="fa-solid fa-basket-shopping"></i>' . esc_html( $button_text ) . '</button>';
}

function yaamama_update_pay_order_phone( $order ) {
	if ( empty( $_POST['billing_phone'] ) ) {
		return;
	}

	$phone = sanitize_text_field( wp_unslash( $_POST['billing_phone'] ) );
	if ( $phone === '' ) {
		return;
	}

	$order->set_billing_phone( $phone );
	$order->save();
}

add_action( 'save_post', 'yaamama_purge_site_cache', 20 );
add_action( 'deleted_post', 'yaamama_purge_site_cache', 20 );
add_action( 'trashed_post', 'yaamama_purge_site_cache', 20 );
add_action( 'created_term', 'yaamama_purge_site_cache', 20 );
add_action( 'edited_term', 'yaamama_purge_site_cache', 20 );
add_action( 'delete_term', 'yaamama_purge_site_cache', 20 );
add_action( 'customize_save_after', 'yaamama_purge_site_cache', 20 );
add_action( 'wp_update_nav_menu', 'yaamama_purge_site_cache', 20 );
add_action( 'updated_option', 'yaamama_purge_cache_on_option_change', 20, 1 );
add_action( 'deleted_option', 'yaamama_purge_cache_on_option_change', 20, 1 );
add_filter( 'woocommerce_currency', 'yaamama_force_store_currency', 20 );
add_filter( 'woocommerce_currency_symbol', 'yaamama_force_currency_symbol', 20, 2 );
add_filter( 'woocommerce_get_checkout_order_received_url', 'yaamama_order_received_redirect', 20, 2 );
add_filter( 'woocommerce_pay_order_button_html', 'yaamama_ensure_pay_order_button', 20, 1 );
add_filter( 'home_url', 'yaamama_fix_local_home_url', 20, 4 );
add_filter( 'site_url', 'yaamama_fix_local_home_url', 20, 4 );
add_filter( 'option_home', 'yaamama_fix_local_option_url', 20, 2 );
add_filter( 'option_siteurl', 'yaamama_fix_local_option_url', 20, 2 );
add_action( 'woocommerce_before_pay_action', 'yaamama_update_pay_order_phone' );

function yaamama_custom_login_url( $login_url, $redirect, $force_reauth ) {
	$custom = home_url( '/login' );
	if ( ! empty( $redirect ) ) {
		$custom = add_query_arg( 'redirect_to', rawurlencode( $redirect ), $custom );
	}
	return $custom;
}
add_filter( 'login_url', 'yaamama_custom_login_url', 20, 3 );

function yaamama_custom_register_url( $register_url ) {
	return home_url( '/signup' );
}
add_filter( 'register_url', 'yaamama_custom_register_url', 20, 1 );

function yaamama_block_wp_login() {
	if ( is_admin() ) {
		return;
	}
	global $pagenow;
	if ( $pagenow !== 'wp-login.php' ) {
		return;
	}
	$action = isset( $_GET['action'] ) ? $_GET['action'] : '';
	if ( in_array( $action, array( 'postpass', 'logout', 'lostpassword', 'retrievepassword', 'resetpass', 'rp', 'confirmaction' ), true ) ) {
		return;
	}
	$redirect_to = isset( $_GET['redirect_to'] ) ? $_GET['redirect_to'] : '';
	$url = home_url( '/login' );
	if ( $redirect_to ) {
		$url = add_query_arg( 'redirect_to', rawurlencode( $redirect_to ), $url );
	}
	wp_safe_redirect( $url );
	exit;
}
add_action( 'init', 'yaamama_block_wp_login' );
