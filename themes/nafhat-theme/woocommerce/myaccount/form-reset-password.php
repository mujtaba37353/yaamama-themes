<?php
/**
 * Reset password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-reset-password.php.
 *
 * @package Nafhat Theme
 */

defined('ABSPATH') || exit;

do_action('woocommerce_before_reset_password_form');
?>

<div class="auth-page">
    <div class="container">
        <div class="auth-form-wrapper reset-password-form">
            <div class="form-icon">
                <i class="fas fa-key"></i>
            </div>
            
            <h2><?php esc_html_e('إعادة تعيين كلمة المرور', 'nafhat'); ?></h2>
            
            <p class="form-description">
                <?php esc_html_e('أدخل كلمة المرور الجديدة أدناه.', 'nafhat'); ?>
            </p>

            <form method="post" class="woocommerce-ResetPassword lost_reset_password">

                <div class="form-group">
                    <label for="password_1"><?php esc_html_e('كلمة المرور الجديدة', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_1" id="password_1" autocomplete="new-password" placeholder="********" required />
                    <span class="password-strength-meter" id="password-strength"></span>
                </div>

                <div class="form-group">
                    <label for="password_2"><?php esc_html_e('تأكيد كلمة المرور الجديدة', 'nafhat'); ?>&nbsp;<span class="required">*</span></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password_2" id="password_2" autocomplete="new-password" placeholder="********" required />
                    <span class="password-match-message" id="password-match"></span>
                </div>

                <?php do_action('woocommerce_resetpassword_form'); ?>

                <input type="hidden" name="reset_key" value="<?php echo esc_attr($args['key']); ?>" />
                <input type="hidden" name="reset_login" value="<?php echo esc_attr($args['login']); ?>" />

                <button type="submit" class="woocommerce-Button button btn btn-primary" id="reset-submit-btn" value="<?php esc_attr_e('حفظ كلمة المرور', 'nafhat'); ?>">
                    <?php esc_html_e('حفظ كلمة المرور', 'nafhat'); ?>
                </button>

                <?php wp_nonce_field('reset_password', 'woocommerce-reset-password-nonce'); ?>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const password1 = document.getElementById('password_1');
    const password2 = document.getElementById('password_2');
    const passwordStrength = document.getElementById('password-strength');
    const passwordMatch = document.getElementById('password-match');
    const resetBtn = document.getElementById('reset-submit-btn');
    
    if (password1 && password2) {
        // Password strength checker
        password1.addEventListener('input', function() {
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
            if (password2.value) {
                checkPasswordMatch();
            }
        });
        
        // Password match checker
        password2.addEventListener('input', checkPasswordMatch);
        
        function checkPasswordMatch() {
            const pass1 = password1.value;
            const pass2 = password2.value;
            
            if (pass2.length === 0) {
                passwordMatch.textContent = '';
                return;
            }
            
            if (pass1 === pass2) {
                passwordMatch.textContent = 'كلمة المرور متطابقة ✓';
                passwordMatch.style.color = '#28a745';
                resetBtn.disabled = false;
            } else {
                passwordMatch.textContent = 'كلمة المرور غير متطابقة';
                passwordMatch.style.color = '#dc3545';
                resetBtn.disabled = true;
            }
        }
        
        // Form validation before submit
        const resetForm = document.querySelector('.woocommerce-ResetPassword');
        if (resetForm) {
            resetForm.addEventListener('submit', function(e) {
                const pass1 = password1.value;
                const pass2 = password2.value;
                
                if (pass1 !== pass2) {
                    e.preventDefault();
                    alert('كلمة المرور غير متطابقة. يرجى التأكد من تطابق كلمتي المرور.');
                    password2.focus();
                    return false;
                }
                
                if (pass1.length < 8) {
                    e.preventDefault();
                    alert('كلمة المرور يجب أن تكون 8 أحرف على الأقل.');
                    password1.focus();
                    return false;
                }
            });
        }
    }
});
</script>

<?php
do_action('woocommerce_after_reset_password_form');
