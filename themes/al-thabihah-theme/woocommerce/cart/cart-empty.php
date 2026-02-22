<?php
/**
 * Empty cart – تصميم الثيم (سلتك فارغة حالياً + تصفح المتجر)
 *
 * @see https://woocommerce.com/document/template-structure/
 */

defined('ABSPATH') || exit;
?>

<main class="y-l-cart-page" data-y="cart-main">
    <div class="y-u-container">

        <nav class="y-c-breadcrumbs" aria-label="breadcrumb" data-y="breadcrumbs">
            <p>
                <a href="<?php echo esc_url(home_url('/')); ?>">الرئيسية</a>
                <span>></span>
                <span data-y="bc-current">سلة المشتريات</span>
            </p>
        </nav>

        <div id="empty-cart-message" class="y-c-empty-cart" data-y="empty-cart">
            <i class="fas fa-shopping-cart"></i>
            <h3>سلتك فارغة حالياً</h3>
            <p>تصفح المتجر وأضف بعض المنتجات المميزة!</p>
            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="y-c-outline-btn y-c-basic-btn">تصفح المتجر</a>
        </div>

    </div>
</main>
