<?php
/**
 * WooCommerce Product Card - Elegance
 */
defined( 'ABSPATH' ) || exit;

global $product;

$assets = ELEGANCE_ELEGANCE_URI . '/assets';
$product_id = $product->get_id();
$is_purchasable = $product->is_purchasable() && $product->is_in_stock();
$is_simple = $product->is_type( 'simple' );
$add_via_form = $is_purchasable && $is_simple;
?>
<li class="card" data-product-id="<?php echo esc_attr( $product_id ); ?>">
  <div class="img product-card-media">
    <?php woocommerce_template_loop_product_link_open(); ?>
    <?php echo $product->get_image( 'woocommerce_thumbnail' ); ?>
    <?php woocommerce_template_loop_product_link_close(); ?>
    <button type="button" class="product-card-action product-card-favorite" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="أضف إلى المفضلة">
      <i class="fa-regular fa-heart"></i>
    </button>
    <div class="product-card-action product-card-cart">
      <?php if ( $add_via_form ) : ?>
        <form class="elegance-add-to-cart-form" method="get" action="">
          <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product_id ); ?>">
          <button type="submit" class="elegance-add-to-cart-btn add_to_cart_button" data-product_id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'إضافة إلى السلة', 'elegance' ); ?>">
            <img src="<?php echo esc_url( $assets . '/add-to-cart.svg' ); ?>" alt="<?php esc_attr_e( 'إضافة إلى السلة', 'elegance' ); ?>">
          </button>
        </form>
      <?php else : ?>
        <a href="<?php echo esc_url( $is_purchasable ? $product->add_to_cart_url() : get_permalink( $product_id ) ); ?>" class="button"><?php echo esc_html( $product->add_to_cart_text() ); ?></a>
        <img src="<?php echo esc_url( $assets . '/add-to-cart.svg' ); ?>" alt="" aria-hidden="true">
      <?php endif; ?>
    </div>
  </div>
  <div class="content">
    <?php woocommerce_template_loop_product_link_open(); ?>
    <p><?php the_title(); ?></p>
    <?php woocommerce_template_loop_product_link_close(); ?>
    <div class="left">
      <?php woocommerce_template_loop_price(); ?>
    </div>
  </div>
</li>
