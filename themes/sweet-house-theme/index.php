<?php
/**
 * Default template — fallback when no specific template is used.
 *
 * @package Sweet_House_Theme
 */

get_header();
?>

<main data-y="main" class="main-page">
	<div class="main-container">
		<?php if ( have_posts() ) : ?>
			<?php while ( have_posts() ) : the_post(); ?>
				<article <?php post_class(); ?>>
					<?php
				$hide_page_title = ( function_exists( 'is_cart' ) && is_cart() ) || ( function_exists( 'is_account_page' ) && is_account_page() );
				if ( ! $hide_page_title ) :
					?>
					<h1><?php the_title(); ?></h1>
				<?php endif; ?>
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</article>
			<?php endwhile; ?>
		<?php else : ?>
			<p><?php echo esc_html__( 'لا يوجد محتوى.', 'sweet-house-theme' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php get_footer(); ?>
