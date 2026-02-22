<?php
/**
 * قالب صفحة الوصفة المنفردة — Sweet House design.
 *
 * @see design: sweet-house/templates/recepies/recipe1.html, components/recipe/y-c-single-recipe.html
 *
 * @package Sweet_House_Theme
 */

get_header();

$asset_uri = function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( '' ) : get_template_directory_uri() . '/sweet-house/';

while ( have_posts() ) :
	the_post();

	$prep_time     = get_post_meta( get_the_ID(), '_recipe_prep_time', true );
	$cook_time     = get_post_meta( get_the_ID(), '_recipe_cook_time', true );
	$serves        = get_post_meta( get_the_ID(), '_recipe_serves', true );
	$ingredients   = get_post_meta( get_the_ID(), '_recipe_ingredients', true );
	$instructions  = get_post_meta( get_the_ID(), '_recipe_instructions', true );

	$ingredients_list = array_filter( array_map( 'trim', explode( "\n", (string) $ingredients ) ) );
	$instructions_list = array_filter( array_map( 'trim', explode( "\n", (string) $instructions ) ) );

	$thumb = get_the_post_thumbnail_url( get_the_ID(), 'large' );
	?>
<header data-y="design-header" class="single-recipe-design-header">
	<img src="<?php echo esc_url( $asset_uri . 'assets/panner.png' ); ?>" alt="<?php esc_attr_e( 'بانر سويت هاوس - الوصفات', 'sweet-house-theme' ); ?>" class="panner-img" />
</header>

<main data-y="main">
	<div class="main-container">
		<nav data-y="breadcrumb" class="y-breadcrumb-container" aria-label="<?php esc_attr_e( 'مسار التنقل', 'sweet-house-theme' ); ?>">
			<ol class="y-breadcrumb">
				<li class="y-breadcrumb-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'الرئيسية', 'sweet-house-theme' ); ?></a></li>
				<li class="y-breadcrumb-item"><a href="<?php echo esc_url( get_post_type_archive_link( 'recipe' ) ); ?>"><?php esc_html_e( 'الوصفات', 'sweet-house-theme' ); ?></a></li>
				<li class="y-breadcrumb-item active"><?php the_title(); ?></li>
			</ol>
		</nav>

		<div data-y="single-recipe" class="single-recipe-container">
			<div class="recipe-image-section">
				<?php if ( $thumb ) : ?>
					<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php the_title_attribute(); ?>" class="recipe-main-image">
				<?php else : ?>
					<img src="<?php echo esc_url( $asset_uri . 'assets/recipe2.png' ); ?>" alt="<?php the_title_attribute(); ?>" class="recipe-main-image">
				<?php endif; ?>
			</div>

			<div class="recipe-content-section">
				<h1 class="recipe-title-main"><?php the_title(); ?></h1>

				<div class="recipe-meta">
					<?php if ( $prep_time ) : ?>
					<div class="meta-item">
						<span class="meta-label"><?php esc_html_e( 'وقت التحضير', 'sweet-house-theme' ); ?></span>
						<span class="meta-value"><?php echo esc_html( $prep_time . ' ' . __( 'دقائق', 'sweet-house-theme' ) ); ?></span>
					</div>
					<?php endif; ?>
					<?php if ( $cook_time ) : ?>
					<div class="meta-item">
						<span class="meta-label"><?php esc_html_e( 'وقت الطهي', 'sweet-house-theme' ); ?></span>
						<span class="meta-value"><?php echo esc_html( $cook_time . ' ' . __( 'دقائق', 'sweet-house-theme' ) ); ?></span>
					</div>
					<?php endif; ?>
					<?php if ( $serves ) : ?>
					<div class="meta-item">
						<span class="meta-label"><?php esc_html_e( 'يخدم', 'sweet-house-theme' ); ?></span>
						<span class="meta-value"><?php echo esc_html( $serves . ' ' . __( 'شخص', 'sweet-house-theme' ) ); ?></span>
					</div>
					<?php endif; ?>
				</div>

				<div class="recipe-section">
					<h2 class="section-title"><?php esc_html_e( 'المكونات والتوجيهات', 'sweet-house-theme' ); ?></h2>

					<?php if ( ! empty( $ingredients_list ) ) : ?>
					<div class="ingredients-section">
						<h3 class="ingredients-title"><?php esc_html_e( 'المكونات:', 'sweet-house-theme' ); ?></h3>
						<ul class="ingredients-list">
							<?php foreach ( $ingredients_list as $i ) : ?>
								<li><?php echo esc_html( $i ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
					<?php endif; ?>

					<?php if ( ! empty( $instructions_list ) ) : ?>
					<div class="instructions-section">
						<h3 class="instructions-title"><?php esc_html_e( 'التوجيهات:', 'sweet-house-theme' ); ?></h3>
						<ol class="instructions-list">
							<?php foreach ( $instructions_list as $step ) : ?>
								<li><?php echo esc_html( $step ); ?></li>
							<?php endforeach; ?>
						</ol>
					</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</main>
<?php
endwhile;
get_footer();
