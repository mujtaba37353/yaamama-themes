<?php
/**
 * Template Name: قائمة المفضلة
 */
get_header();
$assets = get_template_directory_uri() . '/mallati/assets';

$fav_ids = array();
if (is_user_logged_in()) {
    $fav_ids = get_user_meta(get_current_user_id(), 'mallati_favourites', true);
    $fav_ids = is_array($fav_ids) ? $fav_ids : array();
}
$fav_ids = array_filter(array_map('absint', $fav_ids));
$has_items = !empty($fav_ids);
?>
<main>
  <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
    <div class="y-u-max-w-1200 y-u-w-full fav-page">
      <div class="header y-u-flex y-u-flex-col y-u-py-32 y-u-p-t-24">
        <h1 class="y-u-color-muted y-u-text-2xl"><?php esc_html_e('قائمة المفضلة', 'mallati-theme'); ?></h1>
        <p class="y-u-text-2xl y-u-text-bold y-u-mb-16 y-u-color-muted breadcrumb">
          <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('الرئيسية', 'mallati-theme'); ?></a> &gt; <?php esc_html_e('قائمة المفضلة', 'mallati-theme'); ?>
        </p>
      </div>

      <?php if ($has_items) : ?>
        <ul class="products-grid">
          <?php
          global $post, $product;
          foreach ($fav_ids as $pid) {
            $product = wc_get_product($pid);
            if ($product && $product->exists()) {
              $post = get_post($pid);
              setup_postdata($post);
              get_template_part('parts/product-card');
            }
          }
          wp_reset_postdata();
          ?>
        </ul>
      <?php else : ?>
        <div class="empty-state">
          <i class="fa-solid fa-heart"></i>
          <h3><?php esc_html_e('لا توجد عناصر', 'mallati-theme'); ?></h3>
          <p><?php esc_html_e('لم تقم بإضافة أي منتجات إلى قائمة المفضلة الخاصة بك.', 'mallati-theme'); ?><br><?php esc_html_e('تصفح المنتجات وأضفها إلى قائمتك لحفظها هنا.', 'mallati-theme'); ?></p>
          <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn-empty-action"><?php esc_html_e('عودة للتسوق', 'mallati-theme'); ?></a>
        </div>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
