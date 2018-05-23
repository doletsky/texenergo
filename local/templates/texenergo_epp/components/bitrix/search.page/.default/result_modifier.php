<?php
use Bitrix\Main\Entity\Validator\Unique;

$arResult['catalog_search'] = array();
$arResult['action_search'] = array();

$goodsIds = array();
$serialIds = array();
$categoryIds = array();
$goodsList = array();
$categoryList = array();
$actionIds = array();
$actionList = array();
/*
 * valueIds = its filter properties
 */
$valueIds = array();
$valueList = array();

$propertyIds = array();

$firstLevelCategory = array();

$copySearch = $arResult["SEARCH"];
foreach ($copySearch as $key => $arItem) {
    if ($arItem["PARAM1"] == "catalog" && $arItem["PARAM2"] == "1" && strpos($arItem["ITEM_ID"], "S") === false) {
        $goodsIds[] = $arItem["ITEM_ID"];
    } elseif ($arItem["PARAM1"] == "catalog" && $arItem["PARAM2"] == "1" && strpos($arItem["ITEM_ID"], "S") >= 0) {
        $categoryIds[] = (int)str_replace("S", "", $arItem["ITEM_ID"]);
        //     } elseif ($arItem[ "PARAM1" ] == "shop_articles" && $arItem[ "PARAM2" ] == "9" && strpos ($arItem[ "ITEM_ID" ], "S") === false) {
        //         $actionIds[ ] = $arItem[ "ITEM_ID" ];
    } elseif ($arItem["PARAM1"] == "filter_category" && $arItem["PARAM2"] == "4" && strpos($arItem["ITEM_ID"], "S") === false) {
        $valueIds[] = $arItem["ITEM_ID"];
        $valueList[$arItem["ITEM_ID"]] = $arItem;
        unset ($arResult["SEARCH"][$key]);
    }
}

/*
 * BEGIN search for picture properties
 */
$goodsList2Properties = array();
if ($valueIds) {

    $arSelect = array(
        'ID', 'NAME', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'PROPERTY_GOODS_ART', 'PROPERTY_GOODS_FILTER_VALUE',
        'DETAIL_TEXT'
    );
    $arFilter = Array(
        "IBLOCK_ID" => IBLOCK_ID_CATALOG, "IBLOCK_LID" => SITE_ID, "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y", "PROPERTY_GOODS_FILTER_VALUE" => $valueIds
    );

    $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
    while ($ar_res = $res->GetNext()) {
        if (!in_array($ar_res["ID"], $goodsIds)) {
            $goodsIds[] = $ar_res["ID"];
            $goodsList2Properties[$ar_res['ID']] = $ar_res;
        }
    }
}
$goodsIds = array_unique($goodsIds);

/*
 * catIds нужны для сбора информации по категории первого уровня
 */
$catIds = array();
if ($goodsIds) {

    $arSelect = array(
        'ID', 'IBLOCK_SECTION_ID', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'PROPERTY_GOODS_ART', 'PROPERTY_OLD_PRICE',
        'PROPERTY_SHOW_OLD_PRICE', 'PROPERTY_ANALOGS', 'PROPERTY_GOODS_RATE', 'PROPERTY_BLOG_COMMENTS_CNT',
        'CATALOG_WEIGHT', 'CATALOG_WIDTH', 'CATALOG_LENGTH', 'CATALOG_HEIGHT', 'CATALOG_GROUP_1', 'PROPERTY_REST_INDICATE'
    );
    //'PREVIEW_TEXT'
    $res = CIBlockElement::GetList(Array(), $arFilter = Array(
        "IBLOCK_ID" => IBLOCK_ID_CATALOG, "IBLOCK_LID" => SITE_ID, "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y", "ID" => $goodsIds
    ), false, false, $arSelect);
    while ($ob = $res->GetNextElement()) {

        $ar_res = $ob->GetFields();
        if (!isset ($goodsList[$ar_res['ID']])) {
            $goodsList[$ar_res['ID']] = $ar_res;
            if ((int)$ar_res['IBLOCK_SECTION_ID']) {
                if (!isset ($catIds[$ar_res['IBLOCK_SECTION_ID']])) {
                    $catIds[$ar_res['IBLOCK_SECTION_ID']] = 0;
                }
                $catIds[$ar_res['IBLOCK_SECTION_ID']] += 1;
            } else {
                $catIds[0] += 1;
            }
        }
        if (isset ($ar_res['PROPERTY_GOODS_FILTER_VALUE_VALUE']) && (int)$ar_res['PROPERTY_GOODS_FILTER_VALUE_VALUE']) {
            $goodsList[$ar_res['ID']]['PROPERTY_GOODS_FILTER_VALUE_IDS'][] = $ar_res['PROPERTY_GOODS_FILTER_VALUE_VALUE'];
            $goodsList[$ar_res['ID']]['PROPERTY_GOODS_FILTER_VALUE_IDS'] = array_unique($goodsList[$ar_res['ID']]['PROPERTY_GOODS_FILTER_VALUE_IDS']);
            $propertyIds[] = $ar_res['PROPERTY_GOODS_FILTER_VALUE_VALUE'];
        }
        if (isset ($ar_res['PROPERTY_ANALOGS_VALUE'])) {
            $goodsList[$ar_res['ID']]['PROPERTY_ANALOGS_VALUE_IDS'][] = $ar_res['PROPERTY_ANALOGS_VALUE'];
            $goodsList[$ar_res['ID']]['PROPERTY_ANALOGS_VALUE_IDS'] = array_unique($goodsList[$ar_res['ID']]['PROPERTY_ANALOGS_VALUE_IDS']);
        }
    }
}

$propertyIds = array_unique($propertyIds);

/*
 * BEGIN вычисление свойств
 */

if (count($propertyIds) && count($goodsList)) {
    $propertyIds = array_unique($propertyIds);
    $propertyList = getValueNamesList($propertyIds);
    $curentFilterIds = array();
    foreach ($propertyList as $valueItem) {
        $curentFilterIds[] = $valueItem['IBLOCK_SECTION_ID'];
    }
    $curentFilterIds = array_unique($curentFilterIds);
    $curretFilterList = getFilterListByIds($curentFilterIds);

    foreach ($goodsList as &$arItem) {
        if (isset ($arItem['PROPERTY_GOODS_FILTER_VALUE_IDS']) && is_array($arItem['PROPERTY_GOODS_FILTER_VALUE_IDS'])) {
            foreach ($arItem['PROPERTY_GOODS_FILTER_VALUE_IDS'] as $id) {
                if (isset ($propertyList[$id])) {
                    $filterId = $propertyList[$id]['IBLOCK_SECTION_ID'];
                    $arItem['FILTER'][$id] = $propertyList[$id];
                    if (isset ($curretFilterList[$filterId])) {
                        $arItem['FILTER'][$id]['FILTER_VARS'] = $curretFilterList[$filterId];
                    }
                }
            }
        }
    }
}
/*
 * END вычисление свойств
*/

if ($categoryIds) {
    $arSelect2 = array(
        'ID', 'PICTURE'
    );
    $res2 = CIBlockSection::GetList(Array(), $arFilter = Array(
        "IBLOCK_ID" => IBLOCK_ID_CATALOG, "IBLOCK_LID" => SITE_ID, "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y", "ID" => $categoryIds
    ), false, $arSelect2);
    while ($ar_res2 = $res2->GetNext()) {
        $categoryList[$ar_res2['ID']] = $ar_res2;
    }
}

if ($actionIds) {
    $arSelect = array(
        'ID', 'PREVIEW_PICTURE', 'PROPERTY_GOODS', 'PROPERTY_GOODS_SERIAL'
    );
    $res = CIBlockElement::GetList(Array(), $arFilter = Array(
        "IBLOCK_ID" => 9, "IBLOCK_LID" => SITE_ID, "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y", "ID" => $actionIds
    ), false, false, $arSelect);
    while ($ar_res = $res->GetNext()) {
        $actionList[$ar_res['ID']] = $ar_res;
    }
}
/*
 * END search for picture properties
*/

foreach ($goodsList2Properties as $item) {
    $item["PARAM1"] = 'catalog';
    $item["PARAM2"] = '1';
    $item["ITEM_ID"] = $item["ID"];
    $item["TITLE"] = $item["NAME"];
    $item["BODY_FORMATED"] = $item["DETAIL_TEXT"];
    $item["property"] = isset ($valueList[$item["PROPERTY_GOODS_FILTER_VALUE_VALUE"]]) ? $valueList[$item["PROPERTY_GOODS_FILTER_VALUE_VALUE"]] : '';

    $arResult["SEARCH"][] = $item;
}

$goodsIdsInCompareList = getGoodsIdsInCompareList();


foreach ($arResult["SEARCH"] as &$arItem) {
    if ($arItem["PARAM1"] == "catalog" && $arItem["PARAM2"] == IBLOCK_ID_CATALOG && strpos($arItem["ITEM_ID"], "S") === false) {
        $arItem["PREVIEW_PICTURE"] = "";
        if (isset ($goodsList[$arItem["ITEM_ID"]])) {
            $picID = $goodsList[$arItem["ITEM_ID"]]["PREVIEW_PICTURE"];
            $arItem["PREVIEW_PICTURE"] = $picID > 0 ? CFile::GetFileArray($picID) : "";
            $arItem["PROPERTY_GOODS_ART_VALUE"] = $goodsList[$arItem["ITEM_ID"]]["PROPERTY_GOODS_ART_VALUE"];
            $arItem["IBLOCK_SECTION_ID"] = $goodsList[$arItem["ITEM_ID"]]["IBLOCK_SECTION_ID"];
            $arItem["PREVIEW_TEXT"] = $goodsList[$arItem["ITEM_ID"]]["PREVIEW_TEXT"];
            $arItem["FILTER"] = isset ($goodsList[$arItem["ITEM_ID"]]["FILTER"]) ? $goodsList[$arItem["ITEM_ID"]]["FILTER"] : '';

            $arItem['PROPERTY_GOODS_RATE_VALUE'] = isset ($goodsList[$arItem["ITEM_ID"]]['PROPERTY_GOODS_RATE_VALUE']) ? $goodsList[$arItem["ITEM_ID"]]['PROPERTY_GOODS_RATE_VALUE'] : '';
            $arItem['PROPERTY_GOODS_ART'] = isset ($goodsList[$arItem["ITEM_ID"]]['PROPERTY_GOODS_ART']) ? $goodsList[$arItem["ITEM_ID"]]['PROPERTY_GOODS_ART'] : '';
            $arItem['PROPERTY_OLD_PRICE_VALUE'] = isset ($goodsList[$arItem["ITEM_ID"]]['PROPERTY_OLD_PRICE_VALUE']) ? $goodsList[$arItem["ITEM_ID"]]['PROPERTY_OLD_PRICE_VALUE'] : '';
            $arItem['PROPERTY_ANALOGS_VALUE_IDS'] = isset ($goodsList[$arItem["ITEM_ID"]]['PROPERTY_ANALOGS_VALUE_IDS']) && count($goodsList[$arItem["ITEM_ID"]]['PROPERTY_ANALOGS_VALUE_IDS']) ? true : false;
            $arItem['PROPERTY_BLOG_COMMENTS_CNT_VALUE'] = isset ($goodsList[$arItem["ITEM_ID"]]['PROPERTY_BLOG_COMMENTS_CNT_VALUE']) ? $goodsList[$arItem["ITEM_ID"]]['PROPERTY_BLOG_COMMENTS_CNT_VALUE'] : '';
            $arItem['PROPERTY_REST_INDICATE_VALUE'] = isset ($goodsList[$arItem["ITEM_ID"]]['PROPERTY_REST_INDICATE_VALUE']) ? $goodsList[$arItem["ITEM_ID"]]['PROPERTY_REST_INDICATE_VALUE'] : '';

            $arItem['CATALOG_WEIGHT'] = $goodsList[$arItem["ITEM_ID"]]['CATALOG_WEIGHT'];
            $arItem['CATALOG_WIDTH'] = $goodsList[$arItem["ITEM_ID"]]['CATALOG_WIDTH'];
            $arItem['CATALOG_LENGTH'] = $goodsList[$arItem["ITEM_ID"]]['CATALOG_LENGTH'];
            $arItem['CATALOG_HEIGHT'] = $goodsList[$arItem["ITEM_ID"]]['CATALOG_HEIGHT'];

            $arItem['CATALOG_PURCHASING_PRICE'] = $goodsList[$arItem["ITEM_ID"]]['CATALOG_PURCHASING_PRICE'];
            $arItem['CATALOG_QUANTITY'] = $goodsList[$arItem["ITEM_ID"]]['CATALOG_QUANTITY'];
            $arItem['CATALOG_MEASURE'] = $goodsList[$arItem["ITEM_ID"]]['CATALOG_MEASURE'];

            $arItem['CATALOG_PRICE_1'] = $goodsList[$arItem["ITEM_ID"]]['CATALOG_PRICE_1'];
            $arItem['CATALOG_CURRENCY_1'] = $goodsList[$arItem["ITEM_ID"]]['CATALOG_CURRENCY_1'];


            $arItem["~COMPARE_URL"] = "/personal/compare.php?action=ADD_TO_COMPARE_LIST&id=" . $arItem['ID'];
            $arItem["COMPARE_URL"] = htmlspecialcharsbx($arItem["~COMPARE_URL"]);
            $arItem["~COMPARE_DELETE_URL"] = "/personal/compare.php?action=DELETE_FROM_COMPARE_LIST&id=" . $arItem['ID'];
            $arItem["COMPARE_DELETE_URL"] = htmlspecialcharsbx($arItem["~COMPARE_DELETE_URL"]);
            $arItem["~BUY_URL"] = '/catalog/?action=ADD2BASKET&id=' . $arItem["ID"];
            $arItem["BUY_URL"] = htmlspecialcharsbx($arItem["~BUY_URL"]);

            $arItem["~ADD_URL"] = '/catalog/?action=BUY&id=' . $arItem["ID"];
            $arItem["ADD_URL"] = htmlspecialcharsbx($arItem["~ADD_URL"]);

            $sectionId = (int)$arItem['IBLOCK_SECTION_ID'] ? $arItem['IBLOCK_SECTION_ID'] : -1;
            $arItem["ANALOG_URL"] = '/catalog/?SECTION_ID=' . $sectionId . '&ELEMENT_ID=' . $arItem["ID"] . '#tAnalogs';
            $arItem['IN_COMPARE_LIST'] = false;
            if (is_array($goodsIdsInCompareList) && in_array($arItem['ID'], $goodsIdsInCompareList)) {
                $arItem['IN_COMPARE_LIST'] = true;
            }

        }
    } elseif ($arItem["PARAM1"] == "catalog" && $arItem["PARAM2"] == IBLOCK_ID_CATALOG && strpos($arItem["ITEM_ID"], "S") >= 0) {
        if (isset ($categoryList[(int)str_replace("S", "", $arItem["ITEM_ID"])])) {
            $picID = $categoryList[(int)str_replace("S", "", $arItem["ITEM_ID"])]["PICTURE"];
            $arItem["PICTURE"] = $picID > 0 ? CFile::GetFileArray($picID) : "";
        }
    }
    //     } elseif ($arItem[ "PARAM1" ] == "shop_articles" && $arItem[ "PARAM2" ] == "9" && strpos ($arItem[ "ITEM_ID" ], "S") === false) {
    //         $arItem[ "PREVIEW_PICTURE" ] = "";
    //         if (isset ($actionList[ $arItem[ "ITEM_ID" ] ])) {
    //             $picID = $actionList[ $arItem[ "ITEM_ID" ] ][ "PREVIEW_PICTURE" ];
    //             $arItem[ "PREVIEW_PICTURE" ] = $picID > 0 ? CFile::GetFileArray ($picID) : "";
    //         }
    //     }
}
/*
 * BEGIN search for goods that takes part in actions
 */

$actionGoodsIds = array();

if ($goodsIds) {
    $rsElement = CIBlockElement::GetList(array(
        'NAME' => 'ASC'
    ), array(
        "IBLOCK_ID" => 9, "IBLOCK_LID" => SITE_ID, "ACTIVE" => "Y", "CHECK_PERMISSIONS" => "Y", "PROPERTY_GOODS" => $goodsIds
    ), false, array(
        'nTopCount' => 100000
    ), array(
        "ID", "IBLOCK_ID", "NAME", "PROPERTY_GOODS", "GOODS_SERIAL"
    ));
    while ($row = $rsElement->GetNext()) {
        $actionGoodsIds[] = $row['PROPERTY_GOODS_VALUE'];
    }
}

/*
 * END search for goods that takes part in actions
*/

foreach ($arResult["SEARCH"] as &$arItem) {
    if ($arItem["PARAM1"] == "catalog" && $arItem["PARAM2"] == "1" && strpos($arItem["ITEM_ID"], "S") === false) {
        $arItem['IsAction'] = 0;
        if (in_array($arItem["ITEM_ID"], $actionGoodsIds)) {
            $arItem['IsAction'] = 1;
            $arResult['action_search']['list'][] = $arItem;
        }
        $arResult['catalog_search']['list'][] = $arItem;
    } elseif ($arItem["PARAM1"] == "catalog" && $arItem["PARAM2"] == "1" && strpos($arItem["ITEM_ID"], "S") >= 0) {
        // $arResult[ 'catalog_search' ][ 'list' ][ ] = $arItem;
    }
    //     } elseif ($arItem[ "PARAM1" ] == "shop_articles" && $arItem[ "PARAM2" ] == "9") {
    //         $arResult[ 'action_search' ][ 'list' ][ ] = $arItem;
    //     }
}

/*
 * BEGIN вычисление категорий первого уровня у товаров
 */

$firstLevel = array();
$word = isset ($arResult["REQUEST"]["QUERY"]) ? $arResult["REQUEST"]["QUERY"] : '';
foreach ($catIds as $catId => $count) {
    $catId = (int)$catId;
    if ($catId > 0 && !isset ($firstLevel[$catId])) {
        $rsPath = GetIBlockSectionPath(1, $catId);
        $parent = '';
        while ($arPath = $rsPath->GetNext()) {
            $data = array(
                'ID' => $arPath['ID'], 'NAME' => $arPath['NAME'],
                'SEARCH_URL' => "/search/index.php?q=" . $word . "&section=catalog&catId=" . $arPath['ID'], 'COUNT' => 0
            );
            if ($arPath['DEPTH_LEVEL'] == 1) {
                $parent = $data;
                $firstLevel[$catId] = $data;
            } else {
                $firstLevel[$arPath['ID']] = $parent;
            }
        }
    }
}

foreach ($catIds as $catId => $count) {
    if ((int)$catId > 0) {
        $firstLevel[$catId]['COUNT'] += $count;
    } else {
        if (!isset ($firstLevel[$catId])) {
            $firstLevel[$catId]['ID'] = 0;
            $firstLevel[$catId]['NAME'] = 'БЕЗ КАТЕГОРИИ';
            $firstLevel[$catId]['CATALOG_URL'] = '';
            $firstLevel[$catId]['COUNT'] = 0;
        }
        $firstLevel[$catId]['COUNT'] += $count;
    }
}
$firstLevelTransp = array();
foreach ($firstLevel as $key => $params) {
    if ((int)$params['ID'] > 0) {
        if (!isset ($firstLevelTransp[$params['ID']])) {
            $firstLevelTransp[$params['ID']] = $params;
        } else {
            $firstLevelTransp[$params['ID']]['COUNT'] += $params['COUNT'];
        }
    }
}

/*
 * END вычисление категорий первого уровня у товаров
*/

$result = array();
foreach ($arResult["SEARCH"] as $key2 => $arItem2) {

    if (isset ($_REQUEST['section']) && $_REQUEST['section'] == 'action') {
        if ($arItem2["PARAM1"] == "catalog" && $arItem2["PARAM2"] == "1" && strpos($arItem2["ITEM_ID"], "S") == false) {
            if ($arItem2['IsAction']) {
                $result[] = $arItem2;
            }
        }
    } else if (isset ($_REQUEST['section']) && isset ($_REQUEST['catId']) && $_REQUEST['section'] == 'catalog' && (int)$_REQUEST['catId']) {
        if ($arItem2["PARAM1"] == "catalog" && $arItem2["PARAM2"] == "1" && strpos($arItem2["ITEM_ID"], "S") == false) {
            if ($arItem2['IBLOCK_SECTION_ID'] && isset ($firstLevel[$arItem2['IBLOCK_SECTION_ID']])) {

                if ($firstLevel[$arItem2['IBLOCK_SECTION_ID']]['ID'] == $_REQUEST['catId']) {
                    $result[] = $arItem2;
                }
            }
        }
    } else if (isset ($_REQUEST['section']) && $_REQUEST['section'] == 'catalog') {
        if ($arItem2["PARAM1"] == "catalog" && $arItem2["PARAM2"] == "1" && strpos($arItem2["ITEM_ID"], "S") == false) {

            $result[] = $arItem2;
        }
    } else {
        $result[] = $arItem2;
    }
}

unset($arResult["SEARCH"]);
$arResult["SEARCH"] = $result;

$arResult['firstLevel'] = $firstLevelTransp;
$arResult['catalog_search']['totalCount'] = count($arResult['catalog_search']['list']);
$arResult['action_search']['totalCount'] = count($arResult['action_search']['list']);

?>
