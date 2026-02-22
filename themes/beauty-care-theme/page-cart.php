<?php
get_header();

$cart_empty = function_exists( 'WC' ) && WC()->cart && WC()->cart->is_empty();
?>
<main>
	<section class="panner cart <?php echo $cart_empty ? 'y-u-m-b-0 ' : ''; ?>container y-u-max-w-1200">
		<h1 class="y-u-text-center"><?php esc_html_e( 'السلة', 'beauty-care-theme' ); ?></h1>
		<?php if ( ! $cart_empty ) : ?>
		<div class="breadcrumbs">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a>
			<p><?php esc_html_e( 'السلة', 'beauty-care-theme' ); ?></p>
		</div>
		<?php endif; ?>
	</section>
	<?php if ( $cart_empty ) : ?>
	<div class="breadcrumbs container y-u-max-w-1200">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a>
		<p><?php esc_html_e( 'السلة', 'beauty-care-theme' ); ?></p>
	</div>
	<?php endif; ?>
	<section class="container y-u-max-w-1200 cart-section">
		<?php echo do_shortcode( '[woocommerce_cart]' ); ?>
	</section>
</main>

<?php
get_footer();
