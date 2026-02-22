<?php
get_header();

$is_order_received = function_exists( 'is_order_received_page' ) && is_order_received_page();
$order_id = $is_order_received ? absint( get_query_var( 'order-received' ) ) : 0;
$order = $order_id ? wc_get_order( $order_id ) : null;
?>

<?php if ( $is_order_received ) : ?>
	<main data-y="main" class="not-found-container">
		<div class="y-main-container">
			<?php if ( $order && $order->has_status( 'failed' ) ) : ?>
				<div class="not-found-content">
					<p class="not-found-text">عذرًا، لم يكتمل الدفع. يمكنك المحاولة مرة أخرى.</p>
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn-back">
						العودة للدفع <i class="fa-solid fa-credit-card"></i>
					</a>
				</div>
			<?php else : ?>
				<div class="not-found-content">
					<p class="not-found-text">تم الدفع بنجاح وتم استلام طلبك.</p>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-back">
						العودة للرئيسية <i class="fa-solid fa-house"></i>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</main>
<?php else : ?>
	<header data-y="design-header">
		<img src="<?php echo esc_url( dark_theme_asset_uri( 'assets/header2.png' ) ); ?>" alt="" class="design-img y-u-w-100" />
	</header>

	<main data-y="main">
		<div class="y-main-container">
			<div data-y="breadcrumb">
				<nav aria-label="breadcrumb" class="y-breadcrumb-container">
					<ol class="y-breadcrumb"></ol>
				</nav>
			</div>
		</div>
		<div class="y-main-container">
			<div data-y="payment">
				<?php echo do_shortcode( '[woocommerce_checkout]' ); ?>
			</div>
		</div>
	</main>
<?php endif; ?>

<?php get_footer(); ?>
