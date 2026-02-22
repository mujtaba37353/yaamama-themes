<?php
/**
 * Default page template - Elegance
 */
get_header();

$shop_url = function_exists( 'elegance_shop_url' ) ? elegance_shop_url() : home_url( '/shop/' );
$queried_object = get_queried_object();
$is_policy_page = ( $queried_object instanceof WP_Post ) && ! empty( $queried_object->post_name ) && strpos( (string) $queried_object->post_name, 'policy' ) !== false;
?>
<main class="page-main page-main--has-footer-space<?php echo $is_policy_page ? ' page-main--policy' : ''; ?>">
	<?php
	while ( have_posts() ) {
		the_post();
		$content = get_the_content();
		if ( trim( (string) $content ) === '' ) {
			elegance_enqueue_component_css( array( 'empty-state' ) );
			?>
	<section class="container y-u-max-w-1200 y-u-py-32">
		<div class="empty-state">
			<div class="empty-icon"><i class="fas fa-file-alt"></i></div>
			<h2><?php esc_html_e( 'لا يوجد محتوى', 'elegance' ); ?></h2>
			<a href="<?php echo esc_url( $shop_url ); ?>" class="btn main-button"><?php esc_html_e( 'تصفح المنتجات', 'elegance' ); ?></a>
		</div>
	</section>
			<?php
		} else {
			if ( $is_policy_page ) {
				echo '<section class="container y-u-max-w-1200 policy-page-content">';
			}
			the_content();
			if ( $is_policy_page ) {
				echo '</section>';
			}
		}
	}
	?>
</main>
<?php
get_footer();
