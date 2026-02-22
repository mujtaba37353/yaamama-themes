<?php
get_header();
$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';
?>

<main>
	<section class="template-details-section nameofpage-section special-bg">
		<div class="container y-u-max-w-1200">
			<div class="details-grid">
				<div class="details-content">
					<div class="headers">
						<h1>قالب خطي</h1>
						<h2>للتجارة الإلكترونية</h2>
					</div>

					<p class="description-text">
						قالب أنيق مناسب لعرض منتجات مميزة مثل الأحذية والشنط. يتميز بتصميم عصري يركز على جمالية المنتجات وسهولة
						التصفح.
					</p>

					<div class="rating-price-row">
						<div class="price-container">
							<span class="price-value">99</span>
							<span class="currency">
								<img src="<?php echo esc_url( $assets_uri . '/ryal-prim.svg' ); ?>" alt="ر.س">
							</span>
						</div>

						<div class="rating-badge">
							<i class="fa-solid fa-star"></i>
							<span class="rating-number">4.2</span>
						</div>
					</div>

					<div class="actions-row">
						<a href="<?php echo esc_url( home_url( '/payment' ) ); ?>" class="btn main-button y-u-px-48">
							شراء القالب
						</a>
						<button class="btn black-outline-button y-u-px-48">
							عرض تجريبى
						</button>
					</div>
				</div>

				<div class="details-image">
					<div class="image-wrapper">
						<img src="<?php echo esc_url( $assets_uri . '/single-temp-hero.png' ); ?>" alt="Khatti Template Mockup">
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

			<section class="subscription-section">
				<div class="container y-u-max-w-1200">
					<div class="subscription-header">
						<h2 class="sub-title">اختر الخطة المناسبة لقالب خطى</h2>

						<div class="toggle-wrapper">
							<div class="discount-tag">وفر 20%</div>
							<div class="billing-toggle">
								<button class="toggle-option active">اشتراك ( شهري )</button>
								<button class="toggle-option">اشتراك ( سنوي )</button>
							</div>
						</div>
					</div>

					<!-- TODO: Replace plans with WooCommerce subscription products for this template. -->
					<div class="plans-container">
						<div class="plan-card free-plan">
							<div class="card-header">
								<h3 class="plan-name">مجانية</h3>
								<span class="plan-badge">للمشاريع الصغيرة والمبتدئين</span>
							</div>

							<div class="card-divider"></div>

							<ul class="plan-features">
								<li>
									<i class="fa-regular fa-circle-check"></i>
									<span>متجر إلكتروني كامل</span>
								</li>
								<li>
									<i class="fa-regular fa-circle-check"></i>
									<span>تصميم متجاوب</span>
								</li>
								<li>
									<i class="fa-regular fa-circle-check"></i>
									<span>دعم فني أساسي</span>
								</li>
								<li>
									<i class="fa-regular fa-circle-check"></i>
									<span>حتى 500 منتج</span>
								</li>
								<li>
									<i class="fa-regular fa-circle-check"></i>
									<span>تصميم متجاوب</span>
								</li>
							</ul>

							<a href="<?php echo esc_url( home_url( '/payment' ) ); ?>" class="black-outline-button plan-btn">
								اشترك الآن
							</a>
						</div>

						<div class="plan-card pro-card">
							<div class="card-header">
								<h3 class="plan-name">الاحترافية</h3>
								<span class="plan-badge">للأعمال المتنامية والجادة</span>
								<div class="plan-price">99<span class="currency">ريال</span></div>
							</div>

							<div class="card-divider"></div>

							<ul class="plan-features">
								<li>
									<i class="fa-solid fa-circle-check"></i>
									<span>كل مميزات البداية</span>
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i>
									<span>منتجات غير محدودة</span>
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i>
									<span>محسن SEO</span>
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i>
									<span>تكامل مع بوابات الدفع</span>
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i>
									<span>تحليلات متقدمة</span>
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i>
									<span>دعم فني ذهبي</span>
								</li>
								<li>
									<i class="fa-solid fa-circle-check"></i>
									<span>دعم فني ذهبي</span>
								</li>
							</ul>

							<a href="<?php echo esc_url( home_url( '/payment' ) ); ?>" class="btn main-button plan-btn">
								اشتري الآن
							</a>
						</div>
					</div>
				</div>
			</section>

			<h2 class="section-title y-u-mt-80 y-u-mb-40">
				شاهد بعض المتاجر المماثلة
			</h2>

			<!-- TODO: Replace similar templates with WooCommerce data. -->
			<div class="themes-grid y-u-grid y-u-grid-3 y-u-gap-32">
				<div class="theme-card y-u-rounded-m y-u-overflow-hidden y-u-border">
					<div class="theme-image">
						<img src="<?php echo esc_url( $assets_uri . '/theme-img.png' ); ?>" alt="معاينة قالب متجر إلكتروني 1" />
					</div>
					<div class="theme-content y-u-p-24 y-u-flex y-u-flex-col y-u-gap-16">
						<div class="theme-badge y-u-flex y-u-items-center y-u-gap-8 light-primary-bg y-u-rounded-s y-u-px-16 y-u-py-4 y-u-w-fit">
							<i class="fa-solid fa-star primary-color"></i>
							<span class="y-u-text-s y-u-font-bold primary-color">أزياء</span>
						</div>
						<div class="theme-main-info y-u-flex y-u-justify-between y-u-items-center">
							<h3 class="y-u-text-l y-u-font-bold">خطى</h3>
							<div class="price y-u-flex y-u-items-center y-u-gap-8">
								<span class="y-u-text-l y-u-font-bold">990</span>
								<img src="<?php echo esc_url( $assets_uri . '/ryal-prim.svg' ); ?>" alt="Ryal" style="width: 16px;">
							</div>
						</div>
						<div class="theme-include y-u-flex y-u-items-center y-u-gap-8 y-u-text-muted">
							<i class="fa-solid fa-tag"></i>
							<span class="y-u-text-s y-u-font-bold">السعر شامل سعر الثيم وباقة اليمامة</span>
						</div>
						<div class="theme-actions y-u-grid y-u-grid-2 y-u-gap-12">
							<a href="<?php echo esc_url( home_url( '/single-temp' ) ); ?>" class="btn black-outline-button y-u-w-full">عرض</a>
							<a href="<?php echo esc_url( home_url( '/payment' ) ); ?>" class="btn main-button y-u-w-full">شراء القالب</a>
						</div>
					</div>
				</div>

				<div class="theme-card y-u-rounded-m y-u-overflow-hidden y-u-border">
					<div class="theme-image">
						<img src="<?php echo esc_url( $assets_uri . '/theme-img.png' ); ?>" alt="معاينة قالب متجر إلكتروني 2" />
					</div>
					<div class="theme-content y-u-p-24 y-u-flex y-u-flex-col y-u-gap-16">
						<div class="theme-badge y-u-flex y-u-items-center y-u-gap-8 light-primary-bg y-u-rounded-s y-u-px-16 y-u-py-4 y-u-w-fit">
							<i class="fa-solid fa-star primary-color"></i>
							<span class="y-u-text-s y-u-font-bold primary-color">أزياء</span>
						</div>
						<div class="theme-main-info y-u-flex y-u-justify-between y-u-items-center">
							<h3 class="y-u-text-l y-u-font-bold">خطى</h3>
							<div class="price y-u-flex y-u-items-center y-u-gap-8">
								<span class="y-u-text-l y-u-font-bold">990</span>
								<img src="<?php echo esc_url( $assets_uri . '/ryal-prim.svg' ); ?>" alt="Ryal" style="width: 16px;">
							</div>
						</div>
						<div class="theme-include y-u-flex y-u-items-center y-u-gap-8 y-u-text-muted">
							<i class="fa-solid fa-tag"></i>
							<span class="y-u-text-s y-u-font-bold">السعر شامل سعر الثيم وباقة اليمامة</span>
						</div>
						<div class="theme-actions y-u-grid y-u-grid-2 y-u-gap-12">
							<a href="<?php echo esc_url( home_url( '/single-temp' ) ); ?>" class="btn black-outline-button y-u-w-full">عرض</a>
							<a href="<?php echo esc_url( home_url( '/payment' ) ); ?>" class="btn main-button">شراء القالب</a>
						</div>
					</div>
				</div>

				<div class="theme-card y-u-rounded-m y-u-overflow-hidden y-u-border">
					<div class="theme-image">
						<img src="<?php echo esc_url( $assets_uri . '/theme-img.png' ); ?>" alt="معاينة قالب متجر إلكتروني 3" />
					</div>
					<div class="theme-content y-u-p-24 y-u-flex y-u-flex-col y-u-gap-16">
						<div class="theme-badge y-u-flex y-u-items-center y-u-gap-8 light-primary-bg y-u-rounded-s y-u-px-16 y-u-py-4 y-u-w-fit">
							<i class="fa-solid fa-star primary-color"></i>
							<span class="y-u-text-s y-u-font-bold primary-color">أزياء</span>
						</div>
						<div class="theme-main-info y-u-flex y-u-justify-between y-u-items-center">
							<h3 class="y-u-text-l y-u-font-bold">خطى</h3>
							<div class="price y-u-flex y-u-items-center y-u-gap-8">
								<span class="y-u-text-l y-u-font-bold">990</span>
								<img src="<?php echo esc_url( $assets_uri . '/ryal-prim.svg' ); ?>" alt="Ryal" style="width: 16px;">
							</div>
						</div>
						<div class="theme-include y-u-flex y-u-items-center y-u-gap-8 y-u-text-muted">
							<i class="fa-solid fa-tag"></i>
							<span class="y-u-text-s y-u-font-bold">السعر شامل سعر الثيم وباقة اليمامة</span>
						</div>
						<div class="theme-actions y-u-grid y-u-grid-2 y-u-gap-12">
							<a href="<?php echo esc_url( home_url( '/single-temp' ) ); ?>" class="btn black-outline-button y-u-w-full">عرض</a>
							<a href="<?php echo esc_url( home_url( '/payment' ) ); ?>" class="btn main-button">شراء القالب</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
?>
