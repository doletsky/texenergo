$(function () {


	$('.track-trigger').click(function(e){
		var productId = $(this).data("product");
		var userId = $(this).data("userid");
		var data = {'id' : productId, 'user_id' : userId};
			
		if($(this).hasClass('active')){						
			data.action = 'off';
			$('#product-track-trigger').removeClass('active');
		}else{
			data.action = 'on';
			$('#product-track-trigger').addClass('active');
		}
		
		$.post('/ajax/product_tracking.php', data, function(data){});
	});


    $('.j_switch_img_item:first').removeClass('hidden');

    $(".j_switch_img_link").on('click', function () {
        var dataSwitch = $(this).hasClass("on") ? 1 : 2,
            block = $(this).closest(".j_switch_img_block");
			
        $(".j_switch_img_item[data-switch!=" + dataSwitch + "]", block).addClass("hidden");
        $(".j_switch_img_item[data-switch=" + dataSwitch + "]", block).removeClass("hidden");
		
		$(this).toggleClass("on");
    }).trigger('click');

    $(".j_rounded_reviews").on("click", function () {
        $(".j_reviews_form").show();
        $(".j_show_reviews_form").trigger("click");
    });

    $('#tabContainer').easytabs();


    $(document).on('click', '#fileSwitch', function () {

        if ($(this).hasClass('on')) {

            $('#block-publications').removeClass('hidden');
            $('#block-files').addClass('hidden');

        } else {

            $('#block-publications').addClass('hidden');
            $('#block-files').removeClass('hidden');

        }

    });

    $('.j-detail-product-img').on('click', 'img', function () {

        $('#galleria-box').removeClass('hidden');

        galleria = true;


    });

     $('.j_file_certificate').hover(function(){

	$(this).next().show();

    }, function(){
       $(this).prev().hide();
    }
);
     $('.no-sertificate').hover(function(){

    }, function(){
       $(this).hide();
    }
);
 

   $(document).on('click', '#close-gallery', function () {

        $(this).closest('#galleria-box').addClass('hidden');

    });

	$('body').on('click', '.fancybox-inner .fancybox-image', function(){
		$.fancybox.next();
	});
	
    $('a[data-fancybox-group=fancybox-thumb]').fancybox({
        autoHeight: true,
        autoSize: true,
        fitToView: true,
        live: true,
        padding: 10,
        margin: [60, 20, 100, 20],
        maxWidth: 1600,
        title: null,
        scrolling: 'no',
        type: 'image',		
        helpers: {
            title: {
                type: 'outside'
            },
            thumbs: {
                width: 70,
                height: 70
            }
        }
    });

    $('.product-photos .photo').on('mouseenter', function (e) {
        e.preventDefault();
        $('.product-photos .photo').removeClass('active');
        $(this).addClass('active');

        $('.j-detail-product-img .zoom').attr('href', $(this).attr('href'));
        $('.j-detail-product-img .zoom img').attr('src', $(this).data('preview'));
        $('.j-detail-product-img .zoom').css('background-image', 'url(' + $(this).data('preview') + ')');

        return false;
    });

    $('.product-photos .photo').first().trigger('mouseenter');

    if ($('.product-photos').size() > 0) {
        $('.j-detail-product-img .zoom').on('click', function (e) {
            e.preventDefault();
            $('.product-photos .photo.active').trigger('click');
            return false;
        });
    }

});