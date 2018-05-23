$(function(){
	$('#subscribe-form').submit(function(e){		
		var result = $('#subscribe-result-msg');
		result.text('');
		result.removeClass('ok').removeClass('fail');
		
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		email = $('.subscribe-email', $(this)).val();
		if(email.length == 0 || !regex.test(email)){
			$('.subscribe-email', $(this)).addClass('field-error');
		}else{
			$('.subscribe-email', $(this)).removeClass('field-error');
			$.post('/local/templates/texenergo/components/bitrix/subscribe.form/footer/ajax.php', $(this).serializeArray(), function(data){
				try{
					objRes = $.parseJSON(data);
					if(objRes.status == 'ok'){
						result.addClass('ok').text(objRes.msg);
						$('#subscribe-form .subscribe-email').val('');	
					}else
						result.addClass('fail').html('Ошибка: ' + objRes.msg);
						
				}catch(ex){
					result.addClass('fail').text('При оформлении подписки произошла ошибка');
				}
			});
		}
		
		return false;
	});
});