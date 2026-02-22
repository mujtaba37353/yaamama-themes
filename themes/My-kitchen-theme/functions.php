<?php

if (!defined('ABSPATH')) {
    exit;
}

define('MYK_THEME_URI', get_template_directory_uri());
define('MYK_ASSETS_URI', MYK_THEME_URI . '/my-kitchen');

function mykitchen_setup_theme(): void
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');

    register_nav_menus(
        array(
            'primary' => __('Primary Menu', 'my-kitchen'),
            'footer' => __('Footer Menu', 'my-kitchen'),
        )
    );
}
add_action('after_setup_theme', 'mykitchen_setup_theme');

function mykitchen_enqueue_assets(): void
{
    wp_enqueue_style(
        'mykitchen-fontawesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css',
        array(),
        '6.5.0'
    );

    wp_enqueue_style(
        'mykitchen-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Gulzar&display=swap',
        array(),
        null
    );

    wp_enqueue_style('mykitchen-reset', MYK_ASSETS_URI . '/base/reset.css', array(), '1.0.0');
    wp_enqueue_style('mykitchen-tokens', MYK_ASSETS_URI . '/base/tokens.css', array('mykitchen-reset'), '1.0.0');
    wp_enqueue_style('mykitchen-typography', MYK_ASSETS_URI . '/base/typography.css', array('mykitchen-tokens'), '1.0.0');
    wp_enqueue_style('mykitchen-utilities', MYK_ASSETS_URI . '/base/utilities.css', array('mykitchen-typography'), '1.0.0');

    $site_settings = mykitchen_get_site_settings();
    $inline_tokens = ':root{'
        . '--y-main:' . esc_html($site_settings['main_color']) . ';'
        . '--y-secondary:' . esc_html($site_settings['secondary_color']) . ';'
        . '--y-color-bg:' . esc_html($site_settings['background_color']) . ';'
        . '}';
    wp_add_inline_style('mykitchen-tokens', $inline_tokens);
    wp_add_inline_style(
        'mykitchen-reset',
        'body{background-color:' . esc_html($site_settings['body_background']) . ';}'
    );

    wp_enqueue_style('mykitchen-navbar', MYK_ASSETS_URI . '/components/layout/y-c-navbar.css', array('mykitchen-utilities'), '1.0.0');
    wp_enqueue_style('mykitchen-footer', MYK_ASSETS_URI . '/components/layout/y-c-footer.css', array('mykitchen-utilities'), '1.0.0');
    wp_enqueue_style('mykitchen-buttons', MYK_ASSETS_URI . '/components/buttons/y-c-btn.css', array('mykitchen-utilities'), '1.0.0');
    $product_card_path = get_template_directory() . '/my-kitchen/components/cards/y-c-product-card.css';
    $product_card_version = @filemtime($product_card_path) ?: '1.0.0';
    wp_enqueue_style(
        'mykitchen-product-card',
        MYK_ASSETS_URI . '/components/cards/y-c-product-card.css',
        array('mykitchen-utilities'),
        $product_card_version
    );
    wp_enqueue_style('mykitchen-animations', MYK_ASSETS_URI . '/css/y-animations.css', array('mykitchen-utilities'), '1.0.0');

    wp_enqueue_script('mykitchen-header-toggle', MYK_ASSETS_URI . '/js/header-toggle.js', array(), '1.0.0', true);
    wp_enqueue_script('mykitchen-navbar', MYK_ASSETS_URI . '/js/y-navbar.js', array(), '1.0.0', true);
    wp_enqueue_script('mykitchen-footer', MYK_ASSETS_URI . '/js/y-footer.js', array(), '1.0.0', true);
    wp_enqueue_script('mykitchen-favorites', MYK_ASSETS_URI . '/js/y-favorites.js', array(), '1.0.0', true);

    $assets_url = esc_url_raw(MYK_ASSETS_URI);
    $site_url = esc_url_raw(home_url('/'));
    $logout_url = home_url('/logout/');
    $inline_bootstrap = "window.MYK_ASSETS_URL = '" . esc_js($assets_url) . "';"
        . "window.MYK_SITE_URL = '" . esc_js($site_url) . "';"
        . "window.MYK_LOGOUT_URL = '" . esc_js($logout_url) . "';"
        . "window.mykitchenResolveAssets=function(root){"
        . "if(!root||!window.MYK_ASSETS_URL||!root.querySelectorAll){return;}"
        . "var nodes=root.querySelectorAll('[src],[href]');"
        . "var resolver=function(node,attr){"
        . "var value=node.getAttribute(attr);"
        . "if(!value||value.indexOf('../')!==0){return;}"
        . "var cleaned=value.replace(/^(\\.\\.\\/)+/,'');"
        . "if(cleaned.indexOf('assets/')!==0){return;}"
        . "node.setAttribute(attr,window.MYK_ASSETS_URL+'/'+cleaned);"
        . "};"
        . "for(var i=0;i<nodes.length;i++){resolver(nodes[i],'src');resolver(nodes[i],'href');}"
        . "};"
        . "(function(){"
        . "if(!window.fetch){return;}"
        . "var origFetch=window.fetch;"
        . "window.fetch=function(resource,init){"
        . "try{"
        . "if(typeof resource==='string' && resource.indexOf('../')===0){"
        . "var cleaned=resource.replace(/^(\\.\\.\\/)+/,'');"
        . "resource=window.MYK_ASSETS_URL+'/'+cleaned;"
        . "}"
        . "}catch(e){}"
        . "return origFetch(resource,init);"
        . "};"
        . "})();";
    wp_add_inline_script('mykitchen-header-toggle', $inline_bootstrap, 'before');

    $nav_categories = mykitchen_get_nav_categories_data();
    wp_add_inline_script(
        'mykitchen-navbar',
        'window.MYK_NAV_CATEGORIES = ' . wp_json_encode($nav_categories) . ';',
        'before'
    );

    $cart_count = 0;
    if (function_exists('WC') && WC()->cart) {
        $cart_count = (int) WC()->cart->get_cart_contents_count();
    }
    wp_add_inline_script(
        'mykitchen-navbar',
        'window.MYK_CART_COUNT = ' . wp_json_encode($cart_count) . ';',
        'before'
    );

    wp_add_inline_script(
        'wc-add-to-cart',
        'if(window.wc_add_to_cart_params){window.wc_add_to_cart_params.i18n_view_cart="اذهب للسلة";}',
        'after'
    );

    $contact_settings = mykitchen_get_contact_settings();
    $contact_payload = array(
        'address' => $contact_settings['address'],
        'phone' => $contact_settings['phone'],
        'email' => $contact_settings['email'],
        'whatsapp' => $contact_settings['whatsapp'],
    );
    wp_add_inline_script(
        'mykitchen-footer',
        'window.MYK_CONTACT_SETTINGS = ' . wp_json_encode($contact_payload) . ';',
        'before'
    );

    if (is_front_page()) {
        wp_enqueue_style('mykitchen-home-header', MYK_ASSETS_URI . '/components/home/y-c-header.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-home-section', MYK_ASSETS_URI . '/components/home/y-c-section.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-home-brands', MYK_ASSETS_URI . '/components/home/y-c-brands.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-home-offers', MYK_ASSETS_URI . '/components/offers/y-c-offers.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-home-less', MYK_ASSETS_URI . '/components/less than 99/y-c-less-than.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-products', MYK_ASSETS_URI . '/components/products/y-c-products.css', array('mykitchen-utilities'), '1.0.0');

        wp_enqueue_script('mykitchen-header', MYK_ASSETS_URI . '/js/y-header.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-products-section', MYK_ASSETS_URI . '/js/y-products-section.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-offers-section', MYK_ASSETS_URI . '/js/y-offers-section.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-last-chance', MYK_ASSETS_URI . '/js/y-last-chance-section.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-brands', MYK_ASSETS_URI . '/js/y-brands.js', array(), '1.0.0', true);
    }

    if (is_shop() || is_product_category() || is_page('offers')) {
        wp_enqueue_style('mykitchen-breadcrumb', MYK_ASSETS_URI . '/components/layout/y-c-breadcrumb.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-categories', MYK_ASSETS_URI . '/components/categories/y-c-categories.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-products', MYK_ASSETS_URI . '/components/products/y-c-products.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-pagination', MYK_ASSETS_URI . '/components/products/y-c-pagination.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-filter-bar', MYK_ASSETS_URI . '/components/products/y-c-filter-bar.css', array('mykitchen-utilities'), '1.0.0');
        if (is_page('offers')) {
            wp_enqueue_style('mykitchen-empty', MYK_ASSETS_URI . '/components/products/y-c-empty.css', array('mykitchen-utilities'), '1.0.0');
        }

        wp_enqueue_script('mykitchen-design-header', MYK_ASSETS_URI . '/js/y-design-header.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-breadcrumb', MYK_ASSETS_URI . '/js/y-breadcrumb.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-categories', MYK_ASSETS_URI . '/js/y-categories.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-products', MYK_ASSETS_URI . '/js/y-products.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-pagination', MYK_ASSETS_URI . '/js/y-pagination.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-filter', MYK_ASSETS_URI . '/js/y-filter-bar.js', array(), '1.0.0', true);
    }

    if (is_product()) {
        wp_enqueue_style('mykitchen-breadcrumb', MYK_ASSETS_URI . '/components/layout/y-c-breadcrumb.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-auth-buttons', MYK_ASSETS_URI . '/components/buttons/y-c-auth-btn.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-products', MYK_ASSETS_URI . '/components/products/y-c-products.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-single-card', MYK_ASSETS_URI . '/components/single product/y-c-single-product.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-single-page', MYK_ASSETS_URI . '/templates/single product/single-product.css', array('mykitchen-utilities'), '1.0.0');

        wp_enqueue_script('mykitchen-design-header', MYK_ASSETS_URI . '/js/y-design-header.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-breadcrumb', MYK_ASSETS_URI . '/js/y-breadcrumb.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-single-product', MYK_ASSETS_URI . '/js/y-single-product.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-products', MYK_ASSETS_URI . '/js/y-products.js', array(), '1.0.0', true);
    }

    if (is_page('wishlist')) {
        wp_enqueue_style('mykitchen-breadcrumb', MYK_ASSETS_URI . '/components/layout/y-c-breadcrumb.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-products', MYK_ASSETS_URI . '/components/products/y-c-products.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-pagination', MYK_ASSETS_URI . '/components/products/y-c-pagination.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-filter-bar', MYK_ASSETS_URI . '/components/products/y-c-filter-bar.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-home-section', MYK_ASSETS_URI . '/components/home/y-c-section.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-wishlist', MYK_ASSETS_URI . '/templates/wishlist/wishlist.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-empty', MYK_ASSETS_URI . '/components/products/y-c-empty.css', array('mykitchen-utilities'), '1.0.0');

        wp_enqueue_script('mykitchen-design-header', MYK_ASSETS_URI . '/js/y-design-header.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-breadcrumb', MYK_ASSETS_URI . '/js/y-breadcrumb.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-wishlist', MYK_ASSETS_URI . '/js/y-wishlist.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-pagination', MYK_ASSETS_URI . '/js/y-pagination.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-filter', MYK_ASSETS_URI . '/js/y-filter-bar.js', array(), '1.0.0', true);
    }

    if (is_page('contact-us')) {
        wp_enqueue_style('mykitchen-breadcrumb', MYK_ASSETS_URI . '/components/layout/y-c-breadcrumb.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-text-fields', MYK_ASSETS_URI . '/components/text fields/y-c-text-fields.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-contact-us', MYK_ASSETS_URI . '/components/contact us/y-c-contact-us.css', array('mykitchen-utilities'), '1.0.0');

        wp_enqueue_script('mykitchen-design-header', MYK_ASSETS_URI . '/js/y-design-header.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-breadcrumb', MYK_ASSETS_URI . '/js/y-breadcrumb.js', array(), '1.0.0', true);
    }

    if (is_page('about-us')) {
        wp_enqueue_style('mykitchen-breadcrumb', MYK_ASSETS_URI . '/components/layout/y-c-breadcrumb.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-about-us', MYK_ASSETS_URI . '/components/about us/y-c-about-us.css', array('mykitchen-utilities'), '1.0.0');

        wp_enqueue_script('mykitchen-design-header', MYK_ASSETS_URI . '/js/y-design-header.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-breadcrumb', MYK_ASSETS_URI . '/js/y-breadcrumb.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-about-us', MYK_ASSETS_URI . '/js/y-about-us.js', array(), '1.0.0', true);
    }

    if (is_page(array('privacy-policy', 'refund-policy', 'using-policy'))) {
        wp_enqueue_style('mykitchen-breadcrumb', MYK_ASSETS_URI . '/components/layout/y-c-breadcrumb.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-policy', MYK_ASSETS_URI . '/components/policy/y-c-policy.css', array('mykitchen-utilities'), '1.0.0');

        wp_enqueue_script('mykitchen-design-header', MYK_ASSETS_URI . '/js/y-design-header.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-breadcrumb', MYK_ASSETS_URI . '/js/y-breadcrumb.js', array(), '1.0.0', true);
    }

    if (is_cart()) {
        wp_enqueue_style('mykitchen-breadcrumb', MYK_ASSETS_URI . '/components/layout/y-c-breadcrumb.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-auth-buttons', MYK_ASSETS_URI . '/components/buttons/y-c-auth-btn.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-text-fields', MYK_ASSETS_URI . '/components/text fields/y-c-text-fields.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-cart-summary', MYK_ASSETS_URI . '/components/cards/y-c-cart-summary-card.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-cart-table', MYK_ASSETS_URI . '/components/cart/y-c-cart-table.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-qnt-btn', MYK_ASSETS_URI . '/components/buttons/y-c-qnt-btn.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-home-section', MYK_ASSETS_URI . '/components/home/y-c-section.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-cart', MYK_ASSETS_URI . '/templates/cart/cart.css', array('mykitchen-utilities'), '1.0.0');

        if (function_exists('WC') && WC()->cart && WC()->cart->is_empty()) {
            wp_enqueue_style('mykitchen-empty', MYK_ASSETS_URI . '/components/products/y-c-empty.css', array('mykitchen-utilities'), '1.0.0');
        }

        wp_enqueue_script('mykitchen-design-header', MYK_ASSETS_URI . '/js/y-design-header.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-breadcrumb', MYK_ASSETS_URI . '/js/y-breadcrumb.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-cart-summary', MYK_ASSETS_URI . '/js/y-cart-summary-card.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-cart-table', MYK_ASSETS_URI . '/js/y-cart-table.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-cart-quantity', MYK_ASSETS_URI . '/js/y-cart-quantity.js', array(), '1.0.0', true);
    }

    if (is_checkout()) {
        wp_enqueue_style('mykitchen-breadcrumb', MYK_ASSETS_URI . '/components/layout/y-c-breadcrumb.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-auth-buttons', MYK_ASSETS_URI . '/components/buttons/y-c-auth-btn.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-text-fields', MYK_ASSETS_URI . '/components/text fields/y-c-text-fields.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-cart-summary', MYK_ASSETS_URI . '/components/cards/y-c-cart-summary-card.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-payment-summary', MYK_ASSETS_URI . '/components/payment/y-c-payment-summary-card.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-payment-form', MYK_ASSETS_URI . '/components/payment/y-c-payment-form.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-home-section', MYK_ASSETS_URI . '/components/home/y-c-section.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-payment', MYK_ASSETS_URI . '/templates/payment/payment.css', array('mykitchen-utilities'), '1.0.0');

        wp_add_inline_style(
            'mykitchen-payment',
            '.wc-credit-card-form{display:none !important;}'
        );

        wp_enqueue_script('mykitchen-design-header', MYK_ASSETS_URI . '/js/y-design-header.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-breadcrumb', MYK_ASSETS_URI . '/js/y-breadcrumb.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-payment-summary', MYK_ASSETS_URI . '/js/y-payment-summary-card.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-payment', MYK_ASSETS_URI . '/js/y-payment.js', array(), '1.0.0', true);
    }

    if (is_page(array('login', 'sign-up', 'reset-password', 'create-password'))) {
        wp_enqueue_style('mykitchen-auth-buttons', MYK_ASSETS_URI . '/components/buttons/y-c-auth-btn.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-auth', MYK_ASSETS_URI . '/components/auth/y-c-auth.css', array('mykitchen-utilities'), '1.0.0');

        wp_enqueue_script('mykitchen-design-header', MYK_ASSETS_URI . '/js/y-design-header.js', array(), '1.0.0', true);
    }

    if (is_page('login')) {
        wp_enqueue_script('mykitchen-login', MYK_ASSETS_URI . '/js/y-login.js', array(), '1.0.0', true);
    }

    if (is_page('sign-up')) {
        wp_enqueue_script('mykitchen-sign-up', MYK_ASSETS_URI . '/js/y-sign-up.js', array(), '1.0.0', true);
    }

    if (is_page('reset-password')) {
        wp_enqueue_script('mykitchen-reset-password', MYK_ASSETS_URI . '/js/y-reset-password.js', array(), '1.0.0', true);
    }

    if (is_account_page() || is_page('my-account')) {
        wp_enqueue_style('mykitchen-breadcrumb', MYK_ASSETS_URI . '/components/layout/y-c-breadcrumb.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-account-sidebar', MYK_ASSETS_URI . '/components/account/y-c-account-sidebar.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-account-orders', MYK_ASSETS_URI . '/components/account/y-c-orders.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-account-details', MYK_ASSETS_URI . '/components/account/y-c-account-details.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-account-address', MYK_ASSETS_URI . '/components/account/y-c-address.css', array('mykitchen-utilities'), '1.0.0');
        wp_enqueue_style('mykitchen-account', MYK_ASSETS_URI . '/templates/my-account/my-account.css', array('mykitchen-utilities'), '1.0.0');

        wp_enqueue_script('mykitchen-design-header', MYK_ASSETS_URI . '/js/y-design-header.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-breadcrumb', MYK_ASSETS_URI . '/js/y-breadcrumb.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-account-sidebar', MYK_ASSETS_URI . '/js/y-account-sidebar.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-order-details', MYK_ASSETS_URI . '/js/y-order-details.js', array(), '1.0.0', true);
        wp_enqueue_script('mykitchen-my-account', MYK_ASSETS_URI . '/js/y-my-account.js', array(), '1.0.0', true);
    }
}

function mykitchen_customize_checkout_fields(array $fields): array
{
    if (isset($fields['billing']['billing_first_name'])) {
        $fields['billing']['billing_first_name']['label'] = 'الاسم الكامل';
        $fields['billing']['billing_first_name']['priority'] = 10;
        $fields['billing']['billing_first_name']['class'] = array('form-row-wide');
    }

    if (isset($fields['billing']['billing_last_name'])) {
        unset($fields['billing']['billing_last_name']);
    }

    if (isset($fields['billing']['billing_email'])) {
        $fields['billing']['billing_email']['label'] = 'البريد الإلكتروني';
        $fields['billing']['billing_email']['priority'] = 20;
    }

    if (isset($fields['billing']['billing_phone'])) {
        $fields['billing']['billing_phone']['label'] = 'رقم الجوال';
        $fields['billing']['billing_phone']['priority'] = 30;
        $fields['billing']['billing_phone']['required'] = true;
    }

    if (isset($fields['billing']['billing_address_1'])) {
        $fields['billing']['billing_address_1']['label'] = 'العنوان';
        $fields['billing']['billing_address_1']['priority'] = 40;
        $fields['billing']['billing_address_1']['class'] = array('form-row-wide');
    }

    if (isset($fields['billing']['billing_address_2'])) {
        unset($fields['billing']['billing_address_2']);
    }

    if (isset($fields['billing']['billing_city'])) {
        unset($fields['billing']['billing_city']);
    }

    if (isset($fields['billing']['billing_state'])) {
        unset($fields['billing']['billing_state']);
    }

    if (isset($fields['billing']['billing_postcode'])) {
        unset($fields['billing']['billing_postcode']);
    }

    if (isset($fields['order']['order_comments'])) {
        unset($fields['order']['order_comments']);
    }

    $remove_fields = array('billing_company');

    foreach ($remove_fields as $field_key) {
        if (isset($fields['billing'][$field_key])) {
            unset($fields['billing'][$field_key]);
        }
    }

    if (isset($fields['billing']['billing_country'])) {
        $fields['billing']['billing_country']['type'] = 'hidden';
        $fields['billing']['billing_country']['default'] = 'SA';
        $fields['billing']['billing_country']['required'] = false;
    }

    if (isset($fields['account']['account_password'])) {
        if (is_user_logged_in()) {
            unset($fields['account']['account_password']);
        } else {
            $fields['account']['account_password']['label'] = 'كلمة المرور';
            $fields['account']['account_password']['class'] = array('form-row-wide');
            $fields['account']['account_password']['priority'] = 50;
        }
    }

    return $fields;
}
add_filter('woocommerce_checkout_fields', 'mykitchen_customize_checkout_fields');

remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);

function mykitchen_checkout_intro_section(): void
{
    echo '<div class="myk-checkout-intro">';
    echo '<h3>معلومات التوصيل</h3>';
    echo '<p class="note">سوف نستخدم هذا البريد الإلكتروني لإرسال التفاصيل والتحديثات إليك حول طلبك.</p>';
    echo '</div>';
}
add_action('woocommerce_checkout_before_customer_details', 'mykitchen_checkout_intro_section', 5);

function mykitchen_checkout_back_link(): void
{
    echo '<div class="myk-checkout-back">';
    echo '<a href="' . esc_url(wc_get_cart_url()) . '"><i class="fa fa-arrow-right" aria-hidden="true"></i> العودة إلى سلة المشتريات</a>';
    echo '</div>';
}
add_action('woocommerce_checkout_after_order_review', 'mykitchen_checkout_back_link', 20);

add_filter('woocommerce_order_button_text', function (): string {
    return 'تقديم الطلب';
});

add_filter('woocommerce_gateway_title', function (string $title, string $gateway_id): string {
    $map = array(
        'bacs' => 'تحويل بنكي مباشر',
        'cod' => 'الدفع عند الاستلام',
        'paymob-19305-card-vpc-sar' => 'الدفع بالبطاقة البنكية',
        'moyasar-credit-card' => 'الدفع عبر البطاقة البنكية',
    );
    return $map[$gateway_id] ?? $title;
}, 10, 2);

add_filter('woocommerce_gateway_description', function (string $description, string $gateway_id): string {
    $map = array(
        'bacs' => 'قم بتحويل المبلغ مباشرة إلى حسابنا البنكي. يرجى استخدام رقم الطلب كمرجع. لن يتم شحن الطلب حتى يتم تأكيد وصول المبلغ.',
        'cod' => 'ادفع نقدًا عند الاستلام.',
        'paymob-19305-card-vpc-sar' => 'ادفع باستخدام بطاقتك البنكية.',
        'moyasar-credit-card' => 'ادفع باستخدام بطاقتك البنكية.',
    );
    return $map[$gateway_id] ?? $description;
}, 10, 2);

add_filter('woocommerce_checkout_registration_enabled', '__return_true');
add_filter('woocommerce_checkout_registration_required', '__return_false');
add_filter('woocommerce_registration_generate_password', '__return_false');
add_action('wp_enqueue_scripts', 'mykitchen_enqueue_assets');

function mykitchen_homepage_admin_assets(string $hook): void
{
    $page = $_GET['page'] ?? '';
    if ('mykitchen-content_page_mykitchen-homepage' !== $hook && 'mykitchen-homepage' !== $page) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script(
        'mykitchen-homepage-admin',
        MYK_ASSETS_URI . '/js/y-homepage-admin.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('admin_enqueue_scripts', 'mykitchen_homepage_admin_assets');

function mykitchen_current_page(): string
{
    if (is_front_page()) {
        return 'home';
    }

    if (is_shop()) {
        return 'store';
    }

    if (is_page('offers')) {
        return 'offers';
    }

    if (is_product_category()) {
        return 'products';
    }

    if (is_product()) {
        return 'single-product';
    }

    if (is_cart()) {
        return 'cart';
    }

    if (is_page('wishlist')) {
        return 'wishlist';
    }

    if (is_checkout()) {
        return 'payment';
    }

    if (is_page('privacy-policy')) {
        return 'privacy-policy';
    }

    if (is_page('refund-policy')) {
        return 'refund-policy';
    }

    if (is_page('using-policy')) {
        return 'using-policy';
    }

    if (is_account_page()) {
        return 'account';
    }

    return 'page';
}

function mykitchen_render_product_categories(): string
{
    if (!taxonomy_exists('product_cat')) {
        return '';
    }

    $terms = get_terms(
        array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        )
    );

    if (empty($terms) || is_wp_error($terms)) {
        return '';
    }

    $fallback_image = MYK_ASSETS_URI . '/assets/product.png';
    ob_start();
    echo '<div class="categories">';
    foreach ($terms as $term) {
        if ('uncategorized' === $term->slug) {
            continue;
        }
        $term_id = (int) $term->term_id;
        $thumb_id = (int) get_term_meta($term_id, 'thumbnail_id', true);
        $image_url = $thumb_id ? wp_get_attachment_url($thumb_id) : $fallback_image;
        if (!$image_url) {
            $image_url = $fallback_image;
        }
        echo '<a href="' . esc_url(get_term_link($term)) . '" class="category">';
        echo '<img src="' . esc_url($image_url) . '" alt="' . esc_attr($term->name) . '" />';
        echo '<h3 class="category-title">' . esc_html($term->name) . '</h3>';
        echo '</a>';
    }
    echo '</div>';

    return ob_get_clean();
}

function mykitchen_get_nav_category_icon(object $term): string
{
    $icon = (string) get_term_meta((int) $term->term_id, 'mykitchen_nav_icon', true);
    if ($icon) {
        return $icon;
    }

    $fallbacks = array(
        'electronics' => 'fa-solid fa-plug-circle-bolt',
        'for-home' => 'fa-solid fa-kitchen-set',
        'decoration' => 'fa-solid fa-couch',
    );

    return $fallbacks[$term->slug] ?? 'fa-solid fa-tags';
}

function mykitchen_get_nav_categories_data(): array
{
    if (!taxonomy_exists('product_cat')) {
        return array();
    }

    $terms = get_terms(
        array(
            'taxonomy' => 'product_cat',
            'hide_empty' => false,
        )
    );

    if (empty($terms) || is_wp_error($terms)) {
        return array();
    }

    $default_cat_id = (int) get_option('default_product_cat');
    $data = array();
    foreach ($terms as $term) {
        if ('uncategorized' === $term->slug || $term->term_id === $default_cat_id) {
            continue;
        }
        $data[] = array(
            'name' => $term->name,
            'url' => get_term_link($term),
            'icon' => mykitchen_get_nav_category_icon($term),
        );
    }

    return $data;
}

function mykitchen_get_contact_settings(): array
{
    $defaults = array(
        'hero_title' => 'يسعدنا استقبال رسالتك',
        'contact_heading' => 'تواصل معنا',
        'label_name' => 'الاسم ثلاثي',
        'label_email' => 'البريد الإلكتروني',
        'label_phone' => 'رقم الهاتف',
        'label_topic' => 'موضوع الرسالة',
        'label_message' => 'رسالتك',
        'label_submit' => 'إرسال',
        'address' => 'الرياض - المملكة العربية السعودية',
        'phone' => '059688929 - 058493948',
        'whatsapp' => '059688929',
        'email' => 'info@super.ksa.com',
        'visit_title_1' => 'زورونا في معرض دارك',
        'visit_title_2' => 'أوقات الدوام من 9:30 ص حتى 10:30 م',
        'map_embed' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3714.299193570229!2d46.696511975000004!3d24.693191200000002!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2ee4e61d2ce76f%3A0x32f0826979251e13!2sDarak%20Furniture%20Store!5e0!3m2!1sen!2ssa!4v1733536800000!5m2!1sen!2ssa',
        'recipient_email' => '',
        'from_name' => 'My Kitchen',
        'from_email' => '',
        'smtp_enabled' => 'no',
        'smtp_mode' => 'gmail',
        'smtp_host' => '',
        'smtp_port' => '587',
        'smtp_encryption' => 'tls',
        'smtp_username' => '',
        'smtp_password' => '',
        'gmail_address' => '',
        'gmail_app_password' => '',
        'test_email' => '',
    );

    $stored = get_option('mykitchen_contact_settings', array());
    if (!is_array($stored)) {
        $stored = array();
    }

    return array_merge($defaults, $stored);
}

function mykitchen_render_contact_settings(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $settings = mykitchen_get_contact_settings();
    $notice = '';
    $error = '';

    if (isset($_POST['mykitchen_contact_settings_save'])) {
        check_admin_referer('mykitchen_contact_settings');
        $new_settings = $settings;

        $fields = array(
            'hero_title',
            'contact_heading',
            'label_name',
            'label_email',
            'label_phone',
            'label_topic',
            'label_message',
            'label_submit',
            'address',
            'phone',
            'whatsapp',
            'email',
            'visit_title_1',
            'visit_title_2',
            'map_embed',
            'recipient_email',
            'from_name',
            'from_email',
            'smtp_enabled',
            'smtp_mode',
            'smtp_host',
            'smtp_port',
            'smtp_encryption',
            'smtp_username',
            'gmail_address',
            'test_email',
        );

        foreach ($fields as $field) {
            $value = isset($_POST[$field]) ? wp_unslash($_POST[$field]) : '';
            $value = is_string($value) ? trim($value) : $value;

            if (in_array($field, array('email', 'recipient_email', 'from_email', 'gmail_address', 'test_email'), true)) {
                $new_settings[$field] = sanitize_email($value);
            } elseif ('map_embed' === $field) {
                $new_settings[$field] = esc_url_raw($value);
            } else {
                $new_settings[$field] = sanitize_text_field($value);
            }
        }

        $mail_type = isset($_POST['mail_type']) ? sanitize_text_field(wp_unslash($_POST['mail_type'])) : '';
        if ('gmail' === $mail_type) {
            $new_settings['smtp_enabled'] = 'yes';
            $new_settings['smtp_mode'] = 'gmail';
        } elseif ('custom' === $mail_type) {
            $new_settings['smtp_enabled'] = 'yes';
            $new_settings['smtp_mode'] = 'custom';
        } else {
            $new_settings['smtp_enabled'] = 'no';
            $new_settings['smtp_mode'] = 'gmail';
        }

        $new_settings['smtp_enabled'] = ($new_settings['smtp_enabled'] === 'yes') ? 'yes' : 'no';
        $new_settings['smtp_mode'] = ($new_settings['smtp_mode'] === 'custom') ? 'custom' : 'gmail';
        $new_settings['smtp_encryption'] = in_array($new_settings['smtp_encryption'], array('', 'tls', 'ssl'), true)
            ? $new_settings['smtp_encryption']
            : '';

        if (isset($_POST['smtp_password']) && $_POST['smtp_password'] !== '') {
            $new_settings['smtp_password'] = (string) wp_unslash($_POST['smtp_password']);
        }

        if (isset($_POST['gmail_app_password']) && $_POST['gmail_app_password'] !== '') {
            $new_settings['gmail_app_password'] = (string) wp_unslash($_POST['gmail_app_password']);
        }

        update_option('mykitchen_contact_settings', $new_settings);
        $settings = mykitchen_get_contact_settings();
        $notice = 'تم حفظ الإعدادات بنجاح.';
    }

    if (isset($_POST['mykitchen_contact_send_test'])) {
        check_admin_referer('mykitchen_contact_settings');
        $to = sanitize_email(wp_unslash($_POST['test_email'] ?? ''));
        if (!$to) {
            $error = 'يرجى إدخال بريد لاختبار الإرسال.';
        } else {
            $sent = wp_mail($to, 'رسالة اختبار - تواصل معنا', 'هذه رسالة اختبار من إعدادات تواصل معنا.');
            if ($sent) {
                $notice = 'تم إرسال رسالة الاختبار بنجاح.';
            } else {
                $error = 'فشل إرسال رسالة الاختبار.';
            }
        }
    }

    echo '<div class="wrap">';
    echo '<h1>صفحة تواصل معنا</h1>';
    if ($notice) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($notice) . '</p></div>';
    }
    if ($error) {
        echo '<div class="notice notice-error is-dismissible"><p>' . esc_html($error) . '</p></div>';
    }
    echo '<form method="post">';
    wp_nonce_field('mykitchen_contact_settings');
    echo '<h2>محتوى الصفحة</h2>';
    echo '<table class="form-table"><tbody>';
    echo '<tr><th><label for="hero_title">عنوان الصفحة</label></th><td><input type="text" class="regular-text" id="hero_title" name="hero_title" value="' . esc_attr($settings['hero_title']) . '" /></td></tr>';
    echo '<tr><th><label for="contact_heading">عنوان معلومات التواصل</label></th><td><input type="text" class="regular-text" id="contact_heading" name="contact_heading" value="' . esc_attr($settings['contact_heading']) . '" /></td></tr>';
    echo '<tr><th><label for="label_name">تسمية حقل الاسم</label></th><td><input type="text" class="regular-text" id="label_name" name="label_name" value="' . esc_attr($settings['label_name']) . '" /></td></tr>';
    echo '<tr><th><label for="label_email">تسمية حقل البريد</label></th><td><input type="text" class="regular-text" id="label_email" name="label_email" value="' . esc_attr($settings['label_email']) . '" /></td></tr>';
    echo '<tr><th><label for="label_phone">تسمية حقل الهاتف</label></th><td><input type="text" class="regular-text" id="label_phone" name="label_phone" value="' . esc_attr($settings['label_phone']) . '" /></td></tr>';
    echo '<tr><th><label for="label_topic">تسمية حقل الموضوع</label></th><td><input type="text" class="regular-text" id="label_topic" name="label_topic" value="' . esc_attr($settings['label_topic']) . '" /></td></tr>';
    echo '<tr><th><label for="label_message">تسمية حقل الرسالة</label></th><td><input type="text" class="regular-text" id="label_message" name="label_message" value="' . esc_attr($settings['label_message']) . '" /></td></tr>';
    echo '<tr><th><label for="label_submit">نص زر الإرسال</label></th><td><input type="text" class="regular-text" id="label_submit" name="label_submit" value="' . esc_attr($settings['label_submit']) . '" /></td></tr>';
    echo '<tr><th><label for="address">العنوان</label></th><td><input type="text" class="regular-text" id="address" name="address" value="' . esc_attr($settings['address']) . '" /></td></tr>';
    echo '<tr><th><label for="phone">رقم الهاتف</label></th><td><input type="text" class="regular-text" id="phone" name="phone" value="' . esc_attr($settings['phone']) . '" /></td></tr>';
    echo '<tr><th><label for="whatsapp">رقم الواتساب</label></th><td><input type="text" class="regular-text" id="whatsapp" name="whatsapp" value="' . esc_attr($settings['whatsapp']) . '" /></td></tr>';
    echo '<tr><th><label for="email">البريد المعروض</label></th><td><input type="email" class="regular-text" id="email" name="email" value="' . esc_attr($settings['email']) . '" /></td></tr>';
    echo '<tr><th><label for="visit_title_1">سطر الزيارة 1</label></th><td><input type="text" class="regular-text" id="visit_title_1" name="visit_title_1" value="' . esc_attr($settings['visit_title_1']) . '" /></td></tr>';
    echo '<tr><th><label for="visit_title_2">سطر الزيارة 2</label></th><td><input type="text" class="regular-text" id="visit_title_2" name="visit_title_2" value="' . esc_attr($settings['visit_title_2']) . '" /></td></tr>';
    echo '<tr><th><label for="map_embed">رابط خريطة Google (Embed)</label></th><td><input type="text" class="large-text" id="map_embed" name="map_embed" value="' . esc_attr($settings['map_embed']) . '" /></td></tr>';
    echo '</tbody></table>';

    echo '<h2>إعدادات نموذج التواصل</h2>';
    echo '<table class="form-table"><tbody>';
    echo '<tr><th><label for="recipient_email">بريد الاستقبال</label></th><td><input type="email" class="regular-text" id="recipient_email" name="recipient_email" value="' . esc_attr($settings['recipient_email']) . '" /><p class="description">سيتم إرسال رسائل النموذج إلى هذا البريد.</p></td></tr>';
    echo '<tr><th><label for="from_name">اسم المُرسِل</label></th><td><input type="text" class="regular-text" id="from_name" name="from_name" value="' . esc_attr($settings['from_name']) . '" /></td></tr>';
    echo '<tr><th><label for="from_email">بريد المُرسِل</label></th><td><input type="email" class="regular-text" id="from_email" name="from_email" value="' . esc_attr($settings['from_email']) . '" /></td></tr>';
    echo '</tbody></table>';

    $mail_type = ($settings['smtp_enabled'] === 'yes')
        ? ($settings['smtp_mode'] === 'custom' ? 'custom' : 'gmail')
        : 'php';
    echo '<h2>إعدادات البريد</h2>';
    echo '<table class="form-table"><tbody>';
    echo '<tr><th><label for="mail_type">نوع البريد</label></th><td><select id="mail_type" name="mail_type"><option value="php"' . selected($mail_type, 'php', false) . '>إرسال افتراضي (PHP Mail)</option><option value="gmail"' . selected($mail_type, 'gmail', false) . '>Gmail (App Password)</option><option value="custom"' . selected($mail_type, 'custom', false) . '>SMTP احترافي</option></select></td></tr>';
    echo '</tbody></table>';

    echo '<table class="form-table" data-mail-section="gmail"><tbody>';
    echo '<tr><th><label for="gmail_address">بريد Gmail</label></th><td><input type="email" class="regular-text" id="gmail_address" name="gmail_address" value="' . esc_attr($settings['gmail_address']) . '" /></td></tr>';
    echo '<tr><th><label for="gmail_app_password">كلمة مرور التطبيق</label></th><td><input type="password" class="regular-text" id="gmail_app_password" name="gmail_app_password" placeholder="••••••••••" /></td></tr>';
    echo '</tbody></table>';

    echo '<table class="form-table" data-mail-section="custom"><tbody>';
    echo '<tr><th><label for="smtp_host">SMTP Host</label></th><td><input type="text" class="regular-text" id="smtp_host" name="smtp_host" value="' . esc_attr($settings['smtp_host']) . '" /></td></tr>';
    echo '<tr><th><label for="smtp_port">SMTP Port</label></th><td><input type="text" class="regular-text" id="smtp_port" name="smtp_port" value="' . esc_attr($settings['smtp_port']) . '" /></td></tr>';
    echo '<tr><th><label for="smtp_encryption">التشفير</label></th><td><select id="smtp_encryption" name="smtp_encryption"><option value=""' . selected($settings['smtp_encryption'], '', false) . '>بدون</option><option value="tls"' . selected($settings['smtp_encryption'], 'tls', false) . '>TLS</option><option value="ssl"' . selected($settings['smtp_encryption'], 'ssl', false) . '>SSL</option></select></td></tr>';
    echo '<tr><th><label for="smtp_username">اسم المستخدم</label></th><td><input type="text" class="regular-text" id="smtp_username" name="smtp_username" value="' . esc_attr($settings['smtp_username']) . '" /></td></tr>';
    echo '<tr><th><label for="smtp_password">كلمة المرور</label></th><td><input type="password" class="regular-text" id="smtp_password" name="smtp_password" placeholder="••••••••••" /></td></tr>';
    echo '</tbody></table>';
    echo '</tbody></table>';

    echo '<p>';
    submit_button('حفظ الإعدادات', 'primary', 'mykitchen_contact_settings_save', false);
    echo '</p>';

    echo '<h2>اختبار الإيميل</h2>';
    echo '<table class="form-table"><tbody>';
    echo '<tr><th><label for="test_email">بريد الاختبار</label></th><td><input type="email" class="regular-text" id="test_email" name="test_email" value="' . esc_attr($settings['test_email']) . '" /></td></tr>';
    echo '</tbody></table>';
    submit_button('إرسال رسالة اختبار', 'secondary', 'mykitchen_contact_send_test');
    echo '<script>
      (function(){
        var selector = document.getElementById("mail_type");
        if(!selector){return;}
        var gmail = document.querySelector("[data-mail-section=\"gmail\"]");
        var custom = document.querySelector("[data-mail-section=\"custom\"]");
        var toggle = function(){
          var value = selector.value;
          if(gmail){gmail.style.display = value === "gmail" ? "table" : "none";}
          if(custom){custom.style.display = value === "custom" ? "table" : "none";}
        };
        selector.addEventListener("change", toggle);
        toggle();
      })();
    </script>';
    echo '</form>';
    echo '</div>';
}

function mykitchen_policy_defaults(): array
{
    return array(
        'privacy' => array(
            'title' => 'سياسة الخصوصية',
            'content' => "نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية عند استخدامك لموقعنا.\n\nنستخدم البيانات اللازمة لمعالجة الطلبات والتواصل معك وتحسين خدماتنا.\n\nلا نشارك معلوماتك مع أي طرف ثالث إلا للضرورة لتقديم الخدمة مثل الشحن أو الدفع.",
            'image_id' => 0,
            'image' => '',
        ),
        'refund' => array(
            'title' => 'سياسة الاسترجاع والاسترداد',
            'content' => "عمل دائماً لنيل رضاكم ونكون عند حسن ظنكم بنا. إذا كنت ترغب في إرجاع منتج ما، فنحن نقبل بسرور استبدال المنتج أو منحك رصيداً في المتجر أو إرجاع المنتج مقابل نقاط متجر الأحمدي.\n\nفي حال طلب استرجاع أي منتج، يرجى التواصل معنا عبر البريد الإلكتروني: care@yamamah.sa أو الهاتف أو واتساب: 966534411732+\n\nالدفع عبر الإنترنت:\nستتم معالجة المبالغ المستردة في غضون ٢٤ ساعة وستضاف إلى حساب العميل في غضون 3-5 أيام عمل، اعتمادًا على مصدر البنك.\n\nالدفع نقداً عند التسليم:\nستُضاف المبالغ المستردة إلى حساب العميل كنقاط متجر الأحمدي ويمكن استخدامها في الطلب التالي.\n\nنقاط متجر الأحمدي:\nستُضاف المبالغ المستردة إلى حساب العميل كنقاط متجر الأحمدي ويمكن استخدامها في الطلب التالي.",
            'image_id' => 0,
            'image' => '',
        ),
        'using' => array(
            'title' => 'سياسة الشحن',
            'content' => 'يتم الشحن خلال اليوم لكل الطلبات داخل المدينة المنورة، أما كل الطلبات داخل السعودية وخارج المدينة المنورة يستغرق الشحن من يوم إلى ثلاثة أيام عمل.',
            'image_id' => 0,
            'image' => '',
        ),
    );
}

function mykitchen_get_policy_settings(): array
{
    $defaults = mykitchen_policy_defaults();
    $stored = get_option('mykitchen_policy_settings', array());
    if (!is_array($stored)) {
        $stored = array();
    }

    return array_replace_recursive($defaults, $stored);
}

function mykitchen_render_policy_settings(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $settings = mykitchen_get_policy_settings();
    $notice = '';

    if (isset($_POST['mykitchen_policy_settings_save'])) {
        check_admin_referer('mykitchen_policy_settings');
        $new_settings = $settings;
        $sections = array('privacy', 'refund', 'using');

        foreach ($sections as $section) {
            $title_key = $section . '_title';
            $content_key = $section . '_content';
            $image_id_key = $section . '_image_id';
            $image_key = $section . '_image';

            $new_settings[$section]['title'] = sanitize_text_field(wp_unslash($_POST[$title_key] ?? ''));
            $new_settings[$section]['content'] = wp_kses_post(wp_unslash($_POST[$content_key] ?? ''));
            $new_settings[$section]['image_id'] = absint($_POST[$image_id_key] ?? 0);
            $new_settings[$section]['image'] = esc_url_raw(wp_unslash($_POST[$image_key] ?? ''));
        }

        update_option('mykitchen_policy_settings', $new_settings);
        $settings = mykitchen_get_policy_settings();
        $notice = 'تم حفظ السياسات بنجاح.';
    }

    echo '<div class="wrap">';
    echo '<h1>سياسات الموقع</h1>';
    if ($notice) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($notice) . '</p></div>';
    }
    echo '<form method="post">';
    wp_nonce_field('mykitchen_policy_settings');

    $labels = array(
        'privacy' => 'سياسة الخصوصية',
        'refund' => 'سياسة الاسترجاع والاسترداد',
        'using' => 'سياسة الشحن',
    );

    foreach ($labels as $key => $label) {
        $title = $settings[$key]['title'] ?? '';
        $content = $settings[$key]['content'] ?? '';
        $image_id = (int) ($settings[$key]['image_id'] ?? 0);
        $image_url = $settings[$key]['image'] ?? '';
        if ($image_id) {
            $image_url = wp_get_attachment_url($image_id) ?: $image_url;
        }

        echo '<div class="postbox" style="padding:16px;margin:16px 0;">';
        echo '<h2 style="margin-top:0;">' . esc_html($label) . '</h2>';
        echo '<p><label for="' . esc_attr($key . '_title') . '">العنوان</label><br>';
        echo '<input type="text" class="regular-text" id="' . esc_attr($key . '_title') . '" name="' . esc_attr($key . '_title') . '" value="' . esc_attr($title) . '" /></p>';

        echo '<p><label for="' . esc_attr($key . '_content') . '">المحتوى</label></p>';
        wp_editor(
            $content,
            $key . '_content',
            array(
                'textarea_name' => $key . '_content',
                'textarea_rows' => 6,
                'media_buttons' => false,
            )
        );

        echo '<p><label>صورة (اختياري)</label></p>';
        echo '<div class="myk-upload-field">';
        echo '<div class="myk-upload-preview" id="' . esc_attr($key . '_preview') . '">';
        if ($image_url) {
            echo '<img src="' . esc_url($image_url) . '" alt="" style="max-width:220px;height:auto;">';
        } else {
            echo '<span>لا توجد صورة</span>';
        }
        echo '</div>';
        echo '<div class="myk-upload-actions">';
        echo '<button type="button" class="button myk-upload-btn" data-target-id="' . esc_attr($key . '_image_id') . '" data-target-url="' . esc_attr($key . '_image') . '" data-preview="' . esc_attr($key . '_preview') . '">اختيار صورة</button> ';
        echo '<button type="button" class="button myk-remove-btn" data-target-id="' . esc_attr($key . '_image_id') . '" data-target-url="' . esc_attr($key . '_image') . '" data-preview="' . esc_attr($key . '_preview') . '">إزالة</button>';
        echo '</div>';
        echo '<input type="hidden" id="' . esc_attr($key . '_image_id') . '" name="' . esc_attr($key . '_image_id') . '" value="' . esc_attr($image_id) . '">';
        echo '<input type="hidden" id="' . esc_attr($key . '_image') . '" name="' . esc_attr($key . '_image') . '" value="' . esc_attr($image_url) . '">';
        echo '</div>';
        echo '</div>';
    }

    submit_button('حفظ السياسات', 'primary', 'mykitchen_policy_settings_save');
    echo '</form>';
    echo '</div>';
}

function mykitchen_policy_admin_assets(string $hook): void
{
    $page = $_GET['page'] ?? '';
    if ('mykitchen-content_page_mykitchen-policy-settings' !== $hook && 'mykitchen-policy-settings' !== $page) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script(
        'mykitchen-policy-admin',
        MYK_ASSETS_URI . '/js/y-homepage-admin.js',
        array('jquery'),
        '1.0.0',
        true
    );
}
add_action('admin_enqueue_scripts', 'mykitchen_policy_admin_assets');

function mykitchen_site_settings_defaults(): array
{
    return array(
        'main_color' => '#3a0506',
        'secondary_color' => '#542525',
        'background_color' => '#ffffff',
        'body_background' => '#fff3f3',
    );
}

function mykitchen_get_site_settings(): array
{
    $defaults = mykitchen_site_settings_defaults();
    $stored = get_option('mykitchen_site_settings', array());
    if (!is_array($stored)) {
        $stored = array();
    }

    return array_merge($defaults, $stored);
}

function mykitchen_render_site_settings(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    $settings = mykitchen_get_site_settings();
    $notice = '';

    if (isset($_POST['mykitchen_site_settings_save'])) {
        check_admin_referer('mykitchen_site_settings');
        $new_settings = $settings;

        $main_color = sanitize_hex_color(wp_unslash($_POST['main_color'] ?? '')) ?: $settings['main_color'];
        $secondary_color = sanitize_hex_color(wp_unslash($_POST['secondary_color'] ?? '')) ?: $settings['secondary_color'];
        $background_color = sanitize_hex_color(wp_unslash($_POST['background_color'] ?? '')) ?: $settings['background_color'];
        $body_background = sanitize_hex_color(wp_unslash($_POST['body_background'] ?? '')) ?: $settings['body_background'];

        $new_settings['main_color'] = $main_color;
        $new_settings['secondary_color'] = $secondary_color;
        $new_settings['background_color'] = $background_color;
        $new_settings['body_background'] = $body_background;

        update_option('mykitchen_site_settings', $new_settings);
        $settings = mykitchen_get_site_settings();
        $notice = 'تم حفظ إعدادات الموقع.';
    }

    if (isset($_POST['mykitchen_site_settings_reset'])) {
        check_admin_referer('mykitchen_site_settings');
        delete_option('mykitchen_site_settings');
        $settings = mykitchen_get_site_settings();
        $notice = 'تمت استعادة الألوان الافتراضية.';
    }

    echo '<div class="wrap">';
    echo '<h1>إعدادات الموقع</h1>';
    if ($notice) {
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($notice) . '</p></div>';
    }
    echo '<form method="post">';
    wp_nonce_field('mykitchen_site_settings');
    echo '<table class="form-table"><tbody>';
    echo '<tr><th><label for="main_color">لون الفوتر والأزرار (أساسي)</label></th><td><input type="text" class="regular-text myk-color-field" id="main_color" name="main_color" value="' . esc_attr($settings['main_color']) . '" /></td></tr>';
    echo '<tr><th><label for="secondary_color">لون الأزرار الثانوي</label></th><td><input type="text" class="regular-text myk-color-field" id="secondary_color" name="secondary_color" value="' . esc_attr($settings['secondary_color']) . '" /></td></tr>';
    echo '<tr><th><label for="background_color">لون خلفية المكونات</label></th><td><input type="text" class="regular-text myk-color-field" id="background_color" name="background_color" value="' . esc_attr($settings['background_color']) . '" /></td></tr>';
    echo '<tr><th><label for="body_background">لون خلفية الموقع بالكامل</label></th><td><input type="text" class="regular-text myk-color-field" id="body_background" name="body_background" value="' . esc_attr($settings['body_background']) . '" /></td></tr>';
    echo '</tbody></table>';
    echo '<p>';
    submit_button('حفظ الإعدادات', 'primary', 'mykitchen_site_settings_save', false);
    echo ' ';
    submit_button('استعادة الافتراضي', 'secondary', 'mykitchen_site_settings_reset', false);
    echo '</p>';
    echo '</form>';
    echo '</div>';
}

function mykitchen_site_settings_admin_assets(string $hook): void
{
    $page = $_GET['page'] ?? '';
    if ('mykitchen-content_page_mykitchen-site-settings' !== $hook && 'mykitchen-site-settings' !== $page) {
        return;
    }

    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('wp-color-picker');
    wp_add_inline_script(
        'wp-color-picker',
        '(function($){$(function(){$(".myk-color-field").wpColorPicker();});})(jQuery);',
        'after'
    );
}
add_action('admin_enqueue_scripts', 'mykitchen_site_settings_admin_assets');

function mykitchen_apply_mailer_settings($phpmailer): void
{
    $settings = mykitchen_get_contact_settings();
    if (($settings['smtp_enabled'] ?? 'no') !== 'yes') {
        return;
    }

    $phpmailer->isSMTP();
    $phpmailer->SMTPAuth = true;

    if (($settings['smtp_mode'] ?? 'gmail') === 'gmail') {
        $phpmailer->Host = 'smtp.gmail.com';
        $phpmailer->Port = 587;
        $phpmailer->SMTPSecure = 'tls';
        $phpmailer->Username = (string) $settings['gmail_address'];
        $phpmailer->Password = (string) $settings['gmail_app_password'];
    } else {
        $phpmailer->Host = (string) $settings['smtp_host'];
        $phpmailer->Port = (int) $settings['smtp_port'];
        $phpmailer->SMTPSecure = (string) $settings['smtp_encryption'];
        $phpmailer->Username = (string) $settings['smtp_username'];
        $phpmailer->Password = (string) $settings['smtp_password'];
    }
}
add_action('phpmailer_init', 'mykitchen_apply_mailer_settings');

function mykitchen_mail_from_email($email): string
{
    $settings = mykitchen_get_contact_settings();
    return $settings['from_email'] ? $settings['from_email'] : $email;
}
add_filter('wp_mail_from', 'mykitchen_mail_from_email');

function mykitchen_mail_from_name($name): string
{
    $settings = mykitchen_get_contact_settings();
    return $settings['from_name'] ? $settings['from_name'] : $name;
}
add_filter('wp_mail_from_name', 'mykitchen_mail_from_name');

function mykitchen_translate_view_cart_label(string $message, array $products = array(), bool $show_qty = false): string
{
    $view_cart = esc_html__('View cart', 'woocommerce');
    if (strpos($message, $view_cart) !== false) {
        $message = str_replace($view_cart, 'اذهب للسلة', $message);
    }

    return $message;
}
add_filter('wc_add_to_cart_message_html', 'mykitchen_translate_view_cart_label', 10, 3);

function mykitchen_added_to_cart_link_label(string $link): string
{
    return str_replace('View cart', 'اذهب للسلة', $link);
}
add_filter('woocommerce_add_to_cart_added_to_cart_link', 'mykitchen_added_to_cart_link_label');

function mykitchen_translate_add_to_cart_params($params): array
{
    if (is_array($params) && isset($params['i18n_view_cart'])) {
        $params['i18n_view_cart'] = 'اذهب للسلة';
    }
    return $params;
}
add_filter('woocommerce_add_to_cart_params', 'mykitchen_translate_add_to_cart_params');

function mykitchen_cart_count_fragment($fragments): array
{
    $count = 0;
    if (function_exists('WC') && WC()->cart) {
        $count = (int) WC()->cart->get_cart_contents_count();
    }

    $classes = 'myk-cart-count';
    $label = '';
    if ($count > 0) {
        $label = (string) $count;
    } else {
        $classes .= ' is-empty';
    }
    $fragments['span.myk-cart-count'] = '<span class="' . esc_attr($classes) . '" data-y="cart-count">' . esc_html($label) . '</span>';

    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'mykitchen_cart_count_fragment');
function mykitchen_register_content_menu(): void
{
    add_menu_page(
        'المحتوى',
        'المحتوى',
        'edit_pages',
        'mykitchen-content',
        'mykitchen_render_content_page',
        'dashicons-media-document',
        25
    );

    add_submenu_page(
        'mykitchen-content',
        'تجهيز الصفحات',
        'تجهيز الصفحات',
        'manage_options',
        'mykitchen-setup-pages',
        'mykitchen_render_setup_pages'
    );

    add_submenu_page(
        'mykitchen-content',
        'الصفحة الرئيسية',
        'الصفحة الرئيسية',
        'manage_options',
        'mykitchen-homepage',
        'mykitchen_render_homepage_settings'
    );

    add_submenu_page(
        'mykitchen-content',
        'منتجات ديمو',
        'منتجات ديمو',
        'manage_options',
        'mykitchen-demo-products',
        'mykitchen_render_demo_products'
    );

    add_submenu_page(
        'mykitchen-content',
        'صفحة تواصل معنا',
        'صفحة تواصل معنا',
        'manage_options',
        'mykitchen-contact-settings',
        'mykitchen_render_contact_settings'
    );

    add_submenu_page(
        'mykitchen-content',
        'سياسات الموقع',
        'سياسات الموقع',
        'manage_options',
        'mykitchen-policy-settings',
        'mykitchen_render_policy_settings'
    );

    add_submenu_page(
        'mykitchen-content',
        'إعدادات الموقع',
        'إعدادات الموقع',
        'manage_options',
        'mykitchen-site-settings',
        'mykitchen_render_site_settings'
    );
}
add_action('admin_menu', 'mykitchen_register_content_menu');

function mykitchen_product_cat_add_icon_field(): void
{
    ?>
    <div class="form-field term-group">
        <label for="mykitchen_nav_icon">أيقونة القائمة</label>
        <input type="text" id="mykitchen_nav_icon" name="mykitchen_nav_icon" placeholder="fa-solid fa-plug-circle-bolt" />
        <p class="description">اكتب كلاس أيقونة Font Awesome ليظهر بجانب التصنيف في الهيدر.</p>
    </div>
    <?php
}
add_action('product_cat_add_form_fields', 'mykitchen_product_cat_add_icon_field');

function mykitchen_product_cat_edit_icon_field($term): void
{
    $icon = (string) get_term_meta((int) $term->term_id, 'mykitchen_nav_icon', true);
    ?>
    <tr class="form-field term-group-wrap">
        <th scope="row"><label for="mykitchen_nav_icon">أيقونة القائمة</label></th>
        <td>
            <input type="text" id="mykitchen_nav_icon" name="mykitchen_nav_icon" value="<?php echo esc_attr($icon); ?>" placeholder="fa-solid fa-plug-circle-bolt" />
            <p class="description">اكتب كلاس أيقونة Font Awesome ليظهر بجانب التصنيف في الهيدر.</p>
        </td>
    </tr>
    <?php
}
add_action('product_cat_edit_form_fields', 'mykitchen_product_cat_edit_icon_field');

function mykitchen_save_product_cat_icon(int $term_id): void
{
    if (!isset($_POST['mykitchen_nav_icon'])) {
        return;
    }
    $icon = sanitize_text_field(wp_unslash($_POST['mykitchen_nav_icon']));
    if ($icon) {
        update_term_meta($term_id, 'mykitchen_nav_icon', $icon);
    } else {
        delete_term_meta($term_id, 'mykitchen_nav_icon');
    }
}
add_action('created_product_cat', 'mykitchen_save_product_cat_icon');
add_action('edited_product_cat', 'mykitchen_save_product_cat_icon');

function mykitchen_ensure_shop_page(): void
{
    if (!function_exists('wc_get_page_id')) {
        return;
    }

    $shop = get_page_by_path('shop');
    if (!$shop) {
        $shop_id = wp_insert_post(
            array(
                'post_title' => 'المتجر',
                'post_name' => 'shop',
                'post_status' => 'publish',
                'post_type' => 'page',
            )
        );

        if (is_wp_error($shop_id)) {
            return;
        }

        $shop = get_post((int) $shop_id);
    }

    if ($shop && isset($shop->ID)) {
        wp_update_post(
            array(
                'ID' => $shop->ID,
                'post_title' => 'المتجر',
            )
        );
        update_option('woocommerce_shop_page_id', (int) $shop->ID);
    }

    $offers = get_page_by_path('offers');
    if (!$offers) {
        $offers_id = wp_insert_post(
            array(
                'post_title' => 'العروض',
                'post_name' => 'offers',
                'post_status' => 'publish',
                'post_type' => 'page',
            )
        );
        if (!is_wp_error($offers_id)) {
            $offers = get_post((int) $offers_id);
        }
    }
    if ($offers && isset($offers->ID)) {
        wp_update_post(
            array(
                'ID' => $offers->ID,
                'post_title' => 'العروض',
            )
        );
    }
}
add_action('after_switch_theme', 'mykitchen_ensure_shop_page');

function mykitchen_handle_logout_request(): void
{
    $path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    $path = $path ? trim($path, '/') : '';
    if ($path && preg_match('/(^|\/)logout$/', $path)) {
        wp_logout();
        wp_safe_redirect(home_url('/login/'));
        exit;
    }
}
add_action('template_redirect', 'mykitchen_handle_logout_request');

function mykitchen_handle_buy_now(): void
{
    if (!function_exists('WC') || !is_product()) {
        return;
    }

    if (empty($_POST['myk_buy_now'])) {
        return;
    }

    $product_id = absint($_POST['product_id'] ?? ($_POST['add-to-cart'] ?? 0));
    if (!$product_id) {
        return;
    }

    $quantity = 1;
    if (isset($_POST['quantity'])) {
        $quantity = wc_stock_amount(wp_unslash($_POST['quantity']));
        if ($quantity < 1) {
            $quantity = 1;
        }
    }

    if (WC()->session) {
        WC()->session->set('myk_hide_added_notice', true);
    }
    $added = WC()->cart->add_to_cart($product_id, $quantity);
    if ($added) {
        wp_safe_redirect(wc_get_checkout_url());
        exit;
    }
}
add_action('template_redirect', 'mykitchen_handle_buy_now', 9);

function mykitchen_hide_added_to_cart_notice($message): string
{
    if (function_exists('WC') && WC()->session && WC()->session->get('myk_hide_added_notice')) {
        WC()->session->__unset('myk_hide_added_notice');
        return '';
    }

    return $message;
}
add_filter('wc_add_to_cart_message_html', 'mykitchen_hide_added_to_cart_notice', 20);

function mykitchen_homepage_defaults(): array
{
    $assets_uri = MYK_ASSETS_URI . '/assets';

    return array(
        'banners' => array(
            'primary_id' => 0,
            'primary' => $assets_uri . '/header1.png',
            'secondary_id' => 0,
            'secondary' => $assets_uri . '/header2.png',
        ),
        'less_than_price' => 99,
        'last_chance_title' => 'الفرصة الأخيرة - تصفية حتى نفاذ الكمية !',
        'last_chance_product_ids' => array(),
        'phrases' => array(
            array(
                'image_id' => 0,
                'image' => $assets_uri . '/adv1.png',
                'title' => 'أسعار تنافسية',
                'text' => 'أفضل الأسعار في السوق مع ضمان الجودة',
            ),
            array(
                'image_id' => 0,
                'image' => $assets_uri . '/adv2.png',
                'title' => 'توصيل سريع',
                'text' => 'خدمة توصيل سريعة وموثوقة إلى باب منزلك',
            ),
            array(
                'image_id' => 0,
                'image' => $assets_uri . '/adv3.png',
                'title' => 'خدمة عملاء ممتازة',
                'text' => 'فريق دعم متعاون وجاهز لمساعدتك في أي وقت',
            ),
        ),
    );
}

function mykitchen_homepage_deep_merge(array $data, array $defaults): array
{
    $merged = $defaults;
    foreach ($data as $key => $value) {
        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
            $merged[$key] = mykitchen_homepage_deep_merge($value, $merged[$key]);
        } else {
            $merged[$key] = $value;
        }
    }

    return $merged;
}

function mykitchen_get_homepage_settings(): array
{
    $saved = get_option('mykitchen_homepage_settings', array());
    $defaults = mykitchen_homepage_defaults();

    if (!is_array($saved)) {
        $saved = array();
    }

    return mykitchen_homepage_deep_merge($saved, $defaults);
}

function mykitchen_render_content_page(): void
{
    echo '<div class="wrap"><h1>المحتوى</h1><p>اختر من القائمة الجانبية لتجهيز الصفحات أو إنشاء المحتوى التجريبي.</p></div>';
}

function mykitchen_render_homepage_settings(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['mykitchen_homepage_save'])) {
        check_admin_referer('mykitchen_homepage_settings');

        $less_than_price = (float) wp_unslash($_POST['homepage_less_than_price'] ?? 99);
        if ($less_than_price <= 0) {
            $less_than_price = 99;
        }
        $last_chance_title = sanitize_text_field(wp_unslash($_POST['homepage_last_chance_title'] ?? ''));
        $last_chance_ids = array();
        if (!empty($_POST['homepage_last_chance_products']) && is_array($_POST['homepage_last_chance_products'])) {
            $last_chance_ids = array_values(
                array_filter(
                    array_map('absint', wp_unslash($_POST['homepage_last_chance_products']))
                )
            );
        }
        $settings = array(
            'banners' => array(
                'primary_id' => absint($_POST['homepage_banner_primary_id'] ?? 0),
                'primary' => esc_url_raw(wp_unslash($_POST['homepage_banner_primary'] ?? '')),
                'secondary_id' => absint($_POST['homepage_banner_secondary_id'] ?? 0),
                'secondary' => esc_url_raw(wp_unslash($_POST['homepage_banner_secondary'] ?? '')),
            ),
            'less_than_price' => $less_than_price,
            'last_chance_title' => $last_chance_title ?: 'الفرصة الأخيرة - تصفية حتى نفاذ الكمية !',
            'last_chance_product_ids' => $last_chance_ids,
            'phrases' => array(),
        );

        for ($i = 0; $i < 3; $i++) {
            $settings['phrases'][] = array(
                'image_id' => absint($_POST['homepage_phrase_image_id_' . $i] ?? 0),
                'image' => esc_url_raw(wp_unslash($_POST['homepage_phrase_image_' . $i] ?? '')),
                'title' => sanitize_text_field(wp_unslash($_POST['homepage_phrase_title_' . $i] ?? '')),
                'text' => sanitize_textarea_field(wp_unslash($_POST['homepage_phrase_text_' . $i] ?? '')),
            );
        }

        update_option('mykitchen_homepage_settings', $settings);
        echo '<div class="notice notice-success is-dismissible"><p>تم حفظ التغييرات بنجاح.</p></div>';
    }

    if (isset($_POST['mykitchen_homepage_reset'])) {
        check_admin_referer('mykitchen_homepage_settings');
        delete_option('mykitchen_homepage_settings');
        echo '<div class="notice notice-success is-dismissible"><p>تمت استعادة المحتوى الأصلي للصفحة الرئيسية.</p></div>';
    }

    $settings = mykitchen_get_homepage_settings();
    $banners = $settings['banners'] ?? array();
    $phrases = $settings['phrases'] ?? array();
    while (count($phrases) < 3) {
        $phrases[] = array('image_id' => 0, 'image' => '', 'title' => '', 'text' => '');
    }

    echo '<div class="wrap">';
    echo '<h1>محتوى الصفحة الرئيسية</h1>';
    echo '<form method="post" action="">';
    wp_nonce_field('mykitchen_homepage_settings');

    echo '<h2>البنرات</h2>';
    echo '<table class="form-table"><tbody>';
    echo '<tr>';
    echo '<th scope="row"><label for="homepage_banner_primary">صورة البنر الأولى</label></th>';
    $primary_id = absint($banners['primary_id'] ?? 0);
    $primary_url = $banners['primary'] ?? '';
    if ($primary_id) {
        $primary_url = wp_get_attachment_url($primary_id) ?: $primary_url;
    }
    echo '<td>';
    echo '<div class="myk-upload-field">';
    echo '<div class="myk-upload-preview" id="homepage_banner_primary_preview">';
    if ($primary_url) {
        echo '<img src="' . esc_url($primary_url) . '" alt="" style="max-width:220px;height:auto;">';
    } else {
        echo '<span>لا توجد صورة</span>';
    }
    echo '</div>';
    echo '<div class="myk-upload-actions">';
    echo '<button type="button" class="button myk-upload-btn" data-target-id="homepage_banner_primary_id" data-target-url="homepage_banner_primary" data-preview="homepage_banner_primary_preview">اختيار صورة</button> ';
    echo '<button type="button" class="button myk-remove-btn" data-target-id="homepage_banner_primary_id" data-target-url="homepage_banner_primary" data-preview="homepage_banner_primary_preview">إزالة</button>';
    echo '</div>';
    echo '<input type="hidden" id="homepage_banner_primary_id" name="homepage_banner_primary_id" value="' . esc_attr($primary_id) . '">';
    echo '<input type="hidden" id="homepage_banner_primary" name="homepage_banner_primary" value="' . esc_attr($primary_url) . '">';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th scope="row"><label for="homepage_banner_secondary">صورة البنر الثانية</label></th>';
    $secondary_id = absint($banners['secondary_id'] ?? 0);
    $secondary_url = $banners['secondary'] ?? '';
    if ($secondary_id) {
        $secondary_url = wp_get_attachment_url($secondary_id) ?: $secondary_url;
    }
    echo '<td>';
    echo '<div class="myk-upload-field">';
    echo '<div class="myk-upload-preview" id="homepage_banner_secondary_preview">';
    if ($secondary_url) {
        echo '<img src="' . esc_url($secondary_url) . '" alt="" style="max-width:220px;height:auto;">';
    } else {
        echo '<span>لا توجد صورة</span>';
    }
    echo '</div>';
    echo '<div class="myk-upload-actions">';
    echo '<button type="button" class="button myk-upload-btn" data-target-id="homepage_banner_secondary_id" data-target-url="homepage_banner_secondary" data-preview="homepage_banner_secondary_preview">اختيار صورة</button> ';
    echo '<button type="button" class="button myk-remove-btn" data-target-id="homepage_banner_secondary_id" data-target-url="homepage_banner_secondary" data-preview="homepage_banner_secondary_preview">إزالة</button>';
    echo '</div>';
    echo '<input type="hidden" id="homepage_banner_secondary_id" name="homepage_banner_secondary_id" value="' . esc_attr($secondary_id) . '">';
    echo '<input type="hidden" id="homepage_banner_secondary" name="homepage_banner_secondary" value="' . esc_attr($secondary_url) . '">';
    echo '</div>';
    echo '</td>';
    echo '</tr>';
    echo '</tbody></table>';

    echo '<h2>قسم أقل من</h2>';
    echo '<table class="form-table"><tbody>';
    echo '<tr>';
    echo '<th scope="row"><label for="homepage_less_than_price">القيمة (ريال)</label></th>';
    echo '<td><input class="regular-text" id="homepage_less_than_price" name="homepage_less_than_price" type="number" min="1" step="1" value="' . esc_attr($settings['less_than_price'] ?? 99) . '"></td>';
    echo '</tr>';
    echo '</tbody></table>';

    $last_chance_title = $settings['last_chance_title'] ?? 'الفرصة الأخيرة - تصفية حتى نفاذ الكمية !';
    $last_chance_selected = $settings['last_chance_product_ids'] ?? array();
    $last_chance_selected = is_array($last_chance_selected) ? array_map('absint', $last_chance_selected) : array();
    $last_chance_products = wc_get_products(
        array(
            'limit' => 200,
            'orderby' => 'title',
            'order' => 'ASC',
            'status' => 'publish',
        )
    );
    $last_chance_product_map = array();
    foreach ($last_chance_products as $product) {
        $last_chance_product_map[$product->get_id()] = $product->get_name();
    }
    echo '<h2>قسم الفرصة الأخيرة</h2>';
    echo '<table class="form-table"><tbody>';
    echo '<tr>';
    echo '<th scope="row"><label for="homepage_last_chance_title">عنوان القسم</label></th>';
    echo '<td><input class="regular-text" id="homepage_last_chance_title" name="homepage_last_chance_title" type="text" value="' . esc_attr($last_chance_title) . '"></td>';
    echo '</tr>';
    echo '<tr>';
    echo '<th scope="row"><label for="homepage_last_chance_products">المنتجات المعروضة</label></th>';
    echo '<td>';
    echo '<div class="myk-last-chance-picker">';
    echo '<select id="homepage_last_chance_picker" style="min-width:320px;">';
    echo '<option value="">اختر منتجًا</option>';
    foreach ($last_chance_products as $product) {
        $product_id = $product->get_id();
        echo '<option value="' . esc_attr($product_id) . '">' . esc_html($product->get_name()) . '</option>';
    }
    echo '</select> ';
    echo '<button type="button" class="button myk-add-last-chance">إضافة</button>';
    echo '</div>';
    echo '<ul id="homepage_last_chance_selected" class="myk-selected-list">';
    foreach ($last_chance_selected as $product_id) {
        if (empty($product_id)) {
            continue;
        }
        $label = $last_chance_product_map[$product_id] ?? ('#' . $product_id);
        echo '<li class="myk-selected-item" data-product-id="' . esc_attr($product_id) . '">';
        echo '<span class="myk-selected-label">' . esc_html($label) . '</span>';
        echo '<button type="button" class="button myk-remove-selected">إزالة</button>';
        echo '<input type="hidden" name="homepage_last_chance_products[]" value="' . esc_attr($product_id) . '">';
        echo '</li>';
    }
    echo '</ul>';
    echo '<p class="description">اختر المنتجات التي تظهر داخل قسم الفرصة الأخيرة، ويمكنك حذف أي منتج من القائمة.</p>';
    echo '</td>';
    echo '</tr>';
    echo '</tbody></table>';

    echo '<h2>العبارات</h2>';
    for ($i = 0; $i < 3; $i++) {
        $phrase = $phrases[$i];
        echo '<div class="postbox" style="padding:16px;margin:16px 0;">';
        echo '<h3 style="margin-top:0;">عبارة رقم ' . esc_html($i + 1) . '</h3>';
        echo '<p><label for="homepage_phrase_image_' . esc_attr($i) . '">صورة العبارة</label><br>';
        $phrase_image_id = absint($phrase['image_id'] ?? 0);
        $phrase_image_url = $phrase['image'] ?? '';
        if ($phrase_image_id) {
            $phrase_image_url = wp_get_attachment_url($phrase_image_id) ?: $phrase_image_url;
        }
        echo '<div class="myk-upload-field">';
        echo '<div class="myk-upload-preview" id="homepage_phrase_preview_' . esc_attr($i) . '">';
        if ($phrase_image_url) {
            echo '<img src="' . esc_url($phrase_image_url) . '" alt="" style="max-width:180px;height:auto;">';
        } else {
            echo '<span>لا توجد صورة</span>';
        }
        echo '</div>';
        echo '<div class="myk-upload-actions">';
        echo '<button type="button" class="button myk-upload-btn" data-target-id="homepage_phrase_image_id_' . esc_attr($i) . '" data-target-url="homepage_phrase_image_' . esc_attr($i) . '" data-preview="homepage_phrase_preview_' . esc_attr($i) . '">اختيار صورة</button> ';
        echo '<button type="button" class="button myk-remove-btn" data-target-id="homepage_phrase_image_id_' . esc_attr($i) . '" data-target-url="homepage_phrase_image_' . esc_attr($i) . '" data-preview="homepage_phrase_preview_' . esc_attr($i) . '">إزالة</button>';
        echo '</div>';
        echo '<input type="hidden" id="homepage_phrase_image_id_' . esc_attr($i) . '" name="homepage_phrase_image_id_' . esc_attr($i) . '" value="' . esc_attr($phrase_image_id) . '">';
        echo '<input type="hidden" id="homepage_phrase_image_' . esc_attr($i) . '" name="homepage_phrase_image_' . esc_attr($i) . '" value="' . esc_attr($phrase_image_url) . '">';
        echo '</div></p>';
        echo '<p><label for="homepage_phrase_title_' . esc_attr($i) . '">العنوان</label><br>';
        echo '<input class="regular-text" id="homepage_phrase_title_' . esc_attr($i) . '" name="homepage_phrase_title_' . esc_attr($i) . '" type="text" value="' . esc_attr($phrase['title'] ?? '') . '"></p>';
        echo '<p><label for="homepage_phrase_text_' . esc_attr($i) . '">الوصف</label><br>';
        echo '<textarea class="large-text" rows="2" id="homepage_phrase_text_' . esc_attr($i) . '" name="homepage_phrase_text_' . esc_attr($i) . '">' . esc_textarea($phrase['text'] ?? '') . '</textarea></p>';
        echo '</div>';
    }

    echo '<p>';
    echo '<button type="submit" name="mykitchen_homepage_save" class="button button-primary">حفظ التغييرات</button> ';
    echo '<button type="submit" name="mykitchen_homepage_reset" class="button">استعادة المحتوى الأصلي</button>';
    echo '</p>';

    echo '</form>';
    echo '</div>';
}

function mykitchen_render_setup_pages(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['mykitchen_setup_pages'])) {
        check_admin_referer('mykitchen_setup_pages');
        $result = mykitchen_create_or_update_pages();
        echo '<div class="notice notice-success is-dismissible"><p>';
        echo esc_html(sprintf('تم تجهيز الصفحات: %d إنشاء/تحديث.', $result['updated']));
        echo '</p></div>';
    }

    echo '<div class="wrap">';
    echo '<h1>تجهيز الصفحات</h1>';
    echo '<p>ينشئ أو يحدّث صفحات القالب المطلوبة ويضبط صفحات WooCommerce الأساسية.</p>';
    echo '<p><strong>الصفحات المطلوبة:</strong> الرئيسية، المتجر، العروض، السلة، الدفع، حسابي، المفضلة، من نحن، تواصل معنا، سياسة الخصوصية، سياسة الاسترجاع والاسترداد، سياسة الشحن، تسجيل الدخول، تسجيل الخروج، إنشاء حساب، نسيت كلمة المرور، إنشاء كلمة المرور.</p>';
    echo '<form method="post">';
    wp_nonce_field('mykitchen_setup_pages');
    submit_button('إنشاء/تحديث الصفحات', 'primary', 'mykitchen_setup_pages');
    echo '</form>';
    echo mykitchen_render_pages_table();
    echo '</div>';
}

function mykitchen_render_demo_products(): void
{
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['mykitchen_create_demo_products'])) {
        check_admin_referer('mykitchen_create_demo_products');
        $result = mykitchen_create_demo_products();
        echo '<div class="notice notice-success is-dismissible"><p>';
        echo esc_html(sprintf('تم تجهيز المنتجات الديمو: %d إنشاء/تحديث.', $result['updated']));
        echo '</p></div>';
    }

    if (isset($_POST['mykitchen_delete_demo_products'])) {
        check_admin_referer('mykitchen_delete_demo_products');
        $result = mykitchen_delete_demo_products();
        echo '<div class="notice notice-success is-dismissible"><p>';
        echo esc_html(
            sprintf(
                'تم حذف المنتجات الديمو: %d منتج، %d تصنيف، %d ماركة.',
                $result['products'],
                $result['categories'],
                $result['brands']
            )
        );
        echo '</p></div>';
    }

    echo '<div class="wrap">';
    echo '<h1>منتجات ديمو</h1>';
    echo '<p>ينشئ منتجات ديمو ويستخدم صور القالب.</p>';
    echo '<form method="post">';
    wp_nonce_field('mykitchen_create_demo_products');
    submit_button('إنشاء/تحديث منتجات ديمو', 'primary', 'mykitchen_create_demo_products');
    echo '</form>';
    echo '<form method="post" style="margin-top:12px;">';
    wp_nonce_field('mykitchen_delete_demo_products');
    submit_button('حذف منتجات الديمو', 'secondary', 'mykitchen_delete_demo_products', false);
    echo '</form>';
    echo mykitchen_render_products_table();
    echo '</div>';
}

function mykitchen_create_or_update_pages(): array
{
    $policy_settings = function_exists('mykitchen_get_policy_settings')
        ? mykitchen_get_policy_settings()
        : array();
    $policy_map = array(
        'privacy-policy' => $policy_settings['privacy'] ?? array(),
        'refund-policy' => $policy_settings['refund'] ?? array(),
        'using-policy' => $policy_settings['using'] ?? array(),
    );

    $pages = array(
        array('title' => 'الرئيسية', 'slug' => 'home'),
        array('title' => 'المتجر', 'slug' => 'shop'),
        array('title' => 'العروض', 'slug' => 'offers'),
        array('title' => 'السلة', 'slug' => 'cart'),
        array('title' => 'الدفع', 'slug' => 'checkout'),
        array('title' => 'حسابي', 'slug' => 'my-account'),
        array('title' => 'المفضلة', 'slug' => 'wishlist'),
        array('title' => 'من نحن', 'slug' => 'about-us'),
        array('title' => 'تواصل معنا', 'slug' => 'contact-us'),
        array('title' => 'سياسة الخصوصية', 'slug' => 'privacy-policy'),
        array('title' => 'سياسة الاسترجاع والاسترداد', 'slug' => 'refund-policy'),
        array('title' => 'سياسة الشحن', 'slug' => 'using-policy'),
        array('title' => 'تسجيل الدخول', 'slug' => 'login'),
        array('title' => 'تسجيل الخروج', 'slug' => 'logout'),
        array('title' => 'إنشاء حساب', 'slug' => 'sign-up'),
        array('title' => 'نسيت كلمة المرور', 'slug' => 'reset-password'),
        array('title' => 'إنشاء كلمة المرور', 'slug' => 'create-password'),
    );

    $updated = 0;

    foreach ($pages as $page) {
        $existing = get_page_by_path($page['slug']);
        $title = $page['title'];
        $content = '';
        if (isset($policy_map[$page['slug']])) {
            $policy = $policy_map[$page['slug']];
            if (!empty($policy['title'])) {
                $title = (string) $policy['title'];
            }
            if (!empty($policy['content'])) {
                $content = (string) $policy['content'];
            }
        }

        $post_data = array(
            'post_title' => $title,
            'post_name' => $page['slug'],
            'post_content' => $content,
            'post_status' => 'publish',
            'post_type' => 'page',
        );

        if ($existing) {
            $post_data['ID'] = $existing->ID;
            wp_update_post($post_data);
            $page_id = $existing->ID;
        } else {
            $page_id = wp_insert_post($post_data);
        }

        if (!is_wp_error($page_id)) {
            $updated++;
        }
    }

    $front = get_page_by_path('home');
    if ($front) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $front->ID);
    }

    if (class_exists('WooCommerce')) {
        $shop = get_page_by_path('shop');
        $cart = get_page_by_path('cart');
        $checkout = get_page_by_path('checkout');
        $account = get_page_by_path('my-account');

        if ($shop) {
            update_option('woocommerce_shop_page_id', $shop->ID);
        }
        if ($cart) {
            update_option('woocommerce_cart_page_id', $cart->ID);
        }
        if ($checkout) {
            update_option('woocommerce_checkout_page_id', $checkout->ID);
        }
        if ($account) {
            update_option('woocommerce_myaccount_page_id', $account->ID);
        }
    }

    return array('updated' => $updated);
}

function mykitchen_create_demo_products(): array
{
    if (!class_exists('WC_Product')) {
        return array('updated' => 0);
    }

    $categories = array(
        array('name' => 'أجهزة كهربائية', 'slug' => 'electronics'),
        array('name' => 'أدوات منزلية', 'slug' => 'for-home'),
        array('name' => 'الديكورات', 'slug' => 'decoration'),
    );

    $category_ids = array();
    $category_images = array(
        'electronics' => 'category2.png',
        'for-home' => 'category1.png',
        'decoration' => 'category3.png',
    );
    $category_image_ids = array();
    foreach ($category_images as $slug => $filename) {
        $image_path = get_template_directory() . '/my-kitchen/assets/' . $filename;
        $image_id = mykitchen_import_asset_image($image_path);
        if ($image_id) {
            $category_image_ids[$slug] = $image_id;
        }
    }
    foreach ($categories as $category) {
        $term = get_term_by('slug', $category['slug'], 'product_cat');
        if (!$term) {
            $term = wp_insert_term($category['name'], 'product_cat', array('slug' => $category['slug']));
        }
        if (!is_wp_error($term)) {
            $category_ids[$category['slug']] = is_array($term) ? $term['term_id'] : $term->term_id;
            $term_id = $category_ids[$category['slug']];
            if ($term_id && isset($category_image_ids[$category['slug']])) {
                update_term_meta($term_id, 'thumbnail_id', $category_image_ids[$category['slug']]);
            }
        }
    }

    $image_path = get_template_directory() . '/my-kitchen/assets/product.png';
    $image_id = mykitchen_import_asset_image($image_path);

    $brand_term_ids = array();
    if (taxonomy_exists('product_brand')) {
        $brand_files = array('brand1.png', 'brand2.png', 'brand3.png', 'brand4.png', 'brand5.png');
        $brand_names = array('ماركة 1', 'ماركة 2', 'ماركة 3', 'ماركة 4', 'ماركة 5');
        foreach ($brand_files as $index => $filename) {
            $brand_slug = 'brand-' . ($index + 1);
            $term = get_term_by('slug', $brand_slug, 'product_brand');
            if (!$term) {
                $term = wp_insert_term($brand_names[$index], 'product_brand', array('slug' => $brand_slug));
            }
            if (!is_wp_error($term)) {
                $term_id = is_array($term) ? $term['term_id'] : $term->term_id;
                $brand_term_ids[] = $term_id;
                $brand_image_path = get_template_directory() . '/my-kitchen/assets/' . $filename;
                $brand_image_id = mykitchen_import_asset_image($brand_image_path);
                if ($brand_image_id) {
                    update_term_meta($term_id, 'thumbnail_id', $brand_image_id);
                }
            }
        }
    }

    $products = array();
    $base_name = 'تاتش قلاية كهربائية هيلثي بلس 8 لتر قوة 2400 وات';

    $category_plan = array(
        'electronics' => 10,
        'for-home' => 10,
        'decoration' => 10,
        'uncategorized' => 10,
    );

    $brand_index = 0;
    $brand_total = count($brand_term_ids);
    $total_products = array_sum($category_plan);
    $price_min = 10;
    $price_max = 800;
    $price_step = $total_products > 1 ? ($price_max - $price_min) / ($total_products - 1) : 0;
    $product_index = 0;
    foreach ($category_plan as $category => $count) {
        for ($i = 1; $i <= $count; $i++) {
            $brand_term_id = 0;
            if ($brand_total) {
                $brand_term_id = $brand_term_ids[$brand_index % $brand_total];
                $brand_index++;
            }
            $product_index++;
            $price_value = (int) round($price_min + (($product_index - 1) * $price_step));
            $products[] = array(
                'name' => $base_name,
                'sku' => 'myk-demo-' . $category . '-' . $i,
                'regular_price' => (string) $price_value,
                'sale_price' => '',
                'category' => $category,
                'brand_term_id' => $brand_term_id,
            );
        }
    }

    $updated = 0;

    foreach ($products as $product_data) {
        $existing_id = wc_get_product_id_by_sku($product_data['sku']);
        $product = $existing_id ? wc_get_product($existing_id) : new WC_Product_Simple();

        if (!$product) {
            continue;
        }

        $product->set_name($product_data['name']);
        $product->set_sku($product_data['sku']);
        $product->set_regular_price($product_data['regular_price']);
        if (!empty($product_data['sale_price'])) {
            $product->set_sale_price($product_data['sale_price']);
        } else {
            $product->set_sale_price('');
        }
        $product->set_status('publish');

        if (isset($category_ids[$product_data['category']])) {
            $product->set_category_ids(array($category_ids[$product_data['category']]));
        } else {
            $product->set_category_ids(array());
        }

        if ($image_id) {
            $product->set_image_id($image_id);
        }

        $product->save();
        if (!empty($product_data['brand_term_id']) && taxonomy_exists('product_brand')) {
            wp_set_object_terms(
                $product->get_id(),
                array((int) $product_data['brand_term_id']),
                'product_brand',
                false
            );
        }
        $updated++;
    }

    return array('updated' => $updated);
}

function mykitchen_delete_demo_products(): array
{
    if (!class_exists('WC_Product')) {
        return array('products' => 0, 'categories' => 0, 'brands' => 0);
    }

    $products_deleted = 0;
    $query = new WP_Query(
        array(
            'post_type' => 'product',
            'post_status' => array('publish', 'draft', 'pending', 'private'),
            'posts_per_page' => -1,
            'fields' => 'ids',
            'meta_query' => array(
                array(
                    'key' => '_sku',
                    'value' => 'myk-demo-',
                    'compare' => 'LIKE',
                ),
            ),
        )
    );

    if ($query->posts) {
        foreach ($query->posts as $product_id) {
            wp_delete_post((int) $product_id, true);
            $products_deleted++;
        }
    }

    $categories_deleted = 0;
    $demo_category_slugs = array('electronics', 'for-home', 'decoration');
    foreach ($demo_category_slugs as $slug) {
        $term = get_term_by('slug', $slug, 'product_cat');
        if ($term && !is_wp_error($term)) {
            $deleted = wp_delete_term($term->term_id, 'product_cat');
            if (!is_wp_error($deleted)) {
                $categories_deleted++;
            }
        }
    }

    $brands_deleted = 0;
    if (taxonomy_exists('product_brand')) {
        for ($i = 1; $i <= 5; $i++) {
            $brand = get_term_by('slug', 'brand-' . $i, 'product_brand');
            if ($brand && !is_wp_error($brand)) {
                $deleted = wp_delete_term($brand->term_id, 'product_brand');
                if (!is_wp_error($deleted)) {
                    $brands_deleted++;
                }
            }
        }
    }

    return array(
        'products' => $products_deleted,
        'categories' => $categories_deleted,
        'brands' => $brands_deleted,
    );
}

function mykitchen_render_pages_table(): string
{
    $pages = get_pages(array('sort_column' => 'post_title', 'sort_order' => 'ASC'));
    if (!$pages) {
        return '<p>لا توجد صفحات حالياً.</p>';
    }

    $html = '<h2>قائمة الصفحات</h2>';
    $html .= '<table class="widefat striped"><thead><tr>';
    $html .= '<th>العنوان</th><th>الرابط</th><th>الحالة</th>';
    $html .= '</tr></thead><tbody>';

    foreach ($pages as $page) {
        $title = esc_html($page->post_title);
        $link = get_permalink($page->ID);
        $status = esc_html($page->post_status);
        $html .= '<tr>';
        $html .= '<td>' . $title . '</td>';
        $html .= '<td><a href="' . esc_url($link) . '" target="_blank" rel="noopener">عرض</a></td>';
        $html .= '<td>' . $status . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';
    return $html;
}

function mykitchen_render_products_table(): string
{
    if (!class_exists('WooCommerce')) {
        return '<p>WooCommerce غير مفعّل.</p>';
    }

    $products = wc_get_products(
        array(
            'limit' => 50,
            'orderby' => 'title',
            'order' => 'ASC',
            'status' => array('publish', 'draft'),
        )
    );

    if (!$products) {
        return '<p>لا توجد منتجات حالياً.</p>';
    }

    $html = '<h2>قائمة المنتجات</h2>';
    $html .= '<table class="widefat striped"><thead><tr>';
    $html .= '<th>المنتج</th><th>السعر</th><th>الحالة</th><th>الرابط</th>';
    $html .= '</tr></thead><tbody>';

    foreach ($products as $product) {
        $title = esc_html($product->get_name());
        $price = $product->get_price();
        $status = esc_html($product->get_status());
        $link = get_permalink($product->get_id());
        $html .= '<tr>';
        $html .= '<td>' . $title . '</td>';
        $html .= '<td>' . esc_html($price) . '</td>';
        $html .= '<td>' . $status . '</td>';
        $html .= '<td><a href="' . esc_url($link) . '" target="_blank" rel="noopener">عرض</a></td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';
    return $html;
}

function mykitchen_get_asset_url(string $filename): string
{
    return MYK_ASSETS_URI . '/assets/' . ltrim($filename, '/');
}

function mykitchen_get_wishlist_ids(): array
{
    $ids = array();
    if (!empty($_COOKIE['myk_wishlist'])) {
        $decoded = json_decode(wp_unslash($_COOKIE['myk_wishlist']), true);
        if (is_array($decoded)) {
            $ids = array_merge($ids, $decoded);
        }
    }

    if (is_user_logged_in()) {
        $meta = get_user_meta(get_current_user_id(), 'myk_wishlist', true);
        if (is_array($meta)) {
            $ids = array_merge($ids, $meta);
        }
    }

    $ids = array_map('absint', $ids);
    $ids = array_values(array_filter(array_unique($ids)));

    return $ids;
}

function mykitchen_sync_wishlist_from_cookie(): void
{
    if (!is_user_logged_in() || empty($_COOKIE['myk_wishlist'])) {
        return;
    }

    $decoded = json_decode(wp_unslash($_COOKIE['myk_wishlist']), true);
    if (!is_array($decoded)) {
        return;
    }

    $ids = array_values(array_filter(array_map('absint', $decoded)));
    update_user_meta(get_current_user_id(), 'myk_wishlist', $ids);
}
add_action('init', 'mykitchen_sync_wishlist_from_cookie');

function mykitchen_render_product_card(WC_Product $product, array $args = array()): string
{
    $show_badge = !empty($args['show_badge']);
    $wishlist_ids = $args['wishlist_ids'] ?? mykitchen_get_wishlist_ids();
    $is_favorite = in_array($product->get_id(), $wishlist_ids, true);
    $image_id = $product->get_image_id();
    $image_url = $image_id
        ? wp_get_attachment_image_url($image_id, 'woocommerce_thumbnail')
        : mykitchen_get_asset_url('product.png');
    $image_url = $image_url ?: mykitchen_get_asset_url('product.png');

    $regular_price = (float) $product->get_regular_price();
    $sale_price = (float) $product->get_sale_price();
    $current_price = (float) $product->get_price();

    $is_on_sale = $product->is_on_sale() && $regular_price > 0 && $sale_price > 0;
    $badge_percent = 0;
    if ($is_on_sale) {
        $badge_percent = (int) round((($regular_price - $sale_price) / $regular_price) * 100);
    }

    $price_value = $current_price > 0 ? $current_price : $regular_price;
    $price_value = $price_value > 0 ? $price_value : 0;

    $add_to_cart_url = $product->add_to_cart_url();
    $product_link = get_permalink($product->get_id());

    ob_start();
    ?>
    <li class="product-card">
        <?php if ($show_badge && $is_on_sale && $badge_percent > 0) : ?>
            <div class="offer-badge"><span><?php echo esc_html($badge_percent); ?>%</span></div>
        <?php endif; ?>
        <a href="<?php echo esc_url($product_link); ?>">
            <div class="product-card-img">
                <label class="favorite-toggle">
                    <input
                        type="checkbox"
                        class="favorite-toggle__checkbox"
                        data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                        <?php checked($is_favorite); ?>
                    >
                    <span class="favorite-toggle__icon">
                        <i class="fa-regular fa-heart" aria-hidden="true"></i>
                        <i class="fa-solid fa-heart" aria-hidden="true"></i>
                    </span>
                </label>
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>" />
            </div>
        </a>
        <div class="product-card-info">
            <h3 class="product-card-title"><?php echo esc_html($product->get_name()); ?></h3>
        </div>
        <div class="buy">
            <div class="product-price">
                <?php if ($is_on_sale) : ?>
                    <span class="old-card-price">
                        <?php echo esc_html($regular_price); ?>
                        <img src="<?php echo esc_url(mykitchen_get_asset_url('riyal1.png')); ?>" alt="" />
                    </span>
                <?php endif; ?>
                <span class="new-card-price">
                    <?php echo esc_html($price_value); ?>
                    <img src="<?php echo esc_url(mykitchen_get_asset_url('riyal.png')); ?>" alt="" />
                </span>
            </div>
            <a href="<?php echo esc_url($add_to_cart_url); ?>"
                class="add-to-cart add_to_cart_button ajax_add_to_cart"
                data-quantity="1"
                data-product_id="<?php echo esc_attr($product->get_id()); ?>"
                data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                aria-label="<?php echo esc_attr($product->add_to_cart_description()); ?>">
                <img src="<?php echo esc_url(mykitchen_get_asset_url('addcart.png')); ?>" alt="" />
            </a>
        </div>
    </li>
    <?php
    return (string) ob_get_clean();
}

function mykitchen_render_pagination(WP_Query $query): string
{
    $total = (int) $query->max_num_pages;
    if ($total <= 1) {
        return '';
    }

    $current = max(1, (int) get_query_var('paged', 1));
    $links = paginate_links(
        array(
            'total' => $total,
            'current' => $current,
            'type' => 'array',
            'prev_next' => false,
        )
    );

    $has_prev = $current > 1;
    $has_next = $current < $total;

    ob_start();
    ?>
    <div class="pagination">
        <button class="pagination-next" <?php echo $has_prev ? '' : 'disabled'; ?> onclick="if(this.disabled){return false;}window.location.href='<?php echo esc_url(get_pagenum_link($current - 1)); ?>'">
            <i class="fa fa-chevron-right"></i>
        </button>
        <ul class="pagination-list">
            <?php foreach ((array) $links as $link_html) : ?>
                <?php
                $is_current = strpos($link_html, 'current') !== false;
                $label = wp_strip_all_tags($link_html);
                $href = '';
                if (preg_match('/href=[\'"]([^\'"]+)[\'"]/', $link_html, $matches)) {
                    $href = $matches[1];
                }
                ?>
                <li class="pagination-item<?php echo $is_current ? ' active' : ''; ?>">
                    <?php if ($href) : ?>
                        <a href="<?php echo esc_url($href); ?>"><?php echo esc_html($label); ?></a>
                    <?php else : ?>
                        <?php echo esc_html($label); ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <button class="pagination-prev" <?php echo $has_next ? '' : 'disabled'; ?> onclick="if(this.disabled){return false;}window.location.href='<?php echo esc_url(get_pagenum_link($current + 1)); ?>'">
            <i class="fa fa-chevron-left"></i>
        </button>
    </div>
    <?php
    return (string) ob_get_clean();
}

function mykitchen_import_asset_image(string $path): int
{
    if (!file_exists($path)) {
        return 0;
    }

    $existing = get_posts(
        array(
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'meta_key' => '_myk_source',
            'meta_value' => $path,
            'numberposts' => 1,
        )
    );

    if ($existing) {
        return (int) $existing[0]->ID;
    }

    $file_contents = file_get_contents($path);
    if ($file_contents === false) {
        return 0;
    }

    $upload = wp_upload_bits(basename($path), null, $file_contents);
    if (!empty($upload['error'])) {
        return 0;
    }

    $filetype = wp_check_filetype($upload['file'], null);
    $attachment = array(
        'post_mime_type' => $filetype['type'],
        'post_title' => sanitize_file_name(basename($upload['file'])),
        'post_content' => '',
        'post_status' => 'inherit',
    );

    $attach_id = wp_insert_attachment($attachment, $upload['file']);
    if (is_wp_error($attach_id)) {
        return 0;
    }

    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
    wp_update_attachment_metadata($attach_id, $attach_data);
    update_post_meta($attach_id, '_myk_source', $path);

    return (int) $attach_id;
}

