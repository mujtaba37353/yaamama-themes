<?php
/**
 * Theme Settings (Home, Contact, Static Pages, Page Creator)
 *
 * @package Beauty_Time_Theme
 */
defined( 'ABSPATH' ) || exit;

const BEAUTY_DEMO_SITE_OPTION     = 'beauty_demo_site_options';
const BEAUTY_CONTACT_OPTION       = 'beauty_contact_settings';
const BEAUTY_STATIC_PAGES_OPTION  = 'beauty_static_pages_content';
const BEAUTY_THEME_PAGES_OPTION   = 'beauty_theme_pages';

function beauty_demo_site_get_defaults() {
	$assets = array(
		'logo_header'     => beauty_time_asset( 'assets/navbar-icon.png' ),
		'logo_footer'     => beauty_time_asset( 'assets/navbar-icon.png' ),
		'logo_footer_alt' => beauty_time_asset( 'assets/footer-icon.png' ),
		'hero_image'      => beauty_time_asset( 'assets/hero.png' ),
		'hero_text_image' => beauty_time_asset( 'assets/hero-text.png' ),
	);

	return array(
		'colors' => array(
			'primary' => '#C67C73',
			'card'    => '#A88558',
			'hero_bg' => '#f3eae5',
			'text'    => '#0d0507',
		),
		'logos' => array(
			'header'     => $assets['logo_header'],
			'footer'     => $assets['logo_footer'],
			'footer_alt' => $assets['logo_footer_alt'],
		),
		'hero' => array(
			'text_image'  => $assets['hero_text_image'],
			'title'       => 'العناية بكم هى غايتنا',
			'description' => 'انغمسي في عالم الجمال والأناقة مع أفضل خدمات التجميل مع بيوتي تايم للتجميل',
			'button_text' => 'احجزي الآن',
			'button_link' => home_url( '/booking' ),
			'image'       => $assets['hero_image'],
		),
		'mid_banner' => array(
			array( 'icon' => 'fa-star',  'number' => '+75',  'label' => 'خدمات مميزة' ),
			array( 'icon' => 'fa-spa',   'number' => '+100', 'label' => 'منتجات اصلية' ),
			array( 'icon' => 'fa-leaf',  'number' => '+500', 'label' => 'منتجات طبيعية' ),
			array( 'icon' => 'fa-award', 'number' => '+20',  'label' => 'سنوات خبرة' ),
		),
		'footer' => array(
			'paragraph' => 'الجمال والأناقة في صالون بيوتي، نقدم لكِ أحدث صيحات الجمال وخدمات التجميل لإطلالة متألقة تبرز جمالك.',
		),
	);
}

function beauty_demo_site_get_options() {
	$defaults = beauty_demo_site_get_defaults();
	$stored   = get_option( BEAUTY_DEMO_SITE_OPTION, array() );
	if ( ! is_array( $stored ) ) {
		return $defaults;
	}
	return array_replace_recursive( $defaults, $stored );
}

function beauty_contact_settings_get_defaults() {
	return array(
		'recipient_email' => get_option( 'admin_email' ),
		'mailer_type'     => 'professional',
		'professional'    => array(
			'host'       => '',
			'port'       => '587',
			'encryption' => 'tls',
			'username'   => '',
			'password'   => '',
			'from_name'  => get_bloginfo( 'name' ),
			'from_email' => get_option( 'admin_email' ),
		),
		'gmail' => array(
			'username'   => '',
			'app_pass'   => '',
			'from_name'  => get_bloginfo( 'name' ),
			'from_email' => get_option( 'admin_email' ),
		),
		'info' => array(
			'phones'        => '+966 12 345 6789 - +966 12 345 6789',
			'email'         => 'info@beautytime.com',
			'address'       => 'الرياض - المملكة العربية السعودية',
			'working_hours' => 'طوال ايام الاسبوع : 2:00 ظهرًا – 10:45 مساءً',
			'map_embed'     => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3611.903336783503!2d46.70987737489967!3d24.68355808420423!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2f0487454990d9%3A0x3650175541c443c4!2sAlaliya%20Library!5e0!3m2!1sar!2ssa!4v1733935615825!5m2!1sar!2ssa',
		),
		'whatsapp' => array(
			'number' => '',
		),
	);
}

function beauty_contact_settings_get_options() {
	$defaults = beauty_contact_settings_get_defaults();
	$stored   = get_option( BEAUTY_CONTACT_OPTION, array() );
	if ( ! is_array( $stored ) ) {
		return $defaults;
	}
	return array_replace_recursive( $defaults, $stored );
}

function beauty_static_pages_get_defaults() {
	return array(
		'about' => array(
			'section_one_title'    => 'من نحن',
			'section_one_subtitle' => 'اكتشفي جمالك الحقيقي معنا في صالون بيوتي',
			'section_one_body'     => 'نحن في صالون " بيوتي " نؤمن بأن الجمال هو عنصر أساسي في الثقة بالنفس والسعادة. صالوننا هو مكان للتجميل والاسترخاء حيث نقدم لكن تجربة مميزة وفريدة في عالم الجمال والأناقة. بفضل فريقنا المختص والمحترف، نسعى لتحقيق أحلامكن وتلبية توقعاتكن الجمالية. نحن نقدم مجموعة واسعة من الخدمات، بما في ذلك تصفيف الشعر، العناية بالبشرة، العناية بالأظافر، والمكياج، وخدمات التجميل المتقدمة.',
			'section_two_title'    => 'لماذا تختارنا ؟؟',
			'section_two_subtitle' => 'أكثر من 15 سنة خبرة في مجال تجميل و العناية بالشعر و الأظافر',
			'section_two_body'     => 'نحن نعطي الأولوية لنظافة وراحة عميلاتنا، ونضمن لكِ بيئة صحية ومريحة طوال زيارتك، يلتزم صالوننا بتعقيم أدواتنا وبجميع بروتوكولات التعقيم الصارمة، وأدوات الاستخدام الواحد',
			'features'             => array(
				array(
					'icon'  => 'fa-hand-sparkles',
					'title' => 'أحدث تقنيات العناية بالأظافر',
					'body'  => 'من خدمات تجميل الأظافر والباديكير الكلاسيكية إلى فن الأظافر المعقد ووصلات الأظافر المخصصة',
				),
				array(
					'icon'  => 'fa-scissors',
					'title' => 'منتجات عالية الجودة',
					'body'  => 'نحرص في صالون بيوتي على استخدام منتجات عالية الجودة ومواد ذات مستوى عالي من الفعالية والأمان لضمان الحصول على نتائج مذهلة وصحية للعملاء.',
				),
				array(
					'icon'  => 'fa-user-check',
					'title' => 'خبرة ومهارة',
					'body'  => 'نحرص في صالون بيوتي على استخدام منتجات عالية الجودة ومواد ذات مستوى عالي من الفعالية والأمان لضمان الحصول على نتائج مذهلة وصحية للعملاء.',
				),
			),
		),
		'privacy' => array(
			'sections' => array(
				array(
					'title' => 'سياسية الاسترجاع والاسترداد',
					'body'  => 'نعمل دائما لنيل رضاكم ونكون عند حسن ظنكم بنا. إذا كنت ترغب في إرجاع منتج ما، فنحن نقبل بسرور استبدال المنتج أو منحك رصيداً في المتجر أو إرجاع المنتج مقابل نقاط متجر الأحمدي. في حال طلب استرجاع أي منتج، يرجى التواصل معنا عبر البريد الإلكتروني: care@aahmadi.sa أو الهاتف أو الواتساب: 966534411732+',
					'image' => '',
				),
				array(
					'title' => 'الدفع عبر الإنترنت:',
					'body'  => 'ستتم معالجة المبالغ المستردة في غضون ٢٤ ساعة وستضاف إلى حساب العميل في غضون 3-5 أيام عمل، اعتمادًا على مصدر البنك.',
					'image' => '',
				),
				array(
					'title' => 'الدفع نقدا عند التسليم:',
					'body'  => 'ستُضاف المبالغ المستردة إلى حساب العميل كنقاط متجر الأحمدي ويمكن استخدامها في الطلب التالي.',
					'image' => '',
				),
				array(
					'title' => 'سياسة الشحن',
					'body'  => 'يتم الشحن خلال اليوم لكل الطلبات داخل المدينة المنورة، اما كل الطلبات داخل السعودية وخارج المدينة المنورة يستغرق الشحن من يوم الى ثلاثة ايام عمل.',
					'image' => '',
				),
			),
		),
		'terms' => array(
			'sections' => array(
				array(
					'title' => 'سياسة الاستخدام',
					'body'  => 'باستخدامك هذا الموقع فإنك توافق على الالتزام بجميع الشروط والأحكام الموضحة. يرجى مراجعة هذه السياسة بشكل دوري لمعرفة أي تحديثات.',
					'image' => '',
				),
				array(
					'title' => 'المحتوى والخدمات',
					'body'  => 'جميع المحتويات المقدمة لأغراض التعريف بالخدمات، وقد تتغير دون إشعار مسبق.',
					'image' => '',
				),
				array(
					'title' => 'الخصوصية',
					'body'  => 'نلتزم بحماية بياناتك وفق سياسة الخصوصية المعتمدة على الموقع.',
					'image' => '',
				),
			),
		),
	);
}

function beauty_static_pages_get_options() {
	$defaults = beauty_static_pages_get_defaults();
	$stored   = get_option( BEAUTY_STATIC_PAGES_OPTION, array() );
	if ( ! is_array( $stored ) ) {
		return $defaults;
	}
	return array_replace_recursive( $defaults, $stored );
}

function beauty_theme_pages_get_definitions() {
	return array(
		'services' => array(
			'title'    => 'الاقسام',
			'slug'     => 'services',
			'template' => 'page-templates/services.php',
		),
		'onsale' => array(
			'title'    => 'العروض والباقات',
			'slug'     => 'onsale',
			'template' => 'page-templates/onsale.php',
		),
		'booking' => array(
			'title'    => 'الحجز',
			'slug'     => 'booking',
			'template' => 'page-templates/booking.php',
		),
		'booking_success' => array(
			'title'    => 'تأكيد الحجز',
			'slug'     => 'booking-success',
			'template' => 'page-templates/booking-success.php',
		),
		'contact' => array(
			'title'    => 'تواصل معنا',
			'slug'     => 'contact',
			'template' => 'page-templates/contact.php',
		),
		'privacy' => array(
			'title'    => 'سياسة الخصوصية',
			'slug'     => 'privacy-policy',
			'template' => 'page-templates/privacy-policy.php',
		),
		'about' => array(
			'title'    => 'من نحن',
			'slug'     => 'about-us',
			'template' => 'page-templates/about-us.php',
		),
		'terms' => array(
			'title'    => 'سياسة الاستخدام',
			'slug'     => 'terms-of-use',
			'template' => 'page-templates/terms-of-use.php',
		),
	);
}

function beauty_theme_get_page_link( $key, $fallback ) {
	$pages = get_option( BEAUTY_THEME_PAGES_OPTION, array() );
	$page_id = isset( $pages[ $key ] ) ? absint( $pages[ $key ] ) : 0;
	if ( $page_id ) {
		$link = get_permalink( $page_id );
		if ( $link ) {
			return $link;
		}
	}
	return $fallback;
}

function beauty_theme_settings_menu() {
	add_menu_page(
		__( 'إعدادات القالب', 'beauty-time-theme' ),
		__( 'إعدادات القالب', 'beauty-time-theme' ),
		'manage_options',
		'beauty-theme-settings',
		'beauty_theme_settings_redirect',
		'dashicons-admin-customizer',
		59
	);

	add_submenu_page(
		'beauty-theme-settings',
		__( 'الصفحة الرئيسية', 'beauty-time-theme' ),
		__( 'الصفحة الرئيسية', 'beauty-time-theme' ),
		'manage_options',
		'beauty-theme-home',
		'beauty_theme_home_settings_page'
	);

	add_submenu_page(
		'beauty-theme-settings',
		__( 'إعدادات التواصل', 'beauty-time-theme' ),
		__( 'إعدادات التواصل', 'beauty-time-theme' ),
		'manage_options',
		'beauty-theme-contact',
		'beauty_theme_contact_settings_page'
	);

	add_submenu_page(
		'beauty-theme-settings',
		__( 'من نحن', 'beauty-time-theme' ),
		__( 'من نحن', 'beauty-time-theme' ),
		'manage_options',
		'beauty-theme-about',
		'beauty_theme_about_settings_page'
	);

	add_submenu_page(
		'beauty-theme-settings',
		__( 'سياسة الخصوصية', 'beauty-time-theme' ),
		__( 'سياسة الخصوصية', 'beauty-time-theme' ),
		'manage_options',
		'beauty-theme-privacy',
		'beauty_theme_privacy_settings_page'
	);

	add_submenu_page(
		'beauty-theme-settings',
		__( 'سياسة الاستخدام', 'beauty-time-theme' ),
		__( 'سياسة الاستخدام', 'beauty-time-theme' ),
		'manage_options',
		'beauty-theme-terms',
		'beauty_theme_terms_settings_page'
	);

	add_menu_page(
		__( 'إنشاء صفحات القالب', 'beauty-time-theme' ),
		__( 'إنشاء صفحات القالب', 'beauty-time-theme' ),
		'manage_options',
		'beauty-theme-pages',
		'beauty_theme_pages_admin_page',
		'dashicons-admin-page',
		60
	);

	remove_submenu_page( 'beauty-theme-settings', 'beauty-theme-settings' );
}
add_action( 'admin_menu', 'beauty_theme_settings_menu' );

function beauty_theme_settings_redirect() {
	wp_safe_redirect( admin_url( 'admin.php?page=beauty-theme-home' ) );
	exit;
}

function beauty_theme_settings_admin_assets( $hook ) {
	if ( false === strpos( $hook, 'beauty-theme' ) ) {
		return;
	}
	wp_enqueue_media();
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_add_inline_script(
		'wp-color-picker',
		<<<'JS'
jQuery(function($){
  $('.beauty-color-field').wpColorPicker();
  $(document).on('click', '.beauty-media-upload', function(e){
    e.preventDefault();
    var button = $(this);
    var target = $('#' + button.data('target'));
    var previewId = button.data('preview');
    var preview = previewId ? $('#' + previewId) : null;
    var frame = wp.media({title:'اختر صورة',button:{text:'اختيار'},multiple:false});
    frame.on('select', function(){
      var attachment = frame.state().get('selection').first().toJSON();
      target.val(attachment.url);
      if(preview && preview.length){
        preview.attr('src', attachment.url).show();
      }
    });
    frame.open();
  });
});
JS
	);
}
add_action( 'admin_enqueue_scripts', 'beauty_theme_settings_admin_assets' );

function beauty_theme_render_media_field( $id, $name, $label, $value ) {
	$preview_id = $id . '_preview';
	echo '<tr><th><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label></th><td>';
	echo '<input type="text" id="' . esc_attr( $id ) . '" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" class="regular-text" readonly /> ';
	echo '<button class="button beauty-media-upload" data-target="' . esc_attr( $id ) . '" data-preview="' . esc_attr( $preview_id ) . '">' . esc_html__( 'رفع', 'beauty-time-theme' ) . '</button>';
	echo '<div style="margin-top:8px;">';
	echo '<img id="' . esc_attr( $preview_id ) . '" src="' . esc_url( $value ) . '" alt="" style="max-width:140px;height:auto;' . ( $value ? '' : 'display:none;' ) . '">';
	echo '</div></td></tr>';
}

function beauty_theme_home_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notice = '';
	$action = isset( $_POST['beauty_demo_action'] ) ? sanitize_text_field( wp_unslash( $_POST['beauty_demo_action'] ) ) : '';

	if ( 'reset' === $action && isset( $_POST['beauty_demo_site_reset_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['beauty_demo_site_reset_nonce'] ) ), 'beauty_demo_site_reset' ) ) {
		update_option( BEAUTY_DEMO_SITE_OPTION, beauty_demo_site_get_defaults(), false );
		$notice = __( 'تمت استعادة الإعدادات الافتراضية.', 'beauty-time-theme' );
	} elseif ( isset( $_POST['beauty_demo_site_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['beauty_demo_site_nonce'] ) ), 'beauty_demo_site_save' ) ) {
		$payload = array(
			'colors' => array(
				'primary' => sanitize_hex_color( wp_unslash( $_POST['color_primary'] ?? '' ) ),
				'card'    => sanitize_hex_color( wp_unslash( $_POST['color_card'] ?? '' ) ),
				'hero_bg' => sanitize_hex_color( wp_unslash( $_POST['color_hero_bg'] ?? '' ) ),
				'text'    => sanitize_hex_color( wp_unslash( $_POST['color_text'] ?? '' ) ),
			),
			'logos' => array(
				'header'     => esc_url_raw( wp_unslash( $_POST['logo_header'] ?? '' ) ),
				'footer'     => esc_url_raw( wp_unslash( $_POST['logo_footer'] ?? '' ) ),
				'footer_alt' => esc_url_raw( wp_unslash( $_POST['logo_footer_alt'] ?? '' ) ),
			),
			'hero' => array(
				'text_image'  => esc_url_raw( wp_unslash( $_POST['hero_text_image'] ?? '' ) ),
				'title'       => sanitize_text_field( wp_unslash( $_POST['hero_title'] ?? '' ) ),
				'description' => sanitize_textarea_field( wp_unslash( $_POST['hero_desc'] ?? '' ) ),
				'button_text' => sanitize_text_field( wp_unslash( $_POST['hero_button_text'] ?? '' ) ),
				'button_link' => esc_url_raw( wp_unslash( $_POST['hero_button_link'] ?? '' ) ),
				'image'       => esc_url_raw( wp_unslash( $_POST['hero_image'] ?? '' ) ),
			),
			'mid_banner' => array(),
			'footer' => array(
				'paragraph' => sanitize_textarea_field( wp_unslash( $_POST['footer_paragraph'] ?? '' ) ),
			),
		);

		for ( $i = 0; $i < 4; $i++ ) {
			$payload['mid_banner'][] = array(
				'icon'   => sanitize_text_field( wp_unslash( $_POST[ "mid_icon_{$i}" ] ?? '' ) ),
				'number' => sanitize_text_field( wp_unslash( $_POST[ "mid_number_{$i}" ] ?? '' ) ),
				'label'  => sanitize_text_field( wp_unslash( $_POST[ "mid_label_{$i}" ] ?? '' ) ),
			);
		}

		update_option( BEAUTY_DEMO_SITE_OPTION, $payload, false );
		$notice = __( 'تم حفظ إعدادات الصفحة الرئيسية.', 'beauty-time-theme' );
	}

	$options = beauty_demo_site_get_options();
	$colors  = $options['colors'];
	$logos   = $options['logos'];
	$hero    = $options['hero'];
	$mid     = $options['mid_banner'];
	$footer  = $options['footer'];

	echo '<div class="wrap" dir="rtl" style="text-align: right;">';
	echo '<h1>' . esc_html__( 'إعدادات القالب - الصفحة الرئيسية', 'beauty-time-theme' ) . '</h1>';
	if ( $notice ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $notice ) . '</p></div>';
	}
	echo '<form method="post">';
	wp_nonce_field( 'beauty_demo_site_save', 'beauty_demo_site_nonce' );

	echo '<h2>' . esc_html__( 'الألوان', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th><label for="color_primary">' . esc_html__( 'اللون الأساسي', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="color_primary" name="color_primary" value="' . esc_attr( $colors['primary'] ) . '" class="beauty-color-field" /></td></tr>';
	echo '<tr><th><label for="color_card">' . esc_html__( 'لون بطاقة القسم', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="color_card" name="color_card" value="' . esc_attr( $colors['card'] ) . '" class="beauty-color-field" /></td></tr>';
	echo '<tr><th><label for="color_hero_bg">' . esc_html__( 'لون خلفية الهيرو', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="color_hero_bg" name="color_hero_bg" value="' . esc_attr( $colors['hero_bg'] ) . '" class="beauty-color-field" /></td></tr>';
	echo '<tr><th><label for="color_text">' . esc_html__( 'لون الخط الأساسي', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="color_text" name="color_text" value="' . esc_attr( $colors['text'] ) . '" class="beauty-color-field" /></td></tr>';
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'الشعارات', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	beauty_theme_render_media_field( 'logo_header', 'logo_header', __( 'شعار الهيدر', 'beauty-time-theme' ), $logos['header'] );
	beauty_theme_render_media_field( 'logo_footer', 'logo_footer', __( 'شعار الفوتر', 'beauty-time-theme' ), $logos['footer'] );
	beauty_theme_render_media_field( 'logo_footer_alt', 'logo_footer_alt', __( 'شعار الفوتر الإضافي', 'beauty-time-theme' ), $logos['footer_alt'] );
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'الهيرو', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	beauty_theme_render_media_field( 'hero_text_image', 'hero_text_image', __( 'صورة نص الهيرو', 'beauty-time-theme' ), $hero['text_image'] );
	echo '<tr><th><label for="hero_title">' . esc_html__( 'عنوان الهيرو', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="hero_title" name="hero_title" value="' . esc_attr( $hero['title'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="hero_desc">' . esc_html__( 'وصف الهيرو', 'beauty-time-theme' ) . '</label></th><td><textarea id="hero_desc" name="hero_desc" rows="3" class="large-text">' . esc_textarea( $hero['description'] ) . '</textarea></td></tr>';
	echo '<tr><th><label for="hero_button_text">' . esc_html__( 'نص زر الهيرو', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="hero_button_text" name="hero_button_text" value="' . esc_attr( $hero['button_text'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="hero_button_link">' . esc_html__( 'رابط زر الهيرو', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="hero_button_link" name="hero_button_link" value="' . esc_attr( $hero['button_link'] ) . '" class="regular-text" /></td></tr>';
	beauty_theme_render_media_field( 'hero_image', 'hero_image', __( 'صورة الهيرو', 'beauty-time-theme' ), $hero['image'] );
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'البنر الأوسط', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	for ( $i = 0; $i < 4; $i++ ) {
		$item = $mid[ $i ] ?? array( 'icon' => '', 'number' => '', 'label' => '' );
		echo '<tr><th><label>' . esc_html( sprintf( 'عنصر %d', $i + 1 ) ) . '</label></th><td>';
		echo '<input type="text" name="mid_icon_' . $i . '" value="' . esc_attr( $item['icon'] ) . '" placeholder="fa-star" class="regular-text" /> ';
		echo '<input type="text" name="mid_number_' . $i . '" value="' . esc_attr( $item['number'] ) . '" placeholder="+75" class="regular-text" /> ';
		echo '<input type="text" name="mid_label_' . $i . '" value="' . esc_attr( $item['label'] ) . '" placeholder="خدمات مميزة" class="regular-text" />';
		echo '</td></tr>';
	}
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'الفوتر', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th><label for="footer_paragraph">' . esc_html__( 'فقرة الفوتر', 'beauty-time-theme' ) . '</label></th><td><textarea id="footer_paragraph" name="footer_paragraph" rows="3" class="large-text">' . esc_textarea( $footer['paragraph'] ) . '</textarea></td></tr>';
	echo '</tbody></table>';

	submit_button( __( 'حفظ', 'beauty-time-theme' ) );
	echo '</form>';

	echo '<form method="post" style="margin-top:16px;">';
	wp_nonce_field( 'beauty_demo_site_reset', 'beauty_demo_site_reset_nonce' );
	echo '<input type="hidden" name="beauty_demo_action" value="reset">';
	submit_button( __( 'إعادة الإعدادات الافتراضية', 'beauty-time-theme' ), 'secondary' );
	echo '</form>';
	echo '</div>';
}

function beauty_theme_contact_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notice = '';
	$options = beauty_contact_settings_get_options();

	if ( isset( $_POST['beauty_contact_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['beauty_contact_nonce'] ) ), 'beauty_contact_save' ) ) {
		$payload = array(
			'recipient_email' => sanitize_email( wp_unslash( $_POST['recipient_email'] ?? '' ) ),
			'mailer_type'     => sanitize_text_field( wp_unslash( $_POST['mailer_type'] ?? 'professional' ) ),
			'professional'    => array(
				'host'       => sanitize_text_field( wp_unslash( $_POST['smtp_host'] ?? '' ) ),
				'port'       => sanitize_text_field( wp_unslash( $_POST['smtp_port'] ?? '' ) ),
				'encryption' => sanitize_text_field( wp_unslash( $_POST['smtp_encryption'] ?? '' ) ),
				'username'   => sanitize_text_field( wp_unslash( $_POST['smtp_username'] ?? '' ) ),
				'password'   => sanitize_text_field( wp_unslash( $_POST['smtp_password'] ?? '' ) ),
				'from_name'  => sanitize_text_field( wp_unslash( $_POST['smtp_from_name'] ?? '' ) ),
				'from_email' => sanitize_email( wp_unslash( $_POST['smtp_from_email'] ?? '' ) ),
			),
			'gmail' => array(
				'username'   => sanitize_text_field( wp_unslash( $_POST['gmail_username'] ?? '' ) ),
				'app_pass'   => sanitize_text_field( wp_unslash( $_POST['gmail_app_pass'] ?? '' ) ),
				'from_name'  => sanitize_text_field( wp_unslash( $_POST['gmail_from_name'] ?? '' ) ),
				'from_email' => sanitize_email( wp_unslash( $_POST['gmail_from_email'] ?? '' ) ),
			),
			'info' => array(
				'phones'        => sanitize_text_field( wp_unslash( $_POST['contact_phones'] ?? '' ) ),
				'email'         => sanitize_email( wp_unslash( $_POST['contact_email'] ?? '' ) ),
				'address'       => sanitize_text_field( wp_unslash( $_POST['contact_address'] ?? '' ) ),
				'working_hours' => sanitize_text_field( wp_unslash( $_POST['contact_working_hours'] ?? '' ) ),
				'map_embed'     => esc_url_raw( wp_unslash( $_POST['contact_map_embed'] ?? '' ) ),
			),
			'whatsapp' => array(
				'number' => sanitize_text_field( wp_unslash( $_POST['whatsapp_number'] ?? '' ) ),
			),
		);

		update_option( BEAUTY_CONTACT_OPTION, $payload, false );
		$options = beauty_contact_settings_get_options();
		$notice = __( 'تم حفظ إعدادات التواصل.', 'beauty-time-theme' );

		if ( isset( $_POST['beauty_test_email'] ) ) {
			$test_email = sanitize_email( wp_unslash( $_POST['test_email'] ?? '' ) );
			if ( ! $test_email ) {
				$test_email = get_option( 'admin_email' );
			}
			$sent = wp_mail(
				$test_email,
				__( 'رسالة اختبار - إعدادات البريد', 'beauty-time-theme' ),
				__( 'هذه رسالة اختبار للتأكد من إعدادات البريد.', 'beauty-time-theme' )
			);
			if ( $sent ) {
				$notice = __( 'تم إرسال البريد التجريبي بنجاح.', 'beauty-time-theme' );
			} else {
				$notice = __( 'تعذر إرسال البريد التجريبي. يرجى مراجعة الإعدادات.', 'beauty-time-theme' );
			}
		}
	}

	echo '<div class="wrap" dir="rtl" style="text-align: right;">';
	echo '<h1>' . esc_html__( 'إعدادات التواصل', 'beauty-time-theme' ) . '</h1>';
	if ( $notice ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $notice ) . '</p></div>';
	}

	echo '<form method="post">';
	wp_nonce_field( 'beauty_contact_save', 'beauty_contact_nonce' );
	echo '<h2>' . esc_html__( 'بريد استقبال نموذج التواصل', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th><label for="recipient_email">' . esc_html__( 'البريد المستلم', 'beauty-time-theme' ) . '</label></th><td><input type="email" id="recipient_email" name="recipient_email" value="' . esc_attr( $options['recipient_email'] ) . '" class="regular-text" /></td></tr>';
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'نوع البريد', 'beauty-time-theme' ) . '</h2>';
	echo '<fieldset style="margin-bottom:16px;">';
	echo '<label style="margin-left:16px;"><input type="radio" name="mailer_type" value="professional" ' . checked( $options['mailer_type'], 'professional', false ) . '> ' . esc_html__( 'بريد احترافي (SMTP)', 'beauty-time-theme' ) . '</label>';
	echo '<label><input type="radio" name="mailer_type" value="gmail" ' . checked( $options['mailer_type'], 'gmail', false ) . '> ' . esc_html__( 'بريد Gmail (App Password)', 'beauty-time-theme' ) . '</label>';
	echo '</fieldset>';

	echo '<h2>' . esc_html__( 'إعدادات البريد الاحترافي', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th><label for="smtp_host">SMTP Host</label></th><td><input type="text" id="smtp_host" name="smtp_host" value="' . esc_attr( $options['professional']['host'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="smtp_port">Port</label></th><td><input type="text" id="smtp_port" name="smtp_port" value="' . esc_attr( $options['professional']['port'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="smtp_encryption">Encryption</label></th><td><select id="smtp_encryption" name="smtp_encryption"><option value="tls"' . selected( $options['professional']['encryption'], 'tls', false ) . '>TLS</option><option value="ssl"' . selected( $options['professional']['encryption'], 'ssl', false ) . '>SSL</option><option value="none"' . selected( $options['professional']['encryption'], 'none', false ) . '>' . esc_html__( 'بدون', 'beauty-time-theme' ) . '</option></select></td></tr>';
	echo '<tr><th><label for="smtp_username">Username</label></th><td><input type="text" id="smtp_username" name="smtp_username" value="' . esc_attr( $options['professional']['username'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="smtp_password">Password</label></th><td><input type="password" id="smtp_password" name="smtp_password" value="' . esc_attr( $options['professional']['password'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="smtp_from_name">' . esc_html__( 'From Name', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="smtp_from_name" name="smtp_from_name" value="' . esc_attr( $options['professional']['from_name'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="smtp_from_email">' . esc_html__( 'From Email', 'beauty-time-theme' ) . '</label></th><td><input type="email" id="smtp_from_email" name="smtp_from_email" value="' . esc_attr( $options['professional']['from_email'] ) . '" class="regular-text" /></td></tr>';
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'إعدادات Gmail', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th><label for="gmail_username">' . esc_html__( 'البريد الإلكتروني', 'beauty-time-theme' ) . '</label></th><td><input type="email" id="gmail_username" name="gmail_username" value="' . esc_attr( $options['gmail']['username'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="gmail_app_pass">' . esc_html__( 'App Password', 'beauty-time-theme' ) . '</label></th><td><input type="password" id="gmail_app_pass" name="gmail_app_pass" value="' . esc_attr( $options['gmail']['app_pass'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="gmail_from_name">' . esc_html__( 'From Name', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="gmail_from_name" name="gmail_from_name" value="' . esc_attr( $options['gmail']['from_name'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="gmail_from_email">' . esc_html__( 'From Email', 'beauty-time-theme' ) . '</label></th><td><input type="email" id="gmail_from_email" name="gmail_from_email" value="' . esc_attr( $options['gmail']['from_email'] ) . '" class="regular-text" /></td></tr>';
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'محتوى صفحة تواصل معنا', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th><label for="contact_phones">' . esc_html__( 'أرقام الهاتف', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="contact_phones" name="contact_phones" value="' . esc_attr( $options['info']['phones'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="contact_email">' . esc_html__( 'البريد الإلكتروني', 'beauty-time-theme' ) . '</label></th><td><input type="email" id="contact_email" name="contact_email" value="' . esc_attr( $options['info']['email'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="contact_address">' . esc_html__( 'العنوان', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="contact_address" name="contact_address" value="' . esc_attr( $options['info']['address'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="contact_working_hours">' . esc_html__( 'أوقات العمل', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="contact_working_hours" name="contact_working_hours" value="' . esc_attr( $options['info']['working_hours'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="contact_map_embed">' . esc_html__( 'رابط الخريطة (Embed)', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="contact_map_embed" name="contact_map_embed" value="' . esc_attr( $options['info']['map_embed'] ) . '" class="large-text" /></td></tr>';
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'زر واتساب العائم', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th><label for="whatsapp_number">' . esc_html__( 'رقم واتساب', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="whatsapp_number" name="whatsapp_number" value="' . esc_attr( $options['whatsapp']['number'] ) . '" class="regular-text" /></td></tr>';
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'تجربة البريد', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	echo '<tr><th><label for="test_email">' . esc_html__( 'إرسال إلى', 'beauty-time-theme' ) . '</label></th><td><input type="email" id="test_email" name="test_email" value="' . esc_attr( get_option( 'admin_email' ) ) . '" class="regular-text" /></td></tr>';
	echo '</tbody></table>';

	submit_button( __( 'حفظ', 'beauty-time-theme' ) );
	echo '<button type="submit" name="beauty_test_email" value="1" class="button button-secondary">' . esc_html__( 'إرسال بريد تجريبي', 'beauty-time-theme' ) . '</button>';
	echo '</form></div>';
}

function beauty_theme_about_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notice  = '';
	$options = beauty_static_pages_get_options();

	if ( isset( $_POST['beauty_about_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['beauty_about_nonce'] ) ), 'beauty_about_save' ) ) {
		$features = array();
		for ( $i = 0; $i < 3; $i++ ) {
			$features[] = array(
				'icon'  => sanitize_text_field( wp_unslash( $_POST[ "about_feature_icon_{$i}" ] ?? '' ) ),
				'title' => sanitize_text_field( wp_unslash( $_POST[ "about_feature_title_{$i}" ] ?? '' ) ),
				'body'  => sanitize_textarea_field( wp_unslash( $_POST[ "about_feature_body_{$i}" ] ?? '' ) ),
			);
		}

		$options['about'] = array(
			'section_one_title'    => sanitize_text_field( wp_unslash( $_POST['about_section_one_title'] ?? '' ) ),
			'section_one_subtitle' => sanitize_text_field( wp_unslash( $_POST['about_section_one_subtitle'] ?? '' ) ),
			'section_one_body'     => sanitize_textarea_field( wp_unslash( $_POST['about_section_one_body'] ?? '' ) ),
			'section_two_title'    => sanitize_text_field( wp_unslash( $_POST['about_section_two_title'] ?? '' ) ),
			'section_two_subtitle' => sanitize_text_field( wp_unslash( $_POST['about_section_two_subtitle'] ?? '' ) ),
			'section_two_body'     => sanitize_textarea_field( wp_unslash( $_POST['about_section_two_body'] ?? '' ) ),
			'features'             => $features,
		);

		update_option( BEAUTY_STATIC_PAGES_OPTION, $options, false );
		$notice = __( 'تم حفظ محتوى صفحة من نحن.', 'beauty-time-theme' );
	}

	$about = $options['about'];

	echo '<div class="wrap" dir="rtl" style="text-align: right;">';
	echo '<h1>' . esc_html__( 'محتوى صفحة من نحن', 'beauty-time-theme' ) . '</h1>';
	if ( $notice ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $notice ) . '</p></div>';
	}
	echo '<form method="post">';
	wp_nonce_field( 'beauty_about_save', 'beauty_about_nonce' );
	echo '<table class="form-table"><tbody>';
	echo '<tr><th><label for="about_section_one_title">' . esc_html__( 'عنوان القسم الأول', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="about_section_one_title" name="about_section_one_title" value="' . esc_attr( $about['section_one_title'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="about_section_one_subtitle">' . esc_html__( 'سطر تمهيدي', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="about_section_one_subtitle" name="about_section_one_subtitle" value="' . esc_attr( $about['section_one_subtitle'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="about_section_one_body">' . esc_html__( 'فقرة القسم الأول', 'beauty-time-theme' ) . '</label></th><td><textarea id="about_section_one_body" name="about_section_one_body" rows="4" class="large-text">' . esc_textarea( $about['section_one_body'] ) . '</textarea></td></tr>';
	echo '<tr><th><label for="about_section_two_title">' . esc_html__( 'عنوان القسم الثاني', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="about_section_two_title" name="about_section_two_title" value="' . esc_attr( $about['section_two_title'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="about_section_two_subtitle">' . esc_html__( 'سطر تمهيدي', 'beauty-time-theme' ) . '</label></th><td><input type="text" id="about_section_two_subtitle" name="about_section_two_subtitle" value="' . esc_attr( $about['section_two_subtitle'] ) . '" class="regular-text" /></td></tr>';
	echo '<tr><th><label for="about_section_two_body">' . esc_html__( 'فقرة القسم الثاني', 'beauty-time-theme' ) . '</label></th><td><textarea id="about_section_two_body" name="about_section_two_body" rows="4" class="large-text">' . esc_textarea( $about['section_two_body'] ) . '</textarea></td></tr>';
	echo '</tbody></table>';

	echo '<h2>' . esc_html__( 'نقاط التميز', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="form-table"><tbody>';
	for ( $i = 0; $i < 3; $i++ ) {
		$item = $about['features'][ $i ] ?? array( 'icon' => '', 'title' => '', 'body' => '' );
		echo '<tr><th><label>' . esc_html( sprintf( 'عنصر %d', $i + 1 ) ) . '</label></th><td>';
		echo '<input type="text" name="about_feature_icon_' . $i . '" value="' . esc_attr( $item['icon'] ) . '" placeholder="fa-star" class="regular-text" /> ';
		echo '<input type="text" name="about_feature_title_' . $i . '" value="' . esc_attr( $item['title'] ) . '" placeholder="عنوان" class="regular-text" /><br>';
		echo '<textarea name="about_feature_body_' . $i . '" rows="3" class="large-text" style="margin-top:6px;">' . esc_textarea( $item['body'] ) . '</textarea>';
		echo '</td></tr>';
	}
	echo '</tbody></table>';
	submit_button( __( 'حفظ', 'beauty-time-theme' ) );
	echo '</form></div>';
}

function beauty_theme_privacy_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notice  = '';
	$options = beauty_static_pages_get_options();

	if ( isset( $_POST['beauty_privacy_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['beauty_privacy_nonce'] ) ), 'beauty_privacy_save' ) ) {
		$sections = array();
		for ( $i = 0; $i < 4; $i++ ) {
			$sections[] = array(
				'title' => sanitize_text_field( wp_unslash( $_POST[ "privacy_title_{$i}" ] ?? '' ) ),
				'body'  => sanitize_textarea_field( wp_unslash( $_POST[ "privacy_body_{$i}" ] ?? '' ) ),
				'image' => esc_url_raw( wp_unslash( $_POST[ "privacy_image_{$i}" ] ?? '' ) ),
			);
		}
		$options['privacy']['sections'] = $sections;
		update_option( BEAUTY_STATIC_PAGES_OPTION, $options, false );
		$notice = __( 'تم حفظ سياسة الخصوصية.', 'beauty-time-theme' );
	}

	echo '<div class="wrap" dir="rtl" style="text-align: right;">';
	echo '<h1>' . esc_html__( 'محتوى سياسة الخصوصية', 'beauty-time-theme' ) . '</h1>';
	if ( $notice ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $notice ) . '</p></div>';
	}
	echo '<form method="post">';
	wp_nonce_field( 'beauty_privacy_save', 'beauty_privacy_nonce' );
	echo '<table class="form-table"><tbody>';
	$privacy_sections = $options['privacy']['sections'] ?? array();
	for ( $i = 0; $i < 4; $i++ ) {
		$item = $privacy_sections[ $i ] ?? array( 'title' => '', 'body' => '', 'image' => '' );
		echo '<tr><th><label>' . esc_html( sprintf( 'فقرة %d', $i + 1 ) ) . '</label></th><td>';
		echo '<input type="text" name="privacy_title_' . $i . '" value="' . esc_attr( $item['title'] ) . '" placeholder="العنوان" class="regular-text" /><br>';
		echo '<textarea name="privacy_body_' . $i . '" rows="4" class="large-text" style="margin-top:6px;">' . esc_textarea( $item['body'] ) . '</textarea>';
		echo '<div style="margin-top:8px;">';
		echo '<input type="text" id="privacy_image_' . $i . '" name="privacy_image_' . $i . '" value="' . esc_attr( $item['image'] ) . '" class="regular-text" readonly /> ';
		echo '<button class="button beauty-media-upload" data-target="privacy_image_' . $i . '" data-preview="privacy_image_' . $i . '_preview">' . esc_html__( 'رفع صورة', 'beauty-time-theme' ) . '</button>';
		echo '<div style="margin-top:8px;"><img id="privacy_image_' . $i . '_preview" src="' . esc_url( $item['image'] ) . '" alt="" style="max-width:140px;height:auto;' . ( $item['image'] ? '' : 'display:none;' ) . '"></div>';
		echo '</div>';
		echo '</td></tr>';
	}
	echo '</tbody></table>';
	submit_button( __( 'حفظ', 'beauty-time-theme' ) );
	echo '</form></div>';
}

function beauty_theme_terms_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notice  = '';
	$options = beauty_static_pages_get_options();

	if ( isset( $_POST['beauty_terms_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['beauty_terms_nonce'] ) ), 'beauty_terms_save' ) ) {
		$sections = array();
		for ( $i = 0; $i < 4; $i++ ) {
			$sections[] = array(
				'title' => sanitize_text_field( wp_unslash( $_POST[ "terms_title_{$i}" ] ?? '' ) ),
				'body'  => sanitize_textarea_field( wp_unslash( $_POST[ "terms_body_{$i}" ] ?? '' ) ),
				'image' => esc_url_raw( wp_unslash( $_POST[ "terms_image_{$i}" ] ?? '' ) ),
			);
		}
		$options['terms']['sections'] = $sections;
		update_option( BEAUTY_STATIC_PAGES_OPTION, $options, false );
		$notice = __( 'تم حفظ سياسة الاستخدام.', 'beauty-time-theme' );
	}

	echo '<div class="wrap" dir="rtl" style="text-align: right;">';
	echo '<h1>' . esc_html__( 'محتوى سياسة الاستخدام', 'beauty-time-theme' ) . '</h1>';
	if ( $notice ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $notice ) . '</p></div>';
	}
	echo '<form method="post">';
	wp_nonce_field( 'beauty_terms_save', 'beauty_terms_nonce' );
	echo '<table class="form-table"><tbody>';
	$terms_sections = $options['terms']['sections'] ?? array();
	for ( $i = 0; $i < 4; $i++ ) {
		$item = $terms_sections[ $i ] ?? array( 'title' => '', 'body' => '', 'image' => '' );
		echo '<tr><th><label>' . esc_html( sprintf( 'فقرة %d', $i + 1 ) ) . '</label></th><td>';
		echo '<input type="text" name="terms_title_' . $i . '" value="' . esc_attr( $item['title'] ) . '" placeholder="العنوان" class="regular-text" /><br>';
		echo '<textarea name="terms_body_' . $i . '" rows="4" class="large-text" style="margin-top:6px;">' . esc_textarea( $item['body'] ) . '</textarea>';
		echo '<div style="margin-top:8px;">';
		echo '<input type="text" id="terms_image_' . $i . '" name="terms_image_' . $i . '" value="' . esc_attr( $item['image'] ) . '" class="regular-text" readonly /> ';
		echo '<button class="button beauty-media-upload" data-target="terms_image_' . $i . '" data-preview="terms_image_' . $i . '_preview">' . esc_html__( 'رفع صورة', 'beauty-time-theme' ) . '</button>';
		echo '<div style="margin-top:8px;"><img id="terms_image_' . $i . '_preview" src="' . esc_url( $item['image'] ) . '" alt="" style="max-width:140px;height:auto;' . ( $item['image'] ? '' : 'display:none;' ) . '"></div>';
		echo '</div>';
		echo '</td></tr>';
	}
	echo '</tbody></table>';
	submit_button( __( 'حفظ', 'beauty-time-theme' ) );
	echo '</form></div>';
}

function beauty_theme_pages_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notice  = '';
	$pages   = get_option( BEAUTY_THEME_PAGES_OPTION, array() );
	$defs    = beauty_theme_pages_get_definitions();

	if ( isset( $_POST['beauty_pages_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['beauty_pages_nonce'] ) ), 'beauty_pages_sync' ) ) {
		$mode = sanitize_text_field( wp_unslash( $_POST['beauty_pages_mode'] ?? 'update' ) );
		$updated = array();
		foreach ( $defs as $key => $def ) {
			$page_id = isset( $pages[ $key ] ) ? absint( $pages[ $key ] ) : 0;
			$page = $page_id ? get_post( $page_id ) : null;
			if ( ! $page ) {
				$found = get_page_by_path( $def['slug'] );
				if ( $found && 'page' === $found->post_type ) {
					$page = $found;
				}
			}

			if ( 'missing' === $mode && $page ) {
				$pages[ $key ] = $page->ID;
				continue;
			}

			if ( $page ) {
				wp_update_post(
					array(
						'ID'         => $page->ID,
						'post_title' => $def['title'],
						'post_name'  => $def['slug'],
						'post_type'  => 'page',
						'post_status'=> 'publish',
					)
				);
				update_post_meta( $page->ID, '_wp_page_template', $def['template'] );
				$pages[ $key ] = $page->ID;
				$updated[] = $def['title'];
			} else {
				$new_id = wp_insert_post(
					array(
						'post_title'  => $def['title'],
						'post_name'   => $def['slug'],
						'post_type'   => 'page',
						'post_status' => 'publish',
					)
				);
				if ( $new_id && ! is_wp_error( $new_id ) ) {
					update_post_meta( $new_id, '_wp_page_template', $def['template'] );
					$pages[ $key ] = $new_id;
					$updated[] = $def['title'];
				}
			}
		}

		update_option( BEAUTY_THEME_PAGES_OPTION, $pages, false );
		$notice = $updated ? __( 'تم إنشاء/تحديث الصفحات المطلوبة.', 'beauty-time-theme' ) : __( 'لا توجد تغييرات مطلوبة.', 'beauty-time-theme' );
	}

	echo '<div class="wrap" dir="rtl" style="text-align: right;">';
	echo '<h1>' . esc_html__( 'إنشاء صفحات القالب', 'beauty-time-theme' ) . '</h1>';
	if ( $notice ) {
		echo '<div class="notice notice-success"><p>' . esc_html( $notice ) . '</p></div>';
	}
	echo '<form method="post">';
	wp_nonce_field( 'beauty_pages_sync', 'beauty_pages_nonce' );
	echo '<p>' . esc_html__( 'يمكنك إنشاء صفحات القالب أو تحديثها بالاعتماد على القوالب الموجودة.', 'beauty-time-theme' ) . '</p>';
	echo '<input type="hidden" name="beauty_pages_mode" value="update">';
	submit_button( __( 'إنشاء أو تحديث صفحات القالب', 'beauty-time-theme' ), 'primary' );
	echo '</form>';

	echo '<form method="post" style="margin-top:16px;">';
	wp_nonce_field( 'beauty_pages_sync', 'beauty_pages_nonce' );
	echo '<input type="hidden" name="beauty_pages_mode" value="missing">';
	submit_button( __( 'إنشاء الصفحات غير الموجودة فقط', 'beauty-time-theme' ), 'secondary' );
	echo '</form>';

	echo '<h2 style="margin-top:24px;">' . esc_html__( 'حالة الصفحات', 'beauty-time-theme' ) . '</h2>';
	echo '<table class="widefat striped" style="max-width: 720px;"><thead><tr><th>' . esc_html__( 'الصفحة', 'beauty-time-theme' ) . '</th><th>' . esc_html__( 'المعرف', 'beauty-time-theme' ) . '</th><th>' . esc_html__( 'الرابط', 'beauty-time-theme' ) . '</th></tr></thead><tbody>';
	foreach ( $defs as $key => $def ) {
		$page_id = isset( $pages[ $key ] ) ? absint( $pages[ $key ] ) : 0;
		$link    = $page_id ? get_permalink( $page_id ) : '';
		echo '<tr><td>' . esc_html( $def['title'] ) . '</td><td>' . ( $page_id ? esc_html( $page_id ) : '—' ) . '</td><td>' . ( $link ? '<a href="' . esc_url( $link ) . '" target="_blank">' . esc_html__( 'عرض', 'beauty-time-theme' ) . '</a>' : '—' ) . '</td></tr>';
	}
	echo '</tbody></table>';
	echo '</div>';
}

function beauty_theme_configure_smtp( $phpmailer ) {
	$options = beauty_contact_settings_get_options();
	$type = $options['mailer_type'] ?? '';

	if ( 'gmail' === $type ) {
		$user = $options['gmail']['username'] ?? '';
		$pass = $options['gmail']['app_pass'] ?? '';
		if ( ! $user || ! $pass ) {
			return;
		}
		$phpmailer->isSMTP();
		$phpmailer->Host       = 'smtp.gmail.com';
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Username   = $user;
		$phpmailer->Password   = $pass;
		$phpmailer->SMTPSecure = 'tls';
		$phpmailer->Port       = 587;
		$from_email = $options['gmail']['from_email'] ?: $user;
		$from_name  = $options['gmail']['from_name'] ?: get_bloginfo( 'name' );
		$phpmailer->setFrom( $from_email, $from_name );
	} elseif ( 'professional' === $type ) {
		$host = $options['professional']['host'] ?? '';
		$user = $options['professional']['username'] ?? '';
		$pass = $options['professional']['password'] ?? '';
		if ( ! $host || ! $user || ! $pass ) {
			return;
		}
		$phpmailer->isSMTP();
		$phpmailer->Host       = $host;
		$phpmailer->SMTPAuth   = true;
		$phpmailer->Username   = $user;
		$phpmailer->Password   = $pass;
		$phpmailer->Port       = (int) ( $options['professional']['port'] ?? 587 );
		$encryption = $options['professional']['encryption'] ?? 'tls';
		if ( 'none' !== $encryption ) {
			$phpmailer->SMTPSecure = $encryption;
		}
		$from_email = $options['professional']['from_email'] ?: $user;
		$from_name  = $options['professional']['from_name'] ?: get_bloginfo( 'name' );
		$phpmailer->setFrom( $from_email, $from_name );
	}
}
add_action( 'phpmailer_init', 'beauty_theme_configure_smtp' );
