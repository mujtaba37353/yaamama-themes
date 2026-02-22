<?php
defined('ABSPATH') || exit;
global $product;
$assets = get_template_directory_uri() . '/mallati/assets';

do_action('woocommerce_before_single_product');
if (post_password_required()) {
    echo get_the_password_form();
    return;
}

$terms = get_the_terms($product->get_id(), 'product_cat');
$cat_name = $terms && !is_wp_error($terms) && !empty($terms) ? $terms[0]->name : '';
$shop_url = wc_get_page_permalink('shop');
$cat_url = $terms && !is_wp_error($terms) && !empty($terms) ? get_term_link($terms[0]) : $shop_url;
?>
<section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
  <div class="y-u-max-w-1200 y-u-w-full pd-page">
    <div class="header y-u-flex y-u-justify-between y-u-items-center y-u-py-32 y-u-p-t-24">
      <p class="y-u-text-2xl y-u-text-bold y-u-mb-16 y-u-color-muted breadcrumb">
        <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('الرئيسية', 'mallati-theme'); ?></a> &gt;
        <a href="<?php echo esc_url($shop_url); ?>"><?php esc_html_e('المتجر', 'mallati-theme'); ?></a> &gt;
        <?php if ($cat_name) : ?><a href="<?php echo esc_url($cat_url); ?>"><?php echo esc_html($cat_name); ?></a> &gt; <?php endif; ?>
        <?php the_title(); ?>
      </p>
    </div>

    <div class="pd-grid">
      <div class="pd-gallery">
        <div class="top">
          <?php
          $pid = $product->get_id();
          $fav_ids = is_user_logged_in() ? (array) get_user_meta(get_current_user_id(), 'mallati_favourites', true) : array();
          $in_fav = in_array($pid, array_map('intval', $fav_ids));
          ?>
          <label class="product-add-to-wishlist" data-product-id="<?php echo esc_attr($pid); ?>">
            <input type="checkbox" class="wishlist-checkbox" <?php checked($in_fav); ?> />
            <i class="<?php echo $in_fav ? 'fas' : 'far'; ?> fa-heart"></i>
          </label>
          <?php if ($product->is_on_sale()) : ?>
            <?php
            $reg = (float) $product->get_regular_price();
            $sale = (float) $product->get_sale_price();
            $pct = $reg > 0 ? round((($reg - $sale) / $reg) * 100) : 0;
            ?>
            <div class="discount"><?php printf(esc_html__('خصم %d%%', 'mallati-theme'), $pct); ?></div>
          <?php endif; ?>
        </div>
        <div class="pd-main-img">
          <?php echo $product->get_image('woocommerce_single'); ?>
        </div>
      </div>

      <div class="pd-info">
        <h2 class="pd-brand"><?php echo esc_html($cat_name); ?></h2>
        <p class="pd-title y-u-text-muted"><?php echo wp_kses_post($product->get_short_description() ?: $product->get_name()); ?></p>
        <div class="pd-price">
          <span class="pd-price-label"><?php esc_html_e('السعر', 'mallati-theme'); ?></span>
          <span class="pd-price-value"><?php echo $product->get_price_html(); ?></span>
        </div>
        <div class="pd-actions">
          <?php woocommerce_template_single_add_to_cart(); ?>
          <?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
            <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="y-c-btn y-c-btn--primary"><?php esc_html_e('اشترى الان', 'mallati-theme'); ?> <img src="<?php echo esc_url($assets); ?>/buy-details.svg" alt="" onerror="this.style.display='none'" /></a>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="pd-content">
      <?php woocommerce_output_product_data_tabs(); ?>
    </div>

    <?php if ($product->get_reviews_allowed()) : ?>
    <div class="rating">
      <div class="pd-meta">
        <h2 class="reviews-title"><?php esc_html_e('التقييمات', 'mallati-theme'); ?></h2>
        <?php woocommerce_template_single_rating(); ?>
      </div>
    </div>
    <?php endif; ?>
  </div>
</section>

<?php woocommerce_output_related_products(); ?>

<?php do_action('woocommerce_after_single_product'); ?>
