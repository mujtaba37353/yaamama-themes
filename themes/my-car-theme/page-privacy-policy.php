<?php
/**
 * Template Name: صفحة سياسة الخصوصية
 * The template for displaying Privacy Policy page
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

// Get dynamic content
$content = my_car_get_page_content('privacy-policy');

get_header();
?>

<main data-y="main">
    <!-- Hero Section -->
    <div class="y-l-page-hero y-l-policy-hero" data-y="privacy-hero">
        <div class="y-c-hero-content">
            <div class="y-c-hero-icon">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <h1 class="y-c-hero-title"><?php echo esc_html($content['hero_title']); ?></h1>
            <p class="y-c-hero-subtitle"><?php echo esc_html($content['hero_subtitle']); ?></p>
        </div>
    </div>

    <div class="y-u-container y-l-policy-container">
        <div class="y-c-policy-content">
            
            <div class="y-c-policy-intro">
                <p><?php echo wp_kses_post(str_replace('MY CAR', '<strong>MY CAR</strong>', $content['intro'])); ?></p>
                <p class="y-c-policy-date">
                    <i class="fa-solid fa-calendar"></i>
                    آخر تحديث: <?php echo esc_html($content['last_update']); ?>
                </p>
            </div>

            <?php foreach ($content['sections'] as $section): ?>
            <div class="y-c-policy-section">
                <h2>
                    <i class="fa-solid <?php echo esc_attr($section['icon']); ?>"></i>
                    <?php echo esc_html($section['title']); ?>
                </h2>
                <?php echo wp_kses_post($section['content']); ?>
            </div>
            <?php endforeach; ?>

            <div class="y-c-policy-section">
                <h2>
                    <i class="fa-solid fa-envelope"></i>
                    تواصل معنا
                </h2>
                <p>
                    إذا كانت لديك أي أسئلة حول سياسة الخصوصية، يرجى التواصل معنا:
                </p>
                <div class="y-c-policy-contact">
                    <span><i class="fa-solid fa-envelope"></i> info@super.ksa.com</span>
                    <span><i class="fa-solid fa-phone"></i> 059688929</span>
                </div>
            </div>

        </div>
    </div>
</main>

<?php
get_footer();
?>
