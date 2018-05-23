<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<aside class="accountFilter">
    <div class="b-filterStatus">
        <label>показывать:</label>
        <select class="j-select">
            <option
                value="<?= $APPLICATION->GetCurPageParam('status=all', Array('status')); ?>" <?= ($arResult['FILTER_STATUS'] == 'all' ? 'selected' : ''); ?>>
                По всем юр. лицам
            </option>
            <option
                value="<?= $APPLICATION->GetCurPageParam('status=open', Array('status')); ?>" <?= ($arResult['FILTER_STATUS'] == 'open' ? 'selected' : ''); ?>>
                Только открытые
            </option>
            <option
                value="<?= $APPLICATION->GetCurPageParam('status=closed', Array('status')); ?>" <?= ($arResult['FILTER_STATUS'] == 'closed' ? 'selected' : ''); ?>>
                Только закрытые
            </option>
        </select>
    </div>
    <div class="b-filterPeriod">
        <label>за период:</label>
        <em><?= $arResult['FILTER_DATE_FROM']; ?> - <?= $arResult['FILTER_DATE_TO']; ?></em>

        <div class="filter-dates" id="filter_dates">
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

<article class="contractItem">
    <section class="orderItem">
        <?if ($arResult["ITEMS"]) {
            $bShowNote = false;?>

            <div class="contracts">
                <div class="contracts-header">
                    <div class="contracts-current">
                        Текущее состояние по оплате по всем договорам:

                        <span class="nowrap">
                            <?if ($arResult["DEBT_MONEY"] < 0) {?>
                                <span class="contracts-current-data">
                                    переплата - <?=priceFormat(abs($arResult["DEBT_MONEY"]))?><i class="rouble">a</i>
                                </span>
                            <?} else {?>
                                <span class="contracts-current-data debt">
                                    задолженность - <?=priceFormat($arResult["DEBT_MONEY"])?><i class="rouble">a</i>
                                </span>
                            <?}?>
                        </span>
                    </div>
                </div>

                <div class="contracts-list">
                    <?foreach ($arResult["ITEMS"] as $arItem) {?>
                        <section class="contact">
                            <header>Договор #<?=$arItem["DOC_ID"]?> от <?=$arItem["DATE"]?></header>
                            <footer>
                                <ul class="grid grid-cols-2 clearfix contact-grid">
                                    <li>
                                        <p>Юридическое лицо: «<strong><?=$arItem["COMPANY"]?></strong>»</p>

                                        <p>
                                            Текущее состояние:

                                            <?if ($arItem["DEBT_MONEY"] < 0) {?>
                                                <span class="contract-current">
                                                    переплата — <?=priceFormat(abs($arItem["DEBT_MONEY"]))?><i class="rouble">a</i>
                                                </span>
                                            <?} else {?>
                                                <span class="contract-current debt">
                                                    задолженность — <?=priceFormat($arItem["DEBT_MONEY"])?><i class="rouble">a</i>
                                                </span>
                                            <?}?>
                                        </p>

                                        <?if ($arItem["FILE"]) {?>
                                            <a href="<?=$arItem["FILE"]?>" target="_blank" class="button contract-button">
                                                Посмотреть договор →
                                            </a>
                                        <?}?>
                                    </li>
                                    <li>
                                        <p>
                                            Срок действия:
                                            <strong><?=$arItem["ACTIVE_FROM"]?> — <?=$arItem["ACTIVE_TO"]?></strong>
                                        </p>

                                        <?if ($arItem["REST_MONEY"] > 0 || $arItem["REST_DAYS"] > 0) {?>
                                            <div>Ваш кредитный лимит по договору:</div>
                                            <table class="contract-credit-limit">
                                                <tbody>
                                                <tr>
                                                    <?if ($arItem["REST_MONEY"] > 0) {
                                                        $bShowNote = true;?>

                                                        <td>в сумме, не более*:</td>
                                                        <td><?=priceFormat($arItem["REST_MONEY"])?><i class="rouble">a</i></td>
                                                    <?}?>
                                                </tr>
                                                <tr>
                                                    <?if ($arItem["REST_DAYS"] > 0) {?>
                                                        <td>в днях, не более:</td>
                                                        <td><?=$arItem["REST_DAYS"]?></td>
                                                    <?}?>
                                                </tr>
                                                </tbody>
                                            </table>
                                        <?}?>
                                    </li>
                                </ul>
                            </footer>
                        </section>
                    <?}?>
                </div>

                <?if ($bShowNote) {?>
                    <div class="contracts-note">
                        *Вы можете получить товар на эту сумму до оплаты.
                    </div>
                <?}?>
            </div>
        <?} else {?>
            <div class="no-contracts">
                Договоры не найдены
            </div>
        <?}?>
    </section>
</article>