<nav class="menu-drop clearfix hidden">

<? 
$parts = array_chunk($arResult, ceil(count($arResult) / 2));
foreach($parts as $part):?>

<ul>
	
	<?foreach($part as $arItem):?>
	
		<li><a href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a></li>
	
	<?endforeach;?>
	
</ul>
	
<?endforeach;?>

</nav>