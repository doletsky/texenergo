<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="tagline container">
    <?$APPLICATION->IncludeComponent(
        "bitrix:main.include",
        "",
        Array(
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => "/include/main_company_text.php"
        )
    );?>
    <button class="rounded collapsed" id="techenergo-more-btn">Подробнее о техэнерго</button>
</div>

<div class="main-about mainpage-about" id="techenergo-more" style="margin-top:50px;display:none;">
    <section class="manHero manHero-about">
        <div class="manHeadline">
            <div class="headline-header">информация о компании</div>
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                "",
                Array(
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => "/include/main_company_text_block.php"
                )
            );?>
        </div>
    </section>
    <div class="manCategories clearfix">
        <? $i = 1;?>
        <?foreach($arResult['ITEMS'] as $arItems):?>
        <a href="<?=$arItems["LINK"]?>">
            <article class="manCategory<?if ($i == 2):?> h-noMargin-right<?endif;?> clearfix">
                <img alt="<?=$arItems["NAME"]?>" src="<?=$arItems["PREVIEW_PICTURE"]["SRC"]?>">
                <div class="b-manCategory-copy">
                    <h2><?=$arItems["NAME"]?></h2>
                    <p><?=$arItems["PREVIEW_TEXT"]?></p>
                </div>
            </article>
        </a>
            <?
            $i++;
            if ($i == 3) {$i = 1;}
            ?>
        <?endforeach;?>
    </div>
    <button class="rounded" id="techenergo-less-btn">Свернуть</button>
</div>
