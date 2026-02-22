<?php
/**
 * WooCommerce Notice — override
 * Styled with beauty-time design
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! $notices ) {
	return;
}
?>
<?php foreach ( $notices as $notice ) : ?>
	<div class="woocommerce-<?php echo esc_attr( isset( $notice['type'] ) ? $notice['type'] : 'info' ); ?>"<?php echo wc_get_notice_data_attr( $notice ); ?> role="status">
		<?php echo wc_kses_notice( $notice['notice'] ); ?>
	</div>
<?php endforeach; ?>
