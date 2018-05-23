<?php
require_once 'mobile_detect/Mobile_Detect.php';

//$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
// echo $deviceType;
    $detect = new Mobile_Detect;
    $detectDeviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer'); 
/**
 * Проверка мобильного устройства
 * @$deviceType  "tablet", "phone", "computer"
 * @return bool true
 */
function isMobile($deviceType) {
    global $detectDeviceType;
    if (isset($deviceType)) 
    return (($deviceType === $detectDeviceType) ? true : false);
    else 
       return ((($detectDeviceType == 'phone') or ($detectDeviceType == 'tablet')) ? true : false);    
}
/**
 * Проверка на адаптивность макета
 * @return bool true
 */
function isResponsive() {
    $ResponsiveLayout = $_SERVER["REQUEST_URI"];
    $arResponsiveLayouts = array (
        "/",         // главная страница
        "/catalog/"   // каталог товаров
        );
    if (in_array($ResponsiveLayout, $arResponsiveLayouts)) return true;
    else return false;
}
?>
