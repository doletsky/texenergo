<?
/*
 * BEGIN получить наименования фильтров и наименования значений
 * 
*/
$valuesIds = array ();
$goodsIdsInCompareList = getGoodsIdsInCompareList ();
foreach ($arResult ["ITEMS"] as &$arItem ) {
	
	if($arItem['GOODS_LIST']) {
	    foreach ($arItem['GOODS_LIST'] as $arActionProduct) {

			if (isset ( $arActionProduct ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] ) && is_array ( $arActionProduct ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] )) {
				foreach ( $arActionProduct ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] as $itemId ) {
					$valuesIds [] = $itemId;
				}
			}

	    }
	}

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
		if($arItem['GOODS_LIST']) {
		    foreach ($arItem['GOODS_LIST'] as $arActionProduct) {

				if (isset ( $arActionProduct ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] ) && is_array ( $arActionProduct ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] )) {
					foreach ( $arActionProduct ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] as $id ) {
						if (isset ( $valuesList [$id] )) {
							$filterId = $valuesList [$id] ['IBLOCK_SECTION_ID'];
							$arActionProduct['PROPERTIES']['GOODS_FILTER_VALUE']['VALUE_NAMES'][$id] = $valuesList[$id];
							if (isset ( $curretFilterList [$filterId] )) {
								$arActionProduct ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE_NAMES'] [$id] ['FILTER_VARS'] = $curretFilterList [$filterId];
							}
						}
					}
				}

		    }
		}
	}
}
/*
 * END получить наименования фильтров и наименования значений
*
*/
?>