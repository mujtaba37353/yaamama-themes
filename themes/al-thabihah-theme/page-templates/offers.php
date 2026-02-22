<?php
/*
Template Name: Offers
*/
get_header();

$paged = max(1, get_query_var('paged'));
$on_sale_ids = wc_get_product_ids_on_sale();
$offers_query = new WP_Query(
    array(
        'post_type' => 'product',
        'posts_per_page' => 12,
        'post_status' => 'publish',
        'post__in' => $on_sale_ids,
        'paged' => $paged,
    )
);
?>

<main data-y="offers-main">
    <div class="y-u-container">

        <nav class="y-c-breadcrumbs" aria-label="breadcrumb" data-y="breadcrumbs">
            <p>
                <a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a>
                <span>></span>
                العروض
            </p>
        </nav>

        <h1 class="y-c-section-title" data-y="page-header-title"><?php the_title(); ?></h1>

        <ul class="y-l-products-grid" id="products-container" data-y="products-grid">
            <?php if ($offers_query->have_posts()) : ?>
                <?php while ($offers_query->have_posts()) : $offers_query->the_post(); ?>
                    <?php
                    $product = wc_get_product(get_the_ID());
                    al_thabihah_render_product_card($product);
                    ?>
                <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            <?php else : ?>
                <li style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem;">
                    <i class="fas fa-tag" style="font-size: 4rem; color: var(--y-color-error); margin-bottom: 1rem;"></i>
                    <h3 style="margin-bottom: 0.5rem; font-size: 1.5rem;">لا توجد منتجات حالياً</h3>
                    <p style="color: var(--y-color-third-text);">تابعنا للحصول على أحدث المنتجات</p>
                </li>
            <?php endif; ?>
        </ul>

        <?php
        $next_link = get_next_posts_page_link($offers_query->max_num_pages);
        if ($next_link) :
        ?>
            <div class="y-c-show-more-container" id="show-more-container" data-y="show-more-container">
                <a class="y-c-outline-btn y-c-show-more-btn y-c-basic-btn" id="show-more-btn" data-y="show-more-btn" href="<?php echo esc_url($next_link); ?>">
                    <span data-y="show-more-text">عرض المزيد</span>
                    <i class="fas fa-arrow-left" data-y="show-more-arrow"></i>
                </a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
