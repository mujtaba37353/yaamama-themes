<?php get_header(); ?>

<main data-y="main">
  <div class="main-container">
    <?php if (have_posts()) : ?>
      <?php while (have_posts()) : the_post(); ?>
        <article <?php post_class(); ?>>
          <h1><?php the_title(); ?></h1>
          <div class="entry-content">
            <?php the_content(); ?>
          </div>
        </article>
      <?php endwhile; ?>
    <?php else : ?>
      <p><?php esc_html_e('لا توجد محتويات بعد.', 'my-kitchen'); ?></p>
    <?php endif; ?>
  </div>
</main>

<?php get_footer(); ?>
