<?php
/**
 * The Template for displaying all single products
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

get_header();
?>

<main data-y="store-main">
    <div class="y-u-container">
    <?php
    while (have_posts()) {
        the_post();
        global $product;
        
        // Ensure product object is set
        if (!$product) {
            $product = wc_get_product(get_the_ID());
        }
        
        // Include the custom template directly
        $template_path = get_template_directory() . '/woocommerce/single-product/content-single-product.php';
        if (file_exists($template_path)) {
            include $template_path;
        } else {
            // Fallback to WooCommerce default
            wc_get_template_part('content', 'single-product');
        }
    }
    ?>
    </div>
</main>

<?php
get_footer();
?>
