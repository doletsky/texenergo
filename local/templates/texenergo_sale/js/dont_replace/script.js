$(function () {

    // PRODUCTS TABLE SLIDER
    $('#productsTable').owlCarousel({
        items: 1,
        pagination: true,
        navigation: true,
        navigationText: false,
        slideSpeed: 500,
        mouseDrag: false
    });

    var carouselPaginator = $('.j-owl-pagination').find('.owl-page');

    var iterator = 0;

    carouselPaginator.each(function () {

        $(this).attr('data-owl-page', iterator);

        iterator++;

    });

    //Custom select
    $('select.j-sort-select').select2();
    $('select.j-catalog-filter').select2({
        dropdownCssClass: 'dark-select'
    });


    //Products analog carousel
    var owl = $(".j-analog-carousel");

    owl.owlCarousel({
        items: 1,
        itemsMobile: false,
	responsive: false,
        pagination: false
    });

    $(".accessories-right").click(function (e) {
        e.preventDefault();
        owl.trigger('owl.next');
    })
    $(".accessories-left").click(function (e) {
        e.preventDefault();
        owl.trigger('owl.prev');
    })


    //Additional analog carousel
    var owlAdditional = $(".j-additional-carousel");

    owlAdditional.owlCarousel({
        items: 1,
        itemsMobile: false,
	responsive: false,
        pagination: false
    });

    $(".additional-right").click(function (e) {
        e.preventDefault();
        owlAdditional.trigger('owl.next');
    })
    $(".additional-left").click(function (e) {
        e.preventDefault();
        owlAdditional.trigger('owl.prev');
    })

    //Accessory carausel
    var owlAccessory = $('.j-accessory-carousel');

    owlAccessory.owlCarousel({
        items: 1,
        itemsMobile: false,
        pagination: true,
        paginationNumbers: true,
        afterInit: function () {
            $('.accessory-wrap .owl-controls').clone(true, true).prependTo($('.accessory-wrap .j-accessory-carousel'));
        }
    });

    $('.accessory-wrap .owl-pagination').after('<a href="#" class="j-accessory-right accessory-right"></a>');
    $('.accessory-wrap .owl-pagination').before('<a href="#" class="j-accessory-left accessory-left"></a>');

    $(".j-accessory-right").click(function (e) {
        e.preventDefault();
        owlAccessory.trigger('owl.next');
    })
    $(".j-accessory-left").click(function (e) {
        e.preventDefault();
        owlAccessory.trigger('owl.prev');
    })

    var owlOffer = $('.j-catalog-offer');

    owlOffer.owlCarousel({
        items: 1,
        itemsMobile: false,
        pagination: false,
        paginationNumbers: true
    });

    $(".j-offer-right").click(function (e) {
        e.preventDefault();
        owlOffer.trigger('owl.next');
    })
    $(".j-offer-left").click(function (e) {
        e.preventDefault();
        owlOffer.trigger('owl.prev');
    })

    //Analogs height

    function setEqualHeight(columns) {
        var maxColumnHeight = 0;
        columns.each(function () {
            var currentHeight = $(this).height();

            if (currentHeight > maxColumnHeight) {
                maxColumnHeight = currentHeight;
            }

        });
        columns.height(maxColumnHeight);
    }

    var analogNameWrap = $('.j-name-wrap'),
        priceWrap = $('.j-price-wrap'),
        technicals = $('.j-pTechnicals')
    accessory = $('.j-pAccessories');

    var hash = window.location.hash;

    if (hash == '#tAnalogs') {

        setEqualHeight(analogNameWrap);
        setEqualHeight(priceWrap);
        setEqualHeight(technicals);

    } else if (hash == "#tMarkdown") {
        //$('.discounted-block').removeClass('hidden');
    }


    $('#tabContainer').bind('easytabs:after', function () {

        var hash = window.location.hash;

        if (hash == "#tAnalogs") {

            setTimeout(function () {

                setEqualHeight(analogNameWrap);
                setEqualHeight(priceWrap);
                setEqualHeight(technicals);

            }, 1);

        }

    });

    $('#tabContainer').bind('easytabs:after', function () {

        var hash = window.location.hash;

        /*if (hash == "#tDiscounts") {
            $('.discounted-block').removeClass('hidden');
        } else {
            $('.discounted-block').addClass('hidden');
        }*/


    });

    //Hide name on product card hover
    $('.j-with-photos').hover(function () {
        $(this).find('.product-name').css('opacity', '0');
    }, function () {
        $(this).find('.product-name').css('opacity', '1');
    });

    $('.j-compare-different').click(function () {
        var href = $(this).data('href');
        window.location.href = href;
    });


    // $('.j-open-subcategory').click(function(e) {
    // 	e.preventDefault();

    // 	var subList = $(this).parent().next('.sidebarSearch-sub');

    // 	if (subList.hasClass('visible')) {
    // 		subList.hide('slow').removeClass('visible');
    // 	} else {
    // 		subList.show('slow').addClass('visible');
    // 	}
    // });


});