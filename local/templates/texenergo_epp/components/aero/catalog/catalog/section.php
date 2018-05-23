<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?
/**
 * Текущий раздел. Выбирается для того, чтобы определить на каком уровне каталога мы находимся
 *
 * В зависимости от типа раздела подключается определенный файл с компонентами:
 * section_products.php - список товаров - подключается, если раздел содержит товары
 * section_featured.php - новинки и спецпредложения - подключается на первом уровне и если в разделе нет товаров
 *
 */
$arSection = CIBlockSection::GetList(Array(), Array(
    'ACTIVE' => 'Y',
    'IBLOCK_ID' => $arParams['IBLOCK_ID'],
    //'ID' => $arResult['VARIABLES']['SECTION_ID'],
    'CODE' => $arResult['VARIABLES']['SECTION_CODE'],
    'ELEMENT_SUBSECTIONS' => 'N',
), true, Array('UF_*'))->GetNext();

?>

<?
/**
 * Мета для соц. сетей
 */

$arResult['OG_DESCRIPTION'] = '';

if($arSection['PICTURE'])
	$arResult['OG_PHOTO'] = CFile::GetPath($arSection['PICTURE']);
else
	$arResult['OG_PHOTO'] = SITE_TEMPLATE_PATH."/img/header/logo.png";

$APPLICATION->AddHeadString('<meta property="og:type" content="website" />');
$APPLICATION->AddHeadString('<meta property="og:locale" content="ru_RU" />');
$APPLICATION->AddHeadString('<meta property="og:title" content="'.$arSection['NAME'].'"/>');
$APPLICATION->AddHeadString('<meta property="og:url" content="http://'.SITE_SERVER_NAME.$APPLICATION->GetCurPage().'"/>');
$APPLICATION->AddHeadString('<meta property="og:description" content="'.$arSection['NAME'].'" />');
$APPLICATION->AddHeadString('<meta property="og:image" content="'.$arResult['OG_PHOTO'].'" />');
?>

<input type='checkbox' name='texenergoVendor' id='texenergoVendor' class='catalog-input-hide j-texenergo-vendor'
       <? if ($arResult['FILTER_BY_VENDOR']): ?>checked='checked'<? endif; ?>>

<div class="group-switch group-switch-catalog own-production-inside">
    <div class="subSwitch j_catalog_view <? if ($arResult['FILTER_BY_VENDOR']): ?>subSwitch-on<? endif; ?>"
         data-param='texenergoVendor'></div>
    <span>Только товары  TEXENERGO</span>
</div>


<div class="twelve">
    <h1 class="headline inside-headline">
        <?//= $arSection['NAME']; ?>
        <? $APPLICATION->ShowTitle(false) ?>
        <? if ($arResult['FILTER_BY_VENDOR']): ?>
            <? if ($arResult['COUNT_BY_VENDOR'] > 0): ?>
                <small class="counter"><?= $arResult['COUNT_BY_VENDOR']; ?></small>
            <? endif; ?>
        <?else:?>
            <? if ($arSection['UF_ELEMENT_CNT'] > 0): ?>
                <small class="counter"><?= $arSection['UF_ELEMENT_CNT']; ?></small>
            <? endif; ?>
        <? endif; ?>
    </h1>


    <aside class="share">
        <ul style="display:none;">
            <?/*  if ($USER->isAuthorized()): ?>
                <li>
                    <? if ($arResult['USER_IS_SUBSCRIBED']): ?>
                        <a href="#" class="active j-delete-from-goods-subscribe" title="Отписаться от рассылки"><img
                                src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/rss.png"
                                alt="Отписаться от рассылки"></a>
                    <? else: ?>
                        <a href="#" class="j-add-to-goods-subscribe" title="Подписаться на рассылку"><img
                                src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/rss.png" alt="Подписаться на рассылку"></a>
                    <? endif; ?>
                </li>
            <? else: ?>
                <li>
                    <a href="#" title="Подписаться на рассылку"><img
                            src="<?= SITE_TEMPLATE_PATH; ?>/img/catalog/rss.png" alt="Подписаться на рассылку"></a>
                </li>
            <?endif; */ ?>

            <li>
				<a href="#" class="" id="product-share-trigger" title="Поделиться товаром"></a>
			</li>
        </ul>

		<div class="share-block" id="share_block">
			<a class="share-social share-fb"
				onclick="Share.facebook(window.location.href, '<?=$arResult['NAME']?>', '<?=$arResult['OG_PHOTO']?>', '<?=$arResult['OG_DESCRIPTION']?>')">
			</a>
			<a class="share-social share-vk"
				onclick="Share.vkontakte(window.location.href, '<?=$arResult['NAME']?>', '<?=$arResult['OG_PHOTO']?>', '<?=$arResult['OG_DESCRIPTION']?>')">
			</a>
			<a class="share-social share-tw"
				onclick="Share.twitter(window.location.href, '<?=$arResult['NAME']?>')">
			</a>
			<a class="share-social share-od"
				onclick="Share.odnoklassniki(window.location.href, '')">
			</a>
		</div>

    </aside>

</div>
<?
/**
 * Показывать товары только производства Техэнерго
 */
if ($arResult['FILTER_BY_VENDOR']) {
    global $f;
    $f['PROPERTY_IS_OWN_VALUE'] = 'Y';
}?>

<div class="three three-nomargin">
    <nav class="sidebar sidebar-catalog">
        <? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/sidebar.php'; ?>
    </nav>
</div>
<div class="nine nine-nomargin">
    <div class="catalog">

        <nav class="sort">
            <span class="text">Сортировка:</span>
            <select class="j-catalog-sort-id j-sort-select product-sort-top">
                <? foreach ($arResult['SORT_FIELDS'] as $arField): ?>
                    <option
                        value="<?= $arField['CODE']; ?>" <?= ($arResult['SORT_FIELD'] == $arField['CODE'] ? 'selected' : ''); ?>>
                        <?= $arField['NAME']; ?>
                    </option>
                <? endforeach; ?>
            </select>

            <span class="text">Отображение товаров:</span>
            <select class="j-catalog-view-id j-goods-full-view j-sort-select product-sort-top">
                <? foreach ($arResult['CATALOG_SECTION_VIEWS'] as $sVal => $sView): ?>
                    <option
                        value="<?= $sVal; ?>" <?= ($arResult['CATALOG_SECTION_VIEW'] == $sVal ? 'selected' : ''); ?>>
                        <?= $sView; ?>
                    </option>
                <? endforeach; ?>
            </select>

        </nav>
<? /* Акции в категориях товаров */
if (isset($GLOBALS['SEO']['CATALOG_SECTION_CODE'])) { 
	$file_shares = $_SERVER['DOCUMENT_ROOT'].'/upload/shares/catalog/categories/'.$GLOBALS['SEO']['CATALOG_SECTION_CODE'].'.html';
	if (file_exists($file_shares)) {
		echo '<div class="shares-text">';
		include($file_shares); 
		echo '</div>';
	}
}
?>
        <?
        $GLOBALS['arCatalogBrandFilter'] = Array(
            'PROPERTY_VENDOR_POPULAR_ON_VALUE' => 'Y',
        );
        ?>
        <?/* $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "brands",
            Array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "N",
                "AJAX_MODE" => "N",
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => IBLOCK_ID_BRANDS,
                "NEWS_COUNT" => "10000",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "SORT",
                "SORT_ORDER2" => "ASC",
                "FILTER_NAME" => "arCatalogBrandFilter",
                "FIELD_CODE" => array(),
                "PROPERTY_CODE" => array("VENDOR_POPULAR_ON"),
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "SET_TITLE" => "N",
                "SET_STATUS_404" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_FILTER" => "N",
                "CACHE_GROUPS" => "Y",
                "PAGER_TEMPLATE" => ".default",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "",
                "PAGER_SHOW_ALWAYS" => "Y",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "Y",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N"
            ),
            $component
        );*/?>

        <?
		if ($arSection['ELEMENT_CNT'] <= 0 && $_REQUEST['set_filter'] !== 'y'): ?>
            <? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/section_featured.php'; ?>
			<? define('INCLUDE_SUBSECTIONS', true); ?>
			<? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/section_products.php'; ?>
        <? else: ?>
            <? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/section_products.php'; ?>
        <?endif; ?>


    </div>
</div>