<?php
/**
 * Booking System — CPT + Admin + Frontend handlers
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register Booking CPT
 */
function beauty_register_booking_post_type() {
	$labels = array(
		'name'                  => _x( 'الحجوزات', 'Post type general name', 'beauty-time-theme' ),
		'singular_name'         => _x( 'حجز', 'Post type singular name', 'beauty-time-theme' ),
		'menu_name'             => _x( 'الحجوزات', 'Admin Menu text', 'beauty-time-theme' ),
		'name_admin_bar'        => _x( 'حجز', 'Add New on Toolbar', 'beauty-time-theme' ),
		'add_new'               => __( 'إضافة حجز', 'beauty-time-theme' ),
		'add_new_item'          => __( 'إضافة حجز جديد', 'beauty-time-theme' ),
		'new_item'              => __( 'حجز جديد', 'beauty-time-theme' ),
		'edit_item'             => __( 'تعديل الحجز', 'beauty-time-theme' ),
		'view_item'             => __( 'عرض الحجز', 'beauty-time-theme' ),
		'all_items'             => __( 'جميع الحجوزات', 'beauty-time-theme' ),
		'search_items'          => __( 'البحث في الحجوزات', 'beauty-time-theme' ),
		'not_found'             => __( 'لا توجد حجوزات.', 'beauty-time-theme' ),
		'not_found_in_trash'    => __( 'لا توجد حجوزات في سلة المحذوفات.', 'beauty-time-theme' ),
	);

	$args = array(
		'labels'             => $labels,
		'public'             => false,
		'publicly_queryable' => false,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => false,
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'      => false,
		'menu_position'      => 30,
		'menu_icon'          => 'dashicons-calendar-alt',
		'supports'           => array( 'title' ),
		'show_in_rest'       => false,
	);

	register_post_type( 'booking', $args );
}
add_action( 'init', 'beauty_register_booking_post_type' );

/**
 * Register booking statuses
 */
function beauty_register_booking_statuses() {
	register_post_status(
		'new',
		array(
			'label'                     => _x( 'جديد', 'booking status', 'beauty-time-theme' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'جديد <span class="count">(%s)</span>', 'جديد <span class="count">(%s)</span>', 'beauty-time-theme' ),
		)
	);

	register_post_status(
		'confirmed',
		array(
			'label'                     => _x( 'مؤكد', 'booking status', 'beauty-time-theme' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'مؤكد <span class="count">(%s)</span>', 'مؤكد <span class="count">(%s)</span>', 'beauty-time-theme' ),
		)
	);

	register_post_status(
		'completed',
		array(
			'label'                     => _x( 'مكتمل', 'booking status', 'beauty-time-theme' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'مكتمل <span class="count">(%s)</span>', 'مكتمل <span class="count">(%s)</span>', 'beauty-time-theme' ),
		)
	);

	register_post_status(
		'cancelled',
		array(
			'label'                     => _x( 'ملغي', 'booking status', 'beauty-time-theme' ),
			'public'                    => false,
			'exclude_from_search'       => false,
			'show_in_admin_all_list'    => true,
			'show_in_admin_status_list' => true,
			'label_count'               => _n_noop( 'ملغي <span class="count">(%s)</span>', 'ملغي <span class="count">(%s)</span>', 'beauty-time-theme' ),
		)
	);
}
add_action( 'init', 'beauty_register_booking_statuses' );

/**
 * Add meta boxes for booking fields
 */
function beauty_add_booking_meta_boxes() {
	add_meta_box(
		'booking_details',
		__( 'تفاصيل الحجز', 'beauty-time-theme' ),
		'beauty_booking_details_meta_box',
		'booking',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'beauty_add_booking_meta_boxes' );

/**
 * Booking details meta box
 */
function beauty_booking_details_meta_box( $post ) {
	wp_nonce_field( 'beauty_save_booking_meta', 'beauty_booking_meta_nonce' );

	$customer_name = get_post_meta( $post->ID, '_booking_customer_name', true );
	$phone         = get_post_meta( $post->ID, '_booking_phone', true );
	$email         = get_post_meta( $post->ID, '_booking_email', true );
	$services      = get_post_meta( $post->ID, '_booking_services', true );
	$date          = get_post_meta( $post->ID, '_booking_date', true );
	$time          = get_post_meta( $post->ID, '_booking_time', true );
	$branch        = get_post_meta( $post->ID, '_booking_branch', true );
	$status        = get_post_meta( $post->ID, '_booking_status', true );
	$notes         = get_post_meta( $post->ID, '_booking_notes', true );
	$order_id      = get_post_meta( $post->ID, '_booking_order_id', true );

	if ( ! $status ) {
		$status = 'new';
	}
	?>
	<table class="form-table">
		<tr>
			<th><label for="booking_customer_name"><?php esc_html_e( 'اسم العميل', 'beauty-time-theme' ); ?></label></th>
			<td><input type="text" id="booking_customer_name" name="booking_customer_name" value="<?php echo esc_attr( $customer_name ); ?>" class="regular-text" required /></td>
		</tr>
		<tr>
			<th><label for="booking_phone"><?php esc_html_e( 'رقم الجوال', 'beauty-time-theme' ); ?></label></th>
			<td><input type="tel" id="booking_phone" name="booking_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" required /></td>
		</tr>
		<tr>
			<th><label for="booking_email"><?php esc_html_e( 'البريد الإلكتروني', 'beauty-time-theme' ); ?></label></th>
			<td><input type="email" id="booking_email" name="booking_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="booking_services"><?php esc_html_e( 'الخدمة/الخدمات', 'beauty-time-theme' ); ?></label></th>
			<td>
				<input type="text" id="booking_services" name="booking_services" value="<?php echo esc_attr( $services ); ?>" class="regular-text" required />
				<p class="description"><?php esc_html_e( 'مثال: المكياج، العناية بالأظافر', 'beauty-time-theme' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label for="booking_date"><?php esc_html_e( 'التاريخ', 'beauty-time-theme' ); ?></label></th>
			<td><input type="date" id="booking_date" name="booking_date" value="<?php echo esc_attr( $date ); ?>" class="regular-text" required /></td>
		</tr>
		<tr>
			<th><label for="booking_time"><?php esc_html_e( 'الوقت', 'beauty-time-theme' ); ?></label></th>
			<td><input type="text" id="booking_time" name="booking_time" value="<?php echo esc_attr( $time ); ?>" class="regular-text" placeholder="10:00 ص" required /></td>
		</tr>
		<tr>
			<th><label for="booking_branch"><?php esc_html_e( 'الفرع', 'beauty-time-theme' ); ?></label></th>
			<td><input type="text" id="booking_branch" name="booking_branch" value="<?php echo esc_attr( $branch ); ?>" class="regular-text" /></td>
		</tr>
		<tr>
			<th><label for="booking_status"><?php esc_html_e( 'الحالة', 'beauty-time-theme' ); ?></label></th>
			<td>
				<select id="booking_status" name="booking_status" required>
					<option value="new" <?php selected( $status, 'new' ); ?>><?php esc_html_e( 'جديد', 'beauty-time-theme' ); ?></option>
					<option value="confirmed" <?php selected( $status, 'confirmed' ); ?>><?php esc_html_e( 'مؤكد', 'beauty-time-theme' ); ?></option>
					<option value="completed" <?php selected( $status, 'completed' ); ?>><?php esc_html_e( 'مكتمل', 'beauty-time-theme' ); ?></option>
					<option value="cancelled" <?php selected( $status, 'cancelled' ); ?>><?php esc_html_e( 'ملغي', 'beauty-time-theme' ); ?></option>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="booking_notes"><?php esc_html_e( 'الملاحظات', 'beauty-time-theme' ); ?></label></th>
			<td><textarea id="booking_notes" name="booking_notes" rows="4" class="large-text"><?php echo esc_textarea( $notes ); ?></textarea></td>
		</tr>
		<?php if ( $order_id && class_exists( 'WooCommerce' ) ) : ?>
		<tr>
			<th><label><?php esc_html_e( 'ربط الطلب', 'beauty-time-theme' ); ?></label></th>
			<td>
				<?php
				$order = wc_get_order( $order_id );
				if ( $order ) {
					printf(
						'<a href="%s">%s #%s</a>',
						esc_url( admin_url( 'post.php?post=' . $order_id . '&action=edit' ) ),
						esc_html__( 'طلب', 'beauty-time-theme' ),
						esc_html( $order->get_order_number() )
					);
				} else {
					echo esc_html( $order_id );
				}
				?>
			</td>
		</tr>
		<?php endif; ?>
	</table>
	<?php
}

/**
 * Save booking meta
 */
function beauty_save_booking_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( ! isset( $_POST['beauty_booking_meta_nonce'] ) || ! wp_verify_nonce( $_POST['beauty_booking_meta_nonce'], 'beauty_save_booking_meta' ) ) {
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if ( get_post_type( $post_id ) !== 'booking' ) {
		return;
	}

	$fields = array(
		'booking_customer_name' => '_booking_customer_name',
		'booking_phone'          => '_booking_phone',
		'booking_email'          => '_booking_email',
		'booking_services'      => '_booking_services',
		'booking_date'          => '_booking_date',
		'booking_time'          => '_booking_time',
		'booking_branch'        => '_booking_branch',
		'booking_status'        => '_booking_status',
		'booking_notes'         => '_booking_notes',
	);

	foreach ( $fields as $field => $meta_key ) {
		if ( isset( $_POST[ $field ] ) ) {
			$value = sanitize_text_field( $_POST[ $field ] );
			if ( 'booking_email' === $field ) {
				$value = sanitize_email( $_POST[ $field ] );
			}
			if ( $field === 'booking_notes' ) {
				$value = sanitize_textarea_field( $_POST[ $field ] );
			}
			update_post_meta( $post_id, $meta_key, $value );
		}
	}

	// Update post status
	if ( isset( $_POST['booking_status'] ) ) {
		$status = sanitize_text_field( $_POST['booking_status'] );
		wp_update_post(
			array(
				'ID'          => $post_id,
				'post_status' => $status,
			)
		);
	}

	// Update post title
	if ( isset( $_POST['booking_customer_name'] ) ) {
		$title = sprintf(
			__( 'حجز - %s - %s', 'beauty-time-theme' ),
			sanitize_text_field( $_POST['booking_customer_name'] ),
			isset( $_POST['booking_date'] ) ? sanitize_text_field( $_POST['booking_date'] ) : ''
		);
		wp_update_post(
			array(
				'ID'         => $post_id,
			'post_title' => $title,
			)
		);
	}
}
add_action( 'save_post', 'beauty_save_booking_meta' );

/**
 * Add custom columns to booking list
 */
function beauty_booking_columns( $columns ) {
	$new_columns = array();
	$new_columns['cb'] = $columns['cb'];
	$new_columns['title'] = __( 'الحجز', 'beauty-time-theme' );
	$new_columns['customer'] = __( 'العميل', 'beauty-time-theme' );
	$new_columns['phone'] = __( 'الجوال', 'beauty-time-theme' );
	$new_columns['services'] = __( 'الخدمات', 'beauty-time-theme' );
	$new_columns['date_time'] = __( 'التاريخ والوقت', 'beauty-time-theme' );
	$new_columns['status'] = __( 'الحالة', 'beauty-time-theme' );
	$new_columns['date'] = $columns['date'];
	return $new_columns;
}
add_filter( 'manage_booking_posts_columns', 'beauty_booking_columns' );

/**
 * Populate custom columns
 */
function beauty_booking_column_content( $column, $post_id ) {
	switch ( $column ) {
		case 'customer':
			echo esc_html( get_post_meta( $post_id, '_booking_customer_name', true ) );
			break;
		case 'phone':
			echo esc_html( get_post_meta( $post_id, '_booking_phone', true ) );
			break;
		case 'services':
			echo esc_html( get_post_meta( $post_id, '_booking_services', true ) );
			break;
		case 'date_time':
			$date = get_post_meta( $post_id, '_booking_date', true );
			$time = get_post_meta( $post_id, '_booking_time', true );
			if ( $date ) {
				echo esc_html( $date );
			}
			if ( $time ) {
				echo ' ' . esc_html( $time );
			}
			break;
		case 'status':
			$status = get_post_meta( $post_id, '_booking_status', true );
			if ( ! $status ) {
				$status = 'new';
			}
			$status_labels = array(
				'new'       => __( 'جديد', 'beauty-time-theme' ),
				'confirmed' => __( 'مؤكد', 'beauty-time-theme' ),
				'completed' => __( 'مكتمل', 'beauty-time-theme' ),
				'cancelled' => __( 'ملغي', 'beauty-time-theme' ),
			);
			$status_colors = array(
				'new'       => '#0073aa',
				'confirmed' => '#46b450',
				'completed' => '#00a0d2',
				'cancelled' => '#dc3232',
			);
			$label = isset( $status_labels[ $status ] ) ? $status_labels[ $status ] : $status;
			$color = isset( $status_colors[ $status ] ) ? $status_colors[ $status ] : '#666';
			printf( '<span style="color: %s; font-weight: bold;">%s</span>', esc_attr( $color ), esc_html( $label ) );
			break;
	}
}
add_action( 'manage_booking_posts_custom_column', 'beauty_booking_column_content', 10, 2 );

/**
 * Make columns sortable
 */
function beauty_booking_sortable_columns( $columns ) {
	$columns['customer'] = 'customer';
	$columns['date_time'] = 'date_time';
	$columns['status'] = 'status';
	return $columns;
}
add_filter( 'manage_edit-booking_sortable_columns', 'beauty_booking_sortable_columns' );

/**
 * Handle AJAX booking submission
 */
function beauty_ajax_create_booking() {
	check_ajax_referer( 'beauty_booking_nonce', 'nonce' );

	$customer_name = isset( $_POST['customer_name'] ) ? sanitize_text_field( $_POST['customer_name'] ) : '';
	$phone         = isset( $_POST['phone'] ) ? sanitize_text_field( $_POST['phone'] ) : '';
	$services      = isset( $_POST['services'] ) ? sanitize_text_field( $_POST['services'] ) : '';
	$date          = isset( $_POST['date'] ) ? sanitize_text_field( $_POST['date'] ) : '';
	$time          = isset( $_POST['time'] ) ? sanitize_text_field( $_POST['time'] ) : '';
	$branch        = isset( $_POST['branch'] ) ? sanitize_text_field( $_POST['branch'] ) : '';
	$payment_method = isset( $_POST['payment_method'] ) ? sanitize_text_field( $_POST['payment_method'] ) : 'cash';
	$notes         = isset( $_POST['notes'] ) ? sanitize_textarea_field( $_POST['notes'] ) : '';
	$create_account = ! empty( $_POST['create_account'] );
	$account_email  = isset( $_POST['account_email'] ) ? sanitize_email( $_POST['account_email'] ) : '';
	$account_password = isset( $_POST['account_password'] ) ? wp_unslash( $_POST['account_password'] ) : '';

	// Validation
	if ( empty( $customer_name ) || empty( $phone ) || empty( $services ) || empty( $date ) || empty( $time ) ) {
		wp_send_json_error( array( 'message' => __( 'يرجى ملء جميع الحقول المطلوبة.', 'beauty-time-theme' ) ) );
	}

	if ( $create_account && ! is_user_logged_in() ) {
		if ( empty( $account_email ) || empty( $account_password ) ) {
			wp_send_json_error( array( 'message' => __( 'يرجى إدخال البريد الإلكتروني وكلمة المرور لإنشاء الحساب.', 'beauty-time-theme' ) ) );
		}
		if ( ! is_email( $account_email ) ) {
			wp_send_json_error( array( 'message' => __( 'البريد الإلكتروني غير صالح.', 'beauty-time-theme' ) ) );
		}
		if ( email_exists( $account_email ) ) {
			wp_send_json_error( array( 'message' => __( 'البريد الإلكتروني مستخدم بالفعل. يرجى تسجيل الدخول.', 'beauty-time-theme' ) ) );
		}
	}

	// Validate date
	$booking_date = strtotime( $date );
	$today = strtotime( 'today' );
	if ( $booking_date < $today ) {
		wp_send_json_error( array( 'message' => __( 'التاريخ المحدد غير صالح.', 'beauty-time-theme' ) ) );
	}

	// Validate time against working hours
	$weekly_hours = beauty_get_weekly_working_hours();
	$time_minutes = beauty_time_to_minutes( $time );
	$weekday      = (int) date( 'w', strtotime( $date ) );
	$day_hours    = isset( $weekly_hours[ $weekday ] ) ? $weekly_hours[ $weekday ] : array();

	if ( ! $time_minutes || empty( $day_hours['enabled'] ) ) {
		wp_send_json_error( array( 'message' => __( 'الوقت المحدد غير متاح.', 'beauty-time-theme' ) ) );
	}

	$start_minutes = beauty_time_to_minutes( $day_hours['start'] ?? '' );
	$end_minutes   = beauty_time_to_minutes( $day_hours['end'] ?? '' );
	if ( ! $start_minutes || ! $end_minutes || $start_minutes >= $end_minutes ) {
		wp_send_json_error( array( 'message' => __( 'الوقت المحدد غير متاح.', 'beauty-time-theme' ) ) );
	}

	if ( $time_minutes < $start_minutes || $time_minutes >= $end_minutes ) {
		wp_send_json_error( array( 'message' => __( 'الوقت المحدد غير متاح.', 'beauty-time-theme' ) ) );
	}

	// Determine status based on payment method
	$status = ( 'cash' === $payment_method ) ? 'new' : 'new';

	// Create account if requested
	$user_id = 0;
	if ( $create_account && ! is_user_logged_in() ) {
		$base_username = sanitize_user( preg_replace( '/@.*/', '', $account_email ), true );
		if ( empty( $base_username ) ) {
			$base_username = 'user';
		}
		$username = $base_username;
		$counter  = 1;
		while ( username_exists( $username ) ) {
			$username = $base_username . $counter;
			$counter++;
		}

		$user_id = wp_create_user( $username, $account_password, $account_email );
		if ( is_wp_error( $user_id ) ) {
			wp_send_json_error( array( 'message' => __( 'تعذر إنشاء الحساب. يرجى المحاولة لاحقًا.', 'beauty-time-theme' ) ) );
		}

		wp_update_user(
			array(
				'ID'           => $user_id,
				'first_name'   => $customer_name,
				'display_name' => $customer_name,
			)
		);
		update_user_meta( $user_id, 'billing_phone', $phone );
		update_user_meta( $user_id, 'billing_first_name', $customer_name );
		wp_set_current_user( $user_id );
		wp_set_auth_cookie( $user_id, true );
	}

	if ( is_user_logged_in() ) {
		$user_id = get_current_user_id();
	}
	if ( $user_id && empty( $account_email ) ) {
		$user = get_user_by( 'id', $user_id );
		if ( $user ) {
			$account_email = $user->user_email;
		}
	}

	// Create booking post
	$post_id = wp_insert_post(
		array(
			'post_type'   => 'booking',
			'post_status' => $status,
			'post_title'  => sprintf( __( 'حجز - %s - %s', 'beauty-time-theme' ), $customer_name, $date ),
		)
	);

	if ( is_wp_error( $post_id ) ) {
		wp_send_json_error( array( 'message' => __( 'حدث خطأ أثناء إنشاء الحجز.', 'beauty-time-theme' ) ) );
	}

	// Save meta
	update_post_meta( $post_id, '_booking_customer_name', $customer_name );
	update_post_meta( $post_id, '_booking_phone', $phone );
	update_post_meta( $post_id, '_booking_email', $account_email );
	update_post_meta( $post_id, '_booking_services', $services );
	update_post_meta( $post_id, '_booking_date', $date );
	update_post_meta( $post_id, '_booking_time', $time );
	update_post_meta( $post_id, '_booking_branch', $branch );
	update_post_meta( $post_id, '_booking_status', $status );
	update_post_meta( $post_id, '_booking_notes', $notes );
	update_post_meta( $post_id, '_booking_payment_method', $payment_method );

	// If user is logged in, link to user
	if ( $user_id ) {
		update_post_meta( $post_id, '_booking_user_id', $user_id );
	}

	// If payment is online, redirect to checkout (Phase E: can be enhanced)
	$redirect_url = '';
	if ( 'cash' !== $payment_method && class_exists( 'WooCommerce' ) ) {
		// TODO: Add service to cart and redirect to checkout
		// For now, just create booking
	}

	wp_send_json_success(
		array(
			'booking_id'   => $post_id,
			'message'      => __( 'تم إنشاء الحجز بنجاح!', 'beauty-time-theme' ),
			'redirect_url' => $redirect_url ? $redirect_url : home_url( '/booking-success?booking_id=' . $post_id ),
		)
	);
}
add_action( 'wp_ajax_beauty_create_booking', 'beauty_ajax_create_booking' );
add_action( 'wp_ajax_nopriv_beauty_create_booking', 'beauty_ajax_create_booking' );

/**
 * Add status filter dropdown
 */
function beauty_booking_status_filter() {
	global $typenow;
	if ( 'booking' !== $typenow ) {
		return;
	}

	$statuses = array(
		'new'       => __( 'جديد', 'beauty-time-theme' ),
		'confirmed' => __( 'مؤكد', 'beauty-time-theme' ),
		'completed' => __( 'مكتمل', 'beauty-time-theme' ),
		'cancelled' => __( 'ملغي', 'beauty-time-theme' ),
	);

	$current_status = isset( $_GET['booking_status'] ) ? sanitize_text_field( $_GET['booking_status'] ) : '';
	?>
	<select name="booking_status" id="booking_status">
		<option value=""><?php esc_html_e( 'جميع الحالات', 'beauty-time-theme' ); ?></option>
		<?php foreach ( $statuses as $status => $label ) : ?>
			<option value="<?php echo esc_attr( $status ); ?>" <?php selected( $current_status, $status ); ?>>
				<?php echo esc_html( $label ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php
}
add_action( 'restrict_manage_posts', 'beauty_booking_status_filter' );

/**
 * Filter bookings by status
 */
function beauty_booking_status_filter_query( $query ) {
	global $pagenow, $typenow;

	if ( 'edit.php' !== $pagenow || 'booking' !== $typenow ) {
		return;
	}

	if ( isset( $_GET['booking_status'] ) && ! empty( $_GET['booking_status'] ) ) {
		$status = sanitize_text_field( $_GET['booking_status'] );
		$query->set( 'meta_key', '_booking_status' );
		$query->set( 'meta_value', $status );
	}
}
add_filter( 'parse_query', 'beauty_booking_status_filter_query' );

/**
 * Add quick status change to row actions
 */
function beauty_booking_row_actions( $actions, $post ) {
	if ( 'booking' !== $post->post_type ) {
		return $actions;
	}

	$status = get_post_meta( $post->ID, '_booking_status', true );
	if ( ! $status ) {
		$status = 'new';
	}

	$statuses = array(
		'new'       => __( 'جديد', 'beauty-time-theme' ),
		'confirmed' => __( 'مؤكد', 'beauty-time-theme' ),
		'completed' => __( 'مكتمل', 'beauty-time-theme' ),
		'cancelled' => __( 'ملغي', 'beauty-time-theme' ),
	);

	foreach ( $statuses as $status_key => $status_label ) {
		if ( $status_key === $status ) {
			continue;
		}
		$actions[ 'set_status_' . $status_key ] = sprintf(
			'<a href="%s" class="beauty-quick-status-change" data-status="%s" data-booking-id="%d">%s</a>',
			wp_nonce_url( admin_url( 'admin-ajax.php?action=beauty_quick_change_status&booking_id=' . $post->ID . '&status=' . $status_key ), 'beauty_quick_status_' . $post->ID ),
			esc_attr( $status_key ),
			$post->ID,
			esc_html( $status_label )
		);
	}

	return $actions;
}
add_filter( 'post_row_actions', 'beauty_booking_row_actions', 10, 2 );

/**
 * Handle quick status change via AJAX
 */
function beauty_ajax_quick_change_status() {
	$booking_id = isset( $_GET['booking_id'] ) ? absint( $_GET['booking_id'] ) : 0;
	$status     = isset( $_GET['status'] ) ? sanitize_text_field( $_GET['status'] ) : '';

	if ( ! $booking_id || ! $status ) {
		wp_die( __( 'بيانات غير صحيحة.', 'beauty-time-theme' ) );
	}

	check_admin_referer( 'beauty_quick_status_' . $booking_id );

	if ( ! current_user_can( 'edit_post', $booking_id ) ) {
		wp_die( __( 'ليس لديك صلاحية لتعديل هذا الحجز.', 'beauty-time-theme' ) );
	}

	$valid_statuses = array( 'new', 'confirmed', 'completed', 'cancelled' );
	if ( ! in_array( $status, $valid_statuses, true ) ) {
		wp_die( __( 'حالة غير صحيحة.', 'beauty-time-theme' ) );
	}

	update_post_meta( $booking_id, '_booking_status', $status );
	wp_update_post(
		array(
			'ID'          => $booking_id,
			'post_status' => $status,
		)
	);

	wp_redirect( admin_url( 'edit.php?post_type=booking&updated=1' ) );
	exit;
}
add_action( 'wp_ajax_beauty_quick_change_status', 'beauty_ajax_quick_change_status' );

/**
 * Add bulk actions for status change
 */
function beauty_booking_bulk_actions( $actions ) {
	$actions['set_status_new']       = __( 'تعيين كـ جديد', 'beauty-time-theme' );
	$actions['set_status_confirmed'] = __( 'تعيين كـ مؤكد', 'beauty-time-theme' );
	$actions['set_status_completed'] = __( 'تعيين كـ مكتمل', 'beauty-time-theme' );
	$actions['set_status_cancelled'] = __( 'تعيين كـ ملغي', 'beauty-time-theme' );
	return $actions;
}
add_filter( 'bulk_actions-edit-booking', 'beauty_booking_bulk_actions' );

/**
 * Handle bulk status change
 */
function beauty_booking_bulk_action_handler( $redirect_to, $action, $post_ids ) {
	if ( strpos( $action, 'set_status_' ) !== 0 ) {
		return $redirect_to;
	}

	$status = str_replace( 'set_status_', '', $action );
	$valid_statuses = array( 'new', 'confirmed', 'completed', 'cancelled' );

	if ( ! in_array( $status, $valid_statuses, true ) ) {
		return $redirect_to;
	}

	$updated = 0;
	foreach ( $post_ids as $post_id ) {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			continue;
		}
		update_post_meta( $post_id, '_booking_status', $status );
		wp_update_post(
			array(
				'ID'          => $post_id,
				'post_status' => $status,
			)
		);
		$updated++;
	}

	$redirect_to = add_query_arg( 'bulk_updated', $updated, $redirect_to );
	return $redirect_to;
}
add_filter( 'handle_bulk_actions-edit-booking', 'beauty_booking_bulk_action_handler', 10, 3 );

/**
 * Show bulk update notice
 */
function beauty_booking_bulk_admin_notices() {
	if ( ! isset( $_REQUEST['bulk_updated'] ) ) {
		return;
	}

	$updated = absint( $_REQUEST['bulk_updated'] );
	printf(
		'<div id="message" class="updated notice is-dismissible"><p>%s</p></div>',
		sprintf(
			/* translators: %d: number of bookings updated */
			_n( 'تم تحديث %d حجز.', 'تم تحديث %d حجز.', $updated, 'beauty-time-theme' ),
			$updated
		)
	);
}
add_action( 'admin_notices', 'beauty_booking_bulk_admin_notices' );

/**
 * Add bookings endpoint to MyAccount
 */
function beauty_add_bookings_endpoint() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	add_rewrite_endpoint( 'bookings', EP_ROOT | EP_PAGES );
}
add_action( 'init', 'beauty_add_bookings_endpoint' );

/**
 * Ensure bookings endpoint rewrite rules exist.
 */
function beauty_ensure_bookings_endpoint_rewrite() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	if ( get_option( 'beauty_bookings_endpoint_rewrite_flushed' ) ) {
		return;
	}
	beauty_add_bookings_endpoint();
	flush_rewrite_rules();
	update_option( 'beauty_bookings_endpoint_rewrite_flushed', 1, true );
}
add_action( 'admin_init', 'beauty_ensure_bookings_endpoint_rewrite' );

/**
 * Add bookings to WooCommerce query vars
 */
function beauty_bookings_query_vars( $vars ) {
	$vars[] = 'bookings';
	return $vars;
}
add_filter( 'woocommerce_get_query_vars', 'beauty_bookings_query_vars' );

/**
 * Flush rewrite rules on theme activation
 */
function beauty_flush_rewrite_rules() {
	beauty_add_bookings_endpoint();
	flush_rewrite_rules();
}
add_action( 'after_switch_theme', 'beauty_flush_rewrite_rules' );

/**
 * Add bookings to MyAccount menu
 */
function beauty_add_bookings_menu_item( $items ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return $items;
	}
	$new_items = array();
	foreach ( $items as $key => $item ) {
		$new_items[ $key ] = $item;
		if ( 'orders' === $key ) {
			$new_items['bookings'] = __( 'حجوزاتي', 'beauty-time-theme' );
		}
	}
	return $new_items;
}
add_filter( 'woocommerce_account_menu_items', 'beauty_add_bookings_menu_item', 10 );

/**
 * Add bookings endpoint content
 */
function beauty_bookings_endpoint_content() {
	wc_get_template( 'myaccount/bookings.php', array(), '', get_template_directory() . '/woocommerce/' );
}
add_action( 'woocommerce_account_bookings_endpoint', 'beauty_bookings_endpoint_content' );

/**
 * Get booking by ID
 */
function beauty_get_booking( $booking_id ) {
	$post = get_post( $booking_id );
	if ( ! $post || $post->post_type !== 'booking' ) {
		return false;
	}

	return array(
		'id'            => $post->ID,
		'customer_name' => get_post_meta( $post->ID, '_booking_customer_name', true ),
		'phone'         => get_post_meta( $post->ID, '_booking_phone', true ),
		'services'      => get_post_meta( $post->ID, '_booking_services', true ),
		'date'          => get_post_meta( $post->ID, '_booking_date', true ),
		'time'          => get_post_meta( $post->ID, '_booking_time', true ),
		'branch'        => get_post_meta( $post->ID, '_booking_branch', true ),
		'status'        => get_post_meta( $post->ID, '_booking_status', true ),
		'notes'         => get_post_meta( $post->ID, '_booking_notes', true ),
		'order_id'      => get_post_meta( $post->ID, '_booking_order_id', true ),
	);
}

/**
 * Convert time string (HH:MM) to minutes.
 *
 * @param string $time Time in HH:MM format.
 * @return int|null Minutes or null if invalid.
 */
function beauty_time_to_minutes( $time ) {
	if ( ! preg_match( '/^\d{2}:\d{2}$/', $time ) ) {
		return null;
	}
	list( $hours, $minutes ) = array_map( 'intval', explode( ':', $time ) );
	if ( $hours < 0 || $hours > 23 || $minutes < 0 || $minutes > 59 ) {
		return null;
	}
	return ( $hours * 60 ) + $minutes;
}

/**
 * Register Working Hours admin page under bookings.
 */
function beauty_register_working_hours_menu() {
	add_submenu_page(
		'edit.php?post_type=booking',
		__( 'أوقات العمل', 'beauty-time-theme' ),
		__( 'أوقات العمل', 'beauty-time-theme' ),
		'manage_options',
		'beauty-working-hours',
		'beauty_render_working_hours_page'
	);
}
add_action( 'admin_menu', 'beauty_register_working_hours_menu' );

/**
 * Sanitize working hours input.
 *
 * @param array $raw Raw input.
 * @return array
 */
function beauty_sanitize_weekly_hours( $raw ) {
	$sanitized = array();
	for ( $i = 0; $i <= 6; $i++ ) {
		$sanitized[ $i ] = array(
			'enabled' => false,
			'start'   => '',
			'end'     => '',
		);
	}

	if ( ! is_array( $raw ) ) {
		return $sanitized;
	}

	foreach ( $raw as $day => $row ) {
		$day_index = intval( $day );
		if ( $day_index < 0 || $day_index > 6 ) {
			continue;
		}
		$enabled = ! empty( $row['enabled'] );
		$start   = isset( $row['start'] ) ? sanitize_text_field( $row['start'] ) : '';
		$end     = isset( $row['end'] ) ? sanitize_text_field( $row['end'] ) : '';

		$start_minutes = beauty_time_to_minutes( $start );
		$end_minutes   = beauty_time_to_minutes( $end );
		if ( $enabled && ( null === $start_minutes || null === $end_minutes || $start_minutes >= $end_minutes ) ) {
			$enabled = false;
			$start   = '';
			$end     = '';
		}

		$sanitized[ $day_index ] = array(
			'enabled' => (bool) $enabled,
			'start'   => $start,
			'end'     => $end,
		);
	}

	return $sanitized;
}

/**
 * Get weekly hours with defaults.
 *
 * @return array
 */
function beauty_get_weekly_working_hours() {
	$defaults = array();
	for ( $i = 0; $i <= 6; $i++ ) {
		$defaults[ $i ] = array(
			'enabled' => false,
			'start'   => '',
			'end'     => '',
		);
	}

	$stored = get_option( 'beauty_working_hours_weekly', array() );
	if ( ! is_array( $stored ) ) {
		return $defaults;
	}

	return array_replace( $defaults, $stored );
}

/**
 * Render Working Hours admin page.
 */
function beauty_render_working_hours_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$notice = '';
	if ( isset( $_POST['beauty_working_hours_nonce'] ) && wp_verify_nonce( $_POST['beauty_working_hours_nonce'], 'beauty_save_working_hours' ) ) {
		$sanitized = beauty_sanitize_weekly_hours( isset( $_POST['weekly_hours'] ) ? wp_unslash( $_POST['weekly_hours'] ) : array() );
		update_option( 'beauty_working_hours_weekly', $sanitized, false );

		$working_hours_note = isset( $_POST['working_hours_note'] )
			? sanitize_textarea_field( wp_unslash( $_POST['working_hours_note'] ) )
			: '';
		update_option( 'beauty_working_hours_note', $working_hours_note, false );

		$notice = __( 'تم حفظ أوقات العمل.', 'beauty-time-theme' );
	}

	$weekly_hours = beauty_get_weekly_working_hours();
	$working_hours_note = get_option( 'beauty_working_hours_note', '' );
	$day_labels = array(
		0 => __( 'الأحد', 'beauty-time-theme' ),
		1 => __( 'الاثنين', 'beauty-time-theme' ),
		2 => __( 'الثلاثاء', 'beauty-time-theme' ),
		3 => __( 'الأربعاء', 'beauty-time-theme' ),
		4 => __( 'الخميس', 'beauty-time-theme' ),
		5 => __( 'الجمعة', 'beauty-time-theme' ),
		6 => __( 'السبت', 'beauty-time-theme' ),
	);
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'أوقات العمل', 'beauty-time-theme' ); ?></h1>
		<?php if ( $notice ) : ?>
			<div class="updated notice is-dismissible"><p><?php echo esc_html( $notice ); ?></p></div>
		<?php endif; ?>
		<form method="post">
			<?php wp_nonce_field( 'beauty_save_working_hours', 'beauty_working_hours_nonce' ); ?>
			<table class="widefat striped" style="max-width: 720px;">
				<thead>
					<tr>
						<th><?php esc_html_e( 'اليوم', 'beauty-time-theme' ); ?></th>
						<th><?php esc_html_e( 'متاح', 'beauty-time-theme' ); ?></th>
						<th><?php esc_html_e( 'بداية الدوام', 'beauty-time-theme' ); ?></th>
						<th><?php esc_html_e( 'نهاية الدوام', 'beauty-time-theme' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $day_labels as $day_index => $label ) : ?>
						<?php $day = $weekly_hours[ $day_index ] ?? array(); ?>
						<tr>
							<td><?php echo esc_html( $label ); ?></td>
							<td>
								<label>
									<input type="checkbox" name="weekly_hours[<?php echo esc_attr( $day_index ); ?>][enabled]" value="1" <?php checked( ! empty( $day['enabled'] ) ); ?> />
								</label>
							</td>
							<td>
								<input type="time" name="weekly_hours[<?php echo esc_attr( $day_index ); ?>][start]" value="<?php echo esc_attr( $day['start'] ?? '' ); ?>" />
							</td>
							<td>
								<input type="time" name="weekly_hours[<?php echo esc_attr( $day_index ); ?>][end]" value="<?php echo esc_attr( $day['end'] ?? '' ); ?>" />
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<table class="form-table" style="max-width: 720px; margin-top: 16px;">
				<tr>
					<th scope="row">
						<label for="working_hours_note"><?php esc_html_e( 'ملحوظة للعميل', 'beauty-time-theme' ); ?></label>
					</th>
					<td>
						<textarea id="working_hours_note" name="working_hours_note" rows="3" class="large-text"><?php echo esc_textarea( $working_hours_note ); ?></textarea>
						<p class="description"><?php esc_html_e( 'تظهر هذه الملحوظة أسفل اختيار الوقت في صفحة الحجز.', 'beauty-time-theme' ); ?></p>
					</td>
				</tr>
			</table>
			<?php submit_button( __( 'حفظ', 'beauty-time-theme' ) ); ?>
		</form>
	</div>
	<?php
}

/**
 * AJAX: Get working hours for a month.
 */
function beauty_ajax_get_working_hours() {
	check_ajax_referer( 'beauty_working_hours', 'nonce' );

	$month = isset( $_POST['month'] ) ? sanitize_text_field( $_POST['month'] ) : '';
	if ( ! preg_match( '/^\d{4}-\d{2}$/', $month ) ) {
		wp_send_json_error( array( 'message' => __( 'تنسيق الشهر غير صالح.', 'beauty-time-theme' ) ) );
	}

	$weekly_hours = beauty_get_weekly_working_hours();
	$response     = array();
	try {
		$start = new DateTime( $month . '-01' );
		$end   = clone $start;
		$end->modify( 'last day of this month' );
		while ( $start <= $end ) {
			$date_key = $start->format( 'Y-m-d' );
			$weekday  = (int) $start->format( 'w' );
			$day_hours = $weekly_hours[ $weekday ] ?? array();
			if ( ! empty( $day_hours['enabled'] ) && ! empty( $day_hours['start'] ) && ! empty( $day_hours['end'] ) ) {
				$response[ $date_key ] = array(
					array(
						'start' => $day_hours['start'],
						'end'   => $day_hours['end'],
					),
				);
			}
			$start->modify( '+1 day' );
		}
	} catch ( Exception $e ) {
		wp_send_json_error( array( 'message' => __( 'تعذر تجهيز الأوقات.', 'beauty-time-theme' ) ) );
	}

	wp_send_json_success( $response );
}
add_action( 'wp_ajax_beauty_get_working_hours', 'beauty_ajax_get_working_hours' );
add_action( 'wp_ajax_nopriv_beauty_get_working_hours', 'beauty_ajax_get_working_hours' );
