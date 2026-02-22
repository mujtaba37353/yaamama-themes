<?php
/**
 * Template Name: About Us Page
 * Template for displaying the About Us page
 *
 * @package MyClinic
 */

get_header();
?>

<main>
    <!-- Breadcrumbs Section -->
    <section class="breadcrumbs-container">
        <div class="breadcrumbs container y-u-max-w-1200">
            <a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a>
            /
            <p>من نحن</p>
        </div>
    </section>

    <!-- About Us Section -->
    <section class="about-us-section">
        <div class="container y-u-max-w-1200">
            <div class="right">
                <h2><?php echo esc_html(get_theme_mod('about_us_title', 'من نحن')); ?></h2>
                <p>
                    <?php 
                    $about_content = get_theme_mod('about_us_content', 'موقعنا هو منصّة متكاملة لحجز المواعيد الطبية بسهولة وسرعة. بنساعدك تلاقي أفضل الأطباء والعيادات في مختلف التخصصات، وتقارن بينهم حسب التقييمات والأسعار والموقع الجغرافي. هدفنا إننا نسهّل تجربة الرعاية الصحية ونخلّيها مريحة ومضمونة من أول خطوة للحجز لحد ما توصل للدكتور.

من خلال موقعنا، تقدر تحجز موعد، تستشير طبيب أونلاين، تعرف أقرب العيادات ليك، وتشوف تقييمات وتجارب المرضى الحقيقيين كل ده بخطوات بسيطة ومن مكانك.');
                    // Convert line breaks to <br> tags
                    $about_content = nl2br(esc_html($about_content));
                    echo wp_kses_post($about_content);
                    ?>
                </p>
            </div>

            <?php 
            $about_image = get_theme_mod('about_us_image', get_template_directory_uri() . '/assets/images/about-us.png');
            if ($about_image):
            ?>
                <img src="<?php echo esc_url($about_image); ?>" alt="<?php echo esc_attr(get_theme_mod('about_us_title', 'من نحن')); ?>">
            <?php endif; ?>
        </div>
    </section>
</main>

<?php
get_footer();
