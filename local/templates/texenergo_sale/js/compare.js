function addCompareAjax(e) {
    //var goodsId = $(this).attr('rel');
    //var item = $(".j-add-to-compare[rel=" + goodsId + "]");
    var goodsId = $(this).attr('data-id');
    var item = $(".j-add-to-compare[data-id=" + goodsId + "]");

	var compareLeft = $('.toolbar-tabs #compare').offset().left + 15;	
    
	if (item.hasClass('j-img-animate-element-page')) {

        var productWrap = item.parents('.twelve');
        var img = productWrap.find('.j-image-to-animate');
        var imgPosTop = item.offset().top;
        var imgPosLeft = item.offset().left;
        var toolBarTop = $('.j-toolbar').offset().top + 25;
        var animatedImg = img.clone();
        animatedImg.appendTo('body').addClass('animated-img').css({
            "left": imgPosLeft,
            "top": imgPosTop
        });
        var timeLine = new TimelineMax();
        timeLine.to(animatedImg, .5, { scale: 1, alpha: 1 })
            .to(animatedImg, 1, {top: toolBarTop, left: compareLeft})
            .to(animatedImg, 1, {scale: 0, alpha: 0, delay: -.5, onComplete: function () {
                animatedImg.remove();
            }});

    } else if(item.hasClass('favorite-compare')) {
		var productWrap = item.closest('.single-accessories');
        var img = productWrap.find('.j-image-to-animate');
        var imgPosTop = item.offset().top;
        var imgPosLeft = item.offset().left;
        var toolBarTop = $('.j-toolbar').offset().top + 30;
        var animatedImg = img.clone();
        animatedImg.appendTo('body').addClass('animated-img').css({
            "left": imgPosLeft,
            "top": imgPosTop
        });
        var timeLine = new TimelineMax();
        timeLine.to(animatedImg, .5, { scale: 1, alpha: 1 })
            .to(animatedImg, 1, {top: toolBarTop, left: compareLeft})
            .to(animatedImg, 1, {scale: 0, alpha: 0, delay: -.5, onComplete: function () {
                animatedImg.remove();
            }});
	
	} else if (item.hasClass('j-img-animate')) {

        var productWrap = item.parent().parent();
        var img = productWrap.find('.j-image-to-animate');
        var imgPosTop = item.offset().top;
        var imgPosLeft = item.offset().left;
        var toolBarTop = $('.j-toolbar').offset().top + 30;
        var animatedImg = img.clone();
        animatedImg.appendTo('body').addClass('animated-img').css({
            "left": imgPosLeft,
            "top": imgPosTop
        });
        var timeLine = new TimelineMax();
        timeLine.to(animatedImg, .5, { scale: 1, alpha: 1 })
            .to(animatedImg, 1, {top: toolBarTop, left: compareLeft})
            .to(animatedImg, 1, {scale: 0, alpha: 0, delay: -.5, onComplete: function () {
                animatedImg.remove();
            }});

    } else if (item.hasClass('j-img-animate-last-list')) {

        var productWrap = item.parent().parent().parent();
        var img = productWrap.find('.j-image-to-animate');
        var imgPosTop = item.offset().top;
        var imgPosLeft = item.offset().left;
        var toolBarTop = $('.j-toolbar').offset().top + 30;
        var animatedImg = img.clone();
        animatedImg.appendTo('body').addClass('animated-img').css({
            "left": imgPosLeft,
            "top": imgPosTop
        });
        var timeLine = new TimelineMax();
        timeLine.to(animatedImg, .5, { scale: 1, alpha: 1 })
            .to(animatedImg, 1, {top: toolBarTop, left: compareLeft})
            .to(animatedImg, 1, {scale: 0, alpha: 0, delay: -.5, onComplete: function () {
                animatedImg.remove();
            }});

    } else if (item.hasClass('j-animate-img-full')) {

        var productWrap = item.parents('.cat-hoverbox').prev('.cat-product');
        var img = productWrap.find('.j-image-to-animate');
        var imgPosTop = item.offset().top + 20;
        var imgPosLeft = item.offset().left + 20;
        var toolBarTop = $('.j-toolbar').offset().top + 45;
        var animatedImg = img.clone();
        animatedImg.appendTo('body').addClass('animated-img').css({
            "left": imgPosLeft,
            "top": imgPosTop
        });
        var timeLine = new TimelineMax();
        timeLine.to(animatedImg, .5, { scale: 1, alpha: 1 })
            .to(animatedImg, 1, {top: toolBarTop, left: compareLeft})
            .to(animatedImg, 1, {scale: 0, alpha: 0, delay: -.5, onComplete: function () {
                animatedImg.remove();
            }});

    } else if (item.hasClass('j-animate-short')) {

        var productWrap = item.parents('.pProductInLine');
        var img = productWrap.find('.j-image-to-animate');
        var imgPosTop = item.offset().top + 15;
        var imgPosLeft = item.offset().left + 5;
        var toolBarTop = $('.j-toolbar').offset().top + 30;
        var animatedImg = img.clone();
        animatedImg.appendTo('body').addClass('animated-img').css({
            "left": imgPosLeft,
            "top": imgPosTop
        });
        var timeLine = new TimelineMax();
        timeLine.to(animatedImg, .5, { scale: 1, alpha: 1 })
            .to(animatedImg, 1, {top: toolBarTop, left: compareLeft})
            .to(animatedImg, 1, {scale: 0, alpha: 0, delay: -.5, onComplete: function () {
                animatedImg.remove();
            }});

    }


    $.get('/catalog/ajax/compare.php', {
        action: 'ADD_TO_COMPARE_LIST_AJAX',
        id: goodsId
    }, function (response) {
        var data = $.parseJSON(response)
        if (data.success == 1) {
            item.attr('title', 'Удалить из сравнения');
            item.find("img").attr('alt', 'Удалить из сравнения');
            item.removeClass('j-add-to-compare').addClass('j-delete-from-compare');
            item.addClass('active');
            $('.j-delete-from-compare').unbind('click').bind('click', deleteCompareAjax);
            $('.j-compare-count').html(data.count);
            $('.j-compare-list ul').owlCarousel({
                items: 5,
                itemsDesktop: false,
                itemsDesktopSmall: false,
                itemsTablet: false,
                itemsMobile: false,
                pagination: false,
                navigation: true,
                navigationText: false
            });
            $('.toolbar').trigger('reload', ['compare']);
        }
    });
    return false;
};

function deleteCompareAjax(e, callback) {
    //var goodsId = $(this).attr('rel');
    //var item = $(".j-delete-from-compare[rel=" + goodsId + "]");
    var goodsId = $(this).attr('data-id');
    var item = $(".j-delete-from-compare[data-id=" + goodsId + "]");
    $.get('/catalog/ajax/compare.php', {
        action: 'DELETE_FROM_COMPARE_LIST_AJAX',
        ID: goodsId
    }, function (response) {
        var data = $.parseJSON(response);
        if (typeof(callback) == 'function') {
            callback.call(this, [response]);
        }
        if (data.success == 1) {
            item.attr('title', 'Добавить в сравнение');
            item.find("img").attr('alt', 'Добавить в сравнение');
            item.addClass('j-add-to-compare').removeClass('j-delete-from-compare');
            item.removeClass('active');
            $('.j-add-to-compare').unbind('click').bind('click', addCompareAjax);
            $('.j-compare-count').html(data.count);
            $('.j-compare-list ul').owlCarousel({
                items: 5,
                itemsDesktop: false,
                itemsDesktopSmall: false,
                itemsTablet: false,
                itemsMobile: false,
                pagination: false,
                navigation: true,
                navigationText: false
            });
            $('.toolbar').trigger('reload', ['compare']);
        }
    });
    return false;
};