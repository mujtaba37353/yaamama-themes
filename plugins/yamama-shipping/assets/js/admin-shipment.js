(function ($) {
    'use strict';

    /* ──────────────────────────────────────────────
     *  Fetch Label Button (works on status view too)
     * ────────────────────────────────────────────── */

    $(document).on('click', '.yamama-fetch-label', function () {
        var $btn = $(this);
        var $status = $btn.siblings('.yamama-label-status');
        var oid = $btn.data('order-id');

        if (!oid || $btn.prop('disabled')) return;

        var originalText = $btn.text();
        $btn.prop('disabled', true).text('جاري الجلب...');
        $status.text('');

        var url = (typeof yamamaShipping !== 'undefined') ? yamamaShipping.ajaxUrl : ajaxurl;
        var nc = (typeof yamamaShipping !== 'undefined') ? yamamaShipping.nonce : '';
        if (window.location.protocol === 'https:' && url.indexOf('http:') === 0) {
            url = url.replace('http:', 'https:');
        }

        $.post(url, {
            action: 'yamama_fetch_label',
            nonce: nc,
            order_id: oid
        }, function (res) {
            if (res.success && res.data) {
                if (res.data.pdf_base64) {
                    var byteChars = atob(res.data.pdf_base64);
                    var byteNumbers = new Array(byteChars.length);
                    for (var i = 0; i < byteChars.length; i++) {
                        byteNumbers[i] = byteChars.charCodeAt(i);
                    }
                    var blob = new Blob([new Uint8Array(byteNumbers)], { type: 'application/pdf' });
                    var blobUrl = URL.createObjectURL(blob);
                    window.open(blobUrl, '_blank');
                } else if (res.data.label_url) {
                    window.open(res.data.label_url, '_blank');
                }
                $btn.prop('disabled', false).text('تحميل PDF');
                $status.text('');
            } else {
                var msg = (res.data && res.data.message) ? res.data.message : 'فشل جلب البوليصة';
                $btn.prop('disabled', false).text(originalText);
                $status.text(' ' + msg).css('color', '#d63638');
            }
        }).fail(function () {
            $btn.prop('disabled', false).text(originalText);
            $status.text(' خطأ في الاتصال').css('color', '#d63638');
        });
    });

    /* ──────────────────────────────────────────────
     *  Shipment Form (only when form exists)
     * ────────────────────────────────────────────── */

    var $form = $('#yamama-shipment-form');
    if (!$form.length) {
        return;
    }

    var orderId        = $form.data('order-id');
    var ajaxUrl        = yamamaShipping.ajaxUrl;
    var nonce          = yamamaShipping.nonce;

    // Match AJAX URL scheme to current page (fixes mixed content after 3DS redirect)
    if (window.location.protocol === 'https:' && ajaxUrl.indexOf('http:') === 0) {
        ajaxUrl = ajaxUrl.replace('http:', 'https:');
    }
    var moyasarKey     = (yamamaShipping.moyasarKey || '').toString().trim();
    var moyasarMethods = yamamaShipping.moyasarMethods || ['creditcard', 'stcpay'];
    var callbackUrl    = yamamaShipping.callbackUrl;


    var quoteCost    = 0;
    var quoteReady   = false;
    var paymentReady = false;

    /* ──────────────────────────────────────────────
     *  Load Carriers
     * ────────────────────────────────────────────── */

    function loadCarriers() {
        var $select = $('#yamama-carrier');
        $select.html('<option value="">جاري التحميل...</option>');

        $.post(ajaxUrl, {
            action: 'yamama_get_carriers',
            nonce: nonce
        }, function (res) {
            $select.empty();
            $select.append('<option value="">-- اختر شركة الشحن --</option>');

            if (res.success && res.data && res.data.carriers) {
                var carriers = res.data.carriers;
                for (var i = 0; i < carriers.length; i++) {
                    var c = carriers[i];
                    var label = c.name || 'Carrier ' + c.carrier_id;
                    var badges = [];
                    if (c.has_cod) badges.push('COD');
                    if (c.has_parcel) badges.push('Parcels');
                    if (c.has_cancel) badges.push('Cancel');
                    if (badges.length) label += ' (' + badges.join(', ') + ')';
                    var $opt = $('<option>', { value: c.carrier_id, text: label });
                    $opt.data('has-cod', !!c.has_cod);
                    $select.append($opt);
                }
                validateCarrierPayment();
            } else {
                $select.append('<option value="">فشل تحميل شركات الشحن</option>');
            }
        }).fail(function () {
            $select.html('<option value="">خطأ في الاتصال</option>');
        });
    }

    loadCarriers();

    /* ──────────────────────────────────────────────
     *  Carrier ↔ Payment Method Validation
     * ────────────────────────────────────────────── */

    var $carrierSelect  = $form.find('[name="carrier_id"]');
    var $paymentSelect  = $form.find('[name="payment_method"]');
    var $carrierWarning = $('<div class="yamama-carrier-warning notice notice-warning inline" style="display:none;margin:8px 0;"><p></p></div>');
    $carrierSelect.closest('.yamama-form-grid').after($carrierWarning);

    function validateCarrierPayment() {
        var $selected    = $carrierSelect.find('option:selected');
        var carrierId    = $selected.val();
        var paymentVal   = $paymentSelect.val();
        var isCod        = (paymentVal === 'cod');
        var hasCod       = $selected.data('has-cod');
        var $quoteBtn    = $('#yamama-get-quote');

        if (!carrierId || !isCod) {
            $carrierWarning.hide();
            $quoteBtn.prop('disabled', false);
            return true;
        }

        if (!hasCod) {
            $carrierWarning.find('p').text('شركة الشحن المختارة لا تدعم الدفع عند الاستلام (COD). يرجى اختيار شركة أخرى أو تغيير طريقة الدفع.');
            $carrierWarning.show();
            $quoteBtn.prop('disabled', true);
            return false;
        }

        $carrierWarning.hide();
        $quoteBtn.prop('disabled', false);
        return true;
    }

    $carrierSelect.on('change', validateCarrierPayment);
    $paymentSelect.on('change', validateCarrierPayment);

    /* ──────────────────────────────────────────────
     *  Collect Form Data
     * ────────────────────────────────────────────── */

    function collectFormData() {
        var data = {};
        $form.find('input, select, textarea').each(function () {
            var name = $(this).attr('name');
            if (!name) return;

            if ($(this).is(':checkbox')) {
                data[name] = $(this).is(':checked') ? '1' : '0';
            } else {
                data[name] = $(this).val();
            }
        });

        var items = [];
        $form.find('.yamama-items-table tbody tr').each(function () {
            var item = {};
            $(this).find('input').each(function () {
                var n = $(this).attr('name');
                var match = n.match(/items\[\d+\]\[(\w+)\]/);
                if (match) {
                    item[match[1]] = $(this).val();
                }
            });
            if (item.name) {
                items.push(item);
            }
        });
        data.items = items;
        data.quote_cost = String(quoteCost);

        return data;
    }

    function calculateTotalWeight() {
        var total = 0;
        $form.find('.yamama-items-table tbody tr').each(function () {
            var qty = parseFloat($(this).find('input[name*="[quantity]"]').val()) || 1;
            var w   = parseFloat($(this).find('input[name*="[weight]"]').val()) || 0.5;
            total += qty * w;
        });
        return total > 0 ? total : 1;
    }

    /* ──────────────────────────────────────────────
     *  Get Quote
     * ────────────────────────────────────────────── */

    function showSpinner() { $('#yamama-spinner').addClass('is-active'); }
    function hideSpinner() { $('#yamama-spinner').removeClass('is-active'); }

    function showResult(type, msg) {
        var cls = type === 'error' ? 'notice-error' : 'notice-success';
        $('#yamama-result').html('<div class="notice ' + cls + ' inline"><p>' + msg + '</p></div>').show();
    }

    $('#yamama-get-quote').on('click', function () {
        var carrierId     = $form.find('[name="carrier_id"]').val();
        var city          = $form.find('[name="customer_city"]').val();
        var paymentMethod = $form.find('[name="payment_method"]').val();
        var weight        = calculateTotalWeight();

        if (!carrierId) {
            showResult('error', 'يرجى اختيار شركة الشحن');
            return;
        }
        if (!validateCarrierPayment()) {
            return;
        }
        if (!city) {
            showResult('error', 'يرجى إدخال مدينة العميل');
            return;
        }

        showSpinner();
        $('#yamama-result').hide();
        quoteReady = false;
        $('#yamama-pay-ship').hide();

        $.post(ajaxUrl, {
            action: 'yamama_get_quote',
            nonce: nonce,
            carrier_id: carrierId,
            city: city,
            weight: weight,
            payment_method: paymentMethod
        }, function (res) {
            hideSpinner();

            if (res.success && res.data) {
                var d = res.data;
                // The middleware may nest results under 'data'
                if (d.data && typeof d.data === 'object') {
                    d = d.data;
                }
                var cost = d.shipping_cost || d.shippingCost || d.cost || d.price || 0;
                quoteCost = parseFloat(cost);
                $('#yamama-quote-cost').text(quoteCost.toFixed(2));
                $('#yamama-quote-section').show();
                quoteReady = true;

                if (quoteCost > 0) {
                    $('#yamama-pay-ship').text('الدفع وإنشاء الشحنة').show();
                } else {
                    $('#yamama-pay-ship').text('إنشاء الشحنة').show();
                }
                $('#yamama-result').hide();
            } else {
                var msg = (res.data && res.data.message) ? res.data.message : 'فشل الحصول على التكلفة';
                showResult('error', msg);
            }
        }).fail(function () {
            hideSpinner();
            showResult('error', 'خطأ في الاتصال بالخادم');
        });
    });

    /* ──────────────────────────────────────────────
     *  Pay & Ship
     * ────────────────────────────────────────────── */

    $('#yamama-pay-ship').on('click', function () {
        if (!quoteReady) {
            showResult('error', 'يرجى حساب التكلفة أولاً');
            return;
        }

        if (quoteCost > 0 && !moyasarKey) {
            showResult('error', 'لم يتم استلام مفتاح الدفع من المنصة. يرجى التأكد من اتصال المتجر بالمنصة الوسيطة.');
            return;
        }

        var requiredFields = [
            { name: 'customer_name', label: 'اسم العميل' },
            { name: 'customer_phone1', label: 'جوال العميل' },
            { name: 'customer_city', label: 'مدينة العميل' },
            { name: 'customer_address1', label: 'عنوان العميل' },
            { name: 'shipper_name', label: 'اسم المرسل' },
            { name: 'shipper_phone', label: 'جوال المرسل' },
            { name: 'shipper_city', label: 'مدينة المرسل' },
            { name: 'shipper_address1', label: 'عنوان المرسل' },
            { name: 'carrier_id', label: 'شركة الشحن' }
        ];

        for (var i = 0; i < requiredFields.length; i++) {
            var val = $form.find('[name="' + requiredFields[i].name + '"]').val();
            if (!val || val.trim() === '') {
                showResult('error', 'يرجى تعبئة: ' + requiredFields[i].label);
                return;
            }
        }

        showSpinner();
        $('#yamama-result').hide();
        $(this).prop('disabled', true);

        var formData = collectFormData();

        $.post(ajaxUrl, {
            action: 'yamama_save_pending',
            nonce: nonce,
            order_id: orderId,
            form_data: formData
        }, function (res) {
            hideSpinner();

            if (!res.success) {
                showResult('error', (res.data && res.data.message) || 'فشل حفظ البيانات');
                $('#yamama-pay-ship').prop('disabled', false);
                return;
            }

            if (quoteCost <= 0) {
                createShipmentOrder('');
            } else {
                initMoyasar();
            }
        }).fail(function () {
            hideSpinner();
            showResult('error', 'خطأ في الاتصال بالخادم');
            $('#yamama-pay-ship').prop('disabled', false);
        });
    });

    /* ──────────────────────────────────────────────
     *  Moyasar Payment
     * ────────────────────────────────────────────── */

    function initMoyasar() {
        if (typeof Moyasar === 'undefined') {
            showResult('error', 'فشل تحميل مكتبة الدفع (Moyasar). يرجى تحديث الصفحة والمحاولة مرة أخرى.');
            $('#yamama-pay-ship').prop('disabled', false);
            return;
        }

        if (!moyasarKey) {
            showResult('error', 'لم يتم استلام مفتاح الدفع من المنصة. يرجى التأكد من اتصال المتجر بالمنصة الوسيطة وتفعيل مفاتيح Moyasar.');
            $('#yamama-pay-ship').prop('disabled', false);
            return;
        }

        var $container = $('#yamama-moyasar-container');
        $container.show();

        $('#yamama-moyasar-form').empty();

        var amountInHalalas = Math.round(quoteCost * 100);
        if (amountInHalalas < 100) {
            amountInHalalas = 100;
        }

        try {
            Moyasar.init({
                element: '#yamama-moyasar-form',
                amount: amountInHalalas,
                currency: 'SAR',
                description: 'Shipping cost for order #' + orderId,
                publishable_api_key: moyasarKey,
                callback_url: callbackUrl,
                methods: moyasarMethods,
                language: 'ar',
                metadata: {
                    wc_order_id: String(orderId)
                },
                on_completed: async function (payment) {
                    if (payment && payment.id) {
                        createShipmentOrder(payment.id);
                    }
                },
                on_failure: async function (error) {
                    showResult('error', 'فشل الدفع: ' + (error || 'خطأ غير معروف'));
                    $('#yamama-pay-ship').prop('disabled', false);
                }
            });
        } catch (e) {
            showResult('error', 'خطأ في تهيئة نموذج الدفع: ' + e.message);
            $('#yamama-moyasar-container').hide();
            $('#yamama-pay-ship').prop('disabled', false);
        }
    }

    function createShipmentOrder(paymentId) {
        showSpinner();
        $('#yamama-moyasar-container').hide();
        $('#yamama-result').hide();

        $.post(ajaxUrl, {
            action: 'yamama_create_order',
            nonce: nonce,
            order_id: orderId,
            payment_id: paymentId
        }, function (res) {
            hideSpinner();

            if (res.success) {
                var d = res.data;
                var html = '<div class="notice notice-success inline">';
                html += '<p><strong>تم إنشاء الشحنة بنجاح!</strong></p>';
                if (d.lamha_order_id) html += '<p>Yamama Order: <code>' + d.lamha_order_id + '</code></p>';
                if (d.tracking_number) html += '<p>رقم التتبع: <code>' + d.tracking_number + '</code></p>';
                if (d.tracking_link) html += '<p><a href="' + d.tracking_link + '" target="_blank">رابط التتبع</a></p>';
                html += '<p><em>سيتم تحديث الصفحة خلال ثوان...</em></p>';
                html += '</div>';

                $('#yamama-result').html(html).show();
                $form.find('.yamama-section, .yamama-actions').hide();

                setTimeout(function () { location.reload(); }, 3000);
            } else {
                var msg = (res.data && res.data.message) ? res.data.message : 'فشل إنشاء الشحنة';
                showResult('error', msg);
                $('#yamama-pay-ship').prop('disabled', false);
            }
        }).fail(function () {
            hideSpinner();
            showResult('error', 'خطأ في الاتصال بالخادم');
            $('#yamama-pay-ship').prop('disabled', false);
        });
    }

    /* ──────────────────────────────────────────────
     *  Handle 3DS Redirect Result
     * ────────────────────────────────────────────── */

    (function handle3DSResult() {
        var params = new URLSearchParams(window.location.search);

        // Build a clean URL (without Moyasar/Yamama callback params) for reloads
        function getCleanUrl() {
            var url = new URL(window.location.href);
            url.searchParams.delete('yamama_success');
            url.searchParams.delete('yamama_3ds_complete');
            url.searchParams.delete('yamama_error');
            url.searchParams.delete('id');
            url.searchParams.delete('status');
            url.searchParams.delete('message');
            return url.toString();
        }

        if (params.get('yamama_success') === '1') {
            showResult('success', 'تم إنشاء الشحنة بنجاح عبر الدفع الإلكتروني!');
            $form.find('.yamama-section, .yamama-actions').hide();
            setTimeout(function () { window.location.href = getCleanUrl(); }, 2000);
            return;
        }

        // 3DS completed — create shipment via AJAX with saved payment_id.
        if (params.get('yamama_3ds_complete') === '1') {
            showResult('success', 'تم الدفع بنجاح. جاري إنشاء الشحنة...');
            showSpinner();

            $.post(ajaxUrl, {
                action: 'yamama_complete_3ds',
                nonce: nonce,
                order_id: orderId
            }, function (res) {
                hideSpinner();
                if (res.success) {
                    var d = res.data;
                    var html = '<div class="notice notice-success inline">';
                    html += '<p><strong>تم إنشاء الشحنة بنجاح!</strong></p>';
                    if (d.lamha_order_id) html += '<p>Yamama Order: <code>' + d.lamha_order_id + '</code></p>';
                    if (d.tracking_number) html += '<p>رقم التتبع: <code>' + d.tracking_number + '</code></p>';
                    html += '<p><em>سيتم تحديث الصفحة خلال ثوان...</em></p>';
                    html += '</div>';
                    $('#yamama-result').html(html).show();
                    $form.find('.yamama-section, .yamama-actions').hide();
                    setTimeout(function () { window.location.href = getCleanUrl(); }, 3000);
                } else {
                    var msg = (res.data && res.data.message) ? res.data.message : 'فشل إنشاء الشحنة';
                    showResult('error', msg);
                }
            }).fail(function () {
                hideSpinner();
                showResult('error', 'خطأ في الاتصال بالخادم');
            });
            return;
        }

        var error = params.get('yamama_error');
        if (error) {
            var errorMessages = {
                'missing_params': 'بيانات الدفع غير مكتملة.',
                'order_not_found': 'الطلب غير موجود.',
                'payment_failed': 'فشل التحقق من الدفع.',
                'no_pending_data': 'لم يتم العثور على بيانات الشحنة المعلقة.',
                'create_failed': 'فشل إنشاء الشحنة بعد الدفع. راجع ملاحظات الطلب.'
            };
            var msg = errorMessages[error] || 'حدث خطأ غير معروف.';
            showResult('error', msg);
        }
    })();

})(jQuery);
