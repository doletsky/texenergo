<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>


	<?$productCount = 1;?>
	<?foreach ($arResult["ITEMS"] as $arElement) {?>

		<div class="compItem <?if ($productCount == 1) {?>first<?}?>">
			<div class="compMain">
				<div class="compLogo">
                    <?php
                        if ($arElement['PRODUCER']['PREVIEW_PICTURE']) {
                            echo '<img src="' . $arElement['PRODUCER']['PREVIEW_PICTURE']['SRC'] . '" alt="' . $arElement['PRODUCER']['NAME'] . '">';
                        }
                        //<img src="< ? =SITE_TEMPLATE_PATH ? >/img/comparsion/texenergo-comp.png" alt="Texenergo">
                    ?>
                </div>
				<figure class="compImage">
                    <? if ($arElement["PREVIEW_PICTURE"]['SRC'] != '') :?>
                        <a href="/catalog/?SECTION_ID=<?=$arElement["IBLOCK_SECTION_ID"]?>&ELEMENT_ID=<?=$arElement["ID"]?>"><img src="<?=$arElement["PREVIEW_PICTURE"]['SRC']?>" alt=""></a>
                    <? else :?>
                        <a href="/catalog/?SECTION_ID=<?=$arElement["IBLOCK_SECTION_ID"]?>&ELEMENT_ID=<?=$arElement["ID"]?>"><img src="/local/templates/texenergo/img/catalog/no-image.png" alt=""></a>
                    <?endif;?>
					<figcaption>
						<span class="name"><a href="/catalog/?SECTION_ID=<?=$arElement["IBLOCK_SECTION_ID"]?>&ELEMENT_ID=<?=$arElement["ID"]?>"><?=$arElement["NAME"]?></a></span>
						<span class="sku sku-pline"><?=$arElement["PROPERTIES"]["GOODS_ART"]["VALUE"]?></span>
					</figcaption>
				</figure>
				<div class="compReview">
					<? if ($arElement["PROPERTIES"]["GOODS_RATE"]["VALUE"]) {?>
						<div class="cat-rating catRating-linear cat-value-raiting-<?=$arElement["PROPERTIES"]["GOODS_RATE"]["VALUE"]?>"></div>
					<?}?>
                    <? if ($arElement["PROPERTIES"]["BLOG_COMMENTS_CNT"]["VALUE"]) :?>
                        <span><?=$arElement["PROPERTIES"]["BLOG_COMMENTS_CNT"]["VALUE"]?> отзыва</span>
                    <? endif ;?>
				</div>
				<?$price = CPrice::GetBasePrice( $arElement['ID']);?>
				<span class="pLinePrice pLinePrice-centered"><?=$price["PRICE"]?> <i class="rouble">a</i></span>
                <? if ($arElement["PROPERTIES"]["SHOW_OLD_PRICE"]["VALUE"] == "Да") :?>
                    <span class="pLineOld pLineOld-centered"><?=$arElement["PROPERTIES"]["OLD_PRICE"]["VALUE"]?> <i class="rouble">a</i></span>
                <? endif;?>
				<!-- <span class="pBonuses pBonuses-centered">10 бонусов</span> -->
			</div>
			<div class="compSpecs compSpecs-body">
				<ul>
					<?foreach ($arResult['propertyTable'] as $property) {?>
					<?
						$diff = isset($property['diff']) ? $property['diff'] : 0;
						if(($diff && $arResult["DIFFERENT"]) || (! $arResult["DIFFERENT"])) {
								$name = $property['TYPE'] ? $property['ID'] : 'filterId' . $property['ID'];

								if (isset($arElement[$name])) { ?>

									<li class="specCell"><span><?=$arElement[$name] ?></span></li>

								<?} else {?>

									<li class="specCell"><span>-</span></li>

								<?}?>

							<?}?>

						<?}?>
				</ul>
			</div>
		</div>

		<?$productCount++;?>
	<?}?>
	

<!-- div class="catalog-compare-result">
<a name="compare_table"></a>
	<noindex><p>
	<?if($arResult["DIFFERENT"]):
		?><a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=N",array("DIFFERENT")))?>" rel="nofollow"><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?></a><?
	else:
		?><?=GetMessage("CATALOG_ALL_CHARACTERISTICS")?><?
	endif
	?>&nbsp;|&nbsp;<?
	if(!$arResult["DIFFERENT"]):
		?><a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("DIFFERENT=Y",array("DIFFERENT")))?>" rel="nofollow"><?=GetMessage("CATALOG_ONLY_DIFFERENT")?></a><?
	else:
		?><?=GetMessage("CATALOG_ONLY_DIFFERENT")?><?
	endif?>
	
	&nbsp;|&nbsp;<?
	if(!$arResult["TEXENERGO_ONLY"]):
		?><a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("TEXENERGO_ONLY=1",array("TEXENERGO_ONLY")))?>" rel="nofollow">ТОЛЬКО ПРОИЗВОДТСВО ТЕХЭНЕРГО</a><?
	else:
		?>ТОЛЬКО ПРОИЗВОДТСВО ТЕХЭНЕРГО<?
	endif?>
	
		&nbsp;|&nbsp;<?
	if($arResult["TEXENERGO_ONLY"]):
		?><a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("TEXENERGO_ONLY=0",array("TEXENERGO_ONLY")))?>" rel="nofollow">ВСЕ</a><?
	else:
		?>ВСЕ<?
	endif?>
	
	
	</p></noindex>
	<?if(!empty($arResult["DELETED_PROPERTIES"]) || !empty($arResult["DELETED_OFFER_FIELDS"]) || !empty($arResult["DELETED_OFFER_PROPS"])):?>
		<noindex><p>
		<?=GetMessage("CATALOG_REMOVED_FEATURES")?>:
		<?foreach($arResult["DELETED_PROPERTIES"] as $arProperty):?>
			<a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=ADD_FEATURE&pr_code=".$arProperty["CODE"],array("op_code","of_code","pr_code","action")))?>" rel="nofollow"><?=$arProperty["NAME"]?></a>
		<?endforeach?>
		<?foreach($arResult["DELETED_OFFER_FIELDS"] as $code):?>
			<a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=ADD_FEATURE&of_code=".$code,array("op_code","of_code","pr_code","action")))?>" rel="nofollow"><?=GetMessage("IBLOCK_FIELD_".$code)?></a>
		<?endforeach?>
		<?foreach($arResult["DELETED_OFFER_PROPERTIES"] as $arProperty):?>
			<a href="<?=htmlspecialcharsbx($APPLICATION->GetCurPageParam("action=ADD_FEATURE&op_code=".$arProperty["CODE"],array("op_code","of_code","pr_code","action")))?>" rel="nofollow"><?=$arProperty["NAME"]?></a>
		<?endforeach?>
		</p></noindex>
	<?endif?>
	<? if(! count($arResult["SHOW_PROPERTIES"])>0):?>
		<p>
		<form action="<?=$APPLICATION->GetCurPage()?>" method="get">
		<?=GetMessage("CATALOG_REMOVE_FEATURES")?>:<br />
		<?foreach($arResult["SHOW_PROPERTIES"] as $arProperty):?>
			<input type="checkbox" name="pr_code[]" value="<?=$arProperty["CODE"]?>" /><?=$arProperty["NAME"]?><br />
		<?endforeach?>
		<?foreach($arResult["SHOW_OFFER_FIELDS"] as $code):?>
			<input type="checkbox" name="of_code[]" value="<?=$code?>" /><?=GetMessage("IBLOCK_FIELD_".$code)?><br />
		<?endforeach?>
		<?foreach($arResult["SHOW_OFFER_PROPERTIES"] as $arProperty):?>
			<input type="checkbox" name="op_code[]" value="<?=$arProperty["CODE"]?>" /><?=$arProperty["NAME"]?><br />
		<?endforeach?>
		<input type="hidden" name="action" value="DELETE_FEATURE" />
		<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
		<input type="submit" value="<?=GetMessage("CATALOG_REMOVE_FEATURES")?>">
		</form>
		</p>
	<?endif?>
<br />
<form action="<?=$APPLICATION->GetCurPage()?>" method="get">
	<table class="data-table" cellspacing="0" cellpadding="0" border="0">
		<thead>
		<tr>
			<td valign="top">&nbsp;</td>
			<?foreach($arResult["ITEMS"] as $arElement):?>
				<td valign="top" width="<?=round(100/count($arResult["ITEMS"]))?>%">
					<input type="checkbox" name="ID[]" value="<?=$arElement["ID"]?>" />
				</td>
			<?endforeach?>
		</tr>
		<?foreach($arResult["ITEMS"][0]["FIELDS"] as $code=>$field):?>
		<tr>
			<th valign="top" nowrap><?=GetMessage("IBLOCK_FIELD_".$code)?></th>
			<?foreach($arResult["ITEMS"] as $arElement):?>
				<td valign="top">
					<?switch($code):
						case "NAME":
							?><a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><?=$arElement[$code]?></a><?
							if($arElement["CAN_BUY"]):
								?><noindex><br /><a href="<?=$arElement["BUY_URL"]?>" rel="nofollow"><?=GetMessage("CATALOG_COMPARE_BUY"); ?></a></noindex><?
							elseif((count($arResult["PRICES"]) > 0) || is_array($arElement["PRICE_MATRIX"])):
								?><br /><?=GetMessage("CATALOG_NOT_AVAILABLE")?><?
							endif;
							break;
						case "PREVIEW_PICTURE":
						case "DETAIL_PICTURE":
							if(is_array($arElement["FIELDS"][$code])):?>
								<a href="<?=$arElement["DETAIL_PAGE_URL"]?>"><img
										border="0"
										src="<?=$arElement["FIELDS"][$code]["SRC"]?>"
										width="<?=$arElement["FIELDS"][$code]["WIDTH"]?>"
										height="<?=$arElement["FIELDS"][$code]["HEIGHT"]?>"
										alt="<?=$arElement["FIELDS"][$code]["ALT"]?>"
										title="<?=$arElement["FIELDS"][$code]["TITLE"]?>"
										/></a>
							<?endif;
							break;
						default:
							echo $arElement["FIELDS"][$code];
							break;
					endswitch;
					?>
				</td>
			<?endforeach?>
		</tr>
		<?endforeach;?>
		</thead>
		<?foreach($arResult["ITEMS"][0]["PRICES"] as $code=>$arPrice):?>
			<?if($arPrice["CAN_ACCESS"]):?>
			<tr>
				<th valign="top" nowrap><?=$arResult["PRICES"][$code]["TITLE"]?></th>
				<?foreach($arResult["ITEMS"] as $arElement):?>
					<td valign="top">
						<?if($arElement["PRICES"][$code]["CAN_ACCESS"]):?>
							<b><?=$arElement["PRICES"][$code]["PRINT_DISCOUNT_VALUE"]?></b>
						<?endif;?>
					</td>
				<?endforeach?>
			</tr>
			<?endif;?>
		<?endforeach;?>
			<?php 


			?>
		<?
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////		
		
		foreach($arResult["my_compare_property_list"] as $code=>$arProperty):

// 			$arCompare = Array();
// 			foreach($arResult["ITEMS"] as $arElement)
// 			{
// 				$arPropertyValue = $arElement["DISPLAY_PROPERTIES"][$code]["VALUE"];
// 				if(is_array($arPropertyValue))
// 				{
// 					sort($arPropertyValue);
// 					$arPropertyValue = implode(" / ", $arPropertyValue);
// 				}
// 				$arCompare[] = $arPropertyValue;
// 			}
			$diff = $arProperty['diff'];
			if(($diff && $arResult["DIFFERENT"]) || (! $arResult["DIFFERENT"])):
		?>

				<tr>
					<th valign="top" nowrap>&nbsp;<?=$arProperty['NAME']?>&nbsp; ( <?php echo $arProperty['UF_FILTER_DESC'];  ?>)</th>
					<?foreach($arResult["ITEMS"] as $arElement):
					
					$keyGoods = 'goods-' . $arElement['ID'];
					$keyFilter = 'filter-' . $arProperty['ID'];
			
					$valuesList = isset($arResult['goods_property_list'][$keyGoods][$keyFilter])?$arResult['goods_property_list'][$keyGoods][$keyFilter]: array();
				
					$value = '';
					$description = '';
					if (is_array($valuesList) && count($valuesList)) {
						foreach ($valuesList as $row) {
							$value .= $row['name'];
							$description .= $row['desc'];
						}
					}

					?>
						<td valign="top">&nbsp;
							<? echo $value;?>
							<? echo $description; ?>
						</td>
					<?endforeach?>
				</tr>
			<?
			endif
			?>
		<?endforeach;?>
<?php 


///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////
?>
				
		<?foreach($arResult["SHOW_OFFER_FIELDS"] as $code):
			$arCompare = Array();
			foreach($arResult["ITEMS"] as $arElement)
			{
				$Value = $arElement["OFFER_FIELDS"][$code];
				if(is_array($Value))
				{
					sort($Value);
					$Value = implode(" / ", $Value);
				}
				$arCompare[] = $Value;
			}
			$diff = (count(array_unique($arCompare)) > 1 ? true : false);
			if($diff || !$arResult["DIFFERENT"]):?>
				<tr>
					<th valign="top" nowrap>&nbsp;<?=GetMessage("IBLOCK_FIELD_".$code)?>&nbsp;</th>
					<?foreach($arResult["ITEMS"] as $arElement):?>
						<?if($diff):?>
						<td valign="top">&nbsp;
							<?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
						</td>
						<?else:?>
						<th valign="top">&nbsp;
							<?=(is_array($arElement["OFFER_FIELDS"][$code])? implode("/ ", $arElement["OFFER_FIELDS"][$code]): $arElement["OFFER_FIELDS"][$code])?>
						</th>
						<?endif?>
					<?endforeach?>
				</tr>
			<?endif?>
		<?endforeach;?>

		<?foreach($arResult["SHOW_OFFER_PROPERTIES"] as $code=>$arProperty):
			$arCompare = Array();
			foreach($arResult["ITEMS"] as $arElement)
			{
				$arPropertyValue = $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["VALUE"];
				if(is_array($arPropertyValue))
				{
					sort($arPropertyValue);
					$arPropertyValue = implode(" / ", $arPropertyValue);
				}
				$arCompare[] = $arPropertyValue;
			}
			$diff = (count(array_unique($arCompare)) > 1 ? true : false);
			if($diff || !$arResult["DIFFERENT"]):?>
				<tr>
					<th valign="top" nowrap>&nbsp;<?=$arProperty["NAME"]?>&nbsp;</th>
					<?foreach($arResult["ITEMS"] as $arElement):?>
						<?if($diff):?>
						<td valign="top">&nbsp;
							<?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
						</td>
						<?else:?>
						<th valign="top">&nbsp;
							<?=(is_array($arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])? implode("/ ", $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"]): $arElement["OFFER_DISPLAY_PROPERTIES"][$code]["DISPLAY_VALUE"])?>
						</th>
						<?endif?>
					<?endforeach?>
				</tr>
			<?endif?>
		<?endforeach;?>
	</table>
	<br />
	<input type="submit" value="<?=GetMessage("CATALOG_REMOVE_PRODUCTS")?>" />
	<input type="hidden" name="action" value="DELETE_FROM_COMPARE_RESULT" />
	<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
</form>
<br />
<?if(count($arResult["ITEMS_TO_ADD"])>0):?>
<p>
<form action="<?=$APPLICATION->GetCurPage()?>" method="get">
	<input type="hidden" name="IBLOCK_ID" value="<?=$arParams["IBLOCK_ID"]?>" />
	<input type="hidden" name="action" value="ADD_TO_COMPARE_RESULT" />
	<select name="id">
	<?foreach($arResult["ITEMS_TO_ADD"] as $ID=>$NAME):?>
		<option value="<?=$ID?>"><?=$NAME?></option>
	<?endforeach?>
	</select>
	<input type="submit" value="<?=GetMessage("CATALOG_ADD_TO_COMPARE_LIST")?>" />
</form>
</p>
<?endif?>
</div> -->