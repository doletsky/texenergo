<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
?>


<?$this->setFrameMode(false);?>

<div class="subscribe-form" >

	<form action="<?=$arResult["FORM_ACTION"]?>" class="form" id="subscribe-form">
	
	<input type="hidden" name="get_subscribed" value="y" />
	
	<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
		
		<input type="hidden" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"]?>" value="<?=$itemValue["ID"]?>" />
		
	<?endforeach;?>

		<!-- <table class="subscribe-tbl">			 -->
		<div class="subscribe-tbl">			
			<!-- <tr> -->
				
				<!-- <td class="subscribe-img"><img src="<?=SITE_TEMPLATE_PATH?>/img/subscribe_icon.png" alt="Подпишитесь! Новинки, скидки, предложения!" width="32"></td> -->
				<div class="subscribe-img"><img src="<?=SITE_TEMPLATE_PATH?>/img/subscribe_icon.png" alt="Подпишитесь! Новинки, скидки, предложения!" width="32"></div>
				<!-- <td class="subscribe-text">Подпишитесь! Новинки, скидки, предложения!</td> -->
				<div class="subscribe-text">Подпишитесь! Новинки, скидки, предложения!</div>
				<!-- <td> -->
					<input type="text" name="sf_EMAIL"  class="subscribe-email formInput" placeholder="Укажите адрес электронной почты" size="20" value="" />
				<!-- </td> -->
				<!-- <td class="subscribe-ok"><input type="submit" class="button orange" name="OK" value="<?=GetMessage("subscr_form_button")?>" /></td> -->
				<div class="subscribe-ok"><input type="submit" class="button orange" name="OK" value="<?=GetMessage("subscr_form_button")?>" /></div>
				<div class="clearfix"></div>
			<!-- </tr> -->
		<!-- </table> -->
		</div>
	</form>

	<div id="subscribe-result-msg"></div>
	
</div>
