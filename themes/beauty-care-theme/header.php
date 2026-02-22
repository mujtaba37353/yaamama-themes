<?php
$assets_uri = get_template_directory_uri() . '/beauty-care/assets';
$logo_url  = function_exists( 'beauty_care_footer_logo_url' ) ? beauty_care_footer_logo_url() : ( file_exists( get_template_directory() . '/beauty-care/assets/navbar-icon.png' ) ? $assets_uri . '/navbar-icon.png' : $assets_uri . '/icon.png' );
$cart_count = 0;
if ( function_exists( 'WC' ) && WC()->cart ) {
	$cart_count = WC()->cart->get_cart_contents_count();
}
?>
<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="manifest" href="<?php echo esc_url( function_exists( 'beauty_care_manifest_url' ) ? beauty_care_manifest_url() : get_template_directory_uri() . '/beauty-care/templates/manifest.json' ); ?>">
	<link rel="icon" href="<?php echo esc_url( $assets_uri . '/icon.png' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?><?php echo is_page( 'wishlist' ) ? ' data-current-page="wishlist"' : ''; ?>>
<?php wp_body_open(); ?>

<header class="y-u-flex y-u-justify-center y-u-items-center y-u-fixed header y-u-top-left">
	<div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200">
		<div class="logo y-u-flex y-u-justify-end y-u-items-center">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="y-u-text-3xl y-u-font-bold y-u-color-primary">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr__( 'شعار بيوتي كير', 'beauty-care-theme' ); ?>">
			</a>
			<ul class="desktop-menu y-u-flex y-u-justify-end y-u-items-center">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( beauty_care_shop_permalink() ); ?>" <?php echo ( function_exists( 'is_shop' ) && is_shop() ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'المتجر', 'beauty-care-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( function_exists( 'beauty_care_wishlist_permalink' ) ? beauty_care_wishlist_permalink() : home_url( '/wishlist' ) ); ?>" <?php echo is_page( 'wishlist' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'المفضلة', 'beauty-care-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>" <?php echo is_page( 'about-us' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'من نحن', 'beauty-care-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" <?php echo is_page( 'contact' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'تواصل معنا', 'beauty-care-theme' ); ?></a></li>
			</ul>
		</div>

		<button class="mobile-menu-btn y-u-flex-col y-u-justify-between" aria-label="<?php esc_attr_e( 'فتح القائمة', 'beauty-care-theme' ); ?>">
			<span></span>
			<span></span>
			<span></span>
		</button>
		<div class="user-nav y-u-flex y-u-justify-end y-u-items-center">
			<?php $shop_search_url = function_exists( 'beauty_care_shop_permalink' ) ? beauty_care_shop_permalink() : home_url( '/shop/' ); ?>
			<form class="y-header-search" method="get" action="<?php echo esc_url( $shop_search_url ); ?>" role="search">
				<div class="y-header-search__wrap">
					<button class="y-header-search__icon-btn" type="button" aria-label="<?php esc_attr_e( 'بحث', 'beauty-care-theme' ); ?>">
						<img src="<?php echo esc_url( $assets_uri . '/search.svg' ); ?>" alt="<?php esc_attr_e( 'بحث', 'beauty-care-theme' ); ?>" class="y-header-search__icon" />
					</button>
					<input type="search" name="s" class="y-header-search__input" placeholder="<?php esc_attr_e( 'بحث', 'beauty-care-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
				</div>
			</form>
			<a href="<?php echo esc_url( function_exists( 'beauty_care_wishlist_permalink' ) ? beauty_care_wishlist_permalink() : home_url( '/wishlist' ) ); ?>" class="header-wishlist-link" aria-label="<?php esc_attr_e( 'المفضلة', 'beauty-care-theme' ); ?>">
				<i class="fa-regular fa-heart"></i>
			</a>
			<span class="beauty-care-cart-fragment">
				<a href="<?php echo esc_url( beauty_care_cart_permalink() ); ?>" class="header-cart-link" aria-label="<?php echo esc_attr( sprintf( _n( '%s منتج في السلة', '%s منتجات في السلة', $cart_count, 'beauty-care-theme' ), $cart_count ) ); ?>">
					<img src="<?php echo esc_url( $assets_uri . '/cart.svg' ); ?>" alt="<?php esc_attr_e( 'السلة', 'beauty-care-theme' ); ?>" />
					<?php if ( $cart_count > 0 ) : ?>
						<span class="cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
					<?php endif; ?>
				</a>
			</span>
			<a href="<?php echo esc_url( beauty_care_account_permalink() ); ?>">
				<img src="<?php echo esc_url( $assets_uri . '/person.svg' ); ?>" alt="<?php esc_attr_e( 'حسابي', 'beauty-care-theme' ); ?>" />
			</a>
		</div>
	</div>

	<div class="mobile-menu-overlay">
		<nav class="mobile-menu">
			<button class="mobile-menu-close" aria-label="<?php esc_attr_e( 'إغلاق القائمة', 'beauty-care-theme' ); ?>">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
			<div class="mobile-menu-search">
				<form class="y-header-search" method="get" action="<?php echo esc_url( $shop_search_url ); ?>" role="search">
					<div class="y-header-search__wrap y-header-search--active">
						<button class="y-header-search__icon-btn" type="button" aria-label="<?php esc_attr_e( 'بحث', 'beauty-care-theme' ); ?>">
							<img src="<?php echo esc_url( $assets_uri . '/search.svg' ); ?>" alt="<?php esc_attr_e( 'بحث', 'beauty-care-theme' ); ?>" class="y-header-search__icon" />
						</button>
						<input type="search" name="s" class="y-header-search__input" placeholder="<?php esc_attr_e( 'بحث', 'beauty-care-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
					</div>
				</form>
			</div>
			<ul class="mobile-menu-list y-u-flex y-u-flex-col y-u-gap-16">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( beauty_care_shop_permalink() ); ?>" <?php echo ( function_exists( 'is_shop' ) && is_shop() ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'المتجر', 'beauty-care-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( function_exists( 'beauty_care_wishlist_permalink' ) ? beauty_care_wishlist_permalink() : home_url( '/wishlist' ) ); ?>" <?php echo is_page( 'wishlist' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'المفضلة', 'beauty-care-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>" <?php echo is_page( 'about-us' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'من نحن', 'beauty-care-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" <?php echo is_page( 'contact' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'تواصل معنا', 'beauty-care-theme' ); ?></a></li>
				<li class="mobile-menu-item-with-bullet"><a href="<?php echo esc_url( beauty_care_cart_permalink() ); ?>"><?php esc_html_e( 'السلة', 'beauty-care-theme' ); ?><?php if ( $cart_count > 0 ) : ?> <span class="cart-count-badge cart-count-badge--mobile"><?php echo esc_html( $cart_count ); ?></span><?php endif; ?></a></li>
				<li class="mobile-menu-item-with-bullet"><a href="<?php echo esc_url( beauty_care_account_permalink() ); ?>"><?php esc_html_e( 'حسابي', 'beauty-care-theme' ); ?></a></li>
			</ul>
		</nav>
	</div>
</header>
