<?php
/**
 * 404 template - Elegance
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
elegance_enqueue_page_css( '404' );

get_header();

$assets = ELEGANCE_ELEGANCE_URI . '/assets';
?>
<main class="error-main y-u-flex y-u-justify-center y-u-items-center y-u-text-center">
  <div class="container">
    <div class="error-content y-u-max-w-600">
      <img src="<?php echo esc_url( $assets . '/error.png' ); ?>" alt="">
    </div>
  </div>
</main>
<?php
get_footer();
