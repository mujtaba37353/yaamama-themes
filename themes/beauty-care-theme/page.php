<?php
get_header();
?>

<main>
	<section class="container y-u-max-w-1200 y-u-py-40">
		<?php
		while ( have_posts() ) {
			the_post();
			the_title( '<h1 class="entry-title">', '</h1>' );
			the_content();
		}
		?>
	</section>
</main>

<?php
get_footer();
