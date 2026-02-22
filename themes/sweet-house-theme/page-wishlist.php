<?php
/**
 * Template Name: المفضلة
 * Wishlist page — Sweet House design.
 * Shows favorite products or empty state.
 *
 * @package Sweet_House_Theme
 * @see     design: sweet-house/templates/wishlist/layout.html, empty-favourite/layout.html
 */

get_header();

$wishlist_ids    = function_exists( 'sweet_house_get_wishlist_ids' ) ? sweet_house_get_wishlist_ids() : array();
$wishlist_posts  = array();
$asset_uri       = function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( '' ) : get_template_directory_uri() . '/sweet-house/';

if ( ! empty( $wishlist_ids ) ) {
	$wishlist_posts = get_posts(
		array(
			'post_type'   => 'product',
			'post__in'    => $wishlist_ids,
			'orderby'     => 'post__in',
			'numberposts' => -1,
			'post_status' => 'publish',
		)
	);
}

while ( have_posts() ) :
	the_post();
	?>
<header data-y="design-header" class="wishlist-design-header">
	<img src="<?php echo esc_url( $asset_uri . 'assets/panner.png' ); ?>" alt="<?php esc_attr_e( 'بانر سويت هاوس - المفضلة', 'sweet-house-theme' ); ?>" class="panner-img" />
</header>

<?php if ( ! empty( $wishlist_posts ) ) : ?>
<main data-y="main" class="wishlist-page">
	<div class="y-u-my-10">
		<div class="main-container">
			<nav data-y="breadcrumb" class="y-breadcrumb-container" aria-label="<?php esc_attr_e( 'مسار التنقل', 'sweet-house-theme' ); ?>">
				<ol class="y-breadcrumb">
					<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
					<li class="y-breadcrumb-item active"><?php esc_html_e( 'المفضلة', 'sweet-house-theme' ); ?></li>
				</ol>
			</nav>
			<ul class="products" data-y="wishlist-products">
				<?php
				foreach ( $wishlist_posts as $post ) {
					$product = wc_get_product( $post->ID );
					if ( $product && $product->is_visible() ) {
						$GLOBALS['product'] = $product;
						wc_get_template( 'content-product.php' );
					}
				}
				wp_reset_postdata();
				?>
			</ul>
		</div>
	</div>
</main>
<?php else : ?>
<main data-y="main" class="not-found-container">
	<div class="main-container">
		<nav data-y="breadcrumb" class="y-breadcrumb-container" aria-label="<?php esc_attr_e( 'مسار التنقل', 'sweet-house-theme' ); ?>">
			<ol class="y-breadcrumb">
				<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
				<li class="y-breadcrumb-item active"><?php esc_html_e( 'المفضلة', 'sweet-house-theme' ); ?></li>
			</ol>
		</nav>
		<div class="not-found-content">
			<img src="<?php echo esc_url( $asset_uri . 'assets/empty-fav.png' ); ?>" alt="<?php esc_attr_e( 'المفضلة فارغة', 'sweet-house-theme' ); ?>" class="not-found-img" />
			<p class="not-found-text">
				<?php esc_html_e( 'قائمة المفضلة فارغة، لم تقم بإضافة أي منتجات إلى قائمة المفضلة الخاصة بك بعد.', 'sweet-house-theme' ); ?>
			</p>
			<?php
			$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
			?>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="btn-back">
				<?php esc_html_e( 'تصفح المنتجات', 'sweet-house-theme' ); ?> <i class="fa-solid fa-store"></i>
			</a>
		</div>
		<?php
		$args     = array(
			'post_type'      => 'product',
			'posts_per_page' => 8,
			'post_status'    => 'publish',
		);
		$featured = get_posts( array_merge( $args, array( 'meta_key' => '_featured', 'meta_value' => 'yes', 'orderby' => 'menu_order title', 'order' => 'ASC' ) ) );
		if ( empty( $featured ) ) {
			$featured = get_posts( array_merge( $args, array( 'orderby' => 'date', 'order' => 'DESC' ) ) );
		}
		if ( ! empty( $featured ) ) :
			?>
		<div class="pro">
			<h1 class="section-title"><?php esc_html_e( 'تسوق أكثر', 'sweet-house-theme' ); ?></h1>
			<ul class="products">
				<?php
				foreach ( $featured as $post ) {
					$product = wc_get_product( $post->ID );
					if ( $product && $product->is_visible() ) {
						$GLOBALS['product'] = $product;
						wc_get_template( 'content-product.php' );
					}
				}
				wp_reset_postdata();
				?>
			</ul>
		</div>
		<?php endif; ?>
	</div>
</main>
<?php endif; ?>
<?php
endwhile;
get_footer();
