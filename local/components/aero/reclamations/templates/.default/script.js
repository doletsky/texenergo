$(function(){
	
	var ajaxUrl = '/local/components/aero/reclamations/ajax/';
	var lastProdId = 1;

	$('#attach_description').click(function(){
		$('#description_file').trigger('click');
		return false;
	});
	
	$('#description_file').change(function(){
		$(this).prev('.filename').text($(this).val());		
	});
	
	$('.submit_reclamation').click(function(e){
		$('input[name=realize_number]').removeClass('field-error');
		var doSend = false;
		if(haveProducts()){			
			doSend = true;
			if($('#type_peresort').is(':checked')){
				var realizeNumber = $('input[name=realize_number]').val();
				if(realizeNumber.length == 0){
					$('input[name=realize_number]').addClass('field-error');
					doSend = false;
				}
			}			
		}
		if(doSend){
			$('#reclamation_form').submit();
		}
		return false;
	});	
	
	function haveProducts(){
		var have = false;
		$('.prod-list .cartItem').each(function(){
			var curProdId = $(this).attr('prod_id');
			var curRNum = $(this).attr('realiz_id');			
			if(curProdId.length > 0 && curRNum.length > 0){
				have = true;
				return true;
			}
		});		
		return have;
	}
	
	$('.reclBlock').click(function(e){
		e.preventDefault();
		$('.b-reclBlock').removeClass('active');
		$('.type-boxes input').attr('checked', false);		
		
		$('.b-reclBlock', this).addClass('active');
		var typeId = $(this).attr('type_id');		
		$('#' + typeId).attr('checked', true);
		
		if(typeId == 'type_peresort'){			
			$('#add_new_product').show();
		}else{
			$('#add_new_product').hide();
		}

		var needSn = $(this).attr('need_sn');
		var qCaption = $(this).attr('quantity_caption');
		
		$('#q-caption').text(qCaption);
		
		if(needSn == 'y'){
			$('.s-num').show();
		}else{
			$('.s-num').hide();			
			$('.sn-wrap .replace_serial').val('');
		}
	});
		
	$('#reclamation_form').on('keyup', '.replace_quantity_new', function(){
		var val = $(this).val().replace(/[^\d+]/g, '');
		
		var rType = $('.type-boxes input:checked').val();
		if(rType != 'peresort'){		
			var maxVal = parseInt($(this).closest('tr').find('.replace_quantity').text());						
			if(val > maxVal)
				val = maxVal;
		}
		$(this).val(val);		
	});
	
	$('#reclamation_form').on('click', '.close-item', function(e){
		e.preventDefault();
		var row = $(this).closest('.cartItem');
		var rNum = row.attr('realiz_id');
		var pNum = row.attr('prod_id');
		$('.sn-wrap .s-num-item-wrap').each(function(){
			var rNumSn = $(this).attr('realiz_id');
			var pNumSn = $(this).attr('prod_id');
			
			if(rNum == rNumSn && pNum == pNumSn){
				$(this).remove();
				return false;
			}
		});
		row.remove();
		
	});
	
	$('#reclamation_form').on('click', '.add-sn', function(e){
		e.preventDefault();
		var item = $(this).prev('.item').clone();
		var num = parseInt($(item).find('.num').text());		
		num++;
		$(item).find('.num').text(num);
		item.find('.cartForm-input').val('');
		$(this).before(item);
	});
	
	$('#add_single_product').click(function(e){
		e.preventDefault();
		var productName = $('input[name=new_product_name]').val();
		$('input[name=new_product_name]').val('');
		if(productName.length > 0){			
			var product = {
				'QUANTITY':'',
				'ID':'-' + lastProdId,
				'PRODUCT_INFO':{
					'ID':'-' + lastProdId,
					'PICTURE':'/local/templates/texenergo/img/catalog/no-image.png',
					'DETAIL_PAGE_URL':'#',
					'PROPERTY_SKU_VALUE':'',
					'NAME':productName
				}
			};
			addProductToList('-', '-', product, '#sample_return_item', '.prod-list-wrap');
			lastProdId++;
		}
	});	
		
	$('#add_products').click(function(e){
		e.preventDefault();
		var realizeNumber = $('input[name=realize_number]').val();
		if(realizeNumber.length > 0){
			$('.prod-list-wrap .cartItem ').each(function(){
				var rNum = $(this).attr('realiz_id');
				if(rNum != '-'){
					$(this).remove();
				}
			});
			
			$('.sn-wrap').html('');
				
			$.post(ajaxUrl + 'search_product.php', {'r_num': realizeNumber}, function(data){				
				handleSearchResponse(data);
			});
		}
	});
	
	function handleSearchResponse(data){
		realization = eval(data);
		if(realization.length > 0){
			$('#no_products_msg').hide();
			realization = realization[0];
			var rNum = realization.REALIZ_NUM;
			var rDate = realization.PROPERTY_DATE_VALUE;
			
			for(i = 0; i < realization.PROPERTY_BASKET_ITEMS_VALUE.length; i++){
				product = realization.PROPERTY_BASKET_ITEMS_VALUE[i];
				if(!productAdded(rNum, product)){
					sampleSelector = '#sample_return_item';
					listSelector = '.prod-list-wrap';
					addProductToList(rNum, rDate, product, sampleSelector, listSelector);
					
					sampleSelector = '#sample_return_item_sn';
					listSelector = '.sn-wrap';
					addProductToList(rNum, rDate, product, sampleSelector, listSelector);
				}
			}
		}else{
			$('#no_products_msg').show();					
		}
	}
	
	function productAdded(rNum, product){
		var added = false;
		$('.prod-list-wrap .cartItem').each(function(){
			var curProdId = $(this).attr('prod_id');
			var curRNum = $(this).attr('realiz_id');
			if(curProdId == product.ID && curRNum == rNum){
				added = true;
				return false;
			}
		});
		return added;
	}
	
	function addProductToList(rNum, rDate, product, sampleSelector, listSelector){		
		var info = product.PRODUCT_INFO;
		var newProd = $(sampleSelector).clone();
		newProd.css('display', 'block');
		newProd.removeAttr('id');
		newProd.attr('realiz_id', rNum);
		newProd.attr('prod_id', info.ID);
		newProd.find('.replace_href').attr('href', info.DETAIL_PAGE_URL);
		if(info.DETAIL_PAGE_URL == '#'){
			newProd.find('.replace_href').click(function(e){e.preventDefault();});
			newProd.find('.replace_href').css('cursor', 'default').css('text-decoration', 'none');
		}
		newProd.find('.replace_bg').css('background-image', 'url("' + info.PICTURE + '")');
		newProd.find('.replace_name').text(info.NAME);
		newProd.find('.replace_sku').text(info.PROPERTY_SKU_VALUE);
		newProd.find('.replace_realize_num').text(rNum);
		newProd.find('.replace_realize_date').text(rDate);
		if(product.QUANTITY.length > 0){
			newProd.find('.replace_quantity').text(product.QUANTITY + ' шт.');
		}
		newProd.find('.replace_product_id').val(product.ID);
		newProd.find('.replace_product_id').attr('name', 'products['+rNum+'][]');
		
		newProd.find('.replace_product_name').val(info.NAME);
		newProd.find('.replace_product_name').attr('name', 'products_name['+rNum+']['+product.ID+']');
		
		newProd.find('.replace_quantity_new').removeClass('field-error').attr("id", "").attr('name', 'quantity['+rNum+']['+product.ID+']');
		newProd.find('.replace_serial').attr('name', 'serial['+rNum+']['+product.ID+'][]');
				
		$(listSelector).append(newProd);
	}
		
	
	$('.pick-sn').click(function(){
		if($(this).hasClass('subSwitch-on')){
			$(this).removeClass('subSwitch-on');
			$('.sn-wrap').hide();
		}else{
			$(this).addClass('subSwitch-on');
			$('.sn-wrap').show();
		}
	});
	
	
});