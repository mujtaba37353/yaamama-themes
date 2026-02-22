<?php
defined('ABSPATH') || exit;
get_header();
$assets = get_template_directory_uri() . '/mallati/assets';
$shop_id = wc_get_page_id('shop');
$shop_url = get_permalink($shop_id);
$current_term = is_product_category() ? get_queried_object() : null;
?>
<main>
  <section class="container y-u-max-w-1200 y-u-flex y-u-flex-col y-u-justify-center hero-section">
    <p class="y-u-text-2xl y-u-text-bold y-u-mb-16 y-u-color-muted breadcrumb">
      <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('الرئيسية', 'mallati-theme'); ?></a> &gt;
      <a href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('المتجر', 'mallati-theme'); ?></a>
      <?php if ($current_term && isset($current_term->name)) : ?>
        &gt; <?php echo esc_html($current_term->name); ?>
      <?php endif; ?>
    </p>
    <?php get_template_part('parts/hero-slider'); ?>
  </section>
  <section class="container y-u-w-full categories-section">
    <?php get_template_part('parts/categories-slider'); ?>
  </section>
  <section class="container y-u-w-full categories-section">
    <?php do_action('woocommerce_before_shop_loop'); ?>
    <?php get_template_part('parts/shop-filters'); ?>
    <?php if (woocommerce_product_loop()) : ?>
      <ul class="products-grid">
        <?php
        while (have_posts()) {
          the_post();
          wc_get_template_part('content', 'product');
        }
        ?>
      </ul>
      <?php woocommerce_pagination(); ?>
    <?php else : ?>
      <p class="y-u-text-center y-u-color-muted"><?php esc_html_e('لا توجد منتجات', 'mallati-theme'); ?></p>
    <?php endif; ?>
  </section>
</main>
<?php get_footer(); ?>
