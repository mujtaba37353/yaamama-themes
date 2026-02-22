<?php
/**
 * Beauty Care Theme — تحميل وحدات إدارة المحتوى.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$inc_dir = get_template_directory() . '/inc';

require_once $inc_dir . '/admin-pages.php';
require_once $inc_dir . '/populate-policy-pages.php';
require_once $inc_dir . '/admin-demo-products.php';
require_once $inc_dir . '/admin-contact.php';
require_once $inc_dir . '/admin-footer.php';
require_once $inc_dir . '/admin-homepage.php';
require_once $inc_dir . '/admin-about.php';
require_once $inc_dir . '/admin-policy-privacy.php';
require_once $inc_dir . '/admin-policy-return.php';
require_once $inc_dir . '/admin-policy-shipping.php';
require_once $inc_dir . '/admin-site-settings.php';
require_once $inc_dir . '/wishlist.php';
