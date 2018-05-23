$(function () {
	
	$('.doOrderBtn').click(function(e){
		if($(this).hasClass('inactive')){
			e.preventDefault();
			return false;
		}
		return true;
	});

    $('#sale_agreement_accept').click(function(){
		if($(this).is(':checked')){
			$('.doOrderBtn').removeClass('inactive');
		}else{
			$('.doOrderBtn').addClass('inactive');
		}
	});
	
	var basket = $('#order_basket');

    basket.on('init', function () {
        $('.quantity', this).Quantity();
    });

    window.qtyTimer = null;
    basket.on('change', '.quantity', function () {
        var that = $(this);
		var controlsContainer = $(this).next('.quantity-container');		
		
		
        clearTimeout(window.qtyTimer);
        window.qtyTimer = setTimeout(function () {
            var id = that.data('id'),
                val = that.val();
			if (val.length && val > 0){
				that.attr('busy', 'y');
				$.post('/basket/ajax/', {basket_id: id, quantity: val, action: 'update'}, function (response) {
					that.attr('busy', 'n');
					if (response.success == true) {
						basket.load('/basket/', {basket_refresh: 'yes'}, function () {
							basket.trigger('init');
						});
						$('#cart').load('/basket/ajax/small.php');
					} else {
						var error = response.error || 'Ошибка. Не удалось обновить корзину';
						alert(error);
					}
				}, 'json');
			}
        }, 500);
    });

    basket.on('click', '.close-item', function (e) {
        e.preventDefault();
        var id = $(this).data('id'),
            row = $(this).closest('.cartItem');
        $.get('/basket/ajax/', {action: 'delete', basket_id: id}, function () {
            $('#cart').load('/basket/ajax/small.php');
            row.slideUp(150, function () {
                row.remove();
                if ($('.cartItem', basket).size() <= 0) {
                    document.location.reload();
                }
            });
            basket.load('/basket/', {basket_refresh: 'yes'}, function () {
                basket.trigger('init');
            });
        }, 'json');
        return false;
    });


    basket.trigger('init');


    $('body').on('popup_init', function (e, wrap) {
        $('input[name=form_text_1]', wrap).mask("+7(999) 999-99-99");
        $('.form-row.required .inputtext', wrap).addClass('required');

    });

});