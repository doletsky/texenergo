<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?if($_REQUEST['ajax_call'] == 'y'):?>
	<script>
	var oHead = document.getElementById('storlist');
	
	var oScript= document.createElement("script");
	oScript.type = "text/javascript";
	oScript.src="<?=$templateFolder?>/script.js";
	oHead.appendChild( oScript);
	
	var oScript= document.createElement("script");
	oScript.type = "text/javascript";
	oScript.src="//maps.api.2gis.ru/2.0/loader.js?pkg=full";
	oScript.setAttribute("data-id", "dgLoader");
	oHead.appendChild( oScript);
	
	var css = document.createElement("link")
	css.setAttribute("rel", "stylesheet")
	css.setAttribute("type", "text/css")
	css.setAttribute("href", '<?=$templateFolder?>/style.css')
	oHead.appendChild(css);
	</script>

<?else:?>

	<?$APPLICATION->AddHeadString('<script src="//maps.api.2gis.ru/2.0/loader.js?pkg=full" data-id="dgLoader"></script>', true);?>
	
<?endif;?>

<?$arResult['STORES'][0]['SELECTED'] = 'Y';?>

<? if (is_array($arResult["STORES"]) && !empty($arResult["STORES"])): ?>
    <div class="store-list" id="storlist">
        <? foreach ($arResult["STORES"] as $arStore): ?>
            <div class="store-info<? if ($arStore['SELECTED'] == 'Y'): ?> active<? endif; ?>"
                 data-lat="<?= $arStore['GPS_N']; ?>" data-lon="<?= $arStore['GPS_S']; ?>"
                 data-title="<?= $arStore['TITLE']; ?>">
                <h3><input type="radio"
                           name="store"<? if ($arStore['SELECTED'] == 'Y'): ?> checked<? endif; ?>> <?= $arStore["TITLE"] ?>
                </h3>
                <? if (isset($arStore["ADDRESS"])): ?>
                    <div class="address">
                        <?= $arStore["ADDRESS"] ?>
                    </div>
                <? endif; ?>
                <? if (isset($arStore["PHONE"])): ?>
                    <div class="phone">
                        Телефон:
                        <span><?= $arStore["PHONE"] ?></span>
                    </div>
                <? endif; ?>
                <? if (isset($arStore["SCHEDULE"])): ?>
                    <div class="schedule">
                        <span><?= $arStore["SCHEDULE"] ?></span>
                    </div>
                <? endif; ?>
            </div>
        <? endforeach; ?>
    </div>
<? endif; ?>

<div id="store_map" class="store-map"></div>
