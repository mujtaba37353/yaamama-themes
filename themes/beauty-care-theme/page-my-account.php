<?php
get_header();
?>

<main>
	<section class="panner">
		<h1 class="y-u-text-center"><?php esc_html_e( 'حسابي', 'beauty-care-theme' ); ?></h1>
		<div class="breadcrumbs container y-u-max-w-1200">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a>
			<p><?php esc_html_e( 'حسابي', 'beauty-care-theme' ); ?></p>
		</div>
	</section>
	<section class="container y-u-max-w-1200 account-section">
		<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
	</section>
</main>

<?php
get_footer();
