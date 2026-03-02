<?php
/**
 * Mallati Theme Functions
 */

if (!defined('ABSPATH')) {
    exit;
}

define('MALLATI_THEME_VERSION', '1.0.0');

function mallati_asset_uri($path) {
    return trailingslashit(get_template_directory_uri()) . 'mallati/' . ltrim($path, '/');
}

function mallati_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));

    register_nav_menus(array(
        'primary'       => __('Primary Menu', 'mallati-theme'),
        'footer_pages'  => __('Footer Pages', 'mallati-theme'),
        'footer_policies'=> __('Footer Policies', 'mallati-theme'),
    ));
}
add_action('after_setup_theme', 'mallati_setup');

/**
 * Ensure WooCommerce rewrite rules are available (shop/category URLs).
 * Runs once and prevents costly flush on every request.
 */
function mallati_maybe_flush_rewrite_rules() {
    $flag = get_option('mallati_rewrite_rules_flushed', '');
    if ('1' === (string) $flag) {
        return;
    }
    flush_rewrite_rules(false);
    update_option('mallati_rewrite_rules_flushed', '1', false);
}
add_action('init', 'mallati_maybe_flush_rewrite_rules', 20);

function mallati_enqueue_assets() {
    $ver = MALLATI_THEME_VERSION;
    $base = mallati_asset_uri('');

    wp_enqueue_style('mallati-tokens', mallati_asset_uri('base/tokens.css'), array(), $ver);
    wp_enqueue_style('mallati-reset', mallati_asset_uri('base/reset.css'), array('mallati-tokens'), $ver);
    wp_enqueue_style('mallati-typography', mallati_asset_uri('base/typography.css'), array('mallati-reset'), $ver);
    wp_enqueue_style('mallati-utilities', mallati_asset_uri('base/utilities.css'), array('mallati-typography'), $ver);
    wp_enqueue_style('mallati-rtl', mallati_asset_uri('base/rtl.css'), array('mallati-utilities'), $ver);
    wp_enqueue_style('mallati-fonts', 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap', array(), null);
    wp_enqueue_style('mallati-fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), null);

    wp_enqueue_style('mallati-header', mallati_asset_uri('components/header.css'), array('mallati-fontawesome'), $ver);
    wp_enqueue_style('mallati-footer', mallati_asset_uri('components/footer.css'), array('mallati-header'), $ver);
    wp_enqueue_style('mallati-hero', mallati_asset_uri('components/hero.css'), array('mallati-footer'), $ver);
    wp_enqueue_style('mallati-toggle', mallati_asset_uri('components/toggle.css'), array('mallati-hero'), $ver);
    wp_enqueue_style('mallati-indicators', mallati_asset_uri('components/indicators.css'), array('mallati-toggle'), $ver);
    wp_enqueue_style('mallati-products', mallati_asset_uri('components/products.css'), array('mallati-indicators'), $ver);
    wp_enqueue_style('mallati-buttons', mallati_asset_uri('components/buttons.css'), array('mallati-products'), $ver);
    wp_enqueue_style('mallati-forms', mallati_asset_uri('components/forms.css'), array('mallati-buttons'), $ver);
    wp_enqueue_style('mallati-cards', mallati_asset_uri('components/cards.css'), array('mallati-forms'), $ver);
    wp_enqueue_style('mallati-badges', mallati_asset_uri('components/badges.css'), array('mallati-cards'), $ver);
    wp_enqueue_style('mallati-breadcrumb', mallati_asset_uri('components/breadcrumb.css'), array('mallati-badges'), $ver);
    wp_enqueue_style('mallati-auth', mallati_asset_uri('components/auth.css'), array('mallati-breadcrumb'), $ver);
    wp_enqueue_style('mallati-empty', mallati_asset_uri('components/empty.css'), array('mallati-auth'), $ver);
    wp_enqueue_style('mallati-notices', mallati_asset_uri('components/notices.css'), array('mallati-empty'), $ver);

    wp_enqueue_script('mallati-init', mallati_asset_uri('js/y-app-init.js'), array(), $ver, true);
    wp_localize_script('mallati-init', 'mallatiData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'restUrl' => rest_url(),
        'assetsUrl' => mallati_asset_uri('assets'),
        'nonce' => wp_create_nonce('mallati_wishlist'),
        'myAccountUrl' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '',
    ));

    if (is_front_page()) {
        wp_enqueue_style('mallati-layout', mallati_asset_uri('templates/layout/layout.css'), array('mallati-empty'), $ver);
        wp_enqueue_script('mallati-hero', mallati_asset_uri('js/y-hero-slider.js'), array(), $ver, true);
        wp_enqueue_script('mallati-brands', mallati_asset_uri('js/brands-slider.js'), array(), $ver, true);
        wp_enqueue_script('mallati-wishlist', mallati_asset_uri('js/wishlist.js'), array('mallati-init'), $ver, true);
    }

    if (is_shop() || is_product_category()) {
        wp_enqueue_style('mallati-category', mallati_asset_uri('templates/category/category.css'), array('mallati-empty'), $ver);
        wp_enqueue_script('mallati-hero', mallati_asset_uri('js/y-hero-slider.js'), array(), $ver, true);
        wp_enqueue_script('mallati-brands', mallati_asset_uri('js/brands-slider.js'), array(), $ver, true);
        wp_enqueue_script('mallati-wishlist', mallati_asset_uri('js/wishlist.js'), array('mallati-init'), $ver, true);
    }

    if (is_product()) {
        wp_enqueue_style('mallati-product-details', mallati_asset_uri('templates/product-details/product-details.css'), array('mallati-empty'), $ver);
        wp_enqueue_script('mallati-quantity', mallati_asset_uri('js/quantity-buttons.js'), array(), $ver, true);
        wp_enqueue_script('mallati-wishlist', mallati_asset_uri('js/wishlist.js'), array('mallati-init'), $ver, true);
    }

    if (is_cart()) {
        wp_enqueue_style('mallati-cart', mallati_asset_uri('templates/cart/cart.css'), array('mallati-empty'), $ver);
        wp_enqueue_script('mallati-quantity', mallati_asset_uri('js/quantity-buttons.js'), array(), $ver, true);
        wp_enqueue_script('mallati-cart-quantity', mallati_asset_uri('js/cart-quantity-update.js'), array('mallati-quantity'), $ver, true);
        wp_enqueue_script('mallati-wishlist', mallati_asset_uri('js/wishlist.js'), array('mallati-init'), $ver, true);
    }

    if (is_checkout()) {
        wp_enqueue_style('mallati-payment', mallati_asset_uri('templates/payment/payment.css'), array('mallati-empty'), $ver);
        wp_enqueue_style('mallati-auth', mallati_asset_uri('components/auth.css'), array(), $ver);
        wp_enqueue_script('just-validate', 'https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js', array(), null, true);
        wp_enqueue_script('mallati-validation', mallati_asset_uri('js/validation.js'), array('just-validate'), $ver, true);
        wp_enqueue_script('mallati-payment', mallati_asset_uri('js/payment.js'), array(), $ver, true);
    }

    if (function_exists('is_order_received_page') && is_order_received_page()) {
        wp_enqueue_style('mallati-payment', mallati_asset_uri('templates/payment/payment.css'), array('mallati-empty'), $ver);
        wp_enqueue_script('mallati-payment', mallati_asset_uri('js/payment.js'), array(), $ver, true);
    }

    if (is_account_page()) {
        wp_enqueue_style('mallati-account', mallati_asset_uri('templates/account/account.css'), array('mallati-empty'), $ver);
        wp_enqueue_script('mallati-orders-modal', mallati_asset_uri('js/orders-modal.js'), array(), $ver, true);
    }

    if (is_page_template('page-templates/contact-us.php')) {
        wp_enqueue_style('mallati-contact', mallati_asset_uri('templates/contact/contact.css'), array('mallati-empty'), $ver);
        wp_enqueue_script('just-validate', 'https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js', array(), null, true);
        wp_enqueue_script('mallati-validation', mallati_asset_uri('js/validation.js'), array('just-validate'), $ver, true);
    }

    if (is_page_template('page-templates/about-us.php') || is_page_template('page-templates/policy.php')) {
        wp_enqueue_style('mallati-pages', mallati_asset_uri('templates/pages/pages.css'), array('mallati-empty'), $ver);
    }

    if (is_page_template('page-templates/favourites.php')) {
        wp_enqueue_style('mallati-category', mallati_asset_uri('templates/category/category.css'), array('mallati-empty'), $ver);
        wp_enqueue_style('mallati-favourite', mallati_asset_uri('templates/favourite/favourite.css'), array('mallati-category'), $ver);
        wp_enqueue_script('mallati-wishlist', mallati_asset_uri('js/wishlist.js'), array('mallati-init'), $ver, true);
    }

    if (is_page_template('page-templates/login.php') || is_page_template('page-templates/signup.php') || is_page_template('page-templates/forget-password.php')) {
        wp_enqueue_script('just-validate', 'https://unpkg.com/just-validate@latest/dist/just-validate.production.min.js', array(), null, true);
        wp_enqueue_script('mallati-validation', mallati_asset_uri('js/validation.js'), array('just-validate'), $ver, true);
    }

    if (is_404()) {
        wp_enqueue_style('mallati-404', mallati_asset_uri('templates/404/404.css'), array('mallati-empty', 'mallati-buttons'), $ver);
    }
}
add_action('wp_enqueue_scripts', 'mallati_enqueue_assets');

add_filter('woocommerce_enqueue_styles', '__return_empty_array');

function mallati_force_cart_checkout_content($content) {
    if (!function_exists('wc_get_page_id')) return $content;
    $cart_id = (int) wc_get_page_id('cart');
    $checkout_id = (int) wc_get_page_id('checkout');
    $current = (int) get_queried_object_id();
    if ($current === $cart_id && $cart_id > 0) return do_shortcode('[woocommerce_cart]');
    if ($current === $checkout_id && $checkout_id > 0) return do_shortcode('[woocommerce_checkout]');
    return $content;
}
add_filter('the_content', 'mallati_force_cart_checkout_content', 999);

add_filter('woocommerce_add_to_cart_fragments', function ($fragments) {
    if (!function_exists('WC')) return $fragments;
    $count = WC()->cart->get_cart_contents_count();
    $assets = get_template_directory_uri() . '/mallati/assets';
    $fragments['a.cart-link'] = '<a href="' . esc_url(wc_get_cart_url()) . '" class="cart-link"><img src="' . esc_url($assets . '/cart.svg') . '" alt="" /><span class="cart-count" data-cart-count="' . absint($count) . '">' . absint($count) . '</span></a>';
    return $fragments;
});

/**
 * تعريب رسائل السلة
 */
add_filter('wc_add_to_cart_message_html', function ($message, $products, $show_qty) {
    $titles = array();
    foreach ((array) $products as $product_id => $qty) {
        $name = get_the_title($product_id);
        $titles[] = ($show_qty && $qty > 1) ? (wc_stock_amount($qty) . ' × «' . wp_strip_all_tags($name) . '»') : ('«' . wp_strip_all_tags($name) . '»');
    }
    $items_list = wc_format_list_of_items($titles);
    $count = array_sum((array) $products);
    $added_text = $count > 1
        ? sprintf(/* translators: %s: product names */ __('تمت إضافة %s إلى السلة.', 'mallati-theme'), $items_list)
        : sprintf(/* translators: %s: product name */ __('تمت إضافة %s إلى السلة.', 'mallati-theme'), $items_list);
    $return_to = apply_filters('woocommerce_continue_shopping_redirect', wc_get_raw_referer() ? wp_validate_redirect(wc_get_raw_referer(), false) : wc_get_page_permalink('shop'));
    if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
        return sprintf('%s <a href="%s" class="button wc-forward">%s</a>', esc_html($added_text), esc_url($return_to), esc_html__('متابعة التسوق', 'mallati-theme'));
    }
    return sprintf('%s <a href="%s" class="button wc-forward">%s</a>', esc_html($added_text), esc_url(wc_get_cart_url()), esc_html__('عرض السلة', 'mallati-theme'));
}, 10, 3);

add_filter('gettext', function ($translated, $text, $domain) {
    if ('woocommerce' !== $domain) return $translated;
    $strings = array(
        'Cart updated.' => 'تم تحديث السلة.',
        'Subtotal' => 'إجمالي العناصر',
        'Shipping' => 'الشحن',
        'Total' => 'الإجمالي',
        'Place order' => 'تقديم الطلب',
        'Proceed to checkout' => 'متابعة الدفع',
        'Billing details' => 'معلومات التوصيل',
        'Billing &amp; Shipping' => 'معلومات التوصيل والشحن',
        'Additional information' => 'معلومات إضافية',
        'Order notes' => 'ملاحظات الطلب',
        'Notes about your order, e.g. special notes for delivery.' => 'ملاحظات حول طلبك، مثل ملاحظات خاصة للتوصيل.',
        'Create an account?' => 'إنشاء حساب؟',
        'Please fill in the required fields.' => 'يرجى ملء الحقول المطلوبة.',
        'Thank you. Your order has been received.' => 'شكراً لك. تم استلام طلبك.',
        'Default sorting' => 'ترتيب حسب',
        'Update cart' => 'تحديث السلة',
        'Order number:' => 'رقم الطلب:',
        'Date:' => 'التاريخ:',
        'Email:' => 'البريد:',
        'Payment method:' => 'طريقة الدفع:',
    );
    return isset($strings[$text]) ? $strings[$text] : $translated;
}, 20, 3);

add_filter('woocommerce_order_button_text', function () {
    return __('تقديم الطلب', 'mallati-theme');
});

add_filter('woocommerce_product_single_add_to_cart_text', function () {
    return __('أضف إلى السلة', 'mallati-theme');
});

/**
 * Allow account creation with medium-strength passwords.
 * This keeps confirmation validation while reducing signup friction.
 */
add_filter('woocommerce_min_password_strength', function () {
    return 1;
});

/**
 * Contact form handler
 */
function mallati_contact_form_handler() {
    if (!isset($_POST['mallati_contact_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['mallati_contact_nonce'])), 'mallati_contact')) {
        wp_safe_redirect(add_query_arg('contact', 'error', wp_get_referer() ?: home_url('/')));
        exit;
    }
    $name = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';
    $number = isset($_POST['number']) ? sanitize_text_field(wp_unslash($_POST['number'])) : '';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $message = isset($_POST['message']) ? sanitize_textarea_field(wp_unslash($_POST['message'])) : '';
    if (empty($name) || empty($email) || empty($message)) {
        wp_safe_redirect(add_query_arg('contact', 'error', wp_get_referer() ?: home_url('/')));
        exit;
    }
    $to = get_option('admin_email');
    $subject = sprintf('[%s] %s', get_bloginfo('name'), __('رسالة تواصل جديدة', 'mallati-theme'));
    $body = sprintf(
        "الاسم: %s\nرقم الجوال: %s\nالبريد: %s\n\n%s",
        $name,
        $number,
        $email,
        $message
    );
    $sent = wp_mail($to, $subject, $body, array('Content-Type: text/plain; charset=UTF-8'));
    wp_safe_redirect(add_query_arg('contact', $sent ? 'sent' : 'error', wp_get_referer() ?: home_url('/')));
    exit;
}
add_action('admin_post_nopriv_mallati_contact_form', 'mallati_contact_form_handler');
add_action('admin_post_mallati_contact_form', 'mallati_contact_form_handler');

/**
 * Contact form notices (query param)
 */
function mallati_contact_form_notices() {
    if (!isset($_GET['contact'])) return;
    $status = sanitize_text_field(wp_unslash($_GET['contact']));
    if ($status === 'sent') {
        if (function_exists('wc_add_notice')) {
            wc_add_notice(__('تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.', 'mallati-theme'), 'success');
        } else {
            set_transient('mallati_contact_notice', 'success', 30);
        }
    } elseif ($status === 'error') {
        if (function_exists('wc_add_notice')) {
            wc_add_notice(__('حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.', 'mallati-theme'), 'error');
        } else {
            set_transient('mallati_contact_notice', 'error', 30);
        }
    }
}
add_action('wp', 'mallati_contact_form_notices');

/**
 * Customizer: Contact & theme options
 */
function mallati_customize_register($wp_customize) {
    $wp_customize->add_section('mallati_contact', array(
        'title'    => __('معلومات التواصل', 'mallati-theme'),
        'priority' => 30,
    ));
    $wp_customize->add_setting('mallati_address', array('default' => 'الرياض، المملكة العربية السعودية', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_setting('mallati_email', array('default' => '', 'sanitize_callback' => 'sanitize_email'));
    $wp_customize->add_setting('mallati_phone', array('default' => '+966 50 000 0000', 'sanitize_callback' => 'sanitize_text_field'));
    $wp_customize->add_setting('mallati_map_embed', array('default' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3624.006470365758!2d46.675296!3d24.713551!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f042f4f9b7a23%3A0x9af0b5a24b!2sRiyadh!5e0!3m2!1sar!2ssa!4v1688570000000', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_setting('mallati_instagram', array('default' => '#', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_setting('mallati_facebook', array('default' => '#', 'sanitize_callback' => 'esc_url_raw'));
    $wp_customize->add_setting('mallati_snapchat', array('default' => '#', 'sanitize_callback' => 'esc_url_raw'));
    foreach (array('address' => 'العنوان', 'email' => 'البريد الإلكتروني', 'phone' => 'رقم الهاتف', 'map_embed' => 'رابط خريطة Google', 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'snapchat' => 'Snapchat') as $key => $label) {
        $wp_customize->add_control('mallati_' . $key, array(
            'label'   => $label,
            'section' => 'mallati_contact',
            'settings'=> 'mallati_' . $key,
            'type'    => $key === 'map_embed' ? 'textarea' : 'text',
        ));
    }
}
add_action('customize_register', 'mallati_customize_register');

/**
 * Dynamic colors from admin settings
 */
function mallati_output_dynamic_colors() {
    $header = get_theme_mod('mallati_color_header_bg', '');
    $footer = get_theme_mod('mallati_color_footer_bg', '');
    $add_cart = get_theme_mod('mallati_color_add_to_cart', '');
    $checkout = get_theme_mod('mallati_color_checkout_btn', '');
    $payment = get_theme_mod('mallati_color_payment_btn', '');
    $page_bg = get_theme_mod('mallati_color_page_bg', '');
    if (!$header && !$footer && !$add_cart && !$checkout && !$payment && !$page_bg) return;
    echo '<style id="mallati-dynamic-colors">';
    echo ':root{';
    if ($header) echo '--y-color-header-bg:' . esc_attr($header) . ';';
    if ($footer) echo '--y-color-footer-bg:' . esc_attr($footer) . ';';
    if ($add_cart) echo '--y-color-add-to-cart:' . esc_attr($add_cart) . ';';
    if ($checkout) echo '--y-color-checkout-btn:' . esc_attr($checkout) . ';';
    if ($payment) echo '--y-color-payment-btn:' . esc_attr($payment) . ';';
    if ($page_bg) echo '--y-color-page-bg:' . esc_attr($page_bg) . ';';
    echo '}';
    if ($header) echo '.header{background-color:var(--y-color-header-bg)!important;}';
    if ($footer) echo '.footer{background-color:var(--y-color-footer-bg)!important;}';
    if ($add_cart) echo '.product-add-to-cart,.y-c-btn--primary.add-to-cart{background-color:var(--y-color-add-to-cart)!important;border-color:var(--y-color-add-to-cart)!important;}';
    if ($page_bg) echo 'body{background-color:var(--y-color-page-bg)!important;}';
    echo '</style>';
}
add_action('wp_head', 'mallati_output_dynamic_colors');

/**
 * Yaamama Dashboard Bridge
 */
require_once get_template_directory() . '/inc/ydash-bridge.php';

/**
 * Admin Content Panel
 */
require_once get_template_directory() . '/inc/admin/admin-content.php';

/**
 * تخصيص حقول الدفع
 */
require_once get_template_directory() . '/inc/woocommerce-checkout-fields.php';

/**
 * المفضلة - AJAX
 */
add_action('wp_ajax_mallati_toggle_favourite', 'mallati_ajax_toggle_favourite');
add_action('wp_ajax_nopriv_mallati_toggle_favourite', 'mallati_ajax_toggle_favourite');
function mallati_ajax_toggle_favourite() {
    if (!wp_verify_nonce(isset($_POST['nonce']) ? sanitize_text_field(wp_unslash($_POST['nonce'])) : '', 'mallati_wishlist')) {
        wp_send_json_error(array('message' => __('خطأ في التحقق.', 'mallati-theme')));
    }
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('يرجى تسجيل الدخول لإضافة للمفضلة.', 'mallati-theme'), 'login' => true));
    }
    $product_id = isset($_POST['product_id']) ? absint($_POST['product_id']) : 0;
    if (!$product_id || !wc_get_product($product_id)) {
        wp_send_json_error(array('message' => __('منتج غير صالح.', 'mallati-theme')));
    }
    $user_id = get_current_user_id();
    $fav = (array) get_user_meta($user_id, 'mallati_favourites', true);
    $fav = array_map('intval', array_filter($fav));
    $idx = array_search($product_id, $fav, true);
    if (false !== $idx) {
        array_splice($fav, $idx, 1);
        $added = false;
    } else {
        $fav[] = $product_id;
        $added = true;
    }
    update_user_meta($user_id, 'mallati_favourites', $fav);
    wp_send_json_success(array('added' => $added, 'count' => count($fav)));
}
