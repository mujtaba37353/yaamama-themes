<footer class="footer">
    <div class="container y-u-max-w-1200">
        <div class="main y-u-flex y-u-flex-col">
            <div class="logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php
                    $footer_logo = get_option('footer_logo', get_template_directory_uri() . '/assets/images/footer-icon.png');
                    if ($footer_logo && file_exists(str_replace(home_url(), ABSPATH, $footer_logo))) {
                        echo '<img src="' . esc_url($footer_logo) . '" alt="footer-icon">';
                    } elseif ($footer_logo) {
                        echo '<img src="' . esc_url($footer_logo) . '" alt="footer-icon">';
                    } else {
                        echo '<span>' . esc_html(get_bloginfo('name')) . '</span>';
                    }
                    ?>
                </a>
            </div>
            <p>
                <?php echo wp_kses_post(get_option('footer_description', 'موقعنا هو منصّة متكاملة لحجز المواعيد الطبية بسهولة وسرعة. بنساعدك تلاقي أفضل الأطباء والعيادات في مختلف التخصصات، وتقارن بينهم حسب التقييمات والأسعار والموقع الجغرافي. هدفنا إننا نسهّل تجربة الرعاية الصحية ونخلّيها مريحة ومضمونة من أول خطوة للحجز لحد ما توصل للدكتور.')); ?>
            </p>
        </div>
        <div class="links y-u-flex y-u-flex-col">
            <h2>الصفحات</h2>
            <ul class="y-u-flex y-u-justify-between y-u-flex-col">
                <li><a href="<?php echo esc_url(home_url('/')); ?>">الرئيسيه</a></li>
                <li><a href="<?php echo esc_url(home_url('/doctors')); ?>">الاطباء</a></li>
                <li><a href="<?php echo esc_url(home_url('/clinics')); ?>">العيادات</a></li>
                <li><a href="<?php echo esc_url(home_url('/about-us')); ?>">من نحن</a></li>
                <li><a href="<?php echo esc_url(home_url('/contact')); ?>">تواصل معنا</a></li>
                <li><a href="<?php echo esc_url(my_clinic_get_myaccount_url()); ?>">حسابي</a></li>
            </ul>
        </div>
        <div class="links y-u-flex y-u-flex-col">
            <h2>السياسات</h2>
            <ul class="y-u-flex y-u-justify-between y-u-flex-col">
                <li><a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">سياسة الخصوصية</a></li>
                <li><a href="<?php echo esc_url(home_url('/return-policy')); ?>">سياسة الاسترجاع</a></li>
            </ul>
        </div>

        <div class="links y-u-flex y-u-flex-col">
            <h2>تواصل معنا</h2>
            <ul class="y-u-flex y-u-justify-between y-u-flex-col">
                <li>
                    <a href="<?php echo esc_url(get_option('footer_contact_map_link', 'https://maps.app.goo.gl/j9xwz9xwz9xwz9xwz9xwz9xw')); ?>">
                        <?php
                        $map_icon = get_template_directory_uri() . '/assets/images/map.svg';
                        if (file_exists(get_template_directory() . '/assets/images/map.svg')) {
                            echo '<img src="' . esc_url($map_icon) . '" alt="الموقع">';
                        }
                        ?>
                        <?php echo esc_html(get_option('footer_contact_address', 'الرياض , المملكة العربية السعودية')); ?>
                    </a>
                </li>
                <li>
                    <a href="mailto:<?php echo esc_attr(get_option('footer_contact_email', 'Customercare@myclinic.com')); ?>">
                        <?php
                        $email_icon = get_template_directory_uri() . '/assets/images/email.svg';
                        if (file_exists(get_template_directory() . '/assets/images/email.svg')) {
                            echo '<img src="' . esc_url($email_icon) . '" alt="البريد الإلكتروني">';
                        }
                        ?>
                        <?php echo esc_html(get_option('footer_contact_email', 'Customercare@myclinic.com')); ?>
                    </a>
                </li>
                <li>
                    <a href="tel:<?php echo esc_attr(str_replace(' ', '', get_option('footer_contact_phone', '+966 12 345 6789'))); ?>">
                        <?php
                        $phone_icon = get_template_directory_uri() . '/assets/images/phone.svg';
                        if (file_exists(get_template_directory() . '/assets/images/phone.svg')) {
                            echo '<img src="' . esc_url($phone_icon) . '" alt="رقم الهاتف">';
                        }
                        ?>
                        <?php echo esc_html(get_option('footer_contact_phone', '+966 12 345 6789')); ?>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <p class="container y-u-max-w-1200">جميع الحقوق محفوظة &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?></p>
</footer>

<?php
// Floating WhatsApp Button
$whatsapp_number = get_option('footer_contact_whatsapp', '+966 12 345 6789');
if ($whatsapp_number) {
    $whatsapp_clean = preg_replace('/[^0-9]/', '', $whatsapp_number);
    if ($whatsapp_clean) {
        ?>
        <a href="https://wa.me/<?php echo esc_attr($whatsapp_clean); ?>" target="_blank" rel="noopener" class="floating-whatsapp" aria-label="تواصل معنا عبر الواتساب">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
        <style>
        .floating-whatsapp {
            position: fixed;
            bottom: 20px;
            left: 20px;
            width: 60px;
            height: 60px;
            background-color: #25D366;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
            z-index: 9999;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .floating-whatsapp:hover {
            background-color: #128C7E;
            transform: scale(1.1);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
        }
        .floating-whatsapp i {
            color: white;
        }
        @media (max-width: 768px) {
            .floating-whatsapp {
                width: 50px;
                height: 50px;
                font-size: 28px;
                bottom: 15px;
                left: 15px;
            }
        }
        </style>
        <?php
    }
}
?>

<?php wp_footer(); ?>
</body>
</html>
