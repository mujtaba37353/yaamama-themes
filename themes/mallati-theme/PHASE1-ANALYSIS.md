# PHASE 1 — Mallati Theme Architectural Analysis

## 1) Design System (tokens.css)
- **Colors:** primary #f7931e, secondary #554533, bg #f3f1ef, text #281907, muted #282118, border #dbccbc, danger/warning/success
- **Spacing:** --y-space-0 to --y-space-600 (tokens defined)
- **Shadows:** xs, sm, md, lg
- **Fonts:** Cairo (sans), Roboto Mono (mono)
- **Typography:** --y-text-xs to --y-text-4xl, line heights, weights
- **Transitions:** fast 120ms, default 200ms, slow 320ms

## 2) Missing Tokens (must add to tokens.css)
| Token | Used In | Fix |
|-------|---------|-----|
| --y-radius-8 | reset.css, cart.css, category.css, etc. | Add: 8px |
| --y-radius-100 | utilities.css .y-u-rounded-full | Add: 9999px or 50% |
| --y-color-surface | typography pre, account.css, payment.css | Add light: #f8f6f4 (or similar) |
| --y-color-s-bg | account.css | Add light: #ebe8e4 |
| --y-color-accent | utilities.css | Add or alias to primary |
| --y-space-full | account.css | Alias to 9999px for full round |

## 3) Pages List (22 pages)
| # | Design File | WP Template |
|---|-------------|-------------|
| 1 | templates/layout/index.html | front-page.php / index.php |
| 2 | templates/category/category.html | archive-product.php |
| 3 | templates/category/sub-category.html | taxonomy-product_cat.php |
| 4 | templates/product-details/product-details.html | single-product.php |
| 5 | templates/cart/cart.html | woocommerce/cart/cart.php |
| 6 | templates/cart/cart-empty.html | (cart empty state) |
| 7 | templates/payment/payment.html | woocommerce/checkout/form-checkout.php |
| 8 | templates/login/login.html | woocommerce/myaccount/form-login.php |
| 9 | templates/signup/signup.html | woocommerce/myaccount/form-register.php |
| 10 | templates/forget-password/forget-password.html | woocommerce/myaccount/form-lost-password.php |
| 11 | templates/account/account.html | woocommerce/myaccount/my-account.php |
| 12 | templates/account/order-empty.html | (orders empty panel) |
| 13 | templates/account/address-empty.html | (addresses empty panel) |
| 14 | templates/account/new-address.html | woocommerce/myaccount/form-edit-address.php |
| 15 | templates/favourite/favourite.html | page-templates/favourites.php |
| 16 | templates/favourite/favourite-empty.html | (favorites empty state) |
| 17 | templates/contact/contact.html | page-templates/contact-us.php |
| 18 | templates/about-us/about-us.html | page-templates/about-us.php |
| 19 | templates/polices/privacy-polices.html | page-templates/privacy-policy.php |
| 20 | templates/polices/user-polices.html | page-templates/user-policy.php |
| 21 | templates/polices/exange-polices.html | page-templates/exchange-policy.php |
| 22 | templates/404/404.html | 404.php |

## 4) Components → Partials
| Component | Partial |
|-----------|---------|
| header.html | parts/header.php |
| footer.html | parts/footer.php |
| hero-slider.html | parts/hero-slider.php |
| product-card.html | parts/product-card.php |
| breadcrumb.html | parts/breadcrumb.php |
| categories-slider.html | parts/categories-slider.php |

## 5) JS Modules
| File | Purpose | WP Usage |
|------|---------|----------|
| y-app-init.js | loadFragment(header/footer), mobile menu, active links, filtration, password toggles | Replace fetch with PHP; keep mobile/filter/validation logic |
| y-hero-slider.js | Hero carousel | Keep as module |
| brands-slider.js | Categories/indicator sliders | **MISSING** - create stub or inline |
| validation.js | JustValidate forms | Keep, adapt selectors for WP |
| payment.js | Payment method toggle, success modal | Keep |
| orders-modal.js | Order details modal | Keep |

## 6) Mandatory Missing Theme Files
- style.css (theme meta)
- functions.php
- index.php
- header.php
- footer.php
- 404.php
- screenshot.png (optional)

## 7) Design Files Missing / 404
- layout.css (templates/layout/) — NOT FOUND
- brands-slider.js — NOT FOUND
- icon.png, hero1.png, cat1.png–cat5.png, tv.png, 404.png — in assets? (0 png files found)

## 8) MU-Plugin Impact
- `disable-woocommerce-styles.php`: Returns empty array for woocommerce_enqueue_styles → theme must provide ALL cart/checkout/shop CSS.

## 9) Replace Design Fetch
- y-app-init.js loads header/footer via `fetch(href)` into `<y-navbar>` and `<y-footer>`
- WP: Use get_header() / get_footer() + get_template_part('parts/header') etc. No fetch.
