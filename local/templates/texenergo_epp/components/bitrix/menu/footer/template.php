<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<nav class="footer-nav clearfix">
	<ul class="copyright-wrap">
		<li><a href="/"><img src="<?= SITE_TEMPLATE_PATH ?>/img/footer/logo-footer.png" alt="Техэнерго"></a>
		</li>
		<li><span class="copyright">© 1990-<?=date("Y");?><br>&laquo;МФК ТЕХЭНЕРГО&raquo;</span></li>
		<li><span class="copyright">Все права защищены.</span></li>
		<li class="aero_copyright"><div class="copyright"><?$APPLICATION->IncludeFile('/include/copyright.php');?></div></li>
		<li><?$APPLICATION->IncludeFile('/include/counter.php');?></li>
	</ul>
		
	<?$bFirst = true;?>	
	<?foreach($arResult as $arItem):?>
	
		<?if($arItem["DEPTH_LEVEL"] == 1):?>
		
			<?if($arItem["PARAMS"]["not_show_in_footer"] == true):?>
			
			<?else:?>
		
				<?if(!$bFirst):?></ul><?endif;?>
				
				<ul>
				<li><h2><?=$arItem["TEXT"]?></h2></li>
				
				<?$bFirst = false;?>		
			
			<?endif;?>
		
		<?endif;?>
		
		<?if($arItem["DEPTH_LEVEL"] == 2):?>
		
			<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
		
		<?endif;?>
	
	<?endforeach;?>
	
	<li class="line"></li>
<?if (false):?>	<li class="specials promotions"><a href="/catalog/special/">Распродажа</a></li><?endif;?>
	<li class="specials footer_pricelist"><a href="/price_list/">Прайс-листы</a></li>
	<li class="specials footer_catalogs"><a href="/manufacturers/">Каталоги производителей</a></li>	
	
	</ul>
	
</nav>