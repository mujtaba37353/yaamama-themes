<?php
/**
 * Account Deleted Page – بعد حذف الحساب
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

// Enqueue thank you page styles
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
            <div class="thank-you-icon" style="background-color: #f44336;">
                <i class="fas fa-user-times"></i>
            </div>
            <h1 class="thank-you-title">نأسف لذهابك ويسعدنا عودتكم</h1>
            
            <p style="font-size: var(--y-space-18); color: var(--y-color-txt); margin-bottom: var(--y-space-32); line-height: 1.6;">
                <?php esc_html_e('تم حذف حسابك بنجاح. نتمنى أن نراك مرة أخرى قريباً.', 'my-clinic'); ?>
            </p>

            <a href="<?php echo esc_url(home_url('/')); ?>" class="btn main-button fw">الذهاب إلى الرئيسية</a>
        </div>
    </section>
</main>

<?php get_footer(); ?>
