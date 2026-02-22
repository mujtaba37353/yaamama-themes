<?php
/**
 * Template Name: صفحة من نحن
 * The template for displaying About Us page
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

// Get dynamic content
$content = my_car_get_page_content('about-us');

get_header();
?>

<main data-y="main">
    <!-- Hero Section -->
    <div class="y-l-page-hero y-l-about-hero" data-y="about-us-hero">
        <div class="y-c-hero-content">
            <div class="y-c-hero-icon">
                <i class="fa-solid fa-building"></i>
            </div>
            <h1 class="y-c-hero-title"><?php echo esc_html($content['hero_title']); ?></h1>
            <p class="y-c-hero-subtitle"><?php echo esc_html($content['hero_subtitle']); ?></p>
        </div>
    </div>

    <div class="y-u-container">
        <!-- About Section -->
        <section class="y-l-about-section">
            <div class="y-c-about-content">
                <div class="y-c-about-text">
                    <h2>
                        <i class="fa-solid fa-info-circle"></i>
                        <?php echo esc_html($content['company_title']); ?>
                    </h2>
                    <?php 
                    $paragraphs = explode("\n\n", $content['company_description']);
                    foreach ($paragraphs as $paragraph): 
                        if (trim($paragraph)):
                    ?>
                    <p><?php echo esc_html(trim($paragraph)); ?></p>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
                <div class="y-c-about-image">
                    <div class="y-c-about-image-placeholder">
                        <i class="fa-solid fa-car-side"></i>
                    </div>
                </div>
            </div>
        </section>

        <!-- Vision & Mission Section -->
        <section class="y-l-vision-section">
            <div class="y-c-vision-card">
                <div class="y-c-vision-icon">
                    <i class="fa-solid fa-eye"></i>
                </div>
                <h3><?php echo esc_html($content['vision_title']); ?></h3>
                <p><?php echo esc_html($content['vision_text']); ?></p>
            </div>
            <div class="y-c-vision-card">
                <div class="y-c-vision-icon y-c-mission-icon">
                    <i class="fa-solid fa-bullseye"></i>
                </div>
                <h3><?php echo esc_html($content['mission_title']); ?></h3>
                <p><?php echo esc_html($content['mission_text']); ?></p>
            </div>
        </section>

        <!-- Values Section -->
        <section class="y-l-values-section">
            <h2 class="y-c-section-title">
                <i class="fa-solid fa-star"></i>
                قيمنا
            </h2>
            <div class="y-l-values-grid">
                <?php foreach ($content['values'] as $value): ?>
                <div class="y-c-value-item">
                    <div class="y-c-value-icon-container">
                        <i class="fa-solid <?php echo esc_attr($value['icon']); ?>"></i>
                    </div>
                    <h3><?php echo esc_html($value['title']); ?></h3>
                    <p><?php echo esc_html($value['description']); ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Stats Section -->
        <section class="y-l-stats-section">
            <?php foreach ($content['stats'] as $stat): ?>
            <div class="y-c-stat-item">
                <div class="y-c-stat-number"><?php echo esc_html($stat['number']); ?></div>
                <div class="y-c-stat-label"><?php echo esc_html($stat['label']); ?></div>
            </div>
            <?php endforeach; ?>
        </section>
    </div>
</main>

<?php
get_footer();
?>
