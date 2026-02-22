<?php
/**
 * Custom Control for Reset Demo Content Button
 *
 * @package KhutaaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Only load if WP_Customize_Control is available (in customizer context)
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

/**
 * Custom Control for Reset Demo Content Button
 */
class Khutaa_Reset_Demo_Control extends WP_Customize_Control {
	public $type = 'khutaa_reset_button';

	public function render_content() {
		?>
		<button type="button" class="button khutaa-reset-demo-content" id="khutaa-reset-demo-content">
			<?php esc_html_e( 'إعادة تعيين المحتوى الديمو إلى القيم الافتراضية', 'khutaa-theme' ); ?>
		</button>
		<p class="description">
			<?php esc_html_e( 'سيتم إعادة جميع إعدادات المحتوى الديمو إلى القيم الافتراضية الأصلية.', 'khutaa-theme' ); ?>
		</p>
		<script>
		(function($) {
			$(document).ready(function() {
				$('#khutaa-reset-demo-content').on('click', function(e) {
					e.preventDefault();
					var confirmMsg = '<?php echo esc_js( __( 'هل أنت متأكد من إعادة تعيين جميع المحتوى الديمو؟ سيتم فقدان جميع التغييرات الحالية.', 'khutaa-theme' ) ); ?>';
					if (confirm(confirmMsg)) {
						var data = {
							action: 'khutaa_reset_demo_content',
							nonce: '<?php echo wp_create_nonce( 'khutaa_reset_demo_content' ); ?>'
						};
						var ajaxUrl = typeof ajaxurl !== 'undefined' ? ajaxurl : '<?php echo esc_js( admin_url( 'admin-ajax.php' ) ); ?>';
						$.post(ajaxUrl, data, function(response) {
							if (response.success) {
								alert('<?php echo esc_js( __( 'تم إعادة تعيين المحتوى الديمو بنجاح!', 'khutaa-theme' ) ); ?>');
								location.reload();
							} else {
								alert('<?php echo esc_js( __( 'حدث خطأ أثناء إعادة التعيين.', 'khutaa-theme' ) ); ?>');
							}
						}).fail(function() {
							alert('<?php echo esc_js( __( 'حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى.', 'khutaa-theme' ) ); ?>');
						});
					}
				});
			});
		})(jQuery);
		</script>
		<?php
	}
}
