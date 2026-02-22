<?php
/**
 * قالب محتوى المنتج المنفرد — تصميم dark-theme (شبكة: مصغرات | صورة رئيسية | تفاصيل)
 *
 * @see https://woocommerce.com/document/template-structure/
 */

defined( 'ABSPATH' ) || exit;

global $product;

do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();
	return;
}

$image_id   = $product->get_image_id();
$main_img   = $image_id ? wp_get_attachment_image_url( $image_id, 'woocommerce_single' ) : wc_placeholder_img_src();
$main_img   = $main_img ?: wc_placeholder_img_src();
$gallery_ids = $product->get_gallery_image_ids();
$thumb_ids  = array_filter( array_merge( array( $image_id ), $gallery_ids ) );
$thumb_ids  = array_unique( $thumb_ids );
$thumb_ids  = array_slice( $thumb_ids, 0, 4 );
if ( empty( $thumb_ids ) ) {
	$thumb_ids = array( $image_id );
}
$thumb_ids = array_filter( $thumb_ids );
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>

	<div data-y="single-product">
		<div class="single-product">
			<div class="thumbnails">
				<?php foreach ( $thumb_ids as $tid ) : ?>
					<?php
					$thumb_url = $tid ? wp_get_attachment_image_url( $tid, 'woocommerce_thumbnail' ) : $main_img;
					$thumb_url = $thumb_url ?: $main_img;
					?>
					<img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" />
				<?php endforeach; ?>
			</div>
			<div class="single-product-main-img">
				<img src="<?php echo esc_url( $main_img ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>" />
			</div>
			<div class="details">
				<h3 class="product_title entry-title"><?php the_title(); ?></h3>
				<?php if ( $product->get_sku() ) : ?>
					<p class="code">#<?php echo esc_html( $product->get_sku() ); ?></p>
				<?php endif; ?>
				<div class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
				<?php if ( $product->is_purchasable() && $product->is_in_stock() ) : ?>
					<form class="actions cart" method="post" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" enctype="multipart/form-data">
						<input type="hidden" name="add-to-cart" value="<?php echo esc_attr( $product->get_id() ); ?>" />
						<input type="hidden" id="single-product-redirect-checkout" name="redirect_to_checkout" value="0" />
						<div class="qnt">
							<button type="button" class="btn btn-outline" data-qty-minus aria-label="<?php esc_attr_e( 'تقليل', 'woocommerce' ); ?>">-</button>
							<?php
							woocommerce_quantity_input(
								array(
									'min_value'   => $product->get_min_purchase_quantity(),
									'max_value'   => $product->get_max_purchase_quantity(),
									'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(),
									'input_name'  => 'quantity',
								)
							);
							?>
							<button type="button" class="btn btn-outline" data-qty-plus aria-label="<?php esc_attr_e( 'زيادة', 'woocommerce' ); ?>">+</button>
						</div>
						<button type="submit" class="btn btn-primary single_add_to_cart_button"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button>
						<button type="button" class="btn btn-primary" data-buy-now><?php esc_html_e( 'شراء الآن', 'woocommerce' ); ?></button>
					</form>
				<?php endif; ?>
			</div>
		</div>
	</div>

	<div class="pro">
		<?php
		add_filter( 'woocommerce_product_related_products_heading', function () {
			return 'تسوق أكثر';
		}, 5 );
		do_action( 'woocommerce_after_single_product_summary' );
		remove_all_filters( 'woocommerce_product_related_products_heading' );
		?>
	</div>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
