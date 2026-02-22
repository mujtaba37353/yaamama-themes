<?php
/**
 * WooCommerce Single Product - Elegance
 */
defined( 'ABSPATH' ) || exit;

get_header();
?>
<main>
<?php
if ( have_posts() ) {
  while ( have_posts() ) {
    the_post();
    wc_get_template_part( 'content', 'single-product' );
  }
}
?>
</main>
<?php
get_footer();
