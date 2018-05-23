<?foreach($arResult['SECTIONS'] as $arSection):?>
	 
	 <li><a href="<?=$arSection['SECTION_PAGE_URL']?>"><?=$arSection['NAME']?></a></li>
	 
<?endforeach;?>