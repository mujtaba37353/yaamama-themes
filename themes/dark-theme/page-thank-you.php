<?php
/**
 * Template Name: Thank You (تأكيد الطلب)
 * يعرض بعد إتمام الطلب بنجاح أو فشل الدفع — بتصميم الثيم (هيدر، breadcrumb، محتوى).
 */
get_header();

$order_id = isset( $_GET['order'] ) ? absint( wp_unslash( $_GET['order'] ) ) : 0;
$order_key = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
$order = $order_id ? wc_get_order( $order_id ) : null;
if ( $order && $order_key && $order->get_order_key() !== $order_key ) {
	$order = null;
}
?>

<header data-y="design-header">
	<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/header2.png' ) ); ?>" alt="" class="design-img y-u-w-100" />
</header>

<main data-y="main" class="not-found-container">
	<div class="y-main-container">
		<div data-y="breadcrumb">
			<nav aria-label="breadcrumb" class="y-breadcrumb-container">
				<ol class="y-breadcrumb"></ol>
			</nav>
		</div>

		<div data-y="thank-you-content" class="thank-you-content-wrapper">
			<?php if ( $order && $order->has_status( 'failed' ) ) : ?>
				<div class="not-found-content thank-you-state thank-you-failed">
					<i class="fa-solid fa-circle-xmark thank-you-icon thank-you-icon-failed" aria-hidden="true"></i>
					<p class="not-found-text">عذرًا، لم يكتمل الدفع. يمكنك المحاولة مرة أخرى.</p>
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn-back">
						العودة للدفع <i class="fa-solid fa-credit-card"></i>
					</a>
				</div>
			<?php elseif ( $order ) : ?>
				<div class="not-found-content thank-you-state thank-you-success">
					<i class="fa-solid fa-circle-check thank-you-icon thank-you-icon-success" aria-hidden="true"></i>
					<p class="not-found-text">تم الدفع بنجاح وتم استلام طلبك.</p>
					<?php if ( $order_id ) : ?>
						<p class="thank-you-order-number">رقم الطلب: <strong><?php echo esc_html( $order->get_order_number() ); ?></strong></p>
					<?php endif; ?>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-back">
						العودة للرئيسية <i class="fa-solid fa-house"></i>
					</a>
				</div>
			<?php else : ?>
				<div class="not-found-content thank-you-state thank-you-invalid">
					<i class="fa-solid fa-circle-question thank-you-icon thank-you-icon-invalid" aria-hidden="true"></i>
					<p class="not-found-text">لا يمكن العثور على الطلب.</p>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-back">
						العودة للرئيسية <i class="fa-solid fa-house"></i>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
