<?php
defined('ABSPATH') || exit;
$assets = get_template_directory_uri() . '/mallati/assets';
$current_cat = is_product_category() ? get_queried_object() : null;
$title = $current_cat && isset($current_cat->name) ? $current_cat->name : __('المتجر', 'mallati-theme');
$base_url = $current_cat ? get_term_link($current_cat) : wc_get_page_permalink('shop');
if (is_wp_error($base_url)) $base_url = wc_get_page_permalink('shop');
$orderby = isset($_GET['orderby']) ? sanitize_text_field(wp_unslash($_GET['orderby'])) : 'menu_order';
$order_options = array(
    'menu_order'   => __('مقترحاتنا', 'mallati-theme'),
    'popularity'   => __('الأكثر مبيعا', 'mallati-theme'),
    'rating'       => __('الأعلى تقييما', 'mallati-theme'),
    'price-desc'   => __('السعر من الأعلى إلى الأقل', 'mallati-theme'),
    'price'        => __('السعر من الأقل إلى الأعلى', 'mallati-theme'),
);
$categories = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => $current_cat ? $current_cat->term_id : 0, 'number' => 20));
if (is_wp_error($categories)) $categories = array();
$brand_terms = array();
if (taxonomy_exists('pa_brand')) {
    $brand_terms = get_terms(array('taxonomy' => 'pa_brand', 'hide_empty' => true, 'number' => 20));
    if (is_wp_error($brand_terms)) $brand_terms = array();
}
?>
<div class="filtration">
  <h2 class="y-u-m-0 y-u-text-xl y-u-text-bold y-u-color-muted"><?php echo esc_html($title); ?></h2>
  <div class="selects">
    <div class="filtration-button">
      <button type="button" aria-haspopup="true" aria-expanded="false">
        <?php esc_html_e('تصنيفة المنتجات', 'mallati-theme'); ?>
        <img src="<?php echo esc_url($assets . '/arrow-right.svg'); ?>" alt="" />
      </button>
      <div class="filtration-menu filter-menu-wide" role="menu">
        <?php if (!empty($categories)) : ?>
        <details class="filter-group">
          <summary>
            <span><?php esc_html_e('فئات المنتجات', 'mallati-theme'); ?></span>
            <div class="toggle-icon">
              <i class="fas fa-plus"></i>
              <i class="fas fa-minus"></i>
            </div>
          </summary>
          <div class="filter-content">
            <?php foreach ($categories as $cat) :
              $cat_url = get_term_link($cat);
              if (is_wp_error($cat_url)) continue;
              $active = $current_cat && $current_cat->term_id === $cat->term_id;
              ?>
              <a href="<?php echo esc_url($cat_url); ?>" class="filter-row" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:8px;padding:8px 0;">
                <span><?php echo esc_html($cat->name); ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        </details>
        <?php endif; ?>
        <?php if (!empty($brand_terms)) : ?>
        <details class="filter-group" open>
          <summary>
            <span><?php esc_html_e('الماركات', 'mallati-theme'); ?></span>
            <div class="toggle-icon">
              <i class="fas fa-plus"></i>
              <i class="fas fa-minus"></i>
            </div>
          </summary>
          <div class="filter-content">
            <?php foreach ($brand_terms as $brand) :
              $brand_url = add_query_arg(array('filter_' . sanitize_title($brand->taxonomy) => $brand->slug), $base_url);
              ?>
              <a href="<?php echo esc_url($brand_url); ?>" class="filter-row" style="text-decoration:none;color:inherit;display:flex;align-items:center;gap:8px;padding:8px 0;">
                <span><?php echo esc_html($brand->name); ?></span>
              </a>
            <?php endforeach; ?>
          </div>
        </details>
        <?php endif; ?>
      </div>
    </div>
    <div class="filtration-button">
      <button type="button" aria-haspopup="true" aria-expanded="false">
        <?php esc_html_e('ترتيب حسب', 'mallati-theme'); ?>
        <img src="<?php echo esc_url($assets . '/arrow-right.svg'); ?>" alt="" />
      </button>
      <div class="filtration-menu" role="menu">
        <?php foreach ($order_options as $value => $label) :
          $url = add_query_arg('orderby', $value, $base_url);
          $active_class = $orderby === $value ? ' filtration-option--active' : '';
          ?>
          <a href="<?php echo esc_url($url); ?>" class="filtration-option<?php echo esc_attr($active_class); ?>" style="text-decoration:none;color:inherit;">
            <?php echo esc_html($label); ?>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>
