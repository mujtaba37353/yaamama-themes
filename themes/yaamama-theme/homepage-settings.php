<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function yaamama_homepage_defaults() {
	$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';
	$icon_defaults = array();
	for ( $i = 0; $i < 8; $i++ ) {
		$icon_defaults[] = array(
			'id'  => 0,
			'url' => '',
		);
	}

	return array(
		'hero'       => array(
			'stat_text'  => 'أكثر من 500 قالب احترافي',
			'title'      => array(
				'line1'     => 'أطلق متجرك',
				'line2'     => 'الإلكتروني',
				'line3'     => '',
				'highlight' => 'بهوية احترافية',
			),
			'cta'        => array(
				'text' => 'تصفح متاجرنا الآن',
				'url'  => home_url( '/store' ),
			),
			'conversion' => array(
				'label' => 'معدل التحويل',
				'value' => '+%45زيادة',
			),
			'slider_images' => array(
				array(
					'src' => $assets_uri . '/inifnite-scroll-img-1.png',
					'alt' => 'صورة من معرض تصاميم المتاجر الإلكترونية',
				),
				array(
					'src' => $assets_uri . '/inifnite-scroll-img-2.png',
					'alt' => 'واجهة متجر إلكتروني ضمن معرض التصاميم',
				),
				array(
					'src' => $assets_uri . '/inifnite-scroll-img-3.png',
					'alt' => 'نموذج تصميم متجر من قوالب يمامة',
				),
			),
		),
		'section_1' => array(
			'title' => 'انطلاق نحو القمة',
			'text'  => 'حول رؤيتك إلى واقع بواجهة مليئة بالثقة والتميز.',
			'speed' => 25,
			'loop'  => true,
			'icons' => $icon_defaults,
		),
		'shipping_section' => array(
			'title'      => 'شحن سريع وآمن',
			'text'       => 'حلول شحن مرنة وتحديثات مستمرة لتجربة تسليم موثوقة لعملائك.',
			'background' => 'gradient',
			'video_id'   => 0,
			'video_url'  => '',
		),
		'why_us'     => array(
			'title'   => 'لماذا يمامة ؟',
			'tagline' => 'لأننا نهتم بالتفاصيل التي تصنع الفرق',
		),
		'features'   => array(
			'design'         => array(
				'title' => 'اتقان في التصميم',
				'desc'  => 'واجهات مستخدم تدمج بين الجمال والوظيفة لضمان رحلة تسويق لا تنسي',
			),
			'support'        => array(
				'title' => 'دعم نخبة الخبراء',
				'desc'  => 'فريق متفاني من المستشارين التقنيين متاح لضمان نجاح ونمو تجارتك',
				'image' => $assets_uri . '/experience1.png',
				'alt'   => 'Experts',
			),
			'responsive_text' => array(
				'title' => 'تجاوب فائق',
				'desc'  => 'تجربة تسوق مثالية تتبع عملائك أينما كانوا ،عبر جميع الأجهزة بذكاء',
			),
			'speed'          => array(
				'title' => 'بسرعة استثنائية',
				'desc'  => 'بنية تحتية تضمن تحميل متجرك في أجزاء من الثانية حول العالم',
			),
			'control'        => array(
				'title' => 'تحكم بلا حدود',
				'desc'  => 'أدوات تخصيص متطورة تمنحك الحرية الكاملة في رسم هوية متجرك بكل يسر',
				'image' => $assets_uri . '/experience2.png',
				'alt'   => 'Control',
			),
			'launch'         => array(
				'title' => 'انطلاق نحو القمة',
				'desc'  => 'حول رؤيتك إلى واقع في غضون لحظات مع أنظمة الإطلاق الذكية والمتكاملة',
			),
			'responsive_image' => array(
				'image' => $assets_uri . '/feature-layouts.png',
				'alt'   => 'Responsive Design',
			),
		),
		'how_start'  => array(
			'title' => 'كيف تبدأ خلال لحظات وتستمتع بكل هذه المميزات وأكثر',
			'steps' => array(
				array(
					'number'   => '1',
					'title'    => 'اختر المجال',
					'desc'     => 'حدد نوع نشاطك، سواء متجر ملابس، هدايا، إلكترونيات أو غيره.',
					'image'    => $assets_uri . '/how-to-start.png',
					'alt'      => 'اختر المجال',
					'reverse'  => true,
				),
				array(
					'number'   => '2',
					'title'    => 'اختر قالبك المفضل',
					'desc'     => 'تصفح قوالب احترافية مصممة خصيصاً لتناسب نشاطك.',
					'image'    => $assets_uri . '/how-to-start.png',
					'alt'      => 'اختر قالبك المفضل',
					'reverse'  => false,
				),
				array(
					'number'   => '3',
					'title'    => 'خصص بسهولة',
					'desc'     => 'عدّل القالب بطريقتك غيّر الألوان، الصور، النصوص، وأضف منتجاتك بكل سهولة.',
					'image'    => $assets_uri . '/how-to-start.png',
					'alt'      => 'خصص بسهولة',
					'reverse'  => true,
				),
				array(
					'number'   => '4',
					'title'    => 'انطلق وابدأ البيع',
					'desc'     => 'انشر متجرك خلال دقائق اربط متجرك وابدأ في استقبال الطلبات فوراً.',
					'image'    => $assets_uri . '/how-to-start.png',
					'alt'      => 'انطلق وابدأ البيع',
					'reverse'  => false,
				),
			),
		),
		'categories' => array(
			'title'    => 'اختر المجال المناسب لمشروعك',
			'subtitle' => 'قوالب مصممة خصيصًا لتناسب كل نشاط وتساعدك تطلق موقعك بسرعة',
			'items'    => array(
				array(
					'label' => 'تجارة إلكترونية',
					'desc'  => 'متاجر جاهزة ومصممة خصيصا للتسويق الرقمي.',
					'image' => $assets_uri . '/category-ecommerce.png',
					'alt'   => 'تجارة إلكترونية',
				),
				array(
					'label' => 'صحة',
					'desc'  => 'منصات حديثة تساعدك على تقديم خدمات صحية موثوقة وسلسة.',
					'image' => $assets_uri . '/category-health.png',
					'alt'   => 'صحة',
				),
				array(
					'label' => 'سياحة وسفر',
					'desc'  => 'حلول ذكية لإدارة وبيع خدمات السياحة والسفر أونلاين.',
					'image' => $assets_uri . '/category-travel.png',
					'alt'   => 'سياحة وسفر',
				),
				array(
					'label' => 'مطاعم',
					'desc'  => 'حل متكامل لعرض منتجات المطاعم وإدارة الطلبات باحترافية.',
					'image' => $assets_uri . '/category-food.png',
					'alt'   => 'مطاعم',
				),
				array(
					'label' => 'تعليم',
					'desc'  => 'متاجر تقدّم محتوى وخدمات تعليمية لدعم التعلّم بشكل سهل وفعّال.',
					'image' => $assets_uri . '/category-education.png',
					'alt'   => 'تعليم',
				),
			),
		),
		'templates'  => array(
			'title' => 'اختر تصميم متجرك الأنيق من بين أكثر من 50 قالب احترافي',
			'items' => array(
				array(
					'image' => $assets_uri . '/temp1.png',
					'alt'   => 'معاينة قالب متجر إلكتروني 1',
				),
				array(
					'image' => $assets_uri . '/temp2.png',
					'alt'   => 'معاينة قالب متجر إلكتروني 2',
				),
				array(
					'image' => $assets_uri . '/temp3.png',
					'alt'   => 'معاينة قالب متجر إلكتروني 3',
				),
				array(
					'image' => $assets_uri . '/temp4.png',
					'alt'   => 'معاينة قالب متجر إلكتروني 4',
				),
			),
			'category_items' => array(),
		),
		'trust'      => array(
			'title' => 'لماذا يثق بنا عملاؤنا ؟',
			'cards' => array(
				array(
					'title' => 'وسائل دفع موثوقة وآمنة',
					'desc'  => 'نستخدم أحدث تقنيات الحماية لضمان أمان بياناتك وعمليات الدفع بالكامل.',
					'image' => $assets_uri . '/guarantee.svg',
					'alt'   => 'أيقونة ضمان وسائل الدفع',
				),
				array(
					'title' => 'دعم مستمر بعد إطلاق متجرك',
					'desc'  => 'فريق دعم جاهز يساعدك في أي وقت، حتى بعد نشر متجرك وتشغيله.',
					'image' => $assets_uri . '/technical-support.svg',
					'alt'   => 'أيقونة الدعم الفني في يمامة',
				),
				array(
					'title' => 'سرعة وسهولة',
					'desc'  => 'بدون تعقيد أو خبرة تقنية — كل شيء مصمم ليكون بسيط وسلس.',
					'image' => $assets_uri . '/press.svg',
					'alt'   => 'أيقونة سرعة وسهولة استخدام المنصة',
				),
			),
		),
		'reviews'    => array(
			'title' => 'آراء العملاء',
			'items' => array(
				array(
					'text'   => 'المنصة وفرت عليّ وقتًا ومجهودًا كبيرًا، ومتجري اشتغل في نفس اليوم',
					'author' => 'أحمد حسنين',
					'rating' => 5,
				),
				array(
					'text'   => 'المنصة وفرت عليّ وقتًا ومجهودًا كبيرًا، ومتجري اشتغل في نفس اليوم',
					'author' => 'أحمد حسنين',
					'rating' => 5,
				),
				array(
					'text'   => 'المنصة وفرت عليّ وقتًا ومجهودًا كبيرًا، ومتجري اشتغل في نفس اليوم',
					'author' => 'أحمد حسنين',
					'rating' => 5,
				),
			),
		),
	);
}

function yaamama_about_defaults() {
	$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';

	return array(
		'hero'     => array(
			'title'      => 'من نحن',
			'image'      => $assets_uri . '/about.png',
			'alt'        => 'فريق يمامة',
			'paragraphs' => array(
				'منصة يمامة هي منصة متخصصة في تصميم وتطوير قوالب المواقع والمتاجر الإلكترونية. نساعد أصحاب الأعمال على إطلاق متاجرهم بسرعة واحترافية دون تعقيد.',
				'نقدم حلول تصميم عصرية تركز على تجربة المستخدم، سهولة الإدارة، وتحقيق أفضل أداء للمبيعات.',
				'في يمامة، نؤمن أن التصميم الجيد ليس مجرد شكل، بل أداة فعالة لتحويل الزوار إلى عملاء وبناء علامة تجارية قوية في السوق الرقمي.',
			),
		),
		'why'      => array(
			'title'   => 'لماذا يمامة ؟',
			'tagline' => 'لأننا نهتم بالتفاصيل التي تصنع الفرق',
		),
		'features' => array(
			array(
				'icon' => $assets_uri . '/desktop.svg',
				'alt'  => 'أيقونة تصميم واجهات عصرية',
				'text' => 'تصاميم عصرية تركز على تجربة المستخدم (UX/UI)',
			),
			array(
				'icon' => $assets_uri . '/rocket.svg',
				'alt'  => 'أيقونة سرعة الأداء',
				'text' => 'سرعة تحميل وأداء عالي',
			),
			array(
				'icon' => $assets_uri . '/responsive.svg',
				'alt'  => 'أيقونة تصميم متجاوب مع الجوال',
				'text' => 'متوافق مع جميع الأجهزة (جوال – تابلت – كمبيوتر)',
			),
			array(
				'icon' => $assets_uri . '/layout.svg',
				'alt'  => 'أيقونة سهولة التعديل',
				'text' => 'سهولة التعديل دون خبرة تقنية',
			),
			array(
				'icon' => $assets_uri . '/technical-support1.svg',
				'alt'  => 'أيقونة الدعم الفني',
				'text' => 'دعم فني يساعدك في كل خطوة',
			),
			array(
				'icon' => $assets_uri . '/idea.svg',
				'alt'  => 'أيقونة أفكار وحلول لإطلاق متجرك',
				'text' => 'حلول تساعدك على إطلاق متجرك بسرعة وبثقة',
			),
		),
	);
}

function yaamama_footer_defaults() {
	$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';

	return array(
		'logo'      => array(
			'image' => $assets_uri . '/navbar-icon.png',
			'alt'   => 'شعار Yamama',
			'url'   => home_url( '/' ),
		),
		'quick'     => array(
			'title' => 'روابط سريعه',
			'items' => array(
				array(
					'label' => 'الرئيسية',
					'url'   => home_url( '/' ),
				),
				array(
					'label' => 'الأسئلة الشائعة',
					'url'   => home_url( '/faq' ),
				),
				array(
					'label' => 'المساعدة',
					'url'   => home_url( '/guide' ),
				),
			),
		),
		'policies'  => array(
			'title' => 'السياسات',
			'items' => array(
				array(
					'label' => 'سياسة الخصوصية',
					'url'   => home_url( '/privacy-policy' ),
				),
				array(
					'label' => 'سياسة الاسترجاع',
					'url'   => home_url( '/refund-policy' ),
				),
				array(
					'label' => 'سياسة الشحن',
					'url'   => home_url( '/shipping-policy' ),
				),
			),
		),
		'contact'   => array(
			'title'   => 'تواصل معنا',
			'address' => array(
				'text' => 'الرياض، المملكة العربية السعودية',
			),
			'email'   => array(
				'label' => 'info@yamama.com',
				'url'   => 'mailto:info@yamama.com',
			),
			'phone'   => array(
				'label' => '+966 12 345 6789',
				'url'   => 'tel:+966123456789',
			),
		),
		'copyright' => 'جميع الحقوق محفوظة © Yamama Solutions',
	);
}

function yaamama_policy_defaults() {
	return array(
		'privacy' => array(
			'title'   => 'سياسة الخصوصية',
			'content' => '',
		),
		'refund'  => array(
			'title'   => 'سياسة الاسترجاع',
			'content' => '',
		),
		'shipping' => array(
			'title'   => 'سياسة الشحن',
			'content' => '',
		),
	);
}

function yaamama_get_policy_settings() {
	$saved    = get_option( 'yaamama_policy_settings', array() );
	$defaults = yaamama_policy_defaults();
	return yaamama_homepage_deep_merge( $saved, $defaults );
}

function yaamama_contact_defaults() {
	return array(
		'page'     => array(
			'title'       => 'تواصل معنا',
			'description' => 'نحن هنا لمساعدتك في الحصول على أفضل تجربة رقمية .هل لديك استفسار أو ترغب فى التعاون ؟ لا تتردد فى مراسلتنا',
		),
		'info'     => array(
			'support_label'  => 'البريد الإلكتروني للدعم',
			'support_value'  => 'SUPPORT@themes.com',
			'work_hours'     => 'الاحد-الخميس (9صباحا-6مساءا)',
			'response_time'  => 'أقل من 24 ساعة',
			'social_title'   => 'تابعنا علي وسائل التواصل الاجتماعي',
			'social_links'   => array(
				'x'         => '#',
				'instagram' => '#',
				'facebook'  => '#',
			),
			'location_title' => 'موقعنا',
			'location_line1' => 'الرياض ، المملكة العربية السعودية',
			'location_line2' => 'حي الملقا ،طريق أنس بن مالك',
		),
		'success'  => array(
			'title'       => 'شكرًا لتواصلك معنا',
			'subtitle'    => 'تم إرسال استفسارك وسيتم التواصل معك فى أقرب وقت',
			'button_text' => 'الذهاب إلى الصفحة الرئيسية',
		),
		'mail'     => array(
			'recipient_email' => 'info@yamama.com',
			'mailer_type'     => 'smtp',
			'smtp'            => array(
				'host'       => '',
				'port'       => '587',
				'encryption' => 'tls',
				'username'   => '',
				'password'   => '',
				'from_name'  => 'Yamama',
				'from_email' => 'info@yamama.com',
			),
			'gmail'           => array(
				'email'       => '',
				'app_password'=> '',
				'from_name'   => 'Yamama',
				'from_email'  => '',
			),
		),
		'floating' => array(
			'whatsapp' => '055116798',
			'call'     => '055116798',
		),
	);
}

function yaamama_homepage_deep_merge( $data, $defaults ) {
	$data = is_array( $data ) ? $data : array();
	$merged = $defaults;

	foreach ( $data as $key => $value ) {
		if ( is_array( $value ) && isset( $merged[ $key ] ) && is_array( $merged[ $key ] ) ) {
			$merged[ $key ] = yaamama_homepage_deep_merge( $value, $merged[ $key ] );
		} else {
			$merged[ $key ] = $value;
		}
	}

	return $merged;
}

function yaamama_get_homepage_settings() {
	$saved = get_option( 'yaamama_homepage_settings', array() );
	$defaults = yaamama_homepage_defaults();

	return yaamama_homepage_deep_merge( $saved, $defaults );
}

function yaamama_get_about_settings() {
	$saved = get_option( 'yaamama_about_settings', array() );
	$defaults = yaamama_about_defaults();

	return yaamama_homepage_deep_merge( $saved, $defaults );
}

function yaamama_get_footer_settings() {
	$saved = get_option( 'yaamama_footer_settings', array() );
	$defaults = yaamama_footer_defaults();

	return yaamama_homepage_deep_merge( $saved, $defaults );
}

function yaamama_get_contact_settings() {
	$saved = get_option( 'yaamama_contact_settings', array() );
	$defaults = yaamama_contact_defaults();

	return yaamama_homepage_deep_merge( $saved, $defaults );
}

function yaamama_register_content_menu() {
	add_menu_page(
		'المحتوى',
		'المحتوى',
		'manage_options',
		'yaamama-content',
		'yaamama_render_content_overview',
		'dashicons-media-text',
		58
	);

	add_submenu_page(
		'yaamama-content',
		'الصفحة الرئيسية',
		'الصفحة الرئيسية',
		'manage_options',
		'yaamama-homepage',
		'yaamama_render_homepage_settings'
	);

	add_submenu_page(
		'yaamama-content',
		'من نحن',
		'من نحن',
		'manage_options',
		'yaamama-about',
		'yaamama_render_about_settings'
	);

	add_submenu_page(
		'yaamama-content',
		'الفوتر',
		'الفوتر',
		'manage_options',
		'yaamama-footer',
		'yaamama_render_footer_settings'
	);

	add_submenu_page(
		'yaamama-content',
		'تواصل معنا',
		'تواصل معنا',
		'manage_options',
		'yaamama-contact',
		'yaamama_render_contact_settings'
	);

	add_submenu_page(
		'yaamama-content',
		'صفحات السياسات',
		'صفحات السياسات',
		'manage_options',
		'yaamama-policies',
		'yaamama_render_policy_pages_settings'
	);

	add_submenu_page(
		'yaamama-content',
		'الصفحات',
		'الصفحات',
		'manage_options',
		'yaamama-pages',
		'yaamama_render_pages_admin'
	);
}
add_action( 'admin_menu', 'yaamama_register_content_menu' );

function yaamama_homepage_admin_assets( $hook ) {
	$page = sanitize_text_field( wp_unslash( $_GET['page'] ?? '' ) );
	if ( ! in_array( $page, array( 'yaamama-content', 'yaamama-homepage', 'yaamama-about', 'yaamama-footer', 'yaamama-contact', 'yaamama-policies', 'yaamama-pages' ), true ) ) {
		return;
	}

	wp_enqueue_media();

	wp_register_style( 'yaamama-content-admin', false );
	wp_enqueue_style( 'yaamama-content-admin' );

	wp_add_inline_style(
		'yaamama-content-admin',
		'.yaamama-content-page{max-width:1200px;margin:20px auto;background:#fff;padding:28px;border-radius:8px;box-shadow:0 1px 3px rgba(0,0,0,.1)}' .
		'.yaamama-section{margin-bottom:32px;padding:20px;background:#f8f8f8;border-radius:8px;border-right:4px solid #2271b1}' .
		'.yaamama-section h2{margin-top:0;color:#2271b1}' .
		'.yaamama-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px}' .
		'.yaamama-item{background:#fff;padding:16px;border-radius:6px;border:1px solid #ddd}' .
		'.yaamama-item h4{margin-top:0}' .
		'.yaamama-preview{width:100%;height:140px;background:#f0f0f0;border-radius:4px;display:flex;align-items:center;justify-content:center;overflow:hidden;margin-bottom:10px}' .
		'.yaamama-preview img{max-width:100%;max-height:100%;object-fit:contain}' .
		'.yaamama-media-grid{display:flex;flex-wrap:wrap;gap:12px}' .
		'.yaamama-media-item{background:#fff;border:1px solid #ddd;border-radius:6px;padding:8px;width:120px;text-align:center}' .
		'.yaamama-media-item img{max-width:100%;max-height:80px;display:block;margin:0 auto 6px}' .
		'.yaamama-media-remove{color:#d63638;text-decoration:none;font-size:12px}' .
		'.yaamama-field{margin-bottom:10px}' .
		'.yaamama-field label{display:block;font-weight:600;margin-bottom:4px}' .
		'.yaamama-actions{display:flex;gap:8px;margin-bottom:8px}' .
		'.yaamama-reset{background:#fff3cd;border-color:#ffc107}' .
		'.yaamama-reset .button{background:#dc3545;border-color:#dc3545;color:#fff}' .
		'.yaamama-success{background:#d4edda;color:#155724;padding:12px;border-radius:4px;margin-bottom:16px;border:1px solid #c3e6cb}'
	);

	wp_register_script( 'yaamama-content-admin', '', array( 'jquery' ), null, true );
	wp_enqueue_script( 'yaamama-content-admin' );

	wp_add_inline_script(
		'yaamama-content-admin',
		'jQuery(function($){' .
		'function getIcons($input){' .
		'var raw=$input.val();if(!raw){return[];}' .
		'try{var parsed=JSON.parse(raw);return Array.isArray(parsed)?parsed:[];}catch(e){return[];}' .
		'}' .
		'function setIcons($input,icons){$input.val(JSON.stringify(icons));}' .
		'function renderIcons($preview,icons){' .
		'$preview.empty();' .
		'if(!icons.length){$preview.append("<span>لا توجد أيقونات</span>");return;}' .
		'icons.forEach(function(icon){if(!icon||!icon.url){return;}' .
		'var item=$("<div/>",{class:"yaamama-media-item","data-id":icon.id||""});' .
		'var img=$("<img/>",{src:icon.url,alt:""});' .
		'var remove=$("<a/>",{href:"#",class:"yaamama-media-remove",text:"إزالة"});' .
		'item.append(img).append(remove);' .
		'$preview.append(item);' .
		'});' .
		'}' .
		'$(".yaamama-upload-btn").on("click",function(e){' .
		'e.preventDefault();' .
		'var button=$(this);var target=button.data("target");var targetId=button.data("targetId");var targetUrl=button.data("targetUrl");var preview=button.data("preview");' .
		'var libraryRaw=button.data("library")||"image";var type=button.data("type")||"image";' .
		'var library=libraryRaw.toString().split(",");' .
		'var isMixed=library.indexOf("video")>-1 && library.indexOf("image")>-1;' .
		'var title=isMixed?"اختر وسائط":(libraryRaw==="video"?"اختر فيديو":"اختر صورة");' .
		'var buttonText=isMixed?"استخدم هذا الملف":(libraryRaw==="video"?"استخدم هذا الفيديو":"استخدم هذه الصورة");' .
		'var frame=wp.media({title:title,button:{text:buttonText},multiple:false,library:{type:library}});' .
		'frame.on("select",function(){var attachment=frame.state().get("selection").first().toJSON();' .
		'if(target){$("#"+target).val(attachment.url||"");}' .
		'if(targetId){$("#"+targetId).val(attachment.id||"");}' .
		'if(targetUrl){$("#"+targetUrl).val(attachment.url||"");}' .
		'var renderType=(type==="media")?(attachment.type||"image"):type;' .
		'if(preview){if(renderType==="video"){if(attachment.url){$("#"+preview).html("<video src=\\""+attachment.url+"\\" controls></video>");}else{$("#"+preview).html("<span>لا يوجد فيديو</span>");}}' .
		'else{if(attachment.url){$("#"+preview).html("<img src=\\""+attachment.url+"\\" />");}else{$("#"+preview).html("<span>لا توجد صورة</span>");}}}' .
		'});frame.open();' .
		'});' .
		'$(".yaamama-remove-btn").on("click",function(e){' .
		'e.preventDefault();var button=$(this);var target=button.data("target");var targetId=button.data("targetId");var targetUrl=button.data("targetUrl");var preview=button.data("preview");var type=button.data("type")||"image";' .
		'if(target){$("#"+target).val("");}' .
		'if(targetId){$("#"+targetId).val("");}' .
		'if(targetUrl){$("#"+targetUrl).val("");}' .
		'if(preview){if(type==="video"){$("#"+preview).html("<span>لا يوجد فيديو</span>");}else if(type==="media"){$("#"+preview).html("<span>لا يوجد ملف</span>");}else{$("#"+preview).html("<span>لا توجد صورة</span>");}}' .
		'});' .
		'$(".yaamama-multi-upload-btn").on("click",function(e){' .
		'e.preventDefault();var button=$(this);var target=button.data("target");var preview=button.data("preview");' .
		'var $input=$("#"+target);var $preview=$("#"+preview);' .
		'var frame=wp.media({title:"اختر الأيقونات",button:{text:"استخدام الأيقونات"},multiple:true,library:{type:["image"]}});' .
		'frame.on("select",function(){var selection=frame.state().get("selection").toJSON();' .
		'var icons=getIcons($input);var existing={};icons.forEach(function(icon){if(icon.id){existing[icon.id]=true;}});' .
		'selection.forEach(function(att){if(att.id && existing[att.id]){return;}icons.push({id:att.id||0,url:att.url||""});});' .
		'setIcons($input,icons);renderIcons($preview,icons);' .
		'});frame.open();' .
		'});' .
		'$(".yaamama-multi-remove-btn").on("click",function(e){' .
		'e.preventDefault();var button=$(this);var target=button.data("target");var preview=button.data("preview");' .
		'var $input=$("#"+target);var $preview=$("#"+preview);setIcons($input,[]);renderIcons($preview,[]);' .
		'});' .
		'$(document).on("click",".yaamama-media-remove",function(e){' .
		'e.preventDefault();var $item=$(this).closest(".yaamama-media-item");var $preview=$item.closest(".yaamama-media-grid");' .
		'var target=$preview.data("target");if(!target){return;}' .
		'var $input=$("#"+target);var icons=getIcons($input);var removeId=$item.data("id");var removeUrl=$item.find("img").attr("src");' .
		'icons=icons.filter(function(icon){if(removeId){return String(icon.id)!==String(removeId);}return icon.url!==removeUrl;});' .
		'setIcons($input,icons);renderIcons($preview,icons);' .
		'});' .
		'});'
	);
}
add_action( 'admin_enqueue_scripts', 'yaamama_homepage_admin_assets' );

function yaamama_render_content_overview() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="wrap yaamama-content-page">
		<h1>المحتوى</h1>
		<p>يمكنك تعديل أقسام الموقع من القائمة الجانبية.</p>
		<p>
			<a class="button button-primary" href="<?php echo esc_url( admin_url( 'admin.php?page=yaamama-homepage' ) ); ?>">
				الصفحة الرئيسية
			</a>
			<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=yaamama-about' ) ); ?>">
				من نحن
			</a>
			<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=yaamama-footer' ) ); ?>">
				الفوتر
			</a>
			<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=yaamama-contact' ) ); ?>">
				تواصل معنا
			</a>
			<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=yaamama-policies' ) ); ?>">
				صفحات السياسات
			</a>
			<a class="button" href="<?php echo esc_url( admin_url( 'admin.php?page=yaamama-pages' ) ); ?>">
				الصفحات
			</a>
		</p>
	</div>
	<?php
}

function yaamama_render_homepage_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$template_terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
		)
	);
	if ( is_wp_error( $template_terms ) ) {
		$template_terms = array();
	}

	if ( isset( $_POST['yaamama_homepage_save'] ) && check_admin_referer( 'yaamama_homepage_settings_nonce' ) ) {
		$settings = array(
			'hero'       => array(
				'stat_text'  => sanitize_text_field( wp_unslash( $_POST['hero_stat_text'] ?? '' ) ),
				'title'      => array(
					'line1'     => sanitize_text_field( wp_unslash( $_POST['hero_title_line1'] ?? '' ) ),
					'line2'     => sanitize_text_field( wp_unslash( $_POST['hero_title_line2'] ?? '' ) ),
					'line3'     => sanitize_text_field( wp_unslash( $_POST['hero_title_line3'] ?? '' ) ),
					'highlight' => sanitize_text_field( wp_unslash( $_POST['hero_title_highlight'] ?? '' ) ),
				),
				'cta'        => array(
					'text' => sanitize_text_field( wp_unslash( $_POST['hero_cta_text'] ?? '' ) ),
					'url'  => esc_url_raw( wp_unslash( $_POST['hero_cta_url'] ?? '' ) ),
				),
				'conversion' => array(
					'label' => sanitize_text_field( wp_unslash( $_POST['hero_conversion_label'] ?? '' ) ),
					'value' => sanitize_text_field( wp_unslash( $_POST['hero_conversion_value'] ?? '' ) ),
				),
				'slider_images' => array(),
			),
			'section_1' => array(
				'title' => sanitize_text_field( wp_unslash( $_POST['section_1_title'] ?? '' ) ),
				'text'  => sanitize_textarea_field( wp_unslash( $_POST['section_1_text'] ?? '' ) ),
				'speed' => absint( $_POST['section_1_speed'] ?? 25 ),
				'loop'  => ! empty( $_POST['section_1_loop'] ),
				'icons' => array(),
			),
			'shipping_section' => array(
				'title'      => sanitize_text_field( wp_unslash( $_POST['shipping_section_title'] ?? '' ) ),
				'text'       => sanitize_textarea_field( wp_unslash( $_POST['shipping_section_text'] ?? '' ) ),
				'background' => sanitize_text_field( wp_unslash( $_POST['shipping_section_background'] ?? 'gradient' ) ),
				'video_id'   => absint( $_POST['shipping_section_video_id'] ?? 0 ),
				'video_url'  => esc_url_raw( wp_unslash( $_POST['shipping_section_video_url'] ?? '' ) ),
			),
			'why_us'     => array(
				'title'   => sanitize_text_field( wp_unslash( $_POST['why_us_title'] ?? '' ) ),
				'tagline' => sanitize_text_field( wp_unslash( $_POST['why_us_tagline'] ?? '' ) ),
			),
			'features'   => array(
				'design'          => array(
					'title' => sanitize_text_field( wp_unslash( $_POST['feature_design_title'] ?? '' ) ),
					'desc'  => sanitize_textarea_field( wp_unslash( $_POST['feature_design_desc'] ?? '' ) ),
				),
				'support'         => array(
					'title' => sanitize_text_field( wp_unslash( $_POST['feature_support_title'] ?? '' ) ),
					'desc'  => sanitize_textarea_field( wp_unslash( $_POST['feature_support_desc'] ?? '' ) ),
					'image' => esc_url_raw( wp_unslash( $_POST['feature_support_image'] ?? '' ) ),
					'alt'   => sanitize_text_field( wp_unslash( $_POST['feature_support_alt'] ?? '' ) ),
				),
				'responsive_text' => array(
					'title' => sanitize_text_field( wp_unslash( $_POST['feature_responsive_title'] ?? '' ) ),
					'desc'  => sanitize_textarea_field( wp_unslash( $_POST['feature_responsive_desc'] ?? '' ) ),
				),
				'speed'           => array(
					'title' => sanitize_text_field( wp_unslash( $_POST['feature_speed_title'] ?? '' ) ),
					'desc'  => sanitize_textarea_field( wp_unslash( $_POST['feature_speed_desc'] ?? '' ) ),
				),
				'control'         => array(
					'title' => sanitize_text_field( wp_unslash( $_POST['feature_control_title'] ?? '' ) ),
					'desc'  => sanitize_textarea_field( wp_unslash( $_POST['feature_control_desc'] ?? '' ) ),
					'image' => esc_url_raw( wp_unslash( $_POST['feature_control_image'] ?? '' ) ),
					'alt'   => sanitize_text_field( wp_unslash( $_POST['feature_control_alt'] ?? '' ) ),
				),
				'launch'          => array(
					'title' => sanitize_text_field( wp_unslash( $_POST['feature_launch_title'] ?? '' ) ),
					'desc'  => sanitize_textarea_field( wp_unslash( $_POST['feature_launch_desc'] ?? '' ) ),
				),
				'responsive_image' => array(
					'image' => esc_url_raw( wp_unslash( $_POST['feature_responsive_image'] ?? '' ) ),
					'alt'   => sanitize_text_field( wp_unslash( $_POST['feature_responsive_alt'] ?? '' ) ),
				),
			),
			'how_start'  => array(
				'title' => sanitize_text_field( wp_unslash( $_POST['how_start_title'] ?? '' ) ),
				'steps' => array(),
			),
			'categories' => array(
				'title'    => sanitize_text_field( wp_unslash( $_POST['categories_title'] ?? '' ) ),
				'subtitle' => sanitize_text_field( wp_unslash( $_POST['categories_subtitle'] ?? '' ) ),
				'items'    => array(),
			),
			'templates'  => array(
				'title' => sanitize_text_field( wp_unslash( $_POST['templates_title'] ?? '' ) ),
				'items' => array(),
				'category_items' => array(),
			),
			'trust'      => array(
				'title' => sanitize_text_field( wp_unslash( $_POST['trust_title'] ?? '' ) ),
				'cards' => array(),
			),
			'reviews'    => array(
				'title' => sanitize_text_field( wp_unslash( $_POST['reviews_title'] ?? '' ) ),
				'items' => array(),
			),
		);

		for ( $i = 0; $i < 3; $i++ ) {
			$settings['hero']['slider_images'][] = array(
				'src' => esc_url_raw( wp_unslash( $_POST[ 'hero_slider_src_' . $i ] ?? '' ) ),
				'alt' => sanitize_text_field( wp_unslash( $_POST[ 'hero_slider_alt_' . $i ] ?? '' ) ),
			);
		}

		$section_1_icons_raw = wp_unslash( $_POST['section_1_icons'] ?? '' );
		$section_1_icons = json_decode( $section_1_icons_raw, true );
		if ( ! is_array( $section_1_icons ) ) {
			$section_1_icons = array();
		}
		foreach ( $section_1_icons as $icon ) {
			if ( ! is_array( $icon ) ) {
				continue;
			}
			$icon_id = absint( $icon['id'] ?? 0 );
			$icon_url = esc_url_raw( $icon['url'] ?? '' );
			if ( ! $icon_id && ! $icon_url ) {
				continue;
			}
			$settings['section_1']['icons'][] = array(
				'id'  => $icon_id,
				'url' => $icon_url,
			);
		}

		for ( $i = 0; $i < 4; $i++ ) {
			$settings['how_start']['steps'][] = array(
				'number'  => sanitize_text_field( wp_unslash( $_POST[ 'step_number_' . $i ] ?? '' ) ),
				'title'   => sanitize_text_field( wp_unslash( $_POST[ 'step_title_' . $i ] ?? '' ) ),
				'desc'    => sanitize_textarea_field( wp_unslash( $_POST[ 'step_desc_' . $i ] ?? '' ) ),
				'image'   => esc_url_raw( wp_unslash( $_POST[ 'step_image_' . $i ] ?? '' ) ),
				'alt'     => sanitize_text_field( wp_unslash( $_POST[ 'step_alt_' . $i ] ?? '' ) ),
				'reverse' => ! empty( $_POST[ 'step_reverse_' . $i ] ),
			);
		}

		for ( $i = 0; $i < 5; $i++ ) {
			$settings['categories']['items'][] = array(
				'label' => sanitize_text_field( wp_unslash( $_POST[ 'category_label_' . $i ] ?? '' ) ),
				'desc'  => sanitize_textarea_field( wp_unslash( $_POST[ 'category_desc_' . $i ] ?? '' ) ),
				'image' => esc_url_raw( wp_unslash( $_POST[ 'category_image_' . $i ] ?? '' ) ),
				'alt'   => sanitize_text_field( wp_unslash( $_POST[ 'category_alt_' . $i ] ?? '' ) ),
			);
		}

		for ( $i = 0; $i < 4; $i++ ) {
			$settings['templates']['items'][] = array(
				'image' => esc_url_raw( wp_unslash( $_POST[ 'template_image_' . $i ] ?? '' ) ),
				'alt'   => sanitize_text_field( wp_unslash( $_POST[ 'template_alt_' . $i ] ?? '' ) ),
			);
		}

		foreach ( $template_terms as $term ) {
			$term_id = (int) $term->term_id;
			$settings['templates']['category_items'][ $term_id ] = array();
			for ( $i = 0; $i < 4; $i++ ) {
				$settings['templates']['category_items'][ $term_id ][] = array(
					'image' => esc_url_raw( wp_unslash( $_POST[ 'template_cat_' . $term_id . '_image_' . $i ] ?? '' ) ),
					'alt'   => sanitize_text_field( wp_unslash( $_POST[ 'template_cat_' . $term_id . '_alt_' . $i ] ?? '' ) ),
				);
			}
		}

		for ( $i = 0; $i < 3; $i++ ) {
			$settings['trust']['cards'][] = array(
				'title' => sanitize_text_field( wp_unslash( $_POST[ 'trust_title_' . $i ] ?? '' ) ),
				'desc'  => sanitize_textarea_field( wp_unslash( $_POST[ 'trust_desc_' . $i ] ?? '' ) ),
				'image' => esc_url_raw( wp_unslash( $_POST[ 'trust_image_' . $i ] ?? '' ) ),
				'alt'   => sanitize_text_field( wp_unslash( $_POST[ 'trust_alt_' . $i ] ?? '' ) ),
			);
		}

		for ( $i = 0; $i < 3; $i++ ) {
			$settings['reviews']['items'][] = array(
				'text'   => sanitize_textarea_field( wp_unslash( $_POST[ 'review_text_' . $i ] ?? '' ) ),
				'author' => sanitize_text_field( wp_unslash( $_POST[ 'review_author_' . $i ] ?? '' ) ),
				'rating' => absint( $_POST[ 'review_rating_' . $i ] ?? 5 ),
			);
		}

		update_option( 'yaamama_homepage_settings', $settings );
		echo '<div class="yaamama-success">تم حفظ التغييرات بنجاح.</div>';
	}

	if ( isset( $_POST['yaamama_homepage_reset'] ) && check_admin_referer( 'yaamama_homepage_settings_nonce' ) ) {
		delete_option( 'yaamama_homepage_settings' );
		echo '<div class="yaamama-success">تمت إعادة التعيين للقيم الافتراضية.</div>';
	}

	$settings = yaamama_get_homepage_settings();
	$section_1 = $settings['section_1'] ?? array();
	if ( empty( $section_1['icons'] ) && ! empty( $settings['icon_marquee'] ) ) {
		$legacy_icons = $settings['icon_marquee']['icons'] ?? array();
		$section_1['title'] = $section_1['title'] ?? ( $settings['icon_marquee']['title'] ?? '' );
		$section_1['text'] = $section_1['text'] ?? ( $settings['icon_marquee']['text'] ?? '' );
		$section_1['speed'] = $section_1['speed'] ?? ( $settings['icon_marquee']['speed'] ?? 25 );
		$section_1['loop'] = $section_1['loop'] ?? true;
		$section_1['icons'] = array();
		foreach ( $legacy_icons as $icon ) {
			if ( empty( $icon['image'] ) ) {
				continue;
			}
			$section_1['icons'][] = array(
				'id'  => 0,
				'url' => esc_url_raw( $icon['image'] ),
			);
		}
	}
	$section_1 = wp_parse_args(
		$section_1,
		array(
			'title' => '',
			'text'  => '',
			'speed' => 25,
			'loop'  => true,
			'icons' => array(),
		)
	);
	$shipping_section = $settings['shipping_section'] ?? array();
	if ( empty( $shipping_section['title'] ) && ! empty( $settings['shipping'] ) ) {
		$shipping_section['title'] = $settings['shipping']['title'] ?? '';
		$shipping_section['text'] = $settings['shipping']['text'] ?? '';
		$shipping_section['video_url'] = $settings['shipping']['video'] ?? '';
		$shipping_section['video_id'] = 0;
	}
	$shipping_section = wp_parse_args(
		$shipping_section,
		array(
			'title'      => '',
			'text'       => '',
			'background' => 'gradient',
			'video_id'   => 0,
			'video_url'  => '',
		)
	);
	?>
	<div class="wrap yaamama-content-page">
		<h1>محتوى الصفحة الرئيسية</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'yaamama_homepage_settings_nonce' ); ?>

			<div class="yaamama-section">
				<h2>قسم الهيرو</h2>
				<div class="yaamama-field">
					<label for="hero_stat_text">نص الإحصائية</label>
					<input class="regular-text" id="hero_stat_text" name="hero_stat_text" type="text"
						value="<?php echo esc_attr( $settings['hero']['stat_text'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="hero_title_line1">عنوان سطر 1</label>
					<input class="regular-text" id="hero_title_line1" name="hero_title_line1" type="text"
						value="<?php echo esc_attr( $settings['hero']['title']['line1'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="hero_title_line2">عنوان سطر 2</label>
					<input class="regular-text" id="hero_title_line2" name="hero_title_line2" type="text"
						value="<?php echo esc_attr( $settings['hero']['title']['line2'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="hero_title_line3">عنوان سطر 3</label>
					<input class="regular-text" id="hero_title_line3" name="hero_title_line3" type="text"
						value="<?php echo esc_attr( $settings['hero']['title']['line3'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="hero_title_highlight">النص المميز</label>
					<input class="regular-text" id="hero_title_highlight" name="hero_title_highlight" type="text"
						value="<?php echo esc_attr( $settings['hero']['title']['highlight'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="hero_cta_text">نص الزر</label>
					<input class="regular-text" id="hero_cta_text" name="hero_cta_text" type="text"
						value="<?php echo esc_attr( $settings['hero']['cta']['text'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="hero_cta_url">رابط الزر</label>
					<input class="regular-text" id="hero_cta_url" name="hero_cta_url" type="url"
						value="<?php echo esc_attr( $settings['hero']['cta']['url'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="hero_conversion_label">عنوان معدل التحويل</label>
					<input class="regular-text" id="hero_conversion_label" name="hero_conversion_label" type="text"
						value="<?php echo esc_attr( $settings['hero']['conversion']['label'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="hero_conversion_value">قيمة معدل التحويل</label>
					<input class="regular-text" id="hero_conversion_value" name="hero_conversion_value" type="text"
						value="<?php echo esc_attr( $settings['hero']['conversion']['value'] ); ?>">
				</div>

				<div class="yaamama-grid">
					<?php foreach ( $settings['hero']['slider_images'] as $index => $slide ) : ?>
						<div class="yaamama-item">
							<h4>صورة السلايدر <?php echo esc_html( $index + 1 ); ?></h4>
							<div class="yaamama-preview" id="hero_slider_preview_<?php echo esc_attr( $index ); ?>">
								<?php if ( ! empty( $slide['src'] ) ) : ?>
									<img src="<?php echo esc_url( $slide['src'] ); ?>" alt="">
								<?php else : ?>
									<span>لا توجد صورة</span>
								<?php endif; ?>
							</div>
							<div class="yaamama-actions">
								<button class="button yaamama-upload-btn" data-target="hero_slider_src_<?php echo esc_attr( $index ); ?>" data-preview="hero_slider_preview_<?php echo esc_attr( $index ); ?>">اختر صورة</button>
								<button class="button yaamama-remove-btn" data-target="hero_slider_src_<?php echo esc_attr( $index ); ?>" data-preview="hero_slider_preview_<?php echo esc_attr( $index ); ?>">إزالة</button>
							</div>
							<input type="text" class="regular-text" id="hero_slider_src_<?php echo esc_attr( $index ); ?>" name="hero_slider_src_<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $slide['src'] ); ?>" placeholder="رابط الصورة">
							<input type="text" class="regular-text" name="hero_slider_alt_<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $slide['alt'] ); ?>" placeholder="النص البديل">
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>القسم الأول (الأيقونات)</h2>
				<div class="yaamama-field">
					<label for="section_1_title">العنوان</label>
					<input class="regular-text" id="section_1_title" name="section_1_title" type="text"
						value="<?php echo esc_attr( $section_1['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="section_1_text">النص</label>
					<textarea class="large-text" rows="2" id="section_1_text" name="section_1_text"><?php echo esc_textarea( $section_1['text'] ); ?></textarea>
				</div>
				<div class="yaamama-field">
					<label for="section_1_speed">سرعة الحركة (بالثواني)</label>
					<input class="small-text" id="section_1_speed" name="section_1_speed" type="number" min="5" max="60"
						value="<?php echo esc_attr( $section_1['speed'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label>
						<input type="checkbox" name="section_1_loop" <?php checked( ! empty( $section_1['loop'] ) ); ?>>
						تكرار الحركة باستمرار
					</label>
				</div>
				<div class="yaamama-field">
					<label>أيقونات القسم</label>
					<div class="yaamama-actions">
						<button class="button yaamama-multi-upload-btn" data-target="section_1_icons" data-preview="section_1_icons_preview">اختر أيقونات</button>
						<button class="button yaamama-multi-remove-btn" data-target="section_1_icons" data-preview="section_1_icons_preview">إزالة الكل</button>
					</div>
					<input type="hidden" id="section_1_icons" name="section_1_icons" value="<?php echo esc_attr( wp_json_encode( $section_1['icons'] ) ); ?>">
					<div class="yaamama-media-grid" id="section_1_icons_preview" data-target="section_1_icons">
						<?php if ( empty( $section_1['icons'] ) ) : ?>
							<span>لا توجد أيقونات</span>
						<?php else : ?>
							<?php foreach ( $section_1['icons'] as $icon ) :
								$icon_id = absint( $icon['id'] ?? 0 );
								$icon_url = $icon['url'] ?? '';
								$icon_src = $icon_id ? wp_get_attachment_url( $icon_id ) : $icon_url;
								if ( ! $icon_src ) {
									continue;
								}
								?>
								<div class="yaamama-media-item" data-id="<?php echo esc_attr( $icon_id ); ?>">
									<img src="<?php echo esc_url( $icon_src ); ?>" alt="">
									<a href="#" class="yaamama-media-remove">إزالة</a>
								</div>
							<?php endforeach; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>قسم الشحن</h2>
				<div class="yaamama-field">
					<label for="shipping_section_title">العنوان</label>
					<input class="regular-text" id="shipping_section_title" name="shipping_section_title" type="text"
						value="<?php echo esc_attr( $shipping_section['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="shipping_section_text">النص</label>
					<textarea class="large-text" rows="2" id="shipping_section_text" name="shipping_section_text"><?php echo esc_textarea( $shipping_section['text'] ); ?></textarea>
				</div>
				<div class="yaamama-field">
					<label for="shipping_section_background">الخلفية</label>
					<select id="shipping_section_background" name="shipping_section_background" class="regular-text">
						<option value="gradient" <?php selected( $shipping_section['background'], 'gradient' ); ?>>خلفية ملونة</option>
						<option value="light" <?php selected( $shipping_section['background'], 'light' ); ?>>خلفية فاتحة</option>
					</select>
				</div>
				<div class="yaamama-grid">
					<div class="yaamama-item">
						<h4>وسائط القسم</h4>
						<div class="yaamama-preview" id="shipping_video_preview">
							<?php
							$shipping_media_id = absint( $shipping_section['video_id'] ?? 0 );
							$shipping_media_url = $shipping_section['video_url'] ?? '';
							if ( $shipping_media_id ) {
								$shipping_media_url = wp_get_attachment_url( $shipping_media_id ) ?: $shipping_media_url;
							}
							$shipping_media_type = $shipping_media_url ? wp_check_filetype( $shipping_media_url ) : array();
							$shipping_is_video = ! empty( $shipping_media_type['type'] ) && 0 === strpos( $shipping_media_type['type'], 'video/' );
							$shipping_is_image = ! empty( $shipping_media_type['type'] ) && 0 === strpos( $shipping_media_type['type'], 'image/' );
							?>
							<?php if ( $shipping_media_url && $shipping_is_video ) : ?>
								<video src="<?php echo esc_url( $shipping_media_url ); ?>" controls></video>
							<?php elseif ( $shipping_media_url && $shipping_is_image ) : ?>
								<img src="<?php echo esc_url( $shipping_media_url ); ?>" alt="">
							<?php else : ?>
								<span>لا يوجد ملف</span>
							<?php endif; ?>
						</div>
						<div class="yaamama-actions">
							<button class="button yaamama-upload-btn" data-target-id="shipping_section_video_id" data-target-url="shipping_section_video_url" data-preview="shipping_video_preview" data-library="image,video" data-type="media">اختر وسائط</button>
							<button class="button yaamama-remove-btn" data-target-id="shipping_section_video_id" data-target-url="shipping_section_video_url" data-preview="shipping_video_preview" data-type="media">إزالة</button>
						</div>
						<input type="hidden" id="shipping_section_video_id" name="shipping_section_video_id" value="<?php echo esc_attr( $shipping_section['video_id'] ); ?>">
						<input type="text" class="regular-text" id="shipping_section_video_url" name="shipping_section_video_url" value="<?php echo esc_attr( $shipping_section['video_url'] ); ?>" placeholder="رابط الوسائط">
					</div>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>قسم لماذا يمامة</h2>
				<div class="yaamama-field">
					<label for="why_us_title">العنوان</label>
					<input class="regular-text" id="why_us_title" name="why_us_title" type="text"
						value="<?php echo esc_attr( $settings['why_us']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="why_us_tagline">الوصف المختصر</label>
					<input class="regular-text" id="why_us_tagline" name="why_us_tagline" type="text"
						value="<?php echo esc_attr( $settings['why_us']['tagline'] ); ?>">
				</div>
			</div>

			<div class="yaamama-section">
				<h2>مميزات المنصة</h2>
				<div class="yaamama-field">
					<label for="feature_design_title">عنوان اتقان التصميم</label>
					<input class="regular-text" id="feature_design_title" name="feature_design_title" type="text"
						value="<?php echo esc_attr( $settings['features']['design']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="feature_design_desc">وصف اتقان التصميم</label>
					<textarea class="large-text" rows="2" id="feature_design_desc" name="feature_design_desc"><?php echo esc_textarea( $settings['features']['design']['desc'] ); ?></textarea>
				</div>

				<div class="yaamama-field">
					<label for="feature_support_title">عنوان دعم الخبراء</label>
					<input class="regular-text" id="feature_support_title" name="feature_support_title" type="text"
						value="<?php echo esc_attr( $settings['features']['support']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="feature_support_desc">وصف دعم الخبراء</label>
					<textarea class="large-text" rows="2" id="feature_support_desc" name="feature_support_desc"><?php echo esc_textarea( $settings['features']['support']['desc'] ); ?></textarea>
				</div>
				<div class="yaamama-grid">
					<div class="yaamama-item">
						<h4>صورة دعم الخبراء</h4>
						<div class="yaamama-preview" id="feature_support_preview">
							<?php if ( ! empty( $settings['features']['support']['image'] ) ) : ?>
								<img src="<?php echo esc_url( $settings['features']['support']['image'] ); ?>" alt="">
							<?php else : ?>
								<span>لا توجد صورة</span>
							<?php endif; ?>
						</div>
						<div class="yaamama-actions">
							<button class="button yaamama-upload-btn" data-target="feature_support_image" data-preview="feature_support_preview">اختر صورة</button>
							<button class="button yaamama-remove-btn" data-target="feature_support_image" data-preview="feature_support_preview">إزالة</button>
						</div>
						<input type="text" class="regular-text" id="feature_support_image" name="feature_support_image" value="<?php echo esc_attr( $settings['features']['support']['image'] ); ?>" placeholder="رابط الصورة">
						<input type="text" class="regular-text" name="feature_support_alt" value="<?php echo esc_attr( $settings['features']['support']['alt'] ); ?>" placeholder="النص البديل">
					</div>
				</div>

				<div class="yaamama-field">
					<label for="feature_responsive_title">عنوان التجاوب</label>
					<input class="regular-text" id="feature_responsive_title" name="feature_responsive_title" type="text"
						value="<?php echo esc_attr( $settings['features']['responsive_text']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="feature_responsive_desc">وصف التجاوب</label>
					<textarea class="large-text" rows="2" id="feature_responsive_desc" name="feature_responsive_desc"><?php echo esc_textarea( $settings['features']['responsive_text']['desc'] ); ?></textarea>
				</div>

				<div class="yaamama-field">
					<label for="feature_speed_title">عنوان السرعة</label>
					<input class="regular-text" id="feature_speed_title" name="feature_speed_title" type="text"
						value="<?php echo esc_attr( $settings['features']['speed']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="feature_speed_desc">وصف السرعة</label>
					<textarea class="large-text" rows="2" id="feature_speed_desc" name="feature_speed_desc"><?php echo esc_textarea( $settings['features']['speed']['desc'] ); ?></textarea>
				</div>

				<div class="yaamama-field">
					<label for="feature_control_title">عنوان التحكم</label>
					<input class="regular-text" id="feature_control_title" name="feature_control_title" type="text"
						value="<?php echo esc_attr( $settings['features']['control']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="feature_control_desc">وصف التحكم</label>
					<textarea class="large-text" rows="2" id="feature_control_desc" name="feature_control_desc"><?php echo esc_textarea( $settings['features']['control']['desc'] ); ?></textarea>
				</div>
				<div class="yaamama-grid">
					<div class="yaamama-item">
						<h4>صورة التحكم</h4>
						<div class="yaamama-preview" id="feature_control_preview">
							<?php if ( ! empty( $settings['features']['control']['image'] ) ) : ?>
								<img src="<?php echo esc_url( $settings['features']['control']['image'] ); ?>" alt="">
							<?php else : ?>
								<span>لا توجد صورة</span>
							<?php endif; ?>
						</div>
						<div class="yaamama-actions">
							<button class="button yaamama-upload-btn" data-target="feature_control_image" data-preview="feature_control_preview">اختر صورة</button>
							<button class="button yaamama-remove-btn" data-target="feature_control_image" data-preview="feature_control_preview">إزالة</button>
						</div>
						<input type="text" class="regular-text" id="feature_control_image" name="feature_control_image" value="<?php echo esc_attr( $settings['features']['control']['image'] ); ?>" placeholder="رابط الصورة">
						<input type="text" class="regular-text" name="feature_control_alt" value="<?php echo esc_attr( $settings['features']['control']['alt'] ); ?>" placeholder="النص البديل">
					</div>
				</div>

				<div class="yaamama-field">
					<label for="feature_launch_title">عنوان الانطلاق</label>
					<input class="regular-text" id="feature_launch_title" name="feature_launch_title" type="text"
						value="<?php echo esc_attr( $settings['features']['launch']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="feature_launch_desc">وصف الانطلاق</label>
					<textarea class="large-text" rows="2" id="feature_launch_desc" name="feature_launch_desc"><?php echo esc_textarea( $settings['features']['launch']['desc'] ); ?></textarea>
				</div>
				<div class="yaamama-grid">
					<div class="yaamama-item">
						<h4>صورة التجاوب</h4>
						<div class="yaamama-preview" id="feature_responsive_preview">
							<?php if ( ! empty( $settings['features']['responsive_image']['image'] ) ) : ?>
								<img src="<?php echo esc_url( $settings['features']['responsive_image']['image'] ); ?>" alt="">
							<?php else : ?>
								<span>لا توجد صورة</span>
							<?php endif; ?>
						</div>
						<div class="yaamama-actions">
							<button class="button yaamama-upload-btn" data-target="feature_responsive_image" data-preview="feature_responsive_preview">اختر صورة</button>
							<button class="button yaamama-remove-btn" data-target="feature_responsive_image" data-preview="feature_responsive_preview">إزالة</button>
						</div>
						<input type="text" class="regular-text" id="feature_responsive_image" name="feature_responsive_image" value="<?php echo esc_attr( $settings['features']['responsive_image']['image'] ); ?>" placeholder="رابط الصورة">
						<input type="text" class="regular-text" name="feature_responsive_alt" value="<?php echo esc_attr( $settings['features']['responsive_image']['alt'] ); ?>" placeholder="النص البديل">
					</div>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>كيف تبدأ</h2>
				<div class="yaamama-field">
					<label for="how_start_title">عنوان القسم</label>
					<input class="regular-text" id="how_start_title" name="how_start_title" type="text"
						value="<?php echo esc_attr( $settings['how_start']['title'] ); ?>">
				</div>
				<div class="yaamama-grid">
					<?php foreach ( $settings['how_start']['steps'] as $index => $step ) : ?>
						<div class="yaamama-item">
							<h4>خطوة <?php echo esc_html( $index + 1 ); ?></h4>
							<div class="yaamama-field">
								<label>الرقم</label>
								<input class="regular-text" name="step_number_<?php echo esc_attr( $index ); ?>" type="text"
									value="<?php echo esc_attr( $step['number'] ); ?>">
							</div>
							<div class="yaamama-field">
								<label>العنوان</label>
								<input class="regular-text" name="step_title_<?php echo esc_attr( $index ); ?>" type="text"
									value="<?php echo esc_attr( $step['title'] ); ?>">
							</div>
							<div class="yaamama-field">
								<label>الوصف</label>
								<textarea class="large-text" rows="2" name="step_desc_<?php echo esc_attr( $index ); ?>"><?php echo esc_textarea( $step['desc'] ); ?></textarea>
							</div>
							<div class="yaamama-preview" id="step_preview_<?php echo esc_attr( $index ); ?>">
								<?php if ( ! empty( $step['image'] ) ) : ?>
									<img src="<?php echo esc_url( $step['image'] ); ?>" alt="">
								<?php else : ?>
									<span>لا توجد صورة</span>
								<?php endif; ?>
							</div>
							<div class="yaamama-actions">
								<button class="button yaamama-upload-btn" data-target="step_image_<?php echo esc_attr( $index ); ?>" data-preview="step_preview_<?php echo esc_attr( $index ); ?>">اختر صورة</button>
								<button class="button yaamama-remove-btn" data-target="step_image_<?php echo esc_attr( $index ); ?>" data-preview="step_preview_<?php echo esc_attr( $index ); ?>">إزالة</button>
							</div>
							<input type="text" class="regular-text" id="step_image_<?php echo esc_attr( $index ); ?>" name="step_image_<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $step['image'] ); ?>" placeholder="رابط الصورة">
							<input type="text" class="regular-text" name="step_alt_<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $step['alt'] ); ?>" placeholder="النص البديل">
							<label><input type="checkbox" name="step_reverse_<?php echo esc_attr( $index ); ?>" <?php checked( $step['reverse'] ); ?>> عكس الاتجاه</label>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>المجالات</h2>
				<div class="yaamama-field">
					<label for="categories_title">العنوان</label>
					<input class="regular-text" id="categories_title" name="categories_title" type="text"
						value="<?php echo esc_attr( $settings['categories']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="categories_subtitle">الوصف</label>
					<input class="regular-text" id="categories_subtitle" name="categories_subtitle" type="text"
						value="<?php echo esc_attr( $settings['categories']['subtitle'] ); ?>">
				</div>
				<div class="yaamama-grid">
					<?php foreach ( $settings['categories']['items'] as $index => $item ) : ?>
						<div class="yaamama-item">
							<h4>مجال <?php echo esc_html( $index + 1 ); ?></h4>
							<div class="yaamama-field">
								<label>العنوان</label>
								<input class="regular-text" name="category_label_<?php echo esc_attr( $index ); ?>" type="text"
									value="<?php echo esc_attr( $item['label'] ); ?>">
							</div>
							<div class="yaamama-field">
								<label>الوصف</label>
								<textarea class="large-text" rows="2" name="category_desc_<?php echo esc_attr( $index ); ?>"><?php echo esc_textarea( $item['desc'] ); ?></textarea>
							</div>
							<div class="yaamama-preview" id="category_preview_<?php echo esc_attr( $index ); ?>">
								<?php if ( ! empty( $item['image'] ) ) : ?>
									<img src="<?php echo esc_url( $item['image'] ); ?>" alt="">
								<?php else : ?>
									<span>لا توجد صورة</span>
								<?php endif; ?>
							</div>
							<div class="yaamama-actions">
								<button class="button yaamama-upload-btn" data-target="category_image_<?php echo esc_attr( $index ); ?>" data-preview="category_preview_<?php echo esc_attr( $index ); ?>">اختر صورة</button>
								<button class="button yaamama-remove-btn" data-target="category_image_<?php echo esc_attr( $index ); ?>" data-preview="category_preview_<?php echo esc_attr( $index ); ?>">إزالة</button>
							</div>
							<input type="text" class="regular-text" id="category_image_<?php echo esc_attr( $index ); ?>" name="category_image_<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $item['image'] ); ?>" placeholder="رابط الصورة">
							<input type="text" class="regular-text" name="category_alt_<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $item['alt'] ); ?>" placeholder="النص البديل">
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>قوالب المتاجر</h2>
				<div class="yaamama-field">
					<label for="templates_title">العنوان</label>
					<input class="regular-text" id="templates_title" name="templates_title" type="text"
						value="<?php echo esc_attr( $settings['templates']['title'] ); ?>">
				</div>
				<div class="yaamama-grid">
					<?php foreach ( $settings['templates']['items'] as $index => $item ) : ?>
						<div class="yaamama-item">
							<h4>قالب <?php echo esc_html( $index + 1 ); ?></h4>
							<div class="yaamama-preview" id="template_preview_<?php echo esc_attr( $index ); ?>">
								<?php if ( ! empty( $item['image'] ) ) : ?>
									<img src="<?php echo esc_url( $item['image'] ); ?>" alt="">
								<?php else : ?>
									<span>لا توجد صورة</span>
								<?php endif; ?>
							</div>
							<div class="yaamama-actions">
								<button class="button yaamama-upload-btn" data-target="template_image_<?php echo esc_attr( $index ); ?>" data-preview="template_preview_<?php echo esc_attr( $index ); ?>">اختر صورة</button>
								<button class="button yaamama-remove-btn" data-target="template_image_<?php echo esc_attr( $index ); ?>" data-preview="template_preview_<?php echo esc_attr( $index ); ?>">إزالة</button>
							</div>
							<input type="text" class="regular-text" id="template_image_<?php echo esc_attr( $index ); ?>" name="template_image_<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $item['image'] ); ?>" placeholder="رابط الصورة">
							<input type="text" class="regular-text" name="template_alt_<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $item['alt'] ); ?>" placeholder="النص البديل">
						</div>
					<?php endforeach; ?>
				</div>

				<h3 style="margin-top:24px;">صور القوالب حسب التصنيف</h3>
				<?php if ( empty( $template_terms ) ) : ?>
					<p>لا توجد تصنيفات منتجات حالياً.</p>
				<?php else : ?>
					<?php foreach ( $template_terms as $term ) :
						$term_id = (int) $term->term_id;
						$term_items = $settings['templates']['category_items'][ $term_id ] ?? array();
						?>
						<h4><?php echo esc_html( $term->name ); ?></h4>
						<div class="yaamama-grid">
							<?php for ( $i = 0; $i < 4; $i++ ) :
								$item = $term_items[ $i ] ?? array( 'image' => '', 'alt' => '' );
								$preview_id = 'template_cat_' . $term_id . '_preview_' . $i;
								$image_id = 'template_cat_' . $term_id . '_image_' . $i;
								?>
								<div class="yaamama-item">
									<h4>صورة <?php echo esc_html( $i + 1 ); ?></h4>
									<div class="yaamama-preview" id="<?php echo esc_attr( $preview_id ); ?>">
										<?php if ( ! empty( $item['image'] ) ) : ?>
											<img src="<?php echo esc_url( $item['image'] ); ?>" alt="">
										<?php else : ?>
											<span>لا توجد صورة</span>
										<?php endif; ?>
									</div>
									<div class="yaamama-actions">
										<button class="button yaamama-upload-btn" data-target="<?php echo esc_attr( $image_id ); ?>" data-preview="<?php echo esc_attr( $preview_id ); ?>">اختر صورة</button>
										<button class="button yaamama-remove-btn" data-target="<?php echo esc_attr( $image_id ); ?>" data-preview="<?php echo esc_attr( $preview_id ); ?>">إزالة</button>
									</div>
									<input type="text" class="regular-text" id="<?php echo esc_attr( $image_id ); ?>" name="<?php echo esc_attr( $image_id ); ?>" value="<?php echo esc_attr( $item['image'] ); ?>" placeholder="رابط الصورة">
									<input type="text" class="regular-text" name="<?php echo esc_attr( 'template_cat_' . $term_id . '_alt_' . $i ); ?>" value="<?php echo esc_attr( $item['alt'] ); ?>" placeholder="النص البديل">
								</div>
							<?php endfor; ?>
						</div>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<div class="yaamama-section">
				<h2>لماذا يثق بنا العملاء</h2>
				<div class="yaamama-field">
					<label for="trust_title">العنوان</label>
					<input class="regular-text" id="trust_title" name="trust_title" type="text"
						value="<?php echo esc_attr( $settings['trust']['title'] ); ?>">
				</div>
				<div class="yaamama-grid">
					<?php foreach ( $settings['trust']['cards'] as $index => $card ) : ?>
						<div class="yaamama-item">
							<h4>ميزة <?php echo esc_html( $index + 1 ); ?></h4>
							<div class="yaamama-field">
								<label>العنوان</label>
								<input class="regular-text" name="trust_title_<?php echo esc_attr( $index ); ?>" type="text"
									value="<?php echo esc_attr( $card['title'] ); ?>">
							</div>
							<div class="yaamama-field">
								<label>الوصف</label>
								<textarea class="large-text" rows="2" name="trust_desc_<?php echo esc_attr( $index ); ?>"><?php echo esc_textarea( $card['desc'] ); ?></textarea>
							</div>
							<div class="yaamama-preview" id="trust_preview_<?php echo esc_attr( $index ); ?>">
								<?php if ( ! empty( $card['image'] ) ) : ?>
									<img src="<?php echo esc_url( $card['image'] ); ?>" alt="">
								<?php else : ?>
									<span>لا توجد صورة</span>
								<?php endif; ?>
							</div>
							<div class="yaamama-actions">
								<button class="button yaamama-upload-btn" data-target="trust_image_<?php echo esc_attr( $index ); ?>" data-preview="trust_preview_<?php echo esc_attr( $index ); ?>">اختر صورة</button>
								<button class="button yaamama-remove-btn" data-target="trust_image_<?php echo esc_attr( $index ); ?>" data-preview="trust_preview_<?php echo esc_attr( $index ); ?>">إزالة</button>
							</div>
							<input type="text" class="regular-text" id="trust_image_<?php echo esc_attr( $index ); ?>" name="trust_image_<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $card['image'] ); ?>" placeholder="رابط الصورة">
							<input type="text" class="regular-text" name="trust_alt_<?php echo esc_attr( $index ); ?>" value="<?php echo esc_attr( $card['alt'] ); ?>" placeholder="النص البديل">
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>آراء العملاء</h2>
				<div class="yaamama-field">
					<label for="reviews_title">العنوان</label>
					<input class="regular-text" id="reviews_title" name="reviews_title" type="text"
						value="<?php echo esc_attr( $settings['reviews']['title'] ); ?>">
				</div>
				<div class="yaamama-grid">
					<?php foreach ( $settings['reviews']['items'] as $index => $review ) : ?>
						<div class="yaamama-item">
							<h4>رأي <?php echo esc_html( $index + 1 ); ?></h4>
							<div class="yaamama-field">
								<label>النص</label>
								<textarea class="large-text" rows="2" name="review_text_<?php echo esc_attr( $index ); ?>"><?php echo esc_textarea( $review['text'] ); ?></textarea>
							</div>
							<div class="yaamama-field">
								<label>الكاتب</label>
								<input class="regular-text" name="review_author_<?php echo esc_attr( $index ); ?>" type="text"
									value="<?php echo esc_attr( $review['author'] ); ?>">
							</div>
							<div class="yaamama-field">
								<label>التقييم (1-5)</label>
								<input class="small-text" name="review_rating_<?php echo esc_attr( $index ); ?>" type="number" min="1" max="5"
									value="<?php echo esc_attr( $review['rating'] ); ?>">
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<p>
				<button type="submit" name="yaamama_homepage_save" class="button button-primary">حفظ التغييرات</button>
			</p>

			<div class="yaamama-section yaamama-reset">
				<h2>إعادة التعيين</h2>
				<p>سيتم حذف جميع التعديلات والعودة للمحتوى الافتراضي.</p>
				<button type="submit" name="yaamama_homepage_reset" class="button">إعادة التعيين</button>
			</div>
		</form>
	</div>
	<?php
}

function yaamama_render_about_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['yaamama_about_save'] ) && check_admin_referer( 'yaamama_about_settings_nonce' ) ) {
		$settings = array(
			'hero'     => array(
				'title'      => sanitize_text_field( wp_unslash( $_POST['about_hero_title'] ?? '' ) ),
				'image'      => esc_url_raw( wp_unslash( $_POST['about_hero_image'] ?? '' ) ),
				'alt'        => sanitize_text_field( wp_unslash( $_POST['about_hero_alt'] ?? '' ) ),
				'paragraphs' => array(),
			),
			'why'      => array(
				'title'   => sanitize_text_field( wp_unslash( $_POST['about_why_title'] ?? '' ) ),
				'tagline' => sanitize_text_field( wp_unslash( $_POST['about_why_tagline'] ?? '' ) ),
			),
			'features' => array(),
		);

		for ( $i = 0; $i < 3; $i++ ) {
			$settings['hero']['paragraphs'][] = sanitize_textarea_field( wp_unslash( $_POST[ 'about_paragraph_' . $i ] ?? '' ) );
		}

		for ( $i = 0; $i < 6; $i++ ) {
			$settings['features'][] = array(
				'icon' => esc_url_raw( wp_unslash( $_POST[ 'about_feature_icon_' . $i ] ?? '' ) ),
				'alt'  => sanitize_text_field( wp_unslash( $_POST[ 'about_feature_alt_' . $i ] ?? '' ) ),
				'text' => sanitize_textarea_field( wp_unslash( $_POST[ 'about_feature_text_' . $i ] ?? '' ) ),
			);
		}

		update_option( 'yaamama_about_settings', $settings );
		echo '<div class="yaamama-success">تم حفظ التغييرات بنجاح.</div>';
	}

	if ( isset( $_POST['yaamama_about_reset'] ) && check_admin_referer( 'yaamama_about_settings_nonce' ) ) {
		delete_option( 'yaamama_about_settings' );
		echo '<div class="yaamama-success">تمت إعادة التعيين للقيم الافتراضية.</div>';
	}

	$settings = yaamama_get_about_settings();
	$features = $settings['features'];
	?>
	<div class="wrap yaamama-content-page">
		<h1>محتوى صفحة من نحن</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'yaamama_about_settings_nonce' ); ?>

			<div class="yaamama-section">
				<h2>قسم التعريف</h2>
				<div class="yaamama-field">
					<label for="about_hero_title">العنوان</label>
					<input class="regular-text" id="about_hero_title" name="about_hero_title" type="text"
						value="<?php echo esc_attr( $settings['hero']['title'] ); ?>">
				</div>
				<div class="yaamama-grid">
					<div class="yaamama-item">
						<h4>صورة القسم</h4>
						<div class="yaamama-preview" id="about_hero_preview">
							<?php if ( ! empty( $settings['hero']['image'] ) ) : ?>
								<img src="<?php echo esc_url( $settings['hero']['image'] ); ?>" alt="">
							<?php else : ?>
								<span>لا توجد صورة</span>
							<?php endif; ?>
						</div>
						<div class="yaamama-actions">
							<button class="button yaamama-upload-btn" data-target="about_hero_image" data-preview="about_hero_preview">اختر صورة</button>
							<button class="button yaamama-remove-btn" data-target="about_hero_image" data-preview="about_hero_preview">إزالة</button>
						</div>
						<input type="text" class="regular-text" id="about_hero_image" name="about_hero_image"
							value="<?php echo esc_attr( $settings['hero']['image'] ); ?>" placeholder="رابط الصورة">
						<input type="text" class="regular-text" name="about_hero_alt"
							value="<?php echo esc_attr( $settings['hero']['alt'] ); ?>" placeholder="النص البديل">
					</div>
				</div>
				<?php for ( $i = 0; $i < 3; $i++ ) : ?>
					<div class="yaamama-field">
						<label>فقرة <?php echo esc_html( $i + 1 ); ?></label>
						<textarea class="large-text" rows="2" name="about_paragraph_<?php echo esc_attr( $i ); ?>"><?php echo esc_textarea( $settings['hero']['paragraphs'][ $i ] ?? '' ); ?></textarea>
					</div>
				<?php endfor; ?>
			</div>

			<div class="yaamama-section">
				<h2>قسم لماذا يمامة</h2>
				<div class="yaamama-field">
					<label for="about_why_title">العنوان</label>
					<input class="regular-text" id="about_why_title" name="about_why_title" type="text"
						value="<?php echo esc_attr( $settings['why']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="about_why_tagline">الوصف المختصر</label>
					<input class="regular-text" id="about_why_tagline" name="about_why_tagline" type="text"
						value="<?php echo esc_attr( $settings['why']['tagline'] ); ?>">
				</div>
			</div>

			<div class="yaamama-section">
				<h2>المميزات</h2>
				<div class="yaamama-grid">
					<?php for ( $i = 0; $i < 6; $i++ ) :
						$feature = $features[ $i ] ?? array( 'icon' => '', 'alt' => '', 'text' => '' );
						?>
						<div class="yaamama-item">
							<h4>ميزة <?php echo esc_html( $i + 1 ); ?></h4>
							<div class="yaamama-preview" id="about_feature_preview_<?php echo esc_attr( $i ); ?>">
								<?php if ( ! empty( $feature['icon'] ) ) : ?>
									<img src="<?php echo esc_url( $feature['icon'] ); ?>" alt="">
								<?php else : ?>
									<span>لا توجد صورة</span>
								<?php endif; ?>
							</div>
							<div class="yaamama-actions">
								<button class="button yaamama-upload-btn" data-target="about_feature_icon_<?php echo esc_attr( $i ); ?>" data-preview="about_feature_preview_<?php echo esc_attr( $i ); ?>">اختر صورة</button>
								<button class="button yaamama-remove-btn" data-target="about_feature_icon_<?php echo esc_attr( $i ); ?>" data-preview="about_feature_preview_<?php echo esc_attr( $i ); ?>">إزالة</button>
							</div>
							<input type="text" class="regular-text" id="about_feature_icon_<?php echo esc_attr( $i ); ?>" name="about_feature_icon_<?php echo esc_attr( $i ); ?>"
								value="<?php echo esc_attr( $feature['icon'] ); ?>" placeholder="رابط الأيقونة">
							<input type="text" class="regular-text" name="about_feature_alt_<?php echo esc_attr( $i ); ?>"
								value="<?php echo esc_attr( $feature['alt'] ); ?>" placeholder="النص البديل">
							<textarea class="large-text" rows="2" name="about_feature_text_<?php echo esc_attr( $i ); ?>"><?php echo esc_textarea( $feature['text'] ); ?></textarea>
						</div>
					<?php endfor; ?>
				</div>
			</div>

			<p>
				<button type="submit" name="yaamama_about_save" class="button button-primary">حفظ التغييرات</button>
			</p>

			<div class="yaamama-section yaamama-reset">
				<h2>إعادة التعيين</h2>
				<p>سيتم حذف جميع التعديلات والعودة للمحتوى الافتراضي.</p>
				<button type="submit" name="yaamama_about_reset" class="button">إعادة التعيين</button>
			</div>
		</form>
	</div>
	<?php
}

function yaamama_render_footer_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['yaamama_footer_save'] ) && check_admin_referer( 'yaamama_footer_settings_nonce' ) ) {
		$settings = array(
			'logo'      => array(
				'image' => esc_url_raw( wp_unslash( $_POST['footer_logo_image'] ?? '' ) ),
				'alt'   => sanitize_text_field( wp_unslash( $_POST['footer_logo_alt'] ?? '' ) ),
				'url'   => esc_url_raw( wp_unslash( $_POST['footer_logo_url'] ?? '' ) ),
			),
			'quick'     => array(
				'title' => sanitize_text_field( wp_unslash( $_POST['footer_quick_title'] ?? '' ) ),
				'items' => array(),
			),
			'policies'  => array(
				'title' => sanitize_text_field( wp_unslash( $_POST['footer_policies_title'] ?? '' ) ),
				'items' => array(),
			),
			'contact'   => array(
				'title'   => sanitize_text_field( wp_unslash( $_POST['footer_contact_title'] ?? '' ) ),
				'address' => array(
					'text' => sanitize_text_field( wp_unslash( $_POST['footer_contact_address'] ?? '' ) ),
				),
				'email'   => array(
					'label' => sanitize_text_field( wp_unslash( $_POST['footer_contact_email_label'] ?? '' ) ),
					'url'   => esc_url_raw( wp_unslash( $_POST['footer_contact_email_url'] ?? '' ) ),
				),
				'phone'   => array(
					'label' => sanitize_text_field( wp_unslash( $_POST['footer_contact_phone_label'] ?? '' ) ),
					'url'   => esc_url_raw( wp_unslash( $_POST['footer_contact_phone_url'] ?? '' ) ),
				),
			),
			'copyright' => sanitize_text_field( wp_unslash( $_POST['footer_copyright'] ?? '' ) ),
		);

		for ( $i = 0; $i < 3; $i++ ) {
			$settings['quick']['items'][] = array(
				'label' => sanitize_text_field( wp_unslash( $_POST[ 'footer_quick_label_' . $i ] ?? '' ) ),
				'url'   => esc_url_raw( wp_unslash( $_POST[ 'footer_quick_url_' . $i ] ?? '' ) ),
			);
			$settings['policies']['items'][] = array(
				'label' => sanitize_text_field( wp_unslash( $_POST[ 'footer_policy_label_' . $i ] ?? '' ) ),
				'url'   => esc_url_raw( wp_unslash( $_POST[ 'footer_policy_url_' . $i ] ?? '' ) ),
			);
		}

		update_option( 'yaamama_footer_settings', $settings );
		echo '<div class="yaamama-success">تم حفظ التغييرات بنجاح.</div>';
	}

	if ( isset( $_POST['yaamama_footer_reset'] ) && check_admin_referer( 'yaamama_footer_settings_nonce' ) ) {
		delete_option( 'yaamama_footer_settings' );
		echo '<div class="yaamama-success">تمت إعادة التعيين للقيم الافتراضية.</div>';
	}

	$settings = yaamama_get_footer_settings();
	?>
	<div class="wrap yaamama-content-page">
		<h1>محتوى الفوتر</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'yaamama_footer_settings_nonce' ); ?>

			<div class="yaamama-section">
				<h2>الشعار</h2>
				<div class="yaamama-grid">
					<div class="yaamama-item">
						<h4>صورة الشعار</h4>
						<div class="yaamama-preview" id="footer_logo_preview">
							<?php if ( ! empty( $settings['logo']['image'] ) ) : ?>
								<img src="<?php echo esc_url( $settings['logo']['image'] ); ?>" alt="">
							<?php else : ?>
								<span>لا توجد صورة</span>
							<?php endif; ?>
						</div>
						<div class="yaamama-actions">
							<button class="button yaamama-upload-btn" data-target="footer_logo_image" data-preview="footer_logo_preview">اختر صورة</button>
							<button class="button yaamama-remove-btn" data-target="footer_logo_image" data-preview="footer_logo_preview">إزالة</button>
						</div>
						<input type="text" class="regular-text" id="footer_logo_image" name="footer_logo_image"
							value="<?php echo esc_attr( $settings['logo']['image'] ); ?>" placeholder="رابط الصورة">
						<input type="text" class="regular-text" name="footer_logo_alt"
							value="<?php echo esc_attr( $settings['logo']['alt'] ); ?>" placeholder="النص البديل">
						<input type="url" class="regular-text" name="footer_logo_url"
							value="<?php echo esc_attr( $settings['logo']['url'] ); ?>" placeholder="رابط الشعار">
					</div>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>روابط سريعة</h2>
				<div class="yaamama-field">
					<label for="footer_quick_title">عنوان القسم</label>
					<input class="regular-text" id="footer_quick_title" name="footer_quick_title" type="text"
						value="<?php echo esc_attr( $settings['quick']['title'] ); ?>">
				</div>
				<div class="yaamama-grid">
					<?php for ( $i = 0; $i < 3; $i++ ) :
						$item = $settings['quick']['items'][ $i ] ?? array( 'label' => '', 'url' => '' );
						?>
						<div class="yaamama-item">
							<h4>رابط <?php echo esc_html( $i + 1 ); ?></h4>
							<input class="regular-text" name="footer_quick_label_<?php echo esc_attr( $i ); ?>" type="text"
								value="<?php echo esc_attr( $item['label'] ); ?>" placeholder="النص">
							<input class="regular-text" name="footer_quick_url_<?php echo esc_attr( $i ); ?>" type="url"
								value="<?php echo esc_attr( $item['url'] ); ?>" placeholder="الرابط">
						</div>
					<?php endfor; ?>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>السياسات</h2>
				<div class="yaamama-field">
					<label for="footer_policies_title">عنوان القسم</label>
					<input class="regular-text" id="footer_policies_title" name="footer_policies_title" type="text"
						value="<?php echo esc_attr( $settings['policies']['title'] ); ?>">
				</div>
				<div class="yaamama-grid">
					<?php for ( $i = 0; $i < 3; $i++ ) :
						$item = $settings['policies']['items'][ $i ] ?? array( 'label' => '', 'url' => '' );
						?>
						<div class="yaamama-item">
							<h4>رابط <?php echo esc_html( $i + 1 ); ?></h4>
							<input class="regular-text" name="footer_policy_label_<?php echo esc_attr( $i ); ?>" type="text"
								value="<?php echo esc_attr( $item['label'] ); ?>" placeholder="النص">
							<input class="regular-text" name="footer_policy_url_<?php echo esc_attr( $i ); ?>" type="url"
								value="<?php echo esc_attr( $item['url'] ); ?>" placeholder="الرابط">
						</div>
					<?php endfor; ?>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>تواصل معنا</h2>
				<div class="yaamama-field">
					<label for="footer_contact_title">عنوان القسم</label>
					<input class="regular-text" id="footer_contact_title" name="footer_contact_title" type="text"
						value="<?php echo esc_attr( $settings['contact']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="footer_contact_address">العنوان</label>
					<input class="regular-text" id="footer_contact_address" name="footer_contact_address" type="text"
						value="<?php echo esc_attr( $settings['contact']['address']['text'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label>البريد الإلكتروني</label>
					<input class="regular-text" name="footer_contact_email_label" type="text"
						value="<?php echo esc_attr( $settings['contact']['email']['label'] ); ?>" placeholder="النص">
					<input class="regular-text" name="footer_contact_email_url" type="url"
						value="<?php echo esc_attr( $settings['contact']['email']['url'] ); ?>" placeholder="الرابط">
				</div>
				<div class="yaamama-field">
					<label>الهاتف</label>
					<input class="regular-text" name="footer_contact_phone_label" type="text"
						value="<?php echo esc_attr( $settings['contact']['phone']['label'] ); ?>" placeholder="النص">
					<input class="regular-text" name="footer_contact_phone_url" type="url"
						value="<?php echo esc_attr( $settings['contact']['phone']['url'] ); ?>" placeholder="الرابط">
				</div>
			</div>

			<div class="yaamama-section">
				<h2>حقوق النشر</h2>
				<input class="regular-text" name="footer_copyright" type="text"
					value="<?php echo esc_attr( $settings['copyright'] ); ?>">
			</div>

			<p>
				<button type="submit" name="yaamama_footer_save" class="button button-primary">حفظ التغييرات</button>
			</p>

			<div class="yaamama-section yaamama-reset">
				<h2>إعادة التعيين</h2>
				<p>سيتم حذف جميع التعديلات والعودة للمحتوى الافتراضي.</p>
				<button type="submit" name="yaamama_footer_reset" class="button">إعادة التعيين</button>
			</div>
		</form>
	</div>
	<?php
}

function yaamama_render_policy_pages_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$policy_keys = array(
		'privacy'  => 'سياسة الخصوصية',
		'refund'   => 'سياسة الاسترجاع',
		'shipping' => 'سياسة الشحن',
	);

	if ( isset( $_POST['yaamama_policy_pages_save'] ) && check_admin_referer( 'yaamama_policy_pages_nonce' ) ) {
		$new_settings = array();
		foreach ( array_keys( $policy_keys ) as $key ) {
			$new_settings[ $key ] = array(
				'title'   => sanitize_text_field( wp_unslash( $_POST[ 'policy_title_' . $key ] ?? '' ) ),
				'content' => wp_kses_post( wp_unslash( $_POST[ 'policy_content_' . $key ] ?? '' ) ),
			);
		}
		update_option( 'yaamama_policy_settings', $new_settings );
		echo '<div class="yaamama-success">تم حفظ صفحات السياسات بنجاح.</div>';
	}

	if ( isset( $_POST['yaamama_policy_pages_reset'] ) && check_admin_referer( 'yaamama_policy_pages_nonce' ) ) {
		delete_option( 'yaamama_policy_settings' );
		echo '<div class="yaamama-success">تمت إعادة التعيين للقيم الافتراضية.</div>';
	}

	$settings = yaamama_get_policy_settings();
	?>
	<div class="wrap yaamama-content-page">
		<h1>صفحات السياسات</h1>
		<p>تحرير محتوى صفحات السياسات المعروضة في الفوتر: سياسة الخصوصية، سياسة الاسترجاع، سياسة الشحن.</p>
		<form method="post" action="">
			<?php wp_nonce_field( 'yaamama_policy_pages_nonce' ); ?>

			<?php foreach ( $policy_keys as $key => $label ) : ?>
				<?php $policy = $settings[ $key ] ?? array( 'title' => '', 'content' => '' ); ?>
				<div class="yaamama-section">
					<h2><?php echo esc_html( $label ); ?></h2>
					<div class="yaamama-field">
						<label for="policy_title_<?php echo esc_attr( $key ); ?>">العنوان</label>
						<input class="regular-text" id="policy_title_<?php echo esc_attr( $key ); ?>" name="policy_title_<?php echo esc_attr( $key ); ?>" type="text"
							value="<?php echo esc_attr( $policy['title'] ?? '' ); ?>">
					</div>
					<div class="yaamama-field">
						<label for="policy_content_<?php echo esc_attr( $key ); ?>">المحتوى</label>
						<?php
						wp_editor(
							$policy['content'] ?? '',
							'policy_content_' . $key,
							array(
								'textarea_name' => 'policy_content_' . $key,
								'textarea_rows' => 16,
								'media_buttons' => true,
								'teeny'         => false,
								'quicktags'     => true,
								'tinymce' => true,
								'wpautop'       => true,
							)
						);
						?>
					</div>
				</div>
			<?php endforeach; ?>

			<p>
				<button type="submit" name="yaamama_policy_pages_save" class="button button-primary">حفظ التغييرات</button>
			</p>

			<div class="yaamama-section yaamama-reset">
				<h2>إعادة التعيين</h2>
				<p>سيتم حذف جميع محتويات السياسات والعودة للقيم الافتراضية.</p>
				<button type="submit" name="yaamama_policy_pages_reset" class="button">إعادة التعيين</button>
			</div>
		</form>
	</div>
	<?php
}

function yaamama_render_contact_settings() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	if ( isset( $_POST['yaamama_contact_save'] ) && check_admin_referer( 'yaamama_contact_settings_nonce' ) ) {
		$settings = array(
			'page'     => array(
				'title'       => sanitize_text_field( wp_unslash( $_POST['contact_page_title'] ?? '' ) ),
				'description' => sanitize_textarea_field( wp_unslash( $_POST['contact_page_description'] ?? '' ) ),
			),
			'info'     => array(
				'support_label'  => sanitize_text_field( wp_unslash( $_POST['contact_support_label'] ?? '' ) ),
				'support_value'  => sanitize_text_field( wp_unslash( $_POST['contact_support_value'] ?? '' ) ),
				'work_hours'     => sanitize_text_field( wp_unslash( $_POST['contact_work_hours'] ?? '' ) ),
				'response_time'  => sanitize_text_field( wp_unslash( $_POST['contact_response_time'] ?? '' ) ),
				'social_title'   => sanitize_text_field( wp_unslash( $_POST['contact_social_title'] ?? '' ) ),
				'social_links'   => array(
					'x'         => esc_url_raw( wp_unslash( $_POST['contact_social_x'] ?? '' ) ),
					'instagram' => esc_url_raw( wp_unslash( $_POST['contact_social_instagram'] ?? '' ) ),
					'facebook'  => esc_url_raw( wp_unslash( $_POST['contact_social_facebook'] ?? '' ) ),
				),
				'location_title' => sanitize_text_field( wp_unslash( $_POST['contact_location_title'] ?? '' ) ),
				'location_line1' => sanitize_text_field( wp_unslash( $_POST['contact_location_line1'] ?? '' ) ),
				'location_line2' => sanitize_text_field( wp_unslash( $_POST['contact_location_line2'] ?? '' ) ),
			),
			'success'  => array(
				'title'       => sanitize_text_field( wp_unslash( $_POST['contact_success_title'] ?? '' ) ),
				'subtitle'    => sanitize_text_field( wp_unslash( $_POST['contact_success_subtitle'] ?? '' ) ),
				'button_text' => sanitize_text_field( wp_unslash( $_POST['contact_success_button_text'] ?? '' ) ),
			),
			'mail'     => array(
				'recipient_email' => sanitize_email( wp_unslash( $_POST['contact_recipient_email'] ?? '' ) ),
				'mailer_type'     => sanitize_text_field( wp_unslash( $_POST['contact_mailer_type'] ?? '' ) ),
				'smtp'            => array(
					'host'       => sanitize_text_field( wp_unslash( $_POST['contact_smtp_host'] ?? '' ) ),
					'port'       => sanitize_text_field( wp_unslash( $_POST['contact_smtp_port'] ?? '' ) ),
					'encryption' => sanitize_text_field( wp_unslash( $_POST['contact_smtp_encryption'] ?? '' ) ),
					'username'   => sanitize_text_field( wp_unslash( $_POST['contact_smtp_username'] ?? '' ) ),
					'password'   => sanitize_text_field( wp_unslash( $_POST['contact_smtp_password'] ?? '' ) ),
					'from_name'  => sanitize_text_field( wp_unslash( $_POST['contact_smtp_from_name'] ?? '' ) ),
					'from_email' => sanitize_email( wp_unslash( $_POST['contact_smtp_from_email'] ?? '' ) ),
				),
				'gmail'           => array(
					'email'        => sanitize_email( wp_unslash( $_POST['contact_gmail_email'] ?? '' ) ),
					'app_password' => sanitize_text_field( wp_unslash( $_POST['contact_gmail_app_password'] ?? '' ) ),
					'from_name'    => sanitize_text_field( wp_unslash( $_POST['contact_gmail_from_name'] ?? '' ) ),
					'from_email'   => sanitize_email( wp_unslash( $_POST['contact_gmail_from_email'] ?? '' ) ),
				),
			),
			'floating' => array(
				'whatsapp' => sanitize_text_field( wp_unslash( $_POST['contact_floating_whatsapp'] ?? '' ) ),
				'call'     => sanitize_text_field( wp_unslash( $_POST['contact_floating_call'] ?? '' ) ),
			),
		);

		update_option( 'yaamama_contact_settings', $settings );
		echo '<div class="yaamama-success">تم حفظ التغييرات بنجاح.</div>';
	}

	if ( isset( $_POST['yaamama_contact_reset'] ) && check_admin_referer( 'yaamama_contact_settings_nonce' ) ) {
		delete_option( 'yaamama_contact_settings' );
		echo '<div class="yaamama-success">تمت إعادة التعيين للقيم الافتراضية.</div>';
	}

	$settings = yaamama_get_contact_settings();
	$test_status = sanitize_text_field( wp_unslash( $_GET['contact_test'] ?? '' ) );
	$test_msg = sanitize_text_field( wp_unslash( $_GET['contact_test_msg'] ?? '' ) );
	if ( $test_status ) {
		$notice_class = ( 'success' === $test_status ) ? 'yaamama-success' : 'notice notice-error';
		echo '<div class="' . esc_attr( $notice_class ) . '" style="padding:12px;margin:16px 0;">' . esc_html( $test_msg ?: 'تم تنفيذ اختبار البريد.' ) . '</div>';
	}
	?>
	<div class="wrap yaamama-content-page">
		<h1>محتوى صفحة تواصل معنا</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'yaamama_contact_settings_nonce' ); ?>

			<div class="yaamama-section">
				<h2>محتوى الصفحة</h2>
				<div class="yaamama-field">
					<label for="contact_page_title">العنوان</label>
					<input class="regular-text" id="contact_page_title" name="contact_page_title" type="text"
						value="<?php echo esc_attr( $settings['page']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_page_description">الوصف</label>
					<textarea class="large-text" rows="2" id="contact_page_description" name="contact_page_description"><?php echo esc_textarea( $settings['page']['description'] ); ?></textarea>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>بيانات التواصل</h2>
				<div class="yaamama-field">
					<label for="contact_support_label">عنوان البريد</label>
					<input class="regular-text" id="contact_support_label" name="contact_support_label" type="text"
						value="<?php echo esc_attr( $settings['info']['support_label'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_support_value">البريد الظاهر للزوار</label>
					<input class="regular-text" id="contact_support_value" name="contact_support_value" type="text"
						value="<?php echo esc_attr( $settings['info']['support_value'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_work_hours">ساعات العمل</label>
					<input class="regular-text" id="contact_work_hours" name="contact_work_hours" type="text"
						value="<?php echo esc_attr( $settings['info']['work_hours'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_response_time">وقت الاستجابة</label>
					<input class="regular-text" id="contact_response_time" name="contact_response_time" type="text"
						value="<?php echo esc_attr( $settings['info']['response_time'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_social_title">عنوان وسائل التواصل</label>
					<input class="regular-text" id="contact_social_title" name="contact_social_title" type="text"
						value="<?php echo esc_attr( $settings['info']['social_title'] ); ?>">
				</div>
				<div class="yaamama-grid">
					<div class="yaamama-item">
						<h4>رابط X</h4>
						<input class="regular-text" name="contact_social_x" type="url"
							value="<?php echo esc_attr( $settings['info']['social_links']['x'] ); ?>">
					</div>
					<div class="yaamama-item">
						<h4>رابط Instagram</h4>
						<input class="regular-text" name="contact_social_instagram" type="url"
							value="<?php echo esc_attr( $settings['info']['social_links']['instagram'] ); ?>">
					</div>
					<div class="yaamama-item">
						<h4>رابط Facebook</h4>
						<input class="regular-text" name="contact_social_facebook" type="url"
							value="<?php echo esc_attr( $settings['info']['social_links']['facebook'] ); ?>">
					</div>
				</div>
				<div class="yaamama-field">
					<label for="contact_location_title">عنوان قسم الموقع</label>
					<input class="regular-text" id="contact_location_title" name="contact_location_title" type="text"
						value="<?php echo esc_attr( $settings['info']['location_title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_location_line1">سطر العنوان الأول</label>
					<input class="regular-text" id="contact_location_line1" name="contact_location_line1" type="text"
						value="<?php echo esc_attr( $settings['info']['location_line1'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_location_line2">سطر العنوان الثاني</label>
					<input class="regular-text" id="contact_location_line2" name="contact_location_line2" type="text"
						value="<?php echo esc_attr( $settings['info']['location_line2'] ); ?>">
				</div>
			</div>

			<div class="yaamama-section">
				<h2>رسالة النجاح</h2>
				<div class="yaamama-field">
					<label for="contact_success_title">العنوان</label>
					<input class="regular-text" id="contact_success_title" name="contact_success_title" type="text"
						value="<?php echo esc_attr( $settings['success']['title'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_success_subtitle">النص</label>
					<input class="regular-text" id="contact_success_subtitle" name="contact_success_subtitle" type="text"
						value="<?php echo esc_attr( $settings['success']['subtitle'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_success_button_text">نص الزر</label>
					<input class="regular-text" id="contact_success_button_text" name="contact_success_button_text" type="text"
						value="<?php echo esc_attr( $settings['success']['button_text'] ); ?>">
				</div>
			</div>

			<div class="yaamama-section">
				<h2>إعدادات البريد</h2>
				<div class="yaamama-field">
					<label for="contact_recipient_email">البريد المستلم للرسائل</label>
					<input class="regular-text" id="contact_recipient_email" name="contact_recipient_email" type="email"
						value="<?php echo esc_attr( $settings['mail']['recipient_email'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_mailer_type">نوع البريد</label>
					<select id="contact_mailer_type" name="contact_mailer_type" class="regular-text">
						<option value="smtp" <?php selected( $settings['mail']['mailer_type'], 'smtp' ); ?>>احترافي SMTP</option>
						<option value="gmail" <?php selected( $settings['mail']['mailer_type'], 'gmail' ); ?>>Gmail App Password</option>
					</select>
				</div>

				<div class="yaamama-grid">
					<div class="yaamama-item">
						<h4>إعدادات SMTP</h4>
						<input class="regular-text" name="contact_smtp_host" type="text" placeholder="Host"
							value="<?php echo esc_attr( $settings['mail']['smtp']['host'] ); ?>">
						<input class="regular-text" name="contact_smtp_port" type="text" placeholder="Port"
							value="<?php echo esc_attr( $settings['mail']['smtp']['port'] ); ?>">
						<input class="regular-text" name="contact_smtp_encryption" type="text" placeholder="tls/ssl"
							value="<?php echo esc_attr( $settings['mail']['smtp']['encryption'] ); ?>">
						<input class="regular-text" name="contact_smtp_username" type="text" placeholder="Username"
							value="<?php echo esc_attr( $settings['mail']['smtp']['username'] ); ?>">
						<input class="regular-text" name="contact_smtp_password" type="password" placeholder="Password"
							value="<?php echo esc_attr( $settings['mail']['smtp']['password'] ); ?>">
						<input class="regular-text" name="contact_smtp_from_name" type="text" placeholder="From Name"
							value="<?php echo esc_attr( $settings['mail']['smtp']['from_name'] ); ?>">
						<input class="regular-text" name="contact_smtp_from_email" type="email" placeholder="From Email"
							value="<?php echo esc_attr( $settings['mail']['smtp']['from_email'] ); ?>">
					</div>
					<div class="yaamama-item">
						<h4>إعدادات Gmail App Password</h4>
						<input class="regular-text" name="contact_gmail_email" type="email" placeholder="Gmail Email"
							value="<?php echo esc_attr( $settings['mail']['gmail']['email'] ); ?>">
						<input class="regular-text" name="contact_gmail_app_password" type="password" placeholder="App Password"
							value="<?php echo esc_attr( $settings['mail']['gmail']['app_password'] ); ?>">
						<input class="regular-text" name="contact_gmail_from_name" type="text" placeholder="From Name"
							value="<?php echo esc_attr( $settings['mail']['gmail']['from_name'] ); ?>">
						<input class="regular-text" name="contact_gmail_from_email" type="email" placeholder="From Email"
							value="<?php echo esc_attr( $settings['mail']['gmail']['from_email'] ); ?>">
					</div>
				</div>
			</div>

			<div class="yaamama-section">
				<h2>الأزرار العائمة</h2>
				<div class="yaamama-field">
					<label for="contact_floating_whatsapp">رقم واتساب</label>
					<input class="regular-text" id="contact_floating_whatsapp" name="contact_floating_whatsapp" type="text"
						value="<?php echo esc_attr( $settings['floating']['whatsapp'] ); ?>">
				</div>
				<div class="yaamama-field">
					<label for="contact_floating_call">رقم الاتصال</label>
					<input class="regular-text" id="contact_floating_call" name="contact_floating_call" type="text"
						value="<?php echo esc_attr( $settings['floating']['call'] ); ?>">
				</div>
			</div>

			<p>
				<button type="submit" name="yaamama_contact_save" class="button button-primary">حفظ التغييرات</button>
			</p>

			<div class="yaamama-section yaamama-reset">
				<h2>إعادة التعيين</h2>
				<p>سيتم حذف جميع التعديلات والعودة للمحتوى الافتراضي.</p>
				<button type="submit" name="yaamama_contact_reset" class="button">إعادة التعيين</button>
			</div>
		</form>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
			<?php wp_nonce_field( 'yaamama_contact_test_nonce', 'yaamama_contact_test_nonce' ); ?>
			<input type="hidden" name="action" value="yaamama_contact_test_email">
			<p>
				<button type="submit" class="button">اختبار البريد</button>
			</p>
		</form>
	</div>
	<?php
}
