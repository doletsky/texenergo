<?
error_reporting(0);
setlocale(LC_ALL, 'ru.UTF-8');
if (!isset($_SERVER["DOCUMENT_ROOT"]) || empty($_SERVER["DOCUMENT_ROOT"]))
    $_SERVER["DOCUMENT_ROOT"] = dirname(__FILE__) . '/../../../..';

$DOCUMENT_ROOT = $_SERVER["DOCUMENT_ROOT"];

define('DOCUMENT_ROOT', $DOCUMENT_ROOT);
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("NO_AGENT_CHECK", true);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
set_time_limit(0);

if (CModule::IncludeModule('aero.import')) {
    CAeroImport::ImportCatalog(false);
}
