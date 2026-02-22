<?php
/**
 * Template Name: من نحن (About Us)
 * Elegance - About Us page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
elegance_enqueue_page_css( 'about-us' );

get_header();

$assets = ELEGANCE_ELEGANCE_URI . '/assets';
?>
<main>
  <section class="panner y-u-m-b-0">
    <h1 class="y-u-text-center">من نحن</h1>
  </section>

  <section class="about-us-section">
    <div class="container y-u-max-w-1200">
      <div class="right">
        <?php
        while ( have_posts() ) {
          the_post();
          if ( get_the_content() ) {
            the_content();
          } else {
            ?>
            <p>Lorem ipsum dolor sit amet consectetur. Pellentesque lacus pulvinar imperdiet cursus dui amet a amet. Enim
              eget etiam varius sed. Mattis arcu sed non tempus dui consequat pellentesque mattis. Orci sagittis eget nisi
              nunc quis sed.
              Iaculis malesuada id nisl pellentesque diam tempus iaculis pretium magna. Diam purus sit enim hendrerit.
              Facilisis ac aliquet pretium ullamcorper. In erat in purus nund.</p>
            <?php
          }
        }
        ?>
      </div>
      <div class="left">
        <?php
        $about_img = function_exists( 'elegance_get_image_url' ) ? elegance_get_image_url( (int) elegance_get_option( 'about_image', 0 ), $assets . '/abouts-us.png' ) : $assets . '/abouts-us.png';
        ?>
        <img src="<?php echo esc_url( $about_img ); ?>" alt="">
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
