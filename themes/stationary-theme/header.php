<?php
$assets_uri = stationary_base_uri() . '/assets';
$logo_url   = stationary_logo_url( 'navbar' );
$cart_count = function_exists( 'WC' ) && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
$shop_url   = stationary_shop_permalink();
?>
<!doctype html>
<html <?php language_attributes(); ?> dir="rtl" lang="ar">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="<?php echo esc_url( $assets_uri . '/icon.png' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="y-u-flex y-u-justify-center y-u-items-center y-u-fixed header y-u-top-left">
	<div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200">
		<div class="logo y-u-flex y-u-justify-end y-u-items-center">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="y-u-text-3xl y-u-font-bold y-u-color-primary">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php esc_attr_e( 'شعار Stationary', 'stationary-theme' ); ?>">
			</a>
			<ul class="desktop-menu y-u-flex y-u-justify-end y-u-items-center">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>><?php esc_html_e( 'الرئيسية', 'stationary-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( $shop_url ); ?>" <?php echo ( function_exists( 'is_shop' ) && is_shop() ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'تسوق', 'stationary-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>" <?php echo is_page( 'about-us' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'من نحن', 'stationary-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" <?php echo is_page( 'contact' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'تواصل معنا', 'stationary-theme' ); ?></a></li>
			</ul>
		</div>

		<button class="mobile-menu-btn y-u-flex-col y-u-justify-between" aria-label="<?php esc_attr_e( 'فتح القائمة', 'stationary-theme' ); ?>">
			<span></span>
			<span></span>
			<span></span>
		</button>
		<div class="user-nav y-u-flex y-u-justify-end y-u-items-center">
			<form class="y-header-search" method="get" action="<?php echo esc_url( $shop_url ); ?>" role="search">
				<div class="y-header-search__wrap">
					<button class="y-header-search__icon-btn" type="button" aria-label="<?php esc_attr_e( 'بحث', 'stationary-theme' ); ?>">
						<img src="<?php echo esc_url( $assets_uri . '/search.svg' ); ?>" alt="<?php esc_attr_e( 'بحث', 'stationary-theme' ); ?>" class="y-header-search__icon" />
					</button>
					<input type="search" name="s" class="y-header-search__input" placeholder="<?php esc_attr_e( 'بحث', 'stationary-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
				</div>
			</form>
			<span class="stationary-cart-fragment">
				<a href="<?php echo esc_url( stationary_cart_permalink() ); ?>" class="header-cart-link" aria-label="<?php echo esc_attr( sprintf( _n( '%s منتج في السلة', '%s منتجات في السلة', $cart_count, 'stationary-theme' ), $cart_count ) ); ?>">
					<img src="<?php echo esc_url( $assets_uri . '/cart.svg' ); ?>" alt="<?php esc_attr_e( 'السلة', 'stationary-theme' ); ?>" />
					<?php if ( $cart_count > 0 ) : ?>
						<span class="cart-count-badge"><?php echo esc_html( $cart_count ); ?></span>
					<?php endif; ?>
				</a>
			</span>
			<a href="<?php echo esc_url( stationary_account_permalink() ); ?>" aria-label="<?php esc_attr_e( 'حسابي', 'stationary-theme' ); ?>">
				<img src="<?php echo esc_url( $assets_uri . '/person.svg' ); ?>" alt="<?php esc_attr_e( 'حسابي', 'stationary-theme' ); ?>" />
			</a>
		</div>
	</div>

	<div class="mobile-menu-overlay">
		<nav class="mobile-menu">
			<button class="mobile-menu-close" aria-label="<?php esc_attr_e( 'إغلاق القائمة', 'stationary-theme' ); ?>">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
				</svg>
			</button>
			<div class="mobile-menu-search">
				<form class="y-header-search" method="get" action="<?php echo esc_url( $shop_url ); ?>" role="search">
					<div class="y-header-search__wrap y-header-search--active">
						<button class="y-header-search__icon-btn" type="button" aria-label="<?php esc_attr_e( 'بحث', 'stationary-theme' ); ?>">
							<img src="<?php echo esc_url( $assets_uri . '/search.svg' ); ?>" alt="<?php esc_attr_e( 'بحث', 'stationary-theme' ); ?>" class="y-header-search__icon" />
						</button>
						<input type="search" name="s" class="y-header-search__input" placeholder="<?php esc_attr_e( 'بحث', 'stationary-theme' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>" />
					</div>
				</form>
			</div>
			<ul class="mobile-menu-list y-u-flex y-u-flex-col y-u-gap-16">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>" <?php echo is_front_page() ? 'class="active"' : ''; ?>><?php esc_html_e( 'الرئيسية', 'stationary-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( $shop_url ); ?>" <?php echo ( function_exists( 'is_shop' ) && is_shop() ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'تسوق', 'stationary-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>" <?php echo is_page( 'about-us' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'من نحن', 'stationary-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" <?php echo is_page( 'contact' ) ? 'class="active"' : ''; ?>><?php esc_html_e( 'تواصل معنا', 'stationary-theme' ); ?></a></li>
				<li class="mobile-menu-item-with-bullet"><a href="<?php echo esc_url( stationary_cart_permalink() ); ?>"><?php esc_html_e( 'السلة', 'stationary-theme' ); ?><?php if ( $cart_count > 0 ) : ?> <span class="cart-count-badge cart-count-badge--mobile"><?php echo esc_html( $cart_count ); ?></span><?php endif; ?></a></li>
				<li class="mobile-menu-item-with-bullet"><a href="<?php echo esc_url( stationary_account_permalink() ); ?>"><?php esc_html_e( 'حسابي', 'stationary-theme' ); ?></a></li>
			</ul>
		</nav>
	</div>
</header>
