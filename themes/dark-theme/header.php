<?php
$current_page = function_exists( 'dark_theme_current_page_key' ) ? dark_theme_current_page_key() : '';
$is_auth_page = in_array( $current_page, array( 'login', 'signup', 'reset-password' ), true ) || is_page( 'forget-password' );
$shop_url     = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : dark_theme_get_page_url( 'shop' );
$cart_url     = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : dark_theme_get_page_url( 'cart' );
$checkout_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'checkout' ) : dark_theme_get_page_url( 'payment' );

// التصنيفات الحقيقية من WooCommerce (للـ dropdown) — استبعاد uncategorized
$product_categories = array();
if ( taxonomy_exists( 'product_cat' ) ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'parent'     => 0,
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);
	if ( ! is_wp_error( $terms ) && is_array( $terms ) ) {
		$default_cat_id = (int) get_option( 'default_product_cat' );
		foreach ( $terms as $cat ) {
			if ( 'uncategorized' === $cat->slug || $cat->term_id === $default_cat_id ) {
				continue;
			}
			$product_categories[] = $cat;
		}
	}
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" href="<?php echo esc_url( dark_theme_asset_uri( 'assets/icon.png' ) ); ?>" type="image/x-icon" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-current-page="<?php echo esc_attr( $current_page ); ?>">
	<?php wp_body_open(); ?>
	<div data-y="nav">
		<nav class="navbar">
			<div class="logo-container">
				<?php if ( ! $is_auth_page ) : ?>
				<form class="search-container" action="<?php echo esc_url( $shop_url ); ?>" method="get" role="search">
					<input type="search" class="search-input" name="s" placeholder="بحث..." value="<?php echo esc_attr( get_search_query() ); ?>" />
					<input type="hidden" name="post_type" value="product" />
					<button type="submit" class="search-icon-btn" aria-label="بحث"><i class="fa fa-search search-icon"></i></button>
				</form>
				<?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link" aria-label="العودة للرئيسية">
					<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/logo.png' ) ); ?>" alt="Logo" class="logo" />
				</a>
			</div>

			<div class="links">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" data-page="home">الرئيسية</a>
				<div class="dropdown">
					<a href="<?php echo esc_url( $shop_url ); ?>" data-page="products">
						المنتجات
						<i class="fa-solid fa-angle-down dropdown-arrow"></i>
					</a>
					<div class="dropdown-content">
						<a href="<?php echo esc_url( $shop_url ); ?>">جميع المنتجات</a>
						<?php foreach ( $product_categories as $cat ) : ?>
							<a href="<?php echo esc_url( add_query_arg( 'category', $cat->slug, $shop_url ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
				<a href="<?php echo esc_url( dark_theme_get_page_url( 'offers' ) ); ?>" data-page="offers">العروض</a>
				<a href="<?php echo esc_url( dark_theme_get_page_url( 'contact' ) ); ?>" data-page="contact">تواصل معنا</a>
			</div>

			<div class="icons">
				<?php if ( ! $is_auth_page ) : ?>
				<form class="search-container" action="<?php echo esc_url( $shop_url ); ?>" method="get" role="search">
					<input type="search" class="search-input" name="s" placeholder="بحث..." value="<?php echo esc_attr( get_search_query() ); ?>" />
					<input type="hidden" name="post_type" value="product" />
					<button type="submit" class="search-icon-btn" aria-label="بحث"><i class="fa fa-search search-icon"></i></button>
				</form>
				<?php endif; ?>
				<a href="<?php echo esc_url( dark_theme_get_page_url( 'wishlist' ) ); ?>"><i class="fa-regular fa-heart"></i></a>
				<a href="<?php echo esc_url( dark_theme_get_page_url( 'login' ) ); ?>"><i class="fa-regular fa-user"></i></a>
				<a href="<?php echo esc_url( $cart_url ); ?>"><i class="fa-solid fa-bag-shopping"></i></a>
				<button class="hamburger-menu" aria-label="Toggle menu">
					<i class="fa-solid fa-bars"></i>
				</button>
			</div>

			<div class="mobile-menu-overlay"></div>

			<div class="mobile-menu">
				<div class="mobile-menu-header">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link" aria-label="العودة للرئيسية">
						<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/logo.png' ) ); ?>" alt="Logo" class="mobile-menu-logo" />
					</a>
					<button class="mobile-menu-close" aria-label="Close menu">
						<i class="fa-solid fa-times"></i>
					</button>
				</div>
				<div class="mobile-menu-links">
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" data-page="home">الرئيسية</a>

					<div class="mobile-dropdown">
						<div class="mobile-dropdown-toggle">
							<span>المنتجات</span>
							<i class="fa-solid fa-angle-down mobile-dropdown-arrow"></i>
						</div>
						<div class="mobile-dropdown-content">
							<a href="<?php echo esc_url( $shop_url ); ?>">جميع المنتجات</a>
							<?php foreach ( $product_categories as $cat ) : ?>
								<a href="<?php echo esc_url( add_query_arg( 'category', $cat->slug, $shop_url ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
							<?php endforeach; ?>
						</div>
					</div>

					<a href="<?php echo esc_url( dark_theme_get_page_url( 'offers' ) ); ?>" data-page="offers">العروض</a>
					<a href="<?php echo esc_url( dark_theme_get_page_url( 'contact' ) ); ?>" data-page="contact">تواصل معنا</a>
				</div>
			</div>
		</nav>
	</div>
