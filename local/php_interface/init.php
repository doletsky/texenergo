<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/include/helpers/iblock_tools.php';
/**
 * Константы
 */
if(getenv('PRODUCTION')==1){
    require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/defines/production.php';
} else {
	require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/defines/development.php';
}


if ($iblockBrands = COption::GetOptionInt('aero.import', 'iblock_brands', 0)) {
    define ('IBLOCK_ID_BRANDS', $iblockBrands); // ИБ производители
}

if ($iblockCatalog = COption::GetOptionInt('aero.import', 'iblock_catalog', 0)) {
    define ('IBLOCK_ID_CATALOG', $iblockCatalog); // ИБ каталог товаров
}

if ($iblockSeries = COption::GetOptionInt('aero.import', 'iblock_series', 0)) {
    define ('IBLOCK_ID_SERIES', $iblockSeries); // ИБ серии
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/functions.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/mobile_detect_settings.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/user_auth.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/basket_click.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/publication_backlink.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/product_tracking.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/reclamation_prop.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/notify_about_reclamation.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/notify_webform_sent.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/iblock_agents.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/refresh_delivery_profiles.php';

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/unsubscribe_link.php';

//require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/searchIndex.php';

CModule::AddAutoloadClasses(
	'',
	array(
		'CProductTracking' => '/local/php_interface/include/helpers/product_tracking.php',
		'aero\CDocGen' => '/local/php_interface/include/helpers/docgen.php',
		'aero\CDeliveryDocGenerator' => '/local/php_interface/include/helpers/CDeliveryDocGenerator.php'
	)
);


use Bitrix\Main\Loader;
use Texenergo\Api\Init as Api;

if (Loader::includeModule('texenergo.api')) {
    $api = new Api();
    $api->start();
}

use Clientste\Api\Init as ApiClients;

if (Loader::includeModule('clientste.api')) {
    $apiClients = new ApiClients();
    $apiClients->start();
}

/**
 * Логика работы с платежными поручениями и счетами
 */
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/iblock_orders.php';


function getGoodsProperty($categoryId, $goodsIds)
{
    if (empty ($goodsIds)) {
        return array();
    }
    $elements = getGoodsValueElements($goodsIds);
    $list = array();
    $valuesIds = array();
    $filterIds = array();
    while ($row = $elements->GetNext()) {
        $valuesIds[] = $row['PROPERTY_GOODS_FILTER_VALUE_VALUE'];
    }

    $valuesIds = array_unique($valuesIds);

    $namesElements = getValueNameElements($valuesIds);
    $names = array();
    while ($el = $namesElements->GetNext()) {
        $filterId = $el['IBLOCK_SECTION_ID'];
        $filterIds[] = $filterId;
        if (!$filterId) {
            continue;
        }

        $names[$el['ID']] = array(
            'filterId' => $filterId, 'name' => $el['NAME'], 'id' => $el['ID'], 'desc' => $el['PROPERTY_VALUE_DESCRIPTION_VALUE']
        );
    }

    if (empty ($filterIds)) {
        return array();
    }

    $filterList = getFilterListByFilterIds($filterIds);

    $elements->NavStart();
    while ($row = $elements->GetNext()) {
        $valueId = $row['PROPERTY_GOODS_FILTER_VALUE_VALUE'];
        $params = $names[$valueId];
        if (!isset ($filterList[$params['filterId']])) {
            continue;
        }
        $list['goods-' . $row['ID']]['filter-' . $params['filterId']]['value-' . $params['id']] = array(
            'valueId' => $params['id'], 'name' => $params['name'], 'desc' => $params['desc']
        );
    }
    if (empty ($list)) {
        return array();
    }

    $filterSet = array();

    foreach ($filterList as $key => $filter) {
        $filterId = $filter['ID'];
        $i = 0;
        $set2 = array();
        foreach ($goodsIds as $goodId) {
            $set = array();
            $valuesArray = isset ($list['goods-' . $goodId]['filter-' . $filterId]) ? $list['goods-' . $goodId]['filter-' . $filterId] : array();
            foreach ($valuesArray as $value) {
                $set[] = $value['valueId'];
            }
            if ($i > 0 && $filterList[$key]['diff'] != 1) {

                if (count($set) != count($set2)) {
                    $filterList[$key]['diff'] = 1;
                } else {
                    if (count($set) > count($set2)) {
                        if (count(array_diff($set, $set2))) {
                            $filterList[$key]['diff'] = 1;
                        }
                    } else {
                        if (count(array_diff($set2, $set))) {
                            $filterList[$key]['diff'] = 1;
                        }
                    }
                }
            }
            $i++;
            $set2 = $set;
        }
    }

    $result['goodsList'] = $list;

    $result['filterList'] = $filterList;

    return $result;
}

function getGoodsBannerVarsByGoodsIds($goodsIds)
{
    if (empty ($goodsIds)) {
        return array();
    }
    $filter = array(
        'IBLOCK_ID' => 1, 'IBLOCK_LID' => SITE_ID, 'IBLOCK_ACTIVE' => Y, 'ACTIVE' => Y, 'GLOBAL_ACTIVE' => Y, 'ID' => $goodsIds
    );

    $select = array(
        'ID', 'IBLOCK_SECTION_ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PICTURE', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'DETAIL_PICTURE',
        'PROPERTY_GOODS_ART', 'PROPERTY_GOODS_RATE'
    );

    $res = CIBlockElement::GetList(array(), $filter, false, false, $select);
    $result = array();
    while ($ob = $res->GetNextElement()) {
        $vars = $ob->GetFields();
        $vars['DETAIL_PICTURE'] = (0 < $vars['DETAIL_PICTURE'] ? CFile::GetFileArray($vars['DETAIL_PICTURE']) : false);
        $vars['PREVIEW_PICTURE'] = (0 < $vars['PREVIEW_PICTURE'] ? CFile::GetFileArray($vars['PREVIEW_PICTURE']) : false);
        $priceArr = CPrice::GetBasePrice($vars['ID']);
        $vars['PRICE_PROPERTIES'] = $priceArr;
        $result[$vars['ID']] = $vars;
    }
    return $result;
}

function getSerialBannerVarsBySerialIds($serialIds)
{
    if (empty ($serialIds)) {
        return array();
    }
    $filter = array(
        'IBLOCK_ID' => 6, 'IBLOCK_LID' => SITE_ID, 'IBLOCK_ACTIVE' => Y, 'ACTIVE' => Y, 'GLOBAL_ACTIVE' => Y, 'ID' => $serialIds
    );

    $select = array(
        'ID', 'NAME', 'IBLOCK_SECTION_ID', 'IBLOCK_ID', 'DETAIL_PICTURE', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'DETAIL_PICTURE'
    );

    $res = CIBlockElement::GetList(array(), $filter, false, false, $select);
    $result = array();
    while ($ob = $res->GetNextElement()) {
        $vars = $ob->GetFields();
        $vars['DETAIL_PICTURE'] = (0 < $vars['DETAIL_PICTURE'] ? CFile::GetFileArray($vars['DETAIL_PICTURE']) : false);
        $vars['PREVIEW_PICTURE'] = (0 < $vars['PREVIEW_PICTURE'] ? CFile::GetFileArray($vars['PREVIEW_PICTURE']) : false);
        $result[$vars['ID']] = $vars;
    }

    return $result;
}

function getGoodsBannerVarsBySerialIds($serialIds)
{
    if (empty ($serialIds)) {
        return array();
    }
    $filter = array(
        'IBLOCK_ID' => 1, 'IBLOCK_LID' => SITE_ID, 'IBLOCK_ACTIVE' => Y, 'ACTIVE' => Y, 'GLOBAL_ACTIVE' => Y,
        'PROPERTY_GOODS_FILTER_VALUE' => $serialIds
    );

    $select = array(
        'ID', 'IBLOCK_SECTION_ID', 'IBLOCK_ID', 'NAME', 'DETAIL_PICTURE', 'PREVIEW_PICTURE', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'DETAIL_PICTURE',
        'PROPERTY_GOODS_ART', 'PROPERTY_GOODS_RATE', 'PROPERTY_GOODS_FILTER_VALUE'
    );

    $res = CIBlockElement::GetList(array(), $filter, false, false, $select);
    $result = array();
    while ($ob = $res->GetNextElement()) {
        $vars = $ob->GetFields();

        $vars['DETAIL_PICTURE'] = (0 < $vars['DETAIL_PICTURE'] ? CFile::GetFileArray($vars['DETAIL_PICTURE']) : false);
        $vars['PREVIEW_PICTURE'] = (0 < $vars['PREVIEW_PICTURE'] ? CFile::GetFileArray($vars['PREVIEW_PICTURE']) : false);
        $priceArr = CPrice::GetBasePrice($vars['ID']);
        $vars['PRICE_PROPERTIES'] = $priceArr;
        $valId = isset($vars['PROPERTY_GOODS_FILTER_VALUE_VALUE']) ? $vars['PROPERTY_GOODS_FILTER_VALUE_VALUE'] : '';
        if ((int)$valId) {
            if (!isset($result[$valId]['totalCount'])) {
                $result[$valId]['totalCount'] = 0;
            }

            $result[$valId]['totalCount'] += 1;
            if (isset ($result[$valId]['list']) && count($result[$valId]['list']) >= 3) {
                continue;
            }
            $result[$valId]['list'][] = $vars;
        }
    }

    return $result;
}

require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/prop_ref.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/init/realization_status.php';

if(isset($_GET['clear_cache']) && $_GET['clear_cache'] == 'Y'){
    if(class_exists('CIBlockTools')){
        CIBlockTools::Update();
    }
}


//require_once $_SERVER['DOCUMENT_ROOT'].'/local/php_interface/init/recalc_rating.php';