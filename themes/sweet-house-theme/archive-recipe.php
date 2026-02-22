<?php
/**
 * قالب صفحة الوصفات — Sweet House design.
 *
 * @see design: sweet-house/templates/recepies/layout.html, components/recipe/y-c-recipe.html
 *
 * @package Sweet_House_Theme
 */

get_header();

$asset_uri = function_exists( 'sweet_house_asset_uri' ) ? sweet_house_asset_uri( '' ) : get_template_directory_uri() . '/sweet-house/';
?>
<header data-y="design-header" class="recipe-design-header">
	<img src="<?php echo esc_url( $asset_uri . 'assets/panner.png' ); ?>" alt="<?php esc_attr_e( 'بانر سويت هاوس - الوصفات', 'sweet-house-theme' ); ?>" class="panner-img" />
</header>

<main data-y="main">
	<div class="main-container">
		<div data-y="recipe" class="recipe-container">
			<?php
			$terms = get_terms(
				array(
					'taxonomy'   => 'recipe_category',
					'hide_empty' => false,
				)
			);

			if ( is_wp_error( $terms ) ) {
				$terms = array();
			}

			$default_order = array( 'وصفات مميزة', 'حلويات', 'مقبلات', 'أطباق رئيسية' );
			usort( $terms, function ( $a, $b ) use ( $default_order ) {
				$pos_a = array_search( $a->name, $default_order, true );
				$pos_b = array_search( $b->name, $default_order, true );
				$pos_a = false === $pos_a ? 999 : $pos_a;
				$pos_b = false === $pos_b ? 999 : $pos_b;
				return $pos_a - $pos_b;
			} );

			if ( empty( $terms ) ) {
				$all = get_posts( array( 'post_type' => 'recipe', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
				if ( ! empty( $all ) ) {
					?>
					<h2 class="recipe-title"><?php esc_html_e( 'الوصفات', 'sweet-house-theme' ); ?></h2>
					<div class="recipe-grid">
						<?php foreach ( $all as $post ) : ?>
							<?php
							setup_postdata( $post );
							$thumb = get_the_post_thumbnail_url( $post->ID, 'medium_large' );
							$url   = get_permalink( $post->ID );
							?>
							<a href="<?php echo esc_url( $url ); ?>" class="recipe-card-link">
								<div class="recipe-card">
									<?php if ( $thumb ) : ?>
										<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
									<?php else : ?>
										<img src="<?php echo esc_url( $asset_uri . 'assets/recipe1.png' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
									<?php endif; ?>
									<p><?php the_title(); ?></p>
								</div>
							</a>
						<?php endforeach; ?>
						<?php wp_reset_postdata(); ?>
					</div>
					<?php
				} else {
					echo '<p class="recipe-empty">' . esc_html__( 'لا توجد وصفات حالياً.', 'sweet-house-theme' ) . '</p>';
				}
			}

			foreach ( $terms as $term ) {
				$recipes = get_posts(
					array(
						'post_type'      => 'recipe',
						'posts_per_page' => -1,
						'post_status'    => 'publish',
						'tax_query'      => array(
							array(
								'taxonomy' => 'recipe_category',
								'field'    => 'term_id',
								'terms'    => $term->term_id,
							),
						),
						'orderby'        => 'menu_order date',
						'order'          => 'ASC',
					)
				);

				if ( empty( $recipes ) ) {
					continue;
				}
				?>
				<h2 class="recipe-title"><?php echo esc_html( $term->name ); ?></h2>
				<div class="recipe-grid">
					<?php foreach ( $recipes as $post ) : ?>
						<?php
						setup_postdata( $post );
						$thumb = get_the_post_thumbnail_url( $post->ID, 'medium_large' );
						$url   = get_permalink( $post->ID );
						?>
						<a href="<?php echo esc_url( $url ); ?>" class="recipe-card-link">
							<div class="recipe-card">
								<?php if ( $thumb ) : ?>
									<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
								<?php else : ?>
									<img src="<?php echo esc_url( $asset_uri . 'assets/recipe1.png' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
								<?php endif; ?>
								<p><?php the_title(); ?></p>
							</div>
						</a>
					<?php endforeach; ?>
					<?php wp_reset_postdata(); ?>
				</div>
				<?php
			}

			$all_recipes = get_posts(
				array(
					'post_type'      => 'recipe',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
				)
			);
			$uncategorized = array_filter( $all_recipes, function ( $p ) {
				$terms = wp_get_object_terms( $p->ID, 'recipe_category' );
				return empty( $terms ) || is_wp_error( $terms );
			} );
			if ( ! empty( $uncategorized ) && ! empty( $terms ) ) :
				?>
				<h2 class="recipe-title"><?php esc_html_e( 'وصفات أخرى', 'sweet-house-theme' ); ?></h2>
				<div class="recipe-grid">
					<?php foreach ( $uncategorized as $post ) : ?>
						<?php
						setup_postdata( $post );
						$thumb = get_the_post_thumbnail_url( $post->ID, 'medium_large' );
						$url   = get_permalink( $post->ID );
						?>
						<a href="<?php echo esc_url( $url ); ?>" class="recipe-card-link">
							<div class="recipe-card">
								<?php if ( $thumb ) : ?>
									<img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
								<?php else : ?>
									<img src="<?php echo esc_url( $asset_uri . 'assets/recipe1.png' ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
								<?php endif; ?>
								<p><?php the_title(); ?></p>
							</div>
						</a>
					<?php endforeach; ?>
					<?php wp_reset_postdata(); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</main>

<?php get_footer(); ?>
