<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>

    <ul>
        <? foreach ($arResult as $arItem): ?>
            <? if ($arItem["PERMISSION"] > "D"): ?>
                <li class="more no-arrow<? if ($arItem["SELECTED"]): ?> selected<? endif ?>">
                    <a href="<?= $arItem["LINK"] ?>"><?= $arItem["TEXT"] ?></a>
					<?if($arItem['PARAMS']['events'] == 'y' && $arResult['EVENTS_CNT'] > 0 && !$arItem["SELECTED"]):?>
						<a href="<?= $arItem["LINK"]?>" class="eventCnt"><?=$arResult['EVENTS_CNT']?></a>
					<?endif;?>
					
                </li>
            <? endif; ?>
        <? endforeach; ?>

    </ul>

<? endif; ?>
