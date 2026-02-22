<?php
/**
 * The Template for displaying product archives, including the main shop page
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

get_header();
?>

<main data-y="store-main">

    <!-- Hero Section with Booking Form -->
    <div id="y-l-page-hero" data-y="home-hero">
        <div class="y-c-hero-form-container" data-y="hero-form-container">
            <div class="y-c-hero-tabs" data-y="hero-tabs">
                <button type="button" class="y-c-hero-tab-btn" data-y="hero-tab-booking">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>احجز مستقبلاً</span>
                </button>
                <button type="button" class="y-c-hero-tab-btn active" data-y="hero-tab-search">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <span>احجز الان</span>
                </button>
            </div>
            
            <form class="y-c-hero-form-content" data-y="hero-form-content" method="get" action="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">
                
                <div class="y-l-form-row y-l-form-row-bottom">

                    <div class="y-l-form-field y-l-form-field-datetime">
                        <label>تاريخ ووقت الخروج</label>
                        <div class="y-l-datetime-inputs">
                            <div class="y-c-date-picker" data-picker="pickup-date">
                                <div class="y-c-picker-trigger">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    <span class="y-c-picker-value placeholder">اختر التاريخ</span>
                                </div>
                                <input type="hidden" name="pickup-date" id="pickup-date">
                            </div>
                            <div class="y-c-time-picker" data-picker="pickup-time">
                                <div class="y-c-picker-trigger">
                                    <span class="y-c-picker-value">الوقت</span>
                                </div>
                                <input type="hidden" name="pickup-time" id="pickup-time">
                            </div>
                        </div>
                    </div>
                    
                    <div class="y-l-form-field y-l-form-field-datetime">
                        <label>تاريخ ووقت التسليم</label>
                        <div class="y-l-datetime-inputs">
                            <div class="y-c-date-picker" data-picker="dropoff-date">
                                <div class="y-c-picker-trigger">
                                    <i class="fa-solid fa-calendar-days"></i>
                                    <span class="y-c-picker-value placeholder">اختر التاريخ</span>
                                </div>
                                <input type="hidden" name="dropoff-date" id="dropoff-date">
                            </div>
                            <div class="y-c-time-picker" data-picker="dropoff-time">
                                <div class="y-c-picker-trigger">
                                    <span class="y-c-picker-value">الوقت</span>
                                </div>
                                <input type="hidden" name="dropoff-time" id="dropoff-time">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="y-l-form-field y-l-form-field-submit">
                    <label for="hero-search-btn" style="visibility: hidden; height: 0;">&nbsp;</label>
                    <button type="submit" class="y-c-submit-button" id="hero-search-btn" data-y="hero-search-btn">
                        <span>إبحث</span>
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="y-u-container" data-y="store-content-container">
        <!-- Store Filters Section -->
        <section class="y-l-store-filters" data-y="store-filters">
            <!-- Category Filter Tabs -->
            <div class="y-c-category-filter-tabs" data-y="category-filter-tabs">
                <button type="button" class="y-c-category-filter-btn active" data-filter="all">جميع الاسطول</button>
                <?php
                $product_cats = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false,
                    'exclude' => array(), // Will be set below
                ));
                
                // Get uncategorized term ID to exclude it
                $uncategorized_term = get_term_by('slug', 'uncategorized', 'product_cat');
                $exclude_ids = array();
                if ($uncategorized_term) {
                    $exclude_ids[] = $uncategorized_term->term_id;
                }
                
                // Re-fetch with exclusion
                $product_cats = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => false,
                    'exclude' => $exclude_ids,
                ));
                
                if (!empty($product_cats) && !is_wp_error($product_cats)) {
                    foreach ($product_cats as $cat) {
                        // Also skip by slug in case exclude didn't work
                        if ($cat->slug === 'uncategorized') {
                            continue;
                        }
                        $filter_value = sanitize_title($cat->slug);
                        echo '<button type="button" class="y-c-category-filter-btn" data-filter="' . esc_attr($filter_value) . '" data-category-slug="' . esc_attr($cat->slug) . '">' . esc_html($cat->name) . '</button>';
                    }
                }
                ?>
            </div>

            <!-- Sorting and Price Controls -->
            <div class="y-l-sorting-controls" data-y="sorting-controls">
                <!-- Category Dropdown -->
                <div class="y-c-sort-group" data-y="sort-by-category">
                    <label>حسب الفئة</label>
                    <div class="y-c-dropdown">
                        <button type="button" class="y-c-dropdown-toggle" data-value="all">
                            <span class="y-c-dropdown-selected">الفئة</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <ul class="y-c-dropdown-menu">
                            <li><button type="button" data-value="all">الفئة</button></li>
                            <?php
                            if (!empty($product_cats) && !is_wp_error($product_cats)) {
                                foreach ($product_cats as $cat) {
                                    // Skip uncategorized
                                    if ($cat->slug === 'uncategorized') {
                                        continue;
                                    }
                                    echo '<li><button type="button" data-value="' . esc_attr($cat->slug) . '">' . esc_html($cat->name) . '</button></li>';
                                }
                            }
                            ?>
                        </ul>
                    </div>
                </div>

                <!-- Sort Dropdown -->
                <div class="y-c-sort-group" data-y="sort-by-price">
                    <label>الترتيب حسب</label>
                    <div class="y-c-dropdown">
                        <button type="button" class="y-c-dropdown-toggle" data-value="default">
                            <span class="y-c-dropdown-selected">السعر من الاعلى الى الاقل</span>
                            <i class="fa-solid fa-chevron-down"></i>
                        </button>
                        <ul class="y-c-dropdown-menu">
                            <li><button type="button" data-value="default">السعر من الاعلى الى الاقل</button></li>
                            <li><button type="button" data-value="price-asc">السعر من الاقل الى الاعلى</button></li>
                            <li><button type="button" data-value="name-asc">الاسم (أ - ي)</button></li>
                        </ul>
                    </div>
                </div>

                <!-- Price Range Slider -->
                <div class="y-c-price-range" data-y="price-range">
                    <div class="y-c-range-value" data-y="price-range-value">
                        <span>نطاق السعر</span>
                        <span class="y-c-range-values">
                            <span id="min-value">100</span>
                            ريال -
                            <span id="max-value">1500</span>
                            ريال
                        </span>
                    </div>
                    <div class="y-c-range-slider-wrapper">
                        <div class="y-c-slider-track">
                            <div class="y-c-slider-point" id="minPoint" style="--min-pos: 10%;"></div>
                            <div class="y-c-slider-line" style="left: 10%; right: 20%;"></div>
                            <div class="y-c-slider-point" id="maxPoint" style="--max-pos: 80%;"></div>
                        </div>
                    </div>
                    <button type="button" class="y-c-apply-price-filter-btn" data-y="apply-price-filter">
                        تطبيق نطاق السعر
                    </button>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <div class="y-l-products-section">
            <?php
            if (woocommerce_product_loop()) {
                ?>
                <ul class="y-l-products-list" id="products-container">
                    <?php
                    if (wc_get_loop_prop('is_shortcode')) {
                        $columns = absint(wc_get_loop_prop('columns'));
                        wc_set_loop_prop('columns', $columns);
                    }

                    while (have_posts()) {
                        the_post();
                        wc_get_template_part('content', 'product-list');
                    }
                    ?>
                </ul>

                <div class="y-l-show-more-container">
                    <?php
                    // Pagination
                    woocommerce_pagination();
                    ?>
                </div>
                <?php
            } else {
                ?>
                <?php do_action('woocommerce_no_products_found'); ?>
                <?php
            }
            ?>
        </div>
    </div>
</main>

<?php
get_footer();
?>
