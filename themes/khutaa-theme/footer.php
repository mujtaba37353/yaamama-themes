<?php
/**
 * The footer template file
 *
 * @package KhutaaTheme
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$theme_uri = get_template_directory_uri();
$footer_logo_url = $theme_uri . '/khutaa/assets/footer logo.png';
?>

<footer class="footer">
	<div class="footer-top">
		<div class="footer-col sec1 y-u-col-lg-6 y-u-col-sm-12">
			<div class="footer-brand">
				<p class="footer-title"><?php bloginfo( 'name' ); ?></p>
				<img src="<?php echo esc_url( $footer_logo_url ); ?>" alt="<?php esc_attr_e( 'شعار', 'khutaa-theme' ); ?>" class="footer-logo" />
			</div>
			<?php
			// Display contact information from contact settings
			$address = khutaa_get_contact_setting( 'address' );
			$phone = khutaa_get_contact_setting( 'phone' );
			$email = khutaa_get_contact_setting( 'email' );
			?>
			<?php if ( $address ) : ?>
				<p><?php echo esc_html( $address ); ?><i class="fa-solid fa-location-dot"></i></p>
			<?php endif; ?>
			<?php if ( $phone ) : ?>
				<p><?php echo esc_html( $phone ); ?><i class="fa-solid fa-phone"></i></p>
			<?php endif; ?>
			<?php if ( $email ) : ?>
				<p><a href="mailto:<?php echo esc_attr( $email ); ?>"><?php echo esc_html( $email ); ?></a><i class="fa-solid fa-envelope"></i></p>
			<?php endif; ?>
		</div>
		<div class="footer-col sec2 y-u-col-2">
			<h5><?php esc_html_e( 'الصفحات', 'khutaa-theme' ); ?></h5>
			<ul>
				<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'khutaa-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ); ?>"><?php esc_html_e( 'العروض', 'khutaa-theme' ); ?></a></li>
				<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'المنتجات', 'khutaa-theme' ); ?></a></li>
			</ul>
		</div>

		<div class="footer-col sec3 y-u-col-3">
			<h5><?php esc_html_e( 'معلومات عنا', 'khutaa-theme' ); ?></h5>
			<ul>
				<?php
				$privacy_policy_page = get_page_by_path( 'privacy-policy' );
				$refund_returns_page = get_page_by_path( 'refund_returns' );
				$refund_page = get_page_by_path( 'refund-policy' );
				$shipping_page = get_page_by_path( 'using-policy' );
				$about_page = get_page_by_path( 'about-us' );
				$contact_page = get_page_by_path( 'contact-us' );
				?>
				<?php if ( $privacy_policy_page ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $privacy_policy_page ) ); ?>"><?php esc_html_e( 'سياسة الخصوصية', 'khutaa-theme' ); ?></a></li>
				<?php endif; ?>
				<?php if ( $refund_returns_page ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $refund_returns_page ) ); ?>"><?php esc_html_e( 'سياسة الاسترجاع', 'khutaa-theme' ); ?></a></li>
				<?php elseif ( $refund_page ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $refund_page ) ); ?>"><?php esc_html_e( 'سياسة الاسترجاع', 'khutaa-theme' ); ?></a></li>
				<?php endif; ?>
				<?php if ( $shipping_page ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $shipping_page ) ); ?>"><?php esc_html_e( 'سياسة الشحن', 'khutaa-theme' ); ?></a></li>
				<?php endif; ?>
				<?php if ( $about_page ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $about_page ) ); ?>"><?php esc_html_e( 'من نحن', 'khutaa-theme' ); ?></a></li>
				<?php endif; ?>
				<?php if ( $contact_page ) : ?>
					<li><a href="<?php echo esc_url( get_permalink( $contact_page ) ); ?>"><?php esc_html_e( 'تواصل معنا', 'khutaa-theme' ); ?></a></li>
				<?php endif; ?>
			</ul>
		</div>
	</div>

	<div class="footer-bottom">
		<p><?php echo esc_html( sprintf( __( 'جميع الحقوق محفوظة ل %s', 'khutaa-theme' ), 'Yamama solutions' ) ); ?></p>
		<div class="payment-icons">
			<img src="<?php echo esc_url( $theme_uri . '/khutaa/assets/stc pay.png' ); ?>" alt="STC Pay" />
			<img src="<?php echo esc_url( $theme_uri . '/khutaa/assets/mastercard.png' ); ?>" alt="Mastercard" />
			<img src="<?php echo esc_url( $theme_uri . '/khutaa/assets/visa.png' ); ?>" alt="Visa" />
		</div>
	</div>
</footer>

<?php
// WhatsApp floating button
$whatsapp_number = khutaa_get_contact_setting( 'whatsapp' );
if ( ! empty( $whatsapp_number ) ) {
	// Remove any non-numeric characters
	$whatsapp_clean = preg_replace( '/[^0-9]/', '', $whatsapp_number );
	$whatsapp_url = 'https://wa.me/' . $whatsapp_clean;
	?>
	<a href="<?php echo esc_url( $whatsapp_url ); ?>" target="_blank" rel="noopener noreferrer" class="whatsapp-float" aria-label="<?php esc_attr_e( 'تواصل معنا عبر الواتساب', 'khutaa-theme' ); ?>">
		<svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M16 0C7.163 0 0 7.163 0 16c0 2.827.742 5.48 2.032 7.776L0 32l8.448-2.016C10.696 32.338 13.23 33 16 33c8.837 0 16-7.163 16-16S24.837 0 16 0zm0 29.538c-2.373 0-4.608-.656-6.528-1.8L5.538 28.23l.538-3.838c-1.044-1.8-1.6-3.842-1.6-6.015C4.476 9.54 9.66 4.354 16 4.354c2.792 0 5.415 1.046 7.384 2.946 1.97 1.9 3.054 4.423 3.054 7.108 0 6.34-5.184 11.526-11.526 11.526z" fill="currentColor"/>
			<path d="M23.546 18.77c-.32-.16-1.888-.93-2.181-1.037-.294-.107-.508-.16-.723.16-.215.32-.838 1.037-1.027 1.25-.19.214-.379.24-.699.08-.32-.16-1.35-.498-2.572-1.585-.95-.848-1.592-1.897-1.78-2.217-.189-.32-.02-.493.142-.653.146-.146.32-.373.48-.56.16-.187.213-.32.32-.533.107-.213.053-.4-.027-.56-.08-.16-.723-1.742-.991-2.386-.26-.622-.525-.538-.723-.549-.189-.01-.407-.012-.623-.012-.213 0-.56.08-.854.4-.294.32-1.126 1.1-1.126 2.682 0 1.583 1.152 3.112 1.313 3.326.16.214 2.263 3.446 5.488 4.831.767.325 1.366.52 1.832.665.787.245 1.505.21 2.072.127.627-.09 1.888-.773 2.153-1.52.266-.747.266-1.387.187-1.52-.08-.134-.293-.214-.613-.373z" fill="currentColor"/>
		</svg>
	</a>
	<style>
	.whatsapp-float {
		position: fixed;
		width: 60px;
		height: 60px;
		bottom: 20px;
		left: 20px;
		background-color: #25d366;
		color: #fff;
		border-radius: 50%;
		text-align: center;
		font-size: 30px;
		box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
		z-index: 9999;
		display: flex;
		align-items: center;
		justify-content: center;
		text-decoration: none;
		transition: all 0.3s ease;
	}
	.whatsapp-float:hover {
		background-color: #128c7e;
		transform: scale(1.1);
		color: #fff;
		box-shadow: 2px 2px 15px rgba(0, 0, 0, 0.4);
	}
	.whatsapp-float svg {
		width: 32px;
		height: 32px;
	}
	@media (max-width: 768px) {
		.whatsapp-float {
			width: 55px;
			height: 55px;
			bottom: 15px;
			left: 15px;
		}
		.whatsapp-float svg {
			width: 28px;
			height: 28px;
		}
	}
	</style>
	<?php
}
?>

<?php wp_footer(); ?>

</body>
</html>
