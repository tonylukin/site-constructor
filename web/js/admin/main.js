$(function () {
    'use strict';

    $('.js-spoiler').each(function () {
        var $spoiler = $(this);
        var allowedRows = $spoiler.data('shown-rows') || 3;
        var height = $spoiler.outerHeight();
        var lineHeight = parseFloat($spoiler.css('lineHeight'));
        var rows = height / lineHeight;

        if (rows > allowedRows) {
            $spoiler
                .height(allowedRows * lineHeight)
                .css({overflow: 'hidden'})
            ;
            $('<a href="#">Show all</a>')
                .insertAfter($spoiler)
                .on('click', function () {
                    $(this).hide().prev().height('auto')
                })
            ;
        }
    });
});
