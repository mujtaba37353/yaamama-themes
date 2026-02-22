<?php
/**
 * Sweet House Theme — إعدادات الفوتر.
 * شعار الموقع (الفوتر والمنيو)، معلومات الفوتر، أيقونات الواتساب والاتصال العائمة.
 *
 * @package Sweet_House_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * الإعدادات الافتراضية للفوتر.
 */
function sweet_house_default_footer_settings() {
	return array(
		'site_logo'        => 0,
		'footer_address'   => 'الرياض - المملكة العربية السعودية',
		'footer_phones'    => '059688929 - 058493948',
		'footer_email'     => 'info@super.ksa.com',
		'whatsapp_number'  => '',
		'phone_number'     => '',
	);
}

/**
 * الحصول على إعدادات الفوتر.
 */
function sweet_house_get_footer_settings() {
	$saved = get_option( 'sweet_house_footer_settings', array() );
	return wp_parse_args( $saved, sweet_house_default_footer_settings() );
}

/**
 * الحصول على URL شعار الموقع (للفوتر والمنيو).
 */
function sweet_house_site_logo_url() {
	$s = sweet_house_get_footer_settings();
	$id = isset( $s['site_logo'] ) ? (int) $s['site_logo'] : 0;
	if ( $id && wp_attachment_is_image( $id ) ) {
		$url = wp_get_attachment_image_url( $id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( 'assets/logo.png' ) : '';
}

/**
 * الحصول على URL شعار الفوتر (نفس شعار الموقع للمنيو والفوتر).
 */
function sweet_house_footer_logo_url() {
	return sweet_house_site_logo_url();
}

/**
 * تسجيل القائمة.
 */
function sweet_house_register_footer_admin() {
	add_submenu_page(
		'sweet-house-content',
		'الفوتر',
		'الفوتر',
		'manage_options',
		'sweet-house-footer',
		'sweet_house_render_footer_admin'
	);
}
add_action( 'admin_menu', 'sweet_house_register_footer_admin', 13 );

/**
 * تحميل وسائط رفع الصور.
 */
function sweet_house_footer_admin_enqueue( $hook ) {
	$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
	$on_footer = ( 'sweet-house-footer' === $page );
	$hook_match = ( 'sweet-house-content_page_sweet-house-footer' === $hook );
	if ( $on_footer || $hook_match ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'sweet_house_footer_admin_enqueue' );

/**
 * عرض صفحة إعدادات الفوتر.
 */
function sweet_house_render_footer_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = sweet_house_get_footer_settings();
	$logo_url = sweet_house_site_logo_url();
	if ( isset( $_GET['sweet_house_footer_saved'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم الحفظ بنجاح.', 'sweet-house-theme' ) . '</p></div>';
	}
	if ( isset( $_GET['sweet_house_footer_restored'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم استعادة الإعدادات الأصلية.', 'sweet-house-theme' ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'إعدادات الفوتر', 'sweet-house-theme' ); ?></h1>
		<p class="description"><?php esc_html_e( 'شعار الموقع يظهر في الفوتر والمنيو الرئيسي. وأيقونات الواتساب والاتصال تظهر كأيقونات عائمة في زاوية الصفحة.', 'sweet-house-theme' ); ?></p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'sweet_house_save_footer', 'sweet_house_footer_nonce' ); ?>
			<input type="hidden" name="action" value="sweet_house_save_footer" />

			<h2><?php esc_html_e( 'شعار الموقع', 'sweet-house-theme' ); ?></h2>
			<table class="form-table">
				<tr>
					<th><label><?php esc_html_e( 'الشعار', 'sweet-house-theme' ); ?></label></th>
					<td>
						<div class="sweet-house-image-upload" data-name="site_logo">
							<input type="hidden" name="site_logo" value="<?php echo esc_attr( (int) $s['site_logo'] ); ?>" />
							<div class="image-preview" style="max-width:200px;margin-bottom:8px;">
								<?php if ( $logo_url ) : ?>
									<img src="<?php echo esc_url( $logo_url ); ?>" alt="" style="max-width:100%;height:auto;border:1px solid #ddd;" />
								<?php endif; ?>
							</div>
							<button type="button" class="button upload-image-btn"><?php esc_html_e( 'رفع شعار', 'sweet-house-theme' ); ?></button>
							<button type="button" class="button remove-image-btn" <?php echo (int) $s['site_logo'] ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'إزالة', 'sweet-house-theme' ); ?></button>
							<p class="description"><?php esc_html_e( 'يظهر في الفوتر والمنيو الرئيسي.', 'sweet-house-theme' ); ?></p>
						</div>
					</td>
				</tr>
			</table>

			<h2><?php esc_html_e( 'معلومات الفوتر', 'sweet-house-theme' ); ?></h2>
			<table class="form-table">
				<tr>
					<th><label for="footer_address"><?php esc_html_e( 'العنوان', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="footer_address" id="footer_address" class="large-text" value="<?php echo esc_attr( $s['footer_address'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="footer_phones"><?php esc_html_e( 'أرقام الجوال', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="footer_phones" id="footer_phones" class="large-text" value="<?php echo esc_attr( $s['footer_phones'] ); ?>" placeholder="05xxxxxxxx - 05xxxxxxxx" /></td>
				</tr>
				<tr>
					<th><label for="footer_email"><?php esc_html_e( 'البريد الإلكتروني', 'sweet-house-theme' ); ?></label></th>
					<td><input type="email" name="footer_email" id="footer_email" class="regular-text" value="<?php echo esc_attr( $s['footer_email'] ); ?>" /></td>
				</tr>
			</table>

			<h2><?php esc_html_e( 'الأيقونات العائمة', 'sweet-house-theme' ); ?></h2>
			<table class="form-table">
				<tr>
					<th><label for="whatsapp_number"><?php esc_html_e( 'رقم الواتساب', 'sweet-house-theme' ); ?></label></th>
					<td>
						<input type="text" name="whatsapp_number" id="whatsapp_number" class="regular-text" value="<?php echo esc_attr( $s['whatsapp_number'] ); ?>" placeholder="9665xxxxxxxx" dir="ltr" />
						<p class="description"><?php esc_html_e( 'رقم كامل مع مفتاح الدولة (مثال: 966512345678). عند التعبئة تظهر أيقونة واتساب عائمة.', 'sweet-house-theme' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="phone_number"><?php esc_html_e( 'رقم الاتصال', 'sweet-house-theme' ); ?></label></th>
					<td>
						<input type="text" name="phone_number" id="phone_number" class="regular-text" value="<?php echo esc_attr( $s['phone_number'] ); ?>" placeholder="05xxxxxxxx" dir="ltr" />
						<p class="description"><?php esc_html_e( 'رقم للاتصال المباشر. عند التعبئة تظهر أيقونة اتصال عائمة.', 'sweet-house-theme' ); ?></p>
					</td>
				</tr>
			</table>

			<p class="submit">
				<?php submit_button( __( 'حفظ', 'sweet-house-theme' ), 'primary', 'submit', false ); ?>
				&nbsp;
				<button type="submit" name="restore_default" value="1" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى الأصلي؟', 'sweet-house-theme' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'sweet-house-theme' ); ?></button>
			</p>
		</form>
	</div>

	<script>
	jQuery(function($) {
		$('.sweet-house-image-upload').each(function() {
			var $wrap = $(this);
			var $input = $wrap.find('input[type="hidden"]');
			var $preview = $wrap.find('.image-preview');
			var $upload = $wrap.find('.upload-image-btn');
			var $remove = $wrap.find('.remove-image-btn');

			$upload.on('click', function() {
				var frame = wp.media({ library: { type: 'image' }, multiple: false });
				frame.on('select', function() {
					var att = frame.state().get('selection').first().toJSON();
					$input.val(att.id);
					$preview.html('<img src="' + att.url + '" alt="" style="max-width:100%;height:auto;border:1px solid #ddd;" />');
					$remove.show();
				});
				frame.open();
			});

			$remove.on('click', function() {
				$input.val(0);
				$preview.empty();
				$remove.hide();
			});
		});
	});
	</script>
	<?php
}

/**
 * معالج حفظ إعدادات الفوتر.
 */
function sweet_house_save_footer_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'sweet_house_save_footer', 'sweet_house_footer_nonce' );

	if ( ! empty( $_POST['restore_default'] ) ) {
		delete_option( 'sweet_house_footer_settings' );
		wp_safe_redirect( add_query_arg( 'sweet_house_footer_restored', '1', admin_url( 'admin.php?page=sweet-house-footer' ) ) );
		exit;
	}

	$data = array(
		'site_logo'      => isset( $_POST['site_logo'] ) ? absint( $_POST['site_logo'] ) : 0,
		'footer_address' => isset( $_POST['footer_address'] ) ? sanitize_text_field( wp_unslash( $_POST['footer_address'] ) ) : '',
		'footer_phones'  => isset( $_POST['footer_phones'] ) ? sanitize_text_field( wp_unslash( $_POST['footer_phones'] ) ) : '',
		'footer_email'   => isset( $_POST['footer_email'] ) ? sanitize_email( wp_unslash( $_POST['footer_email'] ) ) : '',
		'whatsapp_number' => isset( $_POST['whatsapp_number'] ) ? sanitize_text_field( wp_unslash( $_POST['whatsapp_number'] ) ) : '',
		'phone_number'   => isset( $_POST['phone_number'] ) ? sanitize_text_field( wp_unslash( $_POST['phone_number'] ) ) : '',
	);
	update_option( 'sweet_house_footer_settings', $data );
	wp_safe_redirect( add_query_arg( 'sweet_house_footer_saved', '1', admin_url( 'admin.php?page=sweet-house-footer' ) ) );
	exit;
}
add_action( 'admin_post_sweet_house_save_footer', 'sweet_house_save_footer_handler' );
