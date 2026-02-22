<?php
if (is_user_logged_in()) {
    wp_logout();
}

wp_safe_redirect(home_url('/login/'));
exit;
