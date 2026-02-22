<?php
/**
 * Sweet House Theme — إعدادات الموقع (ألوان الهيدر، الفوتر، الأزرار).
 * صفحة فرعية تحت: لوحة التحكم → المحتوى → إعدادات الموقع.
 *
 * @package Sweet_House_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** الخيار (option) name لحفظ الألوان */
define( 'SWEET_HOUSE_SITE_COLORS_OPTION', 'sweet_house_site_colors' );

/**
 * الألوان الافتراضية للثيم (من tokens).
 */
function sweet_house_default_site_colors() {
	return array(
		'color_button' => '#55C1DF',
		'color_header' => '#55C1DF',
		'color_footer' => '#55C1DF',
	);
}

/**
 * الحصول على ألوان الموقع المحفوظة (فارغة = استخدام الافتراضي).
 */
function sweet_house_get_site_colors() {
	$saved = get_option( SWEET_HOUSE_SITE_COLORS_OPTION, array() );
	if ( ! is_array( $saved ) ) {
		return array();
	}
	return array_filter( $saved, 'is_string' );
}

/**
 * تسجيل القائمة: إعدادات الموقع تحت المحتوى.
 */
function sweet_house_register_site_settings_admin() {
	add_submenu_page(
		'sweet-house-content',
		__( 'إعدادات الموقع', 'sweet-house-theme' ),
		__( 'إعدادات الموقع', 'sweet-house-theme' ),
		'manage_options',
		'sweet-house-site-settings',
		'sweet_house_render_site_settings_page'
	);
}
add_action( 'admin_menu', 'sweet_house_register_site_settings_admin', 20 );

/**
 * تسجيل الإعدادات وحفظها.
 */
function sweet_house_site_settings_register_setting() {
	register_setting(
		'sweet_house_site_colors_group',
		SWEET_HOUSE_SITE_COLORS_OPTION,
		array(
			'type'              => 'array',
			'sanitize_callback' => 'sweet_house_sanitize_site_colors',
		)
	);
}
add_action( 'admin_init', 'sweet_house_site_settings_register_setting' );

/**
 * تنظيف قيم الألوان (hex فقط).
 */
function sweet_house_sanitize_site_colors( $input ) {
	if ( ! is_array( $input ) ) {
		return array();
	}
	$defaults = sweet_house_default_site_colors();
	$out      = array();
	foreach ( array( 'color_button', 'color_header', 'color_footer' ) as $key ) {
		$val = isset( $input[ $key ] ) ? trim( (string) $input[ $key ] ) : '';
		if ( $val !== '' && preg_match( '/^#([A-Fa-f0-9]{3}|[A-Fa-f0-9]{6})$/', $val ) ) {
			$out[ $key ] = $val;
		} elseif ( isset( $defaults[ $key ] ) ) {
			$out[ $key ] = $defaults[ $key ];
		}
	}
	// إذا كانت القيم مطابقة للافتراضي، لا تخزين (استخدام ألوان الثيم الأصلية)
	$all_default = true;
	foreach ( $defaults as $k => $v ) {
		$norm = isset( $out[ $k ] ) ? strtoupper( $out[ $k ] ) : '';
		$def  = strtoupper( $v );
		if ( strlen( $norm ) === 4 ) {
			$norm = '#' . $norm[1] . $norm[1] . $norm[2] . $norm[2] . $norm[3] . $norm[3];
		}
		if ( strlen( $def ) === 4 ) {
			$def = '#' . $def[1] . $def[1] . $def[2] . $def[2] . $def[3] . $def[3];
		}
		if ( $norm !== $def ) {
			$all_default = false;
			break;
		}
	}
	if ( $all_default && count( $out ) === count( $defaults ) ) {
		return array();
	}
	return $out;
}

/**
 * استعادة الألوان الأصلية (معالجة زر الاستعادة).
 */
function sweet_house_site_settings_handle_restore() {
	if ( ! isset( $_GET['page'] ) || $_GET['page'] !== 'sweet-house-site-settings' ) {
		return;
	}
	if ( ! isset( $_GET['restore_colors'] ) || $_GET['restore_colors'] !== '1' ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! isset( $_GET['_wpnonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ), 'sweet_house_restore_site_colors' ) ) {
		wp_die( esc_html__( 'الرابط غير صالح.', 'sweet-house-theme' ) );
	}
	delete_option( SWEET_HOUSE_SITE_COLORS_OPTION );
	wp_safe_redirect( add_query_arg( array( 'page' => 'sweet-house-site-settings', 'restored' => '1' ), admin_url( 'admin.php' ) ) );
	exit;
}
add_action( 'admin_init', 'sweet_house_site_settings_handle_restore' );

/**
 * عرض صفحة إعدادات الموقع.
 */
function sweet_house_render_site_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$colors = sweet_house_get_site_colors();
	$defaults = sweet_house_default_site_colors();
	$color_button = isset( $colors['color_button'] ) ? $colors['color_button'] : $defaults['color_button'];
	$color_header = isset( $colors['color_header'] ) ? $colors['color_header'] : $defaults['color_header'];
	$color_footer = isset( $colors['color_footer'] ) ? $colors['color_footer'] : $defaults['color_footer'];

	if ( isset( $_GET['restored'] ) && $_GET['restored'] === '1' ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم استعادة الألوان الأصلية للموقع.', 'sweet-house-theme' ) . '</p></div>';
	}
	if ( isset( $_GET['settings-updated'] ) && ( $_GET['settings-updated'] === 'true' || $_GET['settings-updated'] === '1' ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم حفظ إعدادات الألوان.', 'sweet-house-theme' ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'إعدادات الموقع', 'sweet-house-theme' ); ?></h1>
		<p><?php esc_html_e( 'تعديل الألوان الأساسية للهيدر، الفوتر، والأزرار. اترك الحقل فارغاً لاستخدام اللون الافتراضي.', 'sweet-house-theme' ); ?></p>

		<form method="post" action="options.php" id="sweet-house-site-colors-form" data-option-name="<?php echo esc_attr( SWEET_HOUSE_SITE_COLORS_OPTION ); ?>">
			<?php settings_fields( 'sweet_house_site_colors_group' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row"><?php esc_html_e( 'لون الأزرار', 'sweet-house-theme' ); ?></th>
					<td>
						<input type="color" name="<?php echo esc_attr( SWEET_HOUSE_SITE_COLORS_OPTION ); ?>[color_button]" value="<?php echo esc_attr( $color_button ); ?>" />
						<input type="text" class="regular-text" name="<?php echo esc_attr( SWEET_HOUSE_SITE_COLORS_OPTION ); ?>_display_button" value="<?php echo esc_attr( $color_button ); ?>" data-target="color_button" placeholder="#55C1DF" style="width:120px" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'لون الهيدر', 'sweet-house-theme' ); ?></th>
					<td>
						<input type="color" name="<?php echo esc_attr( SWEET_HOUSE_SITE_COLORS_OPTION ); ?>[color_header]" value="<?php echo esc_attr( $color_header ); ?>" />
						<input type="text" class="regular-text" name="<?php echo esc_attr( SWEET_HOUSE_SITE_COLORS_OPTION ); ?>_display_header" value="<?php echo esc_attr( $color_header ); ?>" data-target="color_header" placeholder="#55C1DF" style="width:120px" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php esc_html_e( 'لون الفوتر', 'sweet-house-theme' ); ?></th>
					<td>
						<input type="color" name="<?php echo esc_attr( SWEET_HOUSE_SITE_COLORS_OPTION ); ?>[color_footer]" value="<?php echo esc_attr( $color_footer ); ?>" />
						<input type="text" class="regular-text" name="<?php echo esc_attr( SWEET_HOUSE_SITE_COLORS_OPTION ); ?>_display_footer" value="<?php echo esc_attr( $color_footer ); ?>" data-target="color_footer" placeholder="#55C1DF" style="width:120px" />
					</td>
				</tr>
			</table>
			<p class="submit">
				<?php submit_button( __( 'حفظ التغييرات', 'sweet-house-theme' ), 'primary', 'submit', false ); ?>
			</p>
		</form>

		<hr />
		<h2><?php esc_html_e( 'استعادة الألوان الأصلية', 'sweet-house-theme' ); ?></h2>
		<p><?php esc_html_e( 'إعادة ألوان الموقع إلى قيمها الافتراضية (الهيدر والفوتر والأزرار: #55C1DF).', 'sweet-house-theme' ); ?></p>
		<a href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'page' => 'sweet-house-site-settings', 'restore_colors' => '1' ), admin_url( 'admin.php' ) ), 'sweet_house_restore_site_colors' ) ); ?>" class="button" onclick="return confirm('<?php echo esc_js( __( 'هل تريد استعادة الألوان الأصلية؟', 'sweet-house-theme' ) ); ?>');"><?php esc_html_e( 'استعادة الألوان الأصلية', 'sweet-house-theme' ); ?></a>
	</div>
	<script>
	(function(){
		var form = document.getElementById('sweet-house-site-colors-form');
		if (!form) return;
		var optionName = form.getAttribute('data-option-name') || 'sweet_house_site_colors';
		var opts = form.querySelectorAll('input[data-target]');
		opts.forEach(function(t){
			var name = optionName + '[' + t.getAttribute('data-target') + ']';
			var colorInput = form.querySelector('input[name="' + name + '"]');
			if (!colorInput) return;
			function syncToColor(){
				var v = t.value.trim();
				if (/^#[0-9A-Fa-f]{3}$/.test(v) || /^#[0-9A-Fa-f]{6}$/.test(v)) colorInput.value = v;
			}
			function syncToText(){
				t.value = colorInput.value;
			}
			t.addEventListener('input', syncToColor);
			t.addEventListener('change', syncToColor);
			colorInput.addEventListener('input', syncToText);
			colorInput.addEventListener('change', syncToText);
		});
	})();
	</script>
	<?php
}

/**
 * في الواجهة الأمامية: حقن CSS للألوان المخصصة (متغيرات + تجاوزات).
 */
function sweet_house_output_site_colors_css() {
	$colors = sweet_house_get_site_colors();
	if ( empty( $colors ) ) {
		return;
	}
	$defaults   = sweet_house_default_site_colors();
	$color_button = isset( $colors['color_button'] ) ? $colors['color_button'] : ( $defaults['color_button'] ?? '#55C1DF' );
	$color_header = isset( $colors['color_header'] ) ? $colors['color_header'] : ( $defaults['color_header'] ?? '#55C1DF' );
	$color_footer = isset( $colors['color_footer'] ) ? $colors['color_footer'] : ( $defaults['color_footer'] ?? '#55C1DF' );

	$css  = ':root {';
	$css .= '--y-site-button: ' . sanitize_hex_color( $color_button ) . ';';
	$css .= '--y-site-header: ' . sanitize_hex_color( $color_header ) . ';';
	$css .= '--y-site-footer: ' . sanitize_hex_color( $color_footer ) . ';';
	$css .= '}';

	// تطبيق الألوان على الهيدر، الفوتر، الأزرار (الهوفر يبقى من ألوان الثيم الأصلية)
	$css .= ' header .container:has(.user-nav):first-child::before { background: var(--y-site-header) !important; }';
	$css .= ' .footer { background: var(--y-site-footer) !important; }';
	$css .= ' .btn-auth, .not-found-container .btn-auth, .order-summary-box .btn-auth, .wc-proceed-to-checkout .btn-auth, .woocommerce .button.alt, .single_add_to_cart_button { background: var(--y-site-button) !important; }';

	wp_register_style( 'sweet-house-site-colors-inline', false, array( 'sweet-house-tokens' ) );
	wp_enqueue_style( 'sweet-house-site-colors-inline' );
	wp_add_inline_style( 'sweet-house-site-colors-inline', $css );
}

add_action( 'wp_enqueue_scripts', 'sweet_house_output_site_colors_css', 25 );
