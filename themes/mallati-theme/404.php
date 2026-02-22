<?php
get_header();
$assets = get_template_directory_uri() . '/mallati/assets';
?>
<main>
  <section class="container y-u-w-full y-u-flex y-u-justify-center">
    <div class="y-u-max-w-600 y-u-w-full y-u-py-56">
      <img src="<?php echo esc_url($assets); ?>/404.png" alt="404" onerror="this.style.display='none'" />
      <h1 class="y-u-text-3xl y-u-text-bold y-u-color-muted y-u-text-center"><?php esc_html_e('الصفحة غير موجودة', 'mallati-theme'); ?></h1>
      <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-primary y-u-mt-16 y-c-btn y-c-btn--primary y-u-flex y-u-justify-center y-u-max-w-fit"><?php esc_html_e('الرئيسية', 'mallati-theme'); ?></a>
    </div>
  </section>
</main>
<?php get_footer(); ?>
