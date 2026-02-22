/**
 * Contact Settings Admin JavaScript
 */

(function($) {
    'use strict';

    var ContactSettings = {
        
        init: function() {
            this.bindEvents();
            this.toggleSmtpFields();
        },
        
        bindEvents: function() {
            // SMTP type change
            $('#smtp_type').on('change', this.toggleSmtpType);
            
            // SMTP enabled toggle
            $('#smtp_enabled').on('change', this.toggleSmtpFields);
            
            // WhatsApp position change
            $('#whatsapp_position').on('change', this.updateWhatsAppPreview);
            
            // Test email
            $('#send-test-email').on('click', this.sendTestEmail);
        },
        
        toggleSmtpType: function() {
            var type = $(this).val();
            
            if (type === 'gmail') {
                $('.smtp-gmail-settings').show();
                $('.smtp-professional-settings').hide();
            } else {
                $('.smtp-gmail-settings').hide();
                $('.smtp-professional-settings').show();
            }
        },
        
        toggleSmtpFields: function() {
            var enabled = $('#smtp_enabled').is(':checked');
            
            if (enabled) {
                $('.smtp-field, .smtp-gmail-settings, .smtp-professional-settings').removeClass('disabled');
                $('#smtp_type').trigger('change');
            } else {
                $('.smtp-gmail-settings, .smtp-professional-settings').addClass('disabled');
            }
        },
        
        updateWhatsAppPreview: function() {
            var position = $(this).val();
            var $preview = $('.whatsapp-button-preview');
            
            $preview.removeClass('left right').addClass(position);
        },
        
        sendTestEmail: function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var email = $('#test_email').val();
            var $result = $('#test-email-result');
            
            if (!email) {
                alert(myCarContactSettings.strings.enterEmail);
                return;
            }
            
            $btn.prop('disabled', true);
            $result.removeClass('success error').addClass('loading').text(myCarContactSettings.strings.testing).show();
            
            $.ajax({
                url: myCarContactSettings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'my_car_test_email',
                    nonce: myCarContactSettings.nonce,
                    email: email
                },
                success: function(response) {
                    if (response.success) {
                        $result.removeClass('loading error').addClass('success').text(response.data);
                    } else {
                        $result.removeClass('loading success').addClass('error').text(response.data);
                    }
                },
                error: function() {
                    $result.removeClass('loading success').addClass('error').text(myCarContactSettings.strings.error);
                },
                complete: function() {
                    $btn.prop('disabled', false);
                }
            });
        }
    };

    $(document).ready(function() {
        ContactSettings.init();
    });

})(jQuery);
