<?php
/**
 * Theme Customizer Settings
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Customizer Settings
 */
function my_clinic_customize_register($wp_customize) {
    // Hero Section Settings
    $wp_customize->add_section('my_clinic_hero_section', array(
        'title' => __('قسم البنر الرئيسي', 'my-clinic'),
        'priority' => 30,
    ));
    
    // Hero Card 1
    $wp_customize->add_setting('hero_card1_image', array(
        'default' => get_template_directory_uri() . '/assets/images/hero1.jpg',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_card1_image', array(
        'label' => __('صورة البطاقة الأولى', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'settings' => 'hero_card1_image',
    )));
    
    $wp_customize->add_setting('hero_card1_title', array(
        'default' => 'دورك الآن مضمون',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_card1_title', array(
        'label' => __('عنوان البطاقة الأولى', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('hero_card1_text', array(
        'default' => 'ودع الانتظار واحجز دورك في أفضل عيادات مع أفضل الأطباء',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('hero_card1_text', array(
        'label' => __('نص البطاقة الأولى', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'type' => 'textarea',
    ));
    
    // Hero Card 2
    $wp_customize->add_setting('hero_card2_image', array(
        'default' => get_template_directory_uri() . '/assets/images/hero2.jpg',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_card2_image', array(
        'label' => __('صورة البطاقة الثانية', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'settings' => 'hero_card2_image',
    )));
    
    $wp_customize->add_setting('hero_card2_title', array(
        'default' => 'دورك الآن مضمون',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_card2_title', array(
        'label' => __('عنوان البطاقة الثانية', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('hero_card2_text', array(
        'default' => 'ودع الانتظار واحجز دورك في أفضل عيادات مع أفضل أطباء',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('hero_card2_text', array(
        'label' => __('نص البطاقة الثانية', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'type' => 'textarea',
    ));
    
    // Hero Card 3
    $wp_customize->add_setting('hero_card3_image', array(
        'default' => get_template_directory_uri() . '/assets/images/hero3.jpg',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_card3_image', array(
        'label' => __('صورة البطاقة الثالثة', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'settings' => 'hero_card3_image',
    )));
    
    $wp_customize->add_setting('hero_card3_title', array(
        'default' => 'دورك الآن مضمون',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_card3_title', array(
        'label' => __('عنوان البطاقة الثالثة', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('hero_card3_text', array(
        'default' => 'ودع الانتظار واحجز دورك في أفضل عيادات مع أفضل أطباء',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('hero_card3_text', array(
        'label' => __('نص البطاقة الثالثة', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'type' => 'textarea',
    ));
    
    // Hero Card 4
    $wp_customize->add_setting('hero_card4_image', array(
        'default' => get_template_directory_uri() . '/assets/images/hero4.jpg',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'hero_card4_image', array(
        'label' => __('صورة البطاقة الرابعة', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'settings' => 'hero_card4_image',
    )));
    
    $wp_customize->add_setting('hero_card4_title', array(
        'default' => 'دورك الآن مضمون',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('hero_card4_title', array(
        'label' => __('عنوان البطاقة الرابعة', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('hero_card4_text', array(
        'default' => 'ودع الانتظار واحجز دورك في أفضل عيادات مع أفضل أطباء',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('hero_card4_text', array(
        'label' => __('نص البطاقة الرابعة', 'my-clinic'),
        'section' => 'my_clinic_hero_section',
        'type' => 'textarea',
    ));
    
    // Why Choose Us Section
    $wp_customize->add_section('my_clinic_why_section', array(
        'title' => __('قسم لماذا MY CLINIC', 'my-clinic'),
        'priority' => 31,
    ));
    
    $wp_customize->add_setting('why_section_title', array(
        'default' => 'لماذا MY CLINIC الاختيار الأمثل ؟',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('why_section_title', array(
        'label' => __('عنوان القسم', 'my-clinic'),
        'section' => 'my_clinic_why_section',
        'type' => 'text',
    ));
    
    // Feature Cards (4 cards)
    for ($i = 1; $i <= 4; $i++) {
        $wp_customize->add_setting('why_card' . $i . '_icon', array(
            'default' => get_template_directory_uri() . '/assets/images/way' . $i . '.png',
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'why_card' . $i . '_icon', array(
            'label' => __('أيقونة البطاقة ' . $i, 'my-clinic'),
            'section' => 'my_clinic_why_section',
            'settings' => 'why_card' . $i . '_icon',
        )));
        
        $wp_customize->add_setting('why_card' . $i . '_text', array(
            'default' => $i == 1 ? 'حجز سريع وسهل' : ($i == 2 ? 'تذكير بالمواعيد عبر الرسائل' : ($i == 3 ? 'تذكير بالمواعيد عبر الرسائل' : 'تقييمات حقيقية من المرضى')),
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('why_card' . $i . '_text', array(
            'label' => __('نص البطاقة ' . $i, 'my-clinic'),
            'section' => 'my_clinic_why_section',
            'type' => 'text',
        ));
    }
    
    // Categories Section
    $wp_customize->add_section('my_clinic_categories_section', array(
        'title' => __('قسم التخصصات', 'my-clinic'),
        'priority' => 32,
    ));
    
    // Default specialties with icons
    $default_specialties = array(
        'أطفال وحديثي الولادة' => 'baby.png',
        'أسنان' => 'tooth.png',
        'نفسي' => 'mind.png',
        'باطنة' => 'stomach.png',
        'نساء و توليد' => 'pregnant.png',
        'عيون' => 'eye.png',
        'مخ وأعصاب' => 'brain.png',
        'عظام' => 'bone.png',
        'جلدية' => 'hand.png',
        'صدر و جهاز تنفسي' => 'lungs.png',
        'أنف و أذن و حنجرة' => 'ear.png',
    );
    
    $specialty_index = 1;
    foreach ($default_specialties as $specialty_name => $default_icon) {
        $wp_customize->add_setting('specialty' . $specialty_index . '_name', array(
            'default' => $specialty_name,
            'sanitize_callback' => 'sanitize_text_field',
        ));
        $wp_customize->add_control('specialty' . $specialty_index . '_name', array(
            'label' => __('اسم التخصص ' . $specialty_index, 'my-clinic'),
            'section' => 'my_clinic_categories_section',
            'type' => 'text',
        ));
        
        $wp_customize->add_setting('specialty' . $specialty_index . '_icon', array(
            'default' => get_template_directory_uri() . '/assets/images/' . $default_icon,
            'sanitize_callback' => 'esc_url_raw',
        ));
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'specialty' . $specialty_index . '_icon', array(
            'label' => __('أيقونة التخصص ' . $specialty_index, 'my-clinic'),
            'section' => 'my_clinic_categories_section',
            'settings' => 'specialty' . $specialty_index . '_icon',
        )));
        
        $specialty_index++;
    }
    
    // Banner Section
    $wp_customize->add_section('my_clinic_banner_section', array(
        'title' => __('قسم البنر', 'my-clinic'),
        'priority' => 33,
    ));
    
    $wp_customize->add_setting('banner_image', array(
        'default' => get_template_directory_uri() . '/assets/images/panner.jpg',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'banner_image', array(
        'label' => __('صورة البنر', 'my-clinic'),
        'section' => 'my_clinic_banner_section',
        'settings' => 'banner_image',
    )));
    
    $wp_customize->add_setting('banner_title', array(
        'default' => 'احجز دكتورك بسهولة في أي وقت ومن أي مكان',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('banner_title', array(
        'label' => __('عنوان البنر', 'my-clinic'),
        'section' => 'my_clinic_banner_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('banner_text', array(
        'default' => 'ابحث عن أفضل الأطباء والعيادات في كل التخصصات بخطوات سريعة',
        'sanitize_callback' => 'sanitize_textarea_field',
    ));
    $wp_customize->add_control('banner_text', array(
        'label' => __('نص البنر', 'my-clinic'),
        'section' => 'my_clinic_banner_section',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('banner_button_text', array(
        'default' => 'ابحث عن دكتورك',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('banner_button_text', array(
        'label' => __('نص زر البنر', 'my-clinic'),
        'section' => 'my_clinic_banner_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('banner_button_link', array(
        'default' => home_url('/doctors'),
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('banner_button_link', array(
        'label' => __('رابط زر البنر', 'my-clinic'),
        'section' => 'my_clinic_banner_section',
        'type' => 'url',
    ));
    
    // About Us Section
    $wp_customize->add_section('my_clinic_about_us_section', array(
        'title' => __('صفحة من نحن', 'my-clinic'),
        'priority' => 34,
    ));
    
    // Contact Us Section
    $wp_customize->add_section('my_clinic_contact_us_section', array(
        'title' => __('صفحة تواصل معنا', 'my-clinic'),
        'priority' => 35,
    ));
    
    $wp_customize->add_setting('contact_phone', array(
        'default' => '+966 12 345 6789',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_phone', array(
        'label' => __('رقم الهاتف', 'my-clinic'),
        'section' => 'my_clinic_contact_us_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('contact_email', array(
        'default' => 'Customercare@myclinic.com',
        'sanitize_callback' => 'sanitize_email',
    ));
    $wp_customize->add_control('contact_email', array(
        'label' => __('البريد الإلكتروني', 'my-clinic'),
        'section' => 'my_clinic_contact_us_section',
        'type' => 'email',
    ));
    
    $wp_customize->add_setting('contact_address', array(
        'default' => 'الرياض , المملكة العربية السعودية',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('contact_address', array(
        'label' => __('العنوان', 'my-clinic'),
        'section' => 'my_clinic_contact_us_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('contact_map_link', array(
        'default' => 'https://maps.app.goo.gl/j9xwz9xwz9xwz9xwz9xwz9xw',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control('contact_map_link', array(
        'label' => __('رابط الخريطة', 'my-clinic'),
        'section' => 'my_clinic_contact_us_section',
        'type' => 'url',
    ));
    
    $wp_customize->add_setting('about_us_title', array(
        'default' => 'من نحن',
        'sanitize_callback' => 'sanitize_text_field',
    ));
    $wp_customize->add_control('about_us_title', array(
        'label' => __('عنوان الصفحة', 'my-clinic'),
        'section' => 'my_clinic_about_us_section',
        'type' => 'text',
    ));
    
    $wp_customize->add_setting('about_us_content', array(
        'default' => 'موقعنا هو منصّة متكاملة لحجز المواعيد الطبية بسهولة وسرعة. بنساعدك تلاقي أفضل الأطباء والعيادات في مختلف التخصصات، وتقارن بينهم حسب التقييمات والأسعار والموقع الجغرافي. هدفنا إننا نسهّل تجربة الرعاية الصحية ونخلّيها مريحة ومضمونة من أول خطوة للحجز لحد ما توصل للدكتور.

من خلال موقعنا، تقدر تحجز موعد، تستشير طبيب أونلاين، تعرف أقرب العيادات ليك، وتشوف تقييمات وتجارب المرضى الحقيقيين كل ده بخطوات بسيطة ومن مكانك.',
        'sanitize_callback' => 'wp_kses_post',
    ));
    $wp_customize->add_control('about_us_content', array(
        'label' => __('محتوى الصفحة', 'my-clinic'),
        'section' => 'my_clinic_about_us_section',
        'type' => 'textarea',
    ));
    
    $wp_customize->add_setting('about_us_image', array(
        'default' => get_template_directory_uri() . '/assets/images/about-us.png',
        'sanitize_callback' => 'esc_url_raw',
    ));
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'about_us_image', array(
        'label' => __('صورة الصفحة', 'my-clinic'),
        'section' => 'my_clinic_about_us_section',
        'settings' => 'about_us_image',
    )));
}
add_action('customize_register', 'my_clinic_customize_register');
