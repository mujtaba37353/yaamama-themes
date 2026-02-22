<?php
/**
 * My Account page — Sweet House design
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

$account_url = wc_get_page_permalink( 'myaccount' );

if ( function_exists( 'sweet_house_account_design_header' ) ) {
	sweet_house_account_design_header();
}
?>

<div class="y-breadcrumb-container">
	<nav aria-label="<?php esc_attr_e( 'مسار التنقل', 'sweet-house-theme' ); ?>">
		<ol class="y-breadcrumb">
			<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
			<li class="y-breadcrumb-item active"><?php esc_html_e( 'حسابي', 'sweet-house-theme' ); ?></li>
		</ol>
	</nav>
</div>

<div class="account-layout">
	<?php do_action( 'woocommerce_before_account_navigation' ); ?>

	<aside class="account-sidebar-shell">
		<?php wc_get_template( 'myaccount/navigation.php' ); ?>
	</aside>

	<div class="woocommerce-MyAccount-content account-content-shell">
		<?php do_action( 'woocommerce_account_content' ); ?>
	</div>
</div>
