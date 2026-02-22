<?php
/**
 * Beauty Care Theme — إعدادات تواصل معنا.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beauty_care_default_contact_settings() {
	return array(
		'mail_type'            => '',
		'recipient_email'       => get_option( 'admin_email' ),
		'google_email'         => '',
		'google_app_password'   => '',
		'smtp_host'            => '',
		'smtp_port'            => '587',
		'smtp_user'            => '',
		'smtp_pass'            => '',
		'smtp_encryption'      => 'tls',
		'smtp_from_email'      => '',
		'contact_address'      => 'الرياض، المملكة العربية السعودية',
		'contact_phone'       => '+966 12 345 6789',
		'contact_email_display'=> 'Beauty@gmail.com',
		'sidebar_image'        => 0,
	);
}

function beauty_care_get_contact_settings() {
	$saved = get_option( 'beauty_care_contact_settings', array() );
	return wp_parse_args( $saved, beauty_care_default_contact_settings() );
}

function beauty_care_register_contact_admin() {
	add_submenu_page(
		'beauty-care-content',
		'تواصل معنا',
		'تواصل معنا',
		'manage_options',
		'beauty-care-contact',
		'beauty_care_render_contact_admin'
	);
}
add_action( 'admin_menu', 'beauty_care_register_contact_admin', 12 );

function beauty_care_contact_admin_enqueue( $hook ) {
	if ( 'beauty-care-content_page_beauty-care-contact' !== $hook ) {
		return;
	}
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'beauty_care_contact_admin_enqueue' );

function beauty_care_render_contact_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = beauty_care_get_contact_settings();
	$sidebar_url = '';
	if ( $s['sidebar_image'] && wp_attachment_is_image( $s['sidebar_image'] ) ) {
		$sidebar_url = wp_get_attachment_image_url( $s['sidebar_image'], 'medium' );
	} elseif ( file_exists( get_template_directory() . '/beauty-care/assets/way3.jpg' ) ) {
		$sidebar_url = get_template_directory_uri() . '/beauty-care/assets/way3.jpg';
	}
	?>
	<div class="wrap">
		<h1>إعدادات تواصل معنا</h1>
		<?php if ( isset( $_GET['beauty_care_contact_saved'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم الحفظ بنجاح.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['beauty_care_test_sent'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم إرسال رسالة الاختبار بنجاح.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['beauty_care_test_error'] ) ) : ?>
			<div class="notice notice-error is-dismissible"><p><?php echo esc_html( isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : 'فشل إرسال رسالة الاختبار.' ); ?></p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['beauty_care_contact_restored'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم استعادة المحتوى الأصلي.</p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'beauty_care_save_contact', 'beauty_care_contact_nonce' ); ?>
			<input type="hidden" name="action" value="beauty_care_save_contact" />

			<h2>إعداد البريد الإلكتروني</h2>
			<table class="form-table">
				<tr>
					<th><label for="recipient_email">البريد المستلم</label></th>
					<td>
						<input type="email" name="recipient_email" id="recipient_email" class="regular-text" value="<?php echo esc_attr( $s['recipient_email'] ); ?>" required />
					</td>
				</tr>
				<tr>
					<th><label for="mail_type">نوع البريد</label></th>
					<td>
						<select name="mail_type" id="mail_type">
							<option value="" <?php selected( $s['mail_type'], '' ); ?>>— اختر —</option>
							<option value="google" <?php selected( $s['mail_type'], 'google' ); ?>>إيميل جوجل (Gmail)</option>
							<option value="smtp" <?php selected( $s['mail_type'], 'smtp' ); ?>>إيميل احترافي (SMTP)</option>
						</select>
					</td>
				</tr>
			</table>
			<div id="beauty-care-google-mail" class="beauty-care-mail-block" style="<?php echo 'google' !== $s['mail_type'] ? 'display:none;' : ''; ?>">
				<h3>إعدادات إيميل جوجل</h3>
				<table class="form-table">
					<tr>
						<th><label for="google_email">إيميل جوجل</label></th>
						<td><input type="email" name="google_email" id="google_email" class="regular-text" value="<?php echo esc_attr( $s['google_email'] ); ?>" /></td>
					</tr>
					<tr>
						<th><label for="google_app_password">App Password</label></th>
						<td><input type="password" name="google_app_password" id="google_app_password" class="regular-text" value="<?php echo esc_attr( $s['google_app_password'] ); ?>" autocomplete="new-password" /></td>
					</tr>
				</table>
			</div>
			<div id="beauty-care-smtp-mail" class="beauty-care-mail-block" style="<?php echo 'smtp' !== $s['mail_type'] ? 'display:none;' : ''; ?>">
				<h3>إعدادات SMTP</h3>
				<table class="form-table">
					<tr>
						<th><label for="smtp_host">خادم SMTP</label></th>
						<td><input type="text" name="smtp_host" id="smtp_host" class="regular-text" value="<?php echo esc_attr( $s['smtp_host'] ); ?>" /></td>
					</tr>
					<tr>
						<th><label for="smtp_port">المنفذ</label></th>
						<td><input type="number" name="smtp_port" id="smtp_port" value="<?php echo esc_attr( $s['smtp_port'] ); ?>" style="width:80px;" /></td>
					</tr>
					<tr>
						<th><label for="smtp_user">اسم المستخدم</label></th>
						<td><input type="text" name="smtp_user" id="smtp_user" class="regular-text" value="<?php echo esc_attr( $s['smtp_user'] ); ?>" /></td>
					</tr>
					<tr>
						<th><label for="smtp_pass">كلمة المرور</label></th>
						<td><input type="password" name="smtp_pass" id="smtp_pass" class="regular-text" value="<?php echo esc_attr( $s['smtp_pass'] ); ?>" autocomplete="new-password" /></td>
					</tr>
					<tr>
						<th><label for="smtp_encryption">التشفير</label></th>
						<td>
							<select name="smtp_encryption" id="smtp_encryption">
								<option value="" <?php selected( $s['smtp_encryption'], '' ); ?>>لا شيء</option>
								<option value="tls" <?php selected( $s['smtp_encryption'], 'tls' ); ?>>TLS</option>
								<option value="ssl" <?php selected( $s['smtp_encryption'], 'ssl' ); ?>>SSL</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="smtp_from_email">البريد المرسل (From)</label></th>
						<td><input type="email" name="smtp_from_email" id="smtp_from_email" class="regular-text" value="<?php echo esc_attr( $s['smtp_from_email'] ); ?>" /></td>
					</tr>
				</table>
			</div>

			<h2>معلومات العرض (في صفحة التواصل)</h2>
			<table class="form-table">
				<tr>
					<th><label for="contact_address">العنوان</label></th>
					<td><input type="text" name="contact_address" id="contact_address" class="large-text" value="<?php echo esc_attr( $s['contact_address'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="contact_phone">الهاتف</label></th>
					<td><input type="text" name="contact_phone" id="contact_phone" class="regular-text" value="<?php echo esc_attr( $s['contact_phone'] ); ?>" dir="ltr" /></td>
				</tr>
				<tr>
					<th><label for="contact_email_display">البريد المعروض</label></th>
					<td><input type="email" name="contact_email_display" id="contact_email_display" class="regular-text" value="<?php echo esc_attr( $s['contact_email_display'] ); ?>" /></td>
				</tr>
				<tr>
					<th>صورة القسم الجانبي</th>
					<td>
						<div class="beauty-care-image-upload" data-name="sidebar_image">
							<input type="hidden" name="sidebar_image" value="<?php echo esc_attr( (int) $s['sidebar_image'] ); ?>" />
							<div class="image-preview" style="max-width:200px;margin-bottom:8px;">
								<?php if ( $sidebar_url ) : ?>
									<img src="<?php echo esc_url( $sidebar_url ); ?>" alt="" style="max-width:100%;height:auto;border:1px solid #ddd;" />
								<?php endif; ?>
							</div>
							<button type="button" class="button upload-image-btn">رفع صورة</button>
							<button type="button" class="button remove-image-btn" <?php echo (int) $s['sidebar_image'] ? '' : 'style="display:none;"'; ?>>إزالة</button>
						</div>
					</td>
				</tr>
			</table>

			<p class="submit">
				<?php submit_button( 'حفظ', 'primary', 'submit', false ); ?>
				<button type="button" id="beauty-care-test-email" class="button">إرسال اختبار</button>
				<button type="submit" name="restore_default" value="1" class="button" onclick="return confirm('استعادة المحتوى الأصلي؟');">استعادة المحتوى الأصلي</button>
			</p>
		</form>
	</div>

	<script>
	jQuery(function($) {
		$('#mail_type').on('change', function() {
			var v = $(this).val();
			$('.beauty-care-mail-block').hide();
			if (v === 'google') $('#beauty-care-google-mail').show();
			if (v === 'smtp') $('#beauty-care-smtp-mail').show();
		});
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
		$('#beauty-care-test-email').on('click', function() {
			var $b = $(this);
			$b.prop('disabled', true).text('جاري الإرسال...');
			$.post(ajaxurl, {
				action: 'beauty_care_test_contact_email',
				nonce: '<?php echo esc_js( wp_create_nonce( 'beauty_care_test_email' ) ); ?>',
				recipient: $('#recipient_email').val(),
				mail_type: $('#mail_type').val(),
				google_email: $('#google_email').val(),
				google_app_password: $('#google_app_password').val(),
				smtp_host: $('#smtp_host').val(),
				smtp_port: $('#smtp_port').val(),
				smtp_user: $('#smtp_user').val(),
				smtp_pass: $('#smtp_pass').val(),
				smtp_encryption: $('#smtp_encryption').val(),
				smtp_from_email: $('#smtp_from_email').val()
			}).done(function(r) {
				if (r.success) alert('تم إرسال رسالة الاختبار بنجاح.');
				else alert(r.data || 'فشل الإرسال.');
			}).fail(function() { alert('حدث خطأ.'); })
			.always(function() { $b.prop('disabled', false).text('إرسال اختبار'); });
		});
	});
	</script>
	<?php
}

function beauty_care_save_contact_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'beauty_care_save_contact', 'beauty_care_contact_nonce' );
	if ( ! empty( $_POST['restore_default'] ) ) {
		delete_option( 'beauty_care_contact_settings' );
		wp_safe_redirect( add_query_arg( 'beauty_care_contact_restored', '1', admin_url( 'admin.php?page=beauty-care-contact' ) ) );
		exit;
	}
	$data = array(
		'mail_type'             => isset( $_POST['mail_type'] ) ? sanitize_key( wp_unslash( $_POST['mail_type'] ) ) : '',
		'recipient_email'       => isset( $_POST['recipient_email'] ) ? sanitize_email( wp_unslash( $_POST['recipient_email'] ) ) : '',
		'google_email'         => isset( $_POST['google_email'] ) ? sanitize_email( wp_unslash( $_POST['google_email'] ) ) : '',
		'google_app_password'   => isset( $_POST['google_app_password'] ) ? sanitize_text_field( wp_unslash( $_POST['google_app_password'] ) ) : '',
		'smtp_host'            => isset( $_POST['smtp_host'] ) ? sanitize_text_field( wp_unslash( $_POST['smtp_host'] ) ) : '',
		'smtp_port'             => isset( $_POST['smtp_port'] ) ? absint( $_POST['smtp_port'] ) : 587,
		'smtp_user'            => isset( $_POST['smtp_user'] ) ? sanitize_text_field( wp_unslash( $_POST['smtp_user'] ) ) : '',
		'smtp_pass'            => isset( $_POST['smtp_pass'] ) ? sanitize_text_field( wp_unslash( $_POST['smtp_pass'] ) ) : '',
		'smtp_encryption'      => isset( $_POST['smtp_encryption'] ) ? sanitize_key( wp_unslash( $_POST['smtp_encryption'] ) ) : 'tls',
		'smtp_from_email'      => isset( $_POST['smtp_from_email'] ) ? sanitize_email( wp_unslash( $_POST['smtp_from_email'] ) ) : '',
		'contact_address'      => isset( $_POST['contact_address'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_address'] ) ) : '',
		'contact_phone'       => isset( $_POST['contact_phone'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_phone'] ) ) : '',
		'contact_email_display'=> isset( $_POST['contact_email_display'] ) ? sanitize_email( wp_unslash( $_POST['contact_email_display'] ) ) : '',
		'sidebar_image'        => isset( $_POST['sidebar_image'] ) ? absint( $_POST['sidebar_image'] ) : 0,
	);
	$ex = get_option( 'beauty_care_contact_settings', array() );
	if ( empty( $data['smtp_pass'] ) && ! empty( $ex['smtp_pass'] ) ) {
		$data['smtp_pass'] = $ex['smtp_pass'];
	}
	if ( empty( $data['google_app_password'] ) && ! empty( $ex['google_app_password'] ) ) {
		$data['google_app_password'] = $ex['google_app_password'];
	}
	update_option( 'beauty_care_contact_settings', $data );
	wp_safe_redirect( add_query_arg( 'beauty_care_contact_saved', '1', admin_url( 'admin.php?page=beauty-care-contact' ) ) );
	exit;
}
add_action( 'admin_post_beauty_care_save_contact', 'beauty_care_save_contact_handler' );

function beauty_care_ajax_test_contact_email() {
	check_ajax_referer( 'beauty_care_test_email', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( 'غير مصرح' );
	}
	$to = isset( $_POST['recipient'] ) ? sanitize_email( wp_unslash( $_POST['recipient'] ) ) : '';
	if ( ! $to || ! is_email( $to ) ) {
		wp_send_json_error( 'البريد المستلم غير صحيح' );
	}
	$mt = isset( $_POST['mail_type'] ) ? sanitize_key( wp_unslash( $_POST['mail_type'] ) ) : '';
	if ( ! $mt ) {
		wp_send_json_error( 'اختر نوع البريد أولاً' );
	}
	$saved = beauty_care_get_contact_settings();
	$cfg = array(
		'mail_type'          => $mt,
		'google_email'       => isset( $_POST['google_email'] ) ? sanitize_email( wp_unslash( $_POST['google_email'] ) ) : ( $saved['google_email'] ?? '' ),
		'google_app_password'=> isset( $_POST['google_app_password'] ) && '' !== $_POST['google_app_password'] ? sanitize_text_field( wp_unslash( $_POST['google_app_password'] ) ) : ( $saved['google_app_password'] ?? '' ),
		'smtp_host'          => isset( $_POST['smtp_host'] ) ? sanitize_text_field( wp_unslash( $_POST['smtp_host'] ) ) : ( $saved['smtp_host'] ?? '' ),
		'smtp_port'          => isset( $_POST['smtp_port'] ) ? absint( $_POST['smtp_port'] ) : ( $saved['smtp_port'] ?? 587 ),
		'smtp_user'          => isset( $_POST['smtp_user'] ) ? sanitize_text_field( wp_unslash( $_POST['smtp_user'] ) ) : ( $saved['smtp_user'] ?? '' ),
		'smtp_pass'          => ( isset( $_POST['smtp_pass'] ) && '' !== $_POST['smtp_pass'] ) ? wp_unslash( $_POST['smtp_pass'] ) : ( $saved['smtp_pass'] ?? '' ),
		'smtp_encryption'    => isset( $_POST['smtp_encryption'] ) ? sanitize_key( wp_unslash( $_POST['smtp_encryption'] ) ) : ( $saved['smtp_encryption'] ?? 'tls' ),
		'smtp_from_email'     => isset( $_POST['smtp_from_email'] ) ? sanitize_email( wp_unslash( $_POST['smtp_from_email'] ) ) : ( $saved['smtp_from_email'] ?? '' ),
	);
	set_transient( 'beauty_care_contact_test_cfg', $cfg, 60 );
	$sent = beauty_care_send_mail_with_config( $to, 'رسالة اختبار - تواصل معنا', 'هذه رسالة اختبار من إعدادات تواصل معنا.', $cfg );
	delete_transient( 'beauty_care_contact_test_cfg' );
	$sent ? wp_send_json_success() : wp_send_json_error( 'فشل إرسال البريد' );
}
add_action( 'wp_ajax_beauty_care_test_contact_email', 'beauty_care_ajax_test_contact_email' );

function beauty_care_send_mail_with_config( $to, $subject, $body, $settings = null ) {
	if ( ! $settings ) {
		$settings = beauty_care_get_contact_settings();
	}
	$mt = $settings['mail_type'] ?? '';
	if ( ! $mt ) {
		return wp_mail( $to, $subject, $body, array( 'Content-Type: text/plain; charset=UTF-8' ) );
	}
	set_transient( 'beauty_care_using_contact_mail', 1, 30 );
	add_filter( 'phpmailer_init', 'beauty_care_phpmailer_contact_config', 10, 1 );
	$result = wp_mail( $to, $subject, $body, array( 'Content-Type: text/plain; charset=UTF-8' ) );
	remove_filter( 'phpmailer_init', 'beauty_care_phpmailer_contact_config', 10 );
	delete_transient( 'beauty_care_using_contact_mail' );
	return $result;
}

function beauty_care_phpmailer_contact_config( $phpmailer ) {
	if ( ! get_transient( 'beauty_care_using_contact_mail' ) ) {
		return;
	}
	$s = get_transient( 'beauty_care_contact_test_cfg' );
	if ( ! $s ) {
		$s = beauty_care_get_contact_settings();
	}
	$mt = $s['mail_type'] ?? '';
	if ( 'google' === $mt && ! empty( $s['google_email'] ) && ! empty( $s['google_app_password'] ) ) {
		$phpmailer->isSMTP();
		$phpmailer->Host       = 'smtp.gmail.com';
		$phpmailer->Port       = 587;
		$phpmailer->SMTPAuth   = true;
		$phpmailer->SMTPSecure = 'tls';
		$phpmailer->Username   = $s['google_email'];
		$phpmailer->Password   = $s['google_app_password'];
		$phpmailer->SetFrom( $s['google_email'], get_bloginfo( 'name' ) );
		return;
	}
	if ( 'smtp' === $mt && ! empty( $s['smtp_host'] ) ) {
		$phpmailer->isSMTP();
		$phpmailer->Host       = $s['smtp_host'];
		$phpmailer->Port       = ! empty( $s['smtp_port'] ) ? (int) $s['smtp_port'] : 587;
		$phpmailer->SMTPAuth   = ! empty( $s['smtp_user'] );
		$phpmailer->Username   = $s['smtp_user'] ?? '';
		$phpmailer->Password   = $s['smtp_pass'] ?? '';
		$from = ! empty( $s['smtp_from_email'] ) ? $s['smtp_from_email'] : ( ! empty( $s['smtp_user'] ) && is_email( $s['smtp_user'] ) ? $s['smtp_user'] : get_option( 'admin_email' ) );
		$phpmailer->SetFrom( $from, get_bloginfo( 'name' ) );
		if ( 'ssl' === ( $s['smtp_encryption'] ?? '' ) ) {
			$phpmailer->SMTPSecure = 'ssl';
		} elseif ( 'tls' === ( $s['smtp_encryption'] ?? '' ) ) {
			$phpmailer->SMTPSecure = 'tls';
		} else {
			$phpmailer->SMTPSecure = false;
			$phpmailer->SMTPAutoTLS = false;
		}
	}
}
