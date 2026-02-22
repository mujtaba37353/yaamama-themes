<?php
/**
 * Template Name: صفحة سياسات
 * للاستخدام مع: سياسة الخصوصية، سياسة الاستخدام، الاستبدال والاسترجاع
 */
get_header();
?>
<main>
  <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
    <div class="header y-u-flex y-u-justify-between y-u-items-center y-u-p-y-56 y-u-p-t-24">
      <h1 class="y-u-color-primary y-u-text-2xl"><?php the_title(); ?></h1>
    </div>
    <div class="content y-u-flex y-u-justify-center y-u-flex-col y-u-gap-24">
      <?php
      while (have_posts()) {
        the_post();
        the_content();
      }
      ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
