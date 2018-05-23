<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<?if ($arResult["ERROR"] != ""):?>
    <script>
    $(document).ready(function () {
        $('.b-reviews_form_opener').toggleClass('opened');
        $('.b-reviews_form_content').slideToggle(200);
    });
    </script>
<?endif;?>
<div class="pContent">
    <div class="b-reviews clearfix">
        <a id="b-reviews"></a>
		
		<?
		if($arResult['COMMENT_ADDED']):?> 
			<div class="b-reviews_title moderate-msg">Отзыв отправлен на модерацию</div>
		<?endif;?>
		
        <div class="b-reviews_title">Всего отзывов: <span><?=$arResult["COMMENTS_CNT"]?></span></div>

        <?if($arResult["RAITING"]){?>
            <div class="b-reviews_title"><div class="raiting">Средний рейтинг:  </div>
                <div class="pReviewRating block_raiting_big">
                    <div class="item<?if (($arResult["RAITING"] >= 1)):?> active<?endif;?>"></div>
                    <div class="item<?if (($arResult["RAITING"] >= 2)):?> active<?endif;?>"></div>
                    <div class="item<?if (($arResult["RAITING"] >= 3)):?> active<?endif;?>"></div>
                    <div class="item<?if (($arResult["RAITING"] >= 4)):?> active<?endif;?>"></div>
                    <div class="item<?if (($arResult["RAITING"] == 5)):?> active<?endif;?>"></div>
                </div>
            </div>
        <?}?>
        <? if (sizeof($arResult['COMMENTS']) > 0):?>
        <section class="pReviews">
            <?foreach ($arResult['COMMENTS'] as $arComment):?>

            <article class="pReviewSingle">
                <header>
                    <a id="id<?=$arComment["ID"];?>"></a>
                    <b><span id="helpful-<?=$arComment["ID"];?>"><?=$arComment['HELPFUL'];?></span> человек нашли отзыв полезным</b>
                    <!--<div>
                        <div class="pReviewRating block_raiting_small">
                            <div class="item<?/*if (($arComment["RAITING"] >= 1)):*/?> active<?/*endif;*/?>"></div>
                            <div class="item<?/*if (($arComment["RAITING"] >= 2)):*/?> active<?/*endif;*/?>"></div>
                            <div class="item<?/*if (($arComment["RAITING"] >= 3)):*/?> active<?/*endif;*/?>"></div>
                            <div class="item<?/*if (($arComment["RAITING"] >= 4)):*/?> active<?/*endif;*/?>"></div>
                            <div class="item<?/*if (($arComment["RAITING"] == 5)):*/?> active<?/*endif;*/?>"></div>
                        </div>
                    </div>-->
                    <div class="pGood"></div>
                </header>
                <section class="pReviewContent">
                    <?if ($arComment["CROP_DESCRIPTION"] != ""):?>
                        <p id="pcdall-<?=$arComment["ID"];?>"><?=$arComment["CROP_DESCRIPTION"];?> <a href="" class="pdall" data-id="<?=$arComment["ID"];?>">читать полностью</a></p>
                        <p style="display: none;" id="pdall-<?=$arComment["ID"];?>"><?=$arComment["DESCRIPTION"];?> <br /><a href="" class="pdcrop" data-id="<?=$arComment["ID"];?>">свернуть</a></p>
                    <?else:?>
                        <p><?=$arComment["DESCRIPTION"];?></p>
                    <?endif;?>
                    <span class="date"><?=$arComment["DATE"];?>, <?=$arComment["USER_NAME"];?></span>
                </section>
                <div class="btn_like<?if (isset($_SESSION["LIKE"]["ElementID".$arComment["ID"]])):?> active<? endif; ?>" id="like-<?=$arComment["ID"];?>" data-id="<?=$arComment["ID"];?>" data-active="false"></div>
            </article>
            <?endforeach;?>
        </section>
        <?endif;?>
        <div class="b-reviews_form">
            <a id="b-reviews-form"></a>
            <?if ($arResult['USER']['AUTH']):?>
                <div class="b-reviews_form_opener"><span>Оставить отзыв</span></div>
                <div class="b-reviews_form_content">
                    <?if (!$arResult["USER"]["AUTH"]):?>
                        <div class="b-review_auth_wrap clearfix">
                            <div class="b-review_auth">
                                <a href="/auth/?backurl=<?=$arParams['PAGE_URL'];?>" class="btn_review">Войти</a>
                                <!-- a href="#" class="review_link_reg">Регистрация</a --->
                            </div>
                            Вы не авторизованы, после авторизации поля ниже<br>будут заполнены информацией из профиля.
                        </div>
                    <?endif;?>
                    <?if ($arResult["ERROR"] != ""):?>
                        <div class="b-review_auth_wrap clearfix">
                            <?=$arResult["ERROR"];?>
                        </div>
                    <?endif;?>
                    <form action="<?=$arParams['PAGE_URL'];?>?add=true#b-reviews-form" method="post">
                    <table class="b-review_table">
                        <tr>
                            <td class="td_label">Ваше имя*:</td>
                            <td><input name="name" type="text" class="input_review" value="<?=$arResult["USER"]["NAME"];?>" /></td>
                        </tr>
                        <tr>
                            <td class="td_label">E-mail*:</td>
                            <td><input name="email" type="text" class="input_review" value="<?=$arResult["USER"]["EMAIL"];?>" /></td>
                        </tr>
                        <tr class="tr_rating">
                            <td class="td_label">Рейтинг товара:</td>
                            <td>
                                <div class="rating block_raiting_big">
                                    <div class="item" data-id="1" id="rating-1"></div>
                                    <div class="item" data-id="2" id="rating-2"></div>
                                    <div class="item" data-id="3" id="rating-3"></div>
                                    <div class="item" data-id="4" id="rating-4"></div>
                                    <div class="item" data-id="5" id="rating-5"></div>
                                </div>
                                <input name="rating" type="hidden" value="0" id="rating-value" />
                            </td>
                        </tr>
                        <tr>
                            <td class="td_label">Ваше сообщение*:</td>
                            <td><textarea name="description" class="textarea_review"></textarea></td>
                        </tr>

                        <? if (strLen($arResult["CAPTCHA_CODE"]) > 0): ?>
                            <tr>
                                <td class="td_label">Код подтверждения*:</td>
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
                    <input type="submit" class="btn_review btn_review_send" value="Отправить" />
                    <div class="clear"></div>
                        </form>
                </div>
            <?else:?>
                <a class="b-reviews_form_opener" href="/personal/?backurl=<?=urlencode($APPLICATION->GetCurPageParam())?>">
                    <span>Оставить отзыв</span>
                </a>
            <?endif;?>
        </div>
    </div>
</div>