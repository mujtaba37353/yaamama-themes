<?php
/**
 * Beauty Care Theme — إعدادات الصفحة الرئيسية.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beauty_care_default_homepage_settings() {
	return array(
		'hero_title'       => 'بيوتي كير',
		'hero_subtitle1'   => 'اكتشفي سر إشراقة بشرتك الطبيعية',
		'hero_subtitle2'   => 'منتجات طبيعية وآمنة تمنحك عناية متكاملة لبشرة صحية وشعر لامع.',
		'hero_cta_text'    => 'جربي الآن',
		'hero_img1'        => 0,
		'hero_img2'        => 0,
		'hero_curve'       => 0,
		'about_title'      => 'من نحن',
		'about_text'      => 'نحن شركة متخصصة في تقديم أفضل أدوات ومنتجات التجميل للعناية بالبشرة والشعر، نحرص على الجمع بين الجودة العالية والمكونات الآمنة لتمنحكِ تجربة عناية فريدة ونتائج ملموسة.',
		'about_img'        => 0,
		'panner1_text'     => 'منتجات عناية بالبشرة والشعر مصممة من مكونات طبيعية تمنحك إشراقة تدوم.',
		'panner1_img'      => 0,
		'cat1_title'       => 'العناية بالبشرة',
		'cat2_title'       => 'العناية بالشعر',
		'cat3_title'       => 'العناية بالجسم',
		'cat4_title'       => 'منتجات طبيعية',
		'cat1_img'         => 0,
		'cat2_img'         => 0,
		'cat3_img'         => 0,
		'cat4_img'         => 0,
		'panner2_text1'    => 'سيروم فيتامين سي – إشراقة فورية لبشرتك',
		'panner2_text2'    => 'تركيبة غنية بمضادات الأكسدة لتفتيح البشرة وتقليل التصبغات.',
		'panner2_cta_text' => 'تعرّفي على المزيد',
		'panner2_img'      => 0,
		'products_title'   => 'منتجاتنا المميزة',
		'products_text'    => 'اكتشفي مجموعتنا المختارة من منتجات العناية بالبشرة والشعر، المصممة لتمنحك جمالًا طبيعيًا يدوم.',
		'products_cta_text'=> 'اكتشفي المزيد',
	);
}

function beauty_care_get_homepage_settings() {
	$saved = get_option( 'beauty_care_homepage_settings', array() );
	return wp_parse_args( $saved, beauty_care_default_homepage_settings() );
}

function beauty_care_content_image_url( $attachment_id, $theme_fallback ) {
	if ( $attachment_id && wp_attachment_is_image( $attachment_id ) ) {
		$url = wp_get_attachment_image_url( $attachment_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return get_template_directory_uri() . '/beauty-care/assets/' . ltrim( $theme_fallback, '/' );
}

function beauty_care_register_homepage_admin() {
	add_submenu_page(
		'beauty-care-content',
		'الصفحة الرئيسية',
		'الصفحة الرئيسية',
		'manage_options',
		'beauty-care-homepage',
		'beauty_care_render_homepage_admin'
	);
}
add_action( 'admin_menu', 'beauty_care_register_homepage_admin', 14 );

function beauty_care_homepage_admin_enqueue( $hook ) {
	if ( strpos( $hook, 'beauty-care-homepage' ) !== false ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'beauty_care_homepage_admin_enqueue' );

function beauty_care_render_homepage_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$s = beauty_care_get_homepage_settings();
	$assets = get_template_directory_uri() . '/beauty-care/assets';
	?>
	<div class="wrap">
		<h1>إعدادات الصفحة الرئيسية</h1>
		<?php if ( isset( $_GET['beauty_care_homepage_saved'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم الحفظ بنجاح.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['beauty_care_homepage_restored'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم استعادة المحتوى الأصلي.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'beauty_care_save_homepage', 'beauty_care_homepage_nonce' ); ?>
			<input type="hidden" name="action" value="beauty_care_save_homepage" />

			<h2>قسم Hero</h2>
			<table class="form-table">
				<tr><th><label for="hero_title">العنوان</label></th><td><input type="text" name="hero_title" id="hero_title" class="regular-text" value="<?php echo esc_attr( $s['hero_title'] ); ?>" /></td></tr>
				<tr><th><label for="hero_subtitle1">النص الفرعي 1</label></th><td><input type="text" name="hero_subtitle1" id="hero_subtitle1" class="large-text" value="<?php echo esc_attr( $s['hero_subtitle1'] ); ?>" /></td></tr>
				<tr><th><label for="hero_subtitle2">النص الفرعي 2</label></th><td><textarea name="hero_subtitle2" id="hero_subtitle2" rows="2" class="large-text"><?php echo esc_textarea( $s['hero_subtitle2'] ); ?></textarea></td></tr>
				<tr><th><label for="hero_cta_text">نص زر CTA</label></th><td><input type="text" name="hero_cta_text" id="hero_cta_text" class="regular-text" value="<?php echo esc_attr( $s['hero_cta_text'] ); ?>" /></td></tr>
				<tr><th>صورة 1</th><td><?php beauty_care_admin_image_upload( 'hero_img1', $s['hero_img1'], $assets . '/hero1.jpg' ); ?></td></tr>
				<tr><th>صورة 2</th><td><?php beauty_care_admin_image_upload( 'hero_img2', $s['hero_img2'], $assets . '/hero2.jpg' ); ?></td></tr>
				<tr><th>صورة المنحنى</th><td><?php beauty_care_admin_image_upload( 'hero_curve', $s['hero_curve'], $assets . '/hero-curve.png' ); ?></td></tr>
			</table>

			<h2>قسم من نحن</h2>
			<table class="form-table">
				<tr><th><label for="about_title">العنوان</label></th><td><input type="text" name="about_title" id="about_title" class="regular-text" value="<?php echo esc_attr( $s['about_title'] ); ?>" /></td></tr>
				<tr><th><label for="about_text">النص</label></th><td><textarea name="about_text" id="about_text" rows="4" class="large-text"><?php echo esc_textarea( $s['about_text'] ); ?></textarea></td></tr>
				<tr><th>الصورة</th><td><?php beauty_care_admin_image_upload( 'about_img', $s['about_img'], $assets . '/about-us.jpg' ); ?></td></tr>
			</table>

			<h2>بانر 1</h2>
			<table class="form-table">
				<tr><th><label for="panner1_text">النص</label></th><td><textarea name="panner1_text" id="panner1_text" rows="2" class="large-text"><?php echo esc_textarea( $s['panner1_text'] ); ?></textarea></td></tr>
				<tr><th>الصورة</th><td><?php beauty_care_admin_image_upload( 'panner1_img', $s['panner1_img'], $assets . '/panner1.jpg' ); ?></td></tr>
			</table>

			<h2>الفئات (4)</h2>
			<table class="form-table">
				<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<tr>
					<th>الفئة <?php echo (int) $i; ?></th>
					<td>
						<input type="text" name="cat<?php echo $i; ?>_title" value="<?php echo esc_attr( $s[ 'cat' . $i . '_title' ] ); ?>" class="regular-text" placeholder="العنوان" />
						<?php beauty_care_admin_image_upload( 'cat' . $i . '_img', $s[ 'cat' . $i . '_img' ], $assets . '/cat' . $i . '.jpg' ); ?>
					</td>
				</tr>
				<?php endfor; ?>
			</table>

			<h2>بانر 2</h2>
			<table class="form-table">
				<tr><th><label for="panner2_text1">النص 1</label></th><td><input type="text" name="panner2_text1" id="panner2_text1" class="large-text" value="<?php echo esc_attr( $s['panner2_text1'] ); ?>" /></td></tr>
				<tr><th><label for="panner2_text2">النص 2</label></th><td><textarea name="panner2_text2" id="panner2_text2" rows="2" class="large-text"><?php echo esc_textarea( $s['panner2_text2'] ); ?></textarea></td></tr>
				<tr><th><label for="panner2_cta_text">نص الزر</label></th><td><input type="text" name="panner2_cta_text" id="panner2_cta_text" class="regular-text" value="<?php echo esc_attr( $s['panner2_cta_text'] ); ?>" /></td></tr>
				<tr><th>الصورة</th><td><?php beauty_care_admin_image_upload( 'panner2_img', $s['panner2_img'], $assets . '/panner2.png' ); ?></td></tr>
			</table>

			<h2>قسم المنتجات</h2>
			<table class="form-table">
				<tr><th><label for="products_title">العنوان</label></th><td><input type="text" name="products_title" id="products_title" class="regular-text" value="<?php echo esc_attr( $s['products_title'] ); ?>" /></td></tr>
				<tr><th><label for="products_text">الوصف</label></th><td><textarea name="products_text" id="products_text" rows="2" class="large-text"><?php echo esc_textarea( $s['products_text'] ); ?></textarea></td></tr>
				<tr><th><label for="products_cta_text">نص الزر</label></th><td><input type="text" name="products_cta_text" id="products_cta_text" class="regular-text" value="<?php echo esc_attr( $s['products_cta_text'] ); ?>" /></td></tr>
			</table>

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

function beauty_care_admin_image_upload( $name, $value, $fallback_url = '' ) {
	$val = (int) $value;
	$url = $val && wp_attachment_is_image( $val ) ? wp_get_attachment_image_url( $val, 'medium' ) : $fallback_url;
	?>
	<div class="beauty-care-image-upload">
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $val ); ?>" />
		<div class="image-preview" style="max-width:150px;margin-bottom:6px;">
			<?php if ( $url ) : ?><img src="<?php echo esc_url( $url ); ?>" alt="" style="max-width:100%;height:auto;border:1px solid #ddd;" /><?php endif; ?>
		</div>
		<button type="button" class="button upload-image-btn">رفع</button>
		<button type="button" class="button remove-image-btn" <?php echo $val ? '' : 'style="display:none;"'; ?>>إزالة</button>
	</div>
	<?php
}

function beauty_care_save_homepage_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'beauty_care_save_homepage', 'beauty_care_homepage_nonce' );
	if ( ! empty( $_POST['restore_default'] ) ) {
		delete_option( 'beauty_care_homepage_settings' );
		wp_safe_redirect( add_query_arg( 'beauty_care_homepage_restored', '1', admin_url( 'admin.php?page=beauty-care-homepage' ) ) );
		exit;
	}
	$keys = array(
		'hero_title', 'hero_subtitle1', 'hero_subtitle2', 'hero_cta_text',
		'hero_img1', 'hero_img2', 'hero_curve',
		'about_title', 'about_text', 'about_img',
		'panner1_text', 'panner1_img',
		'cat1_title', 'cat2_title', 'cat3_title', 'cat4_title',
		'cat1_img', 'cat2_img', 'cat3_img', 'cat4_img',
		'panner2_text1', 'panner2_text2', 'panner2_cta_text', 'panner2_img',
		'products_title', 'products_text', 'products_cta_text',
	);
	$data = array();
	foreach ( $keys as $k ) {
		if ( in_array( $k, array( 'hero_img1', 'hero_img2', 'hero_curve', 'about_img', 'panner1_img', 'panner2_img', 'cat1_img', 'cat2_img', 'cat3_img', 'cat4_img' ), true ) ) {
			$data[ $k ] = isset( $_POST[ $k ] ) ? absint( $_POST[ $k ] ) : 0;
		} elseif ( in_array( $k, array( 'hero_subtitle2', 'about_text', 'panner1_text', 'panner2_text2', 'products_text' ), true ) ) {
			$data[ $k ] = isset( $_POST[ $k ] ) ? sanitize_textarea_field( wp_unslash( $_POST[ $k ] ) ) : '';
		} else {
			$data[ $k ] = isset( $_POST[ $k ] ) ? sanitize_text_field( wp_unslash( $_POST[ $k ] ) ) : '';
		}
	}
	update_option( 'beauty_care_homepage_settings', $data );
	wp_safe_redirect( add_query_arg( 'beauty_care_homepage_saved', '1', admin_url( 'admin.php?page=beauty-care-homepage' ) ) );
	exit;
}
add_action( 'admin_post_beauty_care_save_homepage', 'beauty_care_save_homepage_handler' );
