<?php
get_header();
$banner_id = get_option('mallati_home_banner', 0);
$banner_url = $banner_id ? wp_get_attachment_image_url($banner_id, 'full') : (get_template_directory_uri() . '/mallati/assets/hero1.png');
?>
<main>
  <section class="container y-u-max-w-1200 y-u-flex y-u-justify-center y-u-flex-col hero-section">
    <?php get_template_part('parts/hero-slider'); ?>
  </section>
  <section class="container y-u-w-full categories-section">
    <?php get_template_part('parts/categories-slider'); ?>
  </section>
  <section class="container y-u-max-w-1200 y-u-flex y-u-justify-center y-u-flex-col hero-section">
    <img src="<?php echo esc_url($banner_url); ?>" alt="" class="y-u-w-full" onerror="this.src='<?php echo esc_url(get_template_directory_uri() . '/mallati/assets/hero1.png'); ?>'" />
  </section>
  <?php
  $best_selling = new WP_Query(array(
    'post_type' => 'product',
    'posts_per_page' => 8,
    'meta_key' => 'total_sales',
    'orderby' => 'meta_value_num',
    'order' => 'DESC',
  ));
  if (!$best_selling->have_posts()) {
    $best_selling = new WP_Query(array(
      'post_type' => 'product',
      'posts_per_page' => 8,
      'orderby' => 'date',
      'order' => 'DESC',
    ));
  }
  if ($best_selling->have_posts()) :
  ?>
  <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
    <div class="y-u-max-w-1200 y-u-mb-32">
      <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php echo esc_html(get_option('mallati_home_title_best', __('الاكثر مبيعا:', 'mallati-theme'))); ?></h2>
    </div>
    <ul class="products-grid">
      <?php
      $best_ids = array();
      while ($best_selling->have_posts()) :
        $best_selling->the_post();
        $best_ids[] = get_the_ID();
        get_template_part('parts/product-card');
      endwhile;
      wp_reset_postdata();
      ?>
    </ul>
    <div class="indecators">
      <button class="indecator"><img src="<?php echo esc_url(get_template_directory_uri() . '/mallati/assets/arrow-right.svg'); ?>" alt="" /></button>
      <button class="indecator"><img src="<?php echo esc_url(get_template_directory_uri() . '/mallati/assets/arrow-left.svg'); ?>" alt="" /></button>
    </div>
  </section>
  <?php endif; ?>
  <section class="container y-u-max-w-1200 y-u-flex y-u-justify-center y-u-flex-col hero-section">
    <img src="<?php echo esc_url($banner_url); ?>" alt="" class="y-u-w-full" onerror="this.src='<?php echo esc_url(get_template_directory_uri() . '/mallati/assets/hero1.png'); ?>'" />
  </section>
  <?php
  if (!isset($best_ids)) $best_ids = array();
  $recent = new WP_Query(array(
    'post_type' => 'product',
    'posts_per_page' => 8,
    'orderby' => 'date',
    'order' => 'DESC',
    'post__not_in' => $best_ids,
  ));
  if (!$recent->have_posts()) {
    $recent = new WP_Query(array('post_type' => 'product', 'posts_per_page' => 8, 'orderby' => 'date', 'order' => 'DESC'));
  }
  if ($recent->have_posts()) :
  ?>
  <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
    <div class="y-u-max-w-1200 y-u-mb-32">
      <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php echo esc_html(get_option('mallati_home_title_new', __('وصل حديثا:', 'mallati-theme'))); ?></h2>
    </div>
    <ul class="products-grid">
      <?php while ($recent->have_posts()) : $recent->the_post(); get_template_part('parts/product-card'); endwhile; wp_reset_postdata(); ?>
    </ul>
    <div class="indecators">
      <button class="indecator"><img src="<?php echo esc_url(get_template_directory_uri() . '/mallati/assets/arrow-right.svg'); ?>" alt="" /></button>
      <button class="indecator"><img src="<?php echo esc_url(get_template_directory_uri() . '/mallati/assets/arrow-left.svg'); ?>" alt="" /></button>
    </div>
  </section>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
