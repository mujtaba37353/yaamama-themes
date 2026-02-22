<?php
/*
Template Name: Account
*/

get_header();
if (!is_user_logged_in()) {
    wp_safe_redirect(ahmadi_theme_page_url('login'));
    exit;
}
echo do_shortcode('[woocommerce_my_account]');
get_footer();
