<?php
get_header();

$is_empty = function_exists( 'WC' ) && WC()->cart && WC()->cart->is_empty();
$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : dark_theme_get_page_url( 'shop' );
?>

<header data-y="design-header">
	<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/header2.png' ) ); ?>" alt="" class="design-img y-u-w-100" />
</header>

<?php if ( $is_empty ) : ?>
	<main data-y="main" class="not-found-container">
		<div class="y-main-container">
			<div data-y="breadcrumb">
				<nav aria-label="breadcrumb" class="y-breadcrumb-container">
					<ol class="y-breadcrumb"></ol>
				</nav>
			</div>
			<div class="not-found-content">
				<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/empty-cart.png' ) ); ?>" alt="السلة فارغة" class="not-found-img" />
				<p class="not-found-text">
					السلة فارغة، لم تقم بإضافة أي منتجات إلى السلة الخاصة بك بعد.
				</p>
				<a href="<?php echo esc_url( $shop_url ); ?>" class="btn-back">
					لا يوجد منتجات، تصفح المنتجات <i class="fa-solid fa-cart-shopping"></i>
				</a>
			</div>
		</div>
	</main>
<?php else : ?>
	<main data-y="main" class="y-u-my-10">
		<div class="y-main-container">
			<div data-y="breadcrumb">
				<nav aria-label="breadcrumb" class="y-breadcrumb-container">
					<ol class="y-breadcrumb"></ol>
				</nav>
			</div>
		</div>
		<div class="y-main-container">
			<div data-y="cart-table">
				<?php echo do_shortcode( '[woocommerce_cart]' ); ?>
			</div>
		</div>
	</main>
<?php endif; ?>

<?php get_footer(); ?>
