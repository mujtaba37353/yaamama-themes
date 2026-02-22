<?php
/**
 * Front page — markup from beauty-time/templates/layout/index.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$booking = home_url( '/booking' );
$onsale  = home_url( '/onsale' );
$services = home_url( '/services' );
$asset   = 'beauty_time_asset';
$demo_options = function_exists( 'beauty_demo_site_get_options' ) ? beauty_demo_site_get_options() : array();
$hero = isset( $demo_options['hero'] ) ? $demo_options['hero'] : array();
$mid_banner = isset( $demo_options['mid_banner'] ) ? $demo_options['mid_banner'] : array();
$categories = array();
$service_products = array();
$onsale_products = array();

if ( class_exists( 'WooCommerce' ) ) {
	$exclude = array();
	$default_cat = get_option( 'default_product_cat' );
	if ( $default_cat ) {
		$exclude[] = (int) $default_cat;
	}
	$onsale_term = get_term_by( 'slug', 'عروض-وباقات-بيوتي', 'product_cat' );
	if ( ! $onsale_term ) {
		$onsale_term = get_term_by( 'name', 'عروض وباقات بيوتي', 'product_cat' );
	}
	if ( $onsale_term && ! is_wp_error( $onsale_term ) ) {
		$exclude[] = (int) $onsale_term->term_id;
	}

	$categories = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'exclude'    => $exclude,
			'number'     => 6,
		)
	);

	$service_query = new WP_Query(
		array(
			'post_type'           => 'product',
			'post_status'         => 'publish',
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => 1,
			'tax_query'           => $exclude ? array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $exclude,
					'operator' => 'NOT IN',
				),
			) : array(),
		)
	);
	if ( $service_query->have_posts() ) {
		while ( $service_query->have_posts() ) {
			$service_query->the_post();
			$service_products[] = wc_get_product( get_the_ID() );
		}
		wp_reset_postdata();
	}

	$sale_ids = wc_get_product_ids_on_sale();
	$sale_ids = array_filter( array_map( 'absint', $sale_ids ) );
	if ( $sale_ids ) {
		$onsale_query = new WP_Query(
			array(
				'post_type'           => 'product',
				'post_status'         => 'publish',
				'posts_per_page'      => 3,
				'ignore_sticky_posts' => 1,
				'post__in'            => $sale_ids,
				'orderby'             => 'post__in',
			)
		);
		if ( $onsale_query->have_posts() ) {
			while ( $onsale_query->have_posts() ) {
				$onsale_query->the_post();
				$onsale_products[] = wc_get_product( get_the_ID() );
			}
			wp_reset_postdata();
		}
	}
}

$category_assets = array(
	'العناية بالشعر'   => array( 'img' => 'services1.png' ),
	'العناية بالأظافر' => array( 'img' => 'services2.png' ),
	'العناية بالبشرة' => array( 'img' => 'services3.png' ),
	'المكياج'         => array( 'img' => 'services4.png' ),
	'العناية بالأطفال' => array( 'img' => 'services5.png' ),
	'المساج'          => array( 'img' => 'services6.png' ),
);

get_header();
?>
<main class="main-page">
  <section class="hero-section">
    <div class="container y-u-max-w-1200">
      <div class="content">
        <img src="<?php echo esc_url( $hero['text_image'] ?? beauty_time_asset( 'assets/hero-text.png' ) ); ?>" alt="">
        <p><?php echo esc_html( $hero['title'] ?? __( 'العناية بكم هى غايتنا', 'beauty-time-theme' ) ); ?></p>
        <p><?php echo esc_html( $hero['description'] ?? __( 'انغمسي في عالم الجمال والأناقة مع أفضل خدمات التجميل مع بيوتي تايم للتجميل', 'beauty-time-theme' ) ); ?></p>
        <a href="<?php echo esc_url( $hero['button_link'] ?? $booking ); ?>" class="btn">
          <img src="<?php echo esc_url( beauty_time_asset( 'assets/book-now.svg' ) ); ?>" alt="book-now">
          <?php echo esc_html( $hero['button_text'] ?? __( 'احجزي الآن', 'beauty-time-theme' ) ); ?>
        </a>
      </div>
      <img src="<?php echo esc_url( $hero['image'] ?? beauty_time_asset( 'assets/hero.png' ) ); ?>" alt="hero-img">
    </div>
  </section>

  <section class="products-section">
    <div class="container y-u-max-w-1200">
      <h2><?php esc_html_e( 'حجوزات صالون بيوتي', 'beauty-time-theme' ); ?></h2>
      <div class="products-grid">
        <?php if ( $service_products ) : ?>
          <?php foreach ( $service_products as $product ) : ?>
            <?php if ( ! $product ) { continue; } ?>
            <?php
              $product_id = $product->get_id();
              $image_id = $product->get_image_id();
              $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : beauty_time_asset( 'assets/book.png' );
              $product_title = $product->get_name();
              $booking_link = add_query_arg( array( 'product_id' => $product_id ), $booking );
            ?>
            <div class="product-card">
              <div class="product-img">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product_title ); ?>">
              </div>
              <p class="product-title"><?php echo esc_html( $product_title ); ?></p>
              <a href="<?php echo esc_url( $booking_link ); ?>" class="btn full"><img src="<?php echo esc_url( beauty_time_asset( 'assets/book-now.svg' ) ); ?>" alt="book-now"><?php esc_html_e( 'احجز الآن', 'beauty-time-theme' ); ?></a>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <?php for ( $i = 0; $i < 3; $i++ ) : ?>
          <div class="product-card">
            <div class="product-img">
              <img src="<?php echo esc_url( beauty_time_asset( 'assets/book.png' ) ); ?>" alt="">
            </div>
            <p class="product-title"><?php esc_html_e( 'حجز موعد خدمة بالصالون', 'beauty-time-theme' ); ?></p>
            <a href="<?php echo esc_url( $booking ); ?>" class="btn full"><img src="<?php echo esc_url( beauty_time_asset( 'assets/book-now.svg' ) ); ?>" alt="book-now"><?php esc_html_e( 'احجز الآن', 'beauty-time-theme' ); ?></a>
          </div>
          <?php endfor; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="services-section">
    <div class="container y-u-max-w-1200">
      <h2><?php esc_html_e( 'خدماتنا', 'beauty-time-theme' ); ?></h2>
      <div class="services-grid">
        <?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
          <?php foreach ( $categories as $category ) : ?>
            <?php
              $cat_name = $category->name;
              $cat_desc = $category->description ? $category->description : '';
              $cat_link = get_term_link( $category );
              if ( is_wp_error( $cat_link ) ) { continue; }
              $thumb_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
              $thumb_url = $thumb_id ? wp_get_attachment_image_url( $thumb_id, 'medium' ) : '';
              if ( ! $thumb_url && isset( $category_assets[ $cat_name ] ) ) {
              	$thumb_url = beauty_time_asset( 'assets/' . $category_assets[ $cat_name ]['img'] );
              }
            ?>
            <a href="<?php echo esc_url( $cat_link ); ?>">
              <div class="service-card">
                <?php if ( $thumb_url ) : ?>
                  <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $cat_name ); ?>">
                <?php endif; ?>
                <p><?php echo esc_html( $cat_name ); ?></p>
                <?php if ( $cat_desc ) : ?>
                  <p><?php echo esc_html( $cat_desc ); ?></p>
                <?php endif; ?>
              </div>
            </a>
          <?php endforeach; ?>
        <?php else : ?>
          <a href="<?php echo esc_url( $services ); ?>">
            <div class="service-card">
              <img src="<?php echo esc_url( beauty_time_asset( 'assets/services1.png' ) ); ?>" alt="">
              <p><?php esc_html_e( 'العناية بالشعر', 'beauty-time-theme' ); ?></p>
              <p><?php esc_html_e( 'دعي شعرك يتألق بأناقة وجمال مع خدماتنا لتصفيف وعناية الشعر.', 'beauty-time-theme' ); ?></p>
            </div>
          </a>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="panner-hero">
    <div class="container y-u-max-w-1200">
      <div class="cards">
        <?php
        $default_mid = array(
          array( 'icon' => 'fa-star', 'number' => '+75',  'label' => __( 'خدمات مميزة', 'beauty-time-theme' ) ),
          array( 'icon' => 'fa-spa',  'number' => '+100', 'label' => __( 'منتجات اصلية', 'beauty-time-theme' ) ),
          array( 'icon' => 'fa-leaf', 'number' => '+500', 'label' => __( 'منتجات طبيعية', 'beauty-time-theme' ) ),
          array( 'icon' => 'fa-award','number' => '+20',  'label' => __( 'سنوات خبرة', 'beauty-time-theme' ) ),
        );
        $mid_items = $mid_banner && is_array( $mid_banner ) ? $mid_banner : $default_mid;
        foreach ( $mid_items as $mid_item ) :
          $icon = ! empty( $mid_item['icon'] ) ? $mid_item['icon'] : 'fa-star';
          $number = $mid_item['number'] ?? '';
          $label = $mid_item['label'] ?? '';
          ?>
        <div class="card">
          <i class="fas <?php echo esc_attr( $icon ); ?>"></i>
          <p><?php echo esc_html( $number ); ?></p>
          <p class="last"><?php echo esc_html( $label ); ?></p>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <section class="products-section onsale-section">
    <div class="container y-u-max-w-1200">
      <h2><?php esc_html_e( 'عروض وباقات بيوتي', 'beauty-time-theme' ); ?></h2>
      <div class="products-grid">
        <?php if ( $onsale_products ) : ?>
          <?php foreach ( $onsale_products as $product ) : ?>
            <?php if ( ! $product ) { continue; } ?>
            <?php
              $product_id = $product->get_id();
              $image_id = $product->get_image_id();
              $image_url = $image_id ? wp_get_attachment_image_url( $image_id, 'large' ) : beauty_time_asset( 'assets/book.png' );
              $product_title = $product->get_name();
              $short_desc = wp_strip_all_tags( $product->get_short_description() );
              $features = array_filter( array_map( 'trim', preg_split( '/\r\n|\r|\n/', $short_desc ) ) );
              $features = array_slice( $features, 0, 4 );
              $price = $product->get_price();
              $regular = $product->get_regular_price();
              $booking_link = add_query_arg( array( 'product_id' => $product_id ), $booking );
            ?>
            <div class="product-card">
              <div class="product-img">
                <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $product_title ); ?>">
                <div class="infos" role="region" aria-label="<?php esc_attr_e( 'تفاصيل العرض', 'beauty-time-theme' ); ?>">
                  <h3 class="offer-title"><?php echo esc_html( $product_title ); ?></h3>
                  <ul class="info" role="list">
                    <?php foreach ( $features as $feature ) : ?>
                      <li><i class="fas fa-star" aria-hidden="true"></i><span><?php echo esc_html( $feature ); ?></span></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
                <div class="logo">
                  <img src="<?php echo esc_url( beauty_time_asset( 'assets/navbar-icon.png' ) ); ?>" alt="logo">
                </div>
              </div>
              <div class="price">
                <?php if ( '' !== $price ) : ?>
                  <p><?php echo esc_html( $price ); ?> <img src="<?php echo esc_url( beauty_time_asset( 'assets/ryal-prim.svg' ) ); ?>" alt="sar"></p>
                <?php endif; ?>
                <?php if ( '' !== $regular && $regular > $price ) : ?>
                  <p class="old-price"><?php echo esc_html( $regular ); ?> <img src="<?php echo esc_url( beauty_time_asset( 'assets/ryal-prim.svg' ) ); ?>" alt="sar"></p>
                <?php endif; ?>
              </div>
              <p class="product-title"><?php echo esc_html( $product_title ); ?></p>
              <a href="<?php echo esc_url( $booking_link ); ?>" class="btn full"><img src="<?php echo esc_url( beauty_time_asset( 'assets/book-now.svg' ) ); ?>" alt="book-now"><?php esc_html_e( 'احجز الآن', 'beauty-time-theme' ); ?></a>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <?php for ( $i = 0; $i < 3; $i++ ) : ?>
          <div class="product-card">
            <div class="product-img">
              <img src="<?php echo esc_url( beauty_time_asset( 'assets/book.png' ) ); ?>" alt="">
              <div class="infos" role="region" aria-label="<?php esc_attr_e( 'تفاصيل العرض', 'beauty-time-theme' ); ?>">
                <h3 class="offer-title"><?php esc_html_e( 'عرض بيوتي تايم', 'beauty-time-theme' ); ?></h3>
                <ul class="info" role="list">
                  <li><i class="fas fa-star" aria-hidden="true"></i><span><?php esc_html_e( 'جلسة لمعان وترطيب', 'beauty-time-theme' ); ?></span></li>
                  <li><i class="fas fa-cut" aria-hidden="true"></i><span><?php esc_html_e( 'قص احترافي', 'beauty-time-theme' ); ?></span></li>
                  <li><i class="fas fa-wind" aria-hidden="true"></i><span><?php esc_html_e( 'استشوار مع ويفي', 'beauty-time-theme' ); ?></span></li>
                  <li><i class="fas fa-clock" aria-hidden="true"></i><span><?php esc_html_e( 'المدة ساعة ونصف', 'beauty-time-theme' ); ?></span></li>
                </ul>
              </div>
              <div class="logo">
                <img src="<?php echo esc_url( beauty_time_asset( 'assets/navbar-icon.png' ) ); ?>" alt="logo">
              </div>
            </div>
            <div class="price">
              <p>250 <img src="<?php echo esc_url( beauty_time_asset( 'assets/ryal-prim.svg' ) ); ?>" alt="sar"></p>
              <p class="old-price">200 <img src="<?php echo esc_url( beauty_time_asset( 'assets/ryal-prim.svg' ) ); ?>" alt="sar"></p>
            </div>
            <p class="product-title"><?php esc_html_e( 'عرض التألق والأنوثة باشراق بيوتي', 'beauty-time-theme' ); ?></p>
            <a href="<?php echo esc_url( $booking ); ?>" class="btn full"><img src="<?php echo esc_url( beauty_time_asset( 'assets/book-now.svg' ) ); ?>" alt="book-now"><?php esc_html_e( 'احجز الآن', 'beauty-time-theme' ); ?></a>
          </div>
          <?php endfor; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <section class="opinions-section">
    <div class="container y-u-max-w-1200">
      <h2><?php esc_html_e( 'اراء العملاء', 'beauty-time-theme' ); ?></h2>
      <div class="opinions-grid">
        <?php
        $reviews = array();
        if ( class_exists( 'WooCommerce' ) ) {
        	$reviews = get_comments(
        		array(
        			'status'    => 'approve',
        			'type__in'  => array( 'review', 'comment' ),
        			'number'    => 3,
        			'post_type' => 'product',
        			'orderby'   => 'comment_date_gmt',
        			'order'     => 'DESC',
        		)
        	);
        }
        if ( $reviews ) :
        	foreach ( $reviews as $review ) :
        		$rating = (int) get_comment_meta( $review->comment_ID, 'rating', true );
        		$rating = max( 0, min( 5, $rating ) );
        		$product_name = $review->comment_post_ID ? get_the_title( $review->comment_post_ID ) : '';
        		?>
        <div class="opinion-card">
          <img src="<?php echo esc_url( beauty_time_asset( 'assets/decoration-top-left.png' ) ); ?>" alt="">
          <img src="<?php echo esc_url( beauty_time_asset( 'assets/decoration-bottom-right.png' ) ); ?>" alt="">
          <div class="content">
            <img src="<?php echo esc_url( beauty_time_asset( 'assets/fake-profile-img.png' ) ); ?>" alt="">
            <p class="title"><?php echo esc_html( $product_name ? $product_name : __( 'تقييم عميلة', 'beauty-time-theme' ) ); ?></p>
            <p><?php echo esc_html( $review->comment_content ); ?></p>
            <?php if ( $rating ) : ?>
            <p class="rating">
              <?php
              for ( $i = 1; $i <= 5; $i++ ) {
              	$star_class = $i <= $rating ? 'fas fa-star' : 'far fa-star';
              	echo '<i class="' . esc_attr( $star_class ) . '"></i>';
              }
              ?>
            </p>
            <?php endif; ?>
          </div>
        </div>
        <?php endforeach; ?>
        <?php else : ?>
          <?php for ( $i = 0; $i < 3; $i++ ) : ?>
          <div class="opinion-card">
            <img src="<?php echo esc_url( beauty_time_asset( 'assets/decoration-top-left.png' ) ); ?>" alt="">
            <img src="<?php echo esc_url( beauty_time_asset( 'assets/decoration-bottom-right.png' ) ); ?>" alt="">
            <div class="content">
              <img src="<?php echo esc_url( beauty_time_asset( 'assets/fake-profile-img.png' ) ); ?>" alt="">
              <p class="title"><?php esc_html_e( 'صالون متميز', 'beauty-time-theme' ); ?></p>
              <p><?php esc_html_e( 'صالون ماشاء الله علية وفريق عمل جدا محترف مايقدرون ولا اروع', 'beauty-time-theme' ); ?></p>
            </div>
          </div>
          <?php endfor; ?>
        <?php endif; ?>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
