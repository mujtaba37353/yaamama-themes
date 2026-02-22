<?php
/**
 * Template Name: صفحة سياسة الإلغاء
 * The template for displaying Cancellation Policy page
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

// Get dynamic content
$content = my_car_get_page_content('cancellation-policy');

get_header();
?>

<main data-y="main">
    <!-- Hero Section -->
    <div class="y-l-page-hero y-l-policy-hero y-l-cancel-hero" data-y="cancellation-hero">
        <div class="y-c-hero-content">
            <div class="y-c-hero-icon">
                <i class="fa-solid fa-file-contract"></i>
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

            <div class="y-c-policy-section y-c-highlight-section">
                <h2>
                    <i class="fa-solid fa-clock"></i>
                    فترات الإلغاء والاسترداد
                </h2>
                
                <div class="y-c-cancellation-tiers">
                    <?php foreach ($content['tiers'] as $tier): ?>
                    <div class="y-c-tier y-c-tier-<?php echo esc_attr($tier['type']); ?>">
                        <div class="y-c-tier-header">
                            <?php 
                            $icon = 'fa-check-circle';
                            if ($tier['type'] === 'partial') $icon = 'fa-exclamation-circle';
                            if ($tier['type'] === 'none') $icon = 'fa-times-circle';
                            ?>
                            <i class="fa-solid <?php echo $icon; ?>"></i>
                            <span class="y-c-tier-time"><?php echo esc_html($tier['time']); ?></span>
                        </div>
                        <div class="y-c-tier-body">
                            <span class="y-c-tier-percentage"><?php echo esc_html($tier['percentage']); ?></span>
                            <span class="y-c-tier-label"><?php echo esc_html($tier['label']); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
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
                    <i class="fa-solid fa-question-circle"></i>
                    هل لديك أسئلة؟
                </h2>
                <p>
                    إذا كانت لديك أي استفسارات حول سياسة الإلغاء، لا تتردد في التواصل معنا:
                </p>
                <div class="y-c-policy-contact">
                    <span><i class="fa-solid fa-envelope"></i> info@super.ksa.com</span>
                    <span><i class="fa-solid fa-phone"></i> 059688929</span>
                </div>
                <a href="<?php echo esc_url(home_url('/contact-us')); ?>" class="y-c-policy-cta">
                    <i class="fa-solid fa-headset"></i>
                    تواصل مع خدمة العملاء
                </a>
            </div>

        </div>
    </div>
</main>

<?php
get_footer();
?>
