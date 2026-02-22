<?php
get_header();
$has_wishlist = ! empty( dark_theme_get_wishlist_ids() );
?>

<header data-y="design-header">
	<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/header2.png' ) ); ?>" alt="" class="design-img y-u-w-100" />
</header>

<main data-y="main" class="<?php echo $has_wishlist ? '' : 'not-found-container'; ?>">
	<div class="y-main-container">
		<div data-y="breadcrumb">
			<nav aria-label="breadcrumb" class="y-breadcrumb-container">
				<ol class="y-breadcrumb"></ol>
			</nav>
		</div>
		<section data-y="top-products-logo"></section>
		<section data-y="filter"></section>

		<div class="wishlist-content">
			<?php echo do_shortcode( '[dark_wishlist]' ); ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
