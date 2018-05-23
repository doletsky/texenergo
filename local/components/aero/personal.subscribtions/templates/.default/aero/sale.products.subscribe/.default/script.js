$(function(){
	$('.productTracking-toggle').click(function(e){
		var productId = $(this).data("product");
		var userId = $(this).data("userid");
		var data = {'id' : productId, 'user_id' : userId};
			
		if($(this).hasClass('subSwitch-on')){						
			data.action = 'off';			
		}else{
			data.action = 'on';			
		}
		
		$.post('/ajax/product_tracking.php', data, function(data){});
	});
});