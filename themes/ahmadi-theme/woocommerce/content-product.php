<?php

defined('ABSPATH') || exit;

global $product;

if (empty($product) || !$product->is_visible()) {
    return;
}
?>
<li <?php wc_product_class('y-c-product-card', $product); ?>>
    <a href="<?php the_permalink(); ?>" class="y-c-product-image">
        <?php echo ahmadi_theme_get_product_image_html($product, 'woocommerce_thumbnail'); ?>
        <span class="y-c-favorite-icon"><i class="far fa-heart"></i></span>
    </a>
    <div class="y-c-product-info">
        <h4><?php the_title(); ?></h4>
        <div class="y-c-product-price"><?php echo wp_kses_post($product->get_price_html()); ?></div>
        <?php woocommerce_template_loop_add_to_cart(); ?>
    </div>
</li>
