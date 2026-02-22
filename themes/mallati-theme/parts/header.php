<?php
$assets = get_template_directory_uri() . '/mallati/assets';
$logo_id = get_theme_mod('mallati_logo_id', 0) ?: get_theme_mod('mallati_footer_logo', 0);
$logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : $assets . '/logo.png';
$home = home_url('/');
$shop = function_exists('wc_get_page_id') ? get_permalink(wc_get_page_id('shop')) : $home;
$cart = function_exists('wc_get_cart_url') ? wc_get_cart_url() : $home;
$account = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : $home;
$fav_page = ($fav_p = get_page_by_path('favourites')) ? get_permalink($fav_p) : $home;
$cats = get_terms(array('taxonomy' => 'product_cat', 'hide_empty' => true, 'parent' => 0, 'number' => 12));
if (empty($cats) || is_wp_error($cats)) $cats = array();
?>
<header class="y-u-flex-col y-u-fixed header y-u-top-left">
  <div class="container y-u-flex y-u-justify-between y-u-items-center y-u-w-full y-u-max-w-1200 y-u-py-8">
    <div class="logo y-u-flex y-u-justify-start y-u-items-center">
      <a href="<?php echo esc_url($home); ?>">
        <img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" onerror="this.src='<?php echo esc_url($assets); ?>/logo.png'" />
      </a>
      <?php if (!function_exists('is_account_page') || !is_account_page()) : ?>
      <form role="search" method="get" class="desktop-menu y-u-flex y-u-justify-start y-u-items-center y-u-gap-8" action="<?php echo esc_url($shop); ?>">
        <img src="<?php echo esc_url($assets); ?>/search.svg" alt="" />
        <input type="search" name="s" placeholder="<?php esc_attr_e('ابحث عن منتج', 'mallati-theme'); ?>" value="<?php echo esc_attr(get_search_query()); ?>" />
        <input type="hidden" name="post_type" value="product" />
      </form>
      <?php endif; ?>
    </div>

    <button class="mobile-menu-btn y-u-flex-col y-u-justify-between" aria-label="<?php esc_attr_e('فتح القائمة', 'mallati-theme'); ?>">
      <span></span>
      <span></span>
      <span></span>
    </button>
    <div class="user-nav y-u-flex y-u-justify-end y-u-items-center">
      <?php if (is_user_logged_in()) : ?>
        <a href="<?php echo esc_url($account); ?>">
          <?php esc_html_e('حسابي', 'mallati-theme'); ?>
          <img src="<?php echo esc_url($assets); ?>/profile.svg" alt="" />
        </a>
      <?php else : ?>
        <a href="<?php echo esc_url($account); ?>">
          <?php esc_html_e('تسجيل الدخول', 'mallati-theme'); ?>
          <img src="<?php echo esc_url($assets); ?>/profile.svg" alt="" />
        </a>
      <?php endif; ?>
      <a href="<?php echo esc_url($fav_page); ?>">
        <img src="<?php echo esc_url($assets); ?>/heart.svg" alt="<?php esc_attr_e('المفضلة', 'mallati-theme'); ?>" />
      </a>
      <a href="<?php echo esc_url($cart); ?>" class="cart-link">
        <img src="<?php echo esc_url($assets); ?>/cart.svg" alt="<?php esc_attr_e('السلة', 'mallati-theme'); ?>" />
        <?php if (function_exists('WC') && WC()->cart) : ?>
          <span class="cart-count" data-cart-count="<?php echo absint(WC()->cart->get_cart_contents_count()); ?>"><?php echo absint(WC()->cart->get_cart_contents_count()); ?></span>
        <?php endif; ?>
      </a>
    </div>
  </div>

  <div class="mobile-menu-overlay">
    <nav class="mobile-menu">
      <ul class="y-u-flex y-u-flex-col y-u-gap-16">
        <li><a href="<?php echo esc_url($home); ?>" class="category-link"><?php esc_html_e('الرئيسية', 'mallati-theme'); ?></a></li>
        <li><a href="<?php echo esc_url($shop); ?>" class="category-link"><?php esc_html_e('المتجر', 'mallati-theme'); ?></a></li>
        <?php foreach ($cats as $cat) :
          $link = get_term_link($cat);
          if (is_wp_error($link)) continue;
          ?>
          <li><a href="<?php echo esc_url($link); ?>" class="category-link"><?php echo esc_html($cat->name); ?></a></li>
        <?php endforeach; ?>
        <li class="mobile-user-links y-u-flex y-u-justify-between y-u-items-center">
          <a href="<?php echo esc_url($account); ?>"><img src="<?php echo esc_url($assets); ?>/profile.svg" alt="" /></a>
          <a href="<?php echo esc_url($fav_page); ?>"><img src="<?php echo esc_url($assets); ?>/heart.svg" alt="" /></a>
          <a href="<?php echo esc_url($cart); ?>"><img src="<?php echo esc_url($assets); ?>/cart.svg" alt="" /></a>
        </li>
      </ul>
    </nav>
  </div>
  <div class="y-u-flex y-u-justify-between y-u-items-center y-u-w-full top-header y-u-py-16">
    <ul class="y-u-flex y-u-justify-between y-u-items-center container y-u-max-w-1200">
      <li><a href="<?php echo esc_url($home); ?>" class="category-link<?php echo is_front_page() ? ' active' : ''; ?>"><?php esc_html_e('الرئيسية', 'mallati-theme'); ?></a></li>
      <li><a href="<?php echo esc_url($shop); ?>" class="category-link<?php echo is_shop() ? ' active' : ''; ?>"><?php esc_html_e('المتجر', 'mallati-theme'); ?></a></li>
      <?php foreach ($cats as $cat) :
        $link = get_term_link($cat);
        if (is_wp_error($link)) continue;
        $active = is_product_category($cat->term_id) ? ' active' : '';
        ?>
        <li><a href="<?php echo esc_url($link); ?>" class="category-link<?php echo $active; ?>"><?php echo esc_html($cat->name); ?></a></li>
      <?php endforeach; ?>
    </ul>
  </div>
</header>
