<?php
/**
 * Template Name: صفحة العروض
 * Template for displaying offers/sale products page
 *
 * @package KhutaaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';

// Enqueue offers page specific styles
wp_enqueue_style( 'khutaa-product-card', $khutaa_uri . '/components/cards/y-c-product-card.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-btn', $khutaa_uri . '/components/buttons/y-c-btn.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-design-header', $khutaa_uri . '/templates/pages header/y-c-design-header.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-offers', $khutaa_uri . '/components/offers/y-c-offers.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-products', $khutaa_uri . '/components/products/y-c-products.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-pagination', $khutaa_uri . '/components/products/y-c-pagination.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-offers-template', $khutaa_uri . '/templates/offers/offers.css', array(), '1.0.0' );

// Enqueue scripts
wp_enqueue_script( 'khutaa-design-header', $khutaa_uri . '/js/y-design-header.js', array(), '1.0.0', true );
wp_enqueue_script( 'khutaa-offers', $khutaa_uri . '/js/y-offers.js', array( 'jquery' ), '1.0.0', true );
wp_enqueue_script( 'khutaa-products', $khutaa_uri . '/js/y-products.js', array( 'jquery' ), '1.0.0', true );
wp_enqueue_script( 'khutaa-pagination', $khutaa_uri . '/js/y-pagination.js', array( 'jquery' ), '1.0.0', true );

// Get banner image
$banner_2_image = khutaa_get_demo_content( 'khutaa_banner_2_image' );
$default_banner = $khutaa_uri . '/assets/design.png';

// Get products on sale
$product_ids_on_sale = wc_get_product_ids_on_sale();

// Setup query for sale products
$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

// If no products on sale, create empty query
if ( empty( $product_ids_on_sale ) ) {
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
		'post__in'       => $product_ids_on_sale,
		'post_status'    => 'publish',
		'orderby'        => 'post__in',
		'order'          => 'ASC',
	);
}

$offers_query = new WP_Query( $args );
?>

<header class="design-header">
	<?php if ( $banner_2_image ) : ?>
		<img src="<?php echo esc_url( $banner_2_image ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
	<?php else : ?>
		<img src="<?php echo esc_url( $default_banner ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
	<?php endif; ?>
</header>

<main id="main" class="y-u-container">
	<?php if ( $offers_query->have_posts() ) : ?>
		<ul class="products y-u-my-10">
			<?php
			while ( $offers_query->have_posts() ) :
				$offers_query->the_post();
				global $product;

				if ( ! $product->is_on_sale() ) {
					continue;
				}

				// Calculate discount percentage
				$regular_price = $product->get_regular_price();
				$sale_price = $product->get_sale_price();
				$discount_percentage = 0;

				if ( $regular_price && $sale_price ) {
					$discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
				}
				?>

				<li class="product-card">
					<?php
					// Show offer badge if product is on sale
					if ( $product->is_on_sale() ) {
						// Calculate discount percentage
						$regular_price = $product->get_regular_price();
						$sale_price = $product->get_sale_price();
						
						// For variable products, get price range
						if ( $product->is_type( 'variable' ) ) {
							$regular_prices = $product->get_variation_prices( true );
							if ( ! empty( $regular_prices['regular_price'] ) ) {
								$regular_price = max( $regular_prices['regular_price'] );
								$sale_price = max( $regular_prices['price'] );
							}
						}
						
						$discount_percentage = 0;
						if ( $regular_price && $sale_price && $sale_price < $regular_price ) {
							$discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
						}
						
						if ( $discount_percentage > 0 ) {
							echo '<div class="offer-badge"><span>' . esc_html( $discount_percentage ) . '%</span></div>';
						}
					}
					
					$product_id = get_the_ID();
					$wishlist_class = 'fa-regular';
					if ( function_exists( 'khutaa_is_product_in_wishlist' ) && khutaa_is_product_in_wishlist( $product_id ) ) {
						$wishlist_class = 'fa-solid';
					}
					?>
					<button class="wishlist-btn" aria-label="<?php esc_attr_e( 'إضافة للمفضلة', 'khutaa-theme' ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
						<i class="<?php echo esc_attr( $wishlist_class ); ?> fa-heart"></i>
					</button>
					<div class="product-card-img">
						<a href="<?php echo esc_url( get_permalink() ); ?>">
							<?php echo woocommerce_get_product_thumbnail( 'woocommerce_thumbnail' ); ?>
						</a>
					</div>
					<div class="product-card-info">
						<h3 class="product-card-title">
							<a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a>
						</h3>
						<span class="product-card-price"><?php echo $product->get_price_html(); ?></span>
					</div>
					<?php
					echo sprintf(
						'<a href="%s" data-product_id="%s" class="button btn-primary add_to_cart_button ajax_add_to_cart product_type_%s">%s</a>',
						esc_url( $product->add_to_cart_url() ),
						esc_attr( $product_id ),
						esc_attr( $product->get_type() ),
						esc_html__( 'أضف إلى السلة', 'khutaa-theme' )
					);
					?>
				</li>

			<?php endwhile; ?>
		</ul>

		<?php
		// Pagination
		$pagination = paginate_links( array(
			'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'    => '',
			'current'   => max( 1, $paged ),
			'total'     => $offers_query->max_num_pages,
			'prev_text' => '<i class="fa fa-chevron-right"></i>',
			'next_text' => '<i class="fa fa-chevron-left"></i>',
			'type'      => 'list',
			'end_size'  => 3,
			'mid_size'  => 3,
		) );

		if ( $pagination ) {
			echo '<section class="pagination-section">';
			echo '<div class="pagination">' . $pagination . '</div>';
			echo '</section>';
		}

		wp_reset_postdata();
		?>

	<?php else : ?>
		<div style="text-align: center; padding: 4rem 2rem; min-height: 50vh; display: flex; align-items: center; justify-content: center;">
			<p class="no-offers" style="font-size: 2rem; font-weight: 600; color: #666; margin: 0;">
				<?php esc_html_e( 'لا توجد عروض متاحة حالياً', 'khutaa-theme' ); ?>
			</p>
		</div>
	<?php endif; ?>
</main>

<?php
get_footer( 'shop' );
