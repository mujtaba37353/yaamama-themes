<?php
/**
 * Template Name: Terms of Service
 * Template for Terms of Service Page
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
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php endif; ?>
            
            <!-- Default Terms Content -->
            <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                <h2 class="y-u-color-text y-u-text-xl"><?php esc_html_e('قبول الشروط', 'nafhat'); ?></h2>
                <p class="y-u-color-muted">
                    <?php esc_html_e('باستخدام موقع نفحات الإلكتروني، فإنك تقبل وتوافق على الالتزام بهذه الشروط والأحكام. إذا كنت لا توافق على أي جزء من هذه الشروط، فيرجى عدم استخدام موقعنا.', 'nafhat'); ?>
                </p>
            </div>
            
            <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                <h2 class="y-u-color-text y-u-text-xl"><?php esc_html_e('استخدام الموقع', 'nafhat'); ?></h2>
                <p class="y-u-color-muted">
                    <?php esc_html_e('يجب استخدام موقعنا فقط للأغراض القانونية. لا يجوز لك استخدام الموقع بطريقة تنتهك أي قوانين محلية أو دولية أو تنتهك حقوق الآخرين أو تتداخل مع استخدام الآخرين للموقع.', 'nafhat'); ?>
                </p>
            </div>
            
            <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                <h2 class="y-u-color-text y-u-text-xl"><?php esc_html_e('المنتجات والأسعار', 'nafhat'); ?></h2>
                <p class="y-u-color-muted">
                    <?php esc_html_e('نسعى جاهدين لتوفير معلومات دقيقة عن المنتجات والأسعار. ومع ذلك، قد تحدث أخطاء، ونحتفظ بالحق في تصحيح أي أخطاء في التسعير أو المعلومات في أي وقت.', 'nafhat'); ?>
                </p>
            </div>
            
            <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                <h2 class="y-u-color-text y-u-text-xl"><?php esc_html_e('الطلبات والدفع', 'nafhat'); ?></h2>
                <p class="y-u-color-muted">
                    <?php esc_html_e('عند إجراء طلب، فإنك توافق على توفير معلومات دقيقة وكاملة. نقبل طرق دفع متعددة ونتخذ جميع التدابير اللازمة لضمان أمان معاملاتك.', 'nafhat'); ?>
                </p>
            </div>
            
            <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                <h2 class="y-u-color-text y-u-text-xl"><?php esc_html_e('الملكية الفكرية', 'nafhat'); ?></h2>
                <p class="y-u-color-muted">
                    <?php esc_html_e('جميع المحتويات الموجودة على موقعنا، بما في ذلك النصوص والصور والتصاميم، محمية بحقوق الطبع والنشر والملكية الفكرية. لا يجوز نسخ أو استخدام أي محتوى دون إذن كتابي منا.', 'nafhat'); ?>
                </p>
            </div>
            
            <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                <h2 class="y-u-color-text y-u-text-xl"><?php esc_html_e('الحد من المسؤولية', 'nafhat'); ?></h2>
                <p class="y-u-color-muted">
                    <?php esc_html_e('لن نكون مسؤولين عن أي أضرار مباشرة أو غير مباشرة ناتجة عن استخدام موقعنا أو المنتجات التي نقدمها، بما في ذلك على سبيل المثال لا الحصر، فقدان البيانات أو الأرباح.', 'nafhat'); ?>
                </p>
            </div>
            
            <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                <h2 class="y-u-color-text y-u-text-xl"><?php esc_html_e('التعديلات على الشروط', 'nafhat'); ?></h2>
                <p class="y-u-color-muted">
                    <?php esc_html_e('نحتفظ بالحق في تعديل هذه الشروط والأحكام في أي وقت. سيتم إشعارك بأي تغييرات عبر نشر الشروط المحدثة على هذه الصفحة.', 'nafhat'); ?>
                </p>
            </div>
            
            <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                <h2 class="y-u-color-text y-u-text-xl"><?php esc_html_e('اتصل بنا', 'nafhat'); ?></h2>
                <p class="y-u-color-muted">
                    <?php
                    $contact_email = get_theme_mod('nafhat_contact_email', get_option('admin_email'));
                    printf(
                        esc_html__('إذا كان لديك أي أسئلة حول شروط الاستخدام هذه، يرجى الاتصال بنا على: %s', 'nafhat'),
                        '<a href="mailto:' . esc_attr($contact_email) . '">' . esc_html($contact_email) . '</a>'
                    );
                    ?>
                </p>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
