<?php

/**
 * Class CAeroImportCatalog
 *
 * Импорт каталога производится при помощи вызова метода Import(filePath)
 *
 * Пример:
 *   $sDataFile = $_SERVER['DOCUMENT_ROOT'] . '/upload/import/catTE_full.xml';
 *   if (file_exists($sDataFile)) {
 *       $oImport = new CAeroImportCatalog($sDataFile);
 *       $oImport->import();
 *   }
 *
 *
 *
 */
Class CAeroImportCatalog {

    private $IMPORT_FILTER = 'N';
    private $IMPORT_SPECIAL = 'N';
    private $IMPORT_SECTIONS = 'N';
    private $IMPORT_ELEMENTS = 'N';
    private $IMPORT_CATALOG = 'N';
    private $UPDATE_FACET_INDEX = 'Y';


    private $IBLOCK_PRODUCTS;

    /**
     * Инфоблок с акциями /catalog/special
     */
    private $IBLOCK_ACTIONS;

    /**
     * Инфоблок с сериями (/catalog/filter/category[@is_series=1])
     */
    private $IBLOCK_SERIES;

    /**
     * Инфоблок с группами файлов (catalog/files_product[photos_product | description_product]/filesgroup)
     */
    private $IBLOCK_ID_PRODUCT_FILES_GROUP;

    /**
     * Инфоблок с файлами (catalog/files_product[photos_product | description_product]/filesgroup/value)
     */
    private $IBLOCK_ID_PRODUCT_FILES;

    /**
     * @var unix-timestamp время начала импорта
     */
    private $startTimestamp;

    /**
     * @var XMLReader объект парсера. Используется для потокового разбора файла с данными
     */
    private $reader;

    /**
     * @var CAeroIBlockTools объект для доступа к инфоблоку каталога
     */
    private $catalog;

    /**
     * @var bool полная выгрузка или только обновление
     */
    private $bFullImport = true;

    /**
     * @var array используется для кеширования свойств во время разбора ноды filter
     * (XML_ID, CODE, NAME, VALUES)
     */
    private $arProps = Array();

    /**
     * @var array используется для кеширования свойств и их значений (см propertyValueByXmlID)
     */
    private $arPropsIndex = Array();

    /**
     * @var string путь к разбираемому файлу
     */
    private $filePath;

    /**
     * @var array сюда записываем связку товар - аналоги. Запись в БД производится по завершении разбора файла, когда все товары уже в базе
     */
    private $analogs = Array();

    /**
     * @var array сюда записываем связку товар - аксессуары. Запись в БД производится по завершении разбора файла, когда все товары уже в базе
     */
    private $accessories = Array();

    /**
     * @var array сюда записываем связку товар - сопутствующие товары. Запись в БД производится по завершении разбора файла, когда все товары уже в базе
     */
    private $related = Array();

    /**
     * @var array сюда записываем спецпредложения. Запись в БД производится по завершении разбора файла, когда все товары уже в базе
     */
    private $specials = null;

    /**
     * @var array сюда записываем xml_id новинок. Запись в БД производится по завершении разбора файла, когда все товары уже в базе
     */
    private $new = null;

    /**
     * @var array сюда записываем xml_id лидеров продаж. Запись в БД производится по завершении разбора файла, когда все товары уже в базе
     */
    private $bestsellers = null;

    /**
     * @var array сюда записываем св-ва, которые являются сериями. Запись в БД производится по завершении разбора файла, когда все товары уже в базе
     */
    private $series = Array();

    /**
     * @var array символьные коды свойств, которые будут выводиться в карточке товара в каталоге
     */
    private $arCataogDetailPropsCode = Array();

    /**
     * @var array кэш для элементов инфоблоков, которые используются в свойствах "привязка к элементу"
     */
    protected $linkedIblockItemsCache = [];

    /**
     * @var array кэш для тегов элементов
     */
    protected $tagsCache = [];

    private $logPath;

    /**
     * @var array Список свойств, которые не очищаем при обновлении товара
     */
    protected $keepPropertiesList = ['ANALOGS',
                                     'PDF',
                                     'SERIES',
                                     'ACCESSORIES',
                                     'RELATED',
                                     'SKU',
                                     'STOCK_INDICATOR',
                                     'OLD_PRICE',
                                     'PRICE_DOWN_REASON',
                                     'IS_NEW',
                                     'IS_BESTSELLER',
                                     'SHOW_OLD_PRICE',
                                     'IS_PRICE_DOWN',
                                     'IS_SPECIAL',
                                     'BLOG_POST_ID',
                                     'REFERENCE',
                                     'REFERENCE_SEARCH',
                                     'IS_SPECIAL_FROM',
                                     'IS_SPECIAL_TO',
                                     'BARCODE',
                                     'IS_OWN',
                                     'PHOTOS',
                                     'COMMENTS_CNT',
                                     'FORUM_MESSAGE_CNT',
                                     'vote_count',
                                     'RAITING',
                                     'FILES_PRODUCT',
                                     'DESCRIPTION_PRODUCT',
                                      'PHOTOS_PRODUCT',
                                     'vote_sum',
                                     'FORUM_TOPIC_ID',];

    //id свойств
    protected $propertyIds = [];

    public function __construct($filePath) {
        $this->filePath = $filePath;

        $moduleId = 'aero.import';

        $this->IBLOCK_PRODUCTS = COption::GetOptionInt($moduleId, 'iblock_catalog', 0);
        $this->IBLOCK_ACTIONS = COption::GetOptionInt($moduleId, 'iblock_actions', 0);
        $this->IBLOCK_SERIES = COption::GetOptionInt($moduleId, 'iblock_series', 0);
        //TODO: передалать на настройку модуля
        $this->IBLOCK_ID_PRODUCT_FILES_GROUP = CIBlockTools::GetIBlockId('filesgroup');
        $this->IBLOCK_ID_PRODUCT_FILES = CIBlockTools::GetIBlockId('files_product');

        $this->IMPORT_FILTER = COption::GetOptionString($moduleId, 'import_iblock_properties', 'N');
        $this->IMPORT_SPECIAL = COption::GetOptionString($moduleId, 'import_special', 'N');
        $this->IMPORT_SECTIONS = COption::GetOptionString($moduleId, 'import_iblock_sections', 'N');
        $this->IMPORT_ELEMENTS = COption::GetOptionString($moduleId, 'import_iblock_elements', 'N');
        $this->IMPORT_CATALOG = COption::GetOptionString($moduleId, 'import_catalog', 'N');

        $this->logPath = dirname($filePath).'/import.log';
        COption::SetOptionString($moduleId, 'log_path', $this->logPath);

        $this->catalog = new CAeroIBlockTools($this->IBLOCK_PRODUCTS);
    }

    public function import() {

        $time_total_start = microtime(true);

        $this->log(date('d.m.Y H:i:s')." импорт начат", true);

        $this->startTimestamp = time();

        $time_start = microtime(true);
        $this->log("Создание необходимых типов цен в каталоге");
        $this->setupPriceTypes();
        $time = microtime(true) - $time_start;
        $this->log("Создание необходимых типов цен в каталоге - завершено: {$time}s");

        $time_start = microtime(true);
        $this->log("Создание необходимых свойств инфоблока каталога");
        $this->setupCatalogProperties($this->IBLOCK_PRODUCTS);
        $time = microtime(true) - $time_start;
        $this->log("Создание необходимых свойств инфоблока каталога - завершено: {$time}s");

        $time_start = microtime(true);
        $this->log("Создание необходимых пользовательские полей");
        $this->setupUserFields($this->IBLOCK_PRODUCTS);
        $time = microtime(true) - $time_start;
        $this->log("Создание необходимых пользовательские полей - завершено: {$time}s");

        $this->reader = new XMLReader();
        $this->reader->open($this->filePath, 'UTF-8');

        $sort = 0;
        while($this->reader->read()) {
            //$this->log(__FUNCTION__ . '/ ' . $this->reader->depth . ': ' . $this->reader->name);

            if($this->reader->name == 'catalog') {
                $this->bFullImport = !(IntVal($this->reader->getAttribute('catalog_change')) <= 0);

                if($this->reader->nodeType != XMLReader::END_ELEMENT) {
                    if($this->bFullImport) {
                        $this->log("Полный импорт. Все товары, не вошедшие в файл, будут отключены.");
                    }
                    else {
                        $this->log("Частичный импорт. Будут обновлены только товары, присутствующие в файле.");
                    }
                }
            }

            if($this->reader->name == 'files_product'){
                $time_start = microtime(true);
                $this->log("Прикрепленные файлы (/catalog/files_product)");
                $this->parseFilesProduct();
                $time = microtime(true) - $time_start;
                $this->log("Прикрепленные файлы завершено: {$time}s");
            }

            if($this->reader->name == 'photos_product'){
                $time_start = microtime(true);
                $this->log("Прикрепленные фотографии (/catalog/photos_product)");
                $this->parsePhotosProduct();
                $time = microtime(true) - $time_start;
                $this->log("Прикрепленные фотографии завершено: {$time}s");
            }

            if($this->reader->name == 'description_product'){
                $time_start = microtime(true);
                $this->log("Прикрепленное описание (/catalog/description_product)");
                $this->parseDescriptionProduct();
                $time = microtime(true) - $time_start;
                $this->log("Прикрепленное описание завершено: {$time}s");
            }

            if($this->IMPORT_FILTER == 'Y' && $this->reader->name == 'filter') {
                $time_start = microtime(true);
                $this->log("Значения свойств (/catalog/filter)");
                $this->parseFilter();
                $time = microtime(true) - $time_start;
                $this->log("Значения свойств завершено: {$time}s");
            }

            if($this->IMPORT_SPECIAL == 'Y' && $this->reader->name == 'special') {
                $time_start = microtime(true);
                $this->log("Спецпредложения (/catalog/special)");
                $this->parseSpecial();
                $time = microtime(true) - $time_start;
                $this->log("Спецпредложения завершено: {$time}s");
            }

            if($this->IMPORT_SPECIAL == 'Y' && $this->reader->name == 'section_isnew') {
                $time_start = microtime(true);
                $this->log("Новинки (/catalog/section_isnew)");
                $this->parseNew();
                $time = microtime(true) - $time_start;
                $this->log("Новинки завершено: {$time}s");
            }

            if($this->IMPORT_SPECIAL == 'Y' && $this->reader->name == 'section_bestseller') {
                $time_start = microtime(true);
                $this->log("Лидеры продаж (/catalog/section_bestseller)");
                $this->parseBestsellers();
                $time = microtime(true) - $time_start;
                $this->log("Лидеры продаж завершено: {$time}s");
            }

            if($this->reader->name == 'section') {
                $sort += 10;
                $time_start = microtime(true);
                $this->log("Импорт каталога (/catalog/section)");
                $this->parseSection(0, $sort);
            }
        }
        $this->reader->close();
        //удаление кэша картинок
        $imageCacheFiles = glob($_SERVER['DOCUMENT_ROOT'] . '/upload/resize_cache/import/*');
        foreach($imageCacheFiles as $file){
            if(is_file($file))
                unlink($file);
        }
        $time = microtime(true) - $time_start;
        $this->log("Каталог завершено: {$time}s");

        // дополниельные действия после разбора файла

        /*if ($this->IMPORT_FILTER == 'Y') {
            $time_start = microtime(true);
            $this->log("Сортировка свойств");
            $this->sortCatalogProperties($this->IBLOCK_PRODUCTS); // сортировка свойств
            $time = microtime(true) - $time_start;
            $this->log("Сортировка свойств завершено: {$time}s");
        }*/

        if($this->IMPORT_ELEMENTS == 'Y') {
            $time_start = microtime(true);
            $this->log("Привязка аналогов");
            $this->saveAnalogs($this->IBLOCK_PRODUCTS); // аналоги
            $time = microtime(true) - $time_start;
            $this->log("Привязка аналогов завершено: {$time}s");

            $time_start = microtime(true);
            $this->log("Привязка аксессуаров");
            $this->saveAccessories($this->IBLOCK_PRODUCTS); // аксессуары
            $time = microtime(true) - $time_start;
            $this->log("Привязка аксессуаров завершено: {$time}s");

            $time_start = microtime(true);
            $this->log("Привязка сопутствующих товаров");
            $this->saveRelated($this->IBLOCK_PRODUCTS); // сопутствующие товары
            $time = microtime(true) - $time_start;
            $this->log("Привязка сопутствующих товаров завершено: {$time}s");
        }

        if($this->IMPORT_SPECIAL == 'Y') {
            $time_start = microtime(true);
            $this->log("Запись спецпредложений");
            $this->saveSpecials($this->IBLOCK_PRODUCTS); // спецпредложения
            $time = microtime(true) - $time_start;
            $this->log("Запись спецпредложений завершено: {$time}s");

            $time_start = microtime(true);
            $this->log("Запись новинок");
            $this->saveNew(); // новинки
            $time = microtime(true) - $time_start;
            $this->log("Запись новинок завершено: {$time}s");

            $time_start = microtime(true);
            $this->log("Запись лидеров продаж");
            $this->saveBestsellers(); // лидеры продаж
            $time = microtime(true) - $time_start;
            $this->log("Запись лидеров продаж завершено: {$time}s");

        }

        if($this->IMPORT_ELEMENTS == 'Y' || $this->IMPORT_FILTER == 'Y') {
            $time_start = microtime(true);
            $this->log("Запись серий");
            $this->saveSeries($this->IBLOCK_SERIES, $this->IBLOCK_PRODUCTS); // серии
            $time = microtime(true) - $time_start;
            $this->log("Запись серий завершено: {$time}s");
        }

        if($this->IMPORT_ELEMENTS == 'Y' || $this->IMPORT_CATALOG == 'Y') {

            if($this->bFullImport) {
                $time_start = microtime(true);
                $this->log("Деактивация товаров");
                $this->deactivateProducts($this->IBLOCK_PRODUCTS); // отключаем товары, не попавшие в файл
                $time = microtime(true) - $time_start;
                $this->log("Деактивация товаров завершено: {$time}s");
            }

        }

        if($this->IMPORT_ELEMENTS == 'Y' || $this->bFullImport) {
            $time_start = microtime(true);
            $this->log("Подсчет кол-ва товаров в разделах");
            $this->updateSectionsActivity($this->IBLOCK_PRODUCTS); // отключаем пустые разделы и проставляем кол-во товаров
            $time = microtime(true) - $time_start;
            $this->log("Подсчет кол-ва товаров в разделах завершено: {$time}s");
        }

//        if($this->UPDATE_FACET_INDEX == "Y") {
//            $time_start = microtime(true);
//            $this->log("Обновление фасетного индекса");
//            \Bitrix\Iblock\PropertyIndex\Manager::deleteIndex($this->IBLOCK_PRODUCTS);
//            \Bitrix\Iblock\PropertyIndex\Manager::markAsInvalid($this->IBLOCK_PRODUCTS);
//            $index = \Bitrix\Iblock\PropertyIndex\Manager::createIndexer($this->IBLOCK_PRODUCTS);
//            $index->startIndex();
//            $res = $index->continueIndex();
//            if($res > 0) {
//                $index->endIndex();
//                CBitrixComponent::clearComponentCache("bitrix:catalog.smart.filter");
//            }
//            $time = microtime(true) - $time_start;
//            $this->log("Обновление фасетного индекса завершено: {$time}s");
//        }

        $time = microtime(true) - $time_total_start;
        $this->log("Общее время выполнения: {$time}s");
        $this->log(date('d.m.Y H:i:s')." импорт завершен");

    }

    /**
     * /catalog/filter
     */
    private function parseFilter() {
        $this->arProps = Array();
        $depth = $this->reader->depth;
        if($this->reader->isEmptyElement){
            return;
        }
        $sort = 2000;

        while($this->reader->read()) {
            //$this->log(__FUNCTION__ . '/ ' . $this->reader->depth . ': ' . $this->reader->name);

            if($this->reader->name === 'filter' && $this->reader->nodeType == XMLReader::END_ELEMENT
               && $this->reader->depth == $depth
            ) {
                break;
            }

            if($this->reader->name === 'category' && $this->reader->nodeType == XMLReader::ELEMENT) {
                $this->parseFilterCategory($sort);
                $sort += 10;
            }
        }

        /**
         * Записываем свойства в БД
         */
        foreach($this->arProps as $arProp) {
            $this->catalog->updateProperty($arProp, $this->bFullImport);

            // если св-во является серией, записываем в массив series для последующего сохраниения методом saveSeries
            if($arProp['IS_SERIES']) {
                if(array_key_exists($arProp['XML_ID'], $this->series)) {
                    $this->series[$arProp['XML_ID']]['VALUES'] = array_merge($this->series[$arProp['XML_ID']]['VALUES'],
                                                                             $arProp['VALUES']);
                }
                else {
                    $this->series[$arProp['XML_ID']] = $arProp;
                }
            }
            $this->keepPropertiesList[] = $arProp['CODE'];
        }
        $this->arProps = Array();

        // удаляем свойства, которых нет в xml
        foreach($this->catalog->propertiesCache[$this->IBLOCK_PRODUCTS] as $property) {
            if(!in_array($property['CODE'], $this->keepPropertiesList)) {
                CIBlockProperty::Delete($property['ID']);
            }
        }

        /**
         * Записываем файл с массивом свойств, выводимых в каталоге
         */
        /*$arCatalogProps = Array();*/

        /**
         * Выбираем все свойства ИБ Товары для компонента bitrix:catalog
         * см /catalog/index.php
         */
        /*$rsProperties = CIBlockProperty::GetList(Array("sort" => "asc",
                                                       "name" => "asc"), Array("ACTIVE"    => "Y",
                                                                               "IBLOCK_ID" => $this->IBLOCK_PRODUCTS,)

        );

        while($arProperties = $rsProperties->Fetch()) {

            // Формируем массив свойств выводимых в каталоге
            if($arProperties["SORT"] >= 2000) {
                $arCatalogProps[] = $arProperties["CODE"];
            }

        }*/

        /*
        include $_SERVER['DOCUMENT_ROOT'] . '/catalog/props.php';
        if(!is_array($arCatalogProps)) $arCatalogProps = Array();
        $arCatalogProps = array_merge($arCatalogProps, $this->arCataogDetailPropsCode);
        $arCatalogProps = array_unique($arCatalogProps);
        */
        /*file_put_contents($_SERVER['DOCUMENT_ROOT'].'/catalog/props.php',
                          '<'.'?'."php\n".'$arCatalogProps = '.var_export($arCatalogProps, true).";\n\n?".'>');*/

    }

    /**
     * /catalog/files_product
     */
    private function parseFilesProduct() {
        $depth = $this->reader->depth;
        if($this->reader->isEmptyElement){
            return;
        }
        $sort = 2000;

        while($this->reader->read()) {
            if($this->reader->name === 'files_product' && $this->reader->nodeType == XMLReader::END_ELEMENT
                && $this->reader->depth == $depth
            ) {
                break;
            }

            if($this->reader->name === 'filesgroup' && $this->reader->nodeType == XMLReader::ELEMENT) {
                $this->parseFilesGroup($sort);
                $sort += 10;
            }
        }
    }

    /**
     * /catalog/photos_product
     */
    private function parsePhotosProduct() {
        $depth = $this->reader->depth;
        if($this->reader->isEmptyElement){
            return;
        }

        while($this->reader->read()) {
            if($this->reader->name === 'photos_product' && $this->reader->nodeType == XMLReader::END_ELEMENT
                && $this->reader->depth == $depth
            ) {
                break;
            }

            if($this->reader->name === 'filesgroup' && $this->reader->nodeType == XMLReader::ELEMENT) {
                $this->parseFilesGroup();
            }
        }
    }

    /**
     * /catalog/description_product
     */
    private function parseDescriptionProduct() {
        $depth = $this->reader->depth;
        if($this->reader->isEmptyElement){
            return;
        }

        while($this->reader->read()) {
            if($this->reader->name === 'description_product' && $this->reader->nodeType == XMLReader::END_ELEMENT
                && $this->reader->depth == $depth
            ) {
                break;
            }

            if($this->reader->name === 'filesgroup' && $this->reader->nodeType == XMLReader::ELEMENT) {
                $this->parseFilesGroup();
            }
        }
    }

    /**
     * /catalog/files_product/filesgroup
     * /catalog/photos_product/filesgroup
     * /catalog/description_product/filesgroup
     */
    private function parseFilesGroup($sort = 2000) {
        if($this->reader->isEmptyElement) {
            return;
        }

        $aFileGroup = Array(
            'XML_ID' => $this->reader->getAttribute('id'),
            'TYPE' => $this->reader->getAttribute('type'),
            'TITLE' => $this->reader->getAttribute('title'),
            'SORT' => $sort
        );

        $this->updateFilesGroup($aFileGroup);

        $depth = $this->reader->depth;
        $iSort = 200;
        while($this->reader->read()) {

            if($this->reader->name === 'filesgroup' && $this->reader->nodeType == XMLReader::END_ELEMENT
                && $this->reader->depth == $depth
            ) {
                break;
            }

            if($this->reader->name === 'value' && $this->reader->nodeType == XMLReader::ELEMENT) {
                if($arValue = $this->parseFilesGroupValue()) {
                    $arValue['FILE_GROUP_XML_ID'] = $aFileGroup['XML_ID'];
                    $arValue['SORT'] = $iSort;
                    $this->updateProductFiles($arValue);
                    $iSort += 10;
                }
            }
        }

    }

    /**
     * /catalog/files_product/filesgroup/value
     * /catalog/photos_product/filesgroup/value
     * /catalog/description_product/filesgroup/value
     */
    private function parseFilesGroupValue() {
        $sValueID = $this->reader->getAttribute('id');
        $sValueTitle = $this->reader->getAttribute('title');
        $sValuePath = $this->reader->getAttribute('path');

        $aReturn = Array('XML_ID' => $sValueID,
                         'PATH' => $sValuePath);
        if(!empty($sValueTitle)) $aReturn['TITLE'] = $sValueTitle;

        return $aReturn;
    }


    private function updateFilesGroup($arFields){
        $iExistingGroupId = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $this->IBLOCK_ID_PRODUCT_FILES_GROUP,
                                                                  'XML_ID' => $arFields['XML_ID']), false, false, Array('ID'))->GetNext();
        $el = new CIBlockElement;

        $PROP = array();
        $PROP['TYPE'] = $arFields['TYPE'];

        $arSavingFields = Array(
            "IBLOCK_ID"      => $this->IBLOCK_ID_PRODUCT_FILES_GROUP,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => $arFields['TITLE'],
            "ACTIVE"         => "Y",
            'XML_ID' => $arFields['XML_ID'],
            'SORT' => $arFields['SORT']
        );

        if(!empty($iExistingGroupId['ID'])){
            $el->Update($iExistingGroupId['ID'], $arSavingFields);
        }else{
            $el->Add($arSavingFields);
        }
    }

    private function updateProductFiles($arFields){
        $iExistingGroupId = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $this->IBLOCK_ID_PRODUCT_FILES,
            'XML_ID' => $arFields['XML_ID']), false, false, Array('ID'))->GetNext();
        $el = new CIBlockElement;

        $PROP = array();
        $PROP['PATH'] = $arFields['PATH'];
        $PROP['FILESGROUP'] = $arFields['FILE_GROUP_XML_ID'];

        $arSavingFields = Array(
            "IBLOCK_ID"      => $this->IBLOCK_ID_PRODUCT_FILES,
            "PROPERTY_VALUES"=> $PROP,
            "NAME"           => !empty($arFields['TITLE']) ? $arFields['TITLE'] : $arFields['PATH'],
            "ACTIVE"         => "Y",
            'XML_ID' => $arFields['XML_ID'],
            'SORT' => $arFields['SORT']
        );

        if(!empty($iExistingGroupId['ID'])){
            $el->Update($iExistingGroupId['ID'], $arSavingFields);
        }else{
            $el->Add($arSavingFields);
        }
    }

    /**
     * /catalog/special
     */
    private function parseSpecial() {
        $depth = $this->reader->depth;

        $this->specials = Array();

        if($this->reader->isEmptyElement){
            return;
        }

        while($this->reader->read()) {
            //$this->log(__FUNCTION__ . '/ ' . $this->reader->depth . ': ' . $this->reader->name);

            if($this->reader->name === 'special' && $this->reader->nodeType == XMLReader::END_ELEMENT
               && $this->reader->depth == $depth
            ) {
                break;
            }

            if($this->reader->name === 'item' && $this->reader->nodeType == XMLReader::ELEMENT) {
                $this->parseSpecialItem();
            }
        }
    }

    private function parseSpecialItem() {
        $arFields = array("XML_ID"   => $this->reader->getAttribute('id'),
                          'PRODUCTS' => Array(),
                          'SECTIONS' => Array(),);
        if($sStart = $this->reader->getAttribute('start')) {
            $arFields['DATE_ACTIVE_FROM'] = CIBlockFormatProperties::DateFormat('FULL', strtotime($sStart));
        }
        else {
            $arFields['DATE_ACTIVE_FROM'] = CIBlockFormatProperties::DateFormat('FULL', time());
        }
        if($sStop = $this->reader->getAttribute('stop')) {
            $arFields['DATE_ACTIVE_TO'] = CIBlockFormatProperties::DateFormat('FULL', strtotime($sStop));
        }
        else {
            $arFields['DATE_ACTIVE_TO'] = CIBlockFormatProperties::DateFormat('FULL', time() + 365 * 86400);
        }

        if($sProducts = $this->reader->getAttribute('product')) {
            $arFields['PRODUCTS'] = explode(',', $sProducts);
        }

        if($sSections = $this->reader->getAttribute('section')) {
            $arFields['SECTIONS'] = explode(',', $sSections);
        }

        $sActive = (string)$this->reader->getAttribute('active');
        if(strlen($sActive) > 0 && $sActive == '0') {
            $arFields['DEACTIVATE'] = 'Y';
        }

        $this->specials[] = $arFields;
    }

    private function saveSpecials($iblockId) {
        if(!is_array($this->specials)) {
            return;
        }
        global $USER_FIELD_MANAGER;
        $sEntityID = 'IBLOCK_'.$iblockId.'_SECTION';

        $propValueID = $this->catalog->enumValueIDByValue('Y', 'IS_SPECIAL', $this->IBLOCK_PRODUCTS);

        // отключаем все спецпредложения
        $rsItems = CIBlockElement::GetList(Array(), Array('IBLOCK_ID'                 => $this->IBLOCK_PRODUCTS,
                                                          'PROPERTY_IS_SPECIAL_VALUE' => 'Y'), false, false,
                                           Array('ID'));
        while($arItem = $rsItems->Fetch()) {
            CIBlockElement::SetPropertyValuesEx($arItem['ID'], $this->IBLOCK_PRODUCTS, Array('IS_SPECIAL'      => 'N',
                                                                                             'IS_SPECIAL_FROM' => '',
                                                                                             'IS_SPECIAL_TO'   => '',));
        }

        // обнуляем привязку спецпредложений к разделам
        $rsSections = CIBlockSection::GetList(Array(), Array('IBLOCK_ID'            => $this->IBLOCK_PRODUCTS,
                                                             '!UF_PRODUCTS_SPECIAL' => false,), false, Array('ID'));
        while($arSection = $rsSections->Fetch()) {
            $USER_FIELD_MANAGER->Update($sEntityID, $arSection['ID'], Array('UF_PRODUCTS_SPECIAL' => Array(),));
        }

        foreach($this->specials as $arSpecial) {

            $arProductIDS = Array();
            $arSectionIDS = Array();

            foreach($arSpecial['PRODUCTS'] as $xmlID) {
                if(strlen($xmlID) > 0){
                    if($arElement = $this->catalog->getElementByXmlID($xmlID, $iblockId)) {
                        $arProductIDS[] = $arElement['ID'];
                        CIBlockElement::SetPropertyValuesEx($arElement['ID'], $iblockId, Array('IS_SPECIAL'      => $propValueID,
                                                                                               'IS_SPECIAL_FROM' => $arSpecial['DATE_ACTIVE_FROM'],
                                                                                               'IS_SPECIAL_TO'   => $arSpecial['DATE_ACTIVE_TO'],));
                    }
                }
            }

            foreach($arSpecial['SECTIONS'] as $xmlID) {
                if(strlen($xmlID) > 0){
                    if($arElement = $this->catalog->getSectionByXmlID($xmlID, $iblockId)) {
                        $arSectionIDS[] = $arElement['ID'];
                    }
                }
            }

            $arProductIDS = array_unique($arProductIDS);
            $arSectionIDS = array_unique($arSectionIDS);

            foreach($arSectionIDS as $sectionID) {
                $USER_FIELD_MANAGER->Update($sEntityID, $sectionID, Array('UF_PRODUCTS_SPECIAL' => $arProductIDS,));
            }

        }
    }

    /**
     * /catalog/section_isnew
     */
    private function parseNew() {
        $depth = $this->reader->depth;

        $this->new = Array();

        if($this->reader->isEmptyElement){
            return;
        }

        while($this->reader->read()) {
            //$this->log(__FUNCTION__ . '/ ' . $this->reader->depth . ': ' . $this->reader->name);

            if($this->reader->name === 'section_isnew' && $this->reader->nodeType == XMLReader::END_ELEMENT
               && $this->reader->depth == $depth
            ) {
                break;
            }

            if($this->reader->name === 'item' && $this->reader->nodeType == XMLReader::ELEMENT) {
                $this->parseNewItem();
            }
        }

    }

    private function parseNewItem() {
        if($sProducts = $this->reader->getAttribute('product')) {
            $sSectionXMLId = $this->reader->getAttribute('id');
            $sActive = (string)$this->reader->getAttribute('active');
            if(strlen($sSectionXMLId) > 0) {
                $arSectionXMLId = explode(',', $sSectionXMLId);
            }
            else {
                $arSectionXMLId = Array(0); // без привязки к разделам
            }
            $arProducts = explode(',', $sProducts);
            foreach($arSectionXMLId as $sectionXMLId) {
                $arFields = Array();
                foreach($arProducts as $XML_ID) {
                    $arFields['PRODUCTS'][] = $XML_ID;
                }
                if(strlen($sActive) > 0 && $sActive == '0') {
                    $arFields['DEACTIVATE'] = 'Y';
                }

                $this->new[$sectionXMLId] = $arFields;
            }

        }
    }

    private function saveNew() {
        if(!is_array($this->new)) {
            return;
        }

        global $USER_FIELD_MANAGER;
        $sEntityID = 'IBLOCK_'.$this->IBLOCK_PRODUCTS.'_SECTION';

        $propValueID = $this->catalog->enumValueIDByValue('Y', 'IS_NEW', $this->IBLOCK_PRODUCTS);

        // отключаем все новинки
        $rsItems = CIBlockElement::GetList(Array(), Array('IBLOCK_ID'             => $this->IBLOCK_PRODUCTS,
                                                          'PROPERTY_IS_NEW_VALUE' => 'Y'), false, false, Array('ID'));
        while($arItem = $rsItems->Fetch()) {
            CIBlockElement::SetPropertyValuesEx($arItem['ID'], $this->IBLOCK_PRODUCTS, Array('IS_NEW' => 'N',));
        }

        // обнуляем привязку новинок к разделам
        $rsSections = CIBlockSection::GetList(Array(), Array('IBLOCK_ID'        => $this->IBLOCK_PRODUCTS,
                                                             '!UF_PRODUCTS_NEW' => false,), false, Array('ID'));
        while($arSection = $rsSections->Fetch()) {
            $USER_FIELD_MANAGER->Update($sEntityID, $arSection['ID'], Array('UF_PRODUCTS_NEW' => Array(),));
        }


        foreach($this->new as $sectionXmlId => $arFields) {
            $arProductIDS = Array();
            if(!is_array($arFields['PRODUCTS'])) {
                continue;
            }
            foreach($arFields['PRODUCTS'] as $XML_ID) {
                if($arElement = $this->catalog->getElementByXmlID($XML_ID, $this->IBLOCK_PRODUCTS)) {
                    CIBlockElement::SetPropertyValuesEx($arElement['ID'], $this->IBLOCK_PRODUCTS,
                                                        Array('IS_NEW' => $propValueID,));
                    $arProductIDS[] = $arElement['ID'];
                }
            }
            if($sectionXmlId != 0 && !empty($arProductIDS)) {
                $arProductIDS = array_unique($arProductIDS);
                $arSection = $this->catalog->getSectionByXmlID($sectionXmlId, $this->IBLOCK_PRODUCTS);
                if($arSection) {
                    $USER_FIELD_MANAGER->Update($sEntityID, $arSection['ID'],
                                                Array('UF_PRODUCTS_NEW' => $arProductIDS,));
                }
            }
        }
    }

    /**
     * /catalog/section_bestseller
     */
    private function parseBestsellers() {
        $depth = $this->reader->depth;

        $this->bestsellers = Array();

        if($this->reader->isEmptyElement){
            return;
        }

        while($this->reader->read()) {
            //$this->log(__FUNCTION__ . '/ ' . $this->reader->depth . ': ' . $this->reader->name);

            if($this->reader->name === 'section_bestseller' && $this->reader->nodeType == XMLReader::END_ELEMENT
               && $this->reader->depth == $depth
            ) {
                break;
            }

            if($this->reader->name === 'item' && $this->reader->nodeType == XMLReader::ELEMENT) {
                $this->parseBestsellerItem();
            }
        }
    }

    private function parseBestsellerItem() {
        if($sProducts = $this->reader->getAttribute('product')) {
            $sSectionXMLId = $this->reader->getAttribute('id');
            $sActive = (string)$this->reader->getAttribute('active');
            if(strlen($sSectionXMLId) > 0) {
                $arSectionXMLId = explode(',', $sSectionXMLId);
            }
            else {
                $arSectionXMLId = Array(0); // без привязки к разделам
            }
            $arProducts = explode(',', $sProducts);
            foreach($arSectionXMLId as $sectionXMLId) {
                $arFields = Array();
                foreach($arProducts as $XML_ID) {
                    $arFields['PRODUCTS'][] = $XML_ID;
                }
                if(strlen($sActive) > 0 && $sActive == '0') {
                    $arFields['DEACTIVATE'] = 'Y';
                }

                $this->bestsellers[$sectionXMLId] = $arFields;
            }

        }
    }

    private function saveBestsellers() {
        if(!is_array($this->bestsellers)) {
            return;
        }

        global $USER_FIELD_MANAGER;
        $sEntityID = 'IBLOCK_'.$this->IBLOCK_PRODUCTS.'_SECTION';

        $propValueID = $this->catalog->enumValueIDByValue('Y', 'IS_BESTSELLER', $this->IBLOCK_PRODUCTS);

        // отключаем всех лидеров
        $rsItems = CIBlockElement::GetList(Array(), Array('IBLOCK_ID'                    => $this->IBLOCK_PRODUCTS,
                                                          'PROPERTY_IS_BESTSELLER_VALUE' => 'Y'), false, false,
                                           Array('ID'));
        while($arItem = $rsItems->Fetch()) {
            CIBlockElement::SetPropertyValuesEx($arItem['ID'], $this->IBLOCK_PRODUCTS, Array('IS_BESTSELLER' => 'N',));
        }

        // обнуляем привязку лидеров к разделам
        $rsSections = CIBlockSection::GetList(Array(), Array('IBLOCK_ID'         => $this->IBLOCK_PRODUCTS,
                                                             '!UF_PRODUCTS_BEST' => false,), false, Array('ID'));
        while($arSection = $rsSections->Fetch()) {
            $USER_FIELD_MANAGER->Update($sEntityID, $arSection['ID'], Array('UF_PRODUCTS_BEST' => Array(),));
        }

        foreach($this->bestsellers as $sectionXmlId => $arFields) {
            $arProductIDS = Array();
            if(!is_array($arFields['PRODUCTS'])) {
                continue;
            }
            foreach($arFields['PRODUCTS'] as $XML_ID) {
                if($arElement = $this->catalog->getElementByXmlID($XML_ID, $this->IBLOCK_PRODUCTS)) {
                    CIBlockElement::SetPropertyValuesEx($arElement['ID'], $this->IBLOCK_PRODUCTS,
                                                        Array('IS_BESTSELLER' => ($arFields['DEACTIVATE'] == 'Y' ? 'N'
                                                            : $propValueID),));
                    $arProductIDS[] = $arElement['ID'];
                }
            }
            if($sectionXmlId != 0 && !empty($arProductIDS)) {
                $arProductIDS = array_unique($arProductIDS);
                $arSection = $this->catalog->getSectionByXmlID($sectionXmlId, $this->IBLOCK_PRODUCTS);
                if($arSection) {
                    $USER_FIELD_MANAGER->Update($sEntityID, $arSection['ID'],
                                                Array('UF_PRODUCTS_BEST' => ($arFields['DEACTIVATE'] == 'Y' ? Array()
                                                    : $arProductIDS),));
                }
            }
        }
    }

    /**
     * /catalog/filter/category[@is_series=1]
     *
     * Если у категории фильтра задан аттрибут is_series=1, то все товары со значениями из этой категории объединяются в серии
     * Серии хранятся в отдельном инфоблоке, товары привязываются к сериям при помощи множественного св-ва SERIES
     *
     * @param $iblockId int инфоблок с сериями
     * @param $catalogIblockId
     */
    private function saveSeries($iblockId, $catalogIblockId) {
        CModule::IncludeModule('iblock');

        // обходим весь каталог и обнуляем значение св-ва SERIES
        if($this->bFullImport) {
            $rsElements = CIBlockElement::GetList(Array(), Array('IBLOCK_ID'        => $catalogIblockId,
                                                                 '!PROPERTY_SERIES' => false), false, false,
                                                  Array('ID'));
            while($arElement = $rsElements->Fetch()) {
                CIBlockElement::SetPropertyValues($arElement['ID'], $catalogIblockId, false, 'SERIES');
            }
        }

        foreach($this->series as $arProperty) {
            foreach($arProperty['VALUES'] as $arValue) {

                if($arValue = $this->propertyValueByXmlID($arValue['XML_ID'])) {
                    $arFields = Array('IBLOCK_ID' => $iblockId,
                                      'XML_ID'    => $arProperty['XML_ID'].'_'.$arValue['XML_ID'],
                                      'NAME'      => $arProperty['NAME'].' ('.$arValue['VALUE'].')',
                                      'ACTIVE'    => 'Y',);
                    $ID = $this->catalog->updateElement($arFields);

                    $rsElements = CIBlockElement::GetList(Array(), Array('IBLOCK_ID'                      => $catalogIblockId,
                                                                         '=PROPERTY_'
                                                                         .$arProperty['CODE']             => $arValue['ID'],),
                                                          false, false, Array('ID'));

                    while($arElement = $rsElements->Fetch()) {
                        $arSeries = Array($ID);
                        $rsSeries = CIBlockElement::GetProperty($catalogIblockId, $arElement['ID'], "sort", "asc",
                                                                Array("CODE" => "SERIES"));
                        while($arSeriesValue = $rsSeries->Fetch()) {
                            $arSeries[] = $arSeriesValue['VALUE'];
                        }
                        $arSeries = array_unique($arSeries);
                        CIBlockElement::SetPropertyValuesEx($arElement['ID'], $catalogIblockId,
                                                            Array('SERIES' => $arSeries,));

                    }

                }
            }
        }

        // удаляем серии, к которым не привязан ни один товар
        $rsSeries = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $iblockId), false, false, Array('ID'));
        while($arSeries = $rsSeries->Fetch()) {
            $rsElements = CIBlockElement::GetList(Array(), Array('IBLOCK_ID'       => $catalogIblockId,
                                                                 'PROPERTY_SERIES' => $arSeries['ID'],), false,
                                                  Array('nTopCount' => 1), Array('ID'));
            if($rsElements->SelectedRowsCount() <= 0) {
                CIBlockElement::Delete($arSeries['ID']);
            }
        }

    }

    /**
     * /catalog/section
     */
    private function parseSection($parentID = 0, $sort = 0) {
        if($this->reader->isEmptyElement) {
            return;
        }
        $time_start = microtime(true);

        $sName = trim($this->reader->getAttribute('title'));
        $sCode = $this->reader->getAttribute('reference');
        $sXmlId = $this->reader->getAttribute('id');
        $sSeoTitle = $this->reader->getAttribute('meta_title');
        $sSeoDescription = $this->reader->getAttribute('meta_description');
        $sSeoKeywords = $this->reader->getAttribute('meta_keywords');
        $bActive = 'Y';
        if($category = $this->reader->getAttribute('category')) {
            $arSectionProperties = array();
            $arSectionPropertyIds = explode(',', $category);
            foreach($arSectionPropertyIds as $id) {
                $arSectionProperties[] = $this->propertyIds[$id];
            }
        }

        $this->log('Импорт раздела "'.$sName.'"');

        if(!$this->bFullImport) { // при инкрементном обновлении учитываем аттрибут active
            $bActive = $this->reader->getAttribute('active');
            // если аттрибут не указан или ==1, считаем, что раздел включен
            if(strlen($bActive) <= 0 || $bActive == '1') {
                $bActive = 'Y';
            }
            else {
                $bActive = 'N';
            }
        }

        if($this->IMPORT_SECTIONS == 'Y') {

            if(strlen($sCode) <= 0) {
                $sCode = $sXmlId;
            }

            $sPicture = '';
            $sPicturePath = dirname($this->filePath).'/'.$this->reader->getAttribute('pict');

            if(file_exists($sPicturePath) && is_file($sPicturePath)) {
                $sPicture = CFile::MakeFileArray($sPicturePath);
            }

            $sectionID = $this->catalog->updateSection(Array('IBLOCK_SECTION_ID' => $parentID,
                                                             'CODE'              => $sCode,
                                                             'ACTIVE'            => $bActive,
                                                             'NAME'              => $sName,
                                                             'SORT'              => $sort,
                                                             'XML_ID'            => $sXmlId,
                                                             'PICTURE'           => $sPicture,
                                                             "UF_DESCRIPTION" => $sSeoDescription,
                                                             "UF_KEYWORDS" => $sSeoKeywords,
                                                             "UF_TITLE" => $sSeoTitle));

            foreach($arSectionProperties as $arProperty){
                $this->catalog->updateProperySection($sectionID, $arProperty);
            }
        }
        else {
            if($arSection = $this->catalog->getSectionByXmlID($sXmlId, $this->IBLOCK_PRODUCTS)) {
                $sectionID = $arSection['ID'];
            }
        }

        $depth = $this->reader->depth;

        while($this->reader->read()) {
            //$this->log(__FUNCTION__ . '/ ' . $this->reader->depth . ': ' . $this->reader->name);

            if($this->reader->name === 'section' && $this->reader->nodeType == XMLReader::END_ELEMENT
               && $depth == $this->reader->depth
            ) {
                break;
            }

            if($this->reader->name === 'section' && $this->reader->nodeType == XMLReader::ELEMENT) {
                $sort += 10;
                $this->parseSection($sectionID, $sort);
            }
            elseif($this->reader->name === 'product' && $this->reader->nodeType == XMLReader::ELEMENT) {
                if($sectionID > 0) {
                    $this->parseProduct($sectionID);
                }
            }
        }

        $time = microtime(true) - $time_start;
        $this->log('Импорт раздела "'.$sName."\" завершено за: {$time}s");
    }

    /**
     * /catalog/section/product
     *
     * @param $sectionID
     *
     * @return int ID элемента инфоблока
     */
    private function parseProduct($sectionID) {
        $sName = $this->reader->getAttribute('title');
        $sCode = $this->reader->getAttribute('reference');
        $sXmlId = $this->reader->getAttribute('id');
        $sSort = $this->reader->getAttribute('order');

        if(!$sSort && (int)$sSort == 0) {
            $sSort = 500;
        }

        $bActive = 'Y';

        if(!$this->bFullImport) { // при инкрементном обновлении учитываем аттрибут active
            $bActive = $this->reader->getAttribute('active');
            // если аттрибут не указан или ==1, считаем, что товар включен
            if(strlen($bActive) <= 0 || $bActive == '1') {
                $bActive = 'Y';
            }
            else {
                $bActive = 'N';
            }
        }

        $arFields = Array('NAME'              => $sName,
                          'ACTIVE'            => $bActive,
                          'CODE'              => $sCode,
                          'SORT'              => $sSort,
                          'XML_ID'            => $sXmlId,
                          'IBLOCK_SECTION_ID' => $sectionID,);

        $ID = 0;

        if($this->IMPORT_ELEMENTS == 'Y') {

            // Теги нужны для фильтрации на странице поиска по сайту
            // Для каждого товара указываем один тег - корневой раздел каталога
            if(!isset($this->tagsCache[$arFields['IBLOCK_SECTION_ID']])) {
                $arRootSection = CIBlockSection::GetNavChain(IBLOCK_ID_CATALOG, $arFields['IBLOCK_SECTION_ID'],
                                                             Array('NAME'))->Fetch();
                $this->tagsCache[$arFields['IBLOCK_SECTION_ID']] = $arRootSection;
            }
            else {
                $arRootSection = $this->tagsCache[$arFields['IBLOCK_SECTION_ID']];
            }

            $arFields['TAGS'] = str_replace(',', '&comma;', $arRootSection['NAME']);

            /**
             * @todo убрать !$this->bFullImport &&
             */
            /*
            if (!$this->bFullImport && $xmlDescriptionFile = $this->reader->getAttribute('kart')) {
                //$xmlDescriptionFile = dirname($this->filePath) . '/' . str_replace('\\', '/', $xmlDescriptionFile);
                $xmlDescriptionFile = $this->getRemoteFile('shablon/' . trim($xmlDescriptionFile));
                if (file_exists($xmlDescriptionFile) && is_file($xmlDescriptionFile)) {
                    try {
                        $sXmlDescription = trim(file_get_contents($xmlDescriptionFile));
                        if (strlen($sXmlDescription) > 0) {
                            $oDescription = new SimpleXMLElement($sXmlDescription, LIBXML_NOCDATA);

                            $arFields['DETAIL_TEXT_TYPE'] = 'html';
                            $arFields['DETAIL_TEXT'] = preg_replace('/^<description>(.*)<\/description>$/us', '$1', $oDescription->description->asXML());

                            //$sPicturePath = dirname($this->filePath) . '/' . (string)$oDescription->image['src'];
                            $sPicturePath = $this->getRemoteFile('img/' . (string)$oDescription->image['src']);
                            if (file_exists($sPicturePath) && is_file($sPicturePath)) {

                                // конвертируем gif в jpeg
                                if (substr($sPicturePath, -3) == 'gif') {
                                    $oImage = imagecreatefromgif($sPicturePath);
                                    $sPicturePath = substr($sPicturePath, 0, -3) . 'jpg';
                                    imagejpeg($oImage, $sPicturePath, 100);
                                    imagedestroy($oImage);
                                }

                                $arFields['DETAIL_PICTURE'] = CFile::MakeFileArray($sPicturePath); //CFile::GetFileArray($this->getFileID($sPicturePath));
                            }
                        }
                    } catch (Exception $e) {
                    }
                }


            }*/

            $arPropertyValues = Array();

            // значения свойств из ноды filter
            if($category = $this->reader->getAttribute('category')) {
                $arPropsValues = explode(',', $category);
                foreach($arPropsValues as $xmlId) {
                    if($arValue = $this->propertyValueByXmlID($xmlId)) {
                        $arPropertyValues[$arValue['PROPERTY_ID']] = $arValue['ID'];
                    }
                }
            }

            $sSeoTitle = $this->reader->getAttribute('meta_title');
            $sSeoDescription = $this->reader->getAttribute('meta_description');
            $sSeoKeywords = $this->reader->getAttribute('meta_keywords');

            $arPropertyValues['META_TITLE'] = $sSeoTitle;
            $arPropertyValues['META_KEYWORDS'] = $sSeoKeywords;
            $arPropertyValues['META_DESCRIPTION'] = $sSeoDescription;

            // документация PDF
            /**
             * @todo убрать !$this->bFullImport &&
             */
            if(!$this->bFullImport && $sPdf = $this->reader->getAttribute('pdf')) {
                //$sPdf = dirname($this->filePath) . '/' . $sPdf;
                $sPdf = $this->getRemoteFile('pdf/'.$sPdf);
                if(file_exists($sPdf) && is_file($sPdf)) {
                    $arPropertyValues['PDF'] = CFile::MakeFileArray($sPdf); //CFile::GetFileArray($this->getFileID($sPdf), 'catalog');
                }
            }

            if($sFiles = $this->reader->getAttribute('files')){
                if($sFiles != '') {
                    $arFiles = explode(',', $sFiles);
                    $arPropertyValues['FILES_PRODUCT'] = $arFiles;
                }
            }

            if($sPhotos = $this->reader->getAttribute('photos')){
                if($sPhotos != '') {
                    $arPhotos = explode(',', $sPhotos);
                    $arPropertyValues['PHOTOS_PRODUCT'] = $arPhotos;
                }
            }

            if($sDescription = $this->reader->getAttribute('description')){
                if($sDescription != '') {
                    $arPropertyValues['DESCRIPTION_PRODUCT'] = $sDescription;
                }
            }


            if($sRaiting = $this->reader->getAttribute('rating')){
                if($sRaiting != '') {
                    $arPropertyValues['RAITING'] = (int)$sRaiting;
                }else{
                    $arPropertyValues['RAITING'] = 0;
                }
            }

            //артикул
            $arPropertyValues['SKU'] = $this->reader->getAttribute('article');

            //референс
            $arPropertyValues['REFERENCE'] = $this->reader->getAttribute('reference');
            if(preg_match('/^te0+(\d+)/', $arPropertyValues['REFERENCE'], $matches)){
                $arPropertyValues['REFERENCE_SEARCH'] = $matches[1];
            }
            //индикатор остатков
            $arPropertyValues['STOCK_INDICATOR'] = IntVal(trim($this->reader->getAttribute('rest_indicate')));

            //штрих-код
            $arPropertyValues['BARCODE'] = IntVal(trim($this->reader->getAttribute('barcode')));

            $arPropertyValues['IS_OWN'] = ($this->reader->getAttribute('own_prod') == '1' ? 'Y' : 'N');
            $arPropertyValues['IS_OWN'] = $this->catalog->enumValueIDByValue($arPropertyValues['IS_OWN'], 'IS_OWN',
                                                                             $this->IBLOCK_PRODUCTS);


            /*
             * Новинки парсятся отдельно из ноды section_isnew
             * Лидеры из ноды section_isbestseller
             * Признак спецпредложение ставится в методе saveSpecials
             *
            //является новинкой
            $arPropertyValues['IS_NEW'] = ($this->reader->getAttribute('isnew') == '1' ? 'Y' : 'N');
            $arPropertyValues['IS_NEW'] = $this->catalog->enumValueIDByValue($arPropertyValues['IS_NEW'], 'IS_NEW', $this->IBLOCK_PRODUCTS);

            //является лидером продаж
            $arPropertyValues['IS_BESTSELLER'] = ($this->reader->getAttribute('bestseller') == '1' ? 'Y' : 'N');
            $arPropertyValues['IS_BESTSELLER'] = $this->catalog->enumValueIDByValue($arPropertyValues['IS_BESTSELLER'], 'IS_BESTSELLER', $this->IBLOCK_PRODUCTS);

            //является специальным предложением
            $arPropertyValues['IS_SPECIAL'] = ($this->reader->getAttribute('spec_offer') == '1' ? 'Y' : 'N');
            $arPropertyValues['IS_SPECIAL'] = $this->catalog->enumValueIDByValue($arPropertyValues['IS_SPECIAL'], 'IS_SPECIAL', $this->IBLOCK_PRODUCTS);
            */

            //отображать старую цену
            $arPropertyValues['SHOW_OLD_PRICE'] = ($this->reader->getAttribute('show_old_price') == '1' ? 'Y' : 'N');
            $arPropertyValues['SHOW_OLD_PRICE'] = $this->catalog->enumValueIDByValue($arPropertyValues['SHOW_OLD_PRICE'],
                                                                                     'SHOW_OLD_PRICE',
                                                                                     $this->IBLOCK_PRODUCTS);

            //старая цена
            $arPropertyValues['OLD_PRICE'] = DoubleVal($this->reader->getAttribute('old_price'));

            //товар уценен
            $arPropertyValues['IS_PRICE_DOWN'] = ($this->reader->getAttribute('cut_price') == '1' ? 'Y' : 'N');
            $arPropertyValues['IS_PRICE_DOWN'] = $this->catalog->enumValueIDByValue($arPropertyValues['IS_PRICE_DOWN'],
                                                                                    'IS_PRICE_DOWN',
                                                                                    $this->IBLOCK_PRODUCTS);

            //причина уценки товара
            $arPropertyValues['PRICE_DOWN_REASON'] = trim($this->reader->getAttribute('cut_price_reson'));


            $arFields['PROPERTY_VALUES'] = $arPropertyValues;
            $ID = $this->catalog->updateElement($arFields, $this->bFullImport);

            if(!$ID && $this->catalog->LAST_ERROR != "") {
                $this->log('Element upd: '.$this->catalog->LAST_ERROR.' ('
                           .html_entity_decode($this->reader->readOuterXML()).')');
            }

            // аналоги
            if($sAnalogs = $this->reader->getAttribute('analog')) {
                $this->analogs[$ID] = explode(',', $sAnalogs);
            }

            // аксессуары
            if($sAccessories = $this->reader->getAttribute('accessory')) {
                $this->accessories[$ID] = explode(',', $sAccessories);
            }

            // сопутствующие товары
            if($sRelated = $this->reader->getAttribute('associated')) {
                $this->related[$ID] = explode(',', $sRelated);
            }
        }

        if($this->IMPORT_CATALOG == 'Y') {

            if(!$ID) {
                if($arElement = $this->catalog->getElementByXmlID($arFields['XML_ID'], $this->IBLOCK_PRODUCTS)) {
                    $ID = $arElement['ID'];
                }
            }
            if($ID > 0) {
                // обновляем фасетный индекс товара
                \Bitrix\Iblock\PropertyIndex\Manager::updateElementIndex($this->IBLOCK_PRODUCTS, $ID);
                $arProduct = Array('ID' => $ID);

                if($dim = $this->reader->getAttribute('LBH')) {
                    preg_match('/([\d\.,]+)\sx\s([\d\.,]+)\sx\s([\d\.,]+)/', $dim, $m);
                    $arProduct['LENGTH'] = $m[1];
                    $arProduct['WIDTH'] = $m[2];
                    $arProduct['HEIGHT'] = $m[3];
                }

                $arProduct['MEASURE'] = $this->reader->getAttribute('unit');
                $arProduct['WEIGHT'] = $this->reader->getAttribute('weight');
                $arProduct['QUANTITY'] = $this->reader->getAttribute('rest');
                $arProduct['PRICE'] = $this->reader->getAttribute('price');

                // ополнительные оптовые цены
                $arProduct['PRICES'] = Array('price_ws1' => DoubleVal($this->reader->getAttribute('price_ws1')),
                                             'price_ws2' => DoubleVal($this->reader->getAttribute('price_ws2')),
                                             'price_ws3' => DoubleVal($this->reader->getAttribute('price_ws3')),);

                $this->catalog->updateCatalogProduct($arProduct);
            }
        }

        $this->reader->moveToElement();

        return $ID;
    }

    /**
     * Обновление аналогов
     */
    private function saveAnalogs($iblockID) {
        foreach($this->analogs as $ID => $arAnalogXMLIds) {
            $arAnalogIDS = Array();
            foreach($arAnalogXMLIds as $xmlId) {
                if($arAnalog = $this->catalog->getElementByXmlID($xmlId, $iblockID)) {
                    $arAnalogIDS[] = $arAnalog['ID'];
                }
            }
            $this->catalog->updateElement(Array('ID'              => $ID,
                                                'PROPERTY_VALUES' => Array('ANALOGS' => $arAnalogIDS,),));
        }
    }

    /**
     * Обновление аксессуаров
     */
    private function saveAccessories($iblockID) {
        foreach($this->accessories as $ID => $arAccessoriesXMLIds) {
            $arAccessoriesIDS = Array();
            foreach($arAccessoriesXMLIds as $xmlId) {
                if($arAccessory = $this->catalog->getElementByXmlID($xmlId, $iblockID)) {
                    $arAccessoriesIDS[] = $arAccessory['ID'];
                }
            }
            $this->catalog->updateElement(Array('ID'              => $ID,
                                                'PROPERTY_VALUES' => Array('ACCESSORIES' => $arAccessoriesIDS,),));
        }
    }

    /**
     * Обновление сопутствующих товаров
     */
    private function saveRelated($iblockID) {
        foreach($this->related as $ID => $arRelatedXMLIds) {
            $arRelatedIDS = Array();
            foreach($arRelatedXMLIds as $xmlId) {
                if($arRelated = $this->catalog->getElementByXmlID($xmlId, $iblockID)) {
                    $arRelatedIDS[] = $arRelated['ID'];
                }
            }
            $this->catalog->updateElement(Array('ID'              => $ID,
                                                'PROPERTY_VALUES' => Array('RELATED' => $arRelatedIDS,),));
        }
    }

    /**
     * /catalog/filter/category
     * /catalog/filter/category/category
     */
    private function parseFilterCategory($sort = 2000) {
        if($this->reader->isEmptyElement) {
            return;
        }


        $sPropName = $this->reader->getAttribute('title');
        $sPropCode = strtoupper(substr($this->translit($sPropName), 0, 20));
        $bPropIsSeries = ($this->reader->getAttribute('is_series') == '1');
        $bPropIsProducer = ($this->reader->getAttribute('is_producer') == '1');

        $sPropIsFiltrable = $this->reader->getAttribute('search');
        if(strlen($sPropIsFiltrable) <= 0) {
            $sPropIsFiltrable = 1;
        }
        $bPropIsFiltrable = (IntVal($sPropIsFiltrable) > 0);

        $iType = IntVal($this->reader->getAttribute('type'));


        if(strlen($sPropCode) <= 0) {
            $sPropCode = strtoupper(substr(md5($sPropName), 0, 10));
        }

        $bPropIsPacking = $this->reader->getAttribute('packing') == '1';
        if($bPropIsPacking) {
            $sPropCode .= "_PACKING";
        }

        $iPropId = $this->reader->getAttribute('id');

        if(!array_key_exists($sPropCode, $this->arProps)) {

            $this->arCataogDetailPropsCode[] = $sPropCode;

            $arProperty = Array('NAME'         => $sPropName,
                                'XML_ID'       => $sPropCode,
                                'CODE'         => $sPropCode,
                                'SORT'         => $sort,
                                'IS_SERIES'    => $bPropIsSeries,
                                'IS_PRODUCER'  => $bPropIsProducer,
                                'SMART_FILTER' => ($bPropIsFiltrable ? 'Y' : 'N'),
                                'VALUES'       => Array(),
                                'MULTIPLE' => 'N');

            //0 – выпадающий список; 1 – радиокнопка, 2 – чекбокс; 3 – интервал
            switch($iType) {
                case 0:
                    $arProperty['DISPLAY_TYPE'] = 'P';
                    break;
                case 1:
                    $arProperty['DISPLAY_TYPE'] = 'K';
                    break;
                case 3:
                    $arProperty['PROPERTY_TYPE'] = CAeroIBlockTools::PROPERTY_TYPE_NUMBER;
                    break;
                case 2:
                default:
                    $arProperty['DISPLAY_TYPE'] = 'F';
                    break;
            }

        }
        else {
            $arProperty = $this->arProps[$sPropCode];
        }

        $this->propertyIds[$iPropId] = array('XML_ID' => $sPropCode, 'SMART_FILTER' => ($bPropIsFiltrable ? 'Y' : 'N'), 'DISPLAY_TYPE' => $arProperty['DISPLAY_TYPE']);

        $depth = $this->reader->depth;
        while($this->reader->read()) {
            //$this->log(__FUNCTION__ . '/ ' . $this->reader->depth . ': ' . $this->reader->name);

            if($this->reader->name === 'category' && $this->reader->nodeType == XMLReader::END_ELEMENT
               && $this->reader->depth == $depth
            ) {
                break;
            }

            if($this->reader->name === 'category' && $this->reader->nodeType == XMLReader::ELEMENT) {
                $sort += 10;
                $this->parseFilterCategory($sort);
            }
            elseif($this->reader->name === 'value' && $this->reader->nodeType == XMLReader::ELEMENT) {
                if($arValue = $this->parseFilterValue()) {
                    $arValue['SORT'] = (count($arProperty['VALUES']) + 1) * 10;
                    $arProperty['VALUES'][$arValue['XML_ID']] = $arValue;
                }
            }
        }

        if(!empty($arProperty['VALUES'])) {
            $this->arProps[$arProperty['XML_ID']] = $arProperty;
        }

    }

    /**
     * /catalog/filter/category/category/value
     * @return array (NAME, XML_ID, CODE, SORT, VALUES(XML_ID, VALUE))
     */
    private function parseFilterValue() {
        $sValueID = $this->reader->getAttribute('id');
        $sValueName = $this->reader->getAttribute('title');

        return Array('XML_ID' => $sValueID,
                     'VALUE'  => $sValueName,);
    }


    private function translit($string, $specialReplacement = '') {
        $replace = array("а" => "a",
                         "А" => "a",
                         "б" => "b",
                         "Б" => "b",
                         "в" => "v",
                         "В" => "v",
                         "г" => "g",
                         "Г" => "g",
                         "д" => "d",
                         "Д" => "d",
                         "е" => "e",
                         "Е" => "e",
                         "ж" => "zh",
                         "Ж" => "zh",
                         "з" => "z",
                         "З" => "z",
                         "и" => "i",
                         "И" => "i",
                         "й" => "y",
                         "Й" => "y",
                         "к" => "k",
                         "К" => "k",
                         "л" => "l",
                         "Л" => "l",
                         "м" => "m",
                         "М" => "m",
                         "н" => "n",
                         "Н" => "n",
                         "о" => "o",
                         "О" => "o",
                         "п" => "p",
                         "П" => "p",
                         "р" => "r",
                         "Р" => "r",
                         "с" => "s",
                         "С" => "s",
                         "т" => "t",
                         "Т" => "t",
                         "у" => "u",
                         "У" => "u",
                         "ф" => "f",
                         "Ф" => "f",
                         "х" => "h",
                         "Х" => "h",
                         "ц" => "c",
                         "Ц" => "c",
                         "ч" => "ch",
                         "Ч" => "ch",
                         "ш" => "sh",
                         "Ш" => "sh",
                         "щ" => "sch",
                         "Щ" => "sch",
                         "ъ" => "",
                         "Ъ" => "",
                         "ы" => "y",
                         "Ы" => "y",
                         "ь" => "",
                         "Ь" => "",
                         "э" => "e",
                         "Э" => "e",
                         "ю" => "yu",
                         "Ю" => "yu",
                         "я" => "ya",
                         "Я" => "ya",
                         "і" => "i",
                         "І" => "i",
                         "ї" => "yi",
                         "Ї" => "yi",
                         "є" => "e",
                         "Є" => "e");
        $str = iconv("UTF-8", "UTF-8//IGNORE", strtr($string, $replace));
        $str = preg_replace('/[^a-z0-9]/i', $specialReplacement, $str);
        if(strlen($specialReplacement) > 0) {
            $str = preg_replace('/\\'.$specialReplacement.'{2,}/i', $specialReplacement, $str);
        }

        return strtolower(trim($str, $specialReplacement));
    }

    /**
     * Выполняет поиск значения свойства по его XML_ID
     *
     * @param $xmlId
     * @param $sPropertyCode
     *
     * @return array (ID, PROPERTY_ID, VALUE, DEF, SORT, XML_ID, PROPERTY_NAME, PROPERTY_CODE, PROPERTY_SORT)
     */
    private function propertyValueByXmlID($xmlId, $sPropertyCode = '') {
        $xmlId = trim($xmlId);

        if(empty($this->arPropsIndex)) {
            $this->arPropsIndex = $this->getPropertyIndex($this->IBLOCK_PRODUCTS);
        }

        foreach($this->arPropsIndex as $arProp) {
            if(strlen($sPropertyCode) > 0 && $arProp['CODE'] !== $sPropertyCode) {
                continue;
            }

            if($arProp['PROPERTY_TYPE'] == CAeroIBlockTools::PROPERTY_TYPE_LIST) {
                if(array_key_exists($xmlId, $arProp['VALUES'])) {
                    return $arProp['VALUES'][$xmlId];
                }
            }
            elseif($arProp['PROPERTY_TYPE'] == CAeroIBlockTools::PROPERTY_TYPE_IBLOCK_ELEMENTS) {
                if(!isset($this->linkedIblockItemsCache[$arProp['LINK_IBLOCK_ID']])) {
                    $rsValues = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $arProp['LINK_IBLOCK_ID'],
                                                                       '!XML_ID'   => false), false, false, array('ID',
                                                                                                                  'IBLOCK_ID',
                                                                                                                  'XML_ID'));
                    while($arElement = $rsValues->Fetch()) {
                        $this->linkedIblockItemsCache[$arProp['LINK_IBLOCK_ID']][$arElement['XML_ID']] = array('PROPERTY_ID'   => $arProp['ID'],
                                                                                                               'PROPERTY_CODE' => $arProp['CODE'],
                                                                                                               'PROPERTY_NAME' => $arProp['NAME'],
                                                                                                               'VALUE'         => $arElement['ID'],
                                                                                                               'ID'            => $arElement['ID'],);
                    }
                }
                if(isset($this->linkedIblockItemsCache[$arProp['LINK_IBLOCK_ID']][$xmlId])) {
                    return $this->linkedIblockItemsCache[$arProp['LINK_IBLOCK_ID']][$xmlId];
                }
            }
        }

        return false;
    }


    /**
     * Деактивирует разделы, в которых нет активных товаров
     * Заполняет пользовательское поле UF_ELEMENT_CNT
     *
     * @param $iblockId
     */
    public function updateSectionsActivity($iblockId) {

        if($iblockId <= 0){
            return false;
        }
        \Bitrix\Main\Loader::includeModule('iblock');

        $sect = new CIBlockSection();
        $allSections = CIBlockSection::GetList([], Array('IBLOCK_ID' => $iblockId, 'CNT_ACTIVE' => 'Y'),
                                               true, ['ID', 'ELEMENT_CNT']);
        while($section = $allSections->Fetch()){
            $sect->Update($section['ID'], [
                'UF_ELEMENT_CNT' => $section['ELEMENT_CNT'],
                'ACTIVE' => $section['ELEMENT_CNT'] > 0 ? 'Y' : 'N'
            ]);
        }
    }

    /**
     * Деактивируем товары, не вошедшие в массив $this->productsInFile[]
     *
     * @param $iblockId
     */
    public function deactivateProducts($iblockId) {
        global $DB;
        if($this->startTimestamp <= 0) {
            return;
        }
 	$time_deactivate = date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),$this->startTimestamp);
	$this->log("Деактивируются товары у которых время обновления раньше, чем время начало обновления: {$time_deactivate}");

        $objElement = new CIBlockElement();

        $rsElements = CIBlockElement::GetList(Array('id' => 'desc'), Array('IBLOCK_ID'         => $iblockId,
									'ACTIVE' => 'Y',
                                                                          // '!DATE_MODIFY_FROM' => date($DB->DateFormatToPHP(CLang::GetDateFormat("SHORT")),
                                                                           '!DATE_MODIFY_FROM' => date($DB->DateFormatToPHP(CLang::GetDateFormat("FULL")),
                                                                                                       $this->startTimestamp),),
                                              false, false, Array('ID'));

        $this->log("Количество товаров для деактивации: ".$rsElements->SelectedRowsCount());

        while($arElement = $rsElements->Fetch()) {
            $objElement->Update($arElement['ID'], Array('ACTIVE' => 'N'));
        }

    }

    /**
     * Возвращает ассоциативный массив всех свойств типа список и привязка к элементам с их значениями
     * Ключами массива являются XML_ID свойств
     * Каждое свойство содержит массив VALUES (XML_ID=>занчение)
     *
     * @param $iblockId
     *
     * @return array
     */
    private function getPropertyIndex($iblockId) {
        $arProps = Array();
        $rsProps = CIBlockProperty::GetList(Array(), Array('IBLOCK_ID' => $iblockId));
        while($arProp = $rsProps->Fetch()) {
            if(empty($arProp['XML_ID'])) {
                continue;
            }
            if(!in_array($arProp['PROPERTY_TYPE'], Array('E',
                                                         'L'))
            ) {
                continue;
            }

            $arProp['VALUES'] = Array();
            if($arProp['PROPERTY_TYPE'] == 'L') {
                $rsValues = CIBlockPropertyEnum::GetList(Array(), Array('IBLOCK_ID'   => $iblockId,
                                                                        'PROPERTY_ID' => $arProp['ID']));
                while($arValue = $rsValues->Fetch()) {
                    $arProp['VALUES'][$arValue['XML_ID']] = $arValue;
                }
            }
            $arProps[$arProp['XML_ID']] = $arProp;
        }

        return $arProps;
    }

    /**
     * Создает необходимые свойства инфоблока каталога
     *
     * @param $iblockId
     */
    private function setupCatalogProperties($iblockId) {

        $this->catalog->updateProperty(Array('IBLOCK_ID'      => $iblockId,
                                             'CODE'           => 'ANALOGS',
                                             'XML_ID'         => 'ANALOGS',
                                             'NAME'           => 'Аналоги',
                                             'PROPERTY_TYPE'  => CAeroIBlockTools::PROPERTY_TYPE_IBLOCK_ELEMENTS,
                                             'LINK_IBLOCK_ID' => $iblockId,
                                             'MULTIPLE'       => 'Y',
                                             'SORT'           => '10',
                                             'HINT'           => 'Аналоги'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'PDF',
                                             'XML_ID'        => 'PDF',
                                             'NAME'          => 'Документация',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_FILE,
                                             'SORT'          => '10',
                                             'HINT'          => 'Документация в формате PDF'));


        $this->catalog->updateProperty(Array('IBLOCK_ID'      => $iblockId,
                                             'CODE'           => 'SERIES',
                                             'XML_ID'         => 'SERIES',
                                             'NAME'           => 'Серия',
                                             'PROPERTY_TYPE'  => CAeroIBlockTools::PROPERTY_TYPE_IBLOCK_ELEMENTS,
                                             'LINK_IBLOCK_ID' => $this->IBLOCK_SERIES,
                                             'MULTIPLE'       => 'Y',
                                             'SORT'           => '10',
                                             'HINT'           => 'Серия'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'      => $iblockId,
                                             'CODE'           => 'ACCESSORIES',
                                             'XML_ID'         => 'ACCESSORIES',
                                             'NAME'           => 'Аксессуары',
                                             'PROPERTY_TYPE'  => CAeroIBlockTools::PROPERTY_TYPE_IBLOCK_ELEMENTS,
                                             'LINK_IBLOCK_ID' => $iblockId,
                                             'MULTIPLE'       => 'Y',
                                             'SORT'           => '10',
                                             'HINT'           => 'Аксессуары'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'      => $iblockId,
                                             'CODE'           => 'RELATED',
                                             'XML_ID'         => 'RELATED',
                                             'NAME'           => 'Сопутствующие товары',
                                             'PROPERTY_TYPE'  => CAeroIBlockTools::PROPERTY_TYPE_IBLOCK_ELEMENTS,
                                             'LINK_IBLOCK_ID' => $iblockId,
                                             'MULTIPLE'       => 'Y',
                                             'SORT'           => '10',
                                             'HINT'           => 'Сопутствующие товары'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'SKU',
                                             'XML_ID'        => 'SKU',
                                             'NAME'          => 'Артикул',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_STRING,
                                             'MULTIPLE'      => 'N',
                                             'SEARCHABLE'    => 'Y',
                                             'SORT'          => '10',
                                             'HINT'          => 'Артикул'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'REFERENCE',
                                             'XML_ID'        => 'REFERENCE',
                                             'NAME'          => 'Референс',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_STRING,
                                             'MULTIPLE'      => 'N',
                                             'SEARCHABLE'    => 'Y',
                                             'SORT'          => '10',
                                             'HINT'          => 'Референс'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'REFERENCE_SEARCH',
                                             'XML_ID'        => 'REFERENCE_SEARCH',
                                             'NAME'          => 'Референс для поиска',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_STRING,
                                             'MULTIPLE'      => 'N',
                                             'SEARCHABLE'    => 'Y',
                                             'SORT'          => '10',
                                             'HINT'          => 'Референс для поиска'));
       /* TODO: Разобраться с глюком при обновлении метаполей см.#2597 */
       $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'META_TITLE',
                                             'XML_ID'        => 'META_TITLE',
                                             'NAME'          => 'meta заголовок окна браузера',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_STRING,
                                             'MULTIPLE'      => 'N',
                                             'SEARCHABLE'    => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => 'Заголовок окна браузера'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'META_KEYWORDS',
                                             'XML_ID'        => 'META_KEYWORDS',
                                             'NAME'          => 'meta ключевые слова',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_STRING,
                                             'MULTIPLE'      => 'N',
                                             'SEARCHABLE'    => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => 'Ключевые слова'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'META_DESCRIPTION',
                                             'XML_ID'        => 'META_DESCRIPTION',
                                             'NAME'          => 'meta описание',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_STRING,
                                             'MULTIPLE'      => 'N',
                                             'SEARCHABLE'    => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => 'Значения для meta-тега "описание" страницы'));
        
        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'BARCODE',
                                             'XML_ID'        => 'BARCODE',
                                             'NAME'          => 'Штрих-код',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_STRING,
                                             'MULTIPLE'      => 'N',
                                             'SEARCHABLE'    => 'Y',
                                             'SORT'          => '10',
                                             'HINT'          => 'Штрих-код'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'STOCK_INDICATOR',
                                             'XML_ID'        => 'STOCK_INDICATOR',
                                             'NAME'          => 'Индикатор остатка',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_NUMBER,
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => '1 – мало; 2 – средне; 3 – много'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'IS_OWN',
                                             'XML_ID'        => 'IS_OWN',
                                             'NAME'          => 'Товар собственного производства',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_LIST,
                                             'VALUES'        => Array(Array('XML_ID' => '0',
                                                                            'VALUE'  => 'N'),
                                                                      Array('XML_ID' => '1',
                                                                            'VALUE'  => 'Y'),),
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => '0 – не собственное производство; 1 – собственное производство'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'IS_NEW',
                                             'XML_ID'        => 'IS_NEW',
                                             'NAME'          => 'Является новинкой',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_LIST,
                                             'VALUES'        => Array(Array('XML_ID' => '0',
                                                                            'VALUE'  => 'N'),
                                                                      Array('XML_ID' => '1',
                                                                            'VALUE'  => 'Y'),),
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => '0 – не новинка; 1 – новинка'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'IS_BESTSELLER',
                                             'XML_ID'        => 'IS_BESTSELLER',
                                             'NAME'          => 'Лидер продаж',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_LIST,
                                             'VALUES'        => Array(Array('XML_ID' => '0',
                                                                            'VALUE'  => 'N'),
                                                                      Array('XML_ID' => '1',
                                                                            'VALUE'  => 'Y'),),
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => '0 – не лидер продаж; 1 – лидер продаж'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'OLD_PRICE',
                                             'XML_ID'        => 'OLD_PRICE',
                                             'NAME'          => 'Старая цена',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_NUMBER,
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => 'Старая цена'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'SHOW_OLD_PRICE',
                                             'XML_ID'        => 'SHOW_OLD_PRICE',
                                             'NAME'          => 'Показывать старую цену',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_LIST,
                                             'VALUES'        => Array(Array('XML_ID' => '0',
                                                                            'VALUE'  => 'N'),
                                                                      Array('XML_ID' => '1',
                                                                            'VALUE'  => 'Y'),),
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => '0 – не отображать; 1 – отображать'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'IS_SPECIAL',
                                             'XML_ID'        => 'IS_SPECIAL',
                                             'NAME'          => 'Специальное предложение',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_LIST,
                                             'VALUES'        => Array(Array('XML_ID' => '0',
                                                                            'VALUE'  => 'N'),
                                                                      Array('XML_ID' => '1',
                                                                            'VALUE'  => 'Y'),),
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => '0 – не относится к специальным предложениям; 1 – специальное предложение'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'IS_SPECIAL_FROM',
                                             'XML_ID'        => 'IS_SPECIAL_FROM',
                                             'NAME'          => 'Специальное предложение (дата начала)',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_DATETIME,
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => ''));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'IS_SPECIAL_TO',
                                             'XML_ID'        => 'IS_SPECIAL_TO',
                                             'NAME'          => 'Специальное предложение (дата завершения)',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_DATETIME,
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => ''));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'IS_PRICE_DOWN',
                                             'XML_ID'        => 'IS_PRICE_DOWN',
                                             'NAME'          => 'Товар уценен',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_LIST,
                                             'VALUES'        => Array(Array('XML_ID' => '0',
                                                                            'VALUE'  => 'N'),
                                                                      Array('XML_ID' => '1',
                                                                            'VALUE'  => 'Y'),),
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => '0 – не уцененный товар; 1 – уцененный товар'));

        $this->catalog->updateProperty(Array('IBLOCK_ID'     => $iblockId,
                                             'CODE'          => 'PRICE_DOWN_REASON',
                                             'XML_ID'        => 'PRICE_DOWN_REASON',
                                             'NAME'          => 'Причина уценки',
                                             'PROPERTY_TYPE' => CAeroIBlockTools::PROPERTY_TYPE_STRING,
                                             'MULTIPLE'      => 'N',
                                             'SORT'          => '10',
                                             'HINT'          => 'Причина уценки (выводится, если IS_PRICE_DOWN==Y)'));

    }


    /**
     * Создает необходимые типы цен в каталоге
     */
    private function setupPriceTypes() {
        CModule::IncludeModule('catalog');

        $arPrice = CCatalogGroup::GetList(Array(), Array('XML_ID' => 'price_ws1'))->Fetch();
        if(!$arPrice) {
            CCatalogGroup::Add(Array("NAME"           => "price_ws1",
                                     "XML_ID"         => "price_ws1",
                                     "BASE"           => "N",
                                     "SORT"           => 110,
                                     "USER_GROUP"     => array(1),
                                     // видят цены члены группы 1 (Администраторы)
                                     "USER_GROUP_BUY" => array(1),
                                     // покупают по этой цене
                                     "USER_LANG"      => array("ru" => "Опт, 1 уровень",
                                                               "en" => "Wholesale level 1")));
        }

        $arPrice = CCatalogGroup::GetList(Array(), Array('XML_ID' => 'price_ws2'))->Fetch();
        if(!$arPrice) {
            CCatalogGroup::Add(Array("NAME"           => "price_ws2",
                                     "XML_ID"         => "price_ws2",
                                     "BASE"           => "N",
                                     "SORT"           => 120,
                                     "USER_GROUP"     => array(1),
                                     // видят цены члены группы 1 (Администраторы)
                                     "USER_GROUP_BUY" => array(1),
                                     // покупают по этой цене
                                     "USER_LANG"      => array("ru" => "Опт, 2 уровень",
                                                               "en" => "Wholesale level 2")));
        }

        $arPrice = CCatalogGroup::GetList(Array(), Array('XML_ID' => 'price_ws3'))->Fetch();
        if(!$arPrice) {
            CCatalogGroup::Add(Array("NAME"           => "price_ws3",
                                     "XML_ID"         => "price_ws3",
                                     "BASE"           => "N",
                                     "SORT"           => 130,
                                     "USER_GROUP"     => array(1),
                                     // видят цены члены группы 1 (Администраторы)
                                     "USER_GROUP_BUY" => array(1),
                                     // покупают по этой цене
                                     "USER_LANG"      => array("ru" => "Опт, 3 уровень",
                                                               "en" => "Wholesale level 3")));
        }

    }

    /**
     * Создает необходимые пользовательские поля
     *
     * @param $iblockId
     */
    private function setupUserFields($iblockId) {
        $obUserField = new CUserTypeEntity();

        $sEntityID = 'IBLOCK_'.$iblockId.'_SECTION';

        $arEntity = CUserTypeEntity::GetList(Array(), Array('ENTITY_ID'  => $sEntityID,
                                                            'FIELD_NAME' => 'UF_ELEMENT_CNT'))->Fetch();
        if(!$arEntity) {
            $obUserField->Add(Array('ENTITY_ID'       => $sEntityID,
                                    'FIELD_NAME'      => 'UF_ELEMENT_CNT',
                                    'MULTIPLE'        => 'N',
                                    'USER_TYPE_ID'    => 'integer',
                                    'MANDATORY'       => 'N',
                                    'SHOW_FILTER'     => 'I',
                                    'SETTINGS'        => Array('DEFAULT_VALUE' => 0,
                                                               'MIN_VALUE'     => 0,),
                                    'EDIT_FORM_LABEL' => Array('ru' => 'Кол-во элементов в разделе',
                                                               'en' => 'Elements count',)));
        }

        $arEntity = CUserTypeEntity::GetList(Array(), Array('ENTITY_ID'  => $sEntityID,
                                                            'FIELD_NAME' => 'UF_PRODUCTS_NEW'))->Fetch();
        if(!$arEntity) {
            $obUserField->Add(Array('ENTITY_ID'       => $sEntityID,
                                    'FIELD_NAME'      => 'UF_PRODUCTS_NEW',
                                    'MULTIPLE'        => 'Y',
                                    'USER_TYPE_ID'    => 'integer',
                                    'MANDATORY'       => 'N',
                                    'SHOW_FILTER'     => 'I',
                                    'SETTINGS'        => Array('DEFAULT_VALUE' => 0,
                                                               'MIN_VALUE'     => 0,),
                                    'EDIT_FORM_LABEL' => Array('ru' => 'Новинки',
                                                               'en' => 'New items',)));
        }

        $arEntity = CUserTypeEntity::GetList(Array(), Array('ENTITY_ID'  => $sEntityID,
                                                            'FIELD_NAME' => 'UF_PRODUCTS_BEST'))->Fetch();
        if(!$arEntity) {
            $obUserField->Add(Array('ENTITY_ID'       => $sEntityID,
                                    'FIELD_NAME'      => 'UF_PRODUCTS_BEST',
                                    'MULTIPLE'        => 'Y',
                                    'USER_TYPE_ID'    => 'integer',
                                    'MANDATORY'       => 'N',
                                    'SHOW_FILTER'     => 'I',
                                    'SETTINGS'        => Array('DEFAULT_VALUE' => 0,
                                                               'MIN_VALUE'     => 0,),
                                    'EDIT_FORM_LABEL' => Array('ru' => 'Лидеры продаж',
                                                               'en' => 'Bestsellers',)));
        }

        $arEntity = CUserTypeEntity::GetList(Array(), Array('ENTITY_ID'  => $sEntityID,
                                                            'FIELD_NAME' => 'UF_PRODUCTS_SPECIAL'))->Fetch();
        if(!$arEntity) {
            $obUserField->Add(Array('ENTITY_ID'       => $sEntityID,
                                    'FIELD_NAME'      => 'UF_PRODUCTS_SPECIAL',
                                    'MULTIPLE'        => 'Y',
                                    'USER_TYPE_ID'    => 'integer',
                                    'MANDATORY'       => 'N',
                                    'SHOW_FILTER'     => 'I',
                                    'SETTINGS'        => Array('DEFAULT_VALUE' => 0,
                                                               'MIN_VALUE'     => 0,),
                                    'EDIT_FORM_LABEL' => Array('ru' => 'Спецпредложения',
                                                               'en' => 'Special offers',)));
        }
    }

    /**
     * Сортирует сгенерированные свойства по алфавиту
     *
     * @param $iblockId
     */
    private function sortCatalogProperties($iblockId) {
        $rsProps = CIBlockProperty::GetList(Array('name' => 'asc'), Array('IBLOCK_ID' => $iblockId));
        $sort = 2000;
        $sort2 = 10;
        $objProperty = new CIBlockProperty();
        while($arProp = $rsProps->Fetch()) {
            if($arProp['SORT'] >= 2000) {
                $objProperty->Update($arProp['ID'], Array('SORT'         => $sort,
                                                          'SMART_FILTER' => 'Y',
                                                          'SEARCHABLE'   => 'Y',));
                $sort += 10;
            }
            else {
                $objProperty->Update($arProp['ID'], Array('SORT' => $sort2));
                $sort2 += 10;
            }
        }

    }

    private function log($mess, $bResetLog = false) {
        //echo "{$mess}\n";
        if($bResetLog) {
            @file_put_contents($this->logPath, $mess."\n");
        }
        else {
            @file_put_contents($this->logPath, $mess."\n", FILE_APPEND);
        }
    }

    private function getRemoteFile($relativeUrl) {
        $tmpDir = $_SERVER['DOCUMENT_ROOT'].'/upload/import';

        $relativeUrl = trim($relativeUrl);
        $relativeUrl = str_replace('\\', '/', $relativeUrl);
        $baseUrl = 'http://test.texenergo.ru/SpecOfGoods';
        $fullUrl = $baseUrl.'/'.$relativeUrl;
        $newFilePath = $tmpDir.'/'.$relativeUrl;

        if(file_exists($newFilePath)) {
            return realpath($newFilePath);
        }

        @mkdir(dirname($newFilePath), 0775, true);
        $content = @file_get_contents($fullUrl);
        if(strlen($content) > 0) {
            file_put_contents($newFilePath, $content);

            return realpath($newFilePath);
        }
        else {
            //            $this->log($fullUrl . ' not found');
        }

        return false;
    }

    /**
     * Функция проверяет, добавлялся ли файл в базу ранее
     *
     * Если файл уже есть, возвращается его ID
     * Если файла нет, то он сохраняется в папку upload/catalog и добавляется в БД
     *
     * За уникальный ключ берется имя файла, которое формируется как [имя_папки]_[имя_файла]
     *
     * Например для файла armaturakabel/emis.gif новое имя будет armaturakabel_emis.gif
     *
     * Таким образом при повторном импорте одинаковых файлов не расходуется дисковое пространство
     *
     * @param $sFilePath
     *
     * @return bool|int ID файла в таблице b_file или false, если файл не найден
     */
    private function getFileID($sFilePath) {
        if(!file_exists($sFilePath)) {
            return false;
        }

        $sOriginalName = basename(dirname($sFilePath)).'_'.basename($sFilePath);

        $arFile = CFile::GetList(Array(), Array('ORIGINAL_NAME' => $sOriginalName))->Fetch();
        if($arFile) {
            $fileID = $arFile['ID'];
        }
        else {
            $arFile = CFile::MakeFileArray($sFilePath);
            $arFile['name'] = $sOriginalName;
            $fileID = CFile::SaveFile($arFile, 'catalog');
        }

        return IntVal($fileID);
    }

}

?>