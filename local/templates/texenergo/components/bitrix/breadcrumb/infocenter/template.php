<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$siteData = CSite::GetByID(SITE_ID)->Fetch();

//delayed function must return a string
if (empty($arResult))
	return "";
// предотвращаем дублирование разделов
$prevLink = "";

$strReturn = '<nav class="crumbs"><div class="container"><div class="twelve"><ul>';
$strReturn .= '<li><a href="/infocenter/" title="Информационный центр"><b>Информационный центр</b></a></li>';

for ($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++) {
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	if ($arResult[$index]["LINK"] <> ""){
		if(strlen($prevLink) > 0 && $prevLink == $arResult[$index]["LINK"]) {
			continue;
		}
		if ($arResult[$index]["LINK"] == "#" || $index == $itemSize - 1){
			$strReturn .= '<li><span class="big">' . $title . '</span></li>';
		}else{
			$strReturn .= '<li><a href="' . $arResult[$index]["LINK"] . '" title="' . $title . '">' . $title . '</a></li>';
		}
		$prevLink = $arResult[$index]["LINK"];
	}
}

$strReturn .= '</ul></div></div></nav>';
return $strReturn;