<?php
get_header();
?>

<main class="special-bg">
	<section class="container y-u-max-w-1200 y-u-py-40">
		<h1 class="y-u-text-xxl y-u-font-bold y-u-m-b-16"><?php woocommerce_page_title(); ?></h1>
		<!-- TODO: Render WooCommerce product loop using theme cards. -->
		<?php woocommerce_content(); ?>
	</section>
</main>

<?php
get_footer();
?>
