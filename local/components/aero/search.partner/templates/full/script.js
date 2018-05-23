$(function () {

    $('.j-goods-full-view').unbind('change').bind('change', function (e) {
        var oldval = parseInt(getCookie('goods_full_view'), 10);
        if (oldval != parseInt($(this).val(), 10)) {
            setGoodsListViewCookie($(this).val());
            window.location.reload();
        }
    });

    $('.j-catalag-view-mode').unbind('change').bind('change', function (e) {
        var oldval = getCookie('catalog_view');
        if (oldval == 1) {
            setCatalogViewCookie(0);
        } else {
            setCatalogViewCookie(1);
        }
        window.location.assign(window.location.href);
    });

    $('.j-catalog-view-id').unbind('change').on('change', function () {
        setCatalogViewCookie($(this).val());
        window.location.reload();
    });

    $(".j_catalog_view").bind("click", function () {
        var dataParam = $(this).data("param");
        if (dataParam) {
            $("#" + dataParam).trigger("click");
        }
    });


    if ($('.bottom-pager').size() > 0) {
        var ul = $('.bottom-pager ul').clone(),
            span = $('<span/>', {
                'id': 'level-pager-top'
            });
        ul.addClass('pager-top').appendTo(span);
        span.prependTo($('.main > .sort'));
    }

});