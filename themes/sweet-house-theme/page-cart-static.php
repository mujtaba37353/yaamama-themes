<?php
/**
 * Cart Page — Static design only (Phase 1).
 * Displays 100% static HTML/CSS/JS from sweet-house design files.
 * No WooCommerce output. For design verification before Phase 2 integration.
 *
 * @package Sweet_House_Theme
 * @see     design: sweet-house/templates/cart/layout.html
 * @see     sweet-house/components/cart/y-c-cart-table.html
 * @see     sweet-house/components/cards/y-c-cart-summary-card.html
 */

defined( 'ABSPATH' ) || exit;

$asset_uri = function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( '' ) : get_template_directory_uri() . '/sweet-house/';
$asset_path = function_exists( 'sweet_house_asset_path' ) ? sweet_house_asset_path( '' ) : get_template_directory() . '/sweet-house/';

// Load static cart table HTML.
$cart_table_html = '';
$cart_table_file = $asset_path . 'components/cart/y-c-cart-table.html';
if ( file_exists( $cart_table_file ) ) {
	$cart_table_html = file_get_contents( $cart_table_file );
	// Replace relative asset paths with full URIs.
	$cart_table_html = str_replace( '../../assets/', $asset_uri . 'assets/', $cart_table_html );
}

// Load static cart summary HTML.
$cart_summary_html = '';
$cart_summary_file = $asset_path . 'components/cards/y-c-cart-summary-card.html';
if ( file_exists( $cart_summary_file ) ) {
	$cart_summary_html = file_get_contents( $cart_summary_file );
	// Replace relative asset paths.
	$cart_summary_html = str_replace( '../../assets/', $asset_uri . 'assets/', $cart_summary_html );
	// Replace payment link with checkout URL.
	$checkout_url = function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#';
	$cart_summary_html = str_replace( '../../templates/payment/layout.html', esc_url( $checkout_url ), $cart_summary_html );
}

get_header();
?>

<header data-y="design-header" class="cart-design-header">
	<img src="<?php echo esc_url( $asset_uri . 'assets/panner.png' ); ?>" alt="<?php esc_attr_e( 'بانر سويت هاوس - متجر الحلويات والمخبوزات', 'sweet-house-theme' ); ?>" class="panner-img" />
</header>

<main data-y="main" class="y-u-my-10">
	<div class="main-container">
		<nav data-y="breadcrumb" class="woocommerce-breadcrumb-wrap y-breadcrumb-container" aria-label="<?php esc_attr_e( 'مسار الصفحة', 'sweet-house-theme' ); ?>">
			<ol class="y-breadcrumb">
				<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
				<li class="y-breadcrumb-item active"><?php esc_html_e( 'سلة المشتريات', 'sweet-house-theme' ); ?></li>
			</ol>
		</nav>
	</div>
	<div class="main-container cart-main-grid">
		<div data-y="cart-table" class="cart-form-column">
			<?php echo $cart_table_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
		<div data-y="cart-summary">
			<?php echo $cart_summary_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</div>
	</div>
	<div class="main-container">
		<div class="pro">
			<h2 class="section-title"><?php esc_html_e( 'تسوق أكثر', 'sweet-house-theme' ); ?></h2>
			<products data-y="related-products"></products>
		</div>
	</div>
</main>

<?php get_footer(); ?>
