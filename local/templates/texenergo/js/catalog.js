$(function () {

	$('#product-share-trigger').closest('li').click(function(e){
		e.preventDefault();
		if($('#share_block').is(':visible')){
			$('#share_block').hide();
		}else{
			var pos = $(this).position();
			$('#share_block').css('top', pos.top + 33);
			$('#share_block').css('left', pos.left - 62);
			$('#product-tracking-popup').hide();
			$('#share_block').show();
		}
	});

    $('.j-texenergo-vendor').on('change', function (e) {

        var now = new Date();
        var lifetime = 86400 * 365;
        var expiresDate = new Date(now.getTime() + lifetime);

        var newValue = (getCookie('filter_own_products') > 0 ? 0 : 1);
        setCookie('filter_own_products', newValue, expiresDate, '/');
        document.location.reload();
    });

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
        span.prependTo($('.catalog > .sort'));
    }

    $('.cat-product, .cat-hoverbox').hover(
        function () {
            $('.cat-hoverbox').addClass('hidden');
            $('.cat-product').removeClass('is-hovered');
            $(this).parent().find('.cat-hoverbox').removeClass('hidden');
            $(this).parent().children('.cat-product').addClass('is-hovered');
            $(this).parent().find('.thumbnails').removeClass('hidden');
        },
        function () {
            $(this).parent().find('.cat-hoverbox').addClass('hidden');
            $(this).parent().find('.thumbnails').addClass('hidden');
        });

});