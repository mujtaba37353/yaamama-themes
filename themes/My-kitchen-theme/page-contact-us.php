<?php
$settings = mykitchen_get_contact_settings();
$notice = '';
$error = '';

if ('POST' === ($_SERVER['REQUEST_METHOD'] ?? '') && isset($_POST['mykitchen_contact_submit'])) {
  check_admin_referer('mykitchen_contact_form');
  $name = sanitize_text_field(wp_unslash($_POST['contact_name'] ?? ''));
  $email = sanitize_email(wp_unslash($_POST['contact_email'] ?? ''));
  $phone = sanitize_text_field(wp_unslash($_POST['contact_phone'] ?? ''));
  $topic = sanitize_text_field(wp_unslash($_POST['contact_topic'] ?? ''));
  $message = sanitize_textarea_field(wp_unslash($_POST['contact_message'] ?? ''));

  if (!$name || !$email || !$message) {
    $error = 'يرجى تعبئة الحقول المطلوبة.';
  } else {
    $to = $settings['recipient_email'] ?: ($settings['email'] ?: get_option('admin_email'));
    if (!$to) {
      $error = 'لم يتم إعداد بريد الاستقبال بعد.';
    } else {
      $subject = $topic ? ('رسالة تواصل: ' . $topic) : 'رسالة تواصل جديدة';
      $body = "الاسم: {$name}\n";
      $body .= "البريد: {$email}\n";
      if ($phone) {
        $body .= "الهاتف: {$phone}\n";
      }
      if ($topic) {
        $body .= "الموضوع: {$topic}\n";
      }
      $body .= "\n" . $message;
      $headers = array('Reply-To: ' . $email);

      if (wp_mail($to, $subject, $body, $headers)) {
        $notice = 'تم إرسال رسالتك بنجاح.';
      } else {
        $error = 'تعذر إرسال الرسالة حالياً.';
      }
    }
  }
}

get_header();
?>

<header data-y="design-header"></header>

<main data-y="main">
  <div class="main-container">
    <div data-y="breadcrumb"></div>
    <div class="contact-us">
      <h1><i class="fa-solid fa-envelope"></i><?php echo esc_html($settings['hero_title']); ?></h1>
      <?php if ($notice) : ?>
        <div class="y-u-mt-2 y-u-mb-1" style="color: green;"><?php echo esc_html($notice); ?></div>
      <?php endif; ?>
      <?php if ($error) : ?>
        <div class="y-u-mt-2 y-u-mb-1" style="color: red;"><?php echo esc_html($error); ?></div>
      <?php endif; ?>
      <div>
        <form action="" method="post">
          <?php wp_nonce_field('mykitchen_contact_form'); ?>
          <div class="y-u-mt-2 y-u-mb-1">
            <label for="contact_name"><?php echo esc_html($settings['label_name']); ?></label>
            <input type="text" id="contact_name" name="contact_name" class="input" required />
          </div>
          <div class="y-u-mb-1">
            <label for="contact_email"><?php echo esc_html($settings['label_email']); ?></label>
            <input type="email" id="contact_email" name="contact_email" class="input" required />
          </div>
          <div class="y-u-mb-1">
            <label for="contact_phone"><?php echo esc_html($settings['label_phone']); ?></label>
            <input type="tel" id="contact_phone" name="contact_phone" class="input" required />
          </div>
          <div class="msg-topic">
            <label for="contact_topic"><?php echo esc_html($settings['label_topic']); ?></label>
            <input type="text" id="contact_topic" name="contact_topic" class="input" required />
          </div>
          <div class="y-u-mb-1">
            <label for="contact_message"><?php echo esc_html($settings['label_message']); ?></label>
            <textarea id="contact_message" name="contact_message" class="textarea" rows="5" required></textarea>
          </div>
          <button type="submit" name="mykitchen_contact_submit" class="btn-primary y-u-d-block"><?php echo esc_html($settings['label_submit']); ?></button>
        </form>
        <div class="contact-info">
          <h2><?php echo esc_html($settings['contact_heading']); ?></h2>
          <p><i class="fa-solid fa-location-dot"></i><?php echo esc_html($settings['address']); ?></p>
          <p><i class="fa-solid fa-phone"></i><?php echo esc_html($settings['phone']); ?></p>
          <p><i class="fa-solid fa-envelope"></i> <?php echo esc_html($settings['email']); ?></p>
        </div>
      </div>
      <h2 class="section-title">
        <img src="<?php echo esc_url(MYK_ASSETS_URI . '/assets/style.svg'); ?>" alt="" />
      </h2>
      <div class="visit">
        <h2><?php echo esc_html($settings['visit_title_1']); ?></h2>
        <h2><?php echo esc_html($settings['visit_title_2']); ?></h2>
        <iframe
          src="<?php echo esc_url($settings['map_embed']); ?>"
          width="600"
          height="450"
          style="border:0;"
          allowfullscreen=""
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"></iframe>
      </div>
    </div>
  </div>
</main>

<?php get_footer(); ?>
