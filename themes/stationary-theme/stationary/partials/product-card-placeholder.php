<?php
$args       = get_query_var( 'args', array() );
$show_sale  = ! empty( $args['show_sale'] );
$au = stationary_base_uri() . '/assets';
$shop_url = stationary_shop_permalink();
?>
<li class="card">
	<div class="card-inner">
		<a href="<?php echo esc_url( $shop_url ); ?>">
			<div class="img">
				<label class="favorite-toggle">
					<input type="checkbox" class="favorite-toggle__checkbox" aria-hidden="true" tabindex="-1">
					<span class="favorite-toggle__icon">
						<i class="fa-solid fa-heart" aria-hidden="true"></i>
						<i class="fa-regular fa-heart" aria-hidden="true"></i>
					</span>
				</label>
				<img src="<?php echo esc_url( $au . '/product-1.png' ); ?>" alt="<?php esc_attr_e( 'منتج', 'stationary-theme' ); ?>">
			</div>
			<div class="content">
				<div class="top y-u-flex y-u-justify-between y-u-flex-col y-u-gap-8">
					<p><?php esc_html_e( 'نوت بوك مسطر غلاف ورق 100 ورقة', 'stationary-theme' ); ?></p>
				</div>
			</div>
		</a>
		<div class="content card-bottom">
			<div class="bottom">
				<p>
					<?php if ( $show_sale ) : ?>
						<span class="grey-ryal">100 <img src="<?php echo esc_url( $au . '/grey-ryal.svg' ); ?>" alt="ريال"></span>
						<span>80 <img src="<?php echo esc_url( $au . '/ryal.svg' ); ?>" alt="ريال"></span>
					<?php else : ?>
						<span>50 <img src="<?php echo esc_url( $au . '/ryal.svg' ); ?>" alt="ريال"></span>
					<?php endif; ?>
				</p>
				<a href="<?php echo esc_url( $shop_url ); ?>" class="button"><?php esc_html_e( 'أضف إلى السلة', 'stationary-theme' ); ?></a>
			</div>
		</div>
	</div>
</li>
