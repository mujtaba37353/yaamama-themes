<?php
/**
 * My Account navigation
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;

// Remove dashboard and downloads from menu items
$menu_items = wc_get_account_menu_items();

// Remove unwanted items
unset($menu_items['dashboard']);
unset($menu_items['downloads']);

// Define icons for each menu item
$menu_icons = array(
    'orders'          => 'fa-shopping-cart',
    'edit-address'    => 'fa-map-marker-alt',
    'edit-account'    => 'fa-user-circle',
    'customer-logout' => 'fa-sign-out-alt',
);

// Define labels in Arabic
$menu_labels = array(
    'orders'          => 'طلباتي',
    'edit-address'    => 'عنواني',
    'edit-account'    => 'حسابي',
    'customer-logout' => 'تسجيل الخروج',
);

?>

<nav class="woocommerce-MyAccount-navigation">
    <ul>
        <?php foreach ($menu_items as $endpoint => $label) : ?>
            <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?>">
                <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>">
                    <span class="nav-icon">
                        <i class="fas <?php echo isset($menu_icons[$endpoint]) ? esc_attr($menu_icons[$endpoint]) : 'fa-circle'; ?>"></i>
                    </span>
                    <span><?php echo isset($menu_labels[$endpoint]) ? esc_html($menu_labels[$endpoint]) : esc_html($label); ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
