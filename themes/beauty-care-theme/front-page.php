<?php
get_header();

$assets_uri = get_template_directory_uri() . '/beauty-care/assets';
$shop_url   = beauty_care_shop_permalink();
$hp         = function_exists( 'beauty_care_get_homepage_settings' ) ? beauty_care_get_homepage_settings() : array();

$categories = array();
if ( function_exists( 'get_terms' ) ) {
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
			'number'     => 4,
		)
	);
	if ( ! is_wp_error( $terms ) ) {
		$categories = $terms;
	}
}

if ( empty( $categories ) ) {
	$hp_cats = array( $hp['cat1_title'] ?? 'العناية بالبشرة', $hp['cat2_title'] ?? 'العناية بالشعر', $hp['cat3_title'] ?? 'العناية بالجسم', $hp['cat4_title'] ?? 'منتجات طبيعية' );
	foreach ( $hp_cats as $i => $name ) {
		$categories[] = (object) array( 'name' => $name, 'slug' => '', 'link' => $shop_url, '_img_idx' => $i );
	}
}

?>
<main>
	<section class="hero-section">
		<div class="container y-u-max-w-1200">
			<div class="right">
				<h2><?php echo esc_html( $hp['hero_title'] ?? 'بيوتي كير' ); ?></h2>
				<p><?php echo esc_html( $hp['hero_subtitle1'] ?? 'اكتشفي سر إشراقة بشرتك الطبيعية' ); ?></p>
				<p><?php echo esc_html( $hp['hero_subtitle2'] ?? 'منتجات طبيعية وآمنة تمنحك عناية متكاملة لبشرة صحية وشعر لامع.' ); ?></p>
				<a href="<?php echo esc_url( $shop_url ); ?>" class="main-button"><?php echo esc_html( $hp['hero_cta_text'] ?? 'جربي الآن' ); ?></a>
			</div>
			<div class="left">
				<img src="<?php echo esc_url( function_exists( 'beauty_care_content_image_url' ) ? beauty_care_content_image_url( $hp['hero_img1'] ?? 0, 'hero1.jpg' ) : $assets_uri . '/hero1.jpg' ); ?>" alt="">
				<img src="<?php echo esc_url( function_exists( 'beauty_care_content_image_url' ) ? beauty_care_content_image_url( $hp['hero_img2'] ?? 0, 'hero2.jpg' ) : $assets_uri . '/hero2.jpg' ); ?>" alt="">
			</div>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="main-button mobile-button"><?php esc_html_e( 'اكتشفي المزيد', 'beauty-care-theme' ); ?></a>
		</div>
		<img src="<?php echo esc_url( function_exists( 'beauty_care_content_image_url' ) ? beauty_care_content_image_url( $hp['hero_curve'] ?? 0, 'hero-curve.png' ) : $assets_uri . '/hero-curve.png' ); ?>" alt="">
	</section>

	<section class="about-section">
		<div class="container y-u-max-w-1200 about-grid">
			<div class="about-content">
				<h2><?php echo esc_html( $hp['about_title'] ?? 'من نحن' ); ?></h2>
				<p class="y-t-muted"><?php echo esc_html( $hp['about_text'] ?? 'نحن شركة متخصصة في تقديم أفضل أدوات ومنتجات التجميل للعناية بالبشرة والشعر...' ); ?></p>
			</div>
			<div class="about-image">
				<img src="<?php echo esc_url( function_exists( 'beauty_care_content_image_url' ) ? beauty_care_content_image_url( $hp['about_img'] ?? 0, 'about-us.jpg' ) : $assets_uri . '/about-us.jpg' ); ?>" alt="<?php esc_attr_e( 'عن بيوتي كير', 'beauty-care-theme' ); ?>" />
			</div>
		</div>
	</section>

	<section class="panner main-panner first-panner">
		<div class="container y-u-max-w-1200">
			<div class="right">
				<p><?php echo esc_html( $hp['panner1_text'] ?? 'منتجات عناية بالبشرة والشعر مصممة من مكونات طبيعية تمنحك إشراقة تدوم.' ); ?></p>
			</div>
			<div class="left"><img src="<?php echo esc_url( function_exists( 'beauty_care_content_image_url' ) ? beauty_care_content_image_url( $hp['panner1_img'] ?? 0, 'panner1.jpg' ) : $assets_uri . '/panner1.jpg' ); ?>" alt=""></div>
		</div>
	</section>

	<section class="categories-section">
		<div class="container y-u-max-w-1200">
			<h2><?php esc_html_e( 'الفئات', 'beauty-care-theme' ); ?></h2>
			<div class="categories-grid">
				<?php
				$i = 0;
				foreach ( $categories as $cat ) :
					$cat_link = isset( $cat->link ) ? $cat->link : ( function_exists( 'get_term_link' ) ? get_term_link( $cat ) : $shop_url );
					$cat_link = is_wp_error( $cat_link ) ? $shop_url : $cat_link;
					$cat_name = $cat->name ?? '';
					$ci       = isset( $cat->_img_idx ) ? $cat->_img_idx : ( $i % 4 );
					$cat_img  = function_exists( 'beauty_care_content_image_url' ) ? beauty_care_content_image_url( $hp[ 'cat' . ( $ci + 1 ) . '_img' ] ?? 0, 'cat' . ( $ci + 1 ) . '.jpg' ) : ( $assets_uri . '/cat' . ( $ci + 1 ) . '.jpg' );
					++$i;
					?>
					<a href="<?php echo esc_url( $cat_link ); ?>" class="category-item">
						<img src="<?php echo esc_url( $cat_img ); ?>" alt="<?php echo esc_attr( $cat_name ); ?>">
						<p><?php echo esc_html( $cat_name ); ?></p>
					</a>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="panner main-panner">
		<div class="container y-u-max-w-1200">
			<div class="right">
				<p><?php echo esc_html( $hp['panner2_text1'] ?? 'سيروم فيتامين سي – إشراقة فورية لبشرتك' ); ?></p>
				<p><?php echo esc_html( $hp['panner2_text2'] ?? 'تركيبة غنية بمضادات الأكسدة لتفتيح البشرة وتقليل التصبغات.' ); ?></p>
				<a href="<?php echo esc_url( $shop_url ); ?>" class="main-button"><?php echo esc_html( $hp['panner2_cta_text'] ?? 'تعرّفي على المزيد' ); ?></a>
			</div>
			<div class="left">
				<img src="<?php echo esc_url( function_exists( 'beauty_care_content_image_url' ) ? beauty_care_content_image_url( $hp['panner2_img'] ?? 0, 'panner2.png' ) : $assets_uri . '/panner2.png' ); ?>" alt="">
			</div>
		</div>
	</section>

	<section class="products-section">
		<div class="container y-u-max-w-1200">
			<div class="products-grid">
				<div class="product-card special">
					<h2><?php echo esc_html( $hp['products_title'] ?? 'منتجاتنا المميزة' ); ?></h2>
					<p><?php echo esc_html( $hp['products_text'] ?? 'اكتشفي مجموعتنا المختارة من منتجات العناية بالبشرة والشعر، المصممة لتمنحك جمالًا طبيعيًا يدوم.' ); ?></p>
					<a href="<?php echo esc_url( $shop_url ); ?>" class="main-button"><?php echo esc_html( $hp['products_cta_text'] ?? 'اكتشفي المزيد' ); ?></a>
				</div>

				<?php
				if ( function_exists( 'wc_get_products' ) ) {
					$products = wc_get_products( array( 'limit' => 5, 'status' => 'publish' ) );
					foreach ( $products as $product ) :
						$thumb_id    = $product->get_image_id();
						$img_url     = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'woocommerce_thumbnail' ) : $assets_uri . '/pro1.jpg';
						$prod_id     = $product->get_id();
						$in_wishlist = function_exists( 'beauty_care_get_wishlist_ids' ) && in_array( (int) $prod_id, beauty_care_get_wishlist_ids(), true );
						?>
						<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
							<div class="product-card">
								<div class="product-img">
									<label class="favorite-toggle" aria-label="<?php esc_attr_e( 'إضافة للمفضلة', 'beauty-care-theme' ); ?>">
										<input type="checkbox" class="favorite-toggle__checkbox" <?php echo $in_wishlist ? ' checked' : ''; ?> data-product-id="<?php echo esc_attr( (string) $prod_id ); ?>">
										<span class="favorite-toggle__icon">
											<i class="fa-solid fa-heart" aria-hidden="true"></i>
											<i class="fa-regular fa-heart" aria-hidden="true"></i>
										</span>
									</label>
									<img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
									<button><img src="<?php echo esc_url( $assets_uri . '/add-to-cart.svg' ); ?>" alt=""></button>
								</div>
								<div class="product-content">
									<p class="product-title"><?php echo esc_html( $product->get_name() ); ?></p>
									<p class="product-price"><?php echo esc_html( $product->get_price() ); ?> <img src="<?php echo esc_url( $assets_uri . '/ryal.svg' ); ?>" alt=""></p>
								</div>
							</div>
						</a>
						<?php
					endforeach;
				} else {
					$static_products = array(
						array( 'title' => 'ماسك الطمي', 'price' => '30', 'img' => 'pro1.jpg', 'url' => $shop_url ),
						array( 'title' => 'سيرم فيتامين سي', 'price' => '30', 'img' => 'pro2.jpg', 'url' => $shop_url ),
						array( 'title' => 'سيرم فيتامين سي', 'price' => '30', 'img' => 'pro2.jpg', 'url' => $shop_url ),
						array( 'title' => 'تونر مرطب', 'price' => '70', 'img' => 'pro3.jpg', 'url' => $shop_url ),
						array( 'title' => 'ماسك الطمي', 'price' => '30', 'img' => 'pro1.jpg', 'url' => $shop_url ),
					);
					foreach ( $static_products as $prod ) :
						?>
						<a href="<?php echo esc_url( $prod['url'] ); ?>">
							<div class="product-card">
								<div class="product-img">
									<label class="favorite-toggle">
										<input type="checkbox" class="favorite-toggle__checkbox">
										<span class="favorite-toggle__icon">
											<i class="fa-solid fa-heart" aria-hidden="true"></i>
											<i class="fa-regular fa-heart" aria-hidden="true"></i>
										</span>
									</label>
									<img src="<?php echo esc_url( $assets_uri . '/' . $prod['img'] ); ?>" alt="<?php echo esc_attr( $prod['title'] ); ?>">
									<button><img src="<?php echo esc_url( $assets_uri . '/add-to-cart.svg' ); ?>" alt=""></button>
								</div>
								<div class="product-content">
									<p class="product-title"><?php echo esc_html( $prod['title'] ); ?></p>
									<p class="product-price"><?php echo esc_html( $prod['price'] ); ?> <img src="<?php echo esc_url( $assets_uri . '/ryal.svg' ); ?>" alt=""></p>
								</div>
							</div>
						</a>
						<?php
					endforeach;
				}
				?>
			</div>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="main-button mobile-button"><?php esc_html_e( 'اكتشفي المزيد', 'beauty-care-theme' ); ?></a>
		</div>
	</section>
</main>

<?php
get_footer();
