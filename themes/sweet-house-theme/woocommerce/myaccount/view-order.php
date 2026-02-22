<?php
/**
 * View Order — Sweet House design
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.1.0
 */

defined( 'ABSPATH' ) || exit;

$notes = $order->get_customer_order_notes();
?>

<div class="orders-container order-details-view">
	<div class="order-details-header">
		<p class="order-submitted-text">
			<?php
			echo wp_kses_post(
				apply_filters(
					'woocommerce_order_details_status',
					sprintf(
						/* translators: 1: order number 2: order date 3: order status */
						esc_html__( 'تم تقديم الطلب #%1$s في %2$s وهو الآن بحالة %3$s.', 'sweet-house-theme' ),
						'<strong>' . esc_html( $order->get_order_number() ) . '</strong>',
						esc_html( wc_format_datetime( $order->get_date_created() ) ),
						esc_html( wc_get_order_status_name( $order->get_status() ) )
					),
					$order
				)
			);
			?>
		</p>
	</div>

	<?php do_action( 'woocommerce_view_order', $order_id ); ?>

	<?php if ( $notes ) : ?>
		<div class="order-notes-section" style="margin-top: 2rem; padding: 1.5rem; border: 2px solid var(--y-secondary); border-radius: 12px; max-width: 600px; margin-left: auto; margin-right: auto;">
			<h3 style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1rem;"><?php esc_html_e( 'تحديثات الطلب', 'sweet-house-theme' ); ?></h3>
			<ol class="woocommerce-OrderUpdates commentlist notes" style="list-style: none; padding: 0; margin: 0;">
				<?php foreach ( $notes as $note ) : ?>
					<li class="woocommerce-OrderUpdate comment note" style="margin-bottom: 1rem; padding-bottom: 1rem; border-bottom: 1px solid #eee;">
						<p class="woocommerce-OrderUpdate-meta meta" style="font-size: 0.9rem; color: #666; margin-bottom: 0.5rem;">
							<?php echo esc_html( date_i18n( __( 'j F Y، H:i', 'sweet-house-theme' ), strtotime( $note->comment_date ) ) ); ?>
						</p>
						<div class="woocommerce-OrderUpdate-description description">
							<?php echo wp_kses_post( wpautop( wptexturize( $note->comment_content ) ) ); ?>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	<?php endif; ?>
</div>
