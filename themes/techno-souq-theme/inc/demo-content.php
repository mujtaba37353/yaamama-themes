<?php
/**
 * Demo Content Management
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get demo content with default fallback
 */
function techno_souq_get_demo_content($key, $default = '') {
    $content = get_option('techno_souq_demo_content_' . $key, '');
    return !empty($content) ? $content : $default;
}

/**
 * Save demo content
 */
function techno_souq_save_demo_content($key, $value) {
    update_option('techno_souq_demo_content_' . $key, $value);
}

/**
 * Get default demo content
 */
function techno_souq_get_default_demo_content() {
    return array(
        'hero_image' => techno_souq_asset_url('hero-image.png'),
        'slider_1_image' => techno_souq_asset_url('phone-slider.png'),
        'slider_1_title' => 'أحدث الأجهزة',
        'slider_1_text' => 'الجيل الجديد من الأجهزة بين يديك الآن',
        'slider_2_image' => techno_souq_asset_url('phone-slider.png'),
        'slider_2_title' => 'أحدث الأجهزة',
        'slider_2_text' => 'الجيل الجديد من الأجهزة بين يديك الآن',
        'slider_3_image' => techno_souq_asset_url('phone-slider.png'),
        'slider_3_title' => 'أحدث الأجهزة',
        'slider_3_text' => 'الجيل الجديد من الأجهزة بين يديك الآن',
        'cta_product_id' => 0,
        'cta_title' => 'مكنسة كهربائية عمودية لاسلكية U10 من دريم',
        'cta_description' => 'شفط قوي 19000 باسكال، رأس فرشاة دوار 180 درجة، خفيفة الوزن مثبتة على الحائط مع ضوء LED للارضيات .',
        'cta_button_text' => 'اشتري الآن',
        'feature_1_text' => 'سهلة الاستخدام',
        'feature_2_text' => 'سهلة الاستخدام',
        'feature_3_text' => 'سهلة الاستخدام',
        'feature_4_text' => 'نظام ترشيح فعال',
        // About Us content
        'about_title' => 'من نحن',
        'about_paragraph_1' => 'نحن في [اسم البراند] نؤمن أن الجمال يبدأ من التفاصيل الصغيرة. منذ انطلاقنا في عام [سنة التأسيس] ونحن نقدم لعملائنا أجود أنواع العطور، منتجات المكياج، وحلول العناية الشخصية التي تجمع بين الفخامة والجودة.',
        'about_paragraph_2' => 'رؤيتنا هي أن نكون الوجهة الأولى لكل من يبحث عن منتجات أصلية وموثوقة تضيف لمسة خاصة لحياته اليومية.',
        'about_paragraph_3' => 'نحن ملتزمون بتقديم تجربة تسوق سلسة، دعم عملاء مميز، وأسعار تناسب الجميع. اكتشف مجموعتنا اليوم ودعنا نكون جزءًا من روتينك الجمالي.',
        'vision_title' => 'رؤيتنـــــا',
        'vision_text' => 'أن نكون الوجهة الأولى لكل من يبحث عن منتجات أصلية وموثوقة تضيف لمسة خاصة لحياته اليومية.',
        'values_title' => 'قيمنـــــا',
        'values_item_1' => '1- الجودة والأصالة: نحرص على توفير منتجات أصلية ومضمونة.',
        'values_item_2' => '2- تجربة تسوق مميزة: واجهة سهلة ودعم عملاء سريع.',
        'values_item_3' => '3- لمسة فخامة بأسعار مناسبة: نمنح عملاءنا أفضل قيمة مقابل السعر.',
        'values_item_4' => '4- اكتشفي مجموعتنا الآن ودعينا نكون جزءًا من روتينك الجمالي اليومي.',
    );
}

/**
 * Reset demo content to defaults
 */
function techno_souq_reset_demo_content() {
    $defaults = techno_souq_get_default_demo_content();
    foreach ($defaults as $key => $value) {
        delete_option('techno_souq_demo_content_' . $key);
    }
}

/**
 * Add Demo Content menu page
 */
function techno_souq_add_demo_content_menu() {
    add_theme_page(
        __('محتوى ديمو', 'techno-souq-theme'),
        __('محتوى ديمو', 'techno-souq-theme'),
        'edit_theme_options',
        'techno-souq-demo-content',
        'techno_souq_demo_content_page'
    );
}
add_action('admin_menu', 'techno_souq_add_demo_content_menu');

/**
 * Demo Content Admin Page
 */
function techno_souq_demo_content_page() {
    // Handle form submission
    if (isset($_POST['techno_souq_save_demo_content']) && check_admin_referer('techno_souq_demo_content_nonce', 'techno_souq_demo_content_nonce')) {
        // Save hero image
        if (isset($_POST['hero_image'])) {
            techno_souq_save_demo_content('hero_image', esc_url_raw($_POST['hero_image']));
        }
        
        // Save slider content
        for ($i = 1; $i <= 3; $i++) {
            if (isset($_POST["slider_{$i}_image"])) {
                techno_souq_save_demo_content("slider_{$i}_image", esc_url_raw($_POST["slider_{$i}_image"]));
            }
            if (isset($_POST["slider_{$i}_title"])) {
                techno_souq_save_demo_content("slider_{$i}_title", sanitize_text_field($_POST["slider_{$i}_title"]));
            }
            if (isset($_POST["slider_{$i}_text"])) {
                techno_souq_save_demo_content("slider_{$i}_text", sanitize_textarea_field($_POST["slider_{$i}_text"]));
            }
        }
        
        // Save CTA content
        if (isset($_POST['cta_product_id'])) {
            techno_souq_save_demo_content('cta_product_id', intval($_POST['cta_product_id']));
        }
        if (isset($_POST['cta_title'])) {
            techno_souq_save_demo_content('cta_title', sanitize_text_field($_POST['cta_title']));
        }
        if (isset($_POST['cta_description'])) {
            techno_souq_save_demo_content('cta_description', sanitize_textarea_field($_POST['cta_description']));
        }
        if (isset($_POST['cta_button_text'])) {
            techno_souq_save_demo_content('cta_button_text', sanitize_text_field($_POST['cta_button_text']));
        }
        
        // Save features
        for ($i = 1; $i <= 4; $i++) {
            if (isset($_POST["feature_{$i}_text"])) {
                techno_souq_save_demo_content("feature_{$i}_text", sanitize_text_field($_POST["feature_{$i}_text"]));
            }
        }
        
        // Save About Us content
        if (isset($_POST['about_title'])) {
            techno_souq_save_demo_content('about_title', sanitize_text_field($_POST['about_title']));
        }
        if (isset($_POST['about_paragraph_1'])) {
            techno_souq_save_demo_content('about_paragraph_1', wp_kses_post($_POST['about_paragraph_1']));
        }
        if (isset($_POST['about_paragraph_2'])) {
            techno_souq_save_demo_content('about_paragraph_2', wp_kses_post($_POST['about_paragraph_2']));
        }
        if (isset($_POST['about_paragraph_3'])) {
            techno_souq_save_demo_content('about_paragraph_3', wp_kses_post($_POST['about_paragraph_3']));
        }
        if (isset($_POST['vision_title'])) {
            techno_souq_save_demo_content('vision_title', sanitize_text_field($_POST['vision_title']));
        }
        if (isset($_POST['vision_text'])) {
            techno_souq_save_demo_content('vision_text', wp_kses_post($_POST['vision_text']));
        }
        if (isset($_POST['values_title'])) {
            techno_souq_save_demo_content('values_title', sanitize_text_field($_POST['values_title']));
        }
        if (isset($_POST['values_item_1'])) {
            techno_souq_save_demo_content('values_item_1', sanitize_text_field($_POST['values_item_1']));
        }
        if (isset($_POST['values_item_2'])) {
            techno_souq_save_demo_content('values_item_2', sanitize_text_field($_POST['values_item_2']));
        }
        if (isset($_POST['values_item_3'])) {
            techno_souq_save_demo_content('values_item_3', sanitize_text_field($_POST['values_item_3']));
        }
        if (isset($_POST['values_item_4'])) {
            techno_souq_save_demo_content('values_item_4', sanitize_text_field($_POST['values_item_4']));
        }
        
        echo '<div class="notice notice-success"><p>' . __('تم حفظ المحتوى بنجاح', 'techno-souq-theme') . '</p></div>';
    }
    
    // Handle reset
    if (isset($_POST['techno_souq_reset_demo_content']) && check_admin_referer('techno_souq_reset_demo_content_nonce', 'techno_souq_reset_demo_content_nonce')) {
        techno_souq_reset_demo_content();
        echo '<div class="notice notice-success"><p>' . __('تم إعادة تعيين المحتوى إلى القيم الافتراضية', 'techno-souq-theme') . '</p></div>';
    }
    
    // Get current values
    $defaults = techno_souq_get_default_demo_content();
    $hero_image = techno_souq_get_demo_content('hero_image', $defaults['hero_image']);
    $cta_product_id = techno_souq_get_demo_content('cta_product_id', $defaults['cta_product_id']);
    
    // Get products for CTA dropdown
    $products = wc_get_products(array('limit' => -1, 'status' => 'publish'));
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('محتوى ديمو', 'techno-souq-theme'); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('techno_souq_demo_content_nonce', 'techno_souq_demo_content_nonce'); ?>
            
            <h2><?php echo esc_html__('صورة البطل (Hero Section)', 'techno-souq-theme'); ?></h2>
            <table class="form-table">
                <tr>
                    <th><label for="hero_image"><?php echo esc_html__('رابط الصورة', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="url" id="hero_image" name="hero_image" value="<?php echo esc_attr($hero_image); ?>" class="regular-text" />
                        <button type="button" class="button" id="hero_image_upload"><?php echo esc_html__('اختر صورة', 'techno-souq-theme'); ?></button>
                    </td>
                </tr>
            </table>
            
            <h2><?php echo esc_html__('محتوى السلايدر', 'techno-souq-theme'); ?></h2>
            <?php for ($i = 1; $i <= 3; $i++) : 
                $slider_image = techno_souq_get_demo_content("slider_{$i}_image", $defaults["slider_{$i}_image"]);
                $slider_title = techno_souq_get_demo_content("slider_{$i}_title", $defaults["slider_{$i}_title"]);
                $slider_text = techno_souq_get_demo_content("slider_{$i}_text", $defaults["slider_{$i}_text"]);
            ?>
            <h3><?php echo esc_html__('سلايد', 'techno-souq-theme') . ' ' . $i; ?></h3>
            <table class="form-table">
                <tr>
                    <th><label for="slider_<?php echo $i; ?>_image"><?php echo esc_html__('رابط الصورة', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <input type="url" id="slider_<?php echo $i; ?>_image" name="slider_<?php echo $i; ?>_image" value="<?php echo esc_attr($slider_image); ?>" class="regular-text" />
                        <button type="button" class="button slider_image_upload" data-target="slider_<?php echo $i; ?>_image"><?php echo esc_html__('اختر صورة', 'techno-souq-theme'); ?></button>
                    </td>
                </tr>
                <tr>
                    <th><label for="slider_<?php echo $i; ?>_title"><?php echo esc_html__('العنوان', 'techno-souq-theme'); ?></label></th>
                    <td><input type="text" id="slider_<?php echo $i; ?>_title" name="slider_<?php echo $i; ?>_title" value="<?php echo esc_attr($slider_title); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="slider_<?php echo $i; ?>_text"><?php echo esc_html__('النص', 'techno-souq-theme'); ?></label></th>
                    <td><textarea id="slider_<?php echo $i; ?>_text" name="slider_<?php echo $i; ?>_text" class="large-text" rows="3"><?php echo esc_textarea($slider_text); ?></textarea></td>
                </tr>
            </table>
            <?php endfor; ?>
            
            <h2><?php echo esc_html__('قسم الدعوة للعمل (CTA Section)', 'techno-souq-theme'); ?></h2>
            <table class="form-table">
                <tr>
                    <th><label for="cta_product_id"><?php echo esc_html__('اختر منتج', 'techno-souq-theme'); ?></label></th>
                    <td>
                        <select id="cta_product_id" name="cta_product_id">
                            <option value="0"><?php echo esc_html__('-- اختر منتج --', 'techno-souq-theme'); ?></option>
                            <?php foreach ($products as $product) : ?>
                                <option value="<?php echo esc_attr($product->get_id()); ?>" <?php selected($cta_product_id, $product->get_id()); ?>>
                                    <?php echo esc_html($product->get_name()); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th><label for="cta_title"><?php echo esc_html__('العنوان', 'techno-souq-theme'); ?></label></th>
                    <td><input type="text" id="cta_title" name="cta_title" value="<?php echo esc_attr(techno_souq_get_demo_content('cta_title', $defaults['cta_title'])); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="cta_description"><?php echo esc_html__('الوصف', 'techno-souq-theme'); ?></label></th>
                    <td><textarea id="cta_description" name="cta_description" class="large-text" rows="3"><?php echo esc_textarea(techno_souq_get_demo_content('cta_description', $defaults['cta_description'])); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="cta_button_text"><?php echo esc_html__('نص الزر', 'techno-souq-theme'); ?></label></th>
                    <td><input type="text" id="cta_button_text" name="cta_button_text" value="<?php echo esc_attr(techno_souq_get_demo_content('cta_button_text', $defaults['cta_button_text'])); ?>" class="regular-text" /></td>
                </tr>
            </table>
            
            <h2><?php echo esc_html__('المميزات (Features)', 'techno-souq-theme'); ?></h2>
            <table class="form-table">
                <?php for ($i = 1; $i <= 4; $i++) : 
                    $feature_text = techno_souq_get_demo_content("feature_{$i}_text", $defaults["feature_{$i}_text"]);
                ?>
                <tr>
                    <th><label for="feature_<?php echo $i; ?>_text"><?php echo esc_html__('ميزة', 'techno-souq-theme') . ' ' . $i; ?></label></th>
                    <td><input type="text" id="feature_<?php echo $i; ?>_text" name="feature_<?php echo $i; ?>_text" value="<?php echo esc_attr($feature_text); ?>" class="regular-text" /></td>
                </tr>
                <?php endfor; ?>
            </table>
            
            <h2><?php echo esc_html__('صفحة من نحن', 'techno-souq-theme'); ?></h2>
            <table class="form-table">
                <tr>
                    <th><label for="about_title"><?php echo esc_html__('عنوان القسم الرئيسي', 'techno-souq-theme'); ?></label></th>
                    <td><input type="text" id="about_title" name="about_title" value="<?php echo esc_attr(techno_souq_get_demo_content('about_title', $defaults['about_title'])); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="about_paragraph_1"><?php echo esc_html__('الفقرة الأولى', 'techno-souq-theme'); ?></label></th>
                    <td><textarea id="about_paragraph_1" name="about_paragraph_1" class="large-text" rows="4"><?php echo esc_textarea(techno_souq_get_demo_content('about_paragraph_1', $defaults['about_paragraph_1'])); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="about_paragraph_2"><?php echo esc_html__('الفقرة الثانية', 'techno-souq-theme'); ?></label></th>
                    <td><textarea id="about_paragraph_2" name="about_paragraph_2" class="large-text" rows="3"><?php echo esc_textarea(techno_souq_get_demo_content('about_paragraph_2', $defaults['about_paragraph_2'])); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="about_paragraph_3"><?php echo esc_html__('الفقرة الثالثة', 'techno-souq-theme'); ?></label></th>
                    <td><textarea id="about_paragraph_3" name="about_paragraph_3" class="large-text" rows="3"><?php echo esc_textarea(techno_souq_get_demo_content('about_paragraph_3', $defaults['about_paragraph_3'])); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="vision_title"><?php echo esc_html__('عنوان قسم الرؤية', 'techno-souq-theme'); ?></label></th>
                    <td><input type="text" id="vision_title" name="vision_title" value="<?php echo esc_attr(techno_souq_get_demo_content('vision_title', $defaults['vision_title'])); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="vision_text"><?php echo esc_html__('نص الرؤية', 'techno-souq-theme'); ?></label></th>
                    <td><textarea id="vision_text" name="vision_text" class="large-text" rows="3"><?php echo esc_textarea(techno_souq_get_demo_content('vision_text', $defaults['vision_text'])); ?></textarea></td>
                </tr>
                <tr>
                    <th><label for="values_title"><?php echo esc_html__('عنوان قسم القيم', 'techno-souq-theme'); ?></label></th>
                    <td><input type="text" id="values_title" name="values_title" value="<?php echo esc_attr(techno_souq_get_demo_content('values_title', $defaults['values_title'])); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="values_item_1"><?php echo esc_html__('القيمة الأولى', 'techno-souq-theme'); ?></label></th>
                    <td><input type="text" id="values_item_1" name="values_item_1" value="<?php echo esc_attr(techno_souq_get_demo_content('values_item_1', $defaults['values_item_1'])); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="values_item_2"><?php echo esc_html__('القيمة الثانية', 'techno-souq-theme'); ?></label></th>
                    <td><input type="text" id="values_item_2" name="values_item_2" value="<?php echo esc_attr(techno_souq_get_demo_content('values_item_2', $defaults['values_item_2'])); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="values_item_3"><?php echo esc_html__('القيمة الثالثة', 'techno-souq-theme'); ?></label></th>
                    <td><input type="text" id="values_item_3" name="values_item_3" value="<?php echo esc_attr(techno_souq_get_demo_content('values_item_3', $defaults['values_item_3'])); ?>" class="regular-text" /></td>
                </tr>
                <tr>
                    <th><label for="values_item_4"><?php echo esc_html__('القيمة الرابعة', 'techno-souq-theme'); ?></label></th>
                    <td><input type="text" id="values_item_4" name="values_item_4" value="<?php echo esc_attr(techno_souq_get_demo_content('values_item_4', $defaults['values_item_4'])); ?>" class="regular-text" /></td>
                </tr>
            </table>
            
            <?php submit_button(__('حفظ المحتوى', 'techno-souq-theme'), 'primary', 'techno_souq_save_demo_content'); ?>
        </form>
        
        <hr>
        
        <form method="post" action="" onsubmit="return confirm('<?php echo esc_js(__('هل أنت متأكد من إعادة تعيين المحتوى إلى القيم الافتراضية؟', 'techno-souq-theme')); ?>');">
            <?php wp_nonce_field('techno_souq_reset_demo_content_nonce', 'techno_souq_reset_demo_content_nonce'); ?>
            <?php submit_button(__('إعادة تعيين إلى القيم الافتراضية', 'techno-souq-theme'), 'secondary', 'techno_souq_reset_demo_content'); ?>
        </form>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Media uploader for hero image
        $('#hero_image_upload').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var input = $('#hero_image');
            var frame = wp.media({
                title: '<?php echo esc_js(__('اختر صورة', 'techno-souq-theme')); ?>',
                button: { text: '<?php echo esc_js(__('استخدم هذه الصورة', 'techno-souq-theme')); ?>' },
                multiple: false
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                input.val(attachment.url);
            });
            frame.open();
        });
        
        // Media uploader for slider images
        $('.slider_image_upload').on('click', function(e) {
            e.preventDefault();
            var button = $(this);
            var targetId = button.data('target');
            var input = $('#' + targetId);
            var frame = wp.media({
                title: '<?php echo esc_js(__('اختر صورة', 'techno-souq-theme')); ?>',
                button: { text: '<?php echo esc_js(__('استخدم هذه الصورة', 'techno-souq-theme')); ?>' },
                multiple: false
            });
            frame.on('select', function() {
                var attachment = frame.state().get('selection').first().toJSON();
                input.val(attachment.url);
            });
            frame.open();
        });
    });
    </script>
    <?php
}
