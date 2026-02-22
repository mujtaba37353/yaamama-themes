<?php
/**
 * WooCommerce Single Product Template Override (Khutaa Theme)
 * Path: /wp-content/themes/khutaa-theme/woocommerce/single-product.php
 */
defined('ABSPATH') || exit;

get_header('shop');

// Remove default WooCommerce wrappers
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';

// Get banner image
$banner_2_image = khutaa_get_demo_content('khutaa_banner_2_image');
$default_banner = $khutaa_uri . '/assets/design.png';

?>

<div class="single-product-banner-header">
	<?php if ($banner_2_image) : ?>
		<img src="<?php echo esc_url($banner_2_image); ?>" alt="<?php esc_attr_e('بنر', 'khutaa-theme'); ?>" />
	<?php else : ?>
		<img src="<?php echo esc_url($default_banner); ?>" alt="<?php esc_attr_e('بنر', 'khutaa-theme'); ?>" />
	<?php endif; ?>
</div>

<main id="main" class="y-u-container">
	<?php while (have_posts()) : the_post(); ?>
		<?php
		global $product;
		if (!$product) {
			$product = wc_get_product(get_the_ID());
		}
		if (!$product) {
			continue;
		}
		// Get product data
		$main_image_id = $product->get_image_id();
		$gallery_ids = $product->get_gallery_image_ids();
		
		// Build thumbnails: main image first, then gallery
		$thumb_ids = [];
		if ($main_image_id) $thumb_ids[] = $main_image_id;
		if (!empty($gallery_ids)) $thumb_ids = array_merge($thumb_ids, $gallery_ids);
		$thumb_ids = array_values(array_unique(array_filter($thumb_ids)));
		
		$sku = $product->get_sku();
		$price_html = $product->get_price_html();
		
		// Get attributes
		$color_tax = 'pa_color';
		$size_tax = 'pa_size';
		$color_terms = taxonomy_exists($color_tax) ? wc_get_product_terms($product->get_id(), $color_tax, ['fields' => 'all']) : [];
		$size_terms = taxonomy_exists($size_tax) ? wc_get_product_terms($product->get_id(), $size_tax, ['fields' => 'all']) : [];
		
		// Helper function to get color hex
		$get_term_hex = function($term_id) {
			$keys = ['color', 'colour', 'hex', 'pa_color', 'term_color', 'color_hex'];
			foreach ($keys as $k) {
				$v = get_term_meta($term_id, $k, true);
				if (is_string($v) && preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', trim($v))) {
					return trim($v);
				}
			}
			return '';
		};
		?>
		
		<div class="single-product-wrapper">
			<!-- Thumbnails -->
			<div class="thumbnails">
				<?php if (!empty($thumb_ids)) : ?>
					<?php foreach ($thumb_ids as $img_id) :
						$thumb_url = wp_get_attachment_image_url($img_id, 'woocommerce_thumbnail');
						$full_url = wp_get_attachment_image_url($img_id, 'full');
						if (!$thumb_url || !$full_url) continue;
						?>
						<img src="<?php echo esc_url($thumb_url); ?>" data-full="<?php echo esc_url($full_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
					<?php endforeach; ?>
				<?php else : ?>
					<img src="<?php echo esc_url(wc_placeholder_img_src('woocommerce_thumbnail')); ?>" alt="" />
				<?php endif; ?>
			</div>
			
			<!-- Main image -->
			<div class="main-image-container">
				<?php
				$main_url = $main_image_id ? wp_get_attachment_image_url($main_image_id, 'full') : wc_placeholder_img_src('full');
				?>
				<img src="<?php echo esc_url($main_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" />
			</div>
			
			<!-- Details -->
			<div class="details">
				<?php if (!empty($color_terms)) : ?>
					<div>
						<h4><?php esc_html_e('الألوان المتاحة', 'khutaa-theme'); ?></h4>
						<div class="colors">
							<?php foreach ($color_terms as $term) :
								$hex = $get_term_hex($term->term_id);
								if (!$hex && is_string($term->name) && preg_match('/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', trim($term->name))) {
									$hex = trim($term->name);
								}
								?>
								<div class="color" style="<?php echo $hex ? 'background-color:' . esc_attr($hex) : ''; ?>"></div>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				
				<h2 class="product-code">#<?php echo $sku ? esc_html($sku) : esc_html($product->get_id()); ?></h2>
				<h2 class="product-price"><?php echo wp_kses_post($price_html); ?></h2>
				
				<?php if (!empty($size_terms)) : ?>
					<div>
						<h4><?php esc_html_e('المقاسات', 'khutaa-theme'); ?></h4>
						<div class="sizes">
							<?php foreach ($size_terms as $term) : ?>
								<button class="btn-size"><?php echo esc_html($term->name); ?></button>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ($product->is_purchasable() && $product->is_in_stock()) : ?>
					<form class="cart single-product-cart-form" action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>" method="post" enctype="multipart/form-data">
						<?php
						$min_quantity = $product->get_min_purchase_quantity();
						$max_quantity = $product->get_max_purchase_quantity();
						?>
						<div class="actions">
							<a href="<?php echo esc_url(wc_get_checkout_url() . '?add-to-cart=' . $product->get_id() . '&quantity=' . $min_quantity); ?>" class="btn-primary buy-now-btn" data-product-id="<?php echo esc_attr($product->get_id()); ?>"><?php esc_html_e('اشتري الآن', 'khutaa-theme'); ?></a>
							<button type="submit" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>" class="btn-primary add-to-cart-btn"><?php esc_html_e('إضافة الي السلة', 'khutaa-theme'); ?></button>
							<div class="qnt" data-min="<?php echo esc_attr($min_quantity); ?>" data-max="<?php echo esc_attr($max_quantity ? $max_quantity : 9999); ?>">
								<button type="button" class="qnt-minus">-</button>
								<span class="qnt-value"><?php echo esc_html($min_quantity); ?></span>
								<button type="button" class="qnt-plus">+</button>
							</div>
							<input type="hidden" name="quantity" class="qnt-input" value="<?php echo esc_attr($min_quantity); ?>" />
						</div>
					</form>
				<?php else : ?>
					<div class="actions">
						<button class="btn-primary" disabled><?php esc_html_e('غير متاح حالياً', 'khutaa-theme'); ?></button>
					</div>
				<?php endif; ?>
			</div>
		</div>
	<?php endwhile; ?>
</main>

<style>
/* Custom Single Product Styles - No dependency on khutaa files */
.single-product-wrapper {
	display: grid;
	grid-template-columns: 100px 1fr 1fr;
	align-items: start;
	gap: 2rem;
	padding: 2rem 5%;
	margin: 2rem auto;
	max-width: 1400px;
	direction: rtl;
}

.single-product-wrapper .thumbnails {
	display: flex;
	flex-direction: column;
	gap: 1rem;
}

.single-product-wrapper .thumbnails img {
	width: 100%;
	height: auto;
	aspect-ratio: 1/1;
	object-fit: cover;
	border-radius: 16px;
	cursor: pointer;
	transition: transform 0.3s ease, border-color 0.3s ease;
	border: 2px solid transparent;
	background-color: #f5f5f5;
}

.single-product-wrapper .thumbnails img:hover {
	transform: scale(1.05);
	border-color: #8B4513;
}

.single-product-wrapper .main-image-container {
	display: flex;
	justify-content: center;
	align-items: center;
	background-color: transparent;
	border-radius: 24px;
	overflow: hidden;
}

.single-product-wrapper .main-image-container img {
	width: 100%;
	max-width: 500px;
	height: auto;
	object-fit: contain;
	transform: scale(1.1) rotate(-5deg);
	transition: transform 0.5s ease;
}

.single-product-wrapper .details {
	display: flex;
	flex-direction: column;
	gap: 1.5rem;
	padding-right: 2rem;
}

.single-product-wrapper .details h4 {
	font-size: 1.1rem;
	font-weight: 600;
	color: #333;
	margin-bottom: 0.5rem;
}

.single-product-wrapper .details h2 {
	font-size: 2rem;
	font-weight: 800;
	color: #333;
	margin: 0;
}

.single-product-wrapper .colors {
	display: flex;
	gap: 1rem;
	margin-bottom: 1rem;
}

.single-product-wrapper .color {
	width: 32px;
	height: 32px;
	border-radius: 50%;
	cursor: pointer;
	border: 2px solid #eee;
	box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
	transition: transform 0.2s ease, border-color 0.2s ease;
	position: relative;
}

.single-product-wrapper .color:hover {
	transform: scale(1.1);
}

.single-product-wrapper .color.selected {
	border-color: #8B4513;
}

.single-product-wrapper .product-code {
	font-family: sans-serif;
	color: #666;
	font-size: 1.5rem;
	font-weight: bold;
}

.single-product-wrapper .product-price {
	font-size: 2.5rem;
	font-weight: 900;
	color: #333;
}

.single-product-wrapper .sizes {
	display: flex;
	gap: 1rem;
	flex-wrap: wrap;
}

.single-product-wrapper .sizes button {
	width: 60px;
	height: 40px;
	border: 1px solid #8B4513;
	background-color: transparent;
	border-radius: 8px;
	font-size: 1.1rem;
	font-weight: 600;
	color: #333;
	cursor: pointer;
	transition: all 0.3s ease;
}

.single-product-wrapper .sizes button:hover,
.single-product-wrapper .sizes button.active {
	background-color: #8B4513;
	color: #fff;
}

.single-product-wrapper .actions {
	display: flex;
	align-items: center;
	gap: 1rem;
	margin-top: 1rem;
	flex-wrap: wrap;
}

.single-product-wrapper .actions .qnt {
	display: flex;
	align-items: center;
	justify-content: space-between;
	width: 140px;
	height: 50px;
	border: 1px solid #8B4513;
	border-radius: 25px;
	padding: 0 5px;
	font-size: 1.2rem;
	font-weight: bold;
}

.single-product-wrapper .actions .qnt button {
	width: 40px;
	height: 100%;
	border: none;
	background: transparent;
	font-size: 1.5rem;
	cursor: pointer;
	color: #333;
	display: flex;
	align-items: center;
	justify-content: center;
}

.single-product-wrapper .actions .qnt span {
	width: 30px;
	text-align: center;
}

.single-product-wrapper .actions .btn-primary {
	height: 50px;
	padding: 0 2rem;
	border-radius: 25px;
	font-size: 1.1rem;
	font-weight: 700;
	background-color: #8B4513;
	border: none;
	color: #fff;
	cursor: pointer;
	flex-grow: 1;
	max-width: 200px;
	white-space: nowrap;
	display: flex;
	align-items: center;
	justify-content: center;
	transition: background-color 0.3s ease;
}

.single-product-wrapper .actions .btn-primary:hover {
	background-color: #654321;
}

.single-product-wrapper .actions .buy-now-btn {
	text-decoration: none;
}

.single-product-wrapper .actions .buy-now-btn:hover {
	text-decoration: none;
}

.single-product-wrapper .actions .btn-primary:disabled {
	opacity: 0.6;
	cursor: not-allowed;
}

/* Banner header styles */
.single-product-banner-header {
	width: 100%;
}

.single-product-banner-header img {
	width: 100%;
	height: auto;
	display: block;
}

@media (max-width: 992px) {
	.single-product-wrapper {
		grid-template-columns: 1fr;
		gap: 2rem;
	}

	.single-product-wrapper .thumbnails {
		flex-direction: row;
		justify-content: center;
		order: 2;
	}

	.single-product-wrapper .thumbnails img {
		width: 80px;
	}

	.single-product-wrapper .main-image-container {
		order: 1;
	}

	.single-product-wrapper .details {
		order: 3;
		padding-right: 0;
		align-items: center;
		text-align: center;
	}

	.single-product-wrapper .colors,
	.single-product-wrapper .sizes,
	.single-product-wrapper .actions {
		justify-content: center;
	}

	.single-product-wrapper .actions {
		width: 100%;
		flex-direction: column;
	}

	.single-product-wrapper .actions .btn-primary {
		width: 100%;
		max-width: 100%;
	}
}
</style>

<script>
jQuery(document).ready(function($) {
	// Thumbnail click to change main image
	$('.single-product-wrapper .thumbnails img').on('click', function() {
		var fullUrl = $(this).data('full');
		if (fullUrl) {
			$('.single-product-wrapper .main-image-container img').attr('src', fullUrl);
		}
	});

	// Quantity controls
	$('.single-product-wrapper .qnt .qnt-minus, .single-product-wrapper .qnt .qnt-plus').on('click', function(e) {
		e.preventDefault();
		var $qnt = $(this).closest('.qnt');
		var $span = $qnt.find('.qnt-value');
		var $input = $qnt.find('.qnt-input');
		var min = parseInt($qnt.data('min')) || 1;
		var max = parseInt($qnt.data('max')) || 9999;
		var current = parseInt($span.text()) || min;
		
		if ($(this).hasClass('qnt-minus')) {
			current = Math.max(min, current - 1);
		} else if ($(this).hasClass('qnt-plus')) {
			current = Math.min(max, current + 1);
		}
		
		$span.text(current);
		$input.val(current);
		
		// Update buy now link with quantity
		var $buyNowBtn = $('.buy-now-btn');
		var productId = $buyNowBtn.data('product-id');
		if (productId) {
			var checkoutUrl = $buyNowBtn.attr('href').split('?')[0];
			$buyNowBtn.attr('href', checkoutUrl + '?add-to-cart=' + productId + '&quantity=' + current);
		}
	});

	// Size button active state
	$('.single-product-wrapper .sizes button').on('click', function() {
		$('.single-product-wrapper .sizes button').removeClass('active');
		$(this).addClass('active');
	});

	// Color selection
	$('.single-product-wrapper .color').on('click', function() {
		$('.single-product-wrapper .color').removeClass('selected');
		$(this).addClass('selected');
	});
});
</script>

<?php
get_footer('shop');
