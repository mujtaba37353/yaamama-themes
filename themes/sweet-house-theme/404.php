<?php
/**
 * Template for 404 Not Found — Sweet House design.
 *
 * @package Sweet_House_Theme
 */

get_header();

$not_found_img_path = function_exists( 'sweet_house_asset_path' ) ? sweet_house_asset_path( 'assets/404.png' ) : '';
$not_found_img_url = function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( 'assets/404.png' ) : '';
$has_404_img       = $not_found_img_path && file_exists( $not_found_img_path );
$shop_url          = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' );
?>

<main data-y="main" class="main-page main-page-404">
	<div class="main-container">
		<div class="not-found-container">
			<?php if ( $has_404_img ) : ?>
				<img src="<?php echo esc_url( $not_found_img_url ); ?>" alt="<?php esc_attr_e( 'صفحة غير موجودة - خطأ 404', 'sweet-house-theme' ); ?>" />
			<?php else : ?>
				<div class="not-found-404-number" aria-hidden="true">404</div>
			<?php endif; ?>
			<p>
				<?php esc_html_e( 'عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها. يمكنك العودة للرئيسية أو تصفح المتجر.', 'sweet-house-theme' ); ?>
			</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-auth"><?php esc_html_e( 'العودة إلى الرئيسية', 'sweet-house-theme' ); ?></a>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="btn-auth btn-auth-secondary"><?php esc_html_e( 'تصفح المنتجات', 'sweet-house-theme' ); ?></a>
		</div>
	</div>
</main>

<?php get_footer(); ?>
