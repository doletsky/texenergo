<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
IncludeTemplateLangFile(__FILE__);
CUtil::InitJSCore();
$arCurrentSite = CSite::GetByID(SITE_ID)->Fetch();
require_once $_SERVER['DOCUMENT_ROOT'] . '/local/php_interface/secure_auth.php';
?><!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="ru"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name='yandex-verification' content='4474e63f57b83eed' />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=latin,cyrillic' rel='stylesheet'
          type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Exo+2:400,700,600&subset=latin,cyrillic' rel='stylesheet'
          type='text/css'>
    <meta name="yandex" content="noyaca"/>
    <?

    //CJSCore::Init(array("jquery")); // 1.8
    //$APPLICATION->AddHeadScript('https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jquery.js');

    //$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/normalize.min.css');
?>
   <?
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/bootstrap.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/bootstrap-big-grid.css');

    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/owl.carousel.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/owl.carousel2.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/owl.theme.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/catalog.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/popups.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/select2.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/searchbox.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/forms.css', true);
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/forum.css', true);
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/buttons.css', true);
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/integration.css', true);
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/fonts/MaterialDesignIconicFont/material-design-iconic-font.css', true);

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/placeholders.jquery.min.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.masonry.min.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jquery.autocomplete.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jquery.carouFredSel-6.2.1-packed.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/topbtn.js');

    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/js/vendor/fancybox/jquery.fancybox.css');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/fancybox/jquery.fancybox.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/fancybox/helpers/jquery.fancybox-thumbs.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/js/vendor/fancybox/helpers/jquery.fancybox-thumbs.css');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/css/fancybox.custom.css');


    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/validate.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/garlic.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jquery.maskedinput.min.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/chart.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH .'/js/vendor/datepicker/js/datepicker.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH .'/js/vendor/datepicker/css/datepicker.css');


    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/easytabs.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/rem.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/select2.min.js');
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/TweenLite.min.js');
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/TimelineMax.min.js');
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/plugins/CSSPlugin.min.js');
    $APPLICATION->AddHeadScript('//cdnjs.cloudflare.com/ajax/libs/gsap/1.11.6/easing/EasePack.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.carousel.js');

	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.carousel2.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.autoplay.js');
	$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/owl.navigation.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jscrollpane/jquery.jscrollpane.min.js');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/jscrollpane/jquery.mousewheel.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/js/vendor/jscrollpane/jquery.jscrollpane.css');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/quantity/jquery.quantity.js');
    $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH . '/js/vendor/quantity/jquery.quantity.css');
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/cycle/jquery.cycle2.js');

    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/compare.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/favorites.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/vendor/bootstrap.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/bootstrap.file-input.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/main.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/forms.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/modals.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/catalog.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/function.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/basket.js', true);
    $APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/dont_replace/script.js', true);
    ?>

    <? $APPLICATION->ShowHead(); ?>

    <meta name="title" content="<? $APPLICATION->ShowTitle(); ?>">
    <title><? $APPLICATION->ShowTitle(); ?></title>

    <meta name="viewport" content="width=device-width">

    <!--[if lt IE 9]>
    <script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <script>window.html5 || document.write('<script src="<?=SITE_TEMPLATE_PATH?>/js/vendor/html5shiv.js"><\/script>')</script>
    <script src="//ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
    <![endif]-->
<style>
#heroSlider,
.block-stock-main,
#news-slider {
display:none;
}
</style>
</head>
<body itemscope itemtype="http://schema.org/WebPage">
<!--<span>nku.texenergo.ru</span>-->
<!-- Yandex.Metrika counter -->
<script type="text/javascript">
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter1320001 = new Ya.Metrika({
                    id:1320001,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/1320001" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>

<? //include $_SERVER['DOCUMENT_ROOT'] . '/include/infocenter_popup_with_wrap.php'; ?>
<?if(!defined('ERROR_404')):?>
<!-- HEADER START -->
<header class="header" itemscope itemtype="http://schema.org/WPHeader">

<!-- HEADER-TOP END -->

<!-- HEADER-MAIN START -->

<div class="<?= ($APPLICATION->GetCurPage(false) != '/') ? 'header-no-main' : 'header-main'?>" itemscope itemtype="http://schema.org/Organization">
	<meta itemprop="name" content="МФК Texenergo" />
        <div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
         <meta itemprop="addressCountry" content="Россия" />
         <meta itemprop="addressRegion" content="Московская область" />
         <meta itemprop="addressLocality" content="дер. Черная Грязь" />
         <meta itemprop="streetAddress" content="ул. Сходненская дом 65" />
         <meta itemprop="email" content="inform@texenergo.ru" />
         <meta itemprop="postalCode" content="141580"/>
         <meta itemprop="telephone" content="+7 (495) 651-99-99" />
         <meta itemprop="description" content="Производство низковольтной аппаратуры. Оптовые поставки электротехнической продукции. Проектирование и сборка электрощитового оборудования" />
        </div>
  <div class="container">
	  <div class="row blk-top">
		  <div class="header-top col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
    	  <div class="row" itemscope itemtype="http://schema.org/ContactPoint">

<? $menuTop = array (
              "О компании"=>"/about/company/",
              "Бренд"=>"/about/brend/", 
              "Наши клиенты"=>"/about/project/",
              "Складской комплекс"=>"/about/store/",
);?>

            		<ul class="header-top-menu nav navbar-nav col-xlg-12 col-lg-12 col-md-12">
                <li><a href="/"><span class="lnr lnr-home header-top-menu__home_ico"></span></a></li>
<?foreach ($menuTop as $title=>$url) {
  $class = strpos($APPLICATION->GetCurPage(false), $url) !== false ? " class='active'" : "";
  echo '<li><a'.$class.' href="'.$url.'">'.$title.'</a><li>';
}
?>            		
<!--
            <li class="right">
              <a href="#" class="header-account"><span class="lnr lnr-user header-account__ico"></span>Иванов Иван</a>
            </li>
-->
            <li class="right">
              <? if ($USER->isAuthorized()): ?>
                  <a class="logout right" href="<?= $APPLICATION->GetCurPageParam('logout=yes', Array('logout')); ?>">Выйти</a>
                  <a href="/personal/" class="right">
                      <span class="lnr lnr-user header-account__ico"></span>

          			<?
                $userName = $USER->GetFullName();
          			if(empty($userName))
          				//$userName = $USER->GetFirstName();
          				$userName = $USER->GetEmail();
          			?>

          			<span title="<?="Личный кабинет для логина: ".$USER->GetLogin();?>">
                          <?= TruncateText($userName, 40); ?>
                      </span>
                  </a>

              <? else: ?>
                  <a href="/personal/">
                      <span class="lnr lnr-user header-account__ico"></span>
                      <span>Вход / Регистрация</span>
                  </a>
              <?endif; ?>
            </li>
          </ul>

            <ul class="header-middle-menu col-xlg-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">

              <li class="logo col-xlg-3 col-lg-3 col-md-3 col-sm-6 col-xs-6">
               		<div class="logo"><?= ($APPLICATION->GetCurPage(false) != '/') ? '<a href="/">' : ''?><img src="<?= SITE_TEMPLATE_PATH ?>/img/logo.png" alt="Производство низковольтной аппаратуры. Оптовые поставки электротехнической продукции. Проектирование и сборка электрощитового оборудования." width="166"
                                                 height="47" title='<?= ($APPLICATION->GetCurPage(false) != '/') ? 'Перейти на главную страницу' : 'Логотип компании ООО "МФК TEXENERGO"'?>'><?= ($APPLICATION->GetCurPage(false) != '/') ? '</a>' : ''?>
               		<span class="logo-text">Успех начинается здесь!</span>
               		</div>
               </li>
                <li class="col-xlg-3 col-lg-3 col-md-3 col-sm-6 col-xs-6">
                  <span class="header-middle-menu__text">Собственная производственная база в России и за рубежом. Современный складской комплекс.</span>
                </li>      
                <li class="clearfix visible-sm-block"></li>
                <li href="#" class="header-middle-menu__callback col-xlg-2 col-lg-2 col-md-2 col-sm-4 col-xs-12">
                  <span class="lnr lnr-phone-handset header-middle-menu__callback_ico"></span>
                  <a href="#">Заказать звонок</a>
                  <span class="header-middle-menu__callback-number">8 (495) 651-99-99</span>
                </li>      
                <li href="#" class="header-middle-menu__time-work col-xlg-2 col-lg-2 col-md-2 col-sm-4 col-xs-12">
                  <span class="lnr lnr-clock header-middle-menu__time-work-ico"></span> 
                  Время работы. 
                  <span class="header-middle-menu__time">8:30 - 18:00 ПН-ПТ</span>
                </li>      

             <li id="cart" class="cart right col-xlg-2 col-lg-2 col-md-2 col-sm-4 col-xs-12">
                 <? include $_SERVER['DOCUMENT_ROOT'] . '/basket/ajax/small.php'; ?>
             </li>
            </ul>

<? $menuBottom = array (
              "Продукция"=>"/catalog/",
              "Прайс-листы"=>"/price_list/", 
              "Каталоги"=>"/manufacturers/1001463/",
              "Доставка"=>"/eshop/delivery/",
              "Акции"=>"/publication/akcii/",
              "Контакты"=>"/about/contacts/",
);
  $headerMenuCatalog = array (
              "Щитовое оборудование TEXENERGO <span>(1159)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000090",
              "Силовые автоматические выключатели <span>(717)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000005", 
              "Модульное оборудование <span>(1222)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000006",
              "Посты кнопочные <span>(420)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000116",
              "Пускатели электромагнитные, дополнительные устройства и запчасти <span>(4036)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000019",
              "Контакторы электромагнитные <span>(160)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000020",
              "Рубильники, переключатели, разъединители <span>(335)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000111",
              "Светосигнальные индикаторы, арматура, табло <span>(648)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000115",
              "Устройства управления <span>(391)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000114",
              "Предохранители <span>(398)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000009",
              "Кабельные наконечники и гильзы <span>(175)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000010",
              "Выключатели концевые и путевые <span>(51)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000118",
              "Сжимы ответвительные <span>(17)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000119",
              "Кабель-каналы и аксессуары <span>(143)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000145",
              "Шина электротехническая <span>(45)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000129",
              "Сальники кабельные <span>(44)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000130",
              "Шины нулевые и соединительные <span>(83)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000133",
              "Кабельно-проводниковая продукция <span>(311)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000008",
              "Лента киперная, сигнальная, оградительная <span>(10)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000139",
              "Изоляционные материалы <span>(286)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000011",
              "Муфты кабельные <span>(56)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000125",
              "Устройства оповещения <span>(100)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000120",
              "Удлинители, сетевые фильтры <span>(23)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000136",
              "Силовые вилки, розетки, разъемы <span>(70)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000134",
              "Приборы учета и контроля электрической энергии <span>(300)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000013",
              "Мегоомметры <span>(10)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000141",
              "Электроустановочное оборудование <span>(120)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000012",
              "Шкафы, щиты, ящики, боксы и аксессуары <span>(311)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000123",
              "Трансформаторы <span>(973)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000128",
              "Электроизмерительные приборы переносные <span>(120)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000060",
              "Крановое оборудование <span>(61)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000122",
              "Выключатели, переключатели пакетные и кулачковые <span>(659)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000117",
              "Schneider Electric <span>(3547)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000138",
              "Высоковольтное оборудование <span>(14)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000016",
              "Источники света <span>(260)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000121",
              "Светотехника <span>(368)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000022",
              "Реле, датчики контроля <span>(833)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000113",
              "Кабельная оплетка <span>(8)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000155",
              "Кабельные хомуты <span>(83)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000135",
              "Изделия для монтажа электропроводки <span>(105)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000126",
              "Металлорукав и аксессуары <span>(71)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000144",
              "Кабеленесущие изделия <span>(70)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000025",
              "Лотки кабельные <span>(215)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000142",
              "Трубы пластиковые и аксессуары <span>(111)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000143",
              "Скобы металлические <span>(49)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000154",
              "Изолированные наконечники, разъемы, гильзы, зажимы <span>(132)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000030",
              "Изоляторы <span>(71)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000132",
              "Клеммы, блоки зажимов <span>(199)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000124",
              "Средства защиты <span>(180)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000035",
              "Стабилизаторы напряжения, генераторы <span>(4)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000127",
              "Комплектующие для шкафов, ящиков и боксов <span>(30)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000131",
              "Паяльное оборудование <span>(26)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000153",
              "Электродвигатели <span>(117)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000149",
              "Инструмент <span>(69)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000055",
              "ABB <span>(854)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000137",
              "ДКС <span>(332)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000156",
              "Электрообогреватели <span>(17)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000070",
              "Коробки монтажные <span>(160)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000152",
              "Распродажа <span>(257)</span>"=>"https://www.texenergo.ru/catalog/list.html/90000150",
);

?>
          <div class="clearfix visible-sm-block"></div>
          <div class="blk-header-bottom-menu col-xlg-12 col-lg-12 col-md-12">
          <ul class="header-bottom-menu nav navbar-nav">
          <li>
            <button class="cmn-toggle-switch cmn-toggle-switch__rot">
              <span>toggle menu</span>
            </button>
          </li>
<?foreach ($menuBottom as $title=>$url) {
  $class = strpos($APPLICATION->GetCurPage(false), $url) !== false ? " active" : "";
  echo '<li><a class="header-bottom-menu__link'.$class.'" href="'.$url.'">'.$title.'</a><li>';
}
?>
<li class="search right col-xlg-3 col-lg-3 col-md-8 col-sm-6 col-xs-12">
    <?$APPLICATION->IncludeComponent(
        "bitrix:search.form",
        "full-search",
        Array(
            "USE_SUGGEST" => "N",
            "PAGE" => "#SITE_DIR#search/index.php"
        ),
        false
    );?>
</li>
          </ul>
            <ul class="header-menu-catalog row">
               <? $count=0;
                foreach ($headerMenuCatalog as $title=>$url) {
                 $class = strpos($APPLICATION->GetCurPage(false), $url) !== false ? " active" : "";
                 echo '<li class="header-menu-catalog__item col-xlg-4 col-lg-4 col-md-4 col-sm-4 col-xs-12"><a class="header-menu-catalog__link'.$class.'" href="'.$url.'">'.$title.'</a><li>';                  
                 $count++; 
                 echo (($count % 3 == 0) ? '<li class="clearfix visible-lg-block visible-md-block visible-sm-block"></li>' : '');
               }
               ?>
            </ul>
          </div><!--./header-bottom-menu-->
    	  </div>
		  </div><!-- ./header-top -->
	  </div><!-- ./row -->
  </div><!-- container -->
</div><!-- header-bottom-->


<!-- HEADER-MAIN END -->


</header>

<!-- ./header -->

<?endif;?>