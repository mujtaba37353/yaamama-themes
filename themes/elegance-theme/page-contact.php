<?php
/**
 * Template Name: تواصل معنا (Contact)
 * Elegance - Contact page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
elegance_enqueue_page_css( 'contact' );
elegance_enqueue_component_css( array( 'auth' ) );

get_header();

$assets            = ELEGANCE_ELEGANCE_URI . '/assets';
$contact_title     = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'contact_page_title', 'تواصل معنا' ) : 'تواصل معنا';
$contact_content   = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'contact_page_content', 'يمكنك التواصل معنا عبر البريد الإلكتروني أو الهاتف' ) : 'يمكنك التواصل معنا عبر البريد الإلكتروني أو الهاتف';
$contact_email     = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'contact_display_email', 'elegance@gmail.com' ) : 'elegance@gmail.com';
$contact_phones    = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'contact_phones', '+966 12 345 6789' ) : '+966 12 345 6789';
$contact_address   = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'contact_address', 'الرياض , المملكة العربية السعودية' ) : 'الرياض , المملكة العربية السعودية';
$contact_facebook  = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'contact_facebook_url', '' ) : '';
$contact_instagram = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'contact_instagram_url', '' ) : '';
$contact_twitter   = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'contact_twitter_url', '' ) : '';
$contact_youtube   = function_exists( 'elegance_get_option' ) ? elegance_get_option( 'contact_youtube_url', '' ) : '';
?>
<main>
  <section class="panner y-u-m-b-0">
    <h1 class="y-u-text-center"><?php echo esc_html( $contact_title ); ?></h1>
  </section>
  <section class="container y-u-max-w-1200 contact-section y-u-p-t-32">
    <h2> معلومات التواصل</h2>
    <p><?php echo esc_html( $contact_content ); ?></p>
    <div class="contact-grid">
      <div class="contact-info">
        <img src="<?php echo esc_url( $assets . '/phone-primary.svg' ); ?>" alt="">
        <h3>الهاتف</h3>
        <p><?php echo esc_html( $contact_phones ); ?></p>
      </div>
      <div class="contact-info">
        <img src="<?php echo esc_url( $assets . '/email-primary.svg' ); ?>" alt="">
        <h3>البريد الإلكترونى</h3>
        <p><?php echo esc_html( $contact_email ); ?></p>
      </div>
      <div class="contact-info contact-social<?php echo empty( $contact_facebook ) ? ' is-empty' : ''; ?>">
        <?php if ( $contact_facebook ) : ?>
        <a href="<?php echo esc_url( $contact_facebook ); ?>" target="_blank" rel="noopener noreferrer">
          <img src="<?php echo esc_url( $assets . '/facebook-primary.svg' ); ?>" alt="">
          <h3>الفيسبوك</h3>
          <p><?php echo esc_html( wp_parse_url( $contact_facebook, PHP_URL_HOST ) ?: 'الفيسبوك' ); ?></p>
        </a>
        <?php else : ?>
        <span class="contact-social-placeholder" title="<?php esc_attr_e( 'لم يتم إضافة الرابط', 'elegance' ); ?>">
          <img src="<?php echo esc_url( $assets . '/facebook-primary.svg' ); ?>" alt="">
          <h3>الفيسبوك</h3>
          <p><?php esc_html_e( 'لم يتم الإضافة', 'elegance' ); ?></p>
        </span>
        <?php endif; ?>
      </div>
      <div class="contact-info contact-social<?php echo empty( $contact_instagram ) ? ' is-empty' : ''; ?>">
        <?php if ( $contact_instagram ) : ?>
        <a href="<?php echo esc_url( $contact_instagram ); ?>" target="_blank" rel="noopener noreferrer">
          <img src="<?php echo esc_url( $assets . '/instgram-primary.svg' ); ?>" alt="">
          <h3>الانستقرام</h3>
          <p><?php echo esc_html( wp_parse_url( $contact_instagram, PHP_URL_HOST ) ?: 'الانستقرام' ); ?></p>
        </a>
        <?php else : ?>
        <span class="contact-social-placeholder" title="<?php esc_attr_e( 'لم يتم إضافة الرابط', 'elegance' ); ?>">
          <img src="<?php echo esc_url( $assets . '/instgram-primary.svg' ); ?>" alt="">
          <h3>الانستقرام</h3>
          <p><?php esc_html_e( 'لم يتم الإضافة', 'elegance' ); ?></p>
        </span>
        <?php endif; ?>
      </div>
      <div class="contact-info contact-social<?php echo empty( $contact_twitter ) ? ' is-empty' : ''; ?>">
        <?php if ( $contact_twitter ) : ?>
        <a href="<?php echo esc_url( $contact_twitter ); ?>" target="_blank" rel="noopener noreferrer">
          <i class="fab fa-x-twitter contact-social-icon"></i>
          <h3>تويتر / X</h3>
          <p><?php echo esc_html( wp_parse_url( $contact_twitter, PHP_URL_HOST ) ?: 'تويتر' ); ?></p>
        </a>
        <?php else : ?>
        <span class="contact-social-placeholder" title="<?php esc_attr_e( 'لم يتم إضافة الرابط', 'elegance' ); ?>">
          <i class="fab fa-x-twitter contact-social-icon"></i>
          <h3>تويتر / X</h3>
          <p><?php esc_html_e( 'لم يتم الإضافة', 'elegance' ); ?></p>
        </span>
        <?php endif; ?>
      </div>
      <div class="contact-info contact-social<?php echo empty( $contact_youtube ) ? ' is-empty' : ''; ?>">
        <?php if ( $contact_youtube ) : ?>
        <a href="<?php echo esc_url( $contact_youtube ); ?>" target="_blank" rel="noopener noreferrer">
          <i class="fab fa-youtube contact-social-icon"></i>
          <h3>يوتيوب</h3>
          <p><?php echo esc_html( wp_parse_url( $contact_youtube, PHP_URL_HOST ) ?: 'يوتيوب' ); ?></p>
        </a>
        <?php else : ?>
        <span class="contact-social-placeholder" title="<?php esc_attr_e( 'لم يتم إضافة الرابط', 'elegance' ); ?>">
          <i class="fab fa-youtube contact-social-icon"></i>
          <h3>يوتيوب</h3>
          <p><?php esc_html_e( 'لم يتم الإضافة', 'elegance' ); ?></p>
        </span>
        <?php endif; ?>
      </div>
      <div class="contact-info">
        <img src="<?php echo esc_url( $assets . '/map-primary.svg' ); ?>" alt="">
        <h3>العنوان</h3>
        <p><?php echo esc_html( $contact_address ); ?></p>
      </div>
    </div>
  </section>

  <section class="auth-section">
    <div class="container y-u-max-w-1200">
      <div class="right">
        <h2> ارسل لنا استسفسارك</h2>
        <form action="" method="post">
          <?php wp_nonce_field( 'elegance_contact', 'elegance_contact_nonce' ); ?>
          <div class="form-group">
            <label for="contact-name">الاسم</label>
            <input type="text" id="contact-name" name="name">
          </div>
          <div class="form-group">
            <label for="contact-email">البريد الإلكتروني</label>
            <input type="email" id="contact-email" name="email">
          </div>
          <div class="form-group">
            <label for="contact-number"> الهاتف</label>
            <input type="tel" id="contact-number" name="number">
          </div>
          <div class="form-group">
            <label for="contact-message"> الرسالة</label>
            <input type="text" id="contact-message" name="message">
          </div>
          <button type="submit"> ارسال</button>
        </form>
      </div>
      <div class="left">
        <img src="<?php echo esc_url( $assets . '/contact-us.png' ); ?>" alt="">
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
