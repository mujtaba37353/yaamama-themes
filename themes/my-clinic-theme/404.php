<?php
/**
 * 404 Error Page – صفحة الخطأ 404
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

// Enqueue thank you page styles (same design structure)
$theme_version = wp_get_theme()->get('Version');
$theme_uri = get_template_directory_uri();
wp_enqueue_style('my-clinic-thank-you', $theme_uri . '/assets/css/components/thank-you.css', array(
    'my-clinic-header',
    'my-clinic-footer',
    'my-clinic-buttons'
), $theme_version);

get_header();
?>

<main>
    <section class="thank-you-page">
        <div class="thank-you-content">
            <div class="thank-you-icon" style="background-color: #ff9800;">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="thank-you-title">404 - الصفحة غير موجودة</h1>
            
            <p style="font-size: var(--y-space-18); color: var(--y-color-txt); margin-bottom: var(--y-space-32); line-height: 1.6;">
                <?php esc_html_e('عذراً، الصفحة التي تبحث عنها غير موجودة أو تم نقلها.', 'my-clinic'); ?>
            </p>

            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn main-button fw">العودة إلى الصفحة الرئيسية</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
