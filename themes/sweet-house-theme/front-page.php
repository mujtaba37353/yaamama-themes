<?php
/**
 * Front Page — layout from Sweet House home design.
 * التصنيفات and المنتجات sections use real WooCommerce data.
 *
 * @package Sweet_House_Theme
 */

get_header();

$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : sweet_house_get_page_url( 'shop' );
?>

<main data-y="main" class="main-page">
	<div class="main-container">
		<div data-y="categories-sec">
			<?php sweet_house_render_home_categories(); ?>
		</div>
	</div>
	<?php
	$home = function_exists( 'sweet_house_get_home_content' ) ? sweet_house_get_home_content() : array();
	$mid_banner = function_exists( 'sweet_house_content_image_url' ) ? sweet_house_content_image_url( isset( $home['mid_banner_img'] ) ? $home['mid_banner_img'] : 0, 'assets/panner.png' ) : sweet_house_asset_uri( 'assets/panner.png' );
	?>
	<div data-y="design-header">
		<img src="<?php echo esc_url( $mid_banner ); ?>" alt="<?php esc_attr_e( 'بانر سويت هاوس - متجر الحلويات والمخبوزات', 'sweet-house-theme' ); ?>" class="panner-img" />
	</div>
	<div class="main-container">
		<div data-y="products-sec">
			<?php sweet_house_render_home_products(); ?>
		</div>
		<div data-y="about-sec">
			<?php
			$home = function_exists( 'sweet_house_get_home_content' ) ? sweet_house_get_home_content() : array();
			$about_img1 = function_exists( 'sweet_house_content_image_url' ) ? sweet_house_content_image_url( isset( $home['about_img1'] ) ? $home['about_img1'] : 0, 'assets/about-home1.png' ) : sweet_house_asset_uri( 'assets/about-home1.png' );
			$about_img2 = function_exists( 'sweet_house_content_image_url' ) ? sweet_house_content_image_url( isset( $home['about_img2'] ) ? $home['about_img2'] : 0, 'assets/about-home2.png' ) : sweet_house_asset_uri( 'assets/about-home2.png' );
			$about_title = isset( $home['about_title'] ) ? $home['about_title'] : 'الخبز هو شغفنا';
			$about_subtitle = isset( $home['about_subtitle'] ) ? $home['about_subtitle'] : 'شغف نحمله منذ البداية';
			$about_text = isset( $home['about_text'] ) ? $home['about_text'] : 'في كل صباح عند انتشار رائحة مخبوزاتنا في الأرجاء، لتصل إلى زبائننا شهيّة وبمعايير عالية من الخدمة واهتمام بأدق تفاصيل التجربة، نتابع كلّ خطوة لنحافظ على سلامة الغذاء ونرسم الخطط ليوم جديد مليء بما لذّ وطابّ';
			?>
			<section class="my-5 cairo-font about-section" aria-label="<?php echo esc_attr__( 'من نحن', 'sweet-house-theme' ); ?>">
				<div class="container">
					<div class="row align-items-center">
						<div class="content">
							<h1 class="y-u-bluetxt fs-2 fs-lg-1 cairo-font"><?php echo esc_html( $about_title ); ?></h1>
							<h2 class="y-u-bluetxt fs-3 fs-lg-2 cairo-font"><?php echo esc_html( $about_subtitle ); ?></h2>
							<div class="pink-border my-4 mx-auto mx-lg-0" style="width: 50%;"></div>
							<p class="fs-5 fs-lg-4">
								<?php echo esc_html( $about_text ); ?>
							</p>
						</div>
						<div class="about-img-container">
							<img src="<?php echo esc_url( $about_img1 ); ?>" alt="<?php esc_attr_e( 'خبز', 'sweet-house-theme' ); ?>" class="img-fluid rounded shadow about-img">
							<img src="<?php echo esc_url( $about_img2 ); ?>" alt="<?php esc_attr_e( 'مخبوزات', 'sweet-house-theme' ); ?>" class="img-fluid rounded shadow about-img">
						</div>
					</div>
				</div>
			</section>
		</div>
		<div data-y="features-sec">
			<?php
			$home = function_exists( 'sweet_house_get_home_content' ) ? sweet_house_get_home_content() : array();
			$feats = array();
			for ( $i = 1; $i <= 4; $i++ ) {
				$feats[ $i ] = array(
					'icon'  => function_exists( 'sweet_house_content_image_url' ) ? sweet_house_content_image_url( isset( $home[ "feat{$i}_icon" ] ) ? $home[ "feat{$i}_icon" ] : 0, "assets/feat{$i}.png" ) : sweet_house_asset_uri( "assets/feat{$i}.png" ),
					'title' => isset( $home[ "feat{$i}_title" ] ) ? $home[ "feat{$i}_title" ] : '',
					'text'  => isset( $home[ "feat{$i}_text" ] ) ? $home[ "feat{$i}_text" ] : '',
				);
			}
			?>
			<section class="my-5 cairo-font features-section" aria-label="<?php echo esc_attr__( 'مميزاتنا', 'sweet-house-theme' ); ?>">
				<div class="container">
					<div class="row align-items-center justify-content-center mx-auto">
						<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
						<div class="col-6 col-md-3 text-center mb-4 mb-md-0">
							<img src="<?php echo esc_url( $feats[ $i ]['icon'] ); ?>" alt="<?php echo esc_attr( $feats[ $i ]['title'] ); ?>" class="mb-2 feature-icon">
							<p class="fs-6 fs-md-5 fw-bold"><?php echo esc_html( $feats[ $i ]['title'] ); ?></p>
							<p class="fs-6 fs-md-5"><?php echo esc_html( $feats[ $i ]['text'] ); ?></p>
						</div>
						<?php endfor; ?>
					</div>
				</div>
			</section>
		</div>
	</div>
</main>

<?php get_footer(); ?>
