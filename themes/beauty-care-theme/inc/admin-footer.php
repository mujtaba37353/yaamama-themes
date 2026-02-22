<?php
/**
 * Beauty Care Theme — إعدادات الفوتر.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beauty_care_default_footer_settings() {
	return array(
		'footer_text'     => 'نحن شركة متخصصة في تقديم أفضل أدوات ومنتجات التجميل للعناية بالبشرة والشعر، نحرص على الجمع بين الجودة العالية والمكونات الآمنة لتمنحكِ تجربة عناية فريدة ونتائج ملموسة.',
		'site_logo'       => 0,
		'whatsapp_number' => '',
		'phone_number'    => '',
		'copyright_text'  => '',
	);
}

function beauty_care_get_footer_settings() {
	$saved = get_option( 'beauty_care_footer_settings', array() );
	return wp_parse_args( $saved, beauty_care_default_footer_settings() );
}

function beauty_care_footer_logo_url() {
	$s = beauty_care_get_footer_settings();
	$id = (int) ( $s['site_logo'] ?? 0 );
	if ( $id && wp_attachment_is_image( $id ) ) {
		$url = wp_get_attachment_image_url( $id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return get_template_directory_uri() . '/beauty-care/assets/footer-icon.png';
}

function beauty_care_register_footer_admin() {
	add_submenu_page(
		'beauty-care-content',
		'الفوتر',
		'الفوتر',
		'manage_options',
		'beauty-care-footer',
		'beauty_care_render_footer_admin'
	);
}
add_action( 'admin_menu', 'beauty_care_register_footer_admin', 13 );

function beauty_care_footer_admin_enqueue( $hook ) {
	if ( strpos( $hook, 'beauty-care-footer' ) !== false ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'beauty_care_footer_admin_enqueue' );

function beauty_care_render_footer_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = beauty_care_get_footer_settings();
	$logo_url = beauty_care_footer_logo_url();
	?>
	<div class="wrap">
		<h1>إعدادات الفوتر</h1>
		<?php if ( isset( $_GET['beauty_care_footer_saved'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم الحفظ بنجاح.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['beauty_care_footer_restored'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم استعادة المحتوى الأصلي.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'beauty_care_save_footer', 'beauty_care_footer_nonce' ); ?>
			<input type="hidden" name="action" value="beauty_care_save_footer" />
			<h2>الفقرة الرئيسية</h2>
			<table class="form-table">
				<tr>
					<th><label for="footer_text">النص</label></th>
					<td><textarea name="footer_text" id="footer_text" rows="4" class="large-text"><?php echo esc_textarea( $s['footer_text'] ); ?></textarea></td>
				</tr>
			</table>
			<h2>شعار الموقع</h2>
			<table class="form-table">
				<tr>
					<th>الشعار</th>
					<td>
						<div class="beauty-care-image-upload" data-name="site_logo">
							<input type="hidden" name="site_logo" value="<?php echo esc_attr( (int) $s['site_logo'] ); ?>" />
							<div class="image-preview" style="max-width:200px;margin-bottom:8px;">
								<?php if ( $logo_url ) : ?>
									<img src="<?php echo esc_url( $logo_url ); ?>" alt="" style="max-width:100%;height:auto;border:1px solid #ddd;" />
								<?php endif; ?>
							</div>
							<button type="button" class="button upload-image-btn">رفع شعار</button>
							<button type="button" class="button remove-image-btn" <?php echo (int) $s['site_logo'] ? '' : 'style="display:none;"'; ?>>إزالة</button>
						</div>
					</td>
				</tr>
			</table>
			<h2>أزرار واتساب واتصال العائمة</h2>
			<table class="form-table">
				<tr>
					<th><label for="whatsapp_number">رقم الواتساب</label></th>
					<td>
						<input type="text" name="whatsapp_number" id="whatsapp_number" class="regular-text" value="<?php echo esc_attr( $s['whatsapp_number'] ); ?>" placeholder="966512345678" dir="ltr" />
						<p class="description">رقم كامل مع مفتاح الدولة (مثال: 966512345678)</p>
					</td>
				</tr>
				<tr>
					<th><label for="phone_number">رقم الاتصال</label></th>
					<td>
						<input type="text" name="phone_number" id="phone_number" class="regular-text" value="<?php echo esc_attr( $s['phone_number'] ); ?>" placeholder="0512345678" dir="ltr" />
					</td>
				</tr>
			</table>
			<h2>حقوق النشر</h2>
			<table class="form-table">
				<tr>
					<th><label for="copyright_text">نص حقوق النشر</label></th>
					<td>
						<input type="text" name="copyright_text" id="copyright_text" class="large-text" value="<?php echo esc_attr( $s['copyright_text'] ); ?>" placeholder="جميع الحقوق محفوظة ©" />
						<p class="description">اتركه فارغاً لاستخدام: جميع الحقوق محفوظة © السنة Yamamah Solutions</p>
					</td>
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
		$('.beauty-care-image-upload').each(function() {
			var $w = $(this), $in = $w.find('input[type="hidden"]'), $pv = $w.find('.image-preview');
			$w.find('.upload-image-btn').on('click', function() {
				var f = wp.media({ library: { type: 'image' }, multiple: false });
				f.on('select', function() {
					var att = f.state().get('selection').first().toJSON();
					$in.val(att.id);
					$pv.html('<img src="' + att.url + '" alt="" style="max-width:100%;height:auto;border:1px solid #ddd;" />');
					$w.find('.remove-image-btn').show();
				});
				f.open();
			});
			$w.find('.remove-image-btn').on('click', function() {
				$in.val(0);
				$pv.empty();
				$(this).hide();
			});
		});
	});
	</script>
	<?php
}

function beauty_care_save_footer_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'beauty_care_save_footer', 'beauty_care_footer_nonce' );
	if ( ! empty( $_POST['restore_default'] ) ) {
		delete_option( 'beauty_care_footer_settings' );
		wp_safe_redirect( add_query_arg( 'beauty_care_footer_restored', '1', admin_url( 'admin.php?page=beauty-care-footer' ) ) );
		exit;
	}
	$data = array(
		'footer_text'      => isset( $_POST['footer_text'] ) ? sanitize_textarea_field( wp_unslash( $_POST['footer_text'] ) ) : '',
		'site_logo'        => isset( $_POST['site_logo'] ) ? absint( $_POST['site_logo'] ) : 0,
		'whatsapp_number'  => isset( $_POST['whatsapp_number'] ) ? sanitize_text_field( wp_unslash( $_POST['whatsapp_number'] ) ) : '',
		'phone_number'     => isset( $_POST['phone_number'] ) ? sanitize_text_field( wp_unslash( $_POST['phone_number'] ) ) : '',
		'copyright_text'   => isset( $_POST['copyright_text'] ) ? sanitize_text_field( wp_unslash( $_POST['copyright_text'] ) ) : '',
	);
	update_option( 'beauty_care_footer_settings', $data );
	wp_safe_redirect( add_query_arg( 'beauty_care_footer_saved', '1', admin_url( 'admin.php?page=beauty-care-footer' ) ) );
	exit;
}
add_action( 'admin_post_beauty_care_save_footer', 'beauty_care_save_footer_handler' );

/**
 * إضافة أنماط أزرار الواتساب والاتصال العائمة.
 */
function beauty_care_floating_buttons_css() {
	$footer = beauty_care_get_footer_settings();
	if ( empty( $footer['whatsapp_number'] ) && empty( $footer['phone_number'] ) ) {
		return;
	}
	$css = '.beauty-care-float-btn{position:fixed;bottom:1.5rem;z-index:9999;width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;text-decoration:none;box-shadow:0 4px 12px rgba(0,0,0,0.2);transition:transform 0.2s;}.beauty-care-float-whatsapp{right:1.5rem;background:#25D366;}.beauty-care-float-call{right:1.5rem;bottom:5rem;background:var(--y-color-secondary,#bda069);}.beauty-care-float-btn:hover{color:#fff;transform:scale(1.08);}@media(max-width:820px){.beauty-care-float-btn{width:44px;height:44px;right:1rem;}.beauty-care-float-call{bottom:4.5rem;}}';
	wp_add_inline_style( 'beauty-care-footer', $css );
}
add_action( 'wp_enqueue_scripts', 'beauty_care_floating_buttons_css', 25 );
