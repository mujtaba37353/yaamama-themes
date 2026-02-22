<?php
/**
 * Order details — Sweet House design
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;

$order = wc_get_order( $order_id );

if ( ! $order ) {
	return;
}

$order_items        = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$downloads          = $order->get_downloadable_items();
$actions            = array_filter(
	wc_get_account_orders_actions( $order ),
	function ( $key ) {
		return 'view' !== $key;
	},
	ARRAY_FILTER_USE_KEY
);
$show_customer_details = $order->get_user_id() === get_current_user_id();

if ( $show_downloads ) {
	wc_get_template( 'order/order-downloads.php', array( 'downloads' => $downloads, 'show_title' => true ) );
}

do_action( 'woocommerce_order_details_before_order_table', $order );
?>

<div class="order-details-table">
	<div class="order-details-row order-details-header-row">
		<div class="order-detail-col"><?php esc_html_e( 'المنتج', 'sweet-house-theme' ); ?></div>
		<div class="order-detail-col"><?php esc_html_e( 'الإجمالي', 'sweet-house-theme' ); ?></div>
	</div>

	<?php
	do_action( 'woocommerce_order_details_before_order_table_items', $order );
	foreach ( $order_items as $item_id => $item ) {
		if ( ! apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
			continue;
		}
		$product = $item->get_product();
		$name    = $item->get_name();
		$qty     = $item->get_quantity();
		$refunded_qty = $order->get_qty_refunded_for_item( $item_id );
		if ( $refunded_qty ) {
			$qty_display = ( $qty + $refunded_qty );
		} else {
			$qty_display = $qty;
		}
		$subtotal = $order->get_formatted_line_subtotal( $item );
		?>
		<div class="order-details-row" data-label="<?php esc_attr_e( 'المنتج', 'sweet-house-theme' ); ?>">
			<div class="order-detail-col">
				<?php
				echo wp_kses_post( $name );
				if ( $qty_display > 1 ) {
					echo ' &times; ' . esc_html( $qty_display );
				}
				wc_display_item_meta( $item );
				?>
			</div>
			<div class="order-detail-col" data-label="<?php esc_attr_e( 'الإجمالي', 'sweet-house-theme' ); ?>"><?php echo wp_kses_post( $subtotal ); ?></div>
		</div>
		<?php
	}
	do_action( 'woocommerce_order_details_after_order_table_items', $order );
	?>

	<?php
	foreach ( $order->get_order_item_totals() as $key => $total ) {
		?>
		<div class="order-details-row" data-label="<?php echo esc_attr( $total['label'] ); ?>">
			<div class="order-detail-col"><?php echo esc_html( $total['label'] ); ?></div>
			<div class="order-detail-col"><?php echo wp_kses_post( $total['value'] ); ?></div>
		</div>
		<?php
	}
	?>

	<?php if ( $order->get_customer_note() ) : ?>
		<div class="order-details-row" data-label="<?php esc_attr_e( 'ملاحظة:', 'sweet-house-theme' ); ?>">
			<div class="order-detail-col"><?php esc_html_e( 'ملاحظة:', 'sweet-house-theme' ); ?></div>
			<div class="order-detail-col"><?php echo wp_kses_post( nl2br( wc_wptexturize_order_note( $order->get_customer_note() ) ) ); ?></div>
		</div>
	<?php endif; ?>
</div>

<?php if ( ! empty( $actions ) ) : ?>
	<div class="order-actions-section" style="margin: 1.5rem 0; text-align: center;">
		<?php foreach ( $actions as $key => $action ) : ?>
			<a href="<?php echo esc_url( $action['url'] ); ?>" class="btn-view <?php echo esc_attr( sanitize_html_class( $key ) ); ?>"><?php echo esc_html( $action['name'] ); ?></a>
		<?php endforeach; ?>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
<?php do_action( 'woocommerce_after_order_details', $order ); ?>

<?php if ( $show_customer_details ) : ?>
	<div class="order-address-section">
		<h3 class="order-address-title"><?php esc_html_e( 'عنوان الفاتورة', 'sweet-house-theme' ); ?></h3>
		<div class="order-address-info">
			<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'غير متوفر', 'sweet-house-theme' ) ) ); ?>
			<?php if ( $order->get_billing_phone() ) : ?>
				<p><?php echo esc_html( $order->get_billing_phone() ); ?></p>
			<?php endif; ?>
			<?php if ( $order->get_billing_email() ) : ?>
				<p><?php echo esc_html( $order->get_billing_email() ); ?></p>
			<?php endif; ?>
			<?php do_action( 'woocommerce_order_details_after_customer_address', 'billing', $order ); ?>
		</div>
	</div>
<?php endif; ?>
