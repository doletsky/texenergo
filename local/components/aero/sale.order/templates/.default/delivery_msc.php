<?
/**
 * @var array $arDelivery поля обработчика доставки
 */

$APPLICATION->AddHeadString('<script src="//maps.api.2gis.ru/2.0/loader.js?pkg=full" data-id="dgLoader"></script>', true);
?>

<div class="orderTabs subTabs clearfix">
    <ul>
        <? foreach ($arDelivery['PROFILES'] as $sid => $arProfile): ?>
            
			<?if($arParams['ONLY_CALCULATOR'] == 'Y' & $sid == 'simple') $arProfile['SELECTED'] = 'Y';?>
			
			<li<? if ($arProfile['SELECTED'] == 'Y'): ?> class="preactive"<? endif; ?>>
                <a href="#msc-<?= $sid; ?>"
                   class="shippingSelector shippingSelector-oneline profile_<?=$sid?>">
                    <?= $arProfile['TITLE']; ?>
                    <span><?= $arProfile['DESCRIPTION']; ?></span>
                </a>
            </li>
        <? endforeach; ?>

    </ul>
    <? foreach ($arDelivery['PROFILES'] as $profileID => $arProfile): ?>
        <div class="delivery-msc" id="msc-<?= $profileID; ?>"
             data-price="<?= IntVal($arDelivery['CONFIG']['CONFIG']['price_zone_' . $profileID . '_default']['VALUE']); ?>"
             data-title="Москва и область (<?= $arProfile['TITLE']; ?>)"<? if ($arProfile['SELECTED'] != 'Y'): ?> style="display: none;"<? endif; ?>>
            <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery_msc_" . $profileID . ".php"); ?>
        </div>
    <? endforeach; ?>


</div>

<div id="zones" style="display: none;">

    <div class="map-zones" id="map_zones"></div>

    <div class="map-zones-form">
		<?
		$GLOBALS["APPLICATION"]->IncludeComponent(
			"aero:sale.ajax.locations",
			'popup',
			array(
				"AJAX_CALL" => "N",
				"CITY_INPUT_NAME" => 'PROFILE[LOCATION_DELIVERY_POPUP]',
				"ZIP_INPUT_NAME" => 'PROFILE[ZIP_DELIVERY]',
				"LOCATION_VALUE" => $arResult['PROPS_VALUES']['LOCATION_DELIVERY'],
				"ZIP_VALUE" => $arResult['PROPS_VALUES']['ZIP_DELIVERY'],
				"LOCATION_GROUP_ID" => 1,
				"ORDER_PROPS_ID" => 'LOCATION_DELIVERY',
				"ONCITYCHANGE" => "",
				"SIZE1" => 100,
				"INPUT_CLASS" => "formInput required",
			),
			null,
			array('HIDE_ICONS' => 'Y')
		);
		?>
		
        <div class="formRow clearfix">
            <div class="formParent clearfix">
                <div class="formChild form-50">
                    <span class="formLabel">Улица</span>
                    <input class="formInput" type="text" id="search_street"
                           value="">
                </div>
            </div>
            <div class="formParent no-margin clearfix">
                <div class="formChild form-10">
                    <span class="formLabel">Дом</span>
                    <input class="formInput required" type="text" id="search_house"
                           value="">
                </div>
                <div class="formChild form-10">
                    <span class="formLabel">Корп.</span>
                    <input class="formInput" type="text" id="search_housing"
                           value="">
                </div>
            </div>
        </div>
        <footer class="order-buttons clearfix">
            <button class="button orange">Сохранить</button>
        </footer>
    </div>

</div>