/**
 * Contact Form JavaScript
 */

(function($) {
    'use strict';

    var ContactForm = {
        
        init: function() {
            this.bindEvents();
        },
        
        bindEvents: function() {
            $('.y-l-contact-us-form').on('submit', this.handleSubmit);
        },
        
        handleSubmit: function(e) {
            e.preventDefault();
            
            var $form = $(this);
            var $btn = $form.find('.y-c-submit-btn, .y-c-contact-btn');
            var $btnText = $btn.find('span');
            var originalText = $btnText.text();
            
            // Get form data
            var formData = {
                action: 'my_car_send_contact_form',
                nonce: myCarContactForm.nonce,
                name: $form.find('#name').val(),
                email: $form.find('#email').val(),
                phone: $form.find('#phone').val(),
                subject: $form.find('#subject').val(),
                message: $form.find('#message').val()
            };
            
            // Validate
            if (!formData.name || !formData.email || !formData.phone || !formData.subject) {
                ContactForm.showMessage($form, 'error', 'يرجى ملء جميع الحقول المطلوبة');
                return;
            }
            
            // Disable button
            $btn.prop('disabled', true);
            $btnText.text(myCarContactForm.strings.sending);
            
            $.ajax({
                url: myCarContactForm.ajaxUrl,
                type: 'POST',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        ContactForm.showMessage($form, 'success', myCarContactForm.strings.success);
                        $form[0].reset();
                    } else {
                        ContactForm.showMessage($form, 'error', response.data || myCarContactForm.strings.error);
                    }
                },
                error: function() {
                    ContactForm.showMessage($form, 'error', myCarContactForm.strings.error);
                },
                complete: function() {
                    $btn.prop('disabled', false);
                    $btnText.text(originalText);
                }
            });
        },
        
        showMessage: function($form, type, message) {
            // Remove existing message
            $form.find('.y-c-form-message').remove();
            
            var $message = $('<div class="y-c-form-message y-c-form-message-' + type + '">' + message + '</div>');
            
            $form.prepend($message);
            
            // Auto hide after 5 seconds for success
            if (type === 'success') {
                setTimeout(function() {
                    $message.fadeOut(function() {
                        $(this).remove();
                    });
                }, 5000);
            }
            
            // Scroll to message
            $('html, body').animate({
                scrollTop: $form.offset().top - 100
            }, 300);
        }
    };

    $(document).ready(function() {
        ContactForm.init();
    });

})(jQuery);
