<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<aside class="accountFilter">
    <div class="b-filterStatus">

    </div>
    <div class="b-filterPeriod">
        <label>за период:</label>
        <em><?= $arResult['FILTER_DATE_FROM']; ?> - <?= $arResult['FILTER_DATE_TO']; ?></em>

        <div class="filter-dates" id="filter_dates">
            <form action="<?= $APPLICATION->GetCurPageParam('', Array('date_from', 'date_to')); ?>" method="GET">
                <input type="hidden" name="date_from" id="orders_date_from"
                       value="<?= $arResult['FILTER_DATE_FROM']; ?>">
                <input type="hidden" name="date_to" id="orders_date_to" value="<?= $arResult['FILTER_DATE_TO']; ?>">
                <input type="hidden" name="status" value="all">

                <div class="calendar"></div>
                <button class="button orange">Показать</button>
            </form>
        </div>
    </div>
</aside>


<article class="contractItem">
    <? if (!empty($arResult['ITEMS'])): ?>
        <? foreach ($arResult['ITEMS'] as $arItem): ?>
            <article class="orderNote">
                <div class="b-orderNote clearfix">
                    <div class="b-orderNote-left left">
                        <h1 class="noteHeadline"><?= $arItem['DATE']; ?></h1>
                        <span class="noteInfo"><?= $arItem['NAME']; ?></span>
                        <small class="noteSmall"><?= $arItem['MESSAGE']; ?></small>
                    </div>
                    <?if($arItem['ORDER_ID']):?>
					<div class="b-orderNote-button right">
                        <a href="/personal/orders/?q=<?=$arItem['ORDER_ID']; ?>" class="button contract-button">перейти к заявке</a>
                    </div>
					<?endif;?>
                </div>
            </article>
        <? endforeach; ?>
    <? else: ?>
        <section class="orderItem">
            <section class="accountFinancials">
                <span>Сообщения не найдены</span>
            </section>
        </section>
    <?endif; ?>
</article>