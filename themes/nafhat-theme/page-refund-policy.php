<?php
/**
 * Template Name: Refund Policy
 * Template for Refund and Exchange Policy Page
 *
 * @package Nafhat
 * @since 1.0.0
 */

get_header();

// Get contact settings
$contact_settings = nafhat_get_contact_settings();
$contact_email = !empty($contact_settings['display_email']) ? $contact_settings['display_email'] : get_option('admin_email');
$contact_phone = !empty($contact_settings['phone']) ? $contact_settings['phone'] : '+966 50 000 0000';
?>

<main id="main" class="site-main y-u-p-t-0">
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="header y-u-flex y-u-justify-between y-u-items-center y-u-p-y-56 y-u-p-t-24">
            <h1 class="y-u-color-primary y-u-text-2xl"><?php esc_html_e('سياسة الاسترجاع', 'nafhat'); ?></h1>
        </div>
        
        <div class="content policy-content y-u-flex y-u-justify-center y-u-flex-col y-u-gap-32 y-u-max-w-1200 y-u-pb-56">
            
            <!-- Introduction -->
            <div class="policy-section">
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h2 class="y-u-color-primary y-u-text-xl"><?php esc_html_e('سياسة الاسترجاع والاستبدال', 'nafhat'); ?></h2>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('نعمل دائماً لنيل رضاكم ونكون عند حسن ظنكم بنا. إذا كنت ترغب في إرجاع منتج ما، فنحن نقبل بسرور استبدال المنتج أو استرداد المبلغ المدفوع وفقاً للشروط التالية.', 'nafhat'); ?>
                    </p>
                </div>
            </div>
            
            <!-- Return Conditions -->
            <div class="policy-section">
                <h2 class="y-u-color-text y-u-text-xl y-u-mb-24"><?php esc_html_e('شروط الاسترجاع', 'nafhat'); ?></h2>
                
                <div class="policy-cards">
                    <div class="policy-card">
                        <div class="policy-card-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3><?php esc_html_e('المدة الزمنية', 'nafhat'); ?></h3>
                        <p><?php esc_html_e('يجب إرجاع المنتج في غضون 14 يومًا من تاريخ استلام الطلب.', 'nafhat'); ?></p>
                    </div>
                    
                    <div class="policy-card">
                        <div class="policy-card-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <h3><?php esc_html_e('حالة المنتج', 'nafhat'); ?></h3>
                        <p><?php esc_html_e('يجب أن يكون المنتج في حالته الأصلية وغير مستخدم مع جميع الملصقات والعبوات الأصلية.', 'nafhat'); ?></p>
                    </div>
                    
                    <div class="policy-card">
                        <div class="policy-card-icon">
                            <i class="fas fa-receipt"></i>
                        </div>
                        <h3><?php esc_html_e('إثبات الشراء', 'nafhat'); ?></h3>
                        <p><?php esc_html_e('يجب تقديم فاتورة الشراء أو رقم الطلب عند طلب الاسترجاع.', 'nafhat'); ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Non-returnable Items -->
            <div class="policy-section">
                <h2 class="y-u-color-text y-u-text-xl y-u-mb-24"><?php esc_html_e('المنتجات غير القابلة للاسترجاع', 'nafhat'); ?></h2>
                <ul class="y-u-color-muted policy-list">
                    <li><?php esc_html_e('المنتجات المخصصة أو المصنوعة حسب الطلب', 'nafhat'); ?></li>
                    <li><?php esc_html_e('المنتجات التي تم فتحها أو استخدامها (إلا في حالة وجود عيب)', 'nafhat'); ?></li>
                    <li><?php esc_html_e('منتجات العروض الخاصة والتصفيات (ما لم يُذكر خلاف ذلك)', 'nafhat'); ?></li>
                    <li><?php esc_html_e('المنتجات القابلة للتلف أو ذات تاريخ انتهاء صلاحية', 'nafhat'); ?></li>
                </ul>
            </div>
            
            <!-- Refund Process -->
            <div class="policy-section">
                <h2 class="y-u-color-text y-u-text-xl y-u-mb-24"><?php esc_html_e('طريقة الاسترداد', 'nafhat'); ?></h2>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('الدفع عبر الإنترنت', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('ستتم معالجة المبالغ المستردة في غضون 24 ساعة من استلام المنتج المعاد والتحقق من حالته. سيتم إرجاع المبلغ إلى نفس طريقة الدفع الأصلية خلال 3-5 أيام عمل، اعتماداً على البنك الخاص بك.', 'nafhat'); ?>
                    </p>
                </div>
                
                <div class="slide y-u-flex y-u-justify-center y-u-flex-col y-u-gap-12 y-u-mb-24">
                    <h3 class="y-u-color-text y-u-text-lg"><?php esc_html_e('الدفع نقداً عند التسليم', 'nafhat'); ?></h3>
                    <p class="y-u-color-muted">
                        <?php esc_html_e('في حالة الدفع نقداً عند التسليم، يمكنك اختيار:', 'nafhat'); ?>
                    </p>
                    <ul class="y-u-color-muted policy-list">
                        <li><?php esc_html_e('استبدال المنتج بمنتج آخر', 'nafhat'); ?></li>
                        <li><?php esc_html_e('الحصول على رصيد في حسابك يمكن استخدامه في الطلبات القادمة', 'nafhat'); ?></li>
                        <li><?php esc_html_e('تحويل المبلغ إلى حسابك البنكي', 'nafhat'); ?></li>
                    </ul>
                </div>
            </div>
            
            <!-- Exchange Policy -->
            <div class="policy-section">
                <h2 class="y-u-color-text y-u-text-xl y-u-mb-24"><?php esc_html_e('سياسة الاستبدال', 'nafhat'); ?></h2>
                <p class="y-u-color-muted y-u-mb-16">
                    <?php esc_html_e('نوفر خدمة استبدال المنتجات في الحالات التالية:', 'nafhat'); ?>
                </p>
                <ul class="y-u-color-muted policy-list">
                    <li><?php esc_html_e('وجود عيب في التصنيع', 'nafhat'); ?></li>
                    <li><?php esc_html_e('تلف المنتج أثناء الشحن', 'nafhat'); ?></li>
                    <li><?php esc_html_e('عدم تطابق المنتج مع الوصف', 'nafhat'); ?></li>
                    <li><?php esc_html_e('استلام منتج خاطئ', 'nafhat'); ?></li>
                </ul>
                <p class="y-u-color-muted y-u-mt-16">
                    <?php esc_html_e('سيتم استبدال المنتج بنفس المنتج أو منتج آخر بقيمة مماثلة حسب رغبتك وتوفر المنتج.', 'nafhat'); ?>
                </p>
            </div>
            
            <!-- Shipping Costs -->
            <div class="policy-section">
                <h2 class="y-u-color-text y-u-text-xl y-u-mb-24"><?php esc_html_e('تكاليف الشحن', 'nafhat'); ?></h2>
                
                <div class="policy-table-wrapper">
                    <table class="policy-table">
                        <thead>
                            <tr>
                                <th><?php esc_html_e('سبب الاسترجاع', 'nafhat'); ?></th>
                                <th><?php esc_html_e('تكلفة الشحن', 'nafhat'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><?php esc_html_e('عيب في المنتج أو خطأ من جانبنا', 'nafhat'); ?></td>
                                <td class="free"><?php esc_html_e('مجاناً (على حسابنا)', 'nafhat'); ?></td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e('تغيير الرأي أو عدم الرغبة', 'nafhat'); ?></td>
                                <td><?php esc_html_e('على حساب العميل', 'nafhat'); ?></td>
                            </tr>
                            <tr>
                                <td><?php esc_html_e('استبدال بمنتج آخر', 'nafhat'); ?></td>
                                <td><?php esc_html_e('حسب سياسة الشحن المعتادة', 'nafhat'); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- How to Return -->
            <div class="policy-section">
                <h2 class="y-u-color-text y-u-text-xl y-u-mb-24"><?php esc_html_e('كيفية طلب الاسترجاع', 'nafhat'); ?></h2>
                
                <div class="return-steps">
                    <div class="return-step">
                        <div class="step-number">1</div>
                        <div class="step-content">
                            <h3><?php esc_html_e('تواصل معنا', 'nafhat'); ?></h3>
                            <p><?php esc_html_e('تواصل معنا عبر البريد الإلكتروني أو الهاتف لإبلاغنا برغبتك في الاسترجاع.', 'nafhat'); ?></p>
                        </div>
                    </div>
                    
                    <div class="return-step">
                        <div class="step-number">2</div>
                        <div class="step-content">
                            <h3><?php esc_html_e('احصل على الموافقة', 'nafhat'); ?></h3>
                            <p><?php esc_html_e('سنراجع طلبك ونرسل لك تأكيد الموافقة مع تعليمات الإرجاع.', 'nafhat'); ?></p>
                        </div>
                    </div>
                    
                    <div class="return-step">
                        <div class="step-number">3</div>
                        <div class="step-content">
                            <h3><?php esc_html_e('أرسل المنتج', 'nafhat'); ?></h3>
                            <p><?php esc_html_e('قم بتغليف المنتج بشكل آمن وأرسله إلى العنوان المحدد.', 'nafhat'); ?></p>
                        </div>
                    </div>
                    
                    <div class="return-step">
                        <div class="step-number">4</div>
                        <div class="step-content">
                            <h3><?php esc_html_e('استلم المبلغ', 'nafhat'); ?></h3>
                            <p><?php esc_html_e('بعد استلام المنتج والتحقق منه، سنقوم بمعالجة الاسترداد.', 'nafhat'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Section -->
            <div class="policy-section contact-box">
                <h2 class="y-u-color-text y-u-text-xl y-u-mb-24"><?php esc_html_e('تواصل معنا', 'nafhat'); ?></h2>
                <p class="y-u-color-muted y-u-mb-16">
                    <?php esc_html_e('لطلب الاسترجاع أو الاستبدال، أو إذا كان لديك أي استفسارات، يرجى التواصل معنا:', 'nafhat'); ?>
                </p>
                <div class="contact-methods">
                    <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="contact-method">
                        <i class="fas fa-envelope"></i>
                        <span><?php echo esc_html($contact_email); ?></span>
                    </a>
                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_phone)); ?>" class="contact-method">
                        <i class="fas fa-phone"></i>
                        <span><?php echo esc_html($contact_phone); ?></span>
                    </a>
                </div>
            </div>
            
            <p class="y-u-color-muted" style="font-size: var(--y-text-sm);">
                <?php printf(esc_html__('آخر تحديث: %s', 'nafhat'), date_i18n(get_option('date_format'))); ?>
            </p>
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

/* Policy Cards */
.policy-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: var(--y-space-16);
    margin-top: var(--y-space-16);
}
.policy-card {
    background: var(--y-color-surface);
    border: 1px solid var(--y-color-border);
    border-radius: var(--y-radius-12);
    padding: var(--y-space-24);
    text-align: center;
}
.policy-card-icon {
    width: 60px;
    height: 60px;
    background: var(--y-color-primary-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto var(--y-space-16);
}
.policy-card-icon i {
    font-size: 24px;
    color: var(--y-color-primary);
}
.policy-card h3 {
    color: var(--y-color-text);
    margin-bottom: var(--y-space-8);
}
.policy-card p {
    color: var(--y-color-muted);
    font-size: var(--y-text-sm);
}

/* Policy Table */
.policy-table-wrapper {
    overflow-x: auto;
    margin-top: var(--y-space-16);
}
.policy-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--y-color-surface);
    border-radius: var(--y-radius-8);
    overflow: hidden;
}
.policy-table th,
.policy-table td {
    padding: var(--y-space-16);
    text-align: right;
    border-bottom: 1px solid var(--y-color-border);
}
.policy-table th {
    background: var(--y-color-primary);
    color: white;
    font-weight: 600;
}
.policy-table td.free {
    color: var(--y-color-success);
    font-weight: 600;
}

/* Return Steps */
.return-steps {
    display: flex;
    flex-direction: column;
    gap: var(--y-space-16);
    margin-top: var(--y-space-16);
}
.return-step {
    display: flex;
    gap: var(--y-space-16);
    align-items: flex-start;
    background: var(--y-color-surface);
    border: 1px solid var(--y-color-border);
    border-radius: var(--y-radius-8);
    padding: var(--y-space-16);
}
.step-number {
    width: 40px;
    height: 40px;
    background: var(--y-color-primary);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    flex-shrink: 0;
}
.step-content h3 {
    color: var(--y-color-text);
    margin-bottom: var(--y-space-4);
}
.step-content p {
    color: var(--y-color-muted);
    font-size: var(--y-text-sm);
}

/* Contact Box */
.contact-box {
    background: var(--y-color-surface);
    border: 1px solid var(--y-color-border);
    border-radius: var(--y-radius-12);
    padding: var(--y-space-24);
}
.contact-methods {
    display: flex;
    flex-wrap: wrap;
    gap: var(--y-space-16);
}
.contact-method {
    display: flex;
    align-items: center;
    gap: var(--y-space-8);
    padding: var(--y-space-12) var(--y-space-20);
    background: var(--y-color-primary);
    color: white;
    border-radius: var(--y-radius-8);
    text-decoration: none;
    transition: all var(--y-transition);
}
.contact-method:hover {
    background: var(--y-color-primary-dark);
    transform: translateY(-2px);
}
.contact-method i {
    font-size: 18px;
}

@media (max-width: 767px) {
    .policy-cards {
        grid-template-columns: 1fr;
    }
    .return-step {
        flex-direction: column;
        text-align: center;
    }
    .step-number {
        margin: 0 auto;
    }
    .contact-methods {
        flex-direction: column;
    }
    .contact-method {
        justify-content: center;
    }
}
</style>

<?php
get_footer();
