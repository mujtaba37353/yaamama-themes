jQuery(function ($) {
    $('.al-thabihah-color-picker').wpColorPicker();

    let frame;
    $(document).on('click', '.al-thabihah-image-upload', function (e) {
        e.preventDefault();
        const field = $(this).closest('.al-thabihah-image-field');
        const input = field.find('input[data-image-id]');
        const preview = field.find('img');

        if (frame) {
            frame.open();
            return;
        }
        frame = wp.media({
            title: 'اختيار صورة',
            button: { text: 'استخدام الصورة' },
            multiple: false
        });
        frame.on('select', function () {
            const attachment = frame.state().get('selection').first().toJSON();
            input.val(attachment.id);
            preview.attr('src', attachment.url);
        });
        frame.open();
    });

    $(document).on('click', '.al-thabihah-image-remove', function (e) {
        e.preventDefault();
        const field = $(this).closest('.al-thabihah-image-field');
        const input = field.find('input[data-image-id]');
        const preview = field.find('img');
        const defaultUrl = $(this).data('default-url');
        input.val('');
        preview.attr('src', defaultUrl);
    });
});
