<?php
$settings = yaamama_get_contact_settings();
get_header();
?>

<main class="thank-you-page special-bg">
	<div class="container y-u-py-32">
		<div class="confirmation-card">
			<div class="icon-wrapper">
				<i class="fa-solid fa-check"></i>
			</div>

			<h1 class="title"><?php echo esc_html( $settings['success']['title'] ); ?></h1>
			<h2 class="subtitle"><?php echo esc_html( $settings['success']['subtitle'] ); ?></h2>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn main-button fw"><?php echo esc_html( $settings['success']['button_text'] ); ?></a>
		</div>
	</div>
</main>

<?php
get_footer();
?>
