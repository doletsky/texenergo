<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}
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
$this->setFrameMode(true);
?>
<form action="/ajax/deliveryForm.php" enctype="multipart/form-data" class="deliveryForm" method="post"
      data-slide-enable="false"
      data-ajax-form>
    <aside class="popup-order">
        <div data-fields>
            <?= bitrix_sessid_post() ?>
            <input type="hidden" name="submit" value="Y"/>
            <input type="hidden" name="template" value="<?= $templateName ?>"/>
            <header>
                <h1 class="h1-popup">Доставка по заказу из наличия</h1>
                <span class="order-num">Заявка №<?= $arResult['APP_ID'] ?></span>
                <span class="date-popup">От <?= strtolower(FormatDate("d F Y", time())) ?></span>
            </header>
            <section class="section-popup">
                <h2 class="h2-popup">Данные заявителя</h2>

                <div class="row-popup">
                    <span class="param-popup">Организация</span>
                    <span class="value-popup"><?= $arResult['AGENT']['NAME'] ?></span>
                </div>
                <div class="row-popup">
                    <span class="param-popup">Адрес</span>
                    <span class="value-popup"><?
                        $agentAddress = implode(", ",
                                                array_filter(array($arResult['AGENT']['PROPERTIES']['CITY_LEGAL']['VALUE'],
                                                                   $arResult['AGENT']['PROPERTIES']['STREET_LEGAL']['VALUE'],
                                                                   $arResult['AGENT']['PROPERTIES']['HOUSE_LEGAL']['VALUE'],
                                                                   $arResult['AGENT']['PROPERTIES']['HOUSING_LEGAL']['VALUE'],
                                                                   $arResult['AGENT']['PROPERTIES']['OFFICE_LEGAL']['VALUE'],
                                                                   $arResult['AGENT']['PROPERTIES']['STAGE_LEGAL']['VALUE'])));
                        echo $agentAddress;
                        ?></span>
                </div>
                <div class="row-popup">
                    <span class="param-popup">ФИО</span>
                    <span class="value-popup"><?= $USER->GetFullName() ?></span>
                </div>
                <div class="row-popup">
                    <span class="param-popup">Почта</span>
                    <a href="mailto:<?= $arResult['AGENT']['PROPERTIES']['EMAIL']['VALUE'][0] ?>"
                       class="value-popup link-popup"><?= implode(", ",
                                                                  $arResult['AGENT']['PROPERTIES']['EMAIL']['VALUE']) ?></a>
                </div>
                <div class="row-popup">
                    <span class="param-popup">Телефон</span>
                    <span class="value-popup"><?= $arResult['AGENT']['PROPERTIES']['PHONE']['VALUE'] ?></span>
                </div>
            </section>

            <section class="section-popup">
                <h2 class="h2-popup">Счета к отправке</h2>

                <? foreach($arResult['BILL_LIST'] as $bill): ?>
                    <input type="hidden" name="bills[]" value="<?= $bill['ID'] ?>"/>
                    <div class="invoice-popup group">
                        <div class="invoice-block-a">
                            <h3><?= $bill['NAME'] ?></h3>
                        <span>от <?= strtolower(FormatDate("d F Y", MakeTimeStamp($bill['PROPERTIES']['DATE']['VALUE'],
                                                                                  "DD.MM.YYYY HH:MI:SS"))) ?></span>
                        </div>
                        <div class="invoice-block-b">
                            <h3>По заявке №<?= $bill['PROPERTIES']['ORDER_ID']['VALUE'] ?> </h3>
                        <span>от <?= strtolower(FormatDate("d F Y", MakeTimeStamp($bill['ORDER']['DATE_INSERT'],
                                                                                  "YYYY-MM-DD HH:MI:SS"))) ?></span>
                        </div>
                        <div class="invoice-block-c">
                            <a href="javascript:" class="collapse" data-target=".bill-products-list-<?= $bill['ID'] ?>">
                                <?
                                $count = !empty($bill['PROPERTIES']['BASKET_ITEMS']['VALUE'])
                                    ? count($bill['PROPERTIES']['BASKET_ITEMS']['VALUE']) : 0;

                                echo $count;
                                ?> <?= strMorph($count, "товар", "товара", "товаров") ?> на
                                счету →
                            </a>
                        </div>
                    </div>
                    <div class="bill-products-list bill-products-list-<?= $bill['ID'] ?>">
                        <table>
                            <thead>
                            <tr>
                                <td>Наименование</td>
                                <td>Код</td>
                                <td>Цена</td>
                                <td>Количество</td>
                                <td>Сумма</td>
                            </tr>
                            </thead>
                            <tbody>
                            <? foreach($bill['PROPERTIES']['BASKET_ITEMS']['VALUE'] as $product): ?>
                                <tr>
                                    <td>
                                        <?= $product['NAME'] ?>
                                    </td>
                                    <td>
                                        <?= $product['CODE'] ?>
                                    </td>
                                    <td>
                                        <?= $product['PRICE'] ?>
                                    </td>
                                    <td>
                                        <?= $product['QUANTITY'] ?>
                                    </td>
                                    <td>
                                        <?= $product['SUMM'] ?>
                                    </td>
                                </tr>
                            <? endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <? endforeach; ?>
            </section>

            <section class="section-popup group">
                <h2 class="h2-popup">Способ и место доставки</h2>

                <div class="wrap-city-select group">
                    <div class="city-select"><span>москва и область</span></div>
                    <div class="city-select"><span>другой город</span></div>
                    <div class="wrap-input wrap-input-advanced">
                        <input class="checkbox-gray" id="popup-advanced" type="checkbox" required>
                        <label class="label-grayed" for="popup-advanced">За доставку оставшейся продукции оплату
                            произведем
                            дополнительно</label>
                    </div>
                </div>
            </section>
            <section class="section-popup group">
                <h2 class="h2-popup">Доверенное лицо — грузополучатель</h2>

                <? foreach($arResult['ENUM_LISTS']['SELF_RECEIVER'] as $val): ?>
                    <div class="wrap-input">
                        <input class="checkbox-gray" name="fields[self_receiver]" value="<?= $val['ID'] ?>"
                               id="order-source" type="checkbox">
                        <label class="label-grayed" for="order-source"><?= $val['VALUE'] ?></label>
                    </div>
                <? endforeach; ?>

                <? foreach($arResult['ENUM_LISTS']['TYPE'] as $key => $val):
                    if($val['XML_ID'] == 'type_private_person'){
                        $privateTypePerson = $val['ID'];
                    }
                    else {
                        $legalTypePerson = $val['ID'];
                    }?>
                    <div class="wrap-input person-type-input">
                        <input id="person-type<?= $val['ID'] ?>" name="fields[type]" class="radio-gray"
                               type="radio" value="<?= $val['ID'] ?>" <?= $val['XML_ID'] == 'type_private_person'
                            ? 'checked' : '' ?> data-group=".<?= $val['XML_ID'] ?>">
                        <label for="person-type<?= $val['ID'] ?>"><?= $val['VALUE'] ?></label>
                    </div>
                <? endforeach; ?>
                <div class="type_private_person person-type-block active">
                    <div class="block-source-a">
                        <h3 class="h3-popup">Фамилия и имя</h3>
                        <input class="input-popup input-popup-name need-required" name="fields[receiver_fio]"
                               type="text" required>
                    </div>
                    <div class="block-source-b ">
                        <div class="contact-phone-list">
                            <h3 class="h3-popup">Номер телефона</h3>
                            <input class="input-popup input-popup-phone-long only phone-template need-required"
                                   name="fields[receiver_phone][]"
                                   type="text" data-phone-mask required>
                        </div>
                        <a class="link-popup-big add-phone" href="javascript:"><em>+</em> Добавить еще один</a>
                    </div>
                </div>
                <div class="type_organization person-type-block">
                    <div class="block-source-full">
                        <h3 class="h3-popup">Наименование организации</h3>
                        <input class="input-popup input-popup-org need-required" name="fields[receiver_org]"
                               type="text">
                    </div>

                    <div class="block-source-third">
                        <h3 class="h3-popup">ИНН</h3>
                        <input type="text" name="fields[receiver_inn]"
                               class="input-popup input-popup-full need-required">
                    </div>
                    <div class="block-source-third">
                        <h3 class="h3-popup">КПП</h3>
                        <input type="text" name="fields[receiver_kpp]"
                               class="input-popup input-popup-full need-required">
                    </div>
                    <div class="block-source-third last">
                        <h3 class="h3-popup">ОКПО</h3>
                        <input type="text" name="fields[receiver_okpo]"
                               class="input-popup input-popup-full need-required">
                    </div>
                    <div class="textarea-50 first">
                        <h3 class="h3-popup">Адрес</h3>
                    <textarea name="fields[delivery_address]" id="" class="textarea-popup need-required" cols="30"
                              rows="5"></textarea>
                    </div>
                    <div class="textarea-50">
                        <h3 class="h3-popup">Пункт выгрузки</h3>
                        <textarea name="fields[delivery_point]" id="" class="textarea-popup need-required" cols="30"
                                  rows="5"></textarea>
                    </div>
                    <div class="block-source-b">
                        <div class="contact-phone-list">
                            <h3 class="h3-popup">Номер телефона</h3>
                            <input class="input-popup input-popup-phone-long only phone-template need-required"
                                   name="fields[receiver_phone][]"
                                   type="text" data-phone-mask>
                        </div>
                        <a class="link-popup-big l-margin-top-10 add-phone" href="javascript:"><em>+</em> Добавить еще
                            один</a>
                    </div>
                </div>
                <div class="hidden selfReceicerData">
                    <?
                    // тут поля для случая, когда заявитель является получателем
                    switch($arResult['USER']['UF_PAYER_TYPE']){
                        case SALE_PERSON_IP:
                        case SALE_PERSON_YUR:
                            $agentProps = $arResult['AGENT']['PROPERTIES'];
                            ?>
                            <input type="hidden" name="fields[type]" value="<?=$legalTypePerson?>" disabled/>
                            <input type="hidden" name="fields[receiver_org]" value="<?=$arResult['AGENT']['NAME']?>" disabled/>
                            <input type="hidden" name="fields[receiver_inn]" value="<?=$agentProps['INN']['VALUE']?>" disabled/>
                            <input type="hidden" name="fields[receiver_kpp]" value="<?=$agentProps['KPP']['VALUE']?>" disabled/>
                            <input type="hidden" name="fields[delivery_address]" value="<?=$agentAddress;?>" disabled/>
                            <input type="hidden" name="fields[receiver_phone]" value="<?=$agentProps['PHONE']['VALUE']?>" disabled/>
                            <?break;
                        case SALE_PERSON_FIZ:
                        default:?>
                            <input type="hidden" name="fields[type]" value="<?=$privateTypePerson?>" disabled/>
                            <input type="hidden" name="fields[receiver_fio]" value="<?=CUser::GetFullName()?>" disabled/>
                            <input type="hidden" name="fields[receiver_phone][]" value="<?=$arResult['USER']['PERSONAL_PHONE']?>" disabled/>
                            <?break;
                    }?>
                </div>
                <div class="wrap-input">
                    <input id="checkbox100" class="checkbox-solid" type="checkbox" required>
                    <label for="checkbox100">Погрузочно-разгрузочные работы гарантирую произвести самостоятельно</label>
                </div>
                <div class="wrap-input">
                    <input id="checkbox101" class="checkbox-solid" type="checkbox" required>
                    <label for="checkbox101">Наличие доверенности (печати) гарантируем</label>
                </div>
            </section>
            <section class="section-popup section-popup-comments">
                <h2 class="h2-popup">Комментарий к доставке</h2>
                <textarea name="fields[comment]" id="shipping-comments" class="textarea-popup" cols="30"
                          rows="5"></textarea>
            </section>
        </div>
        <div class="section-popup" data-form-message>
            <div data-text-holder>
                <div class="" data-place-text></div>
            </div>
        </div>
        <footer>
            <button class="button-normal" type="submit">Отправить</button>
            <a class="link-bold close-popup-button" href="javascript:">Отменить</a>
        </footer>
        <i class="close-popup"><!-- close popup icon --></i>
    </aside>
</form>