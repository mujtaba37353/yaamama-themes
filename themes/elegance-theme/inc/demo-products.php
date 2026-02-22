<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: demo-products — تصنيفات ومنتجات ديمو + حذفها.

define( 'ELEGANCE_DEMO_TERM_META', '_elegance_demo' );
define( 'ELEGANCE_DEMO_PRODUCT_META', '_elegance_demo' );

/**
 * Demo category hierarchy: parent slug => [ 'name' => string, 'children' => [ slug => name, ... ] ].
 * Parent slug is prefixed with elegance-demo- when creating.
 *
 * @return array
 */
function elegance_demo_categories_config() {
	return array(
		'elegance-demo-رجالى'       => array(
			'name'     => 'رجالي',
			'children' => array(),
		),
		'elegance-demo-نسائى'       => array(
			'name'     => 'نسائي',
			'children' => array(),
		),
		'elegance-demo-أطفال'       => array(
			'name'     => 'أطفال',
			'children' => array(),
		),
		'elegance-demo-ماركات-فاخرة' => array(
			'name'     => 'ماركات فاخرة',
			'children' => array(),
		),
		'elegance-demo-ملابس-رياضية' => array(
			'name'     => 'ملابس رياضية',
			'children' => array(
				'elegance-demo-رياضية-بوتيكات' => 'بوتيكات',
				'elegance-demo-تانك-توب'        => 'تانك توب',
				'elegance-demo-ليجنز'          => 'ليجنز',
				'elegance-demo-شورتات'         => 'شورتات',
				'elegance-demo-سويت-بانتس'     => 'سويت بانتس',
			),
		),
		'elegance-demo-احذية'       => array(
			'name'     => 'أحذية',
			'children' => array(
				'elegance-demo-أحذية-رياضية' => 'أحذية رياضية',
				'elegance-demo-أحذية-كاجوال' => 'أحذية كاجوال',
				'elegance-demo-أحذية-رسمية' => 'أحذية رسمية',
				'elegance-demo-أحذية-سنيكرز' => 'أحذية سنيكرز',
				'elegance-demo-صنادل'       => 'صنادل',
			),
		),
		'elegance-demo-بوتيكات'     => array(
			'name'     => 'بوتيكات',
			'children' => array(),
		),
	);
}

/**
 * Create demo product categories (WooCommerce) with hierarchy. No duplicates.
 *
 * @return int|WP_Error Number of terms created or error.
 */
function elegance_create_demo_categories() {
	if ( ! function_exists( 'wc_get_product_cat_ids' ) || ! taxonomy_exists( 'product_cat' ) ) {
		return new WP_Error( 'no_woo', __( 'WooCommerce غير مفعّل.', 'elegance' ) );
	}
	$config  = elegance_demo_categories_config();
	$created = 0;

	// Create parent terms first.
	foreach ( $config as $parent_slug => $data ) {
		$term = get_term_by( 'slug', $parent_slug, 'product_cat' );
		if ( $term ) {
			continue;
		}
		$r = wp_insert_term( $data['name'], 'product_cat', array( 'slug' => $parent_slug ) );
		if ( ! is_wp_error( $r ) ) {
			update_term_meta( $r['term_id'], ELEGANCE_DEMO_TERM_META, 1 );
			$created++;
		}
	}

	// Create child terms (parent must exist).
	foreach ( $config as $parent_slug => $data ) {
		if ( empty( $data['children'] ) ) {
			continue;
		}
		$parent = get_term_by( 'slug', $parent_slug, 'product_cat' );
		if ( ! $parent ) {
			continue;
		}
		foreach ( $data['children'] as $child_slug => $child_name ) {
			$term = get_term_by( 'slug', $child_slug, 'product_cat' );
			if ( $term ) {
				continue;
			}
			$r = wp_insert_term( $child_name, 'product_cat', array(
				'slug'   => $child_slug,
				'parent' => (int) $parent->term_id,
			) );
			if ( ! is_wp_error( $r ) ) {
				update_term_meta( $r['term_id'], ELEGANCE_DEMO_TERM_META, 1 );
				$created++;
			}
		}
	}

	return $created;
}

/**
 * Sideload image from theme asset URL into media library. Returns attachment ID or 0.
 *
 * @param string $url     Full URL to image.
 * @param int    $post_id Associated post (e.g. product ID).
 * @return int
 */
function elegance_sideload_asset_image( $url, $post_id = 0 ) {
	if ( ! function_exists( 'media_handle_sideload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
		require_once ABSPATH . 'wp-admin/includes/file.php';
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}
	$tmp = download_url( $url );
	if ( is_wp_error( $tmp ) ) {
		return 0;
	}
	$file_array = array(
		'name'     => basename( $url ),
		'tmp_name' => $tmp,
	);
	$id = media_handle_sideload( $file_array, $post_id );
	if ( is_wp_error( $id ) ) {
		@unlink( $tmp );
		return 0;
	}
	return (int) $id;
}

/**
 * قائمة منتجات الديمو: عناوين، أسعار مختلفة، نفس صور التصميم (sales1–6)، موزعة على التصنيفات والفرعية.
 *
 * @return array
 */
function elegance_demo_products_config() {
	return array(
		array( 'title' => 'قميص قطن صيفى', 'price' => 50, 'regular' => 100, 'image' => 'sales1.png', 'categories' => array( 'elegance-demo-نسائى', 'elegance-demo-ملابس-رياضية', 'elegance-demo-تانك-توب' ) ),
		array( 'title' => 'شنطة ظهر حريمي', 'price' => 80, 'regular' => 0, 'image' => 'sales2.png', 'categories' => array( 'elegance-demo-نسائى', 'elegance-demo-بوتيكات' ) ),
		array( 'title' => 'منتج ديمو ٣', 'price' => 75, 'regular' => 0, 'image' => 'sales3.png', 'categories' => array( 'elegance-demo-رجالى', 'elegance-demo-ملابس-رياضية' ) ),
		array( 'title' => 'منتج ديمو ٤', 'price' => 60, 'regular' => 0, 'image' => 'sales4.png', 'categories' => array( 'elegance-demo-أطفال', 'elegance-demo-احذية', 'elegance-demo-أحذية-رياضية' ) ),
		array( 'title' => 'منتج ديمو ٥', 'price' => 90, 'regular' => 0, 'image' => 'sales5.png', 'categories' => array( 'elegance-demo-ماركات-فاخرة', 'elegance-demo-احذية' ) ),
		array( 'title' => 'منتج ديمو ٦', 'price' => 70, 'regular' => 0, 'image' => 'sales6.png', 'categories' => array( 'elegance-demo-رجالى', 'elegance-demo-ملابس-رياضية', 'elegance-demo-شورتات' ) ),
		array( 'title' => 'فستان صيفي نسائي', 'price' => 125, 'regular' => 0, 'image' => 'sales1.png', 'categories' => array( 'elegance-demo-نسائى', 'elegance-demo-بوتيكات' ) ),
		array( 'title' => 'تيشيرت رجالي', 'price' => 45, 'regular' => 65, 'image' => 'sales2.png', 'categories' => array( 'elegance-demo-رجالى', 'elegance-demo-ملابس-رياضية', 'elegance-demo-تانك-توب' ) ),
		array( 'title' => 'حذاء رياضي أطفال', 'price' => 95, 'regular' => 0, 'image' => 'sales3.png', 'categories' => array( 'elegance-demo-أطفال', 'elegance-demo-احذية', 'elegance-demo-أحذية-رياضية' ) ),
		array( 'title' => 'ليجنز نسائي', 'price' => 68, 'regular' => 0, 'image' => 'sales4.png', 'categories' => array( 'elegance-demo-نسائى', 'elegance-demo-ملابس-رياضية', 'elegance-demo-ليجنز' ) ),
		array( 'title' => 'صنادل كاجوال', 'price' => 110, 'regular' => 0, 'image' => 'sales5.png', 'categories' => array( 'elegance-demo-احذية', 'elegance-demo-صنادل' ) ),
		array( 'title' => 'سويت بانتس رجالي', 'price' => 99, 'regular' => 0, 'image' => 'sales6.png', 'categories' => array( 'elegance-demo-رجالى', 'elegance-demo-ملابس-رياضية', 'elegance-demo-سويت-بانتس' ) ),
		array( 'title' => 'منتج ديمو ٧', 'price' => 140, 'regular' => 0, 'image' => 'sales1.png', 'categories' => array( 'elegance-demo-ماركات-فاخرة' ) ),
		array( 'title' => 'أحذية رسمية', 'price' => 199, 'regular' => 249, 'image' => 'sales2.png', 'categories' => array( 'elegance-demo-احذية', 'elegance-demo-أحذية-رسمية' ) ),
		array( 'title' => 'بوتيكات ملابس رياضية', 'price' => 85, 'regular' => 0, 'image' => 'sales3.png', 'categories' => array( 'elegance-demo-ملابس-رياضية', 'elegance-demo-رياضية-بوتيكات' ) ),
		array( 'title' => 'منتج ديمو ٨', 'price' => 55, 'regular' => 0, 'image' => 'sales4.png', 'categories' => array( 'elegance-demo-أطفال', 'elegance-demo-بوتيكات' ) ),
		array( 'title' => 'سنيكرز', 'price' => 165, 'regular' => 0, 'image' => 'sales5.png', 'categories' => array( 'elegance-demo-احذية', 'elegance-demo-أحذية-سنيكرز' ) ),
		array( 'title' => 'أحذية كاجوال', 'price' => 120, 'regular' => 0, 'image' => 'sales6.png', 'categories' => array( 'elegance-demo-احذية', 'elegance-demo-أحذية-كاجوال' ) ),
		array( 'title' => 'منتج ديمو ٩', 'price' => 72, 'regular' => 0, 'image' => 'sales1.png', 'categories' => array( 'elegance-demo-نسائى', 'elegance-demo-ملابس-رياضية', 'elegance-demo-شورتات' ) ),
		array( 'title' => 'منتج ديمو ١٠', 'price' => 158, 'regular' => 0, 'image' => 'sales2.png', 'categories' => array( 'elegance-demo-ماركات-فاخرة', 'elegance-demo-بوتيكات' ) ),
	);
}

/**
 * Create demo products. No duplicates (by meta _elegance_demo).
 *
 * @return int|WP_Error Number created or error.
 */
function elegance_create_demo_products() {
	if ( ! function_exists( 'wc_get_product' ) || ! class_exists( 'WC_Product_Simple' ) ) {
		return new WP_Error( 'no_woo', __( 'WooCommerce غير مفعّل.', 'elegance' ) );
	}
	$base_sku   = 'elegance-demo-';
	$existing   = get_posts( array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'meta_key'       => ELEGANCE_DEMO_PRODUCT_META,
		'meta_value'     => '1',
		'fields'         => 'ids',
	) );
	if ( ! empty( $existing ) ) {
		return 0; // Already have demo products — no duplicate.
	}
	$assets_uri = get_template_directory_uri() . '/elegance/assets';
	$products   = elegance_demo_products_config();
	$created    = 0;
	foreach ( $products as $i => $p ) {
		$product = new WC_Product_Simple();
		$product->set_name( $p['title'] );
		$product->set_status( 'publish' );
		$product->set_sku( $base_sku . ( $i + 1 ) );
		$product->set_regular_price( $p['regular'] ? (string) $p['regular'] : (string) $p['price'] );
		if ( ! empty( $p['regular'] ) && (int) $p['regular'] > 0 ) {
			$product->set_sale_price( (string) $p['price'] );
		}
		$id = $product->save();
		if ( $id ) {
			update_post_meta( $id, ELEGANCE_DEMO_PRODUCT_META, 1 );
			if ( ! empty( $p['categories'] ) && taxonomy_exists( 'product_cat' ) ) {
				wp_set_object_terms( $id, array_map( 'sanitize_title', $p['categories'] ), 'product_cat' );
			}
			$img_url = $assets_uri . '/' . $p['image'];
			$att_id  = elegance_sideload_asset_image( $img_url, $id );
			if ( $att_id ) {
				$product->set_image_id( $att_id );
				$product->save();
			}
			$created++;
		}
	}
	return $created;
}

/**
 * Delete all demo products and optionally demo categories.
 */
function elegance_delete_demo_products() {
	if ( ! function_exists( 'wc_get_product' ) ) {
		return new WP_Error( 'no_woo', __( 'WooCommerce غير مفعّل.', 'elegance' ) );
	}
	$ids = get_posts( array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'meta_key'       => ELEGANCE_DEMO_PRODUCT_META,
		'meta_value'     => '1',
		'fields'         => 'ids',
		'post_status'    => 'any',
	) );
	$deleted = 0;
	foreach ( $ids as $id ) {
		if ( wp_delete_post( $id, true ) ) {
			$deleted++;
		}
	}
	return $deleted;
}

add_action( 'elegance_admin_render_elegance_demo_products', 'elegance_render_demo_products_admin' );

function elegance_render_demo_products_admin() {
	$message = '';
	$action  = isset( $_POST['elegance_demo_action'] ) ? sanitize_text_field( wp_unslash( $_POST['elegance_demo_action'] ) ) : '';
	if ( $action && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['elegance_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['elegance_admin_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'elegance_admin_elegance_demo_products' ) ) {
			$message = __( 'خطأ في التحقق.', 'elegance' );
		} elseif ( $action === 'create_all' ) {
			$cats_created = elegance_create_demo_categories();
			if ( is_wp_error( $cats_created ) ) {
				$message = $cats_created->get_error_message();
			} else {
				$prods_created = elegance_create_demo_products();
				if ( is_wp_error( $prods_created ) ) {
					$message = $prods_created->get_error_message();
				} else {
					$msg_parts = array();
					if ( $cats_created > 0 ) {
						$msg_parts[] = sprintf( __( '%d تصنيف/تصنيف فرعي', 'elegance' ), $cats_created );
					}
					if ( $prods_created > 0 ) {
						$msg_parts[] = sprintf( __( '%d منتج', 'elegance' ), $prods_created );
					}
					if ( empty( $msg_parts ) ) {
						$message = __( 'التصنيفات ومنتجات الديمو موجودة مسبقاً — لم يتم إنشاء مكرر.', 'elegance' );
					} else {
						$message = sprintf( __( 'تم إنشاء: %s.', 'elegance' ), implode( '، ', $msg_parts ) );
					}
				}
			}
		} elseif ( $action === 'delete_products' ) {
			$result = elegance_delete_demo_products();
			if ( is_wp_error( $result ) ) {
				$message = $result->get_error_message();
			} else {
				$message = sprintf( __( 'تم حذف %d منتج ديمو.', 'elegance' ), $result );
			}
		}
	}

	if ( $message ) {
		$class = ( strpos( $message, 'خطأ' ) !== false || strpos( $message, 'غير مفعّل' ) !== false ) ? 'notice-error' : 'notice-success';
		echo '<div class="notice ' . esc_attr( $class ) . ' is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
	}

	$demo_count = 0;
	if ( function_exists( 'wc_get_product' ) ) {
		$demo_count = count( get_posts( array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'meta_key'       => ELEGANCE_DEMO_PRODUCT_META,
			'meta_value'     => '1',
			'fields'         => 'ids',
		) ) );
	}
	?>
	<?php if ( ! function_exists( 'wc_get_product' ) ) : ?>
		<div class="notice notice-warning"><p><?php esc_html_e( 'WooCommerce غير مفعّل. تفعيله مطلوب لإنشاء منتجات ديمو.', 'elegance' ); ?></p></div>
	<?php endif; ?>

	<p><?php echo esc_html( sprintf( __( 'منتجات الديمو الحالية: %d', 'elegance' ), $demo_count ) ); ?></p>

	<form method="post" style="margin: 12px 0;">
		<?php wp_nonce_field( 'elegance_admin_elegance_demo_products', 'elegance_admin_nonce' ); ?>
		<input type="hidden" name="elegance_demo_action" value="create_all" />
		<button type="submit" class="button button-primary"><?php esc_html_e( 'إنشاء تصنيفات ومنتجات الديمو', 'elegance' ); ?></button>
	</form>
	<p class="description"><?php esc_html_e( 'ينشئ التصنيفات الرئيسية والفرعية ثم المنتجات موزعة عليها، باستخدام صور التصميم وأسعار مختلفة.', 'elegance' ); ?></p>
	<form method="post" style="margin: 12px 0;">
		<?php wp_nonce_field( 'elegance_admin_elegance_demo_products', 'elegance_admin_nonce' ); ?>
		<input type="hidden" name="elegance_demo_action" value="delete_products" />
		<button type="submit" class="button" onclick="return confirm('<?php echo esc_js( __( 'حذف كل منتجات الديمو؟', 'elegance' ) ); ?>');"><?php esc_html_e( 'حذف منتجات الديمو', 'elegance' ); ?></button>
	</form>

	<?php
	// جدول: منتجات الديمو + التصنيفات + التصنيفات الفرعية
	$demo_products = array();
	if ( function_exists( 'wc_get_product' ) && taxonomy_exists( 'product_cat' ) ) {
		$demo_ids = get_posts( array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'meta_key'       => ELEGANCE_DEMO_PRODUCT_META,
			'meta_value'     => '1',
			'fields'         => 'ids',
			'post_status'    => 'any',
			'orderby'        => 'title',
			'order'          => 'ASC',
		) );
		foreach ( $demo_ids as $pid ) {
			$product = wc_get_product( $pid );
			if ( ! $product ) {
				continue;
			}
			$terms = wp_get_object_terms( $pid, 'product_cat' );
			$parents = array();
			$children = array();
			if ( ! is_wp_error( $terms ) ) {
				foreach ( $terms as $t ) {
					if ( (int) $t->parent === 0 ) {
						$parents[] = $t->name;
					} else {
						$children[] = $t->name;
					}
				}
			}
			$demo_products[] = array(
				'id'        => $pid,
				'title'     => $product->get_name(),
				'edit_url'  => get_edit_post_link( $pid, 'raw' ),
				'parents'   => $parents,
				'children'  => $children,
			);
		}
	}

	if ( ! empty( $demo_products ) ) :
		?>
		<h2 class="title" style="margin-top: 24px;"><?php esc_html_e( 'جدول منتجات الديمو والتصنيفات', 'elegance' ); ?></h2>
		<table class="widefat striped" style="margin-top: 8px; max-width: 800px;">
			<thead>
				<tr>
					<th style="width: 25%;"><?php esc_html_e( 'المنتج', 'elegance' ); ?></th>
					<th style="width: 35%;"><?php esc_html_e( 'التصنيفات', 'elegance' ); ?></th>
					<th style="width: 40%;"><?php esc_html_e( 'التصنيفات الفرعية', 'elegance' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach ( $demo_products as $row ) : ?>
					<tr>
						<td>
							<?php if ( ! empty( $row['edit_url'] ) ) : ?>
								<a href="<?php echo esc_url( $row['edit_url'] ); ?>"><?php echo esc_html( $row['title'] ); ?></a>
							<?php else : ?>
								<?php echo esc_html( $row['title'] ); ?>
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( implode( '، ', $row['parents'] ) ); ?></td>
						<td><?php echo esc_html( implode( '، ', $row['children'] ) ); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<?php
	elseif ( function_exists( 'wc_get_product' ) ) :
		?>
		<p class="description" style="margin-top: 16px;"><?php esc_html_e( 'لا توجد منتجات ديمو. أنشئ منتجات الديمو لعرض الجدول.', 'elegance' ); ?></p>
		<?php
	endif;
}
