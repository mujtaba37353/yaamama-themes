/**
 * Pages Manager Admin JavaScript
 */

(function($) {
    'use strict';

    var PagesManager = {
        
        init: function() {
            this.bindEvents();
        },
        
        bindEvents: function() {
            // Create missing pages
            $('#create-missing-pages').on('click', this.createMissingPages);
            
            // Create single page
            $('.create-single-page').on('click', this.createSinglePage);
            
            // Reset content
            $('.reset-content').on('click', this.resetContent);
            
            // Remove repeater item
            $(document).on('click', '.remove-item', this.removeRepeaterItem);
            
            // Add repeater item
            $('.add-repeater-item').on('click', this.addRepeaterItem);
        },
        
        createMissingPages: function(e) {
            e.preventDefault();
            
            if (!confirm(myCarPagesManager.strings.confirmCreate)) {
                return;
            }
            
            var $btn = $(this);
            $btn.addClass('loading').text(myCarPagesManager.strings.creating);
            
            $.ajax({
                url: myCarPagesManager.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'my_car_create_pages',
                    nonce: myCarPagesManager.nonce
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message);
                        location.reload();
                    } else {
                        alert(myCarPagesManager.strings.error);
                    }
                },
                error: function() {
                    alert(myCarPagesManager.strings.error);
                },
                complete: function() {
                    $btn.removeClass('loading');
                }
            });
        },
        
        createSinglePage: function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var slug = $btn.data('slug');
            
            $btn.addClass('loading').prop('disabled', true);
            
            $.ajax({
                url: myCarPagesManager.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'my_car_create_pages',
                    nonce: myCarPagesManager.nonce,
                    slug: slug
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(myCarPagesManager.strings.error);
                    }
                },
                error: function() {
                    alert(myCarPagesManager.strings.error);
                },
                complete: function() {
                    $btn.removeClass('loading').prop('disabled', false);
                }
            });
        },
        
        resetContent: function(e) {
            e.preventDefault();
            
            if (!confirm(myCarPagesManager.strings.confirmReset)) {
                return;
            }
            
            var $btn = $(this);
            var page = $btn.data('page');
            
            $btn.addClass('loading');
            
            $.ajax({
                url: myCarPagesManager.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'my_car_reset_content',
                    nonce: myCarPagesManager.nonce,
                    page: page
                },
                success: function(response) {
                    if (response.success) {
                        alert(response.data.message);
                        location.reload();
                    } else {
                        alert(myCarPagesManager.strings.error);
                    }
                },
                error: function() {
                    alert(myCarPagesManager.strings.error);
                },
                complete: function() {
                    $btn.removeClass('loading');
                }
            });
        },
        
        removeRepeaterItem: function(e) {
            e.preventDefault();
            
            var $item = $(this).closest('.repeater-item');
            
            if ($item.siblings('.repeater-item').length === 0) {
                alert('يجب أن يبقى عنصر واحد على الأقل');
                return;
            }
            
            $item.fadeOut(300, function() {
                $(this).remove();
                PagesManager.reindexRepeater($(this).closest('.repeater-field'));
            });
        },
        
        addRepeaterItem: function(e) {
            e.preventDefault();
            
            var $btn = $(this);
            var targetId = $btn.data('target');
            var page = $btn.data('page');
            var field = $btn.data('field');
            var $container = $('#' + targetId);
            var $lastItem = $container.find('.repeater-item:last');
            var newIndex = $container.find('.repeater-item').length;
            
            var $newItem = $lastItem.clone();
            
            // Clear values
            $newItem.find('input, textarea').val('');
            
            // Update name attributes
            $newItem.find('input, textarea, select').each(function() {
                var name = $(this).attr('name');
                if (name) {
                    var newName = name.replace(/\[\d+\]/, '[' + newIndex + ']');
                    $(this).attr('name', newName);
                }
                
                var id = $(this).attr('id');
                if (id) {
                    $(this).attr('id', id.replace(/_\d+$/, '_' + newIndex));
                }
            });
            
            // Update title
            $newItem.find('.item-title').text(PagesManager.getItemTitle(field, newIndex + 1));
            
            // Remove any wp-editor instances and replace with textarea
            $newItem.find('.wp-editor-wrap').each(function() {
                var $wrap = $(this);
                var $textarea = $wrap.find('textarea');
                var name = $textarea.attr('name');
                var newTextarea = $('<textarea>')
                    .attr('name', name.replace(/\[\d+\]/, '[' + newIndex + ']'))
                    .attr('rows', 5)
                    .addClass('widefat')
                    .val('');
                $wrap.replaceWith(newTextarea);
            });
            
            $container.append($newItem);
            $newItem.hide().fadeIn(300);
        },
        
        getItemTitle: function(field, index) {
            var titles = {
                'values': 'قيمة ' + index,
                'stats': 'إحصائية ' + index,
                'sections': 'قسم ' + index,
                'questions': 'سؤال ' + index,
                'tiers': 'فترة ' + index
            };
            return titles[field] || 'عنصر ' + index;
        },
        
        reindexRepeater: function($container) {
            $container.find('.repeater-item').each(function(index) {
                $(this).find('input, textarea, select').each(function() {
                    var name = $(this).attr('name');
                    if (name) {
                        var newName = name.replace(/\[\d+\]/, '[' + index + ']');
                        $(this).attr('name', newName);
                    }
                });
            });
        }
    };

    $(document).ready(function() {
        PagesManager.init();
    });

})(jQuery);
