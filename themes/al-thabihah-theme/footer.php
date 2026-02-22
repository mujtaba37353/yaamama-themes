<?php
if (!defined('ABSPATH')) {
    exit;
}
$footer_settings = wp_parse_args(al_thabihah_get_option('al_thabihah_footer_settings', array()), al_thabihah_default_footer_settings());
$footer_logo_url = $footer_settings['footer_logo_id'] ? wp_get_attachment_url($footer_settings['footer_logo_id']) : al_thabihah_asset_uri('al-thabihah/assets/logo.png');
?>

<footer class="y-l-site-footer" data-y="footer">
    <div class="y-l-footer-container" data-y="footer-container">

        <div class="y-c-footer-logo-container">
            <img src="<?php echo esc_url($footer_logo_url); ?>" alt="Al Thabihah Logo" class="y-c-footer-logo" loading="lazy">
        </div>

        <div class="y-l-footer-main" data-y="footer-main-content">

            <div class="y-l-footer-column y-l-footer-description-col" data-y="footer-description-column">
                <div class="y-c-footer-description">
                    <?php echo wp_kses_post($footer_settings['description']); ?>
                </div>
            </div>

            <div class="y-l-footer-links-wrapper">
                <div class="y-l-footer-column y-l-footer-links-col" data-y="footer-pages-column">
                    <h4 class="y-c-column-title" data-y="footer-pages-title">الصفحات</h4>
                    <ul class="y-l-footer-links" data-y="footer-pages-links">
                        <li><a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a></li>
                        <li><a href="<?php echo esc_url(al_thabihah_get_page_link('offers')); ?>">العروض</a></li>
                        <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">جميع المنتجات</a></li>
                        <li><a href="<?php echo esc_url(al_thabihah_get_page_link('about-us')); ?>">من نحن</a></li>
                        <li><a href="<?php echo esc_url(al_thabihah_get_page_link('contact-us')); ?>">تواصل معنا</a></li>
                    </ul>
                </div>
                <div class="y-l-footer-column y-l-footer-links-col" data-y="footer-policies-column">
                    <h4 class="y-c-column-title" data-y="footer-policies-title">السياسات</h4>
                    <ul class="y-l-footer-links y-l-footer-policies" data-y="footer-policies-links">
                        <li><a href="<?php echo esc_url(al_thabihah_get_page_link('privacy-policy')); ?>">سياسة الخصوصية</a></li>
                        <li><a href="<?php echo esc_url(al_thabihah_get_page_link('replacement-policy')); ?>">سياسة الاسترجاع</a></li>
                        <li><a href="<?php echo esc_url(al_thabihah_get_page_link('delivery-policy')); ?>">سياسة الشحن</a></li>
                    </ul>
                </div>
            </div>

            <div class="y-l-footer-column y-l-footer-contact-col" data-y="footer-contact-column">
                <h4 class="y-c-column-title" data-y="footer-contact-title">تواصل معنا</h4>
                <ul class="y-l-footer-contact-links" data-y="footer-contact-links">
                    <li><i class="fas fa-map-marker-alt"></i><span><?php echo esc_html($footer_settings['address']); ?></span></li>
                    <li><i class="fas fa-envelope"></i><a href="mailto:<?php echo esc_attr($footer_settings['email']); ?>"><?php echo esc_html($footer_settings['email']); ?></a>
                    </li>
                    <li><i class="fas fa-phone"></i><a href="tel:<?php echo esc_attr($footer_settings['phone']); ?>" dir="ltr"><?php echo esc_html($footer_settings['phone']); ?></a></li>
                    <?php if (!empty($footer_settings['whatsapp'])) : ?>
                        <li><i class="fab fa-whatsapp"></i><a href="https://wa.me/<?php echo esc_attr(preg_replace('/\D+/', '', $footer_settings['whatsapp'])); ?>" target="_blank" rel="noopener">واتساب</a></li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>

        <div class="y-l-footer-copyright" data-y="footer-copyright">
            <p>
                جميع الحقوق محفوظة لـ Yamama Solutions &copy;
            </p>
        </div>

    </div>
</footer>

<?php if (!empty($footer_settings['floating_enabled'])) : ?>
    <div class="y-c-floating-actions">
        <?php if (!empty($footer_settings['floating_phone'])) : ?>
            <a href="tel:<?php echo esc_attr($footer_settings['floating_phone']); ?>">
                <i class="fas fa-phone"></i>
                اتصل بنا
            </a>
        <?php endif; ?>
        <?php if (!empty($footer_settings['floating_whatsapp'])) : ?>
            <a href="https://wa.me/<?php echo esc_attr(preg_replace('/\D+/', '', $footer_settings['floating_whatsapp'])); ?>" target="_blank" rel="noopener">
                <i class="fab fa-whatsapp"></i>
                واتساب
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
