<?php
/**
 * Template Name: الحجز (Booking)
 * Uses template-parts/booking-form. Process flow — Phase E wires to CPT.
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Debug: Check if template part exists
$template_part = locate_template( 'template-parts/booking-form.php' );
if ( ! $template_part ) {
	// Fallback: include directly
	$direct_path = get_template_directory() . '/template-parts/booking-form.php';
	if ( file_exists( $direct_path ) ) {
		include $direct_path;
	} else {
		echo '<main><p>Error: booking-form.php not found</p></main>';
	}
} else {
	?>
	<main>
		<?php get_template_part( 'template-parts/booking-form' ); ?>
	</main>
	<?php
}
get_footer(); ?>
