<?php

/**
 * Функция возвращает персональную скидку пользователя в процентах если она установлена
 * @return bool|int
 */
function getUserDiscount() {
    return false;

    /* в настоящее время скидка клиенту не нужна
    global $USER;

    $iPersonalDiscount = 0;

    if ($USER->IsAuthorized()) {

        $arFilter = array('ID' => $USER->GetID());
        $arParams['SELECT'] = array('UF_DISCOUNT');
        $rsUser = CUser::GetList(($by = 'id'), ($order = 'desc'), $arFilter, $arParams);

        if ($arUser = $rsUser->Fetch()) {

            if (intval($arUser['UF_DISCOUNT']) <= 0) return false;
            $iPersonalDiscount = intval($arUser['UF_DISCOUNT']);
        }

    }
    return $iPersonalDiscount;
    */
}

/**
 * Проверка типа запроса
 * @return bool true если AJAX
 */
function isAjaxRequest() {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');
}

/**
 * Функция форматирует цену. Используется везде, где есть вывод цены
 * @param $num
 * @return string
 */
function priceFormat($num) {
    return number_format($num, 2, '.', ' ');
}

/**
 * Получает массив ID товаров, находящихся в корзине
 * Используется в texenergo:catalog.section
 * @param $full
 * @return array
 */
function getGoodsIdsInBasket($full = false) {
    CModule::IncludeModule("sale");

    $arBasketItems = array();

    $dbBasketItems = CSaleBasket::GetList(array(
        "NAME" => "ASC",
        "ID" => "ASC"
    ), array(
        "FUSER_ID" => CSaleBasket::GetBasketUserID(),
        "LID" => SITE_ID,
        "ORDER_ID" => "NULL",
        'DELAY' => 'N',
        'CAN_BUY' => 'Y'
    ), false, false, array(
        'PRODUCT_ID',
        'ID',
        'QUANTITY'
    ));
    while($arItems = $dbBasketItems->Fetch()) {
        if($full) {
            $arItems['QUANTITY'] = (int)$arItems['QUANTITY'];
            $arBasketItems[$arItems['PRODUCT_ID']] = $arItems;
        }
        else {
            $arBasketItems[] = $arItems['PRODUCT_ID'];
        }
    }
    return $arBasketItems;
}

/**
 * Получает массив ID товаров, находящихся в списке сравнения
 * Используется в texenergo:catalog.section
 * Используется в нижней панели
 * @return array
 */
function getGoodsIdsInCompareList() {
    $arResult = array();
    $arSessionItems = $_SESSION['CATALOG_COMPARE_LIST'][IBLOCK_ID_CATALOG]['ITEMS'];
    if(!is_array($arSessionItems)) {
        $arSessionItems = Array();
    }
    foreach($arSessionItems as $arItem) {
        $arResult[] = $arItem['ID'];
    }

    return $arResult;
}

/**
 * Получает массив ID товаров, находящихся в избранном
 * Используется в texenergo:catalog.section
 * Используется в ЛК
 * Используется в нижней панели
 * @return array
 */
function getGoodsIdsInFavorites() {
    global $USER;
    if($USER->isAuthorized()) {
        $arUser = CUser::GetByID($USER->GetID())->Fetch();

        $arFavorites = $arUser['UF_FAVORITES'];
        if(!is_array($arFavorites)) {
            $arFavorites = Array();
        }
        return getExistsProductsId($arFavorites);
    }
    return Array();
}

/**
 * Получает массив ID недавно просмотренных товаров
 * Используется в ЛК
 * Используется в нижней панели
 * @return array
 */
function getGoodsIdsRecentViewed() {
    CModule::IncludeModule('sale');

    $arResult = Array();

    $arFilter = Array(
        'LID' => SITE_ID,
        'FUSER_ID' => CSaleBasket::GetBasketUserID(),
    );
    $rsRecent = CSaleViewedProduct::GetList(
        array(),
        $arFilter,
        false,
        array(
            "nTopCount" => 20
        ),
        array('ID', 'PRODUCT_ID')
    );

    while($arItem = $rsRecent->Fetch()) {
        $arResult[] = $arItem['PRODUCT_ID'];
    }

    return getExistsProductsId($arResult);
}

function getExistsProductsId($ids) {
    if(count($ids) > 0) {
        CModule::IncludeModule('iblock');
        $exIds = array();
        $dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG,
                                                          'ID' => $ids), false, false, array('ID'));
        while($elem = $dbElems->Fetch()) {
            $exIds[] = $elem['ID'];
        }
        return $exIds;
    }
    else {
        return array();
    }
}

function getSortTypeById($typeId) {
    $typeId = (int)$typeId;
    switch($typeId) {
        case 1 :
            return 'ASC';
        case 2 :
            return 'ASC';
        case 3 :
            return 'DESC';
        case 4 :
            return 'DESC';
        case 5 :
            return 'DESC';
        case 6 :
            return 'ASC';
    }
    return 'ASC';
}

function getSortFieldById($typeId) {
    $typeId = (int)$typeId;
    switch($typeId) {
        case 1 :
            return 'catalog_PRICE_1';
        case 2 :
            return 'NAME';
        case 3 :
            return 'CATALOG_QUANTITY';
        case 4 :
            return 'DATE_CREATE';
        case 5 :
            return 'SHOW_COUNTER';
        case 6 :
            return 'SORT';
    }
    return 'SORT';
}

function getSortFieldArray() {
    $arSelect = Array(
        'CODE',
        'NAME'
    );

    $arFilter = Array(
        "IBLOCK_LID" => SITE_ID,
        "IBLOCK_ID" => 10,
        'ACTIVE' => 'Y',
        'GLOBAL_ACTIVE' => 'Y'
    );

    $res = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter, false, Array(
        'nPageSize' => 50
    ), $arSelect);
    $result = array();
    while($ob = $res->GetNextElement()) {
        $vars = $ob->GetFields();
        $result[] = $vars;
    }
    return $result;
}

function wordForm($n) {
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if($n > 10 && $n < 20) {
        return 5;
    }
    if($n1 > 1 && $n1 < 5) {
        return 2;
    }
    if($n1 == 1) {
        return 1;
    }
    return 5;
}

/**
 * Склонение слова
 *
 * @param $n int число
 * @param $f1 1
 * @param $f2 2
 * @param $f5 5
 * @return mixed
 */
function strMorph($n, $f1, $f2, $f5) {
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if($n > 10 && $n < 20) {
        return $f5;
    }
    if($n1 > 1 && $n1 < 5) {
        return $f2;
    }
    if($n1 == 1) {
        return $f1;
    }
    return $f5;
}

function CropStr($string, $limit) {

    if(strlen($string) > $limit) {

        $substring_limited = substr($string, 0, $limit);
        $string = substr($substring_limited, 0, strrpos($substring_limited, ' '));
        $string .= "...";

    }
    else {

        $string = "";

    }

    return $string;

}

function isAjax() {

    return (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && mb_strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') ? true : false;

}

if(!function_exists("mb_ucfirst")) {
    function mb_ucfirst($word) {
        return mb_strtoupper(mb_substr($word, 0, 1, 'UTF-8'), 'UTF-8').mb_substr(mb_convert_case($word, MB_CASE_LOWER, 'UTF-8'), 1, mb_strlen($word), 'UTF-8');
    }
}

function getPricesArray($fullList = false) {
    //    global $USER;
    //    if($USER->IsAuthorized()){
    //        return array("base_price", "price_ws1", "price_ws2", "price_ws3");
    //    }
    //    else {
    //        return array("base_price");
    //    }

    return $fullList
        ? array("base_price", "price_ws1", "price_ws2", "price_ws3")
        : array("base_price");
}

function getBasketPriceMarkup() {
    \Bitrix\Main\Loader::includeModule('sale');
    $arSelect = array(
        'LID' => '',
        'NAME' => '',
        'CURRENCY' => '',
        'DISCOUNT_VALUE' => '',
        'DISCOUNT_TYPE' => 'P',
        'ACTIVE' => 'Y',
        'SORT' => '100',
        'ACTIVE_FROM' => '',
        'ACTIVE_TO' => '',
        'PRIORITY' => 1,
        'LAST_DISCOUNT' => 'Y',
        'CONDITIONS' => '',
        'XML_ID' => '',
        'ACTIONS' => '',
    );
    $arDiscount = CSaleDiscount::GetList(array(), array('XML_ID' => 'order_sum_less_threshold',
                                                        'ACTIVE' => 'Y'), false, false, array_keys($arSelect))->Fetch();
    $arResult = array();
    if($arDiscount) {
        $conditions = unserialize($arDiscount['CONDITIONS']);
        if(isset($conditions['CHILDREN'])) {
            foreach($conditions['CHILDREN'] as $rule) {
                if($rule['CLASS_ID'] == "CondBsktAmtGroup") { // Общая стоимость товаров в корзине
                    $arResult['WHOLESALE_SUM'] = number_format($rule['DATA']['Value'], 0, ".", " ");
                }
            }
        }

        $actions = unserialize($arDiscount['ACTIONS']);

        if(isset($actions['CHILDREN'])) {
            foreach($actions['CHILDREN'] as $rule) {
                if($rule['CLASS_ID'] == "ActSaleBsktGrp") { // наценка к товарам
                    $arResult['WHOLESALE_DISCOUNT'] = $rule['DATA']['Value'];
                    $arResult['WHOLESALE_TYPE'] = $rule['DATA']['Unit'];
                }
            }
        }
    }

    return $arResult;
}

/**
 * @param $string
 * Функция заменяет в строке символы на латинице на похожие символы в кириллице.
 * Используется в поиске для замены похоже выглядящих символов.
 * @return string
 */
function en2ru($string) {
    $converter = array(
        'a' => 'а',
        'e' => 'е',
        'y' => 'у',
        'k' => 'к',
        'm' => 'м',
        'n' => 'н',
        'o' => 'о',
        'p' => 'р',
        'u' => 'и',
        'c' => 'с',
        'A' => 'А',
        'E' => 'Е',
        'Y' => 'У',
        'K' => 'К',
        'M' => 'М',
        'H' => 'Н',
        'O' => 'О',
        'P' => 'Р',
        'U' => 'И',
        'C' => 'С',
    );
    return strtr($string, $converter);
}


function isTrustedUser() {
    global $USER;
    if(!$USER->IsAuthorized()) {
        return false;
    }

    if(isset($_SESSION['trustedUser'])) {
       // return $_SESSION['trustedUser'];
    }


    $showRemnantsGoods = 0;	// показ остатков разрешен / запрещен	
    CModule::IncludeModule('iblock');
    $dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_SITE_SETTINGS, 'PROPERTY_CODE' => 'SHOW_REMNANTS_GOODS', 'ACTIVE' => 'Y'), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_VALUE'));
    while($elem = $dbElems->Fetch()){
	$showRemnantsGoods = $elem['PROPERTY_VALUE_VALUE'];
    }
    
    $group = CGroup::GetList($by = "c_sort", $order = "asc", Array("STRING_ID" => "trustedUsers"))->Fetch();

    if($group && CSite::InGroup([$group['ID']]) && $showRemnantsGoods) {
        $_SESSION['trustedUser'] = true;
        return true;
    }
    $_SESSION['trustedUser'] = false;
    return false;
}

function IsViewCertificate() {
    global $USER;
    if(!$USER->IsAuthorized()) {
        return false;
    }
    $group = CGroup::GetList($by = "c_sort", $order = "asc", Array("STRING_ID" => "ViewCertificate"))->Fetch();

    if($group && CSite::InGroup([$group['ID']])) {
        //$_SESSION['trustedUser'] = true;
        return true;
    }
    //$_SESSION['trustedUser'] = false;
    return false;
}

function getProductRestsInterval($product) {
    $CACHE_LIFE_TIME = 3600*24; // сутки

    $ob_cache_prop = new CPHPCache();
    $cache_id_prop = "restsList";
    $list = [];

    if($ob_cache_prop->InitCache($CACHE_LIFE_TIME, $cache_id_prop, "/")) {
        $cache = $ob_cache_prop->GetVars();
        $list = $cache['list'];
    }
    else {
        $list = [];
        \Bitrix\Main\Loader::includeModule('iblock');
        $rsIntervals = CIBlockElement::GetList(
            ['PROPERTY_FROM' => 'ASC'],
            ['IBLOCK_CODE' => "rests-interval", "ACTIVE" => "Y"],
            false,
            false,
            ['ID', 'IBLOCK_ID', 'PROPERTY_FROM', 'PROPERTY_TO', 'NAME']);

        while($interval = $rsIntervals->GetNext()) {
            $list[] = [
                'NAME' => $interval['NAME'],
                'FROM' => $interval['PROPERTY_FROM_VALUE'],
                'TO' => $interval['PROPERTY_TO_VALUE']
            ];
        }
        if($ob_cache_prop->StartDataCache()) {
            $ob_cache_prop->EndDataCache(array('list' => $list));
        }
    }
    foreach($list as $interval) {
        if($product['CATALOG_QUANTITY'] >= $interval['FROM']
           && $product['CATALOG_QUANTITY'] <= $interval['TO']
        ) {
            return $interval['NAME'];
        }
    }
    return "Нет статуса";
}

function pr($message) {
 echo "<pre>";
 print_r($message);
 echo "</pre>";
}
/**
 * Функция возвращает URL миниатюры по GUID товара
 * @param array
 *      
 * @return string
 */
function getURLThumbnailPhoto($arParamPhoto) {
    //$url_no_image = 'https://'.$_SERVER['HTTP_HOST'].SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
    $url_no_image = 'https://'.$_SERVER['HTTP_HOST'].SITE_TEMPLATE_PATH . '/img/catalog/no_image.jpg';

    if (!isset($arParamPhoto['WIDTH']) or empty($arParamPhoto['WIDTH']))
        return $url_no_image;
    else
        $widthThumb = $arParamPhoto['WIDTH'];

    if (!isset($arParamPhoto['HEIGHT']) or empty($arParamPhoto['HEIGHT']))
        return $url_no_image;
    else
        $heightThumb = $arParamPhoto['HEIGHT'];

    $dir_no_image = '/upload/resize_cache/no_image_photo';    
    if(!is_dir($_SERVER['DOCUMENT_ROOT'] . $dir_no_image)){
        mkdir($_SERVER['DOCUMENT_ROOT'] . $dir_no_image, 0775, true);
    }

    $file_no_image = 'no_image_' . $widthThumb . '_' . $heightThumb  .'.jpg';
    $sSourceNameNoImage = $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . '/img/catalog/no_image.jpg';;
    $sDestinationNameNoImage = $_SERVER["DOCUMENT_ROOT"] . $dir_no_image . '/' . $file_no_image;

    if (!file_exists($sDestinationName)) {
            CFile::ResizeImageFile($sSourceNameNoImage, $sDestinationNameNoImage, array('width' => $widthThumb, 'height' => $heightThumb), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
    }
    
    $url_no_image = 'https://'.$_SERVER['HTTP_HOST'] . $dir_no_image . '/' . $file_no_image;


    if (!isset($arParamPhoto['GUID']) or empty($arParamPhoto['GUID']))
        return $url_no_image;
    else 
        $guidProduct = $arParamPhoto['GUID'];

    if (!isset($arParamPhoto['TYPE']) or empty($arParamPhoto['TYPE']))
        $typeThumb = 'photos';
    else
        $typeThumb = $arParamPhoto['TYPE'];
    
    if (!isset($arParamPhoto['NUMBER']) or empty($arParamPhoto['NUMBER']))
        $numberThumb = '001';
    else
        $numberThumb = $arParamPhoto['NUMBER'];
    if (!isset($arParamPhoto['DEBUG']) or empty($arParamPhoto['DEBUG']))
        $mode_debug = false;
    else
        $mode_debug = true;


    $name_source = $typeThumb . '_' . $guidProduct . '_' . $numberThumb . '.jpg'; 
    $name_Thumb = $typeThumb . '_' . $guidProduct . '_' . $numberThumb . '_' . $widthThumb . '_' . $heightThumb . '.jpg';

    $dir_thumb = '/upload/resize_cache/thumbnail_photo';
    $dir_thumb = $dir_thumb . '/'. substr($guidProduct, 0, 2) . '/' . $guidProduct;
    if(!is_dir($_SERVER['DOCUMENT_ROOT'] . $dir_thumb)){
        mkdir($_SERVER['DOCUMENT_ROOT'] . $dir_thumb, 0775, true);
    }

    $sSourceName = $_SERVER["DOCUMENT_ROOT"].'/upload/restrict/guids/'.substr($guidProduct, 0, 2).'/'.$guidProduct.'/'.$name_source;
    $sDestinationName = $_SERVER["DOCUMENT_ROOT"] . $dir_thumb . '/' . $name_Thumb;
    $url_thumb = 'https://'.$_SERVER['HTTP_HOST']. $dir_thumb . '/' . $name_Thumb;
    

    if (file_exists($sSourceName)) {
        $bFileSource = true;
        $DateFileSource = filemtime($sSourceName);
        if ($mode_debug) echo "В последний раз файл исходника был изменен: " . date ("F d Y H:i:s.", $DateFileSource);
    }
    else { 
        if ($mode_debug) echo 'Файла исходника НЕ существует!';
        $bFileSource = false;
        return $url_no_image;
    }
    
    if (file_exists($sDestinationName)) {
        $bFileThumb = true;
        $DateFileThumb = filemtime($sDestinationName);
        if ($mode_debug) echo "В последний раз файл миниатюры был изменен: " . date ("F d Y H:i:s.", $DateFileThumb);
        if ($DateFileThumb < $DateFileSource) {
            if ($mode_debug) echo 'Файл исходника позже миниатюры. Нужна перегенерация!';
            CFile::ResizeImageFile($sSourceName, $sDestinationName, array('width' => $widthThumb, 'height' => $heightThumb), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
            if (file_exists($sDestinationName)) {
                if ($mode_debug) echo 'Файла миниатюры перегенерировался!';
                return $url_thumb;
            }
        }
        else {
            if ($mode_debug)  echo 'Файл исходника раньше миниатюры. Перегенерации не требуется';
            return $url_thumb;
        }
    }
    else {
        $bFileThumb = false;
        if ($mode_debug)  echo 'Файла миниатюры НЕ существовало!';
        if ($bFileSource) {
            CFile::ResizeImageFile($sSourceName, $sDestinationName, array('width' => $widthThumb, 'height' => $heightThumb), BX_RESIZE_IMAGE_PROPORTIONAL, array(), false, array());
            if (file_exists($sDestinationName)) {
                if ($mode_debug) echo 'Файла миниатюры появился!';
                return $url_thumb;
            }
        }
    }

    return $url_no_image;
}

/**
 * Функция возвращает размер файла
 * @param string
 *      
 * @return string
 */
function getFileSize($sPathFile) {

    if (file_exists($sDestinationName)) {
    	return '';
    } 
    else {
	$FileSize = filesize($sPathFile);	
    }                        

   return CFile::FormatSize($FileSize);
}