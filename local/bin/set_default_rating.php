<?use \Bitrix\Main\Loader;

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

$obElements = CIBlockElement::GetList(array(), array('IBLOCK_ID' => IBLOCK_ID_CATALOG), false, false,
    array('ID', 'PROPERTY_RAITING', 'PROPERTY_COMMENTS_CNT'));

while ($arElement = $obElements->Fetch()) {
    $count = intval($arElement['PROPERTY_COMMENTS_CNT']);
    $rating = round((intval($arElement['PROPERTY_RAITING_VALUE']) + 5) / ($count + 1));

    CIBlockElement::SetPropertyValues($arElement['ID'], IBLOCK_ID_CATALOG, $count, 'COMMENTS_CNT');
    CIBlockElement::SetPropertyValues($arElement['ID'], IBLOCK_ID_CATALOG, $rating, 'RAITING');
}