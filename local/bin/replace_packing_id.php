<?use \Bitrix\Main\Loader,
    \Bitrix\Iblock\IblockTable,
    \Bitrix\Iblock\PropertyTable,
    \Bitrix\Iblock\PropertyEnumerationTable;

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

$file = $_SERVER['DOCUMENT_ROOT'].'/upload/units_GUID.txt';
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
    'filter' => array('=IBLOCK_ID' => $arIBlock['ID'], '=CODE' => 'INDIVIDUALNAYAUPAKOV_PACKING'),
    'select' => array('ID')
);

$obProperty = PropertyTable::getList($arParams);
$arProperty = $obProperty->fetch();
if (!$arProperty)
    exit;

$arParams = array(
    'filter' => array('=PROPERTY_ID' => $arProperty['ID']),
    'select' => array('ID', 'XML_ID')
);

$obEnumEntity = new CIBlockPropertyEnum();
$obEnum  = PropertyEnumerationTable::getList($arParams);

while ($arEnum = $obEnum->fetch()) {
    $id = $arEnum['ID'];
    $xmlID = trim($arEnum['XML_ID']);

    if (isset($arCodes[$xmlID]))
        $obEnumEntity->Update($id, array('XML_ID' => $arCodes[$xmlID]));
}