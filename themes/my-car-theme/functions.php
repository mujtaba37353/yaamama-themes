<?php
/**
 * My Car Theme Functions
 *
 * @package MyCarTheme
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function my_car_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Add support for WooCommerce
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('القائمة الرئيسية', 'my-car-theme'),
        'footer'  => __('قائمة التذييل', 'my-car-theme'),
    ));

    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'my_car_theme_setup');

/**
 * Remove default WooCommerce hooks for single product page
 */
function my_car_remove_default_single_product_hooks() {
    // Remove default product image and gallery
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20);
    remove_action('woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10);
    
    // Remove default product summary
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_title', 5);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_price', 10);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
    remove_action('woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50);
    
    // Remove tabs and related products
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
    remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
}
// Remove hooks early - before WooCommerce hooks are added
add_action('woocommerce_init', 'my_car_remove_default_single_product_hooks', 1);
add_action('wp', 'my_car_remove_default_single_product_hooks', 1);
add_action('template_redirect', 'my_car_remove_default_single_product_hooks', 1);

/**
 * Enqueue Styles and Scripts
 */
function my_car_theme_scripts() {
    // Theme version
    $theme_version = wp_get_theme()->get('Version');

    // Enqueue CSS files
    wp_enqueue_style('my-car-tokens', get_template_directory_uri() . '/my-car/base/tokens.css', array(), $theme_version);
    wp_enqueue_style('my-car-reset', get_template_directory_uri() . '/my-car/base/reset.css', array('my-car-tokens'), $theme_version);
    wp_enqueue_style('my-car-typography', get_template_directory_uri() . '/my-car/base/typography.css', array('my-car-reset'), $theme_version);
    wp_enqueue_style('my-car-utilities', get_template_directory_uri() . '/my-car/base/utilities.css', array('my-car-typography'), $theme_version);
    
    // Component CSS
    wp_enqueue_style('my-car-header', get_template_directory_uri() . '/my-car/components/y-header.css', array('my-car-utilities'), $theme_version);
    wp_enqueue_style('my-car-footer', get_template_directory_uri() . '/my-car/components/y-footer.css', array('my-car-utilities'), $theme_version);
    wp_enqueue_style('my-car-buttons', get_template_directory_uri() . '/my-car/components/y-buttons.css', array('my-car-utilities'), $theme_version);
    wp_enqueue_style('my-car-cards', get_template_directory_uri() . '/my-car/components/y-cards.css', array('my-car-utilities'), $theme_version);
    wp_enqueue_style('my-car-forms', get_template_directory_uri() . '/my-car/components/y-forms.css', array('my-car-utilities'), $theme_version);
    wp_enqueue_style('my-car-popups', get_template_directory_uri() . '/my-car/components/y-popups.css', array('my-car-utilities'), $theme_version);
    
    // Home page specific CSS
    if (is_front_page()) {
        wp_enqueue_style('my-car-home', get_template_directory_uri() . '/my-car/templates/home/y-home.css', array('my-car-cards'), $theme_version);
        
        // Add custom CSS for hero image with correct path
        $hero_css = "
        #y-l-page-hero {
            background-image: url('" . esc_url(get_template_directory_uri()) . "/my-car/assets/home-hero.png');
        }
        ";
        wp_add_inline_style('my-car-home', $hero_css);
    }
    
    // Shop and Product archive pages CSS
    if (is_shop() || is_product_category() || is_product_tag()) {
        wp_enqueue_style('my-car-store', get_template_directory_uri() . '/my-car/templates/store/y-store.css', array('my-car-cards'), $theme_version);
        wp_enqueue_style('my-car-range-slider', get_template_directory_uri() . '/my-car/components/y-range-slider.css', array('my-car-forms'), $theme_version);
        
        // Add custom CSS for hero image with correct path
        $store_hero_css = "
        #y-l-page-hero {
            background-image: url('" . esc_url(get_template_directory_uri()) . "/my-car/assets/store-hero.png');
        }
        ";
        wp_add_inline_style('my-car-store', $store_hero_css);
    }
    
    // Offers page CSS
    if (is_page_template('page-offers.php') || (is_page() && get_page_template_slug() == 'page-offers.php') || (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/offers/') !== false)) {
        wp_enqueue_style('my-car-store', get_template_directory_uri() . '/my-car/templates/store/y-store.css', array('my-car-cards'), $theme_version);
        wp_enqueue_style('my-car-offers', get_template_directory_uri() . '/my-car/templates/offers/y-offers.css', array('my-car-store'), $theme_version);
        
        // Add custom CSS for hero image with correct path
        $store_hero_css = "
        #y-l-page-hero {
            background-image: url('" . esc_url(get_template_directory_uri()) . "/my-car/assets/store-hero.png');
        }
        ";
        wp_add_inline_style('my-car-store', $store_hero_css);
        
        // Add inline CSS to ensure no offers box is centered
        $no_offers_css = "
        .y-l-no-offers {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            min-height: calc(100vh - 300px) !important;
            width: 100% !important;
            padding: var(--y-spacing-4xl) var(--y-spacing-lg) !important;
            margin: var(--y-spacing-2xl) 0 !important;
        }
        .y-c-no-offers-box {
            background-color: var(--y-color-background-light) !important;
            border: var(--y-border-width-xs) solid var(--y-color-border) !important;
            border-radius: var(--y-border-radius-lg) !important;
            padding: var(--y-spacing-3xl) var(--y-spacing-2xl) !important;
            text-align: center !important;
            display: flex !important;
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            gap: var(--y-spacing-xl) !important;
            box-shadow: var(--y-shadow-md) !important;
            max-width: 500px !important;
            width: 100% !important;
            margin: 0 auto !important;
        }
        ";
        wp_add_inline_style('my-car-offers', $no_offers_css);
    }
    
    // Single Product page CSS
    if (is_product()) {
        wp_enqueue_style('my-car-single-product', get_template_directory_uri() . '/my-car/templates/single-product/y-single-product.css', array('my-car-cards'), $theme_version);
    }
    
    // Checkout page CSS
    if (is_checkout()) {
        wp_enqueue_style('my-car-checkout', get_template_directory_uri() . '/my-car/templates/checkout/y-checkout.css', array('my-car-forms'), $theme_version);
        wp_enqueue_script('my-car-checkout-js', get_template_directory_uri() . '/my-car/js/checkout.js', array('jquery'), $theme_version, true);
        
        // Thank you page CSS
        if (is_wc_endpoint_url('order-received')) {
            wp_enqueue_style('my-car-thankyou', get_template_directory_uri() . '/my-car/templates/checkout/y-thankyou.css', array('my-car-checkout'), $theme_version);
            wp_enqueue_script('my-car-thankyou-js', get_template_directory_uri() . '/my-car/js/thankyou.js', array('jquery'), $theme_version, true);
        }
    }
    
    // FAQ page CSS
    if (is_page('faq') || is_page_template('page-faq.php') || is_page_template('صفحة الأسئلة الشائعة')) {
        wp_enqueue_style('my-car-faq', get_template_directory_uri() . '/my-car/templates/faq/y-faq.css', array('my-car-utilities'), $theme_version);
        wp_enqueue_script('my-car-faq-js', get_template_directory_uri() . '/my-car/js/faq.js', array('jquery'), $theme_version, true);
    }
    
    // Static pages CSS (Contact Us, About Us, Privacy Policy, Cancellation Policy)
    if (is_page('contact-us') || is_page('about-us') || is_page('privacy-policy') || is_page('cancellation-policy') ||
        is_page_template('page-contact-us.php') || is_page_template('page-about-us.php') || 
        is_page_template('page-privacy-policy.php') || is_page_template('page-cancellation-policy.php')) {
        wp_enqueue_style('my-car-pages', get_template_directory_uri() . '/my-car/templates/pages/y-pages.css', array('my-car-utilities'), $theme_version);
    }
    
    // My Account page CSS
    if (is_account_page()) {
        wp_enqueue_style('my-car-myaccount', get_template_directory_uri() . '/my-car/templates/myaccount/y-myaccount.css', array('my-car-forms'), $theme_version);
    }

    // FontAwesome
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/my-car/assets/fontawesome/css/all.min.css', array(), '6.0.0');
    
    // Flatpickr CSS and JS (for date/time pickers)
    wp_enqueue_style('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', array(), '4.6.13');
    wp_enqueue_script('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.js', array(), '4.6.13', true);
    wp_enqueue_style('my-car-date-picker', get_template_directory_uri() . '/my-car/components/y-date-picker.css', array('flatpickr'), $theme_version);

    // Main theme style
    wp_enqueue_style('my-car-theme-style', get_stylesheet_uri(), array('my-car-popups'), $theme_version);

    // Enqueue JavaScript files
    wp_enqueue_script('jquery');
    wp_enqueue_script('my-car-theme', get_template_directory_uri() . '/my-car/js/theme.js', array('jquery', 'flatpickr'), $theme_version, true);
    
    // Shop filters JavaScript
    if (is_shop() || is_product_category() || is_product_tag()) {
        wp_enqueue_script('my-car-range-slider', get_template_directory_uri() . '/my-car/js/range-slider.js', array('jquery'), $theme_version, true);
        wp_enqueue_script('my-car-shop-filters', get_template_directory_uri() . '/my-car/js/shop-filters.js', array('jquery', 'my-car-range-slider'), $theme_version, true);
    }
    
    // Localize script for AJAX
    wp_localize_script('my-car-theme', 'myCarTheme', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('my-car-theme-nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'my_car_theme_scripts');

/**
 * Register Widget Areas
 */
function my_car_theme_widgets_init() {
    register_sidebar(array(
        'name'          => __('الشريط الجانبي', 'my-car-theme'),
        'id'            => 'sidebar-1',
        'description'   => __('أضف widgets هنا', 'my-car-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ));
}
add_action('widgets_init', 'my_car_theme_widgets_init');

/**
 * Custom Logo Setup
 */
function my_car_theme_custom_logo_setup() {
    $defaults = array(
        'height'      => 70,
        'width'       => 200,
        'flex-height' => true,
        'flex-width'  => true,
    );
    add_theme_support('custom-logo', $defaults);
}
add_action('after_setup_theme', 'my_car_theme_custom_logo_setup');

/**
 * Change WooCommerce Default Template Path
 */
function my_car_theme_woocommerce_template_path() {
    return 'woocommerce/';
}
add_filter('woocommerce_template_path', 'my_car_theme_woocommerce_template_path');

/**
 * Add RTL Support
 */
function my_car_theme_rtl_support() {
    load_theme_textdomain('my-car-theme', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'my_car_theme_rtl_support');

/**
 * Force front-page.php to be used for the front page
 */
function my_car_theme_template_include($template) {
    if (is_front_page() && !is_home()) {
        $front_page_template = locate_template('front-page.php');
        if ($front_page_template) {
            return $front_page_template;
        }
    }
    return $template;
}
add_filter('template_include', 'my_car_theme_template_include', 99);

/**
 * Remove WooCommerce Default Styles (optional - uncomment if needed)
 */
// add_filter('woocommerce_enqueue_styles', '__return_empty_array');

/**
 * Load demo importer
 */
require_once get_template_directory() . '/inc/demo-importer.php';

/**
 * Set number of products per page for shop
 */
function my_car_products_per_page() {
    return -1; // -1 means show all products
}
add_filter('loop_shop_per_page', 'my_car_products_per_page', 20);

/**
 * Apply sorting and price filtering to shop products based on URL parameters
 */
function my_car_shop_product_sorting($query) {
    if (!is_admin() && $query->is_main_query()) {
        // Only apply on shop page and product archives
        if (is_shop() || is_product_category() || is_product_tag()) {
            $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
            $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : '';
            $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : '';
            $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : '';
            
            // Handle price filtering
            if ($min_price !== '' || $max_price !== '') {
                $meta_query = $query->get('meta_query');
                if (!is_array($meta_query)) {
                    $meta_query = array();
                }
                
                $price_query = array(
                    'key' => '_price',
                    'value' => array(),
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                );
                
                if ($min_price !== '') {
                    $price_query['value'][0] = $min_price;
                } else {
                    $price_query['value'][0] = 0;
                }
                
                if ($max_price !== '') {
                    $price_query['value'][1] = $max_price;
                } else {
                    $price_query['value'][1] = 999999;
                }
                
                // If both min and max are the same, use >= instead of BETWEEN
                if ($price_query['value'][0] == $price_query['value'][1]) {
                    $price_query['compare'] = '>=';
                    $price_query['value'] = $price_query['value'][0];
                }
                
                $meta_query[] = $price_query;
                $query->set('meta_query', $meta_query);
            }
            
            if ($orderby) {
                // Handle price sorting
                if ($orderby === 'price') {
                    // Always set meta_key and orderby for price sorting
                    $query->set('meta_key', '_price');
                    $query->set('orderby', 'meta_value_num');
                    $query->set('order', $order ? strtoupper($order) : 'DESC');
                } 
                // Handle title/name sorting
                elseif ($orderby === 'title') {
                    $query->set('orderby', 'title');
                    $query->set('order', $order ? strtoupper($order) : 'ASC');
                }
                // Default sorting
                else {
                    $query->set('orderby', $orderby);
                    $query->set('order', $order ? strtoupper($order) : 'DESC');
                }
            } else {
                // Default: sort by price descending
                $query->set('meta_key', '_price');
                $query->set('orderby', 'meta_value_num');
                $query->set('order', 'DESC');
            }
        }
    }
}
add_action('pre_get_posts', 'my_car_shop_product_sorting', 30);

/**
 * Fix ordering when using meta_query for price filtering
 * This ensures ORDER BY works correctly with meta_query
 */
function my_car_posts_clauses($clauses, $query) {
    if (!is_admin() && $query->is_main_query()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            $orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
            $order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : '';
            $min_price = isset($_GET['min_price']) ? floatval($_GET['min_price']) : '';
            $max_price = isset($_GET['max_price']) ? floatval($_GET['max_price']) : '';
            
            // Only apply if we have both price filter and price ordering
            if (($min_price !== '' || $max_price !== '') && $orderby === 'price') {
                global $wpdb;
                
                // Remove existing orderby clause if any
                if (preg_match('/ORDER BY\s+.*/i', $clauses['orderby'], $matches)) {
                    $clauses['orderby'] = '';
                }
                
                // Add proper ORDER BY clause for meta_value_num with meta_query
                $order_direction = $order ? strtoupper($order) : 'DESC';
                $clauses['join'] .= " INNER JOIN {$wpdb->postmeta} AS price_meta ON {$wpdb->posts}.ID = price_meta.post_id AND price_meta.meta_key = '_price'";
                $clauses['orderby'] = "CAST(price_meta.meta_value AS DECIMAL(10,2)) {$order_direction}";
            }
        }
    }
    return $clauses;
}
add_filter('posts_clauses', 'my_car_posts_clauses', 20, 2);

/**
 * Customize WooCommerce catalog ordering arguments
 */
function my_car_woocommerce_get_catalog_ordering_args($args, $orderby, $order) {
    // Get custom orderby from URL
    $custom_orderby = isset($_GET['orderby']) ? sanitize_text_field($_GET['orderby']) : '';
    $custom_order = isset($_GET['order']) ? sanitize_text_field($_GET['order']) : '';
    
    if ($custom_orderby) {
        if ($custom_orderby === 'price') {
            $args['orderby'] = 'meta_value_num';
            $args['order'] = $custom_order ? strtoupper($custom_order) : 'DESC';
            $args['meta_key'] = '_price';
        } elseif ($custom_orderby === 'title') {
            $args['orderby'] = 'title';
            $args['order'] = $custom_order ? strtoupper($custom_order) : 'ASC';
        }
    } elseif (!$orderby) {
        // Default: sort by price descending
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
        $args['meta_key'] = '_price';
    }
    
    return $args;
}
add_filter('woocommerce_get_catalog_ordering_args', 'my_car_woocommerce_get_catalog_ordering_args', 20, 3);

/**
 * Filter menu items to force "أسطولنا" to go to shop page and "العروض" to offers page
 */
function my_car_filter_menu_items($items, $args) {
    if (isset($args->theme_location) && $args->theme_location === 'primary') {
        if (function_exists('wc_get_page_permalink')) {
            $shop_url = wc_get_page_permalink('shop');
            foreach ($items as $item) {
                if (strpos(strtolower($item->title), 'أسطول') !== false || strpos(strtolower($item->title), 'اسطول') !== false) {
                    $item->url = $shop_url;
                } elseif (strpos(strtolower($item->title), 'عروض') !== false) {
                    // Force "العروض" to go to offers page
                    $offers_page = get_page_by_path('offers');
                    if (!$offers_page) {
                        $offers_page = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-offers.php'));
                        $offers_page = !empty($offers_page) ? $offers_page[0] : null;
                    }
                    if ($offers_page) {
                        $item->url = get_permalink($offers_page->ID);
                    }
                } elseif (strpos(strtolower($item->title), 'سؤال') !== false || strpos(strtolower($item->title), 'faq') !== false || strpos(strtolower($item->title), 'اسئلة') !== false || strpos(strtolower($item->title), 'أسئلة') !== false) {
                    // Force "الأسئلة الشائعة" to go to FAQ page
                    $faq_page = get_page_by_path('faq');
                    if (!$faq_page) {
                        $faq_page = get_pages(array('meta_key' => '_wp_page_template', 'meta_value' => 'page-faq.php'));
                        $faq_page = !empty($faq_page) ? $faq_page[0] : null;
                    }
                    if ($faq_page) {
                        $item->url = get_permalink($faq_page->ID);
                    }
                }
            }
        }
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'my_car_filter_menu_items', 10, 2);

/**
 * Customize WooCommerce Checkout Fields
 */
function my_car_customize_checkout_fields($fields) {
    // Remove unwanted billing fields
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']);
    
    // Remove first name and last name, add full name
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    
    // Add full name field
    $fields['billing']['billing_full_name'] = array(
        'label'       => 'الاسم الكامل',
        'placeholder' => 'أدخل اسمك الكامل',
        'required'    => true,
        'class'       => array('form-row-wide'),
        'priority'    => 10,
    );
    
    // Customize email field
    $fields['billing']['billing_email']['label'] = 'البريد الإلكتروني';
    $fields['billing']['billing_email']['placeholder'] = 'example@email.com';
    $fields['billing']['billing_email']['priority'] = 20;
    $fields['billing']['billing_email']['class'] = array('form-row-wide');
    
    // Customize phone field
    $fields['billing']['billing_phone']['label'] = 'رقم الجوال';
    $fields['billing']['billing_phone']['placeholder'] = '05xxxxxxxx';
    $fields['billing']['billing_phone']['required'] = true;
    $fields['billing']['billing_phone']['priority'] = 30;
    $fields['billing']['billing_phone']['class'] = array('form-row-wide');
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'my_car_customize_checkout_fields');

/**
 * Customize Order Notes field
 */
function my_car_customize_order_notes($fields) {
    $fields['order']['order_comments']['label'] = 'ملاحظات الطلب';
    $fields['order']['order_comments']['placeholder'] = 'أضف أي ملاحظات خاصة بطلبك هنا...';
    $fields['order']['order_comments']['class'] = array('form-row-wide', 'notes');
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'my_car_customize_order_notes', 20);

/**
 * Save custom billing full name field
 */
function my_car_save_billing_full_name($order_id) {
    if (!empty($_POST['billing_full_name'])) {
        $full_name = sanitize_text_field($_POST['billing_full_name']);
        update_post_meta($order_id, '_billing_full_name', $full_name);
        
        // Split name for WooCommerce compatibility
        $name_parts = explode(' ', $full_name, 2);
        update_post_meta($order_id, '_billing_first_name', $name_parts[0]);
        update_post_meta($order_id, '_billing_last_name', isset($name_parts[1]) ? $name_parts[1] : '');
    }
}
add_action('woocommerce_checkout_update_order_meta', 'my_car_save_billing_full_name');

/**
 * Validate billing full name field
 */
function my_car_validate_billing_full_name() {
    if (empty($_POST['billing_full_name'])) {
        wc_add_notice('الرجاء إدخال الاسم الكامل.', 'error');
    }
}
add_action('woocommerce_checkout_process', 'my_car_validate_billing_full_name');

/**
 * Change Place Order button text
 */
function my_car_order_button_text($button_text) {
    return 'تأكيد الحجز';
}
add_filter('woocommerce_order_button_text', 'my_car_order_button_text');

/**
 * Customize privacy policy text
 */
function my_car_privacy_policy_text($text) {
    return 'سيتم استخدام بياناتك الشخصية لمعالجة طلبك ودعم تجربتك عبر هذا الموقع، ولأغراض أخرى موضحة في <a href="' . get_privacy_policy_url() . '" target="_blank">سياسة الخصوصية</a>.';
}
add_filter('woocommerce_get_privacy_policy_text', 'my_car_privacy_policy_text');

/**
 * Translate payment gateway titles to Arabic
 */
function my_car_translate_payment_gateway_title($title, $gateway_id) {
    $translations = array(
        'bacs' => 'تحويل بنكي',
        'cod' => 'الدفع عند الاستلام',
        'paypal' => 'باي بال',
        'stripe' => 'بطاقة ائتمان',
        'cheque' => 'شيك',
    );
    
    if (isset($translations[$gateway_id])) {
        return $translations[$gateway_id];
    }
    
    return $title;
}
add_filter('woocommerce_gateway_title', 'my_car_translate_payment_gateway_title', 10, 2);

/**
 * Translate WooCommerce strings to Arabic
 */
function my_car_translate_woocommerce_strings($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        $translations = array(
            'Returning customer?' => 'عميل سابق؟',
            'Click here to login' => 'اضغط هنا لتسجيل الدخول',
            'Have a coupon?' => 'لديك كوبون خصم؟',
            'Click here to enter your code' => 'اضغط هنا لإدخال الكود',
            'Enter your coupon code' => 'أدخل كود الخصم',
            'View cart' => 'عرض السلة',
            'has been added to your cart.' => 'تمت إضافته إلى سلة التسوق.',
            'Subtotal' => 'المجموع الفرعي',
            'Total' => 'الإجمالي',
            'Place order' => 'تأكيد الحجز',
            'Direct bank transfer' => 'تحويل بنكي',
            'Cash on delivery' => 'الدفع عند الاستلام',
            'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.' => 'قم بالدفع مباشرة إلى حسابنا البنكي. يرجى استخدام رقم طلبك كمرجع للدفع.',
            'Pay with cash upon delivery.' => 'ادفع نقداً عند الاستلام.',
            'Billing details' => 'بيانات الفوترة',
            'Additional information' => 'معلومات إضافية',
            'Order notes' => 'ملاحظات الطلب',
            'Notes about your order, e.g. special notes for delivery.' => 'أضف أي ملاحظات خاصة بطلبك هنا...',
            'Your order' => 'طلبك',
            'Product' => 'المنتج',
            'Quantity' => 'الكمية',
            'Qty' => 'الكمية',
        );
        
        if (isset($translations[$text])) {
            return $translations[$text];
        }
    }
    
    return $translated_text;
}
add_filter('gettext', 'my_car_translate_woocommerce_strings', 20, 3);

/**
 * Customize My Account menu items - Remove Dashboard and Downloads, translate to Arabic
 */
function my_car_customize_account_menu_items($items) {
    // Remove Dashboard and Downloads
    unset($items['dashboard']);
    unset($items['downloads']);
    
    // Translate remaining items to Arabic
    $translations = array(
        'Orders' => 'طلباتي',
        'Addresses' => 'العناوين',
        'Account details' => 'بيانات الحساب',
        'Logout' => 'تسجيل الخروج',
    );
    
    foreach ($items as $endpoint => $label) {
        if (isset($translations[$label])) {
            $items[$endpoint] = $translations[$label];
        }
    }
    
    return $items;
}
add_filter('woocommerce_account_menu_items', 'my_car_customize_account_menu_items');

/**
 * Redirect My Account page to Orders by default for logged-in users
 */
function my_car_redirect_account_dashboard() {
    if (is_account_page() && is_user_logged_in() && !is_wc_endpoint_url()) {
        wp_safe_redirect(wc_get_account_endpoint_url('orders'));
        exit;
    }
}
add_action('template_redirect', 'my_car_redirect_account_dashboard');

/**
 * Add custom register endpoint to WooCommerce
 */
function my_car_add_register_endpoint() {
    add_rewrite_endpoint('register', EP_ROOT | EP_PAGES);
}
add_action('init', 'my_car_add_register_endpoint');

/**
 * Add register to WooCommerce query vars
 */
function my_car_register_query_vars($vars) {
    $vars['register'] = 'register';
    return $vars;
}
add_filter('woocommerce_get_query_vars', 'my_car_register_query_vars');

/**
 * Handle register endpoint content
 */
function my_car_register_endpoint_content() {
    if (!is_user_logged_in()) {
        // Set action to register and load the login form which handles both
        $_GET['action'] = 'register';
        wc_get_template('myaccount/form-login.php');
    } else {
        wp_redirect(wc_get_account_endpoint_url('orders'));
        exit;
    }
}
add_action('woocommerce_account_register_endpoint', 'my_car_register_endpoint_content');

/**
 * Flush rewrite rules on theme activation
 */
function my_car_flush_rewrite_rules() {
    my_car_add_register_endpoint();
    flush_rewrite_rules();
}
add_action('after_switch_theme', 'my_car_flush_rewrite_rules');

/**
 * Validate password confirmation on registration
 */
function my_car_validate_registration_password($validation_error, $username, $password = '', $email = '') {
    // Get password from POST if not provided
    if (empty($password) && isset($_POST['password'])) {
        $password = $_POST['password'];
    }
    
    if (isset($_POST['password_confirm']) && isset($_POST['password']) && $_POST['password'] !== $_POST['password_confirm']) {
        $validation_error->add('password_mismatch', __('كلمات المرور غير متطابقة.', 'woocommerce'));
    }
    
    // Check password strength only if password is provided
    if (!empty($password) && strlen($password) < 8) {
        $validation_error->add('password_too_short', __('كلمة المرور يجب أن تكون 8 أحرف على الأقل.', 'woocommerce'));
    }
    
    return $validation_error;
}
add_filter('woocommerce_registration_errors', 'my_car_validate_registration_password', 10, 4);

/**
 * Force password field to show in registration (disable auto-generate)
 */
add_filter('woocommerce_registration_generate_password', '__return_false');

/**
 * Disable WooCommerce password strength meter on registration
 */
function my_car_disable_password_strength_meter() {
    if (is_account_page()) {
        wp_dequeue_script('wc-password-strength-meter');
        wp_dequeue_script('password-strength-meter');
    }
}
add_action('wp_enqueue_scripts', 'my_car_disable_password_strength_meter', 100);

/**
 * Remove WooCommerce password strength meter from registration form
 */
function my_car_remove_password_strength() {
    return '';
}
add_filter('woocommerce_min_password_strength', '__return_zero');

/**
 * Translate order statuses to Arabic
 */
function my_car_translate_order_statuses($statuses) {
    $translations = array(
        'wc-pending' => 'قيد الانتظار',
        'wc-processing' => 'قيد المعالجة',
        'wc-on-hold' => 'معلق',
        'wc-completed' => 'مكتمل',
        'wc-cancelled' => 'ملغي',
        'wc-refunded' => 'مسترد',
        'wc-failed' => 'فشل',
    );
    
    foreach ($translations as $key => $value) {
        if (isset($statuses[$key])) {
            $statuses[$key] = $value;
        }
    }
    
    return $statuses;
}
add_filter('wc_order_statuses', 'my_car_translate_order_statuses');

/**
 * Redirect to checkout after adding product to cart
 * This ensures users go directly to checkout instead of cart
 */
function my_car_redirect_to_checkout_after_add_to_cart($url) {
    return wc_get_checkout_url();
}
add_filter('woocommerce_add_to_cart_redirect', 'my_car_redirect_to_checkout_after_add_to_cart');

/**
 * Skip cart page - always redirect to checkout
 */
function my_car_skip_cart_redirect_to_checkout() {
    if (is_cart() && !WC()->cart->is_empty()) {
        wp_safe_redirect(wc_get_checkout_url());
        exit;
    }
}
add_action('template_redirect', 'my_car_skip_cart_redirect_to_checkout');

/**
 * Include Theme Pages Manager
 */
require_once get_template_directory() . '/inc/theme-pages-manager.php';

/**
 * Include Theme Contact Settings
 */
require_once get_template_directory() . '/inc/theme-contact-settings.php';
