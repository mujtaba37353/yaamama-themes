<?php
if ( ! defined( 'ABSPATH' ) ) exit;
add_action( 'stationary_admin_render_stationary_footer_settings', 'stationary_render_footer_settings' );
function stationary_render_footer_settings() {
	$header_logo = (int) stationary_get_option( 'footer_header_logo', 0 );
	$footer_logo = (int) stationary_get_option( 'footer_footer_logo', 0 );
	$phone = stationary_get_option( 'footer_phone', '+966 12 345 6789' );
	$whatsapp = stationary_get_option( 'footer_whatsapp', '' );
	$email = stationary_get_option( 'footer_email', 'Stationary@gmail.com' );
	if ( isset( $_POST['stationary_footer_save'] ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['stationary_footer_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['stationary_footer_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'stationary_footer' ) ) {
			if ( isset( $_POST['stationary_restore_footer'] ) ) {
				update_option( 'stationary_footer_header_logo', 0 );
				update_option( 'stationary_footer_footer_logo', 0 );
				update_option( 'stationary_footer_phone', '+966 12 345 6789' );
				update_option( 'stationary_footer_whatsapp', '' );
				update_option( 'stationary_footer_email', 'Stationary@gmail.com' );
				echo '<div class="notice notice-success"><p>تم الاستعادة.</p></div>';
			} else {
				update_option( 'stationary_footer_header_logo', absint( $_POST['header_logo'] ?? 0 ) );
				update_option( 'stationary_footer_footer_logo', absint( $_POST['footer_logo'] ?? 0 ) );
				update_option( 'stationary_footer_phone', sanitize_text_field( wp_unslash( $_POST['footer_phone'] ?? '' ) ) );
				update_option( 'stationary_footer_whatsapp', sanitize_text_field( wp_unslash( $_POST['footer_whatsapp'] ?? '' ) ) );
				update_option( 'stationary_footer_email', sanitize_email( wp_unslash( $_POST['footer_email'] ?? '' ) ) );
				echo '<div class="notice notice-success"><p>تم الحفظ.</p></div>';
			}
			$header_logo = (int) stationary_get_option( 'footer_header_logo', 0 );
			$footer_logo = (int) stationary_get_option( 'footer_footer_logo', 0 );
			$phone = stationary_get_option( 'footer_phone', '+966 12 345 6789' );
			$whatsapp = stationary_get_option( 'footer_whatsapp', '' );
			$email = stationary_get_option( 'footer_email', 'Stationary@gmail.com' );
		}
	}
	wp_enqueue_media();
	$au = stationary_base_uri() . '/assets';
	$header_src = $header_logo ? wp_get_attachment_image_url( $header_logo, 'medium' ) : ( $au . '/navbar-icon.png' );
	$footer_src = $footer_logo ? wp_get_attachment_image_url( $footer_logo, 'medium' ) : ( $au . '/footer-icon.png' );
	?>
	<form method="post">
		<?php wp_nonce_field( 'stationary_footer', 'stationary_footer_nonce' ); ?>
		<table class="form-table">
			<tr><th>شعار الهيدر</th><td><img src="<?php echo esc_url( $header_src ); ?>" alt="" style="max-height:60px;max-width:150px;" onerror="this.style.display='none'" /><br><input type="hidden" name="header_logo" id="header_logo" value="<?php echo esc_attr( $header_logo ); ?>" /><button type="button" class="button stationary-upload-image" data-target="header_logo">اختيار صورة</button><button type="button" class="button stationary-remove-image" data-target="header_logo">إزالة</button></td></tr>
			<tr><th>شعار الفوتر</th><td><img src="<?php echo esc_url( $footer_src ); ?>" alt="" style="max-height:60px;max-width:150px;" onerror="this.style.display='none'" /><br><input type="hidden" name="footer_logo" id="footer_logo" value="<?php echo esc_attr( $footer_logo ); ?>" /><button type="button" class="button stationary-upload-image" data-target="footer_logo">اختيار صورة</button><button type="button" class="button stationary-remove-image" data-target="footer_logo">إزالة</button></td></tr>
			<tr><th><label for="footer_phone">رقم الاتصال</label></th><td><input type="text" id="footer_phone" name="footer_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" /></td></tr>
			<tr><th><label for="footer_whatsapp">رقم الواتساب</label></th><td><input type="text" id="footer_whatsapp" name="footer_whatsapp" value="<?php echo esc_attr( $whatsapp ); ?>" class="regular-text" /></td></tr>
			<tr><th><label for="footer_email">البريد الإلكتروني</label></th><td><input type="email" id="footer_email" name="footer_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text" /></td></tr>
		</table>
		<p><button type="submit" name="stationary_footer_save" class="button button-primary">حفظ</button><button type="submit" name="stationary_restore_footer" class="button" onclick="return confirm('استعادة المحتوى؟');">استعادة المحتوى الأصلي</button></p>
	</form>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		var frame;
		document.querySelectorAll('.stationary-upload-image').forEach(function(btn) {
			btn.addEventListener('click', function() { var target = this.getAttribute('data-target'); if (frame) frame.close(); frame = wp.media({ library: { type: 'image' }, multiple: false }); frame.on('select', function() { var att = frame.state().get('selection').first().toJSON(); document.getElementById(target).value = att.id; location.reload(); }); frame.open(); });
		});
		document.querySelectorAll('.stationary-remove-image').forEach(function(btn) {
			btn.addEventListener('click', function() { document.getElementById(this.getAttribute('data-target')).value = '0'; location.reload(); });
		});
	});
	</script>
	<?php
}
