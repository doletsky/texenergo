<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$siteData = CSite::GetByID(SITE_ID)->Fetch();

//delayed function must return a string
if (empty($arResult))
	return "";
// предотвращаем дублирование разделов
$prevLink = "";

$strReturn = '<nav class="crumbs"  itemscope itemtype="http://schema.org/BreadcrumbList"><div class="container"><div class="twelve"><ul>';
$strReturn .= '<li><a href="' . SITE_DIR . '" title="' . $siteData["NAME"] . '"><b>' . $siteData["NAME"] . '</b></a></li>';

for ($index = 0, $itemSize = count($arResult); $index < $itemSize; $index++) {
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);
	$position = $index + 1;
	if ($arResult[$index]["LINK"] <> ""){
		if(strlen($prevLink) > 0 && $prevLink == $arResult[$index]["LINK"]) {
			continue;
		}
		if ($arResult[$index]["LINK"] == "#" || $index == $itemSize - 1){
			$strReturn .= '<li><span class="big">' . $title . '</span></li>';
		}else{
			$strReturn .= '<li itemprop="itemListElement" itemscope
      itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . $arResult[$index]["LINK"] . '" title="' . $title . '"><span itemprop="name">' . $title . '</span></a><meta itemprop="position" content="'.$position.'" /></li>';
		}
		$prevLink = $arResult[$index]["LINK"];
	}
}

$strReturn .= '</ul></div></div></nav>';
return $strReturn;
