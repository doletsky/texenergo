$(function () {
    var timeout;

    $(document).on('keyup', '.input-basket-count', function () {
        var $this = $(this),
            product = $this.data('product'),
            rollover = $this.parents('.rollover');

        if (typeof timeout !== 'undefined') {
            clearTimeout(timeout);
        }
        timeout = setTimeout(function () {
            var val = $this.val();
            if (parseInt(val) == 0) {
                $('.removeProduct[data-product="' + product + '"]').click();
            }
            else {
                $.get($this.data('href') + "&quantity=" + val, {}, function (responseJSON) {
                    $('#cart').load('/basket/ajax/small.php');
                    if (responseJSON.success) {
                        if (rollover.length > 0) {
                            var message = $("<div class='addNote arrow_box'>В корзине " + val + " " + pluralStr(val, 'товар', 'товара', 'товаров') + "</div>");
                            rollover.append(message);
                            setTimeout(function () {
                                message.fadeOut(function(){
                                    $(this).remove();
                                });
                            }, 1000);
                        }
                    }
                }, 'json');
            }
        }, 200);
    });

    $(document).on('click', '.removeProduct', function () {
        var $this = $(this),
            product = $this.data('product'),
            rollover = $this.parents('.rollover');

        $.get($this.data('href'), {}, function (responseJSON) {
            $('#cart').load('/basket/ajax/small.php');
            if (responseJSON.success) {
                rollover.removeClass('active');
                $('.input-basket-count[data-product="' + product + '"]').val('0');
            }
        }, 'json');
    });

    $(document).on('page.basket.update', function () {
        $.ajax({
            url: '/basket/ajax/',
            dataType: 'json',
            type: 'post',
            data: {
                action: 'list'
            },
            success: function (result) {
                if (result.success) {
                    for (var i in result.items) {
                        $input = $('.input-basket-count[data-product="' + i + '"]');
                        $rollover = $input.parents('.rollover');
                        $rollover.addClass('active');
                        $input.val(result.items[i].QUANTITY);
                    }
                }
            }
        });
    });

    $(document).trigger('page.basket.update');

    $('body').on('click', '.basket-add', function (e) {
        e.preventDefault();

        var that = $(this),
            flipper = $(this).parents('.rollover'),
            animateId = $(this).data('picture');

        if (animateId) {
            var productPicture = $('#' + animateId),
                animatedImg = productPicture.clone(),
                targetPos = $('.main-nav .cart').offset();


            animatedImg.appendTo('body').addClass('animated-img').css({
                left: e.clientX,
                top: e.clientY + $(window).scrollTop()
            });

            var timeLine = new TimelineMax();
            timeLine.to(animatedImg, .5, {scale: 1, alpha: 1})
                .to(animatedImg, 1, {top: targetPos.top, left: targetPos.left})
                .to(animatedImg, 1, {
                    scale: 0, alpha: 0, delay: -.5, onComplete: function () {
                        animatedImg.remove();
                    }
                });
        }

        $.get(this.href, {}, function (responseJSON) {

            $('#cart').load('/basket/ajax/small.php');

            if (responseJSON.success) {
                flipper.addClass('active');
                $('.input-basket-count', flipper).val("1");
                $('.input-basket-count', flipper)[0].select();

                if (that.attr('text_inside') == 'y')
                    that.text(newText);
            }

        }, 'json');


        return false;
    });


});

function pluralStr(i, str1, str2, str3) {
    function plural(a) {
        if (a % 10 == 1 && a % 100 != 11) return 0
        else if (a % 10 >= 2 && a % 10 <= 4 && ( a % 100 < 10 || a % 100 >= 20)) return 1
        else return 2;
    }

    switch (plural(i)) {
        case 0:
            return str1;
        case 1:
            return str2;
        default:
            return str3;
    }
}