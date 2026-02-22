<?php
get_header();
?>
<main>
  <section class="container y-u-max-w-1200 y-u-flex y-u-justify-center y-u-flex-col hero-section">
    <?php get_template_part('parts/hero-slider'); ?>
  </section>
  <section class="container y-u-w-full categories-section">
    <?php get_template_part('parts/categories-slider'); ?>
  </section>
  <?php if (have_posts()) : ?>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
      <h2 class="y-u-text-3xl y-u-text-bold y-u-mb-16 y-u-color-muted"><?php esc_html_e('المنتجات', 'mallati-theme'); ?></h2>
      <ul class="products-grid">
        <?php
        while (have_posts()) {
          the_post();
          get_template_part('parts/product-card');
        }
        ?>
      </ul>
    </section>
  <?php else : ?>
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
      <p class="y-u-text-center y-u-color-muted"><?php esc_html_e('لا توجد منتجات', 'mallati-theme'); ?></p>
    </section>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
