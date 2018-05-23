var footerOpened = "closed";
var invoiceBox = "bars";
var galleria = "false";

$(document).ready(function () {

  $('.j-select').select2();

	//about/contacts
	function initContactsJs(){
		$('.b-contactCity .button').on('click', function (e) {
			e.preventDefault();
			$(this).removeClass('transparent');
			$(this).siblings().addClass('transparent');
			$('.b-contactsInCity').hide();
			$($(this).data('target')).show();
			return false;
		});

		$('.b-contactCity .button:first').trigger('click');


		$('.contactLink').click(function (e) {
			e.preventDefault();
			var boxId = $(this).attr('href');
			var employeeBox = $('#' + boxId);
			if(employeeBox.hasClass('hidden')){
				$('.epmloyee-list-box').addClass('hidden');
				employeeBox.removeClass('hidden');
			}else{
				employeeBox.addClass('hidden');
			}

		});


		$('.employee-img').fancybox();
	}

	 initContactsJs();

	 function initContatsMap(){
		$('.showMapLink').click(function(e){
			e.preventDefault();
			var href = $(this).attr('href');
			var targetDiv = $(href);
			$(this).parent('.b-contactAdress').find('.scheme').hide();
			if($(this).hasClass('hide')){
				targetDiv.show();
				$(this).removeClass('hide').addClass('active');
				$('.showMapLink').each(function(){
					var href1 = $(this).attr('href');
					if(href1 != href){
						$(this).addClass('hide').removeClass('active');
						var targetDiv = $(href1);
						targetDiv.hide();
					}
				});
			}else{
				targetDiv.hide();
				$(this).addClass('hide').removeClass('active');
			}
		});
	 }

	 initContatsMap();


	// main page
	$(".mainpage_banner_select .switch").on('click', function () {
        if($(this).hasClass("on")){
			var dataSwitch = 1;
			$(this).removeClass("on");
		}else{
			var dataSwitch = 2;
			$(this).addClass("on");
		}

        var block = $(this).closest(".hero");

        $(".heroSlider[data-switch!=" + dataSwitch + "]", block).addClass("hidden");
        $(".heroSlider[data-switch=" + dataSwitch + "]", block).removeClass("hidden");
		carousel = $('.heroSliderAction').data('owl.carousel');
		carousel.update();
    });

	$('#techenergo-more-btn').click(function(){
		if($(this).hasClass('collapsed')){
			$(this).removeClass('collapsed');
			$('#techenergo-more').show();
			$(this).closest('.mainpage_banners').animate({height:"1300px"}, 1000);
			$(this).text('Свернуть');
		}else{
			$(this).addClass('collapsed');
			$(this).closest('.mainpage_banners').animate({height:"660px"}, 1000, function(){
				$('#techenergo-more').hide();
			});
			$(this).text('Подробнее о техэнерго');
		}
	});

	$('#techenergo-less-btn').click(function(){
		$('#techenergo-more-btn').addClass('collapsed');
		$(this).closest('.mainpage_banners').animate({height:"660px"}, 1000, function(){
			$('#techenergo-more').hide();
		});
		$('#techenergo-more-btn').text('Подробнее о техэнерго');
	});


	/* var mpCarousel = $('#productsTableMp');
	mpCarousel.owlCarousel({
        items: 1,
        pagination: true,
        navigation: true,
        navigationText: false,
        slideSpeed: 500,
        mouseDrag: true,

		// afterMove:function(e){
			// var curPage = this.owl.currentItem;
			// console.log(curPage);

			// var btn = $('#mainpage_bot_pagination').find('button[slide_num=' + curPage + ']');
			// btn.trigger('click');
		// }
    }); */

	$('#mainpage_bot_pagination button').click(function(){
		var slideNum = $(this).attr('data-slide_num');
		$('#productsTableMp > li').hide();
		$('#productsTableMp > li[data-slide_num=' + slideNum + ']').show();
	});

/*
          var 	$topBar = $('.top-bar ul'),
                $topBar_menu = $('.top-bar #menu'),
                $login  = $('#login'),
                $manufacture    = $('#manufacture'),
                $catalog    = $('.j-market-menu-link'),
                $price_list     = $('.price-list'),
                $contacts   = $('#contacts'),
                $trigger = $('.trigger-nav'),
                $cart   = $('#cart'),
                $menu = $('.sidebar-responsive')
                ;
          $trigger.click(function() {
            // $(".container").toggleClass("open-menu");
            // $menu.slideToggle();
            if ($menu.is(":hidden")) {
                $menu.show('slow');
            } else {
                $menu.hide('slow');
            }
            return false;
          });
    function response_menu() {      
          
        if ( $(window).width() < 767 ) {
          // $menu.removeAttr('style');
          if($("#menu-responsive").length <1) {
            $sidebar_resp = $('.sidebar-responsive').append('<ul id="menu-responsive"></ul>');
            $menu_resp = $('#menu-responsive');
              $(".top-bar .phone").after($(".main-nav #cart"));
              $price_list.removeAttr("style");
              $menu_resp.prepend($login)
                .append($topBar_menu)
                .append($manufacture)
                .append($contacts)
                .append($catalog)
                .append($price_list);
          }
        } else {
            if ($(".top-bar #cart").length>0) {
                $(".main-nav .search").after($(".top-bar #cart"));
            }
            if ($("#menu-responsive #login").length>0) {
                $(".main-nav #cart").after($("#menu-responsive #login"));
            }
            if ($("#menu-responsive #menu").length>0) {
                $(".top-bar .phone").before($('#menu-responsive #menu'));
            }
            if ($("#menu-responsive #manufacture").length>0) {
                $(".top-bar .callback").after($("#menu-responsive #manufacture"));
            }
            if ($("#menu-responsive #contacts").length>0) {
                $(".top-bar #manufacture").after($("#menu-responsive #contacts"));
            }
            if ($("#menu-responsive .j-market-menu-link").length>0) {
                $(".main-nav .logo").after($("#menu-responsive .j-market-menu-link"));
            }
            if ($("#menu-responsive .price-list").length>0) {
                $(".main-nav .j-market-menu-link").after($("#menu-responsive .price-list"));
                $(".main-nav .price-list").attr("style","margin-right:150px")
            }
            if ($("#menu-responsive").length>0) {
                $("#menu-responsive").remove();
            }
        }
    }
*/


    // VARIABLES

    var heroSwitch = "anons";
    var vote = true;

    // SEARCHBOX

    $("#searchBar").focus(function () {
        $(".searchbox").removeClass("hidden");
        $(".manufacture-dropdown").addClass("hidden");
        $(".categories").addClass("hidden");
    }).blur(function () {
        $(".searchbox").addClass("hidden");
    });

    // MENU-DROP

function menu_view() {
    if ( $(window).width() > 768 ) {
    var insideCounterMenu = 0;

    $("li#menu").mouseenter(function () {
       insideCounterMenu++;
       $(".menu-drop").removeClass("hidden");
    });
    $(".menu-drop").mouseenter(function () {
       insideCounterMenu++;
    });

    $(".menu-drop").mouseleave(function () {
        insideCounterMenu--;
        setTimeout(function(){
            if(insideCounterMenu <= 0){
                $(".menu-drop").addClass("hidden");
            }
        }, 200);
    });
    $("li#menu").mouseleave(function () {
        insideCounterMenu--;
        setTimeout(function(){
            if(insideCounterMenu <= 0){
                $(".menu-drop").addClass("hidden");
            }
        }, 200);
    });

    $("li#menu a#menu-down").click(function(event) {
        event.preventDefault();
    });
    } else {
            $menu_drop = $('#menu-responsive .menu-drop');
            $menu_drop.css('position', 'static');
            $menu_drop.css('width', '100%');
            $menu_drop.css('background-color', 'inherit');
            $menu_drop.find("ul").css('margin', '0 0 0 10px').css('padding', '0').css('width', '100%');
            $menu_drop.find("li").css('width', '100%').css('margin','0').css('display','inline-block');
            $menu_drop.find("a").css('font-size','1em');
            $('#menu-down').click(function() {
            // $(".container").toggleClass("open-menu");
            // $menu.slideToggle();
            if ($menu_drop.is(":hidden")) {
                $menu_drop.removeClass("hidden");
            } else {
                $menu_drop.addClass("hidden");
            }
            // $menu_drop.show('slow');
            return false;
          });
    }
}

    //response_menu();
    menu_view();
    // $menu.hide();
	/*$(window).resize(function() {
    	response_menu();
        menu_view();
      });*/
    // MANUFACTURES
    /* var insideCounterM = 0;

    $("#manufactures-link").mouseenter(function () {
        insideCounterM++;
        $(".manufacture-dropdown").removeClass("hidden");
        $(".searchbox").addClass("hidden");
        $(".categories").addClass("hidden");
    });
	$(".manufacture-dropdown").mouseenter(function(){
		insideCounterM++;
	});

    $(".manufacture-dropdown").mouseleave(function () {
		insideCounterM--;
		setTimeout(function(){
			if(insideCounterM <= 0){
				$(".manufacture-dropdown").addClass("hidden");
			}
		}, 200);
    });
	$("#manufactures-link").mouseleave(function () {
		insideCounterM--;
		setTimeout(function(){
			if(insideCounterM <= 0){
				$(".manufacture-dropdown").addClass("hidden");
			}
		}, 200);
    }); */


    // CATEGORIES

    var insideCounter = 0;

	$("#categories-link").mouseenter(function () {
        insideCounter++;
		$(".categories").removeClass("hidden");
        $(".searchbox").addClass("hidden");
        $(".manufacture-dropdown").addClass("hidden");
    });
	$(".categories").mouseenter(function(){
		insideCounter++;
	});

    $(".categories").mouseleave(function () {
        insideCounter--;
		setTimeout(function(){
			if(insideCounter <= 0){
				$(".categories").addClass("hidden");
			}
		}, 200);
    });
	$("#categories-link").mouseleave(function(){
		insideCounter--;
		setTimeout(function(){
			if(insideCounter <= 0){
				$(".categories").addClass("hidden");
			}
		}, 200);
	});

    // SWITCH

    $(".view-switch").click(function () {
        $(this).toggleClass("on");
    });

    $("cat-man-switch").click(function () {
        $(this).toggleClass("on");
    });

    // HERO SWITCH

    $('.hero .switch').click(function () {
        if (heroSwitch === "anons") {
            $(this).css("background-position", "10px -21px");
            heroSwitch = "promo";
        } else {
            $(this).css("background-position", "center 1px");
            heroSwitch = "anons";
        }
    });

    // HERO SLIDER

	$('#heroSlider, .heroSliderAction').show();
$('.block-stock-main').show();
$('#heroSlider').cycle({
    speed: 1000,
    timeout: 3000,
    fx: 'fade',
    pauseOnHover: true,
    slides: '.slide',
    pagerEventBubble: true,
});
$('.advertising-news').cycle({
    speed: 5000,
    timeout: 3000,
    fx: 'fade',
    pauseOnHover: true,
    slides: '.slide',
});
$('.advertising-goods').cycle({
    speed: 5000,
    timeout: 3000,
    fx: 'fade',
    pauseOnHover: true,
    slides: '.slide',
});

$('.ads-news-main').cycle({
    speed: 500,
    timeout: 5000,
    //fx: 'scrollHorz',
    fx: 'fade',
    pauseOnHover: true,
    slides: '.slide',
});

/*	$('#heroSlider, .heroSliderAction').owlCarousel2({
		loop:true,
		items: 1,
		navText: [ '', '' ],
		// dots:false,
		autoplay:true,
		slideSpeed: 500,
		autoplayTimeout:8000,
		responsive:{
        768:{
			dots:true,
			nav: false,
        },
        1000:{
			dots:false,
			nav: true,
        }
	  }
    });
*/
	$('#heroSlider1').owlCarousel({
        loop:true,
		items: 1,
		navText: [ '', '' ],
		pagination: false,
		nav: true,
		dots:false,
		auto:true,
		navigationText: false,
		slideSpeed: 500
    });

	$('.grid-carousel').owlCarousel({
		items: 1,
		navigationText: [ '', '' ],
		navigation: true,
		pagination:false,
		slideSpeed: 500,
		responsive: false
    });

    // PROMOTIONS BUTTONS

    $(".promotions .rounded.first").click(function () {
        $(".promotions .rounded.first").addClass("selected");
        $(".promotions .rounded.second").removeClass("selected");
        $(".promotions .rounded.third").removeClass("selected");
        $(".promotions .rounded.forth").removeClass("selected");
    });

    $(".promotions .rounded.second").click(function () {
        $(".promotions .rounded.first").removeClass("selected");
        $(".promotions .rounded.second").addClass("selected");
        $(".promotions .rounded.third").removeClass("selected");
        $(".promotions .rounded.forth").removeClass("selected");
    });

    $(".promotions .rounded.third").click(function () {
        $(".promotions .rounded.first").removeClass("selected");
        $(".promotions .rounded.second").removeClass("selected");
        $(".promotions .rounded.third").addClass("selected");
        $(".promotions .rounded.forth").removeClass("selected");
    });

    $(".promotions .rounded.forth").click(function () {
        $(".promotions .rounded.first").removeClass("selected");
        $(".promotions .rounded.second").removeClass("selected");
        $(".promotions .rounded.third").removeClass("selected");
        $(".promotions .rounded.forth").addClass("selected");
    });

	// PROMO SLIDER

	function initPromoSlider(){
		$('#promo-slider').owlCarousel({
			items: 1,
			pagination: false,
			navigation: true,
			navigationText: false,
			slideSpeed: 500,
			autoPlay: true
		});
	}
	initPromoSlider();

    // VOTE INTERACTIONS

    $(".vote .question a").click(function (event) {
        event.preventDefault();
        if (vote === true) {
            $(".vote .answers").addClass("hidden");
            $(this).text("развернуть голосование");
            $(this).css("background-position", "right -15px");
            vote = false;
        } else {
            $(".vote .answers").removeClass("hidden");
            $(this).text("свернуть голосование");
            $(this).css("background-position", "right 0");
            vote = true;
        }
    });

    // NEWS SLIDER

    $('#news-slider').show();
    $('#news-slider').owlCarousel({
        items: 2,
        pagination: false,
        navigation: true,
        navigationText: false,
		responsive: true
		// responsive:{
  //       320:{
  //           items:2
  //       },
  //       800:{
  //           items:3
  //       },
  //       1000:{
  //           items:5
  //       }
  //   }
    });
    $('#publications-slider').owlCarousel({
        items: 4,
        pagination: false,
        navigation: true,
        navigationText: false,
		responsive: true
		// responsive:{
  //       320:{
  //           items:2
  //       },
  //       800:{
  //           items:3
  //       },
  //       1000:{
  //           items:5
  //       }
  //   }
    });

    // CATALOG TOGGLE


    // TABS

    $('#promoTabs').easytabs();
    $('#formTabs').easytabs();

    // PRODUCT PAGE SLIDER

    $('#owl').owlCarousel({
        items: 4,
        pagination: false,
        navigation: true,
        navigationText: false,
        responsive: false
    });
    $('#owl-2').owlCarousel({
        items: 4,
        pagination: false,
        navigation: true,
        navigationText: false,
        responsive: false
    });

    // PRODUCT PAGE PUBLICATIONS SLIDER

    $("#pub-carousel").owlCarousel({
        items: 1,
        pagination: false,
        navigation: true,
        navigationText: false,
        responsive: false
    });
    $("#pub-carousel-markdown").owlCarousel({
        items: 1,
        pagination: false,
        navigation: true,
        navigationText: false,
        responsive: false
    });

    // PROMO SLIDER

    $('#promo-slider').owlCarousel({
        items: 1,
        pagination: false,
        navigation: true,
        navigationText: false
    });

    // HELP PAGE INTERACTIONS

    //$('.helpLinks').toggle();
    //$('.toggleThis').toggle();

	function registerHelpPageHandlers(){
		$('.helpItem > header').click(function () {
			if(!$(this).hasClass('nochildren')){
				$(this).parent().children('.helpLinks').toggle();
			}
		});

		$('.questionHeadline .toggle_trigger').click(function (e) {
			if(!$(this).hasClass('nochildren')){
				e.preventDefault();
				$(this).parent().parent().parent().children('.questionDetails').toggle();
				$(this).toggleClass('is-questionDetail-opened');
			}
		});

		$('.helpItem-shipping .expand-all').click(function () {
			if($(this).hasClass('collapsed')){
				$(this).parent().find('.questionDetails').show();
				$(this).parent().find('.toggle_trigger').addClass('is-questionDetail-opened');
				$(this).removeClass('collapsed');
				$(this).text('Свернуть все');
			}else{
				$(this).parent().find('.questionDetails').hide();
				$(this).parent().find('.toggle_trigger').removeClass('is-questionDetail-opened');
				$(this).addClass('collapsed');
				$(this).text('Развернуть все');
			}
		});

		$('.helpLinks a.with_children').click(function(e){
			e.preventDefault();
			$(this).next('.helpSubLinks').toggle();
		});
	}
	registerHelpPageHandlers();

    // CONTACTS

    /* $('#contactLink-1').click(function (e) {
        e.preventDefault();
        $('#contactBox-1').toggleClass('hidden');
        $('#contactBox-2').addClass('hidden');
        $('#contactBox-3').addClass('hidden');
    });
    $('#contactLink-2').click(function (e) {
        e.preventDefault();
        $('#contactBox-1').addClass('hidden');
        $('#contactBox-2').toggleClass('hidden');
        $('#contactBox-3').addClass('hidden');
    });
    $('#contactLink-3').click(function (e) {
        e.preventDefault();
        $('#contactBox-1').addClass('hidden');
        $('#contactBox-2').addClass('hidden');
        $('#contactBox-3').toggleClass('hidden');
    }); */

    // SWITCHES

    $('.subSwitch:not(.jSubSwitch)').each(function () {
        $(this).addClass('jSubSwitch');
        $(this).click(function () {
            $(this).toggleClass('subSwitch-on');
            $(this).trigger('change');
        });
    });


    $('.side-category').click(function (e) {
        e.preventDefault();
        $(this).parent('.menu').children('.submenu').toggle();
        $(this).children('.more-icon').toggleClass('is-opened');
        $(this).toggleClass('is-opened');
    });

    // USER ACCOUNT INTERACTIONS
    $('.orderItem .switch-socials').click(function () {
        if (invoiceBox === "bars") {
            $(this).parents(".accountInvoice").find(".box-bars").toggle();
            $(this).parents(".accountInvoice").find(".box-docs").toggle();
        } else {
            $(this).parents(".accountInvoice").find(".box-bars").toggle();
            $(this).parents(".accountInvoice").find(".box-docs").toggle();
        }

    });


    // CATALOG BRANDS SLIDER
    $('#brands').owlCarousel({
        items: 1,
        pagination: false,
        navigation: true,
        navigationText: false,
        slideSpeed: 500
    });

    // делаем сайдбар меню

    $('.has-submenu').hover(function () {
            $(this)
                .children('ul')
                .removeClass('hidden');
        },
        function () {
            $(this)
                .children('ul')
                .addClass('hidden');
        });


    // закрываем popup при клике на крестик

    $(document).on('click', '.close-popup, .close-popup-button', function () {

        $(this)
            .parents('.box-popup')
            .addClass('hidden');

    });

    // работа селекта городов в попапах

    $(document).on('click', '.city-select', function () {

        $('.city-select')
            .removeClass('active');

        $(this)
            .toggleClass('active');

    });

    $('.one-click').on('click', '.close-popup', function () {

        $(this)
            .closest('.one-click')
            .addClass('hidden');

    });

	//PUBLICATION PAGE
	function registerVideoHandlers(){
		$('.video_url').each(function () {
			var url = $(this).attr('data-ajax_url');

			if(url && url.length > 0){
				$(this).fancybox({
					width: 'auto',
					height: 'auto',
					title: null,
					type: 'ajax',
					href: url
				});
			}

		});
	}
	registerVideoHandlers();

	$('a.history_button').click(function(e){
		e.preventDefault();
		window.history.back();
	});

	//INFO CENTER POPUP

	var historyStack = [];
	var originalUrl = window.location.href;
	var originalTitle = document.title;
	var infocenterUrl = '/infocenter/index.php';

	$('#info_center_link').click(function(){
		openPopup();
	});

	$('#infocenter_close').click(function(e){
		e.preventDefault();
		closePopup();
	});

	handleEscPress();
	registerBackButtonHandler('a.infocenter_back');
	registerBackButtonHandler('a.history_button');
	registerFormSubmitHandler();
	registerAClickHandler();

	$('#delivery_variants').click(function(e){
		e.preventDefault();
		openPopupOnUrl($(this).attr('href'));
	});

	function openPopupOnUrl(url){
		var doc = document.documentElement;
		var top = (window.pageYOffset || doc.scrollTop)  - (doc.clientTop || 0);

		$('#infocenter_shadow').show();
		$('#infocenter_popup').css('top', top);
		$('#infocenter_shadow').animate({'opacity': '1'}, 500);
		$('#infocenter_popup_content').html('');
		showLoadIndicator();
		animatePopup();
		getAjaxContent(url);
	}

	function openPopup(){
		bDefInit = true;
		if(supportsHtml5Storage()){//restore last state
			if(localStorage.infocenterHistory != undefined){
				var tmpHistory = JSON.parse(localStorage.infocenterHistory);
				if(tmpHistory.length > 0){
					historyStack = tmpHistory;
					bDefInit = false;
					var lastUrl = tmpHistory[tmpHistory.length - 1];
					$('#infocenter_shadow').show();
					$('#infocenter_popup').css('top', 0);
					$('#infocenter_shadow').animate({'opacity': '1'}, 500);
					$('#infocenter_popup_content').html('');
					showLoadIndicator();
					animatePopup();
					getAjaxContent(lastUrl);
					changeUrl('', lastUrl);
				}
			}
		}

		if(bDefInit){
			historyStack = [];
			historyStack.push(infocenterUrl);
			animatePopup();
		}

	}

	function showLoadIndicator(){
		loadIndicator = $('#infocenter_popup .load_indicator').clone();
		loadIndicator.css('display', 'block');
		$('#infocenter_popup_content').append('<div style="height:300px;"></div>');
		$('#infocenter_popup_content').append(loadIndicator);
	}

	function animatePopup(){
		$('#infocenter_popup').show();
		$('#infocenter_shadow').show();
		$('#infocenter_shadow').animate({'opacity': '1'}, 500);
		$('#infocenter_popup').animate({'right' : '50%'}, 500);
	}

	function closePopup(){
		changeUrl(originalTitle, originalUrl);
		$('#infocenter_popup').animate({'right' : '-485px'}, 500, function(){
			$('#infocenter_popup').hide();
			getAjaxContent(infocenterUrl);
		});
		$('#infocenter_shadow').animate({'opacity': '0'}, 500, function(){
			$('#infocenter_shadow').hide();
		});
	}

	function handleEscPress(){
		$('body').keydown(function(e){
			if(e.which == 27){
				closePopup();
			}
		});
	}

	function initCalendarFilter(){
		$('.b-filterPeriod em').on('click', function () {
			$('#filter_dates').slideToggle(300);
		});


		$('#filter_dates .calendar').DatePicker({
			format: 'Y.m.d',
			flat: true,
			date: [$('#orders_date_from').val(), $('#orders_date_to').val()],
			calendars: 2,
			mode: 'range',
			starts: 1,
			onChange: function (formated, dates) {
				$('#orders_date_from').val(formated[0]);
				$('#orders_date_to').val(formated[1]);
			}
		});
	}

	function initSlider3(){
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
	}

    function initSlider4(){
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
	}

	function initSlider5(){
		$('.j_owl_slider_5').owlCarousel({
			items: 5,
			itemsDesktop: false,
			itemsDesktopSmall: false,
			itemsTablet: false,
			itemsMobile: false,
			pagination: false,
			navigation: true,
			navigationText: false
		});
	}

	function registerBackButtonHandler(selector){
		$(selector).click(function(){
			if(historyStack.length > 1){
				current = historyStack.pop();
				prev = historyStack[historyStack.length - 1];
				getAjaxContent(prev);
			}else{
				getAjaxContent(infocenterUrl);
			}
		});
	}

	$('#infocenter_popup form.topform').submit(function(e){
		var action = $(this).attr('action');
		data = $(this).serialize();
		data += '&ajax_call=y';
		rememberState(action + '?' + data);
		$.post(action, data, function(data){
			insertContentToPopup(data);
		});
		e.preventDefault();
		return false;
	});

	function registerFormSubmitHandler(){
		$('#infocenter_popup_content form').submit(function(e){
			var handers = $._data($(this)[0], "events");
			var action = $(this).attr('action');
			var href = window.location.href;
			if(handers['submit'].length == 1 && $(this).attr('noajax') != 'y' && action != undefined && action.length > 0){
				data = $(this).serialize();
				data += '&ajax_call=y';
				$.post(action, data, function(data){
					var title = $(data).find('#ajax_title').text();
					rememberState(title, action + '?' + data);
					changeUrl(title, href);
					insertContentToPopup(data);
				});
				e.preventDefault();
				return false;
			}
		});
	}

	function registerAClickHandler(){
		$('#infocenter_popup a').click(function(e){
			var handers = $._data($(this)[0], "events");
			var href = $(this).attr('href');
			var target = $(this).attr('target');
			var bUnclickable = $(this).hasClass('no-popup');

			if(!bUnclickable && handers['click'].length == 1 && !$(this).hasClass('popup') && href != undefined && href.length > 0 && href != '#' && target != '_blank'){
				e.preventDefault();
				$.post(href, {'ajax_call': 'y'}, function(data){
					var title = $(data).find('h1.headline').text();
					rememberState(title, href);
					changeUrl(title, href);
					insertContentToPopup(data);
				}).fail(function(){
					getAjaxContent(infocenterUrl);
				});
			}
		});
	}

	function getAjaxContent(href, callback){
		if(!href || href == null || href.indexOf('/catalog/') != -1 || href.indexOf('/ajax/') != -1){
			href = infocenterUrl;
		}
		$.post(href, {'ajax_call': 'y'}, function(data){
			insertContentToPopup(data);
			if(callback != undefined){
				callback();
			}
		}).fail(function(){
			getAjaxContent(infocenterUrl, callback);
		});
	}



	function insertContentToPopup(data){
		$('#infocenter_popup_content').html(data);
		registerFormSubmitHandler();
		registerHelpPageHandlers();
		registerVideoHandlers();
		registerAClickHandler();
		initPromoSlider();
		initSlider3();
		initSlider4();
		initSlider5();
		initCalendarFilter();
		initContactsJs();
		registerMenuHandler();
		initContatsMap();
		initPopups();
		registerVacancyOpen();
		registerBackButtonHandler('a.history_button');
	}

	function initPopups(){
		$.extend($.fancybox.defaults, {
			width: 570,
			height: 'auto',
			maxWidth: 570,
			autoHeight: true,
			autoSize: false,
			fitToView: false,
			live: true,
			padding: 30,
			margin: [40, 40, 40, 40],
			title: null,
			scrolling: 'no',
			type: 'inline',
			afterShow: function () {
				$('body').trigger('popup_init', [this.wrap]);
			}
		});

		$('#infocenter_popup_content .popup').each(function () {
			var href = $(this).attr('href'),
				width = $(this).data('width') || '100%',
				height = $(this).data('height') || '100%',
				type = (href.substr(0, 1) == '#' ? 'inline' : 'ajax'),
				picsRegex = /(jpe?g|gif|png)$/i;

			if (picsRegex.test(href)) {
				type = 'image';
			}

			if($(this).hasClass('popup_cb')){
				paddingVal = [0, 30, 30, 30];
			}else{
				paddingVal = 30;
			}

			$(this).fancybox({
				width: width,
				height: height,
				maxWidth: width,
				title: null,
				padding: paddingVal,
				type: type
			});

		});
	}

	function registerVacancyOpen(){
		$('.questionHeadline .t_trigger').click(function (e) {
			if(!$(this).hasClass('nochildren')){
				e.preventDefault();
				$(this).parent().parent().parent().children('.questionDetails').toggle();
				$(this).prev('a.toggle_trigger').toggleClass('is-questionDetail-opened');
			}
		});
	}
	registerVacancyOpen();

	function registerMenuHandler(){
		$('#infocenter_popup_content .side-category').click(function (e) {
			e.preventDefault();
			$(this).parent('.menu').children('.submenu').toggle();
			$(this).children('.more-icon').toggleClass('is-opened');
			$(this).toggleClass('is-opened');
		});
	}

	function rememberState(title, href){
		historyStack.push(href);
		if(supportsHtml5Storage()){
			if(historyStack.length > 10){
				var cnt = historyStack.length - 10;
				historyStack.splice(0, cnt);
			}
			localStorage.infocenterHistory = JSON.stringify(historyStack);
		}
	}

	function changeUrl(title, href){
		if(supportsHtml5Storage()){
			var objPage = {
				'pageUrl': href,
				'title':title,
				'infocenter_popup': true
			};
			try{
			window.history.pushState(objPage, title, href);
			}catch(e){

			}
		}
	}

	function supportsHtml5Storage(){
		try{
			return 'localStorage' in window && window['localStorage'] !== null;
		}catch(e){
			return false;
		}
	}

  $('.b-reviews_form_opener').click(function(){
    $(this).toggleClass('opened');
    $(this).next('.b-reviews_form_content').slideToggle(200);
  });

	if(supportsHtml5Storage()){
    if( !$('html').hasClass('lt-ie9') && window.addEventListener) {
      window.addEventListener("popstate", function(e) {
        if (!e.state) return;
        window.location = window.location;
      });
    }
	}


    $('body').on('click', '.filter .sidebar-child.show-all', function (e) {
        e.preventDefault();
        $(this).prevAll('.collapse').slideDown(150);
        $(this).hide();
        return false;
    });

    $('.sidebar-header').click(function () {
        var header = $(this),
            items = header.parent().find('.sidebar-child');

        if (header.hasClass('collapsed')) {
            header.removeClass('collapsed');
            items.show();
            items.filter('.collapse').hide();
        } else {
            header.addClass('collapsed');
            items.hide();
        }
    });

    $('.min-price').bind("change keyup input click", function() {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

    $('.max-price').bind("change keyup input click", function() {
        if (this.value.match(/[^0-9]/g)) {
            this.value = this.value.replace(/[^0-9]/g, '');
        }
    });

	if($('.filter .sidebar-child select').length){
		$('.filter .sidebar-child select').select2({
			dropdownCssClass: 'dark-select'
		});
	}
// pop-up
        var NameBan = 'ban_cookie_2524';
        var BanCookie = getCookie(NameBan);
        if (typeof BanCookie == 'undefined')
   	    {
          var Dialog = new BX.CDialog({
              title:'Внимания! Акция!',
              head: false,
              content: '<a href="https://www.texenergo.ru/publication/novye_postupleniya/plombiruemye_klemmnye_zaglushki_tm_texenergo/"><img src="/upload/popup/popup02.png"></a>',
              icon: 'head-block',

              resizable: false,
              draggable: false,
              height: '434',
              width: '760',
              buttons: [BX.CDialog.prototype.btnClose]
          });
            Dialog.Show();
            var date = new Date();
            var expiresDate = new Date();
            expiresDate.setDate(expiresDate.getDate() + 7);
            var cookie_string = NameBan+"=1;path=/"
            cookie_string +=  "; expires=" + expiresDate.toGMTString();
            document.cookie = cookie_string;
          }

});

$(window).load(function(){
    $('.specials-carousel').each(function(){
        var $this = $(this),
            $block = $this.parents('.cat-block');

        $this.carouFredSel({
            circular: false,
            infinite: false,
            auto: {
                play: false
            },
            scroll: {
                items: 1
            },
            items: {
                visible: 1,
                height: 'variable'
            },
            width: '100%',
            height: 'variable',
            prev: $('.cat-arrow-left', $block),
            next: $('.cat-arrow-right', $block)
        });
    });
});
