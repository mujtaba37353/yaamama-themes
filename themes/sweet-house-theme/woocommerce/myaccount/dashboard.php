<?php
/**
 * My Account Dashboard — تفاصيل الحساب (الصفحة الرئيسية، تحتوي على نموذج التفاصيل)
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * عرض نموذج تفاصيل الحساب مباشرة في الصفحة الرئيسية
 */
wc_get_template( 'myaccount/form-edit-account.php', array( 'user' => $current_user ) );
