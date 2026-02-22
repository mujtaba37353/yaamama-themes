# Elegance Theme – تنفيذ ثيم ووردبريس

تم تحويل التصميم الثابت (HTML/CSS/JS) من `elegance-theme/elegance` إلى ثيم ووردبريس فعلي.

## هيكل الثيم

- **style.css** – معلومات الثيم
- **functions.php** – تسجيل الأصول، دعم WooCommerce، دوال مساعدة
- **header.php / footer.php** – الهيدر والفوتر مع روابط WP و `data-nav` للرابط النشط
- **index.php, page.php, single.php** – قوالب افتراضية
- **front-page.php** – الصفحة الرئيسية (هيكل layout/index.html)
- **404.php** – صفحة الخطأ
- **page-about-us.php, page-contact.php** – من نحن، تواصل معنا
- **page-login.php, page-signup.php, page-forget-password.php** – تسجيل الدخول، إنشاء حساب، نسيت كلمة المرور
- **page-profile.php, page-payment.php** – حسابي، الدفع
- **woocommerce/** – قوالب المتجر (archive-product، content-product، single-product، cart، cart-empty)

## الأصول (CSS/JS)

- تُحمّل من `elegance-theme/elegance/` (base، components، templates، js، assets).
- **y-app-init-wp.js** – نسخة ووردبريس: لا fetch للهيدر/الفوتر، الرابط النشط من `body_class` (elegance-nav-*).

## الروابط والـ Nav

- الهيدر والفوتر يستخدمان: `home_url()`، `elegance_shop_url()`، `elegance_cart_url()`، `elegance_myaccount_url()`، `elegance_page_url($slug)`.
- الرابط النشط: إضافة `data-nav="home|shop|about-us|contact|cart|profile"` في الهيدر، و`body_class` في `functions.php` (elegance_body_class_nav).

## الاختبار

1. تفعيل الثيم وتعيين الصفحة الرئيسية إذا لزم.
2. إنشاء صفحات: من نحن (slug: about-us)، تواصل معنا (contact)، تسجيل الدخول (login)، إنشاء حساب (signup)، نسيت كلمة المرور (forget-password)، حسابي (profile)، الدفع (payment) وتعيين القالب المناسب لكل منها.
3. مع WooCommerce: تعيين صفحات المتجر والسلة وحسابي والدفع من إعدادات WooCommerce.
4. التحقق: Console بدون أخطاء، Network بدون 404، RTL، Responsive (375 / 768 / 1440).

## القرارات المطبقة

- **components-loader.js:** الخيار ب – عدم استخدام `<y-navbar>`/`<y-footer>`؛ استخدام `get_header()` و `get_footer()` وتعديل JS لاستخدام body class للرابط النشط.
