<?php
/**
 * Template Name: Contact Us Page
 * Template for displaying the Contact Us page
 *
 * @package MyClinic
 */

defined('ABSPATH') || exit;

// Handle form submission
$contact_message = '';
$contact_error = '';

if (isset($_POST['contact_submit']) && wp_verify_nonce($_POST['contact_nonce'], 'contact_form')) {
    $name = isset($_POST['contact_name']) ? sanitize_text_field($_POST['contact_name']) : '';
    $email = isset($_POST['contact_email']) ? sanitize_email($_POST['contact_email']) : '';
    $phone = isset($_POST['contact_phone']) ? sanitize_text_field($_POST['contact_phone']) : '';
    $message = isset($_POST['contact_message']) ? sanitize_textarea_field($_POST['contact_message']) : '';
    
    if (empty($name) || empty($email) || empty($phone) || empty($message)) {
        $contact_error = 'يرجى ملء جميع الحقول';
    } elseif (!is_email($email)) {
        $contact_error = 'البريد الإلكتروني غير صحيح';
    } else {
        // Send email to configured recipient
        $receive_email = get_option('contact_receive_email', get_option('admin_email'));
        $from_email = get_option('smtp_from_email', get_option('admin_email'));
        $from_name = get_option('smtp_from_name', get_bloginfo('name'));
        
        $subject = 'رسالة جديدة من صفحة التواصل - ' . get_bloginfo('name');
        $email_message = "اسم المرسل: $name\n";
        $email_message .= "البريد الإلكتروني: $email\n";
        $email_message .= "رقم الهاتف: $phone\n\n";
        $email_message .= "الرسالة:\n$message";
        
        $headers = array(
            'Content-Type: text/html; charset=UTF-8',
            'From: ' . $from_name . ' <' . $from_email . '>',
            'Reply-To: ' . $name . ' <' . $email . '>'
        );
        
        if (wp_mail($receive_email, $subject, nl2br(esc_html($email_message)), $headers)) {
            $contact_message = 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.';
            // Clear form
            $_POST = array();
        } else {
            $contact_error = 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.';
        }
    }
}

get_header();
?>

<main>
    <!-- Breadcrumbs Section -->
    <section class="breadcrumbs-container">
        <div class="breadcrumbs container y-u-max-w-1200">
            <a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a>
            /
            <p>تواصل معنا</p>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="auth-section">
        <div class="container right-left y-u-max-w-1200">
            <div class="right">
                <form action="" method="POST">
                    <?php wp_nonce_field('contact_form', 'contact_nonce'); ?>
                    <h2>تواصل معنا</h2>
                    
                    <?php if ($contact_message): ?>
                        <div class="notice notice-success" style="color: green; margin-bottom: var(--y-space-16); padding: var(--y-space-12); background: #e8f5e9; border-radius: var(--y-radius-m);">
                            <?php echo esc_html($contact_message); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($contact_error): ?>
                        <div class="notice notice-error" style="color: red; margin-bottom: var(--y-space-16); padding: var(--y-space-12); background: #ffebee; border-radius: var(--y-radius-m);">
                            <?php echo esc_html($contact_error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="contact_name">الاسم</label>
                        <input type="text" id="contact_name" name="contact_name" value="<?php echo isset($_POST['contact_name']) ? esc_attr($_POST['contact_name']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_email">البريد الإلكتروني</label>
                        <input type="email" id="contact_email" name="contact_email" value="<?php echo isset($_POST['contact_email']) ? esc_attr($_POST['contact_email']) : ''; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="contact_phone">الهاتف</label>
                        <input type="tel" id="contact_phone" name="contact_phone" value="<?php echo isset($_POST['contact_phone']) ? esc_attr($_POST['contact_phone']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="contact_message">الرسالة</label>
                        <textarea id="contact_message" name="contact_message" rows="5" required><?php echo isset($_POST['contact_message']) ? esc_textarea($_POST['contact_message']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" name="contact_submit" class="btn main-button fw">إرسال</button>
                    </div>
                </form>
            </div>
            
            <div class="left">
                <div class="cards">
                    <div class="card">
                        <h2>اتصل بنا</h2>
                        <p>
                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', get_option('footer_contact_phone', '+966 12 345 6789'))); ?>">
                                <?php echo esc_html(get_option('footer_contact_phone', '+966 12 345 6789')); ?>
                            </a>
                        </p>
                    </div>
                    <div class="card">
                        <h2>راسلنا على</h2>
                        <p>
                            <a href="mailto:<?php echo esc_attr(get_option('footer_contact_email', 'Customercare@myclinic.com')); ?>">
                                <?php echo esc_html(get_option('footer_contact_email', 'Customercare@myclinic.com')); ?>
                            </a>
                        </p>
                    </div>
                    <div class="card">
                        <h2>العنوان</h2>
                        <p>
                            <a href="<?php echo esc_url(get_option('footer_contact_map_link', 'https://maps.app.goo.gl/j9xwz9xwz9xwz9xwz9xwz9xw')); ?>" target="_blank">
                                <?php echo esc_html(get_option('footer_contact_address', 'الرياض , المملكة العربية السعودية')); ?>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();
