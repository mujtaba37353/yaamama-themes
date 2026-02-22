<?php
defined('ABSPATH') || exit;
?>
<section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
  <div class="y-u-max-w-1200 y-u-w-full">
    <div class="header y-u-flex y-u-flex-col y-u-py-32 y-u-p-t-24">
      <h1 class="y-u-color-muted y-u-text-2xl"><?php esc_html_e('حسابي', 'mallati-theme'); ?></h1>
      <p class="y-u-text-2xl y-u-text-bold y-u-mb-16 y-u-color-muted breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('الرئيسية', 'mallati-theme'); ?></a> &gt; <?php esc_html_e('حسابي', 'mallati-theme'); ?>
      </p>
    </div>
    <div class="profile-layout profile-tabs woocommerce-MyAccount-wrapper">
      <?php do_action('woocommerce_account_navigation'); ?>
      <div class="woocommerce-MyAccount-content profile-panels">
        <?php do_action('woocommerce_account_content'); ?>
      </div>
    </div>
  </div>
</section>
