<?php
/**
 * Doctor Reviews Functions
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get all reviews for a doctor
 *
 * @param int $doctor_id Doctor post ID
 * @param string $status Review status ('approved', 'pending', 'rejected', or 'all')
 * @return array Array of reviews
 */
function my_clinic_get_doctor_reviews($doctor_id, $status = 'approved') {
    $reviews = get_post_meta($doctor_id, '_doctor_reviews', true);
    if (!is_array($reviews)) {
        return array();
    }
    
    // Filter by status if not 'all'
    if ($status !== 'all') {
        $reviews = array_filter($reviews, function($review) use ($status) {
            // Old reviews without status are considered approved for backward compatibility
            $review_status = isset($review['status']) ? $review['status'] : 'approved';
            return $review_status === $status;
        });
    }
    
    // Sort by date (newest first)
    usort($reviews, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    return array_values($reviews); // Re-index array
}

/**
 * Add a new review for a doctor
 *
 * @param int $doctor_id Doctor post ID
 * @param array $review_data Review data (name, rating, comment, sub_ratings)
 * @return array Result array with success status and message
 */
function my_clinic_add_doctor_review($doctor_id, $review_data) {
    // Validate doctor exists
    if (get_post_type($doctor_id) !== 'doctor') {
        return array(
            'success' => false,
            'message' => __('الطبيب غير موجود', 'my-clinic')
        );
    }
    
    // Sanitize and validate data
    $name = isset($review_data['name']) ? sanitize_text_field($review_data['name']) : '';
    $rating = isset($review_data['rating']) ? floatval($review_data['rating']) : 0;
    $comment = isset($review_data['comment']) ? sanitize_textarea_field($review_data['comment']) : '';
    $medical_service = isset($review_data['medical_service']) ? floatval($review_data['medical_service']) : $rating;
    $waiting_place = isset($review_data['waiting_place']) ? floatval($review_data['waiting_place']) : $rating;
    $assistant = isset($review_data['assistant']) ? floatval($review_data['assistant']) : $rating;
    
    // Validate rating
    if ($rating < 1 || $rating > 5) {
        return array(
            'success' => false,
            'message' => __('التقييم يجب أن يكون بين 1 و 5', 'my-clinic')
        );
    }
    
    if (empty($name)) {
        return array(
            'success' => false,
            'message' => __('الرجاء إدخال الاسم', 'my-clinic')
        );
    }
    
    // Get all reviews (including pending)
    $all_reviews = get_post_meta($doctor_id, '_doctor_reviews', true);
    if (!is_array($all_reviews)) {
        $all_reviews = array();
    }
    
    // Create new review with pending status
    $new_review = array(
        'id' => uniqid('review_'),
        'name' => $name,
        'rating' => $rating,
        'comment' => $comment,
        'medical_service' => $medical_service,
        'waiting_place' => $waiting_place,
        'assistant' => $assistant,
        'date' => current_time('mysql'),
        'status' => 'pending', // New reviews need admin approval
    );
    
    // Add review
    $all_reviews[] = $new_review;
    
    // Save reviews
    update_post_meta($doctor_id, '_doctor_reviews', $all_reviews);
    
    // Don't update rating until approved
    // my_clinic_update_doctor_rating($doctor_id);
    
    return array(
        'success' => true,
        'message' => __('تم إرسال التقييم بنجاح. سيتم مراجعته من قبل المدير قبل النشر.', 'my-clinic'),
        'review' => $new_review
    );
}

/**
 * Calculate and update doctor's average rating
 *
 * @param int $doctor_id Doctor post ID
 */
function my_clinic_update_doctor_rating($doctor_id) {
    // Only use approved reviews for rating calculation
    $reviews = my_clinic_get_doctor_reviews($doctor_id, 'approved');
    
    if (empty($reviews)) {
        // If no approved reviews, keep the default rating
        return;
    }
    
    $total_rating = 0;
    $total_medical = 0;
    $total_waiting = 0;
    $total_assistant = 0;
    $count = 0;
    
    foreach ($reviews as $review) {
        // Only count approved reviews (or old reviews without status)
        $review_status = isset($review['status']) ? $review['status'] : 'approved';
        if ($review_status === 'approved') {
            $total_rating += floatval($review['rating']);
            $total_medical += floatval($review['medical_service']);
            $total_waiting += floatval($review['waiting_place']);
            $total_assistant += floatval($review['assistant']);
            $count++;
        }
    }
    
    if ($count === 0) {
        return;
    }
    
    // Calculate averages
    $avg_rating = round($total_rating / $count, 1);
    $avg_medical = round($total_medical / $count, 1);
    $avg_waiting = round($total_waiting / $count, 1);
    $avg_assistant = round($total_assistant / $count, 1);
    
    // Update doctor meta
    update_post_meta($doctor_id, '_doctor_rating', $avg_rating);
    update_post_meta($doctor_id, '_doctor_rating_medical', $avg_medical);
    update_post_meta($doctor_id, '_doctor_rating_waiting', $avg_waiting);
    update_post_meta($doctor_id, '_doctor_rating_assistant', $avg_assistant);
}

/**
 * Get doctor's average ratings
 *
 * @param int $doctor_id Doctor post ID
 * @return array Array with average ratings
 */
function my_clinic_get_doctor_average_ratings($doctor_id) {
    // Only count approved reviews
    $reviews = my_clinic_get_doctor_reviews($doctor_id, 'approved');
    $count = count($reviews);
    
    if ($count === 0) {
        return array(
            'overall' => get_post_meta($doctor_id, '_doctor_rating', true) ?: 5,
            'medical_service' => get_post_meta($doctor_id, '_doctor_rating_medical', true) ?: 5,
            'waiting_place' => get_post_meta($doctor_id, '_doctor_rating_waiting', true) ?: 4.5,
            'assistant' => get_post_meta($doctor_id, '_doctor_rating_assistant', true) ?: 4,
            'count' => 0
        );
    }
    
    return array(
        'overall' => get_post_meta($doctor_id, '_doctor_rating', true) ?: 5,
        'medical_service' => get_post_meta($doctor_id, '_doctor_rating_medical', true) ?: 5,
        'waiting_place' => get_post_meta($doctor_id, '_doctor_rating_waiting', true) ?: 4.5,
        'assistant' => get_post_meta($doctor_id, '_doctor_rating_assistant', true) ?: 4,
        'count' => $count
    );
}

/**
 * Handle AJAX request to add a review
 */
function my_clinic_ajax_add_review() {
    // Verify nonce
    $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : (isset($_POST['review_nonce']) ? $_POST['review_nonce'] : '');
    if (empty($nonce) || !wp_verify_nonce($nonce, 'add_doctor_review')) {
        wp_send_json_error(array('message' => __('خطأ في التحقق من الأمان', 'my-clinic')));
    }
    
    $doctor_id = isset($_POST['doctor_id']) ? intval($_POST['doctor_id']) : 0;
    
    if (!$doctor_id) {
        wp_send_json_error(array('message' => __('معرف الطبيب غير صحيح', 'my-clinic')));
    }
    
    $review_data = array(
        'name' => isset($_POST['review_name']) ? sanitize_text_field($_POST['review_name']) : '',
        'rating' => isset($_POST['review_rating']) ? floatval($_POST['review_rating']) : 0,
        'comment' => isset($_POST['review_comment']) ? sanitize_textarea_field($_POST['review_comment']) : '',
        'medical_service' => isset($_POST['review_medical_service']) ? floatval($_POST['review_medical_service']) : 0,
        'waiting_place' => isset($_POST['review_waiting_place']) ? floatval($_POST['review_waiting_place']) : 0,
        'assistant' => isset($_POST['review_assistant']) ? floatval($_POST['review_assistant']) : 0,
    );
    
    $result = my_clinic_add_doctor_review($doctor_id, $review_data);
    
    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}
add_action('wp_ajax_add_doctor_review', 'my_clinic_ajax_add_review');
add_action('wp_ajax_nopriv_add_doctor_review', 'my_clinic_ajax_add_review');

/**
 * Approve or reject a review
 *
 * @param int $doctor_id Doctor post ID
 * @param string $review_id Review ID
 * @param string $status New status ('approved' or 'rejected')
 * @return array Result array
 */
function my_clinic_update_review_status($doctor_id, $review_id, $status) {
    if (!in_array($status, array('approved', 'rejected'))) {
        return array(
            'success' => false,
            'message' => __('حالة غير صحيحة', 'my-clinic')
        );
    }
    
    $all_reviews = get_post_meta($doctor_id, '_doctor_reviews', true);
    if (!is_array($all_reviews)) {
        return array(
            'success' => false,
            'message' => __('التقييم غير موجود', 'my-clinic')
        );
    }
    
    $found = false;
    foreach ($all_reviews as $key => $review) {
        if (isset($review['id']) && $review['id'] === $review_id) {
            $all_reviews[$key]['status'] = $status;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        return array(
            'success' => false,
            'message' => __('التقييم غير موجود', 'my-clinic')
        );
    }
    
    update_post_meta($doctor_id, '_doctor_reviews', $all_reviews);
    
    // Update doctor rating if approved
    if ($status === 'approved') {
        my_clinic_update_doctor_rating($doctor_id);
    }
    
    return array(
        'success' => true,
        'message' => $status === 'approved' ? __('تم اعتماد التقييم', 'my-clinic') : __('تم رفض التقييم', 'my-clinic')
    );
}

/**
 * Delete a review
 *
 * @param int $doctor_id Doctor post ID
 * @param string $review_id Review ID
 * @return array Result array
 */
function my_clinic_delete_doctor_review($doctor_id, $review_id) {
    $all_reviews = get_post_meta($doctor_id, '_doctor_reviews', true);
    if (!is_array($all_reviews)) {
        return array(
            'success' => false,
            'message' => __('التقييم غير موجود', 'my-clinic')
        );
    }
    
    $found = false;
    $review_status = '';
    foreach ($all_reviews as $key => $review) {
        if (isset($review['id']) && $review['id'] === $review_id) {
            $review_status = isset($review['status']) ? $review['status'] : 'approved';
            unset($all_reviews[$key]);
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        return array(
            'success' => false,
            'message' => __('التقييم غير موجود', 'my-clinic')
        );
    }
    
    // Re-index array
    $all_reviews = array_values($all_reviews);
    update_post_meta($doctor_id, '_doctor_reviews', $all_reviews);
    
    // Update doctor rating if the deleted review was approved
    if ($review_status === 'approved') {
        my_clinic_update_doctor_rating($doctor_id);
    }
    
    return array(
        'success' => true,
        'message' => __('تم حذف التقييم بنجاح', 'my-clinic')
    );
}

/**
 * Get all pending reviews from all doctors
 *
 * @return array Array of pending reviews with doctor info
 */
function my_clinic_get_all_pending_reviews() {
    $doctors = get_posts(array(
        'post_type' => 'doctor',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    ));
    
    $pending_reviews = array();
    
    foreach ($doctors as $doctor) {
        $reviews = my_clinic_get_doctor_reviews($doctor->ID, 'pending');
        foreach ($reviews as $review) {
            $review['doctor_id'] = $doctor->ID;
            $review['doctor_name'] = $doctor->post_title;
            $pending_reviews[] = $review;
        }
    }
    
    // Sort by date (newest first)
    usort($pending_reviews, function($a, $b) {
        return strtotime($b['date']) - strtotime($a['date']);
    });
    
    return $pending_reviews;
}

/**
 * Handle AJAX request to approve/reject review
 */
function my_clinic_ajax_update_review_status() {
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('ليس لديك صلاحية', 'my-clinic')));
    }
    
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'update_review_status')) {
        wp_send_json_error(array('message' => __('خطأ في التحقق من الأمان', 'my-clinic')));
    }
    
    $doctor_id = isset($_POST['doctor_id']) ? intval($_POST['doctor_id']) : 0;
    $review_id = isset($_POST['review_id']) ? sanitize_text_field($_POST['review_id']) : '';
    $status = isset($_POST['status']) ? sanitize_text_field($_POST['status']) : '';
    
    if (!$doctor_id || !$review_id || !$status) {
        wp_send_json_error(array('message' => __('بيانات غير كاملة', 'my-clinic')));
    }
    
    $result = my_clinic_update_review_status($doctor_id, $review_id, $status);
    
    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}
add_action('wp_ajax_update_review_status', 'my_clinic_ajax_update_review_status');
