<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<?
$fieldID = substr(md5($arParams["CITY_INPUT_NAME"]), 0, 8) . '_' . rand(0, 9999);
$containerId = (empty($arParams['CONTAINER_ID'])) ? 'dcalclocations' : $arParams['CONTAINER_ID'];
?>

<?if($_REQUEST['ajax_call'] == 'y'):?>
	<script>
	var oHead = document.getElementById('<?=$containerId?>');
	var oScript= document.createElement("script");
	oScript.type = "text/javascript";
	oScript.src="<?=$templateFolder?>/script.js";
	oHead.appendChild( oScript);
	
	var css = document.createElement("link")
	css.setAttribute("rel", "stylesheet")
	css.setAttribute("type", "text/css")
	css.setAttribute("href", '<?=$templateFolder?>/style.css')
	oHead.appendChild(css);
	</script>	
<?endif;?>

<?if(!$arParams['HIDE_WRAP']):?>

<div class="clearfix address-row" id="<?=$containerId?>">

<?endif;?>

    <div class="cartForm-field">
            <span class="formLabel">Город</span>

            <input name="<?= $arParams["CITY_INPUT_NAME"] ?>_val"
                   id="<?= $fieldID ?>_val" value="<?= $arResult["LOCATION_STRING"] ?>"
                   class="location-suggest <?= $arParams['INPUT_CLASS']; ?>" type="text" autocomplete="off"
                   data-field="<?= $fieldID ?>" data-group="<?= IntVal($arParams['LOCATION_GROUP_ID']); ?>">
            <input type="hidden" name="<? echo $arParams["CITY_INPUT_NAME"] ?>"
                   id="<?= $fieldID ?>" value="<?= $arParams['LOCATION_VALUE'] ?>">

    </div>
	
	<?if(!$arParams['HIDE_INDEX']):?>
	
    <div class="cartForm-field no-margin">
            <span class="formLabel">Индекс</span>
            <input class="formInput zip-suggest <?= $arParams['INPUT_CLASS']; ?>" data-title="Индекс"
                   data-rules="integer|exact_length[6]"
                   type="text"
                   name="<?= $arParams['ZIP_INPUT_NAME']; ?>"
                   value="<?= $arParams['ZIP_VALUE']; ?>">
    </div>
	
	<?endif;?>

<?if(!$arParams['HIDE_WRAP']):?>	
	
</div>

<?endif;?>