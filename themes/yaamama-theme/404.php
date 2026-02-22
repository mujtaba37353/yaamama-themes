<?php
get_header();
$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';
?>

<main class="special-bg">
	<section class="not-found">
		<div class="container y-u-max-w-1200 y-u-mx-auto y-u-py-40 y-u-flex y-u-items-center y-u-justify-center y-u-gap-24">
			<img src="<?php echo esc_url( $assets_uri . '/404.png' ); ?>" alt="صفحة غير موجودة (404)" />
			<p class="u-font-bold y-u-text-xxl">Page Not Found</p>
		</div>
	</section>
</main>

<?php
get_footer();
?>
