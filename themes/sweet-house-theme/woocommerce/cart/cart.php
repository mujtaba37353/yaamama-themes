<?php
/**
 * Cart Page — Sweet House design.
 *
 * @package Sweet_House_Theme
 * @see     design: sweet-house/templates/cart/layout.html
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );
?>

<?php if ( function_exists( 'is_cart' ) && is_cart() ) : ?>
<div class="y-u-my-10 cart-page-wrap">
	<div class="main-container">
		<nav data-y="breadcrumb" class="woocommerce-breadcrumb-wrap y-breadcrumb-container" aria-label="<?php esc_attr_e( 'مسار الصفحة', 'sweet-house-theme' ); ?>">
			<ol class="y-breadcrumb">
				<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
				<li class="y-breadcrumb-item active"><?php esc_html_e( 'سلة المشتريات', 'sweet-house-theme' ); ?></li>
			</ol>
		</nav>
	</div>
	<div class="main-container cart-main-grid">
		<?php endif; ?>

<form class="woocommerce-cart-form cart-form-column" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post" data-y="cart-table">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<ul class="cart-list woocommerce-cart-form__contents" role="list">
		<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$price             = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$subtotal          = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
				$min_qty           = $_product->is_sold_individually() ? 1 : 0;
				$max_qty           = $_product->get_max_purchase_quantity();
				?>
		<li class="cart-item woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
			<div class="product-info">
				<div class="product-img">
					<?php if ( $product_permalink ) : ?>
						<a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></a>
					<?php else : ?>
						<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<?php endif; ?>
				</div>
				<div class="product-details">
					<h5 class="product-title">
						<?php
						if ( $product_permalink ) {
							echo wp_kses_post( sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $product_name ) );
						} else {
							echo wp_kses_post( $product_name );
						}
						?>
					</h5>
					<?php if ( $cart_item_data = wc_get_formatted_cart_item_data( $cart_item ) ) : ?>
						<p class="product-variation"><?php echo wp_kses_post( $cart_item_data ); ?></p>
					<?php endif; ?>
					<h4 class="y-u-d-flex y-u-align-items-center">
						<?php echo $price; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</h4>
				</div>
			</div>

			<div class="product-quantity">
				<?php
				if ( $_product->is_sold_individually() ) {
					echo wp_kses_post( sprintf( '<span class="quantity-value">%s</span>', esc_html( $cart_item['quantity'] ) ) );
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
					echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				?>
			</div>

			<div class="product-total">
				<p class="y-u-d-flex y-u-align-items-center product-subtotal">
					<span class="total-label"><?php esc_html_e( 'المجموع:', 'sweet-house-theme' ); ?></span>
					<span class="total-value"><?php echo wp_kses_post( $subtotal ); ?></span>
				</p>
			</div>
			<div class="remove">
				<?php
				echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					'woocommerce_cart_item_remove_link',
					sprintf(
						'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><i class="fa-solid fa-xmark"></i></a>',
						esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
						esc_attr( sprintf( __( 'إزالة %s من السلة', 'sweet-house-theme' ), wp_strip_all_tags( $product_name ) ) ),
						esc_attr( $product_id ),
						esc_attr( $_product->get_sku() )
					),
					$cart_item_key
				);
				?>
			</div>
		</li>
				<?php
			}
		}
		?>
	</ul>

	<?php do_action( 'woocommerce_cart_contents' ); ?>

	<?php if ( wc_coupons_enabled() ) : ?>
	<div class="cart-actions" style="margin-top: 1rem;">
		<div class="coupon">
			<label for="coupon_code" class="screen-reader-text"><?php esc_html_e( 'كود الخصم:', 'sweet-house-theme' ); ?></label>
			<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'كود الخصم', 'sweet-house-theme' ); ?>" />
			<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'تطبيق', 'sweet-house-theme' ); ?>"><?php esc_html_e( 'تطبيق', 'sweet-house-theme' ); ?></button>
		</div>
		<?php do_action( 'woocommerce_cart_actions' ); ?>
	</div>
	<?php endif; ?>
	<input type="hidden" name="update_cart" value="1" />
	<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>

	<?php do_action( 'woocommerce_after_cart_contents' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals" data-y="cart-summary">
	<?php do_action( 'woocommerce_cart_collaterals' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>

<?php if ( function_exists( 'is_cart' ) && is_cart() ) : ?>
	</div>
	<div class="main-container">
		<div class="pro">
			<?php woocommerce_cross_sell_display( 4, 4 ); ?>
		</div>
	</div>
</div>
<?php endif; ?>
