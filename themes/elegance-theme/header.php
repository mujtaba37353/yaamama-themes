<?php
/**
 * Header template - Elegance (RTL)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$assets     = ELEGANCE_ELEGANCE_URI . '/assets';
$cart_count = 0;
if ( function_exists( 'WC' ) && WC()->cart ) {
	$cart_count = WC()->cart->get_cart_contents_count();
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="icon" href="<?php echo esc_url( $assets . '/icon.png' ); ?>" type="image/x-icon" />
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
<header class="y-u-flex y-u-justify-center y-u-items-center y-u-fixed header y-u-top-left">
  <div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200">
    <div class="logo y-u-flex y-u-justify-end y-u-items-center">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="y-u-text-3xl y-u-font-bold y-u-color-primary" data-nav="home">
        <img src="<?php echo esc_url( $assets . '/navbar-icon.png' ); ?>" alt="navbar-icon">
      </a>
      <ul class="desktop-menu y-u-flex y-u-justify-end y-u-items-center">
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" data-nav="home">الرئيسيه</a></li>
        <li><a href="<?php echo esc_url( elegance_shop_url() ); ?>" data-nav="shop">تسوق</a></li>
        <li><a href="<?php echo esc_url( elegance_page_url( 'favorites', '/favorites/' ) ); ?>" data-nav="favorites">المفضلة</a></li>
        <li><a href="<?php echo esc_url( elegance_page_url( 'about-us', '/about-us/' ) ); ?>" data-nav="about-us">من نحن</a></li>
        <li><a href="<?php echo esc_url( elegance_page_url( 'contact', '/contact/' ) ); ?>" data-nav="contact">تواصل معنا</a></li>
      </ul>
    </div>

    <button class="mobile-menu-btn y-u-flex-col y-u-justify-between" aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'elegance' ); ?>">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <div class="user-nav y-u-flex y-u-justify-end y-u-items-center">
      <div class="search-container">
        <label class="search-icon-label" for="search-input">
          <img src="<?php echo esc_url( $assets . '/search.svg' ); ?>" alt="بحث" />
        </label>
        <input type="text" id="search-input" class="search-input" placeholder="ابحث عن المنتجات...">
      </div>
      <span class="elegance-cart-fragment" data-fragment="cart-count">
        <a href="<?php echo esc_url( elegance_cart_url() ); ?>" class="nav-icon nav-icon--cart" data-nav="cart" aria-label="<?php echo esc_attr( sprintf( _n( '%s منتج في السلة', '%s منتجات في السلة', $cart_count, 'elegance' ), $cart_count ) ); ?>">
          <img src="<?php echo esc_url( $assets . '/cart.svg' ); ?>" alt="" />
          <?php if ( $cart_count > 0 ) : ?>
            <span class="cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
          <?php endif; ?>
        </a>
      </span>
      <a href="<?php echo esc_url( elegance_myaccount_url() ); ?>" class="nav-icon nav-icon--profile" data-nav="profile">
        <img src="<?php echo esc_url( $assets . '/person.svg' ); ?>" alt="حسابي" />
        <span class="notification-dot" aria-hidden="true"></span>
      </a>
    </div>
  </div>

  <div class="mobile-menu-overlay">
    <nav class="mobile-menu">
      <ul class="y-u-flex y-u-flex-col y-u-gap-16">
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" data-nav="home">الرئيسية</a></li>
        <li><a href="<?php echo esc_url( elegance_shop_url() ); ?>" data-nav="shop">تسوق</a></li>
        <li><a href="<?php echo esc_url( elegance_page_url( 'favorites', '/favorites/' ) ); ?>" data-nav="favorites">المفضلة</a></li>
        <li><a href="<?php echo esc_url( elegance_page_url( 'about-us', '/about-us/' ) ); ?>" data-nav="about-us">من نحن</a></li>
        <li><a href="<?php echo esc_url( elegance_page_url( 'contact', '/contact/' ) ); ?>" data-nav="contact">تواصل معنا</a></li>
        <li><a href="<?php echo esc_url( elegance_cart_url() ); ?>" data-nav="cart">عربة التسوق <?php if ( $cart_count > 0 ) : ?><span class="cart-count-badge cart-count-badge--mobile"><?php echo esc_html( $cart_count ); ?></span><?php endif; ?></a></li>
        <li><a href="<?php echo esc_url( elegance_myaccount_url() ); ?>" data-nav="profile">حسابي</a> <span class="notification-dot" aria-hidden="true"></span></li>
        <li class="mobile-search-container">
          <div class="search-container mobile-search">
            <label class="search-icon-label" for="mobile-search-input">
              <img src="<?php echo esc_url( $assets . '/search.svg' ); ?>" alt="بحث" />
            </label>
            <input type="text" id="mobile-search-input" class="search-input" placeholder="ابحث عن المنتجات...">
          </div>
        </li>
      </ul>
    </nav>
  </div>
</header>
