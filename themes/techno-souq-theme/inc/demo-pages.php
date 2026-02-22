<?php
/**
 * Demo Pages Management
 * Create/Update pages used in the theme
 *
 * @package TechnoSouqTheme
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get required pages configuration
 */
function techno_souq_get_required_pages() {
    return array(
        array(
            'slug' => 'about-us',
            'title' => 'من نحن',
            'template' => 'page-about-us.php',
            'content' => '<p>محتوى صفحة من نحن</p>',
        ),
        array(
            'slug' => 'contact-us',
            'title' => 'تواصل معنا',
            'template' => 'page-contact-us.php',
            'content' => '<p>محتوى صفحة تواصل معنا</p>',
        ),
        array(
            'slug' => 'use-policy',
            'title' => 'سياسات الاستخدام',
            'template' => '',
            'content' => '<p>محتوى صفحة سياسات الاستخدام</p>',
        ),
        array(
            'slug' => 'privacy',
            'title' => 'سياسة الخصوصية',
            'template' => '',
            'content' => '<p>محتوى صفحة سياسة الخصوصية</p>',
        ),
        array(
            'slug' => 'replacement',
            'title' => 'سياسة الاستبدال والاسترجاع',
            'template' => '',
            'content' => '<p>محتوى صفحة سياسة الاستبدال والاسترجاع</p>',
        ),
        array(
            'slug' => 'refund',
            'title' => 'سياسة الاسترجاع',
            'template' => '',
            'content' => '<p>محتوى صفحة سياسة الاسترجاع</p>',
        ),
        array(
            'slug' => 'wishlist',
            'title' => 'المفضلة',
            'template' => 'page-wishlist.php',
            'content' => '',
        ),
    );
}

/**
 * Create or update a page
 */
function techno_souq_create_or_update_page($slug, $title, $template = '', $content = '') {
    // Check if page exists
    $page = get_page_by_path($slug);
    
    $page_data = array(
        'post_title' => $title,
        'post_content' => $content,
        'post_status' => 'publish',
        'post_type' => 'page',
        'post_name' => $slug,
    );
    
    if ($page) {
        // Update existing page
        $page_data['ID'] = $page->ID;
        $page_id = wp_update_post($page_data);
    } else {
        // Create new page
        $page_id = wp_insert_post($page_data);
    }
    
    // Set page template if provided
    if ($template && $page_id) {
        update_post_meta($page_id, '_wp_page_template', $template);
    }
    
    return $page_id;
}

/**
 * Create or update all required pages
 */
function techno_souq_create_all_pages() {
    $pages = techno_souq_get_required_pages();
    $results = array();
    
    foreach ($pages as $page_config) {
        $page_id = techno_souq_create_or_update_page(
            $page_config['slug'],
            $page_config['title'],
            $page_config['template'],
            $page_config['content']
        );
        
        if ($page_id && !is_wp_error($page_id)) {
            $results[] = array(
                'slug' => $page_config['slug'],
                'title' => $page_config['title'],
                'status' => 'success',
                'page_id' => $page_id,
                'action' => get_page_by_path($page_config['slug']) ? 'updated' : 'created',
            );
        } else {
            $results[] = array(
                'slug' => $page_config['slug'],
                'title' => $page_config['title'],
                'status' => 'error',
                'message' => is_wp_error($page_id) ? $page_id->get_error_message() : __('فشل في إنشاء الصفحة', 'techno-souq-theme'),
            );
        }
    }
    
    return $results;
}

/**
 * Add Demo Pages menu page
 */
function techno_souq_add_demo_pages_menu() {
    add_theme_page(
        __('صفحات ديمو', 'techno-souq-theme'),
        __('صفحات ديمو', 'techno-souq-theme'),
        'edit_theme_options',
        'techno-souq-demo-pages',
        'techno_souq_demo_pages_page'
    );
}
add_action('admin_menu', 'techno_souq_add_demo_pages_menu');

/**
 * Demo Pages Admin Page
 */
function techno_souq_demo_pages_page() {
    // Handle form submission
    if (isset($_POST['techno_souq_create_pages']) && check_admin_referer('techno_souq_demo_pages_nonce', 'techno_souq_demo_pages_nonce')) {
        $results = techno_souq_create_all_pages();
        
        $success_count = 0;
        $error_count = 0;
        
        foreach ($results as $result) {
            if ($result['status'] === 'success') {
                $success_count++;
            } else {
                $error_count++;
            }
        }
        
        if ($success_count > 0) {
            echo '<div class="notice notice-success"><p>';
            printf(
                __('تم إنشاء/تحديث %d صفحة بنجاح', 'techno-souq-theme'),
                $success_count
            );
            echo '</p></div>';
        }
        
        if ($error_count > 0) {
            echo '<div class="notice notice-error"><p>';
            printf(
                __('فشل في إنشاء/تحديث %d صفحة', 'techno-souq-theme'),
                $error_count
            );
            echo '</p></div>';
        }
        
        // Show detailed results
        if (!empty($results)) {
            echo '<div class="notice notice-info"><p><strong>' . __('تفاصيل النتائج:', 'techno-souq-theme') . '</strong></p><ul>';
            foreach ($results as $result) {
                if ($result['status'] === 'success') {
                    $action_text = $result['action'] === 'created' ? __('تم إنشاؤها', 'techno-souq-theme') : __('تم تحديثها', 'techno-souq-theme');
                    echo '<li>' . esc_html($result['title']) . ' (' . esc_html($result['slug']) . ') - ' . $action_text . '</li>';
                } else {
                    echo '<li>' . esc_html($result['title']) . ' (' . esc_html($result['slug']) . ') - ' . __('خطأ:', 'techno-souq-theme') . ' ' . esc_html($result['message']) . '</li>';
                }
            }
            echo '</ul></div>';
        }
    }
    
    // Get required pages
    $required_pages = techno_souq_get_required_pages();
    ?>
    <div class="wrap">
        <h1><?php echo esc_html__('صفحات ديمو', 'techno-souq-theme'); ?></h1>
        
        <div class="card">
            <h2><?php echo esc_html__('إنشاء/تحديث الصفحات', 'techno-souq-theme'); ?></h2>
            <p><?php echo esc_html__('سيتم إنشاء أو تحديث جميع الصفحات المستخدمة في الثيم بناءً على slugs المحددة.', 'techno-souq-theme'); ?></p>
            
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('Slug', 'techno-souq-theme'); ?></th>
                        <th><?php echo esc_html__('العنوان', 'techno-souq-theme'); ?></th>
                        <th><?php echo esc_html__('Template', 'techno-souq-theme'); ?></th>
                        <th><?php echo esc_html__('الحالة', 'techno-souq-theme'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($required_pages as $page) : 
                        $existing_page = get_page_by_path($page['slug']);
                        $status = $existing_page ? __('موجودة', 'techno-souq-theme') : __('غير موجودة', 'techno-souq-theme');
                        $status_class = $existing_page ? 'success' : 'warning';
                    ?>
                    <tr>
                        <td><code><?php echo esc_html($page['slug']); ?></code></td>
                        <td><?php echo esc_html($page['title']); ?></td>
                        <td><?php echo esc_html($page['template'] ?: '—'); ?></td>
                        <td>
                            <span class="dashicons dashicons-<?php echo $status_class === 'success' ? 'yes-alt' : 'warning'; ?>" style="color: <?php echo $status_class === 'success' ? 'green' : 'orange'; ?>;"></span>
                            <?php echo esc_html($status); ?>
                            <?php if ($existing_page) : ?>
                                <a href="<?php echo esc_url(get_edit_post_link($existing_page->ID)); ?>" target="_blank">
                                    <?php echo esc_html__('(تحرير)', 'techno-souq-theme'); ?>
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <form method="post" action="" style="margin-top: 20px;">
                <?php wp_nonce_field('techno_souq_demo_pages_nonce', 'techno_souq_demo_pages_nonce'); ?>
                <?php submit_button(__('إنشاء/تحديث جميع الصفحات', 'techno-souq-theme'), 'primary', 'techno_souq_create_pages'); ?>
            </form>
        </div>
        
        <div class="card" style="margin-top: 20px;">
            <h2><?php echo esc_html__('صفحات WooCommerce', 'techno-souq-theme'); ?></h2>
            <p><?php echo esc_html__('يتم إنشاء صفحات WooCommerce تلقائياً عند تثبيت WooCommerce. يمكنك إدارة هذه الصفحات من:', 'techno-souq-theme'); ?></p>
            <p>
                <a href="<?php echo esc_url(admin_url('admin.php?page=wc-settings&tab=advanced')); ?>" class="button">
                    <?php echo esc_html__('إعدادات WooCommerce → Advanced → Page Setup', 'techno-souq-theme'); ?>
                </a>
            </p>
        </div>
    </div>
    <?php
}
