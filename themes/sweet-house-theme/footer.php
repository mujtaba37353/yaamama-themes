<?php
/**
 * Footer — from Sweet House design.
 *
 * @package Sweet_House_Theme
 */

$shop_url    = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : sweet_house_get_page_url( 'shop' );
$offers_url  = sweet_house_get_page_url( 'offers' );
$policy_url  = sweet_house_get_page_url( 'policy' );
$privacy_url = sweet_house_get_page_url( 'privacy-policy' );
$refund_url  = sweet_house_get_page_url( 'refund-policy' );
$shipping_url = sweet_house_get_page_url( 'shipping-policy' );
$about_url   = sweet_house_get_page_url( 'about-us', sweet_house_get_page_url( 'about' ) );
$contact_url = sweet_house_get_page_url( 'contact-us', sweet_house_get_page_url( 'contact' ) );

$footer_s = function_exists( 'sweet_house_get_footer_settings' ) ? sweet_house_get_footer_settings() : array();
$footer_logo   = function_exists( 'sweet_house_footer_logo_url' ) ? sweet_house_footer_logo_url() : sweet_house_asset_uri( 'assets/flogo.png' );
$footer_addr   = isset( $footer_s['footer_address'] ) ? $footer_s['footer_address'] : __( 'الرياض - المملكة العربية السعودية', 'sweet-house-theme' );
$footer_phones = isset( $footer_s['footer_phones'] ) ? $footer_s['footer_phones'] : '059688929 - 058493948';
$footer_email  = isset( $footer_s['footer_email'] ) ? $footer_s['footer_email'] : 'info@super.ksa.com';
?>
	<footer data-y="footer" class="footer">
		<div class="footer-content">
			<div class="footer-col sec1">
				<div class="footer-brand">
					<img src="<?php echo esc_url( $footer_logo ); ?>" alt="<?php echo esc_attr__( 'شعار سويت هاوس - متجر الحلويات والمخبوزات', 'sweet-house-theme' ); ?>" class="footer-logo" />
				</div>
				<p><i class="fa-solid fa-location-dot"></i> <?php echo esc_html( $footer_addr ); ?></p>
				<p><i class="fa-solid fa-phone"></i> <?php echo esc_html( $footer_phones ); ?></p>
				<p><i class="fa-solid fa-envelope"></i> <?php echo esc_html( $footer_email ); ?></p>
			</div>

			<div class="footer-col sec2">
				<h5><?php echo esc_html__( 'الصفحات', 'sweet-house-theme' ); ?></h5>
				<ul>
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php echo esc_html__( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $offers_url ); ?>"><?php echo esc_html__( 'العروض', 'sweet-house-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $shop_url ); ?>"><?php echo esc_html__( 'المنتجات', 'sweet-house-theme' ); ?></a></li>
				</ul>
			</div>

			<div class="footer-col sec3">
				<h5><?php echo esc_html__( 'السياسات', 'sweet-house-theme' ); ?></h5>
				<ul>
					<li><a href="<?php echo esc_url( $privacy_url ?: $policy_url ); ?>"><?php echo esc_html__( 'سياسة الخصوصية', 'sweet-house-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $refund_url ?: $policy_url ); ?>"><?php echo esc_html__( 'سياسة الاسترجاع', 'sweet-house-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $shipping_url ?: $policy_url ); ?>"><?php echo esc_html__( 'سياسة الشحن', 'sweet-house-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $about_url ); ?>"><?php echo esc_html__( 'من نحن', 'sweet-house-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( $contact_url ); ?>"><?php echo esc_html__( 'تواصل معنا', 'sweet-house-theme' ); ?></a></li>
				</ul>
			</div>
		</div>

		<div class="last">
			<p><?php echo esc_html__( 'جميع الحقوق محفوظة ل @ yamamah solutions', 'sweet-house-theme' ); ?></p>
			<div>
				<p><?php echo esc_html__( 'طرق الدفع', 'sweet-house-theme' ); ?></p>
				<img src="<?php echo esc_url( sweet_house_asset_uri( 'assets/payment-methods.png' ) ); ?>" alt="<?php echo esc_attr__( 'طرق الدفع المتاحة - مدى، فيزا، ماستركارد', 'sweet-house-theme' ); ?>" />
				<img src="<?php echo esc_url( sweet_house_asset_uri( 'assets/stc-pay.png' ) ); ?>" alt="<?php echo esc_attr__( 'شعار STC Pay - طريقة دفع إلكترونية', 'sweet-house-theme' ); ?>" />
			</div>
		</div>
	</footer>

	<?php
	$footer_s = function_exists( 'sweet_house_get_footer_settings' ) ? sweet_house_get_footer_settings() : array();
	$whatsapp_raw = isset( $footer_s['whatsapp_number'] ) ? trim( $footer_s['whatsapp_number'] ) : '';
	$whatsapp = preg_replace( '/\D/', '', $whatsapp_raw );
	if ( $whatsapp && substr( $whatsapp, 0, 1 ) === '0' ) {
		$whatsapp = '966' . substr( $whatsapp, 1 );
	}
	$phone_raw = isset( $footer_s['phone_number'] ) ? trim( $footer_s['phone_number'] ) : '';
	$phone_floating = preg_replace( '/\D/', '', $phone_raw );
	if ( $phone_floating && substr( $phone_floating, 0, 1 ) === '0' ) {
		$phone_floating = '966' . substr( $phone_floating, 1 );
	}
	if ( $whatsapp || $phone_floating ) :
		$wa_url = $whatsapp ? 'https://wa.me/' . $whatsapp : '';
		$tel_url = $phone_floating ? 'tel:+' . $phone_floating : '';
	?>
	<div class="sweet-house-floating-icons" role="complementary" aria-label="<?php esc_attr_e( 'تواصل سريع', 'sweet-house-theme' ); ?>">
		<?php if ( $wa_url ) : ?>
		<a href="<?php echo esc_url( $wa_url ); ?>" target="_blank" rel="noopener noreferrer" class="sweet-house-float-whatsapp" aria-label="<?php esc_attr_e( 'واتساب', 'sweet-house-theme' ); ?>">
			<i class="fa-brands fa-whatsapp"></i>
		</a>
		<?php endif; ?>
		<?php if ( $tel_url ) : ?>
		<a href="<?php echo esc_url( $tel_url ); ?>" class="sweet-house-float-phone" aria-label="<?php esc_attr_e( 'اتصل بنا', 'sweet-house-theme' ); ?>">
			<i class="fa-solid fa-phone"></i>
		</a>
		<?php endif; ?>
	</div>
	<style>
	.sweet-house-floating-icons { position: fixed; bottom: 1.5rem; left: 1.5rem; z-index: 9999; display: flex; flex-direction: column; gap: 0.5rem; }
	.sweet-house-float-whatsapp, .sweet-house-float-phone { width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.5rem; text-decoration: none; box-shadow: 0 4px 12px rgba(0,0,0,0.2); transition: transform 0.2s; }
	.sweet-house-float-whatsapp { background: #25D366; }
	.sweet-house-float-phone { background: var(--y-main, #2e2e2e); }
	.sweet-house-float-whatsapp:hover, .sweet-house-float-phone:hover { color: #fff; transform: scale(1.08); }
	@media (max-width: 820px) { .sweet-house-floating-icons { bottom: 1rem; left: 1rem; } .sweet-house-float-whatsapp, .sweet-house-float-phone { width: 44px; height: 44px; font-size: 1.25rem; } }
	</style>
	<?php endif; ?>

	<?php
	// Wire mobile menu after DOM is ready
	if ( file_exists( sweet_house_asset_path( 'js/header-toggle.js' ) ) ) {
		echo '<script>document.addEventListener("DOMContentLoaded",function(){if(window.wireMobileMenu)window.wireMobileMenu(document);});</script>';
	}
	?>
	<?php wp_footer(); ?>
</body>
</html>
