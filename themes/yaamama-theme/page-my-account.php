<?php
if ( ! is_user_logged_in() ) {
	$redirect_to = home_url( '/my-account' );
	wp_safe_redirect( home_url( '/login?redirect_to=' . rawurlencode( $redirect_to ) ) );
	exit;
}

get_header();
$assets_uri   = get_template_directory_uri() . '/yaamama-front-platform/assets';
$current_user = wp_get_current_user();
$full_name    = $current_user->display_name;
$user_email   = $current_user->user_email;
$user_phone   = get_user_meta( $current_user->ID, 'phone', true );
$user_gender  = get_user_meta( $current_user->ID, 'gender', true ) ?: 'male';
$updated      = isset( $_GET['profile_updated'] );
?>

<main class="special-bg">
	<section class="my-account-section y-u-py-40">
		<div class="container y-u-max-w-1200 y-u-flex y-u-gap-24">
			<input type="radio" name="account-page" id="page-1" checked>
			<input type="radio" name="account-page" id="page-2">
			<input type="radio" name="account-page" id="page-3">
			<script>
				document.addEventListener('DOMContentLoaded', function () {
					var hash = window.location.hash;
					if (!hash) return;
					var target = document.querySelector(hash);
					if (target && target.type === 'radio') {
						target.checked = true;
					}
				});
			</script>

			<aside class="account-sidebar">
				<ul class="y-u-flex y-u-flex-col y-u-gap-8">
					<li>
						<label for="page-1" class="y-u-flex y-u-items-center y-u-gap-12">
							<i class="fa-regular fa-user"></i>
							<span>حسابي</span>
						</label>
					</li>
					<li>
						<label for="page-2" class="y-u-flex y-u-items-center y-u-gap-12">
							<i class="fa-solid fa-store"></i>
							<span>متاجري</span>
						</label>
					</li>
					<li>
						<label for="page-3" class="y-u-flex y-u-items-center y-u-gap-12">
							<i class="fa-regular fa-file-lines"></i>
							<span>الفواتير</span>
						</label>
					</li>
					<li>
						<a href="<?php echo esc_url( wp_logout_url( home_url( '/login' ) ) ); ?>" class="y-u-flex y-u-items-center y-u-gap-12 logout-link">
							<i class="fa-solid fa-arrow-right-from-bracket"></i>
							<span>تسجيل خروج</span>
						</a>
					</li>
				</ul>
			</aside>
			<div class="account-content">
				<div class="page-1">
					<div class="page-header y-u-m-b-32">
						<h1 class="y-u-text-xl y-u-font-bold y-u-m-b-8">إدارة الملف الشخصي</h1>
						<p class="y-u-text-muted y-u-text-s">قم بتحديث معلوماتك الشخصية وسيرتك الذاتية لإدارة هويتك
							الشخصية</p>
					</div>
					<?php if ( $updated ) : ?>
						<p class="y-u-text-s y-u-m-b-16" style="color: var(--y-color-primary);">تم تحديث بياناتك بنجاح.</p>
					<?php endif; ?>

					<div class="profile-header y-u-flex y-u-justify-between y-u-items-start y-u-m-b-40">
						<div class="profile-info y-u-flex y-u-items-center y-u-gap-16">
							<div class="profile-image">
								<img src="<?php echo esc_url( $assets_uri . '/prof-img.jpg' ); ?>" alt="صورة ملف المستخدم" class="y-u-rounded-f"
									style="width: 80px; height: 80px; object-fit: cover;">
							</div>
							<div>
								<h3 class="y-u-text-l y-u-font-bold y-u-m-b-4"><?php echo esc_html( $full_name ); ?></h3>
								<p class="y-u-text-muted y-u-text-s"><?php echo esc_html( $current_user->user_login ); ?></p>
							</div>
						</div>
						<div class="profile-actions y-u-flex y-u-gap-12">
							<a href="<?php echo esc_url( home_url( '/delete-account' ) ); ?>"
								class="btn y-u-flex y-u-items-center y-u-gap-8 delete-account">
								حذف الحساب
								<i class="fa-regular fa-trash-can"></i>
							</a>
						</div>
					</div>

					<form class="profile-form y-u-flex y-u-flex-col y-u-gap-24" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
						<?php wp_nonce_field( 'yaamama_update_profile', 'yaamama_update_profile_nonce' ); ?>
						<input type="hidden" name="action" value="yaamama_update_profile">
						<div class="form-row y-u-grid y-u-grid-2 y-u-gap-24">
							<div class="form-group">
								<label class="y-u-block y-u-m-b-8 y-u-font-bold">الاسم</label>
								<input type="text" name="full_name" class="y-u-w-full y-u-p-12 y-u-rounded-s y-u-border"
									value="<?php echo esc_attr( $full_name ); ?>" required>
							</div>
							<div class="form-group">
								<label class="y-u-block y-u-m-b-8 y-u-font-bold">البريد الإلكتروني</label>
								<input type="email" name="email" class="y-u-w-full y-u-p-12 y-u-rounded-s y-u-border"
									value="<?php echo esc_attr( $user_email ); ?>" required>
							</div>
						</div>
						<div class="form-row y-u-grid y-u-grid-2 y-u-gap-24">
							<div class="form-group">
								<label class="y-u-block y-u-m-b-8 y-u-font-bold">رقم الجوال</label>
								<input type="tel" name="phone" class="y-u-w-full y-u-p-12 y-u-rounded-s y-u-border"
									value="<?php echo esc_attr( $user_phone ); ?>" maxlength="14"
									oninput="this.value = this.value.replace(/[^0-9+]/g, ''); if(this.value.startsWith('009665')) this.maxLength = 14; else if(this.value.startsWith('+9665')) this.maxLength = 13; else if(this.value.startsWith('9665')) this.maxLength = 12; else if(this.value.startsWith('05')) this.maxLength = 10; else this.maxLength = 14;">
							</div>
							<div class="form-group">
								<label class="y-u-block y-u-m-b-8 y-u-font-bold">النوع</label>
								<div class="custom-dropdown">
									<div class="dropdown-trigger">
										<span class="dropdown-current"><?php echo $user_gender === 'female' ? 'أنثى' : 'ذكر'; ?></span>
										<i class="fa-solid fa-chevron-down dropdown-arrow"></i>
									</div>
									<ul class="dropdown-options">
										<li data-value="male" class="<?php echo $user_gender === 'male' ? 'selected' : ''; ?>">ذكر</li>
										<li data-value="female" class="<?php echo $user_gender === 'female' ? 'selected' : ''; ?>">أنثى</li>
									</ul>
								</div>
								<input type="hidden" name="gender" value="<?php echo esc_attr( $user_gender ); ?>" data-gender-input>
							</div>
						</div>
						<div class="form-actions y-u-flex y-u-gap-16 y-u-m-t-24">
							<button type="submit" class="btn main-button">تعديل</button>
						</div>
					</form>

				</div>

				<div class="page-2">
					<div class="page-header y-u-m-b-32">
						<h1 class="y-u-text-xl y-u-font-bold y-u-m-b-8">إدارة متاجرك</h1>
						<p class="y-u-text-muted y-u-text-s">كل المتاجر الخاصة بك</p>
					</div>

					<?php
					$can_render_subs = function_exists( 'yaamama_subscriptions_tables' )
						&& function_exists( 'yaamama_subscriptions_get_price' )
						&& function_exists( 'yaamama_subscriptions_get_plan' );
					?>
					<?php if ( ! is_user_logged_in() ) : ?>
						<div class="stores-grid y-u-grid y-u-grid-3 y-u-gap-24">
							<p>يرجى تسجيل الدخول لعرض اشتراكاتك.</p>
						</div>
					<?php elseif ( ! $can_render_subs ) : ?>
						<div class="stores-grid y-u-grid y-u-grid-3 y-u-gap-24">
							<p>لا توجد اشتراكات حالياً.</p>
						</div>
					<?php else : ?>
						<?php
						global $wpdb;
						$tables  = yaamama_subscriptions_tables();
						$user_id = get_current_user_id();
						$subs    = $wpdb->get_results(
							$wpdb->prepare(
								"SELECT * FROM {$tables['subs']} WHERE user_id = %d ORDER BY id DESC",
								$user_id
							),
							ARRAY_A
						);
						?>
						<div class="stores-grid y-u-grid y-u-grid-3 y-u-gap-24">
							<?php if ( empty( $subs ) ) : ?>
								<p>لا توجد اشتراكات حالياً.</p>
							<?php else : ?>
							<?php foreach ( $subs as $sub ) : ?>
								<?php
								$product     = get_post( $sub['product_id'] );
								$store_name  = ! empty( $sub['store_name'] ) ? $sub['store_name'] : '';
								$store_name_en = '';
								if ( ! empty( $sub['last_order_id'] ) ) {
									$_order = wc_get_order( (int) $sub['last_order_id'] );
									if ( $_order ) {
										if ( $store_name === '' ) {
											$store_name = (string) $_order->get_meta( 'yaamama_store_name' );
										}
										$store_name_en = (string) $_order->get_meta( 'yaamama_store_name_en' );
									}
								}
								$has_custom_name = ( $store_name !== '' );
								$product_image_id  = get_post_thumbnail_id( $sub['product_id'] );
								$product_image_url = $product_image_id
									? wp_get_attachment_image_url( $product_image_id, 'medium' )
									: $assets_uri . '/product.png';
								$price_row   = yaamama_subscriptions_get_price( $sub['plan_price_id'] );
								$plan        = $price_row ? yaamama_subscriptions_get_plan( $price_row['plan_id'] ) : null;
								$pay_url     = '';
								if ( 'pending_payment' === $sub['status'] && ! empty( $sub['last_order_id'] ) ) {
									$order = wc_get_order( (int) $sub['last_order_id'] );
									if ( $order ) {
										$current_gw = $order->get_payment_method();
										$enabled_gw = function_exists( 'yaamama_subscriptions_get_payment_gateways' ) ? yaamama_subscriptions_get_payment_gateways() : array();
										if ( $current_gw && $enabled_gw && ! isset( $enabled_gw[ $current_gw ] ) ) {
											$sub_settings = function_exists( 'yaamama_subscriptions_settings' ) ? yaamama_subscriptions_settings() : array();
											$new_gw_id = $sub_settings['payment_gateway_id'] ?? '';
											$all_gw = WC_Payment_Gateways::instance()->payment_gateways();
											if ( $new_gw_id && isset( $all_gw[ $new_gw_id ] ) ) {
												$order->set_payment_method( $all_gw[ $new_gw_id ] );
												$order->save();
											}
										}
										$pay_url = $order->get_checkout_payment_url();
									}
								}
								$start_date = ! empty( $sub['starts_at'] ) ? date_i18n( 'j F Y', strtotime( $sub['starts_at'] ) ) : '';

								$store_id     = get_option( "yaamama_store_for_sub_{$sub['id']}", '' );
								$store_url    = get_option( "yaamama_store_url_for_sub_{$sub['id']}", '' );
								$store_status = get_option( "yaamama_store_status_for_sub_{$sub['id']}", '' );
								$job_id       = get_option( "yaamama_job_for_sub_{$sub['id']}", '' );

								if ( $store_id && ( ! $store_url || ! $store_status ) ) {
									if ( function_exists( 'yaamama_factory_api_call' ) ) {
										$_store_data = yaamama_factory_api_call( 'GET', "/stores/{$store_id}" );
										if ( ! is_wp_error( $_store_data ) && ! empty( $_store_data['store_url'] ) ) {
											$store_url    = $_store_data['store_url'];
											$store_status = $_store_data['status'] ?? 'active';
											update_option( "yaamama_store_url_for_sub_{$sub['id']}", $store_url );
											update_option( "yaamama_store_status_for_sub_{$sub['id']}", $store_status );
										}
									}
									if ( ! $store_url ) {
										$store_url    = 'https://' . $store_id . '.staging.yaamama.store';
										$store_status = $store_status ?: 'active';
										update_option( "yaamama_store_url_for_sub_{$sub['id']}", $store_url );
										update_option( "yaamama_store_status_for_sub_{$sub['id']}", $store_status );
									}
								}

								$is_suspended = ( $sub['status'] === 'cancelled' && $store_id );
								$local_store_status = get_option( "yaamama_store_status_for_sub_{$sub['id']}", '' );
								if ( $local_store_status === 'suspended' ) {
									$is_suspended = true;
								}

								$status_map = array(
									'active'          => array( 'label' => 'جاهز', 'class' => 'ready' ),
									'pending_payment' => array( 'label' => 'بانتظار الدفع', 'class' => 'preparing' ),
									'pending'         => array( 'label' => 'قيد التفعيل', 'class' => 'preparing' ),
									'past_due'        => array( 'label' => 'متأخر', 'class' => 'preparing' ),
									'failed'          => array( 'label' => 'فشل', 'class' => 'preparing' ),
									'cancelled'       => array( 'label' => 'ملغي', 'class' => 'preparing' ),
								);
								if ( $is_suspended ) {
									$status_data = array( 'label' => 'معلّق', 'class' => 'suspended' );
								} else {
									$status_data = $status_map[ $sub['status'] ] ?? array( 'label' => $sub['status'], 'class' => 'preparing' );
								}
								$status_label = $status_data['label'];
								$status_class = $status_data['class'];
								$plan_prices  = $price_row
									? $wpdb->get_results(
										$wpdb->prepare( "SELECT * FROM {$tables['plan_prices']} WHERE plan_id = %d AND status = 'active'", $price_row['plan_id'] ),
										ARRAY_A
									)
									: array();
								?>
								<div class="store-card y-u-rounded-m y-u-p-24 y-u-relative y-u-flex y-u-flex-col <?php echo $is_suspended ? 'is-suspended' : ''; ?>">
									<div class="status-badge <?php echo esc_attr( $status_class ); ?> y-u-absolute y-u-top-24 y-u-left-24 y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">
										<span class="status-dot"></span>
										<?php echo esc_html( $status_label ); ?>
									</div>

									<div class="y-u-flex y-u-justify-center y-u-m-b-24 y-u-m-t-24">
										<div class="image-wrapper y-u-rounded-f y-u-p-4" style="border: 2px solid var(--y-color-primary);">
											<img src="<?php echo esc_url( $product_image_url ); ?>" alt="<?php echo esc_attr( $store_name ?: 'Store' ); ?>" class="y-u-rounded-f"
												style="width: 120px; height: 120px; object-fit: cover;">
										</div>
									</div>

									<div class="y-u-text-center y-u-m-b-16">
										<h3 class="y-u-text-l y-u-font-bold primary-color y-u-m-b-4"><?php echo esc_html( $store_name ?: 'متجري' ); ?></h3>
										<?php if ( $store_name_en !== '' ) : ?>
											<p class="y-u-text-s y-u-m-0" style="color:#6b7280;direction:ltr;"><?php echo esc_html( $store_name_en ); ?></p>
										<?php endif; ?>
										<p class="y-u-text-muted y-u-text-s">الباقة: <?php echo esc_html( $plan['name'] ?? '-' ); ?></p>
									</div>

									<div class="y-u-flex y-u-justify-between y-u-items-center y-u-m-b-16">
										<span class="package-badge y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">باقة اليمامة</span>
										<?php if ( $start_date ) : ?>
											<p class="y-u-text-s y-u-font-medium y-u-m-0">بدأ في : <?php echo esc_html( $start_date ); ?></p>
										<?php endif; ?>
									</div>

									<div class="divider y-u-m-b-24"></div>

								<?php if ( $is_suspended ) : ?>
									<div class="yaamama-store-suspended y-u-m-b-16 y-u-text-center y-u-p-24 y-u-rounded-s" style="background:linear-gradient(135deg,#fef3c7 0%,#fffbeb 100%);border:2px solid #f59e0b;">
										<div style="font-size:36px;margin-bottom:8px;">⏸️</div>
										<p class="y-u-text-s y-u-font-bold y-u-m-0" style="color:#d97706;margin-bottom:8px!important;">تم تعليق متجرك</p>
										<p class="y-u-text-xs y-u-m-0" style="color:#92400e;margin-bottom:16px!important;">تم إيقاف المتجر بسبب إلغاء الاشتراك. يمكنك تجديد اشتراكك لإعادة تفعيله.</p>
										<?php
										$reactivate_product_url = '';
										if ( ! empty( $sub['product_id'] ) ) {
											$reactivate_product_url = get_permalink( $sub['product_id'] );
										}
										if ( $reactivate_product_url ) : ?>
											<a class="btn main-button" href="<?php echo esc_url( $reactivate_product_url ); ?>"
												style="display:inline-block;background:#d97706;border-color:#d97706;padding:12px 32px;border-radius:8px;color:#fff;text-decoration:none;font-weight:bold;font-size:14px;">
												تجديد الاشتراك
											</a>
										<?php endif; ?>
									</div>
								<?php elseif ( $store_url && $store_status !== 'failed' && $store_status !== 'provisioning' ) : ?>
									<div class="yaamama-store-ready y-u-m-b-16 y-u-text-center y-u-p-24 y-u-rounded-s" style="background:linear-gradient(135deg,#f0fdf4 0%,#ecfdf5 100%);border:2px solid #86efac;">
										<div style="font-size:36px;margin-bottom:8px;">🎉</div>
										<p class="y-u-text-s y-u-font-bold y-u-m-0" style="color:#059669;margin-bottom:12px!important;">متجرك جاهز ويعمل!</p>
										<div style="display:flex;gap:8px;margin-top:12px;">
											<a class="btn main-button" href="<?php echo esc_url( $store_url ); ?>" target="_blank"
												style="flex:1;display:block;text-align:center;background:#059669;border-color:#059669;padding:12px 8px;border-radius:8px;color:#fff;text-decoration:none;font-weight:bold;font-size:14px;">
												زيارة المتجر
											</a>
											<button type="button"
												class="yaamama-auto-login-btn"
												data-subscription-id="<?php echo esc_attr( $sub['id'] ); ?>"
												style="flex:1;display:block;text-align:center;background:#1e40af;border:none;padding:12px 8px;border-radius:8px;color:#fff;cursor:pointer;font-weight:bold;font-size:14px;">
												لوحة تحكم المتجر
											</button>
										</div>
									</div>
								<?php elseif ( $job_id && ! $store_url && $store_status !== 'failed' ) : ?>
									<div class="yaamama-provision-status y-u-m-b-16 y-u-p-20 y-u-rounded-s" style="background:#f0f9ff;border:1px solid #bae6fd;" data-subscription-id="<?php echo esc_attr( $sub['id'] ); ?>">
										<div class="provision-header" style="display:flex;align-items:center;gap:12px;margin-bottom:16px;">
											<div class="provision-spinner" style="flex-shrink:0;width:28px;height:28px;border:3px solid #e5e7eb;border-top-color:#3b82f6;border-radius:50%;animation:yaamama-spin 1s linear infinite;"></div>
											<div>
												<p class="y-u-text-s y-u-font-bold y-u-m-0" style="color:#1e40af;">جاري تجهيز متجرك</p>
												<p class="provision-step-label y-u-text-xs y-u-text-muted y-u-m-0" style="margin-top:2px;"></p>
											</div>
										</div>
										<div class="provision-bar-track" style="width:100%;height:8px;background:#e0e7ff;border-radius:4px;overflow:hidden;margin-bottom:4px;">
											<div class="provision-bar-fill" style="width:0%;height:100%;background:linear-gradient(90deg,#3b82f6,#6366f1);border-radius:4px;transition:width .6s ease;"></div>
										</div>
										<p class="provision-percent y-u-text-xs y-u-text-muted y-u-m-0" style="text-align:left;direction:ltr;">0%</p>
									</div>
								<?php elseif ( $store_status === 'failed' ) : ?>
									<div class="y-u-m-b-16 y-u-text-center y-u-p-16 y-u-rounded-s" style="background:#fef2f2;border:1px solid #fecaca;">
										<p class="y-u-text-s y-u-font-bold y-u-m-0" style="color:#dc2626;">فشل إنشاء المتجر</p>
										<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="margin-top:8px;">
											<?php wp_nonce_field( 'yaamama_retry_provision', 'yaamama_retry_nonce' ); ?>
											<input type="hidden" name="action" value="yaamama_retry_provision">
											<input type="hidden" name="subscription_id" value="<?php echo esc_attr( $sub['id'] ); ?>">
											<button type="submit" class="btn black-outline-button y-u-p-8 y-u-px-16 y-u-text-xs">إعادة المحاولة</button>
										</form>
									</div>
								<?php elseif ( in_array( $sub['status'], array( 'active', 'pending' ), true ) && ! $store_url ) : ?>
									<div class="y-u-m-b-16 y-u-text-center y-u-p-16 y-u-rounded-s" style="background:#fffbeb;border:1px solid #fde68a;">
										<p class="y-u-text-s y-u-font-bold y-u-m-0" style="color:#d97706;">جاري تجهيز المتجر...</p>
										<p class="y-u-text-xs y-u-text-muted y-u-m-0" style="margin-top:4px;">سيكون متجرك جاهزًا قريبًا</p>
									</div>
								<?php endif; ?>

								<?php if ( ! $is_suspended ) : ?>
									<div class="store-actions y-u-flex y-u-flex-col y-u-gap-12">
										<?php if ( $pay_url ) : ?>
											<a class="btn main-button y-u-w-full" href="<?php echo esc_url( $pay_url ); ?>">إتمام الدفع</a>
										<?php endif; ?>

									<?php if ( $has_custom_name ) : ?>
										<div class="yaamama-rename-wrap">
											<button type="button" class="yaamama-rename-toggle btn black-outline-button y-u-p-8 y-u-px-16 y-u-text-xs y-u-w-full" style="display:flex;align-items:center;justify-content:center;gap:6px;">
												<i class="fa-solid fa-pen" style="font-size:11px;"></i> تعديل اسم المتجر
											</button>
											<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="yaamama-rename-form" style="display:none;margin-top:8px;">
												<?php wp_nonce_field( 'yaamama_rename_store', 'yaamama_rename_store_nonce' ); ?>
												<input type="hidden" name="action" value="yaamama_rename_store">
												<input type="hidden" name="subscription_id" value="<?php echo esc_attr( $sub['id'] ); ?>">
												<div style="display:flex;gap:8px;">
													<input type="text" name="store_name" class="y-u-p-8 y-u-rounded-s y-u-border" style="flex:1;font-size:13px;" value="<?php echo esc_attr( $store_name ); ?>" placeholder="اسم المتجر الجديد" required>
													<button type="submit" class="btn main-button y-u-p-8 y-u-px-12 y-u-text-xs" style="white-space:nowrap;">حفظ</button>
												</div>
											</form>
										</div>
									<?php else : ?>
										<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="y-u-flex y-u-flex-col y-u-gap-8" style="background:#fffbeb;padding:12px;border-radius:8px;border:1px solid #fde68a;">
											<?php wp_nonce_field( 'yaamama_rename_store', 'yaamama_rename_store_nonce' ); ?>
											<input type="hidden" name="action" value="yaamama_rename_store">
											<input type="hidden" name="subscription_id" value="<?php echo esc_attr( $sub['id'] ); ?>">
											<label class="y-u-text-xs y-u-font-bold" style="color:#d97706;">أدخل اسم متجرك</label>
											<input type="text" name="store_name" class="y-u-w-full y-u-p-10 y-u-rounded-s y-u-border" placeholder="اسم المتجر" required>
											<button type="submit" class="btn main-button y-u-p-8 y-u-px-16 y-u-text-xs">حفظ اسم المتجر</button>
										</form>
									<?php endif; ?>

									<?php if ( $plan_prices ) : ?>
										<div class="store-actions-row">
											<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="y-u-flex y-u-flex-col y-u-gap-12">
												<?php wp_nonce_field( 'yaamama_switch_subscription', 'yaamama_switch_subscription_nonce' ); ?>
												<input type="hidden" name="action" value="yaamama_switch_subscription">
												<input type="hidden" name="subscription_id" value="<?php echo esc_attr( $sub['id'] ); ?>">
												<select name="plan_price_id" class="y-u-w-full y-u-p-8 y-u-rounded-s y-u-border">
													<?php foreach ( $plan_prices as $pprice ) : ?>
														<option value="<?php echo esc_attr( $pprice['id'] ); ?>" <?php selected( $pprice['id'], $sub['plan_price_id'] ); ?>>
															<?php echo esc_html( $pprice['period'] ); ?> - <?php echo esc_html( number_format( (float) $pprice['price'], 2 ) ); ?>
														</option>
													<?php endforeach; ?>
												</select>
												<button type="submit" class="btn main-button y-u-p-8 y-u-px-16 y-u-text-xs">تغيير الخطة</button>
											</form>

											<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="yaamama-cancel-form">
												<?php wp_nonce_field( 'yaamama_cancel_subscription', 'yaamama_cancel_subscription_nonce' ); ?>
												<input type="hidden" name="action" value="yaamama_cancel_subscription">
												<input type="hidden" name="subscription_id" value="<?php echo esc_attr( $sub['id'] ); ?>">
												<button type="submit" class="btn y-u-w-full" style="background-color:#ef4444;color:#ffffff;border:1px solid #ef4444;">إلغاء الاشتراك</button>
											</form>
										</div>
									<?php else : ?>
										<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" class="yaamama-cancel-form">
											<?php wp_nonce_field( 'yaamama_cancel_subscription', 'yaamama_cancel_subscription_nonce' ); ?>
											<input type="hidden" name="action" value="yaamama_cancel_subscription">
											<input type="hidden" name="subscription_id" value="<?php echo esc_attr( $sub['id'] ); ?>">
											<button type="submit" class="btn y-u-w-full" style="background-color:#ef4444;color:#ffffff;border:1px solid #ef4444;">إلغاء الاشتراك</button>
										</form>
									<?php endif; ?>
									</div>
								<?php endif; ?>
								</div>
							<?php endforeach; ?>
							<?php endif; ?>
						</div>
					<?php endif; ?>

					<?php if ( false ) : ?>
					<div class="stores-grid y-u-grid y-u-grid-3 y-u-gap-24">
						<div class="store-card y-u-rounded-m y-u-p-24 y-u-relative y-u-flex y-u-flex-col">
							<div class="status-badge ready y-u-absolute y-u-top-24 y-u-left-24 y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">
								<span class="status-dot"></span>
								جاهز
							</div>

							<div class="y-u-flex y-u-justify-center y-u-m-b-24 y-u-m-t-24">
								<div class="image-wrapper y-u-rounded-f y-u-p-4" style="border: 2px solid var(--y-color-primary);">
									<img src="<?php echo esc_url( $assets_uri . '/product.png' ); ?>" alt="Store" class="y-u-rounded-f"
										style="width: 120px; height: 120px; object-fit: cover;">
								</div>
							</div>

							<div class="y-u-text-center y-u-m-b-16">
								<h3 class="y-u-text-l y-u-font-bold primary-color y-u-m-b-4">متجر خطى</h3>
								<p class="y-u-text-muted y-u-text-s">متجر أزياء وحقائب</p>
							</div>

							<div class="y-u-flex y-u-justify-between y-u-items-center y-u-m-b-16">
								<span class="package-badge y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">باقة اليمامة</span>
								<p class="y-u-text-s y-u-font-medium y-u-m-0">بدأ في : 1 اكتوبر 2025</p>
							</div>

							<div class="divider y-u-m-b-24"></div>

							<div class="store-actions y-u-flex y-u-flex-col y-u-gap-12">
								<button class="btn main-button y-u-w-full">دخول</button>
								<div class="y-u-grid y-u-grid-2 y-u-gap-12">
									<button class="btn black-outline-button y-u-w-full">إدارة</button>
									<button class="btn black-outline-button y-u-w-full">تفاصيل</button>
								</div>
							</div>
						</div>
						<div class="store-card y-u-rounded-m y-u-p-24 y-u-relative y-u-flex y-u-flex-col">
							<div class="status-badge ready y-u-absolute y-u-top-24 y-u-left-24 y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">
								<span class="status-dot"></span>
								جاهز
							</div>

							<div class="y-u-flex y-u-justify-center y-u-m-b-24 y-u-m-t-24">
								<div class="image-wrapper y-u-rounded-f y-u-p-4" style="border: 2px solid var(--y-color-primary);">
									<img src="<?php echo esc_url( $assets_uri . '/product.png' ); ?>" alt="Store" class="y-u-rounded-f"
										style="width: 120px; height: 120px; object-fit: cover;">
								</div>
							</div>

							<div class="y-u-text-center y-u-m-b-16">
								<h3 class="y-u-text-l y-u-font-bold primary-color y-u-m-b-4">متجر خطى</h3>
								<p class="y-u-text-muted y-u-text-s">متجر أزياء وحقائب</p>
							</div>

							<div class="y-u-flex y-u-justify-between y-u-items-center y-u-m-b-16">
								<span class="package-badge y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">باقة اليمامة</span>
								<p class="y-u-text-s y-u-font-medium y-u-m-0">بدأ في : 1 اكتوبر 2025</p>
							</div>

							<div class="divider y-u-m-b-24"></div>

							<div class="store-actions y-u-flex y-u-flex-col y-u-gap-12">
								<button class="btn main-button y-u-w-full">دخول</button>
								<div class="y-u-grid y-u-grid-2 y-u-gap-12">
									<button class="btn black-outline-button y-u-w-full">إدارة</button>
									<button class="btn black-outline-button y-u-w-full">تفاصيل</button>
								</div>
							</div>
						</div>
						<div class="store-card y-u-rounded-m y-u-p-24 y-u-relative y-u-flex y-u-flex-col">
							<div class="status-badge ready y-u-absolute y-u-top-24 y-u-left-24 y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">
								<span class="status-dot"></span>
								جاهز
							</div>

							<div class="y-u-flex y-u-justify-center y-u-m-b-24 y-u-m-t-24">
								<div class="image-wrapper y-u-rounded-f y-u-p-4" style="border: 2px solid var(--y-color-primary);">
									<img src="<?php echo esc_url( $assets_uri . '/product.png' ); ?>" alt="Store" class="y-u-rounded-f"
										style="width: 120px; height: 120px; object-fit: cover;">
								</div>
							</div>

							<div class="y-u-text-center y-u-m-b-16">
								<h3 class="y-u-text-l y-u-font-bold primary-color y-u-m-b-4">متجر خطى</h3>
								<p class="y-u-text-muted y-u-text-s">متجر أزياء وحقائب</p>
							</div>

							<div class="y-u-flex y-u-justify-between y-u-items-center y-u-m-b-16">
								<span class="package-badge y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">باقة اليمامة</span>
								<p class="y-u-text-s y-u-font-medium y-u-m-0">بدأ في : 1 اكتوبر 2025</p>
							</div>

							<div class="divider y-u-m-b-24"></div>

							<div class="store-actions y-u-flex y-u-flex-col y-u-gap-12">
								<button class="btn main-button y-u-w-full">دخول</button>
								<div class="y-u-grid y-u-grid-2 y-u-gap-12">
									<button class="btn black-outline-button y-u-w-full">إدارة</button>
									<button class="btn black-outline-button y-u-w-full">تفاصيل</button>
								</div>
							</div>
						</div>

						<div class="store-card y-u-rounded-m y-u-p-24 y-u-relative y-u-flex y-u-flex-col">
							<div class="status-badge preparing y-u-absolute y-u-top-24 y-u-left-24 y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">
								<span class="status-dot"></span>
								جاري التجهيز
							</div>

							<div class="y-u-flex y-u-justify-center y-u-m-b-24 y-u-m-t-24">
								<div class="image-wrapper y-u-rounded-f y-u-p-4" style="border: 2px solid var(--y-color-primary);">
									<img src="<?php echo esc_url( $assets_uri . '/product.png' ); ?>" alt="Store" class="y-u-rounded-f"
										style="width: 120px; height: 120px; object-fit: cover;">
								</div>
							</div>

							<div class="y-u-text-center y-u-m-b-16">
								<h3 class="y-u-text-l y-u-font-bold primary-color y-u-m-b-4">متجر خطى</h3>
								<p class="y-u-text-muted y-u-text-s">متجر أزياء وحقائب</p>
							</div>

							<div class="y-u-flex y-u-justify-between y-u-items-center y-u-m-b-16">
								<p class="y-u-text-s y-u-font-medium y-u-m-0">بدأ في : 1 اكتوبر 2025</p>
								<span class="package-badge y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">باقة اليمامة</span>
							</div>

							<div class="divider y-u-m-b-24"></div>

							<div class="store-actions y-u-flex y-u-flex-col y-u-gap-12">
								<button class="btn main-button y-u-w-full">دخول</button>
								<div class="y-u-grid y-u-grid-2 y-u-gap-12">
									<button class="btn black-outline-button y-u-w-full">إدارة</button>
									<button class="btn black-outline-button y-u-w-full">تفاصيل</button>
								</div>
							</div>
						</div>
						<div class="store-card y-u-rounded-m y-u-p-24 y-u-relative y-u-flex y-u-flex-col">
							<div class="status-badge preparing y-u-absolute y-u-top-24 y-u-left-24 y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">
								<span class="status-dot"></span>
								جاري التجهيز
							</div>

							<div class="y-u-flex y-u-justify-center y-u-m-b-24 y-u-m-t-24">
								<div class="image-wrapper y-u-rounded-f y-u-p-4" style="border: 2px solid var(--y-color-primary);">
									<img src="<?php echo esc_url( $assets_uri . '/product.png' ); ?>" alt="Store" class="y-u-rounded-f"
										style="width: 120px; height: 120px; object-fit: cover;">
								</div>
							</div>

							<div class="y-u-text-center y-u-m-b-16">
								<h3 class="y-u-text-l y-u-font-bold primary-color y-u-m-b-4">متجر خطى</h3>
								<p class="y-u-text-muted y-u-text-s">متجر أزياء وحقائب</p>
							</div>

							<div class="y-u-flex y-u-justify-between y-u-items-center y-u-m-b-16">
								<p class="y-u-text-s y-u-font-medium y-u-m-0">بدأ في : 1 اكتوبر 2025</p>
								<span class="package-badge y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">باقة اليمامة</span>
							</div>

							<div class="divider y-u-m-b-24"></div>

							<div class="store-actions y-u-flex y-u-flex-col y-u-gap-12">
								<button class="btn main-button y-u-w-full">دخول</button>
								<div class="y-u-grid y-u-grid-2 y-u-gap-12">
									<button class="btn black-outline-button y-u-w-full">إدارة</button>
									<button class="btn black-outline-button y-u-w-full">تفاصيل</button>
								</div>
							</div>
						</div>
						<div class="store-card y-u-rounded-m y-u-p-24 y-u-relative y-u-flex y-u-flex-col">
							<div class="status-badge preparing y-u-absolute y-u-top-24 y-u-left-24 y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">
								<span class="status-dot"></span>
								جاري التجهيز
							</div>

							<div class="y-u-flex y-u-justify-center y-u-m-b-24 y-u-m-t-24">
								<div class="image-wrapper y-u-rounded-f y-u-p-4" style="border: 2px solid var(--y-color-primary);">
									<img src="<?php echo esc_url( $assets_uri . '/product.png' ); ?>" alt="Store" class="y-u-rounded-f"
										style="width: 120px; height: 120px; object-fit: cover;">
								</div>
							</div>

							<div class="y-u-text-center y-u-m-b-16">
								<h3 class="y-u-text-l y-u-font-bold primary-color y-u-m-b-4">متجر خطى</h3>
								<p class="y-u-text-muted y-u-text-s">متجر أزياء وحقائب</p>
							</div>

							<div class="y-u-flex y-u-justify-between y-u-items-center y-u-m-b-16">
								<p class="y-u-text-s y-u-font-medium y-u-m-0">بدأ في : 1 اكتوبر 2025</p>
								<span class="package-badge y-u-p-12 y-u-py-4 y-u-rounded-m y-u-text-xs y-u-font-bold">باقة اليمامة</span>
							</div>

							<div class="divider y-u-m-b-24"></div>

							<div class="store-actions y-u-flex y-u-flex-col y-u-gap-12">
								<button class="btn main-button y-u-w-full">دخول</button>
								<div class="y-u-grid y-u-grid-2 y-u-gap-12">
									<button class="btn black-outline-button y-u-w-full">إدارة</button>
									<button class="btn black-outline-button y-u-w-full">تفاصيل</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<?php endif; ?>
				</div>

				<div class="page-3">
					<div class="page-header y-u-m-b-32">
						<h1 class="y-u-text-xl y-u-font-bold y-u-m-b-8">إدارة الفواتير</h1>
						<p class="y-u-text-muted y-u-text-s">قم بإدارة جميع فواتيرك الصادرة والواردة</p>
					</div>

					<div class="invoice-filters y-u-flex y-u-gap-16 y-u-m-b-32 y-u-flex-wrap">
						<button class="tab-btn active" data-invoice-filter="all">الكل</button>
						<button class="tab-btn" data-invoice-filter="paid">مدفوعة</button>
						<button class="tab-btn" data-invoice-filter="unpaid">غير مدفوعة</button>
						<button class="tab-btn" data-invoice-filter="late">متأخرة</button>
					</div>

					<?php
					if ( function_exists( 'wc_get_orders' ) ) {
						$orders = wc_get_orders(
							array(
								'customer_id' => $current_user->ID,
								'limit'       => 20,
								'orderby'     => 'date',
								'order'       => 'DESC',
							)
						);
					} else {
						$orders = array();
					}
					?>
					<?php if ( $orders ) : ?>
						<div class="invoice-table y-u-overflow-x-auto">
							<table class="y-u-w-full">
								<thead>
									<tr class="y-u-border-b">
										<th class="y-u-text-end y-u-p-16 y-u-font-bold">التاريخ</th>
										<th class="y-u-text-end y-u-p-16 y-u-font-bold">المتجر</th>
										<th class="y-u-text-end y-u-p-16 y-u-font-bold">الوصف</th>
										<th class="y-u-text-end y-u-p-16 y-u-font-bold">السعر</th>
										<th class="y-u-text-end y-u-p-16 y-u-font-bold">الحالة</th>
										<th class="y-u-text-end y-u-p-16 y-u-font-bold">تنزيل</th>
									</tr>
								</thead>
								<tbody>
									<?php foreach ( $orders as $order ) : ?>
										<?php
										$status = $order->get_status();
										if ( in_array( $status, array( 'processing', 'completed' ), true ) ) {
											$status_class = 'invoice-status-badge--paid';
											$status_label = 'مدفوع';
										} elseif ( in_array( $status, array( 'pending', 'on-hold' ), true ) ) {
											$status_class = 'invoice-status-badge--unpaid';
											$status_label = 'غير مدفوع';
										} else {
											$status_class = 'invoice-status-badge--late';
											$status_label = 'متأخرة';
										}

										$item_names = array();
										foreach ( $order->get_items() as $item ) {
											$item_names[] = $item->get_name();
										}
										$description = $item_names ? implode( '، ', $item_names ) : 'طلب #' . $order->get_id();
										$order_date  = $order->get_date_created() ? $order->get_date_created()->date_i18n( 'j F Y' ) : '';
										$total       = $order->get_total();
										$store_name  = (string) $order->get_meta( 'yaamama_store_name' );
										$store_name  = $store_name !== '' ? $store_name : $current_user->display_name;
										$items_data  = array();
										foreach ( $order->get_items() as $item ) {
											$items_data[] = array(
												'name' => $item->get_name(),
												'qty'  => $item->get_quantity(),
											);
										}
										?>
										<tr class="y-u-border-b" data-invoice-status="<?php echo esc_attr( $status_class ); ?>">
											<td class="y-u-p-16 y-u-text-s"><?php echo esc_html( $order_date ); ?></td>
											<td class="y-u-p-16 y-u-text-s"><?php echo esc_html( $current_user->display_name ); ?></td>
											<td class="y-u-p-16 y-u-text-s"><?php echo esc_html( $description ); ?></td>
											<td class="y-u-p-16 y-u-text-s y-u-flex y-u-items-center y-u-gap-4">
												<span class="y-u-text-s y-u-font-bold"><?php echo esc_html( number_format( (float) $total, 2 ) ); ?></span>
												<img src="<?php echo esc_url( $assets_uri . '/ryal-prim.svg' ); ?>" alt="Ryal" style="width: 16px;">
											</td>
											<td class="y-u-p-16 y-u-text-s">
												<span class="invoice-status-badge <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $status_label ); ?></span>
											</td>
											<td class="y-u-p-16">
												<a class="download-btn y-u-flex y-u-items-center y-u-justify-center y-u-rounded-f"
													style="width: 32px; height: 32px; border: 1px solid var(--y-color-border); background: transparent;"
													href="<?php echo esc_url( $order->get_view_order_url() ); ?>"
													data-invoice-download
													data-order-id="<?php echo esc_attr( $order->get_id() ); ?>"
													data-order-date="<?php echo esc_attr( $order_date ); ?>"
													data-order-total="<?php echo esc_attr( number_format( (float) $total, 2 ) ); ?>"
													data-store-name="<?php echo esc_attr( $store_name ); ?>"
													data-items="<?php echo esc_attr( wp_json_encode( $items_data ) ); ?>">
													<i class="fa-solid fa-download"></i>
												</a>
											</td>
										</tr>
									<?php endforeach; ?>
								</tbody>
							</table>
						</div>
					<?php else : ?>
						<p class="y-u-text-muted">لا توجد فواتير حتى الآن.</p>
					<?php endif; ?>

					<?php if ( false ) : ?>
					<div class="invoice-table y-u-overflow-x-auto">
						<table class="y-u-w-full">
							<thead>
								<tr class="y-u-border-b">
									<th class="y-u-text-end y-u-p-16 y-u-font-bold">التاريخ</th>
									<th class="y-u-text-end y-u-p-16 y-u-font-bold">المتجر</th>
									<th class="y-u-text-end y-u-p-16 y-u-font-bold">الوصف</th>
									<th class="y-u-text-end y-u-p-16 y-u-font-bold">السعر</th>
									<th class="y-u-text-end y-u-p-16 y-u-font-bold">الحالة</th>
									<th class="y-u-text-end y-u-p-16 y-u-font-bold">تنزيل</th>
								</tr>
							</thead>
							<tbody>
								<tr class="y-u-border-b">
									<td class="y-u-p-16 y-u-text-s">1 يناير 2026 - يناير 2026</td>
									<td class="y-u-p-16 y-u-text-s">متجر خطي</td>
									<td class="y-u-p-16 y-u-text-s">اشتراك سنوي</td>

									<td class="y-u-p-16 y-u-text-s y-u-flex y-u-items-center y-u-gap-4">
										<span class="y-u-text-s y-u-font-bold">15,000</span>
										<img src="<?php echo esc_url( $assets_uri . '/ryal-prim.svg' ); ?>" alt="Ryal" style="width: 16px;">
									</td>
									<td class="y-u-p-16 y-u-text-s">
										<span class="invoice-status-badge invoice-status-badge--paid">مدفوع</span>
									</td>
									<td class="y-u-p-16">
										<button class="download-btn y-u-flex y-u-items-center y-u-justify-center y-u-rounded-f"
											style="width: 32px; height: 32px; border: 1px solid var(--y-color-border); background: transparent;">
											<i class="fa-solid fa-download"></i>
										</button>
									</td>
								</tr>
								<tr class="y-u-border-b">
									<td class="y-u-p-16 y-u-text-s">1 يناير 2026 - يناير 2026</td>
									<td class="y-u-p-16 y-u-text-s">متجر خطي</td>
									<td class="y-u-p-16 y-u-text-s">اشتراك سنوي</td>

									<td class="y-u-p-16 y-u-text-s y-u-flex y-u-items-center y-u-gap-4">
										<span class="y-u-text-s y-u-font-bold">15,000</span>
										<img src="<?php echo esc_url( $assets_uri . '/ryal-prim.svg' ); ?>" alt="Ryal" style="width: 16px;">
									</td>
									<td class="y-u-p-16 y-u-text-s">
										<span class="invoice-status-badge invoice-status-badge--unpaid">غير مدفوع</span>
									</td>
									<td class="y-u-p-16">
										<button class="download-btn y-u-flex y-u-items-center y-u-justify-center y-u-rounded-f"
											style="width: 32px; height: 32px; border: 1px solid var(--y-color-border); background: transparent;">
											<i class="fa-solid fa-download"></i>
										</button>
									</td>
								</tr>
								<tr class="y-u-border-b">
									<td class="y-u-p-16 y-u-text-s">1 يناير 2026 - يناير 2026</td>
									<td class="y-u-p-16 y-u-text-s">متجر خطي</td>
									<td class="y-u-p-16 y-u-text-s">اشتراك سنوي</td>

									<td class="y-u-p-16 y-u-text-s y-u-flex y-u-items-center y-u-gap-4">
										<span class="y-u-text-s y-u-font-bold">15,000</span>
										<img src="<?php echo esc_url( $assets_uri . '/ryal-prim.svg' ); ?>" alt="Ryal" style="width: 16px;">
									</td>
									<td class="y-u-p-16 y-u-text-s">
										<span class="invoice-status-badge invoice-status-badge--paid">مدفوع</span>
									</td>
									<td class="y-u-p-16">
										<button class="download-btn y-u-flex y-u-items-center y-u-justify-center y-u-rounded-f"
											style="width: 32px; height: 32px; border: 1px solid var(--y-color-border); background: transparent;">
											<i class="fa-solid fa-download"></i>
										</button>
									</td>
								</tr>
								<tr class="y-u-border-b">
									<td class="y-u-p-16 y-u-text-s">1 يناير 2026 - يناير 2026</td>
									<td class="y-u-p-16 y-u-text-s">متجر خطي</td>
									<td class="y-u-p-16 y-u-text-s">اشتراك سنوي</td>

									<td class="y-u-p-16 y-u-text-s y-u-flex y-u-items-center y-u-gap-4">
										<span class="y-u-text-s y-u-font-bold">15,000</span>
										<img src="<?php echo esc_url( $assets_uri . '/ryal-prim.svg' ); ?>" alt="Ryal" style="width: 16px;">
									</td>
									<td class="y-u-p-16 y-u-text-s">
										<span class="invoice-status-badge invoice-status-badge--late">متأخرة</span>
									</td>
									<td class="y-u-p-16">
										<button class="download-btn y-u-flex y-u-items-center y-u-justify-center y-u-rounded-f"
											style="width: 32px; height: 32px; border: 1px solid var(--y-color-border); background: transparent;">
											<i class="fa-solid fa-download"></i>
										</button>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php endif; ?>
				</div>
			</div>

			<nav class="account-bottom-nav">
				<label for="page-1" class="bottom-nav-item bottom-nav-item--page-1">
					<i class="fa-regular fa-user"></i>
					<span>حسابي</span>
				</label>
				<label for="page-2" class="bottom-nav-item bottom-nav-item--page-2">
					<i class="fa-solid fa-store"></i>
					<span>متاجري</span>
				</label>
				<label for="page-3" class="bottom-nav-item bottom-nav-item--page-3">
					<i class="fa-regular fa-file-lines"></i>
					<span>فواتيري</span>
				</label>
				<a href="<?php echo esc_url( wp_logout_url( home_url( '/login' ) ) ); ?>" class="bottom-nav-item bottom-nav-item--logout">
					<i class="fa-solid fa-arrow-right-from-bracket"></i>
					<span>تسجيل خروج</span>
				</a>
			</nav>
		</div>
	</section>
</main>

<style>
	@keyframes yaamama-spin { to { transform: rotate(360deg); } }
	.yaamama-store-ready-dynamic {
		text-align: center; animation: yaamama-fadeIn .5s ease;
	}
	@keyframes yaamama-fadeIn { from { opacity:0; transform:translateY(8px); } to { opacity:1; transform:none; } }
	.status-badge.suspended {
		background: #fef3c7;
		color: #92400e;
	}
	.status-badge.suspended .status-dot {
		background: #f59e0b;
	}
	.store-card.is-suspended {
		opacity: 0.85;
		border: 2px dashed #f59e0b;
	}
</style>
<script>
(function(){
	var boxes = document.querySelectorAll('.yaamama-provision-status');
	if (!boxes.length) return;
	var ajaxUrl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';

	function showReady(box, storeUrl) {
		var card = box.closest('.store-card');
		var badge = card ? card.querySelector('.status-badge') : null;
		if (badge) {
			badge.className = badge.className.replace('preparing', 'ready');
			badge.innerHTML = '<span class="status-dot"></span> جاهز';
		}
		var subId = box.getAttribute('data-subscription-id');
		box.className = 'yaamama-store-ready-dynamic y-u-m-b-16';
		box.style.cssText = 'text-align:center;padding:24px;border-radius:8px;background:linear-gradient(135deg,#f0fdf4 0%,#ecfdf5 100%);border:2px solid #86efac;';
		box.innerHTML = '<div style="font-size:36px;margin-bottom:8px;">🎉</div>' +
			'<p style="color:#059669;font-weight:bold;margin:0 0 12px;">متجرك جاهز ويعمل!</p>' +
			(storeUrl ? '<div style="display:flex;gap:8px;margin-top:12px;">' +
			'<a href="' + storeUrl + '" target="_blank" style="flex:1;display:block;text-align:center;background:#059669;padding:12px 8px;border-radius:8px;color:#fff;text-decoration:none;font-weight:bold;font-size:14px;">زيارة المتجر</a>' +
			'<button type="button" class="yaamama-auto-login-btn" data-subscription-id="' + subId + '" style="flex:1;display:block;text-align:center;background:#1e40af;border:none;padding:12px 8px;border-radius:8px;color:#fff;cursor:pointer;font-weight:bold;font-size:14px;">لوحة تحكم المتجر</button>' +
			'</div>' : '');
	}

	function showFailed(box) {
		box.style.background = '#fef2f2';
		box.style.borderColor = '#fecaca';
		box.innerHTML = '<p style="color:#dc2626;font-weight:bold;margin:0;">فشل إنشاء المتجر</p>' +
			'<p style="color:#6b7280;font-size:12px;margin:8px 0 0;">يرجى إعادة تحميل الصفحة للمحاولة مرة أخرى.</p>';
	}

	boxes.forEach(function(box) {
		var subId = box.getAttribute('data-subscription-id');
		if (!subId) return;

		var poll = function() {
			var data = new FormData();
			data.append('action', 'yaamama_check_provision_status');
			data.append('subscription_id', subId);

			fetch(ajaxUrl, { method: 'POST', credentials: 'same-origin', body: data })
			.then(function(r) { return r.json(); })
			.then(function(res) {
				if (!res.success || !res.data) { setTimeout(poll, 5000); return; }
				var d = res.data;

				if (d.status === 'completed' || d.status === 'active') {
					var bar = box.querySelector('.provision-bar-fill');
					var pct = box.querySelector('.provision-percent');
					if (bar) bar.style.width = '100%';
					if (pct) pct.textContent = '100%';
					setTimeout(function() { showReady(box, d.store_url); }, 800);
					return;
				}

				if (d.status === 'failed') {
					showFailed(box);
					return;
				}

				var progress = d.progress || 0;
				var bar = box.querySelector('.provision-bar-fill');
				var pct = box.querySelector('.provision-percent');
				var label = box.querySelector('.provision-step-label');
				if (bar) bar.style.width = progress + '%';
				if (pct) pct.textContent = progress + '%';
				if (label && d.step_label) label.textContent = d.step_label + '...';

				setTimeout(poll, 4000);
			})
			.catch(function() { setTimeout(poll, 8000); });
		};

		setTimeout(poll, 2000);
	});
})();

document.addEventListener('click', function(e) {
	var btn = e.target.closest('.yaamama-auto-login-btn');
	if (!btn) return;
	e.preventDefault();

	var subId = btn.getAttribute('data-subscription-id');
	if (!subId) return;

	var origText = btn.textContent;
	btn.textContent = 'جاري فتح لوحة التحكم...';
	btn.disabled = true;

	var formData = new FormData();
	formData.append('action', 'yaamama_store_auto_login');
	formData.append('subscription_id', subId);

	fetch('<?php echo esc_url( admin_url("admin-ajax.php") ); ?>', {
		method: 'POST',
		credentials: 'same-origin',
		body: formData
	})
	.then(function(r) { return r.json(); })
	.then(function(resp) {
		if (resp.success && resp.data && resp.data.login_url) {
			window.open(resp.data.login_url, '_blank');
		} else {
			alert(resp.data || 'فشل فتح لوحة التحكم');
		}
		btn.textContent = origText;
		btn.disabled = false;
	})
	.catch(function() {
		alert('حدث خطأ في الاتصال');
		btn.textContent = origText;
		btn.disabled = false;
	});
});

document.querySelectorAll('.yaamama-cancel-form').forEach(function(form) {
	form.addEventListener('submit', function(e) {
		var confirmed = confirm('هل أنت متأكد من إلغاء الاشتراك؟\n\nسيتم تعليق متجرك فوراً ولن يتمكن الزوار من الوصول إليه.\nيمكنك تجديد اشتراكك لاحقاً لإعادة تفعيله.');
		if (!confirmed) {
			e.preventDefault();
		}
	});
});

document.querySelectorAll('.yaamama-rename-toggle').forEach(function(btn) {
	btn.addEventListener('click', function() {
		var wrap = btn.closest('.yaamama-rename-wrap');
		var form = wrap.querySelector('.yaamama-rename-form');
		var isHidden = form.style.display === 'none';
		form.style.display = isHidden ? 'block' : 'none';
		if (isHidden) {
			form.querySelector('input[name="store_name"]').focus();
		}
	});
});
</script>

<?php
get_footer();
?>
