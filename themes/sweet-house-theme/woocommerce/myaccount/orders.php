<?php
/**
 * Orders — Sweet House design
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

do_action( 'woocommerce_before_account_orders', $has_orders );
?>

<?php if ( $has_orders ) : ?>

	<?php
	$columns = wc_get_account_orders_columns();
	?>

	<div class="orders-container">
		<div class="orders-list-view">
			<div class="orders-header">
				<?php foreach ( $columns as $column_id => $column_name ) : ?>
					<div class="header-item"><?php echo esc_html( $column_name ); ?></div>
				<?php endforeach; ?>
			</div>

			<ul class="cart-list">
				<?php
				foreach ( $customer_orders->orders as $customer_order ) {
					$order = wc_get_order( $customer_order );
					if ( ! $order ) {
						continue;
					}
					$item_count = $order->get_item_count() - $order->get_item_count_refunded();
					$status     = $order->get_status();
					$actions    = wc_get_account_orders_actions( $order );
					$view_url   = $order->get_view_order_url();
					?>
					<li class="cart-item" data-order-id="<?php echo esc_attr( $order->get_id() ); ?>">
						<div class="product-info">
							<div class="product-details">
								<h5 class="product-title">
									<a href="<?php echo esc_url( $view_url ); ?>"><?php echo esc_html( '#' . $order->get_order_number() ); ?></a>
								</h5>
								<?php
								/* translators: %d: item count */
								echo esc_html( sprintf( _n( '%d منتج', '%d منتجات', $item_count, 'sweet-house-theme' ), $item_count ) );
								?>
							</div>
						</div>
						<div class="order-date">
							<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
						</div>
						<div class="order-status">
							<span class="status-<?php echo esc_attr( $status ); ?>"><?php echo esc_html( wc_get_order_status_name( $status ) ); ?></span>
						</div>
						<div class="product-total">
							<p class="y-u-d-flex y-u-align-items-center">
								<?php echo wp_kses_post( $order->get_formatted_order_total() ); ?>
							</p>
						</div>
						<div class="order-actions">
							<?php
							if ( ! empty( $actions ) ) {
								foreach ( $actions as $key => $action ) {
									printf(
										'<a href="%s" class="btn-view %s">%s</a>',
										esc_url( $action['url'] ),
										esc_attr( sanitize_html_class( $key ) ),
										esc_html( $action['name'] )
									);
								}
							}
							?>
						</div>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
	</div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination" style="margin-top: 2rem; display: flex; gap: 1rem; justify-content: center;">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous button btn-view" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'السابق', 'sweet-house-theme' ); ?></a>
			<?php endif; ?>
			<?php if ( (int) $customer_orders->max_num_pages !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next button btn-view" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'التالي', 'sweet-house-theme' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>

	<?php
	/* translators: 1: opening link 2: closing link */
	$message = sprintf(
		__( 'لم يتم تقديم أي طلب بعد. %1$sتصفح المنتجات%2$s', 'sweet-house-theme' ),
		'<a class="btn-view" href="' . esc_url( $shop_url ) . '">',
		'</a>'
	);
	?>
	<div class="orders-container">
		<p class="woocommerce-info"><?php echo wp_kses_post( $message ); ?></p>
	</div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
