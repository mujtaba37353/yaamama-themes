<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stationary_about_default_content() {
	return '<p>نحن نؤمن أن الإلهام يبدأ من التفاصيل الصغيرة. لذلك نقدّم لك أدوات مكتبية تجمع بين الأناقة، الجودة، والعملية.</p><p>رسالتنا هي دعم الإبداع وتنمية روح التنظيم لدى كل شخص.</p>';
}

add_action( 'stationary_admin_render_stationary_about_settings', 'stationary_render_about_settings' );

function stationary_render_about_settings() {
	$content      = stationary_get_option( 'about_content', stationary_about_default_content() );
	$banner_image = (int) stationary_get_option( 'about_banner_image', 0 );

	if ( isset( $_POST['stationary_about_save'] ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['stationary_about_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['stationary_about_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'stationary_about' ) ) {
			if ( isset( $_POST['stationary_restore_about'] ) ) {
				update_option( 'stationary_about_content', stationary_about_default_content() );
				update_option( 'stationary_about_banner_image', 0 );
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم استعادة المحتوى.', 'stationary-theme' ) . '</p></div>';
				$content      = stationary_about_default_content();
				$banner_image = 0;
			} else {
				$c = isset( $_POST['about_content'] ) ? wp_kses_post( wp_unslash( $_POST['about_content'] ) ) : '';
				update_option( 'stationary_about_content', $c );
				update_option( 'stationary_about_banner_image', absint( $_POST['about_banner_image'] ?? 0 ) );
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم الحفظ.', 'stationary-theme' ) . '</p></div>';
				$content      = stationary_get_option( 'about_content', stationary_about_default_content() );
				$banner_image = (int) stationary_get_option( 'about_banner_image', 0 );
			}
		}
	}

	wp_enqueue_editor();
	wp_enqueue_media();
	$au           = stationary_base_uri() . '/assets';
	$banner_src   = $banner_image ? wp_get_attachment_image_url( $banner_image, 'medium' ) : ( $au . '/panner.jpg' );
	?>
	<form method="post">
		<?php wp_nonce_field( 'stationary_about', 'stationary_about_nonce' ); ?>
		<table class="form-table">
			<tr>
				<th><?php esc_html_e( 'صورة البنر', 'stationary-theme' ); ?></th>
				<td>
					<div style="margin-bottom:14px;"><img src="<?php echo esc_url( $banner_src ); ?>" alt="" style="max-height:120px;max-width:200px;object-fit:contain;" onerror="this.src='<?php echo esc_url( $au . '/product-1.png' ); ?>'" id="about_banner_image_preview" /></div>
					<input type="hidden" name="about_banner_image" id="about_banner_image" value="<?php echo esc_attr( $banner_image ); ?>" />
					<button type="button" class="button stationary-upload-image" data-target="about_banner_image"><?php esc_html_e( 'اختيار صورة', 'stationary-theme' ); ?></button>
					<button type="button" class="button stationary-remove-image" data-target="about_banner_image"><?php esc_html_e( 'إزالة', 'stationary-theme' ); ?></button>
				</td>
			</tr>
			<tr>
				<th><label for="about_content"><?php esc_html_e( 'محتوى الصفحة', 'stationary-theme' ); ?></label></th>
				<td><?php wp_editor( $content, 'about_content', array( 'textarea_name' => 'about_content', 'textarea_rows' => 12, 'media_buttons' => true ) ); ?></td>
			</tr>
		</table>
		<p>
			<button type="submit" name="stationary_about_save" class="button button-primary"><?php esc_html_e( 'حفظ', 'stationary-theme' ); ?></button>
			<button type="submit" name="stationary_restore_about" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى؟', 'stationary-theme' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'stationary-theme' ); ?></button>
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
