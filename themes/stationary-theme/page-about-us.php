<?php
get_header();
$au           = stationary_base_uri() . '/assets';
$banner_img   = (int) ( function_exists( 'stationary_get_option' ) ? stationary_get_option( 'about_banner_image', 0 ) : 0 );
$banner_src   = $banner_img ? wp_get_attachment_image_url( $banner_img, 'full' ) : ( $au . '/panner.jpg' );
$banner_style = $banner_img ? ' style="background-image: url(' . esc_url( $banner_src ) . ');"' : '';
?>

<main>
	<section class="panner panner-image y-u-m-b-0 container y-u-max-w-1200"<?php echo $banner_style; ?>>
		<h1 class="y-u-text-center"><?php esc_html_e( 'من نحن', 'stationary-theme' ); ?></h1>
	</section>
	<section class="breadcrumbs container y-u-max-w-1200 y-u-m-b-0 ">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'stationary-theme' ); ?></a>
		<p><?php esc_html_e( 'من نحن', 'stationary-theme' ); ?></p>
	</section>
	<section class="about-us-section">
		<div class="container y-u-max-w-1200">
			<?php
			$about_content = '';
			if ( function_exists( 'stationary_get_option' ) ) {
				$def = '<p>' . esc_html__( 'نحن نؤمن أن الإلهام يبدأ من التفاصيل الصغيرة. لذلك نقدّم لك أدوات مكتبية تجمع بين الأناقة، الجودة، والعملية.', 'stationary-theme' ) . '</p><p>' . esc_html__( 'رسالتنا هي دعم الإبداع وتنمية روح التنظيم لدى كل شخص.', 'stationary-theme' ) . '</p>';
				$about_content = stationary_get_option( 'about_content', '' );
			}
			if ( empty( trim( $about_content ) ) && have_posts() ) {
				while ( have_posts() ) {
					the_post();
					$about_content = get_the_content();
				}
			}
			if ( empty( trim( $about_content ) ) ) {
				$about_content = '<p>' . esc_html__( 'نحن نؤمن أن الإلهام يبدأ من التفاصيل الصغيرة. لذلك نقدّم لك أدوات مكتبية تجمع بين الأناقة، الجودة، والعملية.', 'stationary-theme' ) . '</p><p>' . esc_html__( 'رسالتنا هي دعم الإبداع وتنمية روح التنظيم لدى كل شخص.', 'stationary-theme' ) . '</p>';
			}
			echo wp_kses_post( $about_content );
			?>
		</div>
	</section>
</main>

<?php
get_footer();
