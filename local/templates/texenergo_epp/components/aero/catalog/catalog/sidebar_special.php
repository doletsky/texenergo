<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?$APPLICATION->IncludeComponent(
    "bitrix:search.form",
    "catalog-search",
    Array(
        "AJAX_MODE" => "N",
        "USE_SUGGEST" => "N",
        "PAGE" => "#SITE_DIR#search/"
    ),
    $component
);?>

<?
$arParentSection = Array(
	'NAME' => 'Каталог товаров',
	'SECTION_PAGE_URL' => $arResult['FOLDER'],
);
?>

<div class="head">
    <p>
        <a href="<?= $arParentSection['SECTION_PAGE_URL']; ?>"><?= $arParentSection['NAME']; ?></a>
    </p>
</div>

<? // if ($arSection['ELEMENT_CNT'] > 0): ?>
<?
if ($arResult['FILTER_BY_VENDOR']) {
    $GLOBALS['f']['IS_OWN'] = PROP_IS_OWN_Y_ID;
}
?>

<ul>
    <li class="head head-sub no-arrow-head">
        <p>
            <a><?= $arSection['NAME']; ?></a>
        </p>
    </li>
</ul>

<? if ($arSection['UF_ELEMENT_CNT'] < 5000): ?>

    <?
	global $specialFilter;
	
	$specialFilter['ID'] = $arSection['ELEMENT_IDS'];
	$APPLICATION->IncludeComponent(
		"aero:catalog.smart.filter",
		"",
		Array(
			"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
			"IBLOCK_ID" => $arParams["IBLOCK_ID"],				
			"FILTER_NAME" => 'f', //$arParams["FILTER_NAME"],
			"INNER_FILTER_NAME" => 'specialFilter',
			"PRICE_CODE" => Array("base_price"), //$arParams["PRICE_CODE"],
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"CACHE_NOTES" => "",
			"CACHE_GROUPS" => "N",
			"SAVE_IN_SESSION" => "N",
			"INSTANT_RELOAD" => "Y",
			"XML_EXPORT" => "N",
			"SECTION_TITLE" => "NAME",
			"SECTION_DESCRIPTION" => "DESCRIPTION"
		),
		$component,
		array('HIDE_ICONS' => 'Y')
	);
    ?>
<? endif; ?>

<?
if ($arResult['FILTER_BY_VENDOR']) {
    $sFilterName = 'arSidebarVendorFilterOwn';
    $GLOBALS[$sFilterName] = Array(
        'PROPERTY' => Array(
            'IS_OWN' => PROP_IS_OWN_Y_ID,
        ),
    );
} else {
    $sFilterName = 'arSidebarVendorFilter';
}
?>
<?$APPLICATION->IncludeComponent(
    "texenergo:catalog.section.list",
    "sidebar",
    array(
        "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
        "CACHE_TYPE" => $arParams["CACHE_TYPE"],
        "CACHE_TIME" => $arParams["CACHE_TIME"],
        "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
        "COUNT_ELEMENTS" => "N",
        "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
        "SECTION_URL" => $arResult["FOLDER"] . $arResult["URL_TEMPLATES"]["section"],
        "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
        "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
        "SECTION_USER_FIELDS" => array('UF_*'),
        "FILTER_NAME" => $sFilterName,
        "SECTION_ID" => $arSection['ID'],
        "CURRENT_SECTION" => $arSection,
    ),
    $component
);?>

