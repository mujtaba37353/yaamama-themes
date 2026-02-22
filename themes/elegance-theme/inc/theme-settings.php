<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: theme-settings — ألوان الموقع ومتغيرات CSS.

function elegance_theme_color_keys() {
	$list = elegance_theme_color_keys_list();
	$labels = array(
		'header_color'       => __( 'لون الهيدر', 'elegance' ),
		'footer_color'       => __( 'لون الفوتر', 'elegance' ),
		'btn_cart_color'     => __( 'لون زر إضافة للسلة', 'elegance' ),
		'btn_checkout_color' => __( 'لون زر اتمام الشراء', 'elegance' ),
		'btn_payment_color'  => __( 'لون زر اتمام الدفع', 'elegance' ),
		'page_bg_color'      => __( 'لون خلفية الصفحات', 'elegance' ),
	);
	$out = array();
	foreach ( $list as $key => $default ) {
		$out[ $key ] = array( 'label' => $labels[ $key ], 'default' => $default );
	}
	return $out;
}

add_action( 'elegance_admin_render_elegance_theme_settings', 'elegance_render_theme_settings' );

function elegance_render_theme_settings() {
	$keys = elegance_theme_color_keys();
	$opts = array();
	foreach ( array_keys( $keys ) as $key ) {
		$opts[ $key ] = elegance_get_theme_mod( $key, $keys[ $key ]['default'] );
	}

	if ( ( isset( $_POST['elegance_theme_save'] ) || isset( $_POST['elegance_restore_theme_colors'] ) ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['elegance_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['elegance_admin_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'elegance_admin_elegance_theme_settings' ) ) {
			if ( isset( $_POST['elegance_restore_theme_colors'] ) ) {
				foreach ( array_keys( $keys ) as $key ) {
					remove_theme_mod( 'elegance_' . $key );
				}
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم استعادة الألوان الأصلية.', 'elegance' ) . '</p></div>';
			} else {
				foreach ( array_keys( $keys ) as $key ) {
					$val = isset( $_POST[ $key ] ) ? sanitize_hex_color( wp_unslash( $_POST[ $key ] ) ) : '';
					set_theme_mod( 'elegance_' . $key, $val );
				}
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم الحفظ.', 'elegance' ) . '</p></div>';
			}
			foreach ( array_keys( $keys ) as $key ) {
				$opts[ $key ] = elegance_get_theme_mod( $key, $keys[ $key ]['default'] );
			}
		}
	}

	?>
	<h2 class="title"><?php esc_html_e( 'الألوان الحالية المستخدمة', 'elegance' ); ?></h2>
	<table class="widefat striped" style="max-width: 520px; margin-bottom: 20px;">
		<thead>
			<tr>
				<th><?php esc_html_e( 'اللون', 'elegance' ); ?></th>
				<th><?php esc_html_e( 'القيمة الحالية', 'elegance' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $keys as $key => $info ) :
				$val      = $opts[ $key ];
				$effective = $val ? $val : $info['default'];
				$display  = $val ? $val : ( $effective . ' (' . __( 'من التصميم', 'elegance' ) . ')' );
			?>
				<tr>
					<td>
						<span style="display:inline-block;width:24px;height:24px;border:1px solid #ccc;vertical-align:middle;background:<?php echo esc_attr( $effective ); ?>;"></span>
						<?php echo esc_html( $info['label'] ); ?>
					</td>
					<td><code><?php echo esc_html( $display ); ?></code></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<form method="post">
		<?php wp_nonce_field( 'elegance_admin_elegance_theme_settings', 'elegance_admin_nonce' ); ?>
		<table class="form-table">
			<?php foreach ( $keys as $key => $info ) :
				$current = $opts[ $key ] ? $opts[ $key ] : $info['default'];
			?>
				<tr>
					<th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $info['label'] ); ?></label></th>
					<td><input type="color" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $current ); ?>" /> <input type="text" value="<?php echo esc_attr( $current ); ?>" class="elegance-hex-input" data-for="<?php echo esc_attr( $key ); ?>" style="width:100px" placeholder="<?php echo esc_attr( $info['default'] ); ?>" /></td>
				</tr>
			<?php endforeach; ?>
		</table>
		<p>
			<button type="submit" name="elegance_theme_save" class="button button-primary"><?php esc_html_e( 'حفظ', 'elegance' ); ?></button>
			<button type="submit" name="elegance_restore_theme_colors" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة الألوان الأصلية من التصميم؟', 'elegance' ) ); ?>');"><?php esc_html_e( 'استعادة الأصلي', 'elegance' ); ?></button>
		</p>
	</form>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		document.querySelectorAll('.elegance-hex-input').forEach(function(t) {
			var forId = t.getAttribute('data-for');
			var color = document.getElementById(forId);
			function syncToColor() { if (t.value) color.value = t.value; }
			function syncToText() { t.value = color.value; }
			t.addEventListener('input', syncToColor);
			color.addEventListener('input', syncToText);
		});
	});
	</script>
	<?php
}
