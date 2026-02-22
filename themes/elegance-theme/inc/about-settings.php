<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: about-settings — إعدادات "من نحن".

function elegance_about_default_content() {
	return '<p>Lorem ipsum dolor sit amet consectetur. Pellentesque lacus pulvinar imperdiet cursus dui amet a amet. Enim eget etiam varius sed. Mattis arcu sed non tempus dui consequat pellentesque mattis. Orci sagittis eget nisi nunc quis sed. Iaculis malesuada id nisl pellentesque diam tempus iaculis pretium magna. Diam purus sit enim hendrerit. Facilisis ac aliquet pretium ullamcorper. In erat in purus nund.</p>';
}

add_action( 'elegance_admin_render_elegance_about_settings', 'elegance_render_about_settings' );

function elegance_render_about_settings() {
	$page_about = get_page_by_path( 'about-us', OBJECT, 'page' );
	$content    = $page_about ? $page_about->post_content : elegance_about_default_content();
	$image_id   = (int) elegance_get_option( 'about_image', 0 );
	$design_uri = get_template_directory_uri() . '/elegance/assets';
	$about_img_src = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : ( $design_uri . '/abouts-us.png' );
	$about_img_label = $image_id ? __( 'مرفوعة', 'elegance' ) : __( 'من التصميم الأصلي', 'elegance' );

	if ( isset( $_POST['elegance_about_save'] ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['elegance_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['elegance_admin_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'elegance_admin_elegance_about_settings' ) ) {
			if ( isset( $_POST['elegance_restore_about'] ) ) {
				$content   = elegance_about_default_content();
				$image_id  = 0;
				update_option( 'elegance_about_image', 0 );
				if ( $page_about ) {
					wp_update_post( array( 'ID' => $page_about->ID, 'post_content' => $content ) );
				}
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم استعادة المحتوى الأصلي.', 'elegance' ) . '</p></div>';
			} else {
				$content  = wp_kses_post( wp_unslash( $_POST['about_content'] ?? '' ) );
				$image_id = absint( $_POST['about_image'] ?? 0 );
				update_option( 'elegance_about_image', $image_id );
				if ( $page_about ) {
					wp_update_post( array( 'ID' => $page_about->ID, 'post_content' => $content ) );
				}
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم الحفظ.', 'elegance' ) . '</p></div>';
			}
			$page_about = get_page_by_path( 'about-us', OBJECT, 'page' );
			$content    = $page_about ? get_post( $page_about->ID )->post_content : elegance_about_default_content();
			$image_id   = (int) elegance_get_option( 'about_image', 0 );
			$about_img_src   = $image_id ? wp_get_attachment_image_url( $image_id, 'medium' ) : ( $design_uri . '/abouts-us.png' );
			$about_img_label = $image_id ? __( 'مرفوعة', 'elegance' ) : __( 'من التصميم الأصلي', 'elegance' );
		}
	}

	wp_enqueue_media();
	?>
	<h2 class="title"><?php esc_html_e( 'من نحن — المحتوى والصور الحالية المستخدمة', 'elegance' ); ?></h2>
	<p class="description"><?php esc_html_e( 'المحتوى والصور المعروضة أدناه هي المعتمدة حالياً على صفحة من نحن. المحتوى من الصفحة أو التصميم الأصلي، والصورة إما مرفوعة أو من التصميم.', 'elegance' ); ?></p>

	<table class="widefat striped" style="max-width: 640px; margin-bottom: 20px;">
		<tbody>
			<tr>
				<td><strong><?php esc_html_e( 'المحتوى الحالي المستخدم', 'elegance' ); ?></strong></td>
			</tr>
			<tr>
				<td><div style="max-height:180px;overflow:auto;padding:10px;background:#f6f7f7;border:1px solid #c3c4c7;"><?php echo wp_kses_post( $content ); ?></div></td>
			</tr>
			<tr>
				<td><strong><?php esc_html_e( 'الصورة الحالية المستخدمة', 'elegance' ); ?></strong> <span class="description"><?php echo esc_html( $about_img_label ); ?></span></td>
			</tr>
			<tr>
				<td><img src="<?php echo esc_url( $about_img_src ); ?>" alt="" style="max-height:200px;max-width:320px;object-fit:contain;" /></td>
			</tr>
		</tbody>
	</table>

	<form method="post">
		<?php wp_nonce_field( 'elegance_admin_elegance_about_settings', 'elegance_admin_nonce' ); ?>
		<table class="form-table">
			<tr>
				<th><label for="about_content"><?php esc_html_e( 'محتوى من نحن', 'elegance' ); ?></label></th>
				<td>
					<?php
					wp_editor( $content, 'about_content', array(
						'textarea_name' => 'about_content',
						'textarea_rows' => 12,
						'media_buttons' => true,
					) );
					?>
				</td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'صورة من نحن', 'elegance' ); ?></th>
				<td>
					<input type="hidden" name="about_image" id="about_image" value="<?php echo esc_attr( $image_id ); ?>" />
					<button type="button" class="button elegance-upload-image" data-target="about_image"><?php esc_html_e( 'اختر صورة', 'elegance' ); ?></button>
					<?php if ( $image_id ) : ?><img src="<?php echo esc_url( wp_get_attachment_image_url( $image_id, 'thumbnail' ) ); ?>" style="max-height:80px;vertical-align:middle;margin-right:8px;" alt="" /><?php endif; ?>
				</td>
			</tr>
		</table>
		<p>
			<button type="submit" name="elegance_about_save" class="button button-primary"><?php esc_html_e( 'حفظ', 'elegance' ); ?></button>
			<button type="submit" name="elegance_restore_about" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى الأصلي؟', 'elegance' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'elegance' ); ?></button>
		</p>
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
