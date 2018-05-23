<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<h4 class="modal-title order">Заявка на расчет стоимости оборудования</h4>

<?= $arResult["FORM_NOTE"] ?>

<form class="form navbar-form form-order"  action="/ajax/settlement.php" data-messages="yes" 
	  enctype="multipart/form-data"
      id="webform_<?= $arParams['WEB_FORM_ID']; ?>"
      name="webform_<?= $arParams['WEB_FORM_ID']; ?>" method="POST">
    
	<?= bitrix_sessid_post(); ?>
    
       <p class="cb-text">
		Пожалуйста, заполните контактную информацию о себе и приложите файл для расчета. Мы свяжемся с вами в ближайшее время.
      </p>

	<input type="hidden" name="vid" value="<?=$arParams['VACANCY_ID']?>">		
	<input type="hidden" name="WEB_FORM_ID" value="<?= $arParams['WEB_FORM_ID']; ?>">
	

    <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
        <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') : ?>
            <?= $arQuestion["HTML_CODE"]; ?>
        <? else: ?>
            
			<? if ($FIELD_SID == 'response_vacancy'):?>			
			
				<input type="hidden" name="form_text_<?=$arQuestion['STRUCTURE'][0]['FIELD_ID']?>" value="<?=$arResult['VACANCY_NAME']?>" />								
				<?continue;?>
				
			<?endif;?>
			
			<? if ($FIELD_SID == 'response_city'):?>			
			
				<input type="hidden" name="form_text_<?=$arQuestion['STRUCTURE'][0]['FIELD_ID']?>" value="<?=$arParams['VACANCY_CITY']?>" />								
				<?continue;?>
				
			<?endif;?>
			
            <div class="input-group form-row<? if ($arQuestion["REQUIRED"] == "Y"): ?> required<? endif; ?>">
                
				<?if($FIELD_SID == 'callback_comment'):?>
				
					<!--<label class="unmask"><?= $arQuestion["CAPTION"] ?></label>-->
                
				<?else:?>
					
					<!--<label><?= $arQuestion["CAPTION"] ?></label>-->
					
				<?endif;?>
				
				<?= $arQuestion["HTML_CODE"] ?>
				<?//= $FIELD_SID?>
				<?//pr($arResult["FORM_ERRORS"])?>
                <? if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])): ?>
                    <div class="field-error" title="<?= $arResult["FORM_ERRORS"][$FIELD_SID] ?>"><?= $arResult["FORM_ERRORS"][$FIELD_SID]?></div>
                <? endif; ?>
            </div>

        <?endif; ?>
    <? endforeach; ?>

    <div class="form-buttons">
        <button name="web_form_submit" value="yes" class="btn btn-default button orange">
            <?= $arResult["arForm"]["BUTTON"]; ?>
        </button>
    </div>

</form>
