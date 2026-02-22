<?php
/**
 * My Account Bookings — override
 * Uses profile.html "حجوزاتي" tab structure with booking-card markup
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

$user_id = get_current_user_id();
if ( ! $user_id ) {
	return;
}
$user = wp_get_current_user();
$user_email = $user ? $user->user_email : '';

$meta_query = array(
	'relation' => 'OR',
	array(
		'key'     => '_booking_user_id',
		'value'   => $user_id,
		'compare' => '=',
	),
);
if ( $user_email ) {
	$meta_query[] = array(
		'key'     => '_booking_email',
		'value'   => $user_email,
		'compare' => '=',
	);
}

$bookings = get_posts(
	array(
		'post_type'      => 'booking',
		'posts_per_page' => -1,
		'post_status'    => 'any',
		'meta_query'     => $meta_query,
		'orderby'        => 'meta_value',
		'meta_key'       => '_booking_date',
		'order'          => 'DESC',
	)
);

if ( ! $bookings ) {
	$bookings = get_posts(
		array(
			'post_type'      => 'booking',
			'posts_per_page' => -1,
			'post_status'    => 'any',
			'author'         => $user_id,
			'orderby'        => 'meta_value',
			'meta_key'       => '_booking_date',
			'order'          => 'DESC',
		)
	);
}

if ( ! $bookings ) :
	?>
	<div class="download-content tab-content active" data-content="downloads">
		<div class="account-form-card">
			<h2 class="section-title"><?php esc_html_e( 'طلباتي', 'beauty-time-theme' ); ?></h2>
			<p><?php esc_html_e( 'لا توجد حجوزات حتى الآن.', 'beauty-time-theme' ); ?></p>
			<div class="form-actions">
				<a class="btn-save" href="<?php echo esc_url( home_url( '/booking' ) ); ?>">
					<?php esc_html_e( 'احجز الآن', 'beauty-time-theme' ); ?>
				</a>
			</div>
		</div>
	</div>
	<?php
else :
	?>
	<div class="download-content tab-content active" data-content="downloads">
		<ul class="bookings-list">
			<?php
			foreach ( $bookings as $booking_post ) {
				$booking = beauty_get_booking( $booking_post->ID );
				if ( ! $booking ) {
					continue;
				}
				$status = $booking['status'] ?: 'new';
				$status_labels = array(
					'new'       => __( 'جديد', 'beauty-time-theme' ),
					'confirmed' => __( 'مؤكد', 'beauty-time-theme' ),
					'completed' => __( 'مكتمل', 'beauty-time-theme' ),
					'cancelled' => __( 'ملغي', 'beauty-time-theme' ),
				);
				?>
				<li class="booking-card">
					<div class="booking-image">
						<img src="<?php echo esc_url( beauty_time_asset( 'assets/book.png' ) ); ?>" alt="<?php echo esc_attr( $booking['services'] ); ?>">
					</div>
					<div class="booking-details">
						<h3 class="booking-title"><?php echo esc_html( $booking['services'] ); ?></h3>
						<p class="booking-service"><?php echo esc_html( $booking['customer_name'] ); ?></p>
						<div class="booking-info">
							<div class="info-item">
								<i class="fas fa-calendar-alt"></i>
								<span><?php echo esc_html( $booking['date'] . ' ' . $booking['time'] ); ?></span>
							</div>
							<div class="info-item">
								<i class="fas fa-phone"></i>
								<span><?php echo esc_html( $booking['phone'] ); ?></span>
							</div>
						</div>
					</div>
					<div class="booking-footer">
						<span class="status-badge <?php echo esc_attr( $status ); ?>">
							<?php echo esc_html( isset( $status_labels[ $status ] ) ? $status_labels[ $status ] : $status ); ?>
						</span>
					</div>
				</li>
				<?php
			}
			?>
		</ul>
	</div>
	<?php
endif;
