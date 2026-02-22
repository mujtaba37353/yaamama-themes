<?php
/**
 * Reset password form - Custom Template
 *
 * @package MyCarTheme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_reset_password_form');
?>

<div class="y-l-myaccount-login y-l-single-form">
    <div class="y-l-container">
        
        <!-- Page Header -->
        <div class="y-l-myaccount-header">
            <div class="y-c-myaccount-icon y-c-icon-reset">
                <i class="fa-solid fa-lock-open"></i>
            </div>
            <h1 class="y-c-myaccount-title">إنشاء كلمة مرور جديدة</h1>
            <p class="y-c-myaccount-subtitle">أدخل كلمة المرور الجديدة لحسابك</p>
        </div>

        <div class="y-l-single-form-wrapper">
            
            <div class="y-c-auth-card">
                
                <form method="post" class="woocommerce-ResetPassword lost_reset_password y-c-auth-form">

                    <div class="y-c-form-group">
                        <label for="password_1" class="y-c-form-label">
                            كلمة المرور الجديدة
                            <span class="y-c-required">*</span>
                        </label>
                        <div class="y-c-input-wrapper y-c-password-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="password_1" id="password_1" autocomplete="new-password" placeholder="أدخل كلمة المرور الجديدة" required minlength="8" />
                            <button type="button" class="y-c-toggle-password" onclick="togglePassword('password_1', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <div class="y-c-password-strength" id="password-strength"></div>
                    </div>

                    <div class="y-c-form-group">
                        <label for="password_2" class="y-c-form-label">
                            تأكيد كلمة المرور الجديدة
                            <span class="y-c-required">*</span>
                        </label>
                        <div class="y-c-input-wrapper y-c-password-wrapper">
                            <i class="fa-solid fa-lock"></i>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text y-c-form-input" name="password_2" id="password_2" autocomplete="new-password" placeholder="أعد إدخال كلمة المرور الجديدة" required minlength="8" />
                            <button type="button" class="y-c-toggle-password" onclick="togglePassword('password_2', this)">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <div class="y-c-password-match" id="password-match"></div>
                    </div>

                    <input type="hidden" name="reset_key" value="<?php echo esc_attr($args['key']); ?>" />
                    <input type="hidden" name="reset_login" value="<?php echo esc_attr($args['login']); ?>" />

                    <div class="clear"></div>

                    <?php do_action('woocommerce_resetpassword_form'); ?>

                    <button type="submit" class="woocommerce-Button button y-c-submit-btn y-c-register-btn" value="<?php esc_attr_e('Save', 'woocommerce'); ?>" id="reset-btn">
                        <span>حفظ كلمة المرور</span>
                        <i class="fa-solid fa-check"></i>
                    </button>

                    <?php wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce'); ?>

                </form>

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
    const password = document.getElementById('password_1');
    const confirmPassword = document.getElementById('password_2');
    const strengthIndicator = document.getElementById('password-strength');
    const matchIndicator = document.getElementById('password-match');
    const form = document.querySelector('.woocommerce-ResetPassword');

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

<?php do_action('woocommerce_after_reset_password_form'); ?>
