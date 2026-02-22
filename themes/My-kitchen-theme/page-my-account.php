<?php
get_header();

if (!is_user_logged_in()) {
    wp_safe_redirect(home_url('/my-kitchen/login/'));
    exit;
}

$account_notice = '';
$account_error = '';
$address_notice = '';
$address_error = '';
$current_user = wp_get_current_user();
$full_name = trim($current_user->first_name . ' ' . $current_user->last_name);
if (!$full_name) {
    $full_name = $current_user->display_name ?: $current_user->user_login;
}
$email = $current_user->user_email;
$phone = (string) get_user_meta($current_user->ID, 'billing_phone', true);

if ('POST' === ($_SERVER['REQUEST_METHOD'] ?? '') && isset($_POST['myk_account_update'])) {
    check_admin_referer('mykitchen_account_update');
    $new_full_name = sanitize_text_field(wp_unslash($_POST['full_name'] ?? ''));
    $new_email = sanitize_email(wp_unslash($_POST['email'] ?? ''));
    $new_phone = sanitize_text_field(wp_unslash($_POST['phone'] ?? ''));
    $current_password = (string) wp_unslash($_POST['current_password'] ?? '');
    $new_password = (string) wp_unslash($_POST['new_password'] ?? '');
    $confirm_password = (string) wp_unslash($_POST['confirm_password'] ?? '');

    if (!$new_full_name || !$new_email || !$new_phone) {
        $account_error = 'يرجى تعبئة الاسم والبريد ورقم الجوال.';
    } elseif (!is_email($new_email)) {
        $account_error = 'يرجى إدخال بريد إلكتروني صحيح.';
    } else {
        $existing_email = email_exists($new_email);
        if ($existing_email && (int) $existing_email !== (int) $current_user->ID) {
            $account_error = 'البريد الإلكتروني مستخدم من قبل.';
        }
    }

    if (!$account_error && ($new_password || $confirm_password || $current_password)) {
        if (!$current_password || !wp_check_password($current_password, $current_user->user_pass, $current_user->ID)) {
            $account_error = 'كلمة المرور الحالية غير صحيحة.';
        } elseif (strlen($new_password) < 6) {
            $account_error = 'كلمة المرور الجديدة يجب أن تكون 6 أحرف على الأقل.';
        } elseif ($new_password !== $confirm_password) {
            $account_error = 'تأكيد كلمة المرور غير مطابق.';
        }
    }

    if (!$account_error) {
        $update_data = array(
            'ID' => $current_user->ID,
            'user_email' => $new_email,
            'display_name' => $new_full_name,
            'first_name' => $new_full_name,
            'last_name' => '',
        );
        $updated = wp_update_user($update_data);
        if (is_wp_error($updated)) {
            $account_error = $updated->get_error_message();
        } else {
            update_user_meta($current_user->ID, 'billing_phone', $new_phone);
            if ($new_password) {
                wp_update_user(
                    array(
                        'ID' => $current_user->ID,
                        'user_pass' => $new_password,
                    )
                );
            }
            $account_notice = 'تم حفظ بيانات الحساب بنجاح.';
            $full_name = $new_full_name;
            $email = $new_email;
            $phone = $new_phone;
        }
    }
}

if ('POST' === ($_SERVER['REQUEST_METHOD'] ?? '') && isset($_POST['myk_address_update'])) {
    check_admin_referer('mykitchen_address_update');
    $addr_full_name = sanitize_text_field(wp_unslash($_POST['billing_full_name'] ?? ''));
    $addr_email = sanitize_email(wp_unslash($_POST['billing_email'] ?? ''));
    $addr_phone = sanitize_text_field(wp_unslash($_POST['billing_phone'] ?? ''));
    $addr_1 = sanitize_text_field(wp_unslash($_POST['billing_address_1'] ?? ''));
    $addr_2 = sanitize_text_field(wp_unslash($_POST['billing_address_2'] ?? ''));
    $addr_city = sanitize_text_field(wp_unslash($_POST['billing_city'] ?? ''));
    $addr_state = sanitize_text_field(wp_unslash($_POST['billing_state'] ?? ''));
    $addr_postcode = sanitize_text_field(wp_unslash($_POST['billing_postcode'] ?? ''));

    if (!$addr_full_name || !$addr_email || !$addr_phone || !$addr_1 || !$addr_city) {
        $address_error = 'يرجى تعبئة الحقول المطلوبة.';
    } elseif (!is_email($addr_email)) {
        $address_error = 'يرجى إدخال بريد إلكتروني صحيح.';
    }

    if (!$address_error) {
        update_user_meta($current_user->ID, 'billing_first_name', $addr_full_name);
        update_user_meta($current_user->ID, 'billing_last_name', '');
        update_user_meta($current_user->ID, 'billing_email', $addr_email);
        update_user_meta($current_user->ID, 'billing_phone', $addr_phone);
        update_user_meta($current_user->ID, 'billing_address_1', $addr_1);
        update_user_meta($current_user->ID, 'billing_address_2', $addr_2);
        update_user_meta($current_user->ID, 'billing_city', $addr_city);
        update_user_meta($current_user->ID, 'billing_state', $addr_state);
        update_user_meta($current_user->ID, 'billing_postcode', $addr_postcode);
        update_user_meta($current_user->ID, 'billing_country', 'SA');
        $address_notice = 'تم حفظ بيانات العنوان بنجاح.';
    }
}
?>

<header data-y="design-header"></header>

<main data-y="main">
  <div class="main-container y-u-my-5">
    <div data-y="breadcrumb"></div>
    <div class="account-layout">
      <aside data-y="account-sidebar" class="account-sidebar-shell"></aside>
      <section id="account-content" class="account-content-shell y-u-justify-center"></section>
    </div>
  </div>
</main>

<div class="popup-overlay" id="deactivate-popup" style="display: none;">
  <div class="popup-content">
    <h3 class="popup-title">هل انت متأكد من تعطيل الحساب</h3>
    <p class="popup-description">
      هنالك العديد من الأنواع المتوفرة لنصوص لوريم إيبسوم، ولكن الغالبية تم تعديلها بشكل ما عبر إدخال بعض النوادر أو الكلمات العشوائية إلى النص.
    </p>
    <div class="popup-actions">
      <button class="popup-btn btn-no" id="btn-cancel-deactivate">لا</button>
      <button class="popup-btn btn-confirm" id="btn-confirm-deactivate">تعطيل</button>
    </div>
  </div>
</div>

<div id="account-details-template" style="display: none;">
  <div class="account-details-container">
    <?php if ($account_notice) : ?>
      <div class="account-notice account-notice--success"><?php echo esc_html($account_notice); ?></div>
    <?php endif; ?>
    <?php if ($account_error) : ?>
      <div class="account-notice account-notice--error"><?php echo esc_html($account_error); ?></div>
    <?php endif; ?>
    <form method="post" class="account-form">
      <?php wp_nonce_field('mykitchen_account_update'); ?>
      <div class="form-section">
        <div class="form-row">
          <div class="form-group">
            <label for="account_full_name">الاسم الكامل</label>
            <input type="text" id="account_full_name" name="full_name" value="<?php echo esc_attr($full_name); ?>" required />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="account_email">البريد الإلكتروني</label>
            <input type="email" id="account_email" name="email" value="<?php echo esc_attr($email); ?>" required />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="account_phone">رقم الجوال</label>
            <input type="tel" id="account_phone" name="phone" value="<?php echo esc_attr($phone); ?>" required />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="account_current_password">كلمة المرور الحالية</label>
            <input type="password" id="account_current_password" name="current_password" autocomplete="current-password" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="account_new_password">كلمة المرور الجديدة</label>
            <input type="password" id="account_new_password" name="new_password" autocomplete="new-password" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label for="account_confirm_password">تأكيد كلمة المرور الجديدة</label>
            <input type="password" id="account_confirm_password" name="confirm_password" autocomplete="new-password" />
          </div>
        </div>
      </div>
      <button type="submit" name="myk_account_update" class="account-save-btn">حفظ التغييرات</button>
    </form>
  </div>
</div>

<div id="account-orders-template" style="display: none;">
  <div class="orders-container">
    <div class="orders-list-view">
      <div class="orders-header">
        <div class="header-item">المنتج</div>
        <div class="header-item">التاريخ</div>
        <div class="header-item">الحالة</div>
        <div class="header-item">الإجمالي</div>
        <div class="header-item">إجراءات</div>
      </div>
      <ul class="cart-list">
        <?php
        if (function_exists('wc_get_orders')) {
            $orders = wc_get_orders(
                array(
                    'customer_id' => $current_user->ID,
                    'limit' => 10,
                    'orderby' => 'date',
                    'order' => 'DESC',
                )
            );
        } else {
            $orders = array();
        }
        if (!empty($orders)) :
            foreach ($orders as $order) :
                $items = $order->get_items();
                $first_item = $items ? reset($items) : null;
                $product = $first_item && $first_item->get_product() ? $first_item->get_product() : null;
                $image_url = $product ? wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') : '';
                if (!$image_url) {
                    $image_url = MYK_ASSETS_URI . '/assets/product.png';
                }
                $status = $order->get_status();
                $status_label = wc_get_order_status_name($status);
                $status_class = 'status-processing';
                if (in_array($status, array('completed', 'processing'), true)) {
                    $status_class = $status === 'completed' ? 'status-delivered' : 'status-processing';
                } elseif (in_array($status, array('cancelled', 'failed', 'refunded'), true)) {
                    $status_class = 'status-cancelled';
                } elseif (in_array($status, array('on-hold', 'pending'), true)) {
                    $status_class = 'status-processing';
                }
                $view_url = function_exists('wc_get_endpoint_url')
                    ? wc_get_endpoint_url('view-order', $order->get_id(), wc_get_page_permalink('myaccount'))
                    : $order->get_view_order_url();
                ?>
          <li class="cart-item">
            <div class="product-info">
              <div class="product-img">
                <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($first_item ? $first_item->get_name() : ''); ?>" />
              </div>
              <div class="product-details">
                <h5 class="product-title"><?php echo esc_html($first_item ? $first_item->get_name() : ''); ?></h5>
              </div>
            </div>
            <div class="order-date">
              <span><?php echo esc_html($order->get_date_created() ? $order->get_date_created()->date_i18n('Y/m/d') : ''); ?></span>
            </div>
            <div class="order-status">
              <span class="<?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_label); ?></span>
            </div>
            <div class="product-total">
              <p class="y-u-d-flex y-u-align-items-center">
                <?php echo esc_html($order->get_total()); ?>
                <span><img src="<?php echo esc_url(MYK_ASSETS_URI . '/assets/riyal.png'); ?>" alt="" class="y-u-me-3" /></span>
              </p>
            </div>
            <div class="order-actions">
              <button type="button" class="btn-view" data-order-id="<?php echo esc_attr($order->get_id()); ?>">عرض</button>
            </div>
          </li>
          <li class="order-details-view" data-order-id="<?php echo esc_attr($order->get_id()); ?>" style="display: none;">
            <div class="order-details-header">
              <p class="order-submitted-text">
                تم تقديم الطلب #<?php echo esc_html($order->get_order_number()); ?>
                بتاريخ <?php echo esc_html($order->get_date_created() ? $order->get_date_created()->date_i18n('Y/m/d') : ''); ?>
                وهو الآن بحالة <?php echo esc_html($status_label); ?>
              </p>
            </div>
            <div class="order-details-table">
              <div class="order-details-row order-details-header-row">
                <div class="order-detail-col">المنتج</div>
                <div class="order-detail-col">الإجمالي</div>
              </div>
              <?php foreach ($order->get_items() as $item) : ?>
                <div class="order-details-row">
                  <div class="order-detail-col"><?php echo esc_html($item->get_name()); ?></div>
                  <div class="order-detail-col">
                    <?php echo esc_html($order->get_formatted_line_subtotal($item)); ?>
                  </div>
                </div>
              <?php endforeach; ?>
              <div class="order-details-row">
                <div class="order-detail-col">الإجمالي:</div>
                <div class="order-detail-col"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></div>
              </div>
              <div class="order-details-row">
                <div class="order-detail-col">وسيلة الدفع:</div>
                <div class="order-detail-col"><?php echo esc_html($order->get_payment_method_title()); ?></div>
              </div>
            </div>
            <div class="order-address-section">
              <h3 class="order-address-title">عنوان الفاتورة</h3>
              <div class="order-address-info">
                <p><?php echo esc_html($order->get_formatted_billing_full_name()); ?></p>
                <p><?php echo esc_html($order->get_billing_address_1()); ?></p>
                <?php if ($order->get_billing_address_2()) : ?>
                  <p><?php echo esc_html($order->get_billing_address_2()); ?></p>
                <?php endif; ?>
                <p><?php echo esc_html($order->get_billing_city()); ?></p>
                <?php if ($order->get_billing_postcode()) : ?>
                  <p><?php echo esc_html($order->get_billing_postcode()); ?></p>
                <?php endif; ?>
                <p><?php echo esc_html($order->get_billing_phone()); ?></p>
                <p><?php echo esc_html($order->get_billing_email()); ?></p>
              </div>
            </div>
          </li>
        <?php
            endforeach;
        else :
        ?>
          <li class="cart-item">
            <div class="product-info">
              <div class="product-details">
                <h5 class="product-title">لا توجد طلبات حتى الآن.</h5>
              </div>
            </div>
          </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</div>

<div id="account-address-template" style="display: none;">
  <div class="address-empty-state">
    <h2 class="section-title">عنوان الفاتورة</h2>
    <?php if ($address_notice) : ?>
      <div class="account-notice account-notice--success"><?php echo esc_html($address_notice); ?></div>
    <?php endif; ?>
    <?php if ($address_error) : ?>
      <div class="account-notice account-notice--error"><?php echo esc_html($address_error); ?></div>
    <?php endif; ?>
    <form method="post" class="address-form">
      <?php wp_nonce_field('mykitchen_address_update'); ?>
      <div class="form-group full-width">
        <label for="billing_full_name">الاسم الكامل *</label>
        <input type="text" id="billing_full_name" name="billing_full_name" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'billing_first_name', true)); ?>" required />
      </div>
      <div class="form-group full-width">
        <label for="billing_email">البريد الإلكتروني *</label>
        <input type="email" id="billing_email" name="billing_email" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'billing_email', true) ?: $email); ?>" required />
      </div>
      <div class="form-group full-width">
        <label for="billing_phone">رقم الجوال *</label>
        <input type="tel" id="billing_phone" name="billing_phone" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'billing_phone', true)); ?>" required />
      </div>
      <div class="form-group full-width">
        <label for="billing_address_1">العنوان *</label>
        <input type="text" id="billing_address_1" name="billing_address_1" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'billing_address_1', true)); ?>" required />
      </div>
      <div class="form-group full-width">
        <label for="billing_address_2">رقم الشقة (اختياري)</label>
        <input type="text" id="billing_address_2" name="billing_address_2" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'billing_address_2', true)); ?>" />
      </div>
      <div class="form-row">
        <div class="form-group">
          <label for="billing_city">المدينة *</label>
          <input type="text" id="billing_city" name="billing_city" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'billing_city', true)); ?>" required />
        </div>
        <div class="form-group">
          <label for="billing_state">المحافظة</label>
          <input type="text" id="billing_state" name="billing_state" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'billing_state', true)); ?>" />
        </div>
      </div>
      <div class="form-group full-width">
        <label for="billing_postcode">الرمز البريدي</label>
        <input type="text" id="billing_postcode" name="billing_postcode" value="<?php echo esc_attr(get_user_meta($current_user->ID, 'billing_postcode', true)); ?>" />
      </div>
      <div class="form-actions">
        <button type="submit" name="myk_address_update" class="btn-primary">حفظ</button>
      </div>
    </form>
  </div>
</div>

<?php get_footer(); ?>
