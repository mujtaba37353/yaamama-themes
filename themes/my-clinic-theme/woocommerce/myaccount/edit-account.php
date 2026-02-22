<?php
/**
 * My Account - Edit Account (Settings) Page
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Get user data for header
$first_name = get_user_meta($user_id, 'first_name', true) ?: '';
$last_name = get_user_meta($user_id, 'last_name', true) ?: '';
$full_name = trim($first_name . ' ' . $last_name) ?: $current_user->display_name;
$avatar_url = get_avatar_url($user_id, array('size' => 300)) ?: get_template_directory_uri() . '/assets/images/man.jpg';

// Handle form submission
$update_message = '';
$update_error = '';

if (isset($_POST['update_account']) && wp_verify_nonce($_POST['update_account_nonce'], 'update_account')) {
    $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
    $last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';
    $phone = isset($_POST['billing_phone']) ? sanitize_text_field($_POST['billing_phone']) : '';
    $gender = isset($_POST['gender']) ? sanitize_text_field($_POST['gender']) : '';
    $birthdate = isset($_POST['birthdate']) ? sanitize_text_field($_POST['birthdate']) : '';
    $national_id = isset($_POST['national_id']) ? sanitize_text_field($_POST['national_id']) : '';
    $address = isset($_POST['billing_address_1']) ? sanitize_text_field($_POST['billing_address_1']) : '';
    $city = isset($_POST['billing_city']) ? sanitize_text_field($_POST['billing_city']) : '';
    
    update_user_meta($user_id, 'first_name', $first_name);
    update_user_meta($user_id, 'last_name', $last_name);
    update_user_meta($user_id, 'billing_phone', $phone);
    update_user_meta($user_id, 'gender', $gender);
    update_user_meta($user_id, 'birthdate', $birthdate);
    update_user_meta($user_id, 'national_id', $national_id);
    update_user_meta($user_id, 'billing_address_1', $address);
    update_user_meta($user_id, 'billing_city', $city);
    
    $update_message = 'تم تحديث البيانات بنجاح';
}

// Handle password change
if (isset($_POST['change_password']) && wp_verify_nonce($_POST['change_password_nonce'], 'change_password')) {
    $current_pass = isset($_POST['current_password']) ? $_POST['current_password'] : '';
    $new_pass = isset($_POST['new_password']) ? $_POST['new_password'] : '';
    $confirm_pass = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    
    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $update_error = 'يرجى ملء جميع الحقول';
    } elseif ($new_pass !== $confirm_pass) {
        $update_error = 'كلمات المرور غير متطابقة';
    } elseif (strlen($new_pass) < 6) {
        $update_error = 'كلمة المرور يجب أن تكون 6 أحرف على الأقل';
    } else {
        $user = wp_authenticate($current_user->user_email, $current_pass);
        if (is_wp_error($user)) {
            $update_error = 'كلمة المرور الحالية غير صحيحة';
        } else {
            wp_set_password($new_pass, $user_id);
            $update_message = 'تم تغيير كلمة المرور بنجاح';
        }
    }
}

// Get current user data
$first_name = get_user_meta($user_id, 'first_name', true) ?: '';
$last_name = get_user_meta($user_id, 'last_name', true) ?: '';
$phone = get_user_meta($user_id, 'billing_phone', true) ?: '';
$gender = get_user_meta($user_id, 'gender', true) ?: '';
$birthdate = get_user_meta($user_id, 'birthdate', true) ?: '';
$national_id = get_user_meta($user_id, 'national_id', true) ?: '';
$address = get_user_meta($user_id, 'billing_address_1', true) ?: '';
$city = get_user_meta($user_id, 'billing_city', true) ?: '';

get_header();
?>

<main>
    <section class="doctor-page clinic-page account-page">
        <div class="container y-u-max-w-1200">
            <div class="bottom">
                <div class="content">
                    <div class="right">
                        <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($full_name); ?>">
                    </div>
                    <div class="left">
                        <input type="radio" name="tab" id="doctors-clinic">
                        <input type="radio" name="tab" id="about-clinic">
                        <input type="radio" name="tab" id="rating-clinic" checked>
                        <div class="tabs">
                            <label for="doctors-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>'">البيانات الشخصية</label>
                            <label for="about-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'))); ?>'">الحجوزات</label>
                            <label for="rating-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_endpoint_url('edit-account', '', wc_get_page_permalink('myaccount'))); ?>'">الاعدادات</label>
                        </div>

<div class="rating-box-clinic">
    <?php if ($update_message): ?>
        <div class="notice notice-success" style="color: green; margin-bottom: var(--y-space-16); padding: var(--y-space-12); background: #e8f5e9; border-radius: var(--y-radius-m);">
            <?php echo esc_html($update_message); ?>
        </div>
    <?php endif; ?>
    
    <?php if ($update_error): ?>
        <div class="notice notice-error" style="color: red; margin-bottom: var(--y-space-16); padding: var(--y-space-12); background: #ffebee; border-radius: var(--y-radius-m);">
            <?php echo esc_html($update_error); ?>
        </div>
    <?php endif; ?>
    
    <h3 style="margin-bottom: var(--y-space-24);">تعديل البيانات الشخصية</h3>
    <form method="POST" action="" style="margin-bottom: var(--y-space-32);">
        <?php wp_nonce_field('update_account', 'update_account_nonce'); ?>
        <div style="display: flex; flex-direction: column; gap: var(--y-space-16);">
            <div>
                <label for="first_name" style="display: block; margin-bottom: var(--y-space-8);">الاسم الأول</label>
                <input type="text" name="first_name" id="first_name" value="<?php echo esc_attr($first_name); ?>" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
            </div>
            <div>
                <label for="last_name" style="display: block; margin-bottom: var(--y-space-8);">اسم العائلة</label>
                <input type="text" name="last_name" id="last_name" value="<?php echo esc_attr($last_name); ?>" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
            </div>
            <div>
                <label for="billing_phone" style="display: block; margin-bottom: var(--y-space-8);">رقم الجوال</label>
                <input type="tel" name="billing_phone" id="billing_phone" value="<?php echo esc_attr($phone); ?>" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
            </div>
            <div>
                <label for="gender" style="display: block; margin-bottom: var(--y-space-8);">الجنس</label>
                <select name="gender" id="gender" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
                    <option value="">اختر</option>
                    <option value="male" <?php selected($gender, 'male'); ?>>ذكر</option>
                    <option value="female" <?php selected($gender, 'female'); ?>>أنثى</option>
                </select>
            </div>
            <div>
                <label for="birthdate" style="display: block; margin-bottom: var(--y-space-8);">تاريخ الميلاد</label>
                <input type="date" name="birthdate" id="birthdate" value="<?php echo esc_attr($birthdate); ?>" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
            </div>
            <div>
                <label for="national_id" style="display: block; margin-bottom: var(--y-space-8);">رقم الهوية</label>
                <input type="text" name="national_id" id="national_id" value="<?php echo esc_attr($national_id); ?>" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
            </div>
            <div>
                <label for="billing_address_1" style="display: block; margin-bottom: var(--y-space-8);">العنوان</label>
                <input type="text" name="billing_address_1" id="billing_address_1" value="<?php echo esc_attr($address); ?>" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
            </div>
            <div>
                <label for="billing_city" style="display: block; margin-bottom: var(--y-space-8);">المدينة</label>
                <input type="text" name="billing_city" id="billing_city" value="<?php echo esc_attr($city); ?>" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
            </div>
            <button type="submit" name="update_account" class="btn main-button fw" style="margin-top: var(--y-space-16);">حفظ التغييرات</button>
        </div>
    </form>
    
    <h3 style="margin-bottom: var(--y-space-24); margin-top: var(--y-space-32);">تغيير كلمة المرور</h3>
    <form method="POST" action="" style="margin-bottom: var(--y-space-32);">
        <?php wp_nonce_field('change_password', 'change_password_nonce'); ?>
        <div style="display: flex; flex-direction: column; gap: var(--y-space-16);">
            <div>
                <label for="current_password" style="display: block; margin-bottom: var(--y-space-8);">كلمة المرور الحالية</label>
                <input type="password" name="current_password" id="current_password" required style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
            </div>
            <div>
                <label for="new_password" style="display: block; margin-bottom: var(--y-space-8);">كلمة المرور الجديدة</label>
                <input type="password" name="new_password" id="new_password" required minlength="6" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
            </div>
            <div>
                <label for="confirm_password" style="display: block; margin-bottom: var(--y-space-8);">تأكيد كلمة المرور</label>
                <input type="password" name="confirm_password" id="confirm_password" required minlength="6" style="width: 100%; padding: var(--y-space-12); border: 1px solid var(--y-color-primary); border-radius: var(--y-radius-m);">
            </div>
            <button type="submit" name="change_password" class="btn main-button fw" style="margin-top: var(--y-space-16);">تغيير كلمة المرور</button>
        </div>
    </form>
    
    <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=delete_account'), 'delete_account_' . $user_id)); ?>" 
       onclick="return confirm('هل أنت متأكد من حذف الحساب؟ لا يمكن التراجع عن هذا الإجراء.');" 
       style="color: var(--y-color-danger); text-decoration: underline; display: block; margin-top: var(--y-space-32);">
        حذف الحساب
    </a>
    
    <div style="margin-top: var(--y-space-32); padding-top: var(--y-space-24); border-top: 1px solid var(--y-color-border);">
        <a href="<?php echo esc_url(wp_logout_url(wc_get_page_permalink('myaccount'))); ?>" class="btn" style="background: #dc3232; color: white; border-color: #dc3232; display: inline-block; text-decoration: none; padding: var(--y-space-12) var(--y-space-24); border-radius: var(--y-radius-m);">تسجيل الخروج</a>
    </div>
</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
