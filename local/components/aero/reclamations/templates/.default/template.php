<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<div class="b_reclamation">
	
	<form id="reclamation_form" method="post" enctype = 'multipart/form-data' class="form">
	
	<input type="hidden" name="add_reclamation" value="y" />
	
	<div class="header b-bottom">
		<h1><span class="orange">Выберите тип рекламации</span></h1>
	</div>
	
	<div class="type-boxes" style="display:none;">
		<input id="type_brak" type="checkbox" name="type" value="brak" checked="checked" />
		<input id="type_vozvrat" type="checkbox" name="type" value="vozvrat" />
		<input id="type_nedostacha" type="checkbox" name="type" value="nedostacha" />
		<input id="type_peresort" type="checkbox" name="type" value="peresort" />
	</div>
	
	
	<div class="type rec-block b-bottom">
		<a href="#" type_id="type_brak" class="reclBlock" need_sn="y" quantity_caption="Возвращаемое кол-во">
			<div class="b-reclBlock active">
				<h1>Брак</h1>
				<span>Если заметили брак</span>				
			</div>
		</a>
		<a href="#" type_id="type_vozvrat" class="reclBlock" need_sn="y" quantity_caption="Возвращаемое кол-во">
			<div class="b-reclBlock">
				<h1>Возврат</h1>
				<span>Если есть основание для возврата</span>				
			</div>
		</a>
		<a href="#" type_id="type_nedostacha" class="reclBlock" need_sn="n" quantity_caption="Кол-во по факту">
			<div class="b-reclBlock">
				<h1>Недостача</h1>
				<span>Если пришел не весь товар</span>				
			</div>
		</a>
		<a href="#" type_id="type_peresort" class="reclBlock" need_sn="n" quantity_caption="Кол-во по факту">
			<div class="b-reclBlock">
				<h1>Пересорт</h1>
				<span>Если ошибка в комплектации</span>				
			</div>
		</a>
	</div>
			
	<div class="products b-bottom rec-block">
		<table width="100%">
			<tr>
				<td width="40%" class="first">
					<span class="bigcaption">Товары</span><br>
					<span class="smallcaption">Перечислите наименования товаров</span>
				</td>
				<td width="10%">
					<span class="smallcaption">Номер<br>реализации</span>
				</td>
				<td width="5%">
					№
				</td>
				<td width="25%">					
					<input type="text" name="realize_number" class="cartForm-input" />
				</td>
				<td width="40%">
					<a class="button orange" href="#" id="add_products">+ Найти товары</a>
				</td>
			</tr>
		</table>
	</div>
	
	<div class="prod-list rec-block">
		<div class="cartHeader clearfix">
			<table>
				<tr>
					<td class="b-cartImage col-img">&nbsp;</td>
					<td class="b-cartName col-name"><div class="col-name-inside">Товар</div></td>
					<td class="col-price">№ реализации</td>
					<td class="col-price">Дата</td>
					<td class="col-quant">Кол-во по реализации</td>
					<td id="q-caption">Возвращаемое кол-во</td>
				</tr>
			</table>
		</div>
		
		<div class="no_products_msg_wrap">
			<div id="no_products_msg">Товары не найдены</div>
		</div>
								
		<div class="cartItem clearfix" realiz_id="" prod_id="" style="display:none;" id="sample_return_item">
			<input class="replace_product_id" type="hidden" name="products[][]" value="">
			<input class="replace_product_name" type="hidden" name="products_name[][]" value="">
			<table>
				<tr>
					<td class="col-img">
						<div class="b-cartImage">
							<a href="" class="picture replace_href replace_bg"  style="background-image: url('');"></a>								
						</div>
					</td>
					<td class="col-name">
						<div class="b-cartName">
							<a href="" class="cartName replace_href replace_name"></a>
							<em class="cartSerial replace_sku"></em>
						</div>
					</td>
					<td class="col-price replace_realize_num">
					
					</td>
					<td class="col-price replace_realize_date">
					
					</td>
					<td class="col-quant replace_quantity">
						
					</td>
					<td>
						<div class="cartSubtotal">
							<input autocomplete="off" type="text" name="" class="cartForm-input replace_quantity_new required" data-rules="integer|required" data-title="Количество" value="" >							
						</div>
					</td>
				</tr>
			</table>


			<a class="close-item" href="#" data-id="<?= $arItem['ID']; ?>" title="Удалить из списка"></a>
		</div>
		
		<div class="prod-list-wrap">
		
		</div>
		
	
	</div>
	
	<div class="products rec-block" id="add_new_product" style="display:none;">
		<table width="100%">
			<tr>
				<td width="40%" class="first">
					
				</td>
				<td width="15%">
					<span class="smallcaption">Введите наименование товара</span>
				</td>				
				<td width="25%">					
					<input type="text" name="new_product_name" class="cartForm-input" />
				</td>
				<td width="40%">
					<a class="button orange" href="#" id="add_single_product">+ Добавить товар</a>
				</td>
			</tr>
		</table>
	</div>
	
	<div class="s-num b-bottom rec-block">
		<div class="s-num-heading clearfix">
			<div class="bigcaption fl cf">Серийные номера</div>
			<div class="pick-sn subSwitch jSubSwitch fl"></div>				
			<div class="smallcaption fl add-product-caption">Указать серийные номера изделий</div>
		</div>
				
		<div class="s-num-item-wrap" realiz_id="" prod_id="" style="display:none;" id="sample_return_item_sn">
			<div class="cartItem s-num-item clearfix">
				<table>
					<tr>
						<td class="col-img">
						<div class="b-cartImage">
							<a href="" class="picture replace_href replace_bg"  style="background-image: url('');"></a>								
						</div>
					</td>
					<td class="col-name">
						<div class="b-cartName">
							<a href="" class="cartName replace_href replace_name"></a>
							<em class="cartSerial replace_sku"></em>
						</div>
					</td>					
					</tr>
				</table>
			</div>
			<div class="sn-list clearfix">
				
				<div class="item">
					<div class="num">1</div>
					<input type="text" name="" value="" class="cartForm-input fl replace_serial">
				</div>
				
				<a href="#" class="button orange item add-sn">+ Добавить</a>
			</div>
		</div>
		
		<div class="sn-wrap" style="display:none;"></div>
		
	</div>
	
	<div class="b-bottom rec-block descr-block">
		<div class="bigcaption fl cf padded">Описание рекламации</div>	
		<div class="fl cf">
			<div class="fl ta-container">
				<textarea name="description"></textarea>
			</div>
			<div class="fl">
				<a href="#" class="button orange item" id="attach_description">+ Приложить</a>
				<div class="filename"></div>
				<input type="file" name="description_file" id="description_file" />				
			</div>
		</div>
	</div>
	
	<div class="b-bottom rec-block descr-block">
		<div class="bigcaption fl cf padded">Обстоятельства при которых были выявлены дефекты</div>	
		<div class="fl cf">
			<div class="fl ta-container">
				<textarea name="case"></textarea>
			</div>			
		</div>
	</div>
	
	<div class="rec-block final">
		<a href="#" class="button orange submit_reclamation">Отправить</a>
		<a href="/personal/orders/reclamation/" class="orange calcelbutton">Отменить</a>
	</div>
	
	</form>

</div>