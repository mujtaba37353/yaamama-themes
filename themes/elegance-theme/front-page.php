<?php
/**
 * Front Page template - Elegance (Home)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
elegance_enqueue_page_css( 'layout' );
elegance_enqueue_component_css( array( 'products' ) );

get_header();

$assets   = ELEGANCE_ELEGANCE_URI . '/assets';
$shop_url = elegance_shop_url();
$sales_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) . '?on_sale=1' : home_url( '/sales/' );
$hero_title   = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'home_hero_title', 'خليك مميز .. خليك أنيق' ) : 'خليك مميز .. خليك أنيق';
$hero_desc    = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'home_hero_desc', 'متجر اليجنس يقدم لك تشكيلات عصرية بجودة عالية وسعر يناسبك' ) : 'متجر اليجنس يقدم لك تشكيلات عصرية بجودة عالية وسعر يناسبك';
$hero_btn_t   = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'home_hero_btn_text', 'اكتشف المجموعة' ) : 'اكتشف المجموعة';
$hero_btn_u   = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'home_hero_btn_url', '' ) : '';
$hero_btn_url = $hero_btn_u ? $hero_btn_u : $shop_url;
$hero_img_1   = function_exists( 'elegance_get_image_url' ) ? elegance_get_image_url( (int) elegance_get_option( 'home_hero_image_1', 0 ), $assets . '/hero.jpg' ) : $assets . '/hero.jpg';
$hero_img_2   = function_exists( 'elegance_get_image_url' ) ? elegance_get_image_url( (int) elegance_get_option( 'home_hero_image_2', 0 ), $assets . '/hero2.jpg' ) : $assets . '/hero2.jpg';
$hero_img_3   = function_exists( 'elegance_get_image_url' ) ? elegance_get_image_url( (int) elegance_get_option( 'home_hero_image_3', 0 ), $assets . '/hero3.jpg' ) : $assets . '/hero3.jpg';
$banner_1    = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'home_banner_1_text', 'خصومات تصل لـ 50% - الحق عروض الموسم قبل ما تخلص !' ) : 'خصومات تصل لـ 50% - الحق عروض الموسم قبل ما تخلص !';
$banner_2    = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'home_banner_2_text', 'أطلق ستايلك... كل قطعة تحكي عنك' ) : 'أطلق ستايلك... كل قطعة تحكي عنك';
?>
<main>
  <section class="container y-u-max-w-1200 hero-section">
    <div class="top">
      <div class="right">
        <div class="content">
          <h1><?php echo esc_html( $hero_title ); ?></h1>
          <p><?php echo esc_html( $hero_desc ); ?></p>
          <a href="<?php echo esc_url( $hero_btn_url ); ?>" class="y-c-btn--primary"><?php echo esc_html( $hero_btn_t ); ?></a>
        </div>
      </div>
      <div class="left">
        <img src="<?php echo esc_url( $hero_img_1 ); ?>" alt="">
        <img src="<?php echo esc_url( $hero_img_2 ); ?>" alt="">
        <img src="<?php echo esc_url( $hero_img_3 ); ?>" alt="">
      </div>
    </div>
    <div class="category">
      <ul>
        <li><a href="<?php echo esc_url( $shop_url ); ?>"><img src="<?php echo esc_url( $assets . '/cat1.jpg' ); ?>" alt="">رجالى</a></li>
        <li><a href="<?php echo esc_url( $shop_url ); ?>"><img src="<?php echo esc_url( $assets . '/cat7.jpg' ); ?>" alt="">نسائى</a></li>
        <li><a href="<?php echo esc_url( $shop_url ); ?>"><img src="<?php echo esc_url( $assets . '/cat3.jpg' ); ?>" alt="">أطفال</a></li>
        <li><a href="<?php echo esc_url( $shop_url ); ?>"><img src="<?php echo esc_url( $assets . '/cat4.jpg' ); ?>" alt="">ماركات فاخرة</a></li>
        <li><a href="<?php echo esc_url( $shop_url ); ?>"><img src="<?php echo esc_url( $assets . '/cat3.jpg' ); ?>" alt="">ملابس رياضية</a></li>
        <li><a href="<?php echo esc_url( $shop_url ); ?>"><img src="<?php echo esc_url( $assets . '/cat6.jpg' ); ?>" alt="">احذية</a></li>
        <li><a href="<?php echo esc_url( $shop_url ); ?>"><img src="<?php echo esc_url( $assets . '/cat7.jpg' ); ?>" alt="">بوتيكات</a></li>
      </ul>
    </div>
  </section>

  <section class="panner panner-image">
    <h1 class="y-u-text-center"><?php echo esc_html( $banner_1 ); ?></h1>
  </section>

  <section class="container y-u-max-w-1200 discount-section">
    <div class="section-header">
      <h2>تخفيضات</h2><a href="<?php echo esc_url( $sales_url ); ?>" class="show-more-link">عرض المزيد</a>
    </div>
    <ul class="grid">
      <?php
      for ( $i = 1; $i <= 6; $i++ ) {
        $img = $assets . '/sales' . $i . '.png';
        ?>
        <li class="card">
          <div class="img product-card-media">
            <a href="<?php echo esc_url( $shop_url ); ?>">
              <img src="<?php echo esc_url( $img ); ?>" alt="">
            </a>
            <button type="button" class="product-card-action product-card-favorite" aria-label="أضف إلى المفضلة">
              <i class="fa-regular fa-heart"></i>
            </button>
            <button type="button" class="product-card-action product-card-cart" aria-label="أضف إلى السلة">
              <img src="<?php echo esc_url( $assets . '/add-to-cart.svg' ); ?>" alt="إضافة إلى السلة">
            </button>
          </div>
          <div class="content">
            <p>قميص قطن صيفى</p>
            <div class="left">
              <p>100 ريال</p>
              <p>50 ريال</p>
            </div>
          </div>
        </li>
        <?php
      }
      ?>
    </ul>
  </section>

  <section class="panner panner-image">
    <h1 class="y-u-text-center"><?php echo esc_html( $banner_2 ); ?></h1>
  </section>

  <section class="container y-u-max-w-1200 vaierty-section">
    <div class="section-header">
      <h2>منتجات متنوعة</h2><a href="<?php echo esc_url( $shop_url ); ?>" class="show-more-link">عرض المزيد</a>
    </div>
    <ul class="grid">
      <?php
      for ( $i = 1; $i <= 6; $i++ ) {
        $img = $assets . '/sales' . $i . '.png';
        ?>
        <li class="card">
          <div class="img product-card-media">
            <a href="<?php echo esc_url( $shop_url ); ?>">
              <img src="<?php echo esc_url( $img ); ?>" alt="">
            </a>
            <button type="button" class="product-card-action product-card-favorite" aria-label="أضف إلى المفضلة">
              <i class="fa-regular fa-heart"></i>
            </button>
            <button type="button" class="product-card-action product-card-cart" aria-label="أضف إلى السلة">
              <img src="<?php echo esc_url( $assets . '/add-to-cart.svg' ); ?>" alt="إضافة إلى السلة">
            </button>
          </div>
          <div class="content">
            <p>قميص قطن صيفى</p>
            <div class="left">
              <p>50 ريال</p>
            </div>
          </div>
        </li>
        <?php
      }
      ?>
    </ul>
  </section>
</main>
<?php
get_footer();
