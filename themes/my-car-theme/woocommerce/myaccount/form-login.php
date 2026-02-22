<?php
/**
 * Login/Register Form - Custom Template
 * Shows login or register form based on ?action parameter
 *
 * @package MyCarTheme
 */

if (!defined('ABSPATH')) {
    exit;
}

// Check if we should show register form
$show_register = isset($_GET['action']) && $_GET['action'] === 'register' && 'yes' === get_option('woocommerce_enable_myaccount_registration');

do_action('woocommerce_before_customer_login_form');

if ($show_register) :
    // Show Register Form
?>

<div class="y-l-myaccount-login y-l-single-form">
    <div class="y-l-container">
        
        <!-- Page Header -->
        <div class="y-l-myaccount-header">
            <div class="y-c-myaccount-icon y-c-icon-register">
                <i class="fa-solid fa-user-plus"></i>
            </div>
            <h1 class="y-c-myaccount-title">إنشاء حساب جديد</h1>
            <p class="y-c-myaccount-subtitle">أنشئ حسابك للاستمتاع بخدماتنا</p>
        </div>

        <div class="y-l-single-form-wrapper">
            
            <!-- Register Form -->
            <div class="y-c-auth-card">
                
                <form method="post" class="woocommerce-form woocommerce-form-register register y-c-auth-form" <?php do_action('woocommerce_register_form_tag'); ?>>

                    <?php do_action('woocommerce_register_form_start'); ?>

                    <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                        <div class="y-c-form-group">
                            <label for="reg_username" class="y-c-form-label">
                                اسم المستخدم
                                <span class="y-c-required">*</span>
                            </label>
                            <div class="y-c-input-wrapper">
                                <i class="fa-solid fa-user"></i>
                                <input type="text" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="username" id="reg_username" autocomplete="username" placeholder="اختر اسم مستخدم" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required />
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="y-c-form-group">
                        <label for="reg_email" class="y-c-form-label">
                            البريد الإلكتروني
                            <span class="y-c-required">*</span>
                        </label>
                        <div class="y-c-input-wrapper">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="email" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="email" id="reg_email" autocomplete="email" placeholder="أدخل بريدك الإلكتروني" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="y-c-form-group">
                        <label for="reg_password" class="y-c-form-label">
                            كلمة المرور
                            <span class="y-c-required">*</span>
                        </label>
                        <div class="y-c-input-wrapper y-c-password-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="password" id="reg_password" autocomplete="new-password" placeholder="اختر كلمة مرور قوية" required minlength="8" />
                            <button type="button" class="y-c-toggle-password" onclick="togglePassword('reg_password', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <div class="y-c-password-strength" id="password-strength"></div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="y-c-form-group">
                        <label for="reg_password_confirm" class="y-c-form-label">
                            تأكيد كلمة المرور
                            <span class="y-c-required">*</span>
                        </label>
                        <div class="y-c-input-wrapper y-c-password-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="password_confirm" id="reg_password_confirm" autocomplete="new-password" placeholder="أعد إدخال كلمة المرور" required minlength="8" />
                            <button type="button" class="y-c-toggle-password" onclick="togglePassword('reg_password_confirm', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <div class="y-c-password-match" id="password-match"></div>
                    </div>

                    <?php do_action('woocommerce_register_form'); ?>

                    <p class="y-c-privacy-text">
                        <?php echo wp_kses_post(wc_get_privacy_policy_text('registration')); ?>
                    </p>

                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    
                    <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit y-c-submit-btn y-c-register-btn" name="register" value="<?php esc_attr_e('Register', 'woocommerce'); ?>" id="register-btn">
                        <span>إنشاء حساب</span>
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>

                    <?php do_action('woocommerce_register_form_end'); ?>

                </form>

                <!-- Link to Login -->
                <div class="y-c-auth-footer">
                    <p>لديك حساب بالفعل؟</p>
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" class="y-c-auth-link">
                        <span>تسجيل الدخول</span>
                        <i class="fa-solid fa-right-to-bracket"></i>
                    </a>
                </div>

            </div>

        </div>

    </div>
</div>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Password validation
document.addEventListener('DOMContentLoaded', function() {
    const password = document.getElementById('reg_password');
    const confirmPassword = document.getElementById('reg_password_confirm');
    const strengthIndicator = document.getElementById('password-strength');
    const matchIndicator = document.getElementById('password-match');
    const form = document.querySelector('.woocommerce-form-register');

    // Check password strength
    function checkPasswordStrength(pass) {
        let strength = 0;
        if (pass.length >= 8) strength++;
        if (pass.length >= 12) strength++;
        if (/[a-z]/.test(pass)) strength++;
        if (/[A-Z]/.test(pass)) strength++;
        if (/[0-9]/.test(pass)) strength++;
        if (/[^a-zA-Z0-9]/.test(pass)) strength++;
        return strength;
    }

    function updateStrengthIndicator() {
        const pass = password.value;
        if (!pass) {
            strengthIndicator.innerHTML = '';
            return;
        }

        const strength = checkPasswordStrength(pass);
        let text, className;

        if (strength <= 2) {
            text = '<i class="fa-solid fa-times-circle"></i> كلمة المرور ضعيفة';
            className = 'y-c-strength-weak';
        } else if (strength <= 4) {
            text = '<i class="fa-solid fa-exclamation-circle"></i> كلمة المرور متوسطة';
            className = 'y-c-strength-medium';
        } else {
            text = '<i class="fa-solid fa-check-circle"></i> كلمة المرور قوية';
            className = 'y-c-strength-strong';
        }

        strengthIndicator.innerHTML = text;
        strengthIndicator.className = 'y-c-password-strength ' + className;
    }

    function checkPasswordMatch() {
        const pass = password.value;
        const confirm = confirmPassword.value;

        if (!confirm) {
            matchIndicator.innerHTML = '';
            return false;
        }

        if (pass === confirm) {
            matchIndicator.innerHTML = '<i class="fa-solid fa-check-circle"></i> كلمات المرور متطابقة';
            matchIndicator.className = 'y-c-password-match y-c-match-success';
            return true;
        } else {
            matchIndicator.innerHTML = '<i class="fa-solid fa-times-circle"></i> كلمات المرور غير متطابقة';
            matchIndicator.className = 'y-c-password-match y-c-match-error';
            return false;
        }
    }

    password.addEventListener('input', function() {
        updateStrengthIndicator();
        if (confirmPassword.value) {
            checkPasswordMatch();
        }
    });

    confirmPassword.addEventListener('input', checkPasswordMatch);

    // Form validation before submit
    form.addEventListener('submit', function(e) {
        if (!checkPasswordMatch()) {
            e.preventDefault();
            alert('كلمات المرور غير متطابقة');
            return false;
        }

        const strength = checkPasswordStrength(password.value);
        if (strength <= 2) {
            e.preventDefault();
            alert('يرجى اختيار كلمة مرور أقوى');
            return false;
        }

        return true;
    });
});
</script>

<?php else : 
    // Show Login Form
?>

<div class="y-l-myaccount-login y-l-single-form">
    <div class="y-l-container">
        
        <!-- Page Header -->
        <div class="y-l-myaccount-header">
            <div class="y-c-myaccount-icon">
                <i class="fa-solid fa-right-to-bracket"></i>
            </div>
            <h1 class="y-c-myaccount-title">تسجيل الدخول</h1>
            <p class="y-c-myaccount-subtitle">أدخل بياناتك للوصول إلى حسابك</p>
        </div>

        <div class="y-l-single-form-wrapper">
            
            <!-- Login Form -->
            <div class="y-c-auth-card">
                
                <form class="woocommerce-form woocommerce-form-login login y-c-auth-form" method="post">

                    <?php do_action('woocommerce_login_form_start'); ?>

                    <div class="y-c-form-group">
                        <label for="username" class="y-c-form-label">
                            البريد الإلكتروني أو اسم المستخدم
                            <span class="y-c-required">*</span>
                        </label>
                        <div class="y-c-input-wrapper">
                            <i class="fa-solid fa-envelope"></i>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="username" id="username" autocomplete="username" placeholder="أدخل بريدك الإلكتروني" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required />
                        </div>
                    </div>

                    <div class="y-c-form-group">
                        <label for="password" class="y-c-form-label">
                            كلمة المرور
                            <span class="y-c-required">*</span>
                        </label>
                        <div class="y-c-input-wrapper y-c-password-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" type="password" name="password" id="password" autocomplete="current-password" placeholder="أدخل كلمة المرور" required />
                            <button type="button" class="y-c-toggle-password" onclick="togglePassword('password', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <?php do_action('woocommerce_login_form'); ?>

                    <div class="y-c-form-options">
                        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme y-c-remember-me">
                            <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                            <span>تذكرني</span>
                        </label>
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('lost-password')); ?>" class="y-c-forgot-password">
                            نسيت كلمة المرور؟
                        </a>
                    </div>

                    <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                    
                    <button type="submit" class="woocommerce-button button woocommerce-form-login__submit y-c-submit-btn" name="login" value="<?php esc_attr_e('Log in', 'woocommerce'); ?>">
                        <span>تسجيل الدخول</span>
                        <i class="fa-solid fa-arrow-left"></i>
                    </button>

                    <?php do_action('woocommerce_login_form_end'); ?>

                </form>

                <!-- Link to Register -->
                <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
                <div class="y-c-auth-footer">
                    <p>ليس لديك حساب؟</p>
                    <a href="<?php echo esc_url(add_query_arg('action', 'register', wc_get_page_permalink('myaccount'))); ?>" class="y-c-auth-link">
                        <span>إنشاء حساب جديد</span>
                        <i class="fa-solid fa-user-plus"></i>
                    </a>
                </div>
                <?php endif; ?>

            </div>

        </div>

    </div>
</div>

<script>
function togglePassword(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>

<?php endif; ?>

<?php do_action('woocommerce_after_customer_login_form'); ?>
