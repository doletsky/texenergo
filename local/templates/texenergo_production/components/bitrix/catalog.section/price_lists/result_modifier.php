<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)
    die();

foreach($arResult['ITEMS'] as $key=>$arItem) {

    if ($arItem['PROPERTIES']['PRICE_LIST']['VALUE'] != "") {

        $arFile = CFile::GetFileArray($arItem['PROPERTIES']['PRICE_LIST']['VALUE']);
        $nameParts = explode('.', $arFile['ORIGINAL_NAME']);
        $ext = strtolower(end($nameParts));

        $arResult['ITEMS'][$key]['LINK'] = $arFile['SRC'];

    }

    if ($arItem['PROPERTIES']['LINK']['VALUE'] != "") {

        $nameParts = explode('.', $arItem['PROPERTIES']['LINK']['VALUE']);
        $ext = strtolower(end($nameParts));
        $arResult['ITEMS'][$key]['LINK'] = $arItem['PROPERTIES']['LINK']['VALUE'];

    }

    if (isset($ext)) {

        $img = SITE_TEMPLATE_PATH.'/img/price_list/'.$ext.'.png';

        if(file_exists($_SERVER['DOCUMENT_ROOT'].$img)) {

            $arResult['ITEMS'][$key]['ICON'] = $img;

        } else {

            $arResult['ITEMS'][$key]['ICON'] = SITE_TEMPLATE_PATH.'/img/price_list/other.png';

        }

    }

    unset($ext);

}
?>