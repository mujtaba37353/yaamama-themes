<?php
get_header();
$au = stationary_base_uri() . '/assets';
$shop_url = stationary_shop_permalink();
$offers_url = add_query_arg( 'on_sale', '1', $shop_url );

$cat_ids = array();
if ( function_exists( 'wc_get_product_category_ids' ) ) {
	$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'number' => 5 ) );
	if ( ! is_wp_error( $terms ) ) {
		$cat_ids = wp_list_pluck( $terms, 'term_id' );
	}
}
$default_cats = array(
	array( 'name' => 'أقلام و أدوات الكتابة', 'slug' => '', 'img' => $au . '/cat1.png' ),
	array( 'name' => 'الأدوات المدرسية', 'slug' => '', 'img' => $au . '/cat2.png' ),
	array( 'name' => 'آلات حاسبة', 'slug' => '', 'img' => $au . '/cat3.png' ),
	array( 'name' => 'أطقم المكتب', 'slug' => '', 'img' => $au . '/cat4.png' ),
	array( 'name' => 'كراسات و منتجات ورقية', 'slug' => '', 'img' => $au . '/cat5.png' ),
);
if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
	foreach ( $terms as $i => $t ) {
		if ( isset( $default_cats[ $i ] ) ) {
			$default_cats[ $i ]['name'] = $t->name;
			$default_cats[ $i ]['slug'] = $t->slug;
			$thumb_id = get_term_meta( $t->term_id, 'thumbnail_id', true );
			if ( $thumb_id ) {
				$default_cats[ $i ]['img'] = wp_get_attachment_image_url( $thumb_id, 'medium' );
			}
		}
	}
}
?>

<main>
	<section class="hero-section container y-u-max-w-1200">
		<div class="carsousel">
			<div class="imgs">
				<?php
				$hero1 = $au . '/hero1.jpg';
				$hero2 = $au . '/hero2.jpg';
				?>
				<img src="<?php echo esc_url( $hero1 ); ?>" alt="<?php esc_attr_e( 'عرض المنتجات المكتبية المميزة', 'stationary-theme' ); ?>">
				<img src="<?php echo esc_url( $hero2 ); ?>" alt="<?php esc_attr_e( 'مجموعة الأدوات المكتبية الحديثة', 'stationary-theme' ); ?>">
			</div>
			<div class="content">
				<h2><?php esc_html_e( 'نظم يومك مع أدواتنا المكتبية الأنيقة', 'stationary-theme' ); ?></h2>
				<h2><?php esc_html_e( 'كل ما تحتاجه للدراسة أو العمل في مكان واحد.', 'stationary-theme' ); ?></h2>
			</div>
		</div>
		<div class="dots">
			<div class="dot active"></div>
			<div class="dot"></div>
		</div>
	</section>

	<section class="category-section">
		<div class="container y-u-max-w-1200">
			<h2><?php esc_html_e( 'جميع الفئات', 'stationary-theme' ); ?></h2>
			<ul>
				<?php foreach ( $default_cats as $cat ) : ?>
					<?php
					$link = ! empty( $cat['slug'] ) ? get_term_link( $cat['slug'], 'product_cat' ) : $shop_url;
					if ( is_wp_error( $link ) ) {
						$link = $shop_url;
					}
					?>
					<a href="<?php echo esc_url( $link ); ?>">
						<li>
							<div class="img"><img src="<?php echo esc_url( $cat['img'] ); ?>" alt="<?php echo esc_attr( $cat['name'] ); ?>"></div>
							<p><?php echo esc_html( $cat['name'] ); ?></p>
						</li>
					</a>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<section class="products-section">
		<div class="container y-u-max-w-1200">
			<div class="header">
				<h2><?php esc_html_e( 'الأكثر مبيعًا', 'stationary-theme' ); ?></h2>
				<a href="<?php echo esc_url( $shop_url ); ?>" class="y-u-text-primary"><?php esc_html_e( 'اطلع على المزيد', 'stationary-theme' ); ?></a>
			</div>
			<ul class="grid">
				<?php
				$products = array();
				if ( function_exists( 'wc_get_products' ) ) {
					$products = wc_get_products( array(
						'limit'   => 8,
						'status'  => 'publish',
						'orderby' => 'popularity',
					) );
					foreach ( $products as $product ) {
						get_template_part( 'stationary/partials/product-card', null, array( 'product' => $product, 'show_sale' => false ) );
					}
				}
				if ( empty( $products ) ) {
					for ( $i = 0; $i < 8; $i++ ) {
						get_template_part( 'stationary/partials/product-card-placeholder', null, array( 'show_sale' => false ) );
					}
				}
				?>
			</ul>
		</div>
	</section>

	<section class="panner panner-image panner-image2 hero-panner">
		<div class="div container y-u-max-w-1200">
			<img src="<?php echo esc_url( $au . '/panner-img.jpg' ); ?>" alt="<?php esc_attr_e( 'عرض خاص على الكشاكيل والأقلام', 'stationary-theme' ); ?>">
			<div class="content">
				<p>
					<span><?php esc_html_e( 'حضّر مكتبك... وابدأ يومك بإبداع!', 'stationary-theme' ); ?></span>
					<br>
					<?php esc_html_e( 'اكتشف مجموعتنا من الكشاكيل والأقلام المميزة', 'stationary-theme' ); ?>
				</p>
				<a href="<?php echo esc_url( $shop_url ); ?>"><?php esc_html_e( 'تسوق الآن', 'stationary-theme' ); ?></a>
			</div>
		</div>
	</section>

	<section class="products-section">
		<div class="container y-u-max-w-1200">
			<div class="header">
				<h2><?php esc_html_e( 'عروض وتخفيضات', 'stationary-theme' ); ?></h2>
				<a href="<?php echo esc_url( $offers_url ); ?>" class="y-u-text-primary link"><?php esc_html_e( 'اطلع على المزيد', 'stationary-theme' ); ?></a>
			</div>
			<ul class="grid">
				<?php
				$sale_ids = array();
				$sale_products = array();
				if ( function_exists( 'wc_get_products' ) ) {
					$sale_ids = wc_get_product_ids_on_sale();
					if ( ! empty( $sale_ids ) ) {
						$sale_products = wc_get_products( array( 'include' => array_slice( $sale_ids, 0, 8 ), 'limit' => 8 ) );
						foreach ( $sale_products as $product ) {
							get_template_part( 'stationary/partials/product-card', null, array( 'product' => $product, 'show_sale' => true ) );
						}
					}
				}
				if ( empty( $sale_products ) ) {
					for ( $i = 0; $i < 8; $i++ ) {
						get_template_part( 'stationary/partials/product-card-placeholder', null, array( 'show_sale' => true ) );
					}
				}
				?>
			</ul>
		</div>
	</section>
</main>

<?php
get_footer();
