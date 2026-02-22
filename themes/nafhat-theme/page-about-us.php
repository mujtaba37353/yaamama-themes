<?php
/**
 * Template Name: About Us
 * Template for About Us Page
 *
 * @package Nafhat
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main">
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="header y-u-flex y-u-justify-between y-u-items-center y-u-p-y-56 y-u-p-t-24">
            <h1 class="y-u-color-primary y-u-text-2xl"><?php the_title(); ?></h1>
        </div>
        
        <div class="content y-u-flex y-u-justify-center y-u-flex-col y-u-gap-24 y-u-max-w-1200">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php if (get_the_content()) : ?>
                        <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                            <?php the_content(); ?>
                        </div>
                    <?php else : ?>
                        <!-- Default Content -->
                        <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                            <p class="y-u-color-muted">
                                <?php esc_html_e('نحن في نفحات نؤمن أن الجمال يبدأ من التفاصيل الصغيرة. منذ انطلاقنا ونحن نقدم لعملائنا أجود أنواع العطور، منتجات المكياج، وحلول العناية الشخصية التي تجمع بين الفخامة والجودة.', 'nafhat'); ?>
                            </p>
                            <p class="y-u-color-muted">
                                <?php esc_html_e('رؤيتنا هي أن نكون الوجهة الأولى لكل من يبحث عن منتجات أصلية وموثوقة تضيف لمسة خاصة لحياته اليومية.', 'nafhat'); ?>
                            </p>
                            <p class="y-u-color-muted">
                                <?php esc_html_e('نحن ملتزمون بتقديم تجربة تسوق سلسة، دعم عملاء مميز، وأسعار تناسب الجميع. اكتشف مجموعتنا اليوم ودعنا نكون جزءًا من روتينك الجمالي.', 'nafhat'); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php else : ?>
                <!-- Default Content -->
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نحن في نفحات نؤمن أن الجمال يبدأ من التفاصيل الصغيرة. منذ انطلاقنا ونحن نقدم لعملائنا أجود أنواع العطور، منتجات المكياج، وحلول العناية الشخصية التي تجمع بين الفخامة والجودة.', 'nafhat'); ?>
                    </p>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('رؤيتنا هي أن نكون الوجهة الأولى لكل من يبحث عن منتجات أصلية وموثوقة تضيف لمسة خاصة لحياته اليومية.', 'nafhat'); ?>
                    </p>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نحن ملتزمون بتقديم تجربة تسوق سلسة، دعم عملاء مميز، وأسعار تناسب الجميع. اكتشف مجموعتنا اليوم ودعنا نكون جزءًا من روتينك الجمالي.', 'nafhat'); ?>
                    </p>
                </div>
            <?php endif; ?>
            
            <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                <h2 class="y-u-color-primary y-u-text-xl"><?php esc_html_e('رؤيتنا', 'nafhat'); ?></h2>
                <p class="y-u-color-muted">
                    <?php esc_html_e('أن نكون الوجهة الأولى لكل من يبحث عن منتجات أصلية وموثوقة تضيف لمسة خاصة لحياته اليومية.', 'nafhat'); ?>
                </p>
            </div>
            
            <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                <h2 class="y-u-color-primary y-u-text-xl"><?php esc_html_e('قيمنا', 'nafhat'); ?></h2>
                <p class="y-u-color-muted">
                    <?php esc_html_e('1- الجودة والأصالة: نحرص على توفير منتجات أصلية ومضمونة.', 'nafhat'); ?>
                </p>
                <p class="y-u-color-muted">
                    <?php esc_html_e('2- تجربة تسوق مميزة: واجهة سهلة ودعم عملاء سريع.', 'nafhat'); ?>
                </p>
                <p class="y-u-color-muted">
                    <?php esc_html_e('3- لمسة فخامة بأسعار مناسبة: نمنح عملاءنا أفضل قيمة مقابل السعر.', 'nafhat'); ?>
                </p>
                <p class="y-u-color-muted">
                    <?php esc_html_e('4- اكتشفي مجموعتنا الآن ودعينا نكون جزءًا من روتينك الجمالي اليومي.', 'nafhat'); ?>
                </p>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
