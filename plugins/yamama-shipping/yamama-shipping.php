<?php
/**
 * Plugin Name: Yamama Shipping
 * Plugin URI: https://yamama.local
 * Description: WooCommerce shipping integration with Lamha via Yamama middleware. Includes order creation, carrier selection, Moyasar payment, and webhook status tracking.
 * Version: 1.0.0
 * Author: Yamama
 * Text Domain: yamama-shipping
 */

if (!defined('ABSPATH')) {
    exit;
}

define('YAMAMA_SHIPPING_VERSION', '1.0.0');
define('YAMAMA_SHIPPING_FILE', __FILE__);
define('YAMAMA_SHIPPING_DIR', plugin_dir_path(__FILE__));
define('YAMAMA_SHIPPING_URL', plugin_dir_url(__FILE__));

require_once YAMAMA_SHIPPING_DIR . 'includes/class-yamama-shipping-plugin.php';

add_action('plugins_loaded', static function () {
    if (!class_exists('WooCommerce')) {
        return;
    }

    Yamama_Shipping_Plugin::boot();
});
