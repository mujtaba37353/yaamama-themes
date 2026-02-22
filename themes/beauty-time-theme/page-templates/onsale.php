<?php
/**
 * Template Name: باقات توفيرية (On Sale / Bundles)
 * Placeholder — WC archive override later. Markup from beauty-time/templates/products/onsale.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$home   = home_url( '/' );
$booking = home_url( '/booking' );
get_header();
?>
<main>
  <section class="panner">
    <p><a href="<?php echo esc_url( $home ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a> / <?php esc_html_e( 'العروض', 'beauty-time-theme' ); ?></p>
  </section>
  <section class="products-section onsale-section">
    <div class="container y-u-max-w-1200">
      <h2><?php esc_html_e( 'عروض وباقات بيوتي', 'beauty-time-theme' ); ?></h2>
      <div class="products-grid">
        <?php
        if ( class_exists( 'WooCommerce' ) ) {
			$sale_ids = wc_get_product_ids_on_sale();
			$sale_ids = array_filter( array_map( 'absint', $sale_ids ) );

			if ( empty( $sale_ids ) ) {
				echo '<p>' . esc_html__( 'لا توجد منتجات بخصم حالياً.', 'beauty-time-theme' ) . '</p>';
			} else {
				$query = new WP_Query(
					array(
						'post_type'           => 'product',
						'post_status'         => 'publish',
						'posts_per_page'      => 12,
						'ignore_sticky_posts' => 1,
						'post__in'            => $sale_ids,
						'orderby'             => 'post__in',
					)
				);

				if ( $query->have_posts() ) {
					while ( $query->have_posts() ) {
						$query->the_post();
						wc_get_template_part( 'content', 'product' );
					}
					wp_reset_postdata();
				} else {
					echo '<p>' . esc_html__( 'لا توجد منتجات بخصم حالياً.', 'beauty-time-theme' ) . '</p>';
				}
			}
		} else {
			echo '<p>' . esc_html__( 'ووكومرس غير مفعل حالياً.', 'beauty-time-theme' ) . '</p>';
		}
		?>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
