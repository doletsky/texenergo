<?if(!defined('ERROR_404')):?>

	<?if($_REQUEST['ajax_call'] == 'y') die();?>


	<!-- FOOTER START -->
	<footer class="footer clearfix" itemscope itemtype="http://schema.org/WPFooter">
		<div class="container footer__inner">
		  <div class="row footer-content">
       <div class="col-xlg-2 col-lg-2 col-md-2 col-sm2 col-xs-12"> 
				<div class="footer-logo">
					<img class="img-responsive" src="/local/templates/texenergo_sale/img/logo-footer.png" 
            alt="Производство низковольтной аппаратуры. Оптовые поставки электротехнической продукции. Проектирование и сборка электрощитового оборудования." 
            width="116" height="33" title="Логотип компании ООО &quot;МФК TEXENERGO&quot;">
				</div>
  			<div class="blk-copyright">
          <span class="copyright">© 1990-<?=date("Y");?><br>&laquo;МФК ТЕХЭНЕРГО&raquo;<br>Все права защищены.</span>
  		  </div>
        <div class="aero_url">
        	<a href="http://aeroidea.ru/" target="_blank">Разработано в Aero</a>
        	<div class="aero_logo">
        		<img class="img-responsive" alt="AERO" src="/local/templates/texenergo/img/logo_aero.png" width="60">
        	</div>
        </div>
				<div><?$APPLICATION->IncludeFile('/include/counter.php');?></div>
      </div>
			<div class="footer-menu col-xlg-10 col-lg-10 col-md-10 col-sm-10 col-xs-12">
			   <div class="row">
			     <div class="col-xlg-3 col-lg-3 col-md-3 col-sm-6 col-xs-6">		
      				<div class="menu-catalog">
        				<h3 class="footer__header">Продукция</h3>
        				<ul>
        				  <li><a href="/catalog/">Перейти в каталог</a></li>
        				  <li><a href="/eshop/delivery/">Доставка</a></li>
        				  <li><a href="/eshop/reclamation/">Рекламации</a></li>
        				</ul>
      				</div>
			     </div>	
			     <div class="col-xlg-3 col-lg-3 col-md-3 col-sm-6 col-xs-6">		
    				<div class="menu-help">	
      				<h3 class="footer__header">О нас</h3>
      				<ul>
        				  <li><a href="/about/company/">Информация о компании</a></li>
        				  <li><a href="/about/brend/">Бренд</a></li>
        				  <li><a href="/about/project/">Наши клиенты</a></li>
        				  <li><a href="/about/store/">Складской комплекс</a></li>
        				  <li><a href="/about/vacancy/">Вакансии</a></li>
        				  <li><a href="/about/contacts/">Контакты</a></li>
      				</ul>
    				</div>
			    </div>
			     <div class="col-xlg-3 col-lg-3 col-md-3 col-sm-6 col-xs-6">		
    				<div class="menu-help">	
      				<h3 class="footer__header">Публикации</h3>
      				<ul>
        				  <li><a href="/publication/novosti_texenergo/">Новости компании</a></li>
        				  <li><a href="/publication/novye_postupleniya/">Новинки оборудования</a></li>
        				  <li><a href="/publication/akcii/">Акции</a></li>
        				  <li><a href="/publication/tendery/">Тендеры</a></li>
        				  <li><a href="/publication/statyi/">Статьи</a></li>
        				  <li><a href="/publication/biblioteka_elektrotekhnika/">Библиотека электротехника</a></li>
        				  <li><a href="/publication/video/">Видео</a></li>
        				  <li><a href="/publication/obyavleniya/">Объявления</a></li>
      				</ul>
    				</div>
			    </div>
			     <div class="col-xlg-3 col-lg-3 col-md-3 col-sm-6 col-xs-6">		
    				<div class="menu-help">	
      				<h3 class="footer__header">Помощь</h3>
      				<ul>
               <li><a href="/user_help/call_centr/">Call-центр</a></li>
               <li><a href="/user_help/work_web/">Работа с сайтом</a></li>
               <li><a href="/forum/">Форум</a></li>
      				</ul>
              <ul class="footer-networks">
                <li class="line"></li>
                <li class="footer-snet">TEXENERGO в соцсетях</li>
                <li class="footer-facebook"><a href="https://www.facebook.com/texnru">Facebook</a></li>
                <li class="footer-vk"><a href="https://vk.com/texenergo">ВКонтакте</a></li>
                <li class="footer-twitter"><a href="https://twitter.com/Texenergo_Ru">Twitter</a></li>
                <li class="footer-ok"><a href="https://ok.ru/texenergo">Одноклассники</a></li>
                <li class="footer-youtube"><a href="https://www.youtube.com/channel/UCpe3yrjA1P7fwhWVJrKjSIQ">YouTube</a></li>	
              </ul>
    				</div>
			    </div>
			  </div><!-- /.row-->
			</div><!-- /.menu1-->
		  </div><!-- /.row -->
		  <div class="row">
        <div class="footer-bottom col-xlg-12 col-lg-12 col-md-12">
          <div class="line"></div>
          <a href="/price_list/" class="footer-pricelist left">Прайс-листы</a>
          <a href="/manufacturers/" class="footer-catalogs left">Каталоги производителей</a>
      </div>
	  </div><!-- /.row-->
  </div>
		<!-- container -->

	</footer>
	<!-- FOOTER END -->



<?endif;?>

<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
ga('create', 'UA-2697871-1', 'auto');
ga('send', 'pageview');
</script>
</body>

</html>
