/**
 * Booking Form Handler — AJAX submission + validation
 */

(() => {
	const form = document.querySelector('.profile-section');
	if (!form) return;

	let selectedService = '';
	let selectedDate = '';
	let selectedTime = '';
	let customerName = '';
	let customerPhone = '';
	let createAccount = false;
	let accountEmail = '';
	let accountPassword = '';

	// Service selection
	const serviceList = form.querySelector('.custome-list');
	const dateTabButton = form.querySelector('.tabs button[data-tab="date"]');
	const serviceItems = form.querySelectorAll('.custome-list .item');
	const serviceLocked = serviceList?.dataset?.serviceLocked === '1';
	if (serviceLocked) {
		selectedService = serviceList?.dataset?.selectedService || '';
		const selectedText = form.querySelector('.selected-service-text');
		if (selectedText && selectedService) {
			selectedText.textContent = selectedService;
		}
		const autoAdvance = () => {
			const nextBtn = form.querySelector('.tab-next[data-next="date"]');
			if (nextBtn) {
				nextBtn.click();
				return;
			}
			if (dateTabButton) {
				dateTabButton.click();
			}
		};
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', () => setTimeout(autoAdvance, 50));
		} else {
			setTimeout(autoAdvance, 50);
		}
	} else {
		serviceItems.forEach((item) => {
			item.addEventListener('click', () => {
				selectedService = item.getAttribute('data-name') || '';
				const selectedText = form.querySelector('.selected-service-text');
				if (selectedText) {
					selectedText.textContent = selectedService;
				}
				// Close dropdown
				const toggle = form.querySelector('#service-list-toggle');
				if (toggle) toggle.checked = false;
			});
		});
	}

	// Date/Time picker integration — read from data attributes set by date-time-picker.js
	const dateTimeWrapper = form.querySelector('.date-time-wrapper');
	const timeInput = form.querySelector('.time-input');
	if (dateTimeWrapper) {
		// Listen for attribute changes
		const observer = new MutationObserver(() => {
			const dateAttr = dateTimeWrapper.getAttribute('data-selected-date');
			const timeAttr = dateTimeWrapper.getAttribute('data-selected-time');
			if (dateAttr) selectedDate = dateAttr;
			if (timeAttr) {
				selectedTime = timeAttr;
			} else {
				selectedTime = '';
			}
		});
		observer.observe(dateTimeWrapper, { attributes: true, attributeFilter: ['data-selected-date', 'data-selected-time'] });
		
		// Listen for time slot clicks (supports dynamic slots)
		dateTimeWrapper.addEventListener('click', (event) => {
			const slot = event.target.closest('.time-slot');
			if (slot) {
				selectedTime = slot.getAttribute('data-time') || '';
			}
		});
	}

	// Prevent next on date tab if time invalid
	const dateNextBtn = form.querySelector('.tab-next[data-next="info"]');
	if (dateNextBtn) {
		dateNextBtn.addEventListener('click', (event) => {
			const activeDateTab = form.querySelector('.tab-content[data-content="date"].active');
			if (!activeDateTab) return;

			const selected = dateTimeWrapper?.getAttribute('data-selected-time') || '';
			const errorEl = form.querySelector('[data-time-error]');
			const hasError = Boolean(errorEl && errorEl.textContent);
			if (!selected || hasError) {
				event.preventDefault();
				event.stopImmediatePropagation();
				if (errorEl && !errorEl.textContent) {
					errorEl.textContent = 'الوقت يجب أن يكون بين بداية الدوام ونهاية الدوام.';
				}
				if (timeInput) {
					timeInput.focus();
				}
			}
		}, true);
	}

	// Info form
	const nameInput = form.querySelector('#booking-name');
	const phoneInput = form.querySelector('#booking-phone');
	if (nameInput) {
		nameInput.addEventListener('input', (e) => {
			customerName = e.target.value;
		});
		customerName = nameInput.value || '';
	}
	if (phoneInput) {
		phoneInput.addEventListener('input', (e) => {
			customerPhone = e.target.value;
		});
		customerPhone = phoneInput.value || '';
	}

	// Create account toggle
	const createAccountToggle = form.querySelector('#booking-create-account');
	const createAccountFields = form.querySelector('[data-create-account-fields]');
	const emailInput = form.querySelector('#booking-email');
	const passwordInput = form.querySelector('#booking-password');
	if (createAccountToggle) {
		createAccount = Boolean(createAccountToggle.checked);
		if (createAccountFields) {
			createAccountFields.classList.toggle('is-active', createAccount);
		}
		createAccountToggle.addEventListener('change', (e) => {
			createAccount = Boolean(e.target.checked);
			if (createAccountFields) {
				createAccountFields.classList.toggle('is-active', createAccount);
			}
		});
	}
	if (emailInput) {
		emailInput.addEventListener('input', (e) => {
			accountEmail = e.target.value;
		});
		accountEmail = emailInput.value || '';
	}
	if (passwordInput) {
		passwordInput.addEventListener('input', (e) => {
			accountPassword = e.target.value;
		});
	}

	// Payment form toggle for dynamic gateways
	const paymentRadios = form.querySelectorAll('.payment-radio');
	const paymentForms = form.querySelectorAll('.payment-form');
	const paymentMethods = form.querySelector('.payment-methods');
	const updatePaymentForm = () => {
		const selectedRadio = form.querySelector('.payment-radio:checked');
		const selectedForm = selectedRadio?.dataset?.form;
		paymentForms.forEach((paymentForm) => {
			const isMatch = paymentForm.getAttribute('data-form') === selectedForm;
			paymentForm.classList.toggle('is-active', isMatch);
		});
		if (paymentMethods) {
			paymentMethods.classList.toggle('has-selection', Boolean(selectedRadio));
		}
	};
	paymentRadios.forEach((radio) => {
		radio.addEventListener('change', updatePaymentForm);
	});
	updatePaymentForm();

	// Payment submit
	const submitBtn = form.querySelector('.payment-submit-btn');
	if (submitBtn) {
		submitBtn.addEventListener('click', (e) => {
			e.preventDefault();

			// Get selected date/time from date-time-picker (read from data attributes)
			if (dateTimeWrapper) {
				selectedDate = dateTimeWrapper.getAttribute('data-selected-date') || '';
				selectedTime = dateTimeWrapper.getAttribute('data-selected-time') || '';
			}
			
			// Fallback: try to read from selected info text if data attributes not set
			if (!selectedTime) {
				const timeText = form.querySelector('.selected-time-text');
				if (timeText && timeText.textContent !== 'لم يتم اختيار وقت' && timeText.classList.contains('has-selection')) {
					// Extract time from text (format: "10:00 ص" or "02:00 م")
					const timeMatch = timeText.textContent.match(/(\d{1,2}):(\d{2})\s*(ص|م)/);
					if (timeMatch) {
						let hour = parseInt(timeMatch[1]);
						const minute = timeMatch[2];
						const period = timeMatch[3];
						if (period === 'م' && hour !== 12) hour += 12;
						if (period === 'ص' && hour === 12) hour = 0;
						selectedTime = `${String(hour).padStart(2, '0')}:${minute}`;
					}
				}
			}

			// Validation
			if (!selectedService) {
				alert('يرجى اختيار الخدمة');
				return;
			}
			if (!selectedDate) {
				alert('يرجى اختيار التاريخ');
				return;
			}
			if (!selectedTime) {
				alert('يرجى اختيار الوقت');
				return;
			}
			if (dateTimeWrapper) {
				const available = dateTimeWrapper.getAttribute('data-available-times') || '';
				if (available) {
					const list = available.split(',').map((value) => value.trim()).filter(Boolean);
					if (list.length && !list.includes(selectedTime)) {
						alert('الوقت المحدد غير متاح');
						return;
					}
				}
			}
			if (!customerName) {
				alert('يرجى إدخال الاسم');
				form.querySelector('[data-tab="info"]')?.click();
				return;
			}
			if (!customerPhone) {
				alert('يرجى إدخال رقم الهاتف');
				form.querySelector('[data-tab="info"]')?.click();
				return;
			}
			if (createAccount) {
				if (!accountEmail) {
					alert('يرجى إدخال البريد الإلكتروني');
					form.querySelector('[data-tab="info"]')?.click();
					return;
				}
				if (!accountPassword) {
					alert('يرجى إدخال كلمة المرور');
					form.querySelector('[data-tab="info"]')?.click();
					return;
				}
			}

			// Get payment method
			const paymentMethod = form.querySelector('input[name="payment-method"]:checked')?.value || 'cash';

			// Submit via AJAX
			const formData = new FormData();
			formData.append('action', 'beauty_create_booking');
			formData.append('nonce', beautyBooking.nonce);
			formData.append('customer_name', customerName);
			formData.append('phone', customerPhone);
			formData.append('services', selectedService);
			formData.append('date', selectedDate);
			formData.append('time', selectedTime);
			formData.append('payment_method', paymentMethod);
			formData.append('create_account', createAccount ? '1' : '0');
			formData.append('account_email', accountEmail);
			formData.append('account_password', accountPassword);

			submitBtn.disabled = true;
			submitBtn.textContent = 'جاري الإرسال...';

			fetch(beautyBooking.ajaxUrl, {
				method: 'POST',
				body: formData,
			})
				.then((response) => response.json())
				.then((data) => {
					if (data.success) {
						window.location.href = data.data.redirect_url;
					} else {
						alert(data.data.message || 'حدث خطأ أثناء إنشاء الحجز');
						submitBtn.disabled = false;
						submitBtn.textContent = 'إتمام الدفع';
					}
				})
				.catch((error) => {
					console.error('Error:', error);
					alert('حدث خطأ أثناء إنشاء الحجز');
					submitBtn.disabled = false;
					submitBtn.textContent = 'إتمام الدفع';
				});
		});
	}
})();
