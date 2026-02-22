<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stationary_policy_default( $slug ) {
	$defaults = array(
		'shipping-policy' => '<h2>سياسة الشحن</h2><p>نوفر خدمة شحن لجميع مناطق المملكة. مدة التوصيل من 2 إلى 5 أيام عمل.</p>',
		'return-policy'   => '<h2>سياسة الاسترجاع</h2><p>يمكنك استرجاع المنتجات خلال 14 يوم من الاستلام في حال عدم استخدامها.</p>',
		'privacy-policy'  => '<h2>سياسة الخصوصية</h2><p>نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية وفق الأنظمة المعمول بها.</p>',
	);
	return isset( $defaults[ $slug ] ) ? $defaults[ $slug ] : '';
}

add_action( 'stationary_admin_render_stationary_shipping_policy', 'stationary_render_shipping_policy' );
add_action( 'stationary_admin_render_stationary_return_policy', 'stationary_render_return_policy' );
add_action( 'stationary_admin_render_stationary_privacy_policy', 'stationary_render_privacy_policy' );

function stationary_render_policy_page( $slug, $label ) {
	$def = stationary_policy_default( $slug );
	$content = stationary_get_option( $slug . '_content', $def );

	if ( isset( $_POST['stationary_policy_save'] ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['stationary_policy_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['stationary_policy_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'stationary_policy_' . $slug ) ) {
			if ( isset( $_POST['stationary_restore_policy'] ) ) {
				update_option( 'stationary_' . $slug . '_content', $def );
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم استعادة المحتوى الأصلي.', 'stationary-theme' ) . '</p></div>';
				$content = $def;
			} else {
				$c = isset( $_POST['policy_content'] ) ? wp_kses_post( wp_unslash( $_POST['policy_content'] ) ) : '';
				update_option( 'stationary_' . $slug . '_content', $c );
				echo '<div class="notice notice-success"><p>' . esc_html__( 'تم الحفظ بنجاح.', 'stationary-theme' ) . '</p></div>';
				$content = stationary_get_option( $slug . '_content', $def );
			}
		}
	}

	wp_enqueue_editor();
	wp_enqueue_media();
	?>
	<form method="post">
		<?php wp_nonce_field( 'stationary_policy_' . $slug, 'stationary_policy_nonce' ); ?>
		<table class="form-table">
			<tr>
				<th><label for="policy_content"><?php echo esc_html( $label ); ?></label></th>
				<td>
					<?php
					wp_editor( $content, 'policy_content', array(
						'textarea_name' => 'policy_content',
						'textarea_rows' => 14,
						'media_buttons' => true,
						'teeny'         => false,
						'quicktags'     => true,
						'tinymce'       => true,
					) );
					?>
				</td>
			</tr>
		</table>
		<p>
			<button type="submit" name="stationary_policy_save" class="button button-primary"><?php esc_html_e( 'حفظ', 'stationary-theme' ); ?></button>
			<button type="submit" name="stationary_restore_policy" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى الأصلي؟', 'stationary-theme' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'stationary-theme' ); ?></button>
		</p>
	</form>
	<?php
}

function stationary_render_shipping_policy() {
	stationary_render_policy_page( 'shipping-policy', __( 'محتوى سياسة الشحن', 'stationary-theme' ) );
}

function stationary_render_return_policy() {
	stationary_render_policy_page( 'return-policy', __( 'محتوى سياسة الاسترجاع', 'stationary-theme' ) );
}

function stationary_render_privacy_policy() {
	stationary_render_policy_page( 'privacy-policy', __( 'محتوى سياسة الخصوصية', 'stationary-theme' ) );
}
