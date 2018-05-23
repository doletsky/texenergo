<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? if (isAjaxRequest()) $APPLICATION->RestartBuffer(); ?>

<? if (!isAjaxRequest()): ?>
<div id="personal_order_detail" style="display: none;">
    <? endif; ?>

    <?
    $arDetParams = array(
        "PATH_TO_LIST" => $arResult["PATH_TO_LIST"],
        "PATH_TO_CANCEL" => $arResult["PATH_TO_CANCEL"],
        "PATH_TO_PAYMENT" => $arParams["PATH_TO_PAYMENT"],
        "SET_TITLE" => $arParams["SET_TITLE"],
        "ID" => $arResult["VARIABLES"]["ID"],
    );
    foreach ($arParams as $key => $val) {
        if (strpos($key, "PROP_") !== false)
            $arDetParams[$key] = $val;
    }

    $APPLICATION->IncludeComponent(
        "aero:sale.personal.order.detail",
        "",
        $arDetParams,
        $component
    );
    ?>
    <? if (!isAjaxRequest()): ?>
</div>
    <script>
        $(function () {
            $.fancybox($('#personal_order_detail'), {
                width: 700,
                maxWidth: 700
            });
        });
    </script>

<? endif; ?>

<? if (isAjaxRequest()) die(); ?>

<?$APPLICATION->IncludeComponent(
    "aero:sale.personal.order.list",
    "",
    array(
        "PATH_TO_DETAIL" => $arResult["PATH_TO_DETAIL"],
        "PATH_TO_CANCEL" => $arResult["PATH_TO_CANCEL"],
        "PATH_TO_COPY" => $arResult["PATH_TO_LIST"] . '?ID=#ID#',
        "PATH_TO_BASKET" => $arParams["PATH_TO_BASKET"],
        "SAVE_IN_SESSION" => $arParams["SAVE_IN_SESSION"],
        "ORDERS_PER_PAGE" => $arParams["ORDERS_PER_PAGE"],
        "SET_TITLE" => $arParams["SET_TITLE"],
        "USE_FILTER" => $arParams["USE_FILTER"],
        "ID" => $arResult["VARIABLES"]["ID"],
        "NAV_TEMPLATE" => $arParams["NAV_TEMPLATE"],
    ),
    false
);
?>
