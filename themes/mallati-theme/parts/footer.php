<?php
$assets = get_template_directory_uri() . '/mallati/assets';
$logo_id = get_theme_mod('mallati_logo_id', 0) ?: get_theme_mod('mallati_footer_logo', 0);
$logo_url = $logo_id ? wp_get_attachment_image_url($logo_id, 'medium') : $assets . '/logo.png';
$home = home_url('/');
$account = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : $home;
$orders = function_exists('wc_get_endpoint_url') ? wc_get_endpoint_url('orders', '', $account) : ($account . '#orders');
$get_page = function ($slugs) use ($home) {
  foreach ((array) $slugs as $slug) {
    $p = get_page_by_path($slug);
    if ($p) return get_permalink($p);
  }
  return $home;
};
$contact = $get_page(array('contact-us'));
$about = $get_page(array('about-us'));
$user_policy = $get_page(array('user-polices', 'usage-policy'));
$privacy = $get_page(array('privacy-policy', 'privacy-polices'));
if ($privacy === $home && get_privacy_policy_url()) $privacy = get_privacy_policy_url();
$return_policy = $get_page(array('return-policy', 'exange-polices', 'exchange-policy'));
$shipping = $get_page(array('shipping-policy', 'shipping'));
?>
<footer class="footer">
  <div class="container y-u-max-w-1200">
    <div class="footer-links">
      <ul class="footer-col-policies">
        <li><a href="<?php echo esc_url($shipping); ?>"><?php esc_html_e('سياسة الشحن', 'mallati-theme'); ?></a></li>
        <li><a href="<?php echo esc_url($return_policy); ?>"><?php esc_html_e('سياسة الاسترجاع', 'mallati-theme'); ?></a></li>
        <li><a href="<?php echo esc_url($privacy); ?>"><?php esc_html_e('سياسة الخصوصية', 'mallati-theme'); ?></a></li>
        <li><a href="<?php echo esc_url($user_policy); ?>"><?php esc_html_e('سياسة الاستخدام', 'mallati-theme'); ?></a></li>
      </ul>
      <ul class="footer-col-account">
        <li><a href="<?php echo esc_url($home); ?>"><?php esc_html_e('الرئيسية', 'mallati-theme'); ?></a></li>
        <li><a href="<?php echo esc_url($account); ?>"><?php esc_html_e('حسابي', 'mallati-theme'); ?></a></li>
        <li><a href="<?php echo esc_url($orders); ?>"><?php esc_html_e('طلباتي', 'mallati-theme'); ?></a></li>
      </ul>
      <ul class="footer-col-info">
        <li><a href="<?php echo esc_url($contact); ?>"><?php esc_html_e('تواصل معنا', 'mallati-theme'); ?></a></li>
        <li><a href="<?php echo esc_url($about); ?>"><?php esc_html_e('من نحن', 'mallati-theme'); ?></a></li>
      </ul>
    </div>
    <div class="logo">
      <a href="<?php echo esc_url($home); ?>"><img src="<?php echo esc_url($logo_url); ?>" alt="<?php bloginfo('name'); ?>" onerror="this.src='<?php echo esc_url($assets); ?>/logo.png'" /></a>
      <p><?php echo esc_html(get_theme_mod('mallati_footer_copyright', sprintf(__('جميع الحقوق محفوظة لـ %s © %d', 'mallati-theme'), get_bloginfo('name'), (int) date('Y')))); ?></p>
    </div>
  </div>
</footer>
<?php if (get_theme_mod('mallati_floating_buttons', 0)) :
  $footer_phone = get_theme_mod('mallati_footer_phone', get_theme_mod('mallati_phone', '+966 50 000 0000'));
  $footer_whatsapp = get_theme_mod('mallati_footer_whatsapp', '');
  $phone_link = 'tel:' . preg_replace('/\s+/', '', $footer_phone);
  $wa_num = $footer_whatsapp ? preg_replace('/\D/', '', $footer_whatsapp) : preg_replace('/\D/', '', $footer_phone);
  $wa_num = (substr($wa_num, 0, 1) === '0' ? '966' . ltrim($wa_num, '0') : ($wa_num ?: '966500000000'));
  $wa_link = 'https://wa.me/' . $wa_num;
?>
<div class="mallati-floating-buttons">
  <a href="<?php echo esc_url($phone_link); ?>" class="mallati-float-btn mallati-float-phone" aria-label="<?php esc_attr_e('اتصال', 'mallati-theme'); ?>"><i class="fas fa-phone"></i></a>
  <a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener" class="mallati-float-btn mallati-float-whatsapp" aria-label="<?php esc_attr_e('واتساب', 'mallati-theme'); ?>"><i class="fab fa-whatsapp"></i></a>
</div>
<style>.mallati-floating-buttons{position:fixed;bottom:24px;left:24px;z-index:9999;display:flex;flex-direction:column;gap:12px}.mallati-float-btn{width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:22px;box-shadow:0 4px 12px rgba(0,0,0,.2);transition:transform .2s}.mallati-float-btn:hover{color:#fff;transform:scale(1.05)}.mallati-float-phone{background:#f7931e}.mallati-float-whatsapp{background:#25d366}</style>
<?php endif; ?>
