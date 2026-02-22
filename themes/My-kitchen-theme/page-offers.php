<?php
get_header();

$shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : home_url('/shop/');
$sale_ids = array_filter(array_map('absint', wc_get_product_ids_on_sale()));
$sale_query = null;

if ($sale_ids) {
    $paged = max(1, (int) get_query_var('paged'));
    $per_page = (int) get_option('posts_per_page');
    $sale_query = new WP_Query(
        array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'post__in' => $sale_ids,
            'orderby' => 'post__in',
            'paged' => $paged,
            'posts_per_page' => $per_page,
        )
    );
}

$has_products = $sale_query && $sale_query->have_posts();
?>

<header data-y="design-header"></header>

<?php if (!$has_products) : ?>
  <main data-y="main" class="not-found-container">
    <div class="y-main-container">
      <div data-y="breadcrumb"></div>
      <div class="not-found-content">
        <img src="<?php echo esc_url(MYK_ASSETS_URI . '/assets/empty-cart.png'); ?>" alt="لا توجد عروض" class="not-found-img" />
        <p class="not-found-text">لا توجد عروض حالياً.</p>
        <a href="<?php echo esc_url($shop_url); ?>" class="btn-back">
          لا يوجد منتجات، تصفح المنتجات <i class="fa-solid fa-cart-shopping"></i>
        </a>
      </div>
    </div>
  </main>
<?php else : ?>
  <main data-y="main">
    <div class="main-container">
      <div data-y="breadcrumb"></div>
      <section data-y="categories" data-real-categories="1">
        <?php echo mykitchen_render_product_categories(); ?>
      </section>
      <section data-y="filter"></section>
      <ul class="products" data-y="products" data-real-products="1">
        <?php while ($sale_query->have_posts()) : ?>
          <?php $sale_query->the_post(); ?>
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
        <?php echo mykitchen_render_pagination($sale_query); ?>
      </section>
    </div>
  </main>
<?php endif; ?>

<?php
wp_reset_postdata();
get_footer();
?>
