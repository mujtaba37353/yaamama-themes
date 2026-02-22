<?php
/**
 * The footer template
 *
 * @package MyCarTheme
 */
?>

<footer class="y-l-site-footer" data-y="footer">
    <div class="y-l-footer-container" data-y="footer-container">
        <div class="y-l-footer-grid">
            <div class="y-l-footer-brand">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-footer-brand-link">
                    <?php
                    if (has_custom_logo()) {
                        $logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image_src($logo_id, 'full');
                        if ($logo) {
                            echo '<img src="' . esc_url($logo[0]) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="y-c-footer-logo y-c-footer-logo--large">';
                        }
                    } else {
                        echo '<img src="' . esc_url(get_template_directory_uri()) . '/my-car/assets/logo.png" alt="' . esc_attr(get_bloginfo('name')) . '" class="y-c-footer-logo y-c-footer-logo--large">';
                    }
                    ?>
                    <span class="y-c-footer-brand-title"><?php bloginfo('name'); ?></span>
                </a>
                <ul class="y-l-contact-info">
                    <li>
                        <i class="fas fa-location-dot"></i>
                        <span><?php echo esc_html(get_option('my_car_address', 'الرياض - المملكة العربية السعودية')); ?></span>
                    </li>
                    <li>
                        <i class="fas fa-phone"></i>
                        <?php
                        $phone1 = get_option('my_car_phone1', '059688929');
                        $phone2 = get_option('my_car_phone2', '058493948');
                        ?>
                        <a href="tel:<?php echo esc_attr($phone1); ?>"><?php echo esc_html($phone1); ?></a>
                        <?php if ($phone2) : ?>
                            - <a href="tel:<?php echo esc_attr($phone2); ?>"><?php echo esc_html($phone2); ?></a>
                        <?php endif; ?>
                    </li>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?php echo esc_attr(get_option('my_car_email', 'info@super.ksa.com')); ?>">
                            <?php echo esc_html(get_option('my_car_email', 'info@super.ksa.com')); ?>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="y-l-footer-nav">
                <?php
                $footer_menu = wp_nav_menu(array(
                    'theme_location' => 'footer',
                    'container'      => false,
                    'echo'           => false,
                    'fallback_cb'    => false,
                ));

                if ($footer_menu) {
                    echo $footer_menu;
                } else {
                    // Default footer menu
                    ?>
                    <div class="y-l-footer-column">
                        <h4 class="y-c-column-title">الصفحات</h4>
                        <ul class="y-l-footer-links">
                            <li><a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a></li>
                            <?php if (function_exists('wc_get_page_permalink')) : ?>
                                <li><a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">أسطولنا</a></li>
                            <?php endif; ?>
                            <li><a href="<?php echo esc_url(home_url('/offers')); ?>">العروض</a></li>
                            <li><a href="<?php echo esc_url(home_url('/contact-us')); ?>">تواصل معنا</a></li>
                        </ul>
                    </div>
                    <div class="y-l-footer-column">
                        <h4 class="y-c-column-title">معلومات</h4>
                        <ul class="y-l-footer-links">
                            <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">سياسة الخصوصية</a></li>
                            <li><a href="<?php echo esc_url(home_url('/cancellation-policy')); ?>">سياسة الإلغاء</a></li>
                            <li><a href="<?php echo esc_url(home_url('/about-us')); ?>">من نحن</a></li>
                            <li><a href="<?php echo esc_url(home_url('/faq')); ?>">الأسئلة الشائعة</a></li>
                        </ul>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>

        <div class="y-l-footer-bottom">
            <p class="y-c-copyright">
                &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. جميع الحقوق محفوظة لـ Yamama Solutions
            </p>
            <div class="y-c-payment-methods">
                <i class="fab fa-cc-mastercard"></i>
                <i class="fab fa-cc-visa"></i>
                <i class="fab fa-apple-pay"></i>
                <span>طرق الدفع</span>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
