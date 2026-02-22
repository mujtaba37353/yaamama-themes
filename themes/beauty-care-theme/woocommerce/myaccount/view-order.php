<?php
/**
 * View Order — Beauty Care design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;

$orders_url = wc_get_endpoint_url( 'orders', '', wc_get_page_permalink( 'myaccount' ) );
$status     = $order->get_status();
$status_class = 'status-pending';
$status_label = wc_get_order_status_name( $status );
if ( 'completed' === $status ) {
	$status_class = 'status-delivered';
	$status_label = __( 'تم التوصيل', 'beauty-care-theme' );
} elseif ( 'cancelled' === $status || 'refunded' === $status ) {
	$status_class = 'status-cancelled';
	$status_label = __( 'ملغى', 'beauty-care-theme' );
} else {
	$status_label = __( 'لم يصل بعد', 'beauty-care-theme' );
}
?>
<div class="order-details-content">
	<div class="order-details-header">
		<div class="order-status-badge <?php echo esc_attr( $status_class ); ?>">
			<?php echo esc_html( $status_label ); ?>
		</div>
		<div class="order-header-main">
			<a href="<?php echo esc_url( $orders_url ); ?>" class="back-to-orders">
				<i class="fa-solid fa-angle-right"></i>
				<?php esc_html_e( 'الرجوع إلى الطلبات', 'beauty-care-theme' ); ?>
			</a>
			<div class="order-info">
				<p class="order-details-title">
					<?php esc_html_e( 'طلب رقم:', 'beauty-care-theme' ); ?> <span><?php echo esc_html( $order->get_order_number() ); ?></span>
				</p>
				<p class="order-details-date">
					<?php esc_html_e( 'تاريخ الطلب:', 'beauty-care-theme' ); ?> <span><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></span>
				</p>
			</div>
		</div>
	</div>

	<?php do_action( 'woocommerce_view_order', $order_id ); ?>
</div>
