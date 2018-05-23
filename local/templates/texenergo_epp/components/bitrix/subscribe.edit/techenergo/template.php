<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="subscription-form-wrap">

<?foreach($arResult["MESSAGE"] as $itemID=>$itemValue):?>
	<p class="note-ok"><?=$itemValue?></p>	
<?endforeach;?>

<?foreach($arResult["ERROR"] as $itemID=>$itemValue):?>
	<p class="note-fail"><?=$itemValue?></p>	
<?endforeach;?>
	

<?if($arResult["ALLOW_ANONYMOUS"]=="N" && !$USER->IsAuthorized()):?>
	<p class="note-fail"><?=GetMessage("CT_BSE_AUTH_ERR")?></p>		
<?else:?>

<div class="subscription">
	<form method="post">
	<?echo bitrix_sessid_post();?>
	<input type="hidden" name="PostAction" value="<?echo ($arResult["ID"]>0? "Update":"Add")?>" />
	<input type="hidden" name="ID" value="<?echo $arResult["SUBSCRIPTION"]["ID"];?>" />
	<input type="hidden" name="RUB_ID[]" value="0" />

	
	
	<?if($arResult["ID"]>0 && $arResult["SUBSCRIPTION"]["CONFIRMED"] <> "Y" && $_GET['action'] != 'unsubscribe'):?>
	
	<div class="subscription-utility">
		<p><?echo GetMessage("CT_BSE_CONF_NOTE")?></p>
		<p><input name="CONFIRM_CODE" type="text" class="subscription-textbox formInput" value="<?echo GetMessage("CT_BSE_CONFIRMATION")?>" onblur="if (this.value=='')this.value='<?echo GetMessage("CT_BSE_CONFIRMATION")?>'" onclick="if (this.value=='<?echo GetMessage("CT_BSE_CONFIRMATION")?>')this.value=''" /></p><p><input type="submit" name="confirm" class="button orange" value="<?echo GetMessage("CT_BSE_BTN_CONF")?>" /></p>
	</div>
	<?endif?>

	</form>

</div>
<?endif;?>

</div>