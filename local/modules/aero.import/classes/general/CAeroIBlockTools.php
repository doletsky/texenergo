<?php

class CAeroIBlockTools
{

    const PROPERTY_TYPE_LIST = 'L';
    const PROPERTY_TYPE_FILE = 'F';
    const PROPERTY_TYPE_IBLOCK_ELEMENTS = 'E';
    const PROPERTY_TYPE_IBLOCK_SECTIONS = 'G';
    const PROPERTY_TYPE_STRING = 'S';
    const PROPERTY_TYPE_NUMBER = 'N';
    const PROPERTY_TYPE_DATETIME = 'S:DateTime';

    private $iblockID;

    protected $sectionsCache = [];
    protected $elementsCache = [];
    public $propertiesCache = [];

    public $LAST_ERROR = '';

    private $arUnits = Array(
        'шт.' => 5,
        'шт' => 5,
        'уп.' => 6,
        'уп' => 6,
        'компл.' => 7,
        'м' => 1,
        'рулон' => 8,
        'кг' => 4,
        'коробка' => 9,
        'п.' => 10,
        'пара' => 10,
        'пар' => 10,
        'лист' => 11
    );

    public function __construct($iblockID)
    {
        $this->iblockID = IntVal($iblockID);

        CModule::IncludeModule('iblock');
        CModule::IncludeModule('catalog');
        CModule::IncludeModule('sale');
    }

    public function updateProperty($arFields, $fullImport = false)
    {
        if (empty($arFields['IBLOCK_ID'])) {
            $arFields['IBLOCK_ID'] = $this->iblockID;
        }

        $arFields['ACTIVE'] = 'Y';
        $arValues = $arFields['VALUES'];
        unset($arFields['VALUES']);

        if (empty($arFields['PROPERTY_TYPE'])) {
            if (!empty($arValues)) {
                $arFields['PROPERTY_TYPE'] = self::PROPERTY_TYPE_LIST;
            } else {
                $arFields['PROPERTY_TYPE'] = self::PROPERTY_TYPE_STRING;
            }
        }

        $arPropType = explode(':', $arFields['PROPERTY_TYPE']);
        if (count($arPropType) == 2) {
            $arFields['PROPERTY_TYPE'] = $arPropType[0];
            $arFields['USER_TYPE'] = $arPropType[1];
        }


        $objProperty = new CIBlockProperty();
        $objIblockElement = new CIBlockElement();
        $objPropEnum = new CIBlockPropertyEnum();

        if(!isset($this->propertiesCache[$arFields['IBLOCK_ID']])){
            $rsProperties = CIBlockProperty::GetList(Array(),
                                                     Array('IBLOCK_ID' => $arFields['IBLOCK_ID']));
            while($arProperty = $rsProperties->Fetch()){
                $this->propertiesCache[$arFields['IBLOCK_ID']][$arProperty['XML_ID']] = $arProperty;
            }
        }

        $arExisting = $this->propertiesCache[$arFields['IBLOCK_ID']][$arFields['XML_ID']];
        if ($arExisting) {
            $arFields['ID'] = $arExisting['ID'];
            $arFields['PROPERTY_TYPE'] = $arExisting['PROPERTY_TYPE'];

            $objProperty->Update($arExisting['ID'], $arFields);
            if (!empty($arValues)) {
                if ($arExisting['PROPERTY_TYPE'] == 'L') {

                    $existingPropValues = [];
                    $rsValuesList = CIBlockPropertyEnum::GetList(Array(),
                                                                 Array(
                                                                     'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                                                                     'PROPERTY_ID' => $arFields['ID']));
                    while($arValue = $rsValuesList->GetNext()){
                        $existingPropValues[$arValue['XML_ID']] = $arValue;
                    }

                    if($fullImport){
                        $valuesFromXml = array();
                        // собираем XML_ID свойства в массив для проверки их в уже существующих
                        foreach ($arValues as $arValue) {
                            $valuesFromXml[] = $arValue['XML_ID'];
                        }

                        $valuesToDeleteFromIB = array();
                        foreach ($existingPropValues as $xmlId => $arValue) {
                            if(!in_array($xmlId, $valuesFromXml)){
                                $valuesToDeleteFromIB[] = $xmlId;
                            }
                        }
                        foreach($valuesToDeleteFromIB as $xmlId) {
                            $objPropEnum->Delete($existingPropValues[$xmlId]['ID']);
                            unset($existingPropValues[$xmlId]);
                        }
                    }

                    foreach ($arValues as $arValue) {
                        if (!isset($existingPropValues[$arValue['XML_ID']])) {
                            $arValue['ID'] = $objPropEnum->Add(Array(
                                                                   'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                                                                   'XML_ID' => $arValue['XML_ID'],
                                                                   'PROPERTY_ID' => $arFields['ID'],
                                                                   'VALUE' => $arValue['VALUE'],
                                                                   'SORT' => $arValue['SORT'],
                                                               ));
                        } else {
                            $arValue['ID'] = $existingPropValues[$arValue['XML_ID']]['ID'];
                            // если обновился текст значения свойства
                            // if($arValue['VALUE'] !== trim($existingPropValues[$arValue['XML_ID']]['VALUE'])){
                                $objPropEnum->Update($arValue['ID'], array(
                                    'VALUE' => trim($arValue['VALUE']),
                                    'SORT' => $arValue['SORT']
                                ));
                            // }
                        }
                    }

                } elseif ($arExisting['LINK_IBLOCK_ID'] > 0) {
                    $arFields['LINK_IBLOCK_ID'] = $arExisting['LINK_IBLOCK_ID'];
                    CIBlockPropertyEnum::DeleteByPropertyID($arExisting['ID'], true);

                    $arDeactive = array();
                    $rsElement = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $arExisting['LINK_IBLOCK_ID']), false, false, Array('ID', 'XML_ID'));
                    while($arElement = $rsElement->GetNext()) {
                        $arValueExists[$arElement["XML_ID"]] =  $arElement['ID'];
                        $arDeactive[$arElement["XML_ID"]] = $arElement['ID'];
                    }

                    foreach ($arValues as $arValue) {
                        if (!isset($arValueExists[$arValue['XML_ID']])) {
                            $arValue['ID'] = $objIblockElement->Add(Array(
                                                                        'IBLOCK_ID' => $arExisting['LINK_IBLOCK_ID'],
                                                                        'XML_ID' => $arValue['XML_ID'],
                                                                        'ACTIVE' => 'Y',
                                                                        'NAME' => $arValue['VALUE'],
                                                                        //'CODE' => $this->translit(substr($arValue['VALUE'], 0, 50), '-'),
                                                                        'CODE' => $arValue['XML_ID'],
                                                                    ));
                        } else {
                            $arValue['ID'] = $arValueExists[$arValue['XML_ID']];
                            unset($arDeactive[$arValue['XML_ID']]);
                        }
                    }

//                    if($fullImport){
//                        foreach ($arDeactive as $Element) {
//                            $objIblockElement->Delete($Element);
//                        }
//                    }
//                    else {
                        foreach ($arDeactive as $Element) {
                            $objIblockElement->Update($Element, array(
                                'ACTIVE' => 'N',
                            ));
                        }
//                    }
                }
                $arFields['VALUES'] = $arValues;
            }

        } else {
            if (!empty($arValues)) {
                $arFields['VALUES'] = $arValues;
            }
            $arFields['ID'] = $objProperty->Add($arFields);
        }

        if ($arFields['ID'] > 0) {

            // разрешаем использование св-ва в умном фильтре
            CIBlockSectionPropertyLink::Delete(0, $arFields['ID']);
            /*$arFields['SMART_FILTER'] = ($arFields['SMART_FILTER'] == 'Y' ? 'Y' : 'N');
            CIBlockSectionPropertyLink::Add(0, $arFields['ID'], array(
                "SMART_FILTER" => $arFields['SMART_FILTER'],
                "IBLOCK_ID" => $arFields['IBLOCK_ID'],
                "DISPLAY_TYPE" => $arFields['DISPLAY_TYPE'] ? $arFields['DISPLAY_TYPE'] : 'F'
            ));*/
        }

    }

    public function updateSection($arFields)
    {
        $arFields['IBLOCK_ID'] = $this->iblockID;

        if (!array_key_exists('ACTIVE', $arFields)) {
            $arFields['ACTIVE'] = 'Y';
        }

        $objSection = new CIBlockSection();

        if(!isset($this->sectionsCache[$arFields['IBLOCK_ID']])){
            $rsSectionsList = CIBlockSection::GetList(
                Array('sort' => 'asc'),
                Array('IBLOCK_ID' => $arFields['IBLOCK_ID']),
                false, Array('XML_ID', 'ID', 'IBLOCK_ID')
            );
            while($section = $rsSectionsList->Fetch()){
                $this->sectionsCache[$arFields['IBLOCK_ID']][$section['XML_ID']] = $section;
            }
        }

        $arExisting = $this->sectionsCache[$arFields['IBLOCK_ID']][$arFields['XML_ID']];

        if ($arExisting) {
            $arFields['ID'] = $arExisting['ID'];
            $objSection->Update($arFields['ID'], $arFields);
        } else {
            $arFields['ID'] = $objSection->Add($arFields);
            $this->sectionsCache[$arFields['IBLOCK_ID']][$arFields['XML_ID']] = $arFields;
        }

        return $arFields['ID'];
    }

    public function updateElement($arFields, $bResetProps = false)
    {
        $this->LAST_ERROR = '';

        if (strlen($arFields['IBLOCK_ID']) <= 0) {
            $arFields['IBLOCK_ID'] = $this->iblockID;
        }
        if (strlen($arFields['ACTIVE']) <= 0) {
            $arFields['ACTIVE'] = 'Y';
        }

        $objElement = new CIBlockElement();

        if(!isset($this->elementsCache[$arFields['IBLOCK_ID']])){
            $rsElements = CIBlockElement::GetList(
                Array(),
                Array(
                    'IBLOCK_ID' => $arFields['IBLOCK_ID'],
                ),
                false,
                false, Array('ID', 'IBLOCK_ID', 'XML_ID', 'DETAIL_PICTURE', 'PREVIEW_PICTURE'));

            while($arElement = $rsElements->Fetch()){
                $this->elementsCache[$arFields['IBLOCK_ID']]['XML_ID'][$arElement['XML_ID']] = $arElement;
                $this->elementsCache[$arFields['IBLOCK_ID']]['ID'][$arElement['ID']] = $arElement;
            }
        }

        $arExisting = false;

        if (strlen($arFields['ID']) > 0) {
            $arExisting = $this->elementsCache[$arFields['IBLOCK_ID']]['ID'][$arFields['ID']];
        } elseif (strlen($arFields['XML_ID']) > 0) {
            $arExisting = $this->elementsCache[$arFields['IBLOCK_ID']]['XML_ID'][$arFields['XML_ID']];
        }

        $objElement->LAST_ERROR = '';
        if ($arExisting) {
            $arFields['ID'] = $arExisting['ID'];
            if (!$bResetProps) {
                $arPropsValues = $arFields['PROPERTY_VALUES'];
                unset($arFields['PROPERTY_VALUES']);
            }
            if (!empty($arFields['DETAIL_PICTURE']) && $arExisting['DETAIL_PICTURE'] > 0) {
                CFile::Delete($arExisting['DETAIL_PICTURE']);
            }

            if (!empty($arFields['PREVIEW_PICTURE']) && $arExisting['PREVIEW_PICTURE'] > 0) {
                CFile::Delete($arExisting['PREVIEW_PICTURE']);
            }

            $objElement->Update($arFields['ID'], $arFields);
            if (!$bResetProps) {
                CIBlockElement::SetPropertyValuesEx($arFields['ID'], $arFields['IBLOCK_ID'], $arPropsValues);
            }
        } else {
            $arFields['ID'] = $objElement->Add($arFields);
            $this->elementsCache[$arFields['IBLOCK_ID']]['XML_ID'][$arFields['XML_ID']] = $arFields;
        }
        if (strlen($objElement->LAST_ERROR) > 0) {
            $this->LAST_ERROR = $objElement->LAST_ERROR;
            return false;
        }
        return $arFields['ID'];
    }

    public function updateCatalogProduct($arFields)
    {
        if ($arFields['MEASURE']) {
            $arFields['MEASURE'] = $this->arUnits[$arFields['MEASURE']];
        }

        CCatalogProduct::Add($arFields);

        CPrice::SetBasePrice($arFields['ID'], $arFields['PRICE'], 'RUB');

        // обновление дополнительных цен из массива вида XML_ID=>цена
        if (is_array($arFields['PRICES'])) {

            foreach ($arFields['PRICES'] as $sPriceCode => $fPrice) {

                $arPrice = CCatalogGroup::GetList(Array(), Array('XML_ID' => $sPriceCode), false, Array('nTopCount' => 1), Array('ID'))->Fetch();

                if ($arPrice) {

                    $arPriceFields = Array(
                        "PRODUCT_ID" => $arFields['ID'],
                        "CATALOG_GROUP_ID" => $arPrice['ID'],
                        "PRICE" => $fPrice,
                        "CURRENCY" => "RUB",
                        "QUANTITY_FROM" => false,
                        "QUANTITY_TO" => false,
                    );

                    $arExisting = CPrice::GetList(
                        array(),
                        array(
                            "PRODUCT_ID" => $arPriceFields['PRODUCT_ID'],
                            "CATALOG_GROUP_ID" => $arPriceFields['CATALOG_GROUP_ID']
                        )
                    )->Fetch();

                    if ($arExisting) {
                        CPrice::Update($arExisting["ID"], $arPriceFields);
                    } else {
                        CPrice::Add($arPriceFields);
                    }

                }
            }
        }

    }

    public function updateProperySection($iSectionId, $arProperty){
        $arPropertyCache = $this->propertiesCache[$this->iblockID][$arProperty['XML_ID']];
        if($arPropertyCache) {
            CIBlockSectionPropertyLink::Delete($iSectionId, $arPropertyCache['ID']);
            CIBlockSectionPropertyLink::Add($iSectionId, $arPropertyCache['ID'], array(
                "SMART_FILTER" => $arProperty['SMART_FILTER'],
                "IBLOCK_ID" => $this->iblockID,
                "DISPLAY_TYPE" => $arProperty['DISPLAY_TYPE'] ? $arProperty['DISPLAY_TYPE'] : 'F'
            ));
        }
    }

    /**
     * Выбор сущности из HL-блока
     *
     * @param $XML_ID
     * @param $hlBlockID
     * @return array
     */
    public function getEntityByXmlID($XML_ID, $hlBlockID)
    {
        CModule::IncludeModule('highloadblock');
        $hldata = Bitrix\Highloadblock\HighloadBlockTable::getById($hlBlockID)->fetch();
        $hlentity = Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);

        $query = new Bitrix\Main\Entity\Query($hlentity);
        $query->setSelect(array('*'));
        $query->setFilter(array('UF_XML_ID' => $XML_ID));
        $rsItem = new CDBResult($query->exec());
        return $rsItem->Fetch();
    }

    /**
     * Выбор элемента инфоблока по XML_ID
     *
     * @param $XML_ID
     * @param $iBlockID
     * @return array
     */
    public function getElementByXmlID($XML_ID, $iBlockID)
    {
        $rsItem = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $iBlockID, 'XML_ID' => $XML_ID), false, false, Array('ID', 'NAME', 'CODE', 'XML_ID', 'DETAIL_PAGE_URL'));
        return $rsItem->GetNext();
    }

    /**
     * Выбор раздела инфоблока по XML_ID
     *
     * @param $XML_ID
     * @param $iBlockID
     * @return array
     */
    public function getSectionByXmlID($XML_ID, $iBlockID)
    {
        $rsItem = CIBlockSection::GetList(Array(), Array('IBLOCK_ID' => $iBlockID, 'XML_ID' => $XML_ID), false, Array('ID', 'NAME', 'CODE', 'XML_ID'));
        return $rsItem->Fetch();
    }

    /**
     * Выбор элемента инфоблока по символьному коду
     *
     * @param $CODE
     * @param $iBlockID
     * @return array
     */
    public function getElementByCode($CODE, $iBlockID)
    {
        $rsItem = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $iBlockID, 'CODE' => $CODE), false, false, Array('ID', 'NAME', 'CODE', 'XML_ID', 'DETAIL_PAGE_URL'));
        return $rsItem->GetNext();
    }


    /**
     * Возвращает ID значения свойства типа список по его VALUE
     * @param $sValue
     * @param $sPropertyCode
     * @param $iblockID
     * @return null
     */
    public function enumValueIDByValue($sValue, $sPropertyCode, $iblockID)
    {
        if ($arValue = CIBlockPropertyEnum::GetList(Array(), Array('IBLOCK_ID' => $iblockID, 'CODE' => $sPropertyCode, 'VALUE' => $sValue))->Fetch()) {
            return $arValue['ID'];
        }
        return null;
    }

    private function translit($string, $specialReplacement = '')
    {
        $replace = array(
            "а" => "a", "А" => "a",
            "б" => "b", "Б" => "b",
            "в" => "v", "В" => "v",
            "г" => "g", "Г" => "g",
            "д" => "d", "Д" => "d",
            "е" => "e", "Е" => "e",
            "ж" => "zh", "Ж" => "zh",
            "з" => "z", "З" => "z",
            "и" => "i", "И" => "i",
            "й" => "y", "Й" => "y",
            "к" => "k", "К" => "k",
            "л" => "l", "Л" => "l",
            "м" => "m", "М" => "m",
            "н" => "n", "Н" => "n",
            "о" => "o", "О" => "o",
            "п" => "p", "П" => "p",
            "р" => "r", "Р" => "r",
            "с" => "s", "С" => "s",
            "т" => "t", "Т" => "t",
            "у" => "u", "У" => "u",
            "ф" => "f", "Ф" => "f",
            "х" => "h", "Х" => "h",
            "ц" => "c", "Ц" => "c",
            "ч" => "ch", "Ч" => "ch",
            "ш" => "sh", "Ш" => "sh",
            "щ" => "sch", "Щ" => "sch",
            "ъ" => "", "Ъ" => "",
            "ы" => "y", "Ы" => "y",
            "ь" => "", "Ь" => "",
            "э" => "e", "Э" => "e",
            "ю" => "yu", "Ю" => "yu",
            "я" => "ya", "Я" => "ya",
            "і" => "i", "І" => "i",
            "ї" => "yi", "Ї" => "yi",
            "є" => "e", "Є" => "e"
        );
        $str = iconv("UTF-8", "UTF-8//IGNORE", strtr($string, $replace));
        $str = preg_replace('/[^a-z0-9]/i', $specialReplacement, $str);
        if (strlen($specialReplacement) > 0) {
            $str = preg_replace('/\\' . $specialReplacement . '{2,}/i', $specialReplacement, $str);
        }
        return strtolower(trim($str, $specialReplacement));
    }

    //Возвращает изображения товаров по референсу
    public function getProductPhotosByReference($sReference){
        $rsItem = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $this->iblockID, 'PROPERTY_REFERENCE' => $sReference), false, false, Array('ID', 'PROPERTY_REFERENCE', 'PROPERTY_PHOTOS_PRODUCT'));
        $arElem = $rsItem->GetNext();
        $iPhotosIbId = CIBlockTools::GetIBlockId('files_product');
        if(!empty($arElem['PROPERTY_PHOTOS_PRODUCT_VALUE'])){
            $arResult = false;
            foreach($arElem['PROPERTY_PHOTOS_PRODUCT_VALUE'] as $sXmlId){
                $arPhoto = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $iPhotosIbId, 'XML_ID' => $sXmlId), false, false, Array('ID', 'PROPERTY_PATH'))->GetNext();
                $arResult[] = array('XML_ID' => $sXmlId, 'PATH' => '/restrict' . $arPhoto['PROPERTY_PATH_VALUE']);
            }
            return $arResult;
        }else{
            return false;
        }
    }
}
