<?php
/**
 * My Account page - Custom Template
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

/**
 * My Account navigation.
 */
do_action('woocommerce_account_navigation');
?>

<div class="woocommerce-MyAccount-content y-c-myaccount-content">
    <?php
        /**
         * My Account content.
         */
        do_action('woocommerce_account_content');
    ?>
</div>
