<?php
get_header();
$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';
?>

<main class="special-bg">
	<section class="themes-section y-u-py-80">
		<div class="container y-u-max-w-1200">
			<div class="section-header y-u-text-center y-u-m-b-48">
				<h1 class="y-u-text-xxl y-u-font-bold y-u-m-b-16">ثيمات اليمامة</h1>
				<p class="y-u-text-m y-u-font-medium y-u-text-muted">اختر تصميم متجرك الأنيق من بين أكثر من 50 قالب احترافي</p>
			</div>

			<div class="tabs y-u-flex y-u-gap-16 y-u-flex-wrap y-u-justify-center y-u-m-b-48">
				<button class="tab-btn active" data-filter="all">الكل</button>
				<?php
				$categories = function_exists( 'get_terms' )
					? get_terms(
						array(
							'taxonomy'   => 'product_cat',
							'hide_empty' => true,
						)
					)
					: array();
				if ( $categories && ! is_wp_error( $categories ) ) :
					foreach ( $categories as $category ) :
						?>
						<button class="tab-btn" data-filter="<?php echo esc_attr( $category->slug ); ?>">
							<?php echo esc_html( $category->name ); ?>
						</button>
						<?php
					endforeach;
				endif;
				?>
			</div>

			<div class="themes-grid y-u-grid y-u-grid-3 y-u-gap-32">
				<?php
				$products = function_exists( 'wc_get_products' )
					? wc_get_products(
						array(
							'status' => 'publish',
							'limit'  => -1,
						)
					)
					: array();

				if ( $products ) :
					foreach ( $products as $product ) :
						$permalink = get_permalink( $product->get_id() );
						$price_raw = $product->get_price();
						$price_html = $product->get_price_html();
						$terms = get_the_terms( $product->get_id(), 'product_cat' );
						$category_label = $terms && ! is_wp_error( $terms ) ? $terms[0]->name : 'عام';
						$category_slugs = array();
						if ( $terms && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								$category_slugs[] = $term->slug;
							}
						}
						$data_categories = $category_slugs ? implode( ' ', $category_slugs ) : 'uncategorized';
						?>
						<div class="theme-card y-u-rounded-m y-u-overflow-hidden y-u-border" data-categories="<?php echo esc_attr( $data_categories ); ?>">
							<div class="theme-image">
								<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'alt' => $product->get_name() ) ); ?>
							</div>
							<div class="theme-content y-u-p-24 y-u-flex y-u-flex-col y-u-gap-16">
								<div class="theme-badge y-u-flex y-u-items-center y-u-gap-8 light-primary-bg y-u-rounded-s y-u-px-16 y-u-py-4 y-u-w-fit">
									<i class="fa-solid fa-star primary-color"></i>
									<span class="y-u-text-s y-u-font-bold primary-color"><?php echo esc_html( $category_label ); ?></span>
								</div>
								<div class="theme-main-info y-u-flex y-u-justify-between y-u-items-center">
									<h3 class="y-u-text-l y-u-font-bold"><?php echo esc_html( $product->get_name() ); ?></h3>
									<div class="price y-u-flex y-u-items-center y-u-gap-8">
										<?php if ( $price_raw !== '' ) : ?>
											<span class="y-u-text-l y-u-font-bold"><?php echo esc_html( number_format( (float) $price_raw, 2 ) ); ?></span>
											<img src="<?php echo esc_url( $assets_uri . '/ryal-prim.svg' ); ?>" alt="Ryal" style="width: 16px;">
										<?php elseif ( $price_html ) : ?>
											<span class="y-u-text-l y-u-font-bold"><?php echo wp_kses_post( $price_html ); ?></span>
										<?php endif; ?>
									</div>
								</div>
								<div class="theme-include y-u-flex y-u-items-center y-u-gap-8 y-u-text-muted">
									<i class="fa-solid fa-tag"></i>
									<span class="y-u-text-s y-u-font-bold">السعر شامل سعر الثيم وباقة اليمامة</span>
								</div>
								<div class="theme-actions y-u-grid y-u-grid-2 y-u-gap-12">
									<a href="<?php echo esc_url( $permalink ); ?>" class="btn black-outline-button y-u-w-full">عرض</a>
									<a href="<?php echo esc_url( $permalink . '#plans' ); ?>" class="btn main-button">شراء القالب</a>
								</div>
							</div>
						</div>
						<?php
					endforeach;
				else :
					?>
					<p>لا توجد قوالب متاحة حاليًا.</p>
					<?php
				endif;
				?>
			</div>

			<button class="btn main-button y-u-w-fit y-u-mx-auto y-u-m-t-24">عرض المزيد</button>
		</div>
	</section>

	<!-- TODO: Replace featured template section with WooCommerce data. -->
	<section class="featured-template y-u-py-80">
		<div class="container y-u-max-w-1200 y-u-flex y-u-items-center y-u-justify-between y-u-gap-48">
			<div class="featured-content y-u-flex y-u-flex-col y-u-gap-16">
				<div class="featured-badge y-u-w-fit y-u-px-16 y-u-py-4 y-u-rounded-l light-primary-bg">
					<p class="y-u-text-s y-u-font-bold primary-color">
						<i class="fa-solid fa-wand-magic-sparkles"></i>
						القالب المميز
					</p>
				</div>
				<h2 class="y-u-text-xl y-u-font-bold">هل لديك "تصميم" خاص بك</h2>
				<div class="featured-actions y-u-flex y-u-gap-16 y-u-m-t-16">
					<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="btn main-button y-u-flex y-u-items-center y-u-gap-8">
						احصل عليه الآن <i class="fa-solid fa-arrow-left"></i>
					</a>
				</div>
			</div>
			<div class="featured-image">
				<img src="<?php echo esc_url( $assets_uri . '/temp-rotate.png' ); ?>" alt="Featured Template">
			</div>
		</div>
	</section>

	<section class="cta-section y-u-py-80">
		<div class="container y-u-max-w-1200">
			<div class="cta-card y-u-p-48 y-u-rounded-m y-u-text-center y-u-flex y-u-flex-col y-u-items-center y-u-gap-24 y-u-py-24">
				<h2 class="y-u-text-xxl y-u-font-bold">هل أنت مستعد لبيع منتجاتك؟</h2>
				<p class="y-u-text-m y-u-font-medium y-u-text-muted">انضم إلى آلاف التجار الناجحين وأطلق متجرك الإلكتروني اليوم</p>
				<button class="btn main-button y-u-px-48 y-u-py-16">ابدأ متجرك المجاني</button>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
?>
