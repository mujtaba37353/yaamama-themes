<?php
/**
 * Main template - Elegance
 */
get_header();
?>
<main>
	<?php
	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();
			the_content();
		}
	} else {
		echo '<section class="container y-u-max-w-1200"><p>لا يوجد محتوى.</p></section>';
	}
	?>
</main>
<?php
get_footer();
