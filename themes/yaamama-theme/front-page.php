<?php
get_header();
$assets_uri = get_template_directory_uri() . '/yaamama-front-platform/assets';
$settings = yaamama_get_homepage_settings();
$template_terms = get_terms(
	array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
		'exclude'    => array( (int) get_option( 'default_product_cat', 0 ) ),
	)
);
if ( is_wp_error( $template_terms ) ) {
	$template_terms = array();
}
$template_category_items = $settings['templates']['category_items'] ?? array();
$hero = $settings['hero'];
$slider_images = array_values( $hero['slider_images'] ?? array() );
while ( count( $slider_images ) < 3 ) {
	$slider_images[] = array( 'src' => '', 'alt' => '' );
}
$hero_lines = array_filter(
	array(
		$hero['title']['line1'] ?? '',
		$hero['title']['line2'] ?? '',
		$hero['title']['line3'] ?? '',
	)
);
$hero_heading = '';
if ( $hero_lines ) {
	$hero_heading = implode( '<br>', array_map( 'esc_html', $hero_lines ) );
}
if ( ! empty( $hero['title']['highlight'] ) ) {
	$hero_heading .= ( $hero_heading ? '<br>' : '' ) . '<span class="primary-color">' . esc_html( $hero['title']['highlight'] ) . '</span>';
}
$section_1 = $settings['section_1'] ?? array();
if ( empty( $section_1['icons'] ) && ! empty( $settings['icon_marquee'] ) ) {
	$legacy_icons = $settings['icon_marquee']['icons'] ?? array();
	$section_1['title'] = $section_1['title'] ?? ( $settings['icon_marquee']['title'] ?? '' );
	$section_1['text'] = $section_1['text'] ?? ( $settings['icon_marquee']['text'] ?? '' );
	$section_1['speed'] = $section_1['speed'] ?? ( $settings['icon_marquee']['speed'] ?? 25 );
	$section_1['loop'] = $section_1['loop'] ?? true;
	$section_1['icons'] = array();
	foreach ( $legacy_icons as $icon ) {
		if ( empty( $icon['image'] ) ) {
			continue;
		}
		$section_1['icons'][] = array(
			'id'  => 0,
			'url' => $icon['image'],
		);
	}
}
$section_1 = wp_parse_args(
	$section_1,
	array(
		'title' => '',
		'text'  => '',
		'speed' => 25,
		'loop'  => true,
		'icons' => array(),
	)
);
$shipping_section = $settings['shipping_section'] ?? array();
if ( empty( $shipping_section['title'] ) && ! empty( $settings['shipping'] ) ) {
	$shipping_section['title'] = $settings['shipping']['title'] ?? '';
	$shipping_section['text'] = $settings['shipping']['text'] ?? '';
	$shipping_section['video_url'] = $settings['shipping']['video'] ?? '';
	$shipping_section['video_id'] = 0;
}
$shipping_section = wp_parse_args(
	$shipping_section,
	array(
		'title'      => '',
		'text'       => '',
		'background' => 'gradient',
		'video_id'   => 0,
		'video_url'  => '',
	)
);
$section_1_icons = array();
foreach ( (array) ( $section_1['icons'] ?? array() ) as $icon ) {
	if ( ! is_array( $icon ) ) {
		continue;
	}
	$icon_id = absint( $icon['id'] ?? 0 );
	$icon_url = $icon['url'] ?? '';
	if ( $icon_id ) {
		$icon_url = wp_get_attachment_url( $icon_id ) ?: $icon_url;
	}
	if ( ! $icon_url ) {
		continue;
	}
	$section_1_icons[] = array(
		'id'  => $icon_id,
		'url' => $icon_url,
	);
}
$show_section_1 = ! empty( $section_1['title'] ) || ! empty( $section_1['text'] ) || ! empty( $section_1_icons );
$shipping_video_id = absint( $shipping_section['video_id'] ?? 0 );
$shipping_video_url = $shipping_section['video_url'] ?? '';
if ( $shipping_video_id ) {
	$shipping_video_url = wp_get_attachment_url( $shipping_video_id ) ?: $shipping_video_url;
}
$show_shipping_section = ! empty( $shipping_section['title'] ) || ! empty( $shipping_section['text'] ) || ! empty( $shipping_video_url );
?>

<main>
	<section class="hero-template">
		<div class="container y-u-max-w-1200">
			<div class="right y-u-flex y-u-flex-col y-u-gap-12">
				<div class="stat y-u-p-12 y-u-py-4 light-primary-bg y-u-rounded-m y-u-w-fit y-u-flex y-u-items-center y-u-gap-8">
					<i class="fa-regular fa-circle-check primary-color"></i>
					<p class="y-u-text-s y-u-font-medium primary-color"><?php echo esc_html( $hero['stat_text'] ); ?></p>
				</div>
				<h1 class="y-u-text-xxxl y-u-font-bold">
					<?php echo wp_kses_post( $hero_heading ); ?>
				</h1>
				<div class="y-u-flex y-u-gap-12 y-u-items-center">
					<a href="<?php echo esc_url( $hero['cta']['url'] ); ?>" class="btn main-button">
						<?php echo esc_html( $hero['cta']['text'] ); ?>
						<i class="fa-solid fa-arrow-left"></i>
					</a>
				</div>
			</div>
			<div class="left">
				<div class="white-card y-u-flex y-u-gap-12 y-u-items-center y-u-w-fit">
					<div class="icon light-primary-bg y-u-rounded-s y-u-p-8 y-u-w-fit y-u-flex y-u-items-center y-u-justify-center">
						<i class="fa-solid fa-chart-line primary-color y-u-text-m"></i>
					</div>
					<div class="content y-u-flex y-u-flex-col  y-u-items-center y-u-justify-center">
						<p class="y-u-text-s y-u-font-normal"><?php echo esc_html( $hero['conversion']['label'] ); ?></p>
						<p class="y-u-text-s y-u-font-bold"><?php echo esc_html( $hero['conversion']['value'] ); ?></p>
					</div>
				</div>
				<div class="infinite-vertical-slider y-u-flex y-u-gap-16">
					<div class="slider-column up">
						<div class="slider-track">
							<?php if ( ! empty( $slider_images[0]['src'] ) ) : ?>
								<img src="<?php echo esc_url( $slider_images[0]['src'] ); ?>" alt="<?php echo esc_attr( $slider_images[0]['alt'] ); ?>" />
								<img src="<?php echo esc_url( $slider_images[0]['src'] ); ?>" alt="<?php echo esc_attr( $slider_images[0]['alt'] ); ?>" />
							<?php endif; ?>
						</div>
					</div>
					<div class="slider-column down">
						<div class="slider-track">
							<?php if ( ! empty( $slider_images[1]['src'] ) ) : ?>
								<img src="<?php echo esc_url( $slider_images[1]['src'] ); ?>" alt="<?php echo esc_attr( $slider_images[1]['alt'] ); ?>" />
								<img src="<?php echo esc_url( $slider_images[1]['src'] ); ?>" alt="<?php echo esc_attr( $slider_images[1]['alt'] ); ?>" />
							<?php endif; ?>
						</div>
					</div>
					<div class="slider-column up">
						<div class="slider-track">
							<?php if ( ! empty( $slider_images[2]['src'] ) ) : ?>
								<img src="<?php echo esc_url( $slider_images[2]['src'] ); ?>" alt="<?php echo esc_attr( $slider_images[2]['alt'] ); ?>" />
								<img src="<?php echo esc_url( $slider_images[2]['src'] ); ?>" alt="<?php echo esc_attr( $slider_images[2]['alt'] ); ?>" />
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php
	if ( empty( $section_1_icons ) ) {
		$images = array();
		if ( function_exists( 'get_field' ) ) {
			$page_id = (int) get_queried_object_id();
			if ( $page_id ) {
				$images = get_field( 'logos_gallery', $page_id );
			} else {
				$images = get_field( 'logos_gallery' );
			}
			if ( ! $images ) {
				$front_id = (int) get_option( 'page_on_front' );
				if ( $front_id ) {
					$images = get_field( 'logos_gallery', $front_id );
				}
			}
			if ( ! $images ) {
				$images = get_field( 'logos_gallery', 'option' );
			}
		}
		if ( ! $images ) {
			$page_id = (int) get_queried_object_id();
			if ( $page_id ) {
				$images = maybe_unserialize( get_post_meta( $page_id, 'logos_gallery', true ) );
			}
		}
		if ( ! $images ) {
			$front_id = (int) get_option( 'page_on_front' );
			if ( $front_id ) {
				$images = maybe_unserialize( get_post_meta( $front_id, 'logos_gallery', true ) );
			}
		}
		if ( ! $images ) {
			$images = maybe_unserialize( get_option( 'options_logos_gallery' ) );
		}
		if ( ! $images && function_exists( 'get_fields' ) ) {
			$candidates = array();
			$page_id = (int) get_queried_object_id();
			if ( $page_id ) {
				$candidates[] = get_fields( $page_id );
			}
			$front_id = (int) get_option( 'page_on_front' );
			if ( $front_id ) {
				$candidates[] = get_fields( $front_id );
			}
			$candidates[] = get_fields( 'option' );

			foreach ( $candidates as $fields ) {
				if ( ! is_array( $fields ) ) {
					continue;
				}
				if ( isset( $fields['logos_gallery'] ) ) {
					$images = $fields['logos_gallery'];
					break;
				}
				foreach ( $fields as $key => $value ) {
					if ( ! is_array( $value ) || ! preg_match( '/logo|gallery/i', (string) $key ) ) {
						continue;
					}
					$images = $value;
					break 2;
				}
			}
		}
		if ( ! $images ) {
			$pages_with_gallery = get_posts(
				array(
					'post_type'      => 'page',
					'posts_per_page' => 1,
					'fields'         => 'ids',
					'meta_key'       => 'logos_gallery',
					'meta_compare'   => 'EXISTS',
				)
			);
			if ( ! empty( $pages_with_gallery[0] ) ) {
				$images = maybe_unserialize( get_post_meta( (int) $pages_with_gallery[0], 'logos_gallery', true ) );
			}
		}
		$logo_urls = array();
		if ( $images ) {
			foreach ( $images as $img ) {
				if ( is_array( $img ) ) {
					$logo_urls[] = $img['url'] ?? '';
				} elseif ( is_numeric( $img ) ) {
					$logo_urls[] = wp_get_attachment_url( (int) $img );
				} else {
					$logo_urls[] = $img;
				}
			}
		}
		$logo_urls = array_values( array_filter( $logo_urls ) );
		foreach ( $logo_urls as $logo_url ) {
			$section_1_icons[] = array(
				'id'  => 0,
				'url' => $logo_url,
			);
		}
	}
	?>
	<?php if ( $show_section_1 && $section_1_icons ) : ?>
		<section class="homepage-icons-section">
			<div class="container y-u-max-w-1200">
				<?php if ( ! empty( $section_1['title'] ) ) : ?>
					<h2 class="center-title"><?php echo esc_html( $section_1['title'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section_1['text'] ) ) : ?>
					<p class="section-subtitle"><?php echo esc_html( $section_1['text'] ); ?></p>
				<?php endif; ?>
			</div>
			<div class="icon-marquee <?php echo ! empty( $section_1['loop'] ) ? 'is-looping' : 'is-static'; ?>" dir="rtl">
				<div class="icon-marquee__track" style="--marquee-duration: <?php echo esc_attr( max( 5, (int) $section_1['speed'] ) ); ?>s;">
					<div class="icon-marquee__group">
						<?php foreach ( $section_1_icons as $icon ) : ?>
							<div class="icon-marquee__item">
								<?php if ( ! empty( $icon['id'] ) ) : ?>
									<?php echo wp_get_attachment_image( (int) $icon['id'], 'medium', false, array( 'loading' => 'lazy' ) ); ?>
								<?php else : ?>
									<img src="<?php echo esc_url( $icon['url'] ); ?>" alt="" loading="lazy">
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					</div>
					<?php if ( ! empty( $section_1['loop'] ) ) : ?>
						<div class="icon-marquee__group" aria-hidden="true">
							<?php foreach ( $section_1_icons as $icon ) : ?>
								<div class="icon-marquee__item">
									<?php if ( ! empty( $icon['id'] ) ) : ?>
										<?php echo wp_get_attachment_image( (int) $icon['id'], 'medium', false, array( 'loading' => 'lazy' ) ); ?>
									<?php else : ?>
										<img src="<?php echo esc_url( $icon['url'] ); ?>" alt="" loading="lazy">
									<?php endif; ?>
								</div>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php if ( $show_shipping_section ) : ?>
		<?php $shipping_section_class = 'shipping-section'; ?>
		<?php if ( 'light' === ( $shipping_section['background'] ?? '' ) ) : ?>
			<?php $shipping_section_class .= ' shipping-section--light'; ?>
		<?php endif; ?>
		<section class="<?php echo esc_attr( $shipping_section_class ); ?>">
			<div class="container y-u-max-w-1200 shipping-section__inner">
				<div class="shipping-content">
					<?php if ( ! empty( $shipping_section['title'] ) ) : ?>
						<h2 class="center-title"><?php echo esc_html( $shipping_section['title'] ); ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $shipping_section['text'] ) ) : ?>
						<p class="section-subtitle"><?php echo esc_html( $shipping_section['text'] ); ?></p>
					<?php endif; ?>
				</div>
				<div class="shipping-media">
					<?php
					$shipping_media_type = $shipping_video_url ? wp_check_filetype( $shipping_video_url ) : array();
					$shipping_is_video = ! empty( $shipping_media_type['type'] ) && 0 === strpos( $shipping_media_type['type'], 'video/' );
					$shipping_is_image = ! empty( $shipping_media_type['type'] ) && 0 === strpos( $shipping_media_type['type'], 'image/' );
					?>
					<?php if ( $shipping_video_url && $shipping_is_video ) : ?>
						<video src="<?php echo esc_url( $shipping_video_url ); ?>" autoplay muted loop playsinline></video>
					<?php elseif ( $shipping_video_url && $shipping_is_image ) : ?>
						<img src="<?php echo esc_url( $shipping_video_url ); ?>" alt="<?php echo esc_attr( $shipping_section['title'] ?? '' ); ?>">
					<?php endif; ?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="special-bg">
		<section class="container y-u-max-w-1200 why-us-section">
			<div class="why-header">
				<h2 class="center-title"><?php echo esc_html( $settings['why_us']['title'] ); ?></h2>
				<p class="tagline"><?php echo esc_html( $settings['why_us']['tagline'] ); ?></p>
			</div>

			<div class="features-grid">
				<div class="feature-col col-right">
					<div class="feature-card design-card fade-item delay-1">
						<div class="feature-content">
							<div class="accent-line"></div>
							<h3 class="feature-title"><?php echo esc_html( $settings['features']['design']['title'] ); ?></h3>
							<p class="feature-desc">
								<?php echo esc_html( $settings['features']['design']['desc'] ); ?>
							</p>
						</div>
					</div>

					<div class="feature-card split-card fade-item delay-2">
						<div class="feature-content-split">
							<div class="split-text">
								<div class="accent-line"></div>
								<h3 class="feature-title"><?php echo esc_html( $settings['features']['support']['title'] ); ?></h3>
								<p class="feature-desc">
									<?php echo esc_html( $settings['features']['support']['desc'] ); ?>
								</p>
							</div>
							<div class="split-image">
								<img src="<?php echo esc_url( $settings['features']['support']['image'] ); ?>" alt="<?php echo esc_attr( $settings['features']['support']['alt'] ); ?>">
							</div>
						</div>
					</div>

					<div class="feature-card launch-card fade-item delay-3">
					</div>

					<div class="feature-card responsiveness-text-card fade-item delay-4">
						<div class="feature-content-split">
							<h3 class="feature-title"><?php echo esc_html( $settings['features']['responsive_text']['title'] ); ?></h3>
							<p class="feature-desc">
								<?php echo esc_html( $settings['features']['responsive_text']['desc'] ); ?>
							</p>
						</div>
					</div>
				</div>

				<div class="feature-col col-left">
					<div class="feature-card speed-card fade-item delay-1">
						<div class="feature-content">
							<div class="accent-line"></div>
							<h3 class="feature-title"><?php echo esc_html( $settings['features']['speed']['title'] ); ?></h3>
							<p class="feature-desc">
								<?php echo esc_html( $settings['features']['speed']['desc'] ); ?>
							</p>
						</div>
					</div>

					<div class="feature-card split-card fade-item delay-2">
						<div class="feature-content-split reverse-layout">
							<div class="split-image">
								<img src="<?php echo esc_url( $settings['features']['control']['image'] ); ?>" alt="<?php echo esc_attr( $settings['features']['control']['alt'] ); ?>">
							</div>
							<div class="split-text">
								<div class="accent-line"></div>
								<h3 class="feature-title"><?php echo esc_html( $settings['features']['control']['title'] ); ?></h3>
								<p class="feature-desc">
									<?php echo esc_html( $settings['features']['control']['desc'] ); ?>
								</p>
							</div>
						</div>
					</div>

					<div class="feature-card response-card fade-item delay-3">
						<div class="feature-content">
							<div class="accent-line"></div>
							<h3 class="feature-title"><?php echo esc_html( $settings['features']['launch']['title'] ); ?></h3>
							<p class="feature-desc">
								<?php echo esc_html( $settings['features']['launch']['desc'] ); ?>
							</p>
						</div>
					</div>

					<div class="feature-card responsiveness-card fade-item delay-4">
						<div class="split-image">
							<img src="<?php echo esc_url( $settings['features']['responsive_image']['image'] ); ?>" alt="<?php echo esc_attr( $settings['features']['responsive_image']['alt'] ); ?>">
						</div>
					</div>
				</div>
			</div>
		</section>
	</section>

	<div class="how-to-header">
		<h2 class="center-title y-u-m-b-56">
			<?php echo esc_html( $settings['how_start']['title'] ); ?>
		</h2>
	</div>

	<section class="special-bg how-start-section">
		<div class="container y-u-max-w-1200">
			<div class="steps-wrapper">
				<?php foreach ( $settings['how_start']['steps'] as $step ) : ?>
					<div class="step-item <?php echo ! empty( $step['reverse'] ) ? 'reverse' : ''; ?>">
						<div class="step-img">
							<img src="<?php echo esc_url( $step['image'] ); ?>" alt="<?php echo esc_attr( $step['alt'] ); ?>">
						</div>
						<div class="step-info">
							<span class="step-num"><?php echo esc_html( $step['number'] ); ?></span>
							<h3 class="y-u-text-xl y-u-font-bold y-u-mb-16"><?php echo esc_html( $step['title'] ); ?></h3>
							<p class="y-u-text-l">
								<?php echo esc_html( $step['desc'] ); ?>
							</p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="container">
		<div class="section-header y-u-text-center y-u-m-b-56">
			<h2 class="center-title"><?php echo esc_html( $settings['categories']['title'] ); ?></h2>
			<p class="section-subtitle"><?php echo esc_html( $settings['categories']['subtitle'] ); ?></p>
		</div>

		<div class="categories-grid">
			<?php foreach ( $settings['categories']['items'] as $item ) : ?>
				<div class="category-card">
					<div class="card-image">
						<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['alt'] ); ?>">
					</div>
					<div class="card-content">
						<span class="category-label"><?php echo esc_html( $item['label'] ); ?></span>
						<p class="category-desc"><?php echo esc_html( $item['desc'] ); ?></p>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="categories-section y-u-py-80">
		<div class="container y-u-max-w-1200">
			<div class="categories-header y-u-flex y-u-flex-col y-u-items-center y-u-justify-center y-u-p-40 y-u-rounded-m">
				<h2 class="y-u-text-l y-u-font-bold y-u-text-center y-u-m-b-32">
					<?php echo esc_html( $settings['templates']['title'] ); ?>
				</h2>
				<div class="tabs">
					<button class="tab-btn active" data-template-filter="all">الكل</button>
					<?php foreach ( $template_terms as $term ) : ?>
						<button class="tab-btn" data-template-filter="<?php echo esc_attr( 'term-' . $term->term_id ); ?>">
							<?php echo esc_html( $term->name ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="templates-preview y-u-grid y-u-grid-4 y-u-gap-24" data-template-group="all">
				<?php foreach ( $settings['templates']['items'] as $item ) : ?>
					<div class="temp-item">
						<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['alt'] ); ?>" />
					</div>
				<?php endforeach; ?>
			</div>
			<?php foreach ( $template_terms as $term ) :
				$term_id = (int) $term->term_id;
				$term_items = $template_category_items[ $term_id ] ?? array();
				?>
				<div class="templates-preview y-u-grid y-u-grid-4 y-u-gap-24" data-template-group="<?php echo esc_attr( 'term-' . $term_id ); ?>" style="display:none;">
					<?php foreach ( $term_items as $item ) :
						if ( empty( $item['image'] ) ) {
							continue;
						}
						$alt_text = $item['alt'] ?: $term->name;
						?>
						<div class="temp-item">
							<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $alt_text ); ?>" />
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="trust-reviews-section y-u-py-80">
		<div class="container y-u-max-w-1200">
			<div class="trust-wrapper y-u-mb-96">
				<h2 class="center-title y-u-mb-48"><?php echo esc_html( $settings['trust']['title'] ); ?></h2>
				<div class="trust-grid">
					<?php foreach ( $settings['trust']['cards'] as $card ) : ?>
						<div class="trust-card">
							<div class="icon-box">
								<img src="<?php echo esc_url( $card['image'] ); ?>" alt="<?php echo esc_attr( $card['alt'] ); ?>" />
							</div>
							<h3><?php echo esc_html( $card['title'] ); ?></h3>
							<p><?php echo esc_html( $card['desc'] ); ?></p>
						</div>
					<?php endforeach; ?>
				</div>
			</div>

			<div class="reviews-wrapper">
				<h2 class="center-title y-u-mb-48"><?php echo esc_html( $settings['reviews']['title'] ); ?></h2>
				<div class="reviews-grid">
					<?php foreach ( $settings['reviews']['items'] as $review ) : ?>
						<div class="review-card">
							<p class="review-text"><?php echo esc_html( $review['text'] ); ?></p>
							<h4 class="review-author"><?php echo esc_html( $review['author'] ); ?></h4>
							<div class="review-stars">
								<?php
								$rating = min( 5, max( 1, intval( $review['rating'] ) ) );
								for ( $i = 1; $i <= 5; $i++ ) :
									$star_class = $i <= $rating ? 'fa-solid' : 'fa-regular';
									?>
									<i class="<?php echo esc_attr( $star_class ); ?> fa-star"></i>
								<?php endfor; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</section>
</main>

<?php
get_footer();
?>
