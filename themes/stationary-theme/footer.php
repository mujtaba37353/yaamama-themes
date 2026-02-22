<?php
$assets_uri = stationary_base_uri() . '/assets';
$logo_url   = stationary_logo_url( 'footer' );
$footer_phone = function_exists( 'stationary_get_option' ) ? stationary_get_option( 'footer_phone', '+966 12 345 6789' ) : '+966 12 345 6789';
$footer_email = function_exists( 'stationary_get_option' ) ? stationary_get_option( 'footer_email', 'Stationary@gmail.com' ) : 'Stationary@gmail.com';
?>
<footer class="footer">
	<div class="container y-u-max-w-1200">
		<div class="logo">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
				<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php esc_attr_e( 'شعار Stationary', 'stationary-theme' ); ?>">
			</a>
		</div>
		<div class="bottom">
			<div class="main y-u-flex y-u-flex-col ">
				<p><?php esc_html_e( 'نحن نؤمن أن الإلهام يبدأ من التفاصيل الصغيرة. لذلك نقدّم لك أدوات مكتبية تجمع بين الأناقة، الجودة، والعملية لتجعل كل يوم عمل أو دراسة تجربة أكثر تنظيمًا ومتعة.', 'stationary-theme' ); ?></p>
			</div>
			<div class="links y-u-flex y-u-flex-col ">
				<h2><?php esc_html_e( 'الصفحات', 'stationary-theme' ); ?></h2>
				<ul class="y-u-flex y-u-justify-between y-u-flex-col ">
					<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'stationary-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( stationary_shop_permalink() ); ?>"><?php esc_html_e( 'تسوق', 'stationary-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/about-us' ) ); ?>"><?php esc_html_e( 'من نحن', 'stationary-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'تواصل معنا', 'stationary-theme' ); ?></a></li>
				</ul>
			</div>
			<div class="links y-u-flex y-u-flex-col ">
				<h2><?php esc_html_e( 'السياسات', 'stationary-theme' ); ?></h2>
				<ul class="y-u-flex y-u-justify-between y-u-flex-col ">
					<li><a href="<?php echo esc_url( home_url( '/privacy-policy' ) ); ?>"><?php esc_html_e( 'سياسة الخصوصية', 'stationary-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/return-policy' ) ); ?>"><?php esc_html_e( 'سياسة الاسترجاع', 'stationary-theme' ); ?></a></li>
					<li><a href="<?php echo esc_url( home_url( '/shipping-policy' ) ); ?>"><?php esc_html_e( 'سياسة الشحن', 'stationary-theme' ); ?></a></li>
				</ul>
			</div>
			<div class="links y-u-flex y-u-flex-col ">
				<h2><?php esc_html_e( 'تواصل معنا', 'stationary-theme' ); ?></h2>
				<ul class="y-u-flex y-u-justify-between y-u-flex-col ">
					<li><a href="#"><img src="<?php echo esc_url( $assets_uri . '/map.svg' ); ?>" alt=""> <?php esc_html_e( 'الرياض، المملكة العربية السعودية', 'stationary-theme' ); ?></a></li>
					<li><a href="mailto:<?php echo esc_attr( $footer_email ); ?>"><img src="<?php echo esc_url( $assets_uri . '/email.svg' ); ?>" alt=""> <?php echo esc_html( $footer_email ); ?></a></li>
					<li><a href="tel:<?php echo esc_attr( preg_replace( '/\s+/', '', $footer_phone ) ); ?>"><img src="<?php echo esc_url( $assets_uri . '/phone.svg' ); ?>" alt=""> <?php echo esc_html( $footer_phone ); ?></a></li>
				</ul>
			</div>
		</div>
		<p><?php echo esc_html( 'جميع الحقوق محفوظة © Yamamah Solutions' ); ?></p>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
