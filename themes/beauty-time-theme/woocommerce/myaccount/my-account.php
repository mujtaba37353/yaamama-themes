<?php
/**
 * My Account — override
 * Uses profile.html structure with tabs
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

if ( ! is_user_logged_in() ) {
	return;
}

$current_user = wp_get_current_user();
?>
<main>
	<section class="panner">
		<p><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-time-theme' ); ?></a> / <?php esc_html_e( 'حسابي', 'beauty-time-theme' ); ?></p>
	</section>
	<section class="profile-section">
		<div class="container y-u-max-w-1200">
			<div class="bottom">
				<div class="sidbar">
					<div class="profile-img">
						<?php echo get_avatar( $current_user->ID, 120 ); ?>
					</div>
					<div class="tabs">
						<?php
						$menu_items = array(
							'dashboard'       => __( 'تفاصيل الحساب', 'beauty-time-theme' ),
							'bookings'        => __( 'طلباتي', 'beauty-time-theme' ),
							'customer-logout' => __( 'تسجيل الخروج', 'beauty-time-theme' ),
						);
						foreach ( $menu_items as $endpoint => $label ) :
							$url = ( 'customer-logout' === $endpoint ) ? wc_logout_url() : wc_get_account_endpoint_url( $endpoint );
							?>
							<a href="<?php echo esc_url( $url ); ?>" class="<?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
								<i class="fas fa-<?php echo esc_attr( beauty_get_account_icon( $endpoint ) ); ?>"></i>
								<?php echo esc_html( $label ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
				<div class="content">
					<?php
					do_action( 'woocommerce_account_navigation' );
					do_action( 'woocommerce_account_content' );
					?>
				</div>
			</div>
		</div>
	</section>
</main>
