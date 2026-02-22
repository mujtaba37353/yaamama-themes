<?php
get_header();

$shop_url = wc_get_page_permalink('shop');
$home_settings = wp_parse_args(al_thabihah_get_option('al_thabihah_home_settings', array()), al_thabihah_default_home_settings());
$promo_image_url = $home_settings['promo_image_id'] ? wp_get_attachment_url($home_settings['promo_image_id']) : '';
$promo_style = $promo_image_url ? ' style="background-image: url(' . esc_url($promo_image_url) . ');"' : '';
function al_thabihah_get_term_image_url($slug, $fallback_url = '') {
    $term = get_term_by('slug', $slug, 'product_cat');
    if ($term && !is_wp_error($term)) {
        $thumb_id = (int) get_term_meta($term->term_id, 'thumbnail_id', true);
        if ($thumb_id) {
            $url = wp_get_attachment_url($thumb_id);
            if ($url) {
                return $url;
            }
        }
    }
    return $fallback_url;
}

$image_urls = array(
    'offers' => al_thabihah_get_term_image_url(
        'offers',
        ($home_settings['category_offers_image_id'] ? wp_get_attachment_url($home_settings['category_offers_image_id']) : al_thabihah_asset_uri('al-thabihah/assets/offers.png'))
    ),
    'naemi' => al_thabihah_get_term_image_url(
        'naemi',
        ($home_settings['category_naemi_image_id'] ? wp_get_attachment_url($home_settings['category_naemi_image_id']) : al_thabihah_asset_uri('al-thabihah/assets/sheep.png'))
    ),
    'tays' => al_thabihah_get_term_image_url(
        'tays',
        ($home_settings['category_tays_image_id'] ? wp_get_attachment_url($home_settings['category_tays_image_id']) : al_thabihah_asset_uri('al-thabihah/assets/tees.png'))
    ),
    'ejel' => al_thabihah_get_term_image_url(
        'ejel',
        ($home_settings['category_ejel_image_id'] ? wp_get_attachment_url($home_settings['category_ejel_image_id']) : al_thabihah_asset_uri('al-thabihah/assets/cow.png'))
    ),
    'cuts' => al_thabihah_get_term_image_url(
        'cuts',
        ($home_settings['category_cuts_image_id'] ? wp_get_attachment_url($home_settings['category_cuts_image_id']) : al_thabihah_asset_uri('al-thabihah/assets/meat.png'))
    ),
);

?>

<main class="y-l-home-main" data-y="home-main">

    <section class="y-u-container y-l-home-hero" data-y="home-hero">
        <div class="y-c-hero-content" data-y="hero-content">
            <h1 class="y-c-hero-title" data-y="hero-title"><?php echo wp_kses_post($home_settings['hero_title']); ?></h1>
            <p class="y-c-hero-subtitle" data-y="hero-subtitle"><?php echo wp_kses_post($home_settings['hero_subtitle']); ?></p>
        </div>
    </section>

    <section class="y-l-category-section y-l-section" data-y="category-section">
        <div class="y-u-container">
            <div class="y-c-section-header" data-y="category-header">
                    <p class="y-c-section-subtitle" data-y="category-subtitle"><?php echo wp_kses_post($home_settings['category_subtitle']); ?></p>
                    <h2 class="y-c-section-title" data-y="category-title"><?php echo wp_kses_post($home_settings['category_title']); ?></h2>
            </div>
            <div class="y-l-category-grid" data-y="category-grid">
                <a href="<?php echo esc_url(al_thabihah_get_page_link('offers')); ?>" class="y-c-category-card" data-y="category-card-offers">
                    <div class="y-c-category-icon" data-y="category-icon-offers">
                        <img src="<?php echo esc_url($image_urls['offers']); ?>" alt="عروض" />
                    </div>
                    <h3 class="y-c-category-title" data-y="category-title-offers"><?php echo esc_html($home_settings['category_offers_label']); ?></h3>
                </a>
                <a href="<?php echo esc_url(add_query_arg('product_cat', 'naemi', $shop_url)); ?>" class="y-c-category-card" data-y="category-card-naemi">
                    <div class="y-c-category-icon" data-y="category-icon-naemi">
                        <img src="<?php echo esc_url($image_urls['naemi']); ?>" alt="نعيمي" />
                    </div>
                    <h3 class="y-c-category-title" data-y="category-title-naemi"><?php echo esc_html($home_settings['category_naemi_label']); ?></h3>
                </a>
                <a href="<?php echo esc_url(add_query_arg('product_cat', 'tays', $shop_url)); ?>" class="y-c-category-card" data-y="category-card-tays">
                    <div class="y-c-category-icon" data-y="category-icon-tays">
                        <img src="<?php echo esc_url($image_urls['tays']); ?>" alt="تيس كشميري" />
                    </div>
                    <h3 class="y-c-category-title" data-y="category-title-tays"><?php echo esc_html($home_settings['category_tays_label']); ?></h3>
                </a>
                <a href="<?php echo esc_url(add_query_arg('product_cat', 'ejel', $shop_url)); ?>" class="y-c-category-card" data-y="category-card-ejel">
                    <div class="y-c-category-icon" data-y="category-icon-ejel">
                        <img src="<?php echo esc_url($image_urls['ejel']); ?>" alt="عجل" />
                    </div>
                    <h3 class="y-c-category-title" data-y="category-title-ejel"><?php echo esc_html($home_settings['category_ejel_label']); ?></h3>
                </a>
                <a href="<?php echo esc_url(add_query_arg('product_cat', 'cuts', $shop_url)); ?>" class="y-c-category-card" data-y="category-card-cuts">
                    <div class="y-c-category-icon" data-y="category-icon-cuts">
                        <img src="<?php echo esc_url($image_urls['cuts']); ?>" alt="قطعيات لحم" />
                    </div>
                    <h3 class="y-c-category-title" data-y="category-title-cuts"><?php echo esc_html($home_settings['category_cuts_label']); ?></h3>
                </a>
            </div>
        </div>
    </section>

    <section class="y-l-featured-section y-l-section" data-y="featured-section">
        <div class="y-u-container">
            <h2 class="y-c-section-title y-u-text-center" data-y="featured-title"><?php echo wp_kses_post($home_settings['featured_title']); ?></h2>
            <ul class="y-l-products-grid" id="featured-products-container" data-y="featured-grid">
                <?php
                $featured_query = new WP_Query(
                    array(
                        'post_type' => 'product',
                        'posts_per_page' => 8,
                        'post_status' => 'publish',
                    )
                );
                if ($featured_query->have_posts()) :
                    while ($featured_query->have_posts()) :
                        $featured_query->the_post();
                        $product = wc_get_product(get_the_ID());
                        al_thabihah_render_product_card($product);
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </ul>

            <div class="y-c-show-more-container" data-y="show-more-container">
                <a href="<?php echo esc_url($shop_url); ?>" class="y-c-outline-btn y-c-basic-btn" data-y="show-more-btn">
                    <span data-y="show-more-text">عرض المزيد</span>
                    <i class="fas fa-arrow-left" data-y="show-more-arrow"></i>
                </a>
            </div>
        </div>
    </section>

    <section class="y-l-promo-banner-section y-l-section" data-y="promo-banner-section">
        <div class="y-u-container">
            <div class="y-c-promo-banner" data-y="promo-banner-content"<?php echo $promo_style; ?>>
                <div class="y-c-promo-text">
                    <h2 class="y-c-promo-title" data-y="promo-banner-title-1"><?php echo esc_html($home_settings['promo_title']); ?></h2>
                    <h3 class="y-c-promo-subtitle" data-y="promo-banner-title-2"><?php echo esc_html($home_settings['promo_subtitle']); ?></h3>
                    <a href="<?php echo esc_url($shop_url); ?>" class="y-c-promo-btn" data-y="promo-banner-btn"><?php echo esc_html($home_settings['promo_button']); ?></a>
                </div>
            </div>
        </div>
    </section>

    <section class="y-l-featured-section y-l-section" data-y="featured-section">
        <div class="y-u-container">
            <h2 class="y-c-section-title y-u-text-center" data-y="featured-title"><?php echo wp_kses_post($home_settings['offers_title']); ?></h2>
            <ul class="y-l-products-grid" id="offers-products-container" data-y="offers-grid">
                <?php
                $offers_query = new WP_Query(
                    array(
                        'post_type' => 'product',
                        'posts_per_page' => 8,
                        'post_status' => 'publish',
                        'meta_query' => array(
                            array(
                                'key' => '_sale_price',
                                'value' => 0,
                                'compare' => '>',
                                'type' => 'NUMERIC',
                            ),
                        ),
                    )
                );
                if ($offers_query->have_posts()) :
                    while ($offers_query->have_posts()) :
                        $offers_query->the_post();
                        $product = wc_get_product(get_the_ID());
                        al_thabihah_render_product_card($product);
                    endwhile;
                    wp_reset_postdata();
                endif;
                ?>
            </ul>

            <div class="y-c-show-more-container" data-y="show-more-container">
                <a href="<?php echo esc_url(al_thabihah_get_page_link('offers')); ?>" class="y-c-outline-btn y-c-basic-btn" data-y="show-more-btn">
                    <span data-y="show-more-text">عرض المزيد</span>
                    <i class="fas fa-arrow-left" data-y="show-more-arrow"></i>
                </a>
            </div>
        </div>
    </section>

    <section class="y-l-testimonials-section y-l-section" data-y="testimonials-section">
        <div class="y-u-container">
            <h2 class="y-c-section-title y-u-text-center" data-y="testimonials-title"><?php echo wp_kses_post($home_settings['testimonials_title']); ?></h2>
            <div class="y-l-testimonials-grid" data-y="testimonials-grid">

                <div class="y-c-testimonial-card" data-y="testimonial-card-1">
                    <p class="y-c-testimonial-text" data-y="testimonial-text-1">
                        اتعاملت معاهم أكتر من مرة وكل مرة نفس المستوى العالي اللي بيميزهم فعلاً إنهم بيسمعوا للعميل
                        وبيعملوا الطلب زي ما هو عايز بالظبط.
                    </p>
                    <h3 class="y-c-testimonial-name" data-y="testimonial-name-1">احمد حسنين</h3>
                    <div class="y-c-testimonial-stars" data-y="testimonial-stars-1">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="y-c-testimonial-card" data-y="testimonial-card-2">
                    <p class="y-c-testimonial-text" data-y="testimonial-text-2">
                        اتعاملت معاهم أكتر من مرة وكل مرة نفس المستوى العالي اللي بيميزهم فعلاً إنهم بيسمعوا للعميل
                        وبيعملوا الطلب زي ما هو عايز بالظبط.
                    </p>
                    <h3 class="y-c-testimonial-name" data-y="testimonial-name-2">احمد حسنين</h3>
                    <div class="y-c-testimonial-stars" data-y="testimonial-stars-2">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

                <div class="y-c-testimonial-card" data-y="testimonial-card-3">
                    <p class="y-c-testimonial-text" data-y="testimonial-text-3">
                        اتعاملت معاهم أكتر من مرة وكل مرة نفس المستوى العالي اللي بيميزهم فعلاً إنهم بيسمعوا للعميل
                        وبيعملوا الطلب زي ما هو عايز بالظبط.
                    </p>
                    <h3 class="y-c-testimonial-name" data-y="testimonial-name-3">احمد حسنين</h3>
                    <div class="y-c-testimonial-stars" data-y="testimonial-stars-3">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>

            </div>
        </div>
    </section>

</main>

<?php
get_footer();
