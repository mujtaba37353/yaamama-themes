<?php
/**
 * Create/update WooCommerce products for doctors and clinics.
 * Product name = doctor/clinic name, price = consultation price (سعر الكشف).
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Create or update WooCommerce product for a doctor.
 * Called after add/update doctor. Stores product ID in _doctor_product_id.
 *
 * @param int $doctor_id Doctor post ID
 * @return int|false Product ID on success, false on failure
 */
function my_clinic_sync_doctor_product($doctor_id) {
    if (!function_exists('wc_get_product') || !class_exists('WC_Product_Simple')) {
        return false;
    }

    $post = get_post($doctor_id);
    if (!$post || $post->post_type !== 'doctor') {
        return false;
    }

    $title = $post->post_title;
    $price = (float) (get_post_meta($doctor_id, '_doctor_price', true) ?: 100);
    $product_id = (int) get_post_meta($doctor_id, '_doctor_product_id', true);

    return my_clinic_sync_booking_product('doctor', $doctor_id, $title, $price, $product_id, '_doctor_product_id');
}

/**
 * Create or update WooCommerce product for a clinic.
 * Called after add/update clinic. Stores product ID in _clinic_product_id.
 *
 * @param int $clinic_id Clinic post ID
 * @return int|false Product ID on success, false on failure
 */
function my_clinic_sync_clinic_product($clinic_id) {
    if (!function_exists('wc_get_product_object') || !class_exists('WC_Product_Simple')) {
        return false;
    }

    $post = get_post($clinic_id);
    if (!$post || $post->post_type !== 'clinic') {
        return false;
    }

    $title = $post->post_title;
    $price = (float) (get_post_meta($clinic_id, '_clinic_price', true) ?: 100);
    $product_id = (int) get_post_meta($clinic_id, '_clinic_product_id', true);

    return my_clinic_sync_booking_product('clinic', $clinic_id, $title, $price, $product_id, '_clinic_product_id');
}

/**
 * Create or update a single WooCommerce product for booking (doctor or clinic).
 *
 * @param string $source       'doctor' or 'clinic'
 * @param int    $source_id    Doctor or clinic post ID
 * @param string $title        Product name
 * @param float  $price        Product price
 * @param int    $product_id   Existing product ID (0 to create new)
 * @param string $meta_key     Meta key to store product ID on source post
 * @return int|false Product ID on success, false on failure
 */
function my_clinic_sync_booking_product($source, $source_id, $title, $price, $product_id, $meta_key) {
    $product = $product_id > 0 ? wc_get_product($product_id) : null;

    if ($product && $product->exists()) {
        $product->set_name($title);
        $product->set_regular_price((string) $price);
        $product->save();
        return (int) $product->get_id();
    }

    $product = new WC_Product_Simple();
    $product->set_name($title);
    $product->set_regular_price((string) $price);
    $product->set_status('publish');
    $product->set_catalog_visibility('hidden');
    $product->set_virtual(true);
    $product->save();

    $new_id = (int) $product->get_id();
    if ($new_id > 0 && $source_id > 0) {
        update_post_meta($source_id, $meta_key, $new_id);
        update_post_meta($new_id, '_booking_source', $source);
        update_post_meta($new_id, '_booking_source_id', $source_id);
    }

    return $new_id > 0 ? $new_id : false;
}
