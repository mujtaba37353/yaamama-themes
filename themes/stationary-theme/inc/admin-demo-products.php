<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'STATIONARY_DEMO_TERM_META', '_stationary_is_demo' );
define( 'STATIONARY_DEMO_PRODUCT_META', '_stationary_is_demo' );

function stationary_demo_categories_config() {
	return array(
		'stationary-demo-اقلام-ادوات-الكتابة'   => 'أقلام و أدوات الكتابة',
		'stationary-demo-ادوات-مدرسية'         => 'الأدوات المدرسية',
		'stationary-demo-الات-حاسبة'           => 'آلات حاسبة',
		'stationary-demo-اطقم-المكتب'          => 'أطقم المكتب',
		'stationary-demo-كراسات-منتجات-ورقية'   => 'كراسات و منتجات ورقية',
	);
}

function stationary_create_demo_categories() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return new WP_Error( 'no_woo', __( 'WooCommerce غير مفعّل.', 'stationary-theme' ) );
	}
	$config  = stationary_demo_categories_config();
	$created = 0;
	foreach ( $config as $slug => $name ) {
		$term = get_term_by( 'slug', $slug, 'product_cat' );
		if ( $term ) {
			continue;
		}
		$r = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
		if ( ! is_wp_error( $r ) ) {
			update_term_meta( $r['term_id'], STATIONARY_DEMO_TERM_META, 1 );
			$created++;
		}
	}
	return $created;
}

function stationary_sideload_asset_image( $url, $post_id = 0 ) {
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

function stationary_create_demo_products() {
	if ( ! class_exists( 'WC_Product_Simple' ) ) {
		return new WP_Error( 'no_woo', __( 'WooCommerce غير مفعّل.', 'stationary-theme' ) );
	}
	$existing = get_posts( array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'meta_key'       => STATIONARY_DEMO_PRODUCT_META,
		'meta_value'     => '1',
		'fields'         => 'ids',
	) );
	if ( ! empty( $existing ) ) {
		return 0;
	}
	$assets_uri = stationary_base_uri() . '/assets';
	$products   = array(
		array( 'title' => 'نوت بوك مسطر غلاف ورق 100 ورقة', 'price' => 50, 'regular' => 100, 'image' => 'product-1.png', 'cat_slug' => 'stationary-demo-كراسات-منتجات-ورقية' ),
		array( 'title' => 'دفتر رسم A4', 'price' => 45, 'regular' => 0, 'image' => 'product-1.png', 'cat_slug' => 'stationary-demo-كراسات-منتجات-ورقية' ),
		array( 'title' => 'قلم حبر جاف 12 لون', 'price' => 25, 'regular' => 0, 'image' => 'product-1.png', 'cat_slug' => 'stationary-demo-اقلام-ادوات-الكتابة' ),
		array( 'title' => 'مجلد بلاستيك 40 جيب', 'price' => 35, 'regular' => 0, 'image' => 'product-1.png', 'cat_slug' => 'stationary-demo-ادوات-مدرسية' ),
		array( 'title' => 'آلة حاسبة علمية', 'price' => 80, 'regular' => 120, 'image' => 'product-1.png', 'cat_slug' => 'stationary-demo-الات-حاسبة' ),
		array( 'title' => 'طقم مكتب 12 قطعة', 'price' => 90, 'regular' => 0, 'image' => 'product-1.png', 'cat_slug' => 'stationary-demo-اطقم-المكتب' ),
	);
	$created = 0;
	$base_sku = 'stationary-demo-';
	foreach ( $products as $i => $p ) {
		$product = new WC_Product_Simple();
		$product->set_name( $p['title'] );
		$product->set_status( 'publish' );
		$product->set_sku( $base_sku . ( $i + 1 ) );
		$product->set_regular_price( $p['regular'] ? (string) $p['regular'] : (string) $p['price'] );
		if ( ! empty( $p['regular'] ) ) {
			$product->set_sale_price( (string) $p['price'] );
		}
		$id = $product->save();
		if ( $id ) {
			update_post_meta( $id, STATIONARY_DEMO_PRODUCT_META, 1 );
			if ( ! empty( $p['cat_slug'] ) && taxonomy_exists( 'product_cat' ) ) {
				$term = get_term_by( 'slug', $p['cat_slug'], 'product_cat' );
				if ( $term ) {
					wp_set_object_terms( $id, (int) $term->term_id, 'product_cat' );
				}
			}
			$img_url = $assets_uri . '/' . $p['image'];
			$att_id = stationary_sideload_asset_image( $img_url, $id );
			if ( $att_id ) {
				$product->set_image_id( $att_id );
				$product->save();
			}
			$created++;
		}
	}
	return $created;
}

function stationary_delete_demo_products() {
	if ( ! class_exists( 'WC_Product_Simple' ) ) {
		return new WP_Error( 'no_woo', __( 'WooCommerce غير مفعّل.', 'stationary-theme' ) );
	}
	$ids = get_posts( array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'meta_key'       => STATIONARY_DEMO_PRODUCT_META,
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

function stationary_create_demo_all() {
	$cats_result = stationary_create_demo_categories();
	if ( is_wp_error( $cats_result ) ) {
		return $cats_result;
	}
	$prods_result = stationary_create_demo_products();
	if ( is_wp_error( $prods_result ) ) {
		return $prods_result;
	}
	return array( 'cats' => $cats_result, 'products' => $prods_result );
}

function stationary_delete_demo_term( $term_id ) {
	$count = 0;
	if ( taxonomy_exists( 'product_cat' ) ) {
		$products = get_posts( array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'tax_query'      => array(
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'term_id',
					'terms'    => $term_id,
				),
			),
			'fields'         => 'ids',
		) );
		$count = count( $products );
	}
	if ( $count > 0 ) {
		return new WP_Error( 'has_products', sprintf( __( 'التصنيف مرتبط بـ %d منتج. احذف المنتجات أولاً أو أزل ارتباطها.', 'stationary-theme' ), $count ) );
	}
	wp_delete_term( $term_id, 'product_cat' );
	return true;
}

add_action( 'stationary_admin_render_stationary_demo_products', 'stationary_render_demo_products_admin' );

function stationary_render_demo_products_admin() {
	$message = '';
	$action  = isset( $_POST['stationary_demo_action'] ) ? sanitize_text_field( wp_unslash( $_POST['stationary_demo_action'] ) ) : '';
	if ( $action && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['stationary_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['stationary_admin_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'stationary_admin_stationary_demo_products' ) ) {
			$message = __( 'خطأ في التحقق من الأمان.', 'stationary-theme' );
		} elseif ( $action === 'create_all' ) {
			$result = stationary_create_demo_all();
			if ( is_wp_error( $result ) ) {
				$message = $result->get_error_message();
			} else {
				$cats = $result['cats'];
				$prods = $result['products'];
				if ( $cats > 0 && $prods > 0 ) {
					$message = sprintf( __( 'تم إنشاء %1$d تصنيف و %2$d منتج، وتم ربط المنتجات بالتصنيفات.', 'stationary-theme' ), $cats, $prods );
				} elseif ( $cats > 0 ) {
					$message = sprintf( __( 'تم إنشاء %d تصنيف. المنتجات موجودة مسبقاً.', 'stationary-theme' ), $cats );
				} elseif ( $prods > 0 ) {
					$message = sprintf( __( 'تم إنشاء %d منتج وربطها بالتصنيفات.', 'stationary-theme' ), $prods );
				} else {
					$message = __( 'التصنيفات والمنتجات موجودة مسبقاً — لم يتم إنشاء مكرر.', 'stationary-theme' );
				}
			}
		} elseif ( $action === 'delete_products' ) {
			$result = stationary_delete_demo_products();
			if ( is_wp_error( $result ) ) {
				$message = $result->get_error_message();
			} else {
				$message = sprintf( __( 'تم حذف %d منتج ديمو.', 'stationary-theme' ), $result );
			}
		} elseif ( $action === 'delete_product' ) {
			$pid = isset( $_POST['product_id'] ) ? absint( $_POST['product_id'] ) : 0;
			if ( $pid && get_post_meta( $pid, STATIONARY_DEMO_PRODUCT_META, true ) === '1' ) {
				wp_delete_post( $pid, true );
				$message = __( 'تم حذف المنتج.', 'stationary-theme' );
			}
		} elseif ( $action === 'delete_term' ) {
			$tid = isset( $_POST['term_id'] ) ? absint( $_POST['term_id'] ) : 0;
			if ( $tid && get_term_meta( $tid, STATIONARY_DEMO_TERM_META, true ) === '1' ) {
				$result = stationary_delete_demo_term( $tid );
				if ( is_wp_error( $result ) ) {
					$message = $result->get_error_message();
				} else {
					$message = __( 'تم حذف التصنيف.', 'stationary-theme' );
				}
			}
		}
	}

	if ( $message ) {
		$class = ( is_wp_error( $message ) || strpos( $message, 'خطأ' ) !== false || strpos( $message, 'غير مفعّل' ) !== false ) ? 'notice-error' : 'notice-success';
		echo '<div class="notice ' . esc_attr( $class ) . ' is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
	}

	$demo_cats = array();
	$demo_products = array();
	if ( taxonomy_exists( 'product_cat' ) ) {
		$terms = get_terms( array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => false,
			'meta_query' => array(
				array( 'key' => STATIONARY_DEMO_TERM_META, 'value' => '1' ),
			),
		) );
		if ( ! is_wp_error( $terms ) ) {
			$demo_cats = $terms;
		}
	}
	if ( class_exists( 'WC_Product_Simple' ) ) {
		$demo_products = get_posts( array(
			'post_type'      => 'product',
			'posts_per_page' => -1,
			'meta_key'       => STATIONARY_DEMO_PRODUCT_META,
			'meta_value'     => '1',
			'post_status'    => 'any',
		) );
	}
	?>
	<?php if ( ! class_exists( 'WC_Product_Simple' ) ) : ?>
		<div class="notice notice-warning"><p><?php esc_html_e( 'WooCommerce غير مفعّل. تفعيله مطلوب لإنشاء منتجات ديمو.', 'stationary-theme' ); ?></p></div>
	<?php endif; ?>

	<div style="margin: 16px 0; display: flex; gap: 12px; flex-wrap: wrap;">
		<form method="post" style="display:inline">
			<?php wp_nonce_field( 'stationary_admin_stationary_demo_products', 'stationary_admin_nonce' ); ?>
			<input type="hidden" name="stationary_demo_action" value="create_all" />
			<button type="submit" class="button button-primary"><?php esc_html_e( 'إنشاء تصنيفات ومنتجات ديمو', 'stationary-theme' ); ?></button>
		</form>
		<form method="post" style="display:inline" onsubmit="return confirm('<?php echo esc_js( __( 'حذف كل منتجات الديمو؟', 'stationary-theme' ) ); ?>');">
			<?php wp_nonce_field( 'stationary_admin_stationary_demo_products', 'stationary_admin_nonce' ); ?>
			<input type="hidden" name="stationary_demo_action" value="delete_products" />
			<button type="submit" class="button"><?php esc_html_e( 'حذف منتجات الديمو', 'stationary-theme' ); ?></button>
		</form>
	</div>

	<h2><?php esc_html_e( 'التصنيفات المنشأة', 'stationary-theme' ); ?></h2>
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th><?php esc_html_e( 'الاسم', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'Slug', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'العدد', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'ديمو', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'تعديل', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'حذف', 'stationary-theme' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $demo_cats ) ) : ?>
				<tr><td colspan="6"><?php esc_html_e( 'لا توجد تصنيفات ديمو.', 'stationary-theme' ); ?></td></tr>
			<?php else : ?>
				<?php foreach ( $demo_cats as $t ) : ?>
					<tr>
						<td><?php echo esc_html( $t->name ); ?></td>
						<td><?php echo esc_html( $t->slug ); ?></td>
						<td><?php echo esc_html( $t->count ); ?></td>
						<td><?php esc_html_e( 'نعم', 'stationary-theme' ); ?></td>
						<td><a href="<?php echo esc_url( get_edit_term_link( $t->term_id, 'product_cat' ) ); ?>"><?php esc_html_e( 'تعديل', 'stationary-theme' ); ?></a></td>
						<td>
							<form method="post" style="display:inline" onsubmit="return confirm('<?php echo esc_js( __( 'حذف هذا التصنيف؟', 'stationary-theme' ) ); ?>');">
								<?php wp_nonce_field( 'stationary_admin_stationary_demo_products', 'stationary_admin_nonce' ); ?>
								<input type="hidden" name="stationary_demo_action" value="delete_term" />
								<input type="hidden" name="term_id" value="<?php echo esc_attr( $t->term_id ); ?>" />
								<button type="submit" class="button button-link-delete"><?php esc_html_e( 'حذف', 'stationary-theme' ); ?></button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>

	<h2><?php esc_html_e( 'المنتجات المنشأة', 'stationary-theme' ); ?></h2>
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th><?php esc_html_e( 'الصورة', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'الاسم', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'SKU', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'السعر', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'التصنيف', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'الحالة', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'تعديل', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'حذف', 'stationary-theme' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $demo_products ) ) : ?>
				<tr><td colspan="8"><?php esc_html_e( 'لا توجد منتجات ديمو.', 'stationary-theme' ); ?></td></tr>
			<?php else : ?>
				<?php foreach ( $demo_products as $post ) :
					$product = wc_get_product( $post->ID );
					if ( ! $product ) continue;
					$thumb = $product->get_image_id();
					$cats  = wp_get_object_terms( $post->ID, 'product_cat' );
					$cat_names = ! is_wp_error( $cats ) && ! empty( $cats ) ? wp_list_pluck( $cats, 'name' ) : array();
					?>
					<tr>
						<td><?php echo $product->get_image( array( 50, 50 ) ) ?: '—'; ?></td>
						<td><?php echo esc_html( $product->get_name() ); ?></td>
						<td><?php echo esc_html( $product->get_sku() ); ?></td>
						<td><?php echo wp_kses_post( $product->get_price_html() ); ?></td>
						<td><?php echo esc_html( implode( ', ', $cat_names ) ); ?></td>
						<td><?php echo esc_html( $product->get_status() ); ?></td>
						<td><a href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>"><?php esc_html_e( 'تعديل', 'stationary-theme' ); ?></a></td>
						<td>
							<form method="post" style="display:inline" onsubmit="return confirm('<?php echo esc_js( __( 'حذف هذا المنتج؟', 'stationary-theme' ) ); ?>');">
								<?php wp_nonce_field( 'stationary_admin_stationary_demo_products', 'stationary_admin_nonce' ); ?>
								<input type="hidden" name="stationary_demo_action" value="delete_product" />
								<input type="hidden" name="product_id" value="<?php echo esc_attr( $post->ID ); ?>" />
								<button type="submit" class="button button-link-delete"><?php esc_html_e( 'حذف', 'stationary-theme' ); ?></button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<?php
}
