<?php
/**
 * Pagination - تصميم مطابق لقالب المتجر
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$total   = isset( $total ) ? (int) $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? (int) $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

if ( $total <= 1 ) {
	return;
}

$assets = get_template_directory_uri() . '/mallati/assets';
$prev_url = ( $current > 1 ) ? str_replace( '%#%', $current - 1, $base ) : '';
$next_url = ( $current < $total ) ? str_replace( '%#%', $current + 1, $base ) : '';

// بناء قائمة الصفحات: 1، 2، ...، n-1، n (مثل التصميم)
$show_pages = array();
$show_pages[] = array( 'num' => 1 );
if ( $total >= 2 ) $show_pages[] = array( 'num' => 2 );
if ( $total > 6 && $current > 4 ) $show_pages[] = array( 'ellipsis' => true );
$mid_start = max( 3, $current - 1 );
$mid_end   = min( $total - 2, $current + 1 );
for ( $i = $mid_start; $i <= $mid_end; $i++ ) {
	if ( $i > 2 && $i < $total - 1 ) $show_pages[] = array( 'num' => $i );
}
if ( $total > 6 && $current < $total - 3 ) $show_pages[] = array( 'ellipsis' => true );
if ( $total > 2 ) $show_pages[] = array( 'num' => $total - 1 );
if ( $total > 1 ) $show_pages[] = array( 'num' => $total );
// إزالة التكرار والحفاظ على الترتيب
$seen = array();
$show_pages = array_filter( $show_pages, function ( $item ) use ( &$seen ) {
	if ( ! empty( $item['ellipsis'] ) ) return true;
	$n = $item['num'];
	if ( isset( $seen[ $n ] ) ) return false;
	$seen[ $n ] = true;
	return true;
} );
?>
<nav class="pagination woocommerce-pagination" aria-label="<?php esc_attr_e( 'Product Pagination', 'woocommerce' ); ?>">
	<?php if ( $prev_url ) : ?>
		<a href="<?php echo esc_url( $prev_url ); ?>" class="pagination-button pagination-prev" aria-label="<?php esc_attr_e( 'السابق', 'mallati-theme' ); ?>">
			<img src="<?php echo esc_url( $assets ); ?>/arrow-right.svg" alt="">
		</a>
	<?php else : ?>
		<span class="pagination-button pagination-prev disabled" aria-disabled="true">
			<img src="<?php echo esc_url( $assets ); ?>/arrow-right.svg" alt="">
		</span>
	<?php endif; ?>

	<?php foreach ( $show_pages as $item ) : ?>
		<?php if ( ! empty( $item['ellipsis'] ) ) : ?>
			<span class="pagination-ellipsis">...</span>
		<?php else :
			$p = $item['num'];
			$url = str_replace( '%#%', $p, $base );
			$active = ( $p === $current ) ? ' active' : '';
		?>
			<a href="<?php echo esc_url( $url ); ?>" class="pagination-button<?php echo $active; ?>"><?php echo (int) $p; ?></a>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php if ( $next_url ) : ?>
		<a href="<?php echo esc_url( $next_url ); ?>" class="pagination-button pagination-next" aria-label="<?php esc_attr_e( 'التالي', 'mallati-theme' ); ?>">
			<img src="<?php echo esc_url( $assets ); ?>/arrow-left.svg" alt="">
		</a>
	<?php else : ?>
		<span class="pagination-button pagination-next disabled" aria-disabled="true">
			<img src="<?php echo esc_url( $assets ); ?>/arrow-left.svg" alt="">
		</span>
	<?php endif; ?>
</nav>
