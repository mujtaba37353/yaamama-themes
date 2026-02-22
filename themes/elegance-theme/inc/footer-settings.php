<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: footer-settings — إعدادات الفوتر (شعار، أرقام، أزرار عائمة).

add_action( 'elegance_admin_render_elegance_footer_settings', 'elegance_render_footer_settings' );

function elegance_render_footer_settings() {
	$logo_id    = (int) elegance_get_option( 'footer_logo', 0 );
	$phone     = elegance_get_option( 'footer_phone', '+966 12 345 6789' );
	$whatsapp  = elegance_get_option( 'footer_whatsapp', '' );
	$floating_contact = elegance_get_option( 'footer_floating_contact', '1' );
	$floating_whatsapp = elegance_get_option( 'footer_floating_whatsapp', '1' );

	if ( isset( $_POST['elegance_footer_save'] ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['elegance_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['elegance_admin_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'elegance_admin_elegance_footer_settings' ) ) {
			update_option( 'elegance_footer_logo', absint( $_POST['footer_logo'] ?? 0 ) );
			update_option( 'elegance_footer_phone', sanitize_text_field( wp_unslash( $_POST['footer_phone'] ?? '' ) ) );
			update_option( 'elegance_footer_whatsapp', sanitize_text_field( wp_unslash( $_POST['footer_whatsapp'] ?? '' ) ) );
			update_option( 'elegance_footer_floating_contact', isset( $_POST['footer_floating_contact'] ) ? '1' : '0' );
			update_option( 'elegance_footer_floating_whatsapp', isset( $_POST['footer_floating_whatsapp'] ) ? '1' : '0' );
			echo '<div class="notice notice-success"><p>' . esc_html__( 'تم الحفظ.', 'elegance' ) . '</p></div>';
			$logo_id    = (int) elegance_get_option( 'footer_logo', 0 );
			$phone     = elegance_get_option( 'footer_phone', '+966 12 345 6789' );
			$whatsapp  = elegance_get_option( 'footer_whatsapp', '' );
			$floating_contact = elegance_get_option( 'footer_floating_contact', '1' );
			$floating_whatsapp = elegance_get_option( 'footer_floating_whatsapp', '1' );
		}
	}

	$design_uri   = get_template_directory_uri() . '/elegance/assets';
	$logo_src     = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : ( $design_uri . '/footer-icon.png' );
	$logo_label   = $logo_id ? __( 'مرفوع', 'elegance' ) : __( 'من التصميم الأصلي', 'elegance' );

	wp_enqueue_media();
	?>
	<h2 class="title"><?php esc_html_e( 'الشعار الحالي المستخدم', 'elegance' ); ?></h2>
	<table class="widefat striped" style="max-width: 360px; margin-bottom: 20px;">
		<tbody>
			<tr>
				<td><strong><?php esc_html_e( 'شعار الفوتر', 'elegance' ); ?></strong> <span class="description"><?php echo esc_html( $logo_label ); ?></span></td>
			</tr>
			<tr>
				<td><img src="<?php echo esc_url( $logo_src ); ?>" alt="" style="max-height:80px;max-width:200px;object-fit:contain;" /></td>
			</tr>
		</tbody>
	</table>

	<form method="post">
		<?php wp_nonce_field( 'elegance_admin_elegance_footer_settings', 'elegance_admin_nonce' ); ?>
		<table class="form-table">
			<tr>
				<th><?php esc_html_e( 'شعار الفوتر', 'elegance' ); ?></th>
				<td>
					<input type="hidden" name="footer_logo" id="footer_logo" value="<?php echo esc_attr( $logo_id ); ?>" />
					<button type="button" class="button elegance-upload-image" data-target="footer_logo"><?php esc_html_e( 'اختر صورة', 'elegance' ); ?></button>
					<?php if ( $logo_id ) : ?><img src="<?php echo esc_url( wp_get_attachment_image_url( $logo_id, 'thumbnail' ) ); ?>" style="max-height:60px;vertical-align:middle;margin-right:8px;" alt="" /><?php endif; ?>
				</td>
			</tr>
			<tr>
				<th><label for="footer_phone"><?php esc_html_e( 'رقم الاتصال', 'elegance' ); ?></label></th>
				<td><input type="text" id="footer_phone" name="footer_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="footer_whatsapp"><?php esc_html_e( 'رقم الواتساب', 'elegance' ); ?></label></th>
				<td><input type="text" id="footer_whatsapp" name="footer_whatsapp" value="<?php echo esc_attr( $whatsapp ); ?>" class="regular-text" placeholder="966501234567" /></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'زر اتصال عائم', 'elegance' ); ?></th>
				<td><label><input type="checkbox" name="footer_floating_contact" value="1" <?php checked( $floating_contact, '1' ); ?> /> <?php esc_html_e( 'تفعيل', 'elegance' ); ?></label></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'زر واتساب عائم', 'elegance' ); ?></th>
				<td><label><input type="checkbox" name="footer_floating_whatsapp" value="1" <?php checked( $floating_whatsapp, '1' ); ?> /> <?php esc_html_e( 'تفعيل', 'elegance' ); ?></label></td>
			</tr>
		</table>
		<p><button type="submit" name="elegance_footer_save" class="button button-primary"><?php esc_html_e( 'حفظ', 'elegance' ); ?></button></p>
	</form>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		var frame;
		document.querySelectorAll('.elegance-upload-image').forEach(function(btn) {
			btn.addEventListener('click', function() {
				var target = this.getAttribute('data-target');
				if (frame) frame.close();
				frame = wp.media({ library: { type: 'image' }, multiple: false });
				frame.on('select', function() {
					var att = frame.state().get('selection').first().toJSON();
					document.getElementById(target).value = att.id;
					location.reload();
				});
				frame.open();
			});
		});
	});
	</script>
	<?php
}
