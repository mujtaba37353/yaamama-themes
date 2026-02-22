<?php
/**
 * Template Name: إنشاء حساب
 * Page template for registration.
 */
if ( is_user_logged_in() ) {
	wp_safe_redirect( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account' ) );
	exit;
}
get_header();
?>

<main>
	<section class="panner signup y-u-m-b-0">
		<h1 class="y-u-text-center"><?php esc_html_e( 'إنشاء حساب', 'beauty-care-theme' ); ?></h1>
	</section>
	<?php echo do_shortcode( '[woocommerce_my_account]' ); ?>
</main>

<?php get_footer(); ?>
