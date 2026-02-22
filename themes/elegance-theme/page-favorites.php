<?php
/**
 * Template Name: المفضلة (Favorites)
 * Elegance - Favorites page (IDs from GET or empty state)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

elegance_enqueue_component_css( array( 'panner', 'empty-state' ) );
elegance_enqueue_page_css( 'store' );
elegance_enqueue_component_css( array( 'products' ) );

$shop_url = function_exists( 'elegance_shop_url' ) ? elegance_shop_url() : home_url( '/shop/' );
$ids_raw  = isset( $_GET['ids'] ) ? sanitize_text_field( wp_unslash( $_GET['ids'] ) ) : '';
$ids      = array_filter( array_map( 'absint', explode( ',', $ids_raw ) ) );
$favorite_products = array();

if ( ! empty( $ids ) ) {
  foreach ( $ids as $product_id ) {
    $item = wc_get_product( $product_id );
    if ( $item && $item->is_visible() ) {
      $favorite_products[] = $item;
    }
  }
}

get_header();
?>
<main>
  <section class="panner">
    <h1 class="y-u-text-center">المفضلة</h1>
  </section>
  <section class="container y-u-max-w-1200 store-section">
    <?php if ( ! empty( $favorite_products ) ) : ?>
      <ul class="grid products" id="elegance-favorites-list">
        <?php
        foreach ( $favorite_products as $favorite_product ) {
          $product_id      = $favorite_product->get_id();
          $GLOBALS['post'] = get_post( $product_id );
          global $product;
          $product = $favorite_product;
          wc_get_template_part( 'content', 'product' );
        }
        wp_reset_postdata();
        ?>
      </ul>
    <?php else : ?>
      <div class="empty-state-container">
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fas fa-heart-broken"></i>
          </div>
          <h2>لم تقم بإضافة منتجات إلى المفضلة بعد</h2>
          <a href="<?php echo esc_url( $shop_url ); ?>" class="btn main-button">استكشف المنتجات</a>
        </div>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php
get_footer();
