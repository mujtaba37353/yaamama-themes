<?php
/**
 * Beauty Care Theme — إعدادات الموقع (ألوان، خطوط).
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beauty_care_default_site_settings() {
	return array(
		'color_primary'   => '#FEE2AD',
		'color_secondary' => '#bda069',
		'color_pink'      => '#D50B8B',
		'color_icon'      => '#F3F6CD',
		'color_bg'        => '#ffffff',
		'color_text'      => '#2a1e07',
		'color_muted'     => '#382607',
		'font_primary'    => 'Cairo',
		'font_weight_normal' => '400',
		'font_weight_bold'   => '700',
	);
}

function beauty_care_get_site_settings() {
	$saved = get_option( 'beauty_care_site_settings', array() );
	return wp_parse_args( $saved, beauty_care_default_site_settings() );
}

function beauty_care_register_site_settings_admin() {
	add_submenu_page(
		'beauty-care-content',
		'إعدادات الموقع',
		'إعدادات الموقع',
		'manage_options',
		'beauty-care-site-settings',
		'beauty_care_render_site_settings_admin'
	);
}
add_action( 'admin_menu', 'beauty_care_register_site_settings_admin', 20 );

function beauty_care_render_site_settings_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = beauty_care_get_site_settings();
	?>
	<div class="wrap">
		<h1>إعدادات الموقع</h1>
		<?php if ( isset( $_GET['beauty_care_site_saved'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم الحفظ بنجاح.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['beauty_care_site_restored'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم استعادة المحتوى الأصلي.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'beauty_care_save_site_settings', 'beauty_care_site_nonce' ); ?>
			<input type="hidden" name="action" value="beauty_care_save_site_settings" />
			<h2>الألوان</h2>
			<table class="form-table">
				<tr><th>Primary</th><td><input type="color" name="color_primary" value="<?php echo esc_attr( $s['color_primary'] ); ?>" /> <input type="text" value="<?php echo esc_attr( $s['color_primary'] ); ?>" class="bc-color-hex" data-for="color_primary" style="width:80px;" dir="ltr" /></td></tr>
				<tr><th>Secondary</th><td><input type="color" name="color_secondary" value="<?php echo esc_attr( $s['color_secondary'] ); ?>" /> <input type="text" value="<?php echo esc_attr( $s['color_secondary'] ); ?>" class="bc-color-hex" data-for="color_secondary" style="width:80px;" dir="ltr" /></td></tr>
				<tr><th>Pink</th><td><input type="color" name="color_pink" value="<?php echo esc_attr( $s['color_pink'] ); ?>" /> <input type="text" value="<?php echo esc_attr( $s['color_pink'] ); ?>" class="bc-color-hex" data-for="color_pink" style="width:80px;" dir="ltr" /></td></tr>
				<tr><th>Icon</th><td><input type="color" name="color_icon" value="<?php echo esc_attr( $s['color_icon'] ); ?>" /> <input type="text" value="<?php echo esc_attr( $s['color_icon'] ); ?>" class="bc-color-hex" data-for="color_icon" style="width:80px;" dir="ltr" /></td></tr>
				<tr><th>Bg</th><td><input type="color" name="color_bg" value="<?php echo esc_attr( $s['color_bg'] ); ?>" /> <input type="text" value="<?php echo esc_attr( $s['color_bg'] ); ?>" class="bc-color-hex" data-for="color_bg" style="width:80px;" dir="ltr" /></td></tr>
				<tr><th>Text</th><td><input type="color" name="color_text" value="<?php echo esc_attr( $s['color_text'] ); ?>" /> <input type="text" value="<?php echo esc_attr( $s['color_text'] ); ?>" class="bc-color-hex" data-for="color_text" style="width:80px;" dir="ltr" /></td></tr>
				<tr><th>Muted</th><td><input type="color" name="color_muted" value="<?php echo esc_attr( $s['color_muted'] ); ?>" /> <input type="text" value="<?php echo esc_attr( $s['color_muted'] ); ?>" class="bc-color-hex" data-for="color_muted" style="width:80px;" dir="ltr" /></td></tr>
			</table>
			<h2>الخطوط</h2>
			<table class="form-table">
				<tr>
					<th><label for="font_primary">الخط الرئيسي</label></th>
					<td>
						<select name="font_primary" id="font_primary">
							<option value="Cairo" <?php selected( $s['font_primary'], 'Cairo' ); ?>>Cairo</option>
							<option value="Tajawal" <?php selected( $s['font_primary'], 'Tajawal' ); ?>>Tajawal</option>
							<option value="Almarai" <?php selected( $s['font_primary'], 'Almarai' ); ?>>Almarai</option>
							<option value="Amiri" <?php selected( $s['font_primary'], 'Amiri' ); ?>>Amiri</option>
							<option value="Readex Pro" <?php selected( $s['font_primary'], 'Readex Pro' ); ?>>Readex Pro</option>
						</select>
						<p class="description">يمكن إضافة خطوط Google أخرى يدوياً</p>
					</td>
				</tr>
				<tr>
					<th><label for="font_weight_normal">وزن النص العادي</label></th>
					<td><input type="text" name="font_weight_normal" id="font_weight_normal" value="<?php echo esc_attr( $s['font_weight_normal'] ); ?>" style="width:60px;" /> (مثال: 400)</td>
				</tr>
				<tr>
					<th><label for="font_weight_bold">وزن النص العريض</label></th>
					<td><input type="text" name="font_weight_bold" id="font_weight_bold" value="<?php echo esc_attr( $s['font_weight_bold'] ); ?>" style="width:60px;" /> (مثال: 700)</td>
				</tr>
			</table>
			<p class="submit">
				<?php submit_button( 'حفظ', 'primary' ); ?>
				<button type="submit" name="restore_default" value="1" class="button" onclick="return confirm('استعادة المحتوى الأصلي؟');">استعادة المحتوى الأصلي</button>
			</p>
		</form>
	</div>
	<script>
	jQuery(function($) {
		$('input[type="color"]').on('input', function() {
			var name = $(this).attr('name');
			$('.bc-color-hex[data-for="' + name + '"]').val($(this).val());
		});
		$('.bc-color-hex').on('input', function() {
			var for_ = $(this).data('for');
			var v = $(this).val();
			if (/^#[0-9A-Fa-f]{6}$/.test(v)) {
				$('input[name="' + for_ + '"]').val(v);
			}
		});
	});
	</script>
	<?php
}

function beauty_care_save_site_settings_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'beauty_care_save_site_settings', 'beauty_care_site_nonce' );
	if ( ! empty( $_POST['restore_default'] ) ) {
		delete_option( 'beauty_care_site_settings' );
		wp_safe_redirect( add_query_arg( 'beauty_care_site_restored', '1', admin_url( 'admin.php?page=beauty-care-site-settings' ) ) );
		exit;
	}
	$colors = array( 'color_primary', 'color_secondary', 'color_pink', 'color_icon', 'color_bg', 'color_text', 'color_muted' );
	$data = array();
	foreach ( $colors as $c ) {
		$v = isset( $_POST[ $c ] ) ? sanitize_hex_color( wp_unslash( $_POST[ $c ] ) ) : '';
		$data[ $c ] = $v ?: beauty_care_default_site_settings()[ $c ];
	}
	$data['font_primary']        = isset( $_POST['font_primary'] ) ? sanitize_text_field( wp_unslash( $_POST['font_primary'] ) ) : 'Cairo';
	$data['font_weight_normal']  = isset( $_POST['font_weight_normal'] ) ? sanitize_text_field( wp_unslash( $_POST['font_weight_normal'] ) ) : '400';
	$data['font_weight_bold']    = isset( $_POST['font_weight_bold'] ) ? sanitize_text_field( wp_unslash( $_POST['font_weight_bold'] ) ) : '700';
	update_option( 'beauty_care_site_settings', $data );
	wp_safe_redirect( add_query_arg( 'beauty_care_site_saved', '1', admin_url( 'admin.php?page=beauty-care-site-settings' ) ) );
	exit;
}
add_action( 'admin_post_beauty_care_save_site_settings', 'beauty_care_save_site_settings_handler' );

/**
 * تحميل خط Google المختار.
 */
function beauty_care_enqueue_site_font() {
	$s = beauty_care_get_site_settings();
	$font = $s['font_primary'] ?? 'Cairo';
	$weights = array( $s['font_weight_normal'] ?? '400', $s['font_weight_bold'] ?? '700' );
	$weights = array_unique( array_filter( $weights ) );
	$family = str_replace( ' ', '+', $font );
	$w = implode( ';', $weights );
	$url = 'https://fonts.googleapis.com/css2?family=' . $family . ':wght@' . $w . '&display=swap';
	wp_enqueue_style( 'beauty-care-site-font', $url, array(), null );
}
add_action( 'wp_enqueue_scripts', 'beauty_care_enqueue_site_font', 5 );

/**
 * إخراج CSS ديناميكي من الإعدادات.
 */
function beauty_care_output_site_settings_css() {
	$s = beauty_care_get_site_settings();
	$css = ':root{';
	$css .= '--y-color-primary:' . esc_attr( $s['color_primary'] ) . ';';
	$css .= '--y-color-secondary:' . esc_attr( $s['color_secondary'] ) . ';';
	$css .= '--y-color-pink:' . esc_attr( $s['color_pink'] ) . ';';
	$css .= '--y-color-icon:' . esc_attr( $s['color_icon'] ) . ';';
	$css .= '--y-color-bg:' . esc_attr( $s['color_bg'] ) . ';';
	$css .= '--y-color-text:' . esc_attr( $s['color_text'] ) . ';';
	$css .= '--y-color-muted:' . esc_attr( $s['color_muted'] ) . ';';
	$css .= '--y-text-normal:' . esc_attr( $s['font_weight_normal'] ) . ';';
	$css .= '--y-text-bold:' . esc_attr( $s['font_weight_bold'] ) . ';';
	$font = esc_attr( $s['font_primary'] );
	$css .= '--y-font-sans:"' . $font . '",ui-sans-serif,system-ui,sans-serif;';
	$css .= '}';
	wp_add_inline_style( 'beauty-care-tokens', $css );
}
add_action( 'wp_enqueue_scripts', 'beauty_care_output_site_settings_css', 20 );
