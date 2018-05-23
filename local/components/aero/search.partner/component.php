<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

CModule::IncludeModule('iblock');

$arItemIds = array();
$dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_SITE_SETTINGS, 'PROPERTY_CODE' => 'SEARCH_ITEMS_COUNT', 'ACTIVE' => 'Y'), false, false, array('ID', 'IBLOCK_ID', 'PROPERTY_VALUE'));
$iItemsCount = false;
while($elem = $dbElems->Fetch()) {
    $iItemsCount = (int)$elem['PROPERTY_VALUE_VALUE'];
}
$sSearchUrl =  'http://www.texenergo.com/products/search.json?q=' . urlencode($_REQUEST['q']) . '&allow_mfk=1' . ($iItemsCount ? '&$top='.$iItemsCount : '');
$sSearchJSON = file_get_contents($sSearchUrl);
$arJSON = json_decode($sSearchJSON);
if(isset($_REQUEST["tags"])) {
    $tags = trim($_REQUEST["tags"]);
    $arResult["REQUEST"]["~TAGS_ARRAY"] = array();
    $arTags = explode(",", $tags);
    $tagsArray = array();
    foreach($arTags as $tag)
    {
        $tag = trim($tag);
        if(strlen($tag) > 0)
            $tagsArray[$tag] = $tag;
    }
    $tagsArray = htmlspecialcharsex($tagsArray);
}else {
    $tags = false;
}
foreach($arJSON as $oItem){
    $sReference = $oItem->mfk;
    $arReferences[] = 'te' . substr($sReference, 3);
}
if(!empty($arReferences)) {
    $dbElems = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG, 'PROPERTY_REFERENCE' => $arReferences, 'ACTIVE' => 'Y'));
    while ($arItem = $dbElems->GetNext()) {
        if($tags){
            $bFound = false;
            $elementTag = $arItem['TAGS'];
            foreach($tagsArray as $tag){
                if($tag == $elementTag){
                    $bFound = true;
                    break;
                }
            }
            if(!$bFound) continue;
        }
        $iCnt++;
        $arElement = CIBlockElement::GetByID($arItem['ID'])->Fetch();
        $sReference = CIBlockElement::GetProperty(IBLOCK_ID_CATALOG, $arItem['ID'], array("sort" => "asc"), Array("CODE"=>"REFERENCE"))->Fetch();
        $arItem['REFERENCE'] = $sReference['VALUE'];
        $picID = $arElement['DETAIL_PICTURE'] ?: $arElement['PREVIEW_PICTURE'];

        // имеются ли аналоги
        $arItem['HAVE_ANALOGS'] = false;
        $arAnalogs = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], 'sort', 'asc', Array('CODE' => 'ANALOGS'))->Fetch();
        if ($arAnalogs && !empty($arAnalogs['VALUE'])) $arItem['HAVE_ANALOGS'] = true;

        // признак для метки "Акция"
        $arItem['IS_SPECIAL'] = false;
        $arIsSpecial = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], 'sort', 'asc', Array('CODE' => 'IS_SPECIAL'))->Fetch();
        if ($arIsSpecial['VALUE_ENUM'] == 'Y') {
            // если является спецпредложением, проверяем диапазон дат
            $arIsSpecialFrom = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], 'sort', 'asc', Array('CODE' => 'IS_SPECIAL_FROM'))->Fetch();
            $arIsSpecialTo = CIBlockElement::GetProperty($arElement['IBLOCK_ID'], $arElement['ID'], 'sort', 'asc', Array('CODE' => 'IS_SPECIAL_TO'))->Fetch();
            if (strlen($arIsSpecialFrom['VALUE']) > 0 && strlen($arIsSpecialTo['VALUE']) > 0) {
                $arFrom = ParseDateTime($arIsSpecialFrom['VALUE']);
                $iFrom = mktime($arFrom['HH'], $arFrom['MI'], $arFrom['SS'], $arFrom['MM'], $arFrom['DD'], $arFrom['YYYY']);
                $arTo = ParseDateTime($arIsSpecialTo['VALUE']);
                $iTo = mktime($arTo['HH'], $arTo['MI'], $arTo['SS'], $arTo['MM'], $arTo['DD'], $arTo['YYYY']);
                $iNow = time();
                if ($iNow > $iFrom && $iNow < $iTo) {
                    $arItem['IS_SPECIAL'] = true;
                }

            }
        }

        // изображение
        if ($picID && $arPic = CFile::ResizeImageGet($picID, array('width' => 43, 'height' => 30), BX_RESIZE_IMAGE_PROPORTIONAL, true)) {
            $arItem['PICTURE'] = $arPic['src'];
        } else {
            $arItem['PICTURE'] = SITE_TEMPLATE_PATH . '/img/catalog/no-image.png';
        }

        $arResult['ITEMS'][] = $arItem;
    }

    $arSortedItems = array();
    foreach($arReferences as $iId){
        foreach($arResult['ITEMS'] as $key=>$arItem){
            if($arItem['REFERENCE'] == $iId){
                $arSortedItems[] = $arItem;
                unset($arResult['ITEMS'][$key]);
                break;
            }
        }
    }
    $arResult['ITEMS'] =  $arSortedItems;

    $total = !empty($arParams['COUNT']) ? $arParams['COUNT'] : false;
    if ($total){
        $arResult['MORE'] = count($arResult['ITEMS']) > 3 ? count($arResult['ITEMS']) - 3 : false;
        $arResult['ITEMS'] = array_slice($arResult['ITEMS'], 0, $total);
    }

    if(!empty($arParams['PAGE_COUNT'])){
        $countOnPage = $arParams['PAGE_COUNT'];
        $page = intval(!empty($_GET['PAGEN_2']) ? $_GET['PAGEN_2'] : 1);
        $elementsPage = array_slice($arResult['ITEMS'], ($page-1) * $countOnPage, $countOnPage);
        $navResult = new CDBResult();
        $navResult->NavPageCount = ceil(count($arResult['ITEMS']) / $countOnPage);
        $navResult->NavPageNomer = $page;
        $navResult->NavNum = 2;
        $navResult->NavPageSize = $countOnPage;
        $navResult->NavRecordCount = count($arResult['ITEMS']);
        $arResult['pager'] = $navResult;
        $arResult['NAV_ITEMS'] = $elementsPage;
    }
}else{
    $arResult['ITEMS'] = false;
}


$this->IncludeComponentTemplate();