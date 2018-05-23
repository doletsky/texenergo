$(function () {
	
	$('.crumbsDisabled').click(function(e){
		e.preventDefault();
	});
	
	$("input[name='oter-delivery-type-selector']").click(function(){
		selectOtherDeliveryType();		
	});
	
	selectOtherDeliveryType();
	
	function selectOtherDeliveryType(){
		$("input[name='oter-delivery-type-selector']").each(function(){
			wrapBlock = $(this).closest('header').next('.other_type_wrap');
			
			if($(this).is(':checked')){									
				wrapBlock.removeClass('inactive');
				wrapBlock.find('.delivery_type_overlay').remove();	
				wrapBlock.find('input, select').each(function(){
					$(this).attr('name', $(this).attr('original_name'));					
				});
				wrapBlock.find('input.tmpRequired, select.tmpRequired').removeClass('tmpRequired').addClass('required');				
			}else{
				wrapBlock.append('<div class="delivery_type_overlay"></div>');
				wrapBlock.addClass('inactive');
				wrapBlock.find('input, select').each(function(){
					$(this).attr('original_name', $(this).attr('name'));
					$(this).attr('name', 'tmp_' + $(this).attr('name'));
				});
				wrapBlock.find('input.required, select.required').removeClass('required').addClass('tmpRequired');
				wrapBlock.find('input').val('');
			}
		});			
	}
	
	
	/* $('.crubmsDelivery').click(function(e){
		e.preventDefault();
		var formData = $('#order_form_confirm').serializeArray();
		for(i = 0; i< formData.length; i++){
			val = formData[i];
			if(val.name == 'confirm_order'){
				formData.splice(i, 1);
			}
		}
		formData[formData.length] = {'name':'create_order', 'value':'yes'};
	}); */

    $(".phone-mask").mask("+7(999) 999-99-99");


    $('.orderTabs').on('easytabs:after', function (e, tab, panel, settings) {
        if ($('#group_msc').is(':visible')) {
            var maxHeight = 0;
            $('.subTabs>ul>li>a').each(function () {
                if ($(this).height() > maxHeight) maxHeight = $(this).height();
            });

            $('.subTabs>ul>li>a').each(function () {
                var delta = maxHeight - $(this).height() + 20;
                $(this).css({
                    paddingTop: delta / 2 + 'px',
                    paddingBottom: delta / 2 + 'px'
                });
            });

            var box = $('.delivery-msc:visible');
            $('#deliveryTitle').html(box.data('title'));
            $('#deliveryPrice').html(box.data('price'));
            $('.delivery-msc:visible').trigger('calculate');
        }

        if ($('#group_later').is(':visible')) {
            $('.asideCourier').slideUp(300);
        } else {
            $('.asideCourier').slideDown(300);
        }

    });

    $('.orderTabs').each(function () {

        var that = $(this),
            defaultTab = $('>ul>li.preactive', that).size() > 0 ? 'li.preactive' : 'li:last-child';

        that.easytabs({
            updateHash: false,
            defaultTab: defaultTab
        });
        if (defaultTab == 'li.preactive') {
            setTimeout(function () {
                that.trigger('easytabs:after');
            }, 500);
        }
    });


    $('.selectProfile').on('click', function (e) {
        e.preventDefault();

        var profile = $(this).closest('.orderProfile');

        if (profile.hasClass('selected')) {
            profile.removeClass('selected');
            $(this).removeClass('orange');
        }
        else {
            $('.orderProfile.selected .selectProfile').removeClass('orange');
            $('.orderProfile.selected').removeClass('selected');
            profile.addClass('selected');
            $(this).addClass('orange');
        }

        if ($('.orderProfile.selected').size() > 0) {
            $('#deliveryForm').fadeOut(500, function () {
                $('#profilesForm').show();
            });
        } else {
            $('#profilesForm').hide();
            $('#deliveryForm').fadeIn(500);
        }

        $('input[name=PROFILE_ID]').val(profile.data('id'));

        return false;
    });


    $('.logisticBox input').on('change', function (e) {
        e.preventDefault();
        var box = $(this).closest('.logisticBox');

        $('.logisticBox').removeClass('active');
        //$('.logisticBox input:radio').attr('checked', false);

        box.addClass('active');
        //$('input:radio', box).attr('checked', true);

        $('#deliveryTitle').html(box.data('title'));
        $('#deliveryPrice').html(box.data('price'));

        return false;
    });

    if ($('.logisticBox.active').size() <= 0) {
        $('.logisticBox:first input').attr('checked', true);
    } else {
        if ($('#group_later').is(':visible')) {
            $('.asideCourier').slideUp(300);
        } else {
            $('.asideCourier').slideDown(300);
        }
    }


    $('.go-delivery').on('click', function (e) {
        e.preventDefault();
        $('input[name=confirm_order]').val('no');

        $('#order_form_confirm').submit();
        return false;
    });

    $('.radioCaption').on('click', function () {
        var id = $(this).attr('for');
        $('#' + id).attr('checked', true);
    });


    var map;

    $(window).on('resize', function () {
        var container = $('.fancybox-inner .map-zones');
        if (container.size()) {
            container.width($('.fancybox-inner').width() - 310);
            container.height($('.fancybox-inner').height());
        }
    });

    $('body').on('popup_init', function (e, wrap) {
        var container = $('.map-zones', wrap);
        if (container.size()) {

            container.width($('.fancybox-inner').width() - 310);
            container.height($('.fancybox-inner').height());

            if (map) {
                map.remove();
            }

            map = DG.map(container[0], {
                "center": [55.749213509661, 37.617202740498],
                "zoom": 10,
                "fullscreenControl": false
            });

            $.get('/local/components/aero/sale.order/zones.php', {}, function (data) {
                DG.geoJson(data, {
                    onEachFeature: function (feature, layer) {
                        layer.bindPopup('<b>' + feature.properties.Name + '</b><br>' + feature.properties.Description);
                    },
                    style: function (feature) {
                        return {
                            color: feature.style.fill,
                            stroke: true,
                            weight: feature.style.stroke,
                            fillOpacity: feature.style.opacity
                        };
                    }
                }).addTo(map);
            }, 'json');
			
            $('.map-zones-form').trigger('show');
            $('.map-zones-form').trigger('search');
        }
    });

    $('.map-zones-form').on('show', function () {
        var form = $('.delivery-msc.active');
		
		var cityName = $('input[name="PROFILE[LOCATION_DELIVERY]_val"]', form).val();
		var cityId = $('input[name="PROFILE[LOCATION_DELIVERY]"]', form).val();
			
		$('input[name="PROFILE[LOCATION_DELIVERY_POPUP]_val"]').val(cityName);
		$('input[name="PROFILE[LOCATION_DELIVERY_POPUP]"]').val(cityId);
		
        $('#search_street').val($('[name="PROFILE[STREET_DELIVERY]"]', form).val());
        $('#search_house').val($('[name="PROFILE[HOUSE_DELIVERY]"]', form).val());
        $('#search_housing').val($('[name="PROFILE[HOUSING_DELIVERY]"]', form).val());
    });

    $('.map-zones-form .formInput').on('keyup', function () {
        $('.map-zones-form').trigger('search');
    });

    var search_timer, point, lat, lng, marker;
	
	$('input[name="PROFILE[LOCATION_DELIVERY_POPUP]_val"]').on('location-selected', function(){
		$('.map-zones-form').trigger('search');
	});
	
	
    $('.map-zones-form').on('search', function () {
        
		var city = $('input[name="PROFILE[LOCATION_DELIVERY_POPUP]_val"]').val();
		if(!city || city.length <= 0){
			city = 'Москва';
		}else{
			var pos = city.indexOf('(');
			city = city.substr(0, pos);			
		}
				
		clearTimeout(search_timer);
        search_timer = setTimeout(function () {
            var address = 'г. ' + city + ', ' + $('#search_street').val() + ', ' + $('#search_house').val();

            if ($('#search_housing').val()) {
                address += ' ст' + $('#search_housing').val();
            }

            DG.ajax({
                url: 'http://catalog.api.2gis.ru/geo/search',
                data: {
                    key: 'ruxlih0718',
                    version: 1.3,
                    q: address
                },
                type: 'GET',
                success: function (data) {
                    if (typeof marker !== 'undefined') {
                        map.removeLayer(marker);
                    }
                    if (data.result) {
                        point = DG.Wkt.toPoints(data.result[0].centroid);
                        lng = point[0];
                        lat = point[1];
                        marker = DG.marker([lat, lng]).bindPopup(data.result[0].name);
                        map.addLayer(marker);
                        map.panTo([lat, lng]);
                        map.setZoom(16);
                    } else {
                        map.setZoom(10);
                    }
                },
                error: function (error) {
                    map.panTo([55.749213509661, 37.617202740498]);
                    map.setZoom(10);
                }
            });

        }, 500);
    });

    $('.map-zones-form button').on('click', function (e) {
        e.preventDefault();
        var form = $('.delivery-msc.active');
        $('[name="PROFILE[STREET_DELIVERY]"]', form).val($('#search_street').val());
        $('[name="PROFILE[HOUSE_DELIVERY]"]', form).val($('#search_house').val());
        $('[name="PROFILE[HOUSING_DELIVERY]"]', form).val($('#search_housing').val());
		
		var cityName = $('input[name="PROFILE[LOCATION_DELIVERY_POPUP]_val"]').val();
		var cityId = $('input[name="PROFILE[LOCATION_DELIVERY_POPUP]"]').val();
			
		$('input[name="PROFILE[LOCATION_DELIVERY]_val"]', form).val(cityName);
		$('input[name="PROFILE[LOCATION_DELIVERY]"]', form).val(cityId);
		
        $('.delivery-msc.active').trigger('calculate');
        $.fancybox.close();
        return false;
    });

    $('.delivery-msc .deliveryForm .formInput').on('change', function () {
        $(this).closest('.delivery-msc').trigger('calculate');
    });
	
	/* $('.calc_delivery_price').click(function(){
		$('.delivery-msc.active').trigger('calculate')
	}); */


    $('.delivery-msc').on('calculate', function () {
       
		var form = $(this),
            location = $('[name="PROFILE[LOCATION_DELIVERY]"]', form).val(),
            street = $('[name="PROFILE[STREET_DELIVERY]"]', form).val(),
            house = $('[name="PROFILE[HOUSE_DELIVERY]"]', form).val(),
            id = $('[name="DELIVERY_ID"]', form).val();


        $.get(document.location, {calc_delivery: 'yes', DELIVERY_ID: id, LOCATION_ID: location, STREET: street, HOUSE: house}, function (data) {
            if (data.ZONE && data.ZONE.length) {
                $('.current-zone-price', form).html('Москва и область, ' + data.ZONE + ', ' + data.VALUE + 'руб.');
				$('#delivery_calc_result > span').text(data.VALUE);
            } else {
                $('.current-zone-price', form).html('');
            }
            $('#deliveryPrice').html(data.VALUE);
        }, 'json');
    });

    $('.shippingClose').on('click', function (e) {
        e.preventDefault();
        var id = $(this).data('id'),
            profile = $(this).closest('.orderProfile');
        $.post(document.location, {'delete_profile': 'yes', id: id}, function (data) {
            if (data.success) {
                if ($('.orderProfile').size() == 1) {
                    profile.closest('.optionsWrapper').remove();
                } else {
                    profile.remove();
                }
            }
        }, 'json');
        return false;
    });

});