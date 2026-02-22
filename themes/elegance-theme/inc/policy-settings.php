<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: policy-settings — إعدادات صفحات السياسات (شحن، استرجاع، خصوصية).

function elegance_policy_defaults() {
	return array(
		'shipping' => '<p>نوضح فيما يلي سياسة الشحن المعتمدة لدينا.</p>',
		'return'   => '<p>نوضح فيما يلي سياسة الاسترجاع والاستبدال.</p>',
		'privacy'   => '<p>نوضح فيما يلي سياسة الخصوصية وحماية البيانات.</p>',
	);
}

function elegance_get_policy_page( $slug ) {
	return get_page_by_path( $slug, OBJECT, 'page' );
}

add_action( 'elegance_admin_render_elegance_shipping_policy', 'elegance_render_shipping_policy_admin' );
add_action( 'elegance_admin_render_elegance_return_policy', 'elegance_render_return_policy_admin' );
add_action( 'elegance_admin_render_elegance_privacy_policy', 'elegance_render_privacy_policy_admin' );

function elegance_render_policy_admin( $slug, $label, $option_key ) {
	$page   = elegance_get_policy_page( $slug );
	$defs   = elegance_policy_defaults();
	$def    = $defs[ $option_key ];
	$content = $page ? $page->post_content : $def;
	$content_source = $page ? __( 'من الصفحة الحالية', 'elegance' ) : __( 'من التصميم الأصلي (الافتراضي)', 'elegance' );

	if ( isset( $_POST['elegance_policy_save'] ) && current_user_can( 'manage_options' ) ) {
		$nonce_key = 'elegance_admin_elegance_' . $option_key . '_policy';
		$nonce = isset( $_POST['elegance_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['elegance_admin_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, $nonce_key ) ) {
			if ( isset( $_POST['elegance_restore_policy'] ) ) {
				$content = $def;
				if ( $page ) {
					wp_update_post( array( 'ID' => $page->ID, 'post_content' => $content ) );
				}
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم استعادة المحتوى الأصلي.', 'elegance' ) . '</p></div>';
			} else {
				$content = wp_kses_post( wp_unslash( $_POST['policy_content'] ?? '' ) );
				if ( $page ) {
					wp_update_post( array( 'ID' => $page->ID, 'post_content' => $content ) );
				}
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم الحفظ.', 'elegance' ) . '</p></div>';
			}
			$page    = elegance_get_policy_page( $slug );
			$content = $page ? $page->post_content : $def;
			$content_source = $page ? __( 'من الصفحة الحالية', 'elegance' ) : __( 'من التصميم الأصلي (الافتراضي)', 'elegance' );
		}
	}
	?>
	<h2 class="title"><?php echo esc_html( $label ); ?> — <?php esc_html_e( 'المحتوى الحالي المستخدم', 'elegance' ); ?></h2>
	<p class="description"><?php esc_html_e( 'المحتوى المعروض أدناه هو المعتمد حالياً على الموقع (من الصفحة أو من التصميم الأصلي).', 'elegance' ); ?></p>

	<table class="widefat striped" style="max-width: 720px; margin-bottom: 20px;">
		<tbody>
			<tr>
				<td><strong><?php esc_html_e( 'المحتوى الحالي المستخدم', 'elegance' ); ?></strong> <span class="description">(<?php echo esc_html( $content_source ); ?>)</span></td>
			</tr>
			<tr>
				<td><div style="max-height:220px;overflow:auto;padding:12px;background:#f6f7f7;border:1px solid #c3c4c7;"><?php echo wp_kses_post( $content ); ?></div></td>
			</tr>
		</tbody>
	</table>

	<form method="post">
		<?php wp_nonce_field( 'elegance_admin_elegance_' . $option_key . '_policy', 'elegance_admin_nonce' ); ?>
		<table class="form-table">
			<tr>
				<th><label for="policy_content"><?php echo esc_html( $label ); ?></label></th>
				<td>
					<textarea id="policy_content" name="policy_content" rows="14" class="large-text"><?php echo esc_textarea( $content ); ?></textarea>
				</td>
			</tr>
		</table>
		<p>
			<button type="submit" name="elegance_policy_save" class="button button-primary"><?php esc_html_e( 'حفظ', 'elegance' ); ?></button>
			<button type="submit" name="elegance_restore_policy" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى الأصلي؟', 'elegance' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'elegance' ); ?></button>
		</p>
	</form>
	<?php
}

function elegance_render_shipping_policy_admin() {
	elegance_render_policy_admin( 'shipping-policy', __( 'سياسة الشحن', 'elegance' ), 'shipping' );
}

function elegance_render_return_policy_admin() {
	elegance_render_policy_admin( 'return-policy', __( 'سياسة الاسترجاع', 'elegance' ), 'return' );
}

function elegance_render_privacy_policy_admin() {
	elegance_render_policy_admin( 'privacy-policy', __( 'سياسة الخصوصية', 'elegance' ), 'privacy' );
}
