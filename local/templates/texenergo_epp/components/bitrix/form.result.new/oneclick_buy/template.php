<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
?>

<h1 class="modal-title"><?= $arResult["FORM_TITLE"] ?></h1>

<?= $arResult["FORM_NOTE"] ?>

<form class="form cb_form" action="/catalog/ajax/oneclick_buy.php" data-messages="yes"
      id="webform_<?= $arParams['WEB_FORM_ID']; ?>"
      name="webform_<?= $arParams['WEB_FORM_ID']; ?>" method="POST">

	<?= bitrix_sessid_post(); ?>

	<input type="hidden" name="product_id" value="<?=$arParams['BUY_PRODUCT']['ID']?>">
	<input type="hidden" name="price" value="<?=$arParams['BUY_PRODUCT']['PRICE']?>">
	<input type="hidden" name="web_form_submit" value="yes">
	<p class="cb-text">
		<?$APPLICATION->IncludeComponent(
			"bitrix:main.include",
			"",
			Array(
				"AREA_FILE_SHOW" => "file",
				"AREA_FILE_SUFFIX" => "inc",
				"EDIT_TEMPLATE" => "",
				"PATH" => "/include/oneclickbuy_text.php"
			)
		);?>
	</p>

	<input type="hidden" name="WEB_FORM_ID" value="<?= $arParams['WEB_FORM_ID']; ?>">
    <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
        <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') : ?>
            <?= $arQuestion["HTML_CODE"]; ?>
        <? else: ?>

			<? if ($FIELD_SID == 'onelick_item'):?>

				<input type="hidden" name="form_textarea_<?=$arQuestion['STRUCTURE'][0]['FIELD_ID']?>" value="[<?=$arParams['BUY_PRODUCT']['ID']?>] <?=$arParams['BUY_PRODUCT']['NAME']?> 1x<?=$arParams['BUY_PRODUCT']['PRICE']?>руб." />
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
