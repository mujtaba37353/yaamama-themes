<?php
/*
Template Name: Contact Us
*/

get_header();

$contact_post = ahmadi_theme_get_latest_post('ahmadi_contact_content');
$contact_hero_title = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_hero_title', true) : '';
$contact_intro_title = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_intro_title', true) : '';
$contact_intro_text = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_intro_text', true) : '';
$contact_whatsapp_title = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_whatsapp_title', true) : '';
$contact_whatsapp_link = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_whatsapp_link', true) : '';
$contact_map_name = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_map_name', true) : '';
$contact_map_address = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_map_address', true) : '';
$contact_map_link = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_map_link', true) : '';
$contact_address_text = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_address_text', true) : '';
$contact_phone_text = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_phone_text', true) : '';
$contact_email_one = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_email_one', true) : '';
$contact_email_two = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_email_two', true) : '';
$contact_hours_line_one = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_hours_line_one', true) : '';
$contact_hours_line_two = $contact_post ? get_post_meta($contact_post->ID, 'ahmadi_contact_hours_line_two', true) : '';

if ($contact_hero_title === '') {
    $contact_hero_title = 'تواصل معنا';
}
if ($contact_intro_title === '') {
    $contact_intro_title = 'اتصل بنا وتواصل معنا!';
}
if ($contact_intro_text === '') {
    $contact_intro_text = 'يسعدنا تواصلك معنا في أي وقت. إذا كان لديك استفسار، اقتراح، أو ترغب في معرفة المزيد عن خدماتنا، فلا تتردد في التواصل معنا عبر النموذج أو وسائل التواصل الاجتماعي.نحن هنا لخدمتك والإجابة على جميع تساؤلاتك بأسرع وقت ممكن.';
}
if ($contact_whatsapp_title === '') {
    $contact_whatsapp_title = 'تواصل معنا مباشرة عبر الواتساب';
}
if ($contact_whatsapp_link === '') {
    $contact_whatsapp_link = 'https://wa.me/+966534411732';
}
if ($contact_map_name === '') {
    $contact_map_name = 'محمد الكندى';
}
if ($contact_map_address === '') {
    $contact_map_address = 'المدينه42313, المملكة العربية السعودية';
}
if ($contact_map_link === '') {
    $contact_map_link = '#';
}
if ($contact_address_text === '') {
    $contact_address_text = 'المدينة المنورة - حي بني الأشهل - محمد بن الأشعث الكندي - ثلاجة الأحمدي';
}
if ($contact_phone_text === '') {
    $contact_phone_text = 'موبيل : 966534411732+';
}
if ($contact_email_one === '') {
    $contact_email_one = 'sales@aahmadi.sa';
}
if ($contact_email_two === '') {
    $contact_email_two = 'info@aahmadi.sa';
}
if ($contact_hours_line_one === '') {
    $contact_hours_line_one = 'السبت - الخميس: 9 ص الى 5 م';
}
if ($contact_hours_line_two === '') {
    $contact_hours_line_two = 'الجمعة (مقفل)';
}
?>

<section class="y-c-hero-About-us">
    <div class="y-c-hero-container">
        <div class="y-c-hero-content-About-us">
            <div class="y-c-hero-text-About-us">
                <h1><?php echo esc_html($contact_hero_title); ?></h1>
            </div>
        </div>
    </div>
</section>
<div class="y-c-contat-us">
    <h1><?php echo esc_html($contact_intro_title); ?></h1>
    <p><?php echo esc_html($contact_intro_text); ?></p>
</div>
<div class="y-c-contat-us-whastapp">
    <h1><?php echo esc_html($contact_whatsapp_title); ?></h1>
    <a href="<?php echo esc_url($contact_whatsapp_link); ?>">
        <i class="fa-brands fa-whatsapp"></i>
    </a>
</div>
<div class="y-c-map-container">
    <div class="y-c-map-card">
        <p><?php echo esc_html($contact_map_name); ?></p>
        <p><?php echo esc_html($contact_map_address); ?></p>
        <br>
        <a href="<?php echo esc_url($contact_map_link); ?>">عرض خريطة أكبر</a>
    </div>
</div>

<section class="y-c-contact-info-card-container">
    <div class="y-c-contact-info-card">
        <div class="y-c-contact-info-card-header">
            <div>
                <span>العنوان
                    تفضل بزيارتنا في</span>
                <i class="fa-solid fa-location-dot"></i>
            </div>
        </div>
        <p><?php echo esc_html($contact_address_text); ?></p>
    </div>
    <a href="tel:<?php echo esc_attr(preg_replace('/[^\d+]/', '', $contact_phone_text)); ?>" class="y-c-contact-link">
        <div class="y-c-contact-info-card">
            <div class="y-c-contact-info-card-header">
                <span>تواصل معنا
                    اتصل على</span>
                <i class="fa-solid fa-phone"></i>
            </div>
            <div>
                <p><?php echo esc_html($contact_phone_text); ?></p>
            </div>
        </div>
    </a>
    <div class="y-c-contact-info-card">
        <div class="y-c-contact-info-card-header">
            <span>راسلنا
                راسلنا على:</span>
            <i class="fa-regular fa-envelope"></i>
        </div>
        <a href="mailto:<?php echo esc_attr($contact_email_one); ?>" class="y-c-contact-link">
            <p><?php echo esc_html($contact_email_one); ?></p>
        </a>
        <a href="mailto:<?php echo esc_attr($contact_email_two); ?>" class="y-c-contact-link">
            <p><?php echo esc_html($contact_email_two); ?></p>
        </a>
    </div>
    <div class="y-c-contact-info-card">
        <div class="y-c-contact-info-card-header">
            <span>أوقات الدوام
                المتجر متاح </span>
            <span class="fa-regular fa-clock info-icon"></span>
        </div>
        <ul>
            <li><?php echo esc_html($contact_hours_line_one); ?></li>
            <li><?php echo esc_html($contact_hours_line_two); ?></li>
        </ul>
    </div>
</section>

<?php
get_footer();
