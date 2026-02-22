<?php
get_header();
$au           = stationary_base_uri() . '/assets';
$banner_img   = (int) ( function_exists( 'stationary_get_option' ) ? stationary_get_option( 'contact_banner_image', 0 ) : 0 );
$content_img  = (int) ( function_exists( 'stationary_get_option' ) ? stationary_get_option( 'contact_content_image', 0 ) : 0 );
$banner_src   = $banner_img ? wp_get_attachment_image_url( $banner_img, 'full' ) : ( $au . '/panner.jpg' );
$content_src  = $content_img ? wp_get_attachment_image_url( $content_img, 'medium_large' ) : ( $au . '/contact-us.png' );
$banner_style = $banner_img ? ' style="background-image: url(' . esc_url( $banner_src ) . ');"' : '';
?>

<main>
	<section class="panner panner-image y-u-m-b-0 container y-u-max-w-1200"<?php echo $banner_style; ?>>
		<h1 class="y-u-text-center"><?php esc_html_e( 'تواصل معنا', 'stationary-theme' ); ?></h1>
	</section>
	<section class="breadcrumbs container y-u-max-w-1200 y-u-m-b-0 ">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'stationary-theme' ); ?></a>
		<p><?php esc_html_e( 'تواصل معنا', 'stationary-theme' ); ?></p>
	</section>
	<section class="auth-section">
		<div class="container y-u-max-w-1200">
			<div class="right">
				<form action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" method="post">
					<input type="hidden" name="action" value="stationary_contact">
					<?php wp_nonce_field( 'stationary_contact', 'stationary_contact_nonce' ); ?>
					<div class="form-group">
						<label for="contact-name"><?php esc_html_e( 'الاسم', 'stationary-theme' ); ?></label>
						<input type="text" id="contact-name" name="name" required>
					</div>
					<div class="form-group">
						<label for="contact-phone"><?php esc_html_e( 'الهاتف', 'stationary-theme' ); ?></label>
						<input type="tel" id="contact-phone" name="phone">
					</div>
					<div class="form-group">
						<label for="contact-email"><?php esc_html_e( 'البريد الإلكتروني', 'stationary-theme' ); ?></label>
						<input type="email" id="contact-email" name="email" required>
					</div>
					<div class="form-group">
						<label for="contact-message"><?php esc_html_e( 'الرسالة', 'stationary-theme' ); ?></label>
						<textarea id="contact-message" name="message" rows="5"></textarea>
					</div>
					<button type="submit"><?php esc_html_e( 'إرسال', 'stationary-theme' ); ?></button>
				</form>
			</div>
			<div class="left">
				<img src="<?php echo esc_url( $content_src ); ?>" alt="<?php esc_attr_e( 'صورة توضيحية للتواصل معنا', 'stationary-theme' ); ?>">
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
