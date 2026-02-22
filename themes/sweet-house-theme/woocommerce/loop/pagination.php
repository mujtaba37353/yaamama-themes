<?php
/**
 * Pagination for shop — Sweet House design (numbers + prev/next).
 *
 * @package Sweet_House_Theme
 * @see     design: sweet-house/components/products/y-c-pagination.html
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? (int) $total : (int) wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? (int) $current : (int) wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}

$current = max( 1, min( $current, $total ) );
$prev_url = ( $current > 1 ) ? get_pagenum_link( $current - 1 ) : '';
$next_url = ( $current < $total ) ? get_pagenum_link( $current + 1 ) : '';
?>
<?php
// Markup matches design: sweet-house/components/products/y-c-pagination.html
?>
<div class="pagination" role="navigation" aria-label="<?php esc_attr_e( 'ترقيم المنتجات', 'sweet-house-theme' ); ?>">
	<?php if ( $next_url ) : ?>
		<a href="<?php echo esc_url( $next_url ); ?>" class="pagination-next" aria-label="<?php esc_attr_e( 'الصفحة التالية', 'sweet-house-theme' ); ?>">
			<i class="fa fa-chevron-right" aria-hidden="true"></i>
		</a>
	<?php else : ?>
		<button type="button" class="pagination-next" disabled aria-label="<?php esc_attr_e( 'الصفحة التالية', 'sweet-house-theme' ); ?>">
			<i class="fa fa-chevron-right" aria-hidden="true"></i>
		</button>
	<?php endif; ?>

	<ul class="pagination-list">
		<?php for ( $i = 1; $i <= $total; $i++ ) : ?>
			<?php
			$url = ( 1 === $i ) ? get_pagenum_link( 1 ) : get_pagenum_link( $i );
			$is_current = ( (int) $i === (int) $current );
			?>
			<li class="pagination-item<?php echo $is_current ? ' active' : ''; ?>">
				<?php if ( $is_current ) : ?>
					<span class="current" aria-current="page"><?php echo (int) $i; ?></span>
				<?php else : ?>
					<a href="<?php echo esc_url( $url ); ?>"><?php echo (int) $i; ?></a>
				<?php endif; ?>
			</li>
		<?php endfor; ?>
	</ul>

	<?php if ( $prev_url ) : ?>
		<a href="<?php echo esc_url( $prev_url ); ?>" class="pagination-prev" aria-label="<?php esc_attr_e( 'الصفحة السابقة', 'sweet-house-theme' ); ?>">
			<i class="fa fa-chevron-left" aria-hidden="true"></i>
		</a>
	<?php else : ?>
		<button type="button" class="pagination-prev" disabled aria-label="<?php esc_attr_e( 'الصفحة السابقة', 'sweet-house-theme' ); ?>">
			<i class="fa fa-chevron-left" aria-hidden="true"></i>
		</button>
	<?php endif; ?>
</div>
