    <?php
    $footer_post = ahmadi_theme_get_latest_post('ahmadi_footer_about');
    $footer_title = $footer_post ? get_post_meta($footer_post->ID, 'ahmadi_footer_title', true) : '';
    $footer_text = $footer_post ? get_post_meta($footer_post->ID, 'ahmadi_footer_text', true) : '';
    if ($footer_title === '') {
        $footer_title = 'حولنا';
    }
    if ($footer_text === '') {
        $footer_text = 'لقد بنينا سمعتنا من خلال الالتزام بالجودة، والاهتمام بأدق التفاصيل، وتقديم تجربة تسوّق إلكتروني سلسة وآمنة تلبي تطلعات عملائنا. تميزنا لا يأتي من فراغ، بل هو ثمرة رؤية واضحة وفريق عمل محترف، ودعم متواصل لكل عميل يسعى للتميز في عالم التجارة الرقمية.';
    }
    ?>
    <footer class="y-c-footer">
        <div class="y-c-footer-container">
            <div class="y-c-footer-content">
                <div class="y-c-footer-section">
                    <h3><?php echo esc_html($footer_title); ?></h3>
                    <p><?php echo esc_html($footer_text); ?></p>
                </div>

                <div class="y-c-footer-section">
                    <h3>روابط مهمة</h3>
                    <ul>
                        <li><a href="<?php echo esc_url(ahmadi_theme_page_url('account')); ?>">طلباتي</a></li>
                        <li><a href="<?php echo esc_url(ahmadi_theme_page_url('replacement')); ?>">سياسة الاستبدال</a></li>
                        <li><a href="<?php echo esc_url(ahmadi_theme_page_url('privacy')); ?>">سياسة الخصوصية والاستخدام</a></li>
                    </ul>
                </div>

                <div class="y-c-footer-section">
                    <h3>روابط أخرى</h3>
                    <ul>
                        <li><a href="<?php echo esc_url(ahmadi_theme_page_url('contact-us')); ?>">تواصل معنا</a></li>
                        <li><a href="<?php echo esc_url(ahmadi_theme_page_url('about-us')); ?>">من نحن</a></li>
                    </ul>
                </div>

                <div class="y-c-footer-section">
                    <h3>خدمات العملاء</h3>
                    <ul>
                        <li><a href="<?php echo esc_url(ahmadi_theme_page_url('account')); ?>">حسابي</a></li>
                    </ul>
                </div>
            </div>

        </div>

        <div class="y-c-footer-bottom">
            <img src="<?php echo esc_url(ahmadi_theme_get_site_logo_url()); ?>" alt="store logo">
            <div>
                <p>جميع الحقوق محفوظة لYamama solutions</p>
            </div>

        </div>
    </footer>
    <?php wp_footer(); ?>
</body>
</html>
