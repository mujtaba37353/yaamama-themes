<?php
/**
 * Cart Page - Elegance
 */
defined( 'ABSPATH' ) || exit;

$assets   = ELEGANCE_ELEGANCE_URI . '/assets';
$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : home_url( '/checkout/' );

do_action( 'woocommerce_before_cart' );
?>
<main>
  <section class="panner">
    <h1 class="y-u-text-center">عربة التسوق</h1>
  </section>
  <section class="container y-u-max-w-1200 cart-section">
    <form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
      <?php do_action( 'woocommerce_before_cart_table' ); ?>
      <div class="right">
        <h2>المنتجات في السلة</h2>
        <div class="items">
          <?php
          foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
            $_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
            if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 || ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
              continue;
            }
            $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
            $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
            $price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
            ?>
            <div class="item woocommerce-cart-form__cart-item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
              <?php if ( $product_permalink ) : ?>
                <a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $thumbnail; // phpcs:ignore ?></a>
              <?php else : ?>
                <?php echo $thumbnail; // phpcs:ignore ?>
              <?php endif; ?>
              <div class="content">
                <h2><?php echo $product_permalink ? '<a href="' . esc_url( $product_permalink ) . '">' . wp_kses_post( $_product->get_name() ) . '</a>' : wp_kses_post( $_product->get_name() ); ?></h2>
                <div class="price"><?php echo $price; // phpcs:ignore ?></div>
                <?php echo wp_kses_post( wc_get_formatted_cart_item_data( $cart_item ) ); ?>
              </div>
              <div class="quantity">
                <?php
                $product_quantity = woocommerce_quantity_input(
                  array(
                    'input_name'   => "cart[{$cart_item_key}][qty]",
                    'input_value'  => $cart_item['quantity'],
                    'max_value'    => $_product->get_max_purchase_quantity(),
                    'min_value'    => $_product->get_min_purchase_quantity(),
                    'product_name' => $_product->get_name(),
                  ),
                  $_product,
                  false
                );
                echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore
                ?>
              </div>
              <a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="remove" aria-label="إزالة"><img src="<?php echo esc_url( $assets . '/trash.svg' ); ?>" alt=""></a>
            </div>
            <?php
          }
          ?>
        </div>
      </div>
      <div class="left">
        <h2>السلة</h2>
        <?php do_action( 'woocommerce_cart_collaterals' ); ?>
        <a href="<?php echo esc_url( $checkout_url ); ?>" class="y-c-btn--primary">اذهب الى الدفع</a>
      </div>
      <?php do_action( 'woocommerce_after_cart_table' ); ?>
    </form>
  </section>
</main>
<?php
do_action( 'woocommerce_after_cart' );
