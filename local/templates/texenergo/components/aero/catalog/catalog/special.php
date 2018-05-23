<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
$APPLICATION->AddChainItem('Распродажа', '#');
$APPLICATION->SetTitle('Распродажа');

$APPLICATION->AddHeadString('<meta property="og:type" content="website" />');
$APPLICATION->AddHeadString('<meta property="og:locale" content="ru_RU" />');
$APPLICATION->AddHeadString('<meta property="og:title" content="Распродажа техэнерго"/>');
$APPLICATION->AddHeadString('<meta property="og:url" content="http://'.SITE_SERVER_NAME.$APPLICATION->GetCurPage().'"/>');
$APPLICATION->AddHeadString('<meta property="og:description" content="Распродажа техэнерго" />');
$APPLICATION->AddHeadString('<meta property="og:image" content="'.SITE_TEMPLATE_PATH.'/img/header/logo.png" />');

/**
 * Текущий вариант отображения списка товаров (для спецпредложений по умолчанию кратко, а не списком, как в каталоге)
 */

if (!in_array($_COOKIE['catalog_view_special'], array_keys($arResult['CATALOG_SECTION_VIEWS']))) {

    $_COOKIE['catalog_view_special'] = 'grid';
}
$arResult['CATALOG_SECTION_VIEW'] = $_COOKIE['catalog_view_special'];

/**
 * фильтр используется в section_special.php и ниже для подсчета количества спецпредложений
 */
$specialFrom = mktime(date("H"), 0, 0);
$specialTo = $specialFrom + (60*60);

$GLOBALS['catalogSpecialFilter'] = $GLOBALS['f'];
$GLOBALS['catalogSpecialFilter']['PROPERTY_IS_SPECIAL_VALUE'] = 'Y';
$GLOBALS['catalogSpecialFilter']['<=PROPERTY_IS_SPECIAL_FROM'] = date('Y-m-d H:i:s', $specialFrom);
$GLOBALS['catalogSpecialFilter']['>PROPERTY_IS_SPECIAL_TO'] = date('Y-m-d H:i:s', $specialTo);

$cache = new CPHPCache();
$cache_time = 60*60;
$cache_id = 'special_products_id';

$cache_path = '/';
if ($cache_time > 0 && $cache->InitCache($cache_time, $cache_id, $cache_path))
{
   $res = $cache->GetVars();
   $specialIds = $res["ids"];

}else{
	$specialIds = array();

	$arFilter = array('IBLOCK_ID' => $arParams['IBLOCK_ID'], 'ACTIVE' => 'Y');
	$arFilter = array_merge($arFilter, $GLOBALS['catalogSpecialFilter']);

	$dbElems = CIBlockElement::GetList(array(), array($arFilter), false, false, array('ID'));
	while($elem = $dbElems->Fetch()){
		$specialIds[] = $elem['ID'];
	}
	$cache->StartDataCache($cache_time, $cache_id, $cache_path);
	$cache->EndDataCache(array("ids" => $specialIds));
}

$arSection = array(
	'NAME' => 'Распродажа',
	'UF_ELEMENT_CNT' => count($specialIds),
	'ELEMENT_IDS' => (count($specialIds) > 0) ? $specialIds : false
);

?>

<input type='checkbox' name='texenergoVendor' id='texenergoVendor' class='catalog-input-hide j-texenergo-vendor'
       <? if ($arResult['FILTER_BY_VENDOR']): ?>checked='checked'<? endif; ?>>

<div class="group-switch group-switch-catalog own-production-inside">
    <div class="subSwitch j_catalog_view <? if ($arResult['FILTER_BY_VENDOR']): ?>subSwitch-on<? endif; ?>"
         data-param='texenergoVendor'></div>
    <span>Только товары TEXENERGO</span>
</div>


<div class="twelve">
    <h1 class="headline inside-headline">
        <?= $arSection['NAME']; ?>
        <? if ($arSection['UF_ELEMENT_CNT'] > 0): ?>
            <small class="counter"><?= $arSection['UF_ELEMENT_CNT']; ?></small>
        <? endif; ?>
		<? //$APPLICATION->ShowViewContent('special_element_count'); ?>
    </h1>


    <aside class="share">
        <ul>
			<li>
				<a href="#" class="" id="product-share-trigger" title="Поделиться товаром"></a>
			</li>
        </ul>

		<div class="share-block" id="share_block" style="display:none;">
			<a class="share-social share-fb"
				onclick="Share.facebook(window.location.href, '<?=$arResult['NAME']?>', '<?=$arResult['OG_PHOTO']?>', '<?=$arResult['OG_DESCRIPTION']?>')">
			</a>
			<a class="share-social share-vk"
				onclick="Share.vkontakte(window.location.href, '<?=$arResult['NAME']?>', '<?=$arResult['OG_PHOTO']?>', '<?=$arResult['OG_DESCRIPTION']?>')">
			</a>
			<a class="share-social share-tw"
				onclick="Share.twitter(window.location.href, '<?=$arResult['NAME']?>')">
			</a>
			<a class="share-social share-od"
				onclick="Share.odnoklassniki(window.location.href, '')">
			</a>
		</div>

    </aside>

</div>

<div class="nine nine-fullwidth">
    <section class="catalog">

        <nav class="sort">
            <span class="text">Сортировка:</span>
            <select class="j-catalog-sort-id j-sort-select product-sort-top">
                <? foreach ($arResult['SORT_FIELDS'] as $arField): ?>
                    <option
                        value="<?= $arField['CODE']; ?>" <?= ($arResult['SORT_FIELD'] == $arField['CODE'] ? 'selected' : ''); ?>>
                        <?= $arField['NAME']; ?>
                    </option>
                <? endforeach; ?>
            </select>

            <span class="text">Отображение товаров:</span>
            <select class="j-catalog-view-id-special j-goods-full-view j-sort-select product-sort-top">
                <? foreach ($arResult['CATALOG_SECTION_VIEWS'] as $sVal => $sView): ?>
                    <option
                        value="<?= $sVal; ?>" <?= ($arResult['CATALOG_SECTION_VIEW'] == $sVal ? 'selected' : ''); ?>>
                        <?= $sView; ?>
                    </option>
                <? endforeach; ?>
            </select>

        </nav>

		<?
        $GLOBALS['arCatalogBrandFilter'] = Array(
            'PROPERTY_VENDOR_POPULAR_ON_VALUE' => 'Y',
        );
        ?>

        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "brands",
            Array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => IBLOCK_ID_BRANDS,
                "NEWS_COUNT" => "10000",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "arCatalogBrandFilter",
                "FIELD_CODE" => array(),
                "PROPERTY_CODE" => array("VENDOR_POPULAR_ON"),
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "SET_TITLE" => "N",
                "SET_STATUS_404" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "PAGER_TEMPLATE" => ".default",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "",
                "PAGER_SHOW_ALWAYS" => "Y",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "Y",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N"
            ),
            $component
        );?>


        <?
        /**
         * Показывать товары только производства Техэнерго
         */
        if ($arResult['FILTER_BY_VENDOR']) {
            $GLOBALS['f'] = Array(
                'PROPERTY_IS_OWN_VALUE' => 'Y',
            );
        }

        if(!empty($specialIds)){
            unset($GLOBALS['catalogSpecialFilter']['PROPERTY_IS_SPECIAL_VALUE']);
			unset($GLOBALS['catalogSpecialFilter']['<=PROPERTY_IS_SPECIAL_FROM']);
//			unset($GLOBALS['catalogSpecialFilter']['>PROPERTY_IS_SPECIAL_TO']);

            $GLOBALS['catalogSpecialFilter']['ID'] = $specialIds;
            include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/section_special.php';
        }?>
    </section>
</div>