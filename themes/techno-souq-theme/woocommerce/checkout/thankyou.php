<?php
/**
 * Thankyou page
 *
 * @package TechnoSouqTheme
 */

defined('ABSPATH') || exit;

// Enqueue thank you page styles
$theme_version = wp_get_theme()->get('Version');
$techno_souq_path = get_template_directory_uri() . '/techno-souq';
wp_enqueue_style('techno-souq-thankyou', $techno_souq_path . '/templates/thankyou/y-thankyou.css', array(
    'techno-souq-header',
    'techno-souq-footer',
    'techno-souq-buttons'
), $theme_version);

// Remove default WooCommerce wrappers
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
remove_action('woocommerce_before_main_content', 'woocommerce_breadcrumb', 20);
?>

<div class="y-l-thankyou-container" data-y="thankyou-container">
    <div class="y-c-thankyou-card" data-y="thankyou-card">
        <div class="y-c-thankyou-icon" data-y="thankyou-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2 class="y-c-thankyou-title" data-y="thankyou-title">
            <?php esc_html_e('تمت العملية بنجاح', 'techno-souq-theme'); ?>
        </h2>
        <a href="<?php echo esc_url(home_url('/')); ?>" class="y-c-btn y-c-btn-primary y-c-thankyou-btn" data-y="thankyou-button">
            <?php esc_html_e('العودة للرئيسية', 'techno-souq-theme'); ?>
        </a>
    </div>
</div>

<?php
// Execute WooCommerce hooks for compatibility
if (isset($order) && $order) {
    do_action('woocommerce_thankyou', $order->get_id());
}
?>
