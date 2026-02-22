<?php
/**
 * Techno Souq Theme Functions
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Include Demo Content Management
require get_template_directory() . '/inc/demo-content.php';

// Include Demo Products Management
require get_template_directory() . '/inc/demo-products.php';

// Include Contact Settings Management
require get_template_directory() . '/inc/contact-settings.php';
require get_template_directory() . '/inc/demo-pages.php';

/**
 * Theme Setup
 */
function techno_souq_theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    
    // Add WooCommerce support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'techno-souq-theme'),
        'footer' => __('Footer Menu', 'techno-souq-theme'),
    ));
    
    // Set content width
    $GLOBALS['content_width'] = 1200;
}
add_action('after_setup_theme', 'techno_souq_theme_setup');

/**
 * Add WooCommerce page shortcuts to admin bar
 */
function techno_souq_add_admin_bar_shortcuts($wp_admin_bar) {
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    // Add parent menu
    $wp_admin_bar->add_node(array(
        'id'    => 'wc-pages-shortcuts',
        'title' => __('WooCommerce Pages', 'techno-souq-theme'),
        'href'  => '#',
    ));
    
    // Cart page shortcut
    $cart_url = wc_get_cart_url();
    if ($cart_url) {
        $wp_admin_bar->add_node(array(
            'id'     => 'wc-cart-page',
            'parent' => 'wc-pages-shortcuts',
            'title'  => __('Cart', 'techno-souq-theme'),
            'href'   => $cart_url,
            'meta'   => array('target' => '_blank'),
        ));
    }
    
    // Checkout page shortcut
    $checkout_url = wc_get_checkout_url();
    if ($checkout_url) {
        $wp_admin_bar->add_node(array(
            'id'     => 'wc-checkout-page',
            'parent' => 'wc-pages-shortcuts',
            'title'  => __('Checkout', 'techno-souq-theme'),
            'href'   => $checkout_url,
            'meta'   => array('target' => '_blank'),
        ));
    }
    
    // My Account page shortcut
    $account_url = wc_get_page_permalink('myaccount');
    if ($account_url) {
        $wp_admin_bar->add_node(array(
            'id'     => 'wc-account-page',
            'parent' => 'wc-pages-shortcuts',
            'title'  => __('My Account', 'techno-souq-theme'),
            'href'   => $account_url,
            'meta'   => array('target' => '_blank'),
        ));
    }
}
add_action('admin_bar_menu', 'techno_souq_add_admin_bar_shortcuts', 100);

/**
 * Register Product Brand Taxonomy
 * Similar to product categories but for brands
 */
function techno_souq_register_product_brand_taxonomy() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    
    $labels = array(
        'name' => __('العلامات التجارية', 'techno-souq-theme'),
        'singular_name' => __('العلامة التجارية', 'techno-souq-theme'),
        'menu_name' => __('العلامات التجارية', 'techno-souq-theme'),
        'all_items' => __('جميع العلامات التجارية', 'techno-souq-theme'),
        'parent_item' => __('العلامة التجارية الرئيسية', 'techno-souq-theme'),
        'parent_item_colon' => __('العلامة التجارية الرئيسية:', 'techno-souq-theme'),
        'new_item_name' => __('اسم العلامة التجارية الجديدة', 'techno-souq-theme'),
        'add_new_item' => __('إضافة علامة تجارية جديدة', 'techno-souq-theme'),
        'edit_item' => __('تحرير العلامة التجارية', 'techno-souq-theme'),
        'update_item' => __('تحديث العلامة التجارية', 'techno-souq-theme'),
        'view_item' => __('عرض العلامة التجارية', 'techno-souq-theme'),
        'separate_items_with_commas' => __('افصل العلامات التجارية بفواصل', 'techno-souq-theme'),
        'add_or_remove_items' => __('إضافة أو إزالة العلامات التجارية', 'techno-souq-theme'),
        'choose_from_most_used' => __('اختر من الأكثر استخداماً', 'techno-souq-theme'),
        'popular_items' => __('العلامات التجارية الشائعة', 'techno-souq-theme'),
        'search_items' => __('البحث عن العلامات التجارية', 'techno-souq-theme'),
        'not_found' => __('لم يتم العثور على علامات تجارية', 'techno-souq-theme'),
        'no_terms' => __('لا توجد علامات تجارية', 'techno-souq-theme'),
        'items_list' => __('قائمة العلامات التجارية', 'techno-souq-theme'),
        'items_list_navigation' => __('تنقل قائمة العلامات التجارية', 'techno-souq-theme'),
    );
    
    $args = array(
        'labels' => $labels,
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_admin_column' => true,
        'show_in_nav_menus' => true,
        'show_tagcloud' => false,
        'rewrite' => array(
            'slug' => 'brand',
            'with_front' => false,
            'hierarchical' => false,
        ),
        'query_var' => true,
    );
    
    register_taxonomy('product_brand', array('product'), $args);
}
add_action('init', 'techno_souq_register_product_brand_taxonomy', 0);

/**
 * Enqueue Styles and Scripts
 */
function techno_souq_enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');
    $techno_souq_path = get_template_directory_uri() . '/techno-souq';
    
    // Base Styles
    wp_enqueue_style('techno-souq-reset', $techno_souq_path . '/base/reset.css', array(), $theme_version);
    wp_enqueue_style('techno-souq-tokens', $techno_souq_path . '/base/tokens.css', array('techno-souq-reset'), $theme_version);
    wp_enqueue_style('techno-souq-typography', $techno_souq_path . '/base/typography.css', array('techno-souq-tokens'), $theme_version);
    wp_enqueue_style('techno-souq-utilities', $techno_souq_path . '/base/utilities.css', array('techno-souq-typography'), $theme_version);
    
    // FontAwesome
    wp_enqueue_style('fontawesome', $techno_souq_path . '/assets/fontawesome/css/all.min.css', array(), '6.0.0');
    
    // Component Styles
    wp_enqueue_style('techno-souq-header', $techno_souq_path . '/components/y-header.css', array('techno-souq-utilities'), $theme_version);
    wp_enqueue_style('techno-souq-footer', $techno_souq_path . '/components/y-footer.css', array('techno-souq-utilities'), $theme_version);
    wp_enqueue_style('techno-souq-buttons', $techno_souq_path . '/components/y-buttons.css', array('techno-souq-utilities'), $theme_version);
    wp_enqueue_style('techno-souq-cards', $techno_souq_path . '/components/y-cards.css', array('techno-souq-utilities'), $theme_version);
    wp_enqueue_style('techno-souq-forms', $techno_souq_path . '/components/y-forms.css', array('techno-souq-utilities'), $theme_version);
    wp_enqueue_style('techno-souq-popup', $techno_souq_path . '/components/y-popup.css', array('techno-souq-utilities'), $theme_version);
    
    // Main Theme Style
    wp_enqueue_style('techno-souq-theme-style', get_stylesheet_uri(), array(
        'techno-souq-header',
        'techno-souq-footer',
        'techno-souq-buttons',
        'techno-souq-cards',
        'techno-souq-forms',
        'techno-souq-popup'
    ), $theme_version);
    
    // Scripts
    wp_enqueue_script('techno-souq-shared-components', $techno_souq_path . '/js/shared-components.js', array(), $theme_version, true);
    
    // Localize script for AJAX
    wp_localize_script('techno-souq-shared-components', 'technoSouqAjax', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('techno_souq_nonce'),
        'isLoggedIn' => is_user_logged_in() ? true : false,
    ));
}
add_action('wp_enqueue_scripts', 'techno_souq_enqueue_assets');

/**
 * Enqueue Page-Specific Styles and Scripts
 */
function techno_souq_enqueue_page_scripts() {
    $theme_version = wp_get_theme()->get('Version');
    $techno_souq_path = get_template_directory_uri() . '/techno-souq';
    
    if (is_front_page()) {
        // Enqueue home-specific styles
        wp_enqueue_style('techno-souq-home', $techno_souq_path . '/templates/home/y-home.css', array(
            'techno-souq-header',
            'techno-souq-footer',
            'techno-souq-buttons',
            'techno-souq-cards'
        ), $theme_version);
        
        // Enqueue home-specific scripts
        wp_enqueue_script('techno-souq-home', $techno_souq_path . '/js/home.js', array('techno-souq-shared-components'), $theme_version, true);
        wp_enqueue_script('techno-souq-products', $techno_souq_path . '/js/products.js', array('techno-souq-shared-components'), $theme_version, true);
        wp_enqueue_script('techno-souq-product-slider', $techno_souq_path . '/js/product-slider.js', array('techno-souq-shared-components'), $theme_version, true);
    }
}
add_action('wp_enqueue_scripts', 'techno_souq_enqueue_page_scripts', 20);

/**
 * Get Asset URL Helper
 */
function techno_souq_asset_url($path) {
    return get_template_directory_uri() . '/techno-souq/assets/' . ltrim($path, '/');
}

/**
 * Remove WooCommerce Sidebar
 */
function techno_souq_remove_woocommerce_sidebar() {
    if (is_woocommerce() || is_cart() || is_checkout() || is_account_page()) {
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
}
add_action('wp', 'techno_souq_remove_woocommerce_sidebar');

/**
 * Force front-page.php to be used on front page
 * This prevents WooCommerce from hijacking the front page template
 */
function techno_souq_force_front_page_template($template) {
    if (is_front_page() && !is_admin()) {
        $front_page_template = locate_template(array('front-page.php'));
        if ($front_page_template) {
            return $front_page_template;
        }
    }
    return $template;
}
add_filter('template_include', 'techno_souq_force_front_page_template', 99);

/**
 * Prevent WooCommerce from modifying the front page query
 */
function techno_souq_prevent_woocommerce_front_page_query($q) {
    if (!is_admin() && is_front_page() && $q->is_main_query()) {
        // Don't let WooCommerce modify the front page query
        remove_action('pre_get_posts', array(WC()->query, 'pre_get_posts'));
    }
}
add_action('pre_get_posts', 'techno_souq_prevent_woocommerce_front_page_query', 1);

/**
 * Register Widget Areas
 */
function techno_souq_widgets_init() {
    register_sidebar(array(
        'name' => __('Sidebar', 'techno-souq-theme'),
        'id' => 'sidebar-1',
        'description' => __('Add widgets here.', 'techno-souq-theme'),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h2 class="widget-title">',
        'after_title' => '</h2>',
    ));
}
add_action('widgets_init', 'techno_souq_widgets_init');

/**
 * Get bestsellers products
 * Returns products ordered by total_sales
 * If no products with sales, returns empty array
 */
function techno_souq_get_bestsellers($limit = 4) {
    $args = array(
        'limit' => $limit,
        'status' => 'publish',
        'orderby' => 'total_sales',
        'order' => 'DESC',
    );
    $products = wc_get_products($args);
    
    // Filter out products with zero sales
    $bestsellers = array();
    foreach ($products as $product) {
        if ($product && $product->get_total_sales() > 0) {
            $bestsellers[] = $product;
            if (count($bestsellers) >= $limit) {
                break;
            }
        }
    }
    
    return $bestsellers;
}

/**
 * Get newest products
 */
function techno_souq_get_newest_products($limit = 8) {
    $args = array(
        'limit' => $limit,
        'status' => 'publish',
        'orderby' => 'date',
        'order' => 'DESC',
    );
    return wc_get_products($args);
}

/**
 * Filter WooCommerce products query for category filtering
 */
function techno_souq_filter_products_by_category($query) {
    if (!is_admin() && $query->is_main_query() && is_shop()) {
        if (isset($_GET['product_cat']) && !empty($_GET['product_cat'])) {
            $category_slug = sanitize_text_field($_GET['product_cat']);
            $query->set('tax_query', array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'slug',
                    'terms' => $category_slug,
                ),
            ));
        }
    }
}
add_action('pre_get_posts', 'techno_souq_filter_products_by_category');

/**
 * Customize checkout button text.
 */
function techno_souq_woocommerce_product_add_to_cart_text() {
    return __('إتمام الشراء', 'techno-souq-theme');
}
add_filter('woocommerce_product_add_to_cart_text', 'techno_souq_woocommerce_product_add_to_cart_text');

/**
 * Customize "Proceed to checkout" button text.
 */
function techno_souq_woocommerce_product_single_add_to_cart_text() {
    return __('إتمام الشراء', 'techno-souq-theme');
}
add_filter('woocommerce_product_single_add_to_cart_text', 'techno_souq_woocommerce_product_single_add_to_cart_text');

/**
 * Translate "Proceed to checkout" button.
 */
function techno_souq_woocommerce_order_button_text() {
    return __('إتمام الشراء', 'techno-souq-theme');
}
add_filter('woocommerce_order_button_text', 'techno_souq_woocommerce_order_button_text');

/**
 * Translate checkout button in cart.
 */
function techno_souq_woocommerce_checkout_button_text() {
    return __('إتمام الشراء', 'techno-souq-theme');
}
add_filter('gettext', function($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        if ($text === 'Proceed to checkout') {
            return __('إتمام الشراء', 'techno-souq-theme');
        }
        // Translate privacy policy text
        if (strpos($text, 'Your personal data will be used to process your order') !== false) {
            return __('سيتم استخدام بياناتك الشخصية لمعالجة طلبك، ودعم تجربتك على هذا الموقع، ولأغراض أخرى موضحة في %s.', 'techno-souq-theme');
        }
        if (strpos($text, 'Your personal data will be used to support your experience') !== false) {
            return __('سيتم استخدام بياناتك الشخصية لدعم تجربتك على هذا الموقع، وإدارة الوصول إلى حسابك، ولأغراض أخرى موضحة في %s.', 'techno-souq-theme');
        }
    }
    return $translated_text;
}, 20, 3);

/**
 * Add custom classes to checkout button.
 */
function techno_souq_woocommerce_widget_shopping_cart_button_html($button) {
    return str_replace('button checkout wc-forward', 'y-c-btn y-c-btn-primary y-c-btn-full y-c-checkout-btn', $button);
}
add_filter('woocommerce_widget_cart_button_html', 'techno_souq_woocommerce_widget_shopping_cart_button_html');

/**
 * Translate payment gateway titles to Arabic.
 */
function techno_souq_translate_payment_gateway_title($title, $gateway_id) {
    $translations = array(
        'bacs' => 'تحويل بنكي مباشر',
        'cod' => 'الدفع عند الاستلام',
        'cheque' => 'دفع بشيك',
        'paypal' => 'باي بال',
        'stripe' => 'سترايب',
    );
    
    if (isset($translations[$gateway_id])) {
        return $translations[$gateway_id];
    }
    
    // Fallback translations for common titles
    $title_translations = array(
        'Direct bank transfer' => 'تحويل بنكي مباشر',
        'Cash on delivery' => 'الدفع عند الاستلام',
        'Check payments' => 'دفع بشيك',
        'PayPal' => 'باي بال',
        'Stripe' => 'سترايب',
    );
    
    if (isset($title_translations[$title])) {
        return $title_translations[$title];
    }
    
    return $title;
}
add_filter('woocommerce_gateway_title', 'techno_souq_translate_payment_gateway_title', 10, 2);

/**
 * Translate privacy policy text on checkout.
 */
function techno_souq_translate_privacy_policy_text($text, $type = '') {
    // Translate the default text
    if (strpos($text, 'Your personal data will be used to process your order') !== false) {
        // Replace the English text with Arabic, keeping the [privacy_policy] placeholder
        $text = str_replace(
            'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our',
            'سيتم استخدام بياناتك الشخصية لمعالجة طلبك، ودعم تجربتك على هذا الموقع، ولأغراض أخرى موضحة في',
            $text
        );
    }
    if (strpos($text, 'Your personal data will be used to support your experience') !== false) {
        // Replace the English text with Arabic, keeping the [privacy_policy] placeholder
        $text = str_replace(
            'Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our',
            'سيتم استخدام بياناتك الشخصية لدعم تجربتك على هذا الموقع، وإدارة الوصول إلى حسابك، ولأغراض أخرى موضحة في',
            $text
        );
    }
    // Also translate if the link is already replaced
    if (strpos($text, 'privacy policy') !== false) {
        $text = str_replace('privacy policy', 'سياسة الخصوصية', $text);
    }
    return $text;
}
add_filter('woocommerce_get_privacy_policy_text', 'techno_souq_translate_privacy_policy_text', 10, 2);

/**
 * Translate privacy policy text using gettext filter.
 */
add_filter('gettext', function($translated_text, $text, $domain) {
    if ($domain === 'woocommerce') {
        // Translate the default privacy policy text
        if ($text === 'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our %s.') {
            return sprintf(__('سيتم استخدام بياناتك الشخصية لمعالجة طلبك، ودعم تجربتك على هذا الموقع، ولأغراض أخرى موضحة في %s.', 'techno-souq-theme'), '%s');
        }
        if ($text === 'Your personal data will be used to support your experience throughout this website, to manage access to your account, and for other purposes described in our %s.') {
            return sprintf(__('سيتم استخدام بياناتك الشخصية لدعم تجربتك على هذا الموقع، وإدارة الوصول إلى حسابك، ولأغراض أخرى موضحة في %s.', 'techno-souq-theme'), '%s');
        }
        // Translate "privacy policy" link text
        if ($text === 'privacy policy') {
            return __('سياسة الخصوصية', 'techno-souq-theme');
        }
        // Translate coupon messages
        if ($text === 'Have a coupon?') {
            return __('هل لديك كوبون؟', 'techno-souq-theme');
        }
        if ($text === 'Click here to enter your code') {
            return __('انقر هنا لإدخال الرمز', 'techno-souq-theme');
        }
        if ($text === 'Enter your coupon code') {
            return __('أدخل رمز الكوبون الخاص بك', 'techno-souq-theme');
        }
        if ($text === 'Coupon:') {
            return __('كوبون:', 'techno-souq-theme');
        }
        if ($text === 'Coupon code') {
            return __('رمز الكوبون', 'techno-souq-theme');
        }
        if ($text === 'Apply coupon') {
            return __('تطبيق الكوبون', 'techno-souq-theme');
        }
        // Translate My Account menu items
        if ($text === 'Dashboard') {
            return __('لوحة التحكم', 'techno-souq-theme');
        }
        if ($text === 'Orders') {
            return __('الطلبات', 'techno-souq-theme');
        }
        if ($text === 'Downloads') {
            return __('التنزيلات', 'techno-souq-theme');
        }
        if ($text === 'Addresses') {
            return __('العناوين', 'techno-souq-theme');
        }
        if ($text === 'Account details') {
            return __('تفاصيل الحساب', 'techno-souq-theme');
        }
        if ($text === 'Logout') {
            return __('تسجيل خروج', 'techno-souq-theme');
        }
        // Translate order statuses
        if ($text === 'Completed') {
            return __('تم التوصيل', 'techno-souq-theme');
        }
        if ($text === 'Processing') {
            return __('قيد المعالجة', 'techno-souq-theme');
        }
        if ($text === 'On hold') {
            return __('قيد الانتظار', 'techno-souq-theme');
        }
        if ($text === 'Cancelled') {
            return __('ملغي', 'techno-souq-theme');
        }
        // Translate order table columns
        if ($text === 'Order') {
            return __('الطلب', 'techno-souq-theme');
        }
        if ($text === 'Date') {
            return __('التاريخ', 'techno-souq-theme');
        }
        if ($text === 'Status') {
            return __('الحالة', 'techno-souq-theme');
        }
        if ($text === 'Total') {
            return __('الإجمالي', 'techno-souq-theme');
        }
        if ($text === 'Actions') {
            return __('الإجراءات', 'techno-souq-theme');
        }
        // Translate address labels
        if ($text === 'Billing address') {
            return __('عنوان الفاتورة', 'techno-souq-theme');
        }
        if ($text === 'Shipping address') {
            return __('عنوان الشحن', 'techno-souq-theme');
        }
        if ($text === 'Edit') {
            return __('تعديل', 'techno-souq-theme');
        }
        if ($text === 'Add') {
            return __('إضافة', 'techno-souq-theme');
        }
        if ($text === 'Save address') {
            return __('حفظ العنوان', 'techno-souq-theme');
        }
        if ($text === 'The following addresses will be used on the checkout page by default.') {
            return __('العناوين التالية سيتم استخدامها في صفحة الدفع', 'techno-souq-theme');
        }
        if ($text === 'You have not set up this type of address yet.') {
            return __('لم تقم بإعداد هذا النوع من العنوان بعد.', 'techno-souq-theme');
        }
        // Translate empty states
        if ($text === 'No order has been made yet.') {
            return __('لم يتم إجراء أي طلب بعد.', 'techno-souq-theme');
        }
        if ($text === 'Browse products') {
            return __('تصفح المنتجات', 'techno-souq-theme');
        }
        if ($text === 'View') {
            return __('عرض', 'techno-souq-theme');
        }
        if ($text === 'Previous') {
            return __('السابق', 'techno-souq-theme');
        }
        if ($text === 'Next') {
            return __('التالي', 'techno-souq-theme');
        }
    }
    return $translated_text;
}, 20, 3);

/**
 * Customize My Account menu items
 */
function techno_souq_account_menu_items($items) {
    // Remove items we don't need
    unset($items['downloads']);
    
    // Reorder items
    $new_items = array();
    $new_items['dashboard'] = __('تفاصيل الحساب', 'techno-souq-theme');
    $new_items['orders'] = __('الطلبات', 'techno-souq-theme');
    $new_items['edit-address'] = __('العنوان', 'techno-souq-theme');
    $new_items['customer-logout'] = __('تسجيل خروج', 'techno-souq-theme');
    
    return $new_items;
}
add_filter('woocommerce_account_menu_items', 'techno_souq_account_menu_items', 10, 1);

/**
 * Remove required validation from billing state and postcode fields
 */
function techno_souq_remove_required_checkout_fields($fields) {
    // Remove required from billing state
    if (isset($fields['billing']['billing_state'])) {
        $fields['billing']['billing_state']['required'] = false;
    }
    
    // Remove required from billing postcode
    if (isset($fields['billing']['billing_postcode'])) {
        $fields['billing']['billing_postcode']['required'] = false;
    }
    
    // Remove required from shipping state
    if (isset($fields['shipping']['shipping_state'])) {
        $fields['shipping']['shipping_state']['required'] = false;
    }
    
    // Remove required from shipping postcode
    if (isset($fields['shipping']['shipping_postcode'])) {
        $fields['shipping']['shipping_postcode']['required'] = false;
    }
    
    return $fields;
}
add_filter('woocommerce_checkout_fields', 'techno_souq_remove_required_checkout_fields', 10, 1);

/**
 * Remove validation for state and postcode fields during checkout processing
 */
function techno_souq_remove_checkout_field_validation($data, $errors) {
    // Remove validation errors for billing state
    if (isset($errors->errors['billing_state'])) {
        unset($errors->errors['billing_state']);
    }
    
    // Remove validation errors for billing postcode
    if (isset($errors->errors['billing_postcode'])) {
        unset($errors->errors['billing_postcode']);
    }
    
    // Remove validation errors for shipping state
    if (isset($errors->errors['shipping_state'])) {
        unset($errors->errors['shipping_state']);
    }
    
    // Remove validation errors for shipping postcode
    if (isset($errors->errors['shipping_postcode'])) {
        unset($errors->errors['shipping_postcode']);
    }
}
add_action('woocommerce_after_checkout_validation', 'techno_souq_remove_checkout_field_validation', 10, 2);

/**
 * Override shortcode output to handle register page
 * Hook early to prevent default shortcode from loading form-login.php
 */
function techno_souq_override_my_account_shortcode_output() {
    // Check if we're on account page and not logged in
    // Use template_redirect because is_account_page() is not available in init
    if (!is_admin() && !is_user_logged_in()) {
        // Remove default shortcode
        remove_shortcode('woocommerce_my_account');
        
        // Add custom shortcode that loads my-account.php template
        add_shortcode('woocommerce_my_account', function($atts) {
            // Load my-account.php template which will handle login/register forms
            wc_get_template('myaccount/my-account.php');
        });
    }
}
add_action('template_redirect', 'techno_souq_override_my_account_shortcode_output', 1);

/**
 * Prevent form-login.php from loading when on register page
 * Redirect template loading to an empty file
 */
function techno_souq_prevent_login_form_on_register($template, $template_name, $args, $template_path, $default_path) {
    if (is_account_page() && !is_user_logged_in() && isset($_GET['action']) && $_GET['action'] === 'register') {
        if ($template_name === 'myaccount/form-login.php') {
            // Return path to empty template file
            $disabled_template = get_template_directory() . '/woocommerce/myaccount/form-login-disabled.php';
            if (file_exists($disabled_template)) {
                return $disabled_template;
            }
        }
    }
    return $template;
}
add_filter('wc_get_template', 'techno_souq_prevent_login_form_on_register', 10, 5);

/**
 * Hide default WooCommerce notice on password reset confirmation page
 */
function techno_souq_hide_password_reset_notice() {
    if (is_account_page() && isset($_GET['reset-link-sent']) && $_GET['reset-link-sent'] === 'true') {
        // Remove default notices
        remove_action('woocommerce_before_lost_password_confirmation_message', 'wc_print_notices', 10);
    }
}
add_action('template_redirect', 'techno_souq_hide_password_reset_notice', 5);

/**
 * AJAX handler to get wishlist products
 * Note: The actual function is defined below after toggle_favorite
 */

/**
 * Toggle favorite product (add/remove from wishlist)
 * Saves to user meta in database
 */
function techno_souq_toggle_favorite() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'techno_souq_nonce')) {
        wp_send_json_error(array('message' => __('خطأ في التحقق من الأمان', 'techno-souq-theme')));
        return;
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => __('يجب تسجيل الدخول لإضافة المنتجات إلى المفضلة', 'techno-souq-theme'),
            'require_login' => true
        ));
        return;
    }
    
    $user_id = get_current_user_id();
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    if ($product_id <= 0) {
        wp_send_json_error(array('message' => __('معرف المنتج غير صحيح', 'techno-souq-theme')));
        return;
    }
    
    // Get current favorites from user meta
    $favorites = get_user_meta($user_id, 'techno_souq_favorites', true);
    if (!is_array($favorites)) {
        $favorites = array();
    }
    
    // Toggle favorite
    $is_favorite = in_array($product_id, $favorites);
    if ($is_favorite) {
        // Remove from favorites
        $favorites = array_values(array_diff($favorites, array($product_id)));
        $action = 'removed';
    } else {
        // Add to favorites
        $favorites[] = $product_id;
        $favorites = array_values(array_unique($favorites));
        $action = 'added';
    }
    
    // Save to user meta
    update_user_meta($user_id, 'techno_souq_favorites', $favorites);
    
    wp_send_json_success(array(
        'action' => $action,
        'is_favorite' => !$is_favorite,
        'favorites_count' => count($favorites),
        'message' => $action === 'added' 
            ? __('تم إضافة المنتج إلى المفضلة', 'techno-souq-theme')
            : __('تم حذف المنتج من المفضلة', 'techno-souq-theme')
    ));
}
add_action('wp_ajax_techno_souq_toggle_favorite', 'techno_souq_toggle_favorite');

/**
 * Get user's favorite products
 */
function techno_souq_get_user_favorites() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'techno_souq_nonce')) {
        wp_send_json_error(array('message' => __('خطأ في التحقق من الأمان', 'techno-souq-theme')));
        return;
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_success(array('favorites' => array()));
        return;
    }
    
    $user_id = get_current_user_id();
    $favorites = get_user_meta($user_id, 'techno_souq_favorites', true);
    
    if (!is_array($favorites)) {
        $favorites = array();
    }
    
    // Ensure all are integers
    $favorites = array_map('intval', $favorites);
    $favorites = array_filter($favorites, function($id) {
        return $id > 0;
    });
    $favorites = array_values($favorites);
    
    wp_send_json_success(array('favorites' => $favorites));
}
add_action('wp_ajax_techno_souq_get_user_favorites', 'techno_souq_get_user_favorites');
add_action('wp_ajax_nopriv_techno_souq_get_user_favorites', 'techno_souq_get_user_favorites');

/**
 * Update get_wishlist_products to use database instead of POST product_ids
 */
function techno_souq_get_wishlist_products() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'techno_souq_nonce')) {
        wp_send_json_error(array('message' => __('خطأ في التحقق من الأمان', 'techno-souq-theme')));
        return;
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('يجب تسجيل الدخول لعرض المفضلة', 'techno-souq-theme')));
        return;
    }
    
    $user_id = get_current_user_id();
    
    // Get favorites from user meta (database)
    $product_ids = get_user_meta($user_id, 'techno_souq_favorites', true);
    
    // Debug: Log the raw user meta
    error_log('Wishlist Debug - User ID: ' . $user_id);
    error_log('Wishlist Debug - Raw user meta: ' . print_r($product_ids, true));
    
    if (!is_array($product_ids)) {
        $product_ids = array();
    }
    
    // Ensure all are integers and valid
    $product_ids = array_map('intval', $product_ids);
    $product_ids = array_filter($product_ids, function($id) {
        return $id > 0;
    });
    $product_ids = array_values($product_ids);
    
    // Debug: Log filtered product IDs
    error_log('Wishlist Debug - Filtered product IDs: ' . print_r($product_ids, true));
    
    if (empty($product_ids)) {
        wp_send_json_error(array(
            'message' => __('لا توجد منتجات في المفضلة', 'techno-souq-theme'),
            'debug' => array(
                'user_id' => $user_id,
                'raw_meta' => get_user_meta($user_id, 'techno_souq_favorites', true),
                'filtered_ids' => $product_ids
            )
        ));
        return;
    }
    
    // Query only the favorite products for this user
    // Removed meta_query as it may be filtering out products incorrectly
    $args = array(
        'post_type' => 'product',
        'post__in' => $product_ids, // Only get products in the favorites list
        'posts_per_page' => -1, // Get all favorite products
        'orderby' => 'post__in', // Maintain the order from product_ids
        'post_status' => 'publish', // Only published products
    );
    
    $products_query = new WP_Query($args);
    
    // Debug: Log query results
    error_log('Wishlist Debug - Query found posts: ' . $products_query->found_posts);
    error_log('Wishlist Debug - Query post count: ' . $products_query->post_count);
    
    $products_html = '';
    
    if ($products_query->have_posts()) {
        ob_start();
        // Don't use woocommerce_product_loop_start/end as the wrapper ul already exists in the page
        while ($products_query->have_posts()) {
            $products_query->the_post();
            // Display product (post__in already filters to only favorite products)
            wc_get_template_part('content', 'product');
        }
        wp_reset_postdata();
        $products_html = ob_get_clean();
        
        // Debug: Log HTML length
        error_log('Wishlist Debug - Products HTML length: ' . strlen($products_html));
    } else {
        // Debug: Log why no posts found
        error_log('Wishlist Debug - No posts found. Query args: ' . print_r($args, true));
    }
    
    wp_send_json_success(array(
        'products' => $products_html, 
        'count' => $products_query->found_posts,
        'debug' => array(
            'user_id' => $user_id,
            'product_ids' => $product_ids,
            'found_posts' => $products_query->found_posts,
            'post_count' => $products_query->post_count,
            'html_length' => strlen($products_html)
        )
    ));
}
add_action('wp_ajax_techno_souq_get_wishlist_products', 'techno_souq_get_wishlist_products');
add_action('wp_ajax_nopriv_techno_souq_get_wishlist_products', 'techno_souq_get_wishlist_products');

/**
 * Remove default WooCommerce empty cart message
 */
function techno_souq_remove_empty_cart_message() {
    if (is_cart()) {
        remove_action('woocommerce_cart_is_empty', 'wc_empty_cart_message', 10);
    }
}
add_action('template_redirect', 'techno_souq_remove_empty_cart_message', 1);