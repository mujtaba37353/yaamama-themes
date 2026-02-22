<?php
/**
 * Edit address endpoint controller (shipping only).
 */
defined('ABSPATH') || exit;

$load_address = get_query_var('edit-address');
$request_path = trim((string) parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH), '/');
$is_list_view = $request_path === 'account/edit-address';

if (!$load_address || $is_list_view) {
    wc_get_template('myaccount/my-address.php');
    return;
}

if ($load_address === 'billing') {
    $load_address = 'shipping';
}

wc_get_template('myaccount/form-edit-address.php', [
    'load_address' => $load_address,
]);
