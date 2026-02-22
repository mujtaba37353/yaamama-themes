<?php
/**
 * Khutaa Theme Functions
 *
 * @package KhutaaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Include Theme Customizer
require get_template_directory() . '/inc/theme-customizer.php';

/**
 * Add Demo Content menu page in Appearance menu
 */
function khutaa_add_demo_content_menu() {
	add_theme_page(
		__( 'المحتوى الديمو', 'khutaa-theme' ),
		__( 'المحتوى الديمو', 'khutaa-theme' ),
		'edit_theme_options',
		'khutaa-demo-content',
		'khutaa_demo_content_page'
	);
}
add_action( 'admin_menu', 'khutaa_add_demo_content_menu' );

/**
 * Add Required Pages menu page in Appearance menu
 */
function khutaa_add_required_pages_menu() {
	add_theme_page(
		__( 'الصفحات المطلوبة', 'khutaa-theme' ),
		__( 'الصفحات', 'khutaa-theme' ),
		'edit_theme_options',
		'khutaa-required-pages',
		'khutaa_required_pages_page'
	);
}
add_action( 'admin_menu', 'khutaa_add_required_pages_menu' );

/**
 * Handle form submission for demo content
 */
function khutaa_save_demo_content() {
	if ( ! isset( $_POST['khutaa_demo_content_save'] ) || ! check_admin_referer( 'khutaa_demo_content_nonce', 'khutaa_demo_content_nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( __( 'غير مصرح لك بهذا الإجراء', 'khutaa-theme' ) );
	}

	// Save hero section
	if ( isset( $_POST['khutaa_hero_image'] ) ) {
		set_theme_mod( 'khutaa_hero_image', esc_url_raw( $_POST['khutaa_hero_image'] ) );
	}
	if ( isset( $_POST['khutaa_hero_title'] ) ) {
		set_theme_mod( 'khutaa_hero_title', sanitize_text_field( $_POST['khutaa_hero_title'] ) );
	}
	if ( isset( $_POST['khutaa_hero_content'] ) ) {
		set_theme_mod( 'khutaa_hero_content', sanitize_textarea_field( $_POST['khutaa_hero_content'] ) );
	}

	// Save banners
	if ( isset( $_POST['khutaa_banner_1_image'] ) ) {
		set_theme_mod( 'khutaa_banner_1_image', esc_url_raw( $_POST['khutaa_banner_1_image'] ) );
	}
	if ( isset( $_POST['khutaa_banner_1_title'] ) ) {
		set_theme_mod( 'khutaa_banner_1_title', sanitize_text_field( $_POST['khutaa_banner_1_title'] ) );
	}
	if ( isset( $_POST['khutaa_banner_1_link'] ) ) {
		set_theme_mod( 'khutaa_banner_1_link', esc_url_raw( $_POST['khutaa_banner_1_link'] ) );
	}

	if ( isset( $_POST['khutaa_banner_2_image'] ) ) {
		set_theme_mod( 'khutaa_banner_2_image', esc_url_raw( $_POST['khutaa_banner_2_image'] ) );
	}
	if ( isset( $_POST['khutaa_banner_2_title'] ) ) {
		set_theme_mod( 'khutaa_banner_2_title', sanitize_text_field( $_POST['khutaa_banner_2_title'] ) );
	}
	if ( isset( $_POST['khutaa_banner_2_link'] ) ) {
		set_theme_mod( 'khutaa_banner_2_link', esc_url_raw( $_POST['khutaa_banner_2_link'] ) );
	}

	// Save demo products - Shoes
	for ( $i = 1; $i <= 6; $i++ ) {
		if ( isset( $_POST["khutaa_shoes_{$i}_image"] ) ) {
			set_theme_mod( "khutaa_shoes_{$i}_image", esc_url_raw( $_POST["khutaa_shoes_{$i}_image"] ) );
		}
		if ( isset( $_POST["khutaa_shoes_{$i}_title"] ) ) {
			set_theme_mod( "khutaa_shoes_{$i}_title", sanitize_text_field( $_POST["khutaa_shoes_{$i}_title"] ) );
		}
		if ( isset( $_POST["khutaa_shoes_{$i}_price"] ) ) {
			set_theme_mod( "khutaa_shoes_{$i}_price", sanitize_text_field( $_POST["khutaa_shoes_{$i}_price"] ) );
		}
	}

	// Save demo products - Bags
	for ( $i = 1; $i <= 6; $i++ ) {
		if ( isset( $_POST["khutaa_bags_{$i}_image"] ) ) {
			set_theme_mod( "khutaa_bags_{$i}_image", esc_url_raw( $_POST["khutaa_bags_{$i}_image"] ) );
		}
		if ( isset( $_POST["khutaa_bags_{$i}_title"] ) ) {
			set_theme_mod( "khutaa_bags_{$i}_title", sanitize_text_field( $_POST["khutaa_bags_{$i}_title"] ) );
		}
		if ( isset( $_POST["khutaa_bags_{$i}_price"] ) ) {
			set_theme_mod( "khutaa_bags_{$i}_price", sanitize_text_field( $_POST["khutaa_bags_{$i}_price"] ) );
		}
	}

	// Create/update WooCommerce products from demo content
	khutaa_create_demo_products();

	add_settings_error( 'khutaa_demo_content', 'settings_updated', __( 'تم حفظ المحتوى الديمو وإنشاء/تحديث المنتجات بنجاح!', 'khutaa-theme' ), 'success' );
}
add_action( 'admin_init', 'khutaa_save_demo_content' );

/**
 * Create or update WooCommerce demo products from theme mods
 */
function khutaa_create_demo_products() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	// Ensure product categories exist
	$shoes_cat = khutaa_get_or_create_category( 'shoes', 'الأحذية' );
	$bags_cat = khutaa_get_or_create_category( 'bags', 'الشنط' );

	// Create/update shoes products
	for ( $i = 1; $i <= 6; $i++ ) {
		$image = khutaa_get_demo_content( "khutaa_shoes_{$i}_image" );
		$title = khutaa_get_demo_content( "khutaa_shoes_{$i}_title" );
		$price = khutaa_get_demo_content( "khutaa_shoes_{$i}_price" );

		// For products 4, 5, 6, use images from products 1, 2, 3 if not set
		if ( empty( $image ) && $i > 3 ) {
			$base_index = ( ( $i - 1 ) % 3 ) + 1;
			$image = khutaa_get_demo_content( "khutaa_shoes_{$base_index}_image" );
		}

		// If title or price not set, use defaults based on base product
		if ( empty( $title ) && $i > 3 ) {
			$base_index = ( ( $i - 1 ) % 3 ) + 1;
			$base_title = khutaa_get_demo_content( "khutaa_shoes_{$base_index}_title" );
			$title = $base_title ? $base_title . ' ' . __( 'نسخة', 'khutaa-theme' ) : '';
		}
		if ( empty( $price ) && $i > 3 ) {
			$base_index = ( ( $i - 1 ) % 3 ) + 1;
			$price = khutaa_get_demo_content( "khutaa_shoes_{$base_index}_price" );
		}

		if ( ! empty( $title ) && ! empty( $price ) ) {
			khutaa_create_or_update_product(
				'demo_shoes_' . $i,
				$title,
				$price,
				$image,
				$shoes_cat
			);
		}
	}

	// Create/update bags products
	for ( $i = 1; $i <= 6; $i++ ) {
		$image = khutaa_get_demo_content( "khutaa_bags_{$i}_image" );
		$title = khutaa_get_demo_content( "khutaa_bags_{$i}_title" );
		$price = khutaa_get_demo_content( "khutaa_bags_{$i}_price" );

		// For products 4, 5, 6, use images from products 1, 2, 3 if not set
		if ( empty( $image ) && $i > 3 ) {
			$base_index = ( ( $i - 1 ) % 3 ) + 1;
			$image = khutaa_get_demo_content( "khutaa_bags_{$base_index}_image" );
		}

		// If title or price not set, use defaults based on base product
		if ( empty( $title ) && $i > 3 ) {
			$base_index = ( ( $i - 1 ) % 3 ) + 1;
			$base_title = khutaa_get_demo_content( "khutaa_bags_{$base_index}_title" );
			$title = $base_title ? $base_title . ' ' . __( 'نسخة', 'khutaa-theme' ) : '';
		}
		if ( empty( $price ) && $i > 3 ) {
			$base_index = ( ( $i - 1 ) % 3 ) + 1;
			$price = khutaa_get_demo_content( "khutaa_bags_{$base_index}_price" );
		}

		if ( ! empty( $title ) && ! empty( $price ) ) {
			khutaa_create_or_update_product(
				'demo_bags_' . $i,
				$title,
				$price,
				$image,
				$bags_cat
			);
		}
	}
}

/**
 * Get or create a product category
 */
function khutaa_get_or_create_category( $slug, $name ) {
	$term = get_term_by( 'slug', $slug, 'product_cat' );
	
	if ( ! $term ) {
		$term_data = wp_insert_term(
			$name,
			'product_cat',
			array(
				'slug' => $slug,
			)
		);
		
		if ( ! is_wp_error( $term_data ) ) {
			$term = get_term( $term_data['term_id'], 'product_cat' );
		}
	}
	
	return $term ? $term->term_id : null;
}

/**
 * Create or update a WooCommerce product
 */
function khutaa_create_or_update_product( $meta_key, $title, $price, $image_url, $category_id = null ) {
	// Check if product already exists by meta key
	$existing_products = wc_get_products( array(
		'meta_key'   => '_khutaa_demo_product_key',
		'meta_value' => $meta_key,
		'limit'      => 1,
	) );

	$product = null;
	if ( ! empty( $existing_products ) ) {
		$product = $existing_products[0];
	} else {
		// Create new product
		$product = new WC_Product_Simple();
		$product->set_status( 'publish' );
	}

	// Set product data
	$product->set_name( $title );
	$product->set_regular_price( $price );
	$product->set_price( $price );
	$product->set_manage_stock( false );
	$product->set_stock_status( 'instock' );
	
	// Set meta to identify demo products
	$product->update_meta_data( '_khutaa_demo_product_key', $meta_key );

	// Set product category
	if ( $category_id ) {
		$product->set_category_ids( array( $category_id ) );
	}

	// Handle product image
	if ( ! empty( $image_url ) ) {
		$image_id = khutaa_get_attachment_id_from_url( $image_url );
		
		// If image not found in media library, try to import it
		if ( ! $image_id ) {
			$image_id = khutaa_import_image_from_url( $image_url );
		}
		
		if ( $image_id ) {
			$product->set_image_id( $image_id );
		}
	}

	// Save product
	$product_id = $product->save();

	return $product_id;
}

/**
 * Get attachment ID from URL
 */
function khutaa_get_attachment_id_from_url( $url ) {
	global $wpdb;
	
	// Remove query string
	$url = preg_replace( '/\?.*/', '', $url );
	
	// Extract filename
	$filename = basename( $url );
	
	// Try to find by filename in attachment metadata
	$attachment = $wpdb->get_col( $wpdb->prepare(
		"SELECT ID FROM {$wpdb->posts} 
		WHERE post_type = 'attachment' 
		AND guid LIKE %s 
		LIMIT 1",
		'%' . $wpdb->esc_like( $filename ) . '%'
	) );

	if ( ! empty( $attachment ) ) {
		return $attachment[0];
	}

	// Try by post meta _wp_attached_file
	$attachment = $wpdb->get_col( $wpdb->prepare(
		"SELECT post_id FROM {$wpdb->postmeta} 
		WHERE meta_key = '_wp_attached_file' 
		AND meta_value LIKE %s 
		LIMIT 1",
		'%' . $wpdb->esc_like( $filename ) . '%'
	) );

	return ! empty( $attachment ) ? $attachment[0] : null;
}

/**
 * Import image from URL to media library
 */
function khutaa_import_image_from_url( $url ) {
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/image.php' );

	// Check if URL is local (theme directory)
	$site_url = site_url();
	$theme_uri = get_template_directory_uri();
	
	// If it's a theme URL, convert to path
	if ( strpos( $url, $theme_uri ) === 0 ) {
		$theme_path = get_template_directory();
		$url_path = str_replace( $theme_uri, $theme_path, $url );
		
		if ( file_exists( $url_path ) ) {
			// Copy file to uploads directory
			$upload_dir = wp_upload_dir();
			$filename = basename( $url_path );
			$dest_path = $upload_dir['path'] . '/' . $filename;
			
			// Generate unique filename if exists
			if ( file_exists( $dest_path ) ) {
				$info = pathinfo( $filename );
				$filename = $info['filename'] . '-' . time() . '.' . $info['extension'];
				$dest_path = $upload_dir['path'] . '/' . $filename;
			}
			
			if ( copy( $url_path, $dest_path ) ) {
				$file_array = array(
					'name'     => $filename,
					'tmp_name' => $dest_path,
				);
				
				$id = media_handle_sideload( $file_array, 0 );
				
				// Clean up temp file
				if ( file_exists( $dest_path ) && $dest_path !== get_attached_file( $id ) ) {
					@unlink( $dest_path );
				}
				
				return is_wp_error( $id ) ? null : $id;
			}
		}
	}
	
	// For external URLs, use download_url
	if ( filter_var( $url, FILTER_VALIDATE_URL ) && strpos( $url, $site_url ) !== 0 ) {
		$tmp = download_url( $url );
		
		if ( is_wp_error( $tmp ) ) {
			return null;
		}

		$file_array = array(
			'name'     => basename( $url ),
			'tmp_name' => $tmp,
		);

		$id = media_handle_sideload( $file_array, 0 );

		if ( is_wp_error( $id ) ) {
			@unlink( $tmp );
			return null;
		}

		return $id;
	}

	return null;
}

/**
 * Handle reset demo content
 */
function khutaa_reset_demo_content_handler() {
	if ( ! isset( $_POST['khutaa_reset_demo_content'] ) || ! check_admin_referer( 'khutaa_reset_demo_nonce', 'khutaa_reset_demo_nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( __( 'غير مصرح لك بهذا الإجراء', 'khutaa-theme' ) );
	}

	$defaults = khutaa_get_default_demo_content();
	foreach ( $defaults as $key => $value ) {
		set_theme_mod( $key, $value );
	}

	// Recreate products with default values
	khutaa_create_demo_products();

	add_settings_error( 'khutaa_demo_content', 'settings_reset', __( 'تم إعادة تعيين المحتوى الديمو وإنشاء/تحديث المنتجات بنجاح!', 'khutaa-theme' ), 'success' );
}
add_action( 'admin_init', 'khutaa_reset_demo_content_handler' );

/**
 * Required Pages admin page callback
 */
function khutaa_required_pages_page() {
	// Handle page creation
	if ( isset( $_POST['khutaa_create_pages'] ) && check_admin_referer( 'khutaa_create_pages_nonce', 'khutaa_create_pages_nonce' ) ) {
		if ( ! current_user_can( 'edit_theme_options' ) ) {
			wp_die( __( 'غير مصرح لك بهذا الإجراء', 'khutaa-theme' ) );
		}

		$pages_to_create = khutaa_get_required_pages();
		$created_count = 0;
		$updated_count = 0;

		foreach ( $pages_to_create as $page_slug => $page_data ) {
			$existing_page = get_page_by_path( $page_slug );
			
			if ( $existing_page ) {
				// Update existing page
				wp_update_post( array(
					'ID'           => $existing_page->ID,
					'post_title'   => $page_data['title'],
					'post_content' => $page_data['content'],
					'post_status'  => 'publish',
				) );
				$updated_count++;
			} else {
				// Create new page
				$page_id = wp_insert_post( array(
					'post_title'   => $page_data['title'],
					'post_name'    => $page_slug,
					'post_content' => $page_data['content'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_author'  => get_current_user_id(),
				) );
				
				if ( $page_id && ! is_wp_error( $page_id ) ) {
					$created_count++;
				}
			}
		}

		if ( $created_count > 0 || $updated_count > 0 ) {
			add_settings_error( 'khutaa_required_pages', 'pages_created', 
				sprintf( 
					__( 'تم إنشاء %d صفحة وتحديث %d صفحة بنجاح!', 'khutaa-theme' ), 
					$created_count, 
					$updated_count 
				), 
				'success' 
			);
		}
	}

	settings_errors( 'khutaa_required_pages' );
	
	$required_pages = khutaa_get_required_pages();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'الصفحات المطلوبة', 'khutaa-theme' ); ?></h1>
		<p class="description">
			<?php esc_html_e( 'هذه قائمة بجميع الصفحات المطلوبة للثيم مع slug لكل صفحة. يمكنك إنشاء جميع الصفحات دفعة واحدة.', 'khutaa-theme' ); ?>
		</p>

		<form method="post" action="">
			<?php wp_nonce_field( 'khutaa_create_pages_nonce', 'khutaa_create_pages_nonce' ); ?>
			
			<table class="wp-list-table widefat fixed striped">
				<thead>
					<tr>
						<th scope="col" style="width: 5%;"><?php esc_html_e( '#', 'khutaa-theme' ); ?></th>
						<th scope="col" style="width: 25%;"><?php esc_html_e( 'اسم الصفحة', 'khutaa-theme' ); ?></th>
						<th scope="col" style="width: 20%;"><?php esc_html_e( 'Slug', 'khutaa-theme' ); ?></th>
						<th scope="col" style="width: 15%;"><?php esc_html_e( 'الحالة', 'khutaa-theme' ); ?></th>
						<th scope="col" style="width: 35%;"><?php esc_html_e( 'الوصف', 'khutaa-theme' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$counter = 1;
					foreach ( $required_pages as $slug => $page_data ) : 
						$existing_page = get_page_by_path( $slug );
						$status = $existing_page ? __( 'موجودة', 'khutaa-theme' ) : __( 'غير موجودة', 'khutaa-theme' );
						$status_class = $existing_page ? 'status-success' : 'status-warning';
					?>
					<tr>
						<td><?php echo esc_html( $counter ); ?></td>
						<td><strong><?php echo esc_html( $page_data['title'] ); ?></strong></td>
						<td>
							<code style="background: #f0f0f1; padding: 2px 6px; border-radius: 3px;">
								<?php echo esc_html( $slug ); ?>
							</code>
						</td>
						<td>
							<span class="<?php echo esc_attr( $status_class ); ?>" style="padding: 3px 8px; border-radius: 3px; font-size: 12px; <?php echo $existing_page ? 'background: #d4edda; color: #155724;' : 'background: #fff3cd; color: #856404;'; ?>">
								<?php echo esc_html( $status ); ?>
							</span>
							<?php if ( $existing_page ) : ?>
								<br>
								<a href="<?php echo esc_url( get_edit_post_link( $existing_page->ID ) ); ?>" target="_blank" style="font-size: 12px; margin-top: 5px; display: inline-block;">
									<?php esc_html_e( 'تعديل', 'khutaa-theme' ); ?>
								</a>
								|
								<a href="<?php echo esc_url( get_permalink( $existing_page->ID ) ); ?>" target="_blank" style="font-size: 12px;">
									<?php esc_html_e( 'عرض', 'khutaa-theme' ); ?>
								</a>
							<?php endif; ?>
						</td>
						<td><?php echo esc_html( $page_data['description'] ); ?></td>
					</tr>
					<?php 
					$counter++;
					endforeach; 
					?>
				</tbody>
			</table>

			<p class="submit">
				<button type="submit" name="khutaa_create_pages" class="button button-primary button-large">
					<?php esc_html_e( 'إنشاء/تحديث جميع الصفحات', 'khutaa-theme' ); ?>
				</button>
			</p>
		</form>

		<div class="notice notice-info" style="margin-top: 20px;">
			<p>
				<strong><?php esc_html_e( 'ملاحظة:', 'khutaa-theme' ); ?></strong>
				<?php esc_html_e( 'سيتم إنشاء الصفحات غير الموجودة وتحديث الصفحات الموجودة. إذا كانت الصفحة موجودة بالفعل، سيتم تحديث عنوانها ومحتواها فقط.', 'khutaa-theme' ); ?>
			</p>
		</div>
	</div>

	<style>
		.status-success {
			display: inline-block;
		}
		.status-warning {
			display: inline-block;
		}
		.wp-list-table code {
			font-family: 'Courier New', monospace;
		}
	</style>
	<?php
}

/**
 * Get required pages list with slugs
 */
function khutaa_get_required_pages() {
	return array(
		'login' => array(
			'title'       => __( 'تسجيل الدخول', 'khutaa-theme' ),
			'slug'        => 'login',
			'description' => __( 'صفحة تسجيل الدخول للمستخدمين', 'khutaa-theme' ),
			'content'     => '',
		),
		'register' => array(
			'title'       => __( 'إنشاء حساب', 'khutaa-theme' ),
			'slug'        => 'register',
			'description' => __( 'صفحة إنشاء حساب جديد', 'khutaa-theme' ),
			'content'     => '',
		),
		'lost-password' => array(
			'title'       => __( 'استعادة كلمة المرور', 'khutaa-theme' ),
			'slug'        => 'lost-password',
			'description' => __( 'صفحة استعادة كلمة المرور', 'khutaa-theme' ),
			'content'     => '',
		),
		'my-account' => array(
			'title'       => __( 'حسابي', 'khutaa-theme' ),
			'slug'        => 'my-account',
			'description' => __( 'صفحة حساب المستخدم (WooCommerce)', 'khutaa-theme' ),
			'content'     => '[woocommerce_my_account]',
		),
		'cart' => array(
			'title'       => __( 'سلة التسوق', 'khutaa-theme' ),
			'slug'        => 'cart',
			'description' => __( 'صفحة سلة التسوق (WooCommerce)', 'khutaa-theme' ),
			'content'     => '[woocommerce_cart]',
		),
		'checkout' => array(
			'title'       => __( 'الدفع', 'khutaa-theme' ),
			'slug'        => 'checkout',
			'description' => __( 'صفحة الدفع (WooCommerce)', 'khutaa-theme' ),
			'content'     => '[woocommerce_checkout]',
		),
		'wishlist' => array(
			'title'       => __( 'قائمة الأمنيات', 'khutaa-theme' ),
			'slug'        => 'wishlist',
			'description' => __( 'صفحة قائمة الأمنيات', 'khutaa-theme' ),
			'content'     => '',
		),
		'contact' => array(
			'title'       => __( 'اتصل بنا', 'khutaa-theme' ),
			'slug'        => 'contact',
			'description' => __( 'صفحة اتصل بنا', 'khutaa-theme' ),
			'content'     => '',
		),
		'contact-us' => array(
			'title'       => __( 'اتصل بنا', 'khutaa-theme' ),
			'slug'        => 'contact-us',
			'description' => __( 'صفحة اتصل بنا (بديل)', 'khutaa-theme' ),
			'content'     => '',
		),
		'offers' => array(
			'title'       => __( 'العروض', 'khutaa-theme' ),
			'slug'        => 'offers',
			'description' => __( 'صفحة العروض الخاصة', 'khutaa-theme' ),
			'content'     => '',
		),
		'privacy-policy' => array(
			'title'       => __( 'سياسة الخصوصية', 'khutaa-theme' ),
			'slug'        => 'privacy-policy',
			'description' => __( 'صفحة سياسة الخصوصية', 'khutaa-theme' ),
			'content'     => '',
		),
		'refund_returns' => array(
			'title'       => __( 'سياسة الاسترجاع', 'khutaa-theme' ),
			'slug'        => 'refund_returns',
			'description' => __( 'صفحة سياسة الاسترجاع والاسترداد', 'khutaa-theme' ),
			'content'     => '',
		),
	);
}

/**
 * Demo Content admin page callback
 */
function khutaa_demo_content_page() {
	// Enqueue media uploader scripts
	wp_enqueue_media();
	
	// Get current values
	$address = khutaa_get_demo_content( 'khutaa_address' );
	$phone = khutaa_get_demo_content( 'khutaa_phone' );
	$email = khutaa_get_demo_content( 'khutaa_email' );
	$hero_image = khutaa_get_demo_content( 'khutaa_hero_image' );
	$hero_title = khutaa_get_demo_content( 'khutaa_hero_title' );
	$hero_content = khutaa_get_demo_content( 'khutaa_hero_content' );
	$banner_1_image = khutaa_get_demo_content( 'khutaa_banner_1_image' );
	$banner_1_title = khutaa_get_demo_content( 'khutaa_banner_1_title' );
	$banner_1_link = khutaa_get_demo_content( 'khutaa_banner_1_link' );
	$banner_2_image = khutaa_get_demo_content( 'khutaa_banner_2_image' );
	$banner_2_title = khutaa_get_demo_content( 'khutaa_banner_2_title' );
	$banner_2_link = khutaa_get_demo_content( 'khutaa_banner_2_link' );
	
	settings_errors( 'khutaa_demo_content' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'المحتوى الديمو', 'khutaa-theme' ); ?></h1>
		
		<form method="post" action="">
			<?php wp_nonce_field( 'khutaa_demo_content_nonce', 'khutaa_demo_content_nonce' ); ?>
			
			<table class="form-table" role="presentation">
				<tbody>
					<!-- Hero Section -->
					<tr>
						<th colspan="2"><h2><?php esc_html_e( 'قسم الهيرو', 'khutaa-theme' ); ?></h2></th>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_hero_image"><?php esc_html_e( 'صورة الهيرو', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="text" id="khutaa_hero_image" name="khutaa_hero_image" value="<?php echo esc_attr( $hero_image ); ?>" class="regular-text" />
							<button type="button" class="button khutaa-upload-image" data-target="khutaa_hero_image"><?php esc_html_e( 'اختر صورة', 'khutaa-theme' ); ?></button>
							<?php if ( $hero_image ) : ?>
								<p><img src="<?php echo esc_url( $hero_image ); ?>" style="max-width: 300px; height: auto;" /></p>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_hero_title"><?php esc_html_e( 'عنوان الهيرو', 'khutaa-theme' ); ?></label></th>
						<td><input type="text" id="khutaa_hero_title" name="khutaa_hero_title" value="<?php echo esc_attr( $hero_title ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_hero_content"><?php esc_html_e( 'محتوى الهيرو', 'khutaa-theme' ); ?></label></th>
						<td><textarea id="khutaa_hero_content" name="khutaa_hero_content" rows="5" class="large-text"><?php echo esc_textarea( $hero_content ); ?></textarea></td>
					</tr>
					
					<!-- Banner 1 -->
					<tr>
						<th colspan="2"><h2><?php esc_html_e( 'البنر الأول', 'khutaa-theme' ); ?></h2></th>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_banner_1_image"><?php esc_html_e( 'صورة البنر', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="text" id="khutaa_banner_1_image" name="khutaa_banner_1_image" value="<?php echo esc_attr( $banner_1_image ); ?>" class="regular-text" />
							<button type="button" class="button khutaa-upload-image" data-target="khutaa_banner_1_image"><?php esc_html_e( 'اختر صورة', 'khutaa-theme' ); ?></button>
							<?php if ( $banner_1_image ) : ?>
								<p><img src="<?php echo esc_url( $banner_1_image ); ?>" style="max-width: 300px; height: auto;" /></p>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_banner_1_title"><?php esc_html_e( 'عنوان البنر', 'khutaa-theme' ); ?></label></th>
						<td><input type="text" id="khutaa_banner_1_title" name="khutaa_banner_1_title" value="<?php echo esc_attr( $banner_1_title ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_banner_1_link"><?php esc_html_e( 'رابط البنر', 'khutaa-theme' ); ?></label></th>
						<td><input type="url" id="khutaa_banner_1_link" name="khutaa_banner_1_link" value="<?php echo esc_attr( $banner_1_link ); ?>" class="regular-text" /></td>
					</tr>
					
					<!-- Banner 2 -->
					<tr>
						<th colspan="2"><h2><?php esc_html_e( 'البنر الثاني', 'khutaa-theme' ); ?></h2></th>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_banner_2_image"><?php esc_html_e( 'صورة البنر', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="text" id="khutaa_banner_2_image" name="khutaa_banner_2_image" value="<?php echo esc_attr( $banner_2_image ); ?>" class="regular-text" />
							<button type="button" class="button khutaa-upload-image" data-target="khutaa_banner_2_image"><?php esc_html_e( 'اختر صورة', 'khutaa-theme' ); ?></button>
							<?php if ( $banner_2_image ) : ?>
								<p><img src="<?php echo esc_url( $banner_2_image ); ?>" style="max-width: 300px; height: auto;" /></p>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_banner_2_title"><?php esc_html_e( 'عنوان البنر', 'khutaa-theme' ); ?></label></th>
						<td><input type="text" id="khutaa_banner_2_title" name="khutaa_banner_2_title" value="<?php echo esc_attr( $banner_2_title ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_banner_2_link"><?php esc_html_e( 'رابط البنر', 'khutaa-theme' ); ?></label></th>
						<td><input type="url" id="khutaa_banner_2_link" name="khutaa_banner_2_link" value="<?php echo esc_attr( $banner_2_link ); ?>" class="regular-text" /></td>
					</tr>
					
					<!-- Demo Products - Shoes -->
					<?php for ( $i = 1; $i <= 6; $i++ ) : 
						$shoes_image = khutaa_get_demo_content( "khutaa_shoes_{$i}_image" );
						$shoes_title = khutaa_get_demo_content( "khutaa_shoes_{$i}_title" );
						$shoes_price = khutaa_get_demo_content( "khutaa_shoes_{$i}_price" );
						
						// For products 4, 5, 6, pre-fill with data from 1, 2, 3 if empty
						if ( empty( $shoes_image ) && $i > 3 ) {
							$base_index = ( ( $i - 1 ) % 3 ) + 1;
							$shoes_image = khutaa_get_demo_content( "khutaa_shoes_{$base_index}_image" );
						}
						if ( empty( $shoes_title ) && $i > 3 ) {
							$base_index = ( ( $i - 1 ) % 3 ) + 1;
							$base_title = khutaa_get_demo_content( "khutaa_shoes_{$base_index}_title" );
							$shoes_title = $base_title ? $base_title . ' ' . __( 'نسخة', 'khutaa-theme' ) : '';
						}
						if ( empty( $shoes_price ) && $i > 3 ) {
							$base_index = ( ( $i - 1 ) % 3 ) + 1;
							$shoes_price = khutaa_get_demo_content( "khutaa_shoes_{$base_index}_price" );
						}
					?>
					<tr>
						<th colspan="2"><h2><?php echo sprintf( __( 'منتج ديمو أحذية %d', 'khutaa-theme' ), $i ); ?></h2></th>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_shoes_<?php echo $i; ?>_image"><?php esc_html_e( 'صورة المنتج', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="text" id="khutaa_shoes_<?php echo $i; ?>_image" name="khutaa_shoes_<?php echo $i; ?>_image" value="<?php echo esc_attr( $shoes_image ); ?>" class="regular-text" />
							<button type="button" class="button khutaa-upload-image" data-target="khutaa_shoes_<?php echo $i; ?>_image"><?php esc_html_e( 'اختر صورة', 'khutaa-theme' ); ?></button>
							<?php if ( $shoes_image ) : ?>
								<p><img src="<?php echo esc_url( $shoes_image ); ?>" style="max-width: 150px; height: auto;" /></p>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_shoes_<?php echo $i; ?>_title"><?php esc_html_e( 'اسم المنتج', 'khutaa-theme' ); ?></label></th>
						<td><input type="text" id="khutaa_shoes_<?php echo $i; ?>_title" name="khutaa_shoes_<?php echo $i; ?>_title" value="<?php echo esc_attr( $shoes_title ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_shoes_<?php echo $i; ?>_price"><?php esc_html_e( 'السعر', 'khutaa-theme' ); ?></label></th>
						<td><input type="text" id="khutaa_shoes_<?php echo $i; ?>_price" name="khutaa_shoes_<?php echo $i; ?>_price" value="<?php echo esc_attr( $shoes_price ); ?>" class="small-text" /> <?php echo get_woocommerce_currency_symbol(); ?></td>
					</tr>
					<?php endfor; ?>
					
					<!-- Demo Products - Bags -->
					<?php for ( $i = 1; $i <= 6; $i++ ) : 
						$bags_image = khutaa_get_demo_content( "khutaa_bags_{$i}_image" );
						$bags_title = khutaa_get_demo_content( "khutaa_bags_{$i}_title" );
						$bags_price = khutaa_get_demo_content( "khutaa_bags_{$i}_price" );
						
						// For products 4, 5, 6, pre-fill with data from 1, 2, 3 if empty
						if ( empty( $bags_image ) && $i > 3 ) {
							$base_index = ( ( $i - 1 ) % 3 ) + 1;
							$bags_image = khutaa_get_demo_content( "khutaa_bags_{$base_index}_image" );
						}
						if ( empty( $bags_title ) && $i > 3 ) {
							$base_index = ( ( $i - 1 ) % 3 ) + 1;
							$base_title = khutaa_get_demo_content( "khutaa_bags_{$base_index}_title" );
							$bags_title = $base_title ? $base_title . ' ' . __( 'نسخة', 'khutaa-theme' ) : '';
						}
						if ( empty( $bags_price ) && $i > 3 ) {
							$base_index = ( ( $i - 1 ) % 3 ) + 1;
							$bags_price = khutaa_get_demo_content( "khutaa_bags_{$base_index}_price" );
						}
					?>
					<tr>
						<th colspan="2"><h2><?php echo sprintf( __( 'منتج ديمو شنط %d', 'khutaa-theme' ), $i ); ?></h2></th>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_bags_<?php echo $i; ?>_image"><?php esc_html_e( 'صورة المنتج', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="text" id="khutaa_bags_<?php echo $i; ?>_image" name="khutaa_bags_<?php echo $i; ?>_image" value="<?php echo esc_attr( $bags_image ); ?>" class="regular-text" />
							<button type="button" class="button khutaa-upload-image" data-target="khutaa_bags_<?php echo $i; ?>_image"><?php esc_html_e( 'اختر صورة', 'khutaa-theme' ); ?></button>
							<?php if ( $bags_image ) : ?>
								<p><img src="<?php echo esc_url( $bags_image ); ?>" style="max-width: 150px; height: auto;" /></p>
							<?php endif; ?>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_bags_<?php echo $i; ?>_title"><?php esc_html_e( 'اسم المنتج', 'khutaa-theme' ); ?></label></th>
						<td><input type="text" id="khutaa_bags_<?php echo $i; ?>_title" name="khutaa_bags_<?php echo $i; ?>_title" value="<?php echo esc_attr( $bags_title ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_bags_<?php echo $i; ?>_price"><?php esc_html_e( 'السعر', 'khutaa-theme' ); ?></label></th>
						<td><input type="text" id="khutaa_bags_<?php echo $i; ?>_price" name="khutaa_bags_<?php echo $i; ?>_price" value="<?php echo esc_attr( $bags_price ); ?>" class="small-text" /> <?php echo get_woocommerce_currency_symbol(); ?></td>
					</tr>
					<?php endfor; ?>
				</tbody>
			</table>
			
			<?php submit_button( __( 'حفظ المحتوى الديمو', 'khutaa-theme' ), 'primary', 'khutaa_demo_content_save' ); ?>
		</form>
		
		<hr>
		
		<form method="post" action="" onsubmit="return confirm('<?php echo esc_js( __( 'هل أنت متأكد من إعادة تعيين جميع المحتوى الديمو؟ سيتم فقدان جميع التغييرات الحالية.', 'khutaa-theme' ) ); ?>');">
			<?php wp_nonce_field( 'khutaa_reset_demo_nonce', 'khutaa_reset_demo_nonce' ); ?>
			<h2><?php esc_html_e( 'إعادة تعيين المحتوى الديمو', 'khutaa-theme' ); ?></h2>
			<p class="description"><?php esc_html_e( 'سيتم إعادة جميع إعدادات المحتوى الديمو إلى القيم الافتراضية الأصلية.', 'khutaa-theme' ); ?></p>
			<?php submit_button( __( 'إعادة تعيين إلى القيم الافتراضية', 'khutaa-theme' ), 'secondary', 'khutaa_reset_demo_content' ); ?>
		</form>
	</div>
	
	<script>
	jQuery(document).ready(function($) {
		$('.khutaa-upload-image').on('click', function(e) {
			e.preventDefault();
			var button = $(this);
			var targetInput = $('#' + button.data('target'));
			
			var mediaUploader = wp.media({
				title: '<?php echo esc_js( __( 'اختر صورة', 'khutaa-theme' ) ); ?>',
				button: {
					text: '<?php echo esc_js( __( 'استخدم هذه الصورة', 'khutaa-theme' ) ); ?>'
				},
				multiple: false
			});
			
			mediaUploader.on('select', function() {
				var attachment = mediaUploader.state().get('selection').first().toJSON();
				targetInput.val(attachment.url);
				if (targetInput.closest('td').find('img').length) {
					targetInput.closest('td').find('img').attr('src', attachment.url);
				} else {
					targetInput.closest('td').append('<p><img src="' + attachment.url + '" style="max-width: 300px; height: auto;" /></p>');
				}
			});
			
			mediaUploader.open();
		});
	});
	</script>
	<?php
}

/**
 * Theme Setup
 */
function khutaa_theme_setup() {
	// Add theme support
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Add WooCommerce support
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

	// Register navigation menus
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'khutaa-theme' ),
	) );
}
add_action( 'after_setup_theme', 'khutaa_theme_setup' );

/**
 * Enqueue scripts and styles
 */
function khutaa_theme_scripts() {
	$theme_uri = get_template_directory_uri();
	$khutaa_uri = $theme_uri . '/khutaa';

	// Base CSS files
	wp_enqueue_style( 'khutaa-reset', $khutaa_uri . '/base/reset.css', array(), '1.0.0' );
	wp_enqueue_style( 'khutaa-tokens', $khutaa_uri . '/base/tokens.css', array(), '1.0.0' );
	wp_enqueue_style( 'khutaa-typography', $khutaa_uri . '/base/typography.css', array(), '1.0.0' );
	wp_enqueue_style( 'khutaa-utilities', $khutaa_uri . '/base/utilities.css', array(), '1.0.0' );

	// Layout components
	wp_enqueue_style( 'khutaa-navbar', $khutaa_uri . '/components/layout/y-c-navbar.css', array(), '1.0.0' );
	wp_enqueue_style( 'khutaa-footer', $khutaa_uri . '/components/layout/y-c-footer.css', array(), '1.0.0' );

	// Animations
	wp_enqueue_style( 'khutaa-animations', $khutaa_uri . '/css/y-animations.css', array(), '1.0.0' );

	// External styles
	wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', array(), '6.5.0' );
	wp_enqueue_style( 'google-fonts-cairo', 'https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap', array(), null );

	// JavaScript files
	wp_enqueue_script( 'khutaa-navbar', $khutaa_uri . '/js/y-navbar.js', array(), '1.0.0', true );
	
	// Add CSS to prevent swipe gestures and overscroll - ONLY on checkout page
	if ( is_checkout() ) {
		$prevent_swipe_css = "
			/* Prevent swipe gestures and overscroll ONLY on checkout page */
			body.woocommerce-checkout {
				overscroll-behavior: none !important;
				overscroll-behavior-x: none !important;
				overscroll-behavior-y: none !important;
				position: relative;
				overflow-x: hidden;
			}
			
			/* Prevent horizontal swipe on checkout, but exclude mobile menu */
			body.woocommerce-checkout *:not(.mobile-menu):not(.mobile-menu *):not(.mobile-menu-overlay) {
				touch-action: pan-y !important;
			}
		";
		wp_add_inline_style( 'khutaa-reset', $prevent_swipe_css );
	}
	wp_enqueue_script( 'khutaa-footer', $khutaa_uri . '/js/y-footer.js', array(), '1.0.0', true );

	// Add jQuery dependency if needed
	wp_enqueue_script( 'jquery' );
	
	// Enqueue wishlist script
	wp_enqueue_script( 'khutaa-wishlist', $khutaa_uri . '/js/khutaa-wishlist.js', array( 'jquery' ), '1.0.0', true );
	
	// Localize script for AJAX
	wp_localize_script( 'khutaa-wishlist', 'khutaaWishlist', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'khutaa_wishlist_nonce' ),
		'yith_available' => function_exists( 'yith_wcwl_get_wishlist_url' ) ? 'yes' : 'no',
	) );

	// My Account page styles and scripts
	if ( is_account_page() ) {
		wp_enqueue_style( 'khutaa-myaccount', $khutaa_uri . '/templates/my-account/my-account.css', array(), '1.0.0' );
		wp_enqueue_script( 'khutaa-myaccount', $khutaa_uri . '/js/y-my-account.js', array( 'jquery' ), '1.0.0', true );
	}
	
	// Password toggle script for auth pages
	$current_page = get_queried_object();
	$is_auth_page = false;
	if ( $current_page && isset( $current_page->post_name ) ) {
		$is_auth_page = in_array( $current_page->post_name, array( 'login', 'register', 'lost-password' ) );
	}
	if ( $is_auth_page || is_account_page() ) {
		$password_toggle_script = "
		(function() {
			function initPasswordToggle() {
				const toggleButtons = document.querySelectorAll('.toggle-password:not([data-initialized])');
				
				toggleButtons.forEach(button => {
					button.setAttribute('data-initialized', 'true');
					
					button.addEventListener('click', function(e) {
						e.preventDefault();
						e.stopPropagation();
						
						const wrapper = this.closest('.password-input-wrapper');
						const input = wrapper ? wrapper.querySelector('input[type=\"password\"], input[type=\"text\"]') : null;
						const icon = this.querySelector('i');
						
						if (input) {
							if (input.type === 'password') {
								input.type = 'text';
								this.classList.add('active');
								if (icon) {
									icon.classList.remove('fa-eye');
									icon.classList.add('fa-eye-slash');
								}
							} else {
								input.type = 'password';
								this.classList.remove('active');
								if (icon) {
									icon.classList.remove('fa-eye-slash');
									icon.classList.add('fa-eye');
								}
							}
						}
					});
				});
			}
			
			if (document.readyState === 'loading') {
				document.addEventListener('DOMContentLoaded', initPasswordToggle);
			} else {
				initPasswordToggle();
			}
			
			// Re-initialize after dynamic content loads
			setTimeout(initPasswordToggle, 100);
			setTimeout(initPasswordToggle, 500);
		})();
		";
		wp_add_inline_script( 'jquery', $password_toggle_script );
	}
	
	// Thank You page styles
	global $wp;
	if ( is_checkout() && isset( $wp->query_vars['order-received'] ) ) {
		wp_enqueue_style( 'khutaa-thankyou', $khutaa_uri . '/templates/thankyou/thankyou.css', array(), '1.0.0' );
	}
	
	// Password reset confirmation and reset form page styles
	global $wp;
	if ( is_account_page() && ( 
		( isset( $_GET['reset-link-sent'] ) && $_GET['reset-link-sent'] === 'true' ) ||
		( isset( $_GET['show-reset-form'] ) && $_GET['show-reset-form'] === 'true' )
	) ) {
		wp_enqueue_style( 'khutaa-auth', $khutaa_uri . '/components/auth/y-c-auth.css', array(), '1.0.0' );
		wp_enqueue_style( 'khutaa-auth-btn', $khutaa_uri . '/components/buttons/y-c-auth-btn.css', array(), '1.0.0' );
	}
	
	// Remove search background on account and auth pages
	$current_page = get_queried_object();
	$is_auth_page = false;
	if ( $current_page && isset( $current_page->post_name ) ) {
		$is_auth_page = in_array( $current_page->post_name, array( 'login', 'register', 'lost-password' ) );
	}
	
	if ( is_account_page() || $is_auth_page ) {
		$custom_css = "
		/* Remove background from search elements on account and auth pages */
		body.account-page .icons .search-container,
		body.account-page .icons .search-submit,
		body.account-page .logo-container .search-container,
		body.account-page .icons .search-container .search-form {
			background: transparent !important;
			box-shadow: none !important;
		}
		
		body.account-page .icons .search-input {
			background: transparent !important;
			border: none !important;
			box-shadow: none !important;
			padding: 0 !important;
		}
		
		body.account-page .icons .search-submit {
			background: transparent !important;
			border: none !important;
			box-shadow: none !important;
			padding: 0 !important;
		}
		
		/* Ensure search input stays in navbar line - don't go below */
		body.account-page .icons .search-container,
		body.account-page .icons .search-container .search-form {
			height: 100% !important;
			align-self: stretch !important;
		}
		
		body.account-page .icons .search-input {
			top: 50% !important;
			transform: translateY(-50%) !important;
			max-height: 50px !important;
		}
		
		/* Mobile - Remove search background */
		@media (max-width: 768px) {
			body.account-page .logo-container .search-container,
			body.account-page .logo-container .search-container .search-form {
				background: transparent !important;
				box-shadow: none !important;
			}
			
			body.account-page .logo-container .search-input {
				background: transparent !important;
				border: none !important;
				box-shadow: none !important;
				padding: 0 !important;
			}
		}
		";
		wp_add_inline_style( 'khutaa-navbar', $custom_css );
	}
}
add_action( 'wp_enqueue_scripts', 'khutaa_theme_scripts' );

/**
 * Convert country field to text input and translate to Arabic in address edit form
 */
function khutaa_convert_country_to_text( $fields, $load_address ) {
	// Only apply to my account address edit form
	if ( ! is_account_page() ) {
		return $fields;
	}
	
	$type = $load_address . '_';
	
	// Convert country field to text
	if ( isset( $fields[ $type . 'country' ] ) ) {
		$fields[ $type . 'country' ]['type'] = 'text';
		$fields[ $type . 'country' ]['class'] = array( 'form-row-wide', 'address-field' );
		
		// If there's a value, convert country code to country name
		if ( ! empty( $fields[ $type . 'country' ]['value'] ) ) {
			$country_code = $fields[ $type . 'country' ]['value'];
			$countries = WC()->countries->get_countries();
			if ( isset( $countries[ $country_code ] ) ) {
				$fields[ $type . 'country' ]['value'] = $countries[ $country_code ];
			}
		} else {
			$fields[ $type . 'country' ]['value'] = __( 'السعودية', 'khutaa-theme' );
		}
	}
	
	// Convert state field to text if it's a select
	if ( isset( $fields[ $type . 'state' ] ) && isset( $fields[ $type . 'state' ]['type'] ) && $fields[ $type . 'state' ]['type'] === 'state' ) {
		$fields[ $type . 'state' ]['type'] = 'text';
		$fields[ $type . 'state' ]['class'] = array( 'form-row-wide', 'address-field' );
	}
	
	// Arabic translations for address fields
	$arabic_labels = array(
		'first_name'  => __( 'الاسم الأول', 'khutaa-theme' ),
		'last_name'   => __( 'اسم العائلة', 'khutaa-theme' ),
		'company'     => __( 'اسم الشركة', 'khutaa-theme' ),
		'country'     => __( 'الدولة', 'khutaa-theme' ),
		'address_1'   => __( 'عنوان الشارع', 'khutaa-theme' ),
		'address_2'   => __( 'عنوان إضافي (اختياري)', 'khutaa-theme' ),
		'city'        => __( 'المدينة', 'khutaa-theme' ),
		'state'       => __( 'المنطقة / المحافظة', 'khutaa-theme' ),
		'postcode'    => __( 'الرمز البريدي', 'khutaa-theme' ),
		'phone'       => __( 'رقم الهاتف', 'khutaa-theme' ),
		'email'       => __( 'البريد الإلكتروني', 'khutaa-theme' ),
	);
	
	$arabic_placeholders = array(
		'address_1'   => __( 'رقم المنزل واسم الشارع', 'khutaa-theme' ),
		'address_2'   => __( 'عنوان إضافي (اختياري)', 'khutaa-theme' ),
		'city'        => __( 'المدينة', 'khutaa-theme' ),
		'state'       => __( 'المنطقة / المحافظة', 'khutaa-theme' ),
		'postcode'    => __( 'الرمز البريدي', 'khutaa-theme' ),
	);
	
	foreach ( $fields as $key => $field ) {
		// Get field name without prefix
		$field_name = str_replace( array( 'billing_', 'shipping_' ), '', $key );
		
		// Translate label
		if ( isset( $arabic_labels[ $field_name ] ) ) {
			$fields[ $key ]['label'] = $arabic_labels[ $field_name ];
		}
		
		// Translate placeholder
		if ( isset( $arabic_placeholders[ $field_name ] ) ) {
			if ( empty( $fields[ $key ]['placeholder'] ) ) {
				$fields[ $key ]['placeholder'] = $arabic_placeholders[ $field_name ];
			}
		}
	}
	
	return $fields;
}
add_filter( 'woocommerce_address_to_edit', 'khutaa_convert_country_to_text', 10, 2 );

/**
 * Hide default WooCommerce notice on password reset confirmation page
 */
function khutaa_hide_password_reset_notice() {
	if ( is_account_page() && isset( $_GET['reset-link-sent'] ) && $_GET['reset-link-sent'] === 'true' ) {
		// Remove default notices
		remove_action( 'woocommerce_before_lost_password_confirmation_message', 'wc_print_notices', 10 );
	}
}
add_action( 'template_redirect', 'khutaa_hide_password_reset_notice' );

/**
 * Translate password reset success message to Arabic
 */
function khutaa_translate_password_reset_success( $translated_text, $text, $domain ) {
	if ( 'woocommerce' === $domain && 'Your password has been reset successfully.' === $text ) {
		if ( is_account_page() && isset( $_GET['password-reset'] ) && $_GET['password-reset'] === 'true' ) {
			return esc_html__( 'تم إعادة تعيين كلمة المرور بنجاح.', 'khutaa-theme' );
		}
	}
	return $translated_text;
}
add_filter( 'gettext', 'khutaa_translate_password_reset_success', 20, 3 );

/**
 * Translate password reset form messages to Arabic
 */
function khutaa_translate_password_reset_messages( $translated_text, $text, $domain ) {
	if ( 'woocommerce' !== $domain ) {
		return $translated_text;
	}

	$translations = array(
		'Enter a new password below.' => __( 'أدخل كلمة مرور جديدة أدناه', 'khutaa-theme' ),
		'New password' => __( 'كلمة المرور الجديدة', 'khutaa-theme' ),
		'Re-enter new password' => __( 'تأكيد كلمة المرور', 'khutaa-theme' ),
		'Save' => __( 'حفظ', 'khutaa-theme' ),
		'Please enter your password.' => __( 'يرجى إدخال كلمة المرور.', 'khutaa-theme' ),
		'Passwords do not match.' => __( 'كلمات المرور غير متطابقة.', 'khutaa-theme' ),
		'This key is invalid or has already been used. Please reset your password again if needed.' => __( 'هذا المفتاح غير صالح أو تم استخدامه بالفعل. يرجى إعادة تعيين كلمة المرور مرة أخرى إذا لزم الأمر.', 'khutaa-theme' ),
	);

	if ( isset( $translations[ $text ] ) ) {
		return $translations[ $text ];
	}

	return $translated_text;
}
add_filter( 'gettext', 'khutaa_translate_password_reset_messages', 20, 3 );


/**
 * Ensure My Account page content is displayed
 */
function khutaa_ensure_my_account_content() {
	if ( is_account_page() && ! is_user_logged_in() ) {
		// If not logged in, the form-login.php template will handle the redirect
		return;
	}
}
add_action( 'template_redirect', 'khutaa_ensure_my_account_content' );

/**
 * Remove dashboard and downloads from My Account menu
 */
function khutaa_remove_account_menu_items( $items ) {
	unset( $items['dashboard'] );
	unset( $items['downloads'] );
	return $items;
}
add_filter( 'woocommerce_account_menu_items', 'khutaa_remove_account_menu_items' );

/**
 * Redirect dashboard to orders page
 */
function khutaa_redirect_account_dashboard() {
	if ( is_account_page() && is_user_logged_in() ) {
		global $wp;
		// If on dashboard (no endpoint), redirect to orders
		if ( empty( $wp->query_vars ) || ( isset( $wp->query_vars['page'] ) && empty( array_diff( array_keys( $wp->query_vars ), array( 'page' ) ) ) ) ) {
			wp_safe_redirect( wc_get_endpoint_url( 'orders' ) );
			exit;
		}
	}
}
add_action( 'template_redirect', 'khutaa_redirect_account_dashboard', 5 );

/**
 * Translate order status to Arabic
 */
function khutaa_translate_order_status( $status ) {
	$status_name = wc_get_order_status_name( $status );
	
	$arabic_statuses = array(
		__( 'Pending payment', 'woocommerce' ) => __( 'قيد الانتظار', 'khutaa-theme' ),
		__( 'Processing', 'woocommerce' ) => __( 'قيد المعالجة', 'khutaa-theme' ),
		__( 'On hold', 'woocommerce' ) => __( 'معلق', 'khutaa-theme' ),
		__( 'Completed', 'woocommerce' ) => __( 'مكتمل', 'khutaa-theme' ),
		__( 'Cancelled', 'woocommerce' ) => __( 'ملغي', 'khutaa-theme' ),
		__( 'Refunded', 'woocommerce' ) => __( 'مسترد', 'khutaa-theme' ),
		__( 'Failed', 'woocommerce' ) => __( 'فاشل', 'khutaa-theme' ),
	);
	
	if ( isset( $arabic_statuses[ $status_name ] ) ) {
		return $arabic_statuses[ $status_name ];
	}
	
	return $status_name;
}

/**
 * Get template directory path for Khutaa assets
 */
function khutaa_get_template_uri() {
	return get_template_directory_uri() . '/khutaa';
}

/**
 * Handle custom login form submission
 */
function khutaa_handle_login_form() {
	if ( ! isset( $_POST['login'] ) || ! isset( $_POST['woocommerce-login-nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['woocommerce-login-nonce'], 'woocommerce-login' ) ) {
		return;
	}

	$creds = array(
		'user_login'    => sanitize_text_field( $_POST['log'] ),
		'user_password' => $_POST['pwd'],
		'remember'      => isset( $_POST['rememberme'] ),
	);

	$user = wp_signon( $creds, is_ssl() );

	if ( is_wp_error( $user ) ) {
		// Translate error messages to Arabic
		$error_code = $user->get_error_code();
		$error_message = $user->get_error_message();
		
		// Translate common login errors
		$translated_errors = array(
			'incorrect_password' => __( 'خطأ: كلمة المرور التي أدخلتها غير صحيحة. هل فقدت كلمة مرورك؟', 'khutaa-theme' ),
			'invalid_username'   => __( 'خطأ: اسم المستخدم غير مسجل على هذا الموقع.', 'khutaa-theme' ),
			'invalid_email'      => __( 'خطأ: عنوان البريد الإلكتروني غير معروف.', 'khutaa-theme' ),
			'empty_username'     => __( 'خطأ: حقل اسم المستخدم أو البريد الإلكتروني فارغ.', 'khutaa-theme' ),
			'empty_password'    => __( 'خطأ: حقل كلمة المرور فارغ.', 'khutaa-theme' ),
		);
		
		if ( isset( $translated_errors[ $error_code ] ) ) {
			$error_message = $translated_errors[ $error_code ];
		} else {
			// Try to translate common error patterns
			if ( strpos( $error_message, 'password' ) !== false && strpos( $error_message, 'incorrect' ) !== false ) {
				$error_message = __( 'خطأ: كلمة المرور التي أدخلتها غير صحيحة. هل فقدت كلمة مرورك؟', 'khutaa-theme' );
			} elseif ( strpos( $error_message, 'username' ) !== false && strpos( $error_message, 'not registered' ) !== false ) {
				$error_message = __( 'خطأ: اسم المستخدم غير مسجل على هذا الموقع.', 'khutaa-theme' );
			}
		}
		
		wc_add_notice( $error_message, 'error' );
	} else {
		$redirect = ! empty( $_POST['redirect'] ) ? esc_url_raw( $_POST['redirect'] ) : wc_get_page_permalink( 'myaccount' );
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'template_redirect', 'khutaa_handle_login_form' );

/**
 * Handle custom registration form submission
 */
function khutaa_handle_registration_form() {
	if ( ! isset( $_POST['register'] ) || ! isset( $_POST['woocommerce-register-nonce'] ) ) {
		return;
	}

	if ( ! wp_verify_nonce( $_POST['woocommerce-register-nonce'], 'woocommerce-register' ) ) {
		return;
	}

	$email    = sanitize_email( $_POST['email'] );
	$password = $_POST['password'];
	$password_confirm = isset( $_POST['password_confirm'] ) ? $_POST['password_confirm'] : '';

	// Validate email
	if ( empty( $email ) || ! is_email( $email ) ) {
		wc_add_notice( __( 'يرجى إدخال بريد إلكتروني صحيح.', 'khutaa-theme' ), 'error' );
		return;
	}

	// Validate passwords match
	if ( empty( $password ) ) {
		wc_add_notice( __( 'يرجى إدخال كلمة مرور.', 'khutaa-theme' ), 'error' );
		return;
	}

	if ( $password !== $password_confirm ) {
		wc_add_notice( __( 'كلمات المرور غير متطابقة.', 'khutaa-theme' ), 'error' );
		return;
	}

	// Check password strength
	if ( strlen( $password ) < 6 ) {
		wc_add_notice( __( 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.', 'khutaa-theme' ), 'error' );
		return;
	}

	// Check terms acceptance
	if ( ! isset( $_POST['terms'] ) ) {
		wc_add_notice( __( 'يجب الموافقة على الشروط والأحكام.', 'khutaa-theme' ), 'error' );
		return;
	}

	// Create customer using WooCommerce function
	$customer = wc_create_new_customer( $email, '', $password );

	if ( is_wp_error( $customer ) ) {
		wc_add_notice( $customer->get_error_message(), 'error' );
	} else {
		// Auto login after registration
		if ( apply_filters( 'woocommerce_registration_auth_new_customer', true, $customer ) ) {
			wc_set_customer_auth_cookie( $customer );
		}

		wc_add_notice( __( 'تم إنشاء حسابك بنجاح!', 'khutaa-theme' ), 'success' );

		$redirect = ! empty( $_POST['redirect'] ) ? esc_url_raw( $_POST['redirect'] ) : wc_get_page_permalink( 'myaccount' );
		wp_safe_redirect( $redirect );
		exit;
	}
}
add_action( 'template_redirect', 'khutaa_handle_registration_form' );

/**
 * Ensure password is required for registration (disable auto-generation)
 */
add_filter( 'woocommerce_registration_generate_password', '__return_false' );

/**
 * Translate WooCommerce cart removed item notice to Arabic
 */
/**
 * Translate WooCommerce strings to Arabic in My Account pages
 */
add_filter( 'gettext', 'khutaa_translate_woocommerce_strings', 20, 3 );
function khutaa_translate_woocommerce_strings( $translated_text, $text, $domain ) {
	if ( 'woocommerce' === $domain && is_account_page() ) {
		// Order status translations
		$status_translations = array(
			'Pending payment' => 'قيد الانتظار',
			'Processing' => 'قيد المعالجة',
			'On hold' => 'معلق',
			'Completed' => 'مكتمل',
			'Cancelled' => 'ملغي',
			'Refunded' => 'مسترد',
			'Failed' => 'فاشل',
		);
		
		if ( isset( $status_translations[ $text ] ) ) {
			return $status_translations[ $text ];
		}
		
		// Menu items translations
		$menu_translations = array(
			'Orders' => 'الطلبات',
			'Addresses' => 'العناوين',
			'Address' => 'العنوان',
			'Payment methods' => 'طرق الدفع',
			'Account details' => 'تفاصيل الحساب',
			'Log out' => 'تسجيل الخروج',
		);
		
		if ( isset( $menu_translations[ $text ] ) ) {
			return $menu_translations[ $text ];
		}
	}
	
	return $translated_text;
}

add_filter( 'gettext', 'khutaa_translate_cart_removed_notice', 20, 3 );
function khutaa_translate_cart_removed_notice( $translated_text, $text, $domain ) {
	if ( 'woocommerce' === $domain ) {
		// Cart notices
		if ( $text === '%s removed.' ) {
			return '%s تم حذفه.';
		} elseif ( $text === ' removed.' ) {
			return ' تم حذفه.';
		} elseif ( $text === 'Undo?' ) {
			return 'تراجع؟';
		} elseif ( $text === 'Proceed to checkout' ) {
			return 'متابعة للدفع';
		}
		
		// Checkout translations
		elseif ( $text === 'Subtotal' ) {
			return 'المجموع';
		} elseif ( $text === 'Total' ) {
			return 'الإجمالي المقدر';
		} elseif ( $text === 'Product' ) {
			return 'المنتج';
		} elseif ( $text === 'Place order' ) {
			return 'الدفع';
		} elseif ( $text === 'Have a coupon?' ) {
			return 'هل لديك كوبون؟';
		} elseif ( $text === 'Click here to enter your code' ) {
			return 'انقر هنا لإدخال الكود';
		} elseif ( $text === 'Coupon code' ) {
			return 'كود الكوبون';
		} elseif ( $text === 'Apply coupon' ) {
			return 'تطبيق الكوبون';
		} elseif ( $text === 'Your order' ) {
			return 'ملخص طلبك';
		} elseif ( strpos( $text, 'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our' ) !== false || 
		           $text === 'Your personal data will be used to process your order, support your experience throughout this website, and for other purposes described in our %s.' ) {
			return 'سيتم استخدام بياناتك الشخصية لمعالجة طلبك ودعم تجربتك على هذا الموقع ولأغراض أخرى موضحة في %s.';
		}
	}
	return $translated_text;
}

/**
 * Create custom wishlist table
 */
function khutaa_create_wishlist_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'khutaa_wishlist';
	$charset_collate = $wpdb->get_charset_collate();
	
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		user_id bigint(20) unsigned NOT NULL,
		product_id bigint(20) unsigned NOT NULL,
		date_added datetime DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY (id),
		UNIQUE KEY user_product (user_id, product_id),
		KEY user_id (user_id),
		KEY product_id (product_id)
	) $charset_collate;";
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
}

/**
 * Initialize wishlist table on theme activation
 */
function khutaa_theme_activation() {
	khutaa_create_wishlist_table();
}
add_action( 'after_switch_theme', 'khutaa_theme_activation' );


// Create table on init if not exists (for existing installations)
function khutaa_check_wishlist_table() {
	global $wpdb;
	$table_name = $wpdb->prefix . 'khutaa_wishlist';
	if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
		khutaa_create_wishlist_table();
	}
}
add_action( 'init', 'khutaa_check_wishlist_table' );

/**
 * Check if product is in user's wishlist
 */
function khutaa_is_product_in_wishlist( $product_id, $user_id = null ) {
	if ( ! $user_id ) {
		$user_id = get_current_user_id();
	}
	
	if ( ! $user_id ) {
		return false;
	}
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'khutaa_wishlist';
	
	$exists = $wpdb->get_var( $wpdb->prepare(
		"SELECT id FROM $table_name WHERE user_id = %d AND product_id = %d",
		$user_id,
		$product_id
	) );
	
	return ! empty( $exists );
}

/**
 * AJAX handler for adding product to wishlist
 */
function khutaa_ajax_add_to_wishlist() {
	// Check nonce if provided
	if ( isset( $_POST['nonce'] ) ) {
		$nonce = sanitize_text_field( $_POST['nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'khutaa_wishlist_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'التحقق من الأمان فشل', 'khutaa-theme' ) ) );
			return;
		}
	}
	
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول لإضافة منتج للمفضلة', 'khutaa-theme' ) ) );
		return;
	}
	
	$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
	
	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'معرف المنتج غير صحيح', 'khutaa-theme' ) ) );
		return;
	}
	
	// Ensure table exists
	khutaa_check_wishlist_table();
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'khutaa_wishlist';
	$user_id = get_current_user_id();
	
	// Check if already exists
	$exists = khutaa_is_product_in_wishlist( $product_id, $user_id );
	
	if ( ! $exists ) {
		$result = $wpdb->insert(
			$table_name,
			array(
				'user_id' => $user_id,
				'product_id' => $product_id,
				'date_added' => current_time( 'mysql' )
			),
			array( '%d', '%d', '%s' )
		);
		
		if ( $result !== false ) {
			wp_send_json_success( array( 
				'message' => __( 'تمت الإضافة للمفضلة', 'khutaa-theme' ),
				'result' => 'success'
			) );
			return;
		} else {
			wp_send_json_error( array( 'message' => __( 'حدث خطأ أثناء الإضافة', 'khutaa-theme' ) ) );
			return;
		}
	} else {
		wp_send_json_success( array( 
			'message' => __( 'المنتج موجود بالفعل في المفضلة', 'khutaa-theme' ),
			'result' => 'success'
		) );
		return;
	}
}
add_action( 'wp_ajax_khutaa_add_to_wishlist', 'khutaa_ajax_add_to_wishlist' );
add_action( 'wp_ajax_nopriv_khutaa_add_to_wishlist', 'khutaa_ajax_add_to_wishlist' );

/**
 * AJAX handler for removing product from wishlist
 */
function khutaa_ajax_remove_from_wishlist() {
	// Check nonce if provided
	if ( isset( $_POST['nonce'] ) ) {
		$nonce = sanitize_text_field( $_POST['nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'khutaa_wishlist_nonce' ) ) {
			wp_send_json_error( array( 'message' => __( 'التحقق من الأمان فشل', 'khutaa-theme' ) ) );
			return;
		}
	}
	
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( array( 'message' => __( 'يجب تسجيل الدخول لإزالة منتج من المفضلة', 'khutaa-theme' ) ) );
		return;
	}
	
	$product_id = isset( $_POST['product_id'] ) ? intval( $_POST['product_id'] ) : 0;
	
	if ( ! $product_id ) {
		wp_send_json_error( array( 'message' => __( 'معرف المنتج غير صحيح', 'khutaa-theme' ) ) );
		return;
	}
	
	// Ensure table exists
	khutaa_check_wishlist_table();
	
	global $wpdb;
	$table_name = $wpdb->prefix . 'khutaa_wishlist';
	$user_id = get_current_user_id();
	
	$result = $wpdb->delete(
		$table_name,
		array(
			'user_id' => $user_id,
			'product_id' => $product_id
		),
		array( '%d', '%d' )
	);
	
	if ( $result !== false ) {
		wp_send_json_success( array( 
			'message' => __( 'تمت الإزالة من المفضلة', 'khutaa-theme' ),
			'result' => 'success'
		) );
		return;
	} else {
		wp_send_json_error( array( 'message' => __( 'حدث خطأ أثناء الإزالة', 'khutaa-theme' ) ) );
		return;
	}
}
add_action( 'wp_ajax_khutaa_remove_from_wishlist', 'khutaa_ajax_remove_from_wishlist' );
add_action( 'wp_ajax_nopriv_khutaa_remove_from_wishlist', 'khutaa_ajax_remove_from_wishlist' );

// ============================================================================
// Contact Settings (إعدادات التواصل)
// ============================================================================

/**
 * Get contact settings value
 */
function khutaa_get_contact_setting( $key, $default = '' ) {
	// Try to get from contact settings first
	$value = get_theme_mod( 'khutaa_contact_' . $key, '' );
	
	// Fallback to demo content if empty (for backward compatibility)
	if ( empty( $value ) ) {
		$value = khutaa_get_demo_content( 'khutaa_' . $key );
	}
	
	// Return default if still empty
	if ( empty( $value ) && ! empty( $default ) ) {
		return $default;
	}
	
	return $value;
}

/**
 * Add Contact Settings menu page in Appearance menu
 */
function khutaa_add_contact_settings_menu() {
	add_theme_page(
		__( 'إعدادات التواصل', 'khutaa-theme' ),
		__( 'إعدادات التواصل', 'khutaa-theme' ),
		'edit_theme_options',
		'khutaa-contact-settings',
		'khutaa_contact_settings_page'
	);
}
add_action( 'admin_menu', 'khutaa_add_contact_settings_menu' );

/**
 * Handle form submission for contact settings
 */
function khutaa_save_contact_settings() {
	if ( ! isset( $_POST['khutaa_contact_settings_save'] ) || ! check_admin_referer( 'khutaa_contact_settings_nonce', 'khutaa_contact_settings_nonce' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_die( __( 'غير مصرح لك بهذا الإجراء', 'khutaa-theme' ) );
	}

	// Save footer contact info
	if ( isset( $_POST['khutaa_contact_address'] ) ) {
		set_theme_mod( 'khutaa_contact_address', sanitize_text_field( $_POST['khutaa_contact_address'] ) );
	}
	if ( isset( $_POST['khutaa_contact_phone'] ) ) {
		set_theme_mod( 'khutaa_contact_phone', sanitize_text_field( $_POST['khutaa_contact_phone'] ) );
	}
	if ( isset( $_POST['khutaa_contact_email'] ) ) {
		set_theme_mod( 'khutaa_contact_email', sanitize_email( $_POST['khutaa_contact_email'] ) );
	}
	
	// Save WhatsApp number
	if ( isset( $_POST['khutaa_contact_whatsapp'] ) ) {
		set_theme_mod( 'khutaa_contact_whatsapp', sanitize_text_field( $_POST['khutaa_contact_whatsapp'] ) );
	}
	
	// Save contact form recipient email
	if ( isset( $_POST['khutaa_contact_form_email'] ) ) {
		set_theme_mod( 'khutaa_contact_form_email', sanitize_email( $_POST['khutaa_contact_form_email'] ) );
	}
	
	// Save SMTP settings
	if ( isset( $_POST['khutaa_smtp_enabled'] ) ) {
		set_theme_mod( 'khutaa_smtp_enabled', '1' );
	} else {
		set_theme_mod( 'khutaa_smtp_enabled', '0' );
	}
	
	if ( isset( $_POST['khutaa_smtp_email_type'] ) ) {
		set_theme_mod( 'khutaa_smtp_email_type', sanitize_text_field( $_POST['khutaa_smtp_email_type'] ) );
	}
	
	if ( isset( $_POST['khutaa_smtp_host'] ) ) {
		set_theme_mod( 'khutaa_smtp_host', sanitize_text_field( $_POST['khutaa_smtp_host'] ) );
	}
	if ( isset( $_POST['khutaa_smtp_port'] ) ) {
		set_theme_mod( 'khutaa_smtp_port', absint( $_POST['khutaa_smtp_port'] ) );
	}
	if ( isset( $_POST['khutaa_smtp_username'] ) ) {
		set_theme_mod( 'khutaa_smtp_username', sanitize_text_field( $_POST['khutaa_smtp_username'] ) );
	}
	if ( isset( $_POST['khutaa_smtp_password'] ) && ! empty( $_POST['khutaa_smtp_password'] ) ) {
		// Store password securely (base64 encoded - not ideal but better than plain text)
		set_theme_mod( 'khutaa_smtp_password', base64_encode( $_POST['khutaa_smtp_password'] ) );
	}
	if ( isset( $_POST['khutaa_smtp_encryption'] ) ) {
		set_theme_mod( 'khutaa_smtp_encryption', sanitize_text_field( $_POST['khutaa_smtp_encryption'] ) );
	}

	add_settings_error( 'khutaa_contact_settings', 'settings_updated', __( 'تم حفظ إعدادات التواصل بنجاح!', 'khutaa-theme' ), 'success' );
}
add_action( 'admin_init', 'khutaa_save_contact_settings' );

/**
 * Contact Settings admin page callback
 */
function khutaa_contact_settings_page() {
	// Get current values
	$address = khutaa_get_contact_setting( 'address' );
	$phone = khutaa_get_contact_setting( 'phone' );
	$email = khutaa_get_contact_setting( 'email' );
	$whatsapp = khutaa_get_contact_setting( 'whatsapp' );
	$form_email = khutaa_get_contact_setting( 'form_email', get_option( 'admin_email' ) );
	
	$smtp_enabled = get_theme_mod( 'khutaa_smtp_enabled', '0' );
	$smtp_email_type = get_theme_mod( 'khutaa_smtp_email_type', 'gmail' );
	$smtp_host = get_theme_mod( 'khutaa_smtp_host', '' );
	$smtp_port = get_theme_mod( 'khutaa_smtp_port', '587' );
	$smtp_username = get_theme_mod( 'khutaa_smtp_username', '' );
	$smtp_password_encoded = get_theme_mod( 'khutaa_smtp_password', '' );
	$smtp_encryption = get_theme_mod( 'khutaa_smtp_encryption', 'tls' );
	
	settings_errors( 'khutaa_contact_settings' );
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'إعدادات التواصل', 'khutaa-theme' ); ?></h1>
		
		<form method="post" action="">
			<?php wp_nonce_field( 'khutaa_contact_settings_nonce', 'khutaa_contact_settings_nonce' ); ?>
			
			<table class="form-table" role="presentation">
				<tbody>
					<!-- Footer Contact Information -->
					<tr>
						<th colspan="2"><h2><?php esc_html_e( 'معلومات الفوتر (العرض في الفوتر)', 'khutaa-theme' ); ?></h2></th>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_contact_address"><?php esc_html_e( 'العنوان', 'khutaa-theme' ); ?></label></th>
						<td><input type="text" id="khutaa_contact_address" name="khutaa_contact_address" value="<?php echo esc_attr( $address ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_contact_phone"><?php esc_html_e( 'رقم الهاتف', 'khutaa-theme' ); ?></label></th>
						<td><input type="text" id="khutaa_contact_phone" name="khutaa_contact_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" /></td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_contact_email"><?php esc_html_e( 'البريد الإلكتروني', 'khutaa-theme' ); ?></label></th>
						<td><input type="email" id="khutaa_contact_email" name="khutaa_contact_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text" /></td>
					</tr>
					
					<!-- WhatsApp -->
					<tr>
						<th colspan="2"><h2><?php esc_html_e( 'رقم الواتساب (للزر العائم)', 'khutaa-theme' ); ?></h2></th>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_contact_whatsapp"><?php esc_html_e( 'رقم الواتساب', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="text" id="khutaa_contact_whatsapp" name="khutaa_contact_whatsapp" value="<?php echo esc_attr( $whatsapp ); ?>" class="regular-text" placeholder="966xxxxxxxxx" />
							<p class="description"><?php esc_html_e( 'أدخل رقم الواتساب بدون + أو 00 (مثال: 966501234567)', 'khutaa-theme' ); ?></p>
						</td>
					</tr>
					
					<!-- Contact Form Email -->
					<tr>
						<th colspan="2"><h2><?php esc_html_e( 'إعدادات نموذج التواصل', 'khutaa-theme' ); ?></h2></th>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_contact_form_email"><?php esc_html_e( 'البريد الإلكتروني المستقبل للرسائل', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="email" id="khutaa_contact_form_email" name="khutaa_contact_form_email" value="<?php echo esc_attr( $form_email ); ?>" class="regular-text" />
							<p class="description"><?php esc_html_e( 'البريد الإلكتروني الذي سيتم إرسال رسائل نموذج التواصل إليه', 'khutaa-theme' ); ?></p>
						</td>
					</tr>
					
					<!-- SMTP Settings -->
					<tr>
						<th colspan="2"><h2><?php esc_html_e( 'إعدادات SMTP للبريد الإلكتروني', 'khutaa-theme' ); ?></h2></th>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_smtp_enabled"><?php esc_html_e( 'تفعيل SMTP', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="checkbox" id="khutaa_smtp_enabled" name="khutaa_smtp_enabled" value="1" <?php checked( $smtp_enabled, '1' ); ?> />
							<label for="khutaa_smtp_enabled"><?php esc_html_e( 'استخدام SMTP لإرسال البريد الإلكتروني', 'khutaa-theme' ); ?></label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_smtp_email_type"><?php esc_html_e( 'نوع الإيميل', 'khutaa-theme' ); ?></label></th>
						<td>
							<select id="khutaa_smtp_email_type" name="khutaa_smtp_email_type">
								<option value="gmail" <?php selected( $smtp_email_type, 'gmail' ); ?>><?php esc_html_e( 'Gmail', 'khutaa-theme' ); ?></option>
								<option value="professional" <?php selected( $smtp_email_type, 'professional' ); ?>><?php esc_html_e( 'إيميل احترافي', 'khutaa-theme' ); ?></option>
							</select>
							<p class="description"><?php esc_html_e( 'اختر نوع البريد الإلكتروني المستخدم', 'khutaa-theme' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_smtp_host"><?php esc_html_e( 'SMTP Host', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="text" id="khutaa_smtp_host" name="khutaa_smtp_host" value="<?php echo esc_attr( $smtp_host ); ?>" class="regular-text" placeholder="<?php echo esc_attr( $smtp_email_type === 'gmail' ? 'smtp.gmail.com' : 'smtp.yourdomain.com' ); ?>" />
							<p class="description"><?php esc_html_e( 'لـ Gmail: smtp.gmail.com', 'khutaa-theme' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_smtp_port"><?php esc_html_e( 'SMTP Port', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="number" id="khutaa_smtp_port" name="khutaa_smtp_port" value="<?php echo esc_attr( $smtp_port ); ?>" class="small-text" />
							<p class="description"><?php esc_html_e( 'عادة: 587 للـ TLS أو 465 للـ SSL', 'khutaa-theme' ); ?></p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_smtp_username"><?php esc_html_e( 'SMTP Username (البريد الإلكتروني)', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="email" id="khutaa_smtp_username" name="khutaa_smtp_username" value="<?php echo esc_attr( $smtp_username ); ?>" class="regular-text" />
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_smtp_password"><?php esc_html_e( 'SMTP Password (كلمة المرور)', 'khutaa-theme' ); ?></label></th>
						<td>
							<input type="password" id="khutaa_smtp_password" name="khutaa_smtp_password" value="" class="regular-text" placeholder="<?php esc_attr_e( 'اتركه فارغاً إذا كنت لا تريد تغييره', 'khutaa-theme' ); ?>" />
							<p class="description">
								<?php 
								if ( $smtp_email_type === 'gmail' ) {
									esc_html_e( 'لـ Gmail: استخدم "كلمة مرور التطبيق" (App Password) من إعدادات Google Account', 'khutaa-theme' );
								} else {
									esc_html_e( 'كلمة المرور الخاصة بحساب البريد الإلكتروني', 'khutaa-theme' );
								}
								?>
							</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="khutaa_smtp_encryption"><?php esc_html_e( 'نوع التشفير', 'khutaa-theme' ); ?></label></th>
						<td>
							<select id="khutaa_smtp_encryption" name="khutaa_smtp_encryption">
								<option value="tls" <?php selected( $smtp_encryption, 'tls' ); ?>>TLS</option>
								<option value="ssl" <?php selected( $smtp_encryption, 'ssl' ); ?>>SSL</option>
								<option value="none" <?php selected( $smtp_encryption, 'none' ); ?>><?php esc_html_e( 'بدون', 'khutaa-theme' ); ?></option>
							</select>
						</td>
					</tr>
				</tbody>
			</table>
			
			<?php submit_button( __( 'حفظ الإعدادات', 'khutaa-theme' ), 'primary', 'khutaa_contact_settings_save' ); ?>
		</form>
		
		<div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #ddd;">
			<h2><?php esc_html_e( 'اختبار الإيميل', 'khutaa-theme' ); ?></h2>
			<p><?php esc_html_e( 'اختر الإيميل الذي تريد إرسال رسالة تجريبية إليه:', 'khutaa-theme' ); ?></p>
			<p>
				<button type="button" id="khutaa-test-email-btn" class="button button-secondary">
					<?php esc_html_e( 'إرسال إيميل تجريبي', 'khutaa-theme' ); ?>
				</button>
			</p>
			<div id="khutaa-test-email-result" style="margin-top: 15px;"></div>
		</div>
	</div>
	
	<script>
	jQuery(document).ready(function($) {
		// Auto-fill SMTP settings based on email type
		$('#khutaa_smtp_email_type').on('change', function() {
			var type = $(this).val();
			if (type === 'gmail') {
				$('#khutaa_smtp_host').val('smtp.gmail.com');
				$('#khutaa_smtp_port').val('587');
				$('#khutaa_smtp_encryption').val('tls');
			}
		});
		
		// Test email button
		$('#khutaa-test-email-btn').on('click', function(e) {
			e.preventDefault();
			
			var $btn = $(this);
			var $result = $('#khutaa-test-email-result');
			var testEmail = $('#khutaa_contact_form_email').val() || '<?php echo esc_js( get_option( 'admin_email' ) ); ?>';
			
			if (!testEmail) {
				$result.html('<div class="notice notice-error"><p><?php echo esc_js( __( 'يرجى إدخال بريد إلكتروني للاختبار', 'khutaa-theme' ) ); ?></p></div>');
				return;
			}
			
			$btn.prop('disabled', true).text('<?php echo esc_js( __( 'جاري الإرسال...', 'khutaa-theme' ) ); ?>');
			$result.html('<div class="notice notice-info"><p><?php echo esc_js( __( 'جاري إرسال الرسالة التجريبية...', 'khutaa-theme' ) ); ?></p></div>');
			
			$.ajax({
				url: ajaxurl,
				type: 'POST',
				data: {
					action: 'khutaa_test_email',
					email: testEmail,
					nonce: '<?php echo wp_create_nonce( 'khutaa_test_email_nonce' ); ?>'
				},
				success: function(response) {
					if (response.success) {
						$result.html('<div class="notice notice-success"><p>' + response.data.message + '</p></div>');
					} else {
						$result.html('<div class="notice notice-error"><p>' + (response.data.message || '<?php echo esc_js( __( 'حدث خطأ أثناء إرسال الرسالة', 'khutaa-theme' ) ); ?>') + '</p></div>');
					}
					$btn.prop('disabled', false).text('<?php echo esc_js( __( 'إرسال إيميل تجريبي', 'khutaa-theme' ) ); ?>');
				},
				error: function() {
					$result.html('<div class="notice notice-error"><p><?php echo esc_js( __( 'حدث خطأ أثناء الإرسال. يرجى المحاولة مرة أخرى.', 'khutaa-theme' ) ); ?></p></div>');
					$btn.prop('disabled', false).text('<?php echo esc_js( __( 'إرسال إيميل تجريبي', 'khutaa-theme' ) ); ?>');
				}
			});
		});
	});
	</script>
	<?php
}

/**
 * Configure SMTP settings via phpmailer
 */
function khutaa_configure_smtp( $phpmailer ) {
	$smtp_enabled = get_theme_mod( 'khutaa_smtp_enabled', '0' );
	
	if ( $smtp_enabled !== '1' ) {
		return;
	}
	
	$phpmailer->isSMTP();
	$phpmailer->Host = get_theme_mod( 'khutaa_smtp_host', 'smtp.gmail.com' );
	$phpmailer->SMTPAuth = true;
	$phpmailer->Port = get_theme_mod( 'khutaa_smtp_port', '587' );
	$phpmailer->Username = get_theme_mod( 'khutaa_smtp_username', '' );
	
	$password_encoded = get_theme_mod( 'khutaa_smtp_password', '' );
	if ( ! empty( $password_encoded ) ) {
		$phpmailer->Password = base64_decode( $password_encoded );
	}
	
	$encryption = get_theme_mod( 'khutaa_smtp_encryption', 'tls' );
	if ( $encryption === 'ssl' ) {
		$phpmailer->SMTPSecure = 'ssl';
	} elseif ( $encryption === 'tls' ) {
		$phpmailer->SMTPSecure = 'tls';
	}
	
	$phpmailer->From = get_theme_mod( 'khutaa_smtp_username', '' );
	$phpmailer->FromName = get_bloginfo( 'name' );
}
add_action( 'phpmailer_init', 'khutaa_configure_smtp' );

// ============================================================================
// Customize Checkout Fields (تخصيص حقول الدفع)
// ============================================================================

/**
 * Customize checkout billing fields
 */
function khutaa_customize_checkout_fields( $fields ) {
	// Remove first name and last name
	unset( $fields['billing']['billing_first_name'] );
	unset( $fields['billing']['billing_last_name'] );
	
	// Add full name field - NOT required
	$fields['billing']['billing_full_name'] = array(
		'label'        => __( 'الاسم الكامل', 'khutaa-theme' ),
		'required'     => false,
		'class'        => array( 'form-row-wide' ),
		'autocomplete' => 'name',
		'priority'     => 10,
	);
	
	// Reorder fields: Full Name, Phone, Email, Address, City, State, Description
	$is_logged_in = is_user_logged_in();
	
	// Phone - required only for non-logged-in users
	$fields['billing']['billing_phone']['priority'] = 20;
	$fields['billing']['billing_phone']['label'] = __( 'رقم الجوال', 'khutaa-theme' );
	$fields['billing']['billing_phone']['required'] = ! $is_logged_in;
	
	// Email - always required
	$fields['billing']['billing_email']['priority'] = 30;
	$fields['billing']['billing_email']['label'] = __( 'البريد الإلكتروني', 'khutaa-theme' );
	$fields['billing']['billing_email']['required'] = true;
	
	// Address - always required
	$fields['billing']['billing_address_1']['priority'] = 40;
	$fields['billing']['billing_address_1']['label'] = __( 'العنوان', 'khutaa-theme' );
	$fields['billing']['billing_address_1']['required'] = true;
	
	// Change country field to text input (default: Saudi Arabia)
	if ( isset( $fields['billing']['billing_country'] ) ) {
		$fields['billing']['billing_country']['type'] = 'text';
		$fields['billing']['billing_country']['default'] = __( 'السعودية', 'khutaa-theme' );
		$fields['billing']['billing_country']['priority'] = 41;
		$fields['billing']['billing_country']['label'] = __( 'الدولة', 'khutaa-theme' );
		$fields['billing']['billing_country']['placeholder'] = __( 'السعودية', 'khutaa-theme' );
		$fields['billing']['billing_country']['class'] = array( 'form-row-wide' );
	}
	
	// City field - NOT required
	if ( isset( $fields['billing']['billing_city'] ) ) {
		$fields['billing']['billing_city']['priority'] = 50;
		$fields['billing']['billing_city']['label'] = __( 'المدينة', 'khutaa-theme' );
		$fields['billing']['billing_city']['class'] = array( 'form-row-first' );
		$fields['billing']['billing_city']['required'] = false;
	}
	
	// State/District field (Neighborhood) - NOT required - Convert to text input for non-logged-in users
	if ( isset( $fields['billing']['billing_state'] ) ) {
		$fields['billing']['billing_state']['priority'] = 60;
		$fields['billing']['billing_state']['label'] = __( 'الحي', 'khutaa-theme' );
		$fields['billing']['billing_state']['class'] = array( 'form-row-last' );
		$fields['billing']['billing_state']['required'] = false;
		
		// Convert to text input for non-logged-in users (checkout page)
		if ( ! is_user_logged_in() && is_checkout() ) {
			$fields['billing']['billing_state']['type'] = 'text';
			$fields['billing']['billing_state']['placeholder'] = __( 'أدخل اسم الحي', 'khutaa-theme' );
		}
	}
	
	// Same for shipping state if exists
	if ( isset( $fields['shipping']['shipping_state'] ) ) {
		$fields['shipping']['shipping_state']['priority'] = 60;
		$fields['shipping']['shipping_state']['label'] = __( 'الحي', 'khutaa-theme' );
		$fields['shipping']['shipping_state']['class'] = array( 'form-row-last' );
		$fields['shipping']['shipping_state']['required'] = false;
		
		// Convert to text input for non-logged-in users (checkout page)
		if ( ! is_user_logged_in() && is_checkout() ) {
			$fields['shipping']['shipping_state']['type'] = 'text';
			$fields['shipping']['shipping_state']['placeholder'] = __( 'أدخل اسم الحي', 'khutaa-theme' );
		}
	}
	
	// Address 2 (optional description)
	if ( isset( $fields['billing']['billing_address_2'] ) ) {
		$fields['billing']['billing_address_2']['priority'] = 70;
		$fields['billing']['billing_address_2']['label'] = __( 'وصف إضافي (اختياري)', 'khutaa-theme' );
		$fields['billing']['billing_address_2']['placeholder'] = __( 'مثال: بجانب المدرسة، الطابق الثاني...', 'khutaa-theme' );
		$fields['billing']['billing_address_2']['required'] = false;
	}
	
	// Remove postcode
	unset( $fields['billing']['billing_postcode'] );
	
	// Remove company field
	unset( $fields['billing']['billing_company'] );
	
	return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'khutaa_customize_checkout_fields', 20 );

/**
 * Remove payment gateway description/instructions from checkout
 */
function khutaa_remove_payment_gateway_description( $description, $gateway_id ) {
	if ( is_checkout() ) {
		return '';
	}
	return $description;
}
add_filter( 'woocommerce_gateway_description', 'khutaa_remove_payment_gateway_description', 10, 2 );

/**
 * Remove payment fields from checkout
 */
function khutaa_remove_payment_fields() {
	if ( is_checkout() ) {
		?>
		<style>
		.woocommerce-checkout-payment .payment_box {
			display: none !important;
		}
		</style>
		<?php
	}
}
add_action( 'wp_footer', 'khutaa_remove_payment_fields' );

/**
 * Convert full name to first name and last name before processing
 */
function khutaa_process_full_name_before_checkout( $posted_data ) {
	if ( isset( $posted_data['billing_full_name'] ) && ! empty( $posted_data['billing_full_name'] ) ) {
		$full_name = sanitize_text_field( $posted_data['billing_full_name'] );
		
		// Split full name into first and last (simple split by space)
		$name_parts = explode( ' ', trim( $full_name ), 2 );
		$first_name = isset( $name_parts[0] ) ? $name_parts[0] : $full_name;
		$last_name = isset( $name_parts[1] ) ? $name_parts[1] : '';
		
		// Set first and last name for WooCommerce compatibility
		$posted_data['billing_first_name'] = $first_name;
		$posted_data['billing_last_name'] = $last_name;
	}
	
	return $posted_data;
}
add_filter( 'woocommerce_checkout_posted_data', 'khutaa_process_full_name_before_checkout', 10, 1 );

/**
 * Validate full name field
 */
function khutaa_validate_full_name_field() {
	if ( empty( $_POST['billing_full_name'] ) ) {
		wc_add_notice( __( 'الاسم الكامل مطلوب', 'khutaa-theme' ), 'error' );
	}
}
add_action( 'woocommerce_checkout_process', 'khutaa_validate_full_name_field' );

// Remove coupon form from before checkout form (will be shown only in order review)
add_action( 'init', 'khutaa_remove_checkout_coupon_from_top', 20 );
function khutaa_remove_checkout_coupon_from_top() {
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
}

// Translate privacy policy text
add_filter( 'woocommerce_get_privacy_policy_text', 'khutaa_translate_privacy_policy_text', 10, 2 );
function khutaa_translate_privacy_policy_text( $text, $type ) {
	if ( $type === 'checkout' ) {
		$text = 'سيتم استخدام بياناتك الشخصية لمعالجة طلبك ودعم تجربتك على هذا الموقع ولأغراض أخرى موضحة في [privacy_policy].';
	}
	return $text;
}

/**
 * AJAX handler for testing email
 */
function khutaa_ajax_test_email() {
	// Verify nonce
	if ( ! check_ajax_referer( 'khutaa_test_email_nonce', 'nonce', false ) ) {
		wp_send_json_error( array( 'message' => __( 'خطأ في التحقق من الأمان', 'khutaa-theme' ) ) );
		return;
	}
	
	// Check permissions
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		wp_send_json_error( array( 'message' => __( 'غير مصرح لك بهذا الإجراء', 'khutaa-theme' ) ) );
		return;
	}
	
	$test_email = isset( $_POST['email'] ) ? sanitize_email( $_POST['email'] ) : '';
	
	if ( empty( $test_email ) || ! is_email( $test_email ) ) {
		wp_send_json_error( array( 'message' => __( 'البريد الإلكتروني غير صحيح', 'khutaa-theme' ) ) );
		return;
	}
	
	// Prepare test email
	$subject = sprintf( __( 'رسالة تجريبية من %s', 'khutaa-theme' ), get_bloginfo( 'name' ) );
	$message = sprintf(
		__( 'هذه رسالة تجريبية من موقع %s.\n\nإذا تلقيت هذه الرسالة، فهذا يعني أن إعدادات SMTP تعمل بشكل صحيح.\n\nالتاريخ والوقت: %s', 'khutaa-theme' ),
		get_bloginfo( 'name' ),
		current_time( 'mysql' )
	);
	
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	
	// Send test email
	$sent = wp_mail( $test_email, $subject, nl2br( esc_html( $message ) ), $headers );
	
	if ( $sent ) {
		wp_send_json_success( array( 
			'message' => sprintf( __( 'تم إرسال الرسالة التجريبية بنجاح إلى %s. يرجى التحقق من صندوق الوارد (والبريد المزعج).', 'khutaa-theme' ), $test_email )
		) );
	} else {
		global $phpmailer;
		$error_msg = __( 'فشل إرسال الرسالة.', 'khutaa-theme' );
		
		if ( isset( $phpmailer ) && ! empty( $phpmailer->ErrorInfo ) ) {
			$error_msg .= ' ' . __( 'تفاصيل الخطأ:', 'khutaa-theme' ) . ' ' . esc_html( $phpmailer->ErrorInfo );
		}
		
		wp_send_json_error( array( 'message' => $error_msg ) );
	}
}
add_action( 'wp_ajax_khutaa_test_email', 'khutaa_ajax_test_email' );
