<?php
/**
 * Product card template — used in shop loop
 * Markup from beauty-time/components/product-card.html
 * For category pages, uses sub-services card style
 *
 * @package Beauty_Time_Theme
 * @var WC_Product $product
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product ) {
	return;
}

$image_id  = $product->get_image_id();
$image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_thumbnail' ) : wc_placeholder_img_src();
$permalink = get_permalink();
$title     = $product->get_name();
$price     = $product->get_price_html();
$sale      = $product->is_on_sale();
$regular   = $sale ? $product->get_regular_price() : '';
$sale_price = $sale ? $product->get_sale_price() : $product->get_price();
$short_desc = $product->get_short_description();
$is_bundle = has_term( 'عروض وباقات بيوتي', 'product_cat', $product->get_id() );
$is_onsale_page = is_page_template( 'page-templates/onsale.php' ) || is_page( 'onsale' );
$show_bundle_info = ( $is_bundle || $is_onsale_page ) && $sale && $short_desc;
$is_category_page = is_product_category();
$booking_url = home_url( '/booking' );
$booking_query = array( 'product_id' => $product->get_id() );
$booking_link = add_query_arg( $booking_query, $booking_url );
$add_to_cart_label = __( 'احجز الآن', 'beauty-time-theme' );
$add_to_cart_icon = beauty_time_asset( 'assets/book-now.svg' );
?>
<?php if ( $is_category_page ) : ?>
	<div class="card">
		<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
		<p><?php echo esc_html( $title ); ?></p>
		<p><?php echo esc_html( $sale_price ); ?> <img src="<?php echo esc_url( beauty_time_asset( 'assets/ryal-prim.svg' ) ); ?>" alt="sar"></p>
		<a href="<?php echo esc_url( $booking_link ); ?>" class="btn rounded full"><i class="fas fa-calendar-alt"></i><?php esc_html_e( 'احجز الآن', 'beauty-time-theme' ); ?></a>
	</div>
<?php else : ?>
	<div <?php wc_product_class( 'product-card', $product ); ?>>
		<?php if ( $is_onsale_page ) : ?>
		<div class="product-img">
		<?php else : ?>
		<a href="<?php echo esc_url( $permalink ); ?>" class="product-img">
		<?php endif; ?>
			<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $title ); ?>">
			<?php if ( $show_bundle_info ) : ?>
			<div class="infos" role="region" aria-label="<?php esc_attr_e( 'تفاصيل العرض', 'beauty-time-theme' ); ?>">
				<h3 class="offer-title"><?php echo esc_html( $title ); ?></h3>
				<?php if ( $short_desc ) : ?>
				<ul class="info" role="list">
					<?php
					$items = explode( '،', $short_desc );
					foreach ( array_slice( $items, 0, 4 ) as $item ) :
						$item = trim( $item );
						if ( ! $item ) {
							continue;
						}
						?>
					<li>
						<i class="fas fa-star" aria-hidden="true"></i>
						<span><?php echo esc_html( $item ); ?></span>
					</li>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</div>
			<div class="logo">
				<img src="<?php echo esc_url( beauty_time_asset( 'assets/navbar-icon.png' ) ); ?>" alt="logo">
			</div>
			<?php endif; ?>
		<?php if ( $is_onsale_page ) : ?>
		</div>
		<?php else : ?>
		</a>
		<?php endif; ?>
		<div class="price">
			<?php if ( $sale && $regular ) : ?>
			<p><?php echo esc_html( $sale_price ); ?> <img src="<?php echo esc_url( beauty_time_asset( 'assets/ryal-prim.svg' ) ); ?>" alt="sar"></p>
			<p class="old-price"><?php echo esc_html( $regular ); ?> <img src="<?php echo esc_url( beauty_time_asset( 'assets/ryal-prim.svg' ) ); ?>" alt="sar"></p>
			<?php else : ?>
			<p><?php echo esc_html( $sale_price ); ?> <img src="<?php echo esc_url( beauty_time_asset( 'assets/ryal-prim.svg' ) ); ?>" alt="sar"></p>
			<?php endif; ?>
		</div>
		<p class="product-title"><?php echo esc_html( $title ); ?></p>
		<?php if ( $is_onsale_page ) : ?>
			<?php
			?>
			<a href="<?php echo esc_url( $booking_link ); ?>" class="btn full">
				<img src="<?php echo esc_url( $add_to_cart_icon ); ?>" alt="book-now">
				<?php echo esc_html( $add_to_cart_label ); ?>
			</a>
		<?php else : ?>
			<?php
			/**
			 * Hook: woocommerce_after_shop_loop_item
			 */
			do_action( 'woocommerce_after_shop_loop_item' );
			?>
		<?php endif; ?>
	</div>
<?php endif; ?>
