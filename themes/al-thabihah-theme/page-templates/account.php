<?php
/*
Template Name: Account
*/
if (!is_user_logged_in()) {
    wp_safe_redirect(al_thabihah_get_page_link('login'));
    exit;
}

get_header();

$user = wp_get_current_user();
$user_id = get_current_user_id();
$full_name = trim($user->first_name . ' ' . $user->last_name);
$display_name = $full_name ? $full_name : $user->display_name;
$phone = get_user_meta($user_id, 'billing_phone', true);
$email = $user->user_email;
$address = get_user_meta($user_id, 'billing_address_1', true);
$billing_first = get_user_meta($user_id, 'billing_first_name', true);
$billing_last = get_user_meta($user_id, 'billing_last_name', true);
$billing_country = get_user_meta($user_id, 'billing_country', true);
$billing_city = get_user_meta($user_id, 'billing_city', true);
$billing_state = get_user_meta($user_id, 'billing_state', true);
$billing_postcode = get_user_meta($user_id, 'billing_postcode', true);
$billing_address_2 = get_user_meta($user_id, 'billing_address_2', true);
?>

<main class="y-l-account-page">
    <div class="y-l-account-container" data-y="account-container">

        <aside class="y-l-account-sidebar" data-y="account-sidebar">

            <nav class="y-c-breadcrumbs" aria-label="breadcrumb" data-y="breadcrumbs">
                <p>
                    <a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a>
                    <span>></span>
                    <span data-y="bc-current">حسابي</span>
                </p>
            </nav>

            <div class="y-c-profile-card" data-y="profile-card">
                <div class="y-c-profile-text" data-y="profile-name-container">
                    <p class="y-c-profile-greeting" data-y="profile-greeting">أهلاً،</p>
                    <p data-y="profile-name-text"><?php echo esc_html($display_name); ?></p>
                </div>
            </div>

            <nav class="y-c-account-nav" data-y="account-nav">
                <a href="#" id="account-details-link" class="y-c-nav-tab y-c-active" data-y="nav-tab-details">
                    البيانات الشخصية
                </a>
                <a href="#" id="address-link" class="y-c-nav-tab" data-y="nav-tab-address">
                    عنوانى
                </a>
                <a href="#" id="favorites-link" class="y-c-nav-tab" data-y="nav-tab-favorites">
                    المفضلة
                </a>
                <a href="#" id="orders-link" class="y-c-nav-tab" data-y="nav-tab-orders">
                    الطلبات
                </a>
                <a href="<?php echo esc_url(wc_logout_url()); ?>" class="y-c-nav-tab y-c-nav-tab--logout" data-y="nav-tab-logout">
                    تسجيل الخروج
                    <i class="fas fa-sign-out-alt"></i>
                </a>
            </nav>
        </aside>

        <section class="y-l-account-content" data-y="account-content-area">
            <?php wc_print_notices(); ?>
            <div id="account-details-content" class="y-c-content-section active">
                <form class="y-c-profile-form" data-y="profile-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <input type="hidden" name="action" value="al_thabihah_profile_update">
                    <?php wp_nonce_field('al_thabihah_profile', 'al_thabihah_profile_nonce'); ?>

                    <div class="y-c-form-field" data-y="form-field-name">
                        <label for="firstName" class="y-c-form-label">الاسم بالكامل</label>
                        <input type="text" id="firstName" name="firstName" class="y-c-form-input" value="<?php echo esc_attr($display_name); ?>" data-y="full-name">
                    </div>
                    <div class="y-c-form-field" data-y="form-field-phone">
                        <label for="phone" class="y-c-form-label">رقم الجوال</label>
                        <input type="tel" id="phone" name="phone" class="y-c-form-input" value="<?php echo esc_attr($phone); ?>" data-y="phone-input">
                    </div>

                    <div class="y-c-form-field" data-y="form-field-email">
                        <label for="email" class="y-c-form-label">البريد الإلكتروني</label>
                        <input type="email" id="email" name="email" class="y-c-form-input" value="<?php echo esc_attr($email); ?>" data-y="email-input">
                    </div>
                    <div class="y-c-form-field" data-y="form-field-address">
                        <label for="address" class="y-c-form-label">العنوان</label>
                        <input type="text" id="address" name="address" class="y-c-form-input" value="<?php echo esc_attr($address); ?>" data-y="address-input">
                    </div>

                    <input type="hidden" id="birthDate" name="birthDate" data-y="birth-date-input">
                    <input type="hidden" id="gender" name="gender" data-y="gender-select">

                    <h2 class="y-c-form-title-sub" data-y="password-section-title">تغيير كلمة المرور</h2>

                    <div class="y-c-form-group y-l-password-wrapper" data-y="form-group-current-pass">
                        <label for="current-password" class="y-c-form-label">كلمة المرور الحاليه</label>
                        <input type="password" id="current-password" name="currentPassword" class="y-c-form-input" data-y="login-password-input">
                        <i class="fas fa-eye y-c-password-toggle"></i>
                    </div>

                    <div class="y-c-form-field y-l-password-wrapper" data-y="form-field-new-pass">
                        <label for="new-password" class="y-c-form-label">كلمة المرور الجديده</label>
                        <input type="password" id="new-password" name="newPassword" class="y-c-form-input" data-y="password-input">
                        <i class="fas fa-eye y-c-password-toggle"></i>
                    </div>

                    <div class="y-c-form-field y-l-password-wrapper" data-y="form-field-confirm-pass">
                        <label for="confirm-password" class="y-c-form-label">تأكيد كلمة المرور الجديده</label>
                        <input type="password" id="confirm-password" name="confirmPassword" class="y-c-form-input" data-y="confirm-password-input">
                        <i class="fas fa-eye y-c-password-toggle"></i>
                    </div>

                    <button type="submit" class="y-c-outline-btn y-c-basic-btn" data-y="profile-submit-btn">حفظ التعديلات</button>
                </form>
            </div>

            <div id="address-content" class="y-c-content-section">
                <div class="y-c-address-container">

                    <div id="address-display-view" class="y-c-address-display-view">
                        <div class="y-l-address-header">
                            <h2 class="y-c-address-title" data-y="address-section-title">عنوان الفواتير</h2>
                            <a href="#" id="edit-address-btn" class="y-c-edit-link">
                                تعديل <i class="fas fa-pen"></i>
                            </a>
                        </div>

                        <div class="y-c-address-list">
                            <div><strong>الاسم:</strong> <span data-field="fullName"><?php echo esc_html(trim($billing_first . ' ' . $billing_last)); ?></span></div>
                            <div><strong>الدولة:</strong> <span data-field="country"><?php echo esc_html($billing_country ?: 'المملكة العربية السعودية'); ?></span></div>
                            <div><strong>الشارع:</strong> <span data-field="street"><?php echo esc_html($address); ?></span></div>
                            <div><strong>الحي:</strong> <span data-field="district"><?php echo esc_html($billing_address_2); ?></span></div>
                            <div><strong>المدينة:</strong> <span data-field="city"><?php echo esc_html($billing_city); ?></span></div>
                            <div><strong>المنطقة:</strong> <span data-field="region"><?php echo esc_html($billing_state); ?></span></div>
                            <div><strong>الرمز البريدي:</strong> <span data-field="postalCode"><?php echo esc_html($billing_postcode); ?></span></div>
                            <div><strong>رقم المبنى:</strong> <span data-field="buildingNo">-</span></div>
                            <div><strong>رقم الوحدة:</strong> <span data-field="unitNo">-</span></div>
                        </div>

                        <button type="button" id="add-address-btn" class="y-c-outline-btn y-c-basic-btn">إضافة عنوان جديد</button>
                    </div>

                    <form id="address-edit-form" class="y-c-address-form y-c-address-edit-form" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                        <input type="hidden" name="action" value="al_thabihah_address_update">
                        <?php wp_nonce_field('al_thabihah_address', 'al_thabihah_address_nonce'); ?>

                        <div class="y-c-form-row-split">
                            <div class="y-c-form-group">
                                <label>الاسم الأول <span class="y-u-text-error">*</span></label>
                                <input type="text" name="firstName" value="<?php echo esc_attr($billing_first); ?>" class="y-c-form-input" required data-y="full-name">
                            </div>
                            <div class="y-c-form-group">
                                <label>الاسم الأخير <span class="y-u-text-error">*</span></label>
                                <input type="text" name="lastName" value="<?php echo esc_attr($billing_last); ?>" class="y-c-form-input" required data-y="full-name">
                            </div>
                        </div>

                        <div class="y-c-form-group">
                            <label>الدولة</label>
                            <input type="text" name="country" value="المملكة العربية السعودية" class="y-c-form-input y-c-form-input-readonly" readonly>
                        </div>

                        <div class="y-c-form-group">
                            <label>الشارع <span class="y-u-text-error">*</span></label>
                            <input type="text" name="street" value="<?php echo esc_attr($address); ?>" class="y-c-form-input" required data-y="address-input">
                        </div>

                        <div class="y-c-form-row-split">
                            <div class="y-c-form-group">
                                <label>المدينة <span class="y-u-text-error">*</span></label>
                                <input type="text" name="city" value="<?php echo esc_attr($billing_city); ?>" class="y-c-form-input" required data-y="address-input">
                            </div>
                            <div class="y-c-form-group">
                                <label>الحي <span class="y-u-text-error">*</span></label>
                                <input type="text" name="district" value="<?php echo esc_attr($billing_address_2); ?>" class="y-c-form-input" required data-y="address-input">
                            </div>
                            <div class="y-c-form-group">
                                <label>المنطقة <span class="y-u-text-error">*</span></label>
                                <input type="text" name="region" value="<?php echo esc_attr($billing_state); ?>" class="y-c-form-input" required data-y="address-input">
                            </div>
                        </div>

                        <div class="y-c-form-row-split">
                            <div class="y-c-form-group">
                                <label>الرمز البريدي <span class="y-u-text-error">*</span></label>
                                <input type="text" name="postalCode" value="<?php echo esc_attr($billing_postcode); ?>" class="y-c-form-input" required pattern="[0-9]{5}" data-y="postal-code">
                            </div>
                            <div class="y-c-form-group">
                                <label>رقم المبنى <span class="y-u-text-error">*</span></label>
                                <input type="text" name="buildingNo" value="" class="y-c-form-input" required data-y="address-number">
                            </div>
                            <div class="y-c-form-group">
                                <label>رقم الوحدة <span class="y-u-text-error">*</span></label>
                                <input type="text" name="unitNo" value="" class="y-c-form-input" required data-y="address-number">
                            </div>
                        </div>

                        <div class="y-c-form-actions">
                            <button type="submit" class="y-c-btn-maroon">حفظ العنوان</button>
                            <button type="button" id="cancel-edit-btn" class="y-c-btn-maroon">إلغاء</button>
                        </div>
                    </form>
                </div>
            </div>

            <div id="favorites-content" class="y-c-content-section">
                <div class="y-c-favorites-container">
                    <div id="favorites-empty" class="y-c-empty-state" style="display: none;">
                        <i class="far fa-heart"></i>
                        <h3>لا توجد منتجات في المفضلة</h3>
                        <p>قم بإضافة منتجات إلى المفضلة لتظهر هنا</p>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-basic-btn">
                            تصفح المنتجات
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>

                    <ul class="y-l-favorites-grid" id="favorites-grid" data-y="favorites-grid"></ul>
                </div>
            </div>

            <div id="orders-content" class="y-c-content-section">
                <div class="y-c-orders-wrapper">
                    <div id="y-v-orders-list">
                        <div class="y-c-orders-list">
                            <?php
                            $orders = wc_get_orders(array(
                                'customer_id' => $user_id,
                                'limit' => 5,
                            ));
                            if ($orders) :
                                foreach ($orders as $order) :
                                    [$status_label, $status_class] = al_thabihah_get_order_status_badge($order);
                                    ?>
                                    <div class="y-c-order-card">
                                        <div class="y-c-order-header">
                                            <div class="y-c-order-meta">
                                                <h3 class="y-c-order-id">ORD - <?php echo esc_html($order->get_order_number()); ?></h3>
                                                <span class="y-c-order-date"><?php echo esc_html($order->get_date_created()->date('d - m - Y')); ?></span>
                                            </div>
                                            <span class="y-c-status-badge <?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_label); ?></span>
                                        </div>
                                        <div class="y-c-order-body">
                                            <div class="y-c-order-summary">
                                                <p class="y-c-order-count">عدد المنتجات: <span><?php echo esc_html($order->get_item_count()); ?></span></p>
                                                <p class="y-c-order-total">الإجمالي: <span><?php echo esc_html(number_format_i18n($order->get_total(), 0)); ?>
                                                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-currency-coin" alt="coin"></span></p>
                                            </div>
                                            <button class="y-c-btn-details" data-order-id="<?php echo esc_attr($order->get_id()); ?>">عرض التفاصيل</button>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <p>لا توجد طلبات حالياً.</p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div id="y-v-order-details" class="y-u-hidden">
                        <button class="y-c-btn-back-orders" id="y-btn-back-to-orders">
                            <i class="fas fa-chevron-right"></i> الرجوع إلى الطلبات
                        </button>

                        <?php if ($orders) :
                            foreach ($orders as $order) :
                                [$status_label, $status_class] = al_thabihah_get_order_status_badge($order);
                                ?>
                                <div class="y-c-order-details" data-order-id="<?php echo esc_attr($order->get_id()); ?>" style="display:none;">
                                    <div class="y-c-details-top-bar">
                                        <div class="y-c-details-title-group">
                                            <h2 class="y-c-details-title">طلب رقم: ORD-<?php echo esc_html($order->get_order_number()); ?></h2>
                                            <span class="y-c-details-date">تاريخ الطلب: <?php echo esc_html($order->get_date_created()->date('d - m - Y')); ?></span>
                                        </div>
                                        <span class="y-c-status-badge <?php echo esc_attr($status_class); ?>"><?php echo esc_html($status_label); ?></span>
                                    </div>

                                    <h3 class="y-c-section-header">المنتجات</h3>

                                    <?php foreach ($order->get_items() as $item) :
                                        $product = $item->get_product();
                                        $product_image = $product ? wp_get_attachment_image_url($product->get_image_id(), 'thumbnail') : al_thabihah_asset_uri('al-thabihah/assets/product.jpg');
                                        ?>
                                        <div class="y-c-details-product-card">
                                            <div class="y-l-d-product-img">
                                                <img src="<?php echo esc_url($product_image); ?>" class="y-c-d-product-img" alt="<?php echo esc_attr($item->get_name()); ?>">
                                                <div class="y-c-d-product-total">
                                                    الإجمالي: <?php echo esc_html(number_format_i18n($item->get_total(), 0)); ?>
                                                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-currency-coin">
                                                </div>
                                            </div>
                                            <div class="y-c-d-product-info">
                                                <div class="y-c-d-product-header">
                                                    <h4><?php echo esc_html($item->get_name()); ?></h4>
                                                </div>
                                                <div class="y-c-d-product-tags">
                                                    <span class="y-c-tag">تقطيع ثلاجة</span>
                                                    <span class="y-c-tag">أكياس فاكيوم</span>
                                                </div>
                                                <div class="y-c-d-product-price-row">
                                                    <span class="y-c-d-price">السعر: <?php echo esc_html(number_format_i18n($product ? $product->get_price() : 0, 0)); ?>
                                                        <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-currency-coin-sm"></span>
                                                </div>
                                                <div class="y-c-d-product-qty">
                                                    <span>الكمية: <?php echo esc_html($item->get_quantity()); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>

                                    <hr class="y-c-divider">

                                    <div class="y-c-details-bottom-grid">
                                        <div class="y-c-details-totals">
                                            <div class="y-c-total-row">
                                                <span>المجموع</span>
                                                <span><?php echo esc_html(number_format_i18n($order->get_subtotal(), 0)); ?> <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-currency-coin-sm"></span>
                                            </div>
                                            <div class="y-c-total-row">
                                                <span>ضريبة القيمة المضافة</span>
                                                <span><?php echo esc_html(number_format_i18n($order->get_total_tax(), 0)); ?> <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-currency-coin-sm"></span>
                                            </div>
                                            <div class="y-c-total-row">
                                                <span>رسوم التوصيل</span>
                                                <span><?php echo esc_html(number_format_i18n($order->get_shipping_total(), 0)); ?> <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-currency-coin-sm"></span>
                                            </div>
                                            <div class="y-c-total-row y-c-total-final">
                                                <div class="y-c-total-label-group">
                                                    <span>إجمالي السعر</span>
                                                    <small>(شامل ضريبة القيمة المضافة 15%)</small>
                                                </div>
                                                <span class="y-c-final-price"><?php echo esc_html(number_format_i18n($order->get_total(), 0)); ?> <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-currency-coin"></span>
                                            </div>
                                        </div>

                                        <div class="y-c-details-info">
                                            <div class="y-c-info-block">
                                                <h4>عنوان الشحن</h4>
                                                <p><?php echo esc_html($order->get_shipping_country()); ?></p>
                                                <p><?php echo esc_html($order->get_shipping_city()); ?></p>
                                                <p><?php echo esc_html($order->get_shipping_state()); ?></p>
                                                <p><?php echo esc_html($order->get_shipping_address_1()); ?></p>
                                            </div>

                                            <div class="y-c-info-block">
                                                <h4>وسيلة الدفع</h4>
                                                <p><?php echo esc_html($order->get_payment_method_title()); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach;
                        endif; ?>
                    </div>

                </div>
            </div>

        </section>

    </div>
</main>

<?php
get_footer();
