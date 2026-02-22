<?php
/**
 * Main template
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<main class="main-page">
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	} else {
		get_template_part( 'template-parts/content', 'none' );
	}
	?>
</main>
<?php
get_footer();
