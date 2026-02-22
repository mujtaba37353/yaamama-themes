<?php
/**
 * Template Name: My Account Page
 * 
 * Custom template for WooCommerce My Account page
 *
 * @package Nafhat Theme
 */

get_header();
?>

<main id="primary" class="site-main woocommerce-account">
    <?php
    // Check if user is logged in
    if (!is_user_logged_in()) {
        // Show login form using WooCommerce shortcode
        echo do_shortcode('[woocommerce_my_account]');
    } else {
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
                        
                        <?php do_action('woocommerce_account_navigation'); ?>
                    </aside>

                    <div class="woocommerce-MyAccount-content">
                        <?php do_action('woocommerce_account_content'); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    ?>
</main>

<?php
get_footer();
