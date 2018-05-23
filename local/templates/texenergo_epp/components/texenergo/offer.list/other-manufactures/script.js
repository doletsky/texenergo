$(function () {

    $('#local-manufactures-switch').click(function () {
        $(this).toggleClass("on");
        $('.other-brands .brand').hide();

        var visible = $(this).hasClass('on') ? $('.other-brands .brand.rus') : $('.other-brands .brand');

        visible.show();

        if (visible.size() < 12) {
            //$('.other-brands .brands .more').show();
			$('.show-all-brands').hide();
        } else {
           // $('.other-brands .brands .more').hide();
			$('.show-all-brands').show();
        }		
		
    });

    $('.other-brands .show-all-brands').on('click', function (e) {
        e.preventDefault();

        var more = $('.other-brands .brands .more'),
            table = $('.manufacture-dropdown .other-brands .brands');

        if (more.is(':visible')) {
            //table.css('min-height', table.height() + 'px');
            more.slideUp(300);			
			$(this).removeClass('open');
			$(this).text("Показать всех производителей");
        } else {
            more.slideDown(300);
			$(this).addClass('open');
			$(this).text("Свернуть");
        }


        return false;
    });

});