<?php
/**
 * Empty cart page
 *
 * @package KhutaaTheme
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_cart_is_empty' );
?>

<div class="not-found-container">
	<div class="not-found-content">
		<?php
		// Try to load image from assets, otherwise use inline SVG
		$theme_uri = get_template_directory_uri();
		$empty_cart_image = $theme_uri . '/khutaa/assets/empty-cart.png';
		$image_path = get_template_directory() . '/khutaa/assets/empty-cart.png';
		?>

		<?php if ( file_exists( $image_path ) ) : ?>
			<img src="<?php echo esc_url( $empty_cart_image ); ?>" alt="<?php esc_attr_e( 'عربة التسوق فارغة', 'khutaa-theme' ); ?>" class="not-found-img" />
		<?php else : ?>
			<!-- Inline SVG Cart Illustration -->
			<svg class="not-found-img empty-cart-illustration" viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
				<!-- Background decorative shapes -->
				<circle cx="80" cy="80" r="40" fill="#B8D4E3" opacity="0.4" />
				<circle cx="320" cy="120" r="30" fill="#B8D4E3" opacity="0.3" />
				<circle cx="350" cy="280" r="35" fill="#B8D4E3" opacity="0.4" />
				<rect x="60" y="250" width="80" height="20" rx="10" fill="#B8D4E3" opacity="0.3" />
				<rect x="280" y="60" width="60" height="20" rx="10" fill="#B8D4E3" opacity="0.3" />
				<!-- Horizontal lines decorative elements -->
				<g opacity="0.4">
					<line x1="50" y1="100" x2="90" y2="100" stroke="#4A9DB8" stroke-width="3" stroke-linecap="round" />
					<line x1="50" y1="110" x2="90" y2="110" stroke="#4A9DB8" stroke-width="3" stroke-linecap="round" />
					<line x1="50" y1="120" x2="90" y2="120" stroke="#4A9DB8" stroke-width="3" stroke-linecap="round" />
					<line x1="310" y1="280" x2="350" y2="280" stroke="#4A9DB8" stroke-width="3" stroke-linecap="round" />
					<line x1="310" y1="290" x2="350" y2="290" stroke="#4A9DB8" stroke-width="3" stroke-linecap="round" />
					<line x1="310" y1="300" x2="350" y2="300" stroke="#4A9DB8" stroke-width="3" stroke-linecap="round" />
				</g>
				
				<!-- Question mark circle -->
				<circle cx="180" cy="140" r="25" fill="#4A9DB8" />
				<text x="180" y="152" font-family="Arial, sans-serif" font-size="24" font-weight="bold" fill="white" text-anchor="middle">?</text>
				
				<!-- Shopping Cart -->
				<!-- Cart basket (orange with grid) -->
				<path d="M 180 220 L 220 220 L 235 280 L 145 280 Z" fill="#FF9800" />
				<!-- Grid lines on basket -->
				<line x1="180" y1="240" x2="220" y2="240" stroke="#FF6F00" stroke-width="2" />
				<line x1="180" y1="260" x2="220" y2="260" stroke="#FF6F00" stroke-width="2" />
				<line x1="190" y1="220" x2="190" y2="280" stroke="#FF6F00" stroke-width="2" />
				<line x1="210" y1="220" x2="210" y2="280" stroke="#FF6F00" stroke-width="2" />
				
				<!-- Cart handle (teal) -->
				<path d="M 220 220 Q 240 210 250 220 L 255 215 Q 245 200 220 210 Z" fill="#4A9DB8" />
				
				<!-- Cart wheels (teal) -->
				<circle cx="165" cy="285" r="12" fill="#4A9DB8" />
				<circle cx="215" cy="285" r="12" fill="#4A9DB8" />
				<circle cx="165" cy="285" r="6" fill="#B8D4E3" />
				<circle cx="215" cy="285" r="6" fill="#B8D4E3" />
			</svg>
		<?php endif; ?>

		<p class="not-found-text">
			<?php esc_html_e( 'عربة التسوق فارغة، لم تقم بإضافة أي منتجات إلى عربة التسوق الخاصة بك بعد.', 'khutaa-theme' ); ?>
		</p>

		<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
			<a href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>" class="btn-back">
				<?php echo esc_html( apply_filters( 'woocommerce_return_to_shop_text', __( 'تصفح المنتجات', 'khutaa-theme' ) ) ); ?>
				<i class="fa-solid fa-store"></i>
			</a>
		<?php endif; ?>
	</div>
</div>

<style>
.not-found-container {
	display: flex;
	flex-direction: column;
	align-items: center;
	justify-content: center;
	text-align: center;
	padding: 4rem 1rem;
	min-height: 60vh;
	background-color: #FCEFE4;
}

.not-found-content {
	max-width: 600px;
	display: flex;
	flex-direction: column;
	align-items: center;
	gap: 2rem;
}

.not-found-img {
	width: 100%;
	max-width: 400px;
	height: auto;
	object-fit: contain;
	margin-bottom: 1rem;
}

.empty-cart-illustration {
	max-width: 400px;
	max-height: 400px;
}

.not-found-text {
	font-size: 1.2rem;
	line-height: 1.6;
	color: #3a2c1c;
	font-weight: 600;
	margin-bottom: 1rem;
}

.btn-back {
	display: inline-flex;
	align-items: center;
	justify-content: center;
	gap: 0.8rem;
	background-color: #ac5300;
	color: #fff;
	padding: 0.8rem 2.5rem;
	border-radius: 8px;
	text-decoration: none;
	font-size: 1.1rem;
	font-weight: 700;
	transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-back:hover {
	background-color: #b18155;
	transform: translateY(-2px);
}

.btn-back i {
	font-size: 1rem;
}

@media (max-width: 992px) {
	.not-found-container {
		padding: 3rem 1rem;
		min-height: 50vh;
	}

	.not-found-img {
		max-width: 350px;
	}
}

@media (max-width: 768px) {
	.not-found-container {
		padding: 2rem 1rem;
	}

	.not-found-img {
		max-width: 280px;
	}

	.not-found-text {
		font-size: clamp(0.9rem, 3.5vw, 1rem);
		padding: 0 1rem;
	}

	.btn-back {
		font-size: clamp(0.9rem, 4vw, 1rem);
		padding: 0.7rem 2rem;
	}
}

@media (max-width: 576px) {
	.not-found-container {
		padding: 1.5rem 0.75rem;
	}

	.not-found-content {
		gap: 1.5rem;
	}

	.not-found-img {
		max-width: 220px;
	}

	.not-found-text {
		font-size: 0.9rem;
		padding: 0 0.5rem;
		line-height: 1.5;
	}

	.btn-back {
		font-size: 0.9rem;
		padding: 0.6rem 1.5rem;
		width: auto;
		max-width: 90%;
		gap: 0.5rem;
	}

	.btn-back i {
		font-size: 0.9rem;
	}
}
</style>
