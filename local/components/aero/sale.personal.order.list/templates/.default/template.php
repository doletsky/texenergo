<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<section class="main main-account main-floated">
<aside class="accountFilter">
    <div class="b-filterStatus">
        <label>показывать:</label>
        <select class="j-select">
            <optgroup label="">
                <option value="<?= $APPLICATION->GetCurPageParam('', Array('status', 'real')); ?>">все статусы</option>
            </optgroup>
            <optgroup label="--------------------------------------------------------">
                <option value="<?= $APPLICATION->GetCurPageParam('status=I', Array('status', 'real')); ?>" <?= ($arResult['FILTER_STATUS'] == 'I' ? 'selected' : ''); ?>>
                    Новые счета
                </option>
                <option value="<?= $APPLICATION->GetCurPageParam('status=P', Array('status', 'real')); ?>" <?= ($arResult['FILTER_STATUS'] == 'P' ? 'selected' : ''); ?>>
                    Частично оплаченные
                </option>
                <option value="<?= $APPLICATION->GetCurPageParam('status=C', Array('status', 'real')); ?>" <?= ($arResult['FILTER_STATUS'] == 'C' ? 'selected' : ''); ?>>
                    Оплаченные счета
                </option>
            </optgroup>

            <?if (count($arResult["REALIZATION_STATUS"]) > 0): ?>
                <?
                $currentStatus = $arResult["FILTER_STATUS"];
                foreach($arResult["REALIZATION_STATUS"] as $group){?>
                    <?if (count($group) > 0): ?>
                    <optgroup label="--------------------------------------------------------">
                        <? foreach ($group as $ind => $arStatus): ?>
                            <?
                            $statusCode = $arStatus["UF_XML_ID"];
                            $url = $APPLICATION->GetCurPageParam("status={$statusCode}&real=Y", array("status", "real"))
                            ?>
                            <option<?=$ind == 0 ? ' class="sep"' : ''?><?=$currentStatus == $statusCode ? ' selected' : ''?> value="<?=$url?>"><?=$arStatus["UF_NAME"]?>
                            </option>
                        <? endforeach; ?>
                    </optgroup>
                    <? endif; ?>
                <?}?>
            <? endif; ?>

        </select>
    </div>
    <div class="b-filterPeriod">
        <label>за период:</label>
        <em><?= $arResult['FILTER_DATE_FROM']; ?> - <?= $arResult['FILTER_DATE_TO']; ?></em>

        <div class="filter-dates" id="filter_dates" style="display: none;">
            <form action="<?= $APPLICATION->GetCurPageParam('', Array('date_from', 'date_to')); ?>" method="GET">
                <input type="hidden" name="date_from" id="orders_date_from"
                       value="<?= $arResult['FILTER_DATE_FROM']; ?>">
                <input type="hidden" name="date_to" id="orders_date_to" value="<?= $arResult['FILTER_DATE_TO']; ?>">
                <input type="hidden" name="status" value="<?= $arResult['FILTER_STATUS']; ?>">

                <div class="calendar"></div>
                <button class="button orange">Показать</button>
            </form>
        </div>
    </div>
</aside>
<? if (!empty($arResult["ORDERS"])): ?>
    <? foreach ($arResult["ORDERS"] as $arOrderData): ?>
        <?
        $arOrder = $arOrderData['ORDER'];
        $arBasket = $arOrderData['BASKET_ITEMS'];
        ?>
        <? //= '<pre>' . print_r($arOrder, true) . '</pre>'; ?>
        <section class="orderItem">
        <header>
            <h1>
                <a href="<?= $arOrder['URL_TO_DETAIL']; ?>" class="popup" data-width="700">
                    <span class="orderName">Заказ №<?= $arOrder['ACCOUNT_NUMBER']; ?></span>
                    <span class="orderDate"> от <?= $arOrder['DATE_INSERT_FORMAT']; ?></span>
                </a>
            </h1>

            <? /*<div class="b-switchDocuments right">
                    <div class="switch-socials switchSocials-order"></div>
                    <span>Документы</span>
                </div>*/
            ?>

			<a class="order-total-products popup" href="/personal/orders/?ID=<?=$arOrder['ID']?>" class="popup" data-width="700">
				<?= count($arBasket); ?> <?= strMorph(count($arBasket), 'товар', 'товара', 'товаров'); ?>
			</a>

            &nbsp;<a href="/personal/orders/docs/print/?form=order&orderId=<?=$arOrder["ACCOUNT_NUMBER"]?>&mode=html" target="_blank" class="button">Распечатать заказ</a>
        </header>
        <section class="accountFinancials">
            <div class="b-financialsRow clearfix">
                <span>Общая сумма</span>

                <div class="orderBar right">
                    <div class="orderFill fill-100">
                        <span><?=priceFormat($arOrder['PRICE']); ?><i class="rouble">a</i></span>
                    </div>
                </div>
            </div>
            <div class="b-financialsRow clearfix">
                <span>Оплачено</span>

                <div class="orderBar right">
                    <div class="orderFill"
                         style="width:<?= round($arOrder['PAYED'] / $arOrder['PRICE'] * 100); ?>%;">
                        <span><?=priceFormat($arOrder['PAYED']); ?><i class="rouble">a</i></span></div>
                </div>
            </div>
            <div class="b-financialsRow clearfix">
                <span>Отгружено</span>

                <div class="orderBar right">
                    <div class="orderFill"
                         style="width:<?= round($arOrder['SOLD'] / $arOrder['PRICE'] * 100); ?>%;">
                        <span><?=priceFormat($arOrder['SOLD']); ?><i class="rouble">a</i></span>
                    </div>
                </div>
            </div>
        </section>

		<?if($arOrder['CANCELED'] == 'Y'):?>

			 <article class="orderCanceled">
				Заявка <?=$arOrder['ID']?> отменена.
				<?if(!empty($arOrder['REASON_CANCELED'])):?>
					Причина: <?=$arOrder['REASON_CANCELED']?>
				<?endif;?>
			 </article>

			 <?continue;?>

		<?endif;?>

        <? foreach ($arOrder['INVOICES'] as $arInvoice): ?>
            <article class="accountInvoice">
            <div class="b-invoiceHead">
                <div
                    class="b-invoiceHeadline<? if (empty($arInvoice['SALES']) && empty($arInvoice['PAYMENTS'])): ?> empty-invoice<? endif; ?>">
                    <h1>
                        <?= $arInvoice['NAME']; ?> на сумму <b><?= priceFormat($arInvoice['AMOUNT']); ?><i
                                class="rouble">a</i></b>
                        <a href="#invoice_<?= $arInvoice['ID']; ?>" class="popup" data-width="700">
                            <?= count($arInvoice['BASKET_ITEMS']); ?> <?= strMorph(count($arInvoice['BASKET_ITEMS']), 'товар', 'товара', 'товаров'); ?>
                        </a>
                    </h1>
                    <span>от <?= $arInvoice['DATE']; ?></span>
                </div>

				<?
				/* if(intval($arInvoice['AMOUNT'] - $arInvoice['PAYED']) > 0){
					$PAY_SUM = $arInvoice['AMOUNT'] - $arInvoice['PAYED'];
					include($arOrder['PAY_SYSTEM']['PATH_TO_ACTION']);
				}
				*/
                ?>

                <div id="invoice_<?= $arInvoice['ID']; ?>" style="display: none;" class="contractItem">
                    <header>
                        <h1><?= $arInvoice['NAME']; ?> от <?=$arInvoice['DATE'];?></h1>
                    </header>

                    <table class="invoice-items">
                        <tr>
                            <th>№</th>
                            <th colspan="2">Наименование</th>
                            <th class="buy">Купить</th>
                            <th class="price">Цена</th>
                            <th>Кол-во</th>
                            <th class="price">Сумма</th>
                        </tr>
                        <? $totalPrice = 0;?>
                        <? foreach ($arInvoice['BASKET_ITEMS'] as $k => $arBasketItem): ?>
                            <tr>
                                <td><?= ($k + 1); ?></td>
                                <td>
                                    <img src="<?= $arBasketItem['PICTURE']; ?>">
                                </td>
                                <td class="name">
                                    <? if ($arBasketItem['URL']): ?>
                                        <a href="<?= $arBasketItem['URL']; ?>" target="_blank">
                                            <?= $arBasketItem['NAME']; ?>
                                        </a>
                                    <? else: ?>
                                        <?= $arBasketItem['NAME']; ?>
                                    <?endif; ?>
				    <? if ($arBasketItem['REFERENCE']):?>	
					<br><span class="sku"><?=$arBasketItem['REFERENCE'];?></span>
				   <? else: ?>
                        		<br><span class="text-error">Товар отсутствует на сайте</span>
				   <? endif;?>
                                </td>
	<td class="product-icons cat-line-icons">
            <div class="nowrap">
                <? if ($arBasketItem['REFERENCE']): ?>
                    <div class="rollover<?=$arBasketItem['IN_BASKET'] ? ' active' : ''?> list-flipper">
                        <div class="flip-side rollover__front">
                            <a href="/basket/ajax/?id=<?=$arBasketItem['ID'];?>"
                               class="cart-line basket-add"
                               title="<?=GetMessage("ADD_TO_CART");?>"
                               data-picture="product_picture_<?=$arBasketItem['ID'];?>">
                                <img src="<?=SITE_TEMPLATE_PATH;?>/img/catalog/cart-line.png"
                                     alt="" width="36" height="36">
                            </a>
                        </div>
                        <div class="flip-side rollover__bottom">
                            <form>
                                <input class="input-basket-count"
                                       data-href="/basket/ajax/?id=<?=$arBasketItem['ID'];?>&action=update" maxlength="5"
                                       type="text" data-product="<?=$arBasketItem['ID'];?>" value="1">
                                <button class="removeProduct"
                                        data-href="/basket/ajax/?id=<?=$arBasketItem['ID'];?>&action=deleteFast"
                                        data-product="<?=$arBasketItem['ID'];?>" type="reset"></button>
                            </form>
                        </div>
                    </div>
                <? endif; ?>

            </div>
        </td>
                                <td class="price">
                                    <?= priceFormat($arBasketItem['PRICE']); ?><i class="rouble">a</i>
                                </td>
                                <td class="qty"><?= $arBasketItem['QUANTITY']; ?></td>
                                <td class="price">
                                    <?$totalPrice += $arBasketItem['PRICE'] * $arBasketItem['QUANTITY'];?>
                                    <?=priceFormat($arBasketItem['PRICE'] * $arBasketItem['QUANTITY']); ?><i class="rouble">a</i>
                                </td>
                            </tr>
                        <? endforeach; ?>
							<? if ($totalPrice < $arInvoice['AMOUNT']):?>
							<tr>
								<td></td>
								<td></td>
								<td class="name"><span class="transport-service">Транспортно-упаковочная услуга</span></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="price">
                                                                	<?=priceFormat($arInvoice['AMOUNT'] - $totalPrice); ?><i class="rouble">a</i>
								</td>
							</tr>
						    	<?endif;?>
                    </table>
                    <div class="cartTotals clearfix">
                        <div class="totalSum">
                            <span class="text">Итого:</span>
                            <span class="cartTotal"><?=priceFormat($arInvoice['AMOUNT']); ?> <i class="rouble">a</i></span>
                        </div>
                    </div>
                </div>
                <div class="b-invoiceStatus">
                    <? /*<a href="#">Выставлен новый счет &rarr;</a>*/ ?>
                </div>
            </div>
            <div class="b-invoiceBody is-invoiceBody-toggled" style="display: none;">

                <div class="box-docs">
                    <? if (!empty($arInvoice['SALES'])
                           || !empty($arInvoice['PAYMENTS'])
                           || !empty($arInvoice['REFUNDS'])
                    ): ?>
                        <table>
                            <thead>
                            <tr>
                                <td>&nbsp;</td>
                                <td>Дата</td>
                                <? /*<td>Сумма</td>*/ ?>
                                <td>Оплачено</td>
                                <td>Отгружено</td>
                            </tr>
                            </thead>
                            <tbody>
                            <? if (!empty($arInvoice['PAYMENTS'])): ?>
                                <? /*<div class="b-orderRow">
                                        <span>Договор на оплату #1234567890</span>
                                    </div>*/
                                ?>
                                <? foreach ($arInvoice['PAYMENTS'] as $arPayment): ?>
                                    <tr class="b-orderRow">
                                        <td>
                                            <?= $arPayment['NAME']; ?>
                                        </td>
                                        <td>
                                            <nobr><?= $arPayment['DATE']; ?></nobr>
                                        </td>
                                        <? /*<td>
                                                <em class="invoiceSum right">
                                                    <nobr>
                                                        –
                                                    </nobr>
                                                </em>
                                            </td>*/
                                        ?>
                                        <td>
                                            <em class="invoiceSum right">
                                                <nobr>
                                                    <?= priceFormat($arPayment['AMOUNT']); ?>
                                                    <i class="rouble">a</i>
                                                </nobr>
                                            </em>
                                        </td>
                                        <td>
                                            <em class="invoiceSum right">
                                                <nobr>
                                                    –
                                                </nobr>
                                            </em>
                                        </td>
                                    </tr>

                                <? endforeach; ?>
                            <? endif; ?>

                            <? if (!empty($arInvoice['SALES'])): ?>
                                <? foreach ($arInvoice['SALES'] as $arSale): ?>
                                    <tr class="b-orderRow">
                                        <td><span><?= $arSale['NAME']; ?> — </span>
                                            <a href="#invoice_<?= $arSale['ID']; ?>" class="popup" data-width="700"><?= count($arSale['BASKET_ITEMS']); ?>
                                                <?echo strMorph(count($arSale['BASKET_ITEMS']), 'товар', 'товара', 'товаров') . ' (сертификаты)'; ?></a>

                                            <div id="invoice_<?= $arSale['ID']; ?>" style="display: none;" class="contractItem">
                                                <header>
                                                    <h1><?= $arSale['NAME']; ?> от <?=$arSale['DATE']; ?></h1>
                                                </header>

                                                <table class="invoice-items">
                                                    <tr>
                                                        <th>№</th>
                                                        <th colspan="2">Наименование</th>
                                                        <th class="buy">Купить</th>
                                                        <th class="price">Цена</th>
                                                        <th>Кол-во</th>
                                                        <th class="price">Сумма</th>
                                                    </tr>
                                                    <? $totalPrice = 0;?>
                                                    <? foreach ($arSale['BASKET_ITEMS'] as $k => $arBasketItem): ?>
                                                        <tr>
                                                            <td><?= ($k + 1); ?></td>
                                                            <td>
                                                                <img src="<?= $arBasketItem['PICTURE']; ?>">
                                                            </td>
                                                            <td class="name">
                                                                <? if ($arBasketItem['URL']): ?>
                                                                    <a href="<?= $arBasketItem['URL']; ?>" target="_blank">
                                                                        <?= $arBasketItem['NAME']; ?>
                                                                    </a>
                                                                <? else: ?>
                                                                    <?= $arBasketItem['NAME']; ?>
                                                                <?endif; ?>
				    				<? if ($arBasketItem['REFERENCE']):?>	
									<br><span class="sku"><?=$arBasketItem['REFERENCE'];?></span>
								<? else: ?>
                        						<br><span class="text-error">Товар отсутствует на сайте</span>
				   				<? endif;?>

								<? if ($arBasketItem['FILES']) echo '<br>'.$arBasketItem['FILES'];?>
                                                            </td>
	<td class="product-icons cat-line-icons">
            <div class="nowrap">
                <? if ($arBasketItem['REFERENCE']): ?>
                    <div class="rollover<?=$arBasketItem['IN_BASKET'] ? ' active' : ''?> list-flipper">
                        <div class="flip-side rollover__front">
                            <a href="/basket/ajax/?id=<?=$arBasketItem['ID'];?>"
                               class="cart-line basket-add"
                               title="<?=GetMessage("ADD_TO_CART");?>"
                               data-picture="product_picture_<?=$arBasketItem['ID'];?>">
                                <img src="<?=SITE_TEMPLATE_PATH;?>/img/catalog/cart-line.png"
                                     alt="" width="36" height="36">
                            </a>
                        </div>
                        <div class="flip-side rollover__bottom">
                            <form>
                                <input class="input-basket-count"
                                       data-href="/basket/ajax/?id=<?=$arBasketItem['ID'];?>&action=update" maxlength="5"
                                       type="text" data-product="<?=$arBasketItem['ID'];?>" value="1">
                                <button class="removeProduct"
                                        data-href="/basket/ajax/?id=<?=$arBasketItem['ID'];?>&action=deleteFast"
                                        data-product="<?=$arBasketItem['ID'];?>" type="reset"></button>
                            </form>
                        </div>
                    </div>
                <? endif; ?>

            </div>
        </td>
                                                            <td class="price">
                                                                <?= priceFormat($arBasketItem['PRICE']); ?><i class="rouble">a</i>
                                                            </td>
                                                            <td class="qty"><?= $arBasketItem['QUANTITY']; ?></td>
                                                            <td class="price">
                                                                <?$totalPrice += $arBasketItem['PRICE'] * $arBasketItem['QUANTITY'];?>
                                                                <?=priceFormat($arBasketItem['PRICE'] * $arBasketItem['QUANTITY']); ?><i class="rouble">a</i>
                                                            </td>


                                                        </tr>
                                                    <? endforeach; ?>
							<? if ($totalPrice < $arSale['AMOUNT']):?>
							<tr>
								<td></td>
								<td></td>
								<td class="name"><span class="transport-service">Транспортно-упаковочная услуга</span></td>
								<td></td>
								<td></td>
								<td></td>
								<td class="price">
                                                                	<?=priceFormat($arSale['AMOUNT'] - $totalPrice); ?><i class="rouble">a</i>
								</td>
							</tr>
						    	<?endif;?>
                                                </table>
                                                <div class="cartTotals clearfix">
                                                    <div class="totalSum">
                                                        <span class="text">Итого:</span>
                                                        <span class="cartTotal"><?=priceFormat($arSale['AMOUNT']); ?> <i class="rouble">a</i></span>
                                                    </div>
                                                </div>
                                                <div><?= $arSale['COMMENT']; ?></div>
                                            </div>

                                        </td>
                                        <td>
                                            <nobr><?= $arSale['DATE']; ?></nobr>
                                        </td>
                                        <? /*<td>
                                                <em class="invoiceSum right">
                                                    <nobr>
                                                        –
                                                    </nobr>
                                                </em>
                                            </td>*/
                                        ?>
                                        <td>
                                            <em class="invoiceSum right">
                                                <nobr>
                                                    –
                                                </nobr>
                                            </em>
                                        </td>
                                        <td>
                                            <em class="invoiceSum right">
                                                <nobr><?= priceFormat($arSale['AMOUNT']); ?><i class="rouble">a</i>
                                                </nobr>
                                            </em>
                                        </td>
                                    </tr>
                                    <? foreach ($arSale['CARGO'] as $arCargo): ?>
                                        <tr class="b-orderRow">
                                            <td>
                                                    <span class="invoiceDetails invoiceSmall"><?= $arCargo['NAME']; ?>
                                                        через транспортную компанию: <?= $arCargo['COMPANY'];  ?> (Квитанция № <?=$arCargo['RECEIPT'];?>)</span>
                                            </td>
                                            <td>
                                                <nobr><?= $arCargo['DATE']; ?></nobr>
                                            </td>
                                            <? /*<td>
                                                <em class="invoiceSum right">
                                                    <nobr>
                                                        –
                                                    </nobr>
                                                </em>
                                            </td>*/
                                            ?>
                                            <td>
                                                <em class="invoiceSum right">
                                                    <nobr>
                                                        –
                                                    </nobr>
                                                </em>
                                            </td>
                                            <td>
                                                <em class="invoiceSum right">
                                                    <nobr>
                                                        –
                                                    </nobr>
                                                </em>
                                            </td>
                                        </tr>
                                    <? endforeach; ?>
                                <? endforeach; ?>
                            <? endif; ?>
                            <? if (!empty($arInvoice['REFUNDS'])): ?>
                                <? foreach ($arInvoice['REFUNDS'] as $arSale): ?>
                                    <tr class="b-orderRow">
                                        <td><span><?= $arSale['NAME']; ?> — </span>
                                            <a href="#invoice_<?= $arSale['ID']; ?>" class="popup" data-width="700"><?= count($arSale['BASKET_ITEMS']); ?>
                                                <?= strMorph(count($arSale['BASKET_ITEMS']), 'товар', 'товара', 'товаров'); ?></a>

                                            <div id="invoice_<?= $arSale['ID']; ?>" style="display: none;" class="contractItem">
                                                <header>
                                                    <h1><?= $arSale['NAME']; ?> на сумму
                                                        <b><?= priceFormat($arSale['AMOUNT']); ?><i class="rouble">a</i></b></h1>
                                                </header>

                                                <table class="invoice-items">
                                                    <tr>
                                                        <th>№</th>
                                                        <th colspan="2">Наименование</th>
                                                        <th>Цена</th>
                                                        <th>Кол-во</th>
                                                        <th>Сумма</th>
                                                    </tr>
                                                    <? $totalPrice = 0;?>
                                                    <? foreach ($arSale['BASKET_ITEMS'] as $k => $arBasketItem): ?>
                                                        <tr>
                                                            <td><?= ($k + 1); ?></td>
                                                            <td>
                                                                <img src="<?= $arBasketItem['PICTURE']; ?>">
                                                            </td>
                                                            <td class="name">
                                                                <? if ($arBasketItem['URL']): ?>
                                                                    <a href="<?= $arBasketItem['URL']; ?>" target="_blank">
                                                                        <?= $arBasketItem['NAME']; ?>
                                                                    </a>
                                                                <? else: ?>
                                                                    <?= $arBasketItem['NAME']; ?>
                                                                <?endif; ?>
                                                            </td>
                                                            <td class="price">
                                                                <?= $arBasketItem['PRICE']; ?><i class="rouble">a</i>
                                                            </td>
                                                            <td class="qty"><?= $arBasketItem['QUANTITY']; ?></td>
                                                            <td class="price">
                                                                <?$totalPrice += $arBasketItem['PRICE'] * $arBasketItem['QUANTITY'];?>
                                                                <?=$arBasketItem['PRICE'] * $arBasketItem['QUANTITY']; ?><i class="rouble">a</i>
                                                            </td>
                                                        </tr>
                                                    <? endforeach; ?>
                                                </table>
                                                <div class="cartTotals clearfix">
                                                    <div class="totalSum">
                                                        <span class="text">Итого:</span>
                                                        <span class="cartTotal"><?=$totalPrice; ?> <i class="rouble">a</i></span>
                                                    </div>
                                                </div>
                                                <div><?= $arSale['COMMENT']; ?></div>
                                            </div>

                                        </td>
                                        <td>
                                            <nobr><?= $arSale['DATE']; ?></nobr>
                                        </td>
                                        <td>
                                            <em class="invoiceSum right">
                                                <nobr>
                                                    –
                                                </nobr>
                                            </em>
                                        </td>
                                        <td>
                                            <em class="invoiceSum right">
                                                <nobr><?= priceFormat($arSale['AMOUNT']); ?><i class="rouble">a</i>
                                                </nobr>
                                            </em>
                                        </td>
                                    </tr>
                                <? endforeach; ?>
                            <? endif; ?>
                            </tbody>
                        </table>
                    <? endif; ?>
                </div>
                <!-- box-docs -->
                <? /*<div class="box-bars">
                        <section class="accountFinancials">
                            <div class="b-financialsRow clearfix">
                                <span>Общая сумма</span>

                                <div class="orderBar right">
                                    <div class="orderFill fill-100">
                                            <span><?= priceFormat($arInvoice['AMOUNT']); ?> <i
                                                    class="rouble">a</i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="b-financialsRow clearfix">
                                <span>Оплачено</span>

                                <div class="orderBar right">
                                    <div class="orderFill"
                                         style="width:<?= round($arInvoice['PAYED'] / $arInvoice['AMOUNT'] * 100); ?>%;">
                                                <span><?= priceFormat($arInvoice['PAYED']); ?> <i
                                                        class="rouble">a</i></span></div>
                                </div>
                            </div>
                            <div class="b-financialsRow clearfix">
                                <span>Отгружено</span>

                                <div class="orderBar right">
                                    <div class="orderFill"
                                         style="width:<?= round($arInvoice['SOLD'] / $arInvoice['AMOUNT'] * 100); ?>%;">
                                                <span><?= priceFormat($arInvoice['SOLD']); ?> <i
                                                        class="rouble">a</i></span>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>*/
                ?>
                <!-- box-bars -->
            </div>
            </article>
        <? endforeach; ?>

        </section>
    <? endforeach; ?>

    <? if (strlen($arResult["NAV_STRING"]) > 0): ?>
        <?= $arResult["NAV_STRING"] ?>
    <? endif ?>
<? else: ?>
    <section class="orderItem">
        <section class="accountFinancials">
            <span>За выбранный период документы не найдены</span>
        </section>
    </section>
<?endif; ?>
</section>

