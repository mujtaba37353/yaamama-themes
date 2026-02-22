<?php
/**
 * Reset password page template.
 *
 * @package stationary-theme
 */

get_header();
$au = stationary_base_uri() . '/assets';

$rp_key  = '';
$rp_login = '';
$show_form = false;

$rp_cookie = 'wp-resetpass-' . COOKIEHASH;
if ( isset( $_COOKIE[ $rp_cookie ] ) && is_string( $_COOKIE[ $rp_cookie ] ) && strpos( $_COOKIE[ $rp_cookie ], ':' ) > 0 ) {
	$parts    = array_map( 'wc_clean', explode( ':', wp_unslash( $_COOKIE[ $rp_cookie ] ), 2 ) );
	$rp_id    = absint( $parts[0] );
	$rp_key   = $parts[1];
	$userdata = get_userdata( $rp_id );
	$rp_login = $userdata ? $userdata->user_login : '';

	if ( $rp_key && $rp_login ) {
		$user = WC_Shortcode_My_Account::check_password_reset_key( $rp_key, $rp_login );
		if ( $user instanceof WP_User ) {
			$show_form = true;
		}
	}
}
?>

<main>
	<section class="panner panner-image y-u-m-b-0 container y-u-max-w-1200">
		<h1 class="y-u-text-center"><?php esc_html_e( 'إعادة تعيين كلمة المرور', 'stationary-theme' ); ?></h1>
	</section>

	<section class="auth-section">
		<div class="container y-u-max-w-1200 special">
			<div class="left special-img">
				<img src="<?php echo esc_url( $au . '/login.png' ); ?>" alt="<?php esc_attr_e( 'صورة توضيحية لإعادة تعيين كلمة المرور', 'stationary-theme' ); ?>" onerror="this.style.display='none'">
			</div>
			<div class="right">
				<?php if ( $show_form ) : ?>
					<?php wc_print_notices(); ?>
					<form method="post" id="reset-password-form" class="woocommerce-ResetPassword lost_reset_password">
						<div class="form-group">
							<label for="password_1"><?php esc_html_e( 'كلمة المرور الجديدة', 'stationary-theme' ); ?> <span class="required">*</span></label>
							<div class="password-input-wrapper">
								<input type="password" id="password_1" name="password_1" autocomplete="new-password" required>
								<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'stationary-theme' ); ?>">
									<i class="fa-regular fa-eye"></i>
								</button>
							</div>
						</div>
						<div class="form-group">
							<label for="password_2"><?php esc_html_e( 'إعادة كلمة المرور الجديدة', 'stationary-theme' ); ?> <span class="required">*</span></label>
							<div class="password-input-wrapper">
								<input type="password" id="password_2" name="password_2" autocomplete="new-password" required>
								<button type="button" class="password-toggle" aria-label="<?php esc_attr_e( 'إظهار/إخفاء كلمة المرور', 'stationary-theme' ); ?>">
									<i class="fa-regular fa-eye"></i>
								</button>
							</div>
						</div>
						<input type="hidden" name="reset_key" value="<?php echo esc_attr( $rp_key ); ?>">
						<input type="hidden" name="reset_login" value="<?php echo esc_attr( $rp_login ); ?>">
						<input type="hidden" name="wc_reset_password" value="true">
						<?php wp_nonce_field( 'reset_password', 'woocommerce-reset-password-nonce' ); ?>
						<button type="submit"><?php esc_html_e( 'حفظ', 'stationary-theme' ); ?></button>
					</form>
				<?php else : ?>
					<div class="auth-empty-state">
						<p><?php esc_html_e( 'رابط استعادة كلمة المرور غير صالح أو منتهي الصلاحية.', 'stationary-theme' ); ?></p>
						<p><a href="<?php echo esc_url( home_url( '/forget-password' ) ); ?>" class="auth-forgot"><?php esc_html_e( 'اطلب رابطاً جديداً', 'stationary-theme' ); ?></a></p>
						<p><a href="<?php echo esc_url( home_url( '/login' ) ); ?>"><?php esc_html_e( 'العودة لتسجيل الدخول', 'stationary-theme' ); ?></a></p>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>
</main>

<?php get_footer(); ?>
