<?php
/**
 * Sweet House Theme — admin "المحتوى" (Content): الصفحات + منتجات ديمو.
 *
 * @package Sweet_House_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pages required by header and footer (slug => [title, description]).
 * Includes: المصادقة (تسجيل الدخول، إنشاء حساب)، المنتجات، السلة، الدفع، إلخ.
 */
function sweet_house_pages_manifest() {
	return array(
		/* الصفحة الأم للمصادقة (حسابي، إنشاء حساب تحت sweet-house) */
		'sweet-house'     => array( 'سويت هاوس', 'الصفحة الأم لحسابي وإنشاء حساب' ),
		/* المصادقة */
		'my-account'      => array( 'حسابي', 'تسجيل الدخول ولوحة التحكم (الطلبات، العناوين)' ),
		'sign-up'         => array( 'إنشاء حساب', 'إنشاء حساب جديد' ),
		/* المتجر */
		'shop'            => array( 'المنتجات', 'صفحة عرض المنتجات' ),
		'cart'            => array( 'السلة', 'سلة المشتريات' ),
		'payment'         => array( 'الدفع', 'صفحة إتمام الطلب' ),
		/* المحتوى */
		'offers'          => array( 'العروض', 'صفحة العروض' ),
		'wishlist'        => array( 'المفضلة', 'قائمة الأمنيات' ),
		'recepies'        => array( 'وصفاتنا', 'صفحة الوصفات' ),
		'about-us'        => array( 'من نحن', 'صفحة من نحن' ),
		'contact-us'      => array( 'تواصل معنا', 'صفحة تواصل معنا' ),
		'privacy-policy'  => array( 'سياسة الخصوصية', 'سياسة الخصوصية' ),
		'refund-policy'   => array( 'سياسة الاسترجاع', 'سياسة الاسترجاع' ),
		'shipping-policy' => array( 'سياسة الشحن', 'سياسة الشحن' ),
	);
}

/**
 * Register admin menu: المحتوى → الصفحات، منتجات ديمو.
 */
function sweet_house_register_content_admin() {
	add_menu_page(
		'المحتوى',
		'المحتوى',
		'manage_options',
		'sweet-house-content',
		'sweet_house_render_content_landing',
		'dashicons-admin-page',
		30
	);
	add_submenu_page(
		'sweet-house-content',
		'الصفحات',
		'الصفحات',
		'manage_options',
		'sweet-house-pages',
		'sweet_house_render_pages_admin'
	);
	add_submenu_page(
		'sweet-house-content',
		'منتجات ديمو',
		'منتجات ديمو',
		'manage_options',
		'sweet-house-demo-products',
		'sweet_house_render_demo_products_admin'
	);
}
add_action( 'admin_menu', 'sweet_house_register_content_admin' );

/**
 * Landing page for المحتوى (first submenu item redirects to الصفحات by default).
 */
function sweet_house_render_content_landing() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	echo '<div class="wrap"><h1>المحتوى</h1><p>اختر من القائمة الجانبية: <strong>الصفحات</strong> لإنشاء/تحديث صفحات الثيم، أو <strong>منتجات ديمو</strong> لإضافة أو حذف المنتجات التجريبية.</p></div>';
}

/**
 * الصفحات admin: button "تحديث / او انشاء الصفحات" + table of pages.
 */
function sweet_house_render_pages_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$pages = sweet_house_pages_manifest();
	?>
	<div class="wrap">
		<h1>الصفحات</h1>
		<p>إنشاء أو تحديث الصفحات التي يستخدمها الثيم. الصفحات المطلوبة:</p>
		<ul style="margin: 0.5em 0 1em 1.5em;">
			<li><strong>المصادقة:</strong> سويت هاوس (صفحة أم)، حسابي، إنشاء حساب — الروابط: /sweet-house/my-account/ و /sweet-house/sign-up/</li>
			<li><strong>المتجر:</strong> المنتجات، السلة، الدفع</li>
			<li><strong>المحتوى:</strong> العروض، المفضلة، وصفاتنا، من نحن، تواصل معنا، السياسات</li>
		</ul>

		<?php if ( isset( $_GET['sweet_house_synced'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم تحديث الصفحات بنجاح.</p></div>
		<?php endif; ?>

		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin: 1em 0;">
			<?php wp_nonce_field( 'sweet_house_sync_pages', 'sweet_house_sync_pages_nonce' ); ?>
			<input type="hidden" name="action" value="sweet_house_sync_pages">
			<?php submit_button( 'تحديث / او انشاء الصفحات', 'primary' ); ?>
		</form>

		<table class="widefat fixed striped">
			<thead>
				<tr>
					<th>العنوان</th>
					<th>الوصف</th>
					<th>المسار</th>
					<th>الحالة</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $pages as $slug => $info ) : ?>
					<?php
					$template_only = is_array( $info ) && ! empty( $info['template_only'] );
					$title         = is_array( $info ) ? $info[0] : $info;
					$desc          = is_array( $info ) && isset( $info[1] ) ? $info[1] : '';
					$page          = $template_only ? null : get_page_by_path( $slug );
					?>
					<tr>
						<td><?php echo esc_html( $title ); ?></td>
						<td><?php echo esc_html( $desc ); ?></td>
						<td><?php echo esc_html( $template_only ? '/recipe/{slug}' : '/' . $slug ); ?></td>
						<td>
							<?php if ( $template_only ) : ?>
								<span title="<?php esc_attr_e( 'لا تُنشأ كصفحة — يعمل تلقائياً عند فتح وصفة', 'sweet-house-theme' ); ?>">قالب تلقائي</span>
							<?php elseif ( $page ) : ?>
								<a href="<?php echo esc_url( get_edit_post_link( $page->ID ) ); ?>">موجودة</a>
							<?php else : ?>
								غير موجودة
							<?php endif; ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Handler: create/update pages from manifest.
 */
function sweet_house_sync_pages_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'sweet_house_sync_pages', 'sweet_house_sync_pages_nonce' );

	$pages = sweet_house_pages_manifest();
	$templates = array(
		'about-us'        => 'page-about-us.php',
		'contact-us'      => 'page-contact-us.php',
		'sign-up'         => 'page-sign-up.php',
		'wishlist'        => 'page-wishlist.php',
		'privacy-policy'  => 'page-policy.php',
		'refund-policy'   => 'page-policy.php',
		'shipping-policy' => 'page-policy.php',
	);
	$child_paths = array(
		'sign-up'     => 'sweet-house/sign-up',
		'my-account'  => 'sweet-house/my-account',
	);
	$parent_slugs = array(
		'sign-up'     => 'sweet-house',
		'my-account'  => 'sweet-house',
	);
	foreach ( $pages as $slug => $info ) {
		if ( is_array( $info ) && ! empty( $info['template_only'] ) ) {
			continue;
		}
		$title = is_array( $info ) ? $info[0] : $info;
		$page  = get_page_by_path( $slug );
		if ( ! $page && isset( $child_paths[ $slug ] ) ) {
			$page = get_page_by_path( $child_paths[ $slug ] );
		}
		$args = array(
			'post_title'   => $title,
			'post_name'    => $slug,
			'post_status'  => 'publish',
			'post_type'    => 'page',
		);
		if ( isset( $templates[ $slug ] ) ) {
			$args['page_template'] = $templates[ $slug ];
		}
		if ( $page ) {
			$needs_update = $page->post_title !== $title || 'publish' !== $page->post_status;
			if ( isset( $parent_slugs[ $slug ] ) ) {
				$parent = get_page_by_path( $parent_slugs[ $slug ] );
				if ( $parent && (int) $page->post_parent !== (int) $parent->ID ) {
					$needs_update   = true;
					$args['post_parent'] = $parent->ID;
				}
			}
			if ( $needs_update ) {
				$args['ID'] = $page->ID;
				unset( $args['post_name'] );
				wp_update_post( $args );
			}
			if ( isset( $templates[ $slug ] ) ) {
				update_post_meta( $page->ID, '_wp_page_template', $templates[ $slug ] );
			}
		} else {
			unset( $args['page_template'] );
			if ( isset( $parent_slugs[ $slug ] ) ) {
				$parent = get_page_by_path( $parent_slugs[ $slug ] );
				if ( $parent ) {
					$args['post_parent'] = $parent->ID;
				}
			}
			$new_id = wp_insert_post( $args );
			if ( $new_id && isset( $templates[ $slug ] ) ) {
				update_post_meta( $new_id, '_wp_page_template', $templates[ $slug ] );
			}
		}
	}

	// ربط صفحات WooCommerce: المنتجات، السلة، الدفع، حسابي
	if ( function_exists( 'wc_get_page_id' ) ) {
		$shop_page = get_page_by_path( 'shop' );
		if ( $shop_page ) {
			update_option( 'woocommerce_shop_page_id', $shop_page->ID );
		}
		$cart_page = get_page_by_path( 'cart' );
		if ( $cart_page ) {
			update_option( 'woocommerce_cart_page_id', $cart_page->ID );
		}
		$checkout_page = get_page_by_path( 'payment' );
		if ( $checkout_page ) {
			update_option( 'woocommerce_checkout_page_id', $checkout_page->ID );
			// Use classic shortcode for Sweet House design (replaces block).
			if ( has_block( 'woocommerce/checkout', $checkout_page ) ) {
				wp_update_post(
					array(
						'ID'           => $checkout_page->ID,
						'post_content' => '<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->',
					)
				);
			}
		}
		$account_page = get_page_by_path( 'sweet-house/my-account' ) ?: get_page_by_path( 'my-account' );
		if ( $account_page ) {
			update_option( 'woocommerce_myaccount_page_id', $account_page->ID );
		}
	}

	// صفحة إنشاء حساب: إضافة الشورتكود
	$signup_page = get_page_by_path( 'sign-up' ) ?: get_page_by_path( 'sweet-house/sign-up' );
	if ( $signup_page ) {
		$shortcode_content = '<!-- wp:shortcode -->[sweet_house_register]<!-- /wp:shortcode -->';
		if ( $signup_page->post_content !== $shortcode_content ) {
			wp_update_post(
				array(
					'ID'           => $signup_page->ID,
					'post_content' => $shortcode_content,
				)
			);
		}
	}

	wp_safe_redirect( add_query_arg( 'sweet_house_synced', '1', admin_url( 'admin.php?page=sweet-house-pages' ) ) );
	exit;
}
add_action( 'admin_post_sweet_house_sync_pages', 'sweet_house_sync_pages_handler' );

/** Meta key for demo products and recipes. */
define( 'SWEET_HOUSE_DEMO_META', '_sweet_house_demo' );

/**
 * Demo categories for Sweet House — names and images from design (y-c-categories-sec.html).
 * Assets: cat1.png, cat2.png, cat3.png, cat4.png, cat6.png, cat7.png.
 */
function sweet_house_demo_categories_config() {
	return array(
		'cake'               => array( 'name' => 'كيك وتورت', 'image' => 'assets/cat1.png' ),
		'mokarmashat'        => array( 'name' => 'المقرمشات', 'image' => 'assets/cat2.png' ),
		'makhbuzat'          => array( 'name' => 'مخبوزات', 'image' => 'assets/cat3.png' ),
		'diet'               => array( 'name' => 'أصناف الدايت', 'image' => 'assets/cat4.png' ),
		'halawiyat-sharqiya' => array( 'name' => 'حلويات شرقية', 'image' => 'assets/cat6.png' ),
		'halawiyat-gharbiya' => array( 'name' => 'حلويات غربية', 'image' => 'assets/cat7.png' ),
	);
}

/**
 * Demo products manifest: all products with categories (using design category names/images); some with sale price.
 */
function sweet_house_demo_products_manifest() {
	$categories  = sweet_house_demo_categories_config();
	$products    = array();
	$base_prices = array( 25, 35, 45, 55, 65, 75 );
	$image       = 'assets/product.png';

	$product_names = array(
		'cake'               => array( 'كيك شوكولاتة', 'تورته أرويو - ميني', 'كيك فانيلا', 'تشيز كيك', 'كيك جوز', 'كيك فراولة' ),
		'mokarmashat'        => array( 'مقرمشات بالجبن', 'مقرمشات بالزعتر', 'بسكويت شوكولاتة', 'مقرمشات بالسمسم', 'كوكيز', 'ويفر' ),
		'makhbuzat'          => array( 'خبز عربي طازج', 'صمون حجازي', 'فطائر بالجبن', 'مناقيش زعتر', 'خبز توست', 'كرواسون' ),
		'diet'               => array( 'كيك دايت', 'بسكويت دايت', 'معجنات دايت', 'حلا دايت', 'شوكولاتة دايت', 'مربى دايت' ),
		'halawiyat-sharqiya' => array( 'كنافة نابلسية', 'بقلاوة بالعسل', 'أم علي', 'معمول التمر', 'لقيمات', 'حلا جوز الهند' ),
		'halawiyat-gharbiya' => array( 'تشيز كيك', 'براوني', 'كوكيز', 'موس الشوكولاتة', 'تيراميسو', 'بان كيك' ),
	);

	foreach ( $categories as $slug => $config ) {
		$name = is_array( $config ) ? $config['name'] : $config;
		$names = isset( $product_names[ $slug ] ) ? $product_names[ $slug ] : array( $name . ' 1', $name . ' 2', $name . ' 3', $name . ' 4', $name . ' 5', $name . ' 6' );
		for ( $i = 0; $i < 6; $i++ ) {
			$regular     = $base_prices[ $i % 6 ];
			$sale        = ( 0 === $i ) ? (int) round( $regular * 0.8 ) : null;
			$product_name = isset( $names[ $i ] ) ? $names[ $i ] : ( $name . ' ' . ( $i + 1 ) );
			$products[]  = array(
				'slug'           => $slug . '-' . ( $i + 1 ),
				'name'           => $product_name,
				'regular_price'  => $regular,
				'sale_price'     => $sale,
				'category_slug'  => $slug,
				'category_name'  => $name,
				'category_image' => is_array( $config ) && ! empty( $config['image'] ) ? $config['image'] : '',
				'image'          => $image,
			);
		}
	}

	// Extra products without category (general)
	for ( $i = 1; $i <= 4; $i++ ) {
		$regular = $base_prices[ ( $i - 1 ) % 6 ];
		$sale = ( 1 === $i ) ? (int) round( $regular * 0.75 ) : null;
		$products[] = array(
			'slug'           => 'general-' . $i,
			'name'           => 'منتج عام ' . $i,
			'regular_price'  => $regular,
			'sale_price'     => $sale,
			'category_slug'  => '',
			'category_name'  => '',
			'image'          => $image,
		);
	}

	return $products;
}

/**
 * Demo recipes manifest — from design (y-c-recipe.html, y-c-single-recipe.html).
 * وصفات مميزة، حلويات، مقبلات، أطباق رئيسية.
 */
function sweet_house_demo_recipes_manifest() {
	return array(
		array(
			'name'         => 'فطيرة جبنة',
			'slug'         => 'cheese-pie',
			'image'        => 'assets/recipe1.png',
			'categories'   => array( 'وصفات مميزة', 'حلويات', 'مقبلات', 'أطباق رئيسية' ),
			'prep_time'    => '15',
			'cook_time'    => '30',
			'serves'       => '6',
			'ingredients'  => "جبنة بيضاء - 250 غم\nعجينة فطيرة - رقاقة واحدة\nبيض - حبتان\nحليب - نصف كوب\nملح وفلفل - حسب الرغبة",
			'instructions' => "انثري العجينة في صينية الفرن\nاخلطي الجبنة مع البيض والحليب\nاسكبي الخليط فوق العجينة\nاخبزي في فرن مسخن مسبقاً على 180 درجة لمدة 25-30 دقيقة\nقدمي دافئة",
		),
		array(
			'name'         => 'بودينغ الشوكولاتة',
			'slug'         => 'chocolate-pudding',
			'image'        => 'assets/recipe2.png',
			'categories'   => array( 'وصفات مميزة', 'حلويات', 'مقبلات', 'أطباق رئيسية' ),
			'prep_time'    => '10',
			'cook_time'    => '20',
			'serves'       => '12',
			'ingredients'  => "بسكويت الحليب فانيلا - علبة واحدة\nلتر حليب\nنشا - 4 ملاعق كبيرة\nكاكاو - 3 ملاعق كبيرة\nفانيلا - ملعقة صغيرة\nقشطة - 100 غم\nسكر - فنجان قهوة\nبيض - حبتان",
			'instructions' => "في وعاء كبير، اخلطي الحليب مع النشا والكاكاو حتى يذوب تماماً\nأضيفي السكر والفانيلا وقلبي جيداً\nفي وعاء منفصل، اخفقي البيض ثم أضيفيه إلى الخليط\nضعي الخليط على نار متوسطة وقلبي باستمرار حتى يثخن\nأضيفي القشطة وقلبي حتى تمتزج\nأزيلي من النار واتركيه يبرد قليلاً\nفي قاع الأطباق، ضعي طبقة من البسكويت\nاسكبي خليط البودينغ فوق البسكويت\nكرري الطبقات حسب الرغبة\nضعي في الثلاجة لمدة ساعتين على الأقل قبل التقديم",
		),
		array(
			'name'         => 'بريدة بودينغ',
			'slug'         => 'brayda-pudding',
			'image'        => 'assets/recipe3.png',
			'categories'   => array( 'وصفات مميزة', 'حلويات', 'مقبلات', 'أطباق رئيسية' ),
			'prep_time'    => '10',
			'cook_time'    => '15',
			'serves'       => '8',
			'ingredients'  => "بسكويت بريدة - علبة\nحليب مكثف محلى - علبة\nقشطة - 200 غم\nكاكاو - ملعقتان كبيرتان\nفانيلا - ملعقة صغيرة",
			'instructions' => "في طبق، رصي طبقة من البسكويت\nاخلطي الحليب المكثف مع القشطة والكاكاو والفانيلا\nاسكبي جزءاً من الخليط فوق البسكويت\nكرري الطبقات\nضعي في الثلاجة لمدة 4 ساعات على الأقل\nقدمي باردة",
		),
	);
}

/**
 * Import image from theme sweet-house assets into media library.
 */
function sweet_house_import_image_from_theme( $relative_path ) {
	$source_path = sweet_house_asset_path( $relative_path );
	if ( ! file_exists( $source_path ) ) {
		return 0;
	}
	$upload_dir = wp_upload_dir();
	$filename  = wp_unique_filename( $upload_dir['path'], basename( $source_path ) );
	$destination = trailingslashit( $upload_dir['path'] ) . $filename;
	copy( $source_path, $destination );

	$filetype   = wp_check_filetype( $filename );
	$attachment = array(
		'post_mime_type' => $filetype['type'],
		'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'  => '',
		'post_status'   => 'inherit',
	);
	$attachment_id = wp_insert_attachment( $attachment, $destination );
	if ( ! $attachment_id ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	$attach_data = wp_generate_attachment_metadata( $attachment_id, $destination );
	wp_update_attachment_metadata( $attachment_id, $attach_data );
	return $attachment_id;
}

/**
 * Get current demo recipes (by option + meta) for table display.
 */
function sweet_house_get_current_demo_recipes() {
	$saved = get_option( 'sweet_house_demo_recipe_ids', array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}
	$by_meta = new WP_Query(
		array(
			'post_type'      => 'recipe',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => SWEET_HOUSE_DEMO_META,
					'value' => '1',
				),
			),
		)
	);
	$ids = array_unique( array_merge( $saved, $by_meta->posts ) );
	$ids = array_filter( array_map( 'intval', $ids ) );
	if ( empty( $ids ) ) {
		return array();
	}
	$recipes = array();
	foreach ( $ids as $id ) {
		if ( get_post_type( $id ) !== 'recipe' ) {
			continue;
		}
		$terms = get_the_terms( $id, 'recipe_category' );
		$cat_names = array();
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$cat_names[] = $t->name;
			}
		}
		$recipes[] = array(
			'id'         => $id,
			'title'      => get_the_title( $id ),
			'categories' => implode( '، ', $cat_names ),
			'edit_url'   => get_edit_post_link( $id, 'raw' ),
		);
	}
	return $recipes;
}

/**
 * Get current demo products (by option + meta) for table display.
 */
function sweet_house_get_current_demo_products() {
	$saved = get_option( 'sweet_house_demo_product_ids', array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}
	$by_meta = new WP_Query(
		array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => SWEET_HOUSE_DEMO_META,
					'value' => '1',
				),
			),
		)
	);
	$ids = array_unique( array_merge( $saved, $by_meta->posts ) );
	$ids = array_filter( array_map( 'intval', $ids ) );
	if ( empty( $ids ) ) {
		return array();
	}
	$products = array();
	foreach ( $ids as $id ) {
		if ( get_post_type( $id ) !== 'product' ) {
			continue;
		}
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( $id ) : null;
		if ( ! $product ) {
			continue;
		}
		$terms = get_the_terms( $id, 'product_cat' );
		$cat_names = array();
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				$cat_names[] = $t->name;
			}
		}
		$products[] = array(
			'id'         => $id,
			'title'      => $product->get_name(),
			'price'      => $product->get_price(),
			'categories' => implode( '، ', $cat_names ),
			'edit_url'   => get_edit_post_link( $id, 'raw' ),
		);
	}
	return $products;
}

/**
 * منتجات ديمو admin: two buttons (add all / delete all) + table of demo products.
 */
function sweet_house_render_demo_products_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$demo_products = sweet_house_get_current_demo_products();
	?>
	<div class="wrap">
		<h1>منتجات ديمو</h1>
		<p>إضافة جميع المنتجات والتصنيفات والوصفات المستخدمة في ملفات التصميم، مع تخفيض على جزء من المنتجات. أو حذف كل محتوى الديمو.</p>

		<?php if ( isset( $_GET['sweet_house_demo_done'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم إنشاء المنتجات والوصفات التجريبية بنجاح.</p></div>
		<?php endif; ?>
		<?php if ( isset( $_GET['sweet_house_demo_deleted'] ) ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>تم حذف محتوى الديمو.
				<?php
				if ( isset( $_GET['deleted_products'] ) && (int) $_GET['deleted_products'] > 0 ) {
					echo ' تم حذف ' . (int) $_GET['deleted_products'] . ' منتج.';
				}
				if ( isset( $_GET['deleted_recipes'] ) && (int) $_GET['deleted_recipes'] > 0 ) {
					echo ' تم حذف ' . (int) $_GET['deleted_recipes'] . ' وصفة.';
				}
				?>
				</p>
			</div>
		<?php endif; ?>

		<?php $demo_recipes = sweet_house_get_current_demo_recipes(); ?>
		<p style="margin: 1em 0;">
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block;">
				<?php wp_nonce_field( 'sweet_house_create_demo_products', 'sweet_house_create_demo_products_nonce' ); ?>
				<input type="hidden" name="action" value="sweet_house_create_demo_products">
				<?php submit_button( 'إضافة كل المنتجات والتصنيفات والوصفات', 'primary' ); ?>
			</form>
			<?php if ( ! empty( $demo_products ) || ! empty( $demo_recipes ) ) : ?>
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block; margin-right: 12px;">
					<?php wp_nonce_field( 'sweet_house_delete_demo_products', 'sweet_house_delete_demo_products_nonce' ); ?>
					<input type="hidden" name="action" value="sweet_house_delete_demo_products">
					<button type="submit" class="button" onclick="return confirm('هل تريد حذف كل المنتجات والوصفات التجريبية؟');">حذف كل محتوى الديمو</button>
				</form>
			<?php endif; ?>
		</p>

		<?php if ( ! empty( $demo_products ) ) : ?>
			<h2 style="margin-top: 24px;">جدول منتجات الديمو</h2>
			<table class="widefat fixed striped" style="margin-top: 12px; max-width: 900px;">
				<thead>
					<tr>
						<th style="width: 50px;">#</th>
						<th>المنتج</th>
						<th style="width: 100px;">السعر</th>
						<th>التصنيفات</th>
						<th style="width: 100px;">تعديل</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $demo_products as $i => $row ) : ?>
						<tr>
							<td><?php echo (int) ( $i + 1 ); ?></td>
							<td><?php echo esc_html( $row['title'] ); ?></td>
							<td><?php echo esc_html( $row['price'] ); ?> ر.س</td>
							<td><?php echo esc_html( $row['categories'] ?: '—' ); ?></td>
							<td>
								<?php if ( ! empty( $row['edit_url'] ) ) : ?>
									<a href="<?php echo esc_url( $row['edit_url'] ); ?>" class="button button-small">تعديل</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php else : ?>
			<p>لا توجد منتجات ديمو حالياً.</p>
		<?php endif; ?>

		<?php if ( ! empty( $demo_recipes ) ) : ?>
			<h2 style="margin-top: 24px;">جدول وصفات الديمو</h2>
			<table class="widefat fixed striped" style="margin-top: 12px; max-width: 900px;">
				<thead>
					<tr>
						<th style="width: 50px;">#</th>
						<th>الوصفة</th>
						<th>التصنيفات</th>
						<th style="width: 100px;">تعديل</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $demo_recipes as $i => $row ) : ?>
						<tr>
							<td><?php echo (int) ( $i + 1 ); ?></td>
							<td><?php echo esc_html( $row['title'] ); ?></td>
							<td><?php echo esc_html( $row['categories'] ?: '—' ); ?></td>
							<td>
								<?php if ( ! empty( $row['edit_url'] ) ) : ?>
									<a href="<?php echo esc_url( $row['edit_url'] ); ?>" class="button button-small">تعديل</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php elseif ( empty( $demo_products ) ) : ?>
			<p>استخدم الزر "إضافة كل المنتجات والتصنيفات والوصفات" أعلاه لإنشاء المحتوى التجريبي.</p>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Delete all demo products handler.
 */
function sweet_house_delete_demo_products_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'sweet_house_delete_demo_products', 'sweet_house_delete_demo_products_nonce' );

	$ids = get_option( 'sweet_house_demo_product_ids', array() );
	if ( ! is_array( $ids ) ) {
		$ids = array();
	}
	$deleted = 0;
	foreach ( $ids as $id ) {
		$id = (int) $id;
		if ( $id && get_post_type( $id ) === 'product' ) {
			wp_delete_post( $id, true );
			$deleted++;
		}
	}
	$by_meta = new WP_Query(
		array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => SWEET_HOUSE_DEMO_META,
					'value' => '1',
				),
			),
		)
	);
	foreach ( $by_meta->posts as $id ) {
		wp_delete_post( $id, true );
		$deleted++;
	}
	delete_option( 'sweet_house_demo_product_ids' );

	// حذف وصفات الديمو
	$recipe_ids = get_option( 'sweet_house_demo_recipe_ids', array() );
	if ( ! is_array( $recipe_ids ) ) {
		$recipe_ids = array();
	}
	$deleted_recipes = 0;
	foreach ( $recipe_ids as $id ) {
		$id = (int) $id;
		if ( $id && get_post_type( $id ) === 'recipe' ) {
			wp_delete_post( $id, true );
			$deleted_recipes++;
		}
	}
	$recipe_by_meta = new WP_Query(
		array(
			'post_type'      => 'recipe',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => SWEET_HOUSE_DEMO_META,
					'value' => '1',
				),
			),
		)
	);
	foreach ( $recipe_by_meta->posts as $id ) {
		wp_delete_post( $id, true );
		$deleted_recipes++;
	}
	delete_option( 'sweet_house_demo_recipe_ids' );

	wp_safe_redirect(
		add_query_arg(
			array(
				'sweet_house_demo_deleted' => '1',
				'deleted_products'        => $deleted,
				'deleted_recipes'         => $deleted_recipes,
			),
			admin_url( 'admin.php?page=sweet-house-demo-products' )
		)
	);
	exit;
}
add_action( 'admin_post_sweet_house_delete_demo_products', 'sweet_house_delete_demo_products_handler' );

/**
 * Create demo products handler.
 */
function sweet_house_create_demo_products_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'sweet_house_create_demo_products', 'sweet_house_create_demo_products_nonce' );

	$products   = sweet_house_demo_products_manifest();
	$created_ids = array();

	foreach ( $products as $product_data ) {
		$existing = get_page_by_path( $product_data['slug'], OBJECT, 'product' );
		if ( $existing ) {
			$product_id = $existing->ID;
			wp_update_post(
				array(
					'ID'          => $product_id,
					'post_title'  => $product_data['name'],
					'post_status' => 'publish',
				)
			);
		} else {
			$product_id = wp_insert_post(
				array(
					'post_title'   => $product_data['name'],
					'post_name'    => $product_data['slug'],
					'post_status'  => 'publish',
					'post_type'    => 'product',
				)
			);
		}

		if ( $product_id && ! is_wp_error( $product_id ) ) {
			update_post_meta( $product_id, SWEET_HOUSE_DEMO_META, 1 );

			$regular = isset( $product_data['regular_price'] ) ? $product_data['regular_price'] : 0;
			$sale    = isset( $product_data['sale_price'] ) ? $product_data['sale_price'] : null;
			update_post_meta( $product_id, '_regular_price', (string) $regular );
			if ( $sale !== null && $sale < $regular ) {
				update_post_meta( $product_id, '_sale_price', (string) $sale );
				update_post_meta( $product_id, '_price', (string) $sale );
			} else {
				update_post_meta( $product_id, '_price', (string) $regular );
			}

			if ( ! empty( $product_data['category_slug'] ) && ! empty( $product_data['category_name'] ) ) {
				$term = get_term_by( 'slug', $product_data['category_slug'], 'product_cat' );
				if ( ! $term || is_wp_error( $term ) ) {
					$ins = wp_insert_term( $product_data['category_name'], 'product_cat', array( 'slug' => $product_data['category_slug'] ) );
					if ( ! is_wp_error( $ins ) ) {
						$term = get_term( $ins['term_id'], 'product_cat' );
					}
				}
				if ( $term && ! is_wp_error( $term ) ) {
					wp_set_object_terms( $product_id, array( (int) $term->term_id ), 'product_cat' );
					// Set category thumbnail from design image if not already set.
					if ( ! empty( $product_data['category_image'] ) ) {
						$cat_thumb = get_term_meta( $term->term_id, 'thumbnail_id', true );
						if ( ! $cat_thumb ) {
							$attachment_id = sweet_house_import_image_from_theme( $product_data['category_image'] );
							if ( $attachment_id ) {
								update_term_meta( $term->term_id, 'thumbnail_id', $attachment_id );
							}
						}
					}
				}
			}

			if ( ! has_post_thumbnail( $product_id ) ) {
				$attachment_id = sweet_house_import_image_from_theme( $product_data['image'] );
				if ( $attachment_id ) {
					set_post_thumbnail( $product_id, $attachment_id );
				}
			}
			$created_ids[] = $product_id;
		}
	}

	update_option( 'sweet_house_demo_product_ids', array_unique( array_filter( $created_ids ) ) );

	// إنشاء وصفات الديمو من ملفات التصميم
	$recipes_data = sweet_house_demo_recipes_manifest();
	$recipe_ids   = array();
	foreach ( $recipes_data as $r ) {
		$existing = get_page_by_path( $r['slug'], OBJECT, 'recipe' );
		if ( $existing ) {
			$recipe_id = $existing->ID;
			wp_update_post(
				array(
					'ID'          => $recipe_id,
					'post_title'  => $r['name'],
					'post_status' => 'publish',
				)
			);
		} else {
			$recipe_id = wp_insert_post(
				array(
					'post_title'   => $r['name'],
					'post_name'    => $r['slug'],
					'post_status'  => 'publish',
					'post_type'    => 'recipe',
				)
			);
		}
		if ( $recipe_id && ! is_wp_error( $recipe_id ) ) {
			update_post_meta( $recipe_id, SWEET_HOUSE_DEMO_META, 1 );
			update_post_meta( $recipe_id, '_recipe_prep_time', $r['prep_time'] );
			update_post_meta( $recipe_id, '_recipe_cook_time', $r['cook_time'] );
			update_post_meta( $recipe_id, '_recipe_serves', $r['serves'] );
			update_post_meta( $recipe_id, '_recipe_ingredients', $r['ingredients'] );
			update_post_meta( $recipe_id, '_recipe_instructions', $r['instructions'] );
			if ( ! empty( $r['categories'] ) ) {
				$term_ids = array();
				foreach ( $r['categories'] as $cat_name ) {
					$term = get_term_by( 'name', $cat_name, 'recipe_category' );
					if ( ! $term || is_wp_error( $term ) ) {
						$ins = wp_insert_term( $cat_name, 'recipe_category' );
						if ( ! is_wp_error( $ins ) ) {
							$term_ids[] = $ins['term_id'];
						}
					} else {
						$term_ids[] = $term->term_id;
					}
				}
				if ( ! empty( $term_ids ) ) {
					wp_set_object_terms( $recipe_id, $term_ids, 'recipe_category' );
				}
			}
			if ( ! has_post_thumbnail( $recipe_id ) && ! empty( $r['image'] ) ) {
				$attachment_id = sweet_house_import_image_from_theme( $r['image'] );
				if ( $attachment_id ) {
					set_post_thumbnail( $recipe_id, $attachment_id );
				}
			}
			$recipe_ids[] = $recipe_id;
		}
	}
	update_option( 'sweet_house_demo_recipe_ids', array_unique( array_filter( $recipe_ids ) ) );

	wp_safe_redirect( add_query_arg( 'sweet_house_demo_done', '1', admin_url( 'admin.php?page=sweet-house-demo-products' ) ) );
	exit;
}
add_action( 'admin_post_sweet_house_create_demo_products', 'sweet_house_create_demo_products_handler' );
