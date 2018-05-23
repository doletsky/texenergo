<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();

global $USER;
?>
<? //='<pre>'.print_r($res, true).'</pre>';?>

<section class="b-reviews clearfix">
<? if (!empty($arResult["MESSAGES"])): ?>
    <div class="b-reviews_title">Отзывы</div>
    <div class="b-reviews_list">
    <?
    $iCount = 0;
    foreach ($arResult["MESSAGES"] as $res):
        $iCount++;
        ?>

        <div class="b-reviews_list__item" id="message<?= $res["ID"] ?>">
            <div class="b-reviews_user">
                <? if ($res['NAME']): ?>
                    <div class="user_name"><?= $res['NAME']; ?></div>
                <? else: ?>
                    <div class="user_name">Гость</div>
                <?endif; ?>
            </div>
            <div class="b-reviews_post">
                <div class="post_date"><?= $res['POST_DATE']; ?></div>
                <div class="rating review-rating rating_vote_graphic">
                    <?
                    $arRatingParams = Array(
                        "ENTITY_TYPE_ID" => "FORUM_POST",
                        "ENTITY_ID" => $res["ID"],
                        "OWNER_ID" => $res["AUTHOR_ID"],
                        "PATH_TO_USER_PROFILE" => strlen($arParams["PATH_TO_USER"]) > 0 ? $arParams["PATH_TO_USER"] : $arParams["~URL_TEMPLATES_PROFILE_VIEW"]
                    );
                    if (!isset($res['RATING']))
                        $res['RATING'] = array(
                            "USER_VOTE" => 0,
                            "USER_HAS_VOTED" => 'N',
                            "TOTAL_VOTES" => 0,
                            "TOTAL_POSITIVE_VOTES" => 0,
                            "TOTAL_NEGATIVE_VOTES" => 0,
                            "TOTAL_VALUE" => 0
                        );

                    $arRatingParams = array_merge($arRatingParams, $res['RATING']);
                    $GLOBALS["APPLICATION"]->IncludeComponent("bitrix:rating.vote", $arParams["RATING_TYPE"], $arRatingParams, $component, array("HIDE_ICONS" => "Y"));
                    ?>
                </div>
                <div class="post_msg"><?= $res['POST_MESSAGE_TEXT']; ?></div>
            </div>
        </div>
    <? endforeach; ?>
    </div>
<? endif; ?>

<? if (isAjaxRequest()) $APPLICATION->RestartBuffer(); ?>

    <div class="b-reviews_form">
        <div class="b-reviews_form_opener"><span>Оставить отзыв</span></div>
        <div class="b-reviews_form_content">
            <form class="form-ajax" id="review_add" data-messages="yes" name="REPLIER<?= $arParams["form_index"] ?>"
                  action="<?= POST_FORM_ACTION_URI ?>" method="post" enctype="multipart/form-data">
                <?= bitrix_sessid_post() ?>
                <input type="hidden" name="back_page" value="<?= $arResult["CURRENT_PAGE"] ?>"/>
                <input type="hidden" name="ELEMENT_ID" value="<?= $arParams["ELEMENT_ID"] ?>"/>
                <input type="hidden" name="SECTION_ID" value="<?= $arResult["ELEMENT_REAL"]["IBLOCK_SECTION_ID"] ?>"/>
                <input type="hidden" name="save_product_review" value="Y"/>
                <input type="hidden" name="preview_comment" value="N"/>
                <input type="hidden" name="send_button" value="yes">

                <?if (empty($arResult["ERROR_MESSAGE"]) && !empty($arResult["OK_MESSAGE"])):
                    ?>
                <div class="b-review_auth_wrap clearfix">
                    <?= ShowNote($arResult["OK_MESSAGE"]); ?>
                </div>
                <? endif; ?>
                <?
                if (!empty($arResult["ERROR_MESSAGE"])):
                    ?>
                <div class="b-review_auth_wrap clearfix"><?= ShowError($arResult["ERROR_MESSAGE"], "field-error-message"); ?></div>
                <?
                endif;
                ?>
                <?if (!$USER->IsAuthorized()):?>
            <div class="b-review_auth_wrap clearfix">
                <div class="b-review_auth">
                    <a href="/personal/" class="btn_review">Войти</a>
                    <!-- a href="#" class="review_link_reg">Регистрация</a --->
                </div>
                Вы не авторизованы, после авторизации поля ниже<br>будут заполнены информацией из профиля.
            </div>
                <?endif;?>
            <table class="b-review_table">
                <tr>
                    <td class="td_label">Ваше имя*:</td>
                    <td><input class="input_review" name="REVIEW_AUTHOR" id="REVIEW_AUTHOR<?= $arParams["form_index"] ?>" type="text" value="<?if ($USER->IsAuthorized()):?><?=$USER->GetFullName()?><?endif;?>" tabindex="<?= $tabIndex++; ?>"<?if ($USER->IsAuthorized()):?> readonly<?endif;?>/></td>
                </tr>
                <? if ($arResult["FORUM"]["ASK_GUEST_EMAIL"] == "Y"): ?>
                <tr>
                    <td class="td_label">E-mail:</td>
                    <td><input type="text" name="REVIEW_EMAIL"
                               id="REVIEW_EMAIL<?= $arParams["form_index"] ?>"
                               value="<?= $arResult["REVIEW_EMAIL"] ?>"
                               tabindex="<?= $tabIndex++; ?>" class="input_review"/></td>
                </tr>
                <? endif; ?>
                <tr class="tr_rating">
                    <td class="td_label">Рейтинг товара:</td>
                    <td> <?$APPLICATION->IncludeComponent(
                            "bitrix:iblock.vote",
                            "stars",
                            Array(
                                "IBLOCK_TYPE" => "",
                                "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                                "ELEMENT_ID" => $arParams["ELEMENT_ID"],
                                "CACHE_TYPE" => "N",
                                "CACHE_TIME" => "3600",
                                "MAX_VOTE" => "5",
                                "VOTE_NAMES" => array(0=>"0",1=>"1",2=>"2",3=>"3",4=>"4",5=>"",),
                                "SET_STATUS_404" => "Y",
                                "DISPLAY_AS_RATING" => "rating"
                            )
                        );?></td>
                </tr>
                <tr>
                    <td class="td_label">Ваше сообщение:</td>
                    <td><textarea name="REVIEW_TEXT" class="textarea_review"></textarea></td>
                </tr>
                <? if (strLen($arResult["CAPTCHA_CODE"]) > 0): ?>
                <tr>
                    <td class="td_label">Код подтверждения:</td>
                    <td>
                        <input type="hidden" name="captcha_code" value="<?= $arResult["CAPTCHA_CODE"] ?>"/>
                        <input type="text" size="30" name="captcha_word" tabindex="<?= $tabIndex++; ?>"
                               autocomplete="off" class="input_review input_review_captcha"/>
                        <!-- a href="#" class="update_captcha"></a --->
                        <div class="review_captcha">
                            <img src="/bitrix/tools/captcha.php?captcha_code=<?= $arResult["CAPTCHA_CODE"] ?>"
                                 alt="<?= GetMessage("F_CAPTCHA_TITLE") ?>"/>
                        </div>
                    </td>
                </tr>
                <? endif; ?>
            </table>
            <input type="submit" name="send_button" class="btn_review btn_review_send" value="Отправить" />
            <div class="clear"></div>
            </form>
        </div>
    </div>
<? if (isAjaxRequest()) stopApplication(); ?>
</section>
