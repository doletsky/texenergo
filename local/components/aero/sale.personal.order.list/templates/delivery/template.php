<? if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
use \Bitrix\Main\Page\Asset;
?>
<aside class="accountFilter">
    <div class="b-filterStatus">
        <label>показывать:</label>
        <select class="j-select">
            <option value="<?= $APPLICATION->GetCurPageParam('', Array('status')); ?>">по всем юр. лицам</option>
            <option
                value="<?= $APPLICATION->GetCurPageParam('status=I',
                                                         Array('status')); ?>" <?= ($arResult['FILTER_STATUS'] == 'I'
                ? 'selected' : ''); ?>>
                Новые счета
            </option>
            <option
                value="<?= $APPLICATION->GetCurPageParam('status=P',
                                                         Array('status')); ?>" <?= ($arResult['FILTER_STATUS'] == 'P'
                ? 'selected' : ''); ?>>
                Частично оплаченные
            </option>
            <option
                value="<?= $APPLICATION->GetCurPageParam('status=C',
                                                         Array('status')); ?>" <?= ($arResult['FILTER_STATUS'] == 'C'
                ? 'selected' : ''); ?>>
                Оплаченные счета
            </option>
        </select>
    </div>
    <div class="b-filterPeriod">
        <label>за период:</label>
        <em><?= $arResult['FILTER_DATE_FROM']; ?> - <?= $arResult['FILTER_DATE_TO']; ?></em>

        <div class="filter-dates" id="filter_dates" style="display: none;">
            <form action="<?= $APPLICATION->GetCurPageParam('', Array('date_from',
                                                                      'date_to')); ?>" method="GET">
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
<? if(!empty($arResult["ORDERS"])): ?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/delivery/lib.js");?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/vendor/parsley.min.js");?>
    <?Asset::getInstance()->addJs(SITE_TEMPLATE_PATH."/js/delivery/script.js");?>
    <script src="http://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

    <!-- заявка на доставку -->
    <div class="box-popup box-popup-order hidden private_delivery"></div>

    <!-- доставка третьему лицу -->
    <div class="box-popup box-popup-person hidden third_party_delivery"></div>

    <!-- доставка по заказу из наличия -->
    <div class="box-popup box-popup-available hidden available_delivery"></div>

    <!-- заявка на предварительный подбор -->
    <div class="box-popup box-popup-advance hidden selection_delivery"></div>

    <section class="shipButtons">
        <div class="b-shipButtons clearfix">
            <button class="rounded rounded-shipping rounded-contract show-popup-order"
                    data-template="private_delivery">
                Заявка <br>на доставку
            </button>
            <button class="rounded rounded-shipping rounded-contract show-popup-person"
                    data-template="third_party_delivery">Доставка<br>третьему лицу
            </button>
            <button class="rounded rounded-shipping rounded-contract show-popup-available"
                    data-template="available_delivery">Доставка <br>по заказу из
                наличия
            </button>
            <button class="rounded rounded-shipping rounded-contract show-popup-advance"
                    data-template="selection_delivery">Предварительный<br>подбор
                по
                счету
            </button>
        </div>
    </section>

    <? foreach($arResult["ORDERS"] as $arOrderData): ?>
        <?
        $arOrder = $arOrderData['ORDER'];
        $arBasket = $arOrderData['BASKET_ITEMS'];
        ?>
        <section class="orderItem">
            <header>
                <h1>
                    <span class="orderName">Заказ №<?= $arOrder['ACCOUNT_NUMBER']; ?></span>
                    <span class="orderDate"> от <?= strtolower(FormatDate("d F Y",
                                                                          MakeTimeStamp($arOrder['DATE_INSERT'],
                                                                                        "DD.MM.YYYY HH:MI:SS"))) ?></span>
                </h1>

            </header>
            <section class="accountFinancials">
                <div class="b-financialsRow clearfix">
                    <span>Общая сумма</span>

                    <div class="orderBar right">
                        <div class="orderFill fill-100">
                            <span><?= priceFormat($arOrder['PRICE']); ?> <i class="rouble">a</i></span>
                        </div>
                    </div>
                </div>
                <div class="b-financialsRow clearfix">
                    <span>Оплачено</span>

                    <div class="orderBar right">
                        <div class="orderFill"
                             style="width:<?= round($arOrder['PAYED'] / $arOrder['PRICE'] * 100); ?>%;">
                            <span><?= priceFormat($arOrder['PAYED']); ?> <i class="rouble">a</i></span></div>
                    </div>
                </div>
                <div class="b-financialsRow clearfix">
                    <span>Отгружено</span>
                    <div class="orderBar right">
                        <div class="orderFill"
                             style="width:<?= round($arOrder['SOLD'] / $arOrder['PRICE'] * 100); ?>%;">
                            <span><?= priceFormat($arOrder['SOLD']); ?> <i class="rouble">a</i></span>
                        </div>
                    </div>
                </div>
            </section>
            <? foreach($arOrder['INVOICES'] as $arInvoice): ?>
                <section class="accountFinancials accountShipping">
                    <div class="b-accountShipping clearfix">
                        <div class="b-accountShipping-left">
                            <label>
                                <input type="checkbox" name="order[]" data-bill value="<?= $arInvoice['ID'] ?>">
                                <span><?= $arInvoice['NAME']; ?></span>
                            </label>
                        </div>
                        <div class="b-accountShipping-right">
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
                                         style="width:<?= round($arInvoice['PAYED'] / $arInvoice['AMOUNT']
                                                                * 100); ?>%;">
                                            <span><?= priceFormat($arInvoice['PAYED']); ?> <i
                                                    class="rouble">a</i></span></div>
                                </div>
                            </div>
                            <div class="b-financialsRow clearfix">
                                <span>Отгружено</span>

                                <div class="orderBar right">
                                    <div class="orderFill"
                                         style="width:<?= round($arInvoice['SOLD'] / $arInvoice['AMOUNT'] * 100); ?>%;">
                                        <span><?= priceFormat($arInvoice['SOLD']); ?> <i class="rouble">a</i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

            <? endforeach; ?>
        </section>
    <? endforeach; ?>

    <? if(strlen($arResult["NAV_STRING"]) > 0): ?>
        <?= $arResult["NAV_STRING"] ?>
    <? endif ?>
<? else: ?>
    <section class="orderItem">
        <section class="accountFinancials">
            <span>Заказы, разрешенные к доставке, не найдены</span>
        </section>
    </section>
<? endif; ?>
</section>