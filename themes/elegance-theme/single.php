<?php
/**
 * Single post template - Elegance
 */
get_header();
?>
<main>
	<?php
	while ( have_posts() ) {
		the_post();
		the_content();
	}
	?>
</main>
<?php
get_footer();
