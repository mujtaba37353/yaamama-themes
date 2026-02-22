<?php
/**
 * Header template part — markup from beauty-time/components/header.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$mock = beauty_time_mock_uri();
$home = home_url( '/' );
$services = function_exists( 'beauty_theme_get_page_link' ) ? beauty_theme_get_page_link( 'services', home_url( '/services' ) ) : home_url( '/services' );
$onsale = function_exists( 'beauty_theme_get_page_link' ) ? beauty_theme_get_page_link( 'onsale', home_url( '/onsale' ) ) : home_url( '/onsale' );
$about = function_exists( 'beauty_theme_get_page_link' ) ? beauty_theme_get_page_link( 'about', home_url( '/about-us' ) ) : home_url( '/about-us' );
$login_url = home_url( '/my-account' );
$account_label = __( 'تسجيل الدخول', 'beauty-time-theme' );
$demo_options = function_exists( 'beauty_demo_site_get_options' ) ? beauty_demo_site_get_options() : array();
$logo_header = $demo_options['logos']['header'] ?? beauty_time_asset( 'assets/navbar-icon.png' );
if ( function_exists( 'wc_get_page_permalink' ) ) {
	$login_url = wc_get_page_permalink( 'myaccount' );
}
if ( is_user_logged_in() ) {
	$account_label = __( 'حسابي', 'beauty-time-theme' );
}
$search_url = home_url( '/' );
if ( function_exists( 'wc_get_page_permalink' ) ) {
	$search_url = wc_get_page_permalink( 'shop' );
}
?>
<header class="y-u-flex y-u-justify-center y-u-items-center y-u-fixed header y-u-top-left">
  <div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200 top">
    <div class="logo y-u-flex y-u-justify-end y-u-items-center">
      <a href="<?php echo esc_url( $home ); ?>" class="y-u-text-3xl y-u-font-bold y-u-color-primary">
        <img src="<?php echo esc_url( $logo_header ); ?>" alt="<?php esc_attr_e( 'Beauty Time', 'beauty-time-theme' ); ?>">
      </a>
      <form role="search" method="get" class="search-input" action="<?php echo esc_url( $search_url ); ?>">
        <img src="<?php echo esc_url( beauty_time_asset( 'assets/search.svg' ) ); ?>" alt="" />
        <input type="search" name="s" placeholder="<?php esc_attr_e( 'ابحث', 'beauty-time-theme' ); ?>" value="<?php echo get_search_query(); ?>" />
      </form>
    </div>

    <div class="user-nav y-u-flex y-u-justify-end y-u-items-center">
      <a href="<?php echo esc_url( $login_url ); ?>">
        <img src="<?php echo esc_url( beauty_time_asset( 'assets/profile.svg' ) ); ?>" alt="" />
        <?php echo esc_html( $account_label ); ?>
      </a>
    </div>
  </div>

  <div class="mobile-menu-overlay">
    <ul class="mobile-menu y-u-flex y-u-justify-start y-u-flex-col y-u-gap-16">
      <li><a href="<?php echo esc_url( $home ); ?>"><i class="fas fa-home"></i> <?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a></li>
      <li><a href="<?php echo esc_url( $services ); ?>"><i class="fas fa-list"></i> <?php esc_html_e( 'الاقسام', 'beauty-time-theme' ); ?></a></li>
      <li><a href="<?php echo esc_url( $onsale ); ?>"><i class="fas fa-tags"></i> <?php esc_html_e( 'باقات توفيرية', 'beauty-time-theme' ); ?></a></li>
      <li><a href="<?php echo esc_url( $about ); ?>"><i class="fas fa-info-circle"></i> <?php esc_html_e( 'من نحن', 'beauty-time-theme' ); ?></a></li>
    </ul>
  </div>

  <div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200 bottom">
    <ul class="desktop-menu y-u-flex y-u-justify-end y-u-items-center">
      <li><a href="<?php echo esc_url( $home ); ?>"><i class="fas fa-home"></i> <?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a></li>
      <li><a href="<?php echo esc_url( $services ); ?>"><i class="fas fa-list"></i> <?php esc_html_e( 'الاقسام', 'beauty-time-theme' ); ?></a></li>
      <li><a href="<?php echo esc_url( $onsale ); ?>"><i class="fas fa-tags"></i> <?php esc_html_e( 'باقات توفيرية', 'beauty-time-theme' ); ?></a></li>
      <li><a href="<?php echo esc_url( $about ); ?>"><i class="fas fa-info-circle"></i> <?php esc_html_e( 'من نحن', 'beauty-time-theme' ); ?></a></li>
    </ul>
    <button class="mobile-menu-btn y-u-flex-col y-u-justify-between" aria-label="<?php esc_attr_e( 'القائمة', 'beauty-time-theme' ); ?>">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <div class="user-nav y-u-flex y-u-justify-end y-u-items-center mobile">
      <a href="<?php echo esc_url( $login_url ); ?>">
        <img src="<?php echo esc_url( beauty_time_asset( 'assets/profile.svg' ) ); ?>" alt="" />
        <?php echo esc_html( $account_label ); ?>
      </a>
    </div>
  </div>
</header>
