<?php
/**
 * Homepage Content Settings
 * 
 * Allows admin to manage homepage content from dashboard
 *
 * @package Nafhat
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get default homepage settings
 */
function nafhat_get_default_homepage_settings() {
    return array(
        // Hero Slider (up to 5 slides)
        'hero_slides' => array(
            array(
                'image' => get_template_directory_uri() . '/assets/images/hero1.png',
                'link' => '',
                'alt' => 'صورة رئيسية 1',
            ),
            array(
                'image' => get_template_directory_uri() . '/assets/images/hero2.png',
                'link' => '',
                'alt' => 'صورة رئيسية 2',
            ),
            array(
                'image' => get_template_directory_uri() . '/assets/images/hero3.png',
                'link' => '',
                'alt' => 'صورة رئيسية 3',
            ),
        ),
        // Secondary Banners (2 images)
        'secondary_banners' => array(
            array(
                'image' => get_template_directory_uri() . '/assets/images/banner1.jpg',
                'link' => '',
                'alt' => 'بانر إعلاني 1',
            ),
            array(
                'image' => get_template_directory_uri() . '/assets/images/banner 2.jpg',
                'link' => '',
                'alt' => 'بانر إعلاني 2',
            ),
        ),
        // Third Banner (1 image)
        'third_banner' => array(
            'image' => get_template_directory_uri() . '/assets/images/hero2.png',
            'link' => '',
            'alt' => 'بانر ثالث',
        ),
    );
}

/**
 * Get homepage settings
 */
function nafhat_get_homepage_settings() {
    $settings = get_option('nafhat_homepage_settings', array());
    $defaults = nafhat_get_default_homepage_settings();
    
    // Merge with defaults
    if (empty($settings)) {
        return $defaults;
    }
    
    return wp_parse_args($settings, $defaults);
}

/**
 * Add admin menu page
 */
function nafhat_add_homepage_settings_menu() {
    add_theme_page(
        __('محتوى الرئيسية', 'nafhat'),
        __('محتوى الرئيسية', 'nafhat'),
        'manage_options',
        'nafhat-homepage-settings',
        'nafhat_homepage_settings_page'
    );
}
add_action('admin_menu', 'nafhat_add_homepage_settings_menu');

/**
 * Enqueue admin scripts and styles
 */
function nafhat_homepage_admin_scripts($hook) {
    if ($hook !== 'appearance_page_nafhat-homepage-settings') {
        return;
    }
    
    // Enqueue WordPress media uploader
    wp_enqueue_media();
    
    // Enqueue custom admin styles
    wp_add_inline_style('wp-admin', '
        .nafhat-homepage-settings {
            max-width: 1200px;
            margin: 20px auto;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .nafhat-homepage-settings h1 {
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid #b88494;
            color: #333;
        }
        .nafhat-section {
            margin-bottom: 40px;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
            border-right: 4px solid #b88494;
        }
        .nafhat-section h2 {
            margin-top: 0;
            color: #b88494;
            font-size: 18px;
        }
        .nafhat-section p.description {
            color: #666;
            margin-bottom: 20px;
        }
        .nafhat-slides-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .nafhat-slide-item {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        .nafhat-slide-item h4 {
            margin-top: 0;
            color: #333;
        }
        .nafhat-image-preview {
            width: 100%;
            height: 150px;
            background: #f0f0f0;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            overflow: hidden;
        }
        .nafhat-image-preview img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .nafhat-image-preview .no-image {
            color: #999;
            font-size: 14px;
        }
        .nafhat-slide-item input[type="text"],
        .nafhat-slide-item input[type="url"] {
            width: 100%;
            margin-bottom: 10px;
        }
        .nafhat-slide-item .button {
            margin-left: 5px;
        }
        .nafhat-buttons-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        .nafhat-reset-section {
            background: #fff3cd;
            border-color: #ffc107;
            padding: 20px;
            border-radius: 8px;
            margin-top: 30px;
        }
        .nafhat-reset-section h3 {
            margin-top: 0;
            color: #856404;
        }
        .nafhat-reset-section p {
            color: #856404;
        }
        .nafhat-reset-btn {
            background: #dc3545 !important;
            border-color: #dc3545 !important;
            color: #fff !important;
        }
        .nafhat-reset-btn:hover {
            background: #c82333 !important;
            border-color: #bd2130 !important;
        }
        .nafhat-save-btn {
            background: #b88494 !important;
            border-color: #b88494 !important;
            color: #fff !important;
            font-size: 16px !important;
            padding: 10px 30px !important;
            height: auto !important;
        }
        .nafhat-save-btn:hover {
            background: #a06e7e !important;
            border-color: #a06e7e !important;
        }
        .nafhat-success-message {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
    ');
    
    // Enqueue custom admin script
    wp_add_inline_script('jquery', '
        jQuery(document).ready(function($) {
            // Media uploader
            $(".nafhat-upload-btn").on("click", function(e) {
                e.preventDefault();
                var button = $(this);
                var inputField = button.siblings("input[type=hidden]");
                var preview = button.closest(".nafhat-slide-item").find(".nafhat-image-preview");
                
                var mediaUploader = wp.media({
                    title: "اختر صورة",
                    button: { text: "استخدم هذه الصورة" },
                    multiple: false
                });
                
                mediaUploader.on("select", function() {
                    var attachment = mediaUploader.state().get("selection").first().toJSON();
                    inputField.val(attachment.url);
                    preview.html("<img src=\"" + attachment.url + "\" />");
                });
                
                mediaUploader.open();
            });
            
            // Remove image
            $(".nafhat-remove-btn").on("click", function(e) {
                e.preventDefault();
                var button = $(this);
                var inputField = button.siblings("input[type=hidden]");
                var preview = button.closest(".nafhat-slide-item").find(".nafhat-image-preview");
                
                inputField.val("");
                preview.html("<span class=\"no-image\">لا توجد صورة</span>");
            });
            
            // Reset to defaults confirmation
            $(".nafhat-reset-btn").on("click", function(e) {
                if (!confirm("هل أنت متأكد من إعادة التعيين للقيم الافتراضية؟ سيتم حذف جميع التغييرات.")) {
                    e.preventDefault();
                }
            });
        });
    ');
}
add_action('admin_enqueue_scripts', 'nafhat_homepage_admin_scripts');

/**
 * Homepage settings page callback
 */
function nafhat_homepage_settings_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Handle form submission
    if (isset($_POST['nafhat_homepage_save']) && check_admin_referer('nafhat_homepage_settings_nonce')) {
        $settings = array(
            'hero_slides' => array(),
            'secondary_banners' => array(),
            'third_banner' => array(),
        );
        
        // Save hero slides
        for ($i = 0; $i < 5; $i++) {
            if (!empty($_POST['hero_slide_image_' . $i])) {
                $settings['hero_slides'][] = array(
                    'image' => esc_url_raw($_POST['hero_slide_image_' . $i]),
                    'link' => esc_url_raw($_POST['hero_slide_link_' . $i] ?? ''),
                    'alt' => sanitize_text_field($_POST['hero_slide_alt_' . $i] ?? ''),
                );
            }
        }
        
        // Save secondary banners
        for ($i = 0; $i < 2; $i++) {
            if (!empty($_POST['secondary_banner_image_' . $i])) {
                $settings['secondary_banners'][] = array(
                    'image' => esc_url_raw($_POST['secondary_banner_image_' . $i]),
                    'link' => esc_url_raw($_POST['secondary_banner_link_' . $i] ?? ''),
                    'alt' => sanitize_text_field($_POST['secondary_banner_alt_' . $i] ?? ''),
                );
            }
        }
        
        // Save third banner
        if (!empty($_POST['third_banner_image'])) {
            $settings['third_banner'] = array(
                'image' => esc_url_raw($_POST['third_banner_image']),
                'link' => esc_url_raw($_POST['third_banner_link'] ?? ''),
                'alt' => sanitize_text_field($_POST['third_banner_alt'] ?? ''),
            );
        }
        
        update_option('nafhat_homepage_settings', $settings);
        echo '<div class="nafhat-success-message">' . __('تم حفظ الإعدادات بنجاح!', 'nafhat') . '</div>';
    }
    
    // Handle reset to defaults
    if (isset($_POST['nafhat_homepage_reset']) && check_admin_referer('nafhat_homepage_settings_nonce')) {
        delete_option('nafhat_homepage_settings');
        echo '<div class="nafhat-success-message">' . __('تم إعادة التعيين للقيم الافتراضية!', 'nafhat') . '</div>';
    }
    
    // Get current settings
    $settings = nafhat_get_homepage_settings();
    $defaults = nafhat_get_default_homepage_settings();
    ?>
    <div class="wrap nafhat-homepage-settings">
        <h1><?php esc_html_e('محتوى الصفحة الرئيسية', 'nafhat'); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('nafhat_homepage_settings_nonce'); ?>
            
            <!-- Hero Slider Section -->
            <div class="nafhat-section">
                <h2><span class="dashicons dashicons-images-alt2"></span> <?php esc_html_e('سلايدر الهيرو', 'nafhat'); ?></h2>
                <p class="description"><?php esc_html_e('أضف حتى 5 صور للسلايدر الرئيسي في أعلى الصفحة. يمكنك إضافة رابط لكل صورة.', 'nafhat'); ?></p>
                
                <div class="nafhat-slides-container">
                    <?php for ($i = 0; $i < 5; $i++) : 
                        $slide = isset($settings['hero_slides'][$i]) ? $settings['hero_slides'][$i] : array('image' => '', 'link' => '', 'alt' => '');
                    ?>
                    <div class="nafhat-slide-item">
                        <h4><?php printf(__('الشريحة %d', 'nafhat'), $i + 1); ?></h4>
                        <div class="nafhat-image-preview">
                            <?php if (!empty($slide['image'])) : ?>
                                <img src="<?php echo esc_url($slide['image']); ?>" />
                            <?php else : ?>
                                <span class="no-image"><?php esc_html_e('لا توجد صورة', 'nafhat'); ?></span>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" name="hero_slide_image_<?php echo $i; ?>" value="<?php echo esc_attr($slide['image']); ?>" />
                        <div class="nafhat-buttons-row">
                            <button type="button" class="button nafhat-upload-btn"><?php esc_html_e('اختر صورة', 'nafhat'); ?></button>
                            <button type="button" class="button nafhat-remove-btn"><?php esc_html_e('إزالة', 'nafhat'); ?></button>
                        </div>
                        <input type="url" name="hero_slide_link_<?php echo $i; ?>" value="<?php echo esc_attr($slide['link']); ?>" placeholder="<?php esc_attr_e('رابط الصورة (اختياري)', 'nafhat'); ?>" />
                        <input type="text" name="hero_slide_alt_<?php echo $i; ?>" value="<?php echo esc_attr($slide['alt']); ?>" placeholder="<?php esc_attr_e('النص البديل', 'nafhat'); ?>" />
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <!-- Secondary Banners Section -->
            <div class="nafhat-section">
                <h2><span class="dashicons dashicons-format-image"></span> <?php esc_html_e('البانرات الثانوية', 'nafhat'); ?></h2>
                <p class="description"><?php esc_html_e('أضف صورتين للبانرات الثانوية التي تظهر بعد قسم الأكثر مبيعاً.', 'nafhat'); ?></p>
                
                <div class="nafhat-slides-container">
                    <?php for ($i = 0; $i < 2; $i++) : 
                        $banner = isset($settings['secondary_banners'][$i]) ? $settings['secondary_banners'][$i] : array('image' => '', 'link' => '', 'alt' => '');
                    ?>
                    <div class="nafhat-slide-item">
                        <h4><?php printf(__('البانر %d', 'nafhat'), $i + 1); ?></h4>
                        <div class="nafhat-image-preview">
                            <?php if (!empty($banner['image'])) : ?>
                                <img src="<?php echo esc_url($banner['image']); ?>" />
                            <?php else : ?>
                                <span class="no-image"><?php esc_html_e('لا توجد صورة', 'nafhat'); ?></span>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" name="secondary_banner_image_<?php echo $i; ?>" value="<?php echo esc_attr($banner['image']); ?>" />
                        <div class="nafhat-buttons-row">
                            <button type="button" class="button nafhat-upload-btn"><?php esc_html_e('اختر صورة', 'nafhat'); ?></button>
                            <button type="button" class="button nafhat-remove-btn"><?php esc_html_e('إزالة', 'nafhat'); ?></button>
                        </div>
                        <input type="url" name="secondary_banner_link_<?php echo $i; ?>" value="<?php echo esc_attr($banner['link']); ?>" placeholder="<?php esc_attr_e('رابط البانر (اختياري)', 'nafhat'); ?>" />
                        <input type="text" name="secondary_banner_alt_<?php echo $i; ?>" value="<?php echo esc_attr($banner['alt']); ?>" placeholder="<?php esc_attr_e('النص البديل', 'nafhat'); ?>" />
                    </div>
                    <?php endfor; ?>
                </div>
            </div>
            
            <!-- Third Banner Section -->
            <div class="nafhat-section">
                <h2><span class="dashicons dashicons-cover-image"></span> <?php esc_html_e('البانر الثالث', 'nafhat'); ?></h2>
                <p class="description"><?php esc_html_e('أضف صورة للبانر الثالث الذي يظهر بعد قسم وصل حديثاً.', 'nafhat'); ?></p>
                
                <div class="nafhat-slides-container">
                    <?php 
                    $third_banner = isset($settings['third_banner']) ? $settings['third_banner'] : array('image' => '', 'link' => '', 'alt' => '');
                    ?>
                    <div class="nafhat-slide-item">
                        <h4><?php esc_html_e('البانر الثالث', 'nafhat'); ?></h4>
                        <div class="nafhat-image-preview">
                            <?php if (!empty($third_banner['image'])) : ?>
                                <img src="<?php echo esc_url($third_banner['image']); ?>" />
                            <?php else : ?>
                                <span class="no-image"><?php esc_html_e('لا توجد صورة', 'nafhat'); ?></span>
                            <?php endif; ?>
                        </div>
                        <input type="hidden" name="third_banner_image" value="<?php echo esc_attr($third_banner['image']); ?>" />
                        <div class="nafhat-buttons-row">
                            <button type="button" class="button nafhat-upload-btn"><?php esc_html_e('اختر صورة', 'nafhat'); ?></button>
                            <button type="button" class="button nafhat-remove-btn"><?php esc_html_e('إزالة', 'nafhat'); ?></button>
                        </div>
                        <input type="url" name="third_banner_link" value="<?php echo esc_attr($third_banner['link']); ?>" placeholder="<?php esc_attr_e('رابط البانر (اختياري)', 'nafhat'); ?>" />
                        <input type="text" name="third_banner_alt" value="<?php echo esc_attr($third_banner['alt']); ?>" placeholder="<?php esc_attr_e('النص البديل', 'nafhat'); ?>" />
                    </div>
                </div>
            </div>
            
            <!-- Save Button -->
            <p>
                <button type="submit" name="nafhat_homepage_save" class="button button-primary nafhat-save-btn">
                    <span class="dashicons dashicons-saved" style="margin-top: 4px;"></span>
                    <?php esc_html_e('حفظ التغييرات', 'nafhat'); ?>
                </button>
            </p>
            
            <!-- Reset Section -->
            <div class="nafhat-reset-section">
                <h3><span class="dashicons dashicons-image-rotate"></span> <?php esc_html_e('إعادة التعيين', 'nafhat'); ?></h3>
                <p><?php esc_html_e('انقر على الزر أدناه لإعادة تعيين جميع الإعدادات إلى القيم الافتراضية. سيتم حذف جميع التغييرات التي أجريتها.', 'nafhat'); ?></p>
                <button type="submit" name="nafhat_homepage_reset" class="button nafhat-reset-btn">
                    <span class="dashicons dashicons-image-rotate" style="margin-top: 4px;"></span>
                    <?php esc_html_e('إعادة التعيين للقيم الافتراضية', 'nafhat'); ?>
                </button>
            </div>
        </form>
    </div>
    <?php
}
