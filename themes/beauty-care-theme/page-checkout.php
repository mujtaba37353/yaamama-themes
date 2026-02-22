<?php
get_header();
?>

<main>
	<section class="panner">
		<h1 class="y-u-text-center"><?php esc_html_e( 'إتمام الطلب', 'beauty-care-theme' ); ?></h1>
		<div class="breadcrumbs container y-u-max-w-1200">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a>
			<a href="<?php echo esc_url( beauty_care_cart_permalink() ); ?>"><?php esc_html_e( 'السلة', 'beauty-care-theme' ); ?></a>
			<p><?php esc_html_e( 'إتمام الطلب', 'beauty-care-theme' ); ?></p>
		</div>
	</section>
	<section class="container y-u-max-w-1200 checkout-section payment-section">
		<?php echo do_shortcode( '[woocommerce_checkout]' ); ?>
	</section>
</main>

<?php
get_footer();
