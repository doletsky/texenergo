<?$layout = $arParams['LAYOUT_NUM'];?>


<?
$i1 = $arResult['ITEMS'][0];
$p1 = $i1['PROPERTIES'];
$img1 = $i1['PREVIEW_PICTURE']['SRC'];

$i2 = $arResult['ITEMS'][1];
$p2 = $i2['PROPERTIES'];
$img2 = $i2['PREVIEW_PICTURE']['SRC'];

$i3 = $arResult['ITEMS'][2];
$p3 = $i3['PROPERTIES'];
$img3 = $i3['PREVIEW_PICTURE']['SRC'];

$i4 = $arResult['ITEMS'][3];
$p4 = $i4['PROPERTIES'];
$img4 = $i4['PREVIEW_PICTURE']['SRC'];

$i5 = $arResult['ITEMS'][4];
$p5 = $i5['PROPERTIES'];
$img5 = $i5['PREVIEW_PICTURE']['SRC'];
?>

<!--<li class="slide container">-->
<li class="slide">

<?switch($layout):
	
	case 1:?>
			
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img1?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>		
			
	<?break;?>
	
	<?case 2:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img1?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-first">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img2?>" alt="<?=$p2['DESCR']['VALUE']?>">
			</a>
		</div>
		
	
	<?break;?>
	
	<?case 3:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img1?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-first">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img2?>" alt="<?=$p2['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-first">
			<a href="<?=$p3['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img3?>" alt="<?=$p3['DESCR']['VALUE']?>">
			</a>
		</div>
	
	<?break;?>
	
	<?case 4:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img1?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-first fixed-half">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img2?>" alt="<?=$p2['DESCR']['VALUE']?>">
			</a>
			<a href="<?=$p3['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img3?>" alt="<?=$p3['DESCR']['VALUE']?>">
			</a>
		</div>
	
	<?break;?>
	
	<?case 5:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img1?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-first fixed-half">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img2?>" alt="<?=$p2['DESCR']['VALUE']?>">
			</a>
			<a class="left" href="<?=$p3['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img3?>" alt="<?=$p3['DESCR']['VALUE']?>">
			</a>
			<a class="left" href="<?=$p4['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img4?>" alt="<?=$p4['DESCR']['VALUE']?>">
			</a>
		</div>
	
	<?break;?>
	
	<?case 6:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img src="<?=$img1?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-second">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img2?>" alt="<?=$p2['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-third">
			<a href="<?=$p3['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img3?>" alt="<?=$p3['DESCR']['VALUE']?>" class="first-half">
			</a>
			<a href="<?=$p4['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img4?>" alt="<?=$p4['DESCR']['VALUE']?>" class="second-half">
			</a>
		</div>
	
	<?break;?>
	
	<?case 7:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img1?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>		
		<div class="banner-third">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img2?>" alt="<?=$p2['DESCR']['VALUE']?>" class="first-half">
			</a>
			<a href="<?=$p3['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img4?>" alt="<?=$p3['DESCR']['VALUE']?>" class="second-half">
			</a>
		</div>
		<div class="banner-third">
			<a href="<?=$p4['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img3?>" alt="<?=$p4['DESCR']['VALUE']?>" class="first-half">
			</a>
			<a href="<?=$p5['LINK']['VALUE']?>">
				<img class="img-responsive" src="<?=$img5?>" alt="<?=$p5['DESCR']['VALUE']?>" class="second-half">
			</a>
		</div>
	
	<?break;?>

<?endswitch;?>

</li>

<?
/* $i1 = $arResult['ITEMS'][0];
$p1 = $i1['PROPERTIES'];
$i2 = $arResult['ITEMS'][1];
$p2 = $i2['PROPERTIES'];
$i3 = $arResult['ITEMS'][2];
$p3 = $i3['PROPERTIES'];
$i4 = $arResult['ITEMS'][3];
$p4 = $i4['PROPERTIES'];
$i5 = $arResult['ITEMS'][4];
$p5 = $i5['PROPERTIES'];

$b1 = '/local/1-1.jpg';
$b2 = '/local/1-2.jpg';
$b4 = '/local/1-4.jpg';
$b4hor = '/local/1-4hor.jpg';
$b8 = '/local/1-8.jpg';
?>

<?for($layout = 1; $layout <= 7; $layout++){?>

<li class="slide container">

<?switch($layout):
	
	case 1:?>
			
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img src="<?=$b1?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>		
			
	<?break;?>
	
	<?case 2:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img src="<?=$b2?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-first">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img src="<?=$b2?>" alt="<?=$p2['DESCR']['VALUE']?>">
			</a>
		</div>
		
	
	<?break;?>
	
	<?case 3:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img src="<?=$b2?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-first">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img src="<?=$b4?>" alt="<?=$p2['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-first">
			<a href="<?=$p3['LINK']['VALUE']?>">
				<img src="<?=$b4?>" alt="<?=$p3['DESCR']['VALUE']?>">
			</a>
		</div>
	
	<?break;?>
	
	<?case 4:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img src="<?=$b2?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-first fixed-half">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img src="<?=$b4hor?>" alt="<?=$p2['DESCR']['VALUE']?>">
			</a>
			<a href="<?=$p3['LINK']['VALUE']?>">
				<img src="<?=$b4hor?>" alt="<?=$p3['DESCR']['VALUE']?>">
			</a>
		</div>
	
	<?break;?>
	
	<?case 5:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img src="<?=$b2?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-first fixed-half">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img src="<?=$b4hor?>" alt="<?=$p2['DESCR']['VALUE']?>">
			</a>
			<a class="left" href="<?=$p3['LINK']['VALUE']?>">
				<img src="<?=$b8?>" alt="<?=$p3['DESCR']['VALUE']?>">
			</a>
			<a class="left" href="<?=$p4['LINK']['VALUE']?>">
				<img src="<?=$b8?>" alt="<?=$p4['DESCR']['VALUE']?>">
			</a>
		</div>
	
	<?break;?>
	
	<?case 6:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img src="<?=$b2?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-second">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img src="<?=$b4?>" alt="<?=$p2['DESCR']['VALUE']?>">
			</a>
		</div>
		<div class="banner-third">
			<a href="<?=$p3['LINK']['VALUE']?>">
				<img src="<?=$b8?>" alt="<?=$p3['DESCR']['VALUE']?>" class="first-half">
			</a>
			<a href="<?=$p4['LINK']['VALUE']?>">
				<img src="<?=$b8?>" alt="<?=$p4['DESCR']['VALUE']?>" class="second-half">
			</a>
		</div>
	
	<?break;?>
	
	<?case 7:?>
	
		<div class="banner-first">
			<a href="<?=$p1['LINK']['VALUE']?>">
				<img src="<?=$b2?>" alt="<?=$p1['DESCR']['VALUE']?>">
			</a>
		</div>		
		<div class="banner-third">
			<a href="<?=$p2['LINK']['VALUE']?>">
				<img src="<?=$b8?>" alt="<?=$p2['DESCR']['VALUE']?>" class="first-half">
			</a>
			<a href="<?=$p3['LINK']['VALUE']?>">
				<img src="<?=$b8?>" alt="<?=$p3['DESCR']['VALUE']?>" class="second-half">
			</a>
		</div>
		<div class="banner-third">
			<a href="<?=$p4['LINK']['VALUE']?>">
				<img src="<?=$b8?>" alt="<?=$p4['DESCR']['VALUE']?>" class="first-half">
			</a>
			<a href="<?=$p5['LINK']['VALUE']?>">
				<img src="<?=$b8?>" alt="<?=$p5['DESCR']['VALUE']?>" class="second-half">
			</a>
		</div>
	
	<?break;?>

<?endswitch;?>

</li>

<?} */?>