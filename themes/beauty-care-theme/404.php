<?php
get_header();

$assets_uri = get_template_directory_uri() . '/beauty-care/assets';
?>

<main class="error-main y-u-flex y-u-justify-center y-u-items-center y-u-text-center">
	<div class="container">
		<div class="error-content y-u-max-w-600">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $assets_uri . '/404.png' ); ?>" alt="<?php esc_attr_e( 'الصفحة غير موجودة', 'beauty-care-theme' ); ?>"></a>
		</div>
	</div>
</main>

<?php
get_footer();
