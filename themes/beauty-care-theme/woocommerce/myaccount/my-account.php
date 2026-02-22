<?php
/**
 * My Account page — Beauty Care design
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;
?>

<?php
global $wp;
$content_class = 'woocommerce-MyAccount-content content';
if ( ! empty( $wp->query_vars['view-order'] ) ) {
	$content_class .= ' order-details-page active';
} elseif ( ! empty( $wp->query_vars['edit-address'] ) ) {
	$content_class .= ' addresses tab-content';
} elseif ( ! empty( $wp->query_vars['orders'] ) ) {
	$content_class .= ' orders tab-content';
} elseif ( ! empty( $wp->query_vars['edit-account'] ) ) {
	$content_class .= ' profile tab-content';
} else {
	$content_class .= ' profile tab-content';
}
?>
<section class="profile-section">
	<div class="container y-u-max-w-1200">
		<?php do_action( 'woocommerce_before_account_navigation' ); ?>
		<?php wc_get_template( 'myaccount/navigation.php' ); ?>
		<div class="<?php echo esc_attr( $content_class ); ?>">
			<?php do_action( 'woocommerce_account_content' ); ?>
		</div>
	</div>
</section>
