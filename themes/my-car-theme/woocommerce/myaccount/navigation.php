<?php
/**
 * My Account navigation - Custom Template
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
?>

<div class="y-l-myaccount-dashboard">
    <div class="y-l-container">
        
        <!-- User Header -->
        <div class="y-c-user-header">
            <div class="y-c-user-avatar">
                <?php echo get_avatar($current_user->ID, 100); ?>
            </div>
            <div class="y-c-user-info">
                <h1 class="y-c-user-name">مرحباً، <?php echo esc_html($current_user->display_name); ?></h1>
                <p class="y-c-user-email"><?php echo esc_html($current_user->user_email); ?></p>
            </div>
        </div>

        <div class="y-l-myaccount-wrapper">
            
            <!-- Navigation Sidebar -->
            <nav class="woocommerce-MyAccount-navigation y-c-myaccount-nav">
                <ul class="y-c-nav-list">
                    <?php foreach (wc_get_account_menu_items() as $endpoint => $label) : ?>
                        <li class="<?php echo wc_get_account_menu_item_classes($endpoint); ?> y-c-nav-item">
                            <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" class="y-c-nav-link">
                                <span class="y-c-nav-icon">
                                    <?php
                                    // Custom icons for each menu item
                                    switch ($endpoint) {
                                        case 'orders':
                                            echo '<i class="fa-solid fa-box"></i>';
                                            break;
                                        case 'edit-address':
                                            echo '<i class="fa-solid fa-location-dot"></i>';
                                            break;
                                        case 'edit-account':
                                            echo '<i class="fa-solid fa-user-pen"></i>';
                                            break;
                                        case 'customer-logout':
                                            echo '<i class="fa-solid fa-right-from-bracket"></i>';
                                            break;
                                        default:
                                            echo '<i class="fa-solid fa-circle"></i>';
                                    }
                                    ?>
                                </span>
                                <span class="y-c-nav-text"><?php echo esc_html($label); ?></span>
                                <?php if ($endpoint !== 'customer-logout') : ?>
                                    <span class="y-c-nav-arrow"><i class="fa-solid fa-chevron-left"></i></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
