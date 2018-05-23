<?
CModule::AddAutoloadClasses(
    "aero.import",
    array( // API classes
        "CAeroImportShipping" => "classes/general/CAeroImportShipping.php",
        "CAeroImportPayments" => "classes/general/CAeroImportPayments.php",
        "CAeroImportSales" => "classes/general/CAeroImportSales.php",
        "CAeroImportRefunds" => "classes/general/CAeroImportRefunds.php",
        "CAeroImportMessages" => "classes/general/CAeroImportMessages.php",
        "CAeroImportContracts" => "classes/general/CAeroImportContracts.php",
        "CAeroImportInvoices" => "classes/general/CAeroImportInvoices.php",
        "CAeroImportOrders" => "classes/general/CAeroImportOrders.php",
        "CAeroExportOrders" => "classes/general/CAeroExportOrders.php",
        "CAeroImportCatalog" => "classes/general/CAeroImportCatalog.php",
        "CAeroIBlockTools" => "classes/general/CAeroIBlockTools.php",
        "CAeroIblockProps" => "classes/general/CAeroIBlockProps.php",
        "CAeroImportUsers" => "classes/general/CAeroImportUsers.php",
        "CAeroClearImageCache" => "classes/general/CAeroClearImageCache.php",
    )
);

/**
 * Class CAeroImport
 */
Class CAeroImport
{

    /**
     * Агент для импорта каталога из xml
     * @param $bIgnoreLock bool
     * @return string
     */
    public static function ImportCatalog($bIgnoreLock = false)
    {
        // не позволяем агенту запускаться параллельно
        $lock = COption::GetOptionString('aero.import', 'agent_is_running', 'N');
        if ($lock == 'Y' && !$bIgnoreLock) {
            return 'CAeroImport::ImportCatalog();';
        }

        COption::SetOptionString('aero.import', 'agent_is_running', 'Y');

        $fileDir = trim(COption::GetOptionString('aero.import', 'data_file', '/upload/import/'), '/');
        $filePath = $fileDir . '/catTE.xml';

        $sDataFile = $_SERVER['DOCUMENT_ROOT'] . '/' . $filePath;
        if (file_exists($sDataFile) && is_file($sDataFile)) {
            $oImport = new CAeroImportCatalog($sDataFile);
            $oImport->import();
            //system('rm -r ' . $sDataFile);
            if(!is_dir($_SERVER['DOCUMENT_ROOT'] . '/' . $fileDir . '/success/')){
                mkdir($_SERVER['DOCUMENT_ROOT'] . '/' . $fileDir . '/success/', 0775, true);
            }
            rename($sDataFile, $_SERVER['DOCUMENT_ROOT'] . '/' . $fileDir . '/success/' . date('Ymd-His') . '-catTE.xml');
            //чистим кэш
            @BXClearCache(true);
            //если мемкэш
            $mc = memcache_connect('localhost', 11211);
            memcache_flush($mc);
        }

        COption::SetOptionString('aero.import', 'agent_is_running', 'N');


        return 'CAeroImport::ImportCatalog();';
    }

    /**
     * Агент для импорта заказов из xml
     * @return string
     */
    public static function ImportDocuments()
    {
        /*global $USER;

        if (!is_object($USER)) $USER = new CUser();

        if (!$USER->isAuthorized()) {
            CUser::Authorize(1);
        }*/

        $fileDir = $_SERVER['DOCUMENT_ROOT'] . '/' . trim(COption::GetOptionString('aero.import', 'data_file_docs', '/upload/docs/'), '/');

        if (is_dir($fileDir)) {

            $fp = opendir($fileDir);

            // порядок импорта задается последовательностью ключей
            $arFiles['AG'] = Array();
            $arFiles['BI'] = Array();
            $arFiles['PA'] = Array();
            $arFiles['RE'] = Array();
            $arFiles['SH'] = Array();
            $arFiles['ME'] = Array();
            $arFiles['UN'] = Array();

            while ($sFile = readdir($fp)) {
                if (is_file($fileDir . '/' . $sFile)) {

                    if (preg_match('/\-BI\-/', $sFile)) {
                        $arFiles['BI'][] = $sFile;
                    }

                    if (preg_match('/\-AG\-/', $sFile)) {
                        $arFiles['AG'][] = $sFile;
                    }

                    if (preg_match('/\-ME\-/', $sFile)) {
                        $arFiles['ME'][] = $sFile;
                    }

                    if (preg_match('/\-PA\-/', $sFile)) {
                        $arFiles['PA'][] = $sFile;
                    }

                    if (preg_match('/\-RE\-/', $sFile)) {
                        $arFiles['RE'][] = $sFile;
                    }

                    if (preg_match('/\-SH\-/', $sFile)) {
                        $arFiles['SH'][] = $sFile;
                    }

                    if (preg_match('/\-UN\-/', $sFile)) {
                        $arFiles['UN'][] = $sFile;
                    }
                }
            }

            closedir($fp);

            if (sizeof($arFiles) > 0) {

                foreach ($arFiles as $sType => $arPath) {

                    if (is_array($arPath) && sizeof($arPath) > 0) {

                        foreach ($arPath as $sFile) {
                            $obImport = null;

                            if ($sType == 'BI') $obImport = new CAeroImportInvoices($fileDir . '/' . $sFile);
                            if ($sType == 'AG') $obImport = new CAeroImportContracts($fileDir . '/' . $sFile);
                            if ($sType == 'ME') $obImport = new CAeroImportMessages($fileDir . '/' . $sFile);
                            if ($sType == 'PA') $obImport = new CAeroImportPayments($fileDir . '/' . $sFile);
                            if ($sType == 'RE') $obImport = new CAeroImportSales($fileDir . '/' . $sFile);
                            if ($sType == 'UN') $obImport = new CAeroImportRefunds($fileDir . '/' . $sFile);
                            if ($sType == 'SH') $obImport = new CAeroImportShipping($fileDir . '/' . $sFile);

                            if (!is_null($obImport)) {
                                if ($obImport->Import()) {
                                    if (!is_dir($fileDir . '/success/')) {
                                        $obImport->log("Create dir ".$fileDir . "/success/ \n");
                                        mkdir($fileDir . '/success/', 0775, true);
                                    }
                                    if (!rename($fileDir . '/' . $sFile, $fileDir . '/success/' . $sFile)) {
                                        $obImport->log("Delete file error {$fileDir}/{$sFile}\n");
                                        echo "Delete file error {$fileDir}/{$sFile}\n";
                                    }
                                } else {
                                    if (!is_dir($fileDir . '/errors/')) {
                                        $obImport->log("Create dir ".$fileDir . "/errors/ \n");
                                        mkdir($fileDir . '/errors/', 0775, true);
                                    }
                                    if (!rename($fileDir . '/' . $sFile, $fileDir . '/errors/' . $sFile)) {
                                        $obImport->log("Delete file error {$fileDir}/{$sFile}\n");
                                        echo "Delete file error {$fileDir}/{$sFile}\n";
                                    }
                                }
                            }

                        }

                    }

                }

            }

        }

        return 'CAeroImport::ImportDocuments();';
    }

    public static function ExportOrders()
    {
        global $DB;

        $iLastSendTime = COption::GetOptionInt("aero.import", "ORDERS_LAST_SEND_TIME", 0);

        $fileDir = $_SERVER['DOCUMENT_ROOT'] . '/' . trim(COption::GetOptionString('aero.import', 'data_file_orders', '/upload/orders/'), '/');


        CModule::IncludeModule('sale');

        $arFilter = Array(
            ">=DATE_UPDATE" => date($DB->DateFormatToPHP(CSite::GetDateFormat("FULL")), $iLastSendTime),
            "UPDATED_1C" => "N",
	    "@STATUS_ID" => array("N", "I", "P", "C","F"),
        );

        $rsOrders = CSaleOrder::GetList(Array("ID" => "ASC"), $arFilter, false, false, Array('ID', 'PAY_SYSTEM_ID', 'PS_STATUS', 'STATUS_ID'));

        while ($arOrder = $rsOrders->Fetch()) {
            $obOrder = new CAeroExportOrders($arOrder['ID'], $fileDir);
            $obOrder->Export();
        }

        COption::SetOptionInt("aero.import", "ORDERS_LAST_SEND_TIME", time());

        return "CAeroImport::ExportOrders();";
    }

    public static function ExportUsers()
    {
        $iLastSendTime = COption::GetOptionInt("aero.import", "USERS_LAST_SEND_TIME", 0);

        if ($iLastSendTime == "") {
            $iLastSendTime = "11.11.2014 13:33:17";
        }

        $import  = new CAeroImportUsers();
        $import->Import($iLastSendTime);

        COption::SetOptionInt("aero.import", "USERS_LAST_SEND_TIME", date("d.m.Y H:i:s"));

        return "CAeroImport::ExportUsers();";
    }

    /**
     * Агент для очистки кэша изображений
     * @return string
     */
    public static function ClearImageCache($bIgnoreLock = false)
    {
        // не позволяем агенту запускаться параллельно
        $lock = COption::GetOptionString('aero.import', 'cache_agent_is_running', 'N');
        if ($lock == 'Y' && !$bIgnoreLock) {
            return 'CAeroImport::ClearImageCache();';
        }

        COption::SetOptionString('aero.import', 'cache_agent_is_running', 'Y');

        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/upload/restrict/photos/log.txt';
        $oImport = new CAeroClearImageCache($filePath);
        if($oImport->import()) {
            COption::SetOptionInt('aero.import', 'clear_image_cache_datetime', time());
        }


        COption::SetOptionString('aero.import', 'cache_agent_is_running', 'N');


        return 'CAeroImport::ClearImageCache();';
    }

}

?>