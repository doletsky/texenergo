<?php
$arResult['CATALOG_SECTION_VIEWS'] = Array('grid' => 'Кратко', 'list' => 'Списком', 'detail' => 'Подробно');
if (!in_array($_COOKIE['catalog_view'], array_keys($arResult['CATALOG_SECTION_VIEWS']))) {
    $_COOKIE['catalog_view'] = 'list';
}
$arResult['CATALOG_SECTION_VIEW'] = $_COOKIE['catalog_view'];