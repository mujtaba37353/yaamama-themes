<?php
/**
 * Template Name: صفحة حسابي
 * Template for My Account page
 *
 * @package KhutaaTheme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';

// Enqueue account-specific styles
wp_enqueue_style( 'khutaa-myaccount', $khutaa_uri . '/templates/my-account/my-account.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-design-header', $khutaa_uri . '/templates/pages header/y-c-design-header.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-breadcrumb', $khutaa_uri . '/components/layout/y-c-breadcrumb.css', array(), '1.0.0' );

// Enqueue scripts
wp_enqueue_script( 'khutaa-myaccount', $khutaa_uri . '/js/y-my-account.js', array( 'jquery' ), '1.0.0', true );
wp_enqueue_script( 'khutaa-design-header', $khutaa_uri . '/js/y-design-header.js', array(), '1.0.0', true );
wp_enqueue_script( 'khutaa-breadcrumb', $khutaa_uri . '/js/y-breadcrumb.js', array(), '1.0.0', true );
?>

<main id="main" class="y-u-container">
	<?php
	// Display WooCommerce notices
	if ( function_exists( 'wc_print_notices' ) ) {
		wc_print_notices();
	}
	?>

	<?php
	// Output the my account shortcode
	echo do_shortcode( '[woocommerce_my_account]' );
	?>
</main>

<?php
get_footer();
