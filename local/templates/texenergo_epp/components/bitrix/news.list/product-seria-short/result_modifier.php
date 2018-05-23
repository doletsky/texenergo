<?
$valuesIds = array ();
$goodsIdsInCompareList = getGoodsIdsInCompareList ();
foreach ( $arResult ["ITEMS"] as &$arItem ) {
	$arItem ["~COMPARE_URL"] = "/personal/compare.php?action=ADD_TO_COMPARE_LIST&id=" . $arItem ['ID'];
	$arItem ["COMPARE_URL"] = htmlspecialcharsbx ( $arItem ["~COMPARE_URL"] );
	$arItem ["~COMPARE_DELETE_URL"] = "/personal/compare.php?action=DELETE_FROM_COMPARE_LIST&id=" . $arItem ['ID'];
	$arItem ["COMPARE_DELETE_URL"] = htmlspecialcharsbx ( $arItem ["~COMPARE_DELETE_URL"] );
	$arItem["~BUY_URL"] = $APPLICATION->GetCurPageParam("action=BUY&id=".$arItem["ID"], array('id', 'action'));
	$arItem["BUY_URL"] = htmlspecialcharsbx($arItem["~BUY_URL"]);
	$arItem["~ADD_URL"] = $APPLICATION->GetCurPageParam("action=ADD2BASKET&id=".$arItem["ID"],  array('id', 'action'));
	$arItem["ADD_URL"] = htmlspecialcharsbx($arItem["~ADD_URL"]);
	$arItem ['IN_COMPARE_LIST'] = false;
	if (is_array ( $goodsIdsInCompareList ) && in_array ( $arItem ['ID'], $goodsIdsInCompareList )) {
		$arItem ['IN_COMPARE_LIST'] = true;
	}
	if (isset ( $arItem ['PROPERTIES'] ['GOODS_PHOTOS'] ['VALUE'] ) && is_array ( $arItem ['PROPERTIES'] ['GOODS_PHOTOS'] ['VALUE'] )) {
		foreach ( $arItem ['PROPERTIES'] ['GOODS_PHOTOS'] ['VALUE'] as $k => $fileId ) {
			$arItem ['PROPERTIES'] ['GOODS_PHOTOS'] ['VALUE'] [$k] = (0 < $fileId ? CFile::GetFileArray ( $fileId ) : false);
		}
	}
	/*
	 * BEGIN получить наименования фильтров и наименования значений
	 */
	
	if (isset ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] ) && is_array ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] )) {
		foreach ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] as $itemId ) {
			$valuesIds [] = $itemId;
		}
	}
	
	/*
	 * END получить наименования фильтров и наименования значений
	 */
}

if (count ( $valuesIds )) {
	$valuesIds = array_unique ( $valuesIds );
	$valuesList = getValueNamesList ( $valuesIds );
	$curentFilterIds = array ();
	foreach ( $valuesList as $valueItem ) {
		$curentFilterIds [] = $valueItem ['IBLOCK_SECTION_ID'];
	}
	$curentFilterIds = array_unique ( $curentFilterIds );
	$curretFilterList = getFilterListByIds ( $curentFilterIds );
	foreach ( $arResult ["ITEMS"] as &$arItem ) {
		if (isset ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] ) && is_array ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] )) {
			foreach ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] as $id ) {
				if (isset ( $valuesList [$id] )) {
					$filterId = $valuesList [$id] ['IBLOCK_SECTION_ID'];
					$arItem['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE_NAMES'][$id] = $valuesList[$id];
					if (isset ( $curretFilterList [$filterId] )) {
						$arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE_NAMES'] [$id] ['FILTER_VARS'] = $curretFilterList [$filterId];
					}
				}
			}
		}
	}
}
?>