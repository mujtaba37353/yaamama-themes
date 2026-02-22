<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: contact-settings — إعدادات تواصل (Gmail/SMTP، حقول الصفحة).

function elegance_contact_option_keys() {
	return array(
		'mail_type', 'gmail_email', 'gmail_app_password',
		'smtp_host', 'smtp_port', 'smtp_user', 'smtp_pass', 'smtp_encryption',
		'page_title', 'display_email', 'phones', 'page_content',
	);
}

add_action( 'elegance_admin_render_elegance_contact_settings', 'elegance_render_contact_settings' );

function elegance_render_contact_settings() {
	$mail_type = elegance_get_option( 'contact_mail_type', 'gmail' );
	$opts = array(
		'gmail_email'        => elegance_get_option( 'contact_gmail_email', '' ),
		'gmail_app_password' => elegance_get_option( 'contact_gmail_app_password', '' ),
		'smtp_host'         => elegance_get_option( 'contact_smtp_host', '' ),
		'smtp_port'         => elegance_get_option( 'contact_smtp_port', '587' ),
		'smtp_user'         => elegance_get_option( 'contact_smtp_user', '' ),
		'smtp_pass'         => elegance_get_option( 'contact_smtp_pass', '' ),
		'smtp_encryption'   => elegance_get_option( 'contact_smtp_encryption', 'tls' ),
		'page_title'        => elegance_get_option( 'contact_page_title', 'تواصل معنا' ),
		'display_email'     => elegance_get_option( 'contact_display_email', 'elegance@gmail.com' ),
		'phones'            => elegance_get_option( 'contact_phones', '+966 12 345 6789' ),
		'page_content'      => elegance_get_option( 'contact_page_content', 'يمكنك التواصل معنا عبر البريد الإلكتروني أو الهاتف' ),
		'facebook_url'      => elegance_get_option( 'contact_facebook_url', '' ),
		'instagram_url'     => elegance_get_option( 'contact_instagram_url', '' ),
		'twitter_url'       => elegance_get_option( 'contact_twitter_url', '' ),
		'youtube_url'       => elegance_get_option( 'contact_youtube_url', '' ),
		'address'           => elegance_get_option( 'contact_address', 'الرياض , المملكة العربية السعودية' ),
	);

	if ( isset( $_POST['elegance_contact_save'] ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['elegance_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['elegance_admin_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'elegance_admin_elegance_contact_settings' ) ) {
			$mail_type = isset( $_POST['mail_type'] ) && $_POST['mail_type'] === 'smtp' ? 'smtp' : 'gmail';
			update_option( 'elegance_contact_mail_type', $mail_type );
			update_option( 'elegance_contact_gmail_email', sanitize_email( wp_unslash( $_POST['gmail_email'] ?? '' ) ) );
			update_option( 'elegance_contact_gmail_app_password', sanitize_text_field( wp_unslash( $_POST['gmail_app_password'] ?? '' ) ) );
			update_option( 'elegance_contact_smtp_host', sanitize_text_field( wp_unslash( $_POST['smtp_host'] ?? '' ) ) );
			update_option( 'elegance_contact_smtp_port', absint( $_POST['smtp_port'] ?? 587 ) );
			update_option( 'elegance_contact_smtp_user', sanitize_text_field( wp_unslash( $_POST['smtp_user'] ?? '' ) ) );
			update_option( 'elegance_contact_smtp_pass', sanitize_text_field( wp_unslash( $_POST['smtp_pass'] ?? '' ) ) );
			update_option( 'elegance_contact_smtp_encryption', in_array( $_POST['smtp_encryption'] ?? '', array( 'tls', 'ssl', '' ), true ) ? sanitize_text_field( wp_unslash( $_POST['smtp_encryption'] ) ) : 'tls' );
			update_option( 'elegance_contact_page_title', sanitize_text_field( wp_unslash( $_POST['page_title'] ?? '' ) ) );
			update_option( 'elegance_contact_display_email', sanitize_email( wp_unslash( $_POST['display_email'] ?? '' ) ) );
			update_option( 'elegance_contact_phones', sanitize_text_field( wp_unslash( $_POST['phones'] ?? '' ) ) );
			update_option( 'elegance_contact_page_content', sanitize_textarea_field( wp_unslash( $_POST['page_content'] ?? '' ) ) );
			update_option( 'elegance_contact_facebook_url', esc_url_raw( wp_unslash( $_POST['facebook_url'] ?? '' ) ) );
			update_option( 'elegance_contact_instagram_url', esc_url_raw( wp_unslash( $_POST['instagram_url'] ?? '' ) ) );
			update_option( 'elegance_contact_twitter_url', esc_url_raw( wp_unslash( $_POST['twitter_url'] ?? '' ) ) );
			update_option( 'elegance_contact_youtube_url', esc_url_raw( wp_unslash( $_POST['youtube_url'] ?? '' ) ) );
			update_option( 'elegance_contact_address', sanitize_text_field( wp_unslash( $_POST['address'] ?? '' ) ) );
			echo '<div class="notice notice-success"><p>' . esc_html__( 'تم الحفظ.', 'elegance' ) . '</p></div>';
			$opts = array(
				'gmail_email'        => elegance_get_option( 'contact_gmail_email', '' ),
				'gmail_app_password' => '',
				'smtp_host'          => elegance_get_option( 'contact_smtp_host', '' ),
				'smtp_port'          => elegance_get_option( 'contact_smtp_port', '587' ),
				'smtp_user'          => elegance_get_option( 'contact_smtp_user', '' ),
				'smtp_pass'          => '',
				'smtp_encryption'    => elegance_get_option( 'contact_smtp_encryption', 'tls' ),
				'page_title'         => elegance_get_option( 'contact_page_title', 'تواصل معنا' ),
				'display_email'      => elegance_get_option( 'contact_display_email', 'elegance@gmail.com' ),
				'phones'             => elegance_get_option( 'contact_phones', '+966 12 345 6789' ),
				'page_content'       => elegance_get_option( 'contact_page_content', '' ),
				'facebook_url'       => elegance_get_option( 'contact_facebook_url', '' ),
				'instagram_url'      => elegance_get_option( 'contact_instagram_url', '' ),
				'twitter_url'        => elegance_get_option( 'contact_twitter_url', '' ),
				'youtube_url'        => elegance_get_option( 'contact_youtube_url', '' ),
				'address'            => elegance_get_option( 'contact_address', 'الرياض , المملكة العربية السعودية' ),
			);
			$mail_type = elegance_get_option( 'contact_mail_type', 'gmail' );
		}
	}
	?>
	<form method="post">
		<?php wp_nonce_field( 'elegance_admin_elegance_contact_settings', 'elegance_admin_nonce' ); ?>
		<h2><?php esc_html_e( 'إعدادات البريد', 'elegance' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><?php esc_html_e( 'نوع البريد', 'elegance' ); ?></th>
				<td>
					<label><input type="radio" name="mail_type" value="gmail" <?php checked( $mail_type, 'gmail' ); ?> /> <?php esc_html_e( 'Gmail (App Password)', 'elegance' ); ?></label>
					<br />
					<label><input type="radio" name="mail_type" value="smtp" <?php checked( $mail_type, 'smtp' ); ?> /> <?php esc_html_e( 'SMTP احترافي', 'elegance' ); ?></label>
				</td>
			</tr>
			<tr class="elegance-gmail-fields" style="<?php echo $mail_type !== 'gmail' ? 'display:none' : ''; ?>">
				<th><label for="gmail_email"><?php esc_html_e( 'البريد', 'elegance' ); ?></label></th>
				<td><input type="email" id="gmail_email" name="gmail_email" value="<?php echo esc_attr( $opts['gmail_email'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr class="elegance-gmail-fields" style="<?php echo $mail_type !== 'gmail' ? 'display:none' : ''; ?>">
				<th><label for="gmail_app_password"><?php esc_html_e( 'App Password', 'elegance' ); ?></label></th>
				<td><input type="password" id="gmail_app_password" name="gmail_app_password" value="<?php echo esc_attr( $opts['gmail_app_password'] ); ?>" class="regular-text" autocomplete="off" /></td>
			</tr>
			<tr class="elegance-smtp-fields" style="<?php echo $mail_type !== 'smtp' ? 'display:none' : ''; ?>">
				<th><label for="smtp_host">Host</label></th>
				<td><input type="text" id="smtp_host" name="smtp_host" value="<?php echo esc_attr( $opts['smtp_host'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr class="elegance-smtp-fields" style="<?php echo $mail_type !== 'smtp' ? 'display:none' : ''; ?>">
				<th><label for="smtp_port">Port</label></th>
				<td><input type="number" id="smtp_port" name="smtp_port" value="<?php echo esc_attr( $opts['smtp_port'] ); ?>" class="small-text" /></td>
			</tr>
			<tr class="elegance-smtp-fields" style="<?php echo $mail_type !== 'smtp' ? 'display:none' : ''; ?>">
				<th><label for="smtp_user">Username</label></th>
				<td><input type="text" id="smtp_user" name="smtp_user" value="<?php echo esc_attr( $opts['smtp_user'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr class="elegance-smtp-fields" style="<?php echo $mail_type !== 'smtp' ? 'display:none' : ''; ?>">
				<th><label for="smtp_pass">Password</label></th>
				<td><input type="password" id="smtp_pass" name="smtp_pass" value="<?php echo esc_attr( $opts['smtp_pass'] ); ?>" class="regular-text" autocomplete="off" /></td>
			</tr>
			<tr class="elegance-smtp-fields" style="<?php echo $mail_type !== 'smtp' ? 'display:none' : ''; ?>">
				<th><label for="smtp_encryption">Encryption</label></th>
				<td>
					<select id="smtp_encryption" name="smtp_encryption">
						<option value="" <?php selected( $opts['smtp_encryption'], '' ); ?>><?php esc_html_e( 'لا', 'elegance' ); ?></option>
						<option value="tls" <?php selected( $opts['smtp_encryption'], 'tls' ); ?>>TLS</option>
						<option value="ssl" <?php selected( $opts['smtp_encryption'], 'ssl' ); ?>>SSL</option>
					</select>
				</td>
			</tr>
		</table>
		<h2><?php esc_html_e( 'محتوى صفحة تواصل معنا', 'elegance' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><label for="page_title"><?php esc_html_e( 'العنوان', 'elegance' ); ?></label></th>
				<td><input type="text" id="page_title" name="page_title" value="<?php echo esc_attr( $opts['page_title'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="display_email"><?php esc_html_e( 'البريد المعروض', 'elegance' ); ?></label></th>
				<td><input type="email" id="display_email" name="display_email" value="<?php echo esc_attr( $opts['display_email'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="phones"><?php esc_html_e( 'أرقام الجوال', 'elegance' ); ?></label></th>
				<td><input type="text" id="phones" name="phones" value="<?php echo esc_attr( $opts['phones'] ); ?>" class="regular-text" /></td>
			</tr>
			<tr>
				<th><label for="page_content"><?php esc_html_e( 'محتوى الصفحة', 'elegance' ); ?></label></th>
				<td><textarea id="page_content" name="page_content" rows="4" class="large-text"><?php echo esc_textarea( $opts['page_content'] ); ?></textarea></td>
			</tr>
			<tr>
				<th><label for="address"><?php esc_html_e( 'العنوان', 'elegance' ); ?></label></th>
				<td><input type="text" id="address" name="address" value="<?php echo esc_attr( $opts['address'] ); ?>" class="large-text" placeholder="الرياض , المملكة العربية السعودية" /></td>
			</tr>
		</table>
		<h2><?php esc_html_e( 'روابط التواصل الاجتماعي', 'elegance' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><label for="facebook_url"><?php esc_html_e( 'فيسبوك', 'elegance' ); ?></label></th>
				<td><input type="url" id="facebook_url" name="facebook_url" value="<?php echo esc_attr( $opts['facebook_url'] ); ?>" class="large-text" placeholder="https://facebook.com/..." /></td>
			</tr>
			<tr>
				<th><label for="instagram_url"><?php esc_html_e( 'انستقرام', 'elegance' ); ?></label></th>
				<td><input type="url" id="instagram_url" name="instagram_url" value="<?php echo esc_attr( $opts['instagram_url'] ); ?>" class="large-text" placeholder="https://instagram.com/..." /></td>
			</tr>
			<tr>
				<th><label for="twitter_url"><?php esc_html_e( 'تويتر / X', 'elegance' ); ?></label></th>
				<td><input type="url" id="twitter_url" name="twitter_url" value="<?php echo esc_attr( $opts['twitter_url'] ); ?>" class="large-text" placeholder="https://twitter.com/..." /></td>
			</tr>
			<tr>
				<th><label for="youtube_url"><?php esc_html_e( 'يوتيوب', 'elegance' ); ?></label></th>
				<td><input type="url" id="youtube_url" name="youtube_url" value="<?php echo esc_attr( $opts['youtube_url'] ); ?>" class="large-text" placeholder="https://youtube.com/..." /></td>
			</tr>
		</table>
		<p><button type="submit" name="elegance_contact_save" class="button button-primary"><?php esc_html_e( 'حفظ', 'elegance' ); ?></button></p>
	</form>
	<script>
	document.addEventListener('DOMContentLoaded', function() {
		function toggle() {
			var v = document.querySelector('input[name="mail_type"]:checked').value;
			document.querySelectorAll('.elegance-gmail-fields').forEach(function(el) { el.style.display = v === 'gmail' ? '' : 'none'; });
			document.querySelectorAll('.elegance-smtp-fields').forEach(function(el) { el.style.display = v === 'smtp' ? '' : 'none'; });
		}
		document.querySelectorAll('input[name="mail_type"]').forEach(function(r) { r.addEventListener('change', toggle); });
	});
	</script>
	<?php
}

// wp_mail is configured via phpmailer_init in helpers.php (loaded always).
