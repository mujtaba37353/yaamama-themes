<?php
get_header();
$assets_uri = stationary_base_uri() . '/assets';
$img_404   = $assets_uri . '/404.png';
if ( ! file_exists( get_template_directory() . '/' . STATIONARY_DS . '/assets/404.png' ) ) {
	$img_404 = ''; // fallback: no image or use placeholder
}
?>

<main class="error-main y-u-flex y-u-justify-center y-u-items-center y-u-text-center">
	<div class="container">
		<div class="error-content y-u-max-w-600 y-u-p-t-32">
			<?php if ( $img_404 ) : ?>
				<img src="<?php echo esc_url( $img_404 ); ?>" alt="<?php esc_attr_e( 'صفحة غير موجودة - خطأ 404', 'stationary-theme' ); ?>">
			<?php else : ?>
				<p class="error-code">404</p>
				<h1 class="error-title"><?php esc_html_e( 'الصفحة غير موجودة', 'stationary-theme' ); ?></h1>
				<p class="error-message"><?php esc_html_e( 'عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها.', 'stationary-theme' ); ?></p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn primary-button"><?php esc_html_e( 'العودة للرئيسية', 'stationary-theme' ); ?></a>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php
get_footer();
