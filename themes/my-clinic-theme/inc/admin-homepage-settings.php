<?php
/**
 * Admin Page for Homepage Settings
 *
 * @package MyClinic
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Register Admin Menu for Homepage Settings
 */
function my_clinic_add_homepage_settings_menu() {
    add_submenu_page(
        'themes.php',
        __('إعدادات الصفحة الرئيسية', 'my-clinic'),
        __('إعدادات الصفحة الرئيسية', 'my-clinic'),
        'manage_options',
        'homepage-settings',
        'my_clinic_render_homepage_settings_page'
    );
}
add_action('admin_menu', 'my_clinic_add_homepage_settings_menu');

/**
 * Handle Reset Homepage Settings
 */
function my_clinic_handle_reset_homepage_settings() {
    if (isset($_POST['reset_homepage_settings']) && wp_verify_nonce($_POST['reset_homepage_settings_nonce'], 'reset_homepage_settings')) {
        // Reset Hero Section
        for ($i = 1; $i <= 4; $i++) {
            delete_option('hero_card' . $i . '_image');
            delete_option('hero_card' . $i . '_title');
            delete_option('hero_card' . $i . '_text');
        }
        
        // Reset Why Choose Us Section
        delete_option('why_section_title');
        for ($i = 1; $i <= 4; $i++) {
            delete_option('why_card' . $i . '_icon');
            delete_option('why_card' . $i . '_text');
        }
        
        // Reset Categories Section
        for ($i = 1; $i <= 11; $i++) {
            delete_option('specialty' . $i . '_name');
            delete_option('specialty' . $i . '_icon');
        }
        
        // Reset Banner Section
        delete_option('banner_image');
        delete_option('banner_title');
        delete_option('banner_text');
        delete_option('banner_button_text');
        delete_option('banner_button_link');
        
        return true;
    }
    return false;
}

/**
 * Render Homepage Settings Page
 */
function my_clinic_render_homepage_settings_page() {
    // Handle reset
    if (my_clinic_handle_reset_homepage_settings()) {
        echo '<div class="notice notice-success is-dismissible"><p>' . __('تم استعادة الإعدادات الافتراضية بنجاح', 'my-clinic') . '</p></div>';
    }
    
    // Handle form submission
    if (isset($_POST['save_homepage_settings']) && wp_verify_nonce($_POST['homepage_settings_nonce'], 'save_homepage_settings')) {
        // Hero Section Settings
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_POST['hero_card' . $i . '_image'])) {
                update_option('hero_card' . $i . '_image', esc_url_raw($_POST['hero_card' . $i . '_image']));
            }
            if (isset($_POST['hero_card' . $i . '_title'])) {
                update_option('hero_card' . $i . '_title', sanitize_text_field($_POST['hero_card' . $i . '_title']));
            }
            if (isset($_POST['hero_card' . $i . '_text'])) {
                update_option('hero_card' . $i . '_text', sanitize_textarea_field($_POST['hero_card' . $i . '_text']));
            }
        }
        
        // Why Choose Us Section
        if (isset($_POST['why_section_title'])) {
            update_option('why_section_title', sanitize_text_field($_POST['why_section_title']));
        }
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_POST['why_card' . $i . '_icon'])) {
                update_option('why_card' . $i . '_icon', esc_url_raw($_POST['why_card' . $i . '_icon']));
            }
            if (isset($_POST['why_card' . $i . '_text'])) {
                update_option('why_card' . $i . '_text', sanitize_text_field($_POST['why_card' . $i . '_text']));
            }
        }
        
        // Categories Section
        for ($i = 1; $i <= 11; $i++) {
            if (isset($_POST['specialty' . $i . '_name'])) {
                update_option('specialty' . $i . '_name', sanitize_text_field($_POST['specialty' . $i . '_name']));
            }
            if (isset($_POST['specialty' . $i . '_icon'])) {
                update_option('specialty' . $i . '_icon', esc_url_raw($_POST['specialty' . $i . '_icon']));
            }
        }
        
        // Banner Section
        if (isset($_POST['banner_image'])) {
            update_option('banner_image', esc_url_raw($_POST['banner_image']));
        }
        if (isset($_POST['banner_title'])) {
            update_option('banner_title', sanitize_text_field($_POST['banner_title']));
        }
        if (isset($_POST['banner_text'])) {
            update_option('banner_text', sanitize_textarea_field($_POST['banner_text']));
        }
        if (isset($_POST['banner_button_text'])) {
            update_option('banner_button_text', sanitize_text_field($_POST['banner_button_text']));
        }
        if (isset($_POST['banner_button_link'])) {
            update_option('banner_button_link', esc_url_raw($_POST['banner_button_link']));
        }
        
        echo '<div class="notice notice-success is-dismissible"><p>' . __('تم حفظ الإعدادات بنجاح', 'my-clinic') . '</p></div>';
    }
    
    // Enqueue media uploader
    wp_enqueue_media();
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <form method="POST" action="">
            <?php wp_nonce_field('save_homepage_settings', 'homepage_settings_nonce'); ?>
            
            <h2 class="nav-tab-wrapper">
                <a href="#hero-section" class="nav-tab nav-tab-active">قسم البنر الرئيسي</a>
                <a href="#why-section" class="nav-tab">قسم لماذا MY CLINIC</a>
                <a href="#categories-section" class="nav-tab">قسم التخصصات</a>
                <a href="#banner-section" class="nav-tab">قسم البنر</a>
            </h2>
            
            <!-- Hero Section -->
            <div id="hero-section" class="tab-content">
                <h2>قسم البنر الرئيسي</h2>
                <?php for ($i = 1; $i <= 4; $i++): ?>
                <div class="form-group" style="margin-bottom: 30px; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
                    <h3>البطاقة <?php echo $i; ?></h3>
                    <table class="form-table">
                        <tr>
                            <th><label for="hero_card<?php echo $i; ?>_image">صورة البطاقة</label></th>
                            <td>
                                <input type="text" id="hero_card<?php echo $i; ?>_image" name="hero_card<?php echo $i; ?>_image" value="<?php echo esc_attr(get_option('hero_card' . $i . '_image', get_template_directory_uri() . '/assets/images/hero' . $i . '.jpg')); ?>" class="regular-text" />
                                <button type="button" class="button upload-image-button" data-target="hero_card<?php echo $i; ?>_image">اختر صورة</button>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="hero_card<?php echo $i; ?>_title">عنوان البطاقة</label></th>
                            <td>
                                <input type="text" id="hero_card<?php echo $i; ?>_title" name="hero_card<?php echo $i; ?>_title" value="<?php echo esc_attr(get_option('hero_card' . $i . '_title', 'دورك الآن مضمون')); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="hero_card<?php echo $i; ?>_text">نص البطاقة</label></th>
                            <td>
                                <textarea id="hero_card<?php echo $i; ?>_text" name="hero_card<?php echo $i; ?>_text" rows="3" class="large-text"><?php echo esc_textarea(get_option('hero_card' . $i . '_text', 'ودع الانتظار واحجز دورك في أفضل عيادات مع أفضل الأطباء')); ?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php endfor; ?>
            </div>
            
            <!-- Why Choose Us Section -->
            <div id="why-section" class="tab-content" style="display: none;">
                <h2>قسم لماذا MY CLINIC</h2>
                <table class="form-table">
                    <tr>
                        <th><label for="why_section_title">عنوان القسم</label></th>
                        <td>
                            <input type="text" id="why_section_title" name="why_section_title" value="<?php echo esc_attr(get_option('why_section_title', 'لماذا MY CLINIC الاختيار الأمثل ؟')); ?>" class="regular-text" />
                        </td>
                    </tr>
                </table>
                <?php for ($i = 1; $i <= 4; $i++): 
                    $default_texts = array(
                        1 => 'حجز سريع وسهل',
                        2 => 'تذكير بالمواعيد عبر الرسائل',
                        3 => 'تذكير بالمواعيد عبر الرسائل',
                        4 => 'تقييمات حقيقية من المرضى'
                    );
                ?>
                <div class="form-group" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                    <h3>البطاقة <?php echo $i; ?></h3>
                    <table class="form-table">
                        <tr>
                            <th><label for="why_card<?php echo $i; ?>_icon">أيقونة البطاقة</label></th>
                            <td>
                                <input type="text" id="why_card<?php echo $i; ?>_icon" name="why_card<?php echo $i; ?>_icon" value="<?php echo esc_attr(get_option('why_card' . $i . '_icon', get_template_directory_uri() . '/assets/images/way' . $i . '.png')); ?>" class="regular-text" />
                                <button type="button" class="button upload-image-button" data-target="why_card<?php echo $i; ?>_icon">اختر صورة</button>
                            </td>
                        </tr>
                        <tr>
                            <th><label for="why_card<?php echo $i; ?>_text">نص البطاقة</label></th>
                            <td>
                                <input type="text" id="why_card<?php echo $i; ?>_text" name="why_card<?php echo $i; ?>_text" value="<?php echo esc_attr(get_option('why_card' . $i . '_text', $default_texts[$i])); ?>" class="regular-text" />
                            </td>
                        </tr>
                    </table>
                </div>
                <?php endfor; ?>
            </div>
            
            <!-- Categories Section -->
            <div id="categories-section" class="tab-content" style="display: none;">
                <h2>قسم التخصصات</h2>
                <?php 
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
                foreach ($default_specialties as $default_name => $default_icon):
                ?>
                <div class="form-group" style="margin-bottom: 15px; padding: 15px; border: 1px solid #ddd; border-radius: 5px;">
                    <h3>التخصص <?php echo $specialty_index; ?></h3>
                    <table class="form-table">
                        <tr>
                            <th><label for="specialty<?php echo $specialty_index; ?>_name">اسم التخصص</label></th>
                            <td>
                                <input type="text" id="specialty<?php echo $specialty_index; ?>_name" name="specialty<?php echo $specialty_index; ?>_name" value="<?php echo esc_attr(get_option('specialty' . $specialty_index . '_name', $default_name)); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th><label for="specialty<?php echo $specialty_index; ?>_icon">أيقونة التخصص</label></th>
                            <td>
                                <input type="text" id="specialty<?php echo $specialty_index; ?>_icon" name="specialty<?php echo $specialty_index; ?>_icon" value="<?php echo esc_attr(get_option('specialty' . $specialty_index . '_icon', get_template_directory_uri() . '/assets/images/' . $default_icon)); ?>" class="regular-text" />
                                <button type="button" class="button upload-image-button" data-target="specialty<?php echo $specialty_index; ?>_icon">اختر صورة</button>
                            </td>
                        </tr>
                    </table>
                </div>
                <?php 
                    $specialty_index++;
                endforeach; 
                ?>
            </div>
            
            <!-- Banner Section -->
            <div id="banner-section" class="tab-content" style="display: none;">
                <h2>قسم البنر</h2>
                <table class="form-table">
                    <tr>
                        <th><label for="banner_image">صورة البنر</label></th>
                        <td>
                            <input type="text" id="banner_image" name="banner_image" value="<?php echo esc_attr(get_option('banner_image', get_template_directory_uri() . '/assets/images/panner.jpg')); ?>" class="regular-text" />
                            <button type="button" class="button upload-image-button" data-target="banner_image">اختر صورة</button>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="banner_title">عنوان البنر</label></th>
                        <td>
                            <input type="text" id="banner_title" name="banner_title" value="<?php echo esc_attr(get_option('banner_title', 'احجز دكتورك بسهولة في أي وقت ومن أي مكان')); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th><label for="banner_text">نص البنر</label></th>
                        <td>
                            <textarea id="banner_text" name="banner_text" rows="3" class="large-text"><?php echo esc_textarea(get_option('banner_text', 'ابحث عن أفضل الأطباء والعيادات في كل التخصصات بخطوات سريعة')); ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="banner_button_text">نص زر البنر</label></th>
                        <td>
                            <input type="text" id="banner_button_text" name="banner_button_text" value="<?php echo esc_attr(get_option('banner_button_text', 'ابحث عن دكتورك')); ?>" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th><label for="banner_button_link">رابط زر البنر</label></th>
                        <td>
                            <input type="url" id="banner_button_link" name="banner_button_link" value="<?php echo esc_attr(get_option('banner_button_link', home_url('/doctors'))); ?>" class="regular-text" />
                        </td>
                    </tr>
                </table>
            </div>
            
            <p class="submit">
                <input type="submit" name="save_homepage_settings" class="button button-primary" value="<?php esc_attr_e('حفظ الإعدادات', 'my-clinic'); ?>" />
                <button type="button" id="reset-homepage-settings" class="button button-secondary" style="margin-right: 10px; background-color: #dc3232; color: white; border-color: #dc3232;">
                    <?php _e('استعادة المحتويات الافتراضية', 'my-clinic'); ?>
                </button>
            </p>
        </form>
        
        <!-- Reset Form (hidden) -->
        <form method="post" action="" id="reset-homepage-form" style="display: none;">
            <?php wp_nonce_field('reset_homepage_settings', 'reset_homepage_settings_nonce'); ?>
            <input type="hidden" name="reset_homepage_settings" value="1">
        </form>
    </div>
    
    <style>
        .tab-content {
            margin-top: 20px;
        }
        .form-group {
            background: #fff;
        }
        .nav-tab-wrapper {
            margin-bottom: 20px;
        }
    </style>
    
    <script>
    jQuery(document).ready(function($) {
        // Tab switching
        $('.nav-tab').on('click', function(e) {
            e.preventDefault();
            var target = $(this).attr('href');
            $('.nav-tab').removeClass('nav-tab-active');
            $(this).addClass('nav-tab-active');
            $('.tab-content').hide();
            $(target).show();
        });
        
        // Media uploader
        $('.upload-image-button').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var targetInput = $('#' + button.data('target'));
            
            var frame = wp.media({
                title: 'اختر صورة',
                button: {
                    text: 'استخدم هذه الصورة'
                },
                multiple: false
            });
            
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                targetInput.val(attachment.url);
            });
            
            frame.open();
        });
        
        // Reset homepage settings
        $('#reset-homepage-settings').on('click', function(e) {
            e.preventDefault();
            if (confirm('هل أنت متأكد من استعادة جميع محتويات الصفحة الرئيسية إلى القيم الافتراضية؟ سيتم حذف جميع المحتويات الحالية.')) {
                $('#reset-homepage-form').submit();
            }
        });
    });
    </script>
    <?php
}
