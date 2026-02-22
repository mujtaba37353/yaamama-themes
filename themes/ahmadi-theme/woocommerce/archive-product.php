<?php

defined('ABSPATH') || exit;

if (did_action('ahmadi_shop_rendered')) {
    return;
}
do_action('ahmadi_shop_rendered');

get_header();

$total = wc_get_loop_prop('total');
$per_page = wc_get_loop_prop('per_page');
$current = max(1, (int) get_query_var('paged'));
$first = $total ? (($current - 1) * $per_page + 1) : 0;
$last = $total ? min($total, $current * $per_page) : 0;
?>

<section class="y-c-container">
    <div class="woocommerce-notices-wrapper">
        <?php wc_print_notices(); ?>
    </div>
    <h1 class="y-c-header-title"><?php woocommerce_page_title(); ?></h1>
    <div class="y-c-shopping-header">
        <div class="y-c-shopping-header-menu">
            عرض
            <span><?php echo esc_html($first); ?></span>
            -
            <span><?php echo esc_html($last); ?></span>
            من اصل
            <span><?php echo esc_html($total); ?></span>
            نتيجة
        </div>
        <div class="y-c-shopping-dropdown">
            <?php woocommerce_catalog_ordering(); ?>
        </div>
    </div>

    <?php if (woocommerce_product_loop()) : ?>
        <ul class="y-c-products-grid">
            <?php while (have_posts()) : ?>
                <?php the_post(); ?>
                <?php wc_get_template_part('content', 'product'); ?>
            <?php endwhile; ?>
        </ul>
        <div class="y-c-pagination-container">
            <div class="y-c-pagination">
                <?php woocommerce_pagination(); ?>
            </div>
        </div>
    <?php else : ?>
        <?php do_action('woocommerce_no_products_found'); ?>
    <?php endif; ?>
    <script>
        document.addEventListener('click', (event) => {
            const button = event.target.closest('.add_to_cart_button');
            if (!button) {
                return;
            }
            const wrapper = document.querySelector('.woocommerce-notices-wrapper');
            if (!wrapper) {
                return;
            }
            const card = button.closest('.y-c-product-card');
            let productName = '';
            if (card) {
                const title = card.querySelector('h4');
                if (title) {
                    productName = title.textContent.trim();
                }
            }
            const messageText = productName
                ? `تمت إضافة "${productName}" إلى السلة.`
                : 'تمت إضافة المنتج إلى السلة.';
            wrapper.innerHTML = `<div class="woocommerce-message" role="alert">${messageText}</div>`;
        });
    </script>
    </section>
<?php get_footer(); ?>
