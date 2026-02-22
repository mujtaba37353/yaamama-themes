<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stationary_theme_color_keys() {
	return array(
		'header_color'       => array( 'label' => 'لون الهيدر', 'default' => '#6D28D9' ),
		'footer_color'       => array( 'label' => 'لون الفوتر', 'default' => '#6D28D9' ),
		'btn_cart_color'     => array( 'label' => 'لون زر إضافة للسلة', 'default' => '#FACC15' ),
		'btn_checkout_color' => array( 'label' => 'لون زر اتمام الشراء', 'default' => '#FACC15' ),
		'btn_payment_color'  => array( 'label' => 'لون زر اتمام الدفع', 'default' => '#FACC15' ),
		'page_bg_color'      => array( 'label' => 'لون خلفية الصفحات', 'default' => '#F3F4F6' ),
	);
}

add_action( 'stationary_admin_render_stationary_theme_settings', 'stationary_render_theme_settings' );

function stationary_render_theme_settings() {
	$keys = stationary_theme_color_keys();
	$opts = array();
	foreach ( array_keys( $keys ) as $key ) {
		$opts[ $key ] = stationary_get_theme_mod( $key, $keys[ $key ]['default'] );
	}

	if ( ( isset( $_POST['stationary_theme_save'] ) || isset( $_POST['stationary_restore_colors'] ) ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['stationary_theme_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['stationary_theme_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'stationary_theme_settings' ) ) {
			if ( isset( $_POST['stationary_restore_colors'] ) ) {
				foreach ( array_keys( $keys ) as $key ) {
					remove_theme_mod( 'stationary_' . $key );
				}
				echo '<div class="notice notice-success"><p>تم استعادة الألوان الأصلية.</p></div>';
			} else {
				foreach ( array_keys( $keys ) as $key ) {
					$val = isset( $_POST[ $key ] ) ? sanitize_hex_color( wp_unslash( $_POST[ $key ] ) ) : '';
					set_theme_mod( 'stationary_' . $key, $val );
				}
				echo '<div class="notice notice-success"><p>تم الحفظ بنجاح.</p></div>';
			}
			foreach ( array_keys( $keys ) as $key ) {
				$opts[ $key ] = stationary_get_theme_mod( $key, $keys[ $key ]['default'] );
			}
		}
	}
	?>
	<h2>الألوان الحالية</h2>
	<table class="widefat striped" style="max-width: 520px; margin-bottom: 20px;">
		<thead>
			<tr>
				<th>اللون</th>
				<th>القيمة</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $keys as $key => $info ) :
				$val = $opts[ $key ];
				$effective = $val ? $val : $info['default'];
			?>
				<tr>
					<td>
						<span style="display:inline-block;width:24px;height:24px;border:1px solid #ccc;vertical-align:middle;background:<?php echo esc_attr( $effective ); ?>;"></span>
						<?php echo esc_html( $info['label'] ); ?>
					</td>
					<td><code><?php echo esc_html( $effective ); ?></code></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<form method="post">
		<?php wp_nonce_field( 'stationary_theme_settings', 'stationary_theme_nonce' ); ?>
		<table class="form-table">
			<?php foreach ( $keys as $key => $info ) :
				$current = $opts[ $key ] ? $opts[ $key ] : $info['default'];
			?>
				<tr>
					<th><label for="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $info['label'] ); ?></label></th>
					<td><input type="color" id="<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $key ); ?>" value="<?php echo esc_attr( $current ); ?>" /> <input type="text" value="<?php echo esc_attr( $current ); ?>" class="stationary-hex-input" data-for="<?php echo esc_attr( $key ); ?>" style="width:100px" /></td>
				</tr>
			<?php endforeach; ?>
		</table>
		<p>
			<button type="submit" name="stationary_theme_save" class="button button-primary">حفظ</button>
			<button type="submit" name="stationary_restore_colors" class="button" onclick="return confirm('استعادة الألوان الأصلية؟');">استعادة الألوان الأصلية</button>
		</p>
	</form>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		document.querySelectorAll('.stationary-hex-input').forEach(function(t) {
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
