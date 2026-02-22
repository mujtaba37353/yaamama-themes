<?php
/**
 * Login Form
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_customer_login_form'); ?>

<div class="auth-page">
    <div class="container">
        <div class="auth-form-wrapper">
            <h2><?php esc_html_e('تسجيل الدخول', 'nafhat'); ?></h2>

            <form class="woocommerce-form woocommerce-form-login login" method="post">

                <?php do_action('woocommerce_login_form_start'); ?>

                <div class="form-group">
                    <label for="username"><?php esc_html_e('البريد الإلكتروني أو اسم المستخدم', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" placeholder="example@gmail.com" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required />
                </div>

                <div class="form-group">
                    <label for="password"><?php esc_html_e('كلمة المرور', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" placeholder="********" required />
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="forgot-password"><?php esc_html_e('نسيت كلمة المرور؟', 'nafhat'); ?></a>
                </div>

                <?php do_action('woocommerce_login_form'); ?>

                <div class="form-group">
                    <label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
                        <input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" />
                        <span><?php esc_html_e('تذكرني', 'nafhat'); ?></span>
                    </label>
                </div>

                <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                <button type="submit" class="woocommerce-button button woocommerce-form-login__submit btn btn-primary" name="login" value="<?php esc_attr_e('تسجيل الدخول', 'nafhat'); ?>"><?php esc_html_e('تسجيل الدخول', 'nafhat'); ?></button>

                <?php do_action('woocommerce_login_form_end'); ?>

            </form>

            <div class="separator"><span><?php esc_html_e('أو', 'nafhat'); ?></span></div>

            <div class="switch-auth">
                <p><?php esc_html_e('ليس لديك حساب؟', 'nafhat'); ?> <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>?action=register"><?php esc_html_e('إنشاء حساب', 'nafhat'); ?></a></p>
            </div>
        </div>

        <?php if ('yes' === get_option('woocommerce_enable_myaccount_registration')) : ?>
        <div class="auth-form-wrapper" id="register-form" style="display: none;">
            <h2><?php esc_html_e('إنشاء حساب', 'nafhat'); ?></h2>

            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>

                <?php do_action('woocommerce_register_form_start'); ?>

                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                    <div class="form-group">
                        <label for="reg_username"><?php esc_html_e('اسم المستخدم', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" placeholder="اسم المستخدم" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" required />
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="reg_email"><?php esc_html_e('البريد الإلكتروني', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" placeholder="example@gmail.com" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" required />
                </div>

                <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                    <div class="form-group">
                        <label for="reg_password"><?php esc_html_e('كلمة المرور', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" placeholder="********" required />
                        <span class="password-strength-meter" id="password-strength"></span>
                    </div>
                    
                    <div class="form-group">
                        <label for="reg_password_confirm"><?php esc_html_e('تأكيد كلمة المرور', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
                        <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" id="reg_password_confirm" autocomplete="new-password" placeholder="********" required />
                        <span class="password-match-message" id="password-match"></span>
                    </div>
                <?php else : ?>
                    <p><?php esc_html_e('سيتم إرسال رابط لتعيين كلمة مرور جديدة إلى عنوان بريدك الإلكتروني.', 'nafhat'); ?></p>
                <?php endif; ?>

                <p class="privacy-policy-text">
                    <?php esc_html_e('سيتم استخدام بياناتك الشخصية لدعم تجربتك في هذا الموقع، وإدارة الوصول إلى حسابك، ولأغراض أخرى موضحة في', 'nafhat'); ?>
                    <a href="<?php echo esc_url(get_privacy_policy_url()); ?>" class="woocommerce-privacy-policy-link" target="_blank"><?php esc_html_e('سياسة الخصوصية', 'nafhat'); ?></a>.
                </p>

                <?php do_action('woocommerce_register_form'); ?>

                <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit btn btn-primary" name="register" id="register-submit-btn" value="<?php esc_attr_e('إنشاء حساب', 'nafhat'); ?>"><?php esc_html_e('إنشاء حساب', 'nafhat'); ?></button>

                <?php do_action('woocommerce_register_form_end'); ?>

            </form>

            <div class="separator"><span><?php esc_html_e('أو', 'nafhat'); ?></span></div>

            <div class="switch-auth">
                <p><?php esc_html_e('لديك حساب بالفعل؟', 'nafhat'); ?> <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('تسجيل الدخول', 'nafhat'); ?></a></p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle register form display
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'register') {
        const loginForm = document.querySelector('.auth-form-wrapper:not(#register-form)');
        const registerForm = document.getElementById('register-form');
        if (loginForm && registerForm) {
            loginForm.style.display = 'none';
            registerForm.style.display = 'block';
        }
    }
    
    // Password validation
    const regPassword = document.getElementById('reg_password');
    const regPasswordConfirm = document.getElementById('reg_password_confirm');
    const passwordStrength = document.getElementById('password-strength');
    const passwordMatch = document.getElementById('password-match');
    const registerBtn = document.getElementById('register-submit-btn');
    
    if (regPassword && regPasswordConfirm) {
        // Password strength checker
        regPassword.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;
            let message = '';
            let color = '';
            
            if (password.length >= 8) strength++;
            if (password.length >= 12) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;
            
            if (password.length === 0) {
                message = '';
            } else if (strength < 3) {
                message = 'ضعيفة';
                color = '#dc3545';
            } else if (strength < 5) {
                message = 'متوسطة';
                color = '#ffc107';
            } else {
                message = 'قوية';
                color = '#28a745';
            }
            
            passwordStrength.textContent = message;
            passwordStrength.style.color = color;
            
            // Check match if confirm field has value
            if (regPasswordConfirm.value) {
                checkPasswordMatch();
            }
        });
        
        // Password match checker
        regPasswordConfirm.addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const password = regPassword.value;
            const confirmPassword = regPasswordConfirm.value;
            
            if (confirmPassword.length === 0) {
                passwordMatch.textContent = '';
                return;
            }
            
            if (password === confirmPassword) {
                passwordMatch.textContent = 'كلمة المرور متطابقة ✓';
                passwordMatch.style.color = '#28a745';
                registerBtn.disabled = false;
            } else {
                passwordMatch.textContent = 'كلمة المرور غير متطابقة';
                passwordMatch.style.color = '#dc3545';
                registerBtn.disabled = true;
            }
        }
        
        // Form validation before submit
        const registerForm = document.querySelector('.woocommerce-form-register');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                const password = regPassword.value;
                const confirmPassword = regPasswordConfirm.value;
                
                if (password !== confirmPassword) {
                    e.preventDefault();
                    alert('كلمة المرور غير متطابقة. يرجى التأكد من تطابق كلمتي المرور.');
                    regPasswordConfirm.focus();
                    return false;
                }
                
                if (password.length < 8) {
                    e.preventDefault();
                    alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل.');
                    regPassword.focus();
                    return false;
                }
            });
        }
    }
});
</script>

<?php do_action('woocommerce_after_customer_login_form'); ?>
