(function () {
  if (typeof JustValidate === 'undefined') return;

  const createValidator = (formSelector) => {
    const form = document.querySelector(formSelector);
    if (!form) return null;

    return new JustValidate(form, {
      errorFieldCssClass: 'is-invalid',
      errorLabelCssClass: ['validation-error-label', 'error-message'],
      focusInvalidField: true,
      lockForm: true,
      validateOnInput: true,
      validateOnEdge: true,
    });
  };

  /* ================= LOGIN ================= */
  const loginValidator = createValidator('#login-form');
  if (loginValidator) {
    loginValidator
      .addField('[name="email"]', [
        { rule: 'required', errorMessage: 'البريد الإلكتروني أو اسم المستخدم مطلوب' },
        {
          rule: 'customRegexp',
          value: /^([A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}|[A-Za-z0-9._-]+)$/,
          errorMessage: 'أدخل بريدًا صحيحًا أو اسم مستخدم صالحًا',
        },
      ])
      .addField('[name="password"]', [
        { rule: 'required', errorMessage: 'كلمة المرور مطلوبة' },
        { rule: 'minLength', value: 6, errorMessage: 'كلمة المرور يجب أن تكون 6 أحرف على الأقل' },
      ])
      .onSuccess((event) => {
        event?.target?.submit();
      });
  }

  /* ================= SIGNUP ================= */
  const signupValidator = createValidator('#signup-form');
  if (signupValidator) {
    signupValidator
      .addField('[name="fullname"]', [
        { rule: 'required', errorMessage: 'الاسم الكامل مطلوب' },
        { rule: 'minLength', value: 3, errorMessage: 'الاسم يجب أن يكون 3 أحرف على الأقل' },
      ])
      .addField('[name="email"]', [
        { rule: 'required', errorMessage: 'البريد الإلكتروني مطلوب' },
        { rule: 'email', errorMessage: 'الرجاء إدخال بريد إلكتروني صحيح' },
      ])
      .addField('[name="phone"]', [
        { rule: 'required', errorMessage: 'رقم الجوال مطلوب' },
        { rule: 'minLength', value: 9, errorMessage: 'رقم الجوال غير صحيح' },
        { rule: 'maxLength', value: 15, errorMessage: 'رقم الجوال غير صحيح' },
      ])
      .addField('[name="password"]', [
        { rule: 'required', errorMessage: 'كلمة المرور مطلوبة' },
        { rule: 'minLength', value: 6, errorMessage: 'كلمة المرور يجب أن تكون 6 أحرف على الأقل' },
      ])
      .addField('[name="confirm-password"]', [
        { rule: 'required', errorMessage: 'تأكيد كلمة المرور مطلوب' },
        {
          validator: (value, fields) =>
            value === fields['[name="password"]']?.elem?.value,
          errorMessage: 'كلمتا المرور غير متطابقتين',
        },
      ])
      .onSuccess((event) => {
        event?.target?.submit();
      });
  }

  /* ================= FORGET PASSWORD ================= */
  const forgetValidator = createValidator('#forget-form');
  if (forgetValidator) {
    forgetValidator
      .addField('[name="email"]', [
        { rule: 'required', errorMessage: 'البريد الإلكتروني مطلوب' },
        { rule: 'email', errorMessage: 'الرجاء إدخال بريد إلكتروني صحيح' },
      ])
      .onSuccess((event) => {
        event?.target?.submit();
      });
  }

  /* ================= RESET PASSWORD ================= */
  const resetValidator = createValidator('#reset-password-form');
  if (resetValidator) {
    resetValidator
      .addField('[name="password"]', [
        { rule: 'required', errorMessage: 'كلمة المرور مطلوبة' },
        { rule: 'minLength', value: 6, errorMessage: 'كلمة المرور يجب أن تكون 6 أحرف على الأقل' },
      ])
      .addField('[name="confirm-password"]', [
        { rule: 'required', errorMessage: 'تأكيد كلمة المرور مطلوب' },
        {
          validator: (value, fields) =>
            value === fields['[name="password"]']?.elem?.value,
          errorMessage: 'كلمتا المرور غير متطابقتين',
        },
      ])
      .onSuccess((event) => {
        event?.target?.submit();
      });
  }

  /* ================= CONTACT ================= */
  const contactValidator = createValidator('#contact-form');
  if (contactValidator) {
    contactValidator
      .addField('[name="email"]', [
        { rule: 'required', errorMessage: 'البريد الإلكتروني مطلوب' },
        { rule: 'email', errorMessage: 'الرجاء إدخال بريد إلكتروني صحيح' },
      ])
      .addField('[name="name"]', [
        { rule: 'required', errorMessage: 'الاسم مطلوب' },
        { rule: 'minLength', value: 3, errorMessage: 'الاسم يجب أن يكون 3 أحرف على الأقل' },
      ])
      .addField('[name="subject"]', [
        { rule: 'required', errorMessage: 'عنوان الرسالة مطلوب' },
        { rule: 'minLength', value: 3, errorMessage: 'العنوان يجب أن تكون 3 أحرف على الأقل' },
      ])
      .addField('[name="message"]', [
        { rule: 'required', errorMessage: 'الرسالة مطلوبة' },
        { rule: 'minLength', value: 10, errorMessage: 'الرسالة يجب أن تكون 10 أحرف على الأقل' },
      ])
      .addField('[name="phone"]', [
        {
          rule: 'customRegexp',
          value: /^[0-9]*$/,
          errorMessage: 'رقم الهاتف يجب أن يحتوي على أرقام فقط',
        },
        {
          rule: 'customRegexp',
          value: /^05[0-9]*$/,
          errorMessage: 'رقم الهاتف يجب أن يبدأ ب 05',
        },
      ])
      .onSuccess((event) => {
        event?.target?.submit();
      });
  }
  /* ================= CUSTOMER INFO ================= */
  const paymentValidator = createValidator('#payment-customer-form');
  if (paymentValidator) {
    paymentValidator
      .addField('[name="cust-name"]', [
        { rule: 'required', errorMessage: 'الاسم الكامل مطلوب' },
        { rule: 'minLength', value: 3, errorMessage: 'الاسم يجب أن يكون 3 أحرف على الأقل' },
      ])
      .addField('[name="cust-email"]', [
        { rule: 'required', errorMessage: 'البريد الإلكتروني مطلوب' },
        { rule: 'email', errorMessage: 'الرجاء إدخال بريد إلكتروني صحيح' },
      ])
      .addField('[name="cust-phone"]', [
        { rule: 'required', errorMessage: 'رقم الجوال مطلوب' },
        {
          rule: 'customRegexp',
          value: /^(05\d{8}|\+9665\d{8}|009665\d{8}|9665\d{8})$/,
          errorMessage: 'يجب أن يبدأ بـ 05 أو +9665 أو 009665 أو 9665 متبوعة بـ 8 أرقام',
        },
      ])
      .addField('[name="cust-password"]', [
        { rule: 'required', errorMessage: 'كلمة المرور مطلوبة' },
        { rule: 'minLength', value: 6, errorMessage: '6 أحرف على الأقل' },
      ])
      .addField('[name="cust-confirm-password"]', [
        { rule: 'required', errorMessage: 'تأكيد كلمة المرور مطلوب' },
        {
          validator: (value, fields) => {
            const pass = fields['[name="cust-password"]']?.elem?.value;
            return value === pass;
          },
          errorMessage: 'كلمتا المرور غير متطابقتين',
        },
      ]);
  }

  /* ================= CREDIT CARD ================= */
  const creditCardValidator = createValidator('#credit-card-form');
  if (creditCardValidator) {
    creditCardValidator
      .addField('[name="credit-card-number"]', [
        { rule: 'required', errorMessage: 'رقم البطاقة مطلوب' },
        {
          rule: 'customRegexp',
          value: /^(\d{4}\s?){4}$|^\d{16}$/,
          errorMessage: 'رقم البطاقة غير صحيح',
        },
        { rule: 'maxLength', value: 19, errorMessage: 'لا يزيد عن 16 أرقام' },
        { rule: 'minLength', value: 19, errorMessage: 'يجب أن يكون 16 أرقام' },
      ])
      .addField('[name="credit-card-holder-name"]', [
        { rule: 'required', errorMessage: 'اسم حامل البطاقة مطلوب' },
        { rule: 'minLength', value: 3, errorMessage: 'الاسم يجب أن يكون 3 أحرف على الأقل' },
      ])
      .addField('[name="credit-card-cvv"]', [
        { rule: 'required', errorMessage: 'رقم CVV مطلوب' },
        { rule: 'customRegexp', value: /^\d{3}$/, errorMessage: '3 أرقام فقط' },
        { rule: 'maxLength', value: 3, errorMessage: '3 أرقام فقط' },
        { rule: 'minLength', value: 3, errorMessage: '3 أرقام فقط' },
      ])
      .addField('[name="credit-card-expiry-date"]', [
        { rule: 'required', errorMessage: 'تاريخ الانتهاء مطلوب' }

      ]);
  }

  /* ================= STC PAY ================= */
  const stcPayValidator = createValidator('#stc-pay-form');
  if (stcPayValidator) {
    stcPayValidator.addField('[name="stc-mobile"]', [
      { rule: 'required', errorMessage: 'رقم الجوال مطلوب' },
      {
        rule: 'customRegexp',
        value: /^(05\d{8}|\+9665\d{8}|009665\d{8}|9665\d{8})$/,
        errorMessage: 'يجب أن يبدأ بـ 05 أو +9665 أو 009665 أو 9665 متبوعة بـ 8 أرقام',
      },
    ]);
  }

  /* ================= FINAL COMPLETE BUTTON ================= */
  const completeBtn = document.getElementById('complete-order-btn');
  if (completeBtn) {
    completeBtn.addEventListener('click', async (e) => {
      e.preventDefault();

      // 1. Validate Customer form
      const isCustomerValid = await paymentValidator.revalidate();
      if (!isCustomerValid) return;

      // 2. Identify Active Payment Method and validate it
      const activeMethod = document.querySelector('.payment-option.active');
      let isPaymentValid = false;

      if (activeMethod.id === 'credit-card-option') {
        isPaymentValid = await creditCardValidator.revalidate();
      } else if (activeMethod.id === 'stc-pay-option') {
        isPaymentValid = await stcPayValidator.revalidate();
      }

      // 3. Final Step
      if (isPaymentValid) {
        window.location.href = '../thank-you/index.html';
      }
    });
  }
})();