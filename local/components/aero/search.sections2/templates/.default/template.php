<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? // if (!empty($arResult['ITEMS'])): ?>
<nav class="sidebar sidebar-search">
    <ul class="sidebarSearch-main">
        <li><a href="/search/?q=<?= $arResult["REQUEST"]["QUERY"]; ?>">все товары</a><span
                class="sidebarQnt"><?= ($arResult['TOTAL'] >= $arResult['MAX_RESULTS'] ? $arResult['MAX_RESULTS'] . '+' : $arResult['TOTAL']); ?></span>
        </li>
        <ul class="sidebarSearch-sub">
            <? foreach ($arResult['SEARCH'] as $sectionName => $arItemList): ?>
                <li>
                    <a href="<?=$APPLICATION->GetCurPageParam("tags=" . urlencode($sectionName), array("tags","PAGEN_2")); ?>"><?= $sectionName;?></a> <span
                        class="sidebarQnt"><?= count($arItemList); ?><?= (count($arItemList) >= $arResult['MAX_RESULTS'] ? '+' : ''); ?></span>
                </li>
            <? endforeach; ?>
        </ul>
    </ul>
</nav>
<? // endif; ?>
