<?php
/**
 * Lost Password Form — override
 * Markup from beauty-time/templates/forget-password/forget-password.html
 *
 * @package Beauty_Time_Theme
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>
<main>
	<section class="panner"></section>
	<section class="auth-section">
		<div class="container right-left y-u-max-w-1200">
			<div class="right">
				<form method="post" class="woocommerce-ResetPassword lost_reset_password">
					<div class="form-group">
						<h2 style="margin-bottom: var(--y-space-40);"><img src="<?php echo esc_url( beauty_time_asset( 'assets/profile-primary.svg' ) ); ?>" alt=""> <?php esc_html_e( 'أعد تعيين كلمة المرور', 'beauty-time-theme' ); ?></h2>
						<p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'يرجى تسجيل رقم الهاتف المسجل به الحساب وسيصلك كود مكون من 4 أرقام', 'beauty-time-theme' ) ); ?></p>
						<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
							<label for="user_login"><?php esc_html_e( 'رقم الهاتف', 'beauty-time-theme' ); ?></label>
							<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" autocomplete="username" />
						</p>
					</div>
					<div class="clear"></div>
					<?php do_action( 'woocommerce_lostpassword_form' ); ?>
					<p class="woocommerce-form-row form-row">
						<input type="hidden" name="wc_reset_password" value="true" />
						<button type="submit" class="woocommerce-Button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>" value="<?php esc_attr_e( 'إرسال الكود', 'beauty-time-theme' ); ?>"><?php esc_html_e( 'إرسال الكود', 'beauty-time-theme' ); ?></button>
					</p>
					<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
				</form>
			</div>
		</div>
	</section>
</main>
<?php do_action( 'woocommerce_after_lost_password_form' ); ?>
