<?php
/**
 * Result Count — override
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;
?>
<p class="woocommerce-result-count">
	<?php
	$total    = $total ? $total : wc_get_loop_prop( 'total' );
	$per_page = $per_page ? $per_page : wc_get_loop_prop( 'per_page' );
	$current  = $current ? $current : wc_get_loop_prop( 'current_page' );
	$first    = ( $current - 1 ) * $per_page + 1;
	$last     = min( $current * $per_page, $total );

	if ( $total <= $per_page || -1 === $per_page ) {
		printf(
			/* translators: %d: total results */
			_n( 'عرض %d منتج', 'عرض %d منتجات', $total, 'beauty-time-theme' ),
			$total
		);
	} else {
		printf(
			/* translators: 1: first result 2: last result 3: total results */
			_nx( 'عرض %1$d–%2$d من %3$d منتج', 'عرض %1$d–%2$d من %3$d منتجات', $total, 'with first and last result', 'beauty-time-theme' ),
			$first,
			$last,
			$total
		);
	}
	?>
</p>
