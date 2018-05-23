<?
define("NO_KEEP_STATISTIC", true);
define('NO_AGENT_CHECK', true);
define("NO_AGENT_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define('DisableEventsCheck', true);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if (check_bitrix_sessid())
{
	
	$APPLICATION->IncludeComponent(
		"bitrix:catalog.comments",
		"product",
		array(                			
			"IS_AJAX" => true,
			"ELEMENT_ID" => $_REQUEST['ELEMENT_ID'],
			"ELEMENT_CODE" => "",
			"IBLOCK_ID" => $_REQUEST['IBLOCK_ID'],
			"URL_TO_COMMENT" => "",
			"WIDTH" => "",
			"COMMENTS_COUNT" => "1000",
			"BLOG_USE" => "Y",
			"FB_USE" => "N",
			"FB_APP_ID" => "",
			"VK_USE" => "N",
			"VK_API_ID" => "",
			"CACHE_TYPE" => "A",
			"CACHE_TIME" => "36000000",
			"BLOG_TITLE" => "",
			"BLOG_URL" => "",
			"PATH_TO_SMILE" => "/bitrix/images/blog/smile/",
			"EMAIL_NOTIFY" => "N",
			"AJAX_POST" => "Y",
			"SHOW_SPAM" => "Y",
			"SHOW_RATING" => "Y",
			"FB_TITLE" => "",
			"FB_USER_ADMIN_ID" => "",
			"FB_APP_ID" => "",
			"FB_COLORSCHEME" => "light",
			"FB_ORDER_BY" => "reverse_time",
			"VK_TITLE" => "",
			//"GOODS_RATE" => isset($arResult['PROPERTIES']['RATING']) ? $arResult['PROPERTIES']['RATING'] : ''
		),		
		array("HIDE_ICONS" => "Y")
	);
}

die();
?>