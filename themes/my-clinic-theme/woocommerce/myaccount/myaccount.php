<?php
/**
 * My Account Page - WooCommerce Main Template
 * This is the template that WooCommerce uses for the myaccount page
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

// If user is not logged in, show WooCommerce login form
if (!is_user_logged_in()) {
    // Enqueue auth styles
    $theme_version = wp_get_theme()->get('Version');
    $theme_uri = get_template_directory_uri();
    wp_enqueue_style('my-clinic-auth', $theme_uri . '/assets/css/components/auth.css', array(
        'my-clinic-header',
        'my-clinic-footer',
        'my-clinic-buttons'
    ), $theme_version);
    
    // Add body class to prevent scrolling on mobile
    add_filter('body_class', function($classes) {
        $classes[] = 'my-account-page';
        $classes[] = 'woocommerce-account';
        return $classes;
    });
    
    // Add html class via JavaScript to prevent scrolling on mobile
    add_action('wp_footer', function() {
        echo '<script>
        (function() {
            if (window.innerWidth <= 768) {
                document.documentElement.classList.add("my-account-page", "woocommerce-account");
            }
        })();
        </script>';
    }, 999);
    
    get_header();
    
    // Check if we need to show registration or lost password form
    $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    
    // Check for lost password or reset password endpoints
    if (strpos($request_uri, 'lost-password') !== false || $action === 'lostpassword') {
        // Load lost password form
        wc_get_template('myaccount/form-lost-password.php');
    } elseif (strpos($request_uri, 'reset-password') !== false || $action === 'rp') {
        // Load reset password form
        $key = isset($_GET['key']) ? sanitize_text_field($_GET['key']) : '';
        $login = isset($_GET['login']) ? sanitize_text_field($_GET['login']) : '';
        wc_get_template('myaccount/form-reset-password.php', array('key' => $key, 'login' => $login));
    } elseif ($action === 'register') {
        // Load registration form
        wc_get_template('myaccount/form-register.php');
    } else {
        // Load login form (default)
        wc_get_template('myaccount/form-login.php');
    }
    
    get_footer();
    return;
}

// User is logged in - load the custom my-account template
$my_account_template = locate_template('woocommerce/myaccount/my-account.php');
if ($my_account_template) {
    include $my_account_template;
} else {
    // Fallback to WooCommerce default
    wc_get_template('myaccount/dashboard.php');
}
