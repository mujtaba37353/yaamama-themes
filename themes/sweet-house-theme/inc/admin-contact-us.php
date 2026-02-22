<?php
/**
 * Sweet House Theme — إعدادات تواصل معنا.
 * إعداد البريد (جوجل مع App Password أو SMTP احترافي)، عرض العنوان والأرقام والخريطة.
 *
 * @package Sweet_House_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * الإعدادات الافتراضية لتواصل معنا.
 */
function sweet_house_default_contact_settings() {
	return array(
		'mail_type'           => '',
		'recipient_email'     => get_option( 'admin_email' ),
		'google_email'       => '',
		'google_app_password' => '',
		'smtp_host'           => '',
		'smtp_port'           => '587',
		'smtp_user'           => '',
		'smtp_pass'           => '',
		'smtp_encryption'     => 'tls',
		'smtp_from_email'     => '',
		'page_title'          => 'يسعدنا استقبال رسالتك',
		'contact_info_title'  => 'تواصل معنا',
		'contact_address'     => 'الرياض - المملكة العربية السعودية',
		'contact_phones'      => '059688929 - 058493948',
		'contact_email_display' => '',
		'map_link'            => '',
		'map_embed_url'       => '',
		'map_lat'             => '',
		'map_lng'             => '',
		'visit_title'         => 'زورونا في معرض دارك',
		'visit_hours'         => 'أوقات الدوام من 9:30 ص حتى 10:30 م',
	);
}

/**
 * تحويل رابط المشاركة من خرائط جوجل إلى رابط تضمين للـ iframe.
 *
 * @param string $url رابط المشاركة (maps.app.goo.gl أو google.com/maps).
 * @return string رابط التضمين أو سلسلة فارغة إن لم يُستخرج.
 */
function sweet_house_map_link_to_embed_url( $url ) {
	$url = trim( $url );
	if ( empty( $url ) || ! wp_http_validate_url( $url ) ) {
		return '';
	}
	// تحميل من الكاش لتجنب طلبات HTTP المتكررة.
	$cache_key = 'sh_map_embed_' . md5( $url );
	$cached    = get_transient( $cache_key );
	if ( false !== $cached ) {
		return is_string( $cached ) ? $cached : '';
	}
	$result = '';
	// إن كان الرابط للتضمين مسبقاً، نعيده كما هو.
	if ( strpos( $url, '/embed' ) !== false || strpos( $url, 'output=embed' ) !== false ) {
		set_transient( $cache_key, $url, DAY_IN_SECONDS );
		return $url;
	}
	// تتبع إعادة التوجيه للحصول على الرابط النهائي (لروابط goo.gl).
	if ( preg_match( '#maps\.app\.goo\.gl|goo\.gl/maps#i', $url ) ) {
		$current = $url;
		for ( $i = 0; $i < 5; $i++ ) {
			$resp = wp_remote_head( $current, array( 'redirection' => 0, 'timeout' => 10 ) );
			if ( is_wp_error( $resp ) ) {
				break;
			}
			$code = wp_remote_retrieve_response_code( $resp );
			$loc  = wp_remote_retrieve_header( $resp, 'location' );
			if ( in_array( (int) $code, array( 301, 302, 303, 307, 308 ), true ) && $loc ) {
				$current = ( strpos( $loc, 'http' ) === 0 ) ? $loc : 'https://www.google.com' . $loc;
			} else {
				break;
			}
		}
		$url = $current;
	}
	// استخراج الإحداثيات من نمط /@lat,lng أو /@lat,lng,zoom
	if ( preg_match( '#/@(-?\d+\.?\d*),(-?\d+\.?\d*)(?:,(\d+)z?)?#', $url, $m ) ) {
		$lat  = floatval( $m[1] );
		$lng  = floatval( $m[2] );
		$zoom = isset( $m[3] ) && $m[3] !== '' ? absint( $m[3] ) : 15;
		$result = 'https://www.google.com/maps?q=' . $lat . ',' . $lng . '&z=' . $zoom . '&output=embed';
	} elseif ( preg_match( '#[?&]q=(-?\d+\.?\d*),(-?\d+\.?\d*)#', $url, $m ) ) {
		$result = 'https://www.google.com/maps?q=' . $m[1] . ',' . $m[2] . '&output=embed';
	}
	if ( ! empty( $result ) ) {
		set_transient( $cache_key, $result, DAY_IN_SECONDS );
	}
	return $result;
}

/**
 * الحصول على إعدادات تواصل معنا.
 */
function sweet_house_get_contact_settings() {
	$saved = get_option( 'sweet_house_contact_settings', array() );
	return wp_parse_args( $saved, sweet_house_default_contact_settings() );
}

/**
 * تسجيل القائمة.
 */
function sweet_house_register_contact_admin() {
	add_submenu_page(
		'sweet-house-content',
		'تواصل معنا',
		'تواصل معنا',
		'manage_options',
		'sweet-house-contact',
		'sweet_house_render_contact_admin'
	);
}
add_action( 'admin_menu', 'sweet_house_register_contact_admin', 12 );

/**
 * تحميل وسائط الصور إن لزم.
 */
function sweet_house_contact_admin_enqueue( $hook ) {
	if ( 'sweet-house-content_page_sweet-house-contact' !== $hook ) {
		return;
	}
	wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'sweet_house_contact_admin_enqueue' );

/**
 * عرض صفحة إعدادات تواصل معنا.
 */
function sweet_house_render_contact_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = sweet_house_get_contact_settings();

	if ( isset( $_GET['sweet_house_contact_saved'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم الحفظ بنجاح.', 'sweet-house-theme' ) . '</p></div>';
	}
	if ( isset( $_GET['sweet_house_contact_restored'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم استعادة الإعدادات الأصلية.', 'sweet-house-theme' ) . '</p></div>';
	}
	if ( isset( $_GET['sweet_house_test_sent'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم إرسال رسالة الاختبار بنجاح.', 'sweet-house-theme' ) . '</p></div>';
	}
	if ( isset( $_GET['sweet_house_test_error'] ) ) {
		$msg = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : __( 'فشل إرسال رسالة الاختبار.', 'sweet-house-theme' );
		echo '<div class="notice notice-error is-dismissible"><p>' . esc_html( $msg ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'إعدادات تواصل معنا', 'sweet-house-theme' ); ?></h1>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'sweet_house_save_contact', 'sweet_house_contact_nonce' ); ?>
			<input type="hidden" name="action" value="sweet_house_save_contact" />

			<h2><?php esc_html_e( 'إعداد البريد الإلكتروني للفورم', 'sweet-house-theme' ); ?></h2>
			<p class="description"><?php esc_html_e( 'البريد الذي يستقبل رسائل فورم تواصل معنا. اختر إما إيميل جوجل (مع App Password) أو إيميل احترافي (مع إعدادات SMTP).', 'sweet-house-theme' ); ?></p>

			<table class="form-table">
				<tr>
					<th><label for="recipient_email"><?php esc_html_e( 'البريد المستلم', 'sweet-house-theme' ); ?></label></th>
					<td>
						<input type="email" name="recipient_email" id="recipient_email" class="regular-text" value="<?php echo esc_attr( $s['recipient_email'] ); ?>" required />
						<p class="description"><?php esc_html_e( 'البريد الذي تصل إليه رسائل الفورم ورسالة الاختبار.', 'sweet-house-theme' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="mail_type"><?php esc_html_e( 'نوع البريد', 'sweet-house-theme' ); ?></label></th>
					<td>
						<select name="mail_type" id="mail_type">
							<option value="" <?php selected( $s['mail_type'], '' ); ?>><?php esc_html_e( '— اختر —', 'sweet-house-theme' ); ?></option>
							<option value="google" <?php selected( $s['mail_type'], 'google' ); ?>><?php esc_html_e( 'إيميل جوجل (Gmail)', 'sweet-house-theme' ); ?></option>
							<option value="smtp" <?php selected( $s['mail_type'], 'smtp' ); ?>><?php esc_html_e( 'إيميل احترافي (SMTP)', 'sweet-house-theme' ); ?></option>
						</select>
					</td>
				</tr>
			</table>

			<div id="sweet-house-google-mail" class="sweet-house-mail-block" style="<?php echo 'google' !== $s['mail_type'] ? 'display:none;' : ''; ?>">
				<h3><?php esc_html_e( 'إعدادات إيميل جوجل', 'sweet-house-theme' ); ?></h3>
				<p class="description"><?php esc_html_e( 'يجب تفعيل التحقق بخطوتين وإنشاء App Password من إعدادات حساب Google.', 'sweet-house-theme' ); ?></p>
				<table class="form-table">
					<tr>
						<th><label for="google_email"><?php esc_html_e( 'إيميل جوجل', 'sweet-house-theme' ); ?></label></th>
						<td><input type="email" name="google_email" id="google_email" class="regular-text" value="<?php echo esc_attr( $s['google_email'] ); ?>" /></td>
					</tr>
					<tr>
						<th><label for="google_app_password"><?php esc_html_e( 'App Password', 'sweet-house-theme' ); ?></label></th>
						<td>
							<input type="password" name="google_app_password" id="google_app_password" class="regular-text" value="<?php echo esc_attr( $s['google_app_password'] ); ?>" autocomplete="new-password" />
							<p class="description"><?php esc_html_e( 'كلمة المرور الخاصة بالتطبيق (16 حرف من إعدادات Google).', 'sweet-house-theme' ); ?></p>
						</td>
					</tr>
				</table>
			</div>

			<div id="sweet-house-smtp-mail" class="sweet-house-mail-block" style="<?php echo 'smtp' !== $s['mail_type'] ? 'display:none;' : ''; ?>">
				<h3><?php esc_html_e( 'إعدادات SMTP', 'sweet-house-theme' ); ?></h3>
				<table class="form-table">
					<tr>
						<th><label for="smtp_host"><?php esc_html_e( 'خادم SMTP', 'sweet-house-theme' ); ?></label></th>
						<td><input type="text" name="smtp_host" id="smtp_host" class="regular-text" value="<?php echo esc_attr( $s['smtp_host'] ); ?>" placeholder="smtp.example.com" /></td>
					</tr>
					<tr>
						<th><label for="smtp_port"><?php esc_html_e( 'المنفذ', 'sweet-house-theme' ); ?></label></th>
						<td><input type="number" name="smtp_port" id="smtp_port" value="<?php echo esc_attr( $s['smtp_port'] ); ?>" style="width:80px;" /> <span class="description"><?php esc_html_e( '587 عادة لـ TLS، 465 لـ SSL', 'sweet-house-theme' ); ?></span></td>
					</tr>
					<tr>
						<th><label for="smtp_user"><?php esc_html_e( 'اسم المستخدم', 'sweet-house-theme' ); ?></label></th>
						<td><input type="text" name="smtp_user" id="smtp_user" class="regular-text" value="<?php echo esc_attr( $s['smtp_user'] ); ?>" /></td>
					</tr>
					<tr>
						<th><label for="smtp_pass"><?php esc_html_e( 'كلمة المرور', 'sweet-house-theme' ); ?></label></th>
						<td><input type="password" name="smtp_pass" id="smtp_pass" class="regular-text" value="<?php echo esc_attr( $s['smtp_pass'] ); ?>" autocomplete="new-password" /></td>
					</tr>
					<tr>
						<th><label for="smtp_encryption"><?php esc_html_e( 'التشفير', 'sweet-house-theme' ); ?></label></th>
						<td>
							<select name="smtp_encryption" id="smtp_encryption">
								<option value="" <?php selected( $s['smtp_encryption'], '' ); ?>><?php esc_html_e( 'لا شيء', 'sweet-house-theme' ); ?></option>
								<option value="tls" <?php selected( $s['smtp_encryption'], 'tls' ); ?>>TLS</option>
								<option value="ssl" <?php selected( $s['smtp_encryption'], 'ssl' ); ?>>SSL</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="smtp_from_email"><?php esc_html_e( 'البريد المرسل (From)', 'sweet-house-theme' ); ?></label></th>
						<td><input type="email" name="smtp_from_email" id="smtp_from_email" class="regular-text" value="<?php echo esc_attr( $s['smtp_from_email'] ); ?>" /></td>
					</tr>
				</table>
			</div>

			<h2><?php esc_html_e( 'المحتوى المعروض في الصفحة', 'sweet-house-theme' ); ?></h2>
			<table class="form-table">
				<tr>
					<th><label for="page_title"><?php esc_html_e( 'عنوان الصفحة', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="page_title" id="page_title" class="regular-text" value="<?php echo esc_attr( $s['page_title'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="contact_info_title"><?php esc_html_e( 'عنوان قسم التواصل', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="contact_info_title" id="contact_info_title" class="regular-text" value="<?php echo esc_attr( $s['contact_info_title'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="contact_address"><?php esc_html_e( 'العنوان', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="contact_address" id="contact_address" class="large-text" value="<?php echo esc_attr( $s['contact_address'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="contact_phones"><?php esc_html_e( 'أرقام الجوال', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="contact_phones" id="contact_phones" class="large-text" value="<?php echo esc_attr( $s['contact_phones'] ); ?>" placeholder="05xxxxxxxx - 05xxxxxxxx" /></td>
				</tr>
				<tr>
					<th><label for="contact_email_display"><?php esc_html_e( 'البريد المعروض', 'sweet-house-theme' ); ?></label></th>
					<td><input type="email" name="contact_email_display" id="contact_email_display" class="regular-text" value="<?php echo esc_attr( $s['contact_email_display'] ); ?>" /></td>
				</tr>
			</table>

			<h2><?php esc_html_e( 'الخريطة', 'sweet-house-theme' ); ?></h2>
			<table class="form-table">
				<tr>
					<th><label for="map_link"><?php esc_html_e( 'رابط الخريطة من Google Maps', 'sweet-house-theme' ); ?></label></th>
					<td>
						<input type="url" name="map_link" id="map_link" class="large-text" value="<?php echo esc_attr( $s['map_link'] ); ?>" placeholder="https://maps.app.goo.gl/5mAT3eWz3NSS2d3X9" />
						<p class="description"><?php esc_html_e( 'الصق رابط المشاركة من خرائط جوجل (مثل https://maps.app.goo.gl/xxx أو الرابط من زر «شارك»). يتم عرض زر لفتح الموقع على الخريطة.', 'sweet-house-theme' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="map_embed_url"><?php esc_html_e( 'رابط التضمين (اختياري)', 'sweet-house-theme' ); ?></label></th>
					<td>
						<textarea name="map_embed_url" id="map_embed_url" rows="2" class="large-text"><?php echo esc_textarea( $s['map_embed_url'] ); ?></textarea>
						<p class="description"><?php esc_html_e( 'إذا أردت عرض الخريطة داخل الصفحة، الصق رابط الـ iframe من Google Maps (شارك ← تضمين خريطة). إن تُرك فارغاً، سيُستخدم الرابط أعلاه كزر للفتح في تاب جديد.', 'sweet-house-theme' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label><?php esc_html_e( 'الإحداثيات (بديل)', 'sweet-house-theme' ); ?></label></th>
					<td>
						<input type="text" name="map_lat" id="map_lat" value="<?php echo esc_attr( $s['map_lat'] ); ?>" placeholder="24.693191" style="width:120px;" /> <?php esc_html_e( 'خط العرض', 'sweet-house-theme' ); ?>
						&nbsp;
						<input type="text" name="map_lng" id="map_lng" value="<?php echo esc_attr( $s['map_lng'] ); ?>" placeholder="46.696512" style="width:120px;" /> <?php esc_html_e( 'خط الطول', 'sweet-house-theme' ); ?>
						<p class="description"><?php esc_html_e( 'إن ترك رابط التضمين فارغاً، ستُستخدم الإحداثيات لتوليد iframe الخريطة.', 'sweet-house-theme' ); ?></p>
					</td>
				</tr>
				<tr>
					<th><label for="visit_title"><?php esc_html_e( 'عنوان قسم الزيارة', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="visit_title" id="visit_title" class="regular-text" value="<?php echo esc_attr( $s['visit_title'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="visit_hours"><?php esc_html_e( 'أوقات الدوام', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="visit_hours" id="visit_hours" class="regular-text" value="<?php echo esc_attr( $s['visit_hours'] ); ?>" /></td>
				</tr>
			</table>

			<p class="submit">
				<?php submit_button( __( 'حفظ', 'sweet-house-theme' ), 'primary', 'submit', false ); ?>
				&nbsp;
				<button type="submit" name="restore_default" value="1" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة الإعدادات الأصلية؟', 'sweet-house-theme' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'sweet-house-theme' ); ?></button>
				&nbsp;
				<button type="button" id="sweet-house-test-email" class="button"><?php esc_html_e( 'اختبار الإيميل', 'sweet-house-theme' ); ?></button>
			</p>
		</form>
	</div>

	<script>
	jQuery(function($) {
		$('#mail_type').on('change', function() {
			var v = $(this).val();
			$('.sweet-house-mail-block').hide();
			if (v === 'google') $('#sweet-house-google-mail').show();
			if (v === 'smtp') $('#sweet-house-smtp-mail').show();
		});

		$('#sweet-house-test-email').on('click', function() {
			var $btn = $(this);
			$btn.prop('disabled', true).text('<?php echo esc_js( __( 'جاري الإرسال...', 'sweet-house-theme' ) ); ?>');
			$.post(ajaxurl, {
				action: 'sweet_house_test_contact_email',
				nonce: '<?php echo esc_js( wp_create_nonce( 'sweet_house_test_email' ) ); ?>',
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
				if (r.success) {
					alert('<?php echo esc_js( __( 'تم إرسال رسالة الاختبار بنجاح إلى البريد المستلم.', 'sweet-house-theme' ) ); ?>');
				} else {
					alert(r.data || '<?php echo esc_js( __( 'فشل الإرسال.', 'sweet-house-theme' ) ); ?>');
				}
			}).fail(function() {
				alert('<?php echo esc_js( __( 'حدث خطأ في الاتصال.', 'sweet-house-theme' ) ); ?>');
			}).always(function() {
				$btn.prop('disabled', false).text('<?php echo esc_js( __( 'اختبار الإيميل', 'sweet-house-theme' ) ); ?>');
			});
		});
	});
	</script>
	<?php
}

/**
 * معالج حفظ الإعدادات.
 */
function sweet_house_save_contact_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'sweet_house_save_contact', 'sweet_house_contact_nonce' );

	if ( ! empty( $_POST['restore_default'] ) ) {
		delete_option( 'sweet_house_contact_settings' );
		wp_safe_redirect( add_query_arg( 'sweet_house_contact_restored', '1', admin_url( 'admin.php?page=sweet-house-contact' ) ) );
		exit;
	}

	$data = array(
		'mail_type'             => isset( $_POST['mail_type'] ) ? sanitize_key( $_POST['mail_type'] ) : '',
		'recipient_email'       => isset( $_POST['recipient_email'] ) ? sanitize_email( wp_unslash( $_POST['recipient_email'] ) ) : '',
		'google_email'         => isset( $_POST['google_email'] ) ? sanitize_email( wp_unslash( $_POST['google_email'] ) ) : '',
		'google_app_password'   => isset( $_POST['google_app_password'] ) ? sanitize_text_field( wp_unslash( $_POST['google_app_password'] ) ) : '',
		'smtp_host'             => isset( $_POST['smtp_host'] ) ? sanitize_text_field( wp_unslash( $_POST['smtp_host'] ) ) : '',
		'smtp_port'             => isset( $_POST['smtp_port'] ) ? absint( $_POST['smtp_port'] ) : 587,
		'smtp_user'             => isset( $_POST['smtp_user'] ) ? sanitize_text_field( wp_unslash( $_POST['smtp_user'] ) ) : '',
		'smtp_pass'             => isset( $_POST['smtp_pass'] ) ? $_POST['smtp_pass'] : '', // Stored as-is, sanitized on use
		'smtp_encryption'       => isset( $_POST['smtp_encryption'] ) ? sanitize_key( $_POST['smtp_encryption'] ) : 'tls',
		'smtp_from_email'      => isset( $_POST['smtp_from_email'] ) ? sanitize_email( wp_unslash( $_POST['smtp_from_email'] ) ) : '',
		'page_title'            => isset( $_POST['page_title'] ) ? sanitize_text_field( wp_unslash( $_POST['page_title'] ) ) : '',
		'contact_info_title'   => isset( $_POST['contact_info_title'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_info_title'] ) ) : '',
		'contact_address'      => isset( $_POST['contact_address'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_address'] ) ) : '',
		'contact_phones'       => isset( $_POST['contact_phones'] ) ? sanitize_text_field( wp_unslash( $_POST['contact_phones'] ) ) : '',
		'contact_email_display' => isset( $_POST['contact_email_display'] ) ? sanitize_email( wp_unslash( $_POST['contact_email_display'] ) ) : '',
		'map_link'             => isset( $_POST['map_link'] ) ? esc_url_raw( wp_unslash( $_POST['map_link'] ) ) : '',
		'map_embed_url'        => isset( $_POST['map_embed_url'] ) ? esc_url_raw( wp_unslash( $_POST['map_embed_url'] ) ) : '',
		'map_lat'              => isset( $_POST['map_lat'] ) ? sanitize_text_field( wp_unslash( $_POST['map_lat'] ) ) : '',
		'map_lng'              => isset( $_POST['map_lng'] ) ? sanitize_text_field( wp_unslash( $_POST['map_lng'] ) ) : '',
		'visit_title'          => isset( $_POST['visit_title'] ) ? sanitize_text_field( wp_unslash( $_POST['visit_title'] ) ) : '',
		'visit_hours'          => isset( $_POST['visit_hours'] ) ? sanitize_text_field( wp_unslash( $_POST['visit_hours'] ) ) : '',
	);
	if ( ! empty( $data['smtp_pass'] ) ) {
		$data['smtp_pass'] = wp_unslash( $data['smtp_pass'] );
	}
	$existing = get_option( 'sweet_house_contact_settings', array() );
	if ( isset( $existing['smtp_pass'] ) && empty( $data['smtp_pass'] ) ) {
		$data['smtp_pass'] = $existing['smtp_pass'];
	}
	if ( isset( $existing['google_app_password'] ) && empty( $data['google_app_password'] ) ) {
		$data['google_app_password'] = $existing['google_app_password'];
	}
	update_option( 'sweet_house_contact_settings', $data );
	wp_safe_redirect( add_query_arg( 'sweet_house_contact_saved', '1', admin_url( 'admin.php?page=sweet-house-contact' ) ) );
	exit;
}
add_action( 'admin_post_sweet_house_save_contact', 'sweet_house_save_contact_handler' );

/**
 * AJAX: إرسال رسالة اختبار.
 */
function sweet_house_ajax_test_contact_email() {
	check_ajax_referer( 'sweet_house_test_email', 'nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( __( 'غير مصرح.', 'sweet-house-theme' ) );
	}
	$to = isset( $_POST['recipient'] ) ? sanitize_email( wp_unslash( $_POST['recipient'] ) ) : '';
	if ( ! $to || ! is_email( $to ) ) {
		wp_send_json_error( __( 'البريد المستلم غير صحيح.', 'sweet-house-theme' ) );
	}
	$mail_type = isset( $_POST['mail_type'] ) ? sanitize_key( wp_unslash( $_POST['mail_type'] ) ) : '';
	if ( ! $mail_type ) {
		wp_send_json_error( __( 'يرجى اختيار نوع البريد وحفظ الإعدادات أولاً.', 'sweet-house-theme' ) );
	}
	$saved = sweet_house_get_contact_settings();
	$settings = array(
		'mail_type'           => $mail_type,
		'google_email'        => isset( $_POST['google_email'] ) ? sanitize_email( wp_unslash( $_POST['google_email'] ) ) : ( $saved['google_email'] ?? '' ),
		'google_app_password' => isset( $_POST['google_app_password'] ) && '' !== $_POST['google_app_password'] ? sanitize_text_field( wp_unslash( $_POST['google_app_password'] ) ) : ( $saved['google_app_password'] ?? '' ),
		'smtp_host'           => isset( $_POST['smtp_host'] ) ? sanitize_text_field( wp_unslash( $_POST['smtp_host'] ) ) : ( $saved['smtp_host'] ?? '' ),
		'smtp_port'           => isset( $_POST['smtp_port'] ) ? absint( $_POST['smtp_port'] ) : ( $saved['smtp_port'] ?? 587 ),
		'smtp_user'           => isset( $_POST['smtp_user'] ) ? sanitize_text_field( wp_unslash( $_POST['smtp_user'] ) ) : ( $saved['smtp_user'] ?? '' ),
		'smtp_pass'           => ( isset( $_POST['smtp_pass'] ) && '' !== $_POST['smtp_pass'] ) ? wp_unslash( $_POST['smtp_pass'] ) : ( $saved['smtp_pass'] ?? '' ),
		'smtp_encryption'     => isset( $_POST['smtp_encryption'] ) ? sanitize_key( wp_unslash( $_POST['smtp_encryption'] ) ) : ( $saved['smtp_encryption'] ?? 'tls' ),
		'smtp_from_email'     => isset( $_POST['smtp_from_email'] ) ? sanitize_email( wp_unslash( $_POST['smtp_from_email'] ) ) : ( $saved['smtp_from_email'] ?? '' ),
	);
	set_transient( 'sweet_house_contact_test_settings', $settings, 60 );
	$sent = sweet_house_send_mail_with_config( $to, __( 'رسالة اختبار - تواصل معنا', 'sweet-house-theme' ), __( 'هذه رسالة اختبار من إعدادات صفحة تواصل معنا. إذا وصلتك فهذا يعني أن الإعدادات صحيحة.', 'sweet-house-theme' ), $settings );
	delete_transient( 'sweet_house_contact_test_settings' );
	if ( $sent ) {
		wp_send_json_success();
	}
	wp_send_json_error( __( 'فشل إرسال البريد. تحقق من الإعدادات.', 'sweet-house-theme' ) );
}
add_action( 'wp_ajax_sweet_house_test_contact_email', 'sweet_house_ajax_test_contact_email' );

/**
 * إرسال بريد باستخدام الإعدادات المحددة (Google أو SMTP).
 */
function sweet_house_send_mail_with_config( $to, $subject, $body, $settings = null ) {
	if ( ! $settings ) {
		$settings = sweet_house_get_contact_settings();
	}
	$mail_type = isset( $settings['mail_type'] ) ? $settings['mail_type'] : '';
	if ( ! $mail_type ) {
		return wp_mail( $to, $subject, $body, array( 'Content-Type: text/plain; charset=UTF-8' ) );
	}
	set_transient( 'sweet_house_using_contact_mail', 1, 30 );
	add_filter( 'phpmailer_init', 'sweet_house_phpmailer_contact_config', 10, 1 );
	$result = wp_mail( $to, $subject, $body, array( 'Content-Type: text/plain; charset=UTF-8' ) );
	remove_filter( 'phpmailer_init', 'sweet_house_phpmailer_contact_config', 10 );
	delete_transient( 'sweet_house_using_contact_mail' );
	return $result;
}

/**
 * تكوين PHPMailer للبريد (Google أو SMTP).
 */
function sweet_house_phpmailer_contact_config( $phpmailer ) {
	if ( ! get_transient( 'sweet_house_using_contact_mail' ) ) {
		return;
	}
	$s = get_transient( 'sweet_house_contact_test_settings' );
	if ( ! $s ) {
		$s = sweet_house_get_contact_settings();
	}
	$mail_type = isset( $s['mail_type'] ) ? $s['mail_type'] : '';
	if ( 'google' === $mail_type && ! empty( $s['google_email'] ) && ! empty( $s['google_app_password'] ) ) {
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
	if ( 'smtp' === $mail_type && ! empty( $s['smtp_host'] ) ) {
		$phpmailer->isSMTP();
		$phpmailer->Host       = $s['smtp_host'];
		$phpmailer->Port       = ! empty( $s['smtp_port'] ) ? (int) $s['smtp_port'] : 587;
		$phpmailer->SMTPAuth   = ! empty( $s['smtp_user'] );
		$phpmailer->Username   = isset( $s['smtp_user'] ) ? $s['smtp_user'] : '';
		$phpmailer->Password   = isset( $s['smtp_pass'] ) ? $s['smtp_pass'] : '';
		$from = ! empty( $s['smtp_from_email'] ) ? $s['smtp_from_email'] : ( ! empty( $s['smtp_user'] ) && is_email( $s['smtp_user'] ) ? $s['smtp_user'] : get_option( 'admin_email' ) );
		$phpmailer->SetFrom( $from, get_bloginfo( 'name' ) );
		if ( 'ssl' === $s['smtp_encryption'] ) {
			$phpmailer->SMTPSecure = 'ssl';
		} elseif ( 'tls' === $s['smtp_encryption'] ) {
			$phpmailer->SMTPSecure = 'tls';
		} else {
			$phpmailer->SMTPSecure = false;
			$phpmailer->SMTPAutoTLS = false;
		}
		return;
	}
}
