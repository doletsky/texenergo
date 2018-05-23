<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/components/texenergo/catalog.section/special/style.css', true);
?>
<section class="toolbar clearfix j-toolbar closed">

    <div class="toolbar-tabs">
        <a href="/personal/favorites/" class="tab active favorites" id="favorites">
            <i class="icon"></i>
            <h4 class="title-label">Избранное</h4>
            <span class="title-label-active">Открыть избранное</span>
            <span class="j-favorites-count"><?= count($arResult['FAVORITES']); ?></span>
        </a>
        <a href="/personal/recent/" class="tab recent" id="recent">
            <i class="icon"></i>
            <h4 class="title-label">Просмотренные</h4>
            <span class="title-label-active">Открыть просмотренные</span>
            <span><?= count($arResult['RECENT']); ?></span>
        </a>
        <a href="/catalog/compare/" class="tab compare" id="compare">
            <i class="icon"></i>
            <h4 class="title-label">Сравнение</h4>
            <span class="title-label-active">Открыть сравнение</span>
            <span class="j-compare-count"><?= count($arResult['COMPARE']); ?></span>
        </a>

        <div class="control" id="control">
			<div class="lines">
				<div class="line_l"></div>
				<div class="line_r"></div>
				<div class="line_l"></div>
				<div class="line_r"></div>
			</div>
		</div>
    </div>

    <div class="toolbar-content clearfix">
        <div class="container">

        </div>
    </div>
</section>



