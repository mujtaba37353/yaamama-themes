<?php
/**
 * Template Name: حسابي (Profile)
 * Elegance - Profile / My Account page
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! is_user_logged_in() ) {
	wp_safe_redirect( elegance_page_url( 'login', '/login/' ) );
	exit;
}

// Save profile (display name, email) with Arabic notices.
if ( isset( $_POST['elegance_profile_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['elegance_profile_nonce'] ) ), 'elegance_profile' ) ) {
	$user_id = get_current_user_id();
	if ( $user_id ) {
		$name  = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$updated = false;
		if ( $name !== '' ) {
			wp_update_user( array( 'ID' => $user_id, 'display_name' => $name ) );
			$updated = true;
		}
		if ( $email && is_email( $email ) ) {
			$existing = email_exists( $email );
			if ( $existing && (int) $existing !== $user_id ) {
				if ( function_exists( 'wc_add_notice' ) ) {
					wc_add_notice( __( 'البريد الإلكتروني مستخدم من قبل حساب آخر.', 'elegance' ), 'error' );
				}
			} else {
				wp_update_user( array( 'ID' => $user_id, 'user_email' => $email ) );
				$updated = true;
			}
		}
		if ( $updated && function_exists( 'wc_add_notice' ) ) {
			wc_add_notice( __( 'تم حفظ البيانات بنجاح.', 'elegance' ), 'success' );
		}
	}
}

elegance_enqueue_page_css( 'profile' );
elegance_enqueue_component_css( array( 'empty-state', 'status-popup', 'auth' ) );

$myaccount_url = elegance_myaccount_url();
$logout_url = wp_logout_url( home_url( '/' ) );
if ( function_exists( 'wc_logout_url' ) ) {
	$logout_url = wc_logout_url( home_url( '/' ) );
}

get_header();

$current_user = wp_get_current_user();
$display_name = $current_user->display_name ?: __( 'مستخدم', 'elegance' );
?>
<main>
  <section class="panner y-u-m-b-0">
    <h1 class="y-u-text-center">حسابي</h1>
  </section>
  <section class="profile-section">
    <input type="radio" name="profile-tab" id="tab-profile" checked>
    <input type="radio" name="profile-tab" id="tab-orders">

    <div class="sidbar">
      <div class="top">
        <div class="content">
          <span>اهلا،</span>
          <p><?php echo esc_html( $display_name ); ?></p>
        </div>
      </div>
      <div class="links">
        <label for="tab-profile">البيانات الشخصية</label>
        <?php if ( function_exists( 'wc_get_account_endpoint_url' ) ) : ?>
          <a href="<?php echo esc_url( $myaccount_url ); ?>">الطلبات</a>
        <?php else : ?>
          <label for="tab-orders">الطلبات</label>
        <?php endif; ?>
        <a class="logout-link" href="<?php echo esc_url( $logout_url ); ?>">تسجيل الخروج</a>
      </div>
    </div>
    <div class="content">
      <div class="profile tab-content">
        <?php if ( function_exists( 'woocommerce_output_all_notices' ) ) : ?>
          <?php woocommerce_output_all_notices(); ?>
        <?php endif; ?>
        <form action="<?php echo esc_url( get_permalink( get_queried_object_id() ) ?: elegance_page_url( 'profile', '/profile/' ) ); ?>" method="post">
          <?php wp_nonce_field( 'elegance_profile', 'elegance_profile_nonce' ); ?>
          <label for="profile-name">الاسم بالكامل</label>
          <input type="text" id="profile-name" name="name" value="<?php echo esc_attr( $display_name ); ?>">
          <label for="profile-email">البريد الإلكتروني</label>
          <input type="email" id="profile-email" name="email" value="<?php echo esc_attr( $current_user->user_email ); ?>">
          <button type="submit" class="btn main-button" name="elegance_profile_save">حفظ التعديلات</button>
        </form>
      </div>
      <div class="addresses tab-content" id="tab-orders-content">
        <p><a href="<?php echo esc_url( $myaccount_url ); ?>" class="btn main-button">عرض الطلبات في حسابي</a></p>
      </div>
    </div>
  </section>
</main>
<?php
get_footer();
