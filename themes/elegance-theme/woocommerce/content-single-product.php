<?php
/**
 * WooCommerce Single Product Content - Elegance
 */
defined( 'ABSPATH' ) || exit;

global $product;

$assets = ELEGANCE_ELEGANCE_URI . '/assets';
$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );
$cart_icon = $assets . '/add-to-cart.svg';
$ryal_icon = $assets . '/ryal.svg';
$ryal_red_icon = $assets . '/ryal-red.svg';

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
  echo get_the_password_form();
  return;
}

$image_id    = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();
$thumb_ids   = array_filter( array_merge( array( $image_id ), $gallery_ids ) );
$thumb_ids   = array_slice( array_unique( $thumb_ids ), 0, 4 );
if ( empty( $thumb_ids ) && $image_id ) {
  $thumb_ids = array( $image_id );
}
$fallback_id = $image_id ? $image_id : 0;
while ( count( $thumb_ids ) < 4 ) {
  $thumb_ids[] = $fallback_id;
}
$main_url = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_single' ) : wc_placeholder_img_src();

$color_terms = wc_get_product_terms( $product->get_id(), 'pa_color', array( 'fields' => 'all' ) );
$size_terms  = wc_get_product_terms( $product->get_id(), 'pa_size', array( 'fields' => 'all' ) );
$related_ids = wc_get_related_products( $product->get_id(), 3 );
if ( empty( $related_ids ) ) {
  $related_products = wc_get_products(
    array(
      'status'  => 'publish',
      'limit'   => 3,
      'exclude' => array( $product->get_id() ),
      'orderby' => 'date',
      'order'   => 'DESC',
    )
  );
  $related_ids = array_map(
    static function ( $p ) {
      return $p instanceof WC_Product ? $p->get_id() : 0;
    },
    $related_products
  );
  $related_ids = array_filter( $related_ids );
}

$tabby_img_uri  = $assets . '/taby-pay.png';
$tamara_img_uri = $assets . '/tmara-pay.png';
$fallback_payment_img_uri = $assets . '/payment-methods.png';

if ( ! file_exists( get_template_directory() . '/elegance/assets/taby-pay.png' ) ) {
  $tabby_img_uri = $fallback_payment_img_uri;
}
if ( ! file_exists( get_template_directory() . '/elegance/assets/tmara-pay.png' ) ) {
  $tamara_img_uri = $fallback_payment_img_uri;
}

$short_description = trim( (string) $product->get_short_description() );
if ( $short_description === '' ) {
  $short_description = trim( wp_strip_all_tags( (string) get_the_content() ) );
}

$format_price = static function ( $price_value ) {
  return number_format_i18n( (float) $price_value, 0 );
};

$regular_price_value = (float) $product->get_regular_price();
$current_price_value = (float) $product->get_price();
$display_regular = $regular_price_value > 0 ? $format_price( wc_get_price_to_display( $product, array( 'price' => $regular_price_value ) ) ) : '';
$display_current = $current_price_value > 0 ? $format_price( wc_get_price_to_display( $product, array( 'price' => $current_price_value ) ) ) : '';

if ( empty( $color_terms ) ) {
  $color_terms = array(
    (object) array( 'slug' => 'black', 'name' => 'black' ),
    (object) array( 'slug' => 'red', 'name' => 'red' ),
    (object) array( 'slug' => 'blue', 'name' => 'blue' ),
    (object) array( 'slug' => 'green', 'name' => 'green' ),
  );
}
if ( empty( $size_terms ) ) {
  $size_terms = array(
    (object) array( 'slug' => 's', 'name' => 'S' ),
    (object) array( 'slug' => 'm', 'name' => 'M' ),
    (object) array( 'slug' => 'l', 'name' => 'L' ),
    (object) array( 'slug' => 'xl', 'name' => 'XL' ),
  );
}
?>
<section id="product-<?php the_ID(); ?>" <?php wc_product_class( 'details-section', $product ); ?>>
  <div class="container y-u-max-w-1200">
    <div class="breadcrumbs">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fa-solid fa-house"></i></a>
      / <a href="<?php echo esc_url( $shop_url ); ?>">المتجر</a>
      / <p><?php the_title(); ?></p>
    </div>
  </div>

  <div class="container y-u-max-w-1200">
    <div class="imgs-section">
      <div class="imgs">
        <?php foreach ( $thumb_ids as $index => $tid ) : ?>
          <?php
          $thumb_url = $tid ? wp_get_attachment_image_url( $tid, 'woocommerce_thumbnail' ) : $main_url;
          $full_url  = $tid ? wp_get_attachment_image_url( $tid, 'woocommerce_single' ) : $main_url;
          ?>
          <button type="button" class="elegance-thumb-btn<?php echo 0 === $index ? ' is-active' : ''; ?>" data-main-image="<?php echo esc_url( $full_url ?: $main_url ); ?>" aria-label="<?php esc_attr_e( 'عرض صورة المنتج', 'elegance' ); ?>">
            <img src="<?php echo esc_url( $thumb_url ?: $main_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
          </button>
        <?php endforeach; ?>
      </div>
      <div class="main-img">
        <img class="elegance-main-product-image" src="<?php echo esc_url( $main_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>">
        <i class="heart fa-regular fa-heart product-card-favorite" role="button" tabindex="0" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>" aria-label="أضف إلى المفضلة"></i>
      </div>
    </div>

    <div class="content">
      <div class="header">
        <h3><?php the_title(); ?></h3>
      </div>
      <?php if ( $short_description !== '' ) : ?>
        <p><?php echo esc_html( $short_description ); ?></p>
      <?php endif; ?>
      <div class="price">
        <p class="price-value">
          <?php if ( $display_regular !== '' && $product->is_on_sale() ) : ?>
            <span class="grey-ryal"><?php echo esc_html( $display_regular ); ?> <img src="<?php echo esc_url( $ryal_red_icon ); ?>" alt=""></span>
          <?php endif; ?>
          <span><?php echo esc_html( $display_current !== '' ? $display_current : $format_price( wc_get_price_to_display( $product ) ) ); ?> <img src="<?php echo esc_url( $ryal_icon ); ?>" alt=""></span>
        </p>
      </div>

      <div class="color">
        <p>اللون</p>
        <div class="color-grid">
          <?php
          foreach ( array_values( $color_terms ) as $index => $color_term ) :
            $slug = strtolower( (string) $color_term->slug );
            $color_map = array(
              'black' => '#000000',
              'red'   => '#ef4444',
              'blue'  => '#3b82f6',
              'green' => '#22c55e',
              'white' => '#ffffff',
            );
            $color_value = isset( $color_map[ $slug ] ) ? $color_map[ $slug ] : '';
            ?>
            <label for="color-<?php echo esc_attr( $index + 1 ); ?>">
              <input type="radio" name="elegance-color" id="color-<?php echo esc_attr( $index + 1 ); ?>" value="<?php echo esc_attr( $slug ); ?>" <?php checked( 0, $index ); ?><?php echo $color_value ? ' style="background-color:' . esc_attr( $color_value ) . ';"' : ''; ?>>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="color">
        <p>المقاس</p>
        <div class="sizes-grid">
          <?php foreach ( array_values( $size_terms ) as $index => $size_term ) : ?>
            <label for="size-<?php echo esc_attr( $index + 1 ); ?>">
              <input class="size-item" type="radio" name="elegance-size" value="<?php echo esc_attr( strtoupper( (string) $size_term->name ) ); ?>" id="size-<?php echo esc_attr( $index + 1 ); ?>" <?php checked( 0, $index ); ?>>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>
      <form class="cart" method="post" enctype="multipart/form-data">
        <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>">
        <div class="buttons">
          <div class="quantity">
            <?php $min_quantity = max( 1, (int) $product->get_min_purchase_quantity() ); ?>
            <button type="button" class="elegance-qty-btn" data-step="-1" aria-label="تقليل الكمية">-</button>
            <input type="number" name="quantity" value="<?php echo esc_attr( $min_quantity ); ?>" min="<?php echo esc_attr( $min_quantity ); ?>" max="<?php echo esc_attr( $product->get_max_purchase_quantity() > 0 ? (int) $product->get_max_purchase_quantity() : 9999 ); ?>" inputmode="numeric">
            <button type="button" class="elegance-qty-btn" data-step="1" aria-label="زيادة الكمية">+</button>
          </div>
          <button type="submit" class="btn main-button single_add_to_cart_button">اضافة للسلة <img src="<?php echo esc_url( $cart_icon ); ?>" alt=""></button>
        </div>
      </form>
      <?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
    </div>
  </div>
</section>
<section class="payment-methods">
  <div class="container y-u-max-w-1200">
    <div class="payment-method">
      <p>
        قسِّمها على 5 دفعات بقيمة 10 ر.س بدون فوائد.
        <br>
        متوافق مع أحكام الشريعة الإسلامية.
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">لمعرفة المزيد</a>
      </p>
      <img src="<?php echo esc_url( $tabby_img_uri ); ?>" alt="Tabby">
    </div>
    <div class="payment-method">
      <p>
        أو قسّم فاتورتك بقيمة 10 ر.س على 5 دفعات بدون رسوم تأخير.
        <br>
        متوافقة مع الشريعة الإسلامية.
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>">لمعرفة المزيد</a>
      </p>
      <img src="<?php echo esc_url( $tamara_img_uri ); ?>" alt="Tamara">
    </div>
  </div>
</section>
<?php if ( ! empty( $related_ids ) ) : ?>
  <section class="container y-u-max-w-1200 discount-section">
    <h2>منتجات مشابهة</h2>
    <ul class="grid">
      <?php foreach ( $related_ids as $related_id ) : ?>
        <?php
        $related_product = wc_get_product( $related_id );
        if ( ! $related_product ) {
          continue;
        }
        ?>
        <li class="card">
          <div class="img product-card-media">
            <a href="<?php echo esc_url( get_permalink( $related_id ) ); ?>">
              <?php echo wp_kses_post( $related_product->get_image( 'woocommerce_thumbnail' ) ); ?>
            </a>
            <button type="button" class="product-card-action product-card-favorite" data-product-id="<?php echo esc_attr( $related_id ); ?>" aria-label="أضف إلى المفضلة">
              <i class="fa-regular fa-heart"></i>
            </button>
            <div class="product-card-action product-card-cart">
              <form class="elegance-add-to-cart-form" method="get" action="">
                <input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $related_id ); ?>">
                <button type="submit" class="elegance-add-to-cart-btn add_to_cart_button" data-product_id="<?php echo esc_attr( $related_id ); ?>" aria-label="<?php esc_attr_e( 'إضافة إلى السلة', 'elegance' ); ?>">
                  <img src="<?php echo esc_url( $cart_icon ); ?>" alt="<?php esc_attr_e( 'إضافة إلى السلة', 'elegance' ); ?>">
                </button>
              </form>
            </div>
          </div>
          <div class="content">
            <a href="<?php echo esc_url( get_permalink( $related_id ) ); ?>">
              <p><?php echo esc_html( $related_product->get_name() ); ?></p>
            </a>
            <div class="left">
              <?php echo wp_kses_post( $related_product->get_price_html() ); ?>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </section>
<?php endif; ?>
<?php do_action( 'woocommerce_after_single_product' ); ?>
