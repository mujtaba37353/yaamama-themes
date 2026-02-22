<?php
/**
 * The template for displaying all pages
 *
 * @package MyCarTheme
 */

get_header();
?>

<main id="main" class="site-main y-l-page-main">
    <?php
    while (have_posts()) {
        the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('y-c-page-article'); ?>>
            <div class="entry-content y-c-page-content">
                <?php
                the_content();
                wp_link_pages(array(
                    'before' => '<div class="page-links">' . esc_html__('Pages:', 'my-car-theme'),
                    'after'  => '</div>',
                ));
                ?>
            </div>
        </article>
        <?php
    }
    ?>
</main>

<?php
get_footer();
