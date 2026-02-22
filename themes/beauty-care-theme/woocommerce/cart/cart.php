<?php
/**
 * Cart Page - Beauty Care design override
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

defined( 'ABSPATH' ) || exit;

$assets_uri = get_template_directory_uri() . '/beauty-care/assets';
$shop_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

do_action( 'woocommerce_before_cart' ); ?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div class="products">
		<h3><?php esc_html_e( 'إجمالي المنتجات', 'beauty-care-theme' ); ?></h3>
		<div class="header">
			<p><?php esc_html_e( 'المنتج', 'beauty-care-theme' ); ?></p>
			<p><?php esc_html_e( 'السعر', 'beauty-care-theme' ); ?></p>
			<p><?php esc_html_e( 'الكمية', 'beauty-care-theme' ); ?></p>
			<p><?php esc_html_e( 'الإجمالي', 'beauty-care-theme' ); ?></p>
		</div>
		<div class="product-grid">
			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
				$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
				$price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$min_qty = $_product->is_sold_individually() ? 1 : 0;
					$max_qty = $_product->get_max_purchase_quantity();
					?>
					<div class="product-item woocommerce-cart-form__cart-item cart_item" data-cart-item-key="<?php echo esc_attr( $cart_item_key ); ?>">
						<div class="product">
							<?php
							$remove_url = wc_get_cart_remove_url( $cart_item_key );
							$remove_link = '<a href="' . esc_url( $remove_url ) . '" class="remove" aria-label="' . esc_attr( sprintf( __( 'إزالة %s من السلة', 'beauty-care-theme' ), wp_strip_all_tags( $product_name ) ) ) . '" data-product_id="' . esc_attr( $product_id ) . '"><img src="' . esc_url( $assets_uri . '/trash.svg' ) . '" alt=""></a>';
							echo apply_filters( 'woocommerce_cart_item_remove_link', $remove_link, $cart_item_key );
							?>
							<?php if ( $product_permalink ) : ?>
								<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $thumbnail; // phpcs:ignore ?></a>
							<?php else : ?>
								<?php echo $thumbnail; // phpcs:ignore ?>
							<?php endif; ?>
							<p><?php echo $product_permalink ? '<a href="' . esc_url( $product_permalink ) . '">' . wp_kses_post( $product_name ) . '</a>' : wp_kses_post( $product_name ); ?></p>
						</div>
						<div class="others">
							<p class="mobile"><?php echo wp_kses_post( $product_name ); ?></p>
							<div class="price"><?php echo $price; // phpcs:ignore ?></div>
							<div class="quantity">
								<?php
								if ( $_product->is_sold_individually() ) {
									echo '<span class="quantity-input">' . esc_html( $cart_item['quantity'] ) . '</span>';
								} else {
									$product_quantity = woocommerce_quantity_input(
										array(
											'input_name'   => "cart[{$cart_item_key}][qty]",
											'input_value'  => $cart_item['quantity'],
											'max_value'    => $max_qty,
											'min_value'    => $min_qty,
											'product_name' => $product_name,
										),
										$_product,
										false
									);
									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore
								}
								?>
							</div>
							<div class="total"><?php echo $subtotal; // phpcs:ignore ?></div>
						</div>
					</div>
					<?php
				}
			}
			?>
		</div>
	</div>

	<?php do_action( 'woocommerce_cart_contents' ); ?>

	<div class="cart-actions">
		<?php if ( wc_coupons_enabled() ) : ?>
			<div class="coupon">
				<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'كود القسيمة:', 'beauty-care-theme' ); ?></label>
				<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'كود القسيمة', 'beauty-care-theme' ); ?>" />
				<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'تطبيق القسيمة', 'beauty-care-theme' ); ?>"><?php esc_html_e( 'تطبيق القسيمة', 'beauty-care-theme' ); ?></button>
			</div>
		<?php endif; ?>
		<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
	</div>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
	<?php do_action( 'woocommerce_cart_collaterals' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
