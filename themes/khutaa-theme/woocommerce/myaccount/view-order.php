<?php
/**
 * View Order
 *
 * Shows the details of a particular order on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/view-order.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>
<div class="content-section" id="view-order-content">
	<div class="order-details-view">
		<div class="order-details-header">
			<?php
			$order_status_text = sprintf(
				/* translators: 1: order number 2: order date 3: order status */
				esc_html__( 'تم تقديم الطلب #%1$s في %2$s وهو الآن بحالة %3$s', 'khutaa-theme' ),
				'<mark class="order-number">' . $order->get_order_number() . '</mark>',
				'<mark class="order-date">' . wc_format_datetime( $order->get_date_created() ) . '</mark>',
				'<mark class="order-status">' . khutaa_translate_order_status( $order->get_status() ) . '</mark>'
			);
			?>
			<p class="order-submitted-text"><?php echo wp_kses_post( $order_status_text ); ?></p>
		</div>

		<?php if ( $notes ) : ?>
			<h2><?php esc_html_e( 'ملاحظات الطلب', 'khutaa-theme' ); ?></h2>
			<ol class="woocommerce-OrderUpdates commentlist notes">
				<?php foreach ( $notes as $note ) : ?>
					<li class="woocommerce-OrderUpdate comment note">
						<div class="woocommerce-OrderUpdate-inner comment_container">
							<div class="woocommerce-OrderUpdate-text comment-text">
								<p class="woocommerce-OrderUpdate-meta meta"><?php echo date_i18n( esc_html__( 'j F Y، h:i A', 'khutaa-theme' ), strtotime( $note->comment_date ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
								<div class="woocommerce-OrderUpdate-description description">
									<?php echo wpautop( wptexturize( $note->comment_content ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							</div>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		<?php endif; ?>

		<?php do_action( 'woocommerce_view_order', $order->get_id() ); ?>

		<div class="order-details-table">
			<div class="order-details-row order-details-header-row">
				<div class="order-detail-col"><?php esc_html_e( 'المنتج', 'khutaa-theme' ); ?></div>
				<div class="order-detail-col"><?php esc_html_e( 'الإجمالي', 'khutaa-theme' ); ?></div>
			</div>

			<?php
			foreach ( $order->get_items() as $item_id => $item ) {
				$product = $item->get_product();
				?>
				<div class="order-details-row">
					<div class="order-detail-col">
						<?php
						$is_visible        = $product && $product->is_visible();
						$product_permalink = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );

						echo wp_kses_post( apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), esc_html( $item->get_name() ) ) : esc_html( $item->get_name() ), $item, $is_visible ) );

						$qty          = $item->get_quantity();
						$refunded_qty  = $order->get_qty_refunded_for_item( $item_id );

						if ( $refunded_qty ) {
							$qty_display = '<del>' . esc_html( $qty ) . '</del> <ins>' . esc_html( $qty - ( $refunded_qty * -1 ) ) . '</ins>';
						} else {
							$qty_display = esc_html( $qty );
						}
						echo apply_filters( 'woocommerce_order_item_quantity_html', ' <strong class="product-quantity">' . sprintf( '&times;&nbsp;%s', $qty_display ) . '</strong>', $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

						wc_display_item_meta( $item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

						do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
						?>
					</div>
					<div class="order-detail-col">
						<?php echo wp_kses_post( $order->get_formatted_line_subtotal( $item ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				</div>
				<?php
			}
			?>

			<?php
			foreach ( $order->get_order_item_totals() as $key => $total ) {
				?>
				<?php
				// Translate total labels to Arabic
				$arabic_labels = array(
					__( 'Subtotal:', 'woocommerce' ) => __( 'المجموع الفرعي:', 'khutaa-theme' ),
					__( 'Shipping:', 'woocommerce' ) => __( 'الشحن:', 'khutaa-theme' ),
					__( 'Tax:', 'woocommerce' ) => __( 'الضريبة:', 'khutaa-theme' ),
					__( 'Total:', 'woocommerce' ) => __( 'الإجمالي:', 'khutaa-theme' ),
					__( 'Payment method:', 'woocommerce' ) => __( 'طريقة الدفع:', 'khutaa-theme' ),
				);
				$label = $total['label'];
				if ( isset( $arabic_labels[ $label ] ) ) {
					$label = $arabic_labels[ $label ];
				}
				?>
				<div class="order-details-row">
					<div class="order-detail-col"><?php echo esc_html( $label ); ?></div>
					<div class="order-detail-col"><?php echo wp_kses_post( $total['value'] ); ?></div>
				</div>
				<?php
			}
			?>
		</div>

		<?php if ( $order->get_payment_method_title() ) : ?>
			<div class="order-details-row">
				<div class="order-detail-col"><?php esc_html_e( 'وسيلة الدفع:', 'khutaa-theme' ); ?></div>
				<div class="order-detail-col"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></div>
			</div>
		<?php endif; ?>

		<?php if ( $order->has_billing_address() || $order->has_shipping_address() ) : ?>
			<div class="order-address-section">
				<?php if ( $order->has_billing_address() ) : ?>
					<h3 class="order-address-title"><?php esc_html_e( 'عنوان الفاتورة', 'khutaa-theme' ); ?></h3>
					<address class="order-address-info">
						<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'غير متوفر', 'khutaa-theme' ) ) ); ?>
					</address>
				<?php endif; ?>

				<?php if ( $order->has_shipping_address() ) : ?>
					<h3 class="order-address-title"><?php esc_html_e( 'عنوان الشحن', 'khutaa-theme' ); ?></h3>
					<address class="order-address-info">
						<?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'غير متوفر', 'khutaa-theme' ) ) ); ?>
					</address>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
