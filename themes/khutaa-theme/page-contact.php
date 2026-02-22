<?php
/**
 * Template Name: صفحة تواصل معنا
 * Template for displaying contact us page
 *
 * @package KhutaaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';

// Enqueue contact page specific styles
wp_enqueue_style( 'khutaa-design-header', $khutaa_uri . '/templates/pages header/y-c-design-header.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-breadcrumb', $khutaa_uri . '/components/layout/y-c-breadcrumb.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-contact-us', $khutaa_uri . '/components/contact us/y-c-contact-us.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-btn', $khutaa_uri . '/components/buttons/y-c-btn.css', array(), '1.0.0' );

// Enqueue scripts
wp_enqueue_script( 'khutaa-design-header', $khutaa_uri . '/js/y-design-header.js', array(), '1.0.0', true );

// Get banner image
$banner_2_image = khutaa_get_demo_content( 'khutaa_banner_2_image' );
$default_banner = $khutaa_uri . '/assets/design.png';

// Get contact info from contact settings
$contact_address = khutaa_get_contact_setting( 'address', 'الرياض - المملكة العربية السعودية' );
$contact_phone = khutaa_get_contact_setting( 'phone', '058493948 - 059688929' );
$contact_email = khutaa_get_contact_setting( 'email', 'info@super.ksa.com' );

// Handle form submission
$message_sent = false;
$error_message = '';

if ( isset( $_POST['contact_form_submit'] ) && check_admin_referer( 'khutaa_contact_form', 'khutaa_contact_nonce' ) ) {
	$name = isset( $_POST['name'] ) ? sanitize_text_field( $_POST['name'] ) : '';
	$email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	$phone = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$topic = isset( $_POST['topic'] ) ? sanitize_text_field( $_POST['topic'] ) : '';
	$message = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

	if ( ! empty( $name ) && ! empty( $email ) && ! empty( $message ) ) {
		// Send email
		$to = get_option( 'admin_email' );
		$subject = sprintf( __( 'رسالة جديدة من الموقع: %s', 'khutaa-theme' ), $topic );
		$body = sprintf(
			__( "اسم المرسل: %s\n\nالبريد الإلكتروني: %s\n\nرقم الهاتف: %s\n\nالموضوع: %s\n\nالرسالة:\n%s", 'khutaa-theme' ),
			$name,
			$email,
			$phone,
			$topic,
			$message
		);
		$headers = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_bloginfo( 'name' ) . ' <' . $email . '>' );

		$sent = wp_mail( $to, $subject, nl2br( esc_html( $body ) ), $headers );

		if ( $sent ) {
			$message_sent = true;
		} else {
			$error_message = __( 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.', 'khutaa-theme' );
		}
	} else {
		$error_message = __( 'يرجى ملء جميع الحقول المطلوبة.', 'khutaa-theme' );
	}
}
?>

<header class="design-header">
	<?php if ( $banner_2_image ) : ?>
		<img src="<?php echo esc_url( $banner_2_image ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
	<?php else : ?>
		<img src="<?php echo esc_url( $default_banner ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
	<?php endif; ?>
</header>

<?php
// Breadcrumb
$breadcrumb_items = array(
	array(
		'text' => esc_html__( 'الرئيسية', 'khutaa-theme' ),
		'url'  => home_url( '/' ),
	),
	array(
		'text' => esc_html__( 'تواصل معنا', 'khutaa-theme' ),
		'url'  => '',
	),
);
?>
<nav aria-label="breadcrumb" class="y-breadcrumb-container">
	<ol class="y-breadcrumb">
		<?php foreach ( $breadcrumb_items as $index => $item ) : ?>
			<li class="y-breadcrumb-item <?php echo ( $index === count( $breadcrumb_items ) - 1 ) ? 'active' : ''; ?>">
				<?php if ( ! empty( $item['url'] ) && $index < count( $breadcrumb_items ) - 1 ) : ?>
					<a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
				<?php else : ?>
					<?php echo esc_html( $item['text'] ); ?>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>

<main id="main" class="y-u-container" style="min-height: 400px; padding: 2rem 0;">
	<div class="contact-us" style="display: block; visibility: visible; opacity: 1;">
		<div class="contact-header">
			<h1><?php esc_html_e( 'يسعدنا استقبال رسالتك', 'khutaa-theme' ); ?> <i class="fa-solid fa-envelope"></i></h1>
		</div>

		<?php if ( $message_sent ) : ?>
			<div class="contact-success" style="background: #4caf50; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
				<?php esc_html_e( 'تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.', 'khutaa-theme' ); ?>
			</div>
		<?php endif; ?>

		<?php if ( $error_message ) : ?>
			<div class="contact-error" style="background: #f44336; color: white; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; text-align: center;">
				<?php echo esc_html( $error_message ); ?>
			</div>
		<?php endif; ?>

		<div class="contact-container">
			<div class="contact-info">
				<h2><?php esc_html_e( 'تواصل معنا', 'khutaa-theme' ); ?></h2>
				<div class="info-item">
					<i class="fa-solid fa-location-dot"></i>
					<p><?php echo esc_html( $contact_address ); ?></p>
				</div>
				<div class="info-item">
					<i class="fa-solid fa-phone"></i>
					<p><?php echo esc_html( $contact_phone ); ?></p>
				</div>
				<div class="info-item">
					<i class="fa-solid fa-envelope"></i>
					<p><a href="mailto:<?php echo esc_attr( $contact_email ); ?>"><?php echo esc_html( $contact_email ); ?></a></p>
				</div>
			</div>

			<form action="" method="post" class="contact-form">
				<?php wp_nonce_field( 'khutaa_contact_form', 'khutaa_contact_nonce' ); ?>
				
				<div class="form-row">
					<div class="form-group">
						<label for="name"><?php esc_html_e( 'الإسم ثلاثي*', 'khutaa-theme' ); ?></label>
						<input type="text" id="name" name="name" class="input" value="<?php echo isset( $_POST['name'] ) ? esc_attr( $_POST['name'] ) : ''; ?>" required />
					</div>
					<div class="form-group">
						<label for="email"><?php esc_html_e( 'البريد الإلكتروني*', 'khutaa-theme' ); ?></label>
						<input type="email" id="email" name="email" class="input" value="<?php echo isset( $_POST['email'] ) ? esc_attr( $_POST['email'] ) : ''; ?>" required />
					</div>
				</div>

				<div class="form-row">
					<div class="form-group">
						<label for="phone"><?php esc_html_e( 'رقم الهاتف*', 'khutaa-theme' ); ?></label>
						<input type="tel" id="phone" name="phone" class="input" value="<?php echo isset( $_POST['phone'] ) ? esc_attr( $_POST['phone'] ) : ''; ?>" required />
					</div>
					<div class="form-group">
						<label for="topic"><?php esc_html_e( 'موضوع الرسالة*', 'khutaa-theme' ); ?></label>
						<input type="text" id="topic" name="topic" class="input" value="<?php echo isset( $_POST['topic'] ) ? esc_attr( $_POST['topic'] ) : ''; ?>" required />
					</div>
				</div>

				<div class="form-group full-width">
					<label for="message"><?php esc_html_e( 'نص الرسالة*', 'khutaa-theme' ); ?></label>
					<textarea id="message" name="message" class="textarea" rows="6" required><?php echo isset( $_POST['message'] ) ? esc_textarea( $_POST['message'] ) : ''; ?></textarea>
				</div>

				<button type="submit" name="contact_form_submit" class="btn-primary"><?php esc_html_e( 'إرسال', 'khutaa-theme' ); ?></button>
			</form>
		</div>
	</div>
</main>

<?php
get_footer( 'shop' );
