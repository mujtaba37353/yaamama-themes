<?php
/*
Template Name: Signup
*/

get_header();
?>

<section class="y-c-container">
    <?php
    $generate_username = get_option('woocommerce_registration_generate_username') === 'yes';
    $generate_password = get_option('woocommerce_registration_generate_password') === 'yes';
    ?>
    <?php if (!empty($_GET['register_error'])) : ?>
        <?php
        $raw_errors = sanitize_text_field(wp_unslash($_GET['register_error']));
        $messages = array_filter(array_map('trim', explode('|', $raw_errors)));
        ?>
        <?php if ($messages) : ?>
            <div class="woocommerce-notices-wrapper">
                <?php foreach ($messages as $message) : ?>
                    <div class="woocommerce-error" role="alert"><?php echo esc_html($message); ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php elseif (function_exists('wc_print_notices')) : ?>
        <div class="woocommerce-notices-wrapper">
            <?php wc_print_notices(); ?>
        </div>
    <?php endif; ?>
    <form class="y-c-login-form-container woocommerce-form woocommerce-form-register" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" novalidate>
        <input type="hidden" name="action" value="ahmadi_register">
        <h1 class="y-c-header-title">إنشاء حساب</h1>
        <br>

        <?php if (!$generate_username) : ?>
            <div class="y-c-form-field">
                <label class="y-c-form-label">اسم المستخدم<span class="y-c-required-mark">*</span></label>
                <input type="text" name="username" class="y-c-form-input" required autocomplete="username">
            </div>
        <?php endif; ?>

        <div class="y-c-form-field">
            <label class="y-c-form-label">البريد الإلكتروني<span class="y-c-required-mark">*</span></label>
            <input type="email" name="email" class="y-c-form-input" required autocomplete="email">
        </div>
        <div class="y-c-form-field">
            <label class="y-c-form-label">رقم الجوال<span class="y-c-required-mark">*</span></label>
            <input type="tel" name="billing_phone" class="y-c-form-input" required inputmode="numeric" pattern="05[0-9]{8}" minlength="10" maxlength="10" placeholder="05XXXXXXXX">
        </div>
        <?php if (!$generate_password) : ?>
            <div class="y-c-form-field">
                <label class="y-c-form-label">ادخل كلمة مرور<span class="y-c-required-mark">*</span></label>
                <input type="password" name="password" class="y-c-form-input" required autocomplete="new-password">
            </div>
            <div class="y-c-form-field">
                <label class="y-c-form-label">تأكيد كلمة المرور<span class="y-c-required-mark">*</span></label>
                <input type="password" name="confirm_password" class="y-c-form-input" required autocomplete="new-password">
            </div>
        <?php else : ?>
            <p>سيتم إرسال رابط لإنشاء كلمة مرور جديدة إلى بريدك الإلكتروني.</p>
        <?php endif; ?>

        <div class="y-c-remember-me">
            <input type="checkbox" id="remember" class="y-c-remember-checkbox">
            <label for="remember">تذكرني </label>
        </div>
        <div class="y-c-login-btn-container">
            <?php wp_nonce_field('ahmadi_register', 'ahmadi_register_nonce'); ?>
            <button type="submit" class="y-c-login-btn" name="register" value="إنشاء حساب">إنشاء حساب</button>
        </div>
        <div class="y-c-login-links">
            <a href="<?php echo esc_url(ahmadi_theme_page_url('login')); ?>">لديك حساب ؟ سجل دخول</a>
        </div>
    </form>
</section>

<?php
get_footer();
