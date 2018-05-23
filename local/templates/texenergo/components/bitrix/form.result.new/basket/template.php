<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<h1 class="modal-title"><?= $arResult["FORM_TITLE"] ?></h1>

<?= $arResult["FORM_NOTE"] ?>

<form class="form" action="/basket/ajax/click.php" data-messages="yes"
      id="webform_<?= $arParams['WEB_FORM_ID']; ?>"
      name="webform_<?= $arParams['WEB_FORM_ID']; ?>" method="POST">
    <?= bitrix_sessid_post(); ?>
    <input type="hidden" name="WEB_FORM_ID" value="<?= $arParams['WEB_FORM_ID']; ?>">
    <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
        <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') : ?>
            <?= $arQuestion["HTML_CODE"]; ?>
        <? else: ?>
			<? if ($FIELD_SID == 'basket_items') continue; ?>            
			
            <div class="form-row<? if ($arQuestion["REQUIRED"] == "Y"): ?> required<? endif; ?>">
                <label><?= $arQuestion["CAPTION"] ?></label>
				
				<? if ($FIELD_SID == 'basket_email'):?>			
					<input type="text" class="inputtext" data-rules="valid_email" name="form_email_<?=$arQuestion['STRUCTURE'][0]['FIELD_ID']?>" value="" size="0"></div>
					<?continue; ?>
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
