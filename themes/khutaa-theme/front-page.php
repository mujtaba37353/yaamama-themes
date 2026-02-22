<?php
/**
 * Front Page Template
 *
 * @package KhutaaTheme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';

// Enqueue home-specific styles
wp_enqueue_style( 'khutaa-header', $khutaa_uri . '/components/home/y-c-header.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-section', $khutaa_uri . '/components/home/y-c-section.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-product-card', $khutaa_uri . '/components/cards/y-c-product-card.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-btn', $khutaa_uri . '/components/buttons/y-c-btn.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-design-header', $khutaa_uri . '/templates/pages header/y-c-design-header.css', array(), '1.0.0' );

// Enqueue scripts
wp_enqueue_script( 'khutaa-header', $khutaa_uri . '/js/y-header.js', array( 'jquery' ), '1.0.0', true );
wp_enqueue_script( 'khutaa-shoes-section', $khutaa_uri . '/js/y-shoes-section.js', array( 'jquery' ), '1.0.0', true );
wp_enqueue_script( 'khutaa-bag-section', $khutaa_uri . '/js/y-bag-section.js', array( 'jquery' ), '1.0.0', true );
wp_enqueue_script( 'khutaa-design-header', $khutaa_uri . '/js/y-design-header.js', array(), '1.0.0', true );

// Get demo content
$hero_image = khutaa_get_demo_content( 'khutaa_hero_image' );
$hero_title = khutaa_get_demo_content( 'khutaa_hero_title' );
$hero_content = khutaa_get_demo_content( 'khutaa_hero_content' );

// Get products for shoes section
$shoes_args = array(
	'post_type'      => 'product',
	'posts_per_page' => 6,
	'orderby'        => 'date',
	'order'          => 'DESC',
);
$shoes_term = get_term_by( 'slug', 'shoes', 'product_cat' );
if ( $shoes_term ) {
	$shoes_args['tax_query'] = array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $shoes_term->term_id,
		),
	);
}
$shoes_products = new WP_Query( $shoes_args );

// Get products for bags section
$bags_args = array(
	'post_type'      => 'product',
	'posts_per_page' => 6,
	'orderby'        => 'date',
	'order'          => 'DESC',
);
$bags_term = get_term_by( 'slug', 'bags', 'product_cat' );
if ( $bags_term ) {
	$bags_args['tax_query'] = array(
		array(
			'taxonomy' => 'product_cat',
			'field'    => 'term_id',
			'terms'    => $bags_term->term_id,
		),
	);
}
$bags_products = new WP_Query( $bags_args );
?>

<main id="main">
	<!-- Hero/Header Section -->
	<header class="header">
		<div class="content">
			<?php if ( $hero_title ) : ?>
				<h1><?php echo esc_html( $hero_title ); ?></h1>
			<?php endif; ?>
			<?php if ( $hero_content ) : ?>
				<p><?php echo esc_html( $hero_content ); ?></p>
			<?php endif; ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-primary"><?php esc_html_e( 'اطلب الآن', 'khutaa-theme' ); ?></a>
		</div>

		<div class="image-wrapper">
			<?php if ( $hero_image ) : ?>
				<img src="<?php echo esc_url( $hero_image ); ?>" alt="<?php echo esc_attr( $hero_title ); ?>" />
			<?php else : ?>
				<img src="<?php echo esc_url( $khutaa_uri . '/assets/header sec.png' ); ?>" alt="<?php esc_attr_e( 'منتجات', 'khutaa-theme' ); ?>" />
			<?php endif; ?>
		</div>
	</header>

	<!-- Shoes Section -->
	<?php 
	$has_shoes_products = $shoes_products->have_posts();
	// Get demo products if no WooCommerce products
	$demo_shoes = array();
	if ( ! $has_shoes_products ) {
		for ( $i = 1; $i <= 3; $i++ ) {
			$demo_shoes[] = array(
				'image' => khutaa_get_demo_content( "khutaa_shoes_{$i}_image" ),
				'title' => khutaa_get_demo_content( "khutaa_shoes_{$i}_title" ),
				'price' => khutaa_get_demo_content( "khutaa_shoes_{$i}_price" ),
			);
		}
		// Duplicate demo products to show 6 items (2x3)
		$demo_shoes = array_merge( $demo_shoes, $demo_shoes );
	}
	if ( $has_shoes_products || ! empty( $demo_shoes ) ) : ?>
		<div class="section-header">
			<h2 class="section-title"><?php esc_html_e( 'أحذية', 'khutaa-theme' ); ?></h2>
			<?php if ( $shoes_term ) : ?>
				<a href="<?php echo esc_url( get_term_link( $shoes_term ) ); ?>" class="see-all">
					<?php esc_html_e( 'عرض الكل', 'khutaa-theme' ); ?> <i class="fa-solid fa-arrow-left"></i>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="see-all">
					<?php esc_html_e( 'عرض الكل', 'khutaa-theme' ); ?> <i class="fa-solid fa-arrow-left"></i>
				</a>
			<?php endif; ?>
		</div>
		<div class="section shoes-section">
			<ul class="products">
				<?php if ( $has_shoes_products ) : ?>
					<?php while ( $shoes_products->have_posts() ) : $shoes_products->the_post(); ?>
						<?php global $product; ?>
						<li class="product-card">
							<?php
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
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<?php foreach ( $demo_shoes as $demo_product ) : ?>
						<?php if ( ! empty( $demo_product['image'] ) && ! empty( $demo_product['title'] ) ) : ?>
							<li class="product-card">
								<button class="wishlist-btn" aria-label="<?php esc_attr_e( 'إضافة للمفضلة', 'khutaa-theme' ); ?>">
									<i class="fa-regular fa-heart"></i>
								</button>
								<div class="product-card-img">
									<img src="<?php echo esc_url( $demo_product['image'] ); ?>" alt="<?php echo esc_attr( $demo_product['title'] ); ?>" />
								</div>
								<div class="product-card-info">
									<h3 class="product-card-title"><?php echo esc_html( $demo_product['title'] ); ?></h3>
									<span class="product-card-price"><?php echo esc_html( $demo_product['price'] ); ?> <?php echo get_woocommerce_currency_symbol(); ?></span>
								</div>
								<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-primary"><?php esc_html_e( 'أضف إلى السلة', 'khutaa-theme' ); ?></a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
			<div class="img-container">
				<img src="<?php echo esc_url( $khutaa_uri . '/assets/shoes-section.png' ); ?>" alt="<?php esc_attr_e( 'الأحذية', 'khutaa-theme' ); ?>" />
			</div>
		</div>
	<?php endif; ?>

	<!-- Design Header / Banner Section -->
	<?php
	$banner_1_image = khutaa_get_demo_content( 'khutaa_banner_1_image' );
	$banner_1_title = khutaa_get_demo_content( 'khutaa_banner_1_title' );
	$banner_1_link = khutaa_get_demo_content( 'khutaa_banner_1_link' );
	$default_banner = $khutaa_uri . '/assets/design.png';
	if ( $banner_1_image || $banner_1_title ) :
		?>
		<div class="design-header">
			<?php if ( $banner_1_link ) : ?>
				<a href="<?php echo esc_url( $banner_1_link ); ?>">
			<?php endif; ?>
				<?php if ( $banner_1_image ) : ?>
					<img src="<?php echo esc_url( $banner_1_image ); ?>" alt="<?php echo esc_attr( $banner_1_title ); ?>" class="design-img y-u-w-100" />
				<?php else : ?>
					<img src="<?php echo esc_url( $default_banner ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
				<?php endif; ?>
				<?php if ( $banner_1_title ) : ?>
					<h2><?php echo esc_html( $banner_1_title ); ?></h2>
				<?php endif; ?>
			<?php if ( $banner_1_link ) : ?>
				</a>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<!-- Bags Section -->
	<?php 
	$has_bags_products = $bags_products->have_posts();
	// Get demo products if no WooCommerce products
	$demo_bags = array();
	if ( ! $has_bags_products ) {
		for ( $i = 1; $i <= 3; $i++ ) {
			$demo_bags[] = array(
				'image' => khutaa_get_demo_content( "khutaa_bags_{$i}_image" ),
				'title' => khutaa_get_demo_content( "khutaa_bags_{$i}_title" ),
				'price' => khutaa_get_demo_content( "khutaa_bags_{$i}_price" ),
			);
		}
		// Duplicate demo products to show 6 items (2x3)
		$demo_bags = array_merge( $demo_bags, $demo_bags );
	}
	if ( $has_bags_products || ! empty( $demo_bags ) ) : ?>
		<div class="section-header">
			<h2 class="section-title"><?php esc_html_e( 'الشنط', 'khutaa-theme' ); ?></h2>
			<?php if ( $bags_term ) : ?>
				<a href="<?php echo esc_url( get_term_link( $bags_term ) ); ?>" class="see-all">
					<?php esc_html_e( 'عرض الكل', 'khutaa-theme' ); ?> <i class="fa-solid fa-arrow-left"></i>
				</a>
			<?php else : ?>
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="see-all">
					<?php esc_html_e( 'عرض الكل', 'khutaa-theme' ); ?> <i class="fa-solid fa-arrow-left"></i>
				</a>
			<?php endif; ?>
		</div>
		<div class="section bag-section">
			<ul class="products">
				<?php if ( $has_bags_products ) : ?>
					<?php while ( $bags_products->have_posts() ) : $bags_products->the_post(); ?>
						<?php global $product; ?>
						<li class="product-card">
							<?php
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
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<?php foreach ( $demo_bags as $demo_product ) : ?>
						<?php if ( ! empty( $demo_product['image'] ) && ! empty( $demo_product['title'] ) ) : ?>
							<li class="product-card">
								<button class="wishlist-btn" aria-label="<?php esc_attr_e( 'إضافة للمفضلة', 'khutaa-theme' ); ?>">
									<i class="fa-regular fa-heart"></i>
								</button>
								<div class="product-card-img">
									<img src="<?php echo esc_url( $demo_product['image'] ); ?>" alt="<?php echo esc_attr( $demo_product['title'] ); ?>" />
								</div>
								<div class="product-card-info">
									<h3 class="product-card-title"><?php echo esc_html( $demo_product['title'] ); ?></h3>
									<span class="product-card-price"><?php echo esc_html( $demo_product['price'] ); ?> <?php echo get_woocommerce_currency_symbol(); ?></span>
								</div>
								<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="btn-primary"><?php esc_html_e( 'أضف إلى السلة', 'khutaa-theme' ); ?></a>
							</li>
						<?php endif; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
			<div class="img-container">
				<img src="<?php echo esc_url( $khutaa_uri . '/assets/bag-section.png' ); ?>" alt="<?php esc_attr_e( 'الشنط', 'khutaa-theme' ); ?>" />
			</div>
		</div>
	<?php endif; ?>
</main>

<?php
get_footer();
