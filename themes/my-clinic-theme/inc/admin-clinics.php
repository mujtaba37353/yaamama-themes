<?php
/**
 * Admin Page for Managing Clinics
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Admin Menu for Clinics
 */
function my_clinic_add_clinics_admin_menu() {
    add_menu_page(
        __('العيادات', 'my-clinic'),
        __('العيادات', 'my-clinic'),
        'manage_options',
        'clinics',
        'my_clinic_render_clinics_admin_page',
        'dashicons-building',
        31
    );
    
    // Add submenu for listing clinics
    add_submenu_page(
        'clinics',
        __('قائمة العيادات', 'my-clinic'),
        __('قائمة العيادات', 'my-clinic'),
        'manage_options',
        'clinics-list',
        'my_clinic_render_clinics_list_page'
    );
}
add_action('admin_menu', 'my_clinic_add_clinics_admin_menu');

/**
 * Render Clinics Admin Page
 */
function my_clinic_render_clinics_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('ليس لديك صلاحية للوصول إلى هذه الصفحة.', 'my-clinic'));
    }

    // Check if editing
    $clinic_id = isset($_GET['clinic_id']) ? intval($_GET['clinic_id']) : 0;
    $is_edit = $clinic_id > 0;
    $clinic_data = null;
    
    if ($is_edit) {
        $clinic = get_post($clinic_id);
        if ($clinic && $clinic->post_type === 'clinic') {
            $clinic_meta = my_clinic_get_clinic_meta($clinic_id);
            $clinic_data = array(
                'name' => $clinic->post_title,
                'address' => $clinic_meta['address'],
                'phone' => $clinic_meta['phone'],
                'whatsapp' => $clinic_meta['whatsapp'],
                'description' => $clinic->post_content,
                'rating' => $clinic_meta['rating'],
                'price' => $clinic_meta['price'],
                'specialty' => $clinic_meta['specialty'],
                'specialties_count' => $clinic_meta['specialties_count'],
                'doctors_count' => $clinic_meta['doctors_count'],
                'work_schedule' => $clinic_meta['work_schedule'],
                'features' => $clinic_meta['features'],
            );
        } else {
            $is_edit = false;
            $clinic_id = 0;
        }
    }

    // Handle form submission
    $message = '';
    $message_type = '';

    if (isset($_POST['add_clinic']) && wp_verify_nonce($_POST['add_clinic_nonce'], 'add_clinic_action')) {
        if ($is_edit && $clinic_id > 0) {
            $result = my_clinic_process_update_clinic_form($clinic_id);
        } else {
            $result = my_clinic_process_add_clinic_form();
        }
        
        if ($result['success']) {
            $saved_clinic_id = isset($result['clinic_id']) ? $result['clinic_id'] : $clinic_id;
            $edit_link = $saved_clinic_id ? admin_url('admin.php?page=clinics&clinic_id=' . $saved_clinic_id) : '';
            $view_link = $saved_clinic_id ? get_permalink($saved_clinic_id) : '';
            $message = $is_edit ? __('تم تحديث العيادة بنجاح!', 'my-clinic') : __('تم إضافة العيادة بنجاح!', 'my-clinic');
            if ($edit_link) {
                $message .= ' <a href="' . esc_url($edit_link) . '">' . __('تعديل', 'my-clinic') . '</a>';
            }
            if ($view_link) {
                $message .= ' | <a href="' . esc_url($view_link) . '" target="_blank">' . __('عرض', 'my-clinic') . '</a>';
            }
            $message_type = 'success';
            
            // Reload clinic data after update
            if ($is_edit && $saved_clinic_id > 0) {
                $clinic = get_post($saved_clinic_id);
                if ($clinic) {
                    $clinic_meta = my_clinic_get_clinic_meta($saved_clinic_id);
                    $clinic_data = array(
                        'name' => $clinic->post_title,
                        'address' => $clinic_meta['address'],
                        'phone' => $clinic_meta['phone'],
                        'whatsapp' => $clinic_meta['whatsapp'],
                        'description' => $clinic->post_content,
                        'rating' => $clinic_meta['rating'],
                        'price' => $clinic_meta['price'],
                        'specialty' => $clinic_meta['specialty'],
                        'specialties_count' => $clinic_meta['specialties_count'],
                        'doctors_count' => $clinic_meta['doctors_count'],
                        'work_schedule' => $clinic_meta['work_schedule'],
                        'features' => $clinic_meta['features'],
                    );
                }
            }
        } else {
            $message = $result['message'];
            $message_type = 'error';
        }
    }

    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('إدارة العيادات', 'my-clinic'); ?></h1>
        
        <?php if ($message): ?>
            <div class="notice notice-<?php echo esc_attr($message_type); ?> is-dismissible">
                <p><?php echo wp_kses_post($message); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data" class="my-clinic-admin-form">
            <?php wp_nonce_field('add_clinic_action', 'add_clinic_nonce'); ?>
            <?php if ($is_edit): ?>
                <input type="hidden" name="clinic_id" value="<?php echo esc_attr($clinic_id); ?>">
            <?php endif; ?>
            
            <h2><?php echo $is_edit ? esc_html__('تعديل العيادة', 'my-clinic') : esc_html__('إضافة عيادة جديدة', 'my-clinic'); ?></h2>
            
            <?php if ($is_edit): ?>
                <p><a href="<?php echo esc_url(admin_url('admin.php?page=clinics')); ?>" class="button"><?php echo esc_html__('إضافة عيادة جديدة', 'my-clinic'); ?></a></p>
            <?php endif; ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="clinic_name"><?php echo esc_html__('اسم العيادة', 'my-clinic'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <input type="text" id="clinic_name" name="clinic_name" class="regular-text" required value="<?php 
                            if (isset($_POST['clinic_name'])) {
                                echo esc_attr($_POST['clinic_name']);
                            } elseif ($is_edit && $clinic_data) {
                                echo esc_attr($clinic_data['name']);
                            }
                        ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="clinic_address"><?php echo esc_html__('العنوان', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="clinic_address" name="clinic_address" class="regular-text" value="<?php 
                            if (isset($_POST['clinic_address'])) {
                                echo esc_attr($_POST['clinic_address']);
                            } elseif ($is_edit && $clinic_data) {
                                echo esc_attr($clinic_data['address']);
                            }
                        ?>" placeholder="<?php echo esc_attr__('مثال: حي العليا، شارع الملك فهد، الرياض', 'my-clinic'); ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="clinic_phone"><?php echo esc_html__('رقم الهاتف', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="clinic_phone" name="clinic_phone" class="regular-text" value="<?php 
                            if (isset($_POST['clinic_phone'])) {
                                echo esc_attr($_POST['clinic_phone']);
                            } elseif ($is_edit && $clinic_data) {
                                echo esc_attr($clinic_data['phone']);
                            }
                        ?>" placeholder="<?php echo esc_attr__('مثال: +966123456789', 'my-clinic'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="clinic_whatsapp"><?php echo esc_html__('رقم الواتساب', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="clinic_whatsapp" name="clinic_whatsapp" class="regular-text" value="<?php 
                            if (isset($_POST['clinic_whatsapp'])) {
                                echo esc_attr($_POST['clinic_whatsapp']);
                            } elseif ($is_edit && $clinic_data) {
                                echo esc_attr($clinic_data['whatsapp']);
                            }
                        ?>" placeholder="<?php echo esc_attr__('مثال: +966123456789', 'my-clinic'); ?>">
                        <p class="description"><?php echo esc_html__('سيتم استخدام هذا الرقم لعرض زر "احجز عبر الواتساب"', 'my-clinic'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="clinic_description"><?php echo esc_html__('الوصف الكامل', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <?php
                        if (isset($_POST['clinic_description'])) {
                            $description = wp_kses_post($_POST['clinic_description']);
                        } elseif ($is_edit && $clinic_data) {
                            $description = $clinic_data['description'];
                        } else {
                            $description = '';
                        }
                        wp_editor($description, 'clinic_description', array(
                            'textarea_name' => 'clinic_description',
                            'textarea_rows' => 10,
                            'media_buttons' => false,
                        ));
                        ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="clinic_rating"><?php echo esc_html__('التقييم', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="clinic_rating" name="clinic_rating" class="regular-text" step="0.1" min="0" max="5" value="<?php 
                            if (isset($_POST['clinic_rating'])) {
                                echo esc_attr($_POST['clinic_rating']);
                            } elseif ($is_edit && $clinic_data) {
                                echo esc_attr($clinic_data['rating']);
                            } else {
                                echo '5';
                            }
                        ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="clinic_price"><?php echo esc_html__('سعر الكشف (ريال)', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="clinic_price" name="clinic_price" class="regular-text" step="0.01" min="0" value="<?php 
                            if (isset($_POST['clinic_price'])) {
                                echo esc_attr($_POST['clinic_price']);
                            } elseif ($is_edit && $clinic_data) {
                                echo esc_attr($clinic_data['price']);
                            } else {
                                echo '100';
                            }
                        ?>">
                        <p class="description"><?php echo esc_html__('يُستخدم لإنشاء منتج WooCommerce مرتبط بالعيادة.', 'my-clinic'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="clinic_specialty"><?php echo esc_html__('التخصص', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="clinic_specialty" name="clinic_specialty" class="regular-text" value="<?php 
                            if (isset($_POST['clinic_specialty'])) {
                                echo esc_attr($_POST['clinic_specialty']);
                            } elseif ($is_edit && isset($clinic_data['specialty'])) {
                                echo esc_attr($clinic_data['specialty']);
                            }
                        ?>" placeholder="<?php echo esc_attr__('مثال: عظام، باطنة، أسنان', 'my-clinic'); ?>">
                        <p class="description"><?php echo esc_html__('التخصص الرئيسي للعيادة', 'my-clinic'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="clinic_specialties_count"><?php echo esc_html__('عدد التخصصات', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="clinic_specialties_count" name="clinic_specialties_count" class="regular-text" min="0" value="<?php 
                            if (isset($_POST['clinic_specialties_count'])) {
                                echo esc_attr($_POST['clinic_specialties_count']);
                            } elseif ($is_edit && $clinic_data) {
                                echo esc_attr($clinic_data['specialties_count']);
                            } else {
                                echo '0';
                            }
                        ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="clinic_doctors_count"><?php echo esc_html__('عدد الأطباء', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="clinic_doctors_count" name="clinic_doctors_count" class="regular-text" min="0" value="<?php 
                            if (isset($_POST['clinic_doctors_count'])) {
                                echo esc_attr($_POST['clinic_doctors_count']);
                            } elseif ($is_edit && $clinic_data) {
                                echo esc_attr($clinic_data['doctors_count']);
                            } else {
                                echo '0';
                            }
                        ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="clinic_image"><?php echo esc_html__('صورة العيادة', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <?php if ($is_edit && $clinic_id): 
                            $current_image = get_the_post_thumbnail_url($clinic_id, 'thumbnail');
                            if ($current_image):
                        ?>
                            <div style="margin-bottom: 10px;">
                                <img src="<?php echo esc_url($current_image); ?>" alt="صورة العيادة الحالية" style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px;">
                                <p class="description"><?php echo esc_html__('الصورة الحالية', 'my-clinic'); ?></p>
                            </div>
                        <?php endif; endif; ?>
                        <input type="file" id="clinic_image" name="clinic_image" accept="image/*">
                        <p class="description"><?php echo $is_edit ? esc_html__('اختر صورة جديدة للعيادة (اتركه فارغاً للاحتفاظ بالصورة الحالية)', 'my-clinic') : esc_html__('اختر صورة للعيادة', 'my-clinic'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="clinic_gallery_images"><?php echo esc_html__('صور العيادة', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <?php 
                        if ($is_edit && $clinic_id): 
                            $current_gallery = get_post_meta($clinic_id, '_clinic_images', true);
                            if (!empty($current_gallery) && is_array($current_gallery)):
                        ?>
                            <div style="margin-bottom: 15px;">
                                <p class="description" style="margin-bottom: 10px;"><?php echo esc_html__('الصور الحالية:', 'my-clinic'); ?></p>
                                <div style="display: flex; flex-wrap: wrap; gap: 10px;">
                                    <?php foreach ($current_gallery as $img_url): 
                                        if (!empty($img_url)):
                                    ?>
                                        <img src="<?php echo esc_url($img_url); ?>" alt="صورة العيادة" style="max-width: 100px; height: auto; border: 1px solid #ddd; padding: 5px;">
                                    <?php 
                                        endif;
                                    endforeach; ?>
                                </div>
                                <p class="description" style="margin-top: 10px;"><?php echo esc_html__('لحذف الصور الحالية، ارفع صور جديدة. لاحتفاظ بالصور الحالية، اترك الحقل فارغاً.', 'my-clinic'); ?></p>
                            </div>
                        <?php endif; endif; ?>
                        <input type="file" id="clinic_gallery_images" name="clinic_gallery_images[]" accept="image/*" multiple>
                        <p class="description"><?php echo esc_html__('يمكنك اختيار عدة صور لعرضها في قسم "صور العيادة" على صفحة العيادة', 'my-clinic'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label><?php echo esc_html__('مميزات العيادة', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <div id="clinic-features-container">
                            <?php
                            $current_features = array();
                            if ($is_edit && $clinic_id) {
                                $saved_features = get_post_meta($clinic_id, '_clinic_features', true);
                                if (!empty($saved_features) && is_array($saved_features)) {
                                    $current_features = $saved_features;
                                }
                            } elseif (isset($_POST['clinic_features']) && is_array($_POST['clinic_features'])) {
                                $current_features = $_POST['clinic_features'];
                            }
                            
                            if (empty($current_features)) {
                                $current_features = array(array('name' => '', 'icon' => ''));
                            }
                            
                            foreach ($current_features as $index => $feature):
                                $feature_name = isset($feature['name']) ? esc_attr($feature['name']) : '';
                                $feature_icon = isset($feature['icon']) ? esc_attr($feature['icon']) : '';
                            ?>
                            <div class="clinic-feature-item" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">
                                <div style="display: flex; gap: 10px; align-items: flex-start;">
                                    <div style="flex: 1;">
                                        <label style="display: block; margin-bottom: 5px; font-weight: 600;"><?php echo esc_html__('اسم الميزة', 'my-clinic'); ?></label>
                                        <input type="text" name="clinic_features[<?php echo $index; ?>][name]" value="<?php echo $feature_name; ?>" class="regular-text" placeholder="<?php echo esc_attr__('مثال: موقف سيارات', 'my-clinic'); ?>">
                                    </div>
                                    <div style="flex: 1;">
                                        <label style="display: block; margin-bottom: 5px; font-weight: 600;"><?php echo esc_html__('رابط الأيقونة (اختياري)', 'my-clinic'); ?></label>
                                        <input type="text" name="clinic_features[<?php echo $index; ?>][icon]" value="<?php echo $feature_icon; ?>" class="regular-text" placeholder="<?php echo esc_attr__('رابط الصورة أو اتركه فارغاً', 'my-clinic'); ?>">
                                    </div>
                                    <div style="padding-top: 25px;">
                                        <button type="button" class="button remove-feature" style="background: #dc3232; color: white; border-color: #dc3232;"><?php echo esc_html__('حذف', 'my-clinic'); ?></button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="button" id="add-clinic-feature" class="button" style="margin-top: 10px;"><?php echo esc_html__('+ إضافة ميزة جديدة', 'my-clinic'); ?></button>
                        <p class="description"><?php echo esc_html__('أضف مميزات العيادة مثل: موقف سيارات، تكييف، الدفع الإلكتروني، إلخ. الأيقونة اختيارية.', 'my-clinic'); ?></p>
                    </td>
                </tr>
            </table>

            <h3><?php echo esc_html__('جدول العمل', 'my-clinic'); ?></h3>
            <table class="form-table">
                <?php
                $days = array(
                    'sunday' => __('الأحد', 'my-clinic'),
                    'monday' => __('الاثنين', 'my-clinic'),
                    'tuesday' => __('الثلاثاء', 'my-clinic'),
                    'wednesday' => __('الأربعاء', 'my-clinic'),
                    'thursday' => __('الخميس', 'my-clinic'),
                    'friday' => __('الجمعة', 'my-clinic'),
                    'saturday' => __('السبت', 'my-clinic'),
                );
                
                foreach ($days as $day_key => $day_label):
                    // Determine if day is checked
                    $day_checked = false;
                    $day_from = '10:00';
                    $day_to = '17:00';
                    
                    if (isset($_POST['work_days']) && in_array($day_key, $_POST['work_days'])) {
                        $day_checked = true;
                        if (isset($_POST['work_hours'][$day_key]['from'])) {
                            $day_from = $_POST['work_hours'][$day_key]['from'];
                        }
                        if (isset($_POST['work_hours'][$day_key]['to'])) {
                            $day_to = $_POST['work_hours'][$day_key]['to'];
                        }
                    } elseif ($is_edit && $clinic_data && isset($clinic_data['work_schedule'][$day_key])) {
                        $day_checked = true;
                        $day_from = isset($clinic_data['work_schedule'][$day_key]['from']) ? $clinic_data['work_schedule'][$day_key]['from'] : '10:00';
                        $day_to = isset($clinic_data['work_schedule'][$day_key]['to']) ? $clinic_data['work_schedule'][$day_key]['to'] : '17:00';
                    }
                ?>
                <tr>
                    <th scope="row">
                        <label>
                            <input type="checkbox" name="work_days[]" value="<?php echo esc_attr($day_key); ?>" <?php checked($day_checked); ?>>
                            <?php echo esc_html($day_label); ?>
                        </label>
                    </th>
                    <td>
                        <label>
                            <?php echo esc_html__('من:', 'my-clinic'); ?>
                            <input type="time" name="work_hours[<?php echo esc_attr($day_key); ?>][from]" value="<?php echo esc_attr($day_from); ?>" style="margin-left: 10px;">
                        </label>
                        <label style="margin-right: 20px;">
                            <?php echo esc_html__('إلى:', 'my-clinic'); ?>
                            <input type="time" name="work_hours[<?php echo esc_attr($day_key); ?>][to]" value="<?php echo esc_attr($day_to); ?>" style="margin-left: 10px;">
                        </label>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>

            <p class="submit">
                <input type="submit" name="add_clinic" class="button button-primary" value="<?php echo $is_edit ? esc_attr__('تحديث العيادة', 'my-clinic') : esc_attr__('إضافة العيادة', 'my-clinic'); ?>">
                <?php if ($is_edit): ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=clinics-list')); ?>" class="button"><?php echo esc_html__('إلغاء', 'my-clinic'); ?></a>
                <?php endif; ?>
            </p>
        </form>
    </div>

    <style>
        .my-clinic-admin-form .form-table th {
            width: 200px;
        }
        .my-clinic-admin-form .required {
            color: #dc3232;
        }
        .clinic-feature-item {
            position: relative;
        }
    </style>
    <script>
    jQuery(document).ready(function($) {
        var featureIndex = <?php echo count($current_features ?? array()); ?>;
        
        // Add new feature
        $('#add-clinic-feature').on('click', function() {
            var featureHtml = '<div class="clinic-feature-item" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 4px; background: #f9f9f9;">' +
                '<div style="display: flex; gap: 10px; align-items: flex-start;">' +
                '<div style="flex: 1;">' +
                '<label style="display: block; margin-bottom: 5px; font-weight: 600;"><?php echo esc_js(__('اسم الميزة', 'my-clinic')); ?></label>' +
                '<input type="text" name="clinic_features[' + featureIndex + '][name]" value="" class="regular-text" placeholder="<?php echo esc_js(__('مثال: موقف سيارات', 'my-clinic')); ?>">' +
                '</div>' +
                '<div style="flex: 1;">' +
                '<label style="display: block; margin-bottom: 5px; font-weight: 600;"><?php echo esc_js(__('رابط الأيقونة (اختياري)', 'my-clinic')); ?></label>' +
                '<input type="text" name="clinic_features[' + featureIndex + '][icon]" value="" class="regular-text" placeholder="<?php echo esc_js(__('رابط الصورة أو اتركه فارغاً', 'my-clinic')); ?>">' +
                '</div>' +
                '<div style="padding-top: 25px;">' +
                '<button type="button" class="button remove-feature" style="background: #dc3232; color: white; border-color: #dc3232;"><?php echo esc_js(__('حذف', 'my-clinic')); ?></button>' +
                '</div>' +
                '</div>' +
                '</div>';
            
            $('#clinic-features-container').append(featureHtml);
            featureIndex++;
        });
        
        // Remove feature
        $(document).on('click', '.remove-feature', function() {
            $(this).closest('.clinic-feature-item').remove();
        });
    });
    </script>
    <?php
}

/**
 * Process Add Clinic Form Submission
 */
function my_clinic_process_add_clinic_form() {
    // Validate required fields
    if (empty($_POST['clinic_name'])) {
        return array(
            'success' => false,
            'message' => __('يرجى إدخال اسم العيادة.', 'my-clinic')
        );
    }

    // Sanitize input
    $clinic_name = sanitize_text_field($_POST['clinic_name']);
    $address = isset($_POST['clinic_address']) ? sanitize_text_field($_POST['clinic_address']) : '';
    $phone = isset($_POST['clinic_phone']) ? sanitize_text_field($_POST['clinic_phone']) : '';
    $whatsapp = isset($_POST['clinic_whatsapp']) ? sanitize_text_field($_POST['clinic_whatsapp']) : '';
    $description = isset($_POST['clinic_description']) ? wp_kses_post($_POST['clinic_description']) : '';
    $rating = isset($_POST['clinic_rating']) ? floatval($_POST['clinic_rating']) : 5;
    $price = isset($_POST['clinic_price']) ? floatval($_POST['clinic_price']) : 100;
    $specialty = isset($_POST['clinic_specialty']) ? sanitize_text_field($_POST['clinic_specialty']) : '';
    $specialties_count = isset($_POST['clinic_specialties_count']) ? intval($_POST['clinic_specialties_count']) : 0;
    $doctors_count = isset($_POST['clinic_doctors_count']) ? intval($_POST['clinic_doctors_count']) : 0;
    
    // Process clinic features
    $features = array();
    if (isset($_POST['clinic_features']) && is_array($_POST['clinic_features'])) {
        foreach ($_POST['clinic_features'] as $feature) {
            $feature_name = isset($feature['name']) ? sanitize_text_field($feature['name']) : '';
            $feature_icon = isset($feature['icon']) ? esc_url_raw($feature['icon']) : '';
            
            // Only add feature if name is not empty
            if (!empty($feature_name)) {
                $features[] = array(
                    'name' => $feature_name,
                    'icon' => $feature_icon,
                );
            }
        }
    }

    // Create clinic post
    $clinic_data = array(
        'post_title' => $clinic_name,
        'post_status' => 'publish',
        'post_type' => 'clinic',
        'post_content' => $description,
    );

    $clinic_id = wp_insert_post($clinic_data);

    if (is_wp_error($clinic_id)) {
        return array(
            'success' => false,
            'message' => __('حدث خطأ أثناء إنشاء العيادة: ', 'my-clinic') . $clinic_id->get_error_message()
        );
    }

    // Save custom fields
    update_post_meta($clinic_id, '_clinic_address', $address);
    update_post_meta($clinic_id, '_clinic_phone', $phone);
    update_post_meta($clinic_id, '_clinic_whatsapp', $whatsapp);
    update_post_meta($clinic_id, '_clinic_rating', $rating);
    update_post_meta($clinic_id, '_clinic_price', $price);
    update_post_meta($clinic_id, '_clinic_specialties_count', $specialties_count);
    update_post_meta($clinic_id, '_clinic_doctors_count', $doctors_count);

    // Save work schedule
    $work_days = isset($_POST['work_days']) ? array_map('sanitize_text_field', $_POST['work_days']) : array();
    $work_hours = isset($_POST['work_hours']) ? $_POST['work_hours'] : array();
    
    $schedule = array();
    foreach ($work_days as $day) {
        if (isset($work_hours[$day])) {
            $schedule[$day] = array(
                'from' => sanitize_text_field($work_hours[$day]['from']),
                'to' => sanitize_text_field($work_hours[$day]['to']),
            );
        }
    }
    update_post_meta($clinic_id, '_clinic_work_schedule', $schedule);

    // Handle image upload
    if (!empty($_FILES['clinic_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('clinic_image', $clinic_id);
        
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($clinic_id, $attachment_id);
        }
    }

    // Handle gallery images upload
    if (!empty($_FILES['clinic_gallery_images']['name'][0])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $gallery_images = array();
        $files = $_FILES['clinic_gallery_images'];
        
        foreach ($files['name'] as $key => $value) {
            if ($files['name'][$key]) {
                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );
                
                $_FILES['clinic_gallery_image'] = $file;
                $attachment_id = media_handle_upload('clinic_gallery_image', $clinic_id);
                
                if (!is_wp_error($attachment_id)) {
                    $image_url = wp_get_attachment_image_url($attachment_id, 'large');
                    if ($image_url) {
                        $gallery_images[] = $image_url;
                    }
                }
            }
        }
        
        if (!empty($gallery_images)) {
            update_post_meta($clinic_id, '_clinic_images', $gallery_images);
        }
    }

    my_clinic_sync_clinic_product($clinic_id);

    return array(
        'success' => true,
        'message' => sprintf(__('تم إضافة العيادة "%s" بنجاح!', 'my-clinic'), $clinic_name),
        'clinic_id' => $clinic_id
    );
}

/**
 * Process Update Clinic Form Submission
 */
function my_clinic_process_update_clinic_form($clinic_id) {
    // Validate required fields
    if (empty($_POST['clinic_name'])) {
        return array(
            'success' => false,
            'message' => __('يرجى إدخال اسم العيادة.', 'my-clinic')
        );
    }

    // Verify clinic exists and is correct type
    $clinic = get_post($clinic_id);
    if (!$clinic || $clinic->post_type !== 'clinic') {
        return array(
            'success' => false,
            'message' => __('العيادة غير موجودة.', 'my-clinic')
        );
    }

    // Sanitize input
    $clinic_name = sanitize_text_field($_POST['clinic_name']);
    $address = isset($_POST['clinic_address']) ? sanitize_text_field($_POST['clinic_address']) : '';
    $phone = isset($_POST['clinic_phone']) ? sanitize_text_field($_POST['clinic_phone']) : '';
    $whatsapp = isset($_POST['clinic_whatsapp']) ? sanitize_text_field($_POST['clinic_whatsapp']) : '';
    $description = isset($_POST['clinic_description']) ? wp_kses_post($_POST['clinic_description']) : '';
    $rating = isset($_POST['clinic_rating']) ? floatval($_POST['clinic_rating']) : 5;
    $price = isset($_POST['clinic_price']) ? floatval($_POST['clinic_price']) : 100;
    $specialty = isset($_POST['clinic_specialty']) ? sanitize_text_field($_POST['clinic_specialty']) : '';
    $specialties_count = isset($_POST['clinic_specialties_count']) ? intval($_POST['clinic_specialties_count']) : 0;
    $doctors_count = isset($_POST['clinic_doctors_count']) ? intval($_POST['clinic_doctors_count']) : 0;
    
    // Process clinic features
    $features = array();
    if (isset($_POST['clinic_features']) && is_array($_POST['clinic_features'])) {
        foreach ($_POST['clinic_features'] as $feature) {
            $feature_name = isset($feature['name']) ? sanitize_text_field($feature['name']) : '';
            $feature_icon = isset($feature['icon']) ? esc_url_raw($feature['icon']) : '';
            
            // Only add feature if name is not empty
            if (!empty($feature_name)) {
                $features[] = array(
                    'name' => $feature_name,
                    'icon' => $feature_icon,
                );
            }
        }
    }

    // Update clinic post
    $clinic_data = array(
        'ID' => $clinic_id,
        'post_title' => $clinic_name,
        'post_content' => $description,
    );

    $result = wp_update_post($clinic_data);

    if (is_wp_error($result)) {
        return array(
            'success' => false,
            'message' => __('حدث خطأ أثناء تحديث العيادة: ', 'my-clinic') . $result->get_error_message()
        );
    }

    // Save custom fields
    update_post_meta($clinic_id, '_clinic_address', $address);
    update_post_meta($clinic_id, '_clinic_phone', $phone);
    update_post_meta($clinic_id, '_clinic_whatsapp', $whatsapp);
    update_post_meta($clinic_id, '_clinic_rating', $rating);
    update_post_meta($clinic_id, '_clinic_price', $price);
    update_post_meta($clinic_id, '_clinic_specialty', $specialty);
    update_post_meta($clinic_id, '_clinic_specialties_count', $specialties_count);
    update_post_meta($clinic_id, '_clinic_doctors_count', $doctors_count);
    update_post_meta($clinic_id, '_clinic_features', $features);

    // Save work schedule
    $work_days = isset($_POST['work_days']) ? array_map('sanitize_text_field', $_POST['work_days']) : array();
    $work_hours = isset($_POST['work_hours']) ? $_POST['work_hours'] : array();
    
    $schedule = array();
    foreach ($work_days as $day) {
        if (isset($work_hours[$day])) {
            $schedule[$day] = array(
                'from' => sanitize_text_field($work_hours[$day]['from']),
                'to' => sanitize_text_field($work_hours[$day]['to']),
            );
        }
    }
    update_post_meta($clinic_id, '_clinic_work_schedule', $schedule);

    // Handle image upload (only if new image is provided)
    if (!empty($_FILES['clinic_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('clinic_image', $clinic_id);
        
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($clinic_id, $attachment_id);
        }
    }

    // Handle gallery images upload (only if new images are provided)
    if (!empty($_FILES['clinic_gallery_images']['name'][0])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $gallery_images = array();
        $files = $_FILES['clinic_gallery_images'];
        
        foreach ($files['name'] as $key => $value) {
            if ($files['name'][$key]) {
                $file = array(
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                );
                
                $_FILES['clinic_gallery_image'] = $file;
                $attachment_id = media_handle_upload('clinic_gallery_image', $clinic_id);
                
                if (!is_wp_error($attachment_id)) {
                    $image_url = wp_get_attachment_image_url($attachment_id, 'large');
                    if ($image_url) {
                        $gallery_images[] = $image_url;
                    }
                }
            }
        }
        
        if (!empty($gallery_images)) {
            update_post_meta($clinic_id, '_clinic_images', $gallery_images);
        }
    }

    my_clinic_sync_clinic_product($clinic_id);

    return array(
        'success' => true,
        'message' => sprintf(__('تم تحديث العيادة "%s" بنجاح!', 'my-clinic'), $clinic_name),
        'clinic_id' => $clinic_id
    );
}

/**
 * Render Clinics List Page
 */
function my_clinic_render_clinics_list_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('ليس لديك صلاحية للوصول إلى هذه الصفحة.', 'my-clinic'));
    }

    // Handle delete action
    $message = '';
    $message_type = '';

    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['clinic_id'])) {
        if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_clinic_' . $_GET['clinic_id'])) {
            $clinic_id = intval($_GET['clinic_id']);
            $result = wp_delete_post($clinic_id, true);
            
            if ($result) {
                $message = __('تم حذف العيادة بنجاح!', 'my-clinic');
                $message_type = 'success';
            } else {
                $message = __('حدث خطأ أثناء حذف العيادة.', 'my-clinic');
                $message_type = 'error';
            }
        } else {
            $message = __('التحقق من الأمان فشل. يرجى المحاولة مرة أخرى.', 'my-clinic');
            $message_type = 'error';
        }
    }

    // Get all clinics
    $clinics = get_posts(array(
        'post_type' => 'clinic',
        'post_status' => 'any',
        'numberposts' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('قائمة العيادات', 'my-clinic'); ?></h1>
        
        <?php if ($message): ?>
            <div class="notice notice-<?php echo esc_attr($message_type); ?> is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <div style="margin: 20px 0;">
            <a href="<?php echo esc_url(admin_url('admin.php?page=clinics')); ?>" class="button"><?php echo esc_html__('إضافة عيادة جديدة', 'my-clinic'); ?></a>
        </div>

        <?php if (!empty($clinics)): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 80px;"><?php echo esc_html__('الصورة', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('اسم العيادة', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('العنوان', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('التقييم', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('عدد التخصصات', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('عدد الأطباء', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('الحالة', 'my-clinic'); ?></th>
                        <th style="width: 200px;"><?php echo esc_html__('الإجراءات', 'my-clinic'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clinics as $clinic): 
                        $clinic_id = $clinic->ID;
                        $clinic_meta = my_clinic_get_clinic_meta($clinic_id);
                        $clinic_image = get_the_post_thumbnail_url($clinic_id, 'thumbnail');
                        $edit_url = admin_url('admin.php?page=clinics&clinic_id=' . $clinic_id);
                        $view_url = get_permalink($clinic_id);
                        $delete_url = wp_nonce_url(
                            admin_url('admin.php?page=clinics-list&action=delete&clinic_id=' . $clinic_id),
                            'delete_clinic_' . $clinic_id
                        );
                    ?>
                    <tr>
                        <td>
                            <?php if ($clinic_image): ?>
                                <img src="<?php echo esc_url($clinic_image); ?>" alt="<?php echo esc_attr($clinic->post_title); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <span style="display: inline-block; width: 50px; height: 50px; background: #ddd; border-radius: 4px;"></span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo esc_html($clinic->post_title); ?></strong></td>
                        <td><?php echo esc_html($clinic_meta['address'] ?: '-'); ?></td>
                        <td><?php echo esc_html($clinic_meta['rating']); ?> ⭐</td>
                        <td><?php echo esc_html($clinic_meta['specialties_count']); ?></td>
                        <td><?php echo esc_html($clinic_meta['doctors_count']); ?></td>
                        <td>
                            <?php if ($clinic->post_status === 'publish'): ?>
                                <span style="color: #46b450;"><?php echo esc_html__('منشور', 'my-clinic'); ?></span>
                            <?php else: ?>
                                <span style="color: #dc3232;"><?php echo esc_html__('مسودة', 'my-clinic'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url($edit_url); ?>" class="button button-small"><?php echo esc_html__('تعديل', 'my-clinic'); ?></a>
                            <a href="<?php echo esc_url($view_url); ?>" class="button button-small" target="_blank"><?php echo esc_html__('عرض', 'my-clinic'); ?></a>
                            <a href="<?php echo esc_url($delete_url); ?>" class="button button-small button-link-delete" onclick="return confirm('<?php echo esc_js(__('هل أنت متأكد من حذف هذه العيادة؟', 'my-clinic')); ?>');"><?php echo esc_html__('حذف', 'my-clinic'); ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><?php echo esc_html__('لا توجد عيادات مضافة حالياً.', 'my-clinic'); ?></p>
            <p><a href="<?php echo esc_url(admin_url('admin.php?page=clinics')); ?>" class="button button-primary"><?php echo esc_html__('إضافة عيادة جديدة', 'my-clinic'); ?></a></p>
        <?php endif; ?>
    </div>

    <style>
        .wp-list-table th {
            font-weight: 600;
        }
        .button-link-delete {
            color: #a00 !important;
        }
        .button-link-delete:hover {
            color: #dc3232 !important;
        }
    </style>
    <?php
}
