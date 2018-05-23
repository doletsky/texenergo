<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<ul class="category-list">
    <? if (!empty($arResult['SECTIONS'])): ?>
    <?
    //print_r($arResult['SECTIONS']);
    $previousLevel = 0;
        foreach ($arResult['SECTIONS'] as $arSection):?>
            <?if ($arSection['ELEMENT_CNT'] <= 0) continue;?>
            <? if ($previousLevel && $arSection["DEPTH_LEVEL"] < $previousLevel): ?>
                <?= str_repeat("</ul></li>", ($previousLevel - $arSection["DEPTH_LEVEL"])); ?>
            <? endif ?>

            <? if ($arSection["IS_PARENT"]): ?>
                <li class="head head-sub head-single has-submenu head-single-arrow">
                    <p><a href="<?= $arSection["SECTION_PAGE_URL"] ?>"><?= $arSection["NAME"] ?></a></p>
                    <ul class="sidebar sidebar-catalog sidebar-second hidden">
            <? else: ?>
                <li class="head head-sub head-single">
                    <p><a href="<?= $arSection["SECTION_PAGE_URL"] ?>"><?= $arSection["NAME"] ?></a></p>
                </li>
            <?endif ?>

            <? $previousLevel = $arSection["DEPTH_LEVEL"]; ?>

        <? endforeach ?>

        <? if ($previousLevel > 1): //close last item tags?>
            <?= str_repeat("</ul></li>", ($previousLevel - 1)); ?>
        <? endif ?>
    <?/* else: ?>

                <li class="head head-sub no-arrow-head">
                    <p>
                        <a><?= $arParams['CURRENT_SECTION']['NAME']; ?></a>
                    </p>
                </li>
*/?>
    <?endif; ?>
        </ul>