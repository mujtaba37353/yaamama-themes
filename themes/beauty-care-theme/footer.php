<?php
$assets_uri = get_template_directory_uri() . '/beauty-care/assets';
$footer     = function_exists( 'beauty_care_get_footer_settings' ) ? beauty_care_get_footer_settings() : array();
$logo_url   = function_exists( 'beauty_care_footer_logo_url' ) ? beauty_care_footer_logo_url() : ( $assets_uri . '/footer-icon.png' );
$whatsapp   = preg_replace( '/\D+/', '', $footer['whatsapp_number'] ?? '' );
$phone      = preg_replace( '/\D+/', '', $footer['phone_number'] ?? '' );
$copyright  = ! empty( $footer['copyright_text'] ) ? $footer['copyright_text'] : ( 'جميع الحقوق محفوظة © ' . date( 'Y' ) . ' Yamamah Solutions' );
?>
<footer class="footer">
	<div class="container y-u-max-w-1200">
		<div class="logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr__( 'شعار بيوتي كير', 'beauty-care-theme' ); ?>">
			</a>
		</div>
		<div class="bottom">
			<div class="main y-u-flex y-u-flex-col ">
				<p><?php echo esc_html( $footer['footer_text'] ?? 'نحن شركة متخصصة في تقديم أفضل أدوات ومنتجات التجميل للعناية بالبشرة والشعر...' ); ?></p>
			</div>
			<div class="links y-u-flex y-u-flex-col ">
				<h2><?php esc_html_e( 'الصفحات', 'beauty-care-theme' ); ?></h2>
				<ul class="y-u-flex y-u-justify-between y-u-flex-col ">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( beauty_care_shop_permalink() ); ?>"><?php esc_html_e( 'المتجر', 'beauty-care-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( function_exists( 'beauty_care_wishlist_permalink' ) ? beauty_care_wishlist_permalink() : home_url( '/wishlist' ) ); ?>"><?php esc_html_e( 'المفضلة', 'beauty-care-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>"><?php esc_html_e( 'من نحن', 'beauty-care-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'تواصل معنا', 'beauty-care-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( beauty_care_cart_permalink() ); ?>"><?php esc_html_e( 'السلة', 'beauty-care-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( beauty_care_account_permalink() ); ?>"><?php esc_html_e( 'حسابي', 'beauty-care-theme' ); ?></a></li>
				</ul>
			</div>
			<div class="links y-u-flex y-u-flex-col ">
				<h2><?php esc_html_e( 'السياسات', 'beauty-care-theme' ); ?></h2>
				<ul class="y-u-flex y-u-justify-between y-u-flex-col ">
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>"><?php esc_html_e( 'سياسة الخصوصية', 'beauty-care-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/return-policy' ) ); ?>"><?php esc_html_e( 'سياسة الاسترجاع', 'beauty-care-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/shipping-policy' ) ); ?>"><?php esc_html_e( 'سياسة الشحن', 'beauty-care-theme' ); ?></a></li>
				</ul>
			</div>
			<?php
			$contact = function_exists( 'beauty_care_get_contact_settings' ) ? beauty_care_get_contact_settings() : array();
			if ( ! empty( $contact['contact_address'] ) || ! empty( $contact['contact_email_display'] ) || ! empty( $contact['contact_phone'] ) ) :
			?>
			<div class="links y-u-flex y-u-flex-col ">
				<h2><?php esc_html_e( 'تواصل معنا', 'beauty-care-theme' ); ?></h2>
				<ul class="y-u-flex y-u-justify-between y-u-flex-col ">
					<?php if ( ! empty( $contact['contact_address'] ) ) : ?><li><a href="#"><img src="<?php echo esc_url( $assets_uri . '/email.svg' ); ?>" alt=""> <?php echo esc_html( $contact['contact_address'] ); ?></a></li><?php endif; ?>
					<?php if ( ! empty( $contact['contact_email_display'] ) ) : ?><li><a href="mailto:<?php echo esc_attr( $contact['contact_email_display'] ); ?>"><img src="<?php echo esc_url( $assets_uri . '/email.svg' ); ?>" alt=""> <?php echo esc_html( $contact['contact_email_display'] ); ?></a></li><?php endif; ?>
					<?php if ( ! empty( $contact['contact_phone'] ) ) : ?><li><a href="tel:<?php echo esc_attr( preg_replace( '/\D/', '', $contact['contact_phone'] ) ); ?>" style="direction: ltr; flex-direction: row-reverse;"><img src="<?php echo esc_url( $assets_uri . '/phone.svg' ); ?>" alt=""> <?php echo esc_html( $contact['contact_phone'] ); ?></a></li><?php endif; ?>
				</ul>
			</div>
			<?php endif; ?>
		</div>
		<p><?php echo esc_html( $copyright ); ?></p>
	</div>
</footer>

<?php if ( $whatsapp ) : ?>
<a class="beauty-care-float-btn beauty-care-float-whatsapp" href="<?php echo esc_url( 'https://wa.me/' . $whatsapp ); ?>" target="_blank" rel="noopener" aria-label="<?php esc_attr_e( 'واتساب', 'beauty-care-theme' ); ?>"><i class="fa-brands fa-whatsapp"></i></a>
<?php endif; ?>
<?php if ( $phone ) : ?>
<a class="beauty-care-float-btn beauty-care-float-call" href="<?php echo esc_url( 'tel:' . $phone ); ?>" aria-label="<?php esc_attr_e( 'اتصال', 'beauty-care-theme' ); ?>"><i class="fa-solid fa-phone"></i></a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
