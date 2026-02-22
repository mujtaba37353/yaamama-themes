<?php
get_header();
?>

<main>
	<section class="panner panner-image y-u-m-b-0 container y-u-max-w-1200">
		<h1 class="y-u-text-center"><?php esc_html_e( 'حسابي', 'stationary-theme' ); ?></h1>
	</section>
	<section class="breadcrumbs container y-u-max-w-1200 y-u-m-b-0 ">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'stationary-theme' ); ?></a>
		<p><?php esc_html_e( 'حسابي', 'stationary-theme' ); ?></p>
	</section>
	<section class="account-section">
		<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
	</section>
</main>

<?php
get_footer();
