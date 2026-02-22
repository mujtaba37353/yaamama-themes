<?php
/**
 * Template Name: الاقسام (Services)
 * Markup from beauty-time/templates/services/services.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

// Get product categories
$product_categories = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
	'exclude'    => array( get_option( 'default_product_cat' ) ),
) );

// Map category names to assets
$category_assets = array(
	'العناية بالشعر'   => array( 'img' => 'services1.png', 'sub' => 'services1-sub.png' ),
	'العناية بالأظافر' => array( 'img' => 'services2.png', 'sub' => 'services2-sub.png' ),
	'العناية بالبشرة' => array( 'img' => 'services3.png', 'sub' => 'services3-sub.png' ),
	'المكياج'         => array( 'img' => 'services4.png', 'sub' => 'services4-sub.png' ),
	'العناية بالأطفال' => array( 'img' => 'services5.png', 'sub' => 'services5-sub.png' ),
	'المساج'          => array( 'img' => 'services6.png', 'sub' => 'services6-sub.png' ),
);
?>
<main>
  <section class="panner">
    <p><?php esc_html_e( 'الاقسام', 'beauty-time-theme' ); ?></p>
  </section>
  <section class="services-section">
    <div class="container y-u-max-w-1200">
      <div class="services-grid">
        <?php
        if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) {
			foreach ( $product_categories as $category ) {
				if ( 'عروض وباقات بيوتي' === $category->name || 'عروض-وباقات-بيوتي' === $category->slug ) {
					continue;
				}
				$category_link = get_term_link( $category );
				if ( is_wp_error( $category_link ) ) {
					continue;
				}
				$category_name = $category->name;
				$category_desc = $category->description ?: '';
				$assets = isset( $category_assets[ $category_name ] ) ? $category_assets[ $category_name ] : array( 'img' => '', 'sub' => '' );
				?>
        <a href="<?php echo esc_url( $category_link ); ?>">
          <div class="service-card">
            <?php if ( ! empty( $assets['img'] ) && file_exists( get_template_directory() . '/' . BEAUTY_TIME_MOCK . '/assets/' . $assets['img'] ) ) : ?>
            <img src="<?php echo esc_url( beauty_time_asset( 'assets/' . $assets['img'] ) ); ?>" alt="<?php echo esc_attr( $category_name ); ?>">
            <?php endif; ?>
            <p><?php echo esc_html( $category_name ); ?></p>
            <?php if ( $category_desc ) : ?>
            <p><?php echo esc_html( $category_desc ); ?></p>
            <?php endif; ?>
            <?php if ( ! empty( $assets['sub'] ) && file_exists( get_template_directory() . '/' . BEAUTY_TIME_MOCK . '/assets/' . $assets['sub'] ) ) : ?>
            <img src="<?php echo esc_url( beauty_time_asset( 'assets/' . $assets['sub'] ) ); ?>" alt="<?php echo esc_attr( $category_name ); ?>">
            <?php endif; ?>
          </div>
        </a>
        <?php
			}
		} else {
			// Fallback to hardcoded items if no categories exist
			$items = array(
				array( 'img' => 'services1.png', 'sub' => 'services1-sub.png', 'title' => 'العناية بالشعر', 'desc' => 'دعي شعرك يتألق بأناقة وجمال مع خدماتنا لتصفيف وعناية الشعر. نقدم لكِ الصيحات في عالم التجميل لتحقيق إطلالة رائعة تليق بكِ.' ),
				array( 'img' => 'services2.png', 'sub' => 'services2-sub.png', 'title' => 'العناية بالأظافر', 'desc' => 'استمتعي بأظافر مثالية ومتألقة مع خدماتنا للعناية بالأظافر. نقدم لكِ عناية مثالية لتحصلي على إطلالة مميزة ومظهر أنيق يلفت الأنظار.' ),
				array( 'img' => 'services3.png', 'sub' => 'services3-sub.png', 'title' => 'العناية بالبشرة', 'desc' => 'استعدي لتجربة عناية فريدة ببشرتك مع خدماتنا المتميزة لتنظيف الوجه، نقدم لك منتجات ذات جودة عالية لتحقيق بشرة صحية ومتألقة.' ),
				array( 'img' => 'services4.png', 'sub' => 'services4-sub.png', 'title' => 'المكياج', 'desc' => 'أبرزي جمالكِ الطبيعي وأضيفي لمسة من الأناقة والجاذبية على مظهرك، لتشعري بالثقة والجمال في كل مناسبة مع خدماتنا للمكياج' ),
				array( 'img' => 'services5.png', 'sub' => 'services5-sub.png', 'title' => 'العناية بالأطفال', 'desc' => 'دعي أطفالك يستمتعون بتجربة عناية مميزة للشعر والبشرة، مع خدمات العناية بالأطفال حيث نقدم لهم أعلى مستويات الرعاية لضمان صحتهم وجمالهم الطبيعي.' ),
				array( 'img' => 'services6.png', 'sub' => 'services6-sub.png', 'title' => 'المساج', 'desc' => 'استمتعي بتجربة فريدة من نوعها للاسترخاء والتجديد مع خدمة المساج المتميزة، حيث تتخلصين من ضغوط الحياة وتستعيدين طاقتكِ وحيويتكِ.' ),
			);
			foreach ( $items as $s ) :
				?>
        <a href="<?php echo esc_url( home_url( '/product-category/' . sanitize_title( $s['title'] ) . '/' ) ); ?>">
          <div class="service-card">
            <img src="<?php echo esc_url( beauty_time_asset( 'assets/' . $s['img'] ) ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>">
            <p><?php echo esc_html( $s['title'] ); ?></p>
            <p><?php echo esc_html( $s['desc'] ); ?></p>
            <?php if ( file_exists( get_template_directory() . '/' . BEAUTY_TIME_MOCK . '/assets/' . $s['sub'] ) ) : ?>
            <img src="<?php echo esc_url( beauty_time_asset( 'assets/' . $s['sub'] ) ); ?>" alt="<?php echo esc_attr( $s['title'] ); ?>">
            <?php endif; ?>
          </div>
        </a>
        <?php
			endforeach;
		}
		?>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
