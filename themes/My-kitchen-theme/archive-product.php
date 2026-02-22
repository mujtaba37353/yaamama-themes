<?php
get_header();
global $wp_query;

$product_query = $wp_query;
$category_query = null;

if (is_product_category()) {
    $term = get_queried_object();
    $term_id = $term && !is_wp_error($term) ? absint($term->term_id) : 0;
    if ($term_id) {
        $paged = max(1, (int) get_query_var('paged'));
        $per_page = (int) get_option('posts_per_page');
        $category_query = new WP_Query(
            array(
                'post_type' => 'product',
                'post_status' => 'publish',
                'paged' => $paged,
                'posts_per_page' => $per_page,
                'tax_query' => array(
                    array(
                        'taxonomy' => 'product_cat',
                        'field' => 'term_id',
                        'terms' => array($term_id),
                    ),
                ),
            )
        );
    }
}

if ($category_query) {
    $product_query = $category_query;
}
$has_products = $product_query && $product_query->have_posts();
?>

<header data-y="design-header"></header>

<main data-y="main" class="archive-products">
  <div class="main-container">
    <div data-y="breadcrumb"></div>
    <section data-y="categories" data-real-categories="1">
      <?php echo mykitchen_render_product_categories(); ?>
    </section>
    <section data-y="filter"></section>
    <?php if ($has_products) : ?>
      <ul class="products" data-y="products" data-real-products="1">
        <?php while ($product_query->have_posts()) : ?>
          <?php $product_query->the_post(); ?>
          <?php
          $product = wc_get_product(get_the_ID());
          if (!$product) {
              continue;
          }
          ?>
          <?php echo mykitchen_render_product_card($product); ?>
        <?php endwhile; ?>
      </ul>
      <section data-y="pagination" data-real-pagination="1">
        <?php echo mykitchen_render_pagination($product_query); ?>
      </section>
    <?php else : ?>
      <p>لا توجد منتجات حالياً.</p>
    <?php endif; ?>
  </div>
</main>

<?php
wp_reset_postdata();
get_footer();
?>
