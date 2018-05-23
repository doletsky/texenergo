<?
/*
 * BEGIN получить наименования фильтров и наименования значений
 */
$arResult ['propertyTable'] = array ();
$valuesIds = array ();
$goodsIdsInCompareList = getGoodsIdsInCompareList ();
foreach ( $arResult ["ITEMS"] as &$arItem ) {
	if (isset ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] ) && is_array ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] )) {
		foreach ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] as $itemId ) {
			$valuesIds [] = $itemId;
		}
	}
}

$propertyTable = array (
		array (
				'ID' => 'CATALOG_WEIGHT',
				'NAME' => 'Вес',
				'TYPE' => 1
		),
		array (
				'ID' => 'CATALOG_WIDTH',
				'NAME' => 'Ширина',
				'TYPE' => 1
		),
		array (
				'ID' => 'CATALOG_LENGTH',
				'NAME' => 'Длина',
				'TYPE' => 1
		),
		array (
				'ID' => 'CATALOG_HEIGHT',
				'NAME' => 'Высота',
				'TYPE' => 1
		),
		array (
				'ID' => 'CATALOG_MEASURE',
				'NAME' => 'Единица измерения',
				'TYPE' => 1
		)
);
foreach ($propertyTable as &$property) {
	$vals = array();
	foreach ( $arResult ["ITEMS"] as $arItem2 ) {
		if (isset($arItem2[$property['ID']])) {
			$vals[] = $arItem2[$property['ID']];
		}
	}
	if (count(array_unique($vals)) == 1 && count($vals) == count($arResult ["ITEMS"])) {
		$property ['diff'] = 0;
	}else {
		$property ['diff'] = 1;
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
		if (isset ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] ) && is_array ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] )) {
			foreach ( $arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE'] as $id ) {
				if (isset ( $valuesList [$id] )) {
					$filterId = $valuesList [$id] ['IBLOCK_SECTION_ID'];
					$arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE_NAMES'] [$id] = $valuesList [$id];
					if (isset ( $curretFilterList [$filterId] )) {
						$arItem ['PROPERTIES'] ['GOODS_FILTER_VALUE'] ['VALUE_NAMES'] [$id] ['FILTER_VARS'] = $curretFilterList [$filterId];
					}
					$arItem ['filterId' . $filterId] = $valuesList [$id] ['NAME'];
				}
			}
		}
	}
	
	foreach ( $curretFilterList as $filter ) {
		if (isset($arResult["my_compare_property_list"][$filter['ID']])) {
			$propertyTable[$filter['ID']] = $arResult["my_compare_property_list"][$filter['ID']];
		}
		$propertyTable [$filter ['ID']] ['ID'] = $filter ['ID'];
		$propertyTable [$filter ['ID']] ['NAME'] = $filter ['NAME'];
		$propertyTable [$filter ['ID']] ['TYPE'] = 0;
	}
	
}
$arResult ['propertyTable'] = $propertyTable;

/*
 * END получить наименования фильтров и наименования значений
 */

?>