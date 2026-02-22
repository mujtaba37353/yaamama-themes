<?php
/**
 * Template Name: Contact Us
 */
get_header();
$assets = get_template_directory_uri() . '/mallati/assets';
$email_fallback = get_theme_mod('mallati_email');
if (empty($email_fallback)) $email_fallback = get_option('admin_email');
$map_url = get_theme_mod('mallati_map_embed', 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3624.006470365758!2d46.675296!3d24.713551!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f042f4f9b7a23%3A0x9af0b5a24b!2sRiyadh!5e0!3m2!1sar!2ssa!4v1688570000000');
$form_title = get_option('mallati_contact_form_title', __('أرسل رسالة', 'mallati-theme'));
$form_desc = get_option('mallati_contact_form_desc', __('أرسل رسالة إلينا وسنرد عليك في أسرع وقت ممكن.', 'mallati-theme'));
?>
<main class="y-u-p-t-0">
  <section class="container y-u-w-full y-u-flex y-u-justify-center y-u-flex-col">
    <?php
    if (!function_exists('wc_print_notices')) {
        $notice = get_transient('mallati_contact_notice');
        if ($notice === 'success') {
            delete_transient('mallati_contact_notice');
            echo '<div class="woocommerce-message" role="alert">' . esc_html__('تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.', 'mallati-theme') . '</div>';
        } elseif ($notice === 'error') {
            delete_transient('mallati_contact_notice');
            echo '<div class="woocommerce-error" role="alert">' . esc_html__('حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.', 'mallati-theme') . '</div>';
        }
    } else {
        wc_print_notices();
    }
    ?>
    <div class="y-u-max-w-1200 contact-grid">
      <div class="contact-card">
        <h2 class="y-u-text-xl y-u-text-bold y-u-color-primary"><?php echo esc_html($form_title); ?></h2>
        <p class="y-u-text-muted"><?php echo esc_html($form_desc); ?></p>
        <form id="contact-form" class="y-u-grid y-u-gap-16" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post">
          <input type="hidden" name="action" value="mallati_contact_form" />
          <?php wp_nonce_field('mallati_contact', 'mallati_contact_nonce'); ?>
          <div class="y-c-field">
            <label class="y-c-label" for="name"><?php esc_html_e('الاسم الكامل', 'mallati-theme'); ?></label>
            <input class="y-c-input" id="name" name="name" type="text" placeholder="<?php esc_attr_e('اكتب اسمك', 'mallati-theme'); ?>" required />
          </div>
          <div class="y-c-field">
            <label class="y-c-label" for="number"><?php esc_html_e('رقم الجوال', 'mallati-theme'); ?></label>
            <input class="y-c-input" id="number" name="number" type="tel" placeholder="05xxxxxxxx" required />
          </div>
          <div class="y-c-field">
            <label class="y-c-label" for="email"><?php esc_html_e('البريد الإلكتروني', 'mallati-theme'); ?></label>
            <input class="y-c-input" id="email" name="email" type="email" placeholder="example@mail.com" required />
          </div>
          <div class="y-c-field">
            <label class="y-c-label" for="message"><?php esc_html_e('نص الرسالة', 'mallati-theme'); ?></label>
            <textarea class="y-c-textarea" id="message" name="message" rows="5" placeholder="<?php esc_attr_e('اكتب رسالتك هنا', 'mallati-theme'); ?>" required></textarea>
            <p class="y-c-help"><?php esc_html_e('سنرد خلال 24 ساعة عمل.', 'mallati-theme'); ?></p>
          </div>
          <button type="submit" class="y-c-btn--primary y-u-w-full"><?php esc_html_e('إرسال الرسالة', 'mallati-theme'); ?></button>
        </form>
      </div>
      <div class="info-card">
        <h2 class="y-u-text-xl y-u-text-bold y-u-color-primary"><?php esc_html_e('معلومات التواصل', 'mallati-theme'); ?></h2>
        <ul class="contact-list">
          <li>
            <img src="<?php echo esc_url($assets); ?>/address-primary.svg" alt="" />
            <span><?php echo esc_html(get_theme_mod('mallati_address', 'الرياض، المملكة العربية السعودية')); ?></span>
          </li>
          <li>
            <img src="<?php echo esc_url($assets); ?>/email-primary.svg" alt="" />
            <a href="mailto:<?php echo esc_attr($email_fallback); ?>"><?php echo esc_html($email_fallback); ?></a>
          </li>
          <li>
            <img src="<?php echo esc_url($assets); ?>/phone-primary.svg" alt="" />
            <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', get_theme_mod('mallati_phone', '+966500000000'))); ?>"><?php echo esc_html(get_theme_mod('mallati_phone', '+966 50 000 0000')); ?></a>
          </li>
          <?php $whatsapp = get_theme_mod('mallati_whatsapp', ''); if ($whatsapp) { $wa_num = preg_replace('/\D/', '', $whatsapp); $wa_link = 'https://wa.me/' . (substr($wa_num, 0, 1) === '0' ? '966' . ltrim($wa_num, '0') : $wa_num); ?>
          <li><img src="<?php echo esc_url($assets); ?>/phone-primary.svg" alt="" /><a href="<?php echo esc_url($wa_link); ?>" target="_blank" rel="noopener"><?php echo esc_html($whatsapp); ?> (واتساب)</a></li>
          <?php } ?>
          <li class="socials">
            <a aria-label="Instagram" href="<?php echo esc_url(get_theme_mod('mallati_instagram', '#')); ?>"><img src="<?php echo esc_url($assets); ?>/instagrame.svg" alt="" /></a>
            <a aria-label="Facebook" href="<?php echo esc_url(get_theme_mod('mallati_facebook', '#')); ?>"><img src="<?php echo esc_url($assets); ?>/facebook.svg" alt="" /></a>
            <a aria-label="Snapchat" href="<?php echo esc_url(get_theme_mod('mallati_snapchat', '#')); ?>"><img src="<?php echo esc_url($assets); ?>/snapchat.svg" alt="" /></a>
          </li>
        </ul>
        <div class="map">
          <iframe title="map" src="<?php echo esc_url($map_url); ?>" width="100%" height="240" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
      </div>
    </div>
  </section>
</main>
<?php get_footer(); ?>
