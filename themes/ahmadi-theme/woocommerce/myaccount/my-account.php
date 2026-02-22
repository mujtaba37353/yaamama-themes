<?php

defined('ABSPATH') || exit;
?>
<section class="y-c-container">
    <h1 class="y-c-header-title">حسابي</h1>
    <section class="y-c-Profile-container">
        <div class="y-c-Profile-menu">
            <?php woocommerce_account_navigation(); ?>
        </div>
        <div class="y-c-Profile-info">
            <?php woocommerce_account_content(); ?>
        </div>
    </section>
</section>
