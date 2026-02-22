<?php
/**
 * Template Name: من نحن (About Us)
 * Markup from beauty-time/templates/about-us/about-us.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$home = home_url( '/' );
$static_options = function_exists( 'beauty_static_pages_get_options' ) ? beauty_static_pages_get_options() : array();
$about = $static_options['about'] ?? array();
$demo_options = function_exists( 'beauty_demo_site_get_options' ) ? beauty_demo_site_get_options() : array();
$mid_banner = $demo_options['mid_banner'] ?? array();
if ( empty( $mid_banner ) && function_exists( 'beauty_demo_site_get_defaults' ) ) {
	$defaults = beauty_demo_site_get_defaults();
	$mid_banner = $defaults['mid_banner'] ?? array();
}
get_header();
?>
<main>
  <section class="panner">
    <p><a href="<?php echo esc_url( $home ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a> / <?php esc_html_e( 'من نحن', 'beauty-time-theme' ); ?></p>
  </section>

  <section class="about-us-section">
    <div class="container y-u-max-w-1200">
      <div class="right">
        <h2><?php echo esc_html( $about['section_one_title'] ?? __( 'من نحن', 'beauty-time-theme' ) ); ?></h2>
        <p><?php echo esc_html( $about['section_one_subtitle'] ?? __( 'اكتشفي جمالك الحقيقي معنا في صالون بيوتي', 'beauty-time-theme' ) ); ?></p>
        <p><?php echo esc_html( $about['section_one_body'] ?? __( 'نحن في صالون " بيوتي " نؤمن بأن الجمال هو عنصر أساسي في الثقة بالنفس والسعادة. صالوننا هو مكان للتجميل والاسترخاء حيث نقدم لكن تجربة مميزة وفريدة في عالم الجمال والأناقة. بفضل فريقنا المختص والمحترف، نسعى لتحقيق أحلامكن وتلبية توقعاتكن الجمالية. نحن نقدم مجموعة واسعة من الخدمات، بما في ذلك تصفيف الشعر، العناية بالبشرة، العناية بالأظافر، والمكياج، وخدمات التجميل المتقدمة.', 'beauty-time-theme' ) ); ?></p>
      </div>
      <img src="<?php echo esc_url( beauty_time_asset( 'assets/about-us-1.png' ) ); ?>" alt="">
    </div>
  </section>

  <section class="panner-hero">
    <div class="container y-u-max-w-1200">
      <div class="cards">
        <?php
        $count = 0;
        foreach ( $mid_banner as $item ) :
			$count++;
			$icon = $item['icon'] ?? 'fa-star';
			$number = $item['number'] ?? '';
			$label = $item['label'] ?? '';
			?>
        <div class="card"><i class="fas <?php echo esc_attr( $icon ); ?>"></i><p><?php echo esc_html( $number ); ?></p><p class="last"><?php echo esc_html( $label ); ?></p></div>
        <?php endforeach; ?>
        <?php if ( 0 === $count ) : ?>
        <div class="card"><i class="fas fa-star"></i><p>+75</p><p class="last"><?php esc_html_e( 'خدمات مميزة', 'beauty-time-theme' ); ?></p></div>
        <div class="card"><i class="fas fa-spa"></i><p>+100</p><p class="last"><?php esc_html_e( 'منتجات اصلية', 'beauty-time-theme' ); ?></p></div>
        <div class="card"><i class="fas fa-leaf"></i><p>+500</p><p class="last"><?php esc_html_e( 'منتجات طبيعية', 'beauty-time-theme' ); ?></p></div>
        <div class="card"><i class="fas fa-award"></i><p>+20</p><p class="last"><?php esc_html_e( 'سنوات خبرة', 'beauty-time-theme' ); ?></p></div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="about-us-section">
    <div class="container y-u-max-w-1200">
      <img src="<?php echo esc_url( beauty_time_asset( 'assets/about-us-2.png' ) ); ?>" alt="">
      <div class="right">
        <h2><?php echo esc_html( $about['section_two_title'] ?? __( 'لماذا تختارنا ؟؟', 'beauty-time-theme' ) ); ?></h2>
        <p><?php echo esc_html( $about['section_two_subtitle'] ?? __( 'أكثر من 15 سنة خبرة في مجال تجميل و العناية بالشعر و الأظافر', 'beauty-time-theme' ) ); ?></p>
        <p><?php echo esc_html( $about['section_two_body'] ?? __( 'نحن نعطي الأولوية لنظافة وراحة عميلاتنا، ونضمن لكِ بيئة صحية ومريحة طوال زيارتك، يلتزم صالوننا بتعقيم أدواتنا وبجميع بروتوكولات التعقيم الصارمة، وأدوات الاستخدام الواحد', 'beauty-time-theme' ) ); ?></p>
        <div class="bottom">
          <?php
          $features = $about['features'] ?? array();
          foreach ( $features as $feature ) :
            $feature_icon = $feature['icon'] ?? 'fa-star';
            $feature_title = $feature['title'] ?? '';
            $feature_body = $feature['body'] ?? '';
            ?>
          <div class="item">
            <i class="fas <?php echo esc_attr( $feature_icon ); ?>"></i>
            <div class="content">
              <p><?php echo esc_html( $feature_title ); ?></p>
              <p><?php echo esc_html( $feature_body ); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
