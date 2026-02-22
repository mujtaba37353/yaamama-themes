<?php
/**
 * The footer template file
 *
 * @package TechnoSouqTheme
 */
?>

<footer class="y-l-footer" data-y="footer-container">
    <div class="y-l-footer-container" data-y="footer-main">

        <!-- Top Section: Centered Logo -->
        <div class="y-c-footer-top" data-y="footer-top-section">
            <?php
            $footer_logo_url = techno_souq_asset_url('logo-copy.png');
            if (has_custom_logo()) {
                the_custom_logo();
            } else {
                echo '<img src="' . esc_url($footer_logo_url) . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="y-c-footer-logo" data-y="footer-logo">';
            }
            ?>
        </div>

        <!-- Middle Section: Links Grid -->
        <div class="y-l-footer-links-grid" data-y="footer-links-grid">

            <!-- Column 1: Important Links (Right in RTL) -->
            <div class="y-c-footer-links" data-y="policies-links-section">
                <h3 data-y="policies-title">روابط تهمك</h3>
                <ul data-y="policies-list">
                    <?php
                    $use_policy = get_page_by_path('use-policy');
                    $privacy = get_page_by_path('privacy');
                    
                    // Try multiple methods to find refund/return policy page
                    $refund = get_page_by_path('refund');
                    if (!$refund) {
                        $refund = get_page_by_path('replacement');
                    }
                    if (!$refund) {
                        $refund = get_page_by_path('return-policy');
                    }
                    // Try by title
                    if (!$refund) {
                        $refund = get_page_by_title('سياسة الاستبدال والاسترجاع');
                    }
                    if (!$refund) {
                        $refund = get_page_by_title('سياسة الاسترجاع');
                    }
                    // Try using get_pages as last resort
                    if (!$refund) {
                        $pages = get_pages(array(
                            'meta_key' => '_wp_page_template',
                            'hierarchical' => 0,
                            'number' => 1
                        ));
                        foreach ($pages as $page) {
                            if (stripos($page->post_name, 'replacement') !== false || 
                                stripos($page->post_name, 'refund') !== false ||
                                stripos($page->post_name, 'return') !== false) {
                                $refund = $page;
                                break;
                            }
                        }
                    }
                    ?>
                    <?php if ($use_policy) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($use_policy)); ?>" data-y="usage-policy-link">سياسات الاستخدام</a></li>
                    <?php endif; ?>
                    <?php if ($privacy) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($privacy)); ?>" data-y="privacy-policy-link">السياسات</a></li>
                    <?php endif; ?>
                    <?php if ($refund) : ?>
                        <li><a href="<?php echo esc_url(get_permalink($refund)); ?>" data-y="return-policy-link">سياسة الاسترجاع</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Column 2: Main Pages (Center) -->
            <div class="y-c-footer-links" data-y="main-links-section">
                <h3 class="y-c-footer-hidden-title">روابط</h3>
                <ul data-y="main-links-list">
                    <li><a href="<?php echo esc_url(home_url('/')); ?>" data-y="footer-home-link">الرئيسية</a></li>
                    <?php
                    // Get shop page URL
                    if (function_exists('get_post_type_archive_link')) {
                        $shop_url = get_post_type_archive_link('product');
                    }
                    if (empty($shop_url) && function_exists('wc_get_page_permalink')) {
                        $shop_url = wc_get_page_permalink('shop');
                    }
                    if (empty($shop_url)) {
                        $shop_url = home_url('/shop');
                    }
                    ?>
                    <li><a href="<?php echo esc_url($shop_url); ?>" data-y="footer-shop-link">تسوق</a></li>
                    <?php
                    $about_page = get_page_by_path('about-us');
                    $about_url = $about_page ? get_permalink($about_page) : home_url('/about-us');
                    ?>
                    <li><a href="<?php echo esc_url($about_url); ?>" data-y="footer-about-link">من نحن</a></li>
                    <?php
                    $contact_page = get_page_by_path('contact-us');
                    $contact_url = $contact_page ? get_permalink($contact_page) : home_url('/contact-us');
                    ?>
                    <li><a href="<?php echo esc_url($contact_url); ?>" data-y="footer-contact-link">تواصل معنا</a></li>
                </ul>
            </div>

            <!-- Column 3: Socials (Left in RTL) -->
            <div class="y-c-footer-links" data-y="socials-section">
                <h3 class="y-c-footer-hidden-title">حساباتنا</h3>
                
                <!-- Social Media Icons -->
                <div class="y-c-footer-socials" data-y="footer-socials">
                    <p class="y-c-socials-title" data-y="socials-title">حساباتنا</p>
                    <div class="y-c-footer-socials-icons" data-y="socials-icons-container">
                        <?php
                        $facebook = get_theme_mod('techno_souq_facebook_url', '#');
                        $instagram = get_theme_mod('techno_souq_instagram_url', '#');
                        $snapchat = get_theme_mod('techno_souq_snapchat_url', '#');
                        ?>
                        <?php if ($facebook) : ?>
                            <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener"><i class="fab fa-facebook-f"></i></a>
                        <?php endif; ?>
                        <?php if ($instagram) : ?>
                            <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener"><i class="fab fa-instagram"></i></a>
                        <?php endif; ?>
                        <?php if ($snapchat) : ?>
                            <a href="<?php echo esc_url($snapchat); ?>" target="_blank" rel="noopener"><i class="fab fa-snapchat"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section: Copyright & Payments -->
        <div class="y-c-footer-bottom" data-y="footer-bottom">
            <div class="y-c-footer-copyright" data-y="footer-copyright">
                <p data-y="copyright-text">
                    <?php
                    $copyright = get_theme_mod('techno_souq_copyright_text', 'جميع الحقوق محفوظه ل Yamama solutions');
                    echo esc_html($copyright);
                    ?>
                </p>
            </div>

            <?php
            // Only show payment icons on My Account pages
            if (function_exists('is_account_page') && is_account_page()) :
            ?>
                <div class="y-c-footer-payments" data-y="footer-payments">
                    <span class="y-c-payment-label">طرق الدفع</span>
                    <div class="y-c-payment-icons">
                        <i class="fab fa-cc-visa"></i>
                        <i class="fab fa-cc-mastercard"></i>
                        <img src="<?php echo esc_url(techno_souq_asset_url('Stc_pay.svg.png')); ?>" alt="stc pay" class="y-c-payment-img">
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<?php
$whatsapp_number = techno_souq_get_whatsapp_number();
if (!empty($whatsapp_number)) :
    // Format WhatsApp number (remove any + or spaces)
    $whatsapp_clean = preg_replace('/[^0-9]/', '', $whatsapp_number);
    $whatsapp_url = 'https://wa.me/' . $whatsapp_clean;
?>
    <a href="<?php echo esc_url($whatsapp_url); ?>" target="_blank" rel="noopener" class="y-c-whatsapp-float" data-y="whatsapp-float-btn" aria-label="<?php esc_attr_e('تواصل معنا عبر واتساب', 'techno-souq-theme'); ?>">
        <i class="fab fa-whatsapp" data-y="whatsapp-icon"></i>
    </a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
