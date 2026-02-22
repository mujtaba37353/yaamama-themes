<?php
/**
 * Sweet House Theme — محرر المحتوى: الصفحة الرئيسية، من نحن، السياسات.
 * إدارة محتوى الصفحات مع رفع الصور وحفظ/استعادة المحتوى الأصلي.
 *
 * @package Sweet_House_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * المحتوى الافتراضي للصفحة الرئيسية.
 */
function sweet_house_default_home_content() {
	return array(
		'hero_banner_img'      => 0,
		'hero_title'           => 'سويت هاوس',
		'hero_subtitle1'       => 'من فرننا لقلبك',
		'hero_subtitle2'       => 'سويت هاوس.. طعم ولا احلى',
		'mid_banner_img'       => 0,
		'categories_title'    => 'أقسام منتجاتنا',
		'categories_subtitle' => 'اكتشف تشكيلة واسعة من أجود الحلويات بألذ النكهات',
		'products_title'      => 'منتجاتنا',
		'about_title'         => 'الخبز هو شغفنا',
		'about_subtitle'      => 'شغف نحمله منذ البداية',
		'about_text'          => 'في كل صباح عند انتشار رائحة مخبوزاتنا في الأرجاء، لتصل إلى زبائننا شهيّة وبمعايير عالية من الخدمة واهتمام بأدق تفاصيل التجربة، نتابع كلّ خطوة لنحافظ على سلامة الغذاء ونرسم الخطط ليوم جديد مليء بما لذّ وطابّ',
		'about_img1'          => 0,
		'about_img2'          => 0,
		'feat1_icon'          => 0,
		'feat1_title'         => 'خدمة العملاء',
		'feat1_text'          => 'على أعلى مستوى من الإحترافية',
		'feat2_icon'          => 0,
		'feat2_title'         => 'منتجات عالية الجودة',
		'feat2_text'          => 'تمتع بمذاقنا المتميز لأكثر من 80 عاماً',
		'feat3_icon'          => 0,
		'feat3_title'         => 'استلام من الفرع',
		'feat3_text'          => 'استلم طلبك من الفرع الأقرب إليك',
		'feat4_icon'          => 0,
		'feat4_title'         => 'توصيل سريع',
		'feat4_text'          => 'توصيل على مدار 24 ساعة',
	);
}

/**
 * المحتوى الافتراضي لصفحة من نحن.
 */
function sweet_house_default_about_content() {
	return array(
		'banner_img'  => 0,
		'about1_img'  => 0,
		'about2_img'  => 0,
		'block1'      => 'سويت هاوس متجر متخصص في الحلويات والمخبوزات الطازجة. نلتزم بتقديم أجود المكونات وألذ النكهات لعملائنا الكرام.',
		'block2'      => 'نسعى دائماً لتجربة العملاء وتقديم أفضل الخدمات. زورونا واستمتعوا بأجود المنتجات.',
		'stat1_num'  => '+200',
		'stat1_text' => 'عملاء سعداء',
		'stat2_num'  => '+10',
		'stat2_text' => 'سنوات الخبرة',
		'stat3_num'  => '+20',
		'stat3_text' => 'عملاء الجملة',
	);
}

/**
 * المحتوى الافتراضي لصفحات السياسات.
 */
function sweet_house_default_policy_content( $slug = 'refund-policy' ) {
	$defaults = array(
		'privacy-policy'  => array(
			'banner_img' => 0,
			'sections'   => array(
				array( 'title' => 'سياسة الخصوصية', 'content' => 'نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية.' ),
			),
		),
		'refund-policy'   => array(
			'banner_img' => 0,
			'sections'   => array(
				array( 'title' => 'سياسة الاسترجاع والاسترداد', 'content' => 'عمل دائماً لنيل رضاكم ونكون عند حسن ظنكم بنا. إذا كنت ترغب في إرجاع منتج ما، فنحن نقبل بسرور استبدال المنتج أو منحك رصيداً في المتجر أو إرجاع المنتج مقابل نقاط متجر الأحمدي.' ),
				array( 'title' => 'في حال طلب استرجاع', 'content' => 'يرجى التواصل معنا عبر البريد الإلكتروني: care@yamamah.sa أو الهاتف أو واتساب: 966534411732+' ),
				array( 'title' => 'الدفع عبر الإنترنت', 'content' => 'ستتم معالجة المبالغ المستردة في غضون ٢٤ ساعة وستضاف إلى حساب العميل في غضون 3-5 أيام عمل، اعتمادًا على مصدر البنك.' ),
				array( 'title' => 'الدفع نقداً عند التسليم', 'content' => 'ستُضاف المبالغ المستردة إلى حساب العميل كنقاط متجر الأحمدي ويمكن استخدامها في الطلب التالي.' ),
				array( 'title' => 'نقاط متجر الأحمدي', 'content' => 'ستُضاف المبالغ المستردة إلى حساب العميل كنقاط متجر الأحمدي ويمكن استخدامها في الطلب التالي.' ),
			),
		),
		'shipping-policy' => array(
			'banner_img' => 0,
			'sections'   => array(
				array( 'title' => 'سياسة الشحن', 'content' => 'يتم الشحن خلال اليوم لكل الطلبات داخل المدينة المنورة، أما كل الطلبات داخل السعودية وخارج المدينة المنورة يستغرق الشحن من يوم إلى ثلاثة أيام عمل.' ),
			),
		),
	);
	return isset( $defaults[ $slug ] ) ? $defaults[ $slug ] : $defaults['refund-policy'];
}

/**
 * الحصول على URL صورة من attachment_id أو أصل الثيم.
 */
function sweet_house_content_image_url( $attachment_id, $theme_fallback ) {
	if ( $attachment_id && wp_attachment_is_image( $attachment_id ) ) {
		$url = wp_get_attachment_image_url( $attachment_id, 'full' );
		if ( $url ) {
			return $url;
		}
	}
	return function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( $theme_fallback ) : '';
}

/**
 * الحصول على محتوى الصفحة الرئيسية (محفوظ أو افتراضي).
 */
function sweet_house_get_home_content() {
	$saved = get_option( 'sweet_house_home_content', array() );
	$default = sweet_house_default_home_content();
	return wp_parse_args( $saved, $default );
}

/**
 * الحصول على محتوى صفحة من نحن.
 */
function sweet_house_get_about_content() {
	$saved = get_option( 'sweet_house_about_content', array() );
	$default = sweet_house_default_about_content();
	return wp_parse_args( $saved, $default );
}

/**
 * الحصول على محتوى صفحة السياسة.
 */
function sweet_house_get_policy_content( $slug ) {
	$all = get_option( 'sweet_house_policy_content', array() );
	$saved = isset( $all[ $slug ] ) ? $all[ $slug ] : array();
	$default = sweet_house_default_policy_content( $slug );
	return wp_parse_args( $saved, $default );
}

/**
 * تسجيل قوائم الإدارة.
 */
function sweet_house_register_content_editor_menus() {
	add_submenu_page(
		'sweet-house-content',
		'الصفحة الرئيسية',
		'الصفحة الرئيسية',
		'manage_options',
		'sweet-house-home-content',
		'sweet_house_render_home_content_editor'
	);
	add_submenu_page(
		'sweet-house-content',
		'من نحن',
		'من نحن',
		'manage_options',
		'sweet-house-about-content',
		'sweet_house_render_about_content_editor'
	);
	add_submenu_page(
		'sweet-house-content',
		'سياسة الخصوصية',
		'سياسة الخصوصية',
		'manage_options',
		'sweet-house-policy-privacy',
		'sweet_house_render_policy_editor'
	);
	add_submenu_page(
		'sweet-house-content',
		'سياسة الاسترجاع',
		'سياسة الاسترجاع',
		'manage_options',
		'sweet-house-policy-refund',
		'sweet_house_render_policy_editor'
	);
	add_submenu_page(
		'sweet-house-content',
		'سياسة الشحن',
		'سياسة الشحن',
		'manage_options',
		'sweet-house-policy-shipping',
		'sweet_house_render_policy_editor'
	);
}
add_action( 'admin_menu', 'sweet_house_register_content_editor_menus', 11 );

/**
 * تحميل وسائط الرفع في صفحة المحرر.
 */
function sweet_house_content_editor_enqueue_media( $hook ) {
	$pages = array(
		'sweet-house-home-content',
		'sweet-house-about-content',
		'sweet-house-policy-privacy',
		'sweet-house-policy-refund',
		'sweet-house-policy-shipping',
	);
	$page = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
	$on_content_page = in_array( $page, $pages, true );
	$hook_match = in_array( $hook, array(
		'sweet-house-content_page_sweet-house-home-content',
		'sweet-house-content_page_sweet-house-about-content',
		'sweet-house-content_page_sweet-house-policy-privacy',
		'sweet-house-content_page_sweet-house-policy-refund',
		'sweet-house-content_page_sweet-house-policy-shipping',
	), true );
	if ( $on_content_page || $hook_match ) {
		wp_enqueue_media();
	}
}
add_action( 'admin_enqueue_scripts', 'sweet_house_content_editor_enqueue_media' );

/**
 * عرض محرر الصفحة الرئيسية.
 */
function sweet_house_render_home_content_editor() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$content = sweet_house_get_home_content();
	sweet_house_render_content_editor_notices( 'home' );
	?>
	<div class="wrap">
		<h1>محتوى الصفحة الرئيسية</h1>
		<p>عدّل البنر الرئيسي، البنر المتوسط، قسم من نحن، وقسم المميزات. الصور عبر الرفع.</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" id="sweet-house-home-form">
			<?php wp_nonce_field( 'sweet_house_save_home_content', 'sweet_house_home_nonce' ); ?>
			<input type="hidden" name="action" value="sweet_house_save_home_content" />

			<h2>البنر الرئيسي (Hero)</h2>
			<table class="form-table">
				<tr>
					<th><label><?php esc_html_e( 'الصورة', 'sweet-house-theme' ); ?></label></th>
					<td><?php sweet_house_render_image_upload( 'hero_banner_img', $content['hero_banner_img'], 'assets/header.png' ); ?></td>
				</tr>
				<tr>
					<th><label for="hero_title"><?php esc_html_e( 'العنوان', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="hero_title" id="hero_title" class="regular-text" value="<?php echo esc_attr( $content['hero_title'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="hero_subtitle1"><?php esc_html_e( 'الجملة الأولى', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="hero_subtitle1" id="hero_subtitle1" class="regular-text" value="<?php echo esc_attr( $content['hero_subtitle1'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="hero_subtitle2"><?php esc_html_e( 'الجملة الثانية', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="hero_subtitle2" id="hero_subtitle2" class="regular-text" value="<?php echo esc_attr( $content['hero_subtitle2'] ); ?>" /></td>
				</tr>
			</table>

			<h2>البنر المتوسط</h2>
			<table class="form-table">
				<tr>
					<th><label><?php esc_html_e( 'الصورة', 'sweet-house-theme' ); ?></label></th>
					<td><?php sweet_house_render_image_upload( 'mid_banner_img', $content['mid_banner_img'], 'assets/panner.png' ); ?></td>
				</tr>
			</table>

			<h2>قسم التصنيفات (row align-items-center)</h2>
			<table class="form-table">
				<tr>
					<th><label for="categories_title"><?php esc_html_e( 'العنوان', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="categories_title" id="categories_title" class="regular-text" value="<?php echo esc_attr( $content['categories_title'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="categories_subtitle"><?php esc_html_e( 'الجملة', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="categories_subtitle" id="categories_subtitle" class="large-text" value="<?php echo esc_attr( $content['categories_subtitle'] ); ?>" /></td>
				</tr>
			</table>

			<h2>قسم المنتجات</h2>
			<table class="form-table">
				<tr>
					<th><label for="products_title"><?php esc_html_e( 'العنوان', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="products_title" id="products_title" class="regular-text" value="<?php echo esc_attr( $content['products_title'] ); ?>" /></td>
				</tr>
			</table>

			<h2>قسم من نحن (row align-items-center)</h2>
			<table class="form-table">
				<tr>
					<th><label for="about_title"><?php esc_html_e( 'العنوان', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="about_title" id="about_title" class="regular-text" value="<?php echo esc_attr( $content['about_title'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="about_subtitle"><?php esc_html_e( 'العنوان الفرعي', 'sweet-house-theme' ); ?></label></th>
					<td><input type="text" name="about_subtitle" id="about_subtitle" class="regular-text" value="<?php echo esc_attr( $content['about_subtitle'] ); ?>" /></td>
				</tr>
				<tr>
					<th><label for="about_text"><?php esc_html_e( 'الجملة', 'sweet-house-theme' ); ?></label></th>
					<td><textarea name="about_text" id="about_text" rows="4" class="large-text"><?php echo esc_textarea( $content['about_text'] ); ?></textarea></td>
				</tr>
				<tr>
					<th><label><?php esc_html_e( 'الصورة الأولى', 'sweet-house-theme' ); ?></label></th>
					<td><?php sweet_house_render_image_upload( 'about_img1', $content['about_img1'], 'assets/about-home1.png' ); ?></td>
				</tr>
				<tr>
					<th><label><?php esc_html_e( 'الصورة الثانية', 'sweet-house-theme' ); ?></label></th>
					<td><?php sweet_house_render_image_upload( 'about_img2', $content['about_img2'], 'assets/about-home2.png' ); ?></td>
				</tr>
			</table>

			<h2>قسم المميزات (row align-items-center justify-content-center)</h2>
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<table class="form-table">
					<tr>
						<th colspan="2"><strong><?php echo esc_html( sprintf( 'ميزة %d', $i ) ); ?></strong></th>
					</tr>
					<tr>
						<th><label><?php esc_html_e( 'الأيقونة', 'sweet-house-theme' ); ?></label></th>
						<td><?php sweet_house_render_image_upload( "feat{$i}_icon", $content[ "feat{$i}_icon" ], "assets/feat{$i}.png" ); ?></td>
					</tr>
					<tr>
						<th><label for="feat<?php echo $i; ?>_title"><?php esc_html_e( 'العنوان', 'sweet-house-theme' ); ?></label></th>
						<td><input type="text" name="feat<?php echo $i; ?>_title" id="feat<?php echo $i; ?>_title" class="regular-text" value="<?php echo esc_attr( $content[ "feat{$i}_title" ] ); ?>" /></td>
					</tr>
					<tr>
						<th><label for="feat<?php echo $i; ?>_text"><?php esc_html_e( 'الجملة', 'sweet-house-theme' ); ?></label></th>
						<td><input type="text" name="feat<?php echo $i; ?>_text" id="feat<?php echo $i; ?>_text" class="large-text" value="<?php echo esc_attr( $content[ "feat{$i}_text" ] ); ?>" /></td>
					</tr>
				</table>
			<?php endfor; ?>

			<p class="submit">
				<?php submit_button( __( 'حفظ', 'sweet-house-theme' ), 'primary', 'submit', false ); ?>
				&nbsp;
				<button type="submit" name="restore_default" value="1" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى الأصلي؟', 'sweet-house-theme' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'sweet-house-theme' ); ?></button>
			</p>
		</form>
	</div>
	<?php
	sweet_house_render_image_upload_script();
}

/**
 * عرض محرر صفحة من نحن.
 */
function sweet_house_render_about_content_editor() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$content = sweet_house_get_about_content();
	sweet_house_render_content_editor_notices( 'about' );
	?>
	<div class="wrap">
		<h1>محتوى صفحة من نحن</h1>
		<p>عدّل البانر والصور والنصوص.</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'sweet_house_save_about_content', 'sweet_house_about_nonce' ); ?>
			<input type="hidden" name="action" value="sweet_house_save_about_content" />

			<h2>البانر</h2>
			<table class="form-table">
				<tr>
					<th><label><?php esc_html_e( 'الصورة', 'sweet-house-theme' ); ?></label></th>
					<td><?php sweet_house_render_image_upload( 'banner_img', $content['banner_img'], 'assets/panner.png' ); ?></td>
				</tr>
			</table>

			<h2>البطاقة الأولى</h2>
			<table class="form-table">
				<tr>
					<th><label><?php esc_html_e( 'الصورة', 'sweet-house-theme' ); ?></label></th>
					<td><?php sweet_house_render_image_upload( 'about1_img', $content['about1_img'], 'assets/about1.png' ); ?></td>
				</tr>
				<tr>
					<th><label for="block1"><?php esc_html_e( 'النص', 'sweet-house-theme' ); ?></label></th>
					<td><textarea name="block1" id="block1" rows="4" class="large-text"><?php echo esc_textarea( $content['block1'] ); ?></textarea></td>
				</tr>
			</table>

			<h2>الإحصائيات</h2>
			<table class="form-table">
				<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
					<tr>
						<th><label for="stat<?php echo $i; ?>_num"><?php echo esc_html( sprintf( 'رقم %d', $i ) ); ?></label></th>
						<td><input type="text" name="stat<?php echo $i; ?>_num" id="stat<?php echo $i; ?>_num" value="<?php echo esc_attr( $content[ "stat{$i}_num" ] ); ?>" /> <input type="text" name="stat<?php echo $i; ?>_text" id="stat<?php echo $i; ?>_text" class="regular-text" value="<?php echo esc_attr( $content[ "stat{$i}_text" ] ); ?>" placeholder="النص" /></td>
					</tr>
				<?php endfor; ?>
			</table>

			<h2>البطاقة الثانية</h2>
			<table class="form-table">
				<tr>
					<th><label for="block2"><?php esc_html_e( 'النص', 'sweet-house-theme' ); ?></label></th>
					<td><textarea name="block2" id="block2" rows="4" class="large-text"><?php echo esc_textarea( $content['block2'] ); ?></textarea></td>
				</tr>
				<tr>
					<th><label><?php esc_html_e( 'الصورة', 'sweet-house-theme' ); ?></label></th>
					<td><?php sweet_house_render_image_upload( 'about2_img', $content['about2_img'], 'assets/about2.png' ); ?></td>
				</tr>
			</table>

			<p class="submit">
				<?php submit_button( __( 'حفظ', 'sweet-house-theme' ), 'primary', 'submit', false ); ?>
				&nbsp;
				<button type="submit" name="restore_default" value="1" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى الأصلي؟', 'sweet-house-theme' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'sweet-house-theme' ); ?></button>
			</p>
		</form>
	</div>
	<?php
	sweet_house_render_image_upload_script();
}

/**
 * عرض محرر صفحة السياسة (واحد لكل سياسة).
 */
function sweet_house_render_policy_editor() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$slug = 'refund-policy';
	if ( isset( $_GET['page'] ) ) {
		if ( 'sweet-house-policy-privacy' === $_GET['page'] ) {
			$slug = 'privacy-policy';
		} elseif ( 'sweet-house-policy-shipping' === $_GET['page'] ) {
			$slug = 'shipping-policy';
		} elseif ( 'sweet-house-policy-refund' === $_GET['page'] ) {
			$slug = 'refund-policy';
		}
	}
	$content = sweet_house_get_policy_content( $slug );
	$titles = array(
		'privacy-policy'  => 'سياسة الخصوصية',
		'refund-policy'   => 'سياسة الاسترجاع',
		'shipping-policy' => 'سياسة الشحن',
	);
	$title = isset( $titles[ $slug ] ) ? $titles[ $slug ] : $slug;
	sweet_house_render_content_editor_notices( 'policy_' . $slug );
	?>
	<div class="wrap">
		<h1>محتوى <?php echo esc_html( $title ); ?></h1>
		<p>عدّل البانر ومحتوى الصفحة.</p>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'sweet_house_save_policy_content', 'sweet_house_policy_nonce' ); ?>
			<input type="hidden" name="action" value="sweet_house_save_policy_content" />
			<input type="hidden" name="policy_slug" value="<?php echo esc_attr( $slug ); ?>" />

			<h2>البانر</h2>
			<table class="form-table">
				<tr>
					<th><label><?php esc_html_e( 'الصورة', 'sweet-house-theme' ); ?></label></th>
					<td><?php sweet_house_render_image_upload( 'banner_img', $content['banner_img'], 'assets/panner.png' ); ?></td>
				</tr>
			</table>

			<h2>الأقسام</h2>
			<?php
			$sections = isset( $content['sections'] ) && is_array( $content['sections'] ) ? $content['sections'] : array();
			if ( empty( $sections ) ) {
				$sections = sweet_house_default_policy_content( $slug )['sections'];
			}
			foreach ( $sections as $idx => $sec ) :
				$t = isset( $sec['title'] ) ? $sec['title'] : '';
				$c = isset( $sec['content'] ) ? $sec['content'] : '';
				?>
				<table class="form-table">
					<tr>
						<th><label><?php echo esc_html( sprintf( 'قسم %d - العنوان', $idx + 1 ) ); ?></label></th>
						<td><input type="text" name="sections[<?php echo $idx; ?>][title]" class="regular-text" value="<?php echo esc_attr( $t ); ?>" /></td>
					</tr>
					<tr>
						<th><label><?php echo esc_html( sprintf( 'قسم %d - النص', $idx + 1 ) ); ?></label></th>
						<td><textarea name="sections[<?php echo $idx; ?>][content]" rows="4" class="large-text"><?php echo esc_textarea( $c ); ?></textarea></td>
					</tr>
				</table>
			<?php endforeach; ?>

			<p class="submit">
				<?php submit_button( __( 'حفظ', 'sweet-house-theme' ), 'primary', 'submit', false ); ?>
				&nbsp;
				<button type="submit" name="restore_default" value="1" class="button" onclick="return confirm('<?php echo esc_js( __( 'استعادة المحتوى الأصلي؟', 'sweet-house-theme' ) ); ?>');"><?php esc_html_e( 'استعادة المحتوى الأصلي', 'sweet-house-theme' ); ?></button>
			</p>
		</form>
	</div>
	<?php
	sweet_house_render_image_upload_script();
}

/**
 * عرض حقل رفع صورة.
 */
function sweet_house_render_image_upload( $name, $attachment_id, $fallback_asset ) {
	$url = sweet_house_content_image_url( (int) $attachment_id, $fallback_asset );
	$id = (int) $attachment_id;
	?>
	<div class="sweet-house-image-upload" data-name="<?php echo esc_attr( $name ); ?>">
		<input type="hidden" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $id ); ?>" />
		<div class="image-preview" style="max-width:300px;margin-bottom:8px;">
			<?php if ( $url ) : ?>
				<img src="<?php echo esc_url( $url ); ?>" alt="" style="max-width:100%;height:auto;border:1px solid #ddd;" />
			<?php endif; ?>
		</div>
		<button type="button" class="button upload-image-btn"><?php esc_html_e( 'رفع صورة', 'sweet-house-theme' ); ?></button>
		<button type="button" class="button remove-image-btn" <?php echo $id ? '' : 'style="display:none;"'; ?>><?php esc_html_e( 'إزالة', 'sweet-house-theme' ); ?></button>
	</div>
	<?php
}

/**
 * سكربت رفع الصور.
 */
function sweet_house_render_image_upload_script() {
	?>
	<script>
	jQuery(function($) {
		$('.sweet-house-image-upload').each(function() {
			var $wrap = $(this);
			var $input = $wrap.find('input[type="hidden"]');
			var $preview = $wrap.find('.image-preview');
			var $upload = $wrap.find('.upload-image-btn');
			var $remove = $wrap.find('.remove-image-btn');

			$upload.on('click', function() {
				var frame = wp.media({
					library: { type: 'image' },
					multiple: false
				});
				frame.on('select', function() {
					var att = frame.state().get('selection').first().toJSON();
					$input.val(att.id);
					$preview.html('<img src="' + att.url + '" alt="" style="max-width:100%;height:auto;border:1px solid #ddd;" />');
					$remove.show();
				});
				frame.open();
			});

			$remove.on('click', function() {
				$input.val(0);
				$preview.empty();
				$remove.hide();
			});
		});
	});
	</script>
	<?php
}

/**
 * عرض إشعارات الحفظ.
 */
function sweet_house_render_content_editor_notices( $key ) {
	$saved = isset( $_GET['sweet_house_saved'] ) && $_GET['sweet_house_saved'] === $key;
	$restored = isset( $_GET['sweet_house_restored'] ) && $_GET['sweet_house_restored'] === $key;
	if ( $saved ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم الحفظ بنجاح.', 'sweet-house-theme' ) . '</p></div>';
	}
	if ( $restored ) {
		echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__( 'تم استعادة المحتوى الأصلي.', 'sweet-house-theme' ) . '</p></div>';
	}
}

/**
 * معالج حفظ الصفحة الرئيسية.
 */
function sweet_house_save_home_content_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'sweet_house_save_home_content', 'sweet_house_home_nonce' );

	if ( ! empty( $_POST['restore_default'] ) ) {
		delete_option( 'sweet_house_home_content' );
		wp_safe_redirect( add_query_arg( 'sweet_house_restored', 'home', admin_url( 'admin.php?page=sweet-house-home-content' ) ) );
		exit;
	}

	$fields = array(
		'hero_banner_img', 'hero_title', 'hero_subtitle1', 'hero_subtitle2',
		'mid_banner_img', 'categories_title', 'categories_subtitle', 'products_title',
		'about_title', 'about_subtitle', 'about_text', 'about_img1', 'about_img2',
		'feat1_icon', 'feat1_title', 'feat1_text', 'feat2_icon', 'feat2_title', 'feat2_text',
		'feat3_icon', 'feat3_title', 'feat3_text', 'feat4_icon', 'feat4_title', 'feat4_text',
	);
	$data = array();
	foreach ( $fields as $f ) {
		if ( isset( $_POST[ $f ] ) ) {
			$val = $_POST[ $f ];
			if ( strpos( $f, '_img' ) !== false || strpos( $f, '_icon' ) !== false ) {
				$data[ $f ] = absint( $val );
			} else {
				$data[ $f ] = sanitize_text_field( wp_unslash( $val ) );
				if ( 'about_text' === $f ) {
					$data[ $f ] = sanitize_textarea_field( wp_unslash( $_POST[ $f ] ) );
				}
			}
		}
	}
	update_option( 'sweet_house_home_content', $data );
	wp_safe_redirect( add_query_arg( 'sweet_house_saved', 'home', admin_url( 'admin.php?page=sweet-house-home-content' ) ) );
	exit;
}
add_action( 'admin_post_sweet_house_save_home_content', 'sweet_house_save_home_content_handler' );

/**
 * معالج حفظ صفحة من نحن.
 */
function sweet_house_save_about_content_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'sweet_house_save_about_content', 'sweet_house_about_nonce' );

	if ( ! empty( $_POST['restore_default'] ) ) {
		delete_option( 'sweet_house_about_content' );
		wp_safe_redirect( add_query_arg( 'sweet_house_restored', 'about', admin_url( 'admin.php?page=sweet-house-about-content' ) ) );
		exit;
	}

	$data = array(
		'banner_img' => absint( $_POST['banner_img'] ?? 0 ),
		'about1_img' => absint( $_POST['about1_img'] ?? 0 ),
		'about2_img' => absint( $_POST['about2_img'] ?? 0 ),
		'block1'    => isset( $_POST['block1'] ) ? sanitize_textarea_field( wp_unslash( $_POST['block1'] ) ) : '',
		'block2'    => isset( $_POST['block2'] ) ? sanitize_textarea_field( wp_unslash( $_POST['block2'] ) ) : '',
		'stat1_num' => isset( $_POST['stat1_num'] ) ? sanitize_text_field( wp_unslash( $_POST['stat1_num'] ) ) : '',
		'stat1_text' => isset( $_POST['stat1_text'] ) ? sanitize_text_field( wp_unslash( $_POST['stat1_text'] ) ) : '',
		'stat2_num' => isset( $_POST['stat2_num'] ) ? sanitize_text_field( wp_unslash( $_POST['stat2_num'] ) ) : '',
		'stat2_text' => isset( $_POST['stat2_text'] ) ? sanitize_text_field( wp_unslash( $_POST['stat2_text'] ) ) : '',
		'stat3_num' => isset( $_POST['stat3_num'] ) ? sanitize_text_field( wp_unslash( $_POST['stat3_num'] ) ) : '',
		'stat3_text' => isset( $_POST['stat3_text'] ) ? sanitize_text_field( wp_unslash( $_POST['stat3_text'] ) ) : '',
	);
	update_option( 'sweet_house_about_content', $data );
	wp_safe_redirect( add_query_arg( 'sweet_house_saved', 'about', admin_url( 'admin.php?page=sweet-house-about-content' ) ) );
	exit;
}
add_action( 'admin_post_sweet_house_save_about_content', 'sweet_house_save_about_content_handler' );

/**
 * معالج حفظ صفحة السياسة.
 */
function sweet_house_save_policy_content_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'sweet_house_save_policy_content', 'sweet_house_policy_nonce' );

	$slug = isset( $_POST['policy_slug'] ) ? sanitize_key( $_POST['policy_slug'] ) : 'refund-policy';
	$page = 'sweet-house-policy-refund';
	if ( 'privacy-policy' === $slug ) {
		$page = 'sweet-house-policy-privacy';
	} elseif ( 'shipping-policy' === $slug ) {
		$page = 'sweet-house-policy-shipping';
	}

	if ( ! empty( $_POST['restore_default'] ) ) {
		$all = get_option( 'sweet_house_policy_content', array() );
		unset( $all[ $slug ] );
		update_option( 'sweet_house_policy_content', $all );
		wp_safe_redirect( add_query_arg( 'sweet_house_restored', 'policy_' . $slug, admin_url( 'admin.php?page=' . $page ) ) );
		exit;
	}

	$sections = array();
	if ( ! empty( $_POST['sections'] ) && is_array( $_POST['sections'] ) ) {
		foreach ( $_POST['sections'] as $s ) {
			$sections[] = array(
				'title'   => isset( $s['title'] ) ? sanitize_text_field( wp_unslash( $s['title'] ) ) : '',
				'content' => isset( $s['content'] ) ? sanitize_textarea_field( wp_unslash( $s['content'] ) ) : '',
			);
		}
	}
	$all = get_option( 'sweet_house_policy_content', array() );
	$all[ $slug ] = array(
		'banner_img' => absint( $_POST['banner_img'] ?? 0 ),
		'sections'   => $sections,
	);
	update_option( 'sweet_house_policy_content', $all );
	wp_safe_redirect( add_query_arg( 'sweet_house_saved', 'policy_' . $slug, admin_url( 'admin.php?page=' . $page ) ) );
	exit;
}
add_action( 'admin_post_sweet_house_save_policy_content', 'sweet_house_save_policy_content_handler' );
