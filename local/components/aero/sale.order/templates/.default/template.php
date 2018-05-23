<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?if($_REQUEST['ajax_call'] == 'y'):?>
	<script>
	var oHead = document.getElementById('dcalcwrap');
	var oScript= document.createElement("script");
	oScript.type = "text/javascript";
	oScript.src="<?=$templateFolder?>/script.js";
	oHead.appendChild( oScript);
	
	var oScript= document.createElement("script");
	oScript.type = "text/javascript";
	oScript.src="<?=SITE_TEMPLATE_PATH?>/js/modals.js";
	oHead.appendChild( oScript);
	
	var css = document.createElement("link")
	css.setAttribute("rel", "stylesheet")
	css.setAttribute("type", "text/css")
	css.setAttribute("href", '<?=$templateFolder?>/style.css')
	oHead.appendChild(css);
	</script>	
<?endif;?>

<? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/head.php"); ?>

<div class="container" id="dcalcwrap">
    <div class="twelve">

        <section class="main main-account main-cart clearfix">

            <? if (!empty($arResult['ERRORS'])): ?>
                <div class="optionsWrapper">
                    <div class="shippingOptions clearfix">
                        <? foreach ($arResult['ERRORS'] as $sError): ?>
                            <?= ShowError($sError); ?>
                        <? endforeach; ?>
                    </div>
                </div>
            <? endif; ?>

            <section class="cartItems cartItems-approve cartItems-payment">
                <? if ($arResult['PERSON_TYPE_ID'] == SALE_PERSON_YUR && empty($arResult['COMPANY'])): ?>
                    <div class="optionsWrapper">
                        <header class="blockHeader">
                            <h1>Реквизиты компании</h1>
                        </header>

                        <section class="shippingOptions clearfix form">
                            <div class="notetext">
                                Для оформления заказа необходимо заполнить реквизиты компании в разделе "<a
                                    href="/personal/contacts/">Мои данные</a>".
                            </div>
                            <a href="/personal/contacts/" class="button">Заполнить реквизиты</a>
                        </section>
                    </div>

                <? else: ?>
                    <div class="profilesWrapper"><? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/profiles.php"); ?></div>
                    <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/delivery.php"); ?>
                <?endif; ?>
				
            </section>
            <aside class="cartAside">
                <? include($_SERVER["DOCUMENT_ROOT"] . $templateFolder . "/basket.php"); ?>
            </aside>

        </section>
    </div>
</div>


