<?php
global $product;
if (!isset($product) && in_the_loop()) {
  $product = wc_get_product(get_the_ID());
}
$assets = get_template_directory_uri() . '/mallati/assets';
if (!$product || !is_a($product, 'WC_Product')) {
  return;
}
$permalink = get_permalink();
$thumb = get_the_post_thumbnail_url(get_the_ID(), 'woocommerce_thumbnail');
if (!$thumb) {
  $thumb = $assets . '/cat1.png';
}
$price_html = $product->get_price_html();
$on_sale = $product->is_on_sale();
$discount = '';
if ($on_sale && $product->get_regular_price()) {
  $reg = (float) $product->get_regular_price();
  $sale = (float) $product->get_sale_price();
  if ($reg > 0) {
    $pct = round((($reg - $sale) / $reg) * 100);
    $discount = sprintf(__('خصم %d%%', 'mallati-theme'), $pct);
  }
}
?>
<li class="product-card">
  <a href="<?php echo esc_url($permalink); ?>" class="product-link">
    <div class="product-img">
      <img src="<?php echo esc_url($thumb); ?>" alt="<?php the_title_attribute(); ?>" />
      <?php if ($discount) : ?><div class="discount"><?php echo esc_html($discount); ?></div><?php endif; ?>
    </div>
    <div class="product-content">
      <p class="product-description"><?php echo wp_kses_post($product->get_short_description() ?: $product->get_name()); ?></p>
      <p class="product-price"><?php echo $price_html; ?></p>
    </div>
  </a>
  <div class="product-actions">
    <?php
    if ($product->is_type('simple') && $product->is_purchasable()) {
      echo '<form class="cart" action="' . esc_url($product->add_to_cart_url()) . '" method="post">';
      echo '<input type="hidden" name="quantity" value="1" />';
      echo '<button type="submit" name="add-to-cart" value="' . esc_attr($product->get_id()) . '" class="y-c-btn y-c-btn--primary product-add-to-cart"><span class="add-to-cart-text">' . esc_html__('أضف إلى السلة', 'mallati-theme') . '</span> <img src="' . esc_url($assets) . '/cart-details.svg" alt="" /></button>';
      echo '</form>';
    } else {
      echo '<a href="' . esc_url($permalink) . '" class="y-c-btn y-c-btn--primary product-add-to-cart"><span class="add-to-cart-text">' . esc_html__('عرض المنتج', 'mallati-theme') . '</span> <img src="' . esc_url($assets) . '/cart-details.svg" alt="" /></a>';
    }
    ?>
    <?php
    $pid = $product->get_id();
    $fav_ids = is_user_logged_in() ? (array) get_user_meta(get_current_user_id(), 'mallati_favourites', true) : array();
    $in_fav = in_array($pid, array_map('intval', $fav_ids));
    ?>
    <label class="product-add-to-wishlist" data-product-id="<?php echo esc_attr($pid); ?>">
      <input type="checkbox" class="wishlist-checkbox" <?php checked($in_fav); ?> />
      <i class="<?php echo $in_fav ? 'fas' : 'far'; ?> fa-heart"></i>
    </label>
  </div>
</li>
