<?php
/**
 * Proceed to checkout button - Beauty Care design override
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn main-button checkout-button button alt wc-forward">
	<?php esc_html_e( 'اذهب إلى الدفع', 'beauty-care-theme' ); ?>
</a>
