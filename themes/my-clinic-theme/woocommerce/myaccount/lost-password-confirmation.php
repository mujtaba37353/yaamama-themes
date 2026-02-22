<?php
/**
 * Lost password confirmation
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/lost-password-confirmation.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined('ABSPATH') || exit;

// Redirect to custom lost password page (which shows success message)
if (!is_user_logged_in()) {
    $lost_password_url = home_url('/lost-password');
    wp_safe_redirect($lost_password_url);
    exit;
}
