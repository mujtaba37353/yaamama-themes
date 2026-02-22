<?php
/**
 * Template Name: من نحن
 * Template for About Us page — Sweet House design
 * @see design: sweet-house/templates/about-us/layout.html, components/about us/y-c-about-us.html
 *
 * @package Sweet_House_Theme
 */

get_header();

$about_content = function_exists( 'sweet_house_get_about_content' ) ? sweet_house_get_about_content() : array();
$about_banner = function_exists( 'sweet_house_content_image_url' ) ? sweet_house_content_image_url( isset( $about_content['banner_img'] ) ? $about_content['banner_img'] : 0, 'assets/panner.png' ) : sweet_house_asset_uri( 'assets/panner.png' );
$about1_img = function_exists( 'sweet_house_content_image_url' ) ? sweet_house_content_image_url( isset( $about_content['about1_img'] ) ? $about_content['about1_img'] : 0, 'assets/about1.png' ) : sweet_house_asset_uri( 'assets/about1.png' );
$about2_img = function_exists( 'sweet_house_content_image_url' ) ? sweet_house_content_image_url( isset( $about_content['about2_img'] ) ? $about_content['about2_img'] : 0, 'assets/about2.png' ) : sweet_house_asset_uri( 'assets/about2.png' );
$block1_content = isset( $about_content['block1'] ) ? '<p>' . esc_html( $about_content['block1'] ) . '</p>' : '<p>' . esc_html__( 'سويت هاوس متجر متخصص في الحلويات والمخبوزات الطازجة. نلتزم بتقديم أجود المكونات وألذ النكهات لعملائنا الكرام.', 'sweet-house-theme' ) . '</p>';
$block2_content = isset( $about_content['block2'] ) ? '<p>' . esc_html( $about_content['block2'] ) . '</p>' : '<p>' . esc_html__( 'نسعى دائماً لتجربة العملاء وتقديم أفضل الخدمات. زورونا واستمتعوا بأجود المنتجات.', 'sweet-house-theme' ) . '</p>';

while ( have_posts() ) :
	the_post();
	$content     = get_the_content( null, false );
	$content_arr = preg_split( '/<!--\s*more\s*-->/', $content, 2 );
	$block1_raw  = ! empty( $content_arr[0] ) ? trim( $content_arr[0] ) : '';
	$block2_raw  = ! empty( $content_arr[1] ) ? trim( $content_arr[1] ) : '';
	$block1      = $block1_raw ? apply_filters( 'the_content', $block1_raw ) : $block1_content;
	$block2      = $block2_raw ? apply_filters( 'the_content', $block2_raw ) : $block2_content;
?>
<header data-y="design-header" class="about-design-header">
	<img src="<?php echo esc_url( $about_banner ); ?>" alt="<?php esc_attr_e( 'بانر سويت هاوس - من نحن', 'sweet-house-theme' ); ?>" class="panner-img" />
</header>

<main data-y="main">
	<div class="main-container">
		<nav class="y-breadcrumb-container" aria-label="<?php esc_attr_e( 'مسار التنقل', 'sweet-house-theme' ); ?>">
			<ol class="y-breadcrumb">
				<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
				<li class="y-breadcrumb-item active"><?php esc_html( get_the_title() ); ?></li>
			</ol>
		</nav>

		<div class="about-us">
			<div class="about-card">
				<img src="<?php echo esc_url( $about1_img ); ?>" alt="<?php esc_attr_e( 'صورة عن سويت هاوس - متجر الحلويات والمخبوزات', 'sweet-house-theme' ); ?>" />
				<div>
					<?php echo $block1 ? wp_kses_post( $block1 ) : wp_kses_post( $block1_content ); ?>
				</div>
			</div>
			<div class="stats">
				<div class="card">
					<h1><?php echo esc_html( isset( $about_content['stat1_num'] ) ? $about_content['stat1_num'] : '+200' ); ?></h1>
					<p><?php echo esc_html( isset( $about_content['stat1_text'] ) ? $about_content['stat1_text'] : __( 'عملاء سعداء', 'sweet-house-theme' ) ); ?></p>
				</div>
				<div class="card">
					<h1><?php echo esc_html( isset( $about_content['stat2_num'] ) ? $about_content['stat2_num'] : '+10' ); ?></h1>
					<p><?php echo esc_html( isset( $about_content['stat2_text'] ) ? $about_content['stat2_text'] : __( 'سنوات الخبرة', 'sweet-house-theme' ) ); ?></p>
				</div>
				<div class="card">
					<h1><?php echo esc_html( isset( $about_content['stat3_num'] ) ? $about_content['stat3_num'] : '+20' ); ?></h1>
					<p><?php echo esc_html( isset( $about_content['stat3_text'] ) ? $about_content['stat3_text'] : __( 'عملاء الجملة', 'sweet-house-theme' ) ); ?></p>
				</div>
			</div>
			<div class="about-card">
				<div>
					<?php echo $block2 ? wp_kses_post( $block2 ) : wp_kses_post( $block2_content ); ?>
				</div>
				<img src="<?php echo esc_url( $about2_img ); ?>" alt="<?php esc_attr_e( 'صورة عن سويت هاوس - مخبوزات طازجة وحلويات لذيذة', 'sweet-house-theme' ); ?>" />
			</div>
		</div>
	</div>
</main>

<?php
endwhile;
get_footer();
