<?php
$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';
?>
<!doctype html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="manifest" href="<?php echo esc_url( get_template_directory_uri() . '/yaamama-front-platform/templates/manifest.json' ); ?>">
	<link rel="icon" href="<?php echo esc_url( $assets_uri . '/icon.png' ); ?>">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="y-u-flex y-u-justify-center y-u-items-center y-u-fixed header y-u-top-left">
	<div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200">
		<div class="logo y-u-flex y-u-justify-end y-u-items-center">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="y-u-text-3xl y-u-font-bold y-u-color-primary">
				<img src="<?php echo esc_url( $assets_uri . '/navbar-icon.png' ); ?>" alt="شعار منصة يمامة" />
			</a>
			<ul class="desktop-menu y-u-flex y-u-justify-end y-u-items-center">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a></li>
				<li><a href="<?php echo esc_url( home_url( '/store' ) ); ?>">متاجرنا</a></li>
				<li><a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>">من نحن</a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">تواصل معنا</a></li>
			</ul>
		</div>

		<button class="mobile-menu-btn y-u-flex-col y-u-justify-between" aria-label="Toggle mobile menu">
			<span></span>
			<span></span>
			<span></span>
		</button>
		<div class="user-nav y-u-flex y-u-justify-end y-u-items-center">
			<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">
				<img src="<?php echo esc_url( $assets_uri . '/person.svg' ); ?>" alt="أيقونة حساب المستخدم" />
				<span>حسابي</span>
			</a>
		</div>
	</div>

	<div class="mobile-menu-overlay">
		<nav class="mobile-menu">
			<button class="mobile-menu-close" aria-label="Close menu">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M18 6L6 18M6 6L18 18" stroke="currentColor" stroke-width="2" stroke-linecap="round"
						stroke-linejoin="round" />
				</svg>
			</button>
			<div class="mobile-menu-search">
				<div class="y-header-search">
					<div class="y-header-search__wrap y-header-search--active">
						<button class="y-header-search__icon-btn" type="button" aria-label="Toggle search">
							<img src="<?php echo esc_url( $assets_uri . '/search.svg' ); ?>" alt="بحث" class="y-header-search__icon" />
						</button>
						<input type="text" class="y-header-search__input" placeholder="بحث" />
					</div>
				</div>
			</div>
			<ul class="mobile-menu-list y-u-flex y-u-flex-col y-u-gap-16">
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a></li>
				<li><a href="<?php echo esc_url( home_url( '/store' ) ); ?>">متاجرنا</a></li>
				<li><a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>">من نحن</a></li>
				<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">تواصل معنا</a></li>
				<li class="mobile-menu-item-with-bullet"><a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">حسابي</a></li>
			</ul>
		</nav>
	</div>
</header>
