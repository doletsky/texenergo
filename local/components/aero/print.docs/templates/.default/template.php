<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
?>
<html>
    <head>
        <title>
            Заказ №<?= $arResult["ACCOUNT_NUMBER"] ?> от <?= $arResult["DATE_INSERT"] ?>
        </title>
        <link rel="stylesheet" href="/local/templates/texenergo/css/print/order.css"/>
    </head>
    <body>
        <div class="document">
            <div class="line"></div>
            <img src="/local/templates/texenergo/img/header/logo.png" alt="Техэнерго" width="166" height="46">
            <h1>Заказ №<?= $arResult["ACCOUNT_NUMBER"] ?> от <?= $arResult["DATE_INSERT"] ?></h1>
            <table>
                <tr>
                    <td class="shop-info">
                        <strong>ИНТЕРНЕТ-МАГАЗИН</strong><br/>
                        WWW.TEXENERGO.RU<br/>
                        SALES@TEXENERGO.RU<br/>
                        +7 (495) 651-99-99
                    </td>
                    <td>
                        От: <?=CUser::GetFullName()?><br/>
                        Телефон: <?=strlen($arResult['CMS_USER']['PERSONAL_PHONE']) > 0 ?
                            $arResult['CMS_USER']['PERSONAL_PHONE'] : $arResult['USER']['PROPERTIES']['PHONE']['VALUE']?><br/>
                        Электронная почта: <?=implode(", ", $arResult['USER']['PROPERTIES']['EMAIL']['VALUE'])?><br/>
                        Название компании: <?=$arResult['USER']['NAME']?><br/>
                        <?=implode(", ",
                           array_filter(array(
                                $arResult['USER']['PROPERTIES']['ZIP_LEGAL']['VALUE'],
                                $arResult['USER']['PROPERTIES']['CITY_LEGAL']['VALUE'],
                                $arResult['USER']['PROPERTIES']['STREET_LEGAL']['VALUE'],
                                $arResult['USER']['PROPERTIES']['HOUSE_LEGAL']['VALUE'],
                                $arResult['USER']['PROPERTIES']['HOUSING_LEGAL']['VALUE'],
                                $arResult['USER']['PROPERTIES']['OFFICE_LEGAL']['VALUE'],
                                $arResult['USER']['PROPERTIES']['STAGE_LEGAL']['VALUE'],
                            ))
                        )?><br/>
                        Наименование банка: <?=$arResult['USER']['PROPERTIES']['BANK']['~VALUE']?><br/>
                        ИНН: <?=$arResult['USER']['PROPERTIES']['INN']['VALUE']?><br/>
                        КПП: <?=$arResult['USER']['PROPERTIES']['KPP']['VALUE']?><br/>
                        Корреспондентский счет: <?=$arResult['USER']['PROPERTIES']['ACCOUNT_COR']['VALUE']?><br/>
                        Расчетный счет: <?=$arResult['USER']['PROPERTIES']['ACCOUNT']['VALUE']?><br/>
                        БИК: <?=$arResult['USER']['PROPERTIES']['BIK']['VALUE']?>
                    </td>
                </tr>
            </table>
            <table class="products-list">
                <thead>
                <tr>
                    <th>
                        №
                    </th>
                    <th>
                        Референс
                    </th>
                    <th>
                        Артикул
                    </th>
                    <th>
                        Наименование
                    </th>
                    <th>
                        Цена
                    </th>
                    <th>
                        Кол-во
                    </th>
                    <th>
                        Сумма
                    </th>
                </tr>
                </thead>
                <tbody>
                <?
                $counter = 0;
                foreach($arResult['BASKET'] as $key => $arItem){?>
                    <tr>
                        <td><?= (++$counter); ?></td>
                        <td><?=$arResult['PRODUCTS'][$arItem['PRODUCT_ID']]['PROPERTIES']['REFERENCE']['VALUE']?></td>
                        <td><?=$arResult['PRODUCTS'][$arItem['PRODUCT_ID']]['PROPERTIES']['SKU']['VALUE']?></td>
                        <td><?=$arItem['NAME']?></td>
                        <td class="price"><?= $arItem['PRICE']; ?>&nbsp;<i class="rouble">a</i></td>
                        <td class="quantity"><?= $arItem['QUANTITY']; ?></td>
                        <td class="sum"><?= number_format($arItem['PRICE'] * $arItem['QUANTITY'], "2", ".", " "); ?>&nbsp;<i class="rouble">a</i></td>
                    </tr>
                <?}?>
                <?if($arResult['PRICE_DELIVERY'] > 0):?>
                    <tr>
                        <td><?= (++$counter); ?></td>
                        <td colspan="5">Доставка</td>
                        <td class="price"><?=$arResult['PRICE_DELIVERY']?><i class="rouble">a</i></td>
                    </tr>
                <?endif;?>
                <tr>
                    <td class="price" colspan="6"><strong>Сумма заказа:</strong></td>
                    <td class="sum"><strong><?=$arResult['PRICE']?>&nbsp;<i class="rouble">a</i></strong></td>
                </tr>
                </tbody>
            </table>
            <div class="message">
                • Обращаем ваше внимание, что оптовые цены действуют для счетов от <?=isset($arResult['WHOLESALE_SUM']) ? $arResult['WHOLESALE_SUM'] : "10 000"?> руб. <br/>
                • Розничная цена выше на <?=$arResult['WHOLESALE_DISCOUNT']?><?=$arResult['WHOLESALE_TYPE'] == "Perc" ? "%" : " руб"?>.<br/>
                • Цены указаны с учетом НДС.
            </div>
            <div class="line line-full"></div>
        </div>
    </body>
</html>
<script type="text/javascript">
    window.onload = function(){
        window.print();
    }
</script>