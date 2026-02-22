<?php
/**
 * WooCommerce Info Notice — override
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! $messages ) {
	return;
}
?>
<ul class="woocommerce-info" role="alert">
	<?php foreach ( $messages as $message ) : ?>
		<li><?php echo wp_kses_post( $message ); ?></li>
	<?php endforeach; ?>
</ul>
