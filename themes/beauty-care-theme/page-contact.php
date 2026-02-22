<?php
get_header();

$assets_uri = get_template_directory_uri() . '/beauty-care/assets';
$contact    = function_exists( 'beauty_care_get_contact_settings' ) ? beauty_care_get_contact_settings() : array();
$sidebar_img = '';
if ( ! empty( $contact['sidebar_image'] ) && wp_attachment_is_image( $contact['sidebar_image'] ) ) {
	$sidebar_img = wp_get_attachment_image_url( $contact['sidebar_image'], 'full' );
} elseif ( file_exists( get_template_directory() . '/beauty-care/assets/way3.jpg' ) ) {
	$sidebar_img = $assets_uri . '/way3.jpg';
}
?>

<main>
	<section class="panner contact y-u-m-b-0">
		<h1 class="y-u-text-center"><?php esc_html_e( 'تواصل معنا', 'beauty-care-theme' ); ?></h1>
		<div class="breadcrumbs container y-u-max-w-1200">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a>
			<p><?php esc_html_e( 'تواصل معنا', 'beauty-care-theme' ); ?></p>
		</div>
	</section>

	<section class="auth-section">
		<div class="container right-left y-u-max-w-1200">
			<div class="right">
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post" id="contact-form">
					<?php wp_nonce_field( 'beauty_care_contact', 'beauty_care_contact_nonce' ); ?>
					<input type="hidden" name="action" value="beauty_care_contact" />
					<div class="form-group">
						<label for="name"><?php esc_html_e( 'الإسم بالكامل', 'beauty-care-theme' ); ?></label>
						<input type="text" id="name" name="name" required>
					</div>
					<div class="form-group">
						<label for="number"><?php esc_html_e( 'رقم الهاتف', 'beauty-care-theme' ); ?></label>
						<input type="tel" id="number" name="number" dir="ltr" maxlength="10" minlength="10" required>
					</div>
					<div class="form-group">
						<label for="email"><?php esc_html_e( 'البريد الإلكتروني', 'beauty-care-theme' ); ?></label>
						<input type="email" id="email" name="email" required>
					</div>
					<div class="form-group">
						<label for="message"><?php esc_html_e( 'اكتب استفسارك من فضلك', 'beauty-care-theme' ); ?></label>
						<textarea name="message" id="message" cols="30" rows="4" style="resize: vertical;" required></textarea>
					</div>
					<button type="submit"><?php esc_html_e( 'إرسال', 'beauty-care-theme' ); ?></button>
				</form>
			</div>
			<?php if ( $sidebar_img ) : ?>
			<div class="left">
				<img src="<?php echo esc_url( $sidebar_img ); ?>" alt="">
			</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="contact-section">
		<div class="container y-u-max-w-1200">
			<h2><?php esc_html_e( 'معلومات التواصل', 'beauty-care-theme' ); ?></h2>
			<div class="contact-grid">
				<?php if ( ! empty( $contact['contact_phone'] ) ) : ?>
				<div class="item">
					<img src="<?php echo esc_url( $assets_uri . '/phone.svg' ); ?>" alt="">
					<p><a href="tel:<?php echo esc_attr( preg_replace( '/\D/', '', $contact['contact_phone'] ) ); ?>" dir="ltr"><?php echo esc_html( $contact['contact_phone'] ); ?></a></p>
				</div>
				<?php endif; ?>
				<?php if ( ! empty( $contact['contact_email_display'] ) ) : ?>
				<div class="item">
					<img src="<?php echo esc_url( $assets_uri . '/email.svg' ); ?>" alt="">
					<p><a href="mailto:<?php echo esc_attr( $contact['contact_email_display'] ); ?>"><?php echo esc_html( $contact['contact_email_display'] ); ?></a></p>
				</div>
				<?php endif; ?>
				<?php if ( ! empty( $contact['contact_address'] ) ) : ?>
				<div class="item">
					<img src="<?php echo esc_url( file_exists( get_template_directory() . '/beauty-care/assets/map.svg' ) ? $assets_uri . '/map.svg' : $assets_uri . '/email.svg' ); ?>" alt="">
					<p><?php echo esc_html( $contact['contact_address'] ); ?></p>
				</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
