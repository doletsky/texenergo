<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="container">
    <div class="twelve position">
        <h1 class="headline">Мои подписки</h1>

        <aside class="subControls">
            <?/*$APPLICATION->IncludeComponent(
                "bitrix:socserv.auth.split",
                "head",
                Array(
                    "SHOW_PROFILES" => "Y",
                    "ALLOW_DELETE" => "Y"
                ),
                false
            );*/?>
            <?/*
            <div class="subMetrics">
                <div class="subMetrics-bonuses subMetrics-divider">
                    <span>Бонусы:</span>
                    <em>600</em>
                </div>
                <div class="subMetrics-bonuses">
                    <span>Ваш кредит:</span>
                    <em>7 000 <i class="rouble">a</i> из 60 000 <i class="rouble">a</i></em>
                </div>
            </div>
            */?>
        </aside>

    </div>
</div>
<div class="container">
    <section class="main main-account main-subscriptions">


        <div class="twelve">
            <section class="subSection clearfix">
                <div class="subSection-left">

                    <section class="subSection-block">
                        <header class="subSection-header">
                            <h1>Рубрики</h1>
                            <!--<a href="#" class="subscribe-off">отписаться от всего</a>-->
                        </header>
                        <div class="subSection-body">
                            <? foreach ($arResult['RUBRICS']['content'] as $i => $arRubric): ?>
                                <div class="subSection-row">
                                    <span
                                        class="subSection-span<? if ($i == (count($arResult['RUBRICS']['content']) - 1)): ?> subSection-span-special<? endif; ?>">
                                        <?= $arRubric['NAME']; ?>
                                    </span>

                                    <div
                                        class="subscribe-toggle subSwitch<? if ($arRubric['CHECKED']): ?> subSwitch-on<? endif; ?>" data-id="<?=$arRubric['ID'];?>"></div>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </section>

					<?$APPLICATION->IncludeComponent(
                        "aero:sale.products.subscribe",
                        "",
                        Array(),
                        $component
                    );?>					
                   
                </div>
                <div class="subSection-right">
                     <section class="subSection-block">
                        <header class="subSection-header">
                            <h1>Рассылка файлов</h1>
                            <!--<a href="#" class="subscribe-off">отписаться от всего</a>-->
                        </header>
                        <div class="subSection-body">
                            <? foreach ($arResult['RUBRICS']['files'] as $i => $arRubric): ?>
                                <div class="subSection-row">
                                    <span class="subSection-span">
                                        <?= $arRubric['NAME']; ?>
                                    </span>

                                    <div
                                        class="subscribe-toggle subSwitch<? if ($arRubric['CHECKED']): ?> subSwitch-on<? endif; ?>" data-id="<?=$arRubric['ID'];?>"></div>
                                </div>
                            <? endforeach; ?>
                        </div>
                    </section>
                </div>
            </section>
        </div>


    </section>
</div>