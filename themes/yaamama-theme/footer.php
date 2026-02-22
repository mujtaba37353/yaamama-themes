<?php
$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';
$settings = yaamama_get_footer_settings();
$contact_settings = yaamama_get_contact_settings();
$whatsapp_number = preg_replace( '/\D+/', '', $contact_settings['floating']['whatsapp'] ?? '' );
$call_number = preg_replace( '/\D+/', '', $contact_settings['floating']['call'] ?? '' );
?>
<footer class="footer">
	<div class="container y-u-max-w-1200">
		<div class="logo">
			<a href="<?php echo esc_url( $settings['logo']['url'] ); ?>">
				<img src="<?php echo esc_url( $settings['logo']['image'] ); ?>" alt="<?php echo esc_attr( $settings['logo']['alt'] ); ?>">
			</a>
		</div>
		<div class="bottom">
			<div class="links y-u-flex y-u-flex-col">
				<h2><?php echo esc_html( $settings['quick']['title'] ); ?></h2>
				<ul class="y-u-flex y-u-justify-between y-u-flex-col">
					<?php foreach ( $settings['quick']['items'] as $item ) : ?>
						<?php if ( ! empty( $item['label'] ) && ! empty( $item['url'] ) ) : ?>
							<li><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="links y-u-flex y-u-flex-col">
				<h2><?php echo esc_html( $settings['policies']['title'] ); ?></h2>
				<ul class="y-u-flex y-u-justify-between y-u-flex-col">
					<?php foreach ( $settings['policies']['items'] as $item ) : ?>
						<?php if ( ! empty( $item['label'] ) && ! empty( $item['url'] ) ) : ?>
							<li><a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			</div>
			<div class="links y-u-flex y-u-flex-col contact-links">
				<h2><?php echo esc_html( $settings['contact']['title'] ); ?></h2>
				<ul class="y-u-flex y-u-justify-between y-u-flex-col">
					<li>
						<a href="#"><img src="<?php echo esc_url( $assets_uri . '/map.svg' ); ?>" alt="أيقونة الموقع"> <?php echo esc_html( $settings['contact']['address']['text'] ); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url( $settings['contact']['email']['url'] ); ?>"><img src="<?php echo esc_url( $assets_uri . '/email.svg' ); ?>" alt="أيقونة البريد الإلكتروني"> <?php echo esc_html( $settings['contact']['email']['label'] ); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url( $settings['contact']['phone']['url'] ); ?>" style="direction: ltr; flex-direction: row-reverse;">
							<img src="<?php echo esc_url( $assets_uri . '/phone.svg' ); ?>" alt="أيقونة الهاتف"><?php echo esc_html( $settings['contact']['phone']['label'] ); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
		<p><?php echo esc_html( $settings['copyright'] ); ?></p>
	</div>
</footer>

<?php if ( $whatsapp_number ) : ?>
	<a class="y-float-btn y-float-whatsapp" href="<?php echo esc_url( 'https://wa.me/' . $whatsapp_number ); ?>" target="_blank" rel="noopener">
		<i class="fa-brands fa-whatsapp"></i>
	</a>
<?php endif; ?>
<?php if ( $call_number ) : ?>
	<a class="y-float-btn y-float-call" href="<?php echo esc_url( 'tel:' . $call_number ); ?>">
		<i class="fa-solid fa-phone"></i>
	</a>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
