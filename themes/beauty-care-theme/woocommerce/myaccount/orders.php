<?php
/**
 * Orders — Beauty Care design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders );
?>

<?php if ( $has_orders ) : ?>
<ul class="orders-list">
	<?php
	foreach ( $customer_orders->orders as $customer_order ) {
		$order       = wc_get_order( $customer_order );
		$item_count  = $order->get_item_count() - $order->get_item_count_refunded();
		$status      = $order->get_status();
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
		<li class="item" data-order-id="<?php echo esc_attr( $order->get_order_number() ); ?>">
			<div class="order-card">
				<div class="order-status <?php echo esc_attr( $status_class ); ?>">
					<span><?php echo esc_html( $status_label ); ?></span>
				</div>
				<div class="order-content">
					<div class="order-main-info">
						<p class="order-number"><?php echo esc_html( $order->get_order_number() ); ?></p>
						<p class="order-date"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></p>
						<p class="order-products"><?php echo esc_html( sprintf( __( 'عدد المنتجات: %d', 'beauty-care-theme' ), $item_count ) ); ?></p>
						<p class="order-total"><?php echo esc_html( __( 'الإجمالي:', 'beauty-care-theme' ) ); ?> <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p>
					</div>
					<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="view-order-btn btn-black"><?php esc_html_e( 'عرض التفاصيل', 'beauty-care-theme' ); ?></a>
				</div>
			</div>
		</li>
		<?php
	}
	?>
</ul>

<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
	<nav class="woocommerce-pagination">
		<?php if ( 1 !== $current_page ) : ?>
			<a class="woocommerce-button button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'السابق', 'beauty-care-theme' ); ?></a>
		<?php endif; ?>
		<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
			<a class="woocommerce-button button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'التالي', 'beauty-care-theme' ); ?></a>
		<?php endif; ?>
	</nav>
<?php endif; ?>

<?php else : ?>
<div class="empty-state-container">
	<div class="empty-state">
		<img src="<?php echo esc_url( get_template_directory_uri() . '/beauty-care/assets/empty-orders.png' ); ?>" alt="<?php esc_attr_e( 'لا توجد طلبات', 'beauty-care-theme' ); ?>" onerror="this.style.display='none'">
		<h3><?php esc_html_e( 'لا توجد طلبات بعد', 'beauty-care-theme' ); ?></h3>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn main-button"><?php esc_html_e( 'تسوق الآن', 'beauty-care-theme' ); ?></a>
	</div>
</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
