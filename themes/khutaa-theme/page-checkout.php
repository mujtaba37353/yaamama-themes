<?php
/**
 * Template Name: صفحة الدفع
 * Template for displaying checkout page
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

// Enqueue styles
wp_enqueue_style( 'khutaa-breadcrumb', $khutaa_uri . '/components/layout/y-c-breadcrumb.css', array(), '1.0.0' );
?>

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
		'text' => esc_html__( 'الدفع', 'khutaa-theme' ),
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

<main id="main" class="checkout-main-wrapper" style="background-color: #FCEFE4; padding: 2rem 5vw; min-height: 60vh;">
	<?php
	// Display checkout shortcode or template
	if ( has_shortcode( get_post()->post_content, 'woocommerce_checkout' ) ) {
		the_content();
	} else {
		// Load checkout form
		wc_get_template( 'checkout/form-checkout.php', array( 'checkout' => WC()->checkout() ) );
	}
	?>
</main>

<style>
.checkout-main-wrapper {
	background-color: #FCEFE4;
	padding: 2rem 5vw;
	min-height: 60vh;
}

@media (max-width: 820px) {
	.checkout-main-wrapper {
		padding: 2rem 1rem;
	}
}

@media (max-width: 576px) {
	.checkout-main-wrapper {
		padding: 1.5rem 0.75rem;
	}
}
</style>

<?php
get_footer( 'shop' );
