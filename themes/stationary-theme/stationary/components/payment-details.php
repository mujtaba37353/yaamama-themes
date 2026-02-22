<?php
defined( 'ABSPATH' ) || exit;
$au = stationary_base_uri() . '/assets';
?>
<section class="payment-methods">
	<div class="container y-u-max-w-1200">
		<div class="payment-method">
			<p>
				<?php esc_html_e( 'قسّمها على 5 دفعات بقيمة 10 ر.س بدون فوائد.', 'stationary-theme' ); ?>
				<br>
				<?php esc_html_e( 'متوافق مع أحكام الشريعة الإسلامية.', 'stationary-theme' ); ?>
				<a href="#"><?php esc_html_e( 'لمعرفة المزيد', 'stationary-theme' ); ?></a>
			</p>
			<img src="<?php echo esc_url( $au . '/taby-pay.png' ); ?>" alt="Tabby" onerror="this.style.display='none'">
		</div>
		<div class="payment-method">
			<p>
				<?php esc_html_e( 'أو قسّم فاتورتك بقيمة 10 ر.س على 5 دفعات بدون رسوم تأخير.', 'stationary-theme' ); ?>
				<br>
				<?php esc_html_e( 'متوافقة مع الشريعة الإسلامية.', 'stationary-theme' ); ?>
				<a href="#"><?php esc_html_e( 'لمعرفة المزيد', 'stationary-theme' ); ?></a>
			</p>
			<img src="<?php echo esc_url( $au . '/tmara-pay.png' ); ?>" alt="Tamara" onerror="this.style.display='none'">
		</div>
	</div>
</section>
