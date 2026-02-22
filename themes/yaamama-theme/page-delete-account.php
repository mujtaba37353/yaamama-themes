<?php
get_header();
?>

<main class="thank-you-page special-bg">
	<div class="container y-u-py-32">
		<div class="confirmation-card">
			<div class="icon-wrapper y-u-bg-danger">
				<i class="fa-regular fa-trash-can"></i>
			</div>

			<h1 class="title">حذف الحساب</h1>
			<h2 class="subtitle">هل أنت متأكد من حذف الحساب؟</h2>
			<p class="note">سيتم حذف جميع البيانات الخاصة بك ولا يمكن التراجع عن هذا الإجراء.</p>

			<div class="actions-wrapper">
				<a href="<?php echo esc_url( home_url( '/login' ) ); ?>" class="btn main-button y-u-bg-danger">حذف الحساب</a>
				<a href="<?php echo esc_url( home_url( '/my-account' ) ); ?>" class="btn secondary-btn">إلغاء</a>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
?>
