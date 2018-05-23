<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

IncludeTemplateLangFile(__FILE__);
$code = trim($arParams["CODE"]);
$scale = !empty($arParams["SCALE"]) ? trim($arParams["SCALE"]) : "2";
$mode = !empty($arParams["MODE"]) ? trim($arParams["MODE"]) : "png";
$className = !empty($arParams["CLASS"]) ? " class='".trim($arParams["CLASS"])."' " : "";
$id = !empty($arParams["ID"]) ? " id='".trim($arParams["ID"])."' " : "";
//$code ="41070017";
//pr($code);
//$strL = strlen($code);
//pr("Длина штрих-кода:".$strL);
//if(!$code || strlen($code) != 8 || strlen($code) != 13 || !is_int((int)$code))
if(!$code  || (strlen($code) != 8 && strlen($code) != 13) || !is_int((int)$code))
{
    echo GetMessage("CODEROID_BARCODE_ENTER_CODE");
}
else
{
    $scales = array("1", "1.2", "2", "3", "4", "5", "6", "7");
    $modes = array("png", "jpeg", "gif", "jpg", "htm", "html", "txt", "text");

    $src = "/local/components/coderoid/barcode/include/barcode.php?code=".$code."&encoding=EAN";
    if(in_array($scale, $scales))
    {
        $src .= "&scale=".$scale;
    }
    if(in_array($mode, $modes))
    {
        $src .= "&mode=".$mode;
    }
    echo "<img src='".$src."' ".$id.$className." alt='Штрих-код: ".$code."'"." />";
}
