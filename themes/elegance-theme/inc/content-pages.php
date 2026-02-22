<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Elegance Theme: content-pages — منطق إنشاء/تحديث صفحات الموقع من التصميم.

/**
 * Pages to create/update from design (slug => title). Default content from design source.
 *
 * @return array
 */
function elegance_content_pages_config() {
	return array(
		'about-us'        => array(
			'title'   => 'من نحن',
			'content' => '<p>Lorem ipsum dolor sit amet consectetur. Pellentesque lacus pulvinar imperdiet cursus dui amet a amet. Enim eget etiam varius sed. Mattis arcu sed non tempus dui consequat pellentesque mattis. Orci sagittis eget nisi nunc quis sed. Iaculis malesuada id nisl pellentesque diam tempus iaculis pretium magna. Diam purus sit enim hendrerit. Facilisis ac aliquet pretium ullamcorper. In erat in purus nund.</p>',
		),
		'contact'         => array(
			'title'   => 'تواصل معنا',
			'content' => '<p>يمكنك التواصل معنا عبر البريد الإلكتروني أو الهاتف.</p>',
		),
		'login'           => array(
			'title'   => 'تسجيل الدخول',
			'content' => '',
		),
		'register'        => array(
			'title'   => 'إنشاء حساب',
			'content' => '',
		),
		'forgot-password'  => array(
			'title'   => 'نسيت كلمة المرور',
			'content' => '',
		),
		'reset-password'   => array(
			'title'   => 'إعادة تعيين كلمة المرور',
			'content' => '',
		),
		'my-account'       => array(
			'title'   => 'حسابي',
			'content' => '',
		),
		'logout'           => array(
			'title'   => 'تسجيل الخروج',
			'content' => '',
		),
		'favorites'        => array(
			'title'   => 'المفضلة',
			'content' => '',
		),
		'shipping-policy'  => array(
			'title'   => 'سياسة الشحن',
			'content' => '<p>نوضح فيما يلي سياسة الشحن المعتمدة لدينا.</p>',
		),
		'return-policy'    => array(
			'title'   => 'سياسة الاسترجاع',
			'content' => '<p>نوضح فيما يلي سياسة الاسترجاع والاستبدال.</p>',
		),
		'privacy-policy'   => array(
			'title'   => 'سياسة الخصوصية',
			'content' => '<p>نوضح فيما يلي سياسة الخصوصية وحماية البيانات.</p>',
		),
	);
}

/**
 * Create or update a single page by slug.
 *
 * @param string $slug    Page slug.
 * @param string $title   Page title.
 * @param string $content Post content.
 * @return int|WP_Error Post ID or error.
 */
function elegance_create_or_update_page( $slug, $title, $content ) {
	$page = get_page_by_path( $slug, OBJECT, 'page' );
	$data = array(
		'post_title'   => sanitize_text_field( $title ),
		'post_name'    => sanitize_title( $slug ),
		'post_content' => wp_kses_post( $content ),
		'post_status'  => 'publish',
		'post_type'    => 'page',
		'post_author'  => get_current_user_id(),
	);
	if ( $page ) {
		$data['ID'] = $page->ID;
		$id         = wp_update_post( $data, true );
	} else {
		$id = wp_insert_post( $data, true );
	}
	return $id;
}

add_action( 'elegance_admin_render_elegance_content_pages', 'elegance_render_content_pages_admin' );

function elegance_render_content_pages_admin() {
	$message = '';
	if ( isset( $_POST['elegance_create_pages'] ) && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['elegance_admin_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['elegance_admin_nonce'] ) ) : '';
		if ( wp_verify_nonce( $nonce, 'elegance_admin_elegance_content_pages' ) ) {
			$config = elegance_content_pages_config();
			$done   = 0;
			$slug_templates = array(
				'login'           => 'page-login.php',
				'register'        => 'page-register.php',
				'forgot-password' => 'page-forgot-password.php',
				'reset-password'  => 'page-reset-password.php',
				'my-account'      => 'page-my-account.php',
				'logout'          => 'page-logout.php',
				'favorites'       => 'page-favorites.php',
			);
			foreach ( $config as $slug => $item ) {
				$id = elegance_create_or_update_page( $slug, $item['title'], $item['content'] );
				if ( ! is_wp_error( $id ) && isset( $slug_templates[ $slug ] ) ) {
					update_post_meta( $id, '_wp_page_template', $slug_templates[ $slug ] );
				}
				if ( ! is_wp_error( $id ) ) {
					$done++;
				}
			}
			$message = $done > 0
				? sprintf( /* translators: %d: number of pages */ esc_html__( 'تم إنشاء/تحديث %d صفحة بنجاح.', 'elegance' ), $done )
				: esc_html__( 'لم يتم تحديث أي صفحة.', 'elegance' );
		} else {
			$message = esc_html__( 'خطأ في التحقق. حاول مرة أخرى.', 'elegance' );
		}
	}

	$config  = elegance_content_pages_config();
	$current = array();
	foreach ( array_keys( $config ) as $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		$current[ $slug ] = $page ? get_permalink( $page ) : '';
	}
	?>
	<?php if ( $message ) : ?>
		<div class="notice notice-success is-dismissible"><p><?php echo esc_html( $message ); ?></p></div>
	<?php endif; ?>

	<table class="widefat striped" style="max-width: 600px;">
		<thead>
			<tr>
				<th><?php esc_html_e( 'الصفحة', 'elegance' ); ?></th>
				<th><?php esc_html_e( 'الحالة', 'elegance' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $config as $slug => $item ) : ?>
				<tr>
					<td><?php echo esc_html( $item['title'] ); ?> (<?php echo esc_html( $slug ); ?>)</td>
					<td>
						<?php if ( ! empty( $current[ $slug ] ) ) : ?>
							<a href="<?php echo esc_url( $current[ $slug ] ); ?>" target="_blank"><?php esc_html_e( 'معروضة', 'elegance' ); ?></a>
						<?php else : ?>
							<?php esc_html_e( 'غير منشأة', 'elegance' ); ?>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

	<form method="post" style="margin-top: 16px;">
		<?php wp_nonce_field( 'elegance_admin_elegance_content_pages', 'elegance_admin_nonce' ); ?>
		<p>
			<button type="submit" name="elegance_create_pages" class="button button-primary">
				<?php esc_html_e( 'إنشاء/تحديث كل صفحات الموقع', 'elegance' ); ?>
			</button>
		</p>
	</form>
	<?php
}
