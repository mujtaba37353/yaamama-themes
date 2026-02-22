<?php
/**
 * Template Name: صفحة السلة
 * Template for displaying cart page
 *
 * @package KhutaaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';

// Get banner image
$banner_2_image = khutaa_get_demo_content( 'khutaa_banner_2_image' );
$default_banner = $khutaa_uri . '/assets/design.png';

// Enqueue breadcrumb CSS
wp_enqueue_style( 'khutaa-breadcrumb', $khutaa_uri . '/components/layout/y-c-breadcrumb.css', array(), '1.0.0' );
?>

<style>
/* WooCommerce Notices - Center and Arabic */
.woocommerce-notices-wrapper {
	display: flex;
	justify-content: center;
	align-items: center;
	width: 100%;
	padding: 1rem 5vw;
	margin-bottom: 2rem;
}

.woocommerce-notices-wrapper .woocommerce-message {
	text-align: center;
	direction: rtl;
	margin: 0 auto;
	background-color: #D4E9F7;
	color: #3a2c1c;
	padding: 1rem 1.5rem;
	border-radius: 8px;
	font-size: 1rem;
	font-weight: 600;
}

.woocommerce-notices-wrapper .woocommerce-message a.restore-item {
	display: inline-block;
	margin-right: 0.5rem;
	color: #4A9DB8;
	text-decoration: underline;
	font-weight: 700;
}

.woocommerce-notices-wrapper .woocommerce-message a.restore-item:hover {
	color: #b18155;
}

/* Prevent overflow on mobile */
@media (max-width: 768px) {
	.cart-page-main {
		padding: 1.5rem 1rem !important;
		width: 100%;
		max-width: 100vw;
		box-sizing: border-box;
		overflow-x: hidden;
	}

	.woocommerce-notices-wrapper {
		padding: 1rem 1rem;
	}
}
</style>

<header class="design-header">
	<?php if ( $banner_2_image ) : ?>
		<img src="<?php echo esc_url( $banner_2_image ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
	<?php else : ?>
		<img src="<?php echo esc_url( $default_banner ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
	<?php endif; ?>
</header>

<?php
// Breadcrumb
$breadcrumb_items = array(
	array(
		'text' => esc_html__( 'الرئيسية', 'khutaa-theme' ),
		'url'  => home_url( '/' ),
	),
	array(
		'text' => esc_html__( 'سلة المشتريات', 'khutaa-theme' ),
		'url'  => '',
	),
);
?>
<nav aria-label="breadcrumb" class="y-breadcrumb-container">
	<ol class="y-breadcrumb">
		<?php foreach ( $breadcrumb_items as $index => $item ) : ?>
			<li class="y-breadcrumb-item <?php echo ( $index === count( $breadcrumb_items ) - 1 ) ? 'active' : ''; ?>">
				<?php if ( ! empty( $item['url'] ) && $index < count( $breadcrumb_items ) - 1 ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $item['text'] ); ?>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>

<main id="main" class="y-u-container cart-page-main" style="background-color: #FCEFE4; padding: 2rem 5vw; min-height: 60vh;">
	<?php
	// Display cart shortcode or template
	if ( has_shortcode( get_post()->post_content, 'woocommerce_cart' ) ) {
		// If shortcode exists, let it handle everything
		the_content();
	} else {
		// Otherwise, manually load cart template
		if ( WC()->cart->is_empty() ) {
			wc_get_template( 'cart/cart-empty.php' );
		} else {
			wc_get_template( 'cart/cart.php' );
		}
	}
	?>
</main>

<?php
get_footer( 'shop' );
