<?php
/**
 * Template for About Us page
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// Enqueue about-us specific styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-about-us', $techno_souq_path . '/templates/about-us/y-about-us.css', array(
    'techno-souq-header',
    'techno-souq-footer'
), $theme_version);

// Get about us content from demo content system
$defaults = techno_souq_get_default_demo_content();
$about_title = techno_souq_get_demo_content('about_title', $defaults['about_title']);
$about_paragraph_1 = techno_souq_get_demo_content('about_paragraph_1', $defaults['about_paragraph_1']);
$about_paragraph_2 = techno_souq_get_demo_content('about_paragraph_2', $defaults['about_paragraph_2']);
$about_paragraph_3 = techno_souq_get_demo_content('about_paragraph_3', $defaults['about_paragraph_3']);
$vision_title = techno_souq_get_demo_content('vision_title', $defaults['vision_title']);
$vision_text = techno_souq_get_demo_content('vision_text', $defaults['vision_text']);
$values_title = techno_souq_get_demo_content('values_title', $defaults['values_title']);
$values_item_1 = techno_souq_get_demo_content('values_item_1', $defaults['values_item_1']);
$values_item_2 = techno_souq_get_demo_content('values_item_2', $defaults['values_item_2']);
$values_item_3 = techno_souq_get_demo_content('values_item_3', $defaults['values_item_3']);
$values_item_4 = techno_souq_get_demo_content('values_item_4', $defaults['values_item_4']);
?>

<main data-y="about-main">
    <section class="y-l-container y-c-section" data-y="about-container">
        <br data-y="about-title-top-separator">
        <h2 class="y-c-section__title" data-y="about-title"><?php echo esc_html($about_title); ?></h2>
        <br data-y="about-title-separator">
        <div class="y-c-section__content" data-y="about-content">
            <p data-y="about-paragraph-1"><?php echo wp_kses_post($about_paragraph_1); ?></p>
            <br data-y="about-paragraph-1-separator">
            <p data-y="about-paragraph-2"><?php echo wp_kses_post($about_paragraph_2); ?></p>
            <br data-y="about-paragraph-2-separator">
            <p data-y="about-paragraph-3"><?php echo wp_kses_post($about_paragraph_3); ?></p>
        </div>
        <br data-y="vision-section-separator">
        <h2 data-y="vision-title"><?php echo esc_html($vision_title); ?></h2>
        <br data-y="vision-title-separator">
        <div class="y-c-section__content" data-y="vision-content">
            <p data-y="vision-paragraph"><?php echo wp_kses_post($vision_text); ?></p>
        </div>
        <br data-y="values-section-separator">
        <h2 data-y="values-title"><?php echo esc_html($values_title); ?></h2>
        <br data-y="values-title-separator">
        <div class="y-c-section__content" data-y="values-content">
            <p data-y="values-item-1"><?php echo esc_html($values_item_1); ?></p>
            <p data-y="values-item-2"><?php echo esc_html($values_item_2); ?></p>
            <p data-y="values-item-3"><?php echo esc_html($values_item_3); ?></p>
            <p data-y="values-item-4"><?php echo esc_html($values_item_4); ?></p>
        </div>
    </section>
    <br data-y="bottom-separator-1">
    <br data-y="bottom-separator-2">
</main>

<?php
get_footer();
