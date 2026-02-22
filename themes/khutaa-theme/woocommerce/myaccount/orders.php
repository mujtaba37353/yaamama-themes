<?php
/**
 * Orders
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<div class="content-section" id="orders-content">
	<h2 class="section-title"><?php esc_html_e( 'الطلبات', 'khutaa-theme' ); ?></h2>

<?php if ( $has_orders ) : ?>

	<div class="orders-list-view">
		<div class="orders-container">
			<div class="orders-header">
				<div class="order-col-header"></div>
				<div class="order-col"><?php esc_html_e( 'رقم الطلب', 'khutaa-theme' ); ?></div>
				<div class="order-col"><?php esc_html_e( 'التاريخ', 'khutaa-theme' ); ?></div>
				<div class="order-col"><?php esc_html_e( 'الحالة', 'khutaa-theme' ); ?></div>
				<div class="order-col"><?php esc_html_e( 'الإجمالي', 'khutaa-theme' ); ?></div>
				<div class="order-col"><?php esc_html_e( 'إجراءات', 'khutaa-theme' ); ?></div>
			</div>

			<?php
			foreach ( $customer_orders->orders as $customer_order ) {
				$order      = wc_get_order( $customer_order );
				$item_count = $order->get_item_count() - $order->get_item_count_refunded();
				$order_items = $order->get_items();
				$first_item = reset( $order_items );
				$product = $first_item ? wc_get_product( $first_item->get_product_id() ) : null;
				$image_url = $product ? wp_get_attachment_image_url( $product->get_image_id(), 'thumbnail' ) : wc_placeholder_img_src();
				?>
				<div class="order-item">
					<div class="order-image">
						<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product ? $product->get_name() : '' ); ?>" />
					</div>
					<div class="order-col order-number">
						<a href="<?php echo esc_url( $order->get_view_order_url() ); ?>">
							<?php echo esc_html( '#' . $order->get_order_number() ); ?>
						</a>
					</div>
					<div class="order-col order-date">
						<time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
							<?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
						</time>
					</div>
					<div class="order-col order-status">
						<?php
						$status = $order->get_status();
						$status_name = wc_get_order_status_name( $status );
						
						// Translate order status to Arabic
						$arabic_statuses = array(
							__( 'Pending payment', 'woocommerce' ) => __( 'قيد الانتظار', 'khutaa-theme' ),
							__( 'Processing', 'woocommerce' ) => __( 'قيد المعالجة', 'khutaa-theme' ),
							__( 'On hold', 'woocommerce' ) => __( 'معلق', 'khutaa-theme' ),
							__( 'Completed', 'woocommerce' ) => __( 'مكتمل', 'khutaa-theme' ),
							__( 'Cancelled', 'woocommerce' ) => __( 'ملغي', 'khutaa-theme' ),
							__( 'Refunded', 'woocommerce' ) => __( 'مسترد', 'khutaa-theme' ),
							__( 'Failed', 'woocommerce' ) => __( 'فاشل', 'khutaa-theme' ),
						);
						
						if ( isset( $arabic_statuses[ $status_name ] ) ) {
							$status_name = $arabic_statuses[ $status_name ];
						}
						echo esc_html( $status_name );
						?>
					</div>
					<div class="order-col order-total">
						<div class="total-text"><?php esc_html_e( 'المجموع:', 'khutaa-theme' ); ?></div>
						<div class="total-price"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></div>
					</div>
					<div class="order-col order-actions">
						<?php
						$actions = wc_get_account_orders_actions( $order );
						if ( ! empty( $actions ) ) {
							// Translate action names to Arabic
							$arabic_action_names = array(
								__( 'View', 'woocommerce' ) => __( 'عرض', 'khutaa-theme' ),
								__( 'Pay', 'woocommerce' ) => __( 'دفع', 'khutaa-theme' ),
								__( 'Cancel', 'woocommerce' ) => __( 'إلغاء', 'khutaa-theme' ),
								__( 'Reorder', 'woocommerce' ) => __( 'إعادة الطلب', 'khutaa-theme' ),
							);
							
							foreach ( $actions as $key => $action ) {
								$action_name = $action['name'];
								if ( isset( $arabic_action_names[ $action_name ] ) ) {
									$action_name = $arabic_action_names[ $action_name ];
								}
								echo '<a href="' . esc_url( $action['url'] ) . '" class="btn-view ' . esc_attr( $key ) . '">' . esc_html( $action_name ) . '</a>';
							}
						}
						?>
					</div>
				</div>
				<?php
			}
			?>
		</div>
	</div>

	<?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

	<?php if ( 1 < $customer_orders->max_num_pages ) : ?>
		<div class="woocommerce-pagination woocommerce-pagination--without-numbers woocommerce-Pagination">
			<?php if ( 1 !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--previous woocommerce-Button woocommerce-Button--previous button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>"><?php esc_html_e( 'السابق', 'khutaa-theme' ); ?></a>
			<?php endif; ?>

			<?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
				<a class="woocommerce-button woocommerce-button--next woocommerce-Button woocommerce-Button--next button" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>"><?php esc_html_e( 'التالي', 'khutaa-theme' ); ?></a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

<?php else : ?>

	<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
		<?php esc_html_e( 'لم يتم إجراء أي طلب حتى الآن.', 'khutaa-theme' ); ?>
		<a class="woocommerce-Button wc-forward button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php esc_html_e( 'تصفح المنتجات', 'khutaa-theme' ); ?>
		</a>
	</div>

<?php endif; ?>
</div>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>
