<?php get_header(); ?>

<header data-y="design-header"></header>

<?php
$wishlist_ids = function_exists('mykitchen_get_wishlist_ids')
    ? mykitchen_get_wishlist_ids()
    : array();
$wishlist_products = array();
if ($wishlist_ids) {
    $wishlist_products = wc_get_products(
        array(
            'include' => $wishlist_ids,
            'orderby' => 'include',
            'status' => 'publish',
            'limit' => -1,
        )
    );
}
?>

<?php if (!empty($wishlist_products)) : ?>
  <main data-y="main">
    <div class="main-container">
      <div data-y="breadcrumb"></div>
      <section data-y="top-products-logo"></section>
      <section data-y="filter"></section>
      <ul class="products" data-y="wishlist-products" data-real-products="1">
        <?php foreach ($wishlist_products as $product) : ?>
          <?php echo mykitchen_render_product_card($product, array('wishlist_ids' => $wishlist_ids)); ?>
        <?php endforeach; ?>
      </ul>
    </div>
  </main>
<?php else : ?>
  <main data-y="main" class="not-found-container">
    <div class="y-main-container">
      <div data-y="breadcrumb"></div>
      <div class="not-found-content">
        <img src="<?php echo esc_url(MYK_ASSETS_URI . '/assets/empty-cart.png'); ?>" alt="لا توجد منتجات مفضلة" class="not-found-img" />
        <p class="not-found-text">لا توجد منتجات مفضلة حالياً.</p>
        <a href="<?php echo esc_url(home_url('/shop/')); ?>" class="btn-back">
          لا يوجد منتجات، تصفح المنتجات <i class="fa-solid fa-cart-shopping"></i>
        </a>
      </div>
    </div>
  </main>
<?php endif; ?>

<?php get_footer(); ?>
