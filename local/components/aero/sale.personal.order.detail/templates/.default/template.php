<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>


<article class="contractItem">
    <header>

	   <h1>
            Заказ №<?= $arResult["ACCOUNT_NUMBER"] ?> от <?= $arResult["DATE_INSERT"] ?>
        </h1>
        <? /*
            <span class="orderWarn">Неоплаченные счета</span>
*/
        ?>
    </header>
    <a href="/personal/orders/docs/print/?form=order&orderId=<?=$arResult["ACCOUNT_NUMBER"]?>&mode=html" target="_blank" class="button contract-button">Распечатать заказ</a>

    <section class="contractItem">
        <? /*<header>
            <h1>Состав заказа</h1>
        </header>*/
        ?>

        <table class="invoice-items">
            <tr>
                <th>№</th>
                <th colspan="2">Наименование</th>
                <th class="price">Цена</th>
                <th>Кол-во</th>
                <th class="price">Сумма</th>
            </tr>
            <? foreach ($arResult['BASKET'] as $k => $arItem): ?>
                <tr>
                    <td><?= ($k + 1); ?></td>
                    <td>
                        <div class="b-cartImage">
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" target="_blank"
                               class="picture"
                               style="background-image: url('<?= $arItem['PICTURE']; ?>');"></a>
                        </div>
                        <? /*<img src="<?= $arItem['PICTURE']; ?>">*/ ?>
                    </td>
                    <td class="name">
                        <? if ($arItem['DETAIL_PAGE_URL']): ?>
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" target="_blank">
                                <?= $arItem['NAME']; ?>
                            </a>
                        <? else: ?>
                            <?= $arItem['NAME']; ?>
                        <?endif; ?>
			<?if ($arItem['REFERENCE'] ):?>
                        	<br><span class="sku"><?= $arItem['REFERENCE']; ?></span>
			<?else:?>
                        	<br><span class="text-error">Товар отсутствует на сайте</span>
			<? endif; ?>
                        <? if ($arItem['OLD_PRICE'] > 0): ?>
                            <div class="cat-discount catDiscount-cart"></div>
                        <? endif; ?>
                    </td>
                    <td class="price">
                        <?= priceFormat($arItem['PRICE']); ?><i class="rouble">a</i>
                    </td>
                    <td class="qty"><?= $arItem['QUANTITY']; ?></td>
                    <td class="price">
                        <?= priceFormat($arItem['PRICE'] * $arItem['QUANTITY']); ?><i class="rouble">a</i>
                    </td>
                </tr>
            <? endforeach; ?>

			<?if($arResult['PRICE_DELIVERY'] > 0):?>
				<tr>
                    <td><?= ($k + 2); ?></td>
                    <td><div class="b-cartImage"></div></td>
                    <td>Доставка</td>
                    <td class="price"></td>
                    <td class="qty"></td>
                    <td class="price"><?=$arResult['PRICE_DELIVERY']?><i class="rouble">a</i></td>
                </tr>
			<?endif;?>

        </table>

		<div class="clearfix">

			<?/* if($arResult["PAYED"] != "Y" && $arResult["CAN_REPAY"]=="Y" && $arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y"){
				$ORDER_ID = $ID;
				include($arResult['PAY_SYSTEM']['PATH_TO_ACTION']);
			} */?>

			<div class="cartTotals">
				<div class="totalSum">
					<span class="text">
					<?if($arResult["PAYED"] == "Y"):?>
						Оплачен
						<?if(strlen($arResult["DATE_PAYED_FORMATED"])):?>
							(<?=$arResult["DATE_PAYED_FORMATED"]?>)
						<?endif?>
					<?else:?>
						Сумма заказа:
					<?endif;?>
					</span>
					<span class="cartTotal"><?=priceFormat($arResult['PRICE'])?> <i class="rouble">a</i></span>
				</div>
			</div>
		</div>
        <? /* foreach ($arResult['BASKET'] as $k => $arItem): ?>



            <div class="cartItem cartItem-approve clearfix">
                <table>
                    <tr>
                        <td><?= ($k + 1); ?></td>
                        <td>
                            <div class="b-cartImage">
                                <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" target="_blank"
                                   class="picture"
                                   style="background-image: url('<?= $arItem['PICTURE']; ?>');"></a>
                                <? if ($arItem['OLD_PRICE'] > 0): ?>
                                    <div class="cat-discount catDiscount-cart"></div>
                                <? endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="b-cartName">
                                <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>" target="_blank"
                                   class="cartName"><?= $arItem['NAME']; ?></a>
                                <em class="cartSerial"><?= $arItem['SKU']; ?></em>
                            </div>
                        </td>

                        <td>
                            <div class="cartSubtotal">
                                <?= priceFormat($arItem['PRICE']); ?><i
                                    class="rouble">a</i>&nbsp;&cross;&nbsp;<?= $arItem['QUANTITY']; ?>
                                <nobr>
                                <span
                                    class="cartSum"><?= priceFormat($arItem['PRICE'] * $arItem['QUANTITY']); ?>
                                    <i class="rouble">a</i></span>
                                </nobr>
                            </div>
                        </td>
                    </tr>
                </table>

            </div>
        <? endforeach;*/
        ?>
    </section>

    <? return; ?>
    <section class="orderItem orderItem-order">
        <div class="b-orderItem clearfix">
            <? /*<h1>
                <span class="orderName orderName-order">Договор №1234567890</span>
                <span class="orderDate orderDate-order">От 17 октября 2013</span>
            </h1>*/
            ?>
            <? /*
            <div class="b-contractText">
                <span>Срок действия:</span>
                <em>11 сен 2013 — 11 окт 2013</em>
            </div>
            <div class="b-contractText">
                <span>Юридическое лицо</span>
                <em>"Техэнерго"</em>
            </div>

*/
            ?>

            <!-- diagram -->
            <? /*
            <aside class="contractStatus">
                <div class="b-contractStatus clearfix">
                    <h1>Cтатус договора</h1>

                    <div class="statusCol">
                        <div class="squareDebt"></div>
                        <span>Текущая задолженность</span>
                        <figure>
                            <img src="<?= SITE_TEMPLATE_PATH; ?>/img/account/diagram-1.png"
                                 alt="Текущая задолженность">
                            <figcaption>Максимум 20 дней</figcaption>
                        </figure>
                    </div>
                    <div class="statusCol">
                        <div class="squareLimit"></div>
                        <span>Оставшийся лимит</span>
                        <figure>
                            <img src="<?= SITE_TEMPLATE_PATH; ?>/img/account/diagram-2.png"
                                 alt="Текущая задолженность">
                            <figcaption>Максимум 800 <i class="rouble">a</i></figcaption>
                        </figure>
                    </div>
                </div>
            </aside>
*/
            ?>
        </div>
    </section>

</article>

<? return; ?>


<a name="tb"></a>
<a href="<?= $arResult["URL_TO_LIST"] ?>"><?= GetMessage("SPOD_RECORDS_LIST") ?></a>
<br/><br/>
<? if (strlen($arResult["ERROR_MESSAGE"]) <= 0): ?>
    <table class="sale_personal_order_detail data-table">
    <tr>
        <th colspan="2" align="center"><b><?= GetMessage("SPOD_ORDER_NO") ?>&nbsp;<?= $arResult["ACCOUNT_NUMBER"] ?>
                &nbsp;<?= GetMessage("SPOD_FROM") ?> <?= $arResult["DATE_INSERT"] ?></b></th>
    </tr>
    <tr>
        <td align="right" width="40%"><? echo GetMessage("SPOD_ORDER_STATUS") ?></td>
        <td width="60%"><?= $arResult["STATUS"]["NAME"] ?><?= GetMessage("SPOD_ORDER_FROM") ?><?= $arResult["DATE_STATUS"] ?>
            )
        </td>
    </tr>
    <tr>
        <td align="right" width="40%"><?= GetMessage("P_ORDER_PRICE") ?>:</td>
        <td width="60%"><?
            echo "<b>" . $arResult["PRICE_FORMATED"] . "</b>";
            if (DoubleVal($arResult["SUM_PAID"]) > 0)
                echo " (" . GetMessage("SPOD_ALREADY_PAID") . "&nbsp;<b>" . $arResult["SUM_PAID_FORMATED"] . "</b>)";
            ?></td>
    </tr>
    <tr>
        <td align="right" width="40%"><?= GetMessage("P_ORDER_CANCELED") ?>:</td>
        <td width="60%"><?
            echo(($arResult["CANCELED"] == "Y") ? GetMessage("SALE_YES") : GetMessage("SALE_NO"));
            if ($arResult["CANCELED"] == "Y") {
                echo GetMessage("SPOD_ORDER_FROM") . $arResult["DATE_CANCELED"] . ")";
                if (strlen($arResult["REASON_CANCELED"]) > 0)
                    echo "<br />" . $arResult["REASON_CANCELED"];
            } elseif ($arResult["CAN_CANCEL"] == "Y") {
                ?>&nbsp;<a href="<?= $arResult["URL_TO_CANCEL"] ?>"><?= GetMessage("SALE_CANCEL_ORDER") ?>&gt;&gt;</a><?
            }
            ?></td>
    </tr>
    <tr>
        <td align="right" colspan="2"><img src="/bitrix/images/1.gif" width="1" height="8"></td>
    </tr>
    <? if (IntVal($arResult["USER_ID"]) > 0): ?>
        <tr>
            <th colspan="2"><b><? echo GetMessage("SPOD_ACCOUNT_DATA") ?></b></th>
        </tr>
        <tr>
            <td align="right" width="40%"><?= GetMessage("SPOD_ACCOUNT") ?>:</td>
            <td width="60%"><?= $arResult["USER_NAME"] ?></td>
        </tr>
        <tr>
            <td align="right" width="40%"><?= GetMessage("SPOD_LOGIN") ?>:</td>
            <td width="60%"><?= $arResult["USER"]["LOGIN"] ?></td>
        </tr>
        <tr>
            <td align="right" width="40%"><? echo GetMessage("SPOD_EMAIL") ?></td>
            <td width="60%"><a href="mailto:<?= $arResult["USER"]["EMAIL"] ?>"><?= $arResult["USER"]["EMAIL"] ?></a>
            </td>
        </tr>
        <tr>
            <td align="right" colspan="2"><img src="/bitrix/images/1.gif" width="1" height="8"></td>
        </tr>
    <? endif; ?>
    <tr>
        <th colspan="2" align="center"><b><?= GetMessage("P_ORDER_USER") ?></b></th>
    </tr>
    <tr>
        <td align="right" width="40%"><?= GetMessage("P_ORDER_PERS_TYPE") ?>:</td>
        <td width="60%"><?= $arResult["PERSON_TYPE"]["NAME"] ?></td>
    </tr>
    <?
    if (!empty($arResult["ORDER_PROPS"])) {
        foreach ($arResult["ORDER_PROPS"] as $val) {
            if ($val["SHOW_GROUP_NAME"] == "Y") {
                ?>
                <tr>
                    <td colspan="2" align="center"><b><?= $val["GROUP_NAME"]; ?></b></td>
                </tr>
            <?
            }
            ?>
            <tr>
                <td width="40%" align="right"><? echo $val["NAME"] ?>:</td>
                <td width="60%"><?
                    if ($val["TYPE"] == "CHECKBOX") {
                        if ($val["VALUE"] == "Y")
                            echo GetMessage("SALE_YES");
                        else
                            echo GetMessage("SALE_NO");
                    } else
                        echo $val["VALUE"];
                    ?></td>
            </tr>
        <?
        }
    }
    if (strlen($arResult["USER_DESCRIPTION"]) > 0) {
        ?>
        <tr>
            <td align="right" colspan="2">
                <img src="/bitrix/images/1.gif" width="1" height="8">
            </td>
        </tr>
        <tr>
            <td align="right" width="40%"><?= GetMessage("P_ORDER_USER_COMMENT") ?>:</td>
            <td width="60%"><?= $arResult["USER_DESCRIPTION"] ?></td>
        </tr>
    <?
    }
    ?>
    <tr>
        <td align="right" colspan="2">
            <img src="/bitrix/images/1.gif" width="1" height="8">
        </td>
    </tr>

    <tr>
        <th colspan="2"><b><?= GetMessage("P_ORDER_PAYMENT") ?></b></th>
    </tr>
    <tr>
        <td align="right" width="40%"><?= GetMessage("P_ORDER_PAY_SYSTEM") ?>:</td>
        <td width="60%"><?
            if (IntVal($arResult["PAY_SYSTEM_ID"]) > 0)
                echo $arResult["PAY_SYSTEM"]["NAME"];
            else
                echo GetMessage("SPOD_NONE");
            ?></td>
    </tr>
    <tr>
        <td align="right" width="40%"><? echo GetMessage("P_ORDER_PAYED") ?>:</td>
        <td width="60%">
            <?
            echo(($arResult["PAYED"] == "Y") ? GetMessage("SALE_YES") : GetMessage("SALE_NO"));
            if ($arResult["PAYED"] == "Y")
                echo GetMessage("SPOD_ORDER_FROM") . $arResult["DATE_PAYED"] . ")";
            ?>

        </td>
    </tr>
    <?
    if ($arResult["CAN_REPAY"] == "Y") {
        ?>
        <tr>
            <td colspan="2" align="center">
                <?
                if ($arResult["PAY_SYSTEM"]["PSA_NEW_WINDOW"] == "Y") {
                    ?>
                    <a href="<?= $arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"] ?>"
                       target="_blank"><?= GetMessage("SALE_REPEAT_PAY") ?></a>
                <?
                } else {
                    $ORDER_ID = $ID;
                    include($arResult["PAY_SYSTEM"]["PSA_ACTION_FILE"]);
                }
                ?>
            </td>
        </tr>
    <?
    }
    ?>
    <tr>
        <td align="right" colspan="2">
            <img src="/bitrix/images/1.gif" width="1" height="8">
        </td>
    </tr>

    <tr>
        <th colspan="2" align="center"><b><?= GetMessage("P_ORDER_DELIVERY") ?></b></th>
    </tr>
    <tr>
        <td align="right" width="40%"><?= GetMessage("P_ORDER_DELIVERY") ?>:</td>
        <td width="60%"><?
            if (strpos($arResult["DELIVERY_ID"], ":") !== false || IntVal($arResult["DELIVERY_ID"]) > 0) {
                echo $arResult["DELIVERY"]["NAME"];
            } else {
                echo GetMessage("SPOD_NONE");
            }
            ?></td>
    </tr>
    <?
    if (strlen($arResult["TRACKING_NUMBER"]) > 0) {
        ?>
        <tr>
            <td align="right" width="40%"><?= GetMessage("P_ORDER_TRACKING_NUMBER") ?>:</td>
            <td width="60%"><?= $arResult["TRACKING_NUMBER"]; ?></td>
        </tr>
    <?
    }
    ?>
    <tr>
        <td align="right" colspan="2">
            <img src="/bitrix/images/1.gif" width="1" height="8">
        </td>
    </tr>

    <tr>
        <th colspan="2" align="center"><b><?= GetMessage("P_ORDER_BASKET") ?></b></th>
    </tr>
    <tr>
        <td colspan="2">
            <table class="sale_personal_order_detail data-table">
                <tr>
                    <th><?= GetMessage("SPOD_NAME") ?></th>
                    <th><?= GetMessage("SPOD_PROPS") ?></th>
                    <th><?= GetMessage("SPOD_DISCOUNT") ?></th>
                    <th><?= GetMessage("SPOD_PRICETYPE") ?></th>
                    <th><?= GetMessage("SPOD_QUANTITY") ?></th>
                    <th><?= GetMessage("SPOD_PRICE") ?></th>
                </tr>
                <?
                foreach ($arResult["BASKET"] as $val) {
                    ?>
                    <tr>
                        <td><?
                            if (strlen($val["DETAIL_PAGE_URL"]) > 0)
                                echo "<a href=\"" . $val["DETAIL_PAGE_URL"] . "\">";
                            echo htmlspecialcharsEx($val["NAME"]);
                            if (strlen($val["DETAIL_PAGE_URL"]) > 0)
                                echo "</a>";
                            ?></td>
                        <td> <?
                            if (!empty($val["PROPS"])):?>
                                <table cellspacing="0">
                                    <?
                                    foreach ($val["PROPS"] as $vv) {
                                        ?>
                                        <tr>
                                            <td style="border:0px; padding:1px;"><?= $vv["NAME"] ?>:</td>
                                            <td style="border:0px; padding:1px;"><?= $vv["VALUE"] ?></td>
                                        </tr>
                                    <?
                                    }
                                    ?>
                                </table>
                            <? endif; ?></td>
                        <td><?= $val["DISCOUNT_PRICE_PERCENT_FORMATED"] ?></td>
                        <td><?= htmlspecialcharsEx($val["NOTES"]) ?></td>
                        <td><?= $val["QUANTITY"] ?></td>
                        <td align="right"><?= $val["PRICE_FORMATED"] ?></td>
                    </tr>
                <?
                }
                ?>
                <? if (strlen($arResult["DISCOUNT_VALUE_FORMATED"]) > 0): ?>
                    <tr>
                        <td align="right"><b><?= GetMessage("SPOD_DISCOUNT") ?>:</b></td>
                        <td align="right" colspan="5"><?= $arResult["DISCOUNT_VALUE_FORMATED"] ?></td>
                    </tr>
                <? endif; ?>
                <?
                foreach ($arResult["TAX_LIST"] as $val) {
                    ?>
                    <tr>
                        <td align="right"><?
                            echo $val["TAX_NAME"];
                            echo $val["VALUE_FORMATED"];
                            ?>:
                        </td>
                        <td align="right" colspan="5"><?= $val["VALUE_MONEY_FORMATED"] ?></td>
                    </tr>
                <?
                }
                ?>
                <? if (strlen($arResult["TAX_VALUE_FORMATED"]) > 0): ?>
                    <tr>
                        <td align="right"><b><?= GetMessage("SPOD_TAX") ?>:</b></td>
                        <td align="right" colspan="5"><?= $arResult["TAX_VALUE_FORMATED"] ?></td>
                    </tr>
                <? endif; ?>
                <? if (strlen($arResult["PRICE_DELIVERY_FORMATED"]) > 0): ?>
                    <tr>
                        <td align="right"><b><?= GetMessage("SPOD_DELIVERY") ?>:</b></td>
                        <td align="right" colspan="5"><?= $arResult["PRICE_DELIVERY_FORMATED"] ?></td>
                    </tr>
                <? endif; ?>
                <tr>
                    <td align="right"><b><?= GetMessage("SPOD_ITOG") ?>:</b></td>
                    <td align="right" colspan="5"><?= $arResult["PRICE_FORMATED"] ?></td>
                </tr>
            </table>
        </td>
    </tr>
    </table>
<? else: ?>
    <?= ShowError($arResult["ERROR_MESSAGE"]); ?>
<?endif; ?>
