<?php
$assets = get_template_directory_uri() . '/mallati/assets';
$shop = function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : home_url('/');
$terms = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0, 'number' => 6));
$cats = !empty($terms) && !is_wp_error($terms) ? $terms : array();
$fallback_imgs = array('cat1.png', 'cat2.png', 'cat3.png', 'cat4.png', 'cat5.png');
?>
<div class="categories-slider">
  <?php
  $i = 0;
  foreach ($cats as $cat) :
    $link = get_term_link($cat);
    if (is_wp_error($link)) continue;
    $thumb_id = get_term_meta($cat->term_id, 'thumbnail_id', true);
    $img_url = $thumb_id ? wp_get_attachment_image_url($thumb_id, 'medium') : null;
    if (!$img_url) $img_url = $assets . '/' . $fallback_imgs[$i % count($fallback_imgs)];
    ?>
    <a href="<?php echo esc_url($link); ?>" class="y-u-flex y-u-flex-col y-u-justify-center y-u-items-center">
      <img src="<?php echo esc_url($img_url); ?>" alt="<?php echo esc_attr($cat->name); ?>" onerror="this.src='<?php echo esc_url($assets . '/' . $fallback_imgs[$i % count($fallback_imgs)]); ?>'" />
      <p><?php echo esc_html($cat->name); ?></p>
    </a>
  <?php
    $i++;
  endforeach;
  if (empty($cats)) : ?>
    <a href="<?php echo esc_url($shop); ?>" class="y-u-flex y-u-flex-col y-u-justify-center y-u-items-center">
      <img src="<?php echo esc_url($assets); ?>/cat1.png" alt="" onerror="this.style.display='none'" />
      <p><?php esc_html_e('المتجر', 'mallati-theme'); ?></p>
    </a>
  <?php endif; ?>
</div>
<div class="indecators">
  <button class="indecator"><img src="<?php echo esc_url($assets); ?>/arrow-right.svg" alt="" /></button>
  <button class="indecator"><img src="<?php echo esc_url($assets); ?>/arrow-left.svg" alt="" /></button>
</div>
