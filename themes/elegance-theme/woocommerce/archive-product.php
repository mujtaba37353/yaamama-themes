<?php
/**
 * WooCommerce Shop Archive - Elegance (مطابق لـ store.html + منتجات حقيقية)
 */
defined( 'ABSPATH' ) || exit;

elegance_enqueue_page_css( 'store' );
elegance_enqueue_component_css( array( 'products', 'breadcrumbs' ) );
$store_style_handle = 'elegance-store-template';
wp_enqueue_style(
	$store_style_handle,
	ELEGANCE_ELEGANCE_URI . '/templates/store/store.css',
	array( 'elegance-footer', 'elegance-buttons', 'elegance-panner', 'elegance-breadcrumbs' ),
	defined( 'ELEGANCE_THEME_VERSION' ) ? ELEGANCE_THEME_VERSION : '1.0.0'
);
$store_grid_css = '@media (min-width: 769px){section.store-section{display:grid !important;grid-template-columns:280px 1fr;grid-template-rows:auto 1fr auto;gap:24px;}section.store-section>.store-header{grid-column:1/-1;grid-row:1;}section.store-section>.sidebar{grid-column:1;grid-row:2;}section.store-section>.grid{grid-column:2;grid-row:2;}section.store-section>.store-pagination-wrap{grid-column:1/-1;grid-row:3;}}';
wp_add_inline_style( $store_style_handle, $store_grid_css );

get_header();

$assets = ELEGANCE_ELEGANCE_URI . '/assets';
$store_css_href = ELEGANCE_ELEGANCE_URI . '/templates/store/store.css?ver=' . ( defined( 'ELEGANCE_THEME_VERSION' ) ? ELEGANCE_THEME_VERSION : '1.0.0' );
$shop_url = function_exists( 'wc_get_page_permalink' ) ? get_permalink( wc_get_page_id( 'shop' ) ) : home_url( '/shop/' );
$shop_permalink = ( function_exists( 'is_product_category' ) && is_product_category() && function_exists( 'get_term_link' ) ) ? get_term_link( get_queried_object() ) : $shop_url;
if ( is_wp_error( $shop_permalink ) ) {
	$shop_permalink = $shop_url;
}
?>
<link rel="stylesheet" id="elegance-store-fallback-css" href="<?php echo esc_url( $store_css_href ); ?>" />
<main>
  <section class="panner">
    <h1 class="y-u-text-center">اكتشف منتجاتنا</h1>
  </section>

  <section class="container y-u-max-w-1200 store-section">
    <div class="store-header">
      <div class="breadcrumbs">
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a>
        <span> &gt; </span>
        <span class="breadcrumbs-current"><?php echo ( function_exists( 'is_product_category' ) && is_product_category() ) ? esc_html( get_queried_object()->name ) : 'المتجر'; ?></span>
      </div>
      <div class="sort-dropdown-wrapper">
        <button type="button" class="sort-dropdown-btn">
          <span>تصنيف حسب</span>
          <i class="fas fa-chevron-up"></i>
        </button>
        <ul class="sort-dropdown-list" aria-hidden="true" style="display: none;">
          <li><a href="<?php echo esc_url( add_query_arg( 'orderby', 'date', $shop_permalink ) ); ?>" data-sort="newest">الأحدث</a></li>
          <li><a href="<?php echo esc_url( add_query_arg( 'orderby', 'popularity', $shop_permalink ) ); ?>" data-sort="bestselling">الأكثر مبيعًا</a></li>
          <li><a href="<?php echo esc_url( add_query_arg( 'orderby', 'price-desc', $shop_permalink ) ); ?>" data-sort="price-high">الأعلى سعرًا</a></li>
          <li><a href="<?php echo esc_url( add_query_arg( 'orderby', 'price', $shop_permalink ) ); ?>" data-sort="price-low">الأقل سعرًا</a></li>
        </ul>
      </div>
    </div>

    <div class="sidebar">
      <div class="filter-top">
        <button type="button" class="hide-filter-btn">
          <span>إخفاء الفلتر</span>
          <i class="fas fa-filter"></i>
        </button>
      </div>

      <a href="<?php echo esc_url( $shop_url ); ?>" class="clear-all-link">مسح الكل</a>

      <div class="filter-list">
        <?php
        // عرض التصنيفات الحقيقية من WooCommerce (رئيسية وفرعية) — استبعاد Uncategorized
        if ( taxonomy_exists( 'product_cat' ) ) {
          $exclude_uncategorized = array();
          $default_cat_id        = (int) get_option( 'default_product_cat', 0 );
          if ( $default_cat_id > 0 ) {
            $exclude_uncategorized[] = $default_cat_id;
          }
          $parent_terms = get_terms( array(
            'taxonomy'   => 'product_cat',
            'parent'     => 0,
            'hide_empty' => false,
            'orderby'    => 'name',
            'order'      => 'ASC',
            'exclude'    => $exclude_uncategorized,
          ) );
          if ( ! is_wp_error( $parent_terms ) && ! empty( $parent_terms ) ) {
            foreach ( $parent_terms as $parent_term ) {
              if ( $parent_term->slug === 'uncategorized' ) {
                continue;
              }
              $child_terms = get_terms( array(
                'taxonomy'   => 'product_cat',
                'parent'     => (int) $parent_term->term_id,
                'hide_empty' => false,
                'orderby'    => 'name',
                'order'      => 'ASC',
              ) );
              $parent_link = get_term_link( $parent_term );
              $has_children = ! is_wp_error( $child_terms ) && ! empty( $child_terms );
              ?>
              <div class="filter-category" data-category="<?php echo esc_attr( $parent_term->slug ); ?>">
                <?php if ( $has_children ) : ?>
                  <button type="button" class="filter-category-btn" aria-expanded="false" aria-controls="filter-sub-<?php echo esc_attr( $parent_term->term_id ); ?>">
                    <?php echo esc_html( $parent_term->name ); ?>
                  </button>
                  <div class="filter-subcategories" id="filter-sub-<?php echo esc_attr( $parent_term->term_id ); ?>" role="region" aria-label="<?php echo esc_attr( $parent_term->name ); ?>" aria-hidden="true">
                    <?php
                    foreach ( $child_terms as $child_term ) {
                      $child_link = get_term_link( $child_term );
                      if ( is_wp_error( $child_link ) ) {
                        continue;
                      }
                      ?>
                      <div class="filter-sub-option">
                        <a href="<?php echo esc_url( $child_link ); ?>" class="filter-sub-label"><?php echo esc_html( $child_term->name ); ?></a>
                      </div>
                    <?php } ?>
                  </div>
                <?php else : ?>
                  <a href="<?php echo esc_url( is_wp_error( $parent_link ) ? $shop_url : $parent_link ); ?>" class="filter-category-btn"><?php echo esc_html( $parent_term->name ); ?></a>
                <?php endif; ?>
              </div>
              <?php
            }
          }
        }
        ?>

        <div class="price-range-section">
          <div class="price-range-header">
            <span class="filter-label">نطاق السعر</span>
          </div>
          <div class="price-range-slider-container active">
            <div class="slider-track" id="price-range-track">
              <div class="slider-range-fill" id="price-range-fill" aria-hidden="true"></div>
              <input type="range" id="price-range-min" min="0" max="3000" value="0" class="range-slider range-min">
              <input type="range" id="price-range-max" min="0" max="3000" value="3000" class="range-slider range-max">
            </div>
            <div class="slider-labels">
              <span id="price-min-label">0</span>
              <span id="price-max-label">3000</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <?php if ( function_exists( 'woocommerce_product_loop' ) && woocommerce_product_loop() ) : ?>
      <ul class="grid">
        <?php
        while ( have_posts() ) {
          the_post();
          wc_get_template_part( 'content', 'product' );
        }
        ?>
      </ul>
      <?php
      if ( function_exists( 'woocommerce_pagination' ) ) {
        echo '<div class="store-pagination-wrap">';
        woocommerce_pagination();
        echo '</div>';
      }
      ?>
    <?php else : ?>
      <?php
      if ( function_exists( 'wc_get_template' ) ) {
        wc_get_template( 'loop/no-products-found.php' );
      } else {
        echo '<p class="y-u-text-center">لا توجد منتجات.</p>';
      }
      ?>
    <?php endif; ?>
  </section>
</main>
<?php
get_footer();
