<?php
/**
 * Cart Page – Stationary theme override
 *
 * Outputs the custom HTML structure expected by templates/cart/cart.css:
 *   .cart-section > .top  (items card)
 *   .cart-section > .bottom (order summary)
 *
 * @package stationary-theme
 * @version 7.9.0
 */

defined( 'ABSPATH' ) || exit;

$au = function_exists( 'stationary_base_uri' ) ? stationary_base_uri() . '/assets' : '';

do_action( 'woocommerce_before_cart' );
?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<div class="top">
		<h2><?php esc_html_e( 'المنتجات', 'stationary-theme' ); ?></h2>

		<div class="header">
			<p><?php esc_html_e( 'المنتج', 'stationary-theme' ); ?></p>
			<p><?php esc_html_e( 'السعر', 'stationary-theme' ); ?></p>
			<p><?php esc_html_e( 'الكمية', 'stationary-theme' ); ?></p>
			<p><?php esc_html_e( 'المجموع', 'stationary-theme' ); ?></p>
			<p></p>
		</div>

		<div class="items">
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( ! $_product || ! $_product->exists() || $cart_item['quantity'] <= 0 ) {
					continue;
				}

				if ( ! apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					continue;
				}

				$product_permalink = apply_filters(
					'woocommerce_cart_item_permalink',
					$_product->is_visible() ? $_product->get_permalink( $cart_item ) : '',
					$cart_item,
					$cart_item_key
				);

				$product_name     = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$product_price    = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_subtotal = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );

				$image_id  = $_product->get_image_id();
				$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src( 'woocommerce_thumbnail' );
				$max_qty   = $_product->get_max_purchase_quantity();
				$item_class = esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) );
				?>

				<div class="item <?php echo $item_class; ?>" data-cart-key="<?php echo esc_attr( $cart_item_key ); ?>">

					<!-- Column 1: Product image + info -->
					<div class="img">
						<?php if ( $product_permalink ) : ?>
							<a href="<?php echo esc_url( $product_permalink ); ?>">
								<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $product_name ) ); ?>">
							</a>
						<?php else : ?>
							<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( wp_strip_all_tags( $product_name ) ); ?>">
						<?php endif; ?>

						<div class="content">
							<h2>
								<?php if ( $product_permalink ) : ?>
									<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo wp_kses_post( $product_name ); ?></a>
								<?php else : ?>
									<?php echo wp_kses_post( $product_name ); ?>
								<?php endif; ?>
							</h2>

							<?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>

							<?php if ( $_product->is_on_sale() ) : ?>
								<div class="price">
									<p><?php echo wp_kses_post( wc_price( $_product->get_price() ) ); ?></p>
									<p><?php echo wp_kses_post( wc_price( $_product->get_regular_price() ) ); ?></p>
								</div>
							<?php else : ?>
								<div class="price">
									<p><?php echo wp_kses_post( $product_price ); ?></p>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<!-- Column 2: Unit price -->
					<p>
						<span><?php esc_html_e( 'السعر', 'stationary-theme' ); ?></span>
						<?php echo wp_kses_post( $product_price ); ?>
					</p>

					<!-- Column 3: Quantity -->
					<div class="quantity">
						<button type="button" class="qty-btn qty-minus" aria-label="<?php esc_attr_e( 'تقليل', 'stationary-theme' ); ?>">
							<i class="fa-solid fa-minus"></i>
						</button>
						<input
							type="number"
							name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]"
							value="<?php echo esc_attr( $cart_item['quantity'] ); ?>"
							min="0"
							<?php echo $max_qty > 0 ? 'max="' . esc_attr( $max_qty ) . '"' : ''; ?>
							step="1"
							inputmode="numeric"
							class="qty-input qty"
							aria-label="<?php esc_attr_e( 'الكمية', 'stationary-theme' ); ?>"
						>
						<button type="button" class="qty-btn qty-plus" aria-label="<?php esc_attr_e( 'زيادة', 'stationary-theme' ); ?>">
							<i class="fa-solid fa-plus"></i>
						</button>
					</div>

					<!-- Column 4: Line subtotal -->
					<p>
						<span><?php esc_html_e( 'المجموع', 'stationary-theme' ); ?></span>
						<?php echo wp_kses_post( $product_subtotal ); ?>
					</p>

					<!-- Column 5: Remove -->
					<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>"
					   class="remove-item"
					   aria-label="<?php echo esc_attr( sprintf( __( 'إزالة %s من السلة', 'stationary-theme' ), wp_strip_all_tags( $product_name ) ) ); ?>">
						<img src="<?php echo esc_url( $au . '/trash.svg' ); ?>" alt="<?php esc_attr_e( 'حذف', 'stationary-theme' ); ?>">
					</a>

					<!-- Mobile-only layout -->
					<div class="mobile-text">
						<div class="top-mobile">
							<h2><?php echo wp_kses_post( $product_name ); ?></h2>
							<a href="<?php echo esc_url( wc_get_cart_remove_url( $cart_item_key ) ); ?>" class="remove-item">
								<img src="<?php echo esc_url( $au . '/trash.svg' ); ?>" alt="<?php esc_attr_e( 'حذف', 'stationary-theme' ); ?>">
							</a>
						</div>
						<p><?php echo wp_kses_post( $product_price ); ?> <span><?php esc_html_e( 'السعر', 'stationary-theme' ); ?></span></p>
						<p><?php echo wp_kses_post( $product_subtotal ); ?> <span><?php esc_html_e( 'المجموع', 'stationary-theme' ); ?></span></p>
					</div>
				</div>

			<?php } ?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>
		</div>
	</div>

	<button type="submit" class="btn secondary-button cart-update-btn" name="update_cart" value="<?php esc_attr_e( 'تحديث السلة', 'woocommerce' ); ?>" disabled aria-disabled="true">
		<?php esc_html_e( 'تحديث السلة', 'woocommerce' ); ?>
	</button>

	<?php do_action( 'woocommerce_cart_actions' ); ?>
	<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="bottom">
	<h2><?php esc_html_e( 'ملخص الطلب', 'stationary-theme' ); ?></h2>

	<?php if ( wc_coupons_enabled() ) : ?>
		<div class="coupon-section">
			<form class="coupon-form" method="post" action="<?php echo esc_url( wc_get_cart_url() ); ?>">
				<div class="coupon-input-wrap">
					<input type="text" name="coupon_code" id="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'كود الخصم', 'stationary-theme' ); ?>">
					<button type="submit" class="btn outline" name="apply_coupon" value="1"><?php esc_html_e( 'تطبيق', 'stationary-theme' ); ?></button>
				</div>
				<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
			</form>
		</div>
	<?php endif; ?>

	<div class="total total-card">
		<p>
			<?php esc_html_e( 'المجموع الفرعي', 'stationary-theme' ); ?>
			<span><?php echo wp_kses_post( WC()->cart->get_cart_subtotal() ); ?></span>
		</p>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<p>
				<?php echo esc_html( sprintf( __( 'خصم (%s)', 'stationary-theme' ), $code ) ); ?>
				<span><?php echo wp_kses_post( wc_cart_totals_coupon_html( $coupon, false ) ); ?></span>
			</p>
		<?php endforeach; ?>

		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
			<?php
			$packages = WC()->shipping()->get_packages();
			foreach ( $packages as $i => $package ) {
				$chosen_method = isset( WC()->session->chosen_shipping_methods[ $i ] ) ? WC()->session->chosen_shipping_methods[ $i ] : '';
				$available     = $package['rates'];
				if ( ! empty( $available ) ) {
					foreach ( $available as $method ) {
						if ( $method->id === $chosen_method ) {
							?>
							<p>
								<?php esc_html_e( 'الشحن', 'stationary-theme' ); ?>
								<span><?php echo wp_kses_post( wc_price( $method->cost ) ); ?></span>
							</p>
							<?php
							break;
						}
					}
				}
			}
			?>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<p>
				<?php echo esc_html( $fee->name ); ?>
				<span><?php echo wp_kses_post( wc_price( $fee->total ) ); ?></span>
			</p>
		<?php endforeach; ?>

		<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $tax ) : ?>
				<p>
					<?php echo esc_html( $tax->label ); ?>
					<span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
				</p>
			<?php endforeach; ?>
		<?php endif; ?>

		<p class="estimated-total">
			<?php esc_html_e( 'الإجمالي', 'stationary-theme' ); ?>
			<span><?php echo wp_kses_post( WC()->cart->get_total() ); ?></span>
		</p>
	</div>

	<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn secondary-button fw">
		<?php esc_html_e( 'إتمام الشراء', 'stationary-theme' ); ?>
	</a>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
