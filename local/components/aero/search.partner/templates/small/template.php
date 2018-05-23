<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php
$queryWord = isset ($_REQUEST['q']) ? $_REQUEST['q'] : '';
if (!empty($arResult["ITEMS"])){?>
    <article class="search-category clearfix">
        <aside>
            <a href="/catalog/">Интернет-магазин</a>
        </aside>

        <section>
            <ul>
                <? foreach ($arResult["ITEMS"] as $arItem) : ?>
                    <li class="search-result">
                        <? if ($arItem["PICTURE"]) : ?>
                            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="picture">
                                <img src="<?= $arItem["PICTURE"] ?>" alt="<?= $arItem["NAME"] ?>">
                            </a>
                        <? endif; ?>
                        <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="title">
                            <? if ($arItem['IS_SPECIAL']): ?>
                                <span class="promo">Акция!</span>
                            <? endif; ?>
                            <?= $arItem["NAME"] ?>
                        </a>
                        <? if ($arItem['HAVE_ANALOGS']): ?>
                            <a href="<?= $arItem["DETAIL_PAGE_URL"] ?>" class="analogs-link">Аналоги</a>
                        <? endif; ?>
                    </li>
                <? endforeach; ?>
            </ul>
            <? if ($arResult["MORE"] > 3): ?>
                <a href="/search/?q=<?= $queryWord; ?>" class="more">
                    еще найдено товаров <?= (int)$arResult["MORE"]?> »</a>
            <? endif; ?>
        </section>
    </article>
<?}else{
    global $arAddFilter;
    $_REQUEST['q'] .= '*';
    $APPLICATION->IncludeComponent("bitrix:search.page", "search-full", array(
        "RESTART" => "N",
        "NO_WORD_LOGIC" => "N",
        "CHECK_DATES" => "N",
        "USE_TITLE_RANK" => "N",
        "DEFAULT_SORT" => "rank",
        "FILTER_NAME" => "",
        "arrFILTER" => array(
            0 => "iblock_catalog",
            1 => "iblock_filter_category"
        ),
        "arrFILTER_iblock_catalog" => array(
            0 => IBLOCK_ID_CATALOG,
        ),
        "SHOW_WHERE" => "N",
        "SHOW_WHEN" => "N",
        "PAGE_RESULT_COUNT" => "3",
        "AJAX_MODE" => "Y",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "AJAX_OPTION_HISTORY" => "N",
        "CACHE_TYPE" => "A",
        "CACHE_TIME" => "3600",
        "DISPLAY_TOP_PAGER" => "Y",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "PAGER_TITLE" => "title",
        "PAGER_SHOW_ALWAYS" => "Y",
        "PAGER_TEMPLATE" => "",
        "USE_LANGUAGE_GUESS" => "N",
        "USE_SUGGEST" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
    ),
        false
    );
}?>
