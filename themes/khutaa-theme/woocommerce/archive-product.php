<?php
/**
 * The Template for displaying product archives, including the main shop page
 *
 * @package KhutaaTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );

// Remove default WooCommerce wrappers and breadcrumb
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );

$theme_uri = get_template_directory_uri();
$khutaa_uri = $theme_uri . '/khutaa';

// Enqueue products page specific styles
wp_enqueue_style( 'khutaa-product-card', $khutaa_uri . '/components/cards/y-c-product-card.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-btn', $khutaa_uri . '/components/buttons/y-c-btn.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-design-header', $khutaa_uri . '/templates/pages header/y-c-design-header.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-products', $khutaa_uri . '/components/products/y-c-products.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-pagination', $khutaa_uri . '/components/products/y-c-pagination.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-filter-bar', $khutaa_uri . '/components/products/y-c-filter-bar.css', array(), '1.0.0' );
wp_enqueue_style( 'khutaa-products-template', $khutaa_uri . '/templates/products/products.css', array(), '1.0.0' );

// Remove ::before pseudo-element from products list
$remove_products_before_css = '
	ul.products::before,
	ul.products.columns-2::before,
	ul.products.columns-3::before,
	ul.products.columns-4::before,
	ul.products.columns-5::before,
	ul.products.columns-6::before,
	.woocommerce ul.products::before,
	.woocommerce ul.products.columns-2::before,
	.woocommerce ul.products.columns-3::before,
	.woocommerce ul.products.columns-4::before,
	.woocommerce ul.products.columns-5::before,
	.woocommerce ul.products.columns-6::before {
		display: none !important;
		content: none !important;
		width: 0 !important;
		height: 0 !important;
		margin: 0 !important;
		padding: 0 !important;
		visibility: hidden !important;
		opacity: 0 !important;
	}
';
wp_add_inline_style( 'khutaa-products', $remove_products_before_css );

// Enqueue scripts
wp_enqueue_script( 'khutaa-design-header', $khutaa_uri . '/js/y-design-header.js', array(), '1.0.0', true );
wp_enqueue_script( 'khutaa-products', $khutaa_uri . '/js/y-products.js', array( 'jquery' ), '1.0.0', true );
wp_enqueue_script( 'khutaa-pagination', $khutaa_uri . '/js/y-pagination.js', array( 'jquery' ), '1.0.0', true );
wp_enqueue_script( 'khutaa-filter-bar', $khutaa_uri . '/js/y-filter-bar.js', array( 'jquery' ), '1.0.0', true );

// Get banner image
$banner_2_image = khutaa_get_demo_content( 'khutaa_banner_2_image' );
$default_banner = $khutaa_uri . '/assets/design.png';
?>

<header class="design-header">
	<?php if ( $banner_2_image ) : ?>
		<img src="<?php echo esc_url( $banner_2_image ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
	<?php else : ?>
		<img src="<?php echo esc_url( $default_banner ); ?>" alt="<?php esc_attr_e( 'بنر', 'khutaa-theme' ); ?>" class="design-img y-u-w-100" />
	<?php endif; ?>
</header>

<main id="main" class="y-u-container">
	<?php
	if ( woocommerce_product_loop() ) {
		// Filter Bar
		?>
		<section class="filter-section">
			<div class="new-filter-bar">
				<div class="filter-right">
					<div class="custom-dropdown products-dropdown">
						<div class="dropdown-trigger products-trigger">
							<?php esc_html_e( 'المنتجات', 'khutaa-theme' ); ?>
							<i class="fa-solid fa-angle-down dropdown-arrow"></i>
						</div>
						<ul class="dropdown-options">
							<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'الكل', 'khutaa-theme' ); ?></a></li>
							<?php
							// Get product categories (exclude uncategorized)
							$categories = get_terms( array(
								'taxonomy'   => 'product_cat',
								'hide_empty' => false,
								'exclude'    => get_option( 'default_product_cat' ), // Exclude uncategorized
							) );
							if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
								foreach ( $categories as $category ) {
									echo '<li><a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a></li>';
								}
							}
							?>
						</ul>
					</div>
				</div>

				<div class="filter-left">
					<div class="custom-dropdown sort-dropdown">
						<div class="dropdown-trigger sort-trigger">
							<?php
							$current_orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : '';
							$sort_text = __( 'ترتيب حسب', 'khutaa-theme' );
							
							if ( 'rating' === $current_orderby ) {
								$sort_text = __( 'الاعلى تقييما', 'khutaa-theme' );
							} elseif ( 'price-desc' === $current_orderby ) {
								$sort_text = __( 'السعر من الأعلى إلى الأقل', 'khutaa-theme' );
							} elseif ( 'price-asc' === $current_orderby || 'price' === $current_orderby ) {
								$sort_text = __( 'السعر من الأقل إلى الأعلى', 'khutaa-theme' );
							}
							?>
							<span class="current-sort"><?php echo esc_html( $sort_text ); ?></span>
							<i class="fa-solid fa-angle-down dropdown-arrow"></i>
						</div>
						<ul class="dropdown-options">
							<li data-sort="rating" <?php echo ( 'rating' === $current_orderby ) ? 'class="selected"' : ''; ?>><?php esc_html_e( 'الاعلى تقييما', 'khutaa-theme' ); ?></li>
							<li data-sort="price-desc" <?php echo ( 'price-desc' === $current_orderby ) ? 'class="selected"' : ''; ?>><?php esc_html_e( 'السعر من الأعلى إلى الأقل', 'khutaa-theme' ); ?></li>
							<li data-sort="price-asc" <?php echo ( 'price-asc' === $current_orderby || 'price' === $current_orderby ) ? 'class="selected"' : ''; ?>><?php esc_html_e( 'السعر من الأقل إلى الأعلى', 'khutaa-theme' ); ?></li>
						</ul>
					</div>
				</div>
			</div>
		</section>

		<?php
		woocommerce_product_loop_start();

		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();
				// Get product object
				global $product;
				// Only skip if product is truly invalid (not just check status here as it may cause issues)
				if ( ! is_a( $product, 'WC_Product' ) ) {
					continue;
				}
				wc_get_template_part( 'content', 'product' );
			}
		}

		woocommerce_product_loop_end();

		// Pagination
		?>
		<section class="pagination-section">
			<?php
			// Get the main query object
			global $wp_query;
			$max_pages = isset( $wp_query->max_num_pages ) ? $wp_query->max_num_pages : 1;
			
			$pagination = paginate_links( array(
				'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
				'format'    => '',
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $max_pages,
				'prev_text' => '<i class="fa fa-chevron-right"></i>',
				'next_text' => '<i class="fa fa-chevron-left"></i>',
				'type'      => 'list',
				'end_size'  => 3,
				'mid_size'  => 3,
			) );

			if ( $pagination ) {
				echo '<div class="pagination">' . $pagination . '</div>';
			}
			?>
		</section>
		<?php
	} else {
		do_action( 'woocommerce_no_products_found' );
	}
	?>
</main>

<script>
(function() {
	// Initialize products dropdown functionality
	function initProductsDropdown() {
		const productsDropdown = document.querySelector('.products-dropdown');
		if (!productsDropdown) return;

		const trigger = productsDropdown.querySelector('.dropdown-trigger');

		// Toggle dropdown
		if (trigger) {
			trigger.addEventListener('click', function(e) {
				e.stopPropagation();
				// Close other dropdowns
				document.querySelectorAll('.custom-dropdown').forEach(function(dropdown) {
					if (dropdown !== productsDropdown) {
						dropdown.classList.remove('open');
					}
				});
				productsDropdown.classList.toggle('open');
			});
		}

		// Close dropdown when clicking outside
		document.addEventListener('click', function(e) {
			if (!productsDropdown.contains(e.target)) {
				productsDropdown.classList.remove('open');
			}
		});
	}

	// Initialize sort dropdown functionality
	function initSortDropdown() {
		const sortDropdown = document.querySelector('.sort-dropdown');
		if (!sortDropdown) return;

		const trigger = sortDropdown.querySelector('.dropdown-trigger');
		const options = sortDropdown.querySelectorAll('.dropdown-options li');
		const currentSort = sortDropdown.querySelector('.current-sort');

		// Toggle dropdown
		if (trigger) {
			trigger.addEventListener('click', function(e) {
				e.stopPropagation();
				// Close other dropdowns
				document.querySelectorAll('.custom-dropdown').forEach(function(dropdown) {
					if (dropdown !== sortDropdown) {
						dropdown.classList.remove('open');
					}
				});
				sortDropdown.classList.toggle('open');
			});
		}

		// Handle option selection
		options.forEach(function(option) {
			option.addEventListener('click', function(e) {
				e.preventDefault();
				const sortValue = this.getAttribute('data-sort');
				const sortText = this.textContent.trim();

				// Update current sort text
				if (currentSort) {
					currentSort.textContent = sortText;
				}

				// Remove selected class from all options
				options.forEach(function(opt) {
					opt.classList.remove('selected');
				});

				// Add selected class to clicked option
				this.classList.add('selected');

				// Close dropdown
				sortDropdown.classList.remove('open');

				// Get current URL
				const url = new URL(window.location.href);
				url.searchParams.set('orderby', sortValue);
				url.searchParams.delete('paged'); // Reset to first page

				// Redirect to new URL
				window.location.href = url.toString();
			});
		});

		// Close dropdown when clicking outside
		document.addEventListener('click', function(e) {
			if (!sortDropdown.contains(e.target)) {
				sortDropdown.classList.remove('open');
			}
		});
	}

	// Initialize on DOM ready
	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', function() {
			initProductsDropdown();
			initSortDropdown();
		});
	} else {
		initProductsDropdown();
		initSortDropdown();
	}
})();
</script>

<?php
get_footer( 'shop' );
