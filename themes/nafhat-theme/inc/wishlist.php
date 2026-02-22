<?php
/**
 * Wishlist System
 * 
 * @package Nafhat
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create wishlist table on theme activation
 */
function nafhat_create_wishlist_table() {
    global $wpdb;
    
    $table_name = $wpdb->prefix . 'nafhat_wishlist';
    $charset_collate = $wpdb->get_charset_collate();
    
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        user_id bigint(20) NOT NULL,
        product_id bigint(20) NOT NULL,
        date_added datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY user_product (user_id, product_id),
        KEY user_id (user_id),
        KEY product_id (product_id)
    ) $charset_collate;";
    
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
add_action('after_switch_theme', 'nafhat_create_wishlist_table');

// Also run on init to ensure table exists
function nafhat_ensure_wishlist_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'nafhat_wishlist';
    
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        nafhat_create_wishlist_table();
    }
}
add_action('init', 'nafhat_ensure_wishlist_table');

/**
 * Add product to wishlist
 */
function nafhat_add_to_wishlist($user_id, $product_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'nafhat_wishlist';
    
    $result = $wpdb->insert(
        $table_name,
        array(
            'user_id' => $user_id,
            'product_id' => $product_id,
            'date_added' => current_time('mysql')
        ),
        array('%d', '%d', '%s')
    );
    
    return $result !== false;
}

/**
 * Remove product from wishlist
 */
function nafhat_remove_from_wishlist($user_id, $product_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'nafhat_wishlist';
    
    $result = $wpdb->delete(
        $table_name,
        array(
            'user_id' => $user_id,
            'product_id' => $product_id
        ),
        array('%d', '%d')
    );
    
    return $result !== false;
}

/**
 * Check if product is in wishlist
 */
function nafhat_is_in_wishlist($user_id, $product_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'nafhat_wishlist';
    
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE user_id = %d AND product_id = %d",
        $user_id,
        $product_id
    ));
    
    return $count > 0;
}

/**
 * Get user's wishlist products
 */
function nafhat_get_wishlist($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'nafhat_wishlist';
    
    $product_ids = $wpdb->get_col($wpdb->prepare(
        "SELECT product_id FROM $table_name WHERE user_id = %d ORDER BY date_added DESC",
        $user_id
    ));
    
    return $product_ids;
}

/**
 * Get wishlist count for user
 */
function nafhat_get_wishlist_count($user_id) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'nafhat_wishlist';
    
    $count = $wpdb->get_var($wpdb->prepare(
        "SELECT COUNT(*) FROM $table_name WHERE user_id = %d",
        $user_id
    ));
    
    return (int) $count;
}

/**
 * AJAX: Toggle wishlist
 */
function nafhat_ajax_toggle_wishlist() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'nafhat-nonce')) {
        wp_send_json_error(array('message' => __('خطأ في التحقق', 'nafhat')));
    }
    
    // Check if user is logged in
    if (!is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => __('يجب تسجيل الدخول أولاً', 'nafhat'),
            'login_required' => true,
            'login_url' => wc_get_page_permalink('myaccount')
        ));
    }
    
    $user_id = get_current_user_id();
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    if (!$product_id) {
        wp_send_json_error(array('message' => __('منتج غير صالح', 'nafhat')));
    }
    
    // Check if product exists
    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(array('message' => __('المنتج غير موجود', 'nafhat')));
    }
    
    // Toggle wishlist
    if (nafhat_is_in_wishlist($user_id, $product_id)) {
        nafhat_remove_from_wishlist($user_id, $product_id);
        wp_send_json_success(array(
            'action' => 'removed',
            'message' => __('تمت الإزالة من المفضلة', 'nafhat'),
            'count' => nafhat_get_wishlist_count($user_id)
        ));
    } else {
        nafhat_add_to_wishlist($user_id, $product_id);
        wp_send_json_success(array(
            'action' => 'added',
            'message' => __('تمت الإضافة إلى المفضلة', 'nafhat'),
            'count' => nafhat_get_wishlist_count($user_id)
        ));
    }
}
add_action('wp_ajax_nafhat_toggle_wishlist', 'nafhat_ajax_toggle_wishlist');
add_action('wp_ajax_nopriv_nafhat_toggle_wishlist', 'nafhat_ajax_toggle_wishlist');

/**
 * AJAX: Remove from wishlist (for wishlist page)
 */
function nafhat_ajax_remove_from_wishlist() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'nafhat-nonce')) {
        wp_send_json_error(array('message' => __('خطأ في التحقق', 'nafhat')));
    }
    
    if (!is_user_logged_in()) {
        wp_send_json_error(array('message' => __('يجب تسجيل الدخول', 'nafhat')));
    }
    
    $user_id = get_current_user_id();
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    
    if (!$product_id) {
        wp_send_json_error(array('message' => __('منتج غير صالح', 'nafhat')));
    }
    
    nafhat_remove_from_wishlist($user_id, $product_id);
    
    wp_send_json_success(array(
        'message' => __('تمت الإزالة من المفضلة', 'nafhat'),
        'count' => nafhat_get_wishlist_count($user_id)
    ));
}
add_action('wp_ajax_nafhat_remove_from_wishlist', 'nafhat_ajax_remove_from_wishlist');

/**
 * Create wishlist page on theme activation
 */
function nafhat_create_wishlist_page() {
    $page_exists = get_page_by_path('wishlist');
    
    if (!$page_exists) {
        $page_id = wp_insert_post(array(
            'post_title'     => __('المفضلة', 'nafhat'),
            'post_name'      => 'wishlist',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'post_content'   => '',
            'comment_status' => 'closed'
        ));
        
        if ($page_id) {
            update_post_meta($page_id, '_wp_page_template', 'page-wishlist.php');
        }
    }
}
add_action('after_switch_theme', 'nafhat_create_wishlist_page');

/**
 * Enqueue wishlist scripts
 */
function nafhat_enqueue_wishlist_scripts() {
    if (class_exists('WooCommerce')) {
        wp_enqueue_script(
            'nafhat-wishlist',
            get_template_directory_uri() . '/assets/js/wishlist.js',
            array('jquery'),
            '1.0.0',
            true
        );
        
        wp_localize_script('nafhat-wishlist', 'nafhatWishlist', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('nafhat-nonce'),
            'isLoggedIn' => is_user_logged_in(),
            'loginUrl' => wc_get_page_permalink('myaccount'),
            'strings' => array(
                'added' => __('تمت الإضافة إلى المفضلة', 'nafhat'),
                'removed' => __('تمت الإزالة من المفضلة', 'nafhat'),
                'loginRequired' => __('يجب تسجيل الدخول أولاً', 'nafhat'),
                'error' => __('حدث خطأ، حاول مرة أخرى', 'nafhat')
            )
        ));
    }
}
add_action('wp_enqueue_scripts', 'nafhat_enqueue_wishlist_scripts');

/**
 * Get wishlist page URL
 */
function nafhat_get_wishlist_url() {
    $page = get_page_by_path('wishlist');
    if ($page) {
        return get_permalink($page->ID);
    }
    return home_url('/wishlist/');
}
