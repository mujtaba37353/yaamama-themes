# stationary-theme — PHASE 1 OUTPUT

## 1. الصفحات (15 صفحة)
| # | Design Path | WP Template |
|---|-------------|-------------|
| 1 | stationary/templates/layout/index.html | front-page.php / home.php |
| 2 | stationary/templates/store/store.html | archive-product.php |
| 3 | stationary/templates/store/offers.html | archive-product.php + on_sale |
| 4 | stationary/templates/product-details/product-details.html | single-product.php |
| 5 | stationary/templates/cart/cart.html | woocommerce/cart/cart.php |
| 6 | stationary/templates/cart/cart-empty.html | cart template + empty check |
| 7 | stationary/templates/payment/payment.html | woocommerce/checkout/form-checkout.php |
| 8 | stationary/templates/login/login.html | myaccount/form-login.php |
| 9 | stationary/templates/signup/signup.html | myaccount/form-register.php |
| 10 | stationary/templates/forget-password/forget-password.html | myaccount/form-lost-password.php |
| 11 | stationary/templates/profile/profile.html | myaccount/dashboard.php |
| 12 | stationary/templates/profile/profile-empty.html | myaccount + empty states |
| 13 | stationary/templates/contact/contact.html | page-contact.php |
| 14 | stationary/templates/about-us/about-us.html | page-about.php |
| 15 | stationary/templates/404/404.html | 404.php |

## 2. Tokens الناقصة (إضافتها في tokens.css)
- --y-radius-4, --y-radius-8, --y-radius-12, --y-radius-full
- --y-color-muted, --y-color-surface, --y-color-secondary
- --y-color-s-bg, --y-color-i, --y-color-accent
- --y-color-text-dark, --y-color-light, --y-main (filter.css)

## 3. الملفات الناقصة
- style.css, functions.php, index.php
- header.php, footer.php, 404.php
- inc/ (minimal) لـ enqueue و template logic
- cards.css, toggle.css (مرجع 404) — إنشاء stubs أو إزالة

## 4. Mapping نهائي
Design → Template/Partial/Hook: انظر الجدول أعلاه. Components → get_template_part('stationary/components/...')
