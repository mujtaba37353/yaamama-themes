<?php
get_header();

$assets_uri = get_template_directory_uri() . '/beauty-care/assets';
$about      = function_exists( 'beauty_care_get_about_settings' ) ? beauty_care_get_about_settings() : array();
$img_url    = function_exists( 'beauty_care_content_image_url' ) ? 'beauty_care_content_image_url' : null;
?>

<main>
	<section class="panner">
		<h1 class="y-u-text-center"><?php esc_html_e( 'من نحن', 'beauty-care-theme' ); ?></h1>
		<div class="breadcrumbs container y-u-max-w-1200">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'beauty-care-theme' ); ?></a>
			<p><?php esc_html_e( 'من نحن', 'beauty-care-theme' ); ?></p>
		</div>
	</section>

	<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
	<?php $swap = ( 2 === $i ); ?>
	<section class="about-section">
		<div class="container y-u-max-w-1200 about-grid<?php echo $swap ? ' about-grid-reverse' : ''; ?>">
			<?php if ( $swap ) : ?>
			<div class="about-image">
				<?php
				$aid = $about[ 'section' . $i . '_img' ] ?? 0;
				$url = $img_url ? beauty_care_content_image_url( $aid, 'about-us.jpg' ) : ( $assets_uri . '/about-us.jpg' );
				?>
				<img src="<?php echo esc_url( $url ); ?>" alt="<?php esc_attr_e( 'عن بيوتي كير', 'beauty-care-theme' ); ?>" />
			</div>
			<div class="about-content">
				<h2><?php echo esc_html( $about[ 'section' . $i . '_title' ] ?? ( 1 === $i ? 'من نحن' : ( 2 === $i ? 'رسالتنا' : 'رؤيتنا' ) ) ); ?></h2>
				<p class="y-t-muted"><?php echo esc_html( $about[ 'section' . $i . '_text' ] ?? '' ); ?></p>
			</div>
			<?php else : ?>
			<div class="about-content">
				<h2><?php echo esc_html( $about[ 'section' . $i . '_title' ] ?? ( 1 === $i ? 'من نحن' : ( 2 === $i ? 'رسالتنا' : 'رؤيتنا' ) ) ); ?></h2>
				<p class="y-t-muted"><?php echo esc_html( $about[ 'section' . $i . '_text' ] ?? '' ); ?></p>
			</div>
			<div class="about-image">
				<?php
				$aid = $about[ 'section' . $i . '_img' ] ?? 0;
				$url = $img_url ? beauty_care_content_image_url( $aid, 'about-us.jpg' ) : ( $assets_uri . '/about-us.jpg' );
				?>
				<img src="<?php echo esc_url( $url ); ?>" alt="<?php esc_attr_e( 'عن بيوتي كير', 'beauty-care-theme' ); ?>" />
			</div>
			<?php endif; ?>
		</div>
	</section>
	<?php endfor; ?>
</main>

<?php
get_footer();
