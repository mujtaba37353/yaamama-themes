<?php
/**
 * Template for Contact Us page
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

get_header();

// Enqueue contact-us specific styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-contact-us', $techno_souq_path . '/templates/contact-us/y-contact-us.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-forms',
    'techno-souq-buttons'
), $theme_version);

// Handle form submission
$form_submitted = false;
$form_message = '';

if (isset($_POST['techno_souq_contact_submit']) && check_admin_referer('techno_souq_contact_form', 'techno_souq_contact_nonce')) {
    $name = sanitize_text_field($_POST['fullName']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $message = sanitize_textarea_field($_POST['message']);
    
    // Configure SMTP if enabled
    techno_souq_configure_smtp();
    
    // Get recipient email from settings
    $to = techno_souq_get_contact_form_email();
    $subject = sprintf(__('رسالة جديدة من %s', 'techno-souq-theme'), get_bloginfo('name'));
    $body = sprintf(
        __("الاسم: %s\nالبريد الإلكتروني: %s\nرقم الهاتف: %s\n\nالرسالة:\n%s", 'techno-souq-theme'),
        $name,
        $email,
        $phone,
        $message
    );
    $headers = array('Content-Type: text/html; charset=UTF-8', 'From: ' . $name . ' <' . $email . '>');
    
    if (wp_mail($to, $subject, nl2br($body), $headers)) {
        $form_submitted = true;
        $form_message = __('تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.', 'techno-souq-theme');
    } else {
        $form_message = __('حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.', 'techno-souq-theme');
    }
}
?>

<main data-y="contact-main">
    <section class="y-l-container" data-y="contact-container">
        <h2 data-y="contact-title">اترك لنا رسالة:</h2>
        <p data-y="contact-subtitle">فريقنا دائما هنا للرد على استفساراتك واقتراحاتك.</p>
        <br data-y="title-form-separator">
        
        <?php if ($form_submitted) : ?>
            <div class="y-c-form-message y-c-form-success" data-y="success-message">
                <?php echo esc_html($form_message); ?>
            </div>
        <?php elseif (!empty($form_message)) : ?>
            <div class="y-c-form-message y-c-form-error" data-y="error-message">
                <?php echo esc_html($form_message); ?>
            </div>
        <?php endif; ?>
        
        <div class="y-l-form-container" data-y="contact-form-container">
            <form action="<?php echo esc_url(get_permalink()); ?>" method="POST" class="y-c-contact-form" data-y="contact-form">
                <?php wp_nonce_field('techno_souq_contact_form', 'techno_souq_contact_nonce'); ?>
                
                <div class="y-c-form-group" data-y="name-form-group">
                    <label for="fullName" data-y="name-label">الاسم</label>
                    <input type="text" id="fullName" name="fullName" 
                        class="y-c-form-input y-c-form-input--underline" 
                        data-y="name-input" 
                        value="<?php echo isset($_POST['fullName']) ? esc_attr($_POST['fullName']) : ''; ?>"
                        required />
                </div>
                
                <div class="y-c-form-group" data-y="email-form-group">
                    <label for="email" data-y="email-label">البريد الالكتروني</label>
                    <input type="email" id="email" name="email" 
                        class="y-c-form-input y-c-form-input--underline"
                        data-y="email-input" 
                        value="<?php echo isset($_POST['email']) ? esc_attr($_POST['email']) : ''; ?>"
                        required />
                </div>
                
                <div class="y-c-form-group" data-y="phone-form-group">
                    <label for="phone" data-y="phone-label">رقم الهاتف</label>
                    <input type="tel" id="phone" name="phone" 
                        class="y-c-form-input y-c-form-input--underline"
                        data-y="phone-input" 
                        value="<?php echo isset($_POST['phone']) ? esc_attr($_POST['phone']) : ''; ?>"
                        required />
                </div>
                
                <div class="y-c-form-group" data-y="message-form-group">
                    <label for="message" data-y="message-label">الرسالة</label>
                    <textarea id="message" name="message" 
                        class="y-c-form-textarea y-c-form-input--underline"
                        data-y="message-textarea" 
                        rows="5"
                        required><?php echo isset($_POST['message']) ? esc_textarea($_POST['message']) : ''; ?></textarea>
                </div>

                <div class="y-c-form-button">
                    <button type="submit" name="techno_souq_contact_submit" class="y-c-btn y-c-btn-primary" data-y="submit-btn">
                        إرسال الرسالة
                    </button>
                </div>
            </form>
        </div>
        
        <div class="y-l-contact-info" data-y="contact-info-section">
            <div class="y-c-contact-card" data-y="contact-info-card">
                <h3 data-y="contact-info-title">بيانات التواصل</h3>
                <?php
                // Get contact info from theme options or use defaults
                $contact_address = get_option('techno_souq_contact_address', '1234 شارع التكنولوجيا، المدينة، الدولة');
                $contact_phone = get_option('techno_souq_contact_phone', '+000 000 0000');
                $contact_email = get_option('techno_souq_contact_email', get_option('admin_email'));
                $contact_website = get_option('techno_souq_contact_website', home_url());
                ?>
                <p data-y="address-info">
                    <i class="fas fa-map-marker-alt"></i> 
                    العنوان: <?php echo esc_html($contact_address); ?>
                </p>
                <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_phone)); ?>" data-y="phone-link">
                    <i class="fas fa-phone-alt"></i> 
                    الهاتف: <?php echo esc_html($contact_phone); ?>
                </a>
                <a href="mailto:<?php echo esc_attr($contact_email); ?>" data-y="email-link">
                    <i class="fas fa-envelope"></i> 
                    البريد الإلكتروني: <?php echo esc_html($contact_email); ?>
                </a>
                <a href="<?php echo esc_url($contact_website); ?>" target="_blank" rel="noopener noreferrer" data-y="website-link">
                    <i class="fas fa-globe"></i> 
                    الموقع الإلكتروني: <?php echo esc_html(parse_url($contact_website, PHP_URL_HOST) ?: $contact_website); ?>
                </a>
            </div>
            
            <div class="y-c-social-container" data-y="social-media-section">
                <h3 data-y="social-media-title">تابعنا على:</h3>
                <?php
                // Get social media links from theme options or use defaults
                $facebook_url = get_option('techno_souq_facebook_url', '#');
                $instagram_url = get_option('techno_souq_instagram_url', '#');
                $snapchat_url = get_option('techno_souq_snapchat_url', '#');
                ?>
                <div class="y-c-social-icons" data-y="social-icons-container">
                    <a href="<?php echo esc_url($facebook_url); ?>" class="y-c-social-icon" aria-label="Facebook" target="_blank" rel="noopener noreferrer" data-y="facebook-link">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="<?php echo esc_url($instagram_url); ?>" class="y-c-social-icon" aria-label="Instagram" target="_blank" rel="noopener noreferrer" data-y="instagram-link">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="<?php echo esc_url($snapchat_url); ?>" class="y-c-social-icon" aria-label="Snapchat" target="_blank" rel="noopener noreferrer" data-y="snapchat-link">
                        <i class="fab fa-snapchat"></i>
                    </a>
                </div>
            </div>
        </div>
        
        <br data-y="contact-map-separator">
        <h2 data-y="map-title">موقعنا على الخريطة</h2>
        <br data-y="map-title-separator">
        <div class="y-c-map-container" data-y="map-container">
            <?php
            $map_image = get_option('techno_souq_contact_map_image', '');
            if (empty($map_image)) {
                $map_image = techno_souq_asset_url('map.png');
            }
            ?>
            <img src="<?php echo esc_url($map_image); ?>" alt="<?php echo esc_attr__('موقعنا على الخريطة', 'techno-souq-theme'); ?>" data-y="map-image">
        </div>
    </section>
</main>

<?php
get_footer();
