<?php get_header(); ?>

<?php
$homepage_settings = function_exists('mykitchen_get_homepage_settings')
    ? mykitchen_get_homepage_settings()
    : array();
$homepage_banners = $homepage_settings['banners'] ?? array();
$homepage_phrases = $homepage_settings['phrases'] ?? array();
$less_than_price = isset($homepage_settings['less_than_price']) ? (float) $homepage_settings['less_than_price'] : 99;
if ($less_than_price <= 0) {
    $less_than_price = 99;
}
$last_chance_title = $homepage_settings['last_chance_title'] ?? 'الفرصة الأخيرة - تصفية حتى نفاذ الكمية !';
$last_chance_product_ids = $homepage_settings['last_chance_product_ids'] ?? array();
$last_chance_product_ids = is_array($last_chance_product_ids) ? array_values(array_filter(array_map('absint', $last_chance_product_ids))) : array();
$less_than_label_value = $less_than_price;
$less_than_label_text = (abs($less_than_label_value - round($less_than_label_value)) < 0.01)
    ? (string) (int) round($less_than_label_value)
    : rtrim(rtrim(number_format($less_than_label_value, 2, '.', ''), '0'), '.');
$primary_banner_id = absint($homepage_banners['primary_id'] ?? 0);
$secondary_banner_id = absint($homepage_banners['secondary_id'] ?? 0);
$primary_banner_url = $homepage_banners['primary'] ?? '';
$secondary_banner_url = $homepage_banners['secondary'] ?? '';
if ($primary_banner_id) {
    $primary_banner_url = wp_get_attachment_url($primary_banner_id) ?: $primary_banner_url;
}
if ($secondary_banner_id) {
    $secondary_banner_url = wp_get_attachment_url($secondary_banner_id) ?: $secondary_banner_url;
}
while (count($homepage_phrases) < 3) {
    $homepage_phrases[] = array('image' => '', 'title' => '', 'text' => '');
}

$assets_uri = defined('MYK_ASSETS_URI') ? MYK_ASSETS_URI : '';
$offers_ids = function_exists('wc_get_product_ids_on_sale') ? wc_get_product_ids_on_sale() : array();

$offers_products = wc_get_products(
    array(
        'include' => array_slice($offers_ids, 0, 8),
        'limit' => 8,
        'status' => 'publish',
    )
);

if (empty($offers_products)) {
    $offers_products = wc_get_products(
        array(
            'limit' => 8,
            'orderby' => 'date',
            'order' => 'DESC',
            'status' => 'publish',
        )
    );
}

$less_than_products = array();
$less_than_query = new WP_Query(
    array(
        'post_type' => 'product',
        'posts_per_page' => 8,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => '_price',
                'value' => $less_than_price,
                'compare' => '<=',
                'type' => 'NUMERIC',
            ),
        ),
    )
);
if (!empty($less_than_query->posts)) {
    $less_than_products = array_values(
        array_filter(
            array_map('wc_get_product', $less_than_query->posts)
        )
    );
}

$last_chance_products = array();
if (!empty($last_chance_product_ids)) {
    $last_chance_products = wc_get_products(
        array(
            'include' => $last_chance_product_ids,
            'orderby' => 'post__in',
            'status' => 'publish',
        )
    );
}
if (empty($last_chance_products)) {
    $last_chance_products = wc_get_products(
        array(
            'limit' => 8,
            'orderby' => 'date',
            'order' => 'DESC',
            'status' => 'publish',
        )
    );
}

$brand_terms = array();
if (taxonomy_exists('product_brand')) {
    $brand_terms = get_terms(
        array(
            'taxonomy' => 'product_brand',
            'hide_empty' => false,
        )
    );
}
?>

<header class="header">
  <div class="ad">
    <?php if (!empty($primary_banner_url)) : ?>
      <img src="<?php echo esc_url($primary_banner_url); ?>" alt="" />
    <?php endif; ?>
    <?php if (!empty($secondary_banner_url)) : ?>
      <img src="<?php echo esc_url($secondary_banner_url); ?>" alt="" />
    <?php endif; ?>
  </div>

  <div class="adv">
    <?php foreach ($homepage_phrases as $index => $phrase) : ?>
      <?php
      $phrase_image_id = absint($phrase['image_id'] ?? 0);
      $phrase_image_url = $phrase['image'] ?? '';
      if ($phrase_image_id) {
          $phrase_image_url = wp_get_attachment_url($phrase_image_id) ?: $phrase_image_url;
      }
      ?>
      <?php if (empty($phrase['title']) && empty($phrase['text']) && empty($phrase_image_url)) : ?>
        <?php continue; ?>
      <?php endif; ?>
      <div class="adv<?php echo esc_attr($index + 1); ?>">
        <?php if (!empty($phrase_image_url)) : ?>
          <img src="<?php echo esc_url($phrase_image_url); ?>" alt="">
        <?php endif; ?>
        <div class="cont">
          <?php if (!empty($phrase['title'])) : ?>
            <h1><?php echo esc_html($phrase['title']); ?></h1>
          <?php endif; ?>
          <?php if (!empty($phrase['text'])) : ?>
            <p><?php echo esc_html($phrase['text']); ?></p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</header>

<main data-y="main">
  <div class="main-container">
    <section class="section-title">اقوى العروض</section>
    <div class="section section2">
      <ul class="products">
        <?php foreach ($offers_products as $product) : ?>
          <?php echo mykitchen_render_product_card($product, array('show_badge' => true)); ?>
        <?php endforeach; ?>
      </ul>
    </div>
    <div class="banner">
      <?php if ($assets_uri) : ?>
        <img src="<?php echo esc_url($assets_uri . '/assets/header3.png'); ?>" alt="" />
        <img src="<?php echo esc_url($assets_uri . '/assets/header4.png'); ?>" alt="" />
      <?php endif; ?>
    </div>

    <section class="section-title">اقل من <?php echo esc_html($less_than_label_text); ?> ريال</section>
    <div class="section section1">
      <ul class="products">
        <?php foreach ($less_than_products as $product) : ?>
          <?php echo mykitchen_render_product_card($product); ?>
        <?php endforeach; ?>
      </ul>
    </div>

    <section class="section-title"><?php echo esc_html($last_chance_title); ?></section>
    <div class="section section2">
      <ul class="products">
        <?php foreach ($last_chance_products as $product) : ?>
          <?php echo mykitchen_render_product_card($product, array('show_badge' => true)); ?>
        <?php endforeach; ?>
      </ul>
    </div>

    <?php if (!empty($brand_terms) && !is_wp_error($brand_terms)) : ?>
      <h2 class="section-title">الماركات</h2>
      <div class="brands" data-y="brands" data-real-brands="1">
        <?php foreach ($brand_terms as $brand) : ?>
          <?php
          $brand_thumb_id = (int) get_term_meta($brand->term_id, 'thumbnail_id', true);
          $brand_img = $brand_thumb_id ? wp_get_attachment_url($brand_thumb_id) : '';
          if (!$brand_img) {
              continue;
          }
          ?>
          <img src="<?php echo esc_url($brand_img); ?>" alt="<?php echo esc_attr($brand->name); ?>">
        <?php endforeach; ?>
      </div>
    <?php else : ?>
      <div class="brands" data-y="brands" data-real-brands="1"></div>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
