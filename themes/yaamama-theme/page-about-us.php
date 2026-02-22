<?php
get_header();
$settings = yaamama_get_about_settings();
?>

<main class="special-bg">
	<div class="container y-u-max-w-1200 y-u-mx-auto y-u-py-40">
		<section class="about-hero">
			<h1 class="section-title"><?php echo esc_html( $settings['hero']['title'] ); ?></h1>
			<div class="about-image">
				<img src="<?php echo esc_url( $settings['hero']['image'] ); ?>" alt="<?php echo esc_attr( $settings['hero']['alt'] ); ?>" />
			</div>
			<div class="about-content">
				<div class="text-body">
					<?php foreach ( $settings['hero']['paragraphs'] as $paragraph ) : ?>
						<?php if ( $paragraph ) : ?>
							<p><?php echo esc_html( $paragraph ); ?></p>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<section class="why-us-section">
			<div class="why-header">
				<h2 class="section-title"><?php echo esc_html( $settings['why']['title'] ); ?></h2>
				<p class="tagline"><?php echo esc_html( $settings['why']['tagline'] ); ?></p>
			</div>

			<div class="features-grid">
				<?php foreach ( $settings['features'] as $feature ) : ?>
					<?php if ( ! empty( $feature['text'] ) ) : ?>
						<div class="feature-card">
							<div class="icon-wrapper">
								<img src="<?php echo esc_url( $feature['icon'] ); ?>" alt="<?php echo esc_attr( $feature['alt'] ); ?>" />
							</div>
							<p><?php echo esc_html( $feature['text'] ); ?></p>
						</div>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</section>
	</div>
</main>

<?php
get_footer();
?>
