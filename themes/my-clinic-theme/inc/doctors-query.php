<?php
/**
 * Functions to query and display doctors and clinics from database
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get doctors from database
 *
 * @param array $args Query arguments
 * @return array Array of doctor posts
 */
function my_clinic_get_doctors($args = array()) {
    // Get paged from args first, then from $_GET, then default to 1
    if (isset($args['paged'])) {
        $paged = max(1, intval($args['paged']));
    } elseif (isset($_GET['paged'])) {
        $paged = max(1, intval($_GET['paged']));
    } else {
        $paged = 1;
    }
    
    $defaults = array(
        'post_type' => 'doctor',
        'post_status' => 'publish',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $paged,
    );

    $query_args = wp_parse_args($args, $defaults);
    
    // Ensure paged is set correctly after merge
    $query_args['paged'] = $paged;
    
    // Use WP_Query for proper pagination support
    $query = new WP_Query($query_args);
    $doctors = $query->posts;

    return $doctors;
}

/**
 * Get total doctors count
 *
 * @return int Total number of doctors
 */
function my_clinic_get_doctors_count() {
    $count = wp_count_posts('doctor');
    return isset($count->publish) ? (int) $count->publish : 0;
}

/**
 * Get clinics from database
 *
 * @param array $args Query arguments
 * @return array Array of clinic posts
 */
function my_clinic_get_clinics($args = array()) {
    // Get paged from args first, then from $_GET, then default to 1
    if (isset($args['paged'])) {
        $paged = max(1, intval($args['paged']));
    } elseif (isset($_GET['paged'])) {
        $paged = max(1, intval($_GET['paged']));
    } else {
        $paged = 1;
    }
    
    $defaults = array(
        'post_type' => 'clinic',
        'post_status' => 'publish',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
        'paged' => $paged,
    );

    $query_args = wp_parse_args($args, $defaults);
    
    // Ensure paged is set correctly after merge
    $query_args['paged'] = $paged;
    
    // Use WP_Query for proper pagination support
    $query = new WP_Query($query_args);
    $clinics = $query->posts;

    return $clinics;
}

/**
 * Get total clinics count
 *
 * @return int Total number of clinics
 */
function my_clinic_get_clinics_count() {
    $count = wp_count_posts('clinic');
    return isset($count->publish) ? (int) $count->publish : 0;
}

/**
 * Get doctor meta data
 *
 * @param int $doctor_id Doctor post ID
 * @return array Doctor meta data
 */
function my_clinic_get_doctor_meta($doctor_id) {
    return array(
        'specialty' => get_post_meta($doctor_id, '_doctor_specialty', true),
        'degree' => get_post_meta($doctor_id, '_doctor_degree', true),
        'rating' => get_post_meta($doctor_id, '_doctor_rating', true) ?: 5,
        'views' => get_post_meta($doctor_id, '_doctor_views', true) ?: 0,
        'price' => get_post_meta($doctor_id, '_doctor_price', true) ?: 100,
        'address' => get_post_meta($doctor_id, '_doctor_address', true),
        'phone' => get_post_meta($doctor_id, '_doctor_phone', true),
        'whatsapp' => get_post_meta($doctor_id, '_doctor_whatsapp', true),
        'clinic_id' => get_post_meta($doctor_id, '_doctor_clinic_id', true),
        'work_schedule' => get_post_meta($doctor_id, '_doctor_work_schedule', true) ?: array(),
    );
}

/**
 * Get clinic meta data
 *
 * @param int $clinic_id Clinic post ID
 * @return array Clinic meta data
 */
function my_clinic_get_clinic_meta($clinic_id) {
    return array(
        'address' => get_post_meta($clinic_id, '_clinic_address', true),
        'phone' => get_post_meta($clinic_id, '_clinic_phone', true),
        'whatsapp' => get_post_meta($clinic_id, '_clinic_whatsapp', true),
        'rating' => get_post_meta($clinic_id, '_clinic_rating', true) ?: 5,
        'price' => get_post_meta($clinic_id, '_clinic_price', true) ?: 100,
        'views' => get_post_meta($clinic_id, '_clinic_views', true) ?: 0,
        'specialty' => get_post_meta($clinic_id, '_clinic_specialty', true),
        'specialties_count' => get_post_meta($clinic_id, '_clinic_specialties_count', true) ?: 0,
        'doctors_count' => get_post_meta($clinic_id, '_clinic_doctors_count', true) ?: 0,
        'work_schedule' => get_post_meta($clinic_id, '_clinic_work_schedule', true) ?: array(),
        'features' => get_post_meta($clinic_id, '_clinic_features', true) ?: array(),
    );
}

/**
 * Get all unique specialties from doctors
 *
 * @return array Array of unique specialty names
 */
function my_clinic_get_all_specialties() {
    global $wpdb;
    
    $specialties = $wpdb->get_col(
        "SELECT DISTINCT meta_value 
         FROM {$wpdb->postmeta} 
         WHERE meta_key = '_doctor_specialty' 
         AND meta_value != '' 
         AND meta_value IS NOT NULL
         ORDER BY meta_value ASC"
    );
    
    return $specialties ?: array();
}

/**
 * Get unique specialties from a list of doctors
 *
 * @param array $doctors Array of doctor post objects
 * @return array Array of unique specialty names
 */
function my_clinic_get_specialties_from_doctors($doctors) {
    $specialties = array();
    
    if (empty($doctors)) {
        return $specialties;
    }
    
    foreach ($doctors as $doctor) {
        $doctor_id = is_object($doctor) ? $doctor->ID : $doctor;
        $specialty = get_post_meta($doctor_id, '_doctor_specialty', true);
        
        if ($specialty && !empty($specialty) && !in_array($specialty, $specialties)) {
            $specialties[] = $specialty;
        }
    }
    
    sort($specialties);
    return $specialties;
}

/**
 * Format work schedule for display
 *
 * @param array $schedule Work schedule array
 * @return string Formatted schedule string
 */
function my_clinic_format_work_schedule($schedule) {
    if (empty($schedule)) {
        return '';
    }

    $days_map = array(
        'sunday' => 'الأحد',
        'monday' => 'الاثنين',
        'tuesday' => 'الثلاثاء',
        'wednesday' => 'الأربعاء',
        'thursday' => 'الخميس',
        'friday' => 'الجمعة',
        'saturday' => 'السبت',
    );

    $today = strtolower(date('l'));
    $today_key = '';
    switch ($today) {
        case 'sunday':
            $today_key = 'sunday';
            break;
        case 'monday':
            $today_key = 'monday';
            break;
        case 'tuesday':
            $today_key = 'tuesday';
            break;
        case 'wednesday':
            $today_key = 'wednesday';
            break;
        case 'thursday':
            $today_key = 'thursday';
            break;
        case 'friday':
            $today_key = 'friday';
            break;
        case 'saturday':
            $today_key = 'saturday';
            break;
    }

    if (isset($schedule[$today_key])) {
        $from = $schedule[$today_key]['from'];
        $to = $schedule[$today_key]['to'];
        return sprintf('%s - %s', $from, $to);
    }

    return '';
}
