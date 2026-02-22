<?php
/**
 * Template Name: سياسات
 * Page template for privacy policy, return policy, shipping policy.
 */
get_header();
?>

<main>
	<section class="panner">
		<h1 class="y-u-text-center"><?php the_title(); ?></h1>
		<div class="breadcrumbs container y-u-max-w-1200">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a>
			<p><?php the_title(); ?></p>
		</div>
	</section>

	<section class="container y-u-max-w-1200 policy-content y-u-py-40">
		<?php
		while ( have_posts() ) {
			the_post();
			the_content();
		}
		?>
	</section>
</main>

<?php
get_footer();
