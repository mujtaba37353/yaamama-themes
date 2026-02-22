<?php
/**
 * Beauty Care Theme — إدارة الصفحات.
 *
 * @package beauty-care-theme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function beauty_care_pages_manifest() {
	return array(
		/* المتجر (WooCommerce) */
		'shop'            => array( 'المتجر', 'صفحة عرض المنتجات' ),
		'cart'            => array( 'السلة', 'سلة المشتريات' ),
		'wishlist'        => array( 'المفضلة', 'قائمة المنتجات المفضلة' ),
		'checkout'        => array( 'الدفع', 'صفحة إتمام الطلب' ),
		'my-account'      => array( 'حسابي', 'تسجيل الدخول ولوحة التحكم والطلبات' ),
		/* المصادقة */
		'login'           => array( 'تسجيل الدخول', 'صفحة تسجيل الدخول' ),
		'signup'          => array( 'إنشاء حساب', 'صفحة إنشاء حساب جديد' ),
		'forget-password' => array( 'نسيت كلمة المرور', 'صفحة استعادة كلمة المرور' ),
		'reset-password'  => array( 'إعادة تعيين كلمة المرور', 'صفحة إعادة إنشاء كلمة المرور' ),
		/* المحتوى */
		'about-us'        => array( 'من نحن', 'صفحة من نحن' ),
		'contact'         => array( 'تواصل معنا', 'صفحة تواصل معنا' ),
		'privacy-policy'  => array( 'سياسة الخصوصية', 'سياسة الخصوصية' ),
		'return-policy'   => array( 'سياسة الاسترجاع', 'سياسة الاسترجاع' ),
		'shipping-policy' => array( 'سياسة الشحن', 'سياسة الشحن' ),
	);
}

function beauty_care_register_content_admin() {
	add_menu_page(
		'المحتوى',
		'المحتوى',
		'manage_options',
		'beauty-care-content',
		'beauty_care_render_content_landing',
		'dashicons-admin-page',
		30
	);
	add_submenu_page(
		'beauty-care-content',
		'الصفحات',
		'الصفحات',
		'manage_options',
		'beauty-care-pages',
		'beauty_care_render_pages_admin'
	);
}
add_action( 'admin_menu', 'beauty_care_register_content_admin' );

function beauty_care_render_content_landing() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	wp_safe_redirect( admin_url( 'admin.php?page=beauty-care-pages' ) );
	exit;
}

function beauty_care_render_pages_admin() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	$pages = beauty_care_pages_manifest();
	?>
	<div class="wrap">
		<h1>الصفحات</h1>
		<p>إنشاء أو تحديث الصفحات المطلوبة للمتجر.</p>
		<?php if ( isset( $_GET['beauty_care_synced'] ) ) : ?>
			<div class="notice notice-success is-dismissible"><p>تم تحديث الصفحات بنجاح.</p></div>
		<?php endif; ?>
		<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin: 1em 0;">
			<?php wp_nonce_field( 'beauty_care_sync_pages', 'beauty_care_sync_nonce' ); ?>
			<input type="hidden" name="action" value="beauty_care_sync_pages">
			<?php submit_button( 'إنشاء / تحديث الصفحات', 'primary' ); ?>
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
					$title = $info[0];
					$desc  = $info[1] ?? '';
					$page  = get_page_by_path( $slug );
					?>
					<tr>
						<td><?php echo esc_html( $title ); ?></td>
						<td><?php echo esc_html( $desc ); ?></td>
						<td>/<?php echo esc_html( $slug ); ?></td>
						<td>
							<?php if ( $page ) : ?>
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

function beauty_care_sync_pages_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized' );
	}
	check_admin_referer( 'beauty_care_sync_pages', 'beauty_care_sync_nonce' );
	$pages     = beauty_care_pages_manifest();
	$templates = array(
		'shop'            => '',
		'cart'            => 'page-cart.php',
		'wishlist'        => 'page-wishlist.php',
		'checkout'        => 'page-checkout.php',
		'my-account'      => 'page-my-account.php',
		'login'           => 'page-login.php',
		'signup'          => 'page-signup.php',
		'forget-password' => 'page-forget-password.php',
		'reset-password'  => 'page-reset-password.php',
		'about-us'        => 'page-about-us.php',
		'contact'         => 'page-contact.php',
		'privacy-policy'  => 'page-policies.php',
		'return-policy'   => 'page-policies.php',
		'shipping-policy' => 'page-policies.php',
	);
	$shortcodes = array(
		'cart'       => '<!-- wp:shortcode -->[woocommerce_cart]<!-- /wp:shortcode -->',
		'checkout'   => '<!-- wp:shortcode -->[woocommerce_checkout]<!-- /wp:shortcode -->',
		'my-account' => '<!-- wp:shortcode -->[woocommerce_my_account]<!-- /wp:shortcode -->',
	);
	foreach ( $pages as $slug => $info ) {
		$title = $info[0];
		$page  = get_page_by_path( $slug );
		$args  = array(
			'post_title'  => $title,
			'post_name'   => $slug,
			'post_status' => 'publish',
			'post_type'   => 'page',
			'post_content'=> isset( $shortcodes[ $slug ] ) ? $shortcodes[ $slug ] : '',
		);
		if ( $page ) {
			$needs = ( $page->post_title !== $title || 'publish' !== $page->post_status );
			if ( isset( $shortcodes[ $slug ] ) && $page->post_content !== $shortcodes[ $slug ] ) {
				$needs = true;
			}
			if ( $needs ) {
				$upd = array( 'ID' => $page->ID, 'post_title' => $title, 'post_status' => 'publish' );
				if ( isset( $shortcodes[ $slug ] ) ) {
					$upd['post_content'] = $shortcodes[ $slug ];
				}
				wp_update_post( $upd );
			}
			if ( isset( $templates[ $slug ] ) && '' !== $templates[ $slug ] ) {
				update_post_meta( $page->ID, '_wp_page_template', $templates[ $slug ] );
			}
		} else {
			if ( ! isset( $shortcodes[ $slug ] ) ) {
				unset( $args['post_content'] );
			}
			$new_id = wp_insert_post( $args );
			if ( $new_id && isset( $templates[ $slug ] ) && '' !== $templates[ $slug ] ) {
				update_post_meta( $new_id, '_wp_page_template', $templates[ $slug ] );
			}
		}
	}
	// ربط صفحات WooCommerce
	if ( function_exists( 'wc_get_page_id' ) ) {
		$shop_p    = get_page_by_path( 'shop' );
		$cart_p    = get_page_by_path( 'cart' );
		$check_p   = get_page_by_path( 'checkout' );
		$account_p = get_page_by_path( 'my-account' );
		if ( $shop_p ) {
			update_option( 'woocommerce_shop_page_id', $shop_p->ID );
		}
		if ( $cart_p ) {
			update_option( 'woocommerce_cart_page_id', $cart_p->ID );
		}
		if ( $check_p ) {
			update_option( 'woocommerce_checkout_page_id', $check_p->ID );
		}
		if ( $account_p ) {
			update_option( 'woocommerce_myaccount_page_id', $account_p->ID );
		}
	}
	if ( function_exists( 'beauty_care_populate_policy_pages' ) ) {
		beauty_care_populate_policy_pages();
	}
	wp_safe_redirect( add_query_arg( 'beauty_care_synced', '1', admin_url( 'admin.php?page=beauty-care-pages' ) ) );
	exit;
}
add_action( 'admin_post_beauty_care_sync_pages', 'beauty_care_sync_pages_handler' );
