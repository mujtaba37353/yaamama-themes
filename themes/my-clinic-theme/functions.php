<?php
/**
 * My Clinic Theme Functions
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Setup
 */
function my_clinic_setup() {
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
        'primary' => __('القائمة الرئيسية', 'my-clinic'),
    ));

    // Set content width
    $GLOBALS['content_width'] = 1200;
}
add_action('after_setup_theme', 'my_clinic_setup');

/**
 * Enqueue Styles and Scripts
 */
function my_clinic_scripts() {
    $theme_version = wp_get_theme()->get('Version');
    $theme_uri = get_template_directory_uri();

    // Enqueue Google Fonts
    wp_enqueue_style(
        'cairo-font',
        'https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap',
        array(),
        null
    );

    // Enqueue Font Awesome
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        array(),
        '6.4.0'
    );

    // Enqueue Base Styles
    wp_enqueue_style(
        'my-clinic-reset',
        $theme_uri . '/assets/css/base/reset.css',
        array(),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-tokens',
        $theme_uri . '/assets/css/base/tokens.css',
        array('my-clinic-reset'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-typography',
        $theme_uri . '/assets/css/base/typography.css',
        array('my-clinic-tokens'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-utilities',
        $theme_uri . '/assets/css/base/utilities.css',
        array('my-clinic-typography'),
        $theme_version
    );

    // Enqueue Component Styles
    wp_enqueue_style(
        'my-clinic-header',
        $theme_uri . '/assets/css/components/header.css',
        array('my-clinic-utilities'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-footer',
        $theme_uri . '/assets/css/components/footer.css',
        array('my-clinic-utilities'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-buttons',
        $theme_uri . '/assets/css/components/buttons.css',
        array('my-clinic-utilities'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-lists',
        $theme_uri . '/assets/css/components/lists-section.css',
        array('my-clinic-utilities'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-doctors',
        $theme_uri . '/assets/css/components/doctors.css',
        array('my-clinic-utilities'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-doctor-page',
        $theme_uri . '/assets/css/components/doctor-page.css',
        array('my-clinic-utilities'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-box',
        $theme_uri . '/assets/css/components/box.css',
        array('my-clinic-utilities'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-calendar',
        $theme_uri . '/assets/css/components/calendar.css',
        array('my-clinic-box', 'my-clinic-doctor-page'),
        $theme_version
    );

    // Enqueue Layout Styles
    wp_enqueue_style(
        'my-clinic-layout',
        $theme_uri . '/assets/css/layout.css',
        array('my-clinic-header', 'my-clinic-footer', 'my-clinic-buttons', 'my-clinic-lists', 'my-clinic-doctors'),
        $theme_version
    );

    // Enqueue Breadcrumbs Styles
    wp_enqueue_style(
        'my-clinic-breadcrumbs',
        $theme_uri . '/assets/css/components/breadcrumbs.css',
        array('my-clinic-utilities'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-filter',
        $theme_uri . '/assets/css/components/filter.css',
        array('my-clinic-utilities'),
        $theme_version
    );

    wp_enqueue_style(
        'my-clinic-pagination',
        $theme_uri . '/assets/css/components/pagination.css',
        array('my-clinic-utilities'),
        $theme_version
    );
    
    // Enqueue About Us CSS (only on about-us page)
    if (is_page('about-us') || (isset($request_uri) && strpos($request_uri, '/about-us') !== false)) {
        wp_enqueue_style(
            'my-clinic-about-us',
            $theme_uri . '/assets/css/components/about-us.css',
            array('my-clinic-utilities'),
            $theme_version
        );
    }
    
    // Enqueue Contact CSS (only on contact page)
    if (is_page('contact') || is_page_template('page-contact.php') || (isset($request_uri) && strpos($request_uri, '/contact') !== false)) {
        wp_enqueue_style(
            'my-clinic-contact',
            $theme_uri . '/assets/css/components/contact.css',
            array('my-clinic-utilities'),
            $theme_version
        );
        wp_enqueue_style(
            'my-clinic-auth',
            $theme_uri . '/assets/css/components/auth.css',
            array('my-clinic-utilities'),
            $theme_version
        );
    }
    
    // Enqueue Booking CSS and JS (only on booking page)
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    if (strpos($request_uri, '/booking') !== false || (isset($_GET['doctor_id']) || isset($_GET['clinic_id']))) {
        wp_enqueue_style(
            'my-clinic-booking',
            $theme_uri . '/assets/css/components/booking.css',
            array(),
            $theme_version
        );
        
        wp_enqueue_script(
            'my-clinic-booking-validation',
            $theme_uri . '/assets/js/booking-validation.js',
            array(),
            $theme_version,
            true
        );
    }
    
    // Enqueue Checkout CSS and JS (only on checkout page)
    if (is_checkout() || strpos($request_uri, '/checkout') !== false) {
        wp_enqueue_style(
            'my-clinic-checkout',
            $theme_uri . '/assets/css/components/checkout.css',
            array(),
            $theme_version
        );
        
        wp_enqueue_script(
            'my-clinic-checkout-validation',
            $theme_uri . '/assets/js/checkout-validation.js',
            array(),
            $theme_version,
            true
        );
    }

    // Enqueue Thank You CSS (only on thank-you page)
    if (strpos($request_uri, '/thank-you') !== false) {
        wp_enqueue_style(
            'my-clinic-thank-you',
            $theme_uri . '/assets/css/components/thank-you.css',
            array(),
            $theme_version
        );
    }

    // Enqueue My Account CSS (only on my-account page)
    if (is_account_page() || strpos($request_uri, '/my-account') !== false) {
        wp_enqueue_style(
            'my-clinic-my-account',
            $theme_uri . '/assets/css/components/my-account.css',
            array(),
            $theme_version
        );
    }

    // Enqueue Auth CSS (on my-account page for login/register forms)
    if (is_account_page() || strpos($request_uri, '/my-account') !== false) {
        wp_enqueue_style(
            'my-clinic-auth',
            $theme_uri . '/assets/css/components/auth.css',
            array(),
            $theme_version
        );
    }

    // Enqueue Main Theme Style
    wp_enqueue_style(
        'my-clinic-style',
        get_stylesheet_uri(),
        array('my-clinic-layout'),
        $theme_version
    );

    // Enqueue Scripts
    wp_enqueue_script(
        'my-clinic-init',
        $theme_uri . '/assets/js/y-app-init.js',
        array(),
        $theme_version,
        true
    );
    
    // Enqueue Calendar Script
    if (file_exists(get_template_directory() . '/assets/js/calendar.js')) {
    wp_enqueue_script(
        'my-clinic-calendar',
        $theme_uri . '/assets/js/calendar.js',
        array(),
        $theme_version,
        true
    );

    wp_enqueue_script(
        'my-clinic-search-filter',
        $theme_uri . '/assets/js/search-filter.js',
        array(),
        $theme_version,
        true
    );
    }
    
    // Enqueue Dropdown Script
    if (file_exists(get_template_directory() . '/assets/js/dropdown.js')) {
        wp_enqueue_script(
            'my-clinic-dropdown',
            $theme_uri . '/assets/js/dropdown.js',
            array(),
            $theme_version,
            true
        );
    }
    
    // Enqueue Prevent Scroll Mobile Script
    if (file_exists(get_template_directory() . '/assets/js/prevent-scroll-mobile.js')) {
        wp_enqueue_script(
            'my-clinic-prevent-scroll-mobile',
            $theme_uri . '/assets/js/prevent-scroll-mobile.js',
            array('my-clinic-dropdown'),
            $theme_version,
            true
        );
    }
    
    // Enqueue Doctor Reviews Script (only on single doctor page)
    if (is_singular('doctor')) {
        wp_enqueue_script(
            'my-clinic-doctor-reviews',
            $theme_uri . '/assets/js/doctor-reviews.js',
            array(),
            $theme_version,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('my-clinic-doctor-reviews', 'ajaxurl', admin_url('admin-ajax.php'));
    }
    
    // Enqueue Clinic Reviews Script (only on single clinic page)
    if (is_singular('clinic')) {
        wp_enqueue_script(
            'my-clinic-clinic-reviews',
            $theme_uri . '/assets/js/clinic-reviews.js',
            array(),
            $theme_version,
            true
        );
        
        // Localize script for AJAX
        wp_localize_script('my-clinic-clinic-reviews', 'ajaxurl', admin_url('admin-ajax.php'));
    }
}
add_action('wp_enqueue_scripts', 'my_clinic_scripts');

/**
 * Fallback Menu
 */
function my_clinic_fallback_menu() {
    echo '<ul class="desktop-menu y-u-flex y-u-justify-end y-u-items-center">';
    echo '<li><a href="' . esc_url(home_url('/')) . '">الرئيسيه</a></li>';
    echo '<li><a href="' . esc_url(home_url('/doctors')) . '">الأطباء</a></li>';
    echo '<li><a href="' . esc_url(home_url('/clinics')) . '">العيادات</a></li>';
    echo '<li><a href="' . esc_url(home_url('/about-us')) . '">من نحن</a></li>';
    echo '</ul>';
}

/**
 * Register Custom Product Types for WooCommerce
 */
function my_clinic_register_product_types() {
    if (!class_exists('WooCommerce')) {
        return;
    }

    // Register Doctor Product Type
    if (!class_exists('WC_Product_Doctor')) {
        class WC_Product_Doctor extends WC_Product_Simple {
            public function __construct($product = 0) {
                $this->product_type = 'doctor';
                parent::__construct($product);
            }

            public function get_type() {
                return 'doctor';
            }

            public function is_virtual() {
                return true;
            }

            public function is_downloadable() {
                return false;
            }
        }
    }

    // Register Clinic Product Type
    if (!class_exists('WC_Product_Clinic')) {
        class WC_Product_Clinic extends WC_Product_Simple {
            public function __construct($product = 0) {
                $this->product_type = 'clinic';
                parent::__construct($product);
            }

            public function get_type() {
                return 'clinic';
            }

            public function is_virtual() {
                return true;
            }

            public function is_downloadable() {
                return false;
            }
        }
    }
}
add_action('woocommerce_loaded', 'my_clinic_register_product_types', 20);

/**
 * Add Custom Product Types to WooCommerce
 */
function my_clinic_add_product_types($types) {
    if (!class_exists('WooCommerce')) {
        return $types;
    }
    $types['doctor'] = __('طبيب', 'my-clinic');
    $types['clinic'] = __('عيادة', 'my-clinic');
    return $types;
}
add_filter('product_type_selector', 'my_clinic_add_product_types');

/**
 * Map product type to product class
 */
function my_clinic_product_class($classname, $product_type, $post_type, $product_id) {
    if ($product_type === 'doctor' && class_exists('WC_Product_Doctor')) {
        return 'WC_Product_Doctor';
    }
    if ($product_type === 'clinic' && class_exists('WC_Product_Clinic')) {
        return 'WC_Product_Clinic';
    }
    return $classname;
}
add_filter('woocommerce_product_class', 'my_clinic_product_class', 10, 4);

/**
 * Show Custom Product Type Options
 */
function my_clinic_product_type_options($options) {
    if (!class_exists('WooCommerce')) {
        return $options;
    }
    return $options;
}
add_filter('product_type_options', 'my_clinic_product_type_options');

/**
 * Add Custom Fields for Doctor Products
 */
function my_clinic_add_doctor_fields() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    global $woocommerce, $post;
    
    echo '<div class="options_group show_if_doctor">';
    
    woocommerce_wp_text_input(array(
        'id' => '_doctor_specialty',
        'label' => __('التخصص', 'my-clinic'),
        'placeholder' => __('مثال: عظام، باطنة، أسنان', 'my-clinic'),
        'desc_tip' => 'true',
        'description' => __('تخصص الطبيب', 'my-clinic'),
    ));

    woocommerce_wp_text_input(array(
        'id' => '_doctor_degree',
        'label' => __('الدرجة العلمية', 'my-clinic'),
        'placeholder' => __('مثال: استاذ جامعي، استشاري', 'my-clinic'),
        'desc_tip' => 'true',
        'description' => __('الدرجة العلمية للطبيب', 'my-clinic'),
    ));

    woocommerce_wp_textarea_input(array(
        'id' => '_doctor_description',
        'label' => __('الوصف الكامل', 'my-clinic'),
        'placeholder' => __('وصف تفصيلي عن الطبيب وخبراته', 'my-clinic'),
        'description' => __('وصف شامل للطبيب', 'my-clinic'),
    ));

    woocommerce_wp_text_input(array(
        'id' => '_doctor_rating',
        'label' => __('التقييم', 'my-clinic'),
        'type' => 'number',
        'custom_attributes' => array(
            'step' => '0.1',
            'min' => '0',
            'max' => '5',
        ),
        'placeholder' => '5',
    ));

    woocommerce_wp_text_input(array(
        'id' => '_doctor_views',
        'label' => __('عدد المشاهدات', 'my-clinic'),
        'type' => 'number',
        'placeholder' => '0',
    ));

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'my_clinic_add_doctor_fields');

/**
 * Add Custom Fields for Clinic Products
 */
function my_clinic_add_clinic_fields() {
    if (!class_exists('WooCommerce')) {
        return;
    }
    global $woocommerce, $post;
    
    echo '<div class="options_group show_if_clinic">';
    
    woocommerce_wp_text_input(array(
        'id' => '_clinic_address',
        'label' => __('العنوان', 'my-clinic'),
        'placeholder' => __('عنوان العيادة', 'my-clinic'),
    ));

    woocommerce_wp_text_input(array(
        'id' => '_clinic_phone',
        'label' => __('رقم الهاتف', 'my-clinic'),
        'placeholder' => __('رقم هاتف العيادة', 'my-clinic'),
    ));

    woocommerce_wp_textarea_input(array(
        'id' => '_clinic_description',
        'label' => __('الوصف الكامل', 'my-clinic'),
        'placeholder' => __('وصف تفصيلي عن العيادة وخدماتها', 'my-clinic'),
    ));

    woocommerce_wp_text_input(array(
        'id' => '_clinic_rating',
        'label' => __('التقييم', 'my-clinic'),
        'type' => 'number',
        'custom_attributes' => array(
            'step' => '0.1',
            'min' => '0',
            'max' => '5',
        ),
        'placeholder' => '5',
    ));

    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', 'my_clinic_add_clinic_fields');

/**
 * Save Custom Fields
 */
function my_clinic_save_custom_fields($post_id) {
    if (!class_exists('WooCommerce')) {
        return;
    }

    $product = wc_get_product($post_id);
    if (!$product) {
        return;
    }

    $product_type = $product->get_type();

    // Save Doctor Fields
    if ($product_type === 'doctor') {
        if (isset($_POST['_doctor_specialty'])) {
            update_post_meta($post_id, '_doctor_specialty', sanitize_text_field($_POST['_doctor_specialty']));
        }
        if (isset($_POST['_doctor_degree'])) {
            update_post_meta($post_id, '_doctor_degree', sanitize_text_field($_POST['_doctor_degree']));
        }
        if (isset($_POST['_doctor_description'])) {
            update_post_meta($post_id, '_doctor_description', sanitize_textarea_field($_POST['_doctor_description']));
        }
        if (isset($_POST['_doctor_rating'])) {
            update_post_meta($post_id, '_doctor_rating', floatval($_POST['_doctor_rating']));
        }
        if (isset($_POST['_doctor_views'])) {
            update_post_meta($post_id, '_doctor_views', intval($_POST['_doctor_views']));
        }
    }

    // Save Clinic Fields
    if ($product_type === 'clinic') {
        if (isset($_POST['_clinic_address'])) {
            update_post_meta($post_id, '_clinic_address', sanitize_text_field($_POST['_clinic_address']));
        }
        if (isset($_POST['_clinic_phone'])) {
            update_post_meta($post_id, '_clinic_phone', sanitize_text_field($_POST['_clinic_phone']));
        }
        if (isset($_POST['_clinic_description'])) {
            update_post_meta($post_id, '_clinic_description', sanitize_textarea_field($_POST['_clinic_description']));
        }
        if (isset($_POST['_clinic_rating'])) {
            update_post_meta($post_id, '_clinic_rating', floatval($_POST['_clinic_rating']));
        }
    }
}
add_action('woocommerce_process_product_meta', 'my_clinic_save_custom_fields');

/**
 * Register Custom Post Type for Doctors
 */
function my_clinic_register_doctor_post_type() {
    $labels = array(
        'name' => __('الأطباء', 'my-clinic'),
        'singular_name' => __('طبيب', 'my-clinic'),
        'add_new' => __('إضافة طبيب جديد', 'my-clinic'),
        'add_new_item' => __('إضافة طبيب جديد', 'my-clinic'),
        'edit_item' => __('تعديل الطبيب', 'my-clinic'),
        'new_item' => __('طبيب جديد', 'my-clinic'),
        'view_item' => __('عرض الطبيب', 'my-clinic'),
        'search_items' => __('بحث عن أطباء', 'my-clinic'),
        'not_found' => __('لم يتم العثور على أطباء', 'my-clinic'),
        'not_found_in_trash' => __('لم يتم العثور على أطباء في المهملات', 'my-clinic'),
        'all_items' => __('جميع الأطباء', 'my-clinic'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => false, // We'll add custom menu
        'query_var' => true,
        'rewrite' => array('slug' => 'doctor'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    );

    register_post_type('doctor', $args);
}
add_action('init', 'my_clinic_register_doctor_post_type');

/**
 * Register Custom Post Type for Clinics
 */
function my_clinic_register_clinic_post_type() {
    $labels = array(
        'name' => __('العيادات', 'my-clinic'),
        'singular_name' => __('عيادة', 'my-clinic'),
        'add_new' => __('إضافة عيادة جديدة', 'my-clinic'),
        'add_new_item' => __('إضافة عيادة جديدة', 'my-clinic'),
        'edit_item' => __('تعديل العيادة', 'my-clinic'),
        'new_item' => __('عيادة جديدة', 'my-clinic'),
        'view_item' => __('عرض العيادة', 'my-clinic'),
        'search_items' => __('بحث عن عيادات', 'my-clinic'),
        'not_found' => __('لم يتم العثور على عيادات', 'my-clinic'),
        'not_found_in_trash' => __('لم يتم العثور على عيادات في المهملات', 'my-clinic'),
        'all_items' => __('جميع العيادات', 'my-clinic'),
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => false, // We'll add custom menu
        'query_var' => true,
        'rewrite' => array('slug' => 'clinic'),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'show_in_rest' => true,
    );

    register_post_type('clinic', $args);
}
add_action('init', 'my_clinic_register_clinic_post_type');

/**
 * Register rewrite rules for doctors and clinics archives
 * REMOVED - pages deleted
 */

/**
 * Flush rewrite rules on theme activation
 * REMOVED - pages deleted
 */

/**
 * Add query vars for product types
 * REMOVED - pages deleted
 */

/**
 * Handle product type queries
 * REMOVED - pages deleted
 */

/**
 * Use custom template for clinic and doctor archives
 * REMOVED - woocommerce folder deleted
 */

/**
 * Filter products by type in query
 * REMOVED - pages deleted
 */

/**
 * Use custom product template based on product type
 * REMOVED - woocommerce folder deleted
 */

/**
 * Override wc_get_template_part to use custom templates
 * REMOVED - woocommerce folder deleted
 */

/**
 * Upload image from file path to WordPress media library
 */
function my_clinic_upload_image_from_path($file_path, $title = '') {
    if (!file_exists($file_path)) {
        return false;
    }

    require_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');

    $filename = basename($file_path);
    $upload_file = wp_upload_bits($filename, null, file_get_contents($file_path));

    if (!$upload_file['error']) {
        $wp_filetype = wp_check_filetype($filename, null);
        $attachment = array(
            'post_mime_type' => $wp_filetype['type'],
            'post_title' => $title ? sanitize_file_name($title) : preg_replace('/\.[^.]+$/', '', $filename),
            'post_content' => '',
            'post_status' => 'inherit'
        );

        $attachment_id = wp_insert_attachment($attachment, $upload_file['file']);
        $attach_data = wp_generate_attachment_metadata($attachment_id, $upload_file['file']);
        wp_update_attachment_metadata($attachment_id, $attach_data);

        return $attachment_id;
    }

    return false;
}

/**
 * Include Admin Pages
 */
$inc_dir = get_template_directory() . '/inc/';

if (file_exists($inc_dir . 'doctors-query.php')) {
    require_once $inc_dir . 'doctors-query.php';
}
if (file_exists($inc_dir . 'doctor-clinic-products.php')) {
    require_once $inc_dir . 'doctor-clinic-products.php';
}
if (file_exists($inc_dir . 'doctor-reviews.php')) {
    require_once $inc_dir . 'doctor-reviews.php'; // Load before admin-doctors.php
}
if (file_exists($inc_dir . 'clinic-reviews.php')) {
    require_once $inc_dir . 'clinic-reviews.php'; // Load before admin-clinics.php
}
if (file_exists($inc_dir . 'customizer.php')) {
    require_once $inc_dir . 'customizer.php'; // Theme Customizer Settings
}
if (file_exists($inc_dir . 'admin-homepage-settings.php')) {
    require_once $inc_dir . 'admin-homepage-settings.php'; // Homepage Settings Page
}
if (file_exists($inc_dir . 'admin-contact-settings.php')) {
    require_once $inc_dir . 'admin-contact-settings.php'; // Contact Settings Page
}
if (file_exists($inc_dir . 'admin-doctors.php')) {
    require_once $inc_dir . 'admin-doctors.php';
}
if (file_exists($inc_dir . 'admin-clinics.php')) {
    require_once $inc_dir . 'admin-clinics.php';
}
if (file_exists($inc_dir . 'admin-demo-content.php')) {
    require_once $inc_dir . 'admin-demo-content.php';
}
if (file_exists($inc_dir . 'admin-pages-setup.php')) {
    require_once $inc_dir . 'admin-pages-setup.php';
}

/**
 * Load custom page templates for doctors and clinics
 */
function my_clinic_load_custom_page_templates($template) {
    global $post;
    
    if (!$post) {
        return $template;
    }
    
    // Check if page slug is 'doctors'
    if (is_page('doctors') || (is_page() && $post->post_name === 'doctors')) {
        $custom_template = locate_template('doctors-page.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    // Check if page slug is 'clinics'
    if (is_page('clinics') || (is_page() && $post->post_name === 'clinics')) {
        $custom_template = locate_template('clinic-page.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    // Check if page slug is 'booking'
    if (is_page('booking') || (is_page() && $post->post_name === 'booking')) {
        $custom_template = locate_template('booking.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    // Check if page slug is 'contact' or 'contact-us'
    if (is_page('contact') || is_page('contact-us') || (is_page() && ($post->post_name === 'contact' || $post->post_name === 'contact-us'))) {
        $custom_template = locate_template('page-contact.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    // Check if page slug is 'about-us'
    if (is_page('about-us') || (is_page() && $post->post_name === 'about-us')) {
        $custom_template = locate_template('page-about-us.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    // Check if page slug is 'account-deleted'
    if (is_page('account-deleted') || (is_page() && $post->post_name === 'account-deleted')) {
        $custom_template = locate_template('account-deleted.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    // Check if page slug is 'account-deleted'
    if (is_page('account-deleted') || (is_page() && $post->post_name === 'account-deleted')) {
        $custom_template = locate_template('account-deleted.php');
        if ($custom_template) {
            return $custom_template;
        }
    }
    
    return $template;
}
add_filter('page_template', 'my_clinic_load_custom_page_templates');

/**
 * Load booking template for /booking URL
 */
function my_clinic_load_booking_template($template) {
    $request_uri = $_SERVER['REQUEST_URI'];
    
    // Check if URL is /booking with or without query parameters
    if (preg_match('#^/booking(?:\?|$)#', $request_uri)) {
        $booking_template = locate_template('booking.php');
        if ($booking_template) {
            return $booking_template;
        }
    }
    
    // Check if URL is /account-deleted
    if (preg_match('#^/account-deleted(?:\?|$)#', $request_uri)) {
        $deleted_template = locate_template('account-deleted.php');
        if ($deleted_template) {
            return $deleted_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'my_clinic_load_booking_template', 99);

/**
 * Load custom checkout template for /checkout URL
 */
function my_clinic_load_checkout_template($template) {
    $request_uri = $_SERVER['REQUEST_URI'];
    $parsed_url = parse_url($request_uri);
    $path = isset($parsed_url['path']) ? trim($parsed_url['path'], '/') : '';
    
    // Check if URL is /checkout
    if ($path === 'checkout' || strpos($path, 'checkout') === 0) {
        $checkout_template = locate_template('woocommerce/checkout.php');
        if ($checkout_template) {
            return $checkout_template;
        }
    }
    
    return $template;
}
add_filter('template_include', 'my_clinic_load_checkout_template', 99);

/**
 * Load thank-you template for /thank-you URL
 */
function my_clinic_load_thank_you_template($template) {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $parsed = parse_url($request_uri);
    $path = isset($parsed['path']) ? trim($parsed['path'], '/') : '';
    if ($path === 'thank-you' || strpos($path, 'thank-you') === 0) {
        $ty = locate_template('thank-you.php');
        if ($ty) {
            return $ty;
        }
    }
    return $template;
}
add_filter('template_include', 'my_clinic_load_thank_you_template', 99);

/**
 * Add booking data to cart item when product is added
 */
function my_clinic_add_booking_data_to_cart_item($cart_item_data, $product_id) {
    if (function_exists('WC') && WC()->session) {
        $doctor_id = WC()->session->get('booking_doctor_id');
        $clinic_id = WC()->session->get('booking_clinic_id');
        $date = WC()->session->get('booking_date');
        $patient_name = WC()->session->get('booking_patient_name');
        $patient_phone = WC()->session->get('booking_patient_phone');
        $patient_email = WC()->session->get('booking_patient_email');

        if ($doctor_id || $clinic_id) {
            $cart_item_data['booking_doctor_id'] = $doctor_id ?: 0;
            $cart_item_data['booking_clinic_id'] = $clinic_id ?: 0;
            $cart_item_data['booking_date'] = $date ?: '';
            $cart_item_data['booking_patient_name'] = $patient_name ?: '';
            $cart_item_data['booking_patient_phone'] = $patient_phone ?: '';
            $cart_item_data['booking_patient_email'] = $patient_email ?: '';

            // Clear session data after use
            WC()->session->__unset('booking_doctor_id');
            WC()->session->__unset('booking_clinic_id');
            WC()->session->__unset('booking_date');
            WC()->session->__unset('booking_patient_name');
            WC()->session->__unset('booking_patient_phone');
            WC()->session->__unset('booking_patient_email');
        }
    }
    return $cart_item_data;
}
add_filter('woocommerce_add_cart_item_data', 'my_clinic_add_booking_data_to_cart_item', 10, 2);

/**
 * Display booking data in cart (optional - for debugging)
 */
function my_clinic_get_cart_item_from_session($cart_item, $values) {
    if (isset($values['booking_doctor_id'])) {
        $cart_item['booking_doctor_id'] = $values['booking_doctor_id'];
    }
    if (isset($values['booking_clinic_id'])) {
        $cart_item['booking_clinic_id'] = $values['booking_clinic_id'];
    }
    if (isset($values['booking_date'])) {
        $cart_item['booking_date'] = $values['booking_date'];
    }
    if (isset($values['booking_patient_name'])) {
        $cart_item['booking_patient_name'] = $values['booking_patient_name'];
    }
    if (isset($values['booking_patient_phone'])) {
        $cart_item['booking_patient_phone'] = $values['booking_patient_phone'];
    }
    if (isset($values['booking_patient_email'])) {
        $cart_item['booking_patient_email'] = $values['booking_patient_email'];
    }
    return $cart_item;
}
add_filter('woocommerce_get_cart_item_from_session', 'my_clinic_get_cart_item_from_session', 10, 2);

/**
 * Disable WooCommerce redirect to cart when accessing checkout from booking page
 */
function my_clinic_prevent_checkout_redirect() {
    // Allow checkout even if cart appears empty (we handle booking via cart)
    if (isset($_SERVER['REQUEST_URI']) && strpos($_SERVER['REQUEST_URI'], '/checkout') !== false) {
        // Prevent WooCommerce from redirecting to cart
        add_filter('woocommerce_checkout_redirect_empty_cart', '__return_false');
    }
}
add_action('template_redirect', 'my_clinic_prevent_checkout_redirect', 1);

/**
 * ترجمة عناوين طرق الدفع إلى العربية
 */
function my_clinic_payment_gateway_title_arabic($title, $gateway_id) {
    $titles = array(
        'bacs'       => 'التحويل البنكي',
        'cod'        => 'الدفع عند الاستلام',
        'cheque'     => 'الدفع بالشيك',
        'paypal'     => 'باي بال',
        'ppec_paypal'=> 'باي بال',
        'stripe'     => 'سترايب',
        'wc_stripe'  => 'سترايب',
        'mada'       => 'مدى',
        'cc'         => 'البطاقة الائتمانية',
        'tabby'      => 'تابي',
        'tabby_installments' => 'تابي بالتقسيط',
        'tap'        => 'تاب',
        'hyperpay'   => 'هايبر باي',
        'vodapay'    => 'فودافون كاش',
        'amazon_payments_advanced' => 'أمازون للدفع',
        'klarna'     => 'كلارنا',
        'authorize_net_cim_credit_card' => 'البطاقة الائتمانية',
        'square'     => 'سكوير',
        'apple_pay'  => 'أبل باي',
    );
    if (isset($titles[$gateway_id])) {
        return $titles[$gateway_id];
    }
    return $title;
}
add_filter('woocommerce_gateway_title', 'my_clinic_payment_gateway_title_arabic', 10, 2);

/**
 * Remove default WooCommerce privacy policy text and add Arabic version
 */
function my_clinic_remove_woocommerce_privacy_text() {
    // Remove the default privacy policy text hook
    remove_action('woocommerce_register_form', 'wc_registration_privacy_policy_text', 20);
}
add_action('woocommerce_register_form_start', 'my_clinic_remove_woocommerce_privacy_text', 5);

/**
 * Handle WooCommerce login form submission
 */
function my_clinic_handle_woocommerce_login() {
    // Check if login form was submitted
    if (isset($_POST['login']) && isset($_POST['woocommerce-login-nonce']) && wp_verify_nonce($_POST['woocommerce-login-nonce'], 'woocommerce-login')) {
        // Only process if on my-account page or if request URI contains my-account
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        $is_myaccount = (is_account_page() || strpos($request_uri, 'my-account') !== false);
        
        if (!$is_myaccount) {
            return;
        }
        
        $username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $remember = isset($_POST['rememberme']) ? true : false;
        
        if (empty($username) || empty($password)) {
            wc_add_notice(__('يرجى إدخال اسم المستخدم وكلمة المرور.', 'my-clinic'), 'error');
            return;
        }
        
        // Use WooCommerce login function
        $user = wp_authenticate($username, $password);
        
        if (is_wp_error($user)) {
            wc_add_notice($user->get_error_message(), 'error');
            return;
        }
        
        // Set auth cookie and redirect
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, $remember);
        
        // Redirect to my-account page
        $redirect_url = wc_get_page_permalink('myaccount');
        if (is_ssl()) {
            $redirect_url = str_replace('http://', 'https://', $redirect_url);
        }
        wp_safe_redirect($redirect_url);
        exit;
    }
}
add_action('init', 'my_clinic_handle_woocommerce_login', 5);

/**
 * Handle WooCommerce registration form submission
 */
function my_clinic_handle_woocommerce_registration() {
    // Check if registration form was submitted
    if (isset($_POST['register']) && isset($_POST['woocommerce-register-nonce']) && wp_verify_nonce($_POST['woocommerce-register-nonce'], 'woocommerce-register')) {
        // Only process if on my-account page or if request URI contains my-account
        $request_uri = $_SERVER['REQUEST_URI'] ?? '';
        $is_myaccount = (is_account_page() || strpos($request_uri, 'my-account') !== false);
        
        if (!$is_myaccount) {
            return;
        }
        
        // Ensure WooCommerce session is started
        if (function_exists('WC')) {
            if (!WC()->session->has_session()) {
                WC()->session->set_customer_session_cookie(true);
            }
        }
        
        $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';
        $password_confirm = isset($_POST['password_confirm']) ? $_POST['password_confirm'] : '';
        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $gender = isset($_POST['gender']) ? sanitize_text_field($_POST['gender']) : '';
        
        $has_errors = false;
        $error_messages = array();
        
        // Validate email
        if (empty($email) || !is_email($email)) {
            $error_messages[] = __('يرجى إدخال بريد إلكتروني صحيح.', 'my-clinic');
            $has_errors = true;
        }
        
        // Check if email already exists
        if (!empty($email) && is_email($email) && email_exists($email)) {
            $error_messages[] = __('البريد الإلكتروني مستخدم بالفعل.', 'my-clinic');
            $has_errors = true;
        }
        
        // Validate phone
        if (empty($phone)) {
            $error_messages[] = __('يرجى إدخال رقم الجوال.', 'my-clinic');
            $has_errors = true;
        } elseif (!preg_match('/^05\d{8}$/', $phone)) {
            $error_messages[] = __('رقم الجوال يجب أن يبدأ بـ 05 ويتبعه 8 أرقام.', 'my-clinic');
            $has_errors = true;
        }
        
        // Validate gender
        if (empty($gender) || !in_array($gender, array('male', 'female'))) {
            $error_messages[] = __('يرجى اختيار النوع (ذكر أو أنثى).', 'my-clinic');
            $has_errors = true;
        }
        
        // Validate password if password generation is disabled
        if ('no' === get_option('woocommerce_registration_generate_password')) {
            if (empty($password)) {
                $error_messages[] = __('يرجى إدخال كلمة مرور.', 'my-clinic');
                $has_errors = true;
            } elseif (strlen($password) < 6) {
                $error_messages[] = __('كلمة المرور يجب أن تكون 6 أحرف على الأقل.', 'my-clinic');
                $has_errors = true;
            } elseif (!empty($password_confirm) && $password !== $password_confirm) {
                $error_messages[] = __('كلمات المرور غير متطابقة.', 'my-clinic');
                $has_errors = true;
            }
        }
        
        // If there are errors, add all notices and redirect back to show errors
        if ($has_errors) {
            // Ensure WooCommerce is loaded
            if (!function_exists('wc_add_notice')) {
                return;
            }
            
            // Ensure session is started and active
            if (function_exists('WC') && WC()->session) {
                if (!WC()->session->has_session()) {
                    WC()->session->set_customer_session_cookie(true);
                }
                
                // Store POST data in session to repopulate form
                WC()->session->set('registration_form_data', array(
                    'email' => $email,
                    'phone' => $phone
                ));
            }
            
            // Add all error messages - this must happen after session is started
            foreach ($error_messages as $error_msg) {
                wc_add_notice($error_msg, 'error');
            }
            
            // Force session save before redirect
            if (function_exists('WC') && WC()->session) {
                WC()->session->save_data();
            }
            
            // Redirect back to registration page to show errors
            $redirect_url = add_query_arg('action', 'register', wc_get_page_permalink('myaccount'));
            if (is_ssl()) {
                $redirect_url = str_replace('http://', 'https://', $redirect_url);
            }
            wp_safe_redirect($redirect_url);
            exit;
        }
        
        // Create user
        $username = $email; // Use email as username
        $user_id = wp_create_user($username, $password ?: wp_generate_password(), $email);
        
        if (is_wp_error($user_id)) {
            $error_message = $user_id->get_error_message();
            // Translate common WordPress errors to Arabic
            if (strpos($error_message, 'username') !== false || strpos($error_message, 'Username') !== false) {
                wc_add_notice(__('اسم المستخدم مستخدم بالفعل. يرجى استخدام بريد إلكتروني آخر.', 'my-clinic'), 'error');
            } else {
                wc_add_notice($error_message, 'error');
            }
            return;
        }
        
        // Save user meta
        if (!empty($phone)) {
            update_user_meta($user_id, 'billing_phone', $phone);
        }
        if (!empty($gender)) {
            update_user_meta($user_id, 'gender', $gender);
        }
        
        // Auto login after registration
        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id);
        
        // Add success notice
        wc_add_notice(__('تم إنشاء حسابك بنجاح! مرحباً بك.', 'my-clinic'), 'success');
        
        // Redirect to my-account page
        $redirect_url = wc_get_page_permalink('myaccount');
        if (is_ssl()) {
            $redirect_url = str_replace('http://', 'https://', $redirect_url);
        }
        wp_safe_redirect($redirect_url);
        exit;
    }
}
add_action('init', 'my_clinic_handle_woocommerce_registration', 5);

/**
 * Handle account deletion
 */
function my_clinic_handle_account_deletion() {
    // Check if this is a delete account request
    if (!isset($_GET['action']) || $_GET['action'] !== 'delete_account') {
        return;
    }
    
    // Verify nonce
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'delete_account_' . get_current_user_id())) {
        wp_die(__('خطأ في التحقق من الأمان. يرجى المحاولة مرة أخرى.', 'my-clinic'));
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_redirect(home_url('/'));
        exit;
    }
    
    $user_id = get_current_user_id();
    
    // Logout user first
    wp_logout();
    
    // Delete user account
    require_once(ABSPATH . 'wp-admin/includes/user.php');
    wp_delete_user($user_id);
    
    // Redirect to account deleted page
    wp_redirect(home_url('/account-deleted/'));
    exit;
}
add_action('admin_post_delete_account', 'my_clinic_handle_account_deletion');
add_action('admin_post_nopriv_delete_account', 'my_clinic_handle_account_deletion');

/**
 * Override WooCommerce My Account templates
 */
function my_clinic_woocommerce_locate_template($template, $template_name, $template_path) {
    // Override my-account templates (including form-login, form-register, etc.)
    if (strpos($template_name, 'myaccount/') === 0) {
        $custom_template = locate_template('woocommerce/' . $template_name);
        if ($custom_template) {
            return $custom_template;
        }
    }
    return $template;
}
add_filter('woocommerce_locate_template', 'my_clinic_woocommerce_locate_template', 10, 3);

/**
 * Don't redirect unauthenticated users - let WooCommerce show login form
 * WooCommerce will automatically show form-login.php for unauthenticated users
 */

/**
 * Load custom My Account template when on my-account page
 * For unauthenticated users, use WooCommerce's myaccount.php template
 * For authenticated users, load custom templates based on endpoint
 */
function my_clinic_load_myaccount_template($template) {
    if (!is_account_page()) {
        return $template;
    }
    
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $parsed = parse_url($request_uri);
    $path = isset($parsed['path']) ? trim($parsed['path'], '/') : '';
    
    // Check if we're on my-account page
    if ($path === 'my-account' || strpos($path, 'my-account') === 0) {
        // If user is not logged in, use WooCommerce's myaccount.php template
        if (!is_user_logged_in()) {
            $myaccount_template = locate_template('woocommerce/myaccount/myaccount.php');
            if ($myaccount_template) {
                return $myaccount_template;
            }
            // Fallback: let WooCommerce handle it
            return $template;
        }
        
        // User is logged in - load custom templates based on endpoint
        $endpoint = '';
        if (function_exists('WC')) {
            $endpoint = WC()->query->get_current_endpoint();
        }
        
        // Determine which template to load
        if ($endpoint === 'orders') {
            $orders_template = locate_template('woocommerce/myaccount/orders.php');
            if ($orders_template) {
                return $orders_template;
            }
        } elseif ($endpoint === 'edit-account') {
            $edit_template = locate_template('woocommerce/myaccount/edit-account.php');
            if ($edit_template) {
                return $edit_template;
            }
        } else {
            // Default to dashboard or my-account template
            $dashboard_template = locate_template('woocommerce/myaccount/dashboard.php');
            if ($dashboard_template) {
                return $dashboard_template;
            }
            // Fallback to my-account.php if dashboard doesn't exist
            $my_account_template = locate_template('woocommerce/myaccount/my-account.php');
            if ($my_account_template) {
                return $my_account_template;
            }
        }
    }
    
    return $template;
}
add_filter('template_include', 'my_clinic_load_myaccount_template', 99);

/**
 * Get My Account URL with fallback
 */
function my_clinic_get_myaccount_url() {
    if (function_exists('wc_get_page_permalink') && function_exists('WC')) {
        $myaccount_url = wc_get_page_permalink('myaccount');
        if ($myaccount_url) {
            return $myaccount_url;
        }
    }
    // Fallback to home URL
    return home_url('/my-account');
}

/**
 * Redirect logout to my-account page (WooCommerce will show login form)
 */
function my_clinic_logout_redirect($redirect_to, $requested_redirect_to, $user) {
    // Redirect to my-account page - WooCommerce will show login form
    $myaccount_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account');
    return $myaccount_url;
}
add_filter('logout_redirect', 'my_clinic_logout_redirect', 10, 3);

/**
 * Override wp_logout_url to redirect to my-account page
 */
function my_clinic_logout_url($logout_url, $redirect) {
    // Use my-account page as redirect - WooCommerce will show login form
    $myaccount_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account');
    // Force HTTPS if site is using HTTPS
    if (is_ssl()) {
        $myaccount_url = str_replace('http://', 'https://', $myaccount_url);
    }
    // Build logout URL with nonce
    $login_php_url = site_url('wp-login.php?action=logout&redirect_to=' . urlencode($myaccount_url), 'login');
    $logout_url = wp_nonce_url($login_php_url, 'log-out');
    return $logout_url;
}
add_filter('logout_url', 'my_clinic_logout_url', 999, 2);

/**
 * Redirect after logout to my-account page
 * WooCommerce will show login form for unauthenticated users
 */
function my_clinic_logout_redirect_handler() {
    // Get request URI - handle both HTTP and HTTPS
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $script_name = isset($_SERVER['SCRIPT_NAME']) ? basename($_SERVER['SCRIPT_NAME']) : '';
    
    // Check if we're on wp-login.php after logout
    $is_login_page = (
        $script_name === 'wp-login.php' || 
        strpos($request_uri, 'wp-login.php') !== false
    );
    
    // Redirect to my-account page after logout
    if ($is_login_page && isset($_GET['loggedout']) && $_GET['loggedout'] == 'true' && !is_user_logged_in()) {
        $myaccount_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account');
        // Force HTTPS if site is using HTTPS
        if (is_ssl()) {
            $myaccount_url = str_replace('http://', 'https://', $myaccount_url);
        }
        wp_safe_redirect($myaccount_url);
        exit;
    }
}
// Use template_redirect with high priority to catch redirects
add_action('template_redirect', 'my_clinic_logout_redirect_handler', 1);

/**
 * Add query vars for pagination
 */
function my_clinic_add_query_vars($vars) {
    $vars[] = 'paged';
    return $vars;
}
add_filter('query_vars', 'my_clinic_add_query_vars');

/**
 * Configure SMTP for contact form emails
 */
function my_clinic_configure_smtp($phpmailer) {
    $smtp_host = get_option('smtp_host', '');
    $smtp_port = get_option('smtp_port', 587);
    $smtp_encryption = get_option('smtp_encryption', 'tls');
    $smtp_username = get_option('smtp_username', '');
    $smtp_password = get_option('smtp_password', '');
    $smtp_from_email = get_option('smtp_from_email', get_option('admin_email'));
    $smtp_from_name = get_option('smtp_from_name', get_bloginfo('name'));
    
    // Only configure if SMTP settings are provided
    if (!empty($smtp_host) && !empty($smtp_username)) {
        $phpmailer->isSMTP();
        $phpmailer->Host = $smtp_host;
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = $smtp_port;
        $phpmailer->Username = $smtp_username;
        
        // Decode password if it's base64 encoded
        if (!empty($smtp_password)) {
            $decoded_password = base64_decode($smtp_password);
            if ($decoded_password !== false) {
                $phpmailer->Password = $decoded_password;
            } else {
                $phpmailer->Password = $smtp_password;
            }
        }
        
        // Set encryption
        if ($smtp_encryption === 'ssl') {
            $phpmailer->SMTPSecure = 'ssl';
        } elseif ($smtp_encryption === 'tls') {
            $phpmailer->SMTPSecure = 'tls';
        } else {
            $phpmailer->SMTPSecure = '';
        }
        
        // Set from email and name
        $phpmailer->From = $smtp_from_email;
        $phpmailer->FromName = $smtp_from_name;
        
        // Enable debug (optional, for troubleshooting)
        // $phpmailer->SMTPDebug = 2;
    }
}
add_action('phpmailer_init', 'my_clinic_configure_smtp');

/**
 * Test function to add sample products (for testing only)
 * REMOVED - inc folder deleted
 */
