<?php
/*
Template Name: Contact Us
*/
get_header();

$status = isset($_GET['contact']) ? sanitize_text_field($_GET['contact']) : '';
$contact_settings = wp_parse_args(al_thabihah_get_option('al_thabihah_contact_settings', array()), al_thabihah_default_contact_settings());
$contact_image = $contact_settings['image_id'] ? wp_get_attachment_url($contact_settings['image_id']) : al_thabihah_asset_uri('al-thabihah/assets/contact-us.png');
?>

<main>
    <section class="y-l-contact-section" data-y="contact-section">
        <div class="y-u-container y-l-contact-container" data-y="contact-container">

            <div class="y-l-contact-form" data-y="contact-form-container">
                <nav class="y-c-breadcrumbs" aria-label="breadcrumb" data-y="breadcrumbs">
                    <p>
                        <a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a>
                        <span>></span>
                        <span data-y="bc-current">تواصل معنا</span>
                    </p>
                </nav>
                <h1 class="y-c-form-title" data-y="form-title"><?php the_title(); ?></h1>
                <?php if (get_the_content()) : ?>
                    <div class="y-c-form-description">
                        <?php the_content(); ?>
                    </div>
                <?php endif; ?>

                <?php if ($status === 'success') : ?>
                    <div class="y-c-success-message" style="text-align: center; padding: 40px;">
                        <i class="fas fa-check-circle" style="font-size: 48px; color: var(--y-color-success); margin-bottom: 20px;"></i>
                        <h2 style="margin-bottom: 10px;">تم استلام رسالتك بنجاح</h2>
                        <p>سنتواصل معك في أقرب وقت ممكن.</p>
                        <a class="y-c-outline-btn" style="margin-top: 20px;" href="<?php echo esc_url(get_permalink()); ?>">إرسال رسالة أخرى</a>
                    </div>
                <?php else : ?>
                    <form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="POST" class="y-c-contact-form-fields" data-y="contact-form">
                        <input type="hidden" name="action" value="al_thabihah_contact">
                        <?php wp_nonce_field('al_thabihah_contact', 'al_thabihah_contact_nonce'); ?>

                        <div class="y-c-form-group" data-y="form-group-name">
                            <label for="name" class="y-c-form-label">الاسم <span class="y-c-required">*</span></label>
                            <input type="text" id="name" name="name" class="y-c-form-input" required data-y="full-name">
                        </div>

                        <div class="y-c-form-group" data-y="form-group-email">
                            <label for="email" class="y-c-form-label">البريد الإلكتروني <span class="y-c-required">*</span></label>
                            <input type="email" id="email" name="email" class="y-c-form-input" required data-y="email-input">
                        </div>

                        <div class="y-c-form-group" data-y="form-group-phone">
                            <label for="phone" class="y-c-form-label">الهاتف <span class="y-c-required">*</span></label>
                            <input type="tel" id="phone" name="phone" class="y-c-form-input" required data-y="phone-input">
                        </div>

                        <div class="y-c-form-group" data-y="form-group-message">
                            <label for="message" class="y-c-form-label">اكتب رسالتك <span class="y-c-required">*</span></label>
                            <textarea id="message" name="message" class="y-c-form-input" rows="6" required></textarea>
                        </div>

                        <div class="y-l-form-button" data-y="form-submit-button-container">
                            <button type="submit" class="y-c-outline-btn" data-y="form-submit-btn">إرسال</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>

            <div class="y-c-contact-logo" data-y="contact-logo">
                <img src="<?php echo esc_url($contact_image); ?>" alt="Al Thabihah Logo" data-y="logo-image">
            </div>

        </div>
    </section>
</main>

<?php
get_footer();
