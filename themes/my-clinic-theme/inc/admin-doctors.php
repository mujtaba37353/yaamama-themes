<?php
/**
 * Admin Page for Managing Doctors
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Admin Menu for Doctors
 */
function my_clinic_add_doctors_admin_menu() {
    add_menu_page(
        __('الأطباء', 'my-clinic'),
        __('الأطباء', 'my-clinic'),
        'manage_options',
        'doctors',
        'my_clinic_render_doctors_admin_page',
        'dashicons-groups',
        30
    );
    
    // Add submenu for listing doctors
    add_submenu_page(
        'doctors',
        __('قائمة الأطباء', 'my-clinic'),
        __('قائمة الأطباء', 'my-clinic'),
        'manage_options',
        'doctors-list',
        'my_clinic_render_doctors_list_page'
    );
    
    // Add submenu for managing reviews
    add_submenu_page(
        'doctors',
        __('تقييمات الأطباء', 'my-clinic'),
        __('تقييمات الأطباء', 'my-clinic'),
        'manage_options',
        'doctors-reviews',
        'my_clinic_render_doctors_reviews_page'
    );
}
add_action('admin_menu', 'my_clinic_add_doctors_admin_menu');

/**
 * Render Doctors Admin Page
 */
function my_clinic_render_doctors_admin_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('ليس لديك صلاحية للوصول إلى هذه الصفحة.', 'my-clinic'));
    }

    // Check if editing
    $doctor_id = isset($_GET['doctor_id']) ? intval($_GET['doctor_id']) : 0;
    $is_edit = $doctor_id > 0;
    $doctor_data = null;
    
    if ($is_edit) {
        $doctor = get_post($doctor_id);
        if ($doctor && $doctor->post_type === 'doctor') {
            $doctor_meta = my_clinic_get_doctor_meta($doctor_id);
            $doctor_data = array(
                'name' => $doctor->post_title,
                'specialty' => $doctor_meta['specialty'],
                'degree' => $doctor_meta['degree'],
                'description' => $doctor->post_content,
                'rating' => $doctor_meta['rating'],
                'price' => $doctor_meta['price'],
                'address' => $doctor_meta['address'],
                'phone' => $doctor_meta['phone'],
                'whatsapp' => $doctor_meta['whatsapp'],
                'clinic_id' => $doctor_meta['clinic_id'],
                'work_schedule' => $doctor_meta['work_schedule'],
            );
        } else {
            $is_edit = false;
            $doctor_id = 0;
        }
    }

    // Handle form submission
    $message = '';
    $message_type = '';

    if (isset($_POST['add_doctor']) && wp_verify_nonce($_POST['add_doctor_nonce'], 'add_doctor_action')) {
        if ($is_edit && $doctor_id > 0) {
            $result = my_clinic_process_update_doctor_form($doctor_id);
        } else {
            $result = my_clinic_process_add_doctor_form();
        }
        
        if ($result['success']) {
            $saved_doctor_id = isset($result['doctor_id']) ? $result['doctor_id'] : $doctor_id;
            $edit_link = $saved_doctor_id ? admin_url('admin.php?page=doctors&doctor_id=' . $saved_doctor_id) : '';
            $view_link = $saved_doctor_id ? get_permalink($saved_doctor_id) : '';
            $message = $is_edit ? __('تم تحديث الطبيب بنجاح!', 'my-clinic') : __('تم إضافة الطبيب بنجاح!', 'my-clinic');
            if ($edit_link) {
                $message .= ' <a href="' . esc_url($edit_link) . '">' . __('تعديل', 'my-clinic') . '</a>';
            }
            if ($view_link) {
                $message .= ' | <a href="' . esc_url($view_link) . '" target="_blank">' . __('عرض', 'my-clinic') . '</a>';
            }
            $message_type = 'success';
            
            // Reload doctor data after update
            if ($is_edit && $saved_doctor_id > 0) {
                $doctor = get_post($saved_doctor_id);
                if ($doctor) {
                    $doctor_meta = my_clinic_get_doctor_meta($saved_doctor_id);
                    $doctor_data = array(
                        'name' => $doctor->post_title,
                        'specialty' => $doctor_meta['specialty'],
                        'degree' => $doctor_meta['degree'],
                        'description' => $doctor->post_content,
                        'rating' => $doctor_meta['rating'],
                        'price' => $doctor_meta['price'],
                        'address' => $doctor_meta['address'],
                        'phone' => $doctor_meta['phone'],
                        'whatsapp' => $doctor_meta['whatsapp'],
                        'clinic_id' => $doctor_meta['clinic_id'],
                        'work_schedule' => $doctor_meta['work_schedule'],
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
        <h1><?php echo esc_html__('إدارة الأطباء', 'my-clinic'); ?></h1>
        
        <?php if ($message): ?>
            <div class="notice notice-<?php echo esc_attr($message_type); ?> is-dismissible">
                <p><?php echo wp_kses_post($message); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" action="" enctype="multipart/form-data" class="my-clinic-admin-form">
            <?php wp_nonce_field('add_doctor_action', 'add_doctor_nonce'); ?>
            <?php if ($is_edit): ?>
                <input type="hidden" name="doctor_id" value="<?php echo esc_attr($doctor_id); ?>">
            <?php endif; ?>
            
            <h2><?php echo $is_edit ? esc_html__('تعديل الطبيب', 'my-clinic') : esc_html__('إضافة طبيب جديد', 'my-clinic'); ?></h2>
            
            <?php if ($is_edit): ?>
                <p><a href="<?php echo esc_url(admin_url('admin.php?page=doctors')); ?>" class="button"><?php echo esc_html__('إضافة طبيب جديد', 'my-clinic'); ?></a></p>
            <?php endif; ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row">
                        <label for="doctor_name"><?php echo esc_html__('اسم الطبيب', 'my-clinic'); ?> <span class="required">*</span></label>
                    </th>
                    <td>
                        <input type="text" id="doctor_name" name="doctor_name" class="regular-text" required value="<?php 
                            if (isset($_POST['doctor_name'])) {
                                echo esc_attr($_POST['doctor_name']);
                            } elseif ($is_edit && $doctor_data) {
                                echo esc_attr($doctor_data['name']);
                            }
                        ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="doctor_specialty"><?php echo esc_html__('التخصص', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="doctor_specialty" name="doctor_specialty" class="regular-text" value="<?php 
                            if (isset($_POST['doctor_specialty'])) {
                                echo esc_attr($_POST['doctor_specialty']);
                            } elseif ($is_edit && $doctor_data) {
                                echo esc_attr($doctor_data['specialty']);
                            }
                        ?>" placeholder="<?php echo esc_attr__('مثال: عظام، باطنة، أسنان', 'my-clinic'); ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="doctor_degree"><?php echo esc_html__('الدرجة العلمية', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="doctor_degree" name="doctor_degree" class="regular-text" value="<?php 
                            if (isset($_POST['doctor_degree'])) {
                                echo esc_attr($_POST['doctor_degree']);
                            } elseif ($is_edit && $doctor_data) {
                                echo esc_attr($doctor_data['degree']);
                            }
                        ?>" placeholder="<?php echo esc_attr__('مثال: استاذ جامعي، استشاري', 'my-clinic'); ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="doctor_description"><?php echo esc_html__('الوصف الكامل', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <?php
                        if (isset($_POST['doctor_description'])) {
                            $description = wp_kses_post($_POST['doctor_description']);
                        } elseif ($is_edit && $doctor_data) {
                            $description = $doctor_data['description'];
                        } else {
                            $description = '';
                        }
                        wp_editor($description, 'doctor_description', array(
                            'textarea_name' => 'doctor_description',
                            'textarea_rows' => 10,
                            'media_buttons' => false,
                        ));
                        ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="doctor_rating"><?php echo esc_html__('التقييم', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="doctor_rating" name="doctor_rating" class="regular-text" step="0.1" min="0" max="5" value="<?php 
                            if (isset($_POST['doctor_rating'])) {
                                echo esc_attr($_POST['doctor_rating']);
                            } elseif ($is_edit && $doctor_data) {
                                echo esc_attr($doctor_data['rating']);
                            } else {
                                echo '5';
                            }
                        ?>">
                    </td>
                </tr>


                <tr>
                    <th scope="row">
                        <label for="doctor_price"><?php echo esc_html__('سعر الكشف (ريال)', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="number" id="doctor_price" name="doctor_price" class="regular-text" step="0.01" min="0" value="<?php 
                            if (isset($_POST['doctor_price'])) {
                                echo esc_attr($_POST['doctor_price']);
                            } elseif ($is_edit && $doctor_data) {
                                echo esc_attr($doctor_data['price']);
                            } else {
                                echo '100';
                            }
                        ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="doctor_address"><?php echo esc_html__('العنوان', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="doctor_address" name="doctor_address" class="regular-text" value="<?php 
                            if (isset($_POST['doctor_address'])) {
                                echo esc_attr($_POST['doctor_address']);
                            } elseif ($is_edit && $doctor_data) {
                                echo esc_attr($doctor_data['address']);
                            }
                        ?>" placeholder="<?php echo esc_attr__('مثال: حي العليا، شارع الملك فهد، الرياض', 'my-clinic'); ?>">
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="doctor_phone"><?php echo esc_html__('رقم الهاتف', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="doctor_phone" name="doctor_phone" class="regular-text" value="<?php 
                            if (isset($_POST['doctor_phone'])) {
                                echo esc_attr($_POST['doctor_phone']);
                            } elseif ($is_edit && $doctor_data) {
                                echo esc_attr($doctor_data['phone']);
                            }
                        ?>" placeholder="<?php echo esc_attr__('مثال: +966123456789', 'my-clinic'); ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="doctor_whatsapp"><?php echo esc_html__('رقم الواتساب', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <input type="text" id="doctor_whatsapp" name="doctor_whatsapp" class="regular-text" value="<?php 
                            if (isset($_POST['doctor_whatsapp'])) {
                                echo esc_attr($_POST['doctor_whatsapp']);
                            } elseif ($is_edit && $doctor_data) {
                                echo esc_attr($doctor_data['whatsapp']);
                            }
                        ?>" placeholder="<?php echo esc_attr__('مثال: +966123456789', 'my-clinic'); ?>">
                        <p class="description"><?php echo esc_html__('سيتم استخدام هذا الرقم لعرض زر "احجز عبر الواتساب"', 'my-clinic'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row">
                        <label for="doctor_clinic"><?php echo esc_html__('العيادة', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <?php
                        // Get all clinics
                        $clinics = get_posts(array(
                            'post_type' => 'clinic',
                            'post_status' => 'publish',
                            'posts_per_page' => -1,
                            'orderby' => 'title',
                            'order' => 'ASC',
                        ));
                        if (isset($_POST['doctor_clinic'])) {
                            $selected_clinic = intval($_POST['doctor_clinic']);
                        } elseif ($is_edit && $doctor_data) {
                            $selected_clinic = intval($doctor_data['clinic_id']);
                        } else {
                            $selected_clinic = 0;
                        }
                        ?>
                        <select id="doctor_clinic" name="doctor_clinic" class="regular-text">
                            <option value=""><?php echo esc_html__('-- اختر العيادة --', 'my-clinic'); ?></option>
                            <?php foreach ($clinics as $clinic): ?>
                                <option value="<?php echo esc_attr($clinic->ID); ?>" <?php selected($selected_clinic, $clinic->ID); ?>>
                                    <?php echo esc_html($clinic->post_title); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php echo esc_html__('اختر العيادة التي يعمل بها الطبيب (اختياري)', 'my-clinic'); ?></p>
                    </td>
                </tr>

                <tr>
                    <th scope="row">
                        <label for="doctor_image"><?php echo esc_html__('صورة الطبيب', 'my-clinic'); ?></label>
                    </th>
                    <td>
                        <?php if ($is_edit && $doctor_id): 
                            $current_image = get_the_post_thumbnail_url($doctor_id, 'thumbnail');
                            if ($current_image):
                        ?>
                            <div style="margin-bottom: 10px;">
                                <img src="<?php echo esc_url($current_image); ?>" alt="صورة الطبيب الحالية" style="max-width: 150px; height: auto; border: 1px solid #ddd; padding: 5px;">
                                <p class="description"><?php echo esc_html__('الصورة الحالية', 'my-clinic'); ?></p>
                            </div>
                        <?php endif; endif; ?>
                        <input type="file" id="doctor_image" name="doctor_image" accept="image/*">
                        <p class="description"><?php echo $is_edit ? esc_html__('اختر صورة جديدة للطبيب (اتركه فارغاً للاحتفاظ بالصورة الحالية)', 'my-clinic') : esc_html__('اختر صورة للطبيب', 'my-clinic'); ?></p>
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
                    } elseif ($is_edit && $doctor_data && isset($doctor_data['work_schedule'][$day_key])) {
                        $day_checked = true;
                        $day_from = isset($doctor_data['work_schedule'][$day_key]['from']) ? $doctor_data['work_schedule'][$day_key]['from'] : '10:00';
                        $day_to = isset($doctor_data['work_schedule'][$day_key]['to']) ? $doctor_data['work_schedule'][$day_key]['to'] : '17:00';
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
                <input type="submit" name="add_doctor" class="button button-primary" value="<?php echo $is_edit ? esc_attr__('تحديث الطبيب', 'my-clinic') : esc_attr__('إضافة الطبيب', 'my-clinic'); ?>">
                <?php if ($is_edit): ?>
                    <a href="<?php echo esc_url(admin_url('admin.php?page=doctors-list')); ?>" class="button"><?php echo esc_html__('إلغاء', 'my-clinic'); ?></a>
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
    </style>
    <?php
}

/**
 * Process Add Doctor Form Submission
 */
function my_clinic_process_add_doctor_form() {
    // Validate required fields
    if (empty($_POST['doctor_name'])) {
        return array(
            'success' => false,
            'message' => __('يرجى إدخال اسم الطبيب.', 'my-clinic')
        );
    }

    // Sanitize input
    $doctor_name = sanitize_text_field($_POST['doctor_name']);
    $specialty = isset($_POST['doctor_specialty']) ? sanitize_text_field($_POST['doctor_specialty']) : '';
    $degree = isset($_POST['doctor_degree']) ? sanitize_text_field($_POST['doctor_degree']) : '';
    $description = isset($_POST['doctor_description']) ? wp_kses_post($_POST['doctor_description']) : '';
    $rating = isset($_POST['doctor_rating']) ? floatval($_POST['doctor_rating']) : 5;
    $price = isset($_POST['doctor_price']) ? floatval($_POST['doctor_price']) : 100;
    $address = isset($_POST['doctor_address']) ? sanitize_text_field($_POST['doctor_address']) : '';
    $phone = isset($_POST['doctor_phone']) ? sanitize_text_field($_POST['doctor_phone']) : '';
    $whatsapp = isset($_POST['doctor_whatsapp']) ? sanitize_text_field($_POST['doctor_whatsapp']) : '';
    $clinic_id = isset($_POST['doctor_clinic']) ? intval($_POST['doctor_clinic']) : 0;

    // Create doctor post
    $doctor_data = array(
        'post_title' => $doctor_name,
        'post_status' => 'publish',
        'post_type' => 'doctor',
        'post_content' => $description,
    );

    $doctor_id = wp_insert_post($doctor_data);

    if (is_wp_error($doctor_id)) {
        return array(
            'success' => false,
            'message' => __('حدث خطأ أثناء إنشاء الطبيب: ', 'my-clinic') . $doctor_id->get_error_message()
        );
    }

    // Save custom fields
    update_post_meta($doctor_id, '_doctor_specialty', $specialty);
    update_post_meta($doctor_id, '_doctor_degree', $degree);
    update_post_meta($doctor_id, '_doctor_rating', $rating);
    update_post_meta($doctor_id, '_doctor_price', $price);
    update_post_meta($doctor_id, '_doctor_address', $address);
    update_post_meta($doctor_id, '_doctor_phone', $phone);
    update_post_meta($doctor_id, '_doctor_whatsapp', $whatsapp);
    if ($clinic_id > 0) {
        update_post_meta($doctor_id, '_doctor_clinic_id', $clinic_id);
    } else {
        delete_post_meta($doctor_id, '_doctor_clinic_id');
    }

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
    update_post_meta($doctor_id, '_doctor_work_schedule', $schedule);

    // Handle image upload
    if (!empty($_FILES['doctor_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('doctor_image', $doctor_id);
        
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($doctor_id, $attachment_id);
        }
    }

    my_clinic_sync_doctor_product($doctor_id);

    return array(
        'success' => true,
        'message' => sprintf(__('تم إضافة الطبيب "%s" بنجاح!', 'my-clinic'), $doctor_name),
        'doctor_id' => $doctor_id
    );
}

/**
 * Process Update Doctor Form Submission
 */
function my_clinic_process_update_doctor_form($doctor_id) {
    // Validate required fields
    if (empty($_POST['doctor_name'])) {
        return array(
            'success' => false,
            'message' => __('يرجى إدخال اسم الطبيب.', 'my-clinic')
        );
    }

    // Verify doctor exists and is correct type
    $doctor = get_post($doctor_id);
    if (!$doctor || $doctor->post_type !== 'doctor') {
        return array(
            'success' => false,
            'message' => __('الطبيب غير موجود.', 'my-clinic')
        );
    }

    // Sanitize input
    $doctor_name = sanitize_text_field($_POST['doctor_name']);
    $specialty = isset($_POST['doctor_specialty']) ? sanitize_text_field($_POST['doctor_specialty']) : '';
    $degree = isset($_POST['doctor_degree']) ? sanitize_text_field($_POST['doctor_degree']) : '';
    $description = isset($_POST['doctor_description']) ? wp_kses_post($_POST['doctor_description']) : '';
    $rating = isset($_POST['doctor_rating']) ? floatval($_POST['doctor_rating']) : 5;
    $price = isset($_POST['doctor_price']) ? floatval($_POST['doctor_price']) : 100;
    $address = isset($_POST['doctor_address']) ? sanitize_text_field($_POST['doctor_address']) : '';
    $phone = isset($_POST['doctor_phone']) ? sanitize_text_field($_POST['doctor_phone']) : '';
    $whatsapp = isset($_POST['doctor_whatsapp']) ? sanitize_text_field($_POST['doctor_whatsapp']) : '';
    $clinic_id = isset($_POST['doctor_clinic']) ? intval($_POST['doctor_clinic']) : 0;

    // Update doctor post
    $doctor_data = array(
        'ID' => $doctor_id,
        'post_title' => $doctor_name,
        'post_content' => $description,
    );

    $result = wp_update_post($doctor_data);

    if (is_wp_error($result)) {
        return array(
            'success' => false,
            'message' => __('حدث خطأ أثناء تحديث الطبيب: ', 'my-clinic') . $result->get_error_message()
        );
    }

    // Save custom fields
    update_post_meta($doctor_id, '_doctor_specialty', $specialty);
    update_post_meta($doctor_id, '_doctor_degree', $degree);
    update_post_meta($doctor_id, '_doctor_rating', $rating);
    update_post_meta($doctor_id, '_doctor_price', $price);
    update_post_meta($doctor_id, '_doctor_address', $address);
    update_post_meta($doctor_id, '_doctor_phone', $phone);
    update_post_meta($doctor_id, '_doctor_whatsapp', $whatsapp);
    if ($clinic_id > 0) {
        update_post_meta($doctor_id, '_doctor_clinic_id', $clinic_id);
    } else {
        delete_post_meta($doctor_id, '_doctor_clinic_id');
    }

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
    update_post_meta($doctor_id, '_doctor_work_schedule', $schedule);

    // Handle image upload (only if new image is provided)
    if (!empty($_FILES['doctor_image']['name'])) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('doctor_image', $doctor_id);
        
        if (!is_wp_error($attachment_id)) {
            set_post_thumbnail($doctor_id, $attachment_id);
        }
    }

    my_clinic_sync_doctor_product($doctor_id);

    return array(
        'success' => true,
        'message' => sprintf(__('تم تحديث الطبيب "%s" بنجاح!', 'my-clinic'), $doctor_name),
        'doctor_id' => $doctor_id
    );
}

/**
 * Render Doctors List Page
 */
function my_clinic_render_doctors_list_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('ليس لديك صلاحية للوصول إلى هذه الصفحة.', 'my-clinic'));
    }

    // Handle delete action
    $message = '';
    $message_type = '';

    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['doctor_id'])) {
        if (isset($_GET['_wpnonce']) && wp_verify_nonce($_GET['_wpnonce'], 'delete_doctor_' . $_GET['doctor_id'])) {
            $doctor_id = intval($_GET['doctor_id']);
            $result = wp_delete_post($doctor_id, true);
            
            if ($result) {
                $message = __('تم حذف الطبيب بنجاح!', 'my-clinic');
                $message_type = 'success';
            } else {
                $message = __('حدث خطأ أثناء حذف الطبيب.', 'my-clinic');
                $message_type = 'error';
            }
        } else {
            $message = __('التحقق من الأمان فشل. يرجى المحاولة مرة أخرى.', 'my-clinic');
            $message_type = 'error';
        }
    }

    // Get all doctors
    $doctors = get_posts(array(
        'post_type' => 'doctor',
        'post_status' => 'any',
        'numberposts' => -1,
        'orderby' => 'date',
        'order' => 'DESC',
    ));

    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('قائمة الأطباء', 'my-clinic'); ?></h1>
        
        <?php if ($message): ?>
            <div class="notice notice-<?php echo esc_attr($message_type); ?> is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>

        <div style="margin: 20px 0;">
            <a href="<?php echo esc_url(admin_url('admin.php?page=doctors')); ?>" class="button"><?php echo esc_html__('إضافة طبيب جديد', 'my-clinic'); ?></a>
        </div>

        <?php if (!empty($doctors)): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 80px;"><?php echo esc_html__('الصورة', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('اسم الطبيب', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('التخصص', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('الدرجة العلمية', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('التقييم', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('السعر', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('الحالة', 'my-clinic'); ?></th>
                        <th style="width: 200px;"><?php echo esc_html__('الإجراءات', 'my-clinic'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($doctors as $doctor): 
                        $doctor_id = $doctor->ID;
                        $doctor_meta = my_clinic_get_doctor_meta($doctor_id);
                        $doctor_image = get_the_post_thumbnail_url($doctor_id, 'thumbnail');
                        $edit_url = admin_url('admin.php?page=doctors&doctor_id=' . $doctor_id);
                        $view_url = get_permalink($doctor_id);
                        $delete_url = wp_nonce_url(
                            admin_url('admin.php?page=doctors-list&action=delete&doctor_id=' . $doctor_id),
                            'delete_doctor_' . $doctor_id
                        );
                    ?>
                    <tr>
                        <td>
                            <?php if ($doctor_image): ?>
                                <img src="<?php echo esc_url($doctor_image); ?>" alt="<?php echo esc_attr($doctor->post_title); ?>" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                            <?php else: ?>
                                <span style="display: inline-block; width: 50px; height: 50px; background: #ddd; border-radius: 4px;"></span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?php echo esc_html($doctor->post_title); ?></strong></td>
                        <td><?php echo esc_html($doctor_meta['specialty'] ?: '-'); ?></td>
                        <td><?php echo esc_html($doctor_meta['degree'] ?: '-'); ?></td>
                        <td><?php echo esc_html($doctor_meta['rating']); ?> ⭐</td>
                        <td><?php echo esc_html($doctor_meta['price']); ?> ريال</td>
                        <td>
                            <?php if ($doctor->post_status === 'publish'): ?>
                                <span style="color: #46b450;"><?php echo esc_html__('منشور', 'my-clinic'); ?></span>
                            <?php else: ?>
                                <span style="color: #dc3232;"><?php echo esc_html__('مسودة', 'my-clinic'); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?php echo esc_url($edit_url); ?>" class="button button-small"><?php echo esc_html__('تعديل', 'my-clinic'); ?></a>
                            <a href="<?php echo esc_url($view_url); ?>" class="button button-small" target="_blank"><?php echo esc_html__('عرض', 'my-clinic'); ?></a>
                            <a href="<?php echo esc_url($delete_url); ?>" class="button button-small button-link-delete" onclick="return confirm('<?php echo esc_js(__('هل أنت متأكد من حذف هذا الطبيب؟', 'my-clinic')); ?>');"><?php echo esc_html__('حذف', 'my-clinic'); ?></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p><?php echo esc_html__('لا توجد أطباء مضافين حالياً.', 'my-clinic'); ?></p>
            <p><a href="<?php echo esc_url(admin_url('admin.php?page=doctors')); ?>" class="button button-primary"><?php echo esc_html__('إضافة طبيب جديد', 'my-clinic'); ?></a></p>
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
        /* Ensure actions column is wide enough */
        .wp-list-table th:last-child,
        .wp-list-table td:last-child {
            min-width: 250px !important;
            white-space: nowrap;
        }
        /* Ensure buttons are visible */
        .wp-list-table form {
            display: inline-block !important;
            margin: 0 5px 5px 0 !important;
            vertical-align: top;
        }
        .wp-list-table button {
            display: inline-block !important;
            visibility: visible !important;
            opacity: 1 !important;
        }
    </style>
    <?php
}

/**
 * Render Doctors Reviews Admin Page
 */
function my_clinic_render_doctors_reviews_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('ليس لديك صلاحية للوصول إلى هذه الصفحة.', 'my-clinic'));
    }
    
    // Handle approve/reject/delete actions
    $message = '';
    $message_type = '';
    
    if (isset($_POST['update_review_status']) && wp_verify_nonce($_POST['update_review_status_nonce'], 'update_review_status_action')) {
        $doctor_id = intval($_POST['doctor_id']);
        $review_id = sanitize_text_field($_POST['review_id']);
        $status = sanitize_text_field($_POST['status']);
        
        $result = my_clinic_update_review_status($doctor_id, $review_id, $status);
        
        if ($result['success']) {
            $message = $result['message'];
            $message_type = 'success';
        } else {
            $message = $result['message'];
            $message_type = 'error';
        }
    }
    
    if (isset($_POST['delete_review']) && wp_verify_nonce($_POST['delete_review_nonce'], 'delete_review_action')) {
        $doctor_id = intval($_POST['doctor_id']);
        $review_id = sanitize_text_field($_POST['review_id']);
        
        $result = my_clinic_delete_doctor_review($doctor_id, $review_id);
        
        if ($result['success']) {
            $message = $result['message'];
            $message_type = 'success';
        } else {
            $message = $result['message'];
            $message_type = 'error';
        }
    }
    
    // Get all pending reviews
    $pending_reviews = my_clinic_get_all_pending_reviews();
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('تقييمات الأطباء', 'my-clinic'); ?></h1>
        
        <?php if ($message): ?>
            <div class="notice notice-<?php echo esc_attr($message_type); ?> is-dismissible">
                <p><?php echo esc_html($message); ?></p>
            </div>
        <?php endif; ?>
        
        <h2><?php echo esc_html__('التقييمات المعلقة', 'my-clinic'); ?></h2>
        
        <?php if (empty($pending_reviews)): ?>
            <p><?php echo esc_html__('لا توجد تقييمات معلقة', 'my-clinic'); ?></p>
        <?php else: ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('الطبيب', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('اسم المراجع', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('التقييم', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('التعليق', 'my-clinic'); ?></th>
                        <th><?php echo esc_html__('التاريخ', 'my-clinic'); ?></th>
                        <th style="min-width: 250px;"><?php echo esc_html__('الإجراءات', 'my-clinic'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pending_reviews as $review): 
                        $review_date = date_i18n('d/m/Y H:i', strtotime($review['date']));
                    ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($review['doctor_name']); ?></strong>
                            </td>
                            <td><?php echo esc_html($review['name']); ?></td>
                            <td>
                                <?php 
                                $rating = floatval($review['rating']);
                                echo esc_html($rating);
                                echo ' ';
                                for ($i = 0; $i < 5; $i++): 
                                    if ($i < floor($rating)): ?>
                                        <span style="color: #ffc107;">★</span>
                                    <?php else: ?>
                                        <span style="color: #ccc;">★</span>
                                    <?php endif;
                                endfor; 
                                ?>
                                <br>
                                <small style="color: #666;">
                                    الخدمة: <?php echo esc_html($review['medical_service']); ?> | 
                                    الانتظار: <?php echo esc_html($review['waiting_place']); ?> | 
                                    المساعد: <?php echo esc_html($review['assistant']); ?>
                                </small>
                            </td>
                            <td><?php echo esc_html($review['comment'] ?: '-'); ?></td>
                            <td><?php echo esc_html($review_date); ?></td>
                            <td style="min-width: 250px; white-space: nowrap;">
                                <form method="post" style="display: inline-block !important; margin: 0 5px 5px 0 !important; vertical-align: top;">
                                    <?php wp_nonce_field('update_review_status_action', 'update_review_status_nonce'); ?>
                                    <input type="hidden" name="doctor_id" value="<?php echo esc_attr($review['doctor_id']); ?>">
                                    <input type="hidden" name="review_id" value="<?php echo esc_attr($review['id']); ?>">
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" name="update_review_status" class="button button-primary" style="margin-right: 5px; display: inline-block !important; visibility: visible !important; opacity: 1 !important;">
                                        <?php echo esc_html__('اعتماد', 'my-clinic'); ?>
                                    </button>
                                </form>
                                <form method="post" style="display: inline-block !important; margin: 0 5px 5px 0 !important; vertical-align: top;">
                                    <?php wp_nonce_field('update_review_status_action', 'update_review_status_nonce'); ?>
                                    <input type="hidden" name="doctor_id" value="<?php echo esc_attr($review['doctor_id']); ?>">
                                    <input type="hidden" name="review_id" value="<?php echo esc_attr($review['id']); ?>">
                                    <input type="hidden" name="status" value="rejected">
                                    <button type="submit" name="update_review_status" class="button button-link-delete" onclick="return confirm('<?php echo esc_js(__('هل أنت متأكد من رفض هذا التقييم؟', 'my-clinic')); ?>');" style="margin-right: 5px; display: inline-block !important; visibility: visible !important; opacity: 1 !important;">
                                        <?php echo esc_html__('رفض', 'my-clinic'); ?>
                                    </button>
                                </form>
                                <form method="post" style="display: inline-block !important; margin: 0 5px 5px 0 !important; vertical-align: top;">
                                    <?php wp_nonce_field('delete_review_action', 'delete_review_nonce'); ?>
                                    <input type="hidden" name="doctor_id" value="<?php echo esc_attr($review['doctor_id']); ?>">
                                    <input type="hidden" name="review_id" value="<?php echo esc_attr($review['id']); ?>">
                                    <button type="submit" name="delete_review" class="button button-link-delete" onclick="return confirm('<?php echo esc_js(__('هل أنت متأكد من حذف هذا التقييم؟ لا يمكن التراجع عن هذا الإجراء.', 'my-clinic')); ?>');" style="display: inline-block !important; visibility: visible !important; opacity: 1 !important;">
                                        <?php echo esc_html__('حذف', 'my-clinic'); ?>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    
    <?php
    // Handle bulk update phone and whatsapp
    if (isset($_POST['bulk_update_contacts']) && wp_verify_nonce($_POST['bulk_update_contacts_nonce'], 'bulk_update_contacts_action')) {
        $phone = '+966536781457';
        $whatsapp = '+966536781457';
        
        // Update all doctors
        $doctors = get_posts(array(
            'post_type' => 'doctor',
            'post_status' => 'any',
            'posts_per_page' => -1,
        ));
        
        $doctors_updated = 0;
        foreach ($doctors as $doctor) {
            update_post_meta($doctor->ID, '_doctor_phone', $phone);
            update_post_meta($doctor->ID, '_doctor_whatsapp', $whatsapp);
            $doctors_updated++;
        }
        
        // Update all clinics
        $clinics = get_posts(array(
            'post_type' => 'clinic',
            'post_status' => 'any',
            'posts_per_page' => -1,
        ));
        
        $clinics_updated = 0;
        foreach ($clinics as $clinic) {
            update_post_meta($clinic->ID, '_clinic_phone', $phone);
            update_post_meta($clinic->ID, '_clinic_whatsapp', $whatsapp);
            $clinics_updated++;
        }
        
        echo '<div class="notice notice-success is-dismissible"><p>';
        echo sprintf(__('تم تحديث %d طبيب و %d عيادة برقم الهاتف والواتساب: %s', 'my-clinic'), $doctors_updated, $clinics_updated, $phone);
        echo '</p></div>';
    }
    ?>
    
    <div class="wrap" style="margin-top: 20px;">
        <h2><?php echo esc_html__('تحديث جماعي', 'my-clinic'); ?></h2>
        <form method="post" action="">
            <?php wp_nonce_field('bulk_update_contacts_action', 'bulk_update_contacts_nonce'); ?>
            <p><?php echo esc_html__('سيتم تحديث جميع الأطباء والعيادات برقم الهاتف والواتساب: +966536781457', 'my-clinic'); ?></p>
            <p class="submit">
                <input type="submit" name="bulk_update_contacts" class="button button-primary" value="<?php echo esc_attr__('تحديث جميع الأطباء والعيادات', 'my-clinic'); ?>" onclick="return confirm('<?php echo esc_js(__('هل أنت متأكد من تحديث جميع الأطباء والعيادات برقم الهاتف والواتساب؟', 'my-clinic')); ?>');">
            </p>
        </form>
    </div>
    <?php
}
