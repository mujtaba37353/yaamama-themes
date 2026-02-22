<?php
/**
 * Beauty Care Theme — منتجات وتصنيفات ديمو.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'BEAUTY_CARE_DEMO_META', '_beauty_care_demo' );
define( 'BEAUTY_CARE_DEMO_TERM_META', '_beauty_care_demo' );

function beauty_care_demo_categories() {
	return array(
		'skin-care'        => 'العناية بالبشرة',
		'hair-care'        => 'العناية بالشعر',
		'body-care'        => 'العناية بالجسم',
		'natural-products' => 'منتجات طبيعية',
	);
}

function beauty_care_demo_products() {
	return array(
		array( 'title' => 'ماسك الطمي', 'price' => '30', 'img' => 'pro1.jpg', 'cats' => array( 'skin-care', 'hair-care', 'body-care', 'natural-products' ) ),
		array( 'title' => 'سيرم فيتامين سي', 'price' => '50', 'img' => 'pro2.jpg', 'cats' => array( 'skin-care', 'hair-care', 'body-care', 'natural-products' ) ),
		array( 'title' => 'تونر مرطب', 'price' => '70', 'img' => 'pro3.jpg', 'cats' => array( 'skin-care', 'hair-care', 'body-care', 'natural-products' ) ),
	);
}

function beauty_care_sideload_demo_image( $filename ) {
	$dir  = get_template_directory() . '/beauty-care/assets/';
	$path = $dir . $filename;
	if ( ! file_exists( $path ) || ! function_exists( 'wp_generate_attachment_metadata' ) ) {
		return 0;
	}
	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$upload_dir = wp_upload_dir();
	$dest       = $upload_dir['path'] . '/' . $filename;
	if ( ! wp_mkdir_p( $upload_dir['path'] ) ) {
		$dest = $upload_dir['basedir'] . '/' . $filename;
	}
	if ( ! copy( $path, $dest ) ) {
		return 0;
	}
	$file_type  = wp_check_filetype( $filename, null );
	$attachment = array(
		'post_mime_type' => $file_type['type'],
		'post_title'     => sanitize_file_name( pathinfo( $filename, PATHINFO_FILENAME ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);
	$attach_id  = wp_insert_attachment( $attachment, $dest );
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

function beauty_care_get_or_create_demo_category( $slug, $name ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	if ( $term && ! is_wp_error( $term ) ) {
		update_term_meta( $term->term_id, BEAUTY_CARE_DEMO_TERM_META, 1 );
		return array( 'term_id' => $term->term_id, 'is_new' => false );
	}
	$insert = wp_insert_term( $name, 'product_cat', array( 'slug' => $slug ) );
	if ( is_wp_error( $insert ) ) {
		return array( 'term_id' => 0, 'is_new' => false );
	}
	update_term_meta( $insert['term_id'], BEAUTY_CARE_DEMO_TERM_META, 1 );
	return array( 'term_id' => $insert['term_id'], 'is_new' => true );
}

function beauty_care_register_demo_products_admin() {
	add_submenu_page(
		'beauty-care-content',
		'منتجات ديمو',
		'منتجات ديمو',
		'manage_options',
		'beauty-care-demo-products',
		'beauty_care_render_demo_products_admin'
	);
}
add_action( 'admin_menu', 'beauty_care_register_demo_products_admin', 11 );

function beauty_care_render_demo_products_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( isset( $_GET['beauty_care_demo_created'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>تم إنشاء منتجات الديمو بنجاح.</p></div>';
	}
	if ( isset( $_GET['beauty_care_demo_deleted'] ) ) {
		echo '<div class="notice notice-success is-dismissible"><p>تم حذف منتجات الديمو.</p></div>';
	}
	if ( isset( $_GET['beauty_care_demo_error'] ) ) {
		$msg = isset( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : 'حدث خطأ.';
		echo '<div class="notice notice-error is-dismissible"><p>' . esc_html( $msg ) . '</p></div>';
	}
	?>
	<div class="wrap">
		<h1>منتجات ديمو</h1>
		<p>إنشاء أو حذف المنتجات والتصنيفات التجريبية من التصميم.</p>
		<p><strong>التصنيفات:</strong> العناية بالبشرة، العناية بالشعر، العناية بالجسم، منتجات طبيعية.</p>
		<p><strong>المنتجات:</strong> ماسك الطمي (30 ر.س)، سيرم فيتامين سي (50 ر.س)، تونر مرطب (70 ر.س) — كلها تُربط بجميع التصنيفات.</p>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin: 1em 0; display: inline-block;">
			<?php wp_nonce_field( 'beauty_care_create_demo', 'beauty_care_demo_nonce' ); ?>
			<input type="hidden" name="action" value="beauty_care_create_demo">
			<?php submit_button( 'إنشاء منتجات ديمو', 'primary' ); ?>
		</form>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin: 1em 0; display: inline-block;" onsubmit="return confirm('حذف جميع منتجات وتصنيفات الديمو؟');">
			<?php wp_nonce_field( 'beauty_care_delete_demo', 'beauty_care_demo_nonce' ); ?>
			<input type="hidden" name="action" value="beauty_care_delete_demo">
			<?php submit_button( 'حذف منتجات ديمو', 'secondary', 'submit', false ); ?>
		</form>

		<?php
		$demo_products = array();
		$demo_cats     = array();
		if ( class_exists( 'WooCommerce' ) ) {
			$pq = new WP_Query( array(
				'post_type'      => 'product',
				'posts_per_page' => -1,
				'post_status'    => 'any',
				'meta_query'     => array( array( 'key' => BEAUTY_CARE_DEMO_META, 'value' => '1' ) ),
			) );
			foreach ( $pq->posts as $p ) {
				$product = wc_get_product( $p->ID );
				$demo_products[] = array(
					'id'    => $p->ID,
					'name'  => $p->post_title,
					'price' => $product ? $product->get_price() : '',
					'edit'  => get_edit_post_link( $p->ID, 'raw' ),
				);
			}
			$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
			foreach ( $terms as $t ) {
				if ( (int) get_term_meta( $t->term_id, BEAUTY_CARE_DEMO_TERM_META, true ) ) {
					$demo_cats[] = array(
						'id'   => $t->term_id,
						'name' => $t->name,
						'slug' => $t->slug,
						'edit' => get_edit_term_link( $t->term_id, 'product_cat' ),
					);
				}
			}
		}
		?>
		<h2>التصنيفات المضافة</h2>
		<?php if ( empty( $demo_cats ) ) : ?>
			<p>لا توجد تصنيفات ديمو.</p>
		<?php else : ?>
			<table class="widefat fixed striped" style="max-width:600px;">
				<thead>
					<tr>
						<th>الاسم</th>
						<th>المسار</th>
						<th>رابط التعديل</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $demo_cats as $c ) : ?>
						<tr>
							<td><?php echo esc_html( $c['name'] ); ?></td>
							<td><?php echo esc_html( $c['slug'] ); ?></td>
							<td><?php if ( ! empty( $c['edit'] ) ) : ?><a href="<?php echo esc_url( $c['edit'] ); ?>">تعديل</a><?php endif; ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>

		<h2>المنتجات المضافة</h2>
		<?php if ( empty( $demo_products ) ) : ?>
			<p>لا توجد منتجات ديمو.</p>
		<?php else : ?>
			<table class="widefat fixed striped" style="max-width:800px;">
				<thead>
					<tr>
						<th>الاسم</th>
						<th>السعر</th>
						<th>رابط التعديل</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $demo_products as $prod ) : ?>
						<tr>
							<td><?php echo esc_html( $prod['name'] ); ?></td>
							<td><?php echo esc_html( $prod['price'] ); ?> ر.س</td>
							<td>
								<?php if ( $prod['edit'] ) : ?>
									<a href="<?php echo esc_url( $prod['edit'] ); ?>">تعديل</a>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		<?php endif; ?>
	</div>
	<?php
}

function beauty_care_create_demo_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'beauty_care_create_demo', 'beauty_care_demo_nonce' );
	if ( ! class_exists( 'WooCommerce' ) ) {
		wp_safe_redirect( add_query_arg( array( 'beauty_care_demo_error' => '1', 'message' => urlencode( 'WooCommerce غير مفعّل' ) ), admin_url( 'admin.php?page=beauty-care-demo-products' ) ) );
		exit;
	}
	$cats   = beauty_care_demo_categories();
	$prods  = beauty_care_demo_products();
	$dir    = get_template_directory() . '/beauty-care/assets/';
	$created = array();
	foreach ( $cats as $slug => $name ) {
		beauty_care_get_or_create_demo_category( $slug, $name );
	}
	foreach ( $prods as $p ) {
		$img_path = $dir . $p['img'];
		$attach_id = 0;
		if ( file_exists( $img_path ) ) {
			$attach_id = beauty_care_sideload_demo_image( $p['img'] );
		}
		$term_ids = array();
		$cats = isset( $p['cats'] ) ? $p['cats'] : ( isset( $p['cat'] ) ? array( $p['cat'] ) : array() );
		foreach ( $cats as $slug ) {
			$term = get_term_by( 'slug', $slug, 'product_cat' );
			if ( $term && ! is_wp_error( $term ) ) {
				$term_ids[] = $term->term_id;
			}
		}
		$product = new WC_Product_Simple();
		$product->set_name( $p['title'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_price( $p['price'] );
		$product->set_regular_price( $p['price'] );
		$product->set_short_description( 'منتج تجريبي' );
		if ( $attach_id ) {
			$product->set_image_id( $attach_id );
		}
		$product_id = $product->save();
		if ( $product_id ) {
			update_post_meta( $product_id, BEAUTY_CARE_DEMO_META, 1 );
			if ( ! empty( $term_ids ) ) {
				wp_set_object_terms( $product_id, $term_ids, 'product_cat' );
			}
			$created[] = $product_id;
		}
	}
	wp_safe_redirect( add_query_arg( 'beauty_care_demo_created', '1', admin_url( 'admin.php?page=beauty-care-demo-products' ) ) );
	exit;
}
add_action( 'admin_post_beauty_care_create_demo', 'beauty_care_create_demo_handler' );

function beauty_care_delete_demo_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'beauty_care_delete_demo', 'beauty_care_demo_nonce' );
	$query = new WP_Query( array(
		'post_type'      => 'product',
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'fields'         => 'ids',
		'meta_query'     => array( array( 'key' => BEAUTY_CARE_DEMO_META, 'value' => '1' ) ),
	) );
	foreach ( $query->posts as $pid ) {
		wp_delete_post( $pid, true );
	}
	$terms = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false ) );
	foreach ( $terms as $t ) {
		if ( (int) get_term_meta( $t->term_id, BEAUTY_CARE_DEMO_TERM_META, true ) ) {
			wp_delete_term( $t->term_id, 'product_cat' );
		}
	}
	wp_safe_redirect( add_query_arg( 'beauty_care_demo_deleted', '1', admin_url( 'admin.php?page=beauty-care-demo-products' ) ) );
	exit;
}
add_action( 'admin_post_beauty_care_delete_demo', 'beauty_care_delete_demo_handler' );
