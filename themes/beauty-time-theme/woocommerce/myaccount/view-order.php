<?php
/**
 * View Order — override
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! $order ) {
	return;
}

$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
?>
<div class="main-content tab-content active" data-content="profile">
	<div class="account-form-card">
		<h2 class="section-title"><?php esc_html_e( 'تفاصيل الطلب', 'beauty-time-theme' ); ?></h2>
		<div class="woocommerce-order-details">
			<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
				<thead>
					<tr>
						<th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'المنتج', 'beauty-time-theme' ); ?></th>
						<th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'المجموع', 'beauty-time-theme' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					do_action( 'woocommerce_order_details_before_order_table_items', $order );

					foreach ( $order_items as $item_id => $item ) {
						$product = $item->get_product();
						?>
						<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">
							<td class="woocommerce-table__product-name product-name">
								<?php
								$is_visible        = $product && $product->is_visible();
								$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

								echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), esc_html( $item->get_name() ) ) : esc_html( $item->get_name() ), $item, $is_visible ) );

								$qty          = $item->get_quantity();
								$refunded_qty = $order->get_qty_refunded_for_item( $item_id );

								if ( $refunded_qty ) {
									$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
								} else {
									$qty_display = esc_html( $qty );
								}

								echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $qty_display ) . '</strong>', $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

								do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

								wc_display_item_meta( $item );

								do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
								?>
							</td>
							<td class="woocommerce-table__product-total product-total">
								<?php echo $order->get_formatted_line_subtotal( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							</td>
						</tr>
						<?php
					}

					do_action( 'woocommerce_order_details_after_order_table_items', $order );
					?>
				</tbody>
				<tfoot>
					<?php
					foreach ( $order->get_order_item_totals() as $key => $total ) {
						?>
						<tr>
							<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
							<td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
						</tr>
						<?php
					}
					?>
					<?php if ( $order->get_customer_note() ) : ?>
						<tr>
							<th><?php esc_html_e( 'ملاحظة:', 'beauty-time-theme' ); ?></th>
							<td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
						</tr>
					<?php endif; ?>
				</tfoot>
			</table>
			<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
		</div>

		<?php if ( $order->has_status( 'processing' ) || $order->has_status( 'completed' ) ) : ?>
			<div class="woocommerce-order-downloads">
				<?php wc_get_template( 'order/order-downloads.php', array( 'downloads' => $order->get_downloadable_items(), 'show_title' => true ) ); ?>
			</div>
		<?php endif; ?>
	</div>
</div>
