$(function () {

    $('.subscribe-toggle').on('change', function () {
        var ids = [];
        $('.subscribe-toggle').each(function () {
            if ($(this).hasClass('subSwitch-on')) {
                ids.push($(this).data('id'));
            }
        });
        $.post(document.location.href, {RUB_ID: ids, FORMAT: 'html', Update: 'yes'});
    });

    $('.subscribe-off').on('click', function (e) {
        e.preventDefault();
        var block = $(this).closest('.subSection-block'),
            items = $('.subscribe-toggle', block);

        items.removeClass('subSwitch-on');
        items.first().trigger('change');

        return false;
    });

    $('.products-toggle').on('change', function () {
        var ids = [];
        $('.products-toggle').each(function () {
            if ($(this).hasClass('subSwitch-on')) {
                ids.push($(this).data('id'));
            }
        });
        //$.post(document.location.href, {ITEMS_ID: ids, UpdateProducts: 'yes'});
    });

    $('.products-off').on('click', function (e) {
        e.preventDefault();
        var block = $(this).closest('.subSection-block'),
            items = $('.products-toggle', block);

        items.removeClass('subSwitch-on');
        items.first().trigger('change');

        return false;
    });

});