<?php
/**
 * Template Name: نسيت كلمة المرور
 * Page template for lost password.
 */
if ( is_user_logged_in() ) {
	wp_safe_redirect( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account' ) );
	exit;
}
get_header();
?>

<main>
	<section class="panner forget-password y-u-m-b-0">
		<h1 class="y-u-text-center"><?php esc_html_e( 'أعد تعيين كلمة المرور', 'beauty-care-theme' ); ?></h1>
	</section>
	<?php wc_get_template( 'myaccount/form-lost-password.php' ); ?>
</main>

<?php get_footer(); ?>
