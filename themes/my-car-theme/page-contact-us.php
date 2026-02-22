<?php
/**
 * Template Name: صفحة تواصل معنا
 * The template for displaying Contact Us page
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

// Get contact settings
$contact = my_car_get_contact_settings();

get_header();
?>

<main data-y="main">
    <!-- Hero Section -->
    <div class="y-l-page-hero y-l-contact-hero" data-y="contact-us-hero">
        <div class="y-c-hero-content">
            <div class="y-c-hero-icon">
                <i class="fa-solid fa-headset"></i>
            </div>
            <h1 class="y-c-hero-title">تواصل معنا</h1>
            <p class="y-c-hero-subtitle">نحن هنا لمساعدتك على مدار الساعة</p>
        </div>
    </div>

    <div class="y-l-contact-us-form-header" data-y="contact-us-form-header">
        <i class="fa-solid fa-envelope" aria-hidden="true"></i>
        <h2>يسعدنا استقبال رسالتك</h2>
    </div>

    <div class="y-u-container y-l-contact-us-content" data-y="contact-us-content">
        <form action="#" class="y-l-contact-us-form" data-y="contact-us-form">

            <div class="y-l-form-row" data-y="contact-form-row-1">
                <div class="y-l-contact-us-form-field" data-y="contact-name-field">
                    <label for="name">الاسم الكامل<span class="y-c-required-indicator">*</span></label>
                    <input type="text" id="name" name="name" placeholder="أدخل اسمك الكامل" required>
                </div>

                <div class="y-l-contact-us-form-field" data-y="contact-email-field">
                    <label for="email">البريد الإلكتروني<span class="y-c-required-indicator">*</span></label>
                    <input type="email" id="email" name="email" placeholder="أدخل بريدك الإلكتروني" required>
                </div>
            </div>

            <div class="y-l-form-row" data-y="contact-form-row-2">
                <div class="y-l-contact-us-form-field" data-y="contact-phone-field">
                    <label for="phone">رقم الهاتف<span class="y-c-required-indicator">*</span></label>
                    <input type="tel" id="phone" name="phone" placeholder="05XXXXXXXX" required>
                </div>

                <div class="y-l-contact-us-form-field" data-y="contact-subject-field">
                    <label for="subject">موضوع الرسالة<span class="y-c-required-indicator">*</span></label>
                    <input type="text" id="subject" name="subject" placeholder="اختر موضوع رسالتك" required>
                </div>
            </div>

            <div class="y-l-contact-us-form-field" data-y="contact-message-field">
                <label for="message">رسالتك</label>
                <textarea id="message" name="message" rows="5" placeholder="اكتب رسالتك هنا..."></textarea>
            </div>

            <button type="submit" class="y-c-submit-btn y-c-contact-btn">
                <span>إرسال الرسالة</span>
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </form>

        <section class="y-l-contact-us-info" data-y="contact-info-section">
            <h2><i class="fa-solid fa-phone-volume"></i> تواصل معنا</h2>
            <span class="y-c-contact-row">
                <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                <span class="y-c-contact-text"><?php echo esc_html($contact['address']); ?></span>
            </span>
            <span class="y-c-contact-row">
                <i class="fa-solid fa-phone" aria-hidden="true"></i>
                <span class="y-c-contact-text">
                    <?php if (!empty($contact['phone_1'])): ?>
                        <a href="tel:<?php echo esc_attr($contact['phone_1']); ?>"><?php echo esc_html($contact['phone_1']); ?></a>
                    <?php endif; ?>
                    <?php if (!empty($contact['phone_1']) && !empty($contact['phone_2'])): ?> - <?php endif; ?>
                    <?php if (!empty($contact['phone_2'])): ?>
                        <a href="tel:<?php echo esc_attr($contact['phone_2']); ?>"><?php echo esc_html($contact['phone_2']); ?></a>
                    <?php endif; ?>
                </span>
            </span>
            <span class="y-c-contact-row">
                <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                <span class="y-c-contact-text">
                    <a href="mailto:<?php echo esc_attr($contact['email']); ?>"><?php echo esc_html($contact['email']); ?></a>
                </span>
            </span>
            <span class="y-c-contact-row">
                <i class="fa-solid fa-clock" aria-hidden="true"></i>
                <span class="y-c-contact-text"><?php echo esc_html($contact['working_hours']); ?></span>
            </span>
            <?php if ($contact['whatsapp_enabled'] && !empty($contact['whatsapp_number'])): ?>
            <span class="y-c-contact-row">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                <span class="y-c-contact-text">
                    <a href="https://wa.me/<?php echo esc_attr($contact['whatsapp_number']); ?>" target="_blank">
                        <?php echo esc_html($contact['whatsapp_number']); ?>
                    </a>
                </span>
            </span>
            <?php endif; ?>
        </section>
    </div>

    <div class="y-l-active-time">
        <h2>
            <i class="fa-solid fa-car"></i>
            زورونا في <?php echo esc_html($contact['company_name']); ?>
            <br>أوقات الدوام: <?php echo esc_html($contact['working_hours']); ?>
        </h2>
    </div>

    <div class="y-u-container">
        <div class="y-c-map-container" data-y="map-section">
            <div class="y-c-map-card">
                <h3><i class="fa-solid fa-map-marker-alt"></i> موقعنا</h3>
                <p><?php echo esc_html($contact['address']); ?></p>
                <?php if (!empty($contact['map_link'])): ?>
                <a href="<?php echo esc_url($contact['map_link']); ?>" target="_blank" class="y-c-map-link">
                    <i class="fa-solid fa-external-link"></i>
                    عرض خريطة أكبر
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();
?>
