<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arItems = $arResult["ITEMS"];
$arCurrentSection = $arResult["CURRENT_SECTION"];

if (is_array($arItems) && count($arItems) == 0 && $arCurrentSection) {?>
    <section class="main main-floated main-info">
    	<div class="newsContent">
            <?if ($arCurrentSection["SCALED_PICTURE"]) {?>
                <figure class="newsFigure">
                    <img src="<?=$arCurrentSection["SCALED_PICTURE"]["src"]?>">
                </figure>
            <?}?>
            <div class="newsPr">
    		    <?=$arCurrentSection["DESCRIPTION"];?>
            </div>
    	</div>
    </section>
<?} elseif ($arCurrentSection)
    echo $arCurrentSection["DESCRIPTION"];
else
    echo $arResult["DESCRIPTION"];