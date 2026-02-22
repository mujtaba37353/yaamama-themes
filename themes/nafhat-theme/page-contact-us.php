<?php
/**
 * Template Name: Contact Us
 * Template for Contact Page
 *
 * This template is used for:
 * - Pages with slug 'contact-us' or 'contact'
 * - Pages using the "Contact Us" template
 *
 * @package Nafhat
 * @since 1.0.0
 */

get_header();
?>

<main id="main" class="site-main y-u-p-t-0">
    <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
        <div class="header y-u-flex y-u-justify-between y-u-items-center y-u-p-y-56 y-u-p-t-24">
            <h1 class="y-u-color-primary y-u-text-2xl"><?php the_title(); ?></h1>
        </div>
        
        <?php
        // Display success message if form was submitted
        if (isset($_GET['contact_sent']) && $_GET['contact_sent'] == '1') {
            echo '<div class="contact-success y-u-mb-24" style="background: var(--y-color-success); color: white; padding: var(--y-space-16); border-radius: var(--y-radius-8); text-align: center;">';
            esc_html_e('تم إرسال رسالتك بنجاح! سنتواصل معك قريباً.', 'nafhat');
            echo '</div>';
        }
        
        // Display error message if there was an error
        if (isset($_GET['contact_error']) && $_GET['contact_error'] == '1') {
            echo '<div class="contact-error y-u-mb-24" style="background: var(--y-color-danger); color: white; padding: var(--y-space-16); border-radius: var(--y-radius-8); text-align: center;">';
            esc_html_e('حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.', 'nafhat');
            echo '</div>';
        }
        ?>
        
        <div class="y-u-max-w-1200 y-u-w-full contact-grid">
            <!-- Contact Form -->
            <div class="contact-card y-u-surface y-u-rounded-12 y-u-shadow-sm">
                <h2 class="y-u-text-xl y-u-text-bold"><?php esc_html_e('أرسل رسالة', 'nafhat'); ?></h2>
                <form class="y-u-grid y-u-gap-16" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
                    <?php wp_nonce_field('nafhat_contact_form', 'nafhat_contact_nonce'); ?>
                    <input type="hidden" name="action" value="nafhat_contact_form">
                    
                    <div class="y-c-field">
                        <label class="y-c-label" for="contact_name"><?php esc_html_e('الاسم الكامل', 'nafhat'); ?></label>
                        <input 
                            class="y-c-input" 
                            id="contact_name" 
                            name="contact_name" 
                            type="text" 
                            placeholder="<?php esc_attr_e('اكتب اسمك', 'nafhat'); ?>" 
                            required 
                            value="<?php echo isset($_POST['contact_name']) ? esc_attr($_POST['contact_name']) : ''; ?>"
                        />
                    </div>
                    
                    <div class="y-c-field">
                        <label class="y-c-label" for="contact_email"><?php esc_html_e('البريد الإلكتروني', 'nafhat'); ?></label>
                        <input 
                            class="y-c-input" 
                            id="contact_email" 
                            name="contact_email" 
                            type="email" 
                            placeholder="<?php esc_attr_e('example@mail.com', 'nafhat'); ?>" 
                            required 
                            value="<?php echo isset($_POST['contact_email']) ? esc_attr($_POST['contact_email']) : ''; ?>"
                        />
                    </div>
                    
                    <div class="y-c-field">
                        <label class="y-c-label" for="contact_subject"><?php esc_html_e('الموضوع', 'nafhat'); ?></label>
                        <input 
                            class="y-c-input" 
                            id="contact_subject" 
                            name="contact_subject" 
                            type="text" 
                            placeholder="<?php esc_attr_e('موضوع الرسالة', 'nafhat'); ?>"
                            value="<?php echo isset($_POST['contact_subject']) ? esc_attr($_POST['contact_subject']) : ''; ?>"
                        />
                    </div>
                    
                    <div class="y-c-field">
                        <label class="y-c-label" for="contact_message"><?php esc_html_e('نص الرسالة', 'nafhat'); ?></label>
                        <textarea 
                            class="y-c-textarea" 
                            id="contact_message" 
                            name="contact_message" 
                            rows="5" 
                            placeholder="<?php esc_attr_e('اكتب رسالتك هنا', 'nafhat'); ?>"
                            required
                        ><?php echo isset($_POST['contact_message']) ? esc_textarea($_POST['contact_message']) : ''; ?></textarea>
                        <p class="y-c-help"><?php esc_html_e('سنرد خلال 24 ساعة عمل.', 'nafhat'); ?></p>
                    </div>
                    
                    <button type="submit" class="y-c-btn y-c-btn--primary y-u-w-full">
                        <?php esc_html_e('إرسال الرسالة', 'nafhat'); ?>
                    </button>
                </form>
            </div>

            <!-- Contact Information -->
            <div class="info-card y-u-surface y-u-rounded-12 y-u-shadow-sm">
                <h2 class="y-u-text-xl y-u-text-bold"><?php esc_html_e('معلومات التواصل', 'nafhat'); ?></h2>
                <ul class="contact-list">
                    <?php
                    // Get contact information from contact settings
                    $contact_settings = nafhat_get_contact_settings();
                    $contact_address = $contact_settings['address'];
                    $contact_email = $contact_settings['display_email'];
                    $contact_phone = $contact_settings['phone'];
                    $contact_instagram = $contact_settings['instagram'];
                    $contact_facebook = $contact_settings['facebook'];
                    $contact_snapchat = $contact_settings['snapchat'];
                    $contact_twitter = $contact_settings['twitter'];
                    $contact_tiktok = $contact_settings['tiktok'];
                    $map_embed = $contact_settings['map_embed_url'];
                    ?>
                    
                    <?php if (!empty($contact_address)) : ?>
                    <li>
                        <i class="fas fa-location-dot"></i>
                        <span><?php echo esc_html($contact_address); ?></span>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (!empty($contact_email)) : ?>
                    <li>
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (!empty($contact_phone)) : ?>
                    <li>
                        <i class="fas fa-phone"></i>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_phone)); ?>"><?php echo esc_html($contact_phone); ?></a>
                    </li>
                    <?php endif; ?>
                    
                    <?php if (!empty($contact_instagram) || !empty($contact_facebook) || !empty($contact_snapchat) || !empty($contact_twitter) || !empty($contact_tiktok)) : ?>
                    <li class="socials">
                        <?php if (!empty($contact_instagram)) : ?>
                        <a aria-label="Instagram" href="<?php echo esc_url($contact_instagram); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($contact_facebook)) : ?>
                        <a aria-label="Facebook" href="<?php echo esc_url($contact_facebook); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-facebook"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($contact_snapchat)) : ?>
                        <a aria-label="Snapchat" href="<?php echo esc_url($contact_snapchat); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-snapchat"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($contact_twitter)) : ?>
                        <a aria-label="Twitter" href="<?php echo esc_url($contact_twitter); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <?php endif; ?>
                        <?php if (!empty($contact_tiktok)) : ?>
                        <a aria-label="TikTok" href="<?php echo esc_url($contact_tiktok); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <?php endif; ?>
                    </li>
                    <?php endif; ?>
                </ul>

                <?php if (!empty($map_embed)) : ?>
                <div class="map">
                    <iframe 
                        title="<?php esc_attr_e('خريطة الموقع', 'nafhat'); ?>"
                        src="<?php echo esc_url($map_embed); ?>"
                        width="100%" 
                        height="240" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
