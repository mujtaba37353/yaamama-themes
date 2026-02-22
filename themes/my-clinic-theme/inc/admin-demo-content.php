<?php
/**
 * Admin Page for Managing Demo Content
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Admin Menu for Demo Content
 */
function my_clinic_add_demo_content_admin_menu() {
    add_submenu_page(
        'doctors',
        __('المحتوى الديمو', 'my-clinic'),
        __('المحتوى الديمو', 'my-clinic'),
        'manage_options',
        'demo-content',
        'my_clinic_render_demo_content_admin_page',
        0
    );
}
add_action('admin_menu', 'my_clinic_add_demo_content_admin_menu', 11);

/**
 * Render Demo Content Admin Page
 */
function my_clinic_render_demo_content_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('ليس لديك صلاحية للوصول إلى هذه الصفحة.', 'my-clinic'));
    }

    // Handle form submissions
    $message = '';
    $message_type = '';

    if (isset($_POST['add_demo_content']) && wp_verify_nonce($_POST['demo_content_nonce'], 'demo_content_action')) {
        $result = my_clinic_add_demo_content();
        if ($result['success']) {
            $message = $result['message'];
            $message_type = 'success';
        } else {
            $message = $result['message'];
            $message_type = 'error';
        }
    }

    if (isset($_POST['delete_demo_content']) && wp_verify_nonce($_POST['demo_content_nonce'], 'demo_content_action')) {
        $result = my_clinic_delete_demo_content();
        if ($result['success']) {
            $message = $result['message'];
            $message_type = 'success';
        } else {
            $message = $result['message'];
            $message_type = 'error';
        }
    }

    if (isset($_POST['restore_demo_content']) && wp_verify_nonce($_POST['demo_content_nonce'], 'demo_content_action')) {
        $result = my_clinic_restore_demo_content();
        if ($result['success']) {
            $message = $result['message'];
            $message_type = 'success';
        } else {
            $message = $result['message'];
            $message_type = 'error';
        }
    }

    // Check if demo content exists
    $demo_clinics = get_posts(array(
        'post_type' => 'clinic',
        'meta_key' => '_is_demo_content',
        'meta_value' => '1',
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));

    $demo_doctors = get_posts(array(
        'post_type' => 'doctor',
        'meta_key' => '_is_demo_content',
        'meta_value' => '1',
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));

    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('المحتوى الديمو', 'my-clinic'); ?></h1>
        
        <?php if ($message): ?>
            <div class="notice notice-<?php echo esc_attr($message_type); ?> is-dismissible">
                <p><?php echo wp_kses_post($message); ?></p>
            </div>
        <?php endif; ?>

        <div class="card" style="max-width: 800px; margin-top: 20px;">
            <h2><?php echo esc_html__('إدارة المحتوى الديمو', 'my-clinic'); ?></h2>
            <p><?php echo esc_html__('يمكنك إضافة محتوى ديمو للعيادات والأطباء لاختبار الموقع.', 'my-clinic'); ?></p>
            
            <div style="margin: 20px 0;">
                <h3><?php echo esc_html__('الحالة الحالية:', 'my-clinic'); ?></h3>
                <ul>
                    <li><?php echo esc_html__('عدد العيادات الديمو:', 'my-clinic'); ?> <strong><?php echo count($demo_clinics); ?></strong></li>
                    <li><?php echo esc_html__('عدد الأطباء الديمو:', 'my-clinic'); ?> <strong><?php echo count($demo_doctors); ?></strong></li>
                </ul>
            </div>

            <form method="post" action="" style="margin-top: 20px;">
                <?php wp_nonce_field('demo_content_action', 'demo_content_nonce'); ?>
                
                <p>
                    <input type="submit" name="add_demo_content" class="button button-primary" value="<?php echo esc_attr__('إضافة المحتوى الديمو', 'my-clinic'); ?>" 
                           onclick="return confirm('<?php echo esc_js(__('هل أنت متأكد من إضافة المحتوى الديمو؟ سيتم إضافة 3 عيادات و 6 أطباء.', 'my-clinic')); ?>');">
                </p>
            </form>

            <?php if (count($demo_clinics) > 0 || count($demo_doctors) > 0): ?>
                <form method="post" action="" style="margin-top: 20px;">
                    <?php wp_nonce_field('demo_content_action', 'demo_content_nonce'); ?>
                    
                    <p>
                        <input type="submit" name="delete_demo_content" class="button button-secondary" value="<?php echo esc_attr__('مسح المحتوى الديمو', 'my-clinic'); ?>" 
                               style="background: #dc3232; color: white; border-color: #dc3232;"
                               onclick="return confirm('<?php echo esc_js(__('هل أنت متأكد من حذف جميع المحتوى الديمو؟ لا يمكن التراجع عن هذا الإجراء.', 'my-clinic')); ?>');">
                    </p>
                </form>
            <?php endif; ?>

            <form method="post" action="" style="margin-top: 20px;">
                <?php wp_nonce_field('demo_content_action', 'demo_content_nonce'); ?>
                
                <p>
                    <input type="submit" name="restore_demo_content" class="button" value="<?php echo esc_attr__('استعادة المحتوى الديمو', 'my-clinic'); ?>" 
                           onclick="return confirm('<?php echo esc_js(__('سيتم حذف المحتوى الديمو الحالي وإعادة إضافته من جديد.', 'my-clinic')); ?>');">
                </p>
            </form>
        </div>
    </div>
    <?php
}

/**
 * Add Demo Content
 */
function my_clinic_add_demo_content() {
    // Check if demo content already exists
    $existing_clinics = get_posts(array(
        'post_type' => 'clinic',
        'meta_key' => '_is_demo_content',
        'meta_value' => '1',
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));

    if (count($existing_clinics) > 0) {
        return array(
            'success' => false,
            'message' => __('المحتوى الديمو موجود بالفعل. يرجى حذفه أولاً أو استخدام زر الاستعادة.', 'my-clinic')
        );
    }

    $theme_dir = get_template_directory();
    $theme_uri = get_template_directory_uri();
    
    // Demo Clinics Data
    $demo_clinics = array(
        array(
            'name' => 'عيادة النور الطبية',
            'address' => 'الرياض، حي العليا، شارع الملك فهد',
            'phone' => '+966 11 234 5678',
            'whatsapp' => '+966 50 123 4567',
            'description' => 'عيادة متخصصة في تقديم أفضل الخدمات الطبية مع فريق طبي متميز وأحدث الأجهزة الطبية.',
            'rating' => 4.8,
            'price' => 150,
            'specialty' => 'باطنة',
            'specialties_count' => 5,
            'doctors_count' => 8,
            'image' => 'pro-clinic.jpg',
            'features' => array(
                array('name' => 'مواقف سيارات', 'icon' => ''),
                array('name' => 'واي فاي مجاني', 'icon' => ''),
                array('name' => 'صيدلية داخلية', 'icon' => ''),
            ),
        ),
        array(
            'name' => 'مستشفى الأمل التخصصي',
            'address' => 'جدة، حي الزهراء، شارع التحلية',
            'phone' => '+966 12 345 6789',
            'whatsapp' => '+966 50 234 5678',
            'description' => 'مستشفى متكامل يقدم خدمات طبية شاملة في مختلف التخصصات مع أحدث التقنيات الطبية.',
            'rating' => 4.9,
            'price' => 200,
            'specialty' => 'عظام',
            'specialties_count' => 8,
            'doctors_count' => 12,
            'image' => 'small-hospital.jpg',
            'features' => array(
                array('name' => 'طوارئ 24 ساعة', 'icon' => ''),
                array('name' => 'مختبرات طبية', 'icon' => ''),
                array('name' => 'أشعة متقدمة', 'icon' => ''),
            ),
        ),
        array(
            'name' => 'عيادة الحياة الصحية',
            'address' => 'الدمام، حي الفيصلية، شارع الأمير سلطان',
            'phone' => '+966 13 456 7890',
            'whatsapp' => '+966 50 345 6789',
            'description' => 'عيادة حديثة تقدم خدمات طبية متميزة مع التركيز على الرعاية الصحية الشاملة للمرضى.',
            'rating' => 4.7,
            'price' => 120,
            'specialty' => 'أطفال وحديثي الولادة',
            'specialties_count' => 6,
            'doctors_count' => 10,
            'image' => 'medical.png',
            'features' => array(
                array('name' => 'عيادة أطفال', 'icon' => ''),
                array('name' => 'خدمة منزلية', 'icon' => ''),
                array('name' => 'تأمين طبي', 'icon' => ''),
            ),
        ),
    );

    // Demo Doctors Data
    $demo_doctors = array(
        array(
            'name' => 'د. أحمد محمد العلي',
            'specialty' => 'باطنة',
            'degree' => 'استاذ جامعي',
            'description' => 'طبيب متخصص في الأمراض الباطنية مع خبرة تزيد عن 15 عاماً في التشخيص والعلاج.',
            'rating' => 4.9,
            'price' => 200,
            'address' => 'الرياض، حي العليا',
            'phone' => '+966 11 234 5678',
            'whatsapp' => '+966 50 111 2222',
            'clinic_index' => 0, // عيادة النور الطبية
            'image' => 'pro1.jpg',
        ),
        array(
            'name' => 'د. فاطمة خالد السعيد',
            'specialty' => 'أطفال وحديثي الولادة',
            'degree' => 'استاذ مساعد',
            'description' => 'طبيبة أطفال متخصصة في رعاية حديثي الولادة والأطفال مع خبرة واسعة في هذا المجال.',
            'rating' => 4.8,
            'price' => 180,
            'address' => 'الرياض، حي العليا',
            'phone' => '+966 11 234 5679',
            'whatsapp' => '+966 50 111 2223',
            'clinic_index' => 0, // عيادة النور الطبية
            'image' => 'pro2.jpg',
        ),
        array(
            'name' => 'د. خالد عبدالله المطيري',
            'specialty' => 'عظام',
            'degree' => 'استاذ جامعي',
            'description' => 'جراح عظام متخصص في جراحات العمود الفقري والمفاصل مع خبرة تزيد عن 20 عاماً.',
            'rating' => 5.0,
            'price' => 250,
            'address' => 'جدة، حي الزهراء',
            'phone' => '+966 12 345 6789',
            'whatsapp' => '+966 50 222 3333',
            'clinic_index' => 1, // مستشفى الأمل التخصصي
            'image' => 'pro3.jpg',
        ),
        array(
            'name' => 'د. سارة علي الأحمد',
            'specialty' => 'نساء و توليد',
            'degree' => 'استاذ مساعد',
            'description' => 'طبيبة نساء وتوليد متخصصة في متابعة الحمل والولادة مع رعاية شاملة للأم والطفل.',
            'rating' => 4.9,
            'price' => 220,
            'address' => 'جدة، حي الزهراء',
            'phone' => '+966 12 345 6790',
            'whatsapp' => '+966 50 222 3334',
            'clinic_index' => 1, // مستشفى الأمل التخصصي
            'image' => 'man.jpg',
        ),
        array(
            'name' => 'د. محمد حسن القحطاني',
            'specialty' => 'أطفال وحديثي الولادة',
            'degree' => 'استاذ جامعي',
            'description' => 'طبيب أطفال متخصص في أمراض الأطفال والمراهقين مع خبرة واسعة في التشخيص والعلاج.',
            'rating' => 4.7,
            'price' => 150,
            'address' => 'الدمام، حي الفيصلية',
            'phone' => '+966 13 456 7890',
            'whatsapp' => '+966 50 333 4444',
            'clinic_index' => 2, // عيادة الحياة الصحية
            'image' => 'pro1-sub1.jpg',
        ),
        array(
            'name' => 'د. نورا سعد الدوسري',
            'specialty' => 'جلدية',
            'degree' => 'استاذ مساعد',
            'description' => 'طبيبة جلدية متخصصة في علاج الأمراض الجلدية والتجميل مع استخدام أحدث التقنيات.',
            'rating' => 4.8,
            'price' => 180,
            'address' => 'الدمام، حي الفيصلية',
            'phone' => '+966 13 456 7891',
            'whatsapp' => '+966 50 333 4445',
            'clinic_index' => 2, // عيادة الحياة الصحية
            'image' => 'pro1-sub2.jpg',
        ),
    );

    $created_clinics = array();
    $created_doctors = array();

    // Create clinics
    foreach ($demo_clinics as $clinic_data) {
        $clinic_post = array(
            'post_title' => $clinic_data['name'],
            'post_content' => $clinic_data['description'],
            'post_status' => 'publish',
            'post_type' => 'clinic',
        );

        $clinic_id = wp_insert_post($clinic_post);

        if (!is_wp_error($clinic_id)) {
            // Save meta data
            update_post_meta($clinic_id, '_clinic_address', $clinic_data['address']);
            update_post_meta($clinic_id, '_clinic_phone', $clinic_data['phone']);
            update_post_meta($clinic_id, '_clinic_whatsapp', $clinic_data['whatsapp']);
            update_post_meta($clinic_id, '_clinic_rating', $clinic_data['rating']);
            update_post_meta($clinic_id, '_clinic_price', $clinic_data['price']);
            update_post_meta($clinic_id, '_clinic_specialty', $clinic_data['specialty']);
            update_post_meta($clinic_id, '_clinic_specialties_count', $clinic_data['specialties_count']);
            update_post_meta($clinic_id, '_clinic_doctors_count', $clinic_data['doctors_count']);
            update_post_meta($clinic_id, '_clinic_features', $clinic_data['features']);
            update_post_meta($clinic_id, '_is_demo_content', '1');

            // Set work schedule (Sunday to Thursday, 9 AM to 5 PM)
            $schedule = array(
                'sunday' => array('from' => '09:00', 'to' => '17:00'),
                'monday' => array('from' => '09:00', 'to' => '17:00'),
                'tuesday' => array('from' => '09:00', 'to' => '17:00'),
                'wednesday' => array('from' => '09:00', 'to' => '17:00'),
                'thursday' => array('from' => '09:00', 'to' => '17:00'),
            );
            update_post_meta($clinic_id, '_clinic_work_schedule', $schedule);

            // Handle image
            $image_path = $theme_dir . '/assets/images/' . $clinic_data['image'];
            if (file_exists($image_path)) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $file_array = array(
                    'name' => basename($image_path),
                    'tmp_name' => $image_path
                );
                
                // Copy file to temp location
                $tmp_file = wp_tempnam($file_array['name']);
                copy($image_path, $tmp_file);
                $file_array['tmp_name'] = $tmp_file;
                
                $attachment_id = media_handle_sideload($file_array, $clinic_id);
                
                if (!is_wp_error($attachment_id)) {
                    set_post_thumbnail($clinic_id, $attachment_id);
                } else {
                    @unlink($tmp_file);
                }
            }

            $created_clinics[] = $clinic_id;
        }
    }

    // Create doctors
    foreach ($demo_doctors as $doctor_data) {
        $clinic_id = isset($created_clinics[$doctor_data['clinic_index']]) ? $created_clinics[$doctor_data['clinic_index']] : 0;

        $doctor_post = array(
            'post_title' => $doctor_data['name'],
            'post_content' => $doctor_data['description'],
            'post_status' => 'publish',
            'post_type' => 'doctor',
        );

        $doctor_id = wp_insert_post($doctor_post);

        if (!is_wp_error($doctor_id)) {
            // Save meta data
            update_post_meta($doctor_id, '_doctor_specialty', $doctor_data['specialty']);
            update_post_meta($doctor_id, '_doctor_degree', $doctor_data['degree']);
            update_post_meta($doctor_id, '_doctor_rating', $doctor_data['rating']);
            update_post_meta($doctor_id, '_doctor_price', $doctor_data['price']);
            update_post_meta($doctor_id, '_doctor_address', $doctor_data['address']);
            update_post_meta($doctor_id, '_doctor_phone', $doctor_data['phone']);
            update_post_meta($doctor_id, '_doctor_whatsapp', $doctor_data['whatsapp']);
            if ($clinic_id > 0) {
                update_post_meta($doctor_id, '_doctor_clinic_id', $clinic_id);
            }
            update_post_meta($doctor_id, '_is_demo_content', '1');

            // Set work schedule (Sunday to Thursday, 9 AM to 5 PM)
            $schedule = array(
                'sunday' => array('from' => '09:00', 'to' => '17:00'),
                'monday' => array('from' => '09:00', 'to' => '17:00'),
                'tuesday' => array('from' => '09:00', 'to' => '17:00'),
                'wednesday' => array('from' => '09:00', 'to' => '17:00'),
                'thursday' => array('from' => '09:00', 'to' => '17:00'),
            );
            update_post_meta($doctor_id, '_doctor_work_schedule', $schedule);

            // Handle image
            $image_path = $theme_dir . '/assets/images/' . $doctor_data['image'];
            if (file_exists($image_path)) {
                $attachment_id = my_clinic_upload_image_from_path($image_path, $doctor_data['name']);
                if ($attachment_id) {
                    set_post_thumbnail($doctor_id, $attachment_id);
                }
            }

            // Sync doctor product
            my_clinic_sync_doctor_product($doctor_id);

            $created_doctors[] = $doctor_id;
        }
    }

    return array(
        'success' => true,
        'message' => sprintf(__('تم إضافة المحتوى الديمو بنجاح: %d عيادة و %d طبيب.', 'my-clinic'), count($created_clinics), count($created_doctors))
    );
}

/**
 * Delete Demo Content
 */
function my_clinic_delete_demo_content() {
    $deleted_clinics = 0;
    $deleted_doctors = 0;

    // Delete demo clinics
    $clinics = get_posts(array(
        'post_type' => 'clinic',
        'meta_key' => '_is_demo_content',
        'meta_value' => '1',
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));

    foreach ($clinics as $clinic) {
        wp_delete_post($clinic->ID, true);
        $deleted_clinics++;
    }

    // Delete demo doctors
    $doctors = get_posts(array(
        'post_type' => 'doctor',
        'meta_key' => '_is_demo_content',
        'meta_value' => '1',
        'posts_per_page' => -1,
        'post_status' => 'any'
    ));

    foreach ($doctors as $doctor) {
        wp_delete_post($doctor->ID, true);
        $deleted_doctors++;
    }

    return array(
        'success' => true,
        'message' => sprintf(__('تم حذف المحتوى الديمو: %d عيادة و %d طبيب.', 'my-clinic'), $deleted_clinics, $deleted_doctors)
    );
}

/**
 * Restore Demo Content (delete and re-add)
 */
function my_clinic_restore_demo_content() {
    // Delete existing demo content
    $delete_result = my_clinic_delete_demo_content();
    
    if (!$delete_result['success']) {
        return $delete_result;
    }

    // Add demo content again
    $add_result = my_clinic_add_demo_content();
    
    if ($add_result['success']) {
        return array(
            'success' => true,
            'message' => __('تم استعادة المحتوى الديمو بنجاح.', 'my-clinic')
        );
    }

    return $add_result;
}
