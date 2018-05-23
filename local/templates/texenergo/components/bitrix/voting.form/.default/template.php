<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arQuestions = $arResult["QUESTIONS"];

if (is_array($arQuestions) && count($arQuestions) > 0) {
    $arQuestion = reset($arQuestions)?>

    <section class="vote">
        <div class="container">
            <div class="twelve">
                <header class="question">
                    <h1><span><strong>Вопрос: </strong></span><?=$arQuestion["QUESTION"]?></h1>
                    <a href="#"><strong>свернуть голосование</strong></a>
                </header>
                <section class="answers">
                    <?if (!empty($arResult["ERROR_MESSAGE"]))
                        echo ShowError($arResult["ERROR_MESSAGE"])?>

                    <form action="<?=POST_FORM_ACTION_URI?>" method="post">
                        <input type="hidden" name="vote" value="Y">
                        <input type="hidden" name="PUBLIC_VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
                        <input type="hidden" name="VOTE_ID" value="<?=$arResult["VOTE"]["ID"]?>">
                        <?=bitrix_sessid_post()?>

                        <?foreach ($arQuestion["ANSWERS"] as $arAnswer) {
                            switch ($arAnswer["FIELD_TYPE"]) {
                                case 0://radio
                                    $value = (isset($_REQUEST['vote_radio_' . $arAnswer["QUESTION_ID"]]) &&
                                        $_REQUEST['vote_radio_' . $arAnswer["QUESTION_ID"]] == $arAnswer["ID"]) ?
                                        'checked="checked"' : '';
                                    break;
                                case 1://checkbox
                                    $value = (isset($_REQUEST['vote_checkbox_' . $arAnswer["QUESTION_ID"]]) &&
                                        array_search($arAnswer["ID"],
                                            $_REQUEST['vote_checkbox_' . $arAnswer["QUESTION_ID"]]) !== false) ?
                                        'checked="checked"' : '';
                                    break;
                            }?>

                            <label>
                                <?switch ($arAnswer["FIELD_TYPE"]) {
                                    case 0://radio?>
                                        <input type="radio" <?=$value?> name="vote_radio_<?=$arAnswer["QUESTION_ID"]?>"
                                            value="<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?>/>
                                        <?break;
                                    case 1://checkbox?>
                                        <input <?=$value?> type="checkbox" name="vote_checkbox_<?=$arAnswer["QUESTION_ID"]?>[]"
                                            value="<?=$arAnswer["ID"]?>" <?=$arAnswer["~FIELD_PARAM"]?> />
                                    <?break;
                                }?>

                                <?=$arAnswer["MESSAGE"]?>
                            </label>
                        <?}?>

                        <button class="rounded" type="submit" name="vote" value="Y"><strong>Проголосовать</strong></button>
                    </form>
                </section>
            </div>
        </div>
    </section>
<?}?>