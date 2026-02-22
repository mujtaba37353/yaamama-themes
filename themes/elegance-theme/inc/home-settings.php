<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: home-settings — إعدادات الصفحة الرئيسية (هيرو، أزرار، صور).

function elegance_home_defaults() {
	return array(
		'hero_title'    => 'خليك مميز .. خليك أنيق',
		'hero_desc'     => 'متجر اليجنس يقدم لك تشكيلات عصرية بجودة عالية وسعر يناسبك',
		'hero_btn_text' => 'اكتشف المجموعة',
		'hero_btn_url'  => '',
		'banner_1_text' => 'خصومات تصل لـ 50% - الحق عروض الموسم قبل ما تخلص !',
		'banner_2_text' => 'أطلق ستايلك... كل قطعة تحكي عنك',
	);
}

add_action( 'elegance_admin_render_elegance_home_settings', 'elegance_render_home_settings' );

function elegance_render_home_settings() {
	$opts = array(
		'hero_title'    => elegance_get_option( 'home_hero_title', elegance_home_defaults()['hero_title'] ),
		'hero_desc'    => elegance_get_option( 'home_hero_desc', elegance_home_defaults()['hero_desc'] ),
		'hero_btn_text' => elegance_get_option( 'home_hero_btn_text', elegance_home_defaults()['hero_btn_text'] ),
		'hero_btn_url'  => elegance_get_option( 'home_hero_btn_url', '' ),
		'hero_image_1'  => (int) elegance_get_option( 'home_hero_image_1', 0 ),
		'hero_image_2'  => (int) elegance_get_option( 'home_hero_image_2', 0 ),
		'hero_image_3'  => (int) elegance_get_option( 'home_hero_image_3', 0 ),
		'banner_1_text' => elegance_get_option( 'home_banner_1_text', elegance_home_defaults()['banner_1_text'] ),
		'banner_2_text' => elegance_get_option( 'home_banner_2_text', elegance_home_defaults()['banner_2_text'] ),
	);

	if ( isset( $_POST['elegance_home_save'] ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['elegance_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['elegance_admin_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'elegance_admin_elegance_home_settings' ) ) {
			if ( isset( $_POST['elegance_restore_home'] ) ) {
				$def = elegance_home_defaults();
				update_option( 'elegance_home_hero_title', $def['hero_title'] );
				update_option( 'elegance_home_hero_desc', $def['hero_desc'] );
				update_option( 'elegance_home_hero_btn_text', $def['hero_btn_text'] );
				update_option( 'elegance_home_hero_btn_url', '' );
				update_option( 'elegance_home_hero_image_1', 0 );
				update_option( 'elegance_home_hero_image_2', 0 );
				update_option( 'elegance_home_hero_image_3', 0 );
				update_option( 'elegance_home_banner_1_text', $def['banner_1_text'] );
				update_option( 'elegance_home_banner_2_text', $def['banner_2_text'] );
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم استعادة المحتوى الأصلي.', 'elegance' ) . '</p></div>';
			} else {
				update_option( 'elegance_home_hero_title', sanitize_text_field( wp_unslash( $_POST['hero_title'] ?? '' ) ) );
				update_option( 'elegance_home_hero_desc', sanitize_textarea_field( wp_unslash( $_POST['hero_desc'] ?? '' ) ) );
				update_option( 'elegance_home_hero_btn_text', sanitize_text_field( wp_unslash( $_POST['hero_btn_text'] ?? '' ) ) );
				update_option( 'elegance_home_hero_btn_url', esc_url_raw( wp_unslash( $_POST['hero_btn_url'] ?? '' ) ) );
				update_option( 'elegance_home_hero_image_1', absint( $_POST['hero_image_1'] ?? 0 ) );
				update_option( 'elegance_home_hero_image_2', absint( $_POST['hero_image_2'] ?? 0 ) );
				update_option( 'elegance_home_hero_image_3', absint( $_POST['hero_image_3'] ?? 0 ) );
				update_option( 'elegance_home_banner_1_text', sanitize_text_field( wp_unslash( $_POST['banner_1_text'] ?? '' ) ) );
				update_option( 'elegance_home_banner_2_text', sanitize_text_field( wp_unslash( $_POST['banner_2_text'] ?? '' ) ) );
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم الحفظ.', 'elegance' ) . '</p></div>';
			}
			$opts = array(
				'hero_title'    => elegance_get_option( 'home_hero_title', elegance_home_defaults()['hero_title'] ),
				'hero_desc'    => elegance_get_option( 'home_hero_desc', elegance_home_defaults()['hero_desc'] ),
				'hero_btn_text' => elegance_get_option( 'home_hero_btn_text', elegance_home_defaults()['hero_btn_text'] ),
				'hero_btn_url'  => elegance_get_option( 'home_hero_btn_url', '' ),
				'hero_image_1'  => (int) elegance_get_option( 'home_hero_image_1', 0 ),
				'hero_image_2'  => (int) elegance_get_option( 'home_hero_image_2', 0 ),
				'hero_image_3'  => (int) elegance_get_option( 'home_hero_image_3', 0 ),
				'banner_1_text' => elegance_get_option( 'home_banner_1_text', elegance_home_defaults()['banner_1_text'] ),
				'banner_2_text' => elegance_get_option( 'home_banner_2_text', elegance_home_defaults()['banner_2_text'] ),
			);
		}
	}

	wp_enqueue_media();
	$shop_url  = function_exists( 'elegance_shop_url' ) ? elegance_shop_url() : home_url( '/shop/' );
	$design_uri = get_template_directory_uri() . '/elegance/assets';
	$hero_fallbacks = array( 1 => 'hero.jpg', 2 => 'hero2.jpg', 3 => 'hero3.jpg' );
	?>
	<h2 class="title"><?php esc_html_e( 'المحتوى والصور الحالية المستخدمة', 'elegance' ); ?></h2>
	<p class="description"><?php esc_html_e( 'النصوص والصور المعروضة أدناه هي المعتمدة حالياً على الصفحة الرئيسية. الصور إما مرفوعة أو من التصميم الأصلي.', 'elegance' ); ?></p>
	<form method="post">
		<?php wp_nonce_field( 'elegance_admin_elegance_home_settings', 'elegance_admin_nonce' ); ?>
		<table class="form-table">
			<tr>
				<th><label for="hero_title"><?php esc_html_e( 'عنوان الهيرو', 'elegance' ); ?></label></th>
				<td><input type="text" id="hero_title" name="hero_title" value="<?php echo esc_attr( $opts['hero_title'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="hero_desc"><?php esc_html_e( 'الوصف', 'elegance' ); ?></label></th>
				<td><textarea id="hero_desc" name="hero_desc" rows="3" class="large-text"><?php echo esc_textarea( $opts['hero_desc'] ); ?></textarea></td>
			</tr>
			<tr>
				<th><label for="hero_btn_text"><?php esc_html_e( 'نص الزر', 'elegance' ); ?></label></th>
				<td><input type="text" id="hero_btn_text" name="hero_btn_text" value="<?php echo esc_attr( $opts['hero_btn_text'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="hero_btn_url"><?php esc_html_e( 'رابط الزر', 'elegance' ); ?></label></th>
				<td><input type="url" id="hero_btn_url" name="hero_btn_url" value="<?php echo esc_attr( $opts['hero_btn_url'] ); ?>" class="regular-text" placeholder="<?php echo esc_attr( $shop_url ); ?>" /></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'صور الهيرو الحالية المستخدمة (1، 2، 3)', 'elegance' ); ?></th>
				<td>
					<?php for ( $i = 1; $i <= 3; $i++ ) :
						$key = 'hero_image_' . $i;
						$val = $opts[ $key ];
						$img_src = $val ? wp_get_attachment_image_url( $val, 'medium' ) : ( $design_uri . '/' . $hero_fallbacks[ $i ] );
						$img_label = $val ? __( 'مرفوعة', 'elegance' ) : __( 'من التصميم الأصلي', 'elegance' );
					?>
						<div style="margin-bottom:14px;padding:8px;border:1px solid #c3c4c7;border-radius:4px;max-width:280px;">
							<strong><?php echo esc_html( sprintf( __( 'صورة %d:', 'elegance' ), $i ) ); ?></strong> <span class="description"><?php echo esc_html( $img_label ); ?></span>
							<div style="margin-top:6px;">
								<img src="<?php echo esc_url( $img_src ); ?>" alt="" style="max-height:120px;max-width:200px;display:block;object-fit:contain;" />
							</div>
							<input type="hidden" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $val ); ?>" />
							<button type="button" class="button elegance-upload-image" data-target="<?php echo esc_attr( $key ); ?>" style="margin-top:6px;"><?php esc_html_e( 'اختر صورة', 'elegance' ); ?></button>
						</div>
					<?php endfor; ?>
				</td>
			</tr>
			<tr>
				<th><label for="banner_1_text"><?php esc_html_e( 'بنر ١', 'elegance' ); ?></label></th>
				<td><input type="text" id="banner_1_text" name="banner_1_text" value="<?php echo esc_attr( $opts['banner_1_text'] ); ?>" class="large-text" /></td>
			</tr>
			<tr>
				<th><label for="banner_2_text"><?php esc_html_e( 'بنر ٢', 'elegance' ); ?></label></th>
				<td><input type="text" id="banner_2_text" name="banner_2_text" value="<?php echo esc_attr( $opts['banner_2_text'] ); ?>" class="large-text" /></td>
			</tr>
		</table>
		<p>
			<button type="submit" name="elegance_home_save" class="button button-primary"><?php esc_html_e( 'حفظ', 'elegance' ); ?></button>
			<button type="submit" name="elegance_restore_home" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى الأصلي من التصميم؟', 'elegance' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'elegance' ); ?></button>
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
