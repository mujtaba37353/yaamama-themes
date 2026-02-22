/* global jQuery, wp */
'use strict';

jQuery(function ($) {
    const openMediaFrame = (targetInput) => {
        const frame = wp.media({
            title: 'اختيار صورة',
            button: { text: 'استخدام الصورة' },
            library: { type: 'image' },
            multiple: false
        });

        frame.on('select', () => {
            const attachment = frame.state().get('selection').first().toJSON();
            if (attachment && attachment.url) {
                $('#' + targetInput).val(attachment.url);
            }
        });

        frame.open();
    };

    $(document).on('click', '.ahmadi-media-upload', function (event) {
        event.preventDefault();
        const target = $(this).data('target');
        if (target) {
            openMediaFrame(target);
        }
    });
});
