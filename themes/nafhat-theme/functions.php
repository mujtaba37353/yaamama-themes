<?php
/**
 * Nafhat Theme Functions
 *
 * @package Nafhat
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Include homepage settings
require_once get_template_directory() . '/inc/homepage-settings.php';

// Include contact settings
require_once get_template_directory() . '/inc/contact-settings.php';

// Include theme pages settings
require_once get_template_directory() . '/inc/theme-pages-settings.php';

/**
 * Theme Setup
 */
function nafhat_theme_setup() {
    // Add theme support for title tag
    add_theme_support('title-tag');
    
    // Add theme support for post thumbnails
    add_theme_support('post-thumbnails');
    
    // Add theme support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 60,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    
    // Add theme support for HTML5 markup
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    
    // Add theme support for automatic feed links
    add_theme_support('automatic-feed-links');
    
    // Add theme support for responsive embeds
    add_theme_support('responsive-embeds');
    
    // Add theme support for wide and full alignments
    add_theme_support('align-wide');
    
    // Add theme support for editor styles
    add_theme_support('editor-styles');
    add_editor_style('assets/css/editor-style.css');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('القائمة الرئيسية', 'nafhat'),
        'footer'  => __('قائمة التذييل', 'nafhat'),
    ));
    
    // Set content width
    $GLOBALS['content_width'] = 1200;
    
    // Load theme textdomain
    load_theme_textdomain('nafhat', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'nafhat_theme_setup');

/**
 * Enqueue Scripts and Styles
 */
function nafhat_enqueue_scripts() {
    // Main stylesheet
    wp_enqueue_style('nafhat-style', get_stylesheet_uri(), array(), '1.0.0');
    
    // Google Fonts (Cairo)
    wp_enqueue_style(
        'nafhat-google-fonts',
        'https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap',
        array(),
        null
    );
    
    // Font Awesome
    wp_enqueue_style(
        'nafhat-font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );
    
    // Main JavaScript
    wp_enqueue_script(
        'nafhat-app-init',
        get_template_directory_uri() . '/assets/js/app-init.js',
        array(),
        '1.0.0',
        true
    );
    
    // Hero Slider Script
    wp_enqueue_script(
        'nafhat-hero-slider',
        get_template_directory_uri() . '/assets/js/y-hero-slider.js',
        array(),
        '1.0.0',
        true
    );
    
    // Opinions Slider Script (for customer reviews)
    wp_enqueue_script(
        'nafhat-opinions-slider',
        get_template_directory_uri() . '/assets/js/opinions-slider.js',
        array(),
        '1.0.0',
        true
    );
    
    // Add to Cart Script (for product cards)
    if (class_exists('WooCommerce')) {
        wp_enqueue_script(
            'nafhat-add-to-cart',
            get_template_directory_uri() . '/assets/js/add-to-cart.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
    
    // Localize script for AJAX
    wp_localize_script('nafhat-app-init', 'nafhatAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('nafhat-nonce'),
    ));
}
add_action('wp_enqueue_scripts', 'nafhat_enqueue_scripts');

/**
 * Register Widget Areas
 */
function nafhat_widgets_init() {
    register_sidebar(array(
        'name'          => __('التذييل - العمود الأول', 'nafhat'),
        'id'            => 'footer-1',
        'description'   => __('إضافة widgets هنا', 'nafhat'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('التذييل - العمود الثاني', 'nafhat'),
        'id'            => 'footer-2',
        'description'   => __('إضافة widgets هنا', 'nafhat'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('التذييل - العمود الثالث', 'nafhat'),
        'id'            => 'footer-3',
        'description'   => __('إضافة widgets هنا', 'nafhat'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'nafhat_widgets_init');

/**
 * Custom Logo URL
 */
function nafhat_custom_logo_url() {
    $custom_logo_id = get_theme_mod('custom_logo');
    if ($custom_logo_id) {
        return wp_get_attachment_image_url($custom_logo_id, 'full');
    }
    return get_template_directory_uri() . '/assets/images/logo.png';
}

/**
 * Get Theme Image URL
 */
function nafhat_get_theme_image($filename) {
    return get_template_directory_uri() . '/assets/images/' . $filename;
}

/**
 * Custom Excerpt Length
 */
function nafhat_excerpt_length($length) {
    return 30;
}
add_filter('excerpt_length', 'nafhat_excerpt_length');

/**
 * Custom Excerpt More
 */
function nafhat_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'nafhat_excerpt_more');

/**
 * Add RTL Support
 */
function nafhat_rtl_support() {
    wp_enqueue_style('nafhat-rtl', get_template_directory_uri() . '/assets/css/rtl.css', array('nafhat-style'), '1.0.0');
}
add_action('wp_enqueue_scripts', 'nafhat_rtl_support');

/**
 * WooCommerce Support
 */
function nafhat_woocommerce_support() {
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
}
add_action('after_setup_theme', 'nafhat_woocommerce_support');

/**
 * Remove WooCommerce Default Content Wrappers
 * Our custom templates handle their own wrappers
 */
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

/**
 * Remove WooCommerce Default Breadcrumb
 */
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);

/**
 * Dequeue WooCommerce default rating select script on single product
 */
function nafhat_dequeue_woocommerce_rating_scripts() {
    if (is_product()) {
        wp_dequeue_script('wc-single-product');
        wp_deregister_script('wc-single-product');
    }
}
add_action('wp_enqueue_scripts', 'nafhat_dequeue_woocommerce_rating_scripts', 99);

/**
 * Prevent WooCommerce from overriding front page template
 */
function nafhat_prevent_woocommerce_front_page_override($template) {
    // If this is the front page and front-page.php exists, use it
    if (is_front_page() && file_exists(get_template_directory() . '/front-page.php')) {
        return get_template_directory() . '/front-page.php';
    }
    return $template;
}
add_filter('template_include', 'nafhat_prevent_woocommerce_front_page_override', 99);

/**
 * Remove WooCommerce shop query on front page
 */
function nafhat_remove_woocommerce_front_page_query($query) {
    if (!is_admin() && $query->is_main_query() && is_front_page()) {
        $query->set('post_type', 'post');
        $query->set('is_archive', false);
        $query->set('is_post_type_archive', false);
    }
}
add_action('pre_get_posts', 'nafhat_remove_woocommerce_front_page_query', 99);

/**
 * Handle Contact Form Submission
 */
function nafhat_handle_contact_form() {
    // Verify nonce
    if (!isset($_POST['nafhat_contact_nonce']) || !wp_verify_nonce($_POST['nafhat_contact_nonce'], 'nafhat_contact_form')) {
        wp_redirect(add_query_arg('contact_error', '1', wp_get_referer()));
        exit;
    }
    
    // Sanitize input
    $name = isset($_POST['contact_name']) ? sanitize_text_field($_POST['contact_name']) : '';
    $email = isset($_POST['contact_email']) ? sanitize_email($_POST['contact_email']) : '';
    $subject = isset($_POST['contact_subject']) ? sanitize_text_field($_POST['contact_subject']) : '';
    $message = isset($_POST['contact_message']) ? sanitize_textarea_field($_POST['contact_message']) : '';
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($message)) {
        wp_redirect(add_query_arg('contact_error', '1', wp_get_referer()));
        exit;
    }
    
    // Validate email
    if (!is_email($email)) {
        wp_redirect(add_query_arg('contact_error', '1', wp_get_referer()));
        exit;
    }
    
    // Get contact settings
    $contact_settings = nafhat_get_contact_settings();
    
    // Get recipient email from settings or fallback to admin email
    $to = !empty($contact_settings['contact_email']) ? $contact_settings['contact_email'] : get_option('admin_email');
    $site_name = get_bloginfo('name');
    
    // Email subject
    $email_subject = $subject ? $subject : sprintf(__('رسالة جديدة من %s', 'nafhat'), $site_name);
    
    // Email body
    $email_body = sprintf(
        __("اسم المرسل: %s\n\nالبريد الإلكتروني: %s\n\nالموضوع: %s\n\nنص الرسالة:\n%s\n\n---\nهذه الرسالة تم إرسالها من نموذج التواصل في %s", 'nafhat'),
        $name,
        $email,
        $subject ? $subject : __('لا يوجد', 'nafhat'),
        $message,
        home_url()
    );
    
    // Email headers
    $headers = array(
        'Content-Type: text/plain; charset=UTF-8',
        'From: ' . $site_name . ' <' . $to . '>',
        'Reply-To: ' . $name . ' <' . $email . '>',
    );
    
    // Send email
    $sent = wp_mail($to, $email_subject, $email_body, $headers);
    
    if ($sent) {
        // Log to database (optional)
        do_action('nafhat_contact_form_submitted', array(
            'name'    => $name,
            'email'   => $email,
            'subject' => $subject,
            'message' => $message,
            'date'    => current_time('mysql'),
        ));
        
        wp_redirect(add_query_arg('contact_sent', '1', wp_get_referer()));
    } else {
        wp_redirect(add_query_arg('contact_error', '1', wp_get_referer()));
    }
    
    exit;
}
add_action('admin_post_nafhat_contact_form', 'nafhat_handle_contact_form');
add_action('admin_post_nopriv_nafhat_contact_form', 'nafhat_handle_contact_form');

/**
 * Add Contact Page CSS
 */
function nafhat_enqueue_contact_styles() {
    if (is_page_template('page-contact.php') || is_page_template('page-contact-us.php') || is_page('contact') || is_page('contact-us') || is_page('تواصل-معنا')) {
        wp_enqueue_style(
            'nafhat-contact-style',
            get_template_directory_uri() . '/assets/css/components/contact.css',
            array('nafhat-style'),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'nafhat_enqueue_contact_styles');

/**
 * Add Front Page CSS (Brands, Products, etc.)
 */
function nafhat_enqueue_front_page_styles() {
    if (is_front_page()) {
        wp_enqueue_style(
            'nafhat-brands-style',
            get_template_directory_uri() . '/assets/css/components/brands.css',
            array('nafhat-style'),
            '1.0.0'
        );
        wp_enqueue_style(
            'nafhat-products-style',
            get_template_directory_uri() . '/assets/css/components/products.css',
            array('nafhat-style'),
            '1.0.0'
        );
        wp_enqueue_style(
            'nafhat-reviews-style',
            get_template_directory_uri() . '/assets/css/components/reviews.css',
            array('nafhat-style'),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'nafhat_enqueue_front_page_styles');

/**
 * Add WooCommerce Shop & Category Page CSS
 */
function nafhat_enqueue_woocommerce_styles() {
    if (class_exists('WooCommerce')) {
        if (is_shop() || is_product_category() || is_product_tag() || is_product()) {
            wp_enqueue_style(
                'nafhat-products-style',
                get_template_directory_uri() . '/assets/css/components/products.css',
                array('nafhat-style'),
                '1.0.0'
            );
            wp_enqueue_style(
                'nafhat-shop-style',
                get_template_directory_uri() . '/assets/css/components/shop.css',
                array('nafhat-style'),
                '1.0.0'
            );
        }
        
        // Single Product Page
        if (is_product()) {
            wp_enqueue_style(
                'nafhat-single-product-style',
                get_template_directory_uri() . '/assets/css/components/single-product.css',
                array('nafhat-style'),
                '1.0.0'
            );
            wp_enqueue_script(
                'nafhat-single-product-js',
                get_template_directory_uri() . '/assets/js/single-product.js',
                array(),
                '1.0.0',
                true
            );
        }
        
        // Cart Page - load on cart page or any page with cart shortcode
        if (is_cart() || is_page('cart') || is_page('سلة-التسوق')) {
            wp_enqueue_style(
                'nafhat-cart-style',
                get_template_directory_uri() . '/assets/css/components/cart.css',
                array('nafhat-style'),
                '1.0.0'
            );
            wp_enqueue_script(
                'nafhat-cart-js',
                get_template_directory_uri() . '/assets/js/cart.js',
                array('jquery'),
                '1.0.0',
                true
            );
        }
    }
}

/**
 * Always load cart CSS when WooCommerce cart shortcode is used
 */
function nafhat_load_cart_css_for_shortcode() {
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'woocommerce_cart')) {
        wp_enqueue_style(
            'nafhat-cart-style',
            get_template_directory_uri() . '/assets/css/components/cart.css',
            array('nafhat-style'),
            '1.0.0'
        );
        wp_enqueue_script(
            'nafhat-cart-js',
            get_template_directory_uri() . '/assets/js/cart.js',
            array('jquery'),
            '1.0.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'nafhat_load_cart_css_for_shortcode');
add_action('wp_enqueue_scripts', 'nafhat_enqueue_woocommerce_styles');

/**
 * Register Contact Page Theme Customizer Settings
 */
function nafhat_contact_customize_register($wp_customize) {
    // Contact Section
    $wp_customize->add_section('nafhat_contact_section', array(
        'title'    => __('معلومات التواصل', 'nafhat'),
        'priority' => 30,
    ));
    
    // Contact Address
    $wp_customize->add_setting('nafhat_contact_address', array(
        'default'           => 'الرياض، المملكة العربية السعودية',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nafhat_contact_address', array(
        'label'   => __('العنوان', 'nafhat'),
        'section' => 'nafhat_contact_section',
        'type'    => 'text',
    ));
    
    // Contact Email
    $wp_customize->add_setting('nafhat_contact_email', array(
        'default'           => get_option('admin_email'),
        'sanitize_callback' => 'sanitize_email',
    ));
    
    $wp_customize->add_control('nafhat_contact_email', array(
        'label'   => __('البريد الإلكتروني', 'nafhat'),
        'section' => 'nafhat_contact_section',
        'type'    => 'email',
    ));
    
    // Contact Phone
    $wp_customize->add_setting('nafhat_contact_phone', array(
        'default'           => '+966 50 000 0000',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('nafhat_contact_phone', array(
        'label'   => __('رقم الهاتف', 'nafhat'),
        'section' => 'nafhat_contact_section',
        'type'    => 'text',
    ));
    
    // Social Media Links
    $wp_customize->add_setting('nafhat_contact_instagram', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('nafhat_contact_instagram', array(
        'label'   => __('رابط Instagram', 'nafhat'),
        'section' => 'nafhat_contact_section',
        'type'    => 'url',
    ));
    
    $wp_customize->add_setting('nafhat_contact_facebook', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('nafhat_contact_facebook', array(
        'label'   => __('رابط Facebook', 'nafhat'),
        'section' => 'nafhat_contact_section',
        'type'    => 'url',
    ));
    
    $wp_customize->add_setting('nafhat_contact_snapchat', array(
        'default'           => '#',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('nafhat_contact_snapchat', array(
        'label'   => __('رابط Snapchat', 'nafhat'),
        'section' => 'nafhat_contact_section',
        'type'    => 'url',
    ));
    
    // Map Embed Code
    $wp_customize->add_setting('nafhat_contact_map', array(
        'default'           => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3624.006470365758!2d46.675296!3d24.713551!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f042f4f9b7a23%3A0x9af0b5a24b!2sRiyadh!5e0!3m2!1sar!2ssa!4v1688570000000',
        'sanitize_callback' => 'esc_url_raw',
    ));
    
    $wp_customize->add_control('nafhat_contact_map', array(
        'label'       => __('رابط خريطة Google Maps (Embed)', 'nafhat'),
        'section'     => 'nafhat_contact_section',
        'type'        => 'url',
        'description' => __('قم بنسخ رابط Embed من Google Maps', 'nafhat'),
    ));
}
add_action('customize_register', 'nafhat_contact_customize_register');

/**
 * ============================================
 * DEMO PRODUCTS SYSTEM
 * ============================================
 */

// Include Demo Products System
require_once get_template_directory() . '/inc/demo-products.php';

/**
 * ============================================
 * WISHLIST SYSTEM
 * ============================================
 */

// Include Wishlist System
require_once get_template_directory() . '/inc/wishlist.php';

/**
 * Add Wishlist Page CSS
 */
function nafhat_enqueue_wishlist_styles() {
    if (is_page_template('page-wishlist.php') || is_page('wishlist') || is_page('المفضلة')) {
        wp_enqueue_style(
            'nafhat-wishlist-style',
            get_template_directory_uri() . '/assets/css/components/wishlist.css',
            array('nafhat-style'),
            '1.0.0'
        );
    }
}
add_action('wp_enqueue_scripts', 'nafhat_enqueue_wishlist_styles');

/**
 * ============================================
 * AJAX ADD TO CART
 * ============================================
 */

/**
 * AJAX Add to Cart Handler
 */
function nafhat_ajax_add_to_cart() {
    $product_id = apply_filters('woocommerce_add_to_cart_product_id', absint($_POST['product_id']));
    $quantity = empty($_POST['quantity']) ? 1 : wc_stock_amount(absint($_POST['quantity']));
    $variation_id = absint($_POST['variation_id']);
    $passed_validation = apply_filters('woocommerce_add_to_cart_validation', true, $product_id, $quantity);
    $product_status = get_post_status($product_id);
    
    if ($passed_validation && WC()->cart->add_to_cart($product_id, $quantity, $variation_id) && 'publish' === $product_status) {
        do_action('woocommerce_ajax_added_to_cart', $product_id);
        
        if ('yes' === get_option('woocommerce_cart_redirect_after_add')) {
            wc_add_to_cart_message(array($product_id => $quantity), true);
        }
        
        // Get cart count for response
        $cart_count = WC()->cart->get_cart_contents_count();
        
        // Get refreshed fragments
        ob_start();
        WC_AJAX::get_refreshed_fragments();
        $fragments_response = ob_get_clean();
        
        // Decode and add cart count
        $response = json_decode($fragments_response, true);
        if (!$response) {
            $response = array();
        }
        $response['cart_count'] = $cart_count;
        
        wp_send_json($response);
    } else {
        $data = array(
            'error' => true,
            'product_url' => apply_filters('woocommerce_cart_redirect_after_error', get_permalink($product_id), $product_id),
        );
        
        wp_send_json($data);
    }
    
    wp_die();
}
add_action('wp_ajax_woocommerce_ajax_add_to_cart', 'nafhat_ajax_add_to_cart');
add_action('wp_ajax_nopriv_woocommerce_ajax_add_to_cart', 'nafhat_ajax_add_to_cart');

/**
 * AJAX Get Cart Count Handler
 */
function nafhat_get_cart_count() {
    $count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    wp_send_json_success(array('count' => $count));
}
add_action('wp_ajax_nafhat_get_cart_count', 'nafhat_get_cart_count');
add_action('wp_ajax_nopriv_nafhat_get_cart_count', 'nafhat_get_cart_count');

/**
 * ============================================
 * MY ACCOUNT CUSTOMIZATIONS
 * ============================================
 */

/**
 * Remove dashboard and downloads from My Account menu
 */
function nafhat_remove_my_account_links($items) {
    unset($items['dashboard']);
    unset($items['downloads']);
    return $items;
}
add_filter('woocommerce_account_menu_items', 'nafhat_remove_my_account_links');

/**
 * Redirect dashboard to edit-account page (profile)
 */
function nafhat_redirect_dashboard_to_profile() {
    if (is_account_page() && !is_wc_endpoint_url() && is_user_logged_in()) {
        wp_safe_redirect(wc_get_account_endpoint_url('edit-account'));
        exit;
    }
}
add_action('template_redirect', 'nafhat_redirect_dashboard_to_profile');

/**
 * Change My Account menu item order
 */
function nafhat_reorder_my_account_menu($items) {
    $new_items = array();
    
    // Define the order we want
    $order = array('edit-account', 'orders', 'edit-address', 'customer-logout');
    
    foreach ($order as $key) {
        if (isset($items[$key])) {
            $new_items[$key] = $items[$key];
        }
    }
    
    return $new_items;
}
add_filter('woocommerce_account_menu_items', 'nafhat_reorder_my_account_menu', 20);

/**
 * Remove WooCommerce default privacy policy text from registration form
 */
function nafhat_remove_wc_privacy_policy_text() {
    remove_action('woocommerce_register_form', 'wc_registration_privacy_policy_text', 20);
}
add_action('init', 'nafhat_remove_wc_privacy_policy_text');

/**
 * Translate WooCommerce password reset messages to Arabic
 */
function nafhat_translate_wc_messages($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        $translations = array(
            'Password reset email has been sent.' => 'تم إرسال بريد إلكتروني لإعادة تعيين كلمة المرور.',
            'A password reset email has been sent to the email address on file for your account, but may take several minutes to show up in your inbox. Please wait at least 10 minutes before attempting another reset.' => 'تم إرسال بريد إلكتروني لإعادة تعيين كلمة المرور إلى عنوان البريد الإلكتروني المسجل في حسابك. قد يستغرق وصوله بضع دقائق. يرجى الانتظار 10 دقائق على الأقل قبل محاولة إعادة التعيين مرة أخرى.',
            'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.' => 'نسيت كلمة المرور؟ أدخل اسم المستخدم أو البريد الإلكتروني وسنرسل لك رابطاً لإنشاء كلمة مرور جديدة.',
            'Username or email' => 'اسم المستخدم أو البريد الإلكتروني',
            'Reset password' => 'إعادة تعيين كلمة المرور',
            'Your password has been reset successfully.' => 'تم إعادة تعيين كلمة المرور بنجاح.',
            'New password' => 'كلمة المرور الجديدة',
            'Re-enter new password' => 'أعد إدخال كلمة المرور الجديدة',
            'Save' => 'حفظ',
            'Invalid username or email.' => 'اسم المستخدم أو البريد الإلكتروني غير صحيح.',
            'There is no account with that username or email address.' => 'لا يوجد حساب بهذا الاسم أو البريد الإلكتروني.',
            'This password reset key is invalid or has already been used. Please reset your password again if needed.' => 'مفتاح إعادة تعيين كلمة المرور غير صالح أو تم استخدامه بالفعل. يرجى إعادة تعيين كلمة المرور مرة أخرى إذا لزم الأمر.',
        );
        
        if (isset($translations[$text])) {
            return $translations[$text];
        }
    }
    return $translated_text;
}
add_filter('gettext', 'nafhat_translate_wc_messages', 20, 3);

/**
 * ============================================
 * CHECKOUT CUSTOMIZATIONS
 * ============================================
 */

/**
 * Override WooCommerce checkout fields
 */
function nafhat_override_checkout_fields($fields) {
    // Remove all default billing fields
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_postcode']);
    
    // Make country a simple text field (not dropdown)
    unset($fields['billing']['billing_country']);
    
    // We'll handle our custom fields in the template
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'nafhat_override_checkout_fields');

/**
 * Disable WooCommerce country validation completely
 */
function nafhat_disable_country_validation($locale) {
    if (isset($locale['country'])) {
        $locale['country']['required'] = false;
        $locale['country']['hidden'] = true;
    }
    return $locale;
}
add_filter('woocommerce_get_country_locale', 'nafhat_disable_country_validation');

/**
 * Bypass WooCommerce country code validation
 */
function nafhat_bypass_country_code_check($countries) {
    // Add custom country text as valid
    if (isset($_POST['billing_country']) && !empty($_POST['billing_country'])) {
        $custom_country = sanitize_text_field($_POST['billing_country']);
        $countries[$custom_country] = $custom_country;
    }
    return $countries;
}
add_filter('woocommerce_countries', 'nafhat_bypass_country_code_check', 999);

/**
 * Add custom checkout field - Full Name
 */
function nafhat_add_checkout_fields($checkout) {
    // Full Name field is handled in template
}
add_action('woocommerce_checkout_billing', 'nafhat_add_checkout_fields');

/**
 * Process custom checkout fields
 */
function nafhat_checkout_field_process() {
    // Validate full name
    if (empty($_POST['billing_full_name'])) {
        wc_add_notice(__('الرجاء إدخال الاسم الكامل.', 'nafhat'), 'error');
    }
    
    // Validate country
    if (empty($_POST['billing_country'])) {
        wc_add_notice(__('الرجاء إدخال الدولة.', 'nafhat'), 'error');
    }
    
    // Validate city
    if (empty($_POST['billing_city'])) {
        wc_add_notice(__('الرجاء إدخال المدينة.', 'nafhat'), 'error');
    }
    
    // Validate address
    if (empty($_POST['billing_address_1'])) {
        wc_add_notice(__('الرجاء إدخال وصف العنوان.', 'nafhat'), 'error');
    }
    
    // Validate email
    if (empty($_POST['billing_email'])) {
        wc_add_notice(__('الرجاء إدخال البريد الإلكتروني.', 'nafhat'), 'error');
    }
    
    // Validate phone
    if (empty($_POST['billing_phone'])) {
        wc_add_notice(__('الرجاء إدخال رقم الجوال.', 'nafhat'), 'error');
    }
}
add_action('woocommerce_checkout_process', 'nafhat_checkout_field_process');

/**
 * Save custom checkout fields to order
 */
function nafhat_checkout_field_update_order_meta($order_id) {
    if (!empty($_POST['billing_full_name'])) {
        update_post_meta($order_id, '_billing_full_name', sanitize_text_field($_POST['billing_full_name']));
        
        // Also save as first name for compatibility
        $order = wc_get_order($order_id);
        $order->set_billing_first_name(sanitize_text_field($_POST['billing_full_name']));
        $order->save();
    }
}
add_action('woocommerce_checkout_update_order_meta', 'nafhat_checkout_field_update_order_meta');

/**
 * Display custom field in admin order
 */
function nafhat_display_admin_order_meta($order) {
    $full_name = get_post_meta($order->get_id(), '_billing_full_name', true);
    if ($full_name) {
        echo '<p><strong>' . __('الاسم الكامل:', 'nafhat') . '</strong> ' . esc_html($full_name) . '</p>';
    }
}
add_action('woocommerce_admin_order_data_after_billing_address', 'nafhat_display_admin_order_meta');

/**
 * Skip country validation - allow any text value
 */
function nafhat_skip_country_validation($fields) {
    // Remove country validation by making it a text field
    if (isset($fields['billing']['billing_country'])) {
        $fields['billing']['billing_country']['type'] = 'text';
        $fields['billing']['billing_country']['validate'] = array();
    }
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'nafhat_skip_country_validation', 20);

/**
 * Override country validation
 */
function nafhat_override_country_validation($data, $errors) {
    // Remove any country-related errors
    $error_codes = $errors->get_error_codes();
    foreach ($error_codes as $code) {
        if (strpos($code, 'country') !== false || strpos($code, 'billing_country') !== false) {
            $errors->remove($code);
        }
    }
    
    // Also check error messages for country validation
    $all_errors = $errors->get_error_messages();
    foreach ($all_errors as $error) {
        if (strpos($error, 'country code') !== false || strpos($error, 'valid country') !== false) {
            // Clear all errors and re-add non-country ones
            $errors->remove('validation');
        }
    }
}
add_action('woocommerce_after_checkout_validation', 'nafhat_override_country_validation', 10, 2);

/**
 * Save custom country text to order
 */
function nafhat_save_custom_country($order_id) {
    if (!empty($_POST['billing_country'])) {
        $country_text = sanitize_text_field($_POST['billing_country']);
        update_post_meta($order_id, '_billing_country_text', $country_text);
        
        // Set billing country to SA for WooCommerce compatibility, but store actual text
        $order = wc_get_order($order_id);
        if ($order) {
            // Store the text in address_2 or a custom field
            update_post_meta($order_id, '_billing_country', 'SA');
            update_post_meta($order_id, '_custom_billing_country', $country_text);
        }
    }
}
add_action('woocommerce_checkout_update_order_meta', 'nafhat_save_custom_country', 5);

/**
 * Translate checkout messages
 */
function nafhat_translate_checkout_messages($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        $checkout_translations = array(
            'Billing details' => 'معلومات الفاتورة',
            'Ship to a different address?' => 'الشحن إلى عنوان مختلف؟',
            'Order notes' => 'ملاحظات الطلب',
            'Notes about your order, e.g. special notes for delivery.' => 'ملاحظات حول طلبك، مثل ملاحظات خاصة بالتوصيل.',
            'Your order' => 'طلبك',
            'Product' => 'المنتج',
            'Subtotal' => 'المجموع الفرعي',
            'Total' => 'الإجمالي',
            'Place order' => 'إتمام الطلب',
            'Coupon code' => 'كود الخصم',
            'Apply coupon' => 'تطبيق',
            'Have a coupon?' => 'هل لديك كود خصم؟',
            'Click here to enter your code' => 'اضغط هنا لإدخال الكود',
            'Coupon has been applied successfully.' => 'تم تطبيق كود الخصم بنجاح.',
            'Sorry, this coupon is not valid.' => 'عذراً، كود الخصم غير صالح.',
            'Thank you. Your order has been received.' => 'شكراً لك. تم استلام طلبك بنجاح.',
            'Order received' => 'تم استلام الطلب',
            'Order number:' => 'رقم الطلب:',
            'Date:' => 'التاريخ:',
            'Email:' => 'البريد الإلكتروني:',
            'Payment method:' => 'طريقة الدفع:',
            'Cash on delivery' => 'الدفع عند الاستلام',
            'Pay with cash upon delivery.' => 'ادفع نقداً عند استلام الطلب.',
            'Direct bank transfer' => 'تحويل بنكي مباشر',
            'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.' => 'قم بالدفع مباشرة إلى حسابنا البنكي. يرجى استخدام رقم الطلب كمرجع للدفع. لن يتم شحن طلبك حتى يتم تأكيد استلام المبلغ في حسابنا.',
            'Returning customer?' => 'عميل سابق؟',
            'Click here to login' => 'اضغط هنا لتسجيل الدخول',
            'Enter your coupon code' => 'أدخل كود الخصم',
            'Shipping' => 'الشحن',
            'Free shipping' => 'شحن مجاني',
            'Flat rate' => 'سعر ثابت',
            // Order statuses
            'Pending payment' => 'في انتظار الدفع',
            'Processing' => 'قيد المعالجة',
            'On hold' => 'معلق',
            'Completed' => 'مكتمل',
            'Cancelled' => 'ملغي',
            'Refunded' => 'مسترد',
            'Failed' => 'فشل',
            // Thank you page
            'Order details' => 'تفاصيل الطلب',
            'Our bank details' => 'تفاصيل حسابنا البنكي',
            'Bank' => 'البنك',
            'Account name' => 'اسم الحساب',
            'Account number' => 'رقم الحساب',
            'Sort code' => 'رمز الفرز',
            'IBAN' => 'رقم الآيبان',
            'BIC' => 'رمز البنك',
            'Billing address' => 'عنوان الفاتورة',
            'Subtotal:' => 'المجموع الفرعي:',
            'Total:' => 'الإجمالي:',
            // Shop page
            'Add to cart' => 'أضف للسلة',
            'View cart' => 'عرض السلة',
            'Showing all %d results' => 'عرض جميع النتائج (%d)',
            'Showing the single result' => 'عرض نتيجة واحدة',
            'Showing %1$d–%2$d of %3$d results' => 'عرض %1$d–%2$d من %3$d نتيجة',
            'Default sorting' => 'الترتيب الافتراضي',
            'Sort by popularity' => 'الأكثر شعبية',
            'Sort by average rating' => 'الأعلى تقييماً',
            'Sort by latest' => 'الأحدث',
            'Sort by price: low to high' => 'السعر: من الأقل للأعلى',
            'Sort by price: high to low' => 'السعر: من الأعلى للأقل',
            // Single product
            'Category:' => 'التصنيف:',
            'Categories:' => 'التصنيفات:',
            'Tag:' => 'الوسم:',
            'Tags:' => 'الوسوم:',
            'SKU:' => 'رمز المنتج:',
            'Description' => 'الوصف',
            'Additional information' => 'معلومات إضافية',
            'Reviews' => 'التقييمات',
            'reviews' => 'تقييمات',
            'customer review' => 'تقييم العميل',
            'customer reviews' => 'تقييمات العملاء',
            'Add a review' => 'أضف تقييمك',
            'Your review' => 'تقييمك',
            'Your review *' => 'تقييمك *',
            'Name *' => 'الاسم *',
            'Email *' => 'البريد الإلكتروني *',
            'Submit' => 'إرسال',
            'Related products' => 'منتجات ذات صلة',
            'You may also like…' => 'قد يعجبك أيضاً...',
            'out of 5' => 'من 5',
            'Rated' => 'التقييم',
            'based on' => 'بناءً على',
            '%s customer rating' => '%s تقييم',
            '%s customer ratings' => '%s تقييمات',
            'Select options' => 'اختر الخيارات',
            'Read more' => 'اقرأ المزيد',
            'In stock' => 'متوفر',
            'Out of stock' => 'غير متوفر',
            'On backorder' => 'طلب مسبق',
            'Sale!' => 'تخفيض!',
        );
        
        if (isset($checkout_translations[$text])) {
            return $checkout_translations[$text];
        }
    }
    return $translated_text;
}
add_filter('gettext', 'nafhat_translate_checkout_messages', 20, 3);

/**
 * Hide WooCommerce default checkout login and coupon forms
 */
function nafhat_hide_checkout_login_form() {
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10);
    remove_action('woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10);
}
add_action('init', 'nafhat_hide_checkout_login_form');

/**
 * Translate payment gateway titles
 */
function nafhat_translate_gateway_title($title, $gateway_id) {
    $translations = array(
        'bacs' => 'تحويل بنكي مباشر',
        'cod' => 'الدفع عند الاستلام',
        'cheque' => 'شيك',
        'paypal' => 'باي بال',
    );
    
    if (isset($translations[$gateway_id])) {
        return $translations[$gateway_id];
    }
    
    return $title;
}
add_filter('woocommerce_gateway_title', 'nafhat_translate_gateway_title', 10, 2);

/**
 * Translate payment gateway descriptions
 */
function nafhat_translate_gateway_description($description, $gateway_id) {
    $translations = array(
        'bacs' => 'قم بالدفع مباشرة إلى حسابنا البنكي. يرجى استخدام رقم الطلب كمرجع للدفع. لن يتم شحن طلبك حتى يتم تأكيد استلام المبلغ في حسابنا.',
        'cod' => 'ادفع نقداً عند استلام الطلب.',
    );
    
    if (isset($translations[$gateway_id])) {
        return $translations[$gateway_id];
    }
    
    return $description;
}
add_filter('woocommerce_gateway_description', 'nafhat_translate_gateway_description', 10, 2);

/**
 * Translate order status names
 */
function nafhat_translate_order_status($status_name) {
    $translations = array(
        'Pending payment' => 'في انتظار الدفع',
        'Processing' => 'قيد المعالجة',
        'On hold' => 'معلق',
        'Completed' => 'مكتمل',
        'Cancelled' => 'ملغي',
        'Refunded' => 'مسترد',
        'Failed' => 'فشل',
        'pending' => 'في انتظار الدفع',
        'processing' => 'قيد المعالجة',
        'on-hold' => 'معلق',
        'completed' => 'مكتمل',
        'cancelled' => 'ملغي',
        'refunded' => 'مسترد',
        'failed' => 'فشل',
    );
    
    if (isset($translations[$status_name])) {
        return $translations[$status_name];
    }
    
    return $status_name;
}
add_filter('wc_order_statuses', function($statuses) {
    $translations = array(
        'wc-pending' => 'في انتظار الدفع',
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
});

/**
 * Translate shop result count
 */
function nafhat_translate_result_count($count_html) {
    // Replace English text with Arabic
    $count_html = preg_replace('/Showing all (\d+) results/', 'عرض جميع النتائج ($1)', $count_html);
    $count_html = preg_replace('/Showing the single result/', 'عرض نتيجة واحدة', $count_html);
    $count_html = preg_replace('/Showing (\d+)–(\d+) of (\d+) results/', 'عرض $1–$2 من $3 نتيجة', $count_html);
    return $count_html;
}
add_filter('woocommerce_result_count_html', 'nafhat_translate_result_count');

/**
 * Translate rating text
 */
function nafhat_translate_rating_html($html) {
    $html = str_replace('Rated', 'التقييم', $html);
    $html = str_replace('out of 5', 'من 5', $html);
    $html = str_replace('based on', 'بناءً على', $html);
    $html = str_replace('customer rating', 'تقييم', $html);
    $html = str_replace('customer ratings', 'تقييمات', $html);
    return $html;
}
add_filter('woocommerce_product_get_rating_html', 'nafhat_translate_rating_html');
add_filter('woocommerce_get_star_rating_html', 'nafhat_translate_rating_html');
