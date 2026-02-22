fetch("../../components/auth/y-c-sign-up.html")
  .then((response) => response.text())
  .then((data) => {
    document.querySelector("[data-y='sign-up']").innerHTML = data;
    initValidation();
  });

function initValidation() {
  const validator = new JustValidate("#signup-form", {
    errorFieldCssClass: "is-invalid",
    errorLabelStyle: {
      color: "#dc3545",
      fontSize: "12px",
      marginTop: "5px",
    },
  });

  validator
    .addField("#email", [
      {
        rule: "required",
        errorMessage: "البريد الإلكتروني مطلوب",
      },
      {
        rule: "email",
        errorMessage: "يرجى إدخال بريد إلكتروني صحيح",
      },
    ])
    .addField("#phone", [
      {
        rule: "required",
        errorMessage: "رقم الجوال مطلوب",
      },
      {
        rule: "customRegexp",
        value: /^05\d{8}$/,
        errorMessage: "يجب أن يبدأ الرقم بـ 05 ويتكون من 10 أرقام",
      },
    ])
    .addField("#password", [
      {
        rule: "required",
        errorMessage: "كلمة المرور مطلوبة",
      },
      {
        rule: "minLength",
        value: 6,
        errorMessage: "كلمة المرور يجب أن تكون 6 أحرف على الأقل",
      },
    ])
    .addField("#confirm-password", [
      {
        rule: "required",
        errorMessage: "تأكيد كلمة المرور مطلوب",
      },
      {
        validator: (value, fields) => {
          return value === fields["#password"].elem.value;
        },
        errorMessage: "كلمة المرور غير متطابقة",
      },
    ])
    .onSuccess((event) => {
      // Handle form submission here
      console.log("Form is valid! Submitting...");
      // event.target.submit(); // Uncomment to actually submit
      window.location.href = "../../templates/home/layout.html"; // Redirect example
    });
}
