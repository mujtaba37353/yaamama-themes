<?php
/**
 * Order Receipt (Thank You) — override
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! isset( $order ) || ! $order ) {
	return;
}

$order_items = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
$downloads = $order->get_downloadable_items();
$show_downloads = $order->has_downloadable_item() && $order->is_download_permitted();

if ( $show_downloads ) {
	wc_get_template(
		'order/order-downloads.php',
		array(
			'downloads'  => $downloads,
			'show_title' => true,
		)
	);
}
?>
<?php
if ( $order->has_status( 'failed' ) ) :
	?>
	<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'فشل الدفع. يرجى المحاولة مرة أخرى.', 'beauty-time-theme' ); ?></p>
	<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
		<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'الدفع', 'beauty-time-theme' ); ?></a>
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'حسابي', 'beauty-time-theme' ); ?></a>
		<?php endif; ?>
	</p>
	<?php
else :
	?>
	<div class="appointment-details">
		<div class="details-column labels-column">
			<p><?php esc_html_e( 'التاريخ:', 'beauty-time-theme' ); ?></p>
			<p><?php esc_html_e( 'الوقت المحلي:', 'beauty-time-theme' ); ?></p>
			<p><?php esc_html_e( 'الخدمات:', 'beauty-time-theme' ); ?></p>
			<p><?php esc_html_e( 'الموظف:', 'beauty-time-theme' ); ?></p>
			<div class="divider"></div>
			<p><?php esc_html_e( 'الاسم:', 'beauty-time-theme' ); ?></p>
			<p><?php esc_html_e( 'رقم الهاتف:', 'beauty-time-theme' ); ?></p>
		</div>
		<div class="details-column values-column">
			<p><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></p>
			<p><?php echo esc_html( $order->get_date_created()->date_i18n( get_option( 'time_format' ) ) ); ?></p>
			<p>
				<?php
				$items = $order->get_items();
				$names = array();
				foreach ( $items as $item ) {
					$names[] = $item->get_name();
				}
				echo esc_html( implode( '، ', $names ) );
				?>
			</p>
			<p>Beauty Salon</p>
			<div class="divider"></div>
			<p><?php echo esc_html( $order->get_billing_first_name() . ' ' . $order->get_billing_last_name() ); ?></p>
			<p><?php echo esc_html( $order->get_billing_phone() ); ?></p>
		</div>
	</div>
	<?php
	do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
	do_action( 'woocommerce_thankyou', $order->get_id() );
endif;
?>
