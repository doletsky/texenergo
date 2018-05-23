<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
    $bisTrustedUser = isTrustedUser(); // пользователю разрешено показ остатков
?>
<aside class="share">
    <ul  style="display:none;">
        <? if ($USER->isAuthorized()): ?>

            <?$arResult['TRACKING_ON'] = CProductTracking::isTrackingOn($USER->GetID(), $arResult['ID']);?>

            <?
            $class = '';
            $title = 'Отслеживать товар';
            if($arResult['TRACKING_ON']){
                $class = 'active';
            }
            ?>

            <li class="no-dropdown">
                <a href="#" class="<?=$class?> track-trigger" id="product-track-trigger" title="Следить за товаром" data-product="<?=$arResult['ID']?>" data-userid="<?=$USER->GetID()?>">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/rss.png" alt="Следить за товаром" style="display:none;">
                <img src="<?=SITE_TEMPLATE_PATH?>/img/catalog/rss_active.png" alt="Активная подписка на товар" style="display:none;">
                </a>
            </li>

        <?endif; ?>

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


<? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/sidebar.php'; ?>
<div class="pContent">

    <div class="tabContainer" id="tabContainer">

        <ul>
            <li><a class="tMain" href="#tMain">Обзор</a></li>

            <? if (!empty($arResult['PROPERTIES']['SERIES']['VALUE'])): ?>
                <li><a class="tLine" href="#tLine">Вся серия</a></li>
            <? endif; ?>

            <? if (!empty($arResult['PROPERTIES']['ACCESSORIES']['VALUE'])): ?>
            <li><a class="tAnalogs" href="#tAnalogs">Аксессуары</a></li>
            <? endif; ?>


        </ul>


        <div class="tMainSection clearfix active" id="tMain">
            <? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/tab_main.php'; ?>
        </div>

        <? if (!empty($arResult['PROPERTIES']['SERIES']['VALUE'])): ?>
            <section class="tLineSection clearfix" id="tLine">
                <? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/tab_series.php'; ?>
            </section>
        <? endif; ?>

        <? if (!empty($arResult["PROPERTIES"]["ACCESSORIES"]["VALUE"])): ?>
        <section class="tAnalogsSection clearfix" id="tAnalogs">
            <? include $_SERVER['DOCUMENT_ROOT'] . $templateFolder . '/tab_accessories.php'; ?>
        </section>
        <? endif; ?>



    </div>
</div>
<? //= '<pre>' . print_r($arResult, true) . '</pre>'; ?>