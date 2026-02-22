<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;

// Get current user
$current_user = wp_get_current_user();
$user_name = $current_user->display_name ? $current_user->display_name : $current_user->user_login;
?>

<div class="my-account-page">
    <div class="container y-u-max-w-1200">
        <div class="my-account-header">
            <h1><?php esc_html_e('حسابي', 'nafhat'); ?></h1>
        </div>

        <div class="my-account-layout">
            <aside class="my-account-sidebar">
                <div class="my-account-greeting">
                    <p class="hello"><?php esc_html_e('أهلاً', 'nafhat'); ?></p>
                    <p class="name"><?php echo esc_html($user_name); ?></p>
                </div>
                
                <?php
                /**
                 * My Account navigation.
                 */
                do_action('woocommerce_account_navigation');
                ?>
            </aside>

            <div class="woocommerce-MyAccount-content">
                <?php
                /**
                 * My Account content.
                 */
                do_action('woocommerce_account_content');
                ?>
            </div>
        </div>
    </div>
</div>
