<?php
get_header();
?>

<header data-y="design-header">
	<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/header2.png' ) ); ?>" alt="" class="design-img y-u-w-100" />
</header>

<main data-y="main" class="archive-products">
	<div class="y-main-container">
		<div data-y="breadcrumb">
			<nav aria-label="breadcrumb" class="y-breadcrumb-container">
				<ol class="y-breadcrumb"></ol>
			</nav>
		</div>
		<section data-y="top-products-logo"></section>
		<section data-y="filter"></section>
		<ul class="products y-u-my-10" data-y="products">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : ?>
					<?php the_post(); ?>
					<?php dark_theme_render_product_card( wc_get_product( get_the_ID() ) ); ?>
				<?php endwhile; ?>
			<?php else : ?>
				<p>لا توجد منتجات حالياً.</p>
			<?php endif; ?>
		</ul>
		<?php dark_theme_render_products_pagination(); ?>
	</div>
</main>

<?php get_footer(); ?>
