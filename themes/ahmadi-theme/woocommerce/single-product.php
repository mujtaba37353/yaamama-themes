<?php

defined('ABSPATH') || exit;

get_header();

while (have_posts()) :
    the_post();
    $product = wc_get_product(get_the_ID());
    if (!$product) {
        continue;
    }
    $stock_quantity = $product->get_stock_quantity();
    $stock_text = $product->is_in_stock()
        ? ($stock_quantity ? $stock_quantity . ' متوفر في المخزون' : 'متوفر في المخزون')
        : 'غير متوفر';
    $category_names = wc_get_product_category_list($product->get_id(), ', ');
    ?>
    <section class="y-c-container">
        <div class="y-c-Element-row">
            <?php echo wp_kses_post(ahmadi_theme_get_product_image_html($product, 'woocommerce_single')); ?>

            <div class="y-c-Element-col">
                <h1><?php the_title(); ?></h1>
                <p class="y-c-price"><?php echo wp_kses_post($product->get_price_html()); ?></p>
                <small id="stock"><?php echo esc_html($stock_text); ?></small>

                <form class="cart" method="post" enctype="multipart/form-data">
                    <div class="y-c-actions">
                        <div class="y-c-quantity">
                            <?php
                            woocommerce_quantity_input([
                                'input_value' => 1,
                                'min_value' => 1,
                                'max_value' => $product->get_max_purchase_quantity(),
                            ]);
                            ?>
                        </div>
                        <button type="submit" class="y-c-add-cart">إضافة إلى السلة</button>
                    </div>
                    <input type="hidden" name="add-to-cart" value="<?php echo esc_attr($product->get_id()); ?>">
                </form>

                <button class="y-c-fav" type="button"
                        data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                        data-product-name="<?php echo esc_attr($product->get_name()); ?>"
                        data-product-price="<?php echo esc_attr($product->get_price()); ?>"
                        data-product-image="<?php echo esc_url(wp_get_attachment_image_url($product->get_image_id(), 'woocommerce_thumbnail')); ?>">
                    <i class="fa-regular fa-heart"></i>
                    <span class="y-c-fav-text">إضافة إلى المفضلة</span>
                </button>

                <div class="y-c-details">
                    <p>رمز المنتج: <?php echo esc_html($product->get_sku() ?: 'SKU'); ?></p>
                    <p>التصنيف: <?php echo wp_kses_post($category_names ?: ''); ?></p>
                    <h4>كرتون</h4>
                </div>
            </div>
        </div>

        <section class="y-c-reviews">
            <h2>المراجعات (<?php echo esc_html(get_comments_number()); ?>)</h2>
            <p class="y-c-be-first">كن أول من يقيم <?php the_title(); ?></p>
            <div class="y-c-rating">
                <label>تقييمك: </label>
                <div class="y-c-stars">
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                    <i class="fa-regular fa-star"></i>
                </div>
            </div>
            <textarea placeholder="اكتب مراجعتك هنا..."></textarea>
            <button class="y-c-submit-review" type="button">إرسال المراجعة</button>
        </section>

        <section class="y-c-products-section">
            <div class="y-c-products-header">
                <h3>منتجات ذات صلة</h3>
                <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="y-c-view-more-btn">
                    <i class="fa-solid fa-arrow-right"></i>
                    انظر أكثر
                </a>
            </div>

            <ul class="y-c-products-grid">
                <?php
                $related_ids = wc_get_related_products($product->get_id(), 4);
                foreach ($related_ids as $related_id) :
                    $related_product = wc_get_product($related_id);
                    if (!$related_product) {
                        continue;
                    }
                    ?>
                    <?php
                    $related_add_url = add_query_arg(
                        'add-to-cart',
                        $related_product->get_id(),
                        wc_get_cart_url()
                    );
                    ?>
                    <li class="y-c-product-card">
                        <a href="<?php echo esc_url(get_permalink($related_id)); ?>" class="y-c-product-image">
                            <?php echo wp_kses_post($related_product->get_image('woocommerce_thumbnail')); ?>
                            <span class="y-c-favorite-icon"><i class="far fa-heart"></i></span>
                        </a>
                        <div class="y-c-product-info">
                            <h4><?php echo esc_html($related_product->get_name()); ?></h4>
                            <div class="y-c-product-price"><?php echo wp_kses_post($related_product->get_price_html()); ?></div>
                            <a href="<?php echo esc_url($related_add_url); ?>"
                               class="y-c-shop-now-btn"
                               data-quantity="1"
                               data-product_id="<?php echo esc_attr($related_product->get_id()); ?>">
                                إضافة الى السلة
                            </a>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const favButton = document.querySelector('.y-c-fav');
            if (!favButton) {
                return;
            }

            const icon = favButton.querySelector('i');
            const text = favButton.querySelector('.y-c-fav-text');
            const product = {
                id: favButton.dataset.productId,
                name: favButton.dataset.productName,
                price: favButton.dataset.productPrice,
                image: favButton.dataset.productImage
            };

            let favorites = [];
            try {
                favorites = JSON.parse(localStorage.getItem('favorites')) || [];
            } catch (error) {
                favorites = [];
            }

            const isSaved = favorites.some(item => String(item.id) === String(product.id));
            if (isSaved) {
                favButton.classList.add('y-c-active');
                if (icon) {
                    icon.classList.remove('fa-regular');
                    icon.classList.add('fa-solid');
                }
                if (text) {
                    text.textContent = ' تمت الإضافة إلى المفضلة';
                }
            }

            favButton.addEventListener('click', () => {
                const isFavorite = favButton.classList.toggle('y-c-active');
                if (icon) {
                    icon.classList.toggle('fa-regular', !isFavorite);
                    icon.classList.toggle('fa-solid', isFavorite);
                }
                if (text) {
                    text.textContent = isFavorite ? ' تمت الإضافة إلى المفضلة' : ' إضافة إلى المفضلة';
                }

                if (isFavorite) {
                    if (!favorites.some(item => String(item.id) === String(product.id))) {
                        favorites.push(product);
                    }
                } else {
                    favorites = favorites.filter(item => String(item.id) !== String(product.id));
                }

                localStorage.setItem('favorites', JSON.stringify(favorites));
            });
        });
    </script>
<?php endwhile; ?>

<?php
get_footer();
