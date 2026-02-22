<?php
/**
 * Proceed to checkout button — Sweet House design.
 *
 * @package Sweet_House_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn-auth checkout-button button alt wc-forward">
	<?php esc_html_e( 'المتابعة لإتمام الطلب', 'sweet-house-theme' ); ?>
</a>
