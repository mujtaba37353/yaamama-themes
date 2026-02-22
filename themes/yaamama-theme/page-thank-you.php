<?php
get_header();
?>

<main class="thank-you-page special-bg">
	<div class="container y-u-py-32">
		<div class="confirmation-card">
			<div class="icon-wrapper">
				<i class="fa-solid fa-check"></i>
			</div>

			<h1 class="title">تم تأكيد الدفع</h1>
			<h2 class="subtitle">جارى تجهيز المتجر</h2>
			<p class="note">قد يستغرق من ثوانى لبضع دقائق</p>

			<div class="actions-wrapper">
				<a href="<?php echo esc_url( home_url( '/store' ) ); ?>" class="btn main-button">استعرض متجرك</a>
				<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>" class="btn secondary-btn">الذهاب إلى حسابى</a>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
?>
