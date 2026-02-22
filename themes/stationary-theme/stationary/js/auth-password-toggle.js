/**
 * Password visibility toggle for auth forms.
 */
(function() {
	'use strict';
	document.addEventListener('DOMContentLoaded', function() {
		document.querySelectorAll('.password-toggle').forEach(function(btn) {
			btn.addEventListener('click', function() {
				var wrapper = this.closest('.password-input-wrapper');
				if (!wrapper) return;
				var input = wrapper.querySelector('input[type="password"], input[type="text"]');
				if (!input) return;
				var icon = this.querySelector('i');
				if (input.type === 'password') {
					input.type = 'text';
					if (icon) {
						icon.classList.remove('fa-eye');
						icon.classList.add('fa-eye-slash');
					}
				} else {
					input.type = 'password';
					if (icon) {
						icon.classList.remove('fa-eye-slash');
						icon.classList.add('fa-eye');
					}
				}
			});
		});
	});
})();
