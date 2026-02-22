<?php
/**
 * Empty Cart - Elegance
 */
defined( 'ABSPATH' ) || exit;

$home_url = home_url( '/' );
?>
<main>
  <section class="panner">
    <h1 class="y-u-text-center">عربة التسوق</h1>
  </section>
  <section class="container y-u-max-w-1200">
    <div class="empty-state-container">
      <div class="empty-state">
        <div class="empty-icon">
          <i class="fas fa-shopping-basket"></i>
        </div>
        <h2>عربة التسوق فارغة</h2>
        <a href="<?php echo esc_url( $home_url ); ?>" class="btn main-button">العودة للرئيسية</a>
      </div>
    </div>
  </section>
</main>
