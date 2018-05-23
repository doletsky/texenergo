<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>

<section class="main main-floated main-info">
	<div class="newsContent">
        <?if ($arResult["SCALED_PICTURE"]) {?>
            <figure class="newsFigure">
                <img src="<?=$arResult["SCALED_PICTURE"]["src"]?>">
            </figure>
        <?}?>
        <div class="newsPr">
		    <?=$arResult["DETAIL_TEXT"];?>
        </div>
	</div>
</section>