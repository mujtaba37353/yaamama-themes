<?php
get_header();
?>

<main class="special-bg">
	<section class="container y-u-max-w-1200 faq-section y-u-py-40">
		<h1 class="u-font-bold y-u-m-b-24">دليلك السريع لمنصة اليمامة</h1>

		<div class="faq-list">
			<div class="faq-item">
				<button class="faq-question">
					<span class="faq-text">كيفية البدء</span>
					<span class="faq-icon">+</span>
				</button>
				<ul class="faq-answer">
					<li>
						<a href="<?php echo esc_url( home_url( '/login' ) ); ?>">
							أنشئ حسابًا أو سجل الدخول
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">
							يمكنك إدارة أكثر من متجر من حساب واحد
						</a>
					</li>
				</ul>
			</div>

			<div class="faq-item">
				<button class="faq-question">
					<span class="faq-text">إنشاء متجر</span>
					<span class="faq-icon">+</span>
				</button>
				<ul class="faq-answer">
					<li>
						<a href="<?php echo esc_url( home_url( '/store' ) ); ?>">اختر إنشاء متجر</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/store' ) ); ?>">حدد نوع النشاط</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/store' ) ); ?>">اختر القالب</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/store' ) ); ?>">أكمل الإعدادات</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/store' ) ); ?>">أطلق متجرك</a>
					</li>
				</ul>
			</div>

			<div class="faq-item">
				<button class="faq-question">
					<span class="faq-text">إدارة المتجر</span>
					<span class="faq-icon">+</span>
				</button>
				<ul class="faq-answer">
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">
							تعديل بيانات المتجر
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">
							إضافة منتجات أو خدمات
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">تخصيص القالب</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">متابعة الأداء</a>
					</li>
				</ul>
			</div>

			<div class="faq-item">
				<button class="faq-question">
					<span class="faq-text">الاشتراكات والباقات</span>
					<span class="faq-icon">+</span>
				</button>
				<ul class="faq-answer">
					<li>
						<a href="<?php echo esc_url( home_url( '/single-temp' ) ); ?>">
							باقات المتجر: خاصة بكل متجر
						</a>
					</li>
				</ul>
			</div>

			<div class="faq-item">
				<button class="faq-question">
					<span class="faq-text"> الطلبات والدفع</span>
					<span class="faq-icon">+</span>
				</button>
				<ul class="faq-answer">
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">
							حالات الدفع:
							مدفوع / غير مدفوع / متأخر
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">
							إدارة الطلبات والطلبات المسبقة
						</a>
					</li>
				</ul>
			</div>

			<div class="faq-item">
				<button class="faq-question">
					<span class="faq-text">الحساب</span>
					<span class="faq-icon">+</span>
				</button>
				<ul class="faq-answer">
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">
							تعديل البيانات
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">
							تغيير كلمة المرور
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">
							إدارة المتاجر
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>">
							حذف الحساب
						</a>
					</li>
				</ul>
			</div>

			<div class="faq-item">
				<button class="faq-question">
					<span class="faq-text">مساعدة</span>
					<span class="faq-icon">+</span>
				</button>
				<ul class="faq-answer">
					<li>
						<a href="<?php echo esc_url( home_url( '/faq' ) ); ?>">
							الأسئلة الشائعة
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">
							اتصل بنا
						</a>
					</li>
					<li>
						<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>">
							إرسال تذكرة دعم
						</a>
					</li>
				</ul>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
?>
