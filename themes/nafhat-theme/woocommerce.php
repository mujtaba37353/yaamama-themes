<?php
/**
 * WooCommerce Template
 * 
 * This template is used for WooCommerce pages like My Account
 *
 * @package Nafhat Theme
 */

// For single products, use the custom single-product.php template
if (is_singular('product')) {
    wc_get_template('single-product.php');
    return;
}

// For shop archive page, use the custom archive-product.php template
if (is_shop() || is_product_category() || is_product_tag()) {
    wc_get_template('archive-product.php');
    return;
}

get_header();
?>

<main id="primary" class="site-main woocommerce-page">
    <?php woocommerce_content(); ?>
</main>

<?php
get_footer();
