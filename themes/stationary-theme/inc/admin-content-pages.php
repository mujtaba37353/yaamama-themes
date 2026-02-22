<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function stationary_content_pages_config() {
	return array(
		'about-us'        => array( 'title' => 'من نحن', 'template' => 'default' ),
		'contact'         => array( 'title' => 'تواصل معنا', 'template' => 'default' ),
		'login'           => array( 'title' => 'تسجيل الدخول', 'template' => 'default' ),
		'signup'          => array( 'title' => 'إنشاء حساب', 'template' => 'default' ),
		'forget-password' => array( 'title' => 'نسيت كلمة المرور', 'template' => 'default' ),
		'reset-password'  => array( 'title' => 'إعادة تعيين كلمة المرور', 'template' => 'default' ),
		'privacy-policy'  => array( 'title' => 'سياسة الخصوصية', 'template' => 'default' ),
		'return-policy'   => array( 'title' => 'سياسة الاسترجاع', 'template' => 'default' ),
		'shipping-policy' => array( 'title' => 'سياسة الشحن', 'template' => 'default' ),
	);
}

function stationary_get_default_page_content( $slug ) {
	$dir = get_template_directory() . '/' . STATIONARY_DS . '/templates';
	$auth_map = array(
		'login'           => '<p>تسجيل الدخول لحسابك.</p>',
		'signup'          => '<p>إنشاء حساب جديد.</p>',
		'forget-password' => '<p>استعادة كلمة المرور.</p>',
		'reset-password'  => '<p>إعادة تعيين كلمة المرور.</p>',
	);
	if ( isset( $auth_map[ $slug ] ) ) {
		return $auth_map[ $slug ];
	}
	$map = array(
		'about-us'        => $dir . '/about-us/about-us.html',
		'contact'         => $dir . '/contact/contact.html',
		'privacy-policy'  => '',
		'return-policy'   => '',
		'shipping-policy' => '',
	);
	if ( empty( $map[ $slug ] ) || ! file_exists( $map[ $slug ] ) ) {
		if ( $slug === 'privacy-policy' ) {
			return '<h2>سياسة الخصوصية</h2><p>نحترم خصوصيتك ونلتزم بحماية بياناتك الشخصية وفق الأنظمة المعمول بها.</p>';
		}
		if ( $slug === 'return-policy' ) {
			return '<h2>سياسة الاسترجاع</h2><p>يمكنك استرجاع المنتجات خلال 14 يوم من الاستلام في حال عدم استخدامها.</p>';
		}
		if ( $slug === 'shipping-policy' ) {
			return '<h2>سياسة الشحن</h2><p>نوفر خدمة شحن لجميع مناطق المملكة. مدة التوصيل من 2 إلى 5 أيام عمل.</p>';
		}
		return '';
	}
	$html = file_get_contents( $map[ $slug ] );
	if ( $slug === 'about-us' ) {
		return '<p>نحن نؤمن أن الإلهام يبدأ من التفاصيل الصغيرة. لذلك نقدّم لك أدوات مكتبية تجمع بين الأناقة، الجودة، والعملية لتجعل كل يوم عمل أو دراسة تجربة أكثر تنظيمًا ومتعة. نختار منتجاتنا بعناية لتلهمك بالإبداع وتساعدك على التركيز وتحقيق أهدافك، لأننا نؤمن أن المكتب المرتّب هو بداية الأفكار الكبيرة.</p><p>رسالتنا هي دعم الإبداع وتنمية روح التنظيم لدى كل شخص، سواء كان طالبًا، موظفًا، أو صاحب فكرة يسعى لتحقيقها. نعمل على توفير أدوات مكتبية مبتكرة وعملية تجعل كل لحظة عمل أو دراسة أكثر راحة وإلهامًا، ونؤمن أن بيئة العمل المنظمة هي الخطوة الأولى نحو النجاح والإنتاجية المستمرة.</p>';
	}
	if ( $slug === 'contact' ) {
		return '<p>تواصل معنا عبر النموذج أو عبر البريد والهاتف.</p>';
	}
	$clean = preg_replace( '/<script[^>]*>[\s\S]*?<\/script>/i', '', $html );
	$clean = preg_replace( '/<style[^>]*>[\s\S]*?<\/style>/i', '', $clean );
	$clean = preg_replace( '/<head[^>]*>[\s\S]*?<\/head>/i', '', $clean );
	$clean = preg_replace( '/<y-navbar[^>]*>[\s\S]*?<\/y-navbar>/i', '', $clean );
	$clean = preg_replace( '/<y-footer[^>]*>[\s\S]*?<\/y-footer>/i', '', $clean );
	if ( preg_match( '/<main[^>]*>([\s\S]*?)<\/main>/i', $clean, $m ) ) {
		$clean = $m[1];
	}
	$clean = wp_kses_post( $clean );
	return $clean;
}

function stationary_create_or_update_pages( $force_update = false ) {
	$config  = stationary_content_pages_config();
	$created = 0;
	$updated = 0;
	foreach ( $config as $slug => $info ) {
		$page   = get_page_by_path( $slug );
		$content = stationary_get_default_page_content( $slug );
		if ( ! $page ) {
			$id = wp_insert_post( array(
				'post_title'   => $info['title'],
				'post_name'    => $slug,
				'post_content' => $content,
				'post_status'  => 'publish',
				'post_type'    => 'page',
			) );
			if ( $id && ! is_wp_error( $id ) ) {
				update_post_meta( $id, '_stationary_from_design', 1 );
				$created++;
			}
		} elseif ( $force_update ) {
			wp_update_post( array(
				'ID'           => $page->ID,
				'post_content' => $content ?: $page->post_content,
				'post_title'   => $info['title'],
			) );
			update_post_meta( $page->ID, '_stationary_from_design', 1 );
			$updated++;
		}
	}
	return array( 'created' => $created, 'updated' => $updated );
}

add_action( 'stationary_admin_render_stationary_content_pages', 'stationary_render_content_pages_admin' );

function stationary_render_content_pages_admin() {
	$message = '';
	$action  = isset( $_POST['stationary_pages_action'] ) ? sanitize_text_field( wp_unslash( $_POST['stationary_pages_action'] ) ) : '';
	if ( $action && current_user_can( 'manage_options' ) ) {
		$nonce = isset( $_POST['stationary_pages_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['stationary_pages_nonce'] ) ) : '';
		if ( ! wp_verify_nonce( $nonce, 'stationary_content_pages' ) ) {
			$message = __( 'خطأ في التحقق من الأمان.', 'stationary-theme' );
		} elseif ( $action === 'create_update' ) {
			$force = isset( $_POST['force_update'] ) && $_POST['force_update'] === '1';
			$r = stationary_create_or_update_pages( $force );
			$msg_parts = array();
			if ( $r['created'] > 0 ) {
				$msg_parts[] = sprintf( __( 'تم إنشاء %d صفحة', 'stationary-theme' ), $r['created'] );
			}
			if ( $r['updated'] > 0 ) {
				$msg_parts[] = sprintf( __( 'تم تحديث %d صفحة', 'stationary-theme' ), $r['updated'] );
			}
			if ( empty( $msg_parts ) && ! $force ) {
				$message = __( 'جميع الصفحات موجودة. فعّل "تحديث قسري" لتحديث المحتوى.', 'stationary-theme' );
			} else {
				$message = implode( '. ', $msg_parts ) ?: __( 'تم التنفيذ.', 'stationary-theme' );
			}
		}
	}
	if ( $message ) {
		$class = strpos( $message, 'خطأ' ) !== false ? 'notice-error' : 'notice-success';
		echo '<div class="notice ' . esc_attr( $class ) . ' is-dismissible"><p>' . esc_html( $message ) . '</p></div>';
	}

	$config  = stationary_content_pages_config();
	$pages   = array();
	foreach ( array_keys( $config ) as $slug ) {
		$p = get_page_by_path( $slug );
		if ( $p ) {
			$pages[] = array(
				'page'   => $p,
				'slug'   => $slug,
				'design' => (int) get_post_meta( $p->ID, '_stationary_from_design', true ) === 1,
			);
		}
	}
	?>
	<form method="post" style="margin: 16px 0;">
		<?php wp_nonce_field( 'stationary_content_pages', 'stationary_pages_nonce' ); ?>
		<input type="hidden" name="stationary_pages_action" value="create_update" />
		<label><input type="checkbox" name="force_update" value="1" /> <?php esc_html_e( 'تحديث قسري (تحديث المحتوى حتى لو الصفحات موجودة)', 'stationary-theme' ); ?></label><br><br>
		<button type="submit" class="button button-primary"><?php esc_html_e( 'إنشاء/تحديث كل صفحات الموقع', 'stationary-theme' ); ?></button>
	</form>

	<h2><?php esc_html_e( 'الصفحات الحالية', 'stationary-theme' ); ?></h2>
	<table class="wp-list-table widefat fixed striped">
		<thead>
			<tr>
				<th><?php esc_html_e( 'العنوان', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'Slug', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'الحالة', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'من التصميم', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'عرض', 'stationary-theme' ); ?></th>
				<th><?php esc_html_e( 'تعديل', 'stationary-theme' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php if ( empty( $pages ) ) : ?>
				<tr><td colspan="6"><?php esc_html_e( 'لا توجد صفحات. استخدم الزر أعلاه لإنشائها.', 'stationary-theme' ); ?></td></tr>
			<?php else : ?>
				<?php foreach ( $pages as $row ) : ?>
					<tr>
						<td><?php echo esc_html( $row['page']->post_title ); ?></td>
						<td><?php echo esc_html( $row['slug'] ); ?></td>
						<td><?php echo esc_html( $row['page']->post_status ); ?></td>
						<td><?php echo $row['design'] ? __( 'نعم', 'stationary-theme' ) : '—'; ?></td>
						<td><a href="<?php echo esc_url( get_permalink( $row['page'] ) ); ?>" target="_blank"><?php esc_html_e( 'عرض', 'stationary-theme' ); ?></a></td>
						<td><a href="<?php echo esc_url( get_edit_post_link( $row['page']->ID ) ); ?>"><?php esc_html_e( 'تعديل', 'stationary-theme' ); ?></a></td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>
	</table>
	<?php
}
