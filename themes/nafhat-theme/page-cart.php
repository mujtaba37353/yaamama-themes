<?php
/**
 * Template Name: Cart Page
 * 
 * Custom cart page template
 *
 * @package Nafhat
 * @since 1.0.0
 */

defined('ABSPATH') || exit;

get_header('shop');

// Enqueue cart styles
wp_enqueue_style(
    'nafhat-cart-style',
    get_template_directory_uri() . '/assets/css/components/cart.css',
    array('nafhat-style'),
    '1.0.0'
);

wp_enqueue_script(
    'nafhat-cart-js',
    get_template_directory_uri() . '/assets/js/cart.js',
    array('jquery'),
    '1.0.0',
    true
);
?>

<main>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="y-u-max-w-1200 y-u-w-full">
            <?php
            // Check if WooCommerce is active
            if (class_exists('WooCommerce')) {
                // Display cart
                echo do_shortcode('[woocommerce_cart]');
            } else {
                echo '<p>' . esc_html__('WooCommerce غير مفعل', 'nafhat') . '</p>';
            }
            ?>
        </div>
    </section>
</main>

<?php
get_footer('shop');
