<?php
/**
 * My Account navigation - مطابقة تصميم مولاتي
 */
defined('ABSPATH') || exit;

$all = wc_get_account_menu_items();
$icons = array(
    'dashboard'       => 'fa-user-circle',
    'orders'          => 'fa-shopping-cart',
    'edit-address'    => 'fa-map-marker-alt',
    'customer-logout' => 'fa-sign-out-alt',
);
$labels = array(
    'dashboard'       => __('حسابي', 'mallati-theme'),
    'orders'          => __('طلباتي', 'mallati-theme'),
    'edit-address'    => __('عنواني', 'mallati-theme'),
    'customer-logout' => __('تسجيل الخروج', 'mallati-theme'),
);
$order = array('dashboard', 'orders', 'edit-address', 'customer-logout');
$items = array();
foreach ($order as $ep) {
    if (isset($all[$ep])) {
        $items[$ep] = isset($labels[$ep]) ? $labels[$ep] : $all[$ep];
    }
}
?>
<aside class="profile-sidebar">
  <nav class="profile-nav">
    <ul>
      <?php foreach ($items as $endpoint => $label) : ?>
        <?php
        $icon = isset($icons[$endpoint]) ? $icons[$endpoint] : 'fa-circle';
        $is_logout = 'customer-logout' === $endpoint;
        $classes = array('profile-nav__link');
        if (wc_is_current_account_menu_item($endpoint)) {
            $classes[] = 'active';
        }
        if ($is_logout) {
            $classes[] = 'profile-nav__logout';
        }
        ?>
        <li>
          <?php if ($is_logout) : ?>
            <a href="<?php echo esc_url(wc_logout_url()); ?>" class="<?php echo esc_attr(implode(' ', $classes)); ?>">
              <span class="profile-nav__icon"><i class="fas <?php echo esc_attr($icon); ?>"></i></span>
              <span><?php echo esc_html($label); ?></span>
            </a>
          <?php else : ?>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" class="<?php echo esc_attr(implode(' ', $classes)); ?>" <?php echo wc_is_current_account_menu_item($endpoint) ? 'aria-current="page"' : ''; ?>>
              <span class="profile-nav__icon"><i class="fas <?php echo esc_attr($icon); ?>"></i></span>
              <span><?php echo esc_html($label); ?></span>
            </a>
          <?php endif; ?>
        </li>
      <?php endforeach; ?>
    </ul>
  </nav>
</aside>
