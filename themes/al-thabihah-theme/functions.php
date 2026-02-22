<?php

if (!defined('ABSPATH')) {
    exit;
}

define('AL_THABIHAH_THEME_VERSION', '1.0.0');
require_once trailingslashit(get_template_directory()) . 'inc/admin-content.php';

function al_thabihah_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('html5', array('search-form', 'gallery', 'caption', 'style', 'script'));

    register_nav_menus(
        array(
            'primary' => __('Primary Menu', 'al-thabihah-theme'),
            'footer_pages' => __('Footer Pages', 'al-thabihah-theme'),
            'footer_policies' => __('Footer Policies', 'al-thabihah-theme'),
        )
    );
}
add_action('after_setup_theme', 'al_thabihah_theme_setup');

function al_thabihah_asset_uri($path) {
    return trailingslashit(get_template_directory_uri()) . ltrim($path, '/');
}

function al_thabihah_enqueue_assets() {
    $ver = AL_THABIHAH_THEME_VERSION;

    // Global base styles
    wp_enqueue_style('al-thabihah-tokens', al_thabihah_asset_uri('assets/css/tokens.css'), array(), $ver);
    wp_enqueue_style('al-thabihah-reset', al_thabihah_asset_uri('assets/css/base/reset.css'), array('al-thabihah-tokens'), $ver);
    wp_enqueue_style('al-thabihah-typography', al_thabihah_asset_uri('assets/css/base/typography.css'), array('al-thabihah-reset'), $ver);
    wp_enqueue_style('al-thabihah-utilities', al_thabihah_asset_uri('assets/css/base/utilities.css'), array('al-thabihah-typography'), $ver);
    wp_enqueue_style('al-thabihah-rtl', al_thabihah_asset_uri('assets/css/base/rtl.css'), array('al-thabihah-utilities'), $ver);
    wp_enqueue_style('al-thabihah-fontawesome', al_thabihah_asset_uri('al-thabihah/assets/fontawesome/css/all.min.css'), array('al-thabihah-rtl'), $ver);

    // Component styles
    wp_enqueue_style('al-thabihah-buttons', al_thabihah_asset_uri('assets/css/components/y-buttons.css'), array('al-thabihah-fontawesome'), $ver);
    wp_enqueue_style('al-thabihah-cards', al_thabihah_asset_uri('assets/css/components/y-cards.css'), array('al-thabihah-buttons'), $ver);
    wp_enqueue_style('al-thabihah-forms', al_thabihah_asset_uri('assets/css/components/y-forms.css'), array('al-thabihah-cards'), $ver);
    wp_enqueue_style('al-thabihah-header', al_thabihah_asset_uri('assets/css/components/y-header.css'), array('al-thabihah-forms'), $ver);
    wp_enqueue_style('al-thabihah-footer', al_thabihah_asset_uri('assets/css/components/y-footer.css'), array('al-thabihah-header'), $ver);
    wp_enqueue_style('al-thabihah-auth', al_thabihah_asset_uri('assets/css/components/y-auth.css'), array('al-thabihah-footer'), $ver);
    wp_enqueue_style('al-thabihah-popups', al_thabihah_asset_uri('assets/css/components/y-popups.css'), array('al-thabihah-auth'), $ver);
    wp_enqueue_style('al-thabihah-notices', al_thabihah_asset_uri('assets/css/components/y-notices.css'), array('al-thabihah-popups'), $ver);
    wp_enqueue_style('al-thabihah-theme-overrides', al_thabihah_asset_uri('assets/css/components/y-theme-overrides.css'), array('al-thabihah-notices'), $ver);

    // Shared scripts
    wp_enqueue_script('al-thabihah-shared', al_thabihah_asset_uri('assets/js/init/shared-components.js'), array(), $ver, true);
    wp_localize_script('al-thabihah-shared', 'alThabihahData', array(
        'assetsUrl' => al_thabihah_asset_uri('al-thabihah/assets'),
        'favoritesProductsUrl' => function_exists('rest_url') ? rest_url('al-thabihah/v1/favorites-products') : '',
    ));
    wp_enqueue_script('al-thabihah-validation', al_thabihah_asset_uri('assets/js/modules/global-validation.js'), array(), $ver, true);
    wp_enqueue_script('al-thabihah-favorites', al_thabihah_asset_uri('assets/js/modules/favorites.js'), array('al-thabihah-shared'), $ver, true);

    if (is_front_page()) {
        wp_enqueue_style('al-thabihah-home', al_thabihah_asset_uri('assets/css/pages/y-home.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-home', al_thabihah_asset_uri('assets/js/pages/home.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (is_shop()) {
        wp_enqueue_style('al-thabihah-store', al_thabihah_asset_uri('assets/css/pages/y-store.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-store', al_thabihah_asset_uri('assets/js/pages/store.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (is_product()) {
        wp_enqueue_style('al-thabihah-single-product', al_thabihah_asset_uri('assets/css/pages/y-single-product.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-single-product', al_thabihah_asset_uri('assets/js/pages/single-product.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (is_cart()) {
        wp_enqueue_style('al-thabihah-cart', al_thabihah_asset_uri('assets/css/pages/y-cart.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-cart', al_thabihah_asset_uri('assets/js/pages/cart.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (is_checkout()) {
        wp_enqueue_style('al-thabihah-payment', al_thabihah_asset_uri('assets/css/pages/y-payment.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-payment', al_thabihah_asset_uri('assets/js/pages/payment.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (function_exists('is_order_received_page') && is_order_received_page()) {
        wp_enqueue_style('al-thabihah-thankyou', al_thabihah_asset_uri('assets/css/pages/y-thank-you.css'), array('al_thabihah-popups'), $ver);
    }

    if (is_account_page() || is_page_template('page-templates/account.php')) {
        wp_enqueue_style('al-thabihah-account', al_thabihah_asset_uri('assets/css/pages/y-account.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-account', al_thabihah_asset_uri('assets/js/pages/account.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (is_page_template('page-templates/offers.php')) {
        wp_enqueue_style('al-thabihah-offers', al_thabihah_asset_uri('assets/css/pages/y-offers.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-offers', al_thabihah_asset_uri('assets/js/pages/offers.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (is_page_template('page-templates/contact-us.php')) {
        wp_enqueue_style('al-thabihah-contact', al_thabihah_asset_uri('assets/css/pages/y-contact-us.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-contact', al_thabihah_asset_uri('assets/js/pages/contact-us.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (is_page_template('page-templates/login.php')) {
        wp_enqueue_style('al-thabihah-login', al_thabihah_asset_uri('assets/css/pages/y-login.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-auth', al_thabihah_asset_uri('assets/js/pages/auth-forms.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (is_page_template('page-templates/signup.php')) {
        wp_enqueue_style('al-thabihah-signup', al_thabihah_asset_uri('assets/css/pages/y-signup.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-auth', al_thabihah_asset_uri('assets/js/pages/auth-forms.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (is_page_template('page-templates/pass-reset.php') || is_page_template('page-templates/reset-password.php')) {
        wp_enqueue_style('al-thabihah-pass-reset', al_thabihah_asset_uri('assets/css/pages/y-pass-reset.css'), array('al-thabihah-popups'), $ver);
        wp_enqueue_script('al-thabihah-auth', al_thabihah_asset_uri('assets/js/pages/auth-forms.js'), array('al-thabihah-shared'), $ver, true);
    }

    if (is_page_template('page-templates/about-us.php')) {
        wp_enqueue_style('al-thabihah-about', al_thabihah_asset_uri('assets/css/pages/y-about-us.css'), array('al-thabihah-popups'), $ver);
    }

    if (is_page_template('page-templates/privacy-policy.php')) {
        wp_enqueue_style('al-thabihah-policy', al_thabihah_asset_uri('assets/css/pages/y-privacy.css'), array('al-thabihah-popups'), $ver);
    }

    if (is_page_template('page-templates/replacement-policy.php')) {
        wp_enqueue_style('al-thabihah-replacement', al_thabihah_asset_uri('assets/css/pages/y-replacement.css'), array('al-thabihah-popups'), $ver);
    }

    if (is_page_template('page-templates/delivery-policy.php')) {
        wp_enqueue_style('al-thabihah-delivery', al_thabihah_asset_uri('assets/css/pages/y-deliver.css'), array('al-thabihah-popups'), $ver);
    }

    if (is_404()) {
        wp_enqueue_style('al-thabihah-404', al_thabihah_asset_uri('assets/css/pages/y-404.css'), array('al-thabihah-popups'), $ver);
    }
}
add_action('wp_enqueue_scripts', 'al_thabihah_enqueue_assets');

/**
 * Body class عند صفحة استلام الطلب – لعرض المودال وإخفاء عنوان الصفحة.
 */
function al_thabihah_order_success_body_class($classes) {
    if (function_exists('is_order_received_page') && is_order_received_page()) {
        global $wp;
        $order = false;
        if (!empty($wp->query_vars['order-received'])) {
            $order_id = absint($wp->query_vars['order-received']);
            if ($order_id) {
                $order = wc_get_order($order_id);
            }
        }
        if (!$order || !$order->has_status('failed')) {
            $classes[] = 'y-order-success-modal';
        }
    }
    return $classes;
}
add_filter('body_class', 'al_thabihah_order_success_body_class');

/**
 * إزالة جدول تفاصيل الطلب عند عرض مودال النجاح فقط.
 */
function al_thabihah_remove_thankyou_order_details() {
    if (function_exists('is_order_received_page') && is_order_received_page()) {
        remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 10);
    }
}
add_action('woocommerce_before_thankyou', 'al_thabihah_remove_thankyou_order_details', 1);

/**
 * Remove WooCommerce frontend styles on cart and checkout so theme/design CSS applies.
 */
function al_thabihah_remove_woocommerce_styles_on_cart_checkout($styles) {
    if (is_cart() || is_checkout()) {
        return array();
    }
    return $styles;
}
add_filter('woocommerce_enqueue_styles', 'al_thabihah_remove_woocommerce_styles_on_cart_checkout');

/**
 * إزالة قسم الكوبون من صفحة الدفع
 */
function al_thabihah_remove_checkout_coupon_form() {
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
}
add_action('init', 'al_thabihah_remove_checkout_coupon_form', 20);

/**
 * REST API: return product data for favorites page (by IDs). No plugin; theme-only.
 */
function al_thabihah_register_favorites_products_route() {
    register_rest_route('al-thabihah/v1', '/favorites-products', array(
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'args' => array(
            'ids' => array(
                'required' => true,
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ),
        ),
        'callback' => function ($request) {
            $ids_str = $request->get_param('ids');
            $ids = array_filter(array_map('absint', explode(',', $ids_str)));
            if (empty($ids)) {
                return rest_ensure_response(array());
            }
            $products = array();
            foreach ($ids as $id) {
                $product = wc_get_product($id);
                if (!$product || !$product->exists()) {
                    continue;
                }
                $image_id = $product->get_image_id();
                $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : al_thabihah_asset_uri('al-thabihah/assets/product.jpg');
                $products[] = array(
                    'id' => $product->get_id(),
                    'name' => $product->get_name(),
                    'permalink' => $product->get_permalink(),
                    'price' => (float) $product->get_price(),
                    'image' => $image_url,
                    'add_to_cart_url' => $product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock() ? $product->add_to_cart_url() : $product->get_permalink(),
                );
            }
            return rest_ensure_response($products);
        },
    ));
}
add_action('rest_api_init', 'al_thabihah_register_favorites_products_route');

/**
 * Force classic cart/checkout shortcode on cart and checkout pages so theme templates
 * (woocommerce/cart/cart.php, woocommerce/checkout/form-checkout.php) are used instead of Blocks.
 */
function al_thabihah_force_classic_cart_checkout_content($content) {
    if (!function_exists('wc_get_page_id')) {
        return $content;
    }
    $cart_page_id = (int) wc_get_page_id('cart');
    $checkout_page_id = (int) wc_get_page_id('checkout');
    $current_id = (int) get_queried_object_id();

    if ($current_id === $cart_page_id && $cart_page_id > 0) {
        return do_shortcode('[woocommerce_cart]');
    }
    if ($current_id === $checkout_page_id && $checkout_page_id > 0) {
        return do_shortcode('[woocommerce_checkout]');
    }
    return $content;
}
/* أولوية 999 حتى يعمل بعد wpautop (10) فلا يُضاف <p>/<br> لمخرجات السلة والدفع */
add_filter('the_content', 'al_thabihah_force_classic_cart_checkout_content', 999);

function al_thabihah_get_page_link($slug) {
    $slugs = array('login', 'register', 'signup', 'forgot-password', 'reset-password', 'pass-reset', 'account', 'my-account');
    if (!in_array($slug, $slugs, true)) {
        $page = get_page_by_path($slug);
        if ($page) {
            return get_permalink($page->ID);
        }
        return home_url('/' . $slug . '/');
    }
    $page = get_page_by_path($slug);
    if ($page) {
        return get_permalink($page->ID);
    }
    return home_url('/' . $slug . '/');
}

function al_thabihah_add_nav_link_class($atts, $item, $args) {
    if (isset($args->theme_location) && $args->theme_location === 'primary') {
        $atts['class'] = trim(($atts['class'] ?? '') . ' y-c-nav-link');
    }
    return $atts;
}
add_filter('nav_menu_link_attributes', 'al_thabihah_add_nav_link_class', 10, 3);

function al_thabihah_cart_count_fragment($fragments) {
    if (!function_exists('WC')) {
        return $fragments;
    }
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    $show_class = $count > 0 ? ' y-c-cart-badge--show' : '';
    $fragments['.y-c-cart-badge'] = '<span class="y-c-cart-badge' . esc_attr($show_class) . '" data-y="cart-badge">' . esc_html($count) . '</span>';
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'al_thabihah_cart_count_fragment');

function al_thabihah_handle_contact_form() {
    if (!isset($_POST['al_thabihah_contact_nonce']) || !wp_verify_nonce($_POST['al_thabihah_contact_nonce'], 'al_thabihah_contact')) {
        wp_safe_redirect(add_query_arg('contact', 'invalid', wp_get_referer()));
        exit;
    }

    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');

    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        wp_safe_redirect(add_query_arg('contact', 'error', wp_get_referer()));
        exit;
    }

    $contact_settings = wp_parse_args(al_thabihah_get_option('al_thabihah_contact_settings', array()), al_thabihah_default_contact_settings());
    $admin_email = !empty($contact_settings['email']) ? $contact_settings['email'] : get_option('admin_email');
    $subject = 'رسالة تواصل جديدة - الذبيحة';
    $body = "الاسم: {$name}\nالبريد الإلكتروني: {$email}\nالهاتف: {$phone}\n\nالرسالة:\n{$message}";
    $headers = array('Reply-To: ' . $email);

    wp_mail($admin_email, $subject, $body, $headers);

    wp_safe_redirect(add_query_arg('contact', 'success', wp_get_referer()));
    exit;
}
add_action('admin_post_nopriv_al_thabihah_contact', 'al_thabihah_handle_contact_form');
add_action('admin_post_al_thabihah_contact', 'al_thabihah_handle_contact_form');

function al_thabihah_handle_profile_update() {
    if (!is_user_logged_in()) {
        wp_safe_redirect(al_thabihah_get_page_link('login'));
        exit;
    }

    if (!isset($_POST['al_thabihah_profile_nonce']) || !wp_verify_nonce($_POST['al_thabihah_profile_nonce'], 'al_thabihah_profile')) {
        wp_safe_redirect(add_query_arg('profile', 'invalid', wp_get_referer()));
        exit;
    }

    $user_id = get_current_user_id();
    $first_last = sanitize_text_field($_POST['firstName'] ?? '');
    $phone = sanitize_text_field($_POST['phone'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $address = sanitize_text_field($_POST['address'] ?? '');

    if (!empty($email)) {
        wp_update_user(array('ID' => $user_id, 'user_email' => $email));
    }

    if (!empty($first_last)) {
        $parts = explode(' ', $first_last, 2);
        $first = $parts[0];
        $last = $parts[1] ?? '';
        wp_update_user(array('ID' => $user_id, 'first_name' => $first, 'last_name' => $last));
    }

    if (!empty($phone)) {
        update_user_meta($user_id, 'billing_phone', $phone);
    }

    if (!empty($address)) {
        update_user_meta($user_id, 'billing_address_1', $address);
    }

    $current_password = $_POST['currentPassword'] ?? '';
    $new_password = $_POST['newPassword'] ?? '';
    $confirm_password = $_POST['confirmPassword'] ?? '';

    if ($new_password && $new_password === $confirm_password) {
        $user = wp_get_current_user();
        if ($user && wp_check_password($current_password, $user->user_pass, $user_id)) {
            wp_set_password($new_password, $user_id);
        }
    }

    wp_safe_redirect(add_query_arg('profile', 'success', wp_get_referer()));
    exit;
}
add_action('admin_post_al_thabihah_profile_update', 'al_thabihah_handle_profile_update');

function al_thabihah_handle_address_update() {
    if (!is_user_logged_in()) {
        wp_safe_redirect(al_thabihah_get_page_link('login'));
        exit;
    }

    if (!isset($_POST['al_thabihah_address_nonce']) || !wp_verify_nonce($_POST['al_thabihah_address_nonce'], 'al_thabihah_address')) {
        wp_safe_redirect(add_query_arg('address', 'invalid', wp_get_referer()));
        exit;
    }

    $user_id = get_current_user_id();
    $first = sanitize_text_field($_POST['firstName'] ?? '');
    $last = sanitize_text_field($_POST['lastName'] ?? '');
    $street = sanitize_text_field($_POST['street'] ?? '');
    $district = sanitize_text_field($_POST['district'] ?? '');
    $city = sanitize_text_field($_POST['city'] ?? '');
    $region = sanitize_text_field($_POST['region'] ?? '');
    $postal = sanitize_text_field($_POST['postalCode'] ?? '');
    $building = sanitize_text_field($_POST['buildingNo'] ?? '');
    $unit = sanitize_text_field($_POST['unitNo'] ?? '');

    update_user_meta($user_id, 'billing_first_name', $first);
    update_user_meta($user_id, 'billing_last_name', $last);
    update_user_meta($user_id, 'billing_address_1', $street);
    update_user_meta($user_id, 'billing_address_2', trim($building . ' ' . $unit));
    update_user_meta($user_id, 'billing_city', $city);
    update_user_meta($user_id, 'billing_state', $region);
    update_user_meta($user_id, 'billing_postcode', $postal);
    update_user_meta($user_id, 'billing_country', 'SA');

    update_user_meta($user_id, 'shipping_first_name', $first);
    update_user_meta($user_id, 'shipping_last_name', $last);
    update_user_meta($user_id, 'shipping_address_1', $street);
    update_user_meta($user_id, 'shipping_address_2', trim($building . ' ' . $unit));
    update_user_meta($user_id, 'shipping_city', $city);
    update_user_meta($user_id, 'shipping_state', $region);
    update_user_meta($user_id, 'shipping_postcode', $postal);
    update_user_meta($user_id, 'shipping_country', 'SA');

    wp_safe_redirect(add_query_arg('address', 'success', wp_get_referer()));
    exit;
}
add_action('admin_post_al_thabihah_address_update', 'al_thabihah_handle_address_update');

function al_thabihah_render_product_card($product) {
    if (!$product) {
        return;
    }

    $id = $product->get_id();
    $name = $product->get_name();
    $truncated = mb_strlen($name) > 20 ? mb_substr($name, 0, 20) . '...' : $name;
    $image_id = $product->get_image_id();
    $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'medium') : al_thabihah_asset_uri('al-thabihah/assets/product.jpg');

    $is_on_sale = $product->is_on_sale();
    $regular_price = (float) $product->get_regular_price();
    $sale_price = (float) $product->get_sale_price();
    $discount = $is_on_sale && $regular_price > 0 ? round((1 - ($sale_price / $regular_price)) * 100) : 0;

    $button_classes = 'y-c-outline-btn y-c-add-to-cart';
    $button_url = get_permalink($id);
    $button_attrs = '';

    if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) {
        $button_url = $product->add_to_cart_url();
        $button_classes .= ' add_to_cart_button ajax_add_to_cart';
        $button_attrs = ' data-quantity="1" data-product_id="' . esc_attr($id) . '"';
    }
    ?>
    <li class="y-c-product-card" data-y="product-card-<?php echo esc_attr($id); ?>">
        <button class="y-c-favorite-btn" data-product-id="<?php echo esc_attr($id); ?>" data-y="product-favorite-<?php echo esc_attr($id); ?>">
            <i class="far fa-heart" data-y="favorite-icon-<?php echo esc_attr($id); ?>"></i>
        </button>

        <a href="<?php echo esc_url(get_permalink($id)); ?>" class="y-c-card-link" data-y="product-link-<?php echo esc_attr($id); ?>">
            <div class="y-c-product-image-container" data-y="product-image-container-<?php echo esc_attr($id); ?>">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($name); ?>" class="y-c-product-image" loading="lazy" data-y="product-image-<?php echo esc_attr($id); ?>">
            </div>
        </a>

        <div class="y-c-product-info" data-y="product-info-<?php echo esc_attr($id); ?>">
            <h3 class="y-c-product-title" title="<?php echo esc_attr($name); ?>" data-y="product-title-<?php echo esc_attr($id); ?>">
                <?php echo esc_html($truncated); ?>
            </h3>

            <?php if ($is_on_sale && $regular_price > 0) : ?>
                <div class="y-c-product-price" data-y="product-price-container-<?php echo esc_attr($id); ?>">
                    <span class="y-c-old-price" data-y="product-old-price-<?php echo esc_attr($id); ?>"><?php echo esc_html(number_format_i18n($regular_price, 0)); ?></span>
                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin-sale.png')); ?>" class="y-c-coin-icon" alt="">
                    <span class="y-c-price-amount" data-y="product-price-amount-<?php echo esc_attr($id); ?>"><?php echo esc_html(number_format_i18n($sale_price, 0)); ?></span>
                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin-red.png')); ?>" class="y-c-coin-icon" alt="">
                    <span class="y-c-discount-text" data-y="product-discount-text-<?php echo esc_attr($id); ?>">خصم <?php echo esc_html($discount); ?>%</span>
                </div>
            <?php else : ?>
                <div class="y-c-product-price" data-y="product-price-container-<?php echo esc_attr($id); ?>">
                    <span class="y-c-price-amount" data-y="product-price-amount-<?php echo esc_attr($id); ?>"><?php echo esc_html(number_format_i18n((float) $product->get_price(), 0)); ?></span>
                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-coin-icon" alt="">
                </div>
            <?php endif; ?>

            <a href="<?php echo esc_url($button_url); ?>" class="<?php echo esc_attr($button_classes); ?>"<?php echo $button_attrs; ?> data-y="product-add-to-cart-<?php echo esc_attr($id); ?>">
                اضف للسلة
                <i class="fas fa-shopping-cart" data-y="cart-icon-<?php echo esc_attr($id); ?>"></i>
            </a>
        </div>
    </li>
    <?php
}

function al_thabihah_get_order_status_badge($order) {
    $status = $order ? $order->get_status() : '';
    $map = array(
        'completed' => array('تم التوصيل', 'y-c-status-delivered'),
        'processing' => array('قيد التجهيز', 'y-c-status-pending'),
        'on-hold' => array('قيد الانتظار', 'y-c-status-pending'),
        'pending' => array('لم يصل بعد', 'y-c-status-pending'),
        'cancelled' => array('ملغى', 'y-c-status-cancelled'),
        'refunded' => array('مسترجع', 'y-c-status-cancelled'),
        'failed' => array('فشل', 'y-c-status-cancelled'),
    );
    return $map[$status] ?? array($status, 'y-c-status-pending');
}

function al_thabihah_adjust_checkout_fields($fields) {
    if (isset($fields['billing']['billing_last_name'])) {
        $fields['billing']['billing_last_name']['required'] = false;
    }
    if (isset($fields['billing']['billing_state'])) {
        $fields['billing']['billing_state']['required'] = false;
    }
    if (isset($fields['billing']['billing_city'])) {
        $fields['billing']['billing_city']['required'] = false;
    }
    if (isset($fields['billing']['billing_postcode'])) {
        $fields['billing']['billing_postcode']['required'] = false;
    }
    if (isset($fields['billing']['billing_country'])) {
        $fields['billing']['billing_country']['required'] = false;
    }
    return $fields;
}

/**
 * Fill billing_state from customer/account when empty – use account address or allow checkout to succeed.
 */
function al_thabihah_checkout_fill_billing_state($data) {
    if (empty($data['billing_state']) && function_exists('WC') && WC()->customer) {
        $from_customer = WC()->customer->get_billing_state();
        if (!empty($from_customer)) {
            $data['billing_state'] = $from_customer;
        }
    }
    return $data;
}
add_filter('woocommerce_checkout_posted_data', 'al_thabihah_checkout_fill_billing_state');
add_filter('woocommerce_checkout_fields', 'al_thabihah_adjust_checkout_fields');

function al_thabihah_output_css_vars() {
    $defaults = al_thabihah_default_colors();
    $colors = wp_parse_args(al_thabihah_get_option('al_thabihah_site_colors', array()), $defaults);
    $home_settings = wp_parse_args(al_thabihah_get_option('al_thabihah_home_settings', array()), al_thabihah_default_home_settings());
    $hero_url = $home_settings['hero_image_id'] ? wp_get_attachment_url($home_settings['hero_image_id']) : al_thabihah_asset_uri('al-thabihah/assets/hero.jpg');
    ?>
    <style>
        :root {
            --y-header-bg: <?php echo esc_html($colors['header_color']); ?>;
            --y-footer-bg: <?php echo esc_html($colors['footer_color']); ?>;
            --y-add-to-cart-bg: <?php echo esc_html($colors['add_to_cart_color']); ?>;
            --y-add-to-cart-border: <?php echo esc_html($colors['add_to_cart_color']); ?>;
            --y-add-to-cart-text: #ffffff;
            --y-checkout-bg: <?php echo esc_html($colors['checkout_color']); ?>;
            --y-checkout-border: <?php echo esc_html($colors['checkout_color']); ?>;
            --y-checkout-text: #ffffff;
            --y-payment-bg: <?php echo esc_html($colors['payment_color']); ?>;
            --y-payment-border: <?php echo esc_html($colors['payment_color']); ?>;
            --y-payment-text: #ffffff;
            --y-page-bg: <?php echo esc_html($colors['page_background']); ?>;
            --y-home-hero-image: url('<?php echo esc_url($hero_url); ?>');
        }
    </style>
    <?php
}
add_action('wp_head', 'al_thabihah_output_css_vars');

function al_thabihah_ensure_my_account_page_id() {
    if (!function_exists('wc_get_page_id')) {
        return;
    }
    $my_id = wc_get_page_id('myaccount');
    if ($my_id > 0) {
        update_post_meta($my_id, '_wp_page_template', 'page-templates/account.php');
        return;
    }
    $page = get_page_by_path('my-account');
    if (!$page) {
        $page = get_page_by_path('account');
    }
    if ($page) {
        update_option('woocommerce_myaccount_page_id', $page->ID);
        update_post_meta($page->ID, '_wp_page_template', 'page-templates/account.php');
    }
}
add_action('init', 'al_thabihah_ensure_my_account_page_id', 20);

function al_thabihah_ensure_auth_pages() {
    $pages = array(
        array('title' => 'تسجيل', 'slug' => 'register', 'template' => 'page-templates/signup.php'),
        array('title' => 'نسيت كلمة المرور', 'slug' => 'forgot-password', 'template' => 'page-templates/pass-reset.php'),
        array('title' => 'إعادة تعيين كلمة المرور', 'slug' => 'reset-password', 'template' => 'page-templates/reset-password.php'),
    );
    foreach ($pages as $p) {
        $existing = get_page_by_path($p['slug']);
        if (!$existing) {
            $page_id = wp_insert_post(array(
                'post_title' => $p['title'],
                'post_name' => $p['slug'],
                'post_status' => 'publish',
                'post_type' => 'page',
            ));
            if ($page_id && !is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', $p['template']);
            }
        } else {
            update_post_meta($existing->ID, '_wp_page_template', $p['template']);
        }
    }
}
add_action('init', 'al_thabihah_ensure_auth_pages', 15);

function al_thabihah_woocommerce_notice_arabic($message) {
    $replacements = array(
        'Invalid username or email.' => 'اسم المستخدم أو البريد الإلكتروني غير صحيح.',
        'Password is required.' => 'كلمة المرور مطلوبة.',
        'A user could not be found with this email address.' => 'لم يتم العثور على مستخدم بهذا البريد الإلكتروني.',
        'Invalid email address.' => 'البريد الإلكتروني غير صحيح.',
        'Please enter a valid account username.' => 'يرجى إدخال اسم مستخدم صحيح.',
        'An account is already registered with your email address.' => 'يوجد حساب مسجل بالفعل بهذا البريد الإلكتروني.',
        'Your account was created successfully' => 'تم إنشاء حسابك بنجاح',
        'Your password has been reset successfully.' => 'تم إعادة تعيين كلمة المرور بنجاح.',
        'Account details changed successfully.' => 'تم تحديث بيانات الحساب بنجاح.',
        'New passwords do not match.' => 'كلمتا المرور الجديدتان غير متطابقتين.',
        'Please enter your password.' => 'يرجى إدخال كلمة المرور.',
        'Please fill out all password fields.' => 'يرجى تعبئة جميع حقول كلمة المرور.',
        'Please re-enter your password.' => 'يرجى إعادة إدخال كلمة المرور.',
        'Your current password is incorrect.' => 'كلمة المرور الحالية غير صحيحة.',
        'This email address is already registered.' => 'هذا البريد الإلكتروني مسجل مسبقاً.',
        'Please provide a valid email address.' => 'يرجى إدخال بريد إلكتروني صحيح.',
        /* Checkout & Cart – رسائل الدفع والسلة */
        'Billing State / County is a required field.' => 'يمكنك إكمال الطلب بدون إدخال المنطقة.',
        'Billing State / County' => 'المنطقة',
        'Billing State' => 'المنطقة',
        'is a required field.' => 'حقل مطلوب.',
        'Please enter an address to continue.' => 'يرجى إدخال العنوان للمتابعة.',
        'Please read and accept the terms and conditions to proceed with your order.' => 'يرجى قراءة والشروط والأحكام وقبولها لمتابعة طلبك.',
        'Invalid payment method.' => 'طريقة الدفع غير صحيحة.',
        'No shipping method has been selected. Please double check your address, or contact us if you need any help.' => 'لم يتم اختيار طريقة شحن. يرجى التأكد من العنوان أو التواصل معنا للمساعدة.',
        'Please enter one of the following:' => 'يرجى إدخال إحدى القيم التالية:',
        'is not valid.' => 'غير صحيح.',
        'Product no longer exists.' => 'المنتج لم يعد متوفراً.',
        'Sorry, this product is unavailable.' => 'عذراً، هذا المنتج غير متوفر.',
        'is a required field' => 'حقل مطلوب',
        'are required fields' => 'حقول مطلوبة',
        'Sorry, we do not ship' => 'عذراً، لا نقوم بالتوصيل إلى',
        'Please enter an alternative shipping address.' => 'يرجى إدخال عنوان توصيل بديل.',
        'Your cart is currently empty.' => 'سلة المشتريات فارغة.',
        'Please enter a valid postcode / ZIP.' => 'يرجى إدخال رمز بريدي صحيح.',
        'is not a valid email address.' => 'غير صحيح كبريد إلكتروني.',
    );
    foreach ($replacements as $en => $ar) {
        if (strpos($message, $en) !== false) {
            return str_replace($en, $ar, $message);
        }
    }
    return $message;
}
add_filter('woocommerce_add_message', 'al_thabihah_woocommerce_notice_arabic', 10, 1);
add_filter('woocommerce_add_error', 'al_thabihah_woocommerce_notice_arabic', 10, 1);
add_filter('woocommerce_add_notice', 'al_thabihah_woocommerce_notice_arabic', 10, 1);

/**
 * WooCommerce: استبدال "View cart" بـ "عرض السلة" في الإشعارات وزر إضافة للسلة.
 */
function al_thabihah_view_cart_arabic($translated, $text, $domain) {
    if ('woocommerce' === $domain && 'View cart' === $text) {
        return 'عرض السلة';
    }
    return $translated;
}
add_filter('gettext', 'al_thabihah_view_cart_arabic', 10, 3);

/**
 * ترجمة رسائل التحقق في الدفع والسلة إلى العربية.
 */
function al_thabihah_woocommerce_checkout_validation_arabic($translated, $text, $domain) {
    if ('woocommerce' !== $domain) {
        return $translated;
    }
    $replacements = array(
        '%s is a required field.' => '%s حقل مطلوب.',
        '%s are required fields' => '%s حقول مطلوبة',
        '%s is not a valid email address.' => '%s غير صحيح كبريد إلكتروني.',
        '%s is not valid. Please enter one of the following: %2$s' => '%s غير صحيح. يرجى إدخال إحدى القيم التالية: %2$s',
        'Please enter an address to continue.' => 'يرجى إدخال العنوان للمتابعة.',
        'Please read and accept the terms and conditions to proceed with your order.' => 'يرجى قراءة الشروط والأحكام وقبولها لمتابعة طلبك.',
        'Invalid payment method.' => 'طريقة الدفع غير صحيحة.',
        'No shipping method has been selected. Please double check your address, or contact us if you need any help.' => 'لم يتم اختيار طريقة شحن. يرجى التأكد من العنوان أو التواصل معنا للمساعدة.',
        'Unfortunately <strong>we do not ship %s</strong>. Please enter an alternative shipping address.' => 'عذراً، لا نقوم بالتوصيل إلى %s. يرجى إدخال عنوان بديل.',
        'Order received' => 'تم استلام الطلب',
        'Billing State / County' => 'المنطقة',
        'Billing State' => 'المنطقة',
        'Billing First Name' => 'الاسم',
        'Billing Last Name' => 'اسم العائلة',
        'Billing Company' => 'اسم الشركة',
        'Billing Address' => 'العنوان',
        'Billing City' => 'المدينة',
        'Billing Postcode / ZIP' => 'الرمز البريدي',
        'Billing Country' => 'الدولة',
        'Billing Phone' => 'رقم الجوال',
        'Billing Email' => 'البريد الإلكتروني',
    );
    foreach ($replacements as $en => $ar) {
        if ($text === $en) {
            return $ar;
        }
    }
    return $translated;
}
add_filter('gettext', 'al_thabihah_woocommerce_checkout_validation_arabic', 11, 3);

/**
 * ترجمة عناوين طرق الدفع إلى العربية (لصفحة الدفع).
 */
/**
 * Whether the payment gateway is a card gateway (show card icons: mada, visa, mastercard).
 */
function al_thabihah_is_card_gateway($gateway_id) {
    $card_ids = array('stripe', 'woocommerce_stripe', 'mada', 'cc', 'cod_stripe');
    return in_array($gateway_id, $card_ids, true)
        || strpos($gateway_id, 'stripe') !== false
        || strpos($gateway_id, 'mada') !== false
        || strpos($gateway_id, 'card') !== false;
}

function al_thabihah_payment_gateway_title($title, $gateway_id = '') {
    $map = array(
        'Cash on delivery' => 'الدفع عند الاستلام',
        'COD' => 'الدفع عند الاستلام',
        'Direct bank transfer' => 'تحويل بنكي',
        'Bank transfer' => 'تحويل بنكي',
        'BACS' => 'تحويل بنكي',
        'Check payments' => 'الدفع بالشيك',
        'Cheque' => 'الدفع بالشيك',
        'PayPal' => 'باي بال',
        'Credit card' => 'بطاقة ائتمان',
        'Credit Card' => 'بطاقة ائتمان',
        'Stripe' => 'سترايب',
        'Mada' => 'مدى',
    );
    $title_trimmed = trim($title);
    return isset($map[$title_trimmed]) ? $map[$title_trimmed] : $title;
}

function al_thabihah_create_theme_pages() {
    $pages = array(
        array('title' => 'العروض', 'slug' => 'offers', 'template' => 'page-templates/offers.php'),
        array('title' => 'تواصل معنا', 'slug' => 'contact-us', 'template' => 'page-templates/contact-us.php'),
        array('title' => 'من نحن', 'slug' => 'about-us', 'template' => 'page-templates/about-us.php'),
        array('title' => 'سياسة الخصوصية', 'slug' => 'privacy-policy', 'template' => 'page-templates/privacy-policy.php'),
        array('title' => 'سياسة الاسترجاع', 'slug' => 'replacement-policy', 'template' => 'page-templates/replacement-policy.php'),
        array('title' => 'سياسة الشحن', 'slug' => 'delivery-policy', 'template' => 'page-templates/delivery-policy.php'),
        array('title' => 'تسجيل الدخول', 'slug' => 'login', 'template' => 'page-templates/login.php'),
        array('title' => 'حساب جديد', 'slug' => 'signup', 'template' => 'page-templates/signup.php'),
        array('title' => 'تسجيل', 'slug' => 'register', 'template' => 'page-templates/signup.php'),
        array('title' => 'استعادة كلمة المرور', 'slug' => 'pass-reset', 'template' => 'page-templates/pass-reset.php'),
        array('title' => 'نسيت كلمة المرور', 'slug' => 'forgot-password', 'template' => 'page-templates/pass-reset.php'),
        array('title' => 'إعادة تعيين كلمة المرور', 'slug' => 'reset-password', 'template' => 'page-templates/reset-password.php'),
        array('title' => 'حسابي', 'slug' => 'account', 'template' => 'page-templates/account.php'),
        array('title' => 'حسابي', 'slug' => 'my-account', 'template' => 'page-templates/account.php'),
    );

    $my_account_page_id = 0;
    foreach ($pages as $page) {
        $existing = get_page_by_path($page['slug']);
        if (!$existing) {
            $page_id = wp_insert_post(
                array(
                    'post_title' => $page['title'],
                    'post_name' => $page['slug'],
                    'post_status' => 'publish',
                    'post_type' => 'page',
                )
            );
            if ($page_id && !is_wp_error($page_id)) {
                update_post_meta($page_id, '_wp_page_template', $page['template']);
                if ($page['slug'] === 'my-account') {
                    $my_account_page_id = $page_id;
                }
            }
        } else {
            update_post_meta($existing->ID, '_wp_page_template', $page['template']);
            if ($page['slug'] === 'my-account') {
                $my_account_page_id = $existing->ID;
            }
        }
    }
    if ($my_account_page_id && function_exists('WC')) {
        update_option('woocommerce_myaccount_page_id', $my_account_page_id);
    }
}
add_action('after_switch_theme', 'al_thabihah_create_theme_pages');

/**
 * Auth + My Account: redirects and URL overrides
 */
function al_thabihah_lostpassword_url($url) {
    return al_thabihah_get_page_link('forgot-password');
}
add_filter('lostpassword_url', 'al_thabihah_lostpassword_url', 20, 1);

function al_thabihah_woocommerce_get_endpoint_url($url, $endpoint, $value, $permalink) {
    $lost_endpoint = get_option('woocommerce_myaccount_lost_password_endpoint', 'lost-password');
    if ($endpoint === $lost_endpoint) {
        return al_thabihah_get_page_link('forgot-password');
    }
    return $url;
}
add_filter('woocommerce_get_endpoint_url', 'al_thabihah_woocommerce_get_endpoint_url', 10, 4);

function al_thabihah_woocommerce_login_redirect($redirect) {
    if (empty($redirect) || $redirect === wc_get_page_permalink('myaccount')) {
        return al_thabihah_get_page_link('my-account');
    }
    return $redirect;
}
add_filter('woocommerce_login_redirect', 'al_thabihah_woocommerce_login_redirect', 10, 1);

function al_thabihah_woocommerce_registration_redirect($redirect) {
    return al_thabihah_get_page_link('my-account');
}
add_filter('woocommerce_registration_redirect', 'al_thabihah_woocommerce_registration_redirect', 10, 1);

function al_thabihah_woocommerce_logout_redirect($redirect) {
    return al_thabihah_get_page_link('login');
}
add_filter('woocommerce_logout_redirect', 'al_thabihah_woocommerce_logout_redirect', 10, 1);

function al_thabihah_redirect_guest_my_account() {
    if (!function_exists('wc_get_page_id')) {
        return;
    }
    $my_account_id = wc_get_page_id('myaccount');
    $login_page = get_page_by_path('login');
    $register_page = get_page_by_path('register');
    $my_account_page = get_page_by_path('my-account');
    if (!$my_account_page) {
        $my_account_page = get_page_by_path('account');
    }
    $is_login = $login_page && is_page($login_page->ID);
    $is_register = $register_page && is_page($register_page->ID);
    $is_my_account = ($my_account_page && is_page($my_account_page->ID)) || (function_exists('is_account_page') && is_account_page());

    if (is_user_logged_in() && ($is_login || $is_register)) {
        wp_safe_redirect(al_thabihah_get_page_link('my-account'));
        exit;
    }
    if (!is_user_logged_in() && $is_my_account) {
        wp_safe_redirect(add_query_arg('redirect_to', urlencode(al_thabihah_get_page_link('my-account')), al_thabihah_get_page_link('login')));
        exit;
    }
}
add_action('template_redirect', 'al_thabihah_redirect_guest_my_account', 5);

function al_thabihah_redirect_wc_lost_password_to_custom() {
    if (!function_exists('is_account_page') || !is_account_page()) {
        return;
    }
    global $wp;
    $lost_endpoint = get_option('woocommerce_myaccount_lost_password_endpoint', 'lost-password');
    if (empty($wp->query_vars[$lost_endpoint])) {
        return;
    }
    $to = al_thabihah_get_page_link('forgot-password');
    if (isset($_GET['reset-link-sent'])) {
        $to = add_query_arg('reset-link-sent', sanitize_text_field(wp_unslash($_GET['reset-link-sent'])), $to);
    }
    wp_safe_redirect($to);
    exit;
}
add_action('template_redirect', 'al_thabihah_redirect_wc_lost_password_to_custom', 1);
