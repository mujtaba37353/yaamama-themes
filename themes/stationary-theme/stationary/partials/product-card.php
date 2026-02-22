<?php
$card_args = isset( $args ) && is_array( $args ) ? $args : get_query_var( 'args', array() );
if ( ! function_exists( 'stationary_debug_log_product_card' ) ) {
	function stationary_debug_log_product_card( $message, $data = array() ) {
		// #region agent log
		$log_path = 'c:\\Users\\mujtaba\\Local Sites\\yamama-platform\\.cursor\\debug.log';
		$payload  = array(
			'runId'        => 'initial',
			'hypothesisId' => 'H1',
			'location'     => 'stationary/partials/product-card.php:2',
			'message'      => $message,
			'data'         => $data,
			'timestamp'    => round( microtime( true ) * 1000 ),
		);
		@file_put_contents( $log_path, wp_json_encode( $payload ) . PHP_EOL, FILE_APPEND );
		// #endregion
	}
}

static $stationary_product_card_log_count = 0;
if ( $stationary_product_card_log_count < 8 ) {
	stationary_debug_log_product_card(
		'Product card args probe',
		array(
			'argsIsArray' => is_array( $card_args ),
			'argsKeys'    => is_array( $card_args ) ? array_keys( $card_args ) : array(),
			'hasProduct'  => ! empty( $card_args['product'] ),
			'productType' => ! empty( $card_args['product'] ) ? get_class( $card_args['product'] ) : null,
		)
	);
	$stationary_product_card_log_count++;
}
if ( empty( $card_args['product'] ) || ! is_a( $card_args['product'], 'WC_Product' ) ) {
	return;
}
$product   = $card_args['product'];
$show_sale = ! empty( $card_args['show_sale'] );
$au        = stationary_base_uri() . '/assets';
$link      = $product->get_permalink();
$product_id = (int) $product->get_id();
?>
<li class="card">
	<div class="card-inner">
	<a href="<?php echo esc_url( $link ); ?>">
		<div class="img">
			<label class="favorite-toggle">
				<input type="checkbox" class="favorite-toggle__checkbox" aria-label="<?php esc_attr_e( 'إضافة إلى المفضلة', 'stationary-theme' ); ?>" data-product-id="<?php echo esc_attr( $product_id ); ?>">
				<span class="favorite-toggle__icon">
					<i class="fa-solid fa-heart" aria-hidden="true"></i>
					<i class="fa-regular fa-heart" aria-hidden="true"></i>
				</span>
			</label>
			<?php echo $product->get_image( 'woocommerce_thumbnail', array( 'alt' => $product->get_name() ) ); ?>
		</div>
		<div class="content">
			<div class="top y-u-flex y-u-justify-between y-u-flex-col y-u-gap-8">
				<p><?php echo esc_html( $product->get_name() ); ?></p>
			</div>
		</div>
	</a>
	<div class="content card-bottom">
		<div class="bottom">
			<p>
				<?php if ( $show_sale && $product->is_on_sale() ) : ?>
					<span class="grey-ryal"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
				<?php else : ?>
					<span><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
				<?php endif; ?>
			</p>
			<?php woocommerce_template_loop_add_to_cart( array( 'class' => 'button' ) ); ?>
		</div>
	</div>
	</div>
</li>
