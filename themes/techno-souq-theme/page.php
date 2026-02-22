<?php
/**
 * The template for displaying all pages
 *
 * @package TechnoSouqTheme
 */

get_header();
?>

<main id="main" class="site-main">
    <?php
    if (have_posts()) :
        while (have_posts()) :
            the_post();
            ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php
                // For WooCommerce account page, don't show title and content wrapper
                // WooCommerce will handle the content via shortcode
                if (!is_account_page()) :
                    ?>
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title">', '</h1>'); ?>
                    </header>
                    <?php
                endif;
                ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(array(
                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'techno-souq-theme'),
                        'after'  => '</div>',
                    ));
                    ?>
                </div>
            </article>
            <?php
        endwhile;
    else :
        ?>
        <p><?php esc_html_e('No content found.', 'techno-souq-theme'); ?></p>
        <?php
    endif;
    ?>
</main>

<?php
get_footer();