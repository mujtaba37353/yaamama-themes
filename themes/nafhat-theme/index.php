<?php
/**
 * The main template file
 *
 * @package Nafhat
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main">
    <div class="container y-u-max-w-1200">
        <?php
        if (have_posts()) :
            while (have_posts()) :
                the_post();
                get_template_part('template-parts/content', get_post_type());
            endwhile;
            
            the_posts_navigation();
        else :
            get_template_part('template-parts/content', 'none');
        endif;
        ?>
    </div>
</main>

<?php
get_footer();
