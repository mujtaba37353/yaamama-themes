<?php
/**
 * Footer template part — markup from beauty-time/components/footer.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$mock = beauty_time_mock_uri();
$home = home_url( '/' );
$services = function_exists( 'beauty_theme_get_page_link' ) ? beauty_theme_get_page_link( 'services', home_url( '/services' ) ) : home_url( '/services' );
$onsale = function_exists( 'beauty_theme_get_page_link' ) ? beauty_theme_get_page_link( 'onsale', home_url( '/onsale' ) ) : home_url( '/onsale' );
$privacy = function_exists( 'beauty_theme_get_page_link' ) ? beauty_theme_get_page_link( 'privacy', home_url( '/privacy-policy' ) ) : home_url( '/privacy-policy' );
$terms = function_exists( 'beauty_theme_get_page_link' ) ? beauty_theme_get_page_link( 'terms', home_url( '/terms-of-use' ) ) : home_url( '/terms-of-use' );
$contact = function_exists( 'beauty_theme_get_page_link' ) ? beauty_theme_get_page_link( 'contact', home_url( '/contact' ) ) : home_url( '/contact' );
$demo_options = function_exists( 'beauty_demo_site_get_options' ) ? beauty_demo_site_get_options() : array();
$logo_footer = $demo_options['logos']['footer'] ?? beauty_time_asset( 'assets/navbar-icon.png' );
$logo_footer_alt = $demo_options['logos']['footer_alt'] ?? beauty_time_asset( 'assets/footer-icon.png' );
$footer_paragraph = $demo_options['footer']['paragraph'] ?? __( 'الجمال والأناقة في صالون بيوتي، نقدم لكِ أحدث صيحات الجمال وخدمات التجميل لإطلالة متألقة تبرز جمالك.', 'beauty-time-theme' );
$contact_options = function_exists( 'beauty_contact_settings_get_options' ) ? beauty_contact_settings_get_options() : array();
$contact_info = $contact_options['info'] ?? array();
$contact_phone = $contact_info['phones'] ?? '+966 12 345 6789';
$contact_email = $contact_info['email'] ?? 'info@beautytime.com';
$contact_address = $contact_info['address'] ?? __( 'الرياض, المملكة العربية السعودية', 'beauty-time-theme' );
$whatsapp_number = $contact_options['whatsapp']['number'] ?? '';
$phone_link = $contact_phone;
if ( false !== strpos( $phone_link, '-' ) ) {
	$parts = explode( '-', $phone_link );
	$phone_link = trim( $parts[0] );
}
$phone_link = preg_replace( '/[^0-9+]/', '', $phone_link );
$map_link = 'https://maps.google.com/?q=' . rawurlencode( $contact_address );
$whatsapp_digits = preg_replace( '/\D+/', '', $whatsapp_number );
?>
<footer class="footer">
  <div class="container y-u-max-w-1200">
    <div class="logo">
      <div class="links y-u-flex y-u-flex-col">
        <a href="<?php echo esc_url( $home ); ?>">
          <img src="<?php echo esc_url( $logo_footer ); ?>" alt="<?php esc_attr_e( 'Beauty Time', 'beauty-time-theme' ); ?>">
          <img src="<?php echo esc_url( $logo_footer_alt ); ?>" alt="">
        </a>
        <ul class="y-u-flex y-u-justify-between y-u-flex-col">
          <p><?php echo esc_html( $footer_paragraph ); ?></p>
        </ul>
      </div>
      <div class="links y-u-flex y-u-flex-col">
        <h2><?php esc_html_e( 'الصفحات', 'beauty-time-theme' ); ?></h2>
        <ul class="y-u-flex y-u-justify-between y-u-flex-col">
          <li><a href="<?php echo esc_url( $home ); ?>"><?php esc_html_e( 'الرئيسيه', 'beauty-time-theme' ); ?></a></li>
          <li><a href="<?php echo esc_url( $services ); ?>"><?php esc_html_e( 'الاقسام', 'beauty-time-theme' ); ?></a></li>
          <li><a href="<?php echo esc_url( $onsale ); ?>"><?php esc_html_e( 'العروض والباقات', 'beauty-time-theme' ); ?></a></li>
          <li><a href="<?php echo esc_url( $privacy ); ?>"><?php esc_html_e( 'سياسة الخصوصية', 'beauty-time-theme' ); ?></a></li>
          <li><a href="<?php echo esc_url( $terms ); ?>"><?php esc_html_e( 'سياسة الاستخدام', 'beauty-time-theme' ); ?></a></li>
          <li><a href="<?php echo esc_url( $contact ); ?>"><?php esc_html_e( 'تواصل معنا', 'beauty-time-theme' ); ?></a></li>
        </ul>
      </div>
      <div class="links y-u-flex y-u-flex-col">
        <h2><?php esc_html_e( 'السياسات', 'beauty-time-theme' ); ?></h2>
        <ul class="y-u-flex y-u-justify-between y-u-flex-col">
          <li><a href="tel:<?php echo esc_attr( $phone_link ); ?>"><i class="fas fa-phone"></i> <?php echo esc_html( $contact_phone ); ?></a></li>
          <li><a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><i class="fas fa-envelope"></i> <?php echo esc_html( $contact_email ); ?></a></li>
          <li><a href="<?php echo esc_url( $map_link ); ?>"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html( $contact_address ); ?></a></li>
        </ul>
      </div>
    </div>
    <div class="bottom">
      <p><?php esc_html_e( 'جميع الحقوق محفوظة ل Yamama solutions', 'beauty-time-theme' ); ?></p>
      <div class="imgs-row">
        <img src="<?php echo esc_url( beauty_time_asset( 'assets/visa.png' ) ); ?>" alt="">
        <img src="<?php echo esc_url( beauty_time_asset( 'assets/master-card.png' ) ); ?>" alt="">
        <img src="<?php echo esc_url( beauty_time_asset( 'assets/apple-pay.png' ) ); ?>" alt="">
        <img src="<?php echo esc_url( beauty_time_asset( 'assets/stc-pay.png' ) ); ?>" alt="">
        <img src="<?php echo esc_url( beauty_time_asset( 'assets/samsung-pay.png' ) ); ?>" alt="">
      </div>
    </div>
  </div>
</footer>
<?php if ( $whatsapp_digits ) : ?>
  <a class="whatsapp-float" href="<?php echo esc_url( 'https://wa.me/' . $whatsapp_digits ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'تواصل عبر واتساب', 'beauty-time-theme' ); ?>">
    <i class="fab fa-whatsapp"></i>
  </a>
<?php endif; ?>
