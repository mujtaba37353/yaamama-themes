<?php

get_header();

$hero_post = ahmadi_theme_get_latest_post('ahmadi_home_hero');
$hero_badge = $hero_post ? get_post_meta($hero_post->ID, 'ahmadi_hero_badge', true) : '';
$hero_title = $hero_post ? get_post_meta($hero_post->ID, 'ahmadi_hero_title', true) : '';
$hero_subtitle = $hero_post ? get_post_meta($hero_post->ID, 'ahmadi_hero_subtitle', true) : '';
$hero_cta_text = $hero_post ? get_post_meta($hero_post->ID, 'ahmadi_hero_cta_text', true) : '';
$hero_cta_url = $hero_post ? get_post_meta($hero_post->ID, 'ahmadi_hero_cta_url', true) : '';
$hero_image = $hero_post ? get_post_meta($hero_post->ID, 'ahmadi_hero_image', true) : '';
$hero_bg_color = $hero_post ? get_post_meta($hero_post->ID, 'ahmadi_hero_bg_color', true) : '';

if ($hero_badge === '') {
    $hero_badge = 'خصم خاص';
}
if ($hero_title === '') {
    $hero_title = 'ثلاجة الأحمدي _ متجر جملة لجميع أنواع الإعاشة، لحوم، دجاج، مقاضي، خضراوات، توابل';
}
if ($hero_subtitle === '') {
    $hero_subtitle = 'خصم 20% في رسوم التوصيل على كل الطلبات الأون لاين';
}
if ($hero_cta_text === '') {
    $hero_cta_text = 'تسوق الآن';
}
if ($hero_cta_url === '') {
    $hero_cta_url = ahmadi_theme_page_url('shop');
}
if ($hero_image === '') {
    $hero_image = ahmadi_theme_design_image_url('image 44.png');
}
$hero_image = ahmadi_theme_normalize_media_url($hero_image);
$hero_style = $hero_bg_color !== '' ? ' style="background: ' . esc_attr($hero_bg_color) . ';"' : '';

$brand_posts = get_posts([
    'post_type' => 'ahmadi_home_brand',
    'posts_per_page' => 20,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);
$brand_items = [];
foreach ($brand_posts as $brand_post) {
    $image = get_post_meta($brand_post->ID, 'ahmadi_brand_image', true);
    if ($image === '') {
        continue;
    }
    $alt = get_post_meta($brand_post->ID, 'ahmadi_brand_alt', true);
    if ($alt === '') {
        $alt = $brand_post->post_title ?: 'Brand';
    }
    $brand_items[] = [
        'image' => ahmadi_theme_normalize_media_url($image),
        'alt' => $alt,
    ];
}
if (!$brand_items) {
    $brand_items = [
        ['image' => ahmadi_theme_asset('assets/image 1.png'), 'alt' => 'Brand 1'],
        ['image' => ahmadi_theme_asset('assets/image 2.png'), 'alt' => 'Brand 2'],
        ['image' => ahmadi_theme_asset('assets/image 3.png'), 'alt' => 'Brand 3'],
        ['image' => ahmadi_theme_asset('assets/image 4.png'), 'alt' => 'Brand 4'],
        ['image' => ahmadi_theme_asset('assets/image 5.png'), 'alt' => 'Brand 5'],
    ];
}

$promo_posts = get_posts([
    'post_type' => 'ahmadi_home_promo',
    'posts_per_page' => 2,
    'post_status' => 'publish',
    'orderby' => 'menu_order',
    'order' => 'ASC',
]);
$promo_items = [];
foreach ($promo_posts as $promo_post) {
    $promo_items[] = [
        'title' => get_post_meta($promo_post->ID, 'ahmadi_promo_title', true),
        'subtitle' => get_post_meta($promo_post->ID, 'ahmadi_promo_subtitle', true),
        'cta_text' => get_post_meta($promo_post->ID, 'ahmadi_promo_cta_text', true),
        'cta_url' => get_post_meta($promo_post->ID, 'ahmadi_promo_cta_url', true),
        'image' => get_post_meta($promo_post->ID, 'ahmadi_promo_image', true),
        'gradient_start' => get_post_meta($promo_post->ID, 'ahmadi_promo_gradient_start', true),
        'gradient_end' => get_post_meta($promo_post->ID, 'ahmadi_promo_gradient_end', true),
    ];
}

$promo_fallback_images = [
    0 => ahmadi_theme_design_image_url('image 34.png'),
    1 => ahmadi_theme_design_image_url('image 36.png'),
];
if (!$promo_items) {
    $promo_items = [
        [
            'title' => 'احصل الآن على فواكه طازجة يوميًا',
            'subtitle' => 'خصم 20% على رسوم التوصيل',
            'cta_text' => 'اذهب الآن',
            'cta_url' => ahmadi_theme_page_url('shop'),
            'image' => ahmadi_theme_design_image_url('image 34.png'),
            'gradient_start' => '',
            'gradient_end' => '',
        ],
        [
            'title' => 'احصل الآن على خضراوات طازجة يوميًا',
            'subtitle' => 'خصم 20% على رسوم التوصيل',
            'cta_text' => 'اذهب الآن',
            'cta_url' => ahmadi_theme_page_url('shop'),
            'image' => ahmadi_theme_design_image_url('image 36.png'),
            'gradient_start' => '',
            'gradient_end' => '',
        ],
    ];
}

$middle_post = ahmadi_theme_get_latest_post('ahmadi_home_middle');
$middle_badge = $middle_post ? get_post_meta($middle_post->ID, 'ahmadi_middle_badge', true) : '';
$middle_title = $middle_post ? get_post_meta($middle_post->ID, 'ahmadi_middle_title', true) : '';
$middle_subtitle = $middle_post ? get_post_meta($middle_post->ID, 'ahmadi_middle_subtitle', true) : '';
$middle_cta_text = $middle_post ? get_post_meta($middle_post->ID, 'ahmadi_middle_cta_text', true) : '';
$middle_cta_url = $middle_post ? get_post_meta($middle_post->ID, 'ahmadi_middle_cta_url', true) : '';
$middle_left_image = $middle_post ? get_post_meta($middle_post->ID, 'ahmadi_middle_left_image', true) : '';
$middle_center_image = $middle_post ? get_post_meta($middle_post->ID, 'ahmadi_middle_center_image', true) : '';
$middle_right_image = $middle_post ? get_post_meta($middle_post->ID, 'ahmadi_middle_right_image', true) : '';

if ($middle_badge === '') {
    $middle_badge = 'خصم خاص';
}
if ($middle_title === '') {
    $middle_title = 'لجميع منتجات البقالة';
}
if ($middle_subtitle === '') {
    $middle_subtitle = 'خذ الأن خصم 20% على كل طلبات التوصيل';
}
if ($middle_cta_text === '') {
    $middle_cta_text = 'تسوق الآن';
}
if ($middle_cta_url === '') {
    $middle_cta_url = ahmadi_theme_page_url('shop');
}
if ($middle_left_image === '') {
    $middle_left_image = ahmadi_theme_design_image_url('image 37.png');
}
if ($middle_center_image === '') {
    $middle_center_image = ahmadi_theme_design_image_url('image 39.png');
}
if ($middle_right_image === '') {
    $middle_right_image = ahmadi_theme_design_image_url('image 38.png');
}
$middle_left_image = ahmadi_theme_normalize_media_url($middle_left_image);
$middle_center_image = ahmadi_theme_normalize_media_url($middle_center_image);
$middle_right_image = ahmadi_theme_normalize_media_url($middle_right_image);
?>

<section class="y-c-hero"<?php echo $hero_style; ?>>
    <div class="y-c-hero-container">
        <div class="y-c-hero-content">
            <div class="y-c-hero-text">
                <span class="y-c-special-offer"><?php echo esc_html($hero_badge); ?></span>
                <h1><?php echo esc_html($hero_title); ?></h1>
                <p><?php echo esc_html($hero_subtitle); ?></p>
                <a href="<?php echo esc_url($hero_cta_url); ?>" class="y-c-hero-cta-btn">
                    <i class="fas fa-arrow-right"></i>
                    <?php echo esc_html($hero_cta_text); ?>
                </a>
            </div>
            <div class="y-c-hero-image">
                <img src="<?php echo esc_url($hero_image); ?>" alt="<?php echo esc_attr($hero_title); ?>">
            </div>
        </div>
    </div>
</section>

<section class="y-c-brands">
    <div class="y-c-brands-container" id="brands-container">
        <?php foreach ($brand_items as $brand_item) : ?>
            <img src="<?php echo esc_url($brand_item['image']); ?>" alt="<?php echo esc_attr($brand_item['alt']); ?>">
        <?php endforeach; ?>
    </div>
</section>

<section class="y-c-categories-section">
    <h2>الأقسام</h2>
    <div class="y-c-categories-carousel" role="region" aria-label="الأقسام">
        <button class="y-c-categories-arrow y-c-categories-arrow-prev" type="button" aria-label="التمرير لليمين">
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
        </button>
        <ul class="y-c-categories-grid">
        <?php
        if (function_exists('get_terms')) {
            $categories = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'orderby' => 'name',
                'order' => 'ASC',
            ]);
        } else {
            $categories = [];
        }

        $fallback_image = ahmadi_theme_asset('assets/image 46.png');

        foreach ($categories as $category) :
            if (!($category instanceof WP_Term)) {
                continue;
            }
            if (strtolower((string) $category->slug) === 'uncategorized') {
                continue;
            }

            $image_url = ahmadi_theme_get_term_image_url($category->term_id);
            if (!$image_url) {
                $image_url = $fallback_image;
            }

            $start = ahmadi_theme_get_term_color($category->term_id, 'ahmadi_category_color_start', '');
            $end = ahmadi_theme_get_term_color($category->term_id, 'ahmadi_category_color_end', '');
            $style = ($start !== '' && $end !== '')
                ? ' style="background: linear-gradient(180deg, ' . esc_attr($start) . ' 0%, ' . esc_attr($start) . ' 50%, ' . esc_attr($end) . ' 50%, ' . esc_attr($end) . ' 100%);"'
                : '';
            $link = get_term_link($category);
            if (is_wp_error($link)) {
                $link = ahmadi_theme_page_url('shop');
            }
            ?>
            <li class="y-c-category-card <?php echo esc_attr($category->slug); ?>"<?php echo $style; ?>>
                <div class="y-c-category-image">
                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
                </div>
                <div class="y-c-category-info">
                    <h3><?php echo esc_html($category->name); ?></h3>
                    <a href="<?php echo esc_url($link); ?>" class="y-c-category-link">تسوق الآن</a>
                </div>
            </li>
        <?php endforeach; ?>
        </ul>
        <button class="y-c-categories-arrow y-c-categories-arrow-next" type="button" aria-label="التمرير لليسار">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
        </button>
    </div>
</section>

<section class="y-c-products-section">
    <div class="y-c-products-header">
        <h2>المنتجات</h2>
        <a href="<?php echo esc_url(ahmadi_theme_page_url('shop')); ?>" class="y-c-view-more-btn">
            <i class="fa-solid fa-arrow-right"></i>
            انظر أكثر
        </a>
    </div>

    <ul class="y-c-products-grid">
        <?php
        if (class_exists('WooCommerce')) {
            $home_products = new WP_Query([
                'post_type' => 'product',
                'posts_per_page' => 8,
                'post_status' => 'publish',
            ]);

            if ($home_products->have_posts()) {
                while ($home_products->have_posts()) {
                    $home_products->the_post();
                    wc_get_template_part('content', 'product');
                }
            }
            wp_reset_postdata();
        }
        ?>
    </ul>
</section>

<section class="y-c-promo-banners">
    <div class="y-c-promo-container">
        <?php
        foreach ($promo_items as $index => $promo_item) :
            $banner_class = $index === 0 ? 'y-c-promo-1' : 'y-c-promo-2';
            $title = $promo_item['title'] !== '' ? $promo_item['title'] : 'عرض جديد';
            $subtitle = $promo_item['subtitle'] !== '' ? $promo_item['subtitle'] : '';
            $cta_text = $promo_item['cta_text'] !== '' ? $promo_item['cta_text'] : 'اذهب الآن';
            $cta_url = $promo_item['cta_url'] !== '' ? $promo_item['cta_url'] : ahmadi_theme_page_url('shop');
            $image = $promo_item['image'] !== ''
                ? $promo_item['image']
                : ($promo_fallback_images[$index] ?? ahmadi_theme_design_image_url('image 34.png'));
            $image = ahmadi_theme_normalize_media_url($image);
            $gradient_start = $promo_item['gradient_start'];
            $gradient_end = $promo_item['gradient_end'];
            $style = ($gradient_start && $gradient_end)
                ? ' style="background: linear-gradient(135deg, ' . esc_attr($gradient_start) . ', ' . esc_attr($gradient_end) . ');"'
                : '';
            ?>
        <div class="y-c-promo-banner <?php echo esc_attr($banner_class); ?>"<?php echo $style; ?>>
            <div class="y-c-promo-content">
                <h3><?php echo esc_html($title); ?></h3>
                <?php if ($subtitle !== '') : ?>
                    <p><?php echo esc_html($subtitle); ?></p>
                <?php endif; ?>
                <a href="<?php echo esc_url($cta_url); ?>" class="y-c-promo-text">
                    <i class="fa-solid fa-arrow-right"></i>
                    <?php echo esc_html($cta_text); ?>
                </a>
            </div>
            <div class="y-c-promo-image">
                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="y-c-banner">
    <div class="y-c-banner-container">
        <img src="<?php echo esc_url($middle_left_image); ?>" alt="vegetables Image">
        <div class="y-c-middle-banner">
            <img src="<?php echo esc_url($middle_center_image); ?>" alt="vegetables Image">
            <div>
                <p class="y-c-special-offer"><?php echo esc_html($middle_badge); ?></p>
                <h1><?php echo esc_html($middle_title); ?></h1>
                <p><?php echo esc_html($middle_subtitle); ?></p>
                <a href="<?php echo esc_url($middle_cta_url); ?>" class="y-c-banner-btn">
                    <i class="fas fa-arrow-right"></i>
                    <?php echo esc_html($middle_cta_text); ?>
                </a>
            </div>
        </div>
        <img src="<?php echo esc_url($middle_right_image); ?>" alt="Fruits Image">
    </div>
</section>

<?php
get_footer();
