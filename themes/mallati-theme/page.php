<?php
get_header();
?>
<main>
  <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
    <?php
    while (have_posts()) {
      the_post();
      the_content();
    }
    ?>
  </section>
</main>
<?php get_footer(); ?>
