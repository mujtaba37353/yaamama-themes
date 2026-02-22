<?php
/**
 * Template Name: تواصل معنا
 * Template for Contact Us page — Sweet House design
 *
 * @package Sweet_House_Theme
 */

get_header();

$sent      = isset( $GLOBALS['sweet_house_contact_sent'] ) ? $GLOBALS['sweet_house_contact_sent'] : false;
$has_error = isset( $GLOBALS['sweet_house_contact_error'] ) ? $GLOBALS['sweet_house_contact_error'] : false;

$asset_uri = function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( '' ) : get_template_directory_uri() . '/sweet-house/';
$contact_settings = function_exists( 'sweet_house_get_contact_settings' ) ? sweet_house_get_contact_settings() : array();
$page_title       = isset( $contact_settings['page_title'] ) ? $contact_settings['page_title'] : __( 'يسعدنا استقبال رسالتك', 'sweet-house-theme' );
$contact_info_title = isset( $contact_settings['contact_info_title'] ) ? $contact_settings['contact_info_title'] : __( 'تواصل معنا', 'sweet-house-theme' );
$contact_address   = isset( $contact_settings['contact_address'] ) ? $contact_settings['contact_address'] : __( 'الرياض - المملكة العربية السعودية', 'sweet-house-theme' );
$contact_phones    = isset( $contact_settings['contact_phones'] ) ? $contact_settings['contact_phones'] : '059688929 - 058493948';
$contact_email_display = isset( $contact_settings['contact_email_display'] ) ? $contact_settings['contact_email_display'] : get_option( 'admin_email' );
if ( empty( $contact_email_display ) ) {
	$contact_email_display = isset( $contact_settings['recipient_email'] ) ? $contact_settings['recipient_email'] : get_option( 'admin_email' );
}
$map_link      = isset( $contact_settings['map_link'] ) ? $contact_settings['map_link'] : '';
$map_embed_url = isset( $contact_settings['map_embed_url'] ) ? $contact_settings['map_embed_url'] : '';
if ( empty( $map_embed_url ) && ! empty( $contact_settings['map_lat'] ) && ! empty( $contact_settings['map_lng'] ) ) {
	$map_embed_url = 'https://www.google.com/maps?q=' . floatval( $contact_settings['map_lat'] ) . ',' . floatval( $contact_settings['map_lng'] ) . '&output=embed';
}
if ( empty( $map_embed_url ) && ! empty( $map_link ) && function_exists( 'sweet_house_map_link_to_embed_url' ) ) {
	$map_embed_url = sweet_house_map_link_to_embed_url( $map_link );
}
$has_map = ! empty( $map_embed_url ) || ! empty( $map_link );
$visit_title = isset( $contact_settings['visit_title'] ) ? $contact_settings['visit_title'] : __( 'زورونا في معرض دارك', 'sweet-house-theme' );
$visit_hours  = isset( $contact_settings['visit_hours'] ) ? $contact_settings['visit_hours'] : __( 'أوقات الدوام من 9:30 ص حتى 10:30 م', 'sweet-house-theme' );
?>
<header data-y="design-header" class="contact-design-header">
	<img src="<?php echo esc_url( $asset_uri . 'assets/panner.png' ); ?>" alt="<?php esc_attr_e( 'بانر سويت هاوس - تواصل معنا', 'sweet-house-theme' ); ?>" class="panner-img" />
</header>

<main data-y="main">
	<div class="main-container">
		<nav class="y-breadcrumb-container" aria-label="<?php esc_attr_e( 'مسار التنقل', 'sweet-house-theme' ); ?>">
			<ol class="y-breadcrumb">
				<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
				<li class="y-breadcrumb-item active"><?php esc_html_e( 'تواصل معنا', 'sweet-house-theme' ); ?></li>
			</ol>
		</nav>

		<div class="contact-us">
			<?php if ( $sent ) : ?>
			<div class="y-u-mb-4" style="padding: 1rem; background: #d4edda; border-radius: 10px; color: #155724;">
				<?php esc_html_e( 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.', 'sweet-house-theme' ); ?>
			</div>
			<?php elseif ( $has_error ) : ?>
			<div class="y-u-mb-4" style="padding: 1rem; background: #f8d7da; border-radius: 10px; color: #721c24;">
				<?php esc_html_e( 'يرجى تعبئة جميع الحقول.', 'sweet-house-theme' ); ?>
			</div>
			<?php endif; ?>

			<h1><i class="fa-solid fa-envelope"></i><?php echo esc_html( $page_title ); ?></h1>
			<div>
				<form action="" method="post">
					<?php wp_nonce_field( 'sweet_house_contact', 'sweet_house_contact_nonce' ); ?>
					<div class="y-u-mt-2 y-u-mb-1">
						<label for="contact_name"><?php esc_html_e( 'الاسم ثلاثي', 'sweet-house-theme' ); ?></label>
						<input type="text" id="contact_name" name="contact_name" class="input" required value="<?php echo isset( $_POST['contact_name'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['contact_name'] ) ) ) : ''; ?>" />
					</div>
					<div class="y-u-mb-1">
						<label for="contact_email"><?php esc_html_e( 'البريد الإلكتروني', 'sweet-house-theme' ); ?></label>
						<input type="email" id="contact_email" name="contact_email" class="input" required value="<?php echo isset( $_POST['contact_email'] ) ? esc_attr( sanitize_email( wp_unslash( $_POST['contact_email'] ) ) ) : ''; ?>" />
					</div>
					<div class="y-u-mb-1">
						<label for="contact_phone"><?php esc_html_e( 'رقم الهاتف', 'sweet-house-theme' ); ?></label>
						<input type="tel" id="contact_phone" name="contact_phone" class="input" required value="<?php echo isset( $_POST['contact_phone'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['contact_phone'] ) ) ) : ''; ?>" />
					</div>
					<div class="msg-topic">
						<label for="contact_topic"><?php esc_html_e( 'موضوع الرسالة', 'sweet-house-theme' ); ?></label>
						<input type="text" id="contact_topic" name="contact_topic" class="input" required value="<?php echo isset( $_POST['contact_topic'] ) ? esc_attr( sanitize_text_field( wp_unslash( $_POST['contact_topic'] ) ) ) : ''; ?>" />
					</div>
					<div class="y-u-mb-1">
						<label for="contact_message"><?php esc_html_e( 'رسالتك', 'sweet-house-theme' ); ?></label>
						<textarea id="contact_message" name="contact_message" class="textarea" rows="5" required><?php echo isset( $_POST['contact_message'] ) ? esc_textarea( sanitize_textarea_field( wp_unslash( $_POST['contact_message'] ) ) ) : ''; ?></textarea>
					</div>
					<button type="submit" class="btn-primary y-u-d-block"><?php esc_html_e( 'إرسال', 'sweet-house-theme' ); ?></button>
				</form>
				<div class="contact-info">
					<h2><?php echo esc_html( $contact_info_title ); ?></h2>
					<p><i class="fa-solid fa-location-dot"></i>
						<?php if ( $map_link ) : ?>
							<a href="<?php echo esc_url( $map_link ); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html( $contact_address ); ?></a>
						<?php else : ?>
							<?php echo esc_html( $contact_address ); ?>
						<?php endif; ?>
					</p>
					<p><i class="fa-solid fa-phone"></i><?php echo esc_html( $contact_phones ); ?></p>
					<p><i class="fa-solid fa-envelope"></i><?php echo esc_html( $contact_email_display ); ?></p>
				</div>
			</div>
			<h2 class="section-title"><img src="<?php echo esc_url( $asset_uri . 'assets/style.svg' ); ?>" alt="" /></h2>
			<div class="visit">
				<h2><?php echo esc_html( $visit_title ); ?></h2>
				<h2><?php echo esc_html( $visit_hours ); ?></h2>
				<?php if ( $has_map ) : ?>
					<?php if ( $map_embed_url ) : ?>
					<iframe src="<?php echo esc_url( $map_embed_url ); ?>" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="<?php esc_attr_e( 'خريطة الموقع', 'sweet-house-theme' ); ?>"></iframe>
					<?php if ( $map_link ) : ?>
					<p class="y-u-mt-2">
						<a href="<?php echo esc_url( $map_link ); ?>" target="_blank" rel="noopener noreferrer" class="btn-primary visit-map-link" style="display:inline-block;">
							<i class="fa-solid fa-map-location-dot"></i> <?php esc_html_e( 'فتح في خرائط جوجل', 'sweet-house-theme' ); ?>
						</a>
					</p>
					<?php endif; ?>
					<?php elseif ( $map_link ) : ?>
					<p>
						<a href="<?php echo esc_url( $map_link ); ?>" target="_blank" rel="noopener noreferrer" class="btn-primary visit-map-link" style="display:inline-block;">
							<i class="fa-solid fa-map-location-dot"></i> <?php esc_html_e( 'عرض الموقع على خرائط جوجل', 'sweet-house-theme' ); ?>
						</a>
					</p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
