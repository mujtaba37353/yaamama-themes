<?php
get_header();

$shop_url = wc_get_page_permalink('shop');
$current_cat = get_query_var('product_cat');
$fallback_images = array(
    'cuts' => al_thabihah_asset_uri('al-thabihah/assets/meat.png'),
    'minced' => al_thabihah_asset_uri('al-thabihah/assets/meat.png'),
    'naemi' => al_thabihah_asset_uri('al-thabihah/assets/sheep.png'),
    'tays' => al_thabihah_asset_uri('al-thabihah/assets/tees.png'),
    'ejel' => al_thabihah_asset_uri('al-thabihah/assets/cow.png'),
    'offers' => al_thabihah_asset_uri('al-thabihah/assets/offers.png'),
);
$category_cards = array(
    array('slug' => 'cuts', 'label' => 'لحوم بالكيلو'),
    array('slug' => 'minced', 'label' => 'مفروم'),
    array('slug' => 'naemi', 'label' => 'نعيمي'),
    array('slug' => 'tays', 'label' => 'تيس كشميري'),
    array('slug' => 'ejel', 'label' => 'عجل'),
    array('slug' => 'bbq', 'label' => 'مجهز للشواء'),
);
function al_thabihah_store_category_image($slug, $fallbacks) {
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
    return $fallbacks[$slug] ?? '';
}
function al_thabihah_store_category_label($slug, $fallback) {
    $term = get_term_by('slug', $slug, 'product_cat');
    if ($term && !is_wp_error($term)) {
        return $term->name;
    }
    return $fallback;
}
?>

<main class="y-l-store-page" data-y="store-main">
    <div class="y-u-container">

        <nav class="y-c-breadcrumbs" aria-label="breadcrumb" data-y="breadcrumbs">
            <p>
                <a href="<?php echo esc_url(home_url('/')); ?>" data-y="bc-home-link">الرئيسية</a>
                <span>></span>
                <span data-y="bc-current-page"><?php echo esc_html($current_cat ? single_term_title('', false) : 'جميع المنتجات'); ?></span>
            </p>
        </nav>

        <section class="y-l-category-bar-section" data-y="category-section">
            <button id="category-menu-trigger" class="y-c-category-trigger-btn" data-y="category-menu-trigger">
                <span>الأقسام</span>
            </button>

            <h2 class="y-c-section-title" data-y="category-title">الأقسام</h2>

            <div class="y-c-category-overlay" id="category-overlay" data-y="category-overlay"></div>

            <div class="y-l-store-category-grid" id="category-grid" data-y="category-grid">
                <div class="y-c-sidebar-header">
                    <button id="category-menu-close" class="y-c-sidebar-close" data-y="category-menu-close">
                        <i class="fas fa-times"></i>
                    </button>
                    <h3>الأقسام</h3>
                </div>

                <a href="<?php echo esc_url($shop_url); ?>" class="y-c-store-category-card" data-category="all" data-y="category-card-all">
                    <div class="y-c-store-category-icon" data-y="category-icon-all"><i class="fas fa-box"></i></div>
                    <h3 class="y-c-store-category-title" data-y="category-title-all">جميع المنتجات</h3>
                </a>
                <?php foreach ($category_cards as $card) :
                    $slug = $card['slug'];
                    $label = al_thabihah_store_category_label($slug, $card['label']);
                    $image = al_thabihah_store_category_image($slug, $fallback_images);
                    ?>
                    <a href="<?php echo esc_url(add_query_arg('product_cat', $slug, $shop_url)); ?>" class="y-c-store-category-card" data-category="<?php echo esc_attr($slug); ?>" data-y="category-card-<?php echo esc_attr($slug); ?>">
                        <div class="y-c-store-category-icon" data-y="category-icon-<?php echo esc_attr($slug); ?>">
                            <?php if ($image) : ?>
                                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($label); ?>">
                            <?php elseif ($slug === 'bbq') : ?>
                                <i class="fa-brands fa-gripfire"></i>
                            <?php else : ?>
                                <i class="fas fa-box"></i>
                            <?php endif; ?>
                        </div>
                        <h3 class="y-c-store-category-title" data-y="category-title-<?php echo esc_attr($slug); ?>"><?php echo esc_html($label); ?></h3>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <h1 class="y-c-section-title" id="products-section-title" data-y="products-section-title">جميع المنتجات</h1>

        <ul class="y-l-products-grid" id="products-container" data-y="products-grid">
            <?php if (have_posts()) : ?>
                <?php while (have_posts()) : the_post(); ?>
                    <?php
                    $product = wc_get_product(get_the_ID());
                    al_thabihah_render_product_card($product);
                    ?>
                <?php endwhile; ?>
            <?php else : ?>
                <li style="grid-column: 1/-1; text-align: center; padding: 4rem 2rem; color: var(--y-color-primary-text);">
                    <i class="fas fa-box-open" style="font-size: 4rem; color: var(--y-color-error); margin-bottom: 1rem;"></i>
                    <h3 style="margin-bottom: 0.5rem; font-size: 1.5rem;">لا توجد منتجات في هذا القسم</h3>
                    <p style="color: var(--y-color-third-text);">جرب تصفح قسم آخر.</p>
                </li>
            <?php endif; ?>
        </ul>

        <?php
        global $wp_query;
        $next_link = get_next_posts_page_link($wp_query->max_num_pages);
        if ($next_link) :
        ?>
            <div class="y-c-show-more-container" id="show-more-container" data-y="show-more-container">
                <a class="y-c-outline-btn y-c-show-more-btn y-c-basic-btn" id="show-more-btn" data-y="show-more-btn" href="<?php echo esc_url($next_link); ?>">
                    <span data-y="show-more-text">عرض المزيد</span>
                    <i class="fas fa-arrow-left" data-y="show-more-arrow"></i>
                </a>
            </div>
        <?php endif; ?>

    </div>
</main>

<?php
get_footer();
