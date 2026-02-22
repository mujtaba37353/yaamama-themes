<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stationary_home_defaults() {
	return array(
		'hero_title_1'  => 'نظم يومك مع أدواتنا المكتبية الأنيقة',
		'hero_title_2'  => 'كل ما تحتاجه للدراسة أو العمل في مكان واحد.',
		'hero_image_1'  => 0,
		'hero_image_2'  => 0,
		'panner_text_1' => 'حضّر مكتبك... وابدأ يومك بإبداع!',
		'panner_text_2' => 'اكتشف مجموعتنا من الكشاكيل والأقلام المميزة',
		'panner_image'  => 0,
	);
}

add_action( 'stationary_admin_render_stationary_home_settings', 'stationary_render_home_settings' );

function stationary_render_home_settings() {
	$def  = stationary_home_defaults();
	$opts = array(
		'hero_title_1'  => stationary_get_option( 'home_hero_title_1', $def['hero_title_1'] ),
		'hero_title_2'  => stationary_get_option( 'home_hero_title_2', $def['hero_title_2'] ),
		'hero_image_1'  => (int) stationary_get_option( 'home_hero_image_1', 0 ),
		'hero_image_2'  => (int) stationary_get_option( 'home_hero_image_2', 0 ),
		'panner_text_1' => stationary_get_option( 'home_panner_text_1', $def['panner_text_1'] ),
		'panner_text_2' => stationary_get_option( 'home_panner_text_2', $def['panner_text_2'] ),
		'panner_image'  => (int) stationary_get_option( 'home_panner_image', 0 ),
	);

	if ( isset( $_POST['stationary_home_save'] ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['stationary_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['stationary_admin_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'stationary_admin_stationary_home_settings' ) ) {
			if ( isset( $_POST['stationary_restore_home'] ) ) {
				foreach ( $def as $k => $v ) {
					update_option( 'stationary_home_' . $k, $v );
				}
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم استعادة المحتوى الأصلي.', 'stationary-theme' ) . '</p></div>';
			} else {
				update_option( 'stationary_home_hero_title_1', sanitize_text_field( wp_unslash( $_POST['hero_title_1'] ?? '' ) ) );
				update_option( 'stationary_home_hero_title_2', sanitize_text_field( wp_unslash( $_POST['hero_title_2'] ?? '' ) ) );
				update_option( 'stationary_home_hero_image_1', absint( $_POST['hero_image_1'] ?? 0 ) );
				update_option( 'stationary_home_hero_image_2', absint( $_POST['hero_image_2'] ?? 0 ) );
				update_option( 'stationary_home_panner_text_1', sanitize_text_field( wp_unslash( $_POST['panner_text_1'] ?? '' ) ) );
				update_option( 'stationary_home_panner_text_2', sanitize_text_field( wp_unslash( $_POST['panner_text_2'] ?? '' ) ) );
				update_option( 'stationary_home_panner_image', absint( $_POST['panner_image'] ?? 0 ) );
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم الحفظ بنجاح.', 'stationary-theme' ) . '</p></div>';
			}
			$opts = array(
				'hero_title_1'  => stationary_get_option( 'home_hero_title_1', $def['hero_title_1'] ),
				'hero_title_2'  => stationary_get_option( 'home_hero_title_2', $def['hero_title_2'] ),
				'hero_image_1'  => (int) stationary_get_option( 'home_hero_image_1', 0 ),
				'hero_image_2'  => (int) stationary_get_option( 'home_hero_image_2', 0 ),
				'panner_text_1' => stationary_get_option( 'home_panner_text_1', $def['panner_text_1'] ),
				'panner_text_2' => stationary_get_option( 'home_panner_text_2', $def['panner_text_2'] ),
				'panner_image'  => (int) stationary_get_option( 'home_panner_image', 0 ),
			);
		}
	}

	wp_enqueue_media();
	$au = stationary_base_uri() . '/assets';
	$fallbacks = array( 'hero1.jpg', 'hero2.jpg' );
	?>
	<form method="post">
		<?php wp_nonce_field( 'stationary_admin_stationary_home_settings', 'stationary_admin_nonce' ); ?>
		<table class="form-table">
			<tr>
				<th><label for="hero_title_1"><?php esc_html_e( 'عنوان الهيرو ١', 'stationary-theme' ); ?></label></th>
				<td><input type="text" id="hero_title_1" name="hero_title_1" value="<?php echo esc_attr( $opts['hero_title_1'] ); ?>" class="large-text" /></td>
			</tr>
			<tr>
				<th><label for="hero_title_2"><?php esc_html_e( 'عنوان الهيرو ٢', 'stationary-theme' ); ?></label></th>
				<td><input type="text" id="hero_title_2" name="hero_title_2" value="<?php echo esc_attr( $opts['hero_title_2'] ); ?>" class="large-text" /></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'صور الهيرو الحالية', 'stationary-theme' ); ?></th>
				<td>
					<?php for ( $i = 1; $i <= 2; $i++ ) :
						$key = 'hero_image_' . $i;
						$val = $opts[ $key ];
						$img_src = $val ? wp_get_attachment_image_url( $val, 'medium' ) : ( $au . '/' . $fallbacks[ $i - 1 ] );
					?>
						<div style="margin-bottom:14px;padding:8px;border:1px solid #c3c4c7;border-radius:4px;max-width:280px;">
							<div style="margin-top:6px;"><img src="<?php echo esc_url( $img_src ); ?>" alt="" style="max-height:120px;max-width:200px;display:block;object-fit:contain;" onerror="this.src='<?php echo esc_url( $au . '/product-1.png' ); ?>'" /></div>
							<input type="hidden" name="<?php echo esc_attr( $key ); ?>" id="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $val ); ?>" />
							<button type="button" class="button stationary-upload-image" data-target="<?php echo esc_attr( $key ); ?>" style="margin-top:6px;"><?php esc_html_e( 'اختيار صورة', 'stationary-theme' ); ?></button>
							<button type="button" class="button stationary-remove-image" data-target="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( 'إزالة', 'stationary-theme' ); ?></button>
						</div>
					<?php endfor; ?>
				</td>
			</tr>
			<tr>
				<th><label for="panner_text_1"><?php esc_html_e( 'نص البنر ١', 'stationary-theme' ); ?></label></th>
				<td><input type="text" id="panner_text_1" name="panner_text_1" value="<?php echo esc_attr( $opts['panner_text_1'] ); ?>" class="large-text" /></td>
			</tr>
			<tr>
				<th><label for="panner_text_2"><?php esc_html_e( 'نص البنر ٢', 'stationary-theme' ); ?></label></th>
				<td><input type="text" id="panner_text_2" name="panner_text_2" value="<?php echo esc_attr( $opts['panner_text_2'] ); ?>" class="large-text" /></td>
			</tr>
			<tr>
				<th><?php esc_html_e( 'صورة البنر', 'stationary-theme' ); ?></th>
				<td>
					<?php
					$panner_val = $opts['panner_image'];
					$panner_src = $panner_val ? wp_get_attachment_image_url( $panner_val, 'medium' ) : ( $au . '/panner-img.jpg' );
					?>
					<div style="margin-bottom:14px;"><img src="<?php echo esc_url( $panner_src ); ?>" alt="" style="max-height:120px;max-width:200px;object-fit:contain;" onerror="this.src='<?php echo esc_url( $au . '/product-1.png' ); ?>'" id="panner_image_preview" /></div>
					<input type="hidden" name="panner_image" id="panner_image" value="<?php echo esc_attr( $panner_val ); ?>" />
					<button type="button" class="button stationary-upload-image" data-target="panner_image"><?php esc_html_e( 'اختيار صورة', 'stationary-theme' ); ?></button>
					<button type="button" class="button stationary-remove-image" data-target="panner_image"><?php esc_html_e( 'إزالة', 'stationary-theme' ); ?></button>
				</td>
			</tr>
		</table>
		<p>
			<button type="submit" name="stationary_home_save" class="button button-primary"><?php esc_html_e( 'حفظ', 'stationary-theme' ); ?></button>
			<button type="submit" name="stationary_restore_home" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى الأصلي من التصميم؟', 'stationary-theme' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'stationary-theme' ); ?></button>
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
					var prev = document.getElementById(target + '_preview') || document.querySelector('[data-target="' + target + '"]')?.previousElementSibling?.querySelector('img');
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
