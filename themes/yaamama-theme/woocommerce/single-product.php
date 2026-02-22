<?php
get_header();
$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';

if ( have_posts() ) :
	while ( have_posts() ) :
		the_post();
		$product  = wc_get_product( get_the_ID() );
		$demo_url = get_post_meta( get_the_ID(), 'demo_url', true );

		if ( ! $product ) {
			continue;
		}

		$rating = $product->get_average_rating();
		$price  = $product->get_price_html();
		?>
		<main>
			<section class="template-details-section nameofpage-section special-bg">
				<div class="product-hero-image">
					<div class="image-wrapper">
						<?php echo $product->get_image( 'full', array( 'class' => 'product-hero-img' ) ); ?>
					</div>
				</div>

				<div class="container y-u-max-w-1200">
					<div class="details-grid">
						<div class="details-content">
							<div class="headers">
								<h1><?php the_title(); ?></h1>
								<h2><?php echo wp_kses_post( wc_get_product_category_list( $product->get_id(), ', ' ) ); ?></h2>
							</div>

							<div class="rating-price-row">
								<div class="price-container">
									<?php if ( $price ) : ?>
										<span class="price-value"><?php echo wp_kses_post( $price ); ?></span>
									<?php endif; ?>
								</div>

								<?php if ( $rating ) : ?>
									<div class="rating-badge">
										<i class="fa-solid fa-star"></i>
										<span class="rating-number"><?php echo esc_html( $rating ); ?></span>
									</div>
								<?php endif; ?>
							</div>

							<div class="actions-row">
								<a href="#plans" class="btn main-button y-u-px-48">
									شراء القالب
								</a>
								<?php if ( $demo_url ) : ?>
									<a href="<?php echo esc_url( $demo_url ); ?>" class="btn black-outline-button y-u-px-48" target="_blank" rel="noopener">
										عرض تجريبي
									</a>
								<?php endif; ?>
							</div>

							<?php
							$gallery_ids = $product->get_gallery_image_ids();
							if ( $gallery_ids ) :
								?>
								<div class="product-gallery">
									<?php foreach ( $gallery_ids as $gallery_id ) : ?>
										<div class="gallery-item">
											<?php echo wp_get_attachment_image( $gallery_id, 'large' ); ?>
										</div>
									<?php endforeach; ?>
								</div>
							<?php endif; ?>

							<div class="product-description-box">
								<?php the_content(); ?>
							</div>
						</div>
					</div>

					<section class="pricing-content-area">
						<div class="features-row container">
							<div class="feature-badge">
								<div class="badge-icon">
									<i class="fa-solid fa-chart-line"></i>
								</div>
								<span class="badge-title">تحليلات ذكية</span>
								<p class="badge-subtitle">تتبع المبيعات والزوار بدقة</p>
							</div>
							<div class="feature-badge">
								<div class="badge-icon">
									<i class="fa-solid fa-bolt"></i>
								</div>
								<span class="badge-title">سرعة فائقة</span>
								<p class="badge-subtitle">تحميل المتجر في أقل من ثانية واحدة</p>
							</div>
							<div class="feature-badge">
								<div class="badge-icon">
									<i class="fa-solid fa-shield-halved"></i>
								</div>
								<span class="badge-title">أمان متقدم</span>
								<p class="badge-subtitle">حماية SSL وتشفير كامل للبيانات</p>
							</div>
							<div class="feature-badge">
								<div class="badge-icon">
									<i class="fa-solid fa-mobile-screen"></i>
								</div>
								<span class="badge-title">تصميم متجاوب</span>
								<p class="badge-subtitle">تجربة مثالية على جميع الأجهزة</p>
							</div>
						</div>
					</section>

					<section id="plans" class="subscription-section">
						<div class="container y-u-max-w-1200">
							<?php
							if ( function_exists( 'yaamama_subscriptions_get_plans_for_product' ) ) {
								$plans = yaamama_subscriptions_get_plans_for_product( $product->get_id() );
							} else {
								$plans = array();
							}

							$has_billing_toggle = false;
							$has_month          = false;
							$has_year           = false;
							if ( $plans ) {
								foreach ( $plans as $plan ) {
									if ( empty( $plan['prices'] ) ) {
										continue;
									}
									$periods = array();
									foreach ( $plan['prices'] as $price_row ) {
										$periods[ $price_row['period'] ] = true;
									}
									$has_month = $has_month || ! empty( $periods['month'] );
									$has_year  = $has_year || ! empty( $periods['year'] );
									if ( ! empty( $periods['month'] ) && ! empty( $periods['year'] ) ) {
										$has_billing_toggle = true;
									}
								}
							}
							$global_default_period = $has_month ? 'month' : ( $has_year ? 'year' : 'month' );
							?>
							<div class="subscription-header">
								<h2 class="sub-title">اختر الخطة المناسبة لـ <?php the_title(); ?></h2>
								<?php if ( $has_billing_toggle ) : ?>
									<div class="toggle-wrapper">
										<div class="discount-tag">وفر 20%</div>
										<div class="billing-toggle">
											<button class="toggle-option<?php echo $global_default_period === 'month' ? ' active' : ''; ?>" data-billing-toggle data-period="month">
												اشتراك ( شهري )
											</button>
											<button class="toggle-option<?php echo $global_default_period === 'year' ? ' active' : ''; ?>" data-billing-toggle data-period="year">
												اشتراك ( سنوي )
											</button>
										</div>
									</div>
								<?php endif; ?>
							</div>

							<div class="plans-container">
								<?php
								if ( $plans ) {
									foreach ( $plans as $plan ) {
										if ( empty( $plan['prices'] ) ) {
											continue;
										}

										$prices_by_period = array();
										foreach ( $plan['prices'] as $price_row ) {
											$prices_by_period[ $price_row['period'] ] = $price_row;
										}

										$default_period = $global_default_period;
										if ( empty( $prices_by_period[ $default_period ] ) ) {
											$default_period = isset( $prices_by_period['month'] ) ? 'month' : ( isset( $prices_by_period['year'] ) ? 'year' : '' );
										}
										$default_price = $default_period ? $prices_by_period[ $default_period ] : null;
										if ( ! $default_price ) {
											continue;
										}

										$trial_label_month = '';
										if ( ! empty( $prices_by_period['month'] ) && (int) $prices_by_period['month']['trial_days'] > 0 ) {
											$trial_label_month = sprintf( 'تجربة مجانية %d يوم', (int) $prices_by_period['month']['trial_days'] );
										}
										$trial_label_year = '';
										if ( ! empty( $prices_by_period['year'] ) && (int) $prices_by_period['year']['trial_days'] > 0 ) {
											$trial_label_year = sprintf( 'تجربة مجانية %d يوم', (int) $prices_by_period['year']['trial_days'] );
										}

										$features = array();
										if ( ! empty( $plan['description'] ) ) {
											$features = array_filter(
												array_map(
													'trim',
													preg_split( "/\r\n|\r|\n/", $plan['description'] )
												)
											);
										}
										?>
										<div
											class="plan-card"
											data-plan-card
											data-default-period="<?php echo esc_attr( $default_period ); ?>"
											data-price-month="<?php echo esc_attr( isset( $prices_by_period['month'] ) ? number_format( (float) $prices_by_period['month']['price'], 2, '.', '' ) : '' ); ?>"
											data-price-year="<?php echo esc_attr( isset( $prices_by_period['year'] ) ? number_format( (float) $prices_by_period['year']['price'], 2, '.', '' ) : '' ); ?>"
											data-price-id-month="<?php echo esc_attr( isset( $prices_by_period['month'] ) ? $prices_by_period['month']['id'] : '' ); ?>"
											data-price-id-year="<?php echo esc_attr( isset( $prices_by_period['year'] ) ? $prices_by_period['year']['id'] : '' ); ?>"
											data-trial-month="<?php echo esc_attr( $trial_label_month ); ?>"
											data-trial-year="<?php echo esc_attr( $trial_label_year ); ?>"
										>
											<div class="card-header">
												<h3 class="plan-name"><?php echo esc_html( $plan['name'] ); ?></h3>
												<?php if ( $default_period === 'month' && $trial_label_month ) : ?>
													<span class="plan-badge" data-plan-trial><?php echo esc_html( $trial_label_month ); ?></span>
												<?php elseif ( $default_period === 'year' && $trial_label_year ) : ?>
													<span class="plan-badge" data-plan-trial><?php echo esc_html( $trial_label_year ); ?></span>
												<?php else : ?>
													<span class="plan-badge" data-plan-trial style="display: none;"></span>
												<?php endif; ?>
												<div class="plan-price">
													<span data-plan-price><?php echo esc_html( number_format( (float) $default_price['price'], 2 ) ); ?></span>
													<span class="currency">ريال</span>
												</div>
											</div>

											<div class="card-divider"></div>

											<?php if ( $features ) : ?>
												<ul class="plan-features">
													<?php foreach ( $features as $feature ) : ?>
														<li>
															<i class="fa-regular fa-circle-check"></i>
															<span><?php echo esc_html( $feature ); ?></span>
														</li>
													<?php endforeach; ?>
												</ul>
											<?php endif; ?>

											<form class="cart" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
												<?php wp_nonce_field( 'yaamama_subscribe', 'yaamama_subscribe_nonce' ); ?>
												<input type="hidden" name="action" value="yaamama_subscribe">
												<input type="hidden" name="product_id" value="<?php echo esc_attr( $product->get_id() ); ?>">
												<input type="hidden" name="plan_price_id" value="<?php echo esc_attr( $default_price['id'] ); ?>" data-plan-price-id>
												<button type="submit" class="btn main-button plan-btn">اشترك الآن</button>
											</form>
										</div>
										<?php
									}
								} else {
									?>
									<p>لا توجد باقات متاحة لهذا القالب حاليًا.</p>
									<?php
								}
								?>
							</div>
						</div>
					</section>
				</div>
			</section>
		</main>
		<?php
	endwhile;
endif;

get_footer();
?>
