<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<h1 class="modal-title"><?= $arResult["FORM_TITLE"] ?> &laquo;<?=$arResult['VACANCY_NAME']?>&raquo;</h1>

<?= $arResult["FORM_NOTE"] ?>

<form class="form cb_form" action="/ajax/vacancy_response.php" data-messages="yes" 
	  enctype="multipart/form-data" 
      id="webform_<?= $arParams['WEB_FORM_ID']; ?>"
      name="webform_<?= $arParams['WEB_FORM_ID']; ?>" method="POST">
    
	<?= bitrix_sessid_post(); ?>
    
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
			
            <div class="form-row<? if ($arQuestion["REQUIRED"] == "Y"): ?> required<? endif; ?>">
                
				<?if($FIELD_SID == 'callback_comment'):?>
				
					<label class="unmask"><?= $arQuestion["CAPTION"] ?></label>
                
				<?else:?>
					
					<label><?= $arQuestion["CAPTION"] ?></label>
					
				<?endif;?>
				
				<?= $arQuestion["HTML_CODE"] ?>
                <? if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])): ?>
                    <div class="field-error" title="<?= $arResult["FORM_ERRORS"][$FIELD_SID] ?>"></div>
                <? endif; ?>
            </div>
        <?endif; ?>
    <? endforeach; ?>

    <div class="form-buttons">
        <button name="web_form_submit" value="yes" class="button orange">
            <?= $arResult["arForm"]["BUTTON"]; ?>
        </button>
    </div>

</form>
