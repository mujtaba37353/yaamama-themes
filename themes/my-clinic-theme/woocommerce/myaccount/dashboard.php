<?php
/**
 * My Account - Dashboard (Personal Info) Page
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// Get user data
$first_name = get_user_meta($user_id, 'first_name', true) ?: '';
$last_name = get_user_meta($user_id, 'last_name', true) ?: '';
$full_name = trim($first_name . ' ' . $last_name) ?: $current_user->display_name;
$phone = get_user_meta($user_id, 'billing_phone', true) ?: '';
$email = $current_user->user_email;
$gender = get_user_meta($user_id, 'gender', true) ?: '';
$birthdate = get_user_meta($user_id, 'birthdate', true) ?: '';
$national_id = get_user_meta($user_id, 'national_id', true) ?: '';
$address = get_user_meta($user_id, 'billing_address_1', true) ?: '';
$city = get_user_meta($user_id, 'billing_city', true) ?: '';
$full_address = trim($address . ($city ? ', ' . $city : ''));

// Get user avatar
$avatar_url = get_avatar_url($user_id, array('size' => 300)) ?: get_template_directory_uri() . '/assets/images/man.jpg';

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
                        <input type="radio" name="tab" id="doctors-clinic" checked>
                        <input type="radio" name="tab" id="about-clinic">
                        <input type="radio" name="tab" id="rating-clinic">
                        <div class="tabs">
                            <label for="doctors-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>'">البيانات الشخصية</label>
                            <label for="about-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'))); ?>'">الحجوزات</label>
                            <label for="rating-clinic" onclick="window.location.href='<?php echo esc_url(wc_get_endpoint_url('edit-account', '', wc_get_page_permalink('myaccount'))); ?>'">الاعدادات</label>
                        </div>
                        <div class="doctors-box-clinic">
                            <p>الاسم الكامل : <?php echo esc_html($full_name); ?></p>
                            <?php if ($phone): ?>
                                <p>رقم الجوال : <?php echo esc_html($phone); ?></p>
                            <?php endif; ?>
                            <p>البريد الإلكتروني : <?php echo esc_html($email); ?></p>
                            <?php if ($gender): ?>
                                <p>الجنس : <?php echo esc_html($gender === 'male' ? 'ذكر' : ($gender === 'female' ? 'أنثى' : $gender)); ?></p>
                            <?php endif; ?>
                            <?php if ($birthdate): ?>
                                <p>تاريخ الميلاد : <?php echo esc_html($birthdate); ?></p>
                            <?php endif; ?>
                            <?php if ($national_id): ?>
                                <p>رقم الهوية : <?php echo esc_html($national_id); ?></p>
                            <?php endif; ?>
                            <?php if ($full_address): ?>
                                <p>العنوان : <?php echo esc_html($full_address); ?></p>
                            <?php endif; ?>
                            
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
