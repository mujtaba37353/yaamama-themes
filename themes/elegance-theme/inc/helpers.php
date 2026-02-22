<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: helpers — دوال مساعدة.

/**
 * Get option with fallback.
 *
 * @param string $key     Option key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function elegance_get_option( $key, $default = '' ) {
	$value = get_option( 'elegance_' . $key, $default );
	return $value !== '' && $value !== null ? $value : $default;
}

/**
 * Get theme mod with fallback.
 *
 * @param string $key     Theme mod key.
 * @param mixed  $default Default value.
 * @return mixed
 */
function elegance_get_theme_mod( $key, $default = '' ) {
	$value = get_theme_mod( 'elegance_' . $key, $default );
	return $value !== '' && $value !== null ? $value : $default;
}

/**
 * Get attachment image URL by attachment ID with fallback.
 *
 * @param int         $attachment_id Attachment ID.
 * @param string      $fallback_url  Fallback URL if no attachment.
 * @param string|array $size         Image size.
 * @return string
 */
function elegance_get_image_url( $attachment_id, $fallback_url = '', $size = 'full' ) {
	if ( ! $attachment_id || ! wp_attachment_is_image( $attachment_id ) ) {
		return $fallback_url;
	}
	$url = wp_get_attachment_image_url( $attachment_id, $size );
	return $url ? $url : $fallback_url;
}

/**
 * Configure PHPMailer from Elegance contact settings (Gmail or SMTP). Runs on frontend and admin when wp_mail is used.
 *
 * @param PHPMailer $phpmailer PHPMailer instance.
 */
function elegance_phpmailer_init( $phpmailer ) {
	$type = elegance_get_option( 'contact_mail_type', 'gmail' );
	if ( $type === 'gmail' ) {
		$email = elegance_get_option( 'contact_gmail_email', '' );
		$pass  = elegance_get_option( 'contact_gmail_app_password', '' );
		if ( $email && $pass ) {
			$phpmailer->isSMTP();
			$phpmailer->Host       = 'smtp.gmail.com';
			$phpmailer->SMTPAuth   = true;
			$phpmailer->Port       = 587;
			$phpmailer->SMTPSecure = 'tls';
			$phpmailer->Username   = $email;
			$phpmailer->Password   = $pass;
		}
	} elseif ( $type === 'smtp' ) {
		$host = elegance_get_option( 'contact_smtp_host', '' );
		if ( $host ) {
			$phpmailer->isSMTP();
			$phpmailer->Host       = $host;
			$phpmailer->SMTPAuth   = true;
			$phpmailer->Port       = (int) elegance_get_option( 'contact_smtp_port', 587 );
			$phpmailer->SMTPSecure = elegance_get_option( 'contact_smtp_encryption', 'tls' );
			$phpmailer->Username   = elegance_get_option( 'contact_smtp_user', '' );
			$phpmailer->Password   = elegance_get_option( 'contact_smtp_pass', '' );
		}
	}
}
add_action( 'phpmailer_init', 'elegance_phpmailer_init', 10, 1 );

/**
 * Theme color mod keys and defaults from design (elegance/base/tokens.css, header, footer).
 *
 * @return array [ key => default_hex ]
 */
function elegance_theme_color_keys_list() {
	return array(
		'header_color'       => '#f3f1f1',  /* --y-color-bg (header.css) */
		'footer_color'       => '#8f72ec',  /* --y-color-primary (footer.css) */
		'btn_cart_color'     => '#8f72ec',  /* --y-color-primary (products.css) */
		'btn_checkout_color' => '#8f72ec',  /* --y-color-primary */
		'btn_payment_color'  => '#ef4444',  /* --y-color-danger (tokens.css) */
		'page_bg_color'      => '#f3f1f1',  /* --y-color-bg */
	);
}

/**
 * Build CSS custom properties from theme_mod. For use in :root.
 *
 * @return string CSS fragment (e.g. --elegance-header-color: #fff;)
 */
function elegance_theme_css_variables() {
	$keys = elegance_theme_color_keys_list();
	$css  = '';
	foreach ( $keys as $key => $default ) {
		$val = get_theme_mod( 'elegance_' . $key, $default );
		if ( $val !== '' ) {
			$var = '--elegance-' . str_replace( '_', '-', $key );
			$css .= $var . ':' . esc_attr( $val ) . ';';
		}
	}
	return $css;
}
