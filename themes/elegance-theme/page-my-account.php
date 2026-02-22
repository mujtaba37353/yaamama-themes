<?php
/**
 * Template Name: حسابي (My Account)
 * Elegance - My Account full design page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_user_logged_in() ) {
	$login_url = add_query_arg( 'redirect_to', rawurlencode( elegance_myaccount_url() ), elegance_page_url( 'login', '/login/' ) );
	wp_safe_redirect( $login_url );
	exit;
}

$active_tab = isset( $_GET['tab'] ) ? sanitize_key( wp_unslash( $_GET['tab'] ) ) : 'profile';
if ( ! in_array( $active_tab, array( 'profile', 'addresses', 'orders' ), true ) ) {
	$active_tab = 'profile';
}

$user_id = get_current_user_id();
$current_user = wp_get_current_user();

$display_name = $current_user->display_name ?: __( 'مستخدم', 'elegance' );
$phone_value  = (string) get_user_meta( $user_id, 'billing_phone', true );

$first_name   = (string) get_user_meta( $user_id, 'billing_first_name', true );
$last_name    = (string) get_user_meta( $user_id, 'billing_last_name', true );
$country      = (string) get_user_meta( $user_id, 'billing_country', true );
$address_1    = (string) get_user_meta( $user_id, 'billing_address_1', true );
$address_2    = (string) get_user_meta( $user_id, 'billing_address_2', true );
$city         = (string) get_user_meta( $user_id, 'billing_city', true );
$state        = (string) get_user_meta( $user_id, 'billing_state', true );
$postcode     = (string) get_user_meta( $user_id, 'billing_postcode', true );
$building_no  = (string) get_user_meta( $user_id, 'elegance_building_number', true );
$unit_no      = (string) get_user_meta( $user_id, 'elegance_unit_number', true );

$address_present = $address_1 !== '' || $city !== '' || $postcode !== '';

if ( isset( $_POST['elegance_account_details_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['elegance_account_details_nonce'] ) ), 'elegance_account_details' ) ) {
	$name       = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email      = isset( $_POST['account_email'] ) ? sanitize_email( wp_unslash( $_POST['account_email'] ) ) : '';
	$phone      = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
	$current_pw = isset( $_POST['password_current'] ) ? (string) $_POST['password_current'] : '';
	$new_pw     = isset( $_POST['password_1'] ) ? (string) $_POST['password_1'] : '';
	$new_pw_2   = isset( $_POST['password_2'] ) ? (string) $_POST['password_2'] : '';

	if ( $email === '' || ! is_email( $email ) ) {
		if ( function_exists( 'wc_add_notice' ) ) {
			wc_add_notice( __( 'الرجاء إدخال بريد إلكتروني صحيح.', 'elegance' ), 'error' );
		}
	} else {
		$existing = email_exists( $email );
		if ( $existing && (int) $existing !== $user_id ) {
			if ( function_exists( 'wc_add_notice' ) ) {
				wc_add_notice( __( 'هذا البريد الإلكتروني مستخدم بحساب آخر.', 'elegance' ), 'error' );
			}
		} else {
			wp_update_user(
				array(
					'ID'           => $user_id,
					'display_name' => $name !== '' ? $name : $display_name,
					'user_email' => $email,
				)
			);
			update_user_meta( $user_id, 'billing_phone', $phone );

			$should_change_password = $current_pw !== '' || $new_pw !== '' || $new_pw_2 !== '';
			if ( $should_change_password ) {
				if ( ! wp_check_password( $current_pw, $current_user->user_pass, $current_user->ID ) ) {
					if ( function_exists( 'wc_add_notice' ) ) {
						wc_add_notice( __( 'كلمة المرور الحالية غير صحيحة.', 'elegance' ), 'error' );
					}
				} elseif ( strlen( $new_pw ) < 6 ) {
					if ( function_exists( 'wc_add_notice' ) ) {
						wc_add_notice( __( 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل.', 'elegance' ), 'error' );
					}
				} elseif ( $new_pw !== $new_pw_2 ) {
					if ( function_exists( 'wc_add_notice' ) ) {
						wc_add_notice( __( 'كلمة المرور الجديدة وتأكيدها غير متطابقين.', 'elegance' ), 'error' );
					}
				} else {
					wp_set_password( $new_pw, $current_user->ID );
					wp_set_auth_cookie( $current_user->ID, true );
					if ( function_exists( 'wc_add_notice' ) ) {
						wc_add_notice( __( 'تم تغيير كلمة المرور بنجاح.', 'elegance' ), 'success' );
					}
				}
			}

			if ( function_exists( 'wc_add_notice' ) ) {
				wc_add_notice( __( 'تم تحديث بيانات الحساب بنجاح.', 'elegance' ), 'success' );
			}
		}
	}
	wp_safe_redirect( add_query_arg( 'tab', 'profile', elegance_myaccount_url() ) );
	exit;
}

if ( isset( $_POST['elegance_billing_address_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['elegance_billing_address_nonce'] ) ), 'elegance_billing_address' ) ) {
	$billing_first_name = isset( $_POST['billing_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_first_name'] ) ) : '';
	$billing_last_name  = isset( $_POST['billing_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_last_name'] ) ) : '';
	$billing_country    = isset( $_POST['billing_country'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_country'] ) ) : 'SA';
	$billing_address_1  = isset( $_POST['billing_address_1'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_address_1'] ) ) : '';
	$billing_address_2  = isset( $_POST['billing_address_2'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_address_2'] ) ) : '';
	$billing_city       = isset( $_POST['billing_city'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_city'] ) ) : '';
	$billing_state      = isset( $_POST['billing_state'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_state'] ) ) : '';
	$billing_postcode   = isset( $_POST['billing_postcode'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_postcode'] ) ) : '';
	$billing_building   = isset( $_POST['billing_building_number'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_building_number'] ) ) : '';
	$billing_unit       = isset( $_POST['billing_unit_number'] ) ? sanitize_text_field( wp_unslash( $_POST['billing_unit_number'] ) ) : '';

	update_user_meta( $user_id, 'billing_first_name', $billing_first_name );
	update_user_meta( $user_id, 'billing_last_name', $billing_last_name );
	update_user_meta( $user_id, 'billing_country', $billing_country !== '' ? $billing_country : 'SA' );
	update_user_meta( $user_id, 'billing_address_1', $billing_address_1 );
	update_user_meta( $user_id, 'billing_address_2', $billing_address_2 );
	update_user_meta( $user_id, 'billing_city', $billing_city );
	update_user_meta( $user_id, 'billing_state', $billing_state );
	update_user_meta( $user_id, 'billing_postcode', $billing_postcode );
	update_user_meta( $user_id, 'elegance_building_number', $billing_building );
	update_user_meta( $user_id, 'elegance_unit_number', $billing_unit );

	if ( function_exists( 'wc_add_notice' ) ) {
		wc_add_notice( __( 'تم حفظ العنوان بنجاح.', 'elegance' ), 'success' );
	}
	wp_safe_redirect( add_query_arg( 'tab', 'addresses', elegance_myaccount_url() ) );
	exit;
}

elegance_enqueue_page_css( 'profile' );
elegance_enqueue_component_css( array( 'empty-state', 'status-popup', 'auth' ) );

$orders_url = function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'orders' ) : elegance_myaccount_url();
$logout_url = elegance_page_url( 'logout', '/logout/' );
$shop_url = function_exists( 'elegance_shop_url' ) ? elegance_shop_url() : home_url( '/shop/' );

$recent_orders = array();
if ( function_exists( 'wc_get_orders' ) ) {
	$recent_orders = wc_get_orders(
		array(
			'customer_id' => $user_id,
			'limit'       => 3,
			'orderby'     => 'date',
			'order'       => 'DESC',
		)
	);
}

if ( $first_name === '' ) {
	$first_name = (string) get_user_meta( $user_id, 'first_name', true );
}
if ( $last_name === '' ) {
	$last_name = (string) get_user_meta( $user_id, 'last_name', true );
}
if ( $country === '' ) {
	$country = 'SA';
}

get_header();
?>
<main>
  <section class="panner y-u-m-b-0">
    <h1 class="y-u-text-center">حسابي</h1>
  </section>
  <section class="profile-section">
    <input type="radio" name="profile-tab" id="tab-profile" <?php checked( $active_tab, 'profile' ); ?>>
    <input type="radio" name="profile-tab" id="tab-addresses" <?php checked( $active_tab, 'addresses' ); ?>>
    <input type="radio" name="profile-tab" id="tab-orders" <?php checked( $active_tab, 'orders' ); ?>>

    <div class="sidbar">
      <div class="top">
        <div class="content">
          <span>اهلا،</span>
          <p><?php echo esc_html( $display_name ); ?></p>
        </div>
      </div>
      <div class="links">
        <label for="tab-profile">البيانات الشخصية</label>
        <label for="tab-addresses">عنواني</label>
        <label for="tab-orders">الطلبات</label>
        <a class="logout-link" href="<?php echo esc_url( $logout_url ); ?>">تسجيل الخروج</a>
      </div>
    </div>

    <div class="content">
      <div class="profile tab-content">
        <?php if ( function_exists( 'woocommerce_output_all_notices' ) ) : ?>
          <?php woocommerce_output_all_notices(); ?>
        <?php endif; ?>

        <form action="<?php echo esc_url( elegance_myaccount_url() ); ?>" method="post">
          <?php wp_nonce_field( 'elegance_account_details', 'elegance_account_details_nonce' ); ?>
          <label for="account-name">الاسم بالكامل</label>
          <input type="text" id="account-name" name="name" value="<?php echo esc_attr( $display_name ); ?>">

          <label for="account-email">البريد الإلكتروني</label>
          <input type="email" id="account-email" name="account_email" value="<?php echo esc_attr( $current_user->user_email ); ?>">

          <label for="account-phone">الهاتف</label>
          <input type="tel" id="account-phone" name="phone" value="<?php echo esc_attr( $phone_value ); ?>">

          <label for="password-current">تغيير كلمة المرور</label>
          <input type="password" id="password-current" name="password_current" autocomplete="current-password">

          <label for="password-1">كلمة المرور الجديدة</label>
          <input type="password" id="password-1" name="password_1" autocomplete="new-password">

          <label for="password-2">تأكيد كلمة المرور الجديدة</label>
          <input type="password" id="password-2" name="password_2" autocomplete="new-password">

          <button type="submit" class="btn main-button">حفظ التعديلات</button>
        </form>
      </div>

      <div class="addresses tab-content">
        <input type="radio" name="address-mode" id="mode-view" <?php checked( $address_present ); ?> hidden>
        <input type="radio" name="address-mode" id="mode-edit" <?php checked( ! $address_present ); ?> hidden>
        <input type="radio" name="address-mode" id="mode-new" hidden>

        <?php if ( $address_present ) : ?>
          <div class="address-view-mode">
            <div class="billing-address-container">
              <div class="billing-header">
                <h3>عنوان الفواتير</h3>
                <label for="mode-edit" class="edit-address-link" style="cursor: pointer;">تعديل</label>
              </div>
              <div class="billing-details">
                <div class="detail-row">
                  <span class="label">الاسم:</span>
                  <span class="value"><?php echo esc_html( trim( $first_name . ' ' . $last_name ) !== '' ? trim( $first_name . ' ' . $last_name ) : $display_name ); ?></span>
                </div>
                <div class="detail-row">
                  <span class="label">الشارع:</span>
                  <span class="value"><?php echo esc_html( $address_1 !== '' ? $address_1 : '-' ); ?></span>
                </div>
                <div class="detail-row">
                  <span class="label">الحي:</span>
                  <span class="value"><?php echo esc_html( $address_2 !== '' ? $address_2 : '-' ); ?></span>
                </div>
                <div class="detail-row">
                  <span class="label">المدينة:</span>
                  <span class="value"><?php echo esc_html( $city !== '' ? $city : '-' ); ?></span>
                </div>
                <div class="detail-row">
                  <span class="label">المنطقة:</span>
                  <span class="value"><?php echo esc_html( $state !== '' ? $state : '-' ); ?></span>
                </div>
                <div class="detail-row">
                  <span class="label">الرمز البريدي:</span>
                  <span class="value"><?php echo esc_html( $postcode !== '' ? $postcode : '-' ); ?></span>
                </div>
                <div class="detail-row">
                  <span class="label">رقم المبنى:</span>
                  <span class="value"><?php echo esc_html( $building_no !== '' ? $building_no : '-' ); ?></span>
                </div>
                <div class="detail-row">
                  <span class="label">رقم الوحدة:</span>
                  <span class="value"><?php echo esc_html( $unit_no !== '' ? $unit_no : '-' ); ?></span>
                </div>
              </div>
              <div class="billing-actions">
                <label for="mode-new" class="btn main-button" style="cursor: pointer;">إضافة عنوان جديد</label>
              </div>
            </div>
          </div>
        <?php else : ?>
          <div class="empty-state-container">
            <div class="empty-state">
              <div class="empty-icon">
                <i class="fas fa-map-marker-alt"></i>
              </div>
              <h2>لا توجد عناوين محفوظة</h2>
              <label for="mode-new" class="btn main-button" style="cursor: pointer;">إضافة عنوان جديد</label>
            </div>
          </div>
        <?php endif; ?>

        <div class="address-edit-mode">
          <div class="billing-address-form">
            <h3>عنوان الفاتورة</h3>
            <form method="post" action="<?php echo esc_url( elegance_myaccount_url() ); ?>">
              <?php wp_nonce_field( 'elegance_billing_address', 'elegance_billing_address_nonce' ); ?>
              <div class="form-row two-cols">
                <div class="form-group">
                  <label>الاسم الأول<span class="required">*</span></label>
                  <input type="text" name="billing_first_name" value="<?php echo esc_attr( $first_name ); ?>">
                </div>
                <div class="form-group">
                  <label>الاسم الأخير<span class="required">*</span></label>
                  <input type="text" name="billing_last_name" value="<?php echo esc_attr( $last_name ); ?>">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>الدولة<span class="required">*</span></label>
                  <div class="static-value"><?php echo esc_html( $country === 'SA' ? 'المملكة العربية السعودية' : $country ); ?></div>
                </div>
              </div>
              <input type="hidden" name="billing_country" value="<?php echo esc_attr( $country ); ?>">
              <div class="form-row">
                <div class="form-group">
                  <label>الشارع<span class="required">*</span></label>
                  <input type="text" name="billing_address_1" value="<?php echo esc_attr( $address_1 ); ?>">
                </div>
              </div>
              <div class="form-row three-cols">
                <div class="form-group">
                  <label>الحي<span class="required">*</span></label>
                  <input type="text" name="billing_address_2" value="<?php echo esc_attr( $address_2 ); ?>">
                </div>
                <div class="form-group">
                  <label>المدينة<span class="required">*</span></label>
                  <input type="text" name="billing_city" value="<?php echo esc_attr( $city ); ?>">
                </div>
                <div class="form-group">
                  <label>المنطقة<span class="required">*</span></label>
                  <input type="text" name="billing_state" value="<?php echo esc_attr( $state ); ?>">
                </div>
              </div>
              <div class="form-row three-cols">
                <div class="form-group">
                  <label>الرمز البريدي<span class="required">*</span></label>
                  <input type="text" name="billing_postcode" value="<?php echo esc_attr( $postcode ); ?>">
                </div>
                <div class="form-group">
                  <label>رقم المبنى<span class="required">*</span></label>
                  <input type="text" name="billing_building_number" value="<?php echo esc_attr( $building_no ); ?>">
                </div>
                <div class="form-group">
                  <label>رقم الوحدة<span class="required">*</span></label>
                  <input type="text" name="billing_unit_number" value="<?php echo esc_attr( $unit_no ); ?>">
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn main-button">حفظ العنوان</button>
              </div>
            </form>
          </div>
        </div>

        <div class="address-new-mode">
          <div class="billing-address-form">
            <h3>عنوان الفاتورة</h3>
            <form method="post" action="<?php echo esc_url( elegance_myaccount_url() ); ?>">
              <?php wp_nonce_field( 'elegance_billing_address', 'elegance_billing_address_nonce' ); ?>
              <div class="form-row two-cols">
                <div class="form-group">
                  <label>الاسم الأول<span class="required">*</span></label>
                  <input type="text" name="billing_first_name" value="">
                </div>
                <div class="form-group">
                  <label>الاسم الأخير<span class="required">*</span></label>
                  <input type="text" name="billing_last_name" value="">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>الدولة<span class="required">*</span></label>
                  <div class="static-value">المملكة العربية السعودية</div>
                </div>
              </div>
              <input type="hidden" name="billing_country" value="SA">
              <div class="form-row">
                <div class="form-group">
                  <label>الشارع<span class="required">*</span></label>
                  <input type="text" name="billing_address_1" value="">
                </div>
              </div>
              <div class="form-row three-cols">
                <div class="form-group">
                  <label>الحي<span class="required">*</span></label>
                  <input type="text" name="billing_address_2" value="">
                </div>
                <div class="form-group">
                  <label>المدينة<span class="required">*</span></label>
                  <input type="text" name="billing_city" value="">
                </div>
                <div class="form-group">
                  <label>المنطقة<span class="required">*</span></label>
                  <input type="text" name="billing_state" value="">
                </div>
              </div>
              <div class="form-row three-cols">
                <div class="form-group">
                  <label>الرمز البريدي<span class="required">*</span></label>
                  <input type="text" name="billing_postcode" value="">
                </div>
                <div class="form-group">
                  <label>رقم المبنى<span class="required">*</span></label>
                  <input type="text" name="billing_building_number" value="">
                </div>
                <div class="form-group">
                  <label>رقم الوحدة<span class="required">*</span></label>
                  <input type="text" name="billing_unit_number" value="">
                </div>
              </div>
              <div class="form-actions">
                <button type="submit" class="btn main-button">حفظ العنوان</button>
              </div>
            </form>
          </div>
        </div>
      </div>

      <div class="orders tab-content" id="ordersList">
        <?php if ( ! empty( $recent_orders ) ) : ?>
          <ul class="orders-list">
            <?php foreach ( $recent_orders as $order ) : ?>
              <?php
              $status_map = array(
                'cancelled'  => 'status-cancelled',
                'completed'  => 'status-delivered',
                'processing' => 'status-pending',
                'pending'    => 'status-pending',
                'on-hold'    => 'status-pending',
              );
              $status_class = isset( $status_map[ $order->get_status() ] ) ? $status_map[ $order->get_status() ] : 'status-pending';
              ?>
              <li class="item">
                <div class="order-card">
                  <div class="order-status <?php echo esc_attr( $status_class ); ?>">
                    <span><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></span>
                  </div>
                  <div class="order-content">
                    <div class="order-main-info">
                      <p class="order-number">ORD - <?php echo esc_html( $order->get_order_number() ); ?></p>
                      <p class="order-date"><?php echo esc_html( wc_format_datetime( $order->get_date_created(), 'd - m - Y' ) ); ?></p>
                      <p class="order-products">عدد المنتجات: <?php echo esc_html( $order->get_item_count() ); ?></p>
                      <p class="order-total">الإجمالي: <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></p>
                    </div>
                    <div class="order-actions">
                      <button type="button" class="view-order-btn">عرض التفاصيل</button>
                      <a class="cancel-order-btn" href="<?php echo esc_url( $order->get_cancel_order_url() ); ?>">إلغاء الطلب</a>
                    </div>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
          <p><a href="<?php echo esc_url( $orders_url ); ?>" class="btn main-button">عرض كل الطلبات</a></p>
        <?php else : ?>
          <div class="empty-state-container">
            <div class="empty-state">
              <div class="empty-icon">
                <i class="fas fa-box-open"></i>
              </div>
              <h2>لا توجد طلبات بعد</h2>
              <a href="<?php echo esc_url( $shop_url ); ?>" class="btn main-button">تسوق الآن</a>
            </div>
          </div>
        <?php endif; ?>
      </div>

      <div class="order-details-page tab-content" id="orderDetailsPage">
        <div class="order-details-content">
          <div class="order-details-header">
            <div class="order-status-badge status-delivered">تفاصيل الطلب</div>
            <div class="order-header-main">
              <a href="#" class="back-to-orders" id="backToOrders">
                <i class="fas fa-arrow-right"></i>
                الرجوع إلى الطلبات
              </a>
              <div class="order-info">
                <p class="order-details-title">طلب رقم: <span id="orderNumber">-</span></p>
                <p class="order-details-date">تاريخ الطلب: <span id="orderDate">-</span></p>
              </div>
            </div>
          </div>
          <h3 class="order-products-title">المنتجات</h3>
          <div class="order-products-table">
            <table>
              <tbody id="orderProductsList">
                <tr class="order-product-row">
                  <td>
                    <div class="order-product-card">
                      <div class="order-product-info">
                        <p class="order-product-name">يمكنك فتح تفاصيل الطلب كاملة من صفحة الطلبات</p>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="order-summary">
            <div class="summary-column">
              <h4>عنوان الشحن</h4>
              <p><?php echo esc_html( $country === 'SA' ? 'المملكة العربية السعودية' : $country ); ?></p>
              <p><?php echo esc_html( $city !== '' ? $city : '-' ); ?></p>
              <p><?php echo esc_html( $state !== '' ? $state : '-' ); ?></p>
              <p><?php echo esc_html( $address_1 !== '' ? $address_1 : '-' ); ?></p>
            </div>
            <div class="summary-column">
              <h4>وسيلة الدفع</h4>
              <p>يمكنك الاطلاع على وسيلة الدفع من صفحة تفاصيل الطلب.</p>
              <div class="invoice-info">
                <span>الطلبات</span>
                <span><a href="<?php echo esc_url( $orders_url ); ?>">عرض كل الطلبات</a></span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</main>
<?php
get_footer();

