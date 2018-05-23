<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$arQuestions = $arResult["QUESTIONS"];

if (is_array($arQuestions) && count($arQuestions) > 0) {
    $arQuestion = reset($arQuestions)?>

    <section class="vote">
        <div class="container">
            <div class="twelve">
                <header class="question">
                    <h1><span><strong>Вопрос:</strong> </span><?=$arQuestion["QUESTION"]?></h1>
                    <a href="#"><strong>свернуть голосование</strong></a>
                </header>
                <section class="answers">
                    <?if (!empty($arResult["ERROR_MESSAGE"]))
                        echo ShowError($arResult["ERROR_MESSAGE"])?>

                    <table class="result">
                        <?foreach ($arQuestion["ANSWERS"] as $arAnswer) {?>
                            <tr>
                                <td><?=$arAnswer["MESSAGE"]?></td>
                                <td>
                                    <div class="answer-bar" style="width: <?=$arAnswer["BAR_PERCENT"]?>%"><?=$arAnswer["COUNTER"]?></div>
                                </td>
                            </tr>
                        <?}?>
                    </table>
                </section>
            </div>
        </div>
    </section>
<?}