<?php
/**
 * Template Name: About Us
 */
get_header();
?>
<main>
  <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
    <div class="header y-u-flex y-u-justify-between y-u-items-center y-u-p-y-56 y-u-p-t-24">
      <h1 class="y-u-color-primary y-u-text-2xl"><?php esc_html_e('من نحن', 'mallati-theme'); ?></h1>
    </div>
    <div class="content y-u-flex y-u-justify-center y-u-flex-col y-u-gap-24">
      <?php
      if (have_posts()) :
        while (have_posts()) :
          the_post();
          the_content();
        endwhile;
      else :
        ?>
        <div class="slide">
          <p class="y-u-color-muted"><?php esc_html_e('نحن نؤمن أن الجمال يبدأ من التفاصيل الصغيرة. نقدم لعملائنا أجود أنواع المنتجات التي تجمع بين الفخامة والجودة.', 'mallati-theme'); ?></p>
          <p class="y-u-color-muted"><?php esc_html_e('رؤيتنا هي أن نكون الوجهة الأولى لكل من يبحث عن منتجات أصلية وموثوقة.', 'mallati-theme'); ?></p>
        </div>
        <div class="slide">
          <h2 class="y-u-color-primary y-u-text-xl"><?php esc_html_e('رؤيتنا', 'mallati-theme'); ?></h2>
          <p class="y-u-color-muted"><?php esc_html_e('أن نكون الوجهة الأولى لكل من يبحث عن منتجات أصلية وموثوقة تضيف لمسة خاصة لحياته اليومية.', 'mallati-theme'); ?></p>
        </div>
        <div class="slide">
          <h2 class="y-u-color-primary y-u-text-xl"><?php esc_html_e('قيمنا', 'mallati-theme'); ?></h2>
          <p class="y-u-color-muted"><?php esc_html_e('1- الجودة والأصالة: نحرص على توفير منتجات أصلية ومضمونة.', 'mallati-theme'); ?></p>
          <p class="y-u-color-muted"><?php esc_html_e('2- تجربة تسوق مميزة: واجهة سهلة ودعم عملاء سريع.', 'mallati-theme'); ?></p>
          <p class="y-u-color-muted"><?php esc_html_e('3- لمسة فخامة بأسعار مناسبة: نمنح عملاءنا أفضل قيمة مقابل السعر.', 'mallati-theme'); ?></p>
        </div>
        <?php
      endif;
      ?>
    </div>
  </section>
</main>
<?php get_footer(); ?>
