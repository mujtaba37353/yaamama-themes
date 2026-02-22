<?php
/**
 * Booking form — markup from beauty-time/templates/process/process.html
 * Used by page-templates/booking.php. Form posts to booking-success (Phase E).
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$home    = home_url( '/' );
$success = home_url( '/booking-success' );

$payment_gateways = array();
if ( class_exists( 'WooCommerce' ) ) {
	$gateway_controller = WC()->payment_gateways();
	if ( $gateway_controller ) {
		$payment_gateways = $gateway_controller->get_available_payment_gateways();
	}
}

if ( empty( $payment_gateways ) ) {
	$payment_gateways = array(
		'cash' => (object) array(
			'id'          => 'cash',
			'title'       => __( 'الدفع كاش', 'beauty-time-theme' ),
			'description' => __( 'سيتم الدفع نقداً عند الاستلام', 'beauty-time-theme' ),
		),
	);
}

$payment_default_id = '';
foreach ( $payment_gateways as $gateway_id => $gateway ) {
	$payment_default_id = $gateway_id;
	break;
}

$gateway_icons = array(
	'cod'        => 'fas fa-money-bill-wave',
	'cash'       => 'fas fa-money-bill-wave',
	'stripe'     => 'fas fa-credit-card',
	'paypal'     => 'fab fa-paypal',
	'stc'        => 'fas fa-mobile-alt',
	'stc-pay'    => 'fas fa-mobile-alt',
	'apple-pay'  => 'fab fa-apple-pay',
	'applepay'   => 'fab fa-apple-pay',
	'tabby'      => 'fas fa-wallet',
	'tamara'     => 'fas fa-credit-card',
	'bacs'       => 'fas fa-university',
	'cheque'     => 'fas fa-money-check',
);

$gateway_images = array(
	'stc'       => 'assets/stc-pay.png',
	'stc-pay'   => 'assets/stc-pay.png',
	'apple-pay' => 'assets/apple-pay.png',
	'applepay'  => 'assets/apple-pay.png',
	'tabby'     => 'assets/taby-pay.png',
	'tamara'    => 'assets/tmara-pay.png',
);

$gateway_titles = array(
	'cod'        => __( 'الدفع عند الاستلام', 'beauty-time-theme' ),
	'cash'       => __( 'الدفع كاش', 'beauty-time-theme' ),
	'bacs'       => __( 'تحويل بنكي مباشر', 'beauty-time-theme' ),
	'cheque'     => __( 'شيك', 'beauty-time-theme' ),
	'stripe'     => __( 'بطاقة بنكية', 'beauty-time-theme' ),
	'paypal'     => __( 'باي بال', 'beauty-time-theme' ),
	'stc'        => __( 'STC Pay', 'beauty-time-theme' ),
	'stc-pay'    => __( 'STC Pay', 'beauty-time-theme' ),
	'apple-pay'  => __( 'Apple Pay', 'beauty-time-theme' ),
	'applepay'   => __( 'Apple Pay', 'beauty-time-theme' ),
	'tabby'      => __( 'Tabby', 'beauty-time-theme' ),
	'tamara'     => __( 'Tamara', 'beauty-time-theme' ),
);

$service_locked = false;
$locked_service_name = '';
$product_id = isset( $_GET['product_id'] ) ? absint( $_GET['product_id'] ) : 0;
if ( $product_id && function_exists( 'wc_get_product' ) ) {
	$product = wc_get_product( $product_id );
	if ( $product ) {
		$service_locked = true;
		$locked_service_name = $product->get_name();
	}
}
$working_hours_note = get_option( 'beauty_working_hours_note', '' );
?>
<section class="panner">
  <p><a href="<?php echo esc_url( $home ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a> / <?php esc_html_e( 'الحجز', 'beauty-time-theme' ); ?></p>
</section>

  <section class="profile-section">
  <div class="container y-u-max-w-1200">
    <div class="bottom">
      <div class="sidbar">
        <div class="tabs">
          <button class="active" data-tab="service"><i class="fas fa-list-check"></i> <?php esc_html_e( 'اختيار الخدمة', 'beauty-time-theme' ); ?></button>
          <button data-tab="date"><i class="fas fa-calendar-alt"></i> <?php esc_html_e( 'التاريخ و الوقت', 'beauty-time-theme' ); ?></button>
          <button data-tab="info"><i class="fas fa-user"></i> <?php esc_html_e( 'معلوماتك', 'beauty-time-theme' ); ?></button>
          <button data-tab="payment"><i class="fas fa-credit-card"></i> <?php esc_html_e( 'الدفع', 'beauty-time-theme' ); ?></button>
        </div>
        <div class="bottom">
          <p><i class="fas fa-phone"></i> <span>+966536520112</span></p>
          <p><i class="fas fa-envelope"></i> <span>beautimie@gmail.com</span></p>
        </div>
      </div>
      <div class="content">
        <div class="service-content tab-content active" data-content="service">
          <div class="top">
            <h2><?php esc_html_e( 'اختيار الخدمة', 'beauty-time-theme' ); ?></h2>
            <div class="custome-list<?php echo $service_locked ? ' service-locked' : ''; ?>" data-selected-service="<?php echo esc_attr( $locked_service_name ); ?>" data-service-locked="<?php echo $service_locked ? '1' : '0'; ?>">
              <input type="checkbox" id="service-list-toggle" name="service-list-toggle" <?php disabled( $service_locked ); ?>>
              <label for="service-list-toggle">
                <span class="selected-service-text"><?php echo $service_locked ? esc_html( $locked_service_name ) : esc_html__( 'اختر الخدمة المناسبة لك', 'beauty-time-theme' ); ?></span>
                <i class="fas fa-chevron-down"></i>
              </label>
              <?php if ( $service_locked ) : ?>
              <p class="service-locked-message">
                <?php printf( esc_html__( 'تم اختيار "%s" حدد التاريخ والوقت من النافذة التالية "التاريخ والوقت"', 'beauty-time-theme' ), esc_html( $locked_service_name ) ); ?>
              </p>
              <?php endif; ?>
              <div class="list">
                <button type="button" class="item" data-service="makeup" data-name="المكياج" <?php disabled( $service_locked ); ?>><span class="service-name">المكياج</span></button>
                <button type="button" class="item" data-service="nails" data-name="العناية بالأظافر" <?php disabled( $service_locked ); ?>><span class="service-name">العناية بالأظافر</span></button>
                <button type="button" class="item" data-service="skincare" data-name="العناية بالبشرة" <?php disabled( $service_locked ); ?>><span class="service-name">العناية بالبشرة</span></button>
                <button type="button" class="item" data-service="hair" data-name="العناية بالشعر" <?php disabled( $service_locked ); ?>><span class="service-name">العناية بالشعر</span></button>
                <button type="button" class="item" data-service="kids" data-name="العناية بالأطفال" <?php disabled( $service_locked ); ?>><span class="service-name">العناية بالأطفال</span></button>
                <button type="button" class="item" data-service="massage" data-name="المساج" <?php disabled( $service_locked ); ?>><span class="service-name">المساج</span></button>
              </div>
            </div>
          </div>
          <button type="button" class="btn rounded tab-next" data-next="date"><?php esc_html_e( 'استمر', 'beauty-time-theme' ); ?></button>
        </div>

        <div class="date-content tab-content" data-content="date">
          <div class="top">
            <h2><?php esc_html_e( 'التاريخ و الوقت', 'beauty-time-theme' ); ?></h2>
            <div class="data-time">
              <div class="date-time-wrapper">
                <div class="date-picker-section">
                  <div class="calendar-header">
                    <button type="button" class="nav-btn prev-month"><i class="fas fa-chevron-right"></i></button>
                    <div class="current-month-year">
                      <span class="month-name"></span>
                      <span class="year-name"></span>
                    </div>
                    <button type="button" class="nav-btn next-month"><i class="fas fa-chevron-left"></i></button>
                  </div>
                  <div class="calendar-weekdays">
                    <div class="weekday">ح</div><div class="weekday">ن</div><div class="weekday">ث</div><div class="weekday">ر</div><div class="weekday">خ</div><div class="weekday">ج</div><div class="weekday">س</div>
                  </div>
                  <div class="calendar-days"></div>
                </div>
                <div class="time-picker-section">
                  <h3><?php esc_html_e( 'اختر الوقت', 'beauty-time-theme' ); ?></h3>
                  <input type="time" class="time-input" disabled>
                  <p class="time-input-error" data-time-error></p>
                  <div class="time-slots-meta">
                    <p class="working-hours-range" data-working-hours-range></p>
                    <?php if ( ! empty( $working_hours_note ) ) : ?>
                    <p class="working-hours-note"><?php echo esc_html( $working_hours_note ); ?></p>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
              <div class="selected-info">
                <div class="selected-date-info">
                  <i class="fas fa-calendar-alt"></i>
                  <span class="selected-date-text"><?php esc_html_e( 'لم يتم اختيار تاريخ', 'beauty-time-theme' ); ?></span>
                </div>
                <div class="selected-time-info">
                  <i class="fas fa-clock"></i>
                  <span class="selected-time-text"><?php esc_html_e( 'لم يتم اختيار وقت', 'beauty-time-theme' ); ?></span>
                </div>
              </div>
            </div>
          </div>
          <button type="button" class="btn rounded tab-next" data-next="info"><?php esc_html_e( 'استمر', 'beauty-time-theme' ); ?></button>
        </div>

        <div class="info-content tab-content" data-content="info">
          <div class="top">
            <h2><?php esc_html_e( 'معلوماتك', 'beauty-time-theme' ); ?></h2>
            <?php
            $prefill_name  = '';
            $prefill_phone = '';
            $prefill_email = '';
            if ( is_user_logged_in() ) {
            	$current_user = wp_get_current_user();
            	if ( $current_user ) {
            		$prefill_name  = $current_user->first_name ? $current_user->first_name : $current_user->display_name;
            		$prefill_email = $current_user->user_email;
            	}
            	$prefill_phone = get_user_meta( get_current_user_id(), 'billing_phone', true );
            }
            ?>
            <form action="" id="booking-info-form">
              <div class="form-group">
                <div class="group">
                  <label for="booking-name"><?php esc_html_e( 'الاسم الأول', 'beauty-time-theme' ); ?></label>
                  <input type="text" id="booking-name" name="name" value="<?php echo esc_attr( $prefill_name ); ?>" required>
                </div>
                <div class="group">
                  <label for="booking-phone"><?php esc_html_e( 'رقم الهاتف', 'beauty-time-theme' ); ?></label>
                  <input type="tel" id="booking-phone" name="phone" value="<?php echo esc_attr( $prefill_phone ); ?>" required>
                </div>
              </div>
              <?php if ( is_user_logged_in() ) : ?>
              <div class="form-group">
                <div class="group">
                  <label for="booking-email"><?php esc_html_e( 'البريد الإلكتروني', 'beauty-time-theme' ); ?></label>
                  <input type="email" id="booking-email" name="email" value="<?php echo esc_attr( $prefill_email ); ?>" autocomplete="email">
                </div>
              </div>
              <?php else : ?>
              <div class="form-group create-account-toggle">
                <div class="group full-width">
                  <label class="checkbox-label" for="booking-create-account">
                    <input type="checkbox" id="booking-create-account" name="create-account">
                    <span><?php esc_html_e( 'إنشاء حساب جديد أثناء الدفع', 'beauty-time-theme' ); ?></span>
                  </label>
                </div>
              </div>
              <div class="form-group create-account-fields" data-create-account-fields>
                <div class="group">
                  <label for="booking-email"><?php esc_html_e( 'البريد الإلكتروني', 'beauty-time-theme' ); ?></label>
                  <input type="email" id="booking-email" name="email" autocomplete="email">
                </div>
                <div class="group">
                  <label for="booking-password"><?php esc_html_e( 'كلمة المرور', 'beauty-time-theme' ); ?></label>
                  <input type="password" id="booking-password" name="password" autocomplete="new-password">
                </div>
              </div>
              <?php endif; ?>
            </form>
          </div>
          <button type="button" class="btn rounded tab-next" data-next="payment"><?php esc_html_e( 'استمر', 'beauty-time-theme' ); ?></button>
        </div>

        <div class="payment-content tab-content" data-content="payment">
          <div class="top">
            <h2><?php esc_html_e( 'الدفع', 'beauty-time-theme' ); ?></h2>
            <div class="payment-methods">
              <?php foreach ( $payment_gateways as $gateway_id => $gateway ) : ?>
                <?php
                $input_id   = 'payment-' . sanitize_title( $gateway_id );
                $title      = method_exists( $gateway, 'get_title' ) ? $gateway->get_title() : ( isset( $gateway->title ) ? $gateway->title : '' );
                if ( isset( $gateway_titles[ $gateway_id ] ) ) {
                	$title = $gateway_titles[ $gateway_id ];
                }
                $icon_class = isset( $gateway_icons[ $gateway_id ] ) ? $gateway_icons[ $gateway_id ] : 'fas fa-credit-card';
                $image_path = isset( $gateway_images[ $gateway_id ] ) ? $gateway_images[ $gateway_id ] : '';
                $is_checked = ( $payment_default_id === $gateway_id );
                ?>
                <input type="radio" id="<?php echo esc_attr( $input_id ); ?>" name="payment-method" value="<?php echo esc_attr( $gateway_id ); ?>" class="payment-radio" data-form="<?php echo esc_attr( $gateway_id ); ?>" <?php checked( $is_checked ); ?>>
                <label for="<?php echo esc_attr( $input_id ); ?>" class="payment-method-card">
                  <div class="payment-icon"><i class="<?php echo esc_attr( $icon_class ); ?>"></i></div>
                  <div class="payment-info">
                    <h3><?php echo esc_html( $title ); ?></h3>
                    <?php if ( $image_path && file_exists( get_template_directory() . '/' . BEAUTY_TIME_MOCK . '/' . $image_path ) ) : ?>
                    <img src="<?php echo esc_url( beauty_time_asset( $image_path ) ); ?>" alt="">
                    <?php endif; ?>
                  </div>
                  <i class="fas fa-check-circle"></i>
                </label>
              <?php endforeach; ?>
            </div>

            <?php foreach ( $payment_gateways as $gateway_id => $gateway ) : ?>
              <?php
              $title       = method_exists( $gateway, 'get_title' ) ? $gateway->get_title() : ( isset( $gateway->title ) ? $gateway->title : '' );
              if ( isset( $gateway_titles[ $gateway_id ] ) ) {
              	$title = $gateway_titles[ $gateway_id ];
              }
              $description = method_exists( $gateway, 'get_description' ) ? $gateway->get_description() : ( isset( $gateway->description ) ? $gateway->description : '' );
              $description_plain = trim( wp_strip_all_tags( $description ) );
              $description_map = array(
              	'Make your payment directly into our bank account. Please use your Order ID as the payment reference. Your order will not be shipped until the funds have cleared in our account.'
              		=> __( 'قم بالدفع مباشرةً إلى حسابنا البنكي. يرجى استخدام رقم الطلب كمرجع للدفع. لن يتم شحن طلبك حتى يتم تأكيد وصول المبلغ إلى حسابنا.', 'beauty-time-theme' ),
              	'Pay with cash upon delivery.'
              		=> __( 'ادفع نقدًا عند الاستلام.', 'beauty-time-theme' ),
              );
              if ( $description_plain && isset( $description_map[ $description_plain ] ) ) {
              	$description = $description_map[ $description_plain ];
              } elseif ( empty( $description ) ) {
              	$description = ( 'cod' === $gateway_id || 'cash' === $gateway_id )
              		? __( 'ادفع نقدًا عند الاستلام.', 'beauty-time-theme' )
              		: __( 'سيتم توجيهك إلى صفحة الدفع لإتمام العملية', 'beauty-time-theme' );
              }
              ?>
              <div class="payment-form" data-form="<?php echo esc_attr( $gateway_id ); ?>">
                <h3><?php echo esc_html( $title ); ?></h3>
                <form>
                  <div class="stc-info-box">
                    <i class="fas fa-info-circle"></i>
                    <p><?php echo esc_html( $description ); ?></p>
                  </div>
                </form>
              </div>
            <?php endforeach; ?>

            <button type="button" class="btn rounded payment-submit-btn"><?php esc_html_e( 'إتمام الدفع', 'beauty-time-theme' ); ?></button>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
