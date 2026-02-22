<?php
/**
 * Template Name: صفحة السياسات
 * Template for Policy pages (privacy, refund, shipping, etc.) — Sweet House design.
 * المحتوى من لوحة التحكم → المحتوى → سياسة الخصوصية / سياسة الاسترجاع / سياسة الشحن
 *
 * @package Sweet_House_Theme
 */

get_header();

$page = get_queried_object();
$page_slug = $page ? $page->post_name : '';
$policy_slug = $page_slug;
if ( 'privacy_policy' === $page_slug ) {
	$policy_slug = 'privacy-policy';
}
if ( function_exists( 'wp_privacy_policy_page_id' ) && $page && (int) $page->ID === (int) wp_privacy_policy_page_id() ) {
	$policy_slug = 'privacy-policy';
}
$policy_content = function_exists( 'sweet_house_get_policy_content' ) ? sweet_house_get_policy_content( $policy_slug ) : array();
$policy_banner = function_exists( 'sweet_house_content_image_url' ) ? sweet_house_content_image_url( isset( $policy_content['banner_img'] ) ? $policy_content['banner_img'] : 0, 'assets/panner.png' ) : sweet_house_asset_uri( 'assets/panner.png' );
$sections = isset( $policy_content['sections'] ) && is_array( $policy_content['sections'] ) ? $policy_content['sections'] : array();
if ( empty( $sections ) ) {
	$default = function_exists( 'sweet_house_default_policy_content' ) ? sweet_house_default_policy_content( $policy_slug ) : array();
	$sections = isset( $default['sections'] ) ? $default['sections'] : array();
}

$breadcrumb_labels = array(
	'privacy-policy'  => __( 'سياسة الخصوصية', 'sweet-house-theme' ),
	'privacy_policy'  => __( 'سياسة الخصوصية', 'sweet-house-theme' ),
	'refund-policy'   => __( 'سياسة الاسترجاع', 'sweet-house-theme' ),
	'shipping-policy' => __( 'سياسة الشحن', 'sweet-house-theme' ),
	'policy'          => __( 'السياسات', 'sweet-house-theme' ),
);
$breadcrumb_title = isset( $breadcrumb_labels[ $page_slug ] ) ? $breadcrumb_labels[ $page_slug ] : ( isset( $breadcrumb_labels[ $policy_slug ] ) ? $breadcrumb_labels[ $policy_slug ] : get_the_title() );
?>
<header data-y="design-header" class="policy-design-header">
	<img src="<?php echo esc_url( $policy_banner ); ?>" alt="<?php echo esc_attr__( 'بانر سويت هاوس - السياسات', 'sweet-house-theme' ); ?>" class="panner-img" />
</header>

<main data-y="main">
	<div class="main-container y-u-my-10">
		<nav class="y-breadcrumb-container" aria-label="<?php echo esc_attr__( 'مسار التنقل', 'sweet-house-theme' ); ?>">
			<ol class="y-breadcrumb">
				<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
				<li class="y-breadcrumb-item active"><?php echo esc_html( $breadcrumb_title ); ?></li>
			</ol>
		</nav>

		<div data-y="policy" class="policy-container">
			<?php foreach ( $sections as $sec ) : ?>
				<?php
				$sec_title = isset( $sec['title'] ) ? $sec['title'] : '';
				$sec_content = isset( $sec['content'] ) ? $sec['content'] : '';
				if ( ! $sec_title && ! $sec_content ) {
					continue;
				}
				?>
				<div>
					<?php if ( $sec_title ) : ?>
						<h1><?php echo esc_html( $sec_title ); ?></h1>
					<?php endif; ?>
					<?php if ( $sec_content ) : ?>
						<div><?php echo wp_kses_post( str_replace( array( "\r\n", "\n" ), '<br>', esc_html( $sec_content ) ) ); ?></div>
					<?php endif; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
