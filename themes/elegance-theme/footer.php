<?php
/**
 * Footer template - Elegance (RTL)
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$assets       = ELEGANCE_ELEGANCE_URI . '/assets';
$footer_logo  = function_exists( 'elegance_get_image_url' ) ? elegance_get_image_url( (int) elegance_get_option( 'footer_logo', 0 ), $assets . '/footer-icon.png' ) : $assets . '/footer-icon.png';
$footer_phone = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'footer_phone', '+966 12 345 6789' ) : '+966 12 345 6789';
$footer_email = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'contact_display_email', 'Anaqa.store@gmail.com' ) : 'Anaqa.store@gmail.com';
?>
<footer class="footer">
  <div class="container y-u-max-w-1200">
    <div class="main y-u-flex y-u-flex-col ">
      <div class="logo">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">
          <img src="<?php echo esc_url( $footer_logo ); ?>" alt="footer-icon">
        </a>
      </div>
      <ul class="y-u-flex y-u-justify-between y-u-flex-col ">
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php echo esc_url( $assets . '/map.svg' ); ?>" alt=""> الرياض , المملكة العربية السعودية</a></li>
        <li><a href="mailto:<?php echo esc_attr( $footer_email ); ?>"><img src="<?php echo esc_url( $assets . '/email.svg' ); ?>" alt=""> <?php echo esc_html( $footer_email ); ?></a></li>
        <li><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $footer_phone ) ); ?>"><img src="<?php echo esc_url( $assets . '/phone.svg' ); ?>" alt=""><?php echo esc_html( $footer_phone ); ?></a></li>
      </ul>
    </div>
    <div class="links y-u-flex y-u-justify-between y-u-flex-col ">
      <ul class="y-u-flex y-u-justify-between y-u-flex-col ">
        <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسيه</a></li>
        <li><a href="<?php echo esc_url( home_url( '/404/' ) ); ?>">خدماتنا</a></li>
        <li><a href="<?php echo esc_url( home_url( '/404/' ) ); ?>">سابقة اعمالنا</a></li>
        <li><a href="<?php echo esc_url( elegance_page_url( 'about-us', '/about-us/' ) ); ?>">من نحن</a></li>
        <li><a href="<?php echo esc_url( elegance_page_url( 'contact', '/contact/' ) ); ?>">تواصل معنا</a></li>
      </ul>
    </div>
    <div class="links y-u-flex y-u-justify-between y-u-flex-col footer-policies">
      <div class="footer-policies-title">السياسات</div>
      <ul class="y-u-flex y-u-justify-between y-u-flex-col ">
        <li><a href="<?php echo esc_url( elegance_page_url( 'privacy-policy', home_url( '/privacy-policy/' ) ) ); ?>">سياسة الخصوصية</a></li>
        <li><a href="<?php echo esc_url( elegance_page_url( 'return-policy', home_url( '/return-policy/' ) ) ); ?>">سياسة الاسترجاع</a></li>
        <li><a href="<?php echo esc_url( elegance_page_url( 'shipping-policy', home_url( '/shipping-policy/' ) ) ); ?>">سياسة الشحن</a></li>
      </ul>
    </div>
    <div class="links y-u-flex y-u-justify-between y-u-flex-col ">
      <div class="top">
        <h2>طرق الدفع</h2>
        <div class="imgs-row">
          <img src="<?php echo esc_url( $assets . '/visa.png' ); ?>" alt="">
          <img src="<?php echo esc_url( $assets . '/mastercard.png' ); ?>" alt="">
        </div>
      </div>
      <p>جميع الحقوق محفوظة ل @ Yamama Solutions</p>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
