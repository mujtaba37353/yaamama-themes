<?php get_header(); ?>

<header data-y="header"><?php dark_theme_render_home_header(); ?></header>

<main data-y="main">
	<div class="y-main-container">
		<div data-y="category"><?php dark_theme_render_home_category(); ?></div>
		<div data-y="products-sec"><?php dark_theme_render_home_products_section(); ?></div>
		<?php dark_theme_render_home_offers_section(); ?>
		<div data-y="reviews"><?php dark_theme_render_home_reviews(); ?></div>
	</div>
</main>

<?php get_footer(); ?>
