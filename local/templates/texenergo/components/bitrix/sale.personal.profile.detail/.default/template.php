<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? /*
<a name="tb"></a>
<a href="<?=$arParams["PATH_TO_LIST"]?>"><?=GetMessage("SPPD_RECORDS_LIST")?></a>
<br /><br />
*/?>
<section class="form-block">
    <?if(strlen($arResult["ID"])>0):?>
        <section class="formBody">
            <?=ShowError($arResult["ERROR_MESSAGE"])?>
            <form method="post" action="<?=POST_FORM_ACTION_URI?>">
                <?=bitrix_sessid_post()?>
                <input type="hidden" name="ID" value="<?=$arResult["ID"]?>">
                <div class="formRow clearfix">
                    <div class="formParent clearfix">
                        <div class="formChild form-50">
                            <span class="formLabel">Профиль</span>
                            <input class="formInput required" type="text" name="NAME"
                                   value="<?= $arResult['NAME']; ?>">
                        </div>
                    </div>
                </div>
                <?
                $GLOBALS["APPLICATION"]->IncludeComponent(
                    "aero:sale.ajax.locations",
                    'popup',
                    array(
                        "AJAX_CALL" => "N",
                        "CITY_INPUT_NAME" => $arResult['CODE']['LOCATION_DELIVERY'],
                        "ZIP_INPUT_NAME" => $arResult['CODE']['ZIP_DELIVERY'],
                        "LOCATION_VALUE" => $arResult['PROFILE']['LOCATION_DELIVERY'],
                        "ZIP_VALUE" => $arResult['PROFILE']['ZIP_DELIVERY'],
                        "ORDER_PROPS_ID" => 'LOCATION_DELIVERY',
                        "ONCITYCHANGE" => "",
                        "SIZE1" => 100,
                        "INPUT_CLASS" => "formInput",
                    ),
                    null,
                    array('HIDE_ICONS' => 'Y')
                );
                ?>
                <div class="formRow clearfix">
                    <div class="formParent clearfix">
                        <div class="formChild form-50">
                            <span class="formLabel">Улица</span>
                            <input class="formInput required" type="text" name="<?= $arResult['CODE']['STREET_DELIVERY']; ?>"
                                   value="<?= $arResult['PROFILE']['STREET_DELIVERY']; ?>">
                        </div>
                    </div>
                    <div class="formParent no-margin clearfix">
                        <div class="formChild form-10">
                            <span class="formLabel">Дом</span>
                            <input class="formInput required" type="text" name="<?= $arResult['CODE']['HOUSE_DELIVERY']; ?>"
                                   value="<?= $arResult['PROFILE']['HOUSE_DELIVERY']; ?>">
                        </div>
                        <div class="formChild form-10">
                            <span class="formLabel">Корп.</span>
                            <input class="formInput" type="text" name="<?= $arResult['CODE']['HOUSING_DELIVERY']; ?>"
                                   value="<?= $arResult['PROFILE']['HOUSING_DELIVERY']; ?>">
                        </div>
                        <div class="formChild form-10">
                            <span class="formLabel">Кв.</span>
                            <input class="formInput required" type="text" name="<?= $arResult['CODE']['OFFICE_DELIVERY']; ?>"
                                   value="<?= $arResult['PROFILE']['OFFICE_DELIVERY']; ?>">
                        </div>
                        <div class="formChild form-10 no-margin">
                            <span class="formLabel">Этаж</span>
                            <input class="formInput" data-rules="integer" type="text" data-title="Этаж"
                                   name="<?= $arResult['CODE']['STAGE_DELIVERY']; ?>"
                                   value="<?= $arResult['PROFILE']['STAGE_DELIVERY']; ?>">
                        </div>
                    </div>
                </div>
                <div class="formRow clearfix">
                    <div class="formParent formParent-full clearfix">
                        <div class="formChild form-100">
                            <span class="formLabel">Дополнительная информация</span>
                            <textarea class="formInput formInput-field" name="<?= $arResult['CODE']['COMMENT_DELIVERY']; ?>" cols="30"
                                      rows="10"><?= $arResult['PROFILE']['COMMENT_DELIVERY']; ?></textarea>
                        </div>
                    </div>
                </div>




                <div class="formBottom">
                    <input type="submit" class="button orange" name="save" value="сохранить">
                </div>
            </form>
        </section>
    <?else:?>
        <?=ShowError($arResult["ERROR_MESSAGE"]);?>
    <?endif;?>
</section>
