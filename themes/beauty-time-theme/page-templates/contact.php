<?php
/**
 * Template Name: تواصل معنا (Contact)
 * Markup from beauty-time/templates/contact/contact.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$home = home_url( '/' );
$contact_options = function_exists( 'beauty_contact_settings_get_options' ) ? beauty_contact_settings_get_options() : array();
$contact_info = $contact_options['info'] ?? array();
$contact_phone = $contact_info['phones'] ?? '+966 12 345 6789 - +966 12 345 6789';
$contact_email = $contact_info['email'] ?? 'info@beautytime.com';
$contact_address = $contact_info['address'] ?? __( 'الرياض - المملكة العربية السعودية', 'beauty-time-theme' );
$contact_hours = $contact_info['working_hours'] ?? __( 'طوال ايام الاسبوع : 2:00 ظهرًا – 10:45 مساءً', 'beauty-time-theme' );
$contact_map = $contact_info['map_embed'] ?? '';
get_header();
?>
<main>
  <section class="panner">
    <p><a href="<?php echo esc_url( $home ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a> / <?php esc_html_e( 'تواصل معنا', 'beauty-time-theme' ); ?></p>
  </section>

  <section class="contact-form-section">
    <div class="container y-u-max-w-1200">
      <div class="right">
        <h2><?php esc_html_e( 'تواصلي معنا أو زورينا !', 'beauty-time-theme' ); ?></h2>
        <p><?php esc_html_e( 'هل لديكِ استفسارات أو مقترحات ؟ رجاءً الارقام التالية', 'beauty-time-theme' ); ?></p>
        <div class="grid">
          <div class="item">
            <i class="fas fa-map-marker-alt"></i>
            <div class="left">
              <p><?php esc_html_e( 'العنوان', 'beauty-time-theme' ); ?></p>
              <p><?php echo esc_html( $contact_address ); ?></p>
            </div>
          </div>
          <div class="item">
            <i class="fas fa-phone"></i>
            <div class="left">
              <p><?php esc_html_e( 'رقم الهاتف', 'beauty-time-theme' ); ?></p>
              <p><?php echo esc_html( $contact_phone ); ?></p>
            </div>
          </div>
          <div class="item">
            <i class="fas fa-calendar-alt"></i>
            <div class="left">
              <p><?php esc_html_e( 'مواعيد العمل', 'beauty-time-theme' ); ?></p>
              <p><?php echo esc_html( $contact_hours ); ?></p>
            </div>
          </div>
          <div class="item">
            <i class="fas fa-envelope"></i>
            <div class="left">
              <p><?php esc_html_e( 'البريد الإلكتروني', 'beauty-time-theme' ); ?></p>
              <p><?php echo esc_html( $contact_email ); ?></p>
            </div>
          </div>
        </div>
      </div>
      <?php if ( $contact_map ) : ?>
      <iframe
        src="<?php echo esc_url( $contact_map ); ?>"
        width="100%" height="340" style="border:0;" allowfullscreen="" loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
