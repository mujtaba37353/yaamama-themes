<?php
/**
 * Template Name: صفحة المفضلة
 * Template for displaying wishlist page
 *
 * @package KhutaaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';

// Enqueue wishlist page specific styles
wp_enqueue_style( 'khutaa-product-card', $khutaa_uri . '/components/cards/y-c-product-card.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-btn', $khutaa_uri . '/components/buttons/y-c-btn.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-design-header', $khutaa_uri . '/templates/pages header/y-c-design-header.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-products', $khutaa_uri . '/components/products/y-c-products.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-pagination', $khutaa_uri . '/components/products/y-c-pagination.css', array(), '1.0.0' );

// Enqueue scripts
wp_enqueue_script( 'khutaa-design-header', $khutaa_uri . '/js/y-design-header.js', array(), '1.0.0', true );
wp_enqueue_script( 'khutaa-wishlist', $khutaa_uri . '/js/khutaa-wishlist.js', array( 'jquery' ), '1.0.0', true );

// Get banner image
$banner_2_image = khutaa_get_demo_content( 'khutaa_banner_2_image' );
$default_banner = $khutaa_uri . '/assets/design.png';

// Get wishlist products for current user
$wishlist_product_ids = array();

if ( is_user_logged_in() ) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'khutaa_wishlist';
	$user_id = get_current_user_id();
	
	// Ensure table exists
	if ( function_exists( 'khutaa_check_wishlist_table' ) ) {
		khutaa_check_wishlist_table();
	}
	
	// Check if table exists before querying
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) == $table_name ) {
		$wishlist_items = $wpdb->get_col( $wpdb->prepare(
			"SELECT product_id FROM $table_name WHERE user_id = %d ORDER BY date_added DESC",
			$user_id
		) );
		
		if ( ! empty( $wishlist_items ) ) {
			$wishlist_product_ids = array_map( 'intval', $wishlist_items );
		}
	}
}

// Setup query for wishlist products
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

// If no wishlist products, create empty query
if ( empty( $wishlist_product_ids ) ) {
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => 12,
		'paged'          => $paged,
		'post__in'       => array( 0 ), // Return no results
	);
} else {
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => 12,
		'paged'          => $paged,
		'post__in'       => $wishlist_product_ids,
		'post_status'    => 'publish',
		'orderby'        => 'post__in',
		'order'          => 'ASC',
	);
}

$wishlist_query = new WP_Query( $args );
?>

<header class="design-header">
	<?php if ( $banner_2_image ) : ?>
		<img src="<?php echo esc_url( $banner_2_image ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
	<?php else : ?>
		<img src="<?php echo esc_url( $default_banner ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
	<?php endif; ?>
</header>

<main id="main" class="y-u-container">
	<?php if ( is_user_logged_in() ) : ?>
		<?php if ( $wishlist_query->have_posts() ) : ?>
			<ul class="products y-u-my-10">
				<?php
				while ( $wishlist_query->have_posts() ) :
					$wishlist_query->the_post();
					wc_get_template_part( 'content', 'product' );
				endwhile;
				wp_reset_postdata();
				?>
			</ul>

			<?php
			// Pagination
			$total_pages = $wishlist_query->max_num_pages;
			if ( $total_pages > 1 ) {
				$current_page = max( 1, $paged );
				?>
				<div class="pagination y-u-my-5">
					<?php
					echo paginate_links( array(
						'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
						'format'    => '?paged=%#%',
						'current'   => $current_page,
						'total'     => $total_pages,
						'prev_text' => '<i class="fa-solid fa-angle-right"></i>',
						'next_text' => '<i class="fa-solid fa-angle-left"></i>',
						'type'      => 'list',
					) );
					?>
				</div>
				<?php
			}
			?>
		<?php else : ?>
			<div class="y-u-my-10" style="text-align: center; padding: 3rem 0;">
				<h2 style="font-size: 2rem; color: #666;"><?php esc_html_e( 'لا توجد منتجات في المفضلة حالياً', 'khutaa-theme' ); ?></h2>
				<p style="font-size: 1.2rem; color: #999; margin-top: 1rem;">
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-primary" style="display: inline-block; margin-top: 1rem;">
						<?php esc_html_e( 'تصفح المنتجات', 'khutaa-theme' ); ?>
					</a>
				</p>
			</div>
		<?php endif; ?>
	<?php else : ?>
		<div class="y-u-my-10" style="text-align: center; padding: 3rem 0;">
			<h2 style="font-size: 2rem; color: #666;"><?php esc_html_e( 'يجب تسجيل الدخول لعرض المفضلة', 'khutaa-theme' ); ?></h2>
			<p style="font-size: 1.2rem; color: #999; margin-top: 1rem;">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="btn-primary" style="display: inline-block; margin-top: 1rem;">
					<?php esc_html_e( 'تسجيل الدخول', 'khutaa-theme' ); ?>
				</a>
			</p>
		</div>
	<?php endif; ?>
</main>

<?php
get_footer( 'shop' );
