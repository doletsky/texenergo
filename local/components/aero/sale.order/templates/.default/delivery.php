<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="optionsWrapper"
     id="deliveryForm"<? if ($arResult['PROFILE']['ID'] > 0): ?> style="display: none;"<? endif; ?>>
    <header class="blockHeader">
        
		<?if($arParams['ONLY_CALCULATOR'] == 'Y'):?>
			<h1>Расчет стоимости доставки</h1>		
		<?else:?>		
			<h1>Выбор способа доставки</h1>		
		<?endif;?>
		
    </header>
    <section class="shippingSelectors clearfix orderTabs">
        <ul>
            <? foreach ($arResult['DELIVERY'] as $arDelivery): ?>
                <li class="<? if ($arDelivery['SELECTED'] == 'Y'): ?> preactive<? endif; ?>">
                    <a href="#group_<?= $arDelivery['SID']; ?>"
                       class="shippingSelector shippingSelector-oneline">
                        <?= $arDelivery['NAME']; ?>
                    </a>
                </li>
            <? endforeach; ?>
            <li class="agree_manager">
                <a href="#group_later" class="shippingSelector shippingSelector-twolines no-margin">
                    согласовать с менеджером
                </a>
            </li>
        </ul>

        <? foreach ($arResult['DELIVERY'] as $arDelivery): ?>
            <div id="group_<?= $arDelivery['SID']; ?>">
                <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery_" . $arDelivery['SID'] . ".php"); ?>
            </div>
        <? endforeach; ?>

        <div id="group_later">
            <form method="POST" name="order_later_form" id="order_later_form">
                <?= bitrix_sessid_post() ?>
                <input type="hidden" name="create_order" value="yes">
                <input type="hidden" name="PROFILE_ID" id="profile_id" value="<?= $arResult['PROFILE_ID']; ?>">

                <footer class="order-buttons clearfix">
                    <a href="/basket/" class="button prev">вернуться в корзину</a>
                    <button class="button orange next">перейти к оплате</button>
                </footer>
            </form>
        </div>
		
		<?/* if($arParams["ONLY_CALCULATOR"] == "Y"):?>
			<footer class="calc-buttons clearfix">			
				<div class="calc_total_wrap"><span class="calc_total_text">Итого:</span><span id="delivery_calc_result"><span>0</span><i class="rouble">a</i></span></div>
				<button class="button orange calc_delivery_price">Расчитать</button>				
			</footer>
		<?endif; */?>	

    </section>

</div>