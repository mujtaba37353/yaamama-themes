<?php
get_header();
$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';
?>

<main class="special-bg">
	<section class="reset-message-section">
		<div class="container y-u-py-64 y-u-flex y-u-justify-center y-u-items-center">
			<div class="reset-card">
				<img src="<?php echo esc_url( $assets_uri . '/send.svg' ); ?>" alt="Email Sent" class="reset-icon">

				<h2 class="section-title">
					تم إرسال رابط إعادة التعيين
				</h2>

				<p>
					تم إرسال بريد إلكتروني لإعادة تعيين كلمة المرور إلى عنوان البريد الإلكتروني المسجل في حسابك
				</p>

				<div class="info-box">
					قد يستغرق وصول البريد الإلكتروني بضع دقائق. يرجى التحقق من مجلد البريد العشوائي (Spam) إذا لم تجده في صندوق
					الوارد.
				</div>

				<div class="divider">أو</div>

				<a href="<?php echo esc_url( home_url( '/login' ) ); ?>" class="btn main-button fw" style="text-decoration: none;">
					العودة لتسجيل الدخول
				</a>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
?>
