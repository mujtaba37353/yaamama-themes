<?php
get_header();

global $product;
if (!$product || !is_a($product, 'WC_Product')) {
    $product = wc_get_product(get_the_ID());
}
$has_product = is_object($product) && is_a($product, 'WC_Product');
$product_id = $has_product ? $product->get_id() : 0;
$image_id = $has_product ? $product->get_image_id() : 0;
$main_image = $image_id ? wp_get_attachment_image_url($image_id, 'large') : al_thabihah_asset_uri('al-thabihah/assets/product.jpg');
$gallery_ids = $has_product ? $product->get_gallery_image_ids() : array();
$price = $has_product ? $product->get_price() : '';
?>

<main class="y-l-product-page" data-y="product-main">
    <div class="y-u-container">
        <nav class="y-c-breadcrumbs" aria-label="breadcrumb" data-y="breadcrumbs">
            <p>
                <a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a>
                <span>></span>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>">المتجر</a>
                <span>></span>
                <span id="breadcrumb-product-name" data-y="bc-product-name"><?php the_title(); ?></span>
            </p>
        </nav>

        <div class="y-l-product-details-container" data-y="product-container">

            <div class="y-c-product-gallery" data-y="product-gallery">
                <div class="y-c-main-image-wrapper" data-y="main-image-wrapper">
                    <img src="<?php echo esc_url($main_image); ?>" alt="<?php the_title_attribute(); ?>" id="main-product-image" class="y-c-main-image" data-y="main-image">
                </div>
                <div class="y-c-thumbnails-list" id="product-thumbnails" data-y="thumbnails-list">
                    <img src="<?php echo esc_url($main_image); ?>" class="y-c-thumbnail active" data-y="thumb-1">
                    <?php foreach ($gallery_ids as $index => $gallery_id) : ?>
                        <?php $gallery_url = wp_get_attachment_image_url($gallery_id, 'thumbnail'); ?>
                        <img src="<?php echo esc_url($gallery_url); ?>" class="y-c-thumbnail" data-y="thumb-<?php echo esc_attr($index + 2); ?>">
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="y-c-product-info-col" data-y="product-info-col">

                <h1 class="y-c-product-title-lg" id="product-name" data-y="product-name"><?php the_title(); ?></h1>

                <p class="y-c-subtitle">وصف المنتج</p>

                <p class="y-c-product-desc" id="product-description" data-y="product-description">
                    <?php echo wp_kses_post($product ? $product->get_short_description() : ''); ?>
                </p>

                <div class="y-c-product-price-lg" id="product-price-container" data-y="product-price">
                    <span class="y-c-price-current" id="product-price"><?php echo esc_html(number_format_i18n((float) $price, 0)); ?></span>
                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/coin.png')); ?>" class="y-c-coin-icon" alt="ريال سعودي">
                </div>

                <hr class="y-c-divider">

                <form id="add-to-cart-form" class="y-c-product-options" data-y="product-options-form" method="post" action="<?php echo esc_url(wc_get_cart_url()); ?>">
                    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product_id); ?>">

                    <div class="y-c-option-group" data-y="option-cutting">
                        <label class="y-c-option-label">طريقة التقطيع:</label>
                        <div class="y-c-option-buttons">
                            <label class="y-c-radio-btn">
                                <input type="radio" name="cutting" value="whole" checked>
                                <span>كامل بدون تقطيع</span>
                            </label>
                            <label class="y-c-radio-btn">
                                <input type="radio" name="cutting" value="halves">
                                <span>نصفين</span>
                            </label>
                            <label class="y-c-radio-btn">
                                <input type="radio" name="cutting" value="quarters">
                                <span>أرباع</span>
                            </label>
                            <label class="y-c-radio-btn">
                                <input type="radio" name="cutting" value="flattened">
                                <span>مفطح</span>
                            </label>
                            <label class="y-c-radio-btn">
                                <input type="radio" name="cutting" value="soup">
                                <span>تقطيع شوربة</span>
                            </label>
                            <label class="y-c-radio-btn">
                                <input type="radio" name="cutting" value="fridge">
                                <span>تقطيع ثلاجة</span>
                            </label>
                        </div>
                    </div>

                    <div class="y-c-option-group" data-y="option-packaging">
                        <label class="y-c-option-label">التغليف:</label>
                        <div class="y-c-option-buttons">
                            <label class="y-c-radio-btn">
                                <input type="radio" name="packaging" value="none" checked>
                                <span>بدون تغليف</span>
                            </label>
                            <label class="y-c-radio-btn">
                                <input type="radio" name="packaging" value="plates">
                                <span>أطباق مغلفة</span>
                            </label>
                            <label class="y-c-radio-btn">
                                <input type="radio" name="packaging" value="bags">
                                <span>تكييس</span>
                            </label>
                            <label class="y-c-radio-btn">
                                <input type="radio" name="packaging" value="vacuum">
                                <span>أكياس فاكيوم (مفرغة من الهواء)</span>
                            </label>
                        </div>
                    </div>

                    <div class="y-c-product-actions" data-y="product-actions">

                        <div class="y-c-quantity-selector" data-y="quantity-selector">
                            <button type="button" class="y-c-qty-btn" id="qty-plus">+</button>
                            <input type="number" id="qty-input" name="quantity" value="1" min="1" readonly>
                            <button type="button" class="y-c-qty-btn" id="qty-minus">-</button>
                        </div>

                        <button type="submit" class="y-c-outline-btn y-c-basic-btn" id="add-to-cart-btn" data-y="add-to-cart-btn">
                            <i class="fas fa-shopping-cart"></i>
                            اضف للسلة
                        </button>

                    </div>

                </form>

            </div>

        </div>

        <section class="y-c-related-products-section" data-y="related-products-section">
            <div class="y-c-section-header" data-y="section-header">
                <h2 class="y-c-section-title" data-y="related-title">منتجات ذات صلة</h2>
                <div class="y-c-slider-nav" data-y="slider-nav">
                    <button type="button" class="y-c-slider-arrow y-c-slider-prev" data-y="slider-prev" aria-label="السابق">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                    <button type="button" class="y-c-slider-arrow y-c-slider-next" data-y="slider-next" aria-label="التالي">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>
            </div>

            <div class="y-c-products-slider" data-y="products-slider">
                <div class="y-c-slider-track" id="related-products-track" data-y="related-grid">
                    <?php
                    $related_ids = $product ? wc_get_related_products($product->get_id(), 8) : array();
                    foreach ($related_ids as $related_id) {
                        $related_product = wc_get_product($related_id);
                        if ($related_product) {
                            al_thabihah_render_product_card($related_product);
                        }
                    }
                    ?>
                </div>
            </div>
        </section>

        <div class="y-l-features-section" data-y="features-section">

            <div class="y-c-feature-card" data-y="feature-delivery">

                <div class="y-c-feature-text">
                    <h3>توصيل سريع</h3>
                    <p>توصيل سريع لجميع أنحاء المملكة وفي أسرع وقت</p>
                </div>
                <div class="y-c-feature-icon">
                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/truck.png')); ?>" class="y-c-truck-icon" alt="شاحنة توصيل">
                </div>
            </div>

            <div class="y-c-feature-card" data-y="feature-fresh">

                <div class="y-c-feature-text">
                    <h3>منتجات طازجة</h3>
                    <p>جميع منتجاتنا طازجة ومغلفة ومخزنة بأفضل الطرق</p>
                </div>
                <div class="y-c-feature-icon">
                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/meat2.png')); ?>" class="y-c-drumstick-icon" alt="دجاجة">
                </div>
            </div>

            <div class="y-c-feature-card" data-y="feature-payment">

                <div class="y-c-feature-text">
                    <h3>طرق دفع متعددة</h3>
                    <p>يمكنك اختيار أسهل طرق الدفع من بين الخيارات المتعددة</p>
                </div>
                <div class="y-c-feature-icon">
                    <img src="<?php echo esc_url(al_thabihah_asset_uri('al-thabihah/assets/credit-card.png')); ?>" class="y-c-payment-icon" alt="رمز دفع">
                </div>
            </div>

        </div>
    </div>
</main>

<?php
get_footer();
