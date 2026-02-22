<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stationary_contact_default_content() {
	return '<p>تواصل معنا عبر النموذج أو البريد والهاتف.</p>';
}

add_action( 'stationary_admin_render_stationary_contact_settings', 'stationary_render_contact_settings' );

function stationary_render_contact_settings() {
	$content        = stationary_get_option( 'contact_content', stationary_contact_default_content() );
	$banner_image   = (int) stationary_get_option( 'contact_banner_image', 0 );
	$content_image  = (int) stationary_get_option( 'contact_content_image', 0 );

	if ( isset( $_POST['stationary_contact_save'] ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['stationary_contact_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['stationary_contact_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'stationary_contact' ) ) {
			if ( isset( $_POST['stationary_restore_contact'] ) ) {
				update_option( 'stationary_contact_content', stationary_contact_default_content() );
				update_option( 'stationary_contact_banner_image', 0 );
				update_option( 'stationary_contact_content_image', 0 );
				delete_option( 'stationary_contact_smtp_type' );
				delete_option( 'stationary_contact_smtp_host' );
				delete_option( 'stationary_contact_smtp_user' );
				delete_option( 'stationary_contact_smtp_pass' );
				delete_option( 'stationary_contact_smtp_port' );
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم استعادة المحتوى.', 'stationary-theme' ) . '</p></div>';
				$content       = stationary_contact_default_content();
				$banner_image  = 0;
				$content_image = 0;
			} else {
				$c = isset( $_POST['contact_content'] ) ? wp_kses_post( wp_unslash( $_POST['contact_content'] ) ) : '';
				update_option( 'stationary_contact_content', $c );
				update_option( 'stationary_contact_banner_image', absint( $_POST['contact_banner_image'] ?? 0 ) );
				update_option( 'stationary_contact_content_image', absint( $_POST['contact_content_image'] ?? 0 ) );
				update_option( 'stationary_contact_smtp_type', sanitize_text_field( wp_unslash( $_POST['smtp_type'] ?? 'gmail' ) ) );
				update_option( 'stationary_contact_smtp_host', sanitize_text_field( wp_unslash( $_POST['smtp_host'] ?? '' ) ) );
				update_option( 'stationary_contact_smtp_user', sanitize_text_field( wp_unslash( $_POST['smtp_user'] ?? '' ) ) );
				if ( ! empty( $_POST['smtp_pass'] ?? '' ) ) {
					update_option( 'stationary_contact_smtp_pass', sanitize_text_field( wp_unslash( $_POST['smtp_pass'] ) ) );
				}
				update_option( 'stationary_contact_smtp_port', absint( $_POST['smtp_port'] ?? 587 ) );
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم الحفظ.', 'stationary-theme' ) . '</p></div>';
				$content       = stationary_get_option( 'contact_content', stationary_contact_default_content() );
				$banner_image  = (int) stationary_get_option( 'contact_banner_image', 0 );
				$content_image = (int) stationary_get_option( 'contact_content_image', 0 );
			}
		}
	}

	$smtp_type = stationary_get_option( 'contact_smtp_type', 'gmail' );
	$smtp_host = stationary_get_option( 'contact_smtp_host', '' );
	$smtp_user = stationary_get_option( 'contact_smtp_user', '' );
	$smtp_port = stationary_get_option( 'contact_smtp_port', '587' );

	wp_enqueue_editor();
	wp_enqueue_media();
	$au            = stationary_base_uri() . '/assets';
	$banner_src    = $banner_image ? wp_get_attachment_image_url( $banner_image, 'medium' ) : ( $au . '/panner.jpg' );
	$content_src   = $content_image ? wp_get_attachment_image_url( $content_image, 'medium' ) : ( $au . '/contact-us.png' );
	?>
	<form method="post">
		<?php wp_nonce_field( 'stationary_contact', 'stationary_contact_nonce' ); ?>
		<h2><?php esc_html_e( 'إعداد البريد', 'stationary-theme' ); ?></h2>
		<table class="form-table">
			<tr><th><?php esc_html_e( 'نوع الإرسال', 'stationary-theme' ); ?></th><td><select name="smtp_type"><option value="gmail" <?php selected( $smtp_type, 'gmail' ); ?>>Gmail App Password</option><option value="smtp" <?php selected( $smtp_type, 'smtp' ); ?>>SMTP</option></select></td></tr>
			<tr><th>SMTP Host</th><td><input type="text" name="smtp_host" value="<?php echo esc_attr( $smtp_host ); ?>" class="regular-text" /></td></tr>
			<tr><th><?php esc_html_e( 'البريد / المستخدم', 'stationary-theme' ); ?></th><td><input type="text" name="smtp_user" value="<?php echo esc_attr( $smtp_user ); ?>" class="regular-text" /></td></tr>
			<tr><th><?php esc_html_e( 'كلمة المرور', 'stationary-theme' ); ?></th><td><input type="password" name="smtp_pass" value="" class="regular-text" placeholder="<?php esc_attr_e( 'اتركها فارغة للإبقاء على الحالية', 'stationary-theme' ); ?>" /></td></tr>
			<tr><th><?php esc_html_e( 'البورت', 'stationary-theme' ); ?></th><td><input type="number" name="smtp_port" value="<?php echo esc_attr( $smtp_port ); ?>" class="small-text" /></td></tr>
		</table>
		<h2><?php esc_html_e( 'صور الصفحة', 'stationary-theme' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><?php esc_html_e( 'صورة البنر', 'stationary-theme' ); ?></th>
				<td>
					<div style="margin-bottom:14px;"><img src="<?php echo esc_url( $banner_src ); ?>" alt="" style="max-height:120px;max-width:200px;object-fit:contain;" onerror="this.src='<?php echo esc_url( $au . '/product-1.png' ); ?>'" id="contact_banner_image_preview" /></div>
					<input type="hidden" name="contact_banner_image" id="contact_banner_image" value="<?php echo esc_attr( $banner_image ); ?>" />
					<button type="button" class="button stationary-upload-image" data-target="contact_banner_image"><?php esc_html_e( 'اختيار صورة', 'stationary-theme' ); ?></button>
					<button type="button" class="button stationary-remove-image" data-target="contact_banner_image"><?php esc_html_e( 'إزالة', 'stationary-theme' ); ?></button>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'صورة محتوى الصفحة', 'stationary-theme' ); ?></th>
				<td>
					<div style="margin-bottom:14px;"><img src="<?php echo esc_url( $content_src ); ?>" alt="" style="max-height:120px;max-width:200px;object-fit:contain;" onerror="this.src='<?php echo esc_url( $au . '/product-1.png' ); ?>'" id="contact_content_image_preview" /></div>
					<input type="hidden" name="contact_content_image" id="contact_content_image" value="<?php echo esc_attr( $content_image ); ?>" />
					<button type="button" class="button stationary-upload-image" data-target="contact_content_image"><?php esc_html_e( 'اختيار صورة', 'stationary-theme' ); ?></button>
					<button type="button" class="button stationary-remove-image" data-target="contact_content_image"><?php esc_html_e( 'إزالة', 'stationary-theme' ); ?></button>
				</td>
			</tr>
		</table>
		<h2><?php esc_html_e( 'محتوى الصفحة', 'stationary-theme' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><label for="contact_content"><?php esc_html_e( 'المحتوى', 'stationary-theme' ); ?></label></th>
				<td><?php wp_editor( $content, 'contact_content', array( 'textarea_name' => 'contact_content', 'textarea_rows' => 8, 'media_buttons' => true ) ); ?></td>
			</tr>
		</table>
		<p>
			<button type="submit" name="stationary_contact_save" class="button button-primary"><?php esc_html_e( 'حفظ', 'stationary-theme' ); ?></button>
			<button type="submit" name="stationary_restore_contact" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى؟', 'stationary-theme' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'stationary-theme' ); ?></button>
		</p>
	</form>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		var frame;
		document.querySelectorAll('.stationary-upload-image').forEach(function(btn) {
			btn.addEventListener('click', function() {
				var target = this.getAttribute('data-target');
				if (frame) frame.close();
				frame = wp.media({ library: { type: 'image' }, multiple: false });
				frame.on('select', function() {
					var att = frame.state().get('selection').first().toJSON();
					document.getElementById(target).value = att.id;
					var prev = document.getElementById(target + '_preview');
					if (prev) prev.src = att.url;
					else location.reload();
				});
				frame.open();
			});
		});
		document.querySelectorAll('.stationary-remove-image').forEach(function(btn) {
			btn.addEventListener('click', function() {
				var target = this.getAttribute('data-target');
				document.getElementById(target).value = '0';
				location.reload();
			});
		});
	});
	</script>
	<?php
}
