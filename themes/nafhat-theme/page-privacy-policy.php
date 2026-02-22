<?php
/**
 * Template Name: Privacy & Terms Policy
 * Template for Privacy Policy and Terms of Use Page
 *
 * @package Nafhat
 * @since 1.0.0
 */

get_header();

// Get contact settings
$contact_settings = nafhat_get_contact_settings();
$contact_email = !empty($contact_settings['display_email']) ? $contact_settings['display_email'] : get_option('admin_email');
?>

<main id="main" class="site-main y-u-p-t-0">
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="header y-u-flex y-u-justify-between y-u-items-center y-u-p-y-56 y-u-p-t-24">
            <h1 class="y-u-color-primary y-u-text-2xl"><?php esc_html_e('سياسة الخصوصية والاستخدام', 'nafhat'); ?></h1>
        </div>
        
        <div class="content policy-content y-u-flex y-u-justify-center y-u-flex-col y-u-gap-32 y-u-max-w-1200 y-u-pb-56">
            
            <!-- Privacy Policy Section -->
            <div class="policy-section">
                <h2 class="y-u-color-primary y-u-text-xl y-u-mb-24"><?php esc_html_e('سياسة الخصوصية', 'nafhat'); ?></h2>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('مقدمة', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نحن نلتزم بحماية خصوصيتك. تشرح سياسة الخصوصية هذه كيف نقوم بجمع واستخدام وحماية المعلومات الشخصية التي تقدمها لنا عند استخدام موقعنا الإلكتروني.', 'nafhat'); ?>
                    </p>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('المعلومات التي نجمعها', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نقوم بجمع المعلومات التي تقدمها لنا مباشرة عند التسجيل في موقعنا أو إجراء عملية شراء، بما في ذلك:', 'nafhat'); ?>
                    </p>
                    <ul class="y-u-color-muted policy-list">
                        <li><?php esc_html_e('الاسم الكامل', 'nafhat'); ?></li>
                        <li><?php esc_html_e('عنوان البريد الإلكتروني', 'nafhat'); ?></li>
                        <li><?php esc_html_e('رقم الهاتف', 'nafhat'); ?></li>
                        <li><?php esc_html_e('عنوان الشحن', 'nafhat'); ?></li>
                        <li><?php esc_html_e('معلومات الدفع', 'nafhat'); ?></li>
                    </ul>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('كيف نستخدم المعلومات', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نستخدم المعلومات التي نجمعها للأغراض التالية:', 'nafhat'); ?>
                    </p>
                    <ul class="y-u-color-muted policy-list">
                        <li><?php esc_html_e('معالجة الطلبات وإتمام عمليات الشراء', 'nafhat'); ?></li>
                        <li><?php esc_html_e('إدارة حسابك وتقديم خدمة العملاء', 'nafhat'); ?></li>
                        <li><?php esc_html_e('تحسين تجربتك في التسوق', 'nafhat'); ?></li>
                        <li><?php esc_html_e('إرسال إشعارات متعلقة بالطلبات', 'nafhat'); ?></li>
                        <li><?php esc_html_e('إرسال العروض والتحديثات (بموافقتك)', 'nafhat'); ?></li>
                    </ul>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('حماية المعلومات', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نستخدم تقنيات الأمان المتقدمة لحماية معلوماتك الشخصية من الوصول غير المصرح به أو التغيير أو الكشف أو التدمير. جميع المعاملات المالية تتم عبر بوابات دفع آمنة ومشفرة.', 'nafhat'); ?>
                    </p>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('مشاركة المعلومات', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('لا نبيع أو نؤجر معلوماتك الشخصية لأطراف ثالثة. قد نشارك معلوماتك مع:', 'nafhat'); ?>
                    </p>
                    <ul class="y-u-color-muted policy-list">
                        <li><?php esc_html_e('شركات الشحن لتوصيل طلباتك', 'nafhat'); ?></li>
                        <li><?php esc_html_e('بوابات الدفع لمعالجة المدفوعات', 'nafhat'); ?></li>
                        <li><?php esc_html_e('الجهات الحكومية عند الطلب القانوني', 'nafhat'); ?></li>
                    </ul>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('ملفات تعريف الارتباط (Cookies)', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نستخدم ملفات تعريف الارتباط لتحسين تجربتك على موقعنا. يمكنك التحكم في إعدادات ملفات تعريف الارتباط من خلال متصفحك.', 'nafhat'); ?>
                    </p>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('حقوقك', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('لديك الحق في:', 'nafhat'); ?>
                    </p>
                    <ul class="y-u-color-muted policy-list">
                        <li><?php esc_html_e('الوصول إلى معلوماتك الشخصية', 'nafhat'); ?></li>
                        <li><?php esc_html_e('تصحيح أو تحديث بياناتك', 'nafhat'); ?></li>
                        <li><?php esc_html_e('طلب حذف حسابك', 'nafhat'); ?></li>
                        <li><?php esc_html_e('إلغاء الاشتراك في الرسائل التسويقية', 'nafhat'); ?></li>
                    </ul>
                </div>
            </div>
            
            <hr class="policy-divider" />
            
            <!-- Terms of Use Section -->
            <div class="policy-section">
                <h2 class="y-u-color-primary y-u-text-xl y-u-mb-24"><?php esc_html_e('سياسة الاستخدام', 'nafhat'); ?></h2>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('القبول بالشروط', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('باستخدامك لهذا الموقع، فإنك توافق على الالتزام بهذه الشروط والأحكام. إذا كنت لا توافق على أي من هذه الشروط، يرجى عدم استخدام الموقع.', 'nafhat'); ?>
                    </p>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('استخدام الموقع', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('يجب عليك استخدام هذا الموقع للأغراض المشروعة فقط. يُحظر عليك:', 'nafhat'); ?>
                    </p>
                    <ul class="y-u-color-muted policy-list">
                        <li><?php esc_html_e('استخدام الموقع بطريقة تنتهك أي قوانين أو لوائح', 'nafhat'); ?></li>
                        <li><?php esc_html_e('محاولة الوصول غير المصرح به إلى أنظمتنا', 'nafhat'); ?></li>
                        <li><?php esc_html_e('نشر محتوى ضار أو مسيء', 'nafhat'); ?></li>
                        <li><?php esc_html_e('انتحال شخصية أي شخص أو كيان', 'nafhat'); ?></li>
                    </ul>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('حسابات المستخدمين', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('عند إنشاء حساب، يجب عليك تقديم معلومات دقيقة وكاملة. أنت مسؤول عن الحفاظ على سرية كلمة المرور الخاصة بك وعن جميع الأنشطة التي تتم تحت حسابك.', 'nafhat'); ?>
                    </p>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('المنتجات والأسعار', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نحتفظ بالحق في تعديل أو إيقاف أي منتج دون إشعار مسبق. الأسعار قابلة للتغيير دون إشعار. نبذل قصارى جهدنا لعرض ألوان وصور دقيقة للمنتجات، ولكن قد تختلف الألوان الفعلية قليلاً.', 'nafhat'); ?>
                    </p>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('الملكية الفكرية', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('جميع المحتويات على هذا الموقع، بما في ذلك النصوص والصور والشعارات والرسومات، هي ملك لنا أو لمرخصينا ومحمية بموجب قوانين حقوق النشر والعلامات التجارية.', 'nafhat'); ?>
                    </p>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('تحديد المسؤولية', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نحن غير مسؤولين عن أي أضرار مباشرة أو غير مباشرة ناتجة عن استخدام أو عدم القدرة على استخدام هذا الموقع أو المنتجات المشتراة منه.', 'nafhat'); ?>
                    </p>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('التعديلات', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نحتفظ بالحق في تعديل هذه الشروط في أي وقت. سيتم نشر التعديلات على هذه الصفحة، واستمرارك في استخدام الموقع بعد التعديلات يعني قبولك للشروط الجديدة.', 'nafhat'); ?>
                    </p>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('القانون الحاكم', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('تخضع هذه الشروط وتُفسر وفقاً لقوانين المملكة العربية السعودية.', 'nafhat'); ?>
                    </p>
                </div>
            </div>
            
            <hr class="policy-divider" />
            
            <!-- Contact Section -->
            <div class="policy-section">
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('اتصل بنا', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php
                        printf(
                            esc_html__('إذا كان لديك أي أسئلة حول سياسة الخصوصية والاستخدام، يرجى الاتصال بنا على: %s', 'nafhat'),
                            '<a href="mailto:' . esc_attr($contact_email) . '">' . esc_html($contact_email) . '</a>'
                        );
                        ?>
                    </p>
                </div>
                
                <p class="y-u-color-muted y-u-mt-24" style="font-size: var(--y-text-sm);">
                    <?php printf(esc_html__('آخر تحديث: %s', 'nafhat'), date_i18n(get_option('date_format'))); ?>
                </p>
            </div>
        </div>
    </section>
</main>

<style>
.policy-content {
    line-height: 1.8;
}
.policy-content h2 {
    padding-bottom: var(--y-space-12);
    border-bottom: 2px solid var(--y-color-primary);
    display: inline-block;
}
.policy-content h3 {
    font-weight: 600;
}
.policy-list {
    list-style: disc;
    padding-right: var(--y-space-24);
    margin-top: var(--y-space-8);
}
.policy-list li {
    margin-bottom: var(--y-space-8);
}
.policy-divider {
    border: none;
    border-top: 1px solid var(--y-color-border);
    margin: var(--y-space-16) 0;
}
</style>

<?php
get_footer();
