<div class="three">
    <nav class="sidebar sidebar-main sidebar-account">
        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "orders",
            Array(
                "ROOT_MENU_TYPE" => "orders",
                "MAX_LEVEL" => "1",
                "CHILD_MENU_TYPE" => "orders",
                "USE_EXT" => "Y",
                "DELAY" => "N",
                "ALLOW_MULTI_SELECT" => "N",
                "MENU_CACHE_TYPE" => "N",
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "MENU_CACHE_GET_VARS" => array()
            )
        );?>
        <ul>

            <li class="more-info">
                <section class="sidebarDetails-orders">
                    <span class="cur-orders-caption">текущие заказы</span>

                    <?$APPLICATION->IncludeComponent(
                        "aero:sale.personal.order.list",
                        "short",
                        array(
                            "PATH_TO_DETAIL" => '',
                            "PATH_TO_CANCEL" => '',
                            "PATH_TO_COPY" => '',
                            "PATH_TO_BASKET" => '',
                            "SAVE_IN_SESSION" => 'N',
                            "ORDERS_PER_PAGE" => 5,
                            "SET_TITLE" => 'N',
                            "ID" => '',
                            "NAV_TEMPLATE" => '',
                        ),
                        false
                    );
                    ?>


                    <?/*<a href="#" class="archiveLink">Архив заявок »</a>*/?>
                </section>
            <?
            /*
            <section class="sidebarCredit sidebarCredit-profile">
                <span>Остаток по кредиту:</span>
                <em>7 000 <i class="rouble">a</i> из 60 000 <i class="rouble">a</i></em>
            </section>
            <section class="sidebarDiscount"><span>Персональная скидка</span><em>12%</em></section>
            */?>
            <?/*$APPLICATION->IncludeComponent(
                "bitrix:socserv.auth.split",
                "",
                Array(
                    "SHOW_PROFILES" => "Y",
                    "ALLOW_DELETE" => "Y"
                ),
                false
            );*/?>
            </li>
</ul>
</nav>
</div>