<?use \Bitrix\Main\Loader,
    \Bitrix\Iblock\IblockTable,
    \Bitrix\Iblock\ElementTable;

setlocale(LC_ALL, 'ru.UTF-8');
set_time_limit(0);

$_SERVER['DOCUMENT_ROOT'] = realpath(dirname(__FILE__).'/../..');
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];

define('DOCUMENT_ROOT', $DOCUMENT_ROOT);
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('NO_AGENT_CHECK', true);

require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

Loader::includeModule('iblock');

$arCodes = array();

$file = $_SERVER['DOCUMENT_ROOT'].'/upload/code_GUID.txt';
$rsFile = fopen($file, 'r');
if (!$rsFile)
    exit;

while ($arRow = fgetcsv($rsFile, null, ',')) {
    $oldXmlID = trim($arRow[0]);
    $newXmlID = trim($arRow[1]);

    $arCodes[$oldXmlID] = $newXmlID;
}

fclose($rsFile);
if (count($arCodes) == 0)
    exit;

$arParams = array(
    'filter' => array('=CODE' => 'catalog_test', '=IBLOCK_TYPE_ID' => 'catalog'),
    'select' => array('ID')
);

$obIBlock = IblockTable::getList($arParams);
$arIBlock = $obIBlock->fetch();
if (!$arIBlock)
    exit;

$arParams = array(
    'filter' => array('=IBLOCK_ID' => $arIBlock['ID']),
    'select' => array('ID', 'XML_ID')
);

$obElementEntity = new CIBlockElement();
$obElements = ElementTable::getList($arParams);

while ($arElement = $obElements->fetch()) {
    $id = $arElement['ID'];
    $xmlID = trim($arElement['XML_ID']);

    if (isset($arCodes[$xmlID]))
        $obElementEntity->Update($id, array('XML_ID' => $arCodes[$xmlID]));
}