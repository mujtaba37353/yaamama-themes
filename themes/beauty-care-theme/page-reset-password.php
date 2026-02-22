<?php
/**
 * Template Name: إعادة تعيين كلمة المرور
 * Page template for reset password.
 */
if ( is_user_logged_in() ) {
	wp_safe_redirect( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : home_url( '/my-account' ) );
	exit;
}
$key   = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
$login = isset( $_GET['login'] ) ? sanitize_text_field( wp_unslash( $_GET['login'] ) ) : '';
if ( ! $key || ! $login ) {
	wp_safe_redirect( home_url( '/forget-password' ) );
	exit;
}
get_header();
?>

<main>
	<section class="panner forget-password y-u-m-b-0">
		<h1 class="y-u-text-center"><?php esc_html_e( 'إعادة تعيين كلمة المرور', 'beauty-care-theme' ); ?></h1>
	</section>
	<?php wc_get_template( 'myaccount/form-reset-password.php', array( 'args' => array( 'key' => $key, 'login' => $login ) ) ); ?>
</main>

<?php get_footer(); ?>
