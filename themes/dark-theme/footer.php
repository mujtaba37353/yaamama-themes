	<footer data-y="footer">
		<footer class="footer">
			<div class="footer-content">
				<div class="footer-col sec1">
					<div class="footer-brand">
						<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/logo.png' ) ); ?>" alt="شعار" class="footer-logo" />
						<p class="footer-title">دارك</p>
					</div>
					<p>
						تسوق الآن كل ما يحتاجه بيتك من الأثاث المنزلي والديكور والأثاث المكتبى
						والأجهزة المنزلية من موقع دارك. اشتري بأفضل سعر فى مصر، توصيل حتى باب
						البيت.
					</p>
				</div>

				<div class="footer-col sec2">
					<h5>الصفحات</h5>
					<ul>
						<li><a href="<?php echo esc_url( home_url( '/' ) ); ?>">الرئيسية</a></li>
						<li><a href="<?php echo esc_url( dark_theme_get_page_url( 'offers' ) ); ?>">العروض</a></li>
						<li><a href="<?php echo esc_url( dark_theme_get_page_url( 'shop' ) ); ?>">المنتجات</a></li>
						<li><a href="<?php echo esc_url( dark_theme_get_page_url( 'about' ) ); ?>">من نحن</a></li>
						<li><a href="<?php echo esc_url( dark_theme_get_page_url( 'contact' ) ); ?>">تواصل معنا</a></li>
					</ul>
				</div>

				<div class="footer-col sec3">
					<h5>السياسات</h5>
					<ul>
						<li><a href="<?php echo esc_url( dark_theme_get_page_url( 'privacy-policy' ) ); ?>">سياسة الخصوصية</a></li>
						<li><a href="<?php echo esc_url( dark_theme_get_page_url( 'refund-policy' ) ); ?>">سياسة الاسترجاع</a></li>
						<li><a href="<?php echo esc_url( dark_theme_get_page_url( 'shipping-policy' ) ); ?>">سياسة الشحن</a></li>
					</ul>
				</div>
			</div>

			<div class="last">
				<p>جميع الحقوق محفوظة لYamama solutions</p>
				<div>
					<p>طرق الدفع</p>
					<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/mastercard.png' ) ); ?>" alt="طرق الدفع" />
				</div>
			</div>
		</footer>
	</footer>

	<?php wp_footer(); ?>
</body>
</html>
