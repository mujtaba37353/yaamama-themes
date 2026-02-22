<?php
/**
 * Template Name: صفحة الأسئلة الشائعة
 * The template for displaying FAQ page
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

// Get dynamic content
$content = my_car_get_page_content('faq');

get_header();
?>

<main data-y="faq-main">
    <!-- Hero Section -->
    <div class="y-l-faq-hero" data-y="faq-hero">
        <div class="y-c-hero-content">
            <div class="y-c-hero-icon">
                <i class="fa-solid fa-circle-question"></i>
            </div>
            <h1 class="y-c-hero-title"><?php echo esc_html($content['hero_title']); ?></h1>
            <p class="y-c-hero-subtitle"><?php echo esc_html($content['hero_subtitle']); ?></p>
        </div>
    </div>

    <div class="y-u-container y-l-faq-container">
        <h2 class="y-c-faq-title" data-y="faq-title"><?php echo esc_html($content['main_title']); ?></h2>

        <div class="y-l-faq-accordion" data-y="faq-accordion">
            <?php foreach ($content['questions'] as $index => $qa): ?>
            <div class="y-c-accordion-item" data-y="faq-item-<?php echo $index + 1; ?>">
                <button class="y-c-accordion-toggle" aria-expanded="false" data-y="faq-toggle-<?php echo $index + 1; ?>">
                    <span><?php echo esc_html($qa['question']); ?></span>
                    <i class="fas fa-chevron-down" aria-hidden="true"></i>
                </button>
                <div class="y-c-accordion-panel" data-y="faq-panel-<?php echo $index + 1; ?>">
                    <p><?php echo esc_html($qa['answer']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <!-- Contact CTA -->
        <div class="y-c-faq-cta">
            <h3><?php echo esc_html($content['cta_title']); ?></h3>
            <p><?php echo esc_html($content['cta_text']); ?></p>
            <a href="<?php echo esc_url(home_url('/contact-us')); ?>" class="y-c-faq-cta-btn">
                <i class="fa-solid fa-headset"></i>
                <span><?php echo esc_html($content['cta_button']); ?></span>
            </a>
        </div>
    </div>
</main>

<?php
get_footer();
?>
