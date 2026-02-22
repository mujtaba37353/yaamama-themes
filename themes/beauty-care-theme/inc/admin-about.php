<?php
/**
 * Beauty Care Theme — إعدادات صفحة من نحن.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beauty_care_default_about_settings() {
	return array(
		'section1_title' => 'من نحن',
		'section1_text'  => 'نحن شركة متخصصة في تقديم أفضل أدوات ومنتجات التجميل للعناية بالبشرة والشعر، نحرص على الجمع بين الجودة العالية والمكونات الآمنة لتمنحكِ تجربة عناية فريدة ونتائج ملموسة. هدفنا أن نساعد كل امرأة على إبراز جمالها الطبيعي من خلال منتجات مبتكرة تراعي احتياجات جميع أنواع البشرة والشعر.',
		'section1_img'   => 0,
		'section2_title' => 'رسالتنا',
		'section2_text'  => 'نلتزم بتقديم منتجات عناية متكاملة للبشرة والشعر تعتمد على مكونات طبيعية وآمنة، تجمع بين الجودة العالية والتقنيات الحديثة لتوفير أفضل تجربة استخدام لعملائنا. نهدف إلى تعزيز ثقة كل فرد في جماله الطبيعي، ودعم أسلوب حياة صحية ومستدامة تعكس الجمال الحقيقي من الداخل إلى الخارج.',
		'section2_img'   => 0,
		'section3_title' => 'رؤيتنا',
		'section3_text'  => 'نسعى لأن نكون العلامة الرائدة في عالم العناية بالجمال الطبيعي، من خلال تقديم منتجات موثوقة وآمنة تساعد عملاءنا على الاهتمام ببشرتهم وشعرهم بثقة وراحة. نؤمن أن الجمال الحقيقي يبدأ من العناية اليومية، لذلك نعمل على ابتكار حلول تجمع بين الفعالية والجودة لتلائم أسلوب الحياة العصري وتبرز جمالك الطبيعي في كل لحظة.',
		'section3_img'   => 0,
	);
}

function beauty_care_get_about_settings() {
	$saved = get_option( 'beauty_care_about_settings', array() );
	return wp_parse_args( $saved, beauty_care_default_about_settings() );
}

function beauty_care_register_about_admin() {
	add_submenu_page(
		'beauty-care-content',
		'من نحن',
		'من نحن',
		'manage_options',
		'beauty-care-about',
		'beauty_care_render_about_admin'
	);
}
add_action( 'admin_menu', 'beauty_care_register_about_admin', 15 );

function beauty_care_about_admin_enqueue( $hook ) {
	if ( strpos( $hook, 'beauty-care-about' ) !== false ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'beauty_care_about_admin_enqueue' );

function beauty_care_render_about_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = beauty_care_get_about_settings();
	$assets = get_template_directory_uri() . '/beauty-care/assets';
	?>
	<div class="wrap">
		<h1>إعدادات من نحن</h1>
		<?php if ( isset( $_GET['beauty_care_about_saved'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم الحفظ بنجاح.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['beauty_care_about_restored'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم استعادة المحتوى الأصلي.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'beauty_care_save_about', 'beauty_care_about_nonce' ); ?>
			<input type="hidden" name="action" value="beauty_care_save_about" />
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
			<h2>القسم <?php echo (int) $i; ?></h2>
			<table class="form-table">
				<tr><th><label for="section<?php echo $i; ?>_title">العنوان</label></th><td><input type="text" name="section<?php echo $i; ?>_title" id="section<?php echo $i; ?>_title" class="regular-text" value="<?php echo esc_attr( $s[ 'section' . $i . '_title' ] ); ?>" /></td></tr>
				<tr><th><label for="section<?php echo $i; ?>_text">النص</label></th><td><textarea name="section<?php echo $i; ?>_text" id="section<?php echo $i; ?>_text" rows="4" class="large-text"><?php echo esc_textarea( $s[ 'section' . $i . '_text' ] ); ?></textarea></td></tr>
				<tr><th>الصورة</th><td><?php beauty_care_admin_image_upload( 'section' . $i . '_img', $s[ 'section' . $i . '_img' ], $assets . '/about-us.jpg' ); ?></td></tr>
			</table>
			<?php endfor; ?>
			<p class="submit">
				<?php submit_button( 'حفظ', 'primary' ); ?>
				<button type="submit" name="restore_default" value="1" class="button" onclick="return confirm('استعادة المحتوى الأصلي؟');">استعادة المحتوى الأصلي</button>
			</p>
		</form>
	</div>
	<script>
	jQuery(function($) {
		$('.beauty-care-image-upload').each(function() {
			var $w = $(this), $in = $w.find('input[type="hidden"]'), $pv = $w.find('.image-preview');
			$w.find('.upload-image-btn').on('click', function() {
				var f = wp.media({ library: { type: 'image' }, multiple: false });
				f.on('select', function() {
					var att = f.state().get('selection').first().toJSON();
					$in.val(att.id);
					$pv.html('<img src="' + att.url + '" alt="" style="max-width:100%;height:auto;border:1px solid #ddd;" />');
					$w.find('.remove-image-btn').show();
				});
				f.open();
			});
			$w.find('.remove-image-btn').on('click', function() {
				$in.val(0);
				$pv.empty();
				$(this).hide();
			});
		});
	});
	</script>
	<?php
}

function beauty_care_save_about_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'beauty_care_save_about', 'beauty_care_about_nonce' );
	if ( ! empty( $_POST['restore_default'] ) ) {
		delete_option( 'beauty_care_about_settings' );
		wp_safe_redirect( add_query_arg( 'beauty_care_about_restored', '1', admin_url( 'admin.php?page=beauty-care-about' ) ) );
		exit;
	}
	$data = array();
	for ( $i = 1; $i <= 3; $i++ ) {
		$data[ 'section' . $i . '_title' ] = isset( $_POST[ 'section' . $i . '_title' ] ) ? sanitize_text_field( wp_unslash( $_POST[ 'section' . $i . '_title' ] ) ) : '';
		$data[ 'section' . $i . '_text' ]  = isset( $_POST[ 'section' . $i . '_text' ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ 'section' . $i . '_text' ] ) ) : '';
		$data[ 'section' . $i . '_img' ]   = isset( $_POST[ 'section' . $i . '_img' ] ) ? absint( $_POST[ 'section' . $i . '_img' ] ) : 0;
	}
	update_option( 'beauty_care_about_settings', $data );
	wp_safe_redirect( add_query_arg( 'beauty_care_about_saved', '1', admin_url( 'admin.php?page=beauty-care-about' ) ) );
	exit;
}
add_action( 'admin_post_beauty_care_save_about', 'beauty_care_save_about_handler' );
