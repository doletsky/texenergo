$(function () {

	Share = {
		vkontakte: function(purl, ptitle, pimg, text) {
			url = 'http://vkontakte.ru/share.php?';
			url += 'url=' + encodeURIComponent(purl);
			url += '&title=' + encodeURIComponent(ptitle);
			url += '&description=' + encodeURIComponent(text);
			url += '&image=' + encodeURIComponent(pimg);
			url += '&noparse=true';
			Share.popup(url);
		},
		odnoklassniki: function(purl, text) {
			url = 'http://www.odnoklassniki.ru/dk?st.cmd=addShare&st.s=1';
			url += '&st.comments=' + encodeURIComponent(text);
			url += '&st._surl=' + encodeURIComponent(purl);
			Share.popup(url);
		},
		facebook: function(purl, ptitle, pimg, text) {
			url = 'http://www.facebook.com/sharer.php?';
			url += '&u=' + encodeURIComponent(purl);
			Share.popup(url);
		},
		twitter: function(purl, ptitle) {
			url = 'http://twitter.com/share?';
			url += 'text=' + encodeURIComponent(ptitle);
			url += '&url=' + encodeURIComponent(purl);
			url += '&counturl=' + encodeURIComponent(purl);
			Share.popup(url);
		},
		mailru: function(purl, ptitle, pimg, text) {
			url = 'http://connect.mail.ru/share?';
			url += 'url=' + encodeURIComponent(purl);
			url += '&title=' + encodeURIComponent(ptitle);
			url += '&description=' + encodeURIComponent(text);
			url += '&imageurl=' + encodeURIComponent(pimg);
			Share.popup(url)
		},
		popup: function(url) {
			window.open(url,'','toolbar=0,status=0,width=626,height=436');
		}
	};

	$('.stop-tracking-all').click(function(){
		var userId = $(this).data("userid");
		var data = {'action' : 'off', 'user_id' : userId, 'id' : 'all'};
		$.post('/ajax/product_tracking.php', data, function(data){

		});
	});

	//callback
	$('body').on('popup_init', function (e, wrap) {
        $('input[name=form_text_7]', wrap).each(function(){
			if(!$(this).prev().hasClass('unmask')){
				$(this).mask("+7(999) 999-99-99");
			}
		});

		$('input[name=form_text_6]', wrap).mask("+7(999) 999-99-99");
		$('input[name=form_text_14]', wrap).mask("+7(999) 999-99-99");
		$('input[name=form_text_9]', wrap).mask("+7(999) 999-99-99");
        $('.form-row.required .inputtext', wrap).addClass('required');

    });

	// Catalog page

    $('.j_owl_slider_5').owlCarousel({
        items: 5,
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false,
        pagination: false,
        navigation: true,
        navigationText: false,
        slideSpeed: 500,
        afterInit: function () {
            $(window).load(function () {
                $(".j_owl_slider_5").each(function () {
                    var parentHeight = $(this).height();
                    $(this).find(".j_owl_slider_item").height(parentHeight);
                });
            });
        }
    });

    $('.j_owl_slider_1').owlCarousel({
        items: 1,
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false,
        pagination: false,
        navigation: true,
        navigationText: false,
        slideSpeed: 500,
        afterInit: function () {
            $(window).load(function () {
                $(".j_owl_slider_1").each(function () {
                    var parentHeight = $(this).height();
                    $(this).find(".j_owl_slider_item").height(parentHeight);
                });
            });
        }
    });

    $('.j_owl_slider_3').owlCarousel({
        items: 3,
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false,
        pagination: false,
        navigation: true,
        navigationText: false,
        slideSpeed: 500
    });

    $('.j_owl_slider_4').owlCarousel({
        items: 4,
        itemsDesktop: false,
        itemsDesktopSmall: false,
        itemsTablet: false,
        itemsMobile: false,
        pagination: false,
        navigation: true,
        navigationText: false
    });

    $(".j_cat_more").click(function () {
        var owl = $(this).parents(".j_cat_block").find(".j_owl_slider_1").data('owlCarousel');
        owl.destroy();
        $(this).parents(".j_cat_block").addClass("full-list");
        return false;
    });

    $('#js-search').keypress(function (e) {
        if (e.which == 13) {
            return false;
        }
    });

    $('.j-delivery-type').unbind('click').bind('click', function () {
        var id = parseInt($(this).attr('rel'), 10);
        $('.j-stores-3').css('display', 'none');
        $('.j-stores-1').css('display', 'none');
        $('.j-stores-2').css('display', 'none');
        $('#j-store-id').val(0);
        if (id == 1 || id == 2) {
            $('.j-order-properties').css('display', 'block');
            $('.j-stores-' + id).css('display', 'block');
        } else if (id == 3) {
            $('.j-order-properties').css('display', 'none');
            $('.j-stores-' + id).css('display', 'block');
        }
    });

    $('.j-store').unbind('click').bind('click', function () {
        $('#j-store-id').val(parseInt($(this).attr('rel'), 10));
    });

    $('.j-catalog-sort-id').on('change', function () {
        var typeId = parseInt($(this).val(), 10);
        setCatalogSortCookie(typeId);
        //window.location.reload();
        window.location = window.location.pathname;
    });

    $('.j-catalog-view-id').on('change', function () {
        setCatalogViewCookie($(this).val());
        window.location.reload();
    });

	$('.j-catalog-view-id-special').on('change', function () {
        setCatalogViewCookieSpecial($(this).val());
        window.location.reload();
    });


    $(".j_dealer_link").click(function () {
        $(this).parents(".j_dealer_block").find(".j_dealer_toggle").toggleClass("hidden");
    });

    $(".j_preview_img").click(function () {
        var previewLink = $(this).find("img").attr("src");
        var idItem = $(this).data("id");
        if (previewLink) {
            $(".j_preview_img").removeClass("active");
            $(this).addClass("active");
            $(".j_preview_img_main_" + idItem).attr("src", previewLink);
        }
    });


    $(".j_item_tab li").click(function () {
        if ($(this).find("a.tLine").size() > 0 || $(this).find("a.tAnalogs").size() > 0) {
            $(".j_comments_block").addClass("hidden");
        } else {
            $(".j_comments_block").removeClass("hidden");
        }
    });
    if (window.location.hash == "#tLine" || window.location.hash == "#tAnalogs") {
        $(".j_comments_block").addClass("hidden");
    } else {
        $(".j_comments_block").removeClass("hidden");
    }

    registerBlock();
    orderProfileBlock();
    subscribeGoodsBlock();
    compareBlock();
    setCompareParams();



});

var addGoodsSubscribeAuth = function (e) {
    var goodsId = $(this).attr('rel');
    var item = $(".j-auth-add-to-subscribe[rel=" + goodsId + "]");
    $.get('/personal/subscribe_goods_ajax.php', {
        act: 'add',
        goodsId: goodsId
    }, function (response) {
        var data = $.parseJSON(response)
        if (data.success == 1) {
            item.attr('title', 'Отписаться от товара');
            item.find("img").attr('alt', 'Отписаться от товара');
            item.removeClass('j-auth-add-to-subscribe').addClass('j-auth-delete-from-subscribe');
            item.addClass('active');
            $('.j-auth-delete-from-subscribe').unbind('click').bind('click', deleteGoodsSubscribeAuth);
        }
    });
    return false;
};

var deleteGoodsSubscribeAuth = function (e) {
    var goodsId = $(this).attr('rel');
    var item = $(".j-auth-delete-from-subscribe[rel=" + goodsId + "]");
    $.get('/personal/subscribe_goods_ajax.php', {
        act: 'delete',
        goodsId: goodsId
    }, function (response) {
        var data = $.parseJSON(response)
        if (data.success == 1) {
            item.attr('title', 'Подписаться на товар');
            item.find("img").attr('alt', 'Подписаться на товар');
            item.addClass('j-auth-add-to-subscribe').removeClass('j-auth-delete-from-subscribe');
            item.removeClass('active');
            $('.j-auth-add-to-subscribe').unbind('click').bind('click', addGoodsSubscribeAuth);
        }
    });
    return false;
};

var addNewGoodsSubscribe = function (e) {
    var goodsId = $(this).attr('rel');
    var item = $(".j-add-to-goods-subscribe");
    $.get('/personal/subscribe_goods_ajax.php', {
        act: 'add_new_goods'
    }, function (response) {
        var data = $.parseJSON(response)
        if (data.success == 1) {
            item.attr('title', 'Отписаться от рассылки');
            item.find("img").attr('alt', 'Отписаться от рассылки');
            item.removeClass('j-add-to-goods-subscribe').addClass('j-delete-from-goods-subscribe');
            item.addClass('active');
            $('.j-delete-from-goods-subscribe').unbind('click').bind('click', deleteNewGoodsSubscribe);
        }
    });
    return false;
};

var deleteNewGoodsSubscribe = function (e) {
    var goodsId = $(this).attr('rel');
    var item = $(".j-delete-from-goods-subscribe");
    $.get('/personal/subscribe_goods_ajax.php', {
        act: 'delete_new_goods'
    }, function (response) {
        var data = $.parseJSON(response)
        if (data.success == 1) {
            item.attr('title', 'Подписаться на рассылку');
            item.find("img").attr('alt', 'Подписаться на рассылку');
            item.addClass('j-add-to-goods-subscribe').removeClass('j-delete-from-goods-subscribe');
            item.removeClass('active');
            $('.j-add-to-goods-subscribe').unbind('click').bind('click', addNewGoodsSubscribe);
        }
    });
    return false;
};

function setCompareParams() {
    $.get('/catalog/ajax/compare.php', {
        action: 'GET_IDS'
    }, function (response) {
        var data = $.parseJSON(response)
        if (data.success == 1) {

            var items = $('.j-delete-from-compare');
            items.attr('title', 'Добавить в сравнение');
            items.find("img").attr('alt', 'Добавить в сравнение');
            items.addClass('j-add-to-compare').removeClass('j-delete-from-compare');
            items.removeClass('active');
            items.unbind('click').bind('click', addCompareAjax);


            var ids = data.ids;
            for (var i in ids) {
                var goodsId = ids[i];
                var item = $(".j-add-to-compare[data-id=" + goodsId + "]");
                if (item) {
                    item.attr('title', 'Удалить из сравнения');
                    item.find("img").attr('alt', 'Удалить из сравнения');
                    item.removeClass('j-add-to-compare').addClass('j-delete-from-compare');
                    item.addClass('active');
                    item.unbind('click').bind('click', deleteCompareAjax);
                }
            }

        }
    });
}


function compareBlock() {
    $('.j-delete-from-compare').on('click', deleteCompareAjax);
    $('.j-add-to-compare').on('click', addCompareAjax);
};

function subscribeGoodsBlock() {
    $('.j-auth-add-to-subscribe').unbind('click').bind('click', addGoodsSubscribeAuth);
    $('.j-auth-delete-from-subscribe').unbind('click').bind('click', deleteGoodsSubscribeAuth);

    $('.j-add-to-goods-subscribe').unbind('click').bind('click', addNewGoodsSubscribe);
    $('.j-delete-from-goods-subscribe').unbind('click').bind('click', deleteNewGoodsSubscribe);
};


function registerBlock() {
    $('.j-juridical-person').bind('change', function () {
        if ($(this).is(':checked') == true) {
            $(this).val(1);
            $('.j-juridical-field').css('display', 'block');
        } else {
            $(this).val(0);
            $('.j-juridical-field').css('display', 'none');
        }
    });
};

function setCookie(name, value, options) {
    options = options || {};

    var expires = options.expires;

    if (typeof expires == "number" && expires) {
        var d = new Date();
        d.setTime(d.getTime() + expires*1000);
        expires = options.expires = d;
    }
    if (expires && expires.toUTCString) {
        options.expires = expires.toUTCString();
    }

    value = encodeURIComponent(value);

    var updatedCookie = name + "=" + value;

    for(var propName in options) {
        updatedCookie += "; " + propName;
        var propValue = options[propName];
        if (propValue !== true) {
            updatedCookie += "=" + propValue;
        }
    }

    document.cookie = updatedCookie;
}


function getCookie(name) {
    var matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCatalogViewCookie(value) {
    var date = new Date();
    var lifetime = 0;
    var expiresDate = new Date((date.getTime() + lifetime) * 1000);
    setCookie('catalog_view', value, expiresDate, '/');
};

function setCatalogViewCookieSpecial(value) {
    var date = new Date();
    var lifetime = 0;
    var expiresDate = new Date((date.getTime() + lifetime) * 1000);
    setCookie('catalog_view_special', value, expiresDate, '/');
};

function setCatalogSortCookie(value) {
    var date = new Date();
    var lifetime = 0;
    var expiresDate = new Date((date.getTime() + lifetime) * 1000);
    setCookie('catalog_sort', value, expiresDate, '/');
};

function setGoodsListViewCookie(value) {
    var date = new Date();
    var lifetime = 0;
    var expiresDate = new Date((date.getTime() + lifetime) * 1000);
    setCookie('goods_full_view', value, expiresDate, '/');
};

function in_array(needle, haystack) {
    var key = -1;
    var found = -1;
    for (key in haystack) {
        if (haystack[key] == needle) {
            found = key;
            break;
        }
    }
    return found;
};


function orderProfileBlock() {
    $('.j-delete-profile').unbind('click').bind('click', function (e) {
        var profileId = parseInt($(this).attr('rel'), 10);
        $.get('/personal/delete_profile_ajax.php', {
            profileId: profileId
        }, function (response) {
            var data = $.parseJSON(response)
            if (data.success == 1) {
                $('#j-tr-profile-' + profileId).remove();
            }
        });
        return false;
    });
    $('.j-chose-profile').unbind('click').bind('click', function (e) {
            var profileId = parseInt($(this).val(), 10);
            if (profileId != 'NaN') {
                var locationId = parseInt($('.j-location-profile-' + profileId).val(), 10);
                if (locationId != 'NaN') {
                    $('input:radio[name="DELIVERY_LOCATION"]').prop('checked', false);
                    $('input:radio[name="DELIVERY_LOCATION"]').filter('[value="' + locationId + '"]').prop('checked', true);
                    SetContact(false);
                }
            }
        }
    );
};
