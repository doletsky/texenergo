<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? //= '<pre>' . print_r($arResult, true) . '</pre>'; ?>
<div class="twelve">
    <h1 class="headline">
        Все результаты поиска
        <span class="searchQuery">
            (<?= $arResult['REQUEST']['QUERY']; ?>)
            <? /*<em class="searchQuantity"><?= count($arResult["SEARCH"]); ?></em>*/ ?>
        </span>
    </h1>
    <aside class="share">
        <ul>
            <li><img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/rss.png" alt="Подписаться на рассылку"></li>
            <li><img src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/share.png" alt="Поделиться с друзьями"></li>
        </ul>
    </aside>
</div>


<div class="three three-nomargin">
    <?
    $APPLICATION->IncludeComponent("aero:search.sections2", "", Array(
        "RESTART" => $arParams['RESTART'],
        "NO_WORD_LOGIC" => $arParams['NO_WORD_LOGIC'],
        "CHECK_DATES" => $arParams['CHECK_DATES'],
        "USE_TITLE_RANK" => $arParams['USE_TITLE_RANK'],
        "DEFAULT_SORT" => $arParams['DEFAULT_SORT'],
        "FILTER_NAME" => $arParams['FILTER_NAME'],
        "SHOW_WHERE" => $arParams['SHOW_WHERE'],
        "SHOW_WHEN" => $arParams['SHOW_WHEN'],
        "PAGE_RESULT_COUNT" =>  $arParams['PAGE_RESULT_COUNT'],
        "AJAX_MODE" => $arParams['AJAX_MODE'],
        "AJAX_OPTION_JUMP" => $arParams['AJAX_OPTION_JUMP'],
        "AJAX_OPTION_STYLE" => $arParams['AJAX_OPTION_STYLE'],
        "AJAX_OPTION_HISTORY" => $arParams['AJAX_OPTION_HISTORY'],
        "CACHE_TYPE" => $arParams['CACHE_TYPE'],
        "CACHE_TIME" => $arParams['CACHE_TIME'],
        "CACHE_FILTER" => $arParams['CACHE_FILTER'],
        "DISPLAY_TOP_PAGER" => $arParams['DISPLAY_TOP_PAGER'],
        "DISPLAY_BOTTOM_PAGER" => $arParams['DISPLAY_BOTTOM_PAGER'],
        "PAGER_TITLE" => $arParams['PAGER_TITLE'],
        "PAGER_SHOW_ALWAYS" => $arParams['PAGER_SHOW_ALWAYS'],
        "PAGER_TEMPLATE" => $arParams['PAGER_TEMPLATE'],
        "USE_LANGUAGE_GUESS" => $arParams['USE_LANGUAGE_GUESS'],
        "USE_SUGGEST" => $arParams['USE_SUGGEST'],
        "AJAX_OPTION_ADDITIONAL" => $arParams['AJAX_OPTION_ADDITIONAL'],
        "arrFILTER_iblock_catalog" => array(IBLOCK_ID_CATALOG),
    ),false);
    ?>
    <?/*$APPLICATION->IncludeComponent(
        "aero:search.sections",
        "",
        Array(
            "IBLOCK_CODE" => 'catalog',
            "IBLOCK_ID" => IBLOCK_ID_CATALOG,
            'ALL_LABEL' => 'все товары',
            "QUERY" => $arResult['REQUEST']['QUERY'],
            "CACHE_TYPE" => $arParams['CACHE_TYPE'],
            "CACHE_TIME" => $arParams['CACHE_TIME'],
            "RESTART" => $arParams["RESTART"] ? 'Y' : 'N',
            "NO_WORD_LOGIC" => $arParams["NO_WORD_LOGIC"] ? 'Y' : 'N',
            "CHECK_DATES" => $arParams["CHECK_DATES"] ? 'Y' : 'N',
            "arrFILTER" => array('iblock_catalog' => IBLOCK_ID_CATALOG),
        ),
        false
    );*/?>

    <? /*$APPLICATION->IncludeComponent(
        "aero:search.sections",
        "",
        Array(
            "IBLOCK_CODE" => 'shop_articles',
            "IBLOCK_ID" => IBLOCK_ID_PUBLICATIONS,
            'ALL_LABEL' => 'все публикации',
            "QUERY" => $arResult['REQUEST']['QUERY'],
        ),
        false
    );*/
    ?>

</div>
<section class="main main-floated">

    <? if (count($arResult["SEARCH"]) > 0): ?>
        <? if ($arParams["DISPLAY_TOP_PAGER"] != "N") echo $arResult["NAV_STRING"] ?>


        <? //include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/results_sections.php'; ?>

        <? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/results_catalog.php'; ?>

        <? //include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/results_publications.php'; ?>

        <? //include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/results_other.php'; ?>

        <? if ($arParams["DISPLAY_BOTTOM_PAGER"] != "N") echo $arResult["NAV_STRING"] ?>
    <? else: ?>
        <? ShowNote(GetMessage("SEARCH_NOTHING_TO_FOUND")); ?>
    <?endif; ?>

</section>

