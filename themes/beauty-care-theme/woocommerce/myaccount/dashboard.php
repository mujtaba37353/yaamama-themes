<?php
/**
 * My Account Dashboard — Beauty Care: show edit account form
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$user = get_user_by( 'id', get_current_user_id() );
wc_get_template( 'myaccount/form-edit-account.php', array( 'user' => $user ) );
