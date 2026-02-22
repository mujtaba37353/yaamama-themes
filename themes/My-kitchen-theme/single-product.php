<?php
/**
 * قالب صفحة المنتج المنفردة — تصميم ثابت (لا يعرض منتجات حقيقية)
 * التصميم مأخوذ من dark-theme: single product layout
 */
get_header();

$assets = defined('MYK_ASSETS_URI') ? MYK_ASSETS_URI : get_template_directory_uri() . '/my-kitchen';
$img_main = $assets . '/assets/product.png';
$img_thumb = $img_main;
?>

<header data-y="design-header"></header>

<main data-y="main">
  <div class="y-main-container">
    <div data-y="breadcrumb"></div>
    <div data-y="single-product">
      <div class="single-product">
        <div class="thumbnails">
          <img src="<?php echo esc_url($img_thumb); ?>" alt="" />
          <img src="<?php echo esc_url($img_thumb); ?>" alt="" />
          <img src="<?php echo esc_url($img_thumb); ?>" alt="" />
        </div>
        <div class="single-product-main-img">
          <img src="<?php echo esc_url($img_main); ?>" alt="كنبة مودرن" />
        </div>
        <div class="details">
          <h3>كنبة مودرن</h3>
          <p class="code">#786A5</p>
          <h2 class="price">350 ر.س</h2>
          <div class="actions">
            <div class="qnt">
              <button type="button" class="btn btn-outline" aria-label="تقليل">-</button>
              <span>1</span>
              <button type="button" class="btn btn-outline" aria-label="زيادة">+</button>
            </div>
            <button type="button" class="btn btn-primary">أضف إلى السلة</button>
            <button type="button" class="btn btn-primary">شراء الآن</button>
          </div>
        </div>
      </div>
    </div>
    <div class="pro">
      <h2 class="section-title">منتجات أخرى</h2>
      <products data-y="products"></products>
    </div>
  </div>
</main>

<?php get_footer(); ?>
