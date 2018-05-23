<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="compSidebar">
    <div class="compControl">
        <div class="compButtons">
            <? /*<button class="rounded rounded-comp-red">по характеристикам</button>
            <button class="rounded rounded-comp">по особенностям</button>*/
            ?>
            <? if (count($arResult['ITEMS']) > 1 || $arResult['OWN_ONLY']): ?>
                <div class="l-switch-row">
                    <div class="group-switch group-switch-catalog vendor">
                        <div class="subSwitch<? if ($arResult['OWN_ONLY']): ?> subSwitch-on<? endif; ?>"></div>
                        <span>Только товары TEXENERGO</span>
                    </div>
                </div>
            <? endif; ?>
            <? if (count($arResult['ITEMS']) > 1): ?>
                <div class="l-switch-row">
                    <div class="group-switch group-switch-catalog similar">
                        <div class="subSwitch"></div>
                        <span>Только различия</span>
                    </div>
                </div>
            <? endif; ?>
        </div>
    </div>
    <? if (!empty($arResult['SHOW_PROPERTIES'])): ?>
        <div class="compSpecs compSpecs-header">
            <h1>характеристики</h1>
            <ul>
                <? foreach ($arResult['SHOW_PROPERTIES'] as $arProp): ?>
                    <li class="specCell specCell-header <?= ($arProp['SIMILAR'] == 'Y' ? 'similar' : ''); ?>">
                        <span><?= $arProp['NAME']; ?></span></li>
                <? endforeach; ?>
            </ul>
        </div>
    <? endif; ?>
</div>
<div class="compBlock" id="compareContainer">
    <div class="compCanvas" style="width:<?= (count($arResult['ITEMS']) * 177); ?>px;">
        <? foreach ($arResult['ITEMS'] as $arItem): ?>
            <div
                class="compItem<? if ($arItem['PROPERTIES']['IS_OWN']['VALUE'] == 'Y'): ?> main-vendor<? endif; ?>">
                <a href="#" rel="<?= $arItem['ID']; ?>" class="compare-remove"
                   title="Убрать из сравнения">Убрать</a>

                <div class="compMain">                   
                    <figure class="compImage">
                        <div class="picture">
                            <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                                <img src="<?= $arItem['PICTURE']; ?>" alt="<?= $arItem['NAME']; ?>">
                            </a>
                        </div>
                        <figcaption>
                            <span class="name">
                                <a href="<?= $arItem['DETAIL_PAGE_URL']; ?>">
                                    <?= $arItem['NAME']; ?>
                                </a>
                            </span>
                            <span class="sku sku-pline"><?= $arItem['SKU']; ?></span>

                        </figcaption>
                    </figure>
                    <? /*
                    <div class="compReview">
                        <img src="<?= SITE_TEMPLATE_PATH; ?>/img/product/review-rating.png" alt="рейтинг покупателей">
                        <span>4 отзыва</span>
                    </div>*/
                    ?>

                    <span class="pLinePrice pLinePrice-centered"><?= $arItem['PRICE']; ?> <i
                            class="rouble">a</i></span>
                    <? if ($arItem['OLD_PRICE']): ?>
                        <span class="pLineOld pLineOld-centered"><?= $arItem['OLD_PRICE']; ?>Р</span>
                    <? endif; ?>

                    <? /*
                    <span class="pBonuses pBonuses-centered">10 бонусов</span>
                    */
                    ?>
                </div>
                <div class="compSpecs compSpecs-body">
                    <ul>
                        <? foreach ($arResult['SHOW_PROPERTIES'] as $arProp): ?>
                            <?
                            $val = $arItem['PROPERTIES'][$arProp['CODE']]['VALUE'];
                            if (is_array($val)) {
                                if (count($val) == 1) {
                                    $val = reset($val);
                                } else {
                                    $val = implode(', ', $val);
                                }
                            }
                            ?>
                            <li class="specCell <?= ($arProp['SIMILAR'] == 'Y' ? 'similar' : ''); ?>">
                                <span><?= $val; ?></span></li>
                        <? endforeach; ?>
                    </ul>
                </div>
            </div>
        <? endforeach; ?>
    </div>
</div>