<?php
if ( ! is_user_logged_in() ) {
	wp_safe_redirect( dark_theme_get_page_url( 'login' ) );
	exit;
}

get_header();

$user = wp_get_current_user();
$orders = function_exists( 'wc_get_orders' )
	? wc_get_orders( array( 'customer_id' => $user->ID, 'limit' => 10 ) )
	: array();
?>

<header data-y="design-header">
	<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/header2.png' ) ); ?>" alt="" class="design-img y-u-w-100" />
</header>

<main data-y="main" class="account-main">
	<div class="y-main-container">
		<div data-y="breadcrumb">
			<nav aria-label="breadcrumb" class="y-breadcrumb-container">
				<ol class="y-breadcrumb"></ol>
			</nav>
		</div>
		<h1 class="account-welcome">مرحبا <?php echo esc_html( $user->display_name ); ?></h1>

		<div class="account-layout">
			<input type="radio" name="account-section" id="section-profile" class="section-radio" checked />
			<input type="radio" name="account-section" id="section-orders" class="section-radio" />
			<input type="radio" name="account-section" id="section-address" class="section-radio" />

			<aside class="account-sidebar">
				<label for="section-profile" class="sidebar-item">
					<i class="fas fa-user"></i>
					<span>حسابي</span>
				</label>

				<label for="section-orders" class="sidebar-item">
					<i class="fas fa-shopping-bag"></i>
					<span>الطلبات</span>
				</label>

				<label for="section-address" class="sidebar-item">
					<i class="fas fa-map-marker-alt"></i>
					<span>العنوان</span>
				</label>

				<a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>" class="sidebar-item logout-item">
					<i class="fas fa-sign-out-alt"></i>
					<span>تسجيل خروج</span>
				</a>
			</aside>

			<div class="account-content">
				<div class="content-section" id="profile-content">
					<h2 class="section-title">معلومات الحساب</h2>
					<div class="profile-form">
						<div class="form-group">
							<label>الاسم الكامل</label>
							<input type="text" value="<?php echo esc_attr( $user->display_name ); ?>" readonly />
						</div>
						<div class="form-group">
							<label>البريد الإلكتروني</label>
							<input type="email" value="<?php echo esc_attr( $user->user_email ); ?>" readonly />
						</div>
						<div class="form-group">
							<label>رقم الجوال</label>
							<input type="text" value="<?php echo esc_attr( get_user_meta( $user->ID, 'phone', true ) ); ?>" readonly />
						</div>
					</div>
				</div>

				<div class="content-section" id="orders-content">
					<h2 class="section-title">طلباتي</h2>
					<?php if ( $orders ) : ?>
						<div class="orders-list-view">
							<div class="orders-container">
								<div class="orders-header">
									<div class="order-col">رقم الطلب</div>
									<div class="order-col">التاريخ</div>
									<div class="order-col">الحالة</div>
									<div class="order-col">الإجمالي</div>
								</div>
								<?php foreach ( $orders as $order ) : ?>
									<div class="order-item">
										<div class="order-col order-number">#<?php echo esc_html( $order->get_order_number() ); ?></div>
										<div class="order-col order-date"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></div>
										<div class="order-col order-status"><?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?></div>
										<div class="order-col order-total"><?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					<?php else : ?>
						<p>لا توجد طلبات حتى الآن.</p>
					<?php endif; ?>
				</div>

				<div class="content-section" id="address-content">
					<h2 class="section-title">العنوان</h2>

					<?php if ( class_exists( 'WC_Shortcode_My_Account' ) ) : ?>
						<div class="address-edit-form-wrapper">
							<?php WC_Shortcode_My_Account::edit_address( 'billing' ); ?>
						</div>
					<?php else : ?>
						<p class="address-empty-message">تعذر تحميل نموذج العنوان.</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
