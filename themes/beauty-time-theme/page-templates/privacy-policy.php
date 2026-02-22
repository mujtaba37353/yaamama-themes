<?php
/**
 * Template Name: سياسة الخصوصية (Privacy Policy)
 * Markup from beauty-time/templates/privacy-policy/privacy-policy.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$static_options = function_exists( 'beauty_static_pages_get_options' ) ? beauty_static_pages_get_options() : array();
$privacy_sections = $static_options['privacy']['sections'] ?? array();
if ( empty( $privacy_sections ) && function_exists( 'beauty_static_pages_get_defaults' ) ) {
	$defaults = beauty_static_pages_get_defaults();
	$privacy_sections = $defaults['privacy']['sections'] ?? array();
}

get_header();
?>
<main>
  <section class="panner"></section>
  <section class="privacy-policy">
    <div class="container y-u-max-w-1200">
      <?php foreach ( $privacy_sections as $section ) : ?>
        <?php if ( ! empty( $section['title'] ) ) : ?>
          <h2><?php echo esc_html( $section['title'] ); ?></h2>
        <?php endif; ?>
        <?php if ( ! empty( $section['body'] ) ) : ?>
          <p><?php echo esc_html( $section['body'] ); ?></p>
        <?php endif; ?>
        <?php if ( ! empty( $section['image'] ) ) : ?>
          <img src="<?php echo esc_url( $section['image'] ); ?>" alt="">
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
