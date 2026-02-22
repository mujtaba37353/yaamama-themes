<footer class="footer">
    <div class="container y-u-max-w-1200">
        <div class="logo">
            <?php if (has_custom_logo()) : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <?php the_custom_logo(); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.png'); ?>" alt="<?php bloginfo('name'); ?>" />
                </a>
            <?php endif; ?>
        </div>
        
        <div class="footer-links">
            <ul>
                <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('الرئيسية', 'nafhat'); ?></a></li>
                <?php if (function_exists('is_woocommerce')) : ?>
                    <li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('حسابي', 'nafhat'); ?></a></li>
                    <li><a href="<?php echo esc_url(wc_get_page_permalink('myaccount') . 'orders'); ?>"><?php esc_html_e('طلباتي', 'nafhat'); ?></a></li>
                <?php else : ?>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('حسابي', 'nafhat'); ?></a></li>
                    <li><a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('طلباتي', 'nafhat'); ?></a></li>
                <?php endif; ?>
            </ul>
            
            <ul>
                <li><a href="<?php echo esc_url(home_url('/privacy-policy/')); ?>"><?php esc_html_e('سياسة الخصوصية والاستخدام', 'nafhat'); ?></a></li>
                <li><a href="<?php echo esc_url(home_url('/refund-policy/')); ?>"><?php esc_html_e('سياسة الاسترجاع', 'nafhat'); ?></a></li>
            </ul>
            
            <ul>
                <?php
                // Contact and About pages
                $contact_page = get_page_by_path('contact');
                $about_page = get_page_by_path('about-us');
                
                if ($contact_page) {
                    echo '<li><a href="' . esc_url(get_permalink($contact_page->ID)) . '">' . esc_html__('تواصل معنا', 'nafhat') . '</a></li>';
                } else {
                    echo '<li><a href="' . esc_url(home_url('/contact')) . '">' . esc_html__('تواصل معنا', 'nafhat') . '</a></li>';
                }
                
                if ($about_page) {
                    echo '<li><a href="' . esc_url(get_permalink($about_page->ID)) . '">' . esc_html__('من نحن', 'nafhat') . '</a></li>';
                } else {
                    echo '<li><a href="' . esc_url(home_url('/about-us')) . '">' . esc_html__('من نحن', 'nafhat') . '</a></li>';
                }
                ?>
            </ul>
        </div>
        
        <p>
            <?php
            printf(
                esc_html__('جميع الحقوق محفوظة ل %s © %s', 'nafhat'),
                '<a href="https://yamama-solutions.com" target="_blank">Yamama Solutions</a>',
                date('Y')
            );
            ?>
        </p>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>
