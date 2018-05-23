<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<section class="subSection-block">
    <header class="subSection-header">
        <h1>Отслеживание товаров</h1>
        <?if(!empty($arResult['ITEMS'])):?>
			<a href="#" class="products-off stop-tracking-all" data-userid="<?=$USER->GetID()?>">отписаться от всего</a>
		<?endif;?>
    </header>
    <div class="subSection-body">
        <? if (!empty($arResult['ITEMS'])): ?>
			
            <? foreach ($arResult['ITEMS'] as $arItem): ?>
                <div class="subSection-row">
                                <span
                                    class="subSection-span subSection-span-arrow"><a href="<?=$arItem['PROPERTY_PRODUCT_ID_DETAIL_PAGE_URL']?>"><?= $arItem['PROPERTY_PRODUCT_ID_NAME']; ?></a></span>

                    <div class="products-toggle subSwitch subSwitch-on productTracking-toggle" data-product="<?=$arItem['PROPERTY_PRODUCT_ID_VALUE']?>" data-userid="<?=$USER->GetID()?>"></div>
                </div>
            <? endforeach; ?>
        <? else: ?>
            <p>Вы пока не подписаны ни на один товар.</p>
        <?endif; ?>
    </div>
</section>