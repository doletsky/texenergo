<form method="POST" name="order_later_form" id="order_later_form" class="form" data-messages="yes">

    <?= bitrix_sessid_post() ?>
    <input type="hidden" name="create_order" value="yes">
    <input type="hidden" name="DELIVERY_ID" id="DELIVERY_ID" value="<?= $arDelivery['SID']; ?>:<?= $profileID; ?>">


    <section class="form-block">
        <header class="formHeader">
            <h1>Выберите место самовывоза</h1>
        </header>
        <section class="formBody">
            <?$APPLICATION->IncludeComponent(
                "aero:catalog.store.list",
                "",
                Array(
                    "PATH_TO_ELEMENT" => "store/#store_id#",
                    "SET_TITLE" => "N",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "36000000"
                ),
                $component
            );?>
        </section>
    </section>

    <footer class="order-buttons clearfix">
        <a href="/basket/" class="button prev">вернуться в корзину</a>
        <button class="button orange next">перейти к оплате</button>
    </footer>
</form>