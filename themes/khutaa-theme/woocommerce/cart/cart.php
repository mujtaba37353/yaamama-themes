<?php
/**
 * Cart Page
 *
 * @package KhutaaTheme
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); ?>

<div class="cart-main-wrapper">
	<?php
	if ( WC()->cart->is_empty() ) {
		wc_get_template( 'cart/cart-empty.php' );
		do_action( 'woocommerce_after_cart' );
		return;
	}
	?>

	<form class="woocommerce-cart-form cart-table-wrapper" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
		<?php do_action( 'woocommerce_before_cart_table' ); ?>

		<div class="cart-table">
			<table>
				<thead>
					<tr>
						<th><?php esc_html_e( 'المنتج', 'khutaa-theme' ); ?></th>
						<th><?php esc_html_e( 'السعر', 'khutaa-theme' ); ?></th>
						<th><?php esc_html_e( 'الكمية', 'khutaa-theme' ); ?></th>
						<th><?php esc_html_e( 'المجموع', 'khutaa-theme' ); ?></th>
						<th></th>
					</tr>
				</thead>

				<tbody>
					<?php
					do_action( 'woocommerce_before_cart_contents' );

					foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
							$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
							?>
							<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
								<td class="product-info">
									<div class="product-img">
										<?php
										$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
										if ( ! $product_permalink ) {
											echo $thumbnail; // PHPCS: XSS ok.
										} else {
											printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
										}
										?>
									</div>
									<div class="product-details">
										<h5 class="product-title">
											<?php
											if ( ! $product_permalink ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
											} else {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
											}
											echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.
											?>
										</h5>
									</div>
								</td>

								<td class="product-price" data-title="<?php esc_attr_e( 'السعر', 'khutaa-theme' ); ?>">
									<?php
									echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</td>

								<td data-title="<?php esc_attr_e( 'الكمية', 'khutaa-theme' ); ?>">
									<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input(
											array(
												'input_name'   => "cart[{$cart_item_key}][qty]",
												'input_value'  => $cart_item['quantity'],
												'max_value'    => $_product->get_max_purchase_quantity(),
												'min_value'    => '0',
												'product_name' => $_product->get_name(),
											),
											$_product,
											false
										);
									}
									// Use custom quantity selector
									$qty = $cart_item['quantity'];
									?>
									<div class="qnt" data-min="0" data-max="<?php echo esc_attr( $_product->get_max_purchase_quantity() ? $_product->get_max_purchase_quantity() : 9999 ); ?>">
										<button type="button" class="qnt-minus" aria-label="<?php esc_attr_e( 'إنقاص الكمية', 'khutaa-theme' ); ?>">-</button>
										<span class="qnt-value"><?php echo esc_html( $qty ); ?></span>
										<button type="button" class="qnt-plus" aria-label="<?php esc_attr_e( 'زيادة الكمية', 'khutaa-theme' ); ?>">+</button>
										<input type="hidden" name="cart[<?php echo esc_attr( $cart_item_key ); ?>][qty]" class="qnt-input" value="<?php echo esc_attr( $qty ); ?>" />
									</div>
								</td>

								<td class="product-total" data-title="<?php esc_attr_e( 'المجموع', 'khutaa-theme' ); ?>">
									<?php
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
									?>
								</td>

								<td class="product-remove-cell">
									<?php
									echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
										'woocommerce_cart_item_remove_link',
										sprintf(
											'<a href="%s" class="product-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">×</a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_attr( sprintf( __( 'إزالة %s من السلة', 'khutaa-theme' ), wp_strip_all_tags( $_product->get_name() ) ) ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										),
										$cart_item_key
									);
									?>
								</td>
							</tr>
							<?php
						}
					}

					do_action( 'woocommerce_cart_contents' );
					?>
				</tbody>
			</table>

			<?php if ( wc_coupons_enabled() ) { ?>
				<div class="cart-coupon">
					<label for="coupon_code"><?php esc_html_e( 'كود الخصم:', 'khutaa-theme' ); ?></label>
					<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'كود الخصم', 'khutaa-theme' ); ?>" />
					<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'تطبيق', 'khutaa-theme' ); ?>"><?php esc_html_e( 'تطبيق', 'khutaa-theme' ); ?></button>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>
				</div>
			<?php } ?>

			<button type="submit" class="button update-cart-btn" name="update_cart" value="<?php esc_attr_e( 'تحديث السلة', 'khutaa-theme' ); ?>" style="display: none;"><?php esc_html_e( 'تحديث السلة', 'khutaa-theme' ); ?></button>

			<?php do_action( 'woocommerce_cart_actions' ); ?>

			<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
		</div>

		<?php do_action( 'woocommerce_after_cart_table' ); ?>
	</form>

	<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

	<div class="cart-summary-wrapper">
		<div class="cart-summary">
			<p><?php esc_html_e( 'إجمالي سلة المشتريات', 'khutaa-theme' ); ?></p>
			<hr />
			<?php
			wc_get_template( 'cart/cart-totals.php' );
			?>
		</div>
	</div>

	<?php do_action( 'woocommerce_after_cart' ); ?>
</div>

<style>
/* WooCommerce Notices - Center and Arabic */
.woocommerce-notices-wrapper {
	display: flex;
	justify-content: center;
	align-items: center;
	width: 100%;
	padding: 1rem 5vw;
	margin-bottom: 2rem;
}

.woocommerce-notices-wrapper .woocommerce-message {
	text-align: center;
	direction: rtl;
	margin: 0 auto;
}

.woocommerce-notices-wrapper .woocommerce-message a.restore-item {
	display: inline-block;
	margin-right: 0.5rem;
}

/* Cart Page Styles */
.cart-main-wrapper {
	display: grid;
	grid-template-columns: 2fr 1fr;
	gap: 32px;
	padding: 0;
	max-width: 100%;
	width: 100%;
	box-sizing: border-box;
	overflow-x: hidden;
}

.cart-table-wrapper {
	width: 100%;
	overflow-x: hidden;
}

.cart-table {
	width: 100%;
	max-width: 800px;
	overflow-x: auto;
}

.cart-table table {
	width: 100%;
	border-collapse: collapse;
}

.cart-table th {
	text-align: center;
	padding: 1rem;
	font-size: 1.2rem;
	font-weight: 700;
	color: #3a2c1c;
	border-bottom: 1px solid #b18155;
}

.cart-table td {
	padding: 1.5rem 0;
	vertical-align: middle;
	border-bottom: 1px solid #b18155;
	text-align: center;
	font-size: 1.1rem;
	font-weight: 600;
	color: #3a2c1c;
}

.product-info {
	display: flex;
	align-items: center;
	gap: 1rem;
	justify-content: flex-start;
	text-align: right;
}

.product-info .product-details {
	display: flex;
	flex-direction: column;
	gap: 0.5rem;
}

.product-title {
	font-size: 1.1rem;
	margin: 0;
}

.product-img img {
	width: 60px;
	height: 60px;
	object-fit: cover;
	border-radius: 8px;
}

.cart-table .qnt {
	display: inline-flex;
	align-items: center;
	justify-content: space-between;
	border: 1px solid #b18155;
	border-radius: 20px;
	padding: 0.3rem 0.8rem;
	gap: 1rem;
	width: 100px;
}

.cart-table .qnt button {
	background: transparent;
	border: none;
	font-size: 1.2rem;
	font-weight: bold;
	cursor: pointer;
	color: #3a2c1c;
	padding: 0;
	display: flex;
	align-items: center;
	justify-content: center;
}

.cart-table .qnt span {
	font-size: 1.1rem;
	font-weight: 600;
}

.product-remove-cell {
	width: 40px;
}

.product-remove {
	cursor: pointer;
	width: 30px;
	height: 30px;
	border: 1px solid #000;
	border-radius: 50%;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	font-size: 1rem;
	font-weight: normal;
	transition: all 0.3s ease;
	color: #000;
	font-style: normal;
	text-decoration: none;
}

.product-remove:hover {
	background-color: #000;
	color: #fff;
}

.cart-coupon {
	display: flex;
	gap: 0.5rem;
	margin: 1rem 0;
	align-items: center;
	flex-wrap: wrap;
	width: 100%;
	max-width: 100%;
	box-sizing: border-box;
}

.cart-coupon label {
	flex: 0 0 auto;
	min-width: fit-content;
	box-sizing: border-box;
}

.cart-coupon input {
	padding: 0.5rem 1rem;
	border: 1px solid #ccc;
	border-radius: 4px;
	flex: 1;
	min-width: 0;
	max-width: 100%;
	box-sizing: border-box;
}

.cart-coupon button {
	flex: 0 0 auto;
	white-space: nowrap;
	box-sizing: border-box;
}


.cart-summary-wrapper {
	width: 100%;
}

.cart-summary {
	width: 100%;
	max-width: 400px;
	border: 1px solid #b18155;
	border-radius: 16px;
	padding: 2rem;
	height: fit-content;
}

.cart-summary p {
	margin: 0;
	font-size: 1.1rem;
	font-weight: 600;
	color: #3a2c1c;
}

.cart-summary > p:first-child {
	font-size: 1.3rem;
	font-weight: 700;
	margin-bottom: 1rem;
	text-align: center;
}

.cart-summary hr {
	border: none;
	border-top: 1px solid #a8a8a8;
	margin: 1.5rem 0;
	opacity: 0.5;
}

.cart-summary div {
	display: flex;
	justify-content: space-between;
	align-items: center;
	margin-bottom: 1rem;
}

.cart-summary div:last-of-type {
	margin-bottom: 0;
}

.cart-summary .shop_table {
	width: 100%;
	border-collapse: collapse;
}

.cart-summary .shop_table th,
.cart-summary .shop_table td {
	padding: 0.75rem 0;
	text-align: right;
	font-size: 1.1rem;
	font-weight: 600;
	color: #3a2c1c;
}

.cart-summary .shop_table th {
	font-weight: 600;
}

.cart-summary .btn-auth,
.cart-summary .checkout-button {
	width: 100%;
	text-align: center;
	background-color: #ac5300;
	color: #fff;
	padding: 1rem;
	border-radius: 8px;
	font-size: 1.1rem;
	font-weight: 700;
	text-decoration: none;
	display: block;
	transition: background-color 0.3s ease;
	margin-top: 1rem;
	border: none;
	cursor: pointer;
}

.cart-summary .btn-auth:hover,
.cart-summary .checkout-button:hover {
	background-color: #b18155;
}

@media (max-width: 992px) {
	.cart-main-wrapper {
		grid-template-columns: 1fr;
		gap: 24px;
		padding: 2rem 3vw;
	}

	.cart-summary-wrapper {
		order: -1;
	}
}

@media (max-width: 768px) {
	.cart-main-wrapper {
		padding: 1.5rem 1rem;
		gap: 20px;
		width: 100%;
		max-width: 100vw;
		box-sizing: border-box;
		overflow-x: hidden;
	}

	.cart-table-wrapper {
		width: 100%;
		overflow-x: auto;
		-webkit-overflow-scrolling: touch;
	}

	.cart-table {
		font-size: 0.9rem;
		min-width: 600px;
		width: 100%;
		max-width: 100%;
		box-sizing: border-box;
	}

	.cart-table table {
		min-width: 600px;
		width: auto;
	}

	/* Ensure coupon form doesn't overflow on mobile */
	.cart-table-wrapper {
		position: relative;
		overflow-x: auto;
	}
	
	/* Force cart-coupon to use container width, not table min-width */
	.cart-table > .cart-coupon {
		display: block !important;
		position: relative;
		min-width: auto !important;
		width: calc(100vw - 3rem) !important;
		max-width: calc(100vw - 3rem) !important;
		margin-right: -1rem !important;
		margin-left: -1rem !important;
		box-sizing: border-box;
		padding-right: 1rem !important;
		padding-left: 1rem !important;
	}

	.cart-table th,
	.cart-table td {
		padding: 0.75rem 0.5rem;
		font-size: 0.9rem;
		white-space: nowrap;
	}

	.cart-table .product-img img {
		width: 50px;
		height: 50px;
	}

	.cart-table .product-title {
		font-size: 0.9rem;
	}

	.cart-table .qnt {
		width: 80px;
		padding: 0.25rem 0.5rem;
	}

	.cart-summary-wrapper {
		width: 100%;
		max-width: 100%;
	}

	.cart-summary {
		max-width: 100%;
		width: 100%;
		box-sizing: border-box;
	}


	.cart-coupon label {
		width: 100%;
		font-size: 0.9rem;
		margin: 0;
		box-sizing: border-box;
	}

	.cart-coupon input {
		width: 100%;
		max-width: 100%;
		flex: none;
		padding: 0.75rem 1rem;
		font-size: 0.9rem;
		box-sizing: border-box;
	}

	.cart-coupon button {
		width: 100%;
		max-width: 100%;
		flex: none;
		padding: 0.75rem 1rem;
		font-size: 0.9rem;
		box-sizing: border-box;
	}
}
</style>

<script>
jQuery(function($){

  function getQtyInput($btn){
    // أقرب صف منتج في السلة
    var $row = $btn.closest('.cart_item, tr');
    // input الكمية الحقيقي في ووكوميرس
    var $qty = $row.find('input.qty, input[name*="[qty]"]').first();
    return $qty;
  }

  function clamp(n, min, max){
    n = parseInt(n, 10);
    if (isNaN(n)) n = min;
    if (n < min) n = min;
    if (n > max) n = max;
    return n;
  }

  function updateCart($form){
    // الأفضل: اضغط زر تحديث السلة بدل submit مباشر
    var $update = $form.find('button[name="update_cart"], input[name="update_cart"]').first();
    if ($update.length) $update.prop('disabled', false).trigger('click');
    else $form.trigger('submit');
  }

  // PLUS
  $(document).on('click', '.cart-table .qnt .qnt-plus', function(e){
    e.preventDefault();
    e.stopPropagation();

    var $btn  = $(this);
    var $form = $btn.closest('form.woocommerce-cart-form');
    var $qty  = getQtyInput($btn);

    if (!$qty.length) return;

    var step = parseFloat($qty.attr('step')) || 1;
    var min  = parseFloat($qty.attr('min'))  || 1;
    var max  = parseFloat($qty.attr('max'))  || 9999;

    var current = clamp($qty.val(), min, max);
    var next = current + step;
    next = clamp(next, min, max);

    $qty.val(next).trigger('change');

    // تحديث عرضك (span) لو عندك
    var $qnt = $btn.closest('.qnt');
    $qnt.find('.qnt-value').text(next);
    $qnt.find('.qnt-input').val(next);

    updateCart($form);
  });

  // MINUS
  $(document).on('click', '.cart-table .qnt .qnt-minus', function(e){
    e.preventDefault();
    e.stopPropagation();

    var $btn  = $(this);
    var $form = $btn.closest('form.woocommerce-cart-form');
    var $qty  = getQtyInput($btn);

    if (!$qty.length) return;

    var step = parseFloat($qty.attr('step')) || 1;
    var min  = parseFloat($qty.attr('min'))  || 1;
    var max  = parseFloat($qty.attr('max'))  || 9999;

    var current = clamp($qty.val(), min, max);
    var next = current - step;
    next = clamp(next, min, max);

    $qty.val(next).trigger('change');

    var $qnt = $btn.closest('.qnt');
    $qnt.find('.qnt-value').text(next);
    $qnt.find('.qnt-input').val(next);

    updateCart($form);
  });

});
</script>
