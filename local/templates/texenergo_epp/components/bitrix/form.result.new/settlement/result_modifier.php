<?//pr($arResult["QUESTIONS"]);?>
    <? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion): ?>
        <? if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') : ?>
            <?//= $arQuestion["HTML_CODE"]; ?>
        <? else: ?>
	    <?//pr($arQuestion);?>
	<? $arResult["QUESTIONS"][$FIELD_SID]["HTML_CODE"] 
		= '<input type="'.$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'" class="search-input form-control inputtext" name="form_'
		.$arQuestion['STRUCTURE'][0]['FIELD_TYPE']."_".$arQuestion['STRUCTURE'][0]['FIELD_ID'].'" value="" size="0" placeholder="'.$arQuestion["CAPTION"].'">';?>	

        <?endif; ?>
    <? endforeach; ?>

<? if (empty($arResult["FORM_NOTE"]) == false) $arResult["FORM_NOTE"]='<div class="sucess">Спасибо! Ваша заявка принята!</div>'; ?>
