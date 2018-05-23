<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? if (!empty($arResult)): ?>
    <nav class="accountMenu">
        <div class="container">
            <div class="twelve">
                <ul class="accountList">
                    <? foreach ($arResult as $arItem): ?>
                        <? if ($arItem["PERMISSION"] > "D"): ?>
                            <li <? if ($arItem["SELECTED"]): ?>class="accountMenu-selected"<? endif ?>>
                                <a href="<?= $arItem["LINK"] ?>"
                                   class="<?= $arItem['PARAMS']['CLASS']; ?>"><?= $arItem["TEXT"] ?></a>
                            </li>
                        <? endif; ?>
                    <? endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
<? endif; ?>
