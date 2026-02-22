<?php
/**
 * Template Name: تأكيد الحجز (Booking Success)
 * Markup from beauty-time/templates/process/payment-sucess.html. Phase E: dynamic data from booking.
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$home = home_url( '/' );
$booking_id = isset( $_GET['booking_id'] ) ? absint( $_GET['booking_id'] ) : 0;
$booking = $booking_id ? beauty_get_booking( $booking_id ) : false;

if ( ! $booking ) {
	wp_redirect( home_url( '/' ) );
	exit;
}

get_header();
?>
<main>
  <section class="panner">
    <p><a href="<?php echo esc_url( $home ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a> / <?php esc_html_e( 'تأكيد الحجز', 'beauty-time-theme' ); ?></p>
  </section>
  <section class="profile-section">
    <div class="container y-u-max-w-1200">
      <div class="content">
        <div class="success-content">
          <div class="appointment-confirmation">
            <div class="celebration-icon">🎉</div>
            <h2 class="congratulations"><?php esc_html_e( 'تهانينا!', 'beauty-time-theme' ); ?></h2>
            <p class="appointment-id"><?php esc_html_e( 'رقم معرف الموعد', 'beauty-time-theme' ); ?> <strong>#<?php echo esc_html( $booking['id'] ); ?></strong></p>
            <div class="appointment-details">
              <div class="details-column labels-column">
                <p><?php esc_html_e( 'التاريخ:', 'beauty-time-theme' ); ?></p>
                <p><?php esc_html_e( 'الوقت المحلي:', 'beauty-time-theme' ); ?></p>
                <p><?php esc_html_e( 'الخدمات:', 'beauty-time-theme' ); ?></p>
                <p><?php esc_html_e( 'الموظف:', 'beauty-time-theme' ); ?></p>
                <div class="divider"></div>
                <p><?php esc_html_e( 'الاسم:', 'beauty-time-theme' ); ?></p>
                <p><?php esc_html_e( 'رقم الهاتف:', 'beauty-time-theme' ); ?></p>
              </div>
              <div class="details-column values-column">
                <p><?php echo esc_html( $booking['date'] ? date_i18n( get_option( 'date_format' ), strtotime( $booking['date'] ) ) : '—' ); ?></p>
                <p><?php echo esc_html( $booking['time'] ?: '—' ); ?></p>
                <p><?php echo esc_html( $booking['services'] ?: '—' ); ?></p>
                <p><?php esc_html_e( 'Beauty Salon', 'beauty-time-theme' ); ?></p>
                <div class="divider"></div>
                <p><?php echo esc_html( $booking['customer_name'] ?: '—' ); ?></p>
                <p><?php echo esc_html( $booking['phone'] ?: '—' ); ?></p>
              </div>
            </div>
            <div class="appointment-footer">
              <a href="<?php echo esc_url( $home ); ?>" class="btn btn-primary"><?php esc_html_e( 'العودة للرئيسية', 'beauty-time-theme' ); ?></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
