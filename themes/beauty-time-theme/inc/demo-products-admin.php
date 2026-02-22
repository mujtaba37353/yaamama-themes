<?php
/**
 * Beauty Time — Admin page "منتجات ديمو" (Demo Products)
 * Create / delete WC products & categories from beauty-time mock. See docs/demo-products-spec.md.
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

const BEAUTY_DEMO_OPTION = 'yamama_beauty_demo_ids';
const BEAUTY_DEMO_META   = '_yamama_demo';

/**
 * Register admin menu
 */
function beauty_demo_admin_menu() {
	add_menu_page(
		__( 'منتجات ديمو', 'beauty-time-theme' ),
		__( 'منتجات ديمو', 'beauty-time-theme' ),
		'manage_options',
		'beauty-demo-products',
		'beauty_demo_admin_page',
		'dashicons-products',
		58
	);
}
add_action( 'admin_menu', 'beauty_demo_admin_menu' );

/**
 * Render admin page
 */
function beauty_demo_admin_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$create_nonce = wp_create_nonce( 'beauty_demo_create' );
	$delete_nonce = wp_create_nonce( 'beauty_demo_delete' );
	$report       = '';
	$wc_active    = class_exists( 'WooCommerce' );

	if ( ! $wc_active ) {
		echo '<div class="wrap"><h1>' . esc_html__( 'منتجات ديمو', 'beauty-time-theme' ) . '</h1>';
		echo '<p class="notice notice-warning">' . esc_html__( 'WooCommerce غير مفعّل. فعّله لاستخدام إنشاء/مسح منتجات الديمو.', 'beauty-time-theme' ) . '</p></div>';
		return;
	}

	// Handle GET action for direct creation (for testing/automation) - bypass nonce for admin
	if ( isset( $_GET['action'] ) && current_user_can( 'manage_options' ) ) {
		if ( 'create' === $_GET['action'] ) {
			$report = beauty_demo_create_products();
		}
		if ( 'delete' === $_GET['action'] ) {
			$report = beauty_demo_delete_products();
		}
	}

	if ( isset( $_POST['beauty_demo_action'] ) && isset( $_POST['_wpnonce'] ) ) {
		if ( 'create' === $_POST['beauty_demo_action'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'beauty_demo_create' ) ) {
			$report = beauty_demo_create_products();
		}
		if ( 'delete' === $_POST['beauty_demo_action'] && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_wpnonce'] ) ), 'beauty_demo_delete' ) ) {
			$report = beauty_demo_delete_products();
		}
	}

	echo '<div class="wrap" dir="rtl" style="text-align: right;">';
	echo '<h1>' . esc_html__( 'منتجات ديمو', 'beauty-time-theme' ) . '</h1>';

	if ( $report ) {
		echo '<div class="notice notice-info"><p>' . wp_kses_post( $report ) . '</p></div>';
	}

	echo '<div class="card" style="max-width: 480px; padding: 20px; margin: 16px 0;">';
	echo '<h2 style="margin-top:0;">' . esc_html__( 'إنشاء منتجات الديمو', 'beauty-time-theme' ) . '</h2>';
	echo '<p>' . esc_html__( 'ينشئ التصنيفات ومنتجات الديمو ومنتجات العروض (onsale) المستخرجة من مجلد beauty-time.', 'beauty-time-theme' ) . '</p>';
	echo '<form method="post">';
	echo wp_nonce_field( 'beauty_demo_create', '_wpnonce', true, false );
	echo '<input type="hidden" name="beauty_demo_action" value="create">';
	submit_button( __( 'إنشاء منتجات الديمو', 'beauty-time-theme' ), 'primary', 'submit', false );
	echo '</form>';
	echo '</div>';

	echo '<div class="card" style="max-width: 480px; padding: 20px; margin: 16px 0;">';
	echo '<h2 style="margin-top:0;">' . esc_html__( 'مسح منتجات الديمو', 'beauty-time-theme' ) . '</h2>';
	echo '<p>' . esc_html__( 'يحذف فقط العناصر المُنشأة عبر «إنشاء منتجات الديمو»، بما في ذلك الأقسام ومنتجات العروض.', 'beauty-time-theme' ) . '</p>';
	echo '<form method="post" id="beauty-demo-delete-form" onsubmit="return confirm(\'' . esc_js( __( 'هل أنت متأكد من مسح كل منتجات الديمو؟', 'beauty-time-theme' ) ) . '\');">';
	echo wp_nonce_field( 'beauty_demo_delete', '_wpnonce', true, false );
	echo '<input type="hidden" name="beauty_demo_action" value="delete">';
	submit_button( __( 'مسح منتجات الديمو', 'beauty-time-theme' ), 'secondary', 'submit', false );
	echo '</form>';
	echo '</div>';

	echo '</div>';
}

/**
 * Create demo products and categories
 *
 * @return string Report HTML
 */
function beauty_demo_create_products() {
	$ids = array(
		'product_ids'   => array(),
		'category_ids'  => array(),
		'attachment_ids' => array(),
	);

	$created_cats = 0;
	$created_prods = 0;
	$created_att = 0;
	$errors = array();

	$mock = get_template_directory() . '/' . BEAUTY_TIME_MOCK;
	$base = array(
		'العناية بالشعر'   => 'services1.png',
		'العناية بالأظافر' => 'services2.png',
		'العناية بالبشرة' => 'services3.png',
		'المكياج'         => 'services4.png',
		'العناية بالأطفال' => 'services5.png',
		'المساج'          => 'services6.png',
	);

	foreach ( $base as $name => $img ) {
		$slug = sanitize_title( $name );
		$exist = get_term_by( 'slug', $slug, 'product_cat' );
		if ( $exist ) {
			$term_id = (int) $exist->term_id;
			if ( ! in_array( $term_id, $ids['category_ids'], true ) ) {
				$ids['category_ids'][] = $term_id;
			}
			continue;
		}
		$r = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
		if ( is_wp_error( $r ) ) {
			$errors[] = $r->get_error_message();
			continue;
		}
		$term_id = (int) $r['term_id'];
		$ids['category_ids'][] = $term_id;
		$created_cats++;
		update_term_meta( $term_id, BEAUTY_DEMO_META, '1' );

		$path = $mock . '/assets/' . $img;
		if ( file_exists( $path ) ) {
			$aid = beauty_demo_upload_asset( $path, $name );
			if ( $aid ) {
				$ids['attachment_ids'][] = $aid;
				$created_att++;
				update_term_meta( $term_id, 'thumbnail_id', $aid );
			}
		}
	}

	$skincare_cat = get_term_by( 'slug', 'العناية-بالبشرة', 'product_cat' );
	$skincare_id = $skincare_cat ? (int) $skincare_cat->term_id : 0;

	$subs = array(
		array( 'name' => 'تنظيف بشرة سطحي', 'price' => 230, 'img' => 'sub-img1.png' ),
		array( 'name' => 'تركيب رموش', 'price' => 230, 'img' => 'sub-img2.png' ),
		array( 'name' => 'مكياج', 'price' => 230, 'img' => 'sub-img3.png' ),
		array( 'name' => 'تنظيف عميق', 'price' => 230, 'img' => 'sub-img4.png' ),
		array( 'name' => 'مكياج عيون', 'price' => 230, 'img' => 'sub-img5.png' ),
		array( 'name' => 'رسم حواجب', 'price' => 230, 'img' => 'sub-img6.png' ),
	);

	foreach ( $subs as $s ) {
		$pid = beauty_demo_create_product( array(
			'name'        => $s['name'],
			'price'       => $s['price'],
			'description' => 'خدمة تجميل من صالون بيوتي تايم.',
			'image'       => $mock . '/assets/' . $s['img'],
			'cat_ids'     => $skincare_id ? array( $skincare_id ) : array(),
		), $ids );
		if ( $pid ) {
			$ids['product_ids'][] = $pid;
			$created_prods++;
		}
	}

	$bundle_slug = sanitize_title( 'عروض وباقات بيوتي' );
	$bundle_cat  = get_term_by( 'slug', $bundle_slug, 'product_cat' );
	$bundle_cat_id = 0;
	if ( ! $bundle_cat ) {
		$r = wp_insert_term( 'عروض وباقات بيوتي', 'product_cat', array( 'slug' => $bundle_slug ) );
		if ( ! is_wp_error( $r ) ) {
			$bundle_cat_id = (int) $r['term_id'];
			$ids['category_ids'][] = $bundle_cat_id;
			$created_cats++;
			update_term_meta( $bundle_cat_id, BEAUTY_DEMO_META, '1' );
		}
	} else {
		$bundle_cat_id = (int) $bundle_cat->term_id;
		if ( ! in_array( $bundle_cat_id, $ids['category_ids'], true ) ) {
			$ids['category_ids'][] = $bundle_cat_id;
		}
	}
	$bundle_products = array(
		array(
			'name'        => 'عرض التألق والأنوثة باشراق بيوتي',
			'price'       => 200,
			'regular'     => 250,
			'description' => 'جلسة لمعان وترطيب، قص احترافي، استشوار مع ويفي. المدة ساعة ونصف.',
			'image'       => $mock . '/assets/book.png',
		),
		array(
			'name'        => 'عرض بيوتي الفاخر',
			'price'       => 180,
			'regular'     => 240,
			'description' => 'جلسة تنظيف عميق مع ترطيب، ماسك إشراقة، مساج خفيف. المدة ساعة.',
			'image'       => $mock . '/assets/book.png',
		),
		array(
			'name'        => 'عرض بيوتي الاقتصادي',
			'price'       => 150,
			'regular'     => 210,
			'description' => 'تنظيف بشرة سريع، عناية بالأظافر، استشوار بسيط. المدة 45 دقيقة.',
			'image'       => $mock . '/assets/book.png',
		),
	);

	foreach ( $bundle_products as $bundle_product ) {
		$pid = beauty_demo_create_product( array(
			'name'        => $bundle_product['name'],
			'price'       => $bundle_product['price'],
			'regular'     => $bundle_product['regular'],
			'description' => $bundle_product['description'],
			'image'       => $bundle_product['image'],
			'cat_ids'     => $bundle_cat_id ? array( $bundle_cat_id ) : array(),
		), $ids );
		if ( $pid ) {
			$ids['product_ids'][] = $pid;
			$created_prods++;
		}
	}

	update_option( BEAUTY_DEMO_OPTION, $ids );

	$report = sprintf(
		/* translators: 1: categories, 2: products, 3: attachments */
		__( 'تم إنشاء %1$s تصنيفاً، %2$s منتجاً، %3$s وسائط.', 'beauty-time-theme' ),
		$created_cats,
		$created_prods,
		$created_att
	);
	if ( ! empty( $errors ) ) {
		$report .= ' ' . __( 'أخطاء:', 'beauty-time-theme' ) . ' ' . implode( '; ', array_map( 'esc_html', $errors ) );
	}
	return $report;
}

/**
 * Create a single WC product
 *
 * @param array $args Name, price, description, image path, cat_ids.
 * @param array $ids  Running IDs arrays (attachment_ids pushed here).
 * @return int Product ID or 0
 */
function beauty_demo_create_product( $args, &$ids ) {
	$name = isset( $args['name'] ) ? $args['name'] : '';
	$price = isset( $args['price'] ) ? (float) $args['price'] : 0;
	$regular = isset( $args['regular'] ) ? (float) $args['regular'] : $price;
	$description = isset( $args['description'] ) ? $args['description'] : '';
	$image = isset( $args['image'] ) ? $args['image'] : '';
	$cat_ids = isset( $args['cat_ids'] ) ? (array) $args['cat_ids'] : array();

	if ( ! $name ) {
		return 0;
	}

	$slug = sanitize_title( $name );
	$existing = get_page_by_path( $slug, OBJECT, 'product' );
	if ( $existing ) {
		$product = wc_get_product( $existing->ID );
		if ( $product && $product->get_meta( BEAUTY_DEMO_META ) === '1' ) {
			return (int) $existing->ID;
		}
	}

	$obj = new WC_Product_Simple();
	$obj->set_name( $name );
	$obj->set_status( 'publish' );
	$obj->set_catalog_visibility( 'visible' );
	$obj->set_price( $price );
	$obj->set_regular_price( $regular );
	if ( $regular > $price ) {
		$obj->set_sale_price( $price );
	}
	$obj->set_virtual( true );
	$obj->set_sold_individually( false );
	$obj->set_stock_status( 'instock' );
	$obj->set_description( $description );
	$obj->set_short_description( $description );
	$obj->update_meta_data( BEAUTY_DEMO_META, '1' );
	$obj->save();

	$pid = $obj->get_id();
	if ( $cat_ids ) {
		wp_set_object_terms( $pid, array_map( 'intval', $cat_ids ), 'product_cat' );
	}

	if ( $image && file_exists( $image ) ) {
		$aid = beauty_demo_upload_asset( $image, $name );
		if ( $aid ) {
			$ids['attachment_ids'][] = $aid;
			$obj->set_image_id( $aid );
			$obj->save();
		}
	}

	return $pid;
}

/**
 * Upload theme asset to media library
 *
 * @param string $file_path Full path to file.
 * @param string $title     Optional title.
 * @return int Attachment ID or 0
 */
function beauty_demo_upload_asset( $file_path, $title = '' ) {
	$name = basename( $file_path );
	$tmp = wp_tempnam( $name );
	if ( ! $tmp ) {
		return 0;
	}
	$content = file_get_contents( $file_path );
	if ( false === $content ) {
		@unlink( $tmp );
		return 0;
	}
	file_put_contents( $tmp, $content );

	$file = array(
		'name'     => $name,
		'tmp_name' => $tmp,
		'size'     => strlen( $content ),
		'type'     => wp_check_filetype( $name, null )['type'],
		'error'    => 0,
	);

	$id = media_handle_sideload( $file, 0, $title );
	@unlink( $tmp );
	if ( is_wp_error( $id ) ) {
		return 0;
	}
	return (int) $id;
}

/**
 * Delete demo products, categories, attachments
 *
 * @return string Report HTML
 */
function beauty_demo_delete_products() {
	$ids = get_option( BEAUTY_DEMO_OPTION, array() );
	if ( ! is_array( $ids ) ) {
		$ids = array();
	}
	$product_ids   = isset( $ids['product_ids'] ) ? (array) $ids['product_ids'] : array();
	$category_ids  = isset( $ids['category_ids'] ) ? (array) $ids['category_ids'] : array();
	$attachment_ids = isset( $ids['attachment_ids'] ) ? (array) $ids['attachment_ids'] : array();

	$deleted_prods = 0;
	$deleted_cats = 0;
	$deleted_att = 0;

	foreach ( $product_ids as $pid ) {
		$p = wc_get_product( $pid );
		if ( ! $p ) {
			continue;
		}
		if ( $p->get_meta( BEAUTY_DEMO_META ) !== '1' ) {
			continue;
		}
		$p->delete( true );
		$deleted_prods++;
	}

	foreach ( $category_ids as $tid ) {
		$t = get_term( $tid, 'product_cat' );
		if ( ! $t || is_wp_error( $t ) ) {
			continue;
		}
		if ( get_term_meta( $tid, BEAUTY_DEMO_META, true ) !== '1' ) {
			continue;
		}
		wp_delete_term( $tid, 'product_cat' );
		$deleted_cats++;
	}

	foreach ( $attachment_ids as $aid ) {
		if ( wp_attachment_is_image( $aid ) && wp_delete_attachment( $aid, true ) ) {
			$deleted_att++;
		}
	}

	update_option( BEAUTY_DEMO_OPTION, array( 'product_ids' => array(), 'category_ids' => array(), 'attachment_ids' => array() ) );

	return sprintf(
		/* translators: 1: products, 2: categories, 3: attachments */
		__( 'تم حذف %1$s منتجاً، %2$s تصنيفاً، %3$s وسائط.', 'beauty-time-theme' ),
		$deleted_prods,
		$deleted_cats,
		$deleted_att
	);
}
