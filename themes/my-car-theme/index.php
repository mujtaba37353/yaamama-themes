<?php
/**
 * The main template file
 *
 * @package MyCarTheme
 */

get_header();
?>

<main id="main" class="site-main">
    <?php
    if (have_posts()) {
        while (have_posts()) {
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                </header>

                <div class="entry-content">
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

        // Pagination
        the_posts_navigation();
    } else {
        ?>
        <section class="no-results not-found">
            <header class="page-header">
                <h1 class="page-title"><?php esc_html_e('لا توجد نتائج', 'my-car-theme'); ?></h1>
            </header>
            <div class="page-content">
                <p><?php esc_html_e('عذراً، لم يتم العثور على محتوى.', 'my-car-theme'); ?></p>
            </div>
        </section>
        <?php
    }
    ?>
</main>

<?php
get_footer();
