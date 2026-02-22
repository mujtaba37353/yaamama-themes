<?php
/**
 * The front page template
 *
 * @package MyCarTheme
 */

get_header();
?>

<main class="y-l-home-main" data-y="main">

    <!-- ===== Hero Section with Booking Form ===== -->
    <div id="y-l-page-hero" data-y="home-hero">
        
        <!-- Booking Form Container -->
        <div class="y-c-hero-form-container" data-y="hero-form-container">
            
            <!-- Tabs -->
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
            
            <!-- Form Content -->
            <form class="y-c-hero-form-content" data-y="hero-form-content" method="get" action="<?php echo function_exists('wc_get_page_permalink') ? esc_url(wc_get_page_permalink('shop')) : esc_url(home_url('/shop')); ?>">
                
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

    <!-- ===== Categories Section ===== -->
    <section class="y-l-home-title y-u-container y-l-categories" data-y="home-categories-section">
        
        <h2 class="y-c-home-title" data-y="categories-title">الفئات</h2>
        
        <div class="y-l-categories-grid" data-y="categories-grid">
            <?php
            // Get product categories
            $product_categories = get_terms(array(
                'taxonomy' => 'product_cat',
                'hide_empty' => true,
                'number' => 3, // Show only first 3 categories
            ));

            if (!empty($product_categories) && !is_wp_error($product_categories)) {
                foreach ($product_categories as $category) {
                    // Get category image
                    $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                    $image_url = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'large') : get_template_directory_uri() . '/my-car/assets/product.png';
                    $category_link = get_term_link($category);
                    ?>
                    <div class="y-c-category-card">
                        <h3 class="y-c-category-title"><?php echo esc_html($category->name); ?></h3>
                        
                        <div class="y-c-category-image">
                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($category->name); ?>">
                        </div>
                        
                        <div class="y-c-category-content">
                            <p class="y-c-category-description">
                                <?php echo esc_html($category->description ? $category->description : 'اكتشف مجموعتنا الواسعة من السيارات في هذه الفئة.'); ?>
                            </p>
                            <a href="<?php echo esc_url($category_link); ?>" class="y-c-basic-btn y-c-category-link">المزيد</a>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // Fallback if no categories exist
                ?>
                <div class="y-c-category-card">
                    <h3 class="y-c-category-title">السيارات الصغيرة</h3>
                    <div class="y-c-category-image">
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/product.png" alt="السيارات الصغيرة">
                    </div>
                    <div class="y-c-category-content">
                        <p class="y-c-category-description">
                            تبحث عن سيارة يومية للتنقلات اليومية والذهاب إلى العمل؟ هذه الفئة توفر لك سيارات بأسعار مناسبة لميزانيتك.
                        </p>
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-basic-btn y-c-category-link">المزيد</a>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
        
    </section>

    <!-- ===== Fleet Section ===== -->
    <section class="y-l-home-title y-u-container" data-y="home-fleet-section">
        <h2 class="y-c-home-title" data-y="fleet-title">أسطولنا</h2>
        
        <ul class="y-l-products-grid" id="home-fleet-grid" data-y="home-fleet-grid">
            <?php
            // Get products from WooCommerce
            if (function_exists('wc_get_products')) {
                $products = wc_get_products(array(
                    'limit' => 4,
                    'status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                ));

                if (!empty($products)) {
                    foreach ($products as $product) {
                        // Get product category
                        $categories = wp_get_post_terms($product->get_id(), 'product_cat');
                        $category_name = !empty($categories) && !is_wp_error($categories) ? $categories[0]->name : '';
                        
                        // Get product image
                        $image_id = $product->get_image_id();
                        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : wc_placeholder_img_src('large');
                        
                        // Get product price
                        $price = $product->get_price();
                        ?>
                        <li class="y-c-fleet-card">
                            <?php if ($category_name) : ?>
                                <span class="y-c-fleet-category"><?php echo esc_html($category_name); ?></span>
                            <?php endif; ?>
                            
                            <div class="y-c-fleet-card-image">
                                <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                                </a>
                            </div>
                            
                            <div class="y-c-fleet-card-content">
                                <h3 class="y-c-fleet-name">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a>
                                </h3>
                                <div class="y-c-fleet-price">
                                    <?php echo esc_html($price); ?>
                                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/coin.png" alt="ريال سعودي" class="y-c-coin-icon">
                                </div>
                                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="y-c-basic-btn y-c-fleet-book-btn">احجز الان</a>
                            </div>
                        </li>
                        <?php
                    }
                } else {
                    // Fallback if no products
                    for ($i = 1; $i <= 4; $i++) {
                        ?>
                        <li class="y-c-fleet-card">
                            <span class="y-c-fleet-category">سيدان صغيرة</span>
                            <div class="y-c-fleet-card-image">
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/product.png" alt="هيونداي اكسنت 2025">
                            </div>
                            <div class="y-c-fleet-card-content">
                                <h3 class="y-c-fleet-name">هيونداي اكسنت 2025</h3>
                                <div class="y-c-fleet-price">
                                    200
                                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/coin.png" alt="ريال سعودي" class="y-c-coin-icon">
                                </div>
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-basic-btn y-c-fleet-book-btn">احجز الان</a>
                            </div>
                        </li>
                        <?php
                    }
                }
            }
            ?>
        </ul>
        
    </section>

    <!-- ===== Banner Section ===== -->
    <section class="y-l-home-banner" data-y="home-banner">
        <div class="y-c-banner-content" data-y="banner-content">
            
            <div class="y-c-banner-image" data-y="banner-image">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/banner.png" alt="تأجير سيارات كيان" class="y-c-banner-img">
            </div>
            
            <div class="y-c-banner-text" data-y="banner-text">
                <h2 class="y-c-banner-title" data-y="banner-title">تأجير سيارات كيان</h2>
                <p class="y-c-banner-description" data-y="banner-description">
                    في شركة كيان لتأجير السيارات، نقدم تشكيلة واسعة من السيارات وخدمات الليموزين لرجال الأعمال من خلال أسطول مكون من أحدث موديلات السيارات الفاخرة الحديثة، وسائقين محترفين لضمان جودة تجربة فريدة
                </p>
            </div>
            
        </div>
    </section>

    <!-- ===== Offers Section ===== -->
    <section class="y-l-home-title y-u-container" data-y="home-offers-section">
        <h2 class="y-c-home-title" data-y="offers-title">عروضنا</h2>
        
        <ul class="y-l-products-grid" id="home-offers-grid" data-y="home-offers-grid">
            <?php
            // Get products on sale or latest products (offset by 4 to get next products)
            if (function_exists('wc_get_products')) {
                $offers = wc_get_products(array(
                    'limit' => 4,
                    'status' => 'publish',
                    'orderby' => 'date',
                    'order' => 'DESC',
                    'offset' => 4, // Skip first 4 products from fleet section
                ));

                // If not enough products, get on-sale products
                if (empty($offers) || count($offers) < 4) {
                    $offers = wc_get_products(array(
                        'limit' => 4,
                        'status' => 'publish',
                        'on_sale' => true,
                    ));
                }

                if (!empty($offers)) {
                    foreach ($offers as $product) {
                        // Get product category
                        $categories = wp_get_post_terms($product->get_id(), 'product_cat');
                        $category_name = !empty($categories) && !is_wp_error($categories) ? $categories[0]->name : '';
                        
                        // Get product image
                        $image_id = $product->get_image_id();
                        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : wc_placeholder_img_src('large');
                        
                        // Get product price
                        $regular_price = $product->get_regular_price();
                        $sale_price = $product->get_sale_price();
                        $price = $sale_price ? $sale_price : $regular_price;
                        ?>
                        <li class="y-c-fleet-card">
                            <?php if ($category_name) : ?>
                                <span class="y-c-fleet-category"><?php echo esc_html($category_name); ?></span>
                            <?php endif; ?>
                            
                            <div class="y-c-fleet-card-image">
                                <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                    <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($product->get_name()); ?>">
                                </a>
                            </div>
                            
                            <div class="y-c-fleet-card-content">
                                <h3 class="y-c-fleet-name">
                                    <a href="<?php echo esc_url($product->get_permalink()); ?>"><?php echo esc_html($product->get_name()); ?></a>
                                </h3>
                                <div class="y-l-fleet-price">
                                    <div class="y-c-fleet-price">
                                        <?php echo esc_html($price); ?>
                                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/coin.png" alt="ريال سعودي" class="y-c-coin-icon">
                                    </div>
                                    <span class="y-c-offer-time">عرض خاص</span>
                                </div>
                                <a href="<?php echo esc_url($product->get_permalink()); ?>" class="y-c-basic-btn y-c-fleet-book-btn">احجز الان</a>
                            </div>
                        </li>
                        <?php
                    }
                } else {
                    // Fallback if no products
                    for ($i = 1; $i <= 4; $i++) {
                        ?>
                        <li class="y-c-fleet-card">
                            <span class="y-c-fleet-category">سيدان صغيرة</span>
                            <div class="y-c-fleet-card-image">
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/product.png" alt="هيونداي اكسنت 2025">
                            </div>
                            <div class="y-c-fleet-card-content">
                                <h3 class="y-c-fleet-name">هيونداي اكسنت 2025</h3>
                                <div class="y-l-fleet-price">
                                    <div class="y-c-fleet-price">
                                        200
                                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/coin.png" alt="ريال سعودي" class="y-c-coin-icon">
                                    </div>
                                    <span class="y-c-offer-time">لمدة 6 ايام</span>
                                </div>
                                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-basic-btn y-c-fleet-book-btn">احجز الان</a>
                            </div>
                        </li>
                        <?php
                    }
                }
            }
            ?>
        </ul>
        
    </section>

    <!-- ===== Branches Section ===== -->
    <section class="y-l-branches-section y-u-container" data-y="branches-section">
        <div class="y-c-branches-content" data-y="branches-content">
            
            <div class="y-c-branches-image" data-y="branches-image">
                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/my-car/assets/home-end.png" alt="شبكة فروع ماي كار" class="y-c-branches-img">
            </div>
            
            <div class="y-c-branches-text" data-y="branches-text">
                <p class="y-c-branches-description" data-y="branches-description">
                    في ماي كار، تضم شبكة فروعنا الواسعة أكثر من 90 فرع منتشرة في مدن ومطارات المملكة العربية السعودية. بالإضافة إلى تواجدنا في دولة الإمارات العربية المتحدة وجمهورية مصر العربية ، نحرص دائمًا ان نخدم عملائنا بشكل يليق بهم في كل وقت ومكان ليضمن لهم راحة في تجربتهم.
                </p>
            </div>
            
        </div>
    </section>

</main>

<?php
get_footer();
?>
