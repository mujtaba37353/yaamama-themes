<?php
/**
 * 404 template — markup from beauty-time/templates/404/404.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();
$home = home_url( '/' );
?>
<main class="error-main y-u-flex y-u-justify-center y-u-items-center y-u-text-center">
  <div class="container">
    <div class="error-content y-u-max-w-600">
      <a href="<?php echo esc_url( $home ); ?>"><img src="<?php echo esc_url( beauty_time_asset( 'assets/404.png' ) ); ?>" alt=""></a>
      <p><?php esc_html_e( 'هنالك العديد من الأنواع المتوفرة لنصوص لوريم إيبسوم، ولكن الغالبية تم تعديلها بشكل ما عبر إدخال بعض النوادر أو الكلمات العشوائية إلى النص.', 'beauty-time-theme' ); ?></p>
      <a href="<?php echo esc_url( $home ); ?>" class="btn"><i class="fas fa-arrow-right"></i> <?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a>
    </div>
  </div>
</main>
<?php get_footer(); ?>
