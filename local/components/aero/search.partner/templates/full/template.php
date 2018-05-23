<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?if (!empty($arResult["ITEMS"])){?>
    <div class="twelve">
        <h1 class="headline">
            Все результаты поиска
        <span class="searchQuery">
            (<?= $_REQUEST['q']; ?>)
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
        $arItemIds = array();
        foreach($arResult["ITEMS"] as $arItem){
            $arItemIds[] = $arItem['ID'];
        }
        $APPLICATION->IncludeComponent("aero:search.sections2", "", Array(
            "RESTART" => 'Y',
            "NO_WORD_LOGIC" => 'Y',
            "CHECK_DATES" => 'N',
            "USE_TITLE_RANK" => 'Y',
            "DEFAULT_SORT" => 'date',
            "FILTER_NAME" => 'siteSearchFilter',
            "SHOW_WHERE" => 'N',
            "SHOW_WHEN" => 'N',
            "PAGE_RESULT_COUNT" =>  '20',
            "AJAX_MODE" => 'N',
            "AJAX_OPTION_JUMP" => 'N',
            "AJAX_OPTION_STYLE" => 'Y',
            "AJAX_OPTION_HISTORY" => 'N',
            "CACHE_TYPE" => 'A',
            "CACHE_TIME" => '3600',
            "CACHE_FILTER" => 'Y',
            "DISPLAY_TOP_PAGER" => 'N',
            "DISPLAY_BOTTOM_PAGER" => 'Y',
            "PAGER_TITLE" => 'Результаты поиска',
            "PAGER_SHOW_ALWAYS" => 'N',
            "PAGER_TEMPLATE" => 'bottom-pagenavigation',
            "USE_LANGUAGE_GUESS" => 'N',
            "USE_SUGGEST" => 'N',
            "AJAX_OPTION_ADDITIONAL" => '',
            "arrFILTER_iblock_catalog" => array(IBLOCK_ID_CATALOG),
            'ITEMS' => $arItemIds
        ),false);
        ?>
    </div>
    <section class="main main-floated">
        <?if(!empty($arResult['pager'])) $APPLICATION->IncludeComponent('bitrix:system.pagenavigation', 'top-pagenavigation', array('NAV_RESULT' => $arResult['pager']));?>
        <? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/results_catalog.php'; ?>
        <?if(!empty($arResult['pager'])) $APPLICATION->IncludeComponent('bitrix:system.pagenavigation', 'bottom-pagenavigation', array('NAV_RESULT' => $arResult['pager']));?>
    </section>
<?}else{
    $_REQUEST['q'] .= '*';
    $GLOBALS['siteSearchFilter'] = Array(
        'MODULE_ID' => 'iblock',
        'PARAM1' => 'catalog',
        'PARAM2' => IBLOCK_ID_CATALOG,
    );
    $APPLICATION->IncludeComponent("bitrix:search.page", "new", Array(
        "RESTART" => "Y",
        "NO_WORD_LOGIC" => "Y",
        "CHECK_DATES" => "N",
        "USE_TITLE_RANK" => "Y",
        "DEFAULT_SORT" => "rank",
        "FILTER_NAME" => "siteSearchFilter",
        "SHOW_WHERE" => "N",
        "SHOW_WHEN" => "N",
        "PAGE_RESULT_COUNT" => "20",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "CACHE_FILTER" => "Y",
        "DISPLAY_TOP_PAGER" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "Результаты поиска",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "bottom-pagenavigation",
        "USE_LANGUAGE_GUESS" => "N",
        "USE_SUGGEST" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "arrFILTER_iblock_catalog" => array(IBLOCK_ID_CATALOG),
    ),
        false
    );
}?>
