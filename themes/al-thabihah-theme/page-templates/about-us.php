<?php
/*
Template Name: About Us
*/
get_header();
$about_settings = wp_parse_args(al_thabihah_get_option('al_thabihah_about_settings', array()), al_thabihah_default_about_settings());
$about_image = $about_settings['image_id'] ? wp_get_attachment_url($about_settings['image_id']) : al_thabihah_asset_uri('al-thabihah/assets/about-us.png');
?>

<main class="y-l-about-us-section">
    <div class="y-u-container y-l-about-us-container" data-y="about-us-content">

        <div class="y-l-about-content" data-y="about-text-content">
            <h1 class="y-c-about-title" data-y="about-title"><?php the_title(); ?></h1>
            <div class="y-c-about-description" data-y="about-description">
                <?php the_content(); ?>
            </div>
        </div>

        <div class="y-c-about-image" data-y="about-image-container">
            <img src="<?php echo esc_url($about_image); ?>" alt="رجل يقطع قطعة لحم" data-y="about-image">
        </div>

    </div>
</main>

<?php
get_footer();
