<?php
/**
 * Header: navbar (from design) + hero only on front page.
 *
 * @package Sweet_House_Theme
 */

$current_page   = function_exists( 'sweet_house_current_page_key' ) ? sweet_house_current_page_key() : '';
$shop_url       = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : sweet_house_get_page_url( 'shop' );
$navbar_logo    = function_exists( 'sweet_house_site_logo_url' ) ? sweet_house_site_logo_url() : sweet_house_asset_uri( 'assets/logo.png' );
$cart_url       = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'cart' ) : sweet_house_get_page_url( 'cart' );
$checkout_url   = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'checkout' ) : sweet_house_get_page_url( 'payment' );
$wishlist_url   = sweet_house_get_page_url( 'wishlist' );
$my_account_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : sweet_house_get_page_url( 'my-account' );
$cart_count     = ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
$recipes_url    = sweet_house_get_page_url( 'recepies' );
$about_url      = sweet_house_get_page_url( 'about-us' );
$about_fallback = sweet_house_get_page_url( 'about' );
if ( $about_url === home_url( '/about-us' ) && get_page_by_path( 'about' ) ) {
	$about_url = $about_fallback;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" href="<?php echo esc_url( sweet_house_asset_uri( 'assets/icon.png' ) ); ?>" type="image/x-icon" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?> data-current-page="<?php echo esc_attr( $current_page ); ?>">
	<?php wp_body_open(); ?>

	<div data-y="nav"></div>
	<header data-y="header" class="y-u-flex y-u-justify-center y-u-items-center y-u-fixed header y-u-top-left">
		<div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200 top">
			<div class="logo y-u-flex y-u-justify-end y-u-items-center">
				<a href="<?php echo esc_url( sweet_house_get_page_url( 'contact-us', sweet_house_get_page_url( 'contact' ) ) ); ?>" class="y-u-text-3xl y-u-font-bold y-u-color-primary contact-us-phone">
					<i class="fa-solid fa-phone"></i> اتصل بنا
				</a>
				<?php if ( ! ( ( function_exists( 'is_account_page' ) && is_account_page() ) || is_page( 'sign-up' ) ) ) : ?>
				<div class="search-input">
					<form action="<?php echo esc_url( $shop_url ); ?>" method="get" role="search">
						<button type="submit" aria-label="<?php echo esc_attr__( 'بحث', 'sweet-house-theme' ); ?>"><i class="fa-solid fa-search"></i></button>
						<input type="search" name="s" placeholder="<?php echo esc_attr__( 'ابحث عن منتج', 'sweet-house-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
						<input type="hidden" name="post_type" value="product" />
					</form>
				</div>
				<?php endif; ?>
			</div>

			<div class="user-nav y-u-flex y-u-justify-end y-u-items-center">
				<a href="<?php echo esc_url( $wishlist_url ); ?>"><i class="fa fa-heart"></i></a>
				<a href="<?php echo esc_url( $cart_url ); ?>" class="cart-icon-link" aria-label="<?php echo esc_attr( sprintf( __( 'السلة (%d)', 'sweet-house-theme' ), $cart_count ) ); ?>">
					<i class="fa-solid fa-basket-shopping"></i>
					<span class="cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
				</a>
				<a href="<?php echo esc_url( $my_account_url ); ?>"><i class="fa fa-user"></i></a>
			</div>
		</div>

		<div class="mobile-menu-overlay">
			<ul class="mobile-menu y-u-flex y-u-justify-start y-u-flex-col y-u-gap-16">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a></li>
				<li><a href="<?php echo esc_url( $shop_url ); ?>">المنتجات</a></li>
				<li><a href="<?php echo esc_url( $recipes_url ); ?>">وصفاتنا</a></li>
				<li><a href="<?php echo esc_url( $about_url ); ?>">من نحن</a></li>
			</ul>
		</div>

		<div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200 bottom">
			<div class="logo y-u-justify-end y-u-items-center mobile">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="y-u-text-3xl y-u-font-bold y-u-color-primary">
					<img src="<?php echo esc_url( $navbar_logo ); ?>" class="navbar-logo" alt="<?php echo esc_attr__( 'شعار سويت هاوس - العودة للرئيسية', 'sweet-house-theme' ); ?>" />
				</a>
			</div>
			<ul class="desktop-menu y-u-flex y-u-justify-end y-u-items-center">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a></li>
				<li><a href="<?php echo esc_url( $shop_url ); ?>">المنتجات</a></li>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="y-u-text-3xl y-u-font-bold y-u-color-primary">
					<img src="<?php echo esc_url( $navbar_logo ); ?>" class="navbar-logo" alt="<?php echo esc_attr__( 'شعار سويت هاوس - العودة للرئيسية', 'sweet-house-theme' ); ?>" />
				</a></li>
				<li><a href="<?php echo esc_url( $recipes_url ); ?>">وصفاتنا</a></li>
				<li><a href="<?php echo esc_url( $about_url ); ?>">من نحن</a></li>
			</ul>
			<button class="mobile-menu-btn y-u-flex-col y-u-justify-between" type="button" aria-label="<?php echo esc_attr__( 'فتح القائمة', 'sweet-house-theme' ); ?>">
				<span></span><span></span><span></span>
			</button>
			<div class="user-nav y-u-flex y-u-justify-end y-u-items-center mobile">
				<a href="<?php echo esc_url( $wishlist_url ); ?>"><i class="fa fa-heart"></i></a>
				<a href="<?php echo esc_url( $cart_url ); ?>" class="cart-icon-link" aria-label="<?php echo esc_attr( sprintf( __( 'السلة (%d)', 'sweet-house-theme' ), $cart_count ) ); ?>">
					<i class="fa-solid fa-basket-shopping"></i>
					<span class="cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
				</a>
				<a href="<?php echo esc_url( $my_account_url ); ?>"><i class="fa fa-user"></i></a>
			</div>
		</div>
	</header>

	<?php if ( is_front_page() ) : ?>
	<?php
	$hero = function_exists( 'sweet_house_get_home_content' ) ? sweet_house_get_home_content() : array();
	$hero_img = function_exists( 'sweet_house_content_image_url' ) ? sweet_house_content_image_url( isset( $hero['hero_banner_img'] ) ? $hero['hero_banner_img'] : 0, 'assets/header.png' ) : sweet_house_asset_uri( 'assets/header.png' );
	$hero_title = isset( $hero['hero_title'] ) ? $hero['hero_title'] : 'سويت هاوس';
	$hero_sub1 = isset( $hero['hero_subtitle1'] ) ? $hero['hero_subtitle1'] : 'من فرننا لقلبك';
	$hero_sub2 = isset( $hero['hero_subtitle2'] ) ? $hero['hero_subtitle2'] : 'سويت هاوس.. طعم ولا احلى';
	?>
	<header class="y-u-bg cairo-font hero-section" data-y="hero">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6 col-md-12 text-center text-lg-start mb-4 mb-lg-0">
					<img src="<?php echo esc_url( $hero_img ); ?>" alt="<?php echo esc_attr__( 'صورة رئيسية لسويت هاوس - متجر الحلويات والمخبوزات الطازجة', 'sweet-house-theme' ); ?>" class="img-fluid header-image" />
				</div>
				<div class="col-lg-6 col-md-12 text-center">
					<h1 class="gulzar-regular y-u-pinkClr header-txt cairo-font"><?php echo esc_html( $hero_title ); ?></h1>
					<h2 class="mb-3 cairo-font"><?php echo esc_html( $hero_sub1 ); ?></h2>
					<h2 class="mb-4 cairo-font"><?php echo esc_html( $hero_sub2 ); ?></h2>
					<a href="<?php echo esc_url( $shop_url ); ?>" class="btn btn-lg px-4 px-lg-5 py-2 rounded-pill fw-bold text-white y-u-blue fs-4 fs-lg-2 text-decoration-none cairo-font">
						<?php echo esc_html__( 'تسوق الان', 'sweet-house-theme' ); ?>
					</a>
				</div>
			</div>
		</div>
	</header>
	<?php endif; ?>

	<?php do_action( 'sweet_house_after_header' ); ?>
