<?foreach($arResult['ITEMS'] as $arItem):?>
		
<section class="orderItem _bb10">
	<?/*<header>
		<h1>
			<span class="orderName">Рекламация <b>№<?=$arItem['ID']?></b></span>
			<span class="orderDate"> от <?=$arItem['DATE_CREATE']?></span>
		</h1>

	</header>*/?>
	<section class="accountFinancials">
		<table width="100%">
			<tr>
				<th class="accountFinancials_orderName">Рекламация <b>№<?=$arItem['ID']?></b></th>
				<th class="accountFinancials_orderDate"> от 28.11.2014</th>
				<th class="accountFinancials_orderTime">13:01:32</th>
			</tr>
			<?foreach($arItem['PROPERTIES']['PRODUCTS']['VALUE'] as $product):?>
			
			<tr>
				<td class="accountFinancials_name"><?=$product['NAME']?></td>
				<td class="accountFinancials_state">Брак</td>
				<td class="accountFinancials_cnt"><?=$product['QUANTITY']?> шт.</td>
			</tr>

			<?endforeach;?>
			
		</table>		
	</section>           
 </section>	
		
<?endforeach;?>
