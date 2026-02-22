<?php
/**
 * The header template file
 *
 * @package KhutaaTheme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0, user-scalable=yes">
	<?php wp_head(); ?>
</head>

<body <?php 
	$body_classes = array();
	if ( is_account_page() ) {
		$body_classes[] = 'account-page';
	}
	// Add account-page class for login, register, and lost-password pages
	$current_page = get_queried_object();
	if ( $current_page && isset( $current_page->post_name ) ) {
		if ( in_array( $current_page->post_name, array( 'login', 'register', 'lost-password' ) ) ) {
			$body_classes[] = 'account-page';
		}
	}
	body_class( $body_classes ); 
?> data-current-page="<?php echo esc_attr( is_account_page() ? 'my-account' : ( is_woocommerce() ? 'products' : ( is_front_page() ? 'home' : '' ) ) ); ?>">
<?php wp_body_open(); ?>

<nav class="navbar">
	<div class="logo-container">
		<div class="search-container">
			<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" class="search-input" placeholder="<?php esc_attr_e( 'بحث...', 'khutaa-theme' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
				<button type="submit" class="search-submit"><i class="fa fa-search search-icon"></i></button>
			</form>
		</div>
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link">
			<?php
			$logo_url = get_template_directory_uri() . '/khutaa/assets/navbar-logo.png';
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				echo '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="logo" />';
			}
			?>
		</a>
	</div>

	<div class="links">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" data-page="home">الرئيسية</a>
		<div class="dropdown">
			<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" data-page="products">
				المنتجات
				<i class="fa-solid fa-angle-down dropdown-arrow"></i>
			</a>
			<div class="dropdown-content">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">جميع المنتجات</a>
				<?php
				// Get product categories (exclude uncategorized)
				$categories = get_terms( array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => false,
					'exclude'    => get_option( 'default_product_cat' ), // Exclude uncategorized category
				) );
				if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
					foreach ( $categories as $category ) {
						echo '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
					}
				}
				?>
			</div>
		</div>
		<?php
		// Get offers page URL (page with slug 'offers' or template 'page-offers.php')
		$offers_page = get_page_by_path( 'offers' );
		if ( ! $offers_page ) {
			$offers_pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-offers.php' ) );
			if ( ! empty( $offers_pages ) ) {
				$offers_page = $offers_pages[0];
			}
		}
		$offers_url = $offers_page ? get_permalink( $offers_page->ID ) : home_url( '/offers' );
		
		// Get contact page URL
		$contact_page = get_page_by_path( 'contact-us' );
		if ( ! $contact_page ) {
			$contact_pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-contact.php' ) );
			if ( ! empty( $contact_pages ) ) {
				$contact_page = $contact_pages[0];
			}
		}
		$contact_url = $contact_page ? get_permalink( $contact_page->ID ) : home_url( '/contact-us' );
		?>
		<a href="<?php echo esc_url( $offers_url ); ?>" data-page="offers">العروض</a>
		<a href="<?php echo esc_url( $contact_url ); ?>" data-page="contact">تواصل معنا</a>
	</div>

	<div class="icons">
		<div class="search-container">
			<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<input type="search" class="search-input" placeholder="<?php esc_attr_e( 'بحث...', 'khutaa-theme' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
				<button type="submit" class="search-submit"><i class="fa fa-search search-icon"></i></button>
			</form>
		</div>
		<?php
		// Get wishlist page URL
		$wishlist_url = home_url( '/wishlist' );
		if ( function_exists( 'yith_wcwl_get_wishlist_url' ) ) {
			$wishlist_url = yith_wcwl_get_wishlist_url();
		} else {
			$wishlist_page = get_page_by_path( 'wishlist' );
			if ( $wishlist_page ) {
				$wishlist_url = get_permalink( $wishlist_page->ID );
			}
		}
		?>
		<a href="<?php echo esc_url( $wishlist_url ); ?>"><i class="fa-regular fa-heart"></i></a>
		<?php if ( is_user_logged_in() ) : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><i class="fa-regular fa-user"></i></a>
		<?php else : ?>
			<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><i class="fa-regular fa-user"></i></a>
		<?php endif; ?>
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-icon-link">
			<i class="fa-solid fa-bag-shopping"></i>
			<?php
			$cart_count = WC()->cart->get_cart_contents_count();
			if ( $cart_count > 0 ) {
				echo '<span class="cart-count">' . esc_html( $cart_count ) . '</span>';
			}
			?>
		</a>

		<!-- Hamburger Menu Button (Visible on mobile - inside icons row) -->
		<button class="hamburger-menu" aria-label="Toggle menu">
			<i class="fa-solid fa-bars"></i>
		</button>
	</div>

	<!-- Mobile Menu Overlay -->
	<div class="mobile-menu-overlay"></div>

	<!-- Mobile Sidebar Menu -->
	<div class="mobile-menu">
		<div class="mobile-menu-header">
			<?php
			$logo_url = get_template_directory_uri() . '/khutaa/assets/navbar-logo.png';
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				echo '<img src="' . esc_url( $logo_url ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="mobile-menu-logo" />';
			}
			?>
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
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">جميع المنتجات</a>
					<?php
					// Get product categories (exclude uncategorized)
					$mobile_categories = get_terms( array(
						'taxonomy'   => 'product_cat',
						'hide_empty' => false,
						'exclude'    => get_option( 'default_product_cat' ), // Exclude uncategorized category
					) );
					if ( ! empty( $mobile_categories ) && ! is_wp_error( $mobile_categories ) ) {
						foreach ( $mobile_categories as $category ) {
							echo '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
						}
					}
					?>
				</div>
			</div>
			<?php
			// Get offers and contact page URLs (reuse from above)
			if ( ! isset( $offers_url ) ) {
				$offers_page = get_page_by_path( 'offers' );
				if ( ! $offers_page ) {
					$offers_pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-offers.php' ) );
					if ( ! empty( $offers_pages ) ) {
						$offers_page = $offers_pages[0];
					}
				}
				$offers_url = $offers_page ? get_permalink( $offers_page->ID ) : home_url( '/offers' );
				
				$contact_page = get_page_by_path( 'contact-us' );
				if ( ! $contact_page ) {
					$contact_pages = get_pages( array( 'meta_key' => '_wp_page_template', 'meta_value' => 'page-contact.php' ) );
					if ( ! empty( $contact_pages ) ) {
						$contact_page = $contact_pages[0];
					}
				}
				$contact_url = $contact_page ? get_permalink( $contact_page->ID ) : home_url( '/contact-us' );
			}
			?>
			<a href="<?php echo esc_url( $offers_url ); ?>" data-page="offers">العروض</a>
			<a href="<?php echo esc_url( $contact_url ); ?>" data-page="contact">تواصل معنا</a>
		</div>
	</div>
</nav>
