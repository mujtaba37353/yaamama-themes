<?php
/**
 * Template Name: تسجيل الدخول
 * Page template for login.
 */
if ( is_user_logged_in() ) {
	wp_safe_redirect( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account' ) );
	exit;
}
get_header();
?>

<main>
	<section class="panner login y-u-m-b-0">
		<h1 class="y-u-text-center"><?php esc_html_e( 'تسجيل الدخول', 'beauty-care-theme' ); ?></h1>
	</section>
	<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
</main>

<?php get_footer(); ?>
