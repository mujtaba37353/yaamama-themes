/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./assets/blocks/src/js/forms/applepay.js":
/*!************************************************!*\
  !*** ./assets/blocks/src/js/forms/applepay.js ***!
  \************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/html-entities */ "@wordpress/html-entities");
/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__);



const ApplePayContent = props => {
  /**
   * Props
   */
  const {
    eventRegistration,
    emitResponse,
    setting,
    onSubmit,
    billing
  } = props;
  let data = (0,_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__.decodeEntities)(setting.description || '');

  /**
   * @description Define the events
   */
  const {
    onPaymentSetup,
    onCheckoutSuccess,
    onCheckoutFail
  } = eventRegistration;

  /**
   * @description To save the payment token
   * @type {null}
   */
  let paymentToken = null;

  /**
   * @description Total Checkout Amount
   * @type {React.MutableRefObject<number>}
   */
  const totalPrice = (0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)(0);

  /**
   * @description Payment Status
   */
  const [paymentStatus, setPaymentStatus] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)({
    status: '',
    message: ''
  });

  /**
   * @description Detect the total price change
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    totalPrice.current = billing.cartTotal.value;
  }, [billing.cartTotal.value]);

  /**
   * @description Handle Apple Pay Button Clicked
   */
  function handleApplePayButtonClicked() {
    setPaymentStatus({
      status: '',
      message: ''
    });
    const request = new MoyasarRequest(setting.baseUrl, setting.publishableKey, setting.id);
    const currency = billing.currency.code;
    if (!ApplePaySession) {
      return;
    }
    MoyasarAppleHelper.startSession(setting.countryCode, currency, setting.supportedNetworks, setting.supportedCountries, setting.storeName, `${request.getFinalPrice(totalPrice.current)}`);
    MoyasarAppleHelper.appleSession.oncancel = event => {
      MoyasarAppleHelper.appleSession.abort();
    };
    MoyasarAppleHelper.appleSession.abort = event => {};

    // Validating Merchant
    MoyasarAppleHelper.appleSession.onvalidatemerchant = async event => {
      const body = {
        "validation_url": event.validationURL,
        "display_name": setting.storeName,
        "domain_name": window.location.hostname,
        "publishable_api_key": setting.publishableKey
      };
      request.initiateApplePay(body).then(merchantSession => {
        MoyasarAppleHelper.appleSession.completeMerchantValidation(merchantSession);
      }).catch(error => {
        MoyasarAppleHelper.appleSession.completeMerchantValidation({});
        MoyasarAppleHelper.appleSession.abort();
        setPaymentStatus({
          status: 'failed',
          message: error?.message || 'An error occurred'
        });
      });
    };
    MoyasarAppleHelper.appleSession.onpaymentauthorized = event => {
      paymentToken = event.payment.token;
      onSubmit();
    };
    MoyasarAppleHelper.appleSession.begin();
  }

  /**
   * @description Set Apple Pay Button on component mount & remove on unmount
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    MoyasarAppleHelper.setApplePayButton(handleApplePayButtonClicked);
    return () => {
      // Remove the Apple Pay button
      MoyasarAppleHelper.resetPlaceOrderButton();
    };
  }, []);

  /**
   * @description Attach the payment token (apple pay) to the form before submitting
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onPaymentSetup(async () => {
      return {
        type: emitResponse.responseTypes.SUCCESS,
        meta: {
          paymentMethodData: {
            'mysr_token': JSON.stringify(paymentToken),
            'moyasar-ap-nonce-field': moyasar_notice.value
          }
        }
      };
    });
    // Unsubscribes when this component is unmounted.
    return () => {
      unsubscribe();
    };
  }, [emitResponse.responseTypes.ERROR, emitResponse.responseTypes.SUCCESS, onPaymentSetup]);

  /**
   * @description Checkout Success
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onCheckoutSuccess(async data => {
      const response = data.processingResponse.paymentDetails;
      MoyasarAppleHelper.appleSession.completePayment({
        status: MoyasarAppleHelper.appleSession.STATUS_SUCCESS
      });
      return {
        type: emitResponse.responseTypes.SUCCESS,
        message: ''
      };
    });
    return () => {
      unsubscribe();
    };
  }, [onCheckoutSuccess, emitResponse.responseTypes.ERROR]);

  /**
   * @description Checkout Fail
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onCheckoutFail(async data => {
      const response = data.processingResponse.paymentDetails;
      MoyasarAppleHelper.appleSession.completeMerchantValidation({});
      MoyasarAppleHelper.appleSession.abort();
      return {
        type: emitResponse.responseTypes.ERROR,
        message: ''
      };
    });
    return () => {
      unsubscribe();
    };
  }, [onCheckoutFail, emitResponse.responseTypes.ERROR]);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: ""
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: ""
  }, paymentStatus.message && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `${paymentStatus.status === 'success' ? 'woocommerce-message' : 'woocommerce-error'}`
  }, paymentStatus.message), data, data));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (ApplePayContent);

/***/ }),

/***/ "./assets/blocks/src/js/forms/creditcard.js":
/*!**************************************************!*\
  !*** ./assets/blocks/src/js/forms/creditcard.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/html-entities */ "@wordpress/html-entities");
/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__);



const __mysr_cc = wp.i18n.__;
const CreditCardContent = props => {
  /**
   * Props
   */
  const {
    setting,
    eventRegistration,
    emitResponse,
    billing
  } = props;

  /**
   * @description Define the events
   */
  const {
    onPaymentSetup,
    onCheckoutSuccess,
    onCheckoutFail
  } = eventRegistration;
  let data = (0,_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__.decodeEntities)(setting.description || '');

  /**
   * @description Form Ref
   */
  const [errors, setErrors] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)({});
  const [formData, setFormData] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)({
    name: '',
    number: '',
    expiry: '',
    cvc: ''
  });
  const form = (0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)(formData);

  /**
   * @description Detect the form data change
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    form.current = formData;
  }, [formData]); // Update the ref every time myState changes

  /**
   * @description Setup Payment and get card token before submitting the form
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onPaymentSetup(async () => {
      const payload = form.current;
      const submitCard = {};
      // Loop through fields
      for (const field in payload) {
        let validationResult = validateField(field, payload[field]);
        if (validationResult.isValid === false) {
          return {
            type: emitResponse.responseTypes.ERROR,
            message: validationResult.message
          };
        }
        if (field === 'expiry') {
          submitCard['month'] = validationResult.submitValue.substring(0, 2);
          submitCard['year'] = validationResult.submitValue.substring(2);
        } else {
          submitCard[field] = validationResult.submitValue;
        }
      }
      const request = new MoyasarRequest(setting.baseUrl, setting.publishableKey, setting.id);
      try {
        let init = await request.initiateCreditCard(submitCard);
        return {
          type: emitResponse.responseTypes.SUCCESS,
          meta: {
            paymentMethodData: {
              'mysr_token': init.id,
              'mysr_form': 'blocks',
              'moyasar-cc-nonce-field': moyasar_notice.value
            }
          }
        };
      } catch (error) {
        let message = '';
        if (error.type === 'authentication_error' || error.type === 'account_inactive_error') {
          message = error.message;
        } else {
          const fields = error.errors;
          // Get First field
          message = fields[Object.keys(fields)[0]][0];
        }
        return {
          type: emitResponse.responseTypes.ERROR,
          message: message
        };
      }
    });
    // Unsubscribes when this component is unmounted.
    return () => {
      unsubscribe();
    };
  }, [emitResponse.responseTypes.ERROR, emitResponse.responseTypes.SUCCESS, onPaymentSetup]);

  /**
   * @description Checkout Success, start the 3D Secure process (Modal)
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onCheckoutSuccess(async data => {
      const response = data.processingResponse.paymentDetails;
      const iframe = jQuery('#mysr-3d-secure-iframe');
      if (!response.transactionUrl) {
        return {
          type: emitResponse.responseTypes.SUCCESS,
          message: ''
        };
      }
      iframe.attr('src', response.transactionUrl);
      await popupManager();
      return {
        type: emitResponse.responseTypes.SUCCESS,
        message: ''
      };
    });
    return () => {
      unsubscribe();
    };
  }, [onCheckoutSuccess, emitResponse.responseTypes.SUCCESS]);

  /**
   * @description Checkout Fail
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onCheckoutFail(async data => {
      const errorMsg = data?.processingResponse?.paymentDetails?.message || 'Something went wrong';
      console.error('[Moyasar error]', errorMsg);
      return {
        type: emitResponse.responseTypes.ERROR,
        message: data.message
      };
    });
    return () => {
      unsubscribe();
    };
  }, [onCheckoutFail, emitResponse.responseTypes.ERROR]);

  /**
   * @description Popup Manager
   * @returns {Promise<unknown>}
   */
  const popupManager = () => {
    return new Promise(async (resolve, reject) => {
      await showModal();
      resolve(true);
    });
  };

  /**
   * @description Show Modal
   * @returns {Promise<unknown>}
   */
  const showModal = () => {
    return new Promise((resolve, reject) => {
      const modal = document.getElementById('mysr-modal');
      const iframe = document.getElementById('mysr-3d-secure-iframe');
      modal.style.display = "block";
      const interval = setInterval(() => {
        try {
          let location = iframe.contentWindow?.location;
          let href = location?.href;
          let isSameHost = location?.host === window.location.host;
          if (href && href.includes('mysr_checkout') && isSameHost) {
            clearInterval(interval);
            modal.style.display = "none";
            resolve(true);
          }
        } catch (e) {}
        //     TODO: Timeout
      }, 50);
      // Click outside the modal, dimis and redirect
      jQuery(document.body).on('click', function (e) {
        if (e.target.id === 'mysr-modal') {
          clearInterval(interval);
          modal.style.display = "none";
          resolve(false);
        }
      });
    });
  };

  /**
   * @description Validate Field
   */
  const validateField = (field, value, showError = true) => {
    let result = {};
    if (field === 'name') {
      result = MoyasarValidation.cardName(value);
    } else if (field === 'number') {
      result = MoyasarValidation.cardNumber(value);
    } else if (field === 'expiry') {
      result = MoyasarValidation.cardExpiry(value);
    } else if (field === 'cvc') {
      result = MoyasarValidation.cvc(value);
    } else {
      return {
        isValid: false,
        showValue: ''
      };
    }
    if (result.isValid === false && showError) {
      setErrors({
        ...errors,
        field: result.message
      });
    } else {
      setErrors({});
    }
    return result;
  };

  /**
   * @description Handle Change
   * @param e
   */
  const handleChange = e => {
    let {
      name,
      value
    } = e.target;
    let result = validateField(name, value, false);
    value = result.showValue;
    setFormData({
      ...formData,
      [name]: value
    });
  };

  /**
   * @description Set submit button
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    // MoyasarTriggers.setMoyasarSubmitButton(`${(billing.cartTotal.value / 100).toFixed(2)}`);
    return () => {
      // Remove the submit button
      // MoyasarTriggers.resetPlaceOrderButton();
    };
  }, []);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    id: "mysr-modal",
    className: "mysr-modal"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "mysr-modal-content"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("iframe", {
    id: "mysr-3d-secure-iframe",
    src: "about:blank",
    frameBorder: "0",
    className: "moyasar-iframe",
    height: "100%",
    width: "100%"
  }))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, data, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "wc-block-components-text-input is-active"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    id: "card-name",
    "aria-label": "Card Name",
    required: true,
    "aria-invalid": "false",
    title: "",
    placeholder: __mysr_cc("Name on card", 'moyasar'),
    name: "name",
    value: formData.name,
    onChange: handleChange
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    htmlFor: "card-name"
  }, __mysr_cc('Card Name', 'moyasar'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "wc-block-components-text-input is-active"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    id: "card-number",
    "aria-label": "Card Number",
    required: true,
    "aria-invalid": "false",
    title: "",
    name: "number",
    className: "moyasar-number",
    value: formData.number,
    onChange: handleChange,
    placeholder: "1234 5678 9101 1121"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    htmlFor: "card-number"
  }, __mysr_cc('Card Number', 'moyasar'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "wc-block-components-text-input is-active"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    id: "card-exp",
    "aria-label": "Expiration",
    required: true,
    "aria-invalid": "false",
    title: "",
    name: "expiry",
    className: "moyasar-number",
    value: formData.expiry,
    onChange: handleChange,
    placeholder: "MM / YY"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    htmlFor: "card-exp"
  }, __mysr_cc('Expiration', 'moyasar'))), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "wc-block-components-text-input is-active"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    id: "card-cvc",
    "aria-label": "cvc",
    required: true,
    "aria-invalid": "false",
    title: "",
    name: "cvc",
    value: formData.cvc,
    className: "moyasar-number",
    onChange: handleChange,
    placeholder: "cvc"
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    htmlFor: "card-cvc"
  }, "CVC"))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (CreditCardContent);

/***/ }),

/***/ "./assets/blocks/src/js/forms/samsungpay.js":
/*!**************************************************!*\
  !*** ./assets/blocks/src/js/forms/samsungpay.js ***!
  \**************************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/html-entities */ "@wordpress/html-entities");
/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__);



const SamsungPayContent = props => {
  /**
   * Props
   */
  const {
    eventRegistration,
    emitResponse,
    setting,
    onSubmit,
    billing
  } = props;
  let data = (0,_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__.decodeEntities)(setting.description || '');

  /**
   * @description Define the events
   */
  const {
    onPaymentSetup,
    onCheckoutSuccess,
    onCheckoutFail
  } = eventRegistration;

  /**
   * @description To save the payment token
   * @type {null}
   */
  let paymentToken = null;

  /**
   * @description Total Checkout Amount
   * @type {React.MutableRefObject<number>}
   */
  const totalPrice = (0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)(0);

  /**
   * @description Payment Status
   */
  const [paymentStatus, setPaymentStatus] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)({
    status: '',
    message: ''
  });

  /**
   * @description Detect the total price change
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    totalPrice.current = billing.cartTotal.value;
  }, [billing.cartTotal.value]);

  /**
   * @description Handle Samsung Pay Button Clicked
   */
  function handleSamsungPayButtonClicked() {
    setPaymentStatus({
      status: '',
      message: ''
    });
    const request = new MoyasarRequest(setting.baseUrl, setting.publishableKey, setting.id);
    const transactionDetail = {
      orderNumber: 'ORDER-' + new Date().getTime(),
      merchant: {
        name: setting.storeName,
        countryCode: setting.countryCode,
        url: window.location.hostname
      },
      amount: {
        option: 'FORMAT_TOTAL_PRICE_ONLY',
        currency: setting.storeCurrency,
        total: `${request.getFinalPrice(totalPrice.current)}`
      }
    };
    MoyasarSamsungHelper.SamsungPaySession.loadPaymentSheet(MoyasarSamsungHelper.samsungPaymentMethods, transactionDetail).then(credentials => {
      paymentToken = credentials['3DS']['data'];
      onSubmit();
    }).catch(function (err) {
      console.log(err);
      // The user canceled or something else failed
      MoyasarSamsungHelper.SamsungPaySession.notify({
        status: 'CANCELED',
        provider: 'Moyasar'
      });
    });
  }

  /**
   * @description Set Samsung Pay Button on component mount & remove on unmount
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    MoyasarSamsungHelper.setSamsungPayButton(handleSamsungPayButtonClicked);
    return () => {
      // Remove the Samsung Pay button
      MoyasarSamsungHelper.resetPlaceOrderButton();
    };
  }, []);

  /**
   * @description Attach the payment token (samsung pay) to the form before submitting
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onPaymentSetup(async () => {
      return {
        type: emitResponse.responseTypes.SUCCESS,
        meta: {
          paymentMethodData: {
            'mysr_token': paymentToken,
            'moyasar-sp-nonce-field': moyasar_notice.value
          }
        }
      };
    });
    // Unsubscribes when this component is unmounted.
    return () => {
      unsubscribe();
    };
  }, [emitResponse.responseTypes.ERROR, emitResponse.responseTypes.SUCCESS, onPaymentSetup]);

  /**
   * @description Checkout Success
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onCheckoutSuccess(async data => {
      const response = data.processingResponse.paymentDetails;
      MoyasarSamsungHelper.SamsungPaySession.notify({
        status: 'CHARGED',
        provider: 'Moyasar'
      });
      return {
        type: emitResponse.responseTypes.SUCCESS,
        message: ''
      };
    });
    return () => {
      unsubscribe();
    };
  }, [onCheckoutSuccess, emitResponse.responseTypes.ERROR]);

  /**
   * @description Checkout Fail
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onCheckoutFail(async data => {
      const response = data.processingResponse.paymentDetails;
      MoyasarSamsungHelper.SamsungPaySession.notify({
        status: 'ERRED',
        provider: 'Moyasar'
      });
      return {
        type: emitResponse.responseTypes.ERROR,
        message: ''
      };
    });
    return () => {
      unsubscribe();
    };
  }, [onCheckoutFail, emitResponse.responseTypes.ERROR]);
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: ""
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: ""
  }, paymentStatus.message && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `${paymentStatus.status === 'success' ? 'woocommerce-message' : 'woocommerce-error'}`
  }, paymentStatus.message), data));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (SamsungPayContent);

/***/ }),

/***/ "./assets/blocks/src/js/forms/stcpay.js":
/*!**********************************************!*\
  !*** ./assets/blocks/src/js/forms/stcpay.js ***!
  \**********************************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! @wordpress/html-entities */ "@wordpress/html-entities");
/* harmony import */ var _wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__);



const __mysr_sp = wp.i18n.__;
const STCPayContent = props => {
  /**
   * Props
   */
  const {
    setting,
    onSubmit,
    eventRegistration,
    emitResponse,
    billing
  } = props;

  /**
   * @description Define the events
   */
  const {
    onPaymentSetup,
    onCheckoutSuccess,
    onCheckoutFail
  } = eventRegistration;

  /**
   * @description Payment Status
   */
  const [paymentStatus, setPaymentStatus] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)({
    status: '',
    message: ''
  });

  /**
   * @description Payment Step
   */
  const [paymentStep, setPaymentStep] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)('PHONE');

  /**
   * @description Payment Validation URL (Redirect with OTP)
   */
  const [paymentValidationUrl, setPaymentValidationUrl] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)('');
  let data = (0,_wordpress_html_entities__WEBPACK_IMPORTED_MODULE_1__.decodeEntities)(setting.description || '');
  const [formData, setFormData] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)({
    phone: '',
    otp: ''
  });
  const form = (0,react__WEBPACK_IMPORTED_MODULE_0__.useRef)(formData);
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    form.current = formData;
  }, [formData]);
  const [errors, setErrors] = (0,react__WEBPACK_IMPORTED_MODULE_0__.useState)({
    phone: '',
    otp: ''
  });

  /**
   * @description Detect the total price change, reset step to PHONE
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(data => {
    setPaymentStep('PHONE');
    setPaymentStatus({
      status: '',
      message: ''
    });
    // Reset payment cycle
  }, [billing.cartTotal.value]);

  /**
   * @description Disable Place Order Button on Mount
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    let placeOrderButton = null;
    // On document load
    window.onload = () => {
      // Disable Place Order Button
      placeOrderButton = document.getElementsByClassName('wc-block-components-checkout-place-order-button')[0];
      placeOrderButton.disabled = true;
    };
    return () => {
      // Reset Place Order Button
      if (placeOrderButton) {
        placeOrderButton.disabled = false;
      }
    };
  }, []);

  /**
   * @description attach phone before submitting the form
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onPaymentSetup(async () => {
      const payload = form.current;
      let validationResult = validateField('phone', payload.phone);
      if (validationResult.isValid === false) {
        return {
          type: emitResponse.responseTypes.ERROR,
          message: validationResult.message
        };
      }
      return {
        type: emitResponse.responseTypes.SUCCESS,
        meta: {
          paymentMethodData: {
            'mysr_token': validationResult.submitValue,
            'moyasar-stc-nonce-field': moyasar_notice.value
          }
        }
      };
    });
    // Unsubscribes when this component is unmounted.
    return () => {
      unsubscribe();
    };
  }, [emitResponse.responseTypes.ERROR, emitResponse.responseTypes.SUCCESS, onPaymentSetup]);

  /**
   * @description Checkout Success, Switch to OTP Step
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onCheckoutSuccess(async data => {
      const response = data.processingResponse.paymentDetails;
      setPaymentStatus({
        status: 'success',
        message: __mysr_sp('OTP Sent Successfully. Please enter the OTP code you received.', 'moyasar')
      });
      setPaymentStep('OTP');
      setPaymentValidationUrl(response.redirect);
      await new Promise(resolve => setTimeout(resolve, 100000));
      if (paymentStep === 'PHONE') {
        return;
      }
      return {
        type: emitResponse.responseTypes.ERROR,
        message: 'Timeout'
      };
    });
    return () => {
      unsubscribe();
    };
  }, [onCheckoutSuccess, emitResponse.responseTypes.SUCCESS]);

  /**
   * @description Checkout Fail
   */
  (0,react__WEBPACK_IMPORTED_MODULE_0__.useEffect)(() => {
    const unsubscribe = onCheckoutFail(async data => {
      const response = data.processingResponse.paymentDetails;
      setPaymentStatus({
        status: 'failed',
        message: response.message
      });
      return {
        type: emitResponse.responseTypes.ERROR,
        message: response.message
      };
    });
    return () => {
      unsubscribe();
    };
  }, [onCheckoutFail, emitResponse.responseTypes.ERROR]);

  /**
   * @description Handle OTP Submission
   * @param e
   */
  const handleSubmit = e => {
    e.preventDefault();
    onSubmit();
  };

  /**
   * @description Redirect to Validation URL with OTP
   * @param e
   */
  const handleVerify = e => {
    e.preventDefault();
    // Redirect to Validation URL
    window.location.href = paymentValidationUrl + '&otp=' + formData.otp;
  };

  /**
   * @description Validate Field
   */
  const validateField = (field, value, showError = true) => {
    let result = {};
    if (field === 'phone') {
      result = MoyasarValidation.phoneNumber(value);
    } else if (field === 'otp') {
      result = MoyasarValidation.otp(value);
    } else {
      return {
        isValid: false,
        showValue: ''
      };
    }
    if (result.isValid === false && showError) {
      setErrors({
        ...errors,
        field: result.message
      });
    } else {
      setErrors({});
    }
    return result;
  };
  const handleChange = e => {
    let {
      name,
      value
    } = e.target;
    let result = validateField(name, value, false);
    value = result.showValue;
    setFormData({
      ...formData,
      [name]: value
    });
  };
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, paymentStatus.message && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: `${paymentStatus.status === 'success' ? 'woocommerce-message' : 'woocommerce-error'}`
  }, paymentStatus.message), data, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, __mysr_sp('Please enter your mobile number and press \'Send OTP\'. You will receive an SMS code required to complete the payment process.', 'moyasar')), paymentStep === 'PHONE' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "wc-block-components-text-input is-active"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    id: "phone-number",
    "aria-label": "Phone number",
    required: true,
    "aria-invalid": "false",
    title: "",
    class: "moyasar-number",
    placeholder: '05x xxx xxxx',
    name: "phone",
    value: formData.phone,
    onChange: handleChange
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    htmlFor: "card-name"
  }, __mysr_sp("Phone Number", 'moyasar'))), paymentStep === 'OTP' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "wc-block-components-text-input is-active"
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("input", {
    type: "text",
    id: "otp-number",
    "aria-label": "OTP number",
    required: true,
    "aria-invalid": "false",
    title: "",
    class: "moyasar-number",
    placeholder: 'xxx',
    name: "otp",
    value: formData.otp,
    onChange: handleChange
  }), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("label", {
    htmlFor: "card-name"
  }, __mysr_sp("OTP", 'moyasar'))), paymentStep === 'PHONE' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "moyasar-stc-pay-button",
    onClick: handleSubmit,
    disabled: 'phone' in errors
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, __mysr_sp("Send OTP", 'moyasar'))), paymentStep === 'OTP' && (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("button", {
    className: "moyasar-stc-pay-button",
    onClick: handleVerify,
    disabled: 'otp' in errors
  }, (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", null, __mysr_sp("Submit", 'moyasar')))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (STCPayContent);

/***/ }),

/***/ "./assets/blocks/src/js/index.js":
/*!***************************************!*\
  !*** ./assets/blocks/src/js/index.js ***!
  \***************************************/
/***/ ((module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.a(module, async (__webpack_handle_async_dependencies__, __webpack_async_result__) => { try {
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _forms_creditcard__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./forms/creditcard */ "./assets/blocks/src/js/forms/creditcard.js");
/* harmony import */ var _forms_stcpay__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./forms/stcpay */ "./assets/blocks/src/js/forms/stcpay.js");
/* harmony import */ var _forms_applepay__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./forms/applepay */ "./assets/blocks/src/js/forms/applepay.js");
/* harmony import */ var _forms_samsungpay__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ./forms/samsungpay */ "./assets/blocks/src/js/forms/samsungpay.js");

/**
 * External dependencies
 */
const registerPaymentMethod = window.wc.wcBlocksRegistry.registerPaymentMethod;
const getPaymentMethodData = window.wc.wcSettings.getPaymentMethodData;

/**
 * Payment Methods
 */




const i18n = window.wp.i18n;
const settings = getPaymentMethodData('moyasar', {});
const isEnabled = method => settings[method].enabled;
const getTitle = method => settings[method].title;
const getIcon = method => settings[method].icon;
const getSupports = method => settings[method].supports;
const Icon = props => {
  const icons = props.images;
  if (!icons) {
    return '';
  }
  return icons.map((icon, index) => {
    return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
      key: index,
      src: icon,
      style: {
        float: i18n.isRTL() ? 'left' : 'right',
        marginRight: i18n.isRTL() ? '0px' : '15px',
        marginLeft: i18n.isRTL() ? '15px' : '0px',
        maxWidth: '35px',
        display: 'inline'
      },
      alt: icon
    });
  });
};
const Label = props => {
  return (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      width: '100%'
    }
  }, getTitle(props.setting), (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Icon, {
    images: getIcon(props.setting)
  }));
};

/**
 * Credit card payment method
 */
const creditCardPaymentName = 'moyasar-credit-card';
if (settings[creditCardPaymentName]) {
  const creditCardPaymentMethod = {
    name: creditCardPaymentName,
    label: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Label, {
      setting: creditCardPaymentName
    }),
    content: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_forms_creditcard__WEBPACK_IMPORTED_MODULE_1__["default"], {
      setting: settings[creditCardPaymentName]
    }),
    edit: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_forms_creditcard__WEBPACK_IMPORTED_MODULE_1__["default"], {
      setting: settings[creditCardPaymentName]
    }),
    canMakePayment: () => isEnabled(creditCardPaymentName),
    ariaLabel: getTitle(creditCardPaymentName),
    supports: {
      features: getSupports(creditCardPaymentName)
    }
  };
  registerPaymentMethod(creditCardPaymentMethod);
}

/**
 * STC Pay payment method
 */
const stcPayPaymentName = 'moyasar-stc-pay';
if (settings[stcPayPaymentName]) {
  const stcPayPaymentMethod = {
    name: stcPayPaymentName,
    label: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Label, {
      setting: stcPayPaymentName
    }),
    content: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_forms_stcpay__WEBPACK_IMPORTED_MODULE_2__["default"], {
      setting: settings[stcPayPaymentName]
    }),
    edit: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_forms_stcpay__WEBPACK_IMPORTED_MODULE_2__["default"], {
      setting: settings[stcPayPaymentName]
    }),
    canMakePayment: () => isEnabled(stcPayPaymentName),
    ariaLabel: getTitle(stcPayPaymentName),
    supports: {
      features: getSupports(stcPayPaymentName)
    }
  };
  registerPaymentMethod(stcPayPaymentMethod);
}

/**
 * Apple Pay payment method
 */
const applePayPaymentName = 'moyasar-apple-pay';
if (settings[applePayPaymentName]) {
  const applePayPaymentMethod = {
    name: applePayPaymentName,
    label: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Label, {
      setting: applePayPaymentName
    }),
    content: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_forms_applepay__WEBPACK_IMPORTED_MODULE_3__["default"], {
      setting: settings[applePayPaymentName]
    }),
    edit: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_forms_applepay__WEBPACK_IMPORTED_MODULE_3__["default"], {
      setting: settings[applePayPaymentName]
    }),
    canMakePayment: () => isEnabled(applePayPaymentName),
    ariaLabel: getTitle(applePayPaymentName),
    supports: {
      features: getSupports(applePayPaymentName)
    }
  };

  /**
   * Register Apple Pay payment method if the browser supports it.
   */
  if (window.ApplePaySession && ApplePaySession.canMakePayments()) {
    registerPaymentMethod(applePayPaymentMethod);
  }
}

/**
 * Samsung Pay payment method
 */
const samsungPayPaymentName = 'moyasar-samsung-pay';
const samsungConfig = settings[samsungPayPaymentName];
if (settings[samsungPayPaymentName]) {
  const samsungPayPaymentMethod = {
    name: samsungPayPaymentName,
    label: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(Label, {
      setting: samsungPayPaymentName
    }),
    content: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_forms_samsungpay__WEBPACK_IMPORTED_MODULE_4__["default"], {
      setting: settings[samsungPayPaymentName]
    }),
    edit: (0,react__WEBPACK_IMPORTED_MODULE_0__.createElement)(_forms_samsungpay__WEBPACK_IMPORTED_MODULE_4__["default"], {
      setting: settings[samsungPayPaymentName]
    }),
    canMakePayment: () => isEnabled(samsungPayPaymentName),
    ariaLabel: getTitle(samsungPayPaymentName),
    supports: {
      features: getSupports(samsungPayPaymentName)
    }
  };

  /**
   * Register Samsung Pay payment method if the browser supports it.
   */
  if (await MoyasarSamsungHelper.initializeSamsungPay(samsungConfig['serviceId'], samsungConfig['supportedNetworks'])) {
    registerPaymentMethod(samsungPayPaymentMethod);
  }
}
__webpack_async_result__();
} catch(e) { __webpack_async_result__(e); } }, 1);

/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/html-entities":
/*!**************************************!*\
  !*** external ["wp","htmlEntities"] ***!
  \**************************************/
/***/ ((module) => {

module.exports = window["wp"]["htmlEntities"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/async module */
/******/ 	(() => {
/******/ 		var webpackQueues = typeof Symbol === "function" ? Symbol("webpack queues") : "__webpack_queues__";
/******/ 		var webpackExports = typeof Symbol === "function" ? Symbol("webpack exports") : "__webpack_exports__";
/******/ 		var webpackError = typeof Symbol === "function" ? Symbol("webpack error") : "__webpack_error__";
/******/ 		var resolveQueue = (queue) => {
/******/ 			if(queue && queue.d < 1) {
/******/ 				queue.d = 1;
/******/ 				queue.forEach((fn) => (fn.r--));
/******/ 				queue.forEach((fn) => (fn.r-- ? fn.r++ : fn()));
/******/ 			}
/******/ 		}
/******/ 		var wrapDeps = (deps) => (deps.map((dep) => {
/******/ 			if(dep !== null && typeof dep === "object") {
/******/ 				if(dep[webpackQueues]) return dep;
/******/ 				if(dep.then) {
/******/ 					var queue = [];
/******/ 					queue.d = 0;
/******/ 					dep.then((r) => {
/******/ 						obj[webpackExports] = r;
/******/ 						resolveQueue(queue);
/******/ 					}, (e) => {
/******/ 						obj[webpackError] = e;
/******/ 						resolveQueue(queue);
/******/ 					});
/******/ 					var obj = {};
/******/ 					obj[webpackQueues] = (fn) => (fn(queue));
/******/ 					return obj;
/******/ 				}
/******/ 			}
/******/ 			var ret = {};
/******/ 			ret[webpackQueues] = x => {};
/******/ 			ret[webpackExports] = dep;
/******/ 			return ret;
/******/ 		}));
/******/ 		__webpack_require__.a = (module, body, hasAwait) => {
/******/ 			var queue;
/******/ 			hasAwait && ((queue = []).d = -1);
/******/ 			var depQueues = new Set();
/******/ 			var exports = module.exports;
/******/ 			var currentDeps;
/******/ 			var outerResolve;
/******/ 			var reject;
/******/ 			var promise = new Promise((resolve, rej) => {
/******/ 				reject = rej;
/******/ 				outerResolve = resolve;
/******/ 			});
/******/ 			promise[webpackExports] = exports;
/******/ 			promise[webpackQueues] = (fn) => (queue && fn(queue), depQueues.forEach(fn), promise["catch"](x => {}));
/******/ 			module.exports = promise;
/******/ 			body((deps) => {
/******/ 				currentDeps = wrapDeps(deps);
/******/ 				var fn;
/******/ 				var getResult = () => (currentDeps.map((d) => {
/******/ 					if(d[webpackError]) throw d[webpackError];
/******/ 					return d[webpackExports];
/******/ 				}))
/******/ 				var promise = new Promise((resolve) => {
/******/ 					fn = () => (resolve(getResult));
/******/ 					fn.r = 0;
/******/ 					var fnQueue = (q) => (q !== queue && !depQueues.has(q) && (depQueues.add(q), q && !q.d && (fn.r++, q.push(fn))));
/******/ 					currentDeps.map((dep) => (dep[webpackQueues](fnQueue)));
/******/ 				});
/******/ 				return fn.r ? promise : getResult();
/******/ 			}, (err) => ((err ? reject(promise[webpackError] = err) : outerResolve(exports)), resolveQueue(queue)));
/******/ 			queue && queue.d < 0 && (queue.d = 0);
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module used 'module' so it can't be inlined
/******/ 	var __webpack_exports__ = __webpack_require__("./assets/blocks/src/js/index.js");
/******/ 	
/******/ })()
;
//# sourceMappingURL=index.js.map