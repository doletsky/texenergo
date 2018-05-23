<?
define("STOP_STATISTICS", true);
define("NO_AGENT_CHECK", true);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?


CModule::IncludeModule('iblock');

$arResult = Array(
    'type' => 'FeatureCollection',
    'crs' => Array('type' => 'name', 'properties' => Array('name' => 'urn:ogc:def:crs:OGC:1.3:CRS84')),
    'features' => Array(),
);

$rsZones = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => IBLOCK_ID_DELIVERY_ZONES), false, false, Array('ID', 'NAME', 'PROPERTY_COORDS', 'PROPERTY_COLOR'));

while ($arZone = $rsZones->Fetch()) {
    $arCoords = $arZone['PROPERTY_COORDS_VALUE'];

    $sColor = $arZone['PROPERTY_COLOR_VALUE'];
    if (empty($sColor)) $sColor = "#ff7800";

    foreach ($arCoords as $sCoords) {

        $arItem = Array(
            'type' => 'Feature',
            'properties' => Array(
                'Id' => $arZone['ID'],
                'Name' => $arZone['NAME'],
                'Description' => '',
            ),
            'geometry' => Array(
                'type' => 'Polygon',
                'coordinates' => Array(),
            ),
            'style' => Array(
                'fill' => $sColor,
                'stroke' => 3,
                'opacity' => 0.5
            ),
        );

        $arRows = explode("\n", $sCoords);
        $arPoints = Array();
        foreach ($arRows as $sRow) {
            $arRow = explode(",", trim($sRow));
            if (count($arRow) == 3) {
                $arPoints[] = Array(
                    DoubleVal($arRow[0]), DoubleVal($arRow[1]), DoubleVal($arRow[2])
                );
            }
        }
        $arItem['geometry']['coordinates'][] = $arPoints;
        $arResult['features'][] = $arItem;
    }
}

header("Content-type: application/json");
echo json_encode($arResult);