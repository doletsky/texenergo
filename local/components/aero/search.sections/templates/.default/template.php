<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? // if (!empty($arResult['ITEMS'])): ?>
<nav class="sidebar sidebar-search">
    <ul class="sidebarSearch-main">
        <li><a href="/search/?q=<?= $arParams['QUERY']; ?>"><?= $arParams['ALL_LABEL']; ?></a><span
                class="sidebarQnt"><?= ($arResult['TOTAL'] >= $arResult['MAX_RESULTS'] ? $arResult['MAX_RESULTS'] . '+' : $arResult['TOTAL']); ?></span>
        </li>
        <ul class="sidebarSearch-sub">
            <? foreach ($arResult['ITEMS'] as $arItem): ?>
                <li>
                    <a href="<?= $arItem['URL']; ?>"><?= $arItem['TAG_NAME']; ?></a> <span
                        class="sidebarQnt"><?= $arItem['COUNT']; ?><?= ($arItem['COUNT'] >= $arResult['MAX_RESULTS'] ? '+' : ''); ?></span>
                </li>
            <? endforeach; ?>
        </ul>
        <? /*
        <li><a href="#">новости</a><span class="sidebarQnt">8</span></li>
        <li><a href="#">статьи</a><span class="sidebarQnt">10</span></li>
        */
        ?>
    </ul>
</nav>
<? // endif; ?>
