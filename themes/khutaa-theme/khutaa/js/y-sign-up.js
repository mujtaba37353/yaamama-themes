fetch("../../components/auth/y-c-sign-up.html")
    .then((response) => response.text())
    .then((data) => {
        document.querySelector("[data-y='sign-up']").innerHTML = data;
    });

// Toggle password visibility
function initPasswordToggle() {
    const toggleButtons = document.querySelectorAll('.toggle-password:not([data-initialized])');
    
    toggleButtons.forEach(button => {
        button.setAttribute('data-initialized', 'true');
        
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const wrapper = this.closest('.password-input-wrapper');
            const input = wrapper ? wrapper.querySelector('input[type="password"], input[type="text"]') : null;
            const icon = this.querySelector('i');
            
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.add('active');
                    if (icon) {
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    }
                } else {
                    input.type = 'password';
                    this.classList.remove('active');
                    if (icon) {
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                }
            }
        });
    });
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPasswordToggle);
} else {
    initPasswordToggle();
}