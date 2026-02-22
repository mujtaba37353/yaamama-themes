<?php
get_header();

$settings = function_exists( 'yaamama_get_policy_settings' ) ? yaamama_get_policy_settings() : array();
$policy   = $settings['privacy'] ?? array();
$title    = ! empty( $policy['title'] ) ? $policy['title'] : '';
$content  = isset( $policy['content'] ) ? $policy['content'] : '';

if ( empty( $title ) || empty( $content ) ) {
	while ( have_posts() ) {
		the_post();
		if ( empty( $title ) ) {
			$title = get_the_title();
		}
		if ( empty( $content ) ) {
			ob_start();
			the_content();
			$content = ob_get_clean();
		}
	}
}
?>

<main class="special-bg">
	<section class="container y-u-max-w-1200 y-u-py-40">
		<h1 class="y-u-text-xxl y-u-font-bold y-u-m-b-24"><?php echo esc_html( $title ); ?></h1>
		<div class="y-u-text-m y-u-text-muted">
			<?php echo apply_filters( 'the_content', $content ); ?>
		</div>
	</section>
</main>

<?php
get_footer();
?>
