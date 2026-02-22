<?php
get_header();
?>

<main class="y-l-page">
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article <?php post_class('y-c-article'); ?>>
                <h1 class="y-c-page-title"><?php the_title(); ?></h1>
                <div class="y-c-page-content">
                    <?php the_content(); ?>
                </div>
            </article>
        <?php endwhile; ?>
    <?php else : ?>
        <p class="y-c-empty-state"><?php esc_html_e('لا توجد نتائج.', 'al-thabihah-theme'); ?></p>
    <?php endif; ?>
</main>

<?php
get_footer();
