<?php

defined('ABSPATH') || exit;

global $product;

$related_products = wc_get_related_products($product->get_id(), 10);

if (!$related_products) {
    return;
}
?>
<section class="y-c-products-section">
    <div class="y-c-products-header">
        <h3>منتجات ذات صلة</h3>
        <a href="<?php echo esc_url(ahmadi_theme_page_url('shop')); ?>" class="y-c-view-more-btn">
            <i class="fa-solid fa-arrow-right"></i>
            انظر أكثر
        </a>
    </div>

    <ul class="y-c-products-grid">
        <?php foreach ($related_products as $related_product_id) : ?>
            <?php
            $post_object = get_post($related_product_id);
            if (!$post_object) {
                continue;
            }
            setup_postdata($post_object);
            wc_get_template_part('content', 'product');
            ?>
        <?php endforeach; ?>
    </ul>
    <?php wp_reset_postdata(); ?>
</section>
