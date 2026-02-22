<?php
get_header();
?>

<main class="payment-page special-bg">
	<div class="container y-u-py-32">
		<div class="y-u-text-center y-u-m-b-40">
			<h1 class="y-u-text-xl y-u-font-bold y-u-m-b-8">إتمام عملية الشراء</h1>
			<p>أكمل بياناتك لإتمام عملية الشراء بأمان</p>
		</div>

		<?php if ( function_exists( 'woocommerce_checkout' ) ) : ?>
			<?php woocommerce_checkout(); ?>
		<?php elseif ( have_posts() ) : ?>
			<?php while ( have_posts() ) : ?>
				<?php the_post(); ?>
				<?php the_content(); ?>
			<?php endwhile; ?>
		<?php else : ?>
			<p class="y-u-text-center y-u-text-muted">WooCommerce غير مفعّل.</p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
?>
