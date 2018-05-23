<?php


class CAeroImportCommon
{

    protected $IBLOCK_PRODUCTS;

    /**
     * @var string путь к разбираемому файлу
     */
    protected $filePath;

    /**
     * @var SimpleXMLElement объект с данными
     */
    protected $oXml;

    /**
     * @var CAeroIBlockTools объект для доступа к инфоблоку каталога
     */
    protected $catalog;

    protected $logPath;

    public function __construct($filePath)
    {
        CModule::IncludeModule('sale');
        CModule::IncludeModule('iblock');

        $moduleId = 'aero.import';

        $this->filePath = $filePath;
        $this->oXml = simplexml_load_file($this->filePath);

        $this->IBLOCK_PRODUCTS = COption::GetOptionInt($moduleId, 'iblock_catalog', 0);

        $this->catalog = new CAeroIBlockTools($this->IBLOCK_PRODUCTS);

        $logDir = dirname($filePath) . '/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $this->logPath = $logDir . '/import-' . date('Ymd') . '.log';

        $this->log("Импорт " . basename($this->filePath));

    }

    public function Import()
    {
        return false;
    }

    protected function getAbsoluteFilePath($relativeUrl)
    {

        $relativeUrl = trim($relativeUrl, '/');
        $relativeUrl = str_replace('\\', '/', $relativeUrl);
        $baseUrl = dirname($this->filePath);
        $fullUrl = $baseUrl . '/' . $relativeUrl;

        if (file_exists($fullUrl) && is_file($fullUrl)) return realpath($fullUrl);

        return false;
    }


    protected function parseBasketItems($oXml)
    {
        $arItems = Array();

        foreach ($oXml->product as $oItem) {
            $sXmlID = (string)$oItem->id;

            $arItem = Array(
                'ID' => '',
                'NAME' => (string)$oItem->name,
                'CODE' => (string)$oItem->code,
                'PRICE' => DoubleVal($oItem->price),
                'QUANTITY' => IntVal($oItem->quantity),
            );

            $arProduct = $this->catalog->getElementByXmlID($sXmlID, $this->IBLOCK_PRODUCTS);
            if ($arProduct) {
                $arItem['ID'] = $arProduct['ID'];
            }
            $arItems[] = $arItem;
        }

        return $arItems;
    }

    public function log($mess = '', $bResetLog = false)
    {
        if (is_array($mess) || is_object($mess)) {
            $mess = print_r($mess, true);
        }

        //echo "{$mess}\n";
        if ($bResetLog) {
            @file_put_contents($this->logPath, $mess . "\n");
        } else {
            @file_put_contents($this->logPath, $mess . "\n", FILE_APPEND);
        }
    }

}