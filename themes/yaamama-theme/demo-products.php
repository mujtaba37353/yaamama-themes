<?php
/**
 * منتجات وتصنيفات ديمو - اعتماداً على ملفات التصميم (store/index.html)
 * التصنيفات: الملابس، مجوهرات، أثاث، كتب، مطاعم، أزياء
 * منتجات داخل كل تصنيف + منتجات عامة بدون تصنيف.
 *
 * @package Yaamama_Theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'YAAMAMA_DEMO_META', '_yaamama_demo' );
define( 'YAAMAMA_DEMO_TERM_META', 'yaamama_demo' );

/**
 * التصنيفات من تصميم المتجر (تبويبات store/index.html)
 */
function yaamama_demo_categories() {
	return array(
		'albas'      => 'الملابس',
		'mjawhrt'    => 'مجوهرات',
		'athath'     => 'أثاث',
		'ktub'       => 'كتب',
		'mtaem'      => 'مطاعم',
		'azya'       => 'أزياء',
	);
}

/**
 * قالب منتج ديمو من التصميم (خطى 990 - السعر شامل سعر الثيم وباقة اليمامة)
 */
function yaamama_demo_product_template( $title, $category_name ) {
	return array(
		'name'               => $title,
		'price'              => '990',
		'short_description'  => 'السعر شامل سعر الثيم وباقة اليمامة',
		'description'        => 'قالب أنيق مناسب لعرض منتجات مميزة مثل الأحذية والشنط. يتميز بتصميم عصري يركز على جمالية المنتجات وسهولة التصفح.',
		'category_label'     => $category_name,
	);
}

/**
 * صور المنتج من أصول التصميم (نفس المستخدمة في store/index.html)
 */
function yaamama_demo_product_image_paths() {
	$dir = get_template_directory() . '/yaamama-front-platform/assets/';
	return array(
		$dir . 'theme-img.png',
		$dir . 'product.png',
		$dir . 'temp1.png',
		$dir . 'temp2.png',
		$dir . 'temp3.png',
		$dir . 'temp4.png',
		$dir . 'single-temp-hero.png',
	);
}

/**
 * رفع صورة من مسار الثيم إلى المكتبة وإرجاع attachment_id
 */
function yaamama_sideload_demo_image( $file_path ) {
	if ( ! file_exists( $file_path ) || ! function_exists( 'wp_generate_attachment_metadata' ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$filename   = basename( $file_path );
	$upload_dir = wp_upload_dir();
	if ( wp_mkdir_p( $upload_dir['path'] ) ) {
		$dest = $upload_dir['path'] . '/' . $filename;
	} else {
		$dest = $upload_dir['basedir'] . '/' . $filename;
	}
	if ( ! copy( $file_path, $dest ) ) {
		return 0;
	}
	$file_type = wp_check_filetype( $filename, null );
	$attachment = array(
		'post_mime_type' => $file_type['type'],
		'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);
	$attach_id = wp_insert_attachment( $attachment, $dest );
	if ( is_wp_error( $attach_id ) ) {
		@unlink( $dest );
		return 0;
	}
	$attach_data = wp_generate_attachment_metadata( $attach_id, $dest );
	if ( ! empty( $attach_data ) ) {
		wp_update_attachment_metadata( $attach_id, $attach_data );
	}
	return (int) $attach_id;
}

/**
 * إنشاء أو إرجاع تصنيف ديمو. يُرجع array( 'term_id' => int, 'is_new' => bool ).
 */
function yaamama_get_or_create_demo_category( $slug, $name ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $term && ! is_wp_error( $term ) ) {
		$is_demo = (int) get_term_meta( $term->term_id, YAAMAMA_DEMO_TERM_META, true );
		return array(
			'term_id' => $term->term_id,
			'is_new'  => (bool) $is_demo,
		);
	}
	$insert = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
	if ( is_wp_error( $insert ) ) {
		return array( 'term_id' => 0, 'is_new' => false );
	}
	update_term_meta( $insert['term_id'], YAAMAMA_DEMO_TERM_META, 1 );
	return array(
		'term_id' => $insert['term_id'],
		'is_new'  => true,
	);
}

/**
 * إضافة منتجات وتصنيفات الديمو
 */
function yaamama_seed_demo_products() {
	if ( ! current_user_can( 'manage_options' ) || ! class_exists( 'WooCommerce' ) ) {
		return array( 'error' => 'غير مصرح أو ووكومرس غير مفعّل' );
	}

	$categories = yaamama_demo_categories();
	$image_paths = yaamama_demo_product_image_paths();
	$created_products = array();
	$created_terms = array();

	// 1) إنشاء التصنيفات (نحفظ فقط التي أنشأناها كديمو للحذف لاحقاً)
	foreach ( $categories as $slug => $name ) {
		$res = yaamama_get_or_create_demo_category( $slug, $name );
		if ( ! empty( $res['term_id'] ) && ! empty( $res['is_new'] ) ) {
			$created_terms[] = $res['term_id'];
		}
	}

	// 2) الحصول على صورة واحدة للمنتجات (نفس صورة التصميم)
	$attachment_id = 0;
	foreach ( $image_paths as $path ) {
		$attachment_id = yaamama_sideload_demo_image( $path );
		if ( $attachment_id ) {
			break;
		}
	}

	$product_titles = array( 'خطى', 'ثيم كلاسيك', 'قالب احترافي', 'متجر أنيق', 'ثيم عصري', 'قالب يمامة' );
	$idx = 0;

	// 3) منتجات داخل كل تصنيف (نفس المنتج مكرر لتغطية التصنيفات)
	foreach ( $categories as $slug => $cat_name ) {
		$res = yaamama_get_or_create_demo_category( $slug, $cat_name );
		$term_id = isset( $res['term_id'] ) ? (int) $res['term_id'] : 0;
		if ( ! $term_id ) {
			continue;
		}
		$title = isset( $product_titles[ $idx % count( $product_titles ) ] ) ? $product_titles[ $idx % count( $product_titles ) ] : 'خطى';
		$idx++;
		$data = yaamama_demo_product_template( $title, $cat_name );
		$product_id = yaamama_create_demo_product( $data, array( $term_id ), $attachment_id );
		if ( $product_id ) {
			$created_products[] = $product_id;
		}
		// منتج ثانٍ في نفس التصنيف
		$title2 = isset( $product_titles[ ( $idx + 1 ) % count( $product_titles ) ] ) ? $product_titles[ ( $idx + 1 ) % count( $product_titles ) ] : 'ثيم كلاسيك';
		$data2 = yaamama_demo_product_template( $title2, $cat_name );
		$product_id2 = yaamama_create_demo_product( $data2, array( $term_id ), $attachment_id );
		if ( $product_id2 ) {
			$created_products[] = $product_id2;
		}
		$idx++;
	}

	// 4) منتجات عامة بدون تصنيف
	$general_titles = array( 'قالب عام', 'ثيم متعدد', 'متجر جاهز' );
	foreach ( $general_titles as $gtitle ) {
		$data = yaamama_demo_product_template( $gtitle, '' );
		$product_id = yaamama_create_demo_product( $data, array(), $attachment_id );
		if ( $product_id ) {
			$created_products[] = $product_id;
		}
	}

	update_option( 'yaamama_demo_product_ids', array_unique( array_filter( $created_products ) ) );
	update_option( 'yaamama_demo_category_ids', array_unique( array_filter( $created_terms ) ) );

	return array(
		'products' => count( $created_products ),
		'categories' => count( $created_terms ),
	);
}

/**
 * إنشاء منتج ووكومرس ديمو
 */
function yaamama_create_demo_product( $data, $category_term_ids, $image_attachment_id ) {
	if ( ! function_exists( 'wc_get_product_object' ) ) {
		return 0;
	}
	$product = new WC_Product_Simple();
	$product->set_name( $data['name'] );
	$product->set_status( 'publish' );
	$product->set_catalog_visibility( 'visible' );
	$product->set_price( $data['price'] );
	$product->set_regular_price( $data['price'] );
	$product->set_short_description( $data['short_description'] );
	$product->set_description( $data['description'] );
	if ( $image_attachment_id ) {
		$product->set_image_id( $image_attachment_id );
	}
	$product_id = $product->save();
	if ( ! $product_id ) {
		return 0;
	}
	update_post_meta( $product_id, YAAMAMA_DEMO_META, 1 );
	if ( ! empty( $category_term_ids ) ) {
		wp_set_object_terms( $product_id, $category_term_ids, 'product_cat' );
	}
	return $product_id;
}

/**
 * حذف كل منتجات وتصنيفات الديمو
 */
function yaamama_delete_demo_products_and_categories() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return array( 'error' => 'غير مصرح' );
	}

	$deleted_products = 0;
	$deleted_terms = 0;

	// حذف المنتجات الديمو (بال meta أو بالخيار المحفوظ)
	$saved_ids = get_option( 'yaamama_demo_product_ids', array() );
	if ( is_array( $saved_ids ) ) {
		foreach ( $saved_ids as $pid ) {
			$pid = (int) $pid;
			if ( $pid && get_post_type( $pid ) === 'product' ) {
				wp_delete_post( $pid, true );
				$deleted_products++;
			}
		}
	}
	// أيضاً أي منتج عليه الـ meta
	$query = new WP_Query(
		array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'fields'         => 'ids',
			'meta_query'     => array(
				array(
					'key'   => YAAMAMA_DEMO_META,
					'value' => '1',
				),
			),
		)
	);
	foreach ( $query->posts as $pid ) {
		wp_delete_post( $pid, true );
		$deleted_products++;
	}

	// حذف التصنيفات الديمو
	$saved_term_ids = get_option( 'yaamama_demo_category_ids', array() );
	if ( is_array( $saved_term_ids ) ) {
		foreach ( $saved_term_ids as $tid ) {
			$tid = (int) $tid;
			if ( $tid ) {
				wp_delete_term( $tid, 'product_cat' );
				$deleted_terms++;
			}
		}
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'   => YAAMAMA_DEMO_TERM_META,
					'value' => '1',
				),
			),
		)
	);
	if ( ! is_wp_error( $terms ) && is_array( $terms ) ) {
		foreach ( $terms as $term ) {
			wp_delete_term( $term->term_id, 'product_cat' );
			$deleted_terms++;
		}
	}

	delete_option( 'yaamama_demo_product_ids' );
	delete_option( 'yaamama_demo_category_ids' );

	return array(
		'products'   => $deleted_products,
		'categories' => $deleted_terms,
	);
}

/**
 * قائمة أدمن: إضافة صفحة تحت WooCommerce أو المحتوى
 */
function yaamama_register_demo_products_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$parent = class_exists( 'WooCommerce' ) ? 'woocommerce' : 'yaamama-content';
	add_submenu_page(
		$parent,
		'منتجات ديمو',
		'منتجات ديمو',
		'manage_options',
		'yaamama-demo-products',
		'yaamama_render_demo_products_page'
	);
}
add_action( 'admin_menu', 'yaamama_register_demo_products_admin', 99 );

/**
 * معالج طلب إضافة الديمو
 */
function yaamama_handle_seed_demo() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'غير مصرح' );
	}
	check_admin_referer( 'yaamama_seed_demo', 'yaamama_demo_nonce' );
	$result = yaamama_seed_demo_products();
	wp_safe_redirect(
		add_query_arg(
			array(
				'page'              => 'yaamama-demo-products',
				'yaamama_demo_seed' => isset( $result['error'] ) ? 0 : 1,
				'products'          => isset( $result['products'] ) ? $result['products'] : 0,
				'categories'        => isset( $result['categories'] ) ? $result['categories'] : 0,
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_yaamama_seed_demo', 'yaamama_handle_seed_demo' );

/**
 * معالج طلب حذف الديمو
 */
function yaamama_handle_delete_demo() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'غير مصرح' );
	}
	check_admin_referer( 'yaamama_delete_demo', 'yaamama_demo_nonce' );
	$result = yaamama_delete_demo_products_and_categories();
	wp_safe_redirect(
		add_query_arg(
			array(
				'page'                 => 'yaamama-demo-products',
				'yaamama_demo_delete' => isset( $result['error'] ) ? 0 : 1,
				'deleted_products'     => isset( $result['products'] ) ? $result['products'] : 0,
				'deleted_categories'   => isset( $result['categories'] ) ? $result['categories'] : 0,
			),
			admin_url( 'admin.php' )
		)
	);
	exit;
}
add_action( 'admin_post_yaamama_delete_demo', 'yaamama_handle_delete_demo' );

/**
 * جلب قائمة منتجات الديمو المضافة حالياً
 */
function yaamama_get_current_demo_products() {
	$saved = get_option( 'yaamama_demo_product_ids', array() );
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
					'key'   => YAAMAMA_DEMO_META,
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
			'id'       => $id,
			'title'    => $product->get_name(),
			'price'    => $product->get_price(),
			'categories' => implode( '، ', $cat_names ),
			'edit_url' => get_edit_post_link( $id, 'raw' ),
		);
	}
	return $products;
}

/**
 * جلب قائمة تصنيفات الديمو المضافة حالياً
 */
function yaamama_get_current_demo_categories() {
	$saved = get_option( 'yaamama_demo_category_ids', array() );
	if ( ! is_array( $saved ) ) {
		$saved = array();
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'meta_query' => array(
				array(
					'key'   => YAAMAMA_DEMO_TERM_META,
					'value' => '1',
				),
			),
		)
	);
	if ( is_wp_error( $terms ) || ! is_array( $terms ) ) {
		$terms = array();
	}
	$list = array();
	foreach ( $terms as $term ) {
		$list[] = array(
			'id'    => $term->term_id,
			'name'  => $term->name,
			'slug'  => $term->slug,
			'count' => $term->count,
		);
	}
	foreach ( $saved as $tid ) {
		$tid = (int) $tid;
		if ( ! $tid ) {
			continue;
		}
		$term = get_term( $tid, 'product_cat' );
		if ( $term && ! is_wp_error( $term ) ) {
			$found = false;
			foreach ( $list as $item ) {
				if ( (int) $item['id'] === $tid ) {
					$found = true;
					break;
				}
			}
			if ( ! $found ) {
				$list[] = array(
					'id'    => $term->term_id,
					'name'  => $term->name,
					'slug'  => $term->slug,
					'count' => $term->count,
				);
			}
		}
	}
	return $list;
}

/**
 * عرض صفحة إدارة منتجات الديمو
 */
function yaamama_render_demo_products_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$seed_ok   = isset( $_GET['yaamama_demo_seed'] ) && (int) $_GET['yaamama_demo_seed'] === 1;
	$delete_ok = isset( $_GET['yaamama_demo_delete'] ) && (int) $_GET['yaamama_demo_delete'] === 1;
	$categories = yaamama_demo_categories();
	$demo_products = yaamama_get_current_demo_products();
	$demo_cats = yaamama_get_current_demo_categories();
	?>
	<div class="wrap">
		<h1>منتجات وتصنيفات ديمو</h1>
		<p>اعتماداً على ملفات التصميم (ثيمات اليمامة – تبويبات المتجر): التصنيفات والمنتجات المعروضة في التصميم مع نفس الصور.</p>

		<?php if ( $seed_ok ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>
					تمت إضافة منتجات وتصنيفات الديمو بنجاح.
					<?php
					$p = isset( $_GET['products'] ) ? (int) $_GET['products'] : 0;
					$c = isset( $_GET['categories'] ) ? (int) $_GET['categories'] : 0;
					if ( $p || $c ) {
						echo ' منتجات: ' . $p . '، تصنيفات: ' . $c . '.';
					}
					?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( $delete_ok ) : ?>
			<div class="notice notice-success is-dismissible">
				<p>
					تم حذف منتجات وتصنيفات الديمو.
					<?php
					$dp = isset( $_GET['deleted_products'] ) ? (int) $_GET['deleted_products'] : 0;
					$dc = isset( $_GET['deleted_categories'] ) ? (int) $_GET['deleted_categories'] : 0;
					if ( $dp || $dc ) {
						echo ' تم حذف منتجات: ' . $dp . '، تصنيفات: ' . $dc . '.';
					}
					?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $demo_products ) || ! empty( $demo_cats ) ) : ?>
			<h2 style="margin-top: 24px;">المنتجات والتصنيفات الديمو المضافة</h2>
			<?php if ( ! empty( $demo_products ) ) : ?>
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
			<?php endif; ?>
			<?php if ( ! empty( $demo_cats ) ) : ?>
				<h3 style="margin-top: 20px;">تصنيفات الديمو (<?php echo count( $demo_cats ); ?>)</h3>
				<table class="widefat fixed striped" style="margin-top: 8px; max-width: 600px;">
					<thead>
						<tr>
							<th>التصنيف</th>
							<th style="width: 120px;">المسار</th>
							<th style="width: 80px;">عدد المنتجات</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $demo_cats as $c ) : ?>
							<tr>
								<td><?php echo esc_html( $c['name'] ); ?></td>
								<td><code><?php echo esc_html( $c['slug'] ); ?></code></td>
								<td><?php echo (int) $c['count']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			<?php endif; ?>
			<p style="margin-top: 16px;">
				<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block;">
					<?php wp_nonce_field( 'yaamama_delete_demo', 'yaamama_demo_nonce' ); ?>
					<input type="hidden" name="action" value="yaamama_delete_demo">
					<button type="submit" class="button button-secondary" onclick="return confirm('هل تريد حذف كل المنتجات والتصنيفات الديمو؟');">مسح كل المنتجات والتصنيفات الديمو</button>
				</form>
			</p>
		<?php endif; ?>

		<div class="card" style="max-width: 520px; padding: 20px; margin: 20px 0;">
			<h2 style="margin-top: 0;">التصنيفات (من التصميم)</h2>
			<p>سيتم إنشاء التصنيفات التالية إن لم تكن موجودة:</p>
			<ul style="list-style: disc; margin-right: 24px;">
				<?php foreach ( $categories as $slug => $name ) : ?>
					<li><?php echo esc_html( $name ); ?> (<code><?php echo esc_html( $slug ); ?></code>)</li>
				<?php endforeach; ?>
			</ul>
			<p>سيتم إضافة منتجات داخل كل تصنيف (نفس الصور المستخدمة في ملفات التصميم) ومنتجات عامة بدون تصنيف.</p>
		</div>

		<p>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block;">
				<?php wp_nonce_field( 'yaamama_seed_demo', 'yaamama_demo_nonce' ); ?>
				<input type="hidden" name="action" value="yaamama_seed_demo">
				<button type="submit" class="button button-primary">إضافة منتجات وتصنيفات ديمو</button>
			</form>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display: inline-block; margin-right: 12px;">
				<?php wp_nonce_field( 'yaamama_delete_demo', 'yaamama_demo_nonce' ); ?>
				<input type="hidden" name="action" value="yaamama_delete_demo">
				<button type="submit" class="button" onclick="return confirm('هل تريد حذف كل المنتجات والتصنيفات الديمو؟');">حذف كل المنتجات والتصنيفات الديمو</button>
			</form>
		</p>
	</div>
	<?php
}
